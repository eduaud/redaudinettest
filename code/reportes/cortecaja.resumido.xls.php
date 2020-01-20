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

$sql=" SELECT na_reporte_auxiliar.id_sucursal,na_sucursales.nombre as sucursal, fecha, total, 
			  a, b, c,  h1, h2, i1, i2, j1, j2, k1, k2, l1, l2, q1, q2, r1, r2, s1, s2
		 FROM na_reporte_auxiliar
	LEFT JOIN na_sucursales on na_reporte_auxiliar.id_sucursal=na_sucursales.id_sucursal
		WHERE token=$token";
		
$result = mysql_query($sql) or die(mysql_error());
$RT=mysql_num_rows($result);
$contador=1;
while ($aResultado = mysql_fetch_array($result))
{ 
	if ($contador%2==0){$class="ODD";}else{$class="EVEN";}
	
	echo '
	<TR>	
		<TD ALIGN="CENTER"CLASS="'.$class.'">'.$aResultado['sucursal'].'</TD>
		<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['fecha'].'</TD>
		<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['total'].'</TD>
		
		<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['a'].'</TD>
		<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['b'].'</TD>
		<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['c'].'</TD>
		
		<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['h1'].'</TD>
		<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['h2'].'</TD>
		
		<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['i1'].'</TD>
		<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['i2'].'</TD>
		
		<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['j1'].'</TD>
		<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['j2'].'</TD>
		
		<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['k1'].'</TD>
		<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['k2'].'</TD>
		
		<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['l1'].'</TD>
		<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['l2'].'</TD>
		
		<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['q1'].'</TD>
		<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['q2'].'</TD>
		
		<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['r1'].'</TD>
		<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['r2'].'</TD>
		
		<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['s1'].'</TD>
		<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['s2'].'</TD>
	</TR>';
	$contador++;
}
?>
<TR><TD ALIGN="RIGHT" COLSPAN="17" CLASS="EVEN"></TD></TR>
<TR><TD WIDTH="750" ALIGN="LEFT" COLSPAN="22" CLASS="CELDAGRANTOTAL"><SPAN CLASS="BOLD">NÚMERO TOTAL DE REGISTROS: <?php echo $RT ?></SPAN></TD></TR>
<TR><TD ALIGN="CENTER" COLSPAN="22" CLASS="NUMPAG">Pagina 1</TD></TR>
</TABLE>
