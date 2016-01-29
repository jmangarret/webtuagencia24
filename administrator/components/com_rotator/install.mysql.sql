DROP TABLE IF EXISTS #__am_rotadores;

CREATE TABLE #__am_rotadores (
 id INTEGER NOT NULL AUTO_INCREMENT,
 nombre VARCHAR(128),
 width INTEGER,
 height INTEGER,
 class VARCHAR(32),
 thumb SMALLINT,
 twidth INTEGER,
 theight INTEGER,
 published SMALLINT,
 css TEXT,
 PRIMARY KEY (id)
) ENGINE = MyISAM;

DROP TABLE IF EXISTS #__am_banners;

CREATE TABLE #__am_banners (
 id INTEGER NOT NULL AUTO_INCREMENT,
 rotator INTEGER,
 title VARCHAR(128),
 description TEXT,
 button VARCHAR(32),
 link VARCHAR(512),
 image VARCHAR(64),
 thumb VARCHAR(64),
 orden INTEGER,
 published SMALLINT,
 PRIMARY KEY (id)
) ENGINE = MyISAM;
