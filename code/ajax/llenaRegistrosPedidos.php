<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id_registro = $_POST["id_registro"];

$sql = "SELECT ad_clientes.nombre AS cliente, na_sucursales.nombre AS sucursal, apellido_paterno, apellido_materno, na_categorias_cliente.nombre AS categoria, telefono_1, telefono_2, celular, email, ad_tipos_pago_clientes.nombre AS tipo_pago, na_fuente_contacto.nombre AS fuente_contacto, IF(requiere_factura=0, 'NO', 'SI') AS factura, IF(ad_clientes.activo=0, 'NO', 'SI') AS activo, ad_clientes.id_cliente AS id_cliente
		FROM ad_clientes
		LEFT JOIN na_sucursales ON ad_clientes.id_sucursal_alta = na_sucursales.id_sucursal
		LEFT JOIN na_categorias_cliente ON ad_clientes.id_categoria_cliente = na_categorias_cliente.id_categoria_cliente
		LEFT JOIN ad_tipos_pago_clientes ON ad_clientes.id_tipo_pago = ad_tipos_pago_clientes.id_tipo_pago
		LEFT JOIN na_fuente_contacto ON ad_clientes.id_fuente_contacto = na_fuente_contacto.id_fuente_contacto
		WHERE id_cliente = $id_registro";

$datos = new consultarTabla($sql);

$result = $datos -> obtenerLineaRegistro();


		echo utf8_encode($result[cliente]) . "|" . utf8_encode($result[sucursal]) . "|" . utf8_encode($result[apellido_paterno]) . "|" . utf8_encode($result[apellido_materno]) . "|" . utf8_encode($result[categoria]) . "|" . utf8_encode($result[telefono_1]) . "|" . utf8_encode($result[telefono_2]) . "|" . utf8_encode($result[celular]) . "|" . utf8_encode($result[email]) . "|" . utf8_encode($result[tipo_pago]) . "|" . utf8_encode($result[fuente_contacto]) . "|" . utf8_encode($result[factura]) . "|" . utf8_encode($result[activo]) . "|" . $result[id_cliente];







?>