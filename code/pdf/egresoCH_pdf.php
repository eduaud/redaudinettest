<?php

class PDF extends FPDF{
		
		function Header(){
				Global $logo;
				Global $sucursal;
				Global $nuevo_recibo;
				Global $fecha;
				$this -> SetLineWidth(0.2);
				$this -> SetDrawColor(199);
				$this->SetFont('Arial','B',7);
				$this->Cell(40,4,utf8_decode('Diseños Exclusivos'),'LTR',1,'C');
				$this->Cell(40,4,utf8_decode('Nasser, S.A. de C.V'),'LR',1,'C');
				$this->Cell(40,4,utf8_decode('R.F.C.: DEN-030320-560'),'LBR',1,'C');
				$this->SetFont('Arial','B',6);
				$this->Cell(40,4,utf8_decode("SUCURSAL " . $sucursal),'LBR',1,'C');
				$this->Image($logo,72,10,60);
				$this->SetXY(155, 10);
				$this->SetFont('Arial','B',12);
				$this->Cell(50,6,utf8_decode('RECIBO'),1,2,'C');
				$this->SetFont('Arial','B',9);
				$this->Cell(50,9,utf8_decode('No. ') . $nuevo_recibo,'LBR',2,'C');
				$this->SetFont('Arial','B',6);
				$this->Cell(50,4,utf8_decode('MÉXICO, D.F. A:'),'LBR',2,'C');
				$this->SetFont('Arial','',6);
				$this->Cell(16.6,4,utf8_decode('DÍA'),'L',0,'C');
				$this->Cell(16.7,4,utf8_decode('MES'),0,0,'C');
				$this->Cell(16.7,4,utf8_decode('AÑO'),'R',1,'C');
				$this->SetFont('Arial','B',9);
				$this->Cell(145);
				$this->Cell(50,8, $fecha, 'LBR',1,'C');
				$this->Ln(2);
				}
		function Cuerpo($registros){
				Global $prefijo;
				Global $nuevo_recibo;
				$this -> SetLineWidth(0.2);
				$this -> SetDrawColor(199);
				$this->Cell(195,4,utf8_decode(''),'LTR',1,'C');
				$this->SetFont('Arial','B',8);
				foreach($registros as $dato){
						//NOMBRE Y FOLIO
						$this->Cell(4,6,utf8_decode(''),'L',0,'C');
						$this->Cell(38,6,utf8_decode('RECIBIMOS DE:'),0,0,'L');
						$this->Cell(112,6,utf8_decode($dato -> usuario_nombre),0,0,'L');
						$this->Cell(12,6,utf8_decode('FOLIO:'),0,0,'L');
						$this->Cell(29,6,utf8_decode($prefijo . $nuevo_recibo),'R',1,'L');
						//CANTIDAD
						$this->Cell(4,6,utf8_decode(''),'L',0,'C');
						$this->Cell(38,6,utf8_decode('BUENO POR:'),0,0,'L');
						$this->Cell(153,6,utf8_decode("$" . number_format($dato -> total_egreso, 2)),'R',1,'L');
						//LETRA
						$this->Cell(4,6,utf8_decode(''),'L',0,'C');
						$this->Cell(38,6,utf8_decode('CANTIDAD CON LETRA:'),0,0,'L');
						$this->Cell(153,6,utf8_decode(num2texto($dato -> total_egreso, 'PESOS', 'PESO')),'R',1,'L');
						//FORMA DE PAGO
						$this->Cell(4,6,utf8_decode(''),'L',0,'C');
						$this->Cell(38,6,utf8_decode('FORMA DE PAGO:'),0,0,'L');
						$this->Cell(153,6,utf8_decode('EFECTIVO'),'R',1,'L');
						//CONCEPTO
						$this->Cell(4,6,utf8_decode(''),'L',0,'C');
						$this->Cell(38,6,utf8_decode('CONCEPTO:'),0,0,'L');
						$this->Cell(153,6,$dato -> tipo_egreso,'R',1,'L');
						//DESCRIPCION
						if($dato -> id_tipo_egreso == 1){
								$this->Cell(4,6,utf8_decode(''),'L',0,'C');
								$this->Cell(38,6,utf8_decode('GASTO:'),0,0,'L');
								$this->Cell(153,6,$dato -> gasto,'R',1,'L');
								}
						else if($dato -> id_tipo_egreso == 2){
								$this->Cell(4,6,utf8_decode(''),'L',0,'C');
								$this->Cell(38,6,utf8_decode('SUCURSAL DESTINO:'),0,0,'L');
								$this->Cell(153,6,utf8_decode($dato -> sucursal_destino),'R',1,'L');
								}
						else if($dato -> id_tipo_egreso == 3){
								$this->Cell(4,6,utf8_decode(''),'L',0,'C');
								$this->Cell(38,6,utf8_decode('CUENTA POR COBRAR:'),0,0,'L');
								$this->Cell(153,6,utf8_decode($dato -> cuenta_cobrar),'R',1,'L');
								}
						else if($dato -> id_tipo_egreso == 4){
								$this->Cell(4,6,utf8_decode(''),'L',0,'C');
								$this->Cell(38,6,utf8_decode('DEPOSITO BANCARIO:'),0,0,'L');
								$this->Cell(153,6,utf8_decode($dato -> deposito_bancario),'R',1,'L');
								}
						else if($dato -> id_tipo_egreso == 5){
								$this->Cell(4,6,utf8_decode(''),'L',0,'C');
								$this->Cell(38,6,utf8_decode('PEDIDO:'),0,0,'L');
								$this->Cell(153,6,utf8_decode($dato -> pedido),'R',1,'L');
								}
						//OBSERVACIONES
						$this->Cell(4,6,utf8_decode(''),'L',0,'C');
						$this->Cell(38,6,utf8_decode('OBERVACIONES:'),0,0,'L');
						$this->Cell(153,6,utf8_decode($dato -> observaciones),'R',1,'L');
						
						$this->Cell(195,4,utf8_decode(''),'LR',1,'C');
						//FIRMA
						$valorY = $this-> GetY();
						$this->Line(75, $valorY-1, 140, $valorY-1); //Linea de firma
						$this->Cell(195,4,utf8_decode($dato -> recibio),'LR',1,'C');
						}
				$this->Cell(195,4,utf8_decode(''),'LBR',1,'C');
				}

		}
$pdf=new PDF($orientation='P',$unit='mm',$format='mcarta');
$pdf->AddPage();
$pdf->Cuerpo($datos);
$pdf->Output();
?>