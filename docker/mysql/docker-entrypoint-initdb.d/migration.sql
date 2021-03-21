CREATE TABLE IF NOT EXISTS `articles` (
    `id` INT UNSIGNED UNIQUE NOT NULL AUTO_INCREMENT,
    `url` varchar(255) NOT NULL UNIQUE,
    `name` varchar(255) NULL,
    `description` text NULL,
    `content` text NULL,
    `links` text NULL,
    `keywords` text NULL,
    `author` varchar(255) NULL,
    PRIMARY KEY(`id`)
);