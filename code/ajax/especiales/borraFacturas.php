<?php
	php_track_vars;

	extract($_GET);
	extract($_POST);

//CONECCION Y PERMISOS A LA BASE DE DATOS
 	include("../../../conect.php");
	include("../../../code/general/funciones.php");

if($opcion==1){

$facCero=array();
$strConsulta="SELECT id_control_factura FROM anderp_facturas WHERE id_factura = '0' AND  id_sucursal= ".$_SESSION["USR"]->sucursalid;
	//echo $strConsulta;

	$res=mysql_query($strConsulta) or die("Error en:\n$strConsulta\n\nDescripcion:".mysql_error());
	$num=mysql_num_rows($res);
	
	if($num>0){
		//echo "exito|".$num;
		for($i=0;$i<$num;$i++)
		{
			$row=mysql_fetch_row($res);
			$facCero[count($facCero)] = $row[0];
		  $sql2 = "DELETE FROM anderp_facturas WHERE id_control_factura = ".$row[0]." AND  id_sucursal= ".$_SESSION["USR"]->sucursalid;
		  $sql3 = "DELETE FROM anderp_facturas_detalles_parcial WHERE id_control_factura = ".$row[0];
		  //echo $sql2."\n".$sql3;
		  mysql_query($sql2) or die("Error en:\n$sql2\n\nDescripcion:".mysql_error());
		  mysql_query($sql3) or die("Error en:\n$sql3\n\nDescripcion:".mysql_error());
		}
		//echo count($facCero);
		//$cadeAux = implode(",",$facCero);
		//echo "cADENA ".$cadeAux;
		//$sql2 = "DELETE FROM anderp_facturas WHERE id_factura = 0 AND  id_sucursal= ".$_SESSION["USR"]->sucursalid;
		//mysql_query($sql2) or die("Error en:\n$sql2\n\nDescripción:".mysql_error());
		echo "exito";
	}
	else{
	   echo "exito";
	}
	
}//fin accion 1


	
?>