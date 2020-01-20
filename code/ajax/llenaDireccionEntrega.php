<?php

include("../../conect.php");


$id = $_POST['id'];

$strConsulta="SELECT id_cliente_direccion_entrega AS id, CONCAT('Calle: ',ad_clientes_direcciones_entrega.calle,' ', 'Num. Ext: ',if(ad_clientes_direcciones_entrega.numero_exterior is null,'',ad_clientes_direcciones_entrega.numero_exterior),' ',if(ad_clientes_direcciones_entrega.numero_interior is null,'',ad_clientes_direcciones_entrega.numero_interior),' ','Col. ',if(ad_clientes_direcciones_entrega.colonia is null,'',ad_clientes_direcciones_entrega.colonia),' ','Del. o Mun. ',if(ad_clientes_direcciones_entrega.delegacion_municipio is null,'',ad_clientes_direcciones_entrega.delegacion_municipio)) AS direccion FROM ad_clientes_direcciones_entrega LEFT JOIN na_ciudades on  ad_clientes_direcciones_entrega.id_ciudad=na_ciudades.id_ciudad LEFT JOIN na_estados on ad_clientes_direcciones_entrega.id_estado=na_estados.id_estado LEFT JOIN ad_clientes ON ad_clientes_direcciones_entrega.id_cliente=ad_clientes.id_cliente WHERE ad_clientes_direcciones_entrega.id_cliente = $id";


$result = mysql_query($strConsulta) or die("Error en consulta $sql\nDescripcion:".mysql_error());	
$contador = mysql_num_rows($result);

if($contador <= 0){
		echo "<option value='0'>Selecciona una opci&oacute;n</option>";
		}
else{
		echo "<option value='0'>Selecciona una opci&oacute;n</option>";
		while($row = mysql_fetch_array($result)){
				
				echo "<option value='" . $row[0] . "'>" . utf8_encode($row[1]) . "</option>";
				}
		}

?>