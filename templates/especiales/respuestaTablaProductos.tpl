<tbody>		
{assign var="contador" value="1"}
{section name="filasProductos" loop=$filas}

		<tr>
				<td style="width:20px;">{$contador}</td>
				<td style="width:125px;">{$filas[filasProductos].0}</td>
				<td style="width:95px;">{$filas[filasProductos].1}</td>
				<td style="width:95px;">{$filas[filasProductos].2}</td>
				<td style="width:95px;">{$filas[filasProductos].3}</td>
				<td style="width:95px;">{$filas[filasProductos].4}</td>
				<td style="width:150px;">{$filas[filasProductos].5}</td>
				<td style="width:80px;">
						<input type="hidden" value="{$filas[filasProductos].6}" id="precio_publico{$contador}"/>
						${$filas[filasProductos].6}
				</td>
				<td style="width:80px;" id="contenedor_descuento">
						<input type="hidden" id="descuento{$contador}_a" value="{$filas[filasProductos].9}"/>
						<input type="text" id="descuento{$contador}" onkeydown="return noletras(event);" class="texto-calculos" onkeyup="calculaPrecioFinal(this, {$contador})" onblur="aplicaSignoPesos(this, '%')" onfocus="quitaSignoPesos(this, '%')" value="{$filas[filasProductos].8}"/>
				</td>
				<td style="width:80px;" >
						<input id="precio_final{$contador}" type="hidden" class="texto-calculos" value="{$filas[filasProductos].11}"/>
						<input id="precio_final{$contador}_a" type="text" class="texto-calculos" onkeydown="return noletras(event);" onkeyup="calculaPorcentaje(this, {$contador})" onblur="aplicaSignoPesos(this, '$')" onfocus="quitaSignoPesos(this, '$')" value="{$filas[filasProductos].10}"/>
				</td>
				<td style="display:none"><input type="hidden" value="{$filas[filasProductos].7}" id="idProducto{$contador}"/></td>
				<td style="display:none"><input type="hidden" value="0" id="idCambio{$contador}"/></td>
				<td style="display:none">{$contador++}</td>
		</tr>

{sectionelse}
		<tr>
				<td>No existen productos con estas caracteristicas</td>
		</tr>
{/section}
</tbody>