<?php

include("../../conect.php");


$id = $_POST['id'];
$caso = $_POST['caso'];

if($caso==1)
	$strConsulta="SELECT id_tipo_producto,nombre FROM na_tipos_productos where activo=1 and id_familia_producto ='".$id."' ORDER BY nombre";
else if($caso==2)	
	$strConsulta="SELECT id_caracteristica_producto,nombre FROM na_caracteristicas_productos where activo=1 and id_familia_producto ='".$id."' ORDER BY nombre";
else if($caso==3)	
	$strConsulta="SELECT id_modelo_producto,nombre FROM na_modelos_productos where activo=1 and id_tipo_producto ='".$id."' ORDER BY nombre";
else if($caso==4)	
	$strConsulta="SELECT id_proveedor_contacto,CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno) AS nombre FROM na_proveedores_contacto where id_proveedor ='".$id."' ORDER BY nombre";	
else if($caso==5){
	$ids=array();
	if($id != ''){
		$ids=explode(',',$id);
	}
	$fil="";
	
	for($i=0; $i < count($ids); $i++){
		if($fil == ""){
			$fil .= " IN ('".$ids[$i]."'";
		} else {
			$fil .= ",'".$ids[$i]."'";
		}
	}
	
	if($fil != ""){ $fil.=")"; }
	else{ $fil="IN (1,2,3)"; }
	
	$strConsulta="SELECT ad_clientes.id_cliente, CONCAT_WS(' ',ad_clientes.nombre,ad_clientes.apellido_paterno,ad_clientes.apellido_materno,'(clave : ',ad_clientes.clave,')') FROM ad_clientes
					LEFT JOIN cl_tipos_cliente_proveedor ON ad_clientes.id_tipo_cliente_proveedor = cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor
					WHERE ad_clientes.id_tipo_cliente_proveedor ".$fil." AND ad_clientes.activo = 1
					ORDER BY CONCAT_WS(' ',ad_clientes.nombre,ad_clientes.apellido_paterno,ad_clientes.apellido_materno)";
} else if($caso==6){
	$idPlaza = $_POST['idPlaza'];
	$fil = $idPlaza != 0 && $idPlaza != ''? "and id_sucursal='".$idPlaza."'" : "";
	
	$strConsulta="SELECT id_cliente, CONCAT_WS(' ',nombre,apellido_paterno,apellido_materno) as nombre FROM ad_clientes 
					where activo=1 and id_tipo_cliente_proveedor='".$id."' ".$fil." ORDER BY di";
} else if($caso==7){
	$tipoCliente = $_POST['tipoCliente'];
	$idPlaza = $_POST['idPlaza'];
	if($id == 0 || $id == ''){ $id = -1; }
	
	if($tipoCliente == 4){ /***   Tecnico Interno   ***/
		$fil = $idPlaza != 0 && $idPlaza != ''? "and id_sucursal='".$idPlaza."'" : "";
		
		$strConsulta="SELECT id_entidad_financiera, CONCAT_WS(' ',nombre,apellido_paterno,apellido_materno) as nombre FROM ad_entidades_financieras 
						where activo=1 ".$fil." ORDER BY nombre";
	} else {
		$strConsulta="SELECT id_cliente, nit FROM ad_clientes where activo=1 and id_cliente_padre='".$id."' ORDER BY nit";
	}
} else if($caso==8){
	$tipoCliente = $_POST['tipoCliente'];
	$fil = $tipoCliente != 0 && $tipoCliente != ''? "and id_tipo_cliente_proveedor='".$tipoCliente."'" : "";
	$fil .= $id != 0 && $id != ''? "and id_sucursal='".$id."'" : "";
	
	$strConsulta="SELECT id_cliente, CONCAT_WS(' ',nombre,apellido_paterno,apellido_materno) as nombre FROM ad_clientes 
					where activo=1 ".$fil." ORDER BY di";
}

$result = mysql_query($strConsulta) or die("Error en consulta $sql\nDescripcion:".mysql_error());	
$contador = mysql_num_rows($result);

if($contador <= 0){
		echo "<option value='0'>Selecciona una opci&oacute;n</option>";
		}
else{
		if($caso != 4)
				echo "<option value='0'>-- Selecciona una opci&oacute;n --</option>";
				
		while($row = mysql_fetch_array($result)){
				
				echo "<option value='" . $row[0] . "'>" . utf8_encode($row[1]) . "</option>";
				}
		}


?>