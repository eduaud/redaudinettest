<?php
	//require_once('../../include/nusoap/nusoap.php');
	$respuesta='';
	/*$usr = "usrSyARoLvxkS";
	$psw = "pswt8EXG2npBu";
	$tkn = 'tokxEX7bJUClJ';
	$cta = 'ctaEomOaxhuIx';*/
        
       	/*$usr = "usruuXeaZDlut"; // Nuevos
	$psw = "psw7rRpeAoSzG";
	$tkn = 'tokuJ95ECLC6n';
	$cta = 'cta7fLrakN9lZ';*/
	
	$str_xml = mb_convert_encoding($str_xml,"ISO-8859-1","UTF-8");
	//echo $str_xml;
	$client = new SoapClient("https://lb04.cfdinova.com.mx/axis2/services/TimbradorIntegradores?wsdl");
	$result = $client -> get(array('user' => $usr,'pass' => $psw,'tk' => $tkn,'cuenta' => $cta,'cad' =>$str_xml));
	//print_r($client);
	$resultado = $result -> return;
	//print_r($resultado);
	//echo $resultado;
	$mystring = $resultado;
	$findme   = 'codError';
	$posCancelacion = strpos($mystring, $findme);

	//echo $posCancelacion.'x';
	if($posCancelacion === false){
		$respuesta = 'exito';
	}else{
		$respuesta = 'error';
	}
	//echo $respuesta;
	//print_r($result);
	//die(print_r($result));
	/*
	$str_xml = mb_convert_encoding($str_xml,"ISO-8859-1","UTF-8");
	//die($str_xml);
	$aParametros = array('cad' =>$str_xml,'tk' => $tkn,'user' => $usr,'pass' => $psw,'cuenta' => $cta);
	
	$wsdl = "https://lb04.cfdinova.com.mx/axis2/services/TimbradorIntegradores?wsdl";

	$client = new nusoap_client($wsdl, true);


	$resultado = $client->call('get', $aParametros);



	if ($client -> fault){ // si
		$respuesta = 'fallo';
	}else{
		$error = $client -> getError();
		if ($error){ // Hubo algun error
			$respuesta = 'error';
			$errores = $client -> faultstring;
		}
		else{
			$respuesta = 'exito';
		}
	}
	//die('</pre>'.print_r($respuesta).'</pre>');
	unset($client);*/
?>