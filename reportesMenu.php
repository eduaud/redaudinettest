<?php
	php_track_vars;

	extract($_GET);
	extract($_POST);
	
//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("conect.php");
	//include("../../code/general/funciones.php");
	
	//vemos a que reportes tiene acceso
	
	//249, 'Reporte de Producto(No Material)', '-', '-', 2, 1, 247, '', 702, '', '', 'reportes/reportes.php?reporte=1', '', '', '', '', '', '', '', ''
	
	//251, 'Facturas Pendientes de Cobro', '-', '-', 2, 1, 247, '', 704, '', '', 'reportes/reportes.php?reporte=2', '', '', '', '', '', '', '', ''
//252, 'Facturas', '-', '-', 2, 1, 247, '', 705, '', '', 'reportes/reportes.php?reporte=3', '', '', '', '', '', '', '', ''
//253, 'Reporte de Producto( Materiales)', '-', '-', 2, 1, 247, '', 706, '', '', 'reportes/reportes.php?reporte=4', '', '', '', '', '', '', '', ''	
    $strSubmenusPermitidos=','.$strSubmenusPermitidos.',';
	
	
	$ver_clientes=0;
    $ver_facturas=0;
	$ver_notas=0;	
	$rep1=0;
	$rep2=0;
	$rep3=0;
	$rep4=0;
	
	
	if($esAdmin==0)
	{
		$permisosrep=false;
		for($i=46;$i<54;$i++)
		{
			if(stristr( ",".$strSubmenusPermitidos.",", ','.$i.','))
				$permisosrep=true;
		}		
		if(!$permisosrep)
		{
			header("Location: ".$rooturl."index.php?".SID);	
			die();		
		}		
	}
	else
	{
		$ver_clientes=1;
		$ver_facturas=1;
		$ver_notas=1;
		$rep1=1;
		$rep2=1;
		$rep3=1;
		$rep4=1;
		$rep5=1;
		$rep6=1;
		$rep7=1;
		$rep8=1;
		$rep9=1;
		$rep10=1;
		$rep11=1;
		$rep12=1;
	}	
	//reporte de inventarios
	if(stristr( ",".$strSubmenusPermitidos.",", ',46,') or stristr( ",".$strSubmenusPermitidos.",", ',47,') or stristr( ",".$strSubmenusPermitidos.",", ',48,') or stristr( ",".$strSubmenusPermitidos.",", ',49,') or stristr( ",".$strSubmenusPermitidos.",", ',50,'))
	{
		$ver_asistentes=1;
		if(stristr( ",".$strSubmenusPermitidos.",", ',46,'))
			$rep1=1;
		if(stristr( ",".$strSubmenusPermitidos.",", ',47,'))
			$rep2=1;
		if(stristr( ",".$strSubmenusPermitidos.",", ',48,'))
			$rep3=1;
		if(stristr( ",".$strSubmenusPermitidos.",", ',49,'))
			$rep4=1;
		if(stristr( ",".$strSubmenusPermitidos.",", ',50,'))
			$rep5=1;
	}
	
	//repotes de ventas
	if(stristr( ",".$strSubmenusPermitidos.",", ',51,') or stristr( ",".$strSubmenusPermitidos.",", ',52,') or stristr( ",".$strSubmenusPermitidos.",", ',53,') or stristr( ",".$strSubmenusPermitidos.",", ',54,') or stristr( ",".$strSubmenusPermitidos.",", ',55,'))
	{
		$ver_ventas=1;
		if(stristr( ",".$strSubmenusPermitidos.",", ',51,'))
			$rep6=1;
		if(stristr( ",".$strSubmenusPermitidos.",", ',52,'))
			$rep7=1;
		if(stristr( ",".$strSubmenusPermitidos.",", ',53,'))
			$rep8=1;
		if(stristr( ",".$strSubmenusPermitidos.",", ',54,'))
			$rep9=1;		
	}		
	
	
	$sql="SELECT
		  CONCAT(anio, ',', mes),				
	      CONCAT('A&ntilde;o: ', anio, ', Mes: ', mes)
		  FROM `fes_control_anio_mes` ORDER BY anio DESC, mes DESC";
		  
	$ids_per=array();
	$nom_per=array();	  
		  
	$res=mysql_query($sql);		  
	
	
	$num=mysql_num_rows($res);
	for($i=0;$i<$num;$i++)	  
	{
		$row=mysql_fetch_row($res);
		array_push($ids_per, $row[0]);
		array_push($nom_per, $row[1]);
	}
	
	$smarty->assign("ids_per", $ids_per);
	$smarty->assign("nom_per", $nom_per);
	
	
	$smarty->display("reportesMenu.tpl");	
		
?>