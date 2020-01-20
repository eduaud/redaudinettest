<?php

class PDF extends FPDF{
		function Girar($angulo=0,$x=-1,$y=-1){ 
				if($x==-1)
						$x=$this->x; 
				if($y==-1)
						$y=$this->y; 
				if($this->angulo!=0)
						$this->_out('Q'); 
				$this->angulo=$angulo; 
				
				if($angulo!=0){ 
						$angulo*=M_PI/180; 
						$c=cos($angulo); 
						$s=sin($angulo); 
						$cx=$x*$this->k; 
						$cy=($this->h-$y)*$this->k; 

						$this->_out(sprintf('q %.5f %.5f %.5f %.5f %.2f %.2f cm 1 0 0 1 %.2f %.2f cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy)); 
						} 
				}
		}

$pdf=new PDF($orientation='P',$unit='mm',$format='letter');

foreach($datos as $datoC){
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',12);
		
		if($datoC -> promocion > $datoC -> precio){
				$precio = $datoC -> promocion;
				$promocion = $datoC -> promocion;
				}
		else if($datoC -> precio > $datoC -> promocion){
				$precio = $datoC -> precio;
				$promocion = $datoC -> promocion;
				}
		else if($datoC -> precio == $datoC -> promocion){
				$precio = $datoC -> precio;
				$promocion = $datoC -> precio;
				}
		
		$pdf-> SetXY(80, 42);
		$pdf->SetFont('Arial','B',33);
		$pos = strpos($precio, ".");
		$precio_final = substr($precio, 0, $pos); 
		$precio_final = number_format($precio_final,2);
		
		$pdf->Cell(90,13,"$" . $precio_final, 0, 1);
		
		if(strlen($datoC -> producto) > 82)
				$yProd = 66;
		else if(strlen($datoC -> producto) > 46)
				$yProd = 67;
		else
			$yProd = 67;
			
		$pdf-> SetXY(55,$yProd);
		$pdf->SetFont('Arial','B',12);
		$pdf->MultiCell(110, 5, $datoC -> producto, 0, 'L');
		
		$pdf-> SetXY(90, 88);
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(100, 15,$datoC -> modelo, 0, 1);
		
		$pdf-> SetXY(100, 110);
		
		$piezas = $datoC -> cantidad;
		if(strlen($piezas) == 1)
				$piezas = "0" . $piezas;
		
		$pdf->Cell(100,15,$piezas, 0, 1);
		
		$pdf-> SetXY(85, 135);
		$pdf->Cell(100,15,$datoC -> sku, 0, 1);
		
	/*	$pdf-> SetXY(80, 168);
		$pdf->SetFont('Arial','B',33);
		$pdf->Cell(100,15,"$" . number_format($promocion,2), 0, 1);*/
		
		$pdf-> SetXY(90, 186);
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(100,15,$datoC -> marca, 0, 1);
		
		
		$pdf-> SetXY(169, 180);
		$pdf->SetFont('Arial','B',6);
		$pdf->Girar(90);
		$pdf->Cell(120,5,$lista . " FI: " . date('d-m-Y H:i:s'),0,1,'C');
		}

//$pdf->Cell(40,10,$productos);

$pdf->Output();


?>