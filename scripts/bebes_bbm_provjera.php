<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require_once dirname(__DIR__) . '/config.php';
require_once DIR_SYSTEM . 'library/bebes_bbm_price_list.php';

try {
	$now = new DateTime('now', new DateTimeZone('Europe/Zagreb'));
	$rows = isset($argv[1]) ? BebesBbmPriceList::readZip($argv[1], $now) : BebesBbmPriceList::download($now);
	$file = BebesPriceCsv::prepare($rows);
	printf("PJ1 / 00001: %d artikala, CSV SHA-256 %s\n", count($rows), $file['checksum']);
	foreach ($rows as $index => $row) {
		if ($row[10] === '') {
			printf("Redak %d, šifra %s (%s): potvrditi skupinu, datum prve ponude, cijenu i akciju na referentni dan.\n", $index + 2, $row[1], $row[0]);
		}
	}
} catch (Throwable $error) {
	fwrite(STDERR, $error->getMessage() . "\n");
	exit(1);
}
