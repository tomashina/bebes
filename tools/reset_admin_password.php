<?php

// CLI-only recovery helper. The password is read from STDIN so it does not
// appear in shell history or the process argument list.

if (PHP_SAPI !== 'cli') {
    fwrite(STDERR, "ERROR: CLI use only.\n");
    exit(77);
}

if ($argc !== 2 || trim($argv[1]) === '') {
    fwrite(STDERR, "Usage: php reset_admin_password.php USERNAME < password-on-stdin\n");
    exit(64);
}

$username = trim($argv[1]);

if (strlen($username) > 64) {
    fwrite(STDERR, "ERROR: invalid administrator username.\n");
    exit(64);
}

$siteRoot = getenv('BEBES_SITE_ROOT');

if ($siteRoot === false || $siteRoot === '') {
    $siteRoot = '/home/amds/bebes.agmedia.rocks';
}

$configPath = rtrim($siteRoot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'config.php';

if (!is_file($configPath)) {
    fwrite(STDERR, "ERROR: production config.php was not found.\n");
    exit(66);
}

require $configPath;

$password = rtrim(stream_get_contents(STDIN), "\r\n");

if (strlen($password) < 20) {
    fwrite(STDERR, "ERROR: use a unique administrator password of at least 20 characters.\n");
    exit(65);
}

if (strlen($password) > 4096) {
    fwrite(STDERR, "ERROR: password input is too long.\n");
    exit(65);
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$database = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, defined('DB_PORT') ? (int) DB_PORT : 3306);
$database->set_charset('utf8mb4');
$table = DB_PREFIX . 'user';

$select = $database->prepare("SELECT user_id, status FROM `{$table}` WHERE username = ? LIMIT 2");
$select->bind_param('s', $username);
$select->execute();
$result = $select->get_result();

if ($result->num_rows !== 1) {
    fwrite(STDERR, "ERROR: administrator account was not found uniquely; no change made.\n");
    exit(67);
}

$user = $result->fetch_assoc();
$salt = substr(bin2hex(random_bytes(16)), 0, 9);

// Match the legacy OpenCart 2.3 login comparison exactly. Avoid entering the
// password through a web form while the incident recovery is still in progress.
$loginPassword = htmlspecialchars($password, ENT_QUOTES, 'UTF-8');
$passwordHash = sha1($salt . sha1($salt . sha1($loginPassword)));

$update = $database->prepare("UPDATE `{$table}` SET salt = ?, password = ?, code = '', status = 1 WHERE user_id = ?");
$userId = (int) $user['user_id'];
$update->bind_param('ssi', $salt, $passwordHash, $userId);
$update->execute();

if ($update->affected_rows !== 1) {
    fwrite(STDERR, "ERROR: administrator password was not updated.\n");
    exit(1);
}

printf("Administrator password updated and account enabled: user_id=%d username=%s previous_status=%d\n", $userId, $username, (int) $user['status']);
