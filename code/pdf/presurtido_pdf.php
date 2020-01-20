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
				$this->Image($logo,10,10,50);
				$this->SetFillColor(63);
				$this->SetTextColor(255);
				$this->SetFont('Arial','B',12);
				$this->Cell(180);
				//$this->Cell(70,7,utf8_decode('PEDIDO') . ' ' . $folio,1,1,'C', true);
				$this->SetTextColor(0);
				$this->SetFont('Arial','B',11);
				$this->Cell(190);
				$this->Cell(190,1,'','LR',1,'C');
				$this->Cell(190);
				$this->Cell(70,5,utf8_decode('MASTER DATA SYSTEM'),'LR',1,'C');
				$this->Cell(190);
				$this->Cell(70,5,utf8_decode('AUDICEL, S.A. de C.V'),'LR',1,'C');
				$this->Cell(190);
				$this->SetFont('Arial','B',7);
				$this->Cell(70,4,utf8_decode('R.F.C.: AUD-123344-000'),'LR',1,'C');
				$this->Cell(190);
				$this->SetFont('Arial','B',10);
				$this->Cell(70,4,utf8_decode("PRESURTIDO"),'LR',1,'C');
				$this->Cell(190);
				$this->Cell(190,1,'','LR',1,'C');
				$this->Cell(190);
				$this->SetFont('Arial','',7);
				$this->Cell(70,4,utf8_decode("FECHA"),1,1,'C');
				$this->Cell(190);
				$this->SetFont('Arial','B',8);
				$this->Cell(70,7,date("d/m/y"),1,1,'C');
				$this->Cell(190);
				
					$this->Cell(70,7,'','LBR',1,'C');
				
				}

			function cabeceraTabla(){
					$this -> SetLineWidth(0.2);
					$this -> SetDrawColor(199);
					$this->SetFont('Arial','B',9);
					$this->Cell(15,10, 'PEDIDO',1,0,'C');
					$this->Cell(70,10, 'CLIENTE','TBR',0,'C');
					$this->Cell(90,10, 'PRODUCTO','TBR',0,'C');
					$this->Cell(20,10, 'CANTIDAD','TBR',0,'C');
					$this->Cell(25,10, 'ALMACEN','TBR',0,'C');
					$this->Cell(40,10, 'OBSERVACIONES','TBR',1,'C');
					$this -> SetLineWidth(0.2);
					}
			
			function datosProductos($producto){
					$cantidad_almacen = "6";
					$this -> SetLineWidth(0.2);
					$this -> SetDrawColor(199);
					$this -> cabeceraTabla();
					$inicialY = $this-> GetY();
					$this->SetXY(10, $inicialY);
					foreach($producto as $datos)
					{
							$this->SetFont('Arial','',10);
							 
							if($inicialY>178)
							{	
								$this->AddPage();
								$this -> SetLineWidth(0.2);
								$this -> SetDrawColor(199);
								$this -> cabeceraTabla();
								$this->SetFont('Arial','',8);
								$inicialY=70; 
							}
							$this->SetXY(10, $inicialY+1);
							$this->SetFont('Arial','',7);
							$this->Cell(15,7,$datos->pedido,0,0,'C',false);
							
							$this->SetXY(25, $inicialY+1);
							$this->SetFont('Arial','',7);
							$this->Cell(70,7,$datos->cliente,0,0,'L',false);
							
							$this->SetXY(95, $inicialY+1);
							$this->SetFont('Arial','',7);
							$this->Cell(90,7,$datos->producto,0,0,'L',false);
							
							$this->SetXY(185, $inicialY+1);
							$this->SetFont('Arial','',7);
							$this->Cell(20,7,$datos->cantidad_requerida.' ',0,0,'R',false);
							
							$this->SetXY(205, $inicialY+1);
							$this->SetFont('Arial','',7);
							$this->Cell(25,8,$cantidad_almacen.' ',0,0,'R',false);
							
							$this->SetXY(230, $inicialY+1);
							$this->Cell(40,7, '___________________________',0,1,'C',false);
							
							$actualY = $this-> GetY();  //Obtenemos el valor de Y despues de la multicelda ya que este tendra el valor donde iniciara la siguiente fila
							$nuevaY = $actualY - $inicialY; // Hacemos la convercion para obtener la suma de la siugiente posicion
							$inicialY +=  $nuevaY;		// Le asignamos a Y inicial el nuevo valor de la siguiente fila
							
					}
					$Yfinal = $this-> GetY();
					/*if($Yfinal >= 200)
							$dibuja = 250;
					else
							$dibuja = 200;
					
					$resto = $dibuja - $Yfinal;
					$this->Cell(201,$resto, '','LBR',1,'L');*/
					/*if($Yfinal >= 200){
									$this -> cabeceraTabla();	
									}*/
					
					}
				function totales($totales, $email, $observaciones){
								$this->Ln(1);
								$this->Ln(1);
								
								$this->SetFont('Arial','',10);
								
								$this->Cell(120,7, '    Asignado a: __________________________________' ,'',0,'L');
								
								$this->Cell(70,7, '                  Aprobo :____________________________________','0',0,'L');

								
								
						
						}
				
				
		}
$pdf = new PDF('L', 'mm', 'Letter');
$pdf->AddPage();
//$pdf->direccionSuc();
//$pdf->datosCliente();
$pdf->datosProductos($productos);

//$posFinalTabla = $pdf -> GetY();
/*if($posFinalTabla <= 200){
		$pdf -> SetXY(10, 200);
		}
else{
		$pdf->AddPage();
		$pdf -> SetXY(10, 200);
		}*/
//$pdf -> SetY(225);
$pdf->totales();
$pdf->Output();
?>