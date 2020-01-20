<tbody>
		{assign var="contador" value="1"}
		{section name="filasPagos" loop=$filas}
				<tr>
						<td style="width:20px; text-align:center">{$contador}</td>
						{if $filas[filasPagos].1 eq 0 AND $filas[filasPagos].12 neq 2}
								<td style="width:18px; text-align:center"><input type="checkbox" id="idPagosCheck{$contador}" value="{$filas[filasPagos].0}" name="idPagosCheck[]"/></td>
						{else}
								<td style="width:18px; text-align:center">&nbsp;</td>
						{/if}
						
						{if $filas[filasPagos].1 eq 0}
								<td style="width:80px; text-align:center">S/R</td>
						{else}
								<td style="width:80px; text-align:center">{$filas[filasPagos].1}</td>
						{/if}
						<td style="width:100px; text-align:center">{$filas[filasPagos].2}</td>
						<td style="width:200px; text-align:center">{$filas[filasPagos].3}</td>
						<td style="width:200px; text-align:center">{$filas[filasPagos].4}</td>
						<td style="width:200px; text-align:center">{$filas[filasPagos].5}</td>
						<td style="width:150px; text-align:center">{$filas[filasPagos].6}</td>
						<td style="width:150px; text-align:center">{$filas[filasPagos].7}</td>
						<td style="width:150px; text-align:center">{$filas[filasPagos].8}</td>
						<td style="width:100px; text-align:center">{$filas[filasPagos].9}</td>
						<td style="width:250px; text-align:center">{$filas[filasPagos].10}</td>
						<td style="width:80px; text-align:center"><img src="../../imagenes/general/cancelar_icono.png"/ onClick="cancelaPago({$filas[filasPagos].0})" style="cursor:pointer;"></td>
						{section name="cuentaRecibo" loop=$recibos}
								{if $recibos[cuentaRecibo].0 eq $filas[filasPagos].1 && $recibos[cuentaRecibo].2 eq $contador}
										<td rowspan="{$recibos[cuentaRecibo].1}" style="width:80px; text-align:center; vertical-align:middle" >
												<img src="../../imagenes/general/print.gif" onClick="generaRecibo({$filas[filasPagos].1})" style="cursor:pointer;"/>
										</td>
								{/if}
						{/section}
						<td style="display:none"><input type="hidden" value="{$filas[filasPagos].11}" id="pedidoTabla{$contador}"/></td>
						<td style="display:none"><input type="hidden" value="{$filas[filasPagos].12}" id="confTabla{$contador}"/></td>
						<td style="display:none">{$contador++}</td>
					</tr>
		{sectionelse}
		<tr>
				<td>No existen pagos relacionados a este pedido</td>
		</tr>
		{/section}
</tbody>