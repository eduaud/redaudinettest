<?php
//die("Hola mundo");
//ini_set('display_errors', '1');

	extract($_GET);
	extract($_POST);
	
	///variables de conexion
	include('config.inc.php');
	
	/*echo 'ad_tipos_proveedores ->'.base64_encode("ad_tipos_proveedores") ."<br>";

die();*/
	
	
/*
echo 'rac_eventos_tipos_lugares ->'.base64_encode("rac_eventos_tipos_lugares") ."<br>";

die();*/
//echo base64_decode("cHJlZl9mb2xpb3M=");

	//variables declaracion
	include(INCLUDEPATH."container.inc.php");
	include("./code/general/funciones.php");
	
	//validacion de SUCURSAL Seleccionada
	if(isset($_POST["id_sucursal"])){
	   if($_POST["id_sucursal"] == 0){
	        $no_pasa ++;
			$mensaje=" Sucursal Inv&aacute;lida";
			unset($_SESSION);
	   }
	}
	
	$strSQL="SELECT id_sucursal, nombre FROM ad_sucursales ";
	
	if(!($res1 = mysql_query($strSQL)))	die("Error at arreglos 2 $strSQL   ::".mysql_error());
		
	unset($idsSuc);
	unset($namesSuc);
	$idsSuc = array();
	$namesSuc = array();
	
	while($rowSuc = mysql_fetch_row($res1))
	{
		array_push($idsSuc,$rowSuc[0]);
		array_push($namesSuc,$rowSuc[1]);
	}
	mysql_free_result($res1);
	
	$smarty->assign("idsSuc",$idsSuc);
	$smarty->assign("namesSuc",$namesSuc);	
	
	if (isset($_POST["login"])) 
	{		
		//valida login y password	
		$row =array();
		$login = $_POST["login"];
		$password = (isset($_POST["password"]) ? $_POST["password"] : "");
		$encrypted_pwd = (isset($_POST["encrypted_pwd"]) ? base64_encode($_POST["encrypted_pwd"]) : "");
		$id_sucursal = (isset($_POST["id_sucursal"]) ? $_POST["id_sucursal"] : "");
		$mensaje=$encrypted_pwd;
		$conn = connect_database($session_long); 
		//password encriptado
		if (!$encrypted_pwd) { $smarty->assign("encrypt_pwd","hex_md5"); }
		
		$sql = "SELECT * FROM sys_usuarios WHERE activo = 1 AND login='".$login."'  and id_tipo_cliente_proveedor in	(SELECT id_tipo_cliente_proveedor FROM cl_tipos_cliente_proveedor where asignar_usuario=1) "; // AND id_sucursal =".$id_sucursal;
		$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:".mysql_error());
	
		///verifica si el login existe
		if (count($res)==0) 
		{
			$no_pasa ++;
			$mensaje=" Login Inv&aacute;lido";
			unset($_SESSION);
		}
		else
		{
			///si existe el login , verificamos el password		
			$sql2 = "
				SELECT 
				id_usuario,
				nombres,
				concat(apellido_paterno,' ', apellido_materno) as apellidos,
				sys_usuarios.email,
				login,
				id_grupo,
				id_cliente_distribuidor
				FROM sys_usuarios				
				WHERE activo = 1 AND pass = '"  . mysql_real_escape_string($encrypted_pwd) . "' 
				AND login = '" . mysql_real_escape_string($login) . "' and id_tipo_cliente_proveedor in	(SELECT id_tipo_cliente_proveedor FROM cl_tipos_cliente_proveedor where asignar_usuario=1)
			";
			
			
			//vemso que corresponda al del login seleccionado
			$resPas=obtenFilaQuery($sql2) /*or die("Error en consulta> $sql2 "  .  mysql_error())*/;
			if (count($resPas)==0) 
			{
				$no_pasa ++;
				$mensaje="Password Inv&aacute;lido";
				$_SESSION = array();
				$_SESSION['USR']=new USR();
				
			}
			else
			{
				$mensaje="conectado con password";
				$row = get_user_info($resPas["id_usuario"]);		
				//si existe el password llenamos la variable row con los datos del ususario
			}
			
						
		}	
		mysql_free_result($res);
		
		if(is_array($row) && $no_pasa ==0) 
		{		
			$_SESSION = array();
			$_SESSION["USR"]= new USR();
			
			///hora y fecha de acceso que actualizaremos en la base de datos, en el acceso del usuario
			$_SESSION["lastin"] = time();
			
			$sql = "UPDATE sys_usuarios SET  fecha_ultimo_acceso=NOW() WHERE id_usuario=".(int)$row["id"];
			$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:".mysql_error());
		
			
			$_SESSION["checkip"] = (isset($_POST["checkip"]) && $_POST["checkip"]==1) ? 1 : 0;
			///la ip
			$_SESSION["remoteip"] = $_SERVER["REMOTE_ADDR"];
			$_SESSION["http_user_agent"] = $_SERVER["HTTP_USER_AGENT"];

			///datos del usuario en la base de datos
			$_SESSION["USR"]->fullusername = $row["nombres"] . " " . $row["apellido_paterno"];
			$_SESSION["USR"]->email = $row["email"];
			$_SESSION["USR"]->username = $row["login"];
			$_SESSION["USR"]->userid   = $row["id_usuario"];
			$_SESSION["USR"]->id_compania = $row["id_compania"];
			$_SESSION["USR"]->login = $login;
			$_SESSION["USR"]->password = $password;
			
			$_SESSION["USR"]->sucursalid = $row["id_sucursal"];
			$_SESSION["USR"]->id_tipo_cliente_proveedor = $row["id_tipo_cliente_proveedor"];
			$_SESSION["USR"]->idGrupo = $row["id_grupo"];
			$_SESSION["USR"]->cliente_principal_id = $row["id_cliente_distribuidor"];
			//de este cliente buscamos los clientes que tenga relacionados
			$_SESSION["USR"]->clientes_relacionados =obtenClientesRelacionados($row["id_cliente_distribuidor"]);
			
			
			$_SESSION["USR"]->sucursalname= get_name_sucursal($row["id_sucursal"]);
			$_SESSION["USR"]->id_sucursalprefijo= get_prefijo_sucursal($row["id_sucursal"]);
			
			//-----------------------------------------------------------------
			
			//echo $_SESSION["USR"]->clientes_relacionados;
			//die();
			
			//para el detalle del grid
			$_SESSION["USR"]->parametrosAux='';			
			
			$_SESSION["USR"]->modules = array();
			//get_user_groups
			//funciones que se activaran para saber a que grupo pertenece el ususario
			$_SESSION["USR"]->groups = obtenerGruposUsuario();
			
			//grupos a los que pertenece
			//$_SESSION["USR"]->groups_names = get_user_groups_names();
			//navegador que detencta
			$_SESSION["USR"]->browser = browser_detect();
			
			//si el usuario administra almacen
			$_SESSION["USR"]->administra_alamacen = $row["administra_alamacen"];
			
			//AUXILIRARES
			
			$_SESSION["USR"]->subtipo_movimiento = 0;
			
			//bitacora
			grabaBitacora('1','','0','0',$_SESSION["USR"]->userid,'0','','');			
			
			header("Location: ".$rooturl."code/indices/menu.php?".SID);			

			exit();			
		}
		// Login inv&aacute;lido
		else 
		{
			unset($_SESSION);
		}
	}
	else
	{
		
		if (isset($_SESSION['USR']) && isset($_SESSION['USR']->userid ) && !empty($_SESSION["USR"]->userid) && !isset($_POST["login"]) &&  $_SESSION["USR"]->userid <>'')
		{
				if($id_razon_pendiente != '')
				{
					//die("1 si");
					header("Location: ".$rooturl."code/especiales/autorizaSolPend.php?id_razon=".$id_razon_pendiente);							
					exit();
				}
				
				if($id_sol_pend != '')
				{
					//die("1 si");
					header("Location: ".$rooturl."code/general/encabezados.php?t=cGV1Z19jbGllbnRlc19kaXN0X3RtcA==&k=$id_sol_pend&op=2&v=0&tabla=&cadP1=MDI0ZG5CbGZqRjhibkJsZmpGOGJYQmxmakY4MQ==&cadP2=MDI0WlhCbGZqRjhhWEJsZmpGOFozQmxmakU9MQ==");							
					exit();
				}
				
				if($id_boletin != '')
			{
				//die("2 si");
				header("Location: ".$rooturl."code/especiales/addBoletin.php?id_control_boletin=".$id_boletin."&ver=true");							
				exit();
			}
				
				header("Location: ".$rooturl."code/indices/menu.php?".SID);
				exit();
		}
							
	}
	

 //carpeta raiz
 //$mensaje = '  mensajes entrantes ';
 //Obtenemos el combo de las sucursales
 $nomTitSucursal="XOCHIMILCO";
 $smarty->assign("VER_SUCURSAL", $_SESSION["USR"]->sucursalid);
 $smarty->assign("sucursalname", $_SESSION["USR"]->sucursalname);
//echo $mensaje."<br>";


 $smarty->assign("mensaje",$mensaje);
 $smarty->assign("id_razon_pendiente",$id_razon_pendiente);
 $smarty->assign("id_sol_pend",$id_sol_pend);
 $smarty->assign("id_boletin",$id_boletin);
 $smarty->display("login.tpl");
 //$smarty->display("mantenimiento.tpl");
 
?>
