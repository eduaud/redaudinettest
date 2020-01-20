<div style="margin:20px 0; width:100%">
		<table class="busca-pedido">
		<caption>Movimientos de Salida por Venta -</caption>
				<thead>
						<tr>
								<th style="width:20px;">No</th>
								<th style="width:22px;"><input type="checkbox" id="sel-todos" onClick="seleccionarCheck(this, 'cuerpo-pedido');"/></th>
								<th style="width:70px;">Documento</th>
								<th style="width:150px;">Ruta</th>
								<th style="width:70px;">Pedido</th>
								<th style="width:120px;">Fecha y Hora</th>
								<th style="width:150px;">Producto</th>
								<th style="width:120px;">SKU</th>
								<th style="width:70px;">Cantidad</th>
								<th style="width:67px;">Ver</th>
						</tr>
				</thead>
		</table>
		<div id="scroll-tabla">
				<table border="0" class="cuerpo-pedido"> 
						<tbody>
						{assign var="contador" value="1"}
							{section name="filasPedidos" loop=$filasPedido}
									<tr>
											<td style="width:20px; text-align:center">{$contador}</td>
											<td style="width:18px; text-align:center"><input type="checkbox" id="idPedidoCheck{$contador}" value="{$filasPedido[filasPedidos].0}" name="idPedidoCheck[]"/></td>
											<td style="width:70px; text-align:center">{$filasPedido[filasPedidos].1}</td>
											<td style="width:150px; text-align:center">{$filasPedido[filasPedidos].2}</td>
											<td style="width:70px; text-align:center">{$filasPedido[filasPedidos].3}</td>
											<td style="width:120px; text-align:center">{$filasPedido[filasPedidos].4}</td>
											<td style="width:150px; text-align:center">{$filasPedido[filasPedidos].5}</td>
											<td style="width:120px; text-align:center">{$filasPedido[filasPedidos].6}</td>
											<td style="width:70px; text-align:center">{$filasPedido[filasPedidos].7}</td>
											<td style="width:60px; text-align:center"><a href="{$rutaAlmacen|cat:$filasPedido[filasPedidos].8}" target="_blank">Movimiento</a></td>
											<td style="display:none"><input id="idPedido{$contador}" type="hidden" value="{$filasPedido[filasPedidos].0}"/></td>
											<td style="display:none">{$contador++}</td>
										</tr>
							{sectionelse}
									<tr>
											<td>No existen movimientos con estos datos de busqueda</td>
									</tr>
							{/section}
						</tbody>
				</table>
		</div>
</div>
<!-- Grid de recoleccion de pedidos -->
<div style="margin:20px 0; width:100%">
		<table class="busca-recoleccion">
		<caption>Recoleccion de Productos a Clientes</caption>
				<thead>
						<tr>
								<th style="width:20px;">No</th>
								<th style="width:22px;"><input type="checkbox" id="sel-todos" onClick="seleccionarCheck(this, 'cuerpo-recoleccionCliente');"/></th>
								<th style="width:70px;">Documento</th>
								<th style="width:150px;">Ruta</th>
								<th style="width:70px;">Pedido</th>
								<th style="width:120px;">Fecha y Hora</th>
								<th style="width:150px;">Producto</th>
								<th style="width:120px;">SKU</th>
								<th style="width:70px;">Cantidad</th>
								<th style="width:67px;">Ver</th>
						</tr>
				</thead>
		</table>
		<div id="scroll-tabla">
				<table border="0" class="cuerpo-recoleccionCliente"> 
						<tbody>
						{assign var="contador" value="1"}
							{section name="filasRecoleccion" loop=$filasRec}
									<tr>
											<td style="width:20px; text-align:center">{$contador}</td>
											<td style="width:18px; text-align:center">
													<input type="checkbox" id="idRecCheck{$contador}" value="{$filasRec[filasRecoleccion].0}" name="idRecCheck[]"/>
											</td>
											<td style="width:70px; text-align:center">{$filasRec[filasRecoleccion].1}</td>
											<td style="width:150px; text-align:center">{$filasRec[filasRecoleccion].2}</td>
											<td style="width:70px; text-align:center">{$filasRec[filasRecoleccion].3}</td>
											<td style="width:120px; text-align:center">{$filasRec[filasRecoleccion].4}</td>
											<td style="width:150px; text-align:center">{$filasRec[filasRecoleccion].5}</td>
											<td style="width:120px; text-align:center">{$filasRec[filasRecoleccion].6}</td>
											<td style="width:70px; text-align:center">{$filasRec[filasRecoleccion].7}</td>
											<td style="width:60px; text-align:center"><a href="{$rutaOrRecCliente|cat:$filasRec[filasRecoleccion].1}" target="_blank">Movimiento</a></td>
											<td style="display:none"><input id="idRec{$contador}" type="hidden" value="{$filasRec[filasRecoleccion].0}"/></td>
											<td style="display:none">{$contador++}</td>
										</tr>
							{sectionelse}
									<tr>
											<td>No existen ordenes con estos datos de busqueda</td>
									</tr>
							{/section}
						</tbody>
				</table>
		</div>
</div>
<!-- Grid de traspasos asucursales -->
<div style="margin:20px 0; width:100%">
		<table class="busca-recoleccion">
		<caption>Traspaso de Sucursal a Cedis -- Sucursal</caption>
				<thead>
						<tr>
								<th style="width:20px;">No</th>
								<th style="width:22px;"><input type="checkbox" id="sel-todos" onClick="seleccionarCheck(this, 'cuerpo-recoleccionSucPed');"/></th>
								<th style="width:150px;">Ruta</th>
								<th style="width:100px;">Orden de recolecci&oacute;n</th>
								<th style="width:100px;">Fecha de recolecci&oacute;n</th>
								<th style="width:200px;">Sucursal</th>
								<th style="width:305px;">Direcci&oacute;n de recolecci&oacute;n</th>
								<th style="width:200px;">Observaciones</th>
						</tr>
				</thead>
		</table>
		<div id="scroll-tabla">
				<table border="0" class="cuerpo-recoleccionSucPed"> 
						<tbody>
						{assign var="contador" value="1"}
							{section name="filasRecoleccionSucPed" loop=$filasRecSucPed}
									<tr>
											<td style="width:20px; text-align:center">{$contador}</td>
											<td style="width:18px; text-align:center"><input type="checkbox" id="idRecSucPedCheck{$contador}" value="{$filasRecSucPed[filasRecoleccionSucPed].1}" name="idRecSucPedCheck[]"/></td>
											<td style="width:150px; text-align:center">{$filasRecSucPed[filasRecoleccionSucPed].0}</td>
											<td style="width:100px; text-align:center">{$filasRecSucPed[filasRecoleccionSucPed].1}</td>
											<td style="width:100px; text-align:center">{$filasRecSucPed[filasRecoleccionSucPed].3}</td>
											<td style="width:200px; text-align:center">{$filasRecSucPed[filasRecoleccionSucPed].4}</td>
											<td style="width:300px; text-align:center">{$filasRecSucPed[filasRecoleccionSucPed].5}</td>
											<td style="width:300px; text-align:center">{$filasRecSucPed[filasRecoleccionSucPed].6}</td>
											<td style="display:none"><input id="idRecSucPed{$contador}" type="hidden" value="{$filasRecSucPed[filasRecoleccionSucPed].1}"/></td>
											<td style="display:none">{$contador++}</td>
										</tr>
							{sectionelse}
									<tr>
											<td>No existen ordenes con estos datos de busqueda</td>
									</tr>
							{/section}
						</tbody>
				</table>
		</div>
</div>
<!-- Grid de traspaso de cedis -->
<div style="margin:20px 0; width:100%">
		<table class="busca-recoleccion">
		<caption>Traspaso de Sucursal a Cedis -- Pedidos</caption>
				<thead>
						<tr>
								<th style="width:20px;">No</th>
								<th style="width:22px;"><input type="checkbox" id="sel-todos" onClick="seleccionarCheck(this, 'cuerpo-recoleccionSucPed2');"/></th>
								<th style="width:150px;">Ruta</th>
								<th style="width:100px;">Orden de recolecci&oacute;n</th>
								<th style="width:100px;">Fecha y hora de recolecci&oacute;n</th>
								<th style="width:200px;">Sucursal</th>
								<th style="width:305px;">Direcci&oacute;n de recolecci&oacute;n</th>
						</tr>
				</thead>
		</table>
		<div id="scroll-tabla">
				<table border="0" class="cuerpo-recoleccionSucPed2"> 
						<tbody>
						{assign var="contador" value="1"}
							{section name="filasRecoleccionSucPed2" loop=$filasRecSucPed2}
									<tr>
											<td style="width:20px; text-align:center">{$contador}</td>
											<td style="width:18px; text-align:center"><input type="checkbox" id="idRecSucPedCheck2{$contador}" value="{$filasRecSucPed2[filasRecoleccionSucPed2].1}" name="idRecSucPedCheck2[]"/></td>
											<td style="width:150px; text-align:center">{$filasRecSucPed2[filasRecoleccionSucPed2].0}</td>
											<td style="width:100px; text-align:center">{$filasRecSucPed2[filasRecoleccionSucPed2].1}</td>
											<td style="width:100px; text-align:center">{$filasRecSucPed2[filasRecoleccionSucPed2].3}</td>
											<td style="width:200px; text-align:center">{$filasRecSucPed2[filasRecoleccionSucPed2].4}</td>
											<td style="width:300px; text-align:center">{$filasRecSucPed2[filasRecoleccionSucPed2].5}</td>
											<td style="display:none"><input id="idRecSucPed{$contador}" type="hidden" value="{$filasRecSucPed2[filasRecoleccionSucPed2].1}"/></td>
											<td style="display:none">{$contador++}</td>
										</tr>
							{sectionelse}
									<tr>
											<td>No existen ordenes con estos datos de busqueda</td>
									</tr>
							{/section}
						</tbody>
				</table>
		</div>
</div>