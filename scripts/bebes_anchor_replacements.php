<?php
// Publish clearly labelled replacements from an existing, validated CSV pair.
// Usage: php bebes_anchor_replacements.php STORE_ROOT SOURCE_PUBLICATION_ID YYYY-MM-DD [...]
if (PHP_SAPI !== 'cli' || $argc < 4) {
	fwrite(STDERR, "Usage: php bebes_anchor_replacements.php STORE_ROOT SOURCE_PUBLICATION_ID YYYY-MM-DD [...]\n");
	exit(64);
}
$root = rtrim($argv[1], '/');
require $root . '/config.php';
require $root . '/system/engine/registry.php';
require $root . '/system/engine/model.php';
require $root . '/system/library/config.php';
require $root . '/system/library/db.php';
require $root . '/system/library/db/mysqli.php';
require $root . '/catalog/model/extension/module/anchor_price.php';
$db = new DB('MySQLi', DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
$config = new Config();
$config->set('config_store_id', 0);
$registry = new Registry();
$registry->set('db', $db);
$registry->set('config', $config);
$model = new ModelExtensionModuleAnchorPrice($registry);
$lock = $db->query("SELECT GET_LOCK('anchor_price_publication_0', 10) AS acquired");
if (empty($lock->row['acquired'])) {
	throw new Exception('Another price-list publication is running.');
}
$created_files = array();
$transaction = false;
try {
	$source = $model->getPublication((int)$argv[2], true);
	if (!$source || strpos($source['filename'], 'naknadno_') === 0) {
		throw new Exception('Source must be an available original publication pair.');
	}
	$pair = $db->query("SELECT * FROM `" . DB_PREFIX . "anchor_price_publication` WHERE store_id=0 AND status='published' AND batch_key='" . $db->escape($source['batch_key']) . "' ORDER BY location_code")->rows;
	if (count($pair) !== 2) {
		throw new Exception('Source pair is incomplete.');
	}
	$dates = array_unique(array_slice($argv, 3));
	foreach ($dates as $date) {
		$parsed = DateTime::createFromFormat('!Y-m-d', $date);
		if (!$parsed || $parsed->format('Y-m-d') !== $date || $date >= substr($source['published_at'], 0, 10)) {
			throw new Exception('Replacement date must precede the source capture date: ' . $date);
		}
	}
	foreach ($dates as $date) {
		$prefix = 'naknadno_za_' . $date . '_';
		$existing = $db->query("SELECT publication_id FROM `" . DB_PREFIX . "anchor_price_publication` WHERE store_id=0 AND status='published' AND LEFT(filename," . strlen($prefix) . ")='" . $db->escape($prefix) . "'")->rows;
		if ($existing) {
			if (count($existing) !== 2 || !$model->getPublication((int)$existing[0]['publication_id'], true)) {
				throw new Exception('An incomplete replacement pair already exists for ' . $date);
			}
			echo $date . " already has a valid replacement pair.\n";
			continue;
		}
		$original = $db->query("SELECT publication_id FROM `" . DB_PREFIX . "anchor_price_publication` WHERE store_id=0 AND status='published' AND LEFT(filename,9)<>'naknadno_' AND DATE(published_at)='" . $db->escape($date) . "'");
		if ($original->num_rows) {
			echo $date . " already has original publications; unchanged.\n";
			continue;
		}
		$now = new DateTime('now', new DateTimeZone('Europe/Zagreb'));
		$timestamp = $now->format('Y-m-d H:i:s');
		$capture = str_replace(array('-', ' ', ':'), array('-', '_', ''), $source['published_at']);
		$batch = md5(uniqid('replacement', true));
		$ids = array();
		$created_files = array();
		$db->query('START TRANSACTION');
		$transaction = true;
		foreach ($pair as $row) {
			$source_path = $model->publicationPath($row);
			if (!$model->publicationFileIsValid($row, $source_path)) {
				throw new Exception('Source checksum mismatch.');
			}
			$location = $row['location_code'];
			$sequence = $db->query("SELECT COALESCE(MAX(sequence_no),0)+1 AS next_no FROM `" . DB_PREFIX . "anchor_price_publication` WHERE store_id=0 AND location_code='" . $db->escape($location) . "'")->row['next_no'];
			$suffix = BebesPriceCsv::filename($location, $sequence, $now);
			$filename = $prefix . 'podaci_' . $capture . '_' . $suffix;
			$relative = 'anchor_price/' . $filename;
			$path = rtrim(DIR_DOWNLOAD, '/\\') . '/' . $relative;
			if (is_file($path)) {
				throw new Exception('Replacement file already exists.');
			}
			$created_files[] = $path;
			if (!copy($source_path, $path) || hash_file('sha256', $path) !== $row['checksum_sha256']) {
				throw new Exception('Unable to copy and verify replacement CSV.');
			}
			chmod($path, 0640);
			$note = 'Replacement for ' . $date . '; data captured ' . $source['published_at'] . '; source publication ' . $row['publication_id'] . '; actually published ' . $timestamp;
			$fields = array('store_id' => 0, 'batch_key' => $batch, 'location_code' => $location, 'sequence_no' => $sequence, 'filename' => $filename, 'relative_path' => $relative, 'status' => 'published', 'product_count' => $row['product_count'], 'checksum_sha256' => $row['checksum_sha256'], 'error_message' => $note, 'created_by' => 0, 'published_at' => $timestamp, 'date_added' => $timestamp);
			$values = array();
			foreach ($fields as $key => $value) {
				$values[] = '`' . $key . "`='" . $db->escape((string)$value) . "'";
			}
			$db->query('INSERT INTO `' . DB_PREFIX . 'anchor_price_publication` SET ' . implode(',', $values));
			$ids[] = $db->getLastId();
		}
		$db->query('COMMIT');
		$transaction = false;
		$created_files = array();
		if (!$model->getPublication((int)$ids[0], true)) {
			throw new Exception('Published replacement pair did not validate.');
		}
		echo $date . ' replacement pair published: ' . implode(',', $ids) . '; source=' . $source['published_at'] . '; published=' . $timestamp . "\n";
	}
	$db->query("SELECT RELEASE_LOCK('anchor_price_publication_0')");
} catch (Exception $exception) {
	if ($transaction) {
		$db->query('ROLLBACK');
		foreach ($created_files as $file) {
			@unlink($file);
		}
	}
	$db->query("SELECT RELEASE_LOCK('anchor_price_publication_0')");
	fwrite(STDERR, $exception->getMessage() . "\n");
	exit(1);
}
