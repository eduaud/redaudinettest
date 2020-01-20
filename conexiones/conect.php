<?PHP
	//rutas abajo
	//header("Content-Type: text/html;charset=ISO-8859-1");
	//s&w
	$ruta = /*'/var/www/html/'*/dirname(__FILE__)."/";
	//arriba
	//$ruta = '/var/www/vhosts/sysandweb.com/httpdocs/sias/';	
	include($ruta.'config.inc.php');
		
	//variables declaracion
	include(INCLUDEPATH."container.inc.php");
	include(INCLUDEPATH."module_exec.inc.php");	
	//mysql_query("SET time_zone = '+00:42'");
	
	
	/**********Mandamos datos basicos del usuario************/
	$smarty->assign("SUCURSAL_USR", $_SESSION["USR"]->id_sucursal);
	$smarty->assign("NAME_USR", $_SESSION["USR"]->username);
	$smarty->assign("ID_USR", $_SESSION["USR"]->userid);	
	
	
	
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