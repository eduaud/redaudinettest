<?php
		php_track_vars;

    extract($_GET);
	extract($_POST);
	
//CONECCION Y PERMISOS A LA BASE DE DATOS
 	include("../../../conect.php");
	include("../../../code/general/funciones.php");
		
	if($tipo == 1)
	{		
		//$sql="SELECT * FROM `mgw_geg_cuentas_por_cobrar_tipos` ORDER BY Descripción";
		/*$sql="SELECT * FROM anderp_cuentas_por_cobrar_tipos ORDER BY nombre";
		$res=mysql_query(utf8_decode($sql)) or die("Error en:\n$sql\n\nDescripcion:".mysql_error());	
		$num=mysql_num_rows($res);		
		echo "exito|$num";
		for($i=0;$i<$num;$i++)
		{
			$row=mysql_fetch_row($res);
			echo utf8_encode("|".$row[0]."~".$row[1]);
		}	
		*/
		
		//siempre son notas de Venta
		$row[0]=1;
		$row[1]='Nota de Venta';
		 echo "exito|1";
	     echo utf8_encode("|".$row[0]."~".$row[1]);			
	}	
	
	if($tipo == 8)
	{
		if($tipodoc == '4')
			$sql="SELECT
			      nc.ID_Nota_de_crédito AS 'nota',
				  total-IF(SUM(monto) IS NULL,0,SUM(monto)) AS 'disponible'
				  FROM `mgw_geg_notas_de_credito` nc
				  LEFT JOIN mgw_geg_cuentas_por_cobrar_detalle cxcd ON (nc.ID_Nota_de_crédito=cxcd.documento AND cxcd.id_tipo_de_cobro=4 AND cxcd.id_estatus_cobro=1)
				  WHERE utilizada=0
				  AND id_cliente='$cliente'
				  GROUP BY nc.ID_Nota_de_crédito";
		else if($tipodoc == '6')
			$sql="SELECT
			      pa.id_pago_anticipado AS 'pago',
				  pa.monto-IF(SUM(cxcd.monto) IS NULL,0,SUM(cxcd.monto)) AS 'disponible'
				  FROM `mgw_geg_pagos_anticipados` pa
				  LEFT JOIN mgw_geg_cuentas_por_cobrar_detalle cxcd ON (pa.ID_Pago_anticipado=cxcd.documento AND cxcd.id_tipo_de_cobro=6 AND cxcd.id_estatus_cobro=1)
				  WHERE pa.id_cliente='$cliente'
				  AND pa.cancelado=0
				  AND utilizado=0
				  GROUP BY pa.id_pago_anticipado";
				  
				  
		$res=mysql_query(utf8_decode($sql)) or die("Error en:\n$sql\n\nDescripcion:".mysql_error());	
		$num=mysql_num_rows($res);
		echo "exito";
		if($tipo == 8)
			echo "|$num";
		for($i=0;$i<$num;$i++)
		{
			$row=mysql_fetch_row($res);
			echo utf8_encode("|".$row[0]."~".$row[1]);
		}		  
	}
	if($tipo == 9)
	{
		if($tipodoc == '4')
			$sql="SELECT
			      nc.ID_Nota_de_crédito AS 'nota',
				  total-IF(SUM(monto) IS NULL,0,SUM(monto)) AS 'disponible'
				  FROM `mgw_geg_notas_de_credito` nc
				  LEFT JOIN mgw_geg_cuentas_por_cobrar_detalle cxcd ON (nc.ID_Nota_de_crédito=cxcd.documento AND cxcd.id_tipo_de_cobro=4 AND cxcd.id_estatus_cobro=1)
				  WHERE utilizada=0
				  AND id_cliente='$cliente'
				  AND nc.ID_Nota_de_crédito='$valor'
				  GROUP BY nc.ID_Nota_de_crédito";
		else if($tipodoc == '6')
			$sql="SELECT
			      pa.id_pago_anticipado AS 'pago',
				  pa.monto-IF(SUM(cxcd.monto) IS NULL,0,SUM(cxcd.monto)) AS 'disponible'
				  FROM `mgw_geg_pagos_anticipados` pa
				  LEFT JOIN mgw_geg_cuentas_por_cobrar_detalle cxcd ON (pa.ID_Pago_anticipado=cxcd.documento AND cxcd.id_tipo_de_cobro=6 AND cxcd.id_estatus_cobro=1)
				  WHERE pa.id_cliente='".$cliente."'
				  AND pa.cancelado=0
				  AND pa.id_pago_anticipado='$valor'
				  AND utilizado=0
				  GROUP BY pa.id_pago_anticipado";
		else
			$sql="SELECT 'NOVALIDAR'";
		//echo $sql;		
		$res=mysql_query(utf8_decode($sql)) or die("Error en:\n$sql\n\nDescripcion:".mysql_error());	
		$num=mysql_num_rows($res);
		echo "exito";
		if($tipo == 8)
			echo "|$num";
		for($i=0;$i<$num;$i++)
		{
			$row=mysql_fetch_row($res);
			echo utf8_encode("|".$row[0]."~".$row[1]);
		}	
	}	
	if($tipo == 10)
	{
		$sql="SELECT pesos_permitidos FROM `mgw_geg_parametros_administrador`";
		$res=mysql_query(utf8_decode($sql)) or die("Error en:\n$sql\n\nDescripcion:".mysql_error());
		$row=mysql_fetch_row($res);
		echo "exito|".$row[0];
	}
?>