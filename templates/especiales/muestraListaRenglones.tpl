{assign var="contador" value="1"}
<link href="{$rooturl}css/estilos.css" rel="stylesheet" type="text/css" />
<link href="{$rooturl}css/estilos_especiales.css" rel="stylesheet" type="text/css" />
<br />
<!-- {$sql} -->
<table width="726" border="0" id="detalle_irds">
	<thead>
		<tr bgcolor="#F2F2F2" height="20">
		  <th width="161" align="right"  class="letra_encabezado"><div align="left">&nbsp;TIPO</div></th>
			<th align="left" class="letra_encabezado"><div align="left">&nbsp;PRODUCTO</div></th>
		    <th align="left" class="letra_encabezado"><div align="left">&nbsp;MONTO</div></th>
		    <th align="left" class="letra_encabezado"><input type="hidden" id="TxtIdControlContrato" name="TxtIdControlContrato" value="{$idDetalle}" /></th>
		</tr>
	</thead>
	<tbody>
		<tr height="18">
			<td align="right" class="letra_detalle">
			  <div align="left">&nbsp;
				<select name="CmbTipoAccion" id="CmbTipoAccion">
					<option value="-1">ELEGIR ACCI&Oacute;N</option>
                  	<option value="1">PENALIZACI&Oacute;N</option>
                  	<option value="2">BONO</option>
                </select>
			  </div>			</td>
			<td width="256" align="left" class="letra_detalle"><div align="left">&nbsp;
                  <select name="CmbProductos" id="CmbProductos">
                    <option value="-1">ELEGIR ACCI&Oacute;N</option>
					{section name="filasProducto" loop=$filasProductos}
                    <option value="{$filasProductos[filasProducto].0}">{$filasProductos[filasProducto].1}</option>
					{/section}
                  </select>
          </div></td>
		    <td width="120" align="left" class="letra_detalle"><div align="left">&nbsp;
		          <input name="TxtMonto" type="text" class="cajas" id="TxtMonto" size="10" maxlength="16"/>
          </div></td>
	      <td width="171" align="left" class="letra_detalle"><label><input type="submit" name="Submit" value="Agregar" onclick="guardarDetalleContrato('{$idDetalle}');" /></label></td>
		</tr>
	</tbody>
</table>
