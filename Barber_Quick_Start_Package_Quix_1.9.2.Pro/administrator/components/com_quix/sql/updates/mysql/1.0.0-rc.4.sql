-- update text commented
ALTER TABLE `#__quix_collections` CHANGE `type` `type` ENUM('layout','section') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'section';