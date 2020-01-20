{include file="_header.tpl" pagetitle="$contentheader"}
<link href="{$rooturl}css/estilos_especiales.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="{$rooturl}js/funciones_especiales.js"></script>
<h1 class="encabezado">Asignaci&oacute;n de Pipeline</h1>
<div id="busquedas">
	<p>Buscar por:</p><br>
	<table width="1024" border="0">
      <tr>
        <td width="55"><label for="select-proveedor">Plaza</label></td>
        <td width="150">
			<select name="CmbPlazas" id="CmbPlazas">
				<option value="-1">Seleccione la Plaza</option>
				{section name="sucursales" loop=$sucursal}
			    <option value="{$sucursal[sucursales].0}">{$sucursal[sucursales].2}</option>
				{/section}
        	</select>
		</td>
        <td width="14">&nbsp;</td>
        <td width="787">&nbsp;<input name="button" type="button" class="button_search" onclick="buscaIRDsParaAsignacion();" value="Buscar &raquo;"/></td>
      </tr>
      <tr>
        <td colspan="4">&nbsp;</td>
      </tr>
    </table>
</div>
<div style="clear:both"></div>
<div class="irds_para_asignar" id="irds_para_asignar">

	<div id="lista_irds_fisicos_sin_pipeline_encabezados" class="lista_irds_fisicos_sin_pipeline_encabezados">
	<!-- AQUÍ VAN ENCABEZADOS DE LA LISTA DE IRDS SIN PIPELINE -->
	<table>
		<thead>
			<tr height="20"><th class="letra_encabezado" align="center" colspan="4">IRD'S FISICOS SIN PIPELINE</th></tr>
			<tr height="20">
				<th width="50"  class="letra_encabezado" align="right">NO.&nbsp;</th>
				<th width="100" class="letra_encabezado"align="left">&nbsp;IRDS</th>
				<th width="100" class="letra_encabezado"align="left">&nbsp;PRODUCTO</th>
				<th width="150" class="letra_encabezado"align="left">&nbsp;FECHA ALTA</th>
			</tr>
		</thead>
	</table>
	</div>
	<div id="espacio_vertical" class="espacio_vertical"></div>
	<div id="lista_irds_en_pipeline_encabezados" class="lista_irds_en_pipeline_encabezados">
	<!-- AQUI VAN LOS ENCABEZADOS DEL HISTORIAL DE IRDS -->
	<table>
		<thead>
			<tr height="20"><th class="letra_encabezado" align="center" colspan="4">IRD'S EN PIPELINE SIN ASIGNAR</th></tr>
			<tr height="20">
				<th width="50"  class="letra_encabezado" align="right">NO.&nbsp;</th>
				<th width="150" class="letra_encabezado"align="left">&nbsp;IRDS</th>
				<th width="100" class="letra_encabezado"align="left">&nbsp;F. PIPELINE</th>
				<th width="100" class="letra_encabezado"align="left">&nbsp;H. PIPELINE</th>
				<!-- <th width="350" class="letra_encabezado"align="left">&nbsp;ESTATUS</th> -->
			</tr>
		</thead>
	</table>
	</div>
	
	<!-- AQUI VAN LOS ENCABEZADOS DEL PIPELINE SIN EXISTENCIA FISICA -->
	<div id="espacio_vertical" class="espacio_vertical"></div>
	<div id="lista_irds_en_pipeline_sin_existencia" class="lista_irds_en_pipeline_encabezados">
		<table>
			<thead>
				<tr height="20"><th class="letra_encabezado" align="center" colspan="4">IRD'S EN PIPELINE SIN EXISTENCIA FISICA</th></tr>
				<tr height="20">
					<th width="50"  class="letra_encabezado" align="right">NO.&nbsp;</th>
					<th width="150" class="letra_encabezado"align="left">&nbsp;IRDS</th>
					<th width="100" class="letra_encabezado"align="left">&nbsp;F. PIPELINE</th>
					<th width="100" class="letra_encabezado"align="left">&nbsp;H. PIPELINE</th>
				</tr>
			</thead>
		</table>
	</div>
	<!-- TERMINA AQUI VAN LOS ENCABEZADOS DEL PIPELINE SIN EXISTENCIA FISICA -->
	
	<div id="espacio_horizontal_linea" class="espacio_horizontal"></div>

	<div id="lista_irds_fisicos_sin_pipeline" class="lista_irds_fisicos_sin_pipeline"><!-- AQUÍ VA LA LISTA DE IRDS SIN PIPELINE --></div>
	<div id="espacio_vertical" class="espacio_vertical">&nbsp;</div>
	<div id="lista_irds_en_pipeline" class="lista_irds_en_pipeline"><!-- AQUÍ VA LA LISTA DE IRDS EN PIPELINE --></div>
	
	<!-- AQUÍ VA LA LISTA DE IRDS EN PIPELINE SIN EXISTENCIA FISICA -->
	<div id="espacio_vertical" class="espacio_vertical">&nbsp;</div>
	<div id="lista_irds_en_pipeline_sin_existencia_fisica" class="lista_irds_en_pipeline"></div>
	<div id="espacio_horizontal" class="espacio_horizontal">&nbsp;</div>
	<!-- TERMINA AQUÍ VA LA LISTA DE IRDS EN PIPELINE SIN EXISTENCIA FISICA -->
	
	<div id="espacio_vertical" class="espacio_vertical">&nbsp;<input name="button" type="button" class="boton" onclick="asignaPipelineAFisicos();" value="Asignar"/></div>

</div>
<br />
{include file="_footer.tpl"}