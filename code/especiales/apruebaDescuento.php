<?php

include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");

//mysql_query("SET NAMES utf8");

$where = "";
if($_SESSION["USR"]->sucursalid != 0)
		$where .= " AND na_solicitud_descuento.id_sucursal = " . $_SESSION["USR"]->sucursalid;

	
	
	//if(ad_clientes.apellido_materno is null,'',ad_clientes.apellido_materno)
	
	
$sql = "SELECT id_solicitud_descuento, na_sucursales.nombre, CONCAT(sys_usuarios.nombres, ' ', sys_usuarios.apellido_paterno, ' ', sys_usuarios.apellido_materno) AS usuario_registro, CONCAT(ad_clientes.nombre, ' ', if(ad_clientes.apellido_paterno is null,'',ad_clientes.apellido_paterno), ' ', if(ad_clientes.apellido_materno is null,'',ad_clientes.apellido_materno)) AS cliente, CONCAT(FORMAT(descuento,2), '%'), 
CONCAT('$', FORMAT((descuento*ad_pedidos.total)/100,2)), 
CONCAT('$', FORMAT(ad_pedidos.total,2)), motivo, na_solicitud_descuento.id_pedido
				FROM na_solicitud_descuento
				LEFT JOIN sys_usuarios ON na_solicitud_descuento.id_usuario_solicita = sys_usuarios.id_usuario
				LEFT JOIN na_sucursales ON na_solicitud_descuento.id_sucursal = na_sucursales.id_sucursal
				LEFT JOIN ad_clientes ON na_solicitud_descuento.id_cliente = ad_clientes.id_cliente
				LEFT JOIN ad_pedidos ON na_solicitud_descuento.id_pedido = ad_pedidos.id_referencia
				WHERE id_estatus_descuento = 1" . $where;
$datosdescuentos = new consultarTabla($sql);
$descuentos = $datosdescuentos -> obtenerArregloRegistros();

$smarty -> assign("descuentos", $descuentos);

$smarty -> display("especiales/apruebaDescuento.tpl");

?>