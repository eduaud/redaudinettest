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
		document.getElementById('botonGuardar').disabled="true";
	}
	
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
		var centroAncho = (screen.width/2) - 400;
		var centroAlto = (screen.height/2) - 300;
		var especificaciones="top="+centroAlto+",left="+centroAncho+",toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,width=800,height=600,resizable=yes"
		var titulo="ventanaEmergente"
		
		window.open('opcionTablas.php?o=Imprimir&t='+tabla+'&l='+Llave,titulo,especificaciones);
	}
}

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
	
}

/***********************************************************************************************************/
//	Función para filtrar los datos de un listado
function filtro(tabla, campo, operador, valor, extra,url,tcr, fd, fa,stm)
{
	//var Grid = ListaTotal.object;
	aPagina('listado', 1);
	if(valor=='' && operador != 'inactivos' && fd == ''  && fa == '' && operador != "=" && operador != "vacio")
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
function activaBuscador(objInput,evento)
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
	if((evento.keyCode==40&objInput.value.length>=1)||objInput.value.length>=1)
	{
		muestraBuscador(objInput);
		ComboBuscador(objInput);
	}
	else if(objdiv.style.display=="block"&&objInput.value.length>=1)
	{
		ComboBuscador(objInput);
	}	
	return true;
}



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
	if(confirm("A\u00FAn no ha guardado el registro.\n " + String.fromCharCode(191)+"Desea salir de esta pantalla?"))
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
	if(confirm("A\u00FAn no se ha terminado el proceso.\n " + String.fromCharCode(191) + "Desea salir de esta pantalla?"))
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
	$('#' + idDestino).attr("valornum", sumaCol);
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

//VALIDAMOS LOS DATOS DE LOS REPORTES
function modificaGridDetalle(forma)
{
	var signo = "1";
	//ALMACENES MOVIMIENTOS
	if(forma=="YWRfbW92aW1pZW50b3NfYWxtYWNlbg=="){
		
		//realizar el recorrido 
		var tipoMovimiento=document.getElementById('id_tipo_movimiento').value;
		if(tipoMovimiento==2)
		{
			signo = "-1";
		}
		var grid='detalleMovimientosAlmacen';
		for(i=0;i<=NumFilas(grid);i++)
		{
			  valorXY(grid,15,i,signo);
		}	
		
	}
	
}






























/***********************************************************************************************************/
/***********************************************************************************************************/
/***********************************************************************************************************/
/***********************************CATALOGOS.TPL***********************************************************/
/***********************************************************************************************************/
/***********************************************************************************************************/
/***********************************************************************************************************/

document.onkeypress = keyhandler;
function keyhandler(e) {
   if(window.event)
	  keyCode=window.event.keyCode; //IE	
   else
      if(e)
	     keyCode=e.which;
	      
      if(keyCode == 39){
	     return false;
	  }
}

function validaultimafila(grid)
{
	var nfil=NumFilas(grid);
	if(nfil<1)
		return true;	
	var objt=document.getElementById("Body_"+grid);
	if(objt)
	{
		for(var i=0;i<objt.rows.length;i++)
		{
			var fila=objt.rows[i];
			var valvac=0;
			for(var j=0;j<fila.cells.length;j++)
			{
				var celda=fila.cells[j];
				if(j>0)
				{
					var head=document.getElementById("H_"+grid+(j-1));
					if(head)
					{ 
						var mod=head.getAttribute("modificable").toUpperCase();
						var tipo=(head.getAttribute("tipo"))?(head.getAttribute("tipo").toUpperCase()):"";
						if(mod=="S"&&tipo!="ELIMINADOR")
						{
							if(tipo!="TEXTO")
								var valor=celda.getAttribute("valor");
							else
								var valor=celda.innerHTML;							
							if(valor==null||valor==""||valor=="&nbsp;")
							{
								alert("En la ultima fila aun existen valores requeridos vacios.");
								return false;
							}
						}
					}					
				}
			}		
		}
	}
	return true;;
}

function redondear(num, long)
{
	 var nuevo = Math.round(num*Math.pow(10,long))/Math.pow(10,long);
	 return nuevo;
}

function imprime(t)
{
	var f=document.forma_datos;
	if(t == '')
	{
		if(f.campo_0.value=="")
		{
			alert("Debe Guardar el documento para poder imprimirlo.")
			return false;
		}
		window.open('../pdf/imprimeDoc.php?Factura=SI&idfactura='+f.campo_0.value,"imp1","width=900,height=650,top=200,left=200,resizable=NO");
	}	
}

function irListado(rooturl,tabla,stm)
{
	location.href=	rooturl+"code/indices/listados.php?t="+tabla+'&stm='+stm;
}	

//da de alta un nuevo registro de la misma tabla seleccionada
function irNuevo(rooturl,tabla,tcr,stm)
{
	location.href=rooturl+"code/general/encabezados.php?t="+tabla+"&k=&op=1&tcr="+tcr+'&stm='+stm;
}	

//modifica el registro de la tabla seleccionada EL ID SIEMPRE ES EL CAMPO UNO
function irModificarRegistro(objform,rooturl,tabla,campo,tcr,stm){
		var id = objform.elements[campo].value;
		
		if(tabla == "YWRfcGVkaWRvcw=="){
				var modifica = verificaModPedido(id);
				if(modifica == 0){
						alert("No puedes modificar un pedido\ntranscurrido un dia de la fecha de alta");
						return false;
						}
				}
		else if(tabla == 'bmFfaW5ncmVzb3NfY2FqYV9jaGljYQ=='){		
					var confirmado = verificaModIngreso(id);
					if(confirmado == 1){
							alert("No puedes modificar un ingreso si ya esta confirmado");
							return false;
							}
					}
		else if(tabla == 'bmFfcHJvZHVjdG9z'){
					
					var ruta = "verificaProductoCompuesto.php";
					var envio = "id=" + id;
					var respuesta = ajaxN(ruta, envio);
					var permite = JSON.parse(respuesta)
					if(permite.modifica > 0){
							if(permite.caso == 1){
									alert("No puedes modificar un producto compuesto que se encuentra apartado");
									return false;
									}
							else if(permite.caso == 2){
									alert("No puedes modificar un producto basico\ncuyo producto compuesto al que pertenece\nse encuentra apartado");
									return false;
									}
							}
					}
		else if(tabla == 'YWRfb3JkZW5lc19jb21wcmFfcHJvZHVjdG9z'){
					var modifica = verificaModOrden(id);
						if(modifica == 0){
								alert("No puedes modificar una orden de compra\ndespues de entregados los productos");
								return false;
								}
					}
		else if(tabla == 'bmFfbW92aW1pZW50b3NfYWxtYWNlbg=='){
				var modificable = $("#no_modificable").val();
				if(modificable==1){
						alert("El movimiento no puede ser modificado.");
						return false;
						}
				else if(modificable==2){
						alert("El movimiento no puede ser modificado, esta pendiente por autorizar por parte de Dirección.");
						return false;
						}
				}
		
		var res=ajaxR(rooturl+'code/ajax/verificaPermisos.php?tabla='+tabla+'&id='+id+'&accion=1');
		if(res != 'si'){
				alert('No es posible realizar el registro, ya esta relacionado a otros módulos.');
				return false;
				}
		if($("#indicaMod").val() == 1){ //Validacion de modificar sobre los fancybox de direcciones de entrega y clientes
				location.href=	rooturl+"code/general/encabezados.php?t="+tabla+"&k="+id+"&tcr="+tcr+"&op=2&v=0"+"&stm="+stm+"&modhf=1";
				}
		else{
				location.href=	rooturl+"code/general/encabezados.php?t="+tabla+"&k="+id+"&tcr="+tcr+"&op=2&v=0"+"&stm="+stm;
				}
			
		}


function irEliminarRegistro(objform,rooturl,tabla,campo)
{	
    var obj=document.getElementById('waitingplease');
	if(document.forma_datos.modificar)
	{
		obj.style.display="block";		
	}
	
	//tabla productos
	if(objform.t.value=="YW5kZXJwX3Byb2R1Y3Rvcw=="){
		var f = document.forma_datos;
	   	var id_prod = f.id_producto.value;
		var aux=ajaxR("../ajax/validaEliminarProducto.php?accion=1&id="+id_prod);
	  
	   	var ax=aux.split('|');
	   	if(ax[0] == 'exito'){
	    	var cade = "";
			var id_sucursal = f.id_sucursal.value;
			var obj=document.getElementById('detalleproducto');  //objeto grid
			var num=NumFilas('detalleproducto');
			   
			var pagAct=obj.pagAct?obj.pagAct:obj.getAttribute("pagAct");  //pagina en cual esta posicionado
			   
			//recorremos grid de listado de presentaciones 		
		    for(var i=0; i <num; i++){
				var id_prodDeta = celdaValorXY('detalleproducto', 0, i); //obtiene id_producto_detalle Grid
				if(cade == ""){
					cade = id_prodDeta;
				}else{
					cade = cade + "@@" + id_prodDeta; 
				}
			}//fin for i
			  		  
			if(cade != ""){
				document.getElementById("accion_pant").value = 4;
				document.getElementById("accionElim").value = 1;
				document.getElementById("prodGrid").value = cade;
			}  
	   	}else{
	      	alert(ax[1]);
		  	obj.style.display="none";
		  	return false;
	   	}
	}
}

/*function valida(objform,make)
{		
	var obj=document.getElementById('waitingplease');
	if(document.forma_datos.modificar)
	{
		document.forma_datos.modificar.disabled=true;		
		obj.style.display="block";		
	}	
	
	if(validaR(objform,make) == false)	
	{
		if(document.forma_datos.modificar)
		{
			document.forma_datos.modificar.disabled=false;		
			obj.style.display="none";
		}	
	}
}*/





function validaRx(objform,make)
{
    var strSelected="";
	var strSelectedSuc="";
	var varinicio=1;
	var validaIDEdit='no';
	
	objform.elements['generaSubmit'].value='0';

	//si el la llave es del tipo chard y es requerida comenzamos a validar desde 0
	var des_arrayID=(objform.elements["propiedades_[0]"].value).split("|");
		
	if(des_arrayID[1]=="CHAR" && des_arrayID[2]=="1")
	{
		varinicio=0;
		
		//validamos si estamos en el insert
		//si la opcion es 1
		if(objform.elements["make"].value=='insertar')
		{
			validaIDEdit='si';
		}	
	}
	
	for(var i=varinicio; i<parseInt(objform.countReg.value);i++)
	{
		var des_array=(objform.elements["propiedades_["+i+"]"].value).split("|");
		
		//si estamos en la fecha de alta mejor lo mandamos desde el codigo
		if (des_array[1]=="DATE" &&  objform.elements["campo_"+i+""].value=="" && des_array[0]=="FECHA ALTA")
		{
			//colocamos la fecha de alta de hoyDate() 
			 objform.elements["campo_"+i+""].value= "now()";
		}
		
		//validamos el formato de la fecha
		if (des_array[1]=="DATE" &&  objform.elements["campo_"+i+""].value!="" &&  objform.elements["campo_"+i+""].value!="now()" &&  objform.elements["campo_"+i+""].value!="0000-00-00 00:00:00" && objform.elements["campo_"+i+""].value!="0000-00-00" && objform.elements["campo_"+i+""].value!="NULL")
		{
			//colocamos la fecha de alta de hoyDate() 
			//alert(esFechaValida(objform.elements["campo_"+i+""].value));
			if (esFechaValida(objform.elements["campo_"+i+""].value)==false && objform.elements["campo_"+i+""].value!= "00/00/0000")
			{
				alert("El formato de  "+ des_array[0]+ " es inválido");
				return false;
			}
		}
	
		if (des_array[1]=="TIME" &&  objform.elements["campo_"+i+""].value!="")
		{	
			if (esHora(objform.elements["campo_"+i+""].value)==false)
			{
				alert("El campo  "+ des_array[0]+ " es inválido.");
				return false;
			}
		}
				
		if(des_array[1]=="COMBOBUSCADOR")
		{
			objform.elements["v_campo_"+i+""].value=trim(objform.elements["v_campo_"+i+""].value);
		}
		
		var regexp = /^[0-9]$/;
		if(des_array[1]=="COMBOBUSCADOR" && des_array[2]=="3" && isNaN(parseInt(objform.elements["campo_"+i+""].value)))
		{
			alert("Debe seleccionar un registro válido del listado en el campo "+des_array[0]+".");
			objform.elements["v_campo_"+i+""].focus();
			return false;
		}
		
		if(des_array[1]=="COMBOBUSCADOR" && des_array[2]=="2" && isNaN(parseInt(objform.elements["campo_"+i+""].value)) && objform.elements["campo_"+i+""].value != "0")
		{
			
			alert("Debe seleccionar un registro válido del listado en el campo "+des_array[0]+".");
			objform.elements["v_campo_"+i+""].focus();
			return false;
		}

		if (des_array[2]=="1" && objform.elements["campo_"+i+""].value=="")
		{
			alert("El campo "+des_array[0]+" es requerido.");
			objform.elements["campo_"+i+""].focus();
			return false;
           
		}
				
		if (des_array[1]=="EMAIL" &&  objform.elements["campo_"+i+""].value!="")
		{
			if (validaMail(objform.elements["campo_"+i+""].value)==false)
			{
				alert("El campo  "+ des_array[0]+ " es inválido.");
				return false;
			}
		}		
	}
	
	if(validaIDEdit=='si')
	{
		 
		var campo=objform.elements['campo_0'].id;
		var valor=objform.elements['campo_0'].value;
		var tabla=objform.elements['t'].value;
		
		objform.elements['generaSubmit'].value='1';
				
		makeRequest('../ajax/ajaxresponse.php?campo='+campo+'&id='+valor+'&tabla='+tabla+'&code=si','validaId');
		
		return false;
	}
	//return false;
	objform.strSelected.value=strSelected;
	document.getElementById('guardarb').disabled="true";
	
	
	//EXCEPCIONES POR CATALOGO 
	if(objform.t.value=="XXXXXXXXXXXXXXXXX"){
	
	}
	
	/***************************** VALIDACIONES PARA NO INSERTAR REPETIDOS EN CATALOGOS ***********************/
	if (document.forma_datos.make.value == "insertar")
	{
		if(objform.t.value == "XXXXXXXXXXXXXXXXXXX")
		{
			if(objform.nombre.value != null)
			{		
				var v_nombre = objform.nombre.value;
				var v_id_book = objform.id_book.value;
				var v_id_plaza = objform.id_plaza.value;
				var v_nom = v_nombre.toUpperCase(); 
							
				var aux = ajaxR("../ajax/validaRegistrosRepetidos.php?accion=5" + "&nombre=" + v_nom + "&id_book=" + v_id_book + "&id_plaza=" + v_id_plaza);
				
				var ax = aux.split("|");
				if(ax[0] == 'exito')
				{
					alert(ax[1]);
					return false;			 
				}
			}
		}		
	}
	/***************************** TERMINA VALIDACIONES PARA NO INSERTAR REPETIDOS EN CATALOGOS ***********************/
	
	//execiones CATALGO
	var valRegresoCat=validaCatalogo(objform.t.value);
	
	if(valRegresoCat==0)
	{
		return false;
	}
	
	objform.submit();	
	//verifcamos el nombre de la tabla
	// si es una solcitud no puede ser modificada si tiene asignado un contrato				
}







/*******************************************************/
function quitaComasForma(f)
{
	f.subtotal.value=removeCommas(f.subtotal.value);
	f.total.value=removeCommas(f.total.value);
	return true;
}

/****************************************************/
function removeCommas(strValue)
{
	objRegExp = /\$|\(|[,]/g;
	return strValue.replace(objRegExp,'');
}

/********************************************************/
function makeRequest(url,busca){
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
	
	if(busca=='validaId')
		http_request.onreadystatechange = validaId;
	else if(busca=='cargaOpciones') 
		http_request.onreadystatechange = cargaOpciones;
	
	http_request.open('GET', url, true);
	http_request.send(null);
}

function trim(cadena)
{
	for(i=0; i<cadena.length; )
	{
		if(cadena.charAt(i)==" ")
			cadena=cadena.substring(i+1, cadena.length);
		else
			break;
	}

	for(i=cadena.length-1; i>=0; i=cadena.length-1)
	{
		if(cadena.charAt(i)==" ")
			cadena=cadena.substring(0,i);
		else
			break;
	}
	
	return cadena;
}




//datos de dependecia , relativo a cada tabla
function llenaDependencia(valor,aquienLlena,opcion)
{
	//realizamos el make 
	makeRequest('../ajax/obtenOpciones.php?valor='+valor+'&aql='+aquienLlena+'&opcion='+opcion,'cargaOpciones');
}

function llenaDependenciaIni()
{
	var valor=document.getElementById('id_familia_de_producto').value;
	llenaDependencia(valor,'subfamilia1','2');
	
}


function ingresaFil(grid)
{
	var nfil=NumFilas(grid);
	if(nfil==0)
		return true;
}

function validaMatriz(obj)
{
	var f=document.forma_datos;
	if(obj.checked == true)
	{
		aux=ajaxR('../ajax/validaMatrizUnica.php?id_suc='+f.campo_0.value);
		if(aux != 'exito')
		{
			alert(aux);
			obj.checked=false;
			return false;
		}
	}
}

function rutaAnterior()
{
	var f=document.forma_datos;
	aux=ajaxR('../ajax/obtenUltimaRuta.php?id_suc='+f.campo_0.value);
	var arrResp=aux.split("|");
	if(arrResp[0] != 'exito')
	{
		return false;		
	}
	else
	{
		objRuta = document.getElementById('id_ruta');
		for(var i=0;i<objRuta.length;i++){
			 if(objRuta.options[i].value == arrResp[1]){
			   objRuta.options[i].selected = true;
			 }
		}		
	}
}

function muestraFilasVisiblesEntrada()
{
	muestrafilasEntrada(document.getElementById('id_tipo_entrada').value);
}

function muestraFilasVisiblesSalida()
{
	//obtenemos el valor del tipo de movim	
	muestrafilasSalida(document.getElementById('id_tipo_salida').value);
}

function cargaWait()
{
	var w=screen.width;
	var h=screen.height;
	var obj=document.getElementById('waitingplease');
	var im1=document.getElementById('imgW1');
	var im2=document.getElementById('imgW2');
	im2.width=screen.width;
	im2.height=screen.height;
	im1.style.left=(w-128)/2;
	im1.style.top=(h-128)/2-(h/8);
}	


function irEliminarRegistro(objform,rooturl,tabla,campo)
{	
    var obj=document.getElementById('waitingplease');
	
	if(document.forma_datos.modificar)
	{
		obj.style.display="block";		
	}
	
	/*if(objform.t.value == "cGV1Z19wdW50b3NfdmVudGE=")
	{
		var id_punto_venta=document.getElementById('id_punto_venta').value;
		var aux=ajaxR("../ajax/validaElimPV.php?tipo=1&id_punto_venta="+id_punto_venta);
		var ax=aux.split('|');
		if(ax[0] == 'exito')
		{
			if(confirm(ax[1]))
			{
				aux=ajaxR("../ajax/validaElimPV.php?tipo=2&id_punto_venta="+id_punto_venta);
				if(aux == 'exito')
					objform.submit();							
				else
					alert(aux);	
			}	
		}
		else
		{
			alert(aux);	
			obj.style.display="none";
			return false;
		}	
	}*/
	
	if (!confirm(String.fromCharCode(191)+"Desea eliminar el registro?"))
		return false;	
	else
		objform.submit();	
}

function validaR(objform,make)
{
    //   **************  abc  ****************
	// Exepciones para the books
	
	//Valida lque se eleja la plaza al registrar una nueva requisición
	if(objform.t.value == "YWRfcmVxdWlzaWNpb25lcw=="){
		var opcion = document.getElementById("id_sucursal").selectedIndex;
		 if(opcion == null || opcion == 0){
			alert('Debe elegir una Plaza');
			return false;
		 }
	}

	//veridfcamos si en la tabla proveedores esta activado el check
	//requiere contrato para cambiar los campos de contrato a requeridos
	if(objform.t.value=="b2ZfcHJvdmVlZG9yZXM="){
		check=document.getElementById('require_contrato');
		 if(check.checked){
			 for(i=20;i<27;i++){
				 elemento=objform.elements["propiedades_["+i+"]"];
				 //cambiamos el ultimo valor por 1 que es el permiso de requerido
				 elemento.value=elemento.value.substring(0,elemento.value.length-1)+"1";
			 }
		 }//if checed
	}
	if(objform.t.value =="Y2xfYnJha2V0X3ZldHY="){
		gridBraket="detalleBraket";
        if(NumFilas(gridBraket)>0)
		{
			var posicion=0;
			var resultado='';
			for(i=0;i<NumFilas(gridBraket);i++)
			{
				var inicioAux=parseInt(celdaValorXY(gridBraket,2,i));
				var finAux=parseInt(celdaValorXY(gridBraket,3,i));
				for(j=0;j<NumFilas(gridBraket);j++){
					var Inicio=parseInt(celdaValorXY(gridBraket,2,j));
					var Fin=parseInt(celdaValorXY(gridBraket,3,j));
					if(j!=posicion){
						if(finAux>inicioAux&&Fin>Inicio){
							if(Inicio>finAux||(Inicio<inicioAux&&Fin<inicioAux))
									resultado=true;
							else
									resultado=false;
						}
					}
					else
						resultado=false;
				}
				posicion+=1;
			}
			if(resultado!=true){
				alert('Error verifique que los rangos sean correctos');
				return false;
			}
		}
	}
	if(objform.t.value =="Y2xfcHJvZHVjdG9zX3NlcnZpY2lvcw=="||objform.t.value =="YWRfc3VjdXJzYWxlcw=="||objform.t.value =="YWRfZW50aWRhZGVzX2ZpbmFuY2llcmFz"){
		var datoExtra='';
		if(objform.t.value =="YWRfY2xpZW50ZXM="){
			var id_tipo_cliente=$("#id_tipo_cliente_proveedor option:selected" ).val();
			if(id_tipo_cliente!='0')
					datoExtra=id_tipo_cliente;
		}
		if(objform.op.value==1){
			var resultado=validaClaveInsert(objform.t.value,datoExtra);
			if(resultado)
				return false;
		}
		else if(objform.op.value==2){
			if(objform.t.value =="Y2xfcHJvZHVjdG9zX3NlcnZpY2lvcw=="){
				var id=$("#id_producto_servicio").val();
				var campo="id_producto_servicio";
			}else if(objform.t.value =="YWRfc3VjdXJzYWxlcw=="){
				var id=$("#id_sucursal").val();
				var campo="id_sucursal";
			}else if(objform.t.value =="YWRfY2xpZW50ZXM="){
				var id=$("#id_cliente").val();
				var campo="id_cliente";
			}else if(objform.t.value =="YWRfZW50aWRhZGVzX2ZpbmFuY2llcmFz"){
				var id=$("#id_entidad_financiera").val();
				var campo="id_entidad_financiera";
			}
			var clave=$("#clave").val();
			var datos="clave="+clave+"&tabla="+objform.t.value+"&campo="+campo+"&id="+id;
			var resultado=validaClave(datos,datoExtra);
			if(resultado=='error'){
				var resultado=validaClaveInsert(objform.t.value,datoExtra);
				if(resultado)
					return false;
			}
		}
	}
	/*if(objform.t.value =="YWRfY2xpZW50ZXM="&&objform.op.value=='2'&&(objform.stm.value=='75000'||objform.stm.value=='75002'||objform.stm.value=='75001')){
		actualizaClientesHijosAudicel('id_cliente');
	}*/
	for(i=0;i<2;i++){
	if(objform.t.value =="YWRfY2xpZW50ZXM="){
		var campoLlave='id_cliente';
		var modulo=objform.stm.value;
		var campo='';
		if(modulo=='75003'||modulo=='75008'||modulo=='75009')
			campo='nit';
		else if(modulo=='75004')
			campo='niv';
		else if(modulo=='75000'||modulo=='75001'||modulo=='75002'){
			if(i=="0")
				campo='clave';
		}
			
		if(objform.op.value=='1'){
			var resultado=validaCamposRepetidos(objform.t.value,campo,'insertar',campoLlave,modulo);
		}else if(objform.op.value=='2'){
			var resultado=validaCamposRepetidos(objform.t.value,campo,'modificar',campoLlave,modulo);
		}
		if(resultado==false){
			alert("El campo "+campo.toUpperCase()+" ya esta registrado");	
			return false;
		}else if(resultado=='vacio'){
			alert('Ingrese el '+campo.toUpperCase());
			return false;
		}
		
	}
	}
	if(objform.t.value == 'c2lzY2JhX2FsbWFjZW5fdWJpY2FjaW9u'){
		id_origen = $("#id_origen_destino").val();
		nom_ubicacion = $("#id_ubicacion").val();
		nom_ubicacion = nom_ubicacion.toUpperCase(); 
		
		var aux = ajaxR("../ajax/validaRegistrosRepetidos.php?accion=1" + "&idOrigenDestino="+id_origen+"&idUbicacion="+nom_ubicacion);
		var ax = aux.split("|");
		if(ax[0] == 'error'){
			alert(ax[1]);
			return false;			 
		}
	}
	if(objform.t.value == 'YWRfcG9saXphcw=='){
		var Grid='detallePolizas';
		for(i=0;i<NumFilas(Grid);i++){
			var abono=celdaValorXY(Grid,10,i);
			var cargo=celdaValorXY(Grid,9,i);
			if(abono!=''&&cargo!=''){
				if(abono>0&&cargo>0){
					alert('No se puede hacer un cargo y abono a la vez');
					return false;
				}
				
			}
		}
	}
	
	/*** Validacion de selects vacios en la tabla de catalogo de productos ***/
if(objform.t.value == 'bmFfcHJvZHVjdG9z' && objform.elements["make"].value=='insertar')
{	
			var familia = document.getElementById("id_familia_producto").selectedIndex;
			var tipo = document.getElementById("id_tipo_producto").selectedIndex;
			var modelo = document.getElementById("id_modelo_producto").selectedIndex;
			var caracteristica = document.getElementById("id_caracteristica_producto").selectedIndex;
			var marca = document.getElementById("id_marca_producto").selectedIndex;
			
				if( familia == null || familia == 0 ) {
				  alert("Selecciona una familia de Producto");
				  return false;
				}
				else if( tipo == null || tipo == 0 ) {
				  alert("Selecciona un tipo de Producto");
				  return false;
				}
				else if( modelo == null || modelo == 0 ) {
				  alert("Selecciona un modelo de Producto");
				  return false;
				}
				else if( caracteristica == null || caracteristica == 0 ) {
				  alert("Selecciona una caracteristica de Producto");
				  return false;
				}
				else if( marca == null || marca == 0 ) {
				  alert("Selecciona una marca de Producto");
				  return false;
				}
		//VALIDAMOS QUE EL CODIGO DEL PRODUCTO NO EXISTA
		if((validaCodigoArticulo()==false))
			return false;
			
}
else if(objform.t.value == 'bmFfcHJvdmVlZG9yZXM=')
{
	//validamos que los datos de de pais esta dados de alta
	if((validaDatosProveedores()==false))
			return false;
	
}
	
	
	
	//   *************************************
	
	var strSelected="";
	var strSelectedSuc="";
	var varinicio=1;
	var validaIDEdit='no';
	objform.elements['generaSubmit'].value='0';
	//si el la llave es del tipo chard y es requerida comenzamos a validar desde 0
	var des_arrayID=(objform.elements["propiedades_[0]"].value).split("|");
	if(des_arrayID[1]=="CHAR" && des_arrayID[2]=="1")
	{
		
		varinicio=0;
		
		//validamos si estamos en el insert
		//si la opcion es 1
		if(objform.elements["make"].value=='insertar')
		{
			validaIDEdit='si';
		}
		
		//si estamos realizando el insert 
			
	}
	
	
	for(var i=varinicio; i<parseInt(objform.countReg.value);i++)
	{
		var des_array=(objform.elements["propiedades_["+i+"]"].value).split("|");
		
		
		//si estamos en la fecha de alta mejor lo mandamos desde el codigo
		if (des_array[1]=="DATE" &&  objform.elements["campo_"+i+""].value=="" && des_array[0]=="FECHA ALTA")
		{
			//colocamos la fecha de alta de hoyDate() 
			 objform.elements["campo_"+i+""].value= "now()";
		}
		//validamos el formato de la fecha
		if (des_array[1]=="DATE" &&  objform.elements["campo_"+i+""].value!="" &&  objform.elements["campo_"+i+""].value!="now()" &&  objform.elements["campo_"+i+""].value!="0000-00-00 00:00:00" && objform.elements["campo_"+i+""].value!="0000-00-00" && objform.elements["campo_"+i+""].value!="NULL")
		{
			//colocamos la fecha de alta de hoyDate() 
			//alert(esFechaValida(objform.elements["campo_"+i+""].value));
			if (esFechaValida(objform.elements["campo_"+i+""].value)==false && objform.elements["campo_"+i+""].value!= "00/00/0000")
			{
				alert("El formato de  "+ des_array[0]+ " es inválido");
				return false;
			}
		}
	
		if (des_array[1]=="TIME" &&  objform.elements["campo_"+i+""].value!="")
		{
			
			if (esHora(objform.elements["campo_"+i+""].value)==false)
			{
				alert("El campo  "+ des_array[0]+ " es inválido.");
				return false;
			}
		}
		
		
		if(des_array[1]=="COMBOBUSCADOR")
		{
			objform.elements["v_campo_"+i+""].value=trim(objform.elements["v_campo_"+i+""].value);
		}
		
		var regexp = /^[0-9]$/;
		if(des_array[1]=="COMBOBUSCADOR" && des_array[2]=="3" && isNaN(parseInt(objform.elements["campo_"+i+""].value)))
		{
			
			alert("Debe seleccionar un registro válido del listado en el campo "+des_array[0]+".");
			objform.elements["v_campo_"+i+""].focus();
			return false;
		}
		if(des_array[1]=="COMBOBUSCADOR" && des_array[2]=="2" && isNaN(parseInt(objform.elements["campo_"+i+""].value)) && objform.elements["campo_"+i+""].value != "0")
		{
			
			alert("Debe seleccionar un registro válido del listado en el campo "+des_array[0]+".");
			objform.elements["v_campo_"+i+""].focus();
			return false;
		}
		
		if (des_array[1]=="ARCHIVO" &&  objform.elements["hcampo_"+i+""].value=="" && des_array[2]=="1")
		{
				alert("El campo - "+ des_array[0]+ " es requerido.");
				return false;
			
		}
	

		if (des_array[2]=="1" && objform.elements["campo_"+i+""].value=="" && des_array[1]!="ARCHIVO")
		{
			alert("El campo "+des_array[0]+" es requerido.");
			objform.elements["campo_"+i+""].focus();
			return false;
           
		}
		
		
		if (des_array[1]=="EMAIL" &&  objform.elements["campo_"+i+""].value!="")
		{
			if (validaMail(objform.elements["campo_"+i+""].value)==false)
			{
				alert("El campo  "+ des_array[0]+ " es inválido.");
				return false;
			}
		}
		
	}
	
	
	//Excepcion especial para grid de almacenes
	modificaGridDetalle(objform.t.value);
	//alert(objform.t.value);
	
	
	//EXCEPCIONES POR CATALOGO
	//solicitudes de pedidos
	//validamos la excepcion de ususarios si es que 
	if(objform.t.value=="c3lzX3VzdWFyaW9z")
	{
	    //quitamos espacios en blanco a la derecha e izquierda
        document.getElementById('login').value = trim(document.getElementById('login').value);
		document.getElementById('pass').value = trim(document.getElementById('pass').value);
		//document.getElementById('password2').value = trim(document.getElementById('password2').value);
	
		//validamos que el password y confirmacion del password sean iguales y mayores a cero
		if(document.getElementById('pass').value.length<6)
		{
			alert("El PASSWORD debe constar de almenos 6 caracteres");
			return false;			
		}
		
		/*if(document.getElementById('pass').value != document.getElementById('password2').value )
		{
			alert("El campo PASSWORD es distinto al CONFIRMAR PASSWORD.");
			return false;
		
		}*/
	}
	
	if(objform.t.value == 'cmFjX3BlZGlkb3M=')
	{		
		if(document.getElementById('no_modificable').checked)
		{
			//VALIDAR QUE POR LO MENOS EXISTA UN REGISTRO EN LOS GRIDS Y QUE NO SEA PARTE DE LAS TRANSFORMACIONES BASICAS
			filasTotales = 0;
			
			grid = "detalleArticulos";
			filasTotales += $('#Body_' + grid + ' tr').length;
			grid = "detalleArticulosEspeciales";
			filasTotales += $('#Body_' + grid + ' tr').length;
			grid = "detalleArticulosProduccion";
			filasTotales += $('#Body_' + grid + ' tr').length;
			grid = "detalleArticulosCompra";
			filasTotales += $('#Body_' + grid + ' tr').length;
			
			if(filasTotales == 0)
			{
				alert("Debe solicitar por lo menos un Art\u00EDculo");
				return false;
			}
		
		
			//si la badera esta en no modificable de los detalles nuevos vemos si hay disponibilidad del producto
			var fecha_entrega_articulos = $("#fecha_entrega_articulos").val();
			var hora_entrega = $("#hora_entrega").val();
			var fecha_recoleccion = $("#fecha_recoleccion").val();
			var hora_recoleccion2 = $("#hora_recoleccion2").val();
			var tipo_evento = $("#id_tipo_evento_localizacion").val();
			var respuestaInvalidas = 0;
			
			var grid = "detalleArticulos";
			$('#Body_' + grid + ' tr').each(
				function () 
				{					
					numFila = $(this).attr("id").replace('' + grid + '_Fila', "");
					
					var idArticulo = celdaValorXY(grid, 2, numFila);
					var idArtExist = celdaValorXY(grid, 5, numFila);
					var cantSurtir = celdaValorXY(grid, 7, numFila);
					//http://localhost:8082/rc/code/ajax/getDatosAjax.php?&opc=9&tipo_evento=1&idArticulo=3&fecha_entrega_articulos=23/02/2014&hora_entrega=10&fecha_recoleccion=25/02/2014&hora_recoleccion2=6&idArtExist=100&cantSurtir=50
					var array = new Object(); 
					array['opc'] = 9;
					array['tipo_evento'] = tipo_evento;
					array['idArticulo'] = idArticulo;
					array['fecha_entrega_articulos'] = fecha_entrega_articulos;
					array['hora_entrega'] = hora_entrega;
					array['fecha_recoleccion'] = fecha_recoleccion;
					array['hora_recoleccion2'] = hora_recoleccion2;
					array['idArtExist'] = idArtExist;
					array['cantSurtir'] = cantSurtir;
					
					$.ajax({
						async:false,
						url: '../../code/ajax/getDatosAjax.php',
						cache:false,
						dataType:"html", 
						data:array,
						type: 'POST',
						success: function(resp){ 
							if(resp != '1')
							{
								alert('La existencia del producto "' + celdaValorXY(grid, 3, numFila) + '" del grid "Detalle art\u00EDculos" es menor a lo que necesita');
								respuestaInvalidas = respuestaInvalidas + 1;
							}
						},
						error: function(data) {
							alert('Error al conectar con el servidor'); //or whatever
							return false;
						}
					});
				}
			);
			
			var grid = "detalleArticulosBasicos";
			$('#Body_' + grid + ' tr').each(
				function () 
				{					
					numFila = $(this).attr("id").replace('' + grid + '_Fila', "");
					
					var idArticulo = celdaValorXY(grid, 2, numFila);
					var idArtExist = celdaValorXY(grid, 7, numFila);
					var cantSurtir = celdaValorXY(grid, 9, numFila);
					//http://localhost:8082/rc/code/ajax/getDatosAjax.php?&opc=9&tipo_evento=1&idArticulo=3&fecha_entrega_articulos=23/02/2014&hora_entrega=10&fecha_recoleccion=25/02/2014&hora_recoleccion2=6&idArtExist=100&cantSurtir=50
					var array = new Object(); 
					array['opc'] = 9;
					array['tipo_evento'] = tipo_evento;
					array['idArticulo'] = idArticulo;
					array['fecha_entrega_articulos'] = fecha_entrega_articulos;
					array['hora_entrega'] = hora_entrega;
					array['fecha_recoleccion'] = fecha_recoleccion;
					array['hora_recoleccion2'] = hora_recoleccion2;
					array['idArtExist'] = idArtExist;
					array['cantSurtir'] = cantSurtir;
					
					$.ajax({
						async:false,
						url: '../../code/ajax/getDatosAjax.php',
						cache:false,
						dataType:"html", 
						data:array,
						type: 'POST',
						success: function(resp){ 
							if(resp != '1')
							{
								alert('La existencia del producto "' + celdaValorXY(grid, 3, numFila) + '" del grid "Detalle art\u00EDculos para transformaciones b\u00E1sicas" es menor a lo que necesita');
								respuestaInvalidas = respuestaInvalidas + 1;
							}
						},
						error: function(data) {
							alert('Error al conectar con el servidor'); //or whatever
							return false;
						}
					});
				}
			);
			
			var grid = "detalleArticulosEspeciales";
			$('#Body_' + grid + ' tr').each(
				function () 
				{					
					numFila = $(this).attr("id").replace('' + grid + '_Fila', "");
					
					var idArticulo = celdaValorXY(grid, 2, numFila);
					var idArtExist = celdaValorXY(grid, 7, numFila);
					var cantSurtir = celdaValorXY(grid, 9, numFila);
					//http://localhost:8082/rc/code/ajax/getDatosAjax.php?&opc=9&tipo_evento=1&idArticulo=3&fecha_entrega_articulos=23/02/2014&hora_entrega=10&fecha_recoleccion=25/02/2014&hora_recoleccion2=6&idArtExist=100&cantSurtir=50
					var array = new Object(); 
					array['opc'] = 9;
					array['tipo_evento'] = tipo_evento;
					array['idArticulo'] = idArticulo;
					array['fecha_entrega_articulos'] = fecha_entrega_articulos;
					array['hora_entrega'] = hora_entrega;
					array['fecha_recoleccion'] = fecha_recoleccion;
					array['hora_recoleccion2'] = hora_recoleccion2;
					array['idArtExist'] = idArtExist;
					array['cantSurtir'] = cantSurtir;
					
					$.ajax({
						async:false,
						url: '../../code/ajax/getDatosAjax.php',
						cache:false,
						dataType:"html", 
						data:array,
						type: 'POST',
						success: function(resp){ 
							if(resp != '1')
							{
								alert('La existencia del producto "' + celdaValorXY(grid, 3, numFila) + '" del grid "Detalle art\u00EDculos para transformaciones especiales" es menor a lo que necesita');
								respuestaInvalidas = respuestaInvalidas + 1;
							}
						},
						error: function(data) {
							alert('Error al conectar con el servidor'); //or whatever
							return false;
						}
					});
				}
			);
			
			if(respuestaInvalidas > 0) 
				return false;
			
			
			
			//VALIDAR TODOS LOS CAMPOS TENGAN DATOS
			numero_personas = $("#numero_personas").val(); 
						
			id_tipo_evento = $("#id_tipo_evento").val();
			tipo_entrega_cliente = $("#tipo_entrega_cliente").val();
			persona_recibe = $("#persona_recibe").val();
			id_almacen_recoge = $("#id_almacen_recoge").val();
			direccion_entrega = $("#direccion_entrega").val();
			direccion_evento = $("#direccion_evento").val();
			tipo_regreso_almacen = $("#tipo_regreso_almacen").val();
			persona_entrega = $("#persona_entrega").val();
			id_almacen_entrega = $("#id_almacen_entrega").val();
			direccion_recoleccion = $("#direccion_recoleccion").val();
			
			//DATOS FISCALES			
			id_tipo_documento = $("#id_tipo_documento").val();
			nombre_razon_social = $("#nombre_razon_social").val();
			rfc = $("#rfc").val();
			calle = $("#calle").val();
			numero_exterior = $("#numero_exterior").val();
			numero_interior = $("#numero_interior").val();
			colonia = $("#colonia").val();
			delegacion_municipio = $("#delegacion_municipio").val();
			codigo_postal = $("#codigo_postal").val();
			id_estado = $("#id_estado").val();
			id_ciudad = $("#id_ciudad").val();
		
			if(numero_personas == "")
			{
				alert("Falta llenar el campo N\u00FAmero de Personas");
				$("#numero_personas").focus();
				return false;
			}
			if(id_tipo_evento == "0")
			{
				alert("Falta llenar el campo Tipo de Evento");
				$("#id_tipo_evento").focus();
				return false;
			}
			if(tipo_entrega_cliente == "0")
			{
				alert("Falta llenar el campo Tipo de Entrega");
				$("#tipo_entrega_cliente").focus();
				return false;
			}
			if(persona_recibe == "0")
			{
				alert("Falta llenar el campo Persona Recibe");
				$("#persona_recibe").focus();
				return false;
			}
			if(id_almacen_recoge == "0")
			{
				alert("Falta llenar el campo Almac\u00E9n Recoge");
				$("#id_almacen_recoge").focus();
				return false;
			}
			if(direccion_entrega == "0")
			{
				alert("Falta llenar el campo Direcci\u00F3n Entrega");
				$("#direccion_entrega").focus();
				return false;
			}
			if(direccion_evento == "0")
			{
				alert("Falta llenar el campo Direcci\u00F3n Evento");
				$("#direccion_evento").focus();
				return false;
			}
			if(tipo_regreso_almacen == "0")
			{
				alert("Falta llenar el campo Tipo Regreso a Almac\u00E9n");
				$("#tipo_regreso_almacen").focus();
				return false;
			}
			if(persona_entrega == "0")
			{
				alert("Falta llenar el campo Persona Regresa");
				$("#persona_entrega").focus();
				return false;
			}
			if(id_almacen_entrega == "0")
			{
				alert("Falta llenar el campo Almac\u00E9n Entrega");
				$("#id_almacen_entrega").focus();
				return false;
			}
			if(direccion_recoleccion == "0")
			{
				alert("Falta llenar el campo Direcci\u00F3n Recolecci\u00F3n");
				$("#direccion_recoleccion").focus();
				return false;
			}
			if(id_tipo_documento == 0)
			{
				alert("Falta llenar el campo Tipo de Documento");
				$("#id_tipo_documento").focus();
				return false;
			}	
			if((id_tipo_documento == "3" || id_tipo_documento == "1") && nombre_razon_social == "")
			{
				alert("Falta llenar el campo Raz\u00F3n social");
				$("#nombre_razon_social").focus();
				return false;
			}
			if((id_tipo_documento == "3" || id_tipo_documento == "1") && rfc == "")
			{
				alert("Falta llenar el campo RFC");
				$("#rfc").focus();
				return false;
			}
			if((id_tipo_documento == "3" || id_tipo_documento == "1") && calle == "")
			{
				alert("Falta llenar el campo Calle");
				$("#calle").focus();
				return false;
			}
			if((id_tipo_documento == "3" || id_tipo_documento == "1") && numero_exterior == "")
			{
				alert("Falta llenar el campo N\u00FAmero Exterior");
				$("#numero_exterior").focus();
				return false;
			}
			if((id_tipo_documento == "3" || id_tipo_documento == "1") && numero_exterior == "")
			{
				alert("Falta llenar el campo N\u00FAmero Exterior");
				$("#numero_exterior").focus();
				return false;
			}
			if((id_tipo_documento == "3" || id_tipo_documento == "1") && colonia == "")
			{
				alert("Falta llenar el campo Colonia");
				$("#colonia").focus();
				return false;
			}
			if((id_tipo_documento == "3" || id_tipo_documento == "1") && delegacion_municipio == "")
			{
				alert("Falta llenar el campo Delegaci\u00F3n Municipio");
				$("#delegacion_municipio").focus();
				return false;
			}
			if((id_tipo_documento == "3" || id_tipo_documento == "1") && codigo_postal == "")
			{
				alert("Falta llenar el campo C\u00F3digo Postal");
				$("#codigo_postal").focus();
				return false;
			}
			if((id_tipo_documento == "3" || id_tipo_documento == "1") && id_estado == "0")
			{
				alert("Falta llenar el campo Estado");
				$("#id_estado").focus();
				return false;
			}
			if((id_tipo_documento == "3" || id_tipo_documento == "1") && id_ciudad == "0")
			{
				alert("Falta llenar el campo Ciudad");
				$("#id_ciudad").focus();
				return false;
			}
						
			
			if(numero_personas != "" && id_tipo_evento != "0" && tipo_entrega_cliente != "0" && persona_recibe != "0" && id_almacen_recoge != "0" && direccion_entrega != "0" && direccion_evento != "0" && tipo_regreso_almacen != "0" && persona_entrega != "0" && id_almacen_entrega != "0" && direccion_recoleccion != "0" &&  (((id_tipo_documento == "3" || id_tipo_documento == "1") && nombre_razon_social != "" && rfc != "" && calle != "" && numero_exterior != "" && colonia != "" && delegacion_municipio != "" && codigo_postal != "" && id_estado != "0" && id_ciudad != "0" ) || (id_tipo_documento == "2")))
			{
				if (!confirm("Todas las solicitudes se envíarán a los respectivos departamentos para ser completadas o aprobadas.\n\n"+String.fromCharCode(191)+"Desea continuar?"))
					return false;
			}
			else
			{
				alert("Debe llenar todos los campos.");
				return false;
			}	
		}
		else
		{
			if (!confirm("La solicitud de cotización se guardará como aún modificable,\n\n-No apartarán los articulos solicitados ni se enviará ninguna solicitud para ser completada o aprobada. \n\n"+String.fromCharCode(191)+"Desea continuar?"))
				return false;			
		}
	}
	
	
	
	//validaciones  rac_clientes
	if(objform.t.value == "cmFjX2NsaWVudGVz"){
		if(validaClientes()==false)
			return false;
		
	}
	
	if(objform.t.value=="cGV1Z19jbGllbnRlc191c3Vhcmlvc193ZWI=")
	{
		if(validaContrasenia()==false)
			return false;
	}
	
	
	
	//excepciones de proyecto
	//--------------------
	//si la tabla es de articulos
	//--------------------
	 if(objform.t.value == "cmFjX2FydGljdWxvcw==")
	 {
		 
		if((validaCodigoArticulo()==false))
			return false;
	 }
	
	
	 if(objform.t.value == "cmFjX21vdmltaWVudG9zX2FsbWFjZW4=")
	 {
		 
		//realizamos la validación y que enbvie las existencias
		if((validaAlmacenSalidasExistencias()==false))
			return false;
	 }
	

	
	//VALIDACION DEL GRID
	if(objform.t.value != "XXX"){ //si es diferente a la tabla XXX
		var ng=parseInt(objform.ngrids.value);

		for(var i=0;i<ng;i++)
		{
			if(validaGridTotalCampos(objform.elements["grid_"+i].value) == false)
				return false;
			
			if(objform.elements["guardaen_"+i].value.length > 0 && objform.elements["guardaen_"+i].value != "NO")
			{			
				var aux=GuardaGrid(objform.elements["grid_"+i].value, 10);		
				//alert(aux);
				var ax=aux.split('|')
				if(ax[0] == 'exito')
				{
					objform.elements["file_"+i].value=ax[1];
					//alert("file_"+i+" = "+ax[1]);
				}	
				else		
					alert(aux);	
			}		
		}
	}//fin de tabla excepcion
	//TERMINA VALIDACION DEL GRID
	
	

	
	

	
	if(validaIDEdit=='si')
	{
		 
		var campo=objform.elements['campo_0'].id;
		var valor=objform.elements['campo_0'].value;
		var tabla=objform.elements['t'].value;
		
		objform.elements['generaSubmit'].value='1';
				
		makeRequest('../ajax/ajaxresponse.php?campo='+campo+'&id='+valor+'&tabla='+tabla+'&code=si','validaId');
		
		return false;
	}
	//return false;
	objform.strSelected.value=strSelected;
	document.getElementById('guardarb').disabled="true";
	
	
	
	
	
	//EXCEPCIONES POR CATALOGO 



	
	/***************************** VALIDACIONES PARA NO INSERTAR REPETIDOS EN CATALOGOS ***********************/
	if (document.forma_datos.make.value == "insertar")
	{
		/************** VALIDA ESTADOS ***************************/
		if(objform.t.value=="b2ZfZXN0YWRvcw==")
		{
			if(objform.nombre.value != null)
			{		
				var v_pais = objform.id_pais.value;
				var v_nombre = objform.nombre.value;
				var v_nom = v_nombre.toUpperCase(); 
		
				var aux = ajaxR("../ajax/validaRegistrosRepetidos.php?accion=1" + "&nombre="+v_nom+"&id_pais="+v_pais);
				var ax = aux.split("|");
				if(ax[0] == 'exito')
				{
					alert(ax[1]);
					return false;			 
				}
			}
		}
		/************** VALIDA ESTADOS ***************************/
		
		
		/************** VALIDA PAIS ***************************/
		if(objform.t.value == "b2ZfcGFpc2Vz")
		{
			if(objform.nombre.value != null)
			{		
				var v_nombre = objform.nombre.value;
				var v_nom = v_nombre.toUpperCase(); 
		
				var aux = ajaxR("../ajax/validaRegistrosRepetidos.php?accion=4" + "&nombre=" + v_nom);
				var ax = aux.split("|");
				if(ax[0] == 'exito')
				{
					alert(ax[1]);
					return false;			 
				}
			}
		}
		/************** VALIDA PAIS ***************************/
		
		
		/************** VALIDA CIUDAD ***************************/
		if(objform.t.value == "b2ZfY2l1ZGFkZXM=")
		{
			if(objform.nombre.value != null)
			{		
				var v_id_pais = objform.id_pais.value;
				var v_id_estado = objform.id_estado.value;
				var v_nombre = objform.nombre.value;
				var v_nom = v_nombre.toUpperCase(); 
		
				var aux = ajaxR("../ajax/validaRegistrosRepetidos.php?accion=2" + "&nombre=" + v_nom + "&id_pais=" + v_id_pais + "&id_estado=" + v_id_estado);
				var ax = aux.split("|");
				if(ax[0] == 'exito')
				{
					alert(ax[1]);
					return false;			 
				}
			}
		}
		
		/************** VALIDA PEDIDOS ***************************/
		
		/*if(objform.t.value == "YWRfcGVkaWRvcw==")
		{
			if(objform.id_pedido.value != null)
			{		
				var pedido = objform.id_pedido.value;
		alert(pedido);
				var aux = ajaxR("../ajax/validaRegistrosRepetidos.php?accion=34" + "&pedido=" + pedido);
				var ax = aux.split("|");
				if(ax[0] == 'exito')
				{
					alert(ax[1]);
					return false;			 
				}
			}
		}*/
		
		/************** VALIDA PEDIDOS CAJA CHICA***************************/
		
		if(objform.t.value == "bmFfZWdyZXNvc19jYWphX2NoaWNh" || objform.t.value == "bmFfaW5ncmVzb3NfY2FqYV9jaGljYQ=="){
			var pedido = objform.id_pedido.value;
			var envia_datos = "id=" + pedido;
			var url = "validaPedido.php";
			var result = ajaxN(url, envia_datos);
			
			var condiciones = result.split("|");
			
			if(objform.t.value == "bmFfZWdyZXNvc19jYWphX2NoaWNh"){
					var opcion = $("#id_tipo_egreso").find("option:selected").val();
					var compara = 5;
					}
			else{
					var opcion = $("#id_tipo_ingreso").find("option:selected").val();
					var compara = 1;
					}
					
			if(condiciones[0] == 0 && opcion == compara){
				alert("El pedido no existe o es incorrecto");
				return false;
				}
			else{
					if(condiciones[1] == 5){
							alert("Pedido cancelado por el cliente");
							return false;
							}
					else if(condiciones[1] == 6){
							alert("Pedido cancelado por el vendedor");
							return false;
							}
					
					}
					
		}
		
		
		
	}
	/***************************** TERMINA VALIDACIONES PARA NO INSERTAR REPETIDOS EN CATALOGOS ***********************/
	/********Validacion de CUENTAS POR COBRAR************/
	if(objform.t.value == 'bmFfY3VlbnRhc19wb3JfY29icmFy'){
			var saldo = $("#saldo").val();
			if(saldo.indexOf("-") != -1){
					alert("El monto total de pagos\nno puede ser mayor al total de la cuenta por cobrar");
					return false;
					}
			}
	/****Validacion de los pedidos *****/
		if(objform.t.value == 'YWRfcGVkaWRvcw==='){
		
				/*var saldo = $("#saldo").val();
				if(saldo.indexOf("-") != -1){
						alert("El monto total de pagos\nno puede ser mayor al total de pedidos");
						return false;
						}*/
				/***********************************************/
				/************Comienza validaciones nde fletes **************************/

				//Funcion agregada a javascript para limpiar un array de elementos repetidos
				Array.prototype.unique=function(a){
						return function(){return this.filter(a)}}(function(a,b,c){return c.indexOf(a,b+1)<0
						});		
								
				var direccion = $("#id_direccion_entrega").find("option:selected").val();
				var url = "obtenCobraFlete.php";
				var envia_datos = "direccion=" + direccion;		
				var tipo_ruta = ajaxN(url, envia_datos); //Obtenemos si local o foranea la ruta de la direccion de entrega
				
				//Verificamos si la tabla de detalle de fletes esta vacia o el numero de elementos que contiene
				var contadorFlete = 0;
				$('table#Body_detallePedidosFletes tr').each(function(index) {
						var totalFlete = $(this).children().filter("[id^=detallePedidosFletes_6_]").attr("valor");
						if(totalFlete != ""){
								contadorFlete += 1;
								}
						});
				
				//Obtenemos las combinaciones de tipos de entrega y el numero de fecha
				var prodircliente = 0;
				var otros = 0;
				var programada = 0;
				var importes = 0;
				var fechasMayores = 0;
				var fecha_entrega = new Array();
				
				var fecha_alta = $("#fecha_limite_pago").val();
				var fecAltaConv = convierteFechaJava(fecha_alta); //Convertimos la fecha para evaluarla en los dias habiles
				var diaHabil = diasHabiles(fecAltaConv, 3); //diaHabil es el rango con el que se avaluara 
				var fecAltaConvVal = convierteFechaJava(fecha_alta);
				var textoFechas = "";
				
				
				
				var requiere_factura1 = $("#requiere_factura").find("option:selected").val();
				if(requiere_factura1=='2')
				{
					alert("Seleccione si el cliente requiere o no factura");
					$("#requiere_factura").focus();
					return false;
				}
				
				
				$('table#Body_detallePedidosProductos tr').each(function() {
				
						var fila_s = $(this).attr("id");
						var fila = fila_s.split("Fila");
						var fila_real = parseInt(fila[1]) + 1;
						
						var tipo_entrega = $(this).children().filter("[id^=detallePedidosProductos_8_]").attr("valor");
						var lugar_entrega = $(this).children().filter("[id^=detallePedidosProductos_10_]").attr("valor");
						var fechas = $(this).children().filter("[id^=detallePedidosProductos_15_]").attr("valor");
						var importe = $(this).children().filter("[id^=detallePedidosProductos_16_]").attr("valor");
						
						var fecEntConv = convierteFechaJava(fechas);
						
						if(fecEntConv < diaHabil && tipo_entrega == 1){
								fechasMayores += 1;
								textoFechas += "La fecha de entrega debe ser mayor a tres dias habiles\na partir de la fecha limite de pago\nFila " + fila_real;
								}
						if(tipo_entrega == 2 && importe != 0){
								importes += 1;
								}
						if(tipo_entrega == 1){
								programada += 1;
								}
						
						if(tipo_entrega == 1 && lugar_entrega == 1){ //Si el tipo de entrega es programada y en la direccion del cliente y esta es foranea
								prodircliente += 1;
								if(fechas != "")
										fecha_entrega.push(fechas);
										
								}
						else{
								otros += 1;
								}
						});
				//var nuevoFecha = [].concat(fecha_entrega.unique());  Copiamos un array dentro de otro
				var fechasDif = fecha_entrega.unique().length;  //Contamos el numero de elementos con el array ya limpio. Unique viene de la funcion prototype declarada arriba
				
				if(prodircliente > 0 && tipo_ruta == 2 && contadorFlete == 0){
						alert("Ruta Foranea: Se debe ingresar al menos un flete");
						return false;	
						}
						
				if(prodircliente >= 3 && tipo_ruta == 1 && fechasDif >= 3 && contadorFlete == 0){
						alert("Mas de 3 entregas a ruta local: Se debe ingresar al menos un flete");
						return false;	
						}
				
				if(prodircliente < 3 && tipo_ruta == 1 && otros >= 0 && contadorFlete != 0){
						alert("No se cobra flete. Por favor elimine el detalle registrado");
						return false;	
						}
				if(importes > 0 && programada > 0){
								alert("Se deben hacer dos pedidos para estos productos");
								return false;	
								}
								
				if(fechasMayores > 0){
						alert(textoFechas);
						return false;	
						}
				
				//Validacion de si son requeridos los campos documentos y terminal bancaria
				var erroresPagos = 0;
				$('table#Body_detallePedidosPagos tr').each(function() {
						var terminalReq = $(this).children().filter("[id^=detallePedidosPagos_16_]").attr("valor");
						var documentoReq = $(this).children().filter("[id^=detallePedidosPagos_17_]").attr("valor");
						var autorizacionReq = $(this).children().filter("[id^=detallePedidosPagos_18_]").attr("valor");
						var bancosReq = $(this).children().filter("[id^=detallePedidosPagos_30_]").attr("valor");
						
						var terminal = $(this).children().filter("[id^=detallePedidosPagos_6_]").attr("valor");
						var documento = $(this).children().filter("[id^=detallePedidosPagos_7_]").attr("valor");
						var autorizacion = $(this).children().filter("[id^=detallePedidosPagos_8_]").attr("valor");
						var bancos = $(this).children().filter("[id^=detallePedidosPagos_9_]").attr("valor");
						
						var fila_s = $(this).attr("id");
						var fila = fila_s.split("Fila");
						var fila_real = parseInt(fila[1]) + 1;
						
						if(terminalReq == 1 && terminal == ""){
								alert("Seleccione una terminal bancaria en el detalle de pagos\nFila: " + fila_real);
								erroresPagos += 1;
								return false;	
								}
						if(documentoReq == 1 && documento == ""){
								alert("Anote el numero de documento en el detalle de pagos\nFila: " + fila_real);
								erroresPagos += 1;
								return false;	
								}
						if(autorizacionReq == 1 && autorizacion == ""){
								alert("Anote el numero de aprobacion en el detalle de pagos\nFila: " + fila_real);
								erroresPagos += 1;
								return false;	
								}
						if(bancosReq == 1 && bancos == ""){
								alert("Seleccione un banco en el detalle de pagos\nFila: " + fila_real);
								erroresPagos += 1;
								return false;	
								}
						});
				if(erroresPagos > 0)		
						return false;
						
				/***** Validaciones de pagos sobre productos inmediatos ****/
						var sumaInmediato = 0;
						$('table#Body_detallePedidosProductos tr').each(function() {
								var tipo_entrega = $(this).children().filter("[id^=detallePedidosProductos_8_]").attr("valor");
										if(tipo_entrega == 2){
												var inmediato = $(this).children().filter("[id^=detallePedidosProductos_16_]").attr("valor");
												sumaInmediato += parseInt(inmediato);
												}
								});
						var pagos = $("#total_pagos").val();
						pagos = pagos.replace(",","");
						
						if(pagos < sumaInmediato){
								alert("Los pagos no cubren el total\nde los productos con entrega inmediata");
								return false;
								}
				var consecutivo = $("#consecutivo").val();
				var prefijo = $("#prefijo").val();
				var url = "verificaDuplicadosPedido.php";
				var envia_datos = "consecutivo=" + consecutivo + "&prefijo=" + prefijo;
				var respuestaDup = ajaxN(url, envia_datos);
				if($("#op").val() == 1){
						if(respuestaDup > 0){
								var nuevoCon = parseInt(consecutivo) + 1;
								var nuevoPre = prefijo + nuevoCon;
								$("#consecutivo").val(nuevoCon);
								$("#id_pedido").val(nuevoPre);
								alert("El pedido que intenta guardar ya tiene asignado el numero de pedido\nse sugiere el pedido " + nuevoPre + " para guardar");
								return false;
								}
						}
				/*********Validacion de vale de productos **********************/
				var errorVale = 0;
				$('table#Body_detallePedidosPagos tr').each(function() {
						var buscaVale = $(this).children().filter("[id^=detallePedidosPagos_3_]").attr("valor");
						if(buscaVale == 9){
								var clienteVale = $("#hcampo_5").val();
								var valeno = $(this).children().filter("[id^=detallePedidosPagos_7_]").attr("valor");
								var montoVale = $(this).children().filter("[id^=detallePedidosPagos_11_]").attr("valor");
								var envia_datos = "vale=" + valeno + "&cliente=" + clienteVale + "&monto=" + montoVale;
								var url = "verificaValeGuardado.php";
								var result = ajaxN(url, envia_datos);
								var datos = JSON.parse(result);
								
								if(datos.status == 0){
										var fila_s = $(this).attr("id");
										var fila = fila_s.split("Fila");
										var fila_real = parseInt(fila[1]) + 1;
										alert(datos.mensaje + "\nFila: " + fila_real);
										errorVale += 1;
										return false;
										}
								}
						
						});
				if(errorVale > 0)		
						return false;
				
				/*alert("Todo bien");
				return false;	*/
				}
	
	//execiones CATALGO
	var valRegresoCat=validaCatalogo(objform.t.value);
	
	if(valRegresoCat==0)
	{
		return false;
	}
	
	if(objform.t.value == "YWRfcGVkaWRvcw=="){
			var pagina = $(location).attr('href');
			if(/idPedido=/.test(pagina)){ //Buscamos en la url que venga de prepedido
				var prepedido = pagina.split("idPedido=");
				var id_pedido = $("#id_pedido").val();
				var envia_datos = "id_pedido=" + id_pedido + "&prepedido=" + prepedido[1];		
				var url = "insertaPedidoPrepedido.php";
				ajaxN(url, envia_datos); //Obtenemos si local o foranea la ruta de la direccion de entrega
				}
			
			}
		if(objform.t.value == "bmFfcHJvZHVjdG9z"){
				/****** Validacion para filas del grid Detalle de Productos Básicos no sean vacios si esta seleccionado el producto compuesto********************/
						if($("#producto_compuesto").is(':checked')){
								var filas_grid_producto = $("table#Body_detalleProductosBasicos > tbody > tr").length;
								var sumaFilas = 0;
								if(filas_grid_producto > 0){
										$('table#Body_detalleProductosBasicos tr').each(function(index){
												var productos = $(this).children().filter("[id^=detalleProductosBasicos_2_]").attr("valor");
												var cantidad = $(this).children().filter("[id^=detalleProductosBasicos_4_]").attr("valor");
												if(productos == "" || cantidad == ""){
														sumaFilas += 1;
														}
												});
										}
								if(filas_grid_producto == 0 || sumaFilas > 0){
										alert("No puedes dejar campos vacios en Detalle de Productos Básicos");
										return false;
										}
								
								}
						
				/**************************Validacion de nombres y sku duplicados*************************************/
				
				if($("#op").val() == 2)
						var idProducto = $("#id_producto").val();
				else
						var idProducto = "";
						
				var sku = $("#sku").val();
				var producto = $("#nombre").val();
				var envia_datos = "sku=" + sku + "&producto=" + producto + "&idProducto= " + idProducto;		
				var url = "verificaDuplicados.php";
				var respuesta = ajaxN(url, envia_datos); //Obtenemos si local o foranea la ruta de la direccion de entrega
				
				if(respuesta == 1){
						alert("Este SKU ya esta registrado para otro producto");
						return false;
						}
				}
		if(objform.t.value == "bmFfYml0YWNvcmFfcnV0YXM="){
				if($("#cerrar_bitacora").is(':checked') && $("#km_finales").val() == ""){
						alert("Kilometraje final es requerido");
						return false;
						}
						
				}
				

		//if(objform.t.value == "bmFfZWdyZXNvcw=="){ JA
		if(objform.t.value == "YWRfZWdyZXNvcw=="){
				
				if($("#op").val() == 1){
						var cuentaDetalleEgresos = $('table#Body_detalleEgresos tr').length;
						var cuentaCC = $('table#Body_detalleEgresosCuentasContables tr').length;
						
						$('table#Body_detalleEgresosCuentasContables tr').each(function(index){
										var cc = $(this).children().filter("[id^=detalleEgresosCuentasContables_2_]").attr("valor");
										var nombre = $(this).children().filter("[id^=detalleEgresosCuentasContables_4_]").attr("valor");
										var total = $(this).children().filter("[id^=detalleEgresosCuentasContables_5_]").attr("valor");
										var observaciones = $(this).children().filter("[id^=detalleEgresosCuentasContables_6_]").attr("valor");
										if(cc != "" || nombre != "" || total != "" || observaciones != ""){
												cuentaCC += 1;
												}
										});
						
						if(cuentaDetalleEgresos == 0 && cuentaCC == 0){
								alert("Debes registrar datos en al menos un Grid");
								return false;
								}
						}
				
				
				if($("#a_nombre_de").val() == ""){
						$('table#Body_detalleEgresos tr').each(function(index){
								var proveedor = $(this).children().filter("[id^=detalleEgresos_3_]").text();
								$("#a_nombre_de").val(proveedor);
								});
						}
				}
		if(objform.t.value == "bmFfb3JkZW5lc19yZWNvbGVjY2lvbl9jbGllbnRlcw=="){
				var direccion = $("#id_direccion_cliente").find("option:selected").val();
				if(direccion == 0){
						alert("Seleccione una direcci\u00f3n");
						return false;
						}
				}
		/***Egresos caja chica***********/
		if(objform.t.value == "bmFfZWdyZXNvc19jYWphX2NoaWNh"){
				var total = $("#total").val();
				total = total.replace(",", "");
				total = total.replace(",", "");
				total = total.replace(",", "");
				total = total.replace(",", "");
				total = total.replace(",", "");
				total = total == "" ? total = 0 : total = total;
				var total_caja = $("#total_caja").val();
				total_caja = total_caja.replace(",", "");
				total_caja = total_caja.replace(",", "");
				total_caja = total_caja.replace(",", "");
				total_caja = total_caja.replace(",", "");
				
				total_caja = total_caja == "" ? total_caja = 0 : total_caja = total_caja;
				if(parseFloat(total) > parseFloat(total_caja)){
						alert("El total del egreso no puede ser mayor\nal saldo de la caja chica");
						return false;
						}
				
				}
		/*******Ingresos caja chica****************/
		if(objform.t.value == "bmFfaW5ncmVzb3NfY2FqYV9jaGljYQ=="){
				var monto_egreso = $("#monto_egreso").val();
				var tipo = $("#id_tipo_ingreso").find("option:selected").val();
				monto_egreso = monto_egreso.replace(",", "");
				monto_egreso = monto_egreso.replace(",", "");
				monto_egreso = monto_egreso.replace(",", "");
				monto_egreso = monto_egreso.replace(",", "");
				monto_egreso = monto_egreso.replace(",", "");
				
				monto_egreso = monto_egreso == "" ? monto_egreso = 0 : monto_egreso = monto_egreso;
				var monto_confirma = $("#monto").val();
				monto_confirma = monto_confirma.replace(",", "");
				monto_confirma = monto_confirma.replace(",", "");
				
				
				monto_confirma = monto_confirma == "" ? monto_confirma = 0 : monto_confirma = monto_confirma;
				
				if((parseFloat(monto_confirma) > parseFloat(monto_egreso)) && tipo <= 3){
						alert("No puedes confirmar un monto mayor al del egreso");
						return false;
						}
				
				}
		/***Ordenes de Compra***********/
		if(objform.t.value == "YWRfb3JkZW5lc19jb21wcmFfcHJvZHVjdG9z"){
				var validaP = 0;
				var repetido = 0;
				$('#Body_detalleProductosOrdenesCompra tr').each(function(index){
						var producto = $(this).children().filter("[id^=detalleProductosOrdenesCompra_2_]").attr("valor");
						if(producto == validaP)
								repetido += 1;
						validaP = producto;
						});
				
				if(repetido > 0){
						alert("Esta repetido");
						return false;
						}
				}
		//if(objform.t.value == "bmFfY3VlbnRhc19wb3JfcGFnYXI="){ //Esto es para na_cuentas_por_pagar		bmFfY3VlbnRhc19wb3JfcGFnYXI=  	Mod. JA 01/12/2015
		if(objform.t.value == "YWRfY3VlbnRhc19wb3JfcGFnYXJfb3BlcmFkb3Jh"){ 	//Esto es para ad_cuentas_por_pagar_operadora		YWRfY3VlbnRhc19wb3JfcGFnYXJfb3BlcmFkb3Jh  	Mod. JA 01/12/2015
				if($("#v").val() == 0){
						if(/-/.test($("#saldo").val())){
								alert("El total de pagos no puede ser mayor\nal total de la cuenta por pagar");
								}
				
						var opcion = $("#id_tipo_sucursal_cxp").find("option:selected").val();
						if(opcion == 2){
								var error = 0;
								var contador = 1;
								$('table.cuerpo-sucursales tr').each(function(index){
										var monto = $("#monto_cxp" + contador).val();
										var porcentaje = $("#porcentaje_cxp" + contador).val();
										if(monto != "" || porcentaje != "")
												error += 1;
										contador++;
										});
								if(error == 0){
										alert("Al seleccionar multisucursal se debe llenar al menos un detalle\nde la tabla de sucursales");
										return false;
										}
								var totalcxp = $("#total").val();
								var porcentaje = $("#porc_suc_cxp").val();
								var totalSuc = $("#total_suc_cxp").val();
								
								totalcxp = totalcxp == "" ? totalcxp = 0 : totalcxp = totalcxp; 
								porcentaje = porcentaje == "" ? porcentaje = 0 : porcentaje = porcentaje; 
								totalSuc = totalSuc == "" ? totalSuc = 0 : totalSuc = totalSuc; 
								
								totalcxp = totalcxp.toString().replace(",", "");
								totalcxp = totalcxp.toString().replace("$", "");
								porcentaje = porcentaje.toString().replace(",", "");
								totalSuc = totalSuc.toString().replace(",", "");
								totalSuc = totalSuc.toString().replace("$", "");
								
								
								
								/*if(parseFloat(totalSuc) != parseFloat(totalcxp) && parseFloat(porcentaje) != 100){
										alert("El monto total de las sucursales\ndebe ser igual al total de la cuenta por pagar");
										return false;
										}*/
								
								}
						
						}
				
				}
	if(objform.t.value == "bmFfdmFsZXNfcHJvZHVjdG9z"){
			var pedido = $("#id_pedido_relacionado").find("option:selected").val();
			if(pedido == 0){
					alert("Seleccione un pedido");
					return false;
					}
			
			
			}
	if(objform.t.value == "bmFfcHJvdmVlZG9yZXM="){
			
			if($("#op").val() == 1){
					var rfc = $("#rfc").val();
					var envia_datos = "rfc=" + rfc;		
					var url = "validaProveedores.php";
					var respuesta = ajaxN(url, envia_datos); //Obtenemos si local o foranea la ruta de la direccion de entrega
						
					if(respuesta > 0){
							alert("Este RFC de proveedor ya ha sido registrado");
							return false;
							}
					}
		}
	//Validacion de movimientos almacen
	if(objform.t.value == "YWRfbW92aW1pZW50b3NfYWxtYWNlbg==")
	{
			
			var error = 0;
			var error2 = 0;
			var filaError = "";
			var filaError2 = "";
			var respuesta2="";
			var subtipo = $('#id_subtipo_movimiento').find("option:selected").val();
			/* Para realizar las validaciones de los irds esta pendiente por gaby
			for(i=0;i<NumFilas('detalleMovimientosAlmacen');i++){
				var irds=celdaValorXY('detalleMovimientosAlmacen',26,i);
				var arr_irds=irds.split(',');
				if(arr_irds.length<celdaValorXY('detalleMovimientosAlmacen',11,i)||arr_irds.length>celdaValorXY('detalleMovimientosAlmacen',11,i)){
					alert("La cantidad de IRDS capturadas en la fila "+parseInt(i+1)+" es menor o mayor a la solicitada");
					return false;
				}
				var url = "../ajax/ValidaNumerosDeSerieGrid.php";
				var data = "arreglo_numeros_serie="+irds+"&idOrdenCompra=0&idAlmacen=0&idProducto=0&idPlaza=0&accion=valida&idCarga=0&idDetalleOrdenCompra=0";
				var resultado=ajaxN(url,data);
				var exito=resultado.split('|');
				console.log(exito);
				if(exito[0]=="correcto")
					return true;
				else{
					alert(resultado);
					return false;
				}
			}*/
			$('#Body_detalleMovimientosAlmacen tr').each(function(index){
					var Cadenafila = $(this).attr("id");
					var fila = Cadenafila.split("Fila");
					var existencia = parseInt($(this).children().filter("[id^=detalleMovimientosAlmacen_10_]").attr("valor"));
					var cantidad = parseInt($(this).children().filter("[id^=detalleMovimientosAlmacen_11_]").attr("valor"));
					if(cantidad > existencia){
							error += 1;
							filaError = fila[1];
							}
							
							if( 0 >= cantidad){
							error2 += 1;
							filaError2 = fila[1];
							}
							
							
							
							
			});
			
			//si estamos en una sallida de cualquier tipo, validamos que la existencia aun exista
			
			var filaErrorReal=parseInt(filaError+1);
			var filaErrorReal2=parseInt(filaError2+1);
			if(error > 0 && subtipo != 70004 && subtipo != 70005 && subtipo != 70006 && subtipo != 70008){
					alert("La cantidad no puede ser mayor a las existencias del producto\nen la fila " +filaErrorReal);
					return false;
					}
					
			if(error2 > 0 ){
				 alert("La cantidad no puede ser menor o igual a cero del producto\nen la fila " + filaErrorReal2);
			return false;
			}
			
			
			//realizamos una segunda validacion para no mostrar los repetidos , gacemo suna extraccion de todos los productos para saber cual agregaron y posterioj mente 
			gridAlmacen="detalleMovimientosAlmacen";
			
		
			if(NumFilas(gridAlmacen)>0 && (subtipo == 70011 || subtipo == 70012 || subtipo == 70033 || subtipo == 70055 || subtipo == 70066 || subtipo == 70088  || subtipo == 71010))
			{
				for(i=0;i<NumFilas(gridAlmacen);i++)
				{
						
					var id_pto= celdaValorXY(gridAlmacen,2,i);
					var id_lote= celdaValorXY(gridAlmacen,8,i);
					
					for(j=(i+1);j<NumFilas(gridAlmacen);j++)
						{
									
									id_pto2= celdaValorXY(gridAlmacen,2,j);
									id_lote2= celdaValorXY(gridAlmacen,8,j);
									
									if(id_pto==id_pto2 && id_lote==id_lote2)
									{
											alert("No puede realizar movimientos de salida del mismo producto con el mismo lote en el renglon :" +(j+1));
											return false;
									}
			
						
											  
						}	
					  
				}	
			}
			
				
	}
		
	
	objform.submit();	
		/***VALIDACION DE CERRAR FANCYBOX DENTRO DE LA TABLA DE PEDIDOS Y ACTUALIZAR EL SELECT DE CLIENTES***/
		
		var href = $(location).attr('href');
		href = href.split("modhf="); //Verificamos de donde viene el modificar si de catalogo normal o el fancybox
		var cierra = href[1] == 1 ? 1 : 0;
		
		var patt = /idCosteo=/;
			var result = patt.test(href);
			if(result === true)
					var cierraC = 1;
			else
					var cierraC = 0;
	if((objform.t.value == "bmFfY2xpZW50ZXM=" || objform.t.value == "bmFfY2xpZW50ZXNfZGlyZWNjaW9uZXNfZW50cmVnYQ==") && (objform.closewindow.value == 10 || cierra == 1))
			parent.$.fancybox.close();
			
	//else if(objform.t.value == "bmFfY3VlbnRhc19wb3JfcGFnYXI=" && cierraC == 1) //Esto es para na_cuentas_por_pagar		bmFfY3VlbnRhc19wb3JfcGFnYXI= 	Mod. JA 01/12/2015
	else if(objform.t.value == "YWRfY3VlbnRhc19wb3JfcGFnYXJfb3BlcmFkb3Jh" && cierraC == 1) //Esto es para ad_cuentas_por_pagar_operadora		YWRfY3VlbnRhc19wb3JfcGFnYXJfb3BlcmFkb3Jh 	Mod. JA 01/12/2015
			parent.$.fancybox.close();
			

	
			
	
			
	/*********************************************************************/
	//verifcamos el nombre de la tabla
	// si es una solcitud no puede ser modificada si tiene asignado un contrato
				
}
/*******************************************************/
function quitaComasForma(f)
{
	//alert(f.subtotal.value);
	f.subtotal.value=removeCommas(f.subtotal.value);
	f.total.value=removeCommas(f.total.value);
	return true;
}
/****************************************************/
function removeCommas(strValue)
{
	//objRegExp = /,/g;
	objRegExp = /\$|\(|[,]/g;
	//alert('pio'+strValue.replace(objRegExp,''));
	return strValue.replace(objRegExp,'');
}
/********************************************************/
//funcion 
function makeRequest(url,busca){

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
	
	if(busca=='validaId')
		http_request.onreadystatechange = validaId;
	else if(busca=='cargaOpciones') 
		http_request.onreadystatechange = cargaOpciones;
	
	http_request.open('GET', url, true);
	http_request.send(null);
	
	
}

function trim(cadena)
{
	for(i=0; i<cadena.length; )
	{
		if(cadena.charAt(i)==" ")
			cadena=cadena.substring(i+1, cadena.length);
		else
			break;
	}

	for(i=cadena.length-1; i>=0; i=cadena.length-1)
	{
		if(cadena.charAt(i)==" ")
			cadena=cadena.substring(0,i);
		else
			break;
	}
	
	return cadena;
}
function requiereNumeroSerie(Grid,renglon,columna){
	var id=celdaValorXY(Grid,columna,renglon);
	var result=ajaxN('requiereNoSerie.php','id='+id);
	var r=result.split('|');
	if(r[0]!="exito"){
		$("#"+Grid+"_24_"+renglon+">img").removeAttr('onclick');
		$("#"+Grid+"_25_"+renglon+">img").removeAttr('onclick');
	}
}