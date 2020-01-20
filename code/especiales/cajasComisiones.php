<?php 
extract($_GET);
extract($_POST);

include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");

$sql="SELECT id_caja_comision,nombre FROM cl_cajas_comisiones WHERE 1";
$Comisiones = new consultarTabla($sql);
$cajaCom = $Comisiones -> obtenerArregloRegistros();

$sqlClaves="SELECT t46 FROM cl_importacion_caja_comisiones WHERE 1 GROUP BY t46";
$Claves = new consultarTabla($sqlClaves);
$aClaves = $Claves -> obtenerArregloRegistros();

$sqlTipoProd="SELECT id_tipo_producto_sky,nombre_producto_sky FROM cl_tipo_producto_sky WHERE 1";
$TipoProd = new consultarTabla($sqlTipoProd);
$aTipoProd = $TipoProd -> obtenerArregloRegistros();

$sqlPromociones="SELECT id_promocion_sky,IF(id_promocion_sky=8,CONCAT(nombre_promocion_sky,' (',clave_promocion_sky,')'),IF(id_promocion_sky=9,CONCAT(nombre_promocion_sky,' (',clave_promocion_sky,')'),nombre_promocion_sky)) FROM cl_promociones_sky WHERE 1;";
$Promociones = new consultarTabla($sqlPromociones);
$aPromociones = $Promociones -> obtenerArregloRegistros();

$sqlFuncionalidades="SELECT id_funcionalidad,nombre FROM cl_funcionalidades WHERE 1";
$Funcionalidades = new consultarTabla($sqlFuncionalidades);
$aFuncionalidades = $Funcionalidades -> obtenerArregloRegistros();

$sqlFormaPago="SELECT id_forma_pago_sky,nombre_forma_pago_sky FROM cl_forma_pago_sky WHERE 1";
$FormaPago = new consultarTabla($sqlFormaPago);
$aFormaPago = $FormaPago -> obtenerArregloRegistros();

$sqlNumEquipos="SELECT id_numero_equipo_sky,nombre_numero_equipo_sky FROM cl_numeros_equipo_sky WHERE 1";
$NumEquipos = new consultarTabla($sqlNumEquipos);
$aNumEquipos = $NumEquipos -> obtenerArregloRegistros();

$sqlPaquetes="SELECT id_paquete_sky,IF(id_paquete_sky=16,CONCAT(nombre_paquete_sky,' (',clave_paquete_sky,')'),IF(id_paquete_sky=17,CONCAT(nombre_paquete_sky,' (',clave_paquete_sky,')'),nombre_paquete_sky)) FROM cl_paquetes_sky WHERE 1";
$Paquetes = new consultarTabla($sqlPaquetes);
$aPaquetes = $Paquetes -> obtenerArregloRegistros();

$smarty->assign('a_comisiones',$cajaCom);
$smarty->assign('a_claves',$aClaves);
$smarty->assign('a_tipoProductos',$aTipoProd);
$smarty->assign('a_promociones',$aPromociones);
$smarty->assign('a_funcionalidades',$aFuncionalidades);
$smarty->assign('a_formasPago',$aFormaPago);
$smarty->assign('a_numeroEquipos',$aNumEquipos);
$smarty->assign('a_paquetes',$aPaquetes);


$smarty->display('especiales/comisionesCaja.tpl');
?>