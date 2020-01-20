//JavaScript Document
//// -GENERALES -->
function validaCantidadAIngresar(cantidadIngresar,cantidadBase, criterio){
	/* ************************************************************************************************************
	Esta funcion, valida que un numero se mayor, menor mayor igual, menor igual , igual, diferente de la 
	cantidad a ingresa con respecto a la cantidad a comparar, de acuerdo al criterio
	
	Posibles valores:
	mayor
	mayor-igual
	menor
	menor-igual
	igual
	diferente
	************************************************************************************************************* */
	var bandera = false;
	var mensajeInicio = 'Por favor, corrija la cantidad a ingresar. Debe ser '+criterio+' a ';
	switch(criterio){
		case 'mayor':
			if(cantidadIngresar>cantidadBase) {bandera = true;}
			else{alert(mensajeInicio+cantidadBase); bandera = false;}
		break;
		case 'mayor igual':
			if(cantidadIngresar>=cantidadBase) {bandera = true;}
			else{alert(mensajeInicio+cantidadBase); bandera = false;}
		break;
		case 'menor':
			if(cantidadIngresar<cantidadBase) {bandera = true;}
			else{alert(mensajeInicio+cantidadBase); bandera = false;}
		break;
		case 'menor o igual':
			if(cantidadIngresar<=cantidadBase) {bandera = true;}
			else{alert(mensajeInicio+cantidadBase); bandera = false;}
		break;
		case 'igual':
			if(cantidadIngresar==cantidadBase) {bandera = true;}
			else{alert(mensajeInicio+cantidadBase); bandera = false;}
		break;
		case 'diferente':
			if(cantidadIngresar!=cantidadBase) {bandera = true;}
			else{alert(mensajeInicio+cantidadBase); bandera = false;}
		break;
		default:
			mensajeInicio='';
		break;
	}
	return (bandera);
}
//// -GENERALES <--

function buscaRequisicionesPendientesDeAprobacion(){
	var error = 0;
	var idSucursal = $("#select-sucursal").find("option:selected").val();
	var idUsuarioSolicitante = $("#select-usuario-solicitante").find("option:selected").val();
	var fecha_inicio = $("#fechadel").val();
	var fecha_fin = $("#fechaal").val();
	if((idSucursal == '-1')&&(idUsuarioSolicitante == '-1')&&(fecha_inicio == '')&&(fecha_fin=='')){
		alert('Debe elgir un crierio');
		error = 1;
	}
	if(fecha_inicio > fecha_fin){
		alert("La fecha incial no puede ser mayor a la fecha final");
		error = 1;
	}

	if(error == 0){
		var FechaInicioConv = cambiarFormatoFecha(fecha_inicio,'ymd','-');
		var FechaFinConv = cambiarFormatoFecha(fecha_fin,'ymd','-');
		$(".detalle tbody tr").remove();
		var ruta = "llenaTablaDetalle.php";
		var envio = "id=" + idSucursal + "&idUsuarioSolicitante=" + idUsuarioSolicitante + "&fecini=" + FechaInicioConv + "&fecfin=" + FechaFinConv + "&proceso=requisiciones";
		var respuesta = ajaxN(ruta, envio);
		$(".detalle tbody").append(respuesta);
	}
}

function apruebaRequisicion(pos){
	var id = $("#idRequisicion" + pos).val();
	var confirma=confirm("Esta seguro de querer aprobar la requisicion con ID: " + id + "?");
	if(confirma==true){
		var envia_datos = "idRequisicion=" + id + "&caso=2";
		var url = "apruebaRechazoRequisicion.php";
		var respuesta = ajaxN(url, envia_datos);
		alert(respuesta);
		location.reload();
	}
	else{
		return false;
	}
}

function rechazaRequisicion(pos){
	var id = $("#idRequisicion" + pos).val();
	var rechazo = $("#motivo-rechazo" + pos).val();
	if(rechazo == ""){
		alert("Debes elegir un motivo del rechazo");
		$("#motivo-rechazo" + pos).css("border", "1px #F75151 solid");
	}
	else{
		var confirma=confirm("Esta seguro de querer rechazar la requisicion con ID: " + id +"?");
		if(confirma==true){
			var envia_datos = "idRequisicion=" + id + "&caso=3&motivo=" + rechazo;
			var url = "apruebaRechazoRequisicion.php";
			var respuesta = ajaxN(url, envia_datos);
			alert(respuesta);
			location.reload();
		}
		else{
			return false;
		}
	}
}

function buscaOrdenesDeCompraAutorizadas(tipo_busqueda){
	var error = 0;
	var idSucursal = $("#select-sucursal").find("option:selected").val();
	var idUsuarioSolicitante = $("#select-usuario-solicitante").find("option:selected").val();
	var idProducto = $("#select-producto").find("option:selected").val();
	var fecha_inicio = $("#fechadel").val();
	var fecha_fin = $("#fechaal").val();
	var idRequisicion = $("#idrequisicion").val();
	//var FechaInicioConv = convierteFechaJava(fecha_inicio);
	//var FechaFinConv = convierteFechaJava(fecha_fin);
	
	
	if(tipo_busqueda==1){
		if((idSucursal == '-1')&&(idUsuarioSolicitante == '-1')&&(idProducto == '-1')&&(fecha_inicio == '')&&(fecha_fin=='')){
			alert('Debe elgir un crierio');
			error = 1;
		}
		if(fecha_inicio > fecha_fin){
			alert("La fecha incial no puede ser mayor a la fecha final");
			error = 1;
		}
	}else if(tipo_busqueda==2){
		if(idRequisicion==''){
			alert('Debe introducir una requisicion');
			error = 1;
		}
	}
	if(error == 0){
		var FechaInicioConv = cambiarFormatoFecha(fecha_inicio,'ymd','/');
		var FechaFinConv = cambiarFormatoFecha(fecha_fin,'ymd','/');
	
		$(".detalle tbody tr").remove();
		var ruta = "llenaTablaDetalle.php";
		var envio = "id=" + idSucursal + "&idUsuarioSolicitante=" + idUsuarioSolicitante + "&fecini=" + FechaInicioConv + "&fecfin=" + FechaFinConv + "&idRequisicion="+idRequisicion+"&idProducto="+idProducto+"&proceso=ordenes_compra_autorizadas";
		var respuesta = ajaxN(ruta, envio);
		$(".detalle tbody").append(respuesta);
	}
}

function obtenerProveedorODCdeProducto(objetoPadre, hijo, table, idProducto, renglon){
	if(objetoPadre.value==0){
		$.ajax({ 
			method: "POST",
			url: "../ajax/cargaDependencias.php",
			data:{tabla:table,id:objetoPadre.value,idProducto:idProducto},
			dataType:"json"
		})
		.done(function(data){
			if(data!=null){
				$("#" + hijo + " option").remove();
				$("#" + hijo).append("<option value='0'>Seleccione una opci&oacute;n</option>");
				$.each(data, function(index, value){
					$("#" + hijo).append("<option value='" + value.id + "'>" + value.nombre + "</option>");
					$("#" + hijo).prop('disabled','');
					$("#permite_mezcla_de_productos"+renglon).val( value.mezclar );
				});
			}else{$("#permite_mezcla_de_productos"+renglon).val('');}
		});
	}
}


function asignaPermiteMezcla(idProveedor, renglon){
	$.ajax({
		type: "POST",
		url: "../ajax/respuestaAsignaPermiteMezcla.php",
		data: "idProveedor="+idProveedor,
		success: function (respuesta){
			respuesta=respuesta.replace("\n","");
			respuesta=respuesta.replace("\r","");
			$("#permite_mezcla_de_productos"+renglon).val( respuesta );
		}
	});
}



function obtenerAlmacenDePlaza(objetoPadre, hijo, table, idAlmacen){
	if(objetoPadre.value==0){
		$.ajax({ 
			method: "POST",
			url: "../ajax/cargaDependencias.php",
			data:{tabla:table,id:objetoPadre.value,id:idAlmacen},
			dataType:"json"
		})
		.done(function(data){
			if(data!=null){
				$("#" + hijo + " option").remove();
				$("#" + hijo).append("<option value='0'>Selecciona una opci&oacute;n</option>");
				$.each(data, function(index, value){
					$("#" + hijo).append("<option value='" + value.id + "'>" + value.nombre + "</option>");
					$("#" + hijo).prop('disabled','');
				});
			}
		});
	}
}

function cancelarDetalleDeRequisicionAutorizada(idDetalleRequisicion, pos){
	//alert('ID del Detalle de la Requisicion Autorizada: '+idDetalleRequisicion);
	//var id = $("#idRequisicion" + pos).val();
	var motivo = $("#cancelar" + pos).val();
	if(motivo == ""){
		alert("Debes elegir un motivo de la cancelacion");
		$("#cancelar" + pos).css("border", "1px #F75151 solid");
	}
	else{
		var confirma=confirm("Esta seguro de querer cancelar el detalle de la requisicion con ID: " + idDetalleRequisicion +"?");
		if(confirma==true){
			var envia_datos = "idDetalleRequisicion=" + idDetalleRequisicion + "&caso=0&motivo=" + motivo;
			var url = "cancelacionDetalleDeRequisicionAutorizada.php";
			var respuesta = ajaxN(url, envia_datos);
			alert(respuesta);
			location.reload();
		}
		else{
			return false;
		}
	}
}

/*******************************************************************************************************************/
function generarODCaPartirDeRequisicionAutorizada(tabla){
	var idDetalles = new Array();
	var idRequisiciones = new Array();
	var idProveedores = new Array();
	var permiteMezclaDeProductos = new Array();
	var idAlmacenes = new Array();
	var idSucursales = new Array();
	var idUsuariosSolicitan = new Array();
	var idProductos = new Array();
	var cantidades = new Array();
	var precios = new Array();
	var importes = new Array();
	var idTiposProductos = new Array();
	var fechasRequeridas = new Array();

	var obj = document.getElementById(tabla);
	var Trs = obj.getElementsByTagName('tr');
	var seleccionado = false;
	
	var	idDetalle = 0;
	var	idRequisicion = 0;
	var idProveedor = 0;
	var permiteMezclaDeProducto = 0;
	var idAlmacen = 0;
	var idProducto = 0;
	var cantidad = 0;
	var precio = 0;
	var importe = 0;
	var idTipoProducto = 0;
	var fechaRequerida = 0;

	var bandera = false;

	for(var i=1; i<Trs.length+1; i++){
		seleccionado = $('#idDetalleCheck_'+i).is(':checked');
		if(seleccionado) break;
	}
	// VALIDACIONES --->
	if(seleccionado){
		for(var i=1; i<Trs.length+1; i++){
			seleccionado = $('#idDetalleCheck_'+i).is(':checked');
			if(seleccionado){
				idDetalle = $('#idDetalleCheck_'+i).val();
				idRequisicion = $('#idRequisicion'+i).val();
				idProveedor = $('#c-det-prov_'+i).val();
				permiteMezclaDeProducto = $('#permite_mezcla_de_productos'+i).val();
				idAlmacen = $('#c-det-almacen_'+i).val();
				idSucursal = $('#idSucursal'+i).val();
				idUsuarioSolicita = $('#idUsuarioSolicita'+i).val();
				idProducto = $('#idProducto'+i).val();
				cantidad = $('#cantidad'+i).val();
				precio = $('#precio'+i).val();
				importe = $('#importe'+i).val();
				idTipoProducto = $('#idTipoProducto'+i).val();
				fechaRequerida = $('#fechaRequerida'+i).val();

				//alert(idDetalle+" : "+idProveedor+" : "+idAlmacen);
				if(!((idProveedor==0)||(idProveedor==null)||(idAlmacen==0)||(idAlmacen==null))){
				    idDetalles.push(idDetalle);
				    idRequisiciones.push(idRequisicion);
					idProveedores.push(idProveedor);
					permiteMezclaDeProductos.push(permiteMezclaDeProducto);
					idAlmacenes.push(idAlmacen);
					idSucursales.push(idSucursal);
					idUsuariosSolicitan.push(idUsuarioSolicita);
					idProductos.push(idProducto);
					cantidades.push(cantidad);
					precios.push(precio);
					importes.push(importe);
					idTiposProductos.push(idTipoProducto);
					fechasRequeridas.push(fechaRequerida);
				}
				else{
					alert('Debe elegir un proveedor o un almacen de plaza'); 
					bandera = true;
					break;
				}
			}
		}
	}
	else{
		alert("Seleccione algun detalle");
		bandera = true;
	}
	// VALIDACIONES <---
//	alert('IdProveedor: '+idProveedor);
//	alert('Permite Mezcla: '+permiteMezclaDeProducto);

	if(!bandera){
		var url = "generaODC.php";
		var envia_datos = 'idDetalles='+idDetalles+"&idProveedores="+idProveedores+"&idAlmacenes="+idAlmacenes+"&idSucursales="+idSucursales+"&idUsuariosSolicitan="+idUsuariosSolicitan+"&idProductos="+idProductos+"&cantidades="+cantidades+"&precios="+precios+"&importes="+importes+"&idTiposProductos="+idTiposProductos+"&idRequisiciones="+idRequisiciones+"&permiteMezclaDeProductos="+permiteMezclaDeProductos+"&fechasRequeridas="+fechasRequeridas;
		var respuesta = ajaxN(url, envia_datos);

		alert(respuesta);
		location.reload();
		
		//var datos = respuesta.split("|");
		//alert(datos[1]);
		//registraEgresos(datos[0]);
		//location.reload();
	}


}
/*******************************************************************************************************************/
/********************************* TODO LO RELACIONADO A APROBACION DE ORDENES DE COMPRA ***************************/
function buscaOrdenesDeCompraPendientesDeAprobacion(tipo_busqueda){
	var error = 0;
	var idSucursal = $("#select-sucursal").find("option:selected").val();
	var idUsuarioSolicitante = $("#select-usuario-solicitante").find("option:selected").val();
	var fecha_inicio = $("#fechadel").val();
	var fecha_fin = $("#fechaal").val();
	var idOrdenCompra = $("#idordencompra").val();
	//var FechaInicioConv = convierteFechaJava(fecha_inicio);
	//var FechaFinConv = convierteFechaJava(fecha_fin);
	
	
	if(tipo_busqueda==1){
		if((idSucursal == '-1')&&(idUsuarioSolicitante == '-1')&&(fecha_inicio == '')&&(fecha_fin=='')){
			alert('Debe elgir un crierio');
			error = 1;
		}
		if(fecha_inicio > fecha_fin){
			alert("La fecha incial no puede ser mayor a la fecha final");
			error = 1;
		}
	}else if(tipo_busqueda==2){
		if(idOrdenCompra==''){
			alert('Debe introducir una orden de compra');
			error = 1;
		}
	}
	if(error == 0){
		var FechaInicioConv = cambiarFormatoFecha(fecha_inicio,'ymd','-');
		var FechaFinConv = cambiarFormatoFecha(fecha_fin,'ymd','-');

		$(".detalle tbody tr").remove();
		var ruta = "llenaTablaDetalle.php";
		var envio = "idSucursal=" + idSucursal + "&idUsuarioSolicitante=" + idUsuarioSolicitante + "&fecini=" + FechaInicioConv + "&fecfin=" + FechaFinConv + "&idOrdenCompra="+idOrdenCompra+"&proceso=ordenes_compra_pendientes_de_aprobacion";
		var respuesta = ajaxN(ruta, envio);
		$(".detalle tbody").append(respuesta);
	}
}

function apruebaOrdenDeCompra(pos){
	var id = $("#idOrdenCompra" + pos).val();
	var idControl = $("#idOrdenCompraControl" + pos).val();
	var aprueba = $("#motivo-rechazo" + pos).val();
	var confirma=confirm("Est\u00e1 seguro de querer aprobar la Orden de Compra: "+id+"?");
	if(confirma==true){
		var envia_datos = "idControlOrdenCompra=" + idControl + "&caso=2&motivo=" + aprueba;
		var url = "apruebaRechazoOrdenCompra.php";
		var respuesta = ajaxN(url, envia_datos);
		alert(respuesta);
		location.reload();
	}
	else{
		return false;
	}
}

function rechazaOrdenDeCompra(pos){
	var id = $("#idOrdenCompra" + pos).val();
	var idControl = $("#idOrdenCompraControl" + pos).val();
	var rechazo = $("#motivo-rechazo" + pos).val();
	if(rechazo == ""){
		alert("Debes elegir un motivo del rechazo");
		$("#motivo-rechazo" + pos).css("border", "1px #F75151 solid");
	}
	else{
		var confirma=confirm("Est\u00e1 seguro de querer rechazar la Orden de Compra: "+id+"?");
		if(confirma==true){
			var envia_datos = "idControlOrdenCompra=" + idControl + "&caso=3&motivo=" + rechazo;
			var url = "apruebaRechazoOrdenCompra.php";
			var respuesta = ajaxN(url, envia_datos);
			alert(respuesta);
			location.reload();
		}
		else{
			return false;
		}
	}
}
/********************************* TODO LO RELACIONADO A APROBACION DE ORDENES DE COMPRA ***************************/

/********** REQUSICIONES ***********/
function verRequisicion(idRequisicion){
	$.fancybox({
		type: 'iframe',
//		href: '../general/encabezados.php?t=Y3VlbnRhc19wb3JfcGFnYXJfb3BlcmFkb3Jh&k=&op=1&tcr=&stm=&idRequisicion=' + idRequisicion,
		href: '../general/encabezados.php?t=YWRfcmVxdWlzaWNpb25lcw==&k='+idRequisicion+'&op=2&v=1&tabla=&cadP1=MDI0ZG5CbGZqRjhibkJsZmpGOGJYQmxmakY4MzQ=&cadP2=MDI0WlhCbGZqRjhhWEJsZmpGOFozQmxmakU9MzQ=&hf=10',
		maxWidth	: 900,
		maxHeight	: 800,
		fitToView	: false,
		width		: '80%',
		height		: '80%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'elastic',
		afterClose  : function(){
		}
	});
}


function verOrdenDeCompra(idOrdenDeCompra){
	$.fancybox({
		type: 'iframe',
//		href: '../general/encabezados.php?t=Y3VlbnRhc19wb3JfcGFnYXJfb3BlcmFkb3Jh&k=&op=1&tcr=&stm=&idRequisicion=' + idRequisicion,
		href: '../general/encabezados.php?t=YWRfb3JkZW5lc19jb21wcmFfcHJvZHVjdG9z&k='+idOrdenDeCompra+'&op=2&v=1&tabla=&cadP1=MDI0ZG5CbGZqRjhibkJsZmpGOGJYQmxmakY4MzQ=&cadP2=MDI0WlhCbGZqRjhhWEJsZmpGOFozQmxmakU9MzQ=&hf=10',
		maxWidth	: 1200,
		maxHeight	: 800,
		fitToView	: false,
		width		: '80%',
		height		: '80%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'elastic',
		afterClose  : function(){
		}
	});
}
/********** REQUSICIONES ***********/

/**** ENTRADAS A ALMACEN ****/
/*
function importarNumeroSerieAlmacen(cantidadAIngresar, idLayout, idAlmacen, idOrdenCompra, numeroRenglon, validacionTipoEquipo){
	var idOrdenCompraAux = idOrdenCompra;
	$.fancybox({
		type: 'iframe',
		href: 'importaciones.php?idLayout='+idLayout+'&cantidadAIngresar='+cantidadAIngresar+'&idAlmacen='+idAlmacen+'&idOrdenCompra='+idOrdenCompra+'&numeroRenglon='+numeroRenglon+'&opc=100&validacionTipoEquipo='+validacionTipoEquipo,
		maxWidth	: 900,
		maxHeight	: 800,
		fitToView	: false,
		width		: '80%',
		height		: '80%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'elastic',
		afterClose  : function(){
		}
	});
}
*/
/**** ENTRADAS A ALMACEN ****/


/********************************* TODO LO RELACIONADO A ENTRADAS A PARTIR DE ORDENES DE COMPRA ***************************/
function importarNumeroSerie(cantidadAIngresar, idLayout, idAlmacen, idOrdenCompra, numeroRenglon, validacionTipoEquipo, idDetalleOrdenCompra){
	var idOrdenCompraAux = idOrdenCompra;
	//alert(cantidadAIngresar+'-'+idLayout+'-'+ idAlmacen+'-'+ idOrdenCompra+'-'+ numeroRenglon+'-'+ validacionTipoEquipo+'-'+ idDetalleOrdenCompra);
	$.fancybox({
		type: 'iframe',
		href: 'importaciones.php?idLayout='+idLayout+'&cantidadAIngresar='+cantidadAIngresar+'&idAlmacen='+idAlmacen+'&idOrdenCompra='+idOrdenCompra+'&numeroRenglon='+numeroRenglon+'&opc=100&validacionTipoEquipo='+validacionTipoEquipo+'&idDetalleOrdenCompra='+idDetalleOrdenCompra,
		maxWidth	: 900,
		maxHeight	: 800,
		fitToView	: false,
		width		: '80%',
		height		: '80%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'elastic',
		afterClose  : function(){
		}
	});
}

function importarNumeroSerieParaSalidaDeAlmacen(cantidadAIngresar, numeroRenglon){
	var cantidadAIngresar = $("#cantidadI"+numeroRenglon).val();
	if(cantidadAIngresar==""){
		alert('Introduzaca la cantidad a surtir');
		return false;
	}else{
		$.fancybox({
			type: 'iframe',
			href: 'importarNumeroSerieParaSalidaDeAlmacen.php?cantidadAIngresar='+cantidadAIngresar+'&numeroRenglon='+numeroRenglon,
			maxWidth	: 900,
			maxHeight	: 800,
			fitToView	: false,
			width		: '80%',
			height		: '80%',
			autoSize	: false,
			closeClick	: false,
			openEffect	: 'none',
			closeEffect	: 'elastic',
			afterClose  : function(){
			}
		});
	}
}

function capturarNumeroSerie(idOrdenCompra, renglon, idLayout, idAlmacen, cantidadAIngresar, idProducto, idDetalleOrdenCompra){
	//alert(cantidadAIngresar+'-'+idLayout+'-'+ idAlmacen+'-'+ idOrdenCompra+'-'+ numeroRenglon+'-'+ validacionTipoEquipo+'-'+ idDetalleOrdenCompra);
	var cantidadAIngresar = $("#cantidadI"+idOrdenCompra+renglon).val();
	$.fancybox({
		type: 'iframe',
		href: 'capturaNumerosSeries.php?idOrdenCompra='+idOrdenCompra+'&renglon='+renglon+'&idLayout='+idLayout+'&idAlmacen='+idAlmacen+'&cantidadAIngresar='+cantidadAIngresar+'&idProducto='+idProducto+'&idDetalleOrdenCompra='+idDetalleOrdenCompra,
		maxWidth	: 900,
		maxHeight	: 950,
		fitToView	: false,
		width		: '80%',
		height		: '90%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'elastic',
		afterClose  : function(){
			/*if($("#cantidadIAux"+idOrdenCompra+renglon).length){
				$("#cantidadIAux"+idOrdenCompra+renglon).val(cantidadAIngresar);
			}*/
		}
	});
}

function capturarNumeroSerieParaSalidaDeAlmacen(renglon, idAlmacen, cantidadAIngresar, idProducto){
	var cantidadAIngresar = $("#cantidadI"+renglon).val();
	if(cantidadAIngresar==""){
		alert('Introduzaca la cantidad a surtir');
		return false;
	}else{
		$.fancybox({
			type: 'iframe',
			href: 'capturarNumeroSerieParaSalidaDeAlmacen.php?renglon='+renglon+'&idAlmacen='+idAlmacen+'&cantidadAIngresar='+cantidadAIngresar+'&idProducto='+idProducto,
			maxWidth	: 900,
			maxHeight	: 950,
			fitToView	: false,
			width		: '80%',
			height		: '90%',
			autoSize	: false,
			closeClick	: false,
			openEffect	: 'none',
			closeEffect	: 'elastic',
			afterClose  : function(){
				//$("#cantidadIAux"+idOrdenCompra+renglon).val(cantidadAIngresar);
			}
		});
	}
}


function validaCantidadesImportadas(idOrdenCompra, renglon) {
	var cantidadI = "";
	var cantidadIAux = "";
	var requiereNumersoDeSerie = "";
	var sumaCantidadI = 0;
	var error = "";
	for (var i = 0; i < renglon; i++) {
		requiereNumerosDeSerie = $("#requiereNumerosDeSerie"+idOrdenCompra+i).val();
		cantidadI = $("#cantidadI"+idOrdenCompra+i).val();
		cantidadIAux = $("#cantidadIAux"+idOrdenCompra+i).val();
		if(requiereNumerosDeSerie=='1'){
			if(cantidadI!=cantidadIAux){
				error = error + "Error en en renglon: "+(i+1)+" del detalle de la ODC: "+idOrdenCompra+". La Cantidad a Ingresar y el n\u00famero de registro importados no coinciden\n";
			}
		}
		sumaCantidadI = sumaCantidadI + cantidadI;
	}

	if(sumaCantidadI==0) error = error + "Debe introducir la Cantidad a Ingresar de al menos un producto en el detalle";
	if(error!="") {
		alert(error);
		return false;
	}
	else{
		//alert('Empieza a guardar:'+error);
		return true;
	}
}

function validaVaciosNumerosDeSerie(numerosDeSerie){
	var bandera = false;
	if(numerosDeSerie.length<=0){
		bandera=true;
		alert('Debe introducir algun valor');
		document.getElementById('TxtValor').focus();
	}else{

		for(var i=0; i<numerosDeSerie.length-1; i++){
			var valor = numerosDeSerie[i];
			if(valor=="") {
				bandera=true;
				alert('No puede dejar cajas de texto en blanco');
				document.getElementById('TxtNumeroSerie'+i).focus();
				break;
			}
		}
	}
	if(bandera) return true;
}

function validaCapturaNumerosDeSerie(idOrdenCompra, idAlmacen, idProducto, idPlaza, numeroRenglon, idDetalleOrdenCompra){
	var arreglo_numeros_serie = new Array();
	var auxiliar;
	var cantidad_capturada_de_numeros_de_serie = $("#TxtContador").val();
	var cantidad_a_capturar_de_numeros_de_serie = $("#cantidadAIngresar").val();
	if(cantidad_capturada_de_numeros_de_serie != cantidad_a_capturar_de_numeros_de_serie){	//Valida que se intrduzcan el numero de datos indicados a capturar
		alert('Debe agregar los '+cantidad_a_capturar_de_numeros_de_serie+' n\u00fameros de serie');
		document.getElementById('TxtValor').focus();
		return true;
	}else{

		for(j=0; j<cantidad_capturada_de_numeros_de_serie; j++){
			auxiliar = document.getElementById('TxtValorLista'+j);
			if(auxiliar!=null){
				num_serie = $('#TxtValorLista'+j).html();
				arreglo_numeros_serie.push(num_serie);
			}
		}
		var id_carga =  $('#id_carga').val();
		$.ajax({
			type: "POST",
			url: "../ajax/respuestaValidaNumerosDeSerie.php",
			data: "arreglo_numeros_serie="+arreglo_numeros_serie+"&idOrdenCompra="+idOrdenCompra+"&idAlmacen="+idAlmacen+"&idProducto="+idProducto+"&idPlaza="+idPlaza+"&accion=valida&idCarga="+id_carga+"&idDetalleOrdenCompra="+idDetalleOrdenCompra,
			success: function (respuesta){
				respuesta=respuesta.replace("\n","");
				respuesta=respuesta.replace("\r","");
				var res = respuesta.split("|"); 
				if(res[0]=='correcto'){
					var numero_de_carga = res[1];
					//$('#captura_numero_serie').remove();
					//$('#correcto').show();
					
					/*
					alert('La informaci\u00f3n se ha validado correctamente, por favor haga clic en el bot\u00f3n Guardar');
					$('#Guardar').show();
					*/
					
					$('#captura_numero_serie').remove();
					$('#correcto').show();					
					$('#idCarga').val( numero_de_carga );
					
					window.top.eval("numeroCarga"+idOrdenCompra+numeroRenglon).value = numero_de_carga;
					
				}else{
					$('#lista_errores').show();
					$("#lista_errores").html(respuesta);
				}
			}
		});
	}
}

function validaCapturaNumerosDeSerieParaSalidaDeAlmacen(idAlmacen, idProducto, numeroRenglon){
	var arreglo_numeros_serie = new Array();
	var auxiliar;
	var cantidad_capturada_de_numeros_de_serie = $("#TxtContador").val();
	var cantidad_a_capturar_de_numeros_de_serie = $("#cantidadAIngresar").val();
	if(cantidad_capturada_de_numeros_de_serie != cantidad_a_capturar_de_numeros_de_serie){	//Valida que se intrduzcan el numero de datos indicados a capturar
		alert('Debe agregar los '+cantidad_a_capturar_de_numeros_de_serie+' n\u00fameros de serie');
		document.getElementById('TxtValor').focus();
		return true;
	}else{

		for(j=0; j<cantidad_capturada_de_numeros_de_serie; j++){
			auxiliar = document.getElementById('TxtValorLista'+j);
			if(auxiliar!=null){
				num_serie = $('#TxtValorLista'+j).html();
				arreglo_numeros_serie.push(num_serie);
			}
		}
		var id_carga =  $('#id_carga').val();
		$.ajax({
			type: "POST",
			url: "../ajax/respuestaValidaNumerosDeSerieParaSalidaDeAlmacen.php",
			data: "arreglo_numeros_serie="+arreglo_numeros_serie+"&idAlmacen="+idAlmacen+"&idProducto="+idProducto+"&accion=valida",
			success: function (respuesta){
				respuesta=respuesta.replace("\n","");
				respuesta=respuesta.replace("\r","");
				var res = respuesta.split("|"); 
				if(res[0]=='correcto'){
					//var numero_de_carga = res[1];
					//$('#captura_numero_serie').remove();
					//$('#correcto').show();
					
					/*
					alert('La informaci\u00f3n se ha validado correctamente, por favor haga clic en el bot\u00f3n Guardar');
					$('#Guardar').show();
					*/
					
					$('#captura_numero_serie').remove();
					$('#correcto').show();
					//$('#quitar').val(res[1]);
					//$('#idCarga').val( numero_de_carga );
					
					//window.top.eval("numeroCarga"+idOrdenCompra+numeroRenglon).value = numero_de_carga;
					
				}else{
					$('#lista_errores').show();
					$("#lista_errores").html(respuesta);
				}
			}
		});
	}
}

function guardarCapturaNumerosDeSerie(idOrdenCompra, idAlmacen, idProducto, idPlaza){
	var arreglo_numeros_serie = new Array();
	var auxiliar;
	var cantidad_capturada_de_numeros_de_serie = $("#TxtContador").val();
	if(cantidad_capturada_de_numeros_de_serie <= 0){	//Valida que se intrduzcan el numero de datos indicados a capturar
		alert('Debe introducir todos los n\u00fameros de serie');
		document.getElementById('TxtValor').focus();
		return true;
	}else{

		for(j=0; j<cantidad_capturada_de_numeros_de_serie; j++){
			auxiliar = document.getElementById('TxtValorLista'+j);
			if(auxiliar!=null){
				num_serie = $('#TxtValorLista'+j).html();
				arreglo_numeros_serie.push(num_serie);
			}
		}
		var id_carga =  $('#BtnIdCarga').val();
		$.ajax({
			type: "POST",
			url: "../ajax/respuestaValidaNumerosDeSerie.php",
			data: "arreglo_numeros_serie="+arreglo_numeros_serie+"&idOrdenCompra="+idOrdenCompra+"&idAlmacen="+idAlmacen+"&idProducto="+idProducto+"&idPlaza="+idPlaza+"&accion=guarda&idCarga="+id_carga+"&idDetalleOrdenCompra="+idDetalleOrdenCompra,
			success: function (respuesta){
				respuesta=respuesta.replace("\n","");
				respuesta=respuesta.replace("\r","");
				if(respuesta=='correcto'){
					$('#captura_numero_serie').remove();
					$('#correcto').show();
				}else{
					$('#lista_errores').show();
					$("#lista_errores").html(respuesta);
				}
			}
		});
	}
}

function desactivarIRDS(idOrdenCompra, idDetalleOrdenCompra, renglon, id_carga){
	//alert('Hola:'+idOrdenCompra+' : '+idDetalleOrdenCompra);
	/*
	var ruta = "desactivarNumeroSerie.php";
	var envio = "idOrdenCompra="+idOrdenCompra+"&idDetalleOrdenCompra="+idDetalleOrdenCompra;
	var respuesta = ajaxN(ruta, envio);
	alert(respuesta);
	location.reload();
	*/
	//var $contenidoAjax = $('div#lista_irds').html('<br/><p align="center"><img src="../../imagenes/general/wait.gif" width="20"/></p>');
	$("#cantidadIAux"+idOrdenCompra+renglon).val( $("#cantidadI"+idOrdenCompra+renglon).val() );
	$("#numeroCarga"+idOrdenCompra+renglon).val( id_carga );
	
	$.ajax({ 
		method: "GET",
		url: "../ajax/desactivarNumeroSerie.php",
		data:{idOrdenCompra:idOrdenCompra, idDetalleOrdenCompra:idDetalleOrdenCompra},
		dataType:"json",
		success: function(data) { // Aquí desaparece la imagen ya que estamos reemplazando todo el HTML del contenido de la div. 
			//$contenidoAjax.html(data);
			alert('Listo!, ahora puede ingresar la información nuevamente');
			$("#cantidadI"+idOrdenCompra+renglon).attr('readonly', false);
			$("#desbloquear"+idOrdenCompra+renglon).remove();
			$("#importarOrden"+idOrdenCompra+renglon).removeAttr("disabled");
			$("#capturarOrden"+idOrdenCompra+renglon).removeAttr("disabled");			
		}
	})
	.done(function(data){
		if(data!=null){}
	});

	
}

function ocultaListaDeErrores() {
	$('#lista_errores').hide();
}

function validaCantidadesIngresadas(idOrdenCompra, renglon) {
	var cantidadR = "";
	var cantidadI = "";
	var error = "";
	for (var i = 0; i < renglon; i++) {
		requiereNumerosDeSerie = $("#requiereNumerosDeSerie"+idOrdenCompra+i).val();
		cantidadR = $("#cantidadR"+idOrdenCompra+i).val();
		cantidadI = $("#cantidadI"+idOrdenCompra+i).val();
		
		if(cantidadI==""){cantidadI=0;}
		
		if(requiereNumerosDeSerie=='1'){
			if( cantidadI != '' && (parseFloat(cantidadI) < 1.0 || parseFloat(cantidadI) > parseFloat(cantidadR)) ) {
				error = error + "Error en el renglon: "+(i+1)+" del detalle de la ODC: "+idOrdenCompra+". La Cantidad a ingresar: "+cantidadI+" debe estar entre 1 y "+cantidadR+"\n";
			}
		}
	}
	if(error!=""){
		alert(error);
		return false;
	}else{
		return true;
	}
}

function buscaOrden(){
	var orden = $("#orden").val();
	var documento = $("#documento").val();
	if((orden == "")&&(documento == "")){
		alert("Debe introducir un n\u00famero de orden o documento");
	}
	else{
		$(".ordenes").html("");
		var ruta = "respuestaEntradasAPartirDeOrdenesCompra.php";
		var envio = "idOrden=" + orden + "&documento=" + documento;
		var respuesta = ajaxN(ruta, envio);
		$(".ordenes").html(respuesta);
	}
}

function buscaProveedor(){
	var idProvedor = $("#select-proveedor").find("option:selected").val();
	if(idProvedor == -1){
		alert("Debe elegir un proveedor");
	}else{
		var ruta = "respuestaEntradasAPartirDeOrdenesCompra.php";
		var envio = "proveedor=" + idProvedor;
		var respuesta = ajaxN(ruta, envio);
		$(".ordenes").html(respuesta);
	}
}
		
function guardaOrdenEntrada(idOrden, id_detalles){
	$("#waitingplease").show();
	
	var almacen = $("#select-almacen"+idOrden).find("option:selected").val();
	var observaciones = $("#observaciones"+idOrden).val();	//Quitar
	var factura = $("#fac-prov"+idOrden).val();
	var parciales = $("#parciales"+idOrden).val();	//Quitar
	var pedimento = $("#pedimento"+idOrden).val();
	var pedimentoFecha = $("#pedimento_fec"+idOrden).val();
	var proveedor = $("#proveedor_id"+idOrden).val();	//Quitar?
	var completo = $("#completo"+idOrden).val();
	var id_sucursal = $("#id_sucursal"+idOrden).val();

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
		var requiereNumerosDeSerie = $("#requiereNumerosDeSerie" + idOrden + index).val();
		var requiereValidarTipoProducto = $("#requiereValidarTipoProducto" + idOrden + index).val();
		
		var numeroCarga = $("#numeroCarga" + idOrden + index).val();

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
		productos.push($("#idProducto"+ idOrden + index).val() + "|" + $("#idPrecioUnitario" + idOrden + index).val() + "|" + ingresada + "|" + $("#idDetalleOrden" + idOrden + index).val() + "|" + requerida + "|" + requiereNumerosDeSerie + "|" + numeroCarga + "|" + requiereValidarTipoProducto);
	});
	
	if($("#completo"+idOrden).is(':checked'))
		var estatus = 1;
	else
		var estatus = 0;
			
	if(parciales == 0 && sumaCantidadesIngresadas < sumaCantidadesProductos){
		alert("La orden de compra no permite recibos parciales");
		var confirma = false; $("#waitingplease").hide();
	}
	else if(valCantidades > 0){
		alert(textValCantidades);
		var confirma = false; $("#waitingplease").hide();
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
		var envio = "idOrden=" + idOrden + "&almacen=" + almacen + "&observaciones=" + observaciones + "&factura=" + factura + "&pedimento=" + pedimento + "&pedimentoFecha=" + pedimentoFecha + "&aduana=" + aduana + "&productos=" + productos + "&proveedor=" + proveedor + "&estatus=" + estatus + "&id_sucursal=" + id_sucursal+"&id_detalles_odc="+id_detalles;
		var respuesta = ajaxN(ruta, envio);
		alert(respuesta);
		location.reload();
	}
	
	$("#waitingplease").hide();
}
/********************************* TODO LO RELACIONADO A ENTRADAS A PARTIR DE ORDENES DE COMPRA ***************************/
/**funciones para la autorizacion de pedidos*/
function buscarPedidos(opcion){
		if(opcion=='1'){
			var fecha1=$("#fechadel").val();
			var fecha2=$("#fechaal").val();
			//var FechaInicioConv = convierteFechaJava(fecha1);
			//var FechaFinConv = convierteFechaJava(fecha2);
			if(fecha2<fecha1)
			{ 
				alert("La fecha incial no puede ser mayor a la fecha final");
				return false;
			}


			var FechaInicioConv = cambiarFormatoFecha(fecha1,'ymd','/');
			var FechaFinConv = cambiarFormatoFecha(fecha2,'ymd','/');

			
			var id_cliente=$("#select-cliente").val();
			var datos='cliente='+id_cliente+'&fecha1='+FechaInicioConv+'&fecha2='+FechaFinConv;
		}
		if(opcion=='2'){
			var pedido=$("#pedido").val();
			var datos='pedido='+pedido;
			if(pedido==''){
				alert("Ingrese un Pedido");
				return false;
			}
		}
		$("#scroll-tabla").html('');
		$.ajax({
			method:'POST',
			url:'../../code/ajax/especiales/buscarPedidos.php',
			data:datos,
			success: function(data) {
				$("#scroll-tabla").html(data);
			}
		});
	}
function apruebaPedido(id){
	var confirma=confirm("Esta seguro de querer aprobar este pedido?");
	if(confirma==true){
			var envia_datos = "idPedido=" + id + "&caso=2";
			var url = "apruebaRechazoPedido.php";
			var respuesta = ajaxN(url, envia_datos);
			alert(respuesta);
			location.reload();
			}
	else{
			return false;
		}
}
function rechazaPedido(id){
	var rechazo = $("#motivo-rechazo" + id).val();
	if(rechazo == ""){
			alert("Debes elegir un motivo del rechazo");
			$("#motivo-rechazo" + id).css("border", "1px #F75151 solid");
			}
	else{
		var confirma=confirm("Esta seguro de querer rechazar este pedido?");
		if(confirma==true){
				var envia_datos = "idPedido=" + id + "&caso=3&motivo=" + rechazo;
				var url = "apruebaRechazoPedido.php";
				var respuesta = ajaxN(url, envia_datos);
				alert(respuesta);
				location.reload();
			}
		else{
			return false;
		}
	}
}
function buscarSolicitud(opcion){
		if(opcion=='1'){
			var fecha1=$("#fechadel").val();
			var fecha2=$("#fechaal").val();
			//var FechaInicioConv = convierteFechaJava(fecha1);
			//var FechaFinConv = convierteFechaJava(fecha2);
			if(fecha2<fecha1)
			{ 
				alert("La fecha incial no puede ser mayor a la fecha final");
				return false;
			}
			
			var FechaInicioConv = cambiarFormatoFecha(fecha1,'ymd','/');
			var FechaFinConv = cambiarFormatoFecha(fecha2,'ymd','/');
			var id_empleado=$("#select-empleado").val();
			var datos='empleado='+id_empleado+'&fecha1='+FechaInicioConv+'&fecha2='+FechaFinConv;
		}
		if(opcion=='2'){
			var solicitud=$("#solicitud").val();
			var datos='solicitud='+solicitud;
			if(solicitud==''){
				alert("Ingrese un Pedido");
				return false;
			}
		}
		$("#scroll-tabla").html('');
		$.ajax({
			method:'POST',
			url:'../../code/ajax/especiales/buscarSolicitudesMaterial.php',
			data:datos,
			success: function(data) {
				$("#scroll-tabla").html(data);
			}
		});
	}
function apruebaSolicitud(id){
	var confirma=confirm("Esta seguro de querer aprobar esta solicitud?");
	if(confirma==true){
			var envia_datos = "idSolicitud=" + id + "&caso=2";
			var url = "apruebaRechazoSolicitudMaterial.php";
			var respuesta = ajaxN(url, envia_datos);
			alert(respuesta);
			location.reload();
			}
	else{
			return false;
		}
}
function rechazaSolicitud(id){
	var rechazo = $("#motivo-rechazo" + id).val();
	if(rechazo == ""){
			alert("Debes elegir un motivo del rechazo");
			$("#motivo-rechazo" + id).css("border", "1px #F75151 solid");
			}
	else{
		var confirma=confirm("Esta seguro de querer rechazar esta solicitud?");
		if(confirma==true){
				var envia_datos = "idSolicitud=" + id + "&caso=3&motivo=" + rechazo;
				var url = "apruebaRechazoSolicitudMaterial.php";
				var respuesta = ajaxN(url, envia_datos);
				alert(respuesta);
				location.reload();
			}
		else{
			return false;
		}
	}
}

/********************************* TODO LO RELACIONADO A COSTEOS ***************************/

function verMovimientoEntrada(id_control_movimiento){
	$.fancybox({
		type: 'iframe',
		href: 'verMovimientosDeAlmacen.php?id_control_movimiento='+id_control_movimiento,
		maxWidth	: 900,
		maxHeight	: 800,
		fitToView	: false,
		width		: '80%',
		height		: '80%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'elastic',
		afterClose  : function(){
			//$("#cantidadIAux"+idOrdenCompra+numeroRenglon).val(numeroSerie);
		}
	});
}
	function verOrdenCompra(tipo, numero){
	$.fancybox({
		type: 'iframe',
		href: 'verPantalla.php?tipo='+tipo+'&numero='+numero,
		maxWidth	: 900,
		maxHeight	: 800,
		fitToView	: false,
		width		: '80%',
		height		: '80%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'elastic',
		afterClose  : function(){
			//$("#cantidadIAux"+idOrdenCompra+numeroRenglon).val(numeroSerie);
		}
	});
}
/********************************* TODO LO RELACIONADO A COSTEOS ***************************/

function limpiaCajasAuxiliares(idOrden, contador){
	document.getElementById('cantidadIAux'+idOrden+contador).value = '';
	document.getElementById('numeroCarga'+idOrden+contador).value = '';
}

function validacionCantidadAIngresar(idOrden, renglon, cantidad, validacionTipoEquipo, tipo, idProducto, idDetalleOrdenCompra) {
	var cantidadAIngresar = 0;
	var baseMinimo = 0;
	var baseMaximo = 0;
	var almacen = 0;
	var idLayout = 5; // Es el id del LayOut

	cantidadAIngresar = $("#cantidadI"+idOrden+renglon).val();
	baseMaximo = cantidad;
	almacen = $("#select-almacen"+idOrden).val();
	if(validaCantidadAIngresar(cantidadAIngresar,baseMinimo, 'mayor')){
		if(validaCantidadAIngresar(cantidadAIngresar,baseMaximo,'menor o igual')){
			switch (tipo) {
				case 1: //Importación
					importarNumeroSerie(cantidadAIngresar, idLayout, almacen,idOrden, renglon, validacionTipoEquipo, idDetalleOrdenCompra);
					break;
				case 2: //Captura
					capturarNumeroSerie(idOrden, renglon, idLayout, almacen, cantidadAIngresar, idProducto, idDetalleOrdenCompra);
					break;
			}
		}
		else{$("#cantidadI"+idOrden+renglon).focus();}
	}
	else{$("#cantidadI"+idOrden+renglon).focus();}
}


/******************************** RELACIONADO A ENTRADAS A PARTIR DE ORDENES DE COMPRA ******************************************/
function buscarEntradasDeODC(tipoBusqueda){
	switch (tipoBusqueda){
		case 1: //Cuando el criterio de buesqueda es por Almacen, Proveedor, Fecha de ODC, Fecha de Entrada de Almacen
			var almacenes = []; 
			$('#ListaAlmacenes :selected').each(function(i, selected){ 
			  almacenes[i] = $(selected).val(); 
			});

			var proveedores = $("#select-proveedor").find("option:selected").val();
			var fecha_inicio_odc = $("#fechadel1").val();
			var fecha_fin_odc = $("#fechaal1").val();
			var fecha_inicio_entrada_almacen = $("#fechadel2").val();
			var fecha_fin_entrada_almacen = $("#fechaal2").val();
			var FechaInicioODC = convierteFechaJava(fecha_inicio_odc);
			var FechaFinODC = convierteFechaJava(fecha_fin_odc);
			var FechaInicioEntradaAlmacen = convierteFechaJava(fecha_inicio_entrada_almacen);
			var FechaFinEntradaAlmacen = convierteFechaJava(fecha_fin_entrada_almacen);

			if ( (proveedores==0) && (fecha_inicio_odc=="") && (fecha_inicio_entrada_almacen=="") && (fecha_inicio_entrada_almacen=="") && (fecha_fin_entrada_almacen=="") && (almacenes.length==0) ){
				alert("Debe elegir al menos alg\u00fan criterio de b\u00fasqueda");
				return false;
			}

			if(FechaInicioODC > FechaFinODC){
				alert("La fecha inicial no puede ser mayor a la fecha final en la fecha de orden de compra");
				return false;
			}
			else{
				if(FechaInicioEntradaAlmacen > FechaFinEntradaAlmacen){
					alert("La fecha inicial no puede ser mayor a la fecha final en la fecha de entrada a almacen");
				}else{
					$(".detalle tbody tr").remove();
					var ruta = "llenaTablaDetalle.php";
					var envio = "almacenes=" + almacenes + "&proveedores=" + proveedores + "&fecha_inicio_odc=" + fecha_inicio_odc + "&fecha_fin_odc=" + fecha_fin_odc + "&fecha_inicio_entrada_almacen=" + fecha_inicio_entrada_almacen + "&fecha_fin_entrada_almacen=" + fecha_fin_entrada_almacen + "&proceso=ad_movimientos_almacen";
					var respuesta = ajaxN(ruta, envio);
					$(".detalle tbody").append(respuesta);
				}
			}
		break;
		case 2: //Cuando el criterio de buesqueda es por Orden de Compra o Documento de Cliente
			var idOrdenCompra = $("#TxtOrdenCompra").val();
			var documentoCliente = $("#TxtDocumentoCliente").val();

			if ((idOrdenCompra=="") && (documentoCliente=="")){
				alert('Debe introducir la orden de compra o el documento del cliente');
				return false;
			}
			$(".detalle tbody tr").remove();
			var ruta = "llenaTablaDetalle.php";
			var envio = "idOrdenCompra=" + idOrdenCompra + "&documentoCliente=" + documentoCliente+"&proceso=ad_movimientos_almacen";
			var respuesta = ajaxN(ruta, envio);
			$(".detalle tbody").append(respuesta);
		break;
	}
}
/******************************** RELACIONADO A ENTRADAS A PARTIR DE ORDENES DE COMPRA ******************************************/

/******************************** RELACIONADO A COSTEOS ******************************************/
function generarCosteo(tabla){
	var obj = document.getElementById(tabla);
	var Trs = obj.getElementsByTagName('tr');
	var seleccionado = false;

	//var idDetalleMovimientoAlmacen;
	var idMovimiento;
	var idControlMovimiento;
	var idOrdenCompra;
	var idControlOrdenCompra;
	var idProveedor;

	//var idDetallesMovimientosAlmacen = new Array();
	var idDetalles = new Array();
	var idMovimientos = new Array();
	var idControlMovimientos = new Array();
	var idOrdenesCompra = new Array();
	var idControlOrdenesCompra = new Array();
	var idProveedores = new Array();
	var bandera = false;
	var renglonesSeleccionados = 0;

	// VALIDACIONES --->
	for(var i=1; i<Trs.length+1; i++){
		seleccionado = $('#idDetalleCheck_'+i).is(':checked');
		if(seleccionado) break;
	}
	if(seleccionado){
		for(var i=1; i<Trs.length+1; i++){
			seleccionado = $('#idDetalleCheck_'+i).is(':checked');
			if(seleccionado){
				//idDetalleMovimientoAlmacen = $("#TxtIdDetalleMovimientoAlmacen"+i).val();
				idMovimiento = $("#TxtIdMovimiento"+i).val();
				idControlMovimiento = $("#TxtIdControlMovimiento"+i).val();
				idOrdenCompra = $("#TxtIdOrdenCompra"+i).val();
				idControlOrdenCompra = $("#TxtIdControlOrdenCompra"+i).val();
				idProveedor = $("#TxtIdProveedor"+i).val();
				
				//idDetallesMovimientosAlmacen.push(idDetalleMovimientoAlmacen);
				idMovimientos.push(idMovimiento);
				idControlMovimientos.push(idControlMovimiento);
				idOrdenesCompra.push(idOrdenCompra);
				idControlOrdenesCompra.push(idControlOrdenCompra);
				idProveedores.push(idProveedor);

				renglonesSeleccionados++;
			}
		}
	}else{
		alert("Seleccione alg\u00fan movimiento de almacen");
		bandera = true;
	}
	// VALIDACIONES <---

	if(!bandera){
		//alert(idMovimientos+" : "+idControlMovimientos);
		var url = "generaCosteo.php";
		//var envia_datos = "renglonesSeleccionados="+renglonesSeleccionados+"&idDetallesMovimientosAlmacen="+idDetallesMovimientosAlmacen+"&idMovimientos="+idMovimientos+"&idControlMovimientos="+idControlMovimientos+"&idOrdenesCompra="+idOrdenesCompra+"&idControlOrdenesCompra="+idControlOrdenesCompra+"&idProveedores="+idProveedores;
		//var envia_datos = "renglonesSeleccionados="+renglonesSeleccionados+"&idMovimientos="+idMovimientos+"&idControlMovimientos="+idControlMovimientos+"&idOrdenesCompra="+idOrdenesCompra+"&idControlOrdenesCompra="+idControlOrdenesCompra+"&idProveedores="+idProveedores;
		var envia_datos = "idMovimientos="+idMovimientos+"&idControlMovimientos="+idControlMovimientos+"&idOrdenesCompra="+idOrdenesCompra+"&idControlOrdenesCompra="+idControlOrdenesCompra+"&idProveedores="+idProveedores;
		var respuesta = ajaxN(url, envia_datos);
		alert(respuesta);
		location.reload();
	}
}
/******************************** RELACIONADO A COSTEOS ******************************************/
/*
function muestraMensaje(){
	/*
		$('#Body_detalleMovimientosAlmacen tr').each(function(index){
			var Cadenafila = $(this).attr("id");
			var fila = Cadenafila.split("Fila");
			var existencia = parseInt($(this).children().filter("[id^=detalleMovimientosAlmacen_10_]").attr("valor"));
			var cantidad = parseInt($(this).children().filter("[id^=detalleMovimientosAlmacen_11_]").attr("valor"));
		});
	*v/
	alert('hola');
//      var id = b.parentNode.colIndex;
//      alert(id);
    }

}
*/
/****************** OTROS ********************/
function agregarALista(valor, numeroDeRengistros){
//Funcion que agrega a cuadro lista, los ird o numeros de serie que se van a capturar
//valor: Es el valor que se intruduce
//numeroDeRengistros: es el numero de ird's o numeros de serie que se van a introducir
	/*
	if(numeroDeRengistros == -1){ 
		var gridAlmacen="detalleMovimientosAlmacen";
		$('#Body_detalleMovimientosAlmacen tr').each(function(index){
			var Cadenafila = $(this).attr("id");
			var fila = Cadenafila.split("Fila");
			var existencia = parseInt($(this).children().filter("[id^=detalleMovimientosAlmacen_10_]").attr("valor"));
			var cantidad = parseInt($(this).children().filter("[id^=detalleMovimientosAlmacen_11_]").attr("valor"));
		});
		*/
		/*
		var existencia = celdaValorXY(gridAlmacen,10,1);
		var cantidad = celdaValorXY(gridAlmacen,11,1);
	}
//	alert(existencia+':'+cantidad);
//	alert(Cadenafila+':'+fila+':'+existencia+':'+cantidad);
//	alert("numeroDeRengistros: "+numeroDeRengistros);
	$numeroDeRengistros = cantidad;
	*/
	var num_serie = "";
	var arreglo_numeros_serie = new Array();
	if(valor==""){alert('Debe introducir un valor');
		document.getElementById('TxtValor').focus();
	}else{
		var cadena = "";
		var consecutivo = 0;
		var i = 0;
		for(i=0; i<numeroDeRengistros; i++){
			var caja = document.getElementById('TxtValorLista'+i);
			document.getElementById('TxtContador').value = i+1;
			if(caja==null){
				consecutivo = i;
				break;
			}
		}

		for(j=0; j<numeroDeRengistros; j++){
			var auxiliar = document.getElementById('TxtValorLista'+j);
			if(auxiliar!=null){
				num_serie = $('#TxtValorLista'+j).html();
				arreglo_numeros_serie.push(num_serie);
			}
		}

		if(i<numeroDeRengistros){
			var posicion = arreglo_numeros_serie.indexOf(valor.toUpperCase());
			if( posicion < 0 ){
				cadena = cadena + '<div class="div_numero_serie" id="div_numero_serie'+consecutivo+'">';
				cadena = cadena + '<span class="texto_numeros_serie" name="TxtValorLista'+consecutivo+'" id="TxtValorLista'+consecutivo+'" align="center">'+valor.toUpperCase()+'</span>&nbsp;';
				cadena = cadena + '<a class="close" name="BtnEliminar'+consecutivo+'" id="BtnEliminar'+consecutivo+'" onClick="eliminarDeLista('+consecutivo+','+numeroDeRengistros+');"><img src="http://localhost/sistema_base/imagenes/x2.png" width="10" height="10"></a>';
				cadena = cadena + '</div>';
				$('#lista_datos').append(cadena); 
				document.getElementById('TxtContador').value = arreglo_numeros_serie.length+1;
				var series=document.getElementById('numeros_serie').value;
				if(series=='')
					series=valor;
				else
					series=series+','+valor;
				document.getElementById('numeros_serie').value=series;
			}else {
				alert('El n\u00famero de serie [ '+arreglo_numeros_serie[posicion]+' ] ya existe en la lista');
				document.getElementById('TxtContador').value = arreglo_numeros_serie.length;
			}
		}else{
			//if(numeroDeRengistros!=-1){
				alert('No puede agregar mas registros');
				document.getElementById('TxtContador').value = arreglo_numeros_serie.length;
			//}
		}
		
		document.getElementById('TxtValor').value = '';
		document.getElementById('TxtValor').focus();
	}
	return false;
}


function eliminarDeLista(renglon, numeroDeRengistros){
	//renglo: Es el numero de renglon que se eliminaria de la lista
	//numeroDeRengistros: Es el numero de renglones que se van capturar. Aqui lo uso para saber el tañamo del arreglo y mostrar el numero de capturas
	var series=$("#numeros_serie").val();
	var ar_series=series.split(',');
	var serie=$('#TxtValorLista'+renglon).html(); 
	for(j=0;j<ar_series.length;j++){
		if(serie==ar_series[j]){
			ar_series.splice(j,1);
		}
	}
	$("#numeros_serie").val(ar_series);
	var arreglo_numeros_serie = new Array();
	var id_error = $('#TxtValorLista'+renglon).html();

	$('#TxtValorLista'+renglon).remove(); 
	$('#BtnEliminar'+renglon).remove();
	$('#div_numero_serie'+renglon).remove();
	$('#renglon_error_'+id_error).remove();

	for(j=0; j<numeroDeRengistros; j++){
		var auxiliar = document.getElementById('TxtValorLista'+j);
		if(auxiliar!=null){
			num_serie = $('#TxtValorLista'+j).html();
			arreglo_numeros_serie.push(num_serie);
		}
	}
	document.getElementById('TxtContador').value = arreglo_numeros_serie.length;
	document.getElementById('TxtValor').value = '';
	document.getElementById('TxtValor').focus();
	return false;
}
/****************** OTROS ********************/
function buscaIRDs(){
	var ird = $("#TxtIRD").val();
	if(ird == ""){
		alert("Debe introducir un IRD");
		return false;
	}
/*
	$("#lista_irds").empty();
	$("#lista_historial_irds").empty();
	$('#lista_estatus_irds').empty();
	var ruta = "muestraIRDs.php";
	var envio = "ird=" + ird;
	var respuesta = ajaxN(ruta, envio);
	$("#lista_irds").append(respuesta);
*/

	$("#display_ird_historial").html('&nbsp;HISTORIAL');
	$("#display_ird_estatus").html('&nbsp;ESTATUS');
	$("#lista_historial_irds").html('');
	$("#lista_estatus_irds").html('');
	var $contenidoAjax = $('div#lista_irds').html('<br/><p align="center"><img src="../../imagenes/general/wait.gif" width="20"/></p>');
	$.ajax({ 
		method: "GET",
		url: "../ajax/muestraIRDs.php",
		data:{ird:ird},
		dataType:"json",
		success: function(data) { // Aquí desaparece la imagen ya que estamos reemplazando todo el HTML del contenido de la div. 
			$contenidoAjax.html(data);
		}
	})
	.done(function(data){
		if(data!=null){}
	});

}

function muestraHistorialIRD(id_control_serie, numero_serie){
	/*
	$("#lista_historial_irds").empty();
	$('#lista_estatus_irds').empty();
	var ruta = "muestraHistorialIRDs.php";
	var envio = "id_control_serie=" + id_control_serie;
	var respuesta = ajaxN(ruta, envio);
	$("#lista_historial_irds").append(respuesta);
	*/
	$("#display_ird_historial").html('&nbsp;HISTORIAL');
	$("#display_ird_estatus").html('&nbsp;ESTATUS');
	var $contenidoAjax = $('div#lista_historial_irds').html('<br/><p align="center"><img src="../../imagenes/general/wait.gif" width="20"/></p>');
	$.ajax({ 
		method: "GET",
		url: "../ajax/muestraHistorialIRDs.php",
		data:{id_control_serie:id_control_serie},
		dataType:"json",
		success: function(data) { // Aquí desaparece la imagen ya que estamos reemplazando todo el HTML del contenido de la div. 
			$contenidoAjax.html(data);
			$("#display_ird_historial").html('&nbsp;HISTORIAL : '+numero_serie);
		}
	})
	.done(function(data){
		if(data!=null){}
	});
}

function muestraEstatusIRD(id_control_serie, numero_serie){
/*
	$("#lista_estatus_irds").empty();
	var ruta = "muestraEstatusIRDs.php";
	var envio = "id_control_serie=" + id_control_serie;
	var respuesta = ajaxN(ruta, envio);
	$("#lista_estatus_irds").append(respuesta);
*/
	var $contenidoAjax = $('div#lista_estatus_irds').html('<br/><p align="center"><img src="../../imagenes/general/wait.gif" width="20"/></p>');
	$.ajax({ 
		method: "GET",
		url: "../ajax/muestraEstatusIRDs.php",
		data:{id_control_serie:id_control_serie},
		dataType:"json",
		success: function(data) { // Aquí desaparece la imagen ya que estamos reemplazando todo el HTML del contenido de la div. 
			$contenidoAjax.html(data);
			$("#display_ird_estatus").html('&nbsp;ESTATUS : '+numero_serie);
		}
	})
	.done(function(data){
		if(data!=null){}
	});
}

function buscaContratos(contrato, cuenta, ird){
	/*
	var contrato = $("#TxtContrato").val();
	var cuenta = $("#TxtCuenta").val();
	var ird = $("#TxtIRD").val();
	*/
	if((contrato=="")&&(cuenta=="")&&(ird=="")){
		alert("Debe introducir alg\u00fan valor");
		$("#TxtContrato").focus();
		return false;
	}
	$("#display_historial_contrato").html('&nbsp;HISTORIAL');
	$("#display_irds_contrato").html('&nbsp;IRDS ASIGNADOS');
	$("#lista_historial_contratos_pie").html('');
	var $contenidoAjax = $('div#lista_contratos').html('<br/><p align="center"><img src="../../imagenes/general/wait.gif" width="20"/></p>');
	$("#lista_historial_contratos").empty();
	$("#lista_irds_asignados").empty();
	$("#editor").empty();
	$.ajax({ 
		method: "GET",
		url: "../ajax/muestraContratos.php",
		data:{contrato:contrato,cuenta:cuenta,ird:ird},
		dataType:"json",
		success: function(data) { // Aquí desaparece la imagen ya que estamos reemplazando todo el HTML del contenido de la div. 
			$contenidoAjax.html(data);
		}
	})
	.done(function(data){
		if(data!=null){
			$("#lista_historial_contratos").empty();
			$("#lista_irds_asignados").empty();
			$("#editor").empty();
		}
	});
}

function muestraHistorialContrato(id_control_contrato, cuenta, fecha_activacion, contrato){
	$("#editor").empty();
	$("#lista_historial_contratos_pie").html('');
	$("#editor_encabezados").html('');
	var $contenidoAjax = $('div#lista_historial_contratos').html('<br/><p align="center"><img src="../../imagenes/general/wait.gif" width="20"/></p>');
	$.ajax({ 
		method: "GET",
		url: "../ajax/muestraHistorialContrato.php",
		data:{id_control_contrato:id_control_contrato},
		dataType:"json",
		success: function(data) { // Aquí desaparece la imagen ya que estamos reemplazando todo el HTML del contenido de la div. 
			$contenidoAjax.html(data);
			$("#display_historial_contrato").html('&nbsp;HISTORIAL : '+contrato);
		}
	})
	.done(function(data){
		if(data!=null){}
	});
}

function marcarTR(numtr){
	var idTR='contraNumTR'+(numtr - 1);
	$('#ResulBusqListContratos tr').each(function () {
		if($(this).attr("id") == idTR){
			$(this).attr('style', 'background-color: #CCC;');
		} else {
			$(this).attr('style', '');
		}
    });
}

function muestraLinkParaAgregarTipoMovimiento(id, cuenta, fecha_Activacion, contrato){
	var $contenidoAjax = $('div#lista_historial_contratos_pie').html('<a hfer="#" class="letra_encabezado" onclick="agregarTipoMovimientoDeContrato('+id+',\''+contrato+'\');">&nbsp;Agregar Tipo de Movimiento</a>');
}

function muestraIRDsAsignados(id, contrato){
	/*
	$("#lista_irds_asignados").empty();
	var ruta = "muestraIRDsAsignados.php";
	var envio = "id_detalle=" + id;
	var respuesta = ajaxN(ruta, envio);
	$("#lista_irds_asignados").append(respuesta);
	*/
	$("#display_historial_contrato").html('&nbsp;HISTORIAL');
	$("#display_irds_contrato").html('&nbsp;IRDS ASIGNADOS');
	var $contenidoAjax = $('div#lista_irds_asignados').html('<br/><p align="center"><img src="../../imagenes/general/wait.gif" width="20"/></p>');
	$.ajax({ 
		method: "GET",
		url: "../ajax/muestraIRDsAsignados.php",
		data:{id_detalle:id},
		dataType:"json",
		success: function(data) { // Aquí desaparece la imagen ya que estamos reemplazando todo el HTML del contenido de la div. 
			$contenidoAjax.html(data);
			$("#display_irds_contrato").html('&nbsp;IRDS ASIGNADOS : '+contrato);
		}
	})
	.done(function(data){
		if(data!=null){}
	});
}

function guardarDetalleContrato(id_control_contrato){
	var accion = $("#CmbTipoAccion").find("option:selected").val();
	var producto = $("#CmbProductos").find("option:selected").val();
	var monto = $("#TxtMonto").val();
	//Validaciones -->	
	if(accion=="-1"){
		alert("Debe elegir la acci\u00f3n");
		$("#CmbTipoAccion").focus();
		return false;
	}
	if(producto=="-1"){
		alert("Debe elegir un producto");
		$("#CmbProductos").focus();
		return false;
	}
	if(monto==""){
		alert("Debe intriducir el monto");
		$("#TxtMonto").focus();
		return false;
	}
	if(monto<=0){
		alert("El monto debe ser un n\u00famero positivo");
		$("#TxtMonto").focus();
		return false;
	}
	if( isNaN(monto) ) {
		alert("El monto debe ser un valor num\u00e9rico v\u00e1lido");
		$("#TxtMonto").focus();
		return false;
	}
	//Validaciones <--
	
	var id_control_contrato = $("#TxtIdControlContrato").val();
	var ruta = "guardarDetalleContrato.php";
	var envio = "id_control_contrato="+id_control_contrato+"&producto="+producto+"&monto="+monto+"&accion="+accion;
	var respuesta = ajaxN(ruta, envio);
	//$("#lista_historial_contratos").append(respuesta);
	$("#CmbTipoAccion").val(-1);
	$("#CmbProductos").val(-1);
	$("#TxtMonto").val('');
	$("#editor").empty();
	alert('La informaci\u00f3n se registr\u00f3 correctamente');
	muestraHistorialContrato(id_control_contrato,'','','');
}

function buscaIRDsParaAsignacion(){
	var idSucursal = $("#CmbPlazas").find("option:selected").val();
	if (idSucursal == '-1'){
		alert("Debe elegir una plaza");
		$("#CmbPlazas").focus();
		return false;
	}
	buscaIRDsFisicosSinAsignacion(idSucursal);
	buscaIRDsEnPipeline(idSucursal);
	buscaIRDsEnPipelineSinExistenciaFisica();
}

function buscaIRDsFisicosSinAsignacion(idSucursal){
	/*
	$("#lista_irds_fisicos_sin_pipeline").empty();
	$("#lista_irds_en_pipeline").empty();
	var ruta = "muestraIRDsSinPipeline.php";
	var envio = "idSucursal="+idSucursal;
	var respuesta = ajaxN(ruta, envio);
	$("#lista_irds_fisicos_sin_pipeline").append(respuesta);
	*/
	var $contenidoAjax = $('div#lista_irds_fisicos_sin_pipeline').html('<br/><p align="center"><img src="../../imagenes/general/wait.gif" width="20"/></p>');
	$.ajax({ 
		method: "GET",
		url: "../ajax/muestraIRDsSinPipeline.php",
		data:{idSucursal:idSucursal},
		dataType:"json",
		success: function(data) { // Aquí desaparece la imagen ya que estamos reemplazando todo el HTML del contenido de la div. 
			$contenidoAjax.html(data);
		}
	})
	.done(function(data){
		if(data!=null){}
	});
}


function buscaIRDsEnPipeline(idSucursal){
	/*
	$("#lista_irds_en_pipeline").empty();
	var ruta = "muestraIRDsEnPipeline.php";
	var envio = "idSucursal="+idSucursal;
	var respuesta = ajaxN(ruta, envio);
	$("#lista_irds_en_pipeline").append(respuesta);
	*/
	var $contenidoAjax = $('div#lista_irds_en_pipeline').html('<br/><p align="center"><img src="../../imagenes/general/wait.gif" width="20"/></p>');
	$.ajax({ 
		method: "GET",
		url: "../ajax/muestraIRDsEnPipeline.php",
		data:{idSucursal:idSucursal,opcion:'pipeSinAsignar'},
		dataType:"json",
		success: function(data) { // Aquí desaparece la imagen ya que estamos reemplazando todo el HTML del contenido de la div. 
			$contenidoAjax.html(data);
		}
	})
	.done(function(data){
		if(data!=null){}
	});
}

function buscaIRDsEnPipelineSinExistenciaFisica(){
	var $contenidoAjax = $('div#lista_irds_en_pipeline_sin_existencia_fisica').html('<br/><p align="center"><img src="../../imagenes/general/wait.gif" width="20"/></p>');
	$.ajax({ 
		method: "GET",
		url: "../ajax/muestraIRDsEnPipeline.php",
		data:{opcion:'pipeSinExistencia'},
		dataType:"json",
		success: function(data) { // Aquí desaparece la imagen ya que estamos reemplazando todo el HTML del contenido de la div. 
			$contenidoAjax.html(data);
		}
	})
	.done(function(data){
		if(data!=null){}
	});
}

function asignaPipelineAFisicos(){
	var idSucursal = $("#CmbPlazas").find("option:selected").val();
	if (idSucursal == '-1'){
		alert("Debe hacer una busqueda");
		$("#CmbPlazas").focus();
		return false;
	}
	/**************************************************************/
	var campo1;
	$("#irds_sin_pipeline tbody tr").each(function (index){
		$(this).children("td").each(function (index2) 
		{
			campo1 = $(this).text();
			//$(this).css("background-color", "#ECF8E0");
		})
		//alert(campo1);
	})
	if (campo1 == "No existen IRD's a mostrar") {
		alert("Debe existir IRD's f\u00edsicos, para hacer la asignaci\u00f3n");
		$("#button").focus();
		return false;
	}

	/**************************************************************/
	var campo2;
	$("#irds_en_pipeline tbody tr").each(function (index){
		$(this).children("td").each(function (index2) 
		{
			campo2 = $(this).text();
			//$(this).css("background-color", "#ECF8E0");
		})
		//alert(campo2);
	})
	if (campo2 == "No existen IRD's a mostrar") {
		alert("Debe existir IRD's en Pipeline, para ser asignados");
		$("#button").focus();
		return false;
	}
	/*
	var numero;
	var ird;
	var fpipe;
	var hpipe;
	var estatus;
	var arreglo_irds = new Array();
	$("#irds_en_pipeline tbody tr").each(function (index){
		$(this).children("td").each(function (index2) 
		{
			switch (index2) 
			{
				case 0: numero = $(this).text(); break;
				case 1: ird = $(this).text(); break;
				case 2: fpipe = $(this).text(); break;
				case 3: hpipe = $(this).text(); break;
				case 4: estatus = $(this).text(); break;
			}
			if((index2==4) && (estatus == 'PENDIENTE POR ASIGNAR')){
				//alert('index1: '+index+' index2: '+index2 + ' numero: ' + numero + ' ird: ' + ird + ' fpipe: ' + fpipe + ' hpipe: ' + hpipe + ' estatus: ' + estatus);
				arreglo_irds.push(ird);
			}
		})
	})
	*/

	var $contenidoAjax = $('div#lista_irds_en_pipeline').html('<br/><p align="center"><img src="../../imagenes/general/wait.gif" width="20"/></p>');
	$.ajax({ 
		method: "GET",
		url: "../ajax/asignaIRDsFisicoConPipeline.php",
		//data:{arreglo_irds:arreglo_irds,idSucursal:idSucursal},
		data:{idSucursal:idSucursal},
		dataType:"json",
		success: function(data) { // Aquí desaparece la imagen ya que estamos reemplazando todo el HTML del contenido de la div. 
			alert("La(s) asignacion(es) de IRD's Pipeline con IRD's F\u00edsicos se realizaron correctamente");
			$contenidoAjax.html(data);
			buscaIRDsFisicosSinAsignacion(idSucursal);
			buscaIRDsEnPipeline(idSucursal);
			buscaIRDsEnPipelineSinExistenciaFisica();
		}
	})
	.done(function(data){
		if(data!=null){}
	});

}

function saltaABoton(evento, formulario){
	var k=null;
	(evento.keyCode) ? k=evento.keyCode : k=evento.which;
	if(k==13) {
		document.forms[formulario].button.focus();
		return true;
	}
}

/*******************************/
function editarNumeroContrato(id_control_contrato){
	$("#editor_encabezados").html('&nbsp;SUSTITUIR N&Uacute;MERO DE CONTRATO');
	var $contenidoAjax = $('div#editor').html('<br/><p align="center"><img src="../../imagenes/general/wait.gif" width="20"/></p>');
	$.ajax({ 
		method: "GET",
		url: "../ajax/editarNumeroContrato.php",
		data:{id_control_contrato:id_control_contrato},
		dataType:"json",
		success: function(data) { // Aquí desaparece la imagen ya que estamos reemplazando todo el HTML del contenido de la div. 
			//alert("La informaci\u00f3n se actualiz\u00f3 conrrectamente");
			$contenidoAjax.html(data);
		}
	})
	.done(function(data){
		if(data!=null){
		}
	});
}
function sustituirNumeroContrato(){
	var contrato = $("#TxtContratoModificar").val();
	var contrato_auxiliar = $("#TxtContratoModificarAux").val();
	var id_control_contrato = $("#TxtIdControlContrato").val();
	
	var cuenta = $("#TxtCuenta").val();
	var fecha_activacion = $("#TxtFechaActivacion").val();
	//var contratoAux;
	
	var accion = '1';	//Sustituir numero de contratos
	if(contrato==""){
		alert('Debe introducir un n\u00famero de contrato');
		document.getElementById('TxtContratoModificar').focus();
		return false;
	}
	if(contrato==contrato_auxiliar){
		alert('El n\u00famero de contrato, no ha cambiado. Por favor introduzca un numero nuevo');
		document.getElementById('TxtContratoModificar').focus();
		return false;
	}

	//cuenta,fecha_Activacion,contrato
	$.ajax({
		method: "GET",
		url: "../ajax/validaExisteNumeroContrato.php",
		data:{cuenta:cuenta,fecha_activacion:fecha_activacion,contrato:contrato},
		dataType:"json",
		success: function(data) { // Aquí desaparece la imagen ya que estamos reemplazando todo el HTML del contenido de la div. 
			if(data!='1'){

				var $contenidoAjax = $('div#editor').html('<br/><p align="center"><img src="../../imagenes/general/wait.gif" width="20"/></p>');
				$.ajax({ 
					method: "GET",
					url: "../ajax/actualizarInformacionDeContratos.php",
					data:{accion:accion,id_control_contrato:id_control_contrato,contrato:contrato},
					dataType:"json",
					success: function(data) { // Aquí desaparece la imagen ya que estamos reemplazando todo el HTML del contenido de la div. 
						$contenidoAjax.html(data);
						setTimeout ('buscaContratos("'+contrato+'", "", "")', 3500); 
					}
				})
				.done(function(data){
					if(data!=null){if(data!='1'){$("#editor_encabezados").html('');}}
				});

			}else{alert('No se puede realizar el cambio del n\u00famero de contrato ya que existe uno registro igual con el mismo n\u00famero de cuenta y fecha de activaci\u00f3n');}
		}
	})
	.done(function(data){
		if(data!=null){}
	});



}


function agregarTipoMovimientoDeContrato(id, contrato){
	var $contenidoAjax = $('div#editor').html('<br/><p align="center"><img src="../../imagenes/general/wait.gif" width="20"/></p>');
	$.ajax({ 
		method: "GET",
		url: "../ajax/agregarTipoMovimientoDeContrato.php",
		data:{id_control_contrato:id},
		dataType:"json",
		success: function(data) { // Aquí desaparece la imagen ya que estamos reemplazando todo el HTML del contenido de la div. 
			$contenidoAjax.html(data);
			$("#editor_encabezados").html('&nbsp;AGREGAR TIPO DE MOVIMIENTO AL CONTRATO : '+contrato);
		}
	})
	.done(function(data){
		if(data!=null){}
	});
}

function editarHistorialContrato(id_control_contrato, id_control_contrato_detalle, accion, contrato){
	 $("#editor_encabezados").html('');
	var $contenidoAjax = $('div#editor').html('<br/><p align="center"><img src="../../imagenes/general/wait.gif" width="20"/></p>');
	$.ajax({ 
		method: "GET",
		url: "../ajax/editarHistorialContrato.php",
		data:{accion:accion,id_control_contrato:id_control_contrato,id_control_contrato_detalle:id_control_contrato_detalle},
		dataType:"json",
		success: function(data) { // Aquí desaparece la imagen ya que estamos reemplazando todo el HTML del contenido de la div. 
			$contenidoAjax.html(data);
			switch(accion){
				case '6': $("#editor_encabezados").html('&nbsp;MODIFICAR PROMOCI&Oacute;N DEL CONTRATO: '+contrato); break;
				case '5': $("#editor_encabezados").html('&nbsp;MODIFICAR NIT DEL CONTRATO: '+contrato); break;
				case '4': $("#editor_encabezados").html('&nbsp;MODIFICAR PRECIO DE SUSCRIPCI&Oacute;N DEL CONTRATO: '+contrato); break;
				case '3': $("#editor_encabezados").html('&nbsp;MODIFICAR FECHA DE MOVIMIENTO DEL CONTRATO: '+contrato); break;
			}
		}
	})
	.done(function(data){
		if(data!=null){}
	});
}


function actualizarInformacionDeContratos(id_control_contrato, id_control_contrato_detalle, accion, campo_modificar, contrato){
	//accion puede tomar los siguientes valores:
	//1 - Sustituir numero de contratos
	//2 - Eliminar el detalle del contrato
	//3 - Modificar fecha del movimiento
	//4 - Modificar Precio de suscripcion
	//5 - Modificar NIT
	//6 - Modificar Promoción
	//    ...
	//    ...
	//Validaciones -->
	if((campo_modificar=="")||(campo_modificar=="-1") ||(campo_modificar=="0")){
		alert('Debe ingresar alg\u00fan valor');
		return false;	
	}
	if(accion == 3){
		//Validacion fechas
		var f = new Date();
		var fecha_fin = (f.getDate() + "/" + (f.getMonth() +1) + "/" + f.getFullYear());
		var fecha_inicio = campo_modificar;
		//var FechaInicioConv = convierteFechaJava(fecha_inicio);
		//var FechaFinConv = convierteFechaJava(fecha_fin);
		if(fecha_inicio > fecha_fin){
			alert("La fecha no puede ser superior a la del dia actual");
			return false;
		}
	} else if(accion == 4){
		if(campo_modificar<=0){
			alert("El precio debe ser mayor a 0");
			$("#TxtPrecioSuscripcion").focus();
			return false;
		}
		if( isNaN(campo_modificar) ) {
			alert("El precio debe ser un valor num\u00e9rico v\u00e1lido");
			$("#TxtPrecioSuscripcion").focus();
			return false;
		}
	}
	//Validaciones <--
	var cuenta = $("#TxtCuenta").val();
	var fecha_activacion = $("#TxtFechaActivacion").val();
	
	if(accion == 2){
		if(confirm('Esta seguro de que desea eliminar este registro?')){
			var $contenidoAjax = $('div#editor').html('<br/><p align="center"><img src="../../imagenes/general/wait.gif" width="20"/></p>');
			$.ajax({ 
				method: "GET",
				url: "../ajax/actualizarInformacionDeContratos.php",
				data:{accion:accion,id_control_contrato:id_control_contrato,id_control_contrato_detalle:id_control_contrato_detalle},
				dataType:"json",
				success: function(data) { // Aquí desaparece la imagen ya que estamos reemplazando todo el HTML del contenido de la div. 
					$contenidoAjax.html(data);
					setTimeout ('muestraHistorialContrato("'+id_control_contrato+'", "'+cuenta+'", "'+fecha_activacion+'","")', 3500); 
				}
			})
			.done(function(data){
				if(data!=null){if(data!='1'){$("#editor_encabezados").html('');}}
			});
		}

	}
	else{
	/*	
		$.ajax({
			method: "GET",
			url: "../ajax/validaExisteNumeroContrato.php",
			data:{cuenta:cuenta,fecha_activacion:fecha_activacion,contrato:contrato},
			dataType:"json",
			success: function(data) { // Aquí desaparece la imagen ya que estamos reemplazando todo el HTML del contenido de la div. 
				if(data!='1'){
	*/	
					var $contenidoAjax = $('div#editor').html('<br/><p align="center"><img src="../../imagenes/general/wait.gif" width="20"/></p>');
					$.ajax({ 
						method: "GET",
						url: "../ajax/actualizarInformacionDeContratos.php",
						data:{accion:accion,id_control_contrato:id_control_contrato,id_control_contrato_detalle:id_control_contrato_detalle,campo_modificar:campo_modificar},
						dataType:"json",
						success: function(data) { // Aquí desaparece la imagen ya que estamos reemplazando todo el HTML del contenido de la div. 
							$contenidoAjax.html(data);
							setTimeout ('muestraHistorialContrato("'+id_control_contrato+'", "", "")', 3500); 
						}
					})
					.done(function(data){
						if(data!=null){if(data!='1'){$("#editor_encabezados").html('');}}
					});
	/*			
				}else{alert('No se puede eliminar el registro, por que el contrare3cibo = 111');}
			}
		})
		.done(function(data){
			if(data!=null){}
		});
	*/			
	}
}
/*******************************/

function limpiaEditor(){
	$("#editor").empty();
}


function validaCantidadIgresar(cantidadIngresar, cantidadSolicitada, cantidadRecibida, idOrdenCompra, renglon){
	if(cantidadIngresar>(cantidadSolicitada-cantidadRecibida)){
		alert('La cantidad a ingresar no puede ser mayor a '+(cantidadSolicitada-cantidadRecibida));
		$("#cantidadI"+idOrdenCompra+renglon).val('');
		$("#cantidadI"+idOrdenCompra+renglon).focus();
		return false;
	}else{return true;}
}

function validarFileImportar(f){
	var file = f.archivo.value;
	var pos = file.lastIndexOf('.');
	var extension = file.substring(pos+1);
	if (file!=='') {
		if (extension === 'csv' || extension === 'CSV') {
		}else{
			alert('El formato debe ser CSV');	
			retVal = false;
		}
	}else{
		retVal = false;
	}
	return retVal;
}



//Surtir pedidos

function verPedido(idControlPedido){
	$.fancybox({
		type: 'iframe',
		href: '../general/encabezados.php?t=YWRfcGVkaWRvcw==&k='+idControlPedido+'&op=2&v=1&tabla=&cadP1=MDI0ZG5CbGZqRjhibkJsZmpGOGJYQmxmakY4MQ==&cadP2=MDI0WlhCbGZqRjhhWEJsZmpGOFozQmxmakU9MQ==&hf=10',
		maxWidth	: 1050,
		maxHeight	: 800,
		fitToView	: false,
		width		: '90%',
		height		: '80%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'elastic',
		afterClose  : function(){
		}
	});
	return false;
}


function generaSalida(total){
	//VALIDACIONES --->
	var f=document.forma1;	
	//Para pedidos -->
	var arrayDetIdDetPedidos = new Array();
	var arrayDetIdProductos = new Array();
	var arrayDetCantidad = new Array();
	var arrayListadoPedidos = new Array();
	var arrayControlPedidos = new Array();
	var arrayAlmacen = new Array();
	//Para pedidos <--
	//Para solicitud de material -->
	var arrayDetIdDetPedidosSM = new Array();
	var arrayDetIdProductosSM = new Array();
	var arrayDetCantidadSM = new Array();
	var arrayListadoPedidosSM = new Array();
	var arrayControlPedidosSM = new Array();
	var arrayAlmacenSM = new Array();
	//Para solicitud de material <--

	var check = 0;
	var elemento;
	var cantidad_surtir;
	var validacion = 0;
	var tipo;
	
	for(i=1; i<total; i++){
		elemento = document.getElementById('check_'+i);
		cantidad_surtir = document.getElementById('cantidadI'+i).value;
		if(elemento){
			if(elemento.checked){
				check++;
			}
			if(elemento.checked && cantidad_surtir == ""){
				validacion = 1;
			}
		}
	}
	
	if(check == 0){
		alert("Para generar un pedido, es requerido seleccionar un producto y escribir la cantidad a surtir.");
		return false;
	}
	if(validacion == 1){
		alert("Es necesario que inserte la cantidad en la casilla de verificaci\u00f3n elegida");
		return false;
	}
	//VALIDACIONES <---
	
	for(i=1; i<total; i++){
		elemento = document.getElementById('check_'+i);
		cantidad_surtir = document.getElementById('cantidadI'+i).value;
		if(elemento)
		{
			if(elemento.checked)
			{
				if(cantidad_surtir!=""){
					tipo = $("#tipo_"+i).val();
					switch (tipo){
					case 'P':
						arrayDetIdDetPedidos.push( $("#id_detalle_"+i).val() );
						arrayDetIdProductos.push( $("#idProd_"+i).val() );
						arrayDetCantidad.push( $("#cantidadI"+i).val() ); //Cantidad a Surtir
						arrayListadoPedidos.push( $("#id_pedido_"+i).val() ); //Pedidos
						arrayControlPedidos.push( $("#id_control_pedido_"+i).val() ); //Id Control Pedido
						arrayAlmacen.push( $("#idAlmacen_"+i).val() );	//Id del almacen
					break;
					case 'S':
						arrayDetIdDetPedidosSM.push( $("#id_detalle_"+i).val() );
						arrayDetIdProductosSM.push( $("#idProd_"+i).val() );
						arrayDetCantidadSM.push( $("#cantidadI"+i).val() ); //Cantidad a Surtir
						arrayListadoPedidosSM.push( $("#id_pedido_"+i).val() ); //Pedidos
						arrayControlPedidosSM.push( $("#id_control_pedido_"+i).val() ); //Id Control Pedido
						arrayAlmacenSM.push( $("#idAlmacen_"+i).val() );	//Id del almacen
					break;
					}
				}
			}
		}
	}


	//var $contenidoAjax = $('div#div_surtido').html('<br/><p align="center"><img src="../../imagenes/general/wait.gif" width="20"/></p>');
	$.ajax({ 
		method: "GET",
		url: "../ajax/generarPedido.php",
		data:{
			arrayDetIdDetPedidos:arrayDetIdDetPedidos, arrayDetIdProductos:arrayDetIdProductos, arrayDetCantidad:arrayDetCantidad, arrayListadoPedidos:arrayListadoPedidos, arrayControlPedidos:arrayControlPedidos, arrayAlmacen:arrayAlmacen,
			arrayDetIdDetPedidosSM:arrayDetIdDetPedidosSM, arrayDetIdProductosSM:arrayDetIdProductosSM, arrayDetCantidadSM:arrayDetCantidadSM, arrayListadoPedidosSM:arrayListadoPedidosSM, arrayControlPedidosSM:arrayControlPedidosSM, arrayAlmacenSM:arrayAlmacenSM},
		dataType:"json",
		success: function(data) { // Aquí desaparece la imagen ya que estamos reemplazando todo el HTML del contenido de la div. 
			//$contenidoAjax.html(data);
			alert(data);
		}
	})
	.done(function(data){});
}

function imprimePresurtido(total){
	var f=document.forma1;	
    var reg_ids_consecutivos='0';
	for(var k=1;k<=total;k++){
		//vemos que este seleccionado algun produto osea cheked
		var elemento = document.getElementById('check_'+k);
		if(elemento){
			if(elemento.checked){
				var id_consecutivo=parseInt(document.getElementById('id_consecutivo_'+k).value);
				reg_ids_consecutivos=reg_ids_consecutivos+'|'+id_consecutivo;
			}
		}
	}	
	if(reg_ids_consecutivos=='0'){
		alert("Para imprimir el documento, es requerido seleccionar un producto.");
		return false;
	}
	window.open('../../code/pdf/imprimePresurtido.php?ids=' + reg_ids_consecutivos, "Presurtido", "width=800, height=600");		
}

//, idAlmacen, pedido, solicitudMaterial, opcion_buscador
function buscarPedidosLiquidados(idSucursal, idCliente, fechaInicial, fechaFinal, idAlmacen, tipo, PedidoSolicitudMaterial, opcion_buscador){
	switch(opcion_buscador){
		case '1':
			//alert('idSucursal: '+idSucursal);	alert('idCliente: '+idCliente); alert('fechaInicial: '+fechaInicial); alert('fechaFinal: '+fechaFinal);
			if(idSucursal == -1 && idCliente == -1 && fechaInicial == '' && fechaFinal == ''){
				alert('Debe elegir algun criterio');
				return false;
			}
			if( (fechaInicial != "" && fechaFinal == "") || (fechaInicial == "" && fechaFinal != "") ){
				alert('Debe elegir las dos fechas');
				return false;
			}else{
				//var FechaInicioConv = convierteFechaJava(fechaInicial);
				//var FechaFinConv = convierteFechaJava(fechaFinal);
				if(fechaInicial > fechaFinal){
					alert("La fecha incial no puede ser mayor a la fecha final");
					return false;
				}
			}
		break;
		case '2':
			//alert('idAlmacen: '+idAlmacen); alert('tipo: '+tipo); alert('PedidoSolicitudMaterial: '+PedidoSolicitudMaterial);
			if(idAlmacen == -1 && tipo == '' && PedidoSolicitudMaterial == ''){
				alert('Debe elegir algun criterio');
				return false;
			}else{
				if( (tipo == 'P' || tipo == 'S') && PedidoSolicitudMaterial == ''){
					alert('Debe introducir el pedido o la solucitud');
					return false;
				}
			}
		break;
	}
	var $contenidoAjax = $('div#div_surtido').html('<br/><p align="center"><img src="../../imagenes/general/wait.gif" width="20"/></p>');
	var fechaInicial = cambiarFormatoFecha(fechaInicial,'ymd','/');
	var fechaFinal = cambiarFormatoFecha(fechaFinal,'ymd','/');
	$.ajax({ 
		method: "GET",
		url: "../ajax/buscarPedidosLiquidados.php",
		data:{idSucursal:idSucursal, idCliente:idCliente, fechaInicial:fechaInicial, fechaFinal:fechaFinal, idAlmacen:idAlmacen, tipo:tipo, PedidoSolicitudMaterial:PedidoSolicitudMaterial, opcion_buscador:opcion_buscador},
		dataType:"json",
		success: function(data) { // Aquí desaparece la imagen ya que estamos reemplazando todo el HTML del contenido de la div. 
			$contenidoAjax.html(data);
			//alert(data);
		}
	})
	.done(function(data){});

	
}


function validaCantidadIgresarParaPedido(cantidadSurtir, cantidadSolicitada, cantidadRecibida, cantidadAlmacen, renglon){
	if(cantidadSurtir>(cantidadSolicitada-cantidadRecibida)){
		alert('La cantidad a ingresar no puede ser mayor a '+(cantidadSolicitada-cantidadRecibida));
		$("#cantidadI"+renglon).val('');
		$("#cantidadI"+renglon).focus();
		return false;
	}else{
		if(cantidadSurtir!=""){
			if(cantidadAlmacen<=0){
				alert('No hay producto suficiente en el almacen, para surtir este pedido');
				$("#cantidadI"+renglon).val('');
				$("#cantidadI"+renglon).focus();
			}
			return true;
		}
	}
}

/***   llenar combos   ***/
function ajaxLLenaCombos(url, datos, hijo){
	$.ajax({
		async:false,
		type: "POST",
		dataType: "html",
		contentType: "application/x-www-form-urlencoded",
		data: datos,
		url: url,
		success: function(data) {
				$("#" + hijo + " option").remove();
				$("#" + hijo).append(data);
		},
		timeout:50000
	});
}
/***   termina llenar combos   ***/

/*
//para realizar el insert en la base de datos de los productos 


function cargaClientesSucursal()
{
	//colocamos los datos
}

function buscarFiltros()
{
	//buscamos los productos dado los filtros 
	var f=document.forma1;
	document.getElementById('accion').value='buscar';
	document.getElementById('buscarPor').value='Filtros';	
	f.submit();
}

function buscarPedido()
{
	//buscamos el pedido seleccionado
	var f=document.forma1;	
	document.getElementById('accion').value='buscar';
	document.getElementById('buscarPor').value='Pedido';
	f.submit();
	
}
*/
