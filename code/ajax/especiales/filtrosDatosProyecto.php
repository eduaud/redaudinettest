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


	
	
	$orderGRC = " of_detalle_pedido.id_pedido ";
	$strConsulta = "SELECT id_control_pedido_detalle,
 0 ,
id_pr_serv,
of_productos_servicios.nombre,
of_plaza.nombre,
of_detalle_pedido.id_pedido,
cantidad,
0 as costo,
0 as importe
FROM of_detalle_pedido
left join of_pedidos on of_detalle_pedido.id_pedido = of_pedidos.id_pedido
left join of_proyectos on of_pedidos.id_proyecto = of_proyectos.id_proyecto
left join of_productos_servicios on id_producto = id_pr_serv
left join of_sucursal_franquicia ON of_sucursal_franquicia.id_sucursal_franquicia =of_pedidos.id_sucursal_franquicia
left join of_franquicias on of_franquicias.id_franquicia=of_sucursal_franquicia.id_franquicia
left join of_plaza ON of_plaza.id_plaza = of_franquicias.id_plaza
where disponible_para_orden_de_produccion=1
and id_control_pedido_detalle not in(SELECT id_control_pedido_detalle FROM of_detalle_productos_pedidos)
and id_estatus_de_proyecto=1 and of_pedidos.id_proyecto='".$id_proyecto."' and id_producto='".$id_producto_servicio."'";
	
	
	

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
