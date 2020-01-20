<?php
/*error_reporting(E_ALL);
ini_set("display_errors", 1);*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<TITLE>Corte de caja</TITLE>
<LINK REL="stylesheet" TYPE="text/css" HREF="../../css/reportesPantalla.css" MEDIA="screen">
<STYLE TYPE="text/css">
P.breakhere 
{
	page-break-before:always;border:0px;margin:0px;background:transparent; 
}

</STYLE>
</HEAD>
<BODY BGCOLOR="#FFFFFF">
<TABLE id="pg3" ALIGN="CENTER" CELLPADDING="1" CELLSPACING="1">
    <TR>
        <TD WIDTH="750" COLSPAN="22" CLASS="HEADER"><TABLE BORDER="0" CELLPADDING="2" CELLSPACING="1" WIDTH="100%">
            <TR>
            <TD ROWSPAN="2" CLASS="HEADER" style="background:#FFFFFF; font-size:10px" ALIGN="CENTER" WIDTH="40%">
                <IMG SRC="../../imagenes/sitio_base/tip_logo.png"><BR>
                <b>NASSER</b>
            </TD>
            <TD CLASS="HEADER" WIDTH="60%" ALIGN="CENTER">									
            	<b><U>Corte de Caja:: Reporte Detallado</U></b>																		
            </TD>
            </TR>
        </TABLE>
        </TD>
	</TR>
<TR><TD WIDTH="750" ALIGN="LEFT" COLSPAN="22" CLASS="HEADER"></TD></TR>
<TR><TD WIDTH="700" ALIGN="RIGHT" COLSPAN="22" CLASS="HEADER">Generado el : <?php echo date('d/m/Y H:i:s');?></TD></TR>
<TR><TD WIDTH="750" HEIGHT="10" ALIGN="CENTER" COLSPAN="22" CLASS="ESPACIO"></TD></TR>
<TR><TD WIDTH="750" HEIGHT="15" ALIGN="LEFT" COLSPAN="22">
<BUTTON style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px; font-weight:bolder; background-color:#FFFFFF; color:#094932; border:1px solid #094932;" onClick="window.print();" class="botonImprimir">IMPRIMIR</BUTTON>
</TD>
</TR>
<TR><TD WIDTH="750" HEIGHT="10" ALIGN="CENTER" COLSPAN="22" CLASS="ESPACIO"></TD></TR>
<TR><TD WIDTH="750" HEIGHT="10" ALIGN="CENTER" COLSPAN="22" CLASS="ESPACIO"></TD></TR>
<TR>
    <TD  ALIGN="CENTER" CLASS="SUBHEADEREVEN"><SPAN CLASS="BOLD">SUCURSAL</SPAN></TD>
    <TD WIDTH="90"  ALIGN="CENTER"  CLASS="SUBHEADEREVEN">FECHA</TD>
    <TD WIDTH="200" ALIGN="CENTER"  CLASS="SUBHEADEREVEN"><SPAN CLASS="BOLD">Tipo Movimiento</SPAN></TD>
    <TD WIDTH="200" ALIGN="CENTER"  CLASS="SUBHEADEREVEN"><SPAN CLASS="BOLD">Forma</SPAN></TD>
    <TD WIDTH="200" ALIGN="CENTER"  CLASS="SUBHEADEREVEN"><SPAN CLASS="BOLD">Documento</SPAN></TD>
    <TD WIDTH="200" ALIGN="CENTER"  CLASS="SUBHEADEREVEN"><SPAN CLASS="BOLD">Confirmado</SPAN></TD>
    <TD WIDTH="200" ALIGN="CENTER"  CLASS="SUBHEADEREVEN"><SPAN CLASS="BOLD">Documento Relacionado</SPAN></TD>
    <TD WIDTH="200" ALIGN="CENTER"  CLASS="SUBHEADEREVEN"><SPAN CLASS="BOLD">Monto Confirmado</SPAN></TD>
<TR>
<?PHP 
function formato_moneda($cantidad)
{
	if($cantidad == 0.00)
	{
		$cantidad="&nbsp;";
	}
	else
	{
		$cantidad=number_format($cantidad,2);
	}	
	return $cantidad;
}

//$sql="";
		
$result = mysql_query($strSQL) or die(mysql_error());
$RT=mysql_num_rows($result);
$contador=1;
$totalSucursal=0;
//$tClass="TOTALPARCIAL";
$gTotalclass="CELDAGRANTOTAL";
$tClass="CELDAGRANTOTAL";
//$tClass="GRANTOTAL";

while ($aResultado = mysql_fetch_array($result))
{ 	
	if ($contador%2==0){$class="ODD";}else{$class="EVEN";}
	
	if (!isset($sucAnterior))
	{		
		/*******SALDO ANTERIOR DE CAJA CHICA*******/
		
		
		
		if($fechadel != ''){
			//echo $fechadel;
			 $vFechadel=$fechadel;
			//convertDate($fechaal) 
		
		$sql = "SELECT SUM(monto) AS ingresos 
				FROM ad_ingresos_caja_chica 
				WHERE id_sucursal = " . $aResultado['id_sucursal'] . " AND confirmado = 1 AND fecha_ingreso < '" . $vFechadel."'";
		
		//echo $sql;
		
		$datos = new consultarTabla($sql);
		$ingresos = $datos -> obtenerLineaRegistro();
			
		//echo $ingresos['ingresos'];
						
		$sql2 = "SELECT SUM(total) AS egresos 
				FROM ad_egresos_caja_chica 
				WHERE id_sucursal = " . $aResultado['id_sucursal'] . " AND ad_egresos_caja_chica.fecha  < '" . $vFechadel."'";
		$datos2 = new consultarTabla($sql2);
		$egresos = $datos2 -> obtenerLineaRegistro();
							
							
							
		$ingresos['ingresos'] = $ingresos['ingresos'] = "" ? 0 : $ingresos['ingresos'];
		$egresos['egresos'] = $egresos['egresos'] = "" ? 0 : $egresos['egresos'];
							
		$total_caja = $ingresos['ingresos'] - $egresos['egresos'];
		/*****ENCABEZADO DE SALDO ANTERIOR*****/
		echo '
				<TR>	
					<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['Sucursal'].'</TD>
					<TD ALIGN="CENTER"CLASS="'.$class.'">&nbsp;</TD>
					<TD ALIGN="RIGHT"  CLASS="'.$class.'">SALDO INICIAL</TD>
					
					<TD ALIGN="RIGHT" CLASS="'.$class.'">&nbsp;</TD>
					<TD ALIGN="RIGHT" CLASS="'.$class.'">&nbsp;</TD>
					<TD ALIGN="RIGHT" CLASS="'.$class.'">&nbsp;</TD>
					
					<TD ALIGN="RIGHT" CLASS="'.$class.'">&nbsp;</TD>
					
					<TD ALIGN="RIGHT" CLASS="'.$class.'">' . formato_moneda($total_caja) . '</TD>
					<TD ALIGN="RIGHT" CLASS="'.$class.'">&nbsp;</TD>			
				</TR>
					';
				}
		$sucAnterior=$aResultado['id_sucursal'];
	}
	
	$sucActual  = $aResultado['id_sucursal'];		
	
	if($aResultado['Tipo Movimiento'] == "ENTRADA")
				$GTConfirmado += $aResultado['monto_confirmado'];
	else if($aResultado['Tipo Movimiento'] == "SALIDA")	
				$GTConfirmado -= $aResultado['monto_confirmado'];
			
	$GTNoConfirmado += $aResultado['Monto No Confirmado'];	
	
	
	
	if ($sucAnterior==$sucActual)
	{
		echo '
		<TR>	
			<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['Sucursal'].'</TD>
			<TD ALIGN="CENTER"CLASS="'.$class.'">'.$aResultado['Fecha'].'</TD>
			<TD ALIGN="RIGHT"  CLASS="'.$class.'">'.$aResultado['Tipo Movimiento'].'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.$aResultado['Forma'].'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.$aResultado['Documento'].'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.$aResultado['Confirmado'].'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.$aResultado['Doc Rel'].'</TD>
			';
	$signo = "";
	if($aResultado['Tipo Movimiento'] == "SALIDA")
			$signo .= "-";
	
	echo '
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.$signo. formato_moneda($aResultado['monto_confirmado'],2).'</TD>	
		</TR>';
		
		if($aResultado['Tipo Movimiento'] == "ENTRADA")
				$tSucConfirmado += $aResultado['monto_confirmado'];
		else if($aResultado['Tipo Movimiento'] == "SALIDA")	
				$tSucConfirmado -= $aResultado['monto_confirmado'];
					
		$sucAnterior=$sucActual;			
		$contador++;
					
	}
	else
	{
//		if ($contador%2==0){$class="ODD";}else{$class="EVEN";}
		echo '
		
		
		<TR>	
			<TD COLSPAN="7" ALIGN="RIGHT" CLASS="'.$tClass.' MONTOSTOTALES">Total Sucursal</TD>
			<TD ALIGN="RIGHT" CLASS="'.$tClass.' MONTOSTOTALES">'.formato_moneda($tSucConfirmado).'</TD>
		</TR>';
		
//		$contador++;
		
		if ($contador%2==0){$class="ODD";}else{$class="EVEN";}
		
		$tSucConfirmado   = 0;
		$tSucNoConfirmado = 0;
		/*****VALIDACION DE SALDO ANTERIOR*****/
		if($fechadel != '' ){
			 $vFechadel=$fechadel;
		$sql = "SELECT SUM(monto) AS ingresos 
				FROM ad_ingresos_caja_chica 
				WHERE id_sucursal = " . $aResultado['id_sucursal'] . " AND confirmado = 1 AND fecha_ingreso < '" . $vFechadel."'";
		$datos = new consultarTabla($sql);
		$ingresos = $datos -> obtenerLineaRegistro();
							
		$sql2 = "SELECT SUM(total) AS egresos 
				FROM ad_egresos_caja_chica 
				WHERE id_sucursal = " . $aResultado['id_sucursal'] . " AND ad_egresos_caja_chica.fecha <  '" . $vFechadel."'";
		$datos2 = new consultarTabla($sql2);
		$egresos = $datos2 -> obtenerLineaRegistro();
							
		$ingresos['ingresos'] = $ingresos['ingresos'] = "" ? 0 : $ingresos['ingresos'];
		$egresos['egresos'] = $egresos['egresos'] = "" ? 0 : $egresos['egresos'];
							
		$total_caja = $ingresos['ingresos'] - $egresos['egresos'];
		/*****ENCABEZADO DE SALDO ANTERIOR*****/
		
				echo '
				<TR>	
					<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['Sucursal'].'</TD>
					<TD ALIGN="CENTER"CLASS="'.$class.'">&nbsp;</TD>
					<TD ALIGN="RIGHT"  CLASS="'.$class.'">SALDO INICIAL</TD>
					
					<TD ALIGN="RIGHT" CLASS="'.$class.'">&nbsp;</TD>
					<TD ALIGN="RIGHT" CLASS="'.$class.'">&nbsp;</TD>
					<TD ALIGN="RIGHT" CLASS="'.$class.'">&nbsp;</TD>
					
					<TD ALIGN="RIGHT" CLASS="'.$class.'">&nbsp;</TD>
					
					<TD ALIGN="RIGHT" CLASS="'.$class.'">' . formato_moneda($total_caja) . '</TD>		
				</TR>';
				}
		echo '
		<TR>	
			<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['Sucursal'].'</TD>
			<TD ALIGN="CENTER"CLASS="'.$class.'">'.$aResultado['Fecha'].'</TD>
			<TD ALIGN="RIGHT"  CLASS="'.$class.'">'.$aResultado['Tipo Movimiento'].'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.$aResultado['Forma'].'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.$aResultado['Documento'].'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.$aResultado['Confirmado'].'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.$aResultado['Doc Rel'].'</TD>
			';
		$signo = "";
		if($aResultado['Tipo Movimiento'] == "SALIDA")
				$signo .= "-";
		echo '
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.$signo . formato_moneda($aResultado['monto_confirmado'],2).'</TD>	
		</TR>';
		if($aResultado['Tipo Movimiento'] == "ENTRADA")
				$tSucConfirmado += $aResultado['monto_confirmado'];
		else if($aResultado['Tipo Movimiento'] == "SALIDA")	
				$tSucConfirmado -= $aResultado['monto_confirmado'];
			
		$tSucNoConfirmado += $aResultado['Monto No Confirmado'];

		
		$sucAnterior=$aResultado['id_sucursal'];
		$contador++;
	}	
}
//	if ($contador%2==0){$class="ODD";}else{$class="EVEN";}
	echo'
		<TR>	
			<TD ALIGN="RIGHT" COLSPAN="7" CLASS="'.$tClass.' MONTOSTOTALES">Total Sucursal</TD>
			<TD ALIGN="RIGHT" CLASS="'.$tClass.' MONTOSTOTALES">'.formato_moneda($tSucConfirmado).'</TD>
		</TR>';

		echo '
		<TR>
				<TD COLSPAN="8">&nbsp;</TD>
		</TR>
		<TR>	
			<TD COLSPAN="7" ALIGN="RIGHT" CLASS="'.$gTotalclass.' GRANTOTAL">Saldo al momento</TD>
			<TD ALIGN="RIGHT" CLASS="'.$gTotalclass.' GRANTOTAL">'.formato_moneda($GTConfirmado).'</TD>
		</TR>';
		
?>
<TR><TD ALIGN="RIGHT" COLSPAN="17" CLASS="EVEN"></TD></TR>
<TR><TD WIDTH="750" ALIGN="LEFT" COLSPAN="22" CLASS="CELDAGRANTOTAL"><SPAN CLASS="BOLD">NÚMERO TOTAL DE REGISTROS: <?php echo $RT  ?></SPAN></TD></TR>
<TR><TD ALIGN="CENTER" COLSPAN="22" CLASS="NUMPAG">Pagina 1</TD></TR>
</TABLE>
</BODY>
</HTML>