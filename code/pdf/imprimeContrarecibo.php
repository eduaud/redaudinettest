<?php
	
	include("../../include/fpdf153/fpdf.php");	
	//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../conect.php");
	include("../general/funciones.php");
	include("../../consultaBase.php");

	$orden = $_GET['orden'];
	
	Global $logo;
	Global $folio;
	
	$sql = "SELECT 
	ad_clientes.id_tipo_cliente_proveedor,
	id_contrarecibo,
	DATE_FORMAT(cl_contrarecibos.fecha_hora,'%d/%m/%Y') as fecha_hora,
	ad_clientes.id_cliente AS id_distribuidor,
	CONCAT_WS(' ',ad_clientes.nombre,ad_clientes.apellido_paterno,ad_clientes.apellido_materno) AS distribuidor,
	CONCAT('Calle ',ad_sucursales.calle, ', No. Ext. ', ad_sucursales.numero_exterior, ', Col. ',ad_sucursales.colonia,',	Del. o Mun. ', ad_sucursales.delegacion, ', ',sys_estados.nombre) AS dir_sucursal,
	ad_sucursales.nombre as nombre_sucursal,
	telefono_1,
	telefono_2,
	ad_clientes.clave,
	ad_clientes.di,
	CONCAT('Calle ',ad_clientes.calle_fiscal, ', No. Ext. ', ad_clientes.numero_exterior_fiscal, ', Col. ',ad_clientes.colonia_fiscal, ', Del. o Mun. ', ad_clientes.delegacion_municipio_fiscal, ', ',sys_estados.nombre) AS dir_distribuidor,
	nombre_entrego
	FROM cl_contrarecibos
	LEFT JOIN ad_clientes 
	ON ad_clientes.id_cliente = cl_contrarecibos.id_cliente
	LEFT JOIN cl_tipos_cliente_proveedor ON cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = ad_clientes.id_tipo_cliente_proveedor
	LEFT JOIN ad_sucursales
	ON ad_sucursales.id_sucursal = cl_contrarecibos.id_sucursal
	LEFT JOIN sys_estados 
	ON sys_estados.id_estado = ad_clientes.id_estado_fiscal
	WHERE cl_contrarecibos.id_cliente <> 0 AND cl_contrarecibos.id_cliente IS NOT NULL AND cl_contrarecibos.id_cliente <> '' AND id_contrarecibo = ".$contrarecibo."
	UNION ALL

	SELECT
	cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor,
	id_contrarecibo,
	DATE_FORMAT(cl_contrarecibos.fecha_hora,'%d/%m/%Y') as fecha_hora,
	ad_entidades_financieras.id_entidad_financiera AS id_distribuidor,
	CONCAT_WS(' ',ad_entidades_financieras.nombre,ad_entidades_financieras.apellido_paterno,ad_entidades_financieras.apellido_materno) AS distribuidor,
	CONCAT('Calle ',ad_sucursales.calle, ', No. Ext. ', ad_sucursales.numero_exterior, ', Col. ',ad_sucursales.colonia,',	Del. o Mun. ', ad_sucursales.delegacion, ', ',sys_estados.nombre) AS dir_sucursal,
	ad_sucursales.nombre as nombre_sucursal,
	ad_sucursales.telefono_1,
	ad_sucursales.telefono_2,
	ad_entidades_financieras.clave,
	ad_sucursales.di,
	CONCAT('Calle ',ad_entidades_financieras.calle, ', No. Ext. ', ad_entidades_financieras.numero_exterior, ', Col. ',ad_entidades_financieras.colonia, ', Del. o Mun. ', ad_entidades_financieras.delegacion_municipio, ', ',sys_estados.nombre) AS dir_distribuidor,
	nombre_entrego
	FROM cl_contrarecibos
	LEFT JOIN ad_entidades_financieras 
	ON ad_entidades_financieras.id_entidad_financiera = cl_contrarecibos.id_entidad_financiera
	LEFT JOIN ad_tipos_entidades_financieras 
	ON ad_tipos_entidades_financieras.id_tipo_entidad_financiera = ad_entidades_financieras.id_tipo_entidad_financiera	
	LEFT JOIN cl_tipos_cliente_proveedor ON cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = ad_tipos_entidades_financieras.id_tipo_cliente_proveedor
	LEFT JOIN ad_sucursales
	ON ad_sucursales.id_sucursal = cl_contrarecibos.id_sucursal
	LEFT JOIN sys_estados 
	ON sys_estados.id_estado = ad_entidades_financieras.id_estado
	WHERE cl_contrarecibos.id_entidad_financiera <> 0 AND cl_contrarecibos.id_entidad_financiera IS NOT NULL AND cl_contrarecibos.id_entidad_financiera <> '' AND id_contrarecibo = ".$contrarecibo;
	$consulta = new consultarTabla($sql);
	$contrarecibos = $consulta -> obtenerRegistros();
	
	
	
	$folio = $contrarecibos[0]->id_contrarecibo;
	
	$sqlDetalle = "SELECT contrato,DATE_FORMAT(fecha_activacion,'%d/%m/%Y') AS fecha_activacion,cuenta
					FROM cl_contrarecibos_detalle
					LEFT JOIN cl_control_contratos 
					ON 
					cl_contrarecibos_detalle.id_control_contrato = cl_control_contratos.id_control_contrato
					WHERE cl_contrarecibos_detalle.id_contrarecibo = ".$contrarecibo;
	$consultaDet = new consultarTabla($sqlDetalle);
	$contrareciboDet = $consultaDet -> obtenerRegistros();
	
	//print_r($contrareciboDet);
	$logo = "../../imagenes/audinet.png";		
	include("contrarecibo_pdf.php");
?>