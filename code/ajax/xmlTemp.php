<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$uploaddir = 'xml/'; //Directorio base
//Borramos los archivos basura de este directorio
$handle = opendir($uploaddir);
while($file = readdir($handle)) {
		if(is_file($uploaddir.$file)) {
				unlink($uploaddir.$file);
				}
		}
closedir($handle);


$timeArchivosCxP=time();
$archivo = "XML_" . $timeArchivosCxP . ".XML";
$uploadfile = $uploaddir . $archivo; //Creamos toda la ruta que se movera en el directorio

if (move_uploaded_file($_FILES['file_xml']['tmp_name'], $uploadfile)){  //Si se sube correctamente el archivo
		if(file_exists($uploadfile)) {  //Validamos que se pueda leer el archivo
				$camposXML = array();
				$conceptosXML = array();
				$insertaProv = array();
				$xml = simplexml_load_file($uploadfile);  //Cargamos la libreira XML
				$ns = $xml->getNamespaces(true); //Obtenemos los namespaces del cfdi
				$xml->registerXPathNamespace('c', $ns['cfdi']);
				$xml->registerXPathNamespace('t', $ns['tfd']);
				$xml->registerXPathNamespace('cu', $ns['customized']);
		/**********Se comienza a leer los nodos del XML***********************/
		foreach ($xml->xpath('//c:Comprobante') as $cfdiComprobante){ //xpath nos lleva al nodo que queremos leer
				$camposXML['fecha'] = $cfdiComprobante['fecha']; //Fecha del documento
				$camposXML['subtotal'] = $cfdiComprobante['subTotal']; //Fecha del documento
				$camposXML['total'] = $cfdiComprobante['total']; //Fecha del documento
				if($cfdiComprobante['folio'] == null)
						$camposXML['folio'] = "";
				else
						$camposXML['folio'] = $cfdiComprobante['folio']; //Folio del documento
				}
		//DATOS DEL PROVEEDOR ***************
		foreach ($xml->xpath('//c:Emisor') as $cfdiEmisor){
				$rfc_emisor = htmlspecialchars_decode($cfdiEmisor['rfc']); //Obtenemos el rfc del proveedor para checarlo en la BD
				$nombre_emisor = htmlspecialchars_decode($cfdiEmisor['nombre']); //Obtenemos el rfc del proveedor para checarlo en la BD
				}
		
		//*******************************Conceptos
		foreach ($xml->xpath('//c:Concepto') as $cfdiConceptos){  //Iteramos en el nodo de conceptos
				//Este array contiene todos los conceptos del xml
				$conceptosXML[] = $cfdiConceptos['descripcion'] . "|" .  $cfdiConceptos['cantidad'] . "|" . $cfdiConceptos['importe'] . "|" . $cfdiConceptos['valorUnitario'];	
				
				}
				
		//*******************************CALCULO IVA
		foreach ($xml->xpath('//c:Impuestos') as $cfdiIva){  //Iteramos en el nodo de conceptos
				$camposXML['iva'] = $cfdiIva['totalImpuestosTrasladados'];
				}
				
		//*******************************UUID
		foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfduuid){  //Iteramos en el nodo de conceptos
				$uuid_c = $tfduuid['UUID'];
				$camposXML['uuid'] = $uuid_c;
				}
				foreach ($xml->xpath('//c:Addenda') as $cfdiAddenda){  
					foreach($cfdiAddenda as $customized){
						foreach($customized as $SKY){
							foreach($SKY as $valores=>$valor){
								if($valores=='factura_sap'){
									$camposXML['factura_sap']=$valor;
								}else if($valores=='documento_sap'){
									$camposXML['documento_sap']=$valor;
								}
							}
						}
					}
				}
				
				//Consulta que verifica la existencia de uuid
				$sqlU = "SELECT 1 FROM ad_cuentas_por_pagar_operadora WHERE folio_fiscal = '" . $uuid_c . "'"; //Verificamos si existe el uuid
				$resultU = new consultarTabla($sqlU);
				$contadorU = $resultU -> cuentaRegistros();
				
				if($contadorU > 0)
						$camposXML['verifica_uuid'] = 1;
				else
						$camposXML['verifica_uuid'] = 0;
				
				
				$sql = "SELECT id_proveedor FROM ad_proveedores WHERE rfc = '" . $rfc_emisor . "' AND activo = 1"; //Verificamos si existe el proveedor
				$result = new consultarTabla($sql);
				$contador = $result -> cuentaRegistros();
				
				if($contador == 0){ //Si no existe procedemos a obtener los datos del xml sobre el proveedor e insertarlo en la base
						foreach ($xml->xpath('//cfdi:DomicilioFiscal') as $cfdiEmisorDomicilio){
								$calle = htmlspecialchars_decode($cfdiEmisorDomicilio['calle']); 
								$noExterior = htmlspecialchars_decode($cfdiEmisorDomicilio['noExterior']);
								$noInterior = htmlspecialchars_decode($cfdiEmisorDomicilio['noInterior']);
								$colonia = htmlspecialchars_decode($cfdiEmisorDomicilio['colonia']);
								$municipio = htmlspecialchars_decode($cfdiEmisorDomicilio['municipio']);
								$codigoPostal = htmlspecialchars_decode($cfdiEmisorDomicilio['codigoPostal']);
								}
						$insertaProv['razon_social'] = mb_strtoupper(utf8_decode($nombre_emisor));
						$insertaProv['id_tipo_proveedor'] = 5;
						$insertaProv['rfc'] = mb_strtoupper($rfc_emisor);
						$insertaProv['calle'] = mb_strtoupper(utf8_decode($calle));
						$insertaProv['numero_exterior'] = $noExterior;
						$insertaProv['numero_interior'] = $noInterior;
						$insertaProv['colonia'] = mb_strtoupper(utf8_decode($colonia));
						$insertaProv['codigo_postal'] = $codigoPostal;
						$insertaProv['id_pais'] = 1;
						$insertaProv['id_estado'] = 9;
						$insertaProv['activo'] = 1;
						accionesMysql($insertaProv, 'ad_proveedores', 'Inserta');
						
						$camposXML['genera_proveedor'] = 1;
						$camposXML['proveedor'] = mysql_insert_id();
						$camposXML['proveedorNombre'] = mb_strtoupper($nombre_emisor);
						}
					else{
							$id_proveedor = $result -> obtenerLineaRegistro();
							$camposXML['proveedor'] = $id_proveedor['id_proveedor'];
							}
				
				$camposXML['conceptos'] = $conceptosXML;  //Añadimos los conceptos al array que contiene todos los datos
				
				if(count($xml->xpath('//c:Retenciones')) != ""){
						$camposXML['tipo_documento'] = 5;
						//*******************************RETENCIONES
						foreach ($xml->xpath('//c:Retencion') as $cfdiRetencion){  //Iteramos en el nodo de conceptos
								//Este array contiene todos los conceptos del xml
								$comIVA = strcasecmp($cfdiRetencion['impuesto'], "IVA"); 
								$comISR = strcasecmp($cfdiRetencion['impuesto'], "ISR"); 
								
								if($comIVA == 0)
										$iva = $cfdiRetencion['importe'];
								if($comISR == 0)
										$isr = $cfdiRetencion['importe'];
										
								}
						$retencionesXML[] = $iva . "|" .  $isr;	
						}
				else{
						$camposXML['tipo_documento'] = 1;
						}
						
				$camposXML['retenciones'] = $retencionesXML;  //Añadimos las retenciones al array que contiene todos los datos
				
				$camposXML['archivo'] = $archivo;
				echo json_encode($camposXML);  //Lo convertimos en json
				
				}
		else{
				exit('Error al leer ' . $uploadfile); //Si el archivo no se leyo correctamente
					}
		}
else{
		echo "No se cargo el archivo correctamente";  //Si el archivo no se subio correctamente
		}





?>
