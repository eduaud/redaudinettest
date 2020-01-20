<?php

include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");

$strSQL = "SELECT ad_pedidos.id_vendedor AS id_vendedor, 
		   CONCAT(na_vendedores.nombre,' ', IF(apellido_paterno is null,'',apellido_paterno),' ',IF(apellido_materno is null,'',apellido_materno)) AS vendedor
		   FROM ad_pedidos
		   LEFT JOIN na_vendedores USING(id_vendedor)
		   LEFT JOIN na_bitacora_rutas_entregas_detalle ON ad_pedidos.id_control_pedido = na_bitacora_rutas_entregas_detalle.id_partida
		   LEFT JOIN na_bitacora_rutas ON na_bitacora_rutas_entregas_detalle.id_bitacora_ruta = na_bitacora_rutas.id_bitacora_ruta
		   WHERE id_estatus_pedido = 4 AND id_estatus_pago_pedido = 2 AND ad_pedidos.id_comision IS NULL";		
		$datos = new consultarTabla($strSQL);	
		$result = $datos -> obtenerRegistros();
		foreach($result as $registros){
				$arrVendId[] = $registros -> id_vendedor;
				$arrVend[] = $registros -> vendedor;			
				}		
		$smarty->assign('vend_id', $arrVendId);
		$smarty->assign('vend_nombre',$arrVend);

$smarty->display('especiales/comisiones.tpl');

?>