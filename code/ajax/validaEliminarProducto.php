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
	
  if($accion==1){ //Valida si no hay una nota de venta relacionada para poder eliminar el producto
	 //validar si el producto esta relacionado con una nota de venta
	  $sql = "SELECT id_control_nota_venta,id_producto FROM anderp_notas_venta_detalles WHERE id_producto=".$id;
	  $res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:\n".mysql_error());	  
		
	  $num=mysql_num_rows($res);	
	  
	  if($num == 0){
	    die("exito|¿Desea eliminar el registro?");
	  }
	  else{
	    echo "exito|Este producto esta relacionado con la nota de venta:\n";	
	  }
	 for($i=0;$i<=$num;$i++)
		{
			$row=mysql_fetch_row($res);
			echo $row[0]."\n";
		}
  }
  
  
  if($accion==2){ //eliminar (Elimina Producto)
     $sql="DELETE FROM anderp_productos WHERE id_sucursal = 1 AND id_producto = ".$id;
	  
	  $id_prodDeta = explode("@@",$id);
	  for($i=0; $i<count($id_prodDeta); $i++){ 
	    $sql2="DELETE FROM anderp_productos_detalle WHERE id_sucursal=1 AND id_producto =".$id_prodDeta[$i];
	     echo $sql2;
		  $res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:\n".mysql_error());	  
	  }
  
  }
  

?>
