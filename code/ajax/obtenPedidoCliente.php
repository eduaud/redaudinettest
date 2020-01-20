<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");


$cliente = $_POST['cliente'];
$caso = $_POST['caso'];


if($caso == 1){
		$sql = "SELECT id_control_pedido, id_pedido FROM ad_pedidos WHERE id_cliente = " . $cliente;
		$datos = new consultarTabla($sql);
		$result = $datos -> obtenerRegistros();

		echo "<option value='0'>Seleccione Pedido</option>";
		foreach($result as $consulta){
				echo "<option value='" . $consulta -> id_control_pedido . "'>" . utf8_encode($consulta -> id_pedido) . "</option>";
				}
		}
else if($caso == 2){
		if($_POST['tabla']=="ad_facturas_audicel"){
			$sql = "SELECT email_fiscal FROM ad_clientes WHERE id_cliente = " . $cliente;
			$datos = new consultarTabla($sql);
			$result = $datos -> obtenerLineaRegistro();
			$datosCliente['email'] = $result['email_fiscal'];
		
			$sqlFiscal = "SELECT id_cliente_dato_fiscal AS id_fiscal FROM ad_clientes_datos_fiscales WHERE id_cliente = " . $cliente . " 
					ORDER BY id_fiscal DESC";
			$resultFiscal = new consultarTabla($sqlFiscal);
			$registroIDFiscal = $resultFiscal -> obtenerLineaRegistro();
		
			$datosCliente['fiscal'] = $registroIDFiscal['id_fiscal'];
		}else if($_POST['tabla']=="ad_facturas"){
			$sql = "SELECT id_compania,email FROM sys_companias WHERE activo = 1";
			$datos = new consultarTabla($sql);
			$result = $datos -> obtenerLineaRegistro();
			$datosCliente['email'] = $result['email'];		
			$datosCliente['fiscal'] = $registroIDFiscal['id_compania'];
		}
			echo json_encode($datosCliente);
		}

else if($caso == 3){
	$sql = "SELECT email FROM sys_companias WHERE activo=1";
	$datos = new consultarTabla($sql);
	$result = $datos -> obtenerLineaRegistro();
	$datosCliente['email'] = $result['email'];

	$sqlFiscal = "SELECT id_compania AS id_fiscal FROM sys_companias WHERE activo=1";
	$resultFiscal = new consultarTabla($sqlFiscal);
	$registroIDFiscal = $resultFiscal -> obtenerLineaRegistro();

	$datosCliente['fiscal'] = $registroIDFiscal['id_fiscal'];

	echo json_encode($datosCliente);
}
else if($caso == 4){
	$tabla = $_POST['tabla'];
	if($tabla=="ad_facturas_audicel"){
		$sql = "SELECT id_forma_pago_sat,scfdi_formas_de_pago_sat.descripcion FROM ad_clientes
		LEFT JOIN scfdi_formas_de_pago_sat ON ad_clientes.id_metodo_pago=scfdi_formas_de_pago_sat.id_forma_pago_sat WHERE id_cliente=".$cliente;
	}else if($tabla=="ad_facturas"){
		$sql = "SELECT id_forma_pago_sat,scfdi_formas_de_pago_sat.descripcion FROM sys_companias
		LEFT JOIN scfdi_formas_de_pago_sat ON sys_companias.id_forma_pago=scfdi_formas_de_pago_sat.id_forma_pago_sat WHERE sys_companias.activo=1";
	}
	$datos = new consultarTabla($sql);
	$result = $datos -> obtenerRegistros();
	echo "<option value='0'>Seleccione Forma de Pago</option>";
	foreach($result as $consulta){
		echo "<option value='" . $consulta -> id_forma_pago_sat . "'>" . utf8_encode($consulta -> descripcion) . "</option>";
	}
}



?>