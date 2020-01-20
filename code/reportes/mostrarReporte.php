<?php
include('../../conect.php');
include('../../consultaBase.php');

//***   Cambia Formato Fecha   ***//
function cambiarFormatoFecha($fecha, $formato, $delimitador_fecha){
	$fechaAMD = "";
	
	if(($delimitador_fecha=="/" || $delimitador_fecha=="-") && ($formato=="ymd" || $formato=="dmy")){
		$delimitador="";
		
		if(strpos($fecha, "-") != false) $delimitador = "-";
		elseif(strpos($fecha, "/") != false) $delimitador = "/";
		$pos = strpos($fecha, $delimitador);
		
		if($delimitador != ""){
			$iniciaPor = "";
			if($pos == 2) $iniciaPor='dia';
			elseif($pos == 4) $iniciaPor='anio';
			
			if($iniciaPor!=""){
				$arrFecha = explode($delimitador, $fecha);
				
				if($formato=="ymd"){
					if($iniciaPor=='dia'){
						$fechaAMD=$arrFecha[2].$delimitador_fecha.$arrFecha[1].$delimitador_fecha.$arrFecha[0];
					} else if($iniciaPor=='anio'){
						$fechaAMD=$arrFecha[0].$delimitador_fecha.$arrFecha[1].$delimitador_fecha.$arrFecha[2];
					}
				} elseif($formato=="dmy"){
					if($iniciaPor=='dia'){
						$fechaAMD=$arrFecha[0].$delimitador_fecha.$arrFecha[1].$delimitador_fecha.$arrFecha[2];
					} elseif($iniciaPor=='anio'){
						$fechaAMD=$arrFecha[2].$delimitador_fecha.$arrFecha[1].$delimitador_fecha.$arrFecha[0];
					}
				}
			}
		}
	}
	
	return $fechaAMD;
}
//***   Termina Cambia Formato Fecha   ***//

$styleEncabezado='font-family:Arial,Helvetica,sans-serif; font-size:11px; table-layout:fixed; color:#FFFFFF; border-color:#CCCCCC; border:solid; border-width:1px; background-color: #666; text-align:center; padding-top: 4px; padding-bottom: 4px; font-weight: normal !important;';
$styleBold='font-weight: bold;';
$styleTituloTotalParcial='font-family:Arial, Helvetica, sans-serif; font-size:10px; table-layout:fixed; color:#000000; background:#87CEFA; border-color:#CCCCCC; border:solid; border-width:1px;';
$styleTotalParcial='font-family:Arial, Helvetica, sans-serif; font-size:10px; table-layout:fixed; color:#000000; background:#B0E0E6; border-color:#CCCCCC; border:solid; border-width:1px;';
$styleTDgris='font-family:Arial, Helvetica, sans-serif; font-size:10px; color:#333333 !important; table-layout:fixed; background-color:#DCDCDC !important; padding-top: 3px; padding-bottom: 3px; padding-right: 5px; padding-left: 5px;';
$styleTDblanco='font-family:Arial, Helvetica, sans-serif; font-size:10px; color:#333333 !important; table-layout:fixed; background-color:#FFFFFF; padding-top: 3px; padding-bottom: 3px; padding-right: 5px; padding-left: 5px;';
$styleTituloTotalGeneral='font-family:Arial, Helvetica, sans-serif; font-size:11px; font-weight:bold; table-layout:fixed; color:#FFFFFF; background:#8C7B75; border-color:#6E6E6E; border:solid; border-width:1px; padding:3px;';
$styleTotalGeneral='font-family:Arial, Helvetica, sans-serif; font-size:11px; font-weight:bold; table-layout:fixed; color:#FFFFFF; background:#8C7B75; border-color:#6E6E6E; border:solid; border-width:1px; padding:3px;';

$reporte = $_GET['reporte'];
$exportar = $_GET['exportar'];

$strSQL = '';
$strWhere = '';
$strWhereCXC = '';
$strWhereCXP = '';

if($exportar == 'si'){
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Content-type: atachment/vnd.ms-excel");
	header("Content-Disposition: atachment; filename=\"$reporte.xls\";");
	header("Content-transfer-encoding: binary\n");
}

if($reporte == 'Reporte_Facturas_para_Arqueo'){
	$plazas = $_GET['plazas'];
	$clientes = $_GET['clientes'];
	$fechaIni = $_GET['fechaInicial'];
	$fechaFin = $_GET['fechaFin'];
	
	$plazas = str_replace('0,', '', $plazas);
	$clientes = str_replace('0,', '', $clientes);
	
	if($plazas != '0'){
		$strWhere.= ' AND ad_sucursales.id_sucursal IN ('.$plazas.')';
		$strWhereCXC.= ' AND ad_sucursales.id_sucursal IN ('.$plazas.')';
		$strWhereCXP.= ' AND ad_sucursales.id_sucursal IN ('.$plazas.')';
	}
	
	if($clientes != '0'){
		$strWhere.= ' AND ad_clientes.id_cliente IN ('.$clientes.')';
		$strWhereCXC.= ' AND ad_clientes.id_cliente IN ('.$clientes.')';
	}
	
	if($fechaIni != '' && $fechaFin != ''){
		$strWhere .= ' AND ad_facturas_audicel.fecha_y_hora BETWEEN "'.$fechaIni.' 00:00:00" AND "'.$fechaFin.' 23:59:59"';
		$strWhereCXC .= ' AND ad_cuentas_por_cobrar.fecha_creacion BETWEEN "'.$fechaIni.'" AND "'.$fechaFin.'"';
		$$strWhereCXP .= ' AND ad_cuentas_por_pagar_operadora.fecha_captura BETWEEN "'.$fechaIni.'" AND "'.$fechaFin.'"';
	}
	
	// ***   Tabla de Facturas   *** //
	$strSQL = '
		SELECT ad_sucursales.nombre AS sucursal, ad_clientes.di,
			CONCAT_WS(" ",ad_clientes.nombre,ad_clientes.apellido_paterno,ad_clientes.apellido_materno) AS nombreCliente
			,ad_facturas_audicel.id_factura, ad_facturas_audicel.total
			,((SELECT IF(SUM(monto) IS NULL, 0,SUM(monto)) FROM ad_depositos_bancarios_detalle WHERE id_control_documento = ad_facturas_audicel.id_control_factura AND activoDetBancarios = 1) + 
			  (SELECT IF(SUM(monto) IS NULL, 0,SUM(monto)) FROM ad_facturas_audicel_detalles_cobros WHERE id_control_factura = ad_facturas_audicel.id_control_factura AND activoCobro = 1)) AS montoPagos
		FROM ad_facturas_audicel
		LEFT JOIN ad_sucursales ON ad_sucursales.id_sucursal = ad_facturas_audicel.id_sucursal
		LEFT JOIN ad_clientes ON ad_clientes.id_cliente = ad_facturas_audicel.id_cliente
		WHERE ad_facturas_audicel.activo = 1 '.$strWhere.'
		HAVING montoPagos < ad_facturas_audicel.total
		ORDER BY ad_sucursales.nombre, ad_clientes.di, ad_facturas_audicel.id_factura
	';
	
	if($strSQL != ''){
		$result = mysql_query($strSQL);
		$numFacturas = mysql_num_rows($result);
		
		if($numFacturas > 0){
			$sucursalActualFacturas='';
			$diActualFacturas='';
			
			$totalParcialFacturas=0;
			$totalGeneralFacturas=0;
			
			echo '<table>';
				echo '<tr>';
					echo '<td style="'.$styleEncabezado.'" colspan="5">';
						echo '<span style="'.$styleBold.'">Facturas</span>';
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td style="'.$styleEncabezado.'">';
						echo '<span style="'.$styleBold.'">Sucursal</span>';
					echo '</td>';
					echo '<td style="'.$styleEncabezado.'">';
						echo '<span style="'.$styleBold.'">DI</span>';
					echo '</td>';
					echo '<td style="'.$styleEncabezado.'">';
						echo '<span style="'.$styleBold.'">Factura</span>';
					echo '</td>';
					echo '<td style="'.$styleEncabezado.'">';
						echo '<span style="'.$styleBold.'">Pagos</span>';
					echo '</td>';
					echo '<td style="'.$styleEncabezado.'">';
						echo '<span style="'.$styleBold.'">Total</span>';
					echo '</td>';
				echo '</tr>';
			while($datos=mysql_fetch_array($result)){
				if($sucursalActualFacturas != $datos["sucursal"]){
					if($sucursalActualFacturas != ''){
						echo '<tr>';
							echo '<td style="'.$styleTituloTotalParcial.'" colspan="4" align="right">';
								echo 'Total ';
							echo '</td>';
							echo '<td style="'.$styleTotalParcial.'" align="right">';
								echo '$ '.number_format($totalParcialFacturas, 2, ',', '.');
							echo '</td>';
						echo '</tr>';
						
						$totalParcialFacturas=0;
					}
					
					$sucursalActualFacturas = $datos["sucursal"];
					echo '<tr>';
						echo '<td style="'.$styleTDgris.'">';
							echo '<p style="font-weight: bold; font-size: 11px;">'.$datos["sucursal"].'</p>';
						echo '</td>';
						echo '<td style="'.$styleTDgris.'">';
							echo '';
						echo '</td>';
						echo '<td style="'.$styleTDgris.'">';
							echo '';
						echo '</td>';
						echo '<td style="'.$styleTDgris.'">';
							echo '';
						echo '</td>';
						echo '<td style="'.$styleTDgris.'">';
							echo '';
						echo '</td>';
					echo '</tr>';
				}
				
				if($diActualFacturas != $datos["di"]){
					$diActualFacturas = $datos["di"];
					echo '<tr>';
						echo '<td style="'.$styleTDblanco.'">';
							echo '<span style="'.$styleBold.'">&nbsp;</span>';
						echo '</td>';
						echo '<td style="'.$styleTDblanco.'">';
							echo $datos["di"].' <br> '.$datos["nombreCliente"];
						echo '</td>';
						echo '<td style="'.$styleTDblanco.'">';
							echo '<span style="'.$styleBold.'">&nbsp;</span>';
						echo '</td>';
						echo '<td style="'.$styleTDblanco.'">';
							echo '<span style="'.$styleBold.'">&nbsp;</span>';
						echo '</td>';
						echo '<td style="'.$styleTDblanco.'">';
							echo '<span style="'.$styleBold.'">&nbsp;</span>';
						echo '</td>';
					echo '</tr>';
				}
				
				echo '<tr>';
					echo '<td style="'.$styleTDgris.'">';
						echo '<span style="'.$styleBold.'">&nbsp;</span>';
					echo '</td>';
					echo '<td style="'.$styleTDgris.'">';
						echo '<span style="'.$styleBold.'">&nbsp;</span>';
					echo '</td>';
					echo '<td style="'.$styleTDgris.'">';
						echo $datos["id_factura"];
					echo '</td>';
					echo '<td style="'.$styleTDgris.'">';
						echo '$ '.number_format($datos["montoPagos"],2,',','.');
					echo '</td>';
					echo '<td style="'.$styleTDgris.'" align="right">';
						echo '$ '.number_format($datos["total"],2,',','.');
					echo '</td>';
				echo '</tr>';
				
				$totalParcialFacturas += $datos["total"];
				$totalGeneralFacturas += $datos["total"];
			}
			
			echo '<tr>';
				echo '<td style="'.$styleTituloTotalParcial.'" colspan="4" align="right">';
					echo 'Total ';
				echo '</td>';
				echo '<td style="'.$styleTotalParcial.'" align="right">';
					echo '$ '.number_format($totalParcialFacturas, 2, ',', '.');
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td style="'.$styleTituloTotalGeneral.'" colspan="4">';
					echo 'Total ';
				echo '</td>';
				echo '<td style="'.$styleTotalGeneral.'" align="right">';
					echo '$ '.number_format($totalGeneralFacturas, 2, ',', '.');
				echo '</td>';
			echo '</tr>';
			
			echo '</table>';
		} else {
			echo '<table width="300px;">';
				echo '<tr>';
					echo '<td style="'.$styleEncabezado.'" colspan="5">';
						echo '<span style="'.$styleBold.'">Facturas</span>';
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td style="'.$styleTDgris.'" colspan="5">';
						echo '<span style="'.$styleBold.'">Sin Informaci&oacute;n.</span>';
					echo '</td>';
				echo '</tr>';
			echo '</table>';
		}
	}
	// ***   Termina Tabla de Facturas   *** //
	
	echo '<table><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr></table>';
	
	// ***   Tabla de Cuentas por Cobrar   *** //
	$strSQLcxc = '
		SELECT ad_sucursales.nombre AS sucursal, ad_clientes.di, CONCAT_WS(" ",ad_clientes.nombre,ad_clientes.apellido_paterno,ad_clientes.apellido_materno) AS nombreCliente
			,ad_cuentas_por_cobrar.id_cuenta_por_cobrar, ad_cuentas_por_cobrar.total
			,(SELECT if(SUM(monto) IS NULL,0,SUM(monto)) FROM ad_cuentas_por_cobrar_detalle_cobros WHERE activoCobro= 1 AND ad_cuentas_por_cobrar_detalle_cobros.id_control_cuenta_por_cobrar = ad_cuentas_por_cobrar.id_control_cuenta_por_cobrar) +
			(SELECT if(SUM(monto) IS NULL,0,SUM(monto)) FROM ad_depositos_bancarios_detalle WHERE activoDetBancarios=1 AND ad_depositos_bancarios_detalle.id_control_documento = ad_cuentas_por_cobrar.id_control_cuenta_por_cobrar) AS montoPagos
		FROM ad_cuentas_por_cobrar
		LEFT JOIN ad_sucursales ON ad_sucursales.id_sucursal = ad_cuentas_por_cobrar.id_sucursal
		LEFT JOIN ad_clientes ON ad_clientes.id_cliente = ad_cuentas_por_cobrar.id_cliente
		WHERE 1 '.$strWhereCXC.'
		HAVING montoPagos < ad_cuentas_por_cobrar.total
		ORDER BY ad_sucursales.nombre, ad_clientes.di, ad_cuentas_por_cobrar.id_cuenta_por_cobrar
	';
	//echo $strSQLcxc;
	if($strSQLcxc != ''){
		$resultCXC = mysql_query($strSQLcxc);
		$numCXC = mysql_num_rows($resultCXC);
		
		if($numCXC > 0){
			$sucursalActualCXC='';
			$diActualCXC='';
			
			$totalParcialCXC=0;
			$totalGeneralCXC=0;
			
			echo '<table>';
				echo '<tr>';
					echo '<td style="'.$styleEncabezado.'" colspan="5">';
						echo '<span style="'.$styleBold.'">Cuentas por Cobrar</span>';
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td style="'.$styleEncabezado.'">';
						echo '<span style="'.$styleBold.'">Sucursal</span>';
					echo '</td>';
					echo '<td style="'.$styleEncabezado.'">';
						echo '<span style="'.$styleBold.'">DI</span>';
					echo '</td>';
					echo '<td style="'.$styleEncabezado.'">';
						echo '<span style="'.$styleBold.'">Cuenta por Cobrar</span>';
					echo '</td>';
					echo '<td style="'.$styleEncabezado.'">';
						echo '<span style="'.$styleBold.'">Pagos</span>';
					echo '</td>';
					echo '<td style="'.$styleEncabezado.'">';
						echo '<span style="'.$styleBold.'">Total</span>';
					echo '</td>';
				echo '</tr>';
			while($datos=mysql_fetch_array($resultCXC)){
				if($sucursalActualCXC != $datos["sucursal"]){
					if($sucursalActualCXC != ''){
						echo '<tr>';
							echo '<td style="'.$styleTituloTotalParcial.'" colspan="4" align="right">';
								echo 'Total ';
							echo '</td>';
							echo '<td style="'.$styleTotalParcial.'" align="right">';
								echo '$ '.number_format($totalParcialCXC, 2, ',', '.');
							echo '</td>';
						echo '</tr>';
						
						$totalParcialCXC=0;
					}
					
					$sucursalActualCXC = $datos["sucursal"];
					echo '<tr>';
						echo '<td style="'.$styleTDgris.'">';
							echo '<p style="font-weight: bold; font-size: 11px;">'.$datos["sucursal"].'</p>';
						echo '</td>';
						echo '<td style="'.$styleTDgris.'">';
							echo '';
						echo '</td>';
						echo '<td style="'.$styleTDgris.'">';
							echo '';
						echo '</td>';
						echo '<td style="'.$styleTDgris.'">';
							echo '';
						echo '</td>';
						echo '<td style="'.$styleTDgris.'">';
							echo '';
						echo '</td>';
					echo '</tr>';
				}
				
				if($diActualCXC != $datos["di"]){
					$diActualCXC = $datos["di"];
					echo '<tr>';
						echo '<td style="'.$styleTDblanco.'">';
							echo '<span style="'.$styleBold.'">&nbsp;</span>';
						echo '</td>';
						echo '<td style="'.$styleTDblanco.'">';
							echo $datos["di"].' <br> '.$datos["nombreCliente"];
						echo '</td>';
						echo '<td style="'.$styleTDblanco.'">';
							echo '<span style="'.$styleBold.'">&nbsp;</span>';
						echo '</td>';
						echo '<td style="'.$styleTDblanco.'">';
							echo '<span style="'.$styleBold.'">&nbsp;</span>';
						echo '</td>';
						echo '<td style="'.$styleTDblanco.'">';
							echo '<span style="'.$styleBold.'">&nbsp;</span>';
						echo '</td>';
					echo '</tr>';
				}
				
				echo '<tr>';
					echo '<td style="'.$styleTDgris.'">';
						echo '<span style="'.$styleBold.'">&nbsp;</span>';
					echo '</td>';
					echo '<td style="'.$styleTDgris.'">';
						echo '<span style="'.$styleBold.'">&nbsp;</span>';
					echo '</td>';
					echo '<td style="'.$styleTDgris.'">';
						echo $datos["id_cuenta_por_cobrar"];
					echo '</td>';
					echo '<td style="'.$styleTDgris.'">';
						echo '$ '.number_format($datos["montoPagos"],2,',','.');
					echo '</td>';
					echo '<td style="'.$styleTDgris.'" align="right">';
						echo '$ '.number_format($datos["total"],2,',','.');
					echo '</td>';
				echo '</tr>';
				
				$totalParcialCXC += $datos["total"];
				$totalGeneralCXC += $datos["total"];
			}
			
			echo '<tr>';
				echo '<td style="'.$styleTituloTotalParcial.'" colspan="4" align="right">';
					echo 'Total ';
				echo '</td>';
				echo '<td style="'.$styleTotalParcial.'" align="right">';
					echo '$ '.number_format($totalParcialCXC, 2, ',', '.');
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td style="'.$styleTituloTotalGeneral.'" colspan="4">';
					echo 'Total ';
				echo '</td>';
				echo '<td style="'.$styleTotalGeneral.'" align="right">';
					echo '$ '.number_format($totalGeneralCXC, 2, ',', '.');
				echo '</td>';
			echo '</tr>';
			
			echo '</table>';
		} else {
			echo '<table width="300px;">';
				echo '<tr>';
					echo '<td style="'.$styleEncabezado.'" colspan="5">';
						echo '<span style="'.$styleBold.'">Cuentas por Cobrar</span>';
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td style="'.$styleTDgris.'" colspan="5">';
						echo '<span style="'.$styleBold.'">Sin Informaci&oacute;n.</span>';
					echo '</td>';
				echo '</tr>';
			echo '</table>';
		}
	}
	// ***   Terminar Tabla de Cuentas por Cobrar   *** //
	
	echo '<table><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr></table>';
	
	// ***   Tabla de Cuentas por Pagar   *** //
	$strSQLcxp = '
		SELECT ad_sucursales.nombre AS sucursal,ad_cuentas_por_pagar_operadora.numero_documento, ad_cuentas_por_pagar_operadora.total
			,((SELECT if( SUM(monto) is null,0,SUM(monto)) FROM ad_cuentas_por_pagar_operadora_detalle_pagos WHERE activoDetCXP=1 AND id_cuenta_por_pagar = ad_cuentas_por_pagar_operadora.id_cuenta_por_pagar) +
			(SELECT if( SUM(monto) is null,0,SUM(monto)) FROM ad_egresos_detalle WHERE activoDetEgreso=1 AND id_cuenta_por_pagar = ad_cuentas_por_pagar_operadora.id_cuenta_por_pagar)) AS montoPagos
		FROM ad_cuentas_por_pagar_operadora
		LEFT JOIN ad_sucursales ON ad_sucursales.id_sucursal = ad_cuentas_por_pagar_operadora.id_sucursal
		WHERE 1 AND ad_cuentas_por_pagar_operadora.activo = 1 '.$strWhereCXP.'
		HAVING montoPagos < ad_cuentas_por_pagar_operadora.total
		ORDER BY ad_sucursales.nombre, ad_cuentas_por_pagar_operadora.numero_documento
	';
	
	if($strSQLcxp != ''){
		$resultCXP = mysql_query($strSQLcxp);
		$numCXP = mysql_num_rows($resultCXP);
		
		if($numCXP > 0){
			$sucursalActualCXC='';
			$diActualCXC='';
			
			$totalParcialCXC=0;
			$totalGeneralCXC=0;
			
			echo '<table>';
				echo '<tr>';
					echo '<td style="'.$styleEncabezado.'" colspan="4">';
						echo '<span style="'.$styleBold.'">Cuentas por Pagar</span>';
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td style="'.$styleEncabezado.'">';
						echo '<span style="'.$styleBold.'">Sucursal</span>';
					echo '</td>';
					echo '<td style="'.$styleEncabezado.'">';
						echo '<span style="'.$styleBold.'">Cuenta por Pagar</span>';
					echo '</td>';
					echo '<td style="'.$styleEncabezado.'">';
						echo '<span style="'.$styleBold.'">Pagos</span>';
					echo '</td>';
					echo '<td style="'.$styleEncabezado.'">';
						echo '<span style="'.$styleBold.'">Total</span>';
					echo '</td>';
				echo '</tr>';
			while($datos=mysql_fetch_array($resultCXP)){
				if($sucursalActualCXC != $datos["sucursal"]){
					if($sucursalActualCXC != ''){
						echo '<tr>';
							echo '<td style="'.$styleTituloTotalParcial.'" colspan="3" align="right">';
								echo 'Total ';
							echo '</td>';
							echo '<td style="'.$styleTotalParcial.'" align="right">';
								echo '$ '.number_format($totalParcialCXC, 2, ',', '.');
							echo '</td>';
						echo '</tr>';
						
						$totalParcialCXC=0;
					}
					
					$sucursalActualCXC = $datos["sucursal"];
					echo '<tr>';
						echo '<td style="'.$styleTDgris.'">';
							echo '<p style="font-weight: bold; font-size: 11px;">'.$datos["sucursal"].'</p>';
						echo '</td>';
						echo '<td style="'.$styleTDgris.'">';
							echo '';
						echo '</td>';
						echo '<td style="'.$styleTDgris.'">';
							echo '';
						echo '</td>';
						echo '<td style="'.$styleTDgris.'">';
							echo '';
						echo '</td>';
					echo '</tr>';
				}
				
				echo '<tr>';
					echo '<td style="'.$styleTDgris.'">';
						echo '<span style="'.$styleBold.'">&nbsp;</span>';
					echo '</td>';
					echo '<td style="'.$styleTDgris.'">';
						echo $datos["numero_documento"];
					echo '</td>';
					echo '<td style="'.$styleTDgris.'">';
						echo '$ '.number_format($datos["montoPagos"],2,',','.');
					echo '</td>';
					echo '<td style="'.$styleTDgris.'" align="right">';
						echo '$ '.number_format($datos["total"],2,',','.');
					echo '</td>';
				echo '</tr>';
				
				$totalParcialCXC += $datos["total"];
				$totalGeneralCXC += $datos["total"];
			}
			
			echo '<tr>';
				echo '<td style="'.$styleTituloTotalParcial.'" colspan="3" align="right">';
					echo 'Total ';
				echo '</td>';
				echo '<td style="'.$styleTotalParcial.'" align="right">';
					echo '$ '.number_format($totalParcialCXC, 2, ',', '.');
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td style="'.$styleTituloTotalGeneral.'" colspan="3">';
					echo 'Total ';
				echo '</td>';
				echo '<td style="'.$styleTotalGeneral.'" align="right">';
					echo '$ '.number_format($totalGeneralCXC, 2, ',', '.');
				echo '</td>';
			echo '</tr>';
			
			echo '</table>';
		} else {
			echo '<table width="300px;">';
				echo '<tr>';
					echo '<td style="'.$styleEncabezado.'" colspan="4">';
						echo '<span style="'.$styleBold.'">Cuentas por Pagar</span>';
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td style="'.$styleTDgris.'" colspan="4">';
						echo '<span style="'.$styleBold.'">Sin Informaci&oacute;n.</span>';
					echo '</td>';
				echo '</tr>';
			echo '</table>';
		}
	}
	
	// ***   Terminar Tabla de Cuentas por Pagar   *** //
	
	
// ***   ------------------------------------------------------------------------------------------------------------------------------------------   *** //
	
	
} elseif($reporte == 'Relacion_de_Facturas_por_Pagar'){
	$plazas = $_GET['plazas'];
	$clientes = $_GET['clientes'];
	$fechaIni = $_GET['fechaInicial'];
	$fechaFin = $_GET['fechaFin'];
	
	$plazas = str_replace('0,', '', $plazas);
	$clientes = str_replace('0,', '', $clientes);
	
	if($plazas != '0'){
		$strWhere.= ' AND ad_clientes.id_sucursal IN ('.$plazas.')';
	}
	
	if($clientes != '0'){
		$strWhere.= ' AND ad_clientes.id_cliente IN ('.$clientes.')';
	}
	
	if($fechaIni != '' && $fechaFin != ''){
		$strWhere .= ' AND cl_arqueos_distribuidores.fecha BETWEEN "'.$fechaIni.'" AND "'.$fechaFin.'"';
	}
	
	$strSQL = '
		SELECT cl_arqueos_distribuidores.id_arqueo,cl_arqueos_distribuidores.clave_cliente, CONCAT_WS(" ",ad_clientes.nombre,ad_clientes.apellido_paterno,ad_clientes.apellido_materno) AS nombreCliente
			,ad_cuentas_por_pagar_operadora.fecha_documento , ad_cuentas_por_pagar_operadora.numero_documento, ad_egresos_puente_detalle.monto
		FROM ad_egresos_puente_detalle
		LEFT JOIN ad_egresos_puente ON ad_egresos_puente.id_egreso_puente = ad_egresos_puente_detalle.id_egreso
		LEFT JOIN cl_arqueos_distribuidores ON cl_arqueos_distribuidores.id_arqueo = ad_egresos_puente.id_arqueo
		LEFT JOIN ad_clientes ON ad_clientes.id_cliente = ad_egresos_puente.id_distribuidor
		LEFT JOIN ad_cuentas_por_pagar_operadora ON ad_cuentas_por_pagar_operadora.id_cuenta_por_pagar = ad_egresos_puente_detalle.id_cuenta_por_pagar
		WHERE ad_egresos_puente.id_tipo_cheque IN (1,2) '.$strWhere.'
		ORDER BY cl_arqueos_distribuidores.clave_cliente,CONCAT_WS(" ",ad_clientes.nombre,ad_clientes.apellido_paterno,ad_clientes.apellido_materno)
	';
	
	$result = mysql_query($strSQL);
	$num = mysql_num_rows($result);
	
	if($num > 0){
		echo '<table>';
			echo '<tr>';
				echo '<td style="'.$styleEncabezado.'">';
					echo '<span style="'.$styleBold.'">Clave ORPA</span>';
				echo '</td>';
				echo '<td style="'.$styleEncabezado.'">';
					echo '<span style="'.$styleBold.'">Distribuidor</span>';
				echo '</td>';
				echo '<td style="'.$styleEncabezado.'">';
					echo '<span style="'.$styleBold.'">Fecha</span>';
				echo '</td>';
				echo '<td style="'.$styleEncabezado.'">';
					echo '<span style="'.$styleBold.'">Cuenta por Pagar</span>';
				echo '</td>';
				echo '<td style="'.$styleEncabezado.'">';
					echo '<span style="'.$styleBold.'">Importe</span>';
				echo '</td>';
				echo '<td style="'.$styleEncabezado.'">';
					echo '<span style="'.$styleBold.'">Concepto</span>';
				echo '</td>';
			echo '</tr>';
		
		$clave_cliente_aux='';
		$totalParcial=0;
		$id_arqueo = '';
		
		$conteoParaColorCelda=1;
		while($datos=mysql_fetch_array($result)){
			$id_arqueo = $datos["id_arqueo"];
			$colorcelda = $conteoParaColorCelda % 2 == 0 ? $styleTDgris : $styleTDblanco;
			
			if($clave_cliente_aux != $datos["clave_cliente"]){
				if($clave_cliente_aux != ''){
					echo '<tr>';
						echo '<td style="'.$styleTituloTotalGeneral.'" colspan="4">';
							echo 'Total ';
						echo '</td>';
						echo '<td style="'.$styleTotalGeneral.'" align="right">';
							echo '$ '.number_format($totalParcial, 2, '.', ',');
						echo '</td>';
						echo '<td style="'.$colorcelda.'">';
							echo 'Factura DIST. SKY';
						echo '</td>';
					echo '</tr>';
					
					// ***   Cheque que endosara Distribuidor   *** //
					$sqlTotDepositos='
						SELECT 
						((SELECT SUM(ad_depositos_bancarios_puente_detalle.monto) AS totDepositos
						FROM ad_depositos_bancarios_puente
						LEFT JOIN cl_arqueos_distribuidores ON cl_arqueos_distribuidores.id_arqueo = ad_depositos_bancarios_puente.id_arqueo
						LEFT JOIN ad_depositos_bancarios_puente_detalle ON ad_depositos_bancarios_puente_detalle.id_deposito_bancario = ad_depositos_bancarios_puente.id_deposito_bancario_puente
						WHERE ad_depositos_bancarios_puente_detalle.id_tipo_documento_deposito = 1
						AND ad_depositos_bancarios_puente.id_tipo_cheque = 1
						AND cl_arqueos_distribuidores.id_arqueo = '.$id_arqueo.') +

						(SELECT SUM(ad_depositos_bancarios_puente_detalle.monto) AS total
						FROM ad_depositos_bancarios_puente_detalle
						LEFT JOIN ad_depositos_bancarios_puente ON ad_depositos_bancarios_puente.id_deposito_bancario_puente = ad_depositos_bancarios_puente_detalle.id_deposito_bancario
						LEFT JOIN ad_cuentas_por_cobrar ON ad_cuentas_por_cobrar.id_control_cuenta_por_cobrar = ad_depositos_bancarios_puente_detalle.id_control_documento
						LEFT JOIN cl_facturas_conceptos_encabezado ON cl_facturas_conceptos_encabezado.id_concepto = ad_cuentas_por_cobrar.id_concepto
						LEFT JOIN cl_arqueos_distribuidores ON cl_arqueos_distribuidores.id_arqueo = ad_depositos_bancarios_puente.id_arqueo
						WHERE cl_arqueos_distribuidores.id_arqueo = '.$id_arqueo.'
						AND ad_depositos_bancarios_puente_detalle.id_tipo_documento_deposito = 2
						GROUP BY cl_facturas_conceptos_encabezado.nombre
						HAVING cl_facturas_conceptos_encabezado.nombre IS NULL
						ORDER BY cl_facturas_conceptos_encabezado.nombre)) AS chequeParaEndosar
					';
					$resultTotDepositos=mysql_query($sqlTotDepositos);
					$numTotDepositos = mysql_num_rows($resultTotDepositos);
					
					$chequeParaEndosar = 0;
					if($numTotDepositos > 0){
						while($datosTotDepositos = mysql_fetch_array($resultTotDepositos)){
							$chequeParaEndosar = $datosTotDepositos['chequeParaEndosar'];
							echo '<tr>';
								echo '<td style="'.$styleTituloTotalParcial.'" colspan="4">';
									echo '';
								echo '</td>';
								echo '<td style="'.$styleTotalParcial.'" align="right">';
									echo '$ '.number_format($chequeParaEndosar, 2, '.', ',');
								echo '</td>';
								echo '<td style="'.$colorcelda.'">';
									echo 'Distribuidor endosara este cheque.';
								echo '</td>';
							echo '</tr>';
						}
					}
					// ***   Termina Cheque que endosara Distribuidor   *** //
					
					
					// ***   Cheque que Cobrara Distribuidor   *** //
					$sqlTotDepositos='
						SELECT SUM(ad_egresos_puente_detalle.monto) AS totChequeDistribuidor
					FROM ad_egresos_puente_detalle
					LEFT JOIN ad_egresos_puente ON ad_egresos_puente.id_egreso_puente = ad_egresos_puente_detalle.id_egreso
					LEFT JOIN cl_arqueos_distribuidores ON cl_arqueos_distribuidores.id_arqueo = ad_egresos_puente.id_arqueo
					WHERE ad_egresos_puente.id_tipo_cheque = 2
					AND cl_arqueos_distribuidores.id_arqueo = '.$id_arqueo.'
					';
					$resultTotDepositos=mysql_query($sqlTotDepositos);
					$numTotDepositos = mysql_num_rows($resultTotDepositos);
					
					$totChequeDistribuidor = 0;
					if($numTotDepositos > 0){
						while($datosTotDepositos = mysql_fetch_array($resultTotDepositos)){
							$totChequeDistribuidor = $datosTotDepositos['totChequeDistribuidor'];
							echo '<tr>';
								echo '<td style="'.$styleTituloTotalParcial.'" colspan="4">';
									echo '';
								echo '</td>';
								echo '<td style="'.$styleTotalParcial.'" align="right">';
									echo '$ '.number_format($totChequeDistribuidor, 2, '.', ',');
								echo '</td>';
								echo '<td style="'.$colorcelda.'">';
									echo 'Distribuidor cobrara este cheque.';
								echo '</td>';
							echo '</tr>';
						}
					}
					// ***   Termina Cheque que Cobrara Distribuidor   *** //
					
					echo '<tr>';
						echo '<td style="'.$styleTituloTotalGeneral.'" colspan="4">';
							echo '';
						echo '</td>';
						echo '<td style="'.$styleTotalGeneral.'" align="right">';
							echo '$ '.number_format(($totChequeDistribuidor + $chequeParaEndosar), 2, '.', ',');
						echo '</td>';
						echo '<td style="'.$colorcelda.'">';
							echo '';
						echo '</td>';
					echo '</tr>';
					
					$totalParcial = 0;
				}
				
				echo '<tr>';
					echo '<td style="'.$colorcelda.'">';
						echo $datos["clave_cliente"];
					echo '</td>';
					echo '<td style="'.$colorcelda.'">';
						echo $datos["nombreCliente"];
					echo '</td>';
					echo '<td style="'.$colorcelda.'">';
						echo '';
					echo '</td>';
					echo '<td style="'.$colorcelda.'">';
						echo '';
					echo '</td>';
					echo '<td style="'.$colorcelda.'">';
						echo '';
					echo '</td>';
					echo '<td style="'.$colorcelda.'">';
						echo '';
					echo '</td>';
				echo '</tr>';
				
				$clave_cliente_aux = $datos["clave_cliente"];
				
				$conteoParaColorCelda++;
				$colorcelda = $conteoParaColorCelda % 2 == 0 ? $styleTDgris : $styleTDblanco;
			}
			
			echo '<tr>';
				echo '<td style="'.$colorcelda.'">';
					echo '';
				echo '</td>';
				echo '<td style="'.$colorcelda.'">';
					echo '';
				echo '</td>';
				echo '<td style="'.$colorcelda.'">';
					echo cambiarFormatoFecha($datos["fecha_documento"], "dmy", "/");
				echo '</td>';
				echo '<td style="'.$colorcelda.'">';
					echo $datos["numero_documento"];
				echo '</td>';
				echo '<td style="'.$colorcelda.'" align="right">';
					echo '$ '.number_format($datos["monto"], 2, ".", ",");
				echo '</td>';
				echo '<td style="'.$colorcelda.'">';
					echo '';
				echo '</td>';
			echo '</tr>';
			
			$totalParcial += $datos["monto"];
			
			$conteoParaColorCelda++;
		}
		
		echo '<tr>';
			echo '<td style="'.$styleTituloTotalGeneral.'" colspan="4">';
				echo 'Total ';
			echo '</td>';
			echo '<td style="'.$styleTotalGeneral.'" align="right">';
				echo '$ '.number_format($totalParcial, 2, '.', ',');
			echo '</td>';
			echo '<td style="'.$colorcelda.'">';
				echo 'Factura DIST. SKY';
			echo '</td>';
		echo '</tr>';
		
		// ***   Cheque que endosara Distribuidor   *** //
		$sqlTotDepositos='
			SELECT 
			((SELECT SUM(ad_depositos_bancarios_puente_detalle.monto) AS totDepositos
			FROM ad_depositos_bancarios_puente
			LEFT JOIN cl_arqueos_distribuidores ON cl_arqueos_distribuidores.id_arqueo = ad_depositos_bancarios_puente.id_arqueo
			LEFT JOIN ad_depositos_bancarios_puente_detalle ON ad_depositos_bancarios_puente_detalle.id_deposito_bancario = ad_depositos_bancarios_puente.id_deposito_bancario_puente
			WHERE ad_depositos_bancarios_puente_detalle.id_tipo_documento_deposito = 1
			AND ad_depositos_bancarios_puente.id_tipo_cheque = 1
			AND cl_arqueos_distribuidores.id_arqueo = '.$id_arqueo.') +

			(SELECT SUM(ad_depositos_bancarios_puente_detalle.monto) AS total
			FROM ad_depositos_bancarios_puente_detalle
			LEFT JOIN ad_depositos_bancarios_puente ON ad_depositos_bancarios_puente.id_deposito_bancario_puente = ad_depositos_bancarios_puente_detalle.id_deposito_bancario
			LEFT JOIN ad_cuentas_por_cobrar ON ad_cuentas_por_cobrar.id_control_cuenta_por_cobrar = ad_depositos_bancarios_puente_detalle.id_control_documento
			LEFT JOIN cl_facturas_conceptos_encabezado ON cl_facturas_conceptos_encabezado.id_concepto = ad_cuentas_por_cobrar.id_concepto
			LEFT JOIN cl_arqueos_distribuidores ON cl_arqueos_distribuidores.id_arqueo = ad_depositos_bancarios_puente.id_arqueo
			WHERE cl_arqueos_distribuidores.id_arqueo = '.$id_arqueo.'
			AND ad_depositos_bancarios_puente_detalle.id_tipo_documento_deposito = 2
			GROUP BY cl_facturas_conceptos_encabezado.nombre
			HAVING cl_facturas_conceptos_encabezado.nombre IS NULL
			ORDER BY cl_facturas_conceptos_encabezado.nombre)) AS chequeParaEndosar
		';
		$resultTotDepositos=mysql_query($sqlTotDepositos);
		$numTotDepositos = mysql_num_rows($resultTotDepositos);
		
		$chequeParaEndosar = 0;
		if($numTotDepositos > 0){
			while($datosTotDepositos = mysql_fetch_array($resultTotDepositos)){
				$chequeParaEndosar = $datosTotDepositos['chequeParaEndosar'];
				echo '<tr>';
					echo '<td style="'.$styleTituloTotalParcial.'" colspan="4">';
						echo '';
					echo '</td>';
					echo '<td style="'.$styleTotalParcial.'" align="right">';
						echo '$ '.number_format($chequeParaEndosar, 2, '.', ',');
					echo '</td>';
					echo '<td style="'.$colorcelda.'">';
						echo 'Distribuidor endosara este cheque.';
					echo '</td>';
				echo '</tr>';
			}
		}
		// ***   Termina Cheque que endosara Distribuidor   *** //
		
		
		// ***   Cheque que Cobrara Distribuidor   *** //
		$sqlTotDepositos='
			SELECT SUM(ad_egresos_puente_detalle.monto) AS totChequeDistribuidor
		FROM ad_egresos_puente_detalle
		LEFT JOIN ad_egresos_puente ON ad_egresos_puente.id_egreso_puente = ad_egresos_puente_detalle.id_egreso
		LEFT JOIN cl_arqueos_distribuidores ON cl_arqueos_distribuidores.id_arqueo = ad_egresos_puente.id_arqueo
		WHERE ad_egresos_puente.id_tipo_cheque = 2
		AND cl_arqueos_distribuidores.id_arqueo = '.$id_arqueo.'
		';
		$resultTotDepositos=mysql_query($sqlTotDepositos);
		$numTotDepositos = mysql_num_rows($resultTotDepositos);
		
		$totChequeDistribuidor = 0;
		if($numTotDepositos > 0){
			while($datosTotDepositos = mysql_fetch_array($resultTotDepositos)){
				$totChequeDistribuidor = $datosTotDepositos['totChequeDistribuidor'];
				echo '<tr>';
					echo '<td style="'.$styleTituloTotalParcial.'" colspan="4">';
						echo '';
					echo '</td>';
					echo '<td style="'.$styleTotalParcial.'" align="right">';
						echo '$ '.number_format($totChequeDistribuidor, 2, '.', ',');
					echo '</td>';
					echo '<td style="'.$colorcelda.'">';
						echo 'Distribuidor cobrara este cheque.';
					echo '</td>';
				echo '</tr>';
			}
		}
		// ***   Termina Cheque que Cobrara Distribuidor   *** //
		
		echo '<tr>';
			echo '<td style="'.$styleTituloTotalGeneral.'" colspan="4">';
				echo '';
			echo '</td>';
			echo '<td style="'.$styleTotalGeneral.'" align="right">';
				echo '$ '.number_format(($totChequeDistribuidor + $chequeParaEndosar), 2, '.', ',');
			echo '</td>';
			echo '<td style="'.$colorcelda.'">';
				echo '';
			echo '</td>';
		echo '</tr>';
		
		echo '</table>';
	} else {
		echo '<table>';
			echo '<tr>';
				echo '<td style="'.$styleEncabezado.'">';
					echo '<span style="'.$styleBold.'">Clave ORPA</span>';
				echo '</td>';
				echo '<td style="'.$styleEncabezado.'">';
					echo '<span style="'.$styleBold.'">Distribuidor</span>';
				echo '</td>';
				echo '<td style="'.$styleEncabezado.'">';
					echo '<span style="'.$styleBold.'">Fecha</span>';
				echo '</td>';
				echo '<td style="'.$styleEncabezado.'">';
					echo '<span style="'.$styleBold.'">Cuenta por Pagar</span>';
				echo '</td>';
				echo '<td style="'.$styleEncabezado.'">';
					echo '<span style="'.$styleBold.'">Importe</span>';
				echo '</td>';
				echo '<td style="'.$styleEncabezado.'">';
					echo '<span style="'.$styleBold.'">Concepto</span>';
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td style="'.$styleTDgris.'" colspan="6">';
					echo 'Sin Informaci&oacute;n.';
				echo '</td>';
			echo '</tr>';
		echo '</table>';
	}
	
	
	// ***   ---------------------------------------------------------------------------------------------------------------   *** //
}
?>