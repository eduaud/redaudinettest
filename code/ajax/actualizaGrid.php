<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id = $_POST["idLista"];
$productos = $_POST["detallesProductos"];

$insertaProd = explode(",", $productos);
$contadorProd = count($insertaProd);


	for($k=0; $k<$contadorProd; $k++){
		$partes = explode("|", $insertaProd[$k]);
		$contadorPartes = count($partes);
		$partes[2] = str_replace("$", "", $partes[2]);
		
		/*if($partes[1] == ""){
				$partes[1] = 0;
				}
		if($partes[0] == ""){
				$partes[0] = 0;
				}
		if($partes[2] == ""){
				$partes[2] = 0;
				}*/
		if($partes[1] != "" && $partes[2] != ""){
				$consultaProducto = "SELECT id_producto FROM na_listas_detalle_productos WHERE id_lista_precios = $id AND id_producto = $partes[0]";
				$datosProducto = new consultarTabla($consultaProducto);
				$contadorPr = $datosProducto -> cuentaRegistros();
				
				if($contadorPr > 0)
						$sqlProd = "UPDATE na_listas_detalle_productos SET porcentaje = $partes[1], precio_final = $partes[2] WHERE id_lista_precios = $id AND id_producto = $partes[0]";
				else
						$sqlProd = "INSERT INTO na_listas_detalle_productos(id_lista_precios, id_producto, porcentaje, precio_final) VALUES ($id, $partes[0], $partes[1], $partes[2])";
						
				mysql_query($sqlProd) or die ("Error en la consulta: <br>$sqlProd. ".mysql_error());
				
				/*if($partes[1] == 0){
						$sqlDel = "DELETE FROM na_listas_detalle_productos WHERE id_lista_precios = $id AND id_producto = $partes[0]";
						mysql_query($sqlDel) or die ("Error en la consulta: <br>$sqlDel. ".mysql_error());
						}*/
				}
		}
	

echo "Datos insertados correctamente\nPuedes consultar la lista en el campo Lista de Precios";


?>