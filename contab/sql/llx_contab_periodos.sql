CREATE TABLE IF NOT EXISTS `llx_contab_periodos` (
  `rowid` int(11) NOT NULL AUTO_INCREMENT,
  `anio` int(11) NOT NULL,
  `mes` int(11) NOT NULL,
  `estado` int(11) NOT NULL,
  `validado_bg` smallint(6) unsigned NOT NULL DEFAULT '0',
  `validado_bc` smallint(6) unsigned NOT NULL DEFAULT '0',
  `validado_er` smallint(6) unsigned NOT NULL DEFAULT '0',
  `validado_ld` smallint(6) unsigned NOT NULL DEFAULT '0',
  `validado_lm` smallint(6) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`rowid`)
) ;