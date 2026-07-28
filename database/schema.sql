DROP TABLE IF EXISTS `posts`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
    `userid`   int UNSIGNED NOT NULL AUTO_INCREMENT,
    `email`    varchar(255) NOT NULL,
    `username` varchar(50)  NOT NULL,
    `name`     varchar(50)  NOT NULL,
    `phone`    varchar(20)  DEFAULT NULL,
    `password` varchar(100) NOT NULL,
    PRIMARY KEY (`userid`),
    UNIQUE KEY `uq_users_username` (`username`),
    UNIQUE KEY `uq_users_email` (`email`)
);

CREATE TABLE `posts` (
    `postid`  int UNSIGNED NOT NULL AUTO_INCREMENT,
    `title`   varchar(150) NOT NULL,
    `content` text         NOT NULL,
    `date`    datetime     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `owner`   int UNSIGNED NOT NULL,
    PRIMARY KEY (`postid`),
    FOREIGN KEY (`owner`) REFERENCES `users` (`userid`) ON DELETE CASCADE
);
