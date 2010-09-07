CREATE TABLE user (
	id integer primary key,
	familyName varchar(128),
	firstName varchar(128),
	email varchar(512),
	password varchar(512),
	gender varchar(64),
	birthday date
);
