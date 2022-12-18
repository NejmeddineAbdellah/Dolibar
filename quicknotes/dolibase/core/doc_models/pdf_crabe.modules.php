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

dolibase_include_once('core/doc_models/pdf_azur.modules.php');

/**
 * pdf_crabe class
 *
 * Class to generate PDF with template Crabe
 */

class pdf_crabe extends pdf_azur
{
	/**
	 * Constructor
	 *
	 * @param      DoliDB      $db      Database handler
	 */
	public function __construct($db)
	{
		parent::__construct($db);

		global $langs;

		$this->name        = 'crabe';
		$this->description = $langs->trans('DocModelCrabeDescription');
	}

	/**
	 * Function to write pdf content
	 *
	 * @param       TCPDF       $pdf                PDF object
	 * @param       Object      $object             Object to generate
	 * @param       Translate   $outputlangs        Lang output object
	 * @param       int         $default_font_size  Default font size
	 * @param       int         $tab_top            Table top position
	 * @param       int         $heightforinfotot   Info height
	 * @param       int         $heightforfreetext  Free text height
	 * @param       int         $heightforfooter    Footer height
	 */
	protected function write_content(&$pdf, $object, $outputlangs, $default_font_size, $tab_top, $heightforinfotot, $heightforfreetext, $heightforfooter)
	{
		global $conf, $langs;

		// Columns background color
		$cols_width = $this->page_largeur - $this->marge_gauche - $this->marge_droite;
		$cols_height = 6; // TODO: retrieve maximum columns height to avoid using a fixed value
		//$conf->global->MAIN_PDF_TITLE_BACKGROUND_COLOR = '230,230,230';
		if (! empty($conf->global->MAIN_PDF_TITLE_BACKGROUND_COLOR)) $pdf->Rect($this->marge_gauche, $tab_top, $cols_width, $cols_height, 'F', null, explode(',',$conf->global->MAIN_PDF_TITLE_BACKGROUND_COLOR));

		// Print lines
		if (isset($object->doc_lines))
		{
			// header
			$cols_count = count($object->doc_lines);
			$col_width = $cols_width / $cols_count;
			$curY = $tab_top + 1;// + 7;
			$curX = $this->marge_gauche + 1;
			$nexY = $curY;
			$tab_height = $this->page_hauteur - $tab_top - $heightforinfotot - $heightforfreetext - $heightforfooter;
			$i = 0;

			foreach ($object->doc_lines as $line)
			{
				$add_separator = ($i == $cols_count - 1 ? 0 : 1);
				$col_name = $langs->trans($line['name']);
				$curX = $this->print_column($pdf, $col_name, $curX, $curY, $nexY, $col_width, $tab_top, $tab_height, $outputlangs, $default_font_size, $add_separator);
				$i++;
			}

			$pdf->line($this->marge_gauche, $nexY+1, $this->page_largeur - $this->marge_droite, $nexY+1);

			// values
			$curX = $this->marge_gauche + 1;
			$curY = $nexY + 3;
			foreach ($object->doc_lines as $line)
			{
				$curX = $this->print_column($pdf, $line['value'], $curX, $curY, $nexY, $col_width, $tab_top, $tab_height, $outputlangs, $default_font_size);
			}
		}
	}

	/**
	 * Function to print table line
	 *
	 * @param       TCPDF       $pdf                PDF object
	 * @param       string      $text               Column text
	 * @param       int         $curX               Current X position
	 * @param       int         $curY               Current Y position
	 * @param       int         $nexY               Next Y position
	 * @param       int         $col_width          Column width
	 * @param       int         $tab_top            Table top position
	 * @param       int         $tab_height         Table height
	 * @param       Translate   $outputlangs        Lang output object
	 * @param       int         $default_font_size  Default font size
	 * @param       int         $add_separator      Should add a row separator or not
	 * @return      int                             next X position
	 */
	protected function print_column(&$pdf, $text, $curX, $curY, &$nexY, $col_width, $tab_top, $tab_height, $outputlangs, $default_font_size, $add_separator=0)
	{
		$pdf->SetFont('', '', $default_font_size - 1); // Into loop to work with multipage
		$pdf->SetTextColor(0, 0, 0);

		//$pdf->setTopMargin($this->marge_haute);
		//$pdf->setPageOrientation('', 1, 0); // The only function to edit the bottom margin of current page to set it.

		// Column
		$pdf->writeHTMLCell($col_width-1, 3, $curX, $curY, $outputlangs->convToOutputCharset($text), 0, 1, false, true, 'L',true);
		$nextY = $pdf->GetY();
		if ($nextY > $nexY) $nexY = $nextY;

		$nextX = $curX + $col_width;

		// Add line
		if ($add_separator)
		{
			$pageposafter = $pdf->getPage();
			$pdf->setPage($pageposafter);
			//$pdf->SetLineStyle(array('dash'=>'1,1','color'=>array(80,80,80)));
			//$pdf->SetDrawColor(190,190,200);
			$pdf->SetLineStyle(array('dash' => 0));
			$pdf->line($nextX-1, $tab_top, $nextX-1, $tab_top + $tab_height);
			//$pdf->SetLineStyle(array('dash'=>0));
		}

		return $nextX;
	}

	/**
	 * Show table for lines
	 *
	 * @param       TCPDF       $pdf            Object PDF
	 * @param       string      $tab_top        Top position of table
	 * @param       string      $tab_height     Height of table (rectangle)
	 * @param       int         $nexY           Y (not used)
	 * @param       Translate   $outputlangs    Langs object
	 * @param       int         $hidetop        1=Hide top bar of array and title, 0=Hide nothing, -1=Hide only title
	 * @param       int         $hidebottom     Hide bottom bar of array
	 * @param       string      $currency       Currency code
	 * @return      void
	 */
	public function _tableau(&$pdf, $tab_top, $tab_height, $nexY, $outputlangs, $hidetop=0, $hidebottom=0, $currency='')
	{
		// Force to disable hidetop and hidebottom
		$hidebottom = 0;
		if ($hidetop) $hidetop = -1;

		$default_font_size = pdf_getPDFFontSize($outputlangs);

		$pdf->SetDrawColor(128, 128, 128);
		$pdf->SetFont('', '', $default_font_size - 1);

		// Output Rect
		$this->printRect($pdf,$this->marge_gauche, $tab_top, $this->page_largeur-$this->marge_gauche-$this->marge_droite, $tab_height, $hidetop, $hidebottom);
	}
}
