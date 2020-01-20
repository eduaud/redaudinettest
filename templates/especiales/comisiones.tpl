{include file="_header.tpl" pagetitle="$contentheader"}
<style>
		{literal}
		.busca-producto{
				padding : 7px !important;
				font-size : 14px;
				width : 250px;
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
		.busca-pedido th{
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
		.cuerpo-pedido td:first-child{
				border-left : 1px #C4C5C7 solid;
				}
		.cuerpo-pedido td{
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
		{/literal}
</style>
<script type="text/javascript">
		{literal}
		function buscaVendedor(){
				var idVend = $("#select-vend").find("option:selected").val();
				$(".cuerpo-pedido tbody tr").remove();
				
				var ruta = "llenaComisiones.php";
				var envio = "idVend=" + idVend;
				var respuesta = ajaxN(ruta, envio);
				$(".cuerpo-pedido tbody").append(respuesta);
				}
		function generaComision(){
				var pedidos = new Array();
				$('table.cuerpo-pedido input[name="idPedidoCheck[]"]:checked').each(function() {
						pedidos.push($(this).val());
						});
				if(pedidos.length == 0){
						alert("Seleccione algun pedido");
						}
				else{
						var idVend = $("#select-vend").find("option:selected").val();
						var url = "generaComision.php";
						var envia_datos = 'pedidos=' + pedidos + '&vendedor=' + idVend; 
						var respuesta = ajaxN(url, envia_datos);
						var datos = respuesta.split("|");
						alert(datos[1]);
						registraEgresos(datos[0]);
						//location.reload();
						}
				}
		

		function registraEgresos(comision){
				$.fancybox({
						type: 'iframe',
						href: '../especiales/egresosComisiones.php?idComision=' + comision,
						fitToView	: false,
						width		: '95%',
						height		: '80%',
						autoSize	: false,
						closeClick	: false,
						openEffect	: 'none',
						closeEffect	: 'elastic',
						afterClose : function(){
							location.reload();
						}
				});
				}
	
		{/literal}
</script>
<h1 class="encabezado">Comisiones</h1>

<div id="busquedas">
<p>Buscar por:</p><br>
		<div id="vendedores" style="float:left; padding-left:25px;">
			<table class="tabla-busquedas">
				<tr>
				<td>
						<label for="select-ruta">Vendedor</label>
				</td>
				<td>
						<select name="select-vend" id="select-vend" onchange="">
								{html_options values=$vend_id output=$vend_nombre}
						</select>
				</td>
				</tr>
				</table><br>
				<p style="display:block; float:right;"><input type="button" class="boton" value="Buscar &raquo;" onclick="buscaVendedor()"/></p>
						
		</div>
</div>
<div style="clear:both;"></div>
<div style="margin:20px 0; width:100%">
		<table class="busca-pedido">
		<caption>Pedidos</caption>
				<thead>
						<tr>
								<th style="width:20px;">No</th>
								<th style="width:22px;"><input type="checkbox" id="sel-todos" onClick="seleccionarCheck(this, 'cuerpo-pedido');"/></th>
								<th style="width:180px;">Vendedor</th>
								<th style="width:100px;">Pedido</th>
								<th style="width:150px;">Sucursal</th>
								<th style="width:100px;">Fecha</th>
								<th style="width:200px;">Cliente</th>
								<th style="width:100px;">Monto Pedido</th>
								<th style="width:100px;">Comisi&oacute;n</th>
						</tr>
				</thead>
		</table>
		<div id="scroll-tabla">
				<table border="0" class="cuerpo-pedido"> 
						<tbody>
						
						</tbody>
				</table>
		</div>
		<p style="display:block; float:right;"><input type="button" class="boton" value="Generar Pago de Comisi&oacute;n &raquo;" onclick="generaComision()"/></p>
</div>


{include file="_footer.tpl"}