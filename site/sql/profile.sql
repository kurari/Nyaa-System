 DROP TABLE profile;
CREATE TABLE profile (
	userid integer,
	name varchar(128),
	value varchar(128),
	pub varchar(128)
);
CREATE INDEX profile_index ON profile(userid,name);
