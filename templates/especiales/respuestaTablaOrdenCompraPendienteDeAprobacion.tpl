{assign var="contador" value="1"}
	{section name="fila" loop=$filas}
		<tr>
			<td style="width:20px; text-align:center">{$contador}</td>
			<td style="width:200px; text-align:center">{$filas[fila].1}</td>
			{*<td style="width:100px; text-align:center"><a href="#" onclick="verOrdenDeCompra({$filas[fila].0});">{$filas[fila].2}</a></td>*}
			<td style="width:100px; text-align:center">{$filas[fila].2}</td>
			<td style="width:100px; text-align:center">{$filas[fila].3}</td>
			<td style="width:100px; text-align:center">{$filas[fila].4}</td>
			<td style="width:100px; text-align:center">{$filas[fila].8}</td>
			<td style="width:200px; text-align:center">{$filas[fila].9}</td>
			<td style="width:200px; text-align:center">{$filas[fila].7}</td>
			<td style="width:300px; text-align:center">
				<div style="width : auto">
					<input type="button" id="aprobar" class="small button grey" value="Aprobar" onClick="apruebaOrdenDeCompra({$contador})"/>
					<input type="button" id="rechazar" class="small button grey" value="Rechazar" onClick="rechazaOrdenDeCompra({$contador})"/><br />
					<textarea style="width:auto; height:25px;" id="motivo-rechazo{$contador}" class="rechazo"></textarea>
				</div>		
			</td>
			<td style="display:none">
			<input id="idOrdenCompra{$contador}" type="hidden" value="{$filas[fila].2}"/>
			<input id="idOrdenCompraControl{$contador}" type="hidden" value="{$filas[fila].10}"/>
			</td>
			<td style="display:none">{$contador++}</td>
		</tr>
	{sectionelse}
	<tr>
		<td width="1215" colspan="9" align="center">No existen &oacute;rdenes de compra pendientes de aprobaci&oacute;n estos criterios de b&uacute;squeda</td>
	</tr>
{/section}