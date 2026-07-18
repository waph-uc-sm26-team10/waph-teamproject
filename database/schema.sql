USE waph;

DROP TABLE IF EXISTS users;

CREATE TABLE users(
	username varchar(50) PRIMARY KEY,
	password varchar(100) NOT NULL
	);
