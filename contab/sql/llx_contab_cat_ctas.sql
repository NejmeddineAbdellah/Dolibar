CREATE TABLE IF NOT EXISTS `llx_contab_cat_ctas` (
  `rowid` int(11) NOT NULL AUTO_INCREMENT,
  `cta` varchar(24) NOT NULL DEFAULT '',
  `descta` varchar(200) NOT NULL DEFAULT '',
  `fk_sat_cta` int(11) NOT NULL DEFAULT '0',
  `subctade` int(11) NOT NULL DEFAULT '0',
  `import_key` varchar(14) NULL DEFAULT '',
  PRIMARY KEY (`rowid`)
);
