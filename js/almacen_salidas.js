$(document).ready(function(){

	$("#buscarDatos").click(function(){
		$("#bodySolicitado").html("");
		$("#bodyRecibido").html("");
		
		var idDoc = $("#idDocBuscado").val();
		var idTipoDoc = $("#tipoEntrada").val();
		
		if(idTipoDoc == '0'){
			alert("Debe seleccionar un tipo de salida");
			return false;
		}
		
		$.getJSON("almacen_salidas.php?idPantalla=13&accion=1&tipoDoc="+idTipoDoc+"&idSalida=" + idDoc, function(data){ 
			$.each(data[0], function(i, item){
				$("#bodySolicitado").append(
					"<tr id='itemBusc_"+item[0]+"_"+item[1]+"_"+item[7]+"_"+item[8]+"'>"+
						"<td id='itemBusc_"+item[0]+"_"+item[1]+"_"+item[7]+"_"+item[8]+"_0'>"+item[2]+"</td>"+
						"<td id='itemBusc_"+item[0]+"_"+item[1]+"_"+item[7]+"_"+item[8]+"_1'>"+item[3]+"</td>"+
						"<td id='itemBusc_"+item[0]+"_"+item[1]+"_"+item[7]+"_"+item[8]+"_2'>"+item[4]+"</td>"+
						"<td id='itemBusc_"+item[0]+"_"+item[1]+"_"+item[7]+"_"+item[8]+"_3'>"+item[5]+"</td>"+
						'<td class="table_align_center_icon"><input type="button" class="btnDatosSel" id="hhhh" style="background-image:url(../../imagenes/iconos/btn_ic_new.png); height:30; width:30;  background-repeat:no-repeat" alt="modificar" value="      " onclick="btnSel(\''+item[0]+'_'+item[1]+"_"+item[7]+"_"+item[8]+'\')" /></td>'+
					'</tr>'
				);
			});	
			
			$.each(data[1], function(i, item){
				$("#bodyRecibido").append(
					"<tr>"+
						"<td>"+item[1]+"</td>"+
						"<td>"+item[2]+"</td>"+
						"<td>"+item[3]+"</td>"+
						"<td>"+item[4]+"</td>"+
						"<td>"+item[5]+"</td>"+
						'<td>N/A</td><td>N/A</td>'+
					'</tr>'
				);
			});	
			
			$("#infoEntrada").html(data[2][0][1] + "" + data[2][0][2]);
			
			$("#contenido").show();
		});												 
	});

	
});

function btnSel(valor){
	//alert(valor);	
	abrirThickBox(valor, '');
}

function abrirThickBox(id, valoresExtras){
	//idCliente = $("#idClienteBuscar").val();
	val1 = $("#itemBusc_"+id+"_2").html();
	val2 = $("#itemBusc_"+id+"_3").html();
	
	ruta="almacen_salidas.php?accion=2&datos="+id+"&val1="+val1+"&val2="+val2+valoresExtras+"&height=500&width=600&modal=false";//"agendaSpa.php?accion=2&idCliente=" + idCliente + "&height=570&width=1000&modal=true";
	var obj=document.getElementById('thickbox_href');
	obj.setAttribute('href',ruta);
	obj.click();		
}

/*
$("#agregarItem").on("click", function(){
	val1 = $("#sku").val();
	val2 = $("#valUbicacion").val();
	val2b = $("#valUbicacion option:selected").html();
	val3 = $("#valCantidad").val();
	val4 = $("#valLote").val();
	val5 = $("#valDoc").val();
	
	val6 = $("#id_producto").val();
	val7 = $("#id_entrada").val();
	val8 = $("#id_entrada_solicitado").val();
	val9 = $("#id_origen_dest").val();
	val10 = $("#clasifLlanta").val();
	
	val11 = $("#val1").val();
	val12 = $("#val2").val();
	
	val11 = val11.replace(/,/gi, "").replace("$","");
	val12 = val12.replace(/,/gi, "").replace("$","");
	
	indice = $("#bodyRecibido").attr("indice");
	fila = $("#fila").val();
	valCantidadTemp = $("#valCantidadTemp").val().replace(/,/gi, "").replace("$","");
	
	if(val2 != "0"){
		if(parseFloat(val12) + parseFloat(val3) - parseFloat(valCantidadTemp) <= parseFloat(val11)){
			if(fila != ""){
				btnDel(fila);
			}
			
			id = val2+'_'+val10+'_'+val6+'_'+val7+"_"+val8+"_"+val9+"_"+indice;
			idPadre = val7+"_"+val6+"_"+val8+"_"+val9;
			
			
			$("#bodyRecibido").append(
				"<tr class='tr_valores_captura' id='fila_"+id+"'>"+
					"<td id='fila_"+id+"_1'>"+val1+"</td>"+
					"<td>"+val2b+"</td>"+
					"<td id='fila_"+id+"_2'>"+val3+"</td>"+
					"<td id='fila_"+id+"_3'>"+val4+"</td>"+
					"<td id='fila_"+id+"_4'>"+val5+"</td>"+
					'<td class="table_align_center_icon"><input type="button" class="btnDatosGen" idPadre="'+idPadre+'" id="'+id+'" style="background-image:url(../../imagenes/iconos/ic18x18_modificar2.png); height:30; width:30;  background-repeat:no-repeat" alt="modificar" value="    " onclick="btnMod(this.id)" /></td>'+
					'<td class="table_align_center_icon"><input type="button" class="btnDatosGen" id="'+id+'" style="background-image:url(../../imagenes/iconos/ic18x18_eliminar.png); height:30; width:30;  background-repeat:no-repeat" alt="modificar" value="    " onclick="btnDel(this.id)" /></td>'+
				'</tr>'
			);
			$("#bodyRecibido").attr("indice", parseFloat(indice) + 1);
			$("#itemBusc_" + idPadre + "_3").html(parseFloat(val12) + parseFloat(val3) - parseFloat(valCantidadTemp));
			tb_remove();
		}else{
			alert("La cantidad de items es mayor a lo solicitado");	
		}
	}else{
		alert("Seleccione una ubicaci\u00F3n");	
	}
});
*/

function btnMod(id){
	//alert(id + "|" + $("#"+id).attr('idPadre'));
	idPadre = $("#"+id).attr('idPadre');
	valores = id.split("_");
	abrirThickBox(idPadre, "&valCantidad="+$("#fila_"+id+"_2").html()+"&valLote="+$("#fila_"+id+"_3").html()+"&valUbicacion="+valores[0]+"&valDoc="+$("#fila_"+id+"_4").html()+"&clasifLlanta="+valores[1]+"&fila="+id+"&valSimilar="+valores[7]);
}

function btnDel(id){
	idPadre = $("#"+id).attr('idPadre');
	idValEliminar = $("#fila_"+id+"_2").html();
	$("#itemBusc_" + idPadre + "_3").html(parseFloat($("#itemBusc_" + idPadre + "_3").html()) - parseFloat(idValEliminar));
	$("#fila_"+id).remove();
}

/*$("#guardarb").click(function(){
	Array.prototype.unique=function(a){
	  return function(){return this.filter(a)}}(function(a,b,c){return c.indexOf(a,b+1)<0
	});

	datosId = new Array();
	datosIdUnicos = new Array();	
	$(".tr_valores_captura").each(function(){
		console.log("-->" + $(this).attr('id'));
		datosId.push($(this).attr('id'));
	});
	datosIdUnicos = datosId.unique();
	
	console.log(datosIdUnicos);
});*/

function guardarCambios(){
	var cadena = "";
	Array.prototype.unique=function(a){
	  return function(){return this.filter(a)}}(function(a,b,c){return c.indexOf(a,b+1)<0
	});

	datosId = new Array();
	datosIdUnicos = new Array();	
	$(".tr_valores_captura").each(function(){
		datosId.push($(this).attr('id'));
	});
	datosIdUnicos = datosId.unique();
	
	for(i=0;i<datosIdUnicos.length;i++){
		id = datosIdUnicos[i];
		valores = id.split("_");
		
		//fila_0_undefined_4679_1_5_CAJA_36545554_4676_3
		idProd = (valores[8] == 'undefined') ? valores[3] : valores[8];
		cadena += "id_salida_solicitada="+valores[5]+"|id_producto="+idProd+"|cantidad="+$("#"+id+"_2").html()+"|id_lote="+$("#"+id+"_3").html()+"|id_ubicacion="+valores[1]+"|id_clasificacion_llanta="+valores[2]+"~";
		//cadena += "id_entrada_solicitado="+valores[5]+"|id_producto="+valores[3]+"|cantidad="+$("#"+id+"_2").html()+"|id_lote="+$("#"+id+"_3").html()+"|id_ubicacion="+valores[1]+"|documento_entrega="+$("#"+id+"_4").html()+"|id_clasificacion_llanta="+valores[2]+"~";
	}
	
	$.getJSON("almacen_salidas.php?idPantalla=13&accion=3&datos=" + cadena, function(data){ 
		valores = data.split("|");
		alert(valores[1]);
		if(valores[0] == 'ok'){
			console.log(data);
			location.href="almacen_salidas.php";
		}
	});
	
}