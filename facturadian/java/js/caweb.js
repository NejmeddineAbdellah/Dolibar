/*Author: Miguel Angel Vargas
 *Fecha: 2010-12-01
 *Descripcion: Este archivo contiene las funciones mas genericas del sistema que se han actualizado, se crea este archivo debido
 *  al inconveniente de multiples archivos que hacen lo mismo, la finalidad es unificar el archivo en esta ruta para que todos los programas
 *  apunten a este.*/

function funcion_subclases(str,val,met) {
    //str: Recibe el valor de la clase
    //val: Parametro opcional 1-Solo Subclases que se manejan por cantidad,2-Solo las que hacen parte del despiece
    //met: Define que hacer cuando se presenta el evento change 1-Session,2-Valor
        $("#layer2_grupos").html("");
        $("#layer2_subclases").load("../scripts/layer2_subclases.php",{q:str,opc:val,metodo:met},function(){
            //$('#descripcionsub').trigger("change");
        });
        return;
}

function funcion_familias(str,val,met) {
    //str: Recibe el valor de la subclase
    //val: Parametro opcional 1-Solo Subclases que se manejan por cantidad,2-Solo las que hacen parte del despiece
    //met: Define que hacer cuando se presenta el evento change 1-Session,2-Valor
        $("#layer2_familias").load("../scripts/layer2_familias.php",{cla:$("#clasetx").val(),q:str,opc:val,metodo:met},function(){
            //$('#familia').trigger("change");
        });
}

function funcion_grupos(str,val,met) {
    //str: Recibe el valor de la familia
    //val: Parametro opcional 1-Solo Subclases que se manejan por cantidad,2-Solo las que hacen parte del despiece
    //met: Define que hacer cuando se presenta el evento change 1-Session,2-Valor
        $("#layer2_grupos").load("../scripts/layer2_grupos.php",{cla:$("#clasetx").val(),subcla:$("#descripcionsub").val(),q:str,opc:val,metodo:met},function(){
            //$('#gruposTaxo').trigger("change");
        });
}

function funcion_pasavalor(campo,valor) {
    if (valor != "") campo.val(valor);
    return;
}

function funcion_pasasesion(vacampo,vavalor,vopc) {
	$.post("pasasesion.php",{campo:vacampo,valor:vavalor,opc:vopc});
}

function formato_numero(num){ //funcion para dar formato de moneda
//Autor :  Roberto Herrero & Daniel
//Web: http://www.indomita.org
//Asunto : Dar formato a un número
var cadena = ""; var aux;

var cont = 1,m,k;

if(num<0) aux=1; else aux=0;

num=num.toString();



for(m=num.length-1; m>=0; m--){

 cadena = num.charAt(m) + cadena;

 if(cont%3 == 0 && m >aux)  cadena = "." + cadena; else cadena = cadena;

 if(cont== 3) cont = 1; else cont++;

}

cadena = cadena.replace(/.,/,",");

return cadena;

}

