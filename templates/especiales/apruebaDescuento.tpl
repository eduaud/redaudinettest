{include file="_header.tpl" pagetitle="$contentheader"}
<style>
{literal}

		.genera-descuento th{
				padding : 2px;
				font-weight : bold;
				font-size : 10px;
				text-align : center;
				height : 20px;
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

		.cuerpo-descuento td:first-child{
				border-left : 1px #C4C5C7 solid;
				}
		.cuerpo-descuento td, .resultado-productos td{
				padding : 2px;
				font-size : 10px;
				border : 1px #C4C5C7 solid;
				border-top : none;
				border-left : none;
				}
		#scroll-tabla{
				width : auto;
				height : 300px;
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
		.motivo-rehazo{
				width : 100px;
				}

{/literal}
</style>
<script type="text/javascript">
{literal}
		function apruebaDescuento(pos){
				var idPedido = $("#idPedido" + pos).val();
				compruebaGuardadoPedido(idPedido);
				if(respuesta == 0){
						alert("No puedes aprobar el descuento\nsi la solicitud no esta guardada");
						}
				else{
						var idDescuento = $("#idDescuento" + pos).val();
						var caso = 2;
						input_box=confirm("Esta seguro de querer aprobar este descuento?");
						if(input_box==true){
								var envia_datos = "motivo=&id=" + idDescuento + "&caso=" + caso + "&idPedido=" + idPedido;
								var url = "apruebaRechazoDescuento.php";
								var respuesta = ajaxN(url, envia_datos);
								
								alert(respuesta);
								location.reload();
								}
						else{
								return false;
								}
						}
				}
		function rechazaDescuento(pos){
				var idPedido = $("#idPedido" + pos).val();
				var respuesta = compruebaGuardadoPedido(idPedido);
				
				if(respuesta == 0){
						alert("No puedes rechazar el descuento\nsi la solicitud no esta guardada");
						}
				else{
						var rechazo = $("#motivo-rechazo" + pos).val();
						var idDescuento = $("#idDescuento" + pos).val();
						var caso = 3;
						
						if(rechazo == ""){
								alert("Debes elegir un motivo del rechazo");
								$("#motivo-rechazo" + pos).css("border", "1px #F75151 solid");
								}
						else{
								input_box=confirm("Esta seguro de querer rechazar este descuento?");
								if(input_box==true){
								
										var envia_datos = "motivo=" + rechazo + "&id=" + idDescuento + "&caso=" + caso + "&idPedido=" + idPedido;
										var url = "apruebaRechazoDescuento.php";
										
										var respuesta = ajaxN(url, envia_datos);
										alert(respuesta);
										location.reload();
										}
								else{
										return false;
										}
								}
						}
				}
		function compruebaGuardadoPedido(pedido){
				var envia_datos = "pedido=" + pedido;
				var url = "compruebaGuardadoPedido.php";
				var respuesta = ajaxN(url, envia_datos);
			
				return respuesta;
						
				}
		function verPedido(idPedido){
				var respuesta = compruebaGuardadoPedido(idPedido);
				
				if(respuesta == 0){
						alert("No puedes ver el pedido\nhasta que sea guardado");
						}
				else{
						var envia_datos = "referencia=" + idPedido;
						var url = "obtenIdPedido.php";
						var id = ajaxN(url, envia_datos);
						
						location.href = "../../code/general/encabezados.php?t=YWRfcGVkaWRvcw==&k=" + id + "&op=2&v=1&tabla=&cadP1=MDI0ZG5CbGZqRjhibkJsZmpGOGJYQmxmakY4MQ==&cadP2=MDI0WlhCbGZqRjhhWEJsZmpGOFozQmxmakU9MQ==";
						}
				}
{/literal}
</script>
<h1 class="encabezado">Aprobaci&oacute;n de Descuento</h1>

<br>

<div style="margin:20px 0; width:966px">
		<table class="genera-descuento">
		<caption>Descuentos</caption>
				<thead>
						<tr>
								<th style="width:20px;">No</th>
								<th style="width:150px;">Sucursal</th>
								<th style="width:200px;">Solicita</th>
								<th style="width:200px;">Cliente</th>
								<th style="width:200px;">Total del Pedido</th>
								<th style="width:70px;">% Descuento Solicitado</th>
								<th style="width:160px;">Monto del Descuento Solicitado</th>
								<th style="width:200px;">Motivo</th>
								<th style="width:300px;">Acciones</th>
						</tr>
				</thead>
		</table>
		<div id="scroll-tabla">
				<table border="0" class="cuerpo-descuento"> 
						<tbody>
								{assign var="contador" value="1"}
								{section name="filasDescuentos" loop=$descuentos}
										<tr>
												<td style="width:20px; text-align:center">{$contador}</td>
												<td style="width:150px; text-align:center">{$descuentos[filasDescuentos].1}</td>
												<td style="width:200px; text-align:center">{$descuentos[filasDescuentos].2}</td>
												<td style="width:200px; text-align:center">{$descuentos[filasDescuentos].3}</td>
												<td style="width:150px; text-align:center">{$descuentos[filasDescuentos].6}</td>
												<td style="width:70px; text-align:center">{$descuentos[filasDescuentos].4}</td>
												
												<td style="width:150px; text-align:center">{$descuentos[filasDescuentos].5}</td>
												
												<td style="width:200px; text-align:center">{$descuentos[filasDescuentos].7}</td>
												<td style="width:200px; text-align:center">
												<div style="width : auto">
														<input type="button" id="aprobar" class="botonesD" value="Aprobar" onClick="apruebaDescuento({$contador})"/>
														<input type="button" id="rechazar" class="botonesD" value="Rechazar" onClick="rechazaDescuento({$contador})"/>
														<input type="button" id="ver" class="botonesD" style="width:40px" value="Ver" onClick="verPedido({$descuentos[filasDescuentos].8})"/><br><br>
														<textarea style="width:150px" id="motivo-rechazo{$contador}"></textarea>
												</div>		
												
												</td>
												<td style="display:none"><input id="idDescuento{$contador}" type="hidden" value="{$descuentos[filasDescuentos].0}"/></td>
												<td style="display:none"><input id="idPedido{$contador}" type="hidden" value="{$descuentos[filasDescuentos].8}"/></td>
												<td style="display:none">{$contador++}</td>
										</tr>
								{sectionelse}
										<tr>
												<td>No existen descuentos registrados</td>
										</tr>
								{/section}
						</tbody>
				</table>
		
		</div>
		
		
		
</div>

{include file="_footer.tpl"}