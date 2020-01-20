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
	
	
if($accion == 1){
/*Consulta los dias de credito del cliente seleccionado
******/
  $fechRev = date("d/m/Y");
  
  
/*$sqld = "SELECT id_dia_revision FROM anderp_clientes WHERE id_cliente = ".$id_cli." AND id_sucursal= ".$_SESSION["USR"]->sucursalid;
  //echo $sql."</br>";
$resd=mysql_query($sqld) or die("Error en:\n$sqld\n\nDescripcion:".mysql_error());
$numd=mysql_num_rows($resd);
$rowd=mysql_fetch_row($resd);
  
  if($numd>0){
     $diaRevicion = $rowd[0];
  }
   mysql_free_result($resd);*/
  
 $sql = "SELECT dias_credito FROM anderp_clientes WHERE id_cliente = ".$id_cli." AND id_sucursal= ".$_SESSION["USR"]->sucursalid;
  //echo $sql."</br>";
$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:".mysql_error());
$num=mysql_num_rows($res);
$row=mysql_fetch_row($res);

    if($num >0){
	   $dias = $row[0];
	}
	else
	    $dias = 0;
		
        //si la forma de pago del cliente es Credito Tomamos Dia en q se genera la Nota de Venta y Sumamos 7 Dias
		if($dias > 0){
		     $new_fecha = suma($fechRev,$dias);
		}
		else{
		      $new_fecha = $fechRev;
		}
    

//cancelado Calculo de Fecha de Vencimiento con Dia de Revision

  /* if($dias > 0){
      if($diaRevicion == 6) //si es No Aplica
	     $new_fecha = suma_fechasNoAplica($fechRev,$dias);
	  else	
          $new_fecha = suma_fechas($fechRev,$dias,$diaRevicion);
   }
   else{
       $new_fecha = $fechRev;
   }*/
   
 mysql_free_result($res);
 echo "exito|".$new_fecha;
}//fin de accion 1	
	
//-------------------------------------------------//

if($accion == 2){

/*Consulta los dias de credito del cliente seleccionado
******/
  $fechRev = date("d/m/Y");
  $sql = "SELECT id_forma_de_pago,anderp_tipos_pago.nombre
  		  FROM anderp_clientes 
		  LEFT JOIN anderp_tipos_pago ON anderp_tipos_pago.id_tipo_pago = anderp_clientes.id_forma_de_pago
		  WHERE id_cliente = ".$id_cli." AND id_sucursal= ".$_SESSION["USR"]->sucursalid;
  //echo $sql."</br>";
$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:".mysql_error());
$num=mysql_num_rows($res);
$row=mysql_fetch_row($res);

    if($num >0){
	   echo "exito|".utf8_encode($row[1])."|".$row[0];
	}

   mysql_free_result($res);


	

}//fin accion 2
	
//---------------------------------------------------//	
function suma_fechasNoAplica($fecha,$ndias){
      if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha))
               list($dia,$mes,$año)=split("/", $fecha);
          
      if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha))
           
         list($dia,$mes,$año)=split("-",$fecha);
        $nueva = mktime(0,0,0, $mes,$dia,$año) + $ndias * 24 * 60 * 60;
        $nuevafecha=date("d/m/Y",$nueva);

   return $nuevafecha;
}

function suma($fecha,$ndias){
      if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha))
               list($dia,$mes,$año)=split("/", $fecha);
          
      if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha))  
         list($dia,$mes,$año)=split("-",$fecha);
		 
        $nueva = mktime(0,0,0, $mes,$dia,$año) + $ndias * 24 * 60 * 60;
        $nuevafecha2=date("d/m/Y",$nueva);
		
		return $nuevafecha2;

} 

function suma_fechas($fecha,$ndias,$diaRevicion)
{
    /*    
      if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha))
               list($dia,$mes,$año)=split("/", $fecha);
          
      if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha))
           
         list($dia,$mes,$año)=split("-",$fecha);
        $nueva = mktime(0,0,0, $mes,$dia,$año) + $ndias * 24 * 60 * 60;
        $nuevafecha=date("d/m/Y",$nueva);
          
 */
 $dias = array( 0=>"Domingo", 1=>"Lunes", 2=>"Martes", 3=>"Miércoles", 4=>"Jueves", 5=>"Viernes", 6=>"Sábado");
$año= date('Y');
$mes= date('m');

$xName =$dias[$diaRevicion];


$hoy = (date(w));
$cont = 0;

//echo $diaRevicion."-".$xName;
  if($hoy>$diaRevicion){
   // echo $hoy." es mayor a ".$diaRevicion." <br>";
    $complemento = ((count($dias)) - $hoy); 
   //echo $complemento;
		for($d=0;$d<count($dias);$d++){
        if($dias[$d] == $xName){
      	  //echo "<br> hoy es dia ".$dias[$hoy]." y faltan ".($cont+$complemento) .' dias para el '.$xName;
          $nuevafecha = date("d/m/Y", mktime(0, 0, 0, $mes, date("d")+($cont+$complemento), $año));

		  }else{
      	  $cont = $cont+1;
      	}
      }
		
	
	}else{
        //echo $hoy." es menor a ".$diaRevicion;
    
    	for($d=$hoy;$d<count($dias);$d++){
        if($dias[$d] == $xName){
      	  //echo "<br> hoy es dia ".$dias[$hoy]." y faltan ".$cont .' dias para el '.$xName;
          $nuevafecha = date("d/m/Y", mktime(0, 0, 0, $mes, date("d")+$cont, $año));

		  }else{
      	  $cont = $cont+1;
      	}
      }
  	 
  }


  if($nuevafecha != ''){
     $new_fwcha =  suma($nuevafecha,$ndias);
  }
  else{
    $new_fwcha =date("d/m/Y"); 
  }
 
      return ($new_fwcha); 

        
}//fin de suma fechas

	
	
	
	
	
	
/*	
 //obtenPresentaciones.php
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

*/

?>