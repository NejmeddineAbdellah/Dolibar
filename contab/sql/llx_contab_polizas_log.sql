CREATE TABLE IF NOT EXISTS `llx_contab_polizas_log` (
  `rowid` int(11) NOT NULL AUTO_INCREMENT,
  `fk_user` int(11) NOT NULL DEFAULT '0',
  `fk_poliza` int(11) NOT NULL DEFAULT '0',
  `cantmodif` int(11) NOT NULL DEFAULT '0',
  `creador` int(11) NOT NULL DEFAULT '0',
  `fechahora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`rowid`)
);
