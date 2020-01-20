<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

date_default_timezone_set('America/Mexico_City');

echo date('Ymdhis') . $_SESSION["USR"]->userid . $_SESSION["USR"]->sucursalid;

?>