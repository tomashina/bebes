<?php
class AnchorPriceLegacyPolicy {
	const STORE_ID = 0;
	const CURRENCY_CODE = 'EUR';
	const CUTOVER_DATE = '2026-09-10';

	public static function isEligible(array $row, $calculated_gross_price, $store_id, $today) {
		$required = array(
			'store_id',
			'price',
			'gross_price',
			'currency_code',
			'tax_class_id',
			'reference_date',
			'rule_code',
			'source',
			'verification_status',
			'product_price',
			'product_tax_class_id',
			'product_status',
			'product_date_added',
			'product_date_available',
			'current_currency_code'
		);

		foreach ($required as $field) {
			if (!array_key_exists($field, $row)) {
				return false;
			}
		}

		$product_date_added = substr((string)$row['product_date_added'], 0, 10);
		$product_date_available = substr((string)$row['product_date_available'], 0, 10);
		$today = substr((string)$today, 0, 10);

		if ((int)$store_id !== self::STORE_ID
			|| (int)$row['store_id'] !== self::STORE_ID
			|| (int)$row['product_status'] !== 1
			|| !self::validDate($today)
			|| !self::validDate($product_date_added)
			|| !self::validDate($product_date_available)
			|| $product_date_added === '0000-00-00'
			|| $product_date_available > $today
			|| ($product_date_available !== '0000-00-00' && $product_date_available > self::CUTOVER_DATE)
			|| $product_date_added > self::CUTOVER_DATE
			|| $row['verification_status'] !== 'pending'
			|| $row['rule_code'] !== 'baseline_2026_09_10'
			|| $row['reference_date'] !== self::CUTOVER_DATE
			|| strtoupper(trim((string)$row['currency_code'])) !== self::CURRENCY_CODE
			|| strtoupper(trim((string)$row['current_currency_code'])) !== self::CURRENCY_CODE
			|| !in_array($row['source'], array('migration_backfill_2026_09_10', 'install'), true)
			|| !is_numeric($row['price'])
			|| !is_numeric($row['gross_price'])
			|| !is_numeric($row['product_price'])
			|| !is_numeric($calculated_gross_price)
			|| (int)$row['tax_class_id'] !== (int)$row['product_tax_class_id']
			|| self::decimal($row['price']) !== self::decimal($row['product_price'])
			|| self::decimal($row['gross_price']) !== self::decimal($calculated_gross_price)) {
			return false;
		}

		return true;
	}

	private static function decimal($value) {
		return number_format((float)$value, 4, '.', '');
	}

	private static function validDate($value) {
		return (bool)preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', (string)$value);
	}
}
