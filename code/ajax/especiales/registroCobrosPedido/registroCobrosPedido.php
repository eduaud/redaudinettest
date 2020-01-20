<?php 
include("../../../../conect.php");	
include_once("../../../../code/general/funciones.php");

$WHERE=" where id_control_pedido=0 ";

if (isset($_GET['cliente']) and $_GET['cliente'] != 0)
{	
	$cliente=$_GET['cliente'];
	
	$WHERE=" WHERE na_clientes.id_cliente=".$cliente;
	
	if(isset($_GET['no_pedido']) and $_GET['no_pedido'] != "")
	{		
		$noPedido="'".$_GET['no_pedido']."'";
		$WHERE.=" AND id_pedido=".$noPedido;		  			  
	}
				  
}

$strConsulta="SELECT id_control_pedido,
					 ad_pedidos.id_sucursal_alta,
					 na_sucursales.nombre as sucursal_nombre,
					 CONCAT(na_clientes.nombre,' ', if(apellido_paterno is null,'',apellido_paterno),' ',if(apellido_materno is null,'',apellido_materno)) 
					 AS cliente_nombre,						
					 id_pedido as Pedido,						
					 id_estatus_pedido,
					 id_estatus_pago_pedido,						
					 total, 
					 ( SELECT if(SUM(monto) is null,0,SUM(monto) ) 
								FROM na_pedidos_detalle_pagos 
								WHERE na_pedidos_detalle_pagos.id_control_pedido=ad_pedidos.id_control_pedido AND activoDetPagos = 1) as pagos,
					( SELECT if(SUM(monto) is null,0,SUM(monto) ) 
							FROM na_pedidos_detalle_pagos WHERE confirmado in (0, 1, 3)
						AND activoDetPagos = 1 and na_pedidos_detalle_pagos.id_control_pedido=ad_pedidos.id_control_pedido) as pagos_confirmados,
					( SELECT if(SUM(monto) is null,0,SUM(monto) ) FROM na_pedidos_detalle_pagos WHERE confirmado in (2) 
						and na_pedidos_detalle_pagos.id_control_pedido=ad_pedidos.id_control_pedido) as pagos_no_confirmados,
					total - ( SELECT if(SUM(monto) is null,0,SUM(monto) ) 
							FROM na_pedidos_detalle_pagos WHERE confirmado in (0, 1, 3)
						AND activoDetPagos = 1 and na_pedidos_detalle_pagos.id_control_pedido=ad_pedidos.id_control_pedido)  as Saldo,
					'' as ver
					FROM ad_pedidos 
		   left join na_clientes on ad_pedidos.id_cliente=na_clientes.id_cliente
		   left join na_sucursales on na_sucursales.id_sucursal=ad_pedidos.id_sucursal_alta
			  $WHERE";	
					
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

//Enviamos los datos del listado, numero de datos y datos que se muestran
if(isset($ini) && isset($fin))
	echo "|$numtotal~$num";
?>
