<?php
	php_track_vars;
	include("../../conect.php");
	include("funciones_facturacion.php");
		
	extract($_POST);
	extract($_GET);
	
	//obtenemos los valores de var var_obj_prefijo=document.getElementById(strnombrePrefijo);	
	//var var_obj_consecutivo=document.getElementById(strnombreConsecutivo);	
	//var var_obj_documento=document.getElementById(strnombreDocumento);
	
	//realizamos la llamanada a las funciones de  facturacion
	//opcion 1 obtienes valores de las facturas : folio consecutivo serie
	
	//var aux = ajaxR("../cfdi/ajax_facturacion.php?opcion="+opcion+"&tipoDoc="+tipoDoc+"&id="+var_id_serie.value);
	
	
	if($opcion==1)
	{
		$arrDatosFac=array();
		$arrDatosFac=asignaIdentificadoresASerie($opcion,$tipoDoc,$id,0);
		
		//enviamoslos valores como 
		echo $arrDatosFac[0].'|'. $arrDatosFac[1].'|'. $arrDatosFac[2];
	
	}
	
	
	
	
?> 