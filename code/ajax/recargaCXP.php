<?php 

include("../../conect.php");
include("../general/funciones.php");


$strConsulta = "SELECT id_costeo_producto_cuentas_por_pagar, ad_costeo_productos_cuentas_por_pagar.id_costeo_productos, ad_costeo_productos_cuentas_por_pagar.id_cuentas_por_pagar, 
				ad_cuentas_por_pagar_operadora.id_cuenta_por_pagar, 
				ad_tipos_cuentas_por_pagar.id_tipo_cuenta_por_pagar, ad_tipos_cuentas_por_pagar.nombre,
				na_proveedores.id_proveedor, na_proveedores.razon_social, na_tipos_proveedores.id_tipo_proveedor, na_tipos_proveedores.nombre, 
				ad_costeo_productos_cuentas_por_pagar.total, ad_costeo_productos_cuentas_por_pagar.observaciones
FROM ad_costeo_productos_cuentas_por_pagar
LEFT JOIN ad_cuentas_por_pagar_operadora ON ad_costeo_productos_cuentas_por_pagar.id_cuentas_por_pagar = ad_cuentas_por_pagar_operadora.id_cuenta_por_pagar
LEFT JOIN ad_tipos_cuentas_por_pagar ON ad_cuentas_por_pagar_operadora.id_tipo_cuenta_por_pagar = ad_tipos_cuentas_por_pagar.id_tipo_cuenta_por_pagar
LEFT JOIN na_proveedores ON ad_cuentas_por_pagar_operadora.id_proveedor = na_proveedores.id_proveedor
LEFT JOIN na_tipos_proveedores ON na_proveedores.id_tipo_proveedor = na_tipos_proveedores.id_tipo_proveedor";


$resultado=mysql_query($strConsulta) or die("Consulta:\n$strConsulta\n\nDescripcion:\n".mysql_error());
$num=mysql_num_rows($resultado);
echo "exito";
for($i=0;$i<$num;$i++)
{
	$row=mysql_fetch_row($resultado);
	echo "|";
	for($j=0;$j<sizeof($row);$j++)
	{	
		if($j > 0)
			echo "~";
		echo utf8_encode($row[$j]);		
	}
}

?>