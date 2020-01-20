<?php
php_track_vars;

	/*Conseguimos los datos del post
	
		tabla -> Nombre de la tabla que se esta accediendo
		id -> id del campo a utilizar
		accion -> accion a realizar, los valores pueden ser: [1 => Modificar, 2 => Ver, 3 => Eliminar o Cancelar]
	*/
	extract($_GET);
	extract($_POST);
	
//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../conect.php");
	
	
if($accion == 1){	//consulta las presentaciones para el tipo de producto seleccionado
	
	$sql = "SELECT a.id_presentacion,b.nombre 
FROM anderp_productos_tipos_detalle a 
LEFT JOIN anderp_presentaciones b ON a.id_presentacion = b.id_presentacion
WHERE a.activo=1 AND a.id_producto_tipo =".$id_prod_tipo;

//echo $sql."</br>";
$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:".mysql_error());
$num=mysql_num_rows($res);

    echo "exito|".$num;
	for($i=0;$i<$num;$i++)
	{
		$row=mysql_fetch_row($res);
		echo utf8_encode("|".$row[0]."~".$row[1]);
	}
	
}//fin de accion 1

if($accion == 2){	//consulta las direcciones del cliente seleccionado en Notas de Venta
	
	$sql = "SELECT id_cliente_direccion,CONCAT(calle,' No. ',no_ext,' #',IF(no_int!='',no_int,0),' Col:',IF(colonia!='',colonia,0),' Del:',IF(delegacion_municipio!='',delegacion_municipio,0)) as Direccion FROM anderp_clientes_direcciones WHERE activo=1 AND id_cliente=".$id;

//echo $sql."</br>";
$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:".mysql_error());
$num=mysql_num_rows($res);

    echo "exito|".$num;
	for($i=0;$i<$num;$i++)
	{
		$row=mysql_fetch_row($res);
		echo utf8_encode("|".$row[0]."~".$row[1]);
	}
	
}//fin de accion 2

if($accion == 3){	//consulta los vendedores asociados a la ruta seleccionada en Notas de Venta
	
	$sql = "SELECT a.id_vendedor,b.nombre 
FROM anderp_rutas a 
LEFT JOIN anderp_vendedores b ON a.id_vendedor = b.id_vendedor 
WHERE a.id_ruta=".$id;

//echo $sql."</br>";
$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:".mysql_error());
$num=mysql_num_rows($res);

    echo "exito|".$num;
	for($i=0;$i<$num;$i++)
	{
		$row=mysql_fetch_row($res);
		echo utf8_encode("|".$row[0]."~".$row[1]);
	}
	
}//fin de accion 3

if($accion == 33){	//consulta los vendedores asociados a la ruta seleccionada en Notas de Venta
	
	/*$sql = "SELECT a.id_vendedor,b.nombre 
FROM anderp_rutas a 
LEFT JOIN anderp_vendedores b ON a.id_vendedor = b.id_vendedor 
WHERE a.id_ruta=".$id;*/

$sql = "SELECT if( MAX( consecutivo ) IS NULL , 1, MAX( consecutivo ) +1 ) as id
	FROM anderp_notas_venta
	WHERE id_ruta=".$id;


//echo $sql."</br>";
$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:".mysql_error());
$num=mysql_num_rows($res);

    echo "exito|";
	for($i=0;$i<$num;$i++)
	{
		$row=mysql_fetch_row($res);
		echo utf8_encode($row[0]);
	}
	
}//fin de accion 33


if($accion == 333){	//consulta los vendedores asociados a la ruta seleccionada en Notas de Venta

	$sql = "SELECT DATE_FORMAT(fecha_y_hora, '%d/%m/%Y') 
		FROM anderp_notas_venta 
		WHERE id_control_nota_venta = (SELECT max( id_control_nota_venta ) AS id
		FROM anderp_notas_venta WHERE id_sucursal = ".$id." )" ;



	$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:".mysql_error());
	$num=mysql_num_rows($res);
	

	
    echo "exito|";
	
	if($num==0)
	{
		echo date("d/m/Y");
		die();
	}
	
	for($i=0;$i<$num;$i++)
	{
		$row=mysql_fetch_row($res);
		echo utf8_encode($row[0]);
	}
	
}//fin de accion 333


if($accion == 4){ //obtiene el tipo de producto de un producto seleccionado	
   
    $IDsucursal =  $_SESSION["USR"]->sucursalid;
	
    $sql="SELECT 
a.id_producto_tipo,
a.nombre 
FROM anderp_productos_tipos a
LEFT JOIN anderp_productos b ON b.id_producto_tipo_default = a.id_producto_tipo
WHERE( a.activo = 1 AND b.id_sucursal= ".$IDsucursal.") AND id_producto=".$id_prod;
$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:".mysql_error());
$num=mysql_num_rows($res);
    echo "exito|".$num;
	for($i=0;$i<$num;$i++)
	{
		$row=mysql_fetch_row($res);
		echo utf8_encode("|".$row[0]."~".$row[1]);
	}
	
}

if($accion == 5){
/*Obtiene los precios (Precion x Unidad de Venta, Precio Minimo x Unidad de Venta,Precio Partida por Unidad de Venta,Precio Minimo de Partida x Unidad de Venta) del producto seleccionado en Pantalla Notas de Venta
***/
    $IDsucursal =  $_SESSION["USR"]->sucursalid;
	
		$sql2 = "SELECT tara 
FROM anderp_presentaciones 
WHERE id_presentacion = ".$id_prod_pres;
$res2=mysql_query($sql2) or die("Error en:\n$sql2\n\nDescripcion:".mysql_error());
$num2=mysql_num_rows($res2);
   if($num2 >0){
	  $row2 = mysql_fetch_array($res2);
	  $vtara = $row2[0]; 
   }
   else
     $vtara=0;
	
	 $sql = "SELECT b.precio_unidad_venta,b.precio_minimo_unidad_venta,b.precio_partida_unidad_venta,b.precio_minimo_partida_unidad_venta 
FROM anderp_productos a
LEFT JOIN anderp_productos_detalle b ON a.id_producto=b.id_producto AND b.id_sucursal=".$IDsucursal."
WHERE a.id_producto= ".$id_prod." AND id_producto_tipo = ".$id_prod_tipo." AND b.id_presentacion=".$id_prod_pres." AND a.id_sucursal=".$IDsucursal;
 //echo $sql;
    $res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:".mysql_error());
   $num=mysql_num_rows($res);
   
    if($num >0){
	  while($row = mysql_fetch_array($res)){
	      $arrPrecios[count($arrPrecios)] = $row[0]."@@".$row[1]."@@".$row[2]."@@".$row[3];
	  }
      mysql_free_result($res);
	  
	 $cade = implode(",",$arrPrecios);
   }
   
	
   echo "exito|$cade|".$vtara;


}

if($accion==6){ 
/*Pantalla Nota de Venta y Pantalla Facturas
obtiene la unidad de venta del producto seleccionado en el grid de productos
*****/
    $IDsucursal =  $_SESSION["USR"]->sucursalid;

   $sql = "SELECT a.id_unidad_venta,c.nombre 
FROM anderp_presentaciones a 
LEFT JOIN anderp_productos_detalle b ON b.id_presentacion = a.id_presentacion
LEFT JOIN anderp_unidadventa c ON c.id_unidad_venta = a.id_unidad_venta
WHERE a.id_presentacion=".$id_prod_pres." AND b.id_producto =".$id_prod." AND id_sucursal=".$IDsucursal;
   //echo $sql;
   $res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:".mysql_error());
   $num=mysql_num_rows($res);
   $row = mysql_fetch_array($res);
    if($num >0){
      $unidad_venta = $row[0];
   }
   else{
      $unidad_venta =0;
   }
   echo "exito|$unidad_venta";
   

}

if($accion==7){ //obtiene el precio deseable del producto seleccionado
 
     $IDsucursal =  $_SESSION["USR"]->sucursalid;
	
	 $sql = "SELECT b.precio_minimo_unidad_venta 
FROM anderp_productos a
LEFT JOIN anderp_productos_detalle b ON a.id_producto=b.id_producto AND b.id_sucursal=".$IDsucursal."
WHERE a.id_producto= ".$id_prod." AND id_producto_tipo = ".$id_prod_tipo." AND b.id_presentacion=".$id_prod_pres." AND a.id_sucursal=".$IDsucursal;
 //echo $sql;
    $res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:".mysql_error());
   $num=mysql_num_rows($res);
   $row = mysql_fetch_array($res);
    if($num >0){
      $precio_minimo_uv = $row[0];
   }
   else{
      $precio_minimo_uv =0;
   }
   echo "exito|$precio_minimo_uv";
 
}


if($accion == 8){	//consulta tipo de producto seleccionado en GRID Notas de Venta
	 $IDsucursal =  $_SESSION["USR"]->sucursalid;
	$sql = "SELECT a.id_producto_tipo,a.nombre 
            FROM anderp_productos_tipos a
            LEFT JOIN anderp_productos b ON b.id_producto_tipo_default = a.id_producto_tipo
            WHERE a.activo = 1 AND b.id_sucursal=".$IDsucursal." AND b.id_producto=".$id_prod;

//echo $sql."</br>";
$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:".mysql_error());
$num=mysql_num_rows($res);

    echo "exito|".$num;
	for($i=0;$i<$num;$i++)
	{
		$row=mysql_fetch_row($res);
		echo utf8_encode("|".$row[0]."~".$row[1]);
	}
	
}//fin de accion 2


/*Calculo de Tara para Notas de Venta*/
if($accion == 9){
	$sql = "SELECT tara 
FROM anderp_presentaciones 
WHERE id_presentacion = ".$idPresentacion;
$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:".mysql_error());
$num=mysql_num_rows($res);

    echo "exito|".$num;
	for($i=0;$i<$num;$i++)
	{
		$row=mysql_fetch_row($res);
		echo utf8_encode("|".$row[0]);
	}
	mysql_free_result($res);


	
}

?>