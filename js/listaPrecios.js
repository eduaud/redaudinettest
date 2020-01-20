/***********************************************FUNCIONES LISTAS DE PRECIO****************************************************/
function registraLista(){
		var id_actualiza = $("#select-lista").find("option:selected").val();
		var nombre = $("#nombre-lista").val();
		var inicio = $("#inicio_vigencia").val();
		var fin = $("#fin_vigencia").val();
		var hora_ini = $("#hora_inicio").find("option:selected").val();
		var hora_fin = $("#hora_final").find("option:selected").val();
		var requiere = $("#requiere_pago").is(':checked') ? 1 : 0;
		var precio_final_vacio = 0;
		var descuento_vacio = 0;
		
		var cortaInicio = inicio.split('/');
		var cortaFin = fin.split('/');
		var dateStart=new Date(cortaInicio[2],(cortaInicio[1]-1),cortaInicio[0]);
		var dateEnd=new Date(cortaFin[2],(cortaFin[1]-1),cortaFin[0]);
		var mayor = dateStart > dateEnd ? 1 : 0;
		var igual = inicio == fin ? 1 : 0;
		var pagos = new Array();
		var sucursales = new Array();
			
		$("input[name='checkPago[]']:checked").each(function() {
				pagos.push($(this).val());
				});
		$("input[name='checkSucursal[]']:checked").each(function() {
				sucursales.push($(this).val());
				});
		
		if(nombre == '' || inicio == '' || fin == '' || (inicio != '' && fin != '' && mayor == 1) || (inicio != '' && fin != '' && igual == 1 && hora_ini >= hora_fin) || pagos.length < 1 ||sucursales.length < 1){
				
				if(nombre == ''){
						alert("Debes anotar un nombre para la lista");
						$("#nombre-lista").css("border", "1px #D16656 solid");
						
						}
				else{
						$("#nombre-lista").css("border", "1px #DBE1EB solid");
						}
				if(inicio == ''){
						alert("Debes elegir una fecha inicial");
						$("#inicio_vigencia").css("border", "1px #D16656 solid");
						}
				else{
						$("#inicio_vigencia").css("border", "1px #DBE1EB solid");
						}
				if(fin == ''){
						alert("Debes elegir una fecha final");
						$("#fin_vigencia").css("border", "1px #D16656 solid");
						}
				else{
						$("#fin_vigencia").css("border", "1px #DBE1EB solid");
						}
				if(inicio != '' && fin != '' && mayor == 1){
						alert("Las fecha final no debe ser menor a la fecha inicial");
						$("#inicio_vigencia").css("border", "1px #6299B6 solid");
						$("#fin_vigencia").css("border", "1px #6299B6 solid");
						}
				if(inicio != '' && fin != '' && igual == 1 && hora_ini >= hora_fin){
						alert("Las hora final no debe ser menor a la hora inicial\ncuando las fechas son iguales");
						$("#hora_inicio").css("border", "1px #6299B6 solid");
						$("#hora_final").css("border", "1px #6299B6 solid");
						}
				if(pagos.length < 1){
						alert("Debes seleccionar al menos un pago");
						}
				if(sucursales.length < 1){
						alert("Debes seleccionar al menos una sucursal");
						}
				
				
				}
		else{
			
			for(var i = 1; i <= 5; i++)
					$(".campo" + i).css("border", "1px #DBE1EB solid");
					
			if($("#op").val() == 0){
					var ruta = "registraListasPrueba.php";
					var id = "";
					}
			else{
					var ruta = "actualizaListas.php";
					var id = "&id=" + id_actualiza;
					}
					
			var envio = "nombre=" + nombre + "&inicio=" + inicio + "&fin=" + fin + "&requiere=" + requiere + "&hora_ini=" + hora_ini + "&hora_fin=" + hora_fin + "&pagos=" + pagos + "&sucursales=" + sucursales + id;
			
			var banderaPorc = $("#bandera-porc").val(); //Esta bandera nos indica si se aplico porcentaje general a los productos presentes en el grid
				var contador = 1;
				var datosGrid = []; //Array que llevara los datos del grid
				$("#resultado-productos tbody tr").each(function(index) {  //Recorremos la tabla en busca de cambios individuales
						if($("#idCambio" + contador).val() == 1){  //Este campo indica si hubo cambio en el producto actual del recorrido
								var registros = {}; //Array que arma los datos en formato JSON
								//Almacenamos el id_producto y el precio calculado
								registros["precio_final"] = $("#precio_final" + contador).val();
								registros["producto"] = $("#idProducto" + contador).val();
								registros["porcentajeInd"] = $("#descuento" + contador + "_a").val();
								
								datosGrid.push(registros); //Se va almacenando en el array
								}
						contador++
						});
				var banderaProd = datosGrid.length; //Esta bandera nos indica si es mayor a cero que se aplico porcentajes individuales
				
				envio += "&banderaPorc=" + banderaPorc + "&banderaProd=" + banderaProd; //Variable que llevara los parametros por post
				
				if(banderaPorc == 1){ //Si se activo la bandera de porcentaje general enviamos los filtros y el porcentaje como parametros
						var porcentajeDesc = $("#porcentaje").val();
						var familia = $("#familia").val();
						var tipo = $("#tipo").val();
						var modelo = $("#modelo").val();
						var marca = $("#marca").val();
						var busquedaProv = $("#buscaProovedor").val();
						
						var enviaProv = "";
						if(busquedaProv == 1){
								var proveedor = $("#proveedor-select").val();
								var marcaProv = $("#marcaProv").val();
								var enviaProv = "&proveedor=" + proveedor + "&marcaProv=" + marcaProv ;
								}
						
								
						envio += "&familia=" + familia + "&tipo=" + tipo + "&modelo=" + modelo + "&marca=" + marca + "&porcentajeDesc=" + porcentajeDesc + enviaProv;
						}
				if(banderaProd > 0){ //Si se activo el cambio individual enviamos los productos como parametros
						var productosJSON = JSON.stringify(datosGrid); //Convertimos el array en un JSON legible para php
						envio += "&productos=" + productosJSON;
						}
						
				var respuesta = ajaxCargando(ruta, envio, "respuesta");
				var datos = respuesta.split("|");
				alert(datos[0]);
			
				if($("#op").val() == 0){
						var ruta = "llenaComboListas.php";
						var envio = "caso=4";
						$("#select-lista option").remove();
						ajaxCombos(ruta, envio, "select-lista");
						$('#select-lista > option[value="' + datos[1] + '"]').attr('selected', 'selected');
						$("#op").val("1");
						}
				else{
						respuesta2 = respuesta;
						}
			$("#bandera-busqueda").val("0");
			$("#bandera-sku").val("0");
			$("#buscaProovedor").val("0");
			}
		}
		
function desabilitaSelect(check){
		if ($(check).is(":checked")) {
				$('.cuerpo-filtro select').prop('disabled', 'disabled');
				$('.cuerpo-filtro select option').removeAttr("selected");
				}
		else {
				$('.cuerpo-filtro select').prop('disabled', false);
				}
		}

function seleccionaTodo(check){		
		var id = $(check).attr('id');
		var selectId = id.substring(id.indexOf('-') + 1);
		
		if ($(check).is(":checked")) {
				$(check).parent().html('Deseleccionar Todo <input type="checkbox" id="check-' + selectId + '" onclick="seleccionaTodo(this)" checked/>');
				$('#' + selectId + ' option').attr("selected", "selected");
				}
		else{
				$(check).parent().html('Seleccionar Todo <input type="checkbox" id="check-' + selectId + '" onclick="seleccionaTodo(this)"/>');
				$('#' + selectId + ' option').removeAttr("selected");
				}
		}
		
function llenaCombos(combo, hijo, caso){
		var filtrosMarca = "";
		if(caso == 1){
				$("#tipo option").remove();
				$("#modelo option").remove();
				$("#marca option").remove();
				}
		if(caso == 2){
				var familia = $("#familia").val();
				var tipo = $("#tipo").val();
				$("#modelo option").remove();
				$("#marca option").remove();
				filtrosMarca = "&familia=" + familia + "&tipo=" + tipo;
				}
		if(caso == 3){
				var familia = $("#familia").val();
				var tipo = $("#tipo").val();
				var modelo = $("#modelo").val();
				filtrosMarca = "&familia=" + familia + "&tipo=" + tipo + "&modelo=" + modelo;
				}
		if(caso == 5){
				var proveedor = $("#proveedor-select").val();
				filtrosMarca = "&proveedor=" + proveedor;
				}
		var idCombo = $(combo).val();
		var ruta = "llenaComboListas.php";
		var envio = "id=" + idCombo + "&caso=" + caso + filtrosMarca;
		var respuesta = ajaxCombos(ruta, envio, hijo);
		
		}
function buscaProduto(){
		$("#aPorc").show();
		$("#bandera-porc").val("0");
		$("#bandera-sku").val("0");
		$("table#resultado-productos tbody").remove();
		var familia = $("#familia").val();
		var tipo = $("#tipo").val();
		var modelo = $("#modelo").val();
		var marca = $("#marca").val();
		var op = $("#op").val();
		var lista = $("#select-lista").find("option:selected").val();
		
		var ruta = "llenaTablaListas.php";
		var envio = "familia=" + familia + "&tipo=" + tipo + "&modelo=" + modelo + "&marca=" + marca + "&op=" + op + "&lista=" + lista;
		var respuesta = ajaxCargando(ruta, envio, "carga-productos");
		$("#resultado-productos").append(respuesta);
		
		if(op == 1 && lista != 1)
				$("#bandera-busqueda").val("1");
		else
				$("#bandera-busqueda").val("0");
		
		
		}

function buscaProveedor(){
		$("#aPorc").show();
		$("#bandera-porc").val("0");
		$("#bandera-sku").val("0");
		$("table#resultado-productos tbody").remove();
		var proveedor = $("#proveedor-select").val();
		var marca = $("#marcaProv").val();
		var op = $("#op").val();
		var lista = $("#select-lista").find("option:selected").val();
		
		var ruta = "llenaTablaListas.php";
		var envio = "proveedor=" + proveedor + "&marcaProv=" + marca + "&op=" + op + "&lista=" + lista;
		var respuesta = ajaxCargando(ruta, envio, "carga-productos");
		$("#resultado-productos").append(respuesta);
		$("#buscaProovedor").val("1");
		
		if(op == 1 && lista != 1)
				$("#bandera-busqueda").val("1");
		else
				$("#bandera-busqueda").val("0");
		}
		
function buscaSkuProd(){
		$("#bandera-porc").val("0");
		$("#buscaProovedor").val("0");
		$("#bandera-busqueda").val("0");
		$("#bandera-sku").val("1");
		var busqueda = $("#buscaListaSKU").val();
		$("#aPorc").hide();
		
		if(busqueda.length >= 3){
				var op = $("#op").val();
				var lista = $("#select-lista").find("option:selected").val();
				$("table#resultado-productos tbody").remove();
				var ruta = "llenaTablaListasSKU.php";
				var envio_datos = "skuNombre=" + busqueda + "&op=" + op + "&lista=" + lista; 
				var respuesta = ajaxCargando(ruta, envio_datos, "carga-productos");
				$("#resultado-productos").append(respuesta);
				}
		else{
				alert("Anota al menos tres caracteres para la busqueda");
				}
		}
		
function actualizaPorcentaje(){	
		
		var porc = $("#porcentaje").val();
		
		if(porc == ""){
				alert("Anota un porcentaje de descuento");
				return false
				}
		else{
				var contador = 1;		
				$("#resultado-productos tbody tr").each(function(index) {  
						$("#descuento" + contador).val(porc + "%");
						$("#descuento" + contador + "_a").val(porc);
						var publico = $("#precio_publico" + contador).val();
						publico = publico.replace(',', '');
						var subtotal = (publico * porc) / 100;
						var precio_final = parseInt(publico) + parseInt(subtotal);
						$("#precio_final" + contador).val(precio_final);
						$("#precio_final" + contador + "_a").val("$" + formatear_pesos(precio_final));
						
						if($("#idCambio" + contador).val() == 1)
								$("#idCambio" + contador).val("0");
								
						contador++
						});
				$("#bandera-porc").val("1");
				
				}
		
		}
		
function calculaPorcentaje(porc, valorX){	
		var precioFinal = $("#precio_final" + valorX + "_a").val();
		$("#precio_final" + valorX).val(precioFinal);
		var publico = $("#precio_publico" + valorX).val();
		var cantidadPorcentual = $(porc).val();
		
		cantidadPorcentual = cantidadPorcentual.replace('-', '');
		$(porc).val(cantidadPorcentual);
		publico = publico.replace(',', '');
		
		var porcentaje = (cantidadPorcentual * 100) / publico;
		porcentaje_final = porcentaje - 100;
		porcentaje_finala = porcentaje_final.toFixed(10);
		porcentaje_finalb = porcentaje_final.toFixed(2);
		
		$("#descuento" + valorX).val(porcentaje_finalb + "%");
		$("#descuento" + valorX + "_a").val(porcentaje_finala);
		$("#idCambio" + valorX).val("1");
		}
		
function calculaPrecioFinal(precio, valorX){	
		var descuento = $("#descuento" + valorX).val();
		var precio_publico = $("#precio_publico" + valorX).val();
		
		var precio_publico_t = precio_publico.replace(",","");
		var descuento_t = descuento.replace("-","");

		var porcentaje = precio_publico_t * descuento_t;
		porcentaje = porcentaje / 100;
		
		if(/-/.test(descuento))
				var total = precio_publico_t - porcentaje;
		else
				var total = parseInt(precio_publico_t) + parseInt(porcentaje);
				
		$("#precio_final" + valorX).val(total);
		$("#precio_final" + valorX + "_a").val("$" + formatear_pesos(total));
		$("#descuento" + valorX + "_a").val(descuento);
		$("#idCambio" + valorX).val("1");
		}
		
function aplicaSignoPesos(texto, signo){
		var numeros = $(texto).val();
		if(signo == "$")
				$(texto).val(signo + numeros);
		else
				$(texto).val(numeros + signo);
		}
function quitaSignoPesos(texto, signo){
		var numero = $(texto).val();
		numero = numero.replace(signo, "");
		$(texto).val(numero);
		}
function nuevaLista(){	
		$("#aPorc").show();
		$("select option:first-child").attr("selected",true);
		$("input[type=text]").val("");
		$("input[type=checkbox]").prop("checked", "");
		$("#boton-actualizar").hide();
		ocultaGrid()
		$("#filtros select").not("#familia, #proveedor-select").empty();
		$("#filtros select option").attr("selected",false);
		$("#op").val("0");
		$("#detalles").show();
		$("#detalle-izquierdo").show();
		$("#guardaSucP").hide();
		$("#form-nueva-lista").show();
		$("#boton-registra").show();
		$("#bandera-porc").val("0");
		$("#bandera-busqueda").val("0");
		$("#bandera-sku").val("0");
		$("#buscaProovedor").val("0");
		}
		
function editarCampos(select){
		$("#bandera-porc").val("0");
		$("#bandera-sku").val("0");
		$("#buscaProovedor").val("0");
		$("#aPorc").show();
		$("#op").val("1");
		$(".tabla-detalles input[type=checkbox]").prop("checked", "");
		var id = $(select).find("option:selected").val();
		if(id == 0) {
				nuevaLista();	
				$("#op").val("0");
				return false;
				}
		else if(id == 1){ //Lista Precios Publico
				$("#form-nueva-lista").hide();
				$("#boton-registra").hide();
				$("#guardaSucP").hide();
				$("#detalle-izquierdo").hide();
				$("#detalle-derecho").css("margin", "10px 15%");
				$(".guardaSuc").show();
				
				var envia_datos = "id=" + id;
				var url = "muestraSucPublica.php";
				var respuestaSuc = ajaxN(url, envia_datos);
				var datosSucursales = JSON.parse(respuestaSuc);
				var countSuc = datosSucursales.length;
				for(var i = 0; i<countSuc; i++){
						$("#checkSuc" + datosSucursales[i]).prop("checked", "checked");
						}
				
				ocultaGrid();
				$("#boton-actualizar").attr("onclick","actualizaListaPublica()");
				$("#boton-actualizar").show();
				}
		else{ //Listas registradas
				$(".guardaSuc").hide();
				$("#detalle-izquierdo").show();
				$("#form-nueva-lista").show();
				$("#boton-registra").show();
				$("#boton-actualizar").attr("onclick","actualizaGrid()");
		
		var envia_datos = "id=" + id;
		var url = "editarListas.php";
		var respuestaEdit = ajaxN(url, envia_datos);
		var datosEdit = JSON.parse(respuestaEdit);
		
		//Llenamos el encabezado de la lista
		$("#nombre-lista").val(datosEdit.nombreLista);
		$("#inicio_vigencia").val(convierteFechaN(datosEdit.finicio));
		$("#fin_vigencia").val(convierteFechaN(datosEdit.ftermino));
		$("#hora_inicio option[value="+ datosEdit.hinicio +"]").attr("selected",true);
		$("#hora_final option[value="+ datosEdit.htermino +"]").attr("selected",true);
		datosEdit.requiere == 1 ? $("#requiere_pago").prop("checked", "checked") : $("#requiere_pago").prop("checked", "");
		
		//Detalle de pagos
		var countP = datosEdit.pagos.length;
		for(var i = 0; i<countP; i++){
				$("#checkPago" + datosEdit.pagos[i]).prop("checked", "checked");
				}
		
		//Detalle de sucursales
		var countS = datosEdit.sucursales.length;
		for(var i = 0; i<countS; i++){
				$("#checkSuc" + datosEdit.sucursales[i]).prop("checked", "checked");
				}
		
		//var countProd = datosEdit.idProd.length;
		if(datosEdit.contador > 0){
				ocultaGrid();
				/*var rutaEditar = "llenaTablaListasEditar.php";
				var envio = "productos=" + datosEdit.idProd; 
				var respuesta = ajaxCargando(rutaEditar, envio, 'respuesta');
				$("#resultado-productos").append(respuesta); */
				
				var ruta = "llenaTablaListasEditar.php";
				var envio = "lista=" + id;
				var respuesta = ajaxCargando(ruta, envio, "respuesta");
				$("#resultado-productos").append(respuesta);
				
				/*var contadorEsc = 1;
				$("#resultado-productos tbody tr").each(function(index) {
						var descuentoM = parseFloat(datosEdit.datosProd[index].porcentaje);
						var muestra = descuentoM.toFixed(2);
						$("#descuento" + contadorEsc).val(muestra + "%");
						$("#descuento" + contadorEsc + "_a").val(datosEdit.datosProd[index].porcentaje);
						
						var precioM = parseFloat(datosEdit.datosProd[index].precio_final);
						$("#precio_final" + contadorEsc).val(precioM);
						$("#precio_final" + contadorEsc + "_a").val("$" + formatear_pesos(precioM));
						contadorEsc++;
						});*/
				$("#boton-actualizar").show();
				}
		else{
				ocultaGrid();
				}
				
				
		}
}
		
		
function ocultaGrid(){		
		$("table#resultado-productos tbody").remove();
		}
function verificaOcultar(){		
		var id = $("#select-lista").find("option:selected").val();
		var envia_datos = "id=" + id;
		var url = "verificaOcultar.php";
		var result = ajaxN(url, envia_datos);
		if(result == 0){
				ocultaGrid();
				}
		else{
				alert("No puedes ocultar el grid\nsi tiene productos registrados");
				}
		}
		
function actualizaGrid(){
		if ($('#resultado-productos tbody tr').length == 0){
				alert ("Selecciona un producto");
			}
		else{
				var banderaPorc = $("#bandera-porc").val(); //Esta bandera nos indica si se aplico porcentaje general a los productos presentes en el grid
				var banderaBusca = $("#bandera-busqueda").val(); //Esta bandera nos indica si se aplico filtro dentro de la edicion de listas
				var banderaSKU = $("#bandera-sku").val(); //Esta bandera nos indica si se aplico filtro desde SKU
				var contador = 1;
				var datosGrid = []; //Array que llevara los datos del grid
				$("#resultado-productos tbody tr").each(function(index) {  //Recorremos la tabla en busca de cambios individuales
						if($("#idCambio" + contador).val() == 1){  //Este campo indica si hubo cambio en el producto actual del recorrido
								var registros = {}; //Array que arma los datos en formato JSON
								//Almacenamos el id_producto y el precio calculado
								registros["precio_final"] = $("#precio_final" + contador).val();
								registros["porcentajeInd"] = $("#descuento" + contador + "_a").val();
								registros["producto"] = $("#idProducto" + contador).val();
								datosGrid.push(registros); //Se va almacenando en el array
								}
						contador++
						});
				var banderaProd = datosGrid.length; //Esta bandera nos indica si es mayor a cero que se aplico porcentajes individuales
				
				if(banderaPorc == 0 && banderaProd == 0){
						alert("Modifica algun registro para actualizar el grid de productos");
						}
				else{
						var lista = $("#select-lista").find("option:selected").val();
						
						//Variable que llevara los parametros por post
						var envio = "lista=" + lista + "&banderaPorc=" + banderaPorc + "&banderaProd=" + banderaProd + "&banderaBusca=" + banderaBusca + "&banderaSKU" + banderaSKU;
						
						if(banderaProd > 0){ //Si se activo el cambio individual enviamos los productos como parametros
								var productosJSON = JSON.stringify(datosGrid); //Convertimos el array en un JSON legible para php
								envio += "&productos=" + productosJSON;
								}
						
						//Filtros
						var porcentajeDesc = $("#porcentaje").val();
								var familia = $("#familia").val();
								var tipo = $("#tipo").val();
								var modelo = $("#modelo").val();
								var marca = $("#marca").val();
								var busquedaProv = $("#buscaProovedor").val();
						
								var enviaProv = "";
								if(busquedaProv == 1){
										var proveedor = $("#proveedor-select").val();
										var marcaProv = $("#marcaProv").val();
										var enviaProv = "&proveedor=" + proveedor + "&marcaProv=" + marcaProv ;
										}
								
								envio += "&familia=" + familia + "&tipo=" + tipo + "&modelo=" + modelo + "&marca=" + marca + "&porcentajeDesc=" + porcentajeDesc + enviaProv;
						
						
						//Campo Porc
						var porcentajeDesc = $("#porcentaje").val();
						envio += "&porcentajeDesc=" + porcentajeDesc;
						
						//Campo SKU
						var buscaSKU = $("#buscaListaSKU").val();
						envio += "&buscaSKU=" + buscaSKU;
						
						if(banderaPorc == 1 && banderaBusca == 0 && banderaSKU == 0){
								var porcentajeDesc = $("#porcentaje").val();
								envio += "&porcentajeDesc=" + porcentajeDesc;
								}
								
						else if(banderaPorc == 1 && banderaBusca == 0 && banderaSKU == 1){
								var buscaSKU = $("#buscaListaSKU").val();
								envio += "&buscaSKU=" + buscaSKU;
								var porcentajeDesc = $("#porcentaje").val();
								envio += "&porcentajeDesc=" + porcentajeDesc;
								}
						else if(banderaPorc == 1 && banderaBusca == 1 && banderaSKU == 0){ 
								var porcentajeDesc = $("#porcentaje").val();
								var familia = $("#familia").val();
								var tipo = $("#tipo").val();
								var modelo = $("#modelo").val();
								var marca = $("#marca").val();
								
								envio += "&familia=" + familia + "&tipo=" + tipo + "&modelo=" + modelo + "&marca=" + marca + "&porcentajeDesc=" + porcentajeDesc;
								
								var porcentajeDesc = $("#porcentaje").val();
								envio += "&porcentajeDesc=" + porcentajeDesc;
								}
						var ruta = "actualizaListaGridPrueba.php";
						var respuesta = ajaxCargando(ruta, envio, "carga-productos");
						alert(respuesta);
						}
			}
		$("#bandera-porc").val("0");
		$("#bandera-sku").val("0");
		
		}
		
function actualizaListaPublica(){
		if ($('#resultado-productos tbody tr').length == 0){
				alert ("Selecciona un producto");
			}
		else{
				var banderaPorc = $("#bandera-porc").val(); //Esta bandera nos indica si se aplico porcentaje general a los productos presentes en el grid
				var contador = 1;
				var datosGrid = []; //Array que llevara los datos del grid
				$("#resultado-productos tbody tr").each(function(index) {  //Recorremos la tabla en busca de cambios individuales
						if($("#idCambio" + contador).val() == 1){  //Este campo indica si hubo cambio en el producto actual del recorrido
								var registros = {}; //Array que arma los datos en formato JSON
								//Almacenamos el id_producto y el precio calculado
								registros["precio_final"] = $("#precio_final" + contador).val();
								registros["producto"] = $("#idProducto" + contador).val();
								datosGrid.push(registros); //Se va almacenando en el array
								}
						contador++
						});
				var banderaProd = datosGrid.length; //Esta bandera nos indica si es mayor a cero que se aplico porcentajes individuales
				
				if(banderaPorc == 0 && banderaProd == 0){
						alert("Modifica algun registro para actualizar el grid de productos");
						}
				else{
						var envio = "banderaPorc=" + banderaPorc + "&banderaProd=" + banderaProd; //Variable que llevara los parametros por post
						
						if(banderaPorc == 1){ //Si se activo la bandera de porcentaje general enviamos los filtros y el porcentaje como parametros
								var porcentajeDesc = $("#porcentaje").val();
								var familia = $("#familia").val();
								var tipo = $("#tipo").val();
								var modelo = $("#modelo").val();
								var marca = $("#marca").val();
								var busquedaProv = $("#buscaProovedor").val();
						
								var enviaProv = "";
								if(busquedaProv == 1){
										var proveedor = $("#proveedor-select").val();
										var marcaProv = $("#marcaProv").val();
										var enviaProv = "&proveedor=" + proveedor + "&marcaProv=" + marcaProv ;
										}
								
								envio += "&familia=" + familia + "&tipo=" + tipo + "&modelo=" + modelo + "&marca=" + marca + "&porcentajeDesc=" + porcentajeDesc + enviaProv;
								}
						if(banderaProd > 0){ //Si se activo el cambio individual enviamos los productos como parametros
								var productosJSON = JSON.stringify(datosGrid); //Convertimos el array en un JSON legible para php
								envio += "&productos=" + productosJSON;
								}
						var ruta = "actualizaListaPublicaPrueba.php";
						var respuesta = ajaxCargando(ruta, envio, "carga-productos");
						alert(respuesta);
						}
			}
		
		}

function sucursalesPublica(){
		var sucursales = new Array();
		
		var lista = $("#select-lista").find("option:selected").val();
		$("input[name='checkSucursal[]']:checked").each(function() {
					sucursales.push($(this).val());
					});
		if(sucursales.length == 0){
				alert("Selecciona el menos una sucursal");
				}
		else{
				var ruta = "guardaSucPublicas.php";
				var envio = "sucursales=" + sucursales + "&lista=" + lista;
				var respuesta = ajaxCargando(ruta, envio, "respuesta-suc");
				$("#respuesta-suc").html(respuesta);
				setTimeout('$("#respuesta-suc").html("")', 2000);
				}
		sucursales.length=0;
		}		