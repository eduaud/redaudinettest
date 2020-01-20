<?php

include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");

$factura = $_POST['id_factura'];
$area = $_POST['area'];
$observacionesF = $_POST['observacionesF'];
$pagos = $_POST['pagos'];
$saldo = $_POST['saldo'];
$primeraFila = $_POST['primeraFila'];
$eliminados = $_POST['eliminados'];
$cobros = json_decode($_POST['cobros']);

if(count($cobros) > 0 && $primeraFila == 0){
		foreach($cobros as $registros){
				$fecha = convierteFecha($registros -> fecha);
				if($registros -> id_detalle_cobro != ""){
						$actualiza = "UPDATE ad_facturas_audicel_detalles_cobros 
									  SET fecha = '" . $fecha . "', id_forma_cobro = " . $registros -> pagos . ", documento = '" . $registros -> documento . "',
									  monto = " . $registros -> monto . ", observaciones = '" . $registros -> observaciones . "'
									  WHERE id_factura_detalle_cobro = " . $registros -> id_detalle_cobro;
						mysql_query($actualiza) or die("Error en consulta:<br> $actualiza <br>" . mysql_error());
						}
				else{
						$detalleCobros['id_control_factura'] = $factura;
						$detalleCobros['fecha'] = $fecha;
						$detalleCobros['id_forma_cobro'] = $registros -> pagos;
						$detalleCobros['documento'] = $registros -> documento;
						$detalleCobros['monto'] = $registros -> monto;
						$detalleCobros['observaciones'] = $registros -> observaciones;
						$detalleCobros['id_usuario'] = $_SESSION["USR"]->userid;
						$detalleCobros['activoCobro'] = 1;
						accionesMysql($detalleCobros, 'ad_facturas_audicel_detalles_cobros', 'Inserta');
						}
				}
		}
		if($saldo <= 0)
				$estatus = 2;
		else
				$estatus = 3;

		//SE COMENTÓ POR QUE AL GENERAR LA INFORMACION, ESTA SE GENERA AL VUELO POR CALCULO Y NO ES NECESARIO REGISTRAR O ACTUALIZAR ESTA INFORMACIÓN -->
		//$facturaActualiza = "UPDATE ad_facturas_audicel SET saldo = " .  $saldo . ", subtotal_cobros = " . $pagos . ", id_estatus_cobro_factura = " . $estatus . ", id_sucursal = " . $area . ", observaciones = '" . $observacionesF . "' WHERE id_control_factura = " . $factura;
		//mysql_query($facturaActualiza) or die("Error en consulta:<br> $facturaActualiza <br>" . mysql_error());
		//SE COMENTÓ POR QUE AL GENERAR LA INFORMACION, ESTA SE GENERA AL VUELO POR CALCULO Y NO ES NECESARIO REGISTRAR O ACTUALIZAR ESTA INFORMACIÓN <--
		$dbActualiza = "UPDATE ad_depositos_bancarios_detalle SET saldo_pendiente = " .  $saldo . " WHERE id_control_factura = " . $factura;
		mysql_query($dbActualiza) or die("Error en consulta:<br> $dbActualiza <br>" . mysql_error());
		
/******Borrado logico de los elementos que se quitaron en el grid*****************/
if($eliminados != ""){
		$borrado = "UPDATE ad_facturas_audicel_detalles_cobros SET activoCobro = 0, id_usuario_cancelo = " . $_SESSION["USR"]->userid . " WHERE id_factura_detalle_cobro IN(" . $eliminados . ")";
		mysql_query($borrado) or die("Error en consulta:<br> $borrado <br>" . mysql_error());
		}
		
echo "Registros Insertado Correctamente";

?>