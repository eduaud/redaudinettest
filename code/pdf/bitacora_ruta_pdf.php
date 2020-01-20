<?php

class PDF extends FPDF{
		function Header(){
				Global $logo;
				Global $bitacora;
				$this->Image($logo,10,15,50);
				$this->SetFont('Arial','B',13);
				$this->SetXY(100, 25);
				$this->Cell(110,7,utf8_decode('BITÁCORA DE RUTAS'),0,0,'C');
				$this->SetFont('Arial','B',10);
				$this->Cell(54,7,utf8_decode('No. ') . $bitacora,0,1,'R');
				$this->Ln(5);
				}
		function datosBitacora($bitacora){
				foreach($bitacora as $datos){
						$this->SetFont('Arial','',8);
						$this->SetXY(10, 45);
						$this->Cell(35,7, 'FECHA:',0,0,'L');
						$this->Cell(125,7, $datos -> fecha_bitacora,0,1,'L');
						
						$this->Cell(35,7, 'CHOFER:',0,0,'L');
						$this->Cell(125,7, $datos -> chofer,0,0,'L');
						
						$this->Cell(35,7, utf8_decode('CAMIÓN:'),0,0,'L');
						$this->Cell(50,7, $datos -> camion,0,1,'L');
						
						$this->Cell(35,7, 'KM INICIAL:',0,0,'L');
						$this->Cell(125,7, $datos -> km_iniciales . " km",0,0,'L');
						$this->Cell(35,7, utf8_decode('KM FINAL:'),0,0,'L');
						$this->Cell(50,7, $datos -> km_finales . " km",0,1,'L');
						
						$this->Cell(35,7, 'HORA DE SALIDA:',0,0,'L');
						$this->Cell(125,7, $datos -> hora_salida,0,0,'L');
						$this->Cell(35,7, utf8_decode('HORA DE LLEGADA:'),0,0,'L');
						$this->Cell(50,7, $datos -> hora_llegada,0,1,'L');
						
						$this->Cell(35,7, 'AYUDANTE:',0,0,'L');
						$this->Cell(125,7, $datos -> ayudante,0,1,'L');
						$this->Ln(5);
						}
				}
		function encabezadoEntrega(){
				$this -> SetLineWidth(0.3);
				$this -> SetDrawColor(199);
				$this->SetFont('Arial','B',7);
				$this->Cell(30,7, 'E N T R E G A S',0,1,'L');
				$this->SetFont('Arial','B',6);
				
				$this->Cell(10,7, '#',1,0,'C');
				$this->Cell(25,7, 'RUTA','TRB',0,'C');
				$this->Cell(25,7, 'TIPO','TRB',0,'C');
				$this->Cell(25,7, 'FECHA DE ENTREGA','TRB',0,'C');
				$this->Cell(40,7, 'CLIENTE/TIENDA','TRB',0,'C');
				$this->Cell(40,7, utf8_decode('DIRECCIÓN DE ENTREGA'),'TRB',0,'C');
				$this->Cell(25,7, 'HORA DE ENTREGA','TRB',0,'C');
				$this->Cell(25,7, 'HORA DE SALIDA','TRB',0,'C');
				$this->Cell(40,7, 'OBSERVACIONES','TRB',0,'C');
				$this->Cell(20,7, 'FIRMA',1,1,'C');
				}
		function bitacoraEntrega($entregas){
				$this->encabezadoEntrega();
				$this->SetFont('Arial','',7);
				$inicialY = $this-> GetY();
				$linea_columna = $inicialY;
				foreach($entregas as $datos){
						$this->SetXY(10, $inicialY);
						$this->Cell(10,7, $datos -> orden_entrega,0,0,'C');
						
						$this->SetXY(20, $inicialY);
						$this->Cell(25,7, $datos -> ruta,0,0,'C');
						
						$this->SetXY(45, $inicialY + 2);
						$this->MultiCell(25, 3, $datos -> tipo, 0, 'C');
						$valorY[] = $this-> GetY();  //Obtenemos el valor en donde queda la posicion de la multicelda
						
						$this->SetXY(70, $inicialY);
						$this->Cell(25,7, $datos -> fecha_entrega,0,0,'C');
						
						$this->SetXY(95, $inicialY + 2);
						$this->MultiCell(40, 3, $datos -> cliente_tienda, 0, 'C');
						$valorY[] = $this-> GetY();  //Obtenemos el valor en donde queda la posicion de la multicelda
						
						$this->SetXY(135, $inicialY + 2);
						$this->MultiCell(40, 3, $datos -> direccion_entrega, 0, 'C');
						$valorY[] = $this-> GetY();  //Obtenemos el valor en donde queda la posicion de la multicelda
						
						$this->SetXY(175, $inicialY);
						$this->Cell(25,7, $datos -> hora_entrega,0,0,'C');
						
						$this->SetXY(200, $inicialY);
						$this->Cell(25,7, $datos -> hora_salida,0,0,'C');
						
						$this->SetXY(225, $inicialY + 2);
						$this->MultiCell(40, 3, $datos -> observaciones, 0, 'C');
						$valorY[] = $this-> GetY();  //Obtenemos el valor en donde queda la posicion de la multicelda
						
						$this->SetXY(265, $inicialY);
						$this->Cell(20,7, "",0,1,'C');
						
						$actualY = max($valorY);
						$this->Line(267, $actualY - 1, 283, $actualY - 1); //Linea de firma
						
						$nuevaY = $actualY - $inicialY; // Hacemos la convercion para obtener la suma de la siugiente posicion
						$inicialY +=  $nuevaY;		// Le asignamos a Y inicial el nuevo valor de la siguiente fila
						
						}
						
						$this->Line(10, $linea_columna, 10, $actualY + 2); //Linea de columnas
						$this->Line(20, $linea_columna, 20, $actualY + 2); //Linea de columnas
						$this->Line(45, $linea_columna, 45, $actualY + 2); //Linea de columnas
						$this->Line(70, $linea_columna, 70, $actualY + 2); //Linea de columnas
						$this->Line(95, $linea_columna, 95, $actualY + 2); //Linea de columnas
						$this->Line(135, $linea_columna, 135, $actualY + 2); //Linea de columnas
						$this->Line(175, $linea_columna, 175, $actualY + 2); //Linea de columnas
						$this->Line(200, $linea_columna, 200, $actualY + 2); //Linea de columnas
						$this->Line(225, $linea_columna, 225, $actualY + 2); //Linea de columnas
						$this->Line(265, $linea_columna, 265, $actualY + 2); //Linea de columnas
						$this->Line(285, $linea_columna, 285, $actualY + 2); //Linea de columnas
						$this->Line(10, $actualY + 2, 285, $actualY + 2); //Linea de cerrado de tabla
						$this->Ln(5);
				}
				
			function encabezadoManual(){
				$this->SetFont('Arial','B',7);
				$this->Cell(30,7, 'E N T R E G A S    M A N U A L E S',0,1,'L');
				$this->SetFont('Arial','B',6);
				
				$this->Cell(10,7, '#',1,0,'C');
				$this->Cell(35,7, 'RUTA','TRB',0,'C');
				$this->Cell(30,7, 'FECHA DE ENTREGA','TRB',0,'C');
				$this->Cell(55,7, utf8_decode('DIRECCIÓN DE ENTREGA'),'TRB',0,'C');
				$this->Cell(30,7, 'HORA DE ENTREGA','TRB',0,'C');
				$this->Cell(30,7, 'HORA DE SALIDA','TRB',0,'C');
				$this->Cell(55,7, 'OBSERVACIONES','TRB',0,'C');
				$this->Cell(30,7, 'FIRMA',1,1,'C');
				}	
			function bitacoraManual($manuales){
				$this->encabezadoManual();
				$this->SetFont('Arial','',6);
				$inicialY = $this-> GetY();
				$linea_columna = $inicialY;
				foreach($manuales as $datos){
						$this->SetXY(10, $inicialY);
						$this->Cell(10,7, $datos -> orden_manual,0,1,'C');
						
						$this->SetXY(20, $inicialY);
						$this->Cell(35,7, $datos -> ruta,0,0,'C');
						
						$this->SetXY(55, $inicialY);
						$this->Cell(30,7, $datos -> fecha_manual,0,0,'C');
						
						$this->SetXY(85, $inicialY + 2);
						$this->MultiCell(55, 3, $datos -> direccion_entrega_manual, 0, 'C');
						$valorY[] = $this-> GetY();  //Obtenemos el valor en donde queda la posicion de la multicelda
						
						$this->SetXY(140, $inicialY);
						$this->Cell(30,7, $datos -> hora_entrega_manual,0,0,'C');
						
						$this->SetXY(170, $inicialY);
						$this->Cell(30,7, $datos -> hora_salida_manual,0,0,'C');
						
						$this->SetXY(200, $inicialY + 2);
						$this->MultiCell(55, 3, $datos -> observaciones_manual, 0, 'C');
						$valorY[] = $this-> GetY();  //Obtenemos el valor en donde queda la posicion de la multicelda
						
						$this->SetXY(255, $inicialY);
						$this->Cell(30,7, "",0,1,'C');
						
						$actualY = max($valorY);
						$this->Line(257, $actualY - 1, 283, $actualY - 1); //Linea de firma
						$nuevaY = $actualY - $inicialY; // Hacemos la convercion para obtener la suma de la siugiente posicion
						$inicialY +=  $nuevaY;		// Le asignamos a Y inicial el nuevo valor de la siguiente fila
						}
						
						$this->Line(10, $linea_columna, 10, $actualY + 2); //Linea de columnas
						$this->Line(20, $linea_columna, 20, $actualY + 2); //Linea de columnas
						$this->Line(55, $linea_columna, 55, $actualY + 2); //Linea de columnas
						$this->Line(85, $linea_columna, 85, $actualY + 2); //Linea de columnas
						$this->Line(140, $linea_columna, 140, $actualY + 2); //Linea de columnas
						$this->Line(170, $linea_columna, 170, $actualY + 2); //Linea de columnas
						$this->Line(200, $linea_columna, 200, $actualY + 2); //Linea de columnas
						$this->Line(255, $linea_columna, 255, $actualY + 2); //Linea de columnas
						$this->Line(285, $linea_columna, 285, $actualY + 2); //Linea de columnas
						$this->Line(10, $actualY + 2, 285, $actualY + 2); //Linea de cerrado de tabla
				}
		
		function Footer(){
				$this->SetY(-10);
				$this->SetFont('Arial','I',8);
				$this->Cell(0,10, utf8_decode('Página ') . $this->PageNo().' de {nb}',0,0,'C');
				}
		}
				
		
		
$pdf=new PDF('L','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->datosBitacora($datosBitacora);
$pdf->bitacoraEntrega($bitacoraEntrega);

$finalEntrega = $pdf-> GetY();

if($finalEntrega >= 170){
		$pdf->AddPage();
		}

/*$pdf->SetXY(10, 10);
$pdf->Cell(30,7, $finalEntrega,0,1,'C');*/

//$pdf->bitacoraManual($bitacoraManual);
$pdf->Output();
?>