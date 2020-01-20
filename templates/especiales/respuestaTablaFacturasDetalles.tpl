{assign var="contador" value="1"}
		{section name="filasFacturas" loop=$filas}
				<tr>
						<td style="width:20px; text-align:center">{$contador}</td>
						<td style="width:22px; text-align:center"><input type="checkbox" id="idFacturaCheck{$contador}" value="{$filas[filasFacturas].0}" name="idFacturaCheck[]"/></td>
						<td style="width:200px; text-align:center">{$filas[filasFacturas].1}</td>
						<td style="width:200px; text-align:center">{$filas[filasFacturas].2}</td>
						<td style="width:150px; text-align:center">{$filas[filasFacturas].3}</td>
						<td style="width:150px; text-align:center">{$filas[filasFacturas].4}</td>
						<td style="display:none"><input id="idFacturaDet{$contador}" type="hidden" value="{$filas[filasFacturas].5}"/></td>
						<td style="display:none">{$contador++}</td>
					</tr>
		{sectionelse}
				<tr>
						<td>No existen prodcutos relacionados a esta factura</td>
				</tr>
		{/section}