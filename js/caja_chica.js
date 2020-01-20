$(document).ready(function() {
		if($("#t").val() == "YWRfaW5ncmVzb3NfY2FqYV9jaGljYQ=="){ //ad_ingresos_caja_chica
				ocultaCamposCH();
				}
		if($("#t").val() == "YWRfZWdyZXNvc19jYWphX2NoaWNh"){	//ad_egresos_caja_chica
				ocultaCamposEgresosCH();
				saldoCajaChica();
				}
		});
		
function saldoCajaChica(){
		var ruta = "saldoCajaChica.php";
		var envio = "id=1";
		var saldo = ajaxN(ruta, envio);
		$("#total_caja").val(formatear_pesos(saldo));
		}

function ocultaCamposCH(){
		if($("#v").val() == 1){
				var tipo_ingreso = $("#id_tipo_ingreso").val();
				}
		else{
		var tipo_ingreso = $("#id_tipo_ingreso").val();				
		//var tipo_ingreso = $("#id_tipo_ingreso").find("option:selected").val();
				}
		
		if(tipo_ingreso == 1 || tipo_ingreso == 2 || tipo_ingreso == 3){
				if(tipo_ingreso == 1){
						$("#fila_catalogo_3").show();
						}
				else{
						$("#id_pedido").val("");
						$("#fila_catalogo_3").hide();
						}
				if(tipo_ingreso == 3){
						$("#fila_catalogo_4").show();
						}
				else{
						$("#id_cuenta_por_cobrar").val("");
						$("#fila_catalogo_4").hide();
						}
				if(tipo_ingreso == 2){
						$("#fila_catalogo_2").show();
						$("#fila_catalogo_5").show();
						$("#fila_catalogo_11").show();
						}
				else{
						$("#fecha_salida_almacen").val("");
						$("#monto_egreso").val("");
						$("#confirmado").prop('checked', false);
						$('#id_sucursal_origen > option[value="0"]').attr('selected', 'selected');
						$("#fila_catalogo_2").hide();
						$("#fila_catalogo_5").hide();
						$("#fila_catalogo_11").hide();
						}
				}
		else{
				$("#fila_catalogo_2").hide();
				$("#fila_catalogo_3").hide();
				$("#fila_catalogo_4").hide();
				$("#fila_catalogo_5").hide();
				$("#fila_catalogo_11").hide();
				}
				
		}

function verificaModIngreso(IDingreso){
		var ruta = "verificaModIngreso.php";
		var envio = "id=" + IDingreso;
		var respuesta = ajaxN(ruta, envio);
		return respuesta;
		}
		
function saldoCXC(){
		var subtotal = $("#subtotal").val();
		subtotal = subtotal.replace(",", "");
		subtotal = subtotal == "" ? subtotal = 0 : subtotal = subtotal;
		var total = $("#total").val();
		total = total.replace(",", "");
		total = total == "" ? total = 0 : total = total;
		var saldo = total - subtotal;
		$("#saldo").val(formatear_pesos(saldo));
		}
		
function ocultaCamposEgresosCH(){
		if($("#v").val() == 1){
				var tipo_egreso = $("#id_tipo_egreso").val();
				}
		else{
				var tipo_egreso = $("#id_tipo_egreso").find("option:selected").val();
				}
		var ruta = "verificaGridEgresos.php";
		var envio = "id=" + tipo_egreso;
		var respuesta = ajaxN(ruta, envio);
		if(respuesta == 0){
				$("#Body_detalleEgresosCajaChica tr").remove();
				$("#divgrid_detalleEgresosCajaChica").hide();
				}
		else{
				$("#divgrid_detalleEgresosCajaChica").show();
				}
				
		if(tipo_egreso == 2 || tipo_egreso == 3 || tipo_egreso == 4 || tipo_egreso == 5){		
				
				if(tipo_egreso == 2){
						$("#fila_catalogo_4").show();
						}
				else{
						$('#id_sucursal_destino > option[value="0"]').attr('selected', 'selected');
						$("#fila_catalogo_4").hide();
						}
				if(tipo_egreso == 3){
						$("#fila_catalogo_3").show();
						$("#fila_catalogo_6").show();
						$("#fila_catalogo_7").show();
						}
				else{
						$("#id_cuenta_por_cobrar").val("");
						$("#hcampo_3").val("");
						$("#id_usuario").val("");
						$("#fila_catalogo_3").hide();
						$("#fila_catalogo_6").hide();
						$("#fila_catalogo_7").hide();
						}
				if(tipo_egreso == 4){
						$("#fila_catalogo_6").show();
						}
				else{
						$("#id_deposito_bancario").val("");
						$("#fila_catalogo_6").hide();
						}
				if(tipo_egreso == 5){
						$("#fila_catalogo_5").show();
						}
				else{
						$("#id_pedido").val("");
						$("#fila_catalogo_5").hide();
						}
				}
		else{
				$("#fila_catalogo_3").hide();
				$("#fila_catalogo_4").hide();
				$("#fila_catalogo_5").hide();
				$("#fila_catalogo_6").hide();
				}
				
		}
function imprimeEgresoCaja(caja){
		window.open('../../code/pdf/imprimeEgresoCaja.php?cajaEgreso=' + caja, "Caja Chica", "width=1000, height=1000");		
		}		
		