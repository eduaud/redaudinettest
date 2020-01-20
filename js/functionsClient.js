function ObtenExistencias(obj){
	$("#detallePedidosProductos_4_0").attr("valor",'0');
	$("#detallePedidosProductos_4_0").html('0');
	$("#detallePedidosProductos_6_0").attr("valor",'0');
	$("#detallePedidosProductos_6_0").html('0');
}
function obtenPrefijo(CampoDependiente,tablapadre,clave){
	var datos="valor="+CampoDependiente+"&tabla="+tablapadre;
	if(CampoDependiente!=0){
		$.ajax({
			method: "POST",
			url: "../ajax/obtenNoPedido_.php",
			data:datos,
			dataType:"json"
		})
		.done(function(data){
			$.each(data, function(index, value){
				$("#"+clave).val(value.pedido);
				$("#prefijo").val(value.prefijo);
				$("#consecutivo").val(value.consecutivo);
			});				
		});
	}
}	
function IDFactura(tabla){
/*
var datos="tabla="+tabla;
$.ajax({
	method: "POST",
	url: "../ajax/obtenNoPedido_.php",
	data:datos,
	dataType:"json"
})
	.done(function(data){
	$.each(data,function(index, value){
		$("#id_factura").val(value.pedido);
		$("#prefijo").val(value.prefijo);
		$("#consecutivo").val(value.consecutivo);
	});				
});
*/
}
function mostrarDI(tipoCliente){
	/*if(tipoCliente==2){
		var objeto=$('input[name="propiedades_[11]"]');
		objeto.attr("value","* DI AUDICEL|CHAR|1");
		
		var objeto1=$('input[name="propiedades_[9]"]');
		objeto1.attr("value","* DI|CHAR|0");
		
		$("#fila_catalogo_11 > td.nom_campo >p").remove();
		$("#fila_catalogo_11 > td.nom_campo").append("<p>* DI AUDICEL</p>");
		
		$("#fila_catalogo_9").css('display','none');
		
		$("#di").attr('value','');
		
		var id_audicel=extraerDIAudicel($("#id_sucursal").find("option:selected").val());
		$("#di_audicel").attr('value',id_audicel);

	}else if(tipoCliente==1 || tipoCliente==3){
		var objeto=$('input[name="propiedades_[11]"]');
		objeto.attr("value","DI AUDICEL|CHAR|0");
		
		var objeto1=$('input[name="propiedades_[9]"]');
		objeto1.attr("value","* DI|CHAR|1");
		
		$("#fila_catalogo_9 > td.nom_campo >p").remove();
		$("#fila_catalogo_9 > td.nom_campo").append("<p>* DI</p>");
		
		$("#fila_catalogo_11").css('display','none');
		$("#di_audicel").attr('value','');
	}*/
}
function extraerDIAudicel(plaza){
	var result="";
	$.ajax({
		async:false,
		method:"POST",
		url:"../ajax/obtenerDIAudicel.php",
		data:{plaza:plaza},
		dataType: "json",
		success:function(data){
			$.each(data,function(index,value){
				result=value.di;
			});
		}
	});
	return result;
}
function verArbolCuentasContables(campo,posision,obj){
	$("#"+obj).fancybox({
		href : '../especiales/cuentasContablesArbol.php?campoId='+campo+'&campoCuenta='+posision,
		type : 'iframe',
		maxWidth	: 500,
		maxHeight	: 600,
		fitToView	: false,
		width		: '70%',
		height		: '70%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none',
		afterClose : function(){
			//$("#id_cuenta_contable").attr('value',$("#IDcuentaContable").val());
			//alert(window.location.pathname);
			//window.location.reload(false);
			//parent.cerrarFancyboxYRedirigeAUrl(window.location.pathname);
			//parent.cerrarFancyboxYRedirigeAUrl('http://localhost/nasser/code/especiales/cuentasContables.php');
		}
	});
	
}
function guardaCuentaContable(cuentaC,id,cuenta){
	$("#IDcuentaContable").attr('value',cuentaC);
	var CuentaContable=$("#IDcuentaContable").val();
	parent.cerrarFancybox(CuentaContable,id,cuenta);
}
function guardaCampos(campos,id){
	parent.cerrarFancyboxCampos(campos,id);
	$("#campos").val(id);
}
function BuscaCuentaContable(cuenta_contable,select){
	var optionsSize=$('#'+select+' option').size();
	if(optionsSize==0){
		ruta='buscarCuentaContable.php';
		envio='cuenta_contable='+cuenta_contable;
		var resultado=ajaxN(ruta,envio);
		$("#"+select).append(resultado);
	}
}
function ExcepcionesEntFin(tipoEnt,caso){
	ruta='entidadesFinancieras.php';
	var envio='id_tipoEntidad='+tipoEnt+'&caso='+caso;
	switch(caso){
		case '1':
			if(tipoEnt!='0'){
				var envio='id_tipoEntidad='+tipoEnt+'&caso='+caso;
				var respuesta = ajaxN(ruta, envio);
				a_respuesta=respuesta.split('|');
				apellidos=a_respuesta[0];
				direccion=a_respuesta[1];
				niv=a_respuesta[2];
				nit=a_respuesta[3];
				if(apellidos=='NO')
					ocultarFilas('4');
				else
					mostrarFilas('4');
				if(direccion=='NO')
					ocultarFilas('10,11,12,13,14,15,16,17,18,19');
				else
					mostrarFilas('10,11,12,13,14,15,16,17,18,19');
				if(niv=='NO')
					ocultarFilas('8');
				else
					mostrarFilas('8');
				if(nit=='NO')
					ocultarFilas('9');
				else
					mostrarFilas('9');
			}
		break;
		case '2':
			if(tipoEnt!='0'){
				var respuesta = ajaxN(ruta, envio);
				if(respuesta=='NO')
					ocultarFilas('4,9,11');
				else 
					mostrarFilas('4,9,11');
			}
		break;
	}
	
	
}
function ocultarFilas(campos){
	a_campos=campos.split(',');
	$.each(a_campos,function(index,value){
		$("#fila_catalogo_"+value).css('display','none');
	});
}
function mostrarFilas(campos){
	a_campos=campos.split(',');
	$.each(a_campos,function(index,value){
		$("#fila_catalogo_"+value).css('display','table-row');
	});
}
function ValidaFechaCajasComision(fechaI,fechaF,idCaja){
	var ruta="validaFechasCajasComisiones.php";
	var envio="fechaI="+fechaI+"&fechaF="+fechaF+"&id_caja="+idCaja;
	var resultado=ajaxN(ruta,envio);
	if(resultado=='error'){
		alert('El periodo de la caja de comisiones entra en un rango ya existente');
		return false;
	}if(resultado=='exito'){
		return true;
	}
	else{
		alert(resultado);
		return false;
	}
}