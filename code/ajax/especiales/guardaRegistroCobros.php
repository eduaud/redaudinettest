<?PHP
//Inicializamos las variables de sesión, grid, smarty y MySQL
	php_track_vars;

    extract($_GET);
	extract($_POST);
	
//CONECCION Y PERMISOS A LA BASE DE DATOS
 	include("../../../conect.php");
	include("../../../code/general/funciones.php");
	
	$globalPermitido=5;

	$strTrans="AUTOCOMMIT=0";
	mysql_query($strTrans);
	mysql_query("BEGIN");
	
	$arr1 = explode('~',$cadena);
		
	//array_pop($arr1);
		
//	[id_control_cxc,id_tipo_de_cobro,Documento,Fecha,Monto,Observaciones,ID_Usuario]
//		0				1				2		3		4		5			6

	$contador=1;
	//echo "tamaño de Areglo: ".count($arr1);
	for($i=0; $i<count($arr1); $i++){
		$arr2 = explode('|',$arr1[$i]);
		
		array_pop($arr2);
		
		//vemos si la cuenta por cobrar ya queda saldada
		//-----------------------------------------------------------------
		//-----------------------------------------------------------------
		//verificamos si la cuenta por cobrar no excede el monto de la deuda
		$strSQL="SELECT
anderp_cuentas_por_cobrar.total AS importeCxC,
Sum(if(anderp_cuentas_por_cobrar_detalle.monto is null,0,anderp_cuentas_por_cobrar_detalle.monto)) AS pagadoCxP,
anderp_cuentas_por_cobrar_detalle.activo
FROM anderp_cuentas_por_cobrar			
LEFT JOIN anderp_cuentas_por_cobrar_detalle 
ON anderp_cuentas_por_cobrar.id_control_cxc = anderp_cuentas_por_cobrar_detalle.id_control_cxc
WHERE anderp_cuentas_por_cobrar_detalle.activo='1' AND anderp_cuentas_por_cobrar.id_control_cxc =  '".$arr2[0]."'
GROUP BY  anderp_cuentas_por_cobrar.id_control_cxc";
		
		$refcxc = mysql_query($strSQL) or die("Error en consulta:\n$strSQL".mysql_error());
		$num = mysql_num_rows($refcxc);
	
	 if($num > 0){	
		extract(mysql_fetch_assoc($refcxc));
	 }
	 else{
	    $TotalCuenta=0;
		$PagadoCuenta=0;
	 }	
		
		$idCxC_control=$arr2[0];  //id_control_cxc
		
		//variables para crear el pago anticipado
		$TotalCuenta=$importeCxC;
		$PagadoCuenta=$pagadoCxP;
		$PorPagar=$arr2[4];//monto
		$PermitePagoExcedente=0;
		
	
	
				//echo "valores ".$arr2[0].",".$arr2[1].",'".$arr2[2]."',".$arr2[3].",".$arr2[4].",'".$arr2[5]." //";
		
		$fecha_sel = $arr2[3];
		if(eregi("/",$fecha_sel)){
		   $getFecha = explode("/",$fecha_sel);
		   $fecha = $getFecha[2]."-".$getFecha[1]."-".$getFecha[0]; 
		}
		else{
		   $fecha = $fecha_sel;
		}
		//echo $fecha;
	   $sql="INSERT INTO anderp_cuentas_por_cobrar_detalle (id_control_cxc_detalle,id_control_cxc,id_tipo_cobro,documento,fecha,monto,observaciones,fecha_registro,activo) VALUES(NULL,".$arr2[0].",".$arr2[1].",'".utf8_decode($arr2[2])."','".$fecha."',".$arr2[4].",'".utf8_decode($arr2[5])."',NOW(),1)";			
		//echo $sql;
		
		mysql_query($sql) or rollback('anderp_cuentas_por_cobrar_detalle',mysql_error(),mysql_errno(),$sql);
		
		$contador++;

	
	//si la cuenta por cobrar sus montos ya estan saldados colocamos como saldada la cuento por cobrar
	$strSQL="SELECT	anderp_cuentas_por_cobrar.total AS importeCxC2,
Sum(if(anderp_cuentas_por_cobrar_detalle.monto is null,0,anderp_cuentas_por_cobrar_detalle.monto)) AS 	pagadoCxP2,
anderp_cuentas_por_cobrar_detalle.activo
FROM anderp_cuentas_por_cobrar
LEFT JOIN anderp_cuentas_por_cobrar_detalle 
ON anderp_cuentas_por_cobrar.id_control_cxc = anderp_cuentas_por_cobrar_detalle.id_control_cxc
WHERE anderp_cuentas_por_cobrar_detalle.activo='1' AND anderp_cuentas_por_cobrar.id_control_cxc =  '".$idCxC_control."'
GROUP BY anderp_cuentas_por_cobrar.id_control_cxc ";
		
		
		$refcxPagos = mysql_query($strSQL) or rollback('strSQL',mysql_error(),mysql_errno(),$strSQL);
		extract(mysql_fetch_assoc($refcxPagos));
		
		
		//$Diferencia = ($TotalCuenta-($PagadoCuenta-$PorPagar));
		$Diferencia = ($pagadoCxP2 - $importeCxC2);
		
		//echo "Diferencia ".$Diferencia;
		
		if($Diferencia ==0){
		    $sql = "UPDATE anderp_cuentas_por_cobrar SET saldada='1' WHERE id_control_cxc='".$idCxC_control."'";
			
			mysql_query($sql) or rollback('mgw_geg_pagos_anticipados',mysql_error(),mysql_errno(),$sql);
		}
		
				
	
	}//fin de for i
	mysql_query("COMMIT");
	echo "exito";
	die();

	

//$idCxC_control,$Diferencia
function generaPagoAnticipado($idCxC_control,$monto,$tipo_pago,$doc)
{
	//obtenemos la informacion del cliente de la informacion
	$strSQL="SELECT ID_Cliente ,ID_Cuenta_por_cobrar FROM `mgw_geg_cuentas_por_cobrar` where ID_Control_cxc='".$idCxC_control."'";
	if(!($res1 = mysql_query($strSQL)))	die("Error at strSQL $strSQL::".mysql_error());
	extract(mysql_fetch_assoc($res1));
	$ID_Cliente=$ID_Cliente;
	
	//------------
	//------------
	//generamos el pago anticipado 
	//generamos la cuenta contable
	//----------------------------------
	//verificamos al cliente campo1
	//vemos si este cliente ya tiene relacionada una cuenta de pagos parciales											
	$strSQL="SELECT if(ID_Cuenta_contable_anticipos is null or ID_Cuenta_contable_anticipos ='NULL',1, ID_Cuenta_contable_anticipos) as 'Cuenta_Pagos_Cliente' FROM mgw_geg_clientes where ID_Cliente ='".$ID_Cliente."'";
	if(!($res = mysql_query($strSQL)))	die("Error at strSQL $strSQL::".mysql_error());
	
	extract(mysql_fetch_assoc($res));
	
	$CuentaPagosCliente=$Cuenta_Pagos_Cliente;
	
	//si no existe la cuenta conable de pagos
	//generamos la cuenta contable de pagos del cliente debajo de la cuenta contable de pagoa anticipados
	if($CuentaPagosCliente=='1')
	{
		//de este cliente generamos la cuenta contable de pagos anticipados debajo de pagos anticipadoas
		$strSQL1="SELECT Pagos_Anticipados as 'MayorPagos' FROM mgw_geg_parametros_contabilidad ";
		if(!($res1 = mysql_query($strSQL1)))	die("Error at strSQL $strSQL1::".mysql_error());
		extract(mysql_fetch_assoc($res1));
		
		//seleccionamos la razon social del cliente
		$strSQL2="SELECT  Razón_Social as 'razon' FROM mgw_geg_clientes where ID_Cliente='".$ID_Cliente."'";
		if(!($res2 = mysql_query($strSQL2)))	die("Error at strSQL $strSQL1::".mysql_error());
		extract(mysql_fetch_assoc($res2));
		
		$stIDCC= $MayorPagos."-".$ID_Cliente;
		
		
		//antes de realizar el insert vemos si existe la cuenta
		$strSQL="SELECT ID_Cuenta_contable as id_exitente FROM `mgw_geg_cuentas_contables` where ID_Cuenta_contable='".$stIDCC."'";
		if(!($res4 = mysql_query($strSQL)))	die("Error at strSQL $strSQL::".mysql_error());
		extract(mysql_fetch_assoc($res4));
		
		$id_exitente = $id_exitente == null ? '0':$id_exitente;
		
		
		if($id_exitente=='0')
		{
			//---
			$strConsulta = "INSERT INTO mgw_geg_cuentas_contables
							(SELECT '".$stIDCC."','".$razon."',0,`ID_Género_cuenta_contable`,Facturable,Cancelada,'".$MayorPagos."',ID_Cuenta_de_mayor,'2' FROM mgw_geg_cuentas_contables WHERE ID_Cuenta_contable='".$MayorPagos."')";
			
			//echo $strConsulta;
			
			if(!(mysql_query($strConsulta)))	die("Error at a.- strConsulta $strConsulta::".mysql_error());
		}
		$CuentaPagosCliente=$stIDCC;
		
		//le realizamos un update a la cuenta contable de pagos anticipados del cliente con $CuentaPagos y campo 1
		$sqlActualizac = "UPDATE mgw_geg_clientes SET ID_Cuenta_contable_anticipos='".$CuentaPagosCliente."' WHERE ID_Cliente='".$ID_Cliente."'";
		
		//if(!(mysql_query($sqlActualizac)))	die("Error at a.- sqlActualizac $sqlActualizac::".mysql_error());
		mysql_query($sqlActualizac) or rollback('sqlActualizac',mysql_error(),mysql_errno(),$sqlActualizac);
	
	}
	
	//generamos una cuenta contable debajo de esta cuenta de mayor del pago anticipado
	//creamos el pago anticipado del cliente y reasignamos los valores
	$strInsert="INSERT INTO mgw_geg_pagos_anticipados (ID_Pago_anticipado, ID_Cliente, Fecha, Monto, ID_Tipo_de_Cobro, No_Documento, No_modificable, Cancelado, Fecha_cancelacion, Utilizado, ID_Cuenta_contable, ID_Deposito_bancario) VALUES (
	NULL, '".$ID_Cliente."', NOW(), '".$monto."', '".$tipo_pago."', '".$doc." - de cxc ".$ID_Cuenta_por_cobrar."', 1, 0, '0000-00-00', 0, 1, NULL
	)";
		
	mysql_query($strInsert) or rollback('strInsert',mysql_error(),mysql_errno(),$strInsert);
	$strLlave=mysql_insert_id();	
	
	
	grabaBitacora($_SESSION["MGW"]->userid,7, 0, " pago anticipado creado apartir del excedente  '.$strLlave.' en mgw_geg_pagos_anticipados del pago a la cuenta por cobrar control ".$idCxC_control." ");
				         
	$stIDCCPago=$CuentaPagosCliente."-".$strLlave;
	
	$strConsulta = "INSERT INTO mgw_geg_cuentas_contables
						(SELECT '".$stIDCCPago."','Pago Anticipado ".$strLlave."',0,`ID_Género_cuenta_contable`,Facturable,Cancelada,'".$CuentaPagosCliente."',ID_Cuenta_de_mayor,'1' FROM mgw_geg_cuentas_contables WHERE ID_Cuenta_contable='".$CuentaPagosCliente."')";
	
	mysql_query($strConsulta) or rollback('strConsulta',mysql_error(),mysql_errno(),$strConsulta. " 3333 ");	
				
	//realizamos un update al pago anticipado
	$sqlActualizac = "UPDATE mgw_geg_pagos_anticipados SET ID_Cuenta_contable='".$stIDCCPago."' WHERE ID_Pago_anticipado='".$strLlave."'";
	
	mysql_query($sqlActualizac) or rollback('sqlActualizac',mysql_error(),mysql_errno(),$sqlActualizac);
	//-------------------

}

/*function rollback($tabla,$errorSQL,$numError,$consulta){
	require('../inc/header.php');
	global $smarty,$link;
	
	echo $consulta;
	mysql_query("ROLLBACK");
		
	require("../errores.php");
	$smarty->assign('contentheader',"Advertencia de error en el sistema");
	$smarty->assign('StrError',$errorSQL);
	$smarty->assign('NumError',$numError);
	$smarty->assign('DescError',$descError);
	$smarty->assign('Consulta',$consulta);
	$smarty->assign('rutaImagen',ROOTURL."modules/GEG/templates/default/media/");
	$smarty->display('error.tpl');
	exit();
}*/

	
?>