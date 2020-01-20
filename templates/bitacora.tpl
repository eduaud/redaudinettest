{include file="_header.tpl" pagetitle="$contentheader"}

<script language="javascript" src="{$rooturl}templates/js/funciones.js"></script>
	
<div class="titulo-icono" id="titulo-icono">
	     <div class="titulo" id="titulo">Bit&aacute;cora de Operaciones </div>
</div>
 

<div class="subtitulo" >
<br />
	     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	     &nbsp;Defina los criterios necesarios y de clic en 'Generar reporte' para ver la bit&aacute;cora de operaciones. <br /><br />
</div>
 
<div class="tabla" id="tabla">



<form name="forma">
<input type="hidden" name="fecha_hoy" value="{$fecha}" />
<table width="501">
	<tr class="nom_campo">
	  <td width="81" align="left" >Usuario:</td>
    	<td width="34" align="left" >&nbsp;</td>
        <td width="129" align="left" >Operaciones:</td>
        <td colspan="2" rowspan="5">&nbsp;</td>
        <td colspan="2" align="left">Fechas:</td>
      </tr>
	<tr>
	  <td rowspan="4"><select name="id_usuarios" size="7" multiple="multiple" class="campos_req">
	    
    	  
	           {html_options values=$usuarios[0] output=$usuarios[1] selected=$id_usuario }
		    
  	  
    	
	    </select>	  </td>
    	<td rowspan="4">&nbsp;&nbsp;&nbsp;</td>
        <td rowspan="4" align="left" class="nom_campo"><select name="id_operaciones" size="7" multiple="multiple" class="campos_req">
          
	          {html_options values=$operaciones[0] output=$operaciones[1] selected=$id_operacion }      
		    
        
        </select>        </td>
        <td width="44" class="nom_campo">Desde</td>
        <td width="151" class="nom_campo"><input  type="text" name="fechaIni" size="10" value="{$fecha}" maxlength="11" id="fechaIni" onfocus="calendario(this);"/></td>
	</tr>
		<tr>
		  <td><span class="nom_campo">Hasta</span></td>
		  <td><span class="nom_campo">
		    <input  type="text" name="fechaFin" size="10" value="{$fecha}" maxlength="11" id="fechaFin" onfocus="calendario(this);"/>
		  </span></td>
	  </tr>
		<tr>
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
	</tr>
    <tr>
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
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
	for (var i = 0; i < objform.id_usuarios.options.length; i++) 
	 	if (objform.id_usuarios.options[ i ].selected) 
			arrselected.push(objform.id_usuarios.options[ i ].value);
	if (arrselected.length ==0)
	{
		alert("Es necesario seleccionar al menos un Usuario")	;
		return false;
	}
	
	
	var arrOperaciones= new Array(); 
	for (var i = 0; i < objform.id_operaciones.options.length; i++) 
	 	if (objform.id_operaciones.options[ i ].selected) 
			arrOperaciones.push(objform.id_operaciones.options[ i ].value);
	
	
	
    // es necesario seleccionar un rango de fechas
	if (esFechaValida(objform.elements["fechaIni"].value)==false)
	{
		alert("La fecha inicial es inv&aacute;lida");
		objform.elements["fechaIni"].focus();
		return false;
	}
	
	if (esFechaValida(objform.elements["fechaFin"].value)==false)
	{
		alert("La fecha final es inv&aacute;lida");
		objform.elements["fechaFin"].focus();
		return false;
	}
	var strusu=arrselected.toString();
	var stroperaciones=arrOperaciones.toString();
	var fecha_Ini=objform.elements["fechaIni"].value;
	var fecha_Fin=objform.elements["fechaFin"].value;
	
	//alert(strselect);
	
	//si todo esta buen mandamos a la parte de reportes para que muestre el reporte
	var centroAncho = (screen.width/2) - 400;
	var centroAlto = (screen.height/2) - 300;
	var especificaciones="top="+centroAlto+",left="+centroAncho+",toolbar=no,location=no,status=no,menubar=yes,scrollbars=yes,width=800,height=600,resizable=yes"
	var titulo="ventanaEmergente"
	window.open("reportes/procesaReportes.php?parametros=&opcion=&idRep=100&opcionales=&titulo=Bit&aacute;cora&fechaIni="+fecha_Ini+"=&fechaFin="+fecha_Fin+"&usu="+strusu+"&operacion="+stroperaciones+"","_blank",especificaciones);
	
	//vamos a ver si ya existe la ventana abierta
	//alert("abriendo ventana");
	return true;
	
	
	//------------------------
	
}
//funciones para validar fecha valida
function esFechaValida(fecha){
    if (fecha != undefined && fecha.value != "" )
	{
        
		//solo limitamos a los primeros diez caracteres de la fecha
		fecha=fecha.substring(0,10);
		if (!/^\d{4}\-\d{2}\-\d{2}$/.test(fecha))
		{
           //alert(fecha);
		    return false;
        }
				
        var anio  =  parseInt(fecha.substring(0,4),10);
        var mes  =  parseInt(fecha.substring(5,7),10);
        var dia=  parseInt(fecha.substring(8),10);
		
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