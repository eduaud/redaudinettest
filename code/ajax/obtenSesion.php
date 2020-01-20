<?php	
include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$caso = $_POST["caso"];

if($caso == 1)
		echo $_SESSION["USR"]->sucursalid;
else if($caso == 2)
		echo $_SESSION["USR"]->userid;

?>