<?php
php_track_vars;

	extract($_GET);
	extract($_POST);

//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../../conect.php");
	include("../../../code/general/funciones.php");
	
	
if($accion == 1){


  $strConsulta="SELECT DISTINCT  anderp_notas_venta.id_cliente,razon_social
		        FROM anderp_notas_venta
				LEFT JOIN anderp_clientes ON anderp_clientes.id_cliente=anderp_notas_venta.id_cliente 
				WHERE anderp_notas_venta.id_sucursal=".$_SESSION["USR"]->sucursalid;
				
				
	$strConsulta="SELECT id_cliente, CONCAT(razon_social,' / ',nombre_comercial) as razon_social
FROM anderp_clientes WHERE (razon_social LIKE '%$llave%' OR id_cliente LIKE '%$llave%') AND activo=1 AND id_sucursal=".$_SESSION["USR"]->sucursalid;

				
		//echo $strConsulta;
				
		$res=mysql_query($strConsulta) or die("Error en:\n$strConsulta\n\nDescripcion:".mysql_error());
		
		$arrDatos=mysql_fetch_row($res);
		
		if($arrDatos[0]!="")
		{
		
			$res=mysql_query($strConsulta) or die("Error en:\n$strConsulta\n\nDescripcion:".mysql_error());			
			$num=mysql_num_rows($res);
		}
		
	
	
	echo "exito|".$num;
	for($i=0;$i<$num;$i++)
	{
		$row=mysql_fetch_row($res);
		echo utf8_encode("|".$row[0]."~".$row[1]);
	}
	
}//fin de accion uno	


//campos buscador para grid
if($accion == 2){
				
	$strConsulta="SELECT id_cliente, CONCAT(razon_social,' / ',nombre_comercial) as razon_social
FROM anderp_clientes WHERE (razon_social LIKE '%".$llave."%' OR id_cliente LIKE '%".$llave."%') AND activo=1 AND id_sucursal=".$_SESSION["USR"]->sucursalid;

				
		//echo $strConsulta;
				
		$res=mysql_query($strConsulta) or die("Error en:\n$strConsulta\n\nDescripcion:".mysql_error());
		
		$arrDatos=mysql_fetch_row($res);
		
		if($arrDatos[0]!="")
		{
		
			$res=mysql_query($strConsulta) or die("Error en:\n$strConsulta\n\nDescripcion:".mysql_error());			
			$num=mysql_num_rows($res);
		}
		
	
	
	echo "exito|".$num;
	for($i=0;$i<$num;$i++)
	{
		$row=mysql_fetch_row($res);
	    // echo utf8_encode("|".$row[0]."~".$row[1]);
		  echo utf8_encode("|".$row[1].":".$row[0]);
		//echo utf8_encode("|".$row[1]);
	}
	
}//fin de accion dos

if($accion == 3){//pantalla registro de cobros
    	$strConsulta="SELECT anderp_notas_venta.id_control_nota_venta,anderp_notas_venta.id_nota_venta
                      FROM anderp_notas_venta 
                      JOIN anderp_cuentas_por_cobrar ON anderp_cuentas_por_cobrar.id_control_nota_venta = anderp_notas_venta.id_control_nota_venta
					  WHERE saldada=0 AND anderp_notas_venta.id_cliente =".$idCli." AND anderp_notas_venta.id_sucursal=".$_SESSION["USR"]->sucursalid;
		
		//echo $strConsulta;				
		$res=mysql_query($strConsulta) or die("Error en:\n$strConsulta\n\nDescripcion:".mysql_error());
		$arrDatos=mysql_fetch_row($res);
		if($arrDatos[0]!="")
		{
			$res=mysql_query($strConsulta) or die("Error en:\n$strConsulta\n\nDescripcion:".mysql_error());			
			$num=mysql_num_rows($res);
		}
	$cade='';	
	//echo "exito|".$num;
	for($i=0;$i<$num;$i++)
	{
		$row=mysql_fetch_row($res);
	    $cade .= utf8_encode("|".$row[0]."~".$row[1]);
	}	 
    echo "exito|".$num.$cade;
}//fin accion 3


///valida exista usuario
//campos buscador para grid
if($accion == 4){
				
	$strConsulta="SELECT id_cliente, CONCAT(razon_social,' / ',nombre_comercial) as razon_social
FROM anderp_clientes WHERE  id_cliente = ".$idcli." AND activo=1 AND id_sucursal=".$_SESSION["USR"]->sucursalid;

				
		//echo $strConsulta;
				
		$res=mysql_query($strConsulta) or die("Error en:\n$strConsulta\n\nDescripcion:".mysql_error());
		
		$arrDatos=mysql_fetch_row($res);
		
		if($arrDatos[0]!="")
		{
		
			$res=mysql_query($strConsulta) or die("Error en:\n$strConsulta\n\nDescripcion:".mysql_error());			
			$num=mysql_num_rows($res);
		}
		
	if($num>0){
	  	echo "exito|".$num;
		$row=mysql_fetch_row($res);
	     echo utf8_encode("|".$row[0]."~".$row[1]);
	}
	else echo "Ingrese un cliente Valido.";
	

	
}//fin de accion 4


?>