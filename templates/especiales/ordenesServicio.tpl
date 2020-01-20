{include file="_header.tpl" pagetitle="$contentheader"}    
<script language="javascript" src="{$rooturl}js/franquicias.js"></script>    
<link href="{$rooturl}css/tabswizard.css" rel="stylesheet" type="text/css">

<link href="{$rooturl}css/jquery-ui.css" rel="stylesheet" type="text/css">

 <br>
<h1> Consultar Presurtido</h1> </div>

  
 
<table border="0" width="90%" >
 <form name="forma1" method="post" action="ordenesServicio.php">
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

<!-- Listado de información de ordenes de servicio -->

{if $encontrados neq 0}
<form name="forma2" method="post" action="ordenesServicio.php">
  <table border="0" id="table_cat_cia_consultar_presurtido">
    <tr>
      <th class="claseTH"><input type="hidden" name="accion" id="accion" value="actualizar" />
          <input type="hidden" name="id_orden_servicio" id="id_orden_servicio" value="" />

          <input type="hidden" name="realiza" id="realiza" value="realiza" />
                          Orden de Servicio</th>
      <th class="claseTH">Cliente</th>
      <th class="claseTH">Cotizaci&oacute;n</th>
      <th class="claseTH">Fecha de Realizaci&oacute;n</th>
      <th class="claseTH">Fecha de Entrega</th>
	  <th class="claseTH">Fecha de Recolecci&oacute;n</th>
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
	  <td class="nom_campo">{$registros[indice][5]}</td>
      <td class="claseParrafo" valign="top">
          <table border="0" id="detalle_consultar_presurtido" width="100%">
            <tr>
              <th class="claseTH">Articulo</th>
              <th class="claseTH">Descripci&oacute;n</th>
              <th class="claseTH">Cantidad Solicitada</th>
              <th class="claseTH">Cantidad Pendiente por Entregar</th>
              <th class="claseTH">Existencia</th>
              <th class="claseTH">Surtir</th>
            </tr>
            {section loop=$registros[indice][6] name=indice2 start=0}
            <tr valign="top">
              <td class="nom_campo">{$registros[indice][6][indice2][3]}</td>
              <td class="nom_campo">{$registros[indice][6][indice2][4]}</td>
              <td class="nom_campo">{$registros[indice][6][indice2][5]}</td>
              <td class="nom_campo">{$registros[indice][6][indice2][8]}</td>
              <td class="nom_campo">{$registros[indice][6][indice2][6]}</td>
              <td class="claseParrafo" valign="top">
					<table id="detalle" width="100%">
						<tr>
						  <th class="claseTH">Almacen</th>
						  <th class="claseTH">Cantidad a Surtir</th>
						</tr>
						{*{section loop=$registros[indice][5][indice2][9] name=indice3 start=0}*}
						{section name=aDetalleAlmacenes loop=$almacenesDetallePorsurtir start=0}
							<tr valign="top">
									<td class="nom_campo">
									<input type="hidden" name="id_almacen" id="id_almacen" size="1" value="{$almacenesDetallePorsurtir[aDetalleAlmacenes].0}" />
									{$almacenesDetallePorsurtir[aDetalleAlmacenes].2}</td>
									<td class="nom_campo">{$almacenesDetallePorsurtir[aDetalleAlmacenes].1}</td>
							</tr>
						{/section}
					</table>
				</td>
              <!--<td class="nom_campo"> <input name="cantidadSurtir_{$registros[indice][0]}_{$smarty.section.indice2.index}" type="text" class="text_especiales" id="cantidadSurtir_{$registros[indice][0]}_{$smarty.section.indice2.index}" size="10" />  </td>-->
            </tr>
            {/section}
          </table>
      </td>
	  <td><input name="btnReporte" type="button" class="boton" value="Reporte      &raquo;" onClick="generaReportePdfConsultaPresurtido({$registros[indice][0]},'{$registros[indice][1]}');" /></td>
    </tr>
	<tr>
			<td colspan ="7"><hr></td>
	</tr>
      {/section}
  </table>
</form>
{else}
<h1> </h1>
<p class="titulo_accion">No se encontraron Salidas de Ordenes de Servicio</p>        	
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
	//mandamos buscar las ordenes de servicio que cumplan con los datos 
	
	
	f.submit();

}

function generaReportePdfConsultaPresurtido(id_control_orden_servicio,id_orden_servicio){
	window.open('../pdf/imprimeDoc.php?id_control_orden_servicio=' + id_control_orden_servicio + '&tipo=GENERA_REPORTE' + '&id_orden_servicio=' + id_orden_servicio);
}

{/literal}
</script>

{include file="_footer.tpl" aktUser=$username}