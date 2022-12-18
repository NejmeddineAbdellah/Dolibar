<?php
/**
 * \file        htdocs/useractivitydashboard/class/useractivityexport.class.php
 * \ingroup     User Activity Icon and Graphs Export
 * \brief       Export User Activity Dashboard
 */

// Class
require_once DOL_DOCUMENT_ROOT . '/core/class/commondocgenerator.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/date.lib.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/functions2.lib.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/files.lib.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/pdf.lib.php';
/**
 * Class to Export User Activity Dashboard
 */
class UserActivityExport extends CommonDocGenerator
{
	/**
	 * @var string document name
	 */
	public $name;

	/**
	 * @var int 	Save the name of generated file as the main doc when generating a doc with this template
	 */
	public $update_main_doc_field;

	/**
	 * @var string document type
	 */
	public $type;

	/**
	 * @var array() Minimum version of PHP required by module.
	 * e.g.: PHP ≥ 5.4 = array(5, 5)
	 */
	public $phpmin = array(5, 5);

	/**
	 * Dolibarr version of the loaded document
	 * @public string
	 */
	public $version = 'dolibarr';

	/**
	 * @var int page_largeur
	 */
	public $page_largeur;

	/**
	 * @var int page_hauteur
	 */
	public $page_hauteur;

	/**
	 * @var array format
	 */
	public $format;

	/**
	 * @var int marge_gauche
	 */
	public $marge_gauche;

	/**
	 * @var int marge_droite
	 */
	public $marge_droite;

	/**
	 * @var int marge_haute
	 */
	public $marge_haute;

	/**
	 * @var int marge_basse
	 */
	public $marge_basse;

	/**
	 * Issuer
	 * @var Societe object that emits
	 */
	public $emetteur;

	/**
	 * Footer position for page break
	 */
	public $pagebreak;

	/**
	 * @var int
	 */
	private $option_logo;

	/**
	 * @var int|mixed
	 */
	private $fullwidth;

	/**
	 * @var int
	 */
	private $lineheight;

	/**
	 * @var int|string
	 */
	private $title;

	/**
	 * @var int
	 */
	private $width;

	/**
	 * @var int
	 */
	private $height;
	/**
	 * @var int
	 */
	private $graph_width;
	/**
	 * @var int
	 */
	private $graph_height;

	/**
	 *    Constructor
	 *
	 * @param DoliDB $db Database handler
	 */
	function __construct(DoliDB $db)
	{
		global $conf, $langs;

		$this->db = $db;
		$this->name = "user_activity_dashboard";
		$this->update_main_doc_field = 1;
		$this->title = $langs->trans('UserActivityDashboardArea');

		// Dimensiont page
		$this->type = 'pdf';
		$formatarray = pdf_getFormat();
		$this->page_largeur = $formatarray['width'];
		$this->page_hauteur = $formatarray['height'];
		$this->format = [$this->page_largeur, $this->page_hauteur];
		$this->marge_gauche = isset($conf->global->MAIN_PDF_MARGIN_LEFT) ? $conf->global->MAIN_PDF_MARGIN_LEFT : 5;
		$this->marge_droite = isset($conf->global->MAIN_PDF_MARGIN_RIGHT) ? $conf->global->MAIN_PDF_MARGIN_RIGHT : 5;
		$this->marge_haute = isset($conf->global->MAIN_PDF_MARGIN_TOP) ? $conf->global->MAIN_PDF_MARGIN_TOP : 5;
		$this->marge_basse = isset($conf->global->MAIN_PDF_MARGIN_BOTTOM) ? $conf->global->MAIN_PDF_MARGIN_BOTTOM : 5;

		$this->pagebreak = 260;
		$this->option_logo = 1;
		$this->width = 38;
		$this->height = 20;
		$this->fullwidth = $this->page_largeur - $this->marge_gauche;
		$this->lineheight = 10;
		$this->graph_width = 140;
		$this->graph_height = 70;
	}

	/**
	 *  Function to generate pdf of User Activity Dashboard
	 *
	 * @param array $parameter
	 * @return void 1=OK, 0=KO
	 */
	function write_file($parameter)
	{
		global $langs, $conf;

		// For backward compatibility with FPDF, force output charset to ISO, because FPDF expect text to be encoded in ISO
		if (! empty($conf->global->MAIN_USE_FPDF)) $langs->charset_output = 'ISO-8859-1';

		$file = $this->name . '.pdf';

		// Create pdf instance
		$pdf = pdf_getInstance($this->format, 'mm', 'l');
		$default_font_size = pdf_getPDFFontSize($langs);	// Must be after pdf_getInstance
		$pdf->SetAutoPageBreak(1, $this->marge_basse);

		if (class_exists('TCPDF')) {
			$pdf->setPrintHeader(false);
			$pdf->setPrintFooter(false);
		}
		$pdf->SetFont(pdf_getPDFFont($langs));
		$pdf->Open();
		$pdf->SetDrawColor(128, 128, 128);

		$pdf->SetTitle($this->name);
		$pdf->SetMargins($this->marge_gauche, $this->marge_haute, $this->marge_droite);

		$pdf->AddPage();
		if (!empty($tplidx)) $pdf->useTemplate($tplidx);

		$this->_pagehead($pdf, $parameter);
		$pdf->SetFont('', '', $default_font_size);
		$pdf->MultiCell(0, $this->lineheight, '');
		$pdf->SetTextColor(0,0,0);
		$this->pageinfo($pdf, $parameter);

		$pdf->lastPage();
		$pdf->Output($file, 'D');
	}

	/**
	 *  Show top header of page.
	 *
	 * @param $pdf Object PDF
	 * @param $parameter
	 * @return    void
	 * @throws Exception
	 */
	function _pagehead($pdf, $parameter)
	{
		global $langs;
		$default_font_size = pdf_getPDFFontSize($langs);
		pdf_pagehead($pdf, $langs, $this->page_hauteur);

		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('', 'B', $default_font_size + 4);
		$posY = $this->marge_haute;
		$posX = $this->marge_gauche;

		$pdf->writeHTMLCell($this->fullwidth, $this->lineheight, $posX, $posY, $langs->trans('PerformanceSummary', getDisplayHeader($parameter['displayType'], $parameter['dateRange']), $parameter['category'] ?? $langs->trans('All')));
		$posY = $pdf->getY() + 8;

		$pdf->SetFont('', 'B', $default_font_size + 2);
		$pdf->SetFillColor(230, 227, 200);
		$pdf->writeHTMLCell($this->width, $this->height, $posX, $posY, '<div style="text-align: center">Total Number of Prospects Entered <br>' . getProspectSummary($parameter['categoryId'], $parameter['dateRange']) . '</div>', 1, 0, true);
		$posX = $this->width + $this->marge_gauche + 2;
		$pdf->writeHTMLCell($this->width, $this->height, $posX, $posY, '<div style="text-align: center">Total Number of Bids/Proposals Submitted <br>' . getQuotationSummary($parameter['categoryId'], $parameter['dateRange']) . '</div>', 1, 0, true);
		$posX = $this->width + $posX + 2;
		$pdf->SetFillColor(193, 193, 193);
		$pdf->writeHTMLCell($this->width, $this->height, $posX, $posY, '<div style="text-align: center">Total Number of Conversion to Sales <br>' . getConversionSaleSummary($parameter['categoryId'], $parameter['dateRange'], $parameter['category']) . '</div>', 1, 0, true);
		$posX = $this->width + $posX + 2;
		$pdf->writeHTMLCell($this->width, $this->height, $posX, $posY, '<div style="text-align: center">% of Conversion to Sales <br>' . getConversionSalePercentSummary($parameter['categoryId'], $parameter['dateRange'], $parameter['category']) . '</div>', 1, 0, true);
		$posX = $this->width + $posX + 2;
		$pdf->writeHTMLCell($this->width, $this->height, $posX, $posY, '<div style="text-align: center">Total Sales <br>' . getTotalSalesSummary($parameter['categoryId'], $parameter['dateRange'], $parameter['category']) . '</div>', 1, 0, true);
		$posX = $this->width + $posX + 2;
		$pdf->SetFillColor(230, 227, 200);
		$pdf->writeHTMLCell($this->width, $this->height, $posX, $posY, '<div style="text-align: center">Gross Profit <br>' . getTotalProfit($parameter['categoryId'], $parameter['dateRange'], $parameter['category']) . '</div>', 1, 0, true);
		$posX = $this->width + $posX + 2;
		$pdf->writeHTMLCell($this->width, $this->height, $posX, $posY, '<div style="text-align: center">% Cost to Sales <br>' . getTotalCostPercent($parameter['categoryId'], $parameter['dateRange'], $parameter['category']) . '</div>', 1, 0, true);
	}

	/**
	 * Show PDF contents in the page
	 * @param $pdf
	 * @param $parameter
	 * @throws Exception
	 */
	function pageinfo($pdf, $parameter)
	{
		global $langs, $conf;
		$graph_position = $conf->useractivitydashboard->dir_output . '/temp/';
		$default_font_size = pdf_getPDFFontSize($langs);
		$posY = $pdf->getY() + $this->lineheight + 2;
		$posX = $this->marge_gauche;
		$pdf->writeHTMLCell($this->graph_width, $this->lineheight, $posX, $posY, '<div style="text-align: center">' . $langs->trans("ProspectsEntered", $parameter['category'], getDisplayHeader($parameter['displayType'], $parameter['dateRange'])) . '</div>');
		$pdf->Image($graph_position . $parameter['prospectGraph'], $posX, $posY + $this->lineheight, 0, $this->graph_height);

		$posX = $this->marge_gauche + $this->graph_width;
		$pdf->writeHTMLCell($this->graph_width, $this->lineheight, $posX, $posY, '<div style="text-align: center">' . $langs->trans("QuotationsEntered", $parameter['category'], getDisplayHeader($parameter['displayType'], $parameter['dateRange'])) . '</div>');
		$pdf->Image($graph_position . $parameter['quotationGraph'], $posX, $posY + $this->lineheight, 0, $this->graph_height);

		$posY = $pdf->getY() + $this->graph_height + $this->lineheight;
		$posX = $this->marge_gauche;
		$pdf->writeHTMLCell($this->graph_width, $this->lineheight, $posX, $posY, '<div style="text-align: center">' . $langs->trans("ConversionSalesEntered", $parameter['category'], getDisplayHeader($parameter['displayType'], $parameter['dateRange'])) . '</div>');
		$pdf->Image($graph_position . $parameter['conversionSaleGraph'], $posX, $posY + $this->lineheight, 0, $this->graph_height);

		$posX = $this->marge_gauche + $this->graph_width;
		$pdf->writeHTMLCell($this->graph_width, $this->lineheight, $posX, $posY, '<div style="text-align: center">' . $langs->trans("ConversionSalesPercentEntered", $parameter['category'], getDisplayHeader($parameter['displayType'], $parameter['dateRange'])) . '</div>');
		$pdf->Image($graph_position . $parameter['conversionSalePercentGraph'], $posX, $posY + $this->lineheight, 0, $this->graph_height);

		$pdf->SetDrawColor(128, 128, 128);

		$pdf->SetTitle($this->name);
		$pdf->SetMargins($this->marge_gauche, $this->marge_haute, $this->marge_droite);

		$pdf->AddPage();
		if (!empty($tplidx)) $pdf->useTemplate($tplidx);

		$this->_pagehead($pdf, $parameter);

		$posY = $pdf->getY() + $this->height + 2;
		$posX = $this->marge_gauche;
		$pdf->SetFont('', '', $default_font_size);
		$pdf->writeHTMLCell($this->graph_width, $this->lineheight, $posX, $posY, '<div style="text-align: center">' . $langs->trans("ConversionSalesTotalEntered", $parameter['category'], getDisplayHeader($parameter['displayType'], $parameter['dateRange'])) . '</div>');
		$pdf->Image($graph_position . $parameter['conversionSaleTotalGraph'], $posX, $posY + $this->lineheight, 0, $this->graph_height);

		$posX = $this->marge_gauche + $this->graph_width;
		$pdf->writeHTMLCell($this->graph_width, $this->lineheight, $posX, $posY, '<div style="text-align: center">' . $langs->trans("ProfitTotalEntered", $parameter['category'], getDisplayHeader($parameter['displayType'], $parameter['dateRange'])) . '</div>');
		$pdf->Image($graph_position . $parameter['profitTotalGraph'], $posX, $posY + $this->lineheight, 0, $this->graph_height);

		$posY = $pdf->getY() + $this->graph_height + $this->lineheight;
		$posX = $this->marge_gauche;
		$pdf->writeHTMLCell($this->graph_width, $this->lineheight, $posX, $posY, '<div style="text-align: center">' . $langs->trans("CostSalesPercentEntered", $parameter['category'], getDisplayHeader($parameter['displayType'], $parameter['dateRange'])) . '</div>');
		$pdf->Image($graph_position . $parameter['costSalePercentGraph'], $posX, $posY + $this->lineheight, 0, $this->graph_height);
	}
}
