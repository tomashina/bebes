<?php
require_once(dirname(__DIR__) . '/system/library/anchor_price_legacy_policy.php');

$root = dirname(__DIR__);
$mirror_pairs = array(
	'legacy policy' => array($root . '/system/library/anchor_price_legacy_policy.php', $root . '/_ocmod/bebes_anchor_prices_v1.0.0/upload/system/library/anchor_price_legacy_policy.php'),
	'admin model' => array($root . '/admin/model/extension/module/anchor_price.php', $root . '/_ocmod/bebes_anchor_prices_v1.0.0/upload/admin/model/extension/module/anchor_price.php'),
	'catalog model' => array($root . '/catalog/model/extension/module/anchor_price.php', $root . '/_ocmod/bebes_anchor_prices_v1.0.0/upload/catalog/model/extension/module/anchor_price.php')
);

foreach ($mirror_pairs as $name => $paths) {
	if (!is_file($paths[0]) || !is_file($paths[1]) || hash_file('sha256', $paths[0]) !== hash_file('sha256', $paths[1])) {
		fwrite(STDERR, 'Packaged ' . $name . " is missing or differs from the source copy.\n");
		exit(1);
	}
}

$base = array(
	'store_id' => 0,
	'price' => '3.3300',
	'gross_price' => '3.3300',
	'currency_code' => 'EUR',
	'tax_class_id' => 0,
	'reference_date' => '2026-09-10',
	'rule_code' => 'baseline_2026_09_10',
	'source' => 'migration_backfill_2026_09_10',
	'verification_status' => 'pending',
	'product_price' => '3.3300',
	'product_tax_class_id' => 0,
	'product_status' => 1,
	'product_date_added' => '2020-10-07 21:13:07',
	'product_date_available' => '2020-10-09',
	'current_currency_code' => 'EUR'
);

$cases = array(
	'unchanged legacy baseline' => array(array(), '3.3300', 0, true),
	'install legacy baseline' => array(array('source' => 'install'), '3.3300', 0, true),
	'changed net price' => array(array('product_price' => '3.3400'), '3.3400', 0, false),
	'changed gross price' => array(array(), '3.3400', 0, false),
	'changed tax class' => array(array('product_tax_class_id' => 1), '3.3300', 0, false),
	'wrong anchor currency' => array(array('currency_code' => 'USD'), '3.3300', 0, false),
	'wrong store currency' => array(array('current_currency_code' => 'USD'), '3.3300', 0, false),
	'first listing' => array(array('rule_code' => 'first_listing'), '3.3300', 0, false),
	'manual source' => array(array('source' => 'admin'), '3.3300', 0, false),
	'inactive product' => array(array('product_status' => 0), '3.3300', 0, false),
	'future availability' => array(array('product_date_available' => '2026-10-01'), '3.3300', 0, false),
	'post-cutover availability' => array(array('product_date_available' => '2026-09-20'), '3.3300', 0, false),
	'post-cutover product' => array(array('product_date_added' => '2026-09-11 00:00:00'), '3.3300', 0, false),
	'already confirmed' => array(array('verification_status' => 'confirmed'), '3.3300', 0, false),
	'unsupported store' => array(array('store_id' => 1), '3.3300', 1, false)
);

$failed = 0;
foreach ($cases as $name => $case) {
	$row = array_replace($base, $case[0]);
	$actual = AnchorPriceLegacyPolicy::isEligible($row, $case[1], $case[2], '2026-09-30');
	if ($actual !== $case[3]) {
		fwrite(STDERR, $name . ': expected ' . ($case[3] ? 'eligible' : 'blocked') . ', got ' . ($actual ? 'eligible' : 'blocked') . ".\n");
		$failed++;
	}
}

if ($failed) {
	exit(1);
}

echo 'OK: ' . count($cases) . " legacy reconciliation policy cases passed.\n";
