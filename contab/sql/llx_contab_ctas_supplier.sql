CREATE TABLE IF NOT EXISTS `llx_contab_ctas_supplier` (
  `rowid` int(11) NOT NULL AUTO_INCREMENT,
  `fk_cta` int(11) NOT NULL,
  `fk_socid` int(11) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '0',
  `fourn_type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`rowid`)
) ;