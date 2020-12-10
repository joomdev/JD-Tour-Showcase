CREATE TABLE IF NOT EXISTS `#__jdtoursshowcase_tours` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`title` VARCHAR(255)  NOT NULL ,
`tour_type` VARCHAR(255)  NOT NULL ,
`alias` VARCHAR(255)  NOT NULL ,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL ,
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`tour_image` VARCHAR(255)  NOT NULL ,
`price` VARCHAR(255)  NOT NULL ,
`price_currency` VARCHAR(255)  NOT NULL ,
`price_postfix` TEXT NOT NULL ,
`feature` TEXT NOT NULL ,
`created_on` TEXT NOT NULL ,
`percentage` DOUBLE,
`fixed_amount` DOUBLE,
`discount_type` VARCHAR(255)  NOT NULL ,
`show_discount` VARCHAR(255)  NOT NULL ,
`duration` VARCHAR(255)  NOT NULL ,
`destination` VARCHAR(255)  NOT NULL ,
`gallery` VARCHAR(255)  NOT NULL ,
`tour_description` VARCHAR(25500)  NOT NULL,
`facilities_description` TEXT NOT NULL ,
`facilities_features` VARCHAR(25550)  NOT NULL ,
`tour_schedule` VARCHAR(25550)  NOT NULL ,
`schedule_description` TEXT NOT NULL ,
`module_position` VARCHAR(255)  NOT NULL ,
`enable_sidebar` VARCHAR(255)  NOT NULL ,
`hits` DOUBLE,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__jdtoursshowcase_tour_type` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL ,
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`title` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;
