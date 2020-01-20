<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

include("../../code/ajax/especiales/funcionesExistenciaProductos.php");

mysql_query("SET NAMES 'utf8'");

$id = $_POST['id'];
$caso = $_POST['caso'];
$producto = $_POST['productos'];
$reconstruye = $_POST['reconst'];
$lista = $_POST['lista'];

if($lista == 1){

		if($caso == 1 && $reconstruye == 0)
				$where = "(na_productos.nombre LIKE '%$producto%' OR na_productos.sku LIKE '%$producto%')";

		else if(($caso == 2 && $reconstruye == 0) || ($caso == 3 && $reconstruye == 0))
				$where = "id_producto = $id";
				
		else if($reconstruye == 1)
				$where = "id_producto IN ($id)";
				
				$sql = "SELECT na_productos.nombre, na_productos.imagen_principal, FORMAT(na_productos.precio_lista, 2) AS precio_final, na_productos.id_producto, na_productos.id_producto, (SELECT if(sum(signo * cantidad) is null,0,sum(signo * cantidad)) as existencia FROM na_movimientos_almacen_detalle left join na_movimientos_almacen on na_movimientos_almacen_detalle.id_control_movimiento=na_movimientos_almacen.id_control_movimiento left join ad_almacenes on na_movimientos_almacen.id_almacen=ad_almacenes.id_almacen WHERE na_movimientos_almacen.no_modificable =1 and na_movimientos_almacen_detalle.id_producto = na_productos.id_producto) AS existencia, (SELECT if(SUM(cantidad) is null,0,SUM(cantidad)) as apartado  FROM na_movimientos_apartados where id_estatus_apartado=1  and id_producto = na_productos.id_producto) AS apartado, ((SELECT if(sum(signo * cantidad) is null,0,sum(signo * cantidad)) as existencia FROM na_movimientos_almacen_detalle left join na_movimientos_almacen on na_movimientos_almacen_detalle.id_control_movimiento=na_movimientos_almacen.id_control_movimiento left join ad_almacenes on na_movimientos_almacen.id_almacen=ad_almacenes.id_almacen WHERE na_movimientos_almacen.no_modificable =1 and na_movimientos_almacen_detalle.id_producto = na_productos.id_producto)-(SELECT if(SUM(cantidad) is null,0,SUM(cantidad)) AS apartado FROM na_movimientos_apartados where id_estatus_apartado=1  and id_producto = na_productos.id_producto)) AS disponible
		FROM na_productos
		WHERE activo = 1 AND " . $where . "
		ORDER BY na_productos.nombre";

		}
else{
		if($caso == 1 && $reconstruye == 0)
				$where = "(na_productos.nombre LIKE '%$producto%' OR na_productos.sku LIKE '%$producto%') AND id_lista_precios = $id";

		else if($caso == 2 && $reconstruye == 0 || $caso == 3 && $reconstruye == 0)
				$where = "id_lista_detalle_producto = $id";
				
		else if($reconstruye == 1)
				$where = "id_lista_detalle_producto IN ($id)";
				
				$sql = "SELECT na_productos.nombre, 
						na_productos.imagen_principal, 
						FORMAT(na_listas_detalle_productos.precio_final, 2) AS precio_final, 
						id_lista_detalle_producto, 
						na_productos.id_producto, 
						0 AS existencia, 
						0 AS apartado, 
						0 AS disponible
				FROM na_listas_detalle_productos
				LEFT JOIN na_productos ON na_listas_detalle_productos.id_producto = na_productos.id_producto
				WHERE " . $where . " ORDER BY na_productos.nombre";
		}
		
$datos = new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();


$tamArreglo= count($result);
for($i=0;$i<$tamArreglo;$i++)
{
	unset($arrResultado);
	$strRespuesta=obtenExistenciaUnProducto($result[$i][4]);
	$arrResultado=explode("|",$strRespuesta);
	
	
	$result[$i][5]=$arrResultado[0];
	$result[$i][6]=$arrResultado[1];
	$result[$i][7]=$arrResultado[2];
	

}

//echo obtenExistenciaUnProducto(50);

$smarty -> assign("caso", $caso);
$smarty -> assign("filas", $result);
echo $smarty->fetch('especiales/respuestaTablaPrepedidos.tpl');


?>