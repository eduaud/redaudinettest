<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");
extract($_POST);
extract($_GET);
$cliente = $_POST['cliente'];
$caso = $_POST['caso'];


if($caso == 1){
		$sql = "SELECT id_control_factura, id_factura FROM ".$tabla." WHERE id_cliente=" . $cliente;

		$datos = new consultarTabla($sql);
		$result = $datos -> obtenerRegistros();
		echo "<option value='0'>Seleccione Factura</option>";
		foreach($result as $consulta){
				echo "<option value='" . $consulta -> id_control_factura . "'>" . utf8_encode($consulta -> id_factura) . "</option>";
				}
		}
else if($caso == 2){
		$sql = "SELECT email_envio_facturas FROM ad_clientes WHERE id_cliente = " . $cliente;
		$datos = new consultarTabla($sql);
		$result = $datos -> obtenerLineaRegistro();
		$datosCliente['email'] = $result['email_envio_facturas'];
		
		$sqlFiscal = "SELECT id_cliente_dato_fiscal AS id_fiscal FROM ad_clientes_datos_fiscales WHERE id_cliente = " . $cliente . " ORDER BY id_fiscal DESC";
		$resultFiscal = new consultarTabla($sqlFiscal);
		$registroIDFiscal = $resultFiscal -> obtenerLineaRegistro();
		
		$datosCliente['fiscal'] = $registroIDFiscal['id_fiscal'];
		
		echo json_encode($datosCliente);
		}
else if($caso == 3){
		
		//obtenemos los datos de la factura
		
		$sql = "SELECT id_sucursal, id_cliente,id_forma_pago_sat,cuenta ,id_fiscales_cliente FROM ".$tabla." WHERE id_control_factura = " . $id_control_factura;
		
		$datos = new consultarTabla($sql);
		$result = $datos -> obtenerLineaRegistro();
		$datosCliente['id_sucursal'] = $result['id_sucursal'];
		$datosCliente['id_cliente'] = $result['id_cliente'];
		$datosCliente['id_forma_pago_sat'] = $result['id_forma_pago_sat'];
		$datosCliente['cuenta'] = $result['cuenta'];
		$datosCliente['id_fiscales_cliente'] = $result['id_fiscales_cliente'];
		

		$sql = "SELECT email_envio_facturas FROM ad_clientes WHERE id_cliente = " . $datosCliente['id_cliente'] ;
		$datos = new consultarTabla($sql);
		$result = $datos -> obtenerLineaRegistro();
		$datosCliente['email'] = $result['email_envio_facturas'];

		if($datosCliente['id_fiscales_cliente']<>'0')
		{					
				$sqlFiscal = "SELECT id_cliente_dato_fiscal AS id_fiscal FROM ad_clientes_datos_fiscales WHERE id_cliente = " . $datosCliente['id_cliente']  . " ORDER BY id_fiscal DESC";
				$resultFiscal = new consultarTabla($sqlFiscal);
				$registroIDFiscal = $resultFiscal -> obtenerLineaRegistro();
				
				$datosCliente['fiscal'] = $registroIDFiscal['id_fiscal'];
		}
		else
		{
			$datosCliente['fiscal'] = 0;
		}
		
		echo json_encode($datosCliente);
}
?>