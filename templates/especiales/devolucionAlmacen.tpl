{include file="_header.tpl" pagetitle="$contentheader"}    
<script language="javascript" src="{$rooturl}js/franquicias.js"></script>    
<link href="{$rooturl}css/tabswizard.css" rel="stylesheet" type="text/css">

<link href="{$rooturl}css/jquery-ui.css" rel="stylesheet" type="text/css">

 <br>
<h1> Ingresos Almacen por Devolución de Evento </h1> </div>

  
 
<table border="0" width="90%" >
 <form name="forma1" method="post" action="devolucionAlmacen.php">
<tr>
<input type="hidden" name="accion" id="accion" value="{$accion}" />
<td colspan="5" class="campo_small" ><br> 	 Seleccione los criterios que desee especificar y de clic al botón 'Buscar '.<br><br>
</td>
</tr>


<tr class='nom_campo'>
    <td >Cliente</td>
    <td>
        <select name="id_cliente" class="campos_req" id="id_cliente">
            <option value="0" selected="selected"> - Seleccione un cliente - </option>
                {html_options values=$arrysIdCliente output=$arrysNombreCliente selected=$idCliente }
        </select>
    </td>
    <td >Orden de Sevicio </td>
    <td><select name="id_orden_servicio" class="campos_req" id="id_orden_servicio">
      <option value="0" selected="selected"> - Seleccione tipo de salida - </option>
                        {html_options values=$arrysIdOS output=$arrysNombreOS selected=$idOS }
    
    </select></td>
</tr>


<tr class='nom_campo'>
  <td>Fecha de Recolecci&oacute;n</td>
  <td align="center"><input name="fecha_inicio" type="text" class="campos_req" id="fecha_inicio" size="10"  onfocus="calendario(this);"/>
    al
    <input name="fecha_fin" type="text" class="campos_req" id="fecha_fin" size="10"  onfocus="calendario(this);"/></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  
</tr>
<tr class='nom_campo'>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td><input name="btnBuscar" type="button" class="boton" value="Buscar      &raquo;" onClick="buscar();" /></td>

</tr>
</form>

</table>   
<br> 
  <br> 

<!-- Listado de información de ingreso a almacen -->

{if $encontrados neq 0}
<form name="forma2" method="post" action="devolucionAlmacen.php">
<input type="hidden" name="accion_forma2" id="accion_forma2" value="{$accion}" />
  <table id="table_cat_cia_consultar_presurtido">
    <tr>
      <th class="claseTH"><input type="hidden" name="accion" id="accion" value="actualizar" />
          <input type="hidden" name="id_orden_servicio" id="id_orden_servicio" value="" />

          <input type="hidden" name="realiza" id="realiza" value="realiza" />
                          Orden de Servicio</th>
      <th class="claseTH">Cliente</th>
      <th class="claseTH">Cotizaci&oacute;n</th>
      <th class="claseTH">Fecha de Recolecci&oacute;n</th>
      <th class="claseTH">Detalle de Articulos</th>
       <th class="claseTH">Acci&oacute;n</th>
    </tr>
    {section loop=$registros name=indice start=0}
    <tr>
      <td class="nom_campo">{$registros[indice][0]}</td>
      <td class="nom_campo">{$registros[indice][2]}</td>
      <td class="nom_campo">{$registros[indice][1]}</td>
      <td class="nom_campo">{$registros[indice][5]}</td>
      <td class="claseParrafo" valign="top">
          <table id="detalle_consultar_presurtido" width="100%">
            <tr>
              <th class="claseTH">Articulo</th>
              <th class="claseTH">Descripci&oacute;n</th>
              <th class="claseTH">Cantidad Salio</th>
              <th class="claseTH">Cantidad Regresada</th>
              <th class="claseTH">Cantidad Faltante Por Ingresar</th>
			  <th class="claseTH">Cantidad</th>
            </tr>
            {section loop=$registros[indice][6] name=indice2 start=0}
            <tr valign="top">
              <td class="nom_campo">
					<input type="hidden" name="{$registros[indice][0]}id_detalle{$smarty.section.indice2.index}" id="{$registros[indice][0]}id_detalle{$smarty.section.indice2.index}" size="2" value={$registros[indice][6][indice2][0]} />
					<input type="hidden" name="{$registros[indice][0]}id_articulo{$smarty.section.indice2.index}" id="{$registros[indice][0]}id_articulo{$smarty.section.indice2.index}" size="2" value={$registros[indice][6][indice2][2]} />
				{$registros[indice][6][indice2][3]}
			</td>
			  <input type="hidden" name="{$registros[indice][0]}descripcion{$smarty.section.indice2.index}" id="{$registros[indice][0]}descripcion{$smarty.section.indice2.index}" size="2" value='{$registros[indice][6][indice2][4]}' />
              <td class="nom_campo">{$registros[indice][6][indice2][4]}</td>
              <td class="nom_campo">
				{$registros[indice][6][indice2][6]}
				<input type="hidden" name="{$registros[indice][0]}cantidad_salio{$smarty.section.indice2.index}" id="{$registros[indice][0]}cantidad_salio{$smarty.section.indice2.index}" size="2" value='{$registros[indice][6][indice2][6]}' />
			  </td>
              <td class="nom_campo">
					<input type="hidden" name="{$registros[indice][0]}cantidad_regresada{$smarty.section.indice2.index}" id="{$registros[indice][0]}cantidad_regresada{$smarty.section.indice2.index}" size="2" value='{$registros[indice][6][indice2][7]}' />
					{$registros[indice][6][indice2][7]}
			</td>
			  <td class="claseParrafo" valign="top">
			  {$registros[indice][6][indice2][8]}
				<input type="hidden" name="{$registros[indice][0]}cantidad_faltante_ingresar{$smarty.section.indice2.index}" id="{$registros[indice][0]}cantidad_faltante_ingresar{$smarty.section.indice2.index}" size="2" value='{$registros[indice][6][indice2][8]}' />
			  </td>
			  </td>
			  <td class="claseParrafo" valign="top">
				<input type="text" name="{$registros[indice][0]}cantidad_ingresar{$smarty.section.indice2.index}" id="{$registros[indice][0]}cantidad_ingresar{$smarty.section.indice2.index}" size="4" />
				{assign var="cant_productos_orden" value=$smarty.section.indice2.index}
			  </td> 
            </tr>
            {/section}
          </table>
      </td>
	  <td>
		<input name="btnReporte" type="button" class="boton" value="Generar Entrada      &raquo;" onClick="generarEntradaAlmacen({$cant_productos_orden},{$registros[indice][0]});" />
	  </td>
    </tr>
	<tr>
			<td colspan ="7"><hr></td>
	</tr>
	{/section}
  </table>
</form>

{else}
<h1> </h1>
<p class="titulo_accion">No se encontraron Ordenes de Servicio para Surtir</p>        	
{/if}   




<br /><br /><br /><br />

<script>
{literal}


function valida(id_pedido,realiza)
{
				
	var f=document.forma2;	
			
	if(realiza==1)
	{
		var fecha=document.getElementById('fecha_'+id_pedido).value;
		if(fecha=='')
		{
			alert("La fecha de entrega es requerida");
			document.getElementById('fecha_'+id_pedido).focus();
			return false;
			
		}
		
		if(!confirm("¿Desea autorizar el pedido?"))
		{
			return false;
		}
		
		document.getElementById('fecha').value=fecha;
		document.getElementById('realiza').value=1;
		
	}
	else
	{
		var razon =document.getElementById('razon_'+id_pedido).value;
		//validamos las razònes de rechazo
		if(razon=='')
		{
			alert("La razón de rechazo es requerida");
			document.getElementById('razon_'+id_pedido).focus();
			return false;
			
		}
		if(!confirm("¿Desea rechazar el pedido?"))
		{
			return false;
		}
		document.getElementById('razon').value=razon;
		document.getElementById('realiza').value=2;
		
		
	}
	document.getElementById('id_pedido').value=id_pedido;
	f.submit();
					
}

function buscar()
{	
	var f_inicial = new Date($("#fecha_inicio").val());
	var f_final = new Date($("#fecha_fin").val());
	
	if(f_inicial > f_final){
		alert("La fecha inicial es mayor a la fecha final, vuelve a seleccionar");
		return false;
	}
	
	var f=document.forma1;
	document.getElementById("accion").value = 'ver';	
	f.submit();

}

function generarEntradaAlmacen(cantidad_productos_orden,id_control_orden_servicio){
	
	var listaDevolucionArticulos = new Array();
	var validarCantidadIngresar = new Array();
	
	//Verifica que todos los campos de Surtir esten llenos, si no asigna CERO como valor y los mete a un arreglo
	for(var k=0;k<=cantidad_productos_orden;k++){
		
		var cantidad_ingresar_aux 	= $("#" + id_control_orden_servicio + "cantidad_ingresar" + k ).val();
		
		if(cantidad_ingresar_aux == ""){
			cantidad_ingresar_aux = 0;
		}
		
		if(cantidad_ingresar_aux == 0){
			validarCantidadIngresar.push({cantidad_ingresar_valida:parseInt(cantidad_ingresar_aux)});
		}
		
	}
	
	// Valida que todos los campos de Surtir no tengan valor de CERO
	var todosVacios = validarCantidadIngresar.length - 1;
	if (cantidad_productos_orden == todosVacios){
		alert('Debes Generar al menos una entrada de articulo para actualizar almacen');
		return false;
	}
	
	for(var i=0;i<=cantidad_productos_orden;i++){
		var id_control_orden_servicio_aux   = id_control_orden_servicio;
		var cantidad_salio_aux					  = $("#"+ id_control_orden_servicio + "cantidad_salio"+ i ).val();
		var cantidad_regresada_aux			  = $("#"+ id_control_orden_servicio + "cantidad_regresada"+ i ).val();
		var cantidad_faltante_ingresar_aux = $("#"+ id_control_orden_servicio + "cantidad_faltante_ingresar"+ i ).val();
		var cantidad_ingresar_aux				  = $("#"+ id_control_orden_servicio + "cantidad_ingresar"+ i ).val();
		var id_detalle_aux							  = $("#" + id_control_orden_servicio + "id_detalle" + i ).val();
		var id_articulo_aux			 				  = $("#" + id_control_orden_servicio + "id_articulo" + i ).val();
		var descripcion_aux							  = $("#" + id_control_orden_servicio + "descripcion" + i ).val();
		
		if(cantidad_ingresar_aux == ""){
			cantidad_ingresar_aux = 0;
		}

		if(parseInt(cantidad_ingresar_aux) <= parseInt(cantidad_faltante_ingresar_aux)){
					listaDevolucionArticulos.push({id_detalle:parseInt(id_detalle_aux),id_articulo:parseInt(id_articulo_aux),cantidad_ingresar:parseInt(cantidad_ingresar_aux),descripcion:descripcion_aux,id_control_orden_servicio:parseInt(id_control_orden_servicio_aux)});
		}
		else{
			alert('Una de las cantidades a devolver es mayor a la FALTANTE POR INGRESAR, favor de corregir la cantidad');
			return false;
		}
	}
	
	var generarEntradaAlmacen = confirm('¿Esta seguro de generar la entrada de productos al almacen? \n ');
	
	if(generarEntradaAlmacen == true){
		
		$.ajax({
			type: "POST",
			url: "../ajax/especiales/generaIngresoProductosAlmacen.php",
			dataType: "html",
			data: {'arreglo':listaDevolucionArticulos},
			success: function (mensaje){
				var idControl = mensaje;
							
							alert('Cantidad de articulos actualizados');
							document.location="../general/encabezados.php?t=cmFjX21vdmltaWVudG9zX2FsbWFjZW4=&k=" + idControl + "&op=2&v=1&tabla=&cadP1=MDI0ZG5CbGZqRjhibkJsZmpGOGJYQmxmakI4MQ==&cadP2=MDI0WlhCbGZqQjhhWEJsZmpCOFozQmxmakE9MQ==";
						
			}
		});
	}
	else{
		return false;
	}
	
}

{/literal}
</script>

{include file="_footer.tpl" aktUser=$username}