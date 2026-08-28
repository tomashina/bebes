# Atelier Bebes storefront

Legacy OpenCart 2.3 storefront for `https://atelierbebes.com`.

Production secrets, runtime data, generated OCMOD/VQMod output, logs, sessions,
customer uploads and product media are intentionally excluded from Git. Copy
`config.php` and `admin/config.php` from the protected production secret store;
never commit them.

Checkout is intentionally restricted to exactly these payment codes:

- `wspay`
- `cod`
- `kekspay`

Before any production release, review database-backed OCMOD/VQMod entries,
regenerate their caches from trusted sources, run the security preflight and
test all three payment flows in provider test mode. Do not restore an old
generated cache or the pre-incident `.htaccess` file.

