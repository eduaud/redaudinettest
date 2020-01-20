$(document).ready(function() {
		//Configuraciones de el plugin para convertir el select multiple
		$("#slct_sucursal_ventas").multipleSelect({
				placeholder: "Selecciona alguna sucursal",
				width: 550,
				multiple: true,
				multipleWidth: 120,
				minimumCountSelected: 5,
				countSelected: '# de % sucursales seleccionadas',
				allSelected: 'Todas las sucursales seleccionadas'
				});
		});
function generaReporte(opcion,idRep){
		if(idRep==0){
			var form =document.forms.formax;
			
			if (form.fecha_del.value =="" ^ form.fecha_al.value ==""){
				if (form.fecha_del.value ==""){
					alert('Favor de capturar los dos campos de fecha');
					return false;
				}
			}
		}			
		
		var centroAncho = (screen.width/2) - 400;
		var centroAlto = (screen.height/2) - 300;
		var parametros=obtenParametrosReporte(idRep,opcion);
		
		var especificaciones="top="+centroAlto+",left="+centroAncho+",toolbar=no,location=no,status=no,menubar=yes,scrollbars=yes,width=800,height=600,resizable=yes"
		window.open("procesaReportesActual.php?opcion="+opcion+"&idRep="+idRep+parametros,"_blank", especificaciones);
		
		//vamos a ver si ya existe la ventana abierta
		return true;
		}
		
function obtenParametrosReporte(idRep,opcion){
	var parametros = '';
	if(idRep == 0){
		//Obtenemos el id de los select normales
		var familia		= $("#slct_familia").find("option:selected").val();
		var tipo			= $("#slct_tipos").find("option:selected").val();
		var modelo			= $("#slct_modelo").find("option:selected").val();
		var caracteristica = $("#slct_car").find("option:selected").val();
		//Se obtiene los ID seleccionados en un select multiple en forma de array
		var productos = new Array();
		$("#slct_productos").find("option:selected").each(function() {
			productos.push($(this).attr('value'));
		});
		//Si no hay alguna opcion seleccionada en el select multiple lo igualamos a cero para las validaciones posteriores
		if(productos.length == 0)
		productos.push(0);
		
		//Armamos la cadena que se pasara como parametro hacia la generacion del reporte
		parametros += "&familia=" + familia + "&tipo=" + tipo + "&modelo=" + modelo + "&caracteristica=" + caracteristica + "&productos[]=" + productos;
		if(idRep == 4 || idRep == 18){
			var almacenes = new Array();
			$("#slct_almacenes").find("option:selected").each(function() {
				almacenes.push($(this).attr('value'));
			});
			//Si no hay alguna opcion seleccionada en el select multiple lo igualamos a cero para las validaciones posteriores
			if(almacenes.length == 0)
				almacenes.push(0);
			parametros += "&almacenes[]=" + almacenes;
		}
					
		//si esta en checked el check box de obtener a nivel detalle
		if($("#incluir_lote").is(':checked')) {  
			parametros += "&incluir_lote=1";
		} else {  
			parametros += "&incluir_lote=0";
		}  
	}
	if(idRep == 105){		
		var fechadel = $("#fechadel").val();
		var fechaal = $("#fechaal").val();
		parametros += "&fechadel=" + fechadel + "&fechaal=" + fechaal;
		
		/*var campoFecha = $('input:radio[name=campoFecha]:checked').val();
		parametros += "&campoFecha="+campoFecha;*/
	}
	if(idRep == 150) {		
		var estatus = $("#estatus-estado").find('option:selected').val();

		var fechadel = $("#fechadel").val();
		var fechaal = $("#fechaal").val();
		parametros += "&fechadel=" + fechadel + "&fechaal=" + fechaal + '&estatus=' + estatus;
		
		/*var campoFecha = $('input:radio[name=campoFecha]:checked').val();
		parametros += "&campoFecha="+campoFecha;*/
	}
        
        if(idRep == 228){
		var tipo_producto = $("#slct_tipos").find("option:selected").val();

		var productos = new Array();
		$("#slct_productos").find("option:selected").each(function() {
			productos.push($(this).attr('value'));
		});
		
		var fecha_ingreso_inicial = $("#sFechaIngresoInicial").val();
		var fecha_ingreso_final = $("#sFechaIngresoFinal").val();
		
		var proveedores = new Array();
		$("#slct_prov").find("option:selected").each(function() {
			proveedores.push($(this).attr('value'));
		});
		
		var movimientos = new Array();
		$("#slct_mov").find("option:selected").each(function() {
			movimientos.push($(this).attr('value'));
		});
		
		var busquedaSku = $("#busqueda_sku").val();
                
                 var tipoReporte = new Array();
		$("#tipo_reporte").find("option:selected").each(function() {
			tipoReporte.push($(this).attr('value'));
		});
		
		var parametros = "&tipo_producto=" + tipo_producto +
                        "&productos[]=" + productos + 
                        "&fecha_ingreso_inicial=" + fecha_ingreso_inicial + 
                        "&fecha_ingreso_final=" + fecha_ingreso_final +
                        "&proveedores[]=" + proveedores + 
                        "&movimientos[]=" + movimientos + 
                        "&busquedaClave=" + busquedaSku + 
                        "&tipoReporte=" + tipoReporte;
	}
        
	return(parametros);
}

function llenaMultiple(){
		var slct_familia		= $("#slct_familia").find("option:selected").val();
		var slct_tipo			= $("#slct_tipos").find("option:selected").val();
		var slct_modelo			= $("#slct_modelo").find("option:selected").val();
		var slct_car = $("#slct_car").find("option:selected").val();
		
		var selectHijo = "slct_productos";
		var urlAjax = "llenaMultiple.php";
		var envio_datos = 'familia=' + slct_familia + '&tipo=' + slct_tipo + '&modelo=' + slct_modelo + '&caracteristica=' + slct_car;  
		ajaxCombos(urlAjax, envio_datos, selectHijo);
		
		}
function ajaxCombosReportes(url, datos, hijo){
		$.ajax({
				async:true,
				type: "POST",
				dataType: "html",
				contentType: "application/x-www-form-urlencoded",
				data: datos,
				url:'../ajax/' + url,
				/*beforeSend:function(){
				},*/
				success: function(data) {
						$("#" + hijo).append(data);
						},
				timeout:50000
				});
		}
function limpiaBusqueda(campo){
		$("#" + campo).empty();
		}
function buscaSku(){		
		var busqueda = $("#busqueda_sku").val();
		if(busqueda.length >= 2){
				var selectHijo = "slct_productos";
				var urlAjax = "buscaSku.php";
				var envio_datos = 'busqueda=' + busqueda;  
				ajaxCombos(urlAjax, envio_datos, selectHijo);
				}
		else{
				alert("Anota al menos dos caracteres para la busqueda");
				}
					
		}
function buscaCliente(){
		var busqueda = $("#busqueda-cliente").val();
		if(busqueda.length >= 2){
				var selectHijo = "slct_clientes";
				var urlAjax = "llenaDato.php";
				var envio_datos = 'id=' + busqueda + '&dato=clientes_select_multiple';  // Se arma la variable de datos que procesara el php
				ajaxCombos(urlAjax, envio_datos, selectHijo);
				}
		else{
				alert("Anota al menos dos caracteres para la busqueda");
				}
		}
		
function buscaClienteRazonSocial(){
		var busqueda = $("#busqueda-cliente").val();
		if(busqueda.length >= 2){
				var selectHijo = "slct_clientes";
				var urlAjax = "llenaDato.php";
				var envio_datos = 'id=' + busqueda + '&dato=clientes_razon_social_multiple';  // Se arma la variable de datos que procesara el php
				ajaxCombos(urlAjax, envio_datos, selectHijo);
				}
		else{
				alert("Anota al menos dos caracteres para la busqueda");
				}
		}		
		
function buscaVendedor(){
		var busqueda = $("#busqueda-vendedor").val();
		if(busqueda.length >= 2){
				var selectHijo = "slct_vendedor";
				var urlAjax = "llenaDato.php";
				var envio_datos = 'id=' + busqueda + '&dato=vendedor_select_multiple';  // Se arma la variable de datos que procesara el php
				ajaxCombosReportes(urlAjax, envio_datos, selectHijo);
				}
		else{
				alert("Anota al menos dos caracteres para la busqueda");
				}
		}
function obtenCliente(){
		var selectHijo = "slct_clientes";
		var sucursal = $("#slct_sucursal").find("option:selected").val();
		var categoria = $("#slct_categoria").find("option:selected").val();
		var urlAjax = "getRepClientes.php";
		var envio_datos = 'sucursal=' + sucursal + "&categoria=" + categoria;  // Se arma la variable de datos que procesara el php
		ajaxCombos(urlAjax, envio_datos, selectHijo);				// Funcion JQuery 
		}
function buscaProv(){
		var busqueda = $("#busqueda-prov").val();
		if(busqueda.length >= 2){
				var selectHijo = "slct_prov";
				var urlAjax = "llenaDato.php";
				var envio_datos = 'id=' + busqueda + '&dato=slct_prov';  // Se arma la variable de datos que procesara el php
				ajaxCombosReportes(urlAjax, envio_datos, selectHijo);
				}
		else{
				alert("Anota al menos dos caracteres para la busqueda");
				}
		}
function buscaRem(){
		var busqueda = $("#busqueda-reem").val();
		if(busqueda.length >= 2){
				var selectHijo = "slct_reem";
				var urlAjax = "llenaDato.php";
				var envio_datos = 'id=' + busqueda + '&dato=slct_reem';  // Se arma la variable de datos que procesara el php
				ajaxCombosReportes(urlAjax, envio_datos, selectHijo);
				}
		else{
				alert("Anota al menos dos caracteres para la busqueda");
				}
		}
                
function buscaClave(){		
	var busqueda = $("#busqueda_sku").val();
	if(busqueda.length >= 2){
		var selectHijo = "slct_productos";
		var urlAjax = "../ajax/buscaSku.php";
		var envio_datos = 'busqueda=' + busqueda;  
		ajaxCombos(urlAjax, envio_datos, selectHijo);
	}
	else{
		alert("Anota al menos dos caracteres para la busqueda");
	}
}                