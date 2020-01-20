<?php

	extract($_GET);
	extract($_POST);
	$cambio = "";
//CONECCION Y PERMISOS A LA BASE DE DATOS
 
	include("../../conect.php");
	include("../general/funciones.php");
	
	//validamos si el usuario es Admin
	$es_admin=0;
	$sql="SELECT 1 FROM sys_usuarios_grupos WHERE id_usuario=".$_SESSION["USR"]->userid." AND id_grupo=1";
	$res=mysql_query($sql) or die("Error en:<br><i>$sql</i><br><br>Descripcion:<br><b>".mysql_error());
	$num=mysql_num_rows($res);
	mysql_free_result($res);
	if($num > 0)
		$es_admin=1;
	
//	$instrucciones = "Seleccione los filtros deseados para lanzar su reporte y de clic al bot&oacute;n 'Generar reporte' para generar el reporte en una ventana independiente o a 'Exportar a Excel' para enviar su reporte a un archivo de Excel.";
	$instrucciones = "A continuaci&oacute;n seleccione los filtros deseados y de clic a 'Generar reporte' para verlo en pantalla o bien a 'Exportar a Excel' para guardar el archivo como un libro de Microsoft Excel.";
	$carpetaRep = ROOTPATH.'/templates/reportes';
	
	$smarty->assign('raiz',ROOTURL.'modules/');
	$smarty->assign('rutaTPL',ROOTPATH.'/templates/');
	$smarty->assign('carpetaImagenes',ROOTURL.'imagenes/general/');
	$smarty->assign("contentheader","M&oacute;dulo de reportes");
	$smarty->assign("carpeta",$carpetaRep);

	$smarty->assign('instrucciones',$instrucciones);
	$smarty->assign('reporte',$reporte);
	
	$smarty->assign('accion','reportes');
	
// Primero vamos a hacer el arreglo de tablas relacionadas al reporte para pasarselo al template

		
//Buscaremos los campos por los cuales se ordenará el reporte
	
	$strConsulta="SELECT nombre FROM sys_menus where cod_tabla='".$rep."'";
	
	$arrDat=valBuscador($strConsulta);
	$titulo=$arrDat[0];
	//grabaBitacora($_SESSION["MGW"]->userid,14, 0, $titulo);
	
	$smarty->assign('reporte',$rep);
	
	$smarty->assign('titulo',$titulo);
	
	//*********************************************************************//
	if($rep==1 or $rep==4 or $rep==9 or $rep==10) 
	{
		
		$strSQL = "SELECT id_familia_producto, nombre FROM na_familias_productos order by nombre";		
		$rs = mysql_query($strSQL) or die("Error en consulta $strSQL\nDescripcion:".mysql_error());		  	
		$arrAuxId = array();
		$arrAuxDesc = array();			
		while($row = mysql_fetch_assoc($rs)){			
			$arrAuxId[] = $row['id_familia_producto'];
			$arrAuxDesc[] = $row['nombre'];			
		}		
		$smarty->assign('familia_id', $arrAuxId);
		$smarty->assign('familia_nombre',$arrAuxDesc);	
		
		//carga tambien los modelos y las caracteristicas que ya no dependen del tipo de producto
		
		$strSQL = "SELECT id_modelo_producto, nombre FROM na_modelos_productos WHERE activo=1 order by nombre ";		
		$rs = mysql_query($strSQL) or die("Error en consulta $strSQL\nDescripcion:".mysql_error());		  	
		$arrAuxId = array();
		$arrAuxDesc = array();			
		while($row = mysql_fetch_assoc($rs)){			
			$arrAuxId[] = $row['id_modelo_producto'];
			$arrAuxDesc[] = $row['nombre'];			
		}		
		$smarty->assign('modelo_id', $arrAuxId);
		$smarty->assign('modelo_nombre',$arrAuxDesc);	
		
		
		
		$strSQL = "SELECT id_caracteristica_producto, nombre FROM na_caracteristicas_productos WHERE activo=1 order by nombre ";		
		$rs = mysql_query($strSQL) or die("Error en consulta $strSQL\nDescripcion:".mysql_error());		  	
		$arrAuxId = array();
		$arrAuxDesc = array();			
		while($row = mysql_fetch_assoc($rs)){			
			$arrAuxId[] = $row['id_caracteristica_producto'];
			$arrAuxDesc[] = $row['nombre'];			
		}		
		$smarty->assign('caracteristica_id', $arrAuxId);
		$smarty->assign('caracteristica_nombre',$arrAuxDesc);	
		
		
		
		
		
	}
	
	if($rep==4) 
	{
		
		$strSQL = "SELECT id_almacen,nombre FROM ad_almacenes order by nombre";		
		$rs = mysql_query($strSQL) or die("Error en consulta $strSQL\nDescripcion:".mysql_error());		  	
		$arrAuxId = array();
		$arrAuxDesc = array();			
		while($row = mysql_fetch_assoc($rs)){			
			$arrAuxId[] = $row['id_almacen'];
			$arrAuxDesc[] = $row['nombre'];			
		}		
		$smarty->assign('almacenes_id', $arrAuxId);
		$smarty->assign('almacenes_nombre',$arrAuxDesc);
		
		
		
			
	}
	
	
	/**********************************************************/
	
	//*********************************************************************//
	if($rep==2 || $rep==5 ) 
	{
		$strSQL = "SELECT id_sucursal, nombre AS 'nombre' FROM na_sucursales WHERE id_sucursal<>0 order by nombre ";
		
		$rs = mysql_query($strSQL) or die("Error en consulta $strSQL\nDescripcion:".mysql_error());		  	

		$arrAuxId = array();
		$arrAuxDesc = array();
		
		while($row = mysql_fetch_assoc($rs)){
			
			$arrAuxId[] = $row['id_sucursal'];
			$arrAuxDesc[] = $row['nombre'];
			
		}
		
		$smarty->assign('sucursal_id', $arrAuxId);
		$smarty->assign('sucursal_nombre',$arrAuxDesc);
	}
	
	
	if( $rep==5 ) 
	{
		//listas de precios publica DATE_FORMAT(fecha_inicio, '%d/%m/%Y')
		$strSQL = "SELECT id_lista_precios ,CONCAT(nombre, if(es_lista_precio_publico=0,
CONCAT(' :: Vigencia del: ', DATE_FORMAT(fecha_inicio, '%d/%m/%Y'),' al: ', DATE_FORMAT(fecha_inicio, '%d/%m/%Y')),'')) vigencia  FROM ad_lista_precios where id_lista_precios=1";
		
		$rs = mysql_query($strSQL) or die("Error en consulta $strSQL\nDescripcion:".mysql_error());		  	

		$arrAuxId = array();
		$arrAuxDesc = array();
		
		while($row = mysql_fetch_assoc($rs)){
			
			$arrAuxId[] = $row['id_lista_precios'];
			$arrAuxDesc[] = $row['vigencia'];
			
		}
		
		$smarty->assign('listaPrecios_id', $arrAuxId);
		$smarty->assign('listaPrecios_nombre',$arrAuxDesc);
	}
	if($rep==3) 
	{
		
		/*
		 * Sucursales
		 */		
		$strSQL = "SELECT id_sucursal, nombre AS 'nombre' FROM na_sucursales order by nombre";		
		$rs = mysql_query($strSQL) or die("Error en consulta $strSQL\nDescripcion:".mysql_error());		  	
		$arrAuxId = array();
		$arrAuxDesc = array();			
		while($row = mysql_fetch_assoc($rs)){			
			$arrAuxId[] = $row['id_sucursal'];
			$arrAuxDesc[] = $row['nombre'];			
		}		
		$smarty->assign('sucursal_id', $arrAuxId);
		$smarty->assign('sucursal_nombre',$arrAuxDesc);	
		/*
		 * Vendedor
		 */
		$strSQL = "SELECT id_vendedor, CONCAT(nombre, ' ',  apellido_paterno,' ', apellido_materno) AS 'nc' FROM na_vendedores order by nc;";		
		$rs = mysql_query($strSQL) or die("Error en consulta $strSQL\nDescripcion:".mysql_error());		  	
		$arrAuxId = array();
		$arrAuxDesc = array();			
		while($row = mysql_fetch_assoc($rs)){			
			$arrAuxId[] = $row['id_vendedor'];
			$arrAuxDesc[] = $row['nc'];			
		}		
		$smarty->assign('vendedor_id', $arrAuxId);
		$smarty->assign('vendedor_nombre',$arrAuxDesc);
		/*
		 * Cliente
		 	
		$strSQL = "SELECT id_cliente, CONCAT(nombre, ' ',  apellido_paterno,' ', apellido_materno) AS 'nc' FROM na_clientes;";		
		$rs = mysql_query($strSQL) or die("Error en consulta $strSQL\nDescripcion:".mysql_error());		  	
		$arrAuxId = array();
		$arrAuxDesc = array();			
		while($row = mysql_fetch_assoc($rs)){			
			$arrAuxId[] = $row['id_cliente'];
			$arrAuxDesc[] = $row['nombre'];			
		}		
		$smarty->assign('cliente_id', $arrAuxId);
		$smarty->assign('cliente_nombre',$arrAuxDesc);	
		*/	
		
		/*
		 * Estatus Pago Pedido
		 */
		$strSQL = "SELECT id_estatus_pago_pedido, nombre AS 'nombre' FROM ad_pedidos_estatus order by nombre;";		
		$rs = mysql_query($strSQL) or die("Error en consulta $strSQL\nDescripcion:".mysql_error());	
		$arrAuxId = array();
		$arrAuxDesc = array();			
		while($row = mysql_fetch_assoc($rs)){			
			$arrAuxId[] = $row['id_estatus_pago_pedidos'];
			$arrAuxDesc[] = $row['nombre'];			
		}		
		$smarty->assign('estatus_pago_id', $arrAuxId);
		$smarty->assign('estatus_nombre',$arrAuxDesc);
		/*
		 * Estatus Pedido
		 */
		$strSQL = "SELECT id_estatus_pedido, nombre AS 'nombre' FROM ad_pedidos_estatus order by nombre";		
		$rs = mysql_query($strSQL) or die("Error en consulta $strSQL\nDescripcion:".mysql_error());		  	
		$arrAuxId = array();
		$arrAuxDesc = array();			
		while($row = mysql_fetch_assoc($rs)){			
			$arrAuxId[] = $row['id_estatus_pedido'];
			$arrAuxDesc[] = $row['nombre'];			
		}		
		$smarty->assign('estatus_id', $arrAuxId);
		$smarty->assign('estatus',$arrAuxDesc);		
	}
	
	if($rep==8) 
	{
		/*
		 * Sucursales
		 */		
		
		$where = "";
		$user = $_SESSION["USR"] -> userid;
		/*if($user != 0 && $user != 1){
				$where .= "AND id_sucursal = " . $_SESSION["USR"] -> sucursalid;
				$sel = $_SESSION["USR"] -> sucursalid;
				}*/
		
		$strSQL = "SELECT id_sucursal, nombre AS 'nombre' FROM na_sucursales WHERE 1 " . $where . " AND activo = 1 AND id_sucursal <> 0";
		
		$rs = mysql_query($strSQL) or die("Error en consulta $strSQL\nDescripcion:".mysql_error());		  	
		$arrAuxId = array();
		$arrAuxDesc = array();			
		while($row = mysql_fetch_assoc($rs)){			
			$arrAuxId[] = $row['id_sucursal'];
			$arrAuxDesc[] = $row['nombre'];			
		}		
		$smarty->assign('sucursal_id', $arrAuxId);
		$smarty->assign('sucursal_nombre',$arrAuxDesc);	
		$smarty->assign('usuarioSuc',$user);	
		/*
		 * Forma de pago
		 */		
		$strSQL = "SELECT id_forma_pago, nombre FROM na_formas_pago order by nombre;";		
		$rs = mysql_query($strSQL) or die("Error en consulta $strSQL\nDescripcion:".mysql_error());		  	
		$arrAuxId = array();
		$arrAuxDesc = array();			
		while($row = mysql_fetch_assoc($rs)){			
			$arrAuxId[] = $row['id_forma_pago'];
			$arrAuxDesc[] = $row['nombre'];			
		}		
		$smarty->assign('forma_pago_id', $arrAuxId);
		$smarty->assign('forma_pago_nombre',$arrAuxDesc);	
		/*
		 * Radio button
		 */	
		$smarty->assign('version_id', array(1,2));
		$smarty->assign('version_nombre', array('Detallada', 'Resumida'));
		// preseleccionar radio
		$smarty->assign('version_default', 1);
		
	}
	
	if($rep==11){
			$where = "";
			if($_SESSION["USR"]->sucursalid != 0){
					$where = " AND id_sucursal = " . $_SESSION["USR"]->sucursalid;
					}
			$strSQL = "SELECT id_sucursal, nombre AS 'nombre' FROM na_sucursales WHERE 1" . $where . " ORDER BY nombre";		
				$rs = mysql_query($strSQL) or die("Error en consulta $strSQL\nDescripcion:".mysql_error());		  	
				$arrAuxId = array();
				$arrAuxDesc = array();			
				while($row = mysql_fetch_assoc($rs)){			
					$arrAuxId[] = $row['id_sucursal'];
					$arrAuxDesc[] = $row['nombre'];			
				}		
				$smarty->assign('sucursal_id', $arrAuxId);
				$smarty->assign('sucursal_nombre',$arrAuxDesc);	
			}
	/**********************************************************/
	$smarty -> assign("sessionSuc", $_SESSION["USR"]->sucursalid);
	$smarty->display('reportes.tpl');	
	
?>