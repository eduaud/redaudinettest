$(document).ready(function(){	
						   
	$('#generarCierre').click(function() {
		if(confirm(String.fromCharCode(191) + "Esta seguro que desea realizar el cierre?")){
	  		generarCierre();
		}
	});
	
	$(function() {
		$( ".datepicker" ).datepicker({
			changeMonth: true,
			changeYear: true
		});
	});
	
	$("#imprimirCierre").live('click', function(){
		imprimir('divImprimirCierre', '<link rel=\'stylesheet\' type=\'text/css\' href=\'../../js/fullcalendar-1.5.4/fullcalendar/fullcalendar.css\' />' + 
				'CIERRE DE CAJA');
	});
});

/********************************************
* Función para cargar los cliente existentes en clientes/facturas
*********************************************/
function generarCierre(){
	var resp = ajaxR("cierreCaja.php?accion=2&fecha=" + $("#fechaCierre").val());
	n = resp.split("|");
	
	alert(n[1]);
	
	if(n[0] == 'ok'){
		window.location = "cierreCaja.php?accion=1&idCierre=" + n[2] + "&fecha=" + n[3];
	}

	$("#clienteSelImpo").html(selectCliente);
}
