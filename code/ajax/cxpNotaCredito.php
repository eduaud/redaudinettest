<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$idProveedor = $_POST['idProveedor'];

$sql = "SELECT ad_cuentas_por_pagar_operadora.id_cuenta_por_pagar, ad_cuentas_por_pagar_operadora.numero_documento 
		FROM ad_cuentas_por_pagar_operadora WHERE id_proveedor = " . $idProveedor;

$datos = new consultarTabla($sql);
$result = $datos -> obtenerRegistros();

echo "<option value='0'>No Aplica</option>";
foreach($result as $consulta){
		echo "<option value='" . $consulta -> id_cuenta_por_pagar . "'>" . $consulta -> numero_documento . "</option>";
		}


?>