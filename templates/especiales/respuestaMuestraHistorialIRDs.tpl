{assign var="contador" value="1"}
<link href="{$rooturl}css/estilos.css" rel="stylesheet" type="text/css" />
<link href="{$rooturl}css/estilos_especiales.css" rel="stylesheet" type="text/css" />
<!-- {$sql} -->
<table>
	<tbody>
	{section name="fila" loop=$filas}
		<tr height="18">
			<td align="right" class="letra_detalle" width="50">{$contador++}&nbsp;</td>
			<td align="left" class="letra_detalle" width="200">&nbsp;{$filas[fila].8}</td>
			<td align="left" class="letra_detalle" width="550">&nbsp;{$filas[fila].12}</td>
		</tr>
	{sectionelse}
		<tr><td colspan="3" align="center" class="letra_detalle" width="800">&nbsp;</td></tr>
		<tr><td colspan="3" align="center" class="letra_detalle" width="800">No existen IRD's a mostrar</td></tr>
	{/section}
	</tbody>
</table>
