<?php
	php_track_vars;

    extract($_GET);
	extract($_POST);
	
//CONECCION Y PERMISOS A LA BASE DE DATOS
 	include("../../../conect.php");
	

	
	if($tipo == 1)
	{		
		$sql="SELECT
		      nc.ID_Nota_de_crédito AS 'nota',
			  Total-IF(SUM(monto) IS NULL,0,SUM(Monto)) AS 'disponible'
			  FROM `mgw_geg_notas_de_credito` nc
			  LEFT JOIN mgw_geg_cuentas_por_cobrar_detalle cxcd ON (nc.ID_Nota_de_crédito=cxcd.Documento AND cxcd.ID_Tipo_de_cobro=4 AND cxcd.ID_Estatus_cobro=1)
			  WHERE Utilizada=0
			  AND ID_Cliente='$id_cliente'
			  AND nc.ID_Nota_de_crédito LIKE '%$llave%'
			  GROUP BY nc.ID_Nota_de_crédito";
		$res=mysql_query(utf8_decode($sql)) or die("Error en:\n$sql\n\nDescripcion:".mysql_error());	
		$num=mysql_num_rows($res);		
		echo "exito|$num";
		for($i=0;$i<$num;$i++)
		{
			$row=mysql_fetch_row($res);
			if($row[1] > 0)
				echo utf8_encode("|".$row[0]." => Disponible: ".$row[1]);
		}						
	}
	
	if($tipo == 2)
	{		/* Tipo de Cobro para Registro de cobros */
		//$sql="SELECT * FROM `mgw_geg_tipos_de_cobro` ORDER BY Descripción";
		$sql="SELECT * FROM anderp_cuentas_por_cobrar_tipos ORDER BY nombre";
		
		$res=mysql_query(utf8_decode($sql)) or die("Error en:\n$sql\n\nDescripcion:".mysql_error());	
		$num=mysql_num_rows($res);		
		echo "exito|$num";
		for($i=0;$i<$num;$i++)
		{
			$row=mysql_fetch_row($res);
			echo utf8_encode("|".$row[0]."~".$row[1]);
		}						
	}
	
	if($tipo == 3)
	{		
		$sql="SELECT
		      nc.ID_Nota_de_crédito AS 'nota',
			  Total-IF(SUM(monto) IS NULL,0,SUM(Monto)) AS 'disponible'
			  FROM `mgw_geg_notas_de_credito` nc
			  LEFT JOIN mgw_geg_cuentas_por_cobrar_detalle cxcd ON (nc.ID_Nota_de_crédito=cxcd.Documento AND cxcd.ID_Tipo_de_cobro=4 AND cxcd.ID_Estatus_cobro=1)
			  WHERE Utilizada=0
			  AND ID_Cliente='$id_cliente'
			  AND nc.ID_Nota_de_crédito='$documento' 
			  GROUP BY nc.ID_Nota_de_crédito";
		$res=mysql_query(utf8_decode($sql)) or die("Error en:\n$sql\n\nDescripcion:".mysql_error());	
		if(mysql_num_rows($res) == 0)
			die(utf8_decode("No existe la nota de crédito $documento"));
		else	
			die('exito');
	}
	if($tipo == 4)
	{		
		$sql="SELECT
		      nc.ID_Nota_de_crédito AS 'nota',
			  Total-IF(SUM(monto) IS NULL,0,SUM(Monto)) AS 'disponible'
			  FROM `mgw_geg_notas_de_credito` nc
			  LEFT JOIN mgw_geg_cuentas_por_cobrar_detalle cxcd ON (nc.ID_Nota_de_crédito=cxcd.Documento AND cxcd.ID_Tipo_de_cobro=4 AND cxcd.ID_Estatus_cobro=1)
			  WHERE Utilizada=0
			  AND ID_Cliente='$id_cliente'
			  AND nc.ID_Nota_de_crédito='$documento'";
		
		if(isset($idgral) && $idgral <> '')	  
			$sql.=" AND IF(cxcd.ID_Control_cxc IS NULL, 1, cxcd.ID_Control_cxc <> '$idgral')";
			  
		$sql.=" GROUP BY nc.ID_Nota_de_crédito";
		$res=mysql_query(utf8_decode($sql)) or die("Error en:\n$sql\n\nDescripcion:".mysql_error());			
		$row=mysql_fetch_row($res);
		if($row[1] < $monto)
			die(utf8_decode("El monto excede el valor del documento"));
		else	
			die("exito");
	}	
	
	if($tipo == 5)
	{		
		$sql="SELECT
		      pa.ID_Pago_anticipado AS 'pago',
			  pa.Monto-IF(SUM(cxcd.Monto) IS NULL,0,SUM(cxcd.Monto)) AS 'disponible'
			  FROM `mgw_geg_pagos_anticipados` pa
			  LEFT JOIN mgw_geg_cuentas_por_cobrar_detalle cxcd ON (pa.ID_Pago_anticipado=cxcd.Documento AND cxcd.ID_Tipo_de_cobro=6 AND cxcd.ID_Estatus_cobro=1)
			  WHERE pa.ID_Cliente='$id_cliente'
			  AND pa.Cancelado=0
			  AND Utilizado=0
			  AND pa.ID_Pago_anticipado LIKE '%$llave%'
			  GROUP BY pa.ID_Pago_anticipado";
		$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:".mysql_error());	
		$num=mysql_num_rows($res);		
		echo "exito|$num";
		for($i=0;$i<$num;$i++)
		{
			$row=mysql_fetch_row($res);
			if($row[1] > 0)
				echo utf8_encode("|".$row[0]." => Disponible: ".$row[1]);
		}						
	}
	
	if($tipo == 6)
	{		
		$sql="SELECT
		      pa.ID_Pago_anticipado AS 'pago',
			  pa.Monto-IF(SUM(cxcd.Monto) IS NULL,0,SUM(cxcd.Monto)) AS 'disponible'
			  FROM `mgw_geg_pagos_anticipados` pa
			  LEFT JOIN mgw_geg_cuentas_por_cobrar_detalle cxcd ON (pa.ID_Pago_anticipado=cxcd.Documento AND cxcd.ID_Tipo_de_cobro=6 AND cxcd.ID_Estatus_cobro=1)
			  WHERE pa.ID_Cliente='$id_cliente'
			  AND pa.Cancelado=0
			  AND Utilizado=0
			  AND pa.id_pago_anticipado='$documento'
			  GROUP BY pa.ID_Pago_anticipado";
		$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:".mysql_error());	
		if(mysql_num_rows($res) == 0)
			die("No existe el pago anticipado $documento");
		else	
			die('exito');				
	}
	
	if($tipo == 7)
	{		
		$sql="SELECT
		      pa.id_pago_anticipado AS 'pago',
			  pa.monto-IF(SUM(cxcd.monto) IS NULL,0,SUM(cxcd.monto)) AS 'disponible'
			  FROM `mgw_geg_pagos_anticipados` pa
			  LEFT JOIN mgw_geg_cuentas_por_cobrar_detalle cxcd ON (pa.id_pago_anticipado=cxcd.Documento AND cxcd.id_tipo_de_cobro=6 AND cxcd.id_estatus_cobro=1)
			  WHERE pa.id_cliente='$id_cliente'
			  AND pa.cancelado=0
			  AND utilizado=0
			  AND pa.id_pago_anticipado='$documento'
			  GROUP BY pa.id_pago_anticipado";
		$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:".mysql_error());	
		$row=mysql_fetch_row($res);
		if($row[1] < $monto)
			die("El monto excede el valor del documento");
		else	
			die("exito");				
	}
	if($tipo == 8)
	{
		$sql="SELECT
		      id_control_cxc
			  FROM `mgw_geg_cuentas_por_cobrar`
			  WHERE id_tipo_ctaxcobrar='$tipo_cxc'
			  AND id_cuenta_por_cobrar='$cxc'";
		$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:".mysql_error());	
		if(mysql_num_rows($res))
			die("Ya existe la cuenta por cobrar, por favor elija otra");
		else	
			die("exito");
	}
	if($tipo == 9)
	{
		$sql="SELECT
			      IF(MAX(id_cuenta_por_cobrar) IS NULL,1,MAX(id_cuenta_por_cobrar)+1)
				  FROM `mgw_geg_cuentas_por_cobrar`
				  WHERE id_tipo_ctaxcobrar='$tipo_cx'";
		$res=mysql_query($sql);	  
		$row=mysql_fetch_row($res);
		echo $row[0];
	}
	if($tipo == 10)
	{
		$sql="SELECT * FROM `mgw_geg_cuentas_por_cobrar_estatus_cobro` ORDER BY Descripción";
		$res=mysql_query(utf8_decode($sql)) or die("Error en:\n$sql\n\nDescripcion:".mysql_error());	
		$num=mysql_num_rows($res);		
		echo "exito|$num";
		for($i=0;$i<$num;$i++)
		{
			$row=mysql_fetch_row($res);
			echo utf8_encode("|".$row[0]."~".$row[1]);
		}
	}
	if($tipo == 11)
	{
		$sql="SELECT
		      ID_Dirección,
			  CONCAT(Calle_y_No,', ',Colonia,', ',mgw_geg_ciudades.Nombre) AS 'direccion'
			  FROM `mgw_geg_clientes_direcciones`
			  LEFT JOIN mgw_geg_ciudades ON mgw_geg_clientes_direcciones.ID_Ciudad=mgw_geg_ciudades.ID_Ciudad
			  WHERE ID_Cliente ='$id_cliente' AND Es_dirección_de_entrega=1";
		$res=mysql_query(utf8_decode($sql)) or die("Error en:\n$sql\n\nDescripcion:".mysql_error());	  
		$num=mysql_num_rows($res);		
		echo "exito|$num";
		for($i=0;$i<$num;$i++)
		{
			$row=mysql_fetch_row($res);
			echo utf8_encode("|".$row[0]."~".$row[1]);
		}	  
	}
?>