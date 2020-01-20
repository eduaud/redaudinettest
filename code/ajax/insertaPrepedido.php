<?php

date_default_timezone_set('America/Mexico_City');

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$cliente = utf8_decode($_POST["cliente"]);
$total = $_POST["total"];
$listaPrecio = $_POST["listaPrecio"];
$formaPago = $_POST["forma_pago"];
$productos = $_POST["productos"];
$id_sucursal = $_SESSION["USR"]->sucursalid;
$vendedor = $_POST["vendedor"];
$fecha = date("Y-m-d"); 
$hora = date("H:i:s");


$sqlSuc = "SELECT prefijo FROM na_sucursales WHERE id_sucursal = $id_sucursal";
$datosSuc = new consultarTabla($sqlSuc);
$prefijo = $datosSuc -> obtenerLineaRegistro();


$sqlFolio = "SELECT folio FROM na_pre_pedidos WHERE prefijo = '" . $prefijo['prefijo'] . "' ORDER BY folio DESC";
$datosFolio = new consultarTabla($sqlFolio);
$folio = $datosFolio -> cuentaRegistros();
if($folio == 0){
		$folio = 1;
		}
else{
		$folioSig = $datosFolio -> obtenerLineaRegistro();
		$folio = $folioSig['folio'] + 1;
		}

$id_control_prepedido = $prefijo['prefijo'] . $folio;

$sql = "INSERT INTO na_pre_pedidos(id_control_pre_pedido, id_sucursal, nombre_cliente, id_lista_precios, id_forma_pago, total, prefijo, folio, id_vendedor, fecha, hora, activo) VALUES('$id_control_prepedido', $id_sucursal, '$cliente', $listaPrecio, $formaPago, $total, '" . $prefijo['prefijo'] . "', $folio, $vendedor, '$fecha', '$hora', 1)";
mysql_query($sql) or die ("Error en la consulta: <br>$sql. ".mysql_error());
$idInsertado = mysql_insert_id();

/****Insertamos los detalles de los prepedidos ****/

		/*$partes[0];  //Id prodcuto
		$partes[1];  //Existencia
		$partes[2];  //Pendientes
		$partes[3];  //Disponible
		$partes[5];  //Cantidad
		$partes[5];  //Importe*/

$insertaProd = explode(",", $productos);
$contadorProd = count($insertaProd);

for($k=0; $k<$contadorProd; $k++){
		$partes = explode("|", $insertaProd[$k]);
		$sqlProd = "INSERT INTO na_pre_pedidos_detalle_productos(id_prepedido, id_producto, existencia, pendientes_entrega, disponible, cantidad, importe) VALUES ($idInsertado, $partes[0], $partes[1], $partes[2], $partes[3], $partes[4], $partes[5])";
		mysql_query($sqlProd) or die ("Error en la consulta: <br>$sqlProd. ".mysql_error());
		}

echo "Prepedido Guardado Correctamente";




?>