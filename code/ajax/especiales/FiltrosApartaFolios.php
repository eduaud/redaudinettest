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
	include("../../../conect.php");	
	

if(isset($filtros)){

  if($filtros ==1 && $accion=1){
  
    
	 
     //opcion de mostrar Notas  de Venta de Todos, Notas que requieren Factura, Notas que no Requieren Factura
	 if($mostrarNV == 1){//cliente requiere factura
	   $strWhere .=  $Orden."  b.require_factura=1 "; 
	 }
	 elseif($mostrarNV == 2){
	    $strWhere .= $Orden."  b.require_factura=0 "; 
	 }
	 else{
	   $strWhere .= "";  
	 }
	 
  
    //filtros de campos
     if($cliente != '')	  
	{
		$strWhere.= $Orden."  a.id_cliente = $cliente ";
	}	
	
	  if($rfc != '')	  
	{
		$strWhere.= $Orden."  b.rfc = '$rfc'";
	}
	
	 if($rutas != '' && $rutas>0)	  
	{
		$strWhere.= $Orden." a.id_ruta = $rutas";
	}
	
	if($fecdel != '')	  
	{
		//convertDate($$fecdel) '".convertDate($fecal)."'"
		$strWhere.= $Orden." a.fecha_y_hora >= '".convertDate($fecdel)."'";
	}
	if($fecal != '')	  
	{
		$strWhere.= $Orden." a.fecha_y_hora <= '".convertDate($fecal)."'";
	}
	
	
	
	$orderGRC = "a.id_nota_venta";
	$strConsulta = "SELECT a.id_control_nota_venta,0 as '-',a.id_nota_venta,a.id_sucursal,a.id_cliente,b.razon_social as 'Cliente',b.rfc as 'RFC',a.fecha_y_hora as 'Fecha',a.subtotal as 'Subtotal',a.iva as 'IVA',a.total as 'Total'
FROM anderp_notas_venta a
LEFT JOIN anderp_clientes b ON b.id_cliente = a.id_cliente
WHERE a.id_sucursal=".$_SESSION["USR"]->sucursalid." AND a.id_factura = 0";
	
	
		//$strConsulta=str_replace('FROM',", 'Dets' FROM",$strConsulta);

		//$strConsulta=str_replace('FROM',", 'Dets' FROM",$strConsulta);
		
		//$strConsulta=str_replace('FROM '.$tabla,", 'Elim' FROM ".$tabla,$strConsulta);
		
		
          $strConsulta.=" ".$strWhere;	


if(isset($orden))
   $strConsulta.=" ".$orden;	

//echo $orden."-";		
$orderGRC=str_replace("\'", "'", $orderGRC);

//echo " ".$orderGRC."-";
if(eregi("id_", $orderGRC))
	$sentidoOr="DESC";
//echo $variable['Orden']."//"; 
/*if($variable['Orden'] != ''  && strlen($orderGRC) <= 0)
	$strConsulta.=$variable['Orden'];*/
	
if(isset($orderGRC))
	$strConsulta.=" ORDER BY ".$orderGRC." $sentidoOr";
	
//ordenacion
/*$orderGRC = 1;
if(isset($orderGRC))
	$strConsulta.=" ORDER BY ".$orderGRC;*/

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

//echo $strConsulta;

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




}//fin for


//Enviamos en el ultimo dato los datos del listado, numero de datos y datos que se muestran
if(isset($ini) && isset($fin))
	echo "|$numtotal~$num";

  
  }//fin de if filtro
  










}//fin if filtro	
	
	
function convertDate($fecha)
	{
		$aux1=explode(' ', $fecha);
		$aux=explode('/', $aux1[0]);
		$aux=$aux[2]."-".$aux[1]."-".$aux[0];
		if(sizeof($aux1) > 1)
			$aux.=" ".$aux1[1];
		return $aux;
	}	
	
	
?>
