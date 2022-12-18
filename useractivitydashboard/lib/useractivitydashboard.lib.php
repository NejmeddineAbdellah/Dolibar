<?php
/* Copyright (C) 2020 Rabib Ahmad <rabib@japantravel.com>
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
 * \file    useractivitydashboard/lib/useractivitydashboard.lib.php
 * \ingroup useractivitydashboard
 * \brief   Library files with common functions for UserActivityDashboard
 */
dol_include_once('/useractivitydashboard/class/useractivitygraph.class.php');
/**
 * Prepare admin pages header
 *
 * @return array
 */
function useractivitydashboardAdminPrepareHead()
{
	global $langs, $conf;

	$langs->load("useractivitydashboard@useractivitydashboard");

	$h = 0;
	$head = [];

	$head[$h][0] = dol_buildpath("/useractivitydashboard/admin/setup.php", 1);
	$head[$h][1] = $langs->trans("Settings");
	$head[$h][2] = 'settings';
	$h++;

	$head[$h][0] = dol_buildpath("/useractivitydashboard/admin/category_setup.php", 1);
	$head[$h][1] = $langs->trans("Category Settings");
	$head[$h][2] = 'category_settings';
	$h++;

	$head[$h][0] = dol_buildpath("/useractivitydashboard/admin/about.php", 1);
	$head[$h][1] = $langs->trans("About");
	$head[$h][2] = 'about';
	$h++;

	complete_head_from_modules($conf, $langs, null, $head, $h, 'useractivitydashboard');
	return $head;
}


/**
 *    Add setup enable disable option for useractivity dashboard
 *
 * @param $value
 * @param string $category Model $category
 * @param string $type
 * @return        int                        <0 if KO, >0 if OK
 * @throws Exception
 */
function addUserActivitySetup($value, string $category, $type)
{
	global $db, $user;
	$now = dol_now();
	if (!empty($value) && !empty($category)) {
		$activity = getActiveCategory($value, $category);
		if (empty($activity) && checkIfGraph($value, $category) === true) {
			$db->begin();

			$sql = 'INSERT INTO ' . MAIN_DB_PREFIX . 'useractivitydashboard_setup (category_label, category_parameter, status, fk_user_create, date_creation, category_type)';
			$sql .= ' VALUES (\'' . $db->escape($category) . '\', \'' . $db->escape($value) . '\', 1 , \'' . $db->escape($user->id) . '\', \'' . $db->idate($now) . '\', \'' . $type . '\')';
			$resql = $db->query($sql);
			if ($resql) {
				$db->commit();
				return 1;
			} else {
				$db->rollback();
				throw new Exception("Database creation error " . dol_print_error($db));
			}
		} else {
			setEventMessages("Category setup already enabled or related parameter is enabled", null, 'errors');
		}
	} else {
		setEventMessages("Category label or value missing", null, 'errors');
	}
}

/**
 * Delete setup enable disable option for useractivity dashboard
 *
 * @param string $name Model $value
 * @param string $type Model $category
 * @return        int                        <0 if KO, >0 if OK
 * @throws Exception
 */
function deleteUserActivitySetup($value, $category)
{
	global $db;
	if (!empty($value) && !empty($category)) {
		$activity = getActiveCategory($value, $category);
		if (!empty($activity)) {
			$db->begin();

			$sql = 'DELETE FROM ' . MAIN_DB_PREFIX . 'useractivitydashboard_setup';
			$sql .= ' WHERE category_label=\'' . $db->escape($category) . '\'';
			$sql .= ' AND category_parameter=\'' . $db->escape($value) . '\'';
			$resql = $db->query($sql);
			if ($resql) {
				$db->commit();
				return 1;
			} else {
				$db->rollback();
				throw new Exception("Database creation error " . dol_print_error($db));
			}
		} else {
			setEventMessages("Category setup already disabled", null, 'errors');
		}
	} else {
		setEventMessages("Category label or value missing", null, 'errors');
	}
}

/**
 *    Add setup enable disable option for useractivity dashboard
 *
 * @param string $name Model $value
 * @param string $type Model $category
 * @return        Object                        <0 if KO, >0 if OK
 * @throws Exception
 */
function getActiveCategory($value, $category)
{
	global $db;

	if (!empty($value) && !empty($category)) {
		$sql = 'SELECT a.rowid, a.category_label, a.category_parameter, a.status';
		$sql .= ' FROM ' . MAIN_DB_PREFIX . 'useractivitydashboard_setup as a';
		$sql .= ' WHERE a.status=1';
		$sql .= ' AND a.category_label=\'' . $category . '\'';
		$sql .= ' AND a.category_parameter=\'' . $value . '\'';
		$result = $db->query($sql);
		if ($result) {
			$obj = $db->fetch_object($result);
			return $obj;
		} else {
			throw new Exception("Database query error " . dol_print_error($db));
		}
	}
}


/**
 * Add setup enable disable option for category
 * @param $value
 * @param $category
 * @param $module
 * @return int
 * @throws Exception
 */
function addCategorySetup($value, $category, $module)
{
	global $db, $user;
	$now = dol_now();
	if (!empty($value) && !empty($category)) {
		$activity = getActiveCategory($value, $category);
		if (empty($activity) && checkIfGraph($value, $category) === true) {
			$db->begin();

			$sql = 'INSERT INTO ' . MAIN_DB_PREFIX . 'useractivitydashboard_categorysetup (category_label, category_parameter, category_status, fk_user_create, date_creation, module)';
			$sql .= ' VALUES (\'' . $db->escape($category) . '\', \'' . $db->escape($value) . '\', 1 , \'' . $db->escape($user->id) . '\', \'' . $db->idate($now) . '\', \'' . $module . '\')';
			$resql = $db->query($sql);
			if ($resql) {
				$db->commit();
				return 1;
			} else {
				$db->rollback();
				throw new Exception("Database creation error " . dol_print_error($db));
			}
		} else {
			setEventMessages("Category setup already enabled or related parameter is enabled", null, 'errors');
		}
	} else {
		setEventMessages("Category label or value missing", null, 'errors');
	}
}

/**
 * Delete setup enable disable option for category
 * @param $value
 * @param $category
 * @param $module
 * @return int
 * @throws Exception
 */
function deleteCategorySetup($value, $category, $module)
{
	global $db;
	if (!empty($value) && !empty($category)) {
		if (getEnabledCategory($category, $value, $module)) {
			$db->begin();

			$sql = 'DELETE FROM ' . MAIN_DB_PREFIX . 'useractivitydashboard_categorysetup';
			$sql .= ' WHERE category_label=\'' . $db->escape($category) . '\'';
			$sql .= ' AND category_parameter=\'' . $db->escape($value) . '\'';
			$sql .= ' AND module=\'' . $db->escape($module) . '\'';
			$resql = $db->query($sql);
			if ($resql) {
				$db->commit();
				return 1;
			} else {
				$db->rollback();
				throw new Exception("Database creation error " . dol_print_error($db));
			}
		} else {
			setEventMessages("Category setup already disabled", null, 'errors');
		}
	} else {
		setEventMessages("Category label or value missing", null, 'errors');
	}
}

/**
 * Add setup enable disable option for category
 * @param $category
 * @param string $value
 * @param string $module
 * @return bool
 * @throws Exception
 */
function getEnabledCategory($category, $value = 'USERACTIVITYDASHBOARD_ENABLE_EXTRA_DATE', $module = 'USERACTIVITYDASHBOARD_MODULE_GENERAL')
{
	global $db;

	if (!empty($value) && !empty($category)) {
		$sql = 'SELECT a.rowid';
		$sql .= ' FROM ' . MAIN_DB_PREFIX . 'useractivitydashboard_categorysetup as a';
		$sql .= ' WHERE a.category_status=1';
		$sql .= ' AND a.category_label=\'' . $category . '\'';
		$sql .= ' AND a.category_parameter=\'' . $value . '\'';
		$sql .= ' AND a.module=\'' . $module . '\'';
		$result = $db->query($sql);
		if ($result) {
			$num = $db->num_rows($result);
			if ($num > 0) {
				return true;
			} else {
				return false;
			}
		} else {
			throw new Exception("Database query error " . dol_print_error($db));
		}
	}
}

/**
 * Get TA category setup
 * @param $category
 * @param string $value
 * @param string $module
 * @return bool|string
 */
function getEnabledTACategory($category, $value = 'USERACTIVITYDASHBOARD_ENABLE_EXTRA_DATE', $module = 'USERACTIVITYDASHBOARD_MODULE_TRAVEL AGENCY')
{
	try {
		return getEnabledCategory($category, $value, $module);
	} catch (Exception $e) {
		$this->error = $e->getMessage();
		return $this->error;
	}
}

/**
 * Get Media category setup
 * @param $category
 * @param string $value
 * @param string $module
 * @return bool|string
 */
function getEnabledMediaCategory($category, $value = 'USERACTIVITYDASHBOARD_ENABLE_ACCOUNTING', $module = 'USERACTIVITYDASHBOARD_MODULE_MEDIA')
{
	try {
		return getEnabledCategory($category, $value, $module);
	} catch (Exception $e) {
		$this->error = $e->getMessage();
		return $this->error;
	}
}

/**
 * Get Engineering category setup
 * @param $category
 * @param string $value
 * @param string $module
 * @return bool|string
 */
function getEnabledEngineeringCategory($category, $value = 'USERACTIVITYDASHBOARD_ENABLE_ACCOUNTING', $module = 'USERACTIVITYDASHBOARD_MODULE_ENGINEERING')
{
	try {
		return getEnabledCategory($category, $value, $module);
	} catch (Exception $e) {
		$this->error = $e->getMessage();
		return $this->error;
	}
}

/**
 * Check if the Username Graph or Category Graph is enabled
 * @param $value
 * @param $category
 * @return bool
 * @throws Exception
 */
function checkIfGraph($value, $category)
{
	if ($value === 'USERACTIVITYDASHBOARD_SHOW_USERNAME_GRAPH') {
		if (empty(getActiveCategory('USERACTIVITYDASHBOARD_SHOW_SUB_CATE_GRAPH', $category))) {
			return true;
		} else {
			return false;
		}
	} elseif ($value === 'USERACTIVITYDASHBOARD_SHOW_SUB_CATE_GRAPH') {
		if (empty(getActiveCategory('USERACTIVITYDASHBOARD_SHOW_USERNAME_GRAPH', $category))) {
			return true;
		} else {
			return false;
		}
	} else {
		return true;
	}
}

/**
 * Get the fields for filter based on the user category
 * @param $id
 * @return mixed
 * @throws Exception
 */
function getUserCategory($id, $type, $categoryId)
{
	global $db, $langs, $user;
	if ($id > 0) {
		$sql = 'SELECT a.category_parameter FROM ' . MAIN_DB_PREFIX . 'categorie as c, ';
		$sql .= MAIN_DB_PREFIX . 'useractivitydashboard_setup as a, ';
		$sql .= MAIN_DB_PREFIX . 'categorie_user as cu ';
		$sql .= 'WHERE cu.fk_categorie = c.rowid';
		$sql .= ' AND c.label = a.category_label COLLATE utf8_unicode_ci';
		$sql .= ' AND a.category_type = \'' . $type . '\'';
		if (!$user->rights->useractivitydashboard->view_all) {
			$sql .= ' AND cu.fk_user = ' . $id;
		}
		if (!empty($categoryId)) {
			$sql .= ' AND c.rowid=' . $categoryId;
		}

		$result = $db->query($sql);
		if ($result) {
			$i = 0;
			$num = $db->num_rows($result);
			$categories = [];
			while ($i < $num) {
				$obj = $db->fetch_object($result);
				$categories[$obj->category_parameter] = $langs->trans($obj->category_parameter);
				$i++;
			}
			return $categories;
		} else {
			throw new Exception("Database query error " . dol_print_error($db));
		}
	} else {
		setEventMessages("User ID invalid or missing", null, 'errors');
	}
}

/**
 * Get the user group name
 * @param $id
 * @return mixed
 * @throws Exception
 */
function getUserGroup($id)
{
	global $db;
	if ($id > 0) {
		$sql = 'SELECT c.label, c.rowid FROM ' . MAIN_DB_PREFIX . 'categorie as c, ';
		$sql .= MAIN_DB_PREFIX . 'categorie_user as cu ';
		$sql .= 'WHERE cu.fk_user = ' . $id;
		$sql .= ' AND cu.fk_categorie = c.rowid';
		$result = $db->query($sql);
		if ($result) {
			return $db->fetch_object($result);
		} else {
			throw new Exception("Database query error " . dol_print_error($db));
		}
	} else {
		setEventMessages("User ID invalid or missing", null, 'errors');
	}
}

/**
 * @param $category
 * @param $dateRange
 * @return mixed
 * @throws Exception
 */
function getProspectSummary($category, $dateRange)
{
	global $db;
	$startDate = $dateRange['start_date'];
	$endDate = $dateRange['end_date'];
	$sql = 'SELECT count(s.rowid) as prospects FROM ' . MAIN_DB_PREFIX . 'societe as s,';
	$sql .= MAIN_DB_PREFIX . 'categorie_user as cu ';
	$sql .= 'WHERE cu.fk_user = s.fk_user_creat';
	if ($category) {
		$sql .= ' AND cu.fk_categorie = ' . $category;
	}
	$sql .= ' AND s.client IN (2,3)';
	$sql .= ' AND (s.datec BETWEEN \'' . $startDate . '\' AND \'' . $endDate . '\')';
	$result = $db->query($sql);
	if ($result) {
		$obj = $db->fetch_object($result);
		return $obj->prospects;
	} else {
		throw new Exception("Database query error " . dol_print_error($db));
	}
}

/**
 * @param $category
 * @param $dateRange
 * @return mixed
 * @throws Exception
 */
function getQuotationSummary($category, $dateRange)
{
	include_once DOL_DOCUMENT_ROOT . '/comm/propal/class/propal.class.php';
	global $db;
	$startDate = $dateRange['start_date'];
	$endDate = $dateRange['end_date'];
	$sql = 'SELECT count(p.rowid) as quotations FROM ' . MAIN_DB_PREFIX . 'propal as p,';
	$sql .= MAIN_DB_PREFIX . 'categorie_user as cu ';
	$sql .= 'WHERE cu.fk_user = p.fk_user_author';
	$sql .= ' AND (p.fk_statut = ' . Propal::STATUS_BILLED . ' OR p.fk_statut = ' . Propal::STATUS_NOTSIGNED . ' OR p.fk_statut = ' . Propal::STATUS_SIGNED . ')';
	if ($category) {
		$sql .= ' AND cu.fk_categorie = ' . $category;
	}
	$sql .= ' AND (p.datep BETWEEN \'' . $startDate . '\' AND \'' . $endDate . '\')';
	$result = $db->query($sql);
	if ($result) {
		$obj = $db->fetch_object($result);
		return $obj->quotations;
	} else {
		throw new Exception("Database query error " . dol_print_error($db));
	}
}

/**
 * Return the calculated summary value of conversion sale
 * @param $category
 * @param $dateRange
 * @param $categoryLabel
 * @return mixed
 * @throws Exception
 */
function getConversionSaleSummary($category, $dateRange, $categoryLabel)
{
	dol_include_once('/compta/facture/class/facture.class.php');
	global $db, $conf;
	$startDate = $dateRange['start_date'];
	$endDate = $dateRange['end_date'];
	$sql = 'SELECT count(f.rowid) as invoices ';
	$sql .= 'FROM ' . MAIN_DB_PREFIX . 'facture as f ';
	$sql .= 'LEFT JOIN ' . MAIN_DB_PREFIX . 'categorie_user as cu ON cu.fk_user = f.fk_user_author ';
	$sql .= 'LEFT JOIN ' . MAIN_DB_PREFIX . 'facture_extrafields as fe ON fe.fk_object = f.rowid ';
	$sql .= 'LEFT JOIN ' . MAIN_DB_PREFIX . 'element_element as el ON el.fk_target = f.rowid ';
	$sql .= ' WHERE f.ref LIKE \'' . $conf->global->INVOICE_PREFIX . '%\'';
	$sql .= ' AND (f.fk_statut = \'' . Facture::STATUS_CLOSED . '\' OR f.fk_statut = \'' . Facture::STATUS_VALIDATED . '\')';
	$sql .= ' AND el.sourcetype = \'commande\'';
	$sql .= ' AND el.targettype = \'facture\'';
	if ($category) {
		$sql .= ' AND cu.fk_categorie = ' . $category;
	}
	if (getEnabledTACategory($categoryLabel)) {
		$sql .= ' AND (fe.tourenddate BETWEEN \'' . $startDate . '\' AND \'' . $endDate . '\')';
	} else {
		$sql .= ' AND (f.datef BETWEEN \'' . $startDate . '\' AND \'' . $endDate . '\')';
	}

	$result = $db->query($sql);
	if ($result) {
		$obj = $db->fetch_object($result);
		return $obj->invoices;
	} else {
		throw new Exception("Database query error " . dol_print_error($db));
	}
}

/**
 * Return the calculated summary value percentage of conversion sale
 * @param $category
 * @param $dateRange
 * @param $categoryLabel
 * @return int
 * @throws Exception
 */
function getConversionSalePercentSummary($category, $dateRange, $categoryLabel)
{
	return round((getConversionSaleSummary($category, $dateRange, $categoryLabel) / getQuotationSummary($category, $dateRange)) * 100);
}

/**
 * Return the calculated total price of conversion sale based on category
 * @param $category
 * @param $dateRange
 * @param $categoryLabel
 * @return string
 * @throws Exception
 */
function getTotalSalesSummary($category, $dateRange, $categoryLabel)
{
	dol_include_once(DOL_DOCUMENT_ROOT . '/compta/facture/class/facture.class.php');
	global $db, $conf, $langs, $user;
	$startDate = $dateRange['start_date'];
	$endDate = $dateRange['end_date'];
	$sql = 'SELECT sum(fd.total_ht) as total';
	$sql .= ' FROM ' . MAIN_DB_PREFIX . 'facturedet as fd';
	$sql .= '  LEFT JOIN ' . MAIN_DB_PREFIX . 'facture as f ON f.rowid = fd.fk_facture';
	$sql .= '  LEFT JOIN ' . MAIN_DB_PREFIX . 'facture_extrafields as fe ON fe.fk_object = f.rowid';
	$sql .= '  LEFT JOIN ' . MAIN_DB_PREFIX . 'categorie_user as cu ON cu.fk_user = f.fk_user_author';
	$sql .= '  LEFT JOIN ' . MAIN_DB_PREFIX . 'accounting_account as aa ON aa.rowid = fd.fk_code_ventilation';
	$sql .= '  LEFT JOIN ' . MAIN_DB_PREFIX . 'element_element as el ON el.fk_target = f.rowid';
	$sql .= ' WHERE (f.fk_statut = \'' . Facture::STATUS_CLOSED . '\' OR f.fk_statut = \'' . Facture::STATUS_VALIDATED . '\')';
	$sql .= ' AND el.sourcetype = \'commande\' AND el.targettype = \'facture\'';
	if ($category) {
		$sql .= ' AND cu.fk_categorie = ' . $category;
		if (getEnabledTACategory($categoryLabel)) {
			$sql .= ' AND aa.account_number LIKE \'' . $conf->global->TA_ACCOUNTING_CODE . '%\'';
			$sql .= ' AND (fe.tourenddate BETWEEN \'' . $startDate . '\' AND \'' . $endDate . '\')';
		} else {
			$sql .= ' AND (aa.account_number LIKE \'' . $conf->global->MEDIA_ACCOUNTING_CODE . '%\' OR aa.account_number LIKE \'' . $conf->global->ENGINEERING_ACCOUNTING_CODE . '%\')';
			$sql .= ' AND (f.datef BETWEEN \'' . $startDate . '\' AND \'' . $endDate . '\')';
		}
	} elseif ($user->rights->useractivitydashboard->view_all) {
		$sql .= ' AND (aa.account_number LIKE \'' . $conf->global->MEDIA_ACCOUNTING_CODE . '%\' OR aa.account_number LIKE \'' . $conf->global->ENGINEERING_ACCOUNTING_CODE . '%\' OR aa.account_number LIKE \'' . $conf->global->TA_ACCOUNTING_CODE . '%\')';
		$sql .= ' AND ((f.datef BETWEEN \'' . $startDate . '\' AND \'' . $endDate . '\') OR (fe.tourenddate BETWEEN \'' . $startDate . '\' AND \'' . $endDate . '\'))';
	}

	$result = $db->query($sql);
	if ($result) {
		$obj = $db->fetch_object($result);
		return $langs->getCurrencySymbol($conf->currency) . ' ' . price($obj->total);
	} else {
		throw new Exception("Database query error " . dol_print_error($db));
	}
}

/**
 * Get total cost per category
 * @param $category
 * @param $dateRange
 * @param $categoryLabel
 * @return string
 * @throws Exception
 */
function getTotalCostSummary($category, $dateRange, $categoryLabel)
{
	global $db, $conf;
	$startDate = $dateRange['start_date'];
	$endDate = $dateRange['end_date'];
	$sql = 'SELECT sum(ab.debit - ab.credit) as total';
	$sql .= ' FROM ' . MAIN_DB_PREFIX . 'accounting_bookkeeping as ab';
	$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'categorie_user as cu ON cu.fk_user = ab.fk_user';
	$sql .= ' WHERE';
	if ($category) {
		if (getEnabledTACategory($categoryLabel)) {
			$sql .= ' ab.numero_compte LIKE \'' . $conf->global->ACCOUNTING_COST_TA_CODE . '%\'';
		} elseif (getEnabledMediaCategory($categoryLabel)) {
			$sql .= ' ab.numero_compte LIKE \'' . $conf->global->ACCOUNTING_COST_MEDIA_CODE . '%\'';
		} elseif (getEnabledEngineeringCategory($categoryLabel)) {
			$sql .= ' ab.numero_compte LIKE \'' . $conf->global->ACCOUNTING_COST_ENG_CODE . '%\'';
		} else {
			$sql .= ' ab.numero_compte LIKE \'' . $conf->global->ACCOUNTING_COST_CODE . '%\'';
		}
		$sql .= ' AND cu.fk_categorie = ' . $category;
	} else {
		$sql .= ' ab.numero_compte LIKE \'' . $conf->global->ACCOUNTING_COST_CODE . '%\'';
	}
	$sql .= ' AND (ab.doc_date BETWEEN \'' . $startDate . '\' AND \'' . $endDate . '\')';
	$result = $db->query($sql);
	if ($result) {
		$obj = $db->fetch_object($result);
		return price($obj->total);
	} else {
		throw new Exception("Database query error " . dol_print_error($db));
	}
}

/**
 * Get total profit
 * @param $category
 * @param $dateRange
 * @param $categoryLabel
 * @return string
 * @throws Exception
 */
function getTotalProfit($category, $dateRange, $categoryLabel)
{
	global $langs, $conf;
	$sales = ltrim(price2num(getTotalSalesSummary($category, $dateRange, $categoryLabel)), $langs->getCurrencySymbol($conf->currency));
	$cost = price2num(getTotalCostSummary($category, $dateRange, $categoryLabel));
	return $langs->getCurrencySymbol($conf->currency) . ' ' . price($sales - $cost);
}

/**
 * Get Cost percent summary
 * @param $category
 * @param $dateRange
 * @param $categoryLabel
 * @return float|int
 * @throws Exception
 */
function getTotalCostPercent($category, $dateRange, $categoryLabel)
{
	global $langs, $conf;
	$sales = ltrim(price2num(getTotalSalesSummary($category, $dateRange, $categoryLabel)), $langs->getCurrencySymbol($conf->currency));
	$cost = price2num(getTotalCostSummary($category, $dateRange, $categoryLabel));
	return round(($cost / $sales) * 100) . '%';
}

/**
 * Return start and end dates for weekly data
 * @param $date
 * @return array
 */
function getWeekDates($date)
{
	global $conf;
	$startDate = dol_print_date($date, 'A') == $conf->global->WEEK_START_DAY ? date('Y-m-d 00:00:00', $date) : date('Y-m-d 00:00:00', strtotime('last ' . strtolower($conf->global->WEEK_START_DAY) . 'day'));
	$endDate = date('Y-m-d 23:59:s', strtotime($startDate. ' + 6 days'));
	return ['start_date' => $startDate, 'end_date' => $endDate];
}

/**
 * Return start and end dates for monthly data
 * @param $date
 * @param $month
 * @param $year
 * @return array
 * @throws Exception
 */
function getMonthDates($date, $month, $year)
{
	$d = new DateTime($date);
	$startDate = date($year . '-' . $month . '-01 00:00:00');
	$endDate = $d->format($year . '-' . $month . '-t 23:59:s');
	return ['start_date' => $startDate, 'end_date' => $endDate];
}

/**
 * Return start and end dates for quarterly data
 * @param $month
 * @param $year
 * @return array
 */
function getQuarterlyDates($month, $year)
{
	$q1EndMonth = 6;
	$q2EndMonth = 9;
	$q3EndMonth = 12;
	$q4EndMonth = 3;
	if ($q2EndMonth >= $month && $q1EndMonth < $month) {
		$endMonth = $q2EndMonth;
	} elseif ($q3EndMonth >= $month && $q2EndMonth < $month) {
		$endMonth = $q3EndMonth;
	} elseif ($q4EndMonth >= $month) {
		$endMonth = $q4EndMonth;
	} else {
		$endMonth = $q1EndMonth;
	}
	$startTime = mktime(0, 0, 0, $endMonth - 2, 1, $year);
	$endTime = mktime(23, 59, 0, $endMonth, date('t'), $year);
	$startDate = date('Y-m-d H:i:s', $startTime);
	$endDate = date('Y-m-t H:i:s', $endTime);

	return ['start_date' => $startDate, 'end_date' => $endDate];
}

/**
 * Return start and end dates for yearly data
 * @param $year
 * @return array
 */
function getYearDates($year)
{
	global $conf;
	$startDate = date($year . '-' . $conf->global->YEAR_START_MONTH . '-01 00:00:00');
	$endDate = date($year+1 . '-' . $conf->global->YEAR_END_MONTH . '-t 23:59:s');
	return ['start_date' => $startDate, 'end_date' => $endDate];
}

/**
 * @param $displayType
 * @param $date
 * @param $month
 * @param $year
 * @return array
 * @throws Exception
 */
function getDateRange($displayType, $date, $month, $year)
{
	if ($displayType === 'Weekly') {
		return getWeekDates($date);
	} elseif ($displayType === 'Monthly') {
		return getMonthDates($date, $month, $year);
	} elseif ($displayType === 'Quarterly') {
		return getQuarterlyDates($month, $year);
	} elseif ($displayType === 'Yearly') {
		return getYearDates($year);
	} else {
		setEventMessages("Display Type invalid or missing", null, 'errors');
	}
}

/**
 * Get User prospects data
 * @param $category
 * @param $dateRange
 * @return array
 * @throws Exception
 */
function getUserProspects($category, $dateRange)
{
	global $db;
	$startDate = $dateRange['start_date'];
	$endDate = $dateRange['end_date'];
	$prospects = [];
	$sql = 'SELECT count(s.rowid) as prospects, u.login as author FROM ' . MAIN_DB_PREFIX . 'societe as s';
	$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'categorie_user as cu ON cu.fk_user = s.fk_user_creat';
	$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'user as u ON u.rowid = s.fk_user_creat ';
	$sql .= 'WHERE s.client IN (2,3)';
	if ($category) {
		$sql .= ' AND cu.fk_categorie = ' . $category;
	}
	$sql .= ' AND (s.datec BETWEEN \'' . $startDate . '\' AND \'' . $endDate . '\')';
	$sql .= ' GROUP BY s.fk_user_creat';
	$result = $db->query($sql);
	if ($result) {
		while($obj = $db->fetch_object($result)) {
			$prospects[] = [$obj->author, $obj->prospects];
		}
		return $prospects;
	} else {
		throw new Exception("Database query error " . dol_print_error($db));
	}
}

/**
 * Show graph based on prospect data
 * @param $category
 * @param $dateRange
 * @param $categoryLabel
 * @param $displayType
 * @return DolGraph
 * @throws Exception
 */
function showUserProspectGraphs($category, $dateRange, $categoryLabel, $displayType)
{
	global $conf, $langs;
	$width = $conf->global->USER_ACTIVITY_GRAPH_WIDTH ?? '520';
	$height = $conf->global->USER_ACTIVITY_GRAPH_HEIGHT ?? '330';

	$data = getUserProspects($category, $dateRange);

	$categories = implode(', ', getChildCategoryIds($category));
	if (!empty($categories)) {
		$allData = getCategoryProspects($categories, $dateRange);
		$secondaryVal = $allData['secondary'];
	}

	$dir = '';
	$dataType = ['bars'];
	$px = new UserActivityGraph();
	$mesg = $px->isGraphKo();
	$filenamenb = $dir . '/' . 'user-prospect-graph.png';
	$fileurlnb = DOL_URL_ROOT . '/viewimage.php?modulepart=useractivitydashboard&amp;file=user-prospect-graph.png';
	if (!$mesg) {
		$px->SetType($dataType);
		$px->SetData($data);
		$px->SetDataColor([[92, 155, 213]]);
		$px->setLineColor([[220, 120, 0]]);
		unset($data);
		$legend = ['Prospects'];
		$px->SetLegend($legend);
		$px->SetMaxValue($px->GetCeilMaxValue());
		$px->SetMinValue(min(0, $px->GetFloorMinValue()));
		$px->SetWidth($width);
		$px->SetHeight($height);
		$px->setShowGraphLine(true);
		$px->setShowXGridLine('false');
		if (!empty($secondaryVal)) {
			$px->setSecondaryLineCount(implode(',', $secondaryVal));
			$px->setSecondaryLineColor([[170, 170, 170]]);
			$label = implode(',', array_keys($secondaryVal));
			$px->setSecondaryLineLabel($label);
		}
		$px->SetYLabel($langs->trans('Prospects Entered'));
		$px->SetShading(3);
		$px->SetHorizTickIncrement(1);
		$px->SetBgColorGrid([[255, 255, 255]]);
		$px->SetCssPrefix("useractivity");
		$px->mode = 'depth';
		$px->SetTitle($langs->trans("ProspectsEntered", $categoryLabel, getDisplayHeader($displayType, $dateRange)));
		$px->draw($filenamenb, $fileurlnb);
		return $px;
	}
}


/**
 * Get User Quotations data
 * @param $category
 * @param $dateRange
 * @return array
 * @throws Exception
 */
function getUserQuotations($category, $dateRange)
{
	include_once DOL_DOCUMENT_ROOT . '/comm/propal/class/propal.class.php';
	global $db;
	$startDate = $dateRange['start_date'];
	$endDate = $dateRange['end_date'];
	$sql = 'SELECT count(p.ref) as quotations, u.login as author FROM ' . MAIN_DB_PREFIX . 'propal as p';
	$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'user as u ON u.rowid = p.fk_user_author';
	$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'categorie_user as cu ON cu.fk_user = p.fk_user_author';
	$sql .= ' WHERE (p.fk_statut = ' . Propal::STATUS_BILLED . ' OR p.fk_statut = ' . Propal::STATUS_NOTSIGNED . ' OR p.fk_statut = ' . Propal::STATUS_SIGNED . ')';
	if ($category) {
		$sql .= ' AND cu.fk_categorie = ' . $category;
	}
	$sql .= ' AND (p.datep BETWEEN \'' . $startDate . '\' AND \'' . $endDate . '\')';
	$sql .= ' GROUP BY p.fk_user_author';
	$result = $db->query($sql);
	if ($result) {
		$quotations = [];
		while ($obj = $db->fetch_object($result)) {
			$quotations[] = [$obj->author, $obj->quotations];
		}
		return $quotations;
	} else {
		throw new Exception("Database query error " . dol_print_error($db));
	}
}

/**
 * Show graph based on quotation data
 * @param $category
 * @param $dateRange
 * @param $categoryLabel
 * @param $displayType
 * @return DolGraph
 * @throws Exception
 */
function showUserQuotationGraphs($category, $dateRange, $categoryLabel, $displayType)
{
	global $conf, $langs;
	$width = $conf->global->USER_ACTIVITY_GRAPH_WIDTH ?? '520';
	$height = $conf->global->USER_ACTIVITY_GRAPH_HEIGHT ?? '330';

	$data = getUserQuotations($category, $dateRange);
	$dir = '';
	$dataType = ['bars'];
	$px = new UserActivityGraph();
	$mesg = $px->isGraphKo();
	$filenamenb = $dir . '/' . 'user-quotation-graph.png';
	$fileurlnb = DOL_URL_ROOT . '/viewimage.php?modulepart=useractivitydashboard&amp;file=user-quotation-graph.png';
	if (!$mesg) {
		$px->SetType($dataType);
		$px->SetData($data);
		$px->SetDataColor([[92, 155, 213]]);
		$px->setLineColor([[220, 120, 0]]);
		unset($data);
		$legend = [$langs->trans("QuotationsLegend", getDisplayString($displayType))];
		$px->SetLegend($legend);
		$px->SetMaxValue($px->GetCeilMaxValue());
		$px->SetMinValue(min(0, $px->GetFloorMinValue()));
		$px->SetWidth($width);
		$px->SetHeight($height);
		$px->setShowGraphLine(true);
		$px->setShowXGridLine('false');
		$px->SetShading(3);
		$px->SetHorizTickIncrement(1);
		$px->SetBgColorGrid([[255, 255, 255]]);
		$px->mode = 'depth';
		$px->SetTitle($langs->trans("QuotationsEntered", $categoryLabel, getDisplayHeader($displayType, $dateRange)));
		$px->draw($filenamenb, $fileurlnb);
		return $px;
	}
}

/**
 * Get display type text to show in legend area
 * Get Week instead of Weekly
 * @param $displayType
 * @return string
 */
function getDisplayString($displayType)
{
	if ($displayType !== 'Date Range') {
		return rtrim($displayType, 'ly');
	}
	return $displayType;
}

/**
 * Get display type text to show in header area
 * Get Week instead of Weekly
 * @param $displayType
 * @param $dateRange
 * @return string
 */
function getDisplayHeader($displayType, $dateRange)
{
	if ($displayType == 'Date Range') {
		return date('m/d/Y', strtotime($dateRange['start_date'])) . ' - ' . date('m/d/Y', strtotime($dateRange['end_date']));
	}
	return $displayType;
}


/**
 * Return the calculated values for user sales
 * @param $category
 * @param $dateRange
 * @param $categoryLabel
 * @return mixed
 * @throws Exception
 */
function getUserConversionSale($category, $dateRange, $categoryLabel)
{
	dol_include_once(DOL_DOCUMENT_ROOT . '/compta/facture/class/facture.class.php');
	global $db, $conf;
	$startDate = $dateRange['start_date'];
	$endDate = $dateRange['end_date'];
	$sql = 'SELECT count(f.rowid) as invoices, u.login as author ';
	$sql .= 'FROM ' . MAIN_DB_PREFIX . 'facture as f ';
	$sql .= 'LEFT JOIN ' . MAIN_DB_PREFIX . 'categorie_user as cu ON cu.fk_user = f.fk_user_author ';
	$sql .= 'LEFT JOIN ' . MAIN_DB_PREFIX . 'user as u ON u.rowid = f.fk_user_author ';
	$sql .= 'LEFT JOIN ' . MAIN_DB_PREFIX . 'facture_extrafields as fe ON fe.fk_object = f.rowid ';
	$sql .= 'LEFT JOIN ' . MAIN_DB_PREFIX . 'element_element as el ON el.fk_target = f.rowid ';
	$sql .= ' WHERE f.ref LIKE \'' . $conf->global->INVOICE_PREFIX . '%\'';
	$sql .= ' AND (f.fk_statut = \'' . Facture::STATUS_CLOSED . '\' OR f.fk_statut = \'' . Facture::STATUS_VALIDATED . '\')';
	$sql .= ' AND el.sourcetype = \'commande\'';
	$sql .= ' AND el.targettype = \'facture\'';
	if ($category) {
		$sql .= ' AND cu.fk_categorie = ' . $category;
	}
	if (getEnabledTACategory($categoryLabel)) {
		$sql .= ' AND (fe.tourenddate BETWEEN \'' . $startDate . '\' AND \'' . $endDate . '\')';
	} else {
		$sql .= ' AND (f.datef BETWEEN \'' . $startDate . '\' AND \'' . $endDate . '\')';
	}
	$sql .= ' GROUP BY f.fk_user_author';

	$result = $db->query($sql);
	if ($result) {
		$invoices = [];
		while ($obj = $db->fetch_object($result)) {
			$invoices[] = [$obj->author, $obj->invoices];
		}
		return $invoices;
	} else {
		throw new Exception("Database query error " . dol_print_error($db));
	}
}

/**
 * Show graph based on conversion sales data
 * @param $category
 * @param $dateRange
 * @param $categoryLabel
 * @param $displayType
 * @return UserActivityGraph
 * @throws Exception
 */
function showUserConversionGraphs($category, $dateRange, $categoryLabel, $displayType)
{
	global $conf, $langs;
	$width = $conf->global->USER_ACTIVITY_GRAPH_WIDTH ?? '520';
	$height = $conf->global->USER_ACTIVITY_GRAPH_HEIGHT ?? '330';

	$data = getUserConversionSale($category, $dateRange, $categoryLabel);
	$dir = '';
	$dataType = ['bars'];
	$px = new UserActivityGraph();
	$mesg = $px->isGraphKo();
	$filenamenb = $dir . '/' . 'user-conversion-sale-graph.png';
	$fileurlnb = DOL_URL_ROOT . '/viewimage.php?modulepart=useractivitydashboard&amp;file=user-conversion-sale-graph.png';
	if (!$mesg) {
		$px->SetType($dataType);
		$px->SetData($data);
		$px->SetDataColor([[92, 155, 213]]);
		$px->setLineColor([[220, 120, 0]]);
		unset($data);
		$legend = [$langs->trans("ConversionSaleLegend", getDisplayString($displayType))];
		$px->SetLegend($legend);
		$px->SetMaxValue($px->GetCeilMaxValue());
		$px->SetMinValue(min(0, $px->GetFloorMinValue()));
		$px->SetWidth($width);
		$px->SetHeight($height);
		$px->setShowGraphLine(true);
		$px->setShowXGridLine('false');
		$px->SetShading(3);
		$px->SetHorizTickIncrement(1);
		$px->SetBgColorGrid([[255, 255, 255]]);
		$px->mode = 'depth';
		$px->SetTitle($langs->trans("ConversionSalesEntered", $categoryLabel, getDisplayHeader($displayType, $dateRange)));
		$px->draw($filenamenb, $fileurlnb);
		return $px;
	}
}


/**
 * Return the calculated value percentage of conversion sale per Users
 * @param $category
 * @param $dateRange
 * @param $categoryLabel
 * @return array
 * @throws Exception
 */
function getUserConversionSalePercent($category, $dateRange, $categoryLabel)
{
	$percentSale = [];
	$userConversionSales = getUserConversionSale($category, $dateRange, $categoryLabel);
	$userQuotations = getUserQuotations($category, $dateRange);

	// Prepares arrays with names in the indexes
	$quotations = [];
	foreach ($userQuotations as $quotation) {
		$quotations[$quotation[0]] = $quotation[1];
	}

	$conversionSales = [];
	foreach ($userConversionSales as $conversionSale) {
		$conversionSales[$conversionSale[0]] = $conversionSale[1];
	}

	// Merge array based on array indexes
	$conversionSaleQuotation = array_merge_recursive($conversionSales, $quotations);

	// Prepare data for generating graph
	foreach ($conversionSaleQuotation as $name => $conversionSale) {
		if (is_array($conversionSale)) {
			$percentResult = ($conversionSale[0] / $conversionSale[1]) * 100;
			$percentSale[] = [$name, $percentResult];
		}
	}

	return $percentSale;
}

/**
 * Show graph based on conversion sales % data
 * @param $category
 * @param $dateRange
 * @param $categoryLabel
 * @param $displayType
 * @return UserActivityGraph
 * @throws Exception
 */
function showUserConversionPercentGraphs($category, $dateRange, $categoryLabel, $displayType)
{
	global $conf, $langs;
	$width = $conf->global->USER_ACTIVITY_GRAPH_WIDTH ?? '520';
	$height = $conf->global->USER_ACTIVITY_GRAPH_HEIGHT ?? '330';

	$data = getUserConversionSalePercent($category, $dateRange, $categoryLabel);
	$dir = '';
	$dataType = ['bars'];
	$px = new UserActivityGraph();
	$mesg = $px->isGraphKo();
	$filenamenb = $dir . '/' . 'user-conversion-sale-percent-graph.png';
	$fileurlnb = DOL_URL_ROOT . '/viewimage.php?modulepart=useractivitydashboard&amp;file=user-conversion-sale-percent-graph.png';
	if (!$mesg) {
		$px->SetType($dataType);
		$px->SetData($data);
		$px->SetDataColor([[92, 155, 213]]);
		$px->setLineColor([[220, 120, 0]]);
		unset($data);
		$legend = [$langs->trans("ConversionSalePercentLegend")];
		$px->SetLegend($legend);
		$px->SetMaxValue(100);
		$px->SetMinValue(min(0, $px->GetFloorMinValue()));
		$px->SetWidth($width);
		$px->SetHeight($height);
		$px->setShowPercent(1);
		$px->setShowGraphLine(true);
		$px->setShowXGridLine('false');
		$px->SetShading(3);
		$px->SetHorizTickIncrement(1);
		$px->SetBgColorGrid([[255, 255, 255]]);
		$px->mode = 'depth';
		$px->SetTitle($langs->trans("ConversionSalesPercentEntered", $categoryLabel, getDisplayHeader($displayType, $dateRange)));
		$px->draw($filenamenb, $fileurlnb);
		return $px;
	}
}

/**
 * Return the users calculated total price of conversion sale based on category
 * @param $category
 * @param $dateRange
 * @param $categoryLabel
 * @return array
 * @throws Exception
 */
function getUserTotalSales($category, $dateRange, $categoryLabel)
{
	dol_include_once(DOL_DOCUMENT_ROOT . '/compta/facture/class/facture.class.php');
	global $db, $conf, $langs, $user;
	$startDate = $dateRange['start_date'];
	$endDate = $dateRange['end_date'];
	$sql = 'SELECT sum(fd.total_ht) as total, u.login as author';
	$sql .= ' FROM ' . MAIN_DB_PREFIX . 'facturedet as fd';
	$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'facture as f ON f.rowid = fd.fk_facture';
	$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'facture_extrafields as fe ON fe.fk_object = f.rowid';
	$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'categorie_user as cu ON cu.fk_user = f.fk_user_author';
	$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'user as u ON u.rowid = f.fk_user_author ';
	$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'accounting_account as aa ON aa.rowid = fd.fk_code_ventilation';
	$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'element_element as el ON el.fk_target = f.rowid';
	$sql .= ' WHERE (f.fk_statut = \'' . Facture::STATUS_CLOSED . '\' OR f.fk_statut = \'' . Facture::STATUS_VALIDATED . '\')';
	$sql .= ' AND el.sourcetype = \'commande\' AND el.targettype = \'facture\'';
	if ($category) {
		$sql .= ' AND cu.fk_categorie = ' . $category;
		if (getEnabledTACategory($categoryLabel)) {
			$sql .= ' AND aa.account_number LIKE \'' . $conf->global->TA_ACCOUNTING_CODE . '%\'';
			$sql .= ' AND (fe.tourenddate BETWEEN \'' . $startDate . '\' AND \'' . $endDate . '\')';
		} else {
			$sql .= ' AND (aa.account_number LIKE \'' . $conf->global->MEDIA_ACCOUNTING_CODE . '%\' OR aa.account_number LIKE \'' . $conf->global->ENGINEERING_ACCOUNTING_CODE . '%\')';
			$sql .= ' AND (f.datef BETWEEN \'' . $startDate . '\' AND \'' . $endDate . '\')';
		}
	} elseif ($user->rights->useractivitydashboard->view_all) {
		$sql .= ' AND (aa.account_number LIKE \'' . $conf->global->MEDIA_ACCOUNTING_CODE . '%\' OR aa.account_number LIKE \'' . $conf->global->ENGINEERING_ACCOUNTING_CODE . '%\' OR aa.account_number LIKE \'' . $conf->global->TA_ACCOUNTING_CODE . '%\')';
		$sql .= ' AND ((f.datef BETWEEN \'' . $startDate . '\' AND \'' . $endDate . '\') OR (fe.tourenddate BETWEEN \'' . $startDate . '\' AND \'' . $endDate . '\'))';
	}
	$sql .= ' GROUP BY f.fk_user_author';

	$result = $db->query($sql);
	if ($result) {
		$totalSales = [];
		while ($obj = $db->fetch_object($result)) {
			$totalSales[] = [$obj->author, $obj->total];
		}
		return $totalSales;
	} else {
		throw new Exception("Database query error " . dol_print_error($db));
	}
}

/**
 * Show graph based on conversion sales total data
 * @param $category
 * @param $dateRange
 * @param $categoryLabel
 * @param $displayType
 * @return UserActivityGraph
 * @throws Exception
 */
function showUserConversionTotalGraphs($category, $dateRange, $categoryLabel, $displayType)
{
	global $conf, $langs;
	$width = $conf->global->USER_ACTIVITY_GRAPH_WIDTH ?? '520';
	$height = $conf->global->USER_ACTIVITY_GRAPH_HEIGHT ?? '330';

	$data = getUserTotalSales($category, $dateRange, $categoryLabel);
	$dir = '';
	$dataType = ['bars'];
	$px = new UserActivityGraph();
	$mesg = $px->isGraphKo();
	$filenamenb = $dir . '/' . 'user-conversion-sale-total-graph.png';
	$fileurlnb = DOL_URL_ROOT . '/viewimage.php?modulepart=useractivitydashboard&amp;file=user-conversion-sale-total-graph.png';
	if (!$mesg) {
		$px->SetType($dataType);
		$px->SetData($data);
		$px->SetDataColor([[92, 155, 213]]);
		$px->setLineColor([[220, 120, 0]]);
		unset($data);
		$legend = [$langs->trans("ConversionSaleTotalLegend", getDisplayString($displayType))];
		$px->SetLegend($legend);
		$px->SetMaxValue($px->GetCeilMaxValue());
		$px->SetMinValue(min(0, $px->GetFloorMinValue()));
		$px->SetWidth($width);
		$px->SetHeight($height);
		$px->setShowGraphLine(true);
		$px->setShowCurrency(true);
		$px->setShowXGridLine('false');
		$px->SetShading(3);
		$px->SetHorizTickIncrement(1);
		$px->SetBgColorGrid([[255, 255, 255]]);
		$px->mode = 'depth';
		$px->SetTitle($langs->trans("ConversionSalesTotalEntered", $categoryLabel, getDisplayHeader($displayType, $dateRange)));
		$px->draw($filenamenb, $fileurlnb);
		return $px;
	}
}

/**
 * Get user cost per category
 * @param $category
 * @param $dateRange
 * @param $categoryLabel
 * @return array
 * @throws Exception
 */
function getUserTotalCost($category, $dateRange, $categoryLabel)
{
	global $db, $conf;
	$startDate = $dateRange['start_date'];
	$endDate = $dateRange['end_date'];
	$sql = 'SELECT sum(ab.debit - ab.credit) as total, u.login as author';
	$sql .= ' FROM ' . MAIN_DB_PREFIX . 'accounting_bookkeeping as ab';
	$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'categorie_user as cu ON cu.fk_user = ab.fk_user';
	$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'user as u ON u.rowid = ab.fk_user';
	$sql .= ' WHERE';
	if ($category) {
		if (getEnabledTACategory($categoryLabel)) {
			$sql .= ' ab.numero_compte LIKE \'' . $conf->global->ACCOUNTING_COST_TA_CODE . '%\'';
		} elseif (getEnabledMediaCategory($categoryLabel)) {
			$sql .= ' ab.numero_compte LIKE \'' . $conf->global->ACCOUNTING_COST_MEDIA_CODE . '%\'';
		} elseif (getEnabledEngineeringCategory($categoryLabel)) {
			$sql .= ' ab.numero_compte LIKE \'' . $conf->global->ACCOUNTING_COST_ENG_CODE . '%\'';
		} else {
			$sql .= ' ab.numero_compte LIKE \'' . $conf->global->ACCOUNTING_COST_CODE . '%\'';
		}
		$sql .= ' AND cu.fk_categorie = ' . $category;
	} else {
		$sql .= ' ab.numero_compte LIKE \'' . $conf->global->ACCOUNTING_COST_CODE . '%\'';
	}
	$sql .= ' AND (ab.doc_date BETWEEN \'' . $startDate . '\' AND \'' . $endDate . '\')';
	$sql .= ' GROUP BY ab.fk_user';

	$result = $db->query($sql);
	if ($result) {
		$costs = [];
		while ($obj = $db->fetch_object($result)) {
			$costs[$obj->author] = $obj->total;
		}
		return $costs;
	} else {
		throw new Exception("Database query error " . dol_print_error($db));
	}
}


/**
 * Return the calculated value profit of sale per Users
 * @param $category
 * @param $dateRange
 * @param $categoryLabel
 * @return array
 * @throws Exception
 */
function getUserProfitTotal($category, $dateRange, $categoryLabel)
{
	$profitSale = [];
	$userTotalSales = getUserTotalSales($category, $dateRange, $categoryLabel);
	$userCosts = getUserTotalCost($category, $dateRange, $categoryLabel);

	// Prepares arrays with names in the indexes
	$sales = [];
	foreach ($userTotalSales as $sale) {
		$sales[$sale[0]] = $sale[1];
	}

	$prices = [];
	foreach ($userCosts as $key => $cost) {
		$prices[$key] = -$cost;
	}

	// Merge array based on array indexes
	$conversionSalesCost = array_merge_recursive($sales, $prices);

	// Prepare data for generating graph
	foreach ($conversionSalesCost as $name => $value) {
		if (is_array($value)) {
			$profit = $value[0] + $value[1];
		} else {
			$profit = $value;
		}
		$profitSale[] = [$name, $profit];
	}

	return $profitSale;
}


/**
 * Show graph based on profit total data
 * @param $category
 * @param $dateRange
 * @param $categoryLabel
 * @param $displayType
 * @return UserActivityGraph
 * @throws Exception
 */
function showUserProfitTotalGraphs($category, $dateRange, $categoryLabel, $displayType)
{
	global $conf, $langs;
	$width = $conf->global->USER_ACTIVITY_GRAPH_WIDTH ?? '520';
	$height = $conf->global->USER_ACTIVITY_GRAPH_HEIGHT ?? '330';

	$data = getUserProfitTotal($category, $dateRange, $categoryLabel);
	$dir = '';
	$dataType = ['bars'];
	$px = new UserActivityGraph();
	$mesg = $px->isGraphKo();
	$filenamenb = $dir . '/' . 'user-profit-total-graph.png';
	$fileurlnb = DOL_URL_ROOT . '/viewimage.php?modulepart=useractivitydashboard&amp;file=user-profit-total-graph.png';
	if (!$mesg) {
		$px->SetType($dataType);
		$px->SetData($data);
		$px->SetDataColor([[92, 155, 213]]);
		$px->setLineColor([[220, 120, 0]]);
		unset($data);
		$legend = [$langs->trans("ProfitTotalLegend", getDisplayString($displayType))];
		$px->SetLegend($legend);
		$px->SetMaxValue($px->GetCeilMaxValue());
		$px->SetMinValue(min(0, $px->GetFloorMinValue()));
		$px->SetWidth($width);
		$px->SetHeight($height);
		$px->setShowGraphLine(true);
		$px->setShowCurrency(true);
		$px->setShowXGridLine('false');
		$px->SetShading(3);
		$px->SetHorizTickIncrement(1);
		$px->SetBgColorGrid([[255, 255, 255]]);
		$px->mode = 'depth';
		$px->SetTitle($langs->trans("ProfitTotalEntered", $categoryLabel, getDisplayHeader($displayType, $dateRange)));
		$px->draw($filenamenb, $fileurlnb);
		return $px;
	}
}

/**
 * Get Cost percent per user
 * @param $category
 * @param $dateRange
 * @param $categoryLabel
 * @return array
 * @throws Exception
 */
function getUserTotalCostPercent($category, $dateRange, $categoryLabel)
{
	$costPercents = [];
	$userTotalSales = getUserTotalSales($category, $dateRange, $categoryLabel);
	$userCosts = getUserTotalCost($category, $dateRange, $categoryLabel);

	// Prepares arrays with names in the indexes
	$sales = [];
	foreach ($userTotalSales as $sale) {
		$sales[$sale[0]] = $sale[1];
	}

	// Merge array based on array indexes
	$conversionSalesCost = array_merge_recursive($sales, $userCosts);

	// Prepare data for generating graph
	foreach ($conversionSalesCost as $name => $value) {
		if (is_array($value)) {
			$costPercent = ($value[1] / $value[0]) * 100;
		} else {
			$costPercent = 0;
		}
		$costPercents[] = [$name, $costPercent];
	}

	return $costPercents;
}

/**
 * Show graph based on conversion sales % data
 * @param $category
 * @param $dateRange
 * @param $categoryLabel
 * @param $displayType
 * @return UserActivityGraph
 * @throws Exception
 */
function showUserCostPercentGraphs($category, $dateRange, $categoryLabel, $displayType)
{
	global $conf, $langs;
	$width = $conf->global->USER_ACTIVITY_GRAPH_WIDTH ?? '520';
	$height = $conf->global->USER_ACTIVITY_GRAPH_HEIGHT ?? '330';

	$data = getUserTotalCostPercent($category, $dateRange, $categoryLabel);
	$dir = '';
	$dataType = ['bars'];
	$px = new UserActivityGraph();
	$mesg = $px->isGraphKo();
	$filenamenb = $dir . '/' . 'user-cost-sale-percent-graph.png';
	$fileurlnb = DOL_URL_ROOT . '/viewimage.php?modulepart=useractivitydashboard&amp;file=user-cost-sale-percent-graph.png';
	if (!$mesg) {
		$px->SetType($dataType);
		$px->SetData($data);
		$px->SetDataColor([[92, 155, 213]]);
		$px->setLineColor([[220, 120, 0]]);
		unset($data);
		$legend = [$langs->trans("CostSalePercentLegend")];
		$px->SetLegend($legend);
		$px->SetMaxValue($px->GetCeilMaxValue());
		$px->SetMinValue(min(0, $px->GetFloorMinValue()));
		$px->SetWidth($width);
		$px->SetHeight($height);
		$px->setShowPercent(1);
		$px->setShowGraphLine(true);
		$px->setShowXGridLine('false');
		$px->SetShading(3);
		$px->SetHorizTickIncrement(1);
		$px->SetBgColorGrid([[255, 255, 255]]);
		$px->mode = 'depth';
		$px->SetTitle($langs->trans("CostSalesPercentEntered", $categoryLabel, getDisplayHeader($displayType, $dateRange)));
		$px->draw($filenamenb, $fileurlnb);
		return $px;
	}
}


/**
 * Get Child categories with labels
 * @param $category
 * @param int $isParent
 * @return array
 */
function getChildCategories($category, $isParent = 0)
{
	dol_include_once('/categories/class/categorie.class.php');
	global $db;
	$categstatic = new Categorie($db);
	$tabcategories = $categstatic->get_full_arbo(Categorie::TYPE_USER, $category, 1);
	$childCategories = [];
	foreach ($tabcategories as $tabcategory) {
		if ($tabcategory['fk_parent'] != $isParent) {
			$childCategories[$tabcategory['id']] = $tabcategory['label'];
		}
	}
	return $childCategories;
}

/**
 * Get child category id's
 * @param $category
 * @return array
 */
function getChildCategoryIds($category)
{
	$categories = getChildCategories($category);
	$categoryIds = [];
	foreach ($categories as $key => $val) {
		$categoryIds[] = $key;
	}
	return $categoryIds;
}

/**
 * Get Category prospects data
 * @param $categories
 * @param $dateRange
 * @return array
 * @throws Exception
 */
function getCategoryProspects($categories, $dateRange)
{
	global $db, $conf;
	$startDate = $dateRange['start_date'];
	$endDate = $dateRange['end_date'];
	$prospects = [];
	$sql = 'SELECT count(s.rowid) as prospects, c.label as category FROM ' . MAIN_DB_PREFIX . 'societe as s';
	$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'categorie_user as cu ON cu.fk_user = s.fk_user_creat';
	$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'categorie as c ON cu.fk_categorie = c.rowid';
	$sql .= ' WHERE s.client IN (2,3)';
	if ($categories) {
		$sql .= ' AND cu.fk_categorie IN (' . $categories . ')';
	}
	$sql .= ' AND (s.datec BETWEEN \'' . $startDate . '\' AND \'' . $endDate . '\')';
	$sql .= ' GROUP BY cu.fk_categorie';
	$result = $db->query($sql);
	if ($result) {
		while($obj = $db->fetch_object($result)) {
			if ($conf->global->SECONDARY_CHART_LINE === $obj->category) {
				$prospects['secondary'][$obj->category] = $obj->prospects;
			} else {
				$prospects['data'][] = [$obj->category, $obj->prospects];
			}
		}
		return $prospects;
	} else {
		throw new Exception("Database query error " . dol_print_error($db));
	}
}

/**
 * Show graph based on category prospect data
 * @param $categories
 * @param $dateRange
 * @param $categoryLabel
 * @param $displayType
 * @return DolGraph
 * @throws Exception
 */
function showCategoryProspectGraphs($categories, $dateRange, $categoryLabel, $displayType)
{
	global $conf, $langs;
	$width = $conf->global->USER_ACTIVITY_GRAPH_WIDTH ?? '520';
	$height = $conf->global->USER_ACTIVITY_GRAPH_HEIGHT ?? '330';

	$allData = getCategoryProspects($categories, $dateRange);
	$data = $allData['data'];
	$secondaryVal = $allData['secondary'];
	$dir = '';
	$dataType = ['bars'];
	$px = new UserActivityGraph();
	$mesg = $px->isGraphKo();
	$filenamenb = $dir . '/' . 'category-prospect-graph.png';
	$fileurlnb = DOL_URL_ROOT . '/viewimage.php?modulepart=useractivitydashboard&amp;file=category-prospect-graph.png';
	if (!$mesg) {
		$px->SetType($dataType);
		$px->SetData($data);
		$px->SetDataColor([[92, 155, 213]]);
		$px->setLineColor([[220, 120, 0]]);
		unset($data);
		$legend = ['Prospects'];
		$px->SetLegend($legend);
		$px->SetMaxValue($px->GetCeilMaxValue());
		$px->SetMinValue(min(0, $px->GetFloorMinValue()));
		$px->SetWidth($width);
		$px->SetHeight($height);
		$px->setShowGraphLine(true);
		$px->setShowXGridLine('false');
		$px->SetYLabel($langs->trans('Prospects Entered'));
		$px->SetShading(3);
		$px->SetHorizTickIncrement(1);
		if (!empty($secondaryVal)) {
			$px->setSecondaryLineCount(implode(',', $secondaryVal));
			$px->setSecondaryLineColor([[170, 170, 170]]);
			$label = implode(',', array_keys($secondaryVal));
			$px->setSecondaryLineLabel($label);
		}

		$px->SetBgColorGrid([[255, 255, 255]]);
		$px->SetCssPrefix("useractivity");
		$px->mode = 'depth';
		$px->SetTitle($langs->trans("ProspectsEntered", $categoryLabel, getDisplayHeader($displayType, $dateRange)));
		$px->draw($filenamenb, $fileurlnb);
		return $px;
	}
}

/**
 * Get Category Quotations data
 * @param $categories
 * @param $dateRange
 * @return array
 * @throws Exception
 */
function getCategoryQuotations($categories, $dateRange)
{
	include_once DOL_DOCUMENT_ROOT . '/comm/propal/class/propal.class.php';
	global $db;
	$startDate = $dateRange['start_date'];
	$endDate = $dateRange['end_date'];
	$sql = 'SELECT count(p.ref) as quotations, c.label as category FROM ' . MAIN_DB_PREFIX . 'propal as p';
	$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'categorie_user as cu ON cu.fk_user = p.fk_user_author';
	$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'categorie as c ON cu.fk_categorie = c.rowid';
	$sql .= ' WHERE (p.fk_statut = ' . Propal::STATUS_BILLED . ' OR p.fk_statut = ' . Propal::STATUS_NOTSIGNED . ' OR p.fk_statut = ' . Propal::STATUS_SIGNED . ')';
	if ($categories) {
		$sql .= ' AND cu.fk_categorie IN (' . $categories . ')';
	}
	$sql .= ' AND (p.datep BETWEEN \'' . $startDate . '\' AND \'' . $endDate . '\')';
	$sql .= ' GROUP BY cu.fk_categorie';
	$result = $db->query($sql);
	if ($result) {
		$quotations = [];
		while ($obj = $db->fetch_object($result)) {
			$quotations[] = [$obj->category, $obj->quotations];
		}
		return $quotations;
	} else {
		throw new Exception("Database query error " . dol_print_error($db));
	}
}

/**
 * Show graph based on quotation data
 * @param $categories
 * @param $dateRange
 * @param $categoryLabel
 * @param $displayType
 * @return DolGraph
 * @throws Exception
 */
function showCategoryQuotationGraphs($categories, $dateRange, $categoryLabel, $displayType)
{
	global $conf, $langs;
	$width = $conf->global->USER_ACTIVITY_GRAPH_WIDTH ?? '520';
	$height = $conf->global->USER_ACTIVITY_GRAPH_HEIGHT ?? '330';

	$data = getCategoryQuotations($categories, $dateRange);
	$dir = '';
	$dataType = ['bars'];
	$px = new UserActivityGraph();
	$mesg = $px->isGraphKo();
	$filenamenb = $dir . '/' . 'category-quotation-graph.png';
	$fileurlnb = DOL_URL_ROOT . '/viewimage.php?modulepart=useractivitydashboard&amp;file=category-quotation-graph.png';
	if (!$mesg) {
		$px->SetType($dataType);
		$px->SetData($data);
		$px->SetDataColor([[92, 155, 213]]);
		$px->setLineColor([[220, 120, 0]]);
		unset($data);
		$legend = [$langs->trans("QuotationsLegend", getDisplayString($displayType))];
		$px->SetLegend($legend);
		$px->SetMaxValue($px->GetCeilMaxValue());
		$px->SetMinValue(min(0, $px->GetFloorMinValue()));
		$px->SetWidth($width);
		$px->SetHeight($height);
		$px->setShowGraphLine(true);
		$px->setShowXGridLine('false');
		$px->SetShading(3);
		$px->SetHorizTickIncrement(1);
		$px->SetBgColorGrid([[255, 255, 255]]);
		$px->mode = 'depth';
		$px->SetTitle($langs->trans("QuotationsEntered", $categoryLabel, getDisplayHeader($displayType, $dateRange)));
		$px->draw($filenamenb, $fileurlnb);
		return $px;
	}
}

/**
 * Return the calculated values for category sales
 * @param $categories
 * @param $dateRange
 * @param $categoryLabel
 * @return mixed
 * @throws Exception
 */
function getCategoryConversionSale($categories, $dateRange, $categoryLabel)
{
	dol_include_once(DOL_DOCUMENT_ROOT . '/compta/facture/class/facture.class.php');
	global $db, $conf;
	$startDate = $dateRange['start_date'];
	$endDate = $dateRange['end_date'];
	$sql = 'SELECT count(f.rowid) as invoices, c.label as category ';
	$sql .= 'FROM ' . MAIN_DB_PREFIX . 'facture as f ';
	$sql .= 'LEFT JOIN ' . MAIN_DB_PREFIX . 'categorie_user as cu ON cu.fk_user = f.fk_user_author ';
	$sql .= 'LEFT JOIN ' . MAIN_DB_PREFIX . 'categorie as c ON cu.fk_categorie = c.rowid ';
	$sql .= 'LEFT JOIN ' . MAIN_DB_PREFIX . 'facture_extrafields as fe ON fe.fk_object = f.rowid ';
	$sql .= 'LEFT JOIN ' . MAIN_DB_PREFIX . 'element_element as el ON el.fk_target = f.rowid ';
	$sql .= ' WHERE f.ref LIKE \'' . $conf->global->INVOICE_PREFIX . '%\'';
	$sql .= ' AND (f.fk_statut = \'' . Facture::STATUS_CLOSED . '\' OR f.fk_statut = \'' . Facture::STATUS_VALIDATED . '\')';
	$sql .= ' AND el.sourcetype = \'commande\'';
	$sql .= ' AND el.targettype = \'facture\'';
	if ($categories) {
		$sql .= ' AND cu.fk_categorie IN (' . $categories . ')';
	}
	if (getEnabledTACategory($categoryLabel)) {
		$sql .= ' AND (fe.tourenddate BETWEEN \'' . $startDate . '\' AND \'' . $endDate . '\')';
	} else {
		$sql .= ' AND (f.datef BETWEEN \'' . $startDate . '\' AND \'' . $endDate . '\')';
	}
	$sql .= ' GROUP BY cu.fk_categorie';

	$result = $db->query($sql);
	if ($result) {
		$invoices = [];
		while ($obj = $db->fetch_object($result)) {
			$invoices[] = [$obj->category, $obj->invoices];
		}
		return $invoices;
	} else {
		throw new Exception("Database query error " . dol_print_error($db));
	}
}

/**
 * Show graph based on conversion sales data
 * @param $categories
 * @param $dateRange
 * @param $categoryLabel
 * @param $displayType
 * @return UserActivityGraph
 * @throws Exception
 */
function showCategoryConversionGraphs($categories, $dateRange, $categoryLabel, $displayType)
{
	global $conf, $langs;
	$width = $conf->global->USER_ACTIVITY_GRAPH_WIDTH ?? '520';
	$height = $conf->global->USER_ACTIVITY_GRAPH_HEIGHT ?? '330';

	$data = getCategoryConversionSale($categories, $dateRange, $categoryLabel);
	$dir = '';
	$dataType = ['bars'];
	$px = new UserActivityGraph();
	$mesg = $px->isGraphKo();
	$filenamenb = $dir . '/' . 'category-conversion-sale-graph.png';
	$fileurlnb = DOL_URL_ROOT . '/viewimage.php?modulepart=useractivitydashboard&amp;file=category-conversion-sale-graph.png';
	if (!$mesg) {
		$px->SetType($dataType);
		$px->SetData($data);
		$px->SetDataColor([[92, 155, 213]]);
		$px->setLineColor([[220, 120, 0]]);
		unset($data);
		$legend = [$langs->trans("ConversionSaleLegend", getDisplayString($displayType))];
		$px->SetLegend($legend);
		$px->SetMaxValue($px->GetCeilMaxValue());
		$px->SetMinValue(min(0, $px->GetFloorMinValue()));
		$px->SetWidth($width);
		$px->SetHeight($height);
		$px->setShowGraphLine(true);
		$px->setShowXGridLine('false');
		$px->SetShading(3);
		$px->SetHorizTickIncrement(1);
		$px->SetBgColorGrid([[255, 255, 255]]);
		$px->mode = 'depth';
		$px->SetTitle($langs->trans("ConversionSalesEntered", $categoryLabel, getDisplayHeader($displayType, $dateRange)));
		$px->draw($filenamenb, $fileurlnb);
		return $px;
	}
}


/**
 * Return the calculated value percentage of conversion sale per Categories
 * @param $categories
 * @param $dateRange
 * @param $categoryLabel
 * @return array
 * @throws Exception
 */
function getCategoryConversionSalePercent($categories, $dateRange, $categoryLabel)
{
	$percentSale = [];
	$categoryConversionSales = getCategoryConversionSale($categories, $dateRange, $categoryLabel);
	$categoryQuotations = getCategoryQuotations($categories, $dateRange);

	// Prepares arrays with names in the indexes
	$quotations = [];
	foreach ($categoryQuotations as $quotation) {
		$quotations[$quotation[0]] = $quotation[1];
	}

	$conversionSales = [];
	foreach ($categoryConversionSales as $conversionSale) {
		$conversionSales[$conversionSale[0]] = $conversionSale[1];
	}

	// Merge array based on array indexes
	$conversionSaleQuotation = array_merge_recursive($conversionSales, $quotations);

	// Prepare data for generating graph
	foreach ($conversionSaleQuotation as $name => $conversionSale) {
		if (is_array($conversionSale)) {
			$percentResult = ($conversionSale[0] / $conversionSale[1]) * 100;
		} else {
			$percentResult = 0;
		}
		$percentSale[] = [$name, $percentResult];
	}

	return $percentSale;
}

/**
 * Show graph based on conversion sales % data
 * @param $categories
 * @param $dateRange
 * @param $categoryLabel
 * @param $displayType
 * @return UserActivityGraph
 * @throws Exception
 */
function showCategoryConversionPercentGraphs($categories, $dateRange, $categoryLabel, $displayType)
{
	global $conf, $langs;
	$width = $conf->global->USER_ACTIVITY_GRAPH_WIDTH ?? '520';
	$height = $conf->global->USER_ACTIVITY_GRAPH_HEIGHT ?? '330';

	$data = getCategoryConversionSalePercent($categories, $dateRange, $categoryLabel);
	$dir = '';
	$dataType = ['bars'];
	$px = new UserActivityGraph();
	$mesg = $px->isGraphKo();
	$filenamenb = $dir . '/' . 'category-conversion-sale-percent-graph.png';
	$fileurlnb = DOL_URL_ROOT . '/viewimage.php?modulepart=useractivitydashboard&amp;file=category-conversion-sale-percent-graph.png';
	if (!$mesg) {
		$px->SetType($dataType);
		$px->SetData($data);
		$px->SetDataColor([[92, 155, 213]]);
		$px->setLineColor([[220, 120, 0]]);
		unset($data);
		$legend = [$langs->trans("ConversionSalePercentLegend")];
		$px->SetLegend($legend);
		$px->SetMaxValue(100);
		$px->SetMinValue(min(0, $px->GetFloorMinValue()));
		$px->SetWidth($width);
		$px->SetHeight($height);
		$px->setShowPercent(1);
		$px->setShowGraphLine(true);
		$px->setShowXGridLine('false');
		$px->SetShading(3);
		$px->SetHorizTickIncrement(1);
		$px->SetBgColorGrid([[255, 255, 255]]);
		$px->mode = 'depth';
		$px->SetTitle($langs->trans("ConversionSalesPercentEntered", $categoryLabel, getDisplayHeader($displayType, $dateRange)));
		$px->draw($filenamenb, $fileurlnb);
		return $px;
	}
}

/**
 * Return the category calculated total price of conversion sale based on category
 * @param $categories
 * @param $dateRange
 * @param $categoryLabel
 * @return array
 * @throws Exception
 */
function getCategoryTotalSales($categories, $dateRange, $categoryLabel)
{
	dol_include_once(DOL_DOCUMENT_ROOT . '/compta/facture/class/facture.class.php');
	global $db, $conf, $langs, $user;
	$startDate = $dateRange['start_date'];
	$endDate = $dateRange['end_date'];
	$sql = 'SELECT sum(fd.total_ht) as total, c.label as category';
	$sql .= ' FROM ' . MAIN_DB_PREFIX . 'facturedet as fd';
	$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'facture as f ON f.rowid = fd.fk_facture';
	$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'facture_extrafields as fe ON fe.fk_object = f.rowid';
	$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'categorie_user as cu ON cu.fk_user = f.fk_user_author';
	$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'categorie as c ON cu.fk_categorie = c.rowid';
	$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'accounting_account as aa ON aa.rowid = fd.fk_code_ventilation';
	$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'element_element as el ON el.fk_target = f.rowid';
	$sql .= ' WHERE (f.fk_statut = \'' . Facture::STATUS_CLOSED . '\' OR f.fk_statut = \'' . Facture::STATUS_VALIDATED . '\')';
	$sql .= ' AND el.sourcetype = \'commande\' AND el.targettype = \'facture\'';
	if ($categories) {
		$sql .= ' AND cu.fk_categorie IN (' . $categories . ')';
		if (getEnabledTACategory($categoryLabel)) {
			$sql .= ' AND aa.account_number LIKE \'' . $conf->global->TA_ACCOUNTING_CODE . '%\'';
			$sql .= ' AND (fe.tourenddate BETWEEN \'' . $startDate . '\' AND \'' . $endDate . '\')';
		} else {
			$sql .= ' AND (aa.account_number LIKE \'' . $conf->global->MEDIA_ACCOUNTING_CODE . '%\' OR aa.account_number LIKE \'' . $conf->global->ENGINEERING_ACCOUNTING_CODE . '%\')';
			$sql .= ' AND (f.datef BETWEEN \'' . $startDate . '\' AND \'' . $endDate . '\')';
		}
	} elseif ($user->rights->useractivitydashboard->view_all) {
		$sql .= ' AND (aa.account_number LIKE \'' . $conf->global->MEDIA_ACCOUNTING_CODE . '%\' OR aa.account_number LIKE \'' . $conf->global->ENGINEERING_ACCOUNTING_CODE . '%\' OR aa.account_number LIKE \'' . $conf->global->TA_ACCOUNTING_CODE . '%\')';
		$sql .= ' AND ((f.datef BETWEEN \'' . $startDate . '\' AND \'' . $endDate . '\') OR (fe.tourenddate BETWEEN \'' . $startDate . '\' AND \'' . $endDate . '\'))';
	}
	$sql .= ' GROUP BY cu.fk_categorie';

	$result = $db->query($sql);
	if ($result) {
		$totalSales = [];
		while ($obj = $db->fetch_object($result)) {
			$totalSales[] = [$obj->category, $obj->total];
		}
		$db->free($sql);
		return $totalSales;
	} else {
		throw new Exception("Database query error " . dol_print_error($db));
	}
}

/**
 * Show graph based on conversion sales total data
 * @param $categories
 * @param $dateRange
 * @param $categoryLabel
 * @param $displayType
 * @return UserActivityGraph
 * @throws Exception
 */
function showCategoryConversionTotalGraphs($categories, $dateRange, $categoryLabel, $displayType)
{
	global $conf, $langs;
	$width = $conf->global->USER_ACTIVITY_GRAPH_WIDTH ?? '520';
	$height = $conf->global->USER_ACTIVITY_GRAPH_HEIGHT ?? '330';

	$data = getCategoryTotalSales($categories, $dateRange, $categoryLabel);
	$dir = '';
	$dataType = ['bars'];
	$px = new UserActivityGraph();
	$mesg = $px->isGraphKo();
	$filenamenb = $dir . '/' . 'category-conversion-sale-total-graph.png';
	$fileurlnb = DOL_URL_ROOT . '/viewimage.php?modulepart=useractivitydashboard&amp;file=category-conversion-sale-total-graph.png';
	if (!$mesg) {
		$px->SetType($dataType);
		$px->SetData($data);
		$px->SetDataColor([[92, 155, 213]]);
		$px->setLineColor([[220, 120, 0]]);
		unset($data);
		$legend = [$langs->trans("ConversionSaleTotalLegend", getDisplayString($displayType))];
		$px->SetLegend($legend);
		$px->SetMaxValue($px->GetCeilMaxValue());
		$px->SetMinValue(min(0, $px->GetFloorMinValue()));
		$px->SetWidth($width);
		$px->SetHeight($height);
		$px->setShowGraphLine(true);
		$px->setShowCurrency(true);
		$px->setShowXGridLine('false');
		$px->SetShading(3);
		$px->SetHorizTickIncrement(1);
		$px->SetBgColorGrid([[255, 255, 255]]);
		$px->mode = 'depth';
		$px->SetTitle($langs->trans("ConversionSalesTotalEntered", $categoryLabel, getDisplayHeader($displayType, $dateRange)));
		$px->draw($filenamenb, $fileurlnb);
		return $px;
	}
}

/**
 * Get total cost per category
 * @param $categories
 * @param $dateRange
 * @param $categoryLabel
 * @return array
 * @throws Exception
 */
function getCategoryTotalCost($categories, $dateRange, $categoryLabel)
{
	dol_include_once(DOL_DOCUMENT_ROOT . '/compta/facture/class/facture.class.php');
	global $db, $conf;
	$startDate = $dateRange['start_date'];
	$endDate = $dateRange['end_date'];
	$sql = 'SELECT sum(ab.debit - ab.credit) as total, c.label as category';
	$sql .= ' FROM ' . MAIN_DB_PREFIX . 'accounting_bookkeeping as ab';
	$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'categorie_user as cu ON cu.fk_user = ab.fk_user';
	$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'categorie as c ON cu.fk_categorie = c.rowid';
	$sql .= ' WHERE';

	if ($categories) {
		if (getEnabledTACategory($categoryLabel)) {
			$sql .= ' ab.numero_compte LIKE \'' . $conf->global->ACCOUNTING_COST_TA_CODE . '%\'';
		} elseif (getEnabledMediaCategory($categoryLabel)) {
			$sql .= ' ab.numero_compte LIKE \'' . $conf->global->ACCOUNTING_COST_MEDIA_CODE . '%\'';
		} elseif (getEnabledEngineeringCategory($categoryLabel)) {
			$sql .= 'ab.numero_compte LIKE \'' . $conf->global->ACCOUNTING_COST_ENG_CODE . '%\'';
		} else {
			$sql .= ' ab.numero_compte LIKE \'' . $conf->global->ACCOUNTING_COST_CODE . '%\'';
		}
		$sql .= ' AND cu.fk_categorie IN (' . $categories . ')';
	} else {
		$sql .= ' ab.numero_compte LIKE \'' . $conf->global->ACCOUNTING_COST_CODE . '%\'';
	}
	$sql .= ' AND (ab.doc_date BETWEEN \'' . $startDate . '\' AND \'' . $endDate . '\')';
	$sql .= ' GROUP BY cu.fk_categorie';

	$result = $db->query($sql);
	if ($result) {
		$costs = [];
		while ($obj = $db->fetch_object($result)) {
			$costs[$obj->category] = $obj->total;
		}
		return $costs;
	} else {
		throw new Exception("Database query error " . dol_print_error($db));
	}
}

/**
 * Return the calculated value profit of sale per Category
 * @param $categories
 * @param $dateRange
 * @param $categoryLabel
 * @return array
 * @throws Exception
 */
function getCategoryProfitTotal($categories, $dateRange, $categoryLabel)
{
	$profitSale = [];
	$categoryTotalSales = getCategoryTotalSales($categories, $dateRange, $categoryLabel);
	$categoryCosts = getCategoryTotalCost($categories, $dateRange, $categoryLabel);

	// Prepares arrays with names in the indexes
	$sales = [];
	foreach ($categoryTotalSales as $sale) {
		$sales[$sale[0]] = $sale[1];
	}

	$prices = [];
	foreach ($categoryCosts as $key => $cost) {
		$prices[$key] = -$cost;
	}

	// Merge array based on array indexes
	$conversionSalesCost = array_merge_recursive($sales, $prices);

	// Prepare data for generating graph
	foreach ($conversionSalesCost as $name => $value) {
		if (is_array($value)) {
			$profit = $value[0] + $value[1];
		} else {
			$profit = $value;
		}
		$profitSale[] = [$name, $profit];
	}

	return $profitSale;
}

/**
 * Show graph based on profit total data
 * @param $categories
 * @param $dateRange
 * @param $categoryLabel
 * @param $displayType
 * @return UserActivityGraph
 * @throws Exception
 */
function showCategoryProfitTotalGraphs($categories, $dateRange, $categoryLabel, $displayType)
{
	global $conf, $langs;
	$width = $conf->global->USER_ACTIVITY_GRAPH_WIDTH ?? '520';
	$height = $conf->global->USER_ACTIVITY_GRAPH_HEIGHT ?? '330';

	$data = getCategoryProfitTotal($categories, $dateRange, $categoryLabel);
	$dir = '';
	$dataType = ['bars'];
	$px = new UserActivityGraph();
	$mesg = $px->isGraphKo();
	$filenamenb = $dir . '/' . 'category-profit-total-graph.png';
	$fileurlnb = DOL_URL_ROOT . '/viewimage.php?modulepart=useractivitydashboard&amp;file=category-profit-total-graph.png';
	if (!$mesg) {
		$px->SetType($dataType);
		$px->SetData($data);
		$px->SetDataColor([[92, 155, 213]]);
		$px->setLineColor([[220, 120, 0]]);
		unset($data);
		$legend = [$langs->trans("ProfitTotalLegend", getDisplayString($displayType))];
		$px->SetLegend($legend);
		$px->SetMaxValue($px->GetCeilMaxValue());
		$px->SetMinValue(min(0, $px->GetFloorMinValue()));
		$px->SetWidth($width);
		$px->SetHeight($height);
		$px->setShowGraphLine(true);
		$px->setShowCurrency(true);
		$px->setShowXGridLine('false');
		$px->SetShading(3);
		$px->SetHorizTickIncrement(1);
		$px->SetBgColorGrid([[255, 255, 255]]);
		$px->mode = 'depth';
		$px->SetTitle($langs->trans("ProfitTotalEntered", $categoryLabel, getDisplayHeader($displayType, $dateRange)));
		$px->draw($filenamenb, $fileurlnb);
		return $px;
	}
}

/**
 * Get Cost percent per user
 * @param $categories
 * @param $dateRange
 * @param $categoryLabel
 * @return array
 * @throws Exception
 */
function getCategoryTotalCostPercent($categories, $dateRange, $categoryLabel)
{
	$costPercents = [];
	$categoryTotalSales = getCategoryTotalSales($categories, $dateRange, $categoryLabel);
	$categoryCosts = getCategoryTotalCost($categories, $dateRange, $categoryLabel);

	// Prepares arrays with names in the indexes
	$sales = [];
	foreach ($categoryTotalSales as $sale) {
		$sales[$sale[0]] = $sale[1];
	}

	// Merge array based on array indexes
	$conversionSalesCost = array_merge_recursive($sales, $categoryCosts);

	// Prepare data for generating graph
	foreach ($conversionSalesCost as $name => $value) {
		if (is_array($value)) {
			$costPercent = ($value[1] / $value[0]) * 100;
		} else {
			$costPercent = 0;
		}
		$costPercents[] = [$name, $costPercent];
	}

	return $costPercents;
}

/**
 * Show graph based on conversion sales % data
 * @param $categories
 * @param $dateRange
 * @param $categoryLabel
 * @param $displayType
 * @return UserActivityGraph
 * @throws Exception
 */
function showCategoryCostPercentGraphs($categories, $dateRange, $categoryLabel, $displayType)
{
	global $conf, $langs;
	$width = $conf->global->USER_ACTIVITY_GRAPH_WIDTH ?? '520';
	$height = $conf->global->USER_ACTIVITY_GRAPH_HEIGHT ?? '330';

	$data = getCategoryTotalCostPercent($categories, $dateRange, $categoryLabel);
	$dir = '';
	$dataType = ['bars'];
	$px = new UserActivityGraph();
	$mesg = $px->isGraphKo();
	$filenamenb = $dir . '/' . 'category-cost-sale-percent-graph.png';
	$fileurlnb = DOL_URL_ROOT . '/viewimage.php?modulepart=useractivitydashboard&amp;file=category-cost-sale-percent-graph.png';
	if (!$mesg) {
		$px->SetType($dataType);
		$px->SetData($data);
		$px->SetDataColor([[92, 155, 213]]);
		$px->setLineColor([[220, 120, 0]]);
		unset($data);
		$legend = [$langs->trans("CostSalePercentLegend")];
		$px->SetLegend($legend);
		$px->SetMaxValue($px->GetCeilMaxValue());
		$px->SetMinValue(min(0, $px->GetFloorMinValue()));
		$px->SetWidth($width);
		$px->SetHeight($height);
		$px->setShowPercent(1);
		$px->setShowGraphLine(true);
		$px->setShowXGridLine('false');
		$px->SetShading(3);
		$px->SetHorizTickIncrement(1);
		$px->SetBgColorGrid([[255, 255, 255]]);
		$px->mode = 'depth';
		$px->SetTitle($langs->trans("CostSalesPercentEntered", $categoryLabel, getDisplayHeader($displayType, $dateRange)));
		$px->draw($filenamenb, $fileurlnb);
		return $px;
	}
}
