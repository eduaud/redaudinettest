<?php

	php_track_vars;
	
	extract($_GET);
	
	include("../../conect.php");

	$txtGR="";
	//conseguimos los puntos de venta
	if($esGR == 1)
	{		
		$sql="SELECT
			  nombre
			  FROM peug_puntos_venta pv
			  JOIN sys_usuarios u ON pv.id_gerente_regional_usr = u.id_usuario
			  WHERE u.id_usuario = ".$id_usuario;
			  
		$res=mysql_query($sql) or die("Error en:<br>$sql<br><br>Descripcion:<br>".mysql_error());			  
		$num=mysql_num_rows($res);
		for($i=0;$i<$num;$i++)
		{
			$row=mysql_fetch_row($res);
			if($txtGR != "" && $i != ($num-1))
				$txtGR.=",";
			elseif($txtGR != "" && $i == ($num-1))	
				$txtGR.=" y ";
			$txtGR.=$row[0];	
		}
		$txtGR="<tr><td><b>Puntos de venta:</b></td><td>$txtGR</td></tr>";
	}
	
	$body='<table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
					  <tr>
						<td align="center"><img src="'.$rooturl.'../images/common/logo_peugeotfinance3.jpg" width="82" height="71" alt="Peugeot Finance" /><br>&nbsp;</td>
					  </tr>
					  <tr>
						<td>
							<i>Se ha realizado un cambio a los datos de su usuario PFMX, la informaci&oacute;n actual es la siguiente:</i>
							<br><br>
							<table>
								<tr>
									<td>
										<b>Nombre:</b>							
									</td>
									<td>
										'.$nombre.'
									</td>
								</tr>
								<tr>
									<td>
										<b>Apellido paterno:</b>
									</td>
									<td>
										'.$apaterno.'
									</td>
								</tr>
								<tr>
									<td>
										<b>Apellido materno:</b>							
									</td>
									<td>
										'.$amaterno.'
									</td>
								</tr>
								<tr>
									<td>
										<b>Usuario:</b>
									</td>
									<td>
										'.$login.'
									</td>
								</tr>
								<tr>
									<td>
										<b>Contrase&ntilde;a:</b>							
									</td>
									<td>
										'.$pass.'
									</td>
								</tr>
								'.$txtGR.'
								<tr>
									<td colspan="2" alignt>
										<br>
										<a href="https://www.peugeot-finance.com.mx/administrador_web">https://www.peugeot-finance.com.mx/administrador_web</a>
									</td>
								</tr>								
							</table>			
						</td>
					  </tr>
					</table>';	
	mail($email, "Cambio de datos en su usuario PFMX", $body,"From: informacion@peugeot-finance.com.mx\r\nContent-Type: text/html;\r\n");						
	
	die("exito");
?>