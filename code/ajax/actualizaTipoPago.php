<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id = $_POST['id'];
$total = $_POST['total'];
$totalPedido = $_POST['totalPedido'];

if($total == ""){
		$total = 0;
		}
if($totalPedido == ""){
		$totalPedido = 0;
		}

$sql = "SELECT ad_clientes.id_tipo_pago AS id_pago, ad_tipos_pago_clientes.nombre AS tipo_pago
		FROM ad_clientes
		LEFT JOIN ad_tipos_pago_clientes ON ad_clientes.id_tipo_pago = ad_tipos_pago_clientes.id_tipo_pago
		WHERE id_cliente = $id";

$datos = new consultarTabla($sql);
$result = $datos -> obtenerRegistros();

foreach($result as $consulta){
		if($total < $totalPedido && $consulta -> id_pago == 2)
				echo "<option value='2'>" . "CRÉDITO" . "</option>";
		else if($total < $totalPedido)
				echo "<option value='3'>" . utf8_encode("PAGOS PARCIALES") . "</option>";
		
		else if($total == $totalPedido) 
				echo "<option value='1'>" . utf8_encode("CONTADO") . "</option>";
		}
		
?>