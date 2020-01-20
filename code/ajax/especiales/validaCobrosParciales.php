<?php
	php_track_vars;

    extract($_GET);
	extract($_POST);
	
//CONECCION Y PERMISOS A LA BASE DE DATOS
 	include("../../../conect.php");
	include("../../../code/general/funciones.php");
	
if($opcion == 1){	
	$IdSucursal = $_SESSION["USR"]->sucursalid;
	
	if(is_null($cxc) or $cxc=='')
	{
		echo "nula~".$pos;
		die();
	}
	//	Primero verificamos que la cuenta por cobrar exista o no esté saldada
	//$sql = "SELECT id_cuenta_por_cobrar AS 'idc',id_tipo_ctaxcobrar AS 'tc' FROM `mgw_geg_cuentas_por_cobrar` WHERE id_cuenta_por_cobrar='".$cxc."' and id_tipo_ctaxcobrar='".$tipo."'  AND saldada=0";

    $sql = "SELECT id_control_cxc AS 'idc',saldada AS 'tc' FROM anderp_cuentas_por_cobrar WHERE id_nota_venta='".$cxc."' AND  id_sucursal='".$IdSucursal."'  AND saldada=0";
    //echo $sql;
	
	$ref = mysql_query($sql);
	/*if(mysql_num_rows($ref)==0){
		echo "noexisteosaldada~".$pos;
		die();
	}*/
	if(mysql_num_rows($ref)>0){
		echo "Esta Saldada~".$pos;
		die();
	}
	
	//	Si existe y no está saldada, verificamos que sea del tipo que se está eligiendo
	
	extract(mysql_fetch_assoc($ref));
	/*if($tipo!=$tc){
		echo "nomismotipo~".$pos;
		die();
	}*/
	
//	Si existe y no está saldada y además es del mismo tipo entonces ya averiguamos sus datos, como cliente, saldo pendiente y total
/*	$sql = "SELECT
	        cxc.id_control_cxc AS 'idcxc',
			CONCAT(cxc.id_cliente,' : ',c.Razón_Social) AS 'cliente',
			importe-IF(SUM(monto) IS NULL,0,SUM(monto)) AS 'pendiente',
			importe AS 'total'
			FROM `mgw_geg_cuentas_por_cobrar` cxc
			LEFT JOIN mgw_geg_cuentas_por_cobrar_detalle cxcd ON cxc.id_control_cxc=cxcd.id_control_cxc
			LEFT JOIN mgw_geg_clientes c ON cxc.id_cliente=c.id_cliente
			WHERE id_cuenta_por_cobrar='".$cxc."'
			AND id_tipo_ctaxcobrar=".$tipo."
			GROUP BY cxc.id_control_cxc";
	*/
		$sql = "SELECT
  cxc.id_control_cxc AS 'idcxc',
  c.razon_social AS 'cliente',
  total-IF(SUM(monto) IS NULL,0,SUM(monto)) AS 'pendiente',
  total AS 'total'
  FROM anderp_cuentas_por_cobrar cxc
  LEFT JOIN anderp_cuentas_por_cobrar_detalle cxcd ON cxc.id_control_cxc=cxcd.id_control_cxc AND cxcd.activo=1
  LEFT JOIN anderp_clientes c ON cxc.id_cliente=c.id_cliente
  WHERE id_control_nota_venta='".$cxc."' AND cxc.id_sucursal=".$IdSucursal." and cxcd.activo=1
  GROUP BY cxc.id_control_cxc";		
	
	//echo $sql;
			
	$ref = mysql_query(utf8_decode($sql)) or die("Error en:\n$sql\n\nDescripcion:\n".mysql_error());
	if(mysql_num_rows($ref)==0){
		echo "noexiste~";
		die();
	}
	extract(mysql_fetch_assoc($ref));
	echo utf8_encode("aceptada~".$cliente."~".$pendiente."~".$total."~".$idcxc."~".$pos."~".date("Y-m-d"));
	                 //0 , 1 , 2 , 3 , 4 ,5 , 6
}	
?>