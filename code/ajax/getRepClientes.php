<?php

include("../../conect.php");

$id = $_POST['id'];
$filtro=$_POST['filtro'];;

if($filtro == "sucursal")
{
	$strSQL = "SELECT id_cliente, na_categorias_cliente.id_categoria_cliente, na_categorias_cliente.nombre as categoria, 
				CONCAT(ad_clientes.nombre, ' ',  apellido_paterno,' ', apellido_materno) AS 'nc' 
				FROM ad_clientes
				LEFT JOIN na_categorias_cliente on ad_clientes.id_categoria_cliente = na_categorias_cliente.id_categoria_cliente 				
				WHERE id_sucursal_alta = $id 
				group by na_categorias_cliente.id_categoria_cliente
				order by categoria	";
				
	$rs_cliente = mysql_query($strSQL) or die("Error en consulta $strSQL\nDescripcion:".mysql_error());	
	$contador = mysql_num_rows($rs_cliente);
	
	if($contador <= 0)
	{
		echo "<option value='0'>Todas</option>";
	}
	else
	{
		echo "<option value='0'>Todas</option>";
		while($row = mysql_fetch_array($rs_cliente)){					
			echo "<option value='" . $row[1] . "'>" .utf8_encode($row[2]). "</option>";
		}
	}	
}



?>