<?php
set_time_limit (300);
/*ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);*/

	function getMonthDays($Month, $Year){
		   //Si la extensión que mencioné está instalada, usamos esa.
			if( is_callable("cal_days_in_month"))
					return cal_days_in_month(CAL_GREGORIAN, $Month, $Year);
		   
			else
					//Lo hacemos a mi manera.
					return date("d",mktime(0,0,0,$Month+1,0,$Year));
			}


	
	extract($_REQUEST);	
	$cambio = "";
	
	include('../../conect.php');
	include('../../code/general/funciones.php');
	include('../../include/phpreports/PHPReportMaker.php');
	include('../../consultaBase.php');
	/*
	$strFiltros = '';
	$titulo="";
	$listaPreciosNombre="12312";*/
	
	switch($idRep){
		case 1:		       
		
			$arrParametros=explode('~',$parametros);
			
			$arryFamilia = explode('@',$arrParametros[0]);
			$arryTipo = explode('@',$arrParametros[1]);
			$arryModelo = explode('@',$arrParametros[2]);
			$arryCaracteristica = explode('@',$arrParametros[3]);
			$arryProductos = explode('@',$arrParametros[4]);
			
			$strFamilia = $arryFamilia[1];
			$strTipo = $arryTipo[1];
			$strModelo=$arryModelo['1'];
			$strCaracteristica=$arryCaracteristica['1'];
			$strProductos=$arryProductos['1'];
			
			$strFamilia = str_replace('|', ',', $strFamilia);
			$strTipo = str_replace('|', ',', $strTipo);
			$strModelo = str_replace('|', ',', $strModelo);
			$strCaracteristica = str_replace('|', ',', $strCaracteristica);
			$strProductos = str_replace('|', ',', $strProductos);

			  
			$strWhere="";
			if($strFamilia <> '0,0')
			{
				$strWhere.= " AND na_familias_productos.id_familia_producto in (".$strFamilia.")";
			}
			if($strTipo <> '0,0')
			{
				$strWhere.= " AND na_tipos_productos.id_tipo_producto in (".$strTipo.")";
			}
			if($strModelo <> '0,0')
			{
				$strWhere.= " AND na_modelos_productos.id_modelo_producto in (".$strModelo.")";
			}
			if($strCaracteristica <> '0,0')
			{
				$strWhere.= " AND na_caracteristicas_productos.id_caracteristica_producto in (".$strCaracteristica.")";
			}
			if($strProductos <> '0')
			{
				$strWhere.= " AND na_productos.id_producto in (".$strProductos.")";
			}
			  
			  
			$strSQL = "
				 SELECT na_productos.sku as SKU, 
						na_familias_productos.nombre as Familia, 
						na_tipos_productos.nombre as Tipos, 
						na_modelos_productos.nombre as Modelos, 
						na_caracteristicas_productos.nombre as Caracteristicas, 
						na_productos.nombre as Producto ,
						if(na_productos.activo=1,'SI','NO') as activo, 
						if(na_productos.producto_compuesto=1,'SI','NO') as compuesto
						From na_productos 
						LEFT JOIN na_familias_productos 
							   on na_productos.id_familia_producto = na_familias_productos.id_familia_producto 
						LEFT JOIN na_tipos_productos 
							  on na_productos.id_tipo_producto = na_tipos_productos.id_tipo_producto 
						LEFT JOIN na_modelos_productos 
							  on na_productos.id_modelo_producto = na_modelos_productos.id_modelo_producto 
						LEFT JOIN na_caracteristicas_productos 
							  on na_productos.id_caracteristica_producto = na_caracteristicas_productos.id_caracteristica_producto 
						where 1 $strWhere ORDER BY na_familias_productos.nombre,na_tipos_productos.nombre,na_modelos_productos.nombre,na_caracteristicas_productos.nombre,na_productos.nombre ";
		break;		
			
		case 2:
			
			$arrParametros=explode('~',$parametros);
			
			$arrySucursal = explode('@',$arrParametros[0]);
			$arryCategoria = explode('@',$arrParametros[1]);
			$arryClientes = explode('@',$arrParametros[2]);
			
			$strSucursal = $arrySucursal[1];
			$strCategoria = $arryCategoria[1];
			$strClientes=$arryClientes['1'];
						
			$strSucursal = str_replace('|', ',', $strSucursal);
			$strCategoria = str_replace('|', ',', $strCategoria);
			$strClientes = str_replace('|', ',', $strClientes);
			  
			$strWhere="";
			if($strSucursal <> '0,0')
			{
				$strWhere.= " AND ad_sucursales.id_sucursal in (".$strSucursal.")";
			}
			if($strCategoria <> '0,0')
			{
				$strWhere.= " AND na_categorias_cliente.id_categoria_cliente in (".$strCategoria.")";
			}
			if($strClientes <> '0')
			{
				$strWhere.= " AND na_clientes.id_cliente in (".$strClientes.")";
			}
			  
			  
			$strSQL = "
				 	SELECT na_clientes.id_cliente as ID, 
					ad_sucursales.nombre as Sucursal, 
					na_categorias_cliente.nombre as Categoria, 
					CONCAT(na_clientes.nombre, ' ', apellido_paterno,' ', apellido_materno) AS 'Nombre' 
					From na_clientes 
					LEFT JOIN ad_sucursales on na_clientes.id_sucursal_alta = ad_sucursales.id_sucursal 
					LEFT JOIN na_categorias_cliente on na_clientes.id_categoria_cliente = na_clientes.id_categoria_cliente 
					where na_clientes.activo=1  $strWhere ";
		
		break;
		case 3:
		/*
			Vacio
			
			Array ( [opcion] => 1 
					[idRep] => 3 
					[parametros] => 
						pedido@
						~sucursal@0
						~vendedor@0
						~clientes@0
						~fechaDel@
						~fechaAl@
						~estatusPago@0
						~estatusPedido@0 
					) 
			
			clientes
			
			Array ( [opcion] => 1 
					[idRep] => 3 
					[parametros] => 
						pedido@
						~sucursal@0
						~vendedor@0
						~clientes@0|1|2
						~fechaDel@
						~fechaAl@
						~estatusPago@0
						~estatusPedido@0 
					) 
			
			todos		
				
			Array ( [opcion] => 1 
					[idRep] => 3 
					[parametros] => 
						pedido@txt_pedido
						~sucursal@4
						~vendedor@5
						~clientes@0|1|2
						~fechaDel@01/07/2014
						~fechaAl@02/07/2014
						~estatusPago@3
						~estatusPedido@5 )
			
		*/
		
	
			$arrParametros=explode('~',$parametros);
			
			$arryPedido			= explode('@',$arrParametros[0]);			
			$arrySucursal		= explode('@',$arrParametros[1]);
			$arryVendedor		= explode('@',$arrParametros[2]);
			$arryCliente		= explode('@',$arrParametros[3]);
			$arryFechaDel		= explode('@',$arrParametros[4]);
			$arryFechaAl		= explode('@',$arrParametros[5]);
			$arryEstatusPago	= explode('@',$arrParametros[6]);
			$arryEstatusPedido	= explode('@',$arrParametros[7]);
			
			$strPedido			= $arryPedido[1];
			$strSucursal		= $arrySucursal[1];
			$strVendedor		= $arryVendedor['1'];
			$strCliente			= $arryCliente['1'];
			$strFechaDel		= $arryFechaDel['1'];
			$strFechaAl			= $arryFechaAl['1'];
			$strEstatusPago		= $arryEstatusPago['1'];
			$strEstatusPedido	= $arryEstatusPedido['1'];
/*						
			$strPedido			= str_replace('|', ',', $strPedido);
			$strSucursal		= str_replace('|', ',', $strSucursal);
			$strVendedor		= str_replace('|', ',', $strVendedor);
*/
			$strCliente			= str_replace('|', ',', $strCliente);
			
/*			$strFechaDel		= str_replace('|', ',', $strFechaDel);
			$strFchaAl			= str_replace('|', ',', $strFechaAl);
			$strEstatusPago		= str_replace('|', ',', $strEstatusPago);
			$strEstatusPedido	= str_replace('|', ',', $strEstatusPedido);
*/
			  
			$strWhere="";
			
			if($strPedido != '')		
			{
				$strWhere.= " AND ad_pedidos.id_pedido LIKE '%".$strPedido."%'";			 
			}
			elseif($strCliente != 0 or $strCliente != '0')	
			{
				$strWhere.= " AND ad_pedidos.id_cliente in (".$strCliente.")";	 
			}else
			{
				if($strSucursal > 0 and $strVendedor > 0)		
				{
					$strWhere.= " AND (ad_pedidos.id_sucursal_alta = ".$strSucursal
							   ." OR ad_pedidos.id_vendedor = ".$strVendedor.")"; 	 
				}
				if($strSucursal > 0 )		
				{
					$strWhere.= " AND ad_pedidos.id_sucursal_alta = ".$strSucursal; 	 
				}
				
				if($strVendedor > 0)		
				{
					$strWhere.= " AND ad_pedidos.id_vendedor = ".$strVendedor; 	 
				}				
				
				if($strFechaDel != '')	 {$strWhere.= " AND ad_pedidos.fecha_alta >= '".$strFechaDel."'"; }
				if($strFechaAl != '')	 {$strWhere.= " AND ad_pedidos.fecha_alta <= '".$strFechaAl."'";	 }
				if($strEstatusPago > 0)	 {$strWhere.= " AND ad_pedidos.id_estatus_pago_pedido = ".$strEstatusPago;}
				if($strEstatusPedido > 0){$strWhere.= " AND ad_pedidos.id_estatus_pedido = ".$strEstatusPedido;}
			}

//			echo " <h1>No hay registros suficientes...</h1>";
//			die();
			  
			$strSQL = "
				 	SELECT 
						ad_pedidos.id_control_pedido, 
						ad_pedidos.id_pedido as Pedido,
						ad_pedidos.id_sucursal_alta,
						ad_sucursales.nombre as Sucursal, 
						ad_pedidos.id_vendedor,
						CONCAT(na_vendedores.nombre, ' ', 
									 na_vendedores.apellido_paterno,' ',
									 na_vendedores.apellido_materno) as Vendedor,
						ad_pedidos.fecha_alta as Alta,
						ad_pedidos.id_cliente,
						CONCAT(na_clientes.nombre, ' ',
							   na_clientes.apellido_paterno,' ', 
							   na_clientes.apellido_materno) as Cliente,
						ad_pedidos.id_estatus_pago_pedido,
						ad_pedidos_estatus.nombre as 'Estatus Pago',
						ad_pedidos.id_estatus_pedido,
						ad_pedidos_estatus.nombre as 'Estatus Pedido'
					from ad_pedidos
					LEFT JOIN ad_sucursales 
						   on ad_pedidos.id_sucursal_alta = ad_sucursales.id_sucursal 
					LEFT JOIN na_vendedores 
						   on ad_pedidos.id_vendedor = na_vendedores.id_vendedor 
					LEFT JOIN na_clientes 
						   on ad_pedidos.id_cliente = na_clientes.id_cliente
					LEFT JOIN ad_pedidos_estatus 
						   on ad_pedidos.id_estatus_pago_pedido= ad_pedidos_estatus.id_estatus_pago_pedido
					LEFT JOIN ad_pedidos_estatus 
						   on ad_pedidos.id_estatus_pedido = ad_pedidos_estatus.id_estatus_pedido
					where na_clientes.activo=1  $strWhere ";
					
					
		
		break;
		
		
		case 4:		       
		
			$arrParametros=explode('~',$parametros);
			
			$arryFamilia = explode('@',$arrParametros[0]);
			$arryTipo = explode('@',$arrParametros[1]);
			$arryModelo = explode('@',$arrParametros[2]);
			$arryCaracteristica = explode('@',$arrParametros[3]);
			$arryProductos = explode('@',$arrParametros[4]);
			$arryAlmacenes = explode('@',$arrParametros[5]);
			
			$strFamilia = $arryFamilia[1];
			$strTipo = $arryTipo[1];
			$strModelo=$arryModelo['1'];
			$strCaracteristica=$arryCaracteristica['1'];
			$strProductos=$arryProductos['1'];
			$strAlmacen=$arryAlmacenes['1'];
			
			$strFamilia = str_replace('|', ',', $strFamilia);
			$strTipo = str_replace('|', ',', $strTipo);
			$strModelo = str_replace('|', ',', $strModelo);
			$strCaracteristica = str_replace('|', ',', $strCaracteristica);
			$strProductos = str_replace('|', ',', $strProductos);
			$strAlmacen = str_replace('|', ',', $strAlmacen);

			  
			$strWhere="";
			if($strFamilia <> '0,0')
			{
				$strWhere.= " AND na_familias_productos.id_familia_producto in (".$strFamilia.")";
			}
			if($strTipo <> '0,0')
			{
				$strWhere.= " AND na_tipos_productos.id_tipo_producto in (".$strTipo.")";
			}
			if($strModelo <> '0,0')
			{
				$strWhere.= " AND na_modelos_productos.id_modelo_producto in (".$strModelo.")";
			}
			if($strCaracteristica <> '0,0')
			{
				$strWhere.= " AND na_caracteristicas_productos.id_caracteristica_producto in (".$strCaracteristica.")";
			}
			if($strProductos <> '0')
			{
				$strWhere.= " AND na_productos.id_producto in (".$strProductos.")";
			}
			if($strAlmacen <> '0')
			{
				$strWhere.= " AND ad_almacenes.id_almacen in (".$strAlmacen.")";
			}
			  
			  
			$strSQL = "	SELECT na_familias_productos.nombre as nombre_familia,
						 sku ,
						 na_productos.nombre as nombre_producto ,
						 na_tipos_productos.nombre as tipo_producto,
						 na_modelos_productos.nombre as modelo_nombre,
						 na_caracteristicas_productos.nombre as caracteristicas_nombre,
						 ad_almacenes.nombre as nombre_almacen,
						 if(sum(signo * cantidad) is null,0,sum(signo * cantidad)) as existencia 
						 FROM na_movimientos_almacen_detalle
					left join na_movimientos_almacen on na_movimientos_almacen_detalle.id_control_movimiento=na_movimientos_almacen.id_control_movimiento
					left join ad_almacenes on na_movimientos_almacen.id_almacen=ad_almacenes.id_almacen
					left join na_productos on na_productos.id_producto=na_movimientos_almacen_detalle.id_producto
					LEFT JOIN na_familias_productos on na_productos.id_familia_producto = na_familias_productos.id_familia_producto
					LEFT JOIN na_tipos_productos  on na_productos.id_tipo_producto = na_tipos_productos.id_tipo_producto
					LEFT JOIN na_modelos_productos  on na_productos.id_modelo_producto = na_modelos_productos.id_modelo_producto
					LEFT JOIN na_caracteristicas_productos  on na_productos.id_caracteristica_producto = na_caracteristicas_productos.id_caracteristica_producto
					LEFT JOIN na_lotes  on na_movimientos_almacen_detalle.id_lote = na_lotes.id_lote
					WHERE na_movimientos_almacen.no_modificable =1 ".$strWhere. " 
					GROUP BY na_movimientos_almacen_detalle.id_producto,na_movimientos_almacen.id_almacen
					ORDER BY ad_almacenes.nombre, na_familias_productos.nombre, na_tipos_productos.nombre,na_modelos_productos.nombre, na_caracteristicas_productos.nombre ,na_productos.nombre
										
					";
					
					

		break;	
		
		
		//-----------------------------------
		//-----------------------------------
			case 5:		       
				
			
				
			$arrParametros=explode('~',$parametros);
			
			$arrySucursal = explode('@',$arrParametros[0]);
			$arrylistaPrecios = explode('@',$arrParametros[1]);
			
			
			$strSucursal = $arrySucursal[1];
			$strlistaPrecios = $arrylistaPrecios[1];
			
			
			$strSucursal = str_replace('|', ',', $strSucursal);
			$strlistaPrecios = str_replace('|', ',', $strlistaPrecios);
			
			$strQuery="SELECT nombre,factor FROM ad_sucursales WHERE id_sucursal in (".$strSucursal.") order by id_sucursal DESC limit 1";
			$arrREsp2=valBuscador($strQuery);
			$sucursalNombre= utf8_encode( $arrREsp2[0]);
			$factor= utf8_encode( $arrREsp2[1]);
			
			//excepcion para la lista de precios de etiquetas
			if($strlistaPrecios=='0,99999999')
			{
				$strSQL = "	select na_familias_productos.nombre as nombre_familia,
							 sku ,
							 na_productos.nombre as nombre_producto ,
							 na_tipos_productos.nombre as tipo_producto,
							 na_modelos_productos.nombre as modelo_nombre,
							 na_caracteristicas_productos.nombre as caracteristicas_nombre,
							0 as porcentaje,
							precio_lista,
				 format(TRUNCATE(ROUND(precio_lista*".$factor.",2),0),2) as precio
				 FROM na_productos
						LEFT JOIN na_familias_productos on na_productos.id_familia_producto = na_familias_productos.id_familia_producto
						LEFT JOIN na_tipos_productos  on na_productos.id_tipo_producto = na_tipos_productos.id_tipo_producto
						LEFT JOIN na_modelos_productos  on na_productos.id_modelo_producto = na_modelos_productos.id_modelo_producto
						LEFT JOIN na_caracteristicas_productos  on na_productos.id_caracteristica_producto = na_caracteristicas_productos.id_caracteristica_producto
			  ORDER BY  na_familias_productos.nombre, na_tipos_productos.nombre,na_modelos_productos.nombre, na_caracteristicas_productos.nombre ,na_productos.nombre ";
			
				// format(ROUND(precio_lista*".$factor.",2),2)as precio
				$listaPreciosNombre= "ETIQUETAS";
			}
			else
			{
			  
				$strWhere="";
				/*if($strSucursal <> '0,0')
				{
					$strWhere.= " AND na_familias_productos.id_familia_producto in (".$strSucursal.")";
				}*/
				if($strlistaPrecios <> '0,0')
				{
					$strWhere.= " AND na_listas_detalle_productos.id_lista_precios in (".$strlistaPrecios.")";
				}
					
					
			 
				$strSQL = "	select na_familias_productos.nombre as nombre_familia,
							 sku ,
							 na_productos.nombre as nombre_producto ,
							 na_tipos_productos.nombre as tipo_producto,
							 na_modelos_productos.nombre as modelo_nombre,
							 na_caracteristicas_productos.nombre as caracteristicas_nombre,
				 porcentaje,precio_lista,
				 format(if(ad_lista_precios.id_lista_precios=1,precio_lista, precio_lista + (precio_lista*if( porcentaje is null,0,porcentaje)) /100),2) as precio
				 FROM na_productos
						LEFT JOIN na_familias_productos on na_productos.id_familia_producto = na_familias_productos.id_familia_producto
						LEFT JOIN na_tipos_productos  on na_productos.id_tipo_producto = na_tipos_productos.id_tipo_producto
						LEFT JOIN na_modelos_productos  on na_productos.id_modelo_producto = na_modelos_productos.id_modelo_producto
						LEFT JOIN na_caracteristicas_productos  on na_productos.id_caracteristica_producto = na_caracteristicas_productos.id_caracteristica_producto
			  left join na_listas_detalle_productos on na_listas_detalle_productos.id_producto=na_productos.id_producto $strWhere
			  left join ad_lista_precios on ad_lista_precios.id_lista_precios=na_listas_detalle_productos.id_lista_precios
			 ORDER BY  na_familias_productos.nombre, na_tipos_productos.nombre,na_modelos_productos.nombre, na_caracteristicas_productos.nombre ,na_productos.nombre ";
						
					$strQuery="SELECT nombre FROM ad_lista_precios WHERE id_lista_precios in (".$strlistaPrecios.") order by id_lista_precios DESC limit 1";
					$arrREsp=valBuscador($strQuery);
					
					$listaPreciosNombre= utf8_encode( $arrREsp[0]);
			}
			
			
		
				
		/*echo $strSQL;
		die();*/
		break;
		
		case 8:
			
			/*
			 * 1.- Genera consulta
			 *	1.1.- consultas por Token-Fecha-Hora
			 * 2.- pinta Consulta
			 *
			 */
			
			$arrParametros=explode('~',$parametros);
			
			$arrySucursal = explode('@',$arrParametros[0]);
			$arryFechaDel = explode('@',$arrParametros[1]);
			$arryFechaAl  = explode('@',$arrParametros[2]);
			$arryVersion  = explode('@',$arrParametros[3]);
			$arryFormaPago= explode('@',$arrParametros[4]);
			
			$strSucursal = $arrySucursal [1] ;
			$strFechaDel = $arryFechaDel [1] ;
			$strFechaAl	 = $arryFechaAl	 [1] ;
			$strVersion	 = $arryVersion	 [1] ;
			$strFormaPago= $arryFormaPago[1] ;
			  
			$strWhere="";
			$strWhereFechaPedido="";
			$strWhereFechaIngreso="";
			$strWhereFechaEgreso="";

			if($strSucursal > 0)
			{
				$strWhere.= " AND ad_sucursales.id_sucursal =".$strSucursal;
			}
			$vFechaAl = "";
			if($strFechaDel != '' and $strFechaAl != '')
			{ 
				$strFechaDelMySQL = explode('/',$strFechaDel); 
				$vFechaDel=$strFechaDelMySQL[2]."-".$strFechaDelMySQL[1]."-".$strFechaDelMySQL[0];
				$strFechaAlMySQL = explode('/',$strFechaAl); 
				$vFechaAl=$strFechaAlMySQL[2]."-".$strFechaAlMySQL[1]."-".$strFechaAlMySQL[0];

				$strWhereFechaPedido.= " AND na_pedidos_detalle_pagos.fecha >='".$vFechaDel."'";
				$strWhereFechaPedido.= " AND na_pedidos_detalle_pagos.fecha <='".$vFechaAl."'";
				
				$strWhereFechaIngreso.= " AND ad_ingresos_caja_chica.fecha_ingreso >='".$vFechaDel."'";
				$strWhereFechaIngreso.= " AND ad_ingresos_caja_chica.fecha_ingreso <='".$vFechaAl."'";
				
				$strWhereFechaEgreso.= " AND ad_egresos_caja_chica.fecha >='".$vFechaDel."'";
				$strWhereFechaEgreso.= " AND ad_egresos_caja_chica.fecha <='".$vFechaAl."'";
			}
						
			if($strVersion == 1)
			{				
				$strSQL= " 
				 SELECT id_ingreso as ID,  
						ad_ingresos_caja_chica.id_sucursal, 
						ad_sucursales.nombre as 'Sucursal', 
						DATE_FORMAT(fecha_ingreso, '%d/%m/%Y') as Fecha, 
						'ENTRADA' as 'Tipo Movimiento', 
						ad_ingresos_caja_chica.id_tipo_ingreso, 
						ad_tipos_ingreso_caja_chica.descripcion as 'Forma',
						'' as id_terminal_bancaria, 
						'' as Terminal,
						'' as Documento,
						'SI' as Confirmado, 
						CONCAT('ING-',id_ingreso) as 'Doc Rel',
						monto as monto_confirmado, '' as 'Monto No Confirmado'
			 	   FROM ad_ingresos_caja_chica
			  LEFT JOIN ad_sucursales 
					 on ad_ingresos_caja_chica.id_sucursal=ad_sucursales.id_sucursal
			  LEFT JOIN ad_tipos_ingreso_caja_chica 
					 on ad_ingresos_caja_chica.id_tipo_ingreso=ad_tipos_ingreso_caja_chica.id_tipo_ingreso
				WHERE 1".$strWhere.$strWhereFechaIngreso."
	
				UNION ALL 
	
		 		 SELECT id_egreso as ID,  
						ad_egresos_caja_chica.id_sucursal, 
						ad_sucursales.nombre as 'Sucursal', 
						DATE_FORMAT(fecha, '%d/%m/%Y')as Fecha, 
						'SALIDA' as 'Tipo Movimiento', 
						ad_egresos_caja_chica.id_tipo_egreso, 
						ad_tipos_egreso_caja_chica.descripcion as 'Forma',
						'' as id_terminal_bancaria, 
						'' as Terminal,
						'' as Documento, 
						'SI' as Confirmado, 
						CONCAT('EGR-',id_egreso) as 'Doc Rel',
						total as monto_confirmado, '' as 'Monto No Confirmado'
				   FROM ad_egresos_caja_chica
			  LEFT JOIN ad_sucursales 
					 on ad_egresos_caja_chica.id_sucursal=ad_sucursales.id_sucursal
			  LEFT JOIN ad_tipos_egreso_caja_chica 
					 on ad_egresos_caja_chica.id_tipo_egreso = ad_tipos_egreso_caja_chica.id_tipo_egreso
			WHERE 1".$strWhere.$strWhereFechaEgreso."
			
		UNION ALL
	
		 		SELECT id_pedido_detalle_pago as ID,  
						ad_pedidos.id_sucursal_alta, 
						ad_sucursales.nombre as 'Sucursal',
						DATE_FORMAT(fecha, '%d/%m/%Y') as Fecha, 
						'ENTRADA' as 'Tipo Movimiento', 
						ad_pedidos_detalle_pagos.id_forma_pago, 
						ad_formas_pago.nombre as 'Forma', 
						ad_pedidos_detalle_pagos.id_terminal_bancaria, 
						if((na_terminales_bancarias.nombre) is null,'',na_terminales_bancarias.nombre) as Terminal, 
						ad_pedidos_detalle_pagos.numero_documento as Documento,
						sys_si_no.nombre, CONCAT('PED-', id_pedido) as 'Doc Rel',
	
						if((SELECT monto FROM na_pedidos_detalle_pagos WHERE id_pedido_detalle_pago=ID AND (confirmado=1 or confirmado=3 )) is null,'',
							 (SELECT monto FROM na_pedidos_detalle_pagos WHERE id_pedido_detalle_pago=ID AND (confirmado=1 or confirmado=3 ))) as monto_confirmado,					
						if((SELECT monto FROM na_pedidos_detalle_pagos WHERE id_pedido_detalle_pago=ID AND confirmado=2 ) is null,'',
							 (SELECT monto FROM na_pedidos_detalle_pagos WHERE id_pedido_detalle_pago=ID AND confirmado=2 )) as 'Monto No Confirmado'
	
				   FROM ad_pedidos_detalle_pagos
			  LEFT JOIN ad_pedidos 
					 on ad_pedidos_detalle_pagos.id_control_pedido=ad_pedidos.id_control_pedido
			  LEFT JOIN ad_sucursales 
					 on ad_pedidos.id_sucursal_alta=ad_sucursales.id_sucursal
			  LEFT JOIN ad_formas_pago 
					 on ad_pedidos_detalle_pagos.id_forma_pago=na_formas_pago.id_forma_pago
			  LEFT JOIN ad_terminales_bancarias
					 on ad_pedidos_detalle_pagos.id_terminal_bancaria=na_terminales_bancarias.id_terminal_bancaria
			  LEFT JOIN sys_si_no
					 on na_pedidos_detalle_pagos.confirmado=sys_si_no.id_si_no
			WHERE na_pedidos_detalle_pagos.id_forma_pago <> 1 AND na_pedidos_detalle_pagos.activoDetPagos = 1 ".$strWhere.$strWhereFechaPedido."
			
		ORDER BY Fecha ASC, Sucursal ";
		
		
			if($opcion==1) 
			{
				// REPORTE RESUMIDO HTML
				include("corteCajaDetallado_html.php"); 
			} 
			else
			{
				// 	REPORTE RESUMIDO XLS												
				//----------------------------------------------------------------------//
				header("Pragma: public");
				header("Expires: 0");
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");		
				header("Content-type: atachment/vnd.ms-excel");		
				header("Content-Disposition: atachment; filename=\"Reporte Detallado de Corte de Caja.xls\";");		
				header("Content-transfer-encoding: binary\n");
				//----------------------------------------------------------------------//									
				include("corteCajaDetallado_xls.php");	
			}
			die();
			
			
				
				
				
			}
			else
			{
				// 2 = Resumida  (No lleva Forma de pago)							
				
				$sql="SELECT ad_pedidos_detalle_pagos.id_control_pedido,
							 ad_sucursales.id_sucursal,
							 ad_sucursales.nombre,
							 ad_pedidos_detalle_pagos.fecha,
							 SUM(monto) as monto
						FROM ad_pedidos_detalle_pagos
				   LEFT JOIN ad_pedidos 
						  on ad_pedidos_detalle_pagos.id_control_pedido = ad_pedidos.id_control_pedido
				   LEFT JOIN ad_sucursales 
						  on ad_pedidos.id_sucursal_alta = ad_sucursales.id_sucursal
					   WHERE 1 ".$strWhere." 
					GROUP BY ad_sucursales.id_sucursal, ad_pedidos_detalle_pagos.fecha
					ORDER BY ad_sucursales.nombre ASC, ad_pedidos_detalle_pagos.fecha DESC;";
			/*echo $sql;
		die();*/
				$token = time();
	
				//echo $token;	echo "<br />";	
				$result = mysql_query($sql) or die(mysql_error());
				
				while ($aResultado = mysql_fetch_array($result)){ 
	
					$sucursal	= $aResultado['nombre'];
					$idSucursal = $aResultado['id_sucursal'];	
					$fecha		= $aResultado['fecha'];
					$monto		= $aResultado['monto'];
					
					$sqlI="INSERT INTO na_reporte_auxiliar
									   (token,
										id_sucursal,
										fecha,
										total)															
								VALUES (".$token.",
										".$idSucursal.",
										'".$fecha."',
										".$monto.")";
					mysql_query($sqlI) or die(mysql_error());
					
					$sqlM= " SELECT	columna, 								
									id_tipo_pago, 
									id_banco,
									na_formas_pago.autorizacion_credito_cobranza, 
									na_formas_pago.requiere_registro_terminal,
									confirmado								
							   FROM na_reporte_auxiliar_mapeo				
						  LEFT JOIN na_formas_pago 
								 on na_reporte_auxiliar_mapeo.id_tipo_pago = na_formas_pago.id_forma_pago ";						 
		 
					$resultM = mysql_query($sqlM) or die(mysql_error());				
					while ($aResultadoM = mysql_fetch_row($resultM))
					{								
						$montoU="0.0";
						
						$columna=$aResultadoM[0];
						$idTipoPago=$aResultadoM[1];
						$idBanco=$aResultadoM[2];
						$reqAutorizacionCC=$aResultadoM[3];
						$reqTerminal=$aResultadoM[4];
						$confirmado=$aResultadoM[5];
	
						if($idTipoPago >= 1)   // VALE DE PRODUCTO
						{
							$sql3="SELECT ad_pedidos.id_sucursal_alta,
										  if(SUM(monto) is null,0,SUM(monto) )  as monto
									 FROM na_pedidos_detalle_pagos 
								LEFT JOIN ad_pedidos 
									   on na_pedidos_detalle_pagos.id_control_pedido = ad_pedidos.id_control_pedido										 
									WHERE id_forma_pago=$idTipoPago
									  AND fecha='".$fecha."'
									  AND id_sucursal_alta=$idSucursal";								  
									  
							if($reqAutorizacionCC==1)
							{
								if		($confirmado == 0) { $sql3.= " AND confirmado=2 "				   ;}
								elseif	($confirmado == 1) { $sql3.= " AND (confirmado=1 OR confirmado=3) ";}
							}
																  
							$result3=mysql_query($sql3) or die(mysql_error());	
							$aResultado3 = mysql_fetch_array($result3)	;
													
							$montoU=$aResultado3['monto'];						 
							
							$sql4 = "UPDATE na_reporte_auxiliar
										SET $columna = $columna + $montoU
									  WHERE id_sucursal=$idSucursal 
										AND fecha='".$fecha."'
										AND token=$token ";
	
							mysql_query($sql4) or die(mysql_error());	
						}	
						
						if($idTipoPago == 0 and $idBanco >= 1)   // BANCOS
						{
							$sql3 = "SELECT ad_pedidos.id_sucursal_alta,
											if(SUM(monto) is null,0,SUM(monto) )  as monto
									   FROM na_pedidos_detalle_pagos 
								  LEFT JOIN ad_pedidos 
										 on na_pedidos_detalle_pagos.id_control_pedido = ad_pedidos.id_control_pedido
								  LEFT JOIN na_terminales_bancarias 
										 on na_pedidos_detalle_pagos.id_terminal_bancaria = na_terminales_bancarias.id_terminal_bancaria		
								  LEFT JOIN na_bancos 
										 on na_terminales_bancarias.id_banco = na_bancos.id_banco
									  WHERE (id_forma_pago=2
										 OR id_forma_pago=3
										 OR id_forma_pago=8)
										AND na_bancos.id_banco=$idBanco
										AND fecha='".$fecha."'
										AND id_sucursal_alta=$idSucursal";
														
							if		($confirmado == 0) { $sql3.= " AND confirmado=2"; 					}
							elseif	($confirmado == 1) { $sql3.= " AND (confirmado=1 OR confirmado=3) ";}
							
							$result3=mysql_query($sql3) or die(mysql_error());	
							$aResultado3 = mysql_fetch_array($result3)	;
							
							$montoU=$aResultado3['monto'];	
							
							$sql4 = "UPDATE na_reporte_auxiliar
											SET $columna = $columna + $montoU							   
										  WHERE id_sucursal=$idSucursal 									  	
											AND fecha='".$fecha."'
											AND token=$token ";
											
							mysql_query($sql4) or die(mysql_error());	
						}					
					}
				}	
				if($opcion==1) 
				{
					// REPORTE RESUMIDO HTML
					include("corteCajaResumido_html.php"); 
				} 
				else
				{
					// 	REPORTE RESUMIDO XLS												
					//----------------------------------------------------------------------//
					header("Pragma: public");
					header("Expires: 0");
					header("Cache-Control: must-revalidate, post-check=0, pre-check=0");		
					header("Content-type: atachment/vnd.ms-excel");		
					header("Content-Disposition: atachment; filename=\"Reporte Resumido de Corte de Caja.xls\";");		
					header("Content-transfer-encoding: binary\n");
					//----------------------------------------------------------------------//									
					include("corteCajaResumido_xls.php");	
				}
				die();
			}		
						
			break;
		
		case 9:	
		
			$arrParametros=explode('~',$parametros);
			
			$arryFamilia = explode('@',$arrParametros[0]);
			$arryTipo = explode('@',$arrParametros[1]);
			$arryModelo = explode('@',$arrParametros[2]);
			$arryCaracteristica = explode('@',$arrParametros[3]);
			$arryProductos = explode('@',$arrParametros[4]);
			
			
			$strFamilia = $arryFamilia[1];
			$strTipo = $arryTipo[1];
			$strModelo=$arryModelo['1'];
			$strCaracteristica=$arryCaracteristica['1'];
			$strProductos=$arryProductos['1'];
			
			
			$strFamilia = str_replace('|', ',', $strFamilia);
			$strTipo = str_replace('|', ',', $strTipo);
			$strModelo = str_replace('|', ',', $strModelo);
			$strCaracteristica = str_replace('|', ',', $strCaracteristica);
			$strProductos = str_replace('|', ',', $strProductos);
			
			  
			$strWhere="";
			if($strFamilia <> '0,0')
			{
				$strWhere.= " AND na_familias_productos.id_familia_producto in (".$strFamilia.")";
			}
			if($strTipo <> '0,0')
			{
				$strWhere.= " AND na_tipos_productos.id_tipo_producto in (".$strTipo.")";
			}
			if($strModelo <> '0,0')
			{
				$strWhere.= " AND na_modelos_productos.id_modelo_producto in (".$strModelo.")";
			}
			if($strCaracteristica <> '0,0')
			{
				$strWhere.= " AND na_caracteristicas_productos.id_caracteristica_producto in (".$strCaracteristica.")";
			}
			if($strProductos <> '0')
			{
				$strWhere.= " AND na_productos.id_producto in (".$strProductos.")";
			}
			
			
		
		
			
			//and na_movimientos_almacen_detalle.id_producto
			
			$strSQL="SELECT nombre_familia, sku, nombre_producto, tipo_producto, modelo_nombre, caracteristicas_nombre, existencia, apartado,  (existencia- apartado) as disponible, proxima_fecha_entrega FROM
					(
						SELECT  na_familias_productos.nombre as nombre_familia,
							 sku ,
							 na_productos.nombre as nombre_producto ,
							 na_tipos_productos.nombre as tipo_producto,
							 na_modelos_productos.nombre as modelo_nombre,
							 na_caracteristicas_productos.nombre as caracteristicas_nombre,
							 if(sum(signo * cantidad) is null,0,sum(signo * cantidad)) as existencia,
							(SELECT if(SUM(cantidad) is null,0,SUM(cantidad)) as apartado  FROM na_movimientos_apartados where id_estatus_apartado=1  and id_producto=na_movimientos_almacen_detalle.id_producto) as apartado,
							(SELECT if(MIN(fecha_entrega) is null,'',MIN(fecha_entrega)) as apartado  FROM na_movimientos_apartados where id_estatus_apartado=1  and id_producto=na_movimientos_almacen_detalle.id_producto) as proxima_fecha_entrega
						FROM na_productos
						    left join na_movimientos_almacen_detalle on na_productos.id_producto=na_movimientos_almacen_detalle.id_producto
							left join na_movimientos_almacen on na_movimientos_almacen_detalle.id_control_movimiento=na_movimientos_almacen.id_control_movimiento and  na_movimientos_almacen.no_modificable =1
							left join ad_almacenes on na_movimientos_almacen.id_almacen=ad_almacenes.id_almacen
							
							LEFT JOIN na_familias_productos on na_productos.id_familia_producto = na_familias_productos.id_familia_producto
							LEFT JOIN na_tipos_productos  on na_productos.id_tipo_producto = na_tipos_productos.id_tipo_producto
							LEFT JOIN na_modelos_productos  on na_productos.id_modelo_producto = na_modelos_productos.id_modelo_producto
							LEFT JOIN na_caracteristicas_productos  on na_productos.id_caracteristica_producto = na_caracteristicas_productos.id_caracteristica_producto
						WHERE na_productos.activo=1  ". $strWhere ."
						GROUP BY na_productos.id_producto) as datos
					    ORDER BY  nombre_familia ,tipo_producto,modelo_nombre, caracteristicas_nombre ,nombre_producto ";
				
			//echo $strSQL;	
		break;
		
		case 10:
			
			$arrParametros=explode('~',$parametros);
			
			$arryFamilia = explode('@',$arrParametros[0]);
			$arryTipo = explode('@',$arrParametros[1]);
			$arryModelo = explode('@',$arrParametros[2]);
			$arryCaracteristica = explode('@',$arrParametros[3]);
			$arryProductos = explode('@',$arrParametros[4]);
			
			
			$strFamilia = $arryFamilia[1];
			$strTipo = $arryTipo[1];
			$strModelo=$arryModelo['1'];
			$strCaracteristica=$arryCaracteristica['1'];
			$strProductos=$arryProductos['1'];
			
			
			$strFamilia = str_replace('|', ',', $strFamilia);
			$strTipo = str_replace('|', ',', $strTipo);
			$strModelo = str_replace('|', ',', $strModelo);
			$strCaracteristica = str_replace('|', ',', $strCaracteristica);
			$strProductos = str_replace('|', ',', $strProductos);
			
			  
			$strWhere="";
			if($strFamilia <> '0,0')
			{
				$strWhere.= " AND na_familias_productos.id_familia_producto in (".$strFamilia.")";
			}
			if($strTipo <> '0,0')
			{
				$strWhere.= " AND na_tipos_productos.id_tipo_producto in (".$strTipo.")";
			}
			if($strModelo <> '0,0')
			{
				$strWhere.= " AND na_modelos_productos.id_modelo_producto in (".$strModelo.")";
			}
			if($strCaracteristica <> '0,0')
			{
				$strWhere.= " AND na_caracteristicas_productos.id_caracteristica_producto in (".$strCaracteristica.")";
			}
			if($strProductos <> '0')
			{
				$strWhere.= " AND na_productos.id_producto in (".$strProductos.")";
			}
			
			
			
			$strSQL="
			SELECT distinct id_apartado, id_control_pedido, id_pedido_detalle, id_pedido, id_producto, cantidad, observaciones, fecha_apartado, fecha_entrega, id_usuario_aparto, sucursal, vendedor, cliente, usuario_aparto, estatus_pago, estatus_pedido, familia_nombre,tipo_producto, modelo_nombre, caracteristicas_nombre, sku, nombre_producto, existencia
		,cantidad, existencia-cantidad as disponible, CONCAT('http://72.167.44.133/sysdev/nasser/code/general/encabezados.php?t=YWRfcGVkaWRvcw==&amp;k=',id_control_pedido,'&amp;op=2&amp;v=1&amp;tabla=&amp;cadP1=MDM0ZG5CbGZqRjhibkJsZmpGOGJYQmxmakY4WlhCbGZqRjhhWDE=&amp;cadP2=MDM0QmxmakY4WjNCbGZqRjhjR1JtZmpCOGRtVnlYM2RsWW40dzE=') as url	FROM
			(
			
			SELECT id_apartado,
				  na_movimientos_apartados.id_control_pedido,
				  id_pedido_detalle,
				  na_movimientos_apartados.id_pedido,
				  na_movimientos_apartados.id_producto,
				  cantidad,
				  na_movimientos_apartados.observaciones,
				  DATE_FORMAT(fecha_apartado, '%d/%m/%Y') as fecha_apartado, 
				  DATE_FORMAT(fecha_entrega, '%d/%m/%Y') as fecha_entrega, 
				  id_usuario_aparto,
				ad_sucursales.nombre as sucursal,
				CONCAT(na_vendedores.nombre, ' ', na_vendedores.apellido_paterno,' ',na_vendedores.apellido_materno) as vendedor,
				CONCAT(na_clientes.nombre, ' ',na_clientes.apellido_paterno,' ',na_clientes.apellido_materno) as cliente,
				CONCAT( sys_usuarios.nombres,' ', sys_usuarios.apellido_paterno,' ', sys_usuarios.apellido_materno) as usuario_aparto,
				ad_pedidos_estatus.nombre as estatus_pago,
				ad_pedidos_estatus.nombre as estatus_pedido,
				na_familias_productos.nombre as familia_nombre,
				na_tipos_productos.nombre as tipo_producto,
				na_modelos_productos.nombre as modelo_nombre,
				na_caracteristicas_productos.nombre as caracteristicas_nombre,
				sku ,
				na_productos.nombre as nombre_producto ,
				(SELECT if(sum(signo * cantidad) is null,0,sum(signo * cantidad)) as existencia
					FROM na_movimientos_almacen_detalle
					left join na_movimientos_almacen on na_movimientos_almacen_detalle.id_control_movimiento=na_movimientos_almacen.id_control_movimiento
					left join ad_almacenes on na_movimientos_almacen.id_almacen=ad_almacenes.id_almacen
					WHERE na_movimientos_almacen.no_modificable =1 and na_movimientos_almacen_detalle.id_producto= na_movimientos_apartados.id_producto) as existencia
				FROM na_movimientos_apartados
				left join na_productos on na_productos.id_producto=na_movimientos_apartados.id_producto
				LEFT JOIN na_familias_productos on na_productos.id_familia_producto = na_familias_productos.id_familia_producto
				LEFT JOIN na_tipos_productos  on na_productos.id_tipo_producto = na_tipos_productos.id_tipo_producto
				LEFT JOIN na_modelos_productos  on na_productos.id_modelo_producto = na_modelos_productos.id_modelo_producto
				LEFT JOIN na_caracteristicas_productos  on na_productos.id_caracteristica_producto = na_caracteristicas_productos.id_caracteristica_producto
				left join ad_pedidos on ad_pedidos.id_control_pedido=ad_pedidos.id_control_pedido
				LEFT JOIN ad_sucursales  on ad_pedidos.id_sucursal_alta = ad_sucursales.id_sucursal
				LEFT JOIN ad_vendedores  on ad_pedidos.id_vendedor = na_vendedores.id_vendedor
				LEFT JOIN ad_clientes on ad_pedidos.id_cliente = na_clientes.id_cliente
				LEFT JOIN ad_pedidos_estatus  on ad_pedidos.id_estatus_pago_pedido= ad_pedidos_estatus.id_estatus_pago_pedido
				LEFT JOIN ad_pedidos_estatus  on ad_pedidos.id_estatus_pedido = ad_pedidos_estatus.id_estatus_pedido
				left join sys_usuarios on sys_usuarios.id_usuario=id_usuario_aparto
				 where id_estatus_apartado=1 ". $strWhere . ") as tabla WHERE existencia < cantidad ORDER BY fecha_entrega";
		
		//echo $strSQL;
		//---------------------------------
		//---------------------------------
		break;
		case 11:
			date_default_timezone_set('America/Mexico_City');
			$arrParametros=explode('~',$parametros);
			$arrySucursal = explode('@',$arrParametros[0]);
			$strSucursal = $arrySucursal[1];
			$strSucursal = str_replace('|', ',', $strSucursal);
			$strWhere="";
			
			if($strSucursal <> '0,0'){
					$strWhere .=" AND id_sucursal IN (".$strSucursal.")";
					}
			
			$sqlSuc = "SELECT id_sucursal FROM ad_sucursales WHERE id_sucursal <> 0" . $strWhere; //Obtenemos las sucursales de las cuales se generara el reporte
			$datosSuc = new consultarTabla($sqlSuc);
			$registrosSuc = $datosSuc -> obtenerRegistros();
			
			
			
			//Fecha actual e inicio de mes e inicio de dia
			$hoy = time();
			$inicio_dia = date('Y') . "-" . date('m') . "-" . date('d') . " 00:00:00";
			$dia_real = strtotime($inicio_dia);
			$inicio_mes = date('Y') . "-" . date('m') . "-" . "01 00:00:00";
			$mes_real = strtotime($inicio_mes);
			
		    $dia_fin = getMonthDays(date('m'), date('Y'));
			$fin_mes = date('Y') . "-" . date('m') . "-" . $dia_fin . " 23:59:59";
			$mes_final_real = strtotime($fin_mes);
			
			
			foreach($registrosSuc as $registros){ //Hacemos un recorrido por cada sucursal encontrada
					//Variables que se insertaran
					$sumaventasdia = 0;
					$sumaventasCanceladasDia = 0;
					$sumaventasMes = 0;
					$sumaCobVencida = 0;
					$sumaCobNoVencida = 0;
					$sumaBancariaHoy = 0;
					$sumaBancariaMes = 0;
					$sumaCajaChicaI = 0;
					$sumaCajaChicaM = 0;
					$sumaCajaChicaE = 0;
					$sumaCajaChicaEM = 0;
					$saldoCajaChica = 0;
					$suma_saldo_cxp = 0;
					$suma_pagados_cxp = 0;
					
					/********** VENTAS DEL DIA **********/
					$sqlVentas = "SELECT total FROM ad_pedidos WHERE (id_estatus_pedido <> 5 AND id_estatus_pedido <> 6) AND id_sucursal_alta = " . $registros -> id_sucursal . " AND (unix_timestamp(fecha_alta) BETWEEN '" . $dia_real . "' AND '" . $hoy . "')";
					
					$datosVentas = new consultarTabla($sqlVentas);
					$registrosVentas = $datosVentas -> obtenerRegistros();
					foreach($registrosVentas as $registrosV){
							$sumaventasdia += $registrosV -> total;
							}
							
					/********** VENTAS CANCELADAS DEL DIA**********/
					$sqlVentasC = "SELECT total FROM ad_pedidos WHERE (id_estatus_pedido = 5 OR id_estatus_pedido = 6) AND id_sucursal_alta = " . $registros -> id_sucursal . " AND (unix_timestamp(fecha_alta) BETWEEN '" . $dia_real . "' AND '" . $hoy . "')";
					
					$datosVentasC = new consultarTabla($sqlVentasC);
					$registrosVentasC = $datosVentasC -> obtenerRegistros();
					foreach($registrosVentasC as $registrosC){
							$sumaventasCanceladasDia += $registrosC -> total;
							}
							
					/********** VENTAS DEL MES **********/
					$sqlVentasMes = "SELECT total FROM ad_pedidos WHERE (id_estatus_pedido <> 5 AND id_estatus_pedido <> 6) AND id_sucursal_alta = " . $registros -> id_sucursal . " AND (unix_timestamp(fecha_alta) BETWEEN '" . $mes_real . "' AND '" . $hoy . "')";
					
					$datosVentasMes = new consultarTabla($sqlVentasMes);
					$registrosVentasMes = $datosVentasMes -> obtenerRegistros();
					foreach($registrosVentasMes as $registrosM){
							$sumaventasMes += $registrosM -> total;
							}
					
					/********** COBRANZA VENCIDA **********/
					$sqlCobVencida ="SELECT ad_pedidos.total AS total_pedido, SUM(na_pedidos_detalle_pagos.monto) AS total_pagos
									FROM na_pedidos_detalle_pagos
									LEFT JOIN ad_pedidos ON na_pedidos_detalle_pagos.id_control_pedido = ad_pedidos.id_control_pedido
									WHERE ad_pedidos.id_sucursal_alta = " . $registros -> id_sucursal . " AND unix_timestamp(ad_pedidos.fecha_limite_pago) <= " . $hoy . "
									AND (id_estatus_pedido <> 5 AND id_estatus_pedido <> 6) AND na_pedidos_detalle_pagos.confirmado <> 2
									GROUP BY na_pedidos_detalle_pagos.id_pedido_detalle_pago HAVING total_pagos < total_pedido";
					$datosCobVencida = new consultarTabla($sqlCobVencida);
					$registrosCobVencida = $datosCobVencida -> obtenerRegistros();
					foreach($registrosCobVencida as $registrosCV){
							$sumaCobVencida += $registrosCV -> total_pedido - $registrosCV -> total_pagos;
							}
							
					/********** COBRANZA NO VENCIDA **********/
					$sqlCobNoVencida ="SELECT ad_pedidos.total AS total_pedido, SUM(na_pedidos_detalle_pagos.monto) AS total_pagos
									FROM na_pedidos_detalle_pagos
									LEFT JOIN ad_pedidos ON na_pedidos_detalle_pagos.id_control_pedido = ad_pedidos.id_control_pedido
									WHERE ad_pedidos.id_sucursal_alta = " . $registros -> id_sucursal . " AND unix_timestamp(ad_pedidos.fecha_limite_pago) > " . $hoy . "
									AND (id_estatus_pedido <> 5 AND id_estatus_pedido <> 6) AND na_pedidos_detalle_pagos.confirmado <> 2
									GROUP BY na_pedidos_detalle_pagos.id_pedido_detalle_pago HAVING total_pagos < total_pedido";
					$datosCobNoVencida = new consultarTabla($sqlCobNoVencida);
					$registrosCobNoVencida = $datosCobNoVencida -> obtenerRegistros();
					foreach($registrosCobNoVencida as $registrosCNV){
							$sumaCobNoVencida += $registrosCNV -> total_pedido - $registrosCNV -> total_pagos;
							}
							
					/********** CUENTAS BANCARIAS HOY **********/
					$sqlCuentaBancaria = "SELECT SUM(na_pedidos_detalle_pagos.monto) AS total_pagos 
										FROM na_pedidos_detalle_pagos
										LEFT JOIN ad_pedidos ON na_pedidos_detalle_pagos.id_control_pedido = ad_pedidos.id_control_pedido
										LEFT JOIN na_depositos_bancarios ON na_pedidos_detalle_pagos.id_deposito_bancario = na_depositos_bancarios.id_deposito_bancario
										WHERE ad_pedidos.id_sucursal_alta = " . $registros -> id_sucursal . " AND (na_pedidos_detalle_pagos.id_deposito_bancario <> '' OR na_pedidos_detalle_pagos.id_deposito_bancario <> 0 OR na_pedidos_detalle_pagos.id_deposito_bancario IS NOT NULL) AND (unix_timestamp(na_depositos_bancarios.fecha) BETWEEN '" . $dia_real . "' AND '" . $hoy . "')";
					$datosCuentaBancaria = new consultarTabla($sqlCuentaBancaria);
					$registrosCuentaBancaria = $datosCuentaBancaria -> obtenerRegistros();
					foreach($registrosCuentaBancaria as $registrosCB){
							$sumaBancariaHoy += $registrosCB -> total_pagos;
							}		
					
					/********** CUENTAS BANCARIAS MES **********/	
					$sqlCuentaBancariaM = "SELECT SUM(na_pedidos_detalle_pagos.monto) AS total_pagos 
										FROM na_pedidos_detalle_pagos
										LEFT JOIN ad_pedidos ON na_pedidos_detalle_pagos.id_control_pedido = ad_pedidos.id_control_pedido
										LEFT JOIN na_depositos_bancarios ON na_pedidos_detalle_pagos.id_deposito_bancario = na_depositos_bancarios.id_deposito_bancario
										WHERE ad_pedidos.id_sucursal_alta = " . $registros -> id_sucursal . " AND (na_pedidos_detalle_pagos.id_deposito_bancario <> '' OR na_pedidos_detalle_pagos.id_deposito_bancario <> 0 OR na_pedidos_detalle_pagos.id_deposito_bancario IS NOT NULL) AND (unix_timestamp(na_depositos_bancarios.fecha) BETWEEN '" . $mes_real . "' AND '" . $hoy . "')";
					$datosCuentaBancariaM = new consultarTabla($sqlCuentaBancariaM);
					$registrosCuentaBancariaM = $datosCuentaBancariaM -> obtenerRegistros();
					foreach($registrosCuentaBancariaM as $registrosCBM){
							$sumaBancariaMes += $registrosCBM -> total_pagos;
							}
					
					/********** INGRESOS A CAJA CHICA HOY **********/	
					$sqlCajaChica = "SELECT monto FROM ad_ingresos_caja_chica WHERE id_sucursal = " . $registros -> id_sucursal . " AND (unix_timestamp(fecha_ingreso) BETWEEN '" . $dia_real . "' AND '" . $hoy . "')";
					$datosCICH = new consultarTabla($sqlCajaChica);
					$registrosCICH = $datosCICH -> obtenerRegistros();
					foreach($registrosCICH as $registrosCICH){
							$sumaCajaChicaI += $registrosCICH -> monto;
							}
							
					/********** INGRESOS A CAJA CHICA MES **********/	
					$sqlCajaChicaM = "SELECT monto FROM ad_ingresos_caja_chica WHERE id_sucursal = " . $registros -> id_sucursal . " AND (unix_timestamp(fecha_ingreso) BETWEEN '" . $mes_real . "' AND '" . $hoy . "')";
					$datosCICHM = new consultarTabla($sqlCajaChicaM);
					$registrosCICHM = $datosCICHM -> obtenerRegistros();
					foreach($registrosCICHM as $registrosCICHM){
							$sumaCajaChicaM += $registrosCICHM -> monto;
							}
							
					/********** EGRESOS A CAJA CHICA DIA **********/	
					$sqlCajaChicaE = "SELECT total FROM ad_egresos_caja_chica WHERE id_sucursal = " . $registros -> id_sucursal . " AND (unix_timestamp(fecha) BETWEEN '" . $dia_real . "' AND '" . $hoy . "')";
					$datosCICHE = new consultarTabla($sqlCajaChicaE);
					$registrosCICHE = $datosCICHE -> obtenerRegistros();
					foreach($registrosCICHE as $registrosCICHE){
							$sumaCajaChicaE += $registrosCICHE -> total;
							}
					
					/********** EGRESOS A CAJA CHICA MES **********/	
					$sqlCajaChicaEM = "SELECT total FROM ad_egresos_caja_chica WHERE id_sucursal = " . $registros -> id_sucursal . " AND (unix_timestamp(fecha) BETWEEN '" . $mes_real . "' AND '" . $hoy . "')";
					$datosCICHEM = new consultarTabla($sqlCajaChicaEM);
					$registrosCICHEM = $datosCICHEM -> obtenerRegistros();
					foreach($registrosCICHEM as $registrosCICHEM){
							$sumaCajaChicaEM += $registrosCICHEM -> total;
							}
					
					/********** Saldo Caja Chica **********/
					$saldoCajaChica = $sumaCajaChicaM - $sumaCajaChicaEM;
					
					/********** CXP pendientes de pago **********/
					$sqlcxpPendientes = "SELECT (SUM(na_cuentas_por_pagar_pagos_detalle.monto) + SUM(na_cuentas_por_pagar_pagos_egresos_detalle.monto_egreso)) AS suma,
											ad_cuentas_por_pagar_operadora.total AS total_cxp, 
											(ad_cuentas_por_pagar_operadora.total - (SUM(na_cuentas_por_pagar_pagos_detalle.monto) + SUM(na_cuentas_por_pagar_pagos_egresos_detalle.monto_egreso))) AS suma_saldos
											FROM ad_cuentas_por_pagar_operadora
											LEFT JOIN na_cuentas_por_pagar_pagos_detalle ON na_cuentas_por_pagar_pagos_detalle.id_cuenta_por_pagar = ad_cuentas_por_pagar_operadora.id_cuenta_por_pagar
											LEFT JOIN na_cuentas_por_pagar_pagos_egresos_detalle ON na_cuentas_por_pagar_pagos_egresos_detalle.id_cuenta_por_pagar = ad_cuentas_por_pagar_operadora.id_cuenta_por_pagar
											WHERE ad_cuentas_por_pagar_operadora.id_sucursal = " . $registros -> id_sucursal . " AND unix_timestamp(ad_cuentas_por_pagar_operadora.fecha_vencimiento) <= " . $mes_final_real . " 
											GROUP BY ad_cuentas_por_pagar_operadora.id_cuenta_por_pagar HAVING total_cxp > suma";
					$datosCXPPendientes = new consultarTabla($sqlcxpPendientes);
					$registrosCXPPendientes = $datosCXPPendientes -> obtenerRegistros();
					foreach($registrosCXPPendientes as $pendientes){
							$suma_saldo_cxp += $pendientes -> suma_saldos;
							}
							
					/********** CXP pagos del mes **********/
					$sqlcxpPagos = "SELECT IF((unix_timestamp(na_cuentas_por_pagar_pagos_detalle.fecha_pago) BETWEEN " . $mes_real . " AND " . $hoy . "), SUM(na_cuentas_por_pagar_pagos_detalle.monto), 0) 
					+ IF((unix_timestamp(na_cuentas_por_pagar_pagos_egresos_detalle.fecha_egreso) BETWEEN " . $mes_real . " AND " . $hoy . "), SUM(na_cuentas_por_pagar_pagos_egresos_detalle.monto_egreso), 0) AS suma_total
					FROM ad_cuentas_por_pagar_operadora
					LEFT JOIN na_cuentas_por_pagar_pagos_detalle ON na_cuentas_por_pagar_pagos_detalle.id_cuenta_por_pagar = ad_cuentas_por_pagar_operadora.id_cuenta_por_pagar
					LEFT JOIN na_cuentas_por_pagar_pagos_egresos_detalle ON na_cuentas_por_pagar_pagos_egresos_detalle.id_cuenta_por_pagar = ad_cuentas_por_pagar_operadora.id_cuenta_por_pagar
					WHERE ad_cuentas_por_pagar_operadora.id_sucursal = " . $registros -> id_sucursal;
					$datosCXPPagos = new consultarTabla($sqlcxpPagos);
					$registrosCXPPagos = $datosCXPPagos -> obtenerRegistros();
					foreach($registrosCXPPagos as $pagos){
							$suma_pagados_cxp += $pagos -> suma_total;
							}
											
											
										
					/************Insertamos un registro en la tabla auxiliar por cada sucursal*********************/
					$tabla_auxiliar['id_sucursal'] = $registros -> id_sucursal;
					$tabla_auxiliar['fecha_hora'] = $hoy;
					$tabla_auxiliar['total_ventas_dia'] = $sumaventasdia;
					$tabla_auxiliar['ventas_canceladas_dia'] = $sumaventasCanceladasDia;
					$tabla_auxiliar['total_ventas_mes'] = $sumaventasMes;
					$tabla_auxiliar['total_cobranza_vencida'] = $sumaCobVencida;
					$tabla_auxiliar['total_cobranza_no_vencida'] = $sumaCobNoVencida;
					$tabla_auxiliar['total_cuenta_bancaria_hoy'] = $sumaBancariaHoy;
					$tabla_auxiliar['total_cuenta_bancaria_mes'] = $sumaBancariaMes;
					$tabla_auxiliar['total_ingresos_cajachica_hoy'] = $sumaCajaChicaI;
					$tabla_auxiliar['total_ingresos_cajachica_mes'] = $sumaCajaChicaM;
					$tabla_auxiliar['total_egresos_cajachica_hoy'] = $sumaCajaChicaE;
					$tabla_auxiliar['total_egresos_cajachica_mes'] = $sumaCajaChicaEM;
					$tabla_auxiliar['saldo_caja_chica'] = $saldoCajaChica;
					$tabla_auxiliar['cxp_pagos_pendientes'] = $suma_saldo_cxp;
					$tabla_auxiliar['cxp_pagos_mes'] = $suma_pagados_cxp;
					
					accionesMysql($tabla_auxiliar, 'na_auxiliares_ventas', 'Inserta');
					}
		/***********Iniciamos la consulta a la tabla que pintara el repotes XML********************/
		$strWhere2 = "";
			if($strSucursal <> '0,0'){
					$strWhere2 .=" AND na_auxiliares_ventas.id_sucursal IN (" . $strSucursal . ")";
					}
			$strSQL = "SELECT ad_sucursales.nombre AS sucursal, CONCAT('$', FORMAT(total_ventas_dia, 2)) AS ventas_dia, CONCAT('$', FORMAT(ventas_canceladas_dia, 2)) AS ventas_canceladas, CONCAT('$', FORMAT(total_ventas_mes, 2)) AS ventas_mes, CONCAT('$', FORMAT(total_cobranza_vencida, 2)) AS cobranza_vencida, CONCAT('$', FORMAT(total_cobranza_no_vencida, 2)) AS cobranza_no_vencida, CONCAT('$', FORMAT(total_cuenta_bancaria_hoy, 2)) AS cuenta_bancaria_hoy, CONCAT('$', FORMAT(total_cuenta_bancaria_mes, 2)) AS cuenta_bancaria_mes, CONCAT('$',FORMAT(total_ingresos_cajachica_hoy, 2)) AS ingreso_caja_chica_hoy, CONCAT('$',FORMAT(total_ingresos_cajachica_mes, 2)) AS ingreso_caja_chica_mes, CONCAT('$',FORMAT(total_egresos_cajachica_hoy, 2)) AS egreso_caja_chica_hoy, CONCAT('$',FORMAT(total_egresos_cajachica_mes, 2)) AS egreso_caja_chica_mes, CONCAT('$',FORMAT(saldo_caja_chica, 2)) AS saldo_caja_chica, CONCAT('$',FORMAT(cxp_pagos_pendientes, 2)) AS pendientes_cxp, CONCAT('$',FORMAT(cxp_pagos_mes, 2)) AS pagos_cxp
					FROM na_auxiliares_ventas 
					LEFT JOIN ad_sucursales ON ad_sucursales.id_sucursal = na_auxiliares_ventas.id_sucursal
					WHERE na_auxiliares_ventas.fecha_hora = " . $hoy . $strWhere2 . " ORDER BY sucursal";
		break;
			
		default:
				echo "No existe algún reporte con este ID. Cierre la ventana e intente nuevamente.";
				die();
		break;
		
	}

    //consulta del Reporte
	$reporte = "rep".$idRep.".xml";
   
	if(!mysql_query($strSQL)) die("Error en:<br><i>$strSQL</i><br><br>Descripcion:<br><b>".mysql_error());

$titulo = (isset($titulo))?$titulo:"Reporte_generico";

$titulo=strtoupper($titulo);





	function insert_date(){

		return "Generado el : ".date('d/m/Y   H:i:s' );

	}

	function insert_Titulo(){

		return $listaPreciosNombre;

	}

	

	function insert_where(){
		return ' -'.  $filtro;

	}

	

	$oRpt = new PHPReportMaker();

/*
	if($filtro == 'Ninguno')
	{

	if($fechaCorte != ""){
		if($fechaCorte != '' || $fechaCorte != '-1')
			$filtro="Del: ".$fechaCorte;
		else
			$filtro="Del: ".$arropc[0];
			
		if(strlen($fecha2) >= 7)
			$filtro.=" Al: ".$fecha2;
		else
			$filtro.=" Al: ".$arropc[1];
	 }		
	 
	 if($fechadel != ""){
	 
	    $filtro="Del: ".$fechadel;
	 	$filtro.=" Al: ".$fechaal;
	 }	
	}

	//die($filtro);


	

	$parametros = array("filtro"=>$filtro, "titulo"=>$titulo, "rango_fechas"=>$rango_fechas);
*/
	//$parametros = array("filtro"=>$filtro, "titulo"=>$titulo, "rango_fechas"=>$rango_fechas);
	$parametros = array("nombreLista"=>$listaPreciosNombre,"sucursal"=>$sucursalNombre);

	$oRpt->setParameters($parametros);

		
	//$matrizDatos = array();



	

	$oRpt->setConnection($dbhost);

	$oRpt->setDatabase($dbname);

	$oRpt->setDatabaseInterface($dbvendor);

	$oRpt->setUser($dbuser);

	

	//abajo

	$oRpt->setPassword($dbpassword);

	//arriba

	//$oRpt->setPassword("PTpg81xi");


	$oRpt->setSQL(iconv("ISO-8859-1","UTF-8",$strSQL));


	$oRpt->setXML($reporte);

	//require_once("../inc/datosBaseReportes.php");


	if($opcion==2)

		ob_start();


	//$oRpt->setPageSize(20);
/////No jala	
	$oRpt->run();

	//echo "Opcion: $opcion";

	if($opcion==2){//genera excel

		//session_cache_limiter('none'); //*Use before session_start()

		//session_start();

		$out = ob_get_clean();

//-------------------------------------------------------------//
		header("Pragma: public");

        header("Expires: 0");

        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		
		header("Content-type: atachment/vnd.ms-excel");
		
		header("Content-Disposition: atachment; filename=\"$titulo.xls\";");
		
		header("Content-transfer-encoding: binary\n");
//----------------------------------------------------------------------//
		echo $out;

	}

	

	

	die();

?>