<?php

class PDF extends FPDF{
		//Cabecera de página
		function Header(){
				Global $logo;
				Global $numero_recibo;
				$this -> SetLineWidth(0.2);
				$this -> SetDrawColor(199);
				$this->SetFont('Arial','B',7);
				$this->Cell(40,4,utf8_decode('Diseños Exclusivos'),'LTR',1,'C');
				$this->Cell(40,4,utf8_decode('Nasser, S.A. de C.V'),'LR',1,'C');
				$this->Cell(40,4,utf8_decode('R.F.C.: DEN-030320-560'),'LBR',1,'C');
				$this->Image($logo,72,10,60);
				$this->SetXY(155, 10);
				$this->SetFont('Arial','B',12);
				$this->Cell(50,7,utf8_decode('VALE DE PRODUCTO'),1,2,'C');
				$this->SetFont('Arial','B',9);
				$this->Cell(50,9,utf8_decode('No. ') . $numero_recibo,'LBR',2,'C');
				$this->SetFont('Arial','B',6);
				$this->Cell(50,4,utf8_decode('MÉXICO, D.F. A:'),'LBR',2,'C');
				$this->SetFont('Arial','',6);
				$this->Cell(16.6,4,utf8_decode('DÍA'),'L',0,'C');
				$this->Cell(16.7,4,utf8_decode('MES'),0,0,'C');
				$this->Cell(16.7,4,utf8_decode('AÑO'),'R',1,'C');
				$this->SetFont('Arial','B',9);
				$this->Cell(145);
				$this->Cell(50,8, date('d/m/Y'), 'LBR',1,'C');
				$this->Ln(2);
				}
		function datosVale($datos){
				$this->SetFont('Arial','B', 7);
				foreach($datos as $inicio){
						$this->MultiCell(195, 5, utf8_decode($inicio -> direccion), 1);
						$this->Cell(15,5, 'Cliente:', 0,0,'L');
						$this->Cell(130,5, $inicio -> cliente, 0,0,'L');
						$this->Cell(15,5, 'Pedido:', 0,0,'L');
						$this->Cell(20,5, $inicio -> pedido, 0,1,'L');
						$this->Cell(15,5, 'Monto:', 0,0,'L');
						$this->Cell(20,5, $inicio -> monto, 0,1,'L');
						$this->Ln(.5);
						}
				}
		function cabeceraTabla(){
				$this->SetFont('Arial','B',6);
				$this->Cell(100,7, 'PRODUCTO','LTB',0,'C');
				$this->Cell(25,7, 'CANTIDAD',1,0,'C');
				$this->Cell(70,7, 'OBSERVACIONES','TBR',1,'C');
				}
		
		function cuerpoVale($productos){
				$this -> cabeceraTabla();
				$this->SetFont('Arial','',6);
				foreach($productos as $datos){
						$this->Cell(100,5, $datos -> producto,'LR',0,'C');
						$this->Cell(25,5, $datos -> cantidad,'R',0,'C');
						$this->Cell(70,5, $datos -> observaciones,'R',1,'C');
						
						}
				$Yfinal = $this-> GetY();
				$this -> Line(10, $Yfinal, 205, $Yfinal);
				}
		}

//Creación del objeto de la clase heredada
$pdf=new PDF($orientation='P',$unit='mm',$format='mcarta');
$pdf->AddPage();
$pdf -> SetLineWidth(0.2);
$pdf -> SetDrawColor(199);
$pdf -> datosVale($result);
$pdf -> cuerpoVale($result2);
$pdf->Output();
?>