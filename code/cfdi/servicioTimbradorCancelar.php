<?php
	require_once('../../include/nusoap/nusoap.php');
	
	$respuesta = '';
	
	$usr = "usrSyARoLvxkS";
	$psw = "pswt8EXG2npBu";
	$tkn = 'tokxEX7bJUClJ';
	$cta = 'ctaEomOaxhuIx';
	//echo $xml_cancelacion;
	$xml_cancelacion = utf8_encode($xml_cancelacion);
	$aParametros = array('usr' => $usr,'psw' => $psw,'tkn' => $tkn,'cta' => $cta,'xmldsig' =>$xml_cancelacion);
	$wsdl = "https://lb04.cfdinova.com.mx/axis2/services/CancelaComprobante?wsdl";
	$client = new nusoap_client($wsdl, true); 
	$resultado = $client -> call('cancelar', $aParametros);
	$respuesta = $resultado['return'];
	unset($client);
	//die(print_r($resultado));
	
	
	/*$xml_cancelacion = utf8_encode($xml_cancelacion);
	$client = new SoapClient("https://lb04.cfdinova.com.mx/axis2/services/CancelaComprobante?wsdl");
	$result = $client -> cancelar(array('usr' => $usr,'psw' => $psw,'tkn' => $tkn,'cta' => $cta,'xmldsig' =>$xml_cancelacion));
	$respuesta = $result -> return;*/
	
	
	//echo '<pre>';print_r($result);echo '</pre>';
	//print_r($client);
/* require_once('../../include/nusoap/nusoap.php');
  $respuesta='';
  $usr = "usrTP9zdbsuhd";
  $psw = "pswPmn0bSPwGW";
  $tkn = 'tokWSvHrvKSip';
  $cta = 'cta6yr4ZSQymc';

  $aParametros = array('usr' => $usr,'psw' => $psw,'tkn' => $tkn,'cta' => $cta,'xmldsig' =>$xml_cancelacion);
  //$aParametros = array('cad' =>$str_xml,'tk' => $tkn,'user' => $usr,'pass' => $psw,'cuenta' => $cta);
  $wsdl = "http://test01.cfdinova.com.mx/axis2/services/CancelaComprobante?wsdl";
  

  $client = new nusoap_client($wsdl, true);    
  $resultado = $client -> call('cancelar', $aParametros);

	  
	  
  if ($client -> fault){ // si
    $respuesta = 'fallo';
  }
  else{
    $error = $client -> getError();
    if ($error){ // Hubo algun error
	$respuesta = 'error';
	$errores = $client -> faultstring;
    }
    else{
      $respuesta = 'exito';
	}
}

die($error);
unset($client);*/
?>
