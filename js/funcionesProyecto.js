/*COLOCAR VALOR EN UNA CELDA DEL GRID*/
function gridAsignarValorACelda(grid, posY, posXOrigen, posXDest){
	var id_evalua = celdaValorXY(grid, posXOrigen, posY);
	valorCeldaXY(grid,posXDest,posY,id_evalua); 
}

//PERMITE AGRUPAR EN UN SOLO CAMPO TODOS LOS VALORES NECESARIOS PARA LLENAR UN GRID
//EN BD REQUIERE strSplit("1|1|1000", '|', 3) PARA POSICION 3
function gridAsignaValorAuxCombo(grid, posY, posXs, posXdest){
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

/*OBTENER EL MONTO PENDIENTE DE UNA NOTA DE CAMBIO DESDE CATALOGO NOTA DE SERVICIO*/
function montoPendiente(posY){
	var idNota = celdaValorXY("detalleNotasServiciosPagos", 10, posY);
	
	var resp = ajaxR("../ajax/getDatosAjax.php?opc=5&idNota=" + idNota);
	datosNota = jQuery.parseJSON(resp);
	
	valorCeldaXY("detalleNotasServiciosPagos", 9, posY, datosNota[0].disponible); 
	$("#detalleNotasServiciosPagos_9_" + posY).html(datosNota[0].disponible);
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


//--------------------------
//para rent
function colocaCategoriasArticulos()
{
	var id_linea=document.getElementById('id_linea_articulo').value;
	
	var objCat=document.getElementById('id_categoria_articulo');
	//obtenemos las categorias de un producto dado la linea seleccionada
	//aux=ajaxR('../ajax/obtenValoresCombosFranUs.php?id_suc='+id_sucursal_franquicia+'&opcion='+document.getElementById('id_grupo').value);
	aux=ajaxR('../ajax/obtenDatosCombos.php?opcion=1&id='+id_linea+'');
	
	//ahora mostramos los proyectos del contrato activos para
	limpiaCombo(objCat);
	var arrResp=aux.split("|");
	
	var numDatos=parseInt(arrResp.length);		
	objCat.options[0]=new Option("-Seleccione un opción-",0);
	for(var i=2;i<numDatos;i++)
	{
		var arrDatos=arrResp[i].split("~");			
		objCat.options[i-1]=new Option(arrDatos[1], arrDatos[0]);
	}
	
}

function colocaCaracteristica()
{
	var id_linea=document.getElementById('id_linea_articulo').value;
	var objCar=document.getElementById('id_caracteristica');
	//obtenemos las categorias de un producto dado la linea seleccionada
	//aux=ajaxR('../ajax/obtenValoresCombosFranUs.php?id_suc='+id_sucursal_franquicia+'&opcion='+document.getElementById('id_grupo').value);
	aux=ajaxR('../ajax/obtenDatosCombos.php?opcion=2&id='+id_linea+'');
	
	//ahora mostramos los proyectos del contrato activos para
	limpiaCombo(objCar);
	var arrResp=aux.split("|");
	
	var numDatos=parseInt(arrResp.length);		
	objCar.options[0]=new Option("-Seleccione un opción-", 0);
	for(var i=2;i<numDatos;i++)
	{
		var arrDatos=arrResp[i].split("~");			
		objCar.options[i-1]=new Option(arrDatos[1], arrDatos[0]);
	}
	
}

function colocaSubcategoria()
{
	var id_categoria=document.getElementById('id_categoria_articulo').value;
	var objSub=document.getElementById('id_subcategoria_articulo');
	//obtenemos las categorias de un producto dado la linea seleccionada
	//aux=ajaxR('../ajax/obtenValoresCombosFranUs.php?id_suc='+id_sucursal_franquicia+'&opcion='+document.getElementById('id_grupo').value);
	aux=ajaxR('../ajax/obtenDatosCombos.php?opcion=3&id='+id_categoria+'');
	
	//ahora mostramos los proyectos del contrato activos para
	limpiaCombo(objSub);
	var arrResp=aux.split("|");
	
	var numDatos=parseInt(arrResp.length);		
	objSub.options[0]=new Option("-Seleccione un opción-", 0);
	for(var i=2;i<numDatos;i++)
	{
		var arrDatos=arrResp[i].split("~");			
		objSub.options[i-1]=new Option(arrDatos[1], arrDatos[0]);
	}
	
}

function armaCodigo()
{
	//buscamos la categoria
	var id_cat=document.getElementById('id_categoria_articulo').value;
	//buscamos la subcategoria
	var id_subcat=document.getElementById('id_subcategoria_articulo').value;
	
	//buscamos la caracteristica
	var id_carac=document.getElementById('id_caracteristica').value;
	
	
	if(id_cat!='0' && id_subcat!='0' && id_carac!='0')
	{
		//obten siglas categoria
			
		
		var aux=ajaxR('../ajax/obtenDatosSQL.php?opcion=1&id='+id_cat+'');
		
		//alert(aux);
		
		var arrResp=aux.split("|");
		//nombre
		var nombre1=arrResp[1];
		//sigla
		var sigals1=arrResp[2];
		
		//obten siglas  id_carac
		var aux1=ajaxR('../ajax/obtenDatosSQL.php?opcion=2&id='+id_subcat+'');
		//alert(aux1);
		var arrResp2=aux1.split("|");
		//nombre
		var nombre2=arrResp2[1];
		//sigla
		var sigals2=arrResp2[2];
			
			
		//obten siglas subcategori
		var aux2=ajaxR('../ajax/obtenDatosSQL.php?opcion=3&id='+id_carac+'');
		
		//alert(aux2);
		var arrResp3=aux2.split("|");
		//nombre
		var nombre3=arrResp3[1];
		//sigla
		var sigals3=arrResp3[2];

		
		document.getElementById('codigo_articulo').value=sigals1+'-'+sigals2+'-'+sigals3;
		document.getElementById('nombre').value=nombre1+' '+nombre2+' '+nombre3;
			
	}
	else
	{
		document.getElementById('codigo_articulo').value='';
		document.getElementById('nombre').value='';
	}	
		
}

function muestraGridBasicos()
{
	//obtenemos el valor de es articulo compuesto
	//------------------------------------------
	if(document.getElementById('es_compuesto').checked || (document.getElementById('v').value ==1 && document.getElementById('es_compuesto').value ==1) )
	{
		document.getElementById('divgrid_articulosBasicos').style.display= "block";
		
	}
	else
	{
		document.getElementById('divgrid_articulosBasicos').style.display= "none";
	}
}

function cambiaCiudades()
{
	//obtenemos los id de la ciudad seleccionada
	var idestado=document.getElementById('id_estado').value;
	var objSub=document.getElementById('id_ciudad');
	
	//obtenemos las categorias de un producto dado la linea seleccionada
	//aux=ajaxR('../ajax/obtenValoresCombosFranUs.php?id_suc='+id_sucursal_franquicia+'&opcion='+document.getElementById('id_grupo').value);
	aux=ajaxR('../ajax/obtenDatosCombos.php?opcion=10&id='+idestado+'');
	
	//ahora mostramos los proyectos del contrato activos para
	limpiaCombo(objSub);
	var arrResp=aux.split("|");
	
	var numDatos=parseInt(arrResp.length);		
	objSub.options[0]=new Option("-Seleccione un opción-", 0);
	for(var i=2;i<numDatos;i++)
	{
		var arrDatos=arrResp[i].split("~");			
		objSub.options[i-1]=new Option(arrDatos[1], arrDatos[0]);
	}
}


function muestraDatosClientesPedidos()
{
	//obtenemos el id del cliente seleccionado
	var idcliente=document.getElementById('id_cliente').value;
	
	//cambiamos los clientes directos del cliente si es que es mayorista
	cambiaCombo(idcliente,"id_cliente_directo",25,"");
	
	
	//llenamos los combos de persona que recibe
	cambiaCombo(idcliente,"persona_recibe",20,"");
	//llenamos los combos de persona  direccion de entrega
	cambiaCombo(idcliente,"direccion_entrega",21,"");
	//direcciones del evento
	cambiaCombo(idcliente,"direccion_evento",22,"");
	//persona que entrega
    cambiaCombo(idcliente,"persona_entrega",23,"");
	//direcciones de recoleccion
    cambiaCombo(idcliente,"direccion_recoleccion",24,"");		
	
	//datos de los clientes y seleccionamos los ids
	//obtenemos un array de datos para mostrar
	
	//-->
	aux1=ajaxR('../ajax/obtenInformacion.php?opcion=1&id='+idcliente);
	
	ingresaInformacionForma(aux1);
	//var arrResp1=aux1.split("|");
	
	$.getJSON( "../ajax/getDatosAjax.php?opc=14&idCliente=" + idcliente, function( json ) {
		//console.log( "JSON Data: " + json.users[ 3 ].name );
		$("#id_vendedor").val(json.id_vendedor_asignado);
		$("#campo1_id_vendedor").val(json.vendedor);
	});
	
	
	//empezamoa a llenar con fin
	
	
}

function cambiaCombo(idPadre,idCombo,opcion,strWhere)
{
	//obtenemos los id de la ciudad seleccionada
	var objSub=document.getElementById(idCombo);
	
	//obtenemos las categorias de un producto dado la linea seleccionada
	//aux=ajaxR('../ajax/obtenValoresCombosFranUs.php?id_suc='+id_sucursal_franquicia+'&opcion='+document.getElementById('id_grupo').value);
	aux=ajaxR('../ajax/obtenDatosCombos.php?opcion='+opcion+'&id='+idPadre+'&strWhere='+strWhere);
		
	//ahora mostramos los datos
	limpiaCombo(objSub);
	var arrResp=aux.split("|");
	
	var numDatos=parseInt(arrResp.length);		
	objSub.options[0]=new Option("-Seleccione un opción-", 0);
	for(var i=2;i<numDatos;i++)
	{
		var arrDatos=arrResp[i].split("~");			
		objSub.options[i-1]=new Option(arrDatos[1], arrDatos[0]);
	}
	
}


function recargaCombosClientesDireccion()
{
	
    //obtenemos el id del cliente seleccionado
    var idcliente=document.getElementById('id_cliente').value;
    //llenamos los combos de persona  direccion de entrega
    cambiaCombo(idcliente,"direccion_entrega",21,"");
    //direcciones del evento
    cambiaCombo(idcliente,"direccion_evento",22,"");
    //direcciones de recoleccion
    cambiaCombo(idcliente,"direccion_recoleccion",24,"");        
}
function ingresaInformacionForma(strCadena)
{

	var arrResp1=strCadena.split("|");
	
	//seleccinamos le vendedor asignado
	
	$("#id_vendedor option[value="+arrResp1[12]+"]").attr("selected",true);
	
	if(arrResp1[15]==1)
		$("#tipo_entrega_cliente option[value=2]").attr("selected",true);
	else
		$("#tipo_entrega_cliente option[value=1]").attr("selected",true);
	
	
	//alert('>>'+arrResp1[15]);
	
	if(arrResp1[16]==1)
		$("#tipo_regreso_almacen option[value=2]").attr("selected",true);
	else
		$("#tipo_regreso_almacen option[value=1]").attr("selected",true);
	
	
	if(arrResp1[3]==1)
	{
		
		$("#requiere_presupuesto_flete").attr("checked","checked");
	}
	else
		$("#requiere_presupuesto_flete").removeAttr("checked");
		
	
	if(arrResp1[4]==1)
	{
		$("#requiere_presupuesto_motaje").attr("checked","checked");
	}
	else
		$("#requiere_presupuesto_motaje").removeAttr("checked");
	
	
	if(arrResp1[5]==1)
	{
		$("#requiere_presupuesto_viaticos").attr("checked","checked");
	}
	else
		$("#requiere_presupuesto_viaticos").removeAttr("checked");
		
	
	$("#id_vendedor option[value="+arrResp1[28]+"]").attr("selected",true);
	
	$("#id_almacen_recoge option[value="+arrResp1[18]+"]").attr("selected",true);

	document.getElementById('nombre_razon_social').value=arrResp1[19];	
	document.getElementById('rfc').value=arrResp1[20];	
	document.getElementById('calle').value=arrResp1[21];
	document.getElementById('numero_exterior').value=arrResp1[22];	
	document.getElementById('numero_interior').value=arrResp1[23];	
	document.getElementById('colonia').value=arrResp1[24];	
	
	//id_ciudad
	$("#id_estado option[value="+arrResp1[29]+"]").attr("selected",true);
	cambiaCiudades();
	$("#id_ciudad option[value="+arrResp1[25]+"]").attr("selected",true);
	
	document.getElementById('delegacion_municipio').value=arrResp1[26];	
	document.getElementById('codigo_postal').value=arrResp1[27];	
		
}

function cambiaClienteMayorista()
{
	var idtipoCliente=document.getElementById('id_tipo_cliente').value;
	//valor fijo si el tipo de cliente es mayorista
	if(idtipoCliente==1)
	{
		document.getElementById('id_cliente_mayorista').disabled= true;
		document.getElementById('id_cliente_mayorista').selectedIndex =0;
	}
	else
	{
		document.getElementById('id_cliente_mayorista').disabled= false;
	}
	
}
	

function comparaDosValoresID(idMenor, idMayor)
{
	valMenor = $("#" + idMenor).val();
	valMayor = $("#" + idMayor).val();
	
	if(valMenor > valMayor)
		$("#" + idMayor).val(0);
}
	
function comparaDosFechasHoraID(idMenorFecha, idMenorHora, idMayorFecha, idMayorHora, borrarMayor)
{
	valMenorFecha = $("#" + idMenorFecha).val().split("/");
	valMenorHora = $("#" + idMenorHora).val();
	valMayorFecha = $("#" + idMayorFecha).val().split("/");
	valMayorHora = $("#" + idMayorHora).val();
	
	if($("#" + idMenorFecha).val() != '' && $("#" + idMayorFecha).val() != '') 
	{
		fecha1 = new Date(parseInt(valMenorFecha[2]), parseInt(valMenorFecha[1]) - 1, parseInt(valMenorFecha[0]));
		fecha2 = new Date(parseInt(valMayorFecha[2]), parseInt(valMayorFecha[1]) - 1, parseInt(valMayorFecha[0]));
		
		if((Date.parse(fecha1) > Date.parse(fecha2)) || (Date.parse(fecha1) == Date.parse(fecha2) && parseInt(valMenorHora) > parseInt(valMayorHora)))
		{
			if(borrarMayor == "1")
				$("#" + idMayorFecha).val('');
			else
				$("#" + idMenorFecha).val('');
		}
	}
}

function limpiarTablaSolCotizacion()
{
	//$("#Body_detalleArticulos").html("");
	limpiarColsTabla("detalleArticulos", 5, 16, 9, 10);
	$("#Body_detalleArticulosBasicos").html("");
	//$("#Body_detalleArticulosEspeciales").html("");
	limpiarColsTabla("detalleArticulosEspeciales", 7, 19, 12, 13);
	//$("#Body_detalleArticulosProduccion").html("");
	limpiarColsTabla("detalleArticulosProduccion", 5, 13, 6, 7);
	//$("#Body_detalleArticulosCompra").html("");
	limpiarColsTabla("detalleArticulosCompra", 5, 13, 6, 7);
	
	/*
	$("#subtotal_articulos").val("0.00");
	$("#subtotal").val("0.00");
	$("#iva").val("0.00");
	$("#total").val("0.00");
	$("#descuento_tipo_cliente").val("0.00");
	$("#monto_descuento_adicional").val("0.00");
	*/
}


function limpiarColsTabla(grid, inicio, fin, desc, factor)
{
	$("#Body_" + grid + " tr").each(function( index ) {
		id = $(this).attr("id");
		idLimpio = id.replace(grid + "_Fila", "");
		
		//console.log(grid + ":" + idLimpio);
		for(i=inicio; i<=fin; i++)
		{
			if(i != desc && i != factor)
			{
				//console.log(i + ":" + idLimpio);
				valorCeldaXY(grid, i, idLimpio, ""); 
				aplicaValorXY(grid, i, idLimpio, "");
			}
		}
		
		if(grid == 'detalleArticulos')
			cargaIdPosicionBuscador('detalleArticulos', 3, idLimpio, 2);
		if(grid == 'detalleArticulosEspeciales')
			cargaIdPosicionBuscador('detalleArticulosEspeciales', 3, idLimpio, 2);
		if(grid == 'detalleArticulosProduccion')
			cargaIdPosicionBuscador('detalleArticulosProduccion', 3, idLimpio, 2);
		if(grid == 'detalleArticulosCompra')
			cargaIdPosicionBuscador('detalleArticulosCompra', 3, idLimpio, 2);
		
		calculaSurtir(grid, idLimpio);
	});
	
	actualizaImportes('detalleArticulos', 0);
}


function enviarEmailSolCot(id)
{
	$.get( "../especiales/enviarFacturas.php?fact=" + id, function( data ) {
		alert(data);
	});
}

function enviaMailCot(id)
{
	$.get( "../especiales/enviarCotizaciones.php?fact=" + id, function( data ) {
		alert(data);
		location.href = "encabezados.php?t=cmFjX2NvdGl6YWNpb25lcw==&k=" + id + "&op=2&v=1&tabla=&cadP1=MDI0ZG5CbGZqRjhibkJsZmpCOGJYQmxmakY4MQ==&cadP2=MDI0WlhCbGZqQjhhWEJsZmpCOFozQmxmakE9MQ==";
	});
}

function generarCot(id)
{
	$.get( "../ajax/getDatosAjax.php?opc=11&id_control_pedido=" + id, function( data ) {
		if(data == '1')
		{
			alert("Se ha generado la Cotizaci\u00F3n");
			window.location = "../indices/listados.php?t=cmFjX3BlZGlkb3M=&stm=";
		}
		else
		{			
			alert(data);
		}
	});
}
function generarOrdenServicio(id)
{
	$.get( "../ajax/getDatosAjax.php?opc=12&id_control_cotizacion=" + id, function( data ) {
		if(data == '1')
		{
			alert("Se ha generado la Orden de Sevicio ");
			window.location = "../indices/listados.php?t=cmFjX2NvdGl6YWNpb25lcw==&stm=";
		}
		else
		{			
			alert(data);
		}
	});
}

//----
function ocultaCamposSubtiposMovimiento()
{
	
	//obtenemos el tipo de movimiento
	var tipo_movimento = document.getElementById('id_tipo_movimiento').value;
	
	//renglon de incliuer en bitacora de ruta  y permiter transportista externo.
	//fila_catalogo_7
	
	
	//si es entrada
	if(tipo_movimento==1)
	{
		//escondemos los datos de inblifila_catalogo_7
		$("#fila_catalogo_7").hide();
	}
	else
	{
		//mostramos la fila 7
		$("#fila_catalogo_7").show();
	}
	
	//si es salida mostramos la informacion
	
}


