<?php
/* $Id: userfunc.inc.php,v 1.96 2004/12/13 14:19:50 k-fish Exp $ */
/* User-Functions for Moregroupware */

if (!defined('ROOTPATH')) die("This is a library.");

// ===================
// conexiones a la base de datos
// ===================
function connect_database($session_time){
    global $dbvendor,$dbhost, $dbuser, $dbpassword, $dbname;
	$conn=@mysql_connect($dbhost,$dbuser,$dbpassword);
	@mysql_select_db($dbname,$conn);
	
	/*Parche feo estilo Microsoft - S&oacute;lo para el caso Novalaser Atte: RedCat*/
	/*$sql="SET SESSION time_zone = '-6:00'";
    mysql_query($sql);*/
	/*Fin*/
	//$sess=500;
	//echo "Dato: $session_long";

	if (!$conn) 
	   	die('Fallo al conectarse al servidor de base de datos. Error encontrado: ' . mysql_error());
    if(isset($_SESSION["tiempo_sesion"]))
    {
    	if(($_SESSION['tiempo_sesion']+$session_time) < time())
    	{
    		session_unset();
    		session_destroy();
    	}
    	else
    		$_SESSION["tiempo_sesion"]=time();
    }
    else
    	$_SESSION["tiempo_sesion"]=time();	
	return $conn;
}

//======================
//Funcion que obtiene la primer fila de un query dado
//======================
function obtenFilaQuery($query)
{
	$resultado=mysql_query($query);
	$row=array();
	if(mysql_num_rows($resultado)>0)
	{
		$row=mysql_fetch_array($resultado);
	}
	return $row;
}

// ===================
// traemos informacion del usuario get_user_info
// ===================
function get_user_info($user){
    global $conn, $appconf;

    // now we just look for the username and not for the password anymore.
	$sql = "SELECT * FROM sys_usuarios WHERE id_usuario=".(int)$user;
    $row=obtenFilaQuery($sql);	
    return $row;
}

function browser_detect() {
  $agent = "@".strtolower($_SERVER["HTTP_USER_AGENT"]);
  if (strpos($agent,"opera")) return "opera";
  if (strpos($agent,"konqueror")) return "konqueror";
  if (strpos($agent,"msie")) return "msie";
  if (strpos($agent,"mozilla/5")) return "mozilla";
  if (strpos($agent,"netscape6")) return "netscape6";
  if (strpos($agent,"mozilla")) return "netscape";
  if (strpos($agent,"lynx")) return "lynx";
  if (strpos($agent,"w3m")) return "w3m";
  return "";
}

//------------------------------------------------------------
//------------------------------------------------------------
//funcion particulares para los permisos

//obtenemos los grupos a los que pertenece el usuario
//cambia dependiendo de proyecto porque esta busca en una tabla  de detalle
function strObtenGruposMenu() 
{
  	global $conn;
  	$groups = array();
  	$sql = "SELECT id_detalle, id_usuario, id_grupo  FROM sys_usuarios_grupos WHERE id_usuario=".$_SESSION["USR"]->userid;
	$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:".mysql_error());
    while($row = mysql_fetch_assoc($res)) {
      $groups[] = $row["id_grupo"];
    }
    print_r($row);
    mysql_free_result($res);
 
 	$strGrupos=implode(',',$groups);	
  	return $strGrupos;
}
//submenus-> grupos
//obtiene los submenus a los que puede entrar como grupo
function strObtenSubmenusGrupos($strGrupos)
{
	global $conn;
  	$menus = array();
  

	$sql="SELECT
	      sm.id_menu as id_menu,
		  '' as id_permiso,
		  '' as id_grupo
		  FROM `sys_menus` sm
		  WHERE activo=1
		  AND
		  (
		    (SELECT 1 FROM `sys_permisos_grupos` WHERE id_menu=sm.id_menu AND id_grupo IN(".$strGrupos.") LIMIT 1) IS NOT NULL
			OR
			(SELECT 1 FROM `sys_permisos_grupos` g JOIN sys_menus m ON g.id_menu = m.id_menu  WHERE m.id_menu_padre=sm.id_menu AND id_grupo IN(".$strGrupos.") ". ES_ADM_SUC ." LIMIT 1) IS NOT NULL
  		  ) ". ES_ADM_SUC ." ";	  
 	$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:".mysql_error());
    while($row = mysql_fetch_assoc($res)) {
     $menus[] = $row["id_menu"];
    }
    mysql_free_result($res);
	$strMenus=implode(',',$menus);	
  	return $strMenus;
}

//submenu->Usuario
//obtiene los submenus a los que puede entrar como usuario
function strObtenSubmenusUsuarios($strUsuarios)
{
	global $conn;
  	$menus = array();
  	$strMenus="";
		
	$sql="SELECT
	      sm.id_menu,
		  '',
		  ''
		  FROM `sys_menus` sm
		  WHERE activo=1
		  AND
		  (
		    (SELECT 1 FROM `sys_permisos_usuarios` WHERE id_menu=sm.id_menu AND id_usuario IN(".$strUsuarios.") LIMIT 1) IS NOT NULL
			OR
			(SELECT 1 FROM `sys_permisos_usuarios` g JOIN sys_menus m ON g.id_menu = m.id_menu  WHERE m.id_menu_padre=sm.id_menu AND id_usuario IN(".$strUsuarios.") ". ES_ADM_SUC ."  LIMIT 1) IS NOT NULL
		  ) ". ES_ADM_SUC ." ";	
 	$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:".mysql_error());
    while($row = mysql_fetch_assoc($res)) {
      $menus[] = $row["id_menu"];
    }
    mysql_free_result($res);   
	$strMenus=implode(',',$menus);	
	//echo '**'.$strMenus.'---<br>';
  	return $strMenus;
}

function obtenMenusdeSub($strSubmenus)
{
	
	global $conn;
  	$menus = array();
	$strMenus="";
	
	$sql = "SELECT distinct id_menu_padre FROM sys_menus where  (activo=1 or activo=2) and id_menu in  (".$strSubmenus.")  ". ES_ADM_SUC ." " ;
	$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:".mysql_error());
    while($row = mysql_fetch_assoc($res)) {
      $menus[] = $row["id_menu_padre"];
    }
    mysql_free_result($res);
	$strMenus=implode(',',$menus);	
	//echo '**'.$strMenus.'---<br>';
  	return $strMenus;
}

//-------------------------
//funcion exclusiva si el usuario pertenece al grupo de administracion
function obtenEsAdmin()
{
	global $conn;
	$groups = array();
  
	$sql = "SELECT id_detalle, id_usuario, id_grupo  FROM sys_usuarios_grupos WHERE id_grupo = 1 and id_usuario=".$_SESSION["USR"]->userid;
	$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:".mysql_error());
    while($row = mysql_fetch_assoc($res)) {
      $groups[] = $row["id_grupo"];
    }
    mysql_free_result($res);
 
 	$strGrupos  =implode(',',$groups);	

	if ($strGrupos=='')
  		return 0;
	else
		return 1;
}

//funcion para obtener los grupos a los que pertenece un usuario
function obtenerGruposUsuario() {
  global $conn;

  $groups = array();

  if (isset($_SESSION["USR"]->userid) && $_SESSION["USR"]->userid and !count($_SESSION["USR"]->groups)>0) {
    $sql = "SELECT id_detalle, id_usuario, id_grupo  FROM sys_usuarios_grupos WHERE id_usuario=".$_SESSION["USR"]->userid;
    $res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:".mysql_error());
    while($row = mysql_fetch_assoc($res)) {
      $groups[] = $row["id_grupo"];
    }
    mysql_free_result($res);
  }
  return $groups;
}

//funcion integrada en todas las pantallas 
function grabaBitacora($id_accion,$tabla,$id_campo,$id_relacionado,$id_usuario,$id_menu,$otro,$observaciones)
{
	global $conn;

	$strSQL="INSERT INTO sys_bitacora (id_bitacora, id_accion, tabla, id_campo,id_relacionado, id_usuario, fecha, hora, id_menu, otro, observaciones) VALUES (NULL, '".$id_accion."', '".$tabla."', '".$id_campo."','".$id_relacionado."', '".$id_usuario."',CURDATE(), CURTIME(), '".$id_menu."', '".$otro."', '".$observaciones."')";	
	$res=mysql_query($strSQL) or die("Error en:\n$strSQL\n\nDescripcion:".mysql_error());
	//falta provocar el error si no se encuntra la tabla en la base de datos
}

function get_name_sucursal($id_sucursal){
    global $conn, $appconf;

    // now we just look for the username and not for the password anymore.
    //if(is_int($user))
	$sql = "SELECT id_sucursal,nombre FROM ad_sucursales WHERE id_sucursal=".(int)$id_sucursal;
   
  /* echo $sql;
   die();*/
   
   $res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:".mysql_error());
  $row = mysql_fetch_assoc($res);
      $nombre = $row["nombre"];
   
    mysql_free_result($res);
	
	
    return $nombre;
}

function get_prefijo_sucursal($id_sucursal){
    global $conn, $appconf;

    // now we just look for the username and not for the password anymore.
    //if(is_int($user))
	$sql = "SELECT id_sucursal,prefijo FROM sys_sucursales WHERE id_sucursal=".(int)$id_sucursal;
   
   $res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:".mysql_error());
  $row = mysql_fetch_assoc($res);
      $prefijo = $row["prefijo"];
   
    mysql_free_result($res);
	
	
    return $prefijo;
}


function obtenClientesRelacionados($cliente_principal_id)
{
	global $conn;
	$clientes = array();
	
	//BUSCAMOS LA CLAVE DEL CLIENTE
	
	
	//OBTENEMOS TODOS LOS CLIENTES REGISTRADOS QUE TENGAN INFORMACION CON LA CLAVE REGISTRADA
  
	$sql = "SELECT todos_clientes.id_cliente as  id_clientes_con_clave FROM ad_clientes as todos_clientes
				left join ad_clientes as cliente_principal on todos_clientes.clave=cliente_principal.clave
				where cliente_principal.id_cliente =".$cliente_principal_id." and todos_clientes.id_tipo_cliente_proveedor in	(SELECT id_tipo_cliente_proveedor FROM cl_tipos_cliente_proveedor where asignar_usuario=1)";

	$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:".mysql_error());
    while($row = mysql_fetch_assoc($res)) {
      $clientes[] = $row["id_clientes_con_clave"];
    }
    mysql_free_result($res);
 	$strClientes  =implode(',',$clientes);	
    return $strClientes;

}


?>
