$(document).ready(function() {
/*
		if($("#t").val() == 'bmFfZmFjdHVyYXM=' && $("#op").val() == 1){
				obtenFactura(1);
				$("#id_pedido option").remove();
				$("#id_pedido").append('<option value="0">Seleccione Pedido</option>');
				}
		if($("#t").val() == 'bmFfbm90YXNfY3JlZGl0bw==' && $("#op").val() == 1){
				obtenFactura(2);
				$("#id_factura option").remove();
				$("#id_factura").append('<option value="0">Seleccione Factura</option>');
				}
*/
});
function obtenFacturasCliente(tabla,tablaFacturas){
	//si se mueve de posicion cambia el cambp hidden
	var id_cliente=id_cliente = $("#hcampo_8").val();
	var obj=document.getElementById('id_control_factura');
	//obtenemos las categorias de un producto dado la linea seleccionada
	//aux=ajaxR('../ajax/obtenValoresCombosFranUs.php?id_suc='+id_sucursal_franquicia+'&opcion='+document.getElementById('id_grupo').value);
	aux=ajaxR('../ajax/obtenDatosCombos.php?opcion=500&id='+id_cliente+'&tabla='+tabla+'&tfac='+tablaFacturas);
	
	//ahora mostramos los proyectos del contrato activos para
	limpiaCombo(obj);
	var arrResp=aux.split("|");
	
	var numDatos=parseInt(arrResp.length);		
	obj.options[0]=new Option("-Seleccione una Factura-",0);
	for(var i=2;i<numDatos;i++)
	{
		var arrDatos=arrResp[i].split("~");			
		obj.options[i-1]=new Option(arrDatos[1], arrDatos[0]);
	}
}		
function obtenPedidoFac(tabla){
		if(tabla=="ad_facturas_audicel")
			id_cliente = $("#hcampo_8").val();
		else if(tabla=="ad_facturas")
			id_cliente = $("#id_cliente").val();
		var selectHijo = 'id_control_pedido';
		var selectHijo2 = 'id_forma_pago_sat';
		var urlAjax = "obtenPedidoCliente.php";
		var envio_datos = 'cliente=' + id_cliente + "&caso=1"; 
		ajaxCombos(urlAjax, envio_datos, selectHijo);
		var envio_datos1 = 'cliente=' + id_cliente + "&caso=4&tabla="+tabla; 
		ajaxCombos(urlAjax, envio_datos1, selectHijo2);
		var envia_datos = 'cliente=' + id_cliente + "&caso=2&tabla="+tabla;
		var respuesta = ajaxN(urlAjax, envia_datos);
		var datos = JSON.parse(respuesta);
		$("#email_envio_fa").val(datos.email);
		$("#id_fiscales_cliente").val(datos.fiscal);
		
}	
function obtenDatosFactura(tabla)
{
	//de la factura seleccionada obtenemos los datos de  la sucursal
	
	//forma de pago SAT
	
	//
	var id_control_factura = $("#id_control_factura").val()
	
	var envia_datos = "id_control_factura=" + id_control_factura + "&caso=3&tabla="+tabla;
	var url = "obtenFacturaCliente.php";
	var factura = ajaxN(url, envia_datos);
	var datos = JSON.parse(factura);
	
	$('#id_sucursal > option[value="'+datos.id_sucursal+'"]').attr('selected', 'selected');
	
	//mandamos llamar el asigar folio
	obtenFactura(2);
	
	$('#id_forma_pago_sat > option[value="'+datos.id_forma_pago_sat+'"]').attr('selected', 'selected');
	


	$("#cuenta").val(datos.cuenta);
	$("#id_fiscales_cliente").val(datos.id_fiscales_cliente);
	$("#email_envio_fa").val(datos.email);
	
			
}
function obtenPrecioUnitario(tabla, renglonOrigen, columnaOrigen, renglonDestino, columnaDestino){
	// **********************************************************************************************************
	// CREACION: 16/10/2015
	// DESCRIPCION: Obtiene el precio Unitario a partir del id del Producto
	// **********************************************************************************************************
	// PARAMETROS:
	// tabla: Nombre identificador de la celda
	// renglonOrigen: Renglón DE donde se OBTENDRÁ el ID del producto para sacar su Precio Unitario (Inciando desde 0)
	// columnaOrigen: Columna DE donde se OBTENDRÁ el valor (Inciando desde 0)
	// renglonDestino: Renglón EN donde se COLOCARÁ en valor obtenido (Inciando desde 0)
	// columnaDestino: Columna EN donde se COLOCARÁ en valor obtenido (Inciando desde 0)
	// **********************************************************************************************************
	var idProducto = celdaValorXY(tabla, columnaOrigen, renglonOrigen);	//Se obtiene el ID del Producto
	var envia_datos = "id=" + idProducto;
	var url = "obtenPrecioProducto.php";
	var respuesta = ajaxN(url, envia_datos);
	var precio_unitario = respuesta.split("|");
	valorCeldaXY(tabla, columnaDestino, renglonDestino, formatear_pesos(precio_unitario));	//Se coloca el precio unitario en la celda (attr)
	valorXY(tabla, columnaDestino, renglonDestino, precio_unitario);	//Se coloca el precio unitario en la celda (html)
}

function obtenIVA(tabla, renglonOrigen, columnaOrigen, renglonDestino, columnaDestino){
	// **********************************************************************************************************
	// CREACION: 20/10/2015
	// DESCRIPCION: Obtiene el IVA a partir del id del Producto
	// **********************************************************************************************************
	// PARAMETROS:
	// tabla: Nombre identificador de la celda
	// renglonOrigen: Renglón DE donde se OBTENDRÁ el ID del producto para sacar su Precio Unitario (Inciando desde 0)
	// columnaOrigen: Columna DE donde se OBTENDRÁ el valor (Inciando desde 0)
	// renglonDestino: Renglón EN donde se COLOCARÁ en valor obtenido (Inciando desde 0)
	// columnaDestino: Columna EN donde se COLOCARÁ en valor obtenido (Inciando desde 0)
	// **********************************************************************************************************
	var idProducto = celdaValorXY(tabla, columnaOrigen, renglonOrigen);	//Se obtiene el ID del Producto
	var envia_datos = "id=" + idProducto;
	var url = "obtenIvaProducto.php";
	var respuesta = ajaxN(url, envia_datos);
	var porcentaje_iva = respuesta.split("|");
	
	valorCeldaXY(tabla, columnaDestino, renglonDestino, (porcentaje_iva));	//Se coloca el IVA en la celda (attr)
	valorXY(tabla, columnaDestino, renglonDestino, (porcentaje_iva));	//Se coloca el IVA en la celda (html)
}

function obtenImporte(tabla, renglonCantidad, columnaCantidad, renglonPrecioUnitario, columnaPrecioUnitario, renglonDestino, columnaDestino){
	// **********************************************************************************************************
	// CREACION: 16/10/2015
	// DESCRIPCION: Obtiene el Importe a partir de la Cantidad y el Costo Unitario
	// **********************************************************************************************************
	// PARAMETROS:
	// tabla: Nombre identificador de la celda
	// renglonCantidad: Renglón DE donde se OBTENDRÁ el valor de la Cantidad (Inciando desde 0)
	// columnaCantidad: Columna DE donde se OBTENDRÁ el valor de la Cantidad (Inciando desde 0)
	// renglonPrecioUnitario: Renglón DE donde se OBTENDRÁ el valor del Precio Unitario (Inciando desde 0)
	// columnaPrecioUnitario: Columna DE donde se OBTENDRÁ el valor del Precio Unitario (Inciando desde 0)
	// renglonDestino: Renglón EN donde se COLOCARÁ en valor obtenido del Importe (Inciando desde 0)
	// columnaDestino: Columna EN donde se COLOCARÁ en valor obtenido del Importe (Inciando desde 0)
	// **********************************************************************************************************
//	alert(tabla+' :: '+columnaCantidad+' :: '+renglonCantidad);
	var cantidad = celdaValorXY(tabla, columnaCantidad, renglonCantidad);
	//Se obtienen obtiene la cantidad
	var precioUnitario = celdaValorXY(tabla, columnaPrecioUnitario, renglonPrecioUnitario);	//Se obtienen el precio unitario
	var importe = precioUnitario * cantidad;
	valorCeldaXY(tabla, columnaDestino, renglonDestino, (importe));	//Se coloca el importe en la celda (attr)
	valorXY(tabla, columnaDestino, renglonDestino, (importe));	//Se coloca el importe en la celda (html)
}

function obtenMontoIva(tabla, renglonImporte, columnaImporte, renglonIva, columnaIva, renglonDestino, columnaDestino){
	// **********************************************************************************************************
	// CREACION: 20/10/2015
	// DESCRIPCION: Obtiene el Importe a partir de la Cantidad y el Costo Unitario
	// **********************************************************************************************************
	// PARAMETROS:
	// tabla: Nombre identificador de la celda
	// renglonImporte: Renglón DE donde se OBTENDRÁ el valor del Importe (Inciando desde 0)
	// columnaImporte: Columna DE donde se OBTENDRÁ el valor del Importe (Inciando desde 0)
	// renglonIva: Renglón DE donde se OBTENDRÁ el valor del % del IVA (Inciando desde 0)
	// columnaIva: Columna DE donde se OBTENDRÁ el valor del % del IVA (Inciando desde 0)
	// renglonDestino: Renglón EN donde se COLOCARÁ en valor obtenido del Monto del IVA (Inciando desde 0)
	// columnaDestino: Columna EN donde se COLOCARÁ en valor obtenido del Monto del IVA (Inciando desde 0)
	// **********************************************************************************************************
	var importe = celdaValorXY(tabla, columnaImporte, renglonImporte);	//Se obtienen obtiene el importe
	var porcentaje_iva = celdaValorXY(tabla, columnaIva, renglonIva);	//Se obtienen el porcentaje del IVA
	var cien = 100.00;
	var monto_iva = parseFloat(importe) * ( parseFloat(porcentaje_iva) / parseFloat(cien) );
	var a = 0;
	valorCeldaXY(tabla, columnaDestino, renglonDestino, (monto_iva));	//Se coloca el Monto del IVA en la celda (attr)
	valorXY(tabla, columnaDestino, renglonDestino, (monto_iva));	//Se coloca el Monto del IVA en la celda (html)
}

function obtenSubtotal(tabla, columna, idSubTotal){
	// **********************************************************************************************************
	// CREACION: 19/10/2015
	// DESCRIPCION: Obtiene el SubTotal en el Encabezado a partir de la Cantidad que se teclee en el detalle
	// **********************************************************************************************************
	// PARAMETROS:
	// tabla: Nombre identificador de la celda
	// columna: Indica que columna se va a sumar
	// idSubTotal: Indica el Id de la caja de texto en donde se va a colorcar la sumatoria
	// **********************************************************************************************************
	var total_parcial = 0;
	var sub_total = 0;
	for(var i=0; i<NumFilas(tabla); i++){
         	total_parcial = celdaValorXY(tabla, columna, i);
         	if(total_parcial!='')
          		sub_total = parseFloat(sub_total) + parseFloat(total_parcial);
	}
	sub_total = formatear_pesos(sub_total);
	$("#" + idSubTotal).val(sub_total);
}

function obtenIva(tabla, columna, idIva){
	// **********************************************************************************************************
	// CREACION: 20/10/2015
	// DESCRIPCION: Obtiene el IVA en el Encabezado a partir de la Cantidad que se teclee en el detalle
	// **********************************************************************************************************
	// PARAMETROS:
	// tabla: Nombre identificador de la celda
	// columna: Indica que columna se va a sumar
	// idIva: Indica el Id de la caja de texto en donde se va a colorcar la sumatoria
	// **********************************************************************************************************
	var iva_parcial = 0;
	var iva_total = 0;
	for(var i=0; i<NumFilas(tabla); i++){
       	iva_parcial = celdaValorXY(tabla, columna, i);
       	if(iva_parcial!='')
       		iva_total = parseFloat(iva_total) + parseFloat(iva_parcial);
	}
	iva_total = formatear_pesos(iva_total);
	$("#" + idIva).val(iva_total);
}

function obtenTotal(idSubTotal,idIva,idTotal){
	// **********************************************************************************************************
	// CREACION: 20/10/2015
	// DESCRIPCION: Obtiene el IVA en el Encabezado a partir de la Cantidad que se teclee en el detalle
	// **********************************************************************************************************
	// PARAMETROS:
	// tabla: Nombre identificador de la celda
	// columna: Indica que columna se va a sumar
	// idIva: Indica el Id de la caja de texto en donde se va a colorcar la sumatoria
	// **********************************************************************************************************
	var sub_total = $("#" + idSubTotal).val();
	var iva = $("#" + idIva).val();
	var re = /,/g;
	var sub_total_aux = sub_total.replace(re, "");
	var iva_aux = iva.replace(re, "");
	total = parseFloat(sub_total_aux) + parseFloat(iva_aux);
	total = formatear_pesos(total);
	$("#" + idTotal).val(total);
}

/*
	var ivaFactura = calculaPorcentajesMonto(sub_total, 16, 1);
	var total = calculaPorcentajesMonto(sub_total, 16, 3);
	$("#" + idIVA).val(formatear_pesos(ivaFactura));
	$("#" + idTotal).val(formatear_pesos(total));
*/


/*
function obtenFactura(caso){
				var sucursal = $("#id_sucursal").find("option:selected").val();
				var envia_datos = "sucursal=" + sucursal + "&caso=" + caso;
				var url = "obtenFactura.php";
				var factura = ajaxN(url, envia_datos);
				var datos = JSON.parse(factura);
				if(caso == 1)
						var campo = "id_factura";
				else if(caso == 2)
						var campo = "id_nota_credito";
				$("#" + campo).val(datos.prefijo + datos.consecutivo);
				$("#prefijo").val(datos.prefijo);
				$("#consecutivo").val(datos.consecutivo);
		}

function obtenPedidoFac(){
		id_cliente = $("#hcampo_8").val()
		var selectHijo = 'id_pedido';
		var urlAjax = "obtenPedidoCliente.php";
		var envio_datos = 'cliente=' + id_cliente + "&caso=1"; 
		ajaxCombos(urlAjax, envio_datos, selectHijo);
		var envia_datos = 'cliente=' + id_cliente + "&caso=2"; 
		var respuesta = ajaxN(urlAjax, envia_datos);
		var datos = JSON.parse(respuesta);
		$("#email_envio_fa").val(datos.email);
		$("#id_fiscales_cliente").val(datos.fiscal);
		}	
		
function obtenFacturaNotaCredito(){
		id_cliente = $("#hcampo_8").val()
		var selectHijo = 'id_factura';
		var urlAjax = "obtenFacturaCliente.php";
		var envio_datos = 'cliente=' + id_cliente + "&caso=1"; 
		ajaxCombos(urlAjax, envio_datos, selectHijo);
		var envia_datos = 'cliente=' + id_cliente + "&caso=2"; 
		var respuesta = ajaxN(urlAjax, envia_datos);
		var datos = JSON.parse(respuesta);
		$("#email_envio_fa").val(datos.email);
		$("#id_fiscales_cliente").val(datos.fiscal);
		}
*/	
/*		
function obtenPrecioUnitarioOld(pos, tabla){
		var producto = $("#" + tabla + "_2_" + pos).attr('valor');
		var precio_unitario=0;
		
		var envia_datos = "id=" + producto;
		var url = "obtenPrecioProducto.php";
		var respuesta = ajaxN(url, envia_datos);
		var precio = respuesta.split("|");
		
		
		
		if(tabla=='detalleFacturas' || tabla=='detalleNotasCredito')
		{
			precio_unitario = parseFloat(precio)/1.16;
			
			//a este precio lo cerramos a 4 decimales
			
		}
		else
		{
			precio_unitario = precio;
		}
		
		//dependiendo de la tabla en la que estemos colocamos el valor
		if(tabla=='detalleFacturas' || tabla=='detalleNotasCredito')
		{
				
				valorCeldaXY(tabla, 6, pos, precio_unitario); 
				valorXY(tabla,6, pos, precio_unitario);
	
			
		}
		else
		{
			$("#" + tabla + "_6_" + pos).attr('valor', precio_unitario);
			$("#" + tabla + "_6_" + pos).html("$" + formatear_pesos(precio_unitario));
			
		}
		
		//$("#" + tabla + "_6_" + pos).html(precio_unitario);
		
		
		
		if(tabla=='detalleFacturas' || tabla=='detalleNotasCredito')
		{
			valorCeldaXY(tabla, 13, pos, precio); 
			valorXY(tabla,13, pos, precio);
			
		}
	 
	}

*/


/*
		
function totalesFactura(tabla){
		var subtotalFactura = 0;
		$('table#Body_' + tabla + ' tr').each(function(index) {
				var subtotal  = $(this).children().filter("[id^=" + tabla + "_7_]").attr("valor");
				subtotal = subtotal == "" ? subtotal = 0 : subtotal ;
				subtotalFactura += parseFloat(subtotal);
				});
		
		var ivaFactura = calculaPorcentajesMonto(subtotalFactura, 16, 1);
		var total = calculaPorcentajesMonto(subtotalFactura, 16, 3);
		
		$("#subtotal").val(formatear_pesos(subtotalFactura));
		$("#iva").val(formatear_pesos(ivaFactura));
		$("#total").val(formatear_pesos(total));
		}
		
function selFactura(){
		$.fancybox({
				type: 'iframe',
				href: '../especiales/seleccionaFacturaNC.php',
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
						colocaDetalleNC();
				}
		});
		}
		
function colocaDetalleNC(){
		var productos = $("#campo_productos").val();
		if(productos != ""){
				var url = "insertaDetalleNC.php";
				var envia_datos = "detalle_productos=" + productos;
				var datos = ajaxN(url, envia_datos);
				var detalles = JSON.parse(datos);
				var contador = detalles.length;
				
				$('table#Body_detalleNotasCredito tr').remove();
				for(var i = 0; i<contador; i++){
						nuevoGridFila('detalleNotasCredito');
						//Prodcutos
						$("#detalleNotasCredito_2_" + i).attr("valor", detalles[i].id_producto); 
						$("#detalleNotasCredito_3_" + i).attr("valor", detalles[i].muestraProducto); 
						$("#detalleNotasCredito_3_" + i).html(detalles[i].producto); 
						
						//Descripcion
						$("#detalleNotasCredito_4_" + i).attr("valor", detalles[i].descripcion); 
						$("#detalleNotasCredito_4_" + i).html(detalles[i].descripcion);
						
						//Cantidad
						$("#detalleNotasCredito_5_" + i).attr("valor", detalles[i].cantidad); 
						$("#detalleNotasCredito_5_" + i).html(detalles[i].cantidad);
						
						//Precio Unitario
						$("#detalleNotasCredito_6_" + i).attr("valor", detalles[i].precio_unitario); 
						$("#detalleNotasCredito_6_" + i).html(detalles[i].precio_unitario_muestra);
						
						//Importe
						$("#detalleNotasCredito_7_" + i).attr("valor", detalles[i].importe); 
						$("#detalleNotasCredito_7_" + i).html(detalles[i].importe_muestra);
						
						//Observaciones
						$("#detalleNotasCredito_8_" + i).attr("valor", detalles[i].observaciones); 
						$("#detalleNotasCredito_8_" + i).html(detalles[i].observaciones);
						
						//IVA
						$("#detalleNotasCredito_9_" + i).attr("valor", detalles[i].iva); 
						
						//Monto IVA
						$("#detalleNotasCredito_10_" + i).attr("valor", detalles[i].monto_iva); 
						
						//ID control Factura
						$("#detalleNotasCredito_11_" + i).attr("valor", detalles[i].factura); 
						var factura = detalles[i].factura;
						}
						
				totalesFactura('detalleNotasCredito');
				var envio = "factura=" + factura;
				var ruta = "llenaEncabezadoFacturaNC.php";
				var respuesta = ajaxN(ruta, envio);		
				var encabezados = JSON.parse(respuesta);
				
				$("#hcampo_8").val(encabezados.id_cliente);
				$("#id_cliente").val(encabezados.cliente);
						
				$("#campo_productos").val("");
				}
		}
		
*/		
		
		
	
// ><<<< 9,11,12
		/*
function realizaCalculoPorIVAMontoIVA(pos, tabla,colImporte,colPorcIVA,colMontoIVA ){
		
		var importe = $("#" + tabla + "_"+colImporte+"_" + pos).attr('valor');
		
		
		
		$("#" + tabla + "_"+colPorcIVA+"_" + pos).attr('valor', '16');
		$("#" + tabla + "_"+colPorcIVA+"_" + pos).html(16);
		
		var montoIVA = parseFloat(importe*0.16);
		
		$("#" + tabla + "_"+ colMontoIVA+"_" + pos).attr('valor', montoIVA);
		$("#" + tabla + "_"+ colMontoIVA+"_" + pos).html(formatear_pesos(montoIVA));
		
}*/

/*

function realizaCalculoPorIVAMontoIVA(pos, tabla,colImporte,colPorcIVA,colMontoIVA ){
		
		var importe = $("#" + tabla + "_"+colImporte+"_" + pos).attr('valor');
		var montoIVA = parseFloat(importe*0.16);
		
		//alert(montoIVA);
		if(tabla=='detalleFacturas' || tabla=='detalleNotasCredito')
		{
				
				valorCeldaXY(tabla, colPorcIVA, pos, 16); 
				valorXY(tabla,colPorcIVA, pos, 16);

				valorCeldaXY(tabla, colMontoIVA, pos, montoIVA); 
				valorXY(tabla,colMontoIVA, pos, montoIVA);
	
			
		}
		else
		{
			$("#" + tabla + "_"+colPorcIVA+"_" + pos).attr('valor', '16');
			$("#" + tabla + "_"+colPorcIVA+"_" + pos).html(16);
			
			
			$("#" + tabla + "_"+ colMontoIVA+"_" + pos).attr('valor', montoIVA);
			$("#" + tabla + "_"+ colMontoIVA+"_" + pos).html(formatear_pesos(montoIVA));
				
		}
		
		
		
		
}
*/
function CCMostrar(tabla, renglonCuenta, columnaCuenta,renglonDestino, columnaDestino,renglonDestinoID,columnaDestinoID){
	var cuenta = celdaValorXY(tabla, columnaCuenta, renglonCuenta);
	url="obtenCuentaContable.php";
	datos="tabla="+tabla+"&cuenta_contable="+cuenta;
	var resultado=ajaxN(url,datos);
	if(resultado!='error'){
		datos=resultado.split('|');
		var NombreCuenta="";
		
		var idCuenta=datos[0];
		NombreCuenta=datos[2];
		
		valorCeldaXY(tabla,columnaDestino,renglonDestino,NombreCuenta);
		valorCeldaXY(tabla,columnaDestinoID,renglonDestinoID,(idCuenta));
		
		htmlXY(tabla,columnaDestino,renglonDestino,NombreCuenta);
	}else{
		var vacio="";
		
		valorCeldaXY(tabla,columnaDestino,renglonDestino,vacio);
		valorCeldaXY(tabla,columnaDestinoID,renglonDestinoID,vacio);
		
		htmlXY(tabla,columnaDestino,renglonDestino,vacio);
		alert('La cuenta contable ingresada no esta registrada!!');
		
	}
}
function seleccionaNoIDentificado(id_tipoPago,cuenta){
	//si seleccionan la forma de pago SAT para poder cambiar a no identificado
	var tipoPago = $("#"+id_tipoPago).find("option:selected").val();
	
	if(tipoPago=='1' || tipoPago=='2'){
		$("#"+cuenta).val('NO IDENTIFICADO');
	}
	else{
		if($("#"+cuenta).val() == '' || $("#"+cuenta).val() == 'NO IDENTIFICADO')
			$("#"+cuenta).val('');
	}	
}




             
          

function validaXML(oInput) {
        var file_xml = document.getElementById('referencia_xml').files[0];
        
        
//        alert("este es el archivo : " + file_xml);
        
        var reader = new FileReader();
         reader.onload = function (e) {
             readFileXML(e.target.result);
         }
         
         if(typeof file_xml !== 'undefined')
             reader.readAsDataURL(file_xml);
 
}

function readFileXML(url){    
    var request = new XMLHttpRequest();
    request.open('GET', url, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
            
            
//            alert("Entre a la extraccion de la informacion");
            
            var id_control_factura = $("#id_control_factura").val();
            var xmlDoc = request.responseXML;
            
//            alert("este es el XML");
            
//            console.log(xmlDoc);
            
            
            var comprobante = $(xmlDoc).find("cfdi\\:Comprobante");                       
            console.log(comprobante);
            
            
            var subtotal = getSubTotal(comprobante);
            console.log("Subtotal: "+subtotal);

            var total = getTotal(comprobante);
            console.log("Total: "+total);

            var folio = getFolio(comprobante);            
            console.log("Folio: "+folio);


            var fecha = getFecha(comprobante);
            console.log("Fecha: "+fecha);


            var emisor = comprobante.find("cfdi\\:Emisor");                                                
            var rfc_emisor = getRfc(emisor);
            console.log("rfc_emisor: "+rfc_emisor);

            var complemento = comprobante.find("cfdi\\:Complemento");   
            var uuid = getUuid(complemento);
            console.log("uuid: "+uuid);
                        
                        
            
            var url = "validaCuentaPorPagarAuxiliar.php";
            var data = "id_control_factura="+id_control_factura+
                       "&rfc_emisor="+rfc_emisor+
                       "&subtotal="+subtotal+
                       "&total="+total+
                       "&uuid="+uuid;
             
//            alert("Estos son los datos enviados:" + data);
             
//            var response = ajaxN(url,data);
            
//            console.log("Esta es la respuesta como JSON: " + JSON.parse(response));
            
            var response = JSON.parse(ajaxN(url,data));
            
//            alert("Este es el estatus de la respuesta : " + response.status);
            
//            alert("Esta es la respuesta de la validacion: " + response  );
            

            if(response.status){
                $("#file_xml").val(1);
                
                $("#uuid_xml").val(uuid);
                if(folio == null){
                    $("#folio_cfd").val(""); 
                }
                else{
                    $("#folio_cfd").val(folio); 
                }
                $("#folio_xml").val(folio);                
                $("#fecha_xml").val(fecha_xml[0]);
            }
            else{
                $("#file_xml").val("");
                $("#referencia_xml").val("");
                var message = "";
                if(response.uuid){
                    if(response.rfc){
                        if(!response.subtotal){
                           message += " * El subtotal es mayor o menor a un $5.00 ";  
                        }
                        if(!response.total){
                           message += " * El total es mayor o menor a un $5.00 ";  
                        }
                    }
                    else{
                       message = "* El RFC del emisor no es igual al de esta sesion"; 
                    }
                }
                else{
                    message = "* El UUID ya existe con un estatus PROVISION o PAGADA";
                }
                alert("Algunos de los criterios de validacion no son validos:"+message);
            }
            console.log(response);
           
//           var a = $("#file_xml").val();
//            
//            alert("este es el valor que falta" + a);
            
            
        }
    };
}



function getSubTotal(comprobante){
    console.log(comprobante);
    var subtotal = comprobante.attr("SubTotal");
    console.log("subtotal: "+subtotal);
    if(subtotal == null){
        subtotal = comprobante.attr("subtotal");
        if(subtotal == null){
            subtotal = comprobante.attr("subTotal");
            if(subtotal == null){
                subtotal = comprobante.attr("Subtotal");

            }
        }
    }
    
    return subtotal;
}

function getTotal(comprobante){
    var total = comprobante.attr("Total");
    if(total == null){
        total = comprobante.attr("total");        
    }    
    return total;
}


function getFolio(comprobante){
    var folio = comprobante.attr("Folio");
    if(folio == null){
        folio = comprobante.attr("folio");        
    }    
    return folio;
}



function getFecha(comprobante){
    var fecha = comprobante.attr("Fecha");
    if(fecha == null){
        fecha = comprobante.attr("fecha");        
    }    
    
    var fecha_xml = fecha.split("T");                                            
     
    return fecha_xml[0];
}

function getEmisor(comprobante){
    
}

function getRfc(emisor){
    var rfc = emisor.attr("Rfc");
    if(rfc == null){
        rfc = emisor.attr("rfc");        
    }    
    return rfc;
}


function getUuid(complemento){
    console.log(complemento);
    var timbre_fiscal = complemento.find("tfd\\:TimbreFiscalDigital");
    console.log(timbre_fiscal);
    var uuid = timbre_fiscal.attr("UUID");
    if(uuid == null){
        uuid = timbre_fiscal.attr("uuid");        
    }    
    return uuid;
}

