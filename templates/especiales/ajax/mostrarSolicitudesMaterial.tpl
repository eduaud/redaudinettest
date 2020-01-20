<table border="0" class="detalle"> 
			<tbody>
			{assign var="contador" value="1"}
					{section name="solicitud" loop=$a_solicitudes}
						<tr>
							<td style="width:20px; text-align:center">{$contador}</td>
							<td style="width:150px; text-align:center">{$a_solicitudes[solicitud].Plaza}</td>
							<td style="width:80px; text-align:center">{$a_solicitudes[solicitud].Solicitud}</td>
							<td style="width:100px; text-align:center">{$a_solicitudes[solicitud].Fecha}</td>
							<td style="width:200px; text-align:center">{$a_solicitudes[solicitud].Empleado}</td>
							<td style="width:300px; text-align:center">
							<div style="width : auto">
									<input type="button" id="aprobar" class="botonesDetalle" value="Aprobar" onClick="apruebaSolicitud({$a_solicitudes[solicitud].id_control_solicitud_material})"/>
									<input type="button" id="rechazar" class="botonesDetalle" value="Rechazar" onClick="rechazaSolicitud({$a_solicitudes[solicitud].id_control_solicitud_material})"/>
									<br><br>
									<textarea style="width:150px" id="motivo-rechazo{$a_solicitudes[solicitud].id_control_solicitud_material}" class="rechazo"></textarea>
							</div>		
							
							</td>
							<td style="display:none"><input id="idOrden{$contador}" type="hidden" value="{$filas[filasOrdenes].1}"/></td>
							<td style="display:none">{$contador++}</td>
						</tr>
					{sectionelse}
						<tr>
							<td>No existen pedidos con estos datos de busqueda</td>
						</tr>
					{/section}
			</tbody>
		</table>