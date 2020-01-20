<?php
if(!isset($caso)){
	//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../conect.php");
	include("../general/funciones.php");
	include("../../consultaBase.php");
	$factura = $_GET['doc'];
	include_once("../../include/fpdf153/fpdf.php");
	include_once("../../code/pdf/qrcode.php");	
	include("../../code/pdf/facturaAudicelPenalizacion_recibida_pdf.php");
	//include("../../code/pdf/facturaAudicelBonos_pdf.php");
	include("../../code/pdf/facturaAudicel_recibida_pdf.php");
	generaPDF($factura,'','');
}
function generaPDF($factura,$rutaPDF,$caso){
	Global $accion;
	Global $foliocfdi;
	Global $certificado;
	Global $lugar_fecha_certificacion;
	Global $cliente;
	Global $direccion_1;
	Global $direccion_2;
	Global $direccion_3;
	Global $rfc_r;
	Global $serie;
	Global $folio_fiscal;
	Global $regimen;
	Global $compania;
	Global $certificado_digital;
	Global $sello;
        Global $selloSAT;
	Global $cadena_original;
	Global $total;
	Global $subtotal;
	Global $iva;
	Global $img_qr;
	Global $moneda;
	Global $clave_cliente;
	Global $direccionCompania;
	Global $direccionCompania1;
	Global $direccionCompania2;
	Global $telefonos;
	
	Global $direccion_sucursal;
	Global $direccion_sucursal1;
	Global $direccion_sucursal2;
	
	Global $metodo_pago;
	Global $cuenta;
        Global $descripcion_forma_pago;
	
	Global $sucursal;
	Global $folio;
	Global $fecha_pedido;
	Global $tipo_pago;
	Global $tipo_pago;
	Global $observaciones;
	Global $logo;
        
        Global $nombreMetodoPago;
        Global $claveMetodoPago;


        $logo = ROOTPATH.'/imagenes/audinet.png';
	
        $sql = "SELECT
			ad_clientes.clave AS clave_cliente,
			certificado,
			sello,
			CONCAT(DATE_FORMAT(fecha_aplicacion_sello, '%Y-%m-%d'), 'T', TIME_FORMAT(fecha_aplicacion_sello, '%H:%i:%s')) as fecha,
			FORMAT(total, 2) AS total,
			FORMAT(subtotal, 2) AS subtotal,
			FORMAT(descuento, 2) AS descuento,  
			FORMAT(retencion_iva, 2) AS retencion_iva,  
			FORMAT(retencion_isr, 2) AS retencion_isr,  
			FORMAT(ieps, 2) AS ieps,
			f.no_certificado, 
			c.rfc,  
			c.razon_social,
			c.calle,
			c.no_exterior,  
			c.no_interior,
			c.colonia,
			c.delegacion_municipio,  
			(SELECT e.nombre FROM sys_estados e JOIN sys_ciudades cd ON e.id_estado = cd.id_estado WHERE cd.id_ciudad=c.id_ciudad) as estado,
			c.cp,
			cl.rfc as rfc_r,  
			cl.nombre_razon_social as razon_social_r,
			CONCAT(cl.calle,', ',cl.numero_exterior,IF(cl.numero_interior IS NOT NULL AND cl.numero_interior <> '',CONCAT(', ',cl.numero_interior),'')) AS direccion_cliente,
			cl.calle as calle_r,  
			cl.numero_interior as no_interior_r,
			cl.numero_exterior as no_exterior_r,
			cl.colonia as colonia_r,
			cl.delegacion_municipio as delegacion_municipio_r,
			CONCAT((SELECT c.nombre FROM sys_ciudades c WHERE c.id_ciudad = cl.id_ciudad),', ',(SELECT e.nombre FROM sys_estados e WHERE e.id_estado = cl.id_estado)
			) as estado_r,
			cl.cp as cp_r ,
			f.folio_cfd,
			f.timbrado,
			id_factura as folio,
			f.fecha_y_hora as fecha_emision,
			f.cadena_original,
			f.observaciones,
			FORMAT(f.iva,2) as iva1,
			f.id_compania as compania,
			total as tot,
			fp.clave as metodo_pago,
                        fp.descripcion as descripcion_forma_pago,
			f.cuenta as  cuenta,
			c.regimen as regimen,
			c.lugar_expedicion,
			f.id_moneda as moneda,
			f.id_sucursal as id_sucursal,
			f.prefijo as serie_fiscal,
			f.consecutivo AS folio_electronico,
			SC.nombre as nombre_sucursal,
			SC.calle as calle_sucursal,
			SC.numero_exterior as no_ext_sucursal,
			SC.numero_interior as no_int_sucursal,
			SC.colonia  as colonia_sucursal,
			SC.delegacion as  delegacion_sucursal,
			SC.telefono_1 as  telefono_1,
			SC.telefono_2 as  telefono_2,
                        mp.nombre as nombre_metodo_pago,
                        mp.clave as clave_metodo_pago,
			(SELECT e.nombre FROM sys_estados e JOIN sys_ciudades cd ON e.id_estado = cd.id_estado WHERE cd.id_ciudad=SC.id_ciudad) as estado_sucursal,
			SC.codigo_postal as  cp_sucursal,
			f.retencion_iva as retencion,
			ftcf.nombre_corto tipo_cfd
			FROM ad_facturas_audicel f
			LEFT JOIN sys_companias c ON f.id_compania = c.id_compania
			LEFT JOIN ad_clientes_datos_fiscales cl ON f.id_fiscales_cliente = cl.id_cliente_dato_fiscal
			LEFT JOIN ad_clientes ON f.id_cliente = ad_clientes.id_cliente
			join scfdi_formas_de_pago_sat  as fp ON fp.id_forma_pago_sat=f.id_forma_pago_sat
			left join  ad_sucursales SC on  SC.id_sucursal=f.id_sucursal
			LEFT JOIN scfdi_tipo_comprobante_fiscal ftcf ON ftcf.id_tipo_comprobante_fiscal = f.id_tipo_comprobante_fiscal
                        LEFT JOIN sat_metodos_pago mp on f.id_metodo_pago = mp.id_metodo_pago
			WHERE id_control_factura = ".$factura;
	
	$ConsultaFactura = new consultarTabla($sql);
	$datosFactura = $ConsultaFactura->obtenerLineaRegistro();
	
	$clave_cliente = $datosFactura['clave_cliente']; 
	$foliocfdi = $datosFactura['folio_cfd'];
	$certificado = $datosFactura['no_certificado'];
	$lugar_fecha_certificacion = $datosFactura['lugar_expedicion'].', '.$datosFactura['fecha'];
	$cliente = $datosFactura['razon_social_r'];
	$direccion_1 = $datosFactura['direccion_cliente'];//$datosFactura['calle_r'].', '.$datosFactura['no_exterior_r'].', '.$datosFactura['no_interior_r'];
	$direccion_2 = $datosFactura['colonia_r'];
	$direccion_3 = $datosFactura['estado_r'].', C.P.'.$datosFactura['cp_r'];
	$rfc_r = $datosFactura['rfc_r'];
	$serie = $datosFactura['serie_fiscal'];
	$folio_fiscal = $datosFactura['folio_electronico'];
	$regimen = $datosFactura['regimen'];
	$compania = $datosFactura['razon_social'].' RFC: '.$datosFactura['rfc'];
	$telefonos = $datosFactura['telefono_1'].', '.$datosFactura['telefono_2'];
	$metodo_pago = $datosFactura['metodo_pago'];
        $descripcion_forma_pago = $datosFactura['descripcion_forma_pago'];
	$cuenta = $datosFactura['cuenta'];
	$ax = explode('selloSAT="',$datosFactura['timbrado']);	
	$ax = explode('"', $ax[1]);
	$observaciones = $datosFactura['observaciones']; 
	
	$direccionCompania = $datosFactura['calle'].' '.$datosFactura['no_interior'].', Col. '.$datosFactura['colonia'];
	$direccionCompania1 = 'C.P. '.$datosFactura['cp'].', Del. '.$datosFactura['delegacion_municipio'];
	$direccionCompania2 = $datosFactura['estado'].', Del. '.$datosFactura['delegacion_municipio'];
	
	$sucursal = $datosFactura['nombre_sucursal'];
	$direccion_sucursal = $datosFactura['calle_sucursal'].' '.$datosFactura['no_int_sucursal'].', Col. '.$datosFactura['colonia_sucursal'];
	$direccion_sucursal1 = 'C.P. '.$datosFactura['cp_sucursal'].', '.$datosFactura['delegacion_sucursal'];
	$direccion_sucursal2 = $datosFactura['estado_sucursal'];
	
	$certificado_digital = $ax[0];
	$sello = $datosFactura['sello'];
	/*$aux = "||";
	$ax = explode('version="', $datosFactura['timbrado']);	
	$ax = explode('"', $ax[1]);
	$aux .= $ax[0]."|";
	$ax = explode('UUID="', $datosFactura['timbrado']);	
	$ax = explode('"', $ax[1]);
	$uuid = $ax[0];
	$aux .= $uuid."|";
	$ax = explode('FechaTimbrado="', $datosFactura['timbrado']);	
	$ax = explode('"', $ax[1]);
	$aux .= $ax[0]."|";
	$ax = explode('selloCFD="', $datosFactura['timbrado']);	
	$ax = explode('"', $ax[1]);
	$aux .= $ax[0]."||";
	$cadena_original = $aux;*/
        
        
        $timbreExplotado = explode('Version="', $datosFactura['timbrado']);
	$ax = explode('"', $timbreExplotado[1]);
        
//        print_r($ax);

        $version = $ax[0];
        $uuid = $ax[2];
        $fechaTimbrado = $ax[4];
        $RFCPAC = $ax[6];
        $selloSAT = $ax[8];
        $selloCFD = $ax[10];
        $NoCertificadoSAT = $ax[12];

        $doblePipe = '||';
        $pipe = '|';
        
        $cadena_original = $doblePipe . $version . $pipe . $uuid . $pipe . $fechaTimbrado . $pipe . $RFCPAC . $pipe . $selloCFD . $pipe . $NoCertificadoSAT . $doblePipe;
        
        
	$subtotal = $datosFactura['subtotal'];
	$total = $datosFactura['total'];
	$iva = $datosFactura['iva1'];
	$moneda = $datosFactura['moneda'];
        
        $nombreMetodoPago = $datosFactura['nombre_metodo_pago'];
        $claveMetodoPago = $datosFactura['clave_metodo_pago'];
        
        $ochoDigitos = substr($sello, -8);

	$qr = new qrcode();
        $liga = "https://verificacfdi.facturaelectronica.sat.gob.mx/default.aspx";
	$qr -> text($liga . "?re=".$datosFactura['rfc'] . "&rr=" . $rfc_r . "&tt=" . $total . "&id=" . $foliocfdi . "&fe=" . $ochoDigitos);
	$ar = fopen("FAC_".$folio_fiscal.".png", "wb");
	fwrite($ar, $qr->get_image());
	fclose($ar);
	$img_qr = "FAC_".$folio_fiscal.".png";
	$accion='FACTURA';
	$sqlDetalle = "
			SELECT count(ids_detalle_control_contrato) AS totalIDS
			FROM ad_facturas_audicel_detalle
			WHERE ids_detalle_control_contrato IS NOT NULL 
			AND ids_detalle_control_contrato <> 0 
			AND ids_detalle_control_contrato <> '' 
			AND id_control_factura = ".$factura;
	$result = mysql_query($sqlDetalle);
	$arrayIDS = mysql_fetch_array($result);
	
	$sqlDetallePen = "
			SELECT count(id_detalle_control_penalizaciones) AS totalIDS
			FROM ad_facturas_audicel_detalle
			WHERE id_detalle_control_penalizaciones IS NOT NULL 
			AND id_detalle_control_penalizaciones <> 0 
			AND id_detalle_control_penalizaciones <> '' 
			AND id_control_factura = ".$factura;
	$resultPen = mysql_query($sqlDetallePen);
	$arrayIDSPen = mysql_fetch_array($resultPen);
	if($arrayIDS[0] > 0){
		$sqlDetalleGroup = "
			SELECT id_producto,valor_unitario,GROUP_CONCAT(ids_detalle_control_contrato) as ids_detalle_control_contrato
			FROM ad_facturas_audicel_detalle
			WHERE id_control_factura = ".$factura." GROUP BY id_producto,valor_unitario";
		$ConsultaProductos = new consultarTabla($sqlDetalleGroup);
		$datosProducto = $ConsultaProductos -> obtenerRegistros();
		$pdf = new PDF('P', 'mm', 'Letter');
		$pdf -> AliasNbPages();
		$pdf -> AddPage();
		$pdf -> datosProductos($datosProducto,$factura);
		$pdf -> footerGeneral();
		if(!$caso)
			$pdf -> Output();
		else
			$pdf->Output($rutaPDF);
	}elseif($arrayIDSPen[0] > 0){
		$sqlDetalleGroup = "
			SELECT id_producto,valor_unitario,GROUP_CONCAT(id_detalle_control_penalizaciones) as id_detalle_control_penalizaciones
			FROM ad_facturas_audicel_detalle
			WHERE id_control_factura = ".$factura." GROUP BY id_producto,valor_unitario";
		$ConsultaProductos = new consultarTabla($sqlDetalleGroup);
		$datosProducto = $ConsultaProductos -> obtenerRegistros();
		$pdf = new PDF('P', 'mm', 'Letter');
		$pdf -> AliasNbPages();
		$pdf -> AddPage();
		$pdf -> datosProductos($datosProducto,$factura);
		$pdf -> footerGeneral();
		if(!$caso)
			$pdf->Output();
		else
			$pdf->Output($rutaPDF);
	}else{
		$sqlDetalle = "
			SELECT
			SUM(fd.cantidad) AS cantidad,
			un.nombre AS unidad,
			CONCAT(p.nombre,IF(fd.descripcion IS NOT NULL AND fd.descripcion <> '',CONCAT(' / ',fd.descripcion),'')) AS nombre,
			fd.descripcion,
			FORMAT(fd.valor_unitario,2) AS valor_unitario,
			FORMAT(fd.valor_unitario*SUM(fd.cantidad),2) as importe,
			fd.iva_monto,
			1,
			sat_productos_unidades_sat.clave as clave_unidad_sat,
			sat_productos_claves_sat.clave as clave_producto_sat
			FROM ad_facturas_audicel_detalle fd
			LEFT JOIN cl_productos_servicios p ON fd.id_producto = p.id_producto_servicio
			LEFT JOIN cl_clasificacion_productos cp ON cp.id_clasificacion_producto = p.id_producto_servicio
			LEFT JOIN scfdi_unidades un ON un.id_unidad = cp.id_unidad
			LEFT JOIN sat_productos_unidades_sat on id_unidad_sat =id_unidad_producto_sat
			LEFT JOIN sat_productos_claves_sat  on id_producto_sat =id_clave_producto_sat
			WHERE fd.id_control_factura =".$factura." GROUP BY id_producto,fd.valor_unitario";
		$ConsultaProductos = new consultarTabla($sqlDetalle);
		$datosProducto = $ConsultaProductos->obtenerRegistros();
		$pdf = new PDFX('P', 'mm', 'Letter');
		$pdf -> AliasNbPages();
		$pdf -> AddPage();
		$pdf -> datosProductos($datosProducto);
		$pdf -> footerGeneral();
		if(!$caso)
			$pdf->Output();
		else
			$pdf->Output($rutaPDF);	
	}
}
?>