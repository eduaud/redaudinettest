<?php
include("../../conect.php");

/***   convirte fecha a formato Y-m-d   ***/
function formatoFechaYMD($fecha){
	if(strpos($fecha,"-")) $delimitador="-";
	elseif(strpos($fecha,"/")) $delimitador="/";
	$pos=strpos($fecha,$delimitador);
	
	if($pos == 2) $iniciaPor='dia';
	elseif($pos == 4) $iniciaPor='anio';
	
	$arrFecha=explode($delimitador,$fecha);
	
	if($iniciaPor=='dia'){
		$fechaAMD=$arrFecha[2].'-'.$arrFecha[1].'-'.$arrFecha[0];
	} elseif($iniciaPor=='anio'){
		$fechaAMD=$arrFecha[0].'-'.$arrFecha[1].'-'.$arrFecha[2];
	}
	
	return $fechaAMD;
}
/***   Termina convirte fecha a formato Y-m-d   ***/

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
$id_accion_contrato = $_POST['id_accion_contrato'];
$tipo = $_POST['tipo'];
$filtro='';

$filtro .= ' AND ad_clientes.id_cliente IN ('.$_SESSION["USR"]->clientes_relacionados.')';

if($fechaIni != "" && $fechaFin != ""){
	if($fechaIni <= $fechaFin){
		$filtro .= ' AND cl_control_contratos.fecha_activacion BETWEEN "'.formatoFechaYMD($fechaIni).'" AND "'.formatoFechaYMD($fechaFin).'"';
	}
}

$query='SELECT CONCAT_WS(" ",ad_clientes.nombre,ad_clientes.apellido_paterno,ad_clientes.apellido_materno) as nombreCliente, ad_clientes.di,
			cl_control_contratos.contrato as folio,cl_control_contratos.cuenta as cuenta,cl_control_contratos.fecha_activacion as fechaActivacion,
			cl_importacion_caja_comisiones.t48 as promocion,cl_paquetes_sky.nombre_paquete_sky as paquete,id_contra_recibo as contraRecibo,cl_contrarecibos.fecha_hora as fechaContraRecibo,
			ad_sucursales.nombre,cl_importacion_caja_comisiones.t46 as clave,cl_tipos_cliente_proveedor.nombre as tipoCliente,
			IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 1, IF(cl_importacion_caja_comisiones.t46 NOT IN ("TMK01", "TMKOM", "BCRCOMV", "BCRCOM"), (cl_importacion_caja_comisiones.dc30 - cl_control_contratos_detalles.precio_suscripcion), cl_importacion_caja_comisiones.dc30),
				IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 3, (cl_importacion_caja_comisiones.dc32),
					IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 2, IF(cl_importacion_caja_comisiones.t46 NOT IN ("TMK01", "TMKOM", "BCRCOMV", "BCRCOM"), (cl_importacion_caja_comisiones.dc31 - cl_control_contratos_detalles.precio_suscripcion), cl_importacion_caja_comisiones.dc31),
						IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 4, (cl_importacion_caja_comisiones.dc33),
							IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 12, (cl_importacion_caja_comisiones.dc34),
								IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 7, (cl_importacion_caja_comisiones.dc35), 0)
							)
						)
					)
				)
			) as comision, cl_control_contratos.id_control_contrato, cl_control_contratos_detalles.id_detalle,ad_tasas_ivas.porcentaje as IVA,
			cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor, ad_clientes.di_audicel,ad_clientes.id_cliente_padre
		FROM cl_control_contratos
		LEFT JOIN cl_control_contratos_detalles
			ON cl_control_contratos.id_control_contrato = cl_control_contratos_detalles.id_control_contrato
		LEFT JOIN ad_clientes
			ON ad_clientes.id_cliente = cl_control_contratos_detalles.id_cliente
		LEFT JOIN cl_importacion_caja_comisiones
			ON cl_importacion_caja_comisiones.id_control = cl_control_contratos_detalles.id_detalle_caja_comisiones
		LEFT JOIN cl_paquetes_sky
			ON cl_paquetes_sky.id_paquete_sky = cl_control_contratos_detalles.id_paquete_sky
		LEFT JOIN ad_sucursales
			ON ad_sucursales.id_sucursal = cl_control_contratos_detalles.id_sucursal
		LEFT JOIN cl_tipos_cliente_proveedor
			ON cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = ad_clientes.id_tipo_cliente_proveedor
		LEFT JOIN cl_contrarecibos
			ON cl_contrarecibos.id_contrarecibo = cl_control_contratos_detalles.id_contra_recibo
		LEFT JOIN cl_productos_servicios
			ON cl_productos_servicios.id_producto_servicio = cl_control_contratos_detalles.id_producto_servicio_facturar
		LEFT JOIN ad_tasas_ivas
			ON ad_tasas_ivas.id_tasa_iva = cl_productos_servicios.id_tasa_iva
		WHERE cl_control_contratos_detalles.id_accion_contrato IN ('.$id_accion_contrato.') AND cl_control_contratos_detalles.id_calificacion = 1 AND
				cl_control_contratos_detalles.activo = 1 AND ultimo_movimiento="1" '.$filtro;

/*$query="SELECT IF(cl_control_contratos_detalles.id_cliente <> 0,CONCAT_WS(' ',CONCAT(ad_clientes.clave,' ::'),ad_clientes.nombre,ad_clientes.apellido_paterno,ad_clientes.apellido_materno),CONCAT_WS(' ',CONCAT(ad_entidades_financieras.clave,' ::'),ad_entidades_financieras.nombre,ad_entidades_financieras.apellido_paterno,ad_entidades_financieras.apellido_materno)) AS distribuidor,IF(cl_control_contratos_detalles.id_cliente <> 0,ad_clientes.id_cliente,ad_entidades_financieras.id_entidad_financiera) AS id_cliente,id_detalle,cl_control_contratos.id_control_contrato,contrato,cl_control_contratos.cuenta,fecha_activacion,
	CONCAT('$',FORMAT(IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 1, IF(cl_importacion_caja_comisiones.t46 NOT IN ('TMK01','TMKOM'), (cl_importacion_caja_comisiones.dc30 - cl_control_contratos_detalles.precio_suscripcion), cl_importacion_caja_comisiones.dc30),
				IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 3, (cl_importacion_caja_comisiones.dc32),
					IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 2, IF(cl_importacion_caja_comisiones.t46 != 'TMK01', (cl_importacion_caja_comisiones.dc31 - cl_control_contratos_detalles.precio_suscripcion), cl_importacion_caja_comisiones.dc31),
						IF(cl_tipos_cliente_proveedor_entidadesFin.id_tipo_cliente_proveedor = 4, (cl_importacion_caja_comisiones.dc33),
							IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 12, (cl_importacion_caja_comisiones.dc34),
								IF(cl_tipos_cliente_proveedor_entidadesFin.id_tipo_cliente_proveedor = 7, (cl_importacion_caja_comisiones.dc35),0)
							)
						)
					)
				)
			),2))" . 'as comision, cl_control_contratos.id_control_contrato, cl_control_contratos_detalles.id_detalle,ad_tasas_ivas.porcentaje as IVA,
			cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor, ad_clientes.di_audicel,ad_clientes.id_cliente_padre
		FROM cl_control_contratos
		LEFT JOIN cl_control_contratos_detalles
			ON cl_control_contratos.id_control_contrato = cl_control_contratos_detalles.id_control_contrato
		LEFT JOIN ad_clientes
			ON ad_clientes.id_cliente = cl_control_contratos_detalles.id_cliente
		LEFT JOIN cl_importacion_caja_comisiones
			ON cl_importacion_caja_comisiones.id_control = cl_control_contratos_detalles.id_detalle_caja_comisiones
		LEFT JOIN cl_paquetes_sky
			ON cl_paquetes_sky.id_paquete_sky = cl_control_contratos_detalles.id_paquete_sky
		LEFT JOIN ad_sucursales
			ON ad_sucursales.id_sucursal = cl_control_contratos_detalles.id_sucursal
		LEFT JOIN cl_tipos_cliente_proveedor
			ON cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = ad_clientes.id_tipo_cliente_proveedor
		LEFT JOIN cl_contrarecibos
			ON cl_contrarecibos.id_contrarecibo = cl_control_contratos_detalles.id_contra_recibo
		LEFT JOIN cl_productos_servicios
			ON cl_productos_servicios.id_producto_servicio = cl_control_contratos_detalles.id_producto_servicio_facturar
		LEFT JOIN ad_tasas_ivas
			ON ad_tasas_ivas.id_tasa_iva = cl_productos_servicios.id_tasa_iva
		WHERE cl_control_contratos_detalles.id_accion_contrato IN ('.$id_accion_contrato.') AND cl_control_contratos_detalles.id_calificacion = 1 AND
				cl_control_contratos_detalles.activo = 1 AND ultimo_movimiento="1" '.$filtro;*/

$result = mysql_query($query);
$num = mysql_num_rows($result);

$contador=1;
$totalComisionG=0;
$totalIVAG=0;
$totalG=0;
echo '<input type="hidden" id="numeroComisiones" value="'.$num.'"/>';
echo '<div style="max-height: 300px; overflow: auto;">';
	echo '<table class="comisiones_pendientes table_border" width="100%">
			<tr>
				<th>No</th>
				<th>Cliente</th>
				<th>Folio</th>
				<th>Cuenta</th>
				<th>Fecha de Activaci&oacute;n</th>
				<th>Promoci&oacute;n</th>
				<th>Paquete</th>
				<th>Contrarecibo</th>
				<th>Fecha de ContraRecibo</th>
				<th>Comisi&oacute;n</th>
				<th>IVA</th>
				<th>Total</th>';
		if($tipo != 'CPF'){
			echo '<th><input id="sel-todos" type="checkbox" onclick="seleccionarCheck(this, \'comisiones_pendientes\');"></th>';
		}
	echo '</tr>';
if($num > 0){
	while($datos=mysql_fetch_array($result)){
		if($contador % 2 == 0){ $fondo='#d8dce3 !important;'; }
		else { $fondo='#f0f1f4 !important;'; }
		
		$ivaXproducto = 0;
		if($datos["IVA"] != '' && $datos["IVA"] > 0){
			$ivaXproducto = ($datos["IVA"] / 100);
		}
		
		echo '<tr style="background-color: '.$fondo.'">';
			echo '<td>'.$contador.'</td>';
			
			if($datos["id_tipo_cliente_proveedor"] == '2' || $datos["id_tipo_cliente_proveedor"] == '3'){
				echo '<td>'.$datos["di_audicel"].' ('.$datos["nombreCliente"].')</td>';	
			} else {
				//echo '<td>('.$datos["id_tipo_cliente_proveedor"].' - '.$datos["id_tipo_cliente_proveedor_ef"].') '.$datos["di"].' ('.$datos["nombreCliente"].')</td>';
				echo '<td>'.$datos["di"].' ('.$datos["nombreCliente"].')</td>';
			}
			
			//echo '<td>'.$datos["nombreCliente"].'</td>';
			echo '<td>'.$datos["folio"].'</td>';
			echo '<td>'.$datos["cuenta"].'</td>';
			echo '<td style="text-align: center">'.cambiarFormatoFecha($datos["fechaActivacion"],"dmy","/").'</td>';
			echo '<td>'.$datos["promocion"].'</td>';
			echo '<td>'.$datos["paquete"].'</td>';
			echo '<td>'.$datos["contraRecibo"].'</td>';
			echo '<td style="text-align: center">'.cambiarFormatoFecha($datos["fechaContraRecibo"],"dmy","-").'</td>';
			/*echo '<td align="right">$'. number_format($datos["comision"],2,'.',',').'</td>';
			echo '<td align="right">$'.number_format(($datos["comision"] * $ivaXproducto),2,'.',',').'</td>';
			echo '<td align="right">$'. number_format(($datos["comision"] + ($datos["comision"] * $ivaXproducto)),2,'.',',').'</td>';*/
			
			if($datos["id_tipo_cliente_proveedor"] == '3' || $datos["id_tipo_cliente_proveedor"] == '12') { // ***   Total Tec Ext, Total Vendedor Ext   *** //
				echo '<td align="right">$'.number_format(round($datos["comision"],2),2,'.',',').'</td>';
				echo '<td align="right">$'.number_format(($datos["comision"] * $ivaXproducto),2,'.',',').'</td>';
				echo '<td align="right">$'.number_format(round(($datos["comision"] + ($datos["comision"] * $ivaXproducto)), 2),2,'.',',').'</td>';
			} elseif($datos["id_tipo_cliente_proveedor"] == '7' || $datos["id_tipo_cliente_proveedor"] == '4') { // ***   Total Tec FP, Total Vendedor FP   *** //
				echo '<td align="right">$'.number_format(round($datos["comision"],2),2,'.',',').'</td>';
				echo '<td align="right">$'.number_format(0,2,'.',',').'</td>';
				echo '<td align="right">$'.number_format(round($datos["comision"],2),2,'.',',').'</td>';
			} else {
				/*echo '<td align="right">$'.number_format(round(($datos["comision"]),2),2,'.',',').'</td>';
				echo '<td align="right">$'.number_format((($datos["comision"]) * $ivaXproducto),2,'.',',').'</td>';
				echo '<td align="right">$'.number_format(round((($datos["comision"]) + (($datos["comision"]) * $ivaXproducto)), 2),2,'.',',').'</td>';*/
				
				echo '<td align="right">$'.number_format(round(($datos["comision"] / ($ivaXproducto + 1)),2),2,'.',',').'</td>';
				echo '<td align="right">$'.number_format((($datos["comision"] / ($ivaXproducto + 1)) * $ivaXproducto),2,'.',',').'</td>';
				echo '<td align="right">$'.number_format(round((($datos["comision"] / ($ivaXproducto + 1)) + (($datos["comision"] / ($ivaXproducto + 1)) * $ivaXproducto)), 2),2,'.',',').'</td>';
			}
			
			if($tipo != 'CPF'){
				echo '<td><input id="idComisionPendienteCheck1" type="checkbox" name="idComisionPendiente[]" value="'.$datos["id_control_contrato"].','.$datos["id_detalle"].'"></td>';
			}
		echo '</tr>';
		
		/*$totalComisionG += $datos["comision"];
		$totalIVAG += $datos["comision"] * $ivaXproducto;
		$totalG += ($datos["comision"] + ($datos["comision"] * $ivaXproducto));*/
		
		if($datos["id_tipo_cliente_proveedor"] == '3' || $datos["id_tipo_cliente_proveedor"] == '12') { // ***   Total Tec Ext, Total Vendedor Ext   *** //
			$totalComisionG += $datos["comision"];
			$totalIVAG += ($datos["comision"] * $ivaXproducto);
			$totalG += ($datos["comision"] + ($datos["comision"] * $ivaXproducto));
		} elseif($datos["id_tipo_cliente_proveedor"] == '7' || $datos["id_tipo_cliente_proveedor"] == '4') { // ***   Total Tec FP, Total Vendedor FP   *** //
			$totalComisionG += $datos["comision"];
			$totalG += $datos["comision"];
		} else {
			/*
			$totalComisionG += ($datos["comision"]);
			$totalIVAG += ($datos["comision"]) * $ivaXproducto;
			$totalG += ($datos["comision"]) + (($datos["comision"]) * $ivaXproducto);
			*/
			
			$totalComisionG += ($datos["comision"] / ($ivaXproducto + 1));
			$totalIVAG += ($datos["comision"] / ($ivaXproducto + 1)) * $ivaXproducto;
			$totalG += ($datos["comision"] / ($ivaXproducto + 1)) + (($datos["comision"] / ($ivaXproducto + 1)) * $ivaXproducto);
		}
		
		$contador++;
	}
}
echo '</table>';
echo '</div>';

echo '<table width="100%" class="campo_small" style="padding: 15px 25px 0px 0;">';
	echo '<tr>';
		echo '<td><p style="font-weight: bold;">TOTAL: &nbsp;'.($contador - 1).'</p></td>';
	echo '</tr>';
echo '</table>';

echo '  <div style="height: 150px; padding-left: 82%;">
			<table class="table_border">
				<tr>
					<th align="right">Comision</th>
					<td align="right" style="width: 120px;">$ '. number_format($totalComisionG, 2, '.', ',').'</td>
				</tr>
				<tr>
					<th align="right">IVA</th>
					<td align="right" style="width: 120px;">$ '. number_format($totalIVAG, 2, '.', ',').'</td>
				</tr>
				<tr>
					<th align="right">Total</th>
					<td align="right" style="width: 120px;">$ '. number_format($totalG, 2, '.', ',').'</td>
				</tr>
			</table>
		</div>';
?>