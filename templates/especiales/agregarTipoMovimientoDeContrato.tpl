{assign var="contador" value="1"}
<link href="{$rooturl}css/estilos.css" rel="stylesheet" type="text/css" />
<link href="{$rooturl}css/estilos_especiales.css" rel="stylesheet" type="text/css" />
<!-- {$sql} -->
<div align="center">
<table>
	<tbody>
		<tr height="18">
			<td width="89" align="right" class="letra_detalle"><div align="left">&nbsp;TIPO</div></td>
			<td align="right" class="letra_detalle"><div align="left">&nbsp;
				<select name="CmbTipoAccion" id="CmbTipoAccion" class="combos">
					<option value="-1">ELEGIR ACCI&Oacute;N</option>
                  	<option value="1">PENALIZACI&Oacute;N</option>
                  	<option value="2">BONO</option>
                </select>
			  </div></td>
		</tr>
		<tr height="18">
		  <td align="right" class="letra_detalle"><div align="left">&nbsp;PRODUCTO</div></td>
		  <td align="right" class="letra_detalle"><div align="left">&nbsp;
                <select name="CmbProductos" id="CmbProductos" class="combos">
                  <option value="-1">ELEGIR PRODUCTO</option>
                  
					{section name="filasProducto" loop=$filasProductos}
                    
                  <option value="{$filasProductos[filasProducto].0}">{$filasProductos[filasProducto].5}</option>
                  
					{/section}
                  
                </select>
          </div></td>
      </tr>
		<tr height="18">
		  <td align="right" class="letra_detalle"><div align="left">&nbsp;MONTO</div></td>
		  <td align="right" class="letra_detalle"><div align="left">&nbsp;
                <input name="TxtMonto" type="text" class="cajas" id="TxtMonto" size="10" maxlength="16"/>
          </div></td>
      </tr>
		<tr height="18">
		  <td align="right" class="letra_detalle"></td>
		  <td align="right" class="letra_detalle"><div align="left">&nbsp;
		    <input type="submit" name="Submit" value="Agregar" onclick="guardarDetalleContrato('{$id_control_contrato}');" />
		    <span class="letra_encabezado">
	        <input type="hidden" id="TxtIdControlContrato" name="TxtIdControlContrato" value="{$id_control_contrato}" />
          </span></div></td>
	  </tr>
	</tbody>
</table>
</div>