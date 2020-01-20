{include file="_header.tpl" pagetitle="$contentheader"}

<script language="javascript" src="{$rooturl}templates/js/funciones.js"></script>
	
<div class="titulo-icono" id="titulo-icono">
	     <div class="titulo" id="titulo">Bit&aacute;cora de Acceso de Clientes </div>
</div>
 

<div class="subtitulo" >
<br />
	     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	     &nbsp;Defina los criterios necesarios y de clic en 'Generar reporte' para ver la bitácora de clientes. <br /><br />
</div>
 
<div class="tabla" id="tabla">



<form name="forma">
<input type="hidden" name="fecha_hoy" value="{$fecha}" />
<table width="501">
	<tr class="nom_campo">
	  
    	
        <td " align="left"><div align="left">Fechas:</div></td>
        <td " align="left"><div align="left"></div></td>
      </tr>
	<tr>
	  <td width="44" class="nom_campo"><div align="left">Desde</div></td>
        <td width="151" class="nom_campo"><div align="left">
          <input  type="text" name="fechaIni" size="10" value="{$fecha}" maxlength="11" id="fechaIni" onfocus="calendario(this);"/>
        </div></td>
	</tr>
		<tr>
		  <td><div align="left"><span class="nom_campo">Hasta</span></div></td>
		  <td><div align="left"><span class="nom_campo">
		    <input  type="text" name="fechaFin" size="10" value="{$fecha}" maxlength="11" id="fechaFin" onfocus="calendario(this);"/>
	      </span></div></td>
	  </tr>
		<tr>
    	<td><div align="left"></div></td>
        <td><div align="left"></div></td>
	</tr>
    <tr>
    	<td><div align="left"></div></td>
        <td><div align="left"></div></td>
    </tr>
    <tr>
    	<td colspan="7" align="right"><input name="button" type="button" class="boton" onclick="buscar(this.form);" value="  Generar Reporte  "/></td>
    </tr>
</table>
</form>
<br><br>
</div>






{literal}
<script type="text/javascript" language="javascript">
/* cambiar a un archivo js especial de este template */

function buscar(objform)
{
	
	//es necesario seleccionar al menos un ususario, y el rango de fechas
	var arrselected= new Array(); 
	
    // es necesario seleccionar un rango de fechas
	if (esFechaValida(objform.elements["fechaIni"].value)==false)
	{
		alert("La fecha inicial es inválida");
		objform.elements["fechaIni"].focus();
		return false;
	}
	
	if (esFechaValida(objform.elements["fechaFin"].value)==false)
	{
		alert("La fecha final es inválida");
		objform.elements["fechaFin"].focus();
		return false;
	}
	
	//alert("Conexión a base pagina no exitosa");
	//return false;
	
	var strusu=arrselected.toString();
	var stroperaciones="";
	var fecha_Ini=objform.elements["fechaIni"].value;
	var fecha_Fin=objform.elements["fechaFin"].value;
	
	
	
	var dia =parseInt(fecha_Ini.substr(0,3),10);
	var mes  =parseInt(fecha_Ini.substring(3,6),10);
	var anio  = parseInt(fecha_Ini.substring(6,10),10)
		
	fecha_Ini= anio + '-' + mes+ '-' +dia;
	
	var dia =parseInt(fecha_Fin.substr(0,3),10);
	var mes  =parseInt(fecha_Fin.substring(3,6),10);
	var anio  = parseInt(fecha_Fin.substring(6,10),10)
		
	fecha_Fin= anio + '-' + mes+ '-' +dia;
	
	//alert(strselect);
	
	//si todo esta buen mandamos a la parte de reportes para que muestre el reporte
	var centroAncho = (screen.width/2) - 400;
	var centroAlto = (screen.height/2) - 300;
	var especificaciones="top="+centroAlto+",left="+centroAncho+",toolbar=no,location=no,status=no,menubar=yes,scrollbars=yes,width=800,height=600,resizable=yes"
	var titulo="ventanaEmergente"
	window.open("reportes/procesaReportes.php?parametros=&opcion=&idRep=100&opcionales=&titulo=Bitácora Clientes&fechaIni="+fecha_Ini+"=&fechaFin="+fecha_Fin+"&usu="+strusu+"&operacion="+stroperaciones+"","_blank",especificaciones);
		//vamos a ver si ya existe la ventana abierta
	//alert("abriendo ventana");
	return true;
	
}
//funciones para validar fecha valida
function esFechaValida(fecha){
    if (fecha != undefined && fecha.value != "" )
	{
       
	  /* 
        var anio  =  parseInt(fecha.substring(0,3),10);
        var mes  =  parseInt(fecha.substring(3,6),10);
        var dia=  parseInt(fecha.substring(6,10),10);*/
		
		//fecha=fecha.substr(0,10);
		var dia =parseInt(fecha.substr(0,3),10);
	 	var mes  =parseInt(fecha.substring(3,6),10);
 		var anio  = parseInt(fecha.substring(6,10),10)
		
		//alert (anio + '-' + mes+ '-' +dia);
	   
	    	
		//solo limitamos a los primeros diez caracteres de la fecha
		/*fecha=fecha.substring(0,10);
		if (!/^\d{2}\-\d{2}\-\d{4}$/.test(fecha))
		{
           alert(fecha);
		   return false;
        }*/
		
		
		switch(mes){
			case 1:
			case 3:
			case 5:
			case 7:
			case 8:
			case 10:
			case 12:
				numDias=31;
				break;
			case 4: case 6: case 9: case 11:
				numDias=30;
				break;
			case 2:
				if (comprobarSiBisisesto(anio)){ numDias=29 }else{ numDias=28};
				break;
			default:
			   
				return false;
		}
    
		if (dia>numDias || dia==0){
        	return false;
	    }
    	//para terminar
		return true;
	
	}
}

</script>
{/literal}


{include file="_footer.tpl" aktUser=$username}