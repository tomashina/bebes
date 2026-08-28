-- Run only after a verified database backup and after preparing customer notice.
-- Every customer will need the normal "Forgotten password" flow to sign in again.
-- This project currently uses the oc_ prefix. Adjust it first if needed.

START TRANSACTION;

UPDATE oc_customer
SET salt = SUBSTRING(SHA2(CONCAT(UUID(), RAND(), customer_id), 256), 1, 9),
    password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1(CONCAT(UUID(), RAND(), customer_id)))))),
    code = '';

SET @customers_forced_to_reset = ROW_COUNT();

COMMIT;

SELECT @customers_forced_to_reset AS customers_forced_to_reset;
