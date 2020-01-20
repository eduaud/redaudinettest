<?php
include("../../../../config.inc.php");
//include("../../../code/general/funciones.php");

header("Content-Type: text/html;charset=utf-8");	
//header('Content-Type: application/json');

// Conectamos con el servidor o enviamos mensaje de conexion fallida
$conexion = mysql_connect($dbhost, $dbuser, $dbpassword) or die ("no se logro la conexión");

// Consulta UTF-8 " Necesario para la correcta codificacion de acentos, ñ y caracteres especiales"
mysql_query("SET NAMES 'utf8'");

// Conectamos con le base de datos o enviamos mensaje de conexion fallida
mysql_select_db($dbname, $conexion) or die ("imposible conectar con la base de datos");



$id=$_GET['id'];

$sql="SELECT id_forma_pago,
			 nombre,
			 autorizacion_credito_cobranza, 
			 requiere_registro_terminal, 
			 requiere_numero_documento,
			 requiere_banco
		FROM na_formas_pago	
	   WHERE id_forma_pago=$id;";
							
$result = mysql_query($sql) or die(mysql_error());
$aResult=mysql_fetch_assoc($result);

echo json_encode($aResult);
//var_dump($aResult);?>