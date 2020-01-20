{assign var="contador" value="1"}
<link href="{$rooturl}css/estilos.css" rel="stylesheet" type="text/css" />
<link href="{$rooturl}css/estilos_especiales.css" rel="stylesheet" type="text/css" />
<!-- {$sql} -->
<table>
	<tbody>
	{section name="fila" loop=$filas}
		<tr height="18">
			<td align="right" class="letra_detalle" width="50">{$contador++}&nbsp;</td>
			<td align="left" class="letra_detalle" width="200">&nbsp;{$filas[fila].2}</td>
			<td align="left" class="letra_detalle" width="200">&nbsp;{$filas[fila].3}</td>
			<td align="left" class="letra_detalle" width="200">&nbsp;{$filas[fila].4}</td>
			<td align="left" class="letra_detalle" width="200">&nbsp;{$filas[fila].14}</td>
			<td class="letra_detalle" align="center" width="50">
			<div style="
			background:{$filas[fila].16}; 
			height:16px; 
			width:16px; 
			float:left; 
			margin-left:14px;
			border: 0.1px solid #000000;
			overflow: auto;
			-webkit-background-clip: padding-box;
			-moz-background-clip: padding;
			background-clip: padding-box;
			-webkit-border-radius: 10px 10px 10px 10px;
			-moz-border-radius: 10px 10px 10px 10px;
			border-radius: 10px 10px 10px 10px;	
			"></div>
			</td>
			<td align="left" class="letra_detalle" width="250">&nbsp;{$filas[fila].18}</td>
		</tr>
	{sectionelse}
		<tr><td colspan="7" align="center" class="letra_detalle">&nbsp;</td></tr>
		<tr><td colspan="7" align="center" class="letra_detalle">No existen IRD's a mostrar</td></tr>
	{/section}
	</tbody>
</table>
