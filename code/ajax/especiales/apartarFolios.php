<?php
    php_track_vars;

	//nada solo un prueba
	extract($_GET);
	extract($_POST);	
	
    include("../../../conect.php");
	
	//Variables de filtro
$c=isset($c)?$c:'nada';
$o=isset($o)?$o:'nada';
$v=isset($v)?$v:'nada';
$op=isset($op)?$op:'nada';
$tcr=isset($tcr)?$tcr:'0';


//echo $ini."-".$fin."-".$dxp."-".$orderGRC."-".$sentidoOr;
//En esta sección de código podemos escoger las opciones de acuerdo a la tabla sis_listado
$tabla = base64_decode($t);

$listado =$tabla;// str_replace("erp_","listado_",$tabla);



	//Opción de datos completos
     //Consulta para obtener el SQL necesario para extraer los campos
	//$strConsulta = "SELECT Consulta, imprimir, orden as 'Orden' FROM sys_listados WHERE `tabla`='".$listado."'";
	
	$orderGRC = "a.id_nota_venta";
	$strConsulta = "SELECT a.id_control_nota_venta,0 as '-',a.id_nota_venta,a.id_sucursal,a.id_cliente,b.razon_social as 'Cliente',b.rfc as 'RFC',a.fecha_y_hora as 'Fecha',a.subtotal as 'Subtotal',a.iva as 'IVA',a.total as 'Total',b.require_factura as req_fac
FROM anderp_notas_venta a
LEFT JOIN anderp_clientes b ON b.id_cliente = a.id_cliente
WHERE a.id_sucursal=".$_SESSION["USR"]->sucursalid." AND a.id_control_factura = 0";


	$resultado = mysql_query($strConsulta);
	$variable=mysql_fetch_assoc($resultado);

	//Consulta para saber que campos hay que desplegar en el grid
	//$strConsulta=$variable['Consulta'];
	//echo $strConsulta."//";
	
	if($mpe == '1')		
		$strConsulta=str_replace('FROM',", 'Dets' FROM",$strConsulta);
	if($vpe == '1')
		$strConsulta=str_replace('FROM',", 'Dets' FROM",$strConsulta);
		
	if($epe == '1')
		$strConsulta=str_replace('FROM '.$tabla,", 'Elim' FROM ".$tabla,$strConsulta);
			
	
	
	if($tabla == 'peug_facturas_plan_piso')
	{	
	
 
		if($serie != '')	  
	{
		$strWhere.=" AND pp.serie LIKE '%$serie%'";
	}	
	
	  if($folio != '')	  
	{
		$strWhere.=" AND pp.folio = '$folio'";
	}
	
	 if($nofact != '')	  
	{
		$strWhere.=" AND pp.vin_fact LIKE '%$nofact%'";
	}
	
	if($tipo != '')	  
	{
	  
	   
		if($tipo == '0') {
		  $strWhere.="";
		}
		else{
		$strWhere.=" AND pp.tipo LIKE '$tipo'";
	    }
		
	}
	if($vin != '')	  
	{
		$strWhere.=" AND pp.vin_fact LIKE '%$vin%'";
	}
	if($referencia != '')	  
	{
		$strWhere.=" AND pp.no_cliente LIKE '%$referencia%'";
	}
	if($fecdel != '')	  
	{
		//convertDate($$fecdel) '".convertDate($fecal)."'"
		$strWhere.=" AND pp.fecha >= '".convertDate($fecdel)."'";
	}
	if($fecal != '')	  
	{
		$strWhere.=" AND pp.fecha<= '".convertDate($fecal)."'";
	}
	}
	
    //echo "Pio".$strConsulta."//";	
	//echo "otro ORDEN ".$orden;

	
		
//EXCEPCIONES
//anexamos a la consulta las cosideraciones de la sucurdal y el tipo de credito
//echo "variable orden  ".$variable['Orden']."//";

	
	
if(isset($orden))
   $strConsulta.=" ".$orden;	

//echo $orden."-";		
$orderGRC=str_replace("\'", "'", $orderGRC);

//echo " ".$orderGRC."-";
if(eregi("id_", $orderGRC))
	$sentidoOr="DESC";
//echo $variable['Orden']."//"; 
if($variable['Orden'] != ''  && strlen($orderGRC) <= 0)
	$strConsulta.=$variable['Orden'];
	
if(isset($orderGRC))
	$strConsulta.=" ORDER BY ".$orderGRC." $sentidoOr";
	
	
//die($strConsulta);
//echo "detalle".$strConsulta;
//Ponemos el inicio y fin que nos marca el grid
if(isset($ini) && isset($fin))
{
	//Conseguimos el nï¿½mero de datos real
	$resultado=mysql_query($strConsulta) or die("Consulta:\n$strConsulta\n\nDescripcion:\n".mysql_error());
	$numtotal=mysql_num_rows($resultado);	
	
	//Aï¿½adimos el limit para el paginador
	$strConsulta.=" LIMIT $ini, $fin";
}	
//echo "STR".$strConsulta;
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
	
	$smarty->assign("numtotalReg",$numtotal);

?>