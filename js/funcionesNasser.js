$(document).ready(function() {

var productoCompuesto = $("#producto_compuesto");


		if((productoCompuesto.is(':checked') && productoCompuesto.attr("type") == "checkbox") || (productoCompuesto.val() == 1 && productoCompuesto.attr("type") == "hidden")) {
			$("#divgrid_detalleProductosBasicos").css("display", "block");
		  } 
		else {
			$("#divgrid_detalleProductosBasicos").css("display", "none");
			}
		
		$("#id_estado").prepend("<option value='0'>Selecciona una opci&oacute;n</option>");
		$("#id_ciudad").prepend("<option value='0'>Selecciona una opci&oacute;n</option>");
		
		if($("#op").val() == 1){
				$('#id_estado > option[value="0"]').attr('selected', 'selected');
				$("#id_ciudad option").remove();
				$("#id_ciudad").append("<option value='0'>Selecciona una opci&oacute;n</option>");
				}
	

		
/*************************************************************************/

/****Inicializa la opcion con value 0 dentro de los combos si es un nuevo registro********************/

var urlActual = $(location).attr('href');

var root = location.protocol + '//' + location.host;

function llenaGrid(){
		if($('table#Body_detallesclientesdirent tr').length == 0){
				nuevoGridFila('detallesclientesdirent');
				}
		var fila = "";
		$('table#Body_detallesclientesdirent tr').each(function(index) {
				fila = $(this).attr("id");
				});
		var pos = fila.split("Fila");
		
		//Agregamos Calle al grid
		$("#detallesclientesdirent_2_" + pos[1]).attr("valor", $("#calle").val());
		$("#detallesclientesdirent_2_" + pos[1]).html($("#calle").val());
		//Agregamos Numero Exterior
		$("#detallesclientesdirent_3_" + pos[1]).attr("valor", $("#numero_exterior").val());
		$("#detallesclientesdirent_3_" + pos[1]).html($("#numero_exterior").val());
		//Agregamos Numero Interior
		$("#detallesclientesdirent_4_" + pos[1]).attr("valor", $("#numero_interior").val());
		$("#detallesclientesdirent_4_" + pos[1]).html($("#numero_interior").val());
		//Agregamos Colonia
		$("#detallesclientesdirent_5_" + pos[1]).attr("valor", $("#colonia").val());
		$("#detallesclientesdirent_5_" + pos[1]).html($("#colonia").val());
		//Agregamos Delegacion/Municipio
		$("#detallesclientesdirent_6_" + pos[1]).attr("valor", $("#delegacion_municipio").val());
		$("#detallesclientesdirent_6_" + pos[1]).html($("#delegacion_municipio").val());
		//Agregamos Estado
		$("#detallesclientesdirent_7_" + pos[1]).attr("valor", $("#id_estado option:selected").val());
		$("#detallesclientesdirent_8_" + pos[1]).html($("#id_estado option:selected").html());
		//Agregamos Ciudad
		$("#detallesclientesdirent_9_" + pos[1]).attr("valor", $("#id_ciudad option:selected").val());
		$("#detallesclientesdirent_10_" + pos[1]).html($("#id_ciudad option:selected").html());
		//Agregamos CP
		$("#detallesclientesdirent_11_" + pos[1]).attr("valor", $("#cp").val());
		$("#detallesclientesdirent_11_" + pos[1]).html($("#cp").val());
		//Agregamos Telefono
		$("#detallesclientesdirent_12_" + pos[1]).attr("valor", $("#telefono_facturas_1").val());
		$("#detallesclientesdirent_12_" + pos[1]).html($("#telefono_facturas_1").val());
		
		
	}


if($("#op").val() == 1 && $("#t").val() == "bmFfcHJvZHVjdG9z"){

	$("#id_familia_producto").prepend("<option value='0'>Selecciona una opci&oacute;n</option>");
		$('#id_familia_producto > option[value="0"]').attr('selected', 'selected');
		
		$("#id_tipo_producto option").remove();
		$("#id_tipo_producto").append("<option value='0'>Selecciona una opci&oacute;n</option>");
		
		//$("#id_modelo_producto option").remove();
		//$("#id_modelo_producto").append("<option value='0'>Selecciona una opci&oacute;n</option>");
		
		$("#id_modelo_producto").prepend("<option value='0'>Selecciona una opci&oacute;n</option>");
		$('#id_modelo_producto > option[value="0"]').attr('selected', 'selected');
		
		
		
		//$("#id_caracteristica_producto option").remove();
		//$("#id_caracteristica_producto").append("<option value='0'>Selecciona una opci&oacute;n</option>");
		
		$("#id_caracteristica_producto").prepend("<option value='0'>Selecciona una opci&oacute;n</option>");
		$('#id_caracteristica_producto > option[value="0"]').attr('selected', 'selected');
		
		
		
		
		$("#id_marca_producto").prepend("<option value='0'>Selecciona una opci&oacute;n</option>");
		$('#id_marca_producto > option[value="0"]').attr('selected', 'selected');
		

}
if(($("#op").val() == 1 || $("#op").val() == 2 ) && $("#t").val() == "bmFfY2xpZW50ZXM="){
		muestraDiasCredito();
		requiereFactura();
		//diasCredito();
		$('#id_estado > option[value="0"]').attr('selected', 'selected');
		$("#id_ciudad option").remove();
		$("#id_ciudad").append("<option value='0'>Selecciona una opci&oacute;n</option>");
		
		}
/*************************************************************************/		


/*******************************Funcionalidades para mostrar cuentas contables****************************************************/
	
	$(".fancybox").fancybox();

	


/***COSTEO****/
if($("#t").val() == "bmFfY29zdGVvX3Byb2R1Y3Rvcw==" && ($("#op").val() == 1 || $("#op").val() == 2)){
		calculaTotalesN('detalleCosteoProductos', 7, 'total_productos');
		obtenPorcentajeCosteo();
		calculaTotalesN('detalleCuentasPorPagarCosteo', 10, 'total_otros_servicios');
		obtenLoteMontoCosteo()
		
		}
		
/********************************ORDENES DE COMPRA*******************************************/

if($("#t").val() == "YWRfb3JkZW5lc19jb21wcmFfcHJvZHVjdG9z" && $("#op").val() == 1){
		proveedorContacto();
		obtenDatosSesion('id_usuario_solicita', 2); //El caso 1 es para obtener el id de la sucursal. El 2  para el id del usuario
		colocaFechaActual('fecha_creacion');
		colocaProveedores();
		}
		


		
/********************************RETENCIONES*******************************************/		
if(($("#t").val() == "bmFfdGlwb3NfZG9jdW1lbnRvcw==" || $("#t").val() == "bmFfY29uY2VwdG9zX3N1Ymdhc3Rvcw==") && ($("#op").val() == 1 || $("#op").val() == 2) && $("#v").val() != 1){
		activaCamposRetenciones("#aplica_retencion_iva");
		activaCamposRetenciones("#aplica_retencion_isr");
		}

/********************************EGRESOS*******************************************/		
if($("#t").val() == "YWRfZWdyZXNvcw==" && ($("#op").val() == 1 || $("#op").val() == 2)){
		muestraCheque();
		}
		
/********************************DEPOSITOS BANCARIOS Y EGRESOS*******************************************/
if(($("#t").val() == "YWRfZGVwb3NpdG9zX2JhbmNhcmlvcw==" || $("#t").val() == "YWRfZWdyZXNvcw==") && $("#op").val() == 1 ){
		colocaFechaActual('fecha');
		}
/********************************CUENTAS POR PAGAR*******************************************/
if($("#t").val() == "bmFfY3VlbnRhc19wb3JfcGFnYXI=" && $("#op").val() == 1 ){
		colocaFechaActual('fecha_captura');
		colocaProveedores();
		}
		
/********************************EMPLEADOS*******************************************/
if($("#t").val() == "bmFfZW1wbGVhZG9z"){
		ocultaCamposEmpleados();
		}
		
/********************************PROVEEDORES*******************************************/
if($("#t").val() == "bmFfcHJvdmVlZG9yZXM="){
		ocultaCamposProveedor();
		obtenCiudad();
		}
if($("#t").val() == "bmFfbm90YXNfY3JlZGl0b19wcm92ZWVkb3I=" && $("#op").val() == 1){		
		cxpNota();
		}	
/********************************VALES DE PRODUCTOS*******************************************/
if($("#t").val() == "bmFfdmFsZXNfcHJvZHVjdG9z"){
		$("#btnNuevaFila").hide();
		}
if($("#t").val() == "bmFfdmFsZXNfcHJvZHVjdG9z" && $("#op").val() == 1){	
		$("#id_pedido_relacionado option").remove();
		$("#id_pedido_relacionado").append("<option value='0'>Selecciona una opci&oacute;n</option>");
		}	
});

function ocultaCamposEmpleados(){
		if($("#v").val() == 1)
				var opcion = $("#id_tipo_entidad_financiera").val();
		else
				var opcion = $("#id_tipo_entidad_financiera").find("option:selected").val();
				
		if(opcion == 2){
				$("#fila_catalogo_3").hide();
				$("#apellido_paterno").val('');
				$("#fila_catalogo_4").hide();
				$("#apellido_materno").val('');
				}
		else if(opcion == 1){
				$("#fila_catalogo_3").show();
				$("#fila_catalogo_4").show();
				}
		
		}
		
function obtenDatosSesion(id_destino, caso){
		var envia_datos = "caso=" + caso; //El caso 1 es para obtener el id de la sucursal. El 2  para el id del usuario
		var url = "obtenSesion.php";
		var sesion = ajaxN(url, envia_datos);
		$("#" + id_destino).val(sesion); //id_usuario_solicita
		}
		

	
function obtenCiudad(id_estado,selectHijo){
	var urlAjax = "llenaCiudadCombo.php";
	var envio_datos = 'id=' + id_estado;  // Se arma la variable de datos que procesara el php
	ajaxCombos(urlAjax, envio_datos, selectHijo);
}
		
	
function colocaTipoProducto()
{
	limpiaCampos("sku", "texto");
	limpiaCampos("nombre", "texto");
	//limpiaCampos("id_modelo_producto", "combo");
	
	var selectHijo = "id_tipo_producto";
	var opcion = $("#id_familia_producto").find("option:selected").val(); //Obtenemos el id seleccionado
	
	if(opcion == 0){
		//$("#id_modelo_producto option").remove();
		//$("#id_modelo_producto").append("<option value='0'>Selecciona una opci&oacute;n</option>");
			}
	
		var urlAjax = "llenaDatosCombos.php"; // URL Donde se procesara el ajax
		var envio_datos = 'id=' + opcion + '&caso=1';  // Se arma la variable de datos que procesara el php
		ajaxCombos(urlAjax, envio_datos, selectHijo);
		/*var selectHijo = "id_caracteristica_producto";
		var envio_datos = 'id=' + opcion + '&caso=2';  // Se arma la variable de datos que procesara el php
		ajaxCombos(urlAjax, envio_datos, selectHijo);*/
		}
	
function colocaModeloProducto()
{
	limpiaCampos("sku", "texto");
	limpiaCampos("nombre", "texto");
	/*
	var selectHijo = "id_modelo_producto";
	var opcion = $("#id_tipo_producto").find("option:selected").val(); //Obtenemos el id seleccionado
	
	if(opcion == 0){
			$('#id_caracteristica_producto > option[value="0"]').attr('selected', 'selected');
			}
	
	var urlAjax = "llenaDatosCombos.php"; // URL Donde se procesara el ajax
	var envio_datos = 'id=' + opcion + '&caso=3';  // Se arma la variable de datos que procesara el php
	ajaxCombos(urlAjax, envio_datos, selectHijo);*/
										
	}

function colocaProducto()
{
	var tipo = $("#id_tipo_producto").find("option:selected").val();
	var modelo = $("#id_modelo_producto").find("option:selected").val();
	var caract = $("#id_caracteristica_producto").find("option:selected").val();


	if(tipo==0 || modelo==0 || caract==0 )
	{
		$("#sku").val("");
		$("#nombre").val("");
	}
	else
	{
	
		var envia_datos = "tipo=" + tipo + "&modelo=" + modelo + "&caract=" + caract;
		var url = "llenaProducto.php";
	
		var sigNomProducto = ajaxN(url, envia_datos);
		var sigNomFinal=sigNomProducto.split("|");
	
		$("#sku").val(sigNomFinal[1]);
		$("#nombre").val(sigNomFinal[0]);
	}
}
		
		
function cambiaValorProducto(){
		
		$('#id_caracteristica_producto > option[value="0"]').attr('selected', 'selected');
			
		limpiaCampos("sku", "texto");
		limpiaCampos("nombre", "texto");
		}
		
function gridVisible(){
		if($("#producto_compuesto").is(':checked')) {
			$("#divgrid_detalleProductosBasicos").css("display", "block");
		  } 
		else {
			$("#divgrid_detalleProductosBasicos").css("display", "none");
			
			var filas_grid = $("table#Body_detalleProductosBasicos > tbody > tr").length;
			
			for(var i=0; i <= filas_grid; i++){
			
					$("#detalleProductosBasicos_2_" + i).attr("valor", "");
					$("#detalleProductosBasicos_2_" + i).text("");
					$("#detalleProductosBasicos_2_" + i).css("background", "#FFFFFF");
					
					$("#detalleProductosBasicos_3_" + i).attr("valor", "");
					$("#detalleProductosBasicos_3_" + i).text("");
					$("#detalleProductosBasicos_3_" + i).css("background", "#FFFFFF");
					
					
					
					}
			$("table#Body_detalleProductosBasicos > tbody > tr").remove();
			nuevoGridFila('detalleProductosBasicos');
			
		  }
		}
	
function estilosCombos(id_combo){
		$("#" + id_combo).css({
				width: "200px",
				padding: "3px 5px",
				margin: "5px 0",
				border: "1px solid rgb(228, 228, 228)",
				color: "rgb(138, 138, 139)"
				});
		}
		
								function ajaxCombos(url, datos, hijo){
										$.ajax({
											async:false,
											type: "POST",
											dataType: "html",
											contentType: "application/x-www-form-urlencoded",
											data: datos,
											url:'../ajax/' + url,
											/*beforeSend:function(){
													},*/
											success: function(data) {
													
													
													$("#" + hijo + " option").remove();
													$("#" + hijo).append(data);
													
													},
											timeout:50000
											});
									}
		function ajaxN(url, datos){					
			var entrega;
			$.ajax({
					async:false,
					type: "POST",
					dataType: "html",
					contentType: "application/x-www-form-urlencoded",
					data: datos,
					url:'../ajax/' + url,
					/*beforeSend:function(){
					},*/
					success: function(data) {
							entrega = data;								
						
					},
					timeout:50000
					});
			return entrega;
			}
		
		function ajaxCargando(url, datos, cargaImagen){					
			var entrega;
			$.ajax({
					async:false,
					type: "POST",
					dataType: "html",
					contentType: "application/x-www-form-urlencoded",
					data: datos,
					url:'../ajax/' + url,
					beforeSend:function(){
							$("#" + cargaImagen).html("<img src='../../imagenes/cargando.gif'/>");
					},
					success: function(data) {
							$("#" + cargaImagen + " img").remove();
							entrega = data;								
						
					},
					timeout:5000000
					});
			return entrega;
			}
		
		function limpiaCampos(campo, tipo){
				if(tipo == "texto")
						$("#" + campo).val("");
				else if(tipo == "combo")
						$('#' + campo + ' > option[value="0"]').attr('selected', 'selected');
				}

function requiereFactura(){
		if($("#v").val() == 1){
				var requiere = $("#requiere_factura").val();
				}
		else{
				var requiere = $("#requiere_factura").is(':checked');
				}
		
		
		if(requiere == 1 || requiere == true) {
			$("#tabla_fila_catalogo_17").css("display", "block");
			$("#div_fila_catalogo_17").css("display", "block");
			var telefono = $("#telefono_1").val();
				var correo = $("#email").val();
				$("#telefono_facturas_1").val(telefono);
				$("#email_envio_facturas").val(correo);
		  } 
		else {
			$("#tabla_fila_catalogo_17").css("display", "none");
			$("#div_fila_catalogo_17").css("display", "none");
			
			limpiaCampos("nombre_razon_social" ,"texto");
			limpiaCampos("rfc","texto");
			limpiaCampos("calle","texto");
			limpiaCampos("numero_exterior","texto");
			limpiaCampos("numero_interior","texto");
			limpiaCampos("colonia","texto");
			limpiaCampos("delegacion_municipio","texto");
			limpiaCampos("cp","texto");
			limpiaCampos("telefono_facturas_1","texto");
			limpiaCampos("email_envio_facturas","texto");
			limpiaCampos("id_estado","combo");
			limpiaCampos("id_ciudad","combo");
			}
		}
		
/***********************************************FUNCIONES CUENTAS CONTABLES****************************************************/
		
function muestraDiasCredito(){
		var tipoPago = $("#id_tipo_pago").find("option:selected").val();
		if(tipoPago == 2){
				$("#fila_catalogo_14").show();
				}
		else{
				$("#fila_catalogo_14").hide();
				}
		
		
		}

function mostrarCuentasNivel2(id_cuenta_superior,id,cuenta){
	var displayNivel2 = $( "#cuentasNivel2" + id_cuenta_superior ).css('display');
	
	if(displayNivel2 == 'none'){
		$('#frmVerCuentacontable').html('<div style="background: url(../../js/fancybox/source/fancybox_overlay.png) repeat;width:50px;height:50px"><img src="../../js/fancybox/source/fancybox_loading@2x.gif" /></div>');
	}
	
	$("#linkNivel1" + id_cuenta_superior ).css("color","#095525");
	
	$( "#cuentasNivel2" + id_cuenta_superior  ).toggle( "slow", function() {
			
			$.ajax({
					type: "POST",
					url: "../ajax/especiales/mostrarCuentasContablesNivel2.php",
					data: "id_cuenta_superior=" + id_cuenta_superior+"&campoId="+id+"&campoCuenta="+cuenta,
					success: function (mostrarNivel2){
						
						if(displayNivel2 == 'none'){
								
								$.ajax({
									type: "POST",
									url: "../ajax/especiales/mostrarFormularioVerCuenta.php",
									data: "id_cuenta_contable=" + id_cuenta_superior,
									success: function (verFormulario){
										$( "#frmVerCuentacontable").html(verFormulario);
									}
								});
								
						}
						else{
								$( "#frmVerCuentacontable").html("");
								$("#linkNivel1" + id_cuenta_superior ).css("color","#4E5457");
						}
						
						$( "#cuentasNivel2" + id_cuenta_superior).html(mostrarNivel2);
						
					}
			});
			
	 });
	 
}

function mostrarCuentasNivel3(id_cuenta_contable,id,cuenta){
	$("#IDcuentaContable").attr('value',id_cuenta_contable);
	var displayNivel3 = 	$( "#cuentasNivel3" + id_cuenta_contable ).css('display');
	
	if(displayNivel3 == 'none'){
		$('#frmVerCuentacontable').html('<div style="background: url(../../js/fancybox/source/fancybox_overlay.png) repeat;width:50px;height:50px"><img src="../../js/fancybox/source/fancybox_loading@2x.gif" /></div>');
	}
	
	$("#linkNivel2" + id_cuenta_contable ).css("color","#095525");
	
	$( "#cuentasNivel3" + id_cuenta_contable  ).toggle( "slow", function() {
			
			$.ajax({
					type: "POST",
					url: "../ajax/especiales/mostrarCuentasContablesNivel3.php",
					data: "id_cuenta_contable=" + id_cuenta_contable+"&campoId="+id+"&campoCuenta="+cuenta,
					success: function (mostrarNivel3){
					
						if(displayNivel3 == 'none'){
							// Mostrar formulario lleno con los datos de la cuenta contable seleccionada
							$.ajax({
									type: "POST",
									url: "../ajax/especiales/mostrarFormularioVerCuenta.php",
									data: "id_cuenta_contable=" + id_cuenta_contable,
									success: function (verFormulario){
										$( "#frmVerCuentacontable").html(verFormulario);
									}
								});
						}
						else{
								$( "#frmVerCuentacontable").html("");
								$("#linkNivel2" + id_cuenta_contable ).css("color","#4E5457");
						}
						
						$( "#cuentasNivel3" + id_cuenta_contable).html(mostrarNivel3);
						
					}
			});
			
			
	 });
}

function agregarCuentaContable(){

	$("#frmAgregarCuentaContable").fancybox({
		maxWidth	: 800,
		maxHeight	: 600,
		fitToView	: false,
		width		: '70%',
		height		: '70%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none',
		afterClose : function(){
			//alert(window.location.pathname);
			window.location.reload(true);
			//parent.cerrarFancyboxYRedirigeAUrl(window.location.pathname);
			//parent.cerrarFancyboxYRedirigeAUrl('http://localhost/nasser/code/especiales/cuentasContables.php');
		}
	});
	
}

function obtenerCuentaSAT(cuentaSAT){
	$.ajax({
		type: "POST",
		url: "obtenerCuentaSATPorNivel.php",
		data: "cuentaSAT=" + cuentaSAT,
		success: function (comboCuentaSAT){
			$('#cuentaSAT').find('option').remove();
			$( "#cuentaSAT").append(comboCuentaSAT);
		}
	});
}

function nivelDeLaCuenta(){
	$("#es_facturable").prop("disabled","disabled");
	$("#visible_poliza").prop("disabled","disabled");
	$("#es_cuenta_mayor").prop("disabled","disabled");
	
	if($("#tipoFormularioMostrar").val() == 1){
		if($("#nivelesDeLaCuenta").val() == 1){
			$("#es_facturable").prop("checked","checked");
			$("#visible_poliza").prop("checked","checked");
		} else {
			$("#es_facturable").prop("checked","");
			$("#visible_poliza").prop("checked","");
		}
		$("#es_cuenta_mayor").prop("checked","checked");
	} else {
		$("#es_cuenta_mayor").prop("checked","");
	}
}

function mostrarFormularioAgregarCuentaContable(tipo_cuenta_contable){
	
		$.ajax({
				type: "POST",
				url: "mostrarFormularioAgregarCuentaContablePorNivel.php",
				data: "tipo_cuenta_contable=" + tipo_cuenta_contable,
				success: function (formularioActivar){
					//alert(mostrarNivel3);
					$( "#frmAgregarCuentaContableTipoNivel").html(formularioActivar);
					$("#mensajeNivelCuentaContable").html("");
					nivelDeLaCuenta();
					if($("#tipoFormularioMostrar").val() == 1){
						obtenerCuentaSAT("");
					}
				}
		});
	
}

function llenarComboCuentaSuperior(id_cuenta_mayor){
	
	$("#mensajeCuentaMayor").html("");
	
	$.ajax({
				type: "POST",
				url: "filtrarComboPorCuentaMayor.php",
				data: "id_cuenta_mayor=" + id_cuenta_mayor,
				success: function (mostrarComboCuentaMayor){
					//alert(mostrarNivel3);
					$( "#selectCuentaSuperior").html(mostrarComboCuentaMayor);
					obtenerPropiedadesCuentaMayor(id_cuenta_mayor);
					obtenerNivelesDeLaCuenta(id_cuenta_mayor)
				}
		});
	
}

function registrarCuentaContable(accion){

	var nivelCuenta = $("#nivel_cuenta_contable").val();
	
	if( nivelCuenta == "" ){
			$("#mensajeNivelCuentaContable").css("color","#fd0606").html(" * Selecciona el nivel de la cuenta a registrar");
			//alert('Elige una cuenta mayor para poder registrar');
			return false;
	}
	
	if(nivelCuenta == 3 || nivelCuenta == 2){
		
		var id_cuenta_mayor = $("#preLlenarCuenta").val();
		
		if( id_cuenta_mayor == 0 ){
			$("#mensajeCuentaMayor").css("color","#fd0606").html(" * Cuenta Mayor Obligatoria");
			//alert('Elige una cuenta mayor para poder registrar');
			return false;
		}
		
	}
	
	if(nivelCuenta == 3){
	
		var id_cuenta_superior = $("#id_cuenta_superior").val();
	
		if( id_cuenta_superior == 0 ){
			$("#mensajeCuentaSuperior").css("color","#fd0606").html(" * Cuenta Superior Obligatoria");
			//alert('Elige una cuenta mayor para poder registrar');
			return false;
		}
	}
	
	if( $("#id_cuenta_contable").val() == "" ){
			$("#mensajeCuentaContable").css("color","#fd0606").html(" * Obligatorio");
			return false;
	}
		
	if( $("#nombre_cuenta_contable").val() == "" ){
			$("#mensajeNombreCuentaContable").css("color","#fd0606").html(" * Obligatorio");
			//alert('Elige una cuenta mayor para poder registrar');
			return false;
	}
	
	if( $("#id_genero_cuenta_contable").val() == 0 ){
			$("#mensajeGeneroCuentaContable").css("color","#fd0606").html(" * Genero de Cuenta Obligatoria");
			//alert('Elige una cuenta mayor para poder registrar');
			return false;
	}
	var cuenta_contable			  		= $("#id_cuenta_contable").val();
	var nombre_cuenta_contable	  	= $("#nombre_cuenta_contable").val();
	var id_genero_cuenta_contable 	= $("#id_genero_cuenta_contable").val();
	var es_cuenta_mayor			  		= ($("#es_cuenta_mayor:checked").val() == '1') ? $("#es_cuenta_mayor:checked").val() : '0';
	var es_facturable		   			  		= ($("#es_facturable:checked").val() == '1') ? $("#es_facturable:checked").val() : '0';
	var visible_arbol			   			  		= ($("#visible_arbol:checked").val() == '1') ? $("#visible_arbol:checked").val() : '0';
	var visible_poliza			   			  		= ($("#visible_poliza:checked").val() == '1') ? $("#visible_poliza:checked").val() : '0';
	var activo						   			  		= ($("#activo:checked").val() == '1') ? $("#activo:checked").val() : '0';
	
	if (nivelCuenta == 1){
		var id_cuenta_contable = $("#llave").val();
		var nivelesDeLaCuenta=$("#nivelesDeLaCuenta").val();
		var cuentaSAT=$("#cuentaSAT").val();
		var arrData = "nivel_cuenta=" + nivelCuenta + "&id_cuenta_contable=" + id_cuenta_contable+"&cuenta_contable=" + cuenta_contable + "&nombre_cuenta_contable=" + nombre_cuenta_contable + 
									"&id_genero_cuenta_contable=" + id_genero_cuenta_contable + "&es_cuenta_mayor=" + es_cuenta_mayor + "&es_facturable=" + es_facturable + 
									"&visible_arbol=" + visible_arbol + "&visible_poliza=" + visible_poliza + "&activo=" + activo +
									"&nivelesDeLaCuenta=" + nivelesDeLaCuenta + "&cuentaSAT=" + cuentaSAT;
	}
	
	if (nivelCuenta == 2){
		
		var cuentaMayor=$("#preLlenarCuentaName").val();
		var cuentaSAT=$("#cuentaSAT").val();
		var arrData = "nivel_cuenta=" + nivelCuenta + "&cuenta_contable=" + cuenta_contable + "&nombre_cuenta_contable=" + nombre_cuenta_contable + 
									"&id_genero_cuenta_contable=" + id_genero_cuenta_contable + "&es_cuenta_mayor=" + es_cuenta_mayor + "&es_facturable=" + es_facturable + 
									"&visible_arbol=" + visible_arbol + "&visible_poliza=" + visible_poliza + "&activo=" + activo + "&id_cuenta_mayor=" + id_cuenta_mayor +"&cuenta_mayor=" + cuentaMayor +"&cuentaSAT=" + cuentaSAT;
	console.log(arrData);
	}
	
	if (nivelCuenta == 3){
		var arrData = "nivel_cuenta=" + nivelCuenta + "&cuenta_contable=" + cuenta_contable + "&nombre_cuenta_contable=" + nombre_cuenta_contable + 
									"&id_genero_cuenta_contable=" + id_genero_cuenta_contable + "&es_cuenta_mayor=" + es_cuenta_mayor + "&es_facturable=" + es_facturable + 
									"&visible_arbol=" + visible_arbol + "&visible_poliza=" + visible_poliza + "&activo=" + activo + "&id_cuenta_mayor=" + id_cuenta_mayor + "&id_cuenta_superior=" + 
									id_cuenta_superior;
	}
	
	if(accion == 'registrar'){
	
		$.ajax({
					type: "POST",
					url: "guardarCuentaContable.php",
					data: arrData,
					success: function (actualizaDivCuentasContables){
						if(actualizaDivCuentasContables == 1){
						
							if(nivelCuenta == 1){
								//Actualizar el DIV de cuentas Nivel 1
								
								$.ajax({
										type: "POST",
										url: "actualizaDivCuentasNivel1.php",
										//url: "cuentasContables.php",
										data: "nivelCuenta=" + nivelCuenta,
										success: function (actualizaCuentasNivel1){
											// Limpiar formulario para capturar nuevas cuentas
											alert('Cuenta Contable Registrada');
											parent.jQuery( "#cuentasNivel1").html(actualizaCuentasNivel1).fancybox();
											limpiaForm($("#frmCapturaDeCuentasContables"));
										}
								});
								
							}
							else{
							// Limpiar Formulario para capturar nuevas cuentas
							alert('Cuenta Contable Registrada');
							limpiaForm($("#frmCapturaDeCuentasContables"));
							}
							
						}
						else{
							alert(actualizaDivCuentasContables);
						}
					}
			});
		}
		
		if(accion == 'editar'){
			//alert(arrData);
			var CuentaSup=$("#preLlenarCuentaName").val();
			var llave = $("#llave").val();
			var id_genero_cuenta_contable_aux 	= $("#id_genero_cuenta_contable_aux").val();
			arrData = arrData +  '&llave=' + llave + '&id_genero_cuenta_contable_aux=' + id_genero_cuenta_contable_aux+"&CuentaSup="+CuentaSup;
			
			$.ajax({
					type: "POST",
					url: "editarCuentaContable.php",
					data: arrData, 
					success: function (actualizaDivCuentasContables){
						if(actualizaDivCuentasContables == 1){
						
							if(nivelCuenta == 1){
								//Actualizar el DIV de cuentas Nivel 1
								
								$.ajax({
										type: "POST",
										url: "actualizaDivCuentasNivel1.php",
										//url: "cuentasContables.php",
										data: "nivelCuenta=" + nivelCuenta,
										success: function (actualizaCuentasNivel1){
											// Limpiar formulario para capturar nuevas cuentas
											alert('Cuenta Contable Actualizada');
											parent.jQuery( "#cuentasNivel1").html(actualizaCuentasNivel1).fancybox();
											//limpiaForm($("#frmCapturaDeCuentasContables"));
										}
								});
								
							}
							else{
							// Limpiar Formulario para capturar nuevas cuentas
							alert('Cuenta Contable Actualizada');
							//limpiaForm($("#frmCapturaDeCuentasContables"));
							}
							
						}
						else{
							alert(actualizaDivCuentasContables);
						}
					}
			});
		}
}

function displayDivDetalleFormulario(id_cuenta_contable){
	$("#IDcuentaContable").attr('value',id_cuenta_contable);
	$('#frmVerCuentacontable').html('<div style="background: url(../../js/fancybox/source/fancybox_overlay.png) repeat;width:50px;height:50px"><img src="../../js/fancybox/source/fancybox_loading@2x.gif" /></div>');
	
	$.ajax({
			type: "POST",
			url: "../ajax/especiales/mostrarFormularioVerCuenta.php",
			data: "id_cuenta_contable=" + id_cuenta_contable,
			success: function (verFormulario){
				$( "#frmVerCuentacontable").html(verFormulario);
			}
	});
	
}

function verificaObligatorioCuentaMayorNivel2(id_cuenta_mayor){
	$("#mensajeCuentaMayor").html("");
	$("#mensajeCuentaSuperior").html("");
	preLlenarCuentaContable(id_cuenta_mayor);
	obtenerPropiedadesCuentaMayor(id_cuenta_mayor);
}

function obtenerNivelesDeLaCuenta(id_cuenta_mayor){
	if(id_cuenta_mayor != 0){
		$.ajax({
			type: "POST",
			url: "../ajax/especiales/obtenerNivelesDeLaCuenta.php",
			data: "id_cuenta_mayor=" + id_cuenta_mayor,
			success: function (obtenerNiveles){
				if(obtenerNiveles == "0"){
					$("#es_facturable").prop("checked","");
					$("#visible_poliza").prop("checked","");
					$("#es_facturable").prop("disabled","disabled");
					$("#visible_poliza").prop("disabled","disabled");
					$("#botonRegistra").prop("disabled","disabled");
					alert("No puede generar una subcuenta de nivel "+$("#tipoFormularioMostrar").val()+" para la cuenta de mayor seleccionada, debido a que no tiene niveles registrados");
				} else if(obtenerNiveles == $("#tipoFormularioMostrar").val()) {
					$("#es_facturable").prop("checked","checked");
					$("#visible_poliza").prop("checked","checked");
					$("#botonRegistra").prop("disabled","");
				} else if(obtenerNiveles < $("#tipoFormularioMostrar").val()) {
					$("#botonRegistra").prop("disabled","disabled");
					$("#es_facturable").prop("checked","");
					$("#visible_poliza").prop("checked","");
					alert("No puede generar una subcuenta de nivel "+$("#tipoFormularioMostrar").val()+" para la cuenta de mayor seleccionada, debido a que fue registrada con "+obtenerNiveles+" nivel(s)");
				} else if(obtenerNiveles > $("#tipoFormularioMostrar").val()) {
					$("#es_facturable").prop("checked","");
					$("#visible_poliza").prop("checked","");
				} else {
					$("#botonRegistra").prop("disabled","");
				}
				
			}
		});
	}
}

function obtenerPropiedadesCuentaMayor(id_cuenta_mayor){
	
	if(id_cuenta_mayor != 0){
	
		$.ajax({
				type: "POST",
				url: "../ajax/especiales/propiedadesHeredaDeCuentaMayor.php",
				data: "id_cuenta_mayor=" + id_cuenta_mayor,
				success: function (obtenerPropiedades){
					var aDetalleCuentaMayor = $.parseJSON(obtenerPropiedades);
					if(aDetalleCuentaMayor.length != 0 ){
						var id_genero_cuenta_contable = aDetalleCuentaMayor[0][0];
						var facturable = aDetalleCuentaMayor[0][1];
						var cuentaSAT = aDetalleCuentaMayor[0][2];
						$("#id_genero_cuenta_contable_aux").val(id_genero_cuenta_contable);
						$("#id_genero_cuenta_contable").prop("disabled", "enabled").val(id_genero_cuenta_contable);
						/*if(facturable == 1){
							$("#es_facturable").prop("checked", "checked");
						}
						else{
							$("#es_facturable").prop("checked", false);
						}*/
						if($("#tipoFormularioMostrar").val() == 2){
							obtenerNivelesDeLaCuenta(id_cuenta_mayor);
						}
						obtenerCuentaSAT(cuentaSAT);
					}
				}
		});
		
	}
}

function cerrarFancyboxYRedirigeAUrl(url){
    $.fancybox.close();
    window.location = url;
}
function cerrarFancybox(cuentaId,CampoVal,CampoId){
	$("#"+CampoId).attr('value',cuentaId);
	$.fancybox.close();
	$.ajax({
		type: "POST",
			url: "../ajax/MostrarCuenta.php",
			data: "id_cuenta_contable=" + cuentaId,
			success: function (respuesta){
				$("#"+CampoVal).attr('value',respuesta);
			}
	});
}
function cerrarFancyboxCampos(campos,id){
	$("#campos").attr('value',campos);
	$("#id").attr('value',id);
	$.fancybox.close();
}
function verificaObligatorioCuentaContable(){
	$("#mensajeGeneroCuentaContable").html("");
}

function preLlenarCuentaContable(id_cuenta_mayor){
	url="obtenCuentaContable.php";
	datos="id_cuenta_mayor="+id_cuenta_mayor;
	var resultado=ajaxN(url,datos);
	$("#preLlenarCuentaName").val(resultado);
	$("#preLlenarCuenta").val($("#id_cuenta_mayor").find(":selected").val());
}

function borraValidacion(input,div){
	
	if($("#" + input).val() != ""){
		$("#" + div).text("");
	}
	
}

function limpiaForm(miForm) {
	// recorremos todos los campos que tiene el formulario
	$(':input', miForm).each(function() {
		var type = this.type;
		var tag = this.tagName.toLowerCase();
		//limpiamos los valores de los campos…
		if (type == 'text' || type == 'password' || tag == 'textarea' || type == 'hidden')
		this.value = "";
		// excepto de los checkboxes y radios, le quitamos el checked
		// pero su valor no debe ser cambiado
		else if (type == 'checkbox' || type == 'radio')
		this.checked = false;
		// los selects le ponesmos el indice a -
		else if (tag == 'select')
		this.selectedIndex = 0;
	});
}

function eliminarCuentaContable(id_cuenta_contable,cuenta_contable,nivel){

	$.ajax({
			type: "POST",
			url: "../ajax/especiales/eliminarCuentaContable.php",
			data: "id_cuenta_contable=" + id_cuenta_contable + "&nivel=" + nivel,
			success: function (respuesta){
				
				var accion = respuesta;
				// Si la respuesta contiene 0, se le pregnta de nuevo al usuario si está seguro de eliminar la cuenta contable.
				// Si la respuesta contiene 1, se le notifica al usuario que no puede eliminarse debido que la cuenta tiene subcuentas asociadas.
				// Si la respuesta contiene 2, se le notifica al usuario que no puede eliminarse debido a que la cuenta esta relacionada con otros documentos
				
				if(accion == 0){
				
					var eliminar = confirm(Utf8.decode('¿Está seguro de eliminar la Cuenta Contable ') + cuenta_contable + '?');
					
					if(eliminar == true){
					
						$.ajax({
								type: "POST",
								url: "../ajax/especiales/eliminarCuentaContable.php",
								data: "id_cuenta_contable=" + id_cuenta_contable + "&nivel=" + nivel + "&confirmado=" + 's',
								success: function (respuesta){
									alert('Cuenta Contable Eliminada');
									window.location.reload(true);
								}
						});
					}
					
				}
				
				if(accion == 1){
					alert(Utf8.decode('La Cuenta NO puede ser eliminada debido a que tiene subcuentas asociadas\nNota: Si no se muestran subcuentas en el árbol, es posible que no estén activas'));
				}
				
				if(accion == 2){
					alert('La Cuenta NO puede ser eliminada debido a que esta relacionada a otros Documentos');
				}
				
			}
	});
	
}

function editarCuentaContable(id_cuenta_contable,nivel){

		$("#frmEditarCuentaContable").fancybox({
				href: 'mostrarFormularioEditarCuentaContable.php?id_cuenta_contable=' + id_cuenta_contable + '&tipo_cuenta_contable=' + nivel,
				maxWidth	: 800,
				maxHeight	: 600,
				fitToView	: false,
				width		: '70%',
				height		: '70%',
				autoSize	: false,
				closeClick	: false,
				openEffect	: 'none',
				closeEffect	: 'none',
				afterClose : function(){
					//alert(window.location.pathname);
					window.location.reload(true);
					//parent.cerrarFancyboxYRedirigeAUrl(window.location.pathname);
					//parent.cerrarFancyboxYRedirigeAUrl('http://localhost/nasser/code/especiales/cuentasContables.php');
				}
		});
	
}


			
function registraLista(){
		var nombre = $("#nombre-lista").val();
		var inicio = $("#inicio_vigencia").val();
		var fin = $("#fin_vigencia").val();
		var requiere = $("#requiere_pago").is(':checked') ? 1 : 0;
		
						var cortaInicio = inicio.split('/');
						var cortaFin = fin.split('/');
						var dateStart=new Date(cortaInicio[2],(cortaInicio[1]-1),cortaInicio[0]);
						var dateEnd=new Date(cortaFin[2],(cortaFin[1]-1),cortaFin[0]);
						var mayor = dateStart > dateEnd ? 1 : 0;

		
		if(nombre == '' || inicio == '' || fin == '' ){
				
						
						
				if(nombre == ''){
						$("#nombre-lista").css("border", "1px #D16656 solid");
						}
				else{
						$("#nombre-lista").css("border", "1px #DBE1EB solid");
						}
				if(inicio == ''){
						$("#inicio_vigencia").css("border", "1px #D16656 solid");
						}
				else{
						$("#inicio_vigencia").css("border", "1px #DBE1EB solid");
						}
				if(fin == ''){
						$("#fin_vigencia").css("border", "1px #D16656 solid");
						}
				else{
						$("#fin_vigencia").css("border", "1px #DBE1EB solid");
						}

						
				
				}
		else{
			var ruta = "registraListas.php";
			var envio = "nombre=" + nombre + "&inicio=" + inicio + "&fin=" + fin + "&requiere=" + requiere;
			var respuesta = ajaxCargando(ruta, envio, "respuesta");
			$("#respuesta").html(respuesta);
			}
		}
		


function verImportacionNumerosSerie(nombreGridDetalle,numeroFila){
//verImportacionNumerosSerie
var cantidad=celdaValorXY(nombreGridDetalle,7,numeroFila);
var idAlmacen = $('#id_almacen').val();
var irds=celdaValorXY(nombreGridDetalle,26,numeroFila);
$("#detalleMovimientosAlmacen_25_"+numeroFila+">img").css('display','none');
		$.fancybox({
				type: 'iframe',
				href: '../especiales/importarSeriesGrid.php?idLayout=5&cantidadAIngresar='+cantidad+'&idAlmacen='+idAlmacen+'&idOrdenCompra=0&numeroRenglon='+numeroFila+'&opc=100&validacionTipoEquipo=1&numeroFilaGrid=' + numeroFila + '&nombreGridDetalle=' + nombreGridDetalle+'&irds='+irds,
				maxWidth	: 900,
				maxHeight	: 800,
				fitToView	: false,
				width		: '90%',
				height		: '90%',
				autoSize	: false,
				closeClick	: false,
				openEffect	: 'none',
				closeEffect	: 'elastic',
				afterClose : function(){
					// En esta sección se coloca el código que se requiera ejecutar cuando se cierra el fancybox
				}
		});

}
function obtenerRenglon(columna){
/*
	var error = 0;
	var n3 = '';
*/
	/*
	$("#detalleMovimientosAlmacen tr td").click(function() {
		var celda = $(this);
		var celda2 = celda.html();
		alert(celda.html());
		var n2 = celda2.search('26');
		n3 = n2;
	});*/
	/*
	if(n3 != '-1'){
		$("#detalleMovimientosAlmacen table tr").click(function() {
			var nombre_producto = $(this).find("td").eq(4).html();
			var cantidad = $(this).find("td").eq(12).html();
			//Validaciones -->
			if( (nombre_producto == '&nbsp;') || (nombre_producto == '') ){
				alert('Debe elegir un producto');
				error = 1;
			}else if( (cantidad == '&nbsp;') || (cantidad == '') ){
					alert('Debe introducir la cantidad');
					error = 1;
			}
			//Validaciones <--
			if(error == 0){ verCapturaNumerosSerie('detalleMovimientosAlmacen',cantidad); }
		});
	}*/


		$("#detalleMovimientosAlmacen table tr").click(function() {
			var idProducto = 10;
			var nombre_producto = $(this).find("td").eq(4).html();
			var cantidad = $(this).find("td").eq(12).html();
			//Validaciones -->
			if( (nombre_producto == '&nbsp;') || (nombre_producto == '') ){
				alert('Debe elegir un producto');
				error = 1;
			}else if( (cantidad == '&nbsp;') || (cantidad == '') ){
					alert('Debe introducir la cantidad');
					error = 1;
			}
			//Validaciones <--
			if(error == 0){ verCapturaNumerosSerie('detalleMovimientosAlmacen',cantidad, idProducto); }
		});
}

//function verImportacionNumerosSerie(nombreGridDetalle,numeroFila){
//function verCapturaNumerosSerie(nombreGridDetalle,cantidad, idProducto){
	function verCapturaNumerosSerie(nombreGridDetalle, numeroFila){
		var cantidad=celdaValorXY(nombreGridDetalle,11,numeroFila);
		if(cantidad==''){
			alert('Ingrese la cantidad a capturar');
			return false;
		}
		var irds=celdaValorXY(nombreGridDetalle,26,numeroFila);
		var idProducto = 0;
		$("#detalleMovimientosAlmacen_24_"+numeroFila+">img").css('display','none');
		var idAlmacen = $('#id_almacen').val();
		$.fancybox({
				type: 'iframe',
				href: '../especiales/capturaNumerosSeries.php?idOrdenCompra=0&renglon='+numeroFila+'&idLayout=5&idAlmacen='+idAlmacen+'&cantidadAIngresar='+cantidad+'&idProducto='+idProducto+'&idDetalleOrdenCompra=0&Modulo=Almacen&irds='+irds,
				maxWidth	: 900,
				maxHeight	: 800,
				fitToView	: false,
				width		: '90%',
				height		: '90%',
				autoSize	: false,
				closeClick	: false,
				openEffect	: 'none',
				closeEffect	: 'elastic',
				afterClose : function(){				

				}
		});
}
			
function verCuentasContables(nombreGridDetalle,numeroFila){

		$.fancybox({
				type: 'iframe',
				href: '../especiales/mostrarArbolCuentasContables.php?numeroFilaGrid=' + numeroFila + '&nombreGridDetalle=' + nombreGridDetalle,
				maxWidth	: 900,
				maxHeight	: 800,
				fitToView	: false,
				width		: '90%',
				height		: '90%',
				autoSize	: false,
				closeClick	: false,
				openEffect	: 'none',
				closeEffect	: 'elastic',
				afterClose : function(){
					// En esta sección se coloca el código que se requiera ejecutar cuando se cierra el fancybox
				}
		});

}

function mostrarCuentasNivel2Seleccionar(id_cuenta_superior,cuenta_contable_seleccionada,mostrarBoton){
	
	var displayNivel2 = $( "#cuentasNivel2" + id_cuenta_superior ).css('display');
	$("input[name='id_cuenta_contable_radio'][value='" + cuenta_contable_seleccionada + "']").attr("checked", true);
	
	if(displayNivel2 == 'none'){
		$('#frmVerCuentaContableSeleccion').html('<img src="../../imagenes/cargando.gif" />');
	}
	
	$("#linkNivel1" + id_cuenta_superior ).css("color","#095525");
	
	$( "#cuentasNivel2" + id_cuenta_superior  ).toggle( "slow", function() {
			
			$.ajax({
					type: "POST",
					url: "../ajax/especiales/mostrarCuentasContablesNivel2Seleccion.php",
					data: "id_cuenta_superior=" + id_cuenta_superior,
					success: function (mostrarNivel2){
						
						if(displayNivel2 == 'none'){
								
								$.ajax({
									type: "POST",
									url: "../ajax/especiales/mostrarFormularioVerCuentaSeleccionar.php",
									data: "id_cuenta_contable=" + id_cuenta_superior + "&mostrarBoton=" + mostrarBoton,
									success: function (verFormulario){
										$( "#frmVerCuentaContableSeleccion").html(verFormulario);
									}
								});
								
						}
						else{
								$( "#frmVerCuentaContableSeleccion").html("");
								$("#linkNivel1" + id_cuenta_superior ).css("color","#4E5457");
								$("input[name='id_cuenta_contable_radio'][value='" + cuenta_contable_seleccionada + "']").attr("checked", false);
						}
						
						$( "#cuentasNivel2" + id_cuenta_superior).html(mostrarNivel2);
						
					}
			});
			
	 });
	 
}

function mostrarCuentasNivel3Seleccion(id_cuenta_contable,cuenta_contable_seleccionada,mostrarBoton){
	
	var displayNivel3 = 	$( "#cuentasNivel3" + id_cuenta_contable ).css('display');
	$("input[name='id_cuenta_contable_radio'][value='" + cuenta_contable_seleccionada + "']").attr("checked", true);
	
	if(displayNivel3 == 'none'){
		$('#frmVerCuentaContableSeleccion').html('<div style="background: url(../../js/fancybox/source/fancybox_overlay.png) repeat;width:50px;height:50px"><img src="../../js/fancybox/source/fancybox_loading@2x.gif" /></div>');
	}
	
	$("#linkNivel2" + id_cuenta_contable ).css("color","#095525");
	
	$( "#cuentasNivel3" + id_cuenta_contable  ).toggle( "slow", function() {
			
			$.ajax({
					type: "POST",
					url: "../ajax/especiales/mostrarCuentasContablesNivel3Seleccion.php",
					data: "id_cuenta_contable=" + id_cuenta_contable,
					success: function (mostrarNivel3){
					
						if(displayNivel3 == 'none'){
							// Mostrar formulario lleno con los datos de la cuenta contable seleccionada
							$.ajax({
									type: "POST",
									url: "../ajax/especiales/mostrarFormularioVerCuentaSeleccionar.php",
									data: "id_cuenta_contable=" + id_cuenta_contable + "&mostrarBoton=" + mostrarBoton,
									success: function (verFormulario){
										$( "#frmVerCuentaContableSeleccion").html(verFormulario);
									}
								});
						}
						else{
								$( "#frmVerCuentaContableSeleccion").html("");
								$("#linkNivel2" + id_cuenta_contable ).css("color","#4E5457");
								$("input[name='id_cuenta_contable_radio'][value='" + cuenta_contable_seleccionada + "']").attr("checked", false);
						}
						
						$( "#cuentasNivel3" + id_cuenta_contable).html(mostrarNivel3);
						
					}
			});
			
			
	 });
}

function displayDivDetalleFormularioSeleccion(id_cuenta_contable,cuenta_contable_seleccionada){
	
	$('#frmVerCuentaContableSeleccion').html('<div style="background: url(../../js/fancybox/source/fancybox_overlay.png) repeat;width:50px;height:50px"><img src="../../js/fancybox/source/fancybox_loading@2x.gif" /></div>');
	
	$("input[name='id_cuenta_contable_radio'][value='" + cuenta_contable_seleccionada + "']").attr("checked", true);
	
	$.ajax({
			type: "POST",
			url: "../ajax/especiales/mostrarFormularioVerCuentaSeleccionar.php",
			data: "id_cuenta_contable=" + id_cuenta_contable + "&mostrarBoton=s",
			success: function (verFormulario){
				$( "#frmVerCuentaContableSeleccion").html(verFormulario);
			}
	});
	
}

function seleccionarCuentaContable(id_cuenta_contable,cuenta_contable,nombre_cuenta_contable){

	var numeroFila = $("#numeroFilaGrid").val();
	var nombreGridDetalle = $("#nombreGridDetalle").val();
	
	parent.$("#" + nombreGridDetalle + "_2_" + numeroFila).attr("valor", id_cuenta_contable);
	parent.$("#" + nombreGridDetalle + "_3_" + numeroFila).attr("valor", cuenta_contable);
	parent.$("#" + nombreGridDetalle + "_3_" + numeroFila).text(cuenta_contable);
	
	//parent.$("#" + nombreGridDetalle + "_5_" + numeroFila).attr("valor",nombre_cuenta_contable);
	//parent.$("#" + nombreGridDetalle + "_5_" + numeroFila).text(nombre_cuenta_contable);

	parent.$("#" + nombreGridDetalle + "_4_" + numeroFila).attr("valor",nombre_cuenta_contable);
	parent.$("#" + nombreGridDetalle + "_4_" + numeroFila).text(nombre_cuenta_contable);

	
	parent.$.fancybox.close();
	
}

function cargaValoresCuentaContable(nombreGridDetalle,numeroFila){

	var id_cuenta_contable = $("#" + nombreGridDetalle + "_2_" + numeroFila ).attr("valor");
	
	$.ajax({
			type: "POST",
			url: "../ajax/especiales/obtenerDatosCuentaContable.php",
			data: "id_cuenta_contable=" + id_cuenta_contable,
			success: function (getCuentaContable){
			
				var aDetalleCuentaContable = $.parseJSON(getCuentaContable);
				var id_cuenta_contable = aDetalleCuentaContable[0][0];
				var nombre_cuenta_contable = aDetalleCuentaContable[0][1];
				
				$("#" + nombreGridDetalle + "_2_" + numeroFila).attr("valor", id_cuenta_contable);
				$("#" + nombreGridDetalle + "_3_" + numeroFila).attr("valor", id_cuenta_contable);
				$("#" + nombreGridDetalle + "_3_" + numeroFila).text(id_cuenta_contable);
				$("#" + nombreGridDetalle + "_4_" + numeroFila).attr("valor",nombre_cuenta_contable);
				$("#" + nombreGridDetalle + "_4_" + numeroFila).text(nombre_cuenta_contable);
			}
	});
	
}


function abreCobros(factura){
		if($("#op").val() == 2)
				var idFactura = factura;
		else
				var idFactura = celdaValorXY('listado',0,factura);
				
		$(location).attr('href','../especiales/detalleCobros.php?idFactura=' + idFactura);
		}


function cuentasPorPagarDetalleEgresos(){
		var proveedor = "";
		var cxp = "";
		$('table#detalleEgresos tr').each(function(index) { //Recorremos el grid
				 proveedor = $(this).children().filter("[id^=detalleEgresos_2_]").attr("valor");
				 cxp = $(this).children().filter("[id^=detalleEgresos_12_]").attr("valor");
				});
	$.fancybox({
				type: 'iframe',
				href: '../especiales/mostrarFormularioCuentasPorPagarBuscar.php?idProv=' + proveedor + '&cxp=' + cxp,
				/*maxWidth	: 900,
				maxHeight	: 800,*/
				fitToView	: false,
				width		: '90%',
				height		: '90%',
				autoSize	: false,
				closeClick	: false,
				openEffect	: 'none',
				closeEffect	: 'elastic',
				afterClose : function(){
					colocaDetalleEgresos()
				}
		});
		
}

function colocaDetalleEgresos(){
		var cxp = $("#campo_cxp").val(); //Obtenemos los valores de los id pedidos que arrojo el fancybox de pedidos al cerrar
		var cantidades = $("#campo_cantidades_cxp").val(); //Obtenemos los valores de las cantidades que arrojo el fancybox de pedidos al cerrar
		
		if(cxp != ""){
				var insertaC = cxp.split(","); //Separamos los id cuenta por pagar
				var partesCantidades = cantidades.split(","); //Separamos las cantidaes
				var contador = insertaC.length; //Contamos cuantos pedidos vienen
				var filas = $("#Body_detalleEgresos tr").length; //Contamos las filas del grid
				for(var j=0; j<contador; j++){
						var envia_datos = "idCXP=" + insertaC[j];
						var url = "detalleEgresosCXP.php";
						var datos = ajaxN(url, envia_datos); //Hacemos un ajax con los pedidos que nos devuelve el numero de filas a insertar y los id de los detalles de pagos
						var registros = datos.split("|");
								//Se llenan los datos
								nuevoGridFila('detalleEgresos');
								$("#detalleEgresos_2_" + filas).attr("valor", registros[0]); //ID Proveedor
								$("#detalleEgresos_3_" + filas).attr("valor", registros[0]); //ID Proveedor
								$("#detalleEgresos_3_" + filas).html(registros[1]); //Nombre del  Proveedor
								$("#detalleEgresos_4_" + filas).attr("valor", registros[2]); //ID Documento Recibido
								$("#detalleEgresos_5_" + filas).attr("valor", registros[2]); //ID Documento Recibido
								$("#detalleEgresos_5_" + filas).html(registros[3]); //Documento Recibido
								$("#detalleEgresos_6_" + filas).attr("valor", registros[4]); //Numero de documento
								$("#detalleEgresos_6_" + filas).html(registros[4]); //Numero de documento
								$("#detalleEgresos_7_" + filas).attr("valor", registros[5]); //Total de la cuenta por pagar
								$("#detalleEgresos_7_" + filas).html(registros[6]); //Total de la cuenta por pagar
								$("#detalleEgresos_8_" + filas).attr("valor", registros[7]); //Pagos Realizados que se registra
								$("#detalleEgresos_8_" + filas).html(registros[8]); //Pagos Realizados que se registra
								$("#detalleEgresos_9_" + filas).attr("valor", registros[9]); //Saldo 
								$("#detalleEgresos_9_" + filas).html(registros[10]); //Saldo
								$("#detalleEgresos_10_" + filas).attr("valor", partesCantidades[j]); //Monto que puede venir del fancybox
								$("#detalleEgresos_10_" + filas).html("$" + formatear_pesos(partesCantidades[j])); //Monto que puede venir del fancybox
								$("#detalleEgresos_12_" + filas).attr("valor", registros[11]);
								
								filas++; //Fila siguiente
						}
				}
				$("#campo_cxp").val("");
				$("#campo_cantidades_cxp").val(""); //Limpiamos los pedidos
				calculaTotalesN('detalleEgresos', 10, 'total_depositos_cxp'); 
				totalCamposEncabezados('total_depositos_cxp', 'total_cuentas_contables', 'total_egreso');
		}
/*
function pedidosDetalleDepositosBancarios(){

	$.fancybox({
				type: 'iframe',
				href: '../especiales/mostrarFormularioPedidosBuscar.php',
				maxWidth	: 900,
				maxHeight	: 800,
				fitToView	: false,
				width		: '90%',
				height		: '90%',
				autoSize	: false,
				closeClick	: false,
				openEffect	: 'none',
				closeEffect	: 'elastic',
				afterClose : function(){
						colocaDetalleBancario();
					
				}
		});
		
}
*/
/*
/* se debe borrar  --->* /
function facturasDetalleDepositosBancarios(){

	$.fancybox({
				type: 'iframe',
				href: '../especiales/mostrarFormularioFacturasBuscar.php',
				maxWidth	: 900,
				maxHeight	: 800,
				fitToView	: false,
				width		: '90%',
				height		: '90%',
				autoSize	: false,
				closeClick	: false,
				openEffect	: 'none',
				closeEffect	: 'elastic',
				afterClose : function(){
						colocaDetalleBancarioFactura();
					
				}
		});
		
}
*/
/* se debe borrar  <---*/

function agregaFacturaDB(){
	var idFactura = new Array();	
	$('table#Body_detalleDepositosBancarios tr').each(function(index) { //Recorremos el grid
		idFactura.push($(this).children().filter("[id^=detalleDepositosBancarios_13_]").attr("valor"));
	});
	$.fancybox({
		type: 'iframe',
		href: '../especiales/buscaFacturasDB.php?facturasR=' + idFactura,
		fitToView	: false,
		width		: '95%',
		height		: '95%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'elastic',
		afterClose : function(){
				colocaDetalleBancario();
		}
	});
	
}

function colocaDetalleBancario(){
	var facturas = $("#campo_facturas").val(); //Obtenemos los valores de los id pedidos que arrojo el fancybox de pedidos al cerrar
	if(facturas != ""){
		var cantidades = $("#campo_cantidades").val();
		var formas = $("#campo_formas").val();
		var insertaF = facturas.split(","); //Separamos los valores
		var cantidadesP = cantidades.split(",");
		var contador = insertaF.length; //Contamos cuantos pedidos vienen
		var filas = $("#Body_detalleDepositosBancarios tr").length; //Contamos las filas del grid
		var envia_datos = "idFacturas=" + insertaF + "&formas=" + formas;
		var url = "detalleBancariosFacturas.php";
		var datos = ajaxN(url, envia_datos);
		var registros = JSON.parse(datos);
		var contador = registros.length;
		var filas = $("#Body_detalleDepositosBancarios tr").length; //Contamos las filas del grid
		for(var i = 0; i < contador; i++){
			nuevoGridFila('detalleDepositosBancarios');
			//CLIENTE
			$("#detalleDepositosBancarios_2_" + filas).attr("valor", registros[i].id_cliente); 
			$("#detalleDepositosBancarios_3_" + filas).attr("valor", registros[i].id_cliente); 
			$("#detalleDepositosBancarios_3_" + filas).html(registros[i].cliente); 
			//FACTURA
			$("#detalleDepositosBancarios_4_" + filas).attr("valor", registros[i].factura); 
			$("#detalleDepositosBancarios_4_" + filas).html(registros[i].factura); 
			//TOTAL
			$("#detalleDepositosBancarios_5_" + filas).attr("valor", registros[i].total); 
			$("#detalleDepositosBancarios_5_" + filas).html("$" + formatear_pesos("$" + registros[i].total)); 
			//COBROS
			$("#detalleDepositosBancarios_6_" + filas).attr("valor", registros[i].suma_cobros); 
			//SALDO
			$("#detalleDepositosBancarios_7_" + filas).attr("valor", registros[i].saldo_factura); 
			$("#detalleDepositosBancarios_7_" + filas).html("$" + formatear_pesos(registros[i].saldo_factura)); 
			//CANTIDADES
			$("#detalleDepositosBancarios_11_" + filas).attr("valor", cantidadesP[i]); 
			$("#detalleDepositosBancarios_11_" + filas).html("$" + formatear_pesos(cantidadesP[i])); 
			//FORMAS DE COBRO
			$("#detalleDepositosBancarios_8_" + filas).attr("valor", registros[i].id_forma_cobro); 
			$("#detalleDepositosBancarios_9_" + filas).attr("valor", registros[i].id_forma_cobro); 
			$("#detalleDepositosBancarios_9_" + filas).html(registros[i].forma_cobro); 
			//CONTROL FACTURA
			$("#detalleDepositosBancarios_13_" + filas).attr("valor", registros[i].control_factura); 
			filas++;
		}
		$("#campo_facturas").val("");
		$("#campo_cantidades").val(""); 
		$("#campo_formas").val("");
		calculaTotalesN('detalleDepositosBancarios', 11, 'total_depositos_bancarios'); 
		totalCamposEncabezados('total_depositos_bancarios', 'total_cuentas_contables', 'total_deposito');
	}
}
/*
function colocaDetalleBancario(){
		var pedidos = $("#campo_pedidos").val(); //Obtenemos los valores de los id pedidos que arrojo el fancybox de pedidos al cerrar
		
		if(pedidos != ""){
				var insertaP = pedidos.split(","); //Separamos los valores
				var contador = insertaP.length; //Contamos cuantos pedidos vienen
				var filas = $("#Body_detalleDepositosBancarios tr").length; //Contamos las filas del grid
				for(var j=0; j<contador; j++){
						var envia_datos = "idPagos=" + insertaP[j] + "&caso=2";
						var url = "detalleBancariosPedidos.php";
						var datos = ajaxN(url, envia_datos); //Hacemos un ajax con los pedidos que nos devuelve el numero de filas a insertar y los id de los detalles de pagos
						var registros = datos.split("|");
						/*var id_pagos = variables[1].split(","); //Ids de detalles de pagos
						for(var i=0; i<variables[0]; i++){ //variables[0] -- Filas a insertar
								var envia_datos2 = "idPagos=" + id_pagos[i] + "&caso=2";
								var url2 = "detalleBancariosPedidos.php";
								var datos2 = ajaxN(url2, envia_datos2);  //Hacemos un nuevo ajax para obtener los datos de los registro de detalle de pagos* /
								
								if(registros != "NO"){
										//var registros = datos2.split("|"); //Separamos los datos
										nuevoGridFila('detalleDepositosBancarios'); //Se agrega una nueva fila por cada dato que se haya seleccionado
										//Se llenan los datos
										$("#detalleDepositosBancarios_2_" + filas).attr("valor", registros[0]); //ID Cliente
										$("#detalleDepositosBancarios_3_" + filas).attr("valor", registros[0]); //ID Cliente
										$("#detalleDepositosBancarios_3_" + filas).html(registros[1]); //Nombre del  Cliente
										$("#detalleDepositosBancarios_4_" + filas).attr("valor", registros[2]); //ID Pedido
										$("#detalleDepositosBancarios_5_" + filas).attr("valor", registros[2]); //ID Pedido
										$("#detalleDepositosBancarios_5_" + filas).html(registros[3]); //Pedido
										$("#detalleDepositosBancarios_6_" + filas).attr("valor", registros[4]); //Total que se registra
										$("#detalleDepositosBancarios_6_" + filas).html(registros[5]); //Total que se muestra
										$("#detalleDepositosBancarios_7_" + filas).attr("valor", registros[6]); //Pagos Realizados que se registra
										$("#detalleDepositosBancarios_8_" + filas).attr("valor", registros[7]); //Saldo que se registra
										$("#detalleDepositosBancarios_8_" + filas).html(registros[8]); //Saldo que se muestra
										$("#detalleDepositosBancarios_9_" + filas).attr("valor", registros[9]); //Forma de pago que se registra
										$("#detalleDepositosBancarios_10_" + filas).attr("valor", registros[9]); //Forma de pago que se registra
										$("#detalleDepositosBancarios_10_" + filas).html(registros[10]); //Forma de pago que se muestra
										$("#detalleDepositosBancarios_11_" + filas).attr("valor", registros[11]); //Tipo de documento que se registra
										$("#detalleDepositosBancarios_11_" + filas).html(registros[11]); //Tipo de documento que se muestra
										$("#detalleDepositosBancarios_12_" + filas).attr("valor", registros[12]); //Pago que se registra
										$("#detalleDepositosBancarios_12_" + filas).html(registros[13]); //Pago que se muestra
										$("#detalleDepositosBancarios_14_" + filas).attr("valor", registros[14]); //ID DETALLE PEDIDO PAGO
										filas++; //Fila siguiente
										}
								
								//}
						}
				$("#campo_pedidos").val(""); //Limpiamos los pedidos
				calculaTotalesN('detalleDepositosBancarios', 12, 'total_depositos_bancarios'); 
				totalCamposEncabezados('total_depositos_bancarios', 'total_cuentas_contables', 'total_deposito');
				}
		}


function colocaDetalleBancarioFactura(){
		var pedidos = $("#campo_pedidos").val(); //Obtenemos los valores de los id pedidos que arrojo el fancybox de pedidos al cerrar
		
		if(pedidos != ""){
				var insertaP = pedidos.split(","); //Separamos los valores
				var contador = insertaP.length; //Contamos cuantos pedidos vienen
				var filas = $("#Body_detalleDepositosBancarios tr").length; //Contamos las filas del grid
				for(var j=0; j<contador; j++){
						var envia_datos = "idPagos=" + insertaP[j] + "&caso=2";
						var url = "detalleBancariosFacturas.php";
						var datos = ajaxN(url, envia_datos); //Hacemos un ajax con los pedidos que nos devuelve el numero de filas a insertar y los id de los detalles de pagos
						var registros = datos.split("|");
						/*var id_pagos = variables[1].split(","); //Ids de detalles de pagos
						for(var i=0; i<variables[0]; i++){ //variables[0] -- Filas a insertar
								var envia_datos2 = "idPagos=" + id_pagos[i] + "&caso=2";
								var url2 = "detalleBancariosPedidos.php";
								var datos2 = ajaxN(url2, envia_datos2);  //Hacemos un nuevo ajax para obtener los datos de los registro de detalle de pagos* /
								
								if(registros != "NO"){
										//var registros = datos2.split("|"); //Separamos los datos
										nuevoGridFila('detalleDepositosBancarios'); //Se agrega una nueva fila por cada dato que se haya seleccionado
										//Se llenan los datos
										$("#detalleDepositosBancarios_2_" + filas).attr("valor", registros[0]); //ID Cliente
										$("#detalleDepositosBancarios_3_" + filas).attr("valor", registros[0]); //ID Cliente
										$("#detalleDepositosBancarios_3_" + filas).html(registros[1]); //Nombre del  Cliente
										$("#detalleDepositosBancarios_4_" + filas).attr("valor", registros[2]); //ID Pedido
										$("#detalleDepositosBancarios_5_" + filas).attr("valor", registros[2]); //ID Pedido
										$("#detalleDepositosBancarios_5_" + filas).html(registros[3]); //Pedido
										$("#detalleDepositosBancarios_6_" + filas).attr("valor", registros[4]); //Total que se registra
										$("#detalleDepositosBancarios_6_" + filas).html(registros[5]); //Total que se muestra
										$("#detalleDepositosBancarios_7_" + filas).attr("valor", registros[6]); //Pagos Realizados que se registra
										$("#detalleDepositosBancarios_8_" + filas).attr("valor", registros[7]); //Saldo que se registra
										$("#detalleDepositosBancarios_8_" + filas).html(registros[8]); //Saldo que se muestra
										$("#detalleDepositosBancarios_9_" + filas).attr("valor", registros[9]); //Forma de pago que se registra
										$("#detalleDepositosBancarios_10_" + filas).attr("valor", registros[9]); //Forma de pago que se registra
										$("#detalleDepositosBancarios_10_" + filas).html(registros[10]); //Forma de pago que se muestra
										$("#detalleDepositosBancarios_11_" + filas).attr("valor", registros[11]); //Tipo de documento que se registra
										$("#detalleDepositosBancarios_11_" + filas).html(registros[11]); //Tipo de documento que se muestra
										$("#detalleDepositosBancarios_12_" + filas).attr("valor", registros[12]); //Pago que se registra
										$("#detalleDepositosBancarios_12_" + filas).html(registros[13]); //Pago que se muestra
										$("#detalleDepositosBancarios_14_" + filas).attr("valor", registros[14]); //ID DETALLE PEDIDO PAGO
										filas++; //Fila siguiente
										}
								
								//}
						}
				$("#campo_pedidos").val(""); //Limpiamos los pedidos
				calculaTotalesN('detalleDepositosBancarios', 12, 'total_depositos_bancarios'); 
				totalCamposEncabezados('total_depositos_bancarios', 'total_cuentas_contables', 'total_deposito');
				}
		}
		
*/		
function agregarCuentaPorPagarCosteoProductos(){
	if($("#v").val() != 1){
			var costeo = $("#id_costeo_productos").val();
			$.fancybox({
						type: 'iframe',
						//href: '../general/encabezados.php?t=bmFfY3VlbnRhc19wb3JfcGFnYXI=&k=&op=1&tcr=&stm=&idCosteo=' + costeo,
						  href: '../general/encabezados.php?t=YWRfY3VlbnRhc19wb3JfcGFnYXJfb3BlcmFkb3Jh&k=&op=1&tcr=&stm=&idCosteo=' + costeo,
						
						
						maxWidth	: 1040,
						maxHeight	: 800,
						fitToView	: false,
						width		: '90%',
						height		: '90%',
						autoSize	: false,
						closeClick	: false,
						openEffect	: 'none',
						closeEffect	: 'elastic',
						afterClose  : function(){
							// En esta sección se coloca el código que se requiera ejecutar cuando se cierra el fancybox
							RecargaGrid('detalleCuentasPorPagarCosteo','');
							var totalcuentaPagar = calculaTotalesN('detalleCuentasPorPagarCosteo', 10, 'total_otros_servicios');
							obtenLoteMontoCosteo();
						}
				});
				}else{alert('no entra');}
		
}

function obtenLoteMontoCosteo(){
		var otrosServicios = $("#total_otros_servicios").val();
		otrosServicios = otrosServicios.replace(",", "");
		otrosServicios = otrosServicios == "" ? otrosServicios = 0 : otrosServicios = otrosServicios;
		$('table#Body_detalleCosteoProductos tr').each(function(index) { //Recorremos el grid
				var porcentaje = $(this).children().filter("[id^=detalleCosteoProductos_8_]").attr("valor"); //Obtenemos el porcentaje de la fila correspondiente
				var montoProp = calculaPorcentajesMonto(otrosServicios, porcentaje, 1); //Calculamos el porcentaje sobre el monto de otros servicios del encabezado
				/***Operacion para obtener la fila actual***/
				var fila = $(this).attr("id");
				var filaReal = fila.split("Fila");
				//Pintamos los calculos en la columna correspondiente
				$("#detalleCosteoProductos_9_" + filaReal[1]).attr("valor", montoProp);
				$("#detalleCosteoProductos_9_" + filaReal[1]).html("$" + formatear_pesos(montoProp));
				
				//Obtenemos el costo del lote de cada producto
				/*var lote = parseFloat($("#detalleCosteoProductos_6_" + filaReal[1]).attr("valor")) + parseFloat($("#detalleCosteoProductos_9_" + filaReal[1]).attr("valor"));*/
				
				var lote = (parseFloat($("#detalleCosteoProductos_7_" + filaReal[1]).attr("valor")) + parseFloat($("#detalleCosteoProductos_9_" + filaReal[1]).attr("valor")))/parseFloat($("#detalleCosteoProductos_4_" + filaReal[1]).attr("valor"));
			
				
				
				$("#detalleCosteoProductos_10_" + filaReal[1]).attr("valor", lote);
				$("#detalleCosteoProductos_10_" + filaReal[1]).html("$" + formatear_pesos(lote));
				});
		
		}

/******************************************************************************************************************************/
function noletras(e){
		tecla = (document.all) ? e.keyCode : e.which; // 2
			if (tecla==8) return true; // backspace
			if (tecla==109) return true; // menos
			if (tecla==110) return true; // punto
			
			if (tecla==37) return true; // Flecha Izquierda
			if (tecla==39) return true; // Flecha Derecha
			/*if (e.ctrlKey && tecla==86) { return true}; //Ctrl v
			if (e.ctrlKey && tecla==67) { return true}; //Ctrl c
			if (e.ctrlKey && tecla==88) { return true}; //Ctrl x*/
			if (tecla>=96 && tecla<=105) { return true;} //numpad
		 
			patron = /[0-9]/; // patron
		 
			te = String.fromCharCode(tecla);
			return patron.test(te); // prueba
			}
function noletrasCantidades(e){
		tecla = (document.all) ? e.keyCode : e.which; // 2
			if (tecla==8) return true; // backspace
			if (tecla==0) return true; //Tabulador
			//if (tecla==110) return true; // punto
			//if (tecla==189) return true; // guion
			if (tecla==37) return true; // Flecha Izquierda
			if (tecla==39) return true; // Flecha Derecha
			/*if (e.ctrlKey && tecla==86) { return true}; //Ctrl v
			if (e.ctrlKey && tecla==67) { return true}; //Ctrl c
			if (e.ctrlKey && tecla==88) { return true}; //Ctrl x*/
			if (tecla>=96 && tecla<=105) { return true;} //numpad
		 
			patron = /[0-9]/; // patron
		 
			te = String.fromCharCode(tecla);
			return patron.test(te); // prueba
			}
function convierteFechaN(fecha){
		var fecha = fecha.split("-");
		var fechaReal = fecha[2] + "/" + fecha[1] + "/" + fecha[0];
		return fechaReal;
		}
function seleccionarCheck(check, tabla){
		if($(check).is(':checked'))
				$("table." + tabla + " input[type=checkbox]").prop('checked', true);
		else
            $("table." + tabla + " input[type=checkbox]").prop('checked', false);
			
		}		
//		
function validaCodigoArticulo(){

     
	  var id_articulo=document.getElementById('id_producto').value;
      var codigo_articulo =document.getElementById('sku').value;
	  
	  //validamos si el Prefijo de la Ruta ya existe en otra sucursal
	  var aux = ajaxR("../ajax/validaCodigo.php?opcion=1&id_articulo="+id_articulo+"&codigo_articulo="+codigo_articulo);
	 // alert(aux);
	  var ax = aux.split("|");
	  if(ax[0] == 'exito'){
	   
		if(ax[1]>1)
		{
			alert("El SKU ya esta relacionado al producto "+ax[4] + ". Modifiquelo y vuelva a intentarlo");
			return false;
		}
		else if(ax[1]==1)
		{
			if(ax[2]!=id_articulo)
			{
				alert("El SKU ya esta relacionado al producto "+ax[4] + ". Modifiquelo y vuelva a intentarlo");
				return false;
			}
		}
		
	  } 
	
	return true;

}

function validaDatosProveedores()
{
     
	var id_estado=document.getElementById('id_estado').value;
    var id_ciudad =document.getElementById('id_ciudad').value;
	
	if( id_estado == null || id_estado == 0 ) {
				  alert("Seleccione un estado v\u00E1lido");
				  document.getElementById('id_estado').focus();
				  return false;
	}
	if( id_ciudad == null || id_ciudad == 0 ) {
				  alert("Seleccione una ciudad v\u00E1lida");
				  document.getElementById('id_estado').focus()
				  return false;
	}
	
	return true;
}

/********************************ORDENES DE COMPRA*******************************************/

function proveedorContacto(){
		if($("#v").val() == 1)
				var proveedor = $("#id_proveedor").val();
		else		
				var proveedor = $("#id_proveedor").find("option:selected").val();
		
		var selectHijo = "id_proveedor_contacto";
		var urlAjax = "llenaDatosCombos.php";
		var envio_datos = 'id=' + proveedor + '&caso=4';  // Se arma la variable de datos que procesara el php
		ajaxCombos(urlAjax, envio_datos, selectHijo);
		
		}
function obtenProductoOrdenes(pos){
		
		var producto = $("#detalleProductosOrdenesCompra_2_" + pos).attr("valor");
		var proveedor = $("#id_proveedor").val();
		var envia_datos = "producto=" + producto + "&proveedor=" + proveedor;
		var url = "obtenProductoOrdenes.php";
		var respuesta = ajaxN(url, envia_datos);
		var datos = JSON.parse(respuesta);
		
		if(datos.estatus == 1){
				$("#detalleProductosOrdenesCompra_5_" + pos).attr("valor", datos.unitario);
				$("#detalleProductosOrdenesCompra_5_" + pos).html("$" + formatear_pesos(datos.unitario));
				
				$("#detalleProductosOrdenesCompra_6_" + pos).attr("valor", datos.descuento);
				$("#detalleProductosOrdenesCompra_6_" + pos).html(datos.descuento + "%");
				
				$("#detalleProductosOrdenesCompra_7_" + pos).attr("valor", datos.finalp);
				$("#detalleProductosOrdenesCompra_7_" + pos).html("$" + formatear_pesos(datos.finalp));
				}
		else{
				$("#detalleProductosOrdenesCompra_5_" + pos).attr("valor", "");
				$("#detalleProductosOrdenesCompra_5_" + pos).html("");
				
				$("#detalleProductosOrdenesCompra_6_" + pos).attr("valor", "");
				$("#detalleProductosOrdenesCompra_6_" + pos).html("");
				
				$("#detalleProductosOrdenesCompra_7_" + pos).attr("valor", "");
				$("#detalleProductosOrdenesCompra_7_" + pos).html("");
				}
		var fecha = $("#fecha_entrega").val();
		$("#detalleProductosOrdenesCompra_9_" + pos).attr("valor", fecha);
		$("#detalleProductosOrdenesCompra_9_" + pos).html(fecha);
		}



/********************************************************************************************/




//MOSTRAMOS EL LISTADO DE VENDEDORES SI ESQUE ES VISIBLE
function muestraVendedores()
{
	//DADO EL TIPO DE PERFIL BUSCAMOS SI LA PERSONA PUEDE VER LOS ALMACENES
	var varPerfil=document.getElementById('id_grupo').value;
	
	//POR AJAX VEMOS SI EL CLIENTES TIENE ALMACENES Y MOSTRAMOS OCULTAMOS EL GRID
	aux=ajaxR('../ajax/especiales/obtenValorPerfil.php?per='+varPerfil);
	var arrResp=aux.split("|");
	if(arrResp[1]==1)
	{
		//mostramos el almacen
		$("#fila_catalogo_13").show();
	}
	else
	{
		$("#fila_catalogo_13").hide();
	}
	
}



function ajaxCombos2(url, datos, hijo){
	$.ajax({
		async:true,
		type: "POST",
		dataType: "html",
		contentType: "application/x-www-form-urlencoded",
		data: datos,
		url:'../ajax/' + url,
		success: function(data) {			
			$("#" + hijo + " option").remove();
			$("#" + hijo)
                    .slideUp('slow', function() {
                        $(this).append(data).slideDown('slow');
            		});			
			}
		});
}

function obtenPorcentajeCosteo()
{	
	var total = $("#total_productos").val();
	total = total == "" ? total = 0 : total = total; 
	total = total.replace(",", "");
	
	var total_otros = $("#total_otros_servicios").val();
	total_otros = total_otros == "" ? total_otros = 0 : total_otros = total_otros; 
	total_otros = total_otros.replace(",", "");
	
	$('table#Body_detalleCosteoProductos tr').each(function(index) 
	{
		var costos  = $(this).children().filter("[id^=detalleCosteoProductos_7_]").attr("valor");
		costos = costos == "" ? costos = 0 : costos = costos; 
		var porcentaje_s =  costos * 100;
		var porcentaje = porcentaje_s / total;
		porcentajeR = porcentaje.toFixed(2);
		
		$(this).children().filter("[id^=detalleCosteoProductos_8_]").attr("valor", porcentajeR);
		$(this).children().filter("[id^=detalleCosteoProductos_8_]").html(porcentajeR + "%");
		
		var sub = porcentaje * total_otros;
		var monto = sub / 100;
		
		$(this).children().filter("[id^=detalleCosteoProductos_9_]").attr("valor", monto);
		$(this).children().filter("[id^=detalleCosteoProductos_9_]").html("$" + formatear_pesos(monto));
		
		var servicios  = $(this).children().filter("[id^=detalleCosteoProductos_9_]").attr("valor");
		servicios = servicios == "" ? servicios = 0 : servicios = servicios; 
		var cantidad  = $(this).children().filter("[id^=detalleCosteoProductos_4_]").attr("valor");
		cantidad = cantidad == "" ? cantidad = 0 : cantidad = cantidad; 
		
		var lotes = parseFloat(servicios) + parseFloat(costos);
		lotes = lotes/cantidad;
		
		$(this).children().filter("[id^=detalleCosteoProductos_10_]").attr("valor", lotes);
		$(this).children().filter("[id^=detalleCosteoProductos_10_]").html("$" + formatear_pesos(lotes));
			
	});
}
		
		
		
/*********************	LFLORES	************************/		
		
	function calculaSubtotalPagos(grid, colImporte, idResultado){

	// REALIZA SUMA DE LOS IMPORTES EN UN GRID Y DEVUELVE EL RESULTADO 
	// AL FORMULARIO POR MEDIO DE UN "IDCAMPO" 

	// GRID: nombre del grid entre apostrofes
	
	// colImporte: hace referencia a la posicion en x (COLUMNA) del
	// GRID, en este caso son 2 pocisiones antes de  su ORDEN 
	
	// idResultado: ID del campo donde se vaciara la informacion 
	// En este caso es el campo subtotal
	
	var subtotal= 0;
	var total	= 0;
	
	$('table#Body_'+grid+' tr').each(function(index) {
		
		subtotal = $(this).children()
						  .filter("[id^="+grid+"_"+colImporte+"_]")
						  .attr("valor");
						  
		subtotal = subtotal == "" ? subtotal = 0 : subtotal ;
		total	+= parseFloat(subtotal);
		
	});		

	total=formatear_pesos(parseFloat(total));
	$("#" + idResultado).val(total);	
}

function calculaIVAyTotal(idSubtotal, idResultadoIVA, idTotal){

	// CALCULA IVA Y TOTAL DEL MONTO SUBTOTAL
	
	// idSubtotal:		ID del text donde se encuentra el valor SUBTOTAL
	
	// idResultadoIVA:	ID del text donde se pintará el IVA
	
	// idTotal:			ID del text donde se pintará el TOTAL
	
	var montoIVA=0;
	var total = 0;
		
	// Almacenamos subtotal en variable
	subtotal=$("#"+idSubtotal).val();
	
	// Quitamos "," que generó la funcion "formatear_pesos"
	subtotal=subtotal.replace(",","");

	// Parseamos como flotante para poder realizar las 
	// operaciones correspondientes
	subtotal=parseFloat(subtotal);
	
	montoIVA+=parseFloat(subtotal * 0.16);
	total	+=parseFloat(subtotal + montoIVA);

	$("#"+idResultadoIVA).val(formatear_pesos(montoIVA));
	$("#"+idTotal).val(formatear_pesos(total));
}	

function colocaIVAyMontoIVA(tabla, pos, colImporte, colIVA , colMontoIVA)
{	
//	alert('grid: '+tabla+' pos: '+pos+' colImporte: '+colImporte+' colIVA: '+colIVA+' colMontoIva: '+colMontoIVA);
	
	var importe=0;
	var IVA=0;
	var montoIVA = 0;	
	
	importe += parseFloat($("#" + tabla + "_" + colImporte + "_" + pos).attr("valor"));	
	IVA 	+=parseFloat(importe * 0.16);
	montoIVA+=parseFloat(importe + IVA);		
	
//	alert('Importe: '+importe+' IVA: '+IVA+' Monto IVA: '+ montoIVA );	
	
	$("#" + tabla + "_" + colIVA + "_" + pos).attr("valor", IVA);
	$("#" + tabla + "_" + colIVA + "_" + pos).html("$" + formatear_pesos(IVA));
	$("#" + tabla + "_" + colMontoIVA+ "_" + pos).attr("valor", montoIVA);
	$("#" + tabla + "_" + colMontoIVA + "_" + pos).html("$" + formatear_pesos(montoIVA));
		
}
function resetOrdenRecoleccion()
{
	vaciaGrid('detalleOrdenesRecoleccionClientesProductos');
	nuevoGridFila('detalleOrdenesRecoleccionClientesProductos');	
}

function alertPedido()
{
	alert('CUIDADO, al cambiar de PEDIDO se borran los productos capturados!!!');	
}

function obtenPedidosCliente()
{
	var selectHijo = "id_pedido";
	var opcion = $("#selcampo_2").find("option:selected").val();
	var urlAjax = "llenaDato.php";
	var envio_datos ='dato=pedidoCliente&'+ 'id=' + opcion; 
	ajaxCombos(urlAjax, envio_datos, selectHijo);
}

function obtenDireccionesCliente()
{
	var selectHijo = "id_cliente_direccion_entrega";
	var opcion = $("#selcampo_2").find("option:selected").val();
	var urlAjax = "llenaDato.php";
	var envio_datos ='dato=direccionCliente&'+ 'id=' + opcion;  
	ajaxCombos(urlAjax, envio_datos, selectHijo);
}

function llenaPedidosCliente(seleccionado)
{
//	alert(seleccionado);
	var selectHijo = "id_pedido";
	var opcion = $("#hcampo_2").val();
	var urlAjax = "llenaDato.php";
	var envio_datos ='dato=pedidoCliente&'+ 'id=' + opcion;  // Se arma la variable de datos que procesara el php
	if(!seleccionado){seleccionado=0;}
	ajaxCombosS(urlAjax, envio_datos, selectHijo, seleccionado);
}
function llenaDireccionesCliente(seleccionado)
{
//	alert(seleccionado);
	var selectHijo = "id_cliente_direccion_entrega";
	var opcion = $("#hcampo_2").val();
	var urlAjax = "llenaDato.php";
	var envio_datos ='dato=direccionCliente&'+ 'id=' + opcion;  
	if(!seleccionado){seleccionado=0;}
	ajaxCombosS(urlAjax, envio_datos, selectHijo, seleccionado);	
}

function obtenRuta()
{
	var selectHijo = "id_direccion_cliente";
	var opcion = $("#selcampo_2").find("option:selected").val();
	var urlAjax = "llenaDato.php";
	var envio_datos ='dato=rutaCliente&'+ 'id=' + opcion;  // Se arma la variable de datos que procesara el php
	/*
		$.ajax({		  				
		  url : '../ajax/especiales/registroCobrosPedido/insertCobrosPedido.php' ,		  
		  type: 'POST',
		  data : $params
			}).done( function( data )
					 {
						 alert( data );
						 datosGrid=$("#registroCobrosPedido").attr("Datos");
						 RecargaGrid('registroCobrosPedido',
						 			 datosGrid);
						 $("#pagos").slideUp('slow');
						 document.forms.frmPagos.reset();
//						console.log(data);
				 });*/
}
function ajaxCombosS(url, datos, hijo, seleccionado)
{
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
		$("#" + hijo + " option").remove();
		$("#" + hijo).append(data);
		if(seleccionado!=0)
		{	
			$("#" + hijo + " option[value="+seleccionado+"]").attr("selected",true);
		}
	},
	timeout:50000
	});
}
$(document).ready(function() {
	
	// ORDENES DE RECOLECCION
	
	if($("#t").val() == "bmFfb3JkZW5lc19yZWNvbGVjY2lvbl9jbGllbnRlcw==" && $("#op").val() == 1)	
	{
		// INICIALIZA INPUTS
		$("#id_pedido option").remove();
		$("#id_pedido").append('<option value=0>Capture un cliente...</option>');
		
		$("#id_cliente_direccion_entrega option").remove();
		$("#id_cliente_direccion_entrega").append('<option value=0>Capture un cliente...</option>');
		
	}
	else if($("#t").val() == "bmFfb3JkZW5lc19yZWNvbGVjY2lvbl9jbGllbnRlcw==" && $("#op").val() == 2)
	{
		// DEPURA COMBOS
		var pedido = $("#id_pedido").val();
		var direccion= $("#id_cliente_direccion_entrega").val();
			
		llenaPedidosCliente(pedido);
		llenaDireccionesCliente(direccion);
//		alert('Pedido: '+pedido+' Direccion: '+direccion)	
	}	
});
/*********************************** FIN BLOQUE LFLORES ***********************************/		

function activaCamposRetenciones(check){
		var id = $(check).attr("id");
				if(id == "aplica_retencion_iva"){
						var nivel = "documento_detalle_iva";
						var porcentaje = "porcentaje_retencion_iva";
						}
				else if("aplica_retencion_isr"){
						var nivel = "documento_detalle_isr";
						var porcentaje = "porcentaje_retencion_isr";
						}
		if($(check).is(':checked')){
				$('#' + nivel).prop('disabled', false);
				$('#' + porcentaje).prop('disabled', false);
				}
		else{
				$("#" + nivel + " option[value=0]").prop("selected","selected");
				$('#' + nivel).prop('disabled', 'disabled');
				$('#' + porcentaje).prop('disabled', 'disabled');
				$('#' + porcentaje).val("");
				}
		}
function totalCamposEncabezados(campo1, campo2, destino){
		
		var suma1 = $("#" + campo1).val();
		var suma2 = $("#" + campo2).val();
		suma1 = suma1 == "" ? suma1 = 0 : suma1 = suma1; 
		suma2 = suma2 == "" ? suma2 = 0 : suma2 = suma2; 
		suma1 = suma1.toString().replace(",", "");
		suma2 = suma2.toString().replace(",", "");
		
		var total = parseFloat(suma1) + parseFloat(suma2);
		$("#" + destino).val(formatear_pesos(total));
		}
		
function ocultaCamposProveedor(){
		if($("#v").val() == 1)
				var opcion = $("#id_tipo_proveedor").val();
		else
				var opcion = $("#id_tipo_proveedor").find("option:selected").val();
				
		var envia_datos = "id=" + opcion;
		var url = "ocultaCamposProveedor.php";
		var respuesta = ajaxN(url, envia_datos);
		if(respuesta != 1){
				/*$("#div_fila_catalogo_4").hide();
				$("#tabla_fila_catalogo_4").hide();*/
				$("#div_fila_catalogo_19").hide();
				$("#fila_catalogo_19").hide();
				$("#divgrid_detalleMarcasYmontos").hide();
				$("#divgrid_detalleProductos").hide();
				}
		else{
				/*$("#div_fila_catalogo_4").show();
				$("#tabla_fila_catalogo_4").show();*/
				$("#div_fila_catalogo_19").show();
				$("#fila_catalogo_19").show();
				$("#divgrid_detalleMarcasYmontos").show();
				$("#divgrid_detalleProductos").show();		
			}
		}
		
function imprimeBitacora(bitacora){
		window.open('../../code/pdf/imprimeBitacora.php?bitacora=' + bitacora, "Bitacora de Ruta", "width=800, height=600");		
		}

function verPDFEgreso(numeroFila){
	var llave = celdaValorXY('detallePagosEgresosCuentasPorPagar',6,numeroFila);
	if(llave=='../../comprobantes/')
		alert("No existe documento asociado al egreso bancario: "+ celdaValorXY('detallePagosEgresosCuentasPorPagar',1,numeroFila));
	else
		//----REPLACE(referencia_xml,'../../','".$rooturl."') as referencia_xml,
		window.open(llave);
		
	}

function imprimirMov(mov){
		window.open('../../code/pdf/imprimeMovimientos.php?mov=' + mov, "Almacen", "width=800, height=600");		
		}
		
function imprimeOrdenCompra(orden){
		if($("#v").val() == 1)
				var ordenC = orden;
		else
				var ordenC = celdaValorXY('listado',0,orden);
		window.open('../../code/pdf/imprimeOrdenCompra.php?orden=' + ordenC, "Orden de Compra", "width=800, height=600");		
		}

function verificaModOrden(orden){
		var envia_datos = "id=" + orden;
		var url = "verificaModOrden.php";
		var respuesta = ajaxN(url, envia_datos);
		return respuesta;
		}
		
function colocaExistenciaLote(pos){
		var lote = $("#detalleMovimientosAlmacen_9_" + pos).text();
		var existencia = lote.split(":");
		$("#detalleMovimientosAlmacen_10_" + pos).attr("valor", existencia[1]);
		$("#detalleMovimientosAlmacen_10_" + pos).html(existencia[1]);
		}
		
function colocaClienteOrden(pos){
		var pedido = $("#detalleOrdenRecoleccionPedido_2_" + pos).attr("valor");
		var envia_datos = "id=" + pedido;
		var url = "obtenClienteOrden.php";
		var respuesta = ajaxN(url, envia_datos);
		var clientes = respuesta.split("|");
		$("#detalleOrdenRecoleccionPedido_4_" + pos).attr("valor", clientes[0]);
		$("#detalleOrdenRecoleccionPedido_5_" + pos).html(clientes[1]);
		}
		
function limpiaGridOrden(){
		$('table#Body_detalleProductosOrdenesCompra tr').each(function(index) {
				EliminaFila('detalleProductosOrdenesCompra_11_' + index);
				});
		nuevoGridFila('detalleProductosOrdenesCompra');
		}
function muestraCheque(){
		
		if($("#v").val() == 0)
				var opcion = $("#id_forma_pago_deposito_egreso").find("option:selected").val();
		else
				var opcion = $("#id_forma_pago_deposito_egreso").val();
		
		if(opcion == 3 || opcion == 4){
				$("#fila_catalogo_5").show();
				}
		else{
				$("#numero_cheque").val("");
				$("#fila_catalogo_5").hide();
				}
		
		}
		
		
function imprimeEmbarque(pos){
		//var movimiento = celdaValorXY('detalleBitacoraRutasEntrega', 27, pos);
		var bitacora = $("#id_bitacora_ruta").val();
		var pedido = celdaValorXY('detalleBitacoraRutasEntrega', 30, pos);
		
		window.open('../../code/pdf/imprimePedidoEmbarque.php?pedido=' + pedido + "&bitacora=" + bitacora, "Pedido", "width=1000, height=1000");		
		}
		
function verificaModBitacora(bitacora){
		var envia_datos = "id=" + bitacora; 
		var url = "modificaBitacora.php";
		var respuesta = ajaxN(url, envia_datos);
		return respuesta;
		}
		
function obtenRutaDireccion(){
		var direccion = $("#id_cliente_direccion_entrega").find("option:selected").val();
		var selectHijo = "id_ruta";
		var urlAjax = "obtenRutaDireccion.php";
		var envio_datos = 'id=' + direccion; 
		ajaxCombos(urlAjax, envio_datos, selectHijo);
		
		}
		
function validaCantidad(tabla, pos, producto, cantidad){
		var pedido = $("#id_pedido").find("option:selected").val();
		if(pedido == 0){
				alert("Selecciona un pedido")
				borraContenidoCeldas(tabla, pos, producto, cantidad); //Function para borrado completo de celda parametros : (grid, fila, columna del al)
				}
		else{
				var cantidadPed = $("#" + tabla + "_" + cantidad + "_" + pos).attr("valor");
				var productoPed = $("#" + tabla + "_" + producto + "_" + pos).attr("valor");
				cantidadPed = cantidadPed == "" ? cantidadPed = 0 : cantidadPed; 
				var envia_datos = "id=" + pedido + "&producto=" + productoPed; 
				var url = "cantidadPedido.php";
				var cantidadPedido = ajaxN(url, envia_datos);
				if(parseInt(cantidadPed) > parseInt(cantidadPedido)){
						alert("La cantidad anotada es mayor a la cantidad de productos del pedido");
						borraContenidoCeldas(tabla, pos, cantidad, cantidad); //Function para borrado completo de celda parametros : (grid, fila, columna del al)
						}
						
				}
		
		}
		
function validaCantidadExistencia(tabla, pos, existencia, cantidad){
		var cantidadTabla = $("#" + tabla + "_" + cantidad + "_" + pos).attr("valor");
		var existenciaTabla = $("#" + tabla + "_" + existencia + "_" + pos).attr("valor");
		
		cantidadTabla = cantidadTabla == "" ? cantidadTabla = 0 : cantidadTabla; 
		existenciaTabla = existenciaTabla == "" ? existenciaTabla = 0 : existenciaTabla; 
		
		if(parseInt(cantidadTabla) > parseInt(existenciaTabla)){
				alert("La cantidad anotada es mayor a la cantidad de existencia del producto");
				borraContenidoCeldas(tabla, pos, cantidad, cantidad); //Function para borrado completo de celda parametros : (grid, fila, columna del al)
				}
		
		}

function obtenExistenciaProductos(tabla, producto, existencia, pos){
		var producto = celdaValorXY(tabla, producto, pos);
		var envia_datos = "idProducto=" + producto;
		var url = "obtenExistenciaProducto.php";
		var respuesta = ajaxN(url, envia_datos);
		
		$("#" + tabla + "_" + existencia + "_" + pos).attr("valor", respuesta);
		$("#" + tabla + "_" + existencia + "_" + pos).html(respuesta);
		
		validaCantidadExistencia('detalleSolicitudDevolucionCEDIS', pos, 4, 5);
		
		}
function cxpNota(){
		var proveedor = $("#id_proveedor").find("option:selected").val();
		var envio_datos = "idProveedor=" + proveedor;
		var urlAjax = "cxpNotaCredito.php";
		var selectHijo = "id_cuenta_por_pagar";
		ajaxCombos(urlAjax, envio_datos, selectHijo);
		}
		
function datoDuplicadoGrid(posy, posx, tabla){
		var datoD = $("#" + tabla + "_" + posx + "_" + posy).attr("valor");
		var verifica = 0;
		$('table#' + tabla + ' tr').each(function(index) {
				var datoV = $(this).children().filter("[id^=" + tabla + "_" + posx + "_]").attr("valor");
				if(datoV == datoD){
						verifica += 1;
						}
				});
		if(verifica > 1){
				alert("Este elemento ya esta registrado en el grid\nfavor de verificar");
				borraContenidoCeldas(tabla,posy,2,3);
				}
		}
function buscaPedidoCancelado(){
		var cliente = $("#hcampo_3").val();
		var envio_datos = "idCliente=" + cliente;
		var urlAjax = "buscaPedidoCancelado.php";
		var selectHijo = "id_pedido_relacionado";
		ajaxCombos(urlAjax, envio_datos, selectHijo);
		}	
		
function llenaProductosPedido(){
		$("#Body_detalleValeProductos tr").remove();
		var pedido = $("#id_pedido_relacionado").find("option:selected").val();
		var envia_datos = "pedido=" + pedido;
		var url = "llenaProductosPedido.php";
		var result = ajaxN(url, envia_datos);
		var datos = JSON.parse(result);
		
		var cuentafilas = $("#Body_detalleValeProductos tr").length;
		if(cuentafilas == 0)
				nuevoGridFila('detalleValeProductos'); 
			
		var filas = datos.productos.length;
		if(filas > 0){
				for (var i=0; i<filas; i++) {
						$("#detalleValeProductos_2_" + i).attr("valor", datos.productos[i].id_producto); 
						$("#detalleValeProductos_3_" + i).attr("valor", datos.productos[i].producto); 
						$("#detalleValeProductos_3_" + i).html(datos.productos[i].producto); 
										
						$("#detalleValeProductos_4_" + i).attr("valor", datos.productos[i].cantidad); 
						$("#detalleValeProductos_4_" + i).html(datos.productos[i].cantidad); 
										
						$("#detalleValeProductos_5_" + i).attr("valor", datos.productos[i].observaciones); 
						$("#detalleValeProductos_5_" + i).html(datos.productos[i].observaciones); 
										
						nuevoGridFila('detalleValeProductos'); 
						}
				//EliminaFila('detalleValeProductos_12_' + i);
				$("#Body_detalleValeProductos tr#detalleValeProductos_Fila" + i).remove();
				}
		}
function imprimeVale(vale){
		window.open('../../code/pdf/imprimeVale.php?vale=' + vale, "Pedido", "width=1000, height=1000");		
		}

function validaVale(pos){
		if($("#detallePedidosPagos_3_" + pos).attr("valor") == 9){
				var vale = $("#detallePedidosPagos_7_" + pos).attr("valor");
				var cliente = $("#hcampo_5").val();
				var envia_datos = "vale=" + vale + "&cliente=" + cliente;
				var url = "verificaValeProducto.php";
				var result = ajaxN(url, envia_datos);
				var datos = JSON.parse(result);
				if(datos.status == 0){
						alert(datos.mensaje);
						borraContenidoCeldas("detallePedidosPagos",pos,7,7);
						borraContenidoCeldas("detallePedidosPagos",pos,11,11);
						calculaTotalPagos();
						colocaTipoPago();
						}
				else{
						$("#detallePedidosPagos_11_" + pos).attr("valor",datos.total);
						$("#detallePedidosPagos_11_" + pos).html("$" + formatear_pesos(datos.total));
						calculaTotalPagos();
						colocaTipoPago();
						}
				
				
				}
		}
		
		
		
		
		
		
		
		
		
		
		
