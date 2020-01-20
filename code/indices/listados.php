<?PHP
	
	include("../../conect.php");
	include("../../config.inc.php");
	include("../general/funciones.php");
	
	extract($_GET);
	extract($_POST);	
		
	$tcr=isset($tcr)?$tcr:'0';
	
	$stm=isset($stm)?$stm:'0';
	
	$tabla = base64_decode($t);
	
	
        if($_GET['message']){
            $message = "Esta prefactura ya ha sido aprobada";
            echo "<script type='text/javascript'>alert('$message');</script>";
        }
	
	$strCamposListado="SELECT  Consulta ,Campos, AnchosGrid,imprimir,EstiloCssHead,AltoHeader, pie_catalogo  FROM sys_listados where tabla='".$tabla."'";
	
	$resConsulta = mysql_query($strCamposListado) or die("Error at a $strCamposListado ::".mysql_error());
	if(mysql_num_rows($resConsulta)<=0){
		$msg = 'No se encontraron registros.';
		echo $msg;
		die();
	}
    
	$variable =mysql_fetch_assoc($resConsulta);
	$Consulta=$variable['Consulta'];
	//variable que trae si el catalogo tendra botones al final
	$pie_catalogo = $variable['pie_catalogo'];
	
	if($_SESSION["USR"]->sucursalid == 0){
		$Consulta = str_replace('$SUC', '', $Consulta);
		$Consulta = str_replace('$SESIONSUC', '', $Consulta);
	}
	else if($tabla != 'ad_facturas_audicel'){
		$Consulta = str_replace('$SUC', ' AND ad_pedidos.id_sucursal_alta =' . $_SESSION["USR"]->sucursalid, $Consulta);	
		$Consulta = str_replace('$SESIONSUC', ' AND ad_sucursales.id_sucursal =' . $_SESSION["USR"]->sucursalid, $Consulta);	
	}
	
	
	unset($anchos);
	$anchos = array();
	$sumAnchos=0;
	//Hay anchos definidos
	if(strlen($variable['AnchosGrid']) > 0)
	{
		$arrAux=explode('|', $variable['AnchosGrid']);
		for($iCont=0;$iCont<sizeof($arrAux);$iCont++)		
		{
			array_push($anchos, $arrAux[$iCont]);
			$sumAnchos+=($arrAux[$iCont]+0);
		}	
	}
	//Asignamos anchos fijos a 100
	else
	{
		$arrAux=explode('|', $variable['Campos']);
		//echo $variable['Campos']."<br>";
		for($iCont=0;$iCont<sizeof($arrAux);$iCont++)		
			array_push($anchos, 100);
	}
	
	//nombres de los campos de los filtros
	$enc_reales = explode("|",$variable['Campos']);
	
	//varible imprimir
	$imprimir=$variable['imprimir'];
	//**********************abc*********************************************
	//nombres de las imagenes que van en los encabezados
	$enc_estiloCSS = $variable['EstiloCssHead'];
	$alto_head = $variable['AltoHeader'];
	//**********************************************************************
	//Arreglo de encabezados
	$valuesFiltro = array();
	$cont=0;
	foreach ($enc_reales as $enc)
	{
	  if($anchos[$cont] != 0){
		array_push($valuesFiltro,$enc);
	  }
		$cont=$cont+1;
	}
	
	$valuesEncGrid = array();
	foreach ($enc_reales as $enc)
	{
		array_push($valuesEncGrid,$enc);
	}
   	$encabezados = array();
   	$outputsFiltro = array();
	
	//anexamos los prefiltros
	/*$Consulta = str_replace("/SUCURSAL", "1", $Consulta);
	echo $Consulta . "<br>111";*/
	$strSQL=$Consulta ;
		
	//EXCEPCIONES A LA CONSULTA DEL LISTADO	
	/***********************************************************************************************/
	//Si no es administrador
	$strSQLaux=$strSQL;
	if($esAdmin==0){
		
		$strWhereNumCat="";
		if($stm!=0)
		{
			$strWhereNumCat = " AND numero_catalogo= $stm";
		}
		
		$strSQL="SELECT id_menu FROM sys_menus where tabla_asociada='".$tabla ."'  ". ES_ADM_SUC ." ".$strWhereNumCat;
		
		$resUS = mysql_query($strSQL) or die(mysql_error());
		$resvariableUS=mysql_fetch_assoc($resUS);
			
		$id_submenu=$resvariableUS['id_menu'];
			
		mysql_free_result($resUS);
			
		//obtenemos los grupos del usuario
		$id_grupo=strObtenGruposMenu();
					
		if(stristr( ",".$strSubmenusPermitidos.",", ','.$id_submenu.',')){
		//de que tabla lo obtenemos??
		if($nivelPermiso=='usuario'){
			$strSQLPer="SELECT id_menu, nombre,
							(SELECT count(id_secuencia) FROM `sys_permisos_usuarios`  
										  where id_permiso=2 and id_menu=aux.id_menu and  id_usuario ='".$_SESSION["USR"]->userid."' ) as ver,
							(SELECT count(id_secuencia)  FROM `sys_permisos_usuarios`  
										  where id_permiso=3 and id_menu=aux.id_menu and id_usuario ='".$_SESSION["USR"]->userid."' )  AS nuevo,
							(SELECT count(id_secuencia)  FROM `sys_permisos_usuarios`  
										  where id_permiso=4 and id_menu=aux.id_menu and id_usuario ='".$_SESSION["USR"]->userid."' ) AS modi,
							(SELECT count(id_secuencia)  FROM `sys_permisos_usuarios`  
										  where id_permiso=5 and id_menu=aux.id_menu and id_usuario ='".$_SESSION["USR"]->userid."' ) AS eliminar,
							(SELECT count(id_secuencia)  FROM `sys_permisos_usuarios`  
										  where id_permiso=6 and id_menu=aux.id_menu and  id_usuario ='".$_SESSION["USR"]->userid."' )  as imprimir,
							(SELECT count(id_secuencia)  FROM `sys_permisos_usuarios`  
										  where id_permiso=7 and id_menu=aux.id_menu and  id_usuario ='".$_SESSION["USR"]->userid."' )  as generar
							FROM  sys_menus aux	WHERE id_menu = '".$id_submenu."'  ". ES_ADM_SUC ." ";						
		}else{
			$strSQLPer="SELECT id_menu, nombre,
							(SELECT count(id_secuencia)  FROM `sys_permisos_grupos`  
										  where id_permiso=2 and id_menu=aux.id_menu and  id_grupo in (".$id_grupo.") ) as ver,
							(SELECT count(id_secuencia)  FROM `sys_permisos_grupos`  
										  where id_permiso=3 and id_menu=aux.id_menu and  id_grupo in (".$id_grupo.") )  AS nuevo,									
							(SELECT count(id_secuencia)  FROM `sys_permisos_grupos`  
										  where id_permiso=4 and id_menu=aux.id_menu and  id_grupo in (".$id_grupo.") ) AS modi,
							(SELECT count(id_secuencia)  FROM `sys_permisos_grupos`  
										  where id_permiso=5 and id_menu=aux.id_menu and  id_grupo in (".$id_grupo.") ) AS eliminar,
							(SELECT count(id_secuencia)  FROM `sys_permisos_grupos`  
										  where id_permiso=6 and id_menu=aux.id_menu and  id_grupo in (".$id_grupo.") )  as imprimir,
							(SELECT count(id_secuencia)  FROM `sys_permisos_grupos`  
										  where id_permiso=7 and id_menu=aux.id_menu and  id_grupo in (".$id_grupo.") )  as generar
							FROM  sys_menus aux	WHERE id_menu = '".$id_submenu."'  ". ES_ADM_SUC ." ";
		}
				
		$resPer = mysql_query($strSQLPer) or die(mysql_error());
		$resvariablePer=mysql_fetch_assoc($resPer);
	
		$ver_per=$resvariablePer['ver'];
		$nuevo_per=$resvariablePer['nuevo'];
		$modi_per=$resvariablePer['modi'];
		$eliminar_per=$resvariablePer['eliminar'];
		$imprimir_per=$resvariablePer['imprimir'];
		$generar_per=$resvariablePer['generar'];
		
		if($nuevo_per>0 || $modi_per>0 || $eliminar_per>0  || $imprimir_per>0 || $generar_per>0)
			$ver_per=1;
			mysql_free_result($resPer);
		}else{
			//si no tiene permisos lo saca
			header("Location: ".$rooturl."index.php?".SID);	
			die();
		}	
	}else{
		//permisos para admin
		$ver_per=1;
		$nuevo_per=1; 
		$modi_per=1; 
		$eliminar_per=1;   
		$imprimir_per=1;  
		$generar_per=1; 		
	}
	
	switch($t){
		case 'Y2xfY29udHJhcmVjaWJvcw==';
			$ver_per=1;
			$nuevo_per=0; 
			$modi_per=0; 
			$eliminar_per=0;   
			$imprimir_per=1;  
			$generar_per=0; 
			break;
		case 'cmFjX3NvbGljaXR1ZGVzX2Rlc2N1ZW50b19zdWJ0b3RhbA==';
			$ver_per=1;
			$nuevo_per=0; 
			$modi_per=1; 
			$eliminar_per=0;   
			$imprimir_per=0;  
			$generar_per=0; 
			break;
			
		case 'cmFjX3NvbGljaXR1ZGVzX2xpYmVyYWNpb25fYXJ0aWN1bG9zX2VuX3BlZGlkbw==';
			$ver_per=1;
			$nuevo_per=0; 
			$modi_per=1; 
			$eliminar_per=0;   
			$imprimir_per=0;  
			$generar_per=0; 
			break;
		
		case 'cmFjX2NvdGl6YWNpb25lcw==';
			$ver_per=1;
			$nuevo_per=0; 
			$modi_per=1; 
			$eliminar_per=0;   
			$imprimir_per=0;  
			$generar_per=0; 
			break;
		
		case 'cmFjX3BlZGlkb3M=';
			$ver_per=1;
			$nuevo_per=1; 
			$modi_per=1; 
			$eliminar_per=0;   
			$imprimir_per=0;  
			$generar_per=0; 
			break;
			
		case 'cmFjX3NvbGljaXR1ZGVzX2Rlc2N1ZW50bw==';
			$ver_per=1;
			$nuevo_per=0; 
			$modi_per=1; 
			$eliminar_per=0;   
			$imprimir_per=0;  
			$generar_per=0; 
			break;
			
		case 'cmFjX3ZpYXRpY29z';
			$ver_per=1;
			$nuevo_per=0; 
			$modi_per=1; 
			$eliminar_per=0;   
			$imprimir_per=0;  
			$generar_per=0; 
			break;	
				
		case 'cmFjX2ZsZXRlcw==';
			$ver_per=1;
			$nuevo_per=0; 
			$modi_per=1; 
			$eliminar_per=0;   
			$imprimir_per=0;  
			$generar_per=0; 
			break;
		
		case 'cmFjX29yZGVuZXNfc2VydmljaW8=';
			$ver_per=1;
			$nuevo_per=0; 
			$modi_per=1; 
			$eliminar_per=0;   
			$imprimir_per=0;  
			$generar_per=0; 
			break;
		
		case 'bmFfZmFjdHVyYXM=';
			$ver_per=1;
			$nuevo_per=1; 
			$modi_per=1; 
			$eliminar_per=1;   
			$imprimir_per=1;  
			$generar_per=1; 
		break;
		case 'YWRfZmFjdHVyYXM=';
			$ver_per=1;
			$nuevo_per=0; 
			//$modi_per=0; 
			$eliminar_per=0;   
			$imprimir_per=1;  
			$generar_per=0; 
		break;
		case 'YWRfZmFjdHVyYXNfYXVkaWNlbA==';
			$ver_per=0;
			$nuevo_per=0; 
			$modi_per=0; 
			$eliminar_per=0;   
			$imprimir_per=1;  
			$generar_per=1; 
		break;

	}
	
	//Se crea una cadena que contiene todos los permisos del usuario
	$cadenaPer="vpe~".$ver_per."|npe~".$nuevo_per."|mpe~".$modi_per."|epe~".$eliminar_per."|ipe~".$imprimir_per."|gpe~".$generar_per;
	//se encripta la cadena
	$cadenaPer=base64_encode($cadenaPer);
	//Se divide a la mitad la cadena codificada
	$longcad=strlen($cadenaPer);
	$subcadena1=substr($cadenaPer,0,$longcad/2);
	$subcadena2=substr($cadenaPer,$longcad/2,$longcad/2);
	//Se le agrega al inicio de las subcadenas la longitud de las mismas, aproximando la longitud a un numero de 3 cifras
	//ademas tambien de le agrega el id de usuario al final de la cadena
	$subcadena1=num3dig($longcad/2).$subcadena1.$_SESSION["USR"]->userid;
	$subcadena2=num3dig($longcad/2).$subcadena2.$_SESSION["USR"]->userid;
	//Se vuelven a encriptar los valores de las longitudes
	$subcadena1=base64_encode($subcadena1);
	$subcadena2=base64_encode($subcadena2);
	$smarty->assign("cadP1",$subcadena1);
	$smarty->assign("cadP2",$subcadena2);	
	$smarty->assign("vpe",$ver_per);
	$smarty->assign("npe",$nuevo_per);								
	$smarty->assign("mpe",$modi_per);														
	$smarty->assign("epe",$eliminar_per);								
	$smarty->assign("ipe",$imprimir_per);								
	$smarty->assign("gpe",$generar_per);
	$strSQL=$strSQLaux;
 	/***********************************************************************************************/

	//EXCEPCIONES DE LOS ALMACENES
	if($tabla =='ad_movimientos_almacen')
	{
		if (isset($stm))
		{
			$G_SUB_TIPO_MOVIMIENTO=$stm;
			
		}
		$strSQL = str_replace('$G_SUB_TIPO_MOVIMIENTO',$G_SUB_TIPO_MOVIMIENTO, $strSQL);
		//excepciones de los almacenes  $G_AL_ALMACENES
		$strSQLBusc1="SELECT sys_usuarios.id_grupo FROM sys_usuarios left join sys_grupos on sys_grupos.id_grupo= sys_usuarios.id_grupo where id_usuario='".$_SESSION["USR"]->userid ."' Limit 1";
			
		//si el grupo es diferente
		$resValUsr=valBuscador($strSQLBusc1);
		$valor_grupo=$resValUsr[0];
		
		
		$strWhereAlmacenes="";
		if($valor_grupo != 1)
		{
			$strWhereAlmacenes= " AND ad_movimientos_almacen.id_almacen in (SELECT id_almacen FROM ad_sucursales where id_sucursal in (SELECT id_sucursal FROM sys_usuarios where id_usuario='".$_SESSION["USR"]->userid ."'))  ";
		}
		//------------------------
		//excepciones de los almacenes  $G_AL_ALMACENES
		$strSQL = str_replace('$G_AL_ALMACENES',$strWhereAlmacenes, $strSQL);	
		
	}
	elseif($tabla =='ad_clientes')
	{
		if (isset($stm))
		{
			
			//buscamos el tipo de cliente que tienen asociado este subtipo de movimientoi
			$strSQLB1="SELECT id_tipo_cliente_proveedor FROM cl_tipos_cliente_proveedor where numero_catalogo =".$stm;
			$resValB1=valBuscador($strSQLB1);
			$tipo_cliente_proveedor=$resValB1[0];
			
			
		}
		$strSQL = str_replace('$G_SUB_TIPO_MOVIMIENTO',$tipo_cliente_proveedor, $strSQL);
		
		//del cliente logu	 $G_CLIENTE_PRINCIPAL
		
		$strWhereClientePrincipal= " AND id_cliente= ".$_SESSION["USR"]->cliente_principal_id;
		
		
		$strSQL = str_replace('$G_CLIENTE_PRINCIPAL',$strWhereClientePrincipal, $strSQL);
	
				
	}
	else if($tabla == 'ad_facturas'){
		$strWhereClientePrincipal= " AND ad_facturas.id_compania IN(".$_SESSION["USR"]->clientes_relacionados .")";
		$strSQL = str_replace('$G_CLIENTE_PRINCIPAL',$strWhereClientePrincipal, $strSQL);
	}else if($tabla == 'ad_pedidos'){
		$strWhereClientePrincipal = " AND ad_pedidos.id_cliente IN(".$_SESSION["USR"]->clientes_relacionados.")";
		$strSQL = str_replace('$G_CLIENTE_PRINCIPAL',$strWhereClientePrincipal, $strSQL);
	}else if($tabla == 'ad_facturas_audicel'){
		$strWhereClientePrincipal = " AND ad_facturas_audicel.id_cliente IN(".$_SESSION["USR"] -> clientes_relacionados.")";
		$strSQL = str_replace('$SUC',$strWhereClientePrincipal, $strSQL);
	}elseif($tabla == 'sys_usuarios'){
		if($_SESSION["USR"] -> idGrupo == '1')
			$strSQL = str_replace('$USR_G',' AND sys_usuarios.id_grupo NOT IN(2,3,4)', $strSQL);
		else
			$strSQL = str_replace('$USR_G',' AND sys_usuarios.id_usuario="'.$_SESSION["USR"] -> userid.'"', $strSQL);
	}

	
	//$G_CLIENTE_CON_CLAVE
		
	
	$strSQL = str_replace('$GRUPO', $_SESSION["USR"]->idGrupo, $strSQL);	
	$strSQL = str_replace('$USUARIO', $_SESSION["USR"]->userid, $strSQL);
	
	/*if($_SESSION["USR"]->sucursalid == 0){
			$strSQL = str_replace('$SUC', '', $strSQL);
			$strSQL = str_replace('$SUCURSAL', '', $strSQL);
			}
	else{
			$strSQL = str_replace('$SUC', ' AND ad_pedidos.id_sucursal_alta =' . $_SESSION["USR"]->sucursalid, $strSQL);	
			$strSQL = str_replace('$SUCURSAL', ' AND id_sucursal =' . $_SESSION["USR"]->sucursalid, $strSQL);	
			}*/
        
	
	if(!($resource = mysql_query($strSQL)))	die("Error en:<br><i>$strSQL</i><br><br>Descripci&oacute;n:<br><b>".mysql_error());
	if(mysql_num_rows($resource)<=0){
		$msg = 'No se encontraron registros.';
	}
	unset($registros);
	unset($headers);
	$registros = array();
	$headers = array();
	$i=0;
	$cont=0;
	while($i<mysql_num_fields($resource)){
		$meta = mysql_fetch_field($resource,$i++);
		array_push($headers,$meta->name);
		//array_push($encabezados,$meta->name);
         if($anchos[$cont] != 0){ //solo improme los campos que se viasualicen en el Grid para el Filtro 
		   array_push($outputsFiltro,$meta->name);
		}
		$cont=$cont+1;
	}
	
		
	
	while($row = mysql_fetch_row($resource)){
		array_push($registros,$row);
	}
	mysql_free_result($resource);
	
	if($tabla =='ad_movimientos_almacen')
	{
		if (isset($stm))
		{
				$numero_catalogo=$stm;
		}
		
		$_SESSION["USR"]->subtipo_movimiento=$stm;
	}
	else	if($tabla =='ad_clientes')
	{
		if (isset($stm))
		{
				$numero_catalogo=$stm;
		}
		
		$_SESSION["USR"]->subtipo_movimiento=$stm;
	}



//echo base64_encode("of_franquicias");
//Extraccion del nombre del encabezado de la pgina
if(isset($numero_catalogo))
{
	$strSQL="SELECT  nombre as nombre_menu FROM sys_menus where tabla_asociada='".$tabla."' and numero_catalogo='".$numero_catalogo."' ". ES_ADM_SUC ." limit 1";
}
else
{
	//traemos del menu el nombre que le dio al menu
	$strSQL="SELECT  nombre as nombre_menu FROM sys_menus where tabla_asociada='".$tabla."'  ". ES_ADM_SUC ." limit 1";
}

	$res= mysql_query($strSQL) or 	die("Error at a $strSQL ::".mysql_error());
	extract(mysql_fetch_assoc($res));
	
	$smarty->assign("t",$t);$tcr=0;		

	//Excepcion de sucursales y usuarios	
	if(VER_SUCURSAL == 1 && $tabla == 'sys_usuarios')
	{
		array_push($headers, "SUCURSAL");
	}
	
	$smarty->assign("stm",$stm);
	$_SESSION["USR"]->subtipo_movimiento=$stm;
	/************************/

	/************************/
	
	
	if($tabla == "ad_facturas_audicel")
		$smarty->assign("nombre_menu","Facturas Emitidas por AUDICEL");
	else
		$smarty->assign("nombre_menu",$nombre_menu);
	$smarty->assign("headers",$headers);
	$smarty->assign("rows",$registros);	
	$smarty->assign("anchos",$anchos);
	$smarty->assign("sumAnchos",$sumAnchos);
	$smarty->assign("valuesFiltro",$valuesFiltro);
	$smarty->assign("valuesEncGrid",$valuesEncGrid);
	$smarty->assign("outputsFiltro",$outputsFiltro);
	//**********************abc*****************************
	$smarty->assign("estiloCssHead",$enc_estiloCSS);
	$smarty->assign("altohead",$alto_head);
	$smarty->assign("pie_catalogo",$pie_catalogo);
	//******************************************************
	//echo $imprimir;
	
	$smarty->assign("imprimir",$imprimir);
	
	$smarty->display("indices/listados.tpl");
	
	function num3dig($numOriginal)
	{
		if(strlen($numOriginal)>=3)
			return $numOriginal;
		if(strlen($numOriginal)>=2)
			return	"0".$numOriginal;
		if(strlen($numOriginal)>=1)
			return "00".$numOriginal;
		if(strlen($numOriginal)>=0)
			return	"000";	
	}	
	
?>