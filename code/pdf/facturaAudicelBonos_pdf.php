<?php

class PDF extends FPDF{
	function Header(){
			Global $accion;
			Global $sucursal;
			Global $folio;
			Global $fecha_pedido;
			Global $cuenta;
			Global $metodo_pago;
			
			Global $logo;
			Global $foliocfdi;
			Global $certificado;
			Global $lugar_fecha_certificacion;
			Global $cliente;
			Global $direccion_1;
			Global $direccion_2;
			Global $direccion_3;
			Global $direccion_1_c;
			Global $direccion_2_c;
			Global $direccion_3_c;
			Global $rfc_r;
			Global $serie;
			Global $folio_fiscal;
			Global $regimen;
			Global $compania;
			Global $rfc_compania;
			
			$this -> SetLineWidth(0.4);
			$this -> SetDrawColor(24,207,157);
			$this -> SetFillColor(24,207,157);
			$this -> SetTextColor(0);
			$this -> SetFont('Arial','B',11);
			$this -> Cell(130,5,$compania,0,0,'L',0);
			$this -> SetFont('Arial','B',7);
			$this -> Cell(65,5,"FACTURA",'LRT',1,'C',1);
			$this -> SetFont('Arial','B',6.5);
			$this -> Cell(130,5,utf8_decode($direccion_1_c.' '.$direccion_2_c.' '.$direccion_3_c),0,0,'L',0);
			$this -> SetFont('Arial','B',7);
			$this -> Cell(32.5,5,"Serie: ".$serie,'LBR',0,'C',0);
			$this -> Cell(32.5,5,"Folio: ".$folio_fiscal,'LBR',1,'C',0);
			$this -> Cell(130,5,$rfc_compania,0,0,'L',0);
			$this -> Cell(65,5,utf8_decode("Lugar y Fecha de certificación"),'LR',1,'C',0);
			$this -> Cell(130,5,$regimen,0,0,'L',0);
			$this -> MultiCell(65,5,$lugar_fecha_certificacion,'LBR',1,'C',0);
			//$this -> Cell(65,5,$lugar_fecha_certificacion,'LBR',1,'C',0);
			$this -> Cell(130);
			$this -> Cell(65,5,utf8_decode("Lugar y Fecha de emisión"),'LR',1,'C',0);
			$this -> Cell(130,5,'','B',0,0,0);
			$this -> MultiCell(65,5,$lugar_fecha_certificacion,'LBR',1,'C',0);
			//$this -> Cell(65,5,$lugar_fecha_certificacion,'LBR',1,'C',0);
			$this -> SetFont('Arial','B',11);
			$this -> Cell(195,5,$cliente,0,1,'L',0);
			$this -> SetFont('Arial','B',7);
			$this -> Cell(195,5,$direccion_1.' '.$direccion_2,0,1,'L',0);
			$this -> Cell(195,5,$direccion_3,0,1,'L',0);
			$this -> Cell(195,5,'RFC: '.$rfc_r,0,1,'L',0);
			$this -> Cell(97.5,5,utf8_decode('MÉTODO DE PAGO: '.$metodo_pago),0,0,'LT',0);
			$this -> Cell(97.5,5,utf8_decode('NÚMERO CUENTA PAGO: '.$cuenta),0,1,'LT',0);
			$this -> Cell(65,5,utf8_decode('Folio Fiscal'),'LBTR',0,'C',1);
			$this -> Cell(65,5,utf8_decode('Número de Certificado del Emisor'),'RBT',0,'C',1);
			$this -> Cell(65,5,utf8_decode('NÚMERO CERTIFICADO DEL SAT'),'RBT',1,'C',1);
			$this -> Cell(65,5,$foliocfdi,'LBR',0,'C',0);
			$this -> Cell(65,5,$certificado,'RB',0,'C',0);
			$this -> Cell(65,5,'00001000000202809550','RB',1,'C',0);
			$this -> Cell(195,5,'',0,1,0,0);
	}
	function Footer(){
		$this -> SetY(260);
		$this->Cell(0,5,utf8_decode('Este documento es una representación impresa de un CFDI'),0,0,'R');
		$this -> SetY(265);
		$this->SetFont('Arial','I',8);
		$this->Cell(0,5,utf8_decode('Página ').$this->PageNo().' de {nb}',0,0,'C');
	}
	function footerGeneral(){
		$this -> SetY(180);
		Global $certificado_digital;
		Global $sello;
		Global $cadena_original;
		Global $total;
		Global $subtotal;
		Global $iva;
		Global $img_qr;
		Global $moneda;
		
		$this -> SetLineWidth(0.4);
		$this -> SetDrawColor(24,207,157);
		$this -> SetFillColor(24,207,157);
		$this -> SetTextColor(0);
		$this -> SetFont('Arial','B',7);
		
		$this -> Cell(195,5,'IMPORTE EN LETRAS',1,1,'L',1);
		$this -> SetTextColor(0);
		if($moneda == '1')
			$letra = num2texto(str_replace(',','',$total), 'PESOS', 'PESO');
		else
			$letra = num2texto(str_replace(',','',$total), 'DOLARES', 'DOLAR');
		$this -> Cell(195,5,$letra,1,1,'L',0);
		$this -> SetTextColor(0);
		$this -> Cell(195,5,'SELLO DIGITAL DEL EMISOR',1,1,'L',1);
		$this -> SetTextColor(0);
		$this -> SetFont('Arial','B',5);
		$this -> MultiCell(195,4,$certificado_digital,1,1,'L',0);
		$this -> SetTextColor(0);
		$this -> SetFont('Arial','B',7);
		$this -> Cell(195,5,'SELLO DIGITAL DEL SAT',1,1,'L',1);
		$this -> SetTextColor(0);
		$this -> SetFont('Arial','B',5);
		$this -> MultiCell(195,4,$sello,1,1,'L',0);
		$this -> SetTextColor(0);
		$this -> SetFont('Arial','B',7);
		$this -> Cell(195,5,'CADENA ORIGINAL DEL COMPLEMENTO DE CERTIFICACION DIGITAL DEL SAT',1,1,'L',1);
		$this -> SetTextColor(0);
		$this -> SetFont('Arial','B',5);
		$this -> MultiCell(195,4,$cadena_original,1,1,'L',0);
		$this -> SetTextColor(0);
		$this -> SetFont('Arial','B',7);
		$this -> Cell(195,3,'',0,1,'L',0);
		$this->Image($img_qr,10,$this -> GetY(),30);
		$this -> Cell(130);
		$this -> Cell(35,5,'SUB-TOTAL',1,0,'L',1);
		$this -> SetTextColor(0);
		$this -> Cell(30,5,'$ '.$subtotal,1,1,'R',0);
		$this -> Cell(130);
		$this -> SetTextColor(0);
		$this -> Cell(35,5,'IVA',1,0,'L',1);
		$this -> SetTextColor(0);
		$this -> Cell(30,5,'$ '.$iva,1,1,'R',0);
		$this -> Cell(130);
		$this -> SetTextColor(0);
		$this -> Cell(35,5,'TOTAL',1,0,'L',1);
		$this -> SetTextColor(0);
		$this -> Cell(30,5,'$ '.$total,1,1,'R',0);
		$this -> Cell(130);
	}		
	function cabeceraTabla(){
			$this -> SetLineWidth(0.4);
			$this -> SetDrawColor(24,207,157);
			$this -> SetFillColor(24,207,157);
			$this -> SetTextColor(0);
			$this->SetFont('Arial','B',7);
			
			$this->Cell(20,10, 'CANTIDAD',1,0,'C',1);
			$this->Cell(119,10, utf8_decode('CONCEPTO'),'TBR',0,'C',1);
			$this->Cell(25,10, 'P UNITARIO','TBR',0,'C',1);
			$this->Cell(31,10, 'IMPORTE','TBR',1,'C',1);
		}
	function datosProductos($producto,$factura){
	$this -> SetLineWidth(0.4);
	$this -> SetDrawColor(24,207,157);
	$this -> cabeceraTabla();
	$this -> SetTextColor(0);
	$this -> SetFillColor(24,207,157);
	$inicialY = $this -> GetY();
	
	$this -> SetXY(10, $inicialY);
	$sqlAccion = "
		SELECT id_accion_contrato
		FROM cl_control_contratos_detalles
		WHERE id_control_factura_distribuidor = ".$factura." GROUP BY id_accion_contrato";
	$arrResultado = valBuscador($sqlAccion);
	foreach($producto as $datos){
		$condicion = "";
		if($arrResultado[0] == '7000'){
			$condicion = " AND cl_control_contratos_detalles.ultimo_movimiento = '1' AND cl_control_contratos_detalles.evaluacion_en_braket = 'E'";
		}
		$sqlDetalle = "
			SELECT SUM(ad_facturas_detalle.cantidad) as cantidad, scfdi_unidades.nombre AS unidad, cl_productos_servicios.nombre, ad_facturas_detalle.descripcion, FORMAT(ad_facturas_detalle.valor_unitario,2) AS valor_unitario, FORMAT(SUM(ad_facturas_detalle.valor_unitario*ad_facturas_detalle.cantidad),2) as importe,id_accion_contrato 
			FROM ad_facturas_detalle
			LEFT JOIN ad_facturas ON ad_facturas_detalle.id_control_factura = ad_facturas.id_control_factura
			LEFT JOIN cl_control_contratos_detalles ON ad_facturas_detalle.id_control_factura_detalle = cl_control_contratos_detalles.id_control_factura_detalle_distribuidor 
			LEFT JOIN cl_control_contratos ON cl_control_contratos_detalles.id_control_contrato = cl_control_contratos.id_control_contrato 
			LEFT JOIN cl_productos_servicios ON ad_facturas_detalle.id_producto = cl_productos_servicios.id_producto_servicio 
			LEFT JOIN cl_clasificacion_productos ON cl_clasificacion_productos.id_clasificacion_producto = cl_productos_servicios.id_producto_servicio 
			LEFT JOIN scfdi_unidades ON scfdi_unidades.id_unidad = cl_clasificacion_productos.id_unidad 
			WHERE ad_facturas_detalle.id_control_factura = ".$factura." AND id_producto=".$datos -> id_producto ." AND valor_unitario='".$datos -> valor_unitario."'".$condicion;
		$ConsultaProductos = new consultarTabla($sqlDetalle);
		$datosProducto = $ConsultaProductos->obtenerRegistros();	
		$contratoFactura = array();

		$sqlDetalleContratos = "
			SELECT
			cl_control_contratos.contrato
			FROM ad_facturas_detalle
			LEFT JOIN ad_facturas ON ad_facturas_detalle.id_control_factura = ad_facturas.id_control_factura
			LEFT JOIN cl_control_contratos_detalles ON ad_facturas_detalle.ids_detalle_control_contrato = cl_control_contratos_detalles.id_detalle 
			LEFT JOIN cl_control_contratos ON cl_control_contratos_detalles.id_control_contrato = cl_control_contratos.id_control_contrato
			WHERE ad_facturas_detalle.id_control_factura =".$factura." AND ad_facturas_detalle.ids_detalle_control_contrato IN(".$datos -> ids_detalle_control_contrato.") AND id_producto=".$datos -> id_producto ." AND valor_unitario='".$datos -> valor_unitario."'".$condicion.' GROUP BY cl_control_contratos.contrato';
		$contratos = array();
		$ConsultaContratos = new consultarTabla($sqlDetalleContratos);
		$Contratos = $ConsultaContratos->obtenerRegistros();
		$x = 0;
		foreach($Contratos as $contrato){
		array_push($contratoFactura,$contrato -> contrato);
		$x += 1;
		}
		foreach($datosProducto as $datosProducto){

		$this->SetFont('Arial','',6); 
		if($inicialY > 170){	
		$Yfinal = $this-> GetY();
		if($Yfinal > 170)
			$dibuja = 250;
		else
			$dibuja = 175;
		$resto = $dibuja - $Yfinal;
		//die("X".$resto);
		$this -> SetLineWidth(0.4);
		$this -> SetDrawColor(24,207,157);

		$this->Cell(20,$resto, '','LBR',0,'L');
		$this->Cell(119,$resto, '','LBR',0,'L');
		$this->Cell(26,$resto, '','LBR',0,'L');
		$this->Cell(30,$resto, '','LBR',1,'L');

		$this->AddPage();
		$this -> SetLineWidth(0.4);
		$this -> SetDrawColor(24,207,157);
		$this -> cabeceraTabla();
		$inicialY = 90; 
		}
		$this -> SetTextColor(0);
		$this->SetFont('Arial','',6);
		$this->SetXY(10, $inicialY);
		$this->Cell(20,5, $datosProducto -> cantidad,'LR',0,'C');

		$this->SetXY(30,$inicialY);
		$this->MultiCell(119,5,$datosProducto -> nombre,'R','C');
		$this->SetXY(117,$inicialY);


		$this->SetXY(150, $inicialY);
		$this->Cell(25,5, $datosProducto -> valor_unitario,'R',0,'C');

		$this->SetXY(175, $inicialY);
		$this->Cell(30,5, $datosProducto -> importe,'R',1,'C');
		for($i = 0; $i < $x; $i++){
		$inicialY = $this-> GetY();
		if($inicialY > 250){	
			$Yfinal = $this-> GetY();
			$dibuja = 250;
			$resto = $dibuja - $Yfinal;
			$this -> SetLineWidth(0.4);
			$this -> SetDrawColor(24,207,157);
			
			$this->Cell(20,$resto, '','LBR',0,'L');
			$this->Cell(119,$resto, '','LBR',0,'L');
			$this->Cell(26,$resto, '','LBR',0,'L');
			$this->Cell(30,$resto, '','LBR',1,'L');
			
			$this->AddPage();
			$this -> SetLineWidth(0.4);
			$this -> SetDrawColor(24,207,157);
			$this -> cabeceraTabla();
			$inicialY = 90; 
		}
		$this -> SetTextColor(0);
		$this->SetFont('Arial','',6);
		$this->SetXY(10, $inicialY);
		$this->Cell(20,5,'','LR',0,'C');
		$this->SetXY(30,$inicialY);
		$contratos = "";
		for($j = 0; $j < 9; $j++){
			if($j + $i < $x){
				if($contratos == "")
					$contratos = $contratoFactura[$j + $i];
				else
					$contratos .= ",".$contratoFactura[$j + $i];
			}
		}
			
			
		$this->MultiCell(119,5,$contratos,'R','C');

		$this->SetXY(117,$inicialY);
		$this->SetXY(150, $inicialY);
		$this->Cell(25,5, '','R',0,'C');
		$this->SetXY(175, $inicialY);
		$this->Cell(30,5, '','R',1,'C');

		$i += 8;
		}



		$actualY = $this-> GetY();  //Obtenemos el valor de Y despues de la multicelda ya que este tendra el valor donde iniciara la siguiente fila
		$nuevaY = $actualY - $inicialY; // Hacemos la convercion para obtener la suma de la siugiente posicion
		$inicialY +=  $nuevaY;		// Le asignamos a Y inicial el nuevo valor de la siguiente fila
		}
	}
	$Yfinal = $this-> GetY();
	if($Yfinal >= 170)
	$dibuja = 175;
	else
	$dibuja = 175;

	$resto = $dibuja - $Yfinal;
	$this->Cell(20,$resto, '','LBR',0,'L');
	$this->Cell(119,$resto, '','LBR',0,'L');
	$this->Cell(26,$resto, '','LBR',0,'L');
	$this->Cell(30,$resto, '','LBR',0,'L');
	if($Yfinal >= 175){
		$this -> cabeceraTabla();	
	}

	}
	function totales($subtotal,$email, $observaciones,$iva,$total){
	$pdf -> SetY(190);
	$this -> SetLineWidth(0.4);
	$this -> SetDrawColor(006,057,113);
	$this -> SetFillColor(006,057,113);
	$this -> SetTextColor(155);
	$this -> SetFont('Arial','B',7);

	$this -> Cell(195,5,'IMPORTE EN LETRAS',1,1,'L',1);
	$this -> SetTextColor(0);
	$this -> Cell(195,5,'CINCO MIL QUINIENTOS CUATRO PESOS 42/100 MXN SELLO DIGITAL',1,1,'L',0);
	$this -> SetTextColor(255);
	$this -> Cell(195,5,'SELLO DIGITAL DEL EMISOR',1,1,'L',1);
	$this -> SetTextColor(0);
	$this -> MultiCell(195,4,$certificado_digital,1,1,'L',0);
	$this -> SetTextColor(255);
	$this -> Cell(195,5,'SELLO DIGITAL DEL SAT',1,1,'L',1);
	$this -> SetTextColor(0);
	$this -> MultiCell(195,4,$sello,1,1,'L',0);
	$this -> SetTextColor(255);
	$this -> Cell(195,5,'CADENA ORIGINAL DEL COMPLEMENTO DE CERTIFICACION DIGITAL DEL SAT',1,1,'L',1);
	$this -> SetTextColor(0);
	$this -> MultiCell(195,4,'||1.0|5f2b4fd5-f693-484e-a2ce-a56cfed33109|2016-06-21T10:14:36|WFa5BG2+AYZbo7NfI6nqMUVcqRSuFEnzQqyrxJsdgEbqnf8ovO88Vref/Pwy+vuWiEgztatu1X2fROC7tB1KnCA5cvnmBG3RSXwqZ1ig4DPO+KN6IcIUhAHAZjXQfCcvy4ympTe4hOhO6HivDNh/3jXOQk7fqXOBFBeBhYAxhiY=|00001000000202809550||',1,1,'L',0);
	$this -> SetTextColor(255);
	$this -> Cell(195,5,'',0,1,'L',0);
	$this->Image('..\..\imagenes\imgaudicel.png',35,244,40);
	$this->Image('qrcode.png',10,244,20);
	$this -> Cell(130);
	$this -> Cell(35,5,'SUB-TOTAL',1,0,'L',1);
	$this -> SetTextColor(0);
	$this -> Cell(30,5,'',1,1,'L',0);
	$this -> Cell(130);
	$this -> SetTextColor(255);
	$this -> Cell(35,5,'IVA',1,0,'L',1);
	$this -> SetTextColor(0);
	$this -> Cell(30,5,'',1,1,'L',0);
	$this -> Cell(130);
	$this -> SetTextColor(255);
	$this -> Cell(35,5,'TOTAL',1,0,'L',1);
	$this -> SetTextColor(0);
	$this -> Cell(30,5,'',1,1,'L',0);
	}
}
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf -> datosProductos($datosProducto,$factura);
$pdf -> footerGeneral();
if(!$caso)
	$pdf->Output();
?>