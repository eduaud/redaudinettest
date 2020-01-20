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
				$this->Cell(70,1,'','LR',1,'C');
				$this->Cell(120);
				$this->SetFont('Arial','B',9);
				$this->Cell(70,4,"ALMACEN " . $almacen,'LR',1,'C');
				$this->Cell(120);
				$this->Cell(70,1,'','LBR',1,'C');
				$this->Cell(120);
				$this->SetFont('Arial','B',15);
				$this->Cell(70,8,$vale,'LBR',1,'C');
				$this->Cell(120);
				$this->Cell(70,1,'','LR',1,'C');
				$this->Cell(120);
				$this->SetFont('Arial','B',9);
				$this->Cell(70,4,"MOVIMIENTO",'LBR',1,'C');
				$this->Cell(120);
				$this->Cell(70,1,'','LR',1,'C');
				$this->Cell(120);
				$this->SetFont('Arial','B',12);
				$this->SetTextColor(216,15,15);
				$this->Cell(70,6,"No. " . $folio,'LR',1,'C');
				$this->SetTextColor(0);
				}
		function datosMovimiento($info){
				Global $texto_tipo;
				$this -> SetLineWidth(0.2);
				$this -> SetDrawColor(199);
				$this->Cell(190,2,'','LTR',1,'C');
				$this->SetFont('Arial','B',8);
				foreach($info as $registros){
						$this->Cell(24,5,$texto_tipo . ": ",'L',0,'L');
						$this->Cell(40,5,$registros -> tipo_movimiento,0,0,'L');
						
						if($registros -> id_subtipo == 70099){
								$this->Cell(21,5, 'Fecha:',0,0,'L');
								$this->Cell(42,5,$registros -> fecha,0,0,'L');
								
								$this->Cell(21,5,'Pedido:',0,0,'L');
								$this->Cell(42,5,$registros -> pedido,'R',1,'L');
								
								$this->Cell(22,5,'Cliente: ','L',0,'L');
								$this->Cell(168,5,$registros -> cliente,'R',1,'L');
								
								$this->Cell(22,5,utf8_decode('Dirección: '),'L',0,'L');
								$this->Cell(168,5, $registros -> direccion,'R',1,'L');
								}
						else if($registros -> id_subtipo == 70004){
								$this->Cell(21,5, 'Fecha:',0,0,'L');
								$this->Cell(105,5,$registros -> fecha,'R',1,'L');
								}
						else if($registros -> id_subtipo == 70066){
								$this->Cell(35,5, 'Fecha:',0,0,'R');
								$this->Cell(21,5,$registros -> fecha,0,0,'L');
								
								$this->Cell(27,5,'Sucursal Destino:',0,0,'L');
								$this->Cell(43,5,$registros -> sucursal_destino,'R',1,'L');
								
								$this->Cell(22,5,utf8_decode('Dirección: '),'L',0,'L');
								$this->MultiCell(168, 3, $registros -> direccion, 'R', 'L');
								}
						else if($registros -> id_subtipo == 70055){
								$this->Cell(100,5, 'Fecha:',0,0,'R');
								$this->Cell(26,5,$registros -> fecha,'R',1,'L');
								$this->Cell(22,5,utf8_decode('Documento: '),'L',0,'L');
								$this->Cell(168,5, $registros -> documento,'R',1,'L');
								}
						else if($registros -> id_subtipo == 70006){
								$this->Cell(35,5, 'Fecha:',0,0,'R');
								$this->Cell(21,5,$registros -> fecha,0,0,'L');
								
								$this->Cell(27,5,'Sucursal Origen:',0,0,'L');
								$this->Cell(43,5,$registros -> sucursal_origen,'R',1,'L');
								
								$this->Cell(22,5,utf8_decode('Dirección: '),'L',0,'L');
								$this->MultiCell(168, 3, $registros -> direccion, 'R', 'L');
								}
						else if($registros -> id_subtipo == 70011 || $registros -> id_subtipo == 70088)
						{
								$this->Cell(35,5, 'Fecha:',0,0,'R');
								$this->Cell(21,5,$registros -> fecha,0,0,'L');
								
								$this->Cell(27,5,'',0,0,'L');
								$this->Cell(43,5,'','R',1,'L');
								
								$this->Cell(22,5,utf8_decode('Proveedor: '),'L',0,'L');
								$this->MultiCell(168, 5, $registros -> razon_social, 'R', 'L');
								
								$this->Cell(22,5,utf8_decode('Dirección: '),'L',0,'L');
								$this->MultiCell(168, 5, $registros -> direccion, 'R', 'L');
								
								}
						
						}
				$this->Cell(190,2,'','LBR',1,'C');
				$this -> Ln(5);
				}
		function cabeceraTabla(){
				$this -> SetLineWidth(0.2);
				$this -> SetDrawColor(199);
				$this->SetFont('Arial','B',7);
				$this->Cell(15,10, 'CANTIDAD',1,0,'C');
				$this->Cell(35,10, 'SKU','TBR',0,'C');
				$this->Cell(65,10, utf8_decode('DESCRIPCIÓN'),'TBR',0,'C');
				$this->Cell(25,10, 'LOTE','TBR',0,'C');
				$this->Cell(50,10, utf8_decode('OBSERVACIONES'),'TBR',1,'C');
				}
				
		function tablaProductos($prod){
				$this->cabeceraTabla();
				$inicialY = $this-> GetY();
				$linea = $inicialY;
					foreach($prod as $datos){
							$this->SetFont('Arial','',7);
							$this->SetXY(10, $inicialY);
							$this->Cell(15,10, $datos -> cantidad,0,0,'C');
							
							$this->SetXY(30, $inicialY);
							$this->Cell(35,10, $datos -> sku,0,0,'L');
							
							$this->SetXY(62, $inicialY + 3);
							$this->MultiCell(62, 3, $datos -> producto. ' ,  '.$datos -> marca, 0, 'L');
							
							
							$this->SetXY(125, $inicialY );
							//$this->Cell(25, 10, $datos -> lote, 0, 0, 'L');
							$this->MultiCell(25, 3, $datos -> lote, 0, 'L');
							
							$this->SetXY(150, $inicialY + 3);
							$this->MultiCell(50, 3, $datos -> observaciones, 0, 'L');
							
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
					
					if($Yfinal >= 225)
							$dibuja = 270;
					else
							$dibuja = 225;
					
					$resto = $dibuja - $Yfinal;
					$this->Cell(190,$resto, '','LBR',1,'L');
					
				
				}
		function observaciones($observaciones){
				$cadena = recortaTexto($observaciones);
				$partes = explode("-", $cadena);
				
				$this -> SetLineWidth(0.2);
				$this -> SetDrawColor(199);
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
		function firmas($tipo, $usuario){
				
				$this->Cell(63,5, utf8_decode("USUARIO"),0,2,'C');
				$this->Cell(63,5, $usuario,0,0,'C');
				$posLinea = $this -> GetY() + 15;
				$this -> Line(10, $posLinea, 73, $posLinea);
				if($tipo == 70099){
						$this -> SetXY(75, $posLinea - 20);
						$this->Cell(64,7, utf8_decode("RECIBIÓ"),0,0,'C');
						$this -> Line(75, $posLinea, 137, $posLinea);
						
						$this -> SetXY(135, $posLinea - 20);
						$this->Cell(63,7, utf8_decode("ENTREGÓ"),0,0,'C');
						$this -> Line(140, $posLinea, 200, $posLinea);
						}
				else if($tipo == 70004 || $tipo == 70006){
						$this -> SetXY(135, $posLinea - 20);
						$this->Cell(64,7, utf8_decode("REVISÓ"),0,0,'C');
						$this -> Line(140, $posLinea, 200, $posLinea);
						}
				else if($tipo == 70066 || $tipo == 70055){
						$this -> SetXY(75, $posLinea - 20);
						$this->Cell(64,7, utf8_decode("AUTORIZÓ"),0,0,'C');
						$this -> Line(75, $posLinea, 137, $posLinea);
						
						$this -> SetXY(135, $posLinea - 20);
						$this->Cell(63,7, utf8_decode("RECIBIÓ"),0,0,'C');
						$this -> Line(140, $posLinea, 200, $posLinea);
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
$pdf -> datosMovimiento($informacion);
$pdf -> tablaProductos($productos);
$posFinalTabla = $pdf -> GetY();
if($posFinalTabla <= 225){
		$pdf -> SetXY(10, 225);
		}
else{
		$pdf->AddPage();
		$pdf -> SetXY(10, 225);
		}
$pdf -> observaciones($datosTipo['observaciones']);
$pdf->firmas($datosTipo['id_subtipo_movimiento'], $datosTipo['usuario']);

$pdf -> Output();

?>