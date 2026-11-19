<?php
$root = dirname(__DIR__);
$failed = 0;

function assertScheduleCondition($condition, $message) {
	global $failed;
	if (!$condition) {
		fwrite(STDERR, $message . "\n");
		$failed++;
	}
}

function readScheduleFile($path) {
	$content = is_file($path) ? file_get_contents($path) : false;
	assertScheduleCondition($content !== false, 'Missing schedule file: ' . $path);
	return $content === false ? '' : $content;
}

$wrapper = readScheduleFile($root . '/scripts/bebes_anchor_cron.sh');
assertScheduleCondition(strpos($wrapper, "LOCAL_TIME=$(date '+%H:%M')") !== false, 'Cron wrapper must evaluate Europe/Zagreb local time.');
assertScheduleCondition(strpos($wrapper, "if [ \"\$LOCAL_TIME\" != '00:30' ]; then") !== false, 'Cron wrapper must run only at 00:30 Europe/Zagreb.');
assertScheduleCondition(strpos($wrapper, 'LOCAL_WEEKDAY') === false && strpos($wrapper, "date '+%u'") === false, 'Cron wrapper must not skip Saturdays or Sundays.');

$documentation = readScheduleFile($root . '/docs/sidrene_cijene_pustanje_na_produkciju.md');
assertScheduleCondition(strpos($documentation, '30 22,23 * * * /home/amds/.local/bin/bebes-anchor-cron.sh') !== false, 'Documented UTC cron schedule must cover both CET and CEST every day.');
assertScheduleCondition(strpos($documentation, 'svaki dan, uključujući subotu i nedjelju') !== false, 'Documentation must state that weekend publication is enabled.');

$mirror_pairs = array(
	'admin model' => array(
		$root . '/admin/model/extension/module/anchor_price.php',
		$root . '/_ocmod/bebes_anchor_prices_v1.0.0/upload/admin/model/extension/module/anchor_price.php'
	),
	'Croatian admin language' => array(
		$root . '/admin/language/hr-hr/extension/module/anchor_price.php',
		$root . '/_ocmod/bebes_anchor_prices_v1.0.0/upload/admin/language/hr-hr/extension/module/anchor_price.php'
	),
	'English admin language' => array(
		$root . '/admin/language/en-gb/extension/module/anchor_price.php',
		$root . '/_ocmod/bebes_anchor_prices_v1.0.0/upload/admin/language/en-gb/extension/module/anchor_price.php'
	)
);

foreach ($mirror_pairs as $name => $paths) {
	assertScheduleCondition(is_file($paths[0]) && is_file($paths[1]) && hash_file('sha256', $paths[0]) === hash_file('sha256', $paths[1]), 'Packaged ' . $name . ' must match the source copy.');
}

$admin_model = readScheduleFile($mirror_pairs['admin model'][0]);
assertScheduleCondition(strpos($admin_model, "'due' => (int)\$now->format('Hi') >= 1100") !== false, 'Admin overdue warning must apply every day after 11:00.');
assertScheduleCondition(strpos($admin_model, 'is_working_day') === false, 'Admin overdue warning must not suppress weekends.');

$croatian_language = readScheduleFile($mirror_pairs['Croatian admin language'][0]);
$english_language = readScheduleFile($mirror_pairs['English admin language'][0]);
assertScheduleCondition(strpos($croatian_language, 'svaki dan u 00:30') !== false, 'Croatian admin help must describe daily publication.');
assertScheduleCondition(strpos($english_language, 'run daily at 00:30') !== false, 'English admin help must describe daily publication.');

if ($failed) {
	exit(1);
}

echo "OK: daily 00:30 publication is enabled for all seven days.\n";
