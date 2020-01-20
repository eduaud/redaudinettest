<?php
	php_track_vars;
	extract($_GET);
	extract($_POST);
	
//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../conect.php");
	include("../../code/general/funciones.php");
	include("../../code/ajax/notificaciones.php");
	include("../../code/cfdi/facturacion_sellado_timbrado.php");
	//id_suc
	//die(base64_decode($tabla));
	switch($opcion){
		case 1://CONSULTA PARA LA CANCELACION DE LA FACTURA
			
			//BUSCAMOS QUE LA FACTURA NO TENGA PAGOS RELACIONADOS
			if(base64_decode($tabla) == "ad_facturas"){
				$tablaFrom = "ad_cuentas_por_pagar_distribuidores_detalle_pagos";
				$tablaJoin = "ad_cuentas_por_pagar_distribuidores";
			}elseif(base64_decode($tabla) == "ad_facturas_audicel"){
				$tablaFrom = "ad_cuentas_por_pagar_operadora_detalle_pagos";
				$tablaJoin = "ad_cuentas_por_pagar_operadora";
			}
			$sql = "
				SELECT id_cxp,(pagosCP + pagosEgresos) AS totalPagos
					FROM(
						SELECT id_cuenta_por_pagar AS id_cxp,
							(
								SELECT IF(SUM(monto) IS NULL,0,SUM(monto)) 
								FROM ad_cuentas_por_pagar_operadora_detalle_pagos 
								WHERE activoDetCXP = 1 AND id_cuenta_por_pagar = aux.id_cuenta_por_pagar
							) AS pagosCP,
							(
								SELECT if( SUM(monto) is null,0,SUM(monto)) 
								FROM ad_egresos_detalle 
								WHERE activoDetEgreso = 1 AND id_cuenta_por_pagar=aux.id_cuenta_por_pagar
							) AS pagosEgresos
				FROM ad_cuentas_por_pagar_operadora AS aux
				LEFT JOIN ad_tipos_documentos ON aux.id_tipo_documento_recibido = ad_tipos_documentos.id_tipo_documento
				LEFT JOIN ad_proveedores ON aux.id_proveedor = ad_proveedores.id_proveedor
				LEFT JOIN ad_clientes ON ad_proveedores.id_cliente = ad_clientes.id_cliente
				WHERE aux.id_estatus_cuentas_por_pagar <> 3 AND aux.id_control_factura = ".$id_documento.") AS datos
				GROUP BY id_cxp";
				$result = mysql_query($sql);

				$Pagos = mysql_fetch_array($result);
				$totalPagos = $Pagos[1]; 
				//die('x:'.$totalPagos);
				if($totalPagos > 0){
					echo utf8_encode("error|No se pueden cancelar facturas con pagos realizados");	
					die();
				}else{
				//BUSCAMOS QUE LA FACTURA NO ESTE CANCELADA
					$strSQL = "SELECT 
							id_factura,
							IFNULL(cancelada,0),
							if(timbrado is null,'',timbrado) as timbrado, 
							DATE_FORMAT(fecha_hora_cancelacion, '%d/%m/%Y') as fecha_can,
							email_envio_fa,
							folio_cfd,
							id_compania_fiscal,
							id_compania
							FROM ".base64_decode($tabla)."
						WHERE id_control_factura='".$id_documento."'";
					$arrResultados = valBuscador($strSQL);
					$folioFactura = $arrResultados[0];
					$correo_electronico = $arrResultados[4];
					$id_compania_fiscal = $arrResultados[6];
					$id_compania = $arrResultados[7];
					$folio_cfd = $arrResultados[5];
			
			
			//para consultar todo  lo relacionado con la factura, que no este cancelada y que no tenga registros de cobros
			if($paso == 1)
			{
				
				//si esta cancelada- > enviamos mensaje de que ya fue cancelada con anterioridad
				if($arrResultados[1] == 1){
					$msjcan = "La factura : ".$arrResultados[0]."  ya fue cancelada anteriormente el ".$arrResultados[3].".";
					echo utf8_encode("error|".$msjcan);	
					die();
				}else{
					echo "exito|".$arrResultados[0];
					die();
				}
				
			
			}
			elseif($paso == 2){
				//TIENE FOLIO CFD SELLADA
				//MANDAMOS LLAMAR A LA FUNCION DE CANCELAR FACTURA CFDI
				if($arrResultados[1] == 1){
					$msjcan = "La factura : ".$arrResultados[0]."  ya fue cancelada anteriormente el ".$arrResultados[3].".";
					echo utf8_encode("error|".$msjcan);	
					die();
				}
				if($arrResultados[2] == 0){
					$tablaC = base64_decode($tabla);
					if($tablaC == "ad_facturas" || $tablaC == "ad_facturas_audicel")
						$errorCancelacion = cfdiCancelaFactura('FAC',$id_documento,$id_compania,$tablaC);
					elseif($tablaC == "ad_notas_credito" || $tablaC == "ad_notas_credito_audicel")
						$errorCancelacion = cfdiCancelaFactura('NC',$id_documento,$id_compania_fiscal,$tablaC);
						
					$posCancelacion = strpos($errorCancelacion,utf8_encode('codError="1112"'));
					
					if($posCancelacion === true){
						echo utf8_encode("error|No se pudo cancelar el UUID: El documento con UUID ".$folio_cfd." ya ha sido cancelado previamente");
						die();
					}
					$posCancelacion = strpos($errorCancelacion,utf8_encode('procesada correctamente'));
					
					if($posCancelacion === false){
						echo utf8_encode("error|Error al cancelar documento: ".$errorCancelacion);
						die();
					}
					//$aux=file_get_contents("http://184.168.72.73:510/bridge.aspx?id_tipo=$id_tipo&id_documento=$id_documento&cancelacion=SI&tipo_cliente=$tipo_cliente&cliente=$cliente");					
					//$aux=file_get_contents("http://216.69.181.177:421/bridge.aspx?id_tipo=$id_tipo&id_documento=$id_documento&cancelacion=SI&tipo_cliente=$tipo_cliente&cliente=$cliente&id_sucursal_franquicia=$cfdi_id_sucursal_franquicia&id_cliente_franquicia=$cfdi_id_cliente_franquicia");					
					/*					
					$ax = explode('<span id="ajaxRes">', $aux);
					if(sizeof($ax) != 2)
						echo "No se pudo conectar al sistema de timbrado.";
					$aux = $ax[1];
					
					$ax = explode('</span>', $aux);
					
					$msjcanRetorno=$ax[0];
					//echo '>> '. $ax[0];
					//die(); 
					
					$pos1 = stripos($msjcanRetorno, 'exito :');
					
					//echo $pos1;
					//if($msjcanRetorno!='exito')		
					if($pos1 === false){		
						$msjcan="El timbre no pudo ser cancelado: ".$msjcanRetorno;
						echo utf8_encode("error|".$msjcan);	
						die();
					}*/
					
				} 
				//CANCELAMOS LA CUENTA POR COBRAR y cancelamos la factura
				$strUpdate = "UPDATE ".base64_decode($tabla)." SET cancelada = 1 ,id_usuario_cancelacion='".$_SESSION["USR"]->userid."',fecha_hora_cancelacion = NOW() WHERE id_control_factura = '".$id_documento."'";
				mysql_query($strUpdate) or die("Error ".base64_decode($tabla).":". mysql_error());
				grabaBitacora(16, $tabla , 0, $id_documento, $_SESSION["USR"]->userid, 0, '', '');
				
				$strUpdateCxP = "UPDATE ad_cuentas_por_pagar_operadora SET id_estatus_cuentas_por_pagar = 3 WHERE id_control_factura = '".$id_documento."'";
				mysql_query($strUpdateCxP) or die("ad_cuentas_por_pagar_operadora:". mysql_error());
				grabaBitacora(16, base64_encode('ad_cuentas_por_pagar_operadora'), 0, $id_documento, $_SESSION["USR"]->userid, 0, '', '');
				
				$strDatos = "
					SELECT id_factura, DATE_FORMAT(fecha_hora_cancelacion, '%d/%m/%Y' ) AS fecha , razon_social, IF( ISNULL( email_envio_fa ) , '',email_envio_fa ) correo_electronico, cancelada, '' AS descripcion,CONCAT('$',FORMAT(total,2)) AS total,sys_companias.rfc,ad_clientes_datos_fiscales.nombre_razon_social AS compania
					FROM ad_facturas
					LEFT JOIN sys_companias ON ad_facturas.id_fiscales_cliente = sys_companias.id_compania
					LEFT JOIN ad_clientes_datos_fiscales ON ad_facturas.id_compania_fiscal = ad_clientes_datos_fiscales.id_cliente_dato_fiscal
					WHERE ad_facturas.id_control_factura = '".$id_documento."'";
				$resDatos = mysql_query($strDatos)or die("Error en \n$strDatos\n\nDescripcion:\n".mysql_error("error"));
				$rowDatos = mysql_fetch_array($resDatos);
				
				$facturasIDs[$j] = $rowDatos[0];
				
				$email = $rowDatos[3];
				//print_r($documentos);
				$asunto = "Factura Electronica Cancelada :: ".$rowDatos[0];
				$cuerpoMail = layoutFacturaCancelada($rowDatos,$folio_cfd);
				enviar_notificacion('', $email, $rowDatos['nombre_razon_social'], $cuerpoMail,$asunto ,"" , "rmedina@audicel.com.mx,psanjuan@audicel.com.mx,amartinez@sysandweb.com,gc@sysandweb.com",'','','');
					
				/*$strUpdatePedidos="UPDATE ad_pedidos SET id_control_factura = NULL where id_control_factura='".$id_documento."'";
				mysql_query($strUpdatePedidos) or die("Error strUpdatePedidos: " . mysql_error());*/
				
				
				grabaBitacora(16, 'b2ZfY3VlbnRhc19wb3JfY29icmFy', 0,$resIdCxC[0], $_SESSION["USR"]->userid, 0, '', '');
				//function grabaBitacora($id_accion,$tabla,$id_campo,$id_relacionado,$id_usuario,$id_menu,$otro,$observaciones,$tipoSistema=1)
				//$msjcan="La factura : ".$arrResultados[0]." se canceló con éxito";
				//enviamos email de cancelacion
				/*$tituloMail="Se le notifica que la Factura ".$folioFactura." se ha cancelado de nuestro sistema y ante el SAT";
				
				
				foreach( $arrayUsuariosEmails as $id_ususario_email ){ 
					mailPHP(utf8_decode("Cancelación de la Factura ".$folioFactura), 
						mailLayout("",$tituloMail ), $id_ususario_email,"");
				}			*/	
			}
			echo utf8_encode("exito|".utf8_decode($msjcan)."|".$arrResultados[0]);	
			}
			
		break;
			  		
	};

?>
