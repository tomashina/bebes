-- Disable stale Authorize.Net AIM payment configuration.
-- This payment method is not used on Atelier Bebes, and if it is enabled
-- while its production files are missing it breaks checkout payment loading.

UPDATE `oc_setting`
SET `value` = '0'
WHERE `key` = 'authorizenet_aim_status';

DELETE FROM `oc_extension`
WHERE `type` = 'payment'
  AND `code` = 'authorizenet_aim';

SELECT `extension_id`, `type`, `code`
FROM `oc_extension`
WHERE `type` = 'payment'
ORDER BY `code`;

SELECT `setting_id`, `store_id`, `code`, `key`, `value`
FROM `oc_setting`
WHERE `key` = 'authorizenet_aim_status'
   OR `key` IN ('cod_status', 'free_checkout_status', 'kekspay_status', 'wspay_status', 'quickcheckout_payment_default')
ORDER BY `code`, `key`, `store_id`;
