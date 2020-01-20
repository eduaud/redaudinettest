<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$tipoCobro = $_POST["tipoCobro"];

$sql = "SELECT autorizacion_credito_cobranza FROM na_formas_pago WHERE id_forma_pago = " . $tipoCobro;

$result = new consultarTabla($sql);
$requiere = $result -> obtenerLineaRegistro();

echo $requiere['autorizacion_credito_cobranza'];



?>