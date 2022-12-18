<?php
/* Copyright (C) 2001-2005 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2015 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012 Regis Houssin        <regis.houssin@inodbox.com>
 * Copyright (C) 2015      Jean-François Ferry	<jfefe@aternatik.fr>
 * Copyright (C) 2020      Ahmad Jamaly rabib	<rabib@metroworks.co.jp>
 *
 * This program is free software; you can redistribute it and/or modify
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
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

/**
 *	\file       useractivitydashboard/useractivitydashboardindex.php
 *	\ingroup    useractivitydashboard
 *	\brief      Home page of useractivitydashboard top menu
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
if (!$res && file_exists("../main.inc.php")) $res = @include "../main.inc.php";
if (!$res && file_exists("../../main.inc.php")) $res = @include "../../main.inc.php";
if (!$res && file_exists("../../../main.inc.php")) $res = @include "../../../main.inc.php";
if (!$res) die("Include of main fails");

require_once DOL_DOCUMENT_ROOT . '/core/class/html.formfile.class.php';
require_once 'lib/useractivitydashboard.lib.php';

global $langs, $db, $user;

// Load translation files required by the page
$langs->loadLangs(array("useractivitydashboard@useractivitydashboard"));

$action = GETPOST('action', 'alpha');
$displaytype = GETPOST('display_types', 'aZ');
$category = GETPOST('cat', 'alpha');
$categoryId = GETPOST('cat_id', 'alpha');
$startDate = GETPOST('date_start', 'alpha');
$endDate = GETPOST('date_end', 'alpha');
$endMonth = GETPOST('date_endmonth', 'alpha');
$endYear = GETPOST('date_endyear', 'alpha');

if (empty($category)) {
	try {
		$categoryobj = getUserGroup($user->id);
		$category = $categoryobj->label;
		$categoryId = $categoryobj->rowid;
	} catch (Exception $e) {
		return $e;
	}
}


// Security check
//if (! $user->rights->useractivitydashboard->myobject->read) accessforbidden();
$socid = GETPOST('socid', 'int');
if (isset($user->socid) && $user->socid > 0)
{
	$action = '';
	$socid = $user->socid;
}

$max = 5;
$now = dol_now();
$form = new Form($db);
$formfile = new FormFile($db);
$categoryarr = getUserCategory($user->id, 'USERACTIVITYDASHBOARD_TYPE_GENERAL', $categoryId);
$datecategoryarr = getUserCategory($user->id, 'USERACTIVITYDASHBOARD_TYPE_DATE', $categoryId);
$graphcategory = getUserCategory($user->id, 'USERACTIVITYDASHBOARD_TYPE_GRAPH', $categoryId);

/*
 * Actions
 */
if (array_key_exists('USERACTIVITYDASHBOARD_SHOW_USERNAME_GRAPH', $graphcategory)) {
	$canvasType = 'user';
} else {
	$canvasType = 'category';
}

if ($action == 'exportpdf') {
	include dol_include_once('/useractivitydashboard/class/useractivityexport.class.php');
	$error = 0;
	if(empty($displaytype) && empty($startDate)) {
		setEventMessages($langs->trans('ErrorFieldRequired', $langs->transnoentities('Display Option')), null, 'errors');
		$error++;
	}
	if (!$error) {
		if (empty($startDate)) {
			try {
				$dateRange = getDateRange($displaytype, $endDate, $endMonth, $endYear);
			} catch (Exception $e) {
				return $e;
			}
		} else {
			$dateRange = [
				'start_date' => date('Y-m-d H:i:s', strtotime($startDate)),
				'end_date' => date('Y-m-d 23:59:59', strtotime($endDate))
			];
			$displaytype = 'Date Range';
		}
		$temp = '/useractivitydashboard/temp/';
		$tempPath = DOL_DATA_ROOT . $temp;
		if (!is_dir($tempPath)) {
			mkdir($tempPath);
		}

		$prospect = GETPOST('prospect', 'alpha');
		$quotation = GETPOST('quotation', 'alpha');
		$conversionSale = GETPOST('conversion_sale', 'alpha');
		$conversionSalePercent = GETPOST('conversion_sale_percent', 'alpha');
		$conversionSaleTotal = GETPOST('conversion_sale_total', 'alpha');
		$profitTotal = GETPOST('profit_total', 'alpha');
		$costSalePercent = GETPOST('cost_sale_percent_total', 'alpha');

		/**
		 * Save base64 image as png to disk
		 */
		file_put_contents($tempPath . $canvasType . '-prospect-graph.png', file_get_contents($prospect));
		file_put_contents($tempPath . $canvasType . '-quotation-graph.png', file_get_contents($quotation));
		file_put_contents($tempPath . $canvasType . '-conversion-sale-graph.png', file_get_contents($conversionSale));
		file_put_contents($tempPath . $canvasType . '-conversion-sale-percent-graph.png', file_get_contents($conversionSalePercent));
		file_put_contents($tempPath . $canvasType . '-conversion-sale-total-graph.png', file_get_contents($conversionSaleTotal));
		file_put_contents($tempPath . $canvasType . '-profit-total-graph.png', file_get_contents($profitTotal));
		file_put_contents($tempPath . $canvasType . '-cost-sale-percent-graph.png', file_get_contents($costSalePercent));

		/**
		 * Prepare to send for generating PDF
		 */
		$prospectUrl              = $canvasType . '-prospect-graph.png';
		$quotationUrl             = $canvasType . '-quotation-graph.png';
		$conversionSaleUrl        = $canvasType . '-conversion-sale-graph.png';
		$conversionSalePercentUrl = $canvasType . '-conversion-sale-percent-graph.png';
		$conversionSaleTotalUrl   = $canvasType . '-conversion-sale-total-graph.png';
		$profitTotalUrl           = $canvasType . '-profit-total-graph.png';
		$costSalePercentUrl       = $canvasType . '-cost-sale-percent-graph.png';

		$parameter = [
			'category'                   => $category,
			'categoryId'                 => $categoryId,
			'dateRange'                  => $dateRange,
			'displayType'                => $displaytype,
			'categoryGraph'              => $graphcategory,
			'prospectGraph'              => $prospectUrl,
			'quotationGraph'             => $quotationUrl,
			'conversionSaleGraph'        => $conversionSaleUrl,
			'conversionSalePercentGraph' => $conversionSalePercentUrl,
			'conversionSaleTotalGraph'   => $conversionSaleTotalUrl,
			'profitTotalGraph'           => $profitTotalUrl,
			'costSalePercentGraph'       => $costSalePercentUrl
		];

		$pdfexport = new UserActivityExport($db);
		$pdfexport->write_file($parameter);
	}
}

/*
 * View
 */

llxHeader('', $langs->trans('UserActivityDashboardArea'));

print load_fiche_titre($langs->trans('UserActivityDashboardArea'), '', 'useractivitydashboard.png@useractivitydashboard');

print '<div class="fichecenter">';
$moreforfilter = '';
print '<form method="POST" id="searchFormList" action="' . $_SERVER["PHP_SELF"] . '">';
print '<input type="hidden" name="token" value="' . newToken() . '">';
print '<input type="hidden" name="action" id="action" value="graphs">';
print '<input type="hidden" name="cat" id="cat" value="' . $category . '">';
print '<input type="hidden" name="cat_id" id="cat_id" value="' . $categoryId . '">';

print '<script type="text/javascript" language="javascript">
jQuery(document).ready(function() {
	jQuery("#exportpdfbutton").on("click", function(e) {
		e.preventDefault();
		jQuery("#action").val("exportpdf");
		jQuery("#searchFormList").submit();
		jQuery("#action").val("graphs");
	});
});
</script>';

print '<table class="liste ' . ($moreforfilter ? "listwithfilterbefore" : "") . '">';

print '<tr class="liste_titre_filter">';
print '<td class="liste_titre" colspan="5">';
if (count($categoryarr) > 1) {
	print $langs->trans('DisplayOption') . ': ' . $form->selectarray('display_types', $categoryarr, $displaytype, 0, 0, 1);
}
print '</td>';
if (count($datecategoryarr) > 1) {
	print '<td>';
	print $langs->trans('Date Range') . ': ' . $form->selectDate(strtotime($startDate) ?: $now, 'date_start', 0, 0, 1, '', 1, 1) . ' ' . $form->selectDate(strtotime($endDate) ?: $now, 'date_end', 0, 0, 1, '', 1, 1);
	print '</td>';
} else {
	print '<td>';
	print $langs->trans('Date') . ': ' . $form->selectDate(strtotime($endDate) ?: $now, 'date_end', 0, 0, 1, '', 1, 1);
	print '</td>';
}
print '<td class="liste_titre right">';
print '<div class="nowrap">';
print '<input type="submit" id="refreshbutton" name="refreshbutton" class="butAction" value="' . $langs->trans('Refresh') . '" />';
print '<input type="button" id="exportpdfbutton" name="exportpdfbutton" class="butAction" value="' . $langs->trans('Export PDF') . '" />';
print '</div>';
print '</td>';
print '</tr>';
print '</table>';
print '</form>';
print '</div></div>';
if ($action === 'graphs') {
	$error = 0;
	if(empty($displaytype) && empty($startDate)) {
		setEventMessages($langs->trans('ErrorFieldRequired', $langs->transnoentities('Display Option')), null, 'errors');
		$error++;
	}
	if (!$error) {
		if (empty($startDate)) {
			try {
				$dateRange = getDateRange($displaytype, $endDate, $endMonth, $endYear);
			} catch (Exception $e) {
				return $e;
			}
		} else {
			$dateRange = [
				'start_date' => date('Y-m-d H:i:s', strtotime($startDate)),
				'end_date' => date('Y-m-d 23:59:59', strtotime($endDate))
			];
			$displaytype = 'Date Range';
		}

		$boxstat = '<!-- User Activity statistics -->'."\n";
		$boxstat .= '<div class="box useractivity">';
		$boxstat .= '<table summary="'.dol_escape_htmltag($langs->trans('UserActivityDashboardArea')).'" class="noborder boxtable boxtablenobottom nohover widgetstats" width="100%">';
		$boxstat .= '<tr class="liste_titre box_titre useractivity">';
		$boxstat .= '<td class="liste_titre">';
		$boxstat .= '<div class="centpercent center"><b>' . $langs->trans('PerformanceSummary', getDisplayHeader($displaytype, $dateRange), $category ?? $langs->trans('All')) . '</b></div>';
		$boxstat .= '</td>';
		$boxstat .= '</tr>';
		$boxstat .= '<tr class="nobottom center useractivity"><td class="tdboxstats flexcontainer useractivity">';
		$boxstat .= '<a class="boxstatsindicator thumbstat nobold nounderline"><div class="boxstats useractivitybox"><span class="boxstatstext" title="'.dol_escape_htmltag('Total Number of Prospects Entered').'">Total Number of Prospects Entered</span><br><span class="boxstatsindicator">' . getProspectSummary($categoryId, $dateRange) . '</span></div></a>';
		$boxstat .= '<a class="boxstatsindicator thumbstat nobold nounderline"><div class="boxstats useractivitybox bids"><span class="boxstatstext" title="'.dol_escape_htmltag('Total Number of Bids/Proposals Submitted').'">Total Number of Bids/Proposals Submitted</span><br><span class="boxstatsindicator">' . getQuotationSummary($categoryId, $dateRange) . '</span></div></a>';
		$boxstat .= '<a class="boxstatsindicator thumbstat nobold nounderline"><div class="boxstats useractivitybox sales"><span class="boxstatstext" title="'.dol_escape_htmltag('Total Number of Conversion to Sales').'">Total Number of Conversion to Sales</span><br><span class="boxstatsindicator">' . getConversionSaleSummary($categoryId, $dateRange, $category) . '</span></div></a>';
		$boxstat .= '<a class="boxstatsindicator thumbstat nobold nounderline"><div class="boxstats useractivitybox sales"><span class="boxstatstext" title="'.dol_escape_htmltag('% of Conversion to Sales').'">% of Conversion to Sales</span><br><span class="boxstatsindicator">' . getConversionSalePercentSummary($categoryId, $dateRange, $category) . '%</span></div></a>';
		$boxstat .= '<a class="boxstatsindicator thumbstat nobold nounderline"><div class="boxstats useractivitybox sales"><span class="boxstatstext" title="'.dol_escape_htmltag('Total Sales').'">Total Sales</span><br><span class="boxstatsindicator">' . getTotalSalesSummary($categoryId, $dateRange, $category) . '</span></div></a>';
		$boxstat .= '<a class="boxstatsindicator thumbstat nobold nounderline"><div class="boxstats useractivitybox profit"><span class="boxstatstext" title="'.dol_escape_htmltag('Gross Profit').'">Gross Profit</span><br><span class="boxstatsindicator">' . getTotalProfit($categoryId, $dateRange, $category) . '</span></div></a>';
		$boxstat .= '<a class="boxstatsindicator thumbstat nobold nounderline"><div class="boxstats useractivitybox profit"><span class="boxstatstext" title="'.dol_escape_htmltag('% Cost to Sales').'">% Cost to Sales</span><br><span class="boxstatsindicator">' . getTotalCostPercent($categoryId, $dateRange, $category) . '</span></div></a>';

		$boxstat .= '</td></tr>';
		$boxstat .= '</table>';
		$boxstat .= '</div>';
		print $boxstat;

		/**
		 * Starts Graph display section
		 */
		$prospect = $quotation = $conversionSale = $conversionSalePercent = $conversionSaleTotal = $profitTotal = $costTotalPercent = '';

		$graph = '<div class="box center">';
		$graph .= '<div class="flexcontainer">';
		if (array_key_exists('USERACTIVITYDASHBOARD_SHOW_USERNAME_GRAPH', $graphcategory)) {
			$px = showUserProspectGraphs($categoryId, $dateRange, $category, $displaytype);
			$prospect = $px->show();
			$px1 = showUserQuotationGraphs($categoryId, $dateRange, $category, $displaytype);
			$quotation = $px1->show();
			$px2 = showUserConversionGraphs($categoryId, $dateRange, $category, $displaytype);
			$conversionSale = $px2->show();
			$px3 = showUserConversionPercentGraphs($categoryId, $dateRange, $category, $displaytype);
			$conversionSalePercent = $px3->show();
			$px4 = showUserConversionTotalGraphs($categoryId, $dateRange, $category, $displaytype);
			$conversionSaleTotal = $px4->show();
			$px5 = showUserProfitTotalGraphs($categoryId, $dateRange, $category, $displaytype);
			$profitTotal = $px5->show();
			$px6 = showUserCostPercentGraphs($categoryId, $dateRange, $category, $displaytype);
			$costTotalPercent = $px6->show();

			$graph .= '<div class="box graphitems">';
			$graph .= $prospect;
			$graph .= '</div>';
			$graph .= '<div class="box graphitems">';
			$graph .= $quotation;
			$graph .= '</div>';
			$graph .= '<div class="box graphitems">';
			$graph .= $conversionSale;
			$graph .= '</div>';
			$graph .= '<div class="box graphitems">';
			$graph .= $conversionSalePercent;
			$graph .= '</div>';
			$graph .= '<div class="box graphitems">';
			$graph .= $conversionSaleTotal;
			$graph .= '</div>';
			$graph .= '<div class="box graphitems">';
			$graph .= $profitTotal;
			$graph .= '</div>';
			$graph .= '<div class="box graphitems">';
			$graph .= $costTotalPercent;
			$graph .= '</div>';
		} else {
			if (!empty(getChildCategoryIds($categoryId))) {
				$categories = implode(', ', getChildCategoryIds($categoryId));
				$px = showCategoryProspectGraphs($categories, $dateRange, $category, $displaytype);
				$prospect = $px->show();
				$px1 = showCategoryQuotationGraphs($categories, $dateRange, $category, $displaytype);
				$quotation = $px1->show();
				$px2 = showCategoryConversionGraphs($categories, $dateRange, $category, $displaytype);
				$conversionSale = $px2->show();
				$px3 = showCategoryConversionPercentGraphs($categories, $dateRange, $category, $displaytype);
				$conversionSalePercent = $px3->show();
				$px4 = showCategoryConversionTotalGraphs($categories, $dateRange, $category, $displaytype);
				$conversionSaleTotal = $px4->show();
				$px5 = showCategoryProfitTotalGraphs($categories, $dateRange, $category, $displaytype);
				$profitTotal = $px5->show();
				$px6 = showCategoryCostPercentGraphs($categories, $dateRange, $category, $displaytype);
				$costTotalPercent = $px6->show();
			}
			$graph .= '<div class="box graphitems">';
			$graph .= $prospect;
			$graph .= '</div>';
			$graph .= '<div class="box graphitems">';
			$graph .= $quotation;
			$graph .= '</div>';
			$graph .= '<div class="box graphitems">';
			$graph .= $conversionSale;
			$graph .= '</div>';
			$graph .= '<div class="box graphitems">';
			$graph .= $conversionSalePercent;
			$graph .= '</div>';
			$graph .= '<div class="box graphitems">';
			$graph .= $conversionSaleTotal;
			$graph .= '</div>';
			$graph .= '<div class="box graphitems">';
			$graph .= $profitTotal;
			$graph .= '</div>';
			$graph .= '<div class="box graphitems">';
			$graph .= $costTotalPercent;
			$graph .= '</div>';
		}
		$graph .= '</div>';
		$graph .= '</div>';
		print $graph;
	}
}
// End of page
llxFooter();
$db->close();
