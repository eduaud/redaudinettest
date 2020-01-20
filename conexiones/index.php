<?php
	php_track_vars;

	extract($_GET);
	extract($_POST);
	
///variables de conexion
	include('config.inc.php');
	
//variables declaracion
	include(INCLUDEPATH."container.inc.php");
	include("./code/general/funciones.php");

	
	
	
	if (isset($_POST["login"])) 
	{
		
		//valida login y password	
		$row =array();
		$login = $_POST["login"];
		$password = (isset($_POST["password"]) ? $_POST["password"] : "");
		$encrypted_pwd = (isset($_POST["encrypted_pwd"]) ? base64_encode($_POST["encrypted_pwd"]) : "");
		$mensaje=$encrypted_pwd;
		$conn = connect_database($session_long); 
		//password encriptado
		if (!$encrypted_pwd) { $smarty->assign("encrypt_pwd","hex_md5"); }
		
		$sql = "SELECT * FROM sys_usuarios WHERE login='".$login."'";
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
			$sql2 = "SELECT 
							id_usuario,
							nombres,
							concat(apellido_paterno,' ', apellido_materno) as apellidos,
							sys_usuarios.email,
							login							 
					FROM sys_usuarios					
					WHERE pass='".$encrypted_pwd."' AND login='".$login."'";
			
			//vemso que corresponda al del login seleccionado
			$resPas=obtenFilaQuery($sql2);
			//if (($resPas = $conn->GetRow($sql2))===false) exit(showSQLerror($sql2, $conn->ErrorMsg(), __LINE__, __FILE__));
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
		
				
		//si esta vacio el arreglo con los datos del usuario 
		//llenamos la variable de sesion $_SESSION
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
			$_SESSION["USR"]->login = $login;
			$_SESSION["USR"]->password = $password;
			
	
			
			$_SESSION["USR"]->modules = array();
			//get_user_groups
			//funciones que se activaran para saber a que grupo pertenece el ususario
			$_SESSION["USR"]->groups = obtenerGruposUsuario();
			
			//grupos a los que pertenece
			//$_SESSION["USR"]->groups_names = get_user_groups_names();
			//navegador que detencta
			$_SESSION["USR"]->browser = browser_detect();
			
			//bitacora
			grabaBitacora('1','','0','0',$_SESSION["USR"]->userid,'0','','');
			
						
			header("Location: ".$rooturl."code/indices/menu.php?".SID);			

			exit();
			// $mensaje="conectado con password 3";
			
			//-----------------------------------------------------
			
		}
		// Login inv&aacute;lido
		else 
		{
			unset($_SESSION);
			/*$_SESSION = array();
			$_SESSION['USR']=new USR();*/
			//$smarty->assign('loginerrortext', Lang::getLanguageString("loginerror"));
		}
	}
	else
	{
		
		if (isset($_SESSION['USR']) && isset($_SESSION['USR']->userid ) && !empty($_SESSION["USR"]->userid) && !isset($_POST["login"]) &&  $_SESSION["USR"]->userid <>'')
		{
				//echo $_SESSION["USR"]->userid;
				
				header("Location: ".$rooturl."code/indices/menu.php?".SID);
				exit();
		}
							
	}
	

 //carpeta raiz
 //$mensaje = '  mensajes entrantes ';
 //Obtenemos el combo de las sucursales

	
 $smarty->assign("mensaje",$mensaje);
 $smarty->display("login.tpl");
 
?>


