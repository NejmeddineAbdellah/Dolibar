CREATE TABLE IF NOT EXISTS `llx_contab_doc` (
  `rowid` int(11) NOT NULL AUTO_INCREMENT,
  `entity` int(11) NOT NULL,
  `folio` varchar(15) DEFAULT NULL,
  `url` longtext,
  PRIMARY KEY (`rowid`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
