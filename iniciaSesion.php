<?php

	php_track_vars;
	//include("conect.php");
	
	extract($_GET);


	include('config.inc.php');
	include(INCLUDEPATH."container.inc.php");
	include("./code/general/funciones.php");
	
	if($guarda == 'SI')
	{
		$mensaje="";		
		$conn=@mysql_connect($dbhost,$dbuser,$dbpassword);
		@mysql_select_db($dbname,$conn);
		
		if(!$conn)
			die("Error al conectar con la base de datos");
			
		$login = $nusuario;
		$password = $contrasena;
		$encrypted_pwd = base64_encode($contrasena);	
		$id_sucursal = $sucursal;
		
		$sql = "SELECT 
							id_usuario,
							nombres,
							concat(apellido_paterno,' ', apellido_materno) as apellidos,
							sys_usuarios.email,
							login,
							id_grupo
					FROM sys_usuarios				
					WHERE pass='".$encrypted_pwd."' AND login='".$login."'";
							 
		$resPas=obtenFilaQuery($sql);
		
		
		if (count($resPas)==0) 
		{
			$mensaje="Password Inv&aacute;lido";
		}
		else
		{	
			$row = get_user_info($resPas["id_usuario"]);
			
			if(!is_array($row))
				die("No se encontro al usuario.");
				
				
			
			
			$_SESSION = array();
			$_SESSION["USR"]= new USR();
			
			///hora y fecha de acceso que actualizaremos en la base de datos, en el acceso del usuario
			$_SESSION["lastin"] = time();
			$_SESSION["tiempo_sesion"]=time();
			
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
			$_SESSION["USR"]->idGrupo = $row["id_grupo"];
			
			$_SESSION["USR"]->sucursalname= get_name_sucursal($row["id_sucursal"]);
			$_SESSION["USR"]->id_sucursalprefijo= get_prefijo_sucursal($row["id_sucursal"]);
			
			
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
			
			//bitacora
			grabaBitacora('1','','0','0',$_SESSION["USR"]->userid,'0','','');
			
			die("<script>window.close();</script>");
		}	
			
	}

?>
	<html >
<title>Inicio de sesi&oacute;n</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    
    <link rel="stylesheet" type="text/css" href="<?php echo $rooturl; ?>css/pro_dropdown_2.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $rooturl; ?>css/estilos.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $rooturl; ?>css/gridSW.css"/>    
   
   
    
    <!-- Librerias para el menu pro_dropdown-->
    <script src="<?php echo $rooturl; ?>js/pro_dropdown_2/stuHover.js" type="text/javascript"></script>	
    
    <!-- Librerias para el grid-->
    <script language="javascript" src="<?php echo $rooturl; ?>js/grid/RedCatGrid.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?php echo $rooturl; ?>js/grid/yahoo.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?php echo $rooturl; ?>js/grid/event.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?php echo $rooturl; ?>js/grid/dom.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?php echo $rooturl; ?>js/grid/fix.js"></script>
    <script type="text/javascript" src="<?php echo $rooturl; ?>js/calendar.js"></script>
    <script type="text/javascript" src="<?php echo $rooturl; ?>js/calendar-es.js"></script>
    <script type="text/javascript" src="<?php echo $rooturl; ?>js/calendar-setup.js"></script>
    <script language="javascript" src="<?php echo $rooturl; ?>js/funciones.js"></script>
    
    <head></head>
<body bgcolor="#FFFFFF">
<div class="contenido" id="contenido">

<div class="titulo-icono" id="titulo-icono">
	     <div class="titulo" id="titulo">Login</div>
</div> 


 <div class="tabla" id="tabla" style="margin-left:10px;">
 <form name="forma1" action="" method="get"> 
 <input type="hidden" name="guarda" value="SI" />
 <input type="hidden" name="username" value="<?php echo $username; ?>">
  <input type="hidden" name="sucursal" value="<?php echo $sucursal; ?>">
 <table>
 
 	<?php
			if($mensaje != "")
			{
		?>
    <tr>
    	<td colspan="2" align="left">
        	<font face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000" size="3"><b><? echo $mensaje; ?></b></font>
        </td>
    </tr>    
    <?php
		}
	?>
    
 	<tr>
    	
        
        	
    	<td class="nom_campo">Usuario:</td>
        <td>
        	<input type="text" class="campos_req" name="nusuario" size="30" onKeyDown="teclaEnter(event)" value="<?php echo $username; ?>"/>
            <!--<input type="hidden" name="nusuario" value="<?php echo $username; ?>">-->
        </td>
    </tr>
    <tr>
    	<td class="nom_campo">Contrase&ntilde;a:</td>
        <td>
        	<input type="password" class="campos_req" name="contrasena" value="" size="30" onKeyDown="teclaEnter(event)"/>
        </td>
    </tr>
    <tr>    	
        <td colspan="2" align="right">
        	<input type="button" name="acpt" value=" Validar " class="boton" onClick="valida()"/>
        </td>
    </tr>
    
 </table> 	
 <br />
 </form>
 </div> 

 <script language="javascript">
 	document.forma1.contrasena.focus();
 
	//Funcion para detectar enters
	function teclaEnter(eve)
	{
		var key=0;	
		key=(eve.which) ? eve.which : eve.keyCode;	
		
		if(key == 13)
			valida();	
	}
	
	function valida()
	{
		var f=document.forma1;
		if(f.contrasena.value.length == 0)
		{
			alert('Es necesario que ingrese su contraseña');
			f.contrasena.focus();
			return false;
		}
		else
			f.submit();
	}
	
 </script>
  </form>

</div>
</body>
</html>