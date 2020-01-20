{include file="_header.tpl" pagetitle="$contentheader"}    
<script language="javascript" src="{$rooturl}js/franquicias.js"></script>    
<link href="{$rooturl}css/tabswizard.css" rel="stylesheet" type="text/css">

<link href="{$rooturl}css/jquery-ui.css" rel="stylesheet" type="text/css">

 <br>
<h1> Prepedido</h1> </div>


<form name="forma1" method="post" action="preSurtido.php">
<table border="0" width="90%" >

<tr class='nom_campo'>
    <td >Producto o SKU <input type="text" name="producto" id="producto" size="30" /></td>
	
    <td >
		<table>
			<tr>
				<td>Lista de Precios</td>
				</td>
				<td>
					<select name="id_orden_servicio" class="campos_req" id="id_orden_servicio">
					  <option value="0" selected="selected"> - Seleccione Lista de Precio - </option>
										{html_options values=$arrysIdOS output=$arrysNombreOS selected=$idOS }
					
					</select>
				</td>
			</tr>
			<tr>
				<td >Formas de Pago</td>
				<td>
					<select name="id_orden_servicio" class="campos_req" id="id_orden_servicio">
					  <option value="0" selected="selected"> - Seleccione Forma de Pago - </option>
										{html_options values=$arrysIdOS output=$arrysNombreOS selected=$idOS }
					</select>
				</td>
			</tr>
		</table>
	</td>
</tr>

<tr class='nom_campo'>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td><input name="btnBuscar" type="button" class="boton" value="Buscar      &raquo;" onClick="buscar();" /></td>
</tr>
</table> 
</form>

<!-- Listado de información de presurtido -->
<b>Productos Encontrados</b>

<table border="1">
	<tr>
		<th>Producto o SKU</th>
		<th>Existencia</th>
		<th>Pendiente por Entregar</th>
		<th>Disponible</th>
		<th>Foto</th>
		<th>Cantidad solicitada</th>
		<th>Agregar</th>
	</tr>
	<td align="center">COCO-Blanca</td>
	<td align="center">10</td>
	<td align="center">2</td>
	<td align="center">8</td>
	<td align="center"><img src="http://www.fabricasdemuebles.com.mx/uploads/9/2/9/1/9291947/6039172_orig.jpg" border="0" WIDTH="50" HEIGHT="50"/></td>
	<td align="center"><input type="text" name="cantidad_solicitada" id="cantidad_solicitada" size="4" /></td>
	<td align="center"><img src="../../imagenes/general/agregar.png" /></td>
</table>

<p />
<br />
<b>Productos Agregados</b>
<table border="1">
	<tr>
		<th>Producto o SKU</th>
		<th>Existencia</th>
		<th>Pendiente por Entregar</th>
		<th>Disponible</th>
		<th>Foto</th>
		<th>Cantidad solicitada</th>
		<th>Agregar</th>
	</tr>
	<tr>
		<td align="center">COCO-Blanca</td>
		<td align="center">10</td>
		<td align="center">2</td>
		<td align="center">8</td>
		<td align="center"><img src="http://www.fabricasdemuebles.com.mx/uploads/9/2/9/1/9291947/6039172_orig.jpg" border="0" WIDTH="50" HEIGHT="50"/></td>
		<td align="center"><input type="text" name="cantidad_solicitada" id="cantidad_solicitada" size="4" /></td>
		<td align="center"><img src="../../imagenes/general/eliminar_2.png" /></td>
	</tr>
	<tr>
		<td align="center">Citadel-camel</td>
		<td align="center">10</td>
		<td align="center">2</td>
		<td align="center">8</td>
		<td align="center"><img src="http://www.fabricasdemuebles.com.mx/uploads/9/2/9/1/9291947/336957_orig.jpg" border="0" WIDTH="50" HEIGHT="50"/></td>
		<td align="center"><input type="text" name="cantidad_solicitada" id="cantidad_solicitada" size="4" /></td>
		<td align="center"><img src="../../imagenes/general/eliminar_2.png" /></td>
	</tr>
	<tr>
		<td align="center">Sala 15400</td>
		<td align="center">10</td>
		<td align="center">2</td>
		<td align="center">8</td>
		<td align="center"><img src="http://www.fabricasdemuebles.com.mx/uploads/9/2/9/1/9291947/4652114_orig.jpg" border="0" WIDTH="50" HEIGHT="50"/></td>
		<td align="center"><input type="text" name="cantidad_solicitada" id="cantidad_solicitada" size="4" /></td>
		<td align="center"><img src="../../imagenes/general/eliminar_2.png" /></td>
	</tr>
</table>
<p />
<br />
<table>
	<tr>
		<td>Total</td>
		<td><input type="text" name="total" id="total" size="10" /></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>Cliente</td>
		<td><input type="text" name="total" id="total" size="30" /></td>
		<td colspan="5"><input type="button" value="Guardar" /></td>
	</tr>
</table>

