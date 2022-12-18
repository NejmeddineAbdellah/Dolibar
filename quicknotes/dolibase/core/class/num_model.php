<?php
/**
 * Dolibase
 * 
 * Open source framework for Dolibarr ERP/CRM
 *
 * Copyright (c) 2018 - 2019
 *
 *
 * @package     Dolibase
 * @author      AXeL
 * @copyright   Copyright (c) 2018 - 2019, AXeL-dev
 * @license     MIT
 * @link        https://github.com/AXeL-dev/dolibase
 * 
 */

/**
 * NumModel class
 */

abstract class NumModel
{
	public $error = '';

	/**
	 * Return if a model can be used or not
	 *
	 * @return     boolean     true if model can be used
	 */
	public function isEnabled()
	{
		return true;
	}

	/**
	 * Return the default description of the numbering model
	 *
	 * @return     string      Text description
	 */
	public function info()
	{
		global $langs;

		return $langs->trans('NoDescription');
	}

	/**
	 * Return an example of numbering
	 *
	 * @return     string      Example
	 */
	public function getExample()
	{
		global $langs;

		return $langs->trans('NoExample');
	}

	/**
	 * Check if the numbers already existing in the database doesn't have conflicts with this numbering model
	 *
	 * @return     boolean     false if conflict, true if ok
	 */
	public function canBeActivated()
	{
		return true;
	}

	/**
	 * Return next numbering value
	 *
	 * @return     string      value
	 */
	public function getNextValue()
	{
		global $langs;

		return $langs->trans('NotAvailable');
	}

	/**
	 * Return numbering model version
	 *
	 * @return     string      value
	 */
	public function getVersion()
	{
		global $langs;
		$langs->load('admin');

		if ($this->version == 'development') return $langs->trans('VersionDevelopment');
		if ($this->version == 'experimental') return $langs->trans('VersionExperimental');
		if ($this->version == 'dolibarr') return DOL_VERSION;
		if ($this->version) return $this->version;
		return $langs->trans('NotAvailable');
	}
}
