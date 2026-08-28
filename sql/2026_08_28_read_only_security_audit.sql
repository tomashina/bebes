-- Read-only production audit. This file does not change database state.
-- The project currently uses the oc_ prefix. Adjust it first if needed.

SELECT user_id, username, user_group_id, email, status, date_added
FROM oc_user
ORDER BY user_id;

SELECT type, code
FROM oc_extension
WHERE type IN ('payment', 'shipping')
ORDER BY type, code;

SELECT code, `key`, value
FROM oc_setting
WHERE `key` IN ('gls_status', 'gls_geo_zone_id', 'gls_filter_type')
ORDER BY code, `key`;

SELECT modification_id, name, code, version, status, date_added
FROM oc_modification
ORDER BY name;

SELECT modification_id, name, code, version, status, date_added
FROM oc_modification
WHERE LOWER(CONCAT_WS(' ', name, code, xml)) REGEXP
      'pp_pro|paypal|anythingpro\\.net|yopmail\\.com|superadmin|account-edit\\.suspect|admin-reset\\.suspect|register\\.suspect';

SELECT COUNT(*) AS ukupno,
       SUM(TRIM(upc) = CHAR(49)) AS gls_oznaceni,
       SUM(TRIM(upc) = CHAR(49) AND status = 1 AND quantity > 0) AS gls_aktivni
FROM oc_product;

