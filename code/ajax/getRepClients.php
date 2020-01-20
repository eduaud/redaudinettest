<?php
	extract($_REQUEST);
	include("../../conect.php");

	$strWhere = ($intIdClient == 0)? '1': 'id_tipo_cliente = \'' . $intIdClient . '\'';
	$strWhere .= ($intIdClientCat == 0)? ' AND 1': ' AND id_categoria_cliente = \'' . $intIdClientCat . '\'';

	$strSQL = "
		SELECT id_cliente, nombre
		FROM rac_clientes
		WHERE activo = 1 AND $strWhere
		ORDER BY nombre
	";
	$rs = mysql_query($strSQL) or die("Error en consulta $strSQL\nDescripcion:".mysql_error());		  	
	$arrClients = array();
	while($row = mysql_fetch_assoc($rs)){
		$arrClients[] = $row;
	}

	echo json_encode($arrClients);
?>