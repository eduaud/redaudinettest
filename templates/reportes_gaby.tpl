<link href="../../css/estilos.css" rel="stylesheet" type="text/css" />
<link href="../../css/gridSW.css" rel="stylesheet" type="text/css" />

{include file="_header.tpl" pagetitle="$contentheader"}

<script language="JavaScript" type="text/javascript" src="{$rooturl}js/jquery/jquery.js"></script>
  <div class="titulo-icono" id="titulo-icono">
	     <div class="titulo" id="titulo">{$titulo} </div>
 </div>

<div>

<form action="#" method="post" name="formax">
<input type="hidden" name="titulo" value="{$titulo}">
<input type="hidden" name="campoTexto" value="">
<input type="hidden" name="selSuc" id="selSuc" value="-1"  />



<table cellspacing="20px" style="background-color:#CCC" width="100%" align="left">
<tr>
{if $reporte eq 1 or $reporte eq 2 or $reporte eq 3 or $reporte eq 4 or $reporte eq 5 or $reporte eq 7 or $reporte eq 8 or $reporte eq 10 or $reporte eq 11 or $reporte eq 12 or $reporte eq 13 or $reporte eq 14}
<td>
	<h4>Sucursales</h4>
	<select name="id_sucursal" size="5" multiple="multiple" class="campos" id="id_sucursal" >
    	{html_options values=$id_sucursal output=$nom_sucursal }
    </select></td>
{/if}

{if $reporte eq 100 or $reporte eq 101 or $reporte eq 102 }
<td>
	<h4>Sucursales</h4>
	<select name="id_sucursal_unica" class="campos" id="id_sucursal_unica" onchange="muestraAlmacenes();" >
	  <option value="0">Seleccione una sucursal</option>
	  
    	{html_options values=$id_sucursal output=$nom_sucursal }
    </select></td>
{/if}
{if $reporte eq 100 or $reporte eq 101 or $reporte eq 102 }
<td>
	<h4>Almacenes</h4>
	<select name="id_almacen_unica" class="campos" id="id_almacen_unica" >
    	{html_options values=$id_alamcen output=$nom_almacen }
    </select></td>
{/if}

 
{if $reporte eq 1 or $reporte eq 2 or $reporte eq 3 or $reporte eq 4 or $reporte eq 5}
<td>
	<h4>Recepcionistas</h4>
	<select name="id_recepcionista" size="5" multiple="multiple" class="campos" id="id_recepcionista" >
    	{html_options values=$id_recepcionista output=$nom_recepcionista }
    </select></td>
{/if}

 
{if $reporte eq 6}
<td>
	<h4>Recepcionistas</h4>
	<select name="id_cliente" size="5" multiple="multiple" class="campos" id="id_cliente" >
    	{html_options values=$id_cliente output=$nom_cliente }
    </select></td>
{/if}

 
{if $reporte eq 1 or $reporte eq 2 or $reporte eq 3 or $reporte eq 4 or $reporte eq 5 or $reporte eq 7 or $reporte eq 8 or $reporte eq 10 or $reporte eq 11 or $reporte eq 12 or $reporte eq 13 or $reporte eq 14 or $reporte eq 101 or $reporte eq 102}
    <td align="left">
    <h4>Fecha Inicial</h4>
    <input type="text" name="fec_ini" id="fec_ini" maxlength="14" size="18" class="campos_req" onfocus="calendario(this);" readonly title="Tipo fecha (dd/mm/aaaa)">		
    <br /><br />
	<h4>Fecha Final</h4> 
    <input type="text" name="fec_fin" id="fec_fin" maxlength="14" size="18" class="campos_req" onfocus="calendario(this);" readonly title="Tipo fecha (dd/mm/aaaa)">
</td>
{/if}
 
</tr>
</table>



</form>
<br />
<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<!-- Reportes que contendran el campo de orden -->
				{if 0}
				<tr style="height:60px;">
					<td>&nbsp;</td>
				</tr>
				{/if}
				<tr valign="middle">
					<td height="26">
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr align="left">
								
<!--Botonera-->
								<td align="left" class="user">
									
						<br>				
				<input type="button" value="Generar reporte" class="boton" onclick="generaReporte(1,{$reporte})"/>
                <input type="button" value="Exportar a Excel" class="boton" onclick="generaReporte(2,{$reporte})"/>
										
                                        </td>
<!--Fin de botonera-->
                      
						  </tr>
						</table>
				  </td>
				</tr>
				<tr valign="top">
					<td class="lines" height="1"><img src="{$imgpathmaster}/space.gif" alt="" width="1" height="1"></td>
				</tr>
			</table>

 </div>
{literal}
<script type="text/javascript" language="javascript">
//--Verifica para que sucursal se Filtrara-------------------------------//

//------------------------------------------------------------//

	


function generaReporte(opcion,idRep)
{
	var centroAncho = (screen.width/2) - 400;
	var centroAlto = (screen.height/2) - 300;
	
	
	var id_almacen=document.getElementById("id_almacen_unica").value;
	if((idRep==101 || idRep==102 || idRep==103) && id_almacen=='' )
	{
		alert("Es necesario seleccionar un almacen valido.");
		return false;
	}
	
	
	var parametros=obtenParametrosReporte(idRep);
	
	var especificaciones="top="+centroAlto+",left="+centroAncho+",toolbar=no,location=no,status=no,menubar=yes,scrollbars=yes,width=800,height=600,resizable=yes"
	var titulo="ventanaEmergente";
	
	window.open("procesaReportes.php?opcion="+opcion+"&idRep="+idRep+"&parametros="+parametros,"_blank", especificaciones);
	
	//vamos a ver si ya existe la ventana abierta
	//alert("abriendo ventana");
	return true;
}


function obtenParametrosReporte(idRep)
{
	//
	var parametros="";
	
	
	if(idRep==1)
	{
		var id_sucursal=obtenIdsSeleccionados("id_sucursal");
		var id_recepcionista=obtenIdsSeleccionados("id_recepcionista");
		parametros="id_sucursal@"+id_sucursal+"~id_recepcionista@"+id_recepcionista+"~fec_ini@"+$("#fec_ini").val()+"~fec_fin@"+$("#fec_fin").val();
	}
	else if(idRep==2)
	{
		var id_sucursal=obtenIdsSeleccionados("id_sucursal");
		var id_recepcionista=obtenIdsSeleccionados("id_recepcionista");
		parametros="id_sucursal@"+id_sucursal+"~id_recepcionista@"+id_recepcionista+"~fec_ini@"+$("#fec_ini").val()+"~fec_fin@"+$("#fec_fin").val();
	}
	else if(idRep==3)
	{
		var id_sucursal=obtenIdsSeleccionados("id_sucursal");
		var id_recepcionista=obtenIdsSeleccionados("id_recepcionista");
		parametros="id_sucursal@"+id_sucursal+"~id_recepcionista@"+id_recepcionista+"~fec_ini@"+$("#fec_ini").val()+"~fec_fin@"+$("#fec_fin").val();
	}
	else if(idRep==4)
	{
		var id_sucursal=obtenIdsSeleccionados("id_sucursal");
		var id_recepcionista=obtenIdsSeleccionados("id_recepcionista");
		parametros="id_sucursal@"+id_sucursal+"~id_recepcionista@"+id_recepcionista+"~fec_ini@"+$("#fec_ini").val()+"~fec_fin@"+$("#fec_fin").val();
	}
	else if(idRep==5)
	{
		var id_sucursal=obtenIdsSeleccionados("id_sucursal");
		var id_recepcionista=obtenIdsSeleccionados("id_recepcionista");
		parametros="id_sucursal@"+id_sucursal+"~id_recepcionista@"+id_recepcionista+"~fec_ini@"+$("#fec_ini").val()+"~fec_fin@"+$("#fec_fin").val();
	}
	else if(idRep==6)
	{
		var id_cliente=obtenIdsSeleccionados("id_cliente");
		parametros="id_cliente@"+id_cliente;
	}
	else if(idRep==7)
	{
		var id_sucursal=obtenIdsSeleccionados("id_sucursal");
		parametros="id_sucursal@"+id_sucursal+"~fec_ini@"+$("#fec_ini").val()+"~fec_fin@"+$("#fec_fin").val();
	}
	else if(idRep==8)
	{
		var id_sucursal=obtenIdsSeleccionados("id_sucursal");
		parametros="id_sucursal@"+id_sucursal+"~fec_ini@"+$("#fec_ini").val()+"~fec_fin@"+$("#fec_fin").val();
	}
	else if(idRep==10)
	{
		var id_sucursal=obtenIdsSeleccionados("id_sucursal");
		parametros="id_sucursal@"+id_sucursal+"~fec_ini@"+$("#fec_ini").val()+"~fec_fin@"+$("#fec_fin").val();
	}
	else if(idRep==11)
	{
		var id_sucursal=obtenIdsSeleccionados("id_sucursal");
		parametros="id_sucursal@"+id_sucursal+"~fec_ini@"+$("#fec_ini").val()+"~fec_fin@"+$("#fec_fin").val();
	}
	else if(idRep==12)
	{
		var id_sucursal=obtenIdsSeleccionados("id_sucursal");
		parametros="id_sucursal@"+id_sucursal+"~fec_ini@"+$("#fec_ini").val()+"~fec_fin@"+$("#fec_fin").val();
	}
	else if(idRep==13)
	{
		var id_sucursal=obtenIdsSeleccionados("id_sucursal");
		parametros="id_sucursal@"+id_sucursal+"~fec_ini@"+$("#fec_ini").val()+"~fec_fin@"+$("#fec_fin").val();
	}
	else if(idRep==14)
	{
		var id_sucursal=obtenIdsSeleccionados("id_sucursal");
		parametros="id_sucursal@"+id_sucursal+"~fec_ini@"+$("#fec_ini").val()+"~fec_fin@"+$("#fec_fin").val();
	}
	
	else if(idRep==100)
	{//reportes de alamacen
		var id_sucursal=document.getElementById("id_sucursal_unica").value;
		var id_almacen=document.getElementById("id_almacen_unica").value;
		
		$('#id_almacen_unica option:selected').html();
		
		parametros=
		"id_sucursal@"+id_sucursal+"~id_almacen@"+id_almacen+"~nom_suc@"+document.getElementById("id_sucursal_unica").text+"~nom_alm@"+document.getElementById("id_almacen_unica").text;
	}
	else if(idRep==101)
	{
		var id_sucursal=document.getElementById("id_sucursal_unica").value;
		var id_almacen=document.getElementById("id_almacen_unica").value;
		
		parametros="id_sucursal@"+id_sucursal+"~fec_ini@"+$("#fec_ini").val()+"~fec_fin@"+$("#fec_fin").val()+"~id_almacen@"+id_almacen;
	}
	else if(idRep==102)
	{
		var id_sucursal=document.getElementById("id_sucursal_unica").value;
		var id_almacen=document.getElementById("id_almacen_unica").value;
			
		parametros="id_sucursal@"+id_sucursal+"~fec_ini@"+$("#fec_ini").val()+"~fec_fin@"+$("#fec_fin").val()+"~id_almacen@"+id_almacen;
	}
	
	
	
	return parametros;
}

function obtenIdsSeleccionados(id_control)
{
	//var obj=document.getElementById(id_control);
	
	var combo = document.getElementById(id_control);
  	var selected ="0";
	var tam=combo.length;
  
	for(var i=0; i <tam; i++ )
   	{
		if(combo.options[i].selected==true)
		{
			selected=selected+'|'+combo.options[i].value
		 }
	}
   	
	//eliminamos el ultimo |
	//selected=selected.substring(0, selected.length-1);
	
	return selected;
}		

function muestraAlmacenes()
{
	var id_suc=document.getElementById('id_sucursal_unica').value;

		//de la sucursal seleccionada buscamos los almacenes relacionadados para realizar la salida
	objCombo = document.getElementById('id_almacen_unica');

	
	limpiaCombo(objCombo);
  // alert("doc "+objTipoDoc.value);
	var respuesta=ajaxR("../ajax/obtenAlmacenes.php?tipo=1&id_suc="+id_suc+"&$idAlamacen=0");
	var arrResp=respuesta.split("|");
	if(arrResp[0]!="exito")
	{
		alert(respuesta);
		return false;
	}
	if(arrResp[1]>0)
	{
		var numDatos=parseInt(arrResp[1]);		
		for(var i=0;i<numDatos;i++)
		{
			var arrDatos=arrResp[i+2].split("~");			
			objCombo.options[i]=new Option(arrDatos[1], arrDatos[0]);
		}
	}
		
		
}

</script>
{/literal}





{include file="_footer.tpl" aktUser=$username}
