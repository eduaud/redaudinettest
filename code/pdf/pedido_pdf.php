<?php

class PDF extends FPDF{
		
		function Header(){
				Global $accion;
				Global $sucursal;
				Global $folio;
				Global $fecha_pedido;
				Global $tipo_pago;
				Global $logo;
				
				$this -> SetLineWidth(0.2);
				$this -> SetDrawColor(199);
				
				//$this->Cell(190,4,'',1,1,'L');
				$this->Image($logo,10,15,50);
				$this->SetFillColor(006,057,113);
				$this->SetTextColor(255);
				$this->SetFont('Arial','B',12);
				$this->Cell(120);
				$this->Cell(70,7,$accion. ' ' . $folio,1,1,'C', true);
				$this->SetTextColor(0);
				$this->SetFont('Arial','B',11);
				$this->Cell(120);
				$this->Cell(190,1,'','LR',1,'C');
				$this->Cell(120);
				$this->Cell(70,5,utf8_decode('Comunicación y Entretenimiento'),'LR',1,'C');
				$this->Cell(120);
				$this->Cell(70,5,utf8_decode('AUDICEL, S.A. de C.V'),'LR',1,'C');
				$this->Cell(120);
				$this->SetFont('Arial','B',7);
				$this->Cell(70,4,utf8_decode('R.F.C.: AUD930113FM9'),'LR',1,'C');
				$this->Cell(120);
				$this->SetFont('Arial','B',9);
				$this->Cell(70,4,utf8_decode("SUCURSAL " . $sucursal),'LR',1,'C');
				$this->Cell(120);
				$this->Cell(190,1,'','LR',1,'C');
				$this->Cell(120);
				$this->SetFont('Arial','',7);
				$this->Cell(70,4,utf8_decode("FECHA"),1,1,'C');
				$this->Cell(120);
				$this->SetFont('Arial','B',8);
				$this->Cell(70,7,$fecha_pedido,1,1,'C');
				$this->Cell(120);
				$this->SetFont('Arial','',7);
				//$this->Cell(70,4,utf8_decode("CONDICIONES DE PAGO"),1,1,'C');
				$this->Cell(120);
				$this->SetFont('Arial','B',8);
				$this->Cell(70,7,'','LBR',1,'C');
				
				}
				
		function direccionSuc($direccion, $telefono, $correo){
				$this -> SetLineWidth(0.2);
				$this -> SetDrawColor(199);
				$this->SetFillColor(006,057,113);
				$this->SetFont('Arial','B',8);
				//$this->Cell(190,5,$direccion,'LTR',1,'L');
				$this->MultiCell(190, 5,$direccion, 'LTR');
				$this->Cell(190,5,'Tels.: ' . $telefono,'LR',1,'L');
				$this->Cell(190,5,'E-Mail: ' . $correo,'LBR',1,'L');
				$this->Ln();
				}
		function datosCliente($cliente){
				foreach($cliente as $datos){
						$this->SetFont('Arial','',8);
						
						$this->Cell(25,6, 'PEDIDO A:',0,0,'L');
						$this->SetFont('Arial','B',7);
						$this->Cell(165,4, $datos -> cliente,'B',1,'L');
						$this->SetFont('Arial','',8);
						$this->Cell(190,1,'',0,1,'C');
						$this->Cell(25,6, 'DOMICILIO:',0,0,'L');
						$this->SetFont('Arial','B',7);
						$this->Cell(165,4, $datos -> domicilio,'B',1,'L');
						$this->Cell(190,1,'',0,1,'C');
						/***Linea de colonia***/
						$this->SetFont('Arial','',8);
						$this->Cell(25,6, 'COLONIA:',0,0,'L');
						$this->SetFont('Arial','B',7);
						$this->Cell(57,4, $datos -> colonia,'B',0,'L');
						$this->Cell(5);
						$this->SetFont('Arial','',8);
						$this->Cell(15,6, 'CIUDAD:',0,0,'L');
						$this->SetFont('Arial','B',7);
						$this->Cell(40,4, $datos -> ciudad,'B',0,'L');
						$this->SetFont('Arial','',8);
						$this->Cell(5);
						$this->Cell(28,6, utf8_decode('CÓDIGO POSTAL:'),0,0,'L');
						$this->SetFont('Arial','B',7);
						$this->Cell(15,4, $datos -> cp,'B',1,'L');
						/********/
						$this->Cell(190,1,'',0,1,'C');
						$this->SetFont('Arial','',8);
						$this->Cell(25,6, 'TELEFONO(S):',0,0,'L');
						$this->SetFont('Arial','B',10);
						$this->Cell(165,4, $datos -> telefonos,'B',1,'L');
						/*$this->Cell(190,1,'',0,1,'C');
						$this->SetFont('Arial','',8);
						$this->Cell(25,6, 'REFERENCIA:',0,0,'L');
						$this->SetFont('Arial','B',7);
						$this->Cell(165,4, $datos -> referencia,'B',1,'L');
						$this->Cell(190,1,'',0,1,'C');
						$this->SetFont('Arial','',8);
						$this->Cell(25,6, 'C. DE VENTAS:',0,0,'L');
						$this->SetFont('Arial','B',8);
						$this->Cell(65,4, $datos -> vendedor,'B',0,'L');
						$this->Cell(5);
						$this->SetFont('Arial','',8);
						$this->Cell(45,6, 'PLANO Y COORDENADA:',0,0,'L');
						$this->SetFont('Arial','B',8);
						$this->Cell(50,4, $datos -> plano . ', ' . $datos -> coordenada ,'B',1,'L');*/
						//$this->Cell(50,4, $datos -> requiere_factura ,'B',1,'L');
						if( $datos -> requiere_factura =='')
										$this->Ln(5);
						else
						{
							$this->Cell(192,3, $datos -> requiere_factura ,'0',1,'R');
							$this->Ln(1);
						}
					}
				}
				
			function cabeceraTabla(){
					$this -> SetLineWidth(0.2);
					$this -> SetDrawColor(199);
					$this->SetFont('Arial','B',7);
					$this->Cell(20,10, 'CANTIDAD',1,0,'C');
					$this->Cell(37,10, 'CLAVE','TBR',0,'C');
					$this->Cell(133,10, utf8_decode('DESCRIPCIÓN'),'TBR',1,'C');
					$this->SetFont('Arial','B',6);
					//$this->Cell(25,10, 'PRECIO UNITARIO','TBR',0,'C');
					//$this->Cell(26,10, 'IMPORTE','TBR',1,'C');
					}
			
			function datosProductos($producto){
					
					$this -> SetLineWidth(0.2);
					$this -> SetDrawColor(199);
					$this -> cabeceraTabla();
					$inicialY = $this-> GetY();
					$this->SetXY(10, $inicialY);
					foreach($producto as $datos){
							$this->SetFont('Arial','',8);
							 
							if($inicialY>240)
							{	
								$this->AddPage();
								$this -> SetLineWidth(0.2);
								$this -> SetDrawColor(199);
								$this -> cabeceraTabla();
								$inicialY=70; 
							}
							$this->SetXY(10, $inicialY);
							//$this->Cell(20,10, $datos -> cantidad. " ".$inicialY ,'L',0,'C');
							$this->Cell(20,10, $datos -> cantidad ,'L',0,'C');
							
							$this->SetXY(30, $inicialY + 2);
							$this->SetFont('Arial','',8);
							$this->MultiCell(37, 3, $datos -> clave,0,'C');
							//$this->Cell(37,10, $datos -> clave,0,0,'L');
							
							$this->SetXY(67, $inicialY + 2);
							$this->MultiCell(133, 3, $datos -> producto . "\n" . $datos -> descripcion,'R','C');
							
							//$this->SetXY(117, $inicialY + 2);
							
							//$this->Cell(31,10, $datos -> tipo_entrega,0,0,'C');
							/*$this->MultiCell(33, 3, $datos -> tipo_entrega . "/" . $datos -> lugar_entrega . "\n" . $datos -> fecha_entrega, 0, 'C');
							$this->SetFont('Arial','',8);*/
							//$this->SetXY(150, $inicialY);
							//$this->Cell(25,10, $datos -> precio,0,0,'R');
							
							//$this->SetXY(175, $inicialY);
							//$this->Cell(25,10, $datos -> importe,'R',1,'R');
							
							$actualY = $this-> GetY();  //Obtenemos el valor de Y despues de la multicelda ya que este tendra el valor donde iniciara la siguiente fila
							$nuevaY = $actualY - $inicialY; // Hacemos la convercion para obtener la suma de la siugiente posicion
							$inicialY +=  $nuevaY;		// Le asignamos a Y inicial el nuevo valor de la siguiente fila
							
							}
					$Yfinal = $this-> GetY();
					if($Yfinal >= 200)
						$dibuja = 250;
					else
						$dibuja = 200;
					
					$resto = $dibuja - $Yfinal;
					$this->Cell(190,$resto, '','LBR',1,'L');
					if($Yfinal >= 200){
						$this -> cabeceraTabla();	
					}
					
					}
				function totales($subtotal,$email, $observaciones,$iva,$total){/*
								$this->SetFont('Arial','',6);
								$this->MultiCell(191, 3, utf8_decode('Audicel Condiciones'), 0);
								$this->SetFillColor(006,057,113);
								$this->SetFont('Arial','B',7);
								$this->Cell(191,1, '','LTR',1,'L');
								$this->SetTextColor(255);
								$this->Cell(120,7, 'INSTRUCCIONES ESPECIALES',0,0,'L', true);
								$this->Cell(30,7, '','R',0,'L', true);
								$this->Cell(41,7, '','R',1,'L');
								
								$this->Cell(120,7, '','LR',0,'L');
								
								$this->Cell(30,7, 'SUBTOTAL','R',0,'L', true);
								$this->SetTextColor(0);
								$this->Cell(41,7, "$" . number_format($subtotal,2),'BR',1,'R');
								
								$this->Cell(120,7, substr($observaciones,0,79),'L',0,'L');
								$this->Cell(30,7, '','LR',0,'L', true);
								$this->Cell(41,7, '','LR',1,'L');
								
								
								$this->Cell(120,7, substr($observaciones,79,80),'L',0,'L');
								$this->SetTextColor(255);
								$this->Cell(30,7, 'I.V.A.','LBR',0,'L', true);
								$this->SetTextColor(0);
								$this->Cell(41,7,"$" . number_format($iva,2),'LBR',1,'R');
								
								$this->Cell(120,7, '','L',0,'L');
								$this->Cell(30,7, '','LR',0,'L', true);
								$this->Cell(41,7, '','LR',1,'L');
								
								$this->Cell(120,7, '','L',0,'L');
								$this->SetTextColor(255);
								$this->Cell(30,7, 'TOTAL','LR',0,'L', true);
								$this->SetTextColor(0);
								$this->Cell(41,7, "$" . number_format($total,2),'LBR',1,'R');
								
								$this->Cell(120,7, 'EMAIL DEL CLIENTE: ' . $email,'LB',0,'L');
								$this->Cell(30,7, '','LBR',0,'L', true);
								$this->Cell(41,7, '','LBR',1,'L');*/
								
								
						
						}
				
				
		}
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AddPage();
$pdf->direccionSuc($datosPedido['direccion'], $datosPedido['telefono'], $datosPedido['correo']);
$pdf->datosCliente($datosCliente);
$pdf->datosProductos($datosProducto);
$posFinalTabla = $pdf -> GetY();
if($posFinalTabla <= 200){
		$pdf -> SetXY(10, 200);
		}
else{
		$pdf->AddPage();
		$pdf -> SetXY(10, 200);
		}
//$pdf -> SetY(225);
$pdf->totales($subtotalIP, $datosEmail['email'], $datosPedido['observaciones'],$iva,$Total);
$pdf->Output();
?>