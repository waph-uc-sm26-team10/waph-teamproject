USE waph_team;

-- DROP TABLE IF EXISTS users;

-- CREATE TABLE users(
-- 	username varchar(50) PRIMARY KEY,
-- 	password varchar(100) NOT NULL
-- 	);

-- sprint 1 DB implementation 

-- DROP TABLE IF EXISTS `posts`;

-- CREATE TABLE `posts`(
-- 	`owner` varchar(50),
-- 	FOREIGN KEY (`owner`) REFERENCES `users` (`username`) ON DELETE CASCADE
-- );

ALTER TABLE users 
ADD email varchar(255) NOT NULL FIRST,
ADD name varchar(50) NOT NULL AFTER username;