<?php
	php_track_vars;

    extract($_GET);
	extract($_POST);
	
//CONECCION Y PERMISOS A LA BASE DE DATOS
 	include("../../../conect.php");
	include("../../../code/general/funciones.php");
	
	

	
if($opcion==1){

$id_sucursal=$_SESSION["USR"]->sucursalid;

   /*Filtros para Busqueda de productos
   ******/
    if($filtros == 1){

//No se pudo cargar datos desde ../ajax/especiales/cargaPrecios.php?opcion=1&filtros=1&sucursal=1&productos=1&unidad_venta=1,2&tipos_prod=1

        //id_prodcuto
		if(isset($productos)){
		  
           $id_productos = $productos;
		   $condicion .= " AND a.id_sucursal IN (".$sucursal.") AND a.id_producto IN (".$id_productos.")";
          
        }
		
		//id_tipo_producto
		if(isset($tipos_prod) && $productos ==''){
		   $id_tipo_producto = $tipos_prod;
		   $condicion .= " AND  a.id_sucursal IN (".$sucursal.") AND b.id_producto_tipo IN (".$id_tipo_producto.")";
		}
          
		//id_unidad_venta
		if(isset($unidad_venta) && $tipos_prod ==''){
		   $id_unidad_venta = $unidad_venta;
		   $condicion .= " AND  a.id_sucursal IN (".$sucursal.")  AND d.id_unidad_venta IN (".$id_unidad_venta.")";
		}  
	   //sucursal
		if(isset($sucursal) && $tipos_prod ==''){
		   $id_sucursal = $sucursal;
		   $condicion .= " AND  a.id_sucursal IN (".$id_sucursal.")";
		}  


}
	




	$strConsulta="SELECT 
b.id_producto_detalle,
b.id_sucursal,	
f.nombre as 'Sucursal',
a.nombre as 'Producto',
c.nombre as 'Tipo de Producto',
d.nombre as 'Presentacion',
b.precio_unidad_venta,
b.precio_minimo_unidad_venta,
b.precio_partida_unidad_venta,
b.precio_minimo_partida_unidad_venta,
b.unidades_minimas_partida,
b.fecha_y_hora_precio as 'Fecha/Hora'
FROM anderp_productos a
LEFT JOIN anderp_productos_detalle b ON b.id_producto = a.id_producto AND b.id_sucursal=a.id_sucursal
LEFT JOIN anderp_productos_tipos c ON c.id_producto_tipo=b.id_producto_tipo
LEFT JOIN anderp_presentaciones d ON d.id_presentacion=b.id_presentacion
LEFT JOIN sys_sucursales f ON f.id_sucursal = a.id_sucursal
WHERE b.activo=1 ".$condicion;	
	//echo $strConsulta;
	$resultado=mysql_query($strConsulta) or die("Error en:\n$strConsulta\n\nDescripcion:".mysql_error());
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
		echo $row[$j];  //utf8_encode()
	}	
}
	
	if(isset($ini) && isset($fin))
	echo "|$numtotal~$num";

}
else{
echo "exito";
//echo "|";
//echo "~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~";
}


?>