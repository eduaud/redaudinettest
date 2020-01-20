<?php
include("../general/funcionesAudinet.php");
include_once('../../include/phpmailer/class.phpmailer.php');

function enviar_notificacion($nombre_emisor, $correos_destino, $nombre_destinatario, $mensaje, $asunto, $correos_con_copia, $correos_con_copia_oculta, $archivos_adjuntos, $id_plaza, $id_almacen){
	/***
		En base de datos(cl_notificaciones) en el campo tipo_envio se tienen las siguientes opciones
		- destino
		- con_copia
		- con_copia_oculta
	***/
	
	// ***   Extrae los correos de destino, con_copia y con_copia_oculta segun la opcion   *** //
	if($correos_destino == ""){
		$where = "";
		
		if($id_plaza != ""){   // ***   Asignacion de Pipeline   *** //
			$where .= " AND id_plaza = ".$id_plaza;
		}
		
		if($id_almacen != ""){   // ***   Asignacion de Pipeline   *** //
			$where .= " AND id_almacen = ".$id_almacen;
		}
		
		$query_correos_destino = "
			SELECT GROUP_CONCAT(correo) AS correos
			FROM cl_notificaciones
			WHERE opcion_notificacion = ".$opcion." AND activo = 1
			AND tipo_envio = 'destino' ".$where."
			GROUP BY opcion_notificacion
		";
		$result_correos_destino = mysql_query($query_correos_destino);
		$datos_correos_destino = mysql_fetch_array($result_correos_destino);
		$correos_destino = $datos_correos_destino["correos"];
		
		$query_correos_con_copia = "
			SELECT GROUP_CONCAT(correo) AS correos
			FROM cl_notificaciones
			WHERE opcion_notificacion = ".$opcion." AND activo = 1
			AND tipo_envio = 'con_copia' ".$where."
			GROUP BY opcion_notificacion
		";
		$result_correos_con_copia = mysql_query($query_correos_con_copia);
		$datos_correos_con_copia = mysql_fetch_array($result_correos_con_copia);
		$correos_con_copia = $datos_correos_con_copia["correos"];
		
		$query_correos_con_copia_oculta = "
			SELECT GROUP_CONCAT(correo) AS correos
			FROM cl_notificaciones
			WHERE opcion_notificacion = ".$opcion." AND activo = 1
			AND tipo_envio = 'con_copia_oculta' ".$where."
			GROUP BY opcion_notificacion
		";
		$result_correos_con_copia_oculta = mysql_query($query_correos_con_copia_oculta);
		$datos_correos_con_copia_oculta = mysql_fetch_array($result_correos_con_copia_oculta);
		$correos_con_copia_oculta = $datos_correos_con_copia_oculta["correos"];
	}
	// ***   Termina Extrae los correos de destino, con_copia y con_copia_oculta segun la opcion   *** //
	
	$subject = '';
	$link = '';
	$ruta_base_sys = 'http://201.99.107.11/sysaudinet_dev/';
	$ruta_base_red = 'http://201.99.107.11/redaudinet_dev/';
	
	$body = '
		<div style="width: 100%;">
			<div style="border: 1px solid #CCC; margin: auto; width: 70%;">
				<div style="padding-left: 15px; padding-right: 15px;">
					<p style="font-weight: bold; font-size: 14px;">Estimado (a): '.$nombre_destinatario.'</p>
					<hr style="color: #CCC;">
					<p style="padding-bottom: 17px;"></p>
	';
		
	if($opcion == 2){   // ***   Importacion de migraciones - remesas   *** //
		if($asunto != ""){ $subject = $asunto; } 
		else { $subject = 'Importación de Migraciones - Remesas.'; }
		
		if($mensaje != ""){ $body .= $mensaje; }
		else { $body .= '<p>Le informamos que se ha realizado una importación de remesas.</p>'; }
		
		$link = '<a href="'.$ruta_base_sys.'code/especiales/migracionesAsignacionFacturas.php" target="_blank">
					201.99.107.11/sysaudinetmaster
				</a>';
	} elseif($opcion == 3){   // ***   Generacion de requisicion   *** //
		if($asunto != ""){ $subject = $asunto; }
		else { $subject = 'Generación de requisición.'; }
		
		if($mensaje != ""){ $body .= $mensaje; }
		else { $body .= '<p>Le informamos que se ha generado una requisición.</p>'; }
		
		$link = '<a href="'.$ruta_base_sys.'code/especiales/requisicionPendienteAprobacion.php" target="_blank">
					201.99.107.11/sysaudinetmaster
				</a>';
	} elseif($opcion == 4){   // ***   Generacion de Orden de compra   *** //
		if($asunto != ""){ $subject = $asunto; }
		else { $subject = 'Generación de Orden de Compra.'; }
		
		if($mensaje != ""){ $body .= $mensaje; }
		else { $body .= '<p>Le informamos que se ha generado una orden de compra.</p>'; }
		
		$link = '<a href="'.$ruta_base_sys.'code/especiales/ordenDeCompraPendienteDeAprobacion.php" target="_blank">
					201.99.107.11/sysaudinetmaster
				</a>';
	} elseif($opcion == 5){   // ***   Importacion Pipeline   *** //
		if($asunto != ""){ $subject = $asunto; }
		else { $subject = 'Importacion Pipeline.'; }
		
		if($mensaje != ""){ $body .= $mensaje; }
		else { $body .= '<p>Le informamos que se ha realizado la importación del pipeline.</p>'; }
		
		$link = '<a href="'.$ruta_base_sys.'code/especiales/asignarPipeline.php" target="_blank">
					201.99.107.11/sysaudinetmaster
				</a>';
	} elseif($opcion == 6){   // ***   Migraciones - Asignacion de Facturas   *** //
		if($asunto != ""){ $subject = $asunto; }
		else { $subject = 'Migraciones - Asignacion de Facturas.'; }
		
		if($mensaje != ""){ $body .= $mensaje; }
		else { $body .= '<p>Le informamos que se han asignado facturas a migraciones.</p>';}
		
		$link = '<a href="'.$ruta_base_sys.'code/especiales/migracionesLiberaciones.php" target="_blank">
					201.99.107.11/sysaudinetmaster
				</a>';
	} elseif($opcion == 7){   // ***   Liberacion de migraciones   *** //
		if($asunto != ""){ $subject = $asunto; }
		else { $subject = 'Liberacion de Migraciones.'; }
		
		if($mensaje != ""){ $body .= $mensaje; }
		else { $body .= '<p>Le informamos que tiene migraciones por facturar.</p>'; }
		
		$link = '<a href="'.$ruta_base_red.'code/especiales/migracionesFacturacion.php" target="_blank">
					201.99.107.11/redaudinettest
				</a>';
	} elseif($opcion == 9){   // ***   Arqueo Distribuidores   *** //
		if($asunto != ""){ $subject = $asunto; }
		else { $subject = 'Arqueo Distribuidores.'; }
		
		if($mensaje != ""){ $body .= $mensaje; }
		else { $body .= '<p>Le informamos que se realizo un arqueo de distribuidores.</p>'; }
		
		$link = '<a href="'.$ruta_base_red.'code/especiales/apruebaCheques.php" target="_blank">
					201.99.107.11/redaudinettest
				</a>';
	} elseif($opcion == 10){   // ***   Aprobar o Rechazar cheques (arqueo)   *** //
		if($asunto != ""){ $subject = $asunto; }
		else { $subject = 'Aprobacion y Rechazo de Cheques (arqueo).'; }
		
		if($mensaje != ""){ $body .= $mensaje; }
		else { $body .= '<p>Aprobacion y Rechazo de Cheques (arqueo).</p>'; }
		
		$link = '<a href="'.$ruta_base_sys.'" target="_blank">
					201.99.107.11/sysaudinetmaster
				</a>';
	} elseif($opcion == 11){   // ***   Liberar Comisiones para facturar   *** //
		if($asunto != ""){ $subject = $asunto; }
		else { $subject = 'Liberacion de comisiones para facturar.'; }
		
		if($mensaje != ""){ $body .= $mensaje; }
		else { $body .= '<p>Le informamos que se han liberado comisiones para facturar.</p>'; }
		
		$link = '<a href="'.$ruta_base_red.'code/especiales/liberaciones/comisionesPendientesFacturar.php" target="_blank">
					201.99.107.11/redaudinettest
				</a>';
	} elseif($opcion == 12){   // ***   Asignacion de Pipeline   *** //
		if($asunto != ""){ $subject = $asunto; }
		else { $subject = 'Asignación de pipeline.'; }
		
		if($mensaje != ""){ $body .= $mensaje; }
		else { $body .= '<p>Le informamos que se ha realizado la asignación del pipeline.</p>'; }
		
		$link = '<a href="'.$ruta_base_sys.'" target="_blank">
					201.99.107.11/sysaudinetmaster
				</a>';
		
		$correos_destino = "vluna@gmail.com";
	} elseif($opcion == 13){   // ***   Liberacion de Penalizaciones   *** //
		if($asunto != ""){ $subject = $asunto; }
		else { $subject = 'Liberacion de Penalizaciones.'; }
		
		if($mensaje != ""){ $body .= $mensaje; }
		else { $body .= '<p>Le informamos que se han liberado penalizaciones.</p>'; }
		
		$link = '<a href="'.$ruta_base_red.'/code/especiales/liberaciones/liberaPenalizacionPorPlaza.php" target="_blank">
					201.99.107.11/redaudinettest
				</a>';
	} elseif($opcion == 14){   // ***   Liberacion de Bonos   *** //
		if($asunto != ""){ $subject = $asunto; }
		else { $subject = 'Liberacion de bonos para facturar.'; }
		
		if($mensaje != ""){ $body .= $mensaje; }
		else { $body .= '<p>Le informamos que se han liberado bonos para facturar.</p>'; }
		
		$link = '<a href="'.$ruta_base_red.'code/especiales/liberaciones/bonosPendientesFacturar.php" target="_blank">
					201.99.107.11/redaudinettest
				</a>';
	} else {
		$subject = $asunto;
		$body .= $mensaje;
	}
	
	$body_cierre = '	
					<p style="padding-top: 20px;">
						'.$link.'
					</p>
				</div>
				<div style="text-align:center; padding-top: 40px; color: #444; font-size: 8pt;">
					Copyright &copy;2015 Audicel. Todos los derechos reservados.
				</div>
			</div>
		</div>
	';
	
	$mail = new PHPMailer();
	/*
	$mail->IsSMTP();
	$mail->SMTPAuth = true;
	$mail->Mailer = "smtp";
	$mail->SMTPSecure = "ssl";
	$mail->Host = "sysandweb.com";
	$mail->Port = 465;
	$mail->From = 'smtp_sw@sysandweb.com';
	$mail->FromName = 'NOTIFICACIONES AUDINET';
	$mail->Username = "smpt_facelec2014@sysandweb.com";
	$mail->Password = "SaWeRd2015";
	
	$mail->IsHTML(true);
	$mail->Subject = $subject;
	$mail->Body = $body;
	$mail->AddEmbeddedImage("../../imagenes/header_bg.jpg", "fondo_azul");
	$mail->AddEmbeddedImage("../../imagenes/header_logo.png", "logo");
	*/
	/*
	$mail->IsSMTP();
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = "tls";
	$mail->Host = "smtp.live.com";
	$mail->Port = 587;
	$mail->From = 'correo@hotmail.com';
	$mail->FromName = 'NOTIFICACIONES AUDINET';
	$mail->Username = "correo@hotmail.com";
	$mail->Password = "passwd";
	*/
	$mail->IsSMTP();
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = "tls";
	$mail->Host = "mail.audicel.com.mx";
	$mail->Port = 2525;
	$mail->From = 'audicelfact@audicel.com.mx';
	$mail->FromName = 'NOTIFICACIONES AUDINET';
	$mail->Username = "audicelfact@audicel.com.mx";
	$mail->Password = "facturas12";
	
	$mail->IsHTML(true);
	//$mail->Subject = '=?UTF-8?B?'.base64_encode($subject).'?=';
	$mail->Subject = mb_convert_encoding($subject,"ISO-8859-1","UTF-8");
	$mail->Body = $body . $body_cierre;
	//$mail->AddEmbeddedImage("../../imagenes/header_logo.png", "logo");
	//$mail->SMTPDebug = 2;
	$tiene_correo_destino = "no";
	
	if($correos_destino != ''){
		$arr_correos_destino = explode(",", $correos_destino);
		for($i = 0; $i < count($arr_correos_destino); $i++){
			$mail -> AddAddress($arr_correos_destino[$i]);
			$tiene_correo_destino = "si";
		}
	}
	
	if($correos_con_copia != ""){
		$arr_correos_con_copia = explode(",", $correos_con_copia);
		for($i = 0; $i < count($arr_correos_con_copia); $i++){
			$mail -> AddCC($arr_correos_con_copia[$i]);
		}
	}
	
	if($correos_con_copia_oculta != ""){
		$arr_correos_con_copia_oculta = explode(",", $correos_con_copia_oculta);
		for($i = 0; $i < count($arr_correos_con_copia_oculta); $i++){
			$mail -> AddBCC($arr_correos_con_copia_oculta[$i]);
		}
	}
	
	if ($archivos_adjuntos != "") {
		$arr_archivos_adjuntos = explode(",", $archivos_adjuntos);
		for($i = 0; $i < count($arr_archivos_adjuntos); $i++){
			$mail -> AddAttachment($arr_archivos_adjuntos[$i]);
		}
	}
	
	if($tiene_correo_destino == "si"){
		$exito = $mail->Send();
		
		if(!$exito){ return $mail->ErrorInfo; }
	} else {
		return "No se agrego un correo de destino.";
	}
	unset($mail);
}
?>