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
    <TD WIDTH="200" ALIGN="CENTER"  CLASS="SUBHEADEREVEN"><SPAN CLASS="BOLD">Terminal</SPAN></TD>
    <TD WIDTH="200" ALIGN="CENTER"  CLASS="SUBHEADEREVEN"><SPAN CLASS="BOLD">Documento</SPAN></TD>
    <TD WIDTH="200" ALIGN="CENTER"  CLASS="SUBHEADEREVEN"><SPAN CLASS="BOLD">Confirmado</SPAN></TD>
    <TD WIDTH="200" ALIGN="CENTER"  CLASS="SUBHEADEREVEN"><SPAN CLASS="BOLD">Documento Relacionado</SPAN></TD>
    <TD WIDTH="200" ALIGN="CENTER"  CLASS="SUBHEADEREVEN"><SPAN CLASS="BOLD">Monto Confirmado</SPAN></TD>
    <TD WIDTH="200" ALIGN="CENTER"  CLASS="SUBHEADEREVEN"><SPAN CLASS="BOLD">Monto No Confirmado</SPAN></TD>
    
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
$tClass="TOTALPARCIAL";
$gTotalclass="CELDAGRANTOTAL";
//$tClass="SUBTOTAL";
//$tClass="GRANTOTAL";
while ($aResultado = mysql_fetch_array($result))
{ 	
	if ($contador%2==0){$class="ODD";}else{$class="EVEN";}
	
	if (!isset($sucAnterior))
	{		
		$sucAnterior=$aResultado['id_sucursal'];
	}
	
	$sucActual  = $aResultado['id_sucursal'];		

	$GTConfirmado += $aResultado['monto_confirmado'];	
	$GTNoConfirmado += $aResultado['Monto No Confirmado'];	
	
	
	if ($sucAnterior==$sucActual)
	{
		echo '
		<TR>	
			<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['Sucursal'].'</TD>
			<TD ALIGN="CENTER"CLASS="'.$class.'">'.$aResultado['Fecha'].'</TD>
			<TD ALIGN="RIGHT"  CLASS="'.$class.'">'.$aResultado['Tipo Movimiento'].'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.$aResultado['Forma'].'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.$aResultado['Terminal'].'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.$aResultado['Documento'].'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.$aResultado['Confirmado'].'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.$aResultado['Doc Rel'].'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['monto_confirmado'],2).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['Monto No Confirmado'],2).'</TD>			
		</TR>';
		
		$tSucConfirmado   += $aResultado['monto_confirmado'];
		$tSucNoConfirmado += $aResultado['Monto No Confirmado'];
		

					
		$sucAnterior=$sucActual;			
		$contador++;
					
	}
	else
	{
//		if ($contador%2==0){$class="ODD";}else{$class="EVEN";}
		echo '
		<TR>	
			<TD ALIGN="LEFT"  CLASS="'.$tClass.'"></TD>
			<TD ALIGN="CENTER"CLASS="'.$tClass.'"></TD>
			<TD ALIGN="RIGHT"  CLASS="'.$tClass.'"></TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'"></TD>
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'"></TD>
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'"></TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'"></TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">Total Sucursal</TD>
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tSucConfirmado).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tSucNoConfirmado).'</TD>
		</TR>';
		
//		$contador++;
		
		if ($contador%2==0){$class="ODD";}else{$class="EVEN";}
		
		$tSucConfirmado   = 0;
		$tSucNoConfirmado = 0;

		
		echo '
		<TR>	
			<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['Sucursal'].'</TD>
			<TD ALIGN="CENTER"CLASS="'.$class.'">'.$aResultado['Fecha'].'</TD>
			<TD ALIGN="RIGHT"  CLASS="'.$class.'">'.$aResultado['Tipo Movimiento'].'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.$aResultado['Forma'].'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.$aResultado['Terminal'].'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.$aResultado['Documento'].'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.$aResultado['Confirmado'].'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.$aResultado['Doc Rel'].'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['monto_confirmado'],2).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['Monto No Confirmado'],2).'</TD>			
		</TR>';
		
		$tSucConfirmado   += $aResultado['monto_confirmado'];
		$tSucNoConfirmado += $aResultado['Monto No Confirmado'];

		
		$sucAnterior=$aResultado['id_sucursal'];
		$contador++;
	}	
}
//	if ($contador%2==0){$class="ODD";}else{$class="EVEN";}
	echo'
		<TR>	
			<TD ALIGN="LEFT"  CLASS="'.$tClass.'"></TD>
			<TD ALIGN="CENTER"CLASS="'.$tClass.'"></TD>
			<TD ALIGN="RIGHT"  CLASS="'.$tClass.'"></TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'"></TD>
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'"></TD>
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'"></TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'"></TD>
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">Total Sucursal</TD>


			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tSucConfirmado).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tSucNoConfirmado).'</TD>
		</TR>';
/*		
		echo '
		<TR>	
			<TD ALIGN="LEFT"  CLASS="'.$class.'">&nbsp;</TD>
			<TD ALIGN="CENTER"CLASS="'.$class.'"></TD>
			<TD ALIGN="RIGHT"  CLASS="'.$class.'"></TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$class.'"></TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'"></TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'"></TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$class.'"></TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'"></TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$class.'"></TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'"></TD>
			
		</TR>';
	*/	
		echo '
		<TR>	
			<TD ALIGN="LEFT"  CLASS="'.$gTotalclass.'"></TD>
			<TD ALIGN="CENTER"CLASS="'.$gTotalclass.'"></TD>
			<TD ALIGN="RIGHT"  CLASS="'.$gTotalclass.'"><strong></strong></TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$gTotalclass.'"></TD>
			<TD ALIGN="RIGHT" CLASS="'.$gTotalclass.'"></TD>
			<TD ALIGN="RIGHT" CLASS="'.$gTotalclass.'"></TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$gTotalclass.'"></TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$gTotalclass.'">Saldo al momento</TD>
			<TD ALIGN="RIGHT" CLASS="'.$gTotalclass.'">'.formato_moneda($GTConfirmado).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$gTotalclass.'">'.formato_moneda($GTNoConfirmado).'</TD>
		</TR>';
		
?>
<TR><TD ALIGN="RIGHT" COLSPAN="17" CLASS="EVEN"></TD></TR>
<TR><TD WIDTH="750" ALIGN="LEFT" COLSPAN="22" CLASS="CELDAGRANTOTAL"><SPAN CLASS="BOLD">NÚMERO TOTAL DE REGISTROS: <?php echo $RT  ?></SPAN></TD></TR>
<TR><TD ALIGN="CENTER" COLSPAN="22" CLASS="NUMPAG">Pagina 1</TD></TR>
</TABLE>
</BODY>
</HTML>