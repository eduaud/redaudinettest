<?php
	php_track_vars;
	//include("conect.php");


	include('config.inc.php');
	
	
	session_name($session_name);
	session_start();

	$ax=$_SESSION["tiempo_sesion"];	
	
	
	$aux = time()-$ax;
	$tiempoRestante = $session_long - $aux;
	
	if($aux >= $session_long)
		die("MUERTA");
	else
		die("ACTIVA|$tiempoRestante");	
	
	

?>