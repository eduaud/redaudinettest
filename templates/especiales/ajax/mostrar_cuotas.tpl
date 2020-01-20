{if !empty($a_cuotas)}
	<input type="button" value="Exportar Datos" align="right" class="button_export" style="margin-left:900px;margin-top:10px" onclick="exportarcuotas()"/>
{/if}
<table style="margin-top:10px">
	<thead class="encabezados_cuota">
		<th>Clave Plaza</th>
		<th>Clave Distribuidor</th>
		<th>Distribuidor</th>
		<th>Tradicional SD</th>
		<th>Tradicional HD</th>
		<th>VETV 1</th>
		<th>VETV 2</th>
		<th>Total</th>
		<th>Acci&oacute;n</th>
</thead>
</table>
<div id="scroll-tabla-cuotas">
	<table>
		<tbody class="detalle_cuotas">
			{section name='Cuota' loop=$a_cuotas}
				<tr id="fila_{$a_cuotas[Cuota].0}">

					<td id="col_0">{$a_cuotas[Cuota].1}</td>
					<td id="col_1">{$a_cuotas[Cuota].2}</td>
					<td id="col_2">{$a_cuotas[Cuota].3}</td>
					<td id="col_3">{$a_cuotas[Cuota].4}</td>
					<td id="col_4">{$a_cuotas[Cuota].5}</td>
					<td id="col_5">{$a_cuotas[Cuota].6}</td>
					<td id="col_6">{$a_cuotas[Cuota].7}</td>
					<td id="col_7">{$a_cuotas[Cuota].8}</td>
					<td id="col_8"><img src="{$rooturl}imagenes/general/modificar.png" onclick="EditarCuota('{$a_cuotas[Cuota].0}')" onmouseover="this.style.cursor='hand';this.style.cursor='pointer';" title="Modificar Registro"/></td>
				</tr>
			{sectionelse}
				<tr><td style="width:500px !important;">No se encontraron registros con estos términos de búsqueda</td></tr>
			{/section}
		</tbody>
	</table>
</div>