{include file="_header.tpl" pagetitle="$contentheader"}
<script type="text/javascript">
{literal}
		function buscaOrden(){
				var orden = $("#orden").val();
				if(orden == ""){
						alert("Anota un numero de orden");
						}
				else{
						alert("Anota un numero de orden");
						$(".cuerpo-ordenes tbody tr").remove();
						var ruta = "llenaTablaOrdenesCompra.php";
						var envio = "idOrden=" + orden;
						var respuesta = ajaxN(ruta, envio);
						$(".cuerpo-ordenes tbody").append(respuesta);
						}
				}
		function buscaProveedor(){
				
				var idProvedor = $("#select-proveedor").find("option:selected").val();
				var fecha_inicio = $("#fechadel").val();
				var fecha_fin = $("#fechaal").val();
				
				var FechaInicioConv = convierteFechaJava(fecha_inicio);
				var FechaFinConv = convierteFechaJava(fecha_fin);
				if(FechaInicioConv > FechaFinConv){
						alert("Las fechas final no puede ser mayor a la fina inicial");
						}
				else{
						$(".cuerpo-ordenes tbody tr").remove();
				
						var ruta = "llenaTablaOrdenesCompra.php";
						var envio = "idProveedor=" + idProvedor + "&fecini=" + fecha_inicio + "&fecfin=" + fecha_fin;
						var respuesta = ajaxN(ruta, envio);
						$(".cuerpo-ordenes tbody").append(respuesta);
						}
				
				}
		function verOrdenCompra(idOrden){
				window.open(
				"../../code/general/encabezados.php?t=YWRfb3JkZW5lc19jb21wcmFfcHJvZHVjdG9z&k=" + idOrden + "&op=2&v=1&tabla=&cadP1=MDI0ZG5CbGZqRjhibkJsZmpGOGJYQmxmakY4MQ==&cadP2=MDI0WlhCbGZqRjhhWEJsZmpGOFozQmxmakU9MQ==", "_blank");


						}
		function apruebaOrden(pos){
				var idOrden = $("#idOrden" + pos).val();
				var confirma=confirm("Esta seguro de querer aprobar esta orden de compra?");
						if(confirma==true){
								var envia_datos = "idOrden=" + idOrden + "&caso=2";
								var url = "apruebaRechazoOrdenCompra.php";
								var respuesta = ajaxN(url, envia_datos);
								alert(respuesta);
								location.reload();
								}
						else{
								return false;
								}
						}
		function rechazaOrden(pos){
				var idOrden = $("#idOrden" + pos).val();
				var rechazo = $("#motivo-rechazo" + pos).val();
						if(rechazo == ""){
								alert("Debes elegir un motivo del rechazo");
								$("#motivo-rechazo" + pos).css("border", "1px #F75151 solid");
								}
						else{
								var confirma=confirm("Esta seguro de querer rechazar esta orden de compra?");
								if(confirma==true){
										var envia_datos = "idOrden=" + idOrden + "&caso=3&motivo=" + rechazo;
										var url = "apruebaRechazoOrdenCompra.php";
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
		.busca-producto{
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
<h1 class="encabezado">Aprobaci&oacute;n de Ordenes de Compra</h1>
<div id="busquedas">
<p>Buscar por:</p><br>
		<div id="provedores" style="float:left; padding-left:25px;">
				<label for="select-proveedor">Proveedor</label>
				<select name="select-proveedor" id="select-proveedor" onchange="">
						<option value="0">Seleccione Proveedor</option>
						{section name="proveedores" loop=$proveedor}
						<option value="{$proveedor[proveedores].0}">{$proveedor[proveedores].1}</option>
						{/section}
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
				<p style="display:block; float:right;"><input type="button" class="boton" value="Buscar &raquo;" onclick="buscaProveedor()"/></p>
						
		</div>
		<div id="orden-compra" style="float:left; padding-left:25px;">
				<label for="orden">Orden de Compra</label><input type="text" id="orden" name="orden" class="busca-producto" onkeydown="return noletrasCantidades(event);"/>
				<br><br>
				<p style="display:block; float:right;"><input type="button" class="boton" value="Buscar &raquo;" onclick="buscaOrden();"/></p>
		</div>
</div>
<div style="clear:both;"></div>
<!---Tabla de ordenes de compra--->
<div style="margin:20px 0; width:966px">
		<table class="busca-ordenes">
		<caption>Ordenes de Compra</caption>
				<thead>
						<tr>
								<th style="width:20px;">No</th>
								<th style="width:80px;">Orden de Compra</th>
								<th style="width:100px;">Fecha</th>
								<th style="width:200px;">Proveedor</th>
								<th style="width:150px;">Monto de Orden de Compra</th>
								<th style="width:150px;">Monto de Adeudo</th>
								<th style="width:150px;">Limite de credito</th>
								<th style="width:200px;">Usuario</th>
								<th style="width:300px;">Acciones</th>
						</tr>
				</thead>
		</table>
		<div id="scroll-tabla">
				<table border="0" class="cuerpo-ordenes"> 
						<tbody>
						{assign var="contador" value="1"}
								{section name="filasOrdenes" loop=$filas}
										<tr>
												<td style="width:20px; text-align:center">{$contador}</td>
												<td style="width:100px; text-align:center">{$filas[filasOrdenes].1}</td>
												<td style="width:100px; text-align:center">{$filas[filasOrdenes].2}</td>
												<td style="width:200px; text-align:center">{$filas[filasOrdenes].3}</td>
												<td style="width:150px; text-align:center">{$filas[filasOrdenes].4}</td>
												<td style="width:150px; text-align:center">{$filas[filasOrdenes].5}</td>
												<td style="width:150px; text-align:center">{$filas[filasOrdenes].6}</td>
												<td style="width:200px; text-align:center">{$filas[filasOrdenes].7}</td>
												<td style="width:200px; text-align:center">
												<div style="width : auto">
														<input type="button" id="aprobar" class="botonesD" value="Aprobar" onClick="apruebaOrden({$contador})"/>
														<input type="button" id="rechazar" class="botonesD" value="Rechazar" onClick="rechazaOrden({$contador})"/>
														<input type="button" id="rechazar" class="botonesD" style="width:40px" value="Ver" onClick="verOrdenCompra({$filas[filasOrdenes].1})"/><br><br>
														<textarea style="width:150px" id="motivo-rechazo{$contador}" class="rechazo"></textarea>
												</div>		
												
												</td>
												<td style="display:none"><input id="idOrden{$contador}" type="hidden" value="{$filas[filasOrdenes].1}"/></td>
												<td style="display:none">{$contador++}</td>
										</tr>
								{sectionelse}
										<tr>
												<td>No existen ordenes con estos datos de busqueda</td>
										</tr>
								{/section}
						</tbody>
				</table>
		</div>
</div>

{include file="_footer.tpl"}