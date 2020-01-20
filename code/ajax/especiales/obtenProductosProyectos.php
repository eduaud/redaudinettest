<?php
	php_track_vars;

	extract($_GET);
	extract($_POST);

//CONECCION Y PERMISOS A LA BASE DE DATOS
 	include("../../../conect.php");
	include("../../../code/general/funciones.php");

    if($proyecto==0)
	{
		echo "exito";
		echo utf8_encode("|0~-Seleccione un Producto-");
		die();
	}
	else
	{
		$strConsulta="SELECT DISTINCT id_producto, of_productos_servicios.nombre FROM of_detalle_pedido
						left join of_pedidos on of_detalle_pedido.id_pedido = of_pedidos.id_pedido
						left join of_proyectos on of_pedidos.id_proyecto = of_proyectos.id_proyecto
						left join of_productos_servicios on id_producto = id_pr_serv
						where disponible_para_orden_de_produccion=1
						and id_control_pedido_detalle not in(SELECT id_control_pedido_detalle FROM of_detalle_productos_pedidos)
						and id_estatus_de_proyecto=1 and of_pedidos.id_proyecto='".$proyecto."'";
	}

	
	
	//echo $strConsulta;
	$res=mysql_query($strConsulta) or die("Error en:\n$strConsulta\n\nDescripcion:".mysql_error());
	$num=mysql_num_rows($res);
	
	echo "exito";
	echo utf8_encode("|0~-Seleccione un Producto-");
	for($i=0;$i<$num;$i++)
	{
		$row=mysql_fetch_row($res);
		
		echo utf8_encode("|".$row[0]."~".$row[1]);
	}
	
?>