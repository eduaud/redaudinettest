<?php

include("../../conect.php");

//$familia = $_POST['familia'];
$tipo = $_POST['tipo'];
$modelo = $_POST['modelo'];
$caract = $_POST['caract'];



$sql = "SELECT nombre, siglas FROM na_tipos_productos WHERE id_tipo_producto = $tipo";
$result = mysql_query($sql) or die("Error en \n$sql\n\nDescripcion:\n".mysql_error());
$datos = mysql_fetch_row($result);

$siglaT = $datos[0];
$nomT = $datos[1];

$sql = "SELECT nombre, siglas FROM na_modelos_productos WHERE id_modelo_producto = $modelo";
$result = mysql_query($sql) or die("Error en \n$sql\n\nDescripcion:\n".mysql_error());
$datos = mysql_fetch_row($result);

$siglaM = $datos[0];
$nomM = $datos[1];

$sql = "SELECT nombre, siglas FROM na_caracteristicas_productos WHERE id_caracteristica_producto = $caract";
$result = mysql_query($sql) or die("Error en \n$sql\n\nDescripcion:\n".mysql_error());
$datos = mysql_fetch_row($result);

$siglaC = $datos[0];
$nomC = $datos[1];

//PARA EL NOMBRE
$siglas = $siglaT . " " . $siglaM . " " . $siglaC;
//PARA LAS SIGLAS
$nombres = $nomT . "-" . $nomM . "-" . $nomC;

$nombreSiglas = $siglas . "|" . $nombres;

echo $nombreSiglas;

?>