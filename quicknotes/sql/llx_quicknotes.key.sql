
-- Constraints

ALTER TABLE llx_quick_notes ADD CONSTRAINT fk_quick_notes_fk_user FOREIGN KEY (fk_user) REFERENCES llx_user (rowid);
