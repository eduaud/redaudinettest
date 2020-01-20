<?php 
extract($_GET);
extract($_POST);

include("../../../conect.php");
include("../../../code/general/funciones.php");
include("../../../consultaBase.php");
$update="UPDATE cl_importacion_caja_comisiones SET dc16='".$PrecioPublico."',dc17='".$Instalacion."',dc18='".$Promocion."',dc19='".$ServInstalacion."',dc20='".$Complemento."',dc24='".$DerechoActivacion."',dc25='".$TotalGanar."',dc26='".$Accesorio."',dc27='".$DIST."',dc28='".$Audicel."',dc29='".$Total."',dc30='".$TotalDistribuidor."',dc31='".$TotalDISCOM."',dc32='".$TotalTECEXT."',dc33='".$TotalTECFP."',dc34='".$TotalVendedorEXT."',dc35='".$TotalVendedorFP."',dc21='".$DescuentoCliente."',dc22='".$DescuentoFP."' WHERE id_control=".$id_control;
if(mysql_query($update)){
	echo "exito";
}else{
	echo "error";
}
?>