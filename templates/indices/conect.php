<?PHP
	$ruta = /*'/var/www/html/'*/dirname(__FILE__)."/";
	
	include($ruta.'config.inc.php');
		
	//variables declaracion
	include(INCLUDEPATH."container.inc.php");
	include(INCLUDEPATH."module_exec.inc.php");	
	
	
	/**********Mandamos datos basicos del usuario************/
	$smarty->assign("SUCURSAL_USR", $_SESSION["USR"]->sucursalid);
	$smarty->assign("NAME_USR", $_SESSION["USR"]->username);
	$smarty->assign("ID_USR", $_SESSION["USR"]->userid);	
	
	$smarty->assign("sucursalname", $_SESSION["USR"]->sucursalname);
		
	/*********Mandamos configuraciones generales*************/
	
	$sql="SELECT mostrar_sucursal FROM `sys_parametros_configuracion` WHERE activo=1";
	$res=mysql_query($sql) or die("Error en:<br>$sql<br><br>Descripcion:<br>".mysql_error());	
	
	if(mysql_num_rows($res) > 0)
	{
		$row=mysql_fetch_row($res);
		$smarty->assign("VER_SUCURSAL", $row[0]);
		define("VER_SUCURSAL", $row[0]);
	}
	
	

	
?>