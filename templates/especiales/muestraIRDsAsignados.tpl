{assign var="contador" value="1"}
<link href="{$rooturl}css/estilos.css" rel="stylesheet" type="text/css" />
<link href="{$rooturl}css/estilos_especiales.css" rel="stylesheet" type="text/css" />
<br />
<!-- {$sql} -->
<table border="0" id="detalle_irds">
	<thead>
		<tr bgcolor="#F2F2F2" height="20">
			<th width="50"  class="letra_encabezado" align="right">NO.&nbsp;</th>
			<th width="200" align="left" class="letra_encabezado">&nbsp;IRD</th>
		</tr>
	</thead>
	<tbody>
	{section name="filaNumeroSerie" loop=$filaNumerosSerie}
		<tr height="18">
			<td class="letra_detalle" align="right">{$contador++}&nbsp;</td>
			<td align="left" class="letra_detalle">&nbsp;{$filaNumerosSerie[filaNumeroSerie].0}</td>
		</tr>
	{sectionelse}
		<tr><td colspan="2" align="center" class="letras">&nbsp;</td></tr>
		<tr><td colspan="2" align="center" class="letras">No existen IRD's a mostrar</td></tr>
	{/section}
		<tr><td colspan="2" align="center" class="letras">&nbsp;</td>
		</tr>
	</tbody>
</table>
