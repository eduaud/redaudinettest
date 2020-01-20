<html>
		<head>
				<title>Descuentos</title>
				<style>
				{literal}
						body{
								background : #F3F3F3;
								font-family : arial;
								
								}
						.encabezado{
								margin-top : 10px;
								margin-bottom : 10px;
								border-bottom: 1px solid rgb(204, 204, 204);
								color: rgb(17, 93, 45);
								font-family: Arial,Helvetica,sans-serif;
								font-size: 26px;
								font-weight: bold;
								height: 34px;
								line-height: 34px;
								}
						.boton{
								-moz-border-bottom-colors: none;
								-moz-border-image: none;
								-moz-border-left-colors: none;
								-moz-border-right-colors: none;
								-moz-border-top-colors: none;
								background-color:#345540;
								border-radius: 6px 6px 6px 6px !important;
								color: #fff !important;
								cursor: pointer;
								outline: 0 none;
								overflow: visible;
								padding: 3px 5px;
								text-shadow: 0 1px 1px #333 !important;
								width: auto;
								font-family: Arial, Helvetica, sans-serif;
								font-size: 12px;
								line-height: 1.3;
								background-attachment: scroll;
								background-repeat: repeat;
								background-position: center top;
								border: 1px solid #4b63ae;
							}
						.texto{
								font-size : 12px;
								color : #3F3F3F;
								}
						textarea{
								font-size : 12px !important;
								color : #3F3F3F !important;
								width : 250px;
								height : 100px;
								}
						.label{
								padding : 3px;
								font-size : 13px;
								text-align : right;
								}
				{/literal}
				</style>
				<script type="text/javascript" src="../../js/jquery/jquery.js"></script>
				<script type="text/javascript">
				{literal}
						/*$(document).ready(function() {
								alert($("#referencia").val()) ;
								});*/
						function noletrasD(e){
							tecla = (document.all) ? e.keyCode : e.which; // 2
								if (tecla==8) return true; // backspace
								//if (tecla==109) return true; // menos
								if (tecla==110) return true; // punto
								
								if (tecla==37) return true; // Flecha Izquierda
								if (tecla==39) return true; // Flecha Derecha
								/*if (e.ctrlKey && tecla==86) { return true}; //Ctrl v
								if (e.ctrlKey && tecla==67) { return true}; //Ctrl c
								if (e.ctrlKey && tecla==88) { return true}; //Ctrl x*/
								if (tecla>=96 && tecla<=105) { return true;} //numpad
							 
								patron = /[0-9]/; // patron
							 
								te = String.fromCharCode(tecla);
								return patron.test(te); // prueba
								}
						function guardarDescuento(){
								var porcentaje = $("#porcentaje").val();
								var motivo = $("#motivo").val();
								var referencia = $("#referencia").val();
								var usuarioR = $("#usuarioR").val();
								var sucursal = $("#sucursal").val();
								var cliente = $("#cliente").val();
								var total = $("#total").val();
								total = total.replace(",", "");
								
								if(porcentaje == ""){
										$("#error").html("No puedes dejar el porcentaje vacio");
										}
										
								else if(motivo == ""){
										$("#error").html("No puedes dejar el motivo vacio");
										}
								else{
										var monto_descuento_s = porcentaje * total;
										var monto_descuento = monto_descuento_s / 100;
										
										var datos = "porcentaje=" + porcentaje + "&motivo=" + motivo + "&referencia=" + referencia + "&usuarioR=" + usuarioR + "&sucursal=" + sucursal + "&cliente=" + cliente + "&total=" + total + "&monto_descuento=" + monto_descuento;
										
										$.ajax({
											async:false,
											type: "POST",
											dataType: "html",
											contentType: "application/x-www-form-urlencoded",
											data: datos,
											url:'../../code/ajax/guardaDescuento.php',
											beforeSend:function(){
													$("#error").html("<img src='../../imagenes/cargando.gif'/>");	
													},
											success: function(data) {
													if(data == "exito"){
															
															parent.$("#fila_catalogo_12").show();
															parent.$("#fila_catalogo_14").show();
															parent.$("#descuento_solicitado").val(porcentaje);
															parent.$("#monto_descuento").val(monto_descuento);
															parent.$("#id_estatus_pedido option").remove();
															parent.$('#id_estatus_pedido').append('<option value="7">PENDIENTE APROBACION DESCUENTO</option>');
															
															
															parent.$.fancybox.close();
															
															}
													},
											timeout:50000
											});
										
										
										}
								}

								
				{/literal}
				</script>
		</head>
<body>

		<h1 class="encabezado">Solicitud de Descuento</h1>
		<br>
		<form>
				<table>
						<tr>
								<td class="label">Porcentaje:</td><td><input type="text" name="porcentaje" id="porcentaje" style="width:70px;" class="texto" onkeydown="return noletrasD(event);"/> %</td>
						</tr>
						<tr>
								<td valign="top" class="label">Motivo:</td><td><textarea name="motivo" id="motivo" class="texto"></textarea></td>
						</tr>
						<tr>
								<td colspan="2" style="text-align:right"><input type="button" name="guardar" id="guardar" value="Guardar &raquo;" class="boton" onClick="guardarDescuento()"/></td>
						</tr>
						<tr>
								<td colspan="2" style="text-align:center; font-size:10px; color:#f00" id="error"></td>
						</tr>
						<tr>
								<td colspan="2">
										<input type="hidden" name="referencia" id="referencia" class="texto" value="{$referencia}"/>
										<input type="hidden" name="usuarioR" id="usuarioR" class="texto" value="{$usuarioR}"/>
										<input type="hidden" name="sucursal" id="sucursal" class="texto" value="{$sucursal}"/>
										<input type="hidden" name="cliente" id="cliente" class="texto" value="{$cliente}"/>
										<input type="hidden" name="total" id="total" class="texto" value="{$total}"/>
								</td>
						</tr>
				</table>
		</form>
		
</body>
</html>