<?PHP
php_track_vars;

	extract($_GET);
	extract($_POST); 
	
	$ruta = /*'/var/www/html/'*/dirname(__FILE__)."/";
	
	//$ruta = "C:/xampp/htdocs/ci/";
	//echo $ruta."<br><br>";

	include($ruta.'config.inc.php');		
	include(INCLUDEPATH."container.inc.php");
	include(INCLUDEPATH."module_exec.inc.php");	
	
	
	
	/*********Mandamos configuraciones generales*************/
	$smarty->assign("sucursalname", $_SESSION["USR"]->sucursalname);
	$smarty->assign("SUCURSAL_USR", $_SESSION["USR"]->sucursalid);
	$smarty->assign("NAME_USR", $_SESSION["USR"]->username);
	$smarty->assign("ID_USR", $_SESSION["USR"]->userid);

	
?>