<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$opcion = $_POST['opcion'];

$where = "";

if($opcion == "si")
		$where = "AND requiere_factura = 1";
else if($opcion == "no")
		$where = "AND requiere_factura = 0";

//$sql = "SELECT id_cliente, CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno) AS cliente FROM ad_clientes WHERE activo=1  AND id_cliente IN(SELECT DISTINCT id_cliente FROM ad_pedidos . $where .) ORDER BY nombre";

/*
$sql = "SELECT id_cliente,CONCAT(nombre, ' ', apellido_paterno, ' ', if(apellido_materno is null,'',apellido_materno))  as cliente
		FROM ad_clientes 
		WHERE activo=1 AND id_cliente IN(
		
		SELECT DISTINCT id_cliente FROM ad_pedidos WHERE 
		
		LEFT JOIN na_pedidos_detalle_pagos ON ad_pedidos.id_control_pedido = na_pedidos_detalle_pagos.id_control_pedido 
		WHERE 1 ".$where." AND (id_control_factura IS NULL OR id_control_factura=0) AND na_pedidos_detalle_pagos.confirmado <> 2 GROUP BY  id_pedido  HAVING pagos >= total_pedido
		
		 ) ORDER BY nombre";

*/

		
$sql = "SELECT id_cliente,CONCAT(nombre, ' ', apellido_paterno, ' ', if(apellido_materno is null,'',apellido_materno))  as cliente
		FROM ad_clientes 
		WHERE activo=1 AND id_cliente IN(
		
			SELECT  id_cliente FROM ad_pedidos

		LEFT JOIN ad_pedidos_detalle_pagos ON ad_pedidos.id_control_pedido = ad_pedidos_detalle_pagos.id_control_pedido

		WHERE  1 ".$where." and (id_control_factura IS NULL OR id_control_factura=0 )


          AND ad_pedidos_detalle_pagos.confirmado <> 2
          GROUP BY  id_pedido  HAVING sum(ad_pedidos_detalle_pagos.monto) >= max(ad_pedidos.total)
		
		 ) ORDER BY nombre";



$result = new consultarTabla($sql);
$datos = $result -> obtenerRegistros();

echo '<option value="0">Seleccione Cliente</option>';
foreach($datos as $dato){
		echo '<option value="' . $dato -> id_cliente . '">' . utf8_encode($dato -> cliente) . '</option>';
		}

?>