<?php	
include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$arreglo = array();
$arreglo_errores = array();
$arreglo_compuesto_errores = array();

$idProveedor = $_POST['idProveedor'];
$sql = "select id_proveedor, permite_mezclar_tipo_producto_en_ODC from ad_proveedores WHERE id_proveedor = '".$idProveedor."';";
$datos = new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();
for($i=0;$i<count($result);$i++)
	$permite_mezclar_tipo_producto_en_ODC = $result[$i]['permite_mezclar_tipo_producto_en_ODC'];

//echo json_encode($permite_mezclar_tipo_producto_en_ODC);
echo $permite_mezclar_tipo_producto_en_ODC;

?>

