var sucursales = Array();
var resp = ajaxR("agendaSpa.php?accion=3&catalogo=56a9a7a36174208cebe90fd229fcc7f42a");
sucursales = jQuery.parseJSON(resp);	

$(document).ready(function() {		
	//FILTRAR USUARIOS EN AUTOCOMPLETE
	$(function() {		
		var options = $( "#clienteSucursal" ).autocomplete({
			minLength: 3,
			source: "agendaSpa.php?accion=3&catalogo=56a9a7a36174209cf2e31fce2ef8d8", //availableTags
			select: function(event, ui){
				 $("#clienteSucursal").attr('valor', ui.item.value );
				 $("#clienteSucursal").val( ui.item.label );
				 return false;
			}
		});		
	});	
	
	$("#limpiarIDCliente").click(function(){
		$("#clienteSucursal").val('');
		$("#clienteSucursal").removeAttr('readonly');
		$("#listaProspectoNotaCambio").hide();
		$("#buscarProspectoNotaCambio").show();
		$("#limpiarIDCliente").hide();		
	});
	
	$("#buscarProspectoNotaCambio").click(function(){
		cargando(1);
		id = $("#clienteSucursal").attr('valor');
		if(isNaN(id) == false && id != ""){
			$("#limpiarIDCliente").show();
			$("#buscarProspectoNotaCambio").hide();
			$("#clienteSucursal").attr('readonly', 'readonly')
			cargarProspectoNotaCambio(id);
			$("#listaProspectoNotaCambio").show();
		}else{
			alert("Error en el ID del cliente");	
		}
		cargando(0);
	});
	
	$(".selAnota").live("click", function(){
		id = $(this).attr("id");
		var n = id.split("_");
		id = n[1];
		
		window.location.href ="generarNotaCambio.php?accion=2&idGenerar=" + id;
	});
	
	$("#guardarb").click(function(){
		idCliente = $("#idCliente").val();
		idSucursal = $("#clienteSucursal").attr("valor");

		if(idCliente != "" && isNaN(idSucursal) == false){
			$.get("generarNotaCambio.php?accion=3&idCliente=" + idCliente + "&idSucursal=" + idSucursal, function(data){ 
				n = data.split("|");
				if(n[0] == 'ok'){
					alert(n[1]);
					window.location.href ="generarNotaCambio.php";
				}else{
					alert(n[1]);	
				}
			});
		}
	});
	
});

function cargando(status){
	if(status == 1)	
		$("#cargando").show();
	else
		$("#cargando").hide();
}

function cargarProspectoNotaCambio(idCliente){
	tipo = "NormalCell_gris";
	var i = 0;
	$.getJSON("generarNotaCambio.php?accion=1&idCliente=" + idCliente,function(data){ 
		var temporal = "";
		$("#bodyListaProspectoNotaCambio").html('');
		$.each(data, function(i, item){
			if(tipo == "NormalCell_gris")
				tipo = "NormalCell"
			else 
				tipo = "NormalCell_gris"
			i = i +1;	
			$("#bodyListaProspectoNotaCambio").append('<tr class="' + tipo + '" class_original="' + tipo + '" onmouseover="selfil(this);" onmouseout="dselfil(this);">');
			$.each(item, function(j, item2){
				if(j == '1'){
					temporal = '<td align="center"><img src="../../imagenes/ic18x18_ver.png" class="selAnota" id="notaCambio_' + item2 + '" style="cursor:pointer" width="18" height="18" border="0"></td>';					
				}else{
					$("#bodyListaProspectoNotaCambio tr:last").append("<td align='center'>" + item2 + "</td>");
				}
			});
			$("#bodyListaProspectoNotaCambio tr:last").append(temporal);
			temporal = "";
			
			$("#totalRegs").html(i);
		});	
		if($("#bodyListaProspectoNotaCambio").html() == "")
			$("#bodyListaProspectoNotaCambio").html('<tr><td colspan="4">No hay datos que mostrar</td></tr>');
			
	});	
	
}