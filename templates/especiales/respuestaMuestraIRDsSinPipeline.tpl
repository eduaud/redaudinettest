{assign var="contador" value="1"}
<link href="{$rooturl}css/estilos.css" rel="stylesheet" type="text/css" />
<link href="{$rooturl}css/estilos_especiales.css" rel="stylesheet" type="text/css" />
<!-- {$sql} -->
<table id="irds_sin_pipeline" class="irds_sin_pipeline">
	<thead>
		<tr height="20" style="display:none;">
			<th width="50"  class="letra_encabezado" align="right">NO.&nbsp;</th>
			<th width="100" class="letra_encabezado"align="left">&nbsp;IRDS</th>
			<th width="100" class="letra_encabezado"align="left">&nbsp;PRODUCTO</th>
			<th width="150" class="letra_encabezado"align="left">&nbsp;FECHA ALTA</th>
		</tr>
	</thead>
	<tbody>
	{section name="fila" loop=$filas}
		<tr height="18">
			<td class="letra_detalle" align="right" width="50">{$contador++}&nbsp;</td>
			<td class="letra_detalle" align="left" width="100">&nbsp;{$filas[fila].1}</td>
			<td class="letra_detalle" align="left" width="100">&nbsp;{$filas[fila].3}</td>
			<td class="letra_detalle" align="left" width="150">&nbsp;{$filas[fila].4}</td>
		</tr>
	{sectionelse}
		<tr><td colspan="4" align="center" class="letra_detalle" width="400" height="20">No existen IRD's a mostrar</td></tr>
	{/section}
	</tbody>
</table>