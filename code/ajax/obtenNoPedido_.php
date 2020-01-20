<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");
extract($_GET);
extract($_POST);
if($tabla=='ad_pedidos'||$tabla=='ad_solicitudes_material'){
	$sqlC="
		SELECT clave 
		FROM ad_sucursales 
		LEFT JOIN ad_sucursales_almacenes_detalle
			ON ad_sucursales.id_sucursal = ad_sucursales_almacenes_detalle.id_sucursal
		LEFT JOIN ad_almacenes
			ON ad_sucursales_almacenes_detalle.id_almacen = ad_almacenes.id_almacen 
		WHERE ad_almacenes.id_almacen = ".$valor;
	$datos_ = new consultarTabla($sqlC);
	$result_ = $datos_ -> obtenerLineaRegistro();
	if($tabla=='ad_pedidos'){
		$sql2 = "
			SELECT id_pedido, MAX(consecutivo) AS siguiente,clave 
			FROM ad_pedidos 
			LEFT JOIN ad_almacenes
				ON ad_almacenes.id_almacen = ad_pedidos.id_almacen_solicita
			LEFT JOIN ad_sucursales_almacenes_detalle
				ON ad_sucursales_almacenes_detalle.id_almacen = ad_almacenes.id_almacen
			LEFT JOIN ad_sucursales 
				ON ad_sucursales.id_sucursal = ad_sucursales_almacenes_detalle.id_sucursal 
			WHERE ad_almacenes.id_almacen = ".$valor;
		$datos2 = new consultarTabla($sql2);
		$result2 = $datos2 -> obtenerLineaRegistro();
		$contador = $datos2 -> cuentaRegistros();
		$consecutivo[0]['prefijo']= $result_['clave']."-P";
		if($contador == 0){
			$consecutivo[0]['pedido']= $result_['clave']."-P1";
			$consecutivo[0]['consecutivo']= "1";
			
		}
		else{
			$consecutivo[0]['pedido'] =$result_['clave']."-P".($result2['siguiente'] + 1);
			$consecutivo[0]['consecutivo']= $result2['siguiente'] + 1;
		}
	}
	else{
		$sql2 = "SELECT id_solicitud_material, MAX(consecutivo) AS siguiente FROM ad_solicitudes_material";

		$datos2 = new consultarTabla($sql2);
		$result2 = $datos2 -> obtenerLineaRegistro();
		$contador = $datos2 -> cuentaRegistros();

		$consecutivo[0]['prefijo']= $result_['clave']."-SM";
		if($contador == 0){
			$consecutivo[0]['pedido']= $result_['clave']."-SM1";
			$consecutivo[0]['consecutivo']= "1";
			
		} else{
				$consecutivo[0]['pedido'] =$result_['clave']."-SM".($result2['siguiente'] + 1);
				$consecutivo[0]['consecutivo']= $result2['siguiente'] + 1;
		}
	}
}
else if($tabla=='ad_facturas_audicel'){
	$sql="SELECT clave FROM ad_sucursales WHERE id_sucursal=".$valor;
	$datos_ = new consultarTabla($sql);
	$result_ = $datos_ -> obtenerLineaRegistro();
	
	$sql_2="
	SELECT id_factura,MAX(consecutivo) AS siguiente
	FROM ".$tabla." 
	LEFT JOIN ad_sucursales 
	ON ".$tabla.".id_sucursal=ad_sucursales.id_sucursal WHERE ad_sucursales.id_sucursal=".$valor;
	$datos2 = new consultarTabla($sql_2);
	$result2 = $datos2 -> obtenerLineaRegistro();
	$contador = $datos2 -> cuentaRegistros();

	$consecutivo[0]['prefijo']= $result_['clave'];
	if($contador == 0){
		$consecutivo[0]['pedido']= $result_['clave']."1";
		$consecutivo[0]['consecutivo']= "1";
		
	} else{
			$consecutivo[0]['pedido'] =$result_['clave'].($result2['siguiente'] + 1);
			$consecutivo[0]['consecutivo']= $result2['siguiente'] + 1;
	}
}
else if($tabla=='ad_notas_credito'){
	$sql="SELECT folio_electronico FROM ad_sucursales WHERE id_sucursal=".$valor;
	$datos_ = new consultarTabla($sql);
	$result_ = $datos_ -> obtenerLineaRegistro();
	
	$sql_2="
	SELECT id_factura,MAX(consecutivo) AS siguiente
	FROM ad_notas_credito 
	LEFT JOIN ad_sucursales 
	ON ad_notas_credito.id_sucursal=ad_sucursales.id_sucursal WHERE ad_sucursales.id_sucursal=".$valor;
	$datos2 = new consultarTabla($sql_2);
	$result2 = $datos2 -> obtenerLineaRegistro();
	$contador = $datos2 -> cuentaRegistros();

	$consecutivo[0]['prefijo']= $result_['folio_electronico'];
	if($contador == 0){
		$consecutivo[0]['pedido']= $result_['folio_electronico']."1";
		$consecutivo[0]['consecutivo']= "1";
		
	} else{
			$consecutivo[0]['pedido'] =$result_['folio_electronico'].($result2['siguiente'] + 1);
			$consecutivo[0]['consecutivo']= $result2['siguiente'] + 1;
	}
}
else if($tabla=='ad_facturas'){
	$sql="SELECT CONCAT(serie,folio) AS prefijo FROM ad_clientes WHERE id_cliente=2";//.$_SESSION["USR"]->userid;
	$datos_ = new consultarTabla($sql);
	$result_ = $datos_ -> obtenerLineaRegistro();
	
	$sql_2="
	SELECT id_factura, MAX(consecutivo) AS siguiente
	FROM ad_facturas
	LEFT JOIN(SELECT MAX( id_cliente_dato_fiscal ) AS id_compania
	FROM ad_clientes_datos_fiscales
	LEFT JOIN ad_clientes ON ad_clientes_datos_fiscales.id_cliente = ad_clientes.id_cliente
	WHERE ad_clientes.id_cliente =".$_SESSION["USR"]->userid."
	) AS A ON ad_facturas.id_compania = A.id_compania ";
	$datos2 = new consultarTabla($sql_2);
	$result2 = $datos2 -> obtenerLineaRegistro();
	$contador = $datos2 -> cuentaRegistros();

	$consecutivo[0]['prefijo']= $result_['prefijo'];
	if($result2['id_factura'] == ''){
		$consecutivo[0]['pedido']= $result_['prefijo']."1";
		$consecutivo[0]['consecutivo']= "1";
		
	} else{
			$consecutivo[0]['pedido'] =$result_['prefijo'].($result2['siguiente'] + 1);
			$consecutivo[0]['consecutivo']= $result2['siguiente'] + 1;
	}
}

echo json_encode($consecutivo);
?>