<?php
$return_table = DB_PREFIX . 'return';
$url_alias_table = DB_PREFIX . 'url_alias';

$columns = array(
	'invoice_number' => "ALTER TABLE `" . $return_table . "` ADD `invoice_number` VARCHAR(64) NOT NULL DEFAULT '' AFTER `order_id`",
	'invoice_date'  => "ALTER TABLE `" . $return_table . "` ADD `invoice_date` DATE NULL AFTER `invoice_number`",
	'refund_iban'   => "ALTER TABLE `" . $return_table . "` ADD `refund_iban` VARCHAR(64) NOT NULL DEFAULT '' AFTER `telephone`",
	'return_items'  => "ALTER TABLE `" . $return_table . "` ADD `return_items` TEXT NULL AFTER `quantity`"
);

foreach ($columns as $column => $sql) {
	$query = $this->db->query("SHOW COLUMNS FROM `" . $return_table . "` LIKE '" . $this->db->escape($column) . "'");

	if (!$query->num_rows) {
		$this->db->query($sql);
	}
}

$this->db->query("DELETE FROM `" . $url_alias_table . "` WHERE `query` = 'account/return/add' OR `keyword` = 'obrazac-za-povrat'");

$url_alias_columns = $this->db->query("SHOW COLUMNS FROM `" . $url_alias_table . "` LIKE 'language_id'");

if ($url_alias_columns->num_rows) {
	$this->db->query("INSERT INTO `" . $url_alias_table . "` SET `query` = 'account/return/add', `keyword` = 'obrazac-za-povrat', `language_id` = '" . (int)$this->config->get('config_language_id') . "'");
} else {
	$this->db->query("INSERT INTO `" . $url_alias_table . "` SET `query` = 'account/return/add', `keyword` = 'obrazac-za-povrat'");
}

$setting_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `store_id` = '0' AND `code` = 'config' AND `key` = 'config_seo_url'");

if ($setting_query->num_rows) {
	$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = '1' WHERE `store_id` = '0' AND `code` = 'config' AND `key` = 'config_seo_url'");
}

$language_ids = array();
$language_query = $this->db->query("SELECT `language_id`, `code` FROM `" . DB_PREFIX . "language` WHERE `code` IN ('hr-hr', 'en-gb')");

foreach ($language_query->rows as $language) {
	$language_ids[$language['code']] = (int)$language['language_id'];
}

$reasons = array(
	'hr-hr' => array(
		1 => 'Preveliko',
		2 => 'Premalo',
		3 => 'Predugo',
		4 => 'Prekratko',
		5 => 'Razlikuje se od proizvoda na slici',
		6 => 'Kvaliteta proizvoda',
		7 => 'Pogrešan proizvod',
		8 => 'Ne stoji mi dobro'
	),
	'en-gb' => array(
		1 => 'Too large',
		2 => 'Too small',
		3 => 'Too long',
		4 => 'Too short',
		5 => 'Differs from the product photo',
		6 => 'Product quality',
		7 => 'Wrong product',
		8 => 'Does not fit well'
	)
);

foreach ($reasons as $code => $items) {
	if (empty($language_ids[$code])) {
		continue;
	}

	foreach ($items as $return_reason_id => $name) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "return_reason` SET `return_reason_id` = '" . (int)$return_reason_id . "', `language_id` = '" . (int)$language_ids[$code] . "', `name` = '" . $this->db->escape($name) . "' ON DUPLICATE KEY UPDATE `name` = VALUES(`name`)");
	}
}

$this->cache->delete('return_reason');
