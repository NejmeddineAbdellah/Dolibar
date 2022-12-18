<?php
/* Copyright (C) 2004-2017 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2020 Rabib Ahmad <rabib@japantravel.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

/**
 * \file    useractivitydashboard/admin/setup.php
 * \ingroup useractivitydashboard
 * \brief   UserActivityDashboard setup page.
 */

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
if (!$res && file_exists("../../main.inc.php")) $res = @include "../../main.inc.php";
if (!$res && file_exists("../../../main.inc.php")) $res = @include "../../../main.inc.php";
if (!$res) die("Include of main fails");

global $langs, $user, $db, $conf;

// Libraries
require_once DOL_DOCUMENT_ROOT . "/core/lib/admin.lib.php";
require_once '../lib/useractivitydashboard.lib.php';
require_once DOL_DOCUMENT_ROOT.'/categories/class/categorie.class.php';

// Translations
$langs->loadLangs(array("admin", "useractivitydashboard@useractivitydashboard"));

// Access control
if (!$user->admin) accessforbidden();

// Parameters
$action = GETPOST('action', 'alpha');
$backtopage = GETPOST('backtopage', 'alpha');

$value = GETPOST('value', 'alpha');
$category = GETPOST('label', 'alpha');
$type = 'USERACTIVITYDASHBOARD_TYPE_GENERAL';
if ($value == 'USERACTIVITYDASHBOARD_SHOW_DATE_FROM' || $value == 'USERACTIVITYDASHBOARD_SHOW_DATE_TO') {
	$type = 'USERACTIVITYDASHBOARD_TYPE_DATE';
}

if ($value == 'USERACTIVITYDASHBOARD_SHOW_USERNAME_GRAPH' || $value == 'USERACTIVITYDASHBOARD_SHOW_SUB_CATE_GRAPH') {
	$type = 'USERACTIVITYDASHBOARD_TYPE_GRAPH';
}

$categstatic = new Categorie($db);
$tabcategories = $categstatic->get_all_categories('user', true);

$arrayofparameters = [
	'USERACTIVITYDASHBOARD_SHOW_WEEKLY'			=> ['css' => 'minwidth200', 'enabled' => 1],
	'USERACTIVITYDASHBOARD_SHOW_MONTHLY'		=> ['css' => 'minwidth200', 'enabled' => 1],
	'USERACTIVITYDASHBOARD_SHOW_QUARTERLY'		=> ['css' => 'minwidth200', 'enabled' => 1],
	'USERACTIVITYDASHBOARD_SHOW_YEARLY'			=> ['css' => 'minwidth200', 'enabled' => 1],
	'USERACTIVITYDASHBOARD_SHOW_DATE_FROM'		=> ['css' => 'minwidth200', 'enabled' => 1],
	'USERACTIVITYDASHBOARD_SHOW_DATE_TO'		=> ['css' => 'minwidth200', 'enabled' => 1],
	'USERACTIVITYDASHBOARD_SHOW_USERNAME_GRAPH' => ['css' => 'minwidth200', 'enabled' => 1],
	'USERACTIVITYDASHBOARD_SHOW_SUB_CATE_GRAPH' => ['css' => 'minwidth200', 'enabled' => 1],
];

$error = 0;
$setupnotempty = 0;


/*
 * Actions
 */

include DOL_DOCUMENT_ROOT . '/core/actions_setmoduleoptions.inc.php';
if ($action === 'set') {
	addUserActivitySetup($value, $category, $type);
} elseif ($action === 'del') {
	deleteUserActivitySetup($value, $category);
}


/*
 * View
 */

$form = new Form($db);

$dirmodels = array_merge(array('/'), (array) $conf->modules_parts['models']);

$page_name = "UserActivityDashboardSetup";
llxHeader('', $langs->trans($page_name));

// Subheader
$linkback = '<a href="'.($backtopage ? $backtopage : DOL_URL_ROOT.'/admin/modules.php?restore_lastsearch_values=1').'">'.$langs->trans("BackToModuleList").'</a>';

print load_fiche_titre($langs->trans($page_name), $linkback, 'object_useractivitydashboard@useractivitydashboard');

// Configuration header
$head = useractivitydashboardAdminPrepareHead();
dol_fiche_head($head, 'settings', '', -1, "useractivitydashboard@useractivitydashboard");

// Setup page goes here
echo '<span class="opacitymedium">'.$langs->trans("UserActivityDashboardSetupPage").'</span><br><br>';


foreach ($tabcategories as $cat) {
	print load_fiche_titre($langs->trans($cat->label), '', '');
	if (!empty($arrayofparameters)) {
		print '<table class="noborder centpercent">';
		print '<tr class="liste_titre"><td class="titlefield">'.$langs->trans("Name").'</td><td></td><td align="center">'.$langs->trans("Status").'</td></tr>';

		foreach ($arrayofparameters as $key => $val) {
			$setupnotempty++;

			print '<tr class="oddeven"><td>';
			$tooltiphelp = (($langs->trans($key.'Tooltip') != $key.'Tooltip') ? $langs->trans($key.'Tooltip') : '');
			print $form->textwithpicto($langs->trans($key), $tooltiphelp);
			print '</td><td>'.$conf->global->$key.'</td>';
			// Active
			if (!empty(getActiveCategory($key, $cat->label))) {
				print '<td class="center">'."\n";
				print '<a href="'.$_SERVER["PHP_SELF"].'?action=del&amp;value='.$key.'&amp;label='.$cat->label.'">';
				print img_picto($langs->trans("Enabled"), 'switch_on');
				print '</a>';
				print '</td>';
			} else {
				print "<td align=\"center\">\n";
				print '<a href="'.$_SERVER["PHP_SELF"].'?action=set&amp;value='.$key.'&amp;label='.$cat->label.'">'.img_picto($langs->trans("Disabled"), 'switch_off').'</a>';
				print "</td>";
				print "</tr>";
			}
		}

		print '</table>';
	} else {
		print '<br>'.$langs->trans("NothingToSetup");
	}
}

if (empty($setupnotempty)) {
	print '<br>'.$langs->trans("NothingToSetup");
}

// Page end
dol_fiche_end();

llxFooter();
$db->close();
