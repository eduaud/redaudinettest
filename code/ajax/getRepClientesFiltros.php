<?php

include("../../conect.php");

$id = $_POST['id'];
$filtro=$_POST['filtro'];;

if($filtro == "categoria")
{
	$strSQL = "SELECT id_cliente, CONCAT(nombre, apellido_paterno, apellido_materno) AS 'nombre' 
				 FROM ad_clientes 
				WHERE id_categoria_cliente = $id 
			 ORDER BY nombre";
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
			echo "<option value='" . $row[0] . "'>" . $row[1] . "</option>";
		}
	}	
}
if(isset($_GET['opcion']))
{
	if($_GET['opcion'] == "llenarClientes")
	{
		$WHERE = "";
		
		if($_POST['slct_sucursal'] >= 1) { $fSucursal  = " id_sucursal_alta		= ".$_POST['slct_sucursal'] ; }
		if($_POST['slct_categoria']>= 1) { $fCategoria = " AND id_categoria_cliente	= ".$_POST['slct_categoria']; }
		
		
		
		$WHERE .= $fSucursal.$fCategoria;
		
		$strSQL = "SELECT id_cliente, CONCAT(nombre, apellido_paterno, apellido_materno) AS 'nombre' 
					 FROM ad_clientes 
					WHERE $WHERE
				 ORDER BY nombre";
		$rs_cliente = mysql_query($strSQL) or die("Error en consulta $strSQL\nDescripcion:".mysql_error());	
		$contador = mysql_num_rows($rs_cliente);
		echo $strSQL;
		if($contador >= 1)
		{
			echo "<option value='0'>Todos</option>";
			while($row = mysql_fetch_array($rs_cliente)){
						
				echo "<option value='" .utf8_encode($row[0]). "'>" . utf8_encode($row[1]) . "</option>";
			}
			
		}
	}
	
}


?>