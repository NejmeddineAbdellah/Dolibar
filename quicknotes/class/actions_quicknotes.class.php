<?php

/**
 * ActionsQuickNotes class (hooks manager)
 */

class ActionsQuickNotes
{
	/**
	 * Overloading the printTopRightMenu function
	 *
	 * @param   array()         $parameters     Hook metadatas (context, etc...)
	 * @param   CommonObject    &$object        The object to process (an invoice if you are in invoice module, a propale in propale's module, etc...)
	 * @param   string          &$action        Current action (if set). Generally create or edit or null
	 * @param   HookManager     $hookmanager    Hook manager propagated to allow calling another hook
	 * @return  int                             < 0 on error, 0 on success, 1 to replace standard code
	 */
	public function printTopRightMenu($parameters, &$object, &$action, $hookmanager)
	{
		global $langs;

		$error = 0; // Error counter

		if (in_array('toprightmenu', explode(':', $parameters['context'])))
		{
			// Notes shortcut
			$langs->load('quicknotes@quicknotes');
			$text = '<a href="#" id="quick-notes-button">';
			$dolibarr_version = explode('.', DOL_VERSION);
			if ((int)$dolibarr_version[0] >= 6) {
				$text.= '<span class="fa fa-sticky-note atoplogin"></span>';
			}
			else {
				$text.= img_picto($langs->trans("Notes"), 'object_quicknotes.png@quicknotes');
			}
			$text.= '</a>';
			$this->resprints = @Form::textwithtooltip('',$langs->trans("Notes"),2,1,$text,'login_block_elem',2);
		}

		if (! $error)
		{
			return 0; // or return 1 to replace standard code
		}
		else
		{
			$this->errors[] = 'Could not add quick-notes shortcut to the top right menu';
			return -1;
		}
	}

	/**
	 * Overloading the printCommonFooter function
	 *
	 * @param   array()         $parameters     Hook metadatas (context, etc...)
	 * @param   CommonObject    &$object        The object to process (an invoice if you are in invoice module, a propale in propale's module, etc...)
	 * @param   string          &$action        Current action (if set). Generally create or edit or null
	 * @param   HookManager     $hookmanager    Hook manager propagated to allow calling another hook
	 * @return  int                             < 0 on error, 0 on success, 1 to replace standard code
	 */
	public function printCommonFooter($parameters, &$obj, &$action, $hookmanager)
	{
		global $langs, $user;

		$error = 0; // Error counter
		$context = explode(':', $parameters['context']);

		if (in_array('main', $context) || in_array('login', $context))
		{
			dol_include_once('quicknotes/autoload.php');
			dolibase_include_once('core/class/query_builder.php');

			$query = QueryBuilder::getInstance()->select('notes')->from('quick_notes')->where('fk_user = '.$user->id)->orderBy('rowid', 'DESC')->limit(1);
			$count = $query->count();
			$notes = $count > 0 ? $query->result()[0]->notes : '';

			$langs->load('quicknotes@quicknotes');
			echo '<div id="quick-notes-dialog" title="'.$langs->trans('Notes').'" style="display: none;">';
			echo '<textarea id="quick-notes-textarea" class="flat centpercent" rows="7">'.$notes.'</textarea>';
			echo '</div>';
		}

		if (! $error)
		{
			return 0; // or return 1 to replace standard code
		}
		else
		{
			$this->errors[] = 'Could not render quick-notes dialog';
			return -1;
		}
	}
}
