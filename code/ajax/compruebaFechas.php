<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$fecha_inicial = $_POST['fecha_inicial'];
$fecha_inicial = str_replace("/", "-", $fecha_inicial);

$fecha_final = $_POST['fecha_final'];
$fecha_final = str_replace("/", "-", $fecha_final);

function check_in_range($start_date, $end_date, $evaluame) {
    $start_ts = strtotime($start_date);
    $end_ts = strtotime($end_date);
    $user_ts = strtotime($evaluame);
    return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
}

$fecha_final = date("d-m-Y", strtotime(date("m/d/Y")." +1 month"));

$start_date = date("d-m-Y");
$end_date = $fecha_final;

if (check_in_range($start_date, $fecha_final, $fecha_inicial)) {
    echo "1";
} else {
    echo "0";
}

?>