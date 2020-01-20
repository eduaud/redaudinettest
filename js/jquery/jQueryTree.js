$(function(){
	$.ajaxSetup({ type: "POST" });
	$("ul#arbolCuentas li").each(function(){
		$(this).click(abrir);
		$(this).click(fillForm);
	});
//	$("#arbol").keyup(desplaza);
});
/*
function desplaza(e){
	//	arriba= 38
	//	abajo= 40
	if(e.keyCode==38)
		alert($(this).siblings('.abierto').next().toggleClass('abierto'));
	if(e.keyCode==40)
		alert($(this));
	return;
}
*/
function fillForm(){
	$('#item_id').val($(this).attr('id').replace(/li_/,""));
	var arr = $(this).attr('nm').split("->");
	$('#item_nm').val(arr[1]);
	$('#item_gen').val($(this).attr('gen'));
	$('#item_fac').val($(this).attr('fac'));
	$('#item_reg').val($(this).attr('reg'));
	return;
}

function abrir(){
	status(true);
	$("#botonModificar").attr("disabled",false);
	if($('li',this).length>0){
		$('li',this).remove();
		status(false);
	}
	else{
		$(this).siblings('.abierto').each(function(){ $('li',this).remove()});
		$(this).siblings('.abierto').removeClass('abierto');
		$.getJSON('./arbolCC/loaddetails.php',{id:$(this).attr('id')},loaded);
	}
	$(this).toggleClass('abierto');
	return false;
}

function loaded(json){
	if(json[1].length==0){
		status(false);
		return;
	}
	for(var i=0, r; r=json[1][i]; i++)
		$('li#li_'+json[0]).append($('<li id="li_'+r.id+'" gen="'+r.genero+'" fac="'+r.facturable+'" nm="'+r.nombre+'">'+r.nombre+'</li>').click(fillForm).click(abrir));
	status(false);
}

function status(fl){
	var d = document.getElementById("showproc");
	if(fl)
		d.style.display = "";
	else
		d.style.display = "none";
}