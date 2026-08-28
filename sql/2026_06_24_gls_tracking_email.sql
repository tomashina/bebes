-- GLS tracking/mail support for Atelier Bebes OpenCart.
-- Database prefix used by this project: oc_

CREATE TABLE IF NOT EXISTS `oc_gls_shipment` (
  `gls_shipment_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `order_number` varchar(64) NOT NULL,
  `parcel_id` varchar(64) NOT NULL DEFAULT '',
  `parcel_number` varchar(64) NOT NULL DEFAULT '',
  `parcel_number_with_checkdigit` varchar(64) NOT NULL DEFAULT '',
  `point_id` varchar(128) NOT NULL DEFAULT '',
  `status` varchar(64) NOT NULL DEFAULT '',
  `payload` mediumtext,
  `response` mediumtext,
  `label` mediumblob,
  `date_added` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`gls_shipment_id`),
  KEY `order_id` (`order_id`),
  KEY `parcel_id` (`parcel_id`),
  KEY `parcel_number` (`parcel_number`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

SET @ddl := (
  SELECT IF(
    COUNT(*) = 0,
    'ALTER TABLE `oc_order` ADD COLUMN `gls` TEXT NULL AFTER `shipping_code`',
    'SELECT ''oc_order.gls already exists'' AS message'
  )
  FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'oc_order'
    AND COLUMN_NAME = 'gls'
);
PREPARE stmt FROM @ddl;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @ddl := (
  SELECT IF(
    COUNT(*) = 0,
    'ALTER TABLE `oc_order` ADD COLUMN `gls_tracking_code` VARCHAR(64) NOT NULL DEFAULT '''' AFTER `printed`',
    'SELECT ''oc_order.gls_tracking_code already exists'' AS message'
  )
  FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'oc_order'
    AND COLUMN_NAME = 'gls_tracking_code'
);
PREPARE stmt FROM @ddl;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @ddl := (
  SELECT IF(
    COUNT(*) = 0,
    'ALTER TABLE `oc_order` ADD COLUMN `gls_tracking_url` VARCHAR(255) NOT NULL DEFAULT '''' AFTER `gls_tracking_code`',
    'SELECT ''oc_order.gls_tracking_url already exists'' AS message'
  )
  FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'oc_order'
    AND COLUMN_NAME = 'gls_tracking_url'
);
PREPARE stmt FROM @ddl;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @ddl := (
  SELECT IF(
    COUNT(*) = 0,
    'ALTER TABLE `oc_order` ADD COLUMN `gls_tracking_email_sent_at` DATETIME NULL AFTER `gls_tracking_url`',
    'SELECT ''oc_order.gls_tracking_email_sent_at already exists'' AS message'
  )
  FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'oc_order'
    AND COLUMN_NAME = 'gls_tracking_email_sent_at'
);
PREPARE stmt FROM @ddl;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @ddl := (
  SELECT IF(
    COUNT(*) = 0,
    'ALTER TABLE `oc_order` ADD INDEX `oc_order_gls_tracking_code_idx` (`gls_tracking_code`)',
    'SELECT ''oc_order_gls_tracking_code_idx already exists'' AS message'
  )
  FROM INFORMATION_SCHEMA.STATISTICS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'oc_order'
    AND INDEX_NAME = 'oc_order_gls_tracking_code_idx'
);
PREPARE stmt FROM @ddl;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

UPDATE `oc_order` o
JOIN (
  SELECT gs1.*
  FROM `oc_gls_shipment` gs1
  JOIN (
    SELECT order_id, MAX(gls_shipment_id) AS gls_shipment_id
    FROM `oc_gls_shipment`
    GROUP BY order_id
  ) latest ON latest.gls_shipment_id = gs1.gls_shipment_id
) gs ON gs.order_id = o.order_id
SET
  o.gls_tracking_code = CASE
    WHEN COALESCE(o.gls_tracking_code, '') = ''
    THEN COALESCE(NULLIF(gs.parcel_number, ''), NULLIF(gs.parcel_number_with_checkdigit, ''), NULLIF(gs.parcel_id, ''))
    ELSE o.gls_tracking_code
  END,
  o.gls_tracking_url = CASE
    WHEN COALESCE(o.gls_tracking_url, '') = ''
      AND COALESCE(NULLIF(gs.parcel_number, ''), NULLIF(gs.parcel_number_with_checkdigit, ''), NULLIF(gs.parcel_id, '')) IS NOT NULL
    THEN CONCAT('https://gls-group.com/HR/hr/pracenje-posiljke/?match=', COALESCE(NULLIF(gs.parcel_number, ''), NULLIF(gs.parcel_number_with_checkdigit, ''), NULLIF(gs.parcel_id, '')))
    ELSE o.gls_tracking_url
  END
WHERE COALESCE(o.gls_tracking_code, '') = ''
   OR COALESCE(o.gls_tracking_url, '') = '';

