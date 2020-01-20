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
	
	$strConsulta="SELECT				  
	      rs.id_control_razon_social as 'id_razon',
		  sf.id_control_razon_social as 'No de solicitud',
		  CONCAT(uw.nombre, ' ', IF(uw.apellido_paterno IS NULL, '', uw.apellido_paterno), ' ',IF(uw.apellido_materno IS NULL, '', uw.apellido_materno)) as Cliente,
		  uw.no_cliente as Referencia,
		  DATE_FORMAT(sf.fecha_solicitud, '%d/%m/%Y') as 'Fecha Solicitud',
		  IF(sf.id_estatus_solucitud_fac = 1, DATE_FORMAT(sf.fecha_aprobacion, '%d/%m/%Y %h:%i:%s'), DATE_FORMAT(sf.fecha_rechazo, '%d/%m/%Y %h:%i:%s')),
		  esf.nombre as 'Estatus',		  
		  '-'		  
		  FROM peug_clientes_solicitudes_fac sf
		  JOIN peug_estatus_solicitudes_facturacion esf ON sf.id_estatus_solucitud_fac = esf.id_estatus_solicitud_fac
		  JOIN peug_clientes_razones_sociales rs ON sf.id_control_razon_social = rs.id_control_razon_social
		  JOIN peug_clientes_usuarios_web uw ON rs.id_control_usuario_web = uw.id_control_usuario_web		  
		  WHERE 1=1 ";
		  
	if($clientef != '')	  
	{
		$strConsulta.=" AND CONCAT(uw.nombre, ' ', IF(uw.apellido_paterno IS NULL, '', uw.apellido_paterno), ' ',IF(uw.apellido_materno IS NULL, '', uw.apellido_materno)) LIKE '%$clientef%'";
	}
	if($referenciaf != '')	  
	{
		$strConsulta.=" AND uw.no_cliente LIKE '%$referenciaf%'";
	}
	if($fecdel != '')	  
	{
		$strConsulta.=" AND sf.fecha_solicitud >= '$fecdel'";
	}
	if($fecal != '')	  
	{
		$strConsulta.=" AND sf.fecha_solicitud <= '$fecal'";
	}
	if($estatus != '-1' && $estatus != '')	  
	{
		$strConsulta.=" AND sf.id_estatus_solucitud_fac = '$estatus'";
	}
	if($solicitud != '')	  
	{
		$strConsulta.=" AND sf.id_control_razon_social = '$solicitud'";
	}
		  
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
		$num=mysql_num_rows($resultado);
		echo "exito";
		for($i=0;$i<$num;$i++)
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