<?php 
include("../../../conect.php");	
include_once("../../../code/general/funciones.php");

if (isset($_GET['cliente']) and $_GET['cliente'] != 0)
{	
	$cliente=$_GET['cliente'];
	
	$strConsulta="SELECT 
						 id_control_pedido,
						 ad_pedidos.id_sucursal_alta,
						 na_sucursales.nombre as sucursal_nombre,
						 CONCAT(ad_clientes.nombre,' ',ad_clientes.apellido_paterno,' ',ad_clientes.apellido_materno) as cliente_nombre,						
						 id_pedido as Pedido,						
						 id_estatus_pedido,
						 id_estatus_pago_pedido,						
						 total, 						
						 ( SELECT if(SUM(monto) is null,0,SUM(monto) ) 
					FROM na_pedidos_detalle_pagos 
				   WHERE na_pedidos_detalle_pagos.id_control_pedido=ad_pedidos.id_control_pedido) as pagos,
				  		 ( SELECT if(SUM(monto) is null,0,SUM(monto) ) FROM na_pedidos_detalle_pagos WHERE confirmado in (0,2) 
				  	 and na_pedidos_detalle_pagos.id_control_pedido=ad_pedidos.id_control_pedido) as pagos_confirmados,
				  		 ( SELECT if(SUM(monto) is null,0,SUM(monto) ) FROM na_pedidos_detalle_pagos WHERE confirmado in (1) 
				  	 and na_pedidos_detalle_pagos.id_control_pedido=ad_pedidos.id_control_pedido) as pagos_no_confirmados,
				  total-( SELECT if(SUM(monto) is null,0,SUM(monto) ) FROM na_pedidos_detalle_pagos WHERE confirmado in (0,2)
				  	 and  na_pedidos_detalle_pagos.id_control_pedido=ad_pedidos.id_control_pedido) as Saldo, '' as ver
				    FROM ad_pedidos 
			   left join ad_clientes on ad_pedidos.id_cliente=ad_clientes.id_cliente
			   left join na_sucursales on na_sucursales.id_sucursal=ad_pedidos.id_sucursal_alta
			       where ad_clientes.id_cliente=".$cliente;
				  			  
				  
}
else
{
	$strConsulta="SELECT 
					id_control_pedido,
					id_pedido as Pedido,
					ad_pedidos.id_sucursal_alta,
					id_estatus_pedido,
					id_estatus_pago_pedido,
					total, 
					CONCAT(ad_clientes.nombre,' ',ad_clientes.apellido_paterno,' ',ad_clientes.apellido_materno) as cliente_nombre,
					na_sucursales.nombre as sucursal_nombre,
					( SELECT if(SUM(monto) is null,0,SUM(monto) ) 
				FROM na_pedidos_detalle_pagos 
			  WHERE  na_pedidos_detalle_pagos.id_control_pedido=ad_pedidos.id_control_pedido) as pagos,
			  ( SELECT if(SUM(monto) is null,0,SUM(monto) ) FROM na_pedidos_detalle_pagos WHERE confirmado in (0,2) 
			  and  na_pedidos_detalle_pagos.id_control_pedido=ad_pedidos.id_control_pedido) as pagos_confirmados,
			  ( SELECT if(SUM(monto) is null,0,SUM(monto) ) FROM na_pedidos_detalle_pagos WHERE confirmado in (1) 
			  and  na_pedidos_detalle_pagos.id_control_pedido=ad_pedidos.id_control_pedido) as pagos_no_confirmados,
			  total-( SELECT if(SUM(monto) is null,0,SUM(monto) ) FROM na_pedidos_detalle_pagos WHERE confirmado in (0,2)
			  and  na_pedidos_detalle_pagos.id_control_pedido=ad_pedidos.id_control_pedido) as Saldo, '' as ver
			  FROM ad_pedidos 
			  left join ad_clientes on ad_pedidos.id_cliente=ad_clientes.id_cliente
			  left join na_sucursales on na_sucursales.id_sucursal=ad_pedidos.id_sucursal_alta
			  where id_control_pedido=0 ";

}

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
// fin

//Enviamos en el ultimo dato los datos del listado, numero de datos y datos que se muestran
if(isset($ini) && isset($fin))
	echo "|$numtotal~$num";

	
?>
