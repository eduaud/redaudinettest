<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

mysql_query("SET NAMES 'utf8'");

$idVend = $_POST['idVend'];

$where = "";

if($idVend != 0) {
		$where .= " AND ad_pedidos.id_vendedor = " . $idVend;
		}
		
$sqlPar = "SELECT porcentaje_comision_1 AS comision FROM sys_parametros_nasser";
$datosPar = new consultarTabla($sqlPar);
$resultPar = $datosPar -> obtenerLineaRegistro();

		
$sql = "SELECT CONCAT(na_vendedores.nombre,' ', IF(na_vendedores.apellido_paterno is null,'',na_vendedores.apellido_paterno),' ',IF(na_vendedores.apellido_materno is 		null,'',na_vendedores.apellido_materno)) AS vendedor,
		id_pedido AS id_pedido, DATE_FORMAT(fecha_alta,'%d/%m/%Y') AS fecha_pedido, na_sucursales.nombre AS sucursal, 
		CONCAT(ad_clientes.nombre,' ', if(ad_clientes.apellido_paterno is null,'',ad_clientes.apellido_paterno),' ',if(ad_clientes.apellido_materno is null,'',ad_clientes.apellido_materno)) as cliente, CONCAT('$', FORMAT(ad_pedidos.total_productos, 2)) AS monto,
		id_control_pedido, ad_pedidos.id_vendedor AS id_vendedor, 
		CONCAT('$', FORMAT(((ad_pedidos.total_productos * " . $resultPar['comision'] . ")/100),2)) AS comision
		   FROM ad_pedidos
		   LEFT JOIN na_vendedores USING(id_vendedor)
		   LEFT JOIN ad_clientes USING(id_cliente)
		   LEFT JOIN na_sucursales ON ad_pedidos.id_sucursal_alta = na_sucursales.id_sucursal
		   WHERE id_estatus_pedido = 4 AND id_estatus_pago_pedido = 2 AND ad_pedidos.id_comision IS NULL" . $where;

$datos = new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();
$smarty -> assign("filasPedido", $result);

echo $smarty->fetch('especiales/respuestaComisiones.tpl');		   


?>