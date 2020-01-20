<tbody>
{assign var="contador" value="1"}
{section name="filasProductos" loop=$filas}
		<tr>
				
				<td style="width:20px;">{$contador}</td>
				<td style="width:20px;"><input type="checkbox" id="idProducto{$contador}" value="{$filas[filasProductos].0}" name="productos[]"/></td>
				<td style="width:123px;">{$filas[filasProductos].1}</td>
				<td style="width:103px;">{$filas[filasProductos].2}</td>
				<td style="width:95px;">{$filas[filasProductos].3}</td>
				<td style="width:95px;">{$filas[filasProductos].4}</td>
				<td style="width:95px;">{$filas[filasProductos].5}</td>
				<td style="width:150px;">{$filas[filasProductos].6}</td>
				<td style="width:80px;">{$filas[filasProductos].7}</td>
				<td style="display:none">{$contador++}</td>
		</tr>

{sectionelse}
		<tr>
				<td>No existen productos con estas caracteristicas</td>
		</tr>
{/section}
</tbody>