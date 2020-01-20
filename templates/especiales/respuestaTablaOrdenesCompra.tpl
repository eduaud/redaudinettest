								{assign var="contador" value="1"}
								{section name="filasOrdenes" loop=$filas}
										<tr>
												<td style="width:20px; text-align:center">{$contador}</td>
												<td style="width:100px; text-align:center">{$filas[filasOrdenes].1}</td>
												<td style="width:100px; text-align:center">{$filas[filasOrdenes].2}</td>
												<td style="width:200px; text-align:center">{$filas[filasOrdenes].3}</td>
												<td style="width:150px; text-align:center">{$filas[filasOrdenes].4}</td>
												<td style="width:150px; text-align:center">{$filas[filasOrdenes].5}</td>
												<td style="width:150px; text-align:center">{$filas[filasOrdenes].6}</td>
												<td style="width:200px; text-align:center">{$filas[filasOrdenes].7}</td>
												<td style="width:200px; text-align:center">
												<div style="width : auto">
														<input type="button" id="aprobar" class="botonesD" value="Aprobar" onClick="apruebaOrden({$contador})"/>
														<input type="button" id="rechazar" class="botonesD" value="Rechazar" onClick="rechazaOrden({$contador})"/>
														<input type="button" id="rechazar" class="botonesD" style="width:40px" value="Ver" onClick="verOrdenCompra({$filas[filasOrdenes].1})"/><br><br>
														<textarea style="width:150px" id="motivo-rechazo{$contador}" class="rechazo"></textarea>
												</div>		
												
												</td>
												<td style="display:none"><input id="idOrden{$contador}" type="hidden" value="{$filas[filasOrdenes].1}"/></td>
												<td style="display:none">{$contador++}</td>
										</tr>
								{sectionelse}
										<tr>
												<td>No existen ordenes con estos datos de busqueda</td>
										</tr>
								{/section}