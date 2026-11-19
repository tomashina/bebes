<?php
class BebesPriceCsv {
	const HEADER = array('Naziv robe', 'Šifra robe', 'Marka robe', 'Neto količina', 'Jedinica mjere robe', 'Maloprodajna cijena robe', 'Poseban oblik prodaje', 'Cijena za jedinicu mjere robe', 'Barkod robe', 'EAN barkod robe', 'Bazna cijena robe');

	public static function filename($location, $sequence, DateTime $now) {
		$site = $location === 'PJ1' ? 'prodavaonica_Nikole-Jurisica-8-10000-Zagreb_PJ1' : 'internetska-trgovina_atelierbebes-com_PJ3';
		return '59774997564_' . $site . '_ROBA_' . sprintf('%06d', $sequence) . '_' . $now->format('Ymd_Hi') . '.csv';
	}

	public static function prepare(array $rows) {
		if (!$rows) {
			throw new RuntimeException('Cjenik nema artikala.');
		}
		$file = tmpfile();
		if (!$file || fwrite($file, "\xEF\xBB\xBF") !== 3 || fputcsv($file, self::HEADER, ';', '"', "\0") === false) {
			throw new RuntimeException('Nije moguće pripremiti CSV.');
		}
		foreach ($rows as $row) {
			if (count($row) !== count(self::HEADER) || fputcsv($file, $row, ';', '"', "\0") === false) {
				throw new RuntimeException('Neispravan redak ili pogreška pri zapisu CSV-a.');
			}
		}
		rewind($file);
		$hash = hash_init('sha256');
		hash_update_stream($hash, $file);
		rewind($file);
		return array('stream' => $file, 'count' => count($rows), 'checksum' => hash_final($hash));
	}

	public static function validGtin($value) {
		$value = (string)$value;
		if (!preg_match('/^(?:[0-9]{8}|[0-9]{12,14})$/D', $value)) {
			return false;
		}
		$sum = 0;
		$weight = 3;
		for ($index = strlen($value) - 2; $index >= 0; $index--) {
			$sum += (int)$value[$index] * $weight;
			$weight = 4 - $weight;
		}
		return (10 - $sum % 10) % 10 === (int)substr($value, -1);
	}

	// Use catalog metadata only when the merchant identifiers identify one product.
	public static function completeRetailRows(array &$rows, array $products) {
		$ids = $brands = array();
		foreach ($products as $i => $p) {
			foreach (array('model', 'sku', 'ean', 'jan', 'isbn') as $field) {
				$id = trim((string)($p[$field] ?? ''));
				if ($id !== '') { $ids[$id][$i] = true; }
			}
			$brand = trim(html_entity_decode((string)$p['manufacturer'], ENT_QUOTES, 'UTF-8'));
			if (mb_strlen($brand, 'UTF-8') >= 3) { $brands[$brand] = true; }
		}
		foreach ($rows as &$row) {
			$matches = ($ids[$row[1]] ?? array()) + ($ids[$row[8]] ?? array());
			if (count($matches) === 1) {
				$p = $products[key($matches)];
				if ($row[2] === '') { $row[2] = trim(html_entity_decode((string)$p['manufacturer'], ENT_QUOTES, 'UTF-8')); }
				$qty = str_replace(',', '.', trim((string)($p['net_quantity'] ?? '')));
				if ($qty === '' && ($count = self::declaredDiaperCount($p['name'] ?? '')) > 0) {
					$qty = (string)$count;
					$p['unit'] = 'kom';
				}
				$unit = mb_strtolower(trim((string)($p['unit'] ?? '')), 'UTF-8');
				if ($row[3] === '' && preg_match('/^\d{1,12}(?:\.\d{1,6})?$/D', $qty) && (float)$qty > 0 && $unit !== '') {
					if ($unit === 'g' || $unit === 'ml') {
						$qty = rtrim(rtrim(number_format((float)$qty / 1000, 9, '.', ''), '0'), '.');
						$unit = $unit === 'g' ? 'kg' : 'l';
					}
					$row[3] = str_replace('.', ',', $qty);
					$row[4] = $unit;
					$row[7] = number_format((float)str_replace(',', '.', $row[5]) / (float)$qty, 2, ',', '');
				}
				if ($row[8] === '' && BebesPriceCsv::validGtin($p['ean'] ?? '')) {
					$row[8] = $p['ean'];
					if (in_array(strlen($row[8]), array(8,13), true)) { $row[9] = $row[8]; }
				}
			}
			if ($row[2] === '') {
				$named = array();
				foreach ($brands as $brand => $unused) {
					if (preg_match('/(?<![\pL\pN])' . preg_quote($brand, '/') . '(?![\pL\pN])/iu', $row[0])) { $named[] = $brand; }
				}
				if (count($named) === 1) { $row[2] = $named[0]; }
			}
			if ($row[3] === '') {
				$count = self::declaredDiaperCount($row[0]);
				if ($count > 0) {
					$row[3] = (string)$count;
					$row[4] = 'kom';
					$row[7] = number_format((float)str_replace(',', '.', $row[5]) / $count, 2, ',', '');
				}
			}
		}
		unset($row);
	}

	// Use only explicit pack counts for diapers. Dimensions and clothing sizes are not pack counts.
	private static function declaredDiaperCount($name) {
		if (!preg_match('/\b(?:tetra|pamučn[ae])\s+pelen[ae]\b/iu', $name)) { return 0; }
		if (preg_match('/\b([1-9][0-9]?)\s*kom(?:ada|ad|\.)?\b/iu', $name, $m) ||
			preg_match('/\bkomplet\s+([1-9][0-9]?)\s+(?:pamučne|tetra)\s+pelene\b/iu', $name, $m)) {
			return (int)$m[1];
		}
		return 0;
	}

	public static function webRow(array $p, $tax, $default_unit = 'kom') {
		$special = $p['special'] !== null && $p['special'] !== '';
		$discount = isset($p['discount']) && (float)$p['discount'] > 0;
		$current = $special ? $p['special'] : ($discount ? $p['discount'] : $p['price']);
		$gross = $tax->calculate((float)$current, (int)$p['tax_class_id'], true);
		$barcode = '';
		foreach (array('ean', 'jan', 'isbn') as $key) {
			if (self::validGtin(trim((string)$p[$key]))) {
				$barcode = trim($p[$key]);
				break;
			}
		}
		$ean = trim((string)$p['ean']);
		$qty = str_replace(',', '.', trim((string)($p['net_quantity'] ?? '')));
		$unit = trim((string)($p['unit'] ?? ''));
		if ($qty === '' && ($count = self::declaredDiaperCount($p['name'])) > 0) {
			$qty = (string)$count;
			$unit = 'kom';
		}
		$unit_price = '';
		if (preg_match('/^\d{1,12}(?:\.\d{1,6})?$/D', $qty) && (float)$qty > 0 && $unit !== '') {
			// Normalize small package units before calculating the comparison price.
			if ($unit === 'g' || $unit === 'ml') {
				$qty = rtrim(rtrim(number_format((float)$qty / 1000, 9, '.', ''), '0'), '.');
				$unit = $unit === 'g' ? 'kg' : 'l';
			}
			$unit_price = number_format($gross / (float)$qty, 2, ',', '');
		}
		$confirmed = !empty($p['anchor_price_id']) && $p['verification_status'] === 'confirmed';
		return array(trim(html_entity_decode((string)$p['name'], ENT_QUOTES, 'UTF-8')), trim((string)$p['model']),
			trim(html_entity_decode((string)$p['manufacturer'], ENT_QUOTES, 'UTF-8')), $unit_price === '' ? '' : str_replace('.', ',', $qty),
			($unit === '' && $qty === '' ? $default_unit : $unit), number_format($gross, 2, ',', ''), ($special || $discount) ? 'DA' : 'NE', $unit_price, $barcode,
			self::validGtin($ean) && in_array(strlen($ean), array(8, 13), true) ? $ean : '',
			$confirmed ? number_format((float)$p['anchor_gross_price'], 2, ',', '') : '');
	}
}
