<tbody>
		{assign var="contador" value="1"}
		{section name="filasCedis" loop=$filas}
				<tr>
						<td style="width:20px; text-align:center">{$contador}</td>
						<td style="width:80px; text-align:center">{$filas[filasCedis].0}</td>
						<td style="width:100px; text-align:center">{$filas[filasCedis].1}</td>
						<td style="width:150px; text-align:center">{$filas[filasCedis].2}</td>
						<td style="width:150px; text-align:center">{$filas[filasCedis].3}</td>
						<td style="width:150px; text-align:center">{$filas[filasCedis].4}</td>
						<td style="width:150px; text-align:center">{$filas[filasCedis].5}</td>
						<td style="width:300px; text-align:center">
						<div style="width : auto">
								<input type="button" id="aprobar" class="botonesD" value="Aprobar" onClick="apruebaOrden({$contador})"/>
								<input type="button" id="rechazar" class="botonesD" value="Rechazar" onClick="rechazaOrden({$contador})"/>
								<input type="button" id="rechazar" class="botonesD" style="width:40px" value="Ver" onClick="verOrdenCompra({$filas[filasCedis].0})"/><br><br>
								<textarea style="width:150px" id="motivo-rechazo{$contador}" class="rechazo"></textarea>
						</div>		
						</td>
						<td style="display:none"><input id="idSolicitud{$contador}" type="hidden" value="{$filas[filasCedis].0}"/></td>
						<td style="display:none">{$contador++}</td>
				</tr>
		{sectionelse}
				<tr>
						<td>No existen solicitudes con estos datos de busqueda</td>
				</tr>
		{/section}
</tbody>