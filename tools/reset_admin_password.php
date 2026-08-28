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

if (preg_match('//u', $password) !== 1) {
    fwrite(STDERR, "ERROR: password input must be valid UTF-8.\n");
    exit(65);
}

// The legacy OpenCart database connection uses MySQL's three-byte `utf8`
// character set. A four-byte character (for example an emoji) can be hashed by
// this CLI process but cannot traverse the web login query unchanged.
if (preg_match('/[\x{10000}-\x{10FFFF}]/u', $password) === 1) {
    fwrite(STDERR, "ERROR: this legacy admin login cannot safely use four-byte Unicode characters; use a long ASCII password.\n");
    exit(65);
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$database = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, defined('DB_PORT') ? (int) DB_PORT : 3306);
$database->set_charset('utf8');
$table = DB_PREFIX . 'user';

$select = $database->prepare("SELECT user_id, salt, password, code, status FROM `{$table}` WHERE username = ? LIMIT 2");
$select->bind_param('s', $username);
$select->execute();
$result = $select->get_result();

if ($result->num_rows !== 1) {
    fwrite(STDERR, "ERROR: administrator account was not found uniquely; no change made.\n");
    exit(67);
}

$user = $result->fetch_assoc();
$salt = substr(bin2hex(random_bytes(16)), 0, 9);

// The request cleaner and admin login controller recover the original UTF-8
// password before Cart\User applies this transformation. Keep it explicit here
// because Cart\User relies on PHP's default_charset being UTF-8.
$loginPassword = htmlspecialchars($password, ENT_QUOTES, 'UTF-8');

$userId = (int) $user['user_id'];
$previousSalt = (string) $user['salt'];
$previousPassword = (string) $user['password'];
$previousCode = (string) $user['code'];
$previousStatus = (int) $user['status'];

// Match system/library/cart/user.php exactly: MySQL performs the nested SHA1
// operations using the same connection charset as the web application.
$update = $database->prepare(
    "UPDATE `{$table}` " .
    "SET salt = ?, password = SHA1(CONCAT(?, SHA1(CONCAT(?, SHA1(?))))), code = '', status = 1 " .
    "WHERE user_id = ?"
);
$update->bind_param('ssssi', $salt, $salt, $salt, $loginPassword, $userId);
$update->execute();

if ($update->affected_rows !== 1) {
    fwrite(STDERR, "ERROR: administrator password was not updated.\n");
    exit(1);
}

$verification = null;

try {
    $verify = $database->prepare(
        "SELECT status, " .
        "password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1(?))))) AS password_matches " .
        "FROM `{$table}` WHERE user_id = ? LIMIT 1"
    );
    $verify->bind_param('si', $loginPassword, $userId);
    $verify->execute();
    $verification = $verify->get_result()->fetch_assoc();
} catch (Throwable $exception) {
    // The original row is restored below. Do not print database exception data.
}

if (!$verification || (int) $verification['status'] !== 1 || (int) $verification['password_matches'] !== 1) {
    $restoreVerified = false;

    try {
        // OpenCart installations commonly use MyISAM, so rollback() cannot be
        // trusted. Restore and verify every previous account value explicitly.
        $restore = $database->prepare(
            "UPDATE `{$table}` SET salt = ?, password = ?, code = ?, status = ? WHERE user_id = ?"
        );
        $restore->bind_param('sssii', $previousSalt, $previousPassword, $previousCode, $previousStatus, $userId);
        $restore->execute();

        $restoreCheck = $database->prepare(
            "SELECT salt, password, code, status FROM `{$table}` WHERE user_id = ? LIMIT 1"
        );
        $restoreCheck->bind_param('i', $userId);
        $restoreCheck->execute();
        $restoredUser = $restoreCheck->get_result()->fetch_assoc();

        $restoreVerified = $restoredUser
            && (string) $restoredUser['salt'] === $previousSalt
            && (string) $restoredUser['password'] === $previousPassword
            && (string) $restoredUser['code'] === $previousCode
            && (int) $restoredUser['status'] === $previousStatus;
    } catch (Throwable $exception) {
        // Report only the recovery outcome, never credentials or SQL details.
    }

    if ($restoreVerified) {
        fwrite(STDERR, "ERROR: verification of the new administrator password failed; previous account values were restored and verified.\n");
    } else {
        fwrite(STDERR, "CRITICAL: verification of the new administrator password failed and the previous account values could not be restored and verified. Keep the site offline and restore this administrator row from the database backup.\n");
    }

    exit(1);
}

printf("Administrator password updated, verified and account enabled: user_id=%d username=%s previous_status=%d\n", $userId, $username, $previousStatus);
