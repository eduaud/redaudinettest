<?php

//ESTAS JUNTARLAS EN UN ARCHIVO
/*$ruta = 'C:/ServidorWeb_S&W/newart/';
include($ruta.'config.inc.php');
//variables declaracion
include(INCLUDEPATH."container.inc.php");
include(INCLUDEPATH."module_exec.inc.php");	
*/
include("../../conect.php");
include("../general/funciones.php");

extract($_GET);
extract($_POST);

//Variables de filtro
$c=isset($c)?$c:'nada';
$o=isset($o)?$o:'nada';
$v=isset($v)?$v:'nada';
$op=isset($op)?$op:'nada';
$tcr=isset($tcr)?$tcr:'0';
//$v = str_replace("'", "\'", $v);
//echo "Esta es la palabra ---> " . $v;
/*$getHandler = new EBAGetHandler();
$getHandler->ProcessRecords();*/


//En esta secci&oacute;n de c&oacute;digo podemos escoger las opciones de acuerdo a la tabla sis_listado
$tabla = base64_decode($t);


$listado =$tabla;// str_replace("erp_","listado_",$tabla);

$sqAux="SELECT
		s.id_sucursal
		FROM `sys_usuarios_sucursales` s		
		WHERE s.id_usuario=".$_SESSION["USR"]->userid."";


	
	
//Opción de datos completos
if($c == 'nada' || $c == 'viewall')
{
	//echo "nada";
	//Consulta para obtener el SQL necesario para extraer los campos
	$strConsulta = "SELECT Consulta, imprimir, orden as 'Orden' FROM sys_listados WHERE `tabla`='".$listado."'";
	$resultado = mysql_query($strConsulta);
	$variable=mysql_fetch_assoc($resultado);
	//Consulta para saber que campos hay que desplegar en el grid
	$strConsulta=$variable['Consulta'];
	
	//Excepcion para sucursales
	if($_SESSION["USR"]->sucursalid == 0)
			$strConsulta = str_replace('$SUC', '', $strConsulta);
	else if($tabla != "ad_facturas_audicel")
			$strConsulta = str_replace('$SUC', ' AND ad_pedidos.id_sucursal_alta =' . $_SESSION["USR"]->sucursalid, $strConsulta);
	
	
	
	if($mpe == '1'  && $tabla != 'ad_facturas_audicel'&& $tabla != 'ad_notas_credito'){
		$strConsulta=str_replace('FROM '.$tabla, ", 'Modif' FROM ".$tabla, $strConsulta);
	}	
	
	if($vpe == '1')
		$strConsulta=str_replace('FROM '.$tabla,", 'Dets' FROM ".$tabla,$strConsulta);
		  
	if($tabla == 'ad_ingresos_caja_chica' || $tabla == 'ad_pedidos'|| $tabla == 'ad_solicitudes_material' || $tabla == 'ad_cuentas_por_pagar_operadora' || $tabla == 'ad_egresos')		
		$strConsulta=str_replace('FROM '.$tabla,", 'can' FROM ".$tabla,$strConsulta);		
		
	if($epe == '1' && $tabla != 'sys_parametros_configuracion' && $tabla != 'ad_facturas'&& $tabla != 'ad_facturas_audicel' && $tabla != 'ad_notas_credito' && $tabla !='anderp_cuentas_por_cobrar' && $tabla != 'ad_cuentas_por_pagar_operadora' && $tabla != 'ad_egresos')
		$strConsulta=str_replace('FROM '.$tabla,", 'Elim' FROM ".$tabla,$strConsulta);
	
	if($tabla == 'ad_egresos_caja_chica' || $tabla == 'ad_pedidos'|| $tabla == 'ad_solicitudes_material' || $tabla == 'ad_ordenes_compra_productos'||$tabla =='ad_movimientos_almacen'||$tabla=='cl_contrarecibos')		
		$strConsulta=str_replace('FROM '.$tabla,", 'imp' FROM ".$tabla,$strConsulta);
if($tabla == 'ad_facturas'||$tabla == 'ad_facturas_audicel')	
{
		$strConsulta=str_replace('FROM '.$tabla,", 'imp' FROM ".$tabla,$strConsulta);
}
if($tabla == 'ad_facturas_audicel'){	
	$strConsulta=str_replace('FROM '.$tabla,", 'xml' FROM ".$tabla,$strConsulta);
}
	
	
	/*if($tabla == 'ad_cuentas_por_pagar_operadora')		
		$strConsulta=str_replace('FROM '.$tabla,", 'xml' FROM ".$tabla,$strConsulta);
	
	if($tabla == 'ad_cuentas_por_pagar_operadora')		
		$strConsulta=str_replace('FROM '.$tabla,", 'pdf' FROM ".$tabla,$strConsulta);*/
	
	
	/*if($tabla == 'spa_clientes#')		
		$strConsulta=str_replace('FROM ' . 'spa_clientes', ", 'Modif', 'Dets', 'Elim' FROM " . 'spa_clientes', $strConsulta);		*/
		
	if(!(strpos($tabla, '#') === false)){
		$tablaAux = str_replace("#", "", $tabla);
		$strConsulta;
		$strConsulta=str_replace("FROM $tablaAux", ", 'Modif', 'Dets', 'Elim' FROM $tablaAux", $strConsulta);		
	}
	
//Opcin para datos filtrados
}
else
{
   //echo "nelson";
   //echo "rastreo 23<br>";
	//Consulta para obtener el SQL necesario para extraer los campos, ya filtrados
	$strConsulta = "SELECT * FROM sys_listados WHERE `tabla`='".$listado."'";

	$resultado = mysql_query($strConsulta);
	$variable = mysql_fetch_assoc($resultado);
	//Consulta para saber que campos hay que desplegar en el grid
	$strConsulta=$variable['Consulta'];
	$consultaaux=strtoupper($strConsulta);
	if(($posOrder=strpos($consultaaux,"ORDER BY"))!==false)
	{
		$orden=substr($strConsulta,$posOrder);
		$strConsulta=substr($strConsulta,0,$posOrder);
	}	

	if($mpe == '1' && $tabla != 'ad_facturas_audicel' && $tabla != 'ad_notas_credito')		
		$strConsulta=str_replace('FROM '.$tabla,", 'Modif' FROM ".$tabla,$strConsulta);

	if($vpe == '1' )
		$strConsulta=str_replace('FROM '.$tabla,", 'Dets' FROM ".$tabla,$strConsulta);
	
	if($epe == '1' && $tabla != 'sys_parametros_configuracion' && $tabla != 'ad_facturas'&& $tabla != 'ad_facturas_audicel'  && $tabla != 'ad_notas_credito' && $tabla !='anderp_cuentas_por_cobrar' && $tabla != 'ad_cuentas_por_pagar_operadora' && $tabla != 'ad_egresos')
		$strConsulta=str_replace('FROM '.$tabla,", 'Elim' FROM ".$tabla,$strConsulta);
		
		
	if($tabla == 'ad_ingresos_caja_chica' || $tabla == 'ad_pedidos' || $tabla == 'ad_cuentas_por_pagar_operadora' || $tabla == 'ad_egresos')		
		$strConsulta=str_replace('FROM '.$tabla,", 'can' FROM ".$tabla,$strConsulta);	
		
	if($tabla == 'ad_egresos_caja_chica' || $tabla == 'ad_pedidos' || $tabla == 'ad_ordenes_compra_productos')		
		$strConsulta=str_replace('FROM '.$tabla,", 'imp' FROM ".$tabla,$strConsulta);
	
	
	if($tabla == 'ad_facturas_audicel' || $tabla == 'ad_facturas')	
		$strConsulta=str_replace('FROM '.$tabla,", 'imp' FROM ".$tabla,$strConsulta);
	if($tabla == 'cl_contrarecibos'  )	
		$strConsulta=str_replace('FROM '.$tabla,", 'imp' FROM ".$tabla,$strConsulta);
	if($tabla == 'ad_facturas_audicel')	
	{	
			$strConsulta=str_replace('FROM '.$tabla,", 'xml' FROM ".$tabla,$strConsulta);
	}
	/*if($tabla == 'ad_cuentas_por_pagar_operadora')		
		$strConsulta=str_replace('FROM '.$tabla,", 'xml' FROM ".$tabla,$strConsulta);	
	if($tabla == 'ad_cuentas_por_pagar_operadora')		
		$strConsulta=str_replace('FROM '.$tabla,", 'pdf' FROM ".$tabla,$strConsulta);	
	*/
   
   $strConsultaAux = strtoupper($strConsulta);	
   if(eregi("WHERE",$strConsultaAux))
    	$strConsulta.=" AND ".str_replace('\\','',urldecode($c))." ";
   else	  
	$strConsulta.=" WHERE ".str_replace('\\','',urldecode($c))." ";

/*Excepcion para fechas con formato dd/mm/YYYY
***/
$FlagesFecha=0;
	if($c != ''){
	   if(eregi("fecha",$c)){//si el campo es fecha
	      
		 if(strlen($v) > 10){  //si el formato de fecha es datatime
		     $FlagesFecha = 1;
			 if(eregi("/",$v)){
		        $Aux_v = explode("/",$v);
			    $Aux2 = explode(" ",$Aux_v[2]);
				$v = $Aux2[0]."-".$Aux_v[1]."-".$Aux_v[0]." ".$Aux2[1];
		     }
		        $v = "DATE_FORMAT('".$v."', '%Y-%m-%d %H:%i:%s')";
		 }
		 else{
		    if(eregi("/",$v)){  //si el formato de fecha es date
		        $Aux_v = explode("/",$v);
				$v = $Aux_v[2]."-".$Aux_v[1]."-".$Aux_v[0];
			
		     }
		 }
	   }
	}
	
	//echo $o . "<br><br>";
	if($v != '')	
	{
		switch($o)
		{
			case '=':
				if($FlagesFecha == 1)
				   $strConsulta.=$o."".$v."";
				else
			 	   $strConsulta.=$o."'".$v."'";
			break;
			case '!=':
			     if($FlagesFecha == 1)
				   $strConsulta.=$o."".$v."";
				else
				   $strConsulta.=$o."'".$v."'";
			break;
			case '>':
			    if($FlagesFecha == 1)
				   $strConsulta.=$o."".$v."";
				else
				   $strConsulta.=$o."'".$v."'";
			break;
			case '<':
			    if($FlagesFecha == 1)
				   $strConsulta.=$o."".$v."";
				else
				   $strConsulta.=$o."'".$v."'";
			break;
			case '>=':
				if($FlagesFecha == 1)
				   $strConsulta.=$o."".$v."";
				else
			 	   $strConsulta.=$o."'".$v."'";
			break;
			case '<=':
				if($FlagesFecha == 1)
				   $strConsulta.=$o."".$v."";
				else
				   $strConsulta.=$o."'".$v."'";
			break;
			case 'empieza':
				if($FlagesFecha == 1)
				   $strConsulta.=$o."".$v."";
				else
				   $strConsulta.=" LIKE '".$v."%'";
			break;
			case 'contiene':
				if($FlagesFecha == 1)
				   $strConsulta.=$o."".$v."";
				else
				   $strConsulta.=" LIKE '%".$v."%'";
			break;
		}
	}	
	
	//echo $o . "<br><br>" . $strConsulta;

	if(isset($orden))
		$strConsulta.=" ".$orden;
		
	grabaBitacora(9, $tabla, 0, 0, $_SESSION["USR"]->userid, 0, '', '');	
	
	//echo "rastreo_else";

}
//echo "rastreo<br>";
//Excepcion de prefiltros


//MOSTRAR TODOS LOS DATOS AL FILTRAR EN SOLIC DE COTIZACION
IF($c != 'nada' && ($tabla == 'rac_pedidos' || $tabla == 'rac_cotizaciones'))
{
	$strConsulta = str_replace('#FORZARVISTA#', '1', $strConsulta);
}

//die($strConsulta);
//EXCEPCIONES
//anexamos a la consulta las cosideraciones de la sucursal y el tipo de credito


$orderGRC=str_replace("\'", "'", $orderGRC);


if(eregi("id_", $orderGRC))
	$sentidoOr="DESC";

if($variable['Orden'] != ''  && strlen($orderGRC) <= 0)
	$strConsulta.=$variable['Orden'];
	
if(isset($orderGRC))
	$strConsulta.=" ORDER BY ".$orderGRC." $sentidoOr";

	

//solo para las tablas de almacen
/*
if($_SESSION["USR"]->administra_alamacen=='1' && ($tabla == 'spa_almacenes_salidas' || $tabla == 'spa_almacenes_entradas' || $tabla == 'spa_pedidos_sucursal' ))
{
		$strConsulta = str_replace('$SUCURSAL', " SELECT id_sucursal FROM sys_sucursales ", $strConsulta);	
}
else
{*/
	
/*}*/

//EXCEPCIONES DE LOS ALMACENES
	if($tabla =='ad_movimientos_almacen')
	{
		if (isset($stm))
		{
			$G_STIPO_MOVIMIENTO=$stm;
		}
		
		$strConsulta = str_replace('$G_SUB_TIPO_MOVIMIENTO',$G_STIPO_MOVIMIENTO, $strConsulta);
		//mostramos solo los almacenes a los que tiene acceso
		//------------------------
		$strSQLBusc1="SELECT sys_usuarios.id_grupo FROM sys_usuarios left join sys_grupos on sys_grupos.id_grupo= sys_usuarios.id_grupo where id_usuario='".$_SESSION["USR"]->userid ."' Limit 1";
			
		//si el grupo es diferente
		$resValUsr=valBuscador($strSQLBusc1);
		$valor_grupo=$resValUsr[0];
		//$valor_tieneAlmacenes=$resValUsr[1];
		
		$strWhereAlmacenes="";
		if($valor_grupo != 1)
		{
			
			$strWhereAlmacenes= " AND ad_movimientos_almacen.id_almacen in (SELECT id_almacen FROM ad_sucursales where id_sucursal in (SELECT id_sucursal FROM sys_usuarios where id_usuario='".$_SESSION["USR"]->userid ."'))  ";
		}
		//------------------------
		//excepciones de los almacenes  $G_AL_ALMACENES
		$strConsulta = str_replace('$G_AL_ALMACENES',$strWhereAlmacenes, $strConsulta);	
		
		$_SESSION["USR"]->sub_tipo_movimiento=$stm;
		
		//hacemos si el usuario logueado es diferente 
		
	}
	else	if($tabla =='ad_clientes')
	{
		if (isset($stm))
		{
			$strSQLB1="SELECT id_tipo_cliente_proveedor FROM cl_tipos_cliente_proveedor where numero_catalogo =".$stm;
			$resValB1=valBuscador($strSQLB1);
			$tipo_cliente_proveedor=$resValB1[0];
		}
		
		$strConsulta = str_replace('$G_SUB_TIPO_MOVIMIENTO',$tipo_cliente_proveedor, $strConsulta);
		//mostramos solo los almacenes a los que tiene acceso
		
		$_SESSION["USR"]->sub_tipo_movimiento=$stm;
		
		
		$strWhereClientePrincipal= " AND id_cliente= ".$_SESSION["USR"]->cliente_principal_id;
		
		
		$strConsulta = str_replace('$G_CLIENTE_PRINCIPAL',$strWhereClientePrincipal, $strConsulta);
	
		
	}
	else if($tabla == 'ad_facturas'){
		$strWhereClientePrincipal= " AND ad_facturas.id_compania IN(".$_SESSION["USR"]->clientes_relacionados .")";
		$strConsulta = str_replace('$G_CLIENTE_PRINCIPAL',$strWhereClientePrincipal, $strConsulta);
	}else if($tabla == 'ad_pedidos'){
		$strWhereClientePrincipal= " AND ad_pedidos.id_cliente IN(".$_SESSION["USR"]->clientes_relacionados.")";
		$strConsulta = str_replace('$G_CLIENTE_PRINCIPAL',$strWhereClientePrincipal, $strConsulta);
	}elseif($tabla == 'cl_contrarecibos'){
		$strConsulta= "
		SELECT  contrarecibo AS Contrarecibo,cliente 'Distribuidor/Fuerza Propia',plaza Plaza,Fecha, 'Dets' , 'imp' FROM( SELECT id_contrarecibo AS contrarecibo,ad_clientes.id_cliente, CONCAT_WS(' ',ad_clientes.nombre,apellido_paterno,apellido_materno) as cliente,ad_sucursales.nombre AS plaza,fecha_hora Fecha
		FROM cl_contrarecibos
		LEFT JOIN ad_clientes ON cl_contrarecibos.id_cliente=ad_clientes.id_cliente
		LEFT JOIN ad_sucursales	ON ad_sucursales.id_sucursal = cl_contrarecibos.id_sucursal
		WHERE ad_clientes.id_cliente IN (".$_SESSION["USR"]->clientes_relacionados.") AND cl_contrarecibos.id_entidad_financiera = 0 OR cl_contrarecibos.id_entidad_financiera IS NULL) AS datos WHERE 1";
	}else if($tabla == 'ad_facturas_audicel'){
		$strWhereClientePrincipal = " AND ad_facturas_audicel.id_cliente IN(".$_SESSION["USR"] -> cliente_principal_id.")";
		$strConsulta = str_replace('$SUC',$strWhereClientePrincipal, $strConsulta);
	}else if($tabla == 'sys_usuarios'){
		if(isset($prefiltro)){
			if($_SESSION["USR"] -> idGrupo == '1')
				$strConsulta = str_replace('$USR_G',' AND sys_usuarios.id_grupo NOT IN(2,3,4)', $strConsulta);
			else
				$strConsulta = str_replace('$USR_G',' AND sys_usuarios.id_usuario="'.$_SESSION["USR"] -> userid.'"', $strConsulta);
		}
		else{
			if($_SESSION["USR"] -> idGrupo == '1')
				$strConsulta = str_replace('$USR_G','', $strConsulta);
			else
				$strConsulta = str_replace('$USR_G',' AND sys_usuarios.id_usuario="'.$_SESSION["USR"] -> userid.'"', $strConsulta);
		}
	}
$strConsulta = str_replace('$USUARIO', $_SESSION["USR"]->userid, $strConsulta);	
$strConsulta = str_replace('$GRUPO', $_SESSION["USR"]->idGrupo, $strConsulta);	
			
	if($_SESSION["USR"]->sucursalid == 0){
			$strConsulta = str_replace('$SUC', '', $strConsulta);
			$strConsulta = str_replace('$SESIONSUC', '', $strConsulta);
			}
	else{
			$strConsulta = str_replace('$SUC', ' AND ad_pedidos.id_sucursal_alta =' . $_SESSION["USR"] -> sucursalid, $strConsulta);	
			$strConsulta = str_replace('$SESIONSUC', ' AND ad_sucursales.id_sucursal =' . $_SESSION["USR"] -> sucursalid, $strConsulta);	
			}

//Ponemos el inicio y fin que nos marca el grid
if(isset($ini) && isset($fin))
{
	//Conseguimos el n&uacute;mero de datos real
	$resultado=mysql_query($strConsulta) or die("Consulta:\n$strConsulta\n\nDescripcion:\n".mysql_error());
	$numtotal=mysql_num_rows($resultado);	
	
	//A&ntilde;adimos el limit para el paginador
	if($fin!="-1")
		$strConsulta.=" LIMIT $ini, $fin";
}	
//die($strConsulta);
//die("Error en:<br><i>$strConsulta</i><br><br>Descripcion:<br><b>".mysql_error()."</b>");

//echo $strConsulta;
$resultado=mysql_query($strConsulta) or die("Consulta:\n$strConsulta\n\nDescripcion:\n".mysql_error());
$num=mysql_num_rows($resultado);
echo "exito";
for($i=0;$i<$num;$i++)
{
	$row=mysql_fetch_row($resultado);
	echo "|";
	for($j=0;$j<sizeof($row);$j++)
	{	
		if($j > 0)
			echo "~";
		echo utf8_encode($row[$j]);
	}	
}

//Enviamos en el ultimo dato los datos del listado, numero de datos y datos que se muestran
if(isset($ini) && isset($fin))
	echo "|$numtotal~$num";

?>
