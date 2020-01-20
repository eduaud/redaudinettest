{include file="_header.tpl" pagetitle="$contentheader"}
<script type="text/javascript">
{literal}
		function buscaSolicitud(){
				var solicitud = $("#solicitud").val();
				if(solicitud == ""){
						alert("Anota un numero de solicitud");
						}
				else{
						$(".cuerpo-ordenes tbody").remove();
						var ruta = "llenaTablaSolCedis.php";
						var envio = "idSolicitud=" + solicitud;
						var respuesta = ajaxN(ruta, envio);
						$(".cuerpo-ordenes").append(respuesta);
						}
				}
		function buscaSucursal(){
				
				var idSucursal = $("#select-sucursal").find("option:selected").val();
				var fecha_inicio = $("#fechadel").val();
				var fecha_fin = $("#fechaal").val();
				
				var FechaInicioConv = convierteFechaJava(fecha_inicio);
				var FechaFinConv = convierteFechaJava(fecha_fin);
				if(FechaInicioConv > FechaFinConv){
						alert("Las fechas final no puede ser mayor a la fina inicial");
						}
				else{
						$(".cuerpo-ordenes tbody").remove();
				
						var ruta = "llenaTablaSolCedis.php";
						var envio = "idSucursal=" + idSucursal + "&fecini=" + fecha_inicio + "&fecfin=" + fecha_fin;
						var respuesta = ajaxN(ruta, envio);
						$(".cuerpo-ordenes").append(respuesta);
						}
				
				}
		function verSolicitud(idSolicitud){
				window.open(
				"../../code/general/encabezados.php?t=bmFfc29saWNpdHVkX2Rldm9sdWNpb25fY2VkaXM=&k=" + idSolicitud + "&op=2&v=1", "_blank");


						}
		function apruebaSolicitud(pos){
				var idSolicitud = $("#idSolicitud" + pos).val();
				var confirma=confirm("Esta seguro de querer aprobar esta orden de recoleccion?");
						if(confirma==true){
								var envia_datos = "idSolicitud=" + idSolicitud + "&caso=2";
								var url = "apruebaRechazoSolCedis.php";
								var respuesta = ajaxN(url, envia_datos);
								alert(respuesta);
								location.reload();
								}
						else{
								return false;
								}
						}
		function rechazaSolicitud(pos){
				var idSolicitud = $("#idSolicitud" + pos).val();
				var rechazo = $("#motivo-rechazo" + pos).val();
						if(rechazo == ""){
								alert("Debes elegir un motivo del rechazo");
								$("#motivo-rechazo" + pos).css("border", "1px #F75151 solid");
								}
						else{
								var confirma=confirm("Esta seguro de querer rechazar esta orden de compra?");
								if(confirma==true){
										var envia_datos = "idSolicitud=" + idSolicitud + "&caso=3&motivo=" + rechazo;
										var url = "apruebaRechazoSolCedis.php";
										var respuesta = ajaxN(url, envia_datos);
										alert(respuesta);
										location.reload();
										}
								else{
										return false;
										}
								}
						}
						
				
{/literal}
</script>

<style>
		{literal}
		.busca-solicitud{
				padding : 7px !important;
				font-size : 14px;
				width : 300px;
				height : 15px;
				border : 1px #DBE1EB solid;
				border-radius : 4px;
				background: #FFFFFF;
				background : -moz-linear-gradient(left, #FFFFFF, #F7F9FA);
				background : -webkit-linear-gradient(left, #FFFFFF, #F7F9FA);
				background : -o-linear-gradient(left, #FFFFFF, #F7F9FA);
				background : -ms-linear-gradient(left, #FFFFFF, #F7F9FA);
				color : #6E6E6E;
				}
		label{
				font-size : 12px;
				padding-right : 10px;
				color : #808080;
				font-weight : bold;
				}
		#busquedas p{
				font-size:13px; 
				font-weight:bold;
				}
		#busquedas select{
				border: 1px solid rgb(219, 225, 235);
				border-radius: 4px;
				color: rgb(90, 90, 90);
				padding: 4px;
				width: 300px;
				font-size : 12px;
				color : #999999;
				}
		.busca-ordenes th{
				padding : 5px;
				font-weight : bold;
				font-size : 10px;
				text-align : center;
				background-color: #FAFBFF;
				background-image: -o-linear-gradient(bottom, #FAFBFF 0%, #B3B4B5 100%);
				background-image: -moz-linear-gradient(bottom, #FAFBFF 0%, #B3B4B5 100%);
				background-image: -webkit-linear-gradient(bottom, #FAFBFF 0%, #B3B4B5 100%);
				background-image: -ms-linear-gradient(bottom, #FAFBFF 0%, #B3B4B5 100%);
				background-image: linear-gradient(to bottom, #FAFBFF 0%, #B3B4B5 100%);
				}
		caption{
				font-size : 13px;
				font-weight : bold;
				text-align : left;
				padding : 10px 0;
				color : #404651;
				}

		.cuerpo-ordenes td:first-child{
				border-left : 1px #C4C5C7 solid;
				}
		.cuerpo-ordenes td{
				padding : 5px;
				font-size : 10px;
				border : 1px #C4C5C7 solid;
				border-top : none;
				border-left : none;
				}
		#scroll-tabla{
				width : auto;
				height : 250px;
				overflow : auto;
				}
		.botonesD {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 9px;
			color: #ffffff;
			padding: 2px 2px;
			width : 60px;
			background: -moz-linear-gradient(
				top,
				#6b6b6b 0%,
				#000000);
			background: -webkit-gradient(
				linear, left top, left bottom,
				from(#6b6b6b),
				to(#000000));
			-moz-border-radius: 6px;
			-webkit-border-radius: 6px;
			border-radius: 6px;
			border: 1px solid #000000;
			-moz-box-shadow:
				0px 1px 3px rgba(000,000,000,0.5),
				inset 0px 0px 1px rgba(255,255,255,0.7);
			-webkit-box-shadow:
				0px 1px 3px rgba(000,000,000,0.5),
				inset 0px 0px 1px rgba(255,255,255,0.7);
			box-shadow:
				0px 1px 3px rgba(000,000,000,0.5),
				inset 0px 0px 1px rgba(255,255,255,0.7);
			text-shadow:
				0px -1px 0px rgba(000,000,000,0.4),
				0px 1px 0px rgba(255,255,255,0.3);
			cursor : pointer;
		}
		.rechazo{
				font-size : 11px;
				color : #999999;
				}
		
		.fechas_ordenes{
				padding : 5px !important;
				font-size : 12px;
				width : 125px;
				height : 12px;
				border : 1px #DBE1EB solid;
				border-radius : 4px;
				background: #FFFFFF;
				background : -moz-linear-gradient(left, #FFFFFF, #F7F9FA);
				background : -webkit-linear-gradient(left, #FFFFFF, #F7F9FA);
				background : -o-linear-gradient(left, #FFFFFF, #F7F9FA);
				background : -ms-linear-gradient(left, #FFFFFF, #F7F9FA);
				color : #6E6E6E;
				}
		
		{/literal}
</style>
<h1 class="encabezado">Aprobaci&oacute;n de Solicitudes de Devoluci&oacute;n a CEDIS</h1>
<div id="busquedas">
<p>Buscar por:</p><br>
		<div id="sucursales" style="float:left; padding-left:25px;">
				<label for="select-sucursal">Sucursal</label>&nbsp;&nbsp;
				<select name="select-sucursal" id="select-sucursal" onchange="">
						{if $grupo eq 1}
								<option value="0">Todas</option>
						{/if}
						{html_options values=$sucursal_id output=$sucursal_nombre}
				</select><br><br>
				<table>
						<tr>
								<td><label for="fechadel">Fecha del&nbsp;&nbsp;&nbsp;</label></td>
								<td><input type="text" id="fechadel" name="fechadel" class="fechas_ordenes" onFocus="calendario(this);"/></td>
								<td style="padding-left : 5px;"><label for="fechaal">Al</label></td>
								<td><input type="text" id="fechaal" name="fechaal" class="fechas_ordenes" onFocus="calendario(this);"/></td>
						</tr>
				</table>
				<br>
				<p style="display:block; float:right;"><input type="button" class="boton" value="Buscar &raquo;" onclick="buscaSucursal()"/></p>
						
		</div>
		<div id="orden-compra" style="float:left; padding-left:25px;">
				<label for="orden">Solicitud No.</label><input type="text" id="solicitud" name="solicitud" class="busca-solicitud"/>
				<br><br>
				<p style="display:block; float:right;"><input type="button" class="boton" value="Buscar &raquo;" onclick="buscaSolicitud();"/></p>
		</div>
</div>
<div style="clear:both;"></div>
<!---Tabla de ordenes de compra--->
<div style="margin:20px 0; width:966px">
		<table class="busca-ordenes">
		<caption>Ordenes de Recoleccion a Clientes</caption>
				<thead>
						<tr>
								<th style="width:20px;">No</th>
								<th style="width:60px;">Solicitud</th>
								<th style="width:100px;">Fecha</th>
								<th style="width:150px;">Sucursal</th>
								<th style="width:150px;">Tipo</th>
								<th style="width:150px;">Fecha y hora de Recolecci&oacute;n</th>
								<th style="width:150px;">Ruta</th>
								<th style="width:300px;">Acciones</th>
						</tr>
				</thead>
		</table>
		<div id="scroll-tabla">
				<table border="0" class="cuerpo-ordenes"> 
						<tbody>
						{assign var="contador" value="1"}
								{section name="filasCedis" loop=$filas}
										<tr>
												<td style="width:20px; text-align:center">{$contador}</td>
												<td style="width:80px; text-align:center">{$filas[filasCedis].0}</td>
												<td style="width:100px; text-align:center">{$filas[filasCedis].1}</td>
												<td style="width:150px; text-align:center">{$filas[filasCedis].2}</td>
												<td style="width:150px; text-align:center">{$filas[filasCedis].3}</td>
												<td style="width:150px; text-align:center">{$filas[filasCedis].4}</td>
												<td style="width:150px; text-align:center">{$filas[filasCedis].5}</td>
												<td style="width:300px; text-align:center">
												<div style="width : auto">
														<input type="button" id="aprobar" class="botonesD" value="Aprobar" onClick="apruebaSolicitud({$contador})"/>
														<input type="button" id="rechazar" class="botonesD" value="Rechazar" onClick="rechazaSolicitud({$contador})"/>
														<input type="button" id="rechazar" class="botonesD" style="width:40px" value="Ver" onClick="verSolicitud({$filas[filasCedis].0})"/><br><br>
														<textarea style="width:150px" id="motivo-rechazo{$contador}" class="rechazo"></textarea>
												</div>		
												
												</td>
												<td style="display:none"><input id="idSolicitud{$contador}" type="hidden" value="{$filas[filasCedis].0}"/></td>
												<td style="display:none">{$contador++}</td>
										</tr>
								{sectionelse}
										<tr>
												<td>No existen solicitudes con estos datos de busqueda</td>
										</tr>
								{/section}
						</tbody>
				</table>
		</div>
</div>

{include file="_footer.tpl"}