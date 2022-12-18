CREATE TABLE IF NOT EXISTS `llx_contab_payment_term` (
  `rowid` int(11) NOT NULL AUTO_INCREMENT,
  `fk_payment_term` int(11) NOT NULL,
  `cond_pago` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`rowid`)
) ;