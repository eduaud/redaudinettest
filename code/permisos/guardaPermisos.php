<?php
	php_track_vars;

	extract($_GET);
	extract($_POST);
	
//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../conect.php");
	//include("../../code/general/funciones.php");
	
	$strTrans="AUTOCOMMIT=0";
	mysql_query($strTrans);
	mysql_query("BEGIN");
	$error=0;
			
	//para la entrada principal
	if($make=="inGpo")
	{
		//separado por ~
		$arrSubmenu= explode('~',$submenu) ;
		
		//grupos separados por ,
		$arrGrupo=explode(',',$grupo);
		//para cada realizamos un insert en la tabla de permisos
		//id_secuencia, id_menu, id_permiso, id_grupo
		if(sizeof($arrGrupo) > 0)	
		{
			//echo sizeof($arrGrupo);
			//aqui realizamos el recorrido de cada grupo
			for($i=0;$i<sizeof($arrGrupo);$i++)
			{
				//primero borramos todos los permisos del grupo
				$strDEL="DELETE FROM sys_permisos_grupos WHERE id_grupo='".$arrGrupo[$i]."' ";
				
				mysql_query($strDEL) or rollback('strDEL',mysql_error(),mysql_errno(),$strDEL );			
				
				
				//si tenemos la opcion de document.getElementById('usuario_cambiar').value seleccionada borramos todos los permisos de los usuarios. 
				//camus cambia usuario
				if($camus=='1')
				{
					//borramos todos los permisos de ususarios (especioales) que pertenecen al grupo
					$strDEL="DELETE FROM sys_permisos_usuarios WHERE id_usuario in (SELECT id_usuario FROM sys_usuarios_grupos where id_grupo = '".$arrGrupo[$i]."') ";
					mysql_query($strDEL) or rollback('strDEL',mysql_error(),mysql_errno(),$strDEL );			
				}
				
				//descomponemos en array los datos de los submenus
				for($j=0;$j<sizeof($arrSubmenu);$j++)
				{
					//partimos el submenu
					//en 0 es el numero del menu
					//en 1 es el permiso asocioado
					$arrmenuPermiso=explode('|',$arrSubmenu[$j]);
					if($arrmenuPermiso[0]!='' && $arrGrupo[$i]!='')
					{
						$strInsert="INSERT INTO sys_permisos_grupos values (null, '".$arrmenuPermiso[0]."', '".$arrmenuPermiso[1]."', '".$arrGrupo[$i]."')";
						//grabaBitacora('3','sys_permisos_grupos','0',$arrmenuPermiso[0],$_SESSION["USR"]->userid,'0',$arrmenuPermiso[1],$arrGrupo[$i]);
						mysql_query($strInsert) or rollback('strInsert',mysql_error(),mysql_errno(),$strInsert );			
					}
				}						
			}
		}
		
		echo 'Exito';
		
	}	
	elseif($make=="inUsr")
	{//--------------------
	//---------------------
	
	//separado por ~
		$arrSubmenu= explode('~',$submenu) ;
		
		//grupos separados por ,
		$arrGrupo=explode(',',$grupo);
		//para cada realizamos un insert en la tabla de permisos
		//id_secuencia, id_menu, id_permiso, id_grupo
		if(sizeof($arrGrupo) > 0)	
		{
			//echo sizeof($arrGrupo);
			//aqui realizamos el recorrido de cada grupo
			for($i=0;$i<sizeof($arrGrupo);$i++)
			{
				//primero borramos todos los permisos del grupo
				$strDEL="DELETE FROM sys_permisos_usuarios WHERE id_usuario='".$arrGrupo[$i]."' ";
				
				mysql_query($strDEL) or rollback('strDEL',mysql_error(),mysql_errno(),$strDEL );			
				
				//descomponemos en array los datos de los submenus
				for($j=0;$j<sizeof($arrSubmenu);$j++)
				{
					//partimos el submenu
					//en 0 es el numero del menu
					//en 1 es el permiso asocioado
					$arrmenuPermiso=explode('|',$arrSubmenu[$j]);
					if($arrmenuPermiso[0]!='' && $arrGrupo[$i]!='')
					{
						$strInsert="INSERT INTO sys_permisos_usuarios values (null, '".$arrmenuPermiso[0]."', '".$arrmenuPermiso[1]."', '".$arrGrupo[$i]."')";
						
						mysql_query($strInsert) or rollback('strInsert',mysql_error(),mysql_errno(),$strInsert );
						
						grabaBitacora('3','sys_permisos_usuarios','0',$arrmenuPermiso[0],$_SESSION["USR"]->userid,'0',$arrmenuPermiso[1],$arrGrupo[$i]);
						
						//grabaBitacora('3',$tabla,'0',$valorllave,$_SESSION["USR"]->userid,'0','','');
				       //grabaBitacora($id_accion,$tabla,$id_campo,$id_relacionado,$id_usuario,$id_menu,$otro,$observaciones)
					
					}
				}						
			}
		}
		
		echo 'Exito';
	
	//--------------	
	}
	elseif($make=="delUsrs")
	{
		//grupos separados por ,
		$arrGrupo=explode(',',$grupo);
		//para cada realizamos un insert en la tabla de permisos
		//id_secuencia, id_menu, id_permiso, id_grupo
		if(sizeof($arrGrupo) > 0)	
		{
			//echo sizeof($arrGrupo);
			//aqui realizamos el recorrido de cada grupo
			for($i=0;$i<sizeof($arrGrupo);$i++)
			{
				//primero borramos todos los permisos del grupo
				$strDEL="DELETE FROM sys_permisos_usuarios WHERE id_usuario='".$arrGrupo[$i]."' ";
				mysql_query($strDEL) or rollback('strDEL',mysql_error(),mysql_errno(),$strDEL );			
			}
		}
		
		echo 'Exito';
	}

	mysql_query("COMMIT");	
	
	//implementamos el rool back
	function rollback($tabla,$errorSQL,$numError,$consulta){
		//global $smarty,$link;
		mysql_query("ROLLBACK");
		/*
		require("./errores.php");
		$smarty->assign('contentheader',"Advertencia de error en el sistema");
		$smarty->assign('StrError',$errorSQL);
		$smarty->assign('NumError',$numError);
		$smarty->assign('DescError',$descError);
		$smarty->assign('Consulta',$consulta);
		$smarty->assign('rutaImagen',ROOTURL."modules/GEG/templates/default/media/");
		$smarty->display('error.tpl');*/
		
		echo "Error en la consulta:<br><i>". $consulta."</i><br><br>Descripci&oacute;n del error:<br><b>".$errorSQL."</b><br><br>Error number: ".$numError;
		
		exit();
	}
?>