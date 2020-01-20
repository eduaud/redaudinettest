<?php
class PDF extends FPDF{
	function Header(){
		Global $accion;
		Global $sucursal;
		Global $folio;
		Global $fecha_pedido;
		Global $tipo_pago;
		
		Global $clave_cliente;
		Global $logo;
		Global $foliocfdi;
		Global $certificado;
		Global $lugar_fecha_certificacion;
		Global $cliente;
		Global $direccion_1;
		Global $direccion_2;
		Global $direccion_3;
		Global $rfc_r;
		Global $serie;
		Global $folio_fiscal;
		Global $regimen;
		Global $compania;
		Global $observaciones;
		
		$this -> SetLineWidth(0.4);
		$this -> SetDrawColor(0,38,89);
		$this -> SetFillColor(0,38,89);
		$this -> SetTextColor(255);
		$this -> SetFont('Arial','B',7);
		$this -> Cell(65,5,'Folio Fiscal',1,0,'C',1);
		$this -> Image($logo,85,10,45, 20,'PNG');
		//$this -> Image($logo,88,9.5);
		$this -> Cell(65);
		$this -> Cell(65,5,'FACTURA',1,1,'C',1);
		$this -> SetTextColor(0);
		$this -> Cell(65,5,$foliocfdi,1,0,'C',0);
		$this -> Cell(65);
		$this -> SetTextColor(255);
		$this -> Cell(65,5,utf8_decode('Lugar y Fecha de certificación'),1,1,'C',1);
		$this -> SetTextColor(0);
		$this -> Cell(32.5,7,'Serie: '.$serie,1,0,'C',0);
		$this -> Cell(32.5,7,'Folio: '.$folio_fiscal,1,0,'C',0);
		$this -> Cell(65);
		$this -> Cell(65,7,utf8_decode($lugar_fecha_certificacion),1,1,'C',0);
		$this -> SetTextColor(255);
		$this -> Cell(65,5,utf8_decode('Número de Certificado del Emisor'),1,0,'C',1);
		$this -> SetTextColor(0);
		$this -> Cell(65,5,'',0,0,'C',0);
		$this -> SetTextColor(255);
		$this -> Cell(65,5,utf8_decode('Proveedor de Certificación de CFDI'),1,1,'C',1);
		$this -> SetTextColor(0);
		$this -> Cell(65,5,$certificado,1,0,'C',0);
		$this -> SetFont('Arial','B',6.5);
		$this -> Cell(65,5,$regimen,0,0,'C',0);
		$this -> SetFont('Arial','B',7);
		$this -> Cell(65,5,'AURORIAN S.A. DE C.V.','LTR',1,'C',0);
		$this -> SetTextColor(255);
		$this -> Cell(65,5,utf8_decode('Lugar y Fecha de Emisión'),1,0,'C',1);
		$this -> SetTextColor(0);
		$this -> Cell(65,5,$compania,0,0,'C',0);
		$this -> Cell(65,5,'RFC: AUR100128NN3','LR',1,'C',0);
		$this -> Cell(65,5,utf8_decode($lugar_fecha_certificacion),1,0,'C',0);
		$this -> Cell(65);
		$this -> SetTextColor(0);
		$this -> Cell(65,5,utf8_decode('Número Certificado del SAT: 00001000000202809550'),'LRB',1,'C',0);
		$this -> SetTextColor(255);
		$this -> Cell(65,5,'Nombre del Cliente',1,0,'C',1);
		$this -> Cell(30,5,'Clave del Cliente',1,0,'C',1);
		$this -> Cell(5);
		$this -> Cell(95,5,utf8_decode('Dirección del Cliente'),1,1,'C',1);
		$this -> SetTextColor(0);
		$this -> SetFont('Arial','B',6);
		$this -> Cell(65,5,utf8_decode($cliente),1,0,'C',0);
		$this -> SetFont('Arial','B',7);
		$this -> Cell(30,5,utf8_decode($clave_cliente),1,0,'C',0);
		$this -> Cell(5);
		$this -> Cell(95,5,utf8_decode($direccion_1),'LTR',1,'L',0);
		$this -> Cell(95,5,'',0,0,'L',0);
		$this -> Cell(5);
		$this -> Cell(95,5,utf8_decode($direccion_2),'LR',1,'L',0);
		$this -> SetTextColor(255);
		$this -> Cell(95,5,'RFC del Cliente',1,0,'C',1);
		$this -> Cell(5);
		$this -> SetTextColor(0);
		$this -> Cell(95,5,$direccion_3,'LR',1,'L',0);
		$this -> Cell(95,5,$rfc_r,1,0,'C',0);
		$this -> Cell(5);
		$this -> Cell(95,5,'','LBR',1,'L',0);
		if($observaciones != ''){
			$this -> MultiCell(195,5,'Observaciones Generales: '.utf8_decode($observaciones),'LTBR',1,'L',0);
		}
		$this -> Cell(195,5,'',0,1,'L',0);
	}		
	function Footer(){
		$this -> SetY(265);
		$this -> Cell(0,5,utf8_decode('Este documento es una representación impresa de un CFDI'),0,0,'L');
		$this -> SetY(265);
		$this->SetFont('Arial','I',8);
		$this->Cell(0,5,utf8_decode('Página ').$this->PageNo().' de {nb}',0,0,'C');
	}
	function footerGeneral(){
		$this -> SetY(177);
		Global $certificado_digital;
		Global $sello;
		Global $cadena_original;
		Global $total;
		Global $subtotal;
		Global $iva;
		Global $img_qr;
		Global $moneda;
		Global $direccionCompania;
		Global $direccionCompania1;
		Global $direccionCompania2;
		
		Global $direccion_sucursal;
		Global $direccion_sucursal1;
		Global $direccion_sucursal2;
		Global $direccion_2;
		Global $direccion_3;
		Global $sucursal;
		Global $telefonos;
		
		Global $metodo_pago;
		Global $cuenta;
                
                Global $descripcion_forma_pago;
                
                Global $nombreMetodoPago;
                Global $claveMetodoPago;
		
		$this -> SetLineWidth(0.4);
		$this -> SetDrawColor(0,38,89);
		$this -> SetFillColor(0,38,89);
		$this -> SetTextColor(255);
		$this -> SetFont('Arial','B',7);
		
		$this -> Cell(195,5,'IMPORTE EN LETRAS',1,1,'L',1);
		$this -> SetTextColor(0);
		if($moneda == '1')
			$letra = num2texto(str_replace(',','',$total), 'PESOS', 'PESO');
		else
			$letra = num2texto(str_replace(',','',$total), 'DOLARES', 'DOLAR');
		$this -> Cell(195,5,$letra,1,1,'L',0);
		$this -> SetTextColor(255);
		$this -> Cell(195,5,'SELLO DIGITAL DEL EMISOR',1,1,'L',1);
		$this -> SetTextColor(0);
		$this -> SetFont('Arial','B',5);
		$this -> MultiCell(195,3.5,$certificado_digital,1,1,'L',0);
		$this -> SetTextColor(255);
		$this -> SetFont('Arial','B',7);
		$this -> Cell(195,5,'SELLO DIGITAL DEL SAT',1,1,'L',1);
		$this -> SetTextColor(0);
		$this -> SetFont('Arial','B',5);
		$this -> MultiCell(195,3.5,$sello,1,1,'L',0);
		$this -> SetTextColor(255);
		$this -> SetFont('Arial','B',7);
		$this -> Cell(195,5,'CADENA ORIGINAL DEL COMPLEMENTO DE CERTIFICACION DIGITAL DEL SAT',1,1,'L',1);
		$this -> SetTextColor(0);
		$this -> SetFont('Arial','B',5);
		$this -> MultiCell(195,3.5,$cadena_original,1,1,'L',0);
		$this -> SetFont('Arial','B',7);
		$this -> Cell(195,2,'',0,1,'L',0);
		$this -> Cell(30,5,'',0,0,'C',0);
		$this -> SetFont('Arial','B',5.5);
		$this -> Cell(50,5,'Matriz:',0,0,'C',0);
		$this -> Cell(50,5,$sucursal.':',0,0,'C',0);
		/*$this -> Cell(50,5,$direccionCompania,1,0,'L',0);
		*/
		$this -> SetFont('Arial','B',7);
		$this -> SetTextColor(255);
		$this -> Cell(35,5,'SUB-TOTAL',1,0,'L',1);
		$this -> Image($img_qr,10,$this-> GetY(),27);
		$this -> SetTextColor(0);
		$this -> Cell(30,5,'$ '.$subtotal,1,1,'R',0);
		$this -> Cell(30,5,'',0,0,'C',0);
		$this -> SetFont('Arial','B',5);
		$this -> Cell(50,5,$direccionCompania,0,0,'C',0);
		$this -> Cell(50,5,$direccion_sucursal,0,0,'C',0);
		$this -> SetTextColor(255);
		$this -> SetFont('Arial','B',7);
		$this -> Cell(35,5,'IVA',1,0,'L',1);
		$this -> SetTextColor(0);
		$this -> Cell(30,5,'$ '.$iva,1,1,'R',0);
		$this -> Cell(30,5,'',0,0,'C',0);
		$this -> SetFont('Arial','B',5);
		$this -> Cell(50,5,$direccionCompania1,0,0,'C',0);
		$this -> Cell(50,5,$direccion_sucursal1,0,0,'C',0);
		$this -> SetTextColor(255);
		$this -> SetFont('Arial','B',7);
		$this -> Cell(35,5,'TOTAL',1,0,'L',1);
		$this -> SetTextColor(0);
		$this -> Cell(30,5,'$ '.$total,1,1,'R',0);
		$this -> Cell(30,5,'',0,0,'C',0);
		$this -> SetFont('Arial','B',5);
		$this -> Cell(50,5,$direccionCompania2,0,0,'C',0);
		$this -> Cell(50,5,$direccion_sucursal2,0,0,'C',0);
		$this -> SetFont('Arial','B',6);
		$this -> Cell(35,5,'Forma de Pago: ',0,0,'L',0);
//		$this -> Cell(30,5,utf8_decode('PAGO EN UNA SOLA EXHIBICION'),0,1,'R',0);
                $this -> Cell(30,5,$metodo_pago . ' - ' . $descripcion_forma_pago,0,1,'R',0);
		$this -> Cell(30,5,'',0,0,'C',0);
		$this -> SetFont('Arial','B',5);
		$this -> Cell(50,5,utf8_decode('Tel. 5639 3335 con 12 líneas'),0,0,'C',0);
		$this -> Cell(50,5,'Tels. '.$telefonos,0,0,'C',0);
		$this -> SetFont('Arial','B',6);
		$this -> Cell(35,5,utf8_decode('Método de Pago: '),0,0,'L',0);
//		$this -> Cell(30,5,utf8_decode($metodo_pago),0,1,'R',0);
                $this -> Cell(30,5,$claveMetodoPago . ' - ' . $nombreMetodoPago, 0, 1, 'R', 0);
		$this -> Cell(30,5,'',0,0,'C',0);
		$this -> SetFont('Arial','B',5);
		$this -> Cell(50,5,utf8_decode('Fax: 5639 3750'),0,0,'C',0);
		$this -> Cell(50,5,'',0,0,'C',0);
		$this -> SetFont('Arial','B',6);
		$this -> Cell(35,5,utf8_decode('Número Cuenta Pago:'),0,0,'L',0);
		$this -> Cell(30,5,utf8_decode($cuenta),0,1,'R',0);		
	}
	function cabeceraTabla(){
		$this -> SetLineWidth(0.4);
		$this -> SetDrawColor(0,38,89);
		$this -> SetFillColor(0,38,89);
		$this -> SetTextColor(255);
		$this -> SetFont('Arial','B',7);
		
		$this -> Cell(20,10, 'CANT./UNIDAD',1,0,'C',1);
		$this -> Cell(119,10, utf8_decode('CONCEPTO'),'TBR',0,'C',1);
		$this -> Cell(26,10, 'P UNITARIO','TBR',0,'C',1);
		$this -> Cell(30,10, 'IMPORTE','TBR',1,'C',1);
	}
	function datosProductos($producto,$factura){
		$this -> SetLineWidth(0.4);
		$this -> SetDrawColor(0,38,89);
		$this -> cabeceraTabla();
		$this -> SetTextColor(0);
		$this -> SetFillColor(0,38,89);
		$inicialY = $this -> GetY();
		//die($inicialY);
		$this -> SetXY(10, $inicialY);
		foreach($producto as $datos){
		$sqlDetalle = "
			SELECT SUM(ad_facturas_audicel_detalle.cantidad) as cantidad, scfdi_unidades.nombre AS unidad, CONCAT(cl_productos_servicios.nombre,IF(ad_facturas_audicel_detalle.descripcion IS NOT NULL AND ad_facturas_audicel_detalle.descripcion <> '',CONCAT(' / ',GROUP_CONCAT(DISTINCT ad_facturas_audicel_detalle.descripcion)),'')) AS nombre, ad_facturas_audicel_detalle.descripcion, FORMAT(ad_facturas_audicel_detalle.valor_unitario,2) AS valor_unitario, FORMAT(SUM(ad_facturas_audicel_detalle.valor_unitario*ad_facturas_audicel_detalle.cantidad),2) as importe 
			FROM ad_facturas_audicel_detalle
			LEFT JOIN ad_facturas_audicel ON ad_facturas_audicel_detalle.id_control_factura = ad_facturas_audicel.id_control_factura
			LEFT JOIN cl_productos_servicios ON ad_facturas_audicel_detalle.id_producto = cl_productos_servicios.id_producto_servicio 
			LEFT JOIN cl_clasificacion_productos ON cl_clasificacion_productos.id_clasificacion_producto = cl_productos_servicios.id_producto_servicio 
			LEFT JOIN scfdi_unidades ON scfdi_unidades.id_unidad = cl_clasificacion_productos.id_unidad 
			WHERE ad_facturas_audicel_detalle.id_control_factura = ".$factura." AND id_producto=".$datos -> id_producto ." AND valor_unitario='".$datos -> valor_unitario."'".$condicion;
			$ConsultaProductos = new consultarTabla($sqlDetalle);
			$datosProducto = $ConsultaProductos->obtenerRegistros();	
			$contratoFactura = array();
		$sqlDetalleContratos = "
			SELECT
				IF(cl_control_penalizaciones.observaciones_detalle IS NULL,'',cl_control_penalizaciones.observaciones_detalle) as contrato
			FROM ad_facturas_audicel_detalle
			LEFT JOIN ad_facturas_audicel ON ad_facturas_audicel_detalle.id_control_factura = ad_facturas_audicel.id_control_factura
			LEFT JOIN cl_control_penalizaciones_detalle ON ad_facturas_audicel_detalle.id_detalle_control_penalizaciones = cl_control_penalizaciones_detalle.id_control_penalizacion_detalle
			LEFT JOIN cl_control_penalizaciones ON cl_control_penalizaciones_detalle.id_control_penalizacion = cl_control_penalizaciones.id_control_penalizacion
			WHERE ad_facturas_audicel_detalle.id_control_factura =".$factura." AND ad_facturas_audicel_detalle.id_detalle_control_penalizaciones IN(".$datos -> id_detalle_control_penalizaciones.") AND id_producto=".$datos -> id_producto ." AND valor_unitario='".$datos -> valor_unitario."'".$condicion;
		//die($sqlDetalleContratos);
		$contratos = array();
		$ConsultaContratos = new consultarTabla($sqlDetalleContratos);
		$Contratos = $ConsultaContratos->obtenerRegistros();
		$x = 0;
		foreach($Contratos as $contrato){
			if($contrato -> contrato != ''){
				array_push($contratoFactura,$contrato -> contrato);
				$x += 1;
			}
		}
		foreach($datosProducto as $datosProducto){

		$this->SetFont('Arial','',6); 
		if($inicialY > 250){	
			$Yfinal = $this-> GetY();
			$dibuja = 255;
			
			$resto = $dibuja - $Yfinal;
			$this -> SetLineWidth(0.4);
			$this -> SetDrawColor(0,38,89);

			$this->Cell(20,$resto, '','LBR',0,'L');
			$this->Cell(119,$resto, '','LBR',0,'L');
			$this->Cell(26,$resto, '','LBR',0,'L');
			$this->Cell(30,$resto, '','LBR',1,'L');

			$this->AddPage();
			$this -> SetLineWidth(0.4);
			$this -> SetDrawColor(0,38,89);
			$this -> cabeceraTabla();
			$inicialY = 87.00125; 
		}
		$linea = 0;
		$longitudNombre = strlen($datosProducto -> nombre);
		$tamaño_linea = $longitudNombre / 100;
		$linea_entero = intval($tamaño_linea);
		if($linea_entero < $tamaño_linea){
			$linea = $linea_entero + 2;
		}else{
			$linea = $linea_entero;
		}
		$this -> SetTextColor(0);
		$this->SetFont('Arial','',5.5);
		$this->SetXY(10, $inicialY);
		$this->Cell(20,(5 * $linea), $datosProducto -> cantidad,'LR',0,'C');

		$this->SetXY(30,$inicialY);
		$this->MultiCell(119,5,$datosProducto -> nombre,'RL','C');
		$this->SetXY(117,$inicialY);


		$this->SetXY(149, $inicialY);
		$this->Cell(26,(5 * $linea), $datosProducto -> valor_unitario,'LR',0,'C');

		$this->SetXY(175, $inicialY);
		$this->Cell(30,(5 * $linea), $datosProducto -> importe,'R',1,'C');
		//print_r($contratoFactura);
		for($i = 0; $i < $x; $i++){
			$inicialY = $this-> GetY();
			if($inicialY > 250){	
				$Yfinal = $this-> GetY();
				//die($Yfinal);
				$dibuja = 255;
				$resto = $dibuja - $Yfinal;
				$this -> SetLineWidth(0.4);
				$this -> SetDrawColor(0,38,89);
				
				$this->Cell(20,$resto, '','LBR',0,'L',0);
				$this->Cell(119,$resto, '','LBR',0,'L');
				$this->Cell(26,$resto, '','LBR',0,'L');
				$this->Cell(30,$resto, '','LBR',1,'L');
				
				$this->AddPage();
				$this -> SetLineWidth(0.4);
				$this -> SetDrawColor(0,38,89);
				$this -> cabeceraTabla();
				$inicialY = 87.00125; 
			}
			$contratos = "";
			for($j = 0; $j < 3; $j++){
				if($j + $i < $x){
					if($contratos == "")
						$contratos = $contratoFactura[$j + $i];
					else
						$contratos .= ",".$contratoFactura[$j + $i];
				}
			}
				
			
			$linea1 = 0;
			$longitudNombre1 = strlen($contratos);
			$tamaño_linea1 = $longitudNombre1 / 100;
			$linea_entero1 = intval($tamaño_linea1);
			if($linea_entero1 < $tamaño_linea1){
				$linea1 = $linea_entero1 + 1;
			}else{
				$linea1 = $linea_entero1;
			}//echo 'x:'.$linea1.'<br>';
			$this -> SetTextColor(0);
			$this->SetFont('Arial','',5.5);
			$this->SetXY(10, $inicialY);
			$this->Cell(20,(5 * $linea1),'','RL',0,'C');
			$this->SetXY(30,$inicialY);
			
			$this->MultiCell(119,5,$contratos,'RL','C');

			$this->SetXY(117,$inicialY);
			$this->SetXY(149, $inicialY);
			$this->Cell(26,(5 * $linea1), '','RL',0,'C');
			$this->SetXY(175, $inicialY);
			$this->Cell(30,(5 * $linea1), '','RL',1,'C');

			$i += 2;
		}



		$actualY = $this-> GetY();  //Obtenemos el valor de Y despues de la multicelda ya que este tendra el valor donde iniciara la siguiente fila
		$nuevaY = $actualY - $inicialY; // Hacemos la convercion para obtener la suma de la siugiente posicion
		$inicialY +=  $nuevaY;		// Le asignamos a Y inicial el nuevo valor de la siguiente fila
		}
	}
	$Yfinal = $this-> GetY();
	if($Yfinal >= 170){
		$dibuja = 255;
		$resto = $dibuja - $Yfinal;
		$this -> SetLineWidth(0.4);
		$this -> SetDrawColor(0,38,89);

		$this->Cell(20,$resto, '','LBR',0,'L');
		$this->Cell(119,$resto, '','LBR',0,'L');
		$this->Cell(26,$resto, '','LBR',0,'L');
		$this->Cell(30,$resto, '','LBR',1,'L');
	}
	else{
		$dibuja = 175;
		$resto = $dibuja - $Yfinal;
		$this->Cell(20,$resto, '','LBR',0,'L');
		$this->Cell(119,$resto, '','LBR',0,'L');
		$this->Cell(26,$resto, '','LBR',0,'L');
		$this->Cell(30,$resto, '','LBR',0,'L');
	}
	if($Yfinal >= 175){
		$this->AddPage();
		//$this -> cabeceraTabla();	
	}

		}
}
?>