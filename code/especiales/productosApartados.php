<?php
		
	
	php_track_vars;

	include("../../conect.php");
	include("../../config.inc.php");
	include("../general/funciones.php");
	include("../../consultaBase.php");
	
	extract($_GET);
	extract($_POST);	
	
	//ACTUALIZAR EL ESTATUS DE LAS ORDENES DE COMPRA
	if($accion=='actualizar')
	{		
		
		//actializamos a desarptar
		
		if($realiza==1)
		{
			//$idControlPedido;
			$strSQL = "		UPDATE na_pedidos_detalle_pagos 
				SET confirmado ='1', id_usuario_confirmacion = " . $_SESSION["USR"]->userid . ", fecha_hora=now() ,
				observaciones_confirmacion='".$observaciones."'
				WHERE id_pedido_detalle_pago = '".$id_detalle_pedido."' 
			";	
			
		}	
		
		//vemos si es una actualizacion 
		
		//actualizamos el pago que solicito la persona
				
		mysql_query($strSQL) or die("Error en:<br><i>$strSQL</i><br><br>Descripcion:<br><b>".mysql_error());
			//Actualiza la fecha de entrega de la orden de compra en la franquicia
			
		
		
		//enviar emails al vendedor del pedido
	
	}

	//Autorizaci&oacute;n &Oacute;rdenes de Compra a Proveedor
	//$smarty->assign("nombre_menu","Aprobación Pedidos");
	
	

	
	//carga combo de sucursales 	{html_options values=$valuesRutas output=$outputsRutas}
	$registros=array();
	$num=0;		

	$sql="SELECT id_pedido_detalle_pago,
		na_pedidos_detalle_pagos.id_control_pedido,
		ad_pedidos.id_pedido,
		CONCAT(ad_clientes.nombre,' ', ad_clientes.apellido_paterno,' ', ad_clientes.apellido_materno) as cliente,
		na_sucursales.nombre as sucursal,
		CONCAT(na_vendedores.nombre,' ', na_vendedores.apellido_paterno,' ', na_vendedores.apellido_materno) as vendedor,
		CONCAT(sys_usuarios.nombres,' ',sys_usuarios.apellido_paterno,' ', sys_usuarios.apellido_materno) as usuario,
		date_format(fecha , '%d/%m/%Y') AS 'Fecha',
		na_formas_pago.id_forma_pago,
		na_formas_pago.nombre AS 'Tipo de Pago',
		na_pedidos_detalle_pagos.id_terminal_bancaria,
		na_terminales_bancarias.nombre AS 'Terminal Bancaria',
		numero_documento AS 'Número de Documento',
		numero_aprobacion AS 'Número de Aprobación',
		format(monto,2) AS 'Monto',
		na_pedidos_detalle_pagos.confirmado,
		sys_si_no.nombre AS 'Confirmado',
		na_pedidos_detalle_pagos.observaciones AS 'Observaciones',
		na_pedidos_detalle_pagos.requiere_terminal_bancaria AS 'Bancaria',
		na_pedidos_detalle_pagos.requiere_documento AS 'Documento',
		na_pedidos_detalle_pagos.requiere_aprobacion AS 'Aprobacion',
		na_pedidos_detalle_pagos.id_recibo AS 'Recibo',
		na_pedidos_detalle_pagos.cancelado AS 'Cancelado'
		FROM na_pedidos_detalle_pagos
		left join ad_pedidos on ad_pedidos.id_control_pedido=na_pedidos_detalle_pagos.id_control_pedido
		left join ad_clientes on ad_clientes.id_cliente=ad_pedidos.id_cliente
		left join na_sucursales on na_sucursales.id_sucursal=ad_pedidos.id_sucursal_alta
		left join na_vendedores on ad_pedidos.id_vendedor=na_vendedores.id_vendedor
		left join sys_usuarios on sys_usuarios.id_usuario=ad_pedidos.id_usuario
		LEFT JOIN na_formas_pago ON na_formas_pago.id_forma_pago = na_pedidos_detalle_pagos.id_forma_pago
		LEFT JOIN na_terminales_bancarias ON na_terminales_bancarias.id_terminal_bancaria = na_pedidos_detalle_pagos.id_terminal_bancaria
		LEFT JOIN sys_si_no ON na_pedidos_detalle_pagos.confirmado = sys_si_no.id_si_no
		where autorizacion_credito_cobranza='1' AND na_pedidos_detalle_pagos.cancelado=0 and na_pedidos_detalle_pagos.confirmado =2 ";

	
	$res=mysql_query($sql) or die("Error en:<br><i>$sql</i><br><br>Descripcion:<br><b>".mysql_error());
	$num=mysql_num_rows($res);
	for($i=0;$i<$num;$i++)
	{
		$row=mysql_fetch_row($res);
		array_push($registros,$row);
		
	}
	
	$smarty->assign("encontrados",$num);
	$smarty->assign("registros",$registros);

	$smarty->display("especiales/productosApartados.tpl");
	
?>