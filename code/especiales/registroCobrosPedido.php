<?php

include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");

	
//	echo "Sucursal ".$_SESSION["USR"]->sucursalname;
//	echo "ID susuario ".$_SESSION["USR"]->userid;
	$fecha=date("d/m/Y");
	
	/*
	 * Formas de Pago
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
	 * Terminal Bancaria
	 */
	 
	$strSQL = "SELECT id_terminal_bancaria, nombre 
				 FROM na_terminales_bancarias 
				where activo=1
				  and id_sucursal=".$_SESSION["USR"]->sucursalid." 
			 order by nombre;";		
	$rs = mysql_query($strSQL) or die("Error en consulta $strSQL\nDescripcion:".mysql_error());	
	$arrAuxId = array();
	$arrAuxDesc = array();			
	while($row = mysql_fetch_assoc($rs)){			
		$arrAuxId[] = $row['id_terminal_bancaria'];
		$arrAuxDesc[] = $row['nombre'];			
	}	
	$smarty-> assign('terminal_bancaria_id', $arrAuxId);
	$smarty-> assign('terminal_bancaria_nombre',$arrAuxDesc);
	/*
	 * Banco
	 */
	 
	$strSQL = "SELECT id_banco, nombre FROM na_bancos WHERE activo = 1";		
	$rs = mysql_query($strSQL) or die("Error en consulta $strSQL\nDescripcion:".mysql_error());	
	$arrAuxId = array();
	$arrAuxDesc = array();			
	while($row = mysql_fetch_assoc($rs)){			
		$arrAuxId[] = $row['id_banco'];
		$arrAuxDesc[] = $row['nombre'];			
	}	
	$smarty-> assign('banco_id', $arrAuxId);
	$smarty-> assign('banco_nombre',$arrAuxDesc);
	/*
	 * Sucursales
	 */			
	if($_SESSION["USR"]->sucursalid == 0)
			$strSQL = "SELECT id_sucursal, nombre AS 'nombre' FROM na_sucursales WHERE id_sucursal <> 0";		
	else
			$strSQL = "SELECT id_sucursal, nombre AS 'nombre' FROM na_sucursales WHERE id_sucursal = " . $_SESSION["USR"]->sucursalid;		
			
	$rs = mysql_query($strSQL) or die("Error en consulta $strSQL\nDescripcion:".mysql_error());		  	
	$arrAuxId = array();
	$arrAuxDesc = array();		
	while($row = mysql_fetch_assoc($rs)){		
		$arrAuxId[] = $row['id_sucursal'];
		$arrAuxDesc[] = $row['nombre'];			
	}
		
	$smarty->assign('sucursal_id', $arrAuxId);
	$smarty->assign('sucursal_nombre',$arrAuxDesc);		
	
	/****************************************************/
			
	$smarty-> assign('fecha',$fecha);
	$smarty-> assign('titulo','Registro de Cobros a Pedidos');
	$smarty-> assign('usuario_id', $_SESSION["USR"]->userid);
	$smarty ->display("especiales/registroCobrosPedido.tpl");
?>