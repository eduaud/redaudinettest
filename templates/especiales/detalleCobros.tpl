{ include file="_header.tpl" pagetitle="$contentheader" }
	<script>
	{literal}
	$(document).ready(function() {
			//actualizaMontos();
			});
			
			function guardaCobroFacturas(){
					//Variables que sera actualizadas o insertadas
					var idFactura = $("#factura").val();
					var area = $("#slct_areas").find("option:selected").val();
					var observacionesF = $("#observaciones_fac").val();
					var pagos = $("#cobros").val();
					pagos = pagos.replace(',','');
					var saldo = $("#saldo").val();
					saldo = saldo.replace(',','');
					var eliminados = $("#elimina").val();
					eliminados = eliminados.replace(/^,/,''); //Eliminamos la coma al inicio de la cadena
					var datosGrid = []; //Array que llevara los datos del grid
					var vacioFecha = 0;
					var filaVAciaF = [];
					var vacioMonto = 0;
					var filaVAciaM = [];
					var numfilas = 0;
					//Recorremos el grid
					$("#gridCobrosFactura tr:not(.nuevo)").each(function() {
							//Obtenemos los valores de la fila que se esta recorriendo filtrando por el ID
							var fecha = $(this).children().children().filter("[id^=fecha_cobro]").val();
							var pagos = $(this).children().children().filter("[id^=slct_pagos]").find("option:selected").val();
							var documento = $(this).children().children().filter("[id^=documento]").val();
							var monto = $(this).children().children().filter("[id^=monto]").val();
							monto = monto.replace(",", "");
							var observaciones = $(this).children().children().filter("[id^=observaciones]").val();
							var id_detalle_cobro = $(this).children().children().filter("[id^=id_detalle_cobro]").val();
							var fila = $(this).children().children().filter("[id^=eliminaFila]").attr("data-fila");
							var registros = {}; //Array que arma los datos en formato JSON
							//Almacenamos los datos en JSON
							registros["fecha"] = fecha;
							registros["pagos"] = pagos;
							registros["documento"] = documento;
							registros["monto"] = monto;
							registros["observaciones"] = observaciones;
							registros["id_detalle_cobro"] = id_detalle_cobro;
							datosGrid.push(registros);  //Empaquetamos el conjunto de datos en un array
							
							/****Validaciones de vacios*****/
							if(fecha == ""){
									vacioFecha += 1;
									filaVAciaF.push(fila);
									}
							
							if(monto == ""){
									vacioMonto += 1;
									filaVAciaM.push(fila);
									}
							numfilas++;
							});
					
					if(vacioFecha != 0 && numfilas > 1){
							alert("El campo fecha es requerido\n\nFilas: " + filaVAciaF);
							}
					else if(vacioMonto != 0 && numfilas > 1){
							alert("El campo monto es requerido\n\nFilas: " + filaVAciaM);
							}
					else{
							var cobrosJSON = JSON.stringify(datosGrid); //Convertimos el array en un JSON legible para php
							if(vacioMonto != 0 && vacioFecha != 0 && numfilas == 1)
									var primeraFila = 1;
							else
									var primeraFila = 0;
							var envia_datos = "cobros=" + cobrosJSON + "&id_factura=" + idFactura + "&eliminados=" + eliminados + "&pagos=" + pagos + "&saldo=" + saldo + "&primeraFila=" + primeraFila + "&area=" + area + "&observacionesF=" + observacionesF;
							var url = "guardaCobrosFacturas.php";
							var respuesta = ajaxN(url, envia_datos);
							alert(respuesta);
							location.reload();
							}
					
					}
					
			function nuevaFilaGrid(tabla){
					$("#" + tabla + " tr.nuevo").clone().appendTo('#' + tabla + " tbody").removeClass().show();
					
					$("#" + tabla +  " tr").each(function(index) {
							$(this).children().filter("[id^=contador]").text(index);
							$(this).children().children().filter("[id^=fecha_cobro]").attr("id", "fecha_cobro" + index);
							$(this).children().children().filter("[id^=slct_pagos]").attr("id", "slct_pagos" + index);
							$(this).children().children().filter("[id^=documento]").attr("id", "documento" + index);
							$(this).children().children().filter("[id^=monto]").attr("id", "monto" + index);
							$(this).children().children().filter("[id^=observaciones]").attr("id", "observaciones" + index);
							$(this).children().children().filter("[id^=id_detalle_cobro]").attr("id", "id_detalle_cobro" + index);
							$(this).children().children().filter("[id^=eliminaFila]").attr("data-fila", index);
							});
					}
			function eliminaFilaGrid(fila, tabla, e){
					e.preventDefault();
					var filaActual = $(fila).attr("data-fila");
					var id_detalle = $("#id_detalle_cobro" + filaActual).val();
					if(id_detalle != ""){
							var cadenaE = $("#elimina").val();
							var conjuntoElimina = cadenaE + "," + id_detalle;
							$("#elimina").val(conjuntoElimina);
							}
					$(fila).parent().parent().remove();
					$("#" + tabla +  " tr").each(function(index) {
							$(this).children().filter("[id^=contador]").text(index);
							$(this).children().children().filter("[id^=fecha_cobro]").attr("id", "fecha_cobro" + index);
							$(this).children().children().filter("[id^=slct_pagos]").attr("id", "slct_pagos" + index);
							$(this).children().children().filter("[id^=documento]").attr("id", "documento" + index);
							$(this).children().children().filter("[id^=monto]").attr("id", "monto" + index);
							$(this).children().children().filter("[id^=observaciones]").attr("id", "observaciones" + index);
							$(this).children().children().filter("[id^=id_detalle_cobro]").attr("id", "id_detalle_cobro" + index);
							$(this).children().children().filter("[id^=eliminaFila]").attr("data-fila", index);
							});
					actualizaMontos();
					}
			
			function actualizaMontos(){	
					var totalPagos = 0;
					$("#gridCobrosFactura tr").each(function(index) {
							var monto = $(this).children().children().filter("[id^=monto]").val();
							monto = monto.replace(",", "");
							monto = monto == "" ? monto = 0 : monto = monto;
							totalPagos += parseFloat(monto);
							$("#detalles").val(formatear_pesos(totalPagos));
							});
					var total = $("#total").val();
					var cobros = $("#bancarios").val();
					total = total.replace(",", "");
					total = total == "" ? total = 0 : total = total;
					cobros = cobros.replace(",", "");
					cobros = cobros == "" ? cobros = 0 : cobros = cobros;
					var sumaCobros = parseFloat(totalPagos) + parseFloat(cobros);
					$("#cobros").val(formatear_pesos(sumaCobros));
					var saldo = total - sumaCobros;
					$("#saldo").val(formatear_pesos(saldo));
					}
			
			function calendarioCobro(campo){
					var idFecha = $(campo).attr("id");
					Calendar.setup({
						inputField     :    idFecha,
						ifFormat       :    "%d/%m/%Y",
						align          :    "BR",
						singleClick    :    true,
						zindex		   :	1000
					});
					}

			
	{/literal}
	</script>
	
	<style>
		{literal}
		.busca-producto{
				padding : 0 !important;
				padding-left : 5px !important;
				font-size : 14px;
				width : 125px;
				height : 25px !important;
				border : 1px #DBE1EB solid;
				border-radius : 4px;
				background: #FFFFFF;
				background : -moz-linear-gradient(left, #FFFFFF, #F7F9FA);
				background : -webkit-linear-gradient(left, #FFFFFF, #F7F9FA);
				background : -o-linear-gradient(left, #FFFFFF, #F7F9FA);
				background : -ms-linear-gradient(left, #FFFFFF, #F7F9FA);
				color : #6E6E6E;
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
		.obserFac{
				padding : 0 !important;
				padding-left : 5px !important;
				font-size : 12px;
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
		.cuerpo-detalle select{
				border: 1px solid #ABADB3;
				border-radius: 4px;
				color: rgb(90, 90, 90);
				padding: 4px;
				
				font-size : 12px;
				color : #999999;
				}
		.fechas_ordenes{
				padding-left : 3px !important;
				font-size : 12px;
				width : 125px;
				height : 25px;
				border : 1px #DBE1EB solid;
				border-radius : 4px;
				background: #FFFFFF;
				background : -moz-linear-gradient(left, #FFFFFF, #F7F9FA);
				background : -webkit-linear-gradient(left, #FFFFFF, #F7F9FA);
				background : -o-linear-gradient(left, #FFFFFF, #F7F9FA);
				background : -ms-linear-gradient(left, #FFFFFF, #F7F9FA);
				color : #6E6E6E;
				}
		.tabla-fac td{
				padding : 5px;
				}
		.detalleCobros th{
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
				font-size : 16px;
				font-weight : bold;
				text-align : left;
				padding : 2px 0;
				color : #404651;
				}
		.cuerpo-detalle td:first-child{
				border-left : 1px #C4C5C7 solid;
				}
		.cuerpo-detalle td{
				padding : 1px;
				font-size : 10px;
				border : 1px #C4C5C7 solid;
				border-top : none;
				border-left : none;
				}
		#scroll-tabla{
				width : auto;
				height : 350px;
				overflow : auto;
				}
		{/literal}
	</style>
</head>
<body>
<input type="hidden" id="factura" name="factura" value="{$factura}"/>
<input type="hidden" id="elimina" name="elimina"/>
<br>
<h1>Detalle de Cobros</h1>
<br>
<input type="button" class="botonSecundario" value="Guardar &raquo;" onClick="guardaCobroFacturas()"/>
&nbsp;&nbsp;<input class="botonSecundario" type="button" onClick="Redirecciona(this)" direccion="{$rooturl}code/indices/listados.php?t=YWRfZmFjdHVyYXNfYXVkaWNlbA==" value=" Listado &raquo;" name="listado">
<br><br>
<div id="busquedas">
		<table class="tabla-fac">
			{section name="valoresFacturas" loop=$facturas}
				<tr>
						<td><label for="factura">ID Control Factura</label></td>
						<td><input type="text" id="factura" name="factura" class="busca-producto" value="{$facturas[valoresFacturas].0}" readonly/></td>
						<td><label for="id_factura">Factura</label></td>
						<td><input type="text" id="id_factura" name="id_factura" class="busca-producto" value="{$facturas[valoresFacturas].10}" readonly/></td>
						
				</tr>
				<tr>
						<td><label for="cfd">Folio CFD</label></td>
						<td><input type="text" style="width:350px;" id="cfd" name="cfd" class="busca-producto" value="{$facturas[valoresFacturas].1}" readonly/></td>
						<td><label for="fecha">Fecha</label></td>
						<td><input type="text" id="fecha" name="fecha" class="busca-producto" value="{$facturas[valoresFacturas].2}" readonly/></td>
				</tr>
				<tr>
						<td><label for="cliente">Cliente</label></td>
						<td><input type="text" style="width:350px;" id="cliente" name="cliente" class="busca-producto" value="{$facturas[valoresFacturas].3}" readonly/></td>
						<td><label for="slct_areas">Area de Negocio</label></td>
						<td>
								<select id="slct_areas" style="width:200px" name="slct_areas">
										{html_options values=$area_id output=$area_nombre selected=$facturas[valoresFacturas].4}
								</select>
						</td>
				</tr>
				<tr>
						<td><label for="estatus">Estatus de Cobro</label></td>
						<td><input type="text" id="estatus" name="estatus" class="busca-producto" value="{$facturas[valoresFacturas].12}" readonly/></td>
				</tr>
				<tr>
						<td><label for="iva">IVA</label></td>
						<td><input type="text" id="iva" name="iva" class="busca-producto" value="{$facturas[valoresFacturas].5}" readonly/></td>
				</tr>
				<tr>
						<td><label for="subtotal">Subtotal</label></td>
						<td><input type="text" id="subtotal" name="subtotal" class="busca-producto" value="{$facturas[valoresFacturas].6}" readonly/></td>
						<td><label for="saldo">Saldo</label></td>
						<td><input type="text" id="saldo" name="saldo" class="busca-producto" value="{$facturas[valoresFacturas].9}" readonly/></td>
						
				</tr>
				<tr>
						<td><label for="total">Total</label></td>
						<td><input type="text" id="total" name="total" class="busca-producto" value="{$facturas[valoresFacturas].7}" readonly/></td>
						<td><label for="cobros">Cobros</label></td>
						<td><input type="text" id="cobros" name="cobros" class="busca-producto" value="{$facturas[valoresFacturas].8}" readonly/></td>
						
				</tr>
				<tr>
						<td ><label for="observaciones_fac">Observaciones</label></td>
						<td>
								<textarea rows="4" cols="50" class="obserFac" id="observaciones_fac" name="observaciones_fac">{$facturas[valoresFacturas].11}</textarea>
								
						</td>
						
				</tr>
				<input type="hidden" id="detalles" name="detalles" class="busca-producto" value="{$facturas[valoresFacturas].13}" readonly/>
				<input type="hidden" id="bancarios" name="bancarios" class="busca-producto" value="{$facturas[valoresFacturas].14}" readonly/>
			{/section}
		</table>
	
</div>
<br><br>
<div style="margin:0px 0; width:100%">
		<table class="detalleCobros">
		<caption>
				<div style="float:left; line-height:200%">Detalle de Cobros</div>
				<div style="float:left; padding-left:15px;"><input style="font-size:10px" type="button" class="boton" value="Agregar Nueva Linea &raquo;" onClick="nuevaFilaGrid('gridCobrosFactura')"/></div>
		</caption>
				<thead>
						<tr>
								<th style="width:20px;">No</th>
								<th style="width:100px;">Fecha</th>
								<th style="width:200px;">Forma de Cobro</th>
								<th style="width:120px;">Documento</th>
								<th style="width:110px;">Monto</th>
								<th style="width:350px;">Observaciones</th>
								<th style="width:26px;"></th>
						</tr>
				</thead>
		</table>
		<div id="scroll-tabla">
				<table border="0" class="cuerpo-detalle" id="gridCobrosFactura"> 
						<tbody>
								<!--Fila nueva que se clonara en caso de requerir un nuevo elemento en el grid -->
								<tr class="nuevo" style="display:none">
										<td style="width:30px; text-align:center" id="contador0"></td>
										<td style="width:120px; text-align:center"><input type="text" style="width:100px" id="fecha_cobro" onFocus="calendarioCobro(this);" readonly/></td>
										<td style="width:220px; text-align:center">
												<select id="slct_pagos" style="width:200px">
														{html_options values=$pago_id output=$pago_nombre}
												</select>
										</td>
										<td style="width:120px; text-align:center"><input type="text" style="width:100px" id="documento"/></td>
										<td style="width:120px; text-align:center"><input type="text" id="monto" onKeyDown="return noletras(event);" onChange="actualizaMontos()" style="width:100px; text-align:right"/></td>
										<td style="width:350px; text-align:center"><input type="text" style="width:330px; font-size:11px;" id="observaciones"/></td>
										<td style="display:none"><input type="hidden" id="id_detalle_cobro"/></td>
										<td style="width:19px; text-align:center">
												<a href="#" data-fila="" id="eliminaFila" onClick="eliminaFilaGrid(this, 'gridCobrosFactura', event)"><img src="../../imagenes/general/cancelar.png"/></a>
										</td>
										
								</tr>
								<!--Mostramos los elementos que ya se hayan insertado en la factura -->
								{assign var="contador" value="1"}
								{section name="cobros" loop=$detallesCobros}
								<tr>
										<td style="width:30px; text-align:center" id="contador{$contador}">{$contador}</td>
										<td style="width:120px; text-align:center">
												<input type="text" style="width:100px" value="{$detallesCobros[cobros].0}" id="fecha_cobro{$contador}" onFocus="calendarioCobro(this);" readonly/>
										</td>
										<td style="width:220px; text-align:center">
												<select id="slct_pagos{$contador}" style="width:200px">
														{html_options values=$pago_id output=$pago_nombre selected=$detallesCobros[cobros].1}
												</select>
										</td>
										<td style="width:120px; text-align:center"><input type="text" id="documento{$contador}" style="width:100px" value="{$detallesCobros[cobros].2}"/></td>
										<td style="width:120px; text-align:center"><input type="text" id="monto{$contador}" style="width:100px; text-align:right" onKeyDown="return noletras(event);" onChange="actualizaMontos()" value="{$detallesCobros[cobros].3}"/></td>
										<td style="width:350px; text-align:center"><input type="text" id="observaciones{$contador}" style="width:330px; font-size:11px;" value="{$detallesCobros[cobros].4}"/></td>
										<td style="display:none"><input type="hidden" id="id_detalle_cobro{$contador}" value="{$detallesCobros[cobros].5}"/></td>
										<td style="width:19px; text-align:center">
												<a href="#" data-fila="{$contador}" id="eliminaFila" onClick="eliminaFilaGrid(this, 'gridCobrosFactura', event)"><img src="../../imagenes/general/cancelar.png"/></a>
										</td>
										<td style="display:none">{$contador++}</td>
										
								</tr>			
								<!--Si no hay elementos insertados para esta factura inicializamos una nueva fila -->
								{sectionelse}
								<tr>
										<td style="width:30px; text-align:center" id="contador{$contador}">{$contador}</td>
										<td style="width:120px; text-align:center"><input type="text" style="width:100px" id="fecha_cobro{$contador}" onFocus="calendarioCobro(this);"readonly/></td>
										<td style="width:220px; text-align:center">
												<select id="slct_pagos{$contador}" name="slct_pagos" style="width:200px">
														{html_options values=$pago_id output=$pago_nombre}
												</select>
										</td>
										<td style="width:120px; text-align:center"><input type="text" id="documento{$contador}" style="width:100px"/></td>
										<td style="width:120px; text-align:center"><input type="text" id="monto{$contador}" onChange="actualizaMontos()" onKeyDown="return noletras(event);" style="width:100px; text-align:right"/></td>
										<td style="width:350px; text-align:center"><input type="text" id="observaciones{$contador}" style="width:330px; font-size:11px;"/></td>
										<td style="display:none"><input type="hidden" id="id_detalle_cobro{$contador}"/></td>
										<td style="width:19px; text-align:center">
												<a href="#" data-fila="{$contador}" id="eliminaFila" onClick="eliminaFilaGrid(this, 'gridCobrosFactura', event)"><img src="../../imagenes/general/cancelar.png"/></a>
										</td>
										
								</tr>
								{/section}
						</tbody>
				</table>
		</div>

</div>
{include file="_footer.tpl" aktUser=$username}