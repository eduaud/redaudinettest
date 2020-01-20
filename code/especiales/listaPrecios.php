<?php

include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");



$consultaLista = "SELECT id_lista_precios, nombre FROM ad_lista_precios WHERE activo = 1 ORDER BY nombre";
$datosLista = new consultarTabla($consultaLista);
$lista = $datosLista -> obtenerArregloRegistros();

$consultaPagos = "SELECT id_forma_pago, nombre FROM na_formas_pago WHERE activo = 1 ORDER BY nombre";
$datosPagos = new consultarTabla($consultaPagos);
$pagos = $datosPagos -> obtenerArregloRegistros();

$consultaTiendas = "SELECT id_sucursal, nombre FROM na_sucursales WHERE activo = 1 ORDER BY nombre";
$datosTiendas = new consultarTabla($consultaTiendas);
$tiendas = $datosTiendas -> obtenerArregloRegistros();

$consultaHoras = "SELECT id_tiempo, nombre FROM na_tiempos WHERE activo = 1 ORDER BY nombre";
$datosHoras = new consultarTabla($consultaHoras);
$horas = $datosHoras -> obtenerArregloRegistros();

$consultaFamilia = "SELECT id_familia_producto, nombre FROM na_familias_productos WHERE activo = 1 ORDER BY nombre";
$datosFamilia = new consultarTabla($consultaFamilia);
$familia = $datosFamilia -> obtenerArregloRegistros();

$consultaProv = "SELECT DISTINCT na_proveedores.id_proveedor, na_proveedores.razon_social
				FROM na_productos_proveedor_detalle
				LEFT JOIN na_proveedores ON na_productos_proveedor_detalle.id_proveedor = na_proveedores.id_proveedor
				LEFT JOIN na_tipos_proveedores ON na_tipos_proveedores.id_tipo_proveedor = na_proveedores.id_tipo_proveedor
				WHERE na_proveedores.activo = 1 AND es_de_productos = 1 ORDER BY razon_social";
$datosProv = new consultarTabla($consultaProv);
$proveedores = $datosProv -> obtenerArregloRegistros();

$consultaMarca = "SELECT DISTINCT na_marcas_productos.id_marca_producto, na_marcas_productos.nombre
				FROM na_productos_proveedor_detalle
				LEFT JOIN na_proveedores ON na_productos_proveedor_detalle.id_proveedor = na_proveedores.id_proveedor
				LEFT JOIN na_tipos_proveedores ON na_tipos_proveedores.id_tipo_proveedor = na_proveedores.id_tipo_proveedor
				LEFT JOIN na_productos ON na_productos_proveedor_detalle.id_producto = na_productos.id_producto
				LEFT JOIN na_marcas_productos ON na_productos.id_marca_producto = na_marcas_productos.id_marca_producto
				WHERE na_productos.activo = 1 AND es_de_productos = 1 ORDER BY na_marcas_productos.nombre";
$datosMarca = new consultarTabla($consultaMarca);
$marcas = $datosMarca -> obtenerArregloRegistros();

$smarty -> assign("lista", $lista);
$smarty -> assign("pagos", $pagos);
$smarty -> assign("tiendas", $tiendas);
$smarty -> assign("horas", $horas);
$smarty -> assign("familia", $familia);
$smarty -> assign("proveedores", $proveedores);
$smarty -> assign("marcas", $marcas);
$smarty -> display("especiales/listaPrecios.tpl");

?>