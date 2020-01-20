$(document).ready(function() {
		if($("#t").val() == "bmFfY2xpZW50ZXM=" && $("#op").val() == 1){
				var envia_datos = "caso=1";
				var url = "obtenSesion.php";
				var sesion = ajaxN(url, envia_datos);
				$("#id_sucursal_alta option[value='" + sesion + "']").prop("selected", "selected");
				}
		});
		
function agregaDireccion(){
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
		$("#detallesclientesdirent_8_" + pos[1]).attr("valor", $("#id_estado option:selected").val());
		$("#detallesclientesdirent_8_" + pos[1]).html($("#id_estado option:selected").html());
		//Agregamos Ciudad
		$("#detallesclientesdirent_9_" + pos[1]).attr("valor", $("#id_ciudad option:selected").val());
		$("#detallesclientesdirent_10_" + pos[1]).attr("valor", $("#id_ciudad option:selected").val());
		$("#detallesclientesdirent_10_" + pos[1]).html($("#id_ciudad option:selected").html());
		//Agregamos CP
		$("#detallesclientesdirent_11_" + pos[1]).attr("valor", $("#cp").val());
		$("#detallesclientesdirent_11_" + pos[1]).html($("#cp").val());
		//Agregamos Telefono
		$("#detallesclientesdirent_12_" + pos[1]).attr("valor", $("#telefono_facturas_1").val());
		$("#detallesclientesdirent_12_" + pos[1]).html($("#telefono_facturas_1").val());
		
		
		}

function agregaDireccionFactura(pos){
		$("#calle").val($("#detallesclientesdirent_2_" + pos).attr("valor"));
		$("#numero_exterior").val($("#detallesclientesdirent_3_" + pos).attr("valor"));
		$("#numero_interior").val($("#detallesclientesdirent_4_" + pos).attr("valor"));
		$("#colonia").val($("#detallesclientesdirent_5_" + pos).attr("valor"));
		$("#delegacion_municipio").val($("#detallesclientesdirent_6_" + pos).attr("valor"));
		$('#id_estado > option[value="' + $("#detallesclientesdirent_7_" + pos).attr("valor") + '"]').attr('selected', 'selected');
		obtenCiudad();
		$('#id_ciudad > option[value="' + $("#detallesclientesdirent_9_" + pos).attr("valor") + '"]').attr('selected', 'selected');
		$("#cp").val($("#detallesclientesdirent_11_" + pos).attr("valor"));
		$("#telefono_facturas_1").val($("#detallesclientesdirent_12_" + pos).attr("valor"));
		}
		
		
		
		
		
		
		
		
		