<?php
	switch($reporte){
/***************************************INVENTARIO*******************************************/
//Catalogo de Clientes por Sucursal
		case 1:
			$arrayTablas = array('anderp_clientes'=>'Clientes', 'sys_sucursales'=>'Sucursales');
		break;
//Catálogo de Productos 
		case 2:
			$arrayTablas = array('anderp_productos'=>'Productos','anderp_productos_tipos'=>'Tipos','anderp_presentaciones'=>'Presentaciones');
		break;
//Tipos de Producto
		case 3:
			$arrayTablas = array('anderp_productos_tipos'=>'Tipos');
		break;
//Presentaciones de Producto
		case 4:
			$arrayTablas = array('anderp_presentaciones'=>'Presentaciones');
		break;
//Notas de Venta por Sucursal
		case 5:
			$arrayTablas = array('anderp_notas_venta'=>'Notas de Venta','anderp_clientes'=>'Clientes','sys_sucursales'=>'Sucursales');
		break;
//Facturas por Sucursal y Cliente
		case 6:
			$arrayTablas = array('anderp_facturas'=>'Facturas', 'anderp_clientes'=>'Clientes','sys_sucursales'=>'Sucursales','anderp_rutas'=>'Rutas','anderp_vendedores'=>'Vendedores');
		break;
//Reporte de Ventas por Cliente
		case 7:
			$arrayTablas = array('anderp_notas_venta'=>'Notas de Venta','anderp_clientes'=>'Clientes','sys_sucursales'=>'Sucursales');
		break;
//Reporte Ventas de Productos por Cliente
		case 8:
			$arrayTablas = array('anderp_notas_venta'=>'Notas de Venta','anderp_productos' => 'Productos','anderp_clientes'=>'Clientes','sys_sucursales'=>'Sucursal');
		break;
 //Reporte de Productos Vendidos con un precio minimo
		case 9:
			$arrayTablas = array('anderp_productos' => 'Productos','anderp_notas_venta'=>'Notas de Venta','sys_sucursales'=>'Sucursales', 'anderp_rutas'=>'Rutas','anderp_vendedores' => 'Vendedores');
		break;
		case 10;//Reporte de Notas de Venta Detalle
			$arrayTablas = array('anderp_productos' => 'Productos','anderp_notas_venta'=>'Notas de Venta','sys_sucursales'=>'Sucursales', 'anderp_rutas'=>'Rutas','anderp_vendedores' => 'Vendedores');
		break;
		case 11:
			//$arrayTablas = array('novalaser_clientes'=>'Clientes','novalaser_vendedores'=>'Doctores','novalaser_servicios'=>'Servicios');
		break;
		case 12:
		 $arrayTablas = array('anderp_cuentas_por_cobrar'=>'Cuentas por Cobrar', 'anderp_clientes'=>'Clientes','sys_sucursales'=>'Sucursales','anderp_rutas'=>'Rutas','anderp_vendedores'=>'Vendedores');
		break;	
		case 13:
			$arrayTablas = array('anderp_cuentas_por_cobrar'=>'Cuentas por Cobrar', 'anderp_clientes'=>'Clientes','sys_sucursales'=>'Sucursales','anderp_rutas'=>'Rutas','anderp_vendedores'=>'Vendedores');
		break;
		case 14:
				$arrayTablas = array('anderp_cuentas_por_cobrar'=>'Cuentas por Cobrar', 'anderp_clientes'=>'Clientes','sys_sucursales'=>'Sucursales','anderp_rutas'=>'Rutas','anderp_vendedores'=>'Vendedores');
		break;
		case 15:
			//$arrayTablas = array('novalaser_notas_venta'=>'Notas de Venta', 'novalaser_vendedores'=>'Vendedores', 'novalaser_servicios'=>'Servicios', 'novalaser_servicios_tipos'=>'Tipos de servicio');
		break;
		case 16:
			$arrayTablas = array();
		break;
		case 20:
			$arrayTablas = array('novalaser_clientes' => 'Clientes', 'novalaser_notas_devolucion' => 'Notas de devolución', 'novalaser_notas_venta' => 'Notas de venta', 'novalaser_sucursales' => 'Sucursales', 'novalaser_vendedores' => 'Vendedores');
		break;	
		case 21:
			$arrayTablas = array('novalaser_clientes' => 'Clientes', 'novalaser_notas_devolucion' => 'Notas de devolución', 'novalaser_notas_venta' => 'Notas de venta', 'novalaser_sucursales' => 'Sucursales', 'novalaser_vendedores' => 'Vendedores');
		break;	
		case 22:
		//	$arrayTablas = array('novalaser_clientes' => 'Clientes', 'novalaser_notas_venta' => 'Notas de venta', 'novalaser_servicios' => 'Servicios', 'novalaser_servicios_tipos' => 'Servicios tipos', 'novalaser_sucursales' => 'Sucursales', 'novalaser_vendedores' => 'Vendedores');
		break;	
		case 25:
			$arrayTablas = array('novalaser_productos'=>'Productos','novalaser_clientes'=>'Clientes','novalaser_vendedores'=>'Doctoras','novalaser_servicios'=>'Servicios');	
		break;
		case 26:
			$arrayTablas = array('novalaser_vendedores'=>'Doctoras');	
		break;
		case 500;//Reporte de Notas de Venta Detalle
			$arrayTablas = array('anderp_productos' => 'Productos','anderp_notas_venta'=>'Notas de Venta','sys_sucursales'=>'Sucursales', 'anderp_rutas'=>'Rutas','anderp_vendedores' => 'Vendedores');
		break;
		
		/***************************************EXPORTACION DE ASISTENTES*******************************************/		
		default:
			$arrayTablas = array();
	}
?>