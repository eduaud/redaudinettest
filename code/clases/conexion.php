<?PHP 
	// include_ONCE("../librerias/resize.php");
	
	include "../clases/class.rc4crypt.php";
	include "../clases/validacion.php";
	
	
	class conexion{  
		private $conexion;  
		private $total_consultas;  
		private $i;
		private $datos;
		private $info;
		private $resultado_arreglo;
		
		function __construct($dbhost, $dbname, $dbuser, $dbpassword)
		{  
			//echo "$dbhost, $dbname, $dbuser, $dbpassword";
			if(!isset($this->conexion)){  
				$this->conexion = (mysql_connect($dbhost, $dbuser, $dbpassword)) or die(mysql_error());  
				mysql_select_db($dbname, $this->conexion) or die(mysql_error());  
				
				/*
				$this->conexion = (mysql_connect("localhost","operadora","1958201")) or die(mysql_error());  
				mysql_select_db("tb_operadora",$this->conexion) or die(mysql_error());  
				*/
			}  
		}  
		
		function getConexion()
		{
			return $conexion;
		}
		
		public function consulta($consulta)
		{  
			$this->total_consultas++;  
			$resultado = mysql_query($consulta,$this->conexion);  
			if(!$resultado)
			{  
				echo 'MySQL Error: ' . mysql_error();  
				exit;  
			}  
			return $resultado;   
		}  
		
		public function ejecutarTransaccion($consulta)
		{  
			$resultado = mysql_query($consulta,$this->conexion);  
			if(!$resultado)
			{  
				return "error";  			
			}  
			return mysql_insert_id();  ; //"Transacción completada satisfactoriamente.";   
		}  
 
		public function fetch_array($consulta)
		{   
			return mysql_fetch_array($consulta);  
		}   
		
		public function num_rows($res)
		{   
			return mysql_num_rows($res);  
		}
		
		public function desplegar_foto($consulta, $eti_bloque_ini, $eti_bloque_fin, $eti_item_ini, $eti_item_fin)
		{
			$this->i = 0;
			$this->datos;
						
			$resultado = $this->consulta($consulta);
			
			if($this->num_rows($resultado) > 0)
			{
				while($fetch_assoc = mysql_fetch_assoc($resultado))
				{
					$this->datos .= str_replace("#id#", $this->i++, $eti_bloque_ini);
					foreach($fetch_assoc as $id => $valor)
					{
						$this->datos .= $eti_item_ini;
						
						//echo $id . "->" . $valor . " | ";
						
						//EL VALOR INFO DEBE SER OBTENIDO ANTES DE CONCATENARSE AL VALOR DE SALIDA
						if($id != 'info')
						{
							if (gettype($valor) == 'integer' || gettype($valor) == 'double' || (gettype($valor) == 'string' && strlen($valor) < 1000) || is_null($valor)){
								if((strstr($id, "idCifrar") != false) && (strstr($id, "CONCAT(") == false)){
									$this->datos .= encX($valor);//$id.'>>'.encX($valor).'|';
								}else{
									$this->datos .= ($valor);
								}
							}
							else
							{
								$this->datos .= '<img src="data:image/jpg;base64,' . base64_encode($valor) . '" />'; 
								
								// $thumb=new thumbnail($valor);
								// $thumb->size_width(100);
								// $thumb->jpeg_quality(100);
								// $thumb->save("photo".$this->i.".jpg");
								// echo "<img src='photo".$this->i.".jpg' />"; 
								// $thumb->show();
							}
							$this->datos .= str_replace('#info#',$this->info,str_replace('#id#',$valor, $eti_item_fin));
							// $this->datos .= str_replace('#id#',$valor, $eti_item_fin);
						}else{
							$this->info = $valor;
						}
					}
					$this->datos .= $eti_bloque_fin;
				}
			}
			mysql_free_result($resultado);
			return $this->datos;
		}
		
		public function desplegar_res_arreglo($consulta)
		{
			$this->i = 0;
			$this->datos;
			
			$itera = 0;
			
			$resultado = $this->consulta($consulta);
			
			if($this->num_rows($resultado) > 0)
			{
				while($fetch_assoc = mysql_fetch_assoc($resultado))
				{
					foreach($fetch_assoc as $id => $valor)
					{
						//EL VALOR INFO DEBE SER OBTENIDO ANTES DE CONCATENARSE AL VALOR DE SALIDA
						if($id != 'info')
						{
							if (gettype($valor) == 'integer' || gettype($valor) == 'double' || (gettype($valor) == 'string' && strlen($valor) < 1000) || is_null($valor)){
								$this->resultado_arreglo[$itera][$id] = $valor;
								/*if((strstr($id, "idCifrar") != false) && (strstr($id, "CONCAT(") == false)){
									$this->resultado_arreglo[$itera][$id] = encX($valor);
								}else{
									$this->resultado_arreglo[$itera][$id] = $valor;
								}*/
							}							
						}else{
							$this->info = $valor;
						}
					}
					$itera++;
				}
			}
			return $this->resultado_arreglo;
		}		
	}
	
?>