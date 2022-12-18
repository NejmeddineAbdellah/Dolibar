CREATE TABLE IF NOT EXISTS `llx_contab_rel_ctas` (
  `rowid` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) DEFAULT NULL,
  `description` varchar(60) DEFAULT NULL,
  `fk_sat_cta` int(11) DEFAULT NULL,
  `fk_cat_cta` int(11) DEFAULT NULL,
  PRIMARY KEY (`rowid`)
) ;