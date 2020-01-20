<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$idSubgasto = $_POST['idSubgasto'];

$sql = "SELECT aplica_calculo_iva FROM na_conceptos_subgastos WHERE id_subgasto = " . $idSubgasto;
$result = new consultarTabla($sql);
$datos = $result -> obtenerLineaRegistro();

echo $datos['aplica_calculo_iva'];

?>