<?php

	php_track_vars;

	
	extract($_GET);
	extract($_POST);
	
	//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../conect.php");
	

if($accion == 1){ //valida si la clave de la nota de venta existe	
	
   $getValor = explode("-",$id_nota_venta);	
   $v_sucursal = $getValor[0];
   $v_ruta = $getValor[1];
   $v_folio = $getValor[2];
   
   
	$sql="SELECT
	      id_nota_venta
		  FROM anderp_notas_venta
		  WHERE id_nota_venta = '".$id_nota_venta."' AND id_sucursal =".$_SESSION["USR"]->sucursalid;
		  
	$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripción:\n".mysql_error());	  
	if(mysql_num_rows($res) > 0)
	{
		$row=mysql_fetch_row($res);
		$mens = "Ya Existe el Folio de Nota de Venta,Verifique.";
		echo "exito|".$mens;
	}
	
		
		
}	

if($accion == 2){
 /*Valida si el producto de ya existe en la base
 *****/
   
   $IdSucursal = $_SESSION["USR"]->sucursalid;
   $sql = "SELECT a.nombre,b.nombre as 'Tipo' 
   FROM anderp_productos a
   LEFT JOIN anderp_productos_tipos b ON a.id_producto_tipo_default=b.id_producto_tipo
   WHERE a.id_sucursal=".$IdSucursal." AND a.activo=1 AND a.id_producto_tipo_default=".$id_tipo_prod." AND a.nombre='".$name_prod."' AND a.id_producto <> ".$id_prod;
   
   
   $res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripción:\n".mysql_error());	  
	if(mysql_num_rows($res) > 0)
	{
		$row=mysql_fetch_row($res);
		die("exito|Ya Existe el Producto para el mismo Tipo ".$row[1]." ,Verifique.");
	}
	else{
	   die("No existe este producto.");
	}
   
 
}//fin if accion 2


if($accion == 3){
 /*Valida si el Tipo de producto de ya existe en la base
 *****/
   
   $IdSucursal = $_SESSION["USR"]->sucursalid;
   
   $sql = "SELECT a.id_producto_tipo,a.nombre as 'Tipo' 
   FROM anderp_productos_tipos a
   WHERE  a.activo=1 AND a.nombre='".$name_prod."' AND a.id_producto_tipo <> ".$id_tipo_prod;
   
   
   $res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripción:\n".mysql_error());	  
	if(mysql_num_rows($res) > 0)
	{
		$row=mysql_fetch_row($res);
		die("exito|Ya Existe el Tipo de Producto, Verifique.");
	}
	else{
	   die("No existe este producto.");
	}
   
 
}//fin if accion 3	


if($accion == 4){
 /*Valida si el Tipo de presentacion ya existe en la base
 *****/
   
   $IdSucursal = $_SESSION["USR"]->sucursalid;
   
   $sql = "SELECT a.id_presentacion,a.nombre as 'Tipo' 
   FROM anderp_presentaciones a
   WHERE  a.activo=1 AND a.nombre='".$name_present."' AND a.id_presentacion <> ".$id_presentacion;
   
   
   $res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripción:\n".mysql_error());	  
	if(mysql_num_rows($res) > 0)
	{
		$row=mysql_fetch_row($res);
		echo utf8_decode("exito|Ya Existe el Tipo de Presentación, Verifique.");
	}
	else{
	   echo utf8_decode("No existe este producto.");
	}
   
 
}//fin if accion 4

?>