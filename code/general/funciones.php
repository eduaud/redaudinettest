<?php
	
	include_once('class.rc4crypt.php');
	//Funciones PHP
	function retornaListaIdsNombres($strSQL, $id_origen = 'NO', $depende = 0, $val = 0, $order)
	{
		$htmltable = array();
		unset($ids);
		unset($names);
		$ids = array();
		$names = array();
		
		
		if($depende != 0)
		{
			$strSQL=str_replace('$IDD', $val, $strSQL);
		}		
		$strSQL1=$strSQL;
		if($id_origen != 'NO')
			$strSQL=str_replace('$ID', $id_origen, $strSQL);
			
		$strSQL=str_replace('$SUCURSAL', $_SESSION["USR"]->sucursalid, $strSQL);	
				
		
		//EXCEPCION PARA ALMACENES
		//-------              $G_TIPO_MOVIMIENTO
		$strSQL = str_replace('$G_SUB_TIPO_MOVIMIENTO',$_SESSION["USR"]->subtipo_movimiento, $strSQL);
		//excepciones de los almacenes  $G_AL_ALMACENES
		$strSQL = str_replace('$G_AL_CONDICION','', $strSQL);	
		//--------------------
		
		
		$strSQL.=" ".$order;
		
		 //echo "$strSQL<br>";
		if(!($resource0 = mysql_query($strSQL)))	die(" retornaListaIdsNombres Error en:<br><i>$strSQL</i><br><br>Descripcion:<br><b>".mysql_error()." <br>".$strSQL1);
		while($row0 = mysql_fetch_row($resource0))
		{
			array_push($ids,$row0[0]);
			array_push($names,$row0[1]);
		}
		mysql_free_result($resource0);
		$htmltable[0] =$ids;
		$htmltable[1] =$names;
		
		//print_r($htmltable);
		
		return  $htmltable;
		

		
	}
	
	function retornaListaIdsNombresBuscador($strSQL, $id_origen = 'NO')
	{
		$htmltable = array();
		unset($ids);
		unset($names);
		$ids = array();
		$names = array();		
		
		if($id_origen != 'NO')
			$strSQL=str_replace('$ID', $id_origen, $strSQL);
			
			
		$strSQL=str_replace('$SUCURSAL', $_SESSION["USR"]->sucursalid, $strSQL);		
		$strSQL=str_replace('$campo2', $valores[1], $strSQL);			
		
		//EXCEPCION PARA ALMACENES
		//-------
		$strSQL = str_replace('$G_SUB_TIPO_MOVIMIENTO',$_SESSION["USR"]->subtipo_movimiento, $strSQL);
		//excepciones de los almacenes  $G_AL_ALMACENES
		$strSQL = str_replace('$G_AL_CONDICION','', $strSQL);	
		//--------------------
		
		
		$strSQL.=" LIMIT 10";
		//die($strSQL);
		//echo "$strSQL<br>";
		
		if(!($resource0 = mysql_query($strSQL)))	die("retornaListaIdsNombresBuscador Error en:<br><i>$strSQL</i><br><br>Descripcion:<br><b>".mysql_error());
		while($row0 = mysql_fetch_row($resource0))
		{
			array_push($ids,$row0[0]);
			array_push($names,$row0[1]);
		}
		
		mysql_free_result($resource0);
		$htmltable[0] =$ids;
		$htmltable[1] =$names;
		return  $htmltable;
	}
	
	function retornaValor($strSQL, $id_origen = 'NO', $depende = 0, $val = 0)
	{
		$valor="";
		
		//echo "Dato:$id_origen<br>";
		
		if($depende != 0)
		{
			$strSQL=str_replace('$IDD', $val, $strSQL);
		}
		
		if($id_origen != 'NO')
			$strSQL=str_replace('$ID', $id_origen, $strSQL);
				
		$strSQL=str_replace('$SUCURSAL', $_SESSION["USR"]->sucursalid, $strSQL);			
		
		
		//EXCEPCION PARA ALMACENES
		//-------
		$strSQL = str_replace('$G_SUB_TIPO_MOVIMIENTO',$_SESSION["USR"]->subtipo_movimiento, $strSQL);
		//excepciones de los almacenes  $G_AL_ALMACENES
		$strSQL = str_replace('$G_AL_CONDICION','', $strSQL);	
		//--------------------
		
		
		
			
		if(!($resource0 = mysql_query($strSQL)))	die("retornaValor Error en:<br><i>$strSQL</i><br><br>Descripci&oacute;n:<br><b>".mysql_error());
		
		//echo $strSQL."<br><br>";
		
		//die();
		
		
		while($row0 = mysql_fetch_row($resource0))
		{
			//si el valor es nulo
			if(is_null($row0[1]))
				$valor=0;
			else
				$valor=$row0[1];
		}
		
		mysql_free_result($resource0);
		//echo $valor;
		return  $valor;
	}
	
	function retornaValorBuscador($strSQL, $id_origen = 'NO', $valores)
	{
		$valor="";
		
		//echo "Dato:$id_origen<br>";
		
		if($id_origen != 'NO')
			$strSQL=str_replace('$ID', $id_origen, $strSQL);
		
		
		//EXCEPCION PARA ALMACENES
		//-------
		$strSQL = str_replace('$G_SUB_TIPO_MOVIMIENTO',$_SESSION["USR"]->subtipo_movimiento, $strSQL);
		//excepciones de los almacenes  $G_AL_ALMACENES
		$strSQL = str_replace('$G_AL_CONDICION','', $strSQL);	
		//--------------------
		
		
		
			
		$strSQL=str_replace('$SUCURSAL', $_SESSION["USR"]->sucursalid, $strSQL);		
		$strSQL=str_replace('$campo2', $valores[1], $strSQL);	
		$strSQL.=" LIMIT 10";	
		
		//echo $strSQL."<br><br>";
				
		if(!($resource0 = mysql_query($strSQL)))	die("retornaValorBuscador Error en:<br><i>$strSQL</i><br><br>Descripci&oacute;n:<br><b>".mysql_error());
		
		//die();
		
		
		while($row0 = mysql_fetch_row($resource0))
		{
			//si el valor es nulo
			if(is_null($row0[1]))
				$valor=0;
			else
				$valor=$row0[1];
		}
		
		mysql_free_result($resource0);
		
		return  $valor;
	}
	
	
	function rollback($tabla,$errorSQL,$numError,$consulta)
	{
		//global $smarty,$link;
		mysql_query("ROLLBACK");
		/*
		require("./errores.php");
		$smarty->assign('contentheader',"Advertencia de error en el sistema");
		$smarty->assign('StrError',$errorSQL);
		$smarty->assign('NumError',$numError);
		$smarty->assign('DescError',$descError);
		$smarty->assign('Consulta',$consulta);
		$smarty->assign('rutaImagen',ROOTURL."modules/GEG/templates/default/media/");
		$smarty->display('error.tpl');*/
		
		if($numError == '1062' && $tabla == 'strInsert')
		{
			echo '<meta http-equiv="Refresh" content="3; url='.ROOTURL.'"> ';
			echo "<table width='100%' bgcolor='#C6E6F5'>";
			echo "<tr><td class='campos'>Error al insertar un cliente. Dos usuarios usaron simultaneamente al mismo prospecto.</td></tr>";
			echo "<tr><td class='campos'>Consulta:<br><i>$consulta</i><br><br>Error:<br><b>$errorSQL</b></td></tr>";
			echo "</table>";
		
			die();
		}
		
		if($numError == '1451' && $tabla == '$strUpdate')
		{
			echo '<meta http-equiv="Refresh" content="3; url='.ROOTURL.'"> ';
			echo "<table width='100%' bgcolor='#C6E6F5'>";
			echo "<tr><td class='campos'>Error al eliminar el usuario, Este ya esta relacionado con otras tablas en el sistema y no es posible eliminarlo.</td></tr>";
			echo "<tr><td class='campos'>Consulta:<br><i>$consulta</i><br><br>Error:<br><b>$errorSQL</b></td></tr>";
			echo "</table>";
		
			die();
		}
		
		echo "Error en la consulta:<br><i>" . $tabla ."</i></br><i>". $consulta."</i><br><br>Descripci&oacute;n:<br><b>".$errorSQL."</b><br>Error number: ".$numError;
		
		
		die();
	}
	
	//parametros de contabilidad
	function obtenParametrosAdmin()
	{
		$strSQL="SELECT id_parametro, descuento_max_por_liq_ant, tasa_de_interes_mensual, tasa_de_interes_moratorio_diario, apertura_de_credito_porcentaje, porcentaje_de_iva, tasa_de_interes_mensual_en_gar FROM hw_parametros_admin where id_parametro=1";
			$resultado = mysql_query($strSQL);
			$variable=mysql_fetch_assoc($resultado);
			mysql_free_result($resultado);
			return 	 $variable;				
	}
	
	function num2texto($numero, $moneda, $singular){
	//si es 0 el n&uacute;mero, no tiene caso procesar toda la informaci&oacute;n
		//die('x:'.$numero);
		if($numero==0 || !isset($numero)){
			return "CERO $moneda 00/100";
		}
	//en caso que sea un peso, pues igual que el 0 aparte que no muestre el plural "pesos"
		if($numero==1){
			return "UN $singular 00/100";
		}
		//$numeros["unidad"][0][0]="cero";
		$numeros["unidad"][1][0]="un";
		$numeros["unidad"][2][0]="dos";
		$numeros["unidad"][3][0]="tres";
		$numeros["unidad"][4][0]="cuatro";
		$numeros["unidad"][5][0]="cinco";
		$numeros["unidad"][6][0]="seis";
		$numeros["unidad"][7][0]="siete";
		$numeros["unidad"][8][0]="ocho";
		$numeros["unidad"][9][0]="nueve";
	
		$numeros["decenas"][1][0]="diez";
		$numeros["decenas"][2][0]="veinte";
		$numeros["decenas"][3][0]="treinta";
		$numeros["decenas"][4][0]="cuarenta";
		$numeros["decenas"][5][0]="cincuenta";
		$numeros["decenas"][6][0]="sesenta";
		$numeros["decenas"][7][0]="setenta";
		$numeros["decenas"][8][0]="ochenta";
		$numeros["decenas"][9][0]="noventa";
		$numeros["decenas"][1][1][0]="dieci";
		$numeros["decenas"][1][1][1]="once";
		$numeros["decenas"][1][1][2]="doce";
		$numeros["decenas"][1][1][3]="trece";
		$numeros["decenas"][1][1][4]="catorce";
		$numeros["decenas"][1][1][5]="quince";
		$numeros["decenas"][2][1]="veinti";
		$numeros["decenas"][3][1]="treinta y ";
		$numeros["decenas"][4][1]="cuarenta y ";
		$numeros["decenas"][5][1]="cincuenta y ";
		$numeros["decenas"][6][1]="sesenta y ";
		$numeros["decenas"][7][1]="setenta y ";
		$numeros["decenas"][8][1]="ochenta y ";
		$numeros["decenas"][9][1]="noventa y ";
		
		$numeros["centenas"][1][0]="cien";
		$numeros["centenas"][2][0]="doscientos ";
		$numeros["centenas"][3][0]="trescientos ";
		$numeros["centenas"][4][0]="cuatrocientos ";
		$numeros["centenas"][5][0]="quinientos ";
		$numeros["centenas"][6][0]="seiscientos ";
		$numeros["centenas"][7][0]="setecientos ";
		$numeros["centenas"][8][0]="ochocientos ";
		$numeros["centenas"][9][0]="novecientos ";
		$numeros["centenas"][1][1]="ciento ";
		
		$postfijos[1][0]="";
		$postfijos[10][0]="";
		$postfijos[100][0]="";
		$postfijos[1000][0]=" mil ";
		$postfijos[10000][0]=" mil ";
		$postfijos[100000][0]=" mil ";
		$postfijos[1000000][0]=" millon ";
		$postfijos[10000000][0]=" millon ";
		$postfijos[100000000][0]=" millon ";
		$postfijos[1000000][1]=" millones ";
		$postfijos[10000000][1]=" millones ";
		$postfijos[100000000][1]=" millones ";
	
		$decimal_break=".";
		//echo "test run on ".$numero."<br>";
		$entero=strtok($numero,$decimal_break);
		$decimal=strtok($decimal_break);
		if($decimal==""){
			$decimal="00";
		}
		if(strlen($decimal)<2){
			$decimal.="0";
		}
		if(strlen($decimal)>2){
			$decimal=substr($decimal,0,2);
		}
		//echo "entero ".$entero."<br> decimal ".$decimal."<br>";
	
		$entero_breakdown=$entero;
	
		$breakdown_key=1000000000000;
		$num_string="";
		while($breakdown_key>0.5){
			$breakdown["entero"][$breakdown_key]["number"]=floor($entero_breakdown/$breakdown_key);
			//echo " ".$breakdown["entero"][$breakdown_key]["number"]."<br>";
			if($breakdown["entero"][$breakdown_key]["number"]>0){
			//echo " further process <br>";
			$breakdown["entero"][$breakdown_key][100]=floor($breakdown["entero"][$breakdown_key]["number"]/100);
			$breakdown["entero"][$breakdown_key][10]=floor(($breakdown["entero"][$breakdown_key]["number"]%100)/10);
			$breakdown["entero"][$breakdown_key][1]=floor($breakdown["entero"][$breakdown_key]["number"]%10);
			//echo " 100 ->".$breakdown["entero"][$breakdown_key][100]."<br>";
			//echo " 10   ->".$breakdown["entero"][$breakdown_key][10]."<br>";
			//echo " 1     ->".$breakdown["entero"][$breakdown_key][1]."<br>";
			$hundreds=$breakdown["entero"][$breakdown_key][100];
			// if not a closed value at hundredths
			if(($breakdown["entero"][$breakdown_key][10]+$breakdown["entero"][$breakdown_key][1])>0){
				$chundreds=1;
			}else{
				$chundreds=0;
			}
			if(isset($numeros["centenas"][$hundreds][$chundreds])){
				//echo " centenas ".$numeros["centenas"][$hundreds][$chundreds]."<br>";
				$num_string.=$numeros["centenas"][$hundreds][$chundreds];
			}else{
				//echo " centenas ".$numeros["centenas"][$hundreds][0]."<br>";
				if(isset($numeros["centenas"][$hundreds][0])){
					$num_string.=$numeros["centenas"][$hundreds][0];
				}
			}
			if(($breakdown["entero"][$breakdown_key][1])>0){
				$ctens=1;
				$tens=$breakdown["entero"][$breakdown_key][10];
				//echo "NOT CLOSE TENTHS<br>";
				if(($breakdown["entero"][$breakdown_key][10])==1){
					if(($breakdown["entero"][$breakdown_key][1])<6){
						$cctens=$breakdown["entero"][$breakdown_key][1];
						//echo " decenas ".$numeros["decenas"][$tens][$ctens][$cctens]."<br>";
						$num_string.=$numeros["decenas"][$tens][$ctens][$cctens];
					}else{
						//echo " decenas ".$numeros["decenas"][$tens][$ctens][0]."<br>";
						$num_string.=$numeros["decenas"][$tens][$ctens][0];
					}
				}else{
					//echo " decenas ".$numeros["decenas"][$tens][$ctens]."<br>";
					if(isset($numeros["decenas"][$tens][$ctens])){
						$num_string.=$numeros["decenas"][$tens][$ctens];
					}
				}
			}else{
				//echo "CLOSED TENTHS<br>";
				$ctens=0;
				$tens=$breakdown["entero"][$breakdown_key][10];
				//echo " decenas ".$numeros["decenas"][$tens][$ctens]."<br>";
				if(isset($numeros["decenas"][$tens][$ctens])){
					$num_string.=$numeros["decenas"][$tens][$ctens];
				}
			}
			if(!(isset($cctens))){
				$ones=$breakdown["entero"][$breakdown_key][1];
				if(isset($numeros["unidad"][$ones][0])){
					//echo " tens ".$numeros["unidad"][$ones][0]."<br>";
					$num_string.=$numeros["unidad"][$ones][0];
				}
			}
			$cpostfijos=-1;
			if($breakdown["entero"][$breakdown_key]["number"]>1){
				$cpostfijos=1;
			}
			if(isset($postfijos[$breakdown_key][$cpostfijos])){
				$num_string.=$postfijos[$breakdown_key][$cpostfijos];
			}else{
				$num_string.=$postfijos[$breakdown_key][0];
			}
		}
		unset($cctens);
		$entero_breakdown%=$breakdown_key;
		$breakdown_key/=1000;
		//echo "CADENA ".$num_string."<br>";
		}
		if($moneda=='PESOS')
			$sufijo = ($decimal."/100 MXN");
		else
			$sufijo = '';
		
		return  strtoupper($num_string)." $moneda ".$sufijo;
	}
	
	

function valBuscador($strSQL)
{
	$resultado=mysql_query($strSQL);// or rollback('Error en:',mysql_error(),mysql_errno(),$strSQL);
	$renglon=mysql_fetch_row($resultado);
	
	$arr=array();
	$columnas=mysql_num_fields($resultado);
	for($i=0;$i<$columnas;$i++)
	{
		$arr[$i]=$renglon[$i];
	}	
	
	return $arr;
}
function retornaidencabezado($campo,$tabla)
{
	$strConsulta="SELECT id_encabezado FROM sys_config_encabezados WHERE campo='".$campo."' AND tabla='".$tabla."'";
	if(!($resource0 = mysql_query($strConsulta)))	die("Error en:<br><i>$strConsulta</i><br><br>Descripcion:<br><b>".mysql_error());
	$row=mysql_fetch_row($resource0);
	return $row[0];
}


	function error_display($query, $descripcion_error, $rollback, $file, $numero_error)
	{
		if($rollback == 1)
			mysql_query("ROLLBACK");
			
		$smarty->assign("consulta", $query);
		$smarty->assign("descripcion", $descripcion_error);
		$smarty->assign("file", $file);
		$smarty->assign("numero", $numero_error);	
		
		
		$smarty->display("error.tpl");
		
		die();	
	}

	function convertDate($fecha)
	{
		$aux1=explode(' ', $fecha);
		$aux=explode('/', $aux1[0]);
		$aux=$aux[2]."-".$aux[1]."-".$aux[0];
		if(sizeof($aux1) > 1)
			$aux.=" ".$aux1[1];
		return $aux;
	}
	
	function convertDate2($fecha)
	{
		$aux1=explode(' ', $fecha);
		$aux=explode('-', $aux1[0]);
		$aux=$aux[2]."/".$aux[1]."/".$aux[0];
		if(sizeof($aux1) > 1)
			$aux.=" ".$aux1[1];
		return $aux;
	}
	
	function decode($x)
	{
		$aux1=md5("Peugeot2010");
		$aux2=str_replace("_q", "=", $x);
		if(strlen($aux2) == (strlen($aux1)+3) && substr($aux2, 0, 1) == 'i')
		{
			$aux=substr($aux2, 2, substr($aux2, strlen($aux2)-2, 2)+0);
			//echo $aux."<br>";
		}
		else
		{
			$aux=substr($aux2, 3, strlen($aux2)-5);
			//echo $aux."<br>";
		}	
			
			
		return base64_decode($aux);	
	} 
	
	
	function encodeW($x)
	{
		$aux1=md5("Peugeot2010");
		$aux2=base64_encode($x);
		if(strlen($aux2) >= strlen($aux1))		
			$aux="c".substr($aux1, 0, 2).$aux2.substr($aux1, strlen($aux1)-2, 2);		
			
		else
		{
			$num=strlen($aux2);
			if($num < 10)
				$num="0".$num;
			$aux="i".substr($aux1,0,1).$aux2.substr($aux1, 1+strlen($aux2), strlen($aux1)-strlen($aux2)-1).$num; 
		}	
			
		$aux=str_replace("=", "_q", $aux);	
		
		return $aux;
	}
	

function mailPHP($subject, $body, $email, $bcc, $adjunto, $adjunto2 ,$fromMail = ''){
	include_once('../../include/phpmailer/class.phpmailer.php');
	$body = $body . "A: $email CC: $bcc";
	$email="amcsysandweb@gmail.com";

	$mail = new PHPMailer();
	$subject = $subject . ' ';

	$mail->From     = 'smtp_sw@sysandweb.com';
	$mail->FromName = ('FACTURACION ELECTRONICA');
	$mail->Host     = "sysandweb.com";
	$mail->Mailer   = "smtp";
	$mail->Port   = 465;
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = "ssl";
	$mail->Username = "smpt_facelec2014@sysandweb.com";
	$mail->Password = "SaWeRd2015";
	$mail->IsHTML(true);

	$mail->Subject = $subject;
	$mail->Body = $body;

	if($email != '')
	{
	$baux=explode(",", $email);
	for($icont=0;$icont<sizeof($baux);$icont++)
	$mail->AddAddress($baux[$icont]);
	}	
	if($bcc != "")
	{
	$baux=explode(",", $bcc);
	for($icont=0;$icont<sizeof($baux);$icont++)
	$mail->AddBCC($baux[$icont]);
	}		

	if ($adjunto != "") {
	$mail->AddAttachment($adjunto);
	}
	if ($adjunto2 != "") {
	$mail->AddAttachment($adjunto2);
	}	


	$exito = $mail->Send();

	if(!$exito)
		return $mail->ErrorInfo;
	unset($mail);
	

}
	

	
	
	function mailLayout($destinatario, $cuerpo)
	{
		$cuerpo = '
			<table bgcolor="#ececec" width="100%" cellpadding="0" cellspacing="0" style="font-family: Helvetica, Arial, sans-serif;">
			  <tr>
				<td width="20" rowspan="4">
				  <div style="width: 20px;"></div>
				</td>
				<td width="703">
				  <table width="700" align="center" cellpadding="0" cellspacing="0" border="0">
					<tr>
					  <td width="700">
						<table width="700" cellpadding="0"  cellspacing="0">
						  <tr width="700">
						   
							<td width="700" valign="top" height="122" style="width: 700px;"><!--<img border="0" src="http://sysnasser.net/system/imagenes/header_mail_nasser.jpg" width="700" height="110" align="left" hspace="30" vspace="0">-->
							  
							  <div style="clear: both;"></div>
							  
							</td>
						   
						  </tr>
						  <tr width="700">
							<td colspan="3" width="700" height="30" style="background-color: #ffffff;" bgcolor="#ffffff">&nbsp;</td>
						  </tr>
						  <tr width="700">
							<td width="700" colspan="3" align="center" style="background-color: #ffffff;" bgcolor="#ffffff">
							  <table width="640" cellpadding="0" cellspacing="0">
								
								<!-- title -->
								<tr>
								  <td style="color: #000; font-size: 20px; font-weight: bold; border-bottom: 1px solid #d9d9d9; padding-bottom: 6px;">Estimado: '.$destinatario.'</td>
								</tr>
								<tr>
								  <td height="10">&nbsp;</td>
								</tr>
								
								<!-- main content -->
								<tr>
								  <td>
									<table cellpadding="0" cellspacing="0" style="font-size: 13px; color: #333333;">
									  <tr>
										<td>
										<p style="font-size: 14px; color: #414042;">
											' . $cuerpo . '
										</p>
										</td>
										</tr>
									  </table>
								  <p>&nbsp;</p></td>
								</tr>
								
								
		  </table>        </td>
						  </tr>
						  <tr width="700">
							<td width="700" height="18" colspan="3" align="center" bgcolor="#ffffff" style="background-color: #ffffff;"><p style="font-size: 10px; color: #999;">
							AUDICEL</p>
							<p style="font-size: 10px; color: #999;">&nbsp;</p></td>
						  </tr>
						  <tr width="700">
							<td width="700" colspan="3" height="20">&nbsp;</td>
						  </tr>
						</table>
					  </td>
		</tr>
				  </table>
				</td>
				<td width="18" rowspan="4">
				  <div style="width: 20px;"></div>
				</td>
			  </tr>
		  </table>
		';
		return $cuerpo;
	}
	
	
	//nuevas funciones
	function obtenIDFActurarporCompania($id_compania)
	{
		if($id_compania == '')
			$id_compania=1;
		
		$strConsulta="SELECT prefijo_facturas_recibos_honorarios,folio_inicial_facturas_recibos_honorarios FROM anderp_companias WHERE id_compania='".$id_compania."' ";
		//die($strConsulta);		
		if(!($resource0 = mysql_query($strConsulta)))	die("Error en:<br><i>$strConsulta</i><br><br>Descripcion:<br><b>".mysql_error());
		
		$row=mysql_fetch_row($resource0);
		
		//***********************************
		//----retornamos el prefijo
		$prefijo = str_replace(' ','',$row[0]);
		//***********************************
		
		$folioInicial = str_replace(' ','',$row[1]);
		
		//prefijo activo
		if($prefijo=='')
		{
			//obtenemos el count de los valores existentes
			$strConsulta = "SELECT
			                count(consecutivo)
							FROM  anderp_facturas
							where (prefijo ='' or prefijo is null)
							and id_compania='".$id_compania."'";			
			if(!($resource1 = mysql_query($strConsulta)))	die("Error en:<br><i>$strConsulta</i><br><br>Descripcion:<br><b>".mysql_error());
			$row1=mysql_fetch_row($resource1);
			//si no hay facturas dadas de altas
			if($row1[0]==0)
			{
				//obtenemos el folio inicia le al
				 if($folioInicial=='')
				 {
				 	$folio=1;	
				 } 
				 else
				 {
				 	$folio=$folioInicial;	
				 }
			}
			else
			{
				//aqui ya existe al menos un registro en la base de datos
				$strConsulta = "SELECT
				                MAX(consecutivo)+1
								FROM anderp_facturas
								where prefijo = '$prefijo' and id_compania='".$id_compania."'";
				if(!($resource2 = mysql_query($strConsulta)))	die("Error en:<br><i>$strConsulta</i><br><br>Descripcion:<br><b>".mysql_error());
				$row2=mysql_fetch_row($resource2);
				$folio=$row2[0];
			}
			
		}
		else
		{
			//obtenemos el count de los valores existentes
			$strConsulta = "SELECT count(consecutivo) FROM anderp_facturas where (prefijo ='".$prefijo."') and id_compania='".$id_compania."'";			
			if(!($resource1 = mysql_query($strConsulta)))	die("Error en:<br><i>$strConsulta</i><br><br>Descripcion:<br><b>".mysql_error());
			$row1=mysql_fetch_row($resource1);
			//si no hay facturas dadas de altas
			if($row1[0]==0)
			{
				//obtenemos el folio inicia le al
				  if($folioInicial=='')
				 {
				 	$folio=1;	
				 } 
				 else
				 {
				 	$folio=$folioInicial;	
				 }
			}
			else
			{
				//aqui ya existe al menos un registro en la base de datos
				$strConsulta = "SELECT MAX(consecutivo)+1 FROM anderp_facturas where (prefijo ='".$prefijo."') and id_compania='".$id_compania."'";
				if(!($resource2 = mysql_query($strConsulta)))	die("Error en:<br><i>$strConsulta</i><br><br>Descripcion:<br><b>".mysql_error());
				$row2=mysql_fetch_row($resource2);
				$folio=$row2[0];
			}
		
		}
		
		//obtenIDFActurarporCompania(id_compania)
		//folio_inicial_facturas_recibos_honorarios
		return $prefijo."|".$folio;
	
}
	
	function obtenIDNotaporCompania($id_compania)
	{
			
		$strConsulta="SELECT
		              prefijo_nc_recibos_honorarios,
					  folio_nc_facturas_recibos_honorarios
					  FROM felec_companias
					  WHERE id_compania='".$id_compania."' ";
		if(!($resource0 = mysql_query($strConsulta)))	die("Error en:<br><i>$strConsulta</i><br><br>Descripcion:<br><b>".mysql_error());
		
		$row=mysql_fetch_row($resource0);
		
		//***********************************
		//----retornamos el prefijo
		$prefijo = str_replace(' ','',$row[0]);
		//***********************************
		
		$folioInicial = str_replace(' ','',$row[1]);
		
		//prefijo activo
		if($prefijo=='')
		{
			//obtenemos el count de los valores existentes
			$strConsulta = "SELECT
			                count(consecutivo) as totales
							FROM felec_notas_credito
							WHERE (prefijo ='' or prefijo is null)
							and id_compania='".$id_compania."'";			
			if(!($resource1 = mysql_query($strConsulta)))	die("Error en:<br><i>$strConsulta</i><br><br>Descripcion:<br><b>".mysql_error());
			$row1=mysql_fetch_row($resource1);
			//si no hay facturas dadas de altas
			if($row1[0]==0)
			{
				//obtenemos el folio inicia le al
				  if($folioInicial=='')
				 {
				 	$folio=1;	
				 } 
				 else
				 {
				 	$folio=$folioInicial;	
				 }
				 
			}
			else
			{
				//aqui ya existe al menos un registro en la base de datos
				$strConsulta = "SELECT MAX(consecutivo)+1 FROM felec_notas_credito where (prefijo ='' or prefijo is null) and id_compania='".$id_compania."'";
				if(!($resource2 = mysql_query($strConsulta)))	die("Error en:<br><i>$strConsulta</i><br><br>Descripcion:<br><b>".mysql_error());
				$row2=mysql_fetch_row($resource2);
				$folio=$row2[0];
			}
			
		}
		else
		{
			//obtenemos el count de los valores existentes
			$strConsulta = "SELECT
			                IF(MAX(consecutivo) IS NULL, 0, MAX(consecutivo)) as  totales
							FROM felec_notas_credito
							where prefijo ='".$prefijo."'
							and id_compania='".$id_compania."'";			
							
			
			if(!($resource1 = mysql_query($strConsulta)))	die("Error en:<br><i>$strConsulta</i><br><br>Descripcion:<br><b>".mysql_error());
			$row1=mysql_fetch_row($resource1);
			//si no hay facturas dadas de altas
			if($row1[0]==0)
			{
				//obtenemos el folio inicia le al
				  if($folioInicial=='')
				 {
				 	$folio=1;	
				 } 
				 else
				 {
				 	$folio=$folioInicial;	
				 }
			}
			else
			{
				//aqui ya existe al menos un registro en la base de datos
				$strConsulta = "SELECT MAX(consecutivo)+1 FROM felec_notas_credito where (prefijo ='".$prefijo."') and id_compania='".$id_compania."'";
				if(!($resource2 = mysql_query($strConsulta)))	die("Error en:<br><i>$strConsulta</i><br><br>Descripcion:<br><b>".mysql_error());
				$row2=mysql_fetch_row($resource2);
				$folio=$row2[0];
			}
		
		}
		//obtenIDFActurarporCompania(id_compania)
		//folio_inicial_facturas_recibos_honorarios
		return $prefijo."|".$folio;
	}
	
	//funcion que obtiene si el consecitivo existe
	function existeFactura($compania,$idFactura)
	{
		$strConsulta = "SELECT count(consecutivo) as  total FROM felec_facturas where id_factura ='".$idFactura."' and id_compania='".$id_compania."'";			
		if(!($resource1 = mysql_query($strConsulta)))	die("Error en:<br><i>$strConsulta</i><br><br>Descripcion:<br><b>".mysql_error());
		$row1=mysql_fetch_row($resource1);
		if($row1[0]>0)
		{
			//retorna lo quence
		}
	}
	
	function existeNotaCredito($compania,$idNota)
	{
		$strConsulta = "SELECT count(consecutivo) as  total FROM felec_notas_credito where id_nota_credito ='".$idNota."' and id_compania='".$id_compania."'";			
		if(!($resource1 = mysql_query($strConsulta)))	die("Error en:<br><i>$strConsulta</i><br><br>Descripcion:<br><b>".mysql_error());
		$row1=mysql_fetch_row($resource1);
		if($row1[0]>0)
		{
			//retorna lo quence
		}
		else
		{
			//retorna serie y folio
		}
	}
	
function redimenzionarImagen($rutaOriginal, $rutaDestino, $maximo_ancho, $maximo_alto)
{
	//Ruta de la imagen original
	$rutaImagenOriginal=$rutaOriginal;
	
	//Creamos una variable imagen a partir de la imagen original
	$img_original = imagecreatefromjpeg($rutaImagenOriginal);
	
	//Se define el maximo ancho o alto que tendra la imagen final
	$max_ancho = $maximo_ancho;
	$max_alto = $maximo_alto;
	
	//Ancho y alto de la imagen original
	list($ancho,$alto)=getimagesize($rutaImagenOriginal);
	
	//Se calcula ancho y alto de la imagen final
	$x_ratio = $max_ancho / $ancho;
	$y_ratio = $max_alto / $alto;
	
	//Si el ancho y el alto de la imagen no superan los maximos, 
	//ancho final y alto final son los que tiene actualmente
	if( ($ancho <= $max_ancho) && ($alto <= $max_alto) ){//Si ancho 
		$ancho_final = $ancho;
		$alto_final = $alto;
	}
	/*
	 * si proporcion horizontal*alto mayor que el alto maximo,
	 * alto final es alto por la proporcion horizontal
	 * es decir, le quitamos al alto, la misma proporcion que 
	 * le quitamos al alto
	 * 
	*/
	elseif (($x_ratio * $alto) < $max_alto){
		$alto_final = ceil($x_ratio * $alto);
		$ancho_final = $max_ancho;
	}
	/*
	 * Igual que antes pero a la inversa
	*/
	else{
		$ancho_final = ceil($y_ratio * $ancho);
		$alto_final = $max_alto;
	}
	
	//Creamos una imagen en blanco de tamaño $ancho_final  por $alto_final .
	$tmp=imagecreatetruecolor($ancho_final,$alto_final);	
	
	//Copiamos $img_original sobre la imagen que acabamos de crear en blanco ($tmp)
	imagecopyresampled($tmp,$img_original,0,0,0,0,$ancho_final, $alto_final,$ancho,$alto);
	
	//Se destruye variable $img_original para liberar memoria
	imagedestroy($img_original);
	
	//Definimos la calidad de la imagen final
	$calidad=95;
	
	//Se crea la imagen final en el directorio indicado
	imagejpeg($tmp, $rutaDestino, $calidad);
	
	/* SI QUEREMOS MOSTRAR LA IMAGEN EN EL NAVEGADOR
	 * 
	 * descomentamos las lineas 64 ( Header("Content-type: image/jpeg"); ) y 65 ( imagejpeg($tmp); ) 
	 * y comentamos la linea 57 ( imagejpeg($tmp,"./imagen/retoque.jpg",$calidad); )
	 */ 
	//Header("Content-type: image/jpeg");
	//imagejpeg($tmp);	
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
		$pwd = 'SDfrf525d';	
		return bin2hex(rc4crypt::encrypt($pwd, 'eujda4d' . $item, 1));
	}
	
	function desencX($item){
		$pwd = 'SDfrf525d';
		return str_replace('eujda4d', '', rc4crypt::decrypt($pwd, hex2bin($item), 1));
	}
	
	function retornaIDCatalogoOrden($campo,$tabla)
{
	
	//$strConsulta="SELECT orden FROM sys_config_encabezados WHERE campo='".$campo."' AND tabla='".$tabla."'";
	$strConsulta="SELECT rownum-1 as row1 FROM(
SELECT @rownum:=@rownum+1 AS rownum,campo
FROM
(SELECT (SELECT @rownum:=0) r ,campo FROM sys_config_encabezados WHERE tabla='".$tabla."' order by orden) as aux
) as dat where campo='".$campo."' ";
	
			
	
	if(!($resource0 = mysql_query($strConsulta)))	die("Error en:<br><i>$strConsulta</i><br><br>Descripcion:<br><b>".mysql_error());
	$row=mysql_fetch_row($resource0);
	return $row[0];
	
}


	function create_guid($namespace = '') {     
		static $guid = '';
		$uid = uniqid("", true);
		$data = $namespace;
		$data .= $_SERVER['REQUEST_TIME'];
		$data .= $_SERVER['HTTP_USER_AGENT'];
		$data .= $_SERVER['LOCAL_ADDR'];
		$data .= $_SERVER['LOCAL_PORT'];
		$data .= $_SERVER['REMOTE_ADDR'];
		$data .= $_SERVER['REMOTE_PORT'];
		$hash = strtoupper(hash('ripemd128', $uid . $guid . md5($data)));
		$guid = '[' .   
				substr($hash,  0,  8) . 
				'-' .
				substr($hash,  8,  4) .
				'-' .
				substr($hash, 12,  4) .
				'-' .
				substr($hash, 16,  4) .
				'-' .
				substr($hash, 20, 12) .
				']';
		return $guid;
	}
	
	function calculaPorcentajesMonto($monto, $porcentaje, $accion){
			$monto = is_numeric($monto) ? $monto : 0;
			$porcentaje = is_numeric($porcentaje) ? $porcentaje : 0;
			$montoPorcentaje = $monto * $porcentaje;
			$montoPorcentaje = $montoPorcentaje / 100;
			
			if($accion == 1){
					return $montoPorcentaje;
					}
			else if($accion == 2){
					$total = $monto + $montoPorcentaje;
					return $total;
					}
			else if($accion == 3){
					$total = $monto - $montoPorcentaje;
					return $total;
					}
			
			}
	
	function enviarCorreosGrupo($id_correo, $adjunto, $arrayMailsCC)
	{
		$sqlDatosMail = "
			SELECT descripcion, cuerpo, cc, subject
			FROM sys_correos_enviar
			WHERE id_correo = $id_correo
		";
		mysql_query("SET NAMES 'utf8'") or die("Error en:<br><i>$sqlSolicitud</i><br><br>Descripcion:<br><b>".mysql_error());
		$resDatosMail = mysql_query($sqlDatosMail) or die("Error en:<br><i>$sqlSolicitud</i><br><br>Descripcion:<br><b>".mysql_error());
		$rowDatosMail = mysql_fetch_assoc($resDatosMail);
		
		$emails = array();
		array_merge($emails, $arrayMailsCC);
			
		$sqlDatosMailDest = "
			SELECT DISTINCT c.email
			FROM sys_correos_enviar_destinatarios a
			LEFT JOIN sys_usuarios_grupos b ON a.id_grupo = b.id_grupo
			LEFT JOIN sys_usuarios c ON b.id_usuario = c.id_usuario
			WHERE a.id_correo = $id_correo
		";
		$resDatosMailDest = mysql_query($sqlDatosMailDest) or die("Error en:<br><i>$sqlSolicitud</i><br><br>Descripcion:<br><b>".mysql_error());
		while($rowDatosMailDest = mysql_fetch_assoc($resDatosMailDest))
		{
			array_push($emails, $rowDatosMailDest['email']);
		}
		
		mailPHP(
			$rowDatosMail['subject'], 
			mailLayoutGeneral("$destinatario", utf8_decode($rowDatosMail['cuerpo'])), 
			implode(",", $emails), 
			"",
			 $adjunto
		);
	}

	function obtenerCuentasContablesNivel1(){
		
		$arrayConsulta = array();
		
		$sql = "SELECT id_cuenta_contable,cuenta_contable,nombre, id_cuenta_superior FROM scfdi_cuentas_contables WHERE nivel=1 AND visible_arbol=1 AND activo=1";
		$oCuentasContables = mysql_query($sql) or die(mysql_error);
		
		while($aCuentasContables = mysql_fetch_array($oCuentasContables)){
				array_push($arrayConsulta, array($aCuentasContables['id_cuenta_contable'],$aCuentasContables['cuenta_contable'],$aCuentasContables['nombre'],$aCuentasContables['id_cuenta_superior']));
		}
		
		return $arrayConsulta;
		
	}
	
	function obtenerCuentasContablesNivel2($id_cuenta_superior){
		
		$arrayConsulta = array();
		
		$sql = "SELECT id_cuenta_contable,cuenta_contable,nombre, id_cuenta_superior FROM scfdi_cuentas_contables WHERE nivel=2 AND visible_arbol=1 AND id_cuenta_superior='$id_cuenta_superior' AND activo=1";
		$oCuentasContablesNivel2 = mysql_query($sql) or die(mysql_error);
		
		while($aCuentasContablesNivel2 = mysql_fetch_array($oCuentasContablesNivel2)){
				array_push($arrayConsulta, array($aCuentasContablesNivel2['id_cuenta_contable'],$aCuentasContablesNivel2['cuenta_contable'],$aCuentasContablesNivel2['nombre'],$aCuentasContablesNivel2['id_cuenta_superior']));
		}
		
		return $arrayConsulta;
		
	}
	
	function obtenerCuentasContablesNivel3($id_cuenta_contable){
		
		$arrayConsulta = array();
		
		$sql = "SELECT id_cuenta_contable, nombre, id_cuenta_superior, id_cuenta_mayor FROM scfdi_cuentas_contables WHERE nivel=3 AND visible_arbol=1 AND id_cuenta_superior='$id_cuenta_contable' AND activo=1";
		$oCuentasContablesNivel3 = mysql_query($sql) or die(mysql_error);
		
		while($aCuentasContablesNivel3 = mysql_fetch_array($oCuentasContablesNivel3)){
				array_push($arrayConsulta, array($aCuentasContablesNivel3['id_cuenta_contable'],$aCuentasContablesNivel3['nombre'],$aCuentasContablesNivel3['id_cuenta_superior'],$aCuentasContablesNivel3['id_cuenta_mayor']));
		}
		
		return $arrayConsulta;
	}
	
	function obtenerGenerosCuentasContables(){
		$arrayConsulta = array();
		
		$sql = "SELECT id_genero_cuenta_contable, nombre FROM scfdi_generos_cuentas_contables WHERE  activo=1";
		$oGenerosCuentasContables = mysql_query($sql) or die(mysql_error);
		
		while($aGenerosCuentasContables = mysql_fetch_array($oGenerosCuentasContables)){
				array_push($arrayConsulta, array($aGenerosCuentasContables['id_genero_cuenta_contable'],$aGenerosCuentasContables['nombre']));
		}
		
		return $arrayConsulta;
	}
	
	function  obtenerCuentasMayores(){
	
		$arrayConsulta = array();
		
		$sql = "SELECT id_cuenta_contable,cuenta_contable, nombre FROM scfdi_cuentas_contables WHERE  nivel=1 AND activo=1";
		$oCuentasMayores = mysql_query($sql) or die(mysql_error);
		
		while($aCuentasMayores = mysql_fetch_array($oCuentasMayores)){
				array_push($arrayConsulta, array($aCuentasMayores['id_cuenta_contable'],$aCuentasMayores['cuenta_contable'],$aCuentasMayores['nombre']));
		}
		
		return $arrayConsulta;
	
	}
	
	function obtenerCuentaSuperiorPorCuentaMayor($id_cuenta_mayor){
		$arrayConsulta = array();
				
		
		$sql = "SELECT id_cuenta_contable,cuenta_contable, nombre FROM scfdi_cuentas_contables WHERE  id_cuenta_mayor='$id_cuenta_mayor' AND nivel=2 AND activo=1 AND visible_arbol=1";
		$oCuentaSuperior = mysql_query($sql) or die(mysql_error);
		
		while($aCuentaSuperior = mysql_fetch_array($oCuentaSuperior)){
				array_push($arrayConsulta, array($aCuentaSuperior['id_cuenta_contable'],$aCuentaSuperior['cuenta_contable'],$aCuentaSuperior['nombre']));
		}
		
		return $arrayConsulta;
	}
	function obtenerCuentaSAT($idCuenta){
		$arrayConsulta = array();
		$sql = "SELECT con_cuentas_agrupadoras_sat.id_cuenta_sat,cuenta_sat,nombre_cuenta_sat 
					FROM con_cuentas_agrupadoras_sat 
					LEFT JOIN scfdi_cuentas_contables
					ON con_cuentas_agrupadoras_sat.id_cuenta_sat=scfdi_cuentas_contables.id_cuenta_sat WHERE scfdi_cuentas_contables.id_cuenta_contable=".$idCuenta;
		
		$oDetalleCuentaSAT = mysql_query($sql) or die(mysql_error);
		
		while($aDetalleCuentaSAT = mysql_fetch_array($oDetalleCuentaSAT)){
			array_push($arrayConsulta,array($aDetalleCuentaSAT['id_cuenta_sat'],$aDetalleCuentaSAT['cuenta_sat'],$aDetalleCuentaSAT['nombre_cuenta_sat']));
		}
		return $arrayConsulta;
	}
	function obtenerDetalleDeCuentaContable($idCuenta){
		$arrayConsulta = array();
		
		$sql = "SELECT id_cuenta_contable,cuenta_contable, nombre, nivel, es_cuenta_mayor, id_genero_cuenta_contable, facturable, id_cuenta_superior, id_cuenta_mayor, visible_arbol, en_poliza, activo, niveles
						FROM scfdi_cuentas_contables WHERE id_cuenta_contable='$idCuenta'";
		$oDetalleCuenta = mysql_query($sql) or die(mysql_error);
		
		while($aDetalleCuenta = mysql_fetch_array($oDetalleCuenta)){
				array_push($arrayConsulta, array($aDetalleCuenta['id_cuenta_contable'],$aDetalleCuenta['cuenta_contable'],utf8_encode($aDetalleCuenta['nombre']), $aDetalleCuenta['nivel'], $aDetalleCuenta['es_cuenta_mayor'], 
																			   $aDetalleCuenta['id_genero_cuenta_contable'], $aDetalleCuenta['facturable'], $aDetalleCuenta['id_cuenta_superior'], 
																			   $aDetalleCuenta['id_cuenta_mayor'], $aDetalleCuenta['visible_arbol'], $aDetalleCuenta['en_poliza'], $aDetalleCuenta['activo'],
																			   $aDetalleCuenta['niveles'])
									);
		}
		
		return $arrayConsulta;
	}function obtenerDetalleDeCuentaPorCuenta($Cuenta){
		$arrayConsulta = array();
		
		$sql = "SELECT id_cuenta_contable,cuenta_contable, nombre, nivel, es_cuenta_mayor, id_genero_cuenta_contable, facturable, id_cuenta_superior, id_cuenta_mayor, visible_arbol, en_poliza, activo, niveles
						FROM scfdi_cuentas_contables WHERE cuenta_contable='$Cuenta'";
		$oDetalleCuenta = mysql_query($sql) or die(mysql_error);
		
		while($aDetalleCuenta = mysql_fetch_array($oDetalleCuenta)){
				array_push($arrayConsulta, array($aDetalleCuenta['id_cuenta_contable'],$aDetalleCuenta['cuenta_contable'],utf8_encode($aDetalleCuenta['nombre']), $aDetalleCuenta['nivel'], $aDetalleCuenta['es_cuenta_mayor'],$aDetalleCuenta['id_genero_cuenta_contable'], $aDetalleCuenta['facturable'], $aDetalleCuenta['id_cuenta_superior'],$aDetalleCuenta['id_cuenta_mayor'], $aDetalleCuenta['visible_arbol'], $aDetalleCuenta['en_poliza'], $aDetalleCuenta['activo'],
																									   $aDetalleCuenta['niveles'])
									);
		}
		
		return $arrayConsulta;
	}

	function verificarSiPuedeEliminarse($id_cuenta_contable,$nivel){
		
		/* 
		** 1° Se verifica que no tenga documentos asociados o exista en algún documento
		** 2° Se realiza una Búsqueda para verificar que la Cuenta Contable no tenga subcuentas asociadas
		** La variable $respuesta tendrá 3 posibles valores: 
		** $respuesta = 0 (Indica que la cuenta puede eliminarse de la base de datos)
		** $respuesta = 1 (Indica que la cuenta tiene subcuentas asociadas)
		** $respuesta = 2 (Indica que la cuenta esta relacionada con otros documentos)
		**
		** AJAX lo interpreta y realiza la acción correspondiente
		*/
		
		switch ($nivel){
		
			case ($nivel == "1" || $nivel == "2"):
			
					// La siguiente función regresa true si la cuenta contable tiene cuentas asociadas
					$respuesta = verificaSiTieneSubcuentasAsociadas($id_cuenta_contable,$nivel);
					if($respuesta == true){
							$respuesta = 1;
					}
					
					if($respuesta != true){
							// La siguiente función regresa true si encontro la cuenta contable relacionada con otros documentos
							$respuesta = verificaSiTieneDocumentosAsociados($id_cuenta_contable);
							if($respuesta == true){
								$respuesta = 2;
							}
							else{
								$respuesta = 0;
							}
					}
					
					return $respuesta;
					
				break;
			
			case "3":
					
						// La siguiente función regresa true si encontro la cuenta contable relacionada con otros documentos
						$respuesta = verificaSiTieneDocumentosAsociados($id_cuenta_contable);
						if($respuesta == true){
							$respuesta = 2;
						}
						else{
							$respuesta = 0;
						}
					
					return $respuesta;
					
				break;
				
		}
		
	}
	
	function verificaSiTieneDocumentosAsociados($id_cuenta_contable){
	
		return false;
		
	}
	
	function verificaSiTieneSubcuentasAsociadas($id_cuenta_contable,$nivel){
		
		if($nivel == 2){
			
			$sql 	  = "SELECT id_cuenta_contable FROM scfdi_cuentas_contables WHERE id_cuenta_superior='$id_cuenta_contable'";
			$result = mysql_query($sql);
			
			$numSubcuentas = mysql_num_rows($result);
			
			if($numSubcuentas > 0){
				return true;
			}
			else{
				return false;
			}

		}
		
		if($nivel == 1){
			
			$sql 	  = "SELECT id_cuenta_contable FROM scfdi_cuentas_contables WHERE id_cuenta_superior='$id_cuenta_contable' AND nivel=2";
			$result = mysql_query($sql);
			
			$numSubcuentas = mysql_num_rows($result);
			
			if($numSubcuentas > 0){
				return true;
			}
			else{
				return false;
			}

		}
		
	}
	
	function eliminaCuentaContable($id_cuenta_contable){
	
		$sql="DELETE FROM scfdi_cuentas_contables WHERE id_cuenta_contable='$id_cuenta_contable' ";
		mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:\n".mysql_error());
		
	}
	
	function verificaCamposQuePuedenEditarse($id_cuenta_contable,$nivel){
	
		/* 
		** 1° Se verifica que no tenga documentos asociados o exista en algún documento
		** 2° Se realiza una Búsqueda para verificar que la Cuenta Contable no tenga subcuentas asociadas
		** La variable $respuesta tendrá 3 posibles valores: 
		** $respuesta = 0 (Indica que la cuenta puede ser editada completamente)
		** $respuesta = 1 (Indica que la cuenta tiene subcuentas asociadas y solo puede editar el nombre de la cuenta, todos los demás campos se bloquean)
		** $respuesta = 2 (Indica que la cuenta esta relacionada con otros documentos y solo se podrá editar el nombre de la cuenta, todos los demás campos se bloquean)
		**
		** AJAX lo interpreta y envía la información correspondiente a formulario de editar para notificación al usuario
		*/
		
		switch ($nivel){
		
			case ($nivel == "1" || $nivel == "2"):
			
					// La siguiente función regresa true si la cuenta contable tiene cuentas asociadas
					$respuesta = verificaSiTieneSubcuentasAsociadas($id_cuenta_contable,$nivel);
					if($respuesta == true){
							$respuesta = 1;
					}
					
					if($respuesta != true){
							// La siguiente función regresa true si encontro la cuenta contable relacionada con otros documentos
							$respuesta = verificaSiTieneDocumentosAsociados($id_cuenta_contable);
							if($respuesta == true){
								$respuesta = 2;
							}
							else{
								$respuesta = 0;
							}
					}
					
					return $respuesta;
					
				break;
			
			case "3":
					
						// La siguiente función regresa true si encontro la cuenta contable relacionada con otros documentos
						$respuesta = verificaSiTieneDocumentosAsociados($id_cuenta_contable);
						if($respuesta == true){
							$respuesta = 2;
						}
						else{
							$respuesta = 0;
						}
					
					return $respuesta;
					
				break;
				
		}
	}
	
	function obtenerPropiedadesCuentaMayorHeredar($id_cuenta_mayor){
		$arrayConsulta = array();
		
		$sql = "SELECT id_genero_cuenta_contable, facturable, id_cuenta_sat FROM scfdi_cuentas_contables WHERE  nivel=1 AND activo=1 AND visible_arbol=1 AND id_cuenta_mayor='$id_cuenta_mayor'";
		$oCuentasMayores = mysql_query($sql) or die(mysql_error);
		
		while($aCuentasMayores = mysql_fetch_array($oCuentasMayores)){
				array_push($arrayConsulta, array($aCuentasMayores['id_genero_cuenta_contable'],$aCuentasMayores['facturable'],$aCuentasMayores['id_cuenta_sat']));
		}
		
		return $arrayConsulta;
	}
	
	function obtenerNivelesDeLaCuenta($id_cuenta_mayor){
		$niveles=0;
		
		$sql = "SELECT niveles FROM scfdi_cuentas_contables WHERE id_cuenta_mayor='$id_cuenta_mayor'";
		$queryNiveles = mysql_query($sql) or die(mysql_error);
		$colNiveles=mysql_fetch_assoc($queryNiveles);
		
		if($colNiveles["niveles"] != "Null" && $colNiveles["niveles"] != "" && $colNiveles["niveles"] != "null"){
			$niveles=$colNiveles["niveles"];
		}
				
		return $niveles;
	}
	
	function convierteFecha($fecha){
		$corteFecha = explode('/', $fecha);
		$fechaFinal = $corteFecha[2] . "-" . $corteFecha[1] . "-" . $corteFecha[0];
		return $fechaFinal;
		}
		
	function calculaPorcentaje($monto, $descuento){
			$monto = is_numeric($monto) ? $monto : 0;
			$descuento = is_numeric($descuento) ? $descuento : 0;
			$porcentaje = ($monto * $descuento) / 100;
			$total = $monto - $porcentaje;
			return $total;
			}

/**********Funcion que aparta y desaparta los productos*****************/			
function apartaDesapartaProductos($pedido){
		date_default_timezone_set('America/Mexico_City');
				
		/**************Descuento***********************/
		$sql = "SELECT ad_pedidos.descuento_aprobado AS descuento, id_pedido, total FROM ad_pedidos WHERE id_control_pedido = " . $pedido;
		$datos = new consultarTabla($sql);
		$descuento = $datos -> obtenerLineaRegistro();
				
		/**************Pagos***********************/
		$sql = "SELECT na_pedidos_detalle_pagos.monto AS pagos
				FROM na_pedidos_detalle_pagos
				WHERE na_pedidos_detalle_pagos.id_control_pedido = " . $pedido . " AND na_pedidos_detalle_pagos.confirmado <> 2 AND na_pedidos_detalle_pagos.cancelado = 0";
		$datos = new consultarTabla($sql);
		$result = $datos -> obtenerRegistros();
		$pagos_confirmados = 0; 
		foreach($result as $dato){
				$pagos_confirmados +=  calculaPorcentaje($dato -> pagos, $descuento['descuento']);
				}
								
		/**************Suma de Productos Inmediatos y programados***********************/
		$sql = "SELECT na_pedidos_detalle.importe AS monto_productos, na_pedidos_detalle.id_tipo_entrega AS tipo_entrega
				FROM na_pedidos_detalle
				WHERE na_pedidos_detalle.id_control_pedido = " . $pedido . " AND id_estatus = 1";
		$datos = new consultarTabla($sql);
		$result = $datos -> obtenerRegistros();
		$produtos_inmediatos = 0;  
		$produtos_programados = 0;  
		foreach($result as $dato){
				if($dato -> tipo_entrega == 2) //Inmediatos
						$produtos_inmediatos +=  calculaPorcentaje($dato -> monto_productos, $descuento['descuento']);
				if($dato -> tipo_entrega == 1) //Programados
						$produtos_programados +=  calculaPorcentaje($dato -> monto_productos, $descuento['descuento']);
				}
								
		/**************FLETES***********************/
		$sql = "SELECT (precio * numero_camiones) AS monto_fletes
				FROM na_pedidos_detalle_fletes
				WHERE na_pedidos_detalle_fletes.id_control_pedido = " . $pedido;
		$datos = new consultarTabla($sql);
		$result = $datos -> obtenerRegistros();
		$monto_fletes = 0;  
		foreach($result as $dato){
				$monto_fletes +=  calculaPorcentaje($dato -> monto_fletes, $descuento['descuento']);
				}
		/*$adeudo = $produtos_inmediatos + $produtos_programados + $monto_fletes;
		$adeudo = calculaPorcentaje($adeudo, $descuento['descuento']);*/
				
		$estatusP = 0;
		if($pagos_confirmados >= $descuento['total']){
				$estatusP = 1;
				}
		/**************APARTADO Y DESPARTADO DE PRODUCTOS***********************/
		$sql = "SELECT cantidad_requerida, id_tipo_entrega, precio, na_pedidos_detalle.id_producto, id_pedido_detalle, fecha_entrega, producto_compuesto, activoDetPedido 
				FROM na_pedidos_detalle
				LEFT JOIN na_productos ON na_pedidos_detalle.id_producto = na_productos.id_producto
				WHERE na_pedidos_detalle.id_control_pedido = " . $pedido . " ORDER BY id_tipo_entrega DESC";
		$datos = new consultarTabla($sql);
		$result = $datos -> obtenerRegistros(); 
				
		foreach($result as $dato){
				$sqlAparta = "SELECT id_apartado FROM na_movimientos_apartados WHERE id_control_pedido = " . $pedido . " AND id_pedido_detalle = " . $dato -> id_pedido_detalle . " AND id_producto = " . $dato -> id_producto;
				$datosA = new consultarTabla($sqlAparta);
				$contador = $datosA -> cuentaRegistros(); 
						
						
		//for($i=1; $i<=$dato -> cantidad_requerida; $i++){
						
		/****Empieza validacion para productos inmediatos ****/
		if($dato -> id_tipo_entrega == 2){ 
				$precioReal = calculaPorcentaje($dato -> precio, $descuento['descuento']); //Sacamos el porcentaje de precio de los productos individuales
				if($pagos_confirmados >= $precioReal && $dato -> activoDetPedido == 1){  //Verificamos si el total de pagos cubre el precio del producto, si cumple se aparta
						if($estatusP == 0)
								$status_pago_inmediato = 3;
						else
								$status_pago_inmediato = 1;
						if($contador == 0){
								$strInsert = "INSERT INTO na_movimientos_apartados(id_control_pedido, id_pedido_detalle, id_pedido, id_producto, cantidad, id_estatus_apartado, id_estatus_pago, fecha_apartado, fecha_entrega, id_usuario_aparto, es_compueSto) VALUES(" . $pedido . ", " . $dato -> id_pedido_detalle . ", '" . $descuento['id_pedido'] . "', " . $dato -> id_producto . "," . $dato -> cantidad_requerida . ", 1, " . $status_pago_inmediato . ", '" . date("Y-m-d H:i:s") . "', '" . $dato -> fecha_entrega . "', " . $_SESSION["USR"]->userid . ", " . $dato -> producto_compuesto . ")";
								mysql_query($strInsert) or die("Error en consulta:<br> $strInsert <br>" . mysql_error());
								$ultimo_movimiento = mysql_insert_id(); //Obtenemos el id del registro insertado
												
								/**********Si el producto es compuesto procedemos a apartar sus productos basicos en la tabla de movimiento apartado detalle****/
								if($dato -> producto_compuesto == 1){
										//Obtenemos los hijos del producto compuesto
										$sqlCompuesto = "SELECT id_producto_relacionado AS producto_hijo, cantidad AS cantidad FROM na_productos_basicos_detalle WHERE id_producto = " . $dato -> id_producto;
										$datosCompuesto = new consultarTabla($sqlCompuesto);
										$resultCompuesto = $datosCompuesto -> obtenerRegistros();
										//Recorremos cada hijo para insertarlo
										foreach($resultCompuesto as $registrosCom){
												$cantidadCom = $dato -> cantidad_requerida * $registrosCom -> cantidad;
												$strInsertCom = "INSERT INTO na_movimientos_apartados_detalles(id_apartado, id_control_pedido, id_pedido_detalle, id_pedido, id_producto, cantidad, id_estatus_apartado, id_estatus_pago, fecha_apartado, fecha_entrega, id_usuario_aparto, id_producto_padre) VALUES(" . $ultimo_movimiento . ", " . $pedido . ", " . $dato -> id_pedido_detalle . ", '" . $descuento['id_pedido'] . "', " . $registrosCom -> producto_hijo . "," . $cantidadCom . ", 1, " . $status_pago_inmediato . ", '" . date("Y-m-d H:i:s") . "', '" . $dato -> fecha_entrega . "', " . $_SESSION["USR"]->userid . ", " . $dato -> id_producto . ")";
												mysql_query($strInsertCom) or die("Error en consulta:<br> $strInsertCom <br>" . mysql_error());
												}
										}
												
												
								}
						/************************Si el producto ya esta insertado actualizamos sus datos*************/
						else{
								$strUpdate = "UPDATE na_movimientos_apartados SET cantidad = " . $dato -> cantidad_requerida . ", id_estatus_apartado = 1, id_estatus_pago = " . $status_pago_inmediato . ", fecha_entrega = '" . $dato -> fecha_entrega . "', es_compuesto = " . $dato -> producto_compuesto . " WHERE id_control_pedido = " . $pedido . " AND id_pedido_detalle = " . $dato -> id_pedido_detalle . " AND id_producto = " . $dato -> id_producto;
								mysql_query($strUpdate) or die("Error en consulta:<br> $strUpdate <br>" . mysql_error());
												
								//Actualizamos los productos basicos si el producto apartado es un compuesto
								if($dato -> producto_compuesto == 1){
										$id_apartado = $datosA -> obtenerLineaRegistro(); 
										$sqlCompuesto = "SELECT id_producto_relacionado AS producto_hijo, cantidad AS cantidad FROM na_productos_basicos_detalle WHERE id_producto = " . $dato -> id_producto;
										$datosCompuesto = new consultarTabla($sqlCompuesto);
										$resultCompuesto = $datosCompuesto -> obtenerRegistros();
										foreach($resultCompuesto as $registrosCom){
												$cantidadCom = $dato -> cantidad_requerida * $registrosCom -> cantidad;
												$strUpdateCom = "UPDATE na_movimientos_apartados_detalles SET cantidad = " . $cantidadCom . ", id_estatus_apartado = 1, id_estatus_pago = " . $status_pago_inmediato . ", fecha_entrega = '" . $dato -> fecha_entrega . "' WHERE id_producto = " . $registrosCom -> producto_hijo . " AND id_apartado = " . $id_apartado['id_apartado'];
												mysql_query($strUpdateCom) or die("Error en consulta:<br> $strUpdate <br>" . mysql_error());
												}
										}
												
								}
												
								$pagos_confirmados -= $precioReal; //Restamos el precio de los pagos
											
						}
				else{  // Desaparta productos inmediatos
						if($contador > 0 && $dato -> activoDetPedido == 0){
								$strUpdate = "UPDATE na_movimientos_apartados SET id_estatus_apartado = 4, id_estatus_pago = 4, fecha_desapartado = '" . date("Y-m-d H:i:s") . "', fecha_entrega = '" . $dato -> fecha_entrega . "', id_usuario_desaparto = " . $_SESSION["USR"]->userid . " WHERE id_control_pedido = " . $pedido . " AND id_pedido_detalle = " . $dato -> id_pedido_detalle . " AND id_producto = " . $dato -> id_producto;
								mysql_query($strUpdate) or die("Error en consulta:<br> $strUpdate <br>" . mysql_error());
								if($dato -> producto_compuesto == 1){
										$id_apartado = $datosA -> obtenerLineaRegistro(); 
										$sqlCompuesto = "SELECT id_producto_relacionado AS producto_hijo, cantidad AS cantidad FROM na_productos_basicos_detalle WHERE id_producto = " . $dato -> id_producto;
										$datosCompuesto = new consultarTabla($sqlCompuesto);
										$resultCompuesto = $datosCompuesto -> obtenerRegistros();
										foreach($resultCompuesto as $registrosCom){
												$cantidadCom = $dato -> cantidad_requerida * $registrosCom -> cantidad;
												$strUpdateCom = "UPDATE na_movimientos_apartados_detalles SET cantidad = " . $cantidadCom . ", id_estatus_apartado = 4, id_estatus_pago = 4, fecha_entrega = '" . $dato -> fecha_entrega . "' WHERE id_producto = " . $registrosCom -> producto_hijo . " AND id_apartado = " . $id_apartado['id_apartado'];
												mysql_query($strUpdateCom) or die("Error en consulta:<br> $strUpdate <br>" . mysql_error());
												}
										}
												
												
								}
						else{
								$apartado_inmediato1 = "No se inserta ni actualiza ningun registro inmediato";
								}
													
						}
											
				}
		/****Empieza validacion para productos programados ****/
		if($dato -> id_tipo_entrega == 1){ //Programados
				$montoProgramado = $produtos_programados / 2;  //Obtenemos el 50 por ciento del total de los productos programados
				if($pagos_confirmados >= $montoProgramado && $dato -> activoDetPedido == 1){  //Si el monto restante de pagos cubre el monto programado aparta
						if($estatusP == 0)
								$status_pago_programado = 2;
						else
								$status_pago_programado = 1;
										
						if($contador == 0){
								$strInsert = "INSERT INTO na_movimientos_apartados(id_control_pedido, id_pedido_detalle, id_pedido, id_producto, cantidad,  id_estatus_apartado, id_estatus_pago, fecha_apartado, fecha_entrega, id_usuario_aparto, es_compuesto) VALUES(" . $pedido . ", " . $dato -> id_pedido_detalle . ", '" . $descuento['id_pedido'] . "', " . $dato -> id_producto . ", " . $dato -> cantidad_requerida . ", 1, " . $status_pago_programado . ", '" . date("Y-m-d H:i:s") . "', '" . $dato -> fecha_entrega . "', " . $_SESSION["USR"]->userid . ", " . $dato -> producto_compuesto . ")";
								mysql_query($strInsert) or die("Error en consulta:<br> $strInsert <br>" . mysql_error());
												
								$ultimo_movimientoP = mysql_insert_id(); //Obtenemos el id del registro insertado
												
								/**********Si el producto es compuesto procedemos a apartar sus productos basicos en la tabla de movimiento apartado detalle****/
								if($dato -> producto_compuesto == 1){
										$id_apartado = $datosA -> obtenerLineaRegistro(); 
										//Obtenemos los hijos del producto compuesto
										$sqlCompuesto = "SELECT id_producto_relacionado AS producto_hijo, cantidad AS cantidad FROM na_productos_basicos_detalle WHERE id_producto = " . $dato -> id_producto;
										$datosCompuesto = new consultarTabla($sqlCompuesto);
										$resultCompuesto = $datosCompuesto -> obtenerRegistros();
										//Recorremos cada hijo para insertarlo
										foreach($resultCompuesto as $registrosCom){
												$cantidadCom = $dato -> cantidad_requerida * $registrosCom -> cantidad;
												$strInsertCom = "INSERT INTO na_movimientos_apartados_detalles(id_apartado, id_control_pedido, id_pedido_detalle, id_pedido, id_producto, cantidad, id_estatus_apartado, id_estatus_pago, fecha_apartado, fecha_entrega, id_usuario_aparto, id_producto_padre) VALUES(" . $ultimo_movimientoP . ", " . $pedido . ", " . $dato -> id_pedido_detalle . ", '" . $descuento['id_pedido'] . "', " . $registrosCom -> producto_hijo . "," . $cantidadCom . ", 1, " . $status_pago_programado . ", '" . date("Y-m-d H:i:s") . "', '" . $dato -> fecha_entrega . "', " . $_SESSION["USR"]->userid . ", " . $dato -> id_producto . ")";
												mysql_query($strInsertCom) or die("Error en consulta:<br> $strInsertCom <br>" . mysql_error());
												}
										}
								}
						else{
								$strUpdate = "UPDATE na_movimientos_apartados SET id_control_pedido = " . $pedido . ", id_pedido_detalle = " . $dato -> id_pedido_detalle . ", id_pedido = '" . $descuento['id_pedido'] . "', id_producto = " . $dato -> id_producto . ", cantidad = " . $dato -> cantidad_requerida . ", id_estatus_apartado = 1, id_estatus_pago = " . $status_pago_programado . ", fecha_entrega = '" . $dato -> fecha_entrega . "' WHERE id_control_pedido = " . $pedido . " AND id_pedido_detalle = " . $dato -> id_pedido_detalle . " AND id_producto = " . $dato -> id_producto;
								mysql_query($strUpdate) or die("Error en consulta:<br> $strUpdate <br>" . mysql_error());
								if($dato -> producto_compuesto == 1){
										$id_apartado = $datosA -> obtenerLineaRegistro(); 
										$sqlCompuesto = "SELECT id_producto_relacionado AS producto_hijo, cantidad AS cantidad FROM na_productos_basicos_detalle WHERE id_producto = " . $dato -> id_producto;
										$datosCompuesto = new consultarTabla($sqlCompuesto);
										$resultCompuesto = $datosCompuesto -> obtenerRegistros();
										foreach($resultCompuesto as $registrosCom){
												$cantidadCom = $dato -> cantidad_requerida * $registrosCom -> cantidad;
												$strUpdateCom = "UPDATE na_movimientos_apartados_detalles SET cantidad = " . $cantidadCom . ", id_estatus_apartado = 1, id_estatus_pago = " . $status_pago_programado . ", fecha_entrega = '" . $dato -> fecha_entrega . "' WHERE id_producto = " . $registrosCom -> producto_hijo . " AND id_apartado = " . $id_apartado['id_apartado'];
												mysql_query($strUpdateCom) or die("Error en consulta:<br> $strUpdateCom <br>" . mysql_error());
												}
										}
												
								}
											
						}
				else{	//	Desaparta productos programados
						if($contador > 0 && $dato -> activoDetPedido == 0){
								$strUpdate = "UPDATE na_movimientos_apartados SET id_control_pedido = " . $pedido . ", id_pedido_detalle = " . $dato -> id_pedido_detalle . ", id_pedido = '" . $descuento['id_pedido'] . "', id_producto = " . $dato -> id_producto . ", id_estatus_apartado = 4, id_estatus_pago = 4, fecha_desapartado = '" . date("Y-m-d H:i:s") . "', fecha_entrega = '" . $dato -> fecha_entrega . "', id_usuario_desaparto = " . $_SESSION["USR"]->userid . " WHERE id_control_pedido = " . $pedido . " AND id_pedido_detalle = " . $dato -> id_pedido_detalle . " AND id_producto = " . $dato -> id_producto;
								mysql_query($strUpdate) or die("Error en consulta:<br> $strUpdate <br>" . mysql_error());
										
								if($dato -> producto_compuesto == 1){
										$id_apartado = $datosA -> obtenerLineaRegistro(); 
										$sqlCompuesto = "SELECT id_producto_relacionado AS producto_hijo, cantidad AS cantidad FROM na_productos_basicos_detalle WHERE id_producto = " . $dato -> id_producto;
										$datosCompuesto = new consultarTabla($sqlCompuesto);
										$resultCompuesto = $datosCompuesto -> obtenerRegistros();
										foreach($resultCompuesto as $registrosCom){
												$cantidadCom = $dato -> cantidad_requerida * $registrosCom -> cantidad;
												$strUpdateCom = "UPDATE na_movimientos_apartados_detalles SET cantidad = " . $cantidadCom . ", id_estatus_apartado = 4, id_estatus_pago = 4, fecha_entrega = '" . $dato -> fecha_entrega . "' WHERE id_producto = " . $registrosCom -> producto_hijo . " AND id_apartado = " . $id_apartado['id_apartado'];
												mysql_query($strUpdateCom) or die("Error en consulta:<br> $strUpdateCom <br>" . mysql_error());
												}
										}
								}
						else{
								$apartado_programado1 = "No se inserta ni actualiza ningun registro programado";
								}
							}
				}			
					//}

		}
				
}

//Funcion que se encargara de obtener el lote del producto;
function obtenLote($id_prodcuto, $fecha, $orden_compra){
		return 1;
		}		
		
//actualizamos los montos de las cuentas por pagar
	function actualizaEstatusPorPagar($cxp){
		$strSQL="SELECT total-(pagosCP+pagosEgresos) AS pagos,id_estatus_cuentas_por_pagar
					FROM
					(
						SELECT total,id_estatus_cuentas_por_pagar,
						(SELECT IF( SUM(monto) IS null,0,SUM(monto)) FROM ad_cuentas_por_pagar_operadora_detalle_pagos WHERE activoDetCXP = 1 AND id_cuenta_por_pagar=aux.id_cuenta_por_pagar) AS pagosCP,
						(SELECT IF( SUM(monto) IS null,0,SUM(monto)) FROM ad_egresos_detalle WHERE activoDetEgreso=1 AND id_cuenta_por_pagar=aux.id_cuenta_por_pagar) AS pagosEgresos
						FROM ad_cuentas_por_pagar_operadora AS aux where id_cuenta_por_pagar=".$cxp."
						) AS datos";		
			
			//si la cuenta por pagar no esta cancelada, entonces mostramos 
			
			$arrDatos=valBuscador($strSQL);
			$decPagos=$arrDatos[0];
			$estatusCxP=$arrDatos[1];
			
			//si la cuenta por pagar no esta cancelada
			if($estatusCxP!= 3){
					if($decPagos<=0)
							$actualiza = "UPDATE ad_cuentas_por_pagar_operadora SET id_estatus_cuentas_por_pagar = 2 WHERE id_cuenta_por_pagar = " . $cxp;
					else
							$actualiza = "UPDATE ad_cuentas_por_pagar_operadora SET id_estatus_cuentas_por_pagar = 1 WHERE id_cuenta_por_pagar = " . $cxp;
				
					mysql_query($actualiza) or die("Error en consulta:<br> $actualiza <br>" . mysql_error());		
				
					}	
			}
			
function ingresoCajaChicaPedido($pedido, $sucursal){
		$sql = "SELECT id_pedido_detalle_pago, monto, observaciones
				FROM ad_pedidos_detalle_pagos 
				WHERE id_control_pedido = " . $pedido . " AND id_forma_pago = 1 AND (id_ingreso IS NULL OR id_ingreso = 0)";
		$datos = new consultarTabla($sql);
		$contador = $datos -> cuentaRegistros();
		
		$sql2 = "SELECT id_pedido FROM ad_pedidos WHERE id_control_pedido = " .  $pedido;
		$datos2 = new consultarTabla($sql2);
		$sqlP = $datos2 -> obtenerLineaRegistro();
		
		if($contador > 0){
				$result = $datos -> obtenerRegistros();
				foreach($result as $registros){
						$ingresos['id_tipo_ingreso'] = 1;
						$ingresos['id_control_pedido'] = $pedido;						
						$ingresos['fecha_ingreso'] = date('Y-m-d');
						$ingresos['monto'] = $registros -> monto;
						$ingresos['observaciones'] = $registros -> observaciones;
 						$ingresos['id_sucursal'] = $sucursal;
						$ingresos['confirmado'] = 1;
						$ingresos['id_pedido'] = $sqlP['id_pedido'];
						$ingresos['id_pedido_detalle_pago'] = $registros -> id_pedido_detalle_pago;
						accionesMysql($ingresos, 'ad_ingresos_caja_chica', 'Inserta');
						
						$actualiza = "UPDATE ad_pedidos_detalle_pagos SET id_ingreso = " . mysql_insert_id() . " WHERE id_pedido_detalle_pago = " . $registros -> id_pedido_detalle_pago;
						mysql_query($actualiza) or die("Error en consulta:<br> $actualiza <br>" . mysql_error());
						}
				
				}
		}
function recortaTexto($texto){
		$tam_original = strlen($texto);
		$tam_maximo = 80;

		if($tam_original > $tam_maximo){
				$texto1 = substr($texto, 0, $tam_maximo);
				$index = strrpos($texto1, " ");
				$cadena1 = substr($texto1, 0, $index);
				$cadena2 = substr($texto, $index, $tam_original);
				$siguiente = strlen($cadena2);
				if($siguiente > $tam_maximo){
						$original = $cadena2;
						$texto2 = substr($cadena2, 0, $tam_maximo);
						$index2 = strrpos($texto2, " ");
						$cadena2 = substr($texto2, 0, $index2);
						$cadena3 = substr($original, $index2, $tam_original);
						}
				else{
						$cadena3 = "";
						}
				}
		else{
				$cadena1 = $texto;
				$cadena2 = "";
				$cadena3 = "";
				}
		return $cadena1 . "-" . $cadena2 . "-" . $cadena3;
		}

function actualizaCostosLotes($id_costeo){
		
		$sql = "SELECT id_lote,costo_lote FROM na_costeo_productos_detalle  where id_lote<>'0' and id_costeo_productos=".$id_costeo;
		$datos = new consultarTabla($sql);
		
		$result = $datos -> obtenerRegistros();
		foreach($result as $registros){
			
			$actualiza = "UPDATE na_lotes SET costo_unitario = " . $registros -> costo_lote . " WHERE id_lote = " . $registros -> id_lote;
			mysql_query($actualiza) or die("Error en consulta:<br> $actualiza <br>" . mysql_error());
		}
			
			 

} 
function layoutFactura($datosFactura){
	return $cuerpoMail = "
		<p>
			<span style=\"font-family:'DejaVu Sans',Arial,Helvetica,sans-serif;color:#524b48;font-size:14px\">
			Le notificamos que ha recibido un Comprobante Fiscal Digital de 
			<span style=\"font-family:'DejaVu Sans',Arial,Helvetica,sans-serif;color:#18CF9D;font-size:14px;font-weight:bold\"> ".$datosFactura['compania'].".</span>
			, el cual está anexado como documento adjunto para su mayor comodidad.
			<br>
			<br>
			Tenga presente que este mensaje es una notificación automática, no la responda ya que esta cuenta no es monitoreada.
			<br>
			<br>
			Mensaje del emisor:
			<br>
			<br>
			<b>FACTURA CORRECTA</b>
			<br>
			<br>
			</span>			
		</p>
		<p>
			<span style=\"font-family:'DejaVu Sans',Arial,Helvetica,sans-serif;color:#524b48;font-size:9px;font-weight:bold\">AVISO DE CONFIDENCIALIDAD:</span>
			<span style=\"font-family:'DejaVu Sans',Arial,Helvetica,sans-serif;color:#524b48;font-size:12px\"></span>
			<span style=\"font-family:'DejaVu Sans',Arial,Helvetica,sans-serif;color:#524b48;font-size:9px\">Este mensaje es confidencial y/o puede contener información privilegiada. Sí usted no es el destinatario o no es alguna persona autorizada por éste para recibir el mensaje, usted no deberá utilizar, copiar, revelar o tomar ninguna acción basada en este mensaje o cualquier otra información incluida en él. Sí recibe este mensaje por error, por favor notifíquelo de inmediato al remitente y bórrelo de su computadora.</span>
		</p>
		<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">
			<tbody>
			<tr>
			<td style=\"font-family:'DejaVu Sans',Arial,Helvetica,sans-serif;font-size:13px;color:#fff;background-color:#18CF9D;text-align:center;height:22px\" valign=\"middle\"> Folio</td>
			<td style=\"font-family:'DejaVu Sans',Arial,Helvetica,sans-serif;font-size:13px;color:#fff;background-color:#18CF9D;text-align:center;height:22px\" valign=\"middle\"> Fecha</td>
			<td style=\"font-family:'DejaVu Sans',Arial,Helvetica,sans-serif;font-size:13px;color:#fff;background-color:#18CF9D;text-align:center;height:22px\" valign=\"middle\"> Nombre del cliente</td>
			<td style=\"font-family:'DejaVu Sans',Arial,Helvetica,sans-serif;font-size:13px;color:#fff;background-color:#18CF9D;text-align:center;height:22px\" valign=\"middle\"> RFC</td>
			<td style=\"font-family:'DejaVu Sans',Arial,Helvetica,sans-serif;font-size:13px;color:#fff;background-color:#18CF9D;text-align:center;height:22px\" valign=\"middle\"> Importe</td>
			<td style=\"font-family:'DejaVu Sans',Arial,Helvetica,sans-serif;font-size:13px;color:#fff;background-color:#18CF9D;text-align:center;height:22px\" valign=\"middle\"> Archivos</td>
			</tr>
			<tr>
			<td style=\"font-family:'DejaVu Sans',Arial,Helvetica,sans-serif;font-size:11px;color:#666;background-color:#f1efef;text-align:center;height:22px\" valign=\"middle\"> ".$datosFactura['id_factura']."</td>
			<td style=\"font-family:'DejaVu Sans',Arial,Helvetica,sans-serif;font-size:11px;color:#666;background-color:#f1efef;text-align:center;height:22px\" valign=\"middle\"> ".$datosFactura['fecha']."</td>
			<td style=\"font-family:'DejaVu Sans',Arial,Helvetica,sans-serif;font-size:11px;color:#666;background-color:#f1efef;text-align:center;height:22px\" valign=\"middle\"> ".$datosFactura['razon_social']."</td>
			<td style=\"font-family:'DejaVu Sans',Arial,Helvetica,sans-serif;font-size:11px;color:#666;background-color:#f1efef;text-align:center;height:22px\" valign=\"middle\"> ".$datosFactura['rfc']."</td>
			<td style=\"font-family:'DejaVu Sans',Arial,Helvetica,sans-serif;font-size:11px;color:#666;background-color:#f1efef;text-align:center;height:22px\" valign=\"middle\"> ".$datosFactura['total']."</td>
			<td style=\"font-family:'DejaVu Sans',Arial,Helvetica,sans-serif;font-size:11px;color:#666;background-color:#f1efef;text-align:center;height:22px\" valign=\"middle\"> XML y PDF</td>
			</tr>
			</tbody>
		</table>

	";
}		
function layoutFacturaCancelada($datosFactura,$uuid){
	return $cuerpoMail = "
		<p>
			<span style=\"font-family:'DejaVu Sans',Arial,Helvetica,sans-serif;color:#524b48;font-size:14px\">
			Le notificamos que se ha cancelado el Comprobante Fiscal Digital con UUID 
			<span style=\"font-family:'DejaVu Sans',Arial,Helvetica,sans-serif;font-size:14px;font-weight:bold\"> ".$uuid.".</span> de
			<span style=\"font-family:'DejaVu Sans',Arial,Helvetica,sans-serif;color:#18CF9D;font-size:14px;font-weight:bold\"> ".$datosFactura['compania'].".</span>
			, el cual está anexado como documento adjunto para su mayor comodidad.
			<br>
			<br>
			Tenga presente que este mensaje es una notificación automática, no la responda ya que esta cuenta no es monitoreada.
			<br>
			<br>
			Mensaje del emisor:
			<br>
			<br>
			<b>FACTURA CANCELADA</b>
			<br>
			<br>
			</span>			
		</p>
		<p>
			<span style=\"font-family:'DejaVu Sans',Arial,Helvetica,sans-serif;color:#524b48;font-size:9px;font-weight:bold\">AVISO DE CONFIDENCIALIDAD:</span>
			<span style=\"font-family:'DejaVu Sans',Arial,Helvetica,sans-serif;color:#524b48;font-size:12px\"></span>
			<span style=\"font-family:'DejaVu Sans',Arial,Helvetica,sans-serif;color:#524b48;font-size:9px\">Este mensaje es confidencial y/o puede contener información privilegiada. Sí usted no es el destinatario o no es alguna persona autorizada por éste para recibir el mensaje, usted no deberá utilizar, copiar, revelar o tomar ninguna acción basada en este mensaje o cualquier otra información incluida en él. Sí recibe este mensaje por error, por favor notifíquelo de inmediato al remitente y bórrelo de su computadora.</span>
		</p>
		<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">
			<tbody>
			<tr>
			<td style=\"font-family:'DejaVu Sans',Arial,Helvetica,sans-serif;font-size:13px;color:#fff;background-color:#18CF9D;text-align:center;height:22px\" valign=\"middle\"> Folio</td>
			<td style=\"font-family:'DejaVu Sans',Arial,Helvetica,sans-serif;font-size:13px;color:#fff;background-color:#18CF9D;text-align:center;height:22px\" valign=\"middle\"> Fecha</td>
			<td style=\"font-family:'DejaVu Sans',Arial,Helvetica,sans-serif;font-size:13px;color:#fff;background-color:#18CF9D;text-align:center;height:22px\" valign=\"middle\"> Nombre del cliente</td>
			<td style=\"font-family:'DejaVu Sans',Arial,Helvetica,sans-serif;font-size:13px;color:#fff;background-color:#18CF9D;text-align:center;height:22px\" valign=\"middle\"> RFC</td>
			</tr>
			<tr>
			<td style=\"font-family:'DejaVu Sans',Arial,Helvetica,sans-serif;font-size:11px;color:#666;background-color:#f1efef;text-align:center;height:22px\" valign=\"middle\"> ".$datosFactura['id_factura']."</td>
			<td style=\"font-family:'DejaVu Sans',Arial,Helvetica,sans-serif;font-size:11px;color:#666;background-color:#f1efef;text-align:center;height:22px\" valign=\"middle\"> ".$datosFactura['fecha']."</td>
			<td style=\"font-family:'DejaVu Sans',Arial,Helvetica,sans-serif;font-size:11px;color:#666;background-color:#f1efef;text-align:center;height:22px\" valign=\"middle\"> ".$datosFactura['razon_social']."</td>
			<td style=\"font-family:'DejaVu Sans',Arial,Helvetica,sans-serif;font-size:11px;color:#666;background-color:#f1efef;text-align:center;height:22px\" valign=\"middle\"> ".$datosFactura['rfc']."</td>
			</tr>
			</tbody>
		</table>

	";
}
?>