<?php
	
	include("../../include/fpdf153/fpdf.php");	
	//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../conect.php");
	include("../general/funciones.php");
	include("../../consultaBase.php");
	
	$mov = $_GET['mov'];
	
	Global $logo;
	Global $almacen;
	Global $vale;
	Global $folio;
	Global $texto_tipo;
	
	$sqlTipo = "SELECT id_subtipo_movimiento,ad_almacenes.nombre AS almacen, ad_almacenes.id_almacen AS id_almacen, 
				ad_movimientos_almacen.observaciones AS observaciones,CONCAT(sys_usuarios.nombres,' ', if(sys_usuarios.apellido_paterno is null,'',sys_usuarios.apellido_paterno),' ',if(sys_usuarios.apellido_materno is null,'',sys_usuarios.apellido_materno)) AS usuario
				FROM ad_movimientos_almacen 
				LEFT JOIN ad_almacenes ON ad_movimientos_almacen.id_almacen = ad_almacenes.id_almacen
				LEFT JOIN sys_usuarios ON ad_movimientos_almacen.id_usuario = sys_usuarios.id_usuario
				WHERE id_control_movimiento=".$mov;
	$consultaTipo = new consultarTabla($sqlTipo);
	$datosTipo = $consultaTipo->obtenerLineaRegistro();
	
	if($datosTipo['id_subtipo_movimiento'] == 70099){
			$vale = "FOLIO DE SALIDA";
			
			if($datosTipo['id_almacen']!=1)
						$vale = "SALIDA EXTRAORDINARIA";
			
			$texto_tipo = "Tipo de Salida";			
			$strSQL1="SELECT  COUNT(distinct (id_control_pedido)) from ad_movimientos_almacen_detalle
					WHERE (id_control_pedido<>0 AND (NOT id_control_pedido  IS NULL)  )AND id_control_movimiento = " . $mov;
			
			$arrValores=valBuscador($strSQL1);
			
			if($arrValores[0]==1)
			{
				$strSQL2=   "SELECT id_control_pedido FROM ad_movimientos_almacen_detalle
 						     WHERE id_control_movimiento=".$mov." LIMIT 1";
			
				$arrPedido=valBuscador($strSQL2);
				
				
				//obtenemos el pedido y buscamos el clientes y  la direccion de entrega y el pedido
				$sql = "SELECT ad_movimientos_almacen.id_tipo_movimiento AS tipo, DATE_FORMAT(fecha_movimiento, '%d/%m/%Y') AS fecha, ad_pedidos.id_pedido AS pedido, ad_subtipos_movimientos.nombre AS tipo_movimiento, ad_subtipos_movimientos.id_subtipo_movimiento AS id_subtipo, 
						CONCAT(ad_clientes.nombre,' ', if(ad_clientes.apellido_paterno is null,'',ad_clientes.apellido_paterno),' ',if(ad_clientes.apellido_materno is null,'',ad_clientes.apellido_materno)) AS cliente
						FROM ad_movimientos_almacen 
						LEFT JOIN ad_subtipos_movimientos ON ad_movimientos_almacen.id_subtipo_movimiento = ad_subtipos_movimientos.id_subtipo_movimiento=
						LEFT JOIN ad_pedidos ON  ad_pedidos.id_control_pedido= '".$arrPedido[0]."'
						LEFT JOIN ad_clientes ON ad_pedidos.id_cliente = ad_clientes.id_cliente
						WHERE id_control_movimiento=".$mov;
				
			}
			else{
				$sql = "SELECT ad_movimientos_almacen.id_tipo_movimiento AS tipo, 
						DATE_FORMAT(fecha_movimiento, '%d/%m/%Y') AS fecha,
						'VARIOS' AS pedido,
						ad_subtipos_movimientos.nombre AS tipo_movimiento, 
						ad_subtipos_movimientos.id_subtipo_movimiento AS id_subtipo, 
						'VARIOS' AS cliente,
						'VARIOS' AS direccion
						FROM ad_movimientos_almacen 
						LEFT JOIN ad_subtipos_movimientos ON ad_movimientos_almacen.id_subtipo_movimiento = ad_subtipos_movimientos.id_subtipo_movimiento
						WHERE id_control_movimiento=".$mov;
			}
			
			
			$consulta = new consultarTabla($sql);
			$informacion = $consulta -> obtenerRegistros();
			}
	else if($datosTipo['id_subtipo_movimiento'] == 70004){
			$vale = "FOLIO DE ENTRADA";
			$texto_tipo = "Tipo de Entrada";
			$sql = "SELECT na_movimientos_almacen.id_tipo_movimiento AS tipo, DATE_FORMAT(fecha_movimiento, '%d/%m/%Y') AS fecha,
					na_subtipos_movimientos.nombre AS tipo_movimiento, na_subtipos_movimientos.id_subtipo_movimiento AS id_subtipo 
				FROM na_movimientos_almacen 
				LEFT JOIN na_subtipos_movimientos ON na_movimientos_almacen.id_subtipo_movimiento = na_subtipos_movimientos.id_subtipo_movimiento
				WHERE id_control_movimiento = " . $mov;
			$consulta = new consultarTabla($sql);
			$informacion = $consulta -> obtenerRegistros();
			}
	else if($datosTipo['id_subtipo_movimiento'] == 70066){
			$vale = "FOLIO DE SALIDA";
			$texto_tipo = "Tipo de Salida";
			$sql = "SELECT na_movimientos_almacen.id_tipo_movimiento AS tipo, DATE_FORMAT(fecha_movimiento, '%d/%m/%Y') AS fecha,
					na_subtipos_movimientos.nombre AS tipo_movimiento, na_subtipos_movimientos.id_subtipo_movimiento AS id_subtipo,
					na_sucursales.nombre AS sucursal_destino,
					CONCAT('Calle: ',na_sucursales.calle,' ', 'Num. Ext: ',if(na_sucursales.numero_exterior is null,'',na_sucursales.numero_exterior),' ',if(na_sucursales.numero_interior is null,'',na_sucursales.numero_interior),' ','Col. ',if(na_sucursales.colonia is null,'',na_sucursales.colonia),' ','Del. o Mun. ',if(na_sucursales.delegacion_municipio is null,'',na_sucursales.delegacion_municipio)) AS direccion
				FROM na_movimientos_almacen 
				LEFT JOIN na_subtipos_movimientos ON na_movimientos_almacen.id_subtipo_movimiento = na_subtipos_movimientos.id_subtipo_movimiento
				LEFT JOIN na_sucursales ON na_movimientos_almacen.id_almacen_origen_destino = na_sucursales.id_almacen
				WHERE id_control_movimiento = " . $mov;
			$consulta = new consultarTabla($sql);
			$informacion = $consulta -> obtenerRegistros();
			}
	else if($datosTipo['id_subtipo_movimiento'] == 70055){
			$vale = "FOLIO DE SALIDA";
			$texto_tipo = "Tipo de Salida";
			$sql = "SELECT na_movimientos_almacen.id_tipo_movimiento AS tipo, DATE_FORMAT(fecha_movimiento, '%d/%m/%Y') AS fecha,
					na_subtipos_movimientos.nombre AS tipo_movimiento, na_subtipos_movimientos.id_subtipo_movimiento AS id_subtipo, id_solicitud_devolucion_cedis AS documento
				FROM na_movimientos_almacen 
				LEFT JOIN na_subtipos_movimientos ON na_movimientos_almacen.id_subtipo_movimiento = na_subtipos_movimientos.id_subtipo_movimiento
				LEFT JOIN na_sucursales ON ad_almacenes.id_almacen = na_sucursales.id_almacen
				WHERE id_control_movimiento = " . $mov;
			$consulta = new consultarTabla($sql);
			$informacion = $consulta -> obtenerRegistros();
			}
	else if($datosTipo['id_subtipo_movimiento'] == 70006){
			$vale = "FOLIO DE ENTRADA";
			$texto_tipo = "Tipo de Entrada";
			$sql = "SELECT na_movimientos_almacen.id_tipo_movimiento AS tipo, DATE_FORMAT(fecha_movimiento, '%d/%m/%Y') AS fecha,
					na_subtipos_movimientos.nombre AS tipo_movimiento, na_subtipos_movimientos.id_subtipo_movimiento AS id_subtipo,
					na_sucursales.nombre AS sucursal_origen,
					CONCAT('Calle: ',na_sucursales.calle,' ', 'Num. Ext: ',if(na_sucursales.numero_exterior is null,'',na_sucursales.numero_exterior),' ',if(na_sucursales.numero_interior is null,'',na_sucursales.numero_interior),' ','Col. ',if(na_sucursales.colonia is null,'',na_sucursales.colonia),' ','Del. o Mun. ',if(na_sucursales.delegacion_municipio is null,'',na_sucursales.delegacion_municipio)) AS direccion
				FROM na_movimientos_almacen 
				LEFT JOIN na_subtipos_movimientos ON na_movimientos_almacen.id_subtipo_movimiento = na_subtipos_movimientos.id_subtipo_movimiento
				LEFT JOIN na_sucursales ON na_movimientos_almacen.id_almacen_origen_destino = na_sucursales.id_almacen
				WHERE id_control_movimiento = " . $mov;
			$consulta = new consultarTabla($sql);
			$informacion = $consulta -> obtenerRegistros();
			}

else if($datosTipo['id_subtipo_movimiento'] == 70011 || $datosTipo['id_subtipo_movimiento'] == 70088){
			$vale = "FOLIO DE SALIDA";
			$texto_tipo = "Tipo de Salida";
			$sql = "SELECT na_movimientos_almacen.id_tipo_movimiento AS tipo, DATE_FORMAT(fecha_movimiento, '%d/%m/%Y') AS fecha,
					na_subtipos_movimientos.nombre AS tipo_movimiento,
          na_subtipos_movimientos.id_subtipo_movimiento AS id_subtipo,
		  razon_social,

					CONCAT('Calle: ',na_proveedores.calle,' ', 'Num. Ext: ',if(na_proveedores.numero_exterior is null,'',na_proveedores.numero_exterior),' ',if(na_proveedores.numero_interior is null,'',na_proveedores.numero_interior),' ','Col. ',if(na_proveedores.colonia is null,'',na_proveedores.colonia),' ','Del. o Mun. ',if(na_proveedores.delegacion_municipio is null,'',na_proveedores.delegacion_municipio),' , ',na_ciudades.nombre,' , ',na_estados.nombre,' , ',na_paises.nombre) AS direccion
				FROM na_movimientos_almacen
				LEFT JOIN na_subtipos_movimientos ON na_movimientos_almacen.id_subtipo_movimiento = na_subtipos_movimientos.id_subtipo_movimiento
				left join na_proveedores on na_proveedores.id_proveedor=na_movimientos_almacen.id_proveedor
left join na_ciudades on na_proveedores.id_ciudad=na_ciudades.id_ciudad
left join na_estados on na_estados.id_estado=na_ciudades.id_estado
left join na_paises on na_paises.id_pais=na_estados.id_pais
				WHERE id_control_movimiento = " . $mov;
			$consulta = new consultarTabla($sql);
			$informacion = $consulta -> obtenerRegistros();
			}
		
				
	/*************Consecutivo de recibo*********************
	
	$sql4 = "SELECT consecutivo FROM na_movimientos_almacen WHERE id_control_movimiento = " . $mov;
	$datos4 = new consultarTabla($sql4);
	$result4 = $datos4 -> obtenerLineaRegistro();

	if($result4['consecutivo'] == ""){
			$sqlF = "SELECT MAX(consecutivo) AS folio FROM na_movimientos_almacen WHERE id_almacen = " . $datosTipo['id_almacen'];
			$datosF = new consultarTabla($sqlF);
			$resultF = $datosF -> obtenerLineaRegistro();

			if($resultF['folio'] == ""){
					$folio = 1;
					}
			else{
					$folio = $resultF['folio'] + 1;
					}
			
			$actualiza = "UPDATE na_movimientos_almacen SET consecutivo = " . $folio . " WHERE id_control_movimiento = " . $mov;
			mysql_query($actualiza);
			}	
	else{
			$folio = $result4['consecutivo'];
			}
	/*********************************************************/		
	//Se reemplaza algoritmo de consecutivo por el iod movimiento
	$sqlFolio = "SELECT id_movimiento FROM ad_movimientos_almacen WHERE id_control_movimiento = " . $mov;
	$datos4 = new consultarTabla($sqlFolio);
	$result4 = $datos4 -> obtenerLineaRegistro();
	
	$prefijoReal = "";
	
	if($datosTipo['prefijo'] != "AA")
			$prefijoReal = $datosTipo['prefijo'];
	
	$folio = $prefijoReal . $result4['id_movimiento'];
			
			
	$logo = $datosTipo["logo_sucursales"];		
	
	$almacen = $datosTipo["almacen"];
	//CONCAT(na_productos.nombre,'   :: ' , na_marcas_productos.nombre) AS producto
	
	$sqlProd = "SELECT cantidad AS cantidad, clave AS clave, cl_productos_servicios.nombre AS producto,
				ad_lotes.lote AS lote, 
				ad_movimientos_almacen_detalle.observaciones AS observaciones
				FROM ad_movimientos_almacen_detalle
				LEFT JOIN cl_productos_servicios  ON ad_movimientos_almacen_detalle.id_producto=cl_productos_servicios.id_producto_servicio
				LEFT JOIN ad_lotes  ON ad_movimientos_almacen_detalle.id_lote=ad_lotes.id_lote
				WHERE ad_movimientos_almacen_detalle.id_control_movimiento= " . $mov;
	$consultaProd = new consultarTabla($sqlProd);
	$productos = $consultaProd -> obtenerRegistros();
	
	
		include("movimientos_pdf.php");
?>