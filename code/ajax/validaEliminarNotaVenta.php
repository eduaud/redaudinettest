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
	
  if($accion==1){ 
     //Valida si no la nota de venta Tiene Relacion con una Factura
       $bandera = 0; 
	 
	  $sql = "SELECT id_control_nota_venta,id_control_factura FROM anderp_notas_venta WHERE id_control_nota_venta=".$id_cnv." AND id_nota_venta='".$id_nv."'";
	  $res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:\n".mysql_error());	  
		
	  $num=mysql_num_rows($res);	
	  $row=mysql_fetch_row($res);
	  mysql_free_result($res);
	  // echo $row[0]."-".$row[1];  
	   
	  if($num == 0){
	    $bandera=1;
	    $valida1 = "exito|1";
	  }
	  else{
	    if($row[1] == 0){
			$valida1 = "exito|1";
		}
		else{
	       $valida1 =  "exito|No se puede Eliminar esta Nota de Venta porque esta relacionada con la Factura:\n";
		   	 for($i=0;$i<$num;$i++)
		     { 
			   $valida1 .= $row[1]."\n ";
		     }	
		 }
		 $bandera = 1;
	  }

	

	  if($bandera == 1){
	     //si no hay Factura Relacionada a la Nota de Venta 
		 //Validamos la Cuenta por cobrar tiene Cobros registrados 
		 
		 $sql = "SELECT b.id_control_cxc_detalle,a.id_control_cxc
		 FROM anderp_cuentas_por_cobrar a
		 LEFT JOIN anderp_cuentas_por_cobrar_detalle b ON b.id_control_cxc = a.id_control_cxc
		 WHERE a.id_control_nota_venta = ".$id_cnv." AND a.id_nota_venta='".$id_nv."' and  b.activo=1";
		 
		  $result=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:\n".mysql_error());	  
		
	      $num=mysql_num_rows($result);	
	      $row=mysql_fetch_row($result);
	      mysql_free_result($result);
		 
		 
		 if($num == 0 || $row[0] == ''){ //si no hay registros en detalle de CxC se puede eliminar Nota de VEnta
			die($valida1);
		 }
		 else{
		   if($row[0] != '')
		      die("exito|No se puede Eliminar esta Nota de Venta porque esta relacionada con la Cuenta por Cobrar:\n".$row[1]." que tiene Cobros Asignados.");
			  
		 }
	  }	
		
		
  }
  
  

  

?>

