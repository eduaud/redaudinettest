{assign var="contador" value="1"}
<link href="{$rooturl}css/estilos.css" rel="stylesheet" type="text/css" />
<link href="{$rooturl}css/estilos_especiales.css" rel="stylesheet" type="text/css" />
<!-- {$sql} -->
<div align="center">
<form name="f2" id="f2">
{section name="fila" loop=$filas}
<table width="512">
	<tbody>
		<tr height="18"><td colspan="2" align="left" class="letra_detalle">&nbsp;</td></tr>
		<tr height="18">
		  <td width="104" class="letra_detalle"><div align="right">CONTRATO:&nbsp;</div></td>
		  <td width="547" class="letra_detalle"><div align="left">&nbsp;<input name="TxtContratoModificar" type="text" class="cajas" id="TxtContratoModificar" size="30" maxlength="15" value="{$filas[fila].1}" onKeyDown="saltaABoton(event,'f2');"/>
		  &nbsp;<input type="button" name="button" class="button_modificar" value="Sustituir" onclick="sustituirNumeroContrato();" />
		  </div></td>
        </tr>
		<tr height="18">
		  <td align="right" class="letra_detalle"></td>
		  <td class="letra_detalle"><input name="TxtContratoModificarAux" type="hidden" class="cajas" id="TxtContratoModificarAux" maxlength="15" value="{$filas[fila].1}"/>
            <input name="TxtIdControlContrato2" type="hidden" class="cajas" id="TxtIdControlContrato2" maxlength="20" value="{$filas[fila].0}"/>
            <input name="TxtCuenta" type="hidden" class="cajas" id="TxtCuenta" maxlength="20" value="{$filas[fila].5}"/>
            <input name="TxtFechaActivacion" type="hidden" class="cajas" id="TxtFechaActivacion" maxlength="20" value="{$filas[fila].6}"/>
            <input type="hidden" id="TxtIdControlContrato" name="TxtIdControlContrato" value="{$idDetalle}" />          </td>
	  </tr>
	</tbody>
</table>
{/section}
</form>
</div>
