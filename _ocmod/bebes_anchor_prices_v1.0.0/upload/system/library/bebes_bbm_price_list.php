<?php
require_once __DIR__ . '/bebes_price_csv.php';

class BebesBbmPriceList {
	const URL = 'https://t02.bbm.hr/altitudo/sidrene_cijene.zip';
	const MAX_BYTES = 16777216;

	private static function passwordPath() {
		return defined('BEBES_BBM_PASSWORD_FILE') ? BEBES_BBM_PASSWORD_FILE : '/home/amds/.config/bebes-bbm-password';
	}

	public static function savePassword($password) {
		if (strpbrk($password, "\r\n\0") !== false) {
			throw new RuntimeException('BBM lozinka mora biti u jednom retku.');
		}
		$path = self::passwordPath();
		$directory = dirname($path);
		if (!is_dir($directory) && !@mkdir($directory, 0700, true) && !is_dir($directory)) {
			throw new RuntimeException('Privatna mapa za BBM lozinku nije dostupna.');
		}
		$temp = @tempnam($directory, '.bbm-');
		if (!$temp) { throw new RuntimeException('Nije moguće spremiti BBM lozinku.'); }
		try {
			if (dirname($temp) !== realpath($directory) || !chmod($temp, 0600) ||
				file_put_contents($temp, $password) !== strlen($password) || !rename($temp, $path)) {
				throw new RuntimeException('Nije moguće spremiti BBM lozinku.');
			}
		} finally {
			if (is_file($temp)) { unlink($temp); }
		}
	}

	public static function download(DateTime $now) {
		$path = self::passwordPath();
		$password = is_readable($path) ? rtrim(file_get_contents($path), "\r\n") : '';
		if ($password === '') {
			throw new RuntimeException('PJ1: nedostaje BBM lozinka u privatnoj konfiguraciji poslužitelja.');
		}
		$temp = tmpfile();
		if (!$temp) {
			throw new RuntimeException('PJ1: nema privremene datoteke za preuzimanje.');
		}
		$curl = curl_init(self::URL);
		curl_setopt_array($curl, array(
			CURLOPT_HTTPAUTH => CURLAUTH_ANY, CURLOPT_USERPWD => 'iis_altitudo:' . $password,
			CURLOPT_PROTOCOLS => CURLPROTO_HTTPS, CURLOPT_FOLLOWLOCATION => false, CURLOPT_FAILONERROR => true,
			CURLOPT_CONNECTTIMEOUT => 10, CURLOPT_TIMEOUT => 60, CURLOPT_FILETIME => true,
			CURLOPT_WRITEFUNCTION => function ($curl, $bytes) use ($temp) {
				return ftell($temp) + strlen($bytes) <= self::MAX_BYTES ? fwrite($temp, $bytes) : 0;
			}
		));
		try {
			$ok = curl_exec($curl);
			$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			$modified = curl_getinfo($curl, CURLINFO_FILETIME);
			if (!$ok || $status !== 200 || !fflush($temp)) {
				throw new RuntimeException('PJ1: BBM preuzimanje nije uspjelo (HTTP ' . (int)$status . ', cURL ' . curl_errno($curl) . ').');
			}
			return self::readZip(stream_get_meta_data($temp)['uri'], $now, $modified);
		} finally {
			curl_close($curl);
			fclose($temp);
		}
	}

	public static function readZip($path, DateTime $now, $modified = -1) {
		$zip = new ZipArchive();
		if ($zip->open($path) !== true) {
			throw new RuntimeException('PJ1: preuzeta datoteka nije čitljiv ZIP.');
		}
		try {
			$entry = null;
			for ($i = 0; $i < $zip->numFiles; $i++) {
				$item = $zip->statIndex($i);
				if (substr($item['name'], -1) === '/' || strpos($item['name'], '__MACOSX/') === 0) {
					continue;
				}
				if (!preg_match('/\.csv$/iD', $item['name']) || $entry || !$item['size'] || $item['size'] > self::MAX_BYTES) {
					throw new RuntimeException('PJ1: očekuje se jedan CSV u BBM ZIP-u. Provjerite strukturu izvoza.');
				}
				$entry = $item;
			}
			if (!$entry) {
				throw new RuntimeException('PJ1: ZIP ne sadrži CSV.');
			}
			$time = new DateTime('@' . ($modified > 0 ? $modified : $entry['mtime']));
			if ($time->setTimezone($now->getTimezone())->format('Y-m-d') !== $now->format('Y-m-d')) {
				throw new RuntimeException('PJ1: datum BBM izvoza ne odgovara današnjem datumu.');
			}
			$file = $zip->getStream($entry['name']);
			if (!$file) {
				throw new RuntimeException('PJ1: CSV se ne može otvoriti.');
			}
			try {
				$bytes = stream_get_contents($file, self::MAX_BYTES + 1);
				if ($bytes === false || strlen($bytes) !== $entry['size'] || sprintf('%u', crc32($bytes)) !== sprintf('%u', $entry['crc']) || !preg_match('//u', $bytes) || strpos($bytes, "\0") !== false) {
					throw new RuntimeException('PJ1: oštećen CSV ili pogrešno kodiranje. Potreban je UTF-8.');
				}
			} finally {
				fclose($file);
			}
		} finally {
			$zip->close();
		}
		$stream = fopen('php://temp', 'w+b');
		try {
			fwrite($stream, preg_replace('/^\xEF\xBB\xBF/', '', $bytes));
			rewind($stream);
			return self::readRows($stream, $entry['name'], $now);
		} finally {
			fclose($stream);
		}
	}

	private static function columnName($field) {
		$field = strtr(mb_strtolower(trim($field), 'UTF-8'), array('č'=>'c', 'ć'=>'c', 'š'=>'s', 'ž'=>'z', 'đ'=>'d'));
		$field = preg_replace('/\s*\(eur\)$/', '', $field);
		return trim(preg_replace('/[ _]+/', ' ', $field));
	}

	private static function readRetailExport($stream, array $header, $filename, DateTime $now) {
		$expected = array('naziv','sifra','mjera','mpcijena','mpcijena akcija','barkod','mpcijena bazna','mpcijena bazna datum');
		if (count($header) !== count($expected) || array_diff($expected, $header) ||
			!preg_match('/^59774997564-prodavaona-nikole_jurisica_8-zagreb-pj1-roba-[0-9]+_([0-9]{8})_[0-9]{6}\.csv$/iD', $filename, $match) ||
			$match[1] !== $now->format('Ymd')) {
			throw new RuntimeException('PJ1: datum, identitet prodavaonice ili stupci BBM izvoza nisu valjani.');
		}
		$rows = $seen = array();
		while (($fields = fgetcsv($stream, 0, ';', '"', "\0")) !== false) {
			if ($fields === array(null)) { continue; }
			if (count($fields) !== count($header)) { throw new RuntimeException('PJ1: neispravan broj polja u BBM retku ' . (count($rows) + 2)); }
			$r = array_combine($header, array_map('trim', $fields));
			$code = $r['sifra'];
			if ($r['naziv'] === '' || $code === '' || isset($seen[$code])) { throw new RuntimeException('PJ1: prazna ili ponovljena šifra/naziv u BBM retku ' . (count($rows) + 2)); }
			$seen[$code] = true;
			foreach (array('mpcijena','mpcijena akcija','mpcijena bazna') as $key) {
				if ($key !== 'mpcijena' && $r[$key] === '') { continue; }
				if (!preg_match('/^\d+(?:[.,]\d{1,4})?$/D', $r[$key])) { throw new RuntimeException('PJ1: neispravna cijena za šifru ' . $code); }
				$r[$key] = number_format((float)str_replace(',', '.', $r[$key]), 2, ',', '');
			}
			$date = DateTime::createFromFormat('!d.m.Y', $r['mpcijena bazna datum'], $now->getTimezone());
			if ($r['mpcijena bazna'] !== '' && (!$date || $date->format('d.m.Y') !== $r['mpcijena bazna datum'] || $date > $now)) {
				throw new RuntimeException('PJ1: neispravan datum bazne cijene za šifru ' . $code);
			}
			$sale = $r['mpcijena akcija'] !== '';
			$barcode = $r['barkod'];
			$ean = in_array(strlen($barcode), array(8,13), true) && BebesPriceCsv::validGtin($barcode) ? $barcode : '';
			$rows[] = array($r['naziv'], $code, '', '', mb_strtolower($r['mjera'], 'UTF-8'),
				$sale ? $r['mpcijena akcija'] : $r['mpcijena'], $sale ? 'DA' : 'NE', '', $barcode, $ean, $r['mpcijena bazna']);
		}
		if (!$rows) { throw new RuntimeException('PJ1: prazan BBM izvoz.'); }
		return $rows;
	}

	private static function readRows($stream, $filename, DateTime $now) {
		$header = fgetcsv($stream, 0, ';', '"', "\0");
		$header = $header ? array_map(array(__CLASS__, 'columnName'), $header) : array();
		if (in_array('mpcijena bazna datum', $header, true)) {
			return self::readRetailExport($stream, $header, $filename, $now);
		}
		$aliases = array(
			array('naziv', 'naziv artikla', 'naziv proizvoda'), array('sifra', 'sifra artikla', 'sifra proizvoda'),
			array('marka', 'brand'), array('neto kolicina robe'), array('jedinica mjere', 'jm'),
			array('maloprodajna cijena', 'aktualna maloprodajna cijena', 'mpc'), array('poseban oblik prodaje robe', 'akcija'),
			array('cijena za jedinicu mjere', 'cijena po jedinici mjere'), array('barkod'), array('ean', 'ean barkod'),
			array('bazna cijena', 'sidrena cijena')
		);
		$columns = array();
		foreach (BebesPriceCsv::HEADER as $index => $name) {
			$accepted = array_merge(array(self::columnName($name)), $aliases[$index]);
			$matches = array_keys(array_intersect($header, $accepted));
			if (count($matches) !== 1) {
				throw new RuntimeException('PJ1: provjerite mapiranje BBM stupca »' . $name . '«. Objava je zaustavljena.');
			}
			$columns[] = $matches[0];
		}
		$rows = array();
		while (($fields = fgetcsv($stream, 0, ';', '"', "\0")) !== false) {
			if ($fields === array(null)) { continue; }
			$line = count($rows) + 2;
			if (count($fields) !== count($header)) {
				throw new RuntimeException('PJ1: pogrešan broj polja u retku ' . $line . '.');
			}
			foreach (array('skladiste' => '00001', 'sifra skladista' => '00001', 'prodajno mjesto' => 'PJ1', 'oznaka prodajnog mjesta' => 'PJ1') as $label => $expected) {
				$index = array_search($label, $header, true);
				if ($index !== false && trim($fields[$index]) !== $expected) {
					throw new RuntimeException('PJ1: pogrešno prodajno mjesto ili skladište u retku ' . $line . '.');
				}
			}
			$row = array();
			foreach ($columns as $index) { $row[] = trim($fields[$index]); }
			$row[6] = mb_strtoupper($row[6], 'UTF-8');
			if ($row[0] === '' || !in_array($row[6], array('DA', 'NE'), true)) {
				throw new RuntimeException('PJ1: provjerite naziv i oznaku akcije u retku ' . $line . '.');
			}
			foreach (array(5, 7, 10) as $index) {
				if ($row[$index] === '' && $index !== 5) { continue; }
				if (!preg_match('/^\d+(?:[,.]\d{1,4})?$/D', $row[$index])) {
					throw new RuntimeException('PJ1: neispravna cijena u retku ' . $line . '.');
				}
				$row[$index] = number_format((float)str_replace(',', '.', $row[$index]), 2, ',', '');
			}
			if (($row[3] !== '' || $row[7] !== '') && (!preg_match('/^\d+(?:[,.]\d+)?$/D', $row[3]) || (float)str_replace(',', '.', $row[3]) <= 0 || $row[4] === '' || $row[7] === '')) {
				throw new RuntimeException('PJ1: provjerite neto količinu, jedinicu i jediničnu cijenu u retku ' . $line . '.');
			}
			if ($row[9] !== '' && (!BebesPriceCsv::validGtin($row[9]) || !in_array(strlen($row[9]), array(8, 13), true))) {
				throw new RuntimeException('PJ1: neispravan EAN u retku ' . $line . ' (šifra ' . $row[1] . ').');
			}
			$verified = array_search('status provjere bazne cijene', $header, true);
			if ($verified !== false && !in_array(self::columnName($fields[$verified]), array('confirmed', 'potvrdeno', 'potvrdena'), true)) {
				$row[10] = '';
			}
			$rows[] = $row;
		}
		if (!$rows) { throw new RuntimeException('PJ1: BBM izvoz nema artikala.'); }
		return $rows;
	}
}
