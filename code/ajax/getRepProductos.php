<?php
include("../../conect.php");
include("../../consultaBase.php");

// Variables - Posibles Filtros
			$titulo  = $_POST['titulo'];
	    $slctFamilia = $_POST['slct_familia'];
		  $slctTipo  = $_POST['slct_tipo'];
		$slctModelo  = $_POST['slct_modelo'];
$slctCaracteristica  = $_POST['slct_caracteristica'];

// Cadena Filtro
 		$fFamilia = "";
		   $fTipo = "";
 		 $fModelo = "";
 $fCaracteristica = "";
//		   $WHERE = "";

$sql = "SELECT id_producto, nombre FROM na_productos WHERE activo=1 ";

if($titulo == "Productos" || $titulo == "Existencia de productos")
{
	if (		 $slctFamilia == 0 and 
		   			$slctTipo == 0 and 
		 		  $slctModelo == 0 and 
		  $slctCaracteristica == 0		)
	{	
		echo $sql;
		die();
	}
	else
	{
		if($slctFamilia >= 1)		{ $fFamilia			= " AND id_familia_producto = $slctFamilia";			 	}
		if($slctTipo >= 1)			{ $fTipo			= " AND id_tipo_producto	= $slctTipo";					}
		if($slctModelo >= 1)		{ $fModelo			= " AND id_modelo_producto	= $slctModelo";					}
		if($slctCaracteristica >= 1){ $fCaracteristica  = " AND id_caracteristica_producto = $slctCaracteristica";	}
		
		$WHERE = $fFamilia.$fTipo.$fModelo.$fCaracteristica;
		
	//	$sql = "SELECT id_categoria_articulo, nombre FROM rac_articulos_categorias WHERE activo = 1 AND id_linea_articulo = $id ORDER BY nombre";
		$sql.= $WHERE;
	
		$datos = new consultarTabla($sql);
		$result = $datos -> obtenerRegistros();
		$total=$datos->cuentaRegistros($sql);
		
		if ($total >= 1)
		{	
			foreach ($result as $registro )
			{
				echo '<option value="'.$registro->id_producto.'" >'.utf8_encode($registro->nombre).'</option>';		
			}
		}	
	}
}

//echo $sql;

?>