{assign var="contador" value="1"}
<link href="{$rooturl}css/estilos.css" rel="stylesheet" type="text/css" />
<link href="{$rooturl}css/estilos_especiales.css" rel="stylesheet" type="text/css" />
<!-- {$sql} -->
<table id="ResulBusqListContratos">
	<tbody>
	{section name="fila" loop=$filas}
		<tr height="18" id="contraNumTR{$contador}">
			<td align="right" class="letra_detalle" width="10">
			{if ($filas[fila].9 eq "") }
				<a href="#" onclick="editarNumeroContrato('{$filas[fila].0}');">{$contador++}</a>
			{else}
				{$contador++}
			{/if}
			</td>
			<td align="left" width="40"><a href="#" onClick="muestraHistorialContrato('{$filas[fila].0}','{$filas[fila].2}','{$filas[fila].10}','{$filas[fila].1}'); muestraLinkParaAgregarTipoMovimiento('{$filas[fila].0}','{$filas[fila].2}','{$filas[fila].10}','{$filas[fila].1}'); muestraIRDsAsignados('{$filas[fila].0}','{$filas[fila].1}'); limpiaEditor(); marcarTR('{$contador}');" class="letra_detalle">{$filas[fila].1}</a></td>
			<td align="left" class="letra_detalle" width="80">{$filas[fila].2}</td>
			<td align="left" class="letra_detalle" width="140">{$filas[fila].4}</td>
			<td align="left" class="letra_detalle" width="150">{$filas[fila].6}</td>
		</tr>
	{sectionelse}
		<tr><td colspan="5" class="letra_detalle" height="20" align="center" width="420">&nbsp;&nbsp;No existen contratos mostrar&nbsp;</td></tr>
	{/section}
	</tbody>
</table>
