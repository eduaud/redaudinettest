<?php
session_cache_limiter('none');
//verificamos si esta abierta la session en la base de datos 

if (!isset($_SESSION['USR']))
{

	if($_SESSION["USR"]->username =='')
	{
		include("../general/funciones.php");
		
		/*$strConsulta="SELECT id_sucursal, nombre FROM novalaser_sucursales WHERE activo='1'";
		$comboSucursales=retornaListaIdsNombres($strConsulta);
		$smarty->assign("comboSucursales",$comboSucursales);
		
		
		$sql="SELECT mostrar_sucursal FROM `sys_parametros_configuracion` WHERE activo=1";
		$res=mysql_query($sql) or die("Error en:<br>$sql<br><br>Descripcion:<br>".mysql_error());	
		
		if(mysql_num_rows($res) > 0)
		{
			$row=mysql_fetch_row($res);
			$smarty->assign("VER_SUCURSAL", $row[0]);			
		}*/
		
		$smarty->display("login.tpl");
		exit();		
	}
}

//**********************************************
$esAdmin=obtenEsAdmin();

$strWhereMenu='';
$strWhereSubmenu='';
//0 no es admin
//1 es admin

//nos define si encontramos permisos a nivel usuario o grupo
$nivelPermiso='usuario';

//grupos a los que pertenece el usuario
$strGrupos='';


if($esAdmin==0)
{
	//traemos los grupos a los que pertenece
	$strGrupos=strObtenGruposMenu();
	
	//obtenemos los submenus por usuario
	$submenusUsuario=strObtenSubmenusUsuarios($_SESSION["USR"]->userid);
	//echo $submenusUsuario;
	//si esta vacio vemos los permisos que tiene
	if($submenusUsuario=='')
	{
		//cambiamos el nivel al tipo de permiso
		$nivelPermiso='grupo';
		//traemos los sumenus por grupo al que pertenece
		
		$submenusGrupo=strObtenSubmenusGrupos($strGrupos);
		
		$strSubmenusPermitidos=$submenusGrupo;
		
		if($submenusGrupo=='')
			$submenusGrupo=0;
		
		//obtenemos los menus de los submenus
		$strMenusPermitidos=obtenMenusdeSub($submenusGrupo);	
	}
	else
	{
		//obtenemso los menus a los que pertenece		
		$strSubmenusPermitidos=$submenusUsuario;
		if($submenusUsuario=='')
			$submenusUsuario=0;
		
		$strMenusPermitidos=obtenMenusdeSub($submenusUsuario);	
		
	}
	
	if($strMenusPermitidos=='')
		$strMenusPermitidos="0";

	if($strSubmenusPermitidos=='')
		$strSubmenusPermitidos="0";
		
	$strWhereMenu=" AND id_menu  in (".$strMenusPermitidos.")";
	$strWhereSubmenu="AND id_menu  in (".$strSubmenusPermitidos.")";
		
	
}
//**********************************************

//esta variable ya contiene todos los menus a los que puede accesar el usuario 
//menos para admin

//echo $strMenusPermitidos.'<< <br>';

//vamos obteniendo la informacion del menu
//obtenemos todos aquellos que sin menu raiz
// y que sean distintos
//tenemos los ids de los grupos a los que pertenece ,g1,g5,
//tenemos los el id del usuario  ,1,

$strSQL1="	SELECT id_menu, nombre, tabla_asociada, cod_tabla, activo, hoja, id_menu_padre, orden, ini, class_ini, ref, class_ref, id, class_span, fin_1,fin_2,ul_class, fin_3,gif
			FROM  sys_menus 
			WHERE id_menu in (
			select distinct id_menu from sys_menus  
			WHERE	id_menu=id_menu_padre AND (activo =1 	". ES_ADM_SUC ." )	
			".$strWhereMenu."	
			) ". ES_ADM_SUC ." 
			ORDER BY orden";
			
//necesitamos colocar el acceso

//echo $strSQL1;
if(!($resource1 = mysql_query($strSQL1)))	die("Error at strSQL1 $strSQL1 ::".mysql_error());

unset($registrosmenu);
$registrosmenu=array();


//array_push($registros,$rowDet);
//$smarty->assign("registros",$registros);

if(mysql_num_rows($resource1)<=0)
{
	
}

while($row1= mysql_fetch_row($resource1))
{
		
		
		
		//buscamos todos aquellos donde este sea padre 11 y tenga tambas asociadas
		$strSQL2="	SELECT id_menu, nombre, tabla_asociada, cod_tabla, activo, hoja, id_menu_padre, orden, ini, class_ini, ref, class_ref, id, class_span, fin_1, fin_2,ul_class, fin_3
			FROM  sys_menus 
			WHERE id_menu in 
			(select distinct id_menu from sys_menus  WHERE	(id_menu_padre='".$row1[0]."' or menu_asociado like ',".$row1[0].",') and id_menu <> id_menu_padre AND (activo =1 	)	".$strWhereSubmenu." )
			ORDER BY orden"	;
			
		//echo 	$strSQL2;
			
		if(!($resource2 = mysql_query($strSQL2)))	die("Error at strSQL2 22 $strSQL2 ::".mysql_error());
		//si esta menu tiene submenus
		//armamos el menu 1 con terminacion que incique que iniciar&aacute; una lista debajo de el
		//N&uacute;mero total de hijos que tiene el menu 2
		$totalhijosMenu2=mysql_num_rows($resource2);
		
		if($totalhijosMenu2>0)
		{	//ini
			$row1[8]="li"; $row1[9]="top";	$row1[12]=$row1[1];	$row1[11]="top_link";$row1[13]="down";	
			array_push($registrosmenu,$row1);
			array_push($registrosmenu,array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,'sub','ul'));
			
			//numero de menus que tiene abajo de el para cerrar con un 
		}
		else
		{
			$row1[8]="li";	$row1[9]="top";	$row1[11]="top_link"; $row1[14]="f_li";	
			array_push($registrosmenu,$row1);
		}
		
		//-----------------------------------------------------------------------------------------------------------
		//1111 1 1 1 1 1 1 1 1 1 1 1 1 1 1 1 1 1 1 1 1 1 1 1 1 11 1 1 1 1 1 1 1 1 1 1 1 1 1 11 1 1 1 1 1 11 1
		//MENU 1 1 1 11 1 1 1 11 1 11 1 
		//registros del primer menu
		
		//-----------------------------------------------------------------------------------------------------------
		
		$hijoMenu2="0";
		while($row2= mysql_fetch_row($resource2))
		{       
			$hijoMenu2++;
			$strSQL3="SELECT	id_menu, nombre, tabla_asociada, cod_tabla, activo, hoja, id_menu_padre, orden, ini, class_ini, ref, class_ref, id, class_span, fin_1, fin_2,ul_class, fin_3
			FROM  sys_menus 
			WHERE	id_menu_padre='".$row2[0]."' AND (activo =1)		and 1 	".$strWhereSubmenu." ". ES_ADM_SUC ." 
			ORDER BY orden";
			if(!($resource3= mysql_query($strSQL3)))	die("Error at strSQL3 $strSQL3 ::".mysql_error());
			
			$totalhijosMenu3=mysql_num_rows($resource3);
			if($totalhijosMenu3>0)
			{
				$row2[8]="li"; $row2[9]="top";	$row2[12]=$row2[1];	$row2[11]="fly";$row2[13]="";
				array_push($registrosmenu,$row2);
				array_push($registrosmenu,array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,'','ul'));
			}
			else
			{
				$row2[8]="li";	$row2[9]="";	$row2[11]=""; $row2[14]="f_li";	
				array_push($registrosmenu,$row2);
			}
					
			$inthijo3=0;
			while($row3= mysql_fetch_row($resource3))
			{
				$inthijo3 ++;
				//             0        1			2			3			4    5	      6           7      8       9       10    11       12    13         14     15
				$strSQL4="SELECT	id_menu, nombre, tabla_asociada, cod_tabla, activo, hoja, id_menu_padre, orden, ini, class_ini, ref, class_ref, id, class_span, fin_1, fin_2,ul_class, fin_3
				FROM  sys_menus 
				WHERE	id_menu_padre='".$row3[0]."' AND (activo =1 )		and 1	". ES_ADM_SUC ."  ".$strWhereSubmenu."
				ORDER BY orden";
				if(!($resource4= mysql_query($strSQL4)))	die("Error at strSQL4 $strSQL4 ::".mysql_error());
				
				$totalhijosMenu4=mysql_num_rows($resource4);
				
				if($totalhijosMenu4>0)
				{
					//los valores de row1 colocamos lo valores
					//<li class="top"><a href="#nogo2" id="products" class="top_link"><span class="down">Products</span></a>
					//ini
					$row3[8]="li"; $row3[9]="top"; $row3[12]=$row3[1]; $row3[11]="fly"; $row3[13]="";	
					array_push($registrosmenu,$row3);
					array_push($registrosmenu,array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,'mid','ul'));
					
				}
				else
				{
					
					$row3[8]="li"; $row3[9]="";	$row3[12]=$row1[1];	$row3[11]=""; $row3[13]="";	$row3[14]="f_li";	
					array_push($registrosmenu,$row3);			
				}
				
				$totalhijosMenu4=mysql_num_rows($resource4);
				$inthijo4=0;
				
				while($row4= mysql_fetch_row($resource4))
				{
					$inthijo4 ++;
					//este es el ultimo nivel
					//verificamos si es el ultimo hijo 				
					$row4[8]="li";	$row4[9]="";	$row4[11]=""; $row4[14]="f_li";	
					array_push($registrosmenu,$row4);
					
					//sumamos los valos a la tabla
				}
				//array_push($registrosmenu,array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,'f_ul','f_li',16,17));
				//aumentamos el numeo del hijo
				mysql_free_result($resource4);
				if($totalhijosMenu4>0)
					array_push($registrosmenu,array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,'f_ul','f_li',16,17));
		
			}
			mysql_free_result($resource3);
			if($totalhijosMenu3>0)
			array_push($registrosmenu,array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,'f_ul','f_li',16,17));
		
		//si es el ultimo valor
		}
		mysql_free_result($resource2);
		//vemos si tuvo hijos 
		//si tuvo lo cerramos
		if($totalhijosMenu2>0)
			array_push($registrosmenu,array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,'f_ul','f_li',16,17));
		
		
}
mysql_free_result($resource1);

$smarty->assign("registrosmenu",$registrosmenu);

?>
