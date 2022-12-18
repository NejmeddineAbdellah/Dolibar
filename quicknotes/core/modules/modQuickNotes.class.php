<?php

// Load Dolibase
dol_include_once('quicknotes/autoload.php');

// Load Dolibase Module class
dolibase_include_once('core/class/module.php');

/**
 *	Class to describe and enable module
 */
class modQuickNotes extends DolibaseModule
{
	/**
	 * Function called after module configuration.
	 * 
	 */
	public function loadSettings()
	{
		// Update picto for Dolibarr 12++
		if (function_exists('version_compare') && version_compare(DOL_VERSION, '12.0.0') >= 0) {
			$this->picto = "quicknotes_128.png@quicknotes";
		}

		$this->addJsFile('quicknotes.js.php');
		$this->enableHooks(array(
			'toprightmenu',
			'main',
			'login'
		));
	}
}
