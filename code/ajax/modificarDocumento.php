<?php
	php_track_vars;
	extract($_GET);
	extract($_POST);
	
//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../conect.php");
	include("../../code/general/funciones.php");
	//id_suc
	switch($opcion){
		case 1://CONSULTA PARA LA CANCELACION DE LA FACTURA
			
			//1
				//BUSCAMOS QUE LA FACTURA NO ESTE CANCELADA
			$strSQL="SELECT no_modificable FROM na_movimientos_almacen where id_control_movimiento ='".$id_documento."'";
			$arrResultados=valBuscador($strSQL);
			
				
			//si esta cancelada- > enviamos mensaje de que ya fue cancelada con atrio
			if($arrResultados[0] == 1)
			{
				$msjcan="El movimiento : ".$arrResultados[0]." no puede ser modificado.";
				echo utf8_encode("error|".$msjcan);	
				die();
			}
			else
			{
				echo utf8_encode("exito|".$msjcan);	
				die();
			
			}
				
				
			
		
		break;	
			  		
	};

?>