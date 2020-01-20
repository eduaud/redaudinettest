<?php

class PDF extends FPDF{
		
		function Header(){
				Global $sucursal;
				Global $folio;
				Global $fecha_pedido;
				Global $tipo_pago;
				Global $logo;
				
				$this -> SetLineWidth(0.2);
				$this -> SetDrawColor(199);
				
				//$this->Cell(190,4,'',1,1,'L');
				$this->Image($logo,10,15,90);
				$this->SetFillColor(63);
				$this->SetTextColor(255);
				$this->SetFont('Arial','B',12);
				$this->Cell(120);
				$this->Cell(70,7,utf8_decode('PEDIDO') . ' ' . $folio,1,1,'C', true);
				$this->SetTextColor(0);
				$this->SetFont('Arial','B',11);
				$this->Cell(120);
				$this->Cell(190,1,'','LR',1,'C');
				$this->Cell(120);
				$this->Cell(70,5,utf8_decode('Diseños Exclusivos'),'LR',1,'C');
				$this->Cell(120);
				$this->Cell(70,5,utf8_decode('Nasser, S.A. de C.V'),'LR',1,'C');
				$this->Cell(120);
				$this->SetFont('Arial','B',7);
				$this->Cell(70,4,utf8_decode('R.F.C.: DEN-030320-560'),'LR',1,'C');
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
				
				
				}
				
		function direccionSuc($direccion, $telefono, $correo){
				$this -> SetLineWidth(0.2);
				$this -> SetDrawColor(199);
				$this->SetFillColor(199);
				$this->SetFont('Arial','B',8);
				//$this->Cell(190,5,$direccion,'LTR',1,'L');
				$this->MultiCell(190, 5, $direccion, 'LTR');
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
						$this->SetFont('Arial','B',7);
						$this->Cell(165,4, $datos -> telefonos,'B',1,'L');
						$this->Cell(190,1,'',0,1,'C');
						$this->SetFont('Arial','',8);
						$this->Cell(25,6, 'REFERENCIA:',0,0,'L');
						$this->SetFont('Arial','B',7);
						$this->Cell(165,4, $datos -> referencia,'B',1,'L');
						/******/
						$this->Cell(190,1,'',0,1,'C');
						$this->SetFont('Arial','',8);
						$this->Cell(25,6, 'C. DE VENTAS:',0,0,'L');
						$this->SetFont('Arial','B',8);
						$this->Cell(65,4, $datos -> vendedor,'B',0,'L');
						$this->Cell(5);
						$this->SetFont('Arial','',8);
						$this->Cell(45,6, 'PLANO Y COORDENADA:',0,0,'L');
						$this->SetFont('Arial','B',8);
						$this->Cell(50,4, $datos -> plano . ', ' . $datos -> coordenada ,'B',1,'L');
						$this->Ln(5);
						}
				}
				
			function cabeceraTabla(){
					$this -> SetLineWidth(0.2);
					$this -> SetDrawColor(199);
					$this->SetFont('Arial','B',7);
					$this->Cell(27,10, 'CANTIDAD',1,0,'C');
					$this->Cell(45,10, 'CLAVE','TBR',0,'C');
					$this->Cell(79,10, utf8_decode('DESCRIPCIÓN'),'TBR',0,'C');
					$this->Cell(40,10, utf8_decode('FORMA DE ENTREGA'),'TBR',1,'C');
					}
			
			function datosProductos($producto){
					
					$this -> SetLineWidth(0.2);
					$this -> SetDrawColor(199);
					$this -> cabeceraTabla();
					$inicialY = $this-> GetY();
					$this->SetXY(10, $inicialY);
					foreach($producto as $datos){
							$this->SetFont('Arial','',7);
							
							$this->SetXY(10, $inicialY);
							$this->Cell(27,10, $datos -> cantidad,'L',0,'C');
							
							$this->SetXY(37, $inicialY);
							$this->SetFont('Arial','',6);
							$this->Cell(45,10, $datos -> clave,0,0,'L');
							
							$this->SetXY(82, $inicialY + 2);
							$this->MultiCell(79, 3, $datos -> producto . "\n" . $datos -> descripcion, 0);
							
							$this->SetXY(161, $inicialY + 2);
							
							//$this->Cell(31,10, $datos -> tipo_entrega,0,0,'C');
							$this->MultiCell(40, 3, $datos -> tipo_entrega . "/" . $datos -> lugar_entrega, 'R', 'C');
							
							
							$actualY = $this-> GetY();  //Obtenemos el valor de Y despues de la multicelda ya que este tendra el valor donde iniciara la siguiente fila
							$nuevaY = $actualY - $inicialY; // Hacemos la convercion para obtener la suma de la siugiente posicion
							$inicialY +=  $nuevaY;		// Le asignamos a Y inicial el nuevo valor de la siguiente fila
							}
					$Yfinal = $this-> GetY();
					if($Yfinal >= 190)
							$dibuja = 250;
					else
							$dibuja = 190;
					
					$resto = $dibuja - $Yfinal;
					$this->Cell(191,$resto, '','LBR',1,'L');
					if($Yfinal >= 190){
									$this -> cabeceraTabla();	
									}
					
					$this->SetFont('Arial','',6);
						$this->Cell(191,3, utf8_decode('- No hay garantía en cristales - Horario abierto en entrega de mercancía - Favor de leer cláusulas al reverso - No se aceptan cancelaciones, ni devoluciones'),0,1,'L');
					}
				function totales($totales, $email, $observaciones){
								$this->SetFont('Arial','',6);
								$this->Cell(191,3, utf8_decode('- No hay garantía en cristales - Horario abierto en entrega de mercancía - Favor de leer cláusulas al reverso - No se aceptan cancelaciones, ni devoluciones'),0,1,'L');
								$this->SetFillColor(63);
								$this->SetFont('Arial','B',7);
								$this->Cell(191,1, '','LTR',1,'L');
								$this->SetTextColor(255);
								$this->Cell(120,7, 'INSTRUCCIONES ESPECIALES',0,0,'L', true);
								$this->Cell(30,7, '','R',0,'L', true);
								$this->Cell(41,7, '','R',1,'L');
								
								$this->Cell(120,7, '','LR',0,'L');
								
								$this->Cell(30,7, 'SUBTOTAL','R',0,'L', true);
								$this->SetTextColor(0);
								$this->Cell(41,7, "$" . number_format($totales,2),'BR',1,'R');
								
								$this->Cell(120,7, $observaciones,'L',0,'L');
								$this->Cell(30,7, '','LR',0,'L', true);
								$this->Cell(41,7, '','LR',1,'L');
								
								$this->Cell(120,7, '','L',0,'L');
								$this->SetTextColor(255);
								$this->Cell(30,7, 'I.V.A.','LBR',0,'L', true);
								$this->Cell(41,7, '','LBR',1,'L');
								
								$this->Cell(120,7, '','L',0,'L');
								$this->Cell(30,7, '','LR',0,'L', true);
								$this->Cell(41,7, '','LR',1,'L');
								
								$this->Cell(120,7, '','L',0,'L');
								$this->SetTextColor(255);
								$this->Cell(30,7, 'TOTAL','LR',0,'L', true);
								$this->SetTextColor(0);
								$this->Cell(41,7, "$" . number_format($totales,2),'LBR',1,'R');
								
								$this->Cell(120,7, 'EMAIL DEL CLIENTE: ' . $email,'LB',0,'L');
								$this->Cell(30,7, '','LBR',0,'L', true);
								$this->Cell(41,7, '','LBR',1,'L');
								
								
						
						}
			function observaciones($observaciones){
						
						$cadena = recortaTexto($observaciones);
						$partes = explode("-", $cadena);
						
						$this -> Ln();
						$this -> SetLineWidth(0.2);
						$this -> SetDrawColor(199);
						$this->SetFont('Arial','',9);
						$this->Cell(25,10, "Observaciones:",0,0,'L');
						$this->Cell(150,8, $partes[0],0,0,'L');
						$posIni = $this -> GetY();
						$PosY = $posIni + 6;
						$this -> Line(37, $PosY, 200, $PosY); //Primera lineA
						$this -> SetXY(37, $PosY - 7);
						$PosY = $posIni + 13;
						$this -> Line(37, $PosY, 200, $PosY); //Segunda Linea
						$this -> SetXY(34, $PosY-7);
						$this->Cell(150,8, $partes[1],0,0,'L');
						$PosY = $posIni + 20;
						$this -> Line(37, $PosY, 200, $PosY); //Tercera Linea
						$this -> SetXY(34, $PosY-7);
						$this->Cell(150,8, $partes[2],0,1,'L');
						$this -> SetXY(10, $PosY+7);
				}
				
				
		}
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AddPage();
$pdf->direccionSuc($datosPedido['direccion'], $datosPedido['telefono'], $datosPedido['correo']);
$pdf->datosCliente($datosCliente);
$pdf->datosProductos($datosProducto);
$posFinalTabla = $pdf -> GetY();
if($posFinalTabla <= 200){
		$pdf -> SetXY(10, 190);
		}
else{
		$pdf->AddPage();
		$pdf -> SetXY(10, 190);
		}
//$pdf -> SetY(225);
//$pdf->totales($subtotalIP, $datosEmail['email'], $datosPedido['observaciones']);
$pdf -> observaciones($datosPedido['observaciones']);
$pdf->Output();
?>