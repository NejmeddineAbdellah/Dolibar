CREATE TABLE IF NOT EXISTS `llx_contab_pol_rec` (
  `rowid` int(11) NOT NULL AUTO_INCREMENT,
  `entity` int(11),
  `tipo_pol` varchar(1) NOT NULL DEFAULT 'D',
  `cons` int(11) NOT NULL,
  `anio` smallint(6) NOT NULL,
  `mes` smallint(6) NOT NULL,
  `fecha` date NOT NULL,
  `concepto` varchar(256) NOT NULL,
  `comentario` varchar(150) NOT NULL,
  `fk_facture` int(11) NOT NULL DEFAULT '0',
  `anombrede` varchar(100) DEFAULT NULL,
  `numcheque` varchar(50) DEFAULT NULL,
  `ant_ctes` bit(1) NOT NULL DEFAULT b'0',
  `fechahora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `societe_type` smallint(6) DEFAULT '0',
  PRIMARY KEY (`rowid`)
);