<?php

class PDF extends FPDF{
		
		function encabezado(){
				
				Global $logo;
				Global $almacen;
				Global $vale;
				Global $folio;
				
				
				$this -> SetLineWidth(0.2);
				$this -> SetDrawColor(199);
				$this->Image($logo,10,15,90);
				$this->SetFillColor(63);
				$this->SetFont('Arial','B',12);
				$this->Cell(120);
				$this->Cell(70,1,'','LTR',1,'C');
				$this->Cell(120);
				$this->Cell(70,5,utf8_decode('Diseños Exclusivos'),'LR',1,'C');
				$this->Cell(120);
				$this->Cell(70,5,utf8_decode('Nasser, S.A. de C.V'),'LR',1,'C');
				$this->Cell(120);
				$this->Cell(70,1,'','LBR',1,'C');
				$this->Cell(120);
				$this->SetFont('Arial','B',15);
				$this->Cell(70,8,'Orden de Compra','LBR',1,'C');
				$this->Cell(120);
				$this->Cell(70,1,'','LR',1,'C');
				$this->Cell(120);
				$this->SetFont('Arial','B',9);
				$this->Cell(70,4,"F O L I O",'LBR',1,'C');
				$this->Cell(120);
				$this->Cell(70,1,'','LR',1,'C');
				$this->Cell(120);
				$this->SetFont('Arial','B',12);
				$this->SetTextColor(216,15,15);
				$this->Cell(70,6,"No. " . $folio,'LBR',1,'C');
				$this->SetTextColor(0);
				$this -> Ln();
				}
		function datosOrden($ordenDatos){
				$this -> SetLineWidth(0.2);
				$this -> SetDrawColor(199);
				$this->SetFont('Arial','B',7);
				foreach($ordenDatos as $registros){
						$this->Cell(190,1,'','LTR',1,'C');
						$this->Cell(190,5,$registros -> dir_cedis,'LR',1,'l');
						$this->Cell(190,5,'Tel.5541-6106 / 5541-6044','LBR',1,'l');
						$this->Cell(190,2,'','LTR',1,'C');
						$this->SetFont('Arial','B',8);
						$this->Cell(2,5,"",'L',0,'L');
						$this->Cell(113,5,"Orden De Compra:   " . $registros -> id,0,0,'L');
						$this->Cell(75,5, 'Fecha:   ' . $registros -> fecha ,'R',1,'L');
						$this->Cell(2,5,"",'L',0,'L');
						$this->Cell(113,5,"Proveedor:   " . $registros -> proveedor,0,0,'L');
						$this->Cell(75,5, 'Fecha de Entrega:   ' . $registros -> fecha_entrega ,'R',1,'L');
						$this->Cell(2,5,"",'L',0,'L');
						//$this->Cell(190,5, utf8_decode("Dirección del Proveedor:   " . $registros -> dir_proveedor),0,1,'L');
						$this->MultiCell(190, 5, utf8_decode("Dirección del Proveedor:   " . $registros -> dir_proveedor), 0, 'L');
						$this->Cell(2,5,"",'L',0,'L');
						$this->Cell(113,5,"Mercancia Puesta:   " . $registros -> mercancia_puesta,0,0,'L');
						$this->Cell(75,5, 'Estatus:   ' . $registros -> estatus ,'R',1,'L');
						$this->Cell(190,2,'','LBR',1,'C');
						$this -> Ln(5);
						}
				
				}
		function cabeceraTabla(){
				$this -> SetLineWidth(0.2);
				$this -> SetDrawColor(199);
				$this->SetFont('Arial','B',6);
				$this->Cell(20,10, 'CANTIDAD','LTB',0,'C');
				$this->Cell(50,10, 'PRODUCTO',1,0,'C');
				$this->Cell(20,10, 'PRECIO UNITARIO','TBR',0,'C');
				$this->Cell(20,10, 'DESCUENTO','TBR',0,'C');
				$this->Cell(20,10, 'PRECIO FINAL','TBR',0,'C');
				$this->Cell(20,10, 'IMPORTE','TBR',0,'C');
				$this->Cell(40,10, 'OBSERVACIONES',1,1,'C');
				}
		
		function tablaProductos($detalles){
				$this->cabeceraTabla();
				$inicialY = $this-> GetY();
				$linea = $inicialY;
					foreach($detalles as $datos){
							$this->SetFont('Arial','',6);
							$this->SetXY(10, $inicialY);
							$this->Cell(20,10, $datos -> cantidad,0,0,'C');
							
							$this->SetXY(30, $inicialY + 3);
							$this->MultiCell(50, 3, $datos -> producto, 0, 'L');
							
							$this->SetXY(80, $inicialY );
							$this->Cell(20, 10, "$". number_format($datos -> unitario, 2), 0, 0, 'C');
							
							$this->SetXY(100, $inicialY );
							$this->Cell(20, 10, number_format($datos -> descuento, 2) . "%", 0, 0, 'C');
							
							$this->SetXY(120, $inicialY );
							$this->Cell(20, 10, "$". number_format($datos -> final_precio, 2), 0, 0, 'C');
							
							$this->SetXY(140, $inicialY);
							$this->Cell(20, 10, "$". number_format($datos -> importe, 2), 0, 0, 'C');
							
							$this->SetXY(160, $inicialY + 3);
							$this->MultiCell(40, 3, $datos -> observaciones, 0, 'L');
							
							$actualY = $this-> GetY();  //Obtenemos el valor de Y despues de la multicelda ya que este tendra el valor donde iniciara la siguiente fila
							if($actualY > 266){
									$ypag = $this-> GetY();
									$this -> Line(10, $linea, 10, $ypag); //Linea que completa el lado izquierdo de la tabla
									$this -> Line(200, $linea, 200, $ypag); //Linea que completa el lado derecho de la tabla
									$resto = 4;
									$this->Cell(190,$resto, '','LBR',1,'L');
									$this -> AddPage();
									$inicialY = 20;
									$this->cabeceraTabla();
									$linea = $this-> GetY();
									}
							else{
									$nuevaY = $actualY - $inicialY; // Hacemos la convercion para obtener la suma de la siugiente posicion
									$inicialY +=  $nuevaY;		// Le asignamos a Y inicial el nuevo valor de la siguiente fila
									}
									
							}
					$Yfinal = $this-> GetY();
					$this -> Line(10, $linea, 10, $Yfinal); //Linea que completa el lado izquierdo de la tabla
					$this -> Line(200, $linea, 200, $Yfinal); //Linea que completa el lado derecho de la tabla
					
					if($Yfinal >= 210)
							$dibuja = 270;
					else
							$dibuja = 210;
					
					$resto = $dibuja - $Yfinal;
					$this->Cell(190,$resto, '','LBR',1,'L');
					
				}
		function totales($totalesDatos){
				$this -> Ln(2);
				$this->SetFont('Arial','B',9);
				foreach($totalesDatos as $datos){
						$this->Cell(155,7, "Subtotal",0,0,'R');
						$this->Cell(35,7, $datos -> subtotal,0,1,'C');
						$this->Cell(155,7, "IVA",0,0,'R');
						$this->Cell(35,7, $datos -> iva,0,1,'C');
						$this->Cell(155,7, "Total",0,0,'R');
						$this->Cell(35,7, $datos -> total,0,1,'C');
						}
				}
		function observaciones($observaciones){
				foreach($observaciones as $obs){
				
				$cadena = recortaTexto($obs -> observaciones);
				$partes = explode("-", $cadena);
				
				$this -> SetLineWidth(0.2);
				$this -> SetDrawColor(199);
				$this->SetFont('Arial','B',6);
				$this->Cell(190,5, "YOLANDA MARQUEZ MARTIN",0,1,'C');
				$this->Cell(190,5, "E-mail: compras@nassermuebles.com",0,1,'C');
				$this->Cell(190,5, "NOTA: FAVOR DE REALIZAR PREVIA CITA PARA LA RECEPCION DE SU MERCANCIA",0,1,'C');
				
				$this->SetFont('Arial','B',9);
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
		
		function Footer(){
				$this->SetY(-15);
				$this->SetFont('Arial','I',8);
				$this->Cell(0,10, utf8_decode('Página ').$this->PageNo().' de {nb}',0,0,'C');
				}
		}

$pdf = new PDF();
$pdf -> AddPage();
$pdf -> AliasNbPages();
$pdf -> encabezado();
$pdf -> datosOrden($ordenCompra);
$pdf -> tablaProductos($ordenCompraDet);
$pdf -> observaciones($ordenCompra);
$pdf -> totales($ordenCompra);
$pdf -> Output();

?>