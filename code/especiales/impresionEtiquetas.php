<?php

include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");

if($_SESSION["USR"]->sucursalid == 0){
		$consultaTiendas = "SELECT id_sucursal, nombre FROM na_sucursales WHERE activo = 1 AND id_sucursal <> 0 ORDER BY nombre";
		}
else{
		$consultaTiendas = "SELECT id_sucursal, nombre FROM na_sucursales WHERE activo = 1 AND id_sucursal = " . $_SESSION["USR"]->sucursalid . " ORDER BY nombre";
		}
$datosTiendas = new consultarTabla($consultaTiendas);
$tiendas = $datosTiendas -> obtenerArregloRegistros();

$consultaFamilia = "SELECT id_familia_producto, nombre FROM na_familias_productos WHERE activo = 1 ORDER BY nombre";
$datosFamilia = new consultarTabla($consultaFamilia);
$familia = $datosFamilia -> obtenerArregloRegistros();

$consultaLista = "SELECT id_lista_precios, nombre FROM ad_lista_precios WHERE id_lista_precios = 1 ORDER BY nombre";
$datosLista = new consultarTabla($consultaLista);
$lista = $datosLista -> obtenerArregloRegistros();
$smarty -> assign("lista", $lista);

$smarty -> assign("tiendas", $tiendas);
$smarty -> assign("familia", $familia);
$smarty -> display("especiales/impresionEtiquetas.tpl");