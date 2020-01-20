<?php
	
	php_track_vars;
	/*
	include('config.inc.php');
	//variables declaracion
	include(INCLUDEPATH."container.inc.php");
	include(INCLUDEPATH."module_exec.inc.php");
	
	*/
	
	include("../conect.php");
	
	extract($_GET);
	extract($_POST);
	
		
	//
	unset($registrossubmenu);
	$registrossubmenu=array();

	
	//validamos si tiene acceso este ususario a esta pantalla
	//----------------------
	if($buscar<>'')
	{
	
	}
	else
	{
			
			//obtenemos todos los usuarios dados de alta , colocar una excepcion de los activos
			
			//tenemos la variable SM
			//traemos del menu el nombre que le dio al sm
			$strSQL="SELECT id_usuario,CONCAT(apellidos,' ',apellido_paterno,' ', apellido_materno) FROM `sys_usuarios` order by CONCAT(apellidos,' ',nombres) ";
			$htmlA=array();
			$htmlA= retornaListaIdsNombres($strSQL);					
			
			$smarty->assign("usuarios",$htmlA);
			
			
			$strSQL="SELECT id_accion, nombre FROM `sys_acciones` ";
			$htmlB=array();
			$htmlB= retornaListaIdsNombres($strSQL);					
			$smarty->assign("operaciones",$htmlB);
			
			
			
				
	}
	//si no traemos parametros 
	
		

	
	$smarty->display("bitacora.tpl");

//funciones especificas
function retornaListaIdsNombres($strSQL)
{
	$htmltable = array();
	unset($ids);
	unset($names);		
	$ids = array();
	$names = array();
			
	//echo "$strSQL<br>";
	
	if(!($resource0 = mysql_query($strSQL)))	die("Error at retornaListaIdsNombres 1 $strSQL::".mysql_error());
	while($row0 = mysql_fetch_row($resource0))
	{
		array_push($ids,$row0[0]);
		array_push($names,$row0[1]);
	}
	
	mysql_free_result($resource0);
	$htmltable[0] =$ids;
	$htmltable[1] =$names;
	return  $htmltable;
}
?>