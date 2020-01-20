<?php
if (!defined('ROOTPATH')) die("This is a library.");


//levantamos las variables smarty
require_once(SMARTY_DIR . "Smarty.class.php");
$smarty = new Smarty;

// array for tracking file revision number used in sql error report screen
$Revisions_array = Array();

// important functions like connect to database
include(INCLUDEPATH . 'userfunc.inc.php');
include(INCLUDEPATH . 'usr.class.php');

// Auto-Assigns for template engine and global definitions
include(INCLUDEPATH . 'appconfig.inc.php');


$smarty->assign("login",'ENTRAR');

?>
