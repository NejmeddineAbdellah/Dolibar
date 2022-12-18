CREATE TABLE IF NOT EXISTS `llx_contab_fourn_product_line` (
  `rowid` int(11) NOT NULL AUTO_INCREMENT,
  `fk_facture` int(11),
  `rowid_line` int(11),
  `fk_cat_cta` int(11),
  `fourn_type` int(11),
  PRIMARY KEY (`rowid`)
);
