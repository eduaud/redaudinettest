//JavaScript Document
var http_request = null;
var funcion_excepcion = null;

var asciiArray = new Array(
	' ', '!', '"', '#', '$', '%', '&', "'", '(', ')', '*', '+', ',', '-',
	'.', '/', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', ':', ';',
	'<', '=', '>', '?', '@', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I',
	'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W',
	'X', 'Y', 'Z', '[', '\\', ']', '^', '_', '`', 'a', 'b', 'c', 'd', 'e',
	'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's',
	't', 'u', 'v', 'w', 'x', 'y', 'z', '{', '|', '}', '~', '', 'Ç', 'ü',
	'é', 'â', 'ä', 'à', 'å', 'ç', 'ê', 'ë', 'è', 'ï', 'î', 'ì', 'Ä', 'Å',
	'É', 'æ', 'Æ', 'ô', 'ö', 'ò', 'û', 'ù', 'ÿ', 'Ö', 'Ü', 'ø', '£', 'Ø',
	'×', 'ƒ', 'á', 'í', 'ó', 'ú', 'ñ', 'Ñ', 'ª', 'º', '¿', '®', '¬', '½',
	'¼', '¡', '«', '»', '_', '_', '_', '¦', '¦', 'Á', 'Â', 'À', '©', '¦',
	'¦', '+', '+', '¢', '¥', '+', '+', '-', '-', '+', '-', '+', 'ã', 'Ã',
	'+', '+', '-', '-', '¦', '-', '+', '¤', 'ð', 'Ð', 'Ê', 'Ë', 'È', 'i',
	'Í', 'Î', 'Ï', '+', '+', '_', '_', '¦', 'Ì', '_', 'Ó', 'ß', 'Ô', 'Ò',
	'õ', 'Õ', 'µ', 'þ', 'Þ', 'Ú', 'Û', 'Ù', 'ý', 'Ý', '¯', '´', '­', '±',
	'_', '¾', '¶', '§', '÷', '¸', '°', '¨', '·', '¹', '³', '²', '_', ' ');

/***********************************************************************************************************/
//Función para inializar el grid y para ajustar el ancho de acuerdo a la pantalla
function inicializa(){
	loff();
	/*InitEBAGrids();
	refreshGrids();*/
	//eval(funcion_excepcion);
	window.status="Sistema  - Desarrollado por Sys&Web SA de CV";
}
/***********************************************************************************************************/
//Función para capturar una respuesta Ajax, se le mandan como parametros
// el metodo (POST o GET), el tipo de respuesta, la url, los parametros de la url
// y el handler
function ajax_request(metodo,type,url,parametros,handler){
	var http_request = false;
	var handlerFunc = null;
	var responseTipo = type.toLowerCase()=='xml'? 'responseXML' : 'responseText';
	var conector = (url.indexOf('?')+parametros.indexOf('?')!=-2) ? '&' : '?';
	var responseRegex = /(ans)|(resp)|(xml)|(text)/g;
	if (window.XMLHttpRequest) { // Mozilla, Safari,...
		http_request = new XMLHttpRequest();
	}else if (window.ActiveXObject) { // IE
		try {
			http_request = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try {
				http_request = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {}
		}
	}
	if (!http_request){
		alert('Su navegador no soporta algunas funciones básicas del sistema.\nActualícelo.');
		return false;
	}
	http_request.onreadystatechange = function(){
		if(http_request.readyState == 4 && http_request.status == 200){
			handler = handler.replace(responseRegex,"http_request."+responseTipo);
//			alert(handler);
			eval(handler);
			http_request = null;
		}
	}
	if(metodo.toUpperCase()=='POST'){
		http_request.open(metodo,url, true);
		http_request.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=iso-8859-1');
		http_request.send(parametros);
	}else{
		http_request.open(metodo,url+conector+parametros, true);
		http_request.send(null);
	}
}
/***********************************************************************************************************/
//	Función para validar campos vacios y tipos de datos correctos
function validaDatosFormulario(forma,cuantos)
{
	for(i=0; i<forma.elements.length-cuantos; i++)
	{
		var cadena = forma.elements[i].id;
		cad = cadena.substring(0,5);
		if(forma.elements[i].value=="" && !forma.elements[i].disabled && forma.elements[i].className=="CampoRequerido")
		{
			alert("Todos los campos marcados en rojo son obligatorios para continuar["+forma.elements[i].name+"].\n\nPor favor complete esos datos e intente nuevamente.");
			forma.elements[i].focus();
			forma.elements[i].select();
			return false;
		}
		else if(forma.elements[i].value=="" && forma.elements[i].className!="CampoRequerido")
		{
			continue;
		}
		else
		{
			switch(cad)
			{
				case 'int':
					forma.elements[i].value=trimAll(forma.elements[i].value);
					forma.elements[i].value=removeCurrency(forma.elements[i].value);
					forma.elements[i].value=removeCommas(forma.elements[i].value);
					if(validateInteger(forma.elements[i].value)==false)
					{
						alert("Verifique sus datos, el "+forma.elements[i].name+" necesita\nun dato numérico entero.");
						forma.elements[i].focus();
						forma.elements[i].select();
						return false;
					}
				break;
				case 'float':
					forma.elements[i].value=trimAll(forma.elements[i].value);
					forma.elements[i].value=removeCurrency(forma.elements[i].value);
					forma.elements[i].value=removeCommas(forma.elements[i].value);
					if(validateNumeric(forma.elements[i].value)==false)
					{
						alert("Verifique sus datos, el "+forma.elements[i].name+" necesita\nun dato numérico decimal.");
						forma.elements[i].focus();
						forma.elements[i].select();
						return false;
					}
				break;
				case 'decim':
					forma.elements[i].value=trimAll(forma.elements[i].value);
					forma.elements[i].value=removeCurrency(forma.elements[i].value);
					forma.elements[i].value=removeCommas(forma.elements[i].value);
					if(validateNumeric(forma.elements[i].value)==false)
					{
						alert("Verifique sus datos, el "+forma.elements[i].name+" necesita\nun dato numérico decimal.");
						forma.elements[i].focus();
						forma.elements[i].select();
						return false;
					}
				break;
				case 'fecha':
					if(validateUSDate(forma.elements[i].value)==false)
					{
						alert("Verifique sus datos, el "+forma.elements[i].name+" necesita\nun dato tipo fecha (aaaa-mm-dd).");
						forma.elements[i].focus();
						forma.elements[i].select();
						return false;
					}
				break;
				case 'time':
					
				break;
			}
		}
	}
	
	//cuando envia la submit debe de desactivar el campor de guardar simular

	if(document.getElementById('botonGuardar'))
	{
			//alert(document.getElementById('botonGuardar').value);		
			document.getElementById('botonGuardar').disabled="true";
	}
	
	//alert('Fin del proceso');
	forma.submit();
	
	
}
/***********************************************************************************************************/
// Función para validar los datos de los grids individuales en una pantalla que sea excepción
function checaDatosGridUnico(objeto, mensaje){
	//var Grid=objeto.object;
	//var headers=new Array();
	var cadena="";
	//headers=Grid.getChildren();
	if(NumFilas(objeto)==0){
		//alert(mensaje);
		return false;
	}
	for(var i=0; i<NumFilas(objeto); i++){
		for(var j=0; j<NumColumnas(objeto); j++){
			if(HeaderLabel(objeto,j)!='Delegación/Municipio' 
					   && HeaderLabel(objeto,j)!='Código Postal' 
					   && HeaderLabel(objeto,j)!='Teléfono' 
					   && HeaderLabel(objeto,j)!='Título' 
					   && HeaderLabel(objeto,j)!='Celular' 
					   && HeaderLabel(objeto,j)!='E-mail' 
					   && HeaderLabel(objeto,j)!='Dirección de entrega' 
					   && HeaderLabel(objeto,j)!='Orden de compra' 
					   && HeaderLabel(objeto,j)!='Descuento en porcentaje' 
					   && HeaderLabel(objeto,j)!='Observaciones' 
					   && HeaderLabel(objeto,j)!='Tipo de cambio' 
					   && HeaderLabel(objeto,j)!='Hora de llegada' 
					   && HeaderLabel(objeto,j)!='Hora de salida' 
					   && HeaderLabel(objeto,j)!='Hora de recep. de mercancía' 
					   && HeaderLabel(objeto,j)!='Dirección fiscal' 
					   && HeaderLabel(objeto,j)!='Bandera1' 
					   && HeaderLabel(objeto,j)!='Bandera2' 
					   && HeaderLabel(objeto,j)!='Fecha de caducidad' 
					   && HeaderLabel(objeto,j)!='% Desc.' 
					   && HeaderLabel(objeto,j)!='% Com.' 
					   && HeaderLabel(objeto,j)!='Cant. otros pedidos' 
					   && HeaderLabel(objeto,j)!='Concepto' 
					   && HeaderLabel(objeto,j)!='RFC' 
					   && HeaderLabel(objeto,j)!='T.C.' 
					   && HeaderLabel(objeto,j)!='Cargo' 
					   && HeaderLabel(objeto,j)!='Abono' 
					   && HeaderLabel(objeto,j)!='Existencias globales' 
					   && HeaderLabel(objeto,j)!='Diferencia' 
					   && HeaderLabel(objeto,j)!='F. compromiso' 
					   && HeaderLabel(objeto,j)!='Estatus anterior' 
					   && HeaderLabel(objeto,j)!='Estatus nuevo' 
					   && HeaderLabel(objeto,j)!='control' 
					   && HeaderLabel(objeto,j)!='Precio.' 
					   && HeaderLabel(objeto,j)!='Importe.' 
					   && HeaderLabel(objeto,j)!='Porcentaje de descuento sobre precio de lista' 
					   && HeaderLabel(objeto,j)!='Porcentaje de comisión' 
					   && HeaderLabel(objeto,j)!='Genera IVA'
					   && HeaderLabel(objeto,j)!='Estatus'
					   && HeaderLabel(objeto,j)!='Cuota Com.'
					   && HeaderLabel(objeto,j)!='Arancel'
					   && HeaderLabel(objeto,j)!='Flete Adicional' 
					   && HeaderLabel(objeto,j)!='Cantidad.'
					   && HeaderLabel(objeto,j)!='% Variación'
					   && HeaderLabel(objeto,j)!='Proporción Aduanal'
					   && HeaderLabel(objeto,j)!='Proporción Fletes'
					   && HeaderLabel(objeto,j)!='Proporción Seguro'
					   && HeaderLabel(objeto,j)!='id'
					   && HeaderLabel(objeto,j)!='deleteRow'){
				if(celdaValorXY(objeto,j,i)=='' || !celdaValorXY(objeto,j,i) || celdaValorXY(objeto,j,i)=='0' || celdaValorXY(objeto,j,i)=='$ 0' || celdaValorXY(objeto,j,i)==0)
				{
					alert('* Complete los datos en la '+(j+1)+'º columna '+HeaderLabel(objeto,j)+' del renglón '+eval(i+1)+' del grid['+objeto+'] para continuar.\n\n- Recuerde que en cantidades y precios no puede haber ceros ni celdas vacías.\n- Tampoco puede dejar renglones completos sin datos.\n\nTome en cuenta estas consideraciones para registrar sus datos en el grid.');
					return false;
				}
			}
		}
	}
	return true;
}
/***********************************************************************************************************/
//	Función para iniciar el calendario
function calendario(objeto){
    Calendar.setup({
        inputField     :    objeto.id,
        ifFormat       :    "%d/%m/%Y",
        align          :    "BR",
        singleClick    :    true,
		zindex		   :	1000
	});
}
/***********************************************************************************************************/
//	Función para mostrar detalles de algún registro
function muestraDetalles(tabla,parExtra,excep, lugar){
	//var Grid = ListaTotal.object;
	var Llave = celdaValorXY('tabladelistados',0,lugar);
//	alert('opcionTablas.php?o=Ver registro&t='+tabla+'&l='+Llave+'&op='+parExtra);
//	return true;
	location.href='opcionTablas.php?o=Ver registro&t='+tabla+'&l='+Llave+'&op='+parExtra+'&excep='+excep;
}
/***********************************************************************************************************/
//	Función para un pdf de ordenes de compra
function despliegaPDF(lugar){
	//var Grid = ListaTotal.object;
	var Llave = celdaValorXY('tabladelistados',0,lugar);
	abrirVentana('pdfOdC.php?odc='+Llave+'|800|600');
}

/***********************************************************************************************************/
//	Función para modificar los detalles de algún registro

// Aqui vamos a hacer la cochinada de personalizar la modificación de los elementos que no son precargados dentro de los catálogos, 
// lo hacemos así temporalmente por la prisa....lo optimizaremos más adelante...lo mismo sucede con la función siguiente de 
// eliminar datos precargados
function modificarRegistro(tabla,parExtra, lugar){
	//var Grid = ListaTotal.object;	
	var Llave = celdaValorXY('tabladelistados',0,lugar);
	if(parExtra == 'anticipo')
		location.href='opcionTablas.php?o=Anticipos&t='+tabla+'&l='+Llave+'&op='+parExtra;
	if(	   (tabla == 'mgw_geg_ciudades' && Llave == 1) 
		|| (tabla == 'mgw_geg_cocinas' && (Llave >= 0 &&  Llave <= 3)) 
		|| (tabla == 'mgw_geg_clientes_tipos' && (Llave >= 1 &&  Llave <= 2)) 
		|| (tabla == 'mgw_geg_proveedores_tipos' && (Llave >= 1 &&  Llave <= 6)) 
		|| (tabla == 'mgw_geg_clave_de_mercancia_puesta' && (Llave >= 1 &&  Llave <= 2)) 
		|| (tabla == 'mgw_geg_forma_de_entrega_de_producto' && (Llave >= 1 &&  Llave <= 3)) 
		|| (tabla == 'mgw_geg_facturas_leyendas_de_pago' && Llave == 1) 
		|| (tabla == 'mgw_geg_notasdecredito_conceptos' && (Llave >= 1 &&  Llave <= 4)) 
		|| (tabla == 'mgw_geg_cuentas_por_cobrar_tipos' && (Llave >= 1 &&  Llave <= 5)) 
		|| (tabla == 'mgw_geg_cuentas_por_cobrar_estatus' && (Llave >= 1 &&  Llave <= 4)) 
		|| (tabla == 'mgw_geg_tipos_de_cobro' && (Llave >= 1 &&  Llave <= 6)) 
		|| (tabla == 'mgw_geg_tipos_de_egreso' && (Llave >= 1 &&  Llave <= 5)) 
		|| (tabla == 'mgw_geg_categorias_de_egresos' && (Llave >= 1 &&  Llave <= 2)) 
		|| (tabla == 'mgw_geg_documentos_tipos' && (Llave == 'FAC' ||  Llave == 'RAR' || Llave == 'RHO')) 
		|| (tabla == 'mgw_geg_cuentas_contables_genero' && (Llave >= 1 &&  Llave <= 11)) 
		|| (tabla == 'mgw_geg_tipo_de_poliza' && (Llave >= 1 &&  Llave <= 8)) 
		|| (tabla == 'mgw_geg_almacenes_subtipos_movimiento' && (Llave == 1 || Llave == 3 || Llave == 5 || Llave == 9))
		|| (tabla == 'mgw_geg_paises' && Llave == 1) 
		|| (tabla == 'mgw_geg_listas_de_precios' && Llave == 1) 
		|| (tabla == 'mgw_geg_estados' && (Llave >= 1 &&  Llave <= 32))){
			alert("Imposible modificar elementos de catálogo");
		return false;
	}else{
		if(tabla=='mgw_geg_cartas_de_liberacion' || tabla=='mgw_geg_pedidos' || tabla=='mgw_geg_notas_de_cargo' || tabla=='mgw_geg_facturas' || tabla=='mgw_geg_polizas' || tabla=='mgw_geg_movimientos_almacen_orden_compra' || tabla=='mgw_geg_ordenes_de_compra'|| tabla=='mgw_geg_cuentas_por_pagar' || tabla=='mgw_geg_egresos_bancarios' )
			ajax_request('POST','text','permisoModifElim.php','tabla='+tabla+'&llave='+Llave+'&opcion=m','resultadoPermiso(ans)');
		else
			location.href='opcionTablas.php?o=Modificar&t='+tabla+'&l='+Llave+'&op='+parExtra;
	}
}

function resultadoPermiso(ans){
	
	
	var arr = ans.split('~');
	if(arr[0]==1){
		if(arr[1]=='m'){
			if(arr[2]=='mgw_geg_notas_de_cargo')
				alert("La nota de cargo no puede ser modificada por las siguientes razones:\n\n- Está cancelada.\n- La cuenta contable asignada forma parte del detalle de alguna poliza.\n- La cuenta por cobrar tiene un pago registrado.");
			else if(arr[2]=='mgw_geg_facturas')
				alert("La factura no es modificable, está marcada como tal.");
			else if(arr[2]=='mgw_geg_polizas')
				alert("La póliza no es modificable porque ya está contabilizada.");
			else if(arr[2]=='mgw_geg_pedidos')
				alert("El pedido no es modificable pues su estatus de aprobación no lo permite.");
			else if(arr[2]=='mgw_geg_movimientos_almacen_orden_compra')
				alert("El documento del movimiento está marcado como no modificable.");
			else if(arr[2]=='mgw_geg_ordenes_de_compra')
				alert("La Orden de compra no se puede modificar por alguna de las siguientes razones:\n\n- Está completa o cancelada.\n- Tiene hecha una entrada en almacen.");
			else if(arr[2]=='mgw_geg_cuentas_por_pagar')
				alert("La cuenta por pagar no se puede modificar por alguna de las siguientes razones:\n\n- Tiene pagos registrados .\n- Esta incluida en una póliza contable .");
			else if(arr[2]=='mgw_geg_egresos_bancarios')
				alert("El egreso no se puede modificar por estar incluido en una póliza contable .");
			else
				alert("El documento seleccionado no es modificable.");
			return false;
		}else{
			if(arr[2]=='mgw_geg_cartas_de_liberacion')
				alert("No se puede eliminar la carta de liberación porque ha sido usada en alguna Orden de Salida.");
			else if(arr[2]=='mgw_geg_notas_de_cargo')
				alert("La nota de cargo no puede ser cancelada por las siguientes razones:\n\n- Está cancelada.\n- La cuenta por cobrar tiene un pago registrado.");
			else if(arr[2]=='mgw_geg_facturas')
				alert("La factura no se puede cancelar.");
			else if(arr[2]=='mgw_geg_ordenes_de_compra')
				alert("La Orden de compra no se puede cancelar por alguna de las siguientes razones:\n\n- Está completa o cancelada.\n- Tiene hecha una entrada en almacen.");
			else if(arr[2]=='mgw_geg_pedidos')
				alert("No es posible cancelar el pedido pues su estatus no lo permite.");
			else if(arr[2]=='mgw_geg_egresos_bancarios')
				alert("El egreso no se puede eliminar por estar incluido en una póliza contable .");
			else if(arr[2]=='mgw_geg_notas_de_credito')
				alert("La Nota de Crédito no se puede cancelar por alguna de las siguientes razones:\n\n- Está Utilizada, con abonos parciales o cancelada.\n");	
			else if(arr[2]=='mgw_geg_polizas')
				alert("La Poóliza no se puede cancelar porque esta contabilizada.\n Realice una póliza de ajuste.");	
			else
				alert("No se permite la eliminacion o cancelación del documento seleccionado.");
			return false;
		}
	}
	if(arr[1]=='m')
		location.href='opcionTablas.php?o=Modificar&t='+arr[2]+'&l='+arr[3];
	else
		location.href='opcionTablas.php?o=Eliminar&t='+arr[2]+'&l='+arr[3];
}
/***********************************************************************************************************/
//	Función para cancelar algún registro
function cancelarRegistro(tabla, lugar){ 
	//var Grid = ListaTotal.object;
	var Llave = celdaValorXY('tabladelistados',0,lugar);
	if(tabla=='mgw_geg_ordenes_de_compra' || tabla=='mgw_geg_pedidos' || tabla=='mgw_geg_notas_de_cargo' || tabla=='mgw_geg_facturas' || tabla=='mgw_geg_egresos_bancarios' || tabla=='mgw_geg_notas_de_credito' || tabla=='mgw_geg_polizas')
		ajax_request('POST','text','permisoModifElim.php','tabla='+tabla+'&llave='+Llave+'&opcion=e','resultadoPermiso(ans)');
	else
		location.href='opcionTablas.php?o=Eliminar&t='+tabla+'&l='+Llave+'&op=cancel';
}

function verificaCancelacion(tabla,campo,campoLlave,llave){
	ajax_request('POST','text','cancelaciones.php','tabla='+tabla+'&campo='+campo+'&campoLlave='+campoLlave+'&llave='+llave,'obtCancelacion(ans)');
}

function obtCancelacion(ans){
	var arr = ans.split('~');
	if(arr[0]=='hecho'){
		alert("El documento se ha cancelado satisfactoriamente");
		location.href='opcionTablas.php?o=Ver todos&t='+arr[1];
		return true;
	}else{
		alert("El documento no se ha podido cancelar.\n\nSi es una cuenta por cobrar es probable que alguna de sus partidas tenga estatus diferente de cancelado.\n\nIntente nuevamente.");
		return false;
	}
}

/***********************************************************************************************************/
//	Función para eliminar algún registro
function eliminarRegistro(tabla, lugar){
	//var Grid = ListaTotal.object;
	var Llave = celdaValorXY('tabladelistados',0,lugar);
	if(	   (tabla == 'mgw_geg_ciudades' && Llave == 1) 
		|| (tabla == 'mgw_geg_cocinas' && (Llave >= 0 &&  Llave <= 3)) 
		|| (tabla == 'mgw_geg_clientes_tipos' && (Llave >= 1 &&  Llave <= 2)) 
		|| (tabla == 'mgw_geg_proveedores_tipos' && (Llave >= 1 &&  Llave <= 6)) 
		|| (tabla == 'mgw_geg_clave_de_mercancia_puesta' && (Llave >= 1 &&  Llave <= 2)) 
		|| (tabla == 'mgw_geg_almacenes_subtipos_movimiento' && (Llave >= 1 &&  Llave <= 19)) 
		|| (tabla == 'mgw_geg_forma_de_entrega_de_producto' && (Llave >= 1 &&  Llave <= 3)) 
		|| (tabla == 'mgw_geg_facturas_leyendas_de_pago' && Llave == 1) 
		|| (tabla == 'mgw_geg_notasdecredito_conceptos' && (Llave >= 1 &&  Llave <= 4)) 
		|| (tabla == 'mgw_geg_cuentas_por_cobrar_tipos' && (Llave >= 1 &&  Llave <= 5)) 
		|| (tabla == 'mgw_geg_cuentas_por_cobrar_estatus' && (Llave >= 1 &&  Llave <= 4)) 
		|| (tabla == 'mgw_geg_tipos_de_cobro' && (Llave >= 1 &&  Llave <= 6)) 
		|| (tabla == 'mgw_geg_tipos_de_egreso' && (Llave >= 1 &&  Llave <= 5)) 
		|| (tabla == 'mgw_geg_categorias_de_egresos' && (Llave >= 1 &&  Llave <= 2)) 
		|| (tabla == 'mgw_geg_cuentas_contables_genero' && (Llave >= 1 &&  Llave <= 11)) 
		|| (tabla == 'mgw_geg_documentos_tipos' && (Llave == 'FAC' ||  Llave == 'RAR' || Llave == 'RHO')) 
		|| (tabla == 'mgw_geg_tipo_de_poliza' && (Llave >= 1 &&  Llave <= 8)) 
		|| (tabla == 'mgw_geg_paises' && Llave == 1) 
		|| (tabla == 'mgw_geg_listas_de_precios' && Llave == 1) 
		|| (tabla == 'mgw_geg_estados' && (Llave >= 1 &&  Llave <= 32))){
		alert("Imposible eliminar elementos de catálogo");
		return false;
	}else{
		if(tabla=='mgw_geg_cartas_de_liberacion' || tabla=='mgw_geg_pedidos' || tabla=='mgw_geg_facturas' || tabla=='mgw_geg_egresos_bancarios' || tabla=='mgw_geg_polizas')
			ajax_request('POST','text','permisoModifElim.php','tabla='+tabla+'&llave='+Llave+'&opcion=e','resultadoPermiso(ans)');
		else
			location.href='opcionTablas.php?o=Eliminar&t='+tabla+'&l='+Llave;
	}
}
/***********************************************************************************************************/
//	Función que lleva a pantalla para imprimir un documento
function imprimirDocumento(tabla, lugar,llave,opcion, numero){
	var aparicion=0;
	
	if(opcion)
		Llave = llave;
	else
	{
		//var Grid = document.getElementById('tabladelistados');		
		var Llave = celdaValorXY('tabladelistados',0, lugar);		
	}

	aparicion=Llave.toString().search(',');
	
	if (tabla=='mgw_geg_facturasRR'  && aparicion<0  )
	{
		//alert('JOJO');	
		//verificamos que este en no modificar la factura
		ajax_request('POST','text','permisoImprimir.php','tabla='+tabla+'&llave='+Llave+'&opcion=i','resultadoPermisoImprimir(ans)');
		
		
	}
	else
	{
		
		//alert(tabla);
		var centroAncho = (screen.width/2) - 400;
		var centroAlto = (screen.height/2) - 300;
		var especificaciones="top="+centroAlto+",left="+centroAncho+",toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,width=800,height=600,resizable=yes"
		var titulo="ventanaEmergente"
		
		window.open('opcionTablas.php?o=Imprimir&t='+tabla+'&l='+Llave,titulo,especificaciones);
	}


//	location.href='opcionTablas.php?o=Imprimir&t='+tabla+'&l='+Llave;
}
//---------------------------------------
//--------------------------------------
//--------------------------------------
function resultadoPermisoImprimir(ans){
	var arr = ans.split('~');
	
	
		if(arr[1]=='si'){
			
			var centroAncho = (screen.width/2) - 400;
			var centroAlto = (screen.height/2) - 300;
			var especificaciones="top="+centroAlto+",left="+centroAncho+",toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,width=800,height=600,resizable=yes"
			var titulo="ventanaEmergente"
			
			window.open('opcionTablas.php?o=Imprimir&t='+arr[2]+'&l='+arr[3],titulo,especificaciones);
				
			return false;
		}else{
		 if(arr[2]=='mgw_geg_facturas')
				alert(arr[0]);
			else if(arr[2]=='mgw_geg_ordenes_de_compra')
				alert("La Orden de compra no se puede cancelar por alguna de las siguientes razones:\n\n- Está completa o cancelada.\n- Tiene hecha una entrada en almacen.");
			else if(arr[2]=='mgw_geg_pedidos')
				alert("No es posible cancelar el pedido pues su estatus no lo permite.");
			else
				alert("No se permite la impresion del documento seleccionado.");
			return false;
		}

	
	//mandamos la impresion
	
	
	
}


//----------------------------------
//----------------------------------



/***********************************************************************************************************/
//	Función para filtrar los datos de un listado
function filtro(tabla, campo, operador, valor, extra,url,tcr, fd, fa,stm)
{
	//var Grid = ListaTotal.object;
	aPagina('listado', 1);
	if(valor=='' && operador != 'inactivos' && fd == ''  && fa == '')
	{
		alert("Debe especificar el dato a filtrar");
		return false;
	}
	//Se agrega este condicional, el boton "Ver Todos" envia nulos en campo, operador y valor.
	//alert(Grid.getHandler);
	if(campo==null)
	{
		//Grid.getHandler = 'datosListados.php?t='+tabla;
		RecargaGrid('listado',url+'code/indices/datosListados.php?t='+tabla+'&tcr='+tcr+'&stm='+stm);
		//alert('Ok...1');
	}
	else
	{
		//Grid.getHandler = 'datosListados.php?t='+tabla+'&c='+campo+'&o='+operador+'&v='+valor+'&op='+extra;
		//alert(url+'code/indices/datosListados.php?t='+tabla+'&c='+campo+'&o='+operador+'&v='+valor+'&op='+extra+'&tcr='+tcr+'&fecdel='+fd+'&fecal='+fa);
		RecargaGrid('listado',url+'code/indices/datosListados.php?t='+tabla+'&c='+campo+'&o='+operador+'&v='+valor+'&op='+extra+'&tcr='+tcr+'&fecdel='+fd+'&fecal='+fa+'&stm='+stm);
		//alert('Ok...');
	}
	return;
}
/***********************************************************************************************************/
//	Función solo para validar que no se tengan datos vacíos en un lookup de cualquier grid
function validaOpcionVacioLookupGrids(obj){
	if(obj.getSelectedCellValue().toString()=='')
		return false;
	return true;
}
/***********************************************************************************************************/
//	Funciones que insertan una decsripción u otra columna de un lookup en otra celda del mismo grid
function muestraDescripcionProducto(obj){
	if(document.getElementById("facturascc"))
		return false;	
	try{
		if(obj.getSelectedCellValue()=='')
			return false;
		//var aux=obj.getChildren(obj.getRow());			
		//var cc=obj.getSelectedLookupColumn("cc");
		var Desc=obj.getSelectedLookupColumn("descripcion");
		if(Desc!=null)
		{
			//alert(obj.getSelectedLookupColumn("cc"));
			
			obj.setCell(obj.getRow(),1,Desc);
		}
	}catch(e){
		if(e.message=='Se esperaba un objeto')
			return false;
	}
	return true;
}

/***********************************************************************************************************/
//	Funcion para eliminar todos los registros de un grid, toma la función que tiene en ese momento y la sustituye por return true;, cuando termina de eliminar, regresa a la función original
/***********************************************************************************************************/
//	Esta función esconde los table rows de una tabla de un formulario que se le mande por el arreglo, los trs deben tener un id numerico, por ejemplo: 19,20,21 y el arreglo debe contener [19,20,21]
function escondeRenglones(arr){	
	for(var i=0; i<arr.length; i++)
	{
		document.getElementById(arr[i]).style.display = 'none';
	}
	return true;
}
/***********************************************************************************************************/
//	Abre la ficha técnica del producto en una ventana
function abreFicha(producto){
	var url = 'fichaTecnica.php?producto='+producto;
	abrirVentana(url+'|500|400');
}
/***********************************************************************************************************/
//	Busca los grids desplegados, si los hay, les inserta una fila que actuará como el primer renglón del registro
function insertaFilaNueva(opcion){
	if(!document.getElementsByTagName("gridlist"))
		return true;
	var grids = document.getElementsByTagName("gridlist");
	if(grids.length<=0)
		return;
	if(opcion=='clientes'){
		document.getElementById("direcciones").object.insertRow();
		document.getElementById("contactos").object.insertRow();
	}else if(opcion=='prodfac')
		document.getElementById("productos").object.insertRow();
	else if(opcion=='egresos')
		document.getElementById("egresodetalle").object.insertRow();
	else if(opcion=='movimientos')
		document.getElementById("detalleTodos").object.insertRow();
	else if(opcion=='proveedores')
		document.getElementById("contactos").object.insertRow();
	else if(opcion=='ncredito')
		document.getElementById("productosNC").object.insertRow();
	else{		
		for(var i=0;i<grids.length;i++)		
		{
			if(grids[i].object.rowCount() <= 0)
				grids[i].object.insertRow();
		}
	}
}
/***********************************************************************************************************/
//	Busca los grids desplegados, si los hay, hace un sort sobre la primera columna
// esto es para evitar el problema de que no muestra los datos en los grids de algunas pantallas de excepciones
// en donde ya hay datos
function refreshGrids(){
	if(!document.getElementsByTagName("gridlist"))
		return true;
	var grids = document.getElementsByTagName("gridlist");
	if(grids.length<=0)	return;
	for(var i=0;i<grids.length;i++){
		grids[i].object.sort(0,true);
	}
}
/***********************************************************************************************************/
// Desactiva todos los elementos de las formas menos el boton con la etiqueta Regresar, todos los grids se ponen
// como estáticos
function desactivaFormulariosGrids(){
	var i,j,columnas;
	var grids = document.getElementsByTagName("gridlist");
	var gridCols = document.getElementsByTagName("ColumnDefinition");
	//DESACTIVA ELEMENTOS DE FORMA
	for(i=0;i<document.forms.length;i++){
		for(j=0;j<document.forms[i].elements.length;j++){
			if(document.forms[i].elements[j].value!='Regresar'){
				document.forms[i].elements[j].disabled = true;
			}
		}
	}
// DESACTIVA GRIDS y no permite tampoco insertar ni eliminar
	if(grids.length<=0 && gridCols.length<=0)	return true;
	for(j=0;j<grids.length;j++){
		grids[j].setAttribute("allowDelete","N");
		grids[j].setAttribute("allowInsert","N");
	}
	for(j=0;j<gridCols.length;j++){
		gridCols[j].setAttribute("celldisabled","Y");
	}
}
/***********************************************************************************************************/
//	Función común para confirmar una eliminación de fila en cualquier grid
function borraFila(){
	input_box=confirm("¿Está seguro de querer borrar esta información?\nSe borrará el renglón completo.");
	if(input_box==true){
		return true;
	}
	return false;
}
/***********************************************************************************************************/
//	Funciones para manejar errores en los grids
function showError(grid){
	alert('El grid ha encontrado un problema - probablemente relacionado con permisos de escritura.\n\nDe clic en Aceptar para ver la respuesta del manejador de errores.');
	alert(grid.object.lastError);
}
function showHandlerError(){
	alert('El grid ha encontrado un problema con el getHandler o el setHandler.\n\nDe clic en Aceptar para ver la respuesta del manejador de errores.');
	alert(grid.object.lastHandlerError);
}
/***********************************************************************************************************/
//	Funciones para desplegar la leyenda de guardar en pantallas en donde existan grids y una función extra que bloquea el campo de cliente o proveedor para que no se escoja en el grid un producto que no sea de otro cliente o proveedor
function OnBeforeSave(etiqueta){
	etiqueta.innerText="Procesando los datos...";
}
// Función para lanzar eventos después de salvar los datos del grid
function OnAfterSave(etiqueta){
//	alert(detallepedido.object.lastSaveHandlerResponse);
	etiqueta.innerText="Procesando los datos...listo!";
	setTimeout("LeyendaGuardar.innerText=''",3000);
}
// Esta función es para cuando necesito deshabilitar un combo que dependa de un producto u otra celda en el grid que se haya elegido
function OnAfterSaveEspecial(etiqueta,campo,grid){
//	alert(odcdetalle.object.lastSaveHandlerResponse);
	var Grid=grid.object;
	try{
		if(Grid.rowCount()>0 && Grid.getCellValue(0,0)!=""){
			if(document.getElementById('buscaCliente'))
				document.getElementById('buscaCliente').readOnly = true;
			campo.disabled=true;
		}else{
			if(document.getElementById('buscaCliente'))
				document.getElementById('buscaCliente').readOnly = false;
			campo.disabled=false;
		}
		etiqueta.innerText="Procesando los datos...listo!";
		setTimeout("LeyendaGuardar.innerText=''",3000);
	}catch(e){
		alert(e);
	}
}
// Esta función es para refrescar la información del grid, es requerida cuando hay grids que tienen partidas importadas de algún lado
// y que por alguna extraña razón no se han salvado bien en el script
function refrescaInfoGrid(grid){
	var aux;
	for(var i=0; i<grid.rowCount(); i++){
		aux=grid.getCellValue(i,7);
		grid.setCell(i,7,'kj hfkjshfkdshkj');
		grid.setCell(i,7,aux);
	}
	return true;

//	grid.onmodified = 'this.save()';
	for(var i=0; i<grid.rowCount(); i++){
		for(var j=0; j<grid.columnCount(); j++){
			grid.activeCellCoords = [i,j];
			grid.activateCell();
			grid.activeCell.focus();
			aux=grid.getCellValue(i,j);
			grid.editCell(i,j);
			grid.setCell(i,j,aux);
			grid.save();
		}
	}
	return true;
}

/***********************************************************************************************************/
//	Función que elimina los selects de una forma y coloca en su lugar el label seleccionado en cada uno, para esto debe haber spans en la forma, en general una estructura que se puede ver en los detalles.tpl
function muestraLabel(cuantosNo){
	var arreglo = document.forms.forma_datos.elements;
	for(var i=0; i<arreglo.length-cuantosNo; i++){
		if(document.getElementById(arreglo[i].id)){
			var sel = document.getElementById(arreglo[i].id);
			var arr = arreglo[i].id.split('campo');
			var et = document.getElementById("campo"+eval(arr[1]-1));
			et.innerHTML = sel.options[sel.selectedIndex].text;
			sel.style.display = 'none';
		}
	}
	return true;
}

/***********************************************************************************************************/
//	Funciones ajax para verificar que no exista un ID que se está capturando en un campo de llave primaria editable tipo alfanumerico
function checaID(campo, tabla, campoTabla,opcionPrefijo){
	if(!document.forms.forma_datos.pri){
		if(campo.value!=null)
			campo.value=campo.value.toUpperCase();
		if(opcionPrefijo)
			var valor = opcionPrefijo.value+campo.value;
		else
			var valor = campo.value;
		ajax_request('POST','text','buscaID.php','valor='+valor.toUpperCase()+'&tabla='+tabla+'&campoTabla='+campoTabla,'checaIDRespuesta(ans)');
	}
	return true;
}

function checaIDRespuesta(respuesta){
	if(respuesta=='no id'){
		alert("Debe introducir un ID válido.");
		document.forms.forma_datos.folio.focus();
		document.forms.forma_datos.folio.select();
		return false;
	}
	if(respuesta=='ya existe'){
		alert("El ID que acaba de indicar ya existe.\nIntroduzca otro.");
		document.forms.forma_datos.folio.focus();
		document.forms.forma_datos.folio.select();
		return false;
	}
	if(respuesta=='no existe')
		return true;
	return false;
}

/***********************************************************************************************************/
//	Función para validar que el dato introducido sea un entero...con expresiones regulares
function validateInteger(strValue){
	var objRegExp=/(^-?\d\d*$)/;
	return objRegExp.test(strValue);
}
/***********************************************************************************************************/
//	Función para validar que el dato introducido sea un número...con expresiones regulares
function validateNumeric(strValue){
	var objRegExp=/(^-?\d\d*\.\d*$)|(^-?\d\d*$)|(^-?\.\d\d*$)/;
	return objRegExp.test(strValue);
}
/***********************************************************************************************************/
//	Función para quitar espacios en blanco antes y después de la cadena...con expresiones regulares
function trimAll(strValue)
{	
	var objRegExp=/^(\s*)$/;
    if(objRegExp.test(strValue))
	{
		strValue=strValue.replace(objRegExp, '');
		if( strValue.length==0)
			return strValue;
    }
	objRegExp=/^(\s*)([\W\w]*)(\b\s*$)/;
	if(objRegExp.test(strValue))
	{
       strValue = strValue.replace(objRegExp, '$2');
	}	
	return strValue;
}
/***********************************************************************************************************/
//	Función para validar que una cadena es una fecha estilo USA (año-mes-dia)
function validateUSDate(strValue){
	var objRegExp = /^\d{4}(\-|\/|\.)\d{1,2}\1\d{1,2}$/
	if(!objRegExp.test(strValue))
		return false;
	else{
		var strSeparator = strValue.substring(4,5);
		var arrayDate = strValue.split(strSeparator);
		var arrayLookup = { '01' : 31,'03' : 31, '04' : 30,'05' : 31,'06' : 30,'07' : 31,'08' : 31,'09' : 30,'10' : 31,'11' : 30,'12' : 31}
		var intDay = parseInt(arrayDate[2],10);
		var intMonth = parseInt(arrayDate[1],10);
		if(arrayLookup[arrayDate[1]] != null){
			if(intDay <= arrayLookup[arrayDate[1]] && intDay != 0)
			return true;
		}
		if(intMonth == 2){ 
			var intYear = parseInt(arrayDate[0]);
			if(intDay > 0 && intDay < 29){
				return true;
			}else if (intDay == 29){
				if((intYear % 4 == 0) && (intYear % 100 != 0) || (intYear % 400 == 0)){
					return true;
				}   
			}
		}
	}  
	return false;
}
/***********************************************************************************************************/
//	Función para hacer el cálculo del total en los formularios de encabezados se suma el iva 
//	para los productos que los generan y se suman al subtotal por cada renglón del grid
function calculaTotales(subtotal,iva,total,idgrid,columnaSubtotal,opcion,ieps)
{
	
	var valorSubtotal = null;
	var valorIVA = null;	
	var valorTotal = null;
	
	if(document.getElementById(ieps)!=null)
		var valorIEPS = null;
		
	
	if(!(valorSubtotal= document.forma_datos.elements[subtotal]))
		valorSubtotal = document.getElementById(subtotal);
		
	if(!(valorIVA= document.forma_datos.elements[iva]))
		valorIVA = document.getElementById(iva);

	if(!(valorTotal= document.forma_datos.elements[total]))
		valorTotal = document.getElementById(total);
		
	if(document.getElementById(ieps)!=null)
		valorIEPS= document.forma_datos.elements[ieps];
		
	if(document.getElementById(ieps)!=null)
		valorIEPS = document.getElementById(ieps);
	
	var generaIVA = null;
	if(document.getElementById(ieps)!=null)
	{
		var generaIEPS = null;
		var i_e_p_s = 0;
	}
	var sub_total = 0;
	var i_v_a = 0;		
	var t_o_t_a_l = 0;
// Si se modifica algo del grid, como agregar una columna, hay que modificar esta parte, en los getcellvalues	
	for(var i=0; i<NumFilas(idgrid); i++)
	{		
		if(idgrid=='odcdetalle')
		{
			generaIVA = grid.getCellValue(i,9);
			montoIVA=generaIVA=='1'?grid.getCellValue(i,10):0;
		}
		else if(idgrid=='detallepedido')
		{
			if(opcion==2)
			{
				generaIVA = grid.getCellValue(i,14);
				generaIEPS = grid.getCellValue(i,15);
				montoIVA=generaIVA=='1'?grid.getCellValue(i,17):0;
				montoIEPS=generaIEPS=='1'?grid.getCellValue(i,18):0;
			}
			else
			{
				generaIVA = grid.getCellValue(i,8);
				generaIEPS = grid.getCellValue(i,9);
				montoIVA=generaIVA=='1'?grid.getCellValue(i,11):0;
				montoIEPS=generaIEPS=='1'?grid.getCellValue(i,12):0;
			}
		}
		else if(idgrid=='productos')
		{			
			generaIVA = celdaValorXY(idgrid,10,i);
			generaIEPS = celdaValorXY(idgrid,11,i);			
			montoIVA=generaIVA=='1'?celdaValorXY(idgrid,13,i):0;
			montoIEPS=generaIEPS=='1'?celdaValorXY(idgrid,14,i):0;			
		}
		else if(idgrid=='productosNC')
		{
			generaIVA = grid.getCellValue(i,6);
			generaIEPS = grid.getCellValue(i,7);			
			montoIVA=generaIVA=='1'?grid.getCellValue(i,9):0;
			montoIEPS=generaIEPS=='1'?grid.getCellValue(i,10):0;
		}
		else if(idgrid=='notadetalle')
		{
			generaIVA = grid.getCellValue(i,1);
			montoIVA=generaIVA=='1'?grid.getCellValue(i,4):0;
		}
		else
		{
			generaIVA = grid.getCellValue(i,9);
			montoIVA=generaIVA=='1'?grid.getCellValue(i,10):0;
		}		
		i_v_a = eval(i_v_a)+eval(montoIVA);
		i_v_a = Math.round(i_v_a * Math.pow(10, 2)) / Math.pow(10, 2);

		if(opcion>=1)
			i_v_a=eval(i_v_a);
		else
			i_v_a=0;		
		if(document.getElementById(ieps)!=null)
		{
			i_e_p_s = eval(i_e_p_s)+eval(montoIEPS);
			i_e_p_s = Math.round(i_e_p_s * Math.pow(10, 2)) / Math.pow(10, 2);
		}		
		//alert(Sumatoria(idgrid,columnaSubtotal));
		sub_total = eval(
						 parseFloat(
									trimAll(
											""+Sumatoria(idgrid,columnaSubtotal)+""
											)
									)
						 );
		sub_total = Math.round(sub_total * Math.pow(10, 2)) / Math.pow(10, 2);		
		if(document.getElementById(ieps)!=null)
			t_o_t_a_l = eval(parseFloat(sub_total)+parseFloat(i_v_a)+parseFloat(i_e_p_s));
		else
			t_o_t_a_l = eval(parseFloat(sub_total)+parseFloat(i_v_a));
		t_o_t_a_l = Math.round(t_o_t_a_l * Math.pow(10, 2)) / Math.pow(10, 2);		
	}		
	valorSubtotal.value = sub_total;
	valorIVA.value = i_v_a;
	valorTotal.value=t_o_t_a_l;
	if(document.getElementById(ieps)!=null)
		valorIEPS.value=i_e_p_s;		
}
// 	Función para obtener el valor del summary en cualquier grid
function obtieneValorSummary(id,column)
{	
}

/***********************************************************************************************************/
//	Función para remover caracteres de formato de las cadenas...con expresiones regulares
function removeCurrency(strValue){
	var objRegExp=/\(/;
	var strMinus='';
	if(objRegExp.test(strValue)){
		strMinus='-';
	}
	objRegExp = /\)|\(|[,]/g;
	strValue = strValue.replace(objRegExp,'');
	if(strValue.indexOf('$')>=0){
		strValue=strValue.substring(1, strValue.length);
	}
	return strMinus+strValue;
}
/***********************************************************************************************************/
//	Función para remover las comas de un numero...con expresiones regulares
function removeCommas(strValue)
{
	//objRegExp = /,/g;
	objRegExp = /\$|\(|[,]/g;
	//alert(strValue.replace(objRegExp,''));
	return strValue.replace(objRegExp,'');
}
/***********************************************************************************************************/
//Cuenta y limita los caracteres que pueden escribirse en un campo Observaciones
function contadorCaracteres(field){
	if(field.value.length>255)
		field.value=field.value.substring(0, 255);
	else
		contCar.innerText=255-field.value.length;
}
/***********************************************************************************************************/
// Función para avisar al usuario acerca de la configuración de la página para imprimir documentos.
function avisoImpresion(){
	alert("Antes de imprimir el documento asegurese de tener las siguientes características en la\nconfiguración de la página:\n\nTamaño:\tCarta\n\nMargen derecho:\t4.32 mm\nMargen superior:\t4.32 mm\nMargen izquierdo:\t4.32 mm\nMargen inferior:\t4.32 mm\n\nEncabezado y pie de página:\tDejar en blanco\n\nEsto lo podrá conseguir accesando al menú [Archivo] -> [Configurar página...] de su navegador.");
	return true;
}

/***********************************************************************************************************/
// Función para generar llaves temporales
function generaLlave(){
	return indice--;
}
/***********************************************************************************************************/
//Funciones de precarga de páginas (ajax)
function lon(target){
	try{
		if (parent.visibilityToolbar)
			parent.visibilityToolbar.set_display("standbyDisplayNoControls");
	}catch(e){}
	try{
		if (!target)
			target = this;
		if (!target._lon_disabled_arr)
			target._lon_disabled_arr = new Array();
		else if (target._lon_disabled_arr.length > 0)
			return true;
		target.document.getElementById("loaderContainer").style.display = "";
		var select_arr = target.document.getElementsByTagName("select");
		for (var i = 0; i < select_arr.length; i++) {
			if (select_arr[i].disabled)
				continue;
			select_arr[i].disabled = true;
			_lon_disabled_arr.pop(select_arr[i]);
			var clone = target.document.createElement("input");
			clone.type = "hidden";
			clone.name = select_arr[i].name;
			var values = new Array();
			for (var n = 0; n < select_arr[i].length; n++) {
				if (select_arr[i][n].selected) {
					values[values.length] = select_arr[i][n].value;
				}
			}
			clone.value = values.join(",");
			select_arr[i].parentNode.insertBefore(clone, select_arr[i]);
		}
	}catch(e){
		return false;
	}
	return true;
}

function loff(target){
	try{
		if (parent.visibilityToolbar){
			parent.visibilityToolbar.set_display(visibilityCount? "standbyDisplay": "standbyDisplayNoControls");
		}
	}catch(e){}
	try{
		if (!target)
			target = this;
		target.document.getElementById("loaderContainer").style.display = "none";
		if (target._lon_disabled_arr){
			while(_lon_disabled_arr.length > 0) {
				var select = _lon_disabled_arr.push();
				select.disabled = false;
				var clones_arr = target.document.getElementsByName(select.name);
				for (var n = 0; n < clones_arr.length; n++) {
					if ("hidden" == clones_arr[n].type)
					clones_arr[n].parent.removeChild(clones_arr[n]);
				}
			}
		}
/*		var select_arr = target.document.getElementsByTagName("select");
		for (var i = 0; i < select_arr.length; i++)
			select_arr[i].disabled = false;*/
	}catch(e){
		return false;
	}
	return true;
}
/***********************************************************************************************************/
// Para imprimir una vista previa
function ieExecWB(intOLEcmd,intOLEparam){
	var WebBrowser = '<OBJECT ID="WebBrowser1" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></OBJECT>';
	document.body.insertAdjacentHTML('beforeEnd', WebBrowser);
	if(!intOLEparam || intOLEparam < -1 || intOLEparam > 1 )
		intOLEparam = 1;
	WebBrowser1.ExecWB( intOLEcmd, intOLEparam );
	WebBrowser1.outerHTML = "";
}
/***********************************************************************************************************/
// Función para abrir una ventana tipo popup centrada en pantalla de usuario
function abrirVentana(cadena){
	var arreglo=cadena.split('|');
	var centroAncho = (screen.width/2) - (arreglo[1]/2);
	var centroAlto = (screen.height/2) - (arreglo[2]/2);
	var especificaciones="top="+centroAlto+",left="+centroAncho+",toolbar=no,location=no,status=no,menubar=no,scrollbars=no,width="+arreglo[1]+",height="+arreglo[2]+",resizable=false"
	var titulo="ventanaEmergente"
	window.open(arreglo[0],titulo,especificaciones);
}
/***********************************************************************************************************/
// Funciones para cobros parciales
//	Valida que la cuenta por cobrar introducida sea del tipo que se especificó y que sea válida
function validaCuenta(grid, pos)
{
	var cxc = celdaValorXY(grid, 1, pos);
	/*
	for(var i=0; i<grid.rowCount(); i++){
		if(cxc==grid.getCellText(i,1) && i!=grid.getRow()){
			alert("La cuenta ya tiene aplicado un cobro, si desea aplicar otro aplíquelo después de guardar este registro.");
			return false;
		}
	}*/
	
	//var arr = grid.getCellText(grid.getRow(),0).split('->');
	var tipo = celdaValorXY(grid, 0, pos);
	//alert('../phpAjax/validacionesCobrosParciales.php?opcion=verificaTipo&cxc='+cxc+'&tipo='+tipo+'&pos='+pos);
	ajax_request('POST','text','../phpAjax/validacionesCobrosParciales.php','opcion=verificaTipo&cxc='+cxc+'&tipo='+tipo+'&pos='+pos,'validaCuenta2(ans)');
	return true;
}

function validaCuenta2(ans)
{
	//var grid = document.getElementById('cobros').object;
	var arr = ans.split('~');
	if(arr[0]=='nula')
	{
		//grid.setCell(grid.getRow(),1,'');
		alert('Debe poner un valor');
		EditarValor('cobros_1_'+pos);
		return false;
	}
	else if(arr[0]=='noexisteosaldada')
	{
		alert("La cuenta por cobrar no existe o ya está saldada.\n\nElija otra.");
		borraValoresCeldasGrid([1,2,3,4,8], arr[1]);
		return false;
	}
	else if(arr[0]=='nomismotipo')
	{
		alert("La cuenta por cobrar no es del mismo tipo de cuenta elegido.\n\nElija el mismo tipo de cuenta o especifique otra cuenta por cobrar.");
		borraValoresCeldasGrid([1,2,3,4,8], arr[1]);
		return false;
	}
	else if(arr[0]=='aceptada')
	{		
		colocaValoresCeldasGrid([2,3,4,11,8],[arr[1],arr[2],arr[3],arr[4],arr[2]], arr[5]);
	}
	else
	{
		alert(ans);
		//grid.setCell(grid.getRow(),1,'');
		return false;
	}
	return true;
}

function validaTipoCobro(grid){
	var arr = grid.getCellText(grid.getRow(),2).split('->');
	var cliente = arr[1];
	var temp = grid.getSelectedCellText();
	var arr = grid.getSelectedCellText().split('->');
	var tipocobro = arr[1];
	var celdadocs = document.getElementById('celdaDocumentos');
	if(tipocobro!='4' && tipocobro!='6'){
		borraValoresCeldasGrid([7,8]);
		colocaValoresCeldasGrid([6],[temp]);
		celdadocs.setAttribute('type','TEXT');
		celdadocs.setAttribute('getHandler','');
	}else{
		if(!cliente || cliente=='' || cliente=='undefined'){
			alert("Aún no se define al cliente para poder listar sus documentos.\n\nEspecifique una cuenta por cobrar válida.");
			return false;
		}
		borraValoresCeldasGrid([7,8]);
		colocaValoresCeldasGrid([6],[temp]);
		celdadocs.setAttribute('getHandler','../excepciones/lookUps/datosLookUpsGeneral.php?opcion=documentos&cliente='+cliente+'&tipo='+tipocobro);
		celdadocs.setAttribute('type','LOOKUP');
	}
	return true;
}

function validaDocumento(grid){
	borraValoresCeldasGrid([8]);
	var pendiente = grid.getCellValue(grid.getRow(),3);
	try{
		if(grid.getSelectedCellValue()=='')
			return false;
		var disponible = grid.getSelectedLookupColumn("disponible");
		if(disponible!=null){
			if(disponible>pendiente)
				grid.setCell(grid.getRow(),8,pendiente);
			else
				grid.setCell(grid.getRow(),8,disponible);
			grid.setCell(grid.getRow(),10,disponible);
		}
	}catch(e){alert(e.message);}
	return true;
}

/*function validaMonto(grid){
	var monto = grid.getSelectedCellValue();
	var pendiente = grid.getCellValue(grid.getRow(),3);
	if(pendiente==0){
		alert("No se ha seleccionado una cuenta por cobrar o bien el saldo pendiente es cero y no aplica ningún cobro.");
		return false;
	}
	if(monto>pendiente){
		alert("Monto a cobrar no válido.\n\nEstá definiendo un monto a cobrar mayor al saldo pendiente de la cuenta. Corrija.");
		return false;
	}
	var arr = grid.getSelectedCellText().split('->');
	var tipocobro = arr[1];
	if(tipocobro==4 || tipocobro==6){
		var disponiblenota = grid.getCellValue(grid.getRow(),10);
		if(monto>disponiblenota){
			alert("Monto a cobrar no válido.\n\nEl monto es mayor al saldo disponible en la nota de crédito o el pago anticipado. Corrija.");
			return false;
		}
	}
	return true;
}*/

function borraValoresCeldasGrid(arreglo, pos){
	//var grid = document.getElementById('cobros').object;
	for(var i=0; i<arreglo.length; i++)
		valorXY('cobros', arreglo[i], pos, '');
	return true;
}

function colocaValoresCeldasGrid(arreglo,valores, pos)
{
	//var grid = document.getElementById('cobros').object;
	for(var i=0; i<arreglo.length; i++)
	{
		//grid.setCell(grid.getRow(),arreglo[i],valores[i]);
		//alert(valores[i]);
		valorXY('cobros', arreglo[i], pos, valores[i]);
	}
	Foco('cobros', 5, pos);
	return true;
}

function validaInsercionesGridCobros(obj)
{
	var ultimaFila = NumFilas(obj);
	if(ultimaFila<1)
		return true;
	else
	{
		var tipocxc = celdaValorXY(obj, 0, ultimaFila-1);		
		var cxc = celdaValorXY(obj, 1, ultimaFila-1);
		var cliente = celdaValorXY(obj, 2, ultimaFila-1);
		var pendiente = celdaValorXY(obj, 3, ultimaFila-1);
		var total = celdaValorXY(obj, 4, ultimaFila-1);
		var fecha = celdaValorXY(obj, 5, ultimaFila-1);
		var tipoc = celdaValorXY(obj, 6, ultimaFila-1);
		var doc = celdaValorXY(obj, 7, ultimaFila-1);
		var monto = celdaValorXY(obj, 8, ultimaFila-1);
		if(tipocxc=='' || tipocxc.length<1 || cxc=='' || cxc.length<1 || cliente=='' || cliente.length<1 || pendiente=='' || pendiente.length<1 || total=='' || total.length<1 || fecha=='' || fecha.length<1 || tipoc=='' || tipoc.length<1 || doc=='' || doc.length<1 || monto=='' || monto.length<1)
		{
			alert('Todos los campos son obligatorios a excepción de Observaciones.\n\nVerifique sus datos y complete los que hagan falta.');
			return false;
		}
		else
			return true;
	}
}

function guardaCobros(usuario)
{
	//var grid = document.getElementById('cobros').object;
	
	if(validaInsercionesGridCobros('cobros')==true)
	{
		if(NumFilas('cobros') == 0)
		{
			alert("No se ha capturado ningún cobro. Haga clic en el grid y presione la tecla 'INSERT' para comenzar a capturar.");
			return false;
		}
		var datos = new Array();
		var arr = new Array();
		var cadena = "";
		for(var i=0; i<NumFilas('cobros'); i++)
		{
			cadena+=(celdaValorXY('cobros',11,i)+'|');
			/*arr = grid.getCellValue(i,6).split('->');*/
			cadena+=(celdaValorXY('cobros',6,i)+'|');
			cadena+=(celdaValorXY('cobros',7,i)+'|');
			cadena+=(celdaValorXY('cobros',5,i)+'|');
			cadena+=(celdaValorXY('cobros',8,i)+'|');
			cadena+=(celdaValorXY('cobros',9,i)+'|');
			cadena+=(usuario+'|');
			cadena+='~'
		}
		//alert(cadena);
		ajax_request('POST','text','../phpAjax/guardaRegistroCobros.php','cadena='+cadena,'guardaCobros2(ans)');
		return true;
	}
	return false;
}

function guardaCobros2(ans){
	if(ans=='exito'){
		alert("Los cobros se han registrado exitosamente.");
		history.go(-1);
	}
	else
	{
		var arr = ans.split('~');
		if(arr[0]=='excedepago')
		{
			//echo "excedepago~".$importeCxC."~".$pagadoCxP."~".$arr2[4]."~".$i;
			alert("El monto de la cuenta por cobrar en el renglón "+arr[4] +" excéde el monto del adeudo.\n      Verifique los montos.");
			return false;
		}
		else if(arr[0]=='noexistepa')
		{
			//echo "excedepago~".$importeCxC."~".$pagadoCxP."~".$arr2[4]."~".$i;
			alert("No existe el pago anticipado : "+arr[1] +" en el renglón "+arr[4] +".");
			return false;
		}
		else if(arr[0]=='docnocliente')
		{
			//echo "excedepago~".$importeCxC."~".$pagadoCxP."~".$arr2[4]."~".$i;
			alert("El documento  : "+arr[1] +" no este relacionado al cliente de la cuenta por cobrar en el renglón "+arr[4] +".");
			return false;
		}
		else if(arr[0]=='saldoinsuf')
		{
			//echo "excedepago~".$importeCxC."~".$pagadoCxP."~".$arr2[4]."~".$i;
			alert("El saldo del documento es mayor al disponible  en el renglón "+arr[4] +".");
			return false;
		}
		else if(arr[0]=='noexistenc')
		{
			//echo "excedepago~".$importeCxC."~".$pagadoCxP."~".$arr2[4]."~".$i;
			alert("No existe la nota de crédito : "+arr[1] +" en el renglón "+arr[4] +".");
			return false;
		}
		else
		{
			alert(ans);
			return false;
		}
		
	}
	return true;
}

function importarDatosDeCuenta(){
	abrirVentana('../popups/importaCuentasPorCobrar.php|800|400');
	return true;
}

/***********************************************************************************************************/
// Función para generar el reporte de bitácora
function generaBitacora(opcion){
	var fecha1 = document.getElementById("fechai").value;
	var fecha2 = document.getElementById("fechaf").value;
	if(fecha1=='' || fecha2==''){
		alert("El rango de fechas es requerido.");
		return;
	}
	var usuario = document.getElementById("usuario").value;
	window.open('../reportes/procesaReportes.php?parametros='+fecha1+'|'+fecha2+'|'+usuario+'&opcion='+opcion+'&idRep=bitacora&opcionales=','popup','top=0,left=0,toolbar=no,location=no,status=no,menubar=yes,scrollbars=yes,width=800,height=600,resizable=yes');
	return;
}

/***********************************************************************************************************/
// Funciones para operaciones entre combos múltiples
//	Compara dos opciones de combos diferentes por su valor, se le pasa el objeto select
function compareOptionValues(a, b){
	var sA = parseInt( a.value, 36 );
	var sB = parseInt( b.value, 36 );
	return sA - sB;
}
//	Compara dos opciones de combos diferentes por su descripcion o output, se le pasa el objeto select
function compareOptionText(a, b){
	var sA = parseInt( a.text, 36 );
	var sB = parseInt( b.text, 36 );
	return sA - sB;
}
//	Mueve los elementos seleccionados de un combo a otro
function moveDualList(srcList, destList, moveAll){
	if((srcList.selectedIndex == -1) && (moveAll == false)){
		return;
	}
	var newDestList = new Array( destList.options.length );
	var len = 0;
	for(len = 0; len < destList.options.length; len++){
		if(destList.options[ len ] != null){
			newDestList[ len ] = new Option( destList.options[ len ].text, destList.options[ len ].value, destList.options[ len ].defaultSelected, destList.options[ len ].selected );
		}
	}
	for(var i = 0; i < srcList.options.length; i++){
		if(srcList.options[i] != null && (srcList.options[i].selected == true || moveAll)){
			newDestList[ len ] = new Option( srcList.options[i].text, srcList.options[i].value, srcList.options[i].defaultSelected, srcList.options[i].selected );
			len++;
		}
	}
	newDestList.sort( compareOptionValues ); // BY VALUES
	for(var j = 0; j < newDestList.length; j++){
		if(newDestList[ j ] != null){
			destList.options[ j ] = newDestList[ j ];
		}
	}
	for(var i = srcList.options.length - 1; i >= 0; i--){
		if(srcList.options[i] != null && (srcList.options[i].selected == true || moveAll)){
			srcList.options[i] = null;
		}
	}
}
//	Selecciona todos los elementos de un select
function selectAll(combo){
	if(combo.options.length==0){
		alert("No hay elementos que seleccionar.");
		return false;
	}else{
		for(var x=0;x<combo.options.length;x++)
			combo.options[x].selected=true;
	}
	return true;
}
/***********************************************************************************************************/
// Funciones para cambiar las opciones de un combo a partir de la entrada de un inputbox...intento de suggest
// Parametros:
//	cadena: cadena que se va evaluando en el inputbox de buscar
//	tabla: nombre de la tabla a buscar
//	grupo: si existe un grupo de radio buttons para buscar por determinado criterio
//	selOp: que combo cambiará cuando regresen las respuestas, este mismo parametro se regresa del php
//	x,y: coordenadas en donde aparecerá la imagen del relojito
function buscaDatosCombo(cadena,tabla,grupo,selOp,x,y,e)
{
	var porCual = 0;
	var key;
	key= e.which?e.which:e.keyCode;
	if(grupo)
	{
		for(var i=0; i<grupo.length; i++)
		{
			if(grupo[i].checked)
			{
				porCual = grupo[i].value;
				break;
			}
		}
	}
	var arrSelects = document.getElementsByTagName("select");
	var objeto = arrSelects[selOp];
	url="phpAjax/cambiaDatosCombo.php?subcadena="+cadena+"&tabla="+tabla+"&porCual="+porCual+'&selOp='+selOp;	
	loadingOn(x,y,'loading1');
	solicitaDatos(url);	
	if(key=='40')
	{
		if(objeto.options[0])
		{
			objeto.options[0].selected = true;
			objeto.focus();
		}
	}
	return true;
}

function solicitaDatos(url){
	http_request = false;
	if(window.XMLHttpRequest){ // mozilla, netscape, opera...
		http_request = new XMLHttpRequest();
		if(http_request.overrideMimeType){
			http_request.overrideMimeType('text/xml');
		}
	}else if(window.ActiveXObject){ // IE
		try{
			http_request = new ActiveXObject("Msxml2.XMLHTTP");
		}catch(e){
			try{
				http_request = new ActiveXObject("Microsoft.XMLHTTP");
			}catch(e){
			}
		}
	}
	if(!http_request){
		alert('Falla :( No es posible crear una instancia XMLHTTP');
		return false;
	}
	http_request.onreadystatechange = opcionesRegresadas;
	http_request.open('GET', url, true);
	http_request.send(null);
}

function opcionesRegresadas(){
	if(http_request.readyState == 4){
		if(http_request.status == 200){
			loadingOff('loading1');
			var arrSelects = document.getElementsByTagName("select");
			var opx=http_request.responseText.split('~');
			var opciones=opx[0].split('|');
			var objeto = arrSelects[opx[1]];
			objeto.options.length=0;
			for(var i=0; i<opciones.length; i++){
				var par=opciones[i].split('#');
				objeto.options[i]=new Option(par[1],par[0]);
			}
			objeto.options.length--;
		}else{
			alert('Hubo problemas con la petición.');
		}
	}
}

function loadingOn(posX, posY, imagen){
	document.getElementById(imagen).style.position = "absolute";
	document.getElementById(imagen).style.top = posY;
	document.getElementById(imagen).style.left = posX;
	document.getElementById(imagen).style.display = "block";
}

function loadingOff(imagen){
	document.getElementById(imagen).style.display = "none";
}

/***********************************************************************************************************/
// Función para hacer dos checkboxes excluyentes, se le pasa como parametros los nombres de los campos a excluir
function excluyentes(cual, campo, campo2){
	var cmp = document.getElementById(campo);
	var cmpAux = document.getElementById(campo2);
	if(cual.name==campo){
		if(cmp.checked==true && cmpAux.checked==true)
			cmpAux.checked=false;
	}else{
		if(cmpAux.checked==true && cmp.checked==true)
			cmp.checked=false;
	}
	return true;
}
function acceptNum(e){
	var targ;
	if (!e) var e = window.event;
	if (e.target) targ = e.target;
	else if (e.srcElement) targ = e.srcElement;
	var cad=targ.value;
	//alert(cad);
	return(soloNumeros(e,cad));
}
function soloNumeros(e,valor){
	var arrValidos = new
	Array("1","2","3","4","5","6","7","8","9","0",".","-")
	var vFlag=false;
	var vcodeKey, vStringKey;
	vFlag=false;
	if(document.all) {
		vcodeKey = event.keyCode
		vStringKey = (String.fromCharCode(event.keyCode));
	}
	else if(document.layers) {
		vcodeKey = e.which
		vStringKey = String.fromCharCode(e.which);
	}
	else if(document.getElementById) {
		vcodeKey = (window.Event) ? e.which : e.keyCode;
		if (vcodeKey==0 || vcodeKey==8) {  
			vFlag=true; 
		}
		else {
			vStringKey=(String.fromCharCode(vcodeKey));
		}
	}
	
	if(vFlag==false) {
		for(i=0;i<arrValidos.length;i++) {
			if(vStringKey==arrValidos[i]) {
				vFlag=true;
				if (vStringKey=="."){
					if (valor.indexOf(".")!=-1){
						vFlag=false;
					}
				}
			}
		}
		if(vFlag==false) {
			if(document.all){
				return false;
				//event.returnValue = false;
			}
			else{
               return false;
			}
        }
	}
}

function addCommas(nStr){
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}

function ignoreCommas(string) {
	var temp = "";
	string = '' + string;
	splitstring = string.split(",");
	for(i = 0; i < splitstring.length; i++)
	temp += splitstring[i];
	return temp;
}

function roundit(Num, Places) {
   if (Places > 0) {
      if ((Num.toString().length - Num.toString().lastIndexOf('.')) > (Places + 1)) {
         var Rounder = Math.pow(10, Places);
         return Math.round(Num * Rounder) / Rounder;
      }
      else return Num;
   }
   else return Math.round(Num);
}

/***********************************************************************************************************/
// Funciones para seguridad, la página debe contener el control de ajax correspondiente, si se necesita personalizar hay que modificar validaPass.php en phpAjax
function habilitaEntrada(){
	dlg0.show();
	return true;
}

function validaEntrada(ruta,opcion){
	var formaPass = document.forms.formaPass;
	var user = formaPass.user.value;
	var pass = formaPass.pass.value;
	ajax_request('POST','text',ruta+'validaPass.php','user='+user+'&pass='+pass+'&opcion='+opcion,'validaEntrada2(ans)');
	return true;
}

function validaEntrada2(ans){
	//alert(ans);
	if(document.forms.forma_datos){
		var campoPermiso = document.forms.forma_datos.permiso;
	}
	if(document.forms.forma_porcentajes){
		var forma = document.forms.forma_porcentajes;
		var llave = document.getElementById('key');
	}
	var arr = ans.split('~');
	switch(arr[0]){
		case 'vacio':
			alert("Escriba un usuario y su contraseña.");
		break;
		case 'noexiste':
			alert("El usuario que acaba de indicar no existe, intente nuevamente.");
		break;
		case 'nopertenece':
			alert("El usuario no está autorizado para validar esta operación.");
		break;
		case 'adelante':
			if(arr[1]=='agregaProductoHoja')
			{
				var elementos = forma.elements.length-6;
				for(i=0; i<elementos; i++)
				{
					var elemento = forma.elements[i];
					if(elemento.name.search(/desc/)!=-1)
					{
						if(elemento.value>0)
						{
							forma.elements[i+1].readOnly = false;
							forma.elements[i+1].style.backgroundColor = "#FFEEEE";
						}
					}
				}
				forma.permiso.value = 1;
				llave.style.display = 'none';
				document.getElementById('hider0').click();
			}
			if(arr[1]=='autorizaDevolucion')
			{
				campoPermiso.value = '1';
				document.getElementById('hider0').click();
				needsDetalle(5);
			}
		break;
		case 'wrongpass':
			alert("Contraseña incorrecta, intente nuevamente.");
		break;
		default:
			alert("Datos incorrectos, imposible validar información. Intente nuevamente.");
		break;
	}
	return true;
}
/***********************************************************************************************************/
// Función para validar el dato que se haya introducido en un celda tipo lookup, se le pasa el nombre de la tabla, el id que corresponda y el objeto grid para obtener el valor de la celda
function esValido(tabla, campo, objGrid, funcion, tipo, posx, posy)
{	
	if(tipo=='A')
		var valor = celdaValorXY(objGrid, posx, posy);
	else if(tipo=='B')
	{
		//var arr = objGrid.getSelectedCellText().split('->');
		var valor = celdaValorXY(objGrid, posx, posy);
	}
	else
	{
		var arr = celdaValorXY(objGrid, posx, posy).split('~');
		var valor = arr[0];
	}
	if(valor == "")
		return true;
	var respuesta = ajaxR("phpAjax/validaOpcionSeleccionada.php?tabla="+tabla+"&campo="+campo+"&valor="+valor);	
	if(respuesta=="1")
	{
		eval(funcion);		
		return true;
	}
	return false;
}

/***********************************************************************************************************/
// Funciones para aplicarle una máscara a un número en una celda tipo text y otra para quitarle diche máscara
function aplicarMascara(objeto)
{
	var valor = objeto.value;
	var arr = objeto.value.split(',');

	if(arr[1] == 2)
		var mascara = '###,##0.00';
	else if(arr[1] == 4)
		var mascara = '###,##0.0000';
	else
		var mascara = '###,##0.000';
		
	var separadorDecimales = '.';
	var separadorMiles = ',';
	objeto.value = formatNumber(valor,mascara,separadorDecimales,separadorMiles);
	return true;
}

function quitarMascara(objeto){
	var valor = objeto.value;
	var separadorDecimales = '.';
	return maskedToNumber(valor,separadorDecimales);
}

function aplicaMascaraDatos(forma){
	var numElementos = forma.elements.length;
	for(var i=0; i<numElementos; i++){
		if(forma.elements[i].decimales){
			var arr = forma.elements[i].decimales.split(',');
			if(arr[1]==2 || arr[1]==3)
				aplicarMascara(forma.elements[i]);
		}
	}
	return true;
}

function validaCar(obj, eve)
{
	var key=0;
	key=(eve.which) ? eve.which : eve.keyCode;
	if(key == 219)
	{
		obj.value=obj.value.replace("'","");
	}
	if(key == 13)
	{		
		eve.returnValue=false;
	}
}

/*function decimales(obj, eve)
{
	var entero;	
	var puntdec;
	var key=0;
	key=(eve.which) ? eve.which : eve.keyCode;
	var lastdato=String.fromCharCode(key);	
	puntodec=0;	
	entero=parseInt(lastdato);
	for(var j=0;j<obj.value.length;j++)
		if(obj.value.charAt(j) == '.')		
			puntodec++;
	if(isNaN(entero) && lastdato != '.' && lastdato != '-')
		eve.returnValue=false;
	if(lastdato == '.' && puntodec > 1)	
		eve.returnValue=false;
	if(lastdato == '-' && obj.value.length != 0)
		eve.returnValue=false;
	//alert('Dato E('+entero+')-Evaluado('+lastdato+')-punto decimal('+puntodec+')');	
}*/

function maskedToNumber(number, decimalSeparator)
{
	number+="";
	var percentage=(number.indexOf("%") != -1);
	if ("." == decimalSeparator)
	{
		number = number.replace(/[^0-9a-z\.-]/g,'');
	}
	else
	{
		var re = new RegExp("[^0-9a-z" + decimalSeparator + "-]", "g");
		number=number.replace(re, '');
		re = new RegExp(decimalSeparator, "g");
		number=number.replace(re, ".");
	}
	number = Number(number);
	return percentage ? number/100 : number; 
}

function formatNumber(dblNumber, bstrFormat, decimalSeparator, groupingSeparator) 
{
	try 
	{
		dblNumber+="";		 
		dblNumber=maskedToNumber(dblNumber,decimalSeparator)+"";
		
		if (dblNumber == "")
		{
			return "";
		}
		
		if (isNaN(Number(dblNumber)))
		{
			return "false";
		}
			
		var xmlDoc = new ActiveXObject("Msxml2.DOMDocument.3.0");
		var xslDoc = new ActiveXObject("Msxml2.DOMDocument.3.0");
		

		var aXml = [];
		aXml.push("<?xml version='1.0' encoding='ISO-8859-1'?>");
		aXml.push("<xsl:stylesheet version='1.0' xmlns:xsl='http://www.w3.org/1999/XSL/Transform'>");
		aXml.push("<xsl:output method='xml' version='4.0' omit-xml-declaration='yes' />");
		aXml.push("<xsl:decimal-format name=\"myNumber\" decimal-separator='" + decimalSeparator + "' grouping-separator='" + groupingSeparator + "' />");
		aXml.push("<xsl:template match='/'><xsl:value-of select='format-number(" + dblNumber + ", \"" + bstrFormat + "\", \"myNumber\")' /></xsl:template></xsl:stylesheet>");
		
		xmlDoc.loadXML('<root/>');
		xslDoc.loadXML(aXml.join(''));

		var result = xmlDoc.transformNode(xslDoc);
		
		xmlDoc = null;
		xslDoc = null;
		return result;
	} 
	catch (err) 
	{
	}
}
//segunda parte de script para los encabezados

//valida si el caracter digitado es numero
//e evento punto = 1 si permite decimales , id es el id del campo
function validarNumero(e,punto,id){
    var valor="";
	
	tecla_codigo = (document.all) ? e.keyCode : e.which;
	valor=document.getElementById(id).value;
	
	
	if(tecla_codigo==8 || tecla_codigo==0)return true;
	if (punto==1)
		patron =/[0-9\-.]/;
	else
		patron =/[0-9\-]/;
	
		
	//validamos que no existan dos puntos o 2 -
	tecla_valor = String.fromCharCode(tecla_codigo);
	//46 es el valor de "."
	if (valor.split('.').length>1 && tecla_codigo==46)		
	{
		return false;
	}
	else if (valor.split('-').length>1 && tecla_codigo==45)		
	{
		//45 es el valor de "-"
		return false;
	}
	
	
	return patron.test(tecla_valor);

}

function isNumerico(valor)
{
	
}

//funciones para validar fecha valida
function esFechaValida(fecha){
    if (fecha != undefined && fecha.value != "" )
	{
        
		//solo limitamos a los primeros diez caracteres de la fecha
		fecha=fecha.substring(0,10);
		if (!/^\d{2}\/\d{2}\/\d{4}$/.test(fecha))
		{
           //alert(fecha);
		    return false;
        }
				
        var anio  =  parseInt(fecha.substring(6),10);
        var mes  =  parseInt(fecha.substring(3,5),10);
        var dia=  parseInt(fecha.substring(0,2),10);
		
		switch(mes){
			case 1:
			case 3:
			case 5:
			case 7:
			case 8:
			case 10:
			case 12:
				numDias=31;
				break;
			case 4: case 6: case 9: case 11:
				numDias=30;
				break;
			case 2:
				if (comprobarSiBisisesto(anio)){ numDias=29 }else{ numDias=28};
				break;
			default:
			   
				return false;
		}
    
		if (dia>numDias || dia==0){
        	return false;
	    }
    	//para terminar
		return true;
	
	}
}

function esHora(tiempo){
    if (tiempo != undefined && tiempo.value != "" )
	{
        
		
		//alert(tiempo);
		//solo limitamos a los primeros diez caracteres de la fecha
		tiempo=tiempo.substring(0,8);
		
		if (!/^\d{2}\:\d{2}\:\d{2}$/.test(tiempo))
		{
          	
		    return false;
        }
		//alert( "tiempo 2 "+tiempo);	
		
        var hora  =  parseInt(tiempo.substring(0,2),10);
  	    
		var minuto  =  parseInt(tiempo.substring(3,5),10);
        var segundo=  parseInt(tiempo.substring(6),10);
		
		//alert(hora+'  :  '+minuto+'  :  '+segundo)
		
		if(hora<0 || hora>24){
			return false;
		}
    	
		if(minuto<0 || minuto>59){
			return false;
		}
    		
		if(segundo<0 || segundo>59){
			return false;
		}
    	//para terminar
		return true;
	
	}
}


function comprobarSiBisisesto(anio)
{
	if ( ( anio % 100 != 0) && ((anio % 4 == 0) || (anio % 400 == 0)))
		return true;
	else 
    	return false;
}


function validaMail(emailStr) 
{
	var emailPat=/^(.+)@(.+)$/
	var specialChars="\\(\\)<>@,;:\\\\\\\"\\.\\[\\]"
	var validChars="\[^\\s" + specialChars + "\]"
	var quotedUser="(\"[^\"]*\")"
	var ipDomainPat=/^\[(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})\]$/
	var atom=validChars + '+'
	var word="(" + atom + "|" + quotedUser + ")"
	var userPat=new RegExp("^" + word + "(\\." + word + ")*$")
	var domainPat=new RegExp("^" + atom + "(\\." + atom +")*$")
	var matchArray=emailStr.match(emailPat)
	if (matchArray==null) {
		return false
	}
	var user=matchArray[1]
	var domain=matchArray[2]

	if (user.match(userPat)==null) {
		return false
	}

	var IPArray=domain.match(ipDomainPat)
	if (IPArray!=null) {
		for (var i=1;i<=4;i++) {
			if (IPArray>255) {
				return false
			}
		}
		return true
	}

	var domainArray=domain.match(domainPat)
	if (domainArray==null) {
		return false
	}

	var atomPat=new RegExp(atom,"g")
	var domArr=domain.match(atomPat)
	var len=domArr.length
	if (domArr[domArr.length-1].length<2 ||	domArr[domArr.length-1].length>3) {
	return false
	}

	if (len<2) {
	return false
	}
	return true;
}  

//--------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------

/***********************************************************************************************************/
// Funciones para cambiar las opciones de un combo a partir de la entrada de un inputbox...intento de suggest
// Parametros:
//	cadena: cadena que se va evaluando en el inputbox de buscar
//	tabla: nombre de la tabla a buscar
//	grupo: si existe un grupo de radio buttons para buscar por determinado criterio
//	selOp: que combo cambiará cuando regresen las respuestas, este mismo parametro se regresa del php
//	x,y: coordenadas en donde aparecerá la imagen del relojito
function buscaDatosComboC(cadena,idele,e)
{
	var porCual = 0;
	var key;
	key= e.which?e.which:e.keyCode;
	
	if(cadena.length>=1 && cadena.length<=3)
		return true;

	
	var objetoTipo = document.getElementById('tipocredito');
	
	//var objeto = document.getElementById('id_cliente');
	url="../pagos/buscaDatos.php?subcadena="+cadena+"&tcr="+objetoTipo.value;	
	solicitaDatosC(url);	
	return true;
}

function solicitaDatosC(url){
	http_request = false;
	if(window.XMLHttpRequest){ // mozilla, netscape, opera...
		http_request = new XMLHttpRequest();
		if(http_request.overrideMimeType){
			http_request.overrideMimeType('text/xml');
		}
	}else if(window.ActiveXObject){ // IE
		try{
			http_request = new ActiveXObject("Msxml2.XMLHTTP");
		}catch(e){
			try{
				http_request = new ActiveXObject("Microsoft.XMLHTTP");
			}catch(e){
			}
		}
	}
	if(!http_request){
		alert('Falla :( No es posible crear una instancia XMLHTTP');
		return false;
	}
	http_request.onreadystatechange = opcionesRegresadasC;
	http_request.open('GET', url, true);
	http_request.send(null);
}

function opcionesRegresadasC(){
	
	if(http_request.readyState == 4){
		
		if(http_request.status == 200){
			
			var opx=http_request.responseText.split('~');
			var opciones=opx[0].split('|');
			var objeto = document.getElementById('id_contratos_dis');
			objeto.options.length=0;
			for(var i=0; i<opciones.length; i++){
				var par=opciones[i].split('#');
				objeto.options[i]=new Option(par[1],par[0]);
			}
			objeto.options.length--;
		}else{
			alert('Hubo problemas con la petición.');
		}
	}
}

function buscaDatosComboGral(cadena, tipo, obj,e, tcr, npro)
{
	var porCual = 0;
	var key;
	key= e.which?e.which:e.keyCode;
	
	if(cadena.length < 1)
		return true;
	//alert(tipo);
	if(tipo == 1 || tipo == 2)
	{
		if(npro == 5)
			url="buscaDatos.php?id_anca=SI&subcadena="+cadena+"&tcr="+tcr+"&tipo="+tipo;
		else
		{
			if(tcr == 3)
				url="buscaDatos.php?id_solicitud=SI&subcadena="+cadena+"&tcr="+tcr+"&tipo="+tipo+"&anca=YES&contrato=NO";
			else
				url="buscaDatos.php?id_solicitud=SI&contrato=NO&subcadena="+cadena+"&tcr="+tcr+"&tipo="+tipo;
		}
	}
	if(tipo == 5 || tipo ==6)
	{
		url="buscaDatos.php?subcadena="+cadena+"&tcr="+tcr+"&tipo="+tipo;
	}
	if(tipo == 7)
	{
		url="buscaDatos.php?subcadena="+cadena+"&tcr="+tcr+"&tipo="+tipo;
	}
	aux=ajaxR(url);	
	//alert(aux);
	ax=aux.split('|');		
	obj.length=0;	
	if(ax.length > 1)
	{
		for(i=1;i<ax.length;i++)
		{
			if(ax[i].length > 0)
			{
				ax1=ax[i].split('~');
				obj.options[i-1]=new Option(ax1[1],ax1[0]);
			}
		}
	}
	
	return true;
}

//No permite borrar datos ya guardados
function borraNoGuardados(grid, pllave, pos)
{
	var idGrid=celdaValorXY(grid,pllave,pos);
	if(idGrid == 'NO')
		return true;
	else
		alert('No es posible eliminar datos ya guardados');
}

//con valor punto = 0 no admite espacios
function validarCaracter(e,punto,id){
    var valor="";
	
	tecla_codigo = (document.all) ? e.keyCode : e.which;
	
	valor=document.getElementById(id).value;

	if(tecla_codigo==8 || tecla_codigo==0 )return true;
	
	patron =/[A-Z, a-z,0-9\-.éáíóú_.#@ñÑ]/;
	
	if (punto==0 &&  tecla_codigo==32) 
	{
		
		return false;
	}
	
	//validamos que no existan dos puntos o 2 -
	tecla_valor = String.fromCharCode(tecla_codigo);
	
	return patron.test(tecla_valor);

}

function ocultaDiv(div, val, obj)
{
	var odiv=document.getElementById(div);	
	if(obj.checked == val)	
		odiv.style.display="block";
	else
		odiv.style.display="none";
}

//function muestraDiv(this.value,'tipo_de_cambio')
function muestraDiv(valor,id_mostrar)
{
	//alert('das');
	var odiv=document.getElementById("m1");	
	//si son pesos ocultamos el div
	if(valor == '1')	
		odiv.style.display="none";
	else
		odiv.style.display="block";
	
}

//sugiereFecha(this.value,'fecha_de_vencimiento','cxp');

function InsertaVacio(grid, val, obj)
{	
	if(obj.checked == val)
	{
		if(NumFilas(grid) == 0)
			InsertaFila(grid);
	}
	else
	{
		if(NumFilas(grid) > 0)
			vaciaGrid(grid);
		//alert(NumFilas(grid));
	}
}


//Funcion que llena un combo a partir de una cadena estandar de combos
function llenaCombo(combo, cadena)
{
	if(!combo)	
		return false;
	combo.options.length=0;
	var aux=cadena.split('|');
	if(aux[0] != 'exito')
	{
		alert(cadena);
		return false;
	}
	for(var i=1;i<aux.length;i++)
	{
		var ax=aux[i].split('~');
		combo.options[i-1]=new Option(ax[1], ax[0]);
	}
}

function validaFechaCampoGrid(tabla,x,y)
{	
	//alert(celdaValorXY(tabla, x, y));
	return esFechaValida(celdaValorXY(tabla, x, y));
}

/*
var asciiArray = new Array(
	' ', '!', '"', '#', '$', '%', '&', "'", '(', ')', '*', '+', ',', '-',
	'.', '/', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', ':', ';',
	'<', '=', '>', '?', '@', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I',
	'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W',
	'X', 'Y', 'Z', '[', '\\', ']', '^', '_', '`', 'a', 'b', 'c', 'd', 'e',
	'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's',
	't', 'u', 'v', 'w', 'x', 'y', 'z', '{', '|', '}', '~', '', 'Ç', 'ü',
	'é', 'â', 'ä', 'à', 'å', 'ç', 'ê', 'ë', 'è', 'ï', 'î', 'ì', 'Ä', 'Å',
	'É', 'æ', 'Æ', 'ô', 'ö', 'ò', 'û', 'ù', 'ÿ', 'Ö', 'Ü', 'ø', '£', 'Ø',
	'×', 'ƒ', 'á', 'í', 'ó', 'ú', 'ñ', 'Ñ', 'ª', 'º', '¿', '®', '¬', '½',
	'¼', '¡', '«', '»', '_', '_', '_', '¦', '¦', 'Á', 'Â', 'À', '©', '¦',
	'¦', '+', '+', '¢', '¥', '+', '+', '-', '-', '+', '-', '+', 'ã', 'Ã',
	'+', '+', '-', '-', '¦', '-', '+', '¤', 'ð', 'Ð', 'Ê', 'Ë', 'È', 'i',
	'Í', 'Î', 'Ï', '+', '+', '_', '_', '¦', 'Ì', '_', 'Ó', 'ß', 'Ô', 'Ò',
	'õ', 'Õ', 'µ', 'þ', 'Þ', 'Ú', 'Û', 'Ù', 'ý', 'Ý', '¯', '´', '­', '±',
	'_', '¾', '¶', '§', '÷', '¸', '°', '¨', '·', '¹', '³', '²', '_', ' ');
*/
/***********************************************************************************************************/

function buscaProductos(grid, pos)
{		
	window.open("../buscaProductos.php?posGrid="+pos+"&grid="+grid,"","width=670,height=530,scrollbars=NO");
}

function Foco3(pos, grid)
{
	Foco(grid, 4, pos);
}

function lanzaCatalogoCC(pos, grid)
{	
	window.open("../catalogosCC/catalogosCC.php?pos="+pos+"&grid="+grid,"","width=825,height=420");
}

function validaPorc(tipo, tabla, x, y)
{
	if(x != -1)	
		var val=celdaValorXY(tabla, x, y);
	else
		val=tabla.value;
	var aux=ajaxR('../ajax/ajaxClientes.php?tipo='+tipo+'&val='+val);
	if(aux.split('|')[0] != 'exito')
	{
		alert(aux);
		if(x != -1)	
			valorXY(tabla, x,y,'');
		else
			tabla.value='';
		return false;			
	}
	if(aux.split('|')[1] == '0')
	{
		alert('El dato insertado rebasa el maximo establecido, valor maximo permitido: '+aux.split('|')[2]);
		if(x != -1)	
		{
			valorXY(tabla, x,y,'');
			Foco(tabla, x, y);
		}
		else
		{
			tabla.value='';
			tabla.focus();
		}
		return false;
	}
}

function refresca(combo, val, tabla)
{
	var aux=ajaxR('../ajax/getComboDependiente.php?tabla='+tabla+'&campo='+combo+'&id='+val);
	var f= document.forma_datos;
	ax=aux.split('|');
	if(ax[0] != 'exito')
	{
		alert(aux);
		return false;
	}
	//alert('../ajax/getComboDependiente.php?tabla='+tabla+'&campo='+combo+'&id='+val+' => '+aux);
	f.elements["campo_"+combo].length=0;	
	for(i=1;i<ax.length;i++)
	{
		//alert(ax[i]);
		a=ax[i].split('~');
		f.elements["campo_"+combo].options[i-1]=new Option(a[1],a[0]);
	}
}

function calculaedad()
{
	var obj=document.getElementById("edad");
	if(obj)
	{
		var objfech=document.getElementById("fecha_nacimiento");
		if(objfech)
		{
			var fenac=objfech.value;
			var arr=fenac.split("-");
			if(arr[0]!="")
			{
				var feact=new Date();
				var anioact=feact.getFullYear();
				var mesact=(feact.getMonth())+1;
				var diaact=feact.getDate();
				var edad=anioact-parseInt(arr[0]);
				var mesnac=parseInt(arr[1]);
				var dianac=parseInt(arr[2]);				
				if(mesact<mesnac)
					edad--;
				else if(mesact=mesnac)
				{
					if(diaact<dianac)
						edad--;
				}
				obj.value=edad;										
			}
		}
	}
	return true;
}

function limpiaCombo(objCombo)
{
	var numoptions=objCombo.options.length;
	for(var i=0;i<numoptions;i++)
	{
		objCombo.options[0]=null;
	}
}

/*------------------------------Funciones especificas para el combo buscador-----*/
/*function activaBuscador(objInput,evento)
{
	var nomcampo=objInput.name;
	var arr=nomcampo.split("_");
	nomcampo=arr[1]+"_"+arr[2];
	var objdiv=document.getElementById("div"+nomcampo);		
	if(!objdiv)
		return false;		
	if(evento.keyCode==9)
	{
		ocultaCombobusc(objInput.name);		
		return false;
	}
	var objh=document.getElementById("h"+nomcampo);
	if(objh&&evento.keyCode!=40)
	{
		objh.value="";
		objh.value=objInput.value;		
	}	
				
	if(evento.keyCode==40&&objdiv.style.display=="block")
	{
		FocoComboBuscador(nomcampo);
		return false;
	}
	if(evento.keyCode==40)
	{
		var depende=(objInput.getAttribute("depende"))?objInput.getAttribute("depende"):"";
		var cadbusq="";
		if(depende!=""&&depende!=0)
		{
			var arrdepen=depende.split("|");
			for(var i=0; i<arrdepen.length;i++)
			{
				if(arrdepen[i].indexOf("~")!=-1)
				{
					var arr=arrdepen[i].split("~");
					var dependencia=arr[0];
					var campodepen=arr[1];
				}
				else
				{
					var dependencia=arrdepen[i];
					var campodepen="";
				}
				var arrnomde=objInput.name.split("_");
				nomde=arrnomde[1]+"_"+dependencia;
				var objvaldep=document.getElementsByName(nomde)[0];				
				if(objvaldep)
				{
					if(objvaldep.value!="")
					{
						cadbusq=objvaldep.value;
						var numdep=dependencia;
					}
				}
				if(objvaldep.value!="")
					break;
			}			
		}
		if(cadbusq!="")
		{
			muestraBuscador(objInput);
			ComboBuscador(objInput);
		}
	}
	if((evento.keyCode==40&objInput.value.length>=4)||objInput.value.length>=3)
	{
		muestraBuscador(objInput);
		ComboBuscador(objInput);
	}
	else if(objdiv.style.display=="block"&&objInput.value.length>=3)
	{
		ComboBuscador(objInput);
	}	
	return true;
}*/



function FocoComboBuscador(nomcampo)
{
	var objsel=document.getElementById("sel"+nomcampo)
	if(objsel)
	{
		if(objsel.selectedIndex==-1)
			objsel.selectedIndex=0;
		objsel.focus();
	}
}

function ComboBuscador(objInput)
{
	var nomcampo=objInput.name;
	var arr=nomcampo.split("_");
	nomcampo=arr[1]+"_"+arr[2];
	var objselec=document.getElementById("sel"+nomcampo);
	//alert("sel"+nomcampo);
	if(objselec)
	{
		if(!objselec)
				return false;
		var lon=objselec.length;
		for(var i=0;i<lon;i++)
			objselec.options[0]=null;
		var url=objInput.getAttribute("datosdb");
		if(url.length>0)
		{
			url+=objInput.value;
			var depende=(objInput.getAttribute("depende"))?objInput.getAttribute("depende"):"";
			if(depende!=""&&depende!=0)
			{
				var arrdepen=depende.split("|");
				var cadbusq="";
				for(var i=0; i<arrdepen.length;i++)
				{
					if(arrdepen[i].indexOf("~")!=-1)
					{
						var arr=arrdepen[i].split("~");
						var dependencia=arr[0];
						var campodepen=arr[1];
					}
					else
					{
						var dependencia=arrdepen[i];
						var campodepen="";
					}
					var arrnomde=objInput.name.split("_");
					nomde=arrnomde[1]+"_"+dependencia;
					var objvaldep=document.getElementsByName(nomde)[0];				
					if(objvaldep)
					{
						if(objvaldep.value!="")
						{
							cadbusq=objvaldep.value;
							var numdep=dependencia;
						}
					}
					if(objvaldep.value!="")
						break;
				}							
				if(objvaldep)
				{
					url+="&depende="+(parseInt(numdep)+1)+"&valordep="+cadbusq;
					if(campodepen!="")
						url+="&nom_dependencia="+campodepen;
				}
			}
			var resp=ajaxR(url);
			//alert(url);
			var arr=resp.split("|");
			if(arr[0]!="exito")
			{
				alert(resp);
				return false;
			}
			var num=parseInt(arr[1]);
			if(num<=0)
			{
				var nombre=objInput.name.split("_");
				nombre=nombre[1]+"_"+nombre[2];
				ocultaCombobusc(nombre);
				return false;
			}
			var objselec=document.getElementById("sel"+nomcampo);			
			for(var i=2;i<(num+2);i++)
			{
				var arrOpciones=arr[i].split("~");
				objselec.options[i-2]=new Option(arrOpciones[1],arrOpciones[0]);
			}
		}
	}
	return true;
}

function muestraBuscador(objInput)
{
	var nomcampo=objInput.name;
	var arr=nomcampo.split("_");
	nomcampo=arr[1]+"_"+arr[2];
	var objdiv=document.getElementById("div"+nomcampo)
	if(objdiv)
	{
		if(objdiv.style.display=="none")
		{
			objdiv.style.display="block";
			objdiv.style.visibility="visible";		
			var top=objdiv.offsetTop;
			var altura=objInput.offsetHeight;
			var y=posicionObjeto(objInput)[1];			
			//if(navigator.appName=="Microsoft Internet Explorer")
			top+=2;
			//if(top<(y+altura))
			//{
				top+=altura;
				top+="px";
				objdiv.style.top=top;
			//}
		}		
	}
	return true;
}

function asignavalorbusc(campo)
{
	var objsel=document.getElementById("sel"+campo);
	if(objsel)
	{
		var valor=objsel.value;
		var objcampo=document.getElementsByName("v_"+campo)[0];
		if(objcampo)
		{
			var seleccionado=objsel.selectedIndex;
			var objseleccionado=objsel.options[seleccionado];
			var visible=objseleccionado.text;
			var oculto=objseleccionado.value;
			var objh=document.getElementById("h"+campo);
			if(objh)
			{
				objh.value=oculto;
				objcampo.value=visible;				
				var funciononchange=(objcampo.getAttribute("on_change"))?objcampo.getAttribute("on_change"):"";
				if(funciononchange.indexOf("#")!=-1)
					funciononchange=funciononchange.replace('#',objh.id);
				ocultaCombobusc(campo);
				eval(funciononchange);
			}
		}
	}
	return true;
}

function ocultaCombobusc(campo)
{
	var objdiv=document.getElementById("div"+campo);
	if(!objdiv)
	{
		var campo=campo.split("_");
		campo=campo[1]+"_"+campo[2];
		var objdiv=document.getElementById("div"+campo);
	}
	if(objdiv)
	{
		if(objdiv.style.display=="block")
		{
			var objInput=document.getElementsByName("v_"+campo)[0];
			if(objInput)
			{
				var top=objdiv.offsetTop;
				top-=2;
				var altura=objInput.offsetHeight;
				top-=altura;
				top+="px";
				objdiv.style.top=top;
			}
			objdiv.style.display="none";
			objdiv.style.visibility="hidden";			
		}
	}
	return true;
}

function posicionObjeto(obj) {
    var left = 0;
      var top = 0;
      if (obj.offsetParent) {
            do {
                  left += obj.offsetLeft;
                  top += obj.offsetTop;
            } while (obj = obj.offsetParent);
      }
      return [left,top];
}

function botonBuscador(nomcampo)
{
	var obj=document.getElementsByName("v_"+nomcampo)[0];
	if(obj)
	{
		var evento=new Object();
		evento.keyCode="40";
		activaBuscador(obj,evento)
	}
}

function teclaCombo(nom_campo,evento)
{
	if(evento.keyCode==13||evento.keyCode==9)
	{
		asignavalorbusc(nom_campo);
		if(evento.keyCode==13)
		{
			var nom_sig=nom_campo.split("_");
			nom_sig=nom_sig[0]+"_"+(parseInt(nom_sig[1])+1);
			var obj=document.getElementsByName("v_"+nom_sig)[0];
			if(obj)
				obj.focus();
			else
			{
				obj=document.getElementsByName(nom_sig)[0];
				if(obj)
					obj.focus();			
			}
		}
	}
	return true;
}


function Redirecciona(objA)
{
	var url=objA.direccion?objA.direccion:objA.getAttribute("direccion");
	if(!url)
		return false;
	if(confirm("Aún no ha guardado el registro.\n ¿Desea salir de esta pantalla?"))
	{
		location.href=url;
	}
	return true;
}

function SalirProceso(objA)
{
	var url=objA.direccion?objA.direccion:objA.getAttribute("direccion");
	if(!url)
		return false;
	if(confirm("Aún no se ha terminado el proceso.\n ¿Desea salir de esta pantalla?"))
	{
		location.href=url;
	}
	return true;
}



function validateCaracteres(e, obj)
{	

	tecla_codigo = (document.all) ? e.keyCode : e.which;
	valor=obj.value;
	
	
	if(tecla_codigo==8 || tecla_codigo==0)
		return true;		
		
	tecla_valor = String.fromCharCode(tecla_codigo)
	

	var RegExPattern = /[A-Z, a-z,0-9\-.éáíóú_.#@ñÑ]/; 	
	//alert(tecla_valor.match(RegExPattern))
	
	return RegExPattern.test(tecla_valor);
}

String.prototype.removeAccents = function ()
{
	var r = {
			'À':'A','Â':'A','Ã':'A','Ä':'A','Å':'A','Á':'A',
			'Æ':'E','È':'E','É':'E','Ê':'E','Ë':'E',
			'Ì':'I','Î':'I','Í':'I',
			'Ò':'O','Ó':'O','Ô':'O','Ö':'O',
			'Ù':'U','Ú':'U','Û':'U','Ü':'U',
			'Ñ':'N'};
	
	return this.replace(/[ÁÀÂÃÄÅÆÈÉÊËÌÍÎÒÓÔÖÙÚÛÜÑ]/gi, function(m){
		var ret = r[m.toUpperCase()];
					
		if (m === m.toLowerCase())
			ret = ret.toLowerCase();
			
		return ret;
	})
}

function formatoMayus(obj)
{
	obj.value=obj.value.removeAccents()		
	obj.value=obj.value.toUpperCase()
}


function nuevoGridFila(grid)
{
	
	InsertaFila(grid);
	
	 /*Excepcion para Tabla Producto
	 Agrega el tipo de producto seleccionado en encabezado para seleccionar la presentacion en el grid
	 *****/
	  var tabla = grid;
	  if(tabla == 'detalleproducto'){ 
			if(document.forma_datos != null){
		var f=document.forma_datos;	
		var id = f.id_producto_tipo_default.options[f.id_producto_tipo_default.selectedIndex].value;  	
		var nfil=NumFilas(tabla);
			 	for(var i=0;i<nfil;i++)
	           {
				valorCeldaXY(tabla,2,i,id);//tipo de producto
	          }
		 }
	}
}


function getComboEnlazado(tipoElemento, tabla, posicionCampoTabla, valorElemento)
{
	//alert(tipoElemento + " - " + tabla + " - " + posicionCampoTabla + " - " + valorElemento);
	var parametros = "";
	var datos = Array();
	var cadDatos = "";
	var idElemento = "";
	
	parametros = "tabla=" + tabla + "&valorElemento=" + valorElemento + "&posicionCampoTabla=" + posicionCampoTabla;
	var resp = ajaxR("../ajax/getComboEnlazado.php?" + parametros);	
	datos = jQuery.parseJSON(resp);
	
	switch(tipoElemento)
	{
		case 1:	//ELEMENTO TIPO COMBO			
			if (datos != null)
			{
				for (var i = 0; i < datos.length; i++)
				{
					cadDatos += "<option value='" + datos[i].valor + "'>" + datos[i].nombre + "</option>";	
					idElemento = datos[i].campo;			
				}
				$("#" + idElemento).html("");					
				$("#" + idElemento).html(cadDatos);
			}
			else
			{
				idElemento = datos[0].campo;
				alert(idElemento);
				$("#" + idElemento).html("");
			}
			break;
	}
}

/**********************************************************************/
function IsNumberDecimal(evt)
{
    var nav4 = window.Event ? true : false;
    var key = nav4 ? evt.which : evt.keyCode;
    return ((key = 48 && key <= 57) || key == 46);

}

function IsNumberInteger(evt)
{
    var nav4 = window.Event ? true : false;
    var key = nav4 ? evt.which : evt.keyCode;
    return ((key = 48 && key <= 57));

}

function IsLetter(evt)
{
    var nav4 = window.Event ? true : false;
    var key = nav4 ? evt.which : evt.keyCode;
    return ((key = 65 && key <= 90) || (key = 97 && key <= 122));
}

function IsAlpha(evt)
{
    var nav4 = window.Event ? true : false;
    var key = nav4 ? evt.which : evt.keyCode;
    return ((key = 65 && key <= 90) || (key = 97 && key <= 122) || (key = 48 && key <= 57));
}

function ValidateMail(id)
{
    if(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test( $("#"+id).val()))
    {
        removeColor(id);
    }
    else
    {
        addColor(id);
        alert('No es un correo valido');        
    }
}

function validateSize(idElement, elementString, maxLength, type)
{
    var isOk = true;
    switch (type)
    {
        case 'text':
            elementString = $.trim(elementString);
            if (elementString.length == 0 || elementString.length > maxLength || elementString == '')
            {
                addColor(idElement);
                isOk = false;
            }
            else
            {
                removeColor(idElement)
            }
            break;
        case 'slc':
            if (elementString == '0')
            {
                addColor(idElement);
                isOk = false;
            }
            else
            {
                removeColor(idElement)
            }
            break;
        default:
            break
            
    }
    return isOk;
}

function addColor(id)
{	
    if ($("#"+id).hasClass("fieldOk"))
    {
        $("#"+id).removeClass('fieldOk');
    }
    $("#"+id).addClass('fieldFail');    
}
	
function removeColor(id)
{
    if ($("#"+id).hasClass("fieldFail"))
    {
        $("#"+id).removeClass('fieldFail');
    }
    $("#"+id).addClass('fieldOk');    
}

function CompararFechas(fecha, fecha2)
{   
    var xMes=fecha.substring(3, 5);
    var xDia=fecha.substring(0, 2);
    var xAnio=fecha.substring(6,10);
	
    var yMes=fecha2.substring(3, 5);
    var yDia=fecha2.substring(0, 2);
    var yAnio=fecha2.substring(6,10);
    
    if (xAnio > yAnio)
    {
        return(true);
    }
    else
    {    
        if (xAnio == yAnio)
        {        
            if (xMes > yMes)
            {
                return(true);
            }
            if (xMes == yMes)
            {            
                if (xDia > yDia){
                    return(true);
                }
                else
                {                
                    return(false);
                }
            }
            else
            {
                return(false);
            }
        }
        else
        {        
            return(false);
        }
    }
}

function fechaDelDia()
{
	var f = new Date();
	var dia = f.getDate();
	var mes = (f.getMonth() +1);
	
	if (parseInt(dia) < 10)
	{
		dia = '0' + dia	;
	}
	
	if (parseInt(mes) < 10)
	{
		mes = '0' + mes	;
	}	
	
	return dia + "/" + mes + "/" + f.getFullYear();
}

/*COLOCAR VALOR EN UNA CELDA DEL GRID*/
function gridAsignarValorACelda(grid, posY, posXOrigen, posXDest){
	
	var id_evalua = celdaValorXY(grid, posXOrigen, posY);
	
	//aplicaValorXY(grid, posXDest, posY, id_evalua);	
	valorCeldaXY(grid,posXDest,posY,id_evalua); 
}

//PERMITE AGRUPAR EN UN SOLO CAMPO TODOS LOS VALORES NECESARIOS PARA LLENAR UN GRID
//EN BD REQUIERE strSplit("1|1|1000", '|', 3) PARA POSICION 3
function gridAsignaValorAuxCombo(grid, posY, posXs, posXdest){
	//posiciones sobre la fila de las que se obtendran los valores
	var n = posXs.split("|");
	var i = 0;
	var cadenaRet = "";
	
	for(i=0; i < n.length; i++){
		if(i > 0){
			cadenaRet += "|";
		}
		cadenaRet += celdaValorXY(grid, parseInt(n[i]), posY);
	}	
	valorCeldaXY(grid,posXdest,posY,cadenaRet); 	
}

//PARA DETALLE DE NOTA DE VENTA POR SERVICIO
function calcImporteDetNotaVenta(posY, grid){
	var descTotal = 0;
	var importe = 0;
	var total = 0;
	
	if(grid == 3){
		var valCantidad = celdaValorXY('detalleNotasServicios', 6, posY);
		var precioUnitario = celdaValorXY('detalleNotasServicios', 7, posY);
		var porcentajeDesc = celdaValorXY('detalleNotasServicios', 18, posY);
		var porcentajeDescAdicional = celdaValorXY('detalleNotasServicios', 19, posY);
		
		valCantidad = (valCantidad == "")? 0 : parseInt(valCantidad);
		precioUnitario = (precioUnitario == "")? 0 : parseFloat(precioUnitario);
		porcentajeDesc = (porcentajeDesc == "")? 0 : parseInt(porcentajeDesc);
		porcentajeDescAdicional = (porcentajeDescAdicional == "")? 0 : parseInt(porcentajeDescAdicional);
	}else if(grid == 4){
		var valCantidad = celdaValorXY('detalleNotasServiciosCitas', 8, posY);
		var precioUnitario = celdaValorXY('detalleNotasServiciosCitas', 9, posY);
		var porcentajeDesc = celdaValorXY('detalleNotasServiciosCitas', 20, posY);
		var porcentajeDescAdicional = celdaValorXY('detalleNotasServiciosCitas', 21, posY);
		
		valCantidad = (valCantidad == "")? 0 : parseInt(valCantidad);
		precioUnitario = (precioUnitario == "")? 0 : parseFloat(precioUnitario);
		porcentajeDesc = (porcentajeDesc == "")? 0 : parseInt(porcentajeDesc);
		porcentajeDescAdicional = (porcentajeDescAdicional == "")? 0 : parseInt(porcentajeDescAdicional);
	}
	
	//console.log(valCantidad + "|" + precioUnitario + "|" + porcentajeDesc + "|" + porcentajeDescAdicional);
	importe = valCantidad * precioUnitario;
	descTotal = porcentajeDesc + porcentajeDescAdicional;
	total = (importe * (1 - (descTotal / 100)));
	//console.log(total);
	
	if(grid == 3){				
		valorCeldaXY('detalleNotasServicios', 12, posY, importe); 
		valorCeldaXY('detalleNotasServicios', 13, posY, total); 
		$("#detalleNotasServicios_12_" + posY).html(formatear_pesos(importe));
		$("#detalleNotasServicios_13_" + posY).html(formatear_pesos(total));
	}else if(grid == 4){
		valorCeldaXY('detalleNotasServiciosCitas', 14, posY, importe); 
		valorCeldaXY('detalleNotasServiciosCitas', 15, posY, total); 
		$("#detalleNotasServiciosCitas_14_" + posY).html(formatear_pesos(importe));
		$("#detalleNotasServiciosCitas_15_" + posY).html(formatear_pesos(total));
	}
}

function sumaGridsNotasServicios(){
	var subtotal = 0;
	var iva = 0;
	var subtotal2 = 0;
	var iva2 = 0;
	
	sumaColumnaGrid('detalleNotasServicios', 14, 'subtotal');
	sumaColumnaGrid('detalleNotasServicios', 17, 'iva');	
	
	subtotal = $("#subtotal").val();
	iva = $("#iva").val();
	
	subtotal = subtotal.replace(/\$|\,/g,'');
	iva = iva.replace(/\$|\,/g,'');
	//console.log(subtotal + "||" + iva);
	
	sumaColumnaGrid('detalleNotasServiciosCitas', 16, 'subtotal');
	sumaColumnaGrid('detalleNotasServiciosCitas', 19, 'iva');
	 
	subtotal2 = $("#subtotal").val();
	iva2 = $("#iva").val();
	
	subtotal2 = subtotal2.replace(/\$|\,/g,'');
	iva2 = iva2.replace(/\$|\,/g,'');
	//console.log(subtotal2 + "||" + iva2);

	$("#subtotal").val(formatear_pesos(parseFloat(subtotal) + parseFloat(subtotal2)));
	$("#iva").val(formatear_pesos(parseInt(iva) + parseInt(iva2)));
	$("#total").val(formatear_pesos(parseFloat(subtotal) + parseFloat(subtotal2) + parseInt(iva) + parseInt(iva2)));
	$("#saldo_pendiente").val(formatear_pesos(parseFloat(subtotal) + parseFloat(subtotal2) + parseInt(iva) + parseInt(iva2)));
}


function formatear_pesos(num){
     num = num.toString().replace(/\$|\,/g,'');
     if (isNaN(num))
		  num = '0';
		  var signo = (num == (num = Math.abs(num)));
		  num = Math.floor(num * 100 + 0.50000000001);
		  centavos = num % 100;
		  num = Math.floor(num / 100).toString();

      if (centavos < 10)
           centavos = '0' + centavos;
      for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++)
           num = num.substring(0, num.length - (4 * i + 3)) + ',' + num.substring(num.length - (4 * i + 3));
      return (((signo) ? '' : '-') + num + '.' + centavos);
}
//LLAMAMOS A LA FUNCION FORMATEAR PESOS PARA DAR  FORMATO AL IMPUT
function formatearPesos(obj,num){
	   //alert(obj+"-"+num);
	   $(obj).val(formatear_pesos(num));
	   //***************************************************************
	
}

/*RECORRE EL GRID PARA SUMAR LOS VALORES DE UNA COLUMNA Y COLOCARLOS EN UN OBJETO CON DETERMINADO ID*/
function sumaColumnaGrid(grid, columna, idDestino){
	var sumaCol = 0.0;
	var valorTemp = "";
	
	$("#Body_" + grid + " tbody tr").each(function (index) {
		//console.log('1:: ' + index);
		$(this).children("td").each(function (index2) {
			//console.log('2:: ' + index2 + '-->' + $(this).text());
			switch (index2) {
				case columna:
					valorTemp = $(this).text().replace(/,/g, '').replace('$', '');
					sumaCol = sumaCol + parseFloat(valorTemp.toString());	
					//console.log('aa>' + $(this).text()+"<");				
				break;
			}
		});
	});
	
	if(isNaN(sumaCol)){
		sumaCol = 0;	
	}
	
	$('#' + idDestino).val(formatear_pesos(sumaCol));
	//console.log(grid + "|" + idDestino + "|" + sumaCol);
}

//MANDA A IMPRIMIR EL CONTENIDO DE UN DIV
function imprimir(id, encabezado){
	var div, imp;
	div = document.getElementById(id);//seleccionamos el objeto
	imp = window.open(" ","Formato de Impresion"); //damos un titulo
	imp.document.open();     //abrimos
	imp.document.write(encabezado + "<br>"); //tambien podriamos agregarle un <link ...
	imp.document.write(div.innerHTML);//agregamos el objeto
	imp.document.close();
	imp.print();   //Abrimos la opcion de imprimir
	imp.close(); //cerramos la ventana nueva
}


function gridComboValidaItem(grid, posVert, posHor, posHor2, compara){
	/*SE LE INDICA EL CAMPO A REVISAR Y SE COMPARA CONTRA EL VALOR PASADO, SI SON IGUALES LIMPIA EL HOR2*/
	var f = document.forma_datos;
	
	var id_evalua = celdaValorXY(grid, posHor, posVert);
	if(id_evalua != compara){
		valorCeldaXY(grid,posHor2,posVert,""); 
		$("#" + grid + "_" + posHor2 + "_" + posVert).html("&nbsp;");
	}
}

function validaComboPrevio(combo1, combo2, valorAcomparar){
	val1 = $("#"+combo1).val();
	if(val1 != valorAcomparar){
		$("#"+combo2).val("0");
	}
}

function llenaCamposNotaProductos(posY){
	cantidad = $("#detalleNotasProducto_3_" + posY).attr("valor").replace(/,/g, '').replace('$', '');
	unitario = $("#detalleNotasProducto_4_" + posY).attr("valor").replace(/,/g, '').replace('$', '');
	descuento = $("#detalleNotasProducto_5_" + posY).attr("valor").replace(/,/g, '').replace('#', '');
	
	cantidad = (isNaN(cantidad) == false)? cantidad : 0;
	unitario = (isNaN(unitario) == false)? unitario : 0;
	descuento = (isNaN(descuento) == false)? descuento : 0;
	
	importe = cantidad * unitario;
	precioFinal = importe * (1-(descuento/100));
	iva = 0
	importeIva = precioFinal * iva;
	total = precioFinal + importeIva;
	
	$("#detalleNotasProducto_6_" + posY).html(formatear_pesos(importe));
	$("#detalleNotasProducto_7_" + posY).html(formatear_pesos(precioFinal));
	$("#detalleNotasProducto_8_" + posY).html(iva);
	$("#detalleNotasProducto_9_" + posY).html(formatear_pesos(importeIva));
	$("#detalleNotasProducto_10_" + posY).html(formatear_pesos(total));	
	
	valorCeldaXY('detalleNotasProducto', 6, posY, importe); 
	valorCeldaXY('detalleNotasProducto', 7, posY, precioFinal); 
	valorCeldaXY('detalleNotasProducto', 8, posY, iva); 
	valorCeldaXY('detalleNotasProducto', 9, posY, importeIva); 
	valorCeldaXY('detalleNotasProducto', 10, posY, total); 
	
	sumaColumnaGrid('detalleNotasProducto', 8,'subtotal');
	sumaColumnaGrid('detalleNotasProducto', 10,'iva');
	sumaColumnaGrid('detalleNotasProducto', 11,'total');
}

function limpiarAlCambiarProducto(posY){
	$("#detalleNotasProducto_4_" + posY).html('&nbsp;');
	$("#detalleNotasProducto_5_" + posY).html('&nbsp;');
	$("#detalleNotasProducto_6_" + posY).html('&nbsp;');
	$("#detalleNotasProducto_7_" + posY).html('&nbsp;');
	$("#detalleNotasProducto_8_" + posY).html('&nbsp;');
	$("#detalleNotasProducto_9_" + posY).html('&nbsp;');
	$("#detalleNotasProducto_10_" + posY).html('&nbsp;');	
	
	valorCeldaXY('detalleNotasProducto', 4, posY, ''); 
	valorCeldaXY('detalleNotasProducto', 5, posY, ''); 
	valorCeldaXY('detalleNotasProducto', 6, posY, ''); 
	valorCeldaXY('detalleNotasProducto', 7, posY, ''); 
	valorCeldaXY('detalleNotasProducto', 8, posY, ''); 
	valorCeldaXY('detalleNotasProducto', 9, posY, ''); 
	valorCeldaXY('detalleNotasProducto', 10, posY, ''); 
}

function limpiarNotaServicio(incluirCampo2, posY){
	if(incluirCampo2 == 1)
		inicio = 4;
	else
		inicio = 6;
	
	for(i = inicio; i <= 19; i++){
		$("#detalleNotasServicios_" + i + "_" + posY).html('&nbsp;');
		valorCeldaXY('detalleNotasServicios', i, posY, ''); 
	}
}

/*CONTROLA LAS FORMAS DE COBRO SOLO EN LOS CATALOGOS DE NOTAS POR SERVICIO Y PRODUCTO*/
function validarTipoPago(grid, posY){
	var idFormaCobro = celdaValorXY(grid, 4, posY);
	var parametros = "opc=4&idFormaCobro=" + idFormaCobro;
	
	var resp = ajaxR("../ajax/getDatosAjax.php?" + parametros);
	datosCobro = jQuery.parseJSON(resp);
	
	if(datosCobro[0].terminal != 1){
		$("#" + grid + "_6_" + posY).html('&nbsp;');
		valorCeldaXY(grid, 5, posY, ''); 
		valorCeldaXY(grid, 6, posY, ''); 
	}
	if(datosCobro[0].aprobacion != 1){
		$("#" + grid + "_8_" + posY).html('&nbsp;');
		valorCeldaXY(grid, 8, posY, ''); 
	}
	
	if(grid == "detalleNotasServiciosPagos"){
		$("#" + grid + "_10_" + posY).html('&nbsp;');
		valorCeldaXY(grid, 10, posY, ""); 
		valorCeldaXY(grid, 16, posY, $("#hcampo_5").val() + "|" + idFormaCobro); 
	}
}

/*CONTROLA LAS FORMAS DE COBRO SOLO EN EN AGENDA PARA EL PAGO DE SERVICIOS*/
function validarTipoPagoAgenda(idTipoPago){
	var idFormaCobro = $("#" + idTipoPago).val();
	var pos = idTipoPago.split("_");
	var parametros = "opc=4&idFormaCobro=" + idFormaCobro;
	
	if(idFormaCobro != 0){
		var resp = ajaxR("../ajax/getDatosAjax.php?" + parametros);
		datosCobro = jQuery.parseJSON(resp);
		
		if(datosCobro[0].terminal != 1){
			$("#selterminal_" + pos[1]).val(0);
		}
		if(datosCobro[0].aprobacion != 1){
			$("#aprobacion_" + pos[1]).val("");
			$("#aprobacion_" + pos[1]).attr('readonly', true);
		}else{
			$("#aprobacion_" + pos[1]).removeAttr('readonly');
		}
		
		if(idFormaCobro != 4){
			$("#selnotaCambio_" + pos[1]).val(0);
		}else{
			var resp = ajaxR("../ajax/getDatosAjax.php?opc=5&idNota=" + $("#selnotaCambio_" + pos[1]).val());
			datosNota = jQuery.parseJSON(resp);
			$("#decimales_" + pos[1]).val(datosNota[0].disponible);
		}
	}else{
		$("#selnotaCambio_" + pos[1]).val(0);
		$("#selterminal_" + pos[1]).val(0);
		$("#aprobacion_" + pos[1]).val("");
	}
}

/*OBTENER EL MONTO PENDIENTE DE UNA NOTA DE CAMBIO DESDE CATALOGO NOTA DE SERVICIO*/
function montoPendiente(posY){
	var idNota = celdaValorXY("detalleNotasServiciosPagos", 10, posY);
	
	var resp = ajaxR("../ajax/getDatosAjax.php?opc=5&idNota=" + idNota);
	datosNota = jQuery.parseJSON(resp);
	
	valorCeldaXY("detalleNotasServiciosPagos", 9, posY, datosNota[0].disponible); 
	$("#detalleNotasServiciosPagos_9_" + posY).html(datosNota[0].disponible);
}


function validaCantidaSalida(grid,id_almacen,colPto,colCantida)
{
	var numf=NumFilas(grid);	
	var id_pto=0;
	var cantidad=0;

	
	for(var i=0;i<numf;i++)
	{
		//obtenemos el priducto
		id_pto=celdaValorXY(grid,colPto,i);
		
		//obtenemos la cantidas  cantidades
		cantidad=removeCommas(celdaValorXY(grid,colCantida,i));
		
		if(cantidad==0 || cantidad==0.00 || cantidad<0  )
		{
			alert("La cantidad para el producto : "+celdaValorXY(grid,(colPto+1),i) +" es inválida. ");
			return 0;	
		}
		
		//tipo 1 es por almacen //sale del almacen de productos		
		var respuesta=ajaxR("../ajax/obtenExistenciaAlmacenes.php?tipo=1&id_almacen="+id_almacen+"&id_pto="+id_pto+"&cantidad="+cantidad);
		var arrResp=respuesta.split("|");
		
		//alert(respuesta);
		
		if(arrResp[0]!="exito")
		{
			alert("La existencia insuficiente para el producto : "+celdaValorXY(grid,(colPto+1),i) +" existencia: " +arrResp[1]);
			return 0;
		}
		
		return 1
			
			
		
	}
}

function validaCatalogo(forma)
{
	var val_return=1;
	if(forma=="c3BhX2FsbWFjZW5lc19zYWxpZGFz"){
		
		//obtenemos el almacen del cual se realizará la salida
		var id_almacen=document.getElementById('id_almacen_salida').value;
		
		val_return =validaCantidaSalida('detalleSalida',id_almacen,3,5);
		
	}
	return val_return;
}