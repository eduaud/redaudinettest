{include file="_header.tpl" pagetitle="$contentheader"}   
<!-- <script language="javascript" src="{$rooturl}js/franquicias.js"></script>
<link href="{$rooturl}css/tabswizard.css" rel="stylesheet" type="text/css">
<link href="{$rooturl}css/jquery-ui.css" rel="stylesheet" type="text/css"> -->

 <br>
<h1>Bonos Pendientes por Prefacturar</h1> </div>

{literal}
	<script language="javascript">
		function ajaxLLenaCombos(url, datos, hijo){
			$.ajax({
				async:false,
				type: "POST",
				dataType: "html",
				contentType: "application/x-www-form-urlencoded",
				data: datos,
				url: url,
				success: function(data) {
						$("#" + hijo + " option").remove();
						$("#" + hijo).append(data);
				},
				timeout:50000
			});
		}
		
		function llenaSelect(idSelect,idSelectALLenar,caso){
			var combo = document.getElementById(idSelect);
			var selected = '';
			var tam = combo.length;
			for(var i = 0; i < tam; i++){
				if(combo.options[i].selected == true){
					if(selected == ''){
						selected += combo.options[i].value;
					} else {
						selected += ','+combo.options[i].value;
					}
				}
			}
			ajaxLLenaCombos("../../ajax/llenaDatosCombos.php", "id=" + selected + "&caso=" + caso, idSelectALLenar);
		}
		
		function listaResultadoDeBusqueda(){
			$("#waitingplease").show();
			
			var fechaIni = $("#fecha_inicio").val();
			var fechaFin = $("#fecha_fin").val();
                        var nombreProducto = $("#nombreProductoServicio").val();
                        
                        //alert("Nombre: " + nombreProducto);
			
			var datos="fechaIni=" + fechaIni + "&fechaFin=" + fechaFin + "&nombreProducto=" + nombreProducto;
			datos += "&tipo=BPF"; // BPF -> bono pendiente de facturar pantalla especial 
			
			$.ajax({
				async:false,
				type: "POST",
				dataType: "html",
				contentType: "application/x-www-form-urlencoded",
				data: datos,
				url: "../../ajax/mostrarResultadoBusqueda.php",
				success: function(data) {
					$("#comisionesPendientes").html(data);
				},
				timeout:50000
			});
			
			$("#waitingplease").hide();
		}
		
		function facturar(){
			$("#waitingplease").show();
			var seleccion = new Array();
			
			$('table.comisiones_pendientes input[name="idComisionPendiente[]"]:checked').each(function() {
				seleccion.push($(this).val());
			});
			
			if(seleccion.length > 0){
				var conf = confirm("Desea facturar los bonos?");
				if(conf){
					var seleccionJSON = JSON.stringify(seleccion);
				
					var datos="seleccion=" + seleccionJSON + "&tipo2=bonos";
					
					$.ajax({
						async:false,
						type: "POST",
						dataType: "html",
						contentType: "application/x-www-form-urlencoded",
						data: datos,
						url: "../../ajax/facturarPenalizaciones.php",
						success: function(data){
							if(data == 0){
								$("#waitingplease").hide();
								alert("Las facturas se generaron con exito.");
								location.href="../../indices/listados.php?t=YWRfZmFjdHVyYXM=";
							}else{
								$("#waitingplease").hide();
								alert(data);						
							}							
						},
						timeout:50000
					});
				}else{
					$("#waitingplease").hide();
				}
			} else {
				alert("Seleccione un bono.");
				$("#waitingplease").hide();
			}
		}
	</script>
{/literal}
<!--
<div style="z-index:5000; display:none; position:absolute; left:50px; top:0px; width:500px; height:400px;" id="waitingplease">
	<img src="../../../imagenes/general/wait.gif" border="0" style="z-index:2000; position:absolute" id="imgW1"/>
	<img src="../../../imagenes/general/back_wait.gif" border="0" style="z-index:1000; position:absolute" id="imgW2"/>
</div>
-->
<table border="0" width="60%" style="margin: auto;">
	<form name="" method="post" action="">
		<tr>
			<td colspan="5" class="campo_small" ><br>Seleccione los criterios que desee especificar y de clic al bot&oacute;n 'Buscar '.<br><br></td>
		</tr>
		<!-- 
		<tr>
			<td colspan="4" align="right" style="padding-right: 10px;"><p>Clave</p></td>
			<td><input type="text" id="clave" name="clave"></td>
		</tr>
		-->
		<!--
		<tr class='nom_campo'>
			<td align="right" style="padding-right: 10px;"><p>Plaza</p></td>
			<td align="left">
				<select name="id_plaza" class="campos_req" id="id_plaza" multiple="" style=" width: 300px; height: 130px;">
					<option value="0" selected="selected">Selecciona una opci&oacute;n</option>
					{html_options values=$arrIDPlaza output=$arrNombrePlaza selected=$id_plaza }
				</select>
			</td>
			<td rowspan="2" align="right" style="padding-right: 10px;"><p>Clientes</p></td>
			<td rowspan="2" align="left">
				<select name="id_cliente" class="campos_req" id="id_cliente" multiple="" style="width: 300px; height: 260px;">
					<option value="0" selected="selected">Selecciona una opci&oacute;n</option>
					{html_options values=$arrysIdCliente output=$arrysNombreCliente selected=$idCliente }
				</select>
			</td>
			<td>&nbsp;</td>
		</tr>
		<tr class='nom_campo'>
			<td align="right" style="padding-right: 10px;"><p>Tipo Cliente</p></td>
			<td align="left">
				<select name="id_tipo_cliente" class="campos_req" id="id_tipo_cliente" multiple="" style="width: 300px; height: 130px;" onmouseup="llenaSelect('id_tipo_cliente','id_cliente',5)">
					<option value="0" selected="selected">Selecciona una opci&oacute;n</option>
					{html_options values=$arrIDTipoCliente output=$arrNombreTipoCliente selected=$id_tipo_cliente }
				</select>
			</td>
			<td>&nbsp;</td>
		</tr>
		-->
		<tr class='nom_campo'>
			<td align="right"><p>Fecha Activaci&oacute;n</p></td>
			<td align="right">
				<input name="fecha_inicio" type="text" class="campos_req" id="fecha_inicio" size="10"  onfocus="calendario(this);" style="width: 150px;"/></td>
			<td align="center" style="padding: 0 10px 0 10px;"><p>al</p></td>
			<td align="left">
				<input name="fecha_fin" type="text" class="campos_req" id="fecha_fin" size="10"  onfocus="calendario(this);" style="width: 150px;"/></td>
			<td align="left"><input name="btnBuscar" type="button" class="button_search" value="Buscar&raquo;" onClick="listaResultadoDeBusqueda();" /></td>
		</tr>
                
		<tr class='nom_campo'>
                    <td  align="right"><p>Nombre Producto Servicio</p></td>
			<td colspan="3" align="right">
                            <input name="nombreProductoServicio" type="text" class="campos_req" id="nombreProductoServicio" size="65" style="margin-right: 6%;"/></td>	
		</tr>
                
	</form>
</table> 

<div id="comisionesPendientes"></div>

<table align="right"><tr><td><input class="boton" type="button" onclick="facturar();" value="Prefacturar Bonos"></td></tr></table>

<script> listaResultadoDeBusqueda(); </script>

<br /><br /><br /><br />
{include file="_footer.tpl" aktUser=$username}