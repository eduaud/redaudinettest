<?php

class consultarTabla{
		
		private $consulta;

		public function __construct($query){
				//mysql_query("SET NAMES 'utf8'");
				$this->consulta = mysql_query($query) or die ("Error en la consulta: <br>$query. ".mysql_error());
				}

		public function obtenerRegistros(){
				$rows_array = array();
				while($result= mysql_fetch_object($this->consulta)){
						$rows_array[] = $result;
						}
				return $rows_array;
				}
		public function obtenerArregloRegistros(){
				$rows_array = array();
				while($result= mysql_fetch_array($this->consulta)){
						$rows_array[] = $result;
						}
				return $rows_array;
				}
		public function obtenerLineaRegistro(){
				$result = mysql_fetch_assoc($this->consulta);
				return $result;
				}
		public function cuentaRegistros(){
				$registros = mysql_num_rows($this->consulta);
				return $registros;
				}
		public function __destruct(){
				mysql_free_result($this->consulta);
				}
		}
		
function accionesMysql($arregloCampos, $tabla, $accion){
		$campos = "";
		$valores = "";
		$actualiza = "";
		
		foreach($arregloCampos as $campo => $valor){
				if($accion == "Inserta"){
						$campos .= $campo . ",";
						$valores .= "'" . $valor . "',";
						}
				else if($accion == "Actualiza"){
						$actualiza .= $campo . " = '" . $valor . "',";
						}
				}
		if($accion == "Inserta"){
				$campos = trim($campos, ',');
				$valores = trim($valores, ',');
				$sql = "INSERT INTO " . $tabla . "(" . $campos . ") VALUES(" . $valores . ")";
				mysql_query($sql) or die("Error en consulta:<br> $sql <br>" . mysql_error());
				}
		else if($accion == "Actualiza"){
				$actualiza = trim($actualiza, ',');
				$sql = "UPDATE " . $tabla . " SET " . $actualiza; 
				mysql_query($sql) or die("Error en consulta:<br> $sql <br>" . mysql_error());
				}
		
		}
		
?>