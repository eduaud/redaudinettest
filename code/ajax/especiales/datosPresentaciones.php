<?php 
	php_track_vars;

	extract($_GET);
	extract($_POST);	
	
	/*
	$ruta = 'C:/ServidorWeb_S&W/newart/';
	include($ruta.'config.inc.php');
	//variables declaracion
	include(INCLUDEPATH."container.inc.php");
	include(INCLUDEPATH."module_exec.inc.php");	*/
	
	include("../../../conect.php");
    
	
   //cargamos valores para grid
   if($k != ''){ 
       $condicion = " LEFT JOIN anderp_productos_tipos_detalle b ON a.id_presentacion = b.id_presentacion 
WHERE a.activo=1 AND b.id_producto_tipo = ".$k; 
       $option = "IF(b.activo=1,1,0) as Activo";
	}
	else {
	   $condicion = " WHERE a.activo=1";
	   $option = "IF(a.activo=1,0,0) as Activo";
    }
   
   $strConsulta="SELECT a.id_presentacion,a.nombre,".$option." 
FROM anderp_presentaciones a ".$condicion;
  //echo $strConsulta;
    //$orderGRC = 'nombre';
	
	if(isset($orderGRC))
		$strConsulta.=" ORDER BY ".$orderGRC." $sentidoOr";
		
		
	//Ponemos el inicio y fin que nos marca el grid
	if(isset($ini) && isset($fin))
	{
		//Conseguimos el n&uacute;mero de datos real
		$resultado=mysql_query($strConsulta) or die("Consulta:\n$strConsulta\n\nDescripcion:\n".mysql_error());
		$numtotal=mysql_num_rows($resultado);	
		
		//A&ntilde;adimos el limit para el paginador
		if($fin!="-1")
			$strConsulta.=" LIMIT $ini, $fin";
	}	
    //die($strConsulta);
	//die("Error en:<br><i>$strConsulta</i><br><br>Descripcion:<br><b>".mysql_error()."</b>");
	$resultado=mysql_query($strConsulta) or die("Consulta:\n$strConsulta\n\nDescripcion:\n".mysql_error());
	$num2=mysql_num_rows($resultado);
		
	echo "exito";
for($i=0;$i<$num2;$i++)
{
	$row=mysql_fetch_row($resultado);
	echo "|";
	for($j=0;$j<sizeof($row);$j++)
	{	
		if($j > 0)
			echo "~";
		echo utf8_encode($row[$j]);
	}	
}

//Enviamos en el ultimo dato los datos del listado, numero de datos y datos que se muestran
if(isset($ini) && isset($fin))
	echo "|$numtotal~$num";

?>
