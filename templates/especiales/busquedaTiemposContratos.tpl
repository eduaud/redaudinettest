{if $Body eq '0'}
	{assign var="contador" value="1"}
	{section name="Pendientes" loop=$a_PendientesEntrega}
		<tr>
			<td style="width:3.3%;">{$contador++}</td>
			<td style="width:8.8%;">{$a_PendientesEntrega[Pendientes].contrato}</td>
			<td style="width:8.8%;">{$a_PendientesEntrega[Pendientes].cuenta}</td>
			<td style="width:17.3%;">{$a_PendientesEntrega[Pendientes].distribuidor}</td>
			<td style="width:8.8%;">{$a_PendientesEntrega[Pendientes].fecha_activacion}</td>
			<td style="width:8.8%;text-align:right;">{$a_PendientesEntrega[Pendientes].comision}</td>
			<td style="width:8.8%;">{$a_PendientesEntrega[Pendientes].fecha_vencimiento}</td>
			<td style="width:7.8%;">{$a_PendientesEntrega[Pendientes].dias_transcurridos}</td>
			<td style="width:6.3%;">
				<div class="circulo" style="margin-left:15px;background: {$a_PendientesEntrega[Pendientes].codigo_color};"></div>
			</td>
		</tr>
	{sectionelse}
		<td>No hay contratos pendientes de entrega.</td>
	{/section}
{elseif $Body eq '1'}
	{assign var="contador" value="1"}
	{section name="Entregados" loop=$a_Entregados}
		<tr>
			<td style="width:3.3%;">{$contador++}</td>
			<td style="width:8.8%;">{$a_Entregados[Entregados].contrato}</td>
			<td style="width:8.8%;">{$a_Entregados[Entregados].cuenta}</td>
			<td style="width:17.3%;">{$a_Entregados[Entregados].distribuidor}</td>
			<td style="width:8.8%;">{$a_Entregados[Entregados].fecha_activacion}</td>
			<td style="width:8.8%;text-align:right;">{$a_Entregados[Entregados].comision}</td>
			<td style="width:8.8%;">{$a_Entregados[Entregados].fecha_entrega}</td>
			<td style="width:6.3%;">{$a_Entregados[Entregados].contrarecibo}</td>
			<td style="width:7.8%;">
				<div class="circulo" style="margin-left:15px;background: {$a_Entregados[Entregados].codigo_color};"></div>
			</td>
		</tr>
	{sectionelse}
		<td>No hay contratos entregados.</td>
	{/section}
{elseif $Body eq '2'}
	{assign var="contador" value="1"}
	{section name="Rechazado" loop=$a_Rechazados}
	<tr>
		<td style="width:3.3%;">{$contador++}</td>
		<td style="width:8.8%;">{$a_Rechazados[Rechazado].contrato}</td>
		<td style="width:8.8%;">{$a_Rechazados[Rechazado].cuenta}</td>
		<td style="width:17.3%;">{$a_Rechazados[Rechazado].distribuidor}</td>
		<td style="width:8.8%;">{$a_Rechazados[Rechazado].fecha_activacion}</td>
		<td style="width:8.8%;text-align:right;">{$a_Rechazados[Rechazado].comision}</td>
		<td style="width:8.8%;">{$a_Rechazados[Rechazado].fecha_rechazo}</td>
		<td style="width:6.3%;">{$a_Rechazados[Rechazado].fecha_vencimiento}</td>
		<td style="width:7.8%;">{$a_Rechazados[Rechazado].dias_transcurridos}</td>
		<td style="width:7.8%;">{$a_Rechazados[Rechazado].motivo_rechazo}</td>
		<td style="width:7.8%;">
			<div class="circulo" style="margin-left:15px;background: {$a_Rechazados[Rechazado].codigo_color};"></div>
		</td>
	</tr>
	{sectionelse}
		<td>No hay contratos rechazados.</td>
	{/section}
{elseif $Body eq '3'}
	{assign var="contador" value="1"}
	{section name="Reagendado" loop=$a_Reagendado}
		<tr>
			<td style="width:2.3%;">{$contador++}</td>
			<td style="width:7.3%;">{$a_Reagendado[Reagendado].contrato}</td>
			<td style="width:7.3%;">{$a_Reagendado[Reagendado].cuenta}</td>
			<td style="width:15.3%;">{$a_Reagendado[Reagendado].distribuidor}</td>
			<td style="width:6.8%;">{$a_Reagendado[Reagendado].fecha_activacion}</td>
			<td style="width:6.8%;">{$a_Reagendado[Reagendado].comision}</td>
			<td style="width:6.8%;">{$a_Reagendado[Reagendado].fecha_rechazo}</td>
			<td style="width:6.3%;">{$a_Reagendado[Reagendado].contrarecibo}</td>
			<td style="width:6.8%;">{$a_Reagendado[Reagendado].fecha_vencimiento}</td>
			<td style="width:6.8%;">{$a_Reagendado[Reagendado].dias_transcurridos}</td>
			<td style="width:6.8%;">{$a_Reagendado[Reagendado].motivo_rechazo_1}</td>
			<td style="width:6.8%;">{$a_Reagendado[Reagendado].motivo_rechazo_2}</td>
			<td style="width:6.8%;">{$a_Reagendado[Reagendado].motivo_rechazo_3}</td>
			<td style="width:6.8%;">
				<div class="circulo" style="margin-left:35px;background: {$a_Reagendado[Reagendado].codigo_color};"></div>
			</td>
		</tr>
		{sectionelse}
		<td>No hay contratos reagendados.</td>
		{/section}
{elseif $Body eq '4'}
	{assign var="contador" value="1"}
	{section name="MalaCalidad" loop=$a_MalaCalidad}
		<tr>
			<td style="width:3.3%;">{$contador++}</td>
			<td style="width:8.8%;">{$a_MalaCalidad[MalaCalidad].contrato}</td>
			<td style="width:8.8%;">{$a_MalaCalidad[MalaCalidad].cuenta}</td>
			<td style="width:17.3%;">{$a_MalaCalidad[MalaCalidad].distribuidor}</td>
			<td style="width:8.8%;">{$a_MalaCalidad[MalaCalidad].fecha_activacion}</td>
			<td style="width:8.8%;text-align:right;">{$a_MalaCalidad[MalaCalidad].comision}</td>
			<td style="width:6.3%;">{$a_MalaCalidad[MalaCalidad].contrarecibo}</td>
			<td style="width:6.8%;">{$a_MalaCalidad[MalaCalidad].motivo_rechazo}</td>
		</tr>
	{sectionelse}
		<td>No hay contratos de mala calidad.</td>
	{/section}
{elseif $Body eq '5'}
	{assign var="contador" value="1"}
	{section name="porFacturar" loop=$a_PorFacturar}
	<tr>
		<td style="width:3.3%;">{$contador++}</td>
		<td style="width:8.8%;">{$a_PorFacturar[porFacturar].contrato}</td>
		<td style="width:8.8%;">{$a_PorFacturar[porFacturar].cuenta}</td>
		<td style="width:17.3%;">{$a_PorFacturar[porFacturar].distribuidor}</td>
		<td style="width:8.8%;">{$a_PorFacturar[porFacturar].fecha_activacion}</td>
		<td style="width:8.8%;text-align:right;">{$a_PorFacturar[porFacturar].comision}</td>
	</tr>
	{sectionelse}
		<td>No hay contratos por facturar.</td>
	{/section}
{elseif $Body eq '6'}
	{assign var="contador" value="1"}
	{section name="Facturado" loop=$a_Facturado}
	<tr>
		<td style="width:3.3%;">{$contador++}</td>
		<td style="width:8.8%;">{$a_Facturado[Facturado].contrato}</td>
		<td style="width:8.8%;">{$a_Facturado[Facturado].cuenta}</td>
		<td style="width:17.3%;">{$a_Facturado[Facturado].distribuidor}</td>
		<td style="width:8.8%;">{$a_Facturado[Facturado].fecha_activacion}</td>
		<td style="width:8.8%;">{$a_Facturado[Facturado].total}</td>
		<td style="width:8.8%;">{$a_Facturado[Facturado].iva}</td>
		<td style="width:8.8%;text-align:right;">{$a_Facturado[Facturado].total_contrato}</td>
		<td style="width:8.8%;">{$a_Facturado[Facturado].folio}</td>
	</tr>
	{sectionelse}
		<td>No hay contratos facturados.</td>
	{/section}
{/if}