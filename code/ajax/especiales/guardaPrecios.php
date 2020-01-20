<?php 
	php_track_vars;

    extract($_GET);
	extract($_POST);
	
//CONECCION Y PERMISOS A LA BASE DE DATOS
 	include("../../../conect.php");
	include("../../../code/general/funciones.php");
	
	
	
if($tabla == 'anderp_productos_detalle'){	
	$bandera=0;
	$IDSucursal=$_SESSION["USR"]->sucursalid;
	if($cadena != ''){//valores de id_producto_detalle que cambiaron sus precios
	   $arrCadena = explode("@@",$cadena);
	} 
	      
if($cadena != ''){		  
	for($i=0; $i<$numdatos; $i++){
	   $sqlUpdate = "UPDATE anderp_productos_detalle SET precio_unidad_venta = ".$dato7[$i].",precio_minimo_unidad_venta=".$dato8[$i].",precio_partida_unidad_venta=".$dato9[$i].",precio_minimo_partida_unidad_venta=".$dato10[$i].",unidades_minimas_partida=".$dato11[$i].",fecha_y_hora_precio=NOW(),id_usuario_modifico=".$_SESSION["USR"]->userid."  WHERE id_producto_detalle=".$dato1[$i];   
	   //echo $sqlUpdate."\n";
	  mysql_query($sqlUpdate) or rollback('sqlUpdate',mysql_error(),mysql_errno(),$sqlUpdate);
	   $bandera =1;
	  			
	}//fin for i
	
	//insertamos los cambios de precios a la bitacora
	for($k=0; $k<count($arrCadena); $k++){
	
	   	for($j=0; $j<$numdatos; $j++){
		  if($dato1[$j] == $arrCadena[$k]){
	
		  //insertamos dato a historico
$sqlConsulta="SELECT id_producto,id_producto_tipo,id_presentacion FROM anderp_productos_detalle WHERE id_producto_detalle=".$dato1[$j];
  //echo $sqlConsulta;
  
    $resultado = mysql_query($sqlConsulta) or die("Error en:\n$sqlConsulta\n\nDescripcion:".mysql_error());
	$num = mysql_num_rows($resultado);
	
	if($num > 0){
	   	for($i=0;$i<$num;$i++)
		{
			$row=mysql_fetch_row($resultado);
			$id_producto = $row[0];
			$id_producto_tipo = $row[1];
			$id_presentacion = $row[2];
		}
		mysql_free_result($resultado);
	
	
	}
	    $sqlInsert = "INSERT INTO anderp_productos_precios_historico (id_control_historico_precio,id_producto,id_producto_tipo,id_presentacion,precio_unidad_venta,precio_minimo_unidad_venta,precio_partida_unidad_venta,precio_minimo_partida_unidad_venta,unidades_minimas_partida,fecha_registro,id_sucursal,id_usuario_modifico) VALUES (NULL,".$id_producto.",".$id_producto_tipo.",".$id_presentacion.",".$dato7[$j].",".$dato8[$j].",".$dato9[$j].",".$dato10[$j].",".$dato11[$j].",NOW(),".$IDSucursal.",".$_SESSION["USR"]->userid.")";
		
		//echo $sql;
		 mysql_query($sqlInsert) or rollback('sqlInsert',mysql_error(),mysql_errno(),$sqlInsert);
		 
		}
		
 	 }//fin for j
	
	}//fin for k
	
	
	
	


   echo "exito|$bandera";
	
}
else{
    echo "Noexito|Sin datos a Actualizar.";
}	
	
}					
						
?>