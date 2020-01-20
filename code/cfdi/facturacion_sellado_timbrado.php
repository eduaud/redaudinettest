<?php
//para las facturas es esta funcion para las notas se credito crearemos otra funcion 
           
function cfdiSellaTimbraFactura($tipo_documento,$id_documento,$id_compania,$tabla)
{
	//para sellar la operadora
	//$id_compania=$id_compania;
	
	//para sellar la franquicia
	//$id_compania=100; 
	
	
	//crea el sello
	//($tipo_documento,$tabla, $llave, $id_compania)
	$respuesta=doSello($tipo_documento, $id_documento, $id_compania,$tabla);
	if($respuesta=="exito") 
	{
		$cancelacion='NO';
		$respuesta=timbra($tipo_documento,$id_documento,$cancelacion,$tabla);
	}
	
	if($respuesta=="exito" || $respuesta==" exito" || $respuesta=="  exito")
		return 0;
	else{	
		if(is_array($respuesta)){
			$arr_errores = array(
				0	=> "codError='303'",
				1 	=> "codError='302'", 
				2 	=> "codError='518'",
				3 	=> "codError='301'",
				4 	=> "codError='901'"		
			);
			for($i = 0;$i < count($arr_errores); $i++){
				$pos = strpos($respuesta['error'], $arr_errores[$i]);
				if ($pos !== false && $arr_errores[$i] == "codError='303'") {
					return "Sello no corresponde a emisor";
				} else if ($pos !== false && $arr_errores[$i] == "codError='302'") {
					return "Sello mal formado o invalido";
				} else if ($pos !== false && $arr_errores[$i] == "codError='518'") {
					return "El certificado incluido en el xml es invalido";
				} else if ($pos !== false && $arr_errores[$i] == "codError='301'") {
					return mb_convert_encoding("Verifique los datos de Facturación","ISO-8859-1","UTF-8");
				} else if ($pos !== false && $arr_errores[$i] == "codError='901'") {
					return mb_convert_encoding("NO SE HA PODIDO AUTENTICAR LA TRANSACCIÓN","ISO-8859-1","UTF-8");
				}
			}
		}else{
			return $respuesta;
		}
	}
}

function timbra($tipo_documento,$id_documento,$cancelacion,$tabla){
	if($tipo_documento=="FAC")
		$sqlXml="SELECT str_xml FROM ".$tabla." WHERE id_control_factura=".$id_documento;
	elseif($tipo_documento=="NC")
		$sqlXml="SELECT str_xml FROM ".$tabla." WHERE id_control_nota_credito=".$id_documento;
	
	$res=mysql_query($sqlXml) or die("Error en:\n$sqlXml\n\nDescripcion\n".mysql_error());
	$row=mysql_fetch_assoc($res);
	extract($row);
	include("../cfdi/servicioTimbrador.php");
	if($respuesta != 'fallo' && $respuesta != 'error'){
		$xml = $resultado;
		$posicion = strpos($xml,"TimbreFiscalDigital");
		if($posicion !== false){
			$uuidPos = strpos($xml,'UUID="');
			$versionPos = strpos($xml,'" version="1.0"');
			$uuid = substr($xml,($uuidPos+6),(($versionPos)-($uuidPos+6)));
			$FechaPos = strpos($xml,'FechaTimbrado="');
			$fecha = substr($xml,($FechaPos+15),(($uuidPos-2)-($FechaPos+15)));
			$fechaR = str_replace('T',' ',$fecha);
			$tfdPos = strpos($xml,'<tfd');
			$cfdiComprobantePos = strpos($xml,'</cfdi:Complemento>');
			$tfd = substr($xml,$tfdPos,($cfdiComprobantePos-$tfdPos));
			if($tipo_documento=="FAC"){
				$sql = "UPDATE ".$tabla." SET folio_cfd='".$uuid."', timbrado='".$tfd."' , fecha_aplicacion_sello='".$fechaR."' 
				WHERE id_control_factura=".$id_documento;
				$res = mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion\n".mysql_error());
			}
			elseif($tipo_documento=="NC"){
				$sql = "UPDATE ".$tabla." SET folio_cfd='".$uuid."', timbrado='".$tfd."' , fecha_aplicacion_sello='".$fechaR."' 
				WHERE id_control_nota_credito=".$id_documento;
				$res = mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion\n".mysql_error());
			}
		return "exito";
		}
		else{
			$arrErrores=array();
			$arrErrores['error'] = $resultado;
			return $arrErrores;
		}		
	}
	if($respuesta=='fallo')
		return $respuesta;
	else{
		$arrErrores['error'] = $resultado;
		return $arrErrores;
	}
}
function doSello($tipo_documento, $llave, $id_compania,$tabla)
{
		if($tipo_documento == "FAC")
			$tipoDoc=1;
		if($tipo_documento == "NC")
			$tipoDoc=2;	
		
		//para las facturas
		if($tipoDoc == 1)
			$sql="SELECT 1 FROM ".$tabla." WHERE id_control_factura=".$llave." AND folio_cfd IS NOT NULL";
		else
			$sql="SELECT 1 FROM ".$tabla." WHERE id_control_nota_credito=".$llave." AND folio_cfd IS NOT NULL";
		//aqui van las otras excepciones para los otros docuementos notas de credito
		
		$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:\n".mysql_error());	
		if(mysql_num_rows($res) > 0)
			die("DocumentoTimbrado");	
		
		if($tabla=='ad_facturas_audicel'||$tabla=='ad_notas_credito_audicel')
			$sql="SELECT
				  c.no_certificado,
				  c.file_cer as ruta_certificado,
				  c.file_key as ruta_llave_privada_key,
				  c.password as password,
				  c.id_certificado as id_certificado
				  FROM scfdi_certificados c
				  WHERE c.activo=1 ";	
		elseif($tabla=='ad_facturas'||$tabla=='ad_notas_credito'){
			//$id_compania="2";//para Prueba
		
			$sql="SELECT 
				no_certificado,
				ruta_cer AS ruta_certificado,
				ruta_key AS ruta_llave_privada_key,
				contrasenia AS password
				FROM ad_clientes 
				WHERE id_cliente=".$id_compania." AND activo=1";
		}
		$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion\n".mysql_error());	  
		
		if(mysql_num_rows($res) <= 0)
		{
			return "No se encontro configuracion para este proceso.";
		}	
		$row=mysql_fetch_assoc($res);
		extract($row);
//print_r($row);
		//validamos los archivos de conf
		if(!file_exists($ruta_certificado))
		{
			return "No se encontro el certificado.";
		}
		if(!file_exists($ruta_llave_privada_key))
		{
			return "No se encontro la llave privada.";
		}
		
		//Obtenemos la fecha del documento
		$sq="SELECT ADDTIME(NOW(), '- 00:35:00')";
		$re=mysql_query($sq);
		$ro=mysql_fetch_row($re);		
		$fechaDoc=$ro[0];
		//------------
		
		//funcion exclusiva para nasser
		//------------------------------------------------------------------------------------------------------
		//obtenemos tambien si en el pedido consulto si era pendo
		$sql = "SELECT if(id_fiscales_cliente=0,0,1) as requiereFactura
							FROM ".$tabla." WHERE id_control_factura= " . $llave;
							
		//echo  $sql ;	
		$result = new consultarTabla($sql);		
		$registro = $result -> obtenerLineaRegistro();
		
		$requiereFactura=$registro['requiereFactura'];
		//-----------------------------------------------------------------------------------------------------
				
		
		$arrayDatos=array();		
		$compania=getArrayCompania($id_compania,$tabla);
		//print_r($compania);
		$sucursal=getArraySucursal($id_compania,$tabla);
		
		
		//excepcione specila para nasser de clientes que no requieren factura y toman el valor generico getArrayCliente($tipoDoc, $llave)
		/*if($requiereFactura==1)*/
			$cliente=getArrayCliente($tipoDoc, $llave,$tabla);
		/*else
			$cliente=getArrayClienteNoRequierenFactura($tipoDoc, $llave);
			*/
		
		
		
		$tablaFacNcO=getArraytablaFacNcO($tipoDoc, $llave,$tabla);		
		$productos=getArrayProductos($tipoDoc, $llave,$tabla);
		$impuestos=getArrayImpuestos($tipoDoc, $llave,$tabla);
		$impuestosRetenidos=getArrayImpuestosRetenidos($tipoDoc, $llave,$tabla);
		//en este orden se deben agregar
		array_push($arrayDatos,$compania);		
		array_push($arrayDatos,$sucursal);
		array_push($arrayDatos,$cliente);		
		array_push($arrayDatos,$tablaFacNcO);
		
		array_push($arrayDatos,$productos);		
		array_push($arrayDatos,$impuestos);
		array_push($arrayDatos,$impuestosRetenidos);
		
		//$cadenaOriginal=cadena_Original($datos, $productos, $impuestos, $fechaDoc,$regimen_lugar,$pagoCliente,$impuestosRetenidos);	
		/*echo '<pre>';print_r($arrayDatos);echo '<pre>';
		die();*/
		if($tipoDoc == 1 )
			$tipoComprobante= "ingreso";
		if($tipo == 2)
			array_push($datos, "egreso");	
		
		$cadenaOriginal= cadena_Original($arrayDatos,$tipoComprobante,$fechaDoc);
		//echo $cadenaOriginal;
		
		//$sello=sello($cadenaOriginal, $ruta_llave_privada_key, $password, "/var/www/vhosts/sysandweb.com/httpdocs/facelec/cache/");
		
		//para produccion
		//$sello=sello($cadenaOriginal, $ruta_llave_privada_key, $password, "/var/www/vhosts/sysandweb.com/facelec/cache/");
		
		
		//para Produccion
		//B:\xampp\htdocs\systemthebook\operadora\cache
		// "/var/www/vhosts/sysandweb.com/facelec/cache/"
		///var/www/vhosts/systhebooks.net/httpdocs/systemthebookTEST/operadora/
		$sello = sello($cadenaOriginal, $ruta_llave_privada_key, $password, '/var/www/html/redaudinetdistribuidor/cache/');	
		//echo $sello;		
		$cert=certificado($ruta_certificado);
		$nombreFile="";		
		
		//actualizamos el campo del xml generado	
		//print_r($arrayDatos);

		$xml = xmlFE($arrayDatos,$tipoComprobante,$fechaDoc,$sello,$cert,$no_certificado);
		
/*echo '<pre>';
		print_r($arrayDatos);
		echo '<pre>';	*/	
		//Actualizamos las tablas de GEG de factura
		if($tipoDoc == 1)
		{
			$sql="UPDATE ".$tabla."
			      SET
				  con_sello=1,				  
				  fecha_aplicacion_sello='$fechaDoc',				  
				  sello='$sello',
				  cadena_original='$cadenaOriginal',
				  certificado='$cert',
				  no_certificado='$no_certificado'
				  WHERE id_control_factura='$llave'";
			$res=mysql_query($sql);
			if(!$res)
				return "Error en:\n\n$sql\n\nDescripcion:\n".mysql_error();
		}
		elseif($tipoDoc == 2)
		{
			$sql="UPDATE ".$tabla."
			      SET
				  con_sello=1,				  
				  fecha_aplicacion_sello='$fechaDoc',				  
				  sello='$sello',
				  cadena_original='$cadenaOriginal',
				  certificado='$cert',
				  no_certificado='$no_certificado'
				  WHERE id_control_nota_credito='$llave'";
			$res=mysql_query($sql);
			if(!$res)
				return "Error en:\n\n$sql\n\nDescripcion:\n".mysql_error();
		}
		
	    ///generamos el XML

		
		if($tipoDoc == 1)
		{
			$sql="UPDATE ".$tabla."
			      SET
				  str_xml='".$xml."'
				  WHERE id_control_factura='$llave'";
			$res=mysql_query(utf8_encode($sql));
			if(!$res)
				return "Error en:\n\n$sql\n\nDescripcion:\n".mysql_error();
		}
		elseif($tipoDoc == 2)
		{
			$sql="UPDATE ".$tabla."
			      SET
				  str_xml='".$xml."'
				  WHERE id_control_nota_credito='$llave'";
			$res=mysql_query($sql);
			if(!$res)
				return "Error en:\n\n$sql\n\nDescripcion:\n".mysql_error();
		}
	
		
		//Proceso de envio de mail
		$enviaMail=0;	
		
		
		
		return "exito";	
		
		
}
//---------------------------
//COMPANIA
//aqui quedo solo una compania
function getArrayCompania($id_compania,$tabla)
	{
	if($tabla=="ad_facturas_audicel"||$tabla=="ad_notas_credito_audicel")
		$sql=" SELECT id_compania,
					  '' AS CL1,	
					  '' AS CL2,	
					  '' AS CL3,	
					  '' AS CL4,	
				      REPLACE(rfc,' ','') as rfc,
					  e.razon_social,
			  calle,
			  no_exterior,
			  no_interior,
			  colonia,
			  '' as localidad,
			  '' as referencia,
			  delegacion_municipio,
			  es.nombre,
			  'MEXICO',
			  cp,
			  regimen,
			  lugar_expedicion
			  FROM sys_companias e
		       left join sys_ciudades cd on e.id_ciudad=cd.id_ciudad
			  left join sys_estados es ON cd.id_estado = es.id_estado
			  WHERE e.activo=1 ";
	elseif($tabla=="ad_facturas"||$tabla=="ad_notas_credito"){
		//$id_compania="2";//Para Pruebas
		$sql="SELECT id_cliente,
			'' AS CL1,	
			'' AS CL2,	
			'' AS CL3,	
			'' AS CL4,	
			REPLACE(rfc,' ','') as rfc,
			e.nombre_razon_social,
			calle,
			numero_exterior,
			numero_interior,
			colonia,
			'' as localidad,
			'' as referencia,
			delegacion_municipio,
			es.nombre,
			'MEXICO',
			cp,
			regimen,
			lugar_expedicion
			FROM ad_clientes_datos_fiscales e
			left join sys_ciudades cd on e.id_ciudad=cd.id_ciudad
			left join sys_estados es ON cd.id_estado = es.id_estado
			WHERE e.id_cliente=".$id_compania." ORDER BY id_cliente_dato_fiscal DESC LIMIT 1";
	}
			
		//para las franquicias aqui agregamos la factura		 
		
		//$res=mysql_query(utf8_decode($sql)) or die(mysql_error());
		$res=mysql_query(($sql)) or die(mysql_error());
		$row=mysql_fetch_row($res);
		for($i=0;$i<count($row);$i++){
			$row[$i]=mb_convert_encoding($row[$i],"ISO-8859-1","UTF-8");			
		}
		$row[5]=preg_replace("/[^a-z0-9A-Záéíóú.(), %-]/","",$row[5]);
		$row[13]=preg_replace("/[^a-z0-9A-Záéíóú.(), %-]/","",$row[13]);
		return $row;
	}

//--------------------------
function getArraySucursal($id_compania,$tabla)
{
	$datos=array();
	return $datos;
}

//---------------
function getArrayCliente($tipoDoc, $llave,$tabla)
{
	$arrRegistro=array();
	//tipo de documento es factura bus
	
	if($tipoDoc == 1)
	{
		//de la factura obtenida, tomamos los valores del cliente
		if($tabla=="ad_facturas_audicel"){
			$sql="SELECT ".$tabla.".id_fiscales_cliente,
			'' as CL1,
			'' as CL2,
			'' as CL3,
			'' as CL4,
			rfc,
			nombre_razon_social,
			calle,
			numero_exterior,
			numero_interior,
			colonia,
			'' as localidad,
			'' as referencia,
			delegacion_municipio,
			sys_estados.nombre,
			'MEXICO' as pais,
			cp
			FROM ".$tabla."
			left join ad_clientes_datos_fiscales on ".$tabla.".id_fiscales_cliente=ad_clientes_datos_fiscales.id_cliente_dato_fiscal
			left join sys_ciudades  ON ad_clientes_datos_fiscales.id_ciudad = sys_ciudades.id_ciudad
			left join sys_estados  ON sys_ciudades.id_estado = sys_estados.id_estado
			where id_control_factura=".$llave;	
		}elseif($tabla=="ad_facturas"){
			$sql="SELECT ".$tabla.".id_fiscales_cliente,
			'' as CL1,
			'' as CL2,
			'' as CL3,
			'' as CL4,
			rfc,
			razon_social,
			calle,
			no_exterior,
			no_interior,
			colonia,
			'' as localidad,
			'' as referencia,
			delegacion_municipio,
			sys_estados.nombre,
			'MEXICO' as pais,
			cp
			FROM ".$tabla."
			left join sys_companias on ".$tabla.".id_fiscales_cliente=sys_companias.id_compania
			left join sys_ciudades  ON sys_companias.id_ciudad = sys_ciudades.id_ciudad
			left join sys_estados  ON sys_ciudades.id_estado = sys_estados.id_estado
			where id_control_factura=".$llave;
		}
		$arrRegistro=valBuscador_cfdi($sql);	
//print_r($arrRegistro);			
		
	}
	elseif($tipoDoc == 2)
	{
		//de la factura obtenida, tomamos los valores del cliente
		$sql="SELECT ".$tabla.".id_fiscales_cliente,
				'' as CL1,
				'' as CL2,
				'' as CL3,
				'' as CL4,
				rfc,
				nombre_razon_social,
				calle,
				numero_exterior,
				numero_interior,
				colonia,
				'' as localidad,
				'' as referencia,
				delegacion_municipio,
				na_estados.nombre,
				'MEXICO' as pais,
				cp
        	FROM ".$tabla."
				left join na_clientes_datos_fiscales on ".$tabla.".id_fiscales_cliente=na_clientes_datos_fiscales.id_cliente_dato_fiscal
        		left join na_ciudades  ON na_clientes_datos_fiscales.id_ciudad = na_ciudades.id_ciudad
        		left join na_estados  ON na_ciudades.id_estado = na_estados.id_estado
		    where  id_control_nota_credito=".$llave;
				
				
		$arrRegistro=valBuscador_cfdi($sql);				
		
	}
	
	//quitamos los acentos, los - las $row[13]=preg_replace("/[^a-z0-9A-Záéíóú.(), %-]/","",$row[13]);
	for($i=0;$i<count($arrRegistro);$i++){
		$arrRegistro[$i]=mb_convert_encoding($arrRegistro[$i],"ISO-8859-1","UTF-8");			
	}
	
	//reemplazamos los espacion y los -	 
	//5 RFC, hasta tres veces si aparece  el -
	$arrRegistro[5]=str_replace('-','',$arrRegistro[5]);
	$arrRegistro[5]=str_replace('-','',$arrRegistro[5]);
	$arrRegistro[5]=str_replace('-','',$arrRegistro[5]);
	$arrRegistro[5]=str_replace('-','',$arrRegistro[5]);
	
	$arrRegistro[5]=str_replace(' ','',$arrRegistro[5]);
	$arrRegistro[5]=str_replace(' ','',$arrRegistro[5]);
	$arrRegistro[5]=str_replace(' ','',$arrRegistro[5]);
	$arrRegistro[5]=str_replace(' ','',$arrRegistro[5]);
	$arrRegistro[5]=str_replace(' ','',$arrRegistro[5]);
	
	
	
	for($i=6;$i<17;$i++)
	{
			$arrRegistro[$i]=quitaEspaciosEntrePalabras($arrRegistro[$i]);
			
	}
	return $arrRegistro;
}

//------------------------------------------------------------------------------------------
//especial para nasser
function getArrayClienteNoRequierenFactura($tipoDoc, $llave)
{
	$arrRegistro=array();
	//tipo de documento es factura bus
	if($tipoDoc == 1)
	{
		//de la factura obtenida, tomamos los valores del cliente
		$sql="SELECT 0 as id_fiscales_cliente,
				'' as CL1,
				'' as CL2,
				'' as CL3,
				'' as CL4,
				'XAXX010101000' as rfc,
				CONCAT(na_clientes.nombre, ' ', apellido_paterno, ' ', if(apellido_materno is null,'',apellido_materno))  as nombre_razon_social,
				if(na_clientes_direcciones_entrega.calle is null,'SI',na_clientes_direcciones_entrega.calle),
				if(na_clientes_direcciones_entrega.numero_exterior is null,'SI',na_clientes_direcciones_entrega.numero_exterior),
				na_clientes_direcciones_entrega.numero_interior,
				if(na_clientes_direcciones_entrega.colonia is null,'SI',na_clientes_direcciones_entrega.colonia),
				
				'' as localidad,
				'' as referencia,
				
				if(na_clientes_direcciones_entrega.delegacion_municipio is null,'SI',na_clientes_direcciones_entrega.delegacion_municipio),
				
				if(na_estados.nombre is null,'SI',na_estados.nombre),
				
				
				'MEXICO' as pais,
				
				
				if(na_clientes_direcciones_entrega.codigo_postal is null,'00000',na_clientes_direcciones_entrega.codigo_postal)
				
        FROM ".$tabla."
				left join ad_pedidos on ".$tabla.".id_control_pedido=ad_pedidos.id_control_pedido
				left join na_clientes_direcciones_entrega on ad_pedidos.id_direccion_entrega=na_clientes_direcciones_entrega.id_cliente_direccion_entrega
				left join na_estados  ON na_estados.id_estado = na_clientes_direcciones_entrega.id_estado
				left join na_clientes on na_clientes.id_cliente=".$tabla.".id_cliente
				where ".$tabla.".id_control_factura=".$llave;
		$arrRegistro=valBuscador_cfdi($sql);		
		
		
		
	}
	elseif($tipoDoc == 2)
	{
		//falta definir esta parte de las direcciones fiscales
		//de la factura obtenida, tomamos los valores del cliente
		$sql="SELECT ".$tabla.".id_fiscales_cliente,
				'' as CL1,
				'' as CL2,
				'' as CL3,
				'' as CL4,
				rfc,
				nombre_razon_social,
				calle,
				numero_exterior,
				numero_interior,
				colonia,
				'' as localidad,
				'' as referencia,
				delegacion_municipio,
				nombre,
				'MEXICO' as pais,
				cp_f FROM ".$tabla."
				left join na_clientes_datos_fiscales on ".$tabla.".id_fiscales_cliente=na_clientes_datos_fiscales.id_cliente_dato_fiscal
				left join na_estados  ON id_ciudad_f = id_estado
				where id_control_nota_credito=".$llave;
				
				
		$arrRegistro=valBuscador_cfdi($sql);				
		
	}
	
	for($i=5;$i<15;$i++)
	{
			$arrRegistro[$i]=quitaEspaciosEntrePalabras($arrRegistro[$i]);
			
	}
	
	return $arrRegistro;
}

//------------------------------------------------------------------------------------------

function getArraytablaFacNcO($tipoDoc, $llave,$tabla)
	{
	
		if($tipoDoc == 1)
		{
			$sql="SELECT 
					id_control_factura,
					'' as CL1,
					'' as CL2,
					'' as CL3,
					'' as CL4,
					prefijo as serie,
					id_factura as folio,
					subtotal,
					'0.00' as descuento,
					total,
					fps.descripcion,
					cuenta
				FROM ".$tabla." left join scfdi_formas_de_pago_sat fps ON fps.id_forma_pago_sat=".$tabla.".id_forma_pago_sat
				WHERE id_control_factura = ".$llave;
							  
				  //del historico de razones sociales de la que este seleccionada en la factura
				  			
		}
		elseif($tipoDoc == 2)
		{
			$sql="SELECT 
					id_control_nota_credito,
					'' as CL1,
					'' as CL2,
					'' as CL3,
					'' as CL4,
					prefijo as serie,
					id_nota_credito as folio,
					subtotal,
					'0.00' as descuento,
					total,
					fps.descripcion,
					cuenta
				FROM ".$tabla." left join scfdi_formas_de_pago_sat fps ON fps.id_forma_pago_sat=".$tabla.".id_forma_pago_sat
				WHERE id_control_factura = ".$llave;
							  
				  //del historico de razones sociales de la que este seleccionada en la factura
				  			
		}
		
		
		$arrRegistro=valBuscador_cfdi($sql);
		return $arrRegistro;	
	}
	


function getArrayProductos($tipo, $llave,$tabla){
	if($tabla=="ad_facturas")
		$tablaDetalle="ad_facturas_detalle";
	elseif($tabla=="ad_facturas_audicel")
		$tablaDetalle="ad_facturas_audicel_detalle";
	elseif($tabla=="ad_notas_credito")
		$tablaDetalle="ad_notas_credito_detalle";
	elseif($tabla=="ad_notas_credito_audicel")
		$tablaDetalle="ad_facturas_audicel_detalle";
	$productos=array();
	if($tipo == 1)
	{
		$sql="SELECT REPLACE(FORMAT(cantidad, 2), ',', ''),scfdi_unidades.nombre,".$tablaDetalle.".id_producto,cl_productos_servicios.nombre,REPLACE(FORMAT(".$tablaDetalle.".valor_unitario, 2), ',', ''),REPLACE(FORMAT(importe, 2), ',', '')
		FROM ".$tablaDetalle." 
		LEFT JOIN cl_productos_servicios ON cl_productos_servicios.id_producto_servicio=".$tablaDetalle.".id_producto
		LEFT JOIN cl_clasificacion_productos ON cl_clasificacion_productos.id_clasificacion_producto=cl_productos_servicios.id_clasificacion
		LEFT JOIN scfdi_unidades ON scfdi_unidades.id_unidad=cl_clasificacion_productos.id_unidad
		WHERE 1 AND id_control_factura='$llave'";
	}
	elseif($tipo == 2){
		$sql="SELECT REPLACE(FORMAT(cantidad, 2), ',', ''),
			scfdi_unidades.nombre,
			na_notas_credito_detalle.id_producto,
			na_productos.nombre,
			REPLACE(FORMAT(na_notas_credito_detalle.valor_unitario, 2), ',', ''),
			REPLACE(FORMAT(importe, 2), ',', '')
		 FROM ".$tablaDetalle."
			 left join na_productos on na_productos.id_producto=".$tablaDetalle.".id_producto
			left join scfdi_unidades on scfdi_unidades.id_unidad=na_productos.id_unidad
		  WHERE  id_control_nota_credito='$llave'
		  ";
	}
		 
	$res=mysql_query(utf8_decode($sql)) or die(mysql_error());
	$num=mysql_num_rows($res);
	for($i=0;$i<$num;$i++)
	{			
		$row=mysql_fetch_row($res);
		$row[3]=preg_replace("/[^a-z0-9A-Záéíóú.(), %-]/","",$row[3]);
		$row[3]=preg_replace("/[^a-z0-9A-Z&aacute;&eacute;&iacute;&oacute;&uacute;.(), %-]/","",$row[3]);
		$row[3]=quitaEspaciosEntrePalabras($row[3]);
		array_push($productos, $row);
	}
	return $productos;
}
function getArrayImpuestos($tipo, $llave,$tabla){
	if($tabla=="ad_facturas")
		$tablaDetalle="ad_facturas_detalle";
	elseif($tabla=="ad_facturas_audicel")
		$tablaDetalle="ad_facturas_audicel_detalle";
		$impuestos=array();
	if($tipo == 1)
	{
		$sql="(
					  SELECT
					  'IVA',
					  iva_tasa,
					  REPLACE(FORMAT(SUM(iva_monto), 2), ',', '')
					  FROM ".$tablaDetalle." fd
					  WHERE fd.id_control_factura='$llave'
					   GROUP BY iva
					)
					UNION ALL
					(
					  SELECT
					  'IEPS',
					  0,
					  0.00
					  
					)
					";
		/*$sql="(
				  SELECT
				  'IVA',
				  iva,
				  REPLACE(FORMAT(SUM(monto_iva), 2), ',', '')
				  FROM of_facturas_detalle fd
				  WHERE fd.id_control_factura='$llave'
				  AND iva > 0
				  GROUP BY iva
				)
				UNION ALL
				(
				  SELECT
				  'IEPS',
				  0,
				  0.00
				  
				)
				";*/
		//el ieps es por producto, si aplica, entonces colocamos en la tabla de detalle de la factura el IEPS
				
	}
	elseif($tipo == 2)
	{
		$sql="(
				  SELECT
				  'IVA',
				  iva_tasa,
				  REPLACE(FORMAT(SUM(iva_monto), 2), ',', '')
				  FROM na_notas_credito_detalle fd
				  WHERE fd.id_control_nota_credito='$llave'
				   GROUP BY iva
				)
				UNION ALL
				(
				  SELECT
				  'IEPS',
				  0,
				  0.00
				  
				)
				";
		
		/*$sql="(
				  SELECT
				  'IVA',
				  iva,
				  REPLACE(FORMAT(SUM(monto_iva), 2), ',', '')
				  FROM of_facturas_detalle fd
				  WHERE fd.id_control_factura='$llave'
				  AND iva > 0
				  GROUP BY iva
				)
				UNION ALL
				(
				  SELECT
				  'IEPS',
				  0,
				  0.00
				  
				)
				";*/
		//el ieps es por producto, si aplica, entonces colocamos en la tabla de detalle de la factura el IEPS
				
	}
	$res=mysql_query(utf8_decode($sql)) or die(mysql_error());
	$num=mysql_num_rows($res);
	for($i=0;$i<$num;$i++)
	{
		$row=mysql_fetch_row($res);
		array_push($impuestos, $row);
	}	
	
	if($num == 0)
		array_push($impuestos, array('IVA', '0.00', '0.00'));
	return $impuestos;
	}
	
	//-----------------------
	
function getArrayImpuestosRetenidos($tipo, $llave,$tabla){
	$impuestosRetenidos=array();
	if($tipo == 1){
		$sql=" SELECT
				 'ISR',
				  retencion_isr
				  FROM ".$tabla."
				  WHERE id_control_factura='$llave'
				  AND retencion_isr > 0
				";
	}
	elseif($tipo == 2){
			$sql=" SELECT
					 'ISR',
					  retencion_isr
					  FROM ".$tabla."
					  WHERE id_control_nota_credito='$llave'
					  AND retencion_isr > 0
					";
		}
		//falta considerar el caso para notas de credito y otros tipos de documento
		
		
		$res=mysql_query(utf8_decode($sql)) or die(mysql_error().$sql);
		$num=mysql_num_rows($res);
		for($i=0;$i<$num;$i++)
		{
			$row=mysql_fetch_row($res);
			array_push($impuestosRetenidos, $row);
		}	
		
		return $impuestosRetenidos;
}
	
	function quitaEspaciosEntrePalabras($variable)
	{
		
		$variable=str_replace("    "," ",$variable);
		$variable=str_replace("    "," ",$variable);
		$variable=str_replace("    "," ",$variable);
		$variable=str_replace("   "," ",$variable);
		$variable=str_replace("   "," ",$variable);
		$variable=str_replace("   "," ",$variable);
		$variable=str_replace("  "," ",$variable);
		$variable=str_replace("  "," ",$variable);
		$variable=str_replace("  "," ",$variable);
		
		$variable=trim($variable);
		 
		return $variable;		
		
	}
function cfdiCancelaFactura($tipo_documento,$id_documento,$id_compania,$tabla){
	if($tabla=='ad_facturas_audicel'||$tabla=='ad_facturas'){
		$campos = "";
		$left = "";
		$where = "";
		if($tabla=='ad_facturas_audicel'){
			$campos = "folio_cfd,sello,sys_companias.rfc";
			$left = "LEFT JOIN sys_companias ON ad_facturas_audicel.id_compania = sys_companias.id_compania";
			$where = "sys_companias.activo = 1";
		} else{
			$campos = "folio_cfd,sello,ad_clientes_datos_fiscales.rfc";
			$left = "LEFT JOIN ad_clientes ON ad_facturas.id_compania = ad_clientes.id_cliente
				 LEFT JOIN ad_clientes_datos_fiscales ON ad_facturas.id_compania_fiscal = ad_clientes_datos_fiscales.id_cliente_dato_fiscal";
			$where = "ad_clientes_datos_fiscales.activo = 1 AND ad_clientes.id_cliente = ".$id_compania;
		}
			
		$sqlUUID = "SELECT ".$campos."
					FROM ".$tabla.' '.$left." 
					WHERE ".$where." AND id_control_factura = ".$id_documento;
	}
	$result = mysql_query($sqlUUID);
	$aUUID = mysql_fetch_array($result);
	$UUID = $aUUID[0];
	$Sello = $aUUID[1];
	$RFC = $aUUID[2];
	if($tabla=='ad_facturas_audicel'||$tabla=='ad_notas_credito_audicel'){
			$sql="
				SELECT
					  no_certificado,
					  file_cer as ruta_certificado,
					  file_key as ruta_llave_privada_key,
					  password as password,
					  id_certificado as id_certificado
			 	FROM scfdi_certificados
			  	WHERE activo=1";	
		}
		elseif($tabla=='ad_facturas'||$tabla=='ad_notas_credito'){
			//$id_compania="2";//para Prueba
		
			$sql="
			SELECT 
				no_certificado,
				ruta_cer AS ruta_certificado,
				ruta_key AS ruta_llave_privada_key,
				contrasenia AS password
			FROM ad_clientes 
			WHERE id_cliente = ".$id_compania." AND activo=1";
		}
	$res = mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion\n".mysql_error());	  
		
		if(mysql_num_rows($res) <= 0){
			return "No se encontro configuracion para este proceso.";
		}	
		$row = mysql_fetch_assoc($res);
		extract($row);
		if(!file_exists($ruta_certificado)){
			return "No se encontro el certificado.";
		}
		if(!file_exists($ruta_llave_privada_key)){
			return "No se encontro la llave privada.";
		}
	
	$respuestaLeer = leerKey($ruta_llave_privada_key,$password,$ruta_certificado);	
	$xml_cancelacion = "";
	
	$fecha = date("Y-m-d");
	$hora = date("H:i:s");	
	
	$xml_cancelacion .= '<?xml version="1.0"?>';
	$xml_cancelacion .= 	'<Cancelacion xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" RfcEmisor="'.$RFC.'" Fecha="'.$fecha.'T'.$hora.'" xmlns="http://cancelacfd.sat.gob.mx">';
	$xml_cancelacion .= 		'<Folios>';
	$xml_cancelacion .= 			'<UUID>'.$UUID.'</UUID>';
	$xml_cancelacion .=		'</Folios>';
	$xml_cancelacion .= '</Cancelacion>';
	
	$dom = new DOMDocument(); 

	$yourXML = $xml_cancelacion;
	$dom -> loadXML($yourXML);
	$canonicalized = $dom -> C14N();
	$digest = base64_encode(pack("H*",sha1($canonicalized)));
	
	$xmlSignature = utf8_encode('<SignedInfo xmlns="http://www.w3.org/2000/09/xmldsig#" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"></CanonicalizationMethod><SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"></SignatureMethod><Reference URI=""><Transforms><Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature"></Transform></Transforms><DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"></DigestMethod><DigestValue>'.$digest.'</DigestValue></Reference></SignedInfo>');
	$filename  = "../../../audicel_archivos/certificados_audicel/utf8_xmlSignature.txt";
	$filename2 = "../../../audicel_archivos/certificados_audicel/xmlSignatureFinal.txt";					

	$fp = fopen ($filename, "w+"); 
	if($fp){
		fwrite($fp, $xmlSignature);
	}
	else
		die("Error al abrir el archivo...");	
	fclose($fp);
	exec("openssl dgst -sha1 -sign ".$ruta_llave_privada_key.".pem $filename | openssl enc -base64 -A > $filename2");
 
 	$archivo = fopen($filename2,'r');
	$SignatureValue = fread($archivo, filesize($filename2));
	
	unlink($filename);
	unlink($filename2);
	
	$key = file_get_contents($ruta_llave_privada_key.".pem");
	$data = openssl_pkey_get_private($key);
	$data = openssl_pkey_get_details($data);

	$key = $data['key'];
	$modulus = $data['rsa']['n'];

	
	
	$xml_cancelacion = str_replace('</Cancelacion>','',$xml_cancelacion);
	$xml_cancelacion .=		'<Signature xmlns="http://www.w3.org/2000/09/xmldsig#">';
	$xml_cancelacion .=			'<SignedInfo>';
	$xml_cancelacion .= 			'<CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315" />';
	$xml_cancelacion .= 			'<SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1" />';
	$xml_cancelacion .=				'<Reference URI="">';
	$xml_cancelacion .=					'<Transforms>';
	$xml_cancelacion .=						'<Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature" />';
	$xml_cancelacion .= 				'</Transforms>';
	$xml_cancelacion .= 				'<DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1" />';
	$xml_cancelacion .= 				'<DigestValue>'.$digest.'</DigestValue>';
	$xml_cancelacion .= 			'</Reference>';
	$xml_cancelacion .= 		'</SignedInfo>';
	$xml_cancelacion .= 	'<SignatureValue>';
	$xml_cancelacion .= 		$SignatureValue;
	$xml_cancelacion .= 	'</SignatureValue>';
	$xml_cancelacion .= 	'<KeyInfo>';
	$xml_cancelacion .= 		'<X509Data>';
	$xml_cancelacion .= 			'<X509IssuerSerial>';
	$xml_cancelacion .=					'<X509IssuerName>'.$respuestaLeer['X509IssuerName'].'</X509IssuerName>';
	$xml_cancelacion .= 				'<X509SerialNumber>'.$respuestaLeer['X509Serial'].'</X509SerialNumber>';
	$xml_cancelacion .= 			'</X509IssuerSerial>';
	$xml_cancelacion .= 			'<X509Certificate>';
	$xml_cancelacion .= 				$respuestaLeer['Certificado'];
	$xml_cancelacion .= 			'</X509Certificate>';
	$xml_cancelacion .= 		'</X509Data>';
	$xml_cancelacion .= 		'<KeyValue>';
	$xml_cancelacion .= 			'<RSAKeyValue>';
	$xml_cancelacion .= 				'<Modulus>';
	$xml_cancelacion .= 				base64_encode($modulus);
	$xml_cancelacion .= 				'</Modulus>';
	$xml_cancelacion .= 				'<Exponent>';
	$xml_cancelacion .= 				'AQAB';
	$xml_cancelacion .= 				'</Exponent>';
	$xml_cancelacion .= 			'</RSAKeyValue>';
	$xml_cancelacion .= 		'</KeyValue>';
	$xml_cancelacion .= 	'</KeyInfo>';
	$xml_cancelacion .= '</Signature>';
	$xml_cancelacion .= '</Cancelacion>';
	
	
	include("../cfdi/servicioTimbradorCancelar.php");
	return $respuesta;
	}
	function leerKey($file,$password,$certificado){
		$X509Data = array();
		$fileCer = $certificado.".pem";	
		$key = $file.".pem";
		$nombreCertificado = str_replace('.cer','',$certificado);
		$nombreArchivo = str_replace('.key','',$file);
		
		

		if(!file_exists($fileCer)){
			$comandoCert = "openssl x509 -inform DER -outform PEM -in $certificado -pubkey > $fileCer";	
			shell_exec($comandoCert); 
			chmod($fileCer,0777);

						
		}
			
		if(!file_exists($fileCer))			
			die("No existe el archivo PEM para el certificado");	
		else{
			$ar = fopen($fileCer, "rt");
			if($ar){
				while(!feof($ar)){
					$ax = fgets($ar, 10000);		
					if($nver == 1)
						$cert.=$ax;
					if(strstr($ax,"-----BEGIN CERTIFICATE-----") != false){				
						$nver = 1;					
						$cert .= $ax;
					}
				}
			}
			$cert = str_replace("-----BEGIN CERTIFICATE-----", "", $cert);
			$cert = str_replace("-----END CERTIFICATE-----", "", $cert);
			$cert = str_replace("\n", "", $cert);
			$X509Data['Certificado'] = $cert;

			$comandotxt = 'openssl x509 -inform PEM -in '.$fileCer.' -noout -issuer > ' .$nombreCertificado. 'Usuario.txt';	
			shell_exec($comandotxt);

			$archivo = fopen($nombreCertificado. 'Usuario.txt','r');
			$X509IssuerName = fread($archivo, filesize($nombreCertificado. 'Usuario.txt'));

			$X509IssuerName = str_replace('\xc3\xA1',utf8_decode('á'),$X509IssuerName);
			$X509IssuerName = str_replace('\xC3\xA9',utf8_decode('é'),$X509IssuerName);
			$X509IssuerName = str_replace('\xC3\xAD',utf8_decode('i'),$X509IssuerName);
			$X509IssuerName = str_replace('\xC3\xB3',utf8_decode('ó'),$X509IssuerName);
			$X509IssuerName = str_replace('\xc3\xBA',utf8_decode('ó'),$X509IssuerName);
			$X509IssuerName = str_replace('issuer= /',utf8_decode(''),$X509IssuerName);
			
			$comandotxt = 'openssl x509 -inform PEM -in '.$fileCer.' -noout -serial > ' .$nombreCertificado. 'Serial.txt';	
			shell_exec($comandotxt);
			
			$archivo = fopen($nombreCertificado. 'Serial.txt','r');
			$X509Serial = fread($archivo, filesize($nombreCertificado. 'Serial.txt'));
			$X509Serial = str_replace('serial=','',$X509Serial);
			$X509Serial = str_split($X509Serial);
			
			$serie = "";
			for($i = 0; $i < count($X509Serial); $i++){
				if($i != 0){
					if($i % 2 != 0)
						$serie .= $X509Serial[$i];
				}
			}
			$arrIssuerName = explode('/',$X509IssuerName);
			$IssuerName = "OID.1.2.840.113549.1.9.2=". str_replace('unstructuredName=','',$arrIssuerName[10]).", OID.2.5.4.45". str_replace('x500UniqueIdentifier=','',$arrIssuerName[9]).", ".$arrIssuerName[8].", ". str_replace('T','',$arrIssuerName[7]).", ".$arrIssuerName[6].", ". str_replace('postalCode','PostalCode',$arrIssuerName[5]).", ". str_replace('street','STREET',$arrIssuerName[4]).", ". str_replace('emailAddress','E',$arrIssuerName[3]).", ".$arrIssuerName[2].", ".$arrIssuerName[1].", ".$arrIssuerName[0];
			$X509Data['X509IssuerName'] = $IssuerName;
			$X509Data['X509Serial'] = $serie;
			
    		fclose($archivo);
    		unlink($nombreCertificado.'Serial.txt');
			unlink($nombreCertificado.'Usuario.txt');
    		
		}
		
		if(!file_exists($key)){
			$comando = "openssl pkcs8 -inform DER -in ".$file." -out ".$key." -passin pass:".$password;
			shell_exec($comando); 		
			chmod($key,0777);
		}
		/*if(!file_exists($key))			
			die("No existe el archivo PEM para la llave");	
		
		else{
   			$comandoTxt = "openssl rsa -inform PEM -outform PEM -in ".$key." -out ".$nombreArchivo.".txt -text";
				shell_exec($comandoTxt);
		}*/
		return $X509Data;
		//Extraer los datos de cada "modulo"
		//$Modulus = obtenerCadena($Cadena, 'modulus:', 'publicExponent:');
		
		// eliminar los dos puntos ":" y convertir de hexadecimal a string y convertir a base64:
		//$StringModules = SSLDataExtractedToXMLData($Modulus, true);
		/*
		//Creamos un archivo con la cadena Original en utf8
		
		$cadenaUTF8 = utf8_encode($cadena);		
		$filename = $rutaCache."utf8_$diferenciador".".txt";
		$filename2 = $rutaCache."selloFinal_$diferenciador.txt";					
		
		
		$fp = fopen ($filename, "w+"); 
		if($fp)
		{
			fwrite($fp, $cadenaUTF8); 
		}
		else
			die("Error al abrir el archivo...");	
		fclose($fp); 				
		//Encriptamos md5, rsa y base64 en un sólo paso
		
		//exec("openssl dgst -md5 -sign $key $filename | openssl enc -base64 -A > $filename2");
		//	$key = $file.".pem";
		
		exec("openssl dgst -sha1 -sign $key $filename | openssl enc -base64 -A > $filename2");
			
		//Eliminamos el archivo creado
		if(file_exists($filename))
			unlink($filename);
								
		//leemos el sello			
		$ar=fopen($filename2, "rt");
		if($ar)
		{
			$sello="";
			while(!feof($ar))
			{
				$sello.=fgets($ar, 10000);
			}
		}
		else
			die("Error al arbir el archivo de sello final");
		fclose($ar);		
		//Eliminamos el archivo creado
		if(file_exists($filename2))
			unlink($filename2);
		return $sello;*/
	}
function obtenerCadena($contenido,$inicio,$fin){
	$r = explode($inicio, $contenido);
	if (isset($r[1])){
	  $r = explode($fin, $r[1]);
	  $saltos = array("\r\n", "\n\r", "\n", "\r");
	  $r[0] = str_replace ($saltos, "", $r[0]);
	  $r[0] = str_replace(" ", "", $r[0]);
	  return $r[0];
	}
	return '';
}
function SSLDataExtractedToXMLData($SSLData, $JumpFirst){
   $ArrVals = explode(":", $SSLData);
   $a = "";
   if ($JumpFirst){
      $inicio = 1;
   } else {
      $inicio = 0;
   }
   for($i = $inicio; $i < count($ArrVals); $i = $i + 1){
      $a = $a.hexToStr($ArrVals[$i]);
   }
   return base64_encode($a);
}

function hexToStr($hex){
    $string='';
    for ($i=0; $i < strlen($hex)-1; $i+=2){
        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
    }
    return $string;
}
?> 
