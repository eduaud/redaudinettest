$(document).ready(function() {
	$('.btnCancelarCotizacion').live('click',function(){
		var id = $(this).attr('id');	
		filaGrid = $("#colsel").val();
		
		valorSel = id.split("_");
		
		//OBTENEMOS EL VALOR DE LO QUE FALTA POR SURTIR DEBIDO A FALTA DE INVENTARIO
		var faltante_x_surtir = parseInt(celdaValorXY('detalleArticulos', 6, filaGrid));
		var cantidad_x_confirmar = (celdaValorXY('detalleArticulos', 30, filaGrid) == '')? 0 : parseInt(celdaValorXY('detalleArticulos', 30, filaGrid));
		
		if(faltante_x_surtir > cantidad_x_confirmar)
		{
			if (confirm("¿Desea solicitar la liberación de este producto?"))
			{
				/*
				//COLOCAMOS EL ESTATUS DEL DETALLE
				valorXY('detalleArticulos',17,filaGrid,9); 
				valorXY('detalleArticulos',18,filaGrid,9); 
				*/
				//INDICA EL DETALLE DEL ARTICULO A SOLICITAR
				
				var valorPrevio26 = (celdaValorXY('detalleArticulos', 26, filaGrid) == '0')? '' : celdaValorXY('detalleArticulos', 26, filaGrid);
				var valorPrevio27 = (celdaValorXY('detalleArticulos', 27, filaGrid) == '0')? '' : celdaValorXY('detalleArticulos', 27, filaGrid);
					
				if(valorSel[0] == "solCanc1")
				{
					valorXY('detalleArticulos', 26, filaGrid, valorPrevio26 + valorSel[1] + "]");
					//console.log("1");
				}
				else if(valorSel[0] == "solCanc3")
				{
					valorXY('detalleArticulos', 27, filaGrid, valorPrevio27 + valorSel[1] + "]");
					//console.log("2");
				}
				
				cantidad = valorSel[1].split(":");
				valorXY('detalleArticulos', 30, filaGrid, cantidad_x_confirmar + parseInt(cantidad[2]));
				
				$(this).remove();
			}
		}
		else
		{
			alert("Ya ha solicitado suficientes cancelaciones para cubrir el faltante");
		}
	});	
});
	





function guardarTransformables()
{       
	cadenaRetorno = "";
	numFilaBasicosGen = -1;
	
	if(confirm("¿Confirma que desea actualizar los valores para transformaciones b\u00E1sicas?"))
	{
		var id_articulo_original = $("#id_articulo_original").val();
		var articulo_original = $("#articulo_original").val();
		
		$('.transfBasicas:checkbox:checked').each(
			function () 
			{
				idTransformar = $(this).attr("id").split("_");
				
				if($("#existSol_" + idTransformar[1]).val() == "")
				{
					alert("El producto " + $("#nombProd_" + idTransformar[1]).attr("valor") + " fue seleccionado pero no se indico una cantidad");
					return false;
				}
				
				$('#Body_detalleArticulosBasicos tr').each(
					function () 
					{					
						numFilaBasicos = $(this).attr("id").replace("detalleArticulosBasicos_Fila", "");
						numFilaBasicosGen = parseInt(numFilaBasicos);
						
						if(idTransformar[1] == celdaValorXY('detalleArticulosBasicos', 2, numFilaBasicos))
						{
							EliminaFila('detalleArticulosBasicos_27_' + numFilaBasicos);
							numFilaBasicosGen = numFilaBasicosGen - 1;
						}
					}
				);
				cadenaRetorno += idTransformar[1] + ":" + $("#existSol_" + idTransformar[1]).val() + "]";
				
				nuevoGridFila('detalleArticulosBasicos');
				
				var filas = numFilaBasicosGen + 1;
				
				valorXY('detalleArticulosBasicos', 4, filas, id_articulo_original);
				valorXY('detalleArticulosBasicos', 5, filas, id_articulo_original + " : " + articulo_original);
				valorXY('detalleArticulosBasicos', 2, filas, idTransformar[1]);
				valorXY('detalleArticulosBasicos', 3, filas, $("#nombProd_" + idTransformar[1]).attr("valor")); 
				valorXY('detalleArticulosBasicos', 6, filas, $("#existSol_" + idTransformar[1]).val());
				valorXY('detalleArticulosBasicos', 7, filas, $("#existExist_" + idTransformar[1]).attr("valor"));
				valorXY('detalleArticulosBasicos', 8, filas, "0");
				valorXY('detalleArticulosBasicos', 9, filas, $("#existSol_" + idTransformar[1]).val());
				valorXY('detalleArticulosBasicos', 10, filas,$("#existExist_" + idTransformar[1]).attr("precioTrans"));
				
				calculaSurtir('detalleArticulosBasicos', filas);
				
				alert("Se ha actualizado la tabla de transformaciones b\u00E1sicas");
			}
		);
	}	
}





