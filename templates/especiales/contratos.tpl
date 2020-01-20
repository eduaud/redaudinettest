{include file="_header.tpl" pagetitle="$contentheader"}
<link href="{$rooturl}css/estilos_especiales.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="{$rooturl}js/funciones_especiales.js"></script>
<h1 class="encabezado">Contratos</h1>
<div id="busquedas">
<form name="f1" id="f1">
	<table width="1024" border="0">
      <tr>
        <td width="55"><label for="select-proveedor">Contrato</label></td>
        <td width="150"><input type="text" id="TxtContrato" name="TxtContrato" class="busca-irds" maxlength="16" onKeyDown="saltaABoton(event,'f1');"/></td>
        <td width="84"><div align="right"><label for="select-proveedor">Cuenta</label></div></td>
        <td width="166"><input type="text" id="TxtCuenta" name="TxtCuenta" class="busca-irds" maxlength="16" onKeyDown="saltaABoton(event,'f1');"/></td>
        <td width="62"><div align="right"><label for="select-proveedor">IRD</label></div></td>
        <td width="144"><div align="right"><input type="text" id="TxtIRD" name="TxtIRD" class="busca-irds" maxlength="16" onKeyDown="saltaABoton(event,'f1');"/></div></td>
        <td width="333">&nbsp;<input name="button" type="button" class="button_search" onclick="buscaContratos(f1.TxtContrato.value, f1.TxtCuenta.value, f1.TxtIRD.value);" value="Buscar &raquo;"/></td>
      </tr>
      <tr><td colspan="7">&nbsp;</td></tr>
    </table>
</form>
</div>
<div style="clear:both"></div>
<div class="contratos" id="contratos">
	<div id="lista_contratos_encabezados" class="lista_contratos_encabezados">
	<!-- AQUÍ VA EL ENCABEZADO DE LA LISTA DE CONTRATOS -->
	<table>
		<thead>
			<tr height="20"><th width="340" class="letra_encabezado" align="center" colspan="5">CONTRATOS</th></tr>
			<tr height="20">
				<th width="20" class="letra_encabezado" align="right">NO.&nbsp;</th>
				<th width="40" class="letra_encabezado"align="left">&nbsp;CONTRATO</th>
			    <th width="100" class="letra_encabezado"align="left">CUENTA</th>
			    <th width="120" class="letra_encabezado"align="left">CLIENTE</th>
			    <th width="120" class="letra_encabezado"align="left">SUCURSAL</th>
			</tr>
		</thead>
	</table>
	</div>
	<div id="espacio_vertical" class="espacio_vertical"></div>
	<div id="lista_historial_contratos_encabezados" class="lista_historial_contratos_encabezados">
	<!-- AQUI VAN LOS ENCABEZADOS DEL HISTORIAL DE LOS CONTRATOS -->
	<table>
		<thead>
			<tr height="20">
				<th class="letra_encabezado" colspan="6"><div align="left" id="display_historial_contrato">&nbsp;HISTORIAL</div></th>
			</tr>
			<tr height="20">
				<th width="30"  class="letra_encabezado" align="right">NO.&nbsp;</th>
				<th width="140" class="letra_encabezado"align="left">&nbsp;MOVIMIENTO</th>
				<th width="60" class="letra_encabezado"align="left">&nbsp;FEC. MOV.</th>
				<th width="180" class="letra_encabezado"align="left">&nbsp;T&Eacute;CNICO</th>
				<th width="190" class="letra_encabezado"align="left">&nbsp;USUARIO ALTA</th>
				<th width="50" class="letra_encabezado"align="left">ACCI&Oacute;N</th>
				
			</tr>
		</thead>
	</table>
	</div>
	<div id="espacio_horizontal_linea" class="espacio_horizontal"></div>
	<div id="lista_contratos" class="lista_contratos"><!-- AQUÍ VA LA LISTA DE CONTRATOS --></div>
	<div id="espacio_vertical" class="espacio_vertical">&nbsp;</div>
	<div id="lista_historial_contratos" class="lista_historial_contratos"><!-- AQUÍ VA EL HISTORIAL DEL CONTRATO --></div>
	<div id="lista_contratos_pie" class="lista_contratos_pie">
	<!-- AQUÍ VA EL ENCABEZADO DE LA LISTA DE CONTRATOS -->
	<table>
		<thead>
			<tr height="20">
				<th width="150" class="letra_encabezado" align="left"></th>
				<th width="50" class="letra_encabezado"align="left"></th>
			    <th width="50" class="letra_encabezado"align="left"></th>
			    <th width="50" class="letra_encabezado"align="left"></th>
			    <th width="50" class="letra_encabezado"align="left"></th>
			</tr>
		</thead>
	</table>
	</div>
	<div id="espacio_vertical" class="espacio_vertical">&nbsp;</div>
	<div id="lista_historial_contratos_pie" class="lista_historial_contratos_pie">
	<!-- AQUI VAN LOS ENCABEZADOS DEL HISTORIAL DE LOS CONTRATOS -->
	<table>
		<thead>
			<tr height="20">
				<th width="200"  class="letra_encabezado" align="left">&nbsp;</th>
				<th width="50" class="letra_encabezado"align="left"></th>
				<th width="50" class="letra_encabezado"align="left"></th>
				<th width="50" class="letra_encabezado"align="left"></th>
				<th width="50" class="letra_encabezado"align="left"></th>
			</tr>
		</thead>
	</table>
	</div>
	
	<div id="espacio_horizontal" class="espacio_horizontal">&nbsp;</div>
	<div id="lista_irds_asignados_encabezados" class="lista_irds_asignados_encabezados">
	<!-- AQUÍ VA EL ENCABEZADO DE LA LISTA DE CONTRATOS -->
	<table>
		<thead>
			<tr height="20">
			  <th width="380" class="letra_encabezado" align="center" colspan="2"><div align="left" id="display_irds_contrato">&nbsp;IRDS ASIGNADOS</div></th>
			</tr>
			<tr height="20">
				<th width="50"  class="letra_encabezado" align="right">NO.&nbsp;</th>
				<th width="330" class="letra_encabezado" align="left">&nbsp;IRD</th>
			</tr>
		</thead>
	</table>
	</div>
	<div id="espacio_vertical" class="espacio_vertical"></div>
	<div id="editor_encabezados" class="editor_encabezados"><!-- AQUÍ VA EL MENSAJE PARA LA EDICION DE LA EDICION --></div>
	<div id="espacio_vertical" class="espacio_vertical">&nbsp;</div>
	<div id="espacio_horizontal_linea" class="espacio_horizontal_linea">&nbsp;</div>
	<div id="lista_irds_asignados" class="lista_irds_asignados"><!-- AQUÍ VA LA LISTA DE IRD'S ASIGNADOS --></div>
	<div id="espacio_vertical" class="espacio_vertical">&nbsp;</div>
	<div id="editor" class="editor"><!-- AQUÍ VAN LOS COMENTARIOS SE SE AGREGEN --></div>
</div>
<br />
{include file="_footer.tpl"}