{assign var="contador" value="1"}
<link href="{$rooturl}css/estilos.css" rel="stylesheet" type="text/css" />
<link href="{$rooturl}css/estilos_especiales.css" rel="stylesheet" type="text/css" />
<!-- {$sql} -->
<table border="0" id="detalle_irds">
	<thead>
		<tr bgcolor="#F2F2F2" height="20" style="display:none;">
			<th width="50"  class="letra_encabezado" align="right">NO.&nbsp;</th>
			<th width="350" align="left" class="letra_encabezado">&nbsp;IRD</th>
		</tr>
	</thead>
	<tbody>
	{section name="filaNumeroSerie" loop=$filaNumerosSerie}
		<tr height="18">
			<td width="50" class="letra_detalle" align="right">{$contador++}&nbsp;</td>
			<td width="350" align="left" class="letra_detalle">&nbsp;{$filaNumerosSerie[filaNumeroSerie].0}</td>
		</tr>
	{sectionelse}
		<tr><td colspan="2" align="center" class="letra_detalle" height="20" width="400">No existen IRD's a mostrar</td></tr>
	{/section}
	</tbody>
</table>
