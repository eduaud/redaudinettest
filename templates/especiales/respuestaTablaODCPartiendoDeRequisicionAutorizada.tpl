				{assign var="contador" value="1"}
					{section name="fila" loop=$filas}
						<tr>
							<td style="width:10px; text-align:center">{$contador}{*$sql*}</td>
							<td style="width:10px; text-align:center"><input type="checkbox" id="idDetalleCheck_{$contador}" value="{$filas[fila].11}" name="idDetalleCheck_{$contador}"/></td>
							<td style="width:50px; text-align:center">{$filas[fila].0}</td>
							<td style="width:70px; text-align:center">{$filas[fila].1}</td>
							<td style="width:100px; text-align:center">{$filas[fila].2}</td>
							<td style="width:100px; text-align:center">{$filas[fila].3}</td>
							<td style="width:100px; text-align:center">{$filas[fila].4}</td>
							<td style="width:100px; text-align:center">{$filas[fila].5}</td>
							<td style="width:100px; text-align:center">{$filas[fila].6}</td>
							<td style="width:100px; text-align:center">{$filas[fila].7}</td>
							<td style="width:100px; text-align:center">{$filas[fila].8}</td>
							<td style="width:100px; text-align:center">{$filas[fila].9}</td>
							<td style="width:90px; text-align:right">{*$sql*}
							<select name="c-det-prov_{$contador}" id="c-det-prov_{$contador}" class="select-detalle-pequeno" onchange="asignaPermiteMezcla(this.value,'{$contador}');" onclick="obtenerProveedorODCdeProducto(this, 'c-det-prov_{$contador}', 'ad_proveedores', '{$filas[fila].10}','{$contador}');"></select>
							{*<select name="c-det-prov_{$contador}" id="c-det-prov_{$contador}" class="select-detalle-pequeno" onchange="asignaPermiteMezcla(this.value,'{$contador}');">*}
								{*<option value="-1">Seleccione la Plaza</option>*}
								{*section name="proveedor" loop=$proveedor_detalles*}
							    <option value="{$proveedor_detalles[proveedor].0}">{$proveedor_detalles[proveedor].1}</option>
								{*/section*}
				      </select>
								<input id="permite_mezcla_de_productos{$contador}" name="permite_mezcla_de_productos{$contador}" type="hidden" value=""/>
							</td>
							<td style="width:90px; text-align:right">
								<select name="c-det-almacen_{$contador}" id="c-det-almacen_{$contador}" class="select-detalle-pequeno" onclick="obtenerAlmacenDePlaza(this, 'c-det-almacen_{$contador}', 'ad_almacenes', '{$filas[fila].12}');"></select>
							</td>
							<td style="width:75px; text-align:center">
								<input type="button" id="cancelar" class="small button grey" value="Cancelar" onClick="cancelarDetalleDeRequisicionAutorizada({$filas[fila].11}, {$contador})"/>
							</td> 
							<td style="display:none; width:0px;">
							<input id="idRequisicion{$contador}" name="idRequisicion{$contador}" type="hidden" value="{$filas[fila].0}"/>
							<input id="idSucursal{$contador}" name="idSucursal{$contador}" type="hidden" value="{$filas[fila].12}"/>
							<input id="idUsuarioSolicita{$contador}" name="idUsuarioSolicita{$contador}" type="hidden" value="{$filas[fila].13}"/>
							<input id="idProducto{$contador}" name="idProducto{$contador}" type="hidden" value="{$filas[fila].10}"/>
							<input id="cantidad{$contador}" name="cantidad{$contador}" type="hidden" value="{$filas[fila].4}"/>
							<input id="precio{$contador}" name="precio{$contador}" type="hidden" value="{$filas[fila].14}"/>
							<input id="importe{$contador}" name="importe{$contador}" type="hidden" value="{$filas[fila].15}"/>
							<input id="idTipoProducto{$contador}" name="idTipoProducto{$contador}" type="hidden" value="{$filas[fila].16}"/>
							<input id="fechaRequerida{$contador}" name="fechaRequerida{$contador}" type="hidden" value="{$filas[fila].9}"/>
							</td>
							<td style="display:none; width:0px;">{$contador++}</td>
						</tr>
					{sectionelse}
					<tr>
						<td width="1210" align="center">{*$sql*}No existen requisiciones autorizadas que coincidan con estos criterios de b&uacute;squeda</td>
					</tr>
				{/section}