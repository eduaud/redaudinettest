{include file="_header.tpl" pagetitle="$contentheader"}    
<script language="javascript" src="{$rooturl}js/franquicias.js"></script>    
<link href="{$rooturl}css/tabswizard.css" rel="stylesheet" type="text/css">

<link href="{$rooturl}css/jquery-ui.css" rel="stylesheet" type="text/css">

 <br>
<h1> Presurtido de Ordenes de Servicio</h1> </div>

  
 
<table width="90%" >
 <form name="forma1" method="post" action="aprobacionPedidos.php">
<tr>
<td colspan="5" class="campo_small" ><br> 	 Seleccione los criterios que desee especificar y de clic al botón 'Buscar '.<br><br>
</td>
</tr>

<tr class='nom_campo'>
<td >Almacén Neteable</td>
<td><select name="id_almacen" class="campos_req" id="id_almacen">
  <option value="0" selected="selected"> - Seleccione una almacén - </option>
  
                        {html_options values=$arrysIdAlm output=$arrysNombreAlm selected=$idAlmSel }
                    
</select></td>
<td>&nbsp;&nbsp;&nbsp;</td>
<td>Cliente
   <select name="id_almacen2" class="campos_req" id="id_almacen2">
    <option value="0" selected="selected"> - Seleccione un cliente - </option>
    
  
  
                        {html_options values=$arrysIdCliente output=$arrysNombreCliente selected=$idCliente }
                    


  </select></td>

</tr>
<tr class='nom_campo'>
  <td>Fecha de Entrega</td>
  <td><input name="fecha_incio" type="text" class="campos_req" id="fecha_incio" size="10"  onfocus="calendario(this);"/>
    al
    <input name="fecha_fin" type="text" class="campos_req" id="fecha_fin" size="10"  onfocus="calendario(this);"/></td>
  <td>&nbsp;</td>
  <td>Orden de Sevicio
    <select name="id_orden_servicio" class="campos_req" id="id_orden_servicio">
      <option value="0" selected="selected"> - Seleccione tipo de salida - </option>
      
    
  

                        {html_options values=$arrysIdOS output=$arrysNombreOS selected=$idOS }
                    



  
    </select></td>
  
</tr>
<tr class='nom_campo'>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td><input name="buscar" type="button" class="boton" value="Buscar      &raquo;" onclick="buscar()" /></td>

</tr>
</form>

</table>   
  <br> 
  <br> 
<!-- CARGA DE SOLICITUDES DE FRANQUICIA -->
{if $encontrados neq 0}
  
<table id="table_cat_cia">
  <form name="forma2" method="post" action="aprobacionPedidos.php">
  <tr>
    <th class="claseTH"><input type="hidden" name="accion" id="accion" value="actualizar" />
        <input type="hidden" name="id_pedido" id="id_pedido" value="" />
        <input type="hidden" name="fecha" id="fecha" value="" />
        <input type="hidden" name="razon" id="razon" value="" />
        <input type="hidden" name="realiza" id="realiza" value="realiza" />
      Orden de Servicio</th>
    <th class="claseTH">Cliente</th>
    <th class="claseTH">Cotizaci&oacute;n</th>
    <th class="claseTH">Fecha de Creaci&oacute;n</th>
    <th class="claseTH">Fecha de Entrega</th>
    <th class="claseTH">Detalle de Articulos</th>
    <th class="claseTH">Estatus</th>
  </tr>
  {section loop=$registros name=indice start=0}
  <tr>
    <td class="claseParrafo">{$registros[indice][2]}</td>
    <!--plaza-->
    <td class="claseParrafo">{$registros[indice][1]}</td>
    <!--cliente-->
    <td class="claseParrafo">{$registros[indice][0]}</td>
    <!--id pedido-->
    <td class="claseParrafo">{$registros[indice][3]}</td>
    <!--fecha-->
    <td class="claseParrafo">${$registros[indice][4]}</td>
    <!--total-->
    <td class="claseParrafo" valign="top">
    <br>
    <table id="detalle" width="90%">
      <tr>
        <th class="claseTH">Producto</th>
        <th class="claseTH">Cantidad Surtida</th>
        <th class="claseTH">Almacen</th>
        <th class="claseTH">Existencia</th>
        <th class="claseTH">Cantidad Solicitada</th>
        <!-- <th class="claseTH">Precio</th>-->
      </tr>
      {section loop=$registros[indice][5] name=indice2 start=0}
      <tr>
        <td class="claseParrafo">{$registros[indice][5][indice2][1]}</td>
        <!--id pedido-->
        <td class="claseParrafo">{$registros[indice][5][indice2][2]}</td>
        <!--id pedido-->
        <!-- <td class="claseParrafo">{$registros[indice][5][indice2][3]}</td>-->
        <!--id pedido-->
      </tr>
      {/section}
    </table></td>
    <!--detalle de prod-->
    <td class="claseParrafo"><table width="100%"  border="0" cellpadding="0" cellspacing="3">
      <tr>
        <td class="label_Especiales">Fecha Entrega:</td>
        <td class="label_Especiales">Raz&oacute;nes:</td>
      </tr>
      <tr>
        <td><input name="fecha_{$registros[indice][0]}" type="text" class="text_especiales" id="fecha_{$registros[indice][0]}" size="10"  onfocus="calendario(this);"/></td>
        <td><textarea name="razon_{$registros[indice][0]}" cols="14" rows="2" class="text_especiales" id="razon_{$registros[indice][0]}"></textarea></td>
      </tr>
      <tr>
        <td ><input name="autorizar" type="button" class="button_especiales" value="Autorizar  &raquo;" onclick="valida({$registros[indice][0]},1)" /></td>
        <td><input name="cancelar" type="button" class="button_especiales" value="Rechazar  &raquo;" onclick="valida({$registros[indice][0]},2)"/></td>
      </tr>
    </table></td>
    <!--estatus-->
  </tr>
  {/section}
  </form>
</table>

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
	var f=document.forma1;	
	
	//mandamos buscar las ordenes de servicio que cumplan con los datos 
	
	
	f.submit();

}

{/literal}
</script>

{include file="_footer.tpl" aktUser=$username}
