SET FOREIGN_KEY_CHECKS=0;

--
-- Update from 1.06 to 1.07
--

-- 36 states
ALTER TABLE `states` CHANGE `country_id` `country_id` INT(11) NULL DEFAULT '1';

-- 37 states
ALTER TABLE `states` ADD INDEX(`country_id`);

-- 38 states
ALTER TABLE `states` ADD FOREIGN KEY (`country_id`) REFERENCES `countries`(`country_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- 39 cities
ALTER TABLE `cities` ADD INDEX(`state_id`);

-- 40 places
ALTER TABLE `places` CHANGE `phone` `phone` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';

-- 41 places
ALTER TABLE `places` CHANGE `website` `website` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';

-- 42 cities
ALTER TABLE `cities` CHANGE `state` `state` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';

-- 43 places
ALTER TABLE `places` CHANGE `area_code` `area_code` VARCHAR(25) NULL DEFAULT NULL;

-- 44 places
ALTER TABLE `places` DROP `last_bump`;


SET FOREIGN_KEY_CHECKS=1;