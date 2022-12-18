<?php
/* Copyright (C) 2017  Laurent Destailleur <eldy@users.sourceforge.net>
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
 * \file        class/useractivitygraph.class.php
 * \ingroup     useractivitydashboard
 * \brief       This file is a graph class file for UserActivityDashboard charts
 */

require_once DOL_DOCUMENT_ROOT . '/core/class/dolgraph.class.php';

/**
 * Class UserActivityGraph
 */
class UserActivityGraph extends DolGraph
{
	public $showXGridLine = 'true'; //Graph grid display style in X Axis
	public $showYGridLine = 'true'; //Graph grid display style in Y Axis
	public $showGraphLine = false;  //Enable showing line on graph
	public $lineColor;				//Line colour in the chart
	private $stringtoshow;
	private $_library = 'jflot'; // Graphic library to use (jflot, artichow)
	public $showCurrency = false;
	private $currencySymbol;
	public $secondaryLineProspects = 0;
	private $secondaryLineColor;
	private $secondaryLineLabel;

	/**
	 * Sets Show hide grid line X axis
	 * @param $xLine
	 * @return bool
	 */
	public function setShowXGridLine($xLine)
	{
		$this->showXGridLine = $xLine;
		return true;
	}

	/**
	 * Sets Show hide grid line X axis
	 * @param $label
	 * @return bool
	 */
	public function setSecondaryLineLabel($label)
	{
		$this->secondaryLineLabel = $label;
		return true;
	}

	/**
	 * Shows secondary grid line for extra secondary category
	 * @param $prospects
	 * @return bool
	 */
	public function setSecondaryLineCount($prospects)
	{
		$this->secondaryLineProspects = $prospects;
		return true;
	}

	/**
	 * Sets Show hide grid line Y axis
	 * @param $yLine
	 * @return bool
	 */
	public function setShowYGridLine($yLine)
	{
		$this->showYGridLine = $yLine;
		return true;
	}

	/**
	 * Sets to enable avarage graph line
	 * @param $bool
	 * @return bool
	 */
	public function setShowGraphLine($bool)
	{
		$this->showGraphLine = $bool;
		return true;
	}

	/**
	 * Set average lines colour
	 * @param $linecolor
	 */
	public function setLineColor($linecolor)
	{
		$this->lineColor = $linecolor;
		return true;
	}

	/**
	 * Set secondary lines colour
	 * @param $secondaryLineColor
	 * @return bool
	 */
	public function setSecondaryLineColor($secondaryLineColor)
	{
		$this->secondaryLineColor = $secondaryLineColor;
		return true;
	}

	/**
	 * Sets Show currency symbol in ticks
	 * @param $bool
	 * @return bool
	 */
	public function setShowCurrency($bool)
	{
		$this->showCurrency = $bool;
		return true;
	}

	/**
	 * Constructor
	 *
	 * @param	string	$library		'auto' (default)
	 */
	public function __construct($library = 'auto')
	{
		global $conf, $langs;
		global $theme_bordercolor, $theme_datacolor, $theme_bgcolor;

		$this->bordercolor = array(235, 235, 224);
		$this->datacolor = array(array(120, 130, 150), array(160, 160, 180), array(190, 190, 220));
		$this->bgcolor = array(235, 235, 224);

		$color_file = DOL_DOCUMENT_ROOT.'/theme/'.$conf->theme.'/theme_vars.inc.php';
		if (is_readable($color_file)) {
			include_once $color_file;
			if (isset($theme_bordercolor)) $this->bordercolor = $theme_bordercolor;
			if (isset($theme_datacolor))   $this->datacolor   = $theme_datacolor;
			if (isset($theme_bgcolor))     $this->bgcolor     = $theme_bgcolor;
		}

		$this->_library = $library;
		if ($this->_library == 'auto') {
			$this->_library = (empty($conf->global->MAIN_JS_GRAPH) ? 'jflot' : $conf->global->MAIN_JS_GRAPH);
		}

		$this->currencySymbol = $langs->getCurrencySymbol($conf->currency);
	}

	/**
	 * Build a graph into memory using correct library  (may also be wrote on disk, depending on library used)
	 *
	 * @param	string	$file    	Image file name to use to save onto disk (also used as javascript unique id)
	 * @param	string	$fileurl	Url path to show image if saved onto disk
	 * @return	integer|null
	 */
	public function draw($file, $fileurl = '')
	{
		if (empty($file))
		{
			$this->error = "Call to draw method was made with empty value for parameter file.";
			dol_syslog(get_class($this)."::draw ".$this->error, LOG_ERR);
			return -2;
		}
		if (!is_array($this->data))
		{
			$this->error = "Call to draw method was made but SetData was not called or called with an empty dataset for parameters";
			dol_syslog(get_class($this)."::draw ".$this->error, LOG_ERR);
			return -1;
		}
		if (count($this->data) < 1)
		{
			$this->error = "Call to draw method was made but SetData was is an empty dataset";
			dol_syslog(get_class($this)."::draw ".$this->error, LOG_WARNING);
		}
		$call = "draw_".$this->_library;
		call_user_func_array(array($this, $call), array($file, $fileurl));
	}

	/**
	 * Build a graph using Chart library. Input when calling this method should be:
	 *	$this->data  = array(array(0=>'labelxA',1=>yA),  array('labelxB',yB));
	 *	$this->data  = array(array(0=>'labelxA',1=>yA1,...,n=>yAn), array('labelxB',yB1,...yBn));   // or when there is n series to show for each x
	 *  $this->data  = array(array('label'=>'labelxA','data'=>yA),  array('labelxB',yB));			// Syntax deprecated
	 *  $this->legend= array("Val1",...,"Valn");													// list of n series name
	 *  $this->type  = array('bars',...'lines', 'linesnopoint'); or array('pie') or array('polar') or array('piesemicircle');
	 *  $this->mode = 'depth' ???
	 *  $this->bgcolorgrid
	 *  $this->datacolor
	 *  $this->shownodatagraph
	 *
	 * @param	string	$file    	Image file name to use to save onto disk (also used as javascript unique id)
	 * @param	string	$fileurl	Url path to show image if saved onto disk. Never used here.
	 * @return	void
	 */
	private function draw_chart($file, $fileurl)
	{
		global $langs;

		if (empty($this->width) && empty($this->height)) {
			print 'Error width or height not set';
			return;
		}

		$showlegend = $this->showlegend;

		$legends = [];
		$nblot = 0;

		if (is_array($this->data)) {
			foreach ($this->data as $valarray) {       // Loop on each x
				$nblot = max($nblot, count($valarray) - 1); // -1 to remove legend
			}
		}

		if ($nblot < 0) dol_syslog('Bad value for property ->data. Must be set by mydolgraph->SetData before calling mydolgrapgh->draw', LOG_WARNING);
		$firstlot = 0;

		$serie = array(); $arrayofgroupslegend = array();

		$i = $firstlot;
		while ($i < $nblot)	{ // Loop on each serie
			$values = array(); // Array with horizontal y values (specific values of a serie) for each abscisse x (with x=0,1,2,...)
			$serie[$i] = "";

			// Fill array $values
			$x = 0;
			foreach ($this->data as $valarray) {	// Loop on each x
				$legends[$x] = (array_key_exists('label', $valarray) ? $valarray['label'] : $valarray[0]);
				$array_of_ykeys = array_keys($valarray);
				$alabelexists = 1;
				$tmpykey = explode('_', ($array_of_ykeys[$i + ($alabelexists ? 1 : 0)]), 3);
				if (!empty($tmpykey[2]) || $tmpykey[2] == '0') {		// This is a 'Group by' array
					$tmpvalue = (array_key_exists('y_' . $tmpykey[1] . '_' . $tmpykey[2], $valarray) ? $valarray['y_' . $tmpykey[1] . '_' . $tmpykey[2]] : $valarray[$i + 1]);
					$values[$x] = (is_numeric($tmpvalue) ? $tmpvalue : null);
					$arrayofgroupslegend[$i] = [
						'stacknum'=> $tmpykey[1],
						'legend' => $this->Legend[$tmpykey[1]],
						'legendwithgroup' => $this->Legend[$tmpykey[1]] . ' - ' . $tmpykey[2]
					];
				} else {
					$tmpvalue = (array_key_exists('y_' . $i, $valarray) ? $valarray['y_' . $i] : $valarray[$i + 1]);
					$values[$x] = (is_numeric($tmpvalue) ? $tmpvalue : null);
				}
				$x++;
			}
			$j = 0;
			foreach ($values as $x => $y) {
				if (isset($y)) {
					$serie[$i] .= ($j > 0 ? ", " : "") . $y;
				} else {
					$serie[$i] .= ($j > 0 ? ", " : "") . 'null';
				}
				$j++;
			}

			$values = null; // Free mem
			$i++;
		}
		$tag = dol_escape_htmltag(dol_string_unaccent(dol_string_nospecial(basename($file), '_', array('-', '.'))));

		$this->stringtoshow = '<!-- Build using chart -->' . "\n";
		if (!empty($this->title)) $this->stringtoshow .= '<div class="center dolgraphtitle' . (empty($this->cssprefix) ? '' : ' dolgraphtitle' . $this->cssprefix) . '">' . $this->title . '</div>';
		if (!empty($this->shownographyet)) {
			$this->stringtoshow .= '<div style="width:' . $this->width . (strpos($this->width, '%') > 0 ? '' : 'px').'; height:' . $this->height . 'px;" class="nographyet"></div>';
			$this->stringtoshow .= '<div class="nographyettext margintoponly">' . $langs->trans("NotEnoughDataYet") . '...</div>';
			return;
		}

		// Start the div that will contains all the graph
		$dolxaxisvertical = '';
		if (count($this->data) > 20) $dolxaxisvertical = 'dol-xaxis-vertical';
		// No height for the pie grah
		$cssfordiv = 'dolgraphchart';
		if (isset($this->type[$firstlot])) $cssfordiv .= ' dolgraphchar' . $this->type[$firstlot];
		$this->stringtoshow .= '<div id="placeholder_' . $tag . '" style="min-height: ' . $this->height . (strpos($this->height, '%') > 0 ? '' : 'px') . '; width:' . $this->width . (strpos($this->width, '%') > 0 ? '' : 'px') . ';" class="' . $cssfordiv . ' dolgraph' . (empty($dolxaxisvertical) ? '' : ' ' . $dolxaxisvertical) . (empty($this->cssprefix) ? '' : ' dolgraph' . $this->cssprefix) . ' center"><canvas id="canvas_' . $tag . '"></canvas></div>' . "\n";

		$this->stringtoshow .= '<script id="' . $tag . '">' . "\n";
		$i = $firstlot;
		if ($nblot < 0) {
			$this->stringtoshow .= '<!-- No series of data -->';
		} else {
			while ($i < $nblot) {
				$i++;
			}
		}
		$this->stringtoshow .= "\n";

		// Special case for Graph of type 'pie', 'piesemicircle', or 'polar'
		if (isset($this->type[$firstlot]) && (in_array($this->type[$firstlot], array('pie', 'polar', 'piesemicircle')))) {
			$type = $this->type[$firstlot]; // pie or polar
			$this->stringtoshow .= 'var options = {' . "\n";
			$legendMaxLines = 0; // Does not work
			if (empty($showlegend)) {
				$this->stringtoshow .= 'legend: { display: false }, ';
			} else {
				$this->stringtoshow .= 'legend: { position: \''.($showlegend == 2 ? 'right' : 'top') . '\'';
				if (!empty($legendMaxLines)) {
					$this->stringtoshow .= ', maxLines: ' . $legendMaxLines;
				}
				$this->stringtoshow .= ' }, '."\n";
			}

			if ($this->type[$firstlot] == 'piesemicircle') {
				$this->stringtoshow .= 'circumference: Math.PI,' . "\n";
				$this->stringtoshow .= 'rotation: -Math.PI,' . "\n";
			}
			$this->stringtoshow .= 'elements: { arc: {' . "\n";
			// Color of earch arc
			$this->stringtoshow .= 'backgroundColor: [';
			$i = 0; $foundnegativecolor = 0;
			foreach ($legends as $val) {	// Loop on each serie
				if ($i > 0) $this->stringtoshow .= ', ' . "\n";
				if (is_array($this->datacolor[$i])) {
					// If datacolor is array(R, G, B)
					$color = 'rgb(' . $this->datacolor[$i][0] . ', ' . $this->datacolor[$i][1] . ', ' . $this->datacolor[$i][2] . ')';
				} else {
					$tmp = str_replace('#', '', $this->datacolor[$i]);
					if (strpos($tmp, '-') !== false) {
						$foundnegativecolor++;
						$color = '#FFFFFF'; // If $val is '-123'
					}
					else $color = "#" . $tmp; // If $val is '123' or '#123'
				}
				$this->stringtoshow .= "'" . $color . "'";
				$i++;
			}
			$this->stringtoshow .= '], ' . "\n";
			// Border color
			if ($foundnegativecolor) {
				$this->stringtoshow .= 'borderColor: [';
				$i = 0;
				foreach ($legends as $val) { // Loop on each serie
					if ($i > 0) $this->stringtoshow .= ', ' . "\n";
					if (is_array($this->datacolor[$i])) {
						$color = 'null'; // If datacolor is array(R, G, B)
					} else {
						$tmp = str_replace('#', '', $this->datacolor[$i]);
						if (strpos($tmp, '-') !== false) {
							$color = '#' . str_replace('-', '', $tmp); // If $val is '-123'
						} else {
							$color = 'null'; // If $val is '123' or '#123'
						}
					}
					$this->stringtoshow .= ($color == 'null' ? "'rgba(0,0,0,0.2)'" : "'".$color."'");
					$i++;
				}
				$this->stringtoshow .= ']';
			}
			$this->stringtoshow .= '} } };' . "\n";

			$this->stringtoshow .= '
				var ctx = document.getElementById("canvas_' . $tag . '").getContext("2d");
				var chart = new Chart(ctx, {
			    // The type of chart we want to create
    			type: \'' . (in_array($type, array('pie', 'piesemicircle')) ? 'doughnut' : 'polarArea') . '\',
				// Configuration options go here
    			options: options,
				data: {
					labels: [';

			$i = 0;
			foreach ($legends as $val) { 	// Loop on each serie
				if ($i > 0) $this->stringtoshow .= ', ';
				$this->stringtoshow .= "'" . dol_escape_js(dol_trunc($val, 32)) . "'";
				$i++;
			}

			$this->stringtoshow .= '],
					datasets: [';
			$i = 0;
			while ($i < $nblot)	{ // Loop on each serie
				$color = 'rgb(' . $this->datacolor[$i][0] . ', ' . $this->datacolor[$i][1] . ', ' . $this->datacolor[$i][2] . ')';
				if ($i > 0) $this->stringtoshow .= ', ' . "\n";
				$this->stringtoshow .= '{' . "\n";
				$this->stringtoshow .= '  data: [' . $serie[$i] . ']';
				$this->stringtoshow .= '}' . "\n";
				$i++;
			}
			$this->stringtoshow .= ']' . "\n";
			$this->stringtoshow .= '}' . "\n";
			$this->stringtoshow .= '});' . "\n";
		} else {
			// Other cases, graph of type 'bars', 'lines', 'linesnopoint'
			$type = 'bar';
			if (!isset($this->type[$firstlot]) || $this->type[$firstlot] == 'bars') $type = 'bar';
			if (isset($this->type[$firstlot]) && ($this->type[$firstlot] == 'lines' || $this->type[$firstlot] == 'linesnopoint')) $type = 'line';

			$this->stringtoshow .= 'var options = { animation: { onComplete: done }, maintainAspectRatio: true, aspectRatio: 2, ';
			if (empty($showlegend)) {
				$this->stringtoshow .= 'legend: { display: false }, ';
			} else {
				$this->stringtoshow .= 'legend: { position: \'bottom\' }, ';
			}
			$this->stringtoshow .= 'scales: { xAxes: [{ ';
			//$this->stringtoshow .= 'type: \'time\', ';		// Need Moment.js
			$this->stringtoshow .= 'gridLines: { display:' . $this->showXGridLine . ' }, '; // Adds style to hide xAxes gridlines
			$this->stringtoshow .= 'distribution: \'linear\'';
			if ($type == 'bar' && count($arrayofgroupslegend) > 0) {
				$this->stringtoshow .= ', stacked: true';
			}
			$this->stringtoshow .= ' }]';
			$this->stringtoshow .= ', yAxes: [{ ';
			if ($type == 'bar' && count($arrayofgroupslegend) > 0) {
				$this->stringtoshow .= 'stacked: true, ';
			}
			$this->stringtoshow .= 'ticks: { min: ' . $this->MinValue . ', max: ' . $this->MaxValue; // Show Min and Max value
			if ($this->showCurrency) {
				$this->stringtoshow .= ' , callback: function(value, index, values) { return \'' . $this->currencySymbol . '\' + value; }';
			}
			if ($this->showpercent) {
				$this->stringtoshow .= ' , callback: function(value, index, values) { return value + \'%\'; }';
			}
			$this->stringtoshow .= ' }, '; // Show Min and Max value
			$this->stringtoshow .= ' id: "left-y-axis", type: "linear", position: "left", ';
			$this->stringtoshow .= 'gridLines: { display:' . $this->showYGridLine . ', color: \'rgba(0, 0, 0, 0.4)\' }, '; // Adds style to hide yAxes gridlines
			if ($this->secondaryLineProspects > 0) {
				$this->stringtoshow .= ' }, { id: "right-y-axis", type: "linear", position: "right", gridLines: { display: false }';
			}
			$this->stringtoshow .= ' }]';
			$this->stringtoshow .= ' }';
			$this->stringtoshow .= '};';

			$this->stringtoshow .= '
				var ctx = document.getElementById("canvas_'.$tag.'").getContext("2d");
				var chart = new Chart(ctx, {
			    // The type of chart we want to create
    			type: \'' . $type . '\',
				// Configuration options go here
    			options: options,
				data: {
					labels: [';

			$i = 0;
			foreach ($legends as $val) {	// Loop on each serie
				if ($i > 0) $this->stringtoshow .= ', ';
				$this->stringtoshow .= "'" . dol_escape_js(dol_trunc($val, 32)) . "'";
				$i++;
			}

			$this->stringtoshow .= '],';
			$this->stringtoshow .= 'yAxisID: "left-y-axis",';
			$this->stringtoshow .= 'datasets: [';

			global $theme_datacolor;
			$i = 0; $iinstack = 0;
			$oldstacknum = -1;
			while ($i < $nblot)	{ // Loop on each serie
				$usecolorvariantforgroupby = 0;
				// We used a 'group by' and we have too many colors so we generated color variants per
				if (is_array($arrayofgroupslegend[$i]) && count($arrayofgroupslegend[$i]) > 0) {	// If we used a group by.
					$nbofcolorneeds = count($arrayofgroupslegend);
					$nbofcolorsavailable = count($theme_datacolor);
					if ($nbofcolorneeds > $nbofcolorsavailable) {
						$usecolorvariantforgroupby = 1;
					}
					$textoflegend = $arrayofgroupslegend[$i]['legendwithgroup'];
				} else {
					$textoflegend = $this->Legend[$i];
				}

				if ($usecolorvariantforgroupby) {
					$newcolor = $this->datacolor[$arrayofgroupslegend[$i]['stacknum']];
					// If we change the stack
					if ($oldstacknum == -1 || $arrayofgroupslegend[$i]['stacknum'] != $oldstacknum) {
						$iinstack = 0;
					}

					if ($iinstack) {
						// Change color with offset of $$iinstack
						if ($iinstack % 2) {	// We increase agressiveness of reference color for color 2, 4, 6, ...
							$ratio = min(95, 10 + 10 * $iinstack); // step of 20
							$brightnessratio = min(90, 5 + 5 * $iinstack); // step of 10
						} else {				// We decrease agressiveness of reference color for color 3, 5, 7, ..
							$ratio = max(-100, - 15 * $iinstack + 10); // step of -20
							$brightnessratio = min(90, 10 * $iinstack); // step of 20
						}
						$newcolor = array_values(colorHexToRgb(colorAgressiveness(colorArrayToHex($newcolor), $ratio, $brightnessratio), false, true));
					}
					$oldstacknum = $arrayofgroupslegend[$i]['stacknum'];

					$color = 'rgb(' . $newcolor[0] . ', ' . $newcolor[1] . ', ' . $newcolor[2] . ', 0.9)';
					$bordercolor = 'rgb(' . $newcolor[0] . ', ' . $newcolor[1] . ', ' . $newcolor[2] . ')';
				} else {																			// We do not use a 'group by'
					$color = 'rgb(' . $this->datacolor[$i][0] . ', ' . $this->datacolor[$i][1] . ', ' . $this->datacolor[$i][2] . ', 0.9)';
					$bordercolor = $color;
				}

				if ($i > 0) $this->stringtoshow .= ', ';
				$this->stringtoshow .= "\n";
				/**
				 * Shows average graph line
				 */
				if ($this->showGraphLine) {
					$index = count(explode(',', $serie[$i]));
					$value = round(array_sum(explode(',', $serie[$i])) / count(explode(',', $serie[$i])), 1);
					$data = implode(',', array_fill(0, $index, $value));
					$this->stringtoshow .= '{' . "\n";
					$this->stringtoshow .= 'label: \'Average\', data: [' . $data . '], type: \'line\', backgroundColor: \'rgb(255, 255, 255, 0.1)\', borderColor: \'rgb(' . $this->lineColor[$i][0] . ', ' . $this->lineColor[$i][1] . ', ' . $this->lineColor[$i][2] . ', 0.9)\', pointStyle: \'line\'' . "\n";
					$this->stringtoshow .= '}, ' . "\n";
					if ($this->secondaryLineProspects > 0) {
						$secondaryData = implode(',', array_fill(0, $index, $this->secondaryLineProspects));
						$this->stringtoshow .= '{' . "\n";
						$this->stringtoshow .= 'label: \'' . $this->secondaryLineLabel . '\', data: [' . $secondaryData . '], type: \'line\', backgroundColor: \'rgb(255, 255, 255, 0.1)\', borderColor: \'rgb(' . $this->secondaryLineColor[$i][0] . ', ' . $this->secondaryLineColor[$i][1] . ', ' . $this->secondaryLineColor[$i][2] . ', 0.9)\', pointStyle: \'line\', yAxisID: "right-y-axis"' . "\n";
						$this->stringtoshow .= '}, ' . "\n";
					}

				}
				$this->stringtoshow .= '{';
				$this->stringtoshow .= 'dolibarrinfo: \'y_' . $i . '\', ';
				$this->stringtoshow .= 'label: \'' . dol_escape_js(dol_string_nohtmltag($textoflegend)) . '\', ';
				$this->stringtoshow .= 'pointStyle: \'' . ($this->type[$i] == 'linesnopoint' ? 'line' : 'circle') . '\', ';
				$this->stringtoshow .= 'fill: '.($type == 'bar' ? 'true' : 'false').', ';

				if ($type == 'bar') { $this->stringtoshow .= 'borderWidth: \'1\', '; }
				$this->stringtoshow .= 'borderColor: \'' . $bordercolor . '\', ';
				$this->stringtoshow .= 'backgroundColor: \'' . $color . '\', ';

				if ($arrayofgroupslegend[$i]) $this->stringtoshow .= 'stack: \'' . $arrayofgroupslegend[$i]['stacknum'] . '\', ';
				$this->stringtoshow .= 'data: [' . $serie[$i] . ']';
				$this->stringtoshow .= '}' . "\n";

				$i++;
				$iinstack++;
			}
			$this->stringtoshow .= ']' . "\n";
			$this->stringtoshow .= '}' . "\n";
			$this->stringtoshow .= '});' . "\n";
		}

		$this->stringtoshow .= 'function done() {
			switch("' . $tag . '") {
				case "user_prospect_graph_png":
				case "category_prospect_graph_png":
					var prospect = document.getElementById("canvas_' . $tag . '").toDataURL();
					$("#searchFormList").append(\'<input type="hidden" name="prospect" id="prospect" value=\' + prospect + \'>\');
					break;
				case "user_quotation_graph_png":
				case "category_quotation_graph_png":
					var quotation = document.getElementById("canvas_' . $tag . '").toDataURL();
					$("#searchFormList").append(\'<input type="hidden" name="quotation" id="quotation" value=\' + quotation + \'>\');
					break;
				case "user_conversion_sale_graph_png":
				case "category_conversion_sale_graph_png":
					var conversion_sale_graph = document.getElementById("canvas_' . $tag . '").toDataURL();
					$("#searchFormList").append(\'<input type="hidden" name="conversion_sale" id="conversion_sale" value=\' + conversion_sale_graph + \'>\');
					break;
				case "user_conversion_sale_percent_graph_png":
				case "category_conversion_sale_percent_graph_png":
					var conversion_sale_percent_graph = document.getElementById("canvas_' . $tag . '").toDataURL();
					$("#searchFormList").append(\'<input type="hidden" name="conversion_sale_percent" id="conversion_sale_percent" value=\' + conversion_sale_percent_graph + \'>\');
					break;
				case "user_conversion_sale_total_graph_png":
				case "category_conversion_sale_total_graph_png":
					var conversion_sale_total_graph = document.getElementById("canvas_' . $tag . '").toDataURL();
					$("#searchFormList").append(\'<input type="hidden" name="conversion_sale_total" id="conversion_sale_total" value=\' + conversion_sale_total_graph + \'>\');
					break;
				case "user_profit_total_graph_png":
				case "category_profit_total_graph_png":
					var profit_total_graph = document.getElementById("canvas_' . $tag . '").toDataURL();
					$("#searchFormList").append(\'<input type="hidden" name="profit_total" id="profit_total" value=\' + profit_total_graph + \'>\');
					break;
				default:
					var cost_sale_percent_total_graph = document.getElementById("canvas_' . $tag . '").toDataURL();
					$("#searchFormList").append(\'<input type="hidden" name="cost_sale_percent_total" id="cost_sale_percent_total" value=\' + cost_sale_percent_total_graph + \'>\');
			}
		}' . "\n";
		$this->stringtoshow .= '</script>' . "\n";
	}

	/**
	 * Output HTML string to show graph
	 *
	 * @param	int|string		$shownographyet    Show graph to say there is not enough data or the message in $shownographyet if it is a string.
	 * @return	string							   HTML string to show graph
	 */
	public function show($shownographyet = 0)
	{
		global $langs;

		if ($shownographyet)
		{
			$s = '<div class="nographyet" style="width:' . (preg_match('/%/', $this->width) ? $this->width : $this->width . 'px').'; height:' . (preg_match('/%/', $this->height) ? $this->height : $this->height . 'px') . ';"></div>';
			$s .= '<div class="nographyettext margintoponly">';
			if (is_numeric($shownographyet)) {
				$s .= $langs->trans("NotEnoughDataYet") . '...';
			} else {
				$s .= $shownographyet.'...';
			}
			$s .= '</div>';
			return $s;
		}

		return $this->stringtoshow;
	}
}
