{include file="_header.tpl" pagetitle="$contentheader"}   

<br>
<h1>Facturacion de Migraciones</h1> </div>

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
			
			ajaxLLenaCombos("../ajax/llenaDatosCombos.php", "id=" + selected + "&caso=" + caso, idSelectALLenar);
		}
		
		function listaResultadoDeBusqueda(){
			$("#waitingplease").show();
			var datos = "";
			
			var fechaIni = $("#fecha_inicio").val();
			var fechaFin = $("#fecha_fin").val();
			datos += "fechaIni=" + fechaIni + "&fechaFin=" + fechaFin;
			
			var orden_de_servicio = $("#orden_de_servicio").val();
			datos += "&orden_de_servicio=" + orden_de_servicio;
			
			var remesa = $("#remesa").val();
			datos += "&remesa=" + remesa;
			
			$.ajax({
				async:false,
				type: "POST",
				dataType: "html",
				contentType: "application/x-www-form-urlencoded",
				data: datos,
				url: "../ajax/listaMigracionesParaFacturacion.php",
				success: function(data) {
					$("#migraciones").html(data);
				},
				timeout:50000
			});
			
			$("#waitingplease").hide();
		}
		
		function facturarMigraciones(){
			$("#waitingplease").show();
			var migraciones = new Array();
			var datos = "";
			
			$('table.tab_migraciones input[name="idMigracionCheck[]"]').each(function() {
				migraciones.push($(this).val());
			});
			
			var migracionesJSON = JSON.stringify(migraciones);
			
			datos = "migraciones=" + migracionesJSON;
			
			if(migraciones.length > 0){
				$.ajax({
					async:false,
					type: "POST",
					dataType: "html",
					contentType: "application/x-www-form-urlencoded",
					data: datos,
					url: "../ajax/migracionesFacturar.php",
					success: function(data) {
						if(data == "exito"){
							alert("La Facturacion se realizo exitosamente.");
							listaResultadoDeBusqueda();
						} else {
							alert(data);
						}
					},
					timeout:50000
				});
			} else {
				alert("Sin resultado de busqueda");
			}
			
			$("#waitingplease").hide();
		}
	</script>
{/literal}

<table border="0" width="75%" style="margin: auto;">
	<form name="" method="post" action="">
		<tr>
			<td colspan="5" class="campo_small" ><br>Seleccione los criterios que desee especificar y de clic al bot&oacute;n 'Buscar '.<br><br></td>
		</tr>
		<tr class='nom_campo'>
			<td align="right" style="padding-right: 10px;"><p>Fecha de Migraci&oacute;n</p></td>
			<td align="left">
				<input name="fecha_inicio" type="text" class="campos_req" id="fecha_inicio" size="10"  onfocus="calendario(this);" style="width: 180px;"/></td>
			<td align="right" style="padding-right: 10px;"><p>al</p></td>
			<td align="left">
				<input name="fecha_fin" type="text" class="campos_req" id="fecha_fin" size="10"  onfocus="calendario(this);" style="width: 180px;"/></td>
			<td align="right"><p>Orden de Servicio.</p></td>
			<td><input type="text" id="orden_de_servicio" name="orden_de_servicio" style="width: 180px;"></td>
		</tr>
		
		<tr class='nom_campo'>
			<td align="right" style="padding-right: 10px;"><p>Remesa</p></td>
			<td align="left">
				<input type="text" id="remesa" name="remesa" style="width: 180px;">
			</td>
			<td colspan="4" align="center"><br><input name="btnBuscar" type="button" class="button_search" value="Buscar&raquo;" onClick="listaResultadoDeBusqueda();" /></td>
		</tr>
	</form>
</table> 

<div id="migraciones"></div>

<script> listaResultadoDeBusqueda(); </script>

<br /><br /><br /><br />
{include file="_footer.tpl" aktUser=$username}