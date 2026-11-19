<?php
require_once dirname(__DIR__) . '/system/library/anchor_price_history.php';

foreach (array('may' => '2025-05-02', 'other' => '2026-09-10') as $group => $baseline) {
	foreach (array('2024-12-01', $baseline, '2026-10-01') as $first) {
		foreach (array('yes', 'no') as $sale) {
			$date = max($baseline, $first);
			$data = array('history_group' => $group, 'first_offer' => $first, 'reference_sale' => $sale, 'reference_date' => $date);
			$review = AnchorPriceHistory::review($data, '2026-11-19');
			$expected = $sale === 'no' ? 'retail_on_date' : ($group === 'may' ? 'lowest_30_days_before_sale' : 'previous_retail_price');
			if ($review['reference_date'] !== $date || $review['price_basis'] !== $expected || (($review['rule_code'] === 'first_listing') !== ($first > $baseline))) {
				throw new Exception('Incorrect history rule');
			}
		}
	}
}
foreach (array('first_offer' => '2026-02-30', 'reference_date' => '2025-05-02', 'history_group' => '', 'reference_sale' => '') as $key => $bad) {
	try {
		AnchorPriceHistory::review(array_replace($data, array($key => $bad)), '2026-11-19');
		throw new Exception('Invalid historical evidence accepted: ' . $key);
	} catch (InvalidArgumentException $correct) {}
}
echo "OK: reference dates, first offers and sale rules for both groups.\n";
