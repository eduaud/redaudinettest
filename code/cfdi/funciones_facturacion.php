<?php
	//obtiene dos valores apartir de un array,
	//funcion general para cualquier busqueda de un array de valores 
	function valBuscador_cfdi($strSQL)
	{
		$resultado=mysql_query($strSQL); //or rollback('Error en:',mysql_error(),mysql_errno(),$strSQL);
		$renglon=mysql_fetch_row($resultado);
		
		$arr=array();
		$columnas=mysql_num_fields($resultado);
		for($i=0;$i<$columnas;$i++)
		{
			$arr[$i]=$renglon[$i];
		}	
		
		return $arr;
	}
	
	//-------------------------------------------------------
	//-------------------------------------------------------
	//-------------------------------------------------------
	// CERTIFICADO
	// Ruta certificado $file
	function certificado($file)
	{		
	
		$filePem=$file.".pem";
		$fileCer=$file;
		
		//Creamos el pem en caso de no existir
		if(!file_exists($filePem))		
			shell_exec("openssl x509 -inform DER -outform PEM -in $fileCer -pubkey > $filePem"); 
			
		//Validamos que exista el PEM
		if(!file_exists($filePem))			
			die("No existe el archivo PEM para el certificado");
		
		$cert="";
		$nver=0;
		$ar=fopen($filePem, "rt");
		if($ar)
		{
			while(!feof($ar))
			{
				$ax=fgets($ar, 10000);		
				if($nver == 1)
					$cert.=$ax;
				if(strstr($ax,"-----BEGIN CERTIFICATE-----") != false)
				{				
					$nver=1;					
					$cert.=$ax;
				}
			}	
		}
		
		$cert=str_replace("-----BEGIN CERTIFICATE-----", "", $cert);
		$cert=str_replace("-----END CERTIFICATE-----", "", $cert);
		$cert=str_replace("\n", "", $cert);
		return $cert;
	}
	//---------termina certificado
	
	
	// SELLO
	////////////////////////////////////////////////////////////
	//CADENA ORIGINAL -->>$cadenaOriginal
	// ruta_llave_privada_key ->>file
	// password
	//  Cache $rutaCache  "/www/Facturacion/cache/" carpeta cache dentro del proyecto
	//------------------------------------------------
	
	function sello($cadena, $file, $password, $rutaCache)
	{
		//echo $cadena;
		//echo $_SERVER['REMOTE_ADDR']."--";
		$diferenciador= sprintf("%u",ip2long($_SERVER['REMOTE_ADDR']))."_".rand(0,1000);
		$key = $file.".pem";		
	
		//Creamos la llave en caso de no encontrarla
		
		$comando="openssl pkcs8 -inform DER -in $file -out $key -passin pass:$password";
		//exec($comando);
		//para linux
		shell_exec($comando);
		if(!file_exists($key))
			die("Error al generar el pem...($comando)<br>");		
		
		else{
			$peso = filesize($key);
			$tamano_archivo = round($peso/pow(1024,($i = floor(log($peso, 1024)))),$decimales ); 
			if($tamano_archivo <= 0)
				die(mb_convert_encoding("Error al generar el pem contraseña incorrecta","ISO-8859-1","UTF-8"));
		}
		//Creamos un archivo con la cadena Original en utf8
		
		$cadenaUTF8 = utf8_encode($cadena);
		$filename=$rutaCache."utf8_$diferenciador".".txt";
		$filename2=$rutaCache."selloFinal_$diferenciador.txt";					
		
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
		return $sello;
	}
	
	///-termina sello
	
	
	//--------------------------------------------
	function cadena_Original($arrayDatos,$tipoComprobante,$fechaDoc)
	{
		//descomponemos el array y en esta posicion llegaran lo datos
		$compania =$arrayDatos[0];
		$sucursal =$arrayDatos[1];
		$cliente=$arrayDatos[2];
		$tablaFacNcO=$arrayDatos[3];
		$productos=$arrayDatos[4];
		$impuestos=$arrayDatos[5];
		$impuestosRetenidos=$arrayDatos[6];
		
		//LIMPIAMOS LOS ARRAYS CON ESPACIO EN BLANCO
		//----------------------------------------------------------------------
			//----------------------------------------------------------------------
		for($i=0;$i<sizeof($productos);$i++)
		{
			for($j=0;$j<sizeof($productos[$i]);$j++)
				$productos[$i][$j]=trim($productos[$i][$j]);
		}
		for($i=0;$i<sizeof($impuestos);$i++)
		{
			for($j=0;$j<sizeof($impuestos[$i]);$j++)
				$impuestos[$i][$j]=trim($impuestos[$i][$j]);
		}
		for($i=0;$i<sizeof($impuestosRetenidos);$i++)
		{
			for($j=0;$j<sizeof($impuestosRetenidos[$i]);$j++)
				$impuestosRetenidos[$i][$j]=trim($impuestosRetenidos[$i][$j]);
		}
		
		for($j=0;$j<sizeof($compania);$j++)
		{
			$compania[$j]=trim($compania[$j]);
			$compania[$j]=str_replace("&", "&amp;", $compania[$j]);
		}
		for($j=0;$j<sizeof($sucursal);$j++)
		{
			$sucursal[$j]=trim($sucursal[$j]);
			$sucursal[$j]=str_replace("&", "&amp;", $sucursal[$j]);
		}
		
		for($j=0;$j<sizeof($cliente);$j++)
		{
			$cliente[$j]=trim($cliente[$j]);
			$cliente[$j]=str_replace("&", "&amp;", $cliente[$j]);
		}
		
		for($j=0;$j<sizeof($tablaFacNcO);$j++)
		{
			$tablaFacNcO[$j]=trim($tablaFacNcO[$j]);
			$tablaFacNcO[$j]=str_replace("&", "&amp;", $tablaFacNcO[$j]);
		}

		//-----------------------------------------------------------------
		// 1.- Datos Comprobante	
		//-----------------------------------------------------------------
			$com_version = "3.2"; 
			$com_serie = $tablaFacNcO[5]; 
			$com_folio = $tablaFacNcO[6];
			$com_fecha = fecha($fechaDoc); 
			$com_tipoDeComprobante = $tipoComprobante;
			$com_subTotal = $tablaFacNcO[7]; 
			$com_descuento = $tablaFacNcO[8]; 
			$com_total = $tablaFacNcO[9]; 
			$metodoPago=$tablaFacNcO[10];
			$num_cuenta=$tablaFacNcO[11];
			
			//*$com_noAprobacion = $general[2];
			//$com_anoAprobacion = $general[3];
			//$com_formaDePago = $cadenaIntroducida[5];
			//$com_condicionesDePago = $general[6];
			
		//-----------------------------------------------------------------
		// 2.- Datos del emisor
		//-----------------------------------------------------------------
			$em_rfc = $compania[5];
			//$em_rfc=str_replace("&", "&amp;", $em_rfc);
			$em_nombre = $compania[6];
			//$em_nombre=str_replace("&", "&amp;", $em_nombre);

			// 2.1.- Datos del domicilio fiscal del emisor
			$em_calle = $compania[7];
			$em_noExterior = $compania[8];
			$em_noInterior = $compania[9];
			$em_colonia = $compania[10];
			$em_localidad = $compania[11];
			$em_referencia = $compania[12];
			$em_municipio = $compania[13];
			$em_estado = $compania[14];
			$em_pais = $compania[15];
			$em_codigoPostal = $compania[16];
			
			$regimen=$compania[17];
			$lugarExpedicion=$compania[18];
					
			
		//-----------------------------------------------------------------
		// 3.- Datos del Domicilio de Expedición del Comprobante
		//-----------------------------------------------------------------
			$calle = "";
			$noExterior = "";
			$noInterior = "";
			$colonia = "";
			$localidad = "";
			$referencia = "";
			$municipio = "";
			$estado = "";
			$país = "";
			$codigoPostal = "";
			
			//-$calle = $sucursal[0];
			//-$noExterior = $sucursal[1];
			//-$noInterior = $sucursal[2];
			//-$colonia = $sucursal[3];
			//-$localidad = $sucursal[4];
			//-$referencia = $sucursal[5];
			//-$municipio = $sucursal[6];
			//-$estado = $sucursal[7];
			//-$país = $sucursal[8];
			//-$codigoPostal = $sucursal[9];
			
			
		//-----------------------------------------------------------------
		// 4.- Datos del Receptor
		//-----------------------------------------------------------------
			$rec_rfc = $cliente[5];
			
			//$rec_rfc=str_replace("&", "&amp;", $rec_rfc);
			
			$rec_nombre = $cliente[6];
			//$rec_nombre=str_replace("&", "&amp;", $rec_nombre);

		//-----------------------------------------------------------------
		// 5.- Datos del Domicilio Fiscal del Receptor
		//-----------------------------------------------------------------
			$rec_calle = $cliente[7];
			$rec_noExterior = $cliente[8];
			$rec_noInterior = $cliente[9];
			$rec_colonia = $cliente[10];
			$rec_localidad = $cliente[11];
			$rec_referencia = $cliente[12];
			$rec_municipio = $cliente[13];
			$rec_estado = $cliente[14];
			$rec_pais = $cliente[15];
			$rec_codigoPostal = $cliente[16];
		
		//---------------------------------------------------------

		$formaPipe = "|3.2|$com_fecha|$com_tipoDeComprobante|";
		//if($com_condicionesDePago != '')
			$formaPipe.="PAGO EN UNA SOLA EXHIBICION";
		$formaPipe .= "|$com_subTotal|$com_descuento|$com_total|$metodoPago|$lugarExpedicion|$num_cuenta|$em_rfc|$em_nombre|$em_calle|";
		
		if($em_noExterior != "")
			$formaPipe.="$em_noExterior|";
		if($em_noInterior != "")
			$formaPipe.="$em_noInterior|";
			
		if($em_colonia != "")			
			$formaPipe .= "$em_colonia|";
		
		if($em_localidad != "")
			$formaPipe.="$em_localidad|";
		if($em_referencia != "")
			$formaPipe.="$em_referencia|";
			
		$formaPipe .= "$em_municipio|$em_estado|$em_pais|$em_codigoPostal|$regimen|$rec_rfc|$rec_nombre|";
		$formaPipe .= "$rec_calle|";
		
		if($rec_noExterior != "")
			$formaPipe.="$rec_noExterior|";
		if($rec_noInterior != "")
			$formaPipe.="$rec_noInterior|";
			
		if($rec_colonia != "")	
			$formaPipe .= "$rec_colonia|";
		
		if($rec_localidad != "")
			$formaPipe.="$rec_localidad|";
		if($rec_referencia != "")
			$formaPipe.="$rec_referencia|";
		if($rec_municipio != "")
			$formaPipe.="$rec_municipio|";		
		if($rec_estado != "")
			$formaPipe.="$rec_estado|";				
		
		
		$formaPipe .= "$rec_pais|$rec_codigoPostal";
		
		for($i=0;$i<sizeof($productos);$i++)
		{			
			for($j=0;$j<sizeof($productos[$i]);$j++)
			{
				if($productos[$i][$j] != '')
					$formaPipe .= "|".$productos[$i][$j];
			}	
		}
		
		
		//IMPUESTOS RETENIDOS
		$totalImpRetenido=0;
		
		
		//echo "--->".sizeof($impuestosRetenidos)."<---";
		
		if(sizeof($impuestosRetenidos)>0)
		{
			for($i=0;$i<sizeof($impuestosRetenidos);$i++)
			{	
				//colocamos los impuestos retenidos
				$formaPipe .= "|".$impuestosRetenidos[$i][0];	
				$formaPipe .= "|".$impuestosRetenidos[$i][1];	
				
				$totalImpRetenido+=(float)$impuestosRetenidos[$i][1];	
			}
			
		}
		
		if(sizeof($impuestosRetenidos)>0)
		{
			$totalImpRetenido=round($totalImpRetenido*100);
			$totalImpRetenido=$totalImpRetenido/100;
			
			$decaux=explode('.', $totalImpRetenido);
			
			
			if(strlen($decaux[1]) == 0)
				$formaPipe .= "|$totalImpRetenido".".00";
			elseif(strlen($decaux[1]) == 1)
				$formaPipe .= "|$totalImpRetenido"."0";
			else
				$formaPipe .= "|$totalImpRetenido";
		
		}
		
		
		$totalImp=0;
		for($i=0;$i<sizeof($impuestos);$i++)
		{			
			if($impuestos[$i][0]!='IEPS'  )
			{
				for($j=0;$j<sizeof($impuestos[$i]);$j++)
				{			
					$formaPipe .= "|".$impuestos[$i][$j];
					if($j == 2)
						$totalImp+=(float)$impuestos[$i][$j];
				}
			}
		}
		
		$totalImp=round($totalImp*100);
		$totalImp=$totalImp/100;
		
		$decaux=explode('.', $totalImp);
		
		
		if(strlen($decaux[1]) == 0)
			$formaPipe .= "|$totalImp".".00";
		elseif(strlen($decaux[1]) == 1)
			$formaPipe .= "|$totalImp"."0";
		else
			$formaPipe .= "|$totalImp";
		
		$cadena_Original = "|".$formaPipe."||";
		
		//Borramos caracteres invalidos
				
		$cadena_Original=str_replace("\n"," ",$cadena_Original);		
		$cadena_Original=str_replace("\t"," ",$cadena_Original);
		$cadena_Original=str_replace('  '," ",$cadena_Original);
		$cadena_Original=str_replace('   '," ",$cadena_Original);
		$cadena_Original=str_replace('    '," ",$cadena_Original);
		$cadena_Original=str_replace('     '," ",$cadena_Original);
		
		return $cadena_Original;
	
	}	
	//----TERMINA CADENA ORIGINAL----------------------------------
		
	
	// XML
	//function nal($cadenaIntroducida,$productos, $impuestos, $fechaDoc,$regimen_lugar,$pagoCliente,$impuestosRetenidos)
 	//$xml=xmlFE($datos,                $sello,     $cert, $no_certificado, $productos, $impuestos, $nombreFile, $ruta);
	//function xmlFE($cadenaIntroducida, $selloEnc, $certPem, $noCert, $productos , $impuestos, $nombreFile, $rutaXML)
	function xmlFE($arrayDatos,$tipoComprobante,$fechaDoc,$selloEnc,$certPem,$noCert)
	{
		//descomponemos el array y en esta posicion llegaran lo datos
		$compania =$arrayDatos[0];
		$sucursal =$arrayDatos[1];
		$cliente=$arrayDatos[2];
		$tablaFacNcO=$arrayDatos[3];
		$productos=$arrayDatos[4];
		$impuestos=$arrayDatos[5];
		$impuestosRetenidos=$arrayDatos[6];
		//LIMPIAMOS LOS ARRAYS CON ESPACIO EN BLANCO
		//----------------------------------------------------------------------
		for($i=0;$i<sizeof($productos);$i++)
		{
			for($j=0;$j<sizeof($productos[$i]);$j++)
				$productos[$i][$j]=trim($productos[$i][$j]);
		}
		for($i=0;$i<sizeof($impuestos);$i++)
		{
			for($j=0;$j<sizeof($impuestos[$i]);$j++)
				$impuestos[$i][$j]=trim($impuestos[$i][$j]);
		}
		for($i=0;$i<sizeof($impuestosRetenidos);$i++)
		{
			for($j=0;$j<sizeof($impuestosRetenidos[$i]);$j++)
				$impuestosRetenidos[$i][$j]=trim($impuestosRetenidos[$i][$j]);
		}
		
		for($j=0;$j<sizeof($compania);$j++)
		{
			$compania[$j]=trim($compania[$j]);
			$compania[$j]=str_replace("&", "&amp;", $compania[$j]);
		}
		for($j=0;$j<sizeof($sucursal);$j++)
		{
			$sucursal[$j]=trim($sucursal[$j]);
			$sucursal[$j]=str_replace("&", "&amp;", $sucursal[$j]);
		}
		
		for($j=0;$j<sizeof($cliente);$j++)
		{
			$cliente[$j]=trim($cliente[$j]);
			$cliente[$j]=str_replace("&", "&amp;", $cliente[$j]);
		}
		
		for($j=0;$j<sizeof($tablaFacNcO);$j++)
		{
			$tablaFacNcO[$j]=trim($tablaFacNcO[$j]);
			$tablaFacNcO[$j]=str_replace("&", "&amp;", $tablaFacNcO[$j]);
		}

		//----------------------------------------------------------------------
        //------------------------------------
		//-----------------------------------------------------------------
		// 1.- Datos Comprobante	
		//-----------------------------------------------------------------
			$com_version = "3.2"; 
			$com_serie = $tablaFacNcO[5]; 
			$com_folio = $tablaFacNcO[6];
			$com_fecha = fecha($fechaDoc); 
			$com_tipoDeComprobante = $tipoComprobante;
			$com_subTotal = $tablaFacNcO[7]; 
			$com_descuento = $tablaFacNcO[8]; 
			$com_total = $tablaFacNcO[9]; 
			$metodoPago=$tablaFacNcO[10];
			$num_cuenta=$tablaFacNcO[11];
			
			$com_formaDePago = 'PAGO EN UNA SOLA EXHIBICION';
			//die($com_version." - ".$com_serie." - ".$com_folio." - ".$com_fecha." - ".$com_tipoDeComprobante." - ".$com_subTotal." - ".$com_descuento." - ".$com_total." - ".$metodoPago." - ".$num_cuenta);
			//$com_noAprobacion = $general[2];
			//$com_anoAprobacion = $general[3];
			//$com_formaDePago = $cadenaIntroducida[5];
			//$com_condicionesDePago = $general[6]; 
			
		//-----------------------------------------------------------------
		// 2.- Datos del emisor
		//-----------------------------------------------------------------
			$em_rfc = $compania[5];
			//$em_rfc=str_replace("&", "&amp;", $em_rfc);
			$em_nombre = $compania[6];
			//$em_nombre=str_replace("&", "&amp;", $em_nombre);

			// 2.1.- Datos del domicilio fiscal del emisor
			$em_calle = $compania[7];
			$em_noExterior = $compania[8];
			$em_noInterior = $compania[9];
			$em_colonia = $compania[10];
			$em_localidad = $compania[11];
			$em_referencia = $compania[12];
			$em_municipio = $compania[13];
			$em_estado = $compania[14];
			$em_pais = $compania[15];
			$em_codigoPostal = $compania[16];
			
			$regimen=$compania[17];
			$lugarExpedicion=$compania[18];
			
		//-----------------------------------------------------------------
		// 3.- Datos del Domicilio de Expedición del Comprobante
		//-----------------------------------------------------------------
			$calle = "";
			$noExterior = "";
			$noInterior = "";
			$colonia = "";
			$localidad = "";
			$referencia = "";
			$municipio = "";
			$estado = "";
			$país = "";
			$codigoPostal = "";
			
			//$calle = $sucursal[0];
			//$noExterior = $sucursal[1];
			//$noInterior = $sucursal[2];
			//$colonia = $sucursal[3];
			//$localidad = $sucursal[4];
			//$referencia = $sucursal[5];
			//$municipio = $sucursal[6];
			//$estado = $sucursal[7];
			//$país = $sucursal[8];
			//$codigoPostal = $sucursal[9];
			
			
		//-----------------------------------------------------------------
		// 4.- Datos del Receptor
		//-----------------------------------------------------------------
			$rec_rfc = $cliente[5];
			
			//$rec_rfc=str_replace("&", "&amp;", $rec_rfc);
			
			$rec_nombre = $cliente[6];
			//$rec_nombre=str_replace("&", "&amp;", $rec_nombre);

		//-----------------------------------------------------------------
		// 5.- Datos del Domicilio Fiscal del Receptor
		//-----------------------------------------------------------------
			$rec_calle = $cliente[7];
			$rec_noExterior = $cliente[8];
			$rec_noInterior = $cliente[9];
			$rec_colonia = $cliente[10];
			$rec_localidad = $cliente[11];
			$rec_referencia = $cliente[12];
			$rec_municipio = $cliente[13];
			$rec_estado = $cliente[14];
			$rec_pais = $cliente[15];
			$rec_codigoPostal = $cliente[16];
		
		//---------------------------------------------------------
		
		
		//-------------------------------------------------
		//-----------------------------------------------
		//-----------------------------------------
	
			
		
		$xml = '<?xml version="1.0" encoding="UTF-8"'.'?'.'> <cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv32.xsd" version="3.2" ';
		$xml = $xml.'fecha="'. $com_fecha .'" sello="'. $selloEnc .'" formaDePago="'.$com_formaDePago.'" noCertificado="'. $noCert .'" certificado="'. $certPem .'" subTotal="'.$com_subTotal.'" descuento="'.$com_descuento.'" total="'.$com_total.'" tipoDeComprobante="'.$tipoComprobante.'" metodoDePago="'.$metodoPago.'" ';
		
		 $xml = $xml .'LugarExpedicion="'.$lugarExpedicion.'" NumCtaPago="'.$num_cuenta.'" > <cfdi:Emisor rfc="'.$em_rfc.'" nombre="'.$em_nombre.'" >';
		
		//$em_calle=str_replace("Ñ", "&Ntilde;", $em_calle);
		//$em_calle=str_replace("ñ", "&ntilde;", $em_calle);
		//$em_calle=utf8_E($em_calle);
		
		$xml = $xml.'<cfdi:DomicilioFiscal calle="'.$em_calle.'" ';
		if($em_noExterior != '')
        	$xml = $xml . ' noExterior="' . $em_noExterior . '" ';
		
		if($em_noInterior != '')
        	$xml = $xml . ' noInterior="' . $em_noInterior . '" ';
        
		if($em_colonia != '')
        	$xml = $xml . ' colonia="' . $em_colonia . '" ';
        
		if($em_municipio != '')
        	$xml = $xml . ' municipio="' . $em_municipio . '" ';
            
	    if($em_estado != '')
        	$xml = $xml . ' estado="' . $em_estado . '" ';   
		   
              
        $xml = $xml .' pais="MEXICO"  codigoPostal="'.$em_codigoPostal.'"/><cfdi:RegimenFiscal Regimen="'.$regimen.'"/> </cfdi:Emisor><cfdi:Receptor rfc="'.$rec_rfc.'" nombre="'.$rec_nombre.'"><cfdi:Domicilio calle="'.$rec_calle.'" ';

		if($rec_noExterior != '')
	    	$xml = $xml . ' noExterior="' . $rec_noExterior . '" ';
       
	   if($rec_noInterior != '')
			$xml = $xml . ' noInterior="' . $rec_noInterior . '" ';
	   
	   if($rec_colonia != '')
	    	$xml = $xml . ' colonia="' . $rec_colonia . '" ';
       
	   if($rec_municipio != '')
	    	$xml = $xml . ' municipio="' . $rec_municipio . '" ';
        
		if($rec_estado != '')
	    	$xml = $xml . ' estado="' . $rec_estado . '" ';
       
       
         $xml = $xml . ' pais="MEXICO"  codigoPostal="'.$rec_codigoPostal.'"/> </cfdi:Receptor>';
		 
		  
         $xml = $xml .'<cfdi:Conceptos>';

            
		for($i=0;$i<sizeof($productos);$i++)
		{			
			
			$productos[$i][0];
					
			$xml = $xml .'<cfdi:Concepto cantidad="'.$productos[$i][0].'" unidad="'.$productos[$i][1].'" noIdentificacion="'.$productos[$i][2].'" descripcion="'.$productos[$i][3].'" valorUnitario="'.$productos[$i][4].'" importe="'.$productos[$i][5].'" /> ';
				
		}		
		
		$xml = $xml . '</cfdi:Conceptos>';
			
			

		$totalImp=0;
		for($i=0;$i<sizeof($impuestos);$i++)
		{			
			if($impuestos[$i][0]!='IEPS'  )
			{
				for($j=0;$j<sizeof($impuestos[$i]);$j++)
				{			
					if($j == 2)
						$totalImp+=(float)$impuestos[$i][$j];
				}
			}
		}
		
		$totalImp=round($totalImp*100);
		$totalImp=$totalImp/100;
		
		$decaux=explode('.', $totalImp);
		
		
		if(strlen($decaux[1]) == 0)
			$totimp .= "$totalImp".".00";
		elseif(strlen($decaux[1]) == 1)
			$totimp .= "$totalImp"."0";
		else
			$totimp .= "$totalImp";			
			
		//totimp	 formaPipe
	
        //Para los impuestos retenidos
//******************
	if(sizeof($impuestosRetenidos)>0)
		{
			for($i=0;$i<sizeof($impuestosRetenidos);$i++)
			{	
				//colocamos los impuestos retenidos
				$formaPipe .= "|".$impuestosRetenidos[$i][0];	
				$formaPipe .= "|".$impuestosRetenidos[$i][1];	
				
				$totalImpRetenido+=(float)$impuestosRetenidos[$i][1];	
			}
			
		}
		
		if(sizeof($impuestosRetenidos)>0)
		{
			$totalImpRetenido=round($totalImpRetenido*100);
			$totalImpRetenido=$totalImpRetenido/100;
			
			$decaux=explode('.', $totalImpRetenido);
			
			
			if(strlen($decaux[1]) == 0)
				$retencion .= "|$totalImpRetenido".".00";
			elseif(strlen($decaux[1]) == 1)
				$retencion .= "|$totalImpRetenido"."0";
			else
				$retencion .= "|$totalImpRetenido";
		
		}


//*************




///////////////////////////////////////////////////////////////////////////

           if(sizeof($impuestos)==0)
		   {
                if(sizeof($impuestosRetenidos)==0)
				{
                    $xml = $xml .'<cfdi:Impuestos totalImpuestosTrasladados="0.00"><cfdi:Traslados><cfdi:Traslado impuesto="IVA" tasa="0.00" importe="0.00" /></cfdi:Traslados></cfdi:Impuestos></cfdi:Comprobante>';
				}
				else
				{
                     $xml = $xml .'<cfdi:Impuestos totalImpuestosRetenidos="'.$retencion.'" totalImpuestosTrasladados="0.00"><cfdi:Retenciones><cfdi:Retencion impuesto="ISR" importe="'.$retencion.'" /></cfdi:Retenciones><cfdi:Traslados><cfdi:Traslado impuesto="IVA" tasa="0.00" importe="0.00" /></cfdi:Traslados></cfdi:Impuestos></cfdi:Comprobante>';
				}

		   }
           else
		   {
                if(sizeof($impuestosRetenidos)==0)
				{
                   $xml = $xml. '<cfdi:Impuestos totalImpuestosTrasladados="'.$totimp.'"><cfdi:Traslados>';
                   for($i=0;$i<sizeof($impuestos);$i++)
				   {
                        if($impuestos[$i][0]!='IEPS')
						{
							$xml = $xml. '<cfdi:Traslado impuesto="'.$impuestos[$i][0].'" tasa="'.$impuestos[$i][1].'" importe="'.$impuestos[$i][2].'" /> ';
						}
				   }

                   $xml = $xml. '</cfdi:Traslados></cfdi:Impuestos></cfdi:Comprobante>';
               
				}
				else
				{

                    $xml = $xml. '<cfdi:Impuestos totalImpuestosRetenidos="'.$retencion.'" totalImpuestosTrasladados="'.$totimp.'">';

                    $xml = $xml. '<cfdi:Retenciones><cfdi:Retencion impuesto="ISR" importe="'.$retencion.'"  /></cfdi:Retenciones>';

                    $xml = $xml.'<cfdi:Traslados>';
                     for($i=0;$i<sizeof($impuestos);$i++)
				  	{
                        if($impuestos[$i][0]!='IEPS')
						{
							$xml = $xml. '<cfdi:Traslado impuesto="'.$impuestos[$i][0].'" tasa="'.$impuestos[$i][1].'" importe="'.$impuestos[$i][2].'" /> ';
						}
					}

                    $xml = $xml.'</cfdi:Traslados></cfdi:Impuestos></cfdi:Comprobante>';

				}
			}
		return $xml;
	}
	
	
	
	
	// Formateo de la fecha en el formato XML requerido (ISO)
	function fecha($fechaDocumento) {
    	//$ano = date(Y);
	    //$mes = date(m);
	    //$dia = date(d);
	    //$hor = date(H);
	    //$min = date(i);
	    //$seg = date(s);
	    //$aux = $ano."-".$mes."-".$dia."T".$hor.":".$min.":".$seg;
		
		$aux=str_replace(" ", "T", $fechaDocumento);
		    
	
		
	    return ($aux);
	}
	

	
	

?> 