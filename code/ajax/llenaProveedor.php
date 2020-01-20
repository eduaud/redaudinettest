<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id = $_POST['id'];
$caso = $_POST['caso'];

if($caso == 1){
		$where = "";
		if($id != 0)
				$where .= " AND ad_proveedores.id_tipo_proveedor = " . $id;
				
				$sql = "SELECT id_proveedor, razon_social 
						FROM ad_proveedores 
						LEFT JOIN ad_tipos_proveedores ON ad_proveedores.id_tipo_proveedor = ad_tipos_proveedores.id_tipo_proveedor
						WHERE 1" . $where . " ORDER BY razon_social";
				$result = new consultarTabla($sql);
				$datos = $result -> obtenerRegistros();
						foreach($datos as $dato){
								echo '<option value="' . $dato -> id_proveedor . '">' . utf8_encode($dato -> razon_social) . '</option>';
								}
		}

if($caso == 2){
		$where = "";
		if($id == 1)
				$where .= "es_de_productos = 1";
		else if($id == 2)
				$where .= "es_de_servicios = 1";
		
		$sql = "SELECT id_tipo_proveedor, nombre 
				FROM ad_tipos_proveedores 
				WHERE " . $where;
				
		$result = new consultarTabla($sql);
		$datos = $result -> obtenerRegistros();
		echo '<option value="0">Todos</option>';
				foreach($datos as $dato){
						echo '<option value="' . $dato -> id_tipo_proveedor . '">' . utf8_encode($dato -> nombre) . '</option>';
						}
		}

?>