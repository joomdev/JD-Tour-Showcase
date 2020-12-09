/* Only premium users are allowed to update a component */
CREATE TABLE IF NOT EXISTS `#__jdtoursshowcase_reviews` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
`ordering` INT(11)  NOT NULL ,
`name` VARCHAR(255)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`reivew` TEXT NOT NULL ,
`email` VARCHAR(255)  NOT NULL ,
`stars` VARCHAR(255)  NOT NULL ,
`packagename` VARCHAR(255)  NOT NULL ,
`tour_id`TINYINT(2)  NOT NULL ,
`tour_date` VARCHAR(255)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL ,
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;
