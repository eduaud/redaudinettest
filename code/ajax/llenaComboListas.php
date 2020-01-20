<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$caso = $_POST['caso'];

if($caso == 1){
		$id = $_POST['id'];
		$sql = "SELECT DISTINCT id_tipo_producto, nombre FROM na_tipos_productos WHERE activo = 1 AND id_familia_producto IN ($id)";
		}
if($caso == 2){
		$familia = $_POST['familia'];
		$tipo = $_POST['tipo'];
		
		$sql = "SELECT DISTINCT na_modelos_productos.id_modelo_producto, na_modelos_productos.nombre
				FROM na_productos 
				LEFT JOIN na_modelos_productos ON na_productos.id_modelo_producto = na_modelos_productos.id_modelo_producto
				WHERE na_productos.activo = 1 AND na_productos.id_familia_producto IN ($familia) AND na_productos.id_tipo_producto IN ($tipo)
				ORDER BY na_modelos_productos.nombre";
		}
if($caso == 3){
		$familia = $_POST['familia'];
		$tipo = $_POST['tipo'];
		$modelo = $_POST['modelo'];
		
		$sql = "SELECT DISTINCT na_marcas_productos.id_marca_producto, na_marcas_productos.nombre
				FROM na_productos 
				LEFT JOIN na_marcas_productos ON na_productos.id_marca_producto = na_marcas_productos.id_marca_producto
				WHERE na_productos.activo = 1 AND id_familia_producto IN ($familia) AND id_tipo_producto IN ($tipo) AND id_modelo_producto IN ($modelo)
				ORDER BY na_marcas_productos.nombre";
		}
if($caso == 4){
		$sql = "SELECT id_lista_precios, nombre FROM ad_lista_precios WHERE activo = 1 ORDER BY nombre";
				echo '<option value="0">Selecciona una opci&oacute;n</option>';
				}

if($caso == 5){
		$id = $_POST['proveedor'];
		$sql = "SELECT DISTINCT na_marcas_productos.id_marca_producto, na_marcas_productos.nombre
				FROM na_productos_proveedor_detalle
				LEFT JOIN na_proveedores ON na_productos_proveedor_detalle.id_proveedor = na_proveedores.id_proveedor
				LEFT JOIN na_tipos_proveedores ON na_tipos_proveedores.id_tipo_proveedor = na_proveedores.id_tipo_proveedor
				LEFT JOIN na_productos ON na_productos_proveedor_detalle.id_producto = na_productos.id_producto
				LEFT JOIN na_marcas_productos ON na_productos.id_marca_producto = na_marcas_productos.id_marca_producto
				WHERE na_productos.activo = 1 AND es_de_productos = 1 AND na_productos_proveedor_detalle.id_proveedor IN(" . $id . ") ORDER BY na_marcas_productos.nombre";
				
				}

$datos = new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();

foreach($result as $opcion){
		echo "<option value='" . $opcion[0] . "'>" . utf8_encode($opcion[1]) . "</option>";
		}
		
		
		
?>