<?php
php_track_vars;
include("../../../conect.php");
extract($_GET);
extract($_POST);
$idCliente = $_REQUEST["idCliente"];
$sqlCliente = "SELECT id_contacto_cliente, id_cliente_franquicia, nombre, apellido_paterno, apellido_materno,
				cargo, telefono_directo, extension, celular, nextel, Nextel_id, email, contacto_para_contrato_entre_franquicias
			   FROM of_contactos_cliente 
			   WHERE id_cliente_franquicia = $idCliente
			   ORDER BY nombre";
$resCliente = mysql_query($sqlCliente) or die("Error en:<br><i>$sqlCliente</i><br><br>Descripcion:<br><b>".mysql_error());
$numReg = mysql_num_rows($resCliente);
echo "exito";
for($i=0;$i<$numReg;$i++)
{
	$row=mysql_fetch_row($resCliente);
	echo "|";
	for($j=0;$j<sizeof($row);$j++)
	{	
		if($j > 0)
			echo "~";
		echo utf8_encode($row[$j]);
	}	
}
?>