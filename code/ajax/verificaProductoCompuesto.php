<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$idProducto = $_POST["id"];

$sql = "SELECT producto_compuesto FROM na_productos WHERE id_producto = " . $idProducto;
$result = new consultarTabla($sql);
$dato = $result -> obtenerLineaRegistro();

if($dato['producto_compuesto'] == 1){
		$sql2 = "SELECT 1 FROM na_movimientos_apartados WHERE id_producto = " . $idProducto . " AND id_estatus_apartado <> 3 AND id_estatus_apartado <> 4";
		$result2 = new consultarTabla($sql2);
		$contador = $result2 -> cuentaRegistros();
		$respuesta['modifica'] = $contador;
		$respuesta['caso'] = 1;
		}
else if($dato['producto_compuesto'] == 0){
		$sql3 = "SELECT id_producto FROM na_productos_basicos_detalle WHERE id_producto_relacionado = " . $idProducto;
		$result3 = new consultarTabla($sql3);
		$basico = $result3 -> cuentaRegistros();
		if($basico == 0){
				$contador = 0;
				$respuesta['modifica'] = 0;
				$respuesta['caso'] = 0;
				}
		else{
				$contador = 0;
				$datoBasico = $result3 -> obtenerRegistros();
				foreach($datoBasico as $verifica){
						$sql4 = "SELECT 1 FROM na_movimientos_apartados WHERE id_producto = " . $verifica -> id_producto . " AND id_estatus_apartado <> 3 AND id_estatus_apartado <> 4";
						$result4 = new consultarTabla($sql4);
						$valida = $result4 -> cuentaRegistros();
						if($valida > 0)
								$contador += 1;
						$respuesta['modifica'] = $contador;
						$respuesta['caso'] = 2;
						}
				}
		
		}


echo json_encode($respuesta);

?>