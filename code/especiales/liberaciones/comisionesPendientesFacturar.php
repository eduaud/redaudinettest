<?php
include("../../../conect.php");
include("../../general/funciones.php");
include("../../../consultaBase.php");

//die('<p>En mantenimiento...</p>');
$smarty -> display("especiales/liberaciones/comisionesPendientesFacturar.tpl");
?>