<?php
	php_track_vars;
	include("../../../conect.php");
	include("factura_electronica.php");
	
	extract($_POST);
	extract($_GET);
	
	$mensaje="";
    $v_regreso="";

	if($factura == 'SI')
	{
	
		$sql="SELECT
		      id_control_factura
			  FROM anderp_facturas
			  WHERE id_control_factura != 0 AND id_factura='0'";
	    //echo $sql." \n ".$id_control_factura."////-".$llave."//</br>\n";   
		$rest=mysql_query($sql);	  
		$num=mysql_num_rows($rest);
		for($jk=0;$jk<$num;$jk++)
		{
			$rowt=mysql_fetch_row($rest);
			$aux=docElectronico("anderp_facturas", $rowt[0]);
			//echo "DO ".$rowt[0]." = $aux<br>";
			$ax=explode("|", $aux);
			if($ax[0] != "exito")
			{
				$mensaje="Se generaron con exito $jk documentos electronicos. Se produjo el siguiente error: $aux";
				echo  $mensaje;
				break;
			}
			if($ax[0] == "exito"){
			    //echo "exito|$folio_nuevo|$nombre_serie|$enviaMail";
				//envio serie,folio,id_control_factura,enviaMail
				if($v_regreso ==''){
		           $v_regreso = $ax[2].$ax[1]."|".$rowt[0]."|".$ax[3];
				}
				else{
				  $v_regreso = $v_regreso."@@".$ax[2].$ax[1]."|".$rowt[0]."|".$ax[3];
				}
				//guardamos la factura Generada en Notas de Venta
				$id_factura = $ax[2].$ax[1];
				$id_control_factura = $rowt[0];
      
	  $sql = "UPDATE anderp_notas_venta SET id_factura ='".$id_factura."' WHERE id_control_factura=".$id_control_factura;
     //echo $sql."//";
	  mysql_query($sql) or die("Error en consulta $sql\n".mysql_error().mysql_errno());
	 
	   $sql3 = "UPDATE anderp_cuentas_por_cobrar SET id_factura='".$id_factura."' WHERE id_control_factura=".$id_control_factura;
	   mysql_query($sql3) or die("Error en consulta $sql3\n".mysql_error().mysql_errno());
	 
	/* $sql2 = "SELECT id_control_nota_venta FROM anderp_notas_venta WHERE id_control_factura=".$id_control_factura;  
	 //echo $sql2."</br>";
	 $msm .= $sql2."\n"; 
	 $resultado = mysql_query($sql2);	  
	 $numf = mysql_num_rows($rest);
	 $row = mysql_fetch_row($resultado);
	 if($numf > 0){
	$sql3 = "UPDATE anderp_cuentas_por_cobrar SET id_factura='".$id_factura."' WHERE id_control_nota_venta=".$row[0]; 
		//echo $sql3;
		mysql_query($sql3) or die("Error en consulta $sql3\n".mysql_error().mysql_errno());
		 $msm .= $sql3."\n"; 
		 
	 }*/
		
			}//fin if exito	
		}
		if($mensaje == ""){
			$mensaje="Se generaron con exito $jk documentos electronicos";
		    //echo $mensaje;
		}	
			
		if($v_regreso != ""){
		  // echo "exito@@".$v_regreso;
		     echo "exito|$msm|$mensaje";
			 
		}	
		//echo "v_regreso ".$v_regreso;
	}
	
	function docElectronico($tabla, $llave)
	{
		
		mysql_query("BEGIN");
	
		$tabla="anderp_facturas";		
		$tipoDoc=1;
		
				
		//validamos que el documento no exista
		$sql="SELECT 1 FROM anderp_folios_documentos WHERE id_tipo_documento=$tipoDoc AND id_control_documento='$llave'";
		//echo $sql."</br>\n";
		$res=mysql_query($sql);
		if(!$res)
		{
			mysql_query("ROLLBACK");
			return "Error en:\n\n$sql\n\nDescripcion:\n".mysql_error();
		}	
		if(mysql_num_rows($res) > 0)
		{
			mysql_query("ROLLBACK");	
			return "El documento $llave, ya ha sido generado.";
		}	
		
		
		//Buscamos una serie activa
		$sql="SELECT
			  s.id_serie,
			  s.nombre
			  FROM anderp_series_documentos sd
			  JOIN anderp_series s ON sd.id_serie = s.id_serie
			  WHERE s.activo=1
			  AND sd.id_tipo_documento=$tipoDoc
			  AND sd.activo=1";
		//echo $sql."</br>\n";
		$res=mysql_query($sql);	  
		$num=mysql_num_rows($res);
		if($num <= 0)
		{
			mysql_query("ROLLBACK");
			return "No se encontraron series activas para este tipo de documento.";
		}	
		
		$row=mysql_fetch_row($res);
		$id_serie=$row[0];
		$nombre_serie=$row[1];
		
		//Buscamos si hay folios dosponibles
		$sql="SELECT folio_final, id_control_folio, numero_aprobacion, anio_aprobacion FROM `anderp_folios` WHERE id_serie=$id_serie AND id_tipo_documento=$tipoDoc AND activo=1";
		//echo $sql."</br>\n";
		$res=mysql_query($sql);
		if(mysql_num_rows($res) <= 0)  
		{
			mysql_query("ROLLBACK");
			return "No se encontraron folios para este tipo de documento";
		}	
		$row=mysql_fetch_row($res);
		$folio_maximo=$row[0];
		$id_control_folio=$row[1];
		$numero_aprobacion=$row[2];
		$anio_aprobacion=$row[3];
		
		//Buscamos el folio maximo  de documentos ocupados		 
		$sql="SELECT IF(MAX(folio) IS NULL, 1, MAX(folio)+1) FROM `anderp_folios_documentos` WHERE id_serie=$id_serie AND id_tipo_documento=$tipoDoc";
		//echo $sql."</br>\n";
		$res=mysql_query($sql);	  
		$row=mysql_fetch_row($res);
		$folio_nuevo=$row[0];		
		
		//Buscamos si el folio nuevo no esta apartado
		
		/*$sql="SELECT
		      GROUP_CONCAT(folios_apartados SEPARATOR ',')
			  FROM `fes_folios_apartados`
			  WHERE id_serie=$id_serie
			  AND id_tipo_documento=$tipoDoc";
		$res=mysql_query($sql);	  
		$row=mysql_fetch_row($res);
		$folios_apartados=explode(",", $row[0]);
		
		
		$nver=0;
		while($nver == 0)
		{
			if(in_array($folio_nuevo, $folios_apartados))
				$folio_nuevo++;
			else
				$nver=1;			
		}*/			
	
	
		//$folio_nuevo=1;
	
		if($folio_nuevo > $folio_maximo)
		{
			$sql="UPDATE anderp_folios SET activo=0 WHERE id_control_folio=$id_control_folio";
			//echo $sql."\n";
			mysql_query($sql);
			mysql_query("COMMIT");
			return "No hay folios disponibles para este tipo de documento";
		}	
		
		//Conseguimos los datos de configuracion
		$sql="SELECT
		      c.no_certificado,
			  ruta_salida_fa,
			  ruta_salida_ncr,
			  ruta_salida_nca,
			  c.file_cer as ruta_certificado,
			  c.file_key as ruta_llave_privada_key,
			  c.password as password,
			  c.id_certificado as id_certificado
			  FROM sys_parametros_configuracion pc
			  JOIN anderp_certificados c ON c.activo = 1
			  WHERE pc.activo=1";
		//echo $sql."</br>\n";	  
		$res=mysql_query($sql);	  
		if(mysql_num_rows($res) <= 0)
		{
			mysql_query("ROLLBACK");
			return "No se encontro configuracion para este proceso.";
		}	
		$row=mysql_fetch_assoc($res);
		extract($row);
		
		$ruta=$ruta_salida_fa;
		
		//echo "Ruta Certificado".$ruta_certificado."</br>";
		//echo "llave ".$ruta_llave_privada_key."</br>";
		//echo "Ruta ".$ruta."</br>";
		//validamos los archivos de conf
		if(!file_exists($ruta_certificado))
		{
			mysql_query("ROLLBACK");
			return "No se encontro el certificado.";
		}
		if(!file_exists($ruta_llave_privada_key))
		{
			mysql_query("ROLLBACK");
			return "No se encontro la llave privada.";
		}
		if(!file_exists($ruta))
		{
			mysql_query("ROLLBACK");
			return "No se encontro la ruta de generacion de XML.";
		}	
			
		
		//conseguimos los datos de facturacion
		$sql="SELECT
		      rfc,
			  e.nombre,
			  calle,
			  no_exterior,
			  no_interior,
			  colonia,
			  localidad,
			  referencia,
			  del_mpo,			  
			  es.nombre,
			  'MEXICO',
			  cp
			  FROM anderp_emisores e
			  JOIN anderp_estados es ON e.id_estado = es.id_estado
			  WHERE e.activo=1";
		//echo $sql."</br>\n";	  
		$res=mysql_query($sql);	  
		if(mysql_num_rows($res) <= 0)
		{
			mysql_query("ROLLBACK");
			return "No se encontro emisor de facturas configurado.";
		}		  
		$row=mysql_fetch_row($res);
			
		
		
				
		
		//Obtenemos la fecha del documento
	
		$sql="SELECT fecha_y_hora FROM anderp_facturas WHERE id_control_factura='$llave'";
		//echo $sql."</br>\n";
		$res=mysql_query(utf8_decode($sql));
		if(!$res)
		{
			mysql_query("ROLLBACK");
			return "Error en:\n\n$sql\n\nDescripcion:\n".mysql_error();
		}	
		$num=mysql_num_rows($res);	
		if($num <= 0)
		{
			mysql_query("ROLLBACK");
			return "No se encontro la factura $llave";
		}	
		$ro=mysql_fetch_row($res);	
		$fechaDoc=$ro[0];
	
		
		$datos=getArrayDatos($tipoDoc, $llave, $row, $folio_nuevo, $nombre_serie, $numero_aprobacion, $anio_aprobacion);
		$productos=getArrayProductos($tipoDoc, $llave);
		$impuestos=getArrayImpuestos($tipoDoc, $llave);
		
		
		///echo "Fecha Doc ".$fechaDoc."</br>";
		
		$cadenaOriginal=cadena_Original($datos, $productos, $impuestos,$fechaDoc);		
		//echo "///";
		
		//produccion
$sello=sello($cadenaOriginal, $ruta_llave_privada_key, $password,"/var/www/vhosts/sysandweb.com/subdomains/ci/httpdocs/cache/");
//$sello=sello($cadenaOriginal, $ruta_llave_privada_key, $password,"/var/www/vhosts/sysandweb.com/subdomains/facelec/httpdocs/ci/cache/");
		//$sello=sello($cadenaOriginal, $ruta_llave_privada_key, $password, "/var/www/vhosts/sysandweb.com/httpdocs/ci/cache/");
		
		//local
		//$sello=sello($cadenaOriginal, $ruta_llave_privada_key, $password, "/www/and_development/proyectos_en_desarrollo/casaibarra/codigo/cache/");
		
		
		$cert=certificado($ruta_certificado);
		
		//echo "Original".$cadenaOriginal."</br>";
		//echo "Sello ".$sello."</br>";
		//echo "Cert ".$cert."</br>";
		
		
		$nombreFile="F".$nombre_serie.$folio_nuevo.".xml";
		
		//echo "Nombre File  ".$nombreFile."</br>";
		
		
		$xml=xmlFE($datos, $sello, $cert, $no_certificado, $productos, $impuestos, $nombreFile, $ruta);
		
		
		//Actualizamos las tablas de GEG de factura
		$sql="UPDATE anderp_facturas
			  SET 
			  prefijo='$nombre_serie',
			  consecutivo='$folio_nuevo',	
			  id_factura ='".$nombre_serie.$folio_nuevo."',		  
			  sello='$sello',
			  cadena_original='$cadenaOriginal',
			  fecha_aplicacion_sello=NOW(),
			  con_sello=1
			  WHERE id_control_factura='$llave'";
			  
			  //numero_aprobacion='$numero_aprobacion',
			 // anio_aprobacion='$anio_aprobacion', 
			//echo $sql."</br>";  
		$res=mysql_query($sql);
		if(!$res)
		{
			mysql_query("ROLLBACK");
			echo "Error en:\n\n$sql\n\nDescripcion:\n".mysql_error();
		}	
		
				
		
		//Insertamos el registro del folio
		$sql="INSERT INTO anderp_folios_documentos(id_tipo_documento, id_serie, folio, id_control_documento, fecha_envio, fecha_doc)
		                                 VALUES($tipoDoc, $id_serie, '$folio_nuevo', '".$nombre_serie.$folio_nuevo."', NOW(), '$fechaDoc')";
										 
		//echo $sql;								 
		$res=mysql_query($sql);
		if(!$res)
		{
			mysql_query("ROLLBACK");
			return "Error en:\n\n$sql\n\nDescripcion:\n".mysql_error();
		}	
										 
		
		//Insertamos los datos del reporte
		
		$totalImp=0;
		for($i=0;$i<sizeof($impuestos);$i++)
			$totalImp+=(float)$impuestos[$i][2];
			
		$efectoComp="";	
		if($tipoDoc == 1 || $tipoDoc == 3)
			$efectoComp="I";
		if($tipoDoc == 2)
			$efectoComp="E";
			
		//Borramos posible repeticion de datos
		$sql="DELETE FROM anderp_reporte_sat WHERE serie='$nombre_serie' AND folio='$folio_nuevo'";			
		$res=mysql_query($sql);
		if(!$res)
		{
			mysql_query("ROLLBACK");
			return "Error en:\n\n$sql\n\nDescripcion:\n".mysql_error();		
		}	
		
		$sql="INSERT INTO anderp_reporte_sat(mes, anio, rfc, serie, folio, no_de_aprobacion, fecha_hora_expedicion, monto_de_operacion, monto_impuesto, estado_comprobante, efecto_comprobante)
							       VALUES(DATE_FORMAT(NOW(), '%m'),DATE_FORMAT(NOW(), '%Y'), '".$datos[22]."', '$nombre_serie', '$folio_nuevo', '".$anio_aprobacion.$numero_aprobacion."', NOW(), ".$datos[9].", ".$totalImp.", 1, '$efectoComp')";

		$res=mysql_query($sql);
		if(!$res)
		{
			mysql_query("ROLLBACK");
			return "Error en:\n\n$sql\n\nDescripcion:\n".mysql_error();		
		}	
			
			
		mysql_query("COMMIT");	
		
		
		return "exito|$folio_nuevo|$nombre_serie|$enviaMail";
		
		//echo 	"exito|$folio_nuevo|$nombre_serie|$enviaMail";
		
		
	}
	
	
	function getArrayDatos($tipo, $llave, $arr, $folio, $serie, $numero_aprobacion, $anio_aprobacion)
	{
	
		$datos=array($serie, $folio, $numero_aprobacion, $anio_aprobacion);
		if($tipo == 1 || $tipo == 3)
			array_push($datos, "ingreso");
		if($tipo == 2)
			array_push($datos, "egreso");	
		
		
		$sql="SELECT
			'PAGO EN UNA SOLA EXHIBICION',
			'',
			a.subtotal,
			0,
			a.total,
			b.rfc,
			b.razon_social,
			b.calle,
			b.no_exterior,
			b.no_interior,
			b.colonia,
			'',
			b.id_localidad,
			b.delegacion_municipio,
			b.id_ciudad,
			'Mexico',
			b.cp
			FROM anderp_facturas a
			LEFT JOIN anderp_clientes b ON b.id_cliente=a.id_cliente AND b.id_sucursal=".$_SESSION["USR"]->sucursalid."
			WHERE a.id_control_factura='$llave'";		
		
		$res=mysql_query(utf8_decode($sql)) or die(mysql_error());
		$row=mysql_fetch_row($res);
		
		
		//Limpiamos el RFC
		$row[5] = str_replace(" ", "", $row[5]);
		
		//Datos de factura
		for($i=0;$i<5;$i++)
		{
			array_push($datos, $row[$i]);
		}
		
		//Datos de emisor
		for($i=0;$i<sizeof($arr);$i++)
		{
			array_push($datos, $arr[$i]);
		}
		
		//Datos de receptor
		for($i=5;$i<sizeof($row);$i++)
		{
			array_push($datos, $row[$i]);
		}
		
		return $datos;
	}
	
	function getArrayProductos($tipo, $llave)
	{
		$productos=array();
		
		$sql="SELECT
				cantidad,
				kilos_netos,
				id_producto,
				id_producto_tipo,
				id_producto_presentacion,
				valor_unitario,
				importe
				FROM `anderp_facturas_detalles`
				WHERE id_control_factura='$llave'";
		
		
		$res=mysql_query(utf8_decode($sql)) or die("Error en: $sql<br><br>".mysql_error());
		$num=mysql_num_rows($res);
		for($i=0;$i<$num;$i++)
		{			
			$row=mysql_fetch_row($res);
			$row[3]=preg_replace("/[^a-z0-9A-Z&aacute;&eacute;&iacute;&oacute;&uacute;.(), %-]/","",$row[3]);
			array_push($productos, $row);
		}	
		
		return $productos;
	}
	
	function getArrayImpuestos($tipo, $llave)
	{
		$impuestos=array();
		
		$sql="SELECT
			  'IVA',
			  16,
			  iva
			  FROM `anderp_facturas`
			  WHERE id_control_factura='$llave'
			  ";

		
		$res=mysql_query(utf8_decode($sql)) or die(mysql_error());
		$num=mysql_num_rows($res);
		for($i=0;$i<$num;$i++)
		{
			$row=mysql_fetch_row($res);
			array_push($impuestos, $row);
		}	
		
		return $impuestos;
	}
	
	
	//Permisos
	
	//$smarty->assign("mensaje",$mensaje);
	//$smarty->display("./especiales/generaDocs.tpl");
	
?> 