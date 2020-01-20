<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">

<?php

extract($_GET);

if($opcion == 2){
    
    ob_start();
    
}

?>


<HTML>
	<HEAD>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<TITLE>Estado de Cuenta</TITLE>
	<LINK REL="stylesheet" TYPE="text/css" HREF="../../css/reportesPantalla.css" MEDIA="screen">
	<STYLE TYPE="text/css">
		P.breakhere {
			page-break-before: always; border : 0px; margin : 0px; background : transparent; 
		}
	</STYLE>
	</HEAD>
	<BODY BGCOLOR="#FFFFFF">
		<TABLE id="pg3" ALIGN="CENTER" CELLPADDING="1" CELLSPACING="1">
		    <TR>
		        <TD WIDTH="1333" COLSPAN="22" CLASS="HEADER">
		        	<TABLE BORDER="0" CELLPADDING="2" CELLSPACING="1" WIDTH="100%">
			            <TR>
				            <TD ROWSPAN="2" CLASS="HEADER" style="background:#FFFFFF; font-size:10px" ALIGN="CENTER" WIDTH="40%">
				                <IMG SRC="../../imagenes/audicel.png"><BR>
				                <b>AUDICEL</b>
				            </TD>
				            <TD CLASS="HEADER" WIDTH="60%" ALIGN="CENTER">									
				            	<b><U>Estado de Cuenta</U></b>																		
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
		</TABLE>
		<DIV STYLE="WIDTH: 1333px; HEIGHT: AUTO;">
			<DIV STYLE="WIDTH: 444px; FLOAT: LEFT">
				<TABLE id="pg3" STYLE="margin-top: 0px" CELLPADDING="1" CELLSPACING="1">
					<TR>
						<TD WIDTH="750" HEIGHT="10" ALIGN="CENTER" COLSPAN="5">FACTURAS EMITIDAS A DISTRIBUIDOR</TD>
					</TR>
					<TR>
						<TD WIDTH="250" ALIGN="CENTER" CLASS="SUBHEADEREVEN"><SPAN CLASS="BOLD">FECHA DE FACTURACION</SPAN></TD>
					    <TD WIDTH="200" ALIGN="CENTER" CLASS="SUBHEADEREVEN"><SPAN CLASS="BOLD">NUMERO DE FACTURA</SPAN></TD>
					    <TD WIDTH="200" ALIGN="CENTER" CLASS="SUBHEADEREVEN"><SPAN CLASS="BOLD">IMPORTE</SPAN></TD>
					    <TD WIDTH="200" ALIGN="CENTER" CLASS="SUBHEADEREVEN"><SPAN CLASS="BOLD">SALDO</SPAN></TD>
					    <TD WIDTH="300" ALIGN="CENTER" CLASS="SUBHEADEREVEN"><SPAN CLASS="BOLD">CONCEPTO</SPAN></TD>
					</TR>
					<?php
						$resultFacturas = mysql_query($strSQLFacturas) or die(mysql_error());
						$RT = mysql_num_rows($resultFacturas);
						$contador = 1;
						$totalFacturas = 0;
						$tClass = "TOTALPARCIAL";
						$gTotalclass="CELDAGRANTOTAL";
						//$tClass="SUBTOTAL";
						//$tClass="GRANTOTAL";
						while ($aResultadoFacturas = mysql_fetch_array($resultFacturas)) { 	
							if ($contador%2 == 0){ $class = "ODD"; } else{ $class = "EVEN"; }
							echo '
								<TR>	
									<TD ALIGN="LEFT"  CLASS="'.$class.'">' . $aResultadoFacturas['fecha_factura'] . '</TD>		
									<TD ALIGN="LEFT"  CLASS="'.$class.'">' . $aResultadoFacturas['factura'] . '</TD>		
									<TD ALIGN="RIGHT"  CLASS="'.$class.'">' . $aResultadoFacturas['total'] . '</TD>		
									<TD ALIGN="RIGHT"  CLASS="'.$class.'">' . $aResultadoFacturas['saldo_factura'] . '</TD>		
									<TD ALIGN="LEFT"  CLASS="'.$class.'">' . $aResultadoFacturas['concepto'] . '</TD>		
								</TR>';
							$contador += 1;
							$totalFacturas += $aResultadoFacturas['saldo_factura_sin_formato'];
						}
						if($contador > 1) {
							echo '
								<TR>	
									<TD ALIGN="CENTER" CLASS="SUBTOTAL" COLSPAN="3">TOTAL</TD>		
									<TD ALIGN="RIGHT"  CLASS="SUBTOTAL">$' . number_format($totalFacturas, 2, '.', ',') . '</TD>		
									<TD ALIGN="CENTER" CLASS="SUBTOTAL">&nbsp;</TD>
								</TR>';
						}
					?>
				</TABLE>
			</DIV>
			<DIV STYLE="WIDTH: 444px; FLOAT: LEFT">
				<TABLE id="pg3" STYLE="margin-top: 0px" CELLPADDING="1" CELLSPACING="1">
					<TR>
						<TD WIDTH="750" HEIGHT="10" ALIGN="CENTER" COLSPAN="6">CXC DESCUENTOS POR ACTIVACION</TD>
					</TR>
					<TR>
						<TD WIDTH="250" ALIGN="CENTER"  CLASS="SUBHEADEREVEN"><SPAN CLASS="BOLD">FECHA DE ACTIVACION</SPAN></TD>
					    <TD WIDTH="200" ALIGN="CENTER"  CLASS="SUBHEADEREVEN"><SPAN CLASS="BOLD">NUMERO DE CONTRATO</SPAN></TD>
					    <TD WIDTH="200" ALIGN="CENTER"  CLASS="SUBHEADEREVEN"><SPAN CLASS="BOLD">IMPORTE</SPAN></TD>
					    <TD WIDTH="200" ALIGN="CENTER"  CLASS="SUBHEADEREVEN"><SPAN CLASS="BOLD">SALDO</SPAN></TD>
					    <TD WIDTH="200" ALIGN="CENTER"  CLASS="SUBHEADEREVEN"><SPAN CLASS="BOLD">TIPO</SPAN></TD>
					</TR>
					<?php
						$resultCXC = mysql_query($strSQLCXC) or die(mysql_error());
						$RT = mysql_num_rows($resultCXC);
						$contador = 1;
						$totalCxC = 0;
						$tClass = "TOTALPARCIAL";
						$gTotalclass = "CELDAGRANTOTAL";
						//$tClass = "SUBTOTAL";
						//$tClass = "GRANTOTAL";
						while ($aResultadoCXC = mysql_fetch_array($resultCXC)) { 	
							if ($contador%2 == 0){ $class = "ODD"; } else{ $class = "EVEN"; }
							echo '
								<TR>	
									<TD ALIGN="LEFT"  CLASS="'.$class.'">' . $aResultadoCXC['fecha_cxc'] . '</TD>		
									<TD ALIGN="LEFT"  CLASS="'.$class.'">' . $aResultadoCXC['contrato'] . '</TD>		
									<TD ALIGN="RIGHT"  CLASS="'.$class.'">' . $aResultadoCXC['total'] . '</TD>		
									<TD ALIGN="RIGHT"  CLASS="'.$class.'">' . $aResultadoCXC['saldo_cxc'] . '</TD>		
									<TD ALIGN="LEFT"  CLASS="'.$class.'">' . $aResultadoCXC['concepto'] . '</TD>		
								</TR>';
							$contador += 1;
							$totalCxC += $aResultadoCXC['saldo_cxc_sin_formato'];
						}
						if($contador > 1) {
							echo '
								<TR>	
									<TD ALIGN="CENTER" CLASS="SUBTOTAL" COLSPAN="3">TOTAL</TD>		
									<TD ALIGN="RIGHT"  CLASS="SUBTOTAL">$' . number_format($totalCxC, 2, '.', ',') . '</TD>		
									<TD ALIGN="CENTER" CLASS="SUBTOTAL">&nbsp;</TD>
								</TR>';
						}
					?>
				</TABLE>
			</DIV>
			<DIV STYLE="WIDTH: 444px; FLOAT: LEFT">
				<TABLE id="pg3" STYLE="margin-top: 0px" CELLPADDING="1" CELLSPACING="1">
					<TR>
						<TD WIDTH="750" HEIGHT="10" ALIGN="CENTER" COLSPAN="5">FACTURAS EMITIDAS POR DISTRIBUIDOR</TD>
					</TR>
					<TR>
						<TD WIDTH="250" ALIGN="CENTER"  CLASS="SUBHEADEREVEN"><SPAN CLASS="BOLD">FECHA DE FACTURACION</SPAN></TD>
					    <TD WIDTH="200" ALIGN="CENTER"  CLASS="SUBHEADEREVEN"><SPAN CLASS="BOLD">NUMERO DE FACTURA</SPAN></TD>
					    <TD WIDTH="200" ALIGN="CENTER"  CLASS="SUBHEADEREVEN"><SPAN CLASS="BOLD">IMPORTE</SPAN></TD>
					    <TD WIDTH="200" ALIGN="CENTER"  CLASS="SUBHEADEREVEN"><SPAN CLASS="BOLD">SALDO</SPAN></TD>
					    <TD WIDTH="200" ALIGN="CENTER"  CLASS="SUBHEADEREVEN"><SPAN CLASS="BOLD">TIPO</SPAN></TD>
					</TR>
					<?php
						$resultCXP = mysql_query($strSQLCXP) or die(mysql_error());
						$RT = mysql_num_rows($resultCXP);
						$contador = 1;
						$totalCxP = 0;
						$tClass = "TOTALPARCIAL";
						$gTotalclass = "CELDAGRANTOTAL";
						//$tClass = "SUBTOTAL";
						//$tClass = "GRANTOTAL";
						while ($aResultadoCXP = mysql_fetch_array($resultCXP)) { 	
							if ($contador%2 == 0){ $class = "ODD"; } else{ $class = "EVEN"; }
							echo '
								<TR>	
									<TD ALIGN="LEFT"  CLASS="'.$class.'">' . $aResultadoCXP['fecha_captura'] . '</TD>		
									<TD ALIGN="LEFT"  CLASS="'.$class.'">' . $aResultadoCXP['numero_documento'] . '</TD>		
									<TD ALIGN="RIGHT"  CLASS="'.$class.'">' . $aResultadoCXP['monto'] . '</TD>		
									<TD ALIGN="RIGHT"  CLASS="'.$class.'">' . $aResultadoCXP['saldo'] . '</TD>		
									<TD ALIGN="LEFT"  CLASS="'.$class.'">' . $aResultadoCXP['concepto'] . '</TD>		
								</TR>';
							$contador += 1;
							$totalCxP += $aResultadoCXP['saldo_sin_formato'];
						}
						if($contador > 1) {
							echo '
								<TR>	
									<TD ALIGN="CENTER" CLASS="SUBTOTAL" COLSPAN="3">TOTAL</TD>		
									<TD ALIGN="RIGHT"  CLASS="SUBTOTAL">$' . number_format($totalCxP, 2, '.', ',') . '</TD>		
									<TD ALIGN="CENTER" CLASS="SUBTOTAL">&nbsp;</TD>
								</TR>';
						}
					?>
				</TABLE>
			</DIV>
		</DIV>
		<br>
		<DIV STYLE="WIDTH: 100%; FLOAT: RIGHT">
			<TABLE id="pg3" STYLE="margin-top: 0px" CELLPADDING="1" CELLSPACING="1">
				<TR>
					<TD STYLE="WIDTH: 25%" CLASS="GRANTOTAL">
						SALDO PENDIENTE
					</TD>
					<TD STYLE="WIDTH: 15%" CLASS="GRANTOTAL">
						MONTO
					</TD>
				</TR>
				<TR>
					<TD CLASS="GRANTOTAL">FACTURAS EMITIDAS A DISTRIBUIDOR</TD>
					<TD STYLE="WIDTH: 15%" CLASS="GRANTOTAL"> <?php echo number_format($totalFacturas, 2, '.', ','); ?> </TD>
				</TR>	
				<TR>
					<TD CLASS="GRANTOTAL">CXC DESCUENTOS POR ACTIVACION</TD>
					<TD STYLE="WIDTH: 15%" CLASS="GRANTOTAL"> <?php echo number_format($totalCxC, 2, '.', ','); ?> </TD>
				</TR>	
				<TR>
					<TD CLASS="GRANTOTAL">FACTURAS EMITIDAS POR DISTRIBUIDOR</TD>
					<TD STYLE="WIDTH: 15%" CLASS="GRANTOTAL"> <?php echo number_format($totalCxP, 2, '.', ','); ?> </TD>
				</TR>	
				<TR>
					<TD CLASS="GRANTOTAL">TOTAL PENDIENTE</TD>
					<TD STYLE="width:15%;" CLASS="GRANTOTAL">
						<?php 
							$totalPendiente = $totalCxP - ($totalFacturas + $totalCxC);
							echo number_format($totalPendiente, 2, '.', ',');
						?>
					</TD>
				</TR>
			</TABLE>
		</DIV>
	</BODY>
</HTML>
