{include file="_header.tpl" pagetitle="$contentheader"}
<link href="{$rooturl}css/estilos_especiales.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="{$rooturl}js/funciones_especiales.js"></script>
<!--
<div style="z-index:5000; display:none; position:absolute; left:50px; top:0px; width:500px; height:400px;" id="waitingplease">
	<img src="../../imagenes/general/wait.gif" border="0" style="z-index:2000; position:absolute" id="imgW1"/>
	<img src="../../imagenes/general/back_wait.gif" border="0" style="z-index:1000; position:absolute" id="imgW2"/>
</div>
-->
<h1>Generar Entradas a Partir de Ordenes de Compra</h1>
<div id="busquedas">
	<p>Buscar por:</p><br>
	<table width="1024" border="0">
      <tr>
        <td width="64"><label for="select-proveedor">Proveedor</label>		</td>
        <td width="249"><select name="select-proveedor" id="select-proveedor" onchange="">
		<option value="-1">Seleccione Proveedor</option>
			{section name="proveedores" loop=$proveedor}
				<option value="{$proveedor[proveedores].0}">{$proveedor[proveedores].1}</option>
			{/section}
		</select></td>
        <td width="77">&nbsp;</td>
        <td width="147"><label for="orden">Orden de Compra</label></td>
        <td colspan="2"><input type="text" id="orden" name="orden" class="busca-producto"/></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right">
          <input type="button" class="button_search" value="Buscar &raquo;" onclick="buscaProveedor()"/>
        </div></td>
        <td>&nbsp;</td>
        <td><label for="documento">Documento Proveedor</label></td>
        <td colspan="2"><input type="text" id="documento" name="documento" class="busca-producto"/></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td width="325"><div align="right">
          <input type="button" class="button_search" value="Buscar &raquo;" onclick="buscaOrden();"/>
        </div></td>
        <td width="136">&nbsp;</td>
      </tr>
    </table><br />
</div>
<div style="clear:both"></div>
<div class="ordenes"></div>
{include file="_footer.tpl"}