$(document).ready(function() {
		//obtenListaPrecio();
		});


function obtenListaPrecio(){
		limpia();
		var id = $("#select-sucursal").find("option:selected").val();
		var envio_datos = "id=" + id;
		var url = "llenaCombosEtiquetas.php";
		$("#select-lista option").remove();	
		ajaxCombos(url, envio_datos, 'select-lista');
		}
		
function desabilitaSelect(check){
		if ($(check).is(":checked")) {
				$('.cuerpo-filtro select').prop('disabled', 'disabled');
				$('.cuerpo-filtro select option').removeAttr("selected");
				}
		else {
				$('.cuerpo-filtro select').prop('disabled', false);
				}
		}


function llenaCombos(combo, hijo, caso){
		var filtrosMarca = "";
		if(caso == 1){
				$("#tipo option").remove();
				$("#modelo option").remove();
				$("#marca option").remove();
				}
		if(caso == 2){
				var familia = $("#familia").val();
				var tipo = $("#tipo").val();
				$("#modelo option").remove();
				$("#marca option").remove();
				filtrosMarca = "&familia=" + familia + "&tipo=" + tipo;
				}
		if(caso == 3){
				var familia = $("#familia").val();
				var tipo = $("#tipo").val();
				var modelo = $("#modelo").val();
				filtrosMarca = "&familia=" + familia + "&tipo=" + tipo + "&modelo=" + modelo;
				}
		var idCombo = $(combo).val();
		var ruta = "llenaComboListas.php";
		var envio = "id=" + idCombo + "&caso=" + caso + filtrosMarca;
		var respuesta = ajaxCombos(ruta, envio, hijo);
		
		}
		
function buscaProduto(){
		
		var lista = $("#select-lista").find("option:selected").val();
		if(lista == 0){
				alert("Seleccione una lista de precio");
				}
		else{
				limpia();
				var familia = $("#familia").val();
				var tipo = $("#tipo").val();
				var modelo = $("#modelo").val();
				var marca = $("#marca").val();
				
				
				var ruta = "llenaTablaEtiquetas.php";
				var envio = "familia=" + familia + "&tipo=" + tipo + "&modelo=" + modelo + "&marca=" + marca + "&lista=" + lista;
				var respuesta = ajaxN(ruta, envio);
				$("#resultado-productos").append(respuesta);
				}
		}
		

function limpia(){
		$("table#resultado-productos tbody").remove();
		$("#sel-todos").prop('checked', false);
		}
function imprimeEtiquetas(){
		var valorProductos = new Array();
		var sucursal = $("#select-sucursal").find("option:selected").val();
		var lista = $("#select-lista").find("option:selected").val();
		$('table.tabla-productos input[name="productos[]"]:checked').each(function() {
				valorProductos.push($(this).val());
				});
		if(valorProductos.length == 0)
				alert("Seleccione algun producto");
		else
				window.open('../../code/pdf/imprimeEtiquetas.php?productos=' + valorProductos + "&sucursal=" + sucursal + "&lista=" + lista, "Recibo de Pago", "width=1000, height=1000");
				
		}
function seleccionaTodo(check){		
		var id = $(check).attr('id');
		var selectId = id.substring(id.indexOf('-') + 1);
		
		if ($(check).is(":checked")) {
				$(check).parent().html('Deseleccionar Todo <input type="checkbox" id="check-' + selectId + '" onclick="seleccionaTodo(this)" checked/>');
				$('#' + selectId + ' option').attr("selected", "selected");
				}
		else{
				$(check).parent().html('Seleccionar Todo <input type="checkbox" id="check-' + selectId + '" onclick="seleccionaTodo(this)"/>');
				$('#' + selectId + ' option').removeAttr("selected");
				}
		}
	
function buscaSkuProd(){
		var busqueda = $("#buscaListaSKU").val();
		if(busqueda.length >= 3){
				var lista = $("#select-lista").find("option:selected").val();
				$("table#resultado-productos tbody").remove();
				var ruta = "llenaTablaEtiquetasSKU.php";
				var envio_datos = "skuNombre=" + busqueda + "&op=" + op + "&lista=" + lista; 
				var respuesta = ajaxN(ruta, envio_datos);
				$("#resultado-productos").append(respuesta);
				}
		else{
				alert("Anota al menos tres caracteres para la busqueda");
				}
		}	
		
		
		
		
		
		