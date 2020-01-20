<?php

class PDF extends FPDF{	
	function encabezado(){
			
			Global $logo;
			Global $almacen;
			Global $vale;
			Global $folio;
			
			
			$this -> SetLineWidth(0.2);
			$this -> SetDrawColor(199);
			$this -> Image($logo,10,10,45, 20,'PNG');
			$this->SetFillColor(63);
			$this->SetFont('Arial','B',12);
			$this->Cell(120);
			$this->Cell(70,1,'','LTR',1,'C');
			$this->Cell(120);
			$this->Cell(70,5,utf8_decode('Comunicación y Entretenimiento'),'LR',1,'C');
			$this->Cell(120);
			$this->Cell(70,5,utf8_decode('AUDICEL, S.A. de C.V'),'LR',1,'C');
			$this->Cell(120);
			$this->Cell(70,1,'','LBR',1,'C');
			$this->Cell(120);
			$this->SetFont('Arial','B',9);
			$this->Cell(70,4,"C O N T R A   R E C I B O",'LBR',1,'C');
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
			$this -> SetFont('Arial','B',7);
			foreach($ordenDatos as $registros){
					$this->Cell(190,1,'','LTR',1,'C');
					$this->Cell(190,5,'Plaza: '.utf8_decode($registros -> nombre_sucursal),'LR',1,'l');
					$this->Cell(190,5,utf8_decode('Dirección: ').$registros -> dir_sucursal,'LR',1,'l');
					$this->Cell(190,5,'Tels. '.$registros -> telefono_1.' / '.$registros -> telefono_2,'LBR',1,'l');
					$this->Cell(190,2,'','LTR',1,'C');
					$this->SetFont('Arial','B',8);
					$this->Cell(115,5,"Contra Recibo: " . $registros -> id_contrarecibo,'L',0,'L');
					$this->Cell(75,5, 'Fecha:   ' . $registros -> fecha_hora ,'R',1,'L');
					if($registros -> id_tipo_cliente_proveedor != '4')
						$this->Cell(190,5,"Distribuidor: " . $registros -> distribuidor,'LR',1,'L');
					else
						$this->Cell(190,5,"Empleado: " . $registros -> distribuidor,'LR',1,'L');
					$this->Cell(95,5,"Clave: " . $registros -> clave,'L',0,'L');
					$this->Cell(95,5,"DI: " . $registros -> di,'R',1,'L');
					$this->MultiCell(190,5,utf8_decode("Dirección del Distribuidor:   "). $registros -> dir_distribuidor,'LR','L');
					$this->Cell(190,2,'','LBR',1,'C');
					$this -> Ln(5);
					}
			
			}
	function cabeceraTabla(){
		$this -> SetLineWidth(0.2);
		$this -> SetTextColor(255);
		$this -> SetDrawColor(199);
		$this -> SetFillColor(0,38,89);
		$this->SetFont('Arial','B',6);
		$this->Cell(63.3,10, 'CONTRATO','LTBR',0,'C',1);
		$this->Cell(63.3,10, 'CUENTA',1,0,'C',1);
		$this->Cell(63.3,10, utf8_decode('FECHA ACTIVACIÓN'),'TBR',1,'C',1);
	}	
	function tablaProductos($detalles){
			$this->cabeceraTabla();
			$this -> SetTextColor(0);
			$inicialY = $this-> GetY();
			$linea = $inicialY;
				foreach($detalles as $datos){
						$this->SetFont('Arial','B',7);
						$this->SetXY(10, $inicialY);
						$this->Cell(63.3,10, $datos -> contrato,0,0,'C');
						
						$this->SetXY(73.3, $inicialY);
						$this->Cell(63.3, 10, $datos -> cuenta,0, 0, 'C');
						
						$this->SetXY(136.6, $inicialY );
						$this->Cell(63.3, 10, $datos -> fecha_activacion, 0, 1, 'C');
						
						$actualY = $this-> GetY();  //Obtenemos el valor de Y despues de la multicelda ya que este tendra el valor donde iniciara la siguiente fila
						if($actualY > 256){
								$ypag = $this-> GetY();
								$this -> Line(10, $linea, 10, $ypag); //Linea que completa el lado izquierdo de la tabla
								$this -> Line(200, $linea, 200, $ypag); //Linea que completa el lado derecho de la tabla
								$resto = 4;
								$this->Cell(190,$resto, '','LBR',1,'L');
								$this -> AddPage();
								$inicialY = 20;
								$this->cabeceraTabla();
								$this -> SetTextColor(0);
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
				$this -> Cell(190,$resto, '','LBR',1,'L');
				
			}
	function observaciones($observaciones){
		$this -> SetTextColor(0);
		$this -> SetLineWidth(0.2);
		$this -> SetDrawColor(199);
		foreach($observaciones as $obs){
			if($obs -> nombre_entrego == '')
				$persona = 'N/A';
			else 
				$persona = $obs->nombre_entrego;
			$this -> SetLineWidth(0.2);
			$this -> SetDrawColor(199);
			$this->SetFont('Arial','B',8);
			$this->Cell(190,5, "Persona que entrego Contratos: ".$persona,0,1,'L');
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
$pdf -> datosOrden($contrarecibos);
$pdf -> tablaProductos($contrareciboDet);
$pdf -> observaciones($contrarecibos);
//$pdf -> totales($ordenCompra);
$pdf -> Output();

?>