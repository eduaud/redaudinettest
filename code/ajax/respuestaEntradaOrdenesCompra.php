<?php	
include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

mysql_query("SET NAMES utf8");

$orden = $_POST['idOrden'];
$proveedor = $_POST['proveedor'];

$where = "";

if(!empty($orden)) $where .= " AND id_orden_compra = " . $orden;
if($proveedor != 0) " AND ad_ordenes_compra_productos.id_proveedor = " . $proveedor;
/*
$sqlOrden = "SELECT";
$sqlOrden .= " id_orden_compra AS id_orden,";
$sqlOrden .= " na_proveedores.razon_social AS proveedor,";
$sqlOrden .= " na_estatus_orden_compra.nombre AS status_orden,";
$sqlOrden .= " na_proveedores.id_proveedor AS id_proveedor,";
$sqlOrden .= " permite_recibo_parciales AS parciales";
$sqlOrden .= " FROM na_ordenes_compra";
$sqlOrden .= " LEFT JOIN na_proveedores";
$sqlOrden .= " ON na_proveedores.id_proveedor = na_ordenes_compra.id_proveedor";
$sqlOrden .= " LEFT JOIN na_estatus_orden_compra";
$sqlOrden .= " ON na_estatus_orden_compra.id_estatus_orden_compra = na_ordenes_compra.id_estatus_orden_compra";
$sqlOrden .= " WHERE (na_ordenes_compra.id_estatus_orden_compra = 2 || na_ordenes_compra.id_estatus_orden_compra = 4)" . $where;
*/

$sqlOrden = " SELECT";
$sqlOrden .= " id_orden_compra AS id_orden,";
$sqlOrden .= " ad_proveedores.razon_social AS proveedor,";
$sqlOrden .= " ad_estatus_orden_compra.nombre AS status_orden,";
$sqlOrden .= " ad_proveedores.id_proveedor AS id_proveedor";
//$sqlOrden .= " ,permite_recibo_parciales AS parciales";
$sqlOrden .= " FROM ad_ordenes_compra_productos";
$sqlOrden .= " LEFT JOIN ad_proveedores";
$sqlOrden .= " ON ad_proveedores.id_proveedor = ad_ordenes_compra_productos.id_proveedor";
$sqlOrden .= " LEFT JOIN ad_estatus_orden_compra";
$sqlOrden .= " ON ad_estatus_orden_compra.id_estatus_odc = ad_ordenes_compra_productos.id_estatus";
$sqlOrden .= " WHERE ad_ordenes_compra_productos.id_estatus = 1";
$sqlOrden .= $where;




$datosOrden = new consultarTabla($sqlOrden);
$registros = $datosOrden -> cuentaRegistros();
$resultOrden = $datosOrden -> obtenerRegistros();

if($registros == 0){
		echo "<p style='font-size:15px; font-weight:bold;'>No existen registros coincidentes.</p>";
		}
else{
foreach($resultOrden as $datosOrden){

?>

<table class="encabezado-orden">
		<thead>
				<tr>
						<th class='titulos'>ID Orden de Compra</th><th class='titulos'>Proveedor</th><th class='titulos'>Estatus de Orden de Compra</th>
				<tr>
		</thead>
<?php
		echo "<tr>";
		echo "<td>" . $datosOrden -> id_orden . "</td>";
		echo "<td>" . $datosOrden -> proveedor . "</td>";
		echo "<td>" . $datosOrden -> status_orden . "</td>";
		echo "</tr>";
		$id_orden = $datosOrden -> id_orden;
		$proveedor = $datosOrden -> id_proveedor;
		
?>
</table>
<?php
echo "<input type='hidden' id='parciales" . $id_orden . "' value='" . $datosOrden -> parciales . "'/>";
?>
<p class='titulos' style="padding : 5px;">Datos de la Entrada</p>


<table class="datos-entrada">
		<tr>
				<td>
						<label for="select-almacen">Almacen</label>
				</td>
				<td>
						<select name="select-almacen" id="select-almacen<?php echo $id_orden; ?>" onchange="">
						<?php
								$sqlAux = "SELECT ad_almacenes.id_almacen, ad_almacenes.nombre FROM ad_almacenes 
											LEFT JOIN  na_almacenes_detalle_entradas ON ad_almacenes.id_almacen = na_almacenes_detalle_entradas.id_almacen
											WHERE ad_almacenes.activo=1 AND na_almacenes_detalle_entradas.id_almacen_tipo_entrada = 70002";
								$datosAux = new consultarTabla($sqlAux);
								$resultAux = $datosAux -> obtenerRegistros();
								foreach($resultAux AS $datosAux){
										echo "<option value='" . $datosAux -> id_almacen . "'>" . $datosAux -> nombre . "</option>";
										}
						?>
						</select>
				</td>
				<td>
						<label for="obervaciones">Observaciones</label>
				</td>
				<td>
						<textarea name="obervaciones" id="observaciones<?php echo $id_orden; ?>" style="height:70px;"></textarea>
				</td>
				
		</tr>
		<tr>
				<td>
						<label for="fac-prov">Factura Proveedor</label>
				</td>
				<td>
						<input type="text" name="fac-prov" id="fac-prov<?php echo $id_orden; ?>"/>
				</td>
				<td>
						&nbsp;
				</td>
				<td>
						&nbsp;
				</td>
		</tr>
		<tr>
				<td>
						<label for="pedimento">Pedimento No.</label>
				</td>
				<td>
						<input type="text" name="pedimento" id="pedimento<?php echo $id_orden; ?>"/>
				</td>
				<td>
						<label for="pedimento_fec">Fecha de Pedimento</label>
				</td>
				<td>
						<input type="text" name="pedimento_fec" id="pedimento_fec<?php echo $id_orden; ?>" onFocus="calendario(this);"/><span style="font-size:10px;font-style:italic;">&nbsp;dd/mm/yyyy</span>
				</td>
		</tr>
		<tr>
				<td>
						<label for="select-aduana">Aduana</label>
				</td>
		
				<td>
						<select name="select-aduana" id="select-aduana<?php echo $id_orden; ?>" onchange="">
						<?php
								$sqlAux = "SELECT id_proveedor,razon_social FROM na_proveedores 
											LEFT JOIN na_tipos_proveedores ON na_proveedores.id_tipo_proveedor = na_tipos_proveedores.id_tipo_proveedor
											WHERE na_proveedores.activo=1 AND na_tipos_proveedores.es_agente_aduanal = 1 OR na_proveedores.id_proveedor = 0";
								$datosAux = new consultarTabla($sqlAux);
								$resultAux = $datosAux -> obtenerRegistros();
								foreach($resultAux AS $datosAux){
										echo "<option value='" . $datosAux -> id_proveedor . "'>" . $datosAux -> razon_social . "</option>";
										}
						?>
						</select>
				</td>
				<td>
						&nbsp;
				</td>
				<td>
						<input type="hidden" name="proveedor_id" id="proveedor_id<?php echo $id_orden; ?>" value="<?php echo $proveedor; ?>"/>
				</td>
						
		</tr>
</table>
<p class='titulos' style="padding : 5px;">Detalle</p>
<table class="encabezado-orden">
		<thead>
				<tr>
						<th class='titulos'>Producto</th><th class='titulos' style='text-align:center;'>Cantidad Solicitada</th><th class='titulos' style='text-align:center;'>Cantidad Recibida</th><th class='titulos'>Cantidad a Ingresar</th>
				</tr>
		</thead>
		<tbody id='res-prod<?php echo $id_orden; ?>'>
		<?php
		$sqlProductos = "SELECT na_ordenes_compra_producto_detalle.id_orden_compra_producto_detalle AS id_detalle, na_productos.id_producto AS id_producto, 
						na_productos.nombre AS producto, na_ordenes_compra_producto_detalle.cantidad AS cantidad, 
						na_ordenes_compra_producto_detalle.precio_unitario AS precio_unitario, 
						na_movimientos_almacen_detalle.cantidad AS recibidos
						FROM na_ordenes_compra_producto_detalle 
						LEFT JOIN na_productos ON na_ordenes_compra_producto_detalle.id_producto = na_productos.id_producto
						LEFT JOIN na_movimientos_almacen USING(id_orden_compra) 
						LEFT JOIN na_movimientos_almacen_detalle ON na_movimientos_almacen.id_control_movimiento = na_movimientos_almacen_detalle.id_control_movimiento
						AND na_movimientos_almacen_detalle.id_producto = na_ordenes_compra_producto_detalle.id_producto
						WHERE na_ordenes_compra_producto_detalle.id_orden_compra = " . $id_orden . " GROUP BY id_producto";
		$datosProductos= new consultarTabla($sqlProductos);
		$resultProductos = $datosProductos -> obtenerRegistros();
		$contador = 0;
		foreach($resultProductos AS $datosProd){
				echo "<tr>";
				echo "<td>" . $datosProd -> producto . "</td>";
				echo "<td style='text-align:center;'>" . $datosProd -> cantidad . "</td>";
				echo "<td style='text-align:center;'>" . $datosProd -> recibidos . "</td>";
				echo "<td style='text-align:center;'><input style='text-align:right;'type='text' name='cantidadI' id='cantidadI" . $id_orden . $contador . "' onkeydown='return noletrasCantidades(event);'/></td>";
				echo "<td style='display:none'>";
				echo "<input type='hidden' name='idProducto' id='idProducto" . $id_orden . $contador . "'value='" . $datosProd -> id_producto . "'/>";
				echo "<input type='hidden' name='idPrecioUnitario' id='idPrecioUnitario" . $id_orden . $contador . "'value='" . $datosProd -> precio_unitario . "'/>";
				echo "<input type='hidden' name='idDetalleOrden' id='idDetalleOrden" . $id_orden . $contador . "'value='" . $datosProd -> id_detalle . "'/>";
				echo "<input type='hidden' name='cantidadProd' id='cantidadProd" . $id_orden . $contador . "'value='" . $datosProd -> cantidad . "'/>";
				echo "<input type='hidden' name='cantidadR' id='cantidadR" . $id_orden . $contador . "'value='" . $datosProd -> recibidos . "'/>";
				echo "</td>";
				echo "</tr>";
				$contador++;
				}
		
		?>
		</tbody>
</table>

<?php

$disabled = "";
$checked = "";
if($datosOrden -> parciales == 0){
		$disabled = "disabled";
		$checked = "checked";
		}

?>

<p style="padding:5px; font-size:10px;">
		<input type="checkbox" name='completo' id='completo<?php echo $id_orden; ?>'<?php echo " " . $disabled . " " . $checked?>/>
		&nbsp;&nbsp;Marcar como "completa" esta Orden de Compra (Al activar esta opci&oacute;n el sistema no permitir&aacute; mas entradas relacionadas a esta Orden de Compra)
</p>
<p style="width:100%; text-align:right;"><input type="button" id="generarOrden" name="generarOrden" value="Generar Orden de Entrada" class="boton" onClick="guardaOrdenEntrada('<?php echo $id_orden; ?>');"/></p>

<hr>
<?php
}
}
?>







