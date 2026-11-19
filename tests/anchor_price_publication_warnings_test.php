<?php
// Run each real OpenCart model in its own process because they share a class name.
if (!isset($argv[1])) {
	foreach (array('admin', 'catalog') as $surface) {
		passthru(escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg(__FILE__) . ' ' . escapeshellarg($surface), $status);
		if ($status !== 0) {
			exit($status);
		}
	}
	exit(0);
}

$surface = $argv[1];
if (!in_array($surface, array('admin', 'catalog'), true)) {
	fwrite(STDERR, "Unknown publication model.\n");
	exit(1);
}
$root = dirname(__DIR__);
$temporary_directory = sys_get_temp_dir() . '/anchor-publication-test-' . uniqid('', true);
mkdir($temporary_directory, 0700, true);
define('DIR_SYSTEM', $root . '/system/');
define('DIR_DOWNLOAD', $temporary_directory . '/');
define('DB_PREFIX', 'test_');
define('DB_DATABASE', 'anchor_publication_test');
define('HTTPS_CATALOG', 'https://example.test/');
require_once($root . '/system/engine/model.php');
require_once($root . '/system/engine/registry.php');
require_once($root . '/' . $surface . '/model/extension/module/anchor_price.php');

function assertPublicationCondition($condition, $message) {
	if (!$condition) {
		throw new Exception($message);
	}
}

function removePublicationTestFiles($directory) {
	foreach (glob($directory . '/anchor_price/*') ?: array() as $path) {
		unlink($path);
	}
	if (is_dir($directory . '/anchor_price')) {
		rmdir($directory . '/anchor_price');
	}
}

register_shutdown_function(function () use ($temporary_directory) {
	removePublicationTestFiles($temporary_directory);
	if (is_file(DIR_DOWNLOAD . 'pj1.zip')) { unlink(DIR_DOWNLOAD . 'pj1.zip'); }
	rmdir($temporary_directory);
});

class PublicationTestConfig {
	public function get($key) {
		$values = array(
			'config_store_id' => 0, 'config_language_id' => 1, 'config_language' => 'hr-hr',
			'config_customer_group_id' => 1, 'config_currency' => 'EUR',
			'config_address' => 'Testna 1', 'config_url' => HTTPS_CATALOG,
			'config_ssl' => HTTPS_CATALOG, 'module_anchor_price_default_unit' => 'kom'
		);
		return isset($values[$key]) ? $values[$key] : null;
	}
}

class PublicationTestTax {
	public function calculate($price, $tax_class_id, $calculate = true) {
		return $price * 1.25;
	}
}

// Only the database is replaced: generation, CSV writing, checksums, pair validation,
// publication transactions and failure cleanup all execute the production methods.
class PublicationTestDatabase {
	public $products;
	public $publications = array();
	public $released_locks = 0;
	public $lock_available = true;
	public $fail_atomic_publication = false;
	private $last_id = 0;
	private $affected = 0;
	private $transaction;

	public function __construct(array $products) {
		$this->products = $products;
	}

	public function escape($value) { return addslashes($value); }
	public function getLastId() { return $this->last_id; }
	public function countAffected() { return $this->affected; }

	private function result(array $rows = array()) {
		return (object)array('rows' => $rows, 'row' => $rows ? $rows[0] : array(), 'num_rows' => count($rows));
	}

	private function assignments($sql) {
		preg_match_all("/(?:^|,\\s*)([a-z_][a-z0-9_]*)\\s*=\\s*('(?:\\\\.|[^'])*'|NULL)/i", $sql, $matches, PREG_SET_ORDER);
		$values = array();
		foreach ($matches as $match) {
			$values[$match[1]] = $match[2] === 'NULL' ? null : stripslashes(substr($match[2], 1, -1));
		}
		return $values;
	}

	private function matches($row, $where) {
		if (strpos($where, "LEFT(filename, 9) <> 'naknadno_'") !== false && strpos($row['filename'], 'naknadno_') === 0) {
			return false;
		}
		foreach (array('publication_id', 'store_id', 'location_code', 'batch_key', 'status') as $field) {
			if (preg_match("/\\b" . $field . " = '([^']*)'/", $where, $match) && (string)$row[$field] !== $match[1]) {
				return false;
			}
		}
		foreach (array('publication_id', 'status') as $field) {
			if (preg_match('/\\b' . $field . ' IN \\(([^)]+)\\)/', $where, $match)) {
				$values = array_map(function ($value) { return trim($value, " '"); }, explode(',', $match[1]));
				if (!in_array((string)$row[$field], $values, true)) {
					return false;
				}
			}
		}
		// No fixture is older than the retention cutoff.
		return !(strpos($where, 'published_at < ') !== false && strpos($where, 'published_at >= ') === false);
	}

	public function query($sql) {
		$this->affected = 0;
		if ($sql === 'START TRANSACTION') {
			$this->transaction = $this->publications;
			return $this->result();
		}
		if ($sql === 'ROLLBACK') {
			$this->publications = $this->transaction;
			return $this->result();
		}
		if ($sql === 'COMMIT') { return $this->result(); }
		if (strpos($sql, 'GET_LOCK(') !== false) {
			return $this->result(array(array('acquired' => (int)$this->lock_available)));
		}
		if (strpos($sql, 'RELEASE_LOCK(') !== false) {
			$this->released_locks++;
			return $this->result();
		}
		if (strpos($sql, 'SHOW TABLES LIKE ') === 0 || strpos($sql, 'SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES') === 0) {
			return $this->result(array(array('exists' => 1)));
		}
		if (strpos($sql, 'SELECT pa.product_id, ad.name') === 0) { return $this->result(); }
		if (strpos($sql, 'SELECT language_id FROM ') === 0) {
			return $this->result(array(array('language_id' => 1)));
		}
		if (strpos($sql, 'SELECT p.product_id, p.model, p.sku, p.ean') === 0) {
			return $this->result($this->products);
		}
		// Synchronization and legacy reconciliation have no rows to change in these fixtures.
		if (strpos($sql, 'SELECT p.product_id, p.price, p.tax_class_id') === 0 || strpos($sql, 'SELECT ap.*, p.price AS product_price') === 0) {
			return $this->result();
		}
		if (strpos($sql, 'SELECT COALESCE(MAX(sequence_no)') === 0) {
			return $this->result(array(array('sequence_no' => $this->last_id + 1, 'next_sequence' => $this->last_id + 1)));
		}
		if (strpos($sql, 'INSERT INTO `test_anchor_price_publication` SET ') === 0) {
			$row = $this->assignments(substr($sql, strpos($sql, ' SET ') + 5));
			$row['publication_id'] = ++$this->last_id;
			$row += array('error_message' => null, 'published_at' => null);
			$this->publications[$this->last_id] = $row;
			$this->affected = 1;
			return $this->result();
		}
		if (strpos($sql, 'UPDATE `test_anchor_price_publication` SET ') === 0) {
			list($set, $where) = explode(' WHERE ', substr($sql, strpos($sql, ' SET ') + 5), 2);
			$values = $this->assignments($set);
			if ($this->fail_atomic_publication && isset($values['status']) && $values['status'] === 'published') {
				return $this->result();
			}
			foreach ($this->publications as $id => $row) {
				if ($this->matches($row, $where)) {
					$this->publications[$id] = array_replace($row, $values);
					$this->affected++;
				}
			}
			return $this->result();
		}
		if (preg_match('/^SELECT .+ FROM `test_anchor_price_publication` WHERE (.+)$/', $sql, $match)) {
			$rows = array();
			foreach ($this->publications as $row) {
				if ($this->matches($row, $match[1])) { $rows[] = $row; }
			}
			if (strpos($sql, 'publication_id DESC') !== false) {
				usort($rows, function ($left, $right) { return $right['publication_id'] - $left['publication_id']; });
			} else {
				usort($rows, function ($left, $right) { return strcmp($left['location_code'], $right['location_code']); });
			}
			if (substr($sql, -7) === 'LIMIT 1') { $rows = array_slice($rows, 0, 1); }
			return $this->result($rows);
		}
		throw new Exception('Unrecognized test database query: ' . $sql);
	}
}

trait PublicationTestFeed {
	protected function fetchPj1(DateTime $now) {
		return BebesBbmPriceList::readZip(DIR_DOWNLOAD . 'pj1.zip', $now);
	}
}

class PublicationFixturePublisher extends AnchorPricePublication {
	use PublicationTestFeed;
}

class PublicationFixtureModel extends ModelExtensionModuleAnchorPrice {
	use PublicationTestFeed;
	protected function publisher() { return new PublicationFixturePublisher($this->registry); }
}

function createPj1Fixture($price = '24,95', $warehouse = '00001') {
	$row = array('PJ1 proizvod', '000109', 'Marka', '0,5', 'kg', $price, 'NE', '49,90', 'INT-00109', '4006381333931', '26,00', $warehouse);
	$file = fopen('php://temp', 'w+b');
	fputcsv($file, array_merge(BebesPriceCsv::HEADER, array('Skladište')), ';', '"', '');
	fputcsv($file, $row, ';', '"', '');
	rewind($file);
	$zip = new ZipArchive();
	$zip->open(DIR_DOWNLOAD . 'pj1.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);
	$zip->addFromString('cjenik.csv', stream_get_contents($file));
	$zip->close();
	fclose($file);
}

function publicationTestModel($database) {
	$registry = new Registry();
	$registry->set('db', $database);
	$registry->set('config', new PublicationTestConfig());
	$registry->set('tax', new PublicationTestTax());
	return new PublicationFixtureModel($registry);
}

function generateTestPublications($model, $surface, $force = true) {
	if ($surface === 'catalog') { return $model->generatePublication($force); }
	try {
		$locations = $model->generateDailyPublications(0, 0, $force);
		return array('success' => true, 'locations' => $locations, 'warnings' => $locations[0]['warnings']);
	} catch (Exception $exception) {
		return array('success' => false, 'error' => $exception->getMessage());
	}
}

$base = array(
	'product_id' => 101, 'model' => 'M101', 'sku' => 'SKU101', 'ean' => '4006381333931', 'jan' => '', 'isbn' => '',
	'net_quantity' => '250', 'unit' => 'ml', 'price' => '12.00', 'regular_price' => '12.00', 'special' => '8.00', 'special_price' => '8.00',
	'quantity' => 5, 'stock_status_id' => 1, 'tax_class_id' => 1, 'manufacturer_id' => 4,
	'name' => 'Test &amp; proizvod', 'product_name' => 'Test &amp; proizvod', 'manufacturer' => 'Test marka',
	'stock_status' => 'Na zalihi', 'anchor_price_id' => 201, 'verification_status' => 'confirmed',
	'anchor_gross_price' => '17.50', 'reference_date' => '2026-09-10', 'currency_code' => 'EUR', 'source' => 'admin_nn132'
);
$products = array(
	$base,
	array_replace($base, array('product_id' => 102, 'anchor_price_id' => null, 'verification_status' => null)),
	array_replace($base, array('product_id' => 103, 'verification_status' => 'pending', 'name' => '', 'product_name' => '', 'model' => ' ', 'manufacturer_id' => 0, 'manufacturer' => '', 'ean' => '4006381333932', 'jan' => 'bad', 'isbn' => '9780306406158')),
	array_replace($base, array('product_id' => 104, 'ean' => '4006381333932', 'jan' => '96385074')),
	array_replace($base, array('product_id' => 105, 'ean' => '', 'isbn' => '9780306406157', 'anchor_gross_price' => '0', 'special' => null, 'special_price' => null)),
	array_replace($base, array('product_id' => 106, 'verification_status' => 'rejected'))
);
$expected_warnings = array(
	102 => array('anchor_missing'),
	103 => array('anchor_unconfirmed', 'name_missing', 'model_missing', 'manufacturer_missing', 'ean_invalid', 'jan_invalid', 'isbn_invalid'),
	104 => array('ean_invalid'),
	106 => array('anchor_unconfirmed')
);

try {
	createPj1Fixture();
	$database = new PublicationTestDatabase($products);
	$model = publicationTestModel($database);
	$result = generateTestPublications($model, $surface);
	assertPublicationCondition(!empty($result['success']), 'Product data warnings must allow daily publication: ' . json_encode($result));
	$actual_warnings = array();
	foreach ($result['warnings'] as $warning) { $actual_warnings[$warning['product_id']] = $warning['reasons']; }
	assertPublicationCondition($actual_warnings === $expected_warnings, 'Warnings must identify each product and every data problem.');
	if ($surface === 'admin') {
		assertPublicationCondition($model->getPublicationWarnings() === $result['warnings'], 'Admin review must expose the same product warnings as publication.');
		foreach ($result['locations'] as $location) {
			assertPublicationCondition($location['warnings'] === $result['warnings'], 'Each admin publication must retain its product warnings.');
		}
	}
	assertPublicationCondition(count($database->publications) === 2, 'Both sales locations must be generated.');
	$batch_keys = array();
	$csv_bodies = array();
	foreach ($database->publications as $publication) {
		assertPublicationCondition($publication['status'] === 'published', 'Both locations must be atomically published.');
		assertPublicationCondition((int)$publication['product_count'] === ($publication['location_code'] === 'PJ1' ? 1 : count($products)), 'The locations must have independently sourced assortments.');
		$batch_keys[] = $publication['batch_key'];
		$path = DIR_DOWNLOAD . $publication['relative_path'];
		assertPublicationCondition(hash_file('sha256', $path) === $publication['checksum_sha256'], 'Published checksum must match the CSV file.');
		$handle = fopen($path, 'rb');
		assertPublicationCondition(fread($handle, 3) === "\xEF\xBB\xBF", 'CSV encoding marker');
		$header = fgetcsv($handle, 0, ';', '"', '');
		assertPublicationCondition($header === BebesPriceCsv::HEADER && count($header) === 11, 'Legal header and order');
		assertPublicationCondition((bool)preg_match('/^59774997564_.+_' . $publication['location_code'] . '_ROBA_\d+_\d{8}_\d{4}\.csv$/D', $publication['filename']), 'Legal filename');
		$rows = array();
		while (($row = fgetcsv($handle, 0, ';', '"', '')) !== false) { $rows[] = $row; }
		fclose($handle);
		if ($publication['location_code'] === 'PJ1') {
			assertPublicationCondition(count($rows) === 1 && $rows[0][1] === '000109' && $rows[0][8] === 'INT-00109' && $rows[0][10] === '26,00', 'BBM code, independent barcode and base price');
			continue;
		}
		foreach (array(1, 2, 5) as $index) {
			assertPublicationCondition($rows[$index][10] === '', 'Unverified historical anchors must be blank.');
		}
		assertPublicationCondition($rows[0] === array('Test & proizvod','M101','Test marka','0,25','l','10,00','DA','40,00','4006381333931','4006381333931','17,50'), 'Gross special, net quantity, unit price and separate EAN.');
		assertPublicationCondition($rows[2][8] === '' && $rows[2][9] === '', 'Invalid codes must be blank.');
		assertPublicationCondition($rows[3][8] === '96385074' && $rows[3][9] === '' && $rows[4][8] === '9780306406157', 'JAN and ISBN may supply the barcode.');
		assertPublicationCondition($rows[4][10] === '0,00' && $rows[4][5] === '15,00', 'Confirmed zero and ordinary price.');
	}
	assertPublicationCondition($batch_keys[0] === $batch_keys[1], 'The two locations are published atomically.');
	assertPublicationCondition($database->released_locks === 1, 'Successful publication must release its lock.');

	$reused = generateTestPublications($model, $surface, false);
	assertPublicationCondition(!empty($reused['success']) && count($database->publications) === 2 && $reused['warnings'] === $result['warnings'], 'A valid existing pair must be reusable while retaining product warnings.');

	// Late replacements have today's actual publication timestamp but must not
	// become today's normal daily publication when they have newer record IDs.
	$normal_publications = $database->publications;
	$replacement_batch = md5('late-replacement-test');
	foreach ($normal_publications as $publication) {
		$replacement = $publication;
		$replacement['publication_id'] += 2;
		$replacement['batch_key'] = $replacement_batch;
		$replacement['filename'] = 'naknadno_za_2026-10-24_podaci_2026-10-26_' . $publication['filename'];
		$replacement['relative_path'] = 'anchor_price/' . $replacement['filename'];
		copy(DIR_DOWNLOAD . $publication['relative_path'], DIR_DOWNLOAD . $replacement['relative_path']);
		$database->publications[$replacement['publication_id']] = $replacement;
	}
	$reused = generateTestPublications($model, $surface, false);
	assertPublicationCondition(!empty($reused['success']) && count($database->publications) === 4, 'Late replacements must not trigger new daily generation.');
	foreach ($reused['locations'] as $location) {
		$publication = $surface === 'catalog' ? $location['publication'] : $location;
		assertPublicationCondition($publication['batch_key'] === $batch_keys[0], 'Daily idempotency must keep selecting the normal pair after a late replacement.');
	}
	if ($surface === 'admin') {
		$database->publications = array_diff_key($database->publications, $normal_publications);
		$daily_state = $model->getDailyPublicationState();
		assertPublicationCondition($daily_state['published'] === array() && $daily_state['missing'] === array('PJ1', 'PJ3'), 'Late replacements alone must not satisfy today\'s publication status.');
	}

	// Start again with the original pair. Change only PJ1, then reject a wrong warehouse.
	$database->publications = $normal_publications;
	createPj1Fixture('23,00');
	$updated = generateTestPublications($model, $surface, false);
	assertPublicationCondition($updated['success'] && count($database->publications) === 4, 'Changed content must publish on the same day.');
	createPj1Fixture('23,00', '00003');
	$invalid = generateTestPublications($model, $surface, false);
	assertPublicationCondition(!$invalid['success'] && count($database->publications) === 4, 'Wrong warehouse must preserve the last pair.');
	createPj1Fixture();
	if ($surface === 'catalog') {
		$clock = new DateTime('now', new DateTimeZone('Europe/Zagreb'));
		foreach ($database->publications as $id => &$row) {
			$row['published_at'] = (clone $clock)->modify($id <= 2 ? '-100 days' : '-29 days')->format('Y-m-d H:i:s');
		}
		unset($row);
		$history = $model->getPublications();
		assertPublicationCondition((int)$model->getCurrentPublication('PJ1')['publication_id'] === 3 && !$model->getCurrentPublication('PJ2'), 'Stable location selects the newest correct file.');
		assertPublicationCondition(count($history) === 4 && count(array_filter(array_column($history, 'current'))) === 2, 'Retain original from replacement date.');
		$model->expireOldPublications((clone $clock)->modify('+2 days'));
		assertPublicationCondition($database->publications[1]['status'] === 'expired' && $database->publications[3]['status'] === 'published', 'Keep current beyond retention, expire old after replacement.');
	}

	removePublicationTestFiles($temporary_directory);
	$database = new PublicationTestDatabase($products);
	$database->fail_atomic_publication = true;
	$result = generateTestPublications(publicationTestModel($database), $surface);
	assertPublicationCondition(empty($result['success']), 'A technical publication failure must still fail the job.');
	assertPublicationCondition($database->released_locks === 1, 'Technical failure must release the publication lock.');
	foreach ($database->publications as $publication) {
		assertPublicationCondition($publication['status'] === 'failed' && !is_file(DIR_DOWNLOAD . $publication['relative_path']), 'An unsuccessful atomic pair must be failed and removed from public files.');
	}

	$database = new PublicationTestDatabase(array());
	$result = generateTestPublications(publicationTestModel($database), $surface);
	assertPublicationCondition(empty($result['success']) && !$database->publications && $database->released_locks === 1, 'An empty active catalog must still fail safely and release its lock.');
	$database = new PublicationTestDatabase($products);
	$database->lock_available = false;
	$result = generateTestPublications(publicationTestModel($database), $surface);
	assertPublicationCondition(empty($result['success']) && !$database->publications, 'Concurrent publication must remain blocked by the lock.');
	echo 'OK: ' . $surface . " publishes both CSVs with product warnings and preserves technical failure handling.\n";
} catch (Exception $exception) {
	fwrite(STDERR, $surface . ': ' . $exception->getMessage() . "\n");
	exit(1);
}
