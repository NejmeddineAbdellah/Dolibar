
-- llx_quick_notes Table

CREATE TABLE llx_quick_notes(
	rowid INTEGER AUTO_INCREMENT PRIMARY KEY,
	notes TEXT NULL,
	fk_user INTEGER NOT NULL
);
