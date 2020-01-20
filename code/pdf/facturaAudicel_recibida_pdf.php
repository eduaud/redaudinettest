<?php
class PDFX extends FPDF{
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
                        Global $descripcion_forma_pago;
                        
                        Global $nombreMetodoPago;
                        Global $claveMetodoPago;
			
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
			$this -> Cell(65,5,utf8_decode($cliente),1,0,'C',0);
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
                Global $selloSAT;
		
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
		$this -> MultiCell(195,3.5,$sello,1,1,'L',0);
		$this -> SetTextColor(255);
		$this -> SetFont('Arial','B',7);
		$this -> Cell(195,5,'SELLO DIGITAL DEL SAT',1,1,'L',1);
		$this -> SetTextColor(0);
		$this -> SetFont('Arial','B',5);
		$this -> MultiCell(195,3.5,$selloSAT,1,1,'L',0);
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
		$this -> SetFont('Arial','B',6.5);
		$this -> Cell(35,5,'Impuesto Trasladado IVA 0.16%',1,0,'L',1);
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
		$this -> Cell(30,5,utf8_decode($metodo_pago . ' - ' . $descripcion_forma_pago),0,1,'R',0);
		$this -> Cell(30,5,'',0,0,'C',0);
		$this -> SetFont('Arial','B',5);
		$this -> Cell(50,5,utf8_decode('Tel. 5639 3335 con 12 líneas'),0,0,'C',0);
		$this -> Cell(50,5,'Tels. '.$telefonos,0,0,'C',0);
		$this -> SetFont('Arial','B',6);
		$this -> Cell(35,5,utf8_decode('Método de Pago: '),0,0,'L',0);
		$this -> Cell(30,5,utf8_decode($claveMetodoPago . ' - ' . $nombreMetodoPago), 0, 1, 'R', 0);
		$this -> Cell(30,5,'',0,0,'C',0);
		$this -> SetFont('Arial','B',5);
		$this -> Cell(50,5,utf8_decode('Fax: 5639 3750'),0,0,'C',0);
		$this -> Cell(50,5,'',0,0,'C',0);
		$this -> SetFont('Arial','B',6);
		$this -> Cell(35,5,utf8_decode('Número Cuenta Pago:'),0,0,'L',0);
		$this -> Cell(30,5,utf8_decode($cuenta),0,1,'R',0);		
	}
	function cabeceraTabla(){
            
                $ancho1=12;
		$ancho2=12;
		$ancho3=10;
		$ancho4=127;
		$ancho5=15;
		$ancho6=20;
                
		$this -> SetLineWidth(0.4);
		$this -> SetDrawColor(0,38,89);
		$this -> SetFillColor(0,38,89);
		$this -> SetTextColor(255);
		$this -> SetFont('Arial','B',7);
		
//		$this -> Cell(20,10, 'CANT./UNIDAD',1,0,'C',1);
//		$this -> Cell(135,10, utf8_decode('CONCEPTO'),'TBR',0,'C',1);
//		$this -> Cell(20,10, 'P UNITARIO','TBR',0,'C',1);
//		$this -> Cell(20,10, 'IMPORTE','TBR',1,'C',1);
                
                $this -> Cell($ancho1,10, 'CLAVE',1,0,'C',1);
		$this -> Cell($ancho2,10, 'UNIDAD','TBR',0,'C',1);
		$this -> Cell($ancho3,10, 'CANT','TBR',0,'C',1);
		$this -> Cell($ancho4,10, utf8_decode('CONCEPTO'),'TBR',0,'C',1);
		$this -> Cell($ancho5,10, 'P UNITARIO','TBR',0,'C',1);
		$this -> Cell($ancho6,10, 'IMPORTE','TBR',1,'C',1);
	}
	function datosProductos($producto){
                
                $ancho1=12;
		$ancho2=12;
		$ancho3=10;
		$ancho4=127;
		$ancho5=15;
		$ancho6=20;
            
		$this -> SetLineWidth(0.4);
		$this -> SetDrawColor(006,057,113);
		$this -> cabeceraTabla();
		$this->SetFillColor(006,057,113);
		$inicialY = $this-> GetY();
		$this -> SetXY(10, $inicialY);
				
		foreach($producto as $datos){
			$this->SetFont('Arial','',6); 
			if($inicialY > 250){	
				$Yfinal = $this-> GetY();
				if($Yfinal > 250)
					$dibuja = 255;
				else
					$dibuja = 255;
				$resto = $dibuja - $Yfinal;
				$this -> SetLineWidth(0.4);
				$this -> SetDrawColor(006,057,113);
				
				/*$this -> Cell(20,$resto, '','LBR',0,'L');
				$this -> Cell(135,$resto, '','LBR',0,'L');
				$this -> Cell(20,$resto, '','LBR',0,'L');
				$this -> Cell(20,$resto, '','LBR',1,'L');*/
                                
                                $this -> Cell($ancho1,$resto, '','LBR',0,'L');
				$this -> Cell($ancho2,$resto, '','LBR',0,'L');
				$this -> Cell($ancho3,$resto, '','LBR',0,'L');
				$this -> Cell($ancho4,$resto, '','LBR',0,'L');
				$this -> Cell($ancho5,$resto, '','LBR',0,'L');
				$this -> Cell($ancho6,$resto, '','LBR',1,'L');
				
				$this -> AddPage();
				$this -> SetLineWidth(0.4);
				$this -> SetDrawColor(006,057,113);
				$this -> cabeceraTabla();
				$inicialY = 87.00125; 
			}
			$this -> SetTextColor(0);
			$this -> SetFont('Arial','',6);
			$this -> SetXY(10, $inicialY);
                        
                        $linea = 0;
			$longitudNombre = strlen($datos -> nombre);
			$tamaño_linea = $longitudNombre / 100;
			$linea_entero = intval($tamaño_linea);
			if($linea_entero < $tamaño_linea){
				$linea = $linea_entero + 1;
			}else{
				$linea = $linea_entero;
			}
				
			$this -> Cell($ancho1,(5 * $linea), $datos -> clave_producto_sat ,'LR',0,'C');
			$this -> Cell($ancho2,(5 * $linea), $datos -> clave_unidad_sat ,'LR',0,'C');
			$this -> Cell($ancho3,(5 * $linea), $datos -> cantidad .' '.$datos -> unidad,'LR',0,'C');
			
			$this->MultiCell($ancho4,5,$datos -> nombre,'R','C');
			//$this->Ln(0.1);
	
			$this -> Cell($ancho1,(5 * $linea), '','LR',0,'C');
			$this -> Cell($ancho2,(5 * $linea), '','LR',0,'C');
			$this -> Cell($ancho3,(5 * $linea), '','LR',0,'C');

                        $this -> SetFont('Arial','',5);

			//$this->MultiCell($ancho4+$ancho5+$ancho6,2,'  Base:'.$datos -> importe.' Impuesto: IVA Tipo Factor: 0.160000   Tasa o Cuota : Tasa  Importe: '.$datos -> iva_monto,'LR','R');
			
			$this->MultiCell($ancho4,2,'  Base:'.$datos -> importe.' | Impuesto: IVA | Tipo Factor: 0.160000   | Tasa o Cuota : Tasa  | Importe: '.$datos -> iva_monto,'LR','R');
			
			$this -> SetFont('Arial','',6);
			$this->SetXY(171,$inicialY);
			$this->Cell($ancho5,(5 * $linea), $datos -> valor_unitario,'R',0,'C');
			
			$this->SetXY(186, $inicialY);
			$this->Cell($ancho6,(5 * $linea), $datos -> importe,'R',1,'C');
			

			$actualY = $this-> GetY();  //Obtenemos el valor de Y despues de la multicelda ya que este tendra el valor donde iniciara la siguiente fila
			$nuevaY = $actualY - $inicialY; // Hacemos la convercion para obtener la suma de la siugiente posicion
			$inicialY = $inicialY +  $nuevaY +2;		// Le asignamos a Y inicial el nuevo valor de la siguiente fila
                        
                        
                        
			/*$this -> Cell(20,5, $datos -> cantidad .' '.$datos -> unidad,'LR',0,'C');
			
			$this->MultiCell(135,5,$datos -> nombre,'R','C');
			
			$this->SetXY(165,$inicialY);
			$this->Cell(20,5, $datos -> valor_unitario,'R',0,'C');
			
			$this->SetXY(185, $inicialY);
			$this->Cell(20,5, $datos -> importe,'R',1,'C');
			

			$actualY = $this-> GetY();  //Obtenemos el valor de Y despues de la multicelda ya que este tendra el valor donde iniciara la siguiente fila
			$nuevaY = $actualY - $inicialY; // Hacemos la convercion para obtener la suma de la siugiente posicion
			$inicialY +=  $nuevaY;*/		// Le asignamos a Y inicial el nuevo valor de la siguiente fila
						
		}
		/*$Yfinal = $this-> GetY();
		if($Yfinal > 175){
			$dibuja = 255;
			$resto = $dibuja - $Yfinal;
			$this -> SetLineWidth(0.4);
			$this -> SetDrawColor(006,057,113);
			
			$this -> Cell(20,$resto, '','LBR',0,'L');
			$this -> Cell(135,$resto, '','LBR',0,'L');
			$this -> Cell(20,$resto, '','LBR',0,'L');
			$this -> Cell(20,$resto, '','LBR',1,'L');
			
			$this -> AddPage();
			$this -> SetLineWidth(0.4);
			$this -> SetDrawColor(006,057,113);
		}
		else{
			$dibuja = 175;		
			$resto = $dibuja - $Yfinal;
			$this->Cell(20,$resto, '','LBR',0,'L');
			$this->Cell(135,$resto, '','LBR',0,'L');
			$this->Cell(20,$resto, '','LBR',0,'L');
			$this->Cell(20,$resto, '','LBR',0,'L');
		}
		if($Yfinal >= 250){
			$this -> cabeceraTabla();	
		}*/
                
                $Yfinal = $this-> GetY();
		if($Yfinal > 175){
			$dibuja = 255;
			$resto = $dibuja - $Yfinal;
			$this -> SetLineWidth(0.4);
			$this -> SetDrawColor(006,057,113);
			
			$this -> Cell($ancho1,$resto, '','LBR',0,'L');
			$this -> Cell($ancho2,$resto, '','LBR',0,'L');
			$this -> Cell($ancho3,$resto, '','LBR',0,'L');
			$this -> Cell($ancho4,$resto, '','LBR',0,'L');
			$this -> Cell($ancho5,$resto, '','LBR',0,'L');
			$this -> Cell($ancho6,$resto, '','LBR',1,'L');
			
			$this -> AddPage();
			$this -> SetLineWidth(0.4);
			$this -> SetDrawColor(006,057,113);
		}
		else{
			$dibuja = 175;		
			$resto = $dibuja - $Yfinal;
			$this -> Cell($ancho1,$resto, '','LBR',0,'L');
			$this -> Cell($ancho2,$resto, '','LBR',0,'L');
			$this -> Cell($ancho3,$resto, '','LBR',0,'L');
			
			$this->Cell($ancho4,$resto, '','LBR',0,'L');
			$this->Cell($ancho5,$resto, '','LBR',0,'L');
			$this->Cell($ancho6,$resto, '','LBR',0,'L');
		}
		if($Yfinal >= 250){
			$this -> cabeceraTabla();	
		}
	}			
}
?>