				{assign var="contador" value="1"}
					{section name="fila" loop=$filas}
						<tr>
							<td style="width:24px; text-align:center">{$contador}{*$sql*}</td>
							<td style="width:50px; text-align:center">
								<input type="checkbox" id="idDetalleCheck_{$contador}" value="{$filas[fila].0}" name="idDetalleCheck_{$contador}"/>
								<!-- <input name="TxtIdDetalleMovimientoAlmacen{$contador}" type="" id="TxtIdDetalleMovimientoAlmacen{$contador}" value="{$filas[fila].9}" /> -->
							</td>
							<td style="width:250px; text-align:center">{$filas[fila].2}</td>
							<td style="width:250px; text-align:center">
								<a href="#" onclick="verMovimientoEntrada('{$filas[fila].0}');">{$filas[fila].0}</a>
								<input name="TxtIdControlMovimiento{$contador}" type="hidden" id="TxtIdControlMovimiento{$contador}" value="{$filas[fila].0}" />
								<input name="TxtIdMovimiento{$contador}" type="hidden" id="TxtIdMovimiento{$contador}" value="{$filas[fila].8}" />
							</td>
							<td style="width:200px; text-align:center">
								<a href="#" onclick="verOrdenDeCompra('{$filas[fila].6}');">{$filas[fila].7}</a>
								{*$filas[fila].7*}
								<input name="TxtIdOrdenCompra{$contador}" type="hidden" id="TxtIdOrdenCompra{$contador}" value="{$filas[fila].7}" />
								<input name="TxtIdControlOrdenCompra{$contador}" type="hidden" id="TxtIdControlOrdenCompra{$contador}" value="{$filas[fila].6}" />
							</td>
							<td style="width:250px; text-align:center">
							<input name="TxtIdProveedor{$contador}" type="hidden" id="TxtIdProveedor{$contador}" value="{$filas[fila].4}" />
							{$filas[fila].5}
							</td>
						</tr>
						{$contador++}
					{sectionelse}
					<tr>
						<td width="1050" align="center">No existen &oacute;rdenes con estos datos de b&uacute;squeda</td>
					</tr>
				{/section}