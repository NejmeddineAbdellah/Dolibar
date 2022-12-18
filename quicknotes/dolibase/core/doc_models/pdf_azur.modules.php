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

dolibase_include_once('core/class/doc_model.php');
require_once DOL_DOCUMENT_ROOT . '/core/lib/company.lib.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/functions2.lib.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/pdf.lib.php';

/**
 * pdf_azur class
 *
 * Class to generate PDF with template Azur
 */

class pdf_azur extends DocModel
{
	public $db;
	public $name;
	public $description;
	public $type;

	public $phpmin = array(4, 3, 0); // Minimum version of PHP required by model
	public $version = 'dolibarr';

	public $page_largeur;
	public $page_hauteur;
	public $format;
	public $marge_gauche;
	public $marge_droite;
	public $marge_haute;
	public $marge_basse;

	public $emetteur; // Society object

	protected $watermark_text;
	protected $modulepart;
	protected $const_prefix;

	/**
	 * Constructor
	 *
	 * @param      DoliDB      $db      Database handler
	 */
	public function __construct($db)
	{
		global $conf, $langs, $mysoc;

		$langs->load('main');
		$langs->load('companies');

		$this->db          = $db;
		$this->name        = 'azur';
		$this->description = $langs->trans('DocModelAzurDescription');

		// Page dimensions for A4 format
		$this->type         = 'pdf';
		$formatarray        = pdf_getFormat();
		$this->page_largeur = $formatarray['width'];
		$this->page_hauteur = $formatarray['height'];
		$this->format       = array($this->page_largeur,$this->page_hauteur);
		$this->marge_gauche = isset($conf->global->MAIN_PDF_MARGIN_LEFT)?$conf->global->MAIN_PDF_MARGIN_LEFT:10;
		$this->marge_droite = isset($conf->global->MAIN_PDF_MARGIN_RIGHT)?$conf->global->MAIN_PDF_MARGIN_RIGHT:10;
		$this->marge_haute  = isset($conf->global->MAIN_PDF_MARGIN_TOP)?$conf->global->MAIN_PDF_MARGIN_TOP:10;
		$this->marge_basse  = isset($conf->global->MAIN_PDF_MARGIN_BOTTOM)?$conf->global->MAIN_PDF_MARGIN_BOTTOM:10;

		$this->option_logo            = 1; // Show logo
		$this->option_multilang       = 1; // Multi-language support
		$this->option_freetext        = 1; // Support add of a personalised text
		$this->modulepart             = get_modulepart();
		$this->const_prefix           = get_rights_class(true);
		$watermark_const              = $this->const_prefix . '_DRAFT_WATERMARK';
		$this->watermark_text         = $conf->global->$watermark_const;
		$this->option_draft_watermark = (! empty($this->watermark_text) ? 1 : 0); // Support add of a watermark on drafts

		// Get source company
		$this->emetteur = $mysoc;
		if (empty($this->emetteur->country_code)) $this->emetteur->country_code = substr($langs->defaultlang,-2); // By default, if was not defined

		// Define position of columns
		$this->posxfield = $this->marge_gauche+1;
		$this->posxvalue = 60;
	}

	/**
	 * Function to build pdf onto disk
	 *
	 * @param       Object      $object             Object to generate
	 * @param       Translate   $outputlangs        Lang output object
	 * @param       string      $srctemplatepath    Full path of source filename for generator using a template file
	 * @param       int         $hidedetails        Do not show line details
	 * @param       int         $hidedesc           Do not show desc
	 * @param       int         $hideref            Do not show ref
	 * @return      int                             1=OK, 0=KO
	 */
	public function write_file($object, $outputlangs, $srctemplatepath='', $hidedetails=0, $hidedesc=0, $hideref=0)
	{
		global $user, $langs, $conf, $mysoc, $db, $hookmanager;

		if (! is_object($outputlangs)) $outputlangs = $langs;
		// For backward compatibility with FPDF, force output charset to ISO, because FPDF expect text to be encoded in ISO
		if (! empty($conf->global->MAIN_USE_FPDF)) $outputlangs->charset_output = 'ISO-8859-1';

		$outputlangs->load('main');
		$outputlangs->load('dict');
		$outputlangs->load('companies');

		if ($conf->{$this->modulepart}->dir_output)
		{
			$object->fetch_thirdparty();

			// Definition of $dir and $file
			if ($object->specimen)
			{
				$dir = $conf->{$this->modulepart}->dir_output;
				$file = $dir . '/SPECIMEN.pdf';
			}
			else
			{
				$objectref = dol_sanitizeFileName($object->ref);
				$dir = $conf->{$this->modulepart}->dir_output . '/' . $objectref;
				$file = $dir . '/' . $objectref . '.pdf';
			}

			if (! file_exists($dir))
			{
				if (dol_mkdir($dir) < 0)
				{
					$this->error = $langs->transnoentities('ErrorCanNotCreateDir', $dir);
					return 0;
				}
			}

			if (file_exists($dir))
			{
				// Add pdfgeneration hook
				if (! is_object($hookmanager))
				{
					require_once DOL_DOCUMENT_ROOT.'/core/class/hookmanager.class.php';
					$hookmanager = new HookManager($this->db);
				}
				$hookmanager->initHooks(array('pdfgeneration'));
				$parameters = array('file' => $file, 'object' => $object, 'outputlangs' => $outputlangs);
				global $action;
				$reshook = $hookmanager->executeHooks('beforePDFCreation', $parameters, $object, $action); // Note that $action and $object may have been modified by some hooks

				// Create pdf instance
				$pdf = pdf_getInstance($this->format);
				$default_font_size = pdf_getPDFFontSize($outputlangs); // Must be after pdf_getInstance
				$pdf->SetAutoPageBreak(1, 0);
				
				$heightforinfotot  = 40; // Height reserved to output the info and total part
				$heightforfreetext = (isset($conf->global->MAIN_PDF_FREETEXT_HEIGHT)?$conf->global->MAIN_PDF_FREETEXT_HEIGHT:5); // Height reserved to output the free text on last page
				$heightforfooter   = $this->marge_basse + 8; // Height reserved to output the footer (value include bottom margin)

				if (class_exists('TCPDF'))
				{
					$pdf->setPrintHeader(false);
					$pdf->setPrintFooter(false);
				}

				$pdf->SetFont(pdf_getPDFFont($outputlangs));
				// Set path to the background PDF File
				if (empty($conf->global->MAIN_DISABLE_FPDI) && ! empty($conf->global->MAIN_ADD_PDF_BACKGROUND))
				{
					$pagecount = $pdf->setSourceFile($conf->mycompany->dir_output.'/'.$conf->global->MAIN_ADD_PDF_BACKGROUND);
					$tplidx = $pdf->importPage(1);
				}

				$pdf->Open();
				$pagenb = 0;
				$pdf->SetDrawColor(128, 128, 128);

				$pdf->SetTitle($outputlangs->convToOutputCharset($object->ref));
				$pdf->SetSubject($outputlangs->transnoentities($object->doc_title));
				$pdf->SetCreator('Dolibarr '.DOL_VERSION);
				$pdf->SetAuthor($outputlangs->convToOutputCharset($user->getFullName($outputlangs)));
				$pdf->SetKeyWords($outputlangs->convToOutputCharset($object->ref).' '.$outputlangs->transnoentities($object->doc_title).' '.$outputlangs->convToOutputCharset($object->thirdparty->name));
				if (! empty($conf->global->MAIN_DISABLE_PDF_COMPRESSION)) $pdf->SetCompression(false);

				$pdf->SetMargins($this->marge_gauche, $this->marge_haute, $this->marge_droite); // Left, Top, Right

				// New page
				$pdf->AddPage();
				if (! empty($tplidx)) $pdf->useTemplate($tplidx);
				$pagenb++;
				$this->_pagehead($pdf, $object, 1, $outputlangs);
				$pdf->SetFont('', '', $default_font_size - 1);
				$pdf->MultiCell(0, 3, ''); // Set interline to 3
				$pdf->SetTextColor(0, 0, 0);

				$tab_top            = 90;
				$tab_top_newpage    = (empty($conf->global->MAIN_PDF_DONOTREPEAT_HEAD)?42:10);
				$tab_height         = 130;
				$tab_height_newpage = 150;

				// Incoterm
				$height_incoterms = 0;
				if ($conf->incoterm->enabled)
				{
					$desc_incoterms = $object->getIncotermsForPDF();
					if ($desc_incoterms)
					{
						$tab_top = 88;

						$pdf->SetFont('','', $default_font_size - 1);
						$pdf->writeHTMLCell(190, 3, $this->marge_gauche, $tab_top-1, dol_htmlentitiesbr($desc_incoterms), 0, 1);
						$nexY = $pdf->GetY();
						$height_incoterms=$nexY-$tab_top;

						// Rect prend une longueur en 3eme param
						$pdf->SetDrawColor(192,192,192);
						$pdf->Rect($this->marge_gauche, $tab_top-1, $this->page_largeur-$this->marge_gauche-$this->marge_droite, $height_incoterms+1);

						$tab_top = $nexY+6;
						$height_incoterms += 4;
					}
				}

				// Show notes
				$notetoshow = empty($object->note_public) ? '' : $object->note_public;
				if (! empty($conf->global->MAIN_ADD_SALE_REP_SIGNATURE_IN_NOTE))
				{
					// Get first sale rep
					if (is_object($object->thirdparty))
					{
						$salereparray = $object->thirdparty->getSalesRepresentatives($user);
						$salerepobj = new User($this->db);
						$salerepobj->fetch($salereparray[0]['id']);
						if (! empty($salerepobj->signature)) $notetoshow = dol_concatdesc($notetoshow, $salerepobj->signature);
					}
				}
				if ($notetoshow)
				{
					$tab_top = 88 + $height_incoterms;

					$pdf->SetFont('','', $default_font_size - 1);
					$pdf->writeHTMLCell(190, 3, $this->marge_gauche, $tab_top, dol_htmlentitiesbr($notetoshow), 0, 1);
					$nexY = $pdf->GetY();
					$height_note = $nexY - $tab_top;

					$pdf->SetDrawColor(192, 192, 192);
					$pdf->Rect($this->marge_gauche, $tab_top-1, $this->page_largeur-$this->marge_gauche-$this->marge_droite, $height_note+1);

					$tab_height = $tab_height - $height_note;
					$tab_top = $nexY+6;
				}
				else
				{
					$height_note=0;
				}

				// Write content
				$this->write_content($pdf, $object, $outputlangs, $default_font_size, $tab_top, $heightforinfotot, $heightforfreetext, $heightforfooter);

				// Show square
				$this->_tableau($pdf, $tab_top, $this->page_hauteur - $tab_top - $heightforinfotot - $heightforfreetext - $heightforfooter, 0, $outputlangs, 0, 0);
				//$bottomlasttab = $this->page_hauteur - $heightforinfotot - $heightforfreetext - $heightforfooter + 1;

				// Page foot
				$this->_pagefoot($pdf,$object,$outputlangs);
				if (method_exists($pdf, 'AliasNbPages')) $pdf->AliasNbPages();

				$pdf->Close();

				$pdf->Output($file, 'F');

				// Add pdfgeneration hook
				$hookmanager->initHooks(array('pdfgeneration'));
				$parameters = array('file' => $file, 'object' => $object, 'outputlangs' => $outputlangs);
				global $action;
				$reshook = $hookmanager->executeHooks('afterPDFCreation', $parameters, $this, $action); // Note that $action and $object may have been modified by some hooks

				if (! empty($conf->global->MAIN_UMASK)) {
					@chmod($file, octdec($conf->global->MAIN_UMASK));
				}

				return 1; // No errors
			}
			else
			{
				$this->error = $langs->trans('ErrorCanNotCreateDir', $dir);
				return 0;
			}
		}
		else
		{
			$this->error = $langs->trans('ErrorConstantNotDefined', $this->const_prefix . '_OUTPUTDIR');
			return 0;
		}
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
		global $conf;

		// Columns background color
		$cols_width = $this->posxvalue - $this->posxfield;
		$cols_height = $this->page_hauteur - $tab_top - $heightforinfotot - $heightforfreetext - $heightforfooter;
		//$conf->global->MAIN_PDF_TITLE_BACKGROUND_COLOR = '230,230,230';
		if (! empty($conf->global->MAIN_PDF_TITLE_BACKGROUND_COLOR)) $pdf->Rect($this->marge_gauche, $tab_top, $cols_width, $cols_height, 'F', null, explode(',',$conf->global->MAIN_PDF_TITLE_BACKGROUND_COLOR));

		// Print lines
		if (isset($object->doc_lines))
		{
			$lines_count = count($object->doc_lines);
			$curY = $tab_top + 1;// + 7;
			$i = 0;

			foreach ($object->doc_lines as $line)
			{
				$add_separator = ($i == $lines_count - 1 ? 0 : 1);
				$curY = $this->print_line($pdf, $line['name'], $line['value'], $curY, $outputlangs, $default_font_size, $add_separator);
				$i++;
			}
		}
	}

	/**
	 * Function to print table line
	 *
	 * @param       TCPDF       $pdf                PDF object
	 * @param       string      $name               Row name
	 * @param       string      $value              Row value
	 * @param       int         $curY               Current Y position
	 * @param       Translate   $outputlangs        Lang output object
	 * @param       int         $default_font_size  Default font size
	 * @param       int         $add_separator      Should add a row separator or not
	 * @return      int                             next Y position
	 */
	protected function print_line(&$pdf, $name, $value, $curY, $outputlangs, $default_font_size, $add_separator=1)
	{
		global $langs;

		$pdf->SetFont('', '', $default_font_size - 1); // Into loop to work with multipage
		$pdf->SetTextColor(0, 0, 0);

		//$pdf->setTopMargin($this->marge_haute);
		//$pdf->setPageOrientation('', 1, 0); // The only function to edit the bottom margin of current page to set it.

		// Field
		$curX = $this->posxfield-1;
		$field = $langs->trans($name);
		$pdf->writeHTMLCell($this->posxvalue-$curX-1, 3, $curX, $curY, $outputlangs->convToOutputCharset($field), 0, 1, false, true, 'J', true);
		$nexY = $pdf->GetY(); // must be here

		// Value
		$curX = $this->posxvalue-1;
		$pdf->writeHTMLCell($this->page_largeur-$this->marge_droite-$curX, 3, $curX, $curY, $outputlangs->convToOutputCharset($value), 0, 1, false, true, 'J', true);
		$nextY = $pdf->GetY();
		if ($nextY > $nexY) $nexY = $nextY;

		// Add line
		if ($add_separator)
		{
			$pageposafter = $pdf->getPage();
			$pdf->setPage($pageposafter);
			//$pdf->SetLineStyle(array('dash'=>'1,1','color'=>array(80,80,80)));
			//$pdf->SetDrawColor(190,190,200);
			$pdf->SetLineStyle(array('dash' => 0));
			$pdf->line($this->marge_gauche, $nexY+1, $this->page_largeur - $this->marge_droite, $nexY+1);
			//$pdf->SetLineStyle(array('dash'=>0));
		}

		$nexY += 2; // put some space between lines

		return $nexY;
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

		$pdf->line($this->posxvalue-1, $tab_top, $this->posxvalue-1, $tab_top + $tab_height);
	}

	/**
	 * Show top header of page.
	 *
	 * @param   TCPDF       $pdf            Object PDF
	 * @param   Object      $object         Object to show
	 * @param   int         $showaddress    0=no, 1=yes
	 * @param   Translate   $outputlangs    Object lang for output
	 * @param   string      $titlekey       Translation key to show as title of document
	 * @return  void
	 */
	public function _pagehead(&$pdf, $object, $showaddress, $outputlangs, $titlekey='')
	{
		global $conf, $langs, $hookmanager;

		$outputlangs->load('main');
		$outputlangs->load('companies');

		$default_font_size = pdf_getPDFFontSize($outputlangs);

		pdf_pagehead($pdf, $outputlangs, $this->page_hauteur);

		// Show Draft Watermark
		if($object->status == 0 && (! empty($this->watermark_text)))
		{
			pdf_watermark($pdf, $outputlangs, $this->page_hauteur, $this->page_largeur, 'mm', $this->watermark_text);
		}

		$pdf->SetTextColor(0, 0, 60);
		$pdf->SetFont('', 'B', $default_font_size + 3);

		$posy = $this->marge_haute;
		$posx = $this->page_largeur-$this->marge_droite-100;

		$pdf->SetXY($this->marge_gauche,$posy);

		// Logo
		$logo = $conf->mycompany->dir_output.'/logos/'.$this->emetteur->logo;
		if ($this->emetteur->logo)
		{
			if (is_readable($logo))
			{
				$height = pdf_getHeightForLogo($logo);
				$pdf->Image($logo, $this->marge_gauche, $posy, 0, $height); // width=0 (auto)
			}
			else
			{
				$pdf->SetTextColor(200, 0, 0);
				$pdf->SetFont('', 'B', $default_font_size - 2);
				$pdf->MultiCell(100, 3, $outputlangs->transnoentities('ErrorLogoFileNotFound', $logo), 0, 'L');
				$pdf->MultiCell(100, 3, $outputlangs->transnoentities('ErrorGoToGlobalSetup'), 0, 'L');
			}
		}
		else
		{
			$text = $this->emetteur->name;
			$pdf->MultiCell(100, 4, $outputlangs->convToOutputCharset($text), 0, 'L');
		}

		// Title
		$pdf->SetFont('','B', $default_font_size + 3);
		$pdf->SetXY($posx, $posy);
		$pdf->SetTextColor(0, 0, 60);
		if (empty($titlekey) && ! empty($object->doc_title)) {
			$titlekey = $object->doc_title;
		}
		$title = $outputlangs->transnoentities($titlekey);
		$pdf->MultiCell(100, 3, $title, '', 'R');

		// Ref
		$pdf->SetFont('', 'B',$default_font_size);
		$posy += 5;
		$pdf->SetXY($posx, $posy);
		$pdf->SetTextColor(0, 0, 60);
		$pdf->MultiCell(100, 4, $outputlangs->transnoentities('Ref').' : ' . $outputlangs->convToOutputCharset($object->ref), '', 'R');

		// Date
		$pdf->SetFont('', '', $default_font_size - 1);
		$posy += 5;
		$pdf->SetXY($posx, $posy);
		$pdf->SetTextColor(0, 0, 60);
		$pdf->MultiCell(100, 3, $outputlangs->transnoentities('Date').' : ' . dol_print_date($object->creation_date, '%d %b %Y', false, $outputlangs, true), '', 'R');

		if ($showaddress)
		{
			// Sender properties
			$carac_emetteur = pdf_build_address($outputlangs, $this->emetteur, $object->thirdparty);

			// Show sender
			$posy = 42;
			$posx = $this->marge_gauche;
			if (! empty($conf->global->MAIN_INVERT_SENDER_RECIPIENT)) $posx = $this->page_largeur-$this->marge_droite-80;
			$hautcadre = 40;

			// Show sender frame
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('', '', $default_font_size - 2);
			$pdf->SetXY($posx, $posy-5);
			$pdf->MultiCell(66, 5, $outputlangs->transnoentities('From').':', 0, 'L');
			$pdf->SetXY($posx, $posy);
			$pdf->SetFillColor(230, 230, 230);
			$pdf->MultiCell(82, $hautcadre, '', 0, 'R', 1);
			$pdf->SetTextColor(0, 0, 60);

			// Show sender name
			$pdf->SetXY($posx+2, $posy+3);
			$pdf->SetFont('', 'B', $default_font_size);
			$pdf->MultiCell(80, 4, $outputlangs->convToOutputCharset($this->emetteur->name), 0, 'L');
			$posy=$pdf->getY();

			// Show sender information
			$pdf->SetXY($posx+2, $posy);
			$pdf->SetFont('', '', $default_font_size - 1);
			$pdf->MultiCell(80, 4, $carac_emetteur, 0, 'L');

			// Recipient name
			if ($usecontact && !empty($conf->global->MAIN_USE_COMPANY_NAME_OF_CONTACT)) {
				$thirdparty = $object->contact;
			} else {
				$thirdparty = $object->thirdparty;
			}

			$carac_client_name = $thirdparty->id > 0 ? pdfBuildThirdpartyName($thirdparty, $outputlangs) : '';

			$carac_client = $thirdparty->id > 0 ? pdf_build_address($outputlangs, $this->emetteur, $object->thirdparty, ($usecontact?$object->contact:''), $usecontact, 'target', $object) : '';

			// Show recipient
			$widthrecbox = 100;
			if ($this->page_largeur < 210) $widthrecbox = 84; // To work with US executive format
			$posy = 42;
			$posx = $this->page_largeur-$this->marge_droite-$widthrecbox;
			if (! empty($conf->global->MAIN_INVERT_SENDER_RECIPIENT)) $posx = $this->marge_gauche;

			// Show recipient frame
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('', '', $default_font_size - 2);
			$pdf->SetXY($posx+2, $posy-5);
			$pdf->MultiCell($widthrecbox, 5, $outputlangs->transnoentities('To').':', 0, 'L');
			$pdf->Rect($posx, $posy, $widthrecbox, $hautcadre);

			// Show recipient name
			$pdf->SetXY($posx+2, $posy+3);
			$pdf->SetFont('', 'B', $default_font_size);
			$pdf->MultiCell($widthrecbox, 4, $carac_client_name, 0, 'L');

			$posy = $pdf->getY();

			// Show recipient information
			$pdf->SetFont('', '', $default_font_size - 1);
			$pdf->SetXY($posx+2, $posy);
			$pdf->MultiCell($widthrecbox, 4, $carac_client, 0, 'L');
		}

		$pdf->SetTextColor(0, 0, 0);
	}

	/**
	 * Show footer of page. Need this->emetteur object
	 *
	 * @param   TCPDF       $pdf                PDF
	 * @param   Object      $object             Object to show
	 * @param   Translate   $outputlangs        Object lang for output
	 * @param   int         $hidefreetext       1=Hide free text
	 * @return  int                             Return height of bottom margin including footer text
	 */
	public function _pagefoot(&$pdf, $object, $outputlangs, $hidefreetext=0)
	{
		global $conf;

		$showdetails   = $conf->global->MAIN_GENERATE_DOCUMENTS_SHOW_FOOT_DETAILS;
		$paramfreetext = $this->const_prefix . '_FREE_TEXT';

		return pdf_pagefoot($pdf, $outputlangs, $paramfreetext, $this->emetteur, $this->marge_basse, $this->marge_gauche, $this->page_hauteur, $object, $showdetails, $hidefreetext);
	}
}
