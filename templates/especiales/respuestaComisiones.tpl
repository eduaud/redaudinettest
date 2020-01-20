{assign var="contador" value="1"}
			{section name="filasPedidos" loop=$filasPedido}
					<tr>
							<td style="width:20px; text-align:center">{$contador}</td>
							<td style="width:18px; text-align:center">
									<input type="checkbox" id="idPedidoCheck{$contador}" value="{$filasPedido[filasPedidos].6}" name="idPedidoCheck[]"/>
							</td>
							<td style="width:200px; text-align:center">{$filasPedido[filasPedidos].0}</td>
							<td style="width:100px; text-align:center">{$filasPedido[filasPedidos].1}</td>
							<td style="width:150px; text-align:center">{$filasPedido[filasPedidos].3}</td>
							<td style="width:100px; text-align:center">{$filasPedido[filasPedidos].2}</td>
							<td style="width:200px; text-align:center">{$filasPedido[filasPedidos].4}</td>
							<td style="width:100px; text-align:center">{$filasPedido[filasPedidos].5}</td>
							<td style="width:100px; text-align:center">{$filasPedido[filasPedidos].8}</td>
							<td style="display:none"><input id="idPedido{$contador}" type="hidden" value="{$filasPedido[filasPedidos].6}"/></td>
							<td style="display:none"><input id="idVendedor{$contador}" type="hidden" value="{$filasPedido[filasPedidos].7}"/></td>
							<td style="display:none">{$contador++}</td>
					</tr>
			{sectionelse}
					<tr>
							<td>No existen pedidos relacionados a este vendedor</td>
					</tr>
			{/section}