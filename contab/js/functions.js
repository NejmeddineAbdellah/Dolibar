function select_all_factures() {
	event.preventDefault();
	$(':checkbox').prop('checked',true);
}

function unselect_all_factures() {
	event.preventDefault();
	$(':checkbox').prop('checked',false);
}

function refresh_month(obj) {
	anio = $("#anio").val();
	//alert(anio);
	$("#mes").html("");
	var path1 = getAbsolutePath() + "../process/select_change_month.php";
	$.ajax({
		type: "POST",
		url: path1,
		data: {anio : anio}
	}).done( function (data) {
		$("#mes").html("");
		$("#mes").html(data);
		
		$( document ).ready(function() {
			
		});
	});
}

function fac_change_chk() {
	if ($('input[name=select_all]').is(':checked')) {
		$('input').attr('checked', true);
	} else {
		$('input').attr('checked', false);
	}
}

function fac_proc_now(id, tipo_fac) {
	var path1 = getAbsolutePath() + "../../process/create_poliza.php";
	$.ajax({
		type: "POST",
		url: path1,
		data: { id : id, tipo_fac : tipo_fac }
	}).done( function (data) {
		//alert("Procesado!!")
		$("#tdprocessnow" + id).html("Procesado !!!");
	});
}

function submit_this() {
	document.getElementById("frmform").submit();
}

function change_tipo_tercero(soc_type) {
	//alert(soc_type);
	var path1 = getAbsolutePath() + "../process/newpol_fill_ddl.php";
	var fecha = document.getElementById("fecha").value;
	var facid = document.getElementById("facid").value;
	
	$.ajax({
		type: "POST",
		url: path1, 
		data: {soc_type : soc_type, fecha : fecha, facid : facid}
	}). done(function (data) {
		//alert(data);
		$("#div_fk_facture").html(data);
	});
}

function change_tipo_rel(soc_type) {
	//alert(soc_type);
	var path1 = getAbsolutePath() + "../process/newpol_fill_ddl.php";
	var fecha = document.getElementById("fecha").value;
	var facid = document.getElementById("facid").value;
	//alert(facid);
	
	$.ajax({
		type: "POST",
		url: path1, 
		data: {soc_type : soc_type, fecha : fecha, facid : facid}
	}). done(function (data) {
		//alert(data);
		$("#div_fk_facture").html(data);
	});
}
function change_tipo_rel2(soc_type) {
	//alert(soc_type);
	var path1 = getAbsolutePath() + "../process/newpol_fill_ddl.php";
	$.ajax({
		type: "POST",
		url: path1, 
		data: {soc_type : soc_type}
	}). done(function (data) {
		//alert(data);
		$("#div_fk_facture").html(data);
	});
}

function save_valida(id, value, anio, mes, pa_val) {
	//alert(getAbsolutePath());
	//alert(id+' '+value+' '+anio+' '+mes);
	//var bg = $('#bg_'+anio+mes).is(':checked');
	//var bc = $('#bc_'+anio+mes).is(':checked');
	//var er = $('#er_'+anio+mes).is(':checked');
	//var ld = $('#ld_'+anio+mes).is(':checked');
	//var lm = $('#lm_'+anio+mes).is(':checked');
	
	var path1 = getAbsolutePath() + "../process/update_validation_field.php";
	//var path2 = getAbsolutePath() + "/process/update_div_open_close.php";
	var jqxhr = $.ajax({
		type: "POST",
		url: path1,
		data: { id : id, value : value, anio : anio, mes : mes }
	});
	//alert("Termino 1");
	//jqxhr.always(function () {
		//alert("Termino 2");
		/*var jqxhr2 = $.ajax({
			type: "POST",
			url: path2,
			data: { anio : anio, mes : mes, pa_val : pa_val }
		});
		jqxhr2.done(function (data) {
			$('#td_'+anio+mes).innerHTML(data);
			alert(data);*/
		//});
		//alert("Termino 3");
	//});
	
	//if (bg && bc && er && ld && lm) {
	//	//$('#th_opcion').css("visibility", "visible");
	//	$('#td_opcion_'+anio+mes).css("visibility", "visible");
	//	//alert("Visible");
	//} else {
		//$('#th_opcion').css("visibility", "hidden");
	//	$('#td_opcion_'+anio+mes).css("visibility", "hidden");
		//alert("Hidden");
	//}
}
function save_valida2(anio, mes) {
	save_valida('bg', true, anio, mes, 1);
	save_valida('bc', true, anio, mes, 1);
	save_valida('er', true, anio, mes, 1);
	save_valida('ld', true, anio, mes, 1);
	save_valida('lm', true, anio, mes, 1);
}
function save_max_rows_per_page(r, tipo_cat) {
	//var x = document.getElementById("catalogos").innerHTML;
	//var x = $("#catalogo").html();
	//event.preventDefault();
	//alert("Hola=" + x);
	if (tipo_cat == 1) {
		$("#catalogo").html("<br><br><strong>Procesando Petición...</strong>");
	} else if (tipo_cat == 2) {
		$("#catalogo_ppal").html("<br><br><strong>Procesando Petición...</strong>");
	}
	var path = getAbsolutePath() + "../process/update_max_rows_per_page.php";
	$.ajax({
		type: "POST",
		url: path,
		data: { r : r, tipo_cat : tipo_cat }
	}).done(function (data) {
		if (tipo_cat == 1) {
			$("#catalogo").html(data);
		} else if(tipo_cat == 2) {
			$("#catalogo_ppal").html(data);
		}
		//document.getElementById("catalogos").innerHTML = data;
		
		$( document ).ready(function() {
			var tp = $("#total_pages").val();
			//alert(tp);
			
			$("#pagina").html("Página: 1 de "+tp+" pág(s).");
			
			var option = '';
			var i = 1;
			while (i <= tp) {
				if (i == 1) {
					option += '<option value="' + i + '" selected="selected">' + i + '</option>';
				} else {
					option += '<option value="' + i + '">' + i + '</option>';
				}
				i ++
			}
			//alert(option);
            $('#page').html(option);
		});
	});
}

function getAbsolutePath() {
    var loc = window.location;
    var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
    return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
}

function habilitar_campos() {
	event.preventDefault();
	/*var cant_pagada = document.getElementById ( "cant_pagada" ) ;
	cant_pagada.style.visibility = "visible";*/
	var npace = document.getElementById("npace");
	npace.style.visibility = "visible";
	var npacc = document.getElementById("npacc");
	npacc.style.visibility = "visible";
}

/*function change_consecutivo() {
	//alert("Hola");
	var tp = document.getElementById("tipo_pol").value;
	var path = getAbsolutePath() + "/process/polizas_get_last_cons.php";
	$.ajax({
  		type: "POST",
  		url: path,
  		data: { tipo_pol : tp }
	}).done( function (data) {
		//alert(data);
		var cons = parseInt(data) + 1;
		//alert(cons);
		document.getElementById("cons").value = cons;
	});
}*/

function validaFechaDDMMAAAA(fecha){
	var dtCh= "/";
	var minYear=1900;
	var maxYear=2100;
	function isInteger(s){
		var i;
		for (i = 0; i < s.length; i++){
			var c = s.charAt(i);
			if (((c < "0") || (c > "9"))) return false;
		}
		return true;
	}
	function stripCharsInBag(s, bag){
		var i;
		var returnString = "";
		for (i = 0; i < s.length; i++){
			var c = s.charAt(i);
			if (bag.indexOf(c) == -1) returnString += c;
		}
		return returnString;
	}
	function daysInFebruary (year){
		return (((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0))) ? 29 : 28 );
	}
	function DaysArray(n) {
		for (var i = 1; i <= n; i++) {
			this[i] = 31
			if (i==4 || i==6 || i==9 || i==11) {this[i] = 30}
			if (i==2) {this[i] = 29}
		}
		return this
	}
	function isDate(dtStr){
		var daysInMonth = DaysArray(12)
		var pos1=dtStr.indexOf(dtCh)
		var pos2=dtStr.indexOf(dtCh,pos1+1)
		var strDay=dtStr.substring(0,pos1)
		var strMonth=dtStr.substring(pos1+1,pos2)
		var strYear=dtStr.substring(pos2+1)
		strYr=strYear
		if (strDay.charAt(0)=="0" && strDay.length>1) strDay=strDay.substring(1)
		if (strMonth.charAt(0)=="0" && strMonth.length>1) strMonth=strMonth.substring(1)
		for (var i = 1; i <= 3; i++) {
			if (strYr.charAt(0)=="0" && strYr.length>1) strYr=strYr.substring(1)
		}
		month=parseInt(strMonth)
		day=parseInt(strDay)
		year=parseInt(strYr)
		if (pos1==-1 || pos2==-1){
			return false
		}
		if (strMonth.length<1 || month<1 || month>12){
			return false
		}
		if (strDay.length<1 || day<1 || day>31 || (month==2 && day>daysInFebruary(year)) || day > daysInMonth[month]){
			return false
		}
		if (strYear.length != 4 || year==0 || year<minYear || year>maxYear){
			return false
		}
		if (dtStr.indexOf(dtCh,pos2+1)!=-1 || isInteger(stripCharsInBag(dtStr, dtCh))==false){
			return false
		}
		return true
	}
	if(isDate(fecha)){
		return true;
	}else{
		return false;
	}
}