<?php
	//$op=1 nuevo
	//$op=2 modificar y ver
	//$op=3 eliminar
	if($tabla == "sys_usuarios" ){
		if($_SESSION["USR"] -> idGrupo != '1'){
			$campo = retornaIDCatalogoOrden("id_usuario ","sys_usuarios");
			$atributos[$campo][6] = 0;
			$atributos[$campo][5] = 0;
			/*
			$campo = retornaIDCatalogoOrden("login ","sys_usuarios");
			$atributos[$campo][6] = 0;
			$atributos[$campo][5] = 0;
			*/
			$campo = retornaIDCatalogoOrden("apellido_paterno ","sys_usuarios");
			$atributos[$campo][6] = 0;
			$atributos[$campo][5] = 0;
			
			$campo = retornaIDCatalogoOrden("apellido_materno ","sys_usuarios");
			$atributos[$campo][6] = 0;
			$atributos[$campo][5] = 0;
			
			$campo = retornaIDCatalogoOrden("nombres ","sys_usuarios");
			$atributos[$campo][6] = 0;
			$atributos[$campo][5] = 0;
			
			$campo = retornaIDCatalogoOrden("id_sucursal ","sys_usuarios");
			$atributos[$campo][6] = 0;
			$atributos[$campo][5] = 0;
			
			$campo = retornaIDCatalogoOrden("id_cliente_distribuidor ","sys_usuarios");
			$atributos[$campo][6] = 0;
			$atributos[$campo][5] = 0;
			
			$campo = retornaIDCatalogoOrden("email ","sys_usuarios");
			$atributos[$campo][6] = 0;
			$atributos[$campo][5] = 0;
			
			$campo = retornaIDCatalogoOrden("telefono ","sys_usuarios");
			$atributos[$campo][6] = 0;
			$atributos[$campo][5] = 0;
			/*
			$campo = retornaIDCatalogoOrden("pass ","sys_usuarios");
			$atributos[$campo][6] = 0;
			$atributos[$campo][5] = 0;*/
			
			$campo = retornaIDCatalogoOrden("id_grupo ","sys_usuarios");
			$atributos[$campo][6] = 0;
			$atributos[$campo][5] = 0;
			
			$campo = retornaIDCatalogoOrden("fecha_alta ","sys_usuarios");
			$atributos[$campo][6] = 0;
			$atributos[$campo][5] = 0;
			
			$campo = retornaIDCatalogoOrden("fecha_ultimo_acceso ","sys_usuarios");
			$atributos[$campo][6] = 0;
			$atributos[$campo][5] = 0;
			
			$campo = retornaIDCatalogoOrden("activo ","sys_usuarios");
			$atributos[$campo][6] = 0;
			$atributos[$campo][5] = 0;
			
			$campo = retornaIDCatalogoOrden("id_empleado_relacionado ","sys_usuarios");
			$atributos[$campo][6] = 0;
			$atributos[$campo][5] = 0;
			
			$campo = retornaIDCatalogoOrden("id_area ","sys_usuarios");
			$atributos[$campo][6] = 0;
			$atributos[$campo][5] = 0;
			
			$campo = retornaIDCatalogoOrden("id_subarea ","sys_usuarios");
			$atributos[$campo][6] = 0;
			$atributos[$campo][5] = 0;
			
			$campo = retornaIDCatalogoOrden("id_tipo_cliente_proveedor ","sys_usuarios");
			$atributos[$campo][6] = 0;
			$atributos[$campo][5] = 0;
			
			$campo = retornaIDCatalogoOrden("id_tipo_empleado ","sys_usuarios");
			$atributos[$campo][6] = 0;
			$atributos[$campo][5] = 0;
		}
		$smarty -> assign('grupoID',$_SESSION["USR"] -> idGrupo);

	}
	if($tabla=='#tabla#' && ($v == 0 || $v == 1))
	{		
		$campo=retornaIDCatalogoOrden("codigo_articulo ","rac_articulos");
		$atributos[$campo][7]=0;
		$campo=retornaIDCatalogoOrden("id_linea_articulo","rac_articulos");
	}
	
	if($tabla == "ad_clientes" ){
				
				//BUSCAMOS EL TIPO DE CLIENTE PROVEEDOR PARA NO MODIFCAR EL COMBO
					
			$htmlCTM=array();
			$strComboAux="SELECT id_tipo_cliente_proveedor,nombre FROM `cl_tipos_cliente_proveedor` where numero_catalogo =".$_SESSION["USR"]->subtipo_movimiento ;
			$htmlCTM=retornaListaIdsNombres($strComboAux,'NO', 0, 0, ' ');
			$campo=retornaIDCatalogoOrden("id_tipo_cliente_proveedor ","ad_clientes");
			$atributos[$campo][16]=array();
			$atributos[$campo][16]=$htmlCTM;
			
			if($_SESSION["USR"]->subtipo_movimiento=='75005')
			{
			
				$htmlSuc=array();
				$strComboAuxSuc="SELECT id_sucursal,nombre FROM ad_sucursales where id_sucursal=10";
				$htmlSuc=retornaListaIdsNombres($strComboAuxSuc,'NO', 0, 0, ' ');
				$campo=retornaIDCatalogoOrden("id_sucursal ","ad_clientes");
				$atributos[$campo][16]=array();
				$atributos[$campo][16]=$htmlSuc;
			}
			
								
						
		}
	elseif($tabla == "ad_proveedores" ){
				
				//BUSCAMOS EL TIPO DE CLIENTE PROVEEDOR PARA NO MODIFCAR EL COMBO
				if($op == 1 || ($op==2 &&	$ver==0))
				{	
					$htmlCTM=array();
					$strComboAux="SELECT id_tipo_cliente_proveedor,nombre FROM cl_tipos_cliente_proveedor WHERE activo=1 and id_tipo_cliente_proveedor in (10,12)";
					$htmlCTM=retornaListaIdsNombres($strComboAux,'NO', 0, 0, ' ');
					$campo=retornaIDCatalogoOrden("id_tipo_cliente_proveedor ","ad_proveedores");
					$atributos[$campo][16]=array();
					$atributos[$campo][16]=$htmlCTM;
					
				 }
		

						
		}
	elseif($tabla == "ad_movimientos_almacen" )
	{
		if($op == 1)
		{
			
			//vemos el subtipo de movimiento
			$htmlATM=array();
			$strComboAux="SELECT ad_subtipos_movimientos.id_tipo_movimiento, ad_tipos_movimiento.nombre FROM ad_subtipos_movimientos
						  left join ad_tipos_movimiento on ad_subtipos_movimientos.id_tipo_movimiento=ad_tipos_movimiento.id_tipo_movimiento
                          WHERE ad_subtipos_movimientos.id_subtipo_movimiento = '".$_SESSION["USR"]->subtipo_movimiento."'";
 
			$htmlATM=retornaListaIdsNombres($strComboAux,'NO', 0, 0, ' ');
			
		//	print_r($htmlASTM);
				
			$campo=retornaIDCatalogoOrden("id_tipo_movimiento ","ad_movimientos_almacen");
			$atributos[$campo][16]=array();
			$atributos[$campo][16]=$htmlATM;
			
			
			//colocamos el subtipo de movimiento
			$htmlBTM=array();
			$strComboAux="SELECT id_subtipo_movimiento, nombre FROM ad_subtipos_movimientos
						 where id_subtipo_movimiento = '".$_SESSION["USR"]->subtipo_movimiento."'";
 
			$htmlBTM=retornaListaIdsNombres($strComboAux,'NO', 0, 0, ' ');
			
			//	print_r($htmlASTM);
				
			$campo=retornaIDCatalogoOrden("id_subtipo_movimiento ","ad_movimientos_almacen");
			$atributos[$campo][16]=array();
			$atributos[$campo][16]=$htmlBTM;
			
				
			//tambien mandamos los almacenes al los qu estamos tratando de entrar 
			//si el ususario tiene asigando un almacen entonces solo mostramos el alamcen alque tiene acceso
			
			//esto tambien aplicará para los pedidos
			//clientes que puedan modificar la informacion
			
			//"na_movimientos_almacen.id_almacen in (SELECT id_almacen FROM na_sucursales where id_sucursal in (SELECT id_sucursal FROM sys_usuarios where id_usuario='".$_SESSION["USR"]->userid ."')
			
			//tambien colocamos los alamacenes a los que requiere realizar el traspaso
			$strSQLU="SELECT id_sucursal FROM `sys_usuarios` where id_usuario='".$_SESSION['USR']->userid."'";
			$res=valBuscador($strSQLU);
			
			if($res[0]!='0')
			{
				//mostramos el almacen al que esta asignado el usuario mendiante la sucursal
				$htmlCTM=array();
				$strComboAux="SELECT id_almacen,nombre FROM ad_almacenes WHERE id_almacen in (SELECT id_almacen FROM ad_sucursales where id_sucursal in (SELECT id_sucursal FROM sys_usuarios where id_usuario='".$_SESSION["USR"]->userid ."'))";
	 			$htmlCTM=retornaListaIdsNombres($strComboAux,'NO', 0, 0, ' ');
				$campo=retornaIDCatalogoOrden("id_almacen ","ad_movimientos_almacen");
				$atributos[$campo][16]=array();
				$atributos[$campo][16]=$htmlCTM;
			}
							
		}
		
	}
	elseif($tabla == "ad_pedidos"){
		$sql = "SELECT id_sucursal,nombre FROM ad_sucursales WHERE activo = 1 AND id_sucursal = ".$_SESSION["USR"] -> sucursalid;
		$htmlCTM = array();
		$htmlCTM = retornaListaIdsNombres($sql,'NO', 0, 0, ' ');
		$campo = retornaIDCatalogoOrden("id_sucursal_alta ","ad_pedidos");
		$atributos[$campo][16] = array();
		$atributos[$campo][16] = $htmlCTM;
		
		$sql = "
			SELECT cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor,cl_tipos_cliente_proveedor.nombre FROM ad_clientes 
			LEFT JOIN cl_tipos_cliente_proveedor ON ad_clientes.id_tipo_cliente_proveedor = cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor
			WHERE id_cliente = ".$_SESSION["USR"] -> cliente_principal_id;
		//echo '<pre>';print_r($atributos);echo '</pre>';die($sql);
		$htmlTCli = array();
		$htmlTCli = retornaListaIdsNombres($sql,'NO', 0, 0, ' ');
		$campo = retornaIDCatalogoOrden("id_tipo_cliente ","ad_pedidos");
		$atributos[$campo][16] = array();
		$atributos[$campo][16] = $htmlTCli;
		
		$sql = "
			SELECT ad_clientes.id_cliente,CONCAT(clave,'::',CONCAT_WS(' ',nombre,apellido_paterno,apellido_materno)) AS nombre_cliente
			FROM ad_clientes 
			WHERE id_cliente = ".$_SESSION["USR"] -> cliente_principal_id;
		$htmlCli = array();
		$htmlCli = retornaListaIdsNombres($sql,'NO', 0, 0, ' ');
		$campo = retornaIDCatalogoOrden("id_cliente ","ad_pedidos");
		$atributos[$campo][16] = array();
		$atributos[$campo][16] = $htmlCli;
		
		$sql = "
			SELECT id_usuario,CONCAT_WS(' ',nombres,apellido_paterno,apellido_materno) AS usuario
			FROM sys_usuarios 
			WHERE id_usuario = ".$_SESSION["USR"] -> userid;
		$htmlUsr = array();
		$htmlUsr = retornaListaIdsNombres($sql,'NO', 0, 0, ' ');
		$campo = retornaIDCatalogoOrden("id_usuario_registro ","ad_pedidos");
		$atributos[$campo][16] = array();
		$atributos[$campo][16] = $htmlUsr;
		$campo = retornaIDCatalogoOrden("subtotal ","ad_pedidos");
		$atributos[$campo][6] = 0;
		$campo = retornaIDCatalogoOrden("iva ","ad_pedidos");
		$atributos[$campo][6] = 0;
		$campo = retornaIDCatalogoOrden("total ","ad_pedidos");
		$atributos[$campo][6] = 0;
	}
	elseif($tabla == "ad_ordenes_compra" )
	{
		//si estamos modificando no se podrá cambiar el almancen de los pedidos
		if($op == 1)
		{
				//mostramos el almacen al que esta asignado el usuario mendiante la sucursal
				$htmlCTM=array();
				$strComboAux="SELECT id_estatus_orden_compra, nombre FROM ad_estatus_orden_compra where id_estatus_orden_compra=1";
				$htmlCTM=retornaListaIdsNombres($strComboAux,'NO', 0, 0, ' ');
				$campo=retornaIDCatalogoOrden("id_estatus_orden_compra ","ad_ordenes_compra");
				$atributos[$campo][16]=array();
				$atributos[$campo][16]=$htmlCTM;
		
		
			
		}//si es para modificacion de pedidos
		else if($op == 2 && $k !='')
		{
			
			//VEMOS EL ESTATUS DE LA ORDEN DE COMPRA PARA SABER A QUE ESTATUS PIDEMOS ACCEDER
			
			$strSQLU="SELECT id_estatus_orden_compra FROM `ad_ordenes_compra` where id_orden_compra='".$k."' ";
			
			//echo $strSQLU;
			
			$res=valBuscador($strSQLU);
			
			
			if($res[0]=='1'  ) 
			{
			
				$strComboAux="SELECT id_estatus_orden_compra, nombre FROM ad_estatus_orden_compra where id_estatus_orden_compra in (1)";
			}
			elseif( $res[0]=='2' )
			{
			
				$strComboAux="SELECT id_estatus_orden_compra, nombre FROM ad_estatus_orden_compra where id_estatus_orden_compra in (2,6)";
			}
			elseif( $res[0]=='3')
			{
			
				$strComboAux="SELECT id_estatus_orden_compra, nombre FROM ad_estatus_orden_compra where id_estatus_orden_compra in (3)";
			}
			elseif($res[0]=='4'  )
			{
			
				$strComboAux="SELECT id_estatus_orden_compra, nombre FROM ad_estatus_orden_compra where id_estatus_orden_compra in (4,6)";
			}
			elseif($res[0]=='5'  )
			{
			
				$strComboAux="SELECT id_estatus_orden_compra, nombre FROM ad_estatus_orden_compra where id_estatus_orden_compra in (5)";
			}
			elseif($res[0]=='6'  )
			{
			
				$strComboAux="SELECT id_estatus_orden_compra, nombre FROM ad_estatus_orden_compra where id_estatus_orden_compra in (6)";
			}
			
			
			
			$htmlCTM=array();
			$htmlCTM=retornaListaIdsNombres($strComboAux,'NO', 0, 0, ' ');
			$campo=retornaIDCatalogoOrden("id_estatus_orden_compra ","ad_ordenes_compra");
			$atributos[$campo][16]=array();
			$atributos[$campo][16]=$htmlCTM;
		
		}
				
		
		
	}
	elseif($tabla == "ad_facturas" )
	{
		if($op == 1)
		{
			//si estamos en facturas y si es manual no mostamos el pedido y solo por default estartá la pantalla de tipo de pedido manual
			$htmlDTM=array();
            $strComboAux="SELECT id_tipo_factura,nombre FROM ad_tipos_factura WHERE id_tipo_factura=1";
            $htmlDTM=retornaListaIdsNombres($strComboAux,'NO', 0, 0, ' ');
            $campo=retornaIDCatalogoOrden("id_tipo_factura","ad_facturas");
            $atributos[$campo][16]=array();
            $atributos[$campo][16]=$htmlDTM;
			
			//colocamos como no visible el pedido
			
			$campo=retornaIDCatalogoOrden("id_control_pedido ","ad_facturas");
				$atributos[$campo][6]=0;
				$atributos[$campo][7]=0;
			
			
			
		}
		
	}
	
	
	
	
	
	/*****CONSULTA PARA OBTENER LAS SUCURSALES EN EL CATALOGO CUENTAS POR PAGAR Y ARROJARLAS EN EL GRID**********************/
if($tabla =='na_aduanas' || $tabla =='ad_cuentas_por_pagar_operadora'){
		$sqlSucCXP = "SELECT id_sucursal, nombre FROM ad_sucursales WHERE activo = 1 AND id_sucursal <> 0";
		$datosSucCXP = new consultarTabla($sqlSucCXP);
		$sucCXP = $datosSucCXP -> obtenerArregloRegistros();
		$smarty->assign("filascxp",$sucCXP);
		
	}
	
	//------------------------------
	if( $tabla =='ad_cuentas_por_pagar_operadora'){
		if($op == 1)
		{
			//solo mostramos el estatus
			$htmlCTM=array();
			$strComboAux="SELECT id_estatus_cuentas_por_pagar,nombre FROM ad_estatus_cuentas_por_pagar WHERE activo=1 and id_estatus_cuentas_por_pagar=1";
			$htmlCTM=retornaListaIdsNombres($strComboAux,'NO', 0, 0, ' ');
			$campo=retornaIDCatalogoOrden("id_estatus_cuentas_por_pagar ","ad_cuentas_por_pagar_operadora");
			$atributos[$campo][16]=array();
			$atributos[$campo][16]=$htmlCTM;
		}
		else
		{
			$campo=retornaIDCatalogoOrden("id_estatus_cuentas_por_pagar ","ad_cuentas_por_pagar_operadora");
			$atributos[$campo][7]=0;
		}
	}
	
if($tabla == "ad_egresos_caja_chica" ){
			if($op == 1 || $op == 2){
					$sql = "SELECT SUM(monto) AS ingresos FROM ad_ingresos_caja_chica WHERE id_sucursal = " . $_SESSION['USR']->sucursalid;
					$datos = new consultarTabla($sql);
					$ingresos = $datos -> obtenerLineaRegistro();
					
					$sql2 = "SELECT SUM(total) AS egresos FROM ad_egresos_caja_chica WHERE id_sucursal = " . $_SESSION['USR']->sucursalid;
					$datos2 = new consultarTabla($sql2);
					$egresos = $datos2 -> obtenerLineaRegistro();
					
					$ingresos['ingresos'] = $ingresos['ingresos'] = "" ? 0 : $ingresos['ingresos'];
					$egresos['egresos'] = $egresos['egresos'] = "" ? 0 : $egresos['egresos'];
					
					$total_caja = $ingresos['ingresos'] - $egresos['egresos'];
					
					$campo=retornaIDCatalogoOrden("total_caja","ad_egresos_caja_chica");
					$atributos[$campo][15] = number_format($total_caja, 2);
					}
			}	
if($tabla == "ad_ingresos_caja_chica" ){
		if($op == 1){
				$htmlDTM=array();
				$strComboAux="SELECT id_tipo_ingreso, descripcion FROM ad_tipos_ingreso_caja_chica WHERE modificable_usuario = 1";
				$htmlDTM=retornaListaIdsNombres($strComboAux,'NO', 0, 0, ' ');
				$campo=retornaIDCatalogoOrden("id_tipo_ingreso","ad_ingresos_caja_chica");
				$atributos[$campo][16]=array();
				$atributos[$campo][16]=$htmlDTM;
				}
		else{
				$campo=retornaIDCatalogoOrden("id_tipo_ingreso ","ad_ingresos_caja_chica");
				$atributos[$campo][7]=0;
				$campo=retornaIDCatalogoOrden("id_sucursal_origen ","ad_ingresos_caja_chica");
				$atributos[$campo][7]=0;
				}
		}
			
	//.....................FUNCIONES AUXILIARES .......................
	//funcion para obtener la posicion del campo en base al id en la base
	function obtenPosicion($arrayDatos,$nombre_campo){
		$id_posicion=0;
	    foreach($arrayDatos as $datos){
			//print_r($datos);
			if($datos[0]==$nombre_campo){
				 break;
			}
			$id_posicion++;
		}
		return $id_posicion;
	}
	
	function obtenerValorSql($strSQL,$posicion_index_return){
		//concatenamos el limit para que solo regrese un valor
		$strSQL.=" LIMIT 1";
		//decrementemos el indice de busqueda 
		$posicion_index_return=$posicion_index_return-1;
		if(!($resource = mysql_query($strSQL)))	die("Error en:<br><i>$strSQL</i><br><br>Descripci&oacute;n:<br><b>".mysql_error());
		while($row0 = mysql_fetch_row($resource))
		{
			//si el valor es nulo
			if(is_null($row0[$posicion_index_return]))
				$valor=0;
			else
				$valor=$row0[$posicion_index_return];
		}
		mysql_free_result($resource);
		//echo $valor;
		return  $valor;		
	}
	//********************************************************************************************************
	
?>