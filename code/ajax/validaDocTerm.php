<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$tipos_pago = $_POST['tipo_pago'];

$sql = "SELECT autorizacion_credito_cobranza, requiere_registro_terminal, requiere_numero_documento, requiere_banco
		FROM na_formas_pago
		WHERE id_forma_pago = $tipos_pago";
		
$datos = new consultarTabla($sql);
$result = $datos -> obtenerLineaRegistro();

echo $result['requiere_registro_terminal'] . "|" . $result['requiere_numero_documento'] . "|" . $result['autorizacion_credito_cobranza'] . "|" . $result['requiere_banco'];

?>