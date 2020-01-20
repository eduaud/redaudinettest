{include file="_header.tpl" pagetitle="$contentheader"}
<script type="text/javascript">
{literal}
		function buscaOrden(){
				var orden = $("#orden").val();
				if(orden == ""){
						alert("Anota un numero de orden");
						}
				else{
						$(".ordenes").html("");
						var ruta = "respuestaEntradaOrdenesCompra.php";
						var envio = "idOrden=" + orden;
						var respuesta = ajaxN(ruta, envio);
						$(".ordenes").html(respuesta);
						}
				}
		function buscaProveedor(){
				
				var idProvedor = $("#select-proveedor").find("option:selected").val();
				
				$(".ordenes").html("");
				
				var ruta = "respuestaEntradaOrdenesCompra.php";
				var envio = "proveedor=" + idProvedor;
				var respuesta = ajaxN(ruta, envio);
				$(".ordenes").html(respuesta);
				}
		function guardaOrdenEntrada(idOrden){
				var almacen = $("#select-almacen"+idOrden).find("option:selected").val();
				var observaciones = $("#observaciones"+idOrden).val();
				var factura = $("#fac-prov"+idOrden).val();
				var parciales = $("#parciales"+idOrden).val();
				var pedimento = $("#pedimento"+idOrden).val();
				var pedimentoFecha = $("#pedimento_fec"+idOrden).val();
				var proveedor = $("#proveedor_id"+idOrden).val();
				var completo = $("#completo"+idOrden).val();
				var valCantidades = 0;
				var textValCantidades = "";
				
				var aduana = $("#select-aduana"+idOrden).find("option:selected").val();
				var productos = new Array();
				var sumaCantidadesProductos = 0;
				var sumaCantidadesIngresadas = 0;
				$("tbody#res-prod" + idOrden + " tr").each(function(index) {
						
						var requerida = $("#cantidadProd" + idOrden + index).val();
						var recibida = $("#cantidadR" + idOrden + index).val();
						var ingresada = $("#cantidadI" + idOrden + index).val();
						
						var sumaV = parseInt(ingresada) + parseInt(recibida);
						if(sumaV > requerida){
								var fila = parseInt(index) + 1;
								valCantidades += 1;
								textValCantidades = "No puedes ingresar una cantidad mayor a la requerida\nFila " + fila;
								}
						
						
						var cantidadIngresada = $("#cantidadI" + idOrden + index).val();
						cantidadIngresada = cantidadIngresada == "" ? cantidadIngresada = 0 : cantidadIngresada;
						sumaCantidadesProductos += parseInt(requerida);
						sumaCantidadesIngresadas += parseInt(cantidadIngresada);
						productos.push($("#idProducto"+ idOrden + index).val() + "|" + $("#idPrecioUnitario" + idOrden + index).val() + "|" + ingresada + "|" + $("#idDetalleOrden" + idOrden + index).val() + "|" + requerida);
						});
				
				if($("#completo"+idOrden).is(':checked'))
						var estatus = 1;
				else
						var estatus = 0;
						
						
				if(parciales == 0 && sumaCantidadesIngresadas < sumaCantidadesProductos){
						alert("La orden de compra no permite recibos parciales");
						var confirma = false;
						}
				else if(valCantidades > 0){
						alert(textValCantidades);
						var confirma = false;
						}
				else if($("#completo"+idOrden).is(':checked') && $("#completo"+idOrden).attr("disabled") != "disabled"){
						var confirma=confirm("Esta seguro de marcar esta orden como completa?");
						var confirmado = 1;
						}
				
				else{
						var confirma = true;
						var confirmado = 0;
						}
				
				if(confirma == true){
						var ruta = "guardaOrdenCompraEntrada.php";
						var envio = "idOrden=" + idOrden + "&almacen=" + almacen + "&observaciones=" + observaciones + "&factura=" + factura + "&pedimento=" + pedimento + "&pedimentoFecha=" + pedimentoFecha + "&aduana=" + aduana + "&productos=" + productos + "&proveedor=" + proveedor + "&estatus=" + estatus;
						var respuesta = ajaxN(ruta, envio);
						alert(respuesta);
						location.reload();
						}
				
				}
				
</script>
{/literal}
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
		.ordenes{
				width : auto;
				height : 1000px;
				overflow : auto;
				}
		.titulos{
				font-size : 13px !important;
				font-weight : bold !important;
				color : #006400;
				}
		.encabezado-orden, .datos-entrada{
				width : 100%;
				border-collapse : collapse;
				}
		.encabezado-orden th, .encabezado-orden td{
				background : #DCD8D7;
				padding : 5px;
				border : 3px #FFFFFF solid;
				text-align : left;
				}
		.encabezado-orden td{
				font-size : 11px !important;
				font-weight : bold !important;
				color : #101010;
				}
		.datos-entrada td{
				background : #DCD8D7;
				width : 50%;
				padding : 5px;
				
				}
		.datos-entrada select, .datos-entrada textarea, .datos-entrada input[type="text"]{
				border: 1px solid rgb(219, 225, 235);
				border-radius: 4px;
				color: rgb(90, 90, 90);
				padding: 4px;
				width: 300px;
				font-size : 12px;
				color : #999999;
				}
		.datos-entrada label{
				color : #262931 !important;
				}
		.datos-entrada input[type="text"]{
				width: 150px;
				}
		hr{
				margin: 10px 0; border: 3px solid #0D6300; border-radius: 50px/10px; height: 0px; text-align: center;
				}
		
		{/literal}
</style>
<h1>Generar Entradas a Partir de Ordenes de Compra</h1>
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
				<p style="display:block; float:right;"><input type="button" class="boton" value="Buscar &raquo;" onclick="buscaProveedor()"/></p>
						
		</div>
		<div id="orden-compra" style="float:left; padding-left:25px;">
				<label for="orden">Orden de Compra</label><input type="text" id="orden" name="orden" class="busca-producto" onkeydown="return noletrasCantidades(event);"/>
				<br><br>
				<p style="display:block; float:right;"><input type="button" class="boton" value="Buscar &raquo;" onclick="buscaOrden();"/></p>
		</div>
</div>
<div style="clear:both"></div>
<br><br>
<div class="ordenes">
		


</div>


{include file="_footer.tpl"}