{include file="_header.tpl" pagetitle="$contentheader"}   
<!-- <script language="javascript" src="{$rooturl}js/franquicias.js"></script>
<link href="{$rooturl}css/tabswizard.css" rel="stylesheet" type="text/css">
<link href="{$rooturl}css/jquery-ui.css" rel="stylesheet" type="text/css"> -->

 <br>
<h1>Liberaci&oacute;n a Plazas de Penalizaci&oacute;n</h1> </div>

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
			
			var comboPlaza = document.getElementById("id_plaza");
			var plazas = '';
			for(var i = 0; i < comboPlaza.length; i++){
				if(comboPlaza.options[i].selected == true){
					if(plazas == ''){ plazas += comboPlaza.options[i].value; } 
					else { plazas += ','+comboPlaza.options[i].value; }
				}
			}
			
			var comboTipocliente = document.getElementById("id_tipo_cliente");
			var tiposCliente = '';
			for(var i = 0; i < comboTipocliente.length; i++){
				if(comboTipocliente.options[i].selected == true){
					if(tiposCliente == ''){ tiposCliente += comboTipocliente.options[i].value; } 
					else { tiposCliente += ','+comboTipocliente.options[i].value; }
				}
			}
			
			var comboClientes = document.getElementById("id_cliente");
			var clientes = '';
			for(var i = 0; i < comboClientes.length; i++){
				if(comboClientes.options[i].selected == true){
					if(clientes == ''){ clientes += comboClientes.options[i].value; } 
					else { clientes += ','+comboClientes.options[i].value; }
				}
			}
			
			var fechaIni = $("#fecha_inicio").val();
			var fechaFin = $("#fecha_fin").val();
			//var clave = $("#clave").val();
			
			var datos="plazas=" + plazas + "&tiposCliente=" + tiposCliente + "&clientes=" + clientes + "&fechaIni=" + fechaIni + "&fechaFin=" + fechaFin; //+ "&clave=" + clave;
			datos += "&tipo=LPP";
			
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
		
		function liberarComisiones(){
			$("#waitingplease").show();
			var comisiones = new Array();
			
			$('table.comisiones_pendientes input[name="idComisionPendiente[]"]:checked').each(function() {
				comisiones.push($(this).val());
			});
			
			var comisionesJSON = JSON.stringify(comisiones);
			
			if(comisiones.length > 0){
				var datos="comisiones=" + comisionesJSON + "&tipo2=penalizaciones";
				
				$.ajax({
					async:false,
					type: "POST",
					dataType: "html",
					contentType: "application/x-www-form-urlencoded",
					data: datos,
					url: "../../ajax/liberarParaFacturar.php",
					success: function(data) {
						$("#waitingplease").hide();
						alert("Las penalizaciones se liberaron correctamente");
						listaResultadoDeBusqueda();
					},
					timeout:50000
				});
			} else {
				$("#waitingplease").hide();
				alert('Seleccione una penalizacion');
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
<table border="0" width="70%" style="margin: auto;">
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
		<tr class='nom_campo'>
			<td align="right" style="padding-right: 10px;"><p>Fecha Activaci&oacute;n</p></td>
			<td align="left">
				<input name="fecha_inicio" type="text" class="campos_req" id="fecha_inicio" size="10"  onfocus="calendario(this);" style="width: 300px;"/></td>
			<td align="right" style="padding-right: 10px;"><p>al</p></td>
			<td align="left">
				<input name="fecha_fin" type="text" class="campos_req" id="fecha_fin" size="10"  onfocus="calendario(this);" style="width: 300px;"/></td>
			<td>&nbsp;</td>
		</tr>
		<tr class='nom_campo'>
			<td colspan="4">&nbsp;</td>
			<td><input name="btnBuscar" type="button" class="button_search" value="Buscar&raquo;" onClick="listaResultadoDeBusqueda();" /></td>
		</tr>
	</form>
</table> 

<div id="comisionesPendientes"></div>

<table align="right"><tr><td><input class="boton" type="button" onclick="liberarComisiones();" value="Liberar Penalizacion"></td></tr></table>

<script> listaResultadoDeBusqueda(); </script>

<br /><br /><br /><br />
{include file="_footer.tpl" aktUser=$username}