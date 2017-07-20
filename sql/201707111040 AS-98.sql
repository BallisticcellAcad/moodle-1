--Moodle DB--

CREATE TABLE `Regions` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(45) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
	PRIMARY KEY (`id`),
	UNIQUE INDEX `id_UNIQUE` (`id`)
)
COLLATE='utf8_unicode_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1;

CREATE TABLE `Municipalities` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(45) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
	`regionId` INT(11) NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `region_id_idx` (`regionId`),
	CONSTRAINT `region_id` FOREIGN KEY (`regionId`) REFERENCES `Regions` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION
)
COLLATE='utf8_unicode_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1;

CREATE TABLE `Cities` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(45) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
	`municipalityId` INT(11) NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `municipality_id_idx` (`municipalityId`),
	CONSTRAINT `municipality_id` FOREIGN KEY (`municipalityId`) REFERENCES `Municipalities` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION
)
COLLATE='utf8_unicode_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1;

CREATE TABLE `schools` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
	`cityId` INT(11) NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `city_id_idx` (`cityId`),
	CONSTRAINT `city_id` FOREIGN KEY (`cityId`) REFERENCES `Cities` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION
)
COLLATE='utf8_unicode_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1;

ALTER TABLE `moodle`.`user_last` 
ADD COLUMN `regionId` INT NULL AFTER `updated_at`,
ADD COLUMN `municipalityId` INT NULL AFTER `regionId`,
ADD COLUMN `cityId` INT NULL AFTER `municipalityId`,
ADD COLUMN `schoolId` INT NULL AFTER `cityId`;




// test gidhub jira integretion
