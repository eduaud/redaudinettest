function obtenerDependencia(objetoPadre,objetoHijo,referencia){
	data={referencia:referencia,id:objetoPadre.value};
	var stm=$("#stm").val();
	if(stm=='75008'){
		data={referencia:referencia,id:objetoPadre.value,id_tipo_cliente:'2'};
	}else if(stm=='75009'){
		data={referencia:referencia,id:objetoPadre.value,id_tipo_cliente:'3'};
	}else if(stm=='75003'||stm=='75004'){
		data={referencia:referencia,id:objetoPadre.value,id_tipo_cliente:'1'};
	}
	$("#"+objetoHijo+" option").remove();
	if(objetoPadre.value==0){
		$("#"+objetoHijo+" option").remove();
		$("#"+objetoHijo).append("<option value='0'> - Seleccione una opci&oacute;n - </option>");
		$("#"+objetoHijo).prop('disabled','');
	}
	else{
		$.ajax({
			method: "POST",
			url: "../ajax/cargaDependencias.php",
			data:data,
			dataType:"json"
		})
		.done(function(data){
			if(data!=null){
				$("#"+objetoHijo).append("<option value='0'> - Seleccione una opci&oacute;n - </option>");
				$.each(data, function(index, value){
					if(stm=='75005'){
						$("#"+objetoHijo).append("<option value='"+value.id+"' selected>"+value.nombre+"</option>");
					}else{
						$("#"+objetoHijo).append("<option value='"+value.id+"'>"+value.nombre+"</option>");
					}
				});
			}
			else
				$("#"+objetoHijo).attr('value','');
				
		});
	}
}
/*
funcion que muestra u oculta campos dependiendo el valor de un combo
PARAMETROS:
camposD=los campos que requiere cada opcion del select en conjunto.
opcion=valor del select.
tabla=tabla de la base de datos.
*/
function oculta_muestra_campos(campos,opcion,tabla){
	var arr_camposOcultar=new Array();
	
	if(tabla=='cl_productos_servicios'){
		if(opcion=='2'){
			arr_camposOcultar[0]='4';
			arr_camposOcultar[1]='6';
			
			arr_camposOcultar[2]='8';
			arr_camposOcultar[3]='9';
		}
	}else if(tabla=='ad_clientes'){
		/*if(opcion=='1'||opcion=='3'){
			arr_camposOcultar[0]='9';
		}else if(opcion=='2'){
			arr_camposOcultar[0]='11';
		}*/
	}
	var camposAsociados=campos.split(',');
	
	$.each(camposAsociados,function(index,value){
		$("#fila_catalogo_"+value).css('display','table-row');
	});
	$.each(arr_camposOcultar,function(index,value){
		$("#fila_catalogo_"+value).css('display','none');
	});
	/*var campos="";//campos que se van a mostrar
	if(tabla=='cl_productos_servicios'){
		if(opcion=='1'){
			campos="4,6,7,8";
		}
	}else if(tabla=='ad_clientes'){
		if(opcion=='1'||opcion=='3'){
			campos="9";
		}else if(opcion=='2'){
			campos="11";
		}
	}
	var arr_campos_considerar=camposD.split(',');
	if(campos!=""){
		var arr_campos=campos.split(',');
		$.each(arr_campos_considerar,function(index,value){
			var bandera=false;
			$.each(arr_campos,function(indice,valor){
				if(arr_campos_considerar[index]==arr_campos[indice]){
					$("#fila_catalogo_"+value).css('display','table-row');
					bandera=true;
				} 
				if(bandera == false){
					$("#fila_catalogo_"+value).css('display','none');
				}
			});
		});
	}else{
		$.each(arr_campos_considerar,function(index,value){
			$("#fila_catalogo_"+value).css('display','none');
		});
	}*/
}
function mostrar_div(id_div_mostrar,mostrar){
	if(mostrar==true) {
		$("#"+id_div_mostrar).css("display", "block");
	  } 
	else {
		$("#"+id_div_mostrar).css("display", "none");
	}
}

function obtenerIDControlOrdenCompra(idAlmacenRecepcion){
	$.ajax({
		method: "POST",
		url: "../ajax/obtenerIDControlOrdenCompra.php",
		data:{idAlmacenRecepcion:idAlmacenRecepcion},
		dataType:"json"
	})
	.done(function(data){
		if(data!=null){
			$("#id_orden_compra").val(data);
		}
		else{
			$("#id_orden_compra").val("");
		}
	});
}

function limpiaPrefijoOrdenCompra(){
	$("#id_orden_compra").val("");
}
function validaCamposRepetidos(tabla,campoValidar,accion,CampoLlave,modulo){
	var valor=$("#"+campoValidar).val();
	var CampoLlaveValor=$("#"+CampoLlave).val();
	var datos="campoValidar="+campoValidar+"&caso=2&tabla="+tabla+"&valor="+valor+"&accion="+accion+"&CampoLlaveValor="+CampoLlaveValor+"&CampoLlave="+CampoLlave+"&modulo="+modulo;
	var result = ajaxN("validacionesClienteAudicel.php",datos);	
	if(result=='exito')
		return true;
	else if(result=='error')
		return false;
	else if(result=='vacio')
		return 'vacio';
	
	
}
function validaClaveInsert(tabla,datosExtra){
	var clave=$("#clave").val();
	if(datosExtra!='')
		var datos="clave="+clave+"&tabla="+tabla+"&datosExtra="+datosExtra;
	else 
		var datos="clave="+clave+"&tabla="+tabla;
	var aux = ajaxN("../ajax/validaClave.php",datos);
	if(aux!="exito"){
		if(aux=='')
			return false;
		else{
			alert(aux);
		return true;
		}
	}
}
function validaClave(datos,datosExtra){
	if(datosExtra!='')
		datos+="&datosExtra="+datosExtra;
	var aux = ajaxN("../ajax/validaClave.php",datos);
	return aux;
}
function ColocaClaveDistribuidor(id_cliente){
	var datos="id_cliente="+id_cliente+"&caso=1";
	var aux = ajaxN("validacionesClienteAudicel.php",datos);
	$("#clave").val(aux);
	$("#clave").prop('readonly','readonly');
}
function obtenerDIAudicel(id_plaza){
	var stm=$("#stm").val();
	if(stm=='75001'||stm=='75002'){
		var datos="id_plaza="+id_plaza+"&caso=3";
		var aux = ajaxN("validacionesClienteAudicel.php",datos);
		$("#di_audicel").val(aux);
	}
}
function actualizaClientesHijosAudicel(campoLlave){
	var id_cliente=$("#"+campoLlave).val();
	var id_plaza=$("#id_sucursal").val();
	var activo=$("#activo").prop('checked');
	if(activo==true)
		activo='1';
	else
		activo='0';
	var clave=$("#clave").val();
	var datos="id_cliente="+id_cliente+"&clave="+clave+"&id_sucursal="+id_plaza+"&activo="+activo+"&caso=4";
	var aux = ajaxN("validacionesClienteAudicel.php",datos);
}