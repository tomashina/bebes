<?php
require_once(DIR_SYSTEM . 'library/anchor_price_publication.php');
require_once(DIR_SYSTEM . 'library/anchor_price_history.php');

class ModelExtensionModuleAnchorPrice extends Model {
	const STORE_ID = 0;
	const CURRENCY_CODE = 'EUR';
	const CUTOVER_DATE = '2026-09-10';

	private $catalog_language_id;

	public function install() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "anchor_price` (
			`anchor_price_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			`product_id` INT(11) UNSIGNED NOT NULL,
			`store_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
			`price` DECIMAL(15,4) NOT NULL DEFAULT '0.0000',
			`gross_price` DECIMAL(15,4) NOT NULL DEFAULT '0.0000',
			`currency_code` CHAR(3) NOT NULL DEFAULT 'EUR',
			`tax_class_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
			`tax_context` TEXT NOT NULL,
			`reference_date` DATE NOT NULL,
			`rule_code` VARCHAR(32) NOT NULL,
			`source` VARCHAR(32) NOT NULL,
			`verification_status` VARCHAR(20) NOT NULL DEFAULT 'pending',
			`created_by` INT(11) UNSIGNED NOT NULL DEFAULT '0',
			`date_added` DATETIME NOT NULL,
			`date_modified` DATETIME NOT NULL,
			PRIMARY KEY (`anchor_price_id`),
			UNIQUE KEY `product_store` (`product_id`, `store_id`),
			KEY `reference_date` (`reference_date`),
			KEY `verification_status` (`verification_status`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "anchor_price_audit` (
			`audit_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			`anchor_price_id` INT(11) UNSIGNED NOT NULL,
			`product_id` INT(11) UNSIGNED NOT NULL,
			`store_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
			`user_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
			`action` VARCHAR(32) NOT NULL,
			`old_data` MEDIUMTEXT NOT NULL,
			`new_data` MEDIUMTEXT NOT NULL,
			`reason` VARCHAR(255) NOT NULL,
			`date_added` DATETIME NOT NULL,
			PRIMARY KEY (`audit_id`),
			KEY `anchor_price_id` (`anchor_price_id`),
			KEY `product_store` (`product_id`, `store_id`),
			KEY `date_added` (`date_added`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "anchor_price_publication` (
			`publication_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			`store_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
			`batch_key` CHAR(32) NOT NULL DEFAULT '',
			`location_code` VARCHAR(16) NOT NULL,
			`sequence_no` INT(11) UNSIGNED NOT NULL,
			`filename` VARCHAR(255) NOT NULL,
			`relative_path` VARCHAR(255) NOT NULL,
			`status` VARCHAR(20) NOT NULL,
			`product_count` INT(11) UNSIGNED NOT NULL DEFAULT '0',
			`checksum_sha256` CHAR(64) NOT NULL DEFAULT '',
			`error_message` TEXT,
			`created_by` INT(11) UNSIGNED NOT NULL DEFAULT '0',
			`published_at` DATETIME DEFAULT NULL,
			`date_added` DATETIME NOT NULL,
			PRIMARY KEY (`publication_id`),
			UNIQUE KEY `store_location_sequence` (`store_id`, `location_code`, `sequence_no`),
			KEY `batch_key` (`batch_key`),
			KEY `status_published` (`status`, `published_at`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");

		$batch_column = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "anchor_price_publication` LIKE 'batch_key'");
		if (!$batch_column->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "anchor_price_publication` ADD `batch_key` CHAR(32) NOT NULL DEFAULT '' AFTER `store_id`");
		} elseif (strtolower($batch_column->row['Type']) !== 'char(32)' || $batch_column->row['Null'] !== 'NO' || (string)$batch_column->row['Default'] !== '') {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "anchor_price_publication` MODIFY `batch_key` CHAR(32) NOT NULL DEFAULT ''");
		}

		$batch_index = $this->db->query("SHOW INDEX FROM `" . DB_PREFIX . "anchor_price_publication` WHERE Key_name = 'batch_key'");
		$valid_batch_index = $batch_index->num_rows === 1 && $batch_index->row['Column_name'] === 'batch_key' && (int)$batch_index->row['Seq_in_index'] === 1 && (int)$batch_index->row['Non_unique'] === 1;
		if ($batch_index->num_rows && !$valid_batch_index) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "anchor_price_publication` DROP INDEX `batch_key`");
		}
		if (!$valid_batch_index) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "anchor_price_publication` ADD KEY `batch_key` (`batch_key`)");
		}

		$sequence_index = $this->db->query("SHOW INDEX FROM `" . DB_PREFIX . "anchor_price_publication` WHERE Key_name = 'store_location_sequence'");
		$sequence_columns = array();
		$valid_sequence_index = $sequence_index->num_rows === 3;
		foreach ($sequence_index->rows as $index_row) {
			$sequence_columns[(int)$index_row['Seq_in_index']] = $index_row['Column_name'];
			$valid_sequence_index = $valid_sequence_index && (int)$index_row['Non_unique'] === 0;
		}
		ksort($sequence_columns);
		$valid_sequence_index = $valid_sequence_index && array_values($sequence_columns) === array('store_id', 'location_code', 'sequence_no');
		if ($sequence_index->num_rows && !$valid_sequence_index) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "anchor_price_publication` DROP INDEX `store_location_sequence`");
		}
		if (!$valid_sequence_index) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "anchor_price_publication` ADD UNIQUE KEY `store_location_sequence` (`store_id`, `location_code`, `sequence_no`)");
		}

		$status_index = $this->db->query("SHOW INDEX FROM `" . DB_PREFIX . "anchor_price_publication` WHERE Key_name = 'status_published'");
		$status_columns = array();
		$valid_status_index = $status_index->num_rows === 2;
		foreach ($status_index->rows as $index_row) {
			$status_columns[(int)$index_row['Seq_in_index']] = $index_row['Column_name'];
			$valid_status_index = $valid_status_index && (int)$index_row['Non_unique'] === 1;
		}
		ksort($status_columns);
		$valid_status_index = $valid_status_index && array_values($status_columns) === array('status', 'published_at');
		if ($status_index->num_rows && !$valid_status_index) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "anchor_price_publication` DROP INDEX `status_published`");
		}
		if (!$valid_status_index) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "anchor_price_publication` ADD KEY `status_published` (`status`, `published_at`)");
		}

		$publication_table = $this->db->query("SHOW TABLE STATUS LIKE '" . $this->db->escape(DB_PREFIX . "anchor_price_publication") . "'");
		if ($publication_table->num_rows && strtoupper($publication_table->row['Engine']) !== 'INNODB') {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "anchor_price_publication` ENGINE=InnoDB");
		}
	}

	public function uninstall() {
		// Deliberately keep all snapshots, audit entries and publication records.
	}

	public function tablesExist() {
		$query = $this->db->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '" . $this->db->escape(DB_DATABASE) . "' AND TABLE_NAME = '" . $this->db->escape(DB_PREFIX . "anchor_price") . "' LIMIT 1");
		return (bool)$query->num_rows;
	}

	public function seedExistingProducts($created_by = 0) {
		$this->markAutomaticFirstListingsPending((int)$created_by);
		return $this->syncProducts((int)$created_by, 'install', false);
	}

	public function syncMissingProducts($created_by = 0) {
		// Drafts are intentionally skipped. Their snapshot is created on first publication.
		return $this->syncProducts((int)$created_by, 'sync', true);
	}

	private function syncProducts($created_by, $source, $active_only) {
		$created = 0;
		$last_product_id = 0;
		$limit = 250;
		$now = new DateTime('now', new DateTimeZone('Europe/Zagreb'));
		$today = $now->format('Y-m-d');

		do {
			$sql = "SELECT p.product_id, p.price, p.tax_class_id, p.status, p.date_added, p.date_available FROM `" . DB_PREFIX . "product` p INNER JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p2s.product_id = p.product_id AND p2s.store_id = '" . self::STORE_ID . "') LEFT JOIN `" . DB_PREFIX . "anchor_price` ap ON (ap.product_id = p.product_id AND ap.store_id = p2s.store_id) WHERE p.product_id > '" . (int)$last_product_id . "' AND ap.anchor_price_id IS NULL";

			if ($active_only) {
				$sql .= " AND p.status = '1' AND p.date_available <= '" . $this->db->escape($today) . "'";
			} elseif ($source === 'install') {
				// Preserve legacy products from the reference day, but never assign a
				// draft created after the cutover a false first-publication date.
				$sql .= " AND (p.date_added < '2026-09-11 00:00:00' OR (p.status = '1' AND p.date_available <= '" . $this->db->escape($today) . "'))";
			}

			$sql .= " ORDER BY p.product_id ASC LIMIT " . (int)$limit;
			$query = $this->db->query($sql);

			foreach ($query->rows as $product) {
				$last_product_id = (int)$product['product_id'];
				$product_date = substr($product['date_added'], 0, 10);

				if ($source === 'sync') {
					// A missing active row is first observed as public now (typically a former draft).
					$reference_date = $now->format('Y-m-d');
					$rule_code = 'first_listing';
				} elseif ($product_date > self::CUTOVER_DATE) {
					$reference_date = $product_date;
					$rule_code = 'first_listing';
				} else {
					$reference_date = self::CUTOVER_DATE;
					$rule_code = 'baseline_2026_09_10';
				}

				if ($this->insertSnapshot($product, $reference_date, $rule_code, $source, $created_by)) {
					$created++;
				}
			}
		} while ($query->num_rows === $limit);

		return $created;
	}

	private function markAutomaticFirstListingsPending($created_by) {
		$today = new DateTime('now', new DateTimeZone('Europe/Zagreb'));
		$query = $this->db->query("SELECT ap.* FROM `" . DB_PREFIX . "anchor_price` ap LEFT JOIN `" . DB_PREFIX . "product` p ON (p.product_id = ap.product_id) WHERE ap.verification_status = 'confirmed' AND ap.source IN ('migration_backfill_2026_09_10', 'install', 'sync', 'price_list_sync', 'cron_sync', 'product_event', 'product_edit_event') AND (ap.rule_code = 'first_listing' OR p.product_id IS NULL OR p.status <> '1' OR p.date_available > '" . $this->db->escape($today->format('Y-m-d')) . "') ORDER BY ap.anchor_price_id ASC");
		if (!$query->num_rows) {
			return 0;
		}

		$this->db->query('START TRANSACTION');
		try {
			foreach ($query->rows as $row) {
				$before = $this->snapshotFromRow($row);
				$after = $before;
				$after['verification_status'] = 'pending';
				$this->addAudit((int)$row['anchor_price_id'], (int)$row['product_id'], (int)$row['store_id'], 'status_review_required', $before, $after, 'Automatic snapshot date/status requires manual verification', (int)$created_by);
			}

			$anchor_price_ids = array();
			foreach ($query->rows as $row) {
				$anchor_price_ids[] = (int)$row['anchor_price_id'];
			}
			$this->db->query("UPDATE `" . DB_PREFIX . "anchor_price` SET verification_status = 'pending', date_modified = NOW() WHERE anchor_price_id IN (" . implode(',', $anchor_price_ids) . ")");
			$this->db->query('COMMIT');
		} catch (Exception $exception) {
			$this->db->query('ROLLBACK');
			throw $exception;
		}

		return $query->num_rows;
	}

	public function capturePublishedProduct($product_id, $source, $created_by = 0) {
		$now = new DateTime('now', new DateTimeZone('Europe/Zagreb'));
		$query = $this->db->query("SELECT p.product_id, p.price, p.tax_class_id, p.status, p.date_added, p.date_available FROM `" . DB_PREFIX . "product` p INNER JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p2s.product_id = p.product_id AND p2s.store_id = '" . self::STORE_ID . "') WHERE p.product_id = '" . (int)$product_id . "' LIMIT 1");

		if (!$query->num_rows || !(int)$query->row['status'] || $query->row['date_available'] > $now->format('Y-m-d')) {
			return false;
		}

		$existing = $this->db->query("SELECT anchor_price_id FROM `" . DB_PREFIX . "anchor_price` WHERE product_id = '" . (int)$product_id . "' AND store_id = '" . self::STORE_ID . "' LIMIT 1");

		if ($existing->num_rows) {
			return false;
		}

		return $this->insertSnapshot($query->row, $now->format('Y-m-d'), 'first_listing', $source, (int)$created_by);
	}

	private function insertSnapshot(array $product, $reference_date, $rule_code, $source, $created_by) {
		$price = round((float)$product['price'], 4);
		$tax_class_id = (int)$product['tax_class_id'];
		$gross_price = round((float)$this->tax->calculate($price, $tax_class_id, true), 4);
		$tax_context = $this->buildTaxContext($price, $tax_class_id);
		$verification_status = 'pending';

		$this->db->query('START TRANSACTION');
		try {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "anchor_price` SET product_id = '" . (int)$product['product_id'] . "', store_id = '" . self::STORE_ID . "', price = '" . (float)$price . "', gross_price = '" . (float)$gross_price . "', currency_code = '" . self::CURRENCY_CODE . "', tax_class_id = '" . $tax_class_id . "', tax_context = '" . $this->db->escape($tax_context) . "', reference_date = '" . $this->db->escape($reference_date) . "', rule_code = '" . $this->db->escape($rule_code) . "', source = '" . $this->db->escape($source) . "', verification_status = '" . $this->db->escape($verification_status) . "', created_by = '" . (int)$created_by . "', date_added = NOW(), date_modified = NOW() ON DUPLICATE KEY UPDATE anchor_price_id = anchor_price_id");

			if ($this->db->countAffected() !== 1) {
				$this->db->query('COMMIT');
				return false;
			}

			$anchor_price_id = (int)$this->db->getLastId();
			$after = array(
				'price' => number_format($price, 4, '.', ''),
				'gross_price' => number_format($gross_price, 4, '.', ''),
				'currency_code' => self::CURRENCY_CODE,
				'tax_class_id' => $tax_class_id,
				'tax_context' => $tax_context,
				'reference_date' => $reference_date,
				'rule_code' => $rule_code,
				'source' => $source,
				'verification_status' => $verification_status
			);

			$this->addAudit($anchor_price_id, (int)$product['product_id'], self::STORE_ID, 'create', null, $after, 'Automatic snapshot: ' . $source, (int)$created_by);
			$this->db->query('COMMIT');
		} catch (Exception $exception) {
			$this->db->query('ROLLBACK');
			throw $exception;
		}

		return true;
	}

	private function buildTaxContext($price, $tax_class_id) {
		$rates = $this->tax->getRates((float)$price, (int)$tax_class_id);
		$context = array(
			'calculation' => 'tax.calculate(value, tax_class_id, true)',
			'config_tax' => (bool)$this->config->get('config_tax'),
			'country_id' => (int)$this->config->get('config_country_id'),
			'zone_id' => (int)$this->config->get('config_zone_id'),
			'customer_group_id' => (int)$this->config->get('config_customer_group_id'),
			'tax_class_id' => (int)$tax_class_id,
			'rates' => $rates
		);

		$json = json_encode($context);
		return ($json === false) ? '{}' : $json;
	}

	public function getAnchorPrice($anchor_price_id) {
		$language_id = $this->getCatalogLanguageId();
		$query = $this->db->query("SELECT ap.*, ap.verification_status AS recorded_status, CASE WHEN ap.verification_status = 'confirmed' AND ap.source <> 'admin_nn132' THEN 'pending' ELSE ap.verification_status END AS verification_status, p.model, p.sku, p.status AS product_status, p.date_added AS product_date_added, pd.name AS product_name, m.name AS manufacturer FROM `" . DB_PREFIX . "anchor_price` ap LEFT JOIN `" . DB_PREFIX . "product` p ON (p.product_id = ap.product_id) LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (pd.product_id = ap.product_id AND pd.language_id = '" . (int)$language_id . "') LEFT JOIN `" . DB_PREFIX . "manufacturer` m ON (m.manufacturer_id = p.manufacturer_id) WHERE ap.anchor_price_id = '" . (int)$anchor_price_id . "' LIMIT 1");
		return $query->row;
	}

	public function getAnchorPrices($data = array()) {
		$language_id = $this->getCatalogLanguageId();
		$sql = "SELECT ap.*, ap.verification_status AS recorded_status, CASE WHEN ap.verification_status = 'confirmed' AND ap.source <> 'admin_nn132' THEN 'pending' ELSE ap.verification_status END AS verification_status, p.model, p.sku, p.status AS product_status, p.price AS current_price, pd.name AS product_name, m.name AS manufacturer FROM `" . DB_PREFIX . "anchor_price` ap LEFT JOIN `" . DB_PREFIX . "product` p ON (p.product_id = ap.product_id) LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (pd.product_id = ap.product_id AND pd.language_id = '" . (int)$language_id . "') LEFT JOIN `" . DB_PREFIX . "manufacturer` m ON (m.manufacturer_id = p.manufacturer_id) WHERE ap.store_id = '" . self::STORE_ID . "'";
		$sql .= $this->buildFilterSql($data);

		$sorts = array(
			'product_name' => 'pd.name',
			'model' => 'p.model',
			'price' => 'ap.price',
			'gross_price' => 'ap.gross_price',
			'reference_date' => 'ap.reference_date',
			'verification_status' => 'ap.verification_status'
		);
		$sort = isset($data['sort']) && isset($sorts[$data['sort']]) ? $sorts[$data['sort']] : 'pd.name';
		$order = isset($data['order']) && strtoupper($data['order']) === 'DESC' ? 'DESC' : 'ASC';
		$sql .= " ORDER BY " . $sort . " " . $order . ", ap.anchor_price_id ASC";

		if (isset($data['start']) || isset($data['limit'])) {
			$start = isset($data['start']) ? max(0, (int)$data['start']) : 0;
			$limit = isset($data['limit']) ? max(1, (int)$data['limit']) : 20;
			$sql .= " LIMIT " . $start . "," . $limit;
		}

		return $this->db->query($sql)->rows;
	}

	public function getTotalAnchorPrices($data = array()) {
		$language_id = $this->getCatalogLanguageId();
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "anchor_price` ap LEFT JOIN `" . DB_PREFIX . "product` p ON (p.product_id = ap.product_id) LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (pd.product_id = ap.product_id AND pd.language_id = '" . (int)$language_id . "') WHERE ap.store_id = '" . self::STORE_ID . "'";
		$sql .= $this->buildFilterSql($data);
		$query = $this->db->query($sql);
		return (int)$query->row['total'];
	}

	private function buildFilterSql($data) {
		$sql = '';

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$value = $this->db->escape($data['filter_model']);
			$sql .= " AND (p.model LIKE '%" . $value . "%' OR p.sku LIKE '%" . $value . "%')";
		}

		if (!empty($data['filter_status']) && in_array($data['filter_status'], array('confirmed', 'pending', 'disabled'), true)) {
			$sql .= " AND (CASE WHEN ap.verification_status = 'confirmed' AND ap.source <> 'admin_nn132' THEN 'pending' ELSE ap.verification_status END) = '" . $this->db->escape($data['filter_status']) . "'";
		}

		if (!empty($data['filter_date_from'])) {
			$sql .= " AND ap.reference_date >= '" . $this->db->escape($data['filter_date_from']) . "'";
		}

		if (!empty($data['filter_date_to'])) {
			$sql .= " AND ap.reference_date <= '" . $this->db->escape($data['filter_date_to']) . "'";
		}

		return $sql;
	}

	public function getMissingProductCount($active_only = true) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "product` p INNER JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p2s.product_id = p.product_id AND p2s.store_id = '" . self::STORE_ID . "') LEFT JOIN `" . DB_PREFIX . "anchor_price` ap ON (ap.product_id = p.product_id AND ap.store_id = p2s.store_id) WHERE ap.anchor_price_id IS NULL";
		if ($active_only) {
			$now = new DateTime('now', new DateTimeZone('Europe/Zagreb'));
			$sql .= " AND p.status = '1' AND p.date_available <= '" . $this->db->escape($now->format('Y-m-d')) . "'";
		}
		$query = $this->db->query($sql);
		return (int)$query->row['total'];
	}

	public function updateAnchorPrice($anchor_price_id, array $data, $reason, $created_by) {
		$before = $this->getAnchorPrice((int)$anchor_price_id);
		if (!$before) {
			return false;
		}

		$status = isset($data['verification_status']) ? $data['verification_status'] : '';
		if (!in_array($status, array('confirmed', 'pending', 'disabled'), true)) {
			throw new Exception('Invalid anchor price status.');
		}
		$reference_date = $data['reference_date'];
		$review = array();
		$rule_code = $before['rule_code'];
		if ($status === 'confirmed') {
			$today = new DateTime('now', new DateTimeZone('Europe/Zagreb'));
			$review = AnchorPriceHistory::review($data, $today->format('Y-m-d'));
			$rule_code = $review['rule_code'];
		}

		$reason = trim($reason);
		if (utf8_strlen($reason) < 3 || utf8_strlen($reason) > 255) {
			throw new Exception('An audit reason is required.');
		}

		$tax_context = $this->buildTaxContext((float)$data['price'], (int)$before['tax_class_id']);
		$tax_context_data = json_decode($tax_context, true);
		if (is_array($tax_context_data)) {
			$tax_context_data['manual_edit'] = true;
			$tax_context_data['history_review'] = $review;
			$tax_context_data['entered_gross_price'] = number_format((float)$data['gross_price'], 4, '.', '');
			$encoded_context = json_encode($tax_context_data);
			if ($encoded_context !== false) {
				$tax_context = $encoded_context;
			}
		}

		$after = array(
			'price' => number_format((float)$data['price'], 4, '.', ''),
			'gross_price' => number_format((float)$data['gross_price'], 4, '.', ''),
			'currency_code' => $before['currency_code'],
			'tax_class_id' => (int)$before['tax_class_id'],
			'tax_context' => $tax_context,
			'reference_date' => $reference_date,
			'rule_code' => $rule_code,
			'source' => 'admin_nn132',
			'verification_status' => $status
		);
		$before_snapshot = $this->snapshotFromRow($before);

		$this->db->query('START TRANSACTION');
		try {
			$this->db->query("UPDATE `" . DB_PREFIX . "anchor_price` SET price = '" . (float)$after['price'] . "', gross_price = '" . (float)$after['gross_price'] . "', tax_context = '" . $this->db->escape($after['tax_context']) . "', reference_date = '" . $this->db->escape($after['reference_date']) . "', rule_code = '" . $this->db->escape($after['rule_code']) . "', source = 'admin_nn132', verification_status = '" . $this->db->escape($status) . "', date_modified = NOW() WHERE anchor_price_id = '" . (int)$anchor_price_id . "'");
			$this->addAudit((int)$anchor_price_id, (int)$before['product_id'], (int)$before['store_id'], 'update', $before_snapshot, $after, $reason, (int)$created_by);
			$this->db->query('COMMIT');
		} catch (Exception $exception) {
			$this->db->query('ROLLBACK');
			throw $exception;
		}

		return true;
	}

	private function snapshotFromRow(array $row) {
		return array(
			'price' => $row['price'],
			'gross_price' => $row['gross_price'],
			'currency_code' => $row['currency_code'],
			'tax_class_id' => (int)$row['tax_class_id'],
			'tax_context' => $row['tax_context'],
			'reference_date' => $row['reference_date'],
			'rule_code' => $row['rule_code'],
			'source' => $row['source'],
			'verification_status' => $row['recorded_status'] ?? $row['verification_status']
		);
	}

	private function addAudit($anchor_price_id, $product_id, $store_id, $action, $before, array $after, $reason, $created_by) {
		$before_json = $before === null ? '{}' : json_encode($before);
		$after_json = json_encode($after);
		$this->db->query("INSERT INTO `" . DB_PREFIX . "anchor_price_audit` SET anchor_price_id = '" . (int)$anchor_price_id . "', product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "', user_id = '" . (int)$created_by . "', action = '" . $this->db->escape($action) . "', old_data = '" . $this->db->escape($before_json === false ? '{}' : $before_json) . "', new_data = '" . $this->db->escape($after_json === false ? '{}' : $after_json) . "', reason = '" . $this->db->escape($reason) . "', date_added = NOW()");
	}

	public function getAuditTrail($anchor_price_id, $limit = 25) {
		$limit = max(1, min(100, (int)$limit));
		$query = $this->db->query("SELECT a.*, u.username FROM `" . DB_PREFIX . "anchor_price_audit` a LEFT JOIN `" . DB_PREFIX . "user` u ON (u.user_id = a.user_id) WHERE a.anchor_price_id = '" . (int)$anchor_price_id . "' ORDER BY a.audit_id DESC LIMIT " . $limit);
		return $query->rows;
	}

	public function generateDailyPublications($store_id = 0, $created_by = 0, $force = false) {
		if ((int)$store_id !== self::STORE_ID) { throw new RuntimeException('Unsupported store.'); }
		$this->syncMissingProducts($created_by);
		$result = $this->publisher()->generatePublication($force, $created_by);
		if (!$result['success']) { throw new RuntimeException($result['error']); }
		$publications = array();
		foreach ($result['locations'] as $entry) {
			$publications[] = $entry['publication'] + array('existing' => $entry['existing'], 'warnings' => $result['warnings']);
		}
		return $publications;
	}

	protected function publisher() {
		return new AnchorPricePublication($this->registry);
	}

	public function generatePublicationCsv($store_id = 0, $created_by = 0, $location_code = 'PJ3', $force = false) {
		$store_id = (int)$store_id;
		$location_code = strtoupper(trim($location_code));
		if ($store_id !== self::STORE_ID || !in_array($location_code, array('PJ1', 'PJ3'), true)) {
			throw new Exception('Unsupported store or sales location.');
		}

		$publications = $this->generateDailyPublications($store_id, (int)$created_by, (bool)$force);
		foreach ($publications as $publication) {
			if ($publication['location_code'] === $location_code) {
				return $publication;
			}
		}

		throw new Exception('Requested sales-location publication was not generated.');
	}

	public function getPublicationWarnings() {
		return $this->publisher()->getPublicationWarnings();
	}

	public function archiveExpiredPublications() {
		$this->publisher()->expireOldPublications(new DateTime('now', new DateTimeZone('Europe/Zagreb')));
	}

	public function getPublications($limit = 10) {
		$limit = max(1, min(100, (int)$limit));
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "anchor_price_publication` WHERE store_id = '" . self::STORE_ID . "' ORDER BY publication_id DESC LIMIT " . $limit);
		return $query->rows;
	}

	public function getDailyPublicationState() {
		$now = new DateTime('now', new DateTimeZone('Europe/Zagreb'));
		$start = clone $now;
		$start->setTime(0, 0, 0);
		$end = clone $start;
		$end->modify('+1 day');
		$query = $this->db->query("SELECT DISTINCT location_code FROM `" . DB_PREFIX . "anchor_price_publication` WHERE store_id = '" . self::STORE_ID . "' AND status = 'published' AND LEFT(filename, 9) <> 'naknadno_' AND published_at >= '" . $this->db->escape($start->format('Y-m-d H:i:s')) . "' AND published_at < '" . $this->db->escape($end->format('Y-m-d H:i:s')) . "'");
		$published = array();
		foreach ($query->rows as $row) {
			$published[] = $row['location_code'];
		}
		return array(
			'due' => (int)$now->format('Hi') >= 1100,
			'published' => $published,
			'missing' => array_values(array_diff(array('PJ1', 'PJ3'), $published))
		);
	}

	public function getPublication($publication_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "anchor_price_publication` WHERE publication_id = '" . (int)$publication_id . "' AND store_id = '" . self::STORE_ID . "' LIMIT 1");
		return $query->row;
	}

	public function getPublicationPath(array $publication) {
		$relative_path = isset($publication['relative_path']) ? str_replace('\\', '/', $publication['relative_path']) : '';
		if (strpos($relative_path, 'anchor_price/') !== 0 || strpos($relative_path, '..') !== false || substr($relative_path, -4) !== '.csv') {
			return false;
		}
		$path = rtrim(DIR_DOWNLOAD, '/\\') . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relative_path);
		return is_file($path) ? $path : false;
	}

	public function publicationFileIsValid(array $publication, $path = false) {
		if ($path === false) {
			$path = $this->getPublicationPath($publication);
		}

		$expected_checksum = isset($publication['checksum_sha256']) ? strtolower(trim((string)$publication['checksum_sha256'])) : '';
		if (!$path || !is_readable($path) || !preg_match('/^[a-f0-9]{64}$/', $expected_checksum)) {
			return false;
		}

		$actual_checksum = hash_file('sha256', $path);
		if ($actual_checksum === false) {
			return false;
		}

		return function_exists('hash_equals') ? hash_equals($expected_checksum, strtolower($actual_checksum)) : $expected_checksum === strtolower($actual_checksum);
	}

	private function getCatalogLanguageId() {
		if ($this->catalog_language_id !== null) {
			return $this->catalog_language_id;
		}

		$code = $this->config->get('config_language');
		$query = $this->db->query("SELECT language_id FROM `" . DB_PREFIX . "language` WHERE code = '" . $this->db->escape($code) . "' LIMIT 1");
		$this->catalog_language_id = $query->num_rows ? (int)$query->row['language_id'] : (int)$this->config->get('config_language_id');
		return $this->catalog_language_id;
	}
}
