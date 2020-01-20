{include file="_header.tpl" pagetitle="$contentheader"}
<link href="{$rooturl}css/estilos_especiales.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="{$rooturl}js/funciones_especiales.js"></script>
<h1>Generaci&oacute;n de Costeo</h1>
<div id="busquedas">
	<p>Buscar por:</p><br>
	<table width="1024" border="0">
	  <tr>
		<td width="225" rowspan="5" valign="baseline"><div align="left" class="letras">Almacenes</div>
		  <label>
		  <select name="ListaAlmacenes" id="ListaAlmacenes" size="8" multiple="multiple" style="width:200px">
		  	{section name="almacenes" loop=$almacen}
				<option value="{$almacen[almacenes].0}">{$almacen[almacenes].1}</option>
			{/section}
		  </select>
		  </label>		</td>
		<td width="122" valign="middle"><div align="left" class="letras">Proveedores:&nbsp;</div></td>
		<td colspan="2" valign="bottom"><select name="select-proveedor" id="select-proveedor" onchange="">
        <option value="0">Seleccione Proveedor</option>
				{section name="proveedores" loop=$proveedor}
				  <option value="{$proveedor[proveedores].0}">{$proveedor[proveedores].1}</option>
				{/section}
	    </select></td>
		<td width="274">
		  <div align="right" class="letras">Orden de Compra:&nbsp;
	    <input name="TxtOrdenCompra" type="text" class="cajas" id="TxtOrdenCompra" onkeydown="" size="10" style="size:10;" />
		</div>		</td>
	  </tr>
	  <tr>
		<td valign="middle"><div class="letras" align="left">Fecha ODC:&nbsp;

		</div></td>
		<td colspan="2"><input type="text" id="fechadel1" name="fechadel1" class="fechas" onFocus="calendario(this);"/>
			<label for="fechaal">&nbsp;Al</label>
		<input type="text" id="fechaal1" name="fechaal1" class="fechas" onFocus="calendario(this);"/></td>
		<td><div align="right" class="letras">Documento Cliente:&nbsp;
	    		<input name="TxtDocumentoCliente" type="text" class="cajas" id="TxtDocumentoCliente" size="10"/>
	    </div></td>
	  </tr>
	  <tr>
		<td valign="middle"><div align="left" class="letras">Fecha Entrada Almacen:&nbsp;</div></td>
		<td colspan="2" valign="bottom"><input type="text" id="fechadel2" name="fechadel2" class="fechas" onFocus="calendario(this);"/>
		<label for="fechaal">&nbsp;Al</label>
		<input type="text" id="fechaal2" name="fechaal2" class="fechas" onFocus="calendario(this);"/></td>
		<td><div align="right" class="letras">
          <input name="button" type="button" class="button_search" onclick="buscarEntradasDeODC(2);" value="Buscar &raquo;"/>
        </div></td>
      </tr>
	  <tr>
	    <td valign="bottom">&nbsp;</td>
        <td width="311" valign="bottom"><div align="right" class="letras">
          <input name="button" type="button" class="button_search" onclick="buscarEntradasDeODC(1);" value="Buscar &raquo;"/>
        </div></td>
        <td width="70" valign="bottom">&nbsp;</td>
        <td valign="bottom">&nbsp;</td>
	  </tr>
	  <tr>
	    <td valign="bottom">&nbsp;</td>
	    <td colspan="2" valign="bottom">&nbsp;</td>
        <td valign="bottom">&nbsp;</td>
	  </tr>
  </table>
</div>
<div style="clear:both"></div>
<!-- Tabla de Requisiciones -->
<div style="margin:20px 0; width:1024px">
	<table width="1024" border="0" class="encabezados">
	<caption>Entradas Por Orden de Compra Sin Costeo Relacionado</caption>
	<thead>
		<tr>
			<th style="width:24px;">No</th>
			<th style="width:50px; text-align:center;"><input type="checkbox" id="sel-todos" onClick="seleccionarCheck(this, 'detalle');"/></th>
			<th style="width:250px; text-align:center">Almacen</th>
			<th style="width:250px; text-align:center">Movimiento Entrada </th>
			<th style="width:200px; text-align:center">Orden Compra </th>
			<th style="width:250px; text-align:center">Proveedor</th>
		</tr>
	</thead>
	</table>
	<div id="scroll-tabla">
	<table width="1024" border="0" class="detalle" id="detalle" name="detalle"> 
		<tbody></tbody>
	</table>
	</div>
	<table>
		<tr><td>
			<p style="display:block; float:right;"><input type="button" class="boton" value="Generar Costeo &raquo;" onclick="generarCosteo('detalle');"/></p>
		</td></tr>
	</table>
</div>
<!-- Tabla de Requisiciones -->
{include file="_footer.tpl"}


