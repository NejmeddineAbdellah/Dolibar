ALTER TABLE `llx_contab_cat_ctas` ADD `entity` INT NOT NULL DEFAULT '1' AFTER `rowid`;

ALTER TABLE `llx_contab_ctas_supplier` ADD `entity` INT NOT NULL DEFAULT '1' AFTER `rowid`;

ALTER TABLE `llx_contab_ctrl_almacen` ADD `entity` INT NOT NULL DEFAULT '1' AFTER `rowid`;

ALTER TABLE `llx_contab_grupos` ADD `entity` INT NOT NULL DEFAULT '1' AFTER `rowid`;

ALTER TABLE `llx_contab_payment_term` ADD `entity` INT NOT NULL DEFAULT '1' AFTER `rowid`;

ALTER TABLE `llx_contab_periodos` ADD `entity` INT NOT NULL DEFAULT '1' AFTER `rowid`;

ALTER TABLE `llx_contab_pol_rec` ADD `entity` INT NOT NULL DEFAULT '1' AFTER `rowid`;

ALTER TABLE `llx_contab_polizas` ADD `entity` INT NOT NULL DEFAULT '1' AFTER `rowid`;

ALTER TABLE `llx_contab_rel_ctas` ADD `entity` INT NOT NULL DEFAULT '1' AFTER `rowid`;

ALTER TABLE `llx_contab_sat_metodo_pago` ADD `entity` INT NOT NULL DEFAULT '1' AFTER `rowid`;
