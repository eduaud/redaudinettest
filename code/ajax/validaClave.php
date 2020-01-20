<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

extract($_GET);
extract($_POST);
$tabla=base64_decode($tabla);
if(isset($datosExtra)){
	if($datosExtra=='1'){
		if(isset($id)){
			$sql="SELECT clave 
			FROM ad_clientes 
			LEFT JOIN cl_tipos_cliente_proveedor ON ad_clientes.id_tipo_cliente_proveedor=cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor
			WHERE cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor='".$datosExtra."' and clave='".$clave."' AND ".$campo."=".$id;
			$datos = new consultarTabla($sql);
			$result = $datos-> obtenerArregloRegistros();
			$claveDB=$result[0][0];
			if($claveDB==$clave)
				echo "exito";
			else 
				echo "error";
		}
		else{
			if($clave!=""){
			$sql="SELECT clave 
			FROM ad_clientes 
			LEFT JOIN cl_tipos_cliente_proveedor ON ad_clientes.id_tipo_cliente_proveedor=cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor
			WHERE cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor='".$datosExtra."' and clave='".$clave."'";
			$datos = new consultarTabla($sql);
			$result = $datos-> obtenerArregloRegistros();
			if(count($result)>0){
				echo "La clave ya esta registrada.";
			}
			}else{
				echo "Ingrese la clave.";
			}
		}
	}
}
else{
	if(isset($id)){
		$sql="SELECT clave FROM $tabla WHERE $campo=$id";
		$datos = new consultarTabla($sql);
		$result = $datos-> obtenerArregloRegistros();
		$claveDB=$result[0][0];
		if($claveDB==$clave)
			echo "exito";
		else 
			echo "error";
	}
	else{
		if($clave!=""){
			$sql="SELECT clave FROM $tabla WHERE clave='$clave'";
			$datos = new consultarTabla($sql);
			$result = $datos-> obtenerArregloRegistros();
			if(count($result)>0){
				echo "La clave ya esta registrada.";
			}
		}else{
			echo "Ingrese la clave.";
		}
		
	}
}
?>