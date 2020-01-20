{include file="_header.tpl" pagetitle="$contentheader"}
<link href="{$rooturl}css/estilos_especiales.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="{$rooturl}js/funciones_especiales.js"></script>
<h1 class="encabezado">Generaci&oacute;n de ODC a partir de Requisiciones Autorizadas</h1>
<div id="busquedas">
	<p>Buscar por:</p><br>
	<div id="requisiciones" style="float:left; padding-left:25px;">
		<table border="0">
			<tr>
				<td><label for="select-plaza">Plaza</label></td>
				<td>
					<div align="right">
					  <select name="select-sucursal" id="select-sucursal" onchange="">
					    <option value="-1">Seleccione la Plaza</option>
						{section name="sucursales" loop=$sucursal}
					    <option value="{$sucursal[sucursales].0}">{$sucursal[sucursales].1}</option>
						{/section}
				      </select>
			        </div></td>
				<td>&nbsp;</td>
				<td><label>Requisici&oacute;n</label></td>
				<td><input type="text" id="idrequisicion" name="idrequisicion"/></td>
			</tr>
			<tr>
				<td><label for="select-solicitante">Usuario Solicitante</label></td>
				<td>
					<div align="right">
					  <select name="select-usuario-solicitante" id="select-usuario-solicitante" onchange="">
					    <option value="-1">Seleccione un Usuario</option>
						{section name="usuarios" loop=$usuario}
					    <option value="{$usuario[usuarios].0}">{$usuario[usuarios].1}</option>
						{/section}
				      </select>
			        </div></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td><p style="display:block; float:right;"><input type="button" class="button_search" value="Buscar &raquo;" onclick="buscaOrdenesDeCompraAutorizadas(2)"/></p></td>
			</tr>
			<tr>
				<td><label for="fechadel">Fecha del</label></td>
				<td>
					<input type="text" id="fechadel" name="fechadel" class="fechas" onFocus="calendario(this);"/>
					<label for="fechaal">&nbsp;Al</label>
					<input type="text" id="fechaal" name="fechaal" class="fechas" onFocus="calendario(this);"/>
				</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><label for="select-plaza">Tipo de Productos</label></td>
				<td>
					<div align="right">
					  <select name="select-producto" id="select-producto" onchange="">
					    <option value="-1">Seleccione el Tipo de Producto</option>
						{section name="sucursales" loop=$producto}
					    <option value="{$sucursal[sucursales].0}">{$producto[sucursales].1}</option>
						{/section}
				      </select>
			        </div></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><p style="display:block; float:right;"><input type="button" class="button_search" value="Buscar &raquo;" onclick="buscaOrdenesDeCompraAutorizadas(1)"/></p></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>

		</table>
	</div>
</div>
<div style="clear:both;"></div>

<!-- Tabla de Requisiciones -->
<div style="margin:20px 0; width:auto">
	<table class="encabezados_tabla" border="0">
	<caption>Ordenes de Compra</caption>
		<thead>
			<tr>
				<th style="width:10px;">N.</th>
				<th style="width:10px; text-align:center;"><input type="checkbox" id="sel-todos" onClick="seleccionarCheck(this, 'detalle');"/></th>
				<th style="width:50px; text-align:center">No. Req.</th>
				<th style="width:70px; text-align:center">Tpo. Prod.</th>
				<th style="width:100px; text-align:center">Cve. Producto</th>
				<th style="width:100px; text-align:center">Producto</th>
				<th style="width:100px; text-align:center">Cantidad</th>
				<th style="width:100px; text-align:center">Plaza</th>
				<th style="width:100px; text-align:center">Distribuidor</th>
				<th style="width:100px; text-align:center">Usuario Solicit&oacute;</th>
				<th style="width:100px; text-align:center">F. Creaci&oacute;n</th>
				<th style="width:100px; text-align:center">F. Requerida</th>
				<th style="width:90px; text-align:center">Prov. de ODC</th>
				<th style="width:90px; text-align:center">Alm. de Plaza</th>
				<th style="width:90px; text-align:center">Acci&oacute;n</th>
			</tr>
		</thead>
	</table>
	<div id="scroll-tabla-detalle-odc">
	<table border="0" class="detalle" name="detalle" id="detalle">  <!-- id="detalle_tabla" -->
		<tbody></tbody>
	</table>
	</div>
	<br />
	<table>
		<tr><td>
			<p style="display:block; float:right;"><input type="button" class="boton" value="Generar &raquo;" onclick="generarODCaPartirDeRequisicionAutorizada('detalle')"/></p>
		</td></tr>
	</table>
</div>
<!-- Tabla de Requisiciones -->
{include file="_footer.tpl"}
