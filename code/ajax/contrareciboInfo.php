<?php
php_track_vars;

extract($_GET);
extract($_POST);

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$sqlCliente = "
SELECT id_cliente,id_entidad_financiera 
FROM cl_contrarecibos
WHERE id_contrarecibo = ".$llave;

$datos = new consultarTabla($sqlCliente);
$CloEf = $datos-> obtenerArregloRegistros();
if($CloEf[0][0] != '0')
	echo "cliente|";
else
	echo "entidad|";

$SqlInfo = "
	SELECT clave,di,cl_tipos_cliente_proveedor.nombre
	FROM cl_contrarecibos
	LEFT JOIN ad_clientes ON cl_contrarecibos.id_cliente=ad_clientes.id_cliente
	LEFT JOIN cl_tipos_cliente_proveedor ON ad_clientes.id_tipo_cliente_proveedor = cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor
	WHERE (cl_contrarecibos.id_entidad_financiera = 0 OR cl_contrarecibos.id_entidad_financiera IS NULL) AND id_contrarecibo = ".$llave."

	UNION ALL 

	SELECT ad_entidades_financieras.clave,di,cl_tipos_cliente_proveedor.nombre
	FROM cl_contrarecibos
	LEFT JOIN ad_entidades_financieras ON cl_contrarecibos.id_entidad_financiera=ad_entidades_financieras.id_entidad_financiera
	LEFT JOIN ad_sucursales ON ad_entidades_financieras.id_sucursal = ad_sucursales.id_sucursal
	LEFT JOIN ad_tipos_entidades_financieras ON ad_entidades_financieras.id_tipo_entidad_financiera = ad_tipos_entidades_financieras.id_tipo_entidad_financiera
	LEFT JOIN cl_tipos_cliente_proveedor ON ad_tipos_entidades_financieras.id_tipo_cliente_proveedor = cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor
	WHERE (cl_contrarecibos.id_cliente = 0 OR cl_contrarecibos.id_cliente IS NULL) AND id_contrarecibo = ".$llave;
$datosContrarecibos = mysql_query($SqlInfo);
while($datos = mysql_fetch_array($datosContrarecibos)){
	echo $datos[0].'|'.$datos[1].'|'.$datos[2];
}
?>