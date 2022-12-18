CREATE TABLE IF NOT EXISTS `llx_contab_sat_ctas` (
  `rowid` int(11) NOT NULL AUTO_INCREMENT,
  `nivel` tinyint(4) NOT NULL DEFAULT '0',
  `codagr` varchar(24) NOT NULL DEFAULT '',
  `descripcion` varchar(200) NOT NULL,
  `natur` char(1) NOT NULL DEFAULT '',
  `import_key` varchar(14) NULL DEFAULT '',
  PRIMARY KEY (`rowid`)
) ;