<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$tabla = $_GET['tabla'];
$campo = $_GET['campo'];
$dato = $_GET['dato'];

if(isset($_FILES['file']['tmp_name']) && isset($_FILES['file2']['tmp_name'])){
	$tmp_name1 = $_FILES['file']['tmp_name'];
	$tmp_name2 = $_FILES['file2']['tmp_name'];
	
	$posiciones1 = $_GET['posiciones1'];
	$posiciones2 = $_GET['posiciones2'];
	
	$arrposiciones1=explode(',',$posiciones1);
	$arrposiciones2=explode(',',$posiciones2);
	
	$errores='';
	if (($gestor1 = fopen($tmp_name1, "r")) !== FALSE && ($gestor2 = fopen($tmp_name2, "r")) !== FALSE) {
		$encabezado2 = fgetcsv($gestor2, 1000, ",");
		$fila2=2;
		while(($datos2 = fgetcsv($gestor2, 1000, ",")) !== FALSE){
			$encabezado1 = fgetcsv($gestor1, 1000, ",");
			//$valoresPosicion=array();
			
			$posi=array();
			$fila1=2;
			while(($datos1 = fgetcsv($gestor1, 1000, ",")) !== FALSE){
				for($i = 0; $i < count($arrposiciones2); $i++){
					if($datos1[$arrposiciones1[$i]] == $datos2[$arrposiciones2[$i]]){
						if(! in_array($arrposiciones2[$i], $posi)){
							array_push($posi,$arrposiciones2[$i]);
						}
					}
				}
				$fila1++;
			}
			
			for($j = 0; $j < count($arrposiciones2); $j++){
				if(! in_array($arrposiciones2[$j], $posi)){
					$errores.='Archivo 2 Fila: '.$fila2.' Campo: '.$encabezado2[$arrposiciones2[$j]].' Valor: '.$datos2[$arrposiciones2[$j]].' No coincide con los valores del archivo 1
';
				}
			}
			
			rewind($gestor1);
			$fila2++;
		}
		fclose($gestor1);
		fclose($gestor2);
	}
	echo $errores;
	
} elseif(isset($_FILES['file']['tmp_name'])){
	$arrCamposWhere=explode(',',$campo);
	
	$filtro='';
	if($dato != ""){
		$arrDatosWhere=explode(',',$dato);
		
		for($j=0; $j< count($arrCamposWhere); $j++){
			if($filtro == ''){
				$filtro .= ' WHERE '.$arrCamposWhere[$j].'="'.$arrDatosWhere[$j].'"';
			} else {
				$filtro .= ' AND '.$arrCamposWhere[$j].'="'.$arrDatosWhere[$j].'"';
			}
		}
	}
	
	if(isset($_GET['campoAcomparar'])){
		$campoAcomparar=$_GET['campoAcomparar'];
	} else {
		/***   Solo se puede comparar un campo, por lo cual se tomara el primero   ***/
		$campoAcomparar=$arrCamposWhere[0];
	}
	
	$posicion=$_GET['posicion'];
	$tmp_name = $_FILES['file']['tmp_name'];
	$sql='SELECT '.$campoAcomparar.' FROM '.$tabla.$filtro;
	$datos = new consultarTabla($sql);
	$result = $datos-> obtenerArregloRegistros();
	$IDremesa='';
	if (($gestor = fopen($tmp_name, "r")) !== FALSE) {
		for($i=0;$i<count($result);$i++){
			$datosCSV = fgetcsv($gestor, 1000, ",");
			while (($datosCSV = fgetcsv($gestor, 1000, ",")) !== FALSE){
				if($result[$i][0]==$datosCSV[$posicion]){
					if(strpos($IDremesa,$datosCSV[$posicion])=== false){
						if($IDremesa == '')
							$IDremesa .= $datosCSV[$posicion];
						else 
							$IDremesa .= ','.$datosCSV[$posicion];
					}
				}
			}
			rewind($gestor);
		}
		fclose($gestor);
	}
	echo $IDremesa;
} else {
	$filtro='';
	if($campo != ""){
		$arrCamposWhere=explode(',',$campo);
		$arrDatosWhere=explode(',',$dato);
		
		for($j=0; $j< count($arrCamposWhere); $j++){
			if($filtro == ''){
				$filtro .= $arrCamposWhere[$j].'="'.$arrDatosWhere[$j].'"';
			} else {
				$filtro .= ' AND '.$arrCamposWhere[$j].'="'.$arrDatosWhere[$j].'"';
			}
		}
	}
	
	$queryVVBD='SELECT '.$campo.' FROM '.$tabla.' WHERE '.$filtro;
	$resultVVBD=mysql_query($queryVVBD);

	if(mysql_num_rows($resultVVBD) > 0) echo 'si';
	else echo 'no';
}
?>