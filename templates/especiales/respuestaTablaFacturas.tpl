{assign var="contador" value="1"}
		{section name="filasFacturas" loop=$filas}
				<tr>
						<td style="width:20px; text-align:center">{$contador}</td>
						<td style="width:100px; text-align:center">{$filas[filasFacturas].1}</td>
						<td style="width:150px; text-align:center">{$filas[filasFacturas].3}</td>
						<td style="width:150px; text-align:center">{$filas[filasFacturas].2}</td>
						<td style="width:144px; text-align:center"><a href="#" id="factura{$filas[filasFacturas].0}" onclick="seleccionaFactura({$filas[filasFacturas].0}, event)"><img src="../../imagenes/seleccionar.png" style="width:20px; height:20px;"/></a></td>
						<td style="display:none"><input id="idFactura{$contador}" type="hidden" value="{$filas[filasFacturas].0}"/></td>
						<td style="display:none">{$contador++}</td>
					</tr>
		{sectionelse}
				<tr>
						<td>No se encuentran facturas disponibles para este cliente</td>
				</tr>
		{/section}