{assign var="contador" value="1"}
{section name="filasProductos" loop=$filas}

		<tr>
				
				<td id="filas-contador" style="width:20px; text-align:center">{$contador}</td>
				<td style="width:205px;">{$filas[filasProductos].0}</td>
				<td style="width:65px; text-align:center" class="sel_existencia"><input class="solo-lectura" type="text" id="existencia{$contador}" readonly="readonly" value="{$filas[filasProductos].5}" style="width:60px"/></td>
				<td style="width:100px; text-align:center" class="sel_pendientes"><input class="solo-lectura" type="text" id="pendientes{$contador}" readonly="readonly" value="{$filas[filasProductos].6}"/></td>
				<td style="width:75px; text-align:center" class="sel_disponible"><input class="solo-lectura" type="text" id="disponible{$contador}" readonly="readonly" value="{$filas[filasProductos].7}"/></td>
				
				{if $filas[filasProductos].1 eq "../../fotos/"}
						<td style="width:70px; text-align:center"><a href="../../imagenes/producto_sin_foto.jpg" title="{$filas[filasProductos].0}" id="despliega"><img src="../../imagenes/producto_sin_foto.jpg" width="50px" height="50px"/></a></td>
				{else}
						<td style="width:70px; text-align:center"><a href="{$filas[filasProductos].1}" title="{$filas[filasProductos].0}" id="despliega"><img src="{$filas[filasProductos].1}" width="50px" height="50px"/></a></td>
				{/if}
				
				<td style="width:110px; text-align:center" class="sel_cantidad"><input type="text" name="cantidad" id="cantidad{$contador}" style="width:80px; font-size:11px; text-align:center" onkeydown="return noletrasCantidades(event);" onkeyup="calculaImporte(this, {$contador}); calculaTotal(this, {$contador});"/></td>
				
				<td style="width:67px; text-align:right"><input type="hidden" id="precio_publico{$contador}" value="{$filas[filasProductos].2}"/>${$filas[filasProductos].2}</td>
				
				<td style="width:67px; text-align:right" class="sel_importe"><input type="hidden" id="importe{$contador}_a"/><input class="solo-lectura" type="text" id="importe{$contador}" readonly="readonly" /></td>
				
				{if $caso eq "1" || $caso eq "3"}
						<td style="width:30px; text-align:center" class="sel_agrega_quita{$contador}"><a href="#" id="agrega_producto" onClick="agregaRegresaProducto(this, {$contador}, 2, event)"><img src="../../imagenes/agregar.png" width="30px" height="30px"/></a></td>
				{else if $caso eq "2"}
						<td style="width:30px; text-align:center" class="sel_agrega_quita{$contador}"><a href="#" id="agrega_producto" onClick="agregaRegresaProducto(this, {$contador}, 3, event)"><img src="../../imagenes/remover.png" width="20px" height="20px"/></a></td>
				{/if}
				<td style="display:none" class="sel_de_detalle_lista"><input type="hidden" id="id_detalle_lista{$contador}" value="{$filas[filasProductos].3}"/></td>
				<td style="display:none" class="sel_producto"><input type="hidden" id="id_producto{$contador}" value="{$filas[filasProductos].4}"/></td>
		</tr>

		<div style="display:none">{$contador++}</div>
{sectionelse}
		<tr>
				<td>No existen productos con estas caracteristicas</td>
		</tr>
{/section}
