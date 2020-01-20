<?php
	/*
	$strConsulta="SELECT porcentaje_iva FROM sys_parametros_configuracion WHERE activo='1'";
	$res=mysql_query($strConsulta) or die("Error en:<br><i>$strConsulta</i><br><br>Descripcion:<b>".mysql_error());
	$row=mysql_fetch_row($res);
	$smarty->assign("porcentaje_iva",$row[0]);
	*/

	//convierte la fecha a formato aaaa-mm-dd
	function FormatoFecha($fecha){
		$getFech = explode("/",$fecha);
		$newFecha =  $getFech[2]."-".$getFech[1]."-".$getFech[0];
		return $newFecha;
	}
?>
