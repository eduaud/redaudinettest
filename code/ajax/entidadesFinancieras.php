<?
include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");
extract($_POST);
$resultado="";
switch($caso){
	case '1':
		$sql="
		SELECT requiere_apellidos,requiere_direccion 
		FROM ad_tipos_entidades_financieras 
		WHERE ad_tipos_entidades_financieras.id_tipo_entidad_financiera=".$id_tipoEntidad;
	$result = new consultarTabla($sql);
	$a_result = $result -> obtenerArregloRegistros();

	
	if($a_result[0]['requiere_apellidos']=='1')
		$resultado="SI";
	else
		$resultado="NO";
	if($a_result[0]['requiere_direccion']=='1')
		$resultado.="|SI";
	else
		$resultado.="|NO";

	$sql2="
		SELECT requiere_nit,requiere_niv 
		FROM ad_tipos_entidades_financieras 
		WHERE ad_tipos_entidades_financieras.id_tipo_entidad_financiera=".$id_tipoEntidad;

	$result_1 = new consultarTabla($sql2);
	$a_result_1 = $result_1 -> obtenerArregloRegistros();

	if($a_result_1[0]['requiere_nit']=='1')
		$resultado.="|SI";
	else
		$resultado.="|NO";
	if($a_result_1[0]['requiere_niv']=='1')
		$resultado.="|SI";
	else
		$resultado.="|NO";
	break;
	case '2':
		$sql="SELECT requiere_tipo_equipo FROM cl_tipos_producto_servicio WHERE id_tipo_producto_servicio=".$id_tipoEntidad;
		$result = new consultarTabla($sql);
		$a_result = $result -> obtenerArregloRegistros();
		if($a_result[0]['requiere_tipo_equipo']=='1')
			$resultado.="SI";
		else 
			$resultado.="NO";
	break;
	case '3':
		$sql="SELECT producto_compuesto FROM cl_productos_servicios WHERE id_producto_servicio=".$llave;
		$result = new consultarTabla($sql);
		$a_result = $result -> obtenerArregloRegistros();
		if($a_result[0]['producto_compuesto']=='1')
			$resultado.="SI";
		else 
			$resultado.="NO";
	break;
	}

	echo $resultado;

?>