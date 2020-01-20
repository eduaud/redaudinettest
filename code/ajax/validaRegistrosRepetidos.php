<?php

php_track_vars;
//CONECCION Y PERMISOS A LA BASE DE DATOS
include("../../conect.php");

$mensaje = "";
switch($_REQUEST["accion"])
{
	case 1:	//valida estado	
		$sql = "SELECT id_estado, nombre 
				FROM of_estados 
				WHERE id_pais =". $_REQUEST["id_pais"] . " AND UPPER(nombre) = '" . $_REQUEST["nombre"] . "'";		  		
		$mensaje = "el estado de";				
		break;
	case 2:	//valida ciudad	
		$sql = "SELECT id_ciudad, nombre 
				FROM of_ciudades 
				WHERE id_pais =". $_REQUEST["id_pais"] . " AND UPPER(nombre) = '" . $_REQUEST["nombre"] . "' AND id_estado = " . $_REQUEST["id_estado"];		  		
		$mensaje = "la ciudad de";				
		break;
	case 3:	//valida proveedor
		$sql = "SELECT id_proveedor, razon_social 
				FROM of_proveedores 
				WHERE UPPER(razon_social) = '". $_REQUEST["razon_social"] . "' AND UPPER(rfc) = '" . $_REQUEST["rfc"] . "'";		  		
		$mensaje = "el proveedor";				
		break;
	case 4:	//valida pAIS
		$sql = "SELECT id_pais, nombre 
				FROM of_paises 
				WHERE UPPER(nombre) = '". $_REQUEST["nombre"] . "'";		  		
		$mensaje = "el país";				
		break;
	case 5:	//valida franquicia
		$sql = "SELECT id_franquicia, nombre 
				FROM of_franquicias 
				WHERE UPPER(nombre) = '". $_REQUEST["nombre"] . "' AND id_book = " . $_REQUEST["id_book"] . " AND id_plaza = " . $_REQUEST["id_plaza"];		  		
		$mensaje = "la franquicia";				
		break;
	case 6:	//tasas iva
		$sql = "SELECT id_tasa_iva, porcentaje_iva 
				FROM of_tasas_iva 
				WHERE porcentaje_iva = '". trim($_REQUEST["porcentaje_iva"]) . "'";		  		
		$mensaje = "la tasa de IVA";				
		break;
	case 7:	//estatus de orden de compra
		$sql = "SELECT id_estatus_oc, nombre_descripcion 
				FROM of_estatus_orden_compra
				WHERE UPPER(nombre_descripcion) = '". trim($_REQUEST["nombre"]) . "'";		  		
		$mensaje = "el estatus ";				
		break;
	case 8:	//NEGOCIO
		$sql = "SELECT id_negocio, nombre 
				FROM of_negocios
				WHERE UPPER(nombre) = '". trim($_REQUEST["nombre"]) . "' AND id_book = " . $_REQUEST["id_book"];		  		
		$mensaje = "el negocio ";				
		break;
	case 9:	//cuentas bancrias operadora
		$sql = "SELECT id_cuenta_de_banco, numero_de_cuenta 
				FROM of_cuentas_bancarias
				WHERE numero_de_cuenta = '". $_REQUEST["numero_de_cuenta"] . "' AND UPPER(banco) = '" . $_REQUEST["banco"] . "' " .
					" AND UPPER(sucursal) = '" . $_REQUEST["sucursal"] . "' AND clabe = '" . $_REQUEST["clabe"] . "'";		  		
		$mensaje = "el negocio ";				
		break;
	case 10:	//plaza
		$sql = "SELECT id_plaza, nombre 
				FROM of_plaza
				WHERE UPPER(nombre) = '". $_REQUEST["nombre"] . "' AND id_estado = '" . $_REQUEST["id_estado"] . "'";		  		
		$mensaje = "la plaza ";				
		break;
	case 11:	//tipos de proveedor
		$sql = "SELECT id_tipo_proveedor, nombre_descripcion 
				FROM of_tipos_de_proveedor
				WHERE UPPER(nombre_descripcion) = '". $_REQUEST["nombre"] . "'";		  		
		$mensaje = "el tipo de proveedor ";				
		break;
	case 12:	//prospectos usuario
		$sql = "SELECT id_prospecto, razon_social 
				FROM fr_prospectos
				WHERE UPPER(razon_social) = '". $_REQUEST["razon_social"] . "' AND UPPER(rfc) = '" . $_REQUEST["rfc"] . "'";		  		
		$mensaje = "el prospecto de usuario ";				
		break;
	case 13:	//OPERADORA
		$sql = "SELECT id_operadora, razon_social 
				FROM of_operadora
				WHERE UPPER(razon_social) = '". $_REQUEST["razon_social"] . "' AND UPPER(rfc) = '" . $_REQUEST["rfc"] . "'";		  		
		$mensaje = "el prospecto de usuario ";				
		break;
	case 14:	//BOOK
		$sql = "SELECT id_book, nombre 
				FROM of_books
				WHERE UPPER(nombre) = '". $_REQUEST["nombre"] . "'";		  		
		$mensaje = "el book ";				
		break;
	case 15:	//PRODUCTOS INVENTARIOS POR PROYECTO
		$sql = "SELECT id_pr_fran, nombre 
				FROM of_productos_inventario_proyecto
				WHERE UPPER(nombre) = '". $_REQUEST["nombre"] . "' AND id_book = " . $_REQUEST["id_book"];		  		
		$mensaje = "el producto ";				
		break;
	case 16:	//PRODUCTOS/SERVICIOS
		$sql = "SELECT id_pr_serv, nombre 
				FROM of_productos_servicios
				WHERE UPPER(nombre) = '". $_REQUEST["nombre"] . "' AND id_book = " . $_REQUEST["id_book"] . 
					" AND id_negocio = " . $_REQUEST["id_negocio"];		  		
		$mensaje = "el producto con el mismo book y negocio ";				
		break;
	case 17:	//ESTADO CIVIL
		$sql = "SELECT id_pr_serv, descripcion 
				FROM of_productos_inventario_proyecto
				WHERE UPPER(descripcion) = '". $_REQUEST["descripcion"] . "'";		  		
		$mensaje = "el estado civil ";				
		break;
	case 18:	//ACTIVIDADES PROYECTO
		$sql = "SELECT id_actividad, descripcion 
				FROM of_actividades_proyecto
				WHERE UPPER(descripcion) = '". $_REQUEST["descripcion"] . "'";		  		
		$mensaje = "la actividad de proyecto ";				
		break;
	case 19:	//GASTO MENSUAL
		$sql = "SELECT id_gasto_mensual, descripcion 
				FROM of_gastos_mensuales
				WHERE UPPER(descripcion) = '". $_REQUEST["descripcion"] . "'";		  		
		$mensaje = "el gasto mensual ";				
		break;
	case 20:	//INGRESO MENSUAL
		$sql = "SELECT id_ingresos_mensuales, descripcion 
				FROM of_ingresos_mensuales
				WHERE UPPER(descripcion) = '". $_REQUEST["descripcion"] . "'";		  		
		$mensaje = "el ingreso mensual ";				
		break;
	case 21:	//NIVEL DE ESTUDIOS
		$sql = "SELECT id_nivel_de_estudios, descripcion 
				FROM of_nivel_estudios
				WHERE UPPER(descripcion) = '". $_REQUEST["descripcion"] . "'";		  		
		$mensaje = "el nivel de estudios ";				
		break;
	case 22:	//NUMERO DE HIJOS
		$sql = "SELECT id_numero_de_hijos, descripcion 
				FROM of_numero_hijos
				WHERE UPPER(descripcion) = '". $_REQUEST["descripcion"] . "'";		  		
		$mensaje = "el numero de hijos ";				
		break;
	case 23:	//PRODUCTOS/SERVICIOS
		$sql = "SELECT id_subsubtipo_gasto, nombre_descripcion 
				FROM of_subsubtipo_gasto
				WHERE UPPER(nombre) = '". $_REQUEST["nombre_descripcion"] . "' AND id_subtipo_gasto = " . $_REQUEST["id_subtipo_gasto"];		  		
		$mensaje = "el subsubtipo ";				
		break;
	case 24:	//PRODUCTOS/SERVICIOS
		$sql = "SELECT id_subtipo_gasto, nombre_descripcion 
				FROM of_subtipo_gasto
				WHERE UPPER(nombre) = '". $_REQUEST["nombre_descripcion"] . "' AND id_tipo_gasto = " . $_REQUEST["id_tipo_gasto"];		  		
		$mensaje = "el subtipo ";				
		break;
	case 25:	//TIPO DE CASA
		$sql = "SELECT id_tipo_casa, descripcion 
				FROM of_tipo_casa
				WHERE UPPER(descripcion) = '". $_REQUEST["descripcion"] . "'";		  		
		$mensaje = "el tipo de casa ";				
		break;
	case 26:	//TIPO DE CUENTA POR COBRAR
		$sql = "SELECT id_tipo_de_cxc, descripcion 
				FROM of_tipo_cuentas_por_cobrar
				WHERE UPPER(descripcion) = '". $_REQUEST["descripcion"] . "'";		  		
		$mensaje = "el tipo de cuenta por cobrar ";				
		break;
	case 27:	//
		$sql = "SELECT id_tipo_gasto, nombre_descripcion 
				FROM of_tipo_gasto
				WHERE UPPER(nombre) = '". $_REQUEST["nombre_descripcion"] . "' AND id_negocio = " . $_REQUEST["id_negocio"];		  		
		$mensaje = "el tipo de gasto ";				
		break;
	case 28:	//TIPO DE PAGO
		$sql = "SELECT id_tipo_de_pago, descripcion 
				FROM of_tipo_pago
				WHERE UPPER(descripcion) = '". $_REQUEST["descripcion"] . "'";		  		
		$mensaje = "el tipo de pago ";				
		break;
	case 29:	//TIPO DE COBRO
		$sql = "SELECT id_tipo_de_cobro, descripcion 
				FROM of_tipos_de_cobro
				WHERE UPPER(descripcion) = '". $_REQUEST["descripcion"] . "'";		  		
		$mensaje = "el tipo de cobro ";				
		break;
	case 30:	//VALORACION DE PROVEEDOR
		$sql = "SELECT id_valoracion_proveedor, descripcion 
				FROM of_valoracion_proveedor
				WHERE UPPER(descripcion) = '". $_REQUEST["descripcion"] . "'";		  		
		$mensaje = "la valoración de proveedor ";				
		break;
	case 31:	//GIROS
		$sql = "SELECT id_giro, nombre 
				FROM of_giro
				WHERE UPPER(nombre) = '". $_REQUEST["nombre"] . "' AND id_categoria_giro = " . $_REQUEST["id_categoria_giro"];		  		
		$mensaje = "el giro ";				
		break;
	case 32:	//COMO CONOCISTE THE BOOKS
		$sql = "SELECT id_conociste_the_books, descripcion 
				FROM of_como_conociste_the_books	
				WHERE UPPER(descripcion) = '". $_REQUEST["descripcion"];		  		
		$mensaje = "la descripción ";				
		break;
	case 33:	//CLASIFICACION DE GIROS
		$sql = "SELECT id_categoria_giro, nombre 
				FROM of_categorias_giro	
				WHERE UPPER(nombre) = '". $_REQUEST["nombre"];		  		
		$mensaje = "la clasificación de giro ";				
		break;
	case 34:	//PEDIDOS
		$sql = "SELECT id_control_pedido, id_pedido
				FROM ad_pedidos	
				WHERE id_pedido = ". $_REQUEST["id_pedido"];		  		
		$mensaje = "el pedido";				
		break;
}	

$res = mysql_query($sql) or die("Error en:\n$sql\n\nDescripción:\n".mysql_error());	  
		
if(mysql_num_rows($res) > 0)
{
	$row = mysql_fetch_row($res);
	echo htmlspecialchars("exito|Ya Existe " . $mensaje . " " . $row[1] . " en el catálogo ....Verifique.");
}
else{
   echo htmlspecialchars("No existe");
}
?>