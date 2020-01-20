<link rel="stylesheet" href="../../js/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="../../js/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>

<?php	
include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

mysql_query("SET NAMES utf8");

$orden = $_POST['idOrden'];
$proveedor = $_POST['proveedor'];
$documento = $_POST['documento'];
$where = "";

if(!empty($orden)) $where .= " AND id_orden_compra = '" . $orden . "'";
if(!empty($documento)) $where .= " AND (documento_proveedor_1 = '" . $documento ."' OR documento_proveedor_2 = '" . $documento . "')";
if($proveedor != 0) $where .= " AND ad_ordenes_compra_productos.id_proveedor = " . $proveedor;

$sqlOrden = "SELECT";
$sqlOrden .= " id_control_orden_compra AS id_control_orden,";
$sqlOrden .= " id_orden_compra AS id_orden,";
$sqlOrden .= " ad_proveedores.razon_social AS proveedor,";
//$sqlOrden .= " ad_estatus_orden_compra.nombre AS status_orden,";
$sqlOrden .= " ad_proveedores.id_proveedor AS id_proveedor,";
$sqlOrden .= " ad_ordenes_compra_productos.documento_proveedor_1 AS documento_proveedor_1,";
//$sqlOrden .= " permite_recibo_parciales AS parciales,";
$sqlOrden .= " ad_ordenes_compra_productos.id_tipo_recepcion_productos AS parciales,";
$sqlOrden .= " ad_ordenes_compra_productos.id_sucursal";
$sqlOrden .= " FROM ad_ordenes_compra_productos";
$sqlOrden .= " INNER JOIN ad_proveedores";
$sqlOrden .= " ON ad_proveedores.id_proveedor = ad_ordenes_compra_productos.id_proveedor";
//$sqlOrden .= " INNER JOIN ad_estatus_orden_compra";
//$sqlOrden .= " ON ad_estatus_orden_compra.id_estatus_odc = ad_ordenes_compra_productos.id_estatus";
//$sqlOrden .= " WHERE ad_ordenes_compra_productos.id_estatus = 4";	//2: Aprobada
$sqlOrden .= " WHERE (ad_ordenes_compra_productos.id_estatus = 2 OR ad_ordenes_compra_productos.id_estatus = 4)";
$sqlOrden .= $where;

//echo $sqlOrden;

$datosOrden = new consultarTabla($sqlOrden);
$registros = $datosOrden -> cuentaRegistros();
$resultOrden = $datosOrden -> obtenerRegistros();

if($registros == 0){
	echo "<p style='font-size:15px; font-weight:bold;'>No existen registros coincidentes.</p>";
}
else{
	foreach($resultOrden as $datosOrden){
		?>
		<form id="postp" method="post" action="">
			<table class="encabezado-orden">
				<thead>
					<tr>
						<th class='titulos'>ID Orden de Compra</th>
						<th class='titulos'>Proveedor</th>
						<th class='titulos'>Estatus de Orden de Compra</th>
					<tr>
				</thead>
				<tr>
					<td><p><?php echo $datosOrden -> id_orden; ?></p></td>
					<td><p><?php echo $datosOrden -> proveedor; ?></p></td>
					<td><p><?php echo $datosOrden -> status_orden; ?></p></td>
				</tr>
			<?php
			$id_control_orden = $datosOrden -> id_orden;
			$id_orden = $datosOrden -> id_control_orden;
			$proveedor = $datosOrden -> id_proveedor;
			?>
			</table>
			<?php echo "<input type='hidden' id='parciales" . $id_orden . "' value='" . $datosOrden -> parciales . "'/>"; ?>
			<p class='titulos' style="padding : 5px;">Datos de la Entrada</p>
			<table width="908" border="0" class="datos-entrada">
				<tr>
					<td width="168"><p>Almacen</p></td>
					<td width="197">
						<select name="select-almacen" id="select-almacen<?php echo $id_orden; ?>" onchange="">
							<?php
							$sqlAux = "SELECT";
							$sqlAux .= " ad_almacenes.id_almacen,";
							$sqlAux .= " ad_almacenes.nombre";
							$sqlAux .= " FROM ad_almacenes";
							$sqlAux .= " LEFT JOIN ad_almacenes_detalle_entradas";
							$sqlAux .= " ON ad_almacenes.id_almacen = ad_almacenes_detalle_entradas.id_almacen";
							$sqlAux .= " WHERE ad_almacenes.activo = 1";
							$sqlAux .= " AND ad_almacenes_detalle_entradas.id_almacen_tipo_entrada = 70002";
							$datosAux = new consultarTabla($sqlAux);
							$resultAux = $datosAux -> obtenerRegistros();
							foreach($resultAux AS $datosAux){
								echo "<option value='" . $datosAux -> id_almacen . "'>" . $datosAux -> nombre . "</option>";
							}
							?>
						</select>
					</td>
					<td width="338">&nbsp;</td>
					<td width="177">&nbsp;</td>
				</tr>
				<tr>
					<td><p>Documento Proveedor</p></td>
					<td><p><?php echo $datosOrden -> documento_proveedor_1; ?></p></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><p>Factura Proveedor</p></td>
					<td><input type="text" name="fac-prov" id="fac-prov<?php echo $id_orden; ?>"/></td>
					<td>&nbsp;<input type='hidden' id='id_sucursal<?php echo $id_orden; ?>' value='<?php echo $datosOrden -> id_sucursal; ?>'/></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><p>Pedimento No.</p></td>
					<td><input type="text" name="pedimento" id="pedimento<?php echo $id_orden; ?>"/></td>
					<td><p>Fecha de Pedimento</p></td>
					<td><input type="text" name="pedimento_fec" id="pedimento_fec<?php echo $id_orden; ?>" onFocus="calendario(this);"/><span style="font-size:10px;font-style:italic;">&nbsp;dd/mm/yyyy</span></td>
				</tr>
				<tr>
					<td><p>Aduana</p></td>
					<td>
						<select name="select-aduana" id="select-aduana<?php echo $id_orden; ?>" onchange="">
						<?php
							$sqlAux = "SELECT";
							$sqlAux .= " id_proveedor,";
							$sqlAux .= " razon_social";
							$sqlAux .= " FROM ad_proveedores";
							$sqlAux .= " LEFT JOIN ad_tipos_proveedores";
							$sqlAux .= " ON ad_proveedores.id_tipo_proveedor = ad_tipos_proveedores.id_tipo_proveedor";
							$sqlAux .= " WHERE ad_proveedores.activo = 1";
							$sqlAux .= " AND ad_tipos_proveedores.es_agente_aduanal = 1";
							//$sqlAux .= " OR ad_proveedores.id_proveedor = 0";
							$datosAux = new consultarTabla($sqlAux);
							$resultAux = $datosAux -> obtenerRegistros();
							foreach($resultAux AS $datosAux){ 
								echo "<option value='" . $datosAux -> id_proveedor . "'>" . $datosAux -> razon_social . "</option>";
							}
						?>
						</select>
					</td>
					<td>&nbsp;</td>
					<td><input type="hidden" name="proveedor_id" id="proveedor_id<?php echo $id_orden; ?>" value="<?php echo $proveedor; ?>"/></td>
				</tr>
			</table>
			
			<p class='titulos' style="padding : 5px;">Detalle</p>
			<table class="encabezado-orden">
				<thead>
					<tr>
						<th class='titulos'>Producto</th>
						<th class='titulos' style='text-align:center;'>Ingresar Numero de Serie</th>
						<th class='titulos' style='text-align:center;'>Requiere Validaci&oacute;n Tipo de Equipo</th>
						<th class='titulos' style='text-align:center;'>Cantidad Solicitada</th>
						<th class='titulos' style='text-align:center;'>Cantidad Recibida</th>
						<th class='titulos'>Cantidad a Ingresar</th>
						<th class='titulos'>&nbsp;</th>
						<th class='titulos'>&nbsp;</th>
					</tr>
				</thead>
				<tbody id='res-prod<?php echo $id_orden; ?>'>
				<?php
					$id_detalles = array();
					/*
					$sqlProductos = "SELECT";
					$sqlProductos .= " ad_ordenes_compra_productos_detalle.id_detalle AS id_detalle,";
					$sqlProductos .= " cl_productos_servicios.id_producto_servicio AS id_producto,";
					$sqlProductos .= " cl_productos_servicios.nombre AS producto,";
					$sqlProductos .= " ad_ordenes_compra_productos_detalle.cantidad AS cantidad,";
					$sqlProductos .= " ad_ordenes_compra_productos_detalle.precio_final AS precio_unitario,";
					$sqlProductos .= " cl_productos_servicios.requiere_numero_serie AS requiere_numero_serie,";
					$sqlProductos .= " cl_productos_servicios.valida_tipo_producto AS valida_tipo_producto,";
					$sqlProductos .= " SUM(ad_movimientos_almacen_detalle.cantidad) AS recibidos";
					$sqlProductos .= " FROM ad_ordenes_compra_productos_detalle";
					$sqlProductos .= " LEFT JOIN cl_productos_servicios";
					$sqlProductos .= " ON ad_ordenes_compra_productos_detalle.id_producto = cl_productos_servicios.id_producto_servicio";
					$sqlProductos .= " LEFT JOIN ad_movimientos_almacen USING(id_control_orden_compra)";
					$sqlProductos .= " LEFT JOIN ad_movimientos_almacen_detalle";
					$sqlProductos .= " ON ad_movimientos_almacen.id_control_movimiento = ad_movimientos_almacen_detalle.id_control_movimiento";
					//$sqlProductos .= " AND ad_movimientos_almacen_detalle.id_producto = ad_ordenes_compra_productos_detalle.id_producto";
					$sqlProductos .= " WHERE ad_ordenes_compra_productos_detalle.id_control_orden_compra = ".$id_orden;
					$sqlProductos .= " GROUP BY ad_ordenes_compra_productos_detalle.id_detalle;";
					*/
					
					$sqlProductos = " SELECT";
					$sqlProductos .= " ad_ordenes_compra_productos_detalle.id_detalle AS id_detalle,";
					$sqlProductos .= " cl_productos_servicios.id_producto_servicio AS id_producto,";
					$sqlProductos .= " cl_productos_servicios.nombre AS producto,";
					$sqlProductos .= " ad_ordenes_compra_productos_detalle.cantidad AS cantidad,";
					$sqlProductos .= " ad_ordenes_compra_productos_detalle.precio_unitario AS precio_unitario,";
					$sqlProductos .= " cl_productos_servicios.requiere_numero_serie AS requiere_numero_serie,";
					$sqlProductos .= " cl_productos_servicios.valida_tipo_producto AS valida_tipo_producto,";
					$sqlProductos .= " (SELECT SUM(cantidad) FROM ad_movimientos_almacen";
						$sqlProductos .= " LEFT JOIN ad_movimientos_almacen_detalle";
						$sqlProductos .= " ON ad_movimientos_almacen.id_control_movimiento = ad_movimientos_almacen_detalle.id_control_movimiento";
						$sqlProductos .= " WHERE ad_movimientos_almacen.activo = 1 AND ad_movimientos_almacen_detalle.id_orden_compra_producto_detalle = ad_ordenes_compra_productos_detalle.id_detalle";
					$sqlProductos .= " ) as recibidos";
					$sqlProductos .= " FROM ad_ordenes_compra_productos_detalle";
					$sqlProductos .= " LEFT JOIN cl_productos_servicios";
					$sqlProductos .= " ON ad_ordenes_compra_productos_detalle.id_producto = cl_productos_servicios.id_producto_servicio";
					$sqlProductos .= " WHERE ad_ordenes_compra_productos_detalle.id_control_orden_compra = ".$id_orden;
					
					//echo $sqlProductos;
					
					//$sCantidad =
					
					$datosProductos= new consultarTabla($sqlProductos);
					$resultProductos = $datosProductos -> obtenerRegistros();
					$contador = 0;
					foreach($resultProductos AS $datosProd){
					
						array_push($id_detalles,$datosProd -> id_detalle);
						$indActivo="";
						$valorCantidad = "";
						$indActivo = "";
						$indActivo2 = "";
						$id_carga = "";
						$queryNumerosSerieActivos = "SELECT COUNT(id_orden_compra) cantidad, id_carga FROM cl_importacion_numeros_series WHERE id_orden_compra = ".$id_orden." AND n1 = '".$datosProd -> id_detalle."' AND activo = '1';";
						$datosNSA= new consultarTabla($queryNumerosSerieActivos);
						$resultNSA = $datosNSA -> obtenerRegistros();
						
						foreach($resultNSA AS $datosNSA){
							if($datosNSA->cantidad != "0"){
								$valorCantidad = $datosNSA->cantidad;
								$indActivo = ' readonly'; 
								$indActivo2 = 'disabled="disabled"';
								$id_carga = $datosNSA->id_carga;
							}
						}
					
						if($datosProd -> requiere_numero_serie=='1'){$requiere_numero_serie='SI';}else{$requiere_numero_serie='NO';}
						if($datosProd -> valida_tipo_producto=='1'){$valida_tipo_producto='SI';}else{$valida_tipo_producto='NO';}
						if(is_null($datosProd -> recibidos)){$recibidos="0";}else{$recibidos=$datosProd -> recibidos;}
						echo "<tr>";
						echo "<td>".$datosProd -> producto."</td>";
						echo "<td style='text-align:center;'>".$requiere_numero_serie;
						echo "<input type='hidden' id='requiereNumerosDeSerie".$id_orden.$contador."' value='".$datosProd -> requiere_numero_serie."'>";
						echo "</td>";
						echo "<td style='text-align:center;'>".$valida_tipo_producto;
						echo "<input type='hidden' id='requiereValidarTipoProducto".$id_orden.$contador."' value='".$datosProd -> valida_tipo_producto."'>";
						echo "</td>";
						echo "<td style='text-align:center;'>".$datosProd -> cantidad."</td>";
						echo "<td style='text-align:center;'>".$recibidos."</td>";
						echo "<td style='text-align:center;'>";
						
						$disabled = $datosProd -> recibidos == $datosProd -> cantidad ? 'disabled = "disabled"' : '';
						
						echo '<input '.$disabled.' style="text-align:right; width:35px;" '.$indActivo.' maxlength="5" type="text" name="cantidadI'.$id_orden.$contador.'"	id="cantidadI'.$id_orden.$contador.'" onkeydown="return noletrasCantidades(event);" onblur="validaCantidadIgresar(this.value,'.$datosProd -> cantidad.','.$recibidos.','.$id_orden.','.$contador.');" value="'.$valorCantidad.'"/>';
						if($indActivo!=''){
							echo '&nbsp;&nbsp;&nbsp;<input type="button" id="desbloquear'.$id_orden.$contador.'" name="desbloquear'.$id_orden.$contador.'" value="Activar" class="small button grey" onClick="desactivarIRDS('.$id_orden.','.$datosProd->id_detalle.','.$contador.','.$id_carga.');" />';
						}
						/*
						echo "<td style='text-align:center;'><input style='text-align:right;'type='text' name='cantidadI' id='cantidadI".$id_orden.$contador."'";
						if($datosProd -> requiere_numero_serie=='1'){
							echo " onkeyup='if(this.value!=0){document.getElementById(".chr(34)."boton-importar".$id_orden.$contador.chr(34).").style.display = ".chr(34)."block".chr(34)."}else{document.getElementById(".chr(34)."boton-importar".$id_orden.$contador.chr(34).").style.display = ".chr(34)."none".chr(34)."}''";
						}
						echo " onkeydown='return noletrasCantidades(event);'/>";
						*/
						echo "<input type='hidden' name='idProducto' id='idProducto".$id_orden.$contador."'value='".$datosProd -> id_producto."'/>";
						echo "<input type='hidden' name='idPrecioUnitario' id='idPrecioUnitario".$id_orden.$contador."'value='".$datosProd -> precio_unitario."'/>";
						echo "<input type='hidden' name='idDetalleOrden' id='idDetalleOrden".$id_orden.$contador."'value='".$datosProd -> id_detalle."'/>";
						echo "<input type='hidden' name='cantidadProd' id='cantidadProd".$id_orden.$contador."'value='".$datosProd -> cantidad."'/>";
						
						echo "<input type='hidden' name='cantidadR' id='cantidadR".$id_orden.$contador."'value='".$datosProd -> recibidos."'/>";
						
						//echo "<input type='' name='cantidadR' id='cantidadR".$id_orden.$contador."'value='".$valorCantidad."'/>";
						
						echo "</td>";
						echo '<td style="text-align:center;"><div id="boton-importar'.$id_orden.$contador.'">';
						if($datosProd -> requiere_numero_serie=='1'){
							//if($indActivo==""){			
								echo '<input '.$disabled.' type="button" '.$indActivo2.' id="importarOrden'.$id_orden.$contador.'" name="importarOrden'.$id_orden.$contador.'" value="Importar" class="small button grey" ';
								echo 'onClick="validacionCantidadAIngresar('.$id_orden.', '.$contador.', '.$datosProd -> cantidad.', '.$datosProd -> valida_tipo_producto.', 1, '.$datosProd -> id_producto.','.$datosProd -> id_detalle.');"';
								echo ' />';
								echo '<br /><input type="hidden" id="cantidadIAux'.$id_orden.$contador.'" name="cantidadIAux'.$id_orden.$contador.'" value="">';
								echo '<input type="hidden" id="numeroCarga'.$id_orden.$contador.'" name="numeroCarga'.$id_orden.$contador.'" value="">';
								//echo '<input type="" id="numeroCargas['.$id_orden.']['.$contador.']" name="numeroCargad['.$id_orden.']['.$contador.']" value="">';
								//echo '<input type="" name="ValorTexto" value="cantidadI'.$id_orden.$contador.'">';
							//}
						}else{echo '&nbsp;';}
						
						echo "</td>";
						echo '<td style="text-align:center;"><div id="boton-capturar'.$id_orden.$contador.'">';
						if($datosProd -> valida_tipo_producto=='1'){
							//if($indActivo==""){			
								echo '<input '.$disabled.' type="button" '.$indActivo2.' id="capturarOrden'.$id_orden.$contador.'" name="capturarOrden'.$id_orden.$contador.'" value="Capturar" class="small button grey" ';
								echo 'onClick="
								limpiaCajasAuxiliares('.$id_orden.','.$contador.');
								validacionCantidadAIngresar('.$id_orden.', '.$contador.', '.$datosProd -> cantidad.', '.$datosProd -> valida_tipo_producto.', 2, '.$datosProd -> id_producto.','.$datosProd -> id_detalle.');"';
								echo ' />';
							//}
						}else{echo '&nbsp;';}
						echo "</td>";
						echo "</tr>";
						$contador++;
					}
					//print_r($id_detalles);
				?>
				</tbody>
			</table>
		</form>
		<?php
		$disabled = "";
		$checked = "";
		if($datosOrden -> parciales == 3){ //Entregas completas
			$disabled = "disabled";
			$checked = "checked";
		}
		?>
		<p style="padding:5px; font-size:10px;">
			<input type="checkbox" name='completo' id='completo<?php echo $id_orden; ?>'<?php echo " " . $disabled . " " . $checked?>/>
			&nbsp;&nbsp;Marcar como "completa" esta Orden de Compra (Al activar esta opci&oacute;n el sistema no permitir&aacute; mas entradas relacionadas a esta Orden de Compra)
		</p>
		<p style="width:100%; text-align:right;">
		<input type="button" id="generarOrden" name="generarOrden" value="Generar Orden de Entrada" class="boton" 
			onClick="
				if(validaCantidadesImportadas('<?php echo $id_orden.chr(39).','.chr(39).$contador; ?>')){
					if(validaCantidadesIngresadas('<?php echo $id_orden.chr(39).','.chr(39).$contador; ?>')){
						guardaOrdenEntrada('<?php echo $id_orden; ?>','<?php echo $ids=implode($id_detalles,','); ?>');
					}
				}
		"/>
		</p>
		<hr>
		<?php
	}
}
?>




<?php	
/*
include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");
mysql_query("SET NAMES utf8");

//ALMACENES CON TIPO DE ENTRADA 7002 -->
$sql = "SELECT";
$sql .= " ad_almacenes.id_almacen,";
$sql .= " ad_almacenes.nombre";
$sql .= " FROM ad_almacenes";
$sql .= " LEFT JOIN ad_almacenes_detalle_entradas";
$sql .= " ON ad_almacenes.id_almacen = ad_almacenes_detalle_entradas.id_almacen";
$sql .= " WHERE ad_almacenes.activo = 1";
$sql .= " AND ad_almacenes_detalle_entradas.id_almacen_tipo_entrada = 70002";
$datosAlmacen = new consultarTabla($sql);
$resultAlmacen = $datosAlmacen -> obtenerArregloRegistros();
$smarty -> assign("filasAlmacen", $resultAlmacen);
//$smarty -> assign("sql", $sql);
//ALMACENES CON TIPO DE ENTRADA 7002 <--
//ADUANAS -->
$sql = "SELECT";
$sql .= " id_proveedor,";
$sql .= " razon_social";
$sql .= " FROM ad_proveedores";
$sql .= " LEFT JOIN ad_tipos_proveedores";
$sql .= " ON ad_proveedores.id_tipo_proveedor = ad_tipos_proveedores.id_tipo_proveedor";
$sql .= " WHERE ad_proveedores.activo = 1";
$sql .= " AND ad_tipos_proveedores.es_agente_aduanal = 1";
$sql .= " OR ad_proveedores.id_proveedor = 0";
$datosAduana = new consultarTabla($sql);
$resultAduana = $datosAduana -> obtenerArregloRegistros();
$smarty -> assign("filasAduana", $resultAduana);
//$smarty -> assign("sql", $sql);
//ADUANAS <--


$orden = $_POST['idOrden'];
$proveedor = $_POST['proveedor'];
$documento = $_POST['documento'];
$where = "";
//DATO DE ORDEN DE COMPRA -->
if(!empty($orden)) $where .= " AND id_orden_compra = '" . $orden . "'";
if(!empty($documento)) $where .= " AND documento_proveedor_1 = '" . $documento ."' OR documento_proveedor_2 = '" . $documento . "'";
if($proveedor != 0) $where .= " AND ad_ordenes_compra_productos.id_proveedor = " . $proveedor;
$sql = "SELECT";
$sql .= " id_control_orden_compra AS id_control_orden,";
$sql .= " id_orden_compra AS id_orden,";
$sql .= " ad_proveedores.razon_social AS proveedor,";
$sql .= " ad_estatus_orden_compra.nombre AS status_orden,";
$sql .= " ad_proveedores.id_proveedor AS id_proveedor,";
$sql .= " ad_ordenes_compra_productos.documento_proveedor_1 AS documento_proveedor_1,";
//$sql .= " permite_recibo_parciales AS parciales,";
$sql .= " ad_ordenes_compra_productos.id_tipo_recepcion_productos AS parciales,";
$sql .= " id_sucursal";
$sql .= " FROM ad_ordenes_compra_productos";
$sql .= " LEFT JOIN ad_proveedores";
$sql .= " ON ad_proveedores.id_proveedor = ad_ordenes_compra_productos.id_proveedor";
$sql .= " LEFT JOIN ad_estatus_orden_compra";
$sql .= " ON ad_estatus_orden_compra.id_estatus_odc = ad_ordenes_compra_productos.id_estatus";
$sql .= " WHERE ad_ordenes_compra_productos.id_estatus = 2";	//2: Aprobada
$sql .= $where;
$datos = new consultarTabla($sql);
$result = $datos->obtenerArregloRegistros();
$numeroDeRegistros = count($result);
$smarty -> assign("registros", $numeroDeRegistros);
$smarty -> assign("filas", $result);
$smarty -> assign("sql1", $sql);
//DATO DE ORDEN DE COMPRA <--

// PRODUCTOS DE LA ORDEN DE COMPRA -->
$i=1;
$resultado=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:".mysql_error()."\n".mysql_errno());
while($row = mysql_fetch_assoc($resultado)){
	$id_control_orden_compra = $row['id_control_orden'];
}
	$id_control_orden_compra = "'2','8'";
	$sql = "SELECT";
	$sql .= " ad_ordenes_compra_productos_detalle.id_detalle AS id_detalle,";
	$sql .= " cl_productos_servicios.id_producto_servicio AS id_producto,";
	$sql .= " cl_productos_servicios.nombre AS producto,";
	$sql .= " ad_ordenes_compra_productos_detalle.cantidad AS cantidad,";
	$sql .= " ad_ordenes_compra_productos_detalle.precio_final AS precio_unitario,";
	$sql .= " cl_productos_servicios.requiere_numero_serie AS requiere_numero_serie,";
	$sql .= " cl_productos_servicios.valida_tipo_producto AS valida_tipo_producto,";
	$sql .= " ad_movimientos_almacen_detalle.cantidad AS recibidos,";
	$sql .= " ad_ordenes_compra_productos_detalle.id_control_orden_compra";
	$sql .= " FROM ad_ordenes_compra_productos_detalle";
	$sql .= " LEFT JOIN cl_productos_servicios";
	$sql .= " ON ad_ordenes_compra_productos_detalle.id_producto = cl_productos_servicios.id_producto_servicio";
	$sql .= " LEFT JOIN ad_movimientos_almacen USING(id_control_orden_compra)";
	$sql .= " LEFT JOIN ad_movimientos_almacen_detalle";
	$sql .= " ON ad_movimientos_almacen.id_control_movimiento = ad_movimientos_almacen_detalle.id_control_movimiento";
	$sql .= " AND ad_movimientos_almacen_detalle.id_producto = ad_ordenes_compra_productos_detalle.id_producto";
	$sql .= " WHERE ad_ordenes_compra_productos_detalle.id_control_orden_compra IN (".$id_control_orden_compra.")";
	$sql .= " GROUP BY ad_ordenes_compra_productos_detalle.id_producto;";
	$datosProductos = new consultarTabla($sql);
	$resultProductos = $datosProductos -> obtenerArregloRegistros();
	$smarty -> assign("filasProducto", $resultProductos);
	$smarty -> assign("sql2", $sql);
	$i++;

// PRODUCTOS DE LA ORDEN DE COMPRA <--

$smarty -> display("especiales/respuestaEntradasAPartirDeOrdenesCompra.tpl");

*/
?>
