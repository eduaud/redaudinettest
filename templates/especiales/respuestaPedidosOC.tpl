{assign var="contador" value="1"}
		{section loop=$pedidos name=filaPedido}
				<div id="cabezal">
				<p>Almacen: <select name="slct_almacen{$pedidos[filaPedido].0}" id="slct_almacen{$pedidos[filaPedido].0}" class="campos_req">
						{html_options values=$alm_id output=$alm_nombre}
				</select></p>
				<p>Pedido: {$pedidos[filaPedido].1}</p>
				<p>Cliente: {$pedidos[filaPedido].2}</p>
				<!--ID Pedido -->
				<input type="hidden" value="{$pedidos[filaPedido].1}" id="nomPedido{$pedidos[filaPedido].0}" name="nomPedido{$pedidos[filaPedido].0}"/>
				<!--ID Control Movimiento -->
				<input type="hidden" value="{$pedidos[filaPedido].3}" id="movimiento{$pedidos[filaPedido].0}" name="movimiento{$pedidos[filaPedido].0}"/>
				</div>
				<table class="productos">
						<thead>
								<tr>
										<th style="width:20px">&nbsp;</th>
										<th style="width:400px">Producto</th>
										<th style="width:120px">Cantidad Pedido</th>
										<th style="width:120px">Cantidad Entregada</th>
										<th style="width:120px">Cantidad Devuelta</th>
										<th style="width:120px">Cantidad a Devolver</th>
								</tr>
						</thead>
						{section loop=$detalles[filaPedido] name=filaDetalle}
								<tr>
										<td style="width:20px"><input style="width:20px"type="checkbox" id="checkProd{$pedidos[filaPedido].0}" name="checkProd{$pedidos[filaPedido].0}[]" value="{$detalles[filaPedido][filaDetalle].0}"/></td>
										<td style="width:400px">{$detalles[filaPedido][filaDetalle].1}</td>
										<td style="width:120px; text-align:center">{$detalles[filaPedido][filaDetalle].2}</td>
										<td style="width:120px; text-align:center">{$detalles[filaPedido][filaDetalle].3}</td>
										<td style="width:120px; text-align:center">{$detalles[filaPedido][filaDetalle].4}</td>
										<td style="width:120px; text-align:center">
												<input style="width:100px" type="text" name="devolver{$pedidos[filaPedido].0}{$detalles[filaPedido][filaDetalle].0}" id="devolver{$pedidos[filaPedido].0}{$detalles[filaPedido][filaDetalle].0}"/>
										</td>
										<!--Documento Interno--->
										<td style="display:none">
												<input type="hidden" name="interno{$pedidos[filaPedido].0}{$detalles[filaPedido][filaDetalle].0}" id="interno{$pedidos[filaPedido].0}{$detalles[filaPedido][filaDetalle].0}" value="{$detalles[filaPedido][filaDetalle].6}"/>
										</td>
								</tr>
						{/section}
				</table>
				<br>
				<div style="width:960px; text-align:right">
						<input type="button" id="entradaD" class="botonesD" value="Generar Entrada" onClick="generaDevolucion({$pedidos[filaPedido].0})"/>
				</div>
				<br>
				<input type="hidden" value="{$contador++}"/>
		{sectionelse}
				<p style="font-weight:bold; color:#E55F70">No hay pedidos por devolver</p>
		{/section}
		