<?php
	
	
	include_once($_SERVER['DOCUMENT_ROOT'] . "/rc/conect.php");
	include_once($_SERVER['DOCUMENT_ROOT'] . "/rc/config.inc.php");
	include_once($_SERVER['DOCUMENT_ROOT'] ."/rc/code/general/funciones.php");
	
	
	extract($_GET);
	//extract($_POST);	
	//http://localhost:8082/rc/code/clases/disponibilidad.class.php?tipoLocalizacionEvento=1&idArticulo=3&fechaInicio=03/02/2014&horaInicio=10&fechaFin=05/02/2014&horaFin=6
	
	class ItemDisponibilidad { 
		//DATOS SOLICITADOS Y A ENTRGAR
		protected $tipoLocalizacion, $idArticulo, $fechaInicio, $horaInicio, $fechaFin, $horaFin, $disponibilidad, $respuesta, $fechaFinBDD, $horaFinBDD, $disponibilidadBDD, $reservadoBDD, $tipoPedido; 

		public function __construct($tipoLocalizacionL, $idArticuloL, $fechaInicioL, $horaInicioL, $fechaFinL, $horaFinL, $tipoPedido = 1) { 
			$this->tipoLocalizacion = mysql_real_escape_string($tipoLocalizacionL); 
			$this->idArticulo = mysql_real_escape_string($idArticuloL); 
			$this->fechaInicio = mysql_real_escape_string($fechaInicioL); 
			$this->horaInicio = mysql_real_escape_string($horaInicioL); 
			$this->fechaFin = mysql_real_escape_string($fechaFinL); 
			$this->horaFin = mysql_real_escape_string($horaFinL); 
			$this->tipoPedido = mysql_real_escape_string($tipoPedido); 
			
			//ABRIR CONEXION
			//mysql_query("LOCK TABLES mytest WRITE;");			
		}
		
		function __destruct()
		{
			//CERRAR CONEXION
			mysql_query("UNLOCK TABLES;");
		}
		
		public function __toString() { 
			return $this->respuesta;
		}
		
		public function validarArticuloDisponibilidad()
		{
			if(is_numeric($this->idArticulo) && is_numeric($this->horaInicio) && is_numeric($this->horaFin) && $this->validaGeneraRangoFecha())
			{
				$this->aumentarDisponibilidadHastaFecFin();
				$this->obtenerDisponibilidadProducto();
				$this->respuesta = $this->obtenerDisponibilidadProducto();
			}
			else
			{
				$this->respuesta = "0"; 
			}
			return $this->respuesta;
		}
		
		protected function obtenerDisponibilidadProducto()
		{
			$horaInicio = "";
			$horaFin = "";
			
			$strConsulta = "
				SELECT tiempo
				FROM rac_tiempos
				WHERE id_tiempo = '" . $this->horaInicio . "'
			";
			$resource = mysql_query($strConsulta) or die("Error at rowsel $strConsulta::".mysql_error());
			$rowFec = mysql_fetch_assoc($resource);
			$horaInicio = $rowFec['tiempo'];
			
			$strConsulta = "
				SELECT tiempo
				FROM rac_tiempos
				WHERE id_tiempo = '" . $this->horaFin . "'
			";
			$resource = mysql_query($strConsulta) or die("Error at rowsel $strConsulta::".mysql_error());
			$rowFec = mysql_fetch_assoc($resource);
			$horaFin = $rowFec['tiempo'];
			
			$strConsulta = "
				SELECT rd.id_articulo, MIN(rd.disponibilidad) minimo
				FROM rac_disponibilidad rd
				LEFT JOIN rac_tiempos rt1 ON rd.id_tiempo = rt1.id_tiempo
				WHERE id_articulo = '" . $this->idArticulo . "' 
				AND CONCAT(rd.fecha, ' ', rt1.tiempo) BETWEEN '" . $this->fechaInicio . " $horaInicio' AND '" . $this->fechaFin . " $horaFin'
				GROUP BY rd.id_articulo
			";
			$resource = mysql_query($strConsulta) or die("Error at rowsel $strConsulta::".mysql_error());
			$rowFecDisp = mysql_fetch_assoc($resource);
			return $rowFecDisp['minimo'];
		}
		
		protected function aumentarDisponibilidadHastaFecFin()
		{
			mysql_query("LOCK TABLES mytest WRITE;");
			mysql_query("BEGIN;");			
			//OBTENER FECHA MAXIMA CON DISPONIBILIDAD DEL ARTICULO
			$strConsulta = "
				SELECT rd.fecha, rd.id_tiempo, inventario_fisico, disponibilidad, cantidad_reservada, rt.tiempo
				FROM
				(
				SELECT MAX(id_disponibilidad) id_disponibilidad
				FROM rac_disponibilidad
				WHERE id_articulo = '" . $this->idArticulo . "'
				) maximo
				LEFT JOIN rac_disponibilidad rd ON rd.id_disponibilidad = maximo.id_disponibilidad
				LEFT JOIN rac_tiempos rt ON rd.id_tiempo = rt.id_tiempo
			";
			$resource = mysql_query($strConsulta) or die("Error at rowsel $strConsulta::".mysql_error());
			$rowDispFecMax = mysql_fetch_assoc($resource);
			
			$strFecFinSolic = strtotime($this->fechaFin);
			$strInicio = strtotime($rowDispFecMax['fecha']);
			$rowDispFecMax['id_tiempo'] = $rowDispFecMax['id_tiempo'] + 1;
				
			$itera = 0;
			do
			{
				//sumarMinutosFecha($FechaStr, $MinASumar)  
				//echo $rowDispFecMax['fecha'] ."|". (($itera++) * 48 * 30) . "|";
				$FecInsert = $this->sumarMinutosFecha($rowDispFecMax['fecha'], ($itera++) * 48 * 30);
				//echo $FecInsert . "<br>";
								
				$strFecFinDispo = strtotime($FecInsert);				
				
				$iteraHoras = ($strFecFinDispo == $strFecFinSolic)? $this->horaFin + 1 : 48;
				$iInicial = ($strFecFinDispo == $strInicio)? $rowDispFecMax['id_tiempo'] : 1;
				//$iInicial = ($iInicial == 48 && $strFecFinDispo == $strInicio)? 49 : $iInicial;
				
				for($i = $iInicial; $i<=$iteraHoras; $i++)
				{
					if($i < 49)
					{
						$sqlInsert = "
							INSERT INTO rac_disponibilidad 
							(id_articulo, fecha, id_tiempo, inventario_fisico, disponibilidad, cantidad_reservada) 
							VALUES 
							('".$this->idArticulo."', '".$FecInsert."', '".$i."', '".$rowDispFecMax['inventario_fisico']."', '".$rowDispFecMax['disponibilidad']."', '".$rowDispFecMax['cantidad_reservada']."');
						";
						mysql_query($sqlInsert) or die("Error al actualizar disponibilidad");					
					}
					else
					{
						$FecInsert = $this->sumarMinutosFecha($rowDispFecMax['fecha'], ($itera++) * 48 * 30);
						$sqlInsert = "
							INSERT INTO rac_disponibilidad 
							(id_articulo, fecha, id_tiempo, inventario_fisico, disponibilidad, cantidad_reservada) 
							VALUES 
							('".$this->idArticulo."', '".$FecInsert."', '1', '".$rowDispFecMax['inventario_fisico']."', '".$rowDispFecMax['disponibilidad']."', '".$rowDispFecMax['cantidad_reservada']."');
						";
						mysql_query($sqlInsert) or die("Error al actualizar disponibilidad");		
					}
				}
				
			}while($strFecFinDispo < $strFecFinSolic);
			mysql_query("COMMIT;");
		}
		//                                tablas de fuentes ligadas ,                         num de grid , cuanto afe disp  -      cuanto el reservado
		public function actualizarDisponibilidadSustento($id_fuente_ligada, $id_fuente, $id_detalle_articulo_fuente, $id_grid, $cantidadAdisponible, $cantidadAreservado)
		{
			$respuesta = "0";
			$disponibilidad = $this->validarArticuloDisponibilidad();
			
			//echo $disponibilidad."|".$cantidadAreservado."<br>";
			//if($disponibilidad >= $cantidadAreservado)
			{
				$insDispSust = "
					INSERT INTO rac_disponibilidad_sustento (
						id_fuente_ligada,
						id_fuente,
						id_detalle_articulo_fuente,
						id_grid,
						id_articulo,
						fecha_inicio_sistema,
						id_hora_inicio_sistema,
						fecha_inicio_usuario,
						id_hora_inicio_usuario,
						fecha_fin_sistema,
						id_hora_fin_sistema,
						fecha_fin_usuario,
						id_hora_fin_usuario,
						cantidadAdisponible,
						cantidadAreservado,
						activo
					) VALUES (
						'$id_fuente_ligada',
						'$id_fuente',
						'$id_detalle_articulo_fuente',
						'$id_grid',
						'" . $this->idArticulo . "',
						'" . $this->fechaInicio . "',
						'" . $this->horaInicio . "',
						'" . $this->fechaInicio . "',
						'" . $this->horaInicio . "',
						'" . $this->fechaFin . "',
						'" . $this->horaFin . "',
						'" . $this->fechaFin . "',
						'" . $this->horaFin . "',
						'$cantidadAdisponible',
						'$cantidadAreservado',
						1
					)
				";
				mysql_query($insDispSust) or die("Error al guardar el soporte de disponibilidad ::".mysql_error());
				//echo "".$insDispSust."<br>";
				
				//FUNCION PARA ACTUALIZAR LA DISPONIBILIDAD EN UN RANGO DE TIEMPO
				$this->actualizarDisponibilidad($cantidadAdisponible, $cantidadAreservado);
				
				$respuesta = "1";
			}
			//else
			//{
			//	$respuesta = "0";
			//}
			
			return $respuesta;
		}
		
		protected function actualizarDisponibilidad($cantidadAdisponible, $cantidadAreservado)
		{
				
			$arrayIdsExtremos = array();
			$arrayIdsActualizar = array();
			$i = 0;
			//OBTENER ID DISPONIBILIDAD FECHA MENOR Y FECHA MAYOR 
			$sqlIdMaxMin = "
				SELECT x.id_disponibilidad
				FROM rac_disponibilidad x
				WHERE x.id_articulo = '" . $this->idArticulo . "'
				AND ((x.fecha = '" . $this->fechaInicio . "' AND x.id_tiempo = '" . $this->horaInicio . "') OR (x.fecha = '" . $this->fechaFin . "' AND x.id_tiempo = '" . $this->horaFin . "'))
			";
			
			
			
			$resIdMaxMin = mysql_query($sqlIdMaxMin) or die("Error al obtener id min y max de articulo por fecha ::".mysql_error());
			while($rowIdMaxMin = mysql_fetch_assoc($resIdMaxMin))
			{
				$arrayIdsExtremos[$i++] = $rowIdMaxMin['id_disponibilidad'];
			}
			
			
			
			if(count($arrayIdsExtremos) > 0)
			{
				
				if($arrayIdsExtremos[1]=='')
				
				$arrayIdsExtremos[1]=$arrayIdsExtremos[0];
				
				//OBTENER TODOS LOS IDS INTERMEDIOS PARA EL ARTÍCULO
				$sqlIdsIntermedios = "
					SELECT id_disponibilidad
					FROM rac_disponibilidad rc
					WHERE rc.id_articulo = '" . $this->idArticulo . "' 
					AND id_disponibilidad BETWEEN '" . $arrayIdsExtremos[0] . "' AND '" . $arrayIdsExtremos[1] . "'
					ORDER BY fecha ASC, id_tiempo ASC
				";
				$resIdsIntermedios = mysql_query($sqlIdsIntermedios) or die("Error al obtener id intermedios de articulo por fecha ::".mysql_error());
				while($rowIdsIntermedios = mysql_fetch_assoc($resIdsIntermedios))
				{
					array_push($arrayIdsActualizar, $rowIdsIntermedios['id_disponibilidad']);
				}
				
				$sqlUpd = "
					UPDATE rac_disponibilidad 
					SET cantidad_reservada = (cantidad_reservada + ($cantidadAreservado)), disponibilidad = (disponibilidad - ($cantidadAdisponible))
					WHERE id_disponibilidad IN (" . implode(",", $arrayIdsActualizar) . ");
				";
				
				mysql_query($sqlUpd) or die("Error al act disponibilidad del articulo ::".mysql_error());
				
				$sqlUpd = "
					UPDATE rac_disponibilidad 
					SET inventario_fisico = cantidad_reservada + disponibilidad
					WHERE id_disponibilidad IN (" . implode(",", $arrayIdsActualizar) . ");
				";
				mysql_query($sqlUpd) or die("Error al act disponibilidad del articulo ::".mysql_error());
			}
			else
			{
				$sqlInsert = "
					INSERT INTO rac_disponibilidad 
					(id_articulo, fecha, id_tiempo, inventario_fisico, disponibilidad, cantidad_reservada) 
					VALUES 
					('".$this->idArticulo."', '".$this->fechaInicio."', '".$this->horaInicio."', ((0 - (". $cantidadAdisponible .")) + (0 + (". $cantidadAreservado ."))), (0 - (". $cantidadAdisponible .")), (0 + (". $cantidadAreservado .")));
				";
				mysql_query($sqlInsert) or die("Error al actualizar disponibilidad");					
			}
		}
		
		//var_dump(validateDate('2012-02-28', 'Y-m-d'))
		protected function validateDate($date, $format = 'Y-m-d H:i:s')
		{
			$d = DateTime::createFromFormat($format, $date);
			return $d && $d->format($format) == $date;
		}
		
		protected function validaGeneraRangoFecha()
		{
			$tiempo_previo = 0;					//TIEMPO PREVIO A LA FECHA DE SALIDA CON LA QUE SE DEBE RESERVAR
			$tiempo_post = 0; 					//TIEMPO POST A LA FECHA DE SALIDA CON LA QUE SE DEBE RESERVAR
			$tiempo_maximo_dia = 48;			//TIEMPO MAXIMO POR DIA
			$tiempo_dedicado_prod_inicio = 0;	//DEBE CONSIDERAR DIAS FERIADOS SOLO PARA ARTICULOS DE TRANSFORMACION
			$fecha_inicial_act = "";			//FECHA DE INICIO ACTUALIZADA
			$fecha_final_act = "";				//FECHA DE FIN ACTUALIZADA
			
			$fechaInicioValida = ($this->validateDate($this->fechaInicio, 'Y-m-d'));
			$fechaFinValida = ($this->validateDate($this->fechaFin, 'Y-m-d'));
			$horaInicioValida = (is_numeric($this->horaInicio) && $this->horaInicio >= 1 && $this->horaInicio <= 48)? TRUE : FALSE;
			$horaFinValida = (is_numeric($this->horaFin) && $this->horaFin >= 1 && $this->horaFin <= 48)? TRUE : FALSE;
			$fechaHoy = date("Y-m-d");  
			
			$strFecIniAux = strtotime($this->fechaInicio);
			$strFecFinAux = strtotime($this->fechaFin);
			$strFecHoy = strtotime($fechaHoy);

			if($fechaInicioValida && $fechaFinValida && $horaInicioValida && $horaFinValida)
			{
				//echo $this->fechaInicio ."==". $this->fechaFin ."&&". $this->horaInicio ."<". $this->horaFin.") || (".$strFecIniAux ."<". $strFecFinAux.")) && (".$strFecIniAux." >= $strFecHoy";
				if((($this->fechaInicio == $this->fechaFin && $this->horaInicio < $this->horaFin) || ($strFecIniAux < $strFecFinAux)) && ($strFecIniAux >= $strFecHoy))
				{
					//OBTENER LO HORA DE INICIO
					$strConsulta="
						SELECT a.tiempo
						FROM rac_tiempos a
						WHERE a.id_tiempo = '" . $this->horaInicio . "'
					";
					$resource = mysql_query($strConsulta) or die("Error at rowsel $strConsulta::".mysql_error());
					$rowHoraInicio = mysql_fetch_assoc($resource);
					
					//OBTENER LA HORA DE TERMINO
					$strConsulta="
						SELECT a.tiempo
						FROM rac_tiempos a
						WHERE a.id_tiempo = '" . $this->horaFin . "'
					";
					$resource = mysql_query($strConsulta) or die("Error at rowsel $strConsulta::".mysql_error());
					$rowHoraFin = mysql_fetch_assoc($resource);
					
					//ACTUALIZAR LAS FECHAS SEGUN EL TIPO DE LOCALIZACION
					//OBTENER LA TOLERANCIA POR LOCAL O FORANEO
					$strConsulta="
						SELECT horas_tolerancia_previo previo, horas_tolerancia_post post
						FROM rac_tipo_evento_localizacion
						WHERE id_tipo_evento_localizacion = '" . $this->tipoLocalizacion . "'
					";
					$resource = mysql_query($strConsulta) or die("Error at rowsel $strConsulta::".mysql_error());
					$rowTipLoc = mysql_fetch_assoc($resource);
					
					//OBTENER LA TOLERANCIA POR NIVEL DE RIESGO DEL ARTICULO
					$strConsulta="
						SELECT (b.dias_no_disponibles_mas * 48) tiempo_tolerancia
						FROM rac_articulos a
						LEFT JOIN rac_articulos_nivel_riesgo b ON a.id_nivel_riesgo = b.id_nivel_riesgo
						WHERE a.id_articulo = '" . $this->idArticulo . "'
					";
					$resource = mysql_query($strConsulta) or die("Error at rowsel $strConsulta::".mysql_error());
					$rowNivRiesgo = mysql_fetch_assoc($resource);
					
					//echo ">>".$this->tipoLocalizacion."<<";
					if($this->tipoLocalizacion != '3')
					{
						$tiempo_previo = ($rowTipLoc['previo'] * 2) + $rowNivRiesgo['tiempo_tolerancia'];
						$tiempo_post = ($rowTipLoc['post'] * 2) + $rowNivRiesgo['tiempo_tolerancia'];
					}
										
					$fecha_inicial_act = $this->sumarMinutosFecha($this->fechaInicio . ' ' . $rowHoraInicio['tiempo'], ($tiempo_previo) * 30 * -1);
					$fecha_final_act = $this->sumarMinutosFecha($this->fechaFin . ' ' . $rowHoraFin['tiempo'], $tiempo_post * 30);
					
					//VALIDAR QUE $fecha_inicial_act NO SEA MENOR A LA FECHA ACTUAL
					$strFecIniActAux = strtotime($fecha_inicial_act);
					$strFecHoy = strtotime(date("Y-m-d H:i:s"));
					
					if(($strFecIniActAux < $strFecHoy))
					{
						$iT = date("i");
						$fecha_inicial_act = date("Y-m-d H:");
						$fecha_inicial_act .= ($iT < 30)? "00:00" : "30:00";						
					}
					
					//echo $this->fechaInicio . ' ' . $rowHoraInicio['tiempo'] . " | " . $this->fechaFin . ' ' . $rowHoraFin['tiempo'] . " <br>";
					//echo "$fecha_inicial_act | $fecha_final_act <br>";
					
					//ACTUALIZAMOS LAS FECHAS YA CON LOS VALORES CON TOLERANCIA
					$this->fechaInicio = substr($fecha_inicial_act, 0, 10);
					$this->fechaFin = substr($fecha_final_act, 0, 10);
					//OBTENER LO HORA DE INICIO
					$strConsulta="
						SELECT a.id_tiempo
						FROM rac_tiempos a
						WHERE a.tiempo = '" . substr($fecha_inicial_act, 11, 8) . "'
					";
					$resource = mysql_query($strConsulta) or die("Error at rowsel $strConsulta::".mysql_error());
					$rowHoraInicio = mysql_fetch_assoc($resource);
					
					//OBTENER LA HORA DE TERMINO
					$strConsulta="
						SELECT a.id_tiempo
						FROM rac_tiempos a
						WHERE a.tiempo = '" . substr($fecha_final_act, 11, 8) . "'
					";
					$resource = mysql_query($strConsulta) or die("Error at rowsel $strConsulta::".mysql_error());
					$rowHoraFin = mysql_fetch_assoc($resource);
					
					$this->horaInicio = $rowHoraInicio['id_tiempo'];
					$this->horaFin = $rowHoraFin['id_tiempo'];
					
					//echo $this->fechaInicio . ' ' . $this->horaInicio . " | " . $this->fechaFin . ' ' . $this->horaFin . " <br>";
					
					return TRUE;
				}
				else
				{
					return FALSE;					
				}
			}
			else
			{
				return FALSE;
			}
		}
		
		public function sumarMinutosFecha($FechaStr, $MinASumar) 
		{
			$FechaStr = str_replace("-", " ", $FechaStr);
			$FechaStr = str_replace(":", " ", $FechaStr);

			$FechaOrigen = explode(" ", $FechaStr);

			$Dia = $FechaOrigen[2];
			$Mes = $FechaOrigen[1];
			$Ano = $FechaOrigen[0];

			$Horas = $FechaOrigen[3];
			$Minutos = $FechaOrigen[4];
			$Segundos = $FechaOrigen[5];

			// Sumo los minutos
			$Minutos = ((int)$Minutos) + ((int)$MinASumar);

			// Asigno la fecha modificada a una nueva variable
			$FechaNueva = date("Y-m-d H:i:s", mktime($Horas,$Minutos,$Segundos,$Mes,$Dia,$Ano));

			return $FechaNueva;
		}
	} 
		
	
	//inciializar con esta linea
	/*
	$disponibleArticulo = new ItemDisponibilidad($tipoLocalizacionEvento, $idArticulo, convertDate($fechaInicio), $horaInicio, convertDate($fechaFin), $horaFin);
	echo $disponibleArticulo->validarArticuloDisponibilidad();	
	unset($disponibleArticulo);
	*/
	
?>