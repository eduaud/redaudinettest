<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

include("../../code/cfdi/funciones_facturacion.php");
include("../../code/cfdi/facturacion_sellado_timbrado.php");


extract($_POST);
extract($_GET);

$errorFacturacion=cfdiSellaTimbraFactura($tipo_documento,$id_documento,1,$tabla);

echo $errorFacturacion;

if($errorFacturacion!='0' || $errorFacturacion!=' 0'  || $errorFacturacion!='  0' )
{
	//enviamos la factura por email
	
}


?>