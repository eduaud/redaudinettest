function creaCuentaMayor(){
	//	Activamos los botones de guardar y cancelar
	$("#botonGuardar").attr("disabled",false);
	$("#botonCancelar").attr("disabled",false);
	$("#botonModificar").attr("disabled",true);
	
	//	Activamos los campos de la forma
	$("#item_id").attr("readonly",false);
	$("#item_nm").attr("readonly",false);
	$("#item_gen").attr("disabled",false);
	$("#item_fac").attr("disabled",false);
	$("#item_reg").attr("disabled",false);
	$("#item_id").css("background-color","#FFFFFF");
	$("#item_nm").css("background-color","#FFFFFF");
	$("#item_gen").css("background-color","#FFFFFF");
	$("#item_fac").css("background-color","#FFFFFF");
	$("#item_reg").css("background-color","#FFFFFF");
	
	//	Reseteamos los campos de la forma
	$("#item_id").val("");
	$("#item_nm").val("Nombre o descripci&oacute;n de la cuenta de mayor");
	
	//	No es actualizaci&oacute;n
	$("#actualizacion").val("0");

	//	Desaparecemos momentaneamente el &aacute;rbol
	$("#arbol").css("display","none");
	
	//	Deshabilitamos momentanemante los botones de Nueva cuenta de mayor, Actualizar y Nueva subcuenta
	$("#botonCrearCuentaMayor").attr("disabled",true);
	$("#botonCrearSubcuenta").attr("disabled",true);
	$("#botonCancelarCuenta").attr("disabled",true);
	$("#botonActualizar").attr("disabled",true);

//	La bandera que indica el tipo de cuenta a guardar se pone en 1, que es de mayor	
	$("#tipoCuenta").val("1");
}

function creaSubcuenta(){
	var prefijo = $("#item_id").val(); 
	if(prefijo=='')
		return false;
	//	Activamos los botones de guardar y cancelar
	$("#botonGuardar").attr("disabled",false);
	$("#botonCancelar").attr("disabled",false);
	$("#botonModificar").attr("disabled",true);
	
	//	Activamos los campos de la forma
	$("#item_id").attr("readonly",false);
	$("#item_nm").attr("readonly",false);
	$("#item_id").css("background-color","#FFFFFF");
	$("#item_nm").css("background-color","#FFFFFF");
	
	//	Reseteamos los campos de la forma
	$("#item_id").val("");
	$("#item_nm").val("Nombre o descripci&oacute;n de la subcuenta");
	
	//	No es actualizaci&oacute;n
	$("#actualizacion").val("0");

	//	Colocamos en el value que indica la cuenta padre de la cuenta actual el valor del prefijo
	$("#item_parent_id").val(prefijo);
	
	//	Colocamos como prefijo del id cuenta la cuenta padre
	$("#prefijo").html(prefijo+"-");

	//	Recortamos el size y maxlength del campo de id por el tama&ntilde;o del prefijo + el tama&ntilde;o para el id que queda disponible
	document.getElementById('item_id').size = eval(25-String(document.getElementById('prefijo').innerHTML).length);
	document.getElementById('item_id').maxLength = eval(20-String(document.getElementById('prefijo').innerHTML).length);

	//	Desaparecemos momentaneamente el &aacute;rbol
	$("#arbol").css("display","none");
	
	//	Deshabilitamos momentanemante los botones de Nueva cuenta de mayor, Actualizar y Nueva subcuenta
	$("#botonCrearCuentaMayor").attr("disabled",true);
	$("#botonCrearSubcuenta").attr("disabled",true);
	$("#botonCancelarCuenta").attr("disabled",true);
	$("#botonActualizar").attr("disabled",true);

	//	La bandera que indica el tipo de cuenta la ponemos en cero o cualuier otro valor que indica que no es cuenta de mayor la que se dar&aacute; de alta
	$("#tipoCuenta").val("0");
	
	// Vamos a ver si puede tener hijos la cuenta, si es as&iacute; sugerimos el siguiente ID, de lo contrario mandamos mensaje
	$.get("../sugiereIDCC.php",{ valor: prefijo}, puedeTenerHijos);
}

function puedeTenerHijos(ans){
	if(ans=='noPadre'){
		alert("La cuenta contable est&aacute; bloqueda para ser padre.");
		cancelaProceso();
		return false;
	}
	var arr = ans.split('-');
	$("#item_id").val(arr[arr.length-1]);
}

function cancelaProceso(){
	//	Desactivamos los botones de guardar y cancelar
	$("#botonGuardar").attr("disabled",true);
	$("#botonCancelar").attr("disabled",true);
	$("#botonModificar").attr("disabled",true);
	
	//	Desactivamos los campos de la forma
	$("#item_id").attr("readonly",true);
	$("#item_nm").attr("readonly",true);
	$("#item_gen").attr("disabled",true);
	$("#item_fac").attr("disabled",true);
	$("#item_reg").attr("disabled",true);
	$("#item_id").css("background-color","#CCCCCC");
	$("#item_nm").css("background-color","#CCCCCC");
	$("#item_gen").css("background-color","#CCCCCC");
	$("#item_fac").css("background-color","#CCCCCC");
	$("#item_reg").css("background-color","#CCCCCC");
	
	//	Reseteamos los campos de la forma
	$("#item_id").val("");
	$("#item_nm").val("");
	
	//	El prefijo lo ponemos en vac&iacute;o
	$("#prefijo").html("");

	//	Colocamos el size y maxlength original al campo de id cuenta
	document.getElementById('item_id').size = 25;
	document.getElementById('item_id').maxLength = 20;

	//	Mostramos el &aacute;rbol
	$("#arbol").css("display","block");
	
	//	Regresa la actualizaci&oacute;n a su estado original
	$("#actualizacion").val("0");
	
	//	Habilitamos los botones de Nueva cuenta de mayor, Actualizar y Nueva subcuenta
	$("#botonCrearCuentaMayor").attr("disabled",false);
	$("#botonCrearSubcuenta").attr("disabled",false);
	$("#botonCancelarCuenta").attr("disabled",false);
	$("#botonActualizar").attr("disabled",false);

//	La bandera que indica el tipo de cuenta la regresamos a vac&iacute;o que indica que no se ha seleccionado ninguna cuenta y se dio clic al cot&oacute;n de nueva cuenta o subcuenta	
	$("#tipoCuenta").val("");

}

function modificaCuenta(){
	if($("#item_id").val()=='')
		return false;
	else{
	//	Activamos los botones de guardar y cancelar
		$("#botonGuardar").attr("disabled",false);
		$("#botonCancelar").attr("disabled",false);
		
		//	Activamos el campo Nombre o descripci&oacute;n de la cuenta de la forma
		$("#item_nm").attr("readonly",false);
		$("#item_nm").css("background-color","#FFFFFF");
		
		//	Desaparecemos momentaneamente el &aacute;rbol
		$("#arbol").css("display","none");
		
		//	Deshabilitamos momentanemante los botones de Nueva cuenta de mayor, Actualizar y Nueva subcuenta
		$("#botonCrearCuentaMayor").attr("disabled",true);
		$("#botonCrearSubcuenta").attr("disabled",true);
		$("#botonCancelarCuenta").attr("disabled",true);
		$("#botonActualizar").attr("disabled",true);
		
		//	Ponemos la variable que indica actualizacion en 1
		$("#actualizacion").val("1");
	}
}

function guardaCuenta(){
	if($('#tipoCuenta').val()=='1' || $('#tipoCuenta').val()=='0'){
	//	Primero validamos que los datos est&eacute;n correctos
	//	El ID cuenta contable no debe estar vac&iacute;o, si tiene un dato este no debe contener guiones y no debe ser uno que ya existe
		if($("#item_id").val()==''){
			alert("Debe especificar un id de cuenta contable.");
			$("#item_id").focus();
			return false;
		}else if($("#item_nm").val()==''){
			alert("El campo Nombre o descripci&oacute;n es obligatorio.");
			$("#item_nm").focus();
			return false;
		}else{
			validaID(document.getElementById("item_id"));
			return;
		}
	}
	if($('#actualizacion').val()=='1'){
		if($("#item_nm").val()==''){
			alert("El campo Nombre o descripci&oacute;n es obligatorio.");
			$("#item_nm").focus();
			return false;
		}else{
			saveItem();
			return;
		}
	}
	return;
}

function cancelaCuenta(){
	if($("#item_id").val()==''){
		alert("No ha seleccionado una cuenta contable.");
		return;
	}
	var resp = confirm("Est&aacute; a punto de cancelar la cuenta contable "+$("#item_id").val()+"->"+$("#item_nm").val()+". &iquest;Desea realmente cancelar la cuenta?");
	if(resp){
		$.post("arbolCC/cancelnode.php",{idCuenta: $("#item_id").val()},respCancelado);
	}
	return;
}

function respCancelado(data){
	if(data=='mayor')
		alert("La cuenta que ha seleccionado es una cuenta de mayor y no puede ser eliminada.");
	else if(data=='hijos')
		alert("La cuenta que ha seleccionado no puede ser cancelada pues tiene subcuentas no canceladas.\n\nDebe antes cancelar las cuentas hijas.");
	else if(data=='devueltos')
		alert("Imposible cancelar esta cuenta contable, se usa para procedimientos de tesorer&iacute;a.");
	else if(data=='saldo')
		alert("La cuenta que ha seleccionado no puede ser cancelada porque tiene saldo positivo.");
	else if(data=='exito'){
		alert("La cuenta ha sido cancelada con &eacute;xito.");
		document.location.reload();
	}else
		alert("La cuenta no se ha podido cancelar debido al siguiente error:\n\n"+data+"\n\nIntente nuevamente. Si persiste el error comuniquese con el equipo de Sys&Web con la impresi&oacute;n de esta pantalla.");
	return false;
}
function validaID(objeto){
	var cadena = String(objeto.value);
	cadena = cadena.toUpperCase();
	cadena = cadena.replace(/-|\b/g,"");
	objeto.value = cadena.replace(/\s/g,"");
	$.get("../cuentascontables/buscaID.php",{ opcion: "cc", valor: document.getElementById('prefijo').innerHTML+objeto.value }, respuesta);
	return;
}


function respuesta(ans){
	if(ans=='ya existe'){
		alert("El ID cuenta contable que acaba de indicar ya existe.\nIntroduzca otro.");
		$("#item_id").focus();
		$("#item_id").select();
		return false;
	}else if(ans=='no existe'){
		saveItem();
	}else{
		alert(ans);
	}
	return;
}

function saveItem(){
	try{
		$.post("arbolCC/savenode.php",{
			   actualizacion: $("#actualizacion").val(),
			   tipoCuenta: $("#tipoCuenta").val(),
			   item_parent_id: $("#item_parent_id").val(),
			   item_id: $("#item_id").val(),
			   item_nm: $("#item_nm").val(),
			   item_gen: $("#item_gen").val(),
			   item_fac: $("#item_fac").val(),
			   item_reg: $("#item_reg").val()
			   },respGuardado);
	}catch(e){
		alert(e);
	}
}

function respGuardado(data){
	if(data==''){
		alert("Informaci&oacute;n actualizada con &eacute;xito.");
		if($('#tipoCuenta').val()=='0'){
			$("#item_id").val($("#item_parent_id").val());
			//document.location.reload();
			creaSubcuenta();
		}else
			document.location.reload();
	}
	else
		alert("No fue posible actualizar la informaci&oacute;n, verifique sus datos e intente nuevamente.");
	return;
}