{assign var="contador" value="1"}
<link href="{$rooturl}css/estilos.css" rel="stylesheet" type="text/css" />
<link href="{$rooturl}css/estilos_especiales.css" rel="stylesheet" type="text/css" />

{if $registros eq 0}
	<p style='font-size:15px; font-weight:bold;'>No existen registros coincidentes.</p>
{else}
	{section name="fila" loop=$filas}
	<form id="postp" method="post" action="">
		<table class="encabezado-orden">
			<thead>
				<tr>
					<th class="titulos">ID Orden de Compra</th>
					<th class="titulos">Proveedor</th>
					<th class="titulos">Estatus de Orden de Compra</th>
				<tr>
			</thead>
			<tbody>
			<tr>
				<td>{$filas[fila].1}</td>
				<td>{$filas[fila].2}</td>
				<td>{$filas[fila].3}</td>
			</tr>
			</tbody>
		</table>
		<input type="hidden" id="parciales{$filas[fila].3}" value="{$filas[fila].6}"/>
		<p class='titulos' style="padding : 5px;">Datos de la Entrada</p>
		<table width="100%" border="0" class="datos-entrada">
			<tr>
			  <td width="14%"><label for="select-almacen">Almacen</label></td>
				<td width="21%">
					<select name="select-almacen" id="select-almacen{$filas[fila].6}" onChange="">
							<option value="-1">ELEGIR ALMACEN</option>
						{section name="filaAlmacen" loop=$filasAlmacen}
						<option value="{$filasAlmacen[filaAlmacen].0}">{$filasAlmacen[filaAlmacen].1}</option>
						{/section}
					</select>
			  </td>
				<td width="33%">&nbsp;</td>
				<td width="32%">&nbsp;</td>
			</tr>
			<tr>
				<td><label for="select-almacen">Documento Proveedor</label></td>
				<td><label for="select-almacen">{$filas[fila].5}</label></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><label for="fac-prov">Factura Proveedor</label></td>
				<td><input type="text" name="fac-prov" id="fac-prov{$filas[fila].0}"/></td>
				<td>&nbsp;<input type='hidden' id='id_sucursal{$filas[fila].0}' value="{$filas[fila].7}"/></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><label for="pedimento">Pedimento No.</label></td>
				<td><input type="text" name="pedimento" id="pedimento{$filas[fila].0}"/></td>
				<td><label for="pedimento_fec">Fecha de Pedimento</label></td>
				<td>
					<input type="text" name="pedimento_fec" id="pedimento_fec{$filas[fila].0}" onFocus="calendario(this);"/>
					<span style="font-size:10px;font-style:italic;">&nbsp;dd/mm/yyyy</span>
				</td>
			</tr>
			<tr>
				<td><label for="select-aduana">Aduana</label></td>
				<td>
					<select name="select-aduana" id="select-aduana{$filas[fila].0}" onChange="">
					<option value="1">ELIGA UNA ADUANA</option>
					{section name="filaAduana" loop=$filasAduana}
							<option value="{$filasAduana[filaAduana].0}">{$filasAduana[filaAduana].1}</option>
					{/section}
					</select>
				</td>
				<td>&nbsp;</td>
				<td><input type="hidden" name="proveedor_id" id="proveedor_id{$filas[fila].0}" value="{$filas[fila].4}"/></td>
			</tr>
		</table>

		<p class='titulos' style="padding : 5px;">Detalle</p>
		<table class="encabezado-orden">
			<thead>
				<tr>
					<th class='titulos'>Producto</th>
					<th class='titulos' style='text-align:center;'>Ingresar Numero de Serie</th>
					<th class='titulos' style='text-align:center;'>Requiere Validaci&oacute;n Tipo de Equipo</th>
					<th class='titulos' style='text-align:center;'>Cantidad Solicitada</th>
					<th class='titulos' style='text-align:center;'>Cantidad Recibida</th>
					<th class='titulos'>Cantidad a Ingresar</th>
					<th class='titulos'>&nbsp;</th>
					<th class='titulos'>&nbsp;</th>
				</tr>
			</thead>
			{section name="filaProducto" loop=$filasProducto}	
			{$filasProducto[filaProducto].8}:{$filas[fila].0}:{$sql2}
			{if $filasProducto[filaProducto].8 eq $filas[fila].0}
			
			
			<tbody id="res-prod{$filasProducto[filaProducto].8}">
					<tr>
					<td>{$filasProducto[filaProducto].2}:{$filasProducto[filaProducto].8}:{$filas[fila].0}<!-- {$sql} --></td>
					<td style="text-align:center;">
					<input type="hidden" id="requiereNumerosDeSerie" value="">
					</td>
					<td style="text-align:center;">
					<input type="hidden" id="requiereValidarTipoProducto" value="">
					</td>
					<td style="text-align:center;"></td>
					<td style="text-align:center;"></td>
					<td style="text-align:center;">
					<input style="text-align:right;" type="text" name="cantidadI" id="cantidadI" onkeydown="return noletrasCantidades(event);"/>
					<input type="hidden" name="idProducto" id="idProducto" value=""/>
					<input type="hidden" name="idPrecioUnitario" id="idPrecioUnitario" value="datosProd -> precio_unitario"/>
					<input type="hidden" name="idDetalleOrden" id="idDetalleOrden" value="datosProd -> id_detalle"/>
					<input type="hidden" name="cantidadProd" id="cantidadProd" value="datosProd -> cantidad"/>
					<input type="hidden" name="cantidadR" id="cantidadR" value="datosProd -> recibidos"/>
					</td>
					<td style="text-align:center;"><div id="boton-importar">
						<input type="button" id="importarOrden" name="importarOrden" value="Importar" class="small button grey" onClick="validacionCantidadAIngresar('id_orden','contador','datosProd -> cantidad','datosProd -> valida_tipo_producto',1,'datosProd -> id_producto');"/>
						<br />
						<input type="hidden" id="cantidadIAux" name="cantidadIAux" value="">
						<input type="hidden" id="numeroCarga" name="numeroCarga" value="">
					</td>
					<td style="text-align:center;"><div id="boton-capturar">
						<input type="button" id="importarOrden" name="importarOrden" value="Capturar" class="small button grey" 
						onClick="
						limpiaCajasAuxiliares('id_orden','contador');
						validacionCantidadAIngresar('id_orden','contador','datosProd -> cantidad','datosProd -> valida_tipo_producto',2,'datosProd -> id_producto');" />
					</td>
					</tr>
			</tbody>
			{/if}
			{/section}
		</table>
</form>	
<br /><br />
{/section}
{/if}
