{include file="_header.tpl" pagetitle="$contentheader"}    
<script language="javascript" src="{$rooturl}js/franquicias.js"></script>    
<link href="{$rooturl}css/tabswizard.css" rel="stylesheet" type="text/css">

<link href="{$rooturl}css/jquery-ui.css" rel="stylesheet" type="text/css">

 <br>
<h1> Surtido de Ordenes de Servicio</h1> </div>

  
 
<table border="0" width="90%" >
 <form name="forma1" method="post" action="preSurtido.php">
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
  <td>Fecha de Entrega</td>
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

<!-- Listado de información de presurtido -->

{if $encontrados neq 0}
<form name="forma2" method="post" action="preSurtido.php">
<input type="hidden" name="accion_forma2" id="accion_forma2" value="{$accion}" />

<div id="datosOrdenesServicio">
  <table id="table_cat_cia_consultar_presurtido">
    <tr>
      <th class="claseTH"><input type="hidden" name="accion" id="accion" value="actualizar" />
          <input type="hidden" name="id_orden_servicio" id="id_orden_servicio" value="" />

          <input type="hidden" name="realiza" id="realiza" value="realiza" />
                          Orden de Servicio</th>
      <th class="claseTH">Cliente</th>
      <th class="claseTH">Cotizaci&oacute;n</th>
      <th class="claseTH">Fecha de Realizaci&oacute;n</th>
      <th class="claseTH">Fecha de Entrega</th>
      <th class="claseTH">Detalle de Articulos</th>
       <th class="claseTH">Acci&oacute;n</th>
    </tr>
    {section loop=$registros name=indice start=0}
    <tr>
      <td class="nom_campo">{$registros[indice][0]}</td>
      <td class="nom_campo">{$registros[indice][2]}</td>
      <td class="nom_campo">{$registros[indice][1]}</td>
      <td class="nom_campo">{$registros[indice][3]}</td>
      <td class="nom_campo">{$registros[indice][4]}</td>
      <td class="claseParrafo" valign="top">
          <table id="detalle_consultar_presurtido" width="100%">
            <tr>
              <th class="claseTH">Articulo</th>
              <th class="claseTH">Descripci&oacute;n</th>
              <th class="claseTH">Cantidad Solicitada</th>
              <th class="claseTH">Cantidad pendiente por entregar</th>
              <th class="claseTH">Existencia</th>
			  <th class="claseTH">Surtir</th>
            </tr>
            {section loop=$registros[indice][5] name=indice2 start=0}
            <tr valign="top">
              <td class="nom_campo">
				<input type="hidden" name="{$registros[indice][0]}id_detalle{$smarty.section.indice2.index}" id="{$registros[indice][0]}id_detalle{$smarty.section.indice2.index}" size="2" value={$registros[indice][5][indice2][0]} />
				<input type="hidden" name="{$registros[indice][0]}id_articulo{$smarty.section.indice2.index}" id="{$registros[indice][0]}id_articulo{$smarty.section.indice2.index}" size="2" value={$registros[indice][5][indice2][2]} />
				{$registros[indice][5][indice2][3]}
			</td>
			  <input type="hidden" name="{$registros[indice][0]}descripcion{$smarty.section.indice2.index}" id="{$registros[indice][0]}descripcion{$smarty.section.indice2.index}" size="2" value='{$registros[indice][5][indice2][4]}' />
              <td class="nom_campo">{$registros[indice][5][indice2][4]}</td>
              <td class="nom_campo">
				{$registros[indice][5][indice2][5]}
				<input type="hidden" name="{$registros[indice][0]}cantidad_solicitada{$smarty.section.indice2.index}" id="{$registros[indice][0]}cantidad_solicitada{$smarty.section.indice2.index}" size="2" value={$registros[indice][5][indice2][5]} />
			  </td>
              <td class="nom_campo">
					<input type="hidden" name="{$registros[indice][0]}pendiente_entregar{$smarty.section.indice2.index}" id="{$registros[indice][0]}pendiente_entregar{$smarty.section.indice2.index}" size="2" value={$registros[indice][5][indice2][8]} />
					{$registros[indice][5][indice2][8]}
			</td>
			  <td class="claseParrafo" valign="top">
				{$registros[indice][5][indice2][6]}
				<input type="hidden" name="{$registros[indice][0]}existencia{$smarty.section.indice2.index}" id="{$registros[indice][0]}existencia{$smarty.section.indice2.index}" size="2" value={$registros[indice][5][indice2][6]} />
			  </td>
			  </td>
			  <td class="claseParrafo" valign="top">
				<input type="text" name="{$registros[indice][0]}cantidad_surtir{$smarty.section.indice2.index}" id="{$registros[indice][0]}cantidad_surtir{$smarty.section.indice2.index}" size="4" />
				{assign var="no_productos_orden" value=$smarty.section.indice2.index}
			  </td> 
            </tr>
            {/section}
          </table>
      </td>
	  <td>
		<input name="btnReporte" type="button" class="boton" value="Generar Salida      &raquo;" onClick="generarSalida({$no_productos_orden},'{$registros[indice][0]}');" />
	  </td>
    </tr>
	<tr>
			<td colspan ="7"><hr></td>
	</tr>
	{/section}
  </table>
</div>

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

function generarSalida(cant_productos,id_control_ord_servicio){
	
	var listaSalidasCotizacion = new Array();
	var validarCantidadSurtir = new Array();
	
	//Verifica que todos los campos de Surtir esten llenos, si no asigna CERO como valor y los mete a un arreglo
	for(var k=0;k<=cant_productos;k++){
		
		var cantidad_surtir_pedido_aux 	= $("#" + id_control_ord_servicio + "cantidad_surtir" + k ).val();
		
		if(cantidad_surtir_pedido_aux == ""){
			cantidad_surtir_pedido_aux = 0;
		}
		
		if(cantidad_surtir_pedido_aux == 0){
			validarCantidadSurtir.push({cantidad_surtir_valida:parseInt(cantidad_surtir_pedido_aux)});
		}
		
	}
	
	// Valida que todos los campos de Surtir no tengan valor de CERO
	var todosVacios = validarCantidadSurtir.length - 1;
	if (cant_productos == todosVacios){
		alert('Debes Surtir al menos un artículo para generar la salida');
		return false;
	}
	
	for(var i=0;i<=cant_productos;i++){
		var id_control_orden_servicio_aux = id_control_ord_servicio;
		var cant_solicitada_aux 					= $("#"+ id_control_ord_servicio + "cantidad_solicitada"+ i ).val();
		var existencia_alm_aux					= $("#" + id_control_ord_servicio + "existencia" + i ).val();
		var cantidad_surtir_pedido_aux 	= $("#" + id_control_ord_servicio + "cantidad_surtir" + i ).val();
		var id_detalle_aux							= $("#" + id_control_ord_servicio + "id_detalle" + i ).val();
		var id_articulo_aux			 				= $("#" + id_control_ord_servicio + "id_articulo" + i ).val();
		var descripcion_aux							= $("#" + id_control_ord_servicio + "descripcion" + i ).val();
		var pendiente_por_entregar_aux	= $("#" + id_control_ord_servicio + "pendiente_entregar" + i ).val();
		
		if(cantidad_surtir_pedido_aux == ""){
			cantidad_surtir_pedido_aux = 0;
		}
		
		if(parseInt(cantidad_surtir_pedido_aux) <= parseInt(cant_solicitada_aux) && parseInt(cantidad_surtir_pedido_aux) <= parseInt(existencia_alm_aux) && parseInt(cantidad_surtir_pedido_aux) <= parseInt(pendiente_por_entregar_aux)){
					listaSalidasCotizacion.push({id_detalle:parseInt(id_detalle_aux),id_articulo:parseInt(id_articulo_aux),cantidad_surtir:parseInt(cantidad_surtir_pedido_aux),descripcion:descripcion_aux,id_control_orden_servicio:parseInt(id_control_orden_servicio_aux)});
		}
		else{
			alert('Una de las cantidades a surtir es mayor a la SOLICITADA, PENDIENTE POR ENTREGAR o la EXISTENCIA, favor de corregir la cantidad');
			return false;
		}
	}
	
	var generarSalidaAlmacenOk = confirm('¿Esta seguro de surtir los productos del almacen? \n ');
	if(generarSalidaAlmacenOk == true){
		
		$.ajax({
			type: "POST",
			url: "../ajax/especiales/validaNuevamenteProductosAlmacen.php",
			dataType: "html",
			data: {'arreglo':listaSalidasCotizacion},
			success: function (mensaje){
				
				var respuesta = mensaje;
				
				if(respuesta != ''){
					alert(mensaje);
					
					$.ajax({
								type: "POST",
								url: "../ajax/especiales/actualizaTablaSalidaProductosAlmacen.php",
								data: "id_control_orden_servicio=" + id_control_ord_servicio,
								success: function (actualizarTabla){
								
									$( "#datosOrdenesServicio" ).html(actualizarTabla);
									
								}
							});	
				}
				
				if(respuesta == ''){
					
					$.ajax({
						type: "POST",
						url: "../ajax/especiales/generaSalidaProductosAlmacen.php",
						dataType: "html",
						data: {'arreglo':listaSalidasCotizacion},
						success: function (accion){
							var idControl = accion;
							alert('Cantidad de articulos actualizados');
							document.location="../general/encabezados.php?t=cmFjX21vdmltaWVudG9zX2FsbWFjZW4=&k=" + idControl + "&op=2&v=1&tabla=&cadP1=MDI0ZG5CbGZqRjhibkJsZmpGOGJYQmxmakI4MQ==&cadP2=MDI0WlhCbGZqQjhhWEJsZmpCOFozQmxmakE9MQ==";
							/*
							$.ajax({
								type: "POST",
								url: "../ajax/especiales/actualizaTablaSalidaProductosAlmacen.php",
								data: "id_control_orden_servicio=" + id_control_ord_servicio,
								success: function (actualizarTabla){
								
									
									//$( "#datosOrdenesServicio" ).html(actualizarTabla);
									
								}
							});
							*/
							
						}
					});
				}
				
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