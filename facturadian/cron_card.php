<?php
// Load Dolibarr environment
$res = 0;
// Try main.inc.php into web root known defined into CONTEXT_DOCUMENT_ROOT (not always defined)
if (!$res && !empty($_SERVER["CONTEXT_DOCUMENT_ROOT"])) $res = @include $_SERVER["CONTEXT_DOCUMENT_ROOT"]."/main.inc.php";
// Try main.inc.php into web root detected using web root calculated from SCRIPT_FILENAME
$tmp = empty($_SERVER['SCRIPT_FILENAME']) ? '' : $_SERVER['SCRIPT_FILENAME']; $tmp2 = realpath(__FILE__); $i = strlen($tmp) - 1; $j = strlen($tmp2) - 1;
while ($i > 0 && $j > 0 && isset($tmp[$i]) && isset($tmp2[$j]) && $tmp[$i] == $tmp2[$j]) { $i--; $j--; }
if (!$res && $i > 0 && file_exists(substr($tmp, 0, ($i + 1))."/main.inc.php")) $res = @include substr($tmp, 0, ($i + 1))."/main.inc.php";
if (!$res && $i > 0 && file_exists(dirname(substr($tmp, 0, ($i + 1)))."/main.inc.php")) $res = @include dirname(substr($tmp, 0, ($i + 1)))."/main.inc.php";
// Try main.inc.php using relative path
if (!$res && file_exists("../main.inc.php")) $res = @include "../main.inc.php";
if (!$res && file_exists("../../main.inc.php")) $res = @include "../../main.inc.php";
if (!$res && file_exists("../../../main.inc.php")) $res = @include "../../../main.inc.php";
if (!$res) die("Include of main fails");

require_once DOL_DOCUMENT_ROOT.'/core/class/html.formcompany.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formfile.class.php';
dol_include_once('/facturadian/class/cron.class.php');
dol_include_once('/facturadian/lib/facturadian_cron.lib.php');

// Load translation files required by the page
$langs->loadLangs(array("facturadian@facturadian", "other"));

// Get parameters desde cron_list.php
$documento	= GETPOST('documento', 'documento');
$etapa		= GETPOST('etapa', 'etapa');
$invoice	= GETPOST('invoice', 'invoice');
$ambiente	= GETPOST('ambiente', 'ambiente');
$cantidad	= GETPOST('cantidad', 'cantidad');

$id 		= GETPOST('id', 'int');
$ref        = GETPOST('ref', 'alpha');
$action 	= GETPOST('action', 'aZ09');
$confirm    = GETPOST('confirm', 'alpha');
$cancel     = GETPOST('cancel', 'aZ09');
$contextpage = GETPOST('contextpage', 'aZ') ?GETPOST('contextpage', 'aZ') : 'croncard'; // To manage different context of search
$backtopage = GETPOST('backtopage', 'alpha');
$backtopageforcancel = GETPOST('backtopageforcancel', 'alpha');
//$lineid   = GETPOST('lineid', 'int');

// Initialize technical objects
$object = new cron($db);
$extrafields = new ExtraFields($db);
$diroutputmassaction = $conf->facturadian->dir_output.'/temp/massgeneration/'.$user->id;
$hookmanager->initHooks(array('croncard', 'globalcard')); // Note that conf->hooks_modules contains array

// Fetch optionals attributes and labels
$extrafields->fetch_name_optionals_label($object->table_element);

$search_array_options = $extrafields->getOptionalsFromPost($object->table_element, '', 'search_');

// Initialize array of search criterias
$search_all = trim(GETPOST("search_all", 'alpha'));
$search = array();
foreach ($object->fields as $key => $val)
{
	if (GETPOST('search_'.$key, 'alpha')) $search[$key] = GETPOST('search_'.$key, 'alpha');
}

if (empty($action) && empty($id) && empty($ref)) $action = 'view';

// Load object
include DOL_DOCUMENT_ROOT.'/core/actions_fetchobject.inc.php'; // Must be include, not include_once.

// Security check - Protection if external user
//if ($user->socid > 0) accessforbidden();
//if ($user->socid > 0) $socid = $user->socid;
//$isdraft = (($object->statut == $object::STATUS_DRAFT) ? 1 : 0);
//$result = restrictedArea($user, 'facturadian', $object->id, '', '', 'fk_soc', 'rowid', $isdraft);

$permissiontoread = $user->rights->facturadian->cron->read;
$permissiontoadd = $user->rights->facturadian->cron->write; // Used by the include of actions_addupdatedelete.inc.php and actions_lineupdown.inc.php
$permissiontodelete = $user->rights->facturadian->cron->delete || ($permissiontoadd && isset($object->status) && $object->status == $object::STATUS_DRAFT);
$permissionnote = $user->rights->facturadian->cron->write; // Used by the include of actions_setnotes.inc.php
$permissiondellink = $user->rights->facturadian->cron->write; // Used by the include of actions_dellink.inc.php
$upload_dir = $conf->facturadian->multidir_output[isset($object->entity) ? $object->entity : 1];


/*
 * Actions
 */

$parameters = array();
$reshook = $hookmanager->executeHooks('doActions', $parameters, $object, $action); // Note that $action and $object may have been modified by some hooks
if ($reshook < 0) setEventMessages($hookmanager->error, $hookmanager->errors, 'errors');

if (empty($reshook))
{
    $error = 0;

    $backurlforlist = dol_buildpath('/facturadian/cron_list.php', 1);

    if (empty($backtopage) || ($cancel && empty($id))) {
    	if (empty($backtopage) || ($cancel && strpos($backtopage, '__ID__'))) {
    		if (empty($id) && (($action != 'add' && $action != 'create') || $cancel)) $backtopage = $backurlforlist;
    		else $backtopage = dol_buildpath('/facturadian/cron_card.php', 1).'?id='.($id > 0 ? $id : '__ID__');
    	}
    }
    $triggermodname = 'FACTURADIAN_CRON_MODIFY'; // Name of trigger action code to execute when we modify record

    // Actions cancel, add, update, update_extras, confirm_validate, confirm_delete, confirm_deleteline, confirm_clone, confirm_close, confirm_setdraft, confirm_reopen
    include DOL_DOCUMENT_ROOT.'/core/actions_addupdatedelete.inc.php';

    // Actions when linking object each other
    include DOL_DOCUMENT_ROOT.'/core/actions_dellink.inc.php';

    // Actions when printing a doc from card
    include DOL_DOCUMENT_ROOT.'/core/actions_printing.inc.php';

    // Action to move up and down lines of object
    //include DOL_DOCUMENT_ROOT.'/core/actions_lineupdown.inc.php';

    // Action to build doc
    include DOL_DOCUMENT_ROOT.'/core/actions_builddoc.inc.php';

    if ($action == 'set_thirdparty' && $permissiontoadd)
    {
    	$object->setValueFrom('fk_soc', GETPOST('fk_soc', 'int'), '', '', 'date', '', $user, 'CRON_MODIFY');
    }
    if ($action == 'classin' && $permissiontoadd)
    {
    	$object->setProject(GETPOST('projectid', 'int'));
    }

    // Actions to send emails
    $triggersendname = 'CRON_SENTBYMAIL';
    $autocopy = 'MAIN_MAIL_AUTOCOPY_CRON_TO';
    $trackid = 'cron'.$object->id;
    include DOL_DOCUMENT_ROOT.'/core/actions_sendmails.inc.php';
}




/*
 * View
 *
 * Put here all code to build page
 */

$form = new Form($db);
$formfile = new FormFile($db);

llxHeader('', $langs->trans('cron'), '');

// Example : Adding jquery code
print '<script type="text/javascript" language="javascript">
jQuery(document).ready(function() {
	function init_myfunc()
	{
		jQuery("#myid").removeAttr(\'disabled\');
		jQuery("#myid").attr(\'disabled\',\'disabled\');
	}
	init_myfunc();
	jQuery("#mybutton").click(function() {
		init_myfunc();
	});
});
</script>';


// La accion se cambio de create a enviar como esta en cron_list.php
if ($action == 'enviar')
{
	print load_fiche_titre($langs->trans("Enviando Documentos a la DIAN", $langs->transnoentitiesnoconv("cron")));

	print '<form method="POST" action="'.$_SERVER["PHP_SELF"].'">';
	if ($backtopage) print '<input type="hidden" name="backtopage" value="'.$backtopage.'">';
	if ($backtopageforcancel) print '<input type="hidden" name="backtopageforcancel" value="'.$backtopageforcancel.'">';

	dol_fiche_head(array(), '');

	print '<table class="border centpercent tableforfieldcreate">'."\n";

	print "Documento: ".$documento.",";
	print "etapa: ".$etapa.",";
	print "invoice: ".$invoice.",";
	print "ambiente: ".$ambiente.",";
	print "cantidad: ".$cantidad."<br />";

		//********************************************************************************
		$sql = "SELECT * FROM ".MAIN_DB_PREFIX."facturadian_credenciales WHERE 1"; 	
		$resql = $db->query($sql);
		if ($resql)
		{
			if($db->num_rows($resql) > 0) {
				$objp = $db->fetch_object($resql);

				if($_REQUEST['etapa'] == 'todas') {
					$ejecutar = "php scripts/enviar_".$_REQUEST['documento'].".php ".$objp->username." ".$objp->password." ".$_REQUEST['ambiente']." ".$_REQUEST['cantidad']." ".$_REQUEST['invoice'];
					$respuesta = shell_exec($ejecutar);
					print "<br /><br />".$respuesta;

					print "<br /><br />";
					$tiempo=1;
					//Actualiza
					$ejecutar2= "echo 'php scripts/update.php ".$objp->username." ".$objp->password."' | at now + ".++$tiempo." minutes";
					$respuesta2 = shell_exec($ejecutar2);

					//Subes3
					$ejecutar3= "echo 'php scripts/pdf.php ".$objp->username." ".$objp->password."' | at now + ".++$tiempo." minutes";
					$respuesta3 = shell_exec($ejecutar3);

					//Actualiza
					$ejecutar4= "echo 'php scripts/update.php ".$objp->username." ".$objp->password."' | at now + ".++$tiempo." minutes";
					$respuesta4 = shell_exec($ejecutar4);

					//Cliente
					$ejecutar5= "echo 'php scripts/cliente.php ".$objp->username." ".$objp->password." ".$_REQUEST['ambiente']."' | at now + ".++$tiempo." minutes";
					$respuesta5 = shell_exec($ejecutar5);

					//Actualiza
					$ejecutar6= "echo 'php scripts/update.php ".$objp->username." ".$objp->password."' | at now + ".++$tiempo." minutes";
					$respuesta6 = shell_exec($ejecutar6);

					//Eventos
					$ejecutar7= "echo 'php scripts/eventos.php ".$objp->username." ".$objp->password."' | at now + ".++$tiempo." minutes";
					$respuesta7 = shell_exec($ejecutar7);
					
					print "<font color='blue'><h3>Los resultados se reflejaran en 7 minutos...</h3></font>";
				}
				if($_REQUEST['etapa'] == 'email') {
					
					//Cliente
					$ejecutar5= "php scripts/cliente.php ".$objp->username." ".$objp->password." ".$_REQUEST['ambiente'];
					$respuesta5 = shell_exec($ejecutar5);
					print $respuesta5;

					print "<br /><br />";
					$tiempo=1;
					//Actualiza
					$ejecutar6= "echo 'php scripts/update.php ".$objp->username." ".$objp->password."' | at now + ".++$tiempo." minutes";
					$respuesta6 = shell_exec($ejecutar6);

					//Eventos
					$ejecutar7= "echo 'php scripts/eventos.php ".$objp->username." ".$objp->password."' | at now + ".++$tiempo." minutes";
					$respuesta7 = shell_exec($ejecutar7);
					
					print "<font color='blue'><h3>Los resultados se reflejaran en 3 minutos...</h3></font>";
				}

			}

		}
		$db->free($resql);
		print "<br /><br />";
		//********************************************************************************
		

	print '</table>'."\n";

	dol_fiche_end();

	print '<div class="center">';
	print '<input type="'.($backtopage ? "submit" : "button").'" class="button" name="cancel" value="'.dol_escape_htmltag($langs->trans("Regresar")).'"'.($backtopage ? '' : ' onclick="javascript:history.go(-1)"').'>'; // Cancel for create does not post form if we don't know the backtopage
	print '</div>';

	print '</form>';

	//dol_set_focus('input[name="ref"]');
}


// End of page
llxFooter();
$db->close();
