<?php

extract($_GET);
extract($_POST);

include("../../../conect.php");
include("../../../code/general/funciones.php");


$aDetalleCuentaMayor = obtenerPropiedadesCuentaMayorHeredar($id_cuenta_mayor);

echo json_encode($aDetalleCuentaMayor);
?>