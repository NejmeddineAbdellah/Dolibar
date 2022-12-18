<?php
/* This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 * or see http://www.gnu.org/
 * 
 * 					JPFarber - jfarber55@hotmail.com
 * 
 */

/**
 *	\file       htdocs/core/lib/product.lib.php
 *	\brief      Ensemble de fonctions de base pour le module produit et service
 * 	\ingroup	product
 */

/*
 * Todas las opciones que se muestran en el módulo de Contab, deben mostrar los tab's que están
 * definidos aqui en esta clase, por lo tanto tengo que poner aqui una rutina que verifique si
 * los registros en la tabla llx_const están generados para las entidades donde entity > 1
 */
function check_tbl_cons_for_new_contab_values() {
	global $db,$conf;
	
	dol_syslog("Estoy verificando que si existan las variables globales en la tabla const");
	$sql = "SELECT * FROM ".MAIN_DB_PREFIX."const WHERE name = 'MAX_DAYS_FOR_DELAY_AFTER_MONTH_ENDS' AND entity = ".$conf->entity;
	dol_syslog("Primer chequeo:: sql=".$sql);
	$res = $db->query($sql);
	if (! $db->num_rows($res) > 0) {
		$sql = "INSERT INTO ".MAIN_DB_PREFIX."const (name,value,type,visible,note,entity) VALUES ('MAX_DAYS_FOR_DELAY_AFTER_MONTH_ENDS', '5','chaine',1,'Total slack days after the end of the month to close the period.',".$conf->entity.")";
		dol_syslog("Creando Registro #1:: sql=".$sql);
		$db->query($sql);
	}
	
	$sql = "SELECT * FROM ".MAIN_DB_PREFIX."const WHERE name = 'CONTAB_MAX_ROWS_PER_PAGE' AND entity = ".$conf->entity;
	dol_syslog("Segundo chequeo:: sql=".$sql);
	$res = $db->query($sql);
	if (! $db->num_rows($res) > 0) {
		$sql = "INSERT INTO ".MAIN_DB_PREFIX."const (name,value,type,visible,note,entity) VALUES ('CONTAB_MAX_ROWS_PER_PAGE', '100','chaine',1,'Count of records that can be showed per page.',".$conf->entity.")";
		dol_syslog("Creando Registro #2:: sql=".$sql);
		$db->query($sql);
	}
}
/*
DELETE FROM llx_const WHERE name = 'MAX_DAYS_FOR_DELAY_AFTER_MONTH_ENDS' AND entity = 1;
INSERT INTO `llx_const` (name,value,type,visible,note,entity) VALUES ('MAX_DAYS_FOR_DELAY_AFTER_MONTH_ENDS', '5','chaine',1,'Total slack days after the end of the month to close the period.',1);

DELETE FROM llx_const WHERE name = 'CONTAB_MAX_ROWS_PER_PAGE' AND entity = 1;
INSERT INTO `llx_const` (name,value,type,visible,note,entity) VALUES ('CONTAB_MAX_ROWS_PER_PAGE', '100','chaine',1,'Count of records that can be showed per page.',1);
*/

/**
 * Prepare array with list of tabs
 *
 * @param   Object	$object		Object related to tabs
 * @param	User	$user		Object user
 * @return  array				Array of tabs to shoc
 */
function contab_admin_prepare_head($object, $user)
{
	global $langs, $conf;
	$langs->load("contab");
	
	check_tbl_cons_for_new_contab_values();
	
	$head=array();        // Tableau des onglets

	$h = 0;
	$head[$h][0]="config_payment_term.php"; // Url de la page affichée quand on clique sur l'onglet
	$head[$h][1]="Condicion de Pago"; // Titre de l'ongLet
	$head[$h][2]="condicion de pago";
	$h++;
	
	/* $head[$h][0]="config_group_bg.php"; // Url de la page affichée quand on clique sur l'onglet
	$head[$h][1]="Agrupacion Bal. Gral."; // Titre de l'ongLet
	$head[$h][2]="Agrupacion Bal. Gral.";
	$h++; */
	/* $head[$h][0]="config_group_er.php"; // Url de la page affichée quand on clique sur l'onglet
	$head[$h][1]="Agrupacion Edo. Res."; // Titre de l'ongLet
	$head[$h][2]="Agrupacion Edo. Res.";
	$h++; */
	$head[$h][0]="reportes.php"; // Url de la page affichée quand on clique sur l'onglet
	$head[$h][1]="Reportes"; // Titre de l'ongLet
	$head[$h][2]="Reportes";
	$h++;
	
	/* $head[$h][0]="config_ctas_aut.php"; // Url de la page affichée quand on clique sur l'onglet
	$head[$h][1]="Conf. Ctas. Automaticas"; // Titre de l'ongLet
	$head[$h][2]="Conf. Ctas. Automaticas";
	$h++; */
	$head[$h][0]="cuentas.php"; // Url de la page affichée quand on clique sur l'onglet
	$head[$h][1]="Cuentas"; // Titre de l'ongLet
	$head[$h][2]="Cuentas";
	$h++;
	/* $head[$h][0]="config_rel_ctas.php"; // Url de la page affichée quand on clique sur l'onglet
	$head[$h][1]="Rel. Cat. con Ctas. Aut."; // Titre de l'ongLet
	$head[$h][2]="Rel. Cat. con Ctas. Aut.";
	$h++; */
	
	$head[$h][0]="terceros.php"; // Url de la page affichée quand on clique sur l'onglet
	$head[$h][1]="Terceros"; // Titre de l'ongLet
	$head[$h][2]="Terceros";
	$h++;
	/* $head[$h][0]="config_tercero.php"; // Url de la page affichée quand on clique sur l'onglet
	$head[$h][1]="Terceros"; // Titre de l'ongLet
	$head[$h][2]="Terceros";
	$h++; */
	/* $head[$h][0]="config_permgenera_poliza.php"; // Url de la page affichée quand on clique sur l'onglet
	$head[$h][1]="Conf. Polizas"; // Titre de l'ongLet
	$head[$h][2]="Conf. Polizas";
	$h++; */
	
	$head[$h][0]="exportar.php"; // Url de la page affichée quand on clique sur l'onglet
	$head[$h][1]="Exportar/Importar"; // Titre de l'ongLet
	$head[$h][2]="Exportar/Importar";
	$h++; 
	
	
/* 	
	$head[$h][0]="config_ter_activos.php"; // Url de la page affichée quand on clique sur l'onglet
	$head[$h][1]="Terceros vs Activos"; // Titre de l'ongLet
	$head[$h][2]="Terceros vs Activos";
	$h++; */
	
    // Show more tabs from modules
    // Entries must be declared in modules descriptor with line
    // $this->tabs = array('entity:+tabname:Title:@mymodule:/mymodule/mypage.php?id=__ID__');   to add new tab
    // $this->tabs = array('entity:-tabname);   												to remove a tab
    complete_head_from_modules($conf,$langs,$object,$head,$h,'contab');

	return $head;
}

/**
*  Return array head with list of tabs to view object informations.
*
*  @param	Object	$object		Product
*  @return	array   	        head array with tabs
*/
function contab_prepare_head($object=null)
{
	global $langs, $conf, $user;
	
	check_tbl_cons_for_new_contab_values();
	
	$head = array();

	$h = 0;
	// Élément décrivant un onglet. Il y aura autant de $h que d'onglets à afficher
	if (file_exists(DOL_DOCUMENT_ROOT . '/contab/periodos/fiche.php')) {
		$path = "";
	} else {
		$path = "/custom/";
	}
	
	$head[$h][0]=DOL_URL_ROOT . $path."/contab/periodos/fiche.php"; // Url de la page affichée quand on clique sur l'onglet
	$head[$h][1]="Periodos"; // Titre de l'ongLet
	$head[$h][2]="Periodos";
	$h++;
	
	$head[$h][0]=DOL_URL_ROOT . $path."/contab/polizas/fiche.php";
	$head[$h][1]="Polizas"; // Titre de l'ongLet
	$head[$h][2]="Polizas"; 
	$h++; 

	/* $head[$h][0]=DOL_URL_ROOT . $path."/contab/cuentas/cat_ppal.php";
	$head[$h][1]="Cat. Principal"; // Titre de l'ongLet
	$head[$h][2]="Cat. Principal";
	$h++; */
	
	/* $head[$h][0]=DOL_URL_ROOT . $path."/contab/cuentas/fiche.php";
	$head[$h][1]="Cat. del Usuario"; // Titre de l'ongLet
	$head[$h][2]="Cat. del Usuario";
	$h++; */
	if($user->rights->contab->pfpenconta){
	$head[$h][0]=DOL_URL_ROOT . $path."/contab/modules/fourn/fiche.php";
	$head[$h][1]="Fact. Prov. sin Contabilizar"; // Titre de l'ongLet
	$head[$h][2]="Fact. Prov. s/Poliza";
	$h++;
	
	$head[$h][0]=DOL_URL_ROOT . $path."/contab/modules/facture/fiche.php";
	$head[$h][1]="Fact. Cte sin Contabilizar"; // Titre de l'ongLet
	$head[$h][2]="Fact. Cte s/Poliza";
	$h++;
	}
	$head[$h][0]=DOL_URL_ROOT . $path."/contab/periodos/documents.php";
	$head[$h][1]="Documentos"; // Titre de l'ongLet
	$head[$h][2]="Documentos";
	$h++;
	
	$head[$h][0] = DOL_URL_ROOT . $path."/contab/polizas/rec.php";
	$head[$h][1]="Polizas Recurrentes"; // Titre de l'ongLet
	$head[$h][2]="Recurrentes";
	$h++;
	
	$head[$h][0] = DOL_URL_ROOT . $path."/contab/lists/auxiliares2.php";
	$head[$h][1]="Auxiliar de cuentas"; // Titre de l'ongLet
	$head[$h][2]="Auxiliar de cuentas";
	$h++;
	
	complete_head_from_modules($conf,$langs,$object,$head,$h,'contab_admin');

	return $head;
}
