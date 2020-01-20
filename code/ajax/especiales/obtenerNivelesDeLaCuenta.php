<?php

extract($_GET);
extract($_POST);

include("../../../conect.php");
include("../../../code/general/funciones.php");


$nivelesDeLaCuenta = obtenerNivelesDeLaCuenta($id_cuenta_mayor);

echo $nivelesDeLaCuenta;
?>