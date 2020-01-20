<?php
	php_track_vars;

	extract($_GET);
	extract($_POST);
	
//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../conect.php");
	include("../../code/general/funciones.php");
	
	if($opcion=='1')
	{
		$strConsulta="SELECT id_cliente,
							id_tipo_cliente, 
							cobrar_flete, 
							cobrar_montaje, 
							cobrar_viaticos, 
							cobrar_anticipo, 
							aplica_penalizacion, 
							 cobrar_danios_faltantes,
							  opcion_subarrendar, 
							  solicitar_orden_produccion,
							 solicitar_orden_compra,
							  id_vendedor_asignado,
							   id_condicion_pago,
							 dias_credito,
							 cliente_recoje, 
							 cliente_entrega,
							  monto_garantia,
							   id_almacen_recoje,
							    nombre_razon_social, 
								rfc,
     						 calle,
							  numero_exterior,
							   numero_interior, 
							   colonia, 
							   id_ciudad, 
							   delegacion_municipio, 
							   codigo_postal, 
							 tipo_documento, id_estado FROM rac_clientes where id_cliente='".$id."'";
	}
	/*elseif($opcion=='2')
	{
	}*/
	
		
	$res=mysql_query($strConsulta) or die("Error en:\n$strConsulta\n\nDescripcion:".mysql_error());	
	$num=mysql_num_rows($res);
	echo "exito";
	for($i=0;$i<$num;$i++)
	{
		echo "|";
		$row=mysql_fetch_row($res);
		$strRow=implode('|',$row);
		echo utf8_encode($strRow);
	}
	mysql_free_result($res);
	
	
?>