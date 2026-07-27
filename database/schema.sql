DROP TABLE IF EXISTS `posts`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
    `email`    varchar(255) NOT NULL,
    `username` varchar(50)  NOT NULL,
    `name`     varchar(50)  NOT NULL,
    `phone`    varchar(20)  DEFAULT NULL,
    `password` varchar(100) NOT NULL,
    PRIMARY KEY (`username`)
);

CREATE TABLE `posts` (
    `postid`  int          NOT NULL AUTO_INCREMENT,
    `title`   varchar(150) NOT NULL,
    `content` text         NOT NULL,
    `date`    datetime     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `owner`   varchar(50)  NOT NULL,
    PRIMARY KEY (`postid`),
    FOREIGN KEY (`owner`) REFERENCES `users` (`username`)
        ON DELETE CASCADE ON UPDATE CASCADE
);
