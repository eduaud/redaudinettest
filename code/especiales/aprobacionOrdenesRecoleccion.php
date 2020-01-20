<?php

include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");

//mysql_query("SET NAMES utf8");

$sql = "SELECT na_ordenes_recoleccion_clientes.id_cliente,
				CONCAT(ad_clientes.nombre,' ', if(apellido_paterno is null,'',apellido_paterno),' ',if(apellido_materno is null,'',apellido_materno)) as cliente 
				FROM na_ordenes_recoleccion_clientes
				LEFT JOIN ad_clientes USING(id_cliente)
				WHERE na_ordenes_recoleccion_clientes.activo=1 AND na_ordenes_recoleccion_clientes.id_estatus_orden_recoleccion = 1
				ORDER BY nombre";
$datosCliente = new consultarTabla($sql);
$cliente = $datosCliente -> obtenerArregloRegistros();

$sql2 = "SELECT id_orden_recoleccion_cliente, fecha_generacion, CONCAT(ad_clientes.nombre, ' ', ad_clientes.apellido_paterno, ' ', ad_clientes.apellido_materno) AS cliente, 
		CONCAT('Calle: ',na_clientes_direcciones_entrega.calle,' ', 'Num. Ext: ',if(na_clientes_direcciones_entrega.numero_exterior is null,'',na_clientes_direcciones_entrega.numero_exterior),' ',if(na_clientes_direcciones_entrega.numero_interior is null,'',na_clientes_direcciones_entrega.numero_interior),' ','Col. ',if(na_clientes_direcciones_entrega.colonia is null,'',na_clientes_direcciones_entrega.colonia),' ','Del. o Mun. ',if(na_clientes_direcciones_entrega.delegacion_municipio is null,'',na_clientes_direcciones_entrega.delegacion_municipio)) AS direccion_cliente,
		fecha_recoleccion, na_rutas.nombre AS ruta
		FROM na_ordenes_recoleccion_clientes
		LEFT JOIN ad_clientes ON na_ordenes_recoleccion_clientes.id_cliente = ad_clientes.id_cliente
		LEFT JOIN na_clientes_direcciones_entrega ON na_ordenes_recoleccion_clientes.id_cliente_direccion_entrega = na_clientes_direcciones_entrega.id_cliente_direccion_entrega
		LEFT JOIN na_estatus_ordenes_recoleccion ON na_ordenes_recoleccion_clientes.id_estatus_orden_recoleccion = na_estatus_ordenes_recoleccion.id_estatus_orden_recoleccion
		LEFT JOIN na_rutas ON na_ordenes_recoleccion_clientes.id_ruta = na_rutas.id_ruta
		WHERE na_ordenes_recoleccion_clientes.id_estatus_orden_recoleccion = 1";
		
$datos = new consultarTabla($sql2);
$result = $datos -> obtenerArregloRegistros();

$smarty -> assign("cliente", $cliente);
$smarty -> assign("filas", $result);
$smarty -> display("especiales/aprobacionOrdenesRecoleccion.tpl");

?>