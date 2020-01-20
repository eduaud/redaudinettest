<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");
extract($_POST);
extract($_GET);
$caso = $_POST['caso'];

if($caso == 1){

		$sql = "SELECT id_sucursal, nombre FROM na_sucursales WHERE id_sucursal <> " . $_SESSION["USR"]->sucursalid;

		$datos = new consultarTabla($sql);
		$result = $datos -> obtenerArregloRegistros();

		foreach($result as $opcion){
				echo "<option value='" . $opcion[0] . "'>" . utf8_encode($opcion[1]) . "</option>";
				}
		}
else if($caso == 2){
		echo "<option value='0'></option>";
		}
else if($caso == 3){
	$sql="SELECT id_sucursal,nombre FROM ad_sucursales";
	$datos = new consultarTabla($sql);
	$result = $datos -> obtenerArregloRegistros();
	echo "<option value='0'>--Selecciona una opción--</option>";
	foreach($result as $opcion){
		echo "<option value='".$opcion[0]."'>".utf8_encode($opcion[1])."</option>";
	}
}
else if($caso == 4){
	$sql="SELECT id_cliente,CONCAT(ad_clientes.nombre,' ',IFNULL(ad_clientes.apellido_paterno,''),' ',IFNULL(apellido_materno,'')) as nombre
		FROM ad_clientes 
		LEFT JOIN ad_sucursales ON ad_clientes.id_sucursal=ad_sucursales.id_sucursal 
		WHERE ad_sucursales.id_sucursal=".$id;
	$datos = new consultarTabla($sql);
	$result = $datos -> obtenerArregloRegistros();
	echo "<option value='0'>--Selecciona una opción--</option>";
	foreach($result as $opcion){
		echo "<option value='".$opcion[0]."'>".utf8_encode($opcion[1])."</option>";
	}
}
else if($caso == 5){
	$sql="SELECT ad_clientes.clave 
	FROM ad_clientes 
	WHERE id_cliente=".$id;
	$datos = new consultarTabla($sql);
	$result = $datos -> obtenerArregloRegistros();
	foreach($result as $opcion){
		echo $opcion[0];
	}
}
?>