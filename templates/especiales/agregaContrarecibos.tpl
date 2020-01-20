{assign var="contador" value="1"}
{section name="contrato" loop=$a_contratos}
	<tr id="fila_{$a_contratos[contrato].id_control_contrato}">
		<td style="width:20px; text-align:center;display:none;" class="sucursal">{$a_contratos[contrato].id_sucursal}</td>
		<td style="width:20px; text-align:center;display:none;" class="contrato">{$a_contratos[contrato].id_detalle}</td>
		<td style="width:20px; text-align:center;display:none;" class="cliente">{$a_contratos[contrato].id_cliente}</td>
		<td style="width:150px; text-align:center">{$a_contratos[contrato].contrato}</td>
		<td style="width:80px; text-align:center">{$a_contratos[contrato].cuenta}</td>
		<td style="width:80px; text-align:center">{$a_contratos[contrato].cliente}</td>
		<td style="width:100px; text-align:center">{$a_contratos[contrato].fecha_activacion}</td>
		<td style="width:100px; text-align:center">{$a_contratos[contrato].tipoActivacion}</td>
		<td style="width:100px; text-align:center">{$a_contratos[contrato].estatus}</td>
		<td style="width:100px; text-align:center"><input style="width:100px;" type="button" value="Eliminar" onclick="eliminarFilaContrarecibo('{$a_contratos[contrato].id_control_contrato}');"/></td>
	</tr>
{$contador++}
{/section}