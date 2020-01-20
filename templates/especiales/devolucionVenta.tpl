{include file="_header.tpl" pagetitle="$contentheader"}
<style>
		{literal}
		.busca-producto{
				padding : 7px !important;
				font-size : 14px;
				width : 100px;
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
		label{
				font-size : 12px;
				padding-right : 10px;
				color : #808080;
				font-weight : bold;
				}
		#cabezal p{
				font-size : 11px;
				font-weight : bold;
				padding-bottom : 2px;
				}
		.productos th{
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
		.productos td:first-child{
				border-left : 1px #C4C5C7 solid;
				}
		.productos td{
				padding : 5px;
				font-size : 10px;
				border : 1px #C4C5C7 solid;
				border-top : none;
				border-left : none;
				}
		.botonesD {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 10px;
			color: #ffffff;
			padding: 2px 2px;
			width : 120px;
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
		{/literal}
</style>
<script type="text/javascript">
{literal}
		function buscaPedido(){
				var cliente = $("#select-clien").find("option:selected").val();
				var pedido = $("#pedidoB").val();
				
				$("#recargaPedido").empty();
				var ruta = "llenaPedidosOC.php";
				var envio = "cliente=" + cliente + "&pedido=" + pedido;
				var respuesta = ajaxN(ruta, envio);
				$("#recargaPedido").html(respuesta);
				
				}
		
		function generaDevolucion(pedido){
				var nomPedido = $("#nomPedido" + pedido).val();
				var almacen = $("#slct_almacen" + pedido).find("option:selected").val();
				var movimiento = $("#movimiento" + pedido).val();
				var datosProd = []; //Array que llevara los datos del grid
				var errorCantidad = 0;
				
				$('input[name="checkProd' + pedido + '[]"]:checked').each(function() {
						var registros = {};
						registros["producto"] = $(this).val();
						var cantidad = $("#devolver" + pedido + $(this).val()).val();
						
						if(cantidad == "")
								errorCantidad++;
								
						var interno = $("#interno" + pedido + $(this).val()).val();
						registros["cantidad"] = cantidad;
						registros["documento_interno"] = interno;
						datosProd.push(registros);
						});
						
				if(datosProd.length == 0){
						alert("Selecciona algun producto");
						}
				else if(errorCantidad > 0){
						alert("Anota la cantidad a devolver");
						}
				else{
						var productosTabla = JSON.stringify(datosProd);
						var envia_datos = "pedido=" + pedido + "&nomPedido=" + nomPedido + "&productos=" + productosTabla + "&almacen=" + almacen + "&movimiento=" + movimiento;
						var url = "insertaDevolucionPedido.php";
						var respuesta = ajaxN(url, envia_datos);
						var variables = respuesta.split("|");
						if(variables[0] == "exito"){
								var redirecciona = variables[2] + "code/general/encabezados.php?t=bmFfbW92aW1pZW50b3NfYWxtYWNlbg==&k=" + variables[1] + "&op=2&v=1&cadP1=MDI0ZG5CbGZqRjhibkJsZmpGOGJYQmxmakY4MQ==&cadP2=MDI0WlhCbGZqRjhhWEJsZmpGOFozQmxmakU9MQ==&stm=70009&pie=0";
								$(location).attr('href', redirecciona); 
								}
						}
				
				}
{/literal}
</script>

<h1 class="encabezado">Generaci&oacute;n de Devoluciones por Venta</h1>

<div id="busquedas">
		<p>Buscar por:</p>
		<br>
		<div id="provedores" style="float:left; padding-left:25px;">
				<table class="tabla-busquedas">
				<tr>
				<td>
						<label for="select-prov">Cliente:</label>
				</td>
				<td >
						<select name="select-clien" id="select-clien" onchange="">
								<option value="0">Seleccione Cliente</option>
								{html_options values=$clien_id output=$clien_nombre}
						</select>
				</td>
				</tr>
				<tr>
				<td>
						<br><label for="select-prov">Pedido:</label>
				</td>
				<td >
						<br><input type="text" id="pedidoB" name="pedidoB" class="busca-producto"/>
				</td>
				</tr>
				<tr>
						<td colspan="2"><br><p style="display:block; float:right;"><input type="button" class="boton" value="Buscar &raquo;" onclick="buscaPedido();"/></p></td>
				</tr>
				</table>
		</div>
</div>
<div style="clear:both"></div>


<div style="margin:20px 0; width:966px" id="recargaPedido">
		{assign var="contador" value="1"}
		{section loop=$pedidos name=filaPedido}
				<div id="cabezal">
				<p>Almacen: <select name="slct_almacen{$pedidos[filaPedido].0}" id="slct_almacen{$pedidos[filaPedido].0}" class="campos_req">
						{html_options values=$alm_id output=$alm_nombre}
				</select></p>
				<p>Pedido: {$pedidos[filaPedido].1}</p>
				<p>Cliente: {$pedidos[filaPedido].2}</p>
				<!--ID Pedido -->
				<input type="hidden" value="{$pedidos[filaPedido].1}" id="nomPedido{$pedidos[filaPedido].0}" name="nomPedido{$pedidos[filaPedido].0}"/>
				<!--ID Control Movimiento -->
				<input type="hidden" value="{$pedidos[filaPedido].3}" id="movimiento{$pedidos[filaPedido].0}" name="movimiento{$pedidos[filaPedido].0}"/>
				</div>
				<table class="productos">
						<thead>
								<tr>
										<th style="width:20px">&nbsp;</th>
										<th style="width:400px">Producto</th>
										<th style="width:120px">Cantidad Pedido</th>
										<th style="width:120px">Cantidad Entregada</th>
										<th style="width:120px">Cantidad Devuelta</th>
										<th style="width:120px">Cantidad a Devolver</th>
								</tr>
						</thead>
						{section loop=$detalles[filaPedido] name=filaDetalle}
								<tr>
										<td style="width:20px"><input style="width:20px"type="checkbox" id="checkProd{$pedidos[filaPedido].0}" name="checkProd{$pedidos[filaPedido].0}[]" value="{$detalles[filaPedido][filaDetalle].0}"/></td>
										<td style="width:400px">{$detalles[filaPedido][filaDetalle].1}</td>
										<td style="width:120px; text-align:center">{$detalles[filaPedido][filaDetalle].2}</td>
										<td style="width:120px; text-align:center">{$detalles[filaPedido][filaDetalle].3}</td>
										<td style="width:120px; text-align:center">{$detalles[filaPedido][filaDetalle].4}</td>
										<td style="width:120px; text-align:center">
												<input style="width:100px" type="text" name="devolver{$pedidos[filaPedido].0}{$detalles[filaPedido][filaDetalle].0}" id="devolver{$pedidos[filaPedido].0}{$detalles[filaPedido][filaDetalle].0}"/>
										</td>
										<!--Documento Interno--->
										<td style="display:none">
												<input type="hidden" name="interno{$pedidos[filaPedido].0}{$detalles[filaPedido][filaDetalle].0}" id="interno{$pedidos[filaPedido].0}{$detalles[filaPedido][filaDetalle].0}" value="{$detalles[filaPedido][filaDetalle].6}"/>
										</td>
								</tr>
						{/section}
				</table>
				<br>
				<div style="width:960px; text-align:right">
						<input type="button" id="entradaD" class="botonesD" value="Generar Entrada" onClick="generaDevolucion({$pedidos[filaPedido].0})"/>
				</div>
				<br>
				<input type="hidden" value="{$contador++}"/>
		{/section}
</div>

{include file="_footer.tpl"}