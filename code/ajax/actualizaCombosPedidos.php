<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");


$caso = $_POST['caso'];


if($caso ==1)
		$sql = "SELECT id_cliente AS id_registro,CONCAT(nombre,' ', if(apellido_paterno is null,'',apellido_paterno),' ',if(apellido_materno is null,'',apellido_materno)) AS dato_muestra FROM ad_clientes WHERE activo=1";
else if ($caso ==2)
		
		$cliente = $_POST['cliente'];
		
		$sql = "SELECT id_cliente_direccion_entrega AS id_registro, CONCAT('Calle: ',ad_clientes_direcciones_entrega.calle,' ', 'Num. Ext: ',if(ad_clientes_direcciones_entrega.numero_exterior is null,'',ad_clientes_direcciones_entrega.numero_exterior),' ',if(ad_clientes_direcciones_entrega.numero_interior is null,'',ad_clientes_direcciones_entrega.numero_interior),' ','Col. ',if(ad_clientes_direcciones_entrega.colonia is null,'',ad_clientes_direcciones_entrega.colonia),' ','Del. o Mun. ',if(ad_clientes_direcciones_entrega.delegacion_municipio is null,'',ad_clientes_direcciones_entrega.delegacion_municipio)) AS dato_muestra FROM ad_clientes_direcciones_entrega LEFT JOIN na_ciudades on  ad_clientes_direcciones_entrega.id_ciudad=na_ciudades.id_ciudad LEFT JOIN na_estados on ad_clientes_direcciones_entrega.id_estado=na_estados.id_estado LEFT JOIN ad_clientes ON ad_clientes_direcciones_entrega.id_cliente=ad_clientes.id_cliente WHERE ad_clientes_direcciones_entrega.id_cliente = $cliente";
		
		
		//, ' ','Ciudad: ', na_ciudades.nombre,' ', 'Estado: ', na_estados.nombre

$datos = new consultarTabla($sql);
$result = $datos -> obtenerRegistros();

foreach($result as $consulta){
		echo "<option value='" . $consulta -> id_registro . "'>" . utf8_encode($consulta -> dato_muestra) . "</option>";
		}
		

?>