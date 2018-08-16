INSERT INTO `#__content_types` 
(`type_title`, `type_alias`, `table`, `rules`, `field_mappings`, `router`, `content_history_options`) 
VALUES ('Quix Page', 'com_quix.page', '{"special":{"dbtable":"#__quix","key":"id","type":"Page","prefix":"QuixTable","config":"array()"},"common":{"dbtable":"#__ucm_content","key":"ucm_id","type":"Corecontent","prefix":"JTable","config":"array()"}}', '', '{"common":{"core_content_item_id":"id","core_title":"title","core_state":"state","core_body":"description", "core_hits":"hits","core_access":"access", "core_params":"params", "core_metadata":"metadata", "core_language":"language", "core_ordering":"ordering", "core_metakey":"metakey", "core_metadesc":"metadesc", "core_xreference":"xreference", "asset_id":"null"}, "special":{}}' , 'QuixFrontendHelperRoute::getPageRoute', '{"formFile":"administrator\\/components\\/com_quix\\/models\\/forms\\/page.xml", "hideFields":["asset_id","checked_out","checked_out_time"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time", "version", "hits"], "convertToInt":["publish_up", "publish_down", "featured", "ordering"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"} ]}'); 


CREATE TABLE IF NOT EXISTS `#__quix` (
	`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

	`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

	`title` VARCHAR(255)  NOT NULL ,
	`catid` int(11) NOT NULL,
	`data` LONGTEXT NOT NULL ,
	`metadata` LONGTEXT NOT NULL,
	`language` VARCHAR(5)  NOT NULL ,
	`ordering` INT(11)  NOT NULL ,
	`state` TINYINT(1)  NOT NULL ,
	`access` INT(11)  NOT NULL ,
	`created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`created_by` int(10) unsigned NOT NULL DEFAULT '0',
	`modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified_by` int(10) unsigned NOT NULL DEFAULT '0',
	`checked_out` INT(11)  NOT NULL ,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`params` LONGTEXT NOT NULL ,
	`version` int(10) unsigned NOT NULL DEFAULT '1',
	`hits` int(11) NOT NULL,
	`xreference` varchar(50) NOT NULL COMMENT 'A reference to enable linkages to external data sets.',
	PRIMARY KEY (`id`),
	KEY `idx_access` (`access`),
	KEY `idx_catid` (`catid`),
	KEY `idx_state` (`state`),
	KEY `idx_createdby` (`created_by`),
	KEY `idx_xreference` (`xreference`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__quix_collections` (
	`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`uid` VARCHAR(255)  NOT NULL ,
	`title` VARCHAR(255)  NOT NULL ,
	`type` ENUM('layout','section') NOT NULL DEFAULT 'section',
	`catid` int(11) NOT NULL,
	`data` LONGTEXT NOT NULL ,
	`metadata` LONGTEXT NOT NULL,
	`language` VARCHAR(5)  NOT NULL ,
	`ordering` INT(11)  NOT NULL ,
	`state` TINYINT(1)  NOT NULL ,
	`access` INT(11)  NOT NULL ,
	`created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`created_by` int(10) unsigned NOT NULL DEFAULT '0',
	`modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified_by` int(10) unsigned NOT NULL DEFAULT '0',
	`checked_out` INT(11)  NOT NULL ,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`params` LONGTEXT NOT NULL ,
	`version` int(10) unsigned NOT NULL DEFAULT '1',
	`hits` int(11) NOT NULL,
	`xreference` varchar(50) NOT NULL COMMENT 'A reference to enable linkages to external data sets.',
	PRIMARY KEY (`id`),
	KEY `idx_access` (`access`),
	KEY `idx_catid` (`catid`),
	KEY `idx_state` (`state`),
	KEY `idx_createdby` (`created_by`),
	KEY `idx_xreference` (`xreference`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__quix_collection_map` (
	`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`cid` int(11) UNSIGNED NOT NULL,
	`pid` int(11) UNSIGNED NOT NULL,
	PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__quix_elements` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `alias` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `params` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;