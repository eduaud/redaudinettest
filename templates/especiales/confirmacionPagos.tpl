{include file="_header.tpl" pagetitle="$contentheader"}  

<div id="titulo-icono" class="titulo-icono">
	<div id="titulo" class="encabezado">Confirmación de Pagos de Pedidos</div>
	<!-- Autorizaci&oacute;n &Oacute;rdenes de Compra a Proveedor -->
</div>
   
<!-- CARGA DE SOLICITUDES DE FRANQUICIA -->
{if $encontrados > 0}
   
   
 
<table id="table_cat_cia" class="genera-descuento">
  <form name="forma2" method="post" action="confirmacionPagos.php">
  <input type="hidden" name="accion" id="accion" value="actualizar" />
  <input type="hidden" name="id_detalle_pedido" id="id_detalle_pedido" value="" />
 <input type="hidden" name="idControlPedido" id="idControlPedido" value="" />
  <input type="hidden" name="realiza" id="realiza" value="" />
  <input type="hidden" name="observaciones" id="observaciones" value="" />
  <tr>
    <th class="th_especiales_encabezado">Sucursal</th>
    <th class="th_especiales_encabezado">Cliente</th>
    <th class="th_especiales_encabezado">Pedido</th>
    <th class="th_especiales_encabezado">Vendedor</th>
    <th class="th_especiales_encabezado">Usuario</th>
    <th class="th_especiales_encabezado">Fecha Cobro</th>    
    <th class="th_especiales_encabezado">Forma Pago</th>
	<th class="th_especiales_encabezado">Banco</th>
    <th class="th_especiales_encabezado">Num. Documento</th>
    <th class="th_especiales_encabezado">Num. Aprobación</th>
    <th class="th_especiales_encabezado">Monto</th>
    <th class="th_especiales_encabezado">Observaciones</th>
    <th class="th_especiales_encabezado">Acciones</th>
  </tr>
  {section loop=$registros name=indice start=0}
  <tr>
    <td class="td_especiales">{$registros[indice][4]}</td>

    <td class="td_especiales">{$registros[indice][3]}</td>

    <td class="td_especiales">{$registros[indice][2]}</td>

    <td class="td_especiales">{$registros[indice][5]}</td>

    <td class="td_especiales">{$registros[indice][6]}</td>

    <td class="td_especiales">{$registros[indice][7]}</td>
    
    <td class="td_especiales">{$registros[indice][9]}</td>
    <td class="td_especiales">{$registros[indice][23]}</td>
    <td class="td_especiales">{$registros[indice][12]}</td>
    <td class="td_especiales">{$registros[indice][13]}</td>
    <td class="td_especiales">{$registros[indice][14]}</td>
    <td class="td_especiales">{$registros[indice][17]}</td>
    
    <td class=""><table width="100%"  border="0" cellpadding="0" cellspacing="3">
      
      <tr>
        <td ><input name="detalle" type="button" class="botones_especiales" value="Ver Pedido " onclick="verPedido({$registros[indice][1]})" /> &nbsp;&nbsp;&nbsp;</td>
        <td ><input name="autorizar" type="button" class="botones_especiales" value="Autorizar  &raquo;" onclick="valida({$registros[indice][0]},{$registros[indice][1]},1)" />&nbsp;&nbsp;&nbsp;</td>
        <td><input name="cancelar" type="button" class="botones_especiales" value="Rechazar  &raquo;" onclick="valida({$registros[indice][0]},{$registros[indice][1]},2)"/>&nbsp;</td>
      </tr>
      <tr>
      	 <td colspan="3">
        <textarea style="width:150px" id="observaciones_{$registros[indice][0]}"></textarea>
         </td>
      </tr>
      
    </table></td>
    <!--estatus-->
  </tr>
  {/section}
    </form>
</table>

{else}
<p class="titulo_accion" style="font-weight:bold; font-size:12px; margin-top:15px;">No se encontraron pagos por confirmar</p>        	
{/if}   




<br /><br /><br /><br />

<script>
{literal}


function verPedido(idControlPedido){
	href = "{/literal}{$rooturl}{literal}code/general/encabezados.php?t=YWRfcGVkaWRvcw==&k=" + idControlPedido +
			"&op=2&v=1&tabla=&cadP1=MDI0ZG5CbGZqRjhibkJsZmpGOGJYQmxmakY4MQ==&cadP2=MDI0WlhCbGZqRjhhWEJsZmpGOFozQmxmakU9MQ==";
	
	window.open(href, 'popup', 'scrollbars=1,width=1000,height=500');
	return false;	
}

function valida(id_detalle_pedido,idControlPedido,realiza)
{
				
	var f=document.forma2;	
			
	if(realiza==1)
	{
		if(!confirm(String.fromCharCode(191) + "Desea confirmar el pago?"))
		{
			return false;
		}
		
		document.getElementById('realiza').value=1;
		
	}
	else
	{
		if(!confirm(String.fromCharCode(191) + "Desea rechazar el pago?"))
		{
			return false;
		}
		else{
				if($("#observaciones_" + id_detalle_pedido).val() == ""){
								alert("Captura el motivo del rechazo");
								return false;
								}
				else{
					document.getElementById('realiza').value=2;
				}
		}
		
		
		
	}
	document.getElementById('id_detalle_pedido').value=id_detalle_pedido;
	document.getElementById('idControlPedido').value=idControlPedido;
	document.getElementById('observaciones').value=document.getElementById('observaciones_'+id_detalle_pedido).value;
	
	//alert(document.getElementById('observaciones').value);
	
	f.submit();
					
}

{/literal}
</script>

{include file="_footer.tpl" aktUser=$username}
