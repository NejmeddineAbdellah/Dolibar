<?php

function tool_tip($form, $text, $new_row=0) {
	$htmltooltip='';
	$form->textwithpicto('',$htmltooltip,1,0);
	
	//return $form;
}

function get_max_rows_per_page($db) {
	global $conf;
	$r = 50;
	$sql = "SELECT value FROM ".MAIN_DB_PREFIX."const WHERE name = 'CONTAB_MAX_ROWS_PER_PAGE' AND entity = ".$conf->entity;
	$res = $db->query($sql);
	if ($res) {
		$row = $db->fetch_array($res);
		$r = $row[0];
	}
	dol_syslog("max_rows=$r, sql=$sql");
	return $r;
}

if (!function_exists('money_format')) {
	function money_format($format, $number) {
		$regex = array( '/%((?:[\^!\-]|\+|\(|\=.)*)([0-9]+)?(?:#([0-9]+))?',
				'(?:\.([0-9]+))?([in%])/'
		);
		$regex = implode('', $regex);
		if (setlocale(LC_MONETARY, null) == '') {
			setlocale(LC_MONETARY, 'es_MX');
		}
		$locale = localeconv();
		$number = floatval($number);
		if (!preg_match($regex, $format, $fmatch)) {
			trigger_error("No format specified or invalid format", E_USER_WARNING);
			return $number;
		}
		$flags = array( 'fillchar'	=> preg_match('/\=(.)/', $fmatch[1], $match) ? $match[1] : ' ',
				'nogroup'	=> preg_match('/\^/', $fmatch[1]) > 0,
				'usesignal'	=> preg_match('/\+|\(/', $fmatch[1], $match) ? $match[0] : '+',
				'nosimbol'	=> preg_match('/\!/', $fmatch[1]) > 0,
				'isleft'	=> preg_match('/\-/', $fmatch[1]) > 0
		);
		$width	= trim($fmatch[2]) ? (int)$fmatch[2] : 0;
		$left	= trim($fmatch[3]) ? (int)$fmatch[3] : 0;
		$right	= trim($fmatch[4]) ? (int)$fmatch[4] : $locale['int_frac_digits'];
		$conversion = $fmatch[5];
		$positive = true;
		if ($number < 0) {
			$positive = false;
			$number *= -1;
		}
		$letter = $positive ? 'p' : 'n';
		$prefix = $suffix = $cprefix = $csuffix = $signal = '';
		if (!$positive) {
			$signal = $locale['negative_sign'];
			switch (true) {
				case $locale['n_sign_posn'] == 0 || $flags['usesignal'] == '(':
					$prefix = '(';
					$suffix = ')';
					break;
				case $locale['n_sign_posn'] == 1:
					$prefix = $signal;
					break;
				case $locale['n_sign_posn'] == 2:
					$suffix = $signal;
					break;
				case $locale['n_sign_posn'] == 3:
					$cprefix = $signal;
					break;
				case $locale['n_sign_posn'] == 4:
					$csuffix = $signal;
					break;
			}
		}
		if (!$flags['nosimbol']) {
			$currency = $cprefix;
			$currency .= ($conversion == 'i' ? $locale['int_curr_symbol'] : $locale['currency_symbol']);
			$currency .= $csuffix;
			$currency = iconv('ISO-8859-1','UTF-8',$currency);
		} else {
			$currency = '';
		}
		$space = $locale["{$letter}_sep_by_space"] ? ' ' : '';

		$number = number_format($number, $right, $locale['mon_decimal_point'], $flags['nogroup'] ? '' : $locale['mon_thousands_sep'] );
		$number = explode($locale['mon_decimal_point'], $number);

		$n = strlen($prefix) + strlen($currency);
		if ($left > 0 && $left > $n) {
			if ($flags['isleft']) {
				$number[0] .= str_repeat($flags['fillchar'], $left - $n);
			} else {
				$number[0] = str_repeat($flags['fillchar'], $left - $n) . $number[0];
			}
		}
		$number = implode($locale['mon_decimal_point'], $number);
		if ($locale["{$letter}_cs_precedes"]) {
			$number = $prefix . $currency . $space . $number . $suffix;
		} else {
			$number = $prefix . $number . $space . $currency . $suffix;
		}
		if ($width > 0) {
			$number = str_pad($number, $width, $flags['fillchar'], $flags['isleft'] ? STR_PAD_RIGHT : STR_PAD_LEFT);
		}
		$format = str_replace($fmatch[0], $number, $format);
		return $format;
	}	//	function money_format()
}
?>