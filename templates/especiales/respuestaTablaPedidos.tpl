{assign var="contador" value="1"}
		{section name="filasPedidos" loop=$filas}
				<tr>
						<td style="width:20px; text-align:center">{$contador}</td>
						<td style="width:18px; text-align:center"><input type="checkbox" id="idPedidoCheck{$contador}" value="{$filas[filasPedidos].3}" name="idPedidoCheck[]"/></td>
						<td style="width:100px; text-align:center">{$filas[filasPedidos].0}</td>
						<td style="width:250px; text-align:center">{$filas[filasPedidos].1}</td>
						<td style="width:150px; text-align:center">{$filas[filasPedidos].2}</td>
						<td style="width:150px; text-align:center">{$filas[filasPedidos].4}</td>
						<td style="width:150px; text-align:center">{$filas[filasPedidos].5}</td>
						<td style="width:150px; text-align:center">&nbsp;{$filas[filasPedidos].6}</td>
						<td style="display:none"><input id="idPedido{$contador}" type="hidden" value="{$filas[filasPedidos].3}"/></td>
						<td style="display:none">{$contador++}</td>
					</tr>
		{sectionelse}
				<tr>
						<td>No existen ordenes con estos datos de busqueda</td>
				</tr>
		{/section}