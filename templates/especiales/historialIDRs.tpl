{include file="_header.tpl" pagetitle="$contentheader"}
<link href="{$rooturl}css/estilos_especiales.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="{$rooturl}js/funciones_especiales.js"></script>
<h1 class="encabezado">Historial IRD's</h1>
<div id="busquedas">
	<p>Buscar por:</p><br>
	<table width="1024" border="0">
      <tr>
        <td width="64"><label for="select-proveedor">IRD's</label>		</td>
        <td width="177"><input type="text" id="TxtIRD" name="TxtIRD" class="busca-irds" maxlength="16"/></td>
        <td width="769"><div align="left">&nbsp;<input type="button" class="button_search" value="Buscar &raquo;" onclick="buscaIRDs();"/></div></td>
      </tr>
      <tr>
        <td colspan="3">&nbsp;</td>
      </tr>
    </table>
</div>
<div style="clear:both"></div>
<div class="irds" id="irds">
	<div id="lista_irds_encabezados" class="lista_irds_encabezados">
	<!-- AQUI VAN LOS ENCABEZADOS DE LA LISTA DE IRDS -->
	<table>
		<thead>
			<tr height="20">
				<th width="50"  class="letra_encabezado" align="right">NO.&nbsp;</th>
				<th width="330" class="letra_encabezado"align="left">&nbsp;IRDS</th>
			</tr>
		</thead>
	</table>
	</div>
	<div id="espacio_vertical" class="espacio_vertical"></div>
	<div id="lista_historial_irds_encabezados" class="lista_historial_irds_encabezados">
	<!-- AQUI VAN LOS ENCABEZADOS DEL HISTORIAL DE IRDS -->
	<table>
		<thead>
			<tr height="20">
				<th class="letra_encabezado" colspan="3"><div align="left" id="display_ird_historial">&nbsp;HISTORIAL</div></th>
			</tr>
			<tr height="20">
				<th width="50"  class="letra_encabezado" align="right">NO.&nbsp;</th>
				<th width="200" class="letra_encabezado"align="left">&nbsp;FEC. ALTA</th>
				<th width="550" class="letra_encabezado"align="left">&nbsp;ALMACEN</th>
			</tr>
		</thead>
	</table>
	</div>
	<div id="espacio_horizontal_linea" class="espacio_horizontal"></div>
	<div id="lista_irds" class="lista_irds"><!-- AQUI VA LA LISTA DE IRDS --></div>
	<div id="espacio_vertical" class="espacio_vertical"></div>
	<div id="lista_historial_irds" class="lista_historial_irds"><!-- AQUI VA EL HISTORIAL DEL IRDS --></div>
	<div id="espacio_horizontal" class="espacio_horizontal"></div>
	<div id="lista_estatus_irds_encabezados" class="lista_estatus_irds_encabezados">
	<!-- AQUI VAN LOS ENCABEZADOS DEL HISTORIAL DE LOS ESTATUS DE LOS IRDS -->
	<table>
		<thead>
			<tr height="20">
				<th class="letra_encabezado" colspan="7"><div align="left" id="display_ird_estatus">&nbsp;ESTATUS</div></th>
			</tr>
			<tr height="20">
				<th width="50" class="letra_encabezado" align="right">NO.&nbsp;</th>
				<th width="200" class="letra_encabezado"align="left">&nbsp;IRDS</th>
				<th width="200" class="letra_encabezado"align="left">&nbsp;FECHA ALTA</th>
				<th width="200" class="letra_encabezado"align="left">&nbsp;FECHA ASIGNACI&Oacute;N</th>
				<th width="200" class="letra_encabezado"align="left">&nbsp;FECHA ACTUAL</th>
				<th width="50" class="letra_encabezado"align="left">&nbsp;ESTATUS</th>
				<th width="250" class="letra_encabezado"align="left">&nbsp;UBICACI&Oacute;N</th>
			</tr>
		</thead>
	</table>
	</div>
	<div id="lista_estatus_irds" class="lista_estatus_irds"><!-- AQUI VAN LOS ESTATUS DE LOS IRDS --></div>
	<div id="espacio_horizontal" class="espacio_horizontal"></div>
</div>
<br />
{include file="_footer.tpl"}