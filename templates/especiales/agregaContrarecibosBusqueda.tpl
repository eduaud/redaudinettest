{assign var="contador" value="1"}
{section name="contrato" loop=$a_contratos}
	<tr id="contrato_{$a_contratos[contrato].id_detalle}">
		<td style="width:150px; text-align:center">{$a_contratos[contrato].contrato}</td>
		<td style="width:80px; text-align:center">{$a_contratos[contrato].cuenta}</td>
		<td style="width:80px; text-align:center">{$a_contratos[contrato].cliente}</td>
		<td style="width:100px; text-align:center">{$a_contratos[contrato].fecha_activacion}</td>
		<td style="width:100px; text-align:center">{$a_contratos[contrato].tipoActivacion}</td>
		<td style="width:100px; text-align:center">{$a_contratos[contrato].estatus}</td>
		<td style="width:220px; text-align:center">
		<div style="width : auto">
		{if $a_contratos[contrato].id_accion_control eq 1 or $a_contratos[contrato].id_accion_control eq 100}
				<input type="button" id="Agregar" class="botonesD" value="Agregar" onClick="agregaContrarecibo({$a_contratos[contrato].id_detalle})"/>
		{/if}
				<input type="button" id="Rechazar" class="botonesD" value="Rechazar" onClick="RechazaContrarecibos({$a_contratos[contrato].id_detalle})"/>
				<br/>
				<textarea cols="20" rows="2" id="motivo_rechazo_{$a_contratos[contrato].id_detalle}"/>
		</div>		
		</td>
		<td style="display:none"><input id="idOrden{$contador}" type="hidden" value="{$filas[filasOrdenes].1}"/></td>
	
		{$contador++}
	</tr>
{sectionelse}
	<tr>
		<td>No existen contratos con estos datos de busqueda</td>
	</tr>
{/section}