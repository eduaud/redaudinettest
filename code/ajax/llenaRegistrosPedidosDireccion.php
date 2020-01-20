<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id_registro = $_POST["id_registro"];

$sql = "SELECT calle, numero_exterior, numero_interior, colonia, delegacion_municipio, na_estados.nombre AS estado, na_ciudades.nombre AS ciudad, codigo_postal, telefono_1, telefono_2, celular, referencias, na_rutas.nombre AS ruta
		FROM ad_clientes_direcciones_entrega
		LEFT JOIN na_estados ON ad_clientes_direcciones_entrega.id_estado = na_estados.id_estado
		LEFT JOIN na_ciudades ON ad_clientes_direcciones_entrega.id_ciudad = na_ciudades.id_ciudad
		LEFT JOIN na_rutas ON ad_clientes_direcciones_entrega.id_ruta = na_rutas.id_ruta
		WHERE id_cliente_direccion_entrega = $id_registro";

$datos = new consultarTabla($sql);

$result = $datos -> obtenerLineaRegistro();


		echo utf8_encode($result[calle]) . "|" . utf8_encode($result[numero_exterior]) . "|" . utf8_encode($result[numero_interior]) . "|" . utf8_encode($result[colonia]) . "|" . utf8_encode($result[delegacion_municipio]) . "|" . utf8_encode($result[estado]) . "|" . utf8_encode($result[ciudad]) . "|" . utf8_encode($result[codigo_postal]) . "|" . utf8_encode($result[telefono_1]) . "|" . utf8_encode($result[telefono_2]) . "|" . utf8_encode($result[celular]) . "|" . utf8_encode($result[referencias]) . "|" . utf8_encode($result[ruta]);







?>