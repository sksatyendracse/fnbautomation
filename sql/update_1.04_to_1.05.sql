SET FOREIGN_KEY_CHECKS=0;

--
-- Update from 1.04 to 1.05
--

-- 25 config
ALTER TABLE `config`
  CHANGE `value` `value` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;

-- 26 contact_msgs
CREATE TABLE IF NOT EXISTS `contact_msgs` (
  `id` int(11) NOT NULL,
  `sender_email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sender_ip` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `place_id` int(11) NOT NULL,
  `msg` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 27 contact_msgs
ALTER TABLE `contact_msgs` ADD PRIMARY KEY (`id`);

-- 28 contact_msgs
ALTER TABLE `contact_msgs` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- 29 config
INSERT INTO `config` (`id`, `type`, `property`, `value`) VALUES
(28, 'api', 'facebook_key', ''),
(29, 'api', 'facebook_secret', ''),
(30, 'api', 'twitter_key', ''),
(31, 'api', 'twitter_secret', '');

-- 30 config
INSERT INTO `config` (`id`, `type`, `property`, `value`) VALUES
(101, 'plugin', 'plugin_contact_owner', 'a:3:{s:8:"question";s:6:"2 + 3?";s:6:"answer";s:1:"5";s:13:"email_subject";s:32:"Message from a DirectoryApp user";}');

-- 31 states
ALTER TABLE `states` CHANGE `country_abbr` `country_abbr` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'country';

-- 32 countries
ALTER TABLE `countries` CHANGE `country_abbr` `country_abbr` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'country';

-- 33 rel_place_custom_fields
ALTER TABLE `rel_place_custom_fields` CHANGE `field_value` `field_value` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;

SET FOREIGN_KEY_CHECKS=1;