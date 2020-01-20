<?php
	include("../../conect.php");
	extract($_POST);
	extract($_GET);
	$campo_nombre = utf8_decode($campo);
	$valor = strtoupper($valor);  
    $condicion="";
		
	//echo $sucursal;	
    if($sucursal != -1 && $sucursal != ''){
	$col = array();
		$resultado = mysql_query("SHOW COLUMNS FROM $tabla");
	if (!$resultado) {
		echo 'No se pudo ejecutar la consulta: ' . mysql_error();
		exit;
	}
	if (mysql_num_rows($resultado) > 0) {
		while ($fila = mysql_fetch_array($resultado)) {
			$col[count($col)] = $fila[0];  
		}
	}


     for($i=0; $i<count($col); $i++){
	     
		 if($col[$i] == 'id_sucursal'){
		   // echo $col[$i];
			$condicion = ' AND '.$tabla.'.id_sucursal = '.$sucursal;
		 }
	 }
	} 
	 
	$sql = "SELECT DISTINCT $campo_nombre FROM $tabla WHERE UPPER($campo_nombre) LIKE '$valor%' ".$condicion." LIMIT 15";
	//echo $sql;
	$ref = @mysql_query($sql);
	$datos = array();	
	if(mysql_num_rows($ref)>0)
	{
		$j=0;
		while($linea = mysql_fetch_row($ref))
		{
			if($j>0)
				echo "|";
			for($i=0;$i<count($linea);$i++)
			{
				if($i>0)
					echo "~";
				echo utf8_encode($linea[0]); 
			}
			$j++;
		}
			
	}
	mysql_free_result($ref);
//	$search_queries = $datos;
//	$results = search($search_queries, $q);
	//sendResults($datos);

function search($search_queries, $query) {
	if(strlen($query) == 0)
		return $search_queries;
	$query = strtolower($query);
	$results = array();
	for($i = 0; $i < count($search_queries); $i++){
		if(strcasecmp(substr($search_queries[$i],0,strlen($query)),$query) == 0)
			array_push($results,$search_queries[$i]);
	}
	return $results;
}

function sendResults($results) {
	die('["'.implode('","',array_map('utf8_encode',$results)).'"]');
//	for ($i = 0; $i <count($results); $i++)
//		print "$results[$i]|$results[$i]\n";
}
?>