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


$fechaIni = $_POST['fechaIni'];
$fechaFin = $_POST['fechaFin'];
$orden_de_servicio = $_POST['orden_de_servicio'];
$remesa = $_POST['remesa'];

$filtro='';

$filtro .= ' AND ad_clientes.id_cliente IN ('.$_SESSION["USR"]->clientes_relacionados.')';

if($orden_de_servicio != ''){
	$filtro .= ' AND cl_importacion_migraciones.t46 LIKE "%'.$orden_de_servicio.'%"';
} else {
	if($fechaIni != "" && $fechaFin != ""){
		if($fechaIni <= $fechaFin){
			$filtro .= ' AND cl_importacion_migraciones.d106 BETWEEN "'.cambiarFormatoFecha($fechaIni,"ymd","-").'" AND "'.cambiarFormatoFecha($fechaFin,"ymd","-").'"';
		}
	}

	if($remesa != ""){
		$filtro .= ' AND cl_importacion_migraciones.remesa like "%'.$remesa.'%"';
	}
}

$query = '
	SELECT cl_importacion_migraciones.d106 AS fecha_migracion, cl_importacion_migraciones.t46 AS orden_de_servicio
		, cl_importacion_migraciones.t47 AS clave, cl_importacion_migraciones.t48 cuadrilla
		, cl_importacion_migraciones.t49 AS cuenta, cl_importacion_migraciones.t53 AS clasificacion
		, cl_importacion_migraciones.t50 AS grupo, cl_importacion_migraciones.t51 AS tipo_de_orden
		, cl_importacion_migraciones.t52 AS subtipo_de_orden, cl_importacion_migraciones.estatus
		, cl_importacion_migraciones.observaciones AS observaciones, cl_importacion_migraciones.id_control AS id_migracion
		, cl_importacion_migraciones.factura, cl_importacion_migraciones.monto_sin_iva, ad_tasas_ivas.porcentaje AS IVA
		, cl_importacion_migraciones.remesa
		,IF(cl_importacion_migraciones.id_tipo_cliente_proveedor = 1, cl_clasificacion_de_servicios_detalle.distribuidor,
				IF(cl_importacion_migraciones.id_tipo_cliente_proveedor = 2, cl_clasificacion_de_servicios_detalle.discom,
					IF(cl_importacion_migraciones.id_tipo_cliente_proveedor = 3, cl_clasificacion_de_servicios_detalle.fuerza_externa,
						IF(cl_importacion_migraciones.id_tipo_cliente_proveedor = 4, cl_clasificacion_de_servicios_detalle.fuerza_propia, 0)
					)
				)
		 ) AS monto_a_facturar
	FROM cl_importacion_migraciones
	LEFT JOIN ad_clientes ON ad_clientes.id_cliente = cl_importacion_migraciones.id_cliente
	LEFT JOIN ad_entidades_financieras ON ad_entidades_financieras.id_entidad_financiera = cl_importacion_migraciones.id_entidad_financiera
	LEFT JOIN cl_grupos_de_servicios ON cl_grupos_de_servicios.nombre = cl_importacion_migraciones.t50
	LEFT JOIN cl_clasificacion_de_servicios ON cl_clasificacion_de_servicios.nombre = cl_importacion_migraciones.t53
	LEFT JOIN cl_clasificacion_de_servicios_detalle
		ON cl_clasificacion_de_servicios_detalle.id_clasificacion_servicios = cl_clasificacion_de_servicios.id_clasificacion_servicios
		AND cl_clasificacion_de_servicios_detalle.cantidad_a_facturar = cl_importacion_migraciones.monto_sin_iva
	LEFT JOIN cl_tipos_de_orden ON cl_tipos_de_orden.nombre = cl_importacion_migraciones.t51
	LEFT JOIN cl_subtipos_de_orden ON cl_subtipos_de_orden.nombre = cl_importacion_migraciones.t52
	LEFT JOIN cl_productos_servicios ON cl_productos_servicios.id_producto_servicio = cl_clasificacion_de_servicios.id_producto
	LEFT JOIN ad_tasas_ivas ON ad_tasas_ivas.id_tasa_iva = cl_productos_servicios.id_tasa_iva
	WHERE cl_importacion_migraciones.activo = 1 AND cl_importacion_migraciones.ultimo_movimiento = 1 
		AND cl_importacion_migraciones.estatus = "Liberado" '.$filtro.'
';

$result = mysql_query($query);
$num = mysql_num_rows($result);

echo '<br><div style="max-height: 400px; overflow: auto;">';
echo '<table class="tab_migraciones table_border">
		<tr>
			<th>No.</th>
			<th>Remesa</th>
			<th>Fecha Migraci&oacute;n</th>
			<th>Orden de Servicio</th>
			<th>Clave</th>
			<th>Cuadrilla</th>
			<th>Cuenta</th>
			<th>Clasificaci&oacute;n</th>
			<th>Grupo</th>
			<th>Tipo de Orden</th>
			<th>Subtipo de Orden</th>
			<th>Estatus</th>
			<th>Monto</th>
			<th>Observaciones</th>
			<th>Factura</th>
			';
	echo '</tr>';

$arr_estatus = array('Abierto','Cerrado','Cancelado','Reagendado');
$contador=1;
$total_sin_iva = 0;
$total_iva = 0;
$total_con_iva = 0;

if($num > 0){
	while($datos=mysql_fetch_array($result)){
		if($contador % 2 == 0){ $fondo='#d8dce3 !important;'; }
		else { $fondo='#f0f1f4 !important;'; }
		
		$iva = 0;
		if($datos["IVA"] > 0){
			$iva = ($datos["IVA"] / 100);
		}
			
		echo '<tr style="background-color: '.$fondo.'">';
			echo '<td style="font-size: 11px;">'.$contador.'</td>';
			echo '<td style="font-size: 11px;">'.$datos["remesa"].'</td>';
			echo '<td style="font-size: 11px;">'.cambiarFormatoFecha($datos["fecha_migracion"],"dmy","/").'</td>';
			echo '<td style="font-size: 11px;">'.$datos["orden_de_servicio"].'</td>';
			echo '<td style="font-size: 11px;">'.$datos["clave"].'</td>';
			echo '<td style="font-size: 11px;">'.$datos["cuadrilla"].'</td>';
			echo '<td style="font-size: 11px;">'.$datos["cuenta"].'</td>';
			echo '<td style="font-size: 11px;">'.$datos["clasificacion"].'</td>';
			echo '<td style="font-size: 11px;">'.$datos["grupo"].'</td>';
			echo '<td style="font-size: 11px;">'.$datos["tipo_de_orden"].'</td>';
			echo '<td style="font-size: 11px;">'.$datos["subtipo_de_orden"].'</td>';
			echo '<td style="font-size: 11px;">'.$datos["estatus"].'</td>';
			echo '<td style="font-size: 11px;">'.number_format($datos["monto_a_facturar"],2,'.',',').'</td>';
			echo '<td style="font-size: 11px;">'.$datos["observaciones"].'</td>';
			echo '<td style="font-size: 11px;">
					'.$datos["factura"].'
					<input id="idMigracionCheck'.$datos["id_migracion"].'" type="checkbox" name="idMigracionCheck[]" value="'.$datos["id_migracion"].'" style="display: none;">
				  </td>';
		echo '</tr>';
		
		$total_sin_iva += $datos["monto_a_facturar"];
		$total_iva += ($datos["monto_a_facturar"] * $iva);
		$total_con_iva += $datos["monto_a_facturar"] + ($datos["monto_a_facturar"] * $iva);
		
		$contador++;
	}
}

echo '</table>';
echo '</div>';

echo '<table width="100%" class="campo_small" style="padding: 15px 25px 10px 0;">';
	echo '<tr>';
		echo '<td><p style="font-weight: bold;">TOTAL: &nbsp;';
			echo ($contador-1);			
		echo '</p></td>';
	echo '</tr>';
echo '</table>';

echo '
		<div style="height: 150px; padding-left: 80%;">
			<table class="table">
				<tr>
					<th align="right"><p style="font-weight: bold;">Comision</p></th>
					<td align="right" style="width: 120px;">$ '. number_format($total_sin_iva, 2,'.',',').'</td>
				</tr>
				<tr>
					<th align="right"><p style="font-weight: bold;">IVA</p></th>
					<td align="right" style="width: 120px;">$ '. number_format($total_iva, 2,'.',',').'</td>
				</tr>
				<tr>
					<th align="right"><p style="font-weight: bold;">Total<p></th>
					<td align="right" style="width: 120px;">$ '. number_format($total_con_iva, 2,'.',',').'</td>
				</tr>
			</table>
		</div>';

echo '
	<table align="right"><tr><td><input class="boton" type="button" onclick="facturarMigraciones();" value="Facturar Migraciones"></td></tr></table>
';
?>