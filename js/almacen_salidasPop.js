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
	
	val13 = $("#valSimilar").val();
	val13b = $("#valSimilar option:selected").html();
	
	val14 = $("#similar").val();
	val15 = $("#producto").val();
	
	indice = $("#bodyRecibido").attr("indice");
	fila = $("#fila").val();
	valCantidadTemp = $("#valCantidadTemp").val().replace(/,/gi, "").replace("$","");
	
	//if(val2 != "0"){
		if((parseFloat(val12) + parseFloat(val3) - parseFloat(valCantidadTemp) <= parseFloat(val11)) || (val14 == '1')){
			if(fila != ""){
				btnDel(fila);
			}
			
			id = val2+'_'+val10+'_'+val6+'_'+val7+"_"+val8+"_"+val9+"_"+val13+"_"+indice;
			idPadre = val7+"_"+val6+"_"+val8+"_"+val9;
			
			
			$("#bodyRecibido").append(
				"<tr class='tr_valores_captura' id='fila_"+id+"'>"+
					"<td id='fila_"+id+"_1'>"+val1+"</td>"+
					"<td>"+val15+"</td>"+
					"<td>"+val2b+"</td>"+
					"<td id='fila_"+id+"_2'>"+val3+"</td>"+
					"<td id='fila_"+id+"_3'>"+val4+"</td>"+
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
	/*}else{
		alert("Seleccione una ubicaci\u00F3n");	
	}*/
});