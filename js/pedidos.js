$(document).ready(function() {
		if($("#t").val() == "YWRfcGVkaWRvcw==xx" && $("#op").val() == 1){
				cambiaVendedorP();
				/*******************Buscamos en la url que venga de prepedido***************************/
				var pagina = $(location).attr('href');
				if(/idPedido=/.test(pagina)){ 
						var idPedido = pagina.split("idPedido=");
						var envia_datos = "idPrepedido=" + idPedido[1];  //Obtenemos el id del prepedido
						$("#id_pre_pedido").val(idPedido[1]);
						var url = "prepedidoPedido.php";
						var datos = ajaxN(url, envia_datos);
						var datosP = datos.split("#"); //Separacion de los datos del cliente y de los productos de acuerdo a la respuesta del prepedido
						
						var cliente = datosP[0].split("|");  
						var productos = datosP[1].split(",");
						
						//Llenamos los datos del cliente
						$("#id_cliente").val(cliente[0]);
						$("#total_productos").val(cliente[1]);
						$('#id_sucursal_alta > option[value="' + cliente[2] + '"]').attr('selected', 'selected');
						cambiaVendedorP();
						$('#id_vendedor > option[value="' + cliente[3] + '"]').attr('selected', 'selected');
						calculaTotalPedidos();
						calculaSaldo();
						
						//Comenzamos con el ciclo que llena el grid de productos
						var numProductos = productos.length;
						for (var i = 0; i < numProductos; i++) {
								var detalleProd = productos[i].split("|");
								$("#detallePedidosProductos_2_" + i).attr("valor", detalleProd[0]); //ID del producto
								
								$("#detallePedidosProductos_3_" + i).attr("valor", detalleProd[3]); //Valor concatenado del id y producto para editar
								$("#detallePedidosProductos_3_" + i).html(detalleProd[1]); // Producto
								
								$("#detallePedidosProductos_7_" + i).attr("valor", detalleProd[2]); //Cantidad
								$("#detallePedidosProductos_7_" + i).html(detalleProd[2]);
								
								$("#detallePedidosProductos_12_" + i).attr("valor", detalleProd[4]); //Lista de precio
								$("#detallePedidosProductos_13_" + i).attr("valor", detalleProd[4]); //Lista de precio
								
								var urlAjax = "obtenListaPrecio.php"; 
								var envio_datos = 'id=' + detalleProd[4] + '&caso=3&producto=' + detalleProd[0]; 
								var listasP = ajaxN(urlAjax, envio_datos);
								$("#detallePedidosProductos_13_" + i).html(listasP);
								cambiaPrecio(i);
								cargaExistencias(i); //Cargamos la funcion de existencias
								nuevoGridFila('detallePedidosProductos'); //Cargamos una nueva fila
								}
								
								EliminaFila('detallePedidosProductos_21_' + i); //Eliminamos la fila sobrante
						}
				/***************************************************************************************************************/
				$("#id_direccion_entrega option").remove();
				$("#fila_catalogo_12").hide();
				$("#fila_catalogo_14").hide();
				
				//colocaPrefijo();
				
				
				}
	/*	else if($("#t").val() == "YWRfcGVkaWRvcw==" && $("#op").val() == 2){
				if($("#v").val() == 0){
						var opcion2 = $("#id_sucursal_alta").val();
						cambiaVendedorP();
						$("#id_vendedor option[value='" + opcion2 + "']").prop("selected", "selected");
						}
						
				calculaTotal();
				montoTotalFletes();
				calculaTotalPagos();
				calculaTotalPedidos();
				var descuento = $("#descuento_solicitado").val() * $("#total").val().replace("," , "");
				descuento = descuento / 100;
				$("#monto_descuento").val(formatear_pesos(descuento));
				
				var descuento = $("#descuento_aprobado").val() * $("#total").val().replace("," , "");
				descuento = descuento / 100;
				$("#monto_aprobado").val(formatear_pesos(descuento));
				
				var envia_datos = "referencia=" + $("#id_referencia").val();
				var url = "compruebaDescuentoPedido.php";
				var comprueba = ajaxN(url, envia_datos);
				
				if(comprueba == 0){
						$("#fila_catalogo_12").hide();
						$("#fila_catalogo_14").hide();
						}
				else{
						$("#fila_catalogo_12").show();
						$("#fila_catalogo_14").show();
						}
				
				calculaTotal();
				montoTotalFletes();
				calculaTotalPagos();
				calculaTotalPedidos();
				}*/
			
		});
		

function agregaDatos(tabla, id, caso){
		/***Parametros para inicializar el fancybox*****/
		var combo = $(id).attr('id');
		combo = combo.replace("fancy_", "");
		var id_cliente = $("#hcampo_5").val();

		if(tabla == 'bmFfY2xpZW50ZXNfZGlyZWNjaW9uZXNfZW50cmVnYQ==' && id_cliente == ""){
				alert("Elije al usuario para el que sera esta direcci\u00f3n");
				return false;
				}
		else{
				$(".fancy").fancybox({
						'autoSize'		: false,
						'width'  		: '85%',
						'height' 		: '85%',
						'type'			:	'iframe',
						'href'			:	'encabezados.php?t=' + tabla + '&k=&op=1&cadP1=MDI0ZG5CbGZqRjhibkJsZmpGOGJYQmxmakY4MQ==&cadP2=MDI0WlhCbGZqRjhhWEJsZmpGOFozQmxmakU9MQ==&tm=&hf=10&id_cliente=' + id_cliente,
						'transitionIn'	:	'elastic',
						'transitionOut'	:	'elastic',
						'speedIn'		:	600, 
						'speedOut'		:	200, 
						'padding'		:	5, 
						'overlayShow'	:	false,
						'afterClose' 	: function(){ /*función que se cumple al cerrar la capa*/
												$("#" + combo + " option").remove();
					
													//Procedemos a actualizar el select
													var selectHijo = combo;
													var urlAjax = "actualizaCombosPedidos.php";
													var envio_datos = 'caso=' + caso + '&cliente=' + id_cliente; 
													ajaxCombos(urlAjax, envio_datos, selectHijo);
												}
						});
				}
		
		
		}

		function colocaPrefijo(){
				
				if($("#v").val() == 1){
						var sucursal = $("#id_sucursal_alta").val();
						}
				else{
						var sucursal = $("#id_sucursal_alta").find("option:selected").val();
						}
				var envia_datos = "sucursal=" + sucursal;
				var url = "obtenNoPedido.php";
				var pedido = ajaxN(url, envia_datos);
				var separador = pedido.split("|");
				$("#id_pedido").val(separador[0] + separador[1]);
				$("#prefijo").val(separador[0]);
				$("#consecutivo").val(separador[1]);
				}
				
function verDatos(tabla, id, caso){
		/***Parametros para inicializar el fancybox*****/
		
		var combo = $(id).attr('id');
		combo = combo.replace("fancy_", "");
		
		if(caso == 1){
				var selCombo = "hcampo_5";
				var id_registro = $("#" + selCombo).val();
				if(id_registro == ""){
						alert("Selecciona un cliente");
						return false;
						}
				}
				
		else{
				var selCombo = "id_direccion_entrega";
				if($("#v" == 1)){
						var id_registro = $("#" + selCombo).val();
						}
				else{
						var id_registro = $("#" + selCombo).find("option:selected").val();
						}
				
				}
		
		
		
		$(".fancy").fancybox({
				'autoSize'		: false,
				'width'  		: '85%',
				'height' 		: '85%',
				'type'			:	'iframe',
				'href'			:	'encabezados.php?t=' + tabla + '&k='+id_registro+'&op=2&v=1&cadP1=MDI0ZG5CbGZqRjhibkJsZmpGOGJYQmxmakY4MQ==&cadP2=MDI0WlhCbGZqRjhhWEJsZmpGOFozQmxmakU9MQ==&stm=&hf=10&id_registro=' + id_registro,
				'transitionIn'	:	'elastic',
				'transitionOut'	:	'elastic',
				'speedIn'		:	600, 
				'speedOut'		:	200, 
				'padding'		:	5, 
				'overlayShow'	:	false
				
				});
		}
		
function cargaExistencias(pos){	
		
		//del producto obtenemos la existencia , el valor 
		
		//---------------------------
		var producto = "detallePedidosProductos_2_" + pos;
		var valor_prod = $("#" + producto).attr( "valor" );
	
	    var existencia=0;
		var apartado=0;
		var disponible=0;
		
	
		aux=ajaxR('../ajax/especiales/obtenExistenciasProductos.php?id='+valor_prod);
	
		var arrResp=aux.split("|");
		
		//mostramos el almacen
		existencia=arrResp[0];
		apartado=arrResp[1];
		disponible=arrResp[2];
		
		
		//------------------------
		
		
		$("#detallePedidosProductos_4_" + pos).text(existencia);
		$("#detallePedidosProductos_4_" + pos).attr("valor", existencia);
		
		$("#detallePedidosProductos_5_" + pos).text(apartado);
		$("#detallePedidosProductos_5_" + pos).attr("valor", apartado);
		
		$("#detallePedidosProductos_6_" + pos).text(disponible);
		$("#detallePedidosProductos_6_" + pos).attr("valor", disponible);
			
	}
		
function solicitaDescuento(){
		var operacion = $("#op").val();
		
		//if(operacion == 1)
				var referencia = $("#id_referencia").val();
				
		/*else
				var referencia = $("#id_control_pedido").val();*/
				

		var cliente = $("#hcampo_5").val();
		var total = $("#total").val();
		var sucursal = $("#id_sucursal_alta").val();
		
		if(total == "" || total == 0){
				alert("Debes insertar al menos un registro completo\nen el detalle de productos");
				}
		else if(cliente == ""){
				alert("Selecciona un cliente");
				}
		else{
				$(".descuentoFancy").fancybox({
						'autoSize'		: false,
						'width'  		: '40%',
						'height' 		: '50%',
						'type'			:	'iframe',
						'href'			:	'../especiales/descuentos.php?referencia=' + referencia + '&cliente=' + cliente + '&total=' + total + '&sucursal=' + sucursal,
						'transitionIn'	:	'elastic',
						'transitionOut'	:	'elastic',
						'speedIn'		:	600, 
						'speedOut'		:	200, 
						'padding'		:	10, 
						'overlayShow'	:	false
						
						});
				}
		}
		
		/*'afterClose' 	: function(){
												$("#fila_catalogo_12").show();
												}*/
function cambiaPrecio(pos){
				var producto = "detallePedidosProductos_2_" + pos;
				var lista = "detallePedidosProductos_13_" + pos;
				var valor_lista = $("#" + lista).attr( "valor" )
				var valor_prod = $("#" + producto).attr( "valor" );
				var idProd = valor_prod.split(":");
				
				if(valor_lista == ""){
						$("#detallePedidosProductos_14_" + pos).html("");
						$("#detallePedidosProductos_14_" + pos).attr("valor", "");
						}
				else{
						var urlAjax = "obtenListaPrecio.php"; 
						var envio_datos = 'id=' + idProd[0] + '&caso=2&lista=' + valor_lista;
						var listasP = ajaxN(urlAjax, envio_datos);
						
						
						$("#detallePedidosProductos_14_" + pos).html("$" + listasP);
						listasP = listasP.replace(",", "");
						$("#detallePedidosProductos_14_" + pos).attr("valor", listasP);
						calculaImporte(pos);
						calculaTotal();
						calculaSaldo();
						}
				}	
function calculaImporte(pos){
		var precio = $("#detallePedidosProductos_14_" + pos).attr("valor");
		var cantidad = $("#detallePedidosProductos_7_" + pos).attr("valor");
		precio = precio.replace(",", "");
		var importe = precio * cantidad;
		$("#detallePedidosProductos_16_" + pos).attr("valor", importe);
		$("#detallePedidosProductos_16_" + pos).html("$" + formatear_pesos(importe));
		calculaTotal();
		calculaSaldo();
		}


function colocaDireccion(){
		var cliente = $("#hcampo_5").val();
		var selectHijo = "id_direccion_entrega";
		var urlAjax = "llenaDireccionEntrega.php";
		var envio_datos = 'id=' + cliente;  // Se arma la variable de datos que procesara el php
		ajaxCombos(urlAjax, envio_datos, selectHijo);
		}

function requiereAutorizacion(pos){
			var tipoCobro = $("#detallePedidosPagos_3_" + pos).attr("valor");
			
			var envia_datos = "tipoCobro=" + tipoCobro;
			var url = "obtenRequiereAutorizacion.php";
			var requiere = ajaxN(url, envia_datos);
			if(requiere == 0){
					$("#detallePedidosPagos_13_" + pos).html("NO APLICA");
					$("#detallePedidosPagos_12_" + pos).attr("valor", 3);
					}
			else if(requiere == 1){
					$("#detallePedidosPagos_13_" + pos).html("NO");
					$("#detallePedidosPagos_12_" + pos).attr("valor", 2);
					}
			}
			
function requiereFormasPago(pos){
		var tipo_pago = $("#detallePedidosPagos_3_" + pos).attr("valor");
		var url = "validaDocTerm.php";
		var envia_datos = "tipo_pago=" + tipo_pago;		
		var respuestaPagos = ajaxN(url, envia_datos); //Obtenemos si local o foranea la ruta de la direccion de entrega		
		var requiereP = respuestaPagos.split("|");
		
		$("#detallePedidosPagos_16_" + pos).attr('valor', requiereP[0]);
		$("#detallePedidosPagos_17_" + pos).attr('valor', requiereP[1]);
		$("#detallePedidosPagos_18_" + pos).attr('valor', requiereP[2]);
		$("#detallePedidosPagos_30_" + pos).attr('valor', requiereP[3]);
		}

//****************************Suma total de productos*********************************/
function calculaTotal(){
		var totales = 0;
		$('table#Body_detallePedidosProductos tr').each(function(index) {
				var subtotal  = $(this).children().filter("[id^=detallePedidosProductos_16_]").attr("valor");
				subtotal = subtotal == "" ? subtotal = 0 : subtotal ;
				totales += parseFloat(subtotal);
				});
		
		$("#total").val(formatear_pesos(totales));
		$("#subtotal").val(formatear_pesos(totales));
		//calculaTotalPedidos();
		
		}

//****************************Suma total de pagos*********************************/
function calculaTotalPagos(){
		var total = 0;
		$('table#Body_detallePedidosPagos tr').each(function(index) {
		var colMonto = "11";
		var colConfirmado = "12";
				var sub = $(this).children().filter("[id^=detallePedidosPagos_" + colMonto + "_]").attr("valor");
				var confirmado = $(this).children().filter("[id^=detallePedidosPagos_" + colConfirmado + "_]").attr("valor");
				sub = confirmado == 2 ? 0 : sub;
				sub = sub == "" ? 0 : sub;
				total += parseFloat(sub);
				
				});
		
		$("#total_pagos").val(formatear_pesos(total));
		calculaSaldo();
		limitePago();
		}
//****************************Precio del flete de acuerdo a la rodada*********************************/
function totalFletes(pos){
		var url = "obtenFlete.php";
		var colRodada = 2; //Variable que nos indica el numero de columna de donde se esta sacando el tipo de rodada
		
		var rodada = $("#detallePedidosFletes_" + colRodada +"_" + pos).attr("valor");
		var direccion = $("#id_direccion_entrega").find("option:selected").val();
		
		var envia_datos = "id=" + rodada + "&direccion=" + direccion;
		
		var precio = ajaxN(url, envia_datos);
		$("#detallePedidosFletes_5_" + pos).attr("valor", precio);
		$("#detallePedidosFletes_5_" + pos).html("$" + formatear_pesos(precio));
		cantidadxPrecio(pos);
		montoTotalFletes();
		calculaSaldo();
		}
//****************************Total de cada linea de detalle de fletes*********************************/	
function cantidadxPrecio(pos){
		
		var camiones = $("#detallePedidosFletes_4_" + pos).attr("valor");
		var precios = $("#detallePedidosFletes_5_" + pos).attr("valor");
		
		camiones = camiones == "" ? camiones = 1 : camiones = camiones; 

		var total = camiones * precios;
		
		$("#detallePedidosFletes_6_" + pos).attr("valor", total);
		$("#detallePedidosFletes_6_" + pos).html("$" + formatear_pesos(total));
		montoTotalFletes();
		calculaSaldo();
		}
		
//****************************Suma total de fletes*********************************/		
function montoTotalFletes(){
		var total = 0;
		$('table#Body_detallePedidosFletes tr').each(function(index) {
		
		var sub = $(this).children().filter("[id^=detallePedidosFletes_6_]").attr("valor");
		sub = sub == "" ? 0 : sub;
		total += parseFloat(sub);
				
		});
		
		$("#total_fletes").val(formatear_pesos(total));
		calculaTotalPedidos()
		}

//****************************Total de pedido: suma de productos + fletes - descuento *********************************/
function calculaTotalPedidos(){
		var productos = $("#total_productos").val();
		productos = productos.replace(",", "");
		var fletes = $("#total_fletes").val();
		fletes = fletes.replace(",", "");
		var descuento = $("#monto_aprobado").val();
		descuento = descuento.replace(",", "");
		
		
		productos = productos == "" ? productos = 0 : productos; 
		fletes = fletes == "" ? fletes = 0 : fletes; 
		descuento = descuento == "" ? descuento = 0 : descuento; 
		
		var totalPedidoS = parseFloat(productos) + parseFloat(fletes);
		var totalPedido = parseFloat(totalPedidoS) - parseFloat(descuento);
		
		
		$("#total").val(formatear_pesos(totalPedido));
		}

//****************************Saldo final: total del pedido - total de pagos*********************************/
function calculaSaldo(){
		var totalPedido = $("#total").val();
		totalPedido = totalPedido.replace(",", "");
		var totalPagos = $("#total_pagos").val();
		totalPagos = totalPagos.replace(",", "");
		
		totalPedido = totalPedido == "" ? totalPedido = 0 : totalPedido; 
		totalPagos = totalPagos == "" ? totalPagos = 0 : totalPagos;
		

		var saldo = parseFloat(totalPedido) - parseFloat(totalPagos);
		$("#saldo").val(formatear_pesos(saldo));
		}
		
function imprimeClausula(pedido){
		window.open('../../code/pdf/imprimeClausulas.php?pedido=' + pedido, "Clausulas", "width=1000, height=1000");		
		}
		
function imprimePedido(pedido){
		window.open('../../code/pdf/imprimePedido.php?pedido=' + pedido, "Pedido", "width=1000, height=1000");		
		}

function cambiaVendedorP(){
		$("#id_vendedor option").remove();
		
		if($("#op").val()==1)
				var sucursal = $("#id_sucursal_alta").find("option:selected").val();
		else
				var sucursal = $("#id_sucursal_alta").val();
				
		var selectHijo = "id_vendedor";
		var urlAjax = "actualizaVendedores.php";
		var envio_datos = 'id=' + sucursal; 
		ajaxCombos(urlAjax, envio_datos, selectHijo);
		}
function colocaTipoPago(){
		var cliente = $("#hcampo_5").val();
		var totalPedido = $("#total").val();
		totalPedido = totalPedido.replace(",", "");
		var total = 0;
		$('table#Body_detallePedidosPagos tr').each(function(index) {
				var sub = $(this).children().filter("[id^=detallePedidosPagos_10_]").attr("valor");
				sub = sub == "" ? 0 : sub;
				total += parseFloat(sub);
				});
		var selectHijo = "id_tipo_pago";
		var envio_datos = "id=" + cliente + "&total=" + total + "&totalPedido=" + totalPedido;
		var urlAjax = "actualizaTipoPago.php";
		ajaxCombos(urlAjax, envio_datos, selectHijo);
		}
		
function limitePago(){
		var tipo_pago = $("#id_tipo_pago").find("option:selected").val();
		if(tipo_pago == 3){
				var fecha_eval = $("#fecha_limite_pago").val();
				var fecha_alta = $("#fecha_alta").val();
				var fecha_inicio = convierteFechaJava(fecha_alta);
				var fecAltaConv = convierteFechaJava(fecha_alta); //Convertimos la fecha para evaluarla en los dias habiles
				var diaHabil = diasHabiles(fecAltaConv, 30); //diaHabil es el rango con el que se avaluara 
				var fecLimConv = convierteFechaJava(fecha_eval); //Fecha de fin
				
				if((fecLimConv >= fecha_inicio) && (fecLimConv <= diaHabil)){
						var exito = 1;
						}
				else{
						$("#fecha_limite_pago").val("");
						}
				}
		}
		
		
function verificaDiasHabiles(pos){
		var fecha_entrega = $("#detallePedidosProductos_15_" + pos).attr("valor");
		var tipo_entrega = $("#detallePedidosProductos_8_" + pos).attr("valor");
		var fecha_alta = $("#fecha_limite_pago").val();
		
		var fecAltaConv = convierteFechaJava(fecha_alta); //Convertimos la fecha para evaluarla en los dias habiles
		var diaHabil = diasHabiles(fecAltaConv, 3); //diaHabil es el rango con el que se avaluara 
		var fecEntConv = convierteFechaJava(fecha_entrega); // Fecha que se evaluara
		
		var fecAltaConvVal = convierteFechaJava(fecha_alta);
		if(fecAltaConvVal > fecEntConv){
				
				$("#detallePedidosProductos_15_" + pos).attr("valor", "");
				$("#detallePedidosProductos_15_" + pos).html("");
				
				alert("La fecha de entrega debe ser mayor a la fecha actual");
				
				}
				
		else if(fecEntConv < diaHabil && tipo_entrega == 1){
				
				$("#detallePedidosProductos_15_" + pos).attr("valor", "");
				$("#detallePedidosProductos_15_" + pos).html("");
				alert("La fecha de entrega debe ser mayor a tres dias habiles\na partir de la fecha limite de pago");
				
				}
		}
		
		
function diasHabiles(fecha, dias){
		var i=0;
		while (i<dias) {
		  fecha.setTime(fecha.getTime()+24*60*60*1000); // añadimos 1 día
		  if (fecha.getDay() != 6 && fecha.getDay() != 0) // Si es diferente de 6--sabado o 0--domingo sumamos un dia mas
			i++;  
			}
		return fecha;
		}
		
function convierteFechaJava(fecha){
		var fechaCor = fecha.split("/");
		var nueva_fecha = fechaCor[1] + "/" + fechaCor[0] + "/" + fechaCor[2];
		var fechaConv = new Date(nueva_fecha);
		return fechaConv;
		}
		
function cambiarFormatoFecha(fecha, formato, delimitador_fecha){
	var fechaAMD = "";
	
	if((delimitador_fecha=="/" || delimitador_fecha=="-") && (formato=="ymd" || formato=="dmy")){
		var delimitador="";
		
		if(fecha.indexOf("-")!="-1") delimitador="-";
		else if(fecha.indexOf("/")!="-1") delimitador="/";
		var pos=fecha.indexOf(delimitador);
		//alert(delimitador);
		if(delimitador != ""){
			var iniciaPor = "";
			if(pos == 2) iniciaPor='dia';
			else if(pos == 4) iniciaPor='anio';
			
			if(iniciaPor!=""){
				var arrFecha = fecha.split(delimitador);
				
				if(formato=="ymd"){
					if(iniciaPor=='dia'){
						fechaAMD=arrFecha[2]+delimitador_fecha+arrFecha[1]+delimitador_fecha+arrFecha[0];
					} else if(iniciaPor=='anio'){
						fechaAMD=arrFecha[0]+delimitador_fecha.arrFecha[1]+delimitador_fecha+arrFecha[2];
					}
				} else if(formato=="dmy"){
					if(iniciaPor=='dia'){
						fechaAMD=arrFecha[0]+delimitador_fecha.arrFecha[1]+delimitador_fecha+arrFecha[2];
					} else if(iniciaPor=='anio'){
						fechaAMD=arrFecha[2]+delimitador_fecha.arrFecha[1]+delimitador_fecha+arrFecha[0];
					}
				}
			}
		}
	}
	
	return fechaAMD;
}
		

function imprimeRecibo(pos){
		if($("#v").val() != 1){
				alert("Los recibos solo se pueden imprimir\nen modo ver");
				}
		else{
				var pago = $("#detallePedidosPagos_3_" + pos).attr("valor");
				
				if(pago == 1){
						var idPago = $("#detallePedidosPagos_0_" + pos).attr("valor");
						var idPedido = $("#detallePedidosPagos_1_" + pos).attr("valor");
						window.open('../../code/pdf/imprimeRecibo.php?pedido=' + idPedido + '&pago=' + idPago, "Recibo de Pago", "width=1000, height=1000");	
						}
				else{
						alert("Solo se pueden imprimir pagos en efectivo.");
						}
				}
		}
function imprimeReciboCobro(pedido, pago){
		window.open('../../code/pdf/imprimeRecibo.php?pedido=' + pedido + '&pago=' + pago, "Recibo de Pago", "width=1000, height=1000");	
		}
		
function verificaModPedido(idPedido){
		var ruta = "verificaModPedido.php";
		var envio = "id=" + idPedido;
		var respuesta = ajaxN(ruta, envio);
		return respuesta;
		}		
		
function colocaDatosPago(pos){
		var usuario = $("#id_usuario").val();
		var sucursal = $("#id_sucursal_alta").val();
		var fecha = new Date();
		var mes = parseInt(fecha.getMonth()) + 1;
		var dia = fecha.getDate();
		if(dia.toString().length == 1)
				dia = "0" + dia;
		var actual = fecha.getFullYear() + "-" + mes + "-" + dia + " " + fecha.getHours() + ":" + fecha.getMinutes() + ":" + fecha.getSeconds();
		
		//alert(usuario + "\n" + sucursal + "\n" + actual);
		
		$("#detallePedidosPagos_25_" + pos).attr("valor", usuario);
		$("#detallePedidosPagos_24_" + pos).attr("valor", sucursal);
		$("#detallePedidosPagos_26_" + pos).attr("valor", actual);
		
		}
		
		
		
		
		
		