<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$rfc = $_POST['rfc'];

$sql = "SELECT rfc FROM na_proveedores WHERE rfc = '" . $rfc . "'";
$result = new consultarTabla($sql);
$contador = $result -> cuentaRegistros();

echo $contador;

?>