{assign var="contador" value="1"}
		{section name="filasPedidos" loop=$filas}
				<tr>
						<td style="width:20px; text-align:center">{$contador}</td>
						<td style="width:18px; text-align:center"><input type="checkbox" id="idPedidoCheck{$contador}" value="{$filas[filasPedidos].5}" name="idPedidoCheck[]"/></td>
                        
                        
                       
						<td style="width:80px; text-align:center"> <a href="../../code/general/encabezados.php?t=YWRfcGVkaWRvcw==&k={$filas[filasPedidos].5}&op=2&v=1&tabla=&cadP1=MDI0ZG5CbGZqRjhibkJsZmpGOGJYQmxmakY4MzQ=&cadP2=MDI0WlhCbGZqRjhhWEJsZmpGOFozQmxmakU9MzQ=" TARGET="_new" TITLE"Ver pedido">{$filas[filasPedidos].0}</a></td>
						
                        
                        <td style="width:100px; text-align:center">{$filas[filasPedidos].3}</td>
						<td style="width:250px; text-align:center"><a href="../../code/general/encabezados.php?t=bmFfY2xpZW50ZXM=&k={$filas[filasPedidos].12}&op=2&v=1&tabla=&cadP1=MDI0ZG5CbGZqRjhibkJsZmpGOGJYQmxmakY4MzQ=&cadP2=MDI0WlhCbGZqRjhhWEJsZmpGOFozQmxmakU9MzQ=" TARGET="_new" TITLE"Ver Cliente">{$filas[filasPedidos].1}</a></td>
						<td style="width:150px; text-align:center">{$filas[filasPedidos].2}</td>
						<td style="width:150px; text-align:center">{$filas[filasPedidos].6}</td>
						<td style="width:150px; text-align:center">{$filas[filasPedidos].7}</td>
						<td style="width:150px; text-align:center">{$filas[filasPedidos].4}</td>
						<td style="width:120px; text-align:center">
								<select name="tipo_pago_sat" id="tipo_pago_sat{$filas[filasPedidos].5}" class="formas_pago">
										{section name="filasPagos" loop=$pagos}
												<option value="{$pagos[filasPagos].0}">{$pagos[filasPagos].1}</option>
										{/section}
								</select>
						</td>
						<td style="width:100px; text-align:center">
								<input style="width:70px; font-size:10px;" id="cuenta{$filas[filasPedidos].5}" type="text" onkeydown="return noletras(event);"/>
						</td>
						<td style="width:120px; text-align:center">
								<select name="factura_servicios" id="factura_servicios{$filas[filasPedidos].5}" class="formas_pago" style="width:40px;">
										{if $filas[filasPedidos].8 eq 1}
												{assign var="selecciona" value="1"}
												
										{else}
												{assign var="selecciona" value="2"}
												
										{/if}
										
										{html_options values=$si_no_id output=$si_no_nombre selected=$selecciona}
								</select>
						</td>
						<td style="display:none"><input id="idControlPedido{$contador}" type="hidden" value="{$filas[filasPedidos].5}"/></td>
						<td style="display:none">{$contador++}</td>
					</tr>
		{sectionelse}
				<tr>
						<td>No existen pedidos con estos datos de busqueda</td>
				</tr>
		{/section}
		
		