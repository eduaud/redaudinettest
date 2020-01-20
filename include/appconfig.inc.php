<?php
// URL to current script folder
$loginurl = ROOTURL . "index.php";

//actualiza la sesion
session_name($session_name);
session_start();

// path to templates and configs (smarty)
$smarty_tmplc = ROOTPATH . "/templates_c";
$conn = connect_database($session_long);


//si decidieron salir del sistema
if(isset($_GET["view"]) && $_GET["view"]=="logout"){

    //    Grabamos en bit&aacute;cora el t&eacute;rmino de sesi&oacute;n
//	grabaBitacora('2','','0','0',$_SESSION["USR"]->userid,'0','','');
	
    $_SESSION = array();
    $_SESSION["USR"]= new USR();
	session_destroy();
	
	//borramos las sesiones de este usuario logeado
}

//cada vez que entra aqui conecta a la base de datos
$_SESSION["sesion_unica"] = session_id();
$smarty->assign("SESSION", SID);
$smarty->assign("SESSIONID", session_id());
$smarty->assign("SESSIONNAME", session_name());
// always assign rooturl and rootpath
$smarty->assign("rooturl", ROOTURL);
$smarty->assign("rootpath", ROOTPATH);


if(isset($_SESSION["USR"]->username)){
     $smarty->assign("username", $_SESSION["USR"]->username);
	 //echo '--------    user name ----------- '.$_SESSION["USR"]->username;
	 }
	
if(isset($_SESSION["USR"]->fullusername)){
     $smarty->assign("userfullname", $_SESSION["USR"]->fullusername);
 	// echo '--------    user name fulll  ----------- '.$_SESSION["USR"]->fullusername;
}
?>
