CREATE TABLE IF NOT EXISTS `llx_contab_sat_bancos` (
  `rowid` int(11) NOT NULL AUTO_INCREMENT,
  `clave` char(3) NOT NULL DEFAULT '',
  `nomcorto` varchar(6) NOT NULL DEFAULT '',
  `nombre` varchar(200) NOT NULL,
  PRIMARY KEY (`rowid`)
) ;
