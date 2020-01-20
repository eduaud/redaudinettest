{assign var="contador" value="1"}
<link href="{$rooturl}css/estilos.css" rel="stylesheet" type="text/css" />
<link href="{$rooturl}css/estilos_especiales.css" rel="stylesheet" type="text/css" />
<!-- {$sql} -->
<div align="center">
<form name="f2" id="f2">
{section name="fila" loop=$filas}

{if $accion eq "3"}
<table>
	<tbody>
		<tr height="18"><td colspan="5" align="left" class="letra_detalle">&nbsp;</td></tr>
		<tr>
		  <td width="320" class="letra_detalle"><div align="right">&nbsp;FECHA DE MOVIMIENTO:</div></td>
		  <td width="401" class="letra_detalle">
		  <input name="fecha_inicial" type="text" id="fecha_inicial" size="10" maxlength="10" onFocus="calendario(this);" value="{$filas[fila].52}"/>&nbsp;
		  <input name="BtnModificar" type="button" class="button_modificar" id="BtnModificar" value="Modificar" onclick="actualizarInformacionDeContratos('{$filas[fila].0}','{$filas[fila].15}','3', document.f2.fecha_inicial.value);" />
		  </tr>
		<tr height="18"><td colspan="5" align="left" class="letra_detalle">&nbsp;</td></tr>
	</tbody>
</table>
{/if}
{if $accion eq "4"}
<table>
	<tbody>
		<tr height="18"><td colspan="5" align="left" class="letra_detalle">&nbsp;</td></tr>
		<tr>
          <td width="320" class="letra_detalle"><div align="right">&nbsp;PRECIO DE SUSCRIPCI&Oacute;N :</div></td>
          <td width="401" class="letra_detalle">
		  <input name="TxtPrecioSuscripcion" type="text" id="TxtPrecioSuscripcion" value="{$filas[fila].47}"/>&nbsp;
		  <input name="BtnModificar" type="button" class="button_modificar" id="BtnModificar" value="Modificar" onclick="actualizarInformacionDeContratos('{$filas[fila].0}','{$filas[fila].15}','4', document.f2.TxtPrecioSuscripcion.value);" />
		  </td>
		</tr>
		<tr height="18"><td colspan="5" align="left" class="letra_detalle">&nbsp;</td></tr>
	</tbody>
</table>
{/if}
{if $accion eq "5"}
<table>
	<tbody>
		<tr height="18"><td colspan="5" align="left" class="letra_detalle">&nbsp;</td></tr>
		<tr>
			<td class="letra_detalle"><div align="right">Plaza :</div></td>
			<td class="letra_detalle">
				<label>
					<select name="CmbPlaza" id="CmbPlaza" onchange="ajaxLLenaCombos('../ajax/llenaDatosCombos.php', 'id='+this.value+'&caso=8&tipoCliente='+document.f2.CmbTipoCliente.value, 'CmbNombreCliente'); ajaxLLenaCombos('../ajax/llenaDatosCombos.php', 'id='+document.f2.CmbNombreCliente.value+'&caso=7', 'CmbNIT')">
						<option value="0">-- Selecciona una opci&oacute;n --</option>
						{html_options values=$arrIdPlazas output=$arrPlazas}
					</select>
				</label>
			</td>
			
			<td class="letra_detalle"><div align="right">Tipo de Cliente :</div></td>
			<td class="letra_detalle">
				<label>
					<select name="CmbTipoCliente" id="CmbTipoCliente" onchange="ajaxLLenaCombos('../ajax/llenaDatosCombos.php', 'id='+this.value+'&caso=6&idPlaza='+document.f2.CmbPlaza.value, 'CmbNombreCliente'); ajaxLLenaCombos('../ajax/llenaDatosCombos.php', 'id='+document.f2.CmbNombreCliente.value+'&caso=7&idPlaza='+document.f2.CmbPlaza.value+'&tipoCliente='+document.f2.CmbTipoCliente.value, 'CmbNIT')">
						<option value="0">-- Selecciona una opci&oacute;n --</option>
						{html_options values=$arrIdTiposCliente output=$arrTiposCliente}
					</select>
				</label>
			</td>
		</tr>
		<tr></tr>
		<tr>
			<td class="letra_detalle"><div align="right">NOMBRE CLIENTE :</div></td>
			<td class="letra_detalle">
				<label>
					<select name="CmbNombreCliente" id="CmbNombreCliente" onchange="ajaxLLenaCombos('../ajax/llenaDatosCombos.php', 'id='+this.value+'&caso=7', 'CmbNIT')">
						<option value="0">-- Selecciona una opci&oacute;n --</option>
					</select>
				</label>
			</td>
			
			<td class="letra_detalle"><div align="right">NIT :</div></td>
			<td class="letra_detalle">
				<label>
					<select name="CmbNIT" id="CmbNIT">
						<option value="0">-- Selecciona una opci&oacute;n --</option>
					</select>
				</label>
			</td>
		</tr>
		<tr>
			<td class="letra_detalle" colspan="4" align="right">
				<br><input name="BtnModificar" type="button" class="button_modificar" id="BtnModificar" value="Modificar" onclick="actualizarInformacionDeContratos('{$filas[fila].0}','{$filas[fila].15}','5', document.f2.CmbNIT.value);" />
			</td>
		</tr>
		{*
		<tr>
          <td width="142" class="letra_detalle"><div align="right">&nbsp;NIT :</div></td>
          <td width="579" class="letra_detalle"><label>
            <select name="CmbNIT" id="CmbNIT">
            <option value="-1">ELEGIR NIT</option>
			  {section name="filaNIT" loop=$filasNIT}
			  <option value="{$filasNIT[filaNIT].0}">{$filasNIT[filaNIT].1}</option>
			  {/section}
            </select>
          </label>
            &nbsp;
		  <input name="BtnModificar" type="button" class="button_modificar" id="BtnModificar" value="Modificar" onclick="actualizarInformacionDeContratos('{$filas[fila].0}','{$filas[fila].15}','5', document.f2.CmbNIT.value);" />
		  </td>
		</tr>
		*}
		<tr height="18"><td colspan="5" align="left" class="letra_detalle">&nbsp;</td></tr>
	</tbody>
</table>
{/if}
{if $accion eq "6"}
<table>
	<tbody>
		<tr height="18"><td colspan="5" align="left" class="letra_detalle">&nbsp;</td></tr>
		
		
		 <tr>
          <td width="142" class="letra_detalle"><div align="right">&nbsp;PROMOCI&Oacute;N :</div></td>
          <td width="579" class="letra_detalle"><label>
            <select name="CmbNIT" id="CmbNIT">
            <option value="-1">ELEGIR PROMOCION</option>
			  {section name="filaPromos" loop=$filasPromos}
			  <option value="{$filasPromos[filaPromos].0}">{$filasPromos[filaPromos].1}</option>
			  {/section}
            </select>
          </label>
            &nbsp;
		  <input name="BtnModificar" type="button" class="button_modificar" id="BtnModificar" value="Modificar" onclick="actualizarInformacionDeContratos('{$filas[fila].0}','{$filas[fila].15}','6', document.f2.CmbNIT.value);" />
		  </td>
		</tr>
		
		<tr height="18"><td colspan="5" align="left" class="letra_detalle">&nbsp;</td></tr>
	</tbody>
</table>
{/if}
{/section}
</form>
</div>
