<?php

function mensaje($mensaje)
{
echo('<script language="JavaScript" type="text/JavaScript">
alert("'.$mensaje.'");
</script>');
}

function comprobarcadena($nombre_usuario, $long){
    $mensaje="";
	//compruebo que el tamaño del string sea válido.
	$longitudcadena=strlen($nombre_usuario);
    if (($longitudcadena<1) || ($longitudcadena>$long)){
		$mensaje = "Este campo no puede quedar vac&iacute;o. ";
	}else{   
		for ($i=0; $i < $longitudcadena; $i++){
			$cc=$nombre_usuario[$i];
			if (!(($cc >= "a" && $cc <= "z") || ($cc >= "A" && $cc <= "Z") || ($cc == " ") || ($cc == "Ú")  || ($cc == "Ó")  || ($cc == "Í")  || ($cc == "É")  || ($cc == "Á") || ($cc == "ú")  || ($cc == "ó")  || ($cc == "í")  || ($cc == "é")  || ($cc == "á")  || ($cc == ".")  || ($cc == "ñ")  || ($cc == "Ñ") || ($cc == "-") || ($cc == "(") || ($cc == ")") )){
				$mensaje = "Existe almenos un caracter que no es letra ni espacio en blanco. ";
				return $mensaje;
			}  
		}
	}
	return $mensaje;
}

function compCadNum($nombre_usuario, $long){
    $mensaje="";
	//compruebo que el tamaño del string sea válido.
	$longitudcadena=strlen($nombre_usuario);
    if (($longitudcadena<1) || ($longitudcadena>$long)){
		$mensaje = "Este campo no puede quedar vac&iacute;o. ";
	}else{   
		for ($i=0; $i < $longitudcadena; $i++){
			$cc=$nombre_usuario[$i];
			if (!(($cc >= "a" && cc <= "z") || ($cc >= "A" && $cc <= "Z") || ($cc == " ") || ($cc >= "0" && $cc <= "9"))){
				$mensaje = "Existe almenos un caracter que no es letra, n&uacute;mero o espacio en blanco. ";
				return $mensaje;
			}  
		}
	}
	return $mensaje;
}

function compNumDec($nombre_usuario){
    $mensaje="";
	//compruebo que el tamaño del string sea válido.
	$longitudcadena=strlen($nombre_usuario);
    if ($longitudcadena<1){
		$mensaje = "Este campo no puede quedar vac&iacute;o. ";
	}else{   
		for ($i=0; $i < $longitudcadena; $i++){
			$cc=$nombre_usuario[$i];
			if (!(($cc == ".") || ($cc >= "0" && $cc <= "9"))){
				$mensaje = "Existe almenos un caracter que no es n&uacute;mero o punto. ";
				return $mensaje;
			}  
		}
	}
	return $mensaje;
}

function compSup($nombre_usuario, $long){
    $mensaje="";
	//compruebo que el tamaño del string sea válido.
	$longitudcadena=strlen($nombre_usuario);
    if (($longitudcadena<1) || ($longitudcadena>$long)){
		$mensaje = "Este campo no puede quedar vac&iacute;o. ";
	}else{   
		for ($i=0; $i < $longitudcadena; $i++){
			$cc=$nombre_usuario[$i];
			if (!(($cc == "-") || ($cc == ".") || ($cc == "%") || ($cc >= "0" && $cc <= "9"))){
				$mensaje = "Solo puede introducir n&uacute;meros y los signos '-', '.', '%'. ";
				return $mensaje;
			}  
		}
	}
	return $mensaje;
}

function comprobar_nombre_usuario($nombre_usuario){ 
   //compruebo que el tamaño del string sea válido. 
   if (strlen($nombre_usuario)<1 || strlen($nombre_usuario)>20){ 
      return "La cadena tiene m&aacute;s caracteres de los que debe."; 
   } 
   //compruebo que los caracteres sean los permitidos 
   $permitidos = " abcdefghaijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_&"; 
   for ($i=0; $i<strlen($nombre_usuario); $i++){ 
      if (strpos($permitidos, substr($nombre_usuario,$i,1)) == false){ 
         return "Existe almenos un caracter que no es letra, n&uacute;mero o alguno de estos signos: '-', '_'."; 
      } 
   } 
   return ""; 
} 

function comprobarnumero2($numero){ 
	if(is_numeric($numero))
	    if(strlen($numero) != 0)
			return;
		else
			return "El campo no puede quedar vac&iacute;o.";
	else
		return "El campo solo acepta n&uacute;meros.";
}

function compCodBarras($cadena){
   	if (strlen($cadena) != 13){ 
      	return "El c&oacute;digo de barras debe de tener 13 n&uacute;meros."; 
   	}else{  	
		$permitidos = "0123456789"; 
		for ($i=0; $i<strlen($cadena); $i++){ 
			$cc=$cadena[$i];
			if (!(strcmp($cc,"0") == 0 || strcmp($cc,"1") == 0 || strcmp($cc,"2") == 0 || strcmp($cc,"3") == 0 || strcmp($cc,"4") == 0 || strcmp($cc,"5") == 0 ||
				 strcmp($cc,"6") == 0 || strcmp($cc,"7") == 0 || strcmp($cc,"8") == 0 || strcmp($cc,"9") == 0)){
				return "Existen caracteres inadecuados donde debe haber n&uacute;meros.";
			}  
		}
	} 
   	return; 
}

function compRFC($RFC){
	$lon=strlen($RFC);
	$mensaje = "";
	if ($lon == 0){	
		return "Este campo no puede quedar vac&iacute;o. ";
	}
	if ($lon < 10){	
		$mensaje = "Faltan caracteres. ";
	}else{
		for ($i=0; $i < 4; $i++){
			$cc=$RFC[$i];
			if (!(($cc >= "a" && cc <= "z") || ($cc >= "A" && $cc <= "Z"))){
				$mensaje = $mensaje."Existen caracteres inadecuados donde debe haber letras. ";
				break;
			}  
		}
		for ($i=4; $i < 10; $i++){
			$cc=$RFC[$i];
			if (!(strcmp($cc,"0") == 0 || strcmp($cc,"1") == 0 || strcmp($cc,"2") == 0 || strcmp($cc,"3") == 0 || strcmp($cc,"4") == 0 || strcmp($cc,"5") == 0 ||
				 strcmp($cc,"6") == 0 || strcmp($cc,"7") == 0 || strcmp($cc,"8") == 0 || strcmp($cc,"9") == 0)){
				$mensaje = $mensaje."Existen caracteres inadecuados donde debe haber n&uacute;meros";
				break;
			}  
		}
	}
	return $mensaje;
}

function compNums($cadNums){
	//para numero telefonico
	$lon=strlen($cadNums);
	if ($lon == 0){	
		return "";
	}
	if ($lon != 10 && $lon != 13){	
		return "Debe proporcionar un n&uacute;mero telefonico de 10 o 13 n&uacute;meros. ";
	}else{
		for ($i=0; $i < strlen($cadNums); $i++){
			$cc=$cadNums[$i];
			if (!(strcmp($cc,"0") == 0 || strcmp($cc,"1") == 0 || strcmp($cc,"2") == 0 || strcmp($cc,"3") == 0 || strcmp($cc,"4") == 0 || strcmp($cc,"5") == 0 ||
				 strcmp($cc,"6") == 0 || strcmp($cc,"7") == 0 || strcmp($cc,"8") == 0 || strcmp($cc,"9") == 0)){
				return "Existen caracteres inadecuados donde debe haber n&uacute;meros";
			}  
		}
	}
}

function compEmail($email){
	if($email=="")
		return "";
	if (ereg("[a-z|A-Z|\.|\_|0-9]+@[a-z]+\.(org|com|net)$",$email)) {
		return;
	}else{
		if (ereg("[a-z|\.|\_|0-9]+@[a-z]+\.(org|com|net)+\.(mx|es|us|ar)$",$email)) {
			return;
		}else{
			return "El correo $email no es valido. Recuerde debe estar escrito con minúsculas o dejar vacío el campo.";
		}
	}
}

function f_fecha($fecha){
	if($fecha[1] == "/"){
		$fecha2 = $fecha[5].$fecha[6].$fecha[7].$fecha[8]."-".$fecha[2].$fecha[3]."-".$fecha[0];
	}else{
		if($fecha[2] == "/"){
			$fecha2 = $fecha[6].$fecha[7].$fecha[8].$fecha[9]."-".$fecha[3].$fecha[4]."-".$fecha[0].$fecha[1];
		}
	}
	return $fecha2;
}

function agrefec($fecha, $num){
	return intval($fecha[0].$fecha[1].$fecha[2].$fecha[3])+intval($num);
}

function perfBase($cad){
	return strtoupper(utf8_decode($cad));
}

function perfBase2($cad){
	return strtoupper($cad);
}

function getAno($fecha){
	return $fecha[0].$fecha[1].$fecha[2].$fecha[3];
}

function getMes($fecha){
	$opc = intval($fecha[5].$fecha[6]);
	$meses = array("nada","01", "02", "03", "04", "05","06", "07", "08", "09","10", "11", "12");
	return $meses[$opc];
}

function getDia($fecha){
	return $fecha[8].$fecha[9];
}

/*!
  @function num2letras ()
  @abstract Dado un n?mero lo devuelve escrito.
  @param $num number - N?mero a convertir.
  @param $fem bool - Forma femenina (true) o no (false).
  @param $dec bool - Con decimales (true) o no (false).
  @result string - Devuelve el n?mero escrito en letra.

*/
function num2letras($num, $fem = true, $dec = true) {
//if (strlen($num) > 14) die("El n?mero introducido es demasiado grande");
   $matuni[2]  = "dos";
   $matuni[3]  = "tres";
   $matuni[4]  = "cuatro";
   $matuni[5]  = "cinco";
   $matuni[6]  = "seis";
   $matuni[7]  = "siete";
   $matuni[8]  = "ocho";
   $matuni[9]  = "nueve";
   $matuni[10] = "diez";
   $matuni[11] = "once";
   $matuni[12] = "doce";
   $matuni[13] = "trece";
   $matuni[14] = "catorce";
   $matuni[15] = "quince";
   $matuni[16] = "dieciseis";
   $matuni[17] = "diecisiete";
   $matuni[18] = "dieciocho";
   $matuni[19] = "diecinueve";
   $matuni[20] = "veinte";
   $matunisub[2] = "dos";
   $matunisub[3] = "tres";
   $matunisub[4] = "cuatro";
   $matunisub[5] = "quin";
   $matunisub[6] = "seis";
   $matunisub[7] = "sete";
   $matunisub[8] = "ocho";
   $matunisub[9] = "nove";

   $matdec[2] = "veint";
   $matdec[3] = "treinta";
   $matdec[4] = "cuarenta";
   $matdec[5] = "cincuenta";
   $matdec[6] = "sesenta";
   $matdec[7] = "setenta";
   $matdec[8] = "ochenta";
   $matdec[9] = "noventa";
   $matsub[3]  = 'mill';
   $matsub[5]  = 'bill';
   $matsub[7]  = 'mill';
   $matsub[9]  = 'trill';
   $matsub[11] = 'mill';
   $matsub[13] = 'bill';
   $matsub[15] = 'mill';
   $matmil[4]  = 'millones';
   $matmil[6]  = 'billones';
   $matmil[7]  = 'de billones';
   $matmil[8]  = 'millones de billones';
   $matmil[10] = 'trillones';
   $matmil[11] = 'de trillones';
   $matmil[12] = 'millones de trillones';
   $matmil[13] = 'de trillones';
   $matmil[14] = 'billones de trillones';
   $matmil[15] = 'de billones de trillones';
   $matmil[16] = 'millones de billones de trillones';

   $num = trim((string)@$num);
   if ($num[0] == '-') {
      $neg = 'menos ';
      $num = substr($num, 1);
   }else
      $neg = '';
   while ($num[0] == '0') $num = substr($num, 1);
   if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num;
   $zeros = true;
   $punt = false;
   $ent = '';
   $fra = '';
   for ($c = 0; $c < strlen($num); $c++) {
      $n = $num[$c];
      if (! (strpos(".,'''", $n) === false)) {
         if ($punt) break;
         else{
            $punt = true;
            continue;
         }

      }elseif (! (strpos('0123456789', $n) === false)) {
         if ($punt) {
            if ($n != '0') $zeros = false;
            $fra .= $n;
         }else

            $ent .= $n;
      }else

         break;

   }
   $ent = '     ' . $ent;
   if ($dec and $fra and ! $zeros) {
      $fin = ' coma';
      for ($n = 0; $n < strlen($fra); $n++) {
         if (($s = $fra[$n]) == '0')
            $fin .= ' cero';
         elseif ($s == '1')
            $fin .= $fem ? ' una' : ' un';
         else
            $fin .= ' ' . $matuni[$s];
      }
   }else
      $fin = '';
   if ((int)$ent === 0) return 'Cero ' . $fin;
   $tex = '';
   $sub = 0;
   $mils = 0;
   $neutro = false;
   while ( ($num = substr($ent, -3)) != '   ') {
      $ent = substr($ent, 0, -3);
      if (++$sub < 3 and $fem) {
         $matuni[1] = 'una';
         $subcent = 'os';
      }else{
         $matuni[1] = $neutro ? 'un' : 'uno';
         $subcent = 'os';
      }
      $t = '';
      $n2 = substr($num, 1);
      if ($n2 == '00') {
      }elseif ($n2 < 21)
         $t = ' ' . $matuni[(int)$n2];
      elseif ($n2 < 30) {
         $n3 = $num[2];
         if ($n3 != 0) $t = 'i' . $matuni[$n3];
         $n2 = $num[1];
         $t = ' ' . $matdec[$n2] . $t;
      }else{
         $n3 = $num[2];
         if ($n3 != 0) $t = ' y ' . $matuni[$n3];
         $n2 = $num[1];
         $t = ' ' . $matdec[$n2] . $t;
      }
      $n = $num[0];
      if ($n == 1) {
         $t = ' ciento' . $t;
      }elseif ($n == 5){
         $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t;
      }elseif ($n != 0){
         $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t;
      }
      if ($sub == 1) {
      }elseif (! isset($matsub[$sub])) {
         if ($num == 1) {
            $t = ' mil';
         }elseif ($num > 1){
            $t .= ' mil';
         }
      }elseif ($num == 1) {
         $t .= ' ' . $matsub[$sub] . '?n';
      }elseif ($num > 1){
         $t .= ' ' . $matsub[$sub] . 'ones';
      }   
      if ($num == '000') $mils ++;
      elseif ($mils != 0) {
         if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub];
         $mils = 0;
      }
      $neutro = true;
      $tex = $t . $tex;
   }
   $tex = $neg . substr($tex, 1) . $fin;
   return ucfirst($tex);
}


function Moda($matriz){
	if(!empty($matriz)){
	$matriz1=array_unique($matriz);
	$datos=array_count_values($matriz); // devuelve un array, cuya clave es el número y el dato es el número de veces que aparece
	arsort($datos);//ordeno el array por el valor del dato de manera descendente y mantengo la clave
	$moda=array();//creo otro array para cargar la moda (pueden ser varias si varios números coinciden en frecuencia)
	$contador="0";//establezco un contador que pongo en cero
	$frecuencia="0";
	foreach($datos as $key => $valor)//recorro el array de comienzo a fin.
	{
		if($key > 0){
			if ($valor >= $contador) {$moda[]=$key; $contador = $valor; $frecuencia=$valor;
			}//en cada clave-valor si el valor es mayor que el contador, cargo el dato en el array moda y cargo el valor en el contador; si no, nada; 
		}
	}
	
	$tipomoda="";//variables para los tipos de moda
	if (count($moda)==1) {$tipomoda='moda modal';}
	if (count($moda)==2) {$tipomoda='moda bimodal';}
	if (count($moda) == count($datos) and count($matriz1)!=1) {$tipomoda='no hay moda';}//si todos los números aparecen con la misma frecuencia, no existe moda.
	if (count($moda) < count($datos) && count($moda) > 2) {$tipomoda='moda multimodal';}
	
	
	if ($tipomoda != 'no hay moda')
	{//echo $tipomoda.'</br>';
	foreach ($moda as $valor)//recorro ahora el array $moda
	{
	//echo $valor.'</br>';
	return $valor;
	}//muestro el dato o los datos
	//echo 'con una frecuencia de : '.$frecuencia.' apariciones';
	}
	 else {
	 $valor='-';
	 return $valor;
		   }
	   }
	   else
	   {
	   $valor='-';
	 return $valor;
	   }
}

function sumarmeses2 ($fechaini, $meses)
{
 //recortamos la cadena separandola en 
 //tres variables de dia, mes y año
 $dia=substr($fechaini,0,2);
 $mes=substr($fechaini,3,2);
 $anio=substr($fechaini,6,4);
 
 //Sumamos los meses requeridos
 $tmpanio=floor($meses/12);
 $tmpmes=$meses%12;
 $anionew=$anio+$tmpanio;
 $mesnew=$mes+$tmpmes;
 
 //Comprobamos que al sumar no nos hayamos
 //pasado del año, si es así incrementamos
 //el año
 if ($mesnew>12)
 {
  $mesnew=$mesnew-12;
  if ($mesnew<10)
   $mesnew="0".$mesnew;
  $anionew=$anionew+1;
 }
 
 //Ponemos la fecha en formato americano y la devolvemos
 $fecha=date( "d/m/Y", mktime(0,0,0,$mesnew,$dia,$anionew+1) );
 return $fecha;
}

function sumarmeses ($fechaini, $meses)
{
 //recortamos la cadena separandola en 
 //tres variables de dia, mes y año
 $dia=substr($fechaini,0,2);
 $mes=substr($fechaini,3,2);
 $anio=substr($fechaini,6,4);
 
 //Sumamos los meses requeridos
 $tmpanio=floor($meses/12);
 $tmpmes=$meses%12;
 $anionew=$anio+$tmpanio;
 $mesnew=$mes+$tmpmes;
 
 //Comprobamos que al sumar no nos hayamos
 //pasado del año, si es así incrementamos
 //el año
 if ($mesnew>12)
 {
  $mesnew=$mesnew-12;
  if ($mesnew<10)
   $mesnew="0".$mesnew;
  $anionew=$anionew+1;
 }
 
 //Ponemos la fecha en formato americano y la devolvemos
 $fecha=date( "Y-m-d", mktime(0,0,0,$mesnew,$dia,$anionew) );
 return $fecha;
}

function UltimoDia($anho,$mes){ 
   if (((fmod($anho,4)==0) and (fmod($anho,100)!=0)) or (fmod($anho,400)==0)) { 
       $dias_febrero = 29; 
   } else { 
       $dias_febrero = 28; 
   } 
   switch($mes) { 
       case 1: return 31; break; 
       case 2: return $dias_febrero; break; 
       case 3: return 31; break; 
       case 4: return 30; break; 
       case 5: return 31; break; 
       case 6: return 30; break; 
       case 7: return 31; break; 
       case 8: return 31; break; 
       case 9: return 30; break; 
       case 10: return 31; break; 
       case 11: return 30; break; 
       case 12: return 31; break; 
   } 
}

function diaSemana($ano,$mes,$dia)
{
        $dia=date("w",mktime(0,0,0, $mes, $dia, $ano));

            return $dia;
}

	function bisiesto($anio){
		if (($anio % 4 == 0) && (($anio % 100 != 0) || ($anio % 400 == 0)))
			return 1;
		else
			return 0;
	}
	
//2005-10-12   2999-12-31
	function dia_vac($fec1, $fec2){
		if($fec2 == "2999-12-31") $fec2 = date('Y/m/j'); else $fec2 = $fec2;
		
		$timestamp1 = mktime(0,0,0,getMes($fec1),getDia($fec1),getAno($fec1)); 
		$timestamp2 = mktime(0,0,0,getMes($fec1),getDia($fec1),getAno($fec2)); //ajusta a cuando se cumple el año
		$timestamp3 = mktime(0,0,0,getMes($fec2),getDia($fec2),getAno($fec2)); //mantiene la fec de hoy
		if(($timestamp3-$timestamp2) < 0)		
			$timestamp2 = mktime(0,0,0,getMes($fec1),getDia($fec1),getAno($fec2)-1);
			
		$segundos_diferencia = $timestamp2 - $timestamp1;
		$dias_diferencia = $segundos_diferencia / (60 * 60 * 24); 
		
		$segundos_diferencia2 = $timestamp3 - $timestamp2;
		$dias_diferencia2 = $segundos_diferencia2 / (60 * 60 * 24); 
		
		//return $dias_diferencia;
		
		/*************ELIMINA 29S DE FEB*********************/
		$diaBis = 0;
		if((2 == getMes($fec1) && 29 >= getDia($fec1)) || getMes($fec1) < 2){
			$diaBis += bisiesto(getAno($fec1));
		}
		
		$i = getAno($fec1)+1;
		while($i < getAno($fec2)){
			$diaBis += bisiesto($i);
			$i++;
		}
		
		if(getMes($fec1) > 2  && getAno($fec1) != getAno($fec2)){
			$diaBis += bisiesto(getAno($fec2));
		}
		/****************************************************/
		$anios = round(($dias_diferencia-$diaBis)/(365));
		/**********************365 O 366*********************/
		$diaBis2 = 0;
		if((2 == getMes($fec1) && 29 >= getDia($fec1)) || getMes($fec1) < 2)
			$diaBis2 = bisiesto(getAno($fec1)+$anios);
		
		if(getMes($fec1) > 2  && getAno($fec1) != getAno($fec2))
			$diaBis2 = bisiesto(getAno($fec1)+$anios+1);
		/****************************************************/
		
		if($anios == 0)
			$vac_dias = 0;
			
		if($anios >= 1 && $anios <= 4){
			$vac_dias = 6; $j=0;
			for($i=1;$i <= 4; $i++){
				if($i <= $anios){
					$vac_dias = $vac_dias+($j * 2);
					$j++;
				}
			}
		}
		
		if($anios >= 5){
			$vac_dias = 14;	
			$i=5;
			$j=0;
			while($i<=50){
				if($i <= $anios)
					$vac_dias = $vac_dias+($j * 2);
				$i+=5;$j++;
			}
		}
						
		return "El trabajador tiene con la empresa ".$anios." a&ntilde;os, ".number_format($dias_diferencia2,0)." d&iacute;as de ".($diaBis2+365). ". Le correponden ".$vac_dias." d&iacute;as de vacaciones.";
	}
	
	function mes($num){
		switch($num){
			case 1: return "Enero"; break;
			case 2: return "Febrero"; break;
			case 3: return "Marzo"; break;
			case 4: return "Abril"; break;
			case 5: return "Mayo"; break;
			case 6: return "Junio"; break;
			case 7: return "Julio"; break;
			case 8: return "Agosto"; break;
			case 9: return "Septiembre"; break;
			case 10: return "Octubre"; break;
			case 11: return "Noviembre"; break;
			case 12: return "Diciembre"; break;
			default: return "N/A";
		}
	}
	
	function dia($num){
		switch($num){
			case 1: return "Lun"; break;
			case 2: return "Mar"; break;
			case 3: return "Mie"; break;
			case 4: return "Jue"; break;
			case 5: return "Vie"; break;
			case 6: return "Sab"; break;
			case 7: return "Dom"; break;
		}
	}
	
		
	
	function haceMKTime($tempVar){
		return mktime(substr($tempVar,11,2),substr($tempVar,14,2),substr($tempVar,17,2),substr($tempVar,5,2),substr($tempVar,8,2),substr($tempVar,0,4));
	}
	
	/*function hex2bin($str) {
		$bin = "";
		$i = 0;
		do {
			$bin .= chr(hexdec($str{$i}.$str{($i + 1)}));
			$i += 2;
		} while ($i < strlen($str));
		return $bin;
	}*/
	
	function encX($item){
		$pwd = 'j6thk8rd5'.date('Y').'jhkjsdhsd';	
		return bin2hex(rc4crypt::encrypt($pwd, $item, 1));
	}
	
	function desencX($item){
		$pwd = 'j6thk8rd5'.date('Y').'jhkjsdhsd';
		return rc4crypt::decrypt($pwd, hex2bin($item), 1);
	}
	
	
	function diasTrans($fec1, $fec2){
		//defino fecha 1 
		$ano1 = getAno($fec1); 
		$mes1 = getMes($fec1); 
		$dia1 = getDia($fec1); 
		
		//defino fecha 2 
		$ano2 = getAno($fec2); 
		$mes2 = getMes($fec2); 
		$dia2 = getDia($fec2); 
		
		//calculo timestam de las dos fechas 
		$timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1); 
		$timestamp2 = mktime(0,0,0,$mes2,$dia2,$ano2); 
		
		//resto a una fecha la otra 
		$segundos_diferencia = $timestamp1 - $timestamp2; 
		//echo $segundos_diferencia; 
		
		//convierto segundos en días 
		$dias_diferencia = $segundos_diferencia / (60 * 60 * 24); 
		
		//obtengo el valor absoulto de los días (quito el posible signo negativo) 
		$dias_diferencia = abs($dias_diferencia); 
		
		//quito los decimales a los días de diferencia 
		$dias_diferencia = number_format($dias_diferencia,0); 
		
		return $dias_diferencia; 	
	}
	
	function diasTrans2($fec1, $fec2){
		//defino fecha 1 
		$ano1 = getAno($fec1); 
		$mes1 = getMes($fec1); 
		$dia1 = getDia($fec1); 
		
		//defino fecha 2 
		$ano2 = getAno($fec2); 
		$mes2 = getMes($fec2); 
		$dia2 = getDia($fec2); 
		
		//calculo timestam de las dos fechas 
		$timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1); 
		$timestamp2 = mktime(0,0,0,$mes2,$dia2,$ano2); 
		
		//resto a una fecha la otra 
		$segundos_diferencia = $timestamp1 - $timestamp2; 
		//echo $segundos_diferencia; 
		
		//convierto segundos en días 
		$dias_diferencia = $segundos_diferencia / (60 * 60 * 24); 
		
		//obtengo el valor absoulto de los días (quito el posible signo negativo) 
		$dias_diferencia = ($dias_diferencia); 
		
		//quito los decimales a los días de diferencia 
		$dias_diferencia = number_format($dias_diferencia,0); 
		
		return $dias_diferencia; 	
	}
	
	function compararFechas($fecha1, $fecha2){
		if($fecha2 >= $fecha1){
				return "OK";
		}else{
				return "Error";
		}
	}
	
	function compararFechas2($fecha1, $fecha2){
		if($fecha2 >= $fecha1){
				return "OK";
		}else{
				return "Error";
		}
	}
?>