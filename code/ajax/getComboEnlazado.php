<?php
require('../../conect.php');

$tabla = $_REQUEST["tabla"];
$valorElemento = $_REQUEST["valorElemento"];
$posicionCampoTabla = $_REQUEST["posicionCampoTabla"];
$arrayDatos = array();
$cadenaJson = "";
$idElemento = "";

$sqlCampo = "SELECT campo	
			FROM sys_config_encabezados
			WHERE tabla = '" . $tabla . "' AND posicion = " . $posicionCampoTabla;	

$resCampo = mysql_query($sqlCampo) or die("Error en:\n$sqlCampo\n\nDescripci&oacute;n:\n".mysql_error());

if(mysql_num_rows($resCampo) > 0)
{
	$nomCampo = mysql_fetch_row($resCampo);
	
	$sqlQuery = "SELECT clausulaSelect, clausulaFrom, clausulaWhere, clausulaOrderby, id_destino		
				FROM sys_combos_enlazados
				WHERE tabla = '" . $tabla . "'";	
			
	$resQuery = mysql_query($sqlQuery) or die("Error en:\n$sqlQuery\n\nDescripci&oacute;n:\n".mysql_error());
	
	if(mysql_num_rows($resQuery) > 0)
	{
		$datosConsulta = mysql_fetch_row($resQuery);
		
		$sqlDatos = "SELECT " . $datosConsulta[0] . 
					" FROM " . $datosConsulta[1] .
					" WHERE " . $nomCampo[0] . " = " . $valorElemento .
					" ORDER BY " . $datosConsulta[3]; 	
		
		$idElemento	= $datosConsulta[4];				

		mysql_query("SET NAMES 'utf8'") or die("Error en:<br><i>$sqlCliente</i><br><br>Descripcion:<br><b>".mysql_error());
		$cargaDatos = mysql_query($sqlDatos) or die("Error en:\n$sqlQuery\n\nDescripci&oacute;n:\n".mysql_error());
		
		if(mysql_num_rows($cargaDatos) > 0)
		{
			//$i = 0;
			$cadenaJson = '[';
			while ($dato = mysql_fetch_assoc($cargaDatos))
			{
				$cadenaJson .= '{"valor":"' . $dato["valor"] . '","nombre":"' . $dato["nombre"] . '","campo":"' . $datosConsulta[4] . '"},';
				/*$arrayDatos[$i] = $dato;
				$i++;*/
			}	
			$cadenaJson = substr($cadenaJson, 0, strlen($cadenaJson) - 1); 
			$cadenaJson .= ']';
		}
		else
		{
			$cadenaJson .= '[{"valor":"","nombre":"","campo":"' . $datosConsulta[4] . '"}]';
		}
	}
}

echo $cadenaJson;
//echo json_encode($arrayDatos);
?>