<?php

	if ($tabla=='sys_usuarios')
	{
		//---------------------
		if($make=="insertar" || $make=="actualizar")
		{
			//---------------------------------------
			//-------------------------------------
			//colocamos los datos del registro
			$idcampo=retornaIDCatalogoOrden("id_grupo","sys_usuarios");

			$campoGrupo= "campo_".$idcampo;
			$valorGrupo= $$campoGrupo;

			$sql="DELETE FROM sys_usuarios_grupos where id_usuario = '".$llave."'  ";
			mysql_query($sql);

			$sql="INSERT INTO sys_usuarios_grupos(id_detalle, id_usuario, id_grupo) VALUES(NULL, '".$llave."', '".$valorGrupo."')";
			mysql_query($sql);



		}

		$smarty->assign("id_usuario",$llave);
		//--------------------

	}
	else if ($tabla == 'ad_movimientos_almacen')
	{
		//---------------------
		if($make=="insertar" || $make=="actualizar")
		{
			$res=array();

			$strSQL=" SELECT id_control_movimiento,
								id_movimiento,
								id_tipo_movimiento,
								id_subtipo_movimiento,
								id_almacen
  			FROM ad_movimientos_almacen  where id_control_movimiento = '".$llave."'";


			$res=valBuscador($strSQL);

		}

		if($make=="insertar")
		{
			//----------------------------------------
			//subtipo de movimiento

			$id_global_subtipo_movimiento=$res[3];

			$valor_almacen=$res[4];

			//$valor_usuario=$res[15];
			//$valor_usuario_valido=$res[16];
			//------------------
			//------------------

			//CHECA SI YA ESTA DADO DE ALTA
			asignaConsecutivoMovimientoAlmacen($llave,$id_global_subtipo_movimiento,$valor_almacen);
			//70004	1	entradas iniciales +

			if($res[3]=='70004'  || $res[3]=='70008')
			{
				asignaLoteDetalles($llave,0);
			}
			//-------------------------------------------------
			//70001	1	INVENTARIO +
			//70003	1	AJUSTE +
			//70005	1	TRASPASO ENTRADA REPARACION A CEDIS +
			//70006	1	TRASPASO ENTRADA ENTRE SUCURSALES +
			//70010	1	ENTRADA POR INVENTARIO FISICO +
			//-------------------------------
			//70012	2	MERMAS -
			//70033	2	AJUSTE -
			//71010	2	INVETARIO FISICO -

			//-------------------------------------------------
			//requieren validacion de direccion
			if($res[3]=='70001' || $res[3]=='70003'  || $res[3]=='70005' || $res[3]=='70006' || $res[3]=='70010' || $res[3]=='70012' || $res[3]=='70033' || $res[3]=='71010')
			{


				//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
				//!ENVIAMOS NOTIFICACION QUE DEBEN CONFIRMAR O AUTORIZAR LA ENTRADA O SALIDA QUE SE ESTA GENERANDO
				//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

				$strUpdate="UPDATE ad_movimientos_almacen set no_modificable=1 where id_control_movimiento = '".$llave."'";
				mysql_query($strUpdate) or die("Error en consulta $strUpdate ");

			}
			else
			{

				$strUpdate="UPDATE ad_movimientos_almacen set no_modificable=1 where id_control_movimiento = '".$llave."'";
				mysql_query($strUpdate) or die("Error en consulta $strUpdate ");

			}


			//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
			//!ENVIAMOS NOTIFICACION QUE DEBEN CONFIRMAR LA ENTRADA
			//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
			//SI LOS TIPOS DE MOVIMIENTOS SON DE SALIDA TRASPASOS GENERAMOS LA ENTRADA EN EL ALMACEN SELECCIONADO QUE SALIO

			//GENERAMOS LA SALIDA DE LSO PRODUCTOS QUE SALIERON CON SU DETALLE
			//	X	x	70055	2	TRASPASO SALIDA DEVOLUCION / RERACION A CEDIS -
			//	X	x	70066	2	TRASPASO SALIDA ENTRE SUCURSALES -
			if($res[3]=='70055' || $res[3]=='70066'  )
			{
				//genermaos entrada al almacen destino
				generaEntradaAlmacen($llave,$res[3]);

			}





		}
		else if($make=="actualizar")
		{



			//solo permitimos modificar los tipos de movimientos  700003  ajustr, 70005,  70006 70007
			//	70003	AUT	AJUSTE +
			//	70005	CONF	TRASPASO ENTRADA REPARACION A CEDIS +
			//	70006	CONF	TRASPASO ENTRADA ENTRE SUCURSALES +
			//	70010	AUT	ENTRADA POR INVENTARIO FISICO +
			//	70012	AUT	MERMAS -
			//	70033 	AUT	AJUSTE -
			//	71010	AUT	INVETARIO FISICO -


			//SI LO GUARDO ENTONCES LO CONFSIRMO

			//PARA LAS AUTORIZACIONES
			if($res[3]=='70003' || $res[3]=='70010'  || $res[3]=='70012' || $res[3]=='70033' || $res[3]=='71010' )
			{

				//SI EL MOVIMIENTO DEL ALAMCEN

				$strUpdate="UPDATE ad_movimientos_almacen set no_modificable=1,id_usuario_valido='".$_SESSION['USR']->userid."' where id_control_movimiento = '".$llave."'";
				mysql_query($strUpdate) or die("Error en consulta $strUpdate ");

			}

			//PARA LAS CONFIRMACIONES
			if($res[3]=='70005' || $res[3]=='70006'  )
			{
				$strUpdate="UPDATE ad_movimientos_almacen set no_modificable=1,id_usuario_valido='".$_SESSION['USR']->userid."' where id_control_movimiento = '".$llave."'";
				mysql_query($strUpdate) or die("Error en consulta $strUpdate ");

			}




		}


	}
	if ($tabla=='ad_pedidos'){
		if($make == "insertar"){
			$sqlAlmacen = "
				SELECT id_almacen_solicita
				FROM ad_pedidos
				WHERE id_control_pedido = ".$llave;
			$datoAlmacen = valBuscador($sqlAlmacen);
			$sql = "
				SELECT COUNT(1) FROM ad_pedidos WHERE id_pedido =
				(
					SELECT id_pedido FROM ad_pedidos WHERE id_control_pedido = ".$llave."
				) AND id_control_pedido <> ".$llave." AND id_almacen_solicita = ".$datoAlmacen[0];
			$datos = valBuscador($sql);
			if($datos[0] > 0){

				$sql2 = "
					SELECT id_pedido, MAX(consecutivo) + 1 AS siguiente,clave
					FROM ad_pedidos
					LEFT JOIN ad_almacenes
						ON ad_almacenes.id_almacen = ad_pedidos.id_almacen_solicita
					LEFT JOIN ad_sucursales_almacenes_detalle
						ON ad_sucursales_almacenes_detalle.id_almacen = ad_almacenes.id_almacen
					LEFT JOIN ad_sucursales
						ON ad_sucursales.id_sucursal = ad_sucursales_almacenes_detalle.id_sucursal
					WHERE ad_almacenes.id_almacen = ".$datoAlmacen[0];
				$aDatos = valBuscador($sql2);
				$id_pedido = $aDatos[2]."-P".$aDatos[1];
				$consecutivo = $aDatos[1];
				$actualizaPedido = "
					UPDATE ad_pedidos SET id_pedido = '".$id_pedido."',consecutivo = '".$consecutivo."'
					WHERE id_control_pedido = ".$llave;
				mysql_query($actualizaPedido) or die("Error en :: ".$actualizaPedido);
			}
		}
	}

	if ($tabla=='na_cuentas_por_cobrar'){
			if($make=="insertar" || $make=="actualizar"){
					$sql = "SELECT id_cuenta_por_cobrar_detalle_pagos, id_cuenta_por_cobrar, monto, fecha FROM na_cuentas_por_cobrar_detalle_pagos WHERE id_cuenta_por_cobrar = " . $llave . " AND id_forma_pago_cuenta_por_cobrar = 1 AND (id_ingreso IS NULL OR id_ingreso = 0)";
					$datos = new consultarTabla($sql);
					$contador = $datos -> cuentaRegistros();
					if($contador > 0){
							$result = $datos -> obtenerRegistros();
							foreach($result as $registros){
									$ingresos['id_tipo_ingreso'] = 3;
									$ingresos['id_cuenta_por_cobrar'] = $llave;
									$ingresos['fecha_ingreso'] = date('Y-m-d');
									$ingresos['monto'] = $registros -> monto;
									$ingresos['id_sucursal'] = $_SESSION["USR"]->sucursalid;
									$ingresos['confirmado'] = 1;
									$ingresos['id_cuenta_por_cobrar_detalle'] = $registros -> id_cuenta_por_cobrar_detalle_pagos;
									accionesMysql($ingresos, 'ad_ingresos_caja_chica', 'Inserta');

									$actualiza = "UPDATE na_cuentas_por_cobrar_detalle_pagos SET id_ingreso = " . mysql_insert_id() . " WHERE id_cuenta_por_cobrar_detalle_pagos = " . $registros -> id_cuenta_por_cobrar_detalle_pagos;
									mysql_query($actualiza) or die("Error en consulta:<br> $actualiza <br>" . mysql_error());
									}

							}
					}
			}

	if ($tabla=='ad_egresos'){
		if($make=="insertar" || $make=="actualizar"){
				$sql = "SELECT ad_egresos_detalle.id_cuenta_por_pagar, ad_egresos_detalle.id_egreso, ad_egresos.fecha, ad_egresos_detalle.monto ,activoDetEgreso, ad_egresos_detalle.id_egreso_detalle
						FROM ad_egresos_detalle
						LEFT JOIN ad_egresos ON ad_egresos_detalle.id_egreso = ad_egresos.id_egreso
						WHERE ad_egresos.id_egreso = " . $llave;
				$result = new consultarTabla($sql);
				$datos = $result -> obtenerRegistros();

				foreach($datos as $registros){

						$egresos['id_cuenta_por_pagar'] = $registros -> id_cuenta_por_pagar;
						$egresos['id_egreso'] = $registros -> id_egreso;
						$egresos['fecha_egreso'] = $registros -> fecha;
						$egresos['monto_egreso'] = $registros -> monto;
						$egresos['id_egreso_detalle'] = $registros -> id_egreso_detalle;

						//si actualizamos la cuenta por pagar a saldada
						$activoDetEgreso=$registros -> activoDetEgreso;
						$id_egreso_detalle=$registros -> id_egreso_detalle;
						$cxp=$registros -> id_cuenta_por_pagar;

						$arrDatos2=array();
						unset($arrDatos2);

						//verificamos que no este registrado el id_egreso_detalle en la cuenta por pagar
						$strSQL = "SELECT count(id_cuenta_por_pagar_pago_egreso_detalle) FROM na_cuentas_por_pagar_pagos_egresos_detalle where  id_egreso_detalle ='".$id_egreso_detalle."' ";
						$arrDatos2 = valBuscador($strSQL);
						$contadorDet= $arrDatos2[0];

						if($contadorDet==0 && $activoDetEgreso ==1)
						{
							accionesMysql($egresos, 'na_cuentas_por_pagar_pagos_egresos_detalle', 'Inserta'); //Funcion que inserta o actualiza segun la opcion solo declarando en un array asociativo los campos y los valores que insetara
						}
						elseif($contadorDet>0 && $activoDetEgreso ==1)
						{
							//realizamos un update
							$strSQL="UPDATE na_cuentas_por_pagar_pagos_egresos_detalle
							         SET   id_cuenta_por_pagar ='".$cxp."',
										   id_egreso='".$registros -> id_egreso."',
										   fecha_egreso='".$registros -> fecha."',
										   monto_egreso='".$registros -> monto."'
								     WHERE id_egreso_detalle ='".$id_egreso_detalle."'";

							mysql_query($strSQL) or die("Error en consulta:<br> $strSQL <br>" . mysql_error());

						}//si hay cuentas por pagar
						elseif($contadorDet>0 && $activoDetEgreso ==0)
						{
							//eliminamos el egreso del detalle
							$strSQL="DELETE FROM na_cuentas_por_pagar_pagos_egresos_detalle WHERE id_egreso_detalle ='".$id_egreso_detalle."'";
							mysql_query($strSQL) or die("Error en consulta:<br> $strSQL <br>" . mysql_error());
						}

						//actualizaEstatusCxP
						actualizaEstatusPorPagar($cxp);

				}
		}
	}

	if ($tabla=='na_depositos_bancarios'){
		if($make=="insertar" || $make=="actualizar"){
				$sql = "SELECT id_pedido_detalle_pago
						FROM na_depositos_bancarios_detalle
						WHERE id_deposito_bancario = " . $llave;
				$result = new consultarTabla($sql);
				$datos = $result -> obtenerRegistros();
				foreach($datos as $registros){
						$actualiza = "UPDATE na_pedidos_detalle_pagos SET id_deposito_bancario = " . $llave . " WHERE id_pedido_detalle_pago = " . $registros -> id_pedido_detalle_pago;
						mysql_query($actualiza) or die("Error en consulta:<br> $actualiza <br>" . mysql_error());
						}
				}
		}
// JA 04/12/2015 -->
	if ($tabla=='ad_ordenes_compra_productos'){
		if($make=="insertar"){

			#2 Se obtiene el numero de ordenes de compra que se ha generado para el almacen
			$sql = "SELECT COUNT(*) numero_de_ordenes FROM ad_ordenes_compra_productos WHERE id_almacen_recepcion IN (";
			$sql .= " SELECT id_almacen_recepcion FROM ad_ordenes_compra_productos WHERE id_control_orden_compra = '".$llave."'";
			$sql .= " );";
			$result = new consultarTabla($sql);
			$datos = $result -> obtenerRegistros();
			foreach($datos as $registros){
				$numeroDeOrdenes = $registros->numero_de_ordenes;
			}

			#3 Se obtiene la clave del almacen
			$sql = "SELECT clave_almacen FROM ad_almacenes WHERE id_almacen IN(";
			$sql .= " SELECT id_almacen_recepcion FROM ad_ordenes_compra_productos WHERE id_control_orden_compra = '".$llave."'";
			$sql .= " );";
			$result = new consultarTabla($sql);
			$datos = $result -> obtenerRegistros();
			foreach($datos as $registros){
				$claveDeAlmacen = $registros->clave_almacen;
			}

			$idOrdenCompra = $claveDeAlmacen."_ODC".$numeroDeOrdenes;

			#Se actualiza la clave generada de la orden de compra
			$actualiza = "UPDATE ad_ordenes_compra_productos SET id_orden_compra = '".$idOrdenCompra."' WHERE id_control_orden_compra = '".$llave."';";
			mysql_query($actualiza) or die("Error en consulta:<br> $actualiza <br>" . mysql_error());

/*
			$sql = "SELECT ad_ordenes_compra_productos.id_control_orden_compra, ad_almacenes.clave_almacen, ad_ordenes_compra_productos.id_almacen_recepcion";
			$sql .= " FROM ad_ordenes_compra_productos";
			$sql .= " INNER JOIN ad_almacenes";
			$sql .= " ON ad_ordenes_compra_productos.id_almacen_recepcion = ad_almacenes.id_almacen";
			$sql .= " WHERE id_orden_compra IN ( SELECT id_orden_compra FROM ad_ordenes_compra_productos WHERE id_control_orden_compra = '".$llave."' );";
			$result = new consultarTabla($sql);
			$datos = $result -> obtenerRegistros();
			foreach($datos as $registros){

				$sql = "SELECT COUNT(*)+1 AS Total FROM ad_ordenes_compra_productos WHERE id_almacen_recepcion = '".$registros->id_almacen_recepcion."';";
				$resultMax = new consultarTabla($sql);
				$datosMax = $resultMax -> obtenerRegistros();
				foreach($datosMax as $registrosMax){

					$nueva_clave = $registros->clave_almacen.'_ODC'.$registrosMax->Total;
					$actualiza = "UPDATE ad_ordenes_compra_productos SET id_orden_compra = '".$nueva_clave."' WHERE id_control_orden_compra = '".$registros->id_control_orden_compra."';";
					mysql_query($actualiza) or die("Error en consulta:<br> $actualiza <br>" . mysql_error());

				}
			}
*/
		}
	}

// JA 04/12/2015 <--

	if($tabla == "na_bitacora_rutas"){
			if($make=="insertar" || $make=="actualizar"){
					$sql = "SELECT cerrar_bitacora FROM na_bitacora_rutas WHERE id_bitacora_ruta = " . $llave;
					$result = new consultarTabla($sql);
					$datos = $result -> obtenerLineaRegistro();

					if($datos['cerrar_bitacora'] == 1){
							$actualiza = "UPDATE na_bitacora_rutas SET id_usuario_cerro = " . $_SESSION['USR'] -> userid . " WHERE id_bitacora_ruta = " . $llave;
							mysql_query($actualiza) or die("Error en consulta:<br> $actualiza <br>" . mysql_error());
							}

					$sqlDet = "SELECT id_tipo_documento AS documento, id_partida AS partida
								FROM na_bitacora_rutas_entregas_detalle
								WHERE id_bitacora_ruta = " . $llave . " AND activoDetBitacora = 0";
					$resultDet = new consultarTabla($sqlDet);
					$datosDet = $resultDet -> obtenerRegistros();
					$contador = $resultDet -> cuentaRegistros();

					if($contador > 0){

							foreach($datosDet as $detalles){
									if($detalles -> documento == 1){
											$actualiza = "UPDATE na_movimientos_almacen_detalle SET id_bitacora_ruta = 0 WHERE id_detalle = " . $detalles -> partida;
											mysql_query($actualiza) or die("Error en consulta:<br> $actualiza <br>" . mysql_error());
											}
									}
							}


					}
			}

	if ($tabla=='ad_cuentas_por_pagar_operadora'){
		if($make=="insertar"){
				$sql = "SELECT ad_cuentas_por_pagar_operadora.id_costeo_productos, ad_cuentas_por_pagar_operadora.id_cuenta_por_pagar, ad_cuentas_por_pagar_operadora.id_tipo_cuenta_por_pagar, ad_proveedores.id_proveedor, ad_proveedores.id_tipo_proveedor, ad_cuentas_por_pagar_operadora.total, ad_cuentas_por_pagar_operadora.observaciones, ad_cuentas_por_pagar_operadora.valida_xml, ad_cuentas_por_pagar_operadora.nombre_xml
						FROM ad_cuentas_por_pagar_operadora
						LEFT JOIN ad_proveedores ON ad_cuentas_por_pagar_operadora.id_proveedor = ad_proveedores.id_proveedor
						WHERE ad_cuentas_por_pagar_operadora.id_cuenta_por_pagar = " . $llave;

				$result = new consultarTabla($sql);
				$datos = $result -> obtenerLineaRegistro();


				$costeo['id_costeo_productos'] = $datos['id_costeo_productos'];
				$costeo['id_cuentas_por_pagar'] = $datos['id_cuenta_por_pagar'];
				$costeo['id_tipo_cuenta_por_pagar'] = $datos['id_tipo_cuenta_por_pagar'];
				$costeo['id_proveedor'] = $datos['id_proveedor'];
				$costeo['id_tipo_proveedor'] = $datos['id_tipo_proveedor'];
				$costeo['total'] = $datos['total'];
				$costeo['observaciones'] = $datos['observaciones'];

				accionesMysql($costeo, 'ad_costeo_productos_cuentas_por_pagar', 'Inserta'); //Funcion que inserta o actualiza segun la opcion solo declarando en un array asociativo los campos y los valores que insetara

				if($datos['valida_xml'] == 1){
					$sqlProveedor="SELECT ad_proveedores.id_proveedor,rfc
										FROM ad_proveedores
										LEFT JOIN ad_cuentas_por_pagar_operadora ON ad_cuentas_por_pagar_operadora.id_proveedor=ad_proveedores.id_proveedor
										WHERE ad_cuentas_por_pagar_operadora.id_cuenta_por_pagar=".$llave;
						$result = new consultarTabla($sqlProveedor);
						$datosProveedor = $result -> obtenerLineaRegistro();
						$temporal = '../ajax/xml/'; //Directorio base
						$rutaXml = "../../../audicel_archivos/cfdicxp/".$datosProveedor['rfc']."/";
						$archivo = $datos['nombre_xml'];
						$origen = $temporal . $archivo;
						$destino = $rutaXml. $archivo;
						if (!file_exists($rutaXml)){
							mkdir($rutaXml);
							chmod($rutaXml,0777);
						}
						if(copy($origen,$destino)){
							chmod($destino,0777);
							$actualiza = "UPDATE ad_cuentas_por_pagar_operadora SET referencia_xml = '" . $destino . "' WHERE id_cuenta_por_pagar = " . $llave;
							mysql_query($actualiza) or die("Error en consulta:<br> $actualiza <br>" . mysql_error());
							unlink($origen); //Borramos el archivo subido en la carpeta temporala de lectura de XML
						}
				}
				$actualiza = "UPDATE ad_cuentas_por_pagar_operadora SET id_estatus_cuentas_por_pagar = 1 WHERE id_cuenta_por_pagar = " . $llave;
				mysql_query($actualiza) or die("Error en consulta:<br> $actualiza <br>" . mysql_error());

		}
		//vemos si el total de pagos de la cuenta por pagar esta saldada, entonces cambiamos el estatus

		if($make=="insertar" || $make=="actualizar")
		{
			actualizaEstatusPorPagar($llave);
		}

	}
	if ($tabla=='ad_costeo_productos'){
			if($make=="actualizar"){

					//$sql = "SELECT id_costeo_productos, id_orden_compra, id_proveedor, total_productos, genero_cuenta_por_pagar, no_modificable
					$sql = "SELECT id_costeo_productos, id_proveedor, total_productos, genero_cuenta_por_pagar, no_modificable
							FROM ad_costeo_productos
							WHERE id_costeo_productos = " . $llave;
					$result = new consultarTabla($sql);
					$datos = $result -> obtenerLineaRegistro();
					if(empty($datos['genero_cuenta_por_pagar'])){
							$cxp['id_costeo_productos'] = $datos['id_costeo_productos'];
							$cxp['id_proveedor'] = $datos['id_proveedor'];
							$cxp['id_tipo_proveedor'] = 1;
							$cxp['subtotal_productos'] = $datos['total_productos'];
							$cxp['fecha_captura'] = date('Y-m-d');
							$cxp['fecha_documento'] = date('Y-m-d');
							$cxp['fecha_vencimiento'] = date('Y-m-d');
							$cxp['id_tipo_cuenta_por_pagar'] = 1;
							$cxp['id_estatus_cuentas_por_pagar'] = 1;

							accionesMysql($cxp, 'ad_cuentas_por_pagar_operadora', 'Inserta');
							$ultimo_cxp = mysql_insert_id();

							$costeo['id_costeo_productos'] = $datos['id_costeo_productos'];
							$costeo['id_cuentas_por_pagar'] = $ultimo_cxp;
							$costeo['id_tipo_cuenta_por_pagar'] = 1;
							$costeo['id_proveedor'] = $datos['id_proveedor'];
							$costeo['id_tipo_proveedor'] = 1;
							$costeo['total'] = $datos['total_productos'];
							$costeo['genero_cuenta_por_pagar'] = 1;


							accionesMysql($costeo, 'ad_costeo_productos_cuentas_por_pagar', 'Inserta');

							$sql = "SELECT id_producto, cantidad, costo_neto_unitario, importe
									FROM ad_costeo_productos_detalle
									WHERE id_costeo_productos = " . $llave;
							$result = new consultarTabla($sql);
							$datos = $result -> obtenerRegistros();
							foreach($datos as $registros){
									$detalleProd['id_cuenta_por_pagar'] = $ultimo_cxp;
									$detalleProd['id_producto'] = $registros -> id_producto;
									$detalleProd['cantidad'] = $registros -> cantidad;
									$detalleProd['costo'] = $registros -> costo_neto_unitario;
									$detalleProd['importe'] = $registros -> importe;
									accionesMysql($detalleProd, 'ad_cuentas_por_pagar_operadora_detalle_productos', 'Inserta');
									}



							$actualiza = "UPDATE ad_costeo_productos SET genero_cuenta_por_pagar = 1 WHERE id_costeo_productos = " . $llave;
							mysql_query($actualiza) or die("Error en consulta:<br> $actualiza <br>" . mysql_error());


							}

					if($datos['no_modificable'] != 0){
							 actualizaCostosLotes($llave);
							}
					}
			}
	if($tabla == 'ad_clientes'){
			if($make == "eliminar"){
				$sqlUpdateProveedor = "
					UPDATE ad_proveedores SET activo = 0 WHERE id_cliente =".$llave;
				mysql_query($sqlUpdateProveedor);
			}
			if($make == "insertar" || $make == "actualizar"){
					$sqlNombreCliente = "
						SELECT razon_social,id_cuenta_contable,id_tipo_cliente,id_cuenta_contable_anticipo
						FROM ad_clientes
						WHERE id_cliente = ".$llave;
					$arrCliente = valBuscador($sqlNombreCliente);
					$cuenta_padre = obtenerCuentaMayor(13,$arrCliente[2]);
					if($cuenta_padre == '')
						$cuenta_padre = 0;
					$tamanioLlave = strlen($llave);
					if($tamanioLlave == 1){
						$llaveCC = '000'.$llave;
					}elseif($tamanioLlave == 2){
						$llaveCC = '00'.$llave;
					}elseif($tamanioLlave == 3){
						$llaveCC = '0'.$llave;
					}else{
						$llaveCC = $llave;
					}
					if($arrCliente[1] == '' || $arrCliente[1] == '0'){
						$id_cc = GeneraCuentaContable($cuenta_padre,$llaveCC,$arrCliente[0],2,2,0,1);
						$id_cc_padre = $id_cc;
					}else{
						$id_cc = $arrCliente[1];
						$id_cc_padre = $id_cc;
						ActualizaCuentaContable($arrCliente[1],$arrCliente[0]);
					}
					if($arrCliente[3] == '' || $arrCliente[3] == '0'){
						$id_cc_anticipo = GeneraCuentaContable($id_cc,'9999',$arrCliente[0].' ANTICIPO',2,3,0,1);
						$id_cc_anticipo_padre = $id_cc_anticipo;
					}else{
						$id_cc_anticipo = $arrCliente[3];
						$id_cc_anticipo_padre = $id_cc_anticipo;
						ActualizaCuentaContable($arrCliente[3],$arrCliente[0].' ANTICIPO');
					}
					if(($arrCliente[1] == '' || $arrCliente[1] == '0') || ($arrCliente[2] == '' || $arrCliente[2] == '0')){
						$sqlUpdate = "
							UPDATE ad_clientes
							SET id_cuenta_contable = '".$id_cc."',
							id_cuenta_contable_anticipo = '".$id_cc_anticipo."'
							WHERE id_cliente = ".$llave;
						mysql_query($sqlUpdate) or die("error en $sqlUpdate ".mysql_error());
					}
					$sqlTiCli = "
						SELECT cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor
						FROM cl_tipos_cliente_proveedor
						LEFT JOIN ad_clientes
						ON cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = ad_clientes.id_tipo_cliente_proveedor
						WHERE ad_clientes.id_cliente = ".$llave;
					$res = valBuscador($sqlTiCli);

					if($res[0] == '1' || $res[0] == '2' || $res[0] == '3' || $res[0] == '6' || $res[0] == '9'){
						$sqlCli = "SELECT razon_social, rfc, calle_fiscal, numero_exterior_fiscal, numero_interior_fiscal, colonia_fiscal, id_estado_fiscal, id_ciudad_fiscal, delegacion_municipio_fiscal,codigo_postal_fiscal, telefono_1_fiscal,regimen,lugar_expedicion,email_fiscal
						FROM ad_clientes
						WHERE id_cliente = " . $llave;
						$result = new consultarTabla($sqlCli);
						// $datosFiscales TIENE LA INFORMACION FISCAL DE LOS CLIENTES
						$datosFiscales = $result -> obtenerLineaRegistro();

						//INSERTAR EN ad_datos_fiscales
						$fiscales['id_cliente'] = $llave;
						$fiscales['nombre_razon_social'] = $datosFiscales['razon_social'];
						$fiscales['rfc'] = $datosFiscales['rfc'];
						$fiscales['calle'] = $datosFiscales['calle_fiscal'];
						$fiscales['numero_exterior'] = $datosFiscales['numero_exterior_fiscal'];
						$fiscales['numero_interior'] = $datosFiscales['numero_interior_fiscal'];
						$fiscales['colonia'] = $datosFiscales['colonia_fiscal'];
						$fiscales['id_estado'] = $datosFiscales['id_estado_fiscal'];
						$fiscales['id_ciudad'] = $datosFiscales['id_ciudad_fiscal'];
						$fiscales['delegacion_municipio'] = $datosFiscales['delegacion_municipio_fiscal'];
						$fiscales['cp'] = $datosFiscales['codigo_postal_fiscal'];
						$fiscales['telefono_1'] = $datosFiscales['telefono_1_fiscal'];
						$fiscales['email_envio_facturas'] = $datosFiscales['email_fiscal'];
						$fiscales['regimen'] = $datosFiscales['regimen'];$fiscales['lugar_expedicion'] = $datosFiscales['lugar_expedicion'];
						$fiscales['activo'] = '1';
						accionesMysql($fiscales, 'ad_clientes_datos_fiscales', 'Inserta');
					}
					$sqlProveedor = "SELECT id_tipo_cliente_proveedor,id_sucursal,CONCAT(nombre,' ',apellido_paterno,' ',IFNULL(apellido_materno,'')) AS nombre_comercial,razon_social,clave,rfc,calle_fiscal,numero_exterior_fiscal,numero_interior_fiscal,colonia_fiscal,codigo_postal_fiscal,id_estado_fiscal,id_ciudad_fiscal,delegacion_municipio_fiscal,telefono_1_fiscal,email_fiscal FROM ad_clientes WHERE id_cliente=".$llave;
					$result = new consultarTabla($sqlProveedor);
					//$datosProveedor TIENE LA INFORMACION DE EL CLIENTE/PROVEEDOR PARA INSERTARLO EN ad_proveedores
					$datosProveedor = $result -> obtenerLineaRegistro();
					if($datosProveedor['id_tipo_cliente_proveedor']=='1'||$datosProveedor['id_tipo_cliente_proveedor']=='2'||$datosProveedor['id_tipo_cliente_proveedor']=='3'||$datosProveedor['id_tipo_cliente_proveedor']=='6'){
						$datosProveedor['id_tipo_cliente_proveedor']=$datosProveedor['id_tipo_cliente_proveedor'];

						$proveedores['id_tipo_proveedor'] = '0';
						$proveedores['id_tipo_cliente_proveedor']=$datosProveedor['id_tipo_cliente_proveedor'];
						$proveedores['id_sucursal'] = $datosProveedor['id_sucursal'];
						$proveedores['nombre_comercial'] = $datosProveedor['nombre_comercial'];
						$proveedores['razon_social'] = $datosProveedor['razon_social'];
						$proveedores['clave'] = $datosProveedor['clave'];
						$proveedores['rfc'] = $datosProveedor['rfc'];
						$proveedores['curp'] = '';
						$proveedores['calle'] = $datosProveedor['calle_fiscal'];
						$proveedores['numero_exterior'] = $datosProveedor['numero_exterior_fiscal'];
						$proveedores['numero_interior'] = $datosProveedor['numero_interior_fiscal'];
						$proveedores['colonia'] = $datosProveedor['colonia_fiscal'];
						$proveedores['codigo_postal'] = $datosProveedor['codigo_postal_fiscal'];
						$proveedores['id_pais'] = '1';
						$proveedores['id_estado'] = $datosProveedor['id_estado_fiscal'];
						$proveedores['id_ciudad'] = $datosProveedor['id_ciudad_fiscal'];
						$proveedores['delegacion_municipio'] = $datosProveedor['delegacion_municipio_fiscal'];
						$proveedores['telefono_1'] = $datosProveedor['telefono_1_fiscal'];
						$proveedores['telefono_2'] = '';
						$proveedores['fax'] = '';
						$proveedores['email'] = $datosProveedor['email_fiscal'];
						$proveedores['dias_credito'] = '0';
						$proveedores['id_default_mercancia_puesta'] = '0';
						$proveedores['id_cuenta_contable_anticipo'] = '1';
						$proveedores['id_cuenta_contable'] = '1';
						$proveedores['requiere_autorizacion_cxp']='0';
						$proveedores['permite_mezclar_tipo_producto_en_ODC'] = '0';
						$proveedores['id_cliente'] = $llave;
						$proveedores['activo'] = '1';

						$sql = "SELECT count(id_cliente) as cliente_proveedor FROM ad_proveedores WHERE id_cliente =".$llave;
						$result = new consultarTabla($sql);
						$datosP = $result -> obtenerLineaRegistro();
						if($datosProveedor['id_tipo_cliente_proveedor'] == '6'){
							$caso = 12;
						}else{
							$caso = 11;
						}
						$cuenta_padre = obtenerCuentaMayor($caso);

						if($datosP['cliente_proveedor'] == 0){
							//insertamos en cuentas contables
							accionesMysql($proveedores, 'ad_proveedores','Inserta');
							$SQlProv = "SELECT MAX(id_proveedor) as id_proveedor FROM ad_proveedores";
							$result = new consultarTabla($SQlProv);
							$aIDProveedor = $result -> obtenerLineaRegistro();
							$tamanioLlave = strlen($aIDProveedor['id_proveedor']);
							if($tamanioLlave == 1){
								$llaveCC = '000'.$aIDProveedor['id_proveedor'];
							}elseif($tamanioLlave == 2){
								$llaveCC = '00'.$aIDProveedor['id_proveedor'];
							}elseif($tamanioLlave == 3){
								$llaveCC = '0'.$aIDProveedor['id_proveedor'];
							}else{
								$llaveCC = $aIDProveedor['id_proveedor'];
							}

							$id_cc = GeneraCuentaContable($cuenta_padre,$llaveCC,$datosProveedor['razon_social'],2,2,0,1);
							$id_cc_anticipo_proveedor = GeneraCuentaContable($id_cc,'9999',$datosProveedor['razon_social'].' ANTICIPO',2,3,0,1);
						}elseif($datosP['cliente_proveedor'] != 0 || $datosP['cliente_proveedor'] != ''){
							$SQlProv = "SELECT MAX(id_proveedor) as id_proveedor FROM ad_proveedores WHERE id_cliente =".$llave;
							$result = new consultarTabla($SQlProv);
							$aIDProveedor = $result -> obtenerLineaRegistro();

							$tamanioLlave = strlen($aIDProveedor['id_proveedor']);
							if($tamanioLlave == 1){
								$llaveCC = '000'.$aIDProveedor['id_proveedor'];
							}elseif($tamanioLlave == 2){
								$llaveCC = '00'.$aIDProveedor['id_proveedor'];
							}elseif($tamanioLlave == 3){
								$llaveCC = '0'.$aIDProveedor['id_proveedor'];
							}else{
								$llaveCC = $aIDProveedor['id_proveedor'];
							}

							$Proveedor = "
								SELECT razon_social,id_cuenta_contable,id_tipo_proveedor,id_cuenta_contable_anticipo
								FROM ad_proveedores
								WHERE id_cliente = ".$llave;
							$arrProveedor = valBuscador($Proveedor);
							if($arrProveedor[1] == '' || $arrProveedor[1] == '0' || $arrProveedor[1] == '1'){
								$id_cc = GeneraCuentaContable($cuenta_padre,$llaveCC,$datosProveedor['razon_social'],2,2,0,1);
							}else{
								ActualizaCuentaContable($arrProveedor[1],$datosProveedor['razon_social']);
								$id_cc = $arrProveedor[1];
							}
							if($arrProveedor[3] == '' || $arrProveedor[3] == '0' || $arrProveedor[3] == '1'){
								$id_cc_anticipo_proveedor = GeneraCuentaContable($id_cc,'9999',$datosProveedor['razon_social'].' ANTICIPO',2,3,0,1);
							}else{
								ActualizaCuentaContable($arrProveedor[3],$datosProveedor['razon_social'].' ANTICIPO');
							}

							foreach($proveedores as $campo => $valor){
								$actualiza .= $campo . " = '" . $valor . "',";
							}
							$actualiza = trim($actualiza, ',');
							$sql = "UPDATE ad_proveedores SET " . $actualiza." WHERE id_cliente=".$llave;
							mysql_query($sql) or die("Error en consulta:<br> $sql <br>" . mysql_error());
						}
						$sqlUpdate = "
							UPDATE ad_proveedores
							SET id_cuenta_contable = '".$id_cc."',id_cuenta_contable_anticipo = '".$id_cc_anticipo_proveedor."'
							WHERE id_cliente = ".$llave;
						mysql_query($sqlUpdate) or die("error en $sqlUpdate ".mysql_error());

						$result = new consultarTabla($SQlProv);
						$aIDProveedor = $result -> obtenerLineaRegistro();
						$sqlUP = "UPDATE ad_clientes SET id_proveedor_relacionado =".$aIDProveedor['id_proveedor']." WHERE id_cliente=".$llave;
						mysql_query($sqlUP) or die("Error en consulta:<br> $sqlUP <br>" . mysql_error());

					}
					//ACTUALIZAR EN CLIENTES TIPO NITS O NIVS
					if($datosProveedor['id_tipo_cliente_proveedor']=='1'||$datosProveedor['id_tipo_cliente_proveedor']=='2'||$datosProveedor['id_tipo_cliente_proveedor']=='3'){
						$sql="UPDATE ad_clientes SET id_sucursal='".$datosProveedor['id_sucursal']."',clave='".$datosProveedor['clave']."',activo='".$activo."'
						WHERE id_cliente_padre=".$llave;
						mysql_query($sql);
					}
				}
			}
	if($tabla == 'ad_egresos_caja_chica'){
			if($make=="insertar"){
					$sql = "SELECT id_egreso, id_sucursal, id_sucursal_destino, total, observaciones, fecha FROM ad_egresos_caja_chica WHERE id_egreso = " . $llave;
					$result = new consultarTabla($sql);
					$datos = $result -> obtenerLineaRegistro();
					$ingresosT['id_tipo_ingreso'] = 2;
					$ingresosT['id_sucursal_origen'] = $datos['id_sucursal'];
					$ingresosT['fecha_ingreso'] = date('Y-m-d');
					$ingresosT['observaciones'] = $datos['observaciones'];
					$ingresosT['id_sucursal'] = $datos['id_sucursal_destino'];
					$ingresosT['confirmado'] = 0;
					$ingresosT['monto_egreso'] = $datos['total'];
					$ingresosT['fecha_egreso'] = $datos['fecha'];
					$ingresosT['id_egreso'] = $datos['id_egreso'];
					accionesMysql($ingresosT, 'ad_ingresos_caja_chica', 'Inserta');
					}
			}

	if ($tabla=='na_productos'){
			if($make=="insertar"){
					$sql = "SELECT id_lista_precios AS lista_precio FROM ad_lista_precios WHERE es_lista_precio_publico = 0";
					$result = new consultarTabla($sql);
					$datos = $result -> obtenerRegistros();
					$contador = $result -> cuentaRegistros();
					if($contador > 0){
							$sqlProd = "SELECT id_producto, precio_lista FROM na_productos WHERE id_producto = " . $llave;
							$resultProd = new consultarTabla($sqlProd);
							$datosProd = $resultProd -> obtenerLineaRegistro();

							foreach($datos as $lista){
									$listaProd['id_lista_precios'] = $lista -> lista_precio;
									$listaProd['id_producto'] = $datosProd['id_producto'];
									$listaProd['porcentaje'] = 0;
									$listaProd['precio_final'] = $datosProd['precio_lista'];
									accionesMysql($listaProd, 'na_listas_detalle_productos', 'Inserta');
									}


							}
					}
			}
	if ($tabla=='na_vales_productos'){
			if($make=="insertar"){
					$sql = "SELECT na_sucursales.prefijo AS prefijo, na_vales_productos.consecutivo AS consecutivo, na_vales_productos.id_sucursal AS sucursal_vale
							FROM na_vales_productos
							LEFT JOIN na_sucursales USING(id_sucursal)
							WHERE id_vale_producto = " . $llave;
					$datos = new consultarTabla($sql);
					$result = $datos -> obtenerLineaRegistro();

					$sqlRec = "SELECT MAX(consecutivo) AS consecutivo
					FROM na_vales_productos
					WHERE id_sucursal = " . $result['sucursal_vale'];
					$datosRec = new consultarTabla($sqlRec);
					$resultRec = $datosRec -> obtenerLineaRegistro();

					$reciboNum = $resultRec['consecutivo'] = 0 ? 1 : $resultRec['consecutivo'] + 1;

					$actualiza = "UPDATE na_vales_productos SET consecutivo = " . $reciboNum . ", valeno = '" . $result['prefijo'] . $reciboNum . "'
					WHERE id_vale_producto = " . $llave;
					mysql_query($actualiza) or die("Error en consulta:<br> $actualiza <br>" . mysql_error());
					}
			}
	/********************************query para cambio de estatus de pedidos****************************************/
	if ($tabla=='na_bitacora_rutas'){
			if($make=="insertar" || $make=="actualizar"){

					$sql = "SELECT na_bitacora_rutas_entregas_detalle.id_partida AS partida, MAX(na_movimientos_apartados.id_apartado) AS id_apartado,
					SUM(IF(na_bitacora_rutas_entregas_detalle.id_estatus_bitacora_entrega = 1, na_bitacora_rutas_entregas_detalle.cantidad, 0)) AS cantidad_entrega,
					MAX(na_movimientos_apartados.cantidad) AS cantidad_solc
					FROM na_bitacora_rutas
					LEFT JOIN na_bitacora_rutas_entregas_detalle ON na_bitacora_rutas.id_bitacora_ruta = na_bitacora_rutas_entregas_detalle.id_bitacora_ruta
					LEFT JOIN na_movimientos_almacen_detalle ON na_bitacora_rutas_entregas_detalle.id_partida = na_movimientos_almacen_detalle.id_detalle
					LEFT JOIN na_movimientos_apartados ON na_movimientos_almacen_detalle.id_apartado = na_movimientos_apartados.id_apartado
					WHERE na_bitacora_rutas.id_bitacora_ruta = " . $llave. "
					GROUP BY(na_movimientos_apartados.id_apartado)";

					$result = new consultarTabla($sql);
					$datos = $result -> obtenerRegistros();
							foreach($datos as $registros){
									if($registros -> cantidad_entrega == $registros -> cantidad_solc){
											$actualiza = "UPDATE na_movimientos_apartados SET id_estatus_apartado = 2 WHERE id_apartado = " . $registros -> id_apartado;
											mysql_query($actualiza) or die("Error en consulta:<br> $actualiza <br>" . mysql_error());
											}
									}

					$sqlPedido = "SELECT DISTINCT id_control_pedido FROM na_bitacora_rutas_entregas_detalle WHERE id_bitacora_ruta = " . $llave;
					$resultP = new consultarTabla($sqlPedido);
					$datosPedido = $resultP -> obtenerRegistros();
					foreach($datosPedido as $registrosP){
							$sqlDetalle = "SELECT na_pedidos_detalle.id_pedido_detalle,
											SUM(IF(na_movimientos_apartados.id_estatus_apartado = 2, 1, 0)) AS entregado,
											count(na_pedidos_detalle.id_pedido_detalle) AS total_partidas
											FROM na_pedidos_detalle
											LEFT JOIN na_movimientos_apartados ON na_pedidos_detalle.id_pedido_detalle = na_movimientos_apartados.id_pedido_detalle
											WHERE na_pedidos_detalle.id_control_pedido = 1" . $registrosP -> id_control_pedido;
							$datosDetalle = new consultarTabla($sqlDetalle);
							$resultDet = $datosDetalle -> obtenerLineaRegistro();

							if($resultDet['entregado'] == $resultDet['total_partidas']){
									$actualiza = "UPDATE ad_pedidos SET id_estatus_pedido = 4 WHERE id_control_pedido = " . $registrosP -> id_control_pedido;
									mysql_query($actualiza) or die("Error en consulta:<br> $actualiza <br>" . mysql_error());
									}
							}



					}

			}
	if($tabla == 'ad_pedidos'){
		if($make == "insertar"){
			$strSQLPedido = "
				SELECT id_pedido,id_sucursal_alta,total,id_cliente,id_control_pedido,id_almacen_solicita
				FROM ad_pedidos
				WHERE id_control_pedido = ".$llave;
			$resPedido = valBuscador($strSQLPedido);
			$strSQL = "
				SELECT count(id_control_pedido)
				FROM ad_pedidos
				WHERE id_pedido = '".$resPedido[0]."'
				AND id_control_pedido <> ".$llave;
			$resContador = valBuscador($strSQL);

			//SI EXISTE PEDIDO YA ASIGNADA CON ESE ID
			if($resContador[0] > 0){
				$sqlC = "
					SELECT clave
					FROM ad_sucursales
					LEFT JOIN ad_sucursales_almacenes_detalle
						ON ad_sucursales.id_sucursal = ad_sucursales_almacenes_detalle.id_sucursal
					LEFT JOIN ad_almacenes
						ON ad_sucursales_almacenes_detalle.id_almacen = ad_almacenes.id_almacen
					WHERE ad_almacenes.id_almacen = ".$resPedido[5];
				$datosClave = valBuscador($sqlC);

				$sql2 = "
					SELECT id_pedido, MAX(consecutivo) AS siguiente
					FROM ad_pedidos
					LEFT JOIN ad_sucursales
						ON ad_pedidos.id_sucursal_alta = ad_sucursales.id_sucursal
					LEFT JOIN ad_sucursales_almacenes_detalle
						ON ad_sucursales.id_sucursal = ad_sucursales_almacenes_detalle.id_sucursal
					LEFT JOIN ad_almacenes
						ON ad_sucursales_almacenes_detalle.id_almacen = ad_almacenes.id_almacen
					WHERE ad_almacenes.id_almacen = ".$resPedido[5];
				$datosPedido = valBuscador($sql2);
				if($datosPedido[0] == ''){
					$id_pedido = $datosClave[0]."-P1";
					$consecutivo = "1";
					$prefijo = $datosClave[0]."-P";
				}else{
					$id_pedido = $datosClave[0]."-P".($datosPedido[1] + 1);
					$consecutivo = $datosPedido[1] + 1;
					$prefijo = $datosClave[0]."-P";
				}

				$strUpdate = "
					UPDATE ad_pedidos
					SET consecutivo = '".$consecutivo."',
					id_pedido = '".$id_pedido."',
					prefijo =  '".$prefijo."'
					WHERE id_control_pedido = ".$llave;
				mysql_query($strUpdate) or rollback('$strUpdate',mysql_error(),mysql_errno(),$strUpdate);
			}
		}
	}
        
	
	if($tabla=='ad_notas_credito')
	{

		//include("../../code/cfdi/funciones_facturacion.php");
		//include("../../code/cfdi/facturacion_sellado_timbrado.php");

		//aplican solo las facturas manuales
		if($make=="insertar")
		{
			//actualizamos la direccion fiscal del cliente que no es franquicia

			//de la factura insertamos los fiscales de la empresa que este activa
			$strSQL="SELECT id_compania FROM sys_companias where activo= 1 ";
			$resDatCompania=valBuscador($strSQL);
			$idCompania=$resDatCompania[0];

			$strUp1="UPDATE ad_notas_credito SET  id_compania_fiscal='".$idCompania."', id_compania='".$idCompania."'  where id_control_nota_credito=".$llave." ";
			mysql_query($strUp1) or rollback('$strUp1',mysql_error(),mysql_errno(),$strUp1);


		}
	}
	function encode($x)
	{
		$aux1=md5("Peugeot2010");
		$aux2=base64_encode($x);
		if(strlen($aux2) >= strlen($aux1))
			$aux="c".substr($aux1, 0, 2).$aux2.substr($aux1, strlen($aux1)-2, 2);

		else
		{
			$num=strlen($aux2);
			if($num < 10)
				$num="0".$num;
			$aux="i".substr($aux1,0,1).$aux2.substr($aux1, 1+strlen($aux2), strlen($aux1)-strlen($aux2)-1).$num;
		}

		$aux=str_replace("=", "_q", $aux);

		return $aux;
	}

	function obtenIDTiempo($horaInicio)
	{
		//seleccionamos la primera hora
		$strSQL="SELECT id_tiempo FROM rac_tiempos where nombre='".$horaInicio.":00' limit 1";
		$resID=valBuscador($strSQL);
		return $resID[0];
	}

        
        if($op == 2 && $v == 0 && $tabla=='ad_facturas'){
            $select = "SELECT activo FROM $tabla WHERE id_control_factura = $llave";
            $datos = new consultarTabla($select);
            $result_datos = $datos -> obtenerLineaRegistro();

            if($result_datos['activo'] == 9 ){
                $message = "Esta prefactura ya ha sido aprobada";
                echo "<script type='text/javascript'>alert('$message');</script>";
                header('Location: '. $_SERVER['HTTP_REFERER']. "&message=".$message);
                exit;
            }            
        }
	if($tabla=='ad_facturas' && $make=="actualizar"){            
            $sql_datos = "SELECT 
                            id_moneda,
                            ad_facturas.id_sucursal,
                            ieps,
                            iva,
                            observaciones,
                            referencia_pdf,
                            referencia_xml,
                            retencion_isr,
                            retencion_iva,
                            subtotal,
                            tipo_cambio,
                            total,
                            ad_proveedores.id_proveedor,
                            ad_proveedores.id_tipo_proveedor
                        FROM
                            ad_facturas
                            LEFT JOIN 
                            ad_proveedores On ad_proveedores.id_cliente = ad_facturas.id_compania

                        WHERE
                            ad_facturas.id_control_factura = $llave";
            $datos = new consultarTabla($sql_datos);
            $result_datos = $datos -> obtenerLineaRegistro();
            
            $sql_datos_existe_cuenta_por_pagar_pendiente = "SELECT 
                            id_cuenta_por_pagar
                        FROM
                            ad_cuentas_por_pagar_auxiliar
                        WHERE
                            id_control_factura = $llave
                                AND estus_auxiliar = 1";
            $datos_ecppp = new consultarTabla($sql_datos_existe_cuenta_por_pagar_pendiente);
            $result_ecppp = $datos_ecppp -> obtenerLineaRegistro();
            //die($sql_datos_existe_cuenta_por_pagar_pendiente);
            if(empty($result_ecppp['id_cuenta_por_pagar'])){
                $ad_cuentas_por_pagar_auxiliar['id_control_factura'] = $llave;
                $ad_cuentas_por_pagar_auxiliar['id_moneda'] = $result_datos['id_moneda'];   
                $ad_cuentas_por_pagar_auxiliar['id_sucursal'] = $result_datos['id_sucursal'];    
                $ad_cuentas_por_pagar_auxiliar['ieps'] = $result_datos['ieps'];        
                $ad_cuentas_por_pagar_auxiliar['iva'] = $result_datos['iva'];         
                $ad_cuentas_por_pagar_auxiliar['observaciones'] = $result_datos['observaciones'];
                $ad_cuentas_por_pagar_auxiliar['referencia_pdf'] = $result_datos['referencia_pdf'];
                $ad_cuentas_por_pagar_auxiliar['referencia_xml'] = $result_datos['referencia_xml'];   
                $ad_cuentas_por_pagar_auxiliar['retencion_isr_documentos'] = $result_datos['retencion_isr'];
                $ad_cuentas_por_pagar_auxiliar['retencion_iva_documentos'] = $result_datos['retencion_iva'];    
                $ad_cuentas_por_pagar_auxiliar['subtotal'] = $result_datos['subtotal'];       
                $ad_cuentas_por_pagar_auxiliar['tipo_cambio'] = $result_datos['tipo_cambio'];
                $ad_cuentas_por_pagar_auxiliar['total'] = $result_datos['total'];
                $ad_cuentas_por_pagar_auxiliar['id_proveedor'] = $result_datos['id_proveedor'];                
                $ad_cuentas_por_pagar_auxiliar['id_tipo_cuenta_por_pagar'] = 1;
                $ad_cuentas_por_pagar_auxiliar['id_tipo_proveedor'] = $result_datos['id_tipo_proveedor'];
                $ad_cuentas_por_pagar_auxiliar['fecha_captura'] = date("Y-m-d");
                $ad_cuentas_por_pagar_auxiliar['id_tipo_documento_recibido'] = 1;
                //uuid 
                $ad_cuentas_por_pagar_auxiliar['folio_fiscal'] = $_POST['campo_46'];
                //foilio
                $ad_cuentas_por_pagar_auxiliar['numero_documento'] = $_POST['campo_45'];
                $ad_cuentas_por_pagar_auxiliar['fecha_documento'] = $_POST['campo_47'];

                accionesMysql($ad_cuentas_por_pagar_auxiliar, 'ad_cuentas_por_pagar_auxiliar', 'Inserta');                                                
            }                                        
	}
?>
