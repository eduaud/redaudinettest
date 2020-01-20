<?php
	php_track_vars;

	extract($_GET);
	extract($_POST);
	
//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../conect.php");
	//include("../../code/general/funciones.php");
	if($esAdmin==0)
	{
		if($nivelPermiso=='usuario')
		{
			$strSQLPer="SELECT id_menu, nombre,
							(SELECT count(id_secuencia)  FROM `sys_permisos_usuarios`  where id_permiso=7 and id_menu=aux.id_menu and  id_usuario ='".$_SESSION["USR"]->userid."' )  as generar
						FROM  sys_menus aux
						WHERE id_menu IN (41,42) ". ES_ADM_SUC ." ";
							
				}
		else
		{
			//obtenemos otro query
			
			$strSQLPer="SELECT id_menu, nombre,
							(SELECT count(id_secuencia)  FROM `sys_permisos_grupos`  where id_permiso=7 and id_menu=aux.id_menu and  id_grupo in (".$id_grupo.") )  as generar
						FROM  sys_menus aux
						WHERE id_menu IN (42,42)  ". ES_ADM_SUC ." ";
		}
		$resPer = mysql_query($strSQLPer) or die(mysql_error());
		$arrPer=array();
		while($resvariablePer=mysql_fetch_assoc($resPer))
		{
			$arrPer[$resvariablePer["id_menu"]]=$resvariablePer["generar"];				
		}		
	}
	else
	{
		$arrPer=array(41=>1,42=>1);
	}
	if($arrPer[41]==0&&$p=="1-")
		header("Location: ".ROOTURL."index.php");
	if($arrPer[42]==0&&$p=="3-")
		header("Location: ".ROOTURL."index.php");
	//para la entrada principal
	if($make=="")
	{
		if($p=='3-')
		{
		
			$strSQL1="SELECT id_usuario,concat(apellido_paterno,' ', apellido_materno,' ' ,nombres) as nombre FROM sys_usuarios where activo=1";
			
			if(!($resource1 = mysql_query($strSQL1)))	die("Error at strSQL1 $strSQL1 ::".mysql_error());
	
			unset($grupos);
			$grupos=array();
		
			while($row1= mysql_fetch_row($resource1))
			{
				array_push($grupos,$row1);							
			}
			
			//-------------------
			//-------------------	
		
		}
		else
		{
			$strSQL1="SELECT id_grupo,nombre FROM sys_grupos where activo=1 order by nombre";
			if(!($resource1 = mysql_query($strSQL1)))	die("Error at strSQL1 $strSQL1 ::".mysql_error());
			
			unset($grupos);
			$grupos=array();
		
			while($row1= mysql_fetch_row($resource1))
			{
				
				unset($arr_usuarios);
				$arr_usuarios=array();

				unset($arr_usuarios2);
				$arr_usuarios2=array();
		
		
				//para cada grupo obtenemos sus usuarios de cada grupos
				$strSQL="SELECT sys_usuarios_grupos.id_usuario, concat(nombres,' ',apellido_paterno,' ', apellido_materno) as nombre 
						 FROM sys_usuarios_grupos left join sys_usuarios on sys_usuarios_grupos.id_usuario=sys_usuarios.id_usuario 
						 WHERE sys_usuarios_grupos.id_grupo='".$row1[0]."' and    sys_usuarios.activo=1
						 ORDER BY 2	 ";
				
				
				if(!($resourceUsu = mysql_query($strSQL)))	die("Error at strSQL1 $strSQL ::".mysql_error());
				while($rowUS= mysql_fetch_row($resourceUsu))
				{
					array_push($arr_usuarios,$rowUS[0]);
					array_push($arr_usuarios2,$rowUS[1]);					
				}
				
				$row1[2]=$arr_usuarios;
				$row1[3]=$arr_usuarios2;
				
				
				//tambien para cada grupo vemos cual tiene permisos especiales
				//--------------------------------------------
				//--------------------------------------------
				//para indicar los ususarios que tienen permisos especiales
				if($p=='2-')
				{
					unset($arr_usuarios3);
					$arr_usuarios3=array();
					
					unset($arr_usuarios4);
					$arr_usuarios4=array();
			
					//para cada grupo obtenemos sus usuarios de cada grupos
					$strSQL=" SELECT sys_usuarios_grupos.id_usuario, concat(nombres,' ',apellido_paterno,' ', apellido_materno) as nombre 
							  FROM sys_usuarios_grupos left join sys_usuarios on sys_usuarios_grupos.id_usuario=sys_usuarios.id_usuario 
							  WHERE id_grupo='".$row1[0]."' and sys_usuarios.id_usuario in (SELECT  id_usuario FROM sys_permisos_usuarios)
							  and sys_usuarios.activo=1  ORDER BY 2	";
							 
					if(!($resourceUsuP = mysql_query($strSQL)))	die("Error at strSQL1 $strSQL ::".mysql_error());
					while($rowUSP= mysql_fetch_row($resourceUsuP))
					{
						array_push($arr_usuarios3,$rowUSP[0]);	
						array_push($arr_usuarios4,$rowUSP[1]);					
					}
					
					//$row1[3]=$arr_usuarios2;
					
				}
				//------------------------------
				$row1[4]=$arr_usuarios3;
				$row1[5]=$arr_usuarios4;
				
				array_push($grupos,$row1);		
			}
			
			
		}
		
		$smarty->assign("no_per",$p);
		$smarty->assign("grupos",$grupos);
		//enviar&aacute; los datos a los contratos
		$smarty->display("permisos/permisos.tpl");
				
			
	}
	elseif ($make=='submenus' || $make=='usuarios' || $make=='grupos')
	{
		//echo '-'.$make.'-';
		// ic : id contrato que tenemos desde la seleccion de los filtros
		$id_menu=$ic;
		
		
		
		if($id_menu!="")
		{
			//obtenemos todos los pagares no saldados del cliente			
			//bpns : busca pagares no saldados 
			if($make=='submenus')
			{
					
					$strSQL1="	SELECT id_menu, nombre,'0' as ver, '0' AS nuevo, '0' AS modi, '0' AS eliminar,'0' as imprimir,'0' as generar
					FROM  sys_menus 
					WHERE  id_menu=id_menu_padre
					AND activo=1
					AND ver_permisos=1 ". ES_ADM_SUC ." 
					ORDER BY orden ";
		
					if(!($resource2 = mysql_query($strSQL1)))	die("Error at strSQL1 $strSQL1 ::".mysql_error());
			
					unset($registrossubr);
					$registrossubr=array();
										
					while($row2= mysql_fetch_row($resource2))
					{
						//del menu buscamos sus hijos
						
						$strSQL11="	SELECT id_menu, nombre, '0' as ver, '0' AS nuevo, '0' AS modi, '0' AS eliminar,'0' as imprimir,'0' as generar
									FROM  sys_menus 
									WHERE 
									(
										id_menu_padre = '".$row2[0]."'
										OR id_menu_padre IN(SELECT id_menu FROM sys_menus WHERE id_menu_padre = '".$row2[0]."' ". ES_ADM_SUC ." )
									)
									AND ver_permisos=1
									AND activo=1
									AND id_menu <> '".$row2[0]."' ". ES_ADM_SUC ." 
									ORDER BY nombre";
						if(!($resourceSub = mysql_query($strSQL11)))	die("Error at strSQL1 $strSQL1 ::".mysql_error());
			
						unset($arrr_Sub);
						$arrr_Sub=array();
						
						while($row22= mysql_fetch_row($resourceSub))
						{
							array_push($arrr_Sub,$row22);
						}
						$row2[8]=$arrr_Sub;
						array_push($registrossubr,$row2);
					}
										
					$smarty->assign("msg",'hola '.mysql_num_rows($resource2));					
					$smarty->assign("reg",$registrossubr);
					//print_r ($registrossubr);
					//enviar&aacute; los datos a los contratos
												
								
			}	
			elseif($make=='grupos')
			{
					$strSQL1="	SELECT id_menu, nombre,'0' as ver, '0' AS nuevo, '0' AS modi, '0' AS eliminar,'0' as imprimir,'0' as generar
					FROM  sys_menus 
					WHERE  id_menu=id_menu_padre
					AND activo=1
					AND ver_permisos=1 ". ES_ADM_SUC ." 
					ORDER BY orden ";
		
				
					
					$id_grupo=$ic;
					
					if(!($resource2 = mysql_query($strSQL1)))	die("Error at strSQL1 $strSQL1 ::".mysql_error());
			
					unset($registrossubr);
					$registrossubr=array();
										
					while($row2= mysql_fetch_row($resource2))
					{
						//del menu buscamos sus hijos
						$strSQL11="SELECT id_menu, nombre,
									(SELECT count(id_secuencia)  FROM `sys_permisos_grupos`  where id_permiso=2 and id_menu=aux.id_menu and  id_grupo='".$id_grupo."') as ver,
									
									
									(SELECT count(id_secuencia)  FROM `sys_permisos_grupos`  where id_permiso=3 and id_menu=aux.id_menu and  id_grupo='".$id_grupo."')  AS nuevo,
									
									(SELECT count(id_secuencia)  FROM `sys_permisos_grupos`  where id_permiso=4 and id_menu=aux.id_menu and  id_grupo='".$id_grupo."') AS modi,
									(SELECT count(id_secuencia)  FROM `sys_permisos_grupos`  where id_permiso=5 and id_menu=aux.id_menu and  id_grupo='".$id_grupo."') AS eliminar,
									
									 (SELECT count(id_secuencia)  FROM `sys_permisos_grupos`  where id_permiso=6 and id_menu=aux.id_menu and  id_grupo='".$id_grupo."')  as imprimir,
									 (SELECT count(id_secuencia)  FROM `sys_permisos_grupos`  where id_permiso=7 and id_menu=aux.id_menu and  id_grupo='".$id_grupo."')  as generar
																		FROM  sys_menus aux
									WHERE
									(
										id_menu_padre = '".$row2[0]."'
										OR id_menu_padre IN(SELECT id_menu FROM sys_menus WHERE id_menu_padre = '".$row2[0]."' ". ES_ADM_SUC ."  )
									)	
									AND ver_permisos=1
									AND activo=1
									AND id_menu <> '".$row2[0]."'  ". ES_ADM_SUC ." 
									ORDER BY nombre ";
						
						//echo "Dato: $strSQL11<br>";
						if(!($resourceSub = mysql_query($strSQL11)))	die("Error at strSQL1 $strSQL1 ::".mysql_error());
			
						unset($arrr_Sub);
						$arrr_Sub=array();
						
						while($row22= mysql_fetch_row($resourceSub))
						{
							array_push($arrr_Sub,$row22);
						}
						$row2[8]=$arrr_Sub;
						array_push($registrossubr,$row2);						
						//print_r ($registrossubr);
						//		echo '<br>';
					}
										
					$smarty->assign("msg",'hola '.mysql_num_rows($resource2));					
					$smarty->assign("reg",$registrossubr);
					//enviar&aacute; los datos a los contratos
			
			
			
			}
			elseif($make=='usuarios')
			{
				
					$strSQL1="	SELECT id_menu, nombre,'0' as ver, '0' AS nuevo, '0' AS modi, '0' AS eliminar,'0' as imprimir,'0' as generar
						FROM  sys_menus 
						WHERE  id_menu=id_menu_padre  ". ES_ADM_SUC ." 
						ORDER BY orden ";
			
					//obtenemos los grupos a los que pertenecen
					
					//buscamos los grupos a los que pertenecen
					$id_usuario=$ic;
					
					//buscamso los grupos a los que pertenece el usuario
					$strid_grupos= strObtenGrupos($id_usuario);
					
					//obtenemos los grupos a los que pertenece
										
					if(!($resource2 = mysql_query($strSQL1)))	die("Error at strSQL1 $strSQL1 ::".mysql_error());
			
					unset($registrossubr);
					$registrossubr=array();
										
					while($row2= mysql_fetch_row($resource2))
					{
						
						//donde almacenara los submenus
						unset($arrr_Sub);
						$arrr_Sub=array();
						
						//obtenemos todos lo submenus que sean hoja del menu seleccionado
						///{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{
						$strSQLM="	SELECT id_menu, nombre
										FROM  sys_menus 
										WHERE  id_menu_padre='".$row2[0]."' and hoja=1  ". ES_ADM_SUC ." 
										ORDER BY nombre ";
										
						
								
						if(!($resourceM = mysql_query($strSQLM)))	die("Error at strSQLM $strSQLM ::".mysql_error());
						//trae el msubmenu
						while($rowM= mysql_fetch_row($resourceM))
						{		
						
						
								//del menu buscamos sus hijos
								$strSQL11="SELECT id_menu, nombre,
											(
												SELECT if(por_usrTodosPermisos=0,por_gpo,por_usr) from
											  (SELECT count(id_secuencia) as por_gpo  FROM `sys_permisos_grupos`  where id_permiso=2 and id_menu= '".$rowM[0]."' and  id_grupo in (".$strid_grupos.") ) as gpo
										  ,
		      								  (SELECT count(id_secuencia) as  por_usr FROM `sys_permisos_usuarios`  where id_permiso=2 and id_menu='".$rowM[0]."' and  id_usuario='".$id_usuario."'  ) as usr ,
											   (SELECT count(id_secuencia) as  por_usrTodosPermisos FROM `sys_permisos_usuarios`  where    id_usuario='".$id_usuario."'  ) as todos
											  ) as ver,
											
											
											(SELECT if(por_usrTodosPermisos=0,por_gpo,por_usr) from
											  (SELECT count(id_secuencia) as por_gpo  FROM `sys_permisos_grupos`  where id_permiso=3 and id_menu= '".$rowM[0]."' and  id_grupo in (".$strid_grupos.")) as gpo
										  ,
		      								  (SELECT count(id_secuencia) as  por_usr FROM `sys_permisos_usuarios`  where id_permiso=3 and id_menu='".$rowM[0]."' and  id_usuario='".$id_usuario."') as usr
											  ,
											   (SELECT count(id_secuencia) as  por_usrTodosPermisos FROM `sys_permisos_usuarios`  where    id_usuario='".$id_usuario."'  ) as todos
											  )  AS nuevo,
											
											(SELECT if(por_usrTodosPermisos=0,por_gpo,por_usr) from
											  (SELECT count(id_secuencia) as por_gpo  FROM `sys_permisos_grupos`  where id_permiso=4 and id_menu= '".$rowM[0]."' and  id_grupo in (".$strid_grupos.")) as gpo
										  ,
		      								  (SELECT count(id_secuencia) as  por_usr FROM `sys_permisos_usuarios`  where id_permiso=4 and id_menu='".$rowM[0]."' and  id_usuario='".$id_usuario."') as usr
											  ,

											  (SELECT count(id_secuencia) as  por_usrTodosPermisos FROM `sys_permisos_usuarios`  where    id_usuario='".$id_usuario."'  ) as todos) AS modi,
											(SELECT if(por_usrTodosPermisos=0,por_gpo,por_usr) from
											  (SELECT count(id_secuencia) as por_gpo  FROM `sys_permisos_grupos`  where id_permiso=5 and id_menu= '".$rowM[0]."' and  id_grupo in (".$strid_grupos.")) as gpo
										  ,
		      								  (SELECT count(id_secuencia) as  por_usr FROM `sys_permisos_usuarios`  where id_permiso=5 and id_menu='".$rowM[0]."' and  id_usuario='".$id_usuario."') as usr
											  ,
											   (SELECT count(id_secuencia) as  por_usrTodosPermisos FROM `sys_permisos_usuarios`  where    id_usuario='".$id_usuario."'  ) as todos
											  ) AS eliminar,
											
											 (SELECT if(por_usrTodosPermisos=0,por_gpo,por_usr) from
											  (SELECT count(id_secuencia) as por_gpo  FROM `sys_permisos_grupos`  where id_permiso=6 and id_menu= '".$rowM[0]."' and  id_grupo in (".$strid_grupos.")) as gpo
										  ,
		      								  (SELECT count(id_secuencia) as  por_usr FROM `sys_permisos_usuarios`  where id_permiso=6 and id_menu='".$rowM[0]."' and  id_usuario='".$id_usuario."') as usr
											  ,
											   (SELECT count(id_secuencia) as  por_usrTodosPermisos FROM `sys_permisos_usuarios`  where    id_usuario='".$id_usuario."'  ) as todos
											  )  as imprimir,
											 (SELECT if(por_usrTodosPermisos=0,por_gpo,por_usr) from
											  (SELECT count(id_secuencia) as por_gpo  FROM `sys_permisos_grupos`  where id_permiso=7 and id_menu= '".$rowM[0]."' and  id_grupo in (".$strid_grupos.")) as gpo
										  ,
		      								  (SELECT count(id_secuencia) as  por_usr FROM `sys_permisos_usuarios`  where id_permiso=7 and id_menu='".$rowM[0]."' and  id_usuario='".$id_usuario."') as usr
											  , 
											   (SELECT count(id_secuencia) as  por_usrTodosPermisos FROM `sys_permisos_usuarios`  where    id_usuario='".$id_usuario."'  ) as todos)  as generar
																				FROM  sys_menus aux
											WHERE  id_menu= '".$rowM[0]."'  ". ES_ADM_SUC ." 
											ORDER BY orden";
								
								
								/*echo $strSQL11;
								echo '<br><br>';
								die();/**/
																
								if(!($resourceSub = mysql_query($strSQL11)))	die("Error at strSQL11 $strSQL11 ::".mysql_error());
								
								while($row222= mysql_fetch_row($resourceSub))
								{
									array_push($arrr_Sub,$row222);
								}/**/
								
					   
					   }// de while m
					   
					   $row2[8]=$arrr_Sub;
					   array_push($registrossubr,$row2);
																
					}//del while de row 2 donde estan los menus
					//print_r($registrossubr);
					$smarty->assign("msg",mysql_num_rows($resource2));					
					$smarty->assign("reg",$registrossubr);
					//enviar&aacute; los datos a los contratos
							
			}
			
			/*
			$registros=array();
						
			if(!($resource1 = mysql_query($strSQL)))	die("Error at rowsel a $rowsel::".mysql_error());
			while($row= mysql_fetch_row($resource1)){
				array_push($registros,$row);	
			}
			$smarty->assign("registros",$registros);
			
			//limpiamos las variables
			mysql_free_result($resource1);
			*/
		}
		
		//nuemero de contrato seleccionado
		//si busca pagare no saldados enviamos una listra de los pagares no saldados
		//bpns : busca pagares no saldados 
		if($make=='submenus')
		{
			$smarty->display("permisos/per_submenus.tpl");				
		}	
		elseif($make=='usuarios')
		{
			$smarty->display("permisos/per_submenus.tpl");
		} 
		elseif($make=='grupos')
		{
			$smarty->display("permisos/per_submenus.tpl");	
		}
		
	}
	
	function strObtenGrupos($id_usuario)
	{
		$strGrupos="0";
		
		$strSQL="SELECT sys_usuarios_grupos.id_grupo ,sys_usuarios_grupos.id_usuario
				FROM sys_usuarios_grupos left join sys_grupos on  sys_usuarios_grupos.id_grupo=sys_grupos.id_grupo
				WHERE activo=1 and sys_usuarios_grupos.id_usuario='".$id_usuario."'";
				
		if(!($resourceGpos = mysql_query($strSQL)))	die("Error at rowsel 1 $strSQL ::".mysql_error());
		
		while($row= mysql_fetch_row($resourceGpos)){
		
			$strGrupos .=",".$row[0];
		}
		return  $strGrupos;
	}
	
?>