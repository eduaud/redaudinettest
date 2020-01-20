<?php
	php_track_vars;

	extract($_GET);
	extract($_POST);

//CONECCION Y PERMISOS A LA BASE DE DATOS
 	include("../../../conect.php");
	include("../../../code/general/funciones.php");


	$strConsulta="SELECT distinct anderp_productos_tipos.id_producto_tipo, anderp_productos_tipos.nombre FROM anderp_productos_tipos left join

anderp_productos_tipos_detalle on anderp_productos_tipos.id_producto_tipo=anderp_productos_tipos_detalle.id_producto_tipo
left join anderp_presentaciones on
anderp_productos_tipos_detalle.id_presentacion=anderp_presentaciones.id_presentacion
WHERE id_unidad_venta in (".$unidadesventa.")";


	$strConsulta="SELECT distinct anderp_productos.id_producto,anderp_productos.nombre
		FROM anderp_productos
		left join anderp_productos_detalle on anderp_productos.id_producto=anderp_productos_detalle.id_producto
		where
		id_producto_tipo in (SELECT distinct anderp_productos_tipos.id_producto_tipo
		FROM anderp_productos_tipos left join
		anderp_productos_tipos_detalle on anderp_productos_tipos.id_producto_tipo=anderp_productos_tipos_detalle.id_producto_tipo
		left join anderp_presentaciones on
		anderp_productos_tipos_detalle.id_presentacion=anderp_presentaciones.id_presentacion
		WHERE id_unidad_venta in (".$unidadesventa.")) and  id_producto_tipo in (".$tiposproducto.")" ;
	
	//echo $strConsulta;
	$res=mysql_query($strConsulta) or die("Error en:\n$strConsulta\n\nDescripcion:".mysql_error());
	$num=mysql_num_rows($res);
	
	echo "exito|".$num;
	for($i=0;$i<$num;$i++)
	{
		$row=mysql_fetch_row($res);
		echo utf8_encode("|".$row[0]."~".$row[1]);
	}
	
?>