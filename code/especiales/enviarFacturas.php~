<?php
	function mailEnviar($llave,$tabla,$envio){	
		if(!(isset($rutaServ))){
			$rutaServ = $_SERVER['DOCUMENT_ROOT']."/sysaudinetmaster/";
			include_once($rutaServ . "config.inc.php");
		}
		
		include_once($rutaServ . "conect.php");
		include_once($rutaServ . "include/fpdf153/nclasepdf.php");	
		include_once($rutaServ . "code/general/funciones.php");
		if(isset($llave)){
			$envio=fnEnviarFacturas($llave, "",$tabla,$envio,$rutaServ);
		return $envio;
		}
	}
	function fnEnviarFacturas($fact, $ruta,$tabla,$envio,$rutaServ,$strCopia = ''){
		//	Global $rutaServ;

		//$fact = 342;
		$archivoXML = "";
		$archivoPDF = "";
		$xmlBdd2=""; 
		//ASIGNAMOS LA VARIABLE QUE REQUIERE LA GENERACION DE FACTURA
		$doc = $fact;		
		//VALIDAR QUE SE PUEDA IMPRIMIR Y OBTENER DATOS COMPLEMENTARIOS
		if($tabla=="ad_facturas_audicel"){
			$strDatos="SELECT id_factura, DATE_FORMAT( fecha_y_hora, '%d/%m/%Y' ) , nombre_razon_social, IF( ISNULL( email_envio_fa ) , '', 					email_envio_fa ) correo_electronico, cancelada, '' AS descripcion, total
					FROM ad_facturas_audicel
					LEFT JOIN ad_clientes_datos_fiscales ON ad_facturas_audicel.id_fiscales_cliente = 						ad_clientes_datos_fiscales.id_cliente_dato_fiscal
					WHERE ad_facturas_audicel.id_control_factura='".$fact."'";
		}else if($tabla=="ad_facturas"){
			$strDatos="SELECT id_factura, DATE_FORMAT( fecha_y_hora, '%d/%m/%Y' ) , razon_social, IF( ISNULL( email_envio_fa ) , '', 					email_envio_fa ) correo_electronico, cancelada, '' AS descripcion, total
					FROM ad_facturas
					LEFT JOIN sys_companias ON ad_facturas.id_fiscales_cliente=sys_companias.id_compania
					WHERE ad_facturas.id_control_factura='".$fact."'";	
		}
		
		$resDatos = mysql_query($strDatos); //or die("Error en \n$strDatos\n\nDescripcion:\n".mysql_error("error"));
		$rowDatos = mysql_fetch_row($resDatos);
		if($rowDatos[0] != ''){
			
			
			
			$cuerpoMail = "				
				Por medio de la presente se le hace llegar su factura con folio <b>\"".$rowDatos[0]."\"</b> con fecha de <b>".$rowDatos[1]."</b>, por la cantidad total de <b>$ ". number_format($rowDatos[6],2)."</b>.
			";
			//OBTENER DATOS PARA ARCHIVO XML
			$xmlBdd="";
			$strSQL="SELECT str_xml,timbrado,folio_cfd,cancelada,str_xml_cancelacion FROM ".$tabla." WHERE id_control_factura ='".$fact."' ";
			$res2=mysql_query($strSQL) or die("Error en \n$strSQL\n\nDescripcion:\n".mysql_error("error"));
			$rowFacXML=mysql_fetch_row($res2);
			if($rowFacXML[0] != '')
			{
				if($rowFacXML[3]=='1')
				{
					$xmlBdd=$rowFacXML[4];
				}
				else
				{
					$xmlBdd=$rowFacXML[0];
				}
				
				
				$timbrado=$rowFacXML[1];
				$folio_cfd=$rowFacXML[2];
				$archivoXML = $rutaServ . "code/especiales/tempFactura/" . $folio_cfd . ".xml";
				$archivoPDF = $rutaServ . "code/especiales/tempFactura/" . $folio_cfd . ".pdf";
				
			}
			
			//GENERAR XML
			if($xmlBdd!="" && $rowFacXML[3]!='1')
			{
				$sqlTipoCliente="
						SELECT id_tipo_cliente_proveedor 
						FROM ad_clientes 
						LEFT JOIN ad_facturas_audicel ON ad_clientes.id_cliente=ad_facturas_audicel.id_cliente 
						WHERE id_control_factura=".$fact;
				$resTipoCliente=mysql_query($sqlTipoCliente) or die("Error en \n$strSQL\n\nDescripcion:\n".mysql_error("error"));
				$rowTipoCliente=mysql_fetch_row($resTipoCliente);

				
					
				if($rowTipoCliente[0]=='6'){
					$xmlBdd2=str_replace('</cfdi:Comprobante>','',$xmlBdd);
					$xmlBdd2.="\n<cfdi:Complemento>\n".$timbrado."\n</cfdi:Complemento>"."\n".'<cfdi:Addenda> <SKY_RecepcionFacturas xmlns="http://repository.edicomnet.com/schemas/mx/cfd/addenda"> <NumAcreedor>40015</NumAcreedor> <TipoFacturaProveedor>master</TipoFacturaProveedor> <CodigoFacturacion>CM00000232</CodigoFacturacion> <Sistema>SAP</Sistema> <PersonaContacto>ERIKA</PersonaContacto> <NumRefSis>-</NumRefSis> </SKY_RecepcionFacturas>'."\n </cfdi:Addenda>";
					$xmlBdd2.="\n</cfdi:Comprobante>";	
				}else{
					$xmlBdd2=str_replace('</cfdi:Comprobante>','',$xmlBdd);
					$xmlBdd2.="\n<cfdi:Complemento>\n".$timbrado."\n</cfdi:Complemento>\n</cfdi:Comprobante>";
				}
				$fp = fopen($archivoXML, "a");
				 $xmlBdd2=mb_convert_encoding($xmlBdd2,"UTF-8", "ISO-8859-1");
				fwrite($fp, $xmlBdd2 . PHP_EOL);
				fclose($fp);
			}
						
			if($archivoPDF != $ruta.""){
				//GENERAR PDF
				$mesNombre=array(
									1=>'Enero',
									2=>'Febrero',
									3=>'Marzo',
									4=>'Abril',
									5=>'Mayo',
									6=>'Junio',
									7=>'Julio',
									8=>'Agosto',
									9=>'Septiembre',
									10=>'Octubre',
									11=>'Noviembre',
									12=>'Diciembre'						
								);
				$impDoc=0;$fpdf=true;
				
				//CONFIGURACION PDF FACTURA
				$orient_doc="P";
				$unid_doc="cm";
				$alto_doc=28;
				$ancho_doc=21.5;
				$ftam=9;
				$tamano_doc = array($ancho_doc, $alto_doc);
				$ypag=28;
				$impDoc++;
				$rutaImagen=$rutaServ . "code/especiales/tempFactura/";
				include($rutaServ . "code/pdf/factura.php");
				$tipoimpresion="Factura No." . $idfactura;
				
				
				grabaBitacora('8', '', '0', '0', $_SESSION["USR"]->userid, '0', $tipoimpresion, '');
				//Determina el nombre del archivo temporal
				$file = $archivoPDF;//basename(tempnam(getcwd(),'tmp'));
				//Salva elPDF en un archivo
				$pdf->Output($file);
			}
			
			
			
			
			////"administracion@thebooks.mx,sistemas@thebooks.mx",
			if( $rowDatos[3]!="" && $rowDatos[3]!=" ")
			{
				mailPHP(
					"Factura Electronica  :: ".$rowDatos[0] ,
					mailLayout($rowDatos[2], $cuerpoMail), 
					 $rowDatos[3],
					$strCopia,
					$adjunto = "$archivoXML", 
					$adjunto2 = "$archivoPDF",
					"amartinez@sysandweb.com"
				);
			}
			
			//function mailPHP($subject, $body, $email, $bcc, $adjunto = '', $adjunto2 = '' ,$fromMail = '')
									
			//ELIMINAR ARCHIVOS TEMPORALES
			 if(file_exists($archivoXML))
				unlink($archivoXML);
			if(file_exists($archivoPDF))
				unlink($archivoPDF);
				$envio='enviada';
			//echo "Se ha enviado la factura";
		}else{
			$envio='error';
		}
		return $envio;
	}
?>