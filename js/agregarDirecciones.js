$(document).ready(function() { 
	try{
		if($('#cl').val() != '')
			$('#id_cliente').val($('#cl').val());
	}
	catch (e) {
	   console.log("Error no se encontro el campo de id cliente");
	}
	
	if($("#t").val() == 'cmFjX2NvdGl6YWNpb25lcw==')
	{
		idcliente = $('#id_cliente').val();
		persona_recibe = $("#persona_recibe").val();
		cambiaCombo(idcliente,"persona_recibe",20,"");
		$("#persona_recibe").val(persona_recibe);
		
		persona_entrega = $("#persona_entrega").val();
		cambiaCombo(idcliente,"persona_entrega",20,"");
		$("#persona_entrega").val(persona_entrega);
	}
	
	$('#id_tipo_lugar_evento').change(function(){
		$('#id_cliente').val($('#cl').val());
	})

	$("#direccion_entrega").focus(function(){        
		recargaCombosClientesDireccion();                
	})

	$("#direccion_evento").focus(function(){        
		recargaCombosClientesDireccion();                
	})

	$("#direccion_recoleccion").focus(function(){        
		recargaCombosClientesDireccion();                
	})

	$('#id_tipo_contacto').change(function(){
		$('#id_cliente').val($('#cl').val());
	})

	$("#persona_recibe").focus(function(){        
		idcliente = $('#id_cliente').val();
		//muestraDatosClientesPedidos();
		//llenamos los combos de persona que recibe
		cambiaCombo(idcliente,"persona_recibe",20,"");
	})
		
	$("#persona_entrega").focus(function(){  
		idcliente = $('#id_cliente').val();
		//muestraDatosClientesPedidos();
		//llenamos los combos de persona que recibe
		cambiaCombo(idcliente,"persona_entrega",20,"");
	})
 });
	







function direccion_ver(){
	 var idDireccionEntrega=$('#direccion_entrega').val();
	 var idDireccionEvento=$('#direccion_evento').val();
	 var idDireccionRecoleccion=$('#direccion_recoleccion').val();
	 var  id=$('#id_cliente').val();
	 if(idDireccionEntrega!='')
	 {
		 var ruta="encabezados.php?t=cmFjX2NsaWVudGVzX2RldGFsbGVfZGlyZWNjaW9uZXM=&k="+idDireccionEntrega+"&op=2&v=1&tcr=&hf=10&valW=100";
		 window.open(ruta, "","width=1000,height=600,scrollbars=YES");
	 }
	 else if(idDireccionEvento!='')
	 {
		 var ruta="encabezados.php?t=cmFjX2NsaWVudGVzX2RldGFsbGVfZGlyZWNjaW9uZXM=&k="+idDireccionEvento+"&op=2&v=1&tcr=&hf=10";
		 window.open(ruta, "", "width=1000,height=600,scrollbars=YES");
	 }
	 else if(idDireccionRecoleccion!='')
	 {
		 var ruta="encabezados.php?t=cmFjX2NsaWVudGVzX2RldGFsbGVfZGlyZWNjaW9uZXM=&k="+idDireccionRecoleccion+"&op=2&v=1&tcr=&hf=10";
		 window.open(ruta, "", "width=1000,height=600,scrollbars=YES");
	 }
	
}
function direccion_agregar(){
	 var id_cliente=$('#id_cliente').val();
	 if(id_cliente==0){
		 alert("Debe seleccionar un cliente");
	 }
	 else
	 {
		 var ruta="encabezados.php?t=cmFjX2NsaWVudGVzX2RldGFsbGVfZGlyZWNjaW9uZXM=&k=&op=1&tcr=&cl="+id_cliente+"&hf=10";
		 window.open(ruta,"", "width=1100,height=600,scrollbars=YES");
	 }
	 
}


function direccion_agregar_Contacto(){
	 var id_cliente=$('#id_cliente').val();
	 if(id_cliente==0){
		 alert("Debe seleccionar un cliente");
	 }
	 else
	 {
		 var ruta="encabezados.php?t=cmFjX2NsaWVudGVzX2RldGFsbGVfY29udGFjdG9z&k=&op=1&tcr=&cl="+id_cliente+"&hf=10";
		 window.open(ruta,"", "width=1100,height=600,scrollbars=YES");
	 }
	 
}


function direccion_ver_Contacto(){
	 var idDireccionEntrega=$('#persona_recibe').val();
	 if(idDireccionEntrega!='')
	 {
		 var ruta="encabezados.php?t=cmFjX2NsaWVudGVzX2RldGFsbGVfY29udGFjdG9z&k="+idDireccionEntrega+"&op=2&v=1&tcr=&hf=10&valW=100";
		 window.open(ruta, "","width=1000,height=600,scrollbars=YES");
	 }
	 
	
}