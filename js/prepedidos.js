$(document).ready(function() {
		
		/***Parametros para inicializar el fancybox*****/
		$("#despliega").fancybox({
				
				'transitionIn'	:	'elastic',
				'transitionOut'	:	'elastic',
				'speedIn'		:	600, 
				'speedOut'		:	200, 
				'padding'		:	2, 
				'overlayShow'	:	false
				});
		});

/**Funcion para colocar formas de pago de acuerdo a la lista elegida ***/
function colocaFormasPago(combo, caso){
		var id = $(combo).find("option:selected").val();
		var envio_datos = "id=" + id;
		var url = "llenaCombosPrepedidos.php";
		$("#formas-pago option").remove();		
		ajaxCombos(url, envio_datos, 'formas-pago');
		$(".resultado-productos tbody tr").remove();
		}

/**Funcion para llenar la tabla de productos de acuerdo al tipo de lista ****/		
function buscarProductos(){
		$(".resultado-productos tbody tr").remove();
		var idLista = $("#lista-precio").find("option:selected").val();
		var producto = $("#producto").val();
		
		if(producto == ""){
				alert("Debes anotar un producto o codigo\nPara iniciar la busqueda");
				}
		else if(idLista == 0){
				alert("Debes seleccionar una lista de precio\nPara iniciar la busqueda");
				}
		else{
				$(".resultado-productos tbody tr").remove();
				var ruta = "llenaTablaPrepedidos.php";
				var envio = "id=" + idLista + "&productos=" + producto + "&caso=1&reconst=0&lista=" + idLista;
				var respuesta = ajaxN(ruta, envio);
				$(".resultado-productos tbody").append(respuesta);
				
				}
		
		}
		
function calculaImporte(cantidad, valorX){	
		var tabla = $(cantidad).parent().parent().parent().parent().attr("class"); //Obtenemos la tabla de la cual es el campo
		var cantidad = $(cantidad).val();
		cantidad = cantidad == 0 ? 1 : cantidad;
		var precioPublico = $("." + tabla + " #precio_publico" + valorX).val();
		precioPublico = precioPublico.replace('$', '');
		precioPublico = precioPublico.replace(',', '');
		
		var importe = cantidad * precioPublico;
		
		$("." + tabla + " #importe" + valorX).val("$" + formatear_pesos(importe));
		$("." + tabla + " #importe" + valorX + "_a").val(importe);
		}

function calculaTotal(cantidad){	
		var tabla = $(cantidad).parent().parent().parent().parent().attr("class"); //Obtenemos la tabla de la cual es el campo
		if(tabla == "resultado-agregados"){
		var total = 0
				$("." + tabla + " tbody tr").each(function(index) {
				
						var subtotal = $(this).find("td.sel_importe input[type='hidden']").val();
						subtotal = subtotal == 0 ? 0 : subtotal;
						total += parseInt(subtotal);
						});
				
				$("#total").val("$" + formatear_pesos(total));
				}
		}
		
function agregaRegresaProducto(producto, posX, caso, e){		
		e.preventDefault();
		
		var tabla = $(producto).parent().parent().parent().parent().attr("class"); //Obtenemos la tabla
		
		var idDetalle = $("."+tabla+" tbody tr td.sel_de_detalle_lista input#id_detalle_lista" + posX).val();
		var cantidad = $("."+tabla+" tbody tr td.sel_cantidad input#cantidad" + posX).val();
		var importeReal = $("."+tabla+" tbody tr td.sel_importe input#importe" + posX + "_a").val();
		var importeMuestra = $("."+tabla+" tbody tr td.sel_importe input#importe" + posX).val();
		

		
		/*-------Calculamos el total con los elementos que se van agregando o quitando -------------*/
		var subtotal = $("#total").val()
		subtotal = subtotal.replace('$', '');
		subtotal = subtotal.replace(',', '');
		
		subtotal = subtotal == "" ? 0 : subtotal;
		
		var importeReal = importeReal == "" ? 0 : importeReal;
		
		if(tabla == "resultado-productos"){
				var total = parseFloat(importeReal) + parseFloat(subtotal);
				}
		else{
				var total = parseFloat(subtotal) - parseFloat(importeReal);
				}
		
		$("#total").val("$" + formatear_pesos(total));
		/********************************************************************************************/
		
		var ruta = "llenaTablaPrepedidos.php";
		var idLista = $("#lista-precio").find("option:selected").val();
		var envio = "id=" + idDetalle + "&productos=0&caso=" + caso + "&reconst=0&lista=" + idLista;
		var respuesta = ajaxN(ruta, envio);
		$(producto).parent().parent().remove(); //Removemos el producto de la tabla respectiva
		//Verificamos el caso si es dos añade si es tres quita de la tabla de añadidos
		if(caso == 2){
				var agregado = 0;
				
				//Validamos que no se añada el mismo producto dos veces
				$(".resultado-agregados tbody tr").each(function(index) { 
						if($(this).find("td.sel_de_detalle_lista input").val() == idDetalle){
								agregado += 1;
								}
						});
				if(agregado == 0)	{
						$(".resultado-agregados tbody").append(respuesta);
						reconstruyeTabla("resultado-agregados", caso);
						}
				else{
						alert("Producto ya agregado");
						return false;
						}
				}
		//QUITAMOS		
		else if(caso == 3){
				$(".resultado-productos tbody").append(respuesta);
				reconstruyeTabla("resultado-productos", caso);
				}
				
		if(tabla == "resultado-productos"){
				var tablaEnvia = "resultado-agregados";
				}
		else{
				var tablaEnvia = "resultado-productos";
				}
		/*****Agregamos los valores de la linea enviada o quitada del grid *****/
				$("." + tablaEnvia + " tbody tr").each(function(index) {
						if($(this).find("td.sel_de_detalle_lista input").val() == idDetalle){
								$(this).find("td.sel_cantidad input").val(cantidad);
								$(this).find("td.sel_importe input[type='hidden']").val(importeReal);
								$(this).find("td.sel_importe input[type='text']").val(importeMuestra);
								}
						});

		}
		
function reconstruyeTabla(tablaR, casoR){
		/*****Reconstruye tabla de agregar******/
		var idDetallesArrayAgrega = new Array(); // En esta variable almacenaremos los id que conformaran la consulta para reconstruir la tabla
		var comprueba = 0;
		$("." + tablaR + " tbody tr").each(function(index) { 
				
				idDetallesArrayAgrega.push($(this).find("td.sel_de_detalle_lista input").val());
				comprueba++;
				});
	if(comprueba > 0){ //Comprobamos que el grid no este vacio
				/***Array que almacenara los valores actuales que existen en la tabla para insertarlos despues de reconstruirla ****/
				var valoresAlmacenadosAgrega = new Array();
				$("." + tablaR + " tbody tr").each(function(index) {
						var cantidadAlmacenada = $(this).find("td.sel_cantidad input").val();
						var importeRealAlmacenado = $(this).find("td.sel_importe input[type='hidden']").val();
						
						var importeMuestraAlmacenado = $(this).find("td.sel_importe input[type='hidden']").val();
						
						var idDetalleAlmacenado = $(this).find("td.sel_de_detalle_lista input").val();
						valoresAlmacenadosAgrega.push(cantidadAlmacenada + "|" + importeRealAlmacenado + "|" + importeMuestraAlmacenado + "|" + idDetalleAlmacenado);
						
						});
				
			/************************************************************************************************************************/	
				
				var ruta = "llenaTablaPrepedidos.php";
				var idLista = $("#lista-precio").find("option:selected").val();
				var envio = "id=" + idDetallesArrayAgrega + "&productos=0&caso=" + casoR + "&reconst=1&lista="  + idLista;
				var respuestaR = ajaxN(ruta, envio);
				$("." + tablaR + " tbody tr").remove();
				$("." + tablaR + " tbody").append(respuestaR);
				
				/*****Primero agregamos los valores que ya estaban en el grid  *****/
				
				var filasValores = valoresAlmacenadosAgrega.toString().split(","); //Obtenemos el numero de filas de la tabla
				var NofilasValores = filasValores.length;
				for(var k=0; k<NofilasValores; k++){
						var valoresId = filasValores[k].split("|"); //Obtenemos los valores con su correspondiente fila donde iran
						$("." + tablaR + " tbody tr").each(function(index) { //Recorremos la tabla en busca del id detalle coincidente
							/*	
								valoresId[0]	--	Cantidad
								valoresId[1]	--	Importe Real
								valoresId[2]	--	Importe Muestra
								valoresId[3] 	--	Id detalle
								
							*/
								
								if($(this).find("td.sel_de_detalle_lista input").val() == valoresId[3]){
										$(this).find("td.sel_cantidad input").val(valoresId[0]);
										$(this).find("td.sel_importe input[type='hidden']").val(valoresId[1]);
										$(this).find("td.sel_importe input[type='text']").val("$" + formatear_pesos(valoresId[2]));
										}
								});
						}
				
				
				}
		}

function guardarPrepedido(){
		var contador = 0;
		
		var cantidad = 0;
		var productos = new Array();
		var cliente = $("#cliente").val();
		var total = $("#total").val();
		var forma_pago = $("#formas-pago").find("option:selected").val();
		var listaPrecio = $("#lista-precio").find("option:selected").val();
		var vendedor = $("#vendedor").find("option:selected").val();
		
		$(".resultado-agregados tbody tr").each(function(index) {
						if($(this).find("td.sel_cantidad input").val() == "")
								cantidad += 1;
								
								productos.push($(this).find("td.sel_producto input").val() + "|" + $(this).find("td.sel_existencia input").val() + "|" + $(this).find("td.sel_pendientes input").val() + "|" + $(this).find("td.sel_disponible input").val() + "|" + $(this).find("td.sel_cantidad input").val() + "|" + $(this).find("td.sel_importe input[type='hidden']").val());
							
						contador++;
						
						});
						
		if(contador == 0){
				alert("Debes agregar un producto");
				}
		else if(cantidad > 0){
				alert("No debe haber cantidades vacias\nen productos agregados");
				}
		else if(cliente == ""){	
				alert("Debes anotar el nombre de un cliente");
				}
		else if(forma_pago == 0){
				alert("Debes elegir un metodo de pago");
				}	
		else if(vendedor == 0){
				alert("Debes seleccionar un vendedor");
				}
				
		else{
			total = total.replace('$', '');
			total = total.replace(',', '');
			var ruta = "insertaPrepedido.php";
			var envio = "cliente=" + cliente + "&total=" + total + "&listaPrecio=" + listaPrecio + "&forma_pago=" + forma_pago + "&vendedor=" + vendedor + "&productos=" + productos;
			var respuesta = ajaxCargando(ruta, envio, "respuestaG");
			alert(respuesta);
			location.reload();
			
				}

		
		}