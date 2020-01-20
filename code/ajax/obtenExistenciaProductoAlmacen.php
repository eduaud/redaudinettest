<?php
	php_track_vars;

	extract($_GET);
	extract($_POST);
	
//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../conect.php");
	include("../../code/general/funciones.php");
	
	//  -  id
	
	if($accion=='1')
	{
		//var aux = ajaxR("../ajax/obtenExistenciaProductoAlmacen.php?accion=1&al="+id_prod_present+"&pto="+id_prod_present);
  
		$strConsulta="SELECT  if(sum(signo * cantidad) is null,0,sum(signo * cantidad)) as existencia FROM na_movimientos_almacen_detalle
					left join na_movimientos_almacen on na_movimientos_almacen_detalle.id_control_movimiento=na_movimientos_almacen.id_control_movimiento
					where no_modificable =1 and id_almacen = '".$al."' and id_articulo='".$pto."'";
					
		//echo $strConsulta;
	}
	
	
	$res=mysql_query($strConsulta) or die("Error en:\n$strConsulta\n\nDescripcion:".mysql_error());	
	$num=mysql_num_rows($res);
	echo "exito";
	for($i=0;$i<$num;$i++)
	{
		
		$row=mysql_fetch_row($res);
		echo "|".$row[0];
	}
	mysql_free_result($res);
	
	
?>