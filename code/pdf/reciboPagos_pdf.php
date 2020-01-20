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
				$this->Cell(50,6,utf8_decode('RECIBO'),1,2,'C');
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
		function datosRecibo($pedido, $cliente, $dirSuc){
				$this->SetFont('Arial','B', 7);
				$this->MultiCell(195, 5, utf8_decode($dirSuc), 1);
				$this->Cell(15,5, 'Cliente:', 0,0,'L');
				$this->Cell(130,5, $cliente, 0,0,'L');
				$this->Cell(15,5, 'Pedido:', 0,0,'L');
				$this->Cell(20,5, $pedido, 0,0,'L');
				$this->Ln();
				}
		function cabeceraTabla(){
				
				$this->SetFont('Arial','B',6);
				$this->Cell(15,7, 'FECHA','LTB',0,'C');
				$this->Cell(32,7, 'FORMA DE PAGO',1,0,'C');
				$this->Cell(25,7, 'SUCURSAL DE PAGO','TBR',0,'C');
				$this->Cell(25,7, 'TERMINAL BANCARIA','TBR',0,'C');
				$this->Cell(25,7, 'BANCO','TBR',0,'C');
				$this->Cell(25,7, 'No. DE DOCUMENTO','TBR',0,'C');
				$this->Cell(25,7, utf8_decode('No. DE APROBACIÓN'),'TBR',0,'C');
				$this->Cell(23,7, 'MONTO','TBR',1,'C');
				}
		function cuerpoRecibo($pagos){
				$this -> cabeceraTabla();
				$this->SetFont('Arial','',6);
				foreach($pagos as $datos){
						$this->Cell(15,5, $datos -> fecha_pago,'LR',0,'C');
						$this->Cell(32,5, $datos -> forma_pago,'R',0,'C');
						$this->Cell(25,5, $datos -> sucursal,'R',0,'C');
						
						$this->Cell(25,5, $datos -> terminal_bancaria ,'R',0,'C');
						$this->Cell(25,5, $datos -> banco  ,'R',0,'C');
						
						$this->Cell(25,5, $datos -> numero_documento,'R',0,'C');
						$this->Cell(25,5, $datos -> numero_aprobacion,'R',0,'C');
						$this->SetFont('Arial','B',9);
						$this->Cell(23,5, $datos -> monto,'R',1,'C');
						$this->SetFont('Arial','',6);
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
$pdf -> datosRecibo($resultP['id_pedido'], $resultP['cliente'], $direccion);
$pdf -> cuerpoRecibo($result);
$pdf->Output();
?>