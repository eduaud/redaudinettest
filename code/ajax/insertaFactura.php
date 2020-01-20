<?php	
	extract($_GET);
	extract($_POST);
include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");


include("../../code/cfdi/funciones_facturacion.php");


include("../../code/cfdi/facturacion_sellado_timbrado.php");

include_once(".../../code/pdf/qrcode.php");

	if(!(isset($rutaServ))){
		$rutaServ = $_SERVER['DOCUMENT_ROOT']."/system/";
		include_once($rutaServ . "config.inc.php");
	}
	
	//echo $rutaServ ;
	
	include_once($rutaServ . "conect.php");
	include_once($rutaServ . "include/fpdf153/nclasepdf.php");	
	//include_once($rutaServ . "code/general/funciones.php");




$pedidos = $_POST['pedidos'];
$pago_sat = $_POST['pago_sat'];
$cuenta = $_POST['cuenta'];
$factura_servicios = $_POST['factura_servicios'];

/*
$datos = explode(",", $pedidos);
$datosSAT = explode(",", $pago_sat);
$datosCuenta = explode(",", $cuenta);
$factura_servicios = explode(",", $factura_servicios);*/

 

$reqFac = $_POST['reqFac'];
$tipo_factura_xpedido_oglobal = $_POST['tipo_factura_xpedido_oglobal'];


$iva_general = 16;
		
//si requiere factura entonces es una por pedidp
if($reqFac =='si')
{

		$respuesta =generaFacturaPorPedido('cliente',$pedidos,$pago_sat,$cuenta,$factura_servicios,$iva_general);
		echo $respuesta;
		
}
else
{
	//se cambio a generar una factura por cada pedido, la global ya no aplica
		
		$respuesta =	generaFacturaPorPedido('generico',$pedidos,$pago_sat,$cuenta,$factura_servicios,$iva_general);
		
		echo $respuesta;
	

	
}


//funciones para cada tipo

function generaFacturaPorPedido($tipoCliente,$pedidos,$pago_sat,$cuenta,$factura_servicios,$iva_general)
{
		//si es una factura por cada pedido seleccionados
		
		
		$datos = explode(",", $pedidos);
		$datosSAT = explode(",", $pago_sat);
		$datosCuenta = explode(",", $cuenta);
		$factura_servicios = explode(",", $factura_servicios);
		
		$contador = count($datos);
		for($i=0; $i<$contador; $i++)
		{
				
				$montoIvaEnc =0;
				$subtotal = 0;
				
				
				
				/************************Consulta de encabezado de factura******************************/
				//obtenemos tambien si en el pedido consulto si era pendo
				$sql = "SELECT ad_clientes.id_cliente AS cliente, 
									ad_pedidos.id_control_pedido AS id_pedido,
									ad_pedidos.id_sucursal_alta AS id_sucursal, 
									ad_clientes.email_envio_facturas AS email, 
									descuento_aprobado AS descuento,
									ad_pedidos.requiere_factura as  requiere_factura,
									ad_pedidos.id_pedido as id_consecutivo_pedido
							FROM ad_pedidos
							LEFT JOIN ad_clientes ON ad_pedidos.id_cliente = ad_clientes.id_cliente
							WHERE  ad_pedidos.id_control_pedido = " . $datos[$i];
							
				//echo  $sql ;
				
				$result = new consultarTabla($sql);
				
				$registro = $result -> obtenerLineaRegistro();
				
				/************************Consulta de detalle de factura******************************/
				$sql4 = "SELECT id_pedido_detalle, 
										id_producto, 
										cantidad_requerida, 
										precio, 
										importe, 
										observaciones 
										FROM na_pedidos_detalle 
										WHERE na_pedidos_detalle.activoDetPedido=1 AND id_control_pedido = " . $datos[$i];
				$result4 = new consultarTabla($sql4);
				$pedido_detalles = $result4 -> obtenerRegistros();
				
				
				
				/************************Consulta para obtener el ultimo dato fiscal del cliente para la factura******************************/
				if($registro['requiere_factura']  =='1')
				{
				
					$sqlFiscal = "SELECT id_cliente_dato_fiscal AS id_fiscal 
									   FROM na_clientes_datos_fiscales 
									   WHERE id_cliente = " . $registro['cliente'] . " ORDER BY id_fiscal DESC";
					$resultFiscal = new consultarTabla($sqlFiscal);
					$registroIDFiscal = $resultFiscal -> obtenerLineaRegistro();
				
				}
				else
				{
					//si es generico debemos preguntar que datos fiscales debemos traer
					
						$sqlFiscal = "SELECT id_cliente_dato_fiscal AS id_fiscal 
									   FROM na_clientes_datos_fiscales 
									   WHERE id_cliente = 0 ORDER BY id_fiscal DESC";
					$resultFiscal = new consultarTabla($sqlFiscal);
					$registroIDFiscal = $resultFiscal -> obtenerLineaRegistro();
						
				}
				/************************************************************************************/
				/* GENERACION DE LA FACTURA*-
				/************************************************************************************/				
				
				
				/*********************************Inserta encabezado de factura******************************************/

				$facturas['id_tipo_comprobante_fiscal'] = 1;

				$facturas['id_compania'] = 1;
				$facturas['id_compania_fiscal'] = 1;
				
				
				$facturas['id_cliente'] = $registro['cliente'];
				
				
				
				$facturas['id_tipo_factura'] = 2;
				$facturas['id_control_pedido'] = $registro['id_pedido'];
				$facturas['id_moneda'] = 1;
				
				//Obtener el prefijo y el consecutivo de la sucursal
						$sql2 = "SELECT prefijo FROM na_sucursales WHERE id_sucursal = " . $registro['id_sucursal'];
						$result2 = new consultarTabla($sql2);
						$sucursal = $result2 -> obtenerLineaRegistro();
					
						$sql3 = "SELECT id_factura, MAX(consecutivo) AS siguiente FROM ad_facturas_audicel WHERE prefijo = '" . $sucursal['prefijo'] . "'";
						$datos3 = new consultarTabla($sql3);
						$result3 = $datos3 -> obtenerLineaRegistro();
						$contador2 = $datos3 -> cuentaRegistros();
				
						if($contador2 == 0)
								$consecutivo = 1;
						else
								$consecutivo = $result3['siguiente'] + 1;
						
				$facturas['prefijo'] = $sucursal['prefijo'];
				$facturas['consecutivo'] = $consecutivo;
				$facturas['id_factura'] = $sucursal['prefijo'] . $consecutivo;
				$facturas['id_fiscales_cliente'] = $registroIDFiscal['id_fiscal'];
				$facturas['fecha_y_hora'] = date("Y-m-d");
				$facturas['id_sucursal'] = $registro['id_sucursal'];
				$facturas['id_forma_pago_sat'] = $datosSAT[$i];
				$facturas['email_envio_fa'] = $registro['email'];
				
				
				if($datosCuenta[$i]=='')
					$facturas['cuenta'] = "NO IDENTIFICADO";
				else
					$facturas['cuenta'] = $datosCuenta[$i];
				
				
				$facturas['activo'] = 1;
				
				$facturas['id_usuario_elaboracion'] =$_SESSION["USR"]->userid;
				
				
				
				accionesMysql($facturas, 'ad_facturas_audicel', 'Inserta');
				$ultima_factura = mysql_insert_id();
				
				/************************Inserta detalle de factura******************************/
				
			
				
				foreach($pedido_detalles as $detalles){
						$facturas_detalle['id_control_factura'] = $ultima_factura;
						$facturas_detalle['id_producto'] = $detalles -> id_producto;
						$facturas_detalle['cantidad'] = $detalles -> cantidad_requerida;
							
								//al precio le quita el iva
								$precio_sin_iva = ( $detalles -> precio/1.16); //calculaPorcentajesMonto($detalles -> precio, $iva_general, 3);
								//al precio sin iva le quita el descuento
								$precio_unitario = calculaPorcentajesMonto($precio_sin_iva, $registro['descuento'], 3);
							
						//precio sin iva y quitandole el descuento que es el que se factura
						$facturas_detalle['valor_unitario'] = $precio_unitario;
						
						//precio publico sin iva solo uinformartivo
						$facturas_detalle['valor_precio_publico'] = $precio_sin_iva;
							
								//importe es la cantidad por el precio sin iva y sin descuento
								$importe = $detalles -> cantidad_requerida * $precio_unitario;
						
						$facturas_detalle['importe'] = $importe;
						$facturas_detalle['observaciones'] = $detalles -> observaciones;
						$facturas_detalle['iva_tasa'] = $iva_general;
	
							//precio sin iva y sin descuento
							$monto_iva = $detalles -> cantidad_requerida * calculaPorcentajesMonto($precio_unitario, $iva_general, 1);
	
						$facturas_detalle['iva_monto'] = $monto_iva;
						$facturas_detalle['id_control_pedido'] = $datos[$i];
						$facturas_detalle['id_control_pedido_detalle'] = $detalles -> id_pedido_detalle;
						$facturas_detalle['precio_pedido'] = $detalles -> precio;
						
						//Calculo de subtotal del encabezado
						$subtotal += $importe;
						$montoIvaEnc += $monto_iva;
						
						accionesMysql($facturas_detalle, 'na_facturas_detalle', 'Inserta');
						}
						
				/************************Si se requiere facturar servicios complementarios******************************/		
				if($factura_servicios[$i] == 1){
						$sqlFletes = "SELECT SUM(precio * numero_camiones) AS monto_fletes FROM na_pedidos_detalle_fletes WHERE id_control_pedido = " . $datos[$i];
						$datosFletes = new consultarTabla($sqlFletes);
						$resultFletes = $datosFletes -> obtenerLineaRegistro();
						
						//Insertamos el detalle de servicios complementarios
						
						$serviciosCom['id_control_factura'] = $ultima_factura;
						$serviciosCom['id_producto'] = 0;
						$serviciosCom['cantidad'] = 1;
						
								//monto de los fletes les quita el iva 
								$flete_sin_iva =  ( $resultFletes['monto_fletes']/1.16); //calculaPorcentajesMonto($resultFletes['monto_fletes'], $iva_general, 3);
								//monto fletes sin iva y sin descuento
								$precio_unitarioF = calculaPorcentajesMonto($flete_sin_iva, $registro['descuento'], 3);
						
						$serviciosCom['valor_unitario'] = $precio_unitarioF;
						$serviciosCom['valor_precio_publico'] = $flete_sin_iva;
						
						$serviciosCom['importe'] = $precio_unitarioF;
						$serviciosCom['iva_tasa'] = $iva_general;
						
								$monto_ivaF = calculaPorcentajesMonto($precio_unitarioF, $iva_general, 1);
						
						$serviciosCom['iva_monto'] = $monto_ivaF;
						$serviciosCom['id_control_pedido'] = $datos[$i];
						$serviciosCom['id_control_pedido_detalle'] = 0;
						$serviciosCom['precio_pedido'] = $resultFletes['monto_fletes'];
						
						accionesMysql($serviciosCom, 'na_facturas_detalle', 'Inserta');
						$subtotal += $precio_unitarioF;
						$montoIvaEnc += $monto_ivaF;
						
				}
						
						
				//Calculos hechos a partir de los detalles para insertar el iva y el total de la factura
				//$iva_enc = calculaPorcentajesMonto($subtotal, $iva_general, 1);
				//$total = calculaPorcentajesMonto($subtotal, $iva_general, 2);
				
				$iva_enc=$montoIvaEnc;
				$total=$subtotal+$montoIvaEnc;
				
				
				//Actualizamos el encabezado de la factura
				$strUpdate = "UPDATE ad_facturas_audicel SET subtotal = " . $subtotal . ", iva = " . $iva_enc . ", total = " . $total . " WHERE id_control_factura = " . $ultima_factura;
				mysql_query($strUpdate) or die("Error en consulta:<br> $strUpdate <br>" . mysql_error());
				
				//Se actualiza el pedido con su factura relacionada
				
				//SELLAMOS LA FACURAR Y TIMBRAMOS LA FACTURA
				
				//DESCOMENTAR
				$errorFacturacion=cfdiSellaTimbraFactura('FAC',$ultima_factura,'1');
			
			
				//si la respuesta es diferente de exito, mandamos error
				//si no la mandamos normal
				
				if($errorFacturacion != "0")
				{
					//mandamos el mensaje de error y el numero de factura y detenemos el proceso
					//eliminamos la factura generada
					
					$strDelete = "DELETE FROM ad_facturas_audicel  WHERE id_control_factura = " . $ultima_factura;
					mysql_query($strDelete) or die("Error en consulta:<br> $strDelete <br>" . mysql_error());
					
					
					$strDelete2 = "DELETE FROM na_facturas_detalle  WHERE id_control_factura = " . $ultima_factura;
					mysql_query($strDelete2) or die("Error en consulta:<br> $strDelete2 <br>" . mysql_error());
					
					
					return "El pedido ".$registro['id_consecutivo_pedido']." , tiene los siguientes errores de facturación: \n ". $errorFacturacion;
					die();
				}
				
				//SI TODO FUE CORRECTO SE ACTUALIZA LA INFORMACION EN LA BASE DE DATOS DEL PEDIDO LA FACTURA RELACIONADA
				$strUpdate = "UPDATE ad_pedidos SET id_control_factura = " . $ultima_factura . " WHERE id_control_pedido = " . $datos[$i];
				mysql_query($strUpdate) or die("Error en consulta:<br> $strUpdate <br>" . mysql_error());
				
				
				///DESCOMENTAR APLICAR LA MISMA FUNCION QUE EN mtm PORQUE SOLO DEJA ENVIAR 1*1
				fnEnviarFacturas($ultima_factura, "../../../code/especiales/tempFactura/", '');
				
				//si hubo un error manda mensaje de error y detiene el proceso
				
				
			}
				
				
		//errorFacturacion		   cambiar la variable por el exito
		
		//mensaje de ok
		if($i==1)
		{
			$mensajePantalla ="La factura se realizó con éxito";
		}
		else
		{
			$mensajePantalla ="La facturas se realizarón con éxito";
		}
		
		return $mensajePantalla; 

	}
	
	
//-------------------------------------
function fnEnviarFacturas($fact, $ruta, $strCopia = ''){
			Global $rutaServ;
		//$fact = 342;
		$archivoXML = "";
		$archivoPDF = "";
		$xmlBdd="";
		$xmlBdd2="";
		//ASIGNAMOS LA VARIABLE QUE REQUIERE LA GENERACION DE FACTURA
		$doc = $fact;	
		
		//VALIDAR QUE SE PUEDA IMPRIMIR Y OBTENER DATOS COMPLEMENTARIOS
		$strDatos = "SELECT id_factura, DATE_FORMAT(fecha_y_hora, '%d/%m/%Y'), 	nombre_razon_social, 	IF(ISNULL(email_envio_fa), '', email_envio_fa) correo_electronico,
			cancelada,	'' as descripcion, total
			FROM ad_facturas_audicel 
			left join ad_clientes on ad_facturas_audicel.id_cliente=ad_clientes.id_cliente
			where ad_facturas_audicel.id_control_factura='$fact'
		";
		
		//echo $strDatos;
		
		$resDatos = mysql_query($strDatos); //or die("Error en \n$strDatos\n\nDescripcion:\n".mysql_error("error"));
		$rowDatos = mysql_fetch_row($resDatos);
		
		//print_r($rowDatos);
	
		if($rowDatos[0] != ''){
			
			
			
			$cuerpoMail = "				
				Por medio de la presente se le hace llegar su factura con folio <b>\"".$rowDatos[0]."\"</b> con fecha de <b>".$rowDatos[1]."</b>, por la cantidad total de <b>$ ". number_format($rowDatos[6],2)."</b>.
			";
			
			//OBTENER DATOS PARA ARCHIVO XML
			$xmlBdd="";
			$strSQL="SELECT str_xml,timbrado,folio_cfd,cancelada,str_xml_cancelacion FROM ad_facturas_audicel WHERE id_control_factura ='".$fact."' ";
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
				$xmlBdd2=str_replace('</cfdi:Comprobante>','',$xmlBdd);
				$xmlBdd2.="\n<cfdi:Complemento>\n".$timbrado."\n</cfdi:Complemento>\n</cfdi:Comprobante>";	
				
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
					"Factura Nasser  :: ".$rowDatos[0] ,
					mailLayout($rowDatos[2], $cuerpoMail), 
					 $rowDatos[3],
					$strCopia,
					$adjunto = "$archivoXML", 
					$adjunto2 = "$archivoPDF",
					"contabilidad@nassermuebles.com"
				);
			}
			
			//function mailPHP($subject, $body, $email, $bcc, $adjunto = '', $adjunto2 = '' ,$fromMail = '')
									
			//ELIMINAR ARCHIVOS TEMPORALES
			 if(file_exists($archivoXML))
				unlink($archivoXML);
			if(file_exists($archivoPDF))
				unlink($archivoPDF);
			
			//echo "Se ha enviado la factura";
			//echo "Se ha enviado la factura";
		}else{
			echo "Factura no valida";
		}
	
}
//------------------------------
	
	
	
	
?>