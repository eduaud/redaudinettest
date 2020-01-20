<?php
	
	include("../../include/fpdf153/fpdf.php");	
	//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../conect.php");
	include("../general/funciones.php");
	include("../../consultaBase.php");
	
	Global $logo;
	Global $sucursal;
	Global $nuevo_recibo;
	Global $fecha;
	Global $prefijo;
	
	$cajaEgreso = $_GET['cajaEgreso'];
	
	$sql = "SELECT MAX(consecutivo) AS norecibo FROM ad_egresos_caja_chica WHERE id_sucursal = " . $_SESSION["USR"]->sucursalid;
	$datos = new consultarTabla($sql);
	$result = $datos -> obtenerLineaRegistro();
	
	if($result['norecibo'] == ""){
			$nuevo_recibo = 1;
			}
	else{
			$nuevo_recibo = $result['norecibo'] + 1;
			}
	$sql2 = "SELECT ad_egresos_caja_chica.consecutivo, DATE_FORMAT(ad_egresos_caja_chica.fecha,'%d/%m/%Y') AS fecha, ad_sucursales.logo_sucursales AS logo, 
			ad_sucursales.nombre AS sucursal, ad_sucursales.prefijo AS prefijo
			FROM ad_egresos_caja_chica 
			LEFT JOIN ad_sucursales USING(id_sucursal)
			WHERE id_egreso = " . $cajaEgreso;

	$datos2 = new consultarTabla($sql2);
	$result2 = $datos2 -> obtenerLineaRegistro();

	if($result2['consecutivo'] == ""){
			$actualiza = "UPDATE ad_egresos_caja_chica SET consecutivo = " . $nuevo_recibo . " WHERE id_egreso = " . $cajaEgreso;
			mysql_query($actualiza);
			}
	else{
			$nuevo_recibo = $result2['consecutivo'];
			}
	
	$sqlEgreso = "SELECT ad_tipos_egreso_caja_chica.id_tipo_egreso AS id_tipo_egreso, ad_tipos_egreso_caja_chica.descripcion AS tipo_egreso, total AS total_egreso, 
					observaciones AS observaciones, id_usuario_registro AS usuario, recibio AS recibio,
					CONCAT(sys_usuarios.nombres,' ', if(sys_usuarios.apellido_paterno is null,'',sys_usuarios.apellido_paterno),' ',if(sys_usuarios.apellido_materno is null,'', sys_usuarios.apellido_materno)) AS usuario_nombre, id_pedido AS pedido, id_deposito_bancario AS deposito_bancario, id_cuenta_por_cobrar AS cuenta_cobrar,
					ad_sucursales.nombre AS sucursal_destino, ad_conceptos_gastos.nombre AS gasto
					FROM ad_egresos_caja_chica
					LEFT JOIN ad_tipos_egreso_caja_chica USING(id_tipo_egreso)
					LEFT JOIN sys_usuarios ON ad_egresos_caja_chica.id_usuario_registro = sys_usuarios.id_usuario
					LEFT JOIN ad_sucursales ON ad_egresos_caja_chica.id_sucursal_destino = ad_sucursales.id_sucursal
					LEFT JOIN ad_egresos_caja_chica_detalle USING(id_egreso)
					LEFT JOIN ad_conceptos_gastos ON ad_egresos_caja_chica_detalle.id_gasto = ad_conceptos_gastos.id_gasto
					WHERE id_egreso = " . $cajaEgreso . " GROUP BY ad_egresos_caja_chica.id_tipo_egreso";
	$consulta = new consultarTabla($sqlEgreso);
	$datos = $consulta->obtenerRegistros();
	
	$fecha = $result2['fecha'];
	$sucursal = $result2['sucursal'];
	$logo = $result2['logo'];
	$prefijo = $result2['prefijo'];
	
	include("egresoCH_pdf.php");
	
?>