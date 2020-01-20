<?php
	
	include("../../include/fpdf153/fpdf.php");	
	if(!isset($caso)){
		//CONECCION Y PERMISOS A LA BASE DE DATOS
		include("../../conect.php");
		include("../general/funciones.php");
		include("../../consultaBase.php");
		$factura = $_GET['doc'];
	}
//	include_once("qrcode.php");
        
        include_once("phpqrcode.php");
	

	//Variables globales para el encabezado del pdf_add_annotation
	Global $accion;
	Global $foliocfdi;
	Global $certificado;
	Global $lugar_fecha_certificacion;
	Global $cliente;
	Global $direccion_1;
	Global $direccion_2;
	Global $direccion_3;
	
	Global $direccion_1_c;
	Global $direccion_2_c;
	Global $direccion_3_c;
	Global $rfc_r;
	Global $serie;
	Global $folio_fiscal;
	Global $regimen;
	Global $compania;
	Global $certificado_digital;
	Global $sello;
	Global $cadena_original;
	Global $total;
	Global $subtotal;
	Global $iva;
	Global $img_qr;
	Global $moneda;
	Global $rfc_compania;
	
	Global $sucursal;
	Global $folio;
	Global $fecha_pedido;
	Global $metodo_pago;
	Global $cuenta;
	Global $logo;
	
	mysql_query("SET SESSION group_concat_max_len = 1000000;");
	$sql="
		SELECT
			certificado,
			sello,
			CONCAT(DATE_FORMAT(fecha_aplicacion_sello, '%Y-%m-%d'), 'T', TIME_FORMAT(fecha_aplicacion_sello, '%H:%i:%s')) as fecha,
			FORMAT(total, 2) AS total,
			FORMAT(subtotal, 2) AS subtotal,
			FORMAT(descuento, 2) AS descuento,  
			FORMAT(retencion_iva, 2) AS retencion_iva,  
			FORMAT(retencion_isr, 2) AS retencion_isr,  
			FORMAT(ieps, 2) AS ieps,
			c.no_certificado,
			cf.rfc,  
			c.razon_social as razon_social,
			c.calle_fiscal as calle,
			c.numero_exterior_fiscal as numero_exterior,  
			c.numero_interior_fiscal as numero_interior,
			c.colonia_fiscal as colonia,
			c.delegacion_municipio_fiscal as delegacion_municipio,  
			(SELECT e.nombre FROM sys_estados e JOIN sys_ciudades cd ON e.id_estado = cd.id_estado WHERE cd.id_ciudad=c.id_ciudad_fiscal) as estado,
			c.codigo_postal_fiscal as cp,
			cl.rfc as rfc_r,  
			cl.razon_social as razon_social_r,
			cl.calle as calle_r,  
			cl.no_interior as no_interior_r,
			cl.no_exterior as no_exterior_r,
			cl.colonia as colonia_r,
			cl.delegacion_municipio as delegacion_municipio_r,
			(SELECT CONCAT(cd.nombre ,' ,',e.nombre )FROM sys_estados e JOIN sys_ciudades cd ON e.id_estado = cd.id_estado WHERE cd.id_ciudad=cl.id_ciudad) as estado_r,
			cl.cp as cp_r ,
			f.prefijo as serie_fiscal,
			f.consecutivo as folio_electronico,
			f.folio_cfd,
			f.timbrado,
			id_factura as folio,
			CONCAT(DATE_FORMAT(f.fecha_y_hora, '%Y-%m-%d'), 'T', TIME_FORMAT(f.fecha_y_hora, '%H:%i:%s')) as fecha_emision,
			f.cadena_original,
			f.observaciones,
			FORMAT(f.iva,2) as iva1,
			f.id_compania as compania,
			total as tot,
			 fp.descripcion as metodo_pago,
			f.cuenta as  cuenta,
			c.regimen as regimen,
			c.lugar_expedicion,
			f.id_moneda as moneda,
			f.id_sucursal as id_sucursal,
			SC.nombre as nombre_sucursal,
			SC.calle as calle_sucursal,
			SC.numero_exterior as no_ext_sucursal,
			SC.numero_interior as no_int_sucursal,
			SC.colonia  as colonia_sucursal,
			SC.delegacion as  delegacion_sucursal,
			(SELECT e.nombre FROM sys_estados e JOIN sys_ciudades cd ON e.id_estado = cd.id_estado WHERE cd.id_ciudad=SC.id_ciudad) as estado_sucursal,
			SC.codigo_postal as  cp_sucursal,
			f.retencion_iva as retencion,
			ftcf.nombre_corto tipo_cfd,id_tipo_factura
		FROM ad_facturas f
		LEFT JOIN ad_clientes c ON f.id_compania = c.id_cliente
		LEFT JOIN ad_clientes_datos_fiscales cf ON f.id_compania_fiscal = cf.id_cliente_dato_fiscal
		LEFT JOIN sys_companias cl ON f.id_fiscales_cliente= cl.id_compania
		join scfdi_formas_de_pago_sat  as fp ON fp.id_forma_pago_sat=f.id_forma_pago_sat
		left join  ad_sucursales SC on  SC.id_sucursal=f.id_sucursal
		LEFT JOIN scfdi_tipo_comprobante_fiscal ftcf ON ftcf.id_tipo_comprobante_fiscal = f.id_tipo_comprobante_fiscal
		WHERE id_control_factura = ".$factura;
	
	$ConsultaFactura = new consultarTabla($sql);
	$datosFactura = $ConsultaFactura->obtenerLineaRegistro();
	
	$cuenta = $datosFactura['cuenta'];
	$metodo_pago = $datosFactura['metodo_pago'];
	$foliocfdi = $datosFactura['folio_cfd'];
	$certificado = $datosFactura['no_certificado'];
	$lugar_fecha_certificacion = $datosFactura['lugar_expedicion'].', '.$datosFactura['fecha'];
	$cliente = $datosFactura['razon_social_r'];
	$direccion_1_c = $datosFactura['calle'].', '.$datosFactura['numero_exterior'].', '.$datosFactura['numero_interior'];
	$direccion_2_c = $datosFactura['colonia'].', '.$datosFactura['delegacion_municipio'];
	$direccion_3_c = $datosFactura['estado'].', C.P.'.$datosFactura['cp'];
	
	
	$direccion_1 = $datosFactura['calle_r'].', '.$datosFactura['no_exterior_r'].', '.$datosFactura['no_interior_r'];
	$direccion_2 = $datosFactura['colonia_r'].', '.$datosFactura['delegacion_municipio_r'];
	$direccion_3 = $datosFactura['estado_r'].', C.P.'.$datosFactura['cp_r'];
	$rfc_r = $datosFactura['rfc_r'];
	$serie = $datosFactura['serie_fiscal'];
	$folio_fiscal = $datosFactura['folio_electronico'];
	$regimen = $datosFactura['regimen'];
	$compania = $datosFactura['razon_social'];
	$rfc_compania = 'RFC: '.$datosFactura['rfc'];
	
	$ax = explode('selloSAT="',$datosFactura['timbrado']);	
	$ax = explode('"', $ax[1]);
	$certificado_digital = $ax[0];
	$sello = $datosFactura['sello'];
	$aux = "||";
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
	$cadena_original = $aux;
	
	$subtotal = $datosFactura['subtotal'];
	$total = $datosFactura['total'];
	$iva = $datosFactura['iva1'];
	$moneda = $datosFactura['moneda'];
        
        $ruta = "FAC_".$folio_fiscal.".png";
	$url_text = "?re=".$datosFactura['rfc']."&rr=".$rfc_r."&tt=".$total."&id=".$foliocfdi."";
	QRcode::png($url_text, $ruta, 'H', 2, 2);
	
	//$qr = new qrcode();
	//$qr -> text("?re=".$datosFactura['rfc']."&rr=".$rfc_r."&tt=".$total."&id=".$foliocfdi."");
	//$ar = fopen($rutaImagen . "FAC_".$folio_fiscal.".png", "wb");
	//fwrite($ar, $qr->get_image());
	//fclose($ar);
	$img_qr = "FAC_".$folio_fiscal.".png";
	$accion='FACTURA';
	$logo ='../../imagenes/audicel.png';
	
		$sqlDetalle = "
				SELECT count(ids_detalle_control_contrato) AS totalIDS
				FROM ad_facturas_detalle
				WHERE ids_detalle_control_contrato IS NOT NULL 
				AND ids_detalle_control_contrato <> 0 
				AND ids_detalle_control_contrato <> '' 
				AND id_control_factura = ".$factura;
		$result = mysql_query($sqlDetalle);
		$arrayIDS = mysql_fetch_array($result);
		if($arrayIDS[0] > 0){
			$sqlDetalleGroup = "
				SELECT id_producto,valor_unitario,GROUP_CONCAT(ids_detalle_control_contrato) as ids_detalle_control_contrato
				FROM ad_facturas_detalle
				WHERE id_control_factura = ".$factura." GROUP BY id_producto,valor_unitario";
			$ConsultaProductos = new consultarTabla($sqlDetalleGroup);
			$datosProducto = $ConsultaProductos -> obtenerRegistros();
			if($foliocfdi == ''){
				include("prefactura_pdf.php");
			}else{
				include("facturaAudicelBonos_pdf.php");
			}
		}else{
			$sqlDetalle = "
				SELECT
				fd.cantidad,
				un.nombre AS unidad,
				p.nombre,
				fd.descripcion,
				FORMAT(fd.valor_unitario,4) AS valor_unitario,
				FORMAT(fd.valor_unitario*fd.cantidad,4) as importe
				,1
				FROM ad_facturas_detalle fd
				LEFT JOIN cl_productos_servicios p ON fd.id_producto = p.id_producto_servicio
				LEFT JOIN cl_clasificacion_productos cp ON cp.id_clasificacion_producto = p.id_producto_servicio
				LEFT JOIN scfdi_unidades un ON un.id_unidad = cp.id_unidad
				WHERE fd.id_control_factura =".$factura;
			$ConsultaProductos = new consultarTabla($sqlDetalle);
			$datosProducto = $ConsultaProductos->obtenerRegistros();
			if($foliocfdi == ''){
				include("prefactura_pdf.php");
			}else{
				include("facturaAudicel_pdf.php");
			}			
		}
	
?>