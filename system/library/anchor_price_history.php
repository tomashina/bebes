<?php
class AnchorPriceHistory {
	public static function review(array $data, $today) {
		$group = $data['history_group'] ?? '';
		$first = $data['first_offer'] ?? '';
		$sale = $data['reference_sale'] ?? '';
		$date = DateTime::createFromFormat('!Y-m-d', $first);
		if (!in_array($group, array('may', 'other'), true) || !in_array($sale, array('yes', 'no'), true) ||
			!$date || $date->format('Y-m-d') !== $first || $first > $today) {
			throw new InvalidArgumentException('Provjerite skupinu robe, datum prve ponude i akciju na referentni dan.');
		}
		$baseline = $group === 'may' ? '2025-05-02' : '2026-09-10';
		$reference = max($baseline, $first);
		if ($data['reference_date'] !== $reference) {
			throw new InvalidArgumentException('Za odabranu skupinu i datum prve ponude referentni datum je ' . $reference . '.');
		}
		$basis = 'retail_on_date';
		if ($sale === 'yes') {
			$basis = $group === 'may' ? 'lowest_30_days_before_sale' : 'previous_retail_price';
		}
		return array('history_group' => $group, 'first_offer' => $first, 'reference_sale' => $sale,
			'price_basis' => $basis, 'reference_date' => $reference,
			'rule_code' => $first > $baseline ? 'first_listing' : 'baseline_' . str_replace('-', '_', $baseline));
	}
}
