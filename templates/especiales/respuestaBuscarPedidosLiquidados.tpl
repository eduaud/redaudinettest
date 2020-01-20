{assign var="contador" value="1"}
{assign var="cantidad_entregada" value="0"}
{assign var="cantidad_en_almacen" value="5"}
<!-- {$sql} -->
<div align="center">
{if $numeroDeRegistros neq "0"}
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
				<input name="id_consecutivo_{$contador}" type="hidden" id="id_consecutivo_{$contador}" value="{$filas[fila].0}" />
				<input name="id_control_pedido_{$contador}" type="hidden" id="id_control_pedido_{$contador}" value="{$filas[fila].0}" />
				<input name="tipo_{$contador}" type="hidden" id="tipo_{$contador}" value="{$filas[fila].12}" />
				</td>
				<td>
					<input name="BtnVerPedido" type="button" class="small button grey" value="{$filas[fila].1}" onclick="verPedido('{$filas[fila].0}');" />
					<input name="id_pedido_{$contador}" type="hidden" id="id_pedido_{$contador}" value="{$filas[fila].1}" />
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
				<td><div align="center">{$cantidad_en_almacen}<input name="canExis_{$contador}" type="hidden" id="canExis_{$contador}" value="{$cantidad_en_almacen}" />
				<input name="idAlmacen_{$contador}" type="hidden" id="idAlmacen_{$contador}" value="{$filas[fila].11}" /></div></td> 
				<td class="td_especiales"><div align="center">
				<!-- validaCantidadIgresar(cantidadIngresar,cantidadSolicitada, cantidadRecibida, idOrdenCompra, renglon) -->
				  <input name="cantidadI{$contador}" type="text" id="cantidadI{$contador}" size="2" maxlength="3" onblur="validaCantidadIgresarParaPedido(this.value,{$filas[fila].8},{$cantidad_entregada},{$cantidad_en_almacen},{$contador});"/>
				</div></td>
				<td class="td_especiales"><div align="center">
				<!-- VERIFICA QUE SEAN IRDS -->
				{if $filas[fila].9 eq "1"}
					<input name="BtnImportar" type="button" class="small button grey" value="Importar" onclick="importarNumeroSerieParaSalidaDeAlmacen(cantidadI{$contador}.value, '{$contador}');" />
				{/if}
				</div></td>
				<td class="td_especiales"><div align="center">
				<!-- VERIFICA QUE SEAN IRDS -->
				{if $filas[fila].9 eq "1"}
					<input name="BtnCapturar" type="button" class="small button grey" value="Capturar" onclick="capturarNumeroSerieParaSalidaDeAlmacen('{$contador}','0',cantidadI{$contador}.value,'0');" />
				{/if}
				</div></td>
				<td style="display:none; width:0px;">{$contador++}</td>
			</tr>
		{/section}
		<input name="TxtContador" type="hidden" id="TxtContador" value="{$contador}" />
	</table>
{else}
	<table width="100%" border="0"><tr><td>&nbsp;</td></tr><tr><td>NO EXISTE INFORMACI&Oacute;N A MOSTRAR</td></tr></table>
{/if}
</div>
