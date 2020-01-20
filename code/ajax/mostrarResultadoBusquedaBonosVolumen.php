<?php
include("../../conect.php");

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

$brakets = $_POST['brakets'];

if($brakets != "" && $brakets != "0"){
	$filtro .= ' AND cl_control_contratos_detalles.id_braket IN ('.$brakets.')';
}

$query='SELECT DISTINCT(cl_control_contratos_detalles.id_braket)
			, CONCAT_WS(" ",ad_clientes.nombre,ad_clientes.apellido_paterno,ad_clientes.apellido_materno) as nombreCliente
			, ad_clientes.clave as claveCliente,ad_sucursales.nombre as nombreSucursal, ad_clientes.di
			, SUM(IF(cl_control_contratos_detalles.evaluacion_en_braket = "E", 1, 0)) AS totEfectivas
			, SUM(IF(cl_control_contratos_detalles.evaluacion_en_braket != "E", 1, 0)) AS totNoefectivas
			, COUNT(cl_control_contratos_detalles.evaluacion_en_braket) AS totActivaciones
			, cl_control_contratos_detalles.monto_bono, ad_tasas_ivas.porcentaje as IVA
			, GROUP_CONCAT(cl_control_contratos_detalles.id_detalle) AS ids_detalle, cl_braket_vetv.nombre AS braket
		FROM cl_control_contratos_detalles 
		LEFT JOIN cl_control_contratos ON cl_control_contratos.id_control_contrato = cl_control_contratos_detalles.id_control_contrato 
		LEFT JOIN ad_clientes ON ad_clientes.id_cliente = cl_control_contratos_detalles.id_cliente 
		LEFT JOIN cl_tipos_cliente_proveedor ON cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = ad_clientes.id_tipo_cliente_proveedor 
		LEFT JOIN ad_sucursales ON ad_sucursales.id_sucursal = cl_control_contratos_detalles.id_sucursal 
		LEFT JOIN cl_paquetes_sky ON cl_paquetes_sky.id_paquete_sky = cl_control_contratos_detalles.id_paquete_sky 
		LEFT JOIN cl_productos_servicios ON cl_productos_servicios.id_producto_servicio = cl_control_contratos_detalles.id_producto_servicio_facturar
		LEFT JOIN ad_tasas_ivas ON ad_tasas_ivas.id_tasa_iva = cl_productos_servicios.id_tasa_iva
		LEFT JOIN cl_braket_vetv ON cl_braket_vetv.id_braket_vetv = cl_control_contratos_detalles.id_braket
		
		WHERE cl_control_contratos_detalles.id_accion_contrato = 7000 AND cl_control_contratos_detalles.activo = 1
		AND cl_control_contratos_detalles.ultimo_movimiento = 4 AND cl_control_contratos_detalles.id_cliente IN ('.$_SESSION["USR"]->clientes_relacionados.') '.$filtro.' 
		GROUP BY cl_control_contratos_detalles.id_braket';

$result = mysql_query($query);
$num = mysql_num_rows($result);

// ***   Muestra tabla de brakets   *** //
$pos = strpos($brakets, ',');
if($pos === false){
	$queryBrakets = '
		SELECT cl_braket_vetv.nombre, cl_braket_vetv_detalle.inicio, cl_braket_vetv_detalle.fin, cl_braket_vetv_detalle.monto
		FROM cl_braket_vetv
		LEFT JOIN cl_braket_vetv_detalle ON cl_braket_vetv.id_braket_vetv = cl_braket_vetv_detalle.id_braket_vetv
		WHERE cl_braket_vetv_detalle.activo = 1 AND cl_braket_vetv.id_braket_vetv = "'.$brakets.'"
	';

	$resultBrakets = mysql_query($queryBrakets);

	echo '<br><div style="margin: auto; max-width: 90%; overflow: auto;">';
		echo '<table class="table_border" style="width: 90%;">';
			$nombreBraket = '';
			while($datosBraket = mysql_fetch_array($resultBrakets)){
				if($nombreBraket != $datosBraket['nombre']){
					if($nombreBraket == ''){
						echo '<tr style="background-color: #f0f1f4 !important;">';
							echo '<th align="center">'.$datosBraket['nombre'].'</th>';
					} else {
						echo '</tr>';
						echo '<tr style="background-color: #f0f1f4 !important;">';
							echo '<th align="center">'.$datosBraket['nombre'].'</th>';
					}
					
					$nombreBraket = $datosBraket['nombre'];
				}
				echo '<td align="center">'.$datosBraket['inicio'].' - '.$datosBraket['fin'].' : $ '. number_format($datosBraket['monto'], 2, '.', ',').'</td>';
			}
			echo '</tr>';
		echo '</table>';
	echo '</div>';
}
// ***   Termina Muestra tabla de brakets   *** //

$totalComisionG=0;
$totalIVAG=0;
$totalG=0;

echo '<div style="max-height: 300px; overflow: auto;">';
echo '<table class="bonos_volumen table_border" width="100%">
		<tr>
			<th>No</th>
			<th>Periodo</th>
			<th>Cliente</th>
			<th>Clave Cliente</th>
			<th>Sucursal</th>
			<th>Total Activaciones</th>
			<th>Total Act. No Efectivas</th>
			<th>Total Act. Efectivas</th>
			<th>Monto</th>';
	  echo '<th><input id="sel-todos" type="checkbox" onclick="seleccionarCheck(this, \'bonos_volumen\');"></th>';
	echo '</tr>';
	
$contador=1;
$totalGeneralSinIVA=0;
$totalIVAG=0;
$totalG=0;

if($num > 0){
	while($datos=mysql_fetch_array($result)){
		
		if($contador % 2 == 0){ $fondo='#d8dce3 !important;'; }
		else { $fondo='#f0f1f4 !important;'; }
		
		echo '<tr style="background-color: '.$fondo.'">';
			echo '<td style="text-align: center">'.$contador.'</td>';
			echo '<td style="text-align: center">'.$datos["braket"].'</td>';
			echo '<td>'.$datos["di"].' :: '.$datos["nombreCliente"].'</td>';
			echo '<td style="text-align: center">'.$datos["claveCliente"].'</td>';
			echo '<td style="text-align: center">'.$datos["nombreSucursal"].'</td>';
			echo '<td style="text-align: center">'.$datos["totActivaciones"].'</td>';
			echo '<td style="text-align: center">'.$datos["totNoefectivas"].'</td>';
			echo '<td style="text-align: center">'.$datos["totEfectivas"].'</td>';
			
			$monto = $datos["monto_bono"] * $datos["totEfectivas"];
			
			echo '<td>
					<table width="100%">
						<tr>
							<td align="left" style="border: 0px; padding: 0px; width: 15%;">$</td>
							<td style="text-align: right; border: 0px; padding: 0px;">'. number_format($monto, 2, '.', ',').'</td>
						</tr>
					</table>
				</td>';
			echo '<td style="text-align: center">
					<input id="idBonoVolumen" type="checkbox" name="idBonoVolumen[]" value="'.$datos["ids_detalle"].'">
				  </td>';
		echo '</tr>';
		
		$ivaXproducto = 0;
		if($datos["IVA"] != '' && $datos["IVA"] > 0){
			$ivaXproducto = ($datos["IVA"] / 100);
		}
		
		$totalGeneralSinIVA += $monto / (1 + $ivaXproducto);
		$totalIVAG += ($monto / (1 + $ivaXproducto)) * $ivaXproducto;
		$totalG += (($monto / (1 + $ivaXproducto)) + (($monto / (1 + $ivaXproducto)) * $ivaXproducto));
		
		$contador++;
	}
}

echo '</table>';
echo '</div>';

echo '<table width="100%" class="campo_small" style="padding: 15px 25px 10px 0;">';
	echo '<tr>';
		echo '<td><p style="font-weight: bold;">TOTAL: &nbsp;'.($contador - 1).'</p></td>';
	echo '</tr>';
echo '</table>';

echo '<br>
		<div style="height: 150px; padding-left: 79%;">
			<table class="table">
				<tr>
					<td align="right"><p style="font-weight: bold;">Total General $</p></td>
					<td align="right" style="width: 120px;">'. number_format($totalGeneralSinIVA, 2, '.', ',').'</td>
				</tr>
				<tr>
					<td align="right"><p style="font-weight: bold;">IVA $</p></td>
					<td align="right" style="width: 120px;">'. number_format($totalIVAG, 2, '.', ' ').'</td>
				</tr>
				<tr>
					<td align="right"><p style="font-weight: bold;">Total $<p></td>
					<td align="right" style="width: 120px;">'. number_format($totalG, 2, '.', ' ').'</td>
				</tr>
			</table>
		</div>';

?>