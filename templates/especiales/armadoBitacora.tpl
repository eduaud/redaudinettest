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
		.fechas_ordenes{
				padding : 5px !important;
				font-size : 12px;
				width : 125px;
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
		.tabla-busquedas td, .cxp_doc td{
				padding : 5px;
				}
		.busca-pedido th, .busca-recoleccion th{
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

		.cuerpo-pedido td:first-child, .cuerpo-recoleccionCliente td:first-child, .cuerpo-recoleccionSucPed td:first-child, .cuerpo-recoleccionSucPed2 td:first-child{
				border-left : 1px #C4C5C7 solid;
				}
		.cuerpo-pedido td, .cuerpo-recoleccionCliente td, .cuerpo-recoleccionSucPed td, .cuerpo-recoleccionSucPed2 td{
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
<script>
{literal}
function buscaBitacoras(){
		var ruta = $("#select-ruta").find("option:selected").val();
		var fechadel = $("#fechadel").val();
		var fechaAl = $("#fechaal").val();
		$("#respuestaTablas").empty();
		
		var envia_datos = "ruta=" + ruta + "&fechadel=" + fechadel + "&fechaAl=" + fechaAl;
		var url = "respuestaArmadoBitacora.php";
		var respuesta = ajaxN(url, envia_datos);
		$("#respuestaTablas").html(respuesta);
		
		}
function insertaBitacora(){
		var prodPedidos = new Array();
		var recolecciones = new Array();
		var recoleccionesSucPed = new Array();
		var recoleccionesSucPed2 = new Array();
		$('table.cuerpo-pedido input[name="idPedidoCheck[]"]:checked').each(function() {
				prodPedidos.push($(this).val());
				});
		$('table.cuerpo-recoleccionCliente input[name="idRecCheck[]"]:checked').each(function() {
				recolecciones.push($(this).val());
				});
		$('table.cuerpo-recoleccionSucPed input[name="idRecSucPedCheck[]"]:checked').each(function() {
				recoleccionesSucPed.push($(this).val());
				});
		$('table.cuerpo-recoleccionSucPed2 input[name="idRecSucPedCheck2[]"]:checked').each(function() {
				recoleccionesSucPed2.push($(this).val());
				});
		
		if(prodPedidos.length == 0 && recolecciones.length == 0 && recoleccionesSucPed.length == 0 && recoleccionesSucPed2.length == 0){
				alert("Seleccione algun registro");
				}
		else{
				var envia_datos = "prodPedidos=" + prodPedidos + "&recolecciones=" + recolecciones + "&recoleccionesSuc=" + recoleccionesSucPed + "&recoleccionesSuc2=" + recoleccionesSucPed2;
				var url = "insertaBitacoraRuta.php";
				var respuesta = ajaxN(url, envia_datos);
				var datos = respuesta.split("|");
				alert(datos[0]);
				$(location).attr('href', datos[1]);
				}
				
		}
{/literal}
</script>
<h1 class="encabezado">Armado de Bit&aacute;cora</h1>

<div id="busquedas">
<p>Buscar por:</p><br>
		<div id="provedores" style="float:left; padding-left:25px;">
			<table class="tabla-busquedas">
				<tr>
				<td>
						<label for="select-ruta">Ruta</label>
				</td>
				<td colspan="3">
						<select name="select-ruta" id="select-ruta" onchange="">
								<option value="0">Seleccione Ruta</option>
								{section name="rutasDatos" loop=$rutas}
								<option value="{$rutas[rutasDatos].0}">{$rutas[rutasDatos].1}</option>
								{/section}
						</select>
				</td>
				</tr>
						<tr >
								<td><label for="fechadel">Fecha de Entrega&nbsp;&nbsp;&nbsp;</label></td>
								<td><input type="text" id="fechadel" name="fechadel" class="fechas_ordenes" onFocus="calendario(this);"/></td>
								<td style="padding-left : 5px;"><label for="fechaal">Al</label></td>
								<td><input type="text" id="fechaal" name="fechaal" class="fechas_ordenes" onFocus="calendario(this);"/></td>
						</tr>
				</table><br>
				<p style="display:block; float:right;"><input type="button" class="boton" value="Buscar &raquo;" onclick="buscaBitacoras()"/></p>
						
		</div>
</div>
<div style="clear:both;"></div>
<!-- Grid de pedidos -->
<div id="respuestaTablas">
<div style="margin:20px 0; width:100%">
		<table class="busca-pedido">
		<caption>Movimientos de Salida por Venta -</caption>
				<thead>
						<tr>
								<th style="width:20px;">No</th>
								<th style="width:22px;"><input type="checkbox" id="sel-todos" onClick="seleccionarCheck(this, 'cuerpo-pedido');"/></th>
								<th style="width:70px;">Documento</th>
								<th style="width:150px;">Ruta</th>
								<th style="width:70px;">Pedido</th>
								<th style="width:120px;">Fecha y Hora</th>
								<th style="width:150px;">Producto</th>
								<th style="width:120px;">SKU</th>
								<th style="width:70px;">Cantidad</th>
								<th style="width:67px;">Ver</th>
						</tr>
				</thead>
		</table>
		<div id="scroll-tabla">
				<table border="0" class="cuerpo-pedido"> 
						<tbody>
						{assign var="contador" value="1"}
							{section name="filasPedidos" loop=$filasPedido}
									<tr>
											<td style="width:20px; text-align:center">{$contador}</td>
											<td style="width:18px; text-align:center">
													<input type="checkbox" id="idPedidoCheck{$contador}" value="{$filasPedido[filasPedidos].0}" name="idPedidoCheck[]"/>
											</td>
											<td style="width:70px; text-align:center">{$filasPedido[filasPedidos].1}</td>
											<td style="width:150px; text-align:center">{$filasPedido[filasPedidos].2}</td>
											<td style="width:70px; text-align:center">{$filasPedido[filasPedidos].3}</td>
											<td style="width:120px; text-align:center">{$filasPedido[filasPedidos].4}</td>
											<td style="width:150px; text-align:center">{$filasPedido[filasPedidos].5}</td>
											<td style="width:120px; text-align:center">{$filasPedido[filasPedidos].6}</td>
											<td style="width:70px; text-align:center">{$filasPedido[filasPedidos].7}</td>
											<td style="width:60px; text-align:center"><a href="{$rutaAlmacen|cat:$filasPedido[filasPedidos].8}" target="_blank">Movimiento</a></td>
											<td style="display:none"><input id="idPedido{$contador}" type="hidden" value="{$filasPedido[filasPedidos].0}"/></td>
											<td style="display:none">{$contador++}</td>
										</tr>
							{sectionelse}
									<tr>
											<td>No existen movimientos con estos datos de busqueda</td>
									</tr>
							{/section}
						</tbody>
				</table>
		</div>
</div>
<!-- Grid de recoleccion de pedidos -->
<div style="margin:20px 0; width:100%">
		<table class="busca-recoleccion">
		<caption>Recoleccion de Productos a Clientes</caption>
				<thead>
						<tr>
								<th style="width:20px;">No</th>
								<th style="width:22px;"><input type="checkbox" id="sel-todos" onClick="seleccionarCheck(this, 'cuerpo-recoleccionCliente');"/></th>
								<th style="width:70px;">Documento</th>
								<th style="width:150px;">Ruta</th>
								<th style="width:70px;">Pedido</th>
								<th style="width:120px;">Fecha y Hora</th>
								<th style="width:150px;">Producto</th>
								<th style="width:120px;">SKU</th>
								<th style="width:70px;">Cantidad</th>
								<th style="width:67px;">Ver</th>
						</tr>
				</thead>
		</table>
		<div id="scroll-tabla">
				<table border="0" class="cuerpo-recoleccionCliente"> 
						<tbody>
						{assign var="contador" value="1"}
							{section name="filasRecoleccion" loop=$filasRec}
									<tr>
											<td style="width:20px; text-align:center">{$contador}</td>
											<td style="width:18px; text-align:center">
													<input type="checkbox" id="idRecCheck{$contador}" value="{$filasRec[filasRecoleccion].0}" name="idRecCheck[]"/>
											</td>
											<td style="width:70px; text-align:center">{$filasRec[filasRecoleccion].1}</td>
											<td style="width:150px; text-align:center">{$filasRec[filasRecoleccion].2}</td>
											<td style="width:70px; text-align:center">{$filasRec[filasRecoleccion].3}</td>
											<td style="width:120px; text-align:center">{$filasRec[filasRecoleccion].4}</td>
											<td style="width:150px; text-align:center">{$filasRec[filasRecoleccion].5}</td>
											<td style="width:120px; text-align:center">{$filasRec[filasRecoleccion].6}</td>
											<td style="width:70px; text-align:center">{$filasRec[filasRecoleccion].7}</td>
											<td style="width:60px; text-align:center"><a href="{$rutaOrRecCliente|cat:$filasRec[filasRecoleccion].1}" target="_blank">Movimiento</a></td>
											<td style="display:none"><input id="idRec{$contador}" type="hidden" value="{$filasRec[filasRecoleccion].0}"/></td>
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
<!-- Grid de traspasos asucursales -->
<div style="margin:20px 0; width:100%">
		<table class="busca-recoleccion">
		<caption>Traspaso de Sucursal a Cedis -- Sucursal</caption>
				<thead>
						<tr>
								<th style="width:20px;">No</th>
								<th style="width:22px;"><input type="checkbox" id="sel-todos" onClick="seleccionarCheck(this, 'cuerpo-recoleccionSucPed');"/></th>
								<th style="width:150px;">Ruta</th>
								<th style="width:100px;">Orden de recolecci&oacute;n</th>
								<th style="width:100px;">Fecha de recolecci&oacute;n</th>
								<th style="width:200px;">Sucursal</th>
								<th style="width:305px;">Direcci&oacute;n de recolecci&oacute;n</th>
								<th style="width:200px;">Observaciones</th>
								<th style="width:50px;">Ver</th>
						</tr>
				</thead>
		</table>
		<div id="scroll-tabla">
				<table border="0" class="cuerpo-recoleccionSucPed"> 
						<tbody>
						{assign var="contador" value="1"}
							{section name="filasRecoleccionSucPed" loop=$filasRecSucPed}
									<tr>
											<td style="width:20px; text-align:center">{$contador}</td>
											<td style="width:18px; text-align:center"><input type="checkbox" id="idRecSucPedCheck{$contador}" value="{$filasRecSucPed[filasRecoleccionSucPed].1}" name="idRecSucPedCheck[]"/></td>
											<td style="width:150px; text-align:center">{$filasRecSucPed[filasRecoleccionSucPed].0}</td>
											<td style="width:100px; text-align:center">{$filasRecSucPed[filasRecoleccionSucPed].1}</td>
											<td style="width:100px; text-align:center">{$filasRecSucPed[filasRecoleccionSucPed].3}</td>
											<td style="width:200px; text-align:center">{$filasRecSucPed[filasRecoleccionSucPed].4}</td>
											<td style="width:300px; text-align:center">{$filasRecSucPed[filasRecoleccionSucPed].5}</td>
											<td style="width:300px; text-align:center">{$filasRecSucPed[filasRecoleccionSucPed].6}</td>
											<td style="width:50px; text-align:center"><a href="{$rutaOrSuc|cat:$filasRecSucPed[filasRecoleccionSucPed].1}" target="_blank">Ver Orden</a></td>
											<td style="display:none"><input id="idRecSucPed{$contador}" type="hidden" value="{$filasRecSucPed[filasRecoleccionSucPed].1}"/></td>
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
<!-- Grid de traspaso de cedis -->
<div style="margin:20px 0; width:100%">
		<table class="busca-recoleccion">
		<caption>Traspaso de Sucursal a Cedis -- Pedidos</caption>
				<thead>
						<tr>
								<th style="width:20px;">No</th>
								<th style="width:22px;"><input type="checkbox" id="sel-todos" onClick="seleccionarCheck(this, 'cuerpo-recoleccionSucPed2');"/></th>
								<th style="width:150px;">Ruta</th>
								<th style="width:100px;">Orden de recolecci&oacute;n</th>
								<th style="width:100px;">Fecha y hora de recolecci&oacute;n</th>
								<th style="width:200px;">Sucursal</th>
								<th style="width:305px;">Direcci&oacute;n de recolecci&oacute;n</th>
								<th style="width:50px;">Ver</th>
						</tr>
				</thead>
		</table>
		<div id="scroll-tabla">
				<table border="0" class="cuerpo-recoleccionSucPed2"> 
						<tbody>
						{assign var="contador" value="1"}
							{section name="filasRecoleccionSucPed2" loop=$filasRecSucPed2}
									<tr>
											<td style="width:20px; text-align:center">{$contador}</td>
											<td style="width:18px; text-align:center"><input type="checkbox" id="idRecSucPedCheck2{$contador}" value="{$filasRecSucPed2[filasRecoleccionSucPed2].1}" name="idRecSucPedCheck2[]"/></td>
											<td style="width:150px; text-align:center">{$filasRecSucPed2[filasRecoleccionSucPed2].0}</td>
											<td style="width:100px; text-align:center">{$filasRecSucPed2[filasRecoleccionSucPed2].1}</td>
											<td style="width:100px; text-align:center">{$filasRecSucPed2[filasRecoleccionSucPed2].3}</td>
											<td style="width:200px; text-align:center">{$filasRecSucPed2[filasRecoleccionSucPed2].4}</td>
											<td style="width:300px; text-align:center">{$filasRecSucPed2[filasRecoleccionSucPed2].5}</td>
											<td style="width:50px; text-align:center"><a href="{$rutaOrPed|cat:$filasRecSucPed2[filasRecoleccionSucPed2].1}" target="_blank">Ver Orden</a></td>
											<td style="display:none"><input id="idRecSucPed{$contador}" type="hidden" value="{$filasRecSucPed2[filasRecoleccionSucPed2].1}"/></td>
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
</div>
<p style="display:block; float:right;"><input type="button" class="boton" value="A&ntilde;adir Bitacora" onclick="insertaBitacora();"/></p>


{include file="_footer.tpl"}
