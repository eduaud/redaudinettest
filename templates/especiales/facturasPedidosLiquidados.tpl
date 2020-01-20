{include file="_header.tpl" pagetitle="$contentheader"}
<script type="text/javascript">
{literal}
		function buscaPedido(){
				var idCliente = $("#select-cliente").find("option:selected").val();
				var fecha_inicio = $("#fechadel").val();
				var fecha_fin = $("#fechaal").val();
				var tipo_requiere_factura = $('input:radio[name=req-factura]:checked').val();
				var idSucursal = $("#select-sucursal").find("option:selected").val();
				
				var FechaInicioConv = convierteFechaJava(fecha_inicio);
				var FechaFinConv = convierteFechaJava(fecha_fin);
				if(FechaInicioConv > FechaFinConv){
						alert("Las fechas final no puede ser mayor a la fina inicial");
						}
				else{
						$(".cuerpo-ordenes tbody tr").remove();
				
						var ruta = "llenaTablaFacturasPedidos.php";
						var envio = "idCliente=" + idCliente + "&fecini=" + fecha_inicio + "&fecfin=" + fecha_fin+"&reqFac="+tipo_requiere_factura+"&id_sucursal="+idSucursal;
						var respuesta = ajaxN(ruta, envio);
						$(".cuerpo-ordenes tbody").append(respuesta);
						}
				}
				
		function llenaCliente(radio){
				var opcion = $(radio).val();
				var selectHijo = "select-cliente";
				var urlAjax = "llenaComboClientes.php";
				var envio_datos = 'opcion=' + opcion; 
				ajaxCombos(urlAjax, envio_datos, selectHijo);
				}
				
		function generarFactura(){ 
				var valorPedidos = new Array();
				var pago_sat = new Array();
				var cuenta = new Array();
				var facturaServicios = new Array();
		
				
				//una factura por pedido 
				//o una factura global para todos los pedidos seleccionados
				var tipo_requiere_factura = $('input:radio[name=req-factura]:checked').val();
				
				//var tipo_factura_xpedido_oglobal = $('input:radio[name=req-numero_facturas]:checked').val();
				
				//alert(tipo_factura_xpedido_oglobal);
				
				var contador = 0;
				var contadorCuenta = 0;
				
				$('table.cuerpo-ordenes input[name="idPedidoCheck[]"]:checked').each(function() {
						valorPedidos.push($(this).val());
						facturaServicios.push($("#factura_servicios" + $(this).val()).find("option:selected").val());
						pago_sat.push($("#tipo_pago_sat" + $(this).val()).find("option:selected").val());
						cuenta.push($("#cuenta" + $(this).val()).val());
						
						
						if(($("#tipo_pago_sat" + $(this).val()).find("option:selected").val()) !='1'  &&  ($("#tipo_pago_sat" + $(this).val()).find("option:selected").val())!=2 && $("#cuenta" + $(this).val()).val() =='')
						{
								
								contadorCuenta ++;
						}						

						
						
						
						});
			
			
				if(valorPedidos.length == 0){
						alert("Seleccione algun pedido");
						}
				else if(contadorCuenta>0){
					alert("Es necesario ingresar una cuenta en los pedidos seleccionados ");
					
				}
				else{
						
							if(confirm(String.fromCharCode(191)+"Desea generar una factura por cada pedido seleccionado? "))
							{
									var url = "insertaFactura.php";
									//var envia_datos = 'pedidos=' + valorPedidos + '&factura_servicios=' + facturaServicios + '&pago_sat=' + pago_sat + '&cuenta=' + cuenta+'&reqFac='+tipo_requiere_factura+'&tipo_factura_xpedido_oglobal='+tipo_factura_xpedido_oglobal;
									var envia_datos = 'pedidos=' + valorPedidos + '&factura_servicios=' + facturaServicios + '&pago_sat=' + pago_sat + '&cuenta=' + cuenta+'&reqFac='+tipo_requiere_factura;
									var respuesta = ajaxN(url, envia_datos);
									
									alert(respuesta);
									buscaPedido();
							}
						
						
						}
		}
				
{/literal}
</script>
<style>
		{literal}
		.busca-orden{
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
		.radios td:last-child{
				padding : 5px;
				}
		.formas_pago {
				width : 120px;
				font-size : 10px !important;
				color : #999 !important;
				}
		.formas_pago option{
				font-size : 10px !important;
				color : #999 !important;
				}
		{/literal}
</style>

<h1 class="encabezado">Generaci&oacute;n de Facturas a Partir de Pedidos Liquidados</h1>
<div id="busquedas">
<p>Buscar por:</p><br>
		<div id="sucursales_busca" style="float:left; padding-left:25px;">
				
				<table class="radios">
						<tr>
								<td>
										<input type="radio" name="req-factura" id="req-factura-si" value="si" onclick="llenaCliente(this);" checked="checked"/>
								</td>
								<td>
										<label for="radio-facturas">Clientes que requieren factura</label>
								</td>
						</tr>
						<tr>
								<td>
										<input type="radio" name="req-factura" id="req-factura-no" value="no" onclick="llenaCliente(this);"/>
								</td>
								<td>
										<label for="radio-facturas">Clientes que no requieren factura</label>
								</td>
						</tr>
				
		  </table>
				<br>
				<table>
						<tr>
								<td><label for="fechadel">Fecha del&nbsp;&nbsp;&nbsp;</label></td>
								<td><input type="text" id="fechadel" name="fechadel" class="fechas_ordenes" onFocus="calendario(this);"/></td>
								<td style="padding-left : 5px;"><label for="fechaal">Al</label></td>
								<td><input type="text" id="fechaal" name="fechaal" class="fechas_ordenes" onFocus="calendario(this);"/></td>
						</tr>
				</table>
				<br>
				<p style="display:block; float:left;"><input type="button" class="boton" value="Buscar &raquo;" onclick="buscaPedido()"/></p>
						
		</div>
		<div id="orden-compra" style="float:left; padding-left:25px;">
				<label for="orden">Cliente</label>
				<select name="select-cliente" id="select-cliente" onchange="">
						<option value="0">Seleccione Cliente</option>
						{section name="clientesDatos" loop=$cliente}
						<option value="{$cliente[clientesDatos].0}">{$cliente[clientesDatos].1}</option>
						{/section}
				</select><br><br>
		</div>
        	<div id="orden-compra" style="float:left; padding-left:25px;">
				<label for="orden">Sucursales</label>
				<select name="select-sucursal" id="select-sucursal" onchange="">
						<option value="0">Todas</option>
						{section name="sucursalesDatos" loop=$sucursales}
						<option value="{$sucursales[sucursalesDatos].0}">{$sucursales[sucursalesDatos].1}</option>
						{/section}
				</select><br><br>
		</div>
</div>

<div style="clear:both;"></div>
<div style="margin:20px 0; width:966px">

		<table class="busca-ordenes">
        <hr />
		<caption>Detalle de Pedidos</caption>
				<thead>
						<tr>

								<th style="width:15px;">No</th>
								<th style="width:10px;"><input type="checkbox" id="sel-todos" onClick="seleccionarCheck(this, 'cuerpo-ordenes');"/></th>
								<th style="width:40px;">Pedido</th>
								<th style="width:70px;">Fecha</th>
								<th style="width:120px;">Cliente</th>
								<th style="width:80px;">Sucursal</th>
								<th style="width:90px;">Total Productos</th>
								<th style="width:90px;">Total Servicios</th>
								<th style="width:50px;">Total</th>
								<th style="width:150px;">Tipo de pago</th>
								<th style="width:100px;">Cuenta</th>
								<th style="width:50px;">Facturar Servicios</th>
						</tr>
				</thead>
		</table>
		<div id="scroll-tabla">
				<table border="0" class="cuerpo-ordenes"> 
						<tbody>
						</tbody>
				</table>
		</div>

</div>

<hr />
<p>&nbsp;</p>
<!--
<p>Generar :</p>

<table class="radios_generacion_pedidos">
						<tr>
								<td>
										<input type="radio" name="req-numero_facturas" id="una_factura_por_pedido" value="si" onclick=""/>
								</td>
								<td>
										<label for="radio-facturas">Una factura por pedido seleccionado</label>
								</td>
						</tr>
						<tr>
								<td>
										<input type="radio" name="req-numero_facturas" id="una_factura_total" value="no" onclick=""/>
								</td>
								<td>
										<label for="radio-facturas">Una factura globla para todos los pedidos seleccionados</label></td>
						</tr>
				</table>
-->
<p style="display:block; float:right;"><input type="button" class="boton" value="Generar Factura" onclick="generarFactura();"/></p>

{include file="_footer.tpl"}