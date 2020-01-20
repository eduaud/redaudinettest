<?php

include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");

$consultaLista = "SELECT ad_lista_precios.id_lista_precios, ad_lista_precios.nombre 
FROM na_listas_detalle_sucursales 
LEFT JOIN ad_lista_precios ON na_listas_detalle_sucursales.id_lista_precios = ad_lista_precios.id_lista_precios
WHERE id_sucursal = " . $_SESSION["USR"]->sucursalid . " AND ad_lista_precios.activo = 1";
$datosLista = new consultarTabla($consultaLista);
$lista = $datosLista -> obtenerArregloRegistros();

$sqlVendedor = "SELECT DISTINCT na_vendedores.id_vendedor AS id_vendedor, 
				CONCAT(na_vendedores.nombre, ' ', na_vendedores.apellido_paterno, ' ', na_vendedores.apellido_materno) AS vendedor
				FROM sys_usuarios
				LEFT JOIN na_vendedores ON sys_usuarios.id_vendor_relacionado = na_vendedores.id_vendedor
				WHERE sys_usuarios.id_vendor_relacionado <> 0 AND sys_usuarios.id_sucursal = " . $_SESSION["USR"]->sucursalid;
$datosVendedor = new consultarTabla($sqlVendedor);
$vendedores = $datosVendedor -> obtenerRegistros();

foreach($vendedores as $registros){
		$arrVendId[] = $registros -> id_vendedor;
		$arrVend[] = $registros -> vendedor;			
		}	

$smarty -> assign("lista", $lista);
$sqlVendActual = "SELECT id_vendor_relacionado FROM sys_usuarios WHERE id_usuario = " . $_SESSION["USR"] -> userid;
$datosVendActual = new consultarTabla($sqlVendActual);
$vendedor = $datosVendActual -> obtenerLineaRegistro();

$smarty -> assign("vendedor", $vendedor['id_vendor_relacionado']);
$smarty->assign('vendedor_id', $arrVendId);
$smarty->assign('vendedor_nombre',$arrVend);

$smarty -> display("especiales/prepedidos.tpl");

?>