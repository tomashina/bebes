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
		array(str_replace('-pj1-', '-pj3-', $filename), $raw, 'naziv datoteke'),
		array(str_replace('20261119', '20261118', $filename), $raw, '20261118, očekuje se 20261119'),
		array($filename, str_replace(';mjera;', ';sifra;', $raw), 'Nedostaju stupci: mjera'),
		array($filename, str_replace('10.09.2026', '31.09.2026', $raw), 'neispravan datum bazne cijene'),
		array($filename, $raw . substr($raw, strpos($raw, "\n") + 1), 'ponovljena šifra')
	) as $invalid) {
		try { readRetailFixture($temp, $invalid[0], $invalid[1], $today); }
		catch (RuntimeException $expected) {
			if (strpos($expected->getMessage(), $invalid[2]) === false) { throw $expected; }
			continue;
		}
		throw new Exception('Invalid BBM export accepted.');
	}
	$seven = str_replace(array(';mpcijena akcija;', ';32.00;'), ';', $raw);
	$rows = readRetailFixture($temp, $filename, $seven, $today);
	if ($rows !== array(array('Test; "artikl"', '000002', '', '', 'kom', '36,90', 'NE', '', '3056560000025', '3056560000025', '35,50'))) { throw new Exception('Seven-column retail mapping changed.'); }
	$empty_sale = str_replace(';32.00;', ';;', $raw);
	if (readRetailFixture($temp, $filename, $empty_sale, $today) !== $rows) { throw new Exception('Missing and empty sale columns must be equivalent.'); }
	$reordered = "sifra;naziv;mjera;mpcijena;barkod;mpcijena bazna datum;mpcijena bazna\n000002;Artikl;KOM;36.90;3056560000025;10.09.2026;35.50\n";
	$r = readRetailFixture($temp, $filename, $reordered, $today);
	if ($r[0][1] !== '000002' || $r[0][10] !== '35,50' || $r[0][5] !== '36,90' || $r[0][6] !== 'NE') { throw new Exception('Header order affected seven-column import.'); }
} finally { if (is_file($temp)) { unlink($temp); } }
echo "OK: BBM with a sale price, an empty sale column and a missing sale column, identity, date, duplicate and required-field validation.\n";
