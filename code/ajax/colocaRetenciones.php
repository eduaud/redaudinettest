<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id = $_POST['id'];
$caso = $_POST['caso'];

if($caso == 1)
		$sql = "SELECT porcentaje_retencion_iva AS campo1, porcentaje_retencion_isr AS campo2 FROM na_conceptos_subgastos WHERE id_subgasto = " . $id;
		
else if($caso == 2)
		$sql = "SELECT documento_detalle_iva AS campo1, porcentaje_retencion_iva AS campo2 FROM ad_tipos_documentos WHERE id_tipo_documento = " . $id;
		
else if($caso == 3)
		$sql = "SELECT documento_detalle_isr AS campo1, porcentaje_retencion_isr AS campo2 FROM ad_tipos_documentos WHERE id_tipo_documento = " . $id;
		
$datos = new consultarTabla($sql);
$registros = $datos -> obtenerLineaRegistro();		
echo $registros['campo1'] . "|" . $registros['campo2'];
?>