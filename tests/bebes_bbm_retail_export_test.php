<?php
require_once dirname(__DIR__) . '/system/library/bebes_bbm_price_list.php';

$today = new DateTime('2026-11-19 00:30', new DateTimeZone('Europe/Zagreb'));
$filename = '59774997564-prodavaona-nikole_jurisica_8-zagreb-pj1-roba-323_20261119_002000.csv';
$raw = "naziv;sifra;mjera;mpcijena;mpcijena akcija;barkod;mpcijena bazna;mpcijena bazna datum\r\n"
	. "\"Test; \"\"artikl\"\"\";000002;KOM;36.90;32.00;3056560000025;35.50;10.09.2026\r\n";
$temp = tempnam(sys_get_temp_dir(), 'bebes-bbm-test-');
function readRetailFixture($path, $member, $content, $when) {
	$zip = new ZipArchive();
	if ($zip->open($path, ZipArchive::OVERWRITE | ZipArchive::CREATE) !== true ||
		!$zip->addFromString($member, $content) || !$zip->close()) { throw new Exception('Fixture zip'); }
	return BebesBbmPriceList::readZip($path, $when, $when->getTimestamp());
}
try {
	$rows = readRetailFixture($temp, $filename, $raw, $today);
	if ($rows !== array(array('Test; "artikl"', '000002', '', '', 'kom', '32,00', 'DA', '', '3056560000025', '3056560000025', '35,50'))) {
		throw new Exception('Retail mapping changed.');
	}
	foreach (array(
		array(str_replace('-pj1-', '-pj3-', $filename), $raw),
		array(str_replace('20261119', '20261118', $filename), $raw),
		array($filename, str_replace('10.09.2026', '31.09.2026', $raw)),
		array($filename, $raw . substr($raw, strpos($raw, "\n") + 1))
	) as $invalid) {
		try { readRetailFixture($temp, $invalid[0], $invalid[1], $today); }
		catch (RuntimeException $expected) { continue; }
		throw new Exception('Invalid BBM export accepted.');
	}
} finally { unlink($temp); }
echo "OK: merchant PJ1 file identity, date, SKU, current sale, barcode, historic base and duplicate protection.\n";
