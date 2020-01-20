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
            	<b><U>Corte de Caja</U></b>																		
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
    <TD rowspan="2" ALIGN="LEFT" CLASS="SUBHEADEREVEN"><SPAN CLASS="BOLD">SUCURSAL</SPAN></TD>
    <TD WIDTH="90" rowspan="2" ALIGN="LEFT" CLASS="SUBHEADEREVEN">FECHA</TD>
    <TD WIDTH="200" rowspan="2" ALIGN="LEFT" CLASS="SUBHEADEREVEN"><SPAN CLASS="BOLD">MONTO TOTAL</SPAN></TD>
    <TD WIDTH="200" rowspan="2" ALIGN="LEFT" CLASS="SUBHEADEREVEN"><SPAN CLASS="BOLD">EFECTIVO</SPAN></TD>
    <TD WIDTH="200" rowspan="2" ALIGN="LEFT" CLASS="SUBHEADEREVEN"><SPAN CLASS="BOLD">NOTA CREDITO</SPAN></TD>
    <TD WIDTH="200" rowspan="2" ALIGN="LEFT" CLASS="SUBHEADEREVEN"><SPAN CLASS="BOLD">VALE PRODUCTO</SPAN></TD>
    <TD WIDTH="200" colspan="2" ALIGN="LEFT" CLASS="SUBHEADEREVEN">SCOTIABANK INVERLAT</TD>
    <TD WIDTH="200" colspan="2" ALIGN="LEFT" CLASS="SUBHEADEREVEN">HSBC MÉXICO</TD>
    <TD WIDTH="200" colspan="2" ALIGN="LEFT" CLASS="SUBHEADEREVEN">BANAMEX </TD>
    <TD WIDTH="200" colspan="2" ALIGN="LEFT" CLASS="SUBHEADEREVEN">BANCOMER</TD>
    <TD WIDTH="200" colspan="2" ALIGN="LEFT" CLASS="SUBHEADEREVEN">BANORTE</TD>
    <TD WIDTH="200" colspan="2" ALIGN="LEFT" CLASS="SUBHEADEREVEN">TRANSFERENCIA</TD>
    <TD WIDTH="200" colspan="2" ALIGN="LEFT" CLASS="SUBHEADEREVEN">CHEQUE</TD>
    <TD WIDTH="200" colspan="2" ALIGN="LEFT" CLASS="SUBHEADEREVEN">DEPOSITO</TD>

</TR>
<TR>
  <TD ALIGN="LEFT" CLASS="SUBHEADEREVEN">CONFIRMADO</TD>
  <TD ALIGN="LEFT" CLASS="SUBHEADEREVEN">NO CONFIRMADO</TD>
  <TD ALIGN="LEFT" CLASS="SUBHEADEREVEN">CONFIRMADO</TD>
  <TD ALIGN="LEFT" CLASS="SUBHEADEREVEN">NO CONFIRMADO</TD>
  <TD ALIGN="LEFT" CLASS="SUBHEADEREVEN">CONFIRMADO</TD>
  <TD ALIGN="LEFT" CLASS="SUBHEADEREVEN">NO CONFIRMADO</TD>
  <TD ALIGN="LEFT" CLASS="SUBHEADEREVEN">CONFIRMADO</TD>
  <TD ALIGN="LEFT" CLASS="SUBHEADEREVEN">NO CONFIRMADO</TD>
  <TD ALIGN="LEFT" CLASS="SUBHEADEREVEN">CONFIRMADO</TD>
  <TD ALIGN="LEFT" CLASS="SUBHEADEREVEN">NO CONFIRMADO</TD>
  <TD ALIGN="LEFT" CLASS="SUBHEADEREVEN">CONFIRMADO</TD>
  <TD ALIGN="LEFT" CLASS="SUBHEADEREVEN">NO CONFIRMADO</TD>
  <TD ALIGN="LEFT" CLASS="SUBHEADEREVEN">CONFIRMADO</TD>
  <TD ALIGN="LEFT" CLASS="SUBHEADEREVEN">NO CONFIRMADO</TD>
  <TD ALIGN="LEFT" CLASS="SUBHEADEREVEN">CONFIRMADO</TD>
  <TD ALIGN="LEFT" CLASS="SUBHEADEREVEN">NO CONFIRMADO</TD>
</TR>
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

$sql=" SELECT na_reporte_auxiliar.id_sucursal,na_sucursales.nombre as sucursal, fecha, total, 
			  a, b, c,  h1, h2, i1, i2, j1, j2, k1, k2, l1, l2, q1, q2, r1, r2, s1, s2
		 FROM na_reporte_auxiliar
	LEFT JOIN na_sucursales on na_reporte_auxiliar.id_sucursal=na_sucursales.id_sucursal
		WHERE token=$token";
		
$result = mysql_query($sql) or die(mysql_error());
$RT=mysql_num_rows($result);
$contador=1;
$totalSucursal=0;
//$tClass="TOTALPARCIAL";
$gTotalclass="CELDAGRANTOTAL";
//$tClass="SUBTOTAL";
$tClass="CELDAGRANTOTAL";
while ($aResultado = mysql_fetch_array($result))
{ 	
	if ($contador%2==0){$class="ODD";}else{$class="EVEN";}
	
	if (!isset($sucAnterior))
	{		
		$sucAnterior=$aResultado['id_sucursal'];
	}
	
	$sucActual  = $aResultado['id_sucursal'];		

	$granTotal += $aResultado['total'];	
	
	$GTA  += $aResultado['a'];
	$GTB  += $aResultado['b'];
	$GTC  += $aResultado['c'];
	$GTH1 += $aResultado['h1'];
	$GTH2 += $aResultado['h2'];
	$GTI1 += $aResultado['i1'];
	$GTI2 += $aResultado['i2'];
	$GTJ1 += $aResultado['j1'];
	$GTJ2 += $aResultado['j2'];
	$GTK1 += $aResultado['k1'];
	$GTK2 += $aResultado['k2'];
	$GTL1 += $aResultado['l1'];
	$GTL2 += $aResultado['l2'];
	$GTQ1 += $aResultado['q1'];
	$GTQ2 += $aResultado['q2'];
	$GTR1 += $aResultado['r1'];
	$GTR2 += $aResultado['r2'];
	$GTS1 += $aResultado['s1'];
	$GTS2 += $aResultado['s2'];
	
	if ($sucAnterior==$sucActual)
	{
		echo '
		<TR>	
			<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['sucursal'].'</TD>
			<TD ALIGN="CENTER"CLASS="'.$class.'">'.date("d/m/Y",strtotime($aResultado['fecha'])).'</TD>
			<TD ALIGN="RIGHT"  CLASS="'.$class.'">'.formato_moneda($aResultado['total'],2).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['a'],2).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['b'],2).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['c'],2).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['h1'],2).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['h2'],2).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['i1'],2).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['i2'],2).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['j1'],2).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['j2'],2).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['k1'],2).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['k2'],2).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['l1'],2).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['l2'],2).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['q1'],2).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['q2'],2).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['r1'],2).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['r2'],2).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['s1'],2).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['s2'],2).'</TD>
		</TR>';
		
		$tSucursal  += $aResultado['total'];
		
		$tA  = $tA  + $aResultado['a'];
		$tB  = $tB  + $aResultado['b'];
		$tC  = $tC  + $aResultado['c'];
		$tH1 = $tH1 + $aResultado['h1'];
		$tH2 = $tH2 + $aResultado['h2'];
		$tI1 = $tI1 + $aResultado['i1'];
		$tI2 = $tI2 + $aResultado['i2'];
		$tJ1 = $tJ1 + $aResultado['j1'];
		$tJ2 = $tJ2 + $aResultado['j2'];
		$tK1 = $tK1 + $aResultado['k1'];
		$tK2 = $tK2 + $aResultado['k2'];
		$tL1 = $tL1 + $aResultado['l1'];
		$tL2 = $tL2 + $aResultado['l2'];
		$tQ1 = $tQ1 + $aResultado['q1'];
		$tQ2 = $tQ2 + $aResultado['q2'];
		$tR1 = $tR1 + $aResultado['r1'];
		$tR2 = $tR2 + $aResultado['r2'];
		$tS1 = $tS1 + $aResultado['s1'];
		$tS2 = $tS2 + $aResultado['s2'];
				
		$sucAnterior=$sucActual;			
		$contador++;
					
	}
	else
	{
//		if ($contador%2==0){$class="ODD";}else{$class="EVEN";}
		echo '
		<TR>	
			<TD ALIGN="LEFT"  CLASS="'.$tClass.'"></TD>
			<TD ALIGN="CENTER"CLASS="'.$tClass.'">SUBTOTAL</TD>
			<TD ALIGN="RIGHT"  CLASS="'.$tClass.'">'.formato_moneda($tSucursal).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tA).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tB).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tC).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tH1).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tH2).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tI1).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tI2).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tJ1).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tJ2).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tK1).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tK2).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tL1).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tL2).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tQ1).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tQ2).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tR1).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tR2).'</TD>		      
			
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tS1).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tS2).'</TD>
		</TR>';
		
//		$contador++;
		
		if ($contador%2==0){$class="ODD";}else{$class="EVEN";}
		
	
		$tSucursal=0;
				
		$tA  = 0;
		$tB  = 0;
		$tC  = 0;
		$tH1 = 0;
		$tH2 = 0;
		$tI1 = 0;
		$tI2 = 0;
		$tJ1 = 0;
		$tJ2 = 0;
		$tK1 = 0;
		$tK2 = 0;
		$tL1 = 0;
		$tL2 = 0;
		$tQ1 = 0;
		$tQ2 = 0;
		$tR1 = 0;
		$tR2 = 0;
		$tS1 = 0;
		$tS2 = 0;		
		
		echo '
		<TR>	
			<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['sucursal'].'</TD>
			<TD ALIGN="CENTER"CLASS="'.$class.'">'.date("d-m-Y",strtotime($aResultado['fecha'])).'</TD>
			<TD ALIGN="RIGHT"  CLASS="'.$class.'">'.formato_moneda($aResultado['total'],2).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['a'],2).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['b'],2).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['c'],2).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['h1'],2).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['h2'],2).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['i1'],2).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['i2'],2).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['j1'],2).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['j2'],2).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['k1'],2).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['k2'],2).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['l1'],2).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['l2'],2).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['q1'],2).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['q2'],2).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['r1'],2).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['r2'],2).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['s1'],2).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'">'.formato_moneda($aResultado['s2'],2).'</TD>
		</TR>';
		
		$tSucursal += $aResultado['total'];

		$tA  = $tA  + $aResultado['a'];
		$tB  = $tB  + $aResultado['b'];
		$tC  = $tC  + $aResultado['c'];
		$tH1 = $tH1 + $aResultado['h1'];
		$tH2 = $tH2 + $aResultado['h2'];
		$tI1 = $tI1 + $aResultado['i1'];
		$tI2 = $tI2 + $aResultado['i2'];
		$tJ1 = $tJ1 + $aResultado['j1'];
		$tJ2 = $tJ2 + $aResultado['j2'];
		$tK1 = $tK1 + $aResultado['k1'];
		$tK2 = $tK2 + $aResultado['k2'];
		$tL1 = $tL1 + $aResultado['l1'];
		$tL2 = $tL2 + $aResultado['l2'];
		$tQ1 = $tQ1 + $aResultado['q1'];
		$tQ2 = $tQ2 + $aResultado['q2'];
		$tR1 = $tR1 + $aResultado['r1'];
		$tR2 = $tR2 + $aResultado['r2'];
		$tS1 = $tS1 + $aResultado['s1'];
		$tS2 = $tS2 + $aResultado['s2'];
		
		$sucAnterior=$aResultado['id_sucursal'];
		$contador++;
	}	
}
//	if ($contador%2==0){$class="ODD";}else{$class="EVEN";}
	echo'
		<TR>	
			<TD ALIGN="LEFT"  CLASS="'.$tClass.'"></TD>
			<TD ALIGN="CENTER"CLASS="'.$tClass.'">SUBTOTAL</TD>
			<TD ALIGN="RIGHT"  CLASS="'.$tClass.'">'.formato_moneda($tSucursal).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tA).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tB).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tC).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tH1).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tH2).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tI1).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tI2).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tJ1).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tJ2).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tK1).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tK2).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tL1).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tL2).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tQ1).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tQ2).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tR1).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tR2).'</TD>		      
			
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tS1).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$tClass.'">'.formato_moneda($tS2).'</TD>
		</TR>';
		
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
			
			<TD ALIGN="RIGHT" CLASS="'.$class.'"></TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'"></TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$class.'"></TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'"></TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$class.'"></TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'"></TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$class.'"></TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'"></TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$class.'"></TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'"></TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$class.'"></TD>
			<TD ALIGN="RIGHT" CLASS="'.$class.'"></TD>
		</TR>';
		
		echo '
		<TR>	
			<TD ALIGN="LEFT"  CLASS="'.$gTotalclass.'"></TD>
			<TD ALIGN="CENTER"CLASS="'.$gTotalclass.'">GRANTOTAL</TD>
			<TD ALIGN="RIGHT"  CLASS="'.$gTotalclass.'"><strong>'.formato_moneda($granTotal).'</strong></TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$gTotalclass.'">'.formato_moneda($GTA).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$gTotalclass.'">'.formato_moneda($GTB).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$gTotalclass.'">'.formato_moneda($GTC).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$gTotalclass.'">'.formato_moneda($GTH1).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$gTotalclass.'">'.formato_moneda($GTH2).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$gTotalclass.'">'.formato_moneda($GTI1).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$gTotalclass.'">'.formato_moneda($GTI2).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$gTotalclass.'">'.formato_moneda($GTJ1).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$gTotalclass.'">'.formato_moneda($GTJ2).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$gTotalclass.'">'.formato_moneda($GTK1).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$gTotalclass.'">'.formato_moneda($GTK2).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$gTotalclass.'">'.formato_moneda($GTL1).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$gTotalclass.'">'.formato_moneda($GTL2).'</TD>
	
			<TD ALIGN="RIGHT" CLASS="'.$gTotalclass.'">'.formato_moneda($GTQ1).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$gTotalclass.'">'.formato_moneda($GTQ2).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$gTotalclass.'">'.formato_moneda($GTR1).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$gTotalclass.'">'.formato_moneda($GTR2).'</TD>
			
			<TD ALIGN="RIGHT" CLASS="'.$gTotalclass.'">'.formato_moneda($GTS1).'</TD>
			<TD ALIGN="RIGHT" CLASS="'.$gTotalclass.'">'.formato_moneda($GTS2).'</TD>
		</TR>';
		
?>
<TR><TD ALIGN="RIGHT" COLSPAN="17" CLASS="EVEN"></TD></TR>
<TR><TD WIDTH="750" ALIGN="LEFT" COLSPAN="22" CLASS="CELDAGRANTOTAL"><SPAN CLASS="BOLD">NÚMERO TOTAL DE REGISTROS: <?php echo $RT  ?></SPAN></TD></TR>
<TR><TD ALIGN="CENTER" COLSPAN="22" CLASS="NUMPAG">Pagina 1</TD></TR>
</TABLE>
</BODY>
</HTML>