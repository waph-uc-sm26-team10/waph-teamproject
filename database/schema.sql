DROP TABLE IF EXISTS `comments`;
DROP TABLE IF EXISTS `posts`;
DROP TABLE IF EXISTS `superusers`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
    `userid`   int UNSIGNED NOT NULL AUTO_INCREMENT,
    `email`    varchar(255) NOT NULL,
    `username` varchar(50)  NOT NULL,
    `name`     varchar(50)  NOT NULL,
    `phone`    varchar(20)  DEFAULT NULL,
    `password` varchar(100) NOT NULL,
    `disabled` tinyint(1)   NOT NULL DEFAULT 0,
    PRIMARY KEY (`userid`),
    UNIQUE KEY `uq_users_username` (`username`),
    UNIQUE KEY `uq_users_email` (`email`)
);

CREATE TABLE `superusers` (
    `superuserid` int UNSIGNED NOT NULL AUTO_INCREMENT,
    `userid`      int UNSIGNED NOT NULL,
    PRIMARY KEY (`superuserid`),
    UNIQUE KEY `uq_superusers_userid` (`userid`),
    FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE
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

CREATE TABLE `comments` (
    `commentid` int UNSIGNED NOT NULL AUTO_INCREMENT,
    `postid`    int UNSIGNED NOT NULL,
    `owner`     int UNSIGNED NOT NULL,
    `content`   text         NOT NULL,
    `date`      datetime     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`commentid`),
    FOREIGN KEY (`postid`) REFERENCES `posts` (`postid`) ON DELETE CASCADE,
    FOREIGN KEY (`owner`)  REFERENCES `users` (`userid`) ON DELETE CASCADE
);
