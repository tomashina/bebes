-- Run only after a verified database backup.
-- This project currently uses the oc_ prefix. Adjust every prefix first if needed.

START TRANSACTION;

-- Lock every administrator first. Only individually reviewed accounts should
-- later be re-enabled with tools/reset_admin_password.php and a new password.
UPDATE oc_user
SET status = 0,
    salt = SUBSTRING(SHA2(CONCAT(UUID(), RAND(), user_id), 256), 1, 9),
    password = SHA1(CONCAT(UUID(), RAND(), user_id)),
    code = '';

SET @administrators_disabled = ROW_COUNT();

-- The confirmed backdoor attempted to create this administrator.
SELECT user_id, username, user_group_id, status, date_added
FROM oc_user
WHERE username = 'superadmin';

DELETE FROM oc_user
WHERE username = 'superadmin';

-- Revoke active API sessions after credential rotation.
DELETE FROM oc_api_session;

-- Prevent known incident- or PayPal-related OCMOD records from being rebuilt.
-- The old generated modification directory is quarantined separately.
UPDATE oc_modification
SET status = 0
WHERE LOWER(CONCAT_WS(' ', name, code, xml)) REGEXP
      'pp_pro|paypal|anythingpro\\.net|yopmail\\.com|superadmin|account-edit\\.suspect|admin-reset\\.suspect|register\\.suspect';

SET @suspicious_modifications_disabled = ROW_COUNT();

-- Capture the currently installed non-approved payment codes for cleanup.
DROP TEMPORARY TABLE IF EXISTS tmp_disabled_payment_code;

CREATE TEMPORARY TABLE tmp_disabled_payment_code (
  code varchar(128) NOT NULL PRIMARY KEY
) ENGINE=MEMORY;

INSERT IGNORE INTO tmp_disabled_payment_code (code)
SELECT DISTINCT code
FROM oc_extension
WHERE type = 'payment'
  AND code NOT IN ('wspay', 'cod', 'kekspay');

UPDATE oc_setting AS setting_row
INNER JOIN tmp_disabled_payment_code AS disabled
  ON disabled.code = setting_row.code
SET setting_row.value = '0'
WHERE setting_row.key = CONCAT(disabled.code, '_status');

DELETE setting_row
FROM oc_setting AS setting_row
INNER JOIN tmp_disabled_payment_code AS disabled
  ON disabled.code = setting_row.code;

DELETE extension_row
FROM oc_extension AS extension_row
INNER JOIN tmp_disabled_payment_code AS disabled
  ON disabled.code = extension_row.code
WHERE extension_row.type = 'payment';

-- Remove the rogue PayPal Pro residue even if its extension row was already deleted.
DELETE FROM oc_setting
WHERE code = 'pp_pro'
   OR `key` LIKE 'pp\_pro\_%';

DROP TEMPORARY TABLE tmp_disabled_payment_code;

COMMIT;

SELECT @administrators_disabled AS administrators_disabled,
       @suspicious_modifications_disabled AS suspicious_modifications_disabled;

-- Expected result: exactly wspay, cod and kekspay.
SELECT type, code
FROM oc_extension
WHERE type = 'payment'
ORDER BY code;
