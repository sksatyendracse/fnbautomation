SET FOREIGN_KEY_CHECKS=0;
--
-- Update from 1.03 to 1.04
--

-- 1 cats
ALTER TABLE `cats` ADD `cat_status` TINYINT(1) NOT NULL DEFAULT '1' AFTER `cat_order`, ADD INDEX (`cat_status`);

-- 2 places
ALTER TABLE `places` ADD FULLTEXT(`description`);

-- 3 custom_fields
CREATE TABLE IF NOT EXISTS `custom_fields` (
  `field_id` int(11) NOT NULL,
  `field_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `values_list` text COLLATE utf8mb4_unicode_ci,
  `tooltip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `searchable` tinyint(1) NOT NULL DEFAULT '1',
  `field_order` int(11) NOT NULL DEFAULT '0',
  `field_status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4 custom_fields
ALTER TABLE `custom_fields` ADD PRIMARY KEY (`field_id`);

-- 5 custom_fields
ALTER TABLE `custom_fields` MODIFY `field_id` int(11) NOT NULL AUTO_INCREMENT;

-- 6 rel_cat_custom_fields
CREATE TABLE IF NOT EXISTS `rel_cat_custom_fields` (
  `rel_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL DEFAULT '0',
  `field_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7 rel_cat_custom_fields
ALTER TABLE `rel_cat_custom_fields`
  ADD PRIMARY KEY (`rel_id`),
  ADD KEY `cat_id` (`cat_id`),
  ADD KEY `field_id` (`field_id`);

-- 8 rel_cat_custom_fields
ALTER TABLE `rel_cat_custom_fields`
  MODIFY `rel_id` int(11) NOT NULL AUTO_INCREMENT;

-- 9 rel_cat_custom_fields
ALTER TABLE `rel_cat_custom_fields`
  ADD CONSTRAINT `rel_cat_custom_fields_ibfk_1` FOREIGN KEY (`cat_id`) REFERENCES `cats` (`id`)
  ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rel_cat_custom_fields_ibfk_2` FOREIGN KEY (`field_id`) REFERENCES `custom_fields` (`field_id`)
  ON DELETE CASCADE ON UPDATE CASCADE;

-- 10 rel_place_custom_fields
CREATE TABLE IF NOT EXISTS `rel_place_custom_fields` (
  `rel_id` int(11) NOT NULL,
  `place_id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `field_value` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11 rel_place_custom_fields
ALTER TABLE `rel_place_custom_fields`
  ADD PRIMARY KEY (`rel_id`),
  ADD KEY `option_id` (`field_id`),
  ADD KEY `place_id` (`place_id`),
  ADD FULLTEXT KEY `field_value` (`field_value`);

-- 12 rel_place_custom_fields
ALTER TABLE `rel_place_custom_fields`
  MODIFY `rel_id` int(11) NOT NULL AUTO_INCREMENT;

-- 13 rel_place_custom_fields
ALTER TABLE `rel_place_custom_fields`
  ADD CONSTRAINT `rel_place_custom_fields_ibfk_1` FOREIGN KEY (`field_id`) REFERENCES `custom_fields` (`field_id`)
  ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rel_place_custom_fields_ibfk_3` FOREIGN KEY (`place_id`) REFERENCES `places` (`place_id`)
  ON DELETE CASCADE ON UPDATE CASCADE;

-- 14 photos
ALTER TABLE `photos`
  ADD CONSTRAINT `photos_ibfk_1` FOREIGN KEY (`place_id`) REFERENCES `places` (`place_id`)
  ON DELETE CASCADE ON UPDATE CASCADE;

-- 15 places
ALTER TABLE `places` DROP INDEX foursq_id;

-- 16 cities
ALTER TABLE `cities` CHANGE `state` `state` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';

-- 17 states
ALTER TABLE `states` CHANGE `state_abbr` `state_abbr` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';

-- 18 reviews
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`place_id`) REFERENCES `places` (`place_id`)
  ON DELETE CASCADE ON UPDATE CASCADE;

-- 19 rel_place_cat
ALTER TABLE `rel_place_cat`
  ADD CONSTRAINT `rel_place_cat_ibfk_1` FOREIGN KEY (`place_id`) REFERENCES `places` (`place_id`)
  ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rel_place_cat_ibfk_2` FOREIGN KEY (`cat_id`) REFERENCES `cats` (`id`)
  ON DELETE CASCADE ON UPDATE CASCADE;

-- 20 business_hours
ALTER TABLE `business_hours`
  ADD CONSTRAINT `business_hours_ibfk_1` FOREIGN KEY (`place_id`) REFERENCES `places` (`place_id`)
  ON DELETE CASCADE ON UPDATE CASCADE;

-- 21 cities_feat
ALTER TABLE `cities_feat`
  ADD CONSTRAINT `cities_feat_ibfk_1` FOREIGN KEY (`city_id`) REFERENCES `cities` (`city_id`)
  ON DELETE CASCADE ON UPDATE CASCADE;

-- 22 loggedin
ALTER TABLE `loggedin`
  ADD CONSTRAINT `loggedin_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`id`)
  ON DELETE CASCADE ON UPDATE CASCADE;

-- 23 pass_request
ALTER TABLE `pass_request`
  ADD CONSTRAINT `pass_request_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
  ON DELETE CASCADE ON UPDATE CASCADE;

-- 24 signup_confirm
ALTER TABLE `signup_confirm`
  ADD CONSTRAINT `signup_confirm_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
  ON DELETE CASCADE ON UPDATE CASCADE;

SET FOREIGN_KEY_CHECKS=1;