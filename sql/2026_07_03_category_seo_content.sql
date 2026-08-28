CREATE TABLE IF NOT EXISTS `oc_category_seo_content` (
  `category_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `description` mediumtext NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`category_id`, `language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `oc_category_seo_faq` (
  `category_seo_faq_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `question` varchar(500) NOT NULL,
  `answer` mediumtext NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`category_seo_faq_id`),
  KEY `category_language` (`category_id`, `language_id`),
  KEY `sort_order` (`sort_order`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
