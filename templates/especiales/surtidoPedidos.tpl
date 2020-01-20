{include file="_header.tpl" pagetitle="$contentheader"}   
{assign var="contador" value="1"}
{assign var="cantidad_entregada" value="4"}
{assign var="cantidad_en_almacen" value="6"}
<link href="{$rooturl}css/estilos_especiales.css" rel="stylesheet" type="text/css">
<link href="{$rooturl}css/estilos.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="{$rooturl}js/funciones_especiales.js"></script>

<link rel="stylesheet" href="{$rooturl}/js/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="{$rooturl}/js/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>

<h1>Generación de Salidas de Almacén de Pedidos Liquidados</h1>
<!-- Cargamos la sucursales --> 
<!-- <form name="forma1" method="post" action="surtidoPedidos.php"> -->  
<form name="forma1">
<table width="100%">
<tr>
     <td width="14%"><div align="left"><p>Sucursal</p></div></td>
     <td colspan="3">
	 <div align="left">
		<select name="id_sucursal_buscar" class="campos_req" id="id_sucursal_buscar" onchange="//cargaClientesSucursal()">
     	{if $sucursal_usuario eq 0}
        	<option value="-1" selected="selected"> - Seleccione una Sucursal - </option>
         	<option value="9999999999" > - Todos - </option>
		{/if}
			{html_options values=$reg_sucursales[0] output=$reg_sucursales[1] }
       </select>
     </div>	 </td>
     <td width="28%">&nbsp;</td>
     <td width="15%" class="form_labels"><div align="right"><p>Surtir del almac&eacute;n</p></div></td>
     <td colspan="2">
		<div align="right">
			<select name="id_almacen_buscar" class="campos_req" id="id_almacen_buscar">
				<option value="-1" selected="selected"> - Seleccione una Almacen - </option>
				{html_options values=$reg_almacenes[0] output=$reg_almacenes[1]  }
			</select>
		</div></td>
    </tr>
<tr>
     <td><div align="left"><p>Cliente</p></div></td>
     <td colspan="3">
	 <div align="left">
		<select name="id_cliente_buscar" class="campos_req" id="id_cliente_buscar">
        	<option value="-1" selected="selected"> - Seleccione un Cliente - </option>
         	<option value="9999999999" > - Todos - </option>
	        {html_options values=$reg_clientes[0] output=$reg_clientes[1] selected=$id_cliente_buscar }
       </select>
     </div>	 </td>
     <td>&nbsp;</td>
     <td>&nbsp;</td>
     <td colspan="2">&nbsp;</td>
    </tr>
<tr>
     <td><div align="left">
       <p>Fecha del Pedido </p>
     </div></td>
     <td width="9%"><div align="left">
       <input name="fecha_inicial" type="text" id="fecha_inicial" size="10" maxlength="10" onFocus="calendario(this);" />
     </div></td>
     <td width="4%"><div align="left"><p>al</p></div></td>
     <td width="9%"><input name="fecha_final" type="text" id="fecha_final" size="10" maxlength="10" onfocus="calendario(this);" /></td>
     <td><div align="left">
       <input name="btnBuscarFiltos" type="button" class="button_search" value="Buscar &raquo;" onclick="
	   buscarPedidosLiquidados(forma1.id_sucursal_buscar.options[id_sucursal_buscar.selectedIndex].value, forma1.id_cliente_buscar.options[id_cliente_buscar.selectedIndex].value, forma1.fecha_inicial.value, forma1.fecha_final.value, forma1.id_almacen_buscar.options[id_almacen_buscar.selectedIndex].value, forma1.Tipo.value, forma1.id_pedido_buscar.value, '1');" />
     </div></td>
     <td><div align="right">
       <p>
		 Pedido <input type="radio" name="Tipo" value="P" /><br />
		 Solicitud de Material <input type="radio" name="Tipo" value="S" /> 
		</p>
     </div></td>
     <td width="21%">
	    <div align="right">
	      <input name="id_pedido_buscar" type="text" id="id_pedido_buscar" size="10" maxlength="10" value='{$id_pedido_buscar}' />
	      &nbsp;&nbsp;&nbsp;
          <input name="btnBuscarPedido" type="button" class="button_search" value="Buscar &raquo;" onclick="
			buscarPedidosLiquidados(forma1.id_sucursal_buscar.options[id_sucursal_buscar.selectedIndex].value, forma1.id_cliente_buscar.options[id_cliente_buscar.selectedIndex].value, forma1.fecha_inicial.value, forma1.fecha_final.value, forma1.id_almacen_buscar.options[id_almacen_buscar.selectedIndex].value, forma1.Tipo.value, forma1.id_pedido_buscar.value, '2');
		  " />
          </div></td>
</tr>
</table> 
<hr>
<div id="div_surtido">
<!-- 
<table id="table_cat_cia_surtido" width="100%">
  <tr>
    <th width="5%" class="th_especiales_encabezado">No.</th>
    <th width="7%" class="th_especiales_encabezado">Pedido</th>
    <th width="19%" class="th_especiales_encabezado">Cliente</th>
    <th width="2%" class="th_especiales_encabezado">&nbsp;</th>
    <th width="26%" class="th_especiales_encabezado">Producto</th>
    <th width="8%" class="th_especiales_encabezado">C. Solicitada</th>
    <th width="8%" class="th_especiales_encabezado">C. Entregada</th>
    <th width="7%" class="th_especiales_encabezado">C. Almac&eacute;n</th>
    <th width="6%" class="th_especiales_encabezado">C. Surtir</th>
    <th width="6%" class="th_especiales_encabezado">&nbsp;</th>
    <th width="6%" class="th_especiales_encabezado">&nbsp;</th>
  </tr>
	{section name="fila" loop=$filas}
        <tr class="campo_small_surtido">  
            <td>{$contador}
            <input name="id_detalle_{$contador}" type="hidden" id="id_detalle_{$contador}" value="{$filas[fila].10}" />
			<!-- <input name="id_consecutivo_{$contador}" type="hidden" id="id_consecutivo_{$contador}" value="{$contador}" /> -- >
			<input name="id_consecutivo_{$contador}" type="hidden" id="id_consecutivo_{$contador}" value="{$filas[fila].0}" />
			</td>
            <td>
				<input name="BtnVerPedido" type="button" class="small button grey" value="{$filas[fila].1}" onclick="verPedido('{$filas[fila].0}');" />
				<input name="id_pedido_{$contador}" type="hidden" id="id_pedido_{$contador}" value="{$filas[fila].0}" />
			</td> 
            <td>{$filas[fila].5}</td> 
            <td><div align="center"><input type="checkbox" name="check_{$contador}" id="check_{$contador}" /></div></td>
            <td class="campo_small_surtido_negro" >{$filas[fila].7}
            <input name="idProd_{$contador}" type="hidden" id="idProd_{$contador}" value="{$filas[fila].6}" />
			<input name="nombreProd_{$contador}" type="hidden" id="nombreProd_{$contador}" value="{$filas[fila].7}" />
			</td> 
            <td class="campo_small_surtido_azul" >{$filas[fila].8}
            <input name="canSol_{$contador}" type="hidden" id="canSol_{$contador}" value="{$filas[fila].8}" /></td>
           	<td class=""><div align="center">{$cantidad_entregada}</div></td> 
			<td><div align="center">{$cantidad_en_almacen}<input name="canExis_{$contador}" type="hidden" id="canExis_{$contador}" value="{$cantidad_en_almacen}" /></div></td> 
            <td class="td_especiales"><div align="center">
			<!-- validaCantidadIgresar(cantidadIngresar,cantidadSolicitada, cantidadRecibida, idOrdenCompra, renglon) -- >
              <input name="cantidadI{$contador}" type="text" id="cantidadI{$contador}" size="2" maxlength="3" onblur="validaCantidadIgresarParaPedido(this.value,{$filas[fila].8},{$cantidad_entregada},{$cantidad_en_almacen},{$contador});"/>
            </div></td>
       	    <td class="td_especiales"><div align="center">
			<!-- VERIFICA QUE SEAN IRDS -- >
			{if $filas[fila].9 eq "1"}
       	      	<input name="BtnImportar" type="button" class="small button grey" value="Importar" onclick="importarNumeroSerieParaSalidaDeAlmacen(cantidadI{$contador}.value, '{$contador}');" />
			{/if}
   	        </div></td>
       	    <td class="td_especiales"><div align="center">
			<!-- VERIFICA QUE SEAN IRDS -- >
			{if $filas[fila].9 eq "1"}
				<input name="BtnCapturar" type="button" class="small button grey" value="Capturar" onclick="capturarNumeroSerieParaSalidaDeAlmacen('{$contador}','0',cantidadI{$contador}.value,'0');" />
			{/if}
   	        </div></td>
			<td style="display:none; width:0px;">
		{$contador++}
		</td>
        </tr>
	{/section}
</table>
-->
 </div>
<table width="100%">
<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
</tr>
<tr>
	<td width="304"><input name="detalle3" type="button" class="boton" value="Imprime Presurtido " onclick="imprimePresurtido('{$contador}')" /></td>
	<td width="342"><input name="detalle2" type="button" class="boton" value="Generar Salida " onclick="generaSalida(forma1.TxtContador.value);" /></td>
</tr>
</table>
</form>
{include file="_footer.tpl" aktUser=$username}
