{assign var="contador" value="1"}
		{section name="filasFacturas" loop=$filas}
				<tr>
						<td style="width:20px; text-align:center">{$contador}</td>
						<td style="width:15px; text-align:center"><input type="checkbox" id="idPedidoCheck{$contador}" value="{$filas[filasFacturas].0}" name="idFacturaCheck[]"/></td>
						<td style="width:100px; text-align:center" id="nomFactura{$filas[filasFacturas].0}">{$filas[filasFacturas].1}</td>
						<td style="width:250px; text-align:center">{$filas[filasFacturas].2}</td>
						<td style="width:100px; text-align:center">{$filas[filasFacturas].3}</td>
						<td style="width:150px; text-align:center">{$filas[filasFacturas].4}</td>
						<td style="width:150px; text-align:center">{$filas[filasFacturas].5}</td>
						<td style="width:150px; text-align:center">{$filas[filasFacturas].6}</td>
						<td style="width:200px; text-align:center">
								<select id="slct_cobros{$filas[filasFacturas].0}" style="width:170px">
										{html_options values=$cobro_id output=$cobro_nombre}
								</select>
						</td>
						<td style="width:150px; text-align:center">
								<input style="width:120px; font-size:10px; text-align:right" id="montoFactura{$filas[filasFacturas].0}" type="text" onkeydown="return noletras(event);" value='{$filas[filasFacturas].4|regex_replace:"/[$]/":""}'/>
						</td>
						<td style="display:none"><input id="idFactura{$contador}" type="hidden" value="{$filas[filasFacturas].0}"/></td>
						<td style="display:none">{$contador++}</td>
					</tr>
		{sectionelse}
				<tr>
						<td>No existen facturas con estos datos de b&uacute;squeda</td>
				</tr>
		{/section}