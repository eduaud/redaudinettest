<?PHP
	
	php_track_vars;
	/*
	include('config.inc.php');
	//variables declaracion
	include(INCLUDEPATH."container.inc.php");
	include(INCLUDEPATH."module_exec.inc.php");
	
	*/
	
	include("../../conect.php");
	
	extract($_GET);
	extract($_POST);
	
		
	//
	unset($registrossubmenu);
	$registrossubmenu=array();
	
	//validamos si tiene acceso este ususario a esta pantalla
	//----------------------
		
	//tenemos la variable SM
	//traemos del menu el nombre que le dio al sm
	$strSQL="SELECT nombre   FROM sys_menus where id_menu='".$sm."' ". ES_ADM_SUC ." limit 1";
	
/*	echo $strSQL;
	die();*/
	
	$res= mysql_query($strSQL) or 	die("Error at a $strSQL ::".mysql_error());
	extract(mysql_fetch_assoc($res));
	$nombre_menu=$nombre;
	
	mysql_free_result($res);
	
	if($esAdmin==0)
	{
		if($nivelPermiso=='usuario')
		{
			$strSQL2="SELECT id_menu, IF(id_menu = 59,'Facturas Emitidas por AUDICEL',nombre) AS nombre, tabla_asociada, cod_tabla, activo, hoja, id_menu_padre, orden, ini, class_ini, ref, class_ref, id, class_span, fin_1, fin_2,ul_class, fin_3
	 			FROM sys_menus 
				WHERE id_menu 
				in 
				(select distinct id_menu from sys_menus WHERE (id_menu_padre='".$sm."' or menu_asociado like ',".$sm.",') and id_menu <> id_menu_padre AND activo =1 and 1 ". ES_ADM_SUC ." ) 
				and (id_menu IN (SELECT id_menu FROM sys_permisos_usuarios WHERE id_usuario='".$_SESSION["USR"]->userid."') OR id_menu_padre IN (SELECT sys_menus.id_menu FROM sys_permisos_usuarios LEFT JOIN sys_menus ON sys_permisos_usuarios.id_menu=sys_menus.id_menu LEFT JOIN sys_menus AS menus_2 ON sys_menus.id_menu_padre=menus_2.id_menu WHERE id_usuario='".$_SESSION["USR"]->userid."' ". ES_ADM_SUC ." ) ) ". ES_ADM_SUC ." ORDER BY sys_menus.nombre";
		}
		else
		{ 
		       $strSQL2="SELECT id_menu, IF(id_menu = 59,'Facturas Emitidas por AUDICEL',nombre) AS nombre, tabla_asociada, cod_tabla, activo, hoja, id_menu_padre, orden, ini, class_ini, ref, class_ref, id, class_span, fin_1, fin_2,ul_class, fin_3
		       	 FROM sys_menus 
			 WHERE
			 id_menu  in (
			 select distinct id_menu from sys_menus WHERE 
			 (id_menu_padre='".$sm."' or menu_asociado like ',".$sm.",') and id_menu <> id_menu_padre 
			 AND activo =1 ) 
			 and (id_menu IN (SELECT id_menu FROM sys_permisos_grupos WHERE id_grupo IN (".$strGrupos.")) OR id_menu_padre IN (SELECT sys_menus.id_menu FROM sys_permisos_grupos LEFT JOIN sys_menus ON sys_permisos_grupos.id_menu=sys_menus.id_menu LEFT JOIN sys_menus AS menus_2 ON sys_menus.id_menu_padre=menus_2.id_menu WHERE id_grupo='".$id_grupo."' ". ES_ADM_SUC_R ." ) ) ". ES_ADM_SUC ."  ORDER BY sys_menus.nombre";
		}
	}
	else
	{
		$strSQL2="	SELECT id_menu, nombre, tabla_asociada, cod_tabla, activo, hoja, id_menu_padre, orden, ini, class_ini, ref, class_ref, id, class_span, fin_1, fin_2,ul_class, fin_3
			FROM  sys_menus 
			WHERE id_menu in 
			(select distinct id_menu from sys_menus  WHERE	(id_menu_padre='".$sm."' or menu_asociado like ',".$sm.",') and id_menu <> id_menu_padre AND activo =1 ". ES_ADM_SUC ."	)  ". ES_ADM_SUC ." 
			ORDER BY sys_menus.nombre";
	}
	
	
	
	if(!($resource2 = mysql_query($strSQL2)))	die("Error at strSQL2 22 $strSQL2 ::".mysql_error());
	
	while($row2= mysql_fetch_row($resource2))
	{       
		array_push($registrossubmenu,$row2);
		//print_r ($row2);
	}
	
	$smarty->assign("nombre_menu",$nombre_menu);
	$smarty->assign("registrossubmenu",$registrossubmenu);
	
	$smarty->display("indices/submenu.tpl");
	
	
?>