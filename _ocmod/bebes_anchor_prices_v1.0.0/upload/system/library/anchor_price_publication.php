<?php
require_once __DIR__ . '/bebes_bbm_price_list.php';

class AnchorPricePublication extends Model {
	public function generatePublication($force = false, $created_by = 0) {
		$store_id = (int)$this->config->get('config_store_id');
		if ($store_id !== 0) {
			return array('success' => false, 'error' => 'Ovaj cjenik pripada trgovini Atelier Bébés.');
		}
		$lock = 'anchor_price_publication_' . $store_id;
		$acquired = $this->db->query("SELECT GET_LOCK('" . $this->db->escape($lock) . "', 10) AS acquired");
		if (empty($acquired->row['acquired'])) {
			return array('success' => false, 'error' => 'Druga objava je u tijeku.');
		}

		$staged = array();
		try {
			$this->discardUnpublishedPublications($store_id);
			$now = new DateTime('now', new DateTimeZone('Europe/Zagreb'));
			$bbm = $this->fetchPj1($now);
			$products = $this->getPublicationProducts($store_id);
			$warnings = $this->collectPublicationWarnings($products);
			$prefix = DB_PREFIX;
			$metadata = $this->db->query("SELECT p.product_id, p.model, p.sku, p.ean, p.jan, p.isbn, m.name AS manufacturer
				FROM `{$prefix}product` p JOIN `{$prefix}product_to_store` ps ON ps.product_id = p.product_id
				LEFT JOIN `{$prefix}manufacturer` m ON m.manufacturer_id = p.manufacturer_id WHERE ps.store_id = '$store_id'")->rows;
			$catalog = array_column($products, null, 'product_id') + array_column($metadata, null, 'product_id');
			BebesPriceCsv::completeRetailRows($bbm, $catalog);
			foreach ($bbm as $index => $row) {
				$reasons = array();
				if ($row[10] === '') { $reasons[] = 'anchor_unconfirmed'; }
				if ($row[2] === '') { $reasons[] = 'manufacturer_missing'; }
				if ($row[4] === '') { $reasons[] = 'unit_missing'; }
				if ($row[5] === '0,00') { $reasons[] = 'zero_retail'; }
				if ($reasons) {
					$warnings[] = array('location_code' => 'PJ1', 'code' => $row[1], 'line' => $index + 2, 'product_name' => $row[0], 'reasons' => $reasons);
				}
			}
			$web = array();
			foreach ($products as $product) {
				$web[] = BebesPriceCsv::webRow($product, $this->tax, $this->config->get('module_anchor_price_default_unit') ?: 'kom');
			}
			$files = array('PJ1' => BebesPriceCsv::prepare($bbm), 'PJ3' => BebesPriceCsv::prepare($web));
			if (!$force) {
				$existing = array();
				foreach ($files as $code => $file) {
					$row = $this->getTodayPublication($store_id, $code, $now);
					if ($row && $file['checksum'] === $row['checksum_sha256']) {
						$existing[$code] = array('success' => true, 'existing' => true, 'publication' => $row);
					}
				}
				if (count($existing) === 2 && $this->validatedPublicationPair(array_column($existing, 'publication'))) {
					$this->expireOldPublications($now);
					return array('success' => true, 'locations' => $existing, 'warnings' => $warnings);
				}
			}

			$batch = $this->createBatchKey();
			foreach ($files as $code => $file) {
				$result = $this->generateLocationPublication($code, $file, $now, $batch, $created_by);
				if (empty($result['success'])) {
					throw new RuntimeException($result['error']);
				}
				$staged[$code] = $result;
			}
			$published = $this->publishPublicationPair($staged, $now, $batch);
			$staged = array();
			$this->expireOldPublications($now);
			return array('success' => true, 'locations' => $published, 'warnings' => $warnings);
		} catch (Exception $error) {
			foreach ($staged as $result) {
				$this->invalidatePublication($result['publication'], 'Objava nije dovršena.');
			}
			return array('success' => false, 'error' => $error->getMessage());
		} finally {
			$this->db->query("SELECT RELEASE_LOCK('" . $this->db->escape($lock) . "')");
		}
	}

	protected function fetchPj1(DateTime $now) {
		return BebesBbmPriceList::download($now);
	}

	private function getTodayPublication($store_id, $location_code, DateTime $now) {
		$start = clone $now;
		$start->setTime(0, 0, 0);
		$end = clone $start;
		$end->modify('+1 day');
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "anchor_price_publication` WHERE store_id = '" . (int)$store_id . "' AND location_code = '" . $this->db->escape($location_code) . "' AND status = 'published' AND LEFT(filename, 9) <> 'naknadno_' AND published_at >= '" . $this->db->escape($start->format('Y-m-d H:i:s')) . "' AND published_at < '" . $this->db->escape($end->format('Y-m-d H:i:s')) . "' ORDER BY publication_id DESC LIMIT 1");

		if (!$query->num_rows) {
			return array();
		}

		$publication = $query->row;
		return $this->publicationFileIsValid($publication) ? $publication : array();
	}

	private function invalidatePublication(array $publication, $reason) {
		$this->db->query("UPDATE `" . DB_PREFIX . "anchor_price_publication` SET status = 'failed', error_message = '" . $this->db->escape(substr($reason, 0, 2000)) . "' WHERE publication_id = '" . (int)$publication['publication_id'] . "' AND status IN ('generating', 'staged')");
		if ($this->db->countAffected() !== 1) { return; }
		$path = $this->publicationPath($publication);
		if ($path) {
			foreach (array($path, $path . '.tmp') as $file) {
				if (is_file($file)) { @unlink($file); }
			}
		}
	}

	private function generateLocationPublication($location_code, array $file, DateTime $now, $batch_key, $created_by) {
		$location_code = strtoupper(preg_replace('/[^A-Z0-9_-]/i', '', (string)$location_code));

		if (!in_array($location_code, array('PJ1', 'PJ3'), true)) {
			return array('success' => false, 'error' => 'Nepoznata oznaka prodajnog mjesta.');
		}

		$store_id = (int)$this->config->get('config_store_id');
		$sequence_query = $this->db->query("SELECT COALESCE(MAX(sequence_no), 0) + 1 AS next_sequence FROM `" . DB_PREFIX . "anchor_price_publication` WHERE store_id = '" . $store_id . "' AND location_code = '" . $this->db->escape($location_code) . "'");
		$sequence_no = (int)$sequence_query->row['next_sequence'];
		$filename = BebesPriceCsv::filename($location_code, $sequence_no, $now);
		$relative_path = 'anchor_price/' . $filename;

		$this->db->query("INSERT INTO `" . DB_PREFIX . "anchor_price_publication` SET created_by = '" . (int)$created_by . "', store_id = '" . $store_id . "', batch_key = '" . $this->db->escape($batch_key) . "', location_code = '" . $this->db->escape($location_code) . "', sequence_no = '" . $sequence_no . "', filename = '" . $this->db->escape($filename) . "', relative_path = '" . $this->db->escape($relative_path) . "', status = 'generating', product_count = '0', checksum_sha256 = '', error_message = '', published_at = NULL, date_added = '" . $this->db->escape($now->format('Y-m-d H:i:s')) . "'");
		$publication_id = $this->db->getLastId();

		$directory = rtrim(DIR_DOWNLOAD, '/\\') . DIRECTORY_SEPARATOR . 'anchor_price';

		if (!is_dir($directory) && !@mkdir($directory, 0750, true)) {
			return $this->failPublication($publication_id, 'Nije moguće pripremiti mapu javnih cjenika.');
		}

		$final_path = $directory . DIRECTORY_SEPARATOR . $filename;
		$temp_path = $final_path . '.tmp';
		$handle = @fopen($temp_path, 'wb');

		if (!$handle) {
			return $this->failPublication($publication_id, 'Nije moguće otvoriti privremenu CSV datoteku.');
		}

		$product_count = $file['count'];
		if (stream_copy_to_stream($file['stream'], $handle) === false) {
			fclose($handle);
			@unlink($temp_path);
			return $this->failPublication($publication_id, 'Pogreška pri zapisu CSV datoteke.');
		}
		if (!fflush($handle)) {
			fclose($handle);
			@unlink($temp_path);
			return $this->failPublication($publication_id, 'Pogreška pri završnom zapisu CSV datoteke.');
		}
		if (function_exists('fsync')) {
			@fsync($handle);
		}
		fclose($handle);

		if (!$product_count || !@rename($temp_path, $final_path)) {
			@unlink($temp_path);
			return $this->failPublication($publication_id, !$product_count ? 'Cjenik nema nijedan proizvod.' : 'Atomska objava CSV datoteke nije uspjela.');
		}

		$checksum = hash_file('sha256', $final_path);
		if ($checksum !== $file['checksum']) {
			@unlink($final_path);
			return $this->failPublication($publication_id, 'Nije moguće izračunati SHA-256 kontrolni zbroj cjenika.');
		}
		$this->db->query("UPDATE `" . DB_PREFIX . "anchor_price_publication` SET status = 'staged', product_count = '" . (int)$product_count . "', checksum_sha256 = '" . $this->db->escape($checksum) . "', error_message = '', published_at = NULL WHERE publication_id = '" . (int)$publication_id . "' AND batch_key = '" . $this->db->escape($batch_key) . "'");
		$publication = $this->getPublication($publication_id, false);

		return array('success' => true, 'existing' => false, 'publication' => $publication);
	}

	private function publishPublicationPair(array $results, DateTime $now, $batch_key) {
		if (count($results) !== 2 || empty($results['PJ1']['publication']) || empty($results['PJ3']['publication'])) {
			throw new Exception('Dnevni PJ1/PJ3 par nije spreman za objavu.');
		}

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "anchor_price_publication` WHERE store_id = '" . (int)$this->config->get('config_store_id') . "' AND batch_key = '" . $this->db->escape($batch_key) . "' AND status = 'staged' ORDER BY location_code ASC");
		if ($query->num_rows !== 2 || $query->rows[0]['location_code'] !== 'PJ1' || $query->rows[1]['location_code'] !== 'PJ3') {
			throw new Exception('Dnevni PJ1/PJ3 par nije potpun.');
		}

		$publication_ids = array();
		foreach ($query->rows as $publication) {
			if (!$this->publicationFileIsValid($publication)) {
				throw new Exception('Kontrolni zbroj pripremljene datoteke nije valjan za ' . $publication['location_code'] . '.');
			}
			$publication_ids[] = (int)$publication['publication_id'];
		}

		$this->db->query('START TRANSACTION');
		try {
			$this->db->query("UPDATE `" . DB_PREFIX . "anchor_price_publication` SET status = 'published', published_at = '" . $this->db->escape($now->format('Y-m-d H:i:s')) . "' WHERE publication_id IN (" . implode(',', $publication_ids) . ") AND batch_key = '" . $this->db->escape($batch_key) . "' AND status = 'staged'");
			if ($this->db->countAffected() !== 2) {
				throw new Exception('Atomska objava dnevnog PJ1/PJ3 para nije uspjela.');
			}
			$this->db->query('COMMIT');
		} catch (Exception $exception) {
			$this->db->query('ROLLBACK');
			throw $exception;
		}

		foreach ($results as &$result) {
			$result['publication']['status'] = 'published';
			$result['publication']['published_at'] = $now->format('Y-m-d H:i:s');
		}
		unset($result);
		return $results;
	}

	private function discardUnpublishedPublications($store_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "anchor_price_publication` WHERE store_id = '" . (int)$store_id . "' AND status IN ('generating', 'staged')");
		foreach ($query->rows as $publication) {
			$this->invalidatePublication($publication, 'Nedovršena priprema cjenika uklonjena je prije novog pokušaja.');
		}
	}

	private function createBatchKey() {
		return md5(uniqid((string)mt_rand(), true));
	}

	private function getPublicationProducts($store_id) {
		$languages = $this->db->query("SELECT language_id FROM `" . DB_PREFIX . "language` WHERE code = 'hr-hr' LIMIT 1");
		$language_id = $languages->num_rows ? (int)$languages->row['language_id'] : (int)$this->config->get('config_language_id');
		$customer_group_id = (int)$this->config->get('config_customer_group_id');
		$prefix = DB_PREFIX;
		$store_id = (int)$store_id;
		$sql = "SELECT p.product_id, p.model, p.sku, p.ean, p.jan, p.isbn, p.price, p.tax_class_id,
			p.manufacturer_id, pd.name, COALESCE(m.name, '') AS manufacturer,
			ap.anchor_price_id, ap.source, ap.verification_status, ap.gross_price AS anchor_gross_price,
			(SELECT d.price FROM `{$prefix}product_discount` d WHERE d.product_id = p.product_id
				AND d.customer_group_id = '$customer_group_id' AND d.quantity = 1
				AND (d.date_start = '0000-00-00' OR d.date_start < NOW())
				AND (d.date_end = '0000-00-00' OR d.date_end > NOW())
				ORDER BY d.priority, d.price LIMIT 1) AS discount,
			(SELECT ps.price FROM `{$prefix}product_special` ps WHERE ps.product_id = p.product_id
				AND ps.customer_group_id = '$customer_group_id'
				AND (ps.date_start = '0000-00-00' OR ps.date_start < NOW())
				AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())
				ORDER BY ps.priority, ps.price LIMIT 1) AS special
			FROM `{$prefix}product` p
			JOIN `{$prefix}product_to_store` ps ON ps.product_id = p.product_id AND ps.store_id = '$store_id'
			LEFT JOIN `{$prefix}product_description` pd ON pd.product_id = p.product_id AND pd.language_id = '$language_id'
			LEFT JOIN `{$prefix}anchor_price` ap ON ap.product_id = p.product_id AND ap.store_id = ps.store_id
			LEFT JOIN `{$prefix}manufacturer` m ON m.manufacturer_id = p.manufacturer_id
			WHERE p.status = 1 AND p.date_available <= NOW() ORDER BY p.product_id";

		$products = $this->db->query($sql)->rows;
		$attributes = $this->db->query("SELECT pa.product_id, ad.name, pa.text FROM `" . DB_PREFIX . "product_attribute` pa JOIN `" . DB_PREFIX . "attribute_description` ad ON ad.attribute_id = pa.attribute_id AND ad.language_id = pa.language_id WHERE pa.language_id = '" . $language_id . "' AND ad.name IN ('Neto količina', 'Jedinica mjere') ORDER BY pa.attribute_id")->rows;
		$units = array();
		foreach ($attributes as $attr) {
			$field = mb_strtolower($attr['name'], 'UTF-8') === 'neto količina' ? 'net_quantity' : 'unit';
			$units[$attr['product_id']][$field] = $attr['text'];
		}
		foreach ($products as &$product) {
			if (isset($units[$product['product_id']])) {
				$product += $units[$product['product_id']];
			}
		}
		unset($product);
		return $products;
	}

	public function getPublicationWarnings() {
		return $this->collectPublicationWarnings($this->getPublicationProducts((int)$this->config->get('config_store_id')));
	}

	private function collectPublicationWarnings(array $products) {
		$warnings = array();
		foreach ($products as $product) {
			$reasons = array();
			if (empty($product['anchor_price_id'])) {
				$reasons[] = 'anchor_missing';
			} elseif ($product['verification_status'] !== 'confirmed') {
				$reasons[] = 'anchor_unconfirmed';
			} elseif ($product['source'] !== 'admin_nn132') {
				$reasons[] = 'legacy_history_review';
			}
			if (trim((string)$product['name']) === '') {
				$reasons[] = 'name_missing';
			}
			if (trim((string)$product['model']) === '') {
				$reasons[] = 'model_missing';
			}
			if (empty($product['manufacturer_id']) || trim((string)$product['manufacturer']) === '') {
				$reasons[] = 'manufacturer_missing';
			}
			foreach (array('ean', 'jan', 'isbn') as $field) {
				$value = isset($product[$field]) ? trim((string)$product[$field]) : '';
				if ($value !== '' && (!BebesPriceCsv::validGtin($value) || ($field === 'ean' && !in_array(strlen($value), array(8, 13), true)))) {
					$reasons[] = $field . '_invalid';
				}
			}
			$net = str_replace(',', '.', trim((string)($product['net_quantity'] ?? '')));
			if (($net !== '' && (!preg_match('/^\d{1,12}(?:\.\d{1,6})?$/D', $net) || (float)$net <= 0 || empty($product['unit']))) || (!empty($product['unit']) && $product['unit'] !== 'kom' && $net === '')) {
				$reasons[] = 'net_quantity';
			}
			if ($reasons) {
				$warnings[] = array(
					'location_code' => 'PJ3', 'code' => $product['model'],
					'product_id' => (int)$product['product_id'],
					'anchor_price_id' => (int)$product['anchor_price_id'],
					'product_name' => (string)$product['name'],
					'reasons' => $reasons
				);
			}
		}
		return $warnings;
	}

	public function getPublications() {
		if (!$this->publicationTableExists()) { return array(); }
		$now = new DateTime('now', new DateTimeZone('Europe/Zagreb'));
		return $this->publicationHistory($now)['visible'];
	}

	public function getCurrentPublication($location) {
		if (!in_array($location, array('PJ1', 'PJ3'), true) || !$this->publicationTableExists()) {
			return false;
		}
		$store_id = (int)$this->config->get('config_store_id');
		$table = "`" . DB_PREFIX . "anchor_price_publication`";
		$latest = $this->db->query("SELECT batch_key FROM " . $table . " WHERE store_id = '" . $store_id . "' AND location_code = '" . $location . "' AND status = 'published' AND LEFT(filename, 9) <> 'naknadno_' ORDER BY published_at DESC, publication_id DESC LIMIT 1");
		if (!$latest->num_rows) { return false; }
		$rows = $this->db->query("SELECT * FROM " . $table . " WHERE store_id = '" . $store_id . "' AND batch_key = '" . $this->db->escape($latest->row['batch_key']) . "' AND status = 'published'")->rows;
		$pair = $this->validatedPublicationPair($rows);
		if ($pair) { return $pair[$location]; }
		foreach ($this->getPublications() as $row) {
			if ($row['current'] && $row['location_code'] === $location) { return $row; }
		}
		return false;
	}

	private function publicationHistory(DateTime $now) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "anchor_price_publication` WHERE store_id = '" . (int)$this->config->get('config_store_id') . "' AND status = 'published' ORDER BY published_at DESC, publication_id DESC");
		$batches = array();
		foreach ($query->rows as $row) {
			$batches[$row['batch_key']][] = $row;
		}
		$visible = array();
		$expired = array();
		$next = null;
		$cutoff = (clone $now)->modify('-30 days')->format('Y-m-d H:i:s');
		foreach ($batches as $rows) {
			$pair = $this->validatedPublicationPair($rows);
			if (!$pair) { continue; }
			$published = $pair['PJ1']['published_at'];
			$late = strpos($pair['PJ1']['filename'], 'naknadno_') === 0;
			$replaced = $late ? $published : $next;
			foreach ($pair as $row) {
				$row['current'] = $replaced === null;
				if ($replaced !== null && $replaced < $cutoff) {
					$expired[] = $row;
				} else {
					$visible[] = $row;
				}
			}
			if (!$late) { $next = $published; }
		}
		return array('visible' => $visible, 'expired' => $expired);
	}

	public function getPublication($publication_id, $public_only = true) {
		if ($public_only) {
			foreach ($this->getPublications() as $row) {
				if ((int)$row['publication_id'] === (int)$publication_id) { return $row; }
			}
			return false;
		}
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "anchor_price_publication` WHERE publication_id = '" . (int)$publication_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT 1");
		return $query->num_rows ? $query->row : false;
	}

	private function validatedPublicationPair(array $rows) {
		if (count($rows) !== 2) {
			return false;
		}

		$pair = array();
		$batch_key = '';
		$published_at = '';
		foreach ($rows as $row) {
			$location_code = isset($row['location_code']) ? $row['location_code'] : '';
			$row_batch_key = isset($row['batch_key']) ? strtolower(trim((string)$row['batch_key'])) : '';
			if (!in_array($location_code, array('PJ1', 'PJ3'), true)
				|| isset($pair[$location_code])
				|| !preg_match('/^[a-f0-9]{32}$/', $row_batch_key)
				|| empty($row['published_at'])
				|| (int)$row['product_count'] < 1
				|| ($batch_key !== '' && $batch_key !== $row_batch_key)
				|| ($published_at !== '' && $published_at !== $row['published_at'])
				|| !$this->publicationFileIsValid($row)) {
				return false;
			}

			$batch_key = $row_batch_key;
			$published_at = $row['published_at'];
			$pair[$location_code] = $row;
		}

		return isset($pair['PJ1'], $pair['PJ3']) ? $pair : false;
	}

	public function publicationPath($publication) {
		if (!$publication || empty($publication['relative_path'])) {
			return false;
		}

		$relative = str_replace('\\', '/', (string)$publication['relative_path']);

		if (strpos($relative, 'anchor_price/') !== 0 || strpos($relative, '..') !== false || substr($relative, -4) !== '.csv') {
			return false;
		}

		$base = rtrim(DIR_DOWNLOAD, '/\\') . DIRECTORY_SEPARATOR;
		$path = $base . str_replace('/', DIRECTORY_SEPARATOR, $relative);

		return $path;
	}

	public function publicationFileIsValid($publication, $path = false) {
		if (!$publication || !isset($publication['checksum_sha256'])) {
			return false;
		}

		if ($path === false) {
			$path = $this->publicationPath($publication);
		}

		$expected_checksum = strtolower(trim((string)$publication['checksum_sha256']));
		if (!$path || !is_file($path) || !is_readable($path) || !preg_match('/^[a-f0-9]{64}$/', $expected_checksum)) {
			return false;
		}

		$actual_checksum = hash_file('sha256', $path);
		if ($actual_checksum === false) {
			return false;
		}

		return function_exists('hash_equals') ? hash_equals($expected_checksum, strtolower($actual_checksum)) : $expected_checksum === strtolower($actual_checksum);
	}

	private function publicationTableExists() {
		$query = $this->db->query("SHOW TABLES LIKE '" . $this->db->escape(DB_PREFIX . "anchor_price_publication") . "'");

		return (bool)$query->num_rows;
	}

	private function failPublication($publication_id, $message) {
		$this->db->query("UPDATE `" . DB_PREFIX . "anchor_price_publication` SET status = 'failed', error_message = '" . $this->db->escape($message) . "' WHERE publication_id = '" . (int)$publication_id . "'");

		return array('success' => false, 'error' => $message, 'publication_id' => (int)$publication_id);
	}

	public function expireOldPublications(DateTime $now) {
		foreach ($this->publicationHistory($now)['expired'] as $row) {
			$path = $this->publicationPath($row);
			$this->db->query("UPDATE `" . DB_PREFIX . "anchor_price_publication` SET status = 'expired' WHERE publication_id = '" . (int)$row['publication_id'] . "'");
			if ($path && is_file($path)) { @unlink($path); }
		}
	}
}
