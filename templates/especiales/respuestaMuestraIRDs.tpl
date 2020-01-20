{assign var="contador" value="1"}
<link href="{$rooturl}css/estilos.css" rel="stylesheet" type="text/css" />
<link href="{$rooturl}css/estilos_especiales.css" rel="stylesheet" type="text/css" />
<!-- {$sql} -->
<table>
	<tbody>
	{section name="fila" loop=$filas}
		<tr height="18">
			<td class="letra_detalle" align="right" width="50">{$contador++}&nbsp;</td>
			<td class="letra_detalle" align="left" width="325">&nbsp;<a href="#" onClick="muestraHistorialIRD('{$filas[fila].0}','{$filas[fila].1}'); muestraEstatusIRD('{$filas[fila].0}','{$filas[fila].1}');" class="letra_detalle">{$filas[fila].1}</a></td>
		</tr>
	{sectionelse}
		<tr><td colspan="2" align="center" class="letra_detalle" width="50">&nbsp;</td></tr>
		<tr><td colspan="2" align="center" class="letra_detalle" width="325">No existen IRD's a mostrar</td></tr>
	{/section}
	</tbody>
</table>