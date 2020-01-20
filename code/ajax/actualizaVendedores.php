<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");


$id = $_POST['id'];

$sql = "SELECT DISTINCT sys_usuarios.id_usuario AS usuario, na_vendedores.id_vendedor AS id_vendedor, CONCAT(na_vendedores.nombre, ' ', na_vendedores.apellido_paterno, ' ', na_vendedores.apellido_materno) AS vendedor
		FROM sys_usuarios
		LEFT JOIN na_vendedores ON sys_usuarios.id_vendor_relacionado = na_vendedores.id_vendedor
		WHERE na_vendedores.activo=1 and sys_usuarios.id_sucursal = $id";

$datos = new consultarTabla($sql);
$result = $datos -> obtenerRegistros();
$selected = "";
foreach($result as $consulta){
		if($_SESSION["USR"]->userid == $consulta -> usuario)
				$selected .= "selected = 'selected'";
		else
				$selected = "";
		echo "<option value='" . $consulta -> id_vendedor . "'". $selected . ">" . utf8_encode($consulta -> vendedor) . "</option>";
		}
		

?>