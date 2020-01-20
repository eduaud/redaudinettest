<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id = $_POST["idLista"];
$productos = $_POST["detallesProductos"];
//$productosLista = $_POST["productosLista"];

/*$insertaProd2 = explode(",", $productosLista);
$contadorProd2 = count($insertaProd2);*/
//echo $productos;
$insertaProd = explode(",", $productos);
$contadorProd = count($insertaProd);



	for($k=0; $k<$contadorProd; $k++){
		$partes = explode("|", $insertaProd[$k]);
		
		$partes[1] = str_replace("$", "", $partes[1]);
		
		if($partes[1] != ""){
				$sqlProd = "UPDATE na_productos SET precio_lista = $partes[1] WHERE id_producto = $partes[0]";
				mysql_query($sqlProd) or die ("Error en la consulta: <br>$sqlProd. ".mysql_error());
				}

		}
		
/*if($productosLista != ""){
	for($k=0; $k<$contadorProd2; $k++){
		$partes = explode("|", $insertaProd2[$k]);
		$contadorPartes = count($partes);
		$partes[2] = str_replace("$", "", $partes[2]);
		
		if($partes[1] == ""){
				$partes[1] = 0;
				}
		if($partes[0] == ""){
				$partes[0] = 0;
				}
		if($partes[2] == ""){
				$partes[2] = 0;
				}
		
		$consultaProducto = "SELECT id_producto FROM na_listas_detalle_productos WHERE id_lista_precios = $id AND id_producto = $partes[0]";
		$datosProducto = new consultarTabla($consultaProducto);
		$contadorPr = $datosProducto -> cuentaRegistros();
		
		if($contadorPr > 0)
				$sqlProd2 = "UPDATE na_listas_detalle_productos SET porcentaje = 0, precio_final = $partes[2] WHERE id_lista_precios = $id AND id_producto = $partes[0]";
		
		
		mysql_query($sqlProd2) or die ("Error en la consulta: <br>$sqlProd2. ".mysql_error());
		
		/*if($partes[1] == 0){
				$sqlDel = "DELETE FROM na_listas_detalle_productos WHERE id_lista_precios = $id AND id_producto = $partes[0]";
				mysql_query($sqlDel) or die ("Error en la consulta: <br>$sqlDel. ".mysql_error());
				}*/
		
		//}
//	}
	
echo "Productos actualizados correctamente";


?>