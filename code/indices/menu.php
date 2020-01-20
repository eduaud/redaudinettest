<?PHP
	php_track_vars;
	
	extract($_GET);
	extract($_POST);

	include("../../conect.php");	
	include_once("../clases/conexion.php");
	include("../general/funcionesAudinet.php");
	
	
		//op es la opcion de las tablas
		//ta tabla a la que afecta
			
		$opcion = base64_decode($op);
		$tabla = base64_decode($ta);
		$t = $tabla;
		
		
			
		//vemos a que grupos pertenece el usuario tambien vemos los permisos a los que tiene acceso
		if($esAdmin==0)
		{
			if($nivelPermiso=='usuario')
			{
		
				$strSQL="SELECT id_menu, 
									 IF(id_menu = 59,'Facturas Emitidas por AUDICEL',nombre) AS nombre,, 
									 tabla_asociada, 
									 cod_tabla, 
									 activo, 
									 hoja, 
									 id_menu_padre, 
									 orden, 
									 ini, 
									 class_ini, 
									 ref, 
									 class_ref, 
									 id, 
									 class_span, 
									 fin_1,
									 fin_2,
									 ul_class, 
									 fin_3,
									 gif
								FROM  sys_menus 
								WHERE id_menu in (
									SELECT DISTINCT menus_2.id_menu AS id_menu FROM sys_permisos_usuarios LEFT JOIN sys_menus ON sys_permisos_usuarios.id_menu=sys_menus.id_menu 
									LEFT JOIN sys_menus AS menus_2 ON sys_menus.id_menu_padre=menus_2.id_menu
									WHERE NOT sys_menus.id_menu=sys_menus.id_menu_padre AND id_usuario='".$_SESSION["USR"]->userid."' ". ES_ADM_SUC ." )
								AND id_menu = id_menu_padre ". ES_ADM_SUC ." 		
							ORDER BY orden";
							
						
			}
			else//permisos por grupos
			{
			
						
				$strSQL="SELECT id_menu, 
									 IF(id_menu = 59,'Facturas Emitidas por AUDICEL',nombre) AS nombre,
									 tabla_asociada, 
									 cod_tabla, 
									 activo, 
									 hoja, 
									 id_menu_padre, 
									 orden, 
									 ini, 
									 class_ini, 
									 ref, 
									 class_ref, 
									 id, 
									 class_span, 
									 fin_1,
									 fin_2,
									 ul_class, 
									 fin_3,
									 gif
								FROM  sys_menus 
								WHERE id_menu in (
									SELECT DISTINCT menus_2.id_menu AS id_menu FROM sys_permisos_grupos LEFT JOIN sys_menus ON sys_permisos_grupos.id_menu=sys_menus.id_menu 
									LEFT JOIN sys_menus AS menus_2 ON sys_menus.id_menu_padre=menus_2.id_menu
									WHERE id_grupo IN (".$strGrupos.") ". ES_ADM_SUC_R ." )
								AND id_menu = id_menu_padre ". ES_ADM_SUC ." 	
							ORDER BY orden";
			}
		}
		else//es administrador
		{
		
			$strSQL="	SELECT id_menu, nombre, tabla_asociada, cod_tabla, activo, hoja, id_menu_padre, orden, ini, class_ini, ref, class_ref, id, class_span, fin_1,fin_2,ul_class, fin_3,gif	
				FROM  sys_menus 
				WHERE id_menu in (select distinct id_menu from sys_menus  WHERE	id_menu=id_menu_padre AND activo =1	and 1 ". ES_ADM_SUC ." ) ". ES_ADM_SUC ." 
				ORDER BY orden";
		}
				
		unset($reg);
		$regmenu=array();
		//echo $strSQL;
		if(!($resourceSel = mysql_query($strSQL)))	die("Error at rowsel $rowsel::".mysql_error());
		while($rowsel= mysql_fetch_row($resourceSel)){
			array_push($regmenu,$rowsel);					
		}
	
	 /*$nomTitSucursal="XOCHIMILCO";	
	 $smarty->assign("VER_SUCURSAL","1");
	 $smarty->assign("nomTitSucursal",$nomTitSucursal);*/
	 
	 
// ***   Actualiza el estatus de los mensajes caducados   *** //
$query_mensajes_finalizados = "
	SELECT id_mensaje FROM cl_mensajes
	WHERE activo = 1 AND estatus = 1 AND fecha_fin < '".date("Y-m-d")."'
";
$result_mensajes_finalizados = mysql_query($query_mensajes_finalizados);

while($datos_mensajes_finalizados = mysql_fetch_array($result_mensajes_finalizados)){
	$query_actualiza_estatus = "
		UPDATE cl_mensajes SET estatus = 0 WHERE id_mensaje = ".$datos_mensajes_finalizados['id_mensaje']."
	";
	$result_actualiza_estatus = mysql_query($query_actualiza_estatus);
}
// ***   Termina Actualiza el estatus de los mensajes caducados   *** //


// ***   Mensajes   *** //
$arr_mensajes = array();
$query_mensajes = "
	SELECT mensaje,fecha_inicio,fecha_fin,id_plaza,id_tipo_cliente,id_cliente,id_tipo_usuario,id_usuario FROM cl_mensajes
	WHERE activo = 1 AND estatus = 1
";
$result_mensajes = mysql_query($query_mensajes);
$num_mensajes = mysql_num_rows($result_mensajes);

if($num_mensajes > 0){
	while($datos_mensajes = mysql_fetch_array($result_mensajes)){
		if(strtotime(date("Y-m-d")) >= strtotime($datos_mensajes["fecha_inicio"]) && strtotime(date("Y-m-d")) <= strtotime($datos_mensajes["fecha_fin"])){
			$en_plaza = "no"; $en_tipo_cliente = "no"; $en_cliente = "no";
			
			if($datos_mensajes["id_plaza"] == "0" || $datos_mensajes["id_plaza"] == ""){
				$en_plaza = "si";
			} else {
				$arr_plazas = explode(",",$datos_mensajes["id_plaza"]);
				if(in_array($_SESSION["USR"]->sucursalid,$arr_plazas)){
					$en_plaza = "si";
				}
			}
			
			if($datos_mensajes["id_tipo_cliente"] == "0" || $datos_mensajes["id_tipo_cliente"] == ""){
				if($datos_mensajes["id_tipo_usuario"] == "0" || $datos_mensajes["id_tipo_usuario"] == ""){
					$en_tipo_cliente = "si";
				} elseif($datos_mensajes["id_cliente"] != "0" && $datos_mensajes["id_cliente"] != "") {
					$en_tipo_cliente = "si";
				}
			} else {
				$arr_tipos_cliente = explode(",",$datos_mensajes["id_tipo_cliente"]);
				if(in_array($_SESSION["USR"]->id_tipo_cliente_proveedor,$arr_tipos_cliente)){
					$en_tipo_cliente = "si";
				}
			}
			
			if($datos_mensajes["id_cliente"] == "0" || $datos_mensajes["id_cliente"] == ""){
				if($datos_mensajes["id_usuario"] == "0" || $datos_mensajes["id_usuario"] == ""){
					$en_cliente = "si";
				} elseif($datos_mensajes["id_tipo_cliente"] != "0" && $datos_mensajes["id_tipo_cliente"] != "") {
					$en_cliente = "si";
				}
			} else {
				$arr_clientes = explode(",",$datos_mensajes["id_cliente"]);
				if(in_array($_SESSION["USR"]->cliente_principal_id,$arr_clientes)){
					$en_cliente = "si";
				}
			}
			//echo $en_plaza . " -- " . $en_tipo_cliente . " -- " . $en_cliente . "<br>";
			if($en_plaza == "si" && $en_tipo_cliente == "si" && $en_cliente == "si"){
				array_push($arr_mensajes, array(nl2br($datos_mensajes["mensaje"]), cambiarFormatoFecha($datos_mensajes["fecha_inicio"], "dmy", "/")));
			}
		}
	}
}

$smarty->assign("num_mensajes",$num_mensajes);
$smarty->assign("arr_mensajes",$arr_mensajes);
// ***   Termina Mensajes   *** //
		
		$smarty->assign("regmenu",$regmenu);	
		$smarty->display("indices/menu.tpl");
	
?>