<?php
$reporte = "Contratos";
if($opcion == '2'){
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Content-type: atachment/vnd.ms-excel");
	header("Content-Disposition: atachment; filename=\"$reporte.xls\";");
	header("Content-transfer-encoding: binary\n");
}
$result = mysql_query($strSQL);
$RT = mysql_num_rows($result);
if($RT > 0){
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
	<HTML>
	<HEAD>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
	<TITLE>Contratos</TITLE>
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
					<TD ROWSPAN="2" CLASS="HEADER" style="background:#052755; font-size:10px;color:#FFFFFF" ALIGN="CENTER" WIDTH="40%">
						<IMG SRC="../../imagenes/audicel.png"/><BR/>
						<b>AUDINET</b>
					</TD>
					<TD CLASS="HEADER" WIDTH="60%" ALIGN="CENTER">									
						<b><U>CONTRATOS</U></b>			
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
		<TD WIDTH="200"  ALIGN="CENTER"  CLASS="SUBHEADEREVEN">DI</TD>
		<TD WIDTH="200"  ALIGN="CENTER"  CLASS="SUBHEADEREVEN">CLIENTE</TD>
		<TD WIDTH="200" ALIGN="CENTER"  CLASS="SUBHEADEREVEN">CLAVE CLIENTE</TD>
		<TD WIDTH="200" ALIGN="CENTER"  CLASS="SUBHEADEREVEN">CONTRATO</TD>
		<TD WIDTH="200" ALIGN="CENTER"  CLASS="SUBHEADEREVEN">CUENTA</TD>
		<TD WIDTH="200" ALIGN="CENTER"  CLASS="SUBHEADEREVEN">TARJETA/MODEM</TD>
		<TD WIDTH="200" ALIGN="CENTER"  CLASS="SUBHEADEREVEN">CONTRARECIBO</TD>
		<TD WIDTH="200" ALIGN="CENTER"  CLASS="SUBHEADEREVEN">FECHA DE ACTIVACION</TD>
		<TD WIDTH="200" ALIGN="CENTER"  CLASS="SUBHEADEREVEN">PAQUETE SKY</TD>
		<TD WIDTH="200" ALIGN="CENTER"  CLASS="SUBHEADEREVEN">CLAVE</TD>
		<TD WIDTH="200" ALIGN="CENTER"  CLASS="SUBHEADEREVEN">TIPO</TD>
		<TD WIDTH="200" ALIGN="CENTER"  CLASS="SUBHEADEREVEN">ESTATUS ACTUAL</TD>
	<TR>
	<?PHP
		$contador = 1;
		$tClass = "TOTALPARCIAL";
		$gTotalclass = "CELDAGRANTOTAL";
		while($aResultado = mysql_fetch_array($result)){
			if ($contador % 2 == 0){$class="ODD";}else{$class="EVEN";}
			echo '
				<TR>	
					<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['DI'].'</TD>
					<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['nombre_cliente'].'</TD>
					<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['clave_cliente'].'</TD>
					<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['contrato'].'</TD>
					<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['cuenta'].'</TD>
					<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['tarjeta'].'</TD>
					<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['id_contra_recibo'].'</TD>
					<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['fecha_activacion'].'</TD>
					<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['nombre_paquete_sky'].'</TD>
					<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['clave'].'</TD>
					<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['tipo'].'</TD>
					<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultado['estatus_actual'].'</TD>
				</TR>
				<TR>
					<TD WIDTH="200"  ALIGN="CENTER"  CLASS="SUBSUBHEADER">IRDS</TD>
				</TR>';
			$sqlIrds = "
				SELECT numero_serie
				FROM cl_control_contratos_detalles 
				WHERE id_control_contrato = '".$aResultado['id_control_contrato']."' AND id_accion_contrato IN(1,100)";
			$resultIrds = mysql_query($sqlIrds);
			//$RT2 += mysql_num_rows($resultIrds);
			$contador2 = 1;
			while($aResultadoIrds = mysql_fetch_array($resultIrds)){
				if ($contador2 % 2 == 0){$class="ODDSUB";}else{$class="EVENSUB";}
				echo '
					<TR>	
						<TD ALIGN="LEFT"  CLASS="'.$class.'">'.$aResultadoIrds['numero_serie'].'</TD>
					</TR>';
				$contador2 ++;
			}
			$contador++;
		}
		?>
		<TR><TD ALIGN="RIGHT" COLSPAN="17" CLASS="EVEN"></TD></TR>
		<TR><TD WIDTH="750" ALIGN="LEFT" COLSPAN="22" CLASS="CELDAGRANTOTAL"><SPAN CLASS="BOLD">NÚMERO TOTAL DE REGISTROS: <?php echo $RT  ?></SPAN></TD></TR>
		<TR><TD ALIGN="CENTER" COLSPAN="22" CLASS="NUMPAG">Pagina 1</TD></TR>
	<?php
}else{
	echo '
		<center>
				<p style="width:400px;background-color:#F5F5F5;border-style:solid;border-width:2;border-color:#CCCCCC;padding:10px 10px 10px 10px;margin:20px;font-family:verdana,arial,helvetica,sans-serif;color:#505050;font-size:12px;">
				<span style="font-size:15px;color:#CC0000;font-weight:bold;" align="center">Faltan datos para el reporte.</span>
				<br>
				<br>
				No se encontraron datos que cumplan los criterios, intente nuevamente. Verifique el estado del documento antes de mandar a impresi&oacute;n.
				<br>
				<br>
				<span style="font-size:10px;font-weight:bold;">Mensaje generado por el manejador de reportes</span>
			</p>
			<br>
			<input value=" Cerrar " onclick="window.close();" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-style:normal; font-size:10px; font-weight:bold; background-color:#F0F0F0; color:#000000; border:1px solid #CCCCCC;" type="button">
		</center>';
	die();
}
?>
</TABLE>
</BODY>
</HTML>