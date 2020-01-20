{include file="_header.tpl" pagetitle="$contentheader"}   
<!-- <script language="javascript" src="{$rooturl}js/franquicias.js"></script>
<link href="{$rooturl}css/tabswizard.css" rel="stylesheet" type="text/css">
<link href="{$rooturl}css/jquery-ui.css" rel="stylesheet" type="text/css"> -->

 <br>
<h1>Liberaci&oacute;n de Penalizaciones por Plaza</h1> </div>

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
			var datos = "fechaIni=" + fechaIni + "&fechaFin=" + fechaFin;
			datos += "&tipo=LPXP";
			
			var campo_fecha = $('input:radio[name=campo_fecha]:checked').val();
			datos += "&campo_fecha="+campo_fecha;
			
			var folio = $("#folio").val();
			var cuenta = $("#cuenta").val();
			var clave = $("#clave").val();
			var observaciones = $("#observaciones").val();
			datos += "&folio=" + folio + "&cuenta=" + cuenta + "&clave=" + clave + "&observaciones=" + observaciones;
			
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
			var comentarioRechazo = new Array();
			var comentarioVacio = 'no';
			var valCheck = '';
			
			$('table.comisiones_pendientes input[name="idComisionPendiente[]"]').each(function() {
				valCheck = $(this).val();
				valCheck = valCheck.replace(/,/g,"-");
				if($(this).is(':checked')){
					//alert('si'+' -- '+valCheck  +' -- '+ $('#textarea-'+valCheck).val());
					comentarioRechazo.push('NA');
				} else {
					//alert('no'+' -- '+valCheck +' -- '+ $('#textarea-'+valCheck).val());
					comentarioRechazo.push($('#textarea-'+valCheck).val());
					if(! $('#textarea-'+valCheck).val() != ''){
						comentarioVacio = 'si';
					}
					
				}
				comisiones.push($(this).val());
			});
			
			if(comisiones.length > 0){
				if(comentarioVacio == 'no'){
					var comisionesJSON = JSON.stringify(comisiones);
					var comentarioRechazoJSON = JSON.stringify(comentarioRechazo);
					
					var datos="comisiones=" + comisionesJSON + "&comentariosRechazo=" + comentarioRechazoJSON + "&tipo2=penalizaciones";
					
					$.ajax({
						async:false,
						type: "POST",
						dataType: "html",
						contentType: "application/x-www-form-urlencoded",
						data: datos,
						url: "../../ajax/liberarParaFacturar.php",
						success: function(data) {
							$("#waitingplease").hide();
							alert("Las penalizaciones se aprobaron correctamente.");
							listaResultadoDeBusqueda();
						},
						timeout:50000
					});
				} else {
					alert('Favor de agragar un comentario de no aprobacion para las penalizaciones que no han sido seleccionadas.');
				}
			} else {
				$("#waitingplease").hide();
				alert('Seleccione una penalizacion.');
			}
		}
	</script>
{/literal}

<table border="0" width="70%" style="margin: auto;">
	<form name="" method="post" action="">
		<tr>
			<td colspan="4" class="campo_small" ><br>Seleccione los criterios que desee especificar y de clic al bot&oacute;n 'Buscar '.<br><br></td>
		</tr>
		
		<tr class='nom_campo'>
			<td align="center" colspan="4">
				<span style="font-size: 9pt;">Fecha Activacion</span>
				<input name="campo_fecha" type="radio" class="campos_req" id="fecha_activacion" value="fecha_activacion" checked="checked"/>
				<span style="font-size: 9pt;">Fecha Movimiento</span>
				<input name="campo_fecha" type="radio" class="campos_req" id="fecha_movimiento" value="fecha_movimiento"/>
			</td>
		</tr>
		
		<tr class='nom_campo'>
			<!--<td align="right" style="padding-right: 10px;"><p>Del</p></td>
			<td align="left">
				<input name="fecha_inicio" type="text" class="campos_req" id="fecha_inicio" size="10"  onfocus="calendario(this);" style="width: 200px;"/>
			</td>
			<td align="right" style="padding-right: 10px;"><p>&nbsp;al</p></td>
			<td align="left">
				<input name="fecha_fin" type="text" class="campos_req" id="fecha_fin" size="10"  onfocus="calendario(this);" style="width: 200px;"/>
			</td>-->
			<td align="center" colspan="4">
			<span style="font-size: 9pt;">Del</span>
			<input name="fecha_inicio" type="text" class="campos_req" id="fecha_inicio" size="10"  onfocus="calendario(this);" style="width: 200px;"/>
			<span style="font-size: 9pt;">&nbsp;al</span>
			<input name="fecha_fin" type="text" class="campos_req" id="fecha_fin" size="10"  onfocus="calendario(this);" style="width: 200px;"/>
			</td>
		</tr>
		
		<tr class='nom_campo'>
			<td align="right" style="padding-right: 10px;"><p>Folio</p></td>
			<td align="left">
				<input name="folio" type="text" class="campos_req" id="folio" style="width: 200px;"/></td>
			<td align="right" style="padding-right: 10px;"><p>Cuenta</p></td>
			<td align="left">
				<input name="cuenta" type="text" class="campos_req" id="cuenta" style="width: 200px;"/>
			</td>
		</tr>
		
		<tr class='nom_campo'>
			<td align="right" style="padding-right: 10px;"><p>Clave Cliente</p></td>
			<td align="left">
				<input name="clave" type="text" class="campos_req" id="clave" style="width: 200px;"/></td>
			<td align="right" style="padding-right: 10px;"><p>Observaciones</p></td>
			<td align="left">
				<input name="observaciones" type="text" class="campos_req" id="observaciones" style="width: 200px;"/>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="3" align="right"><br><input name="btnBuscar" type="button" class="button_search" value="Buscar&raquo;" onClick="listaResultadoDeBusqueda();" /></td>
		</tr>
	</form>
</table> 

<div id="comisionesPendientes"></div>

<table align="right"><tr><td><input class="boton" type="button" onclick="liberarComisiones();" value="Aprobar"></td></tr></table>

<script> listaResultadoDeBusqueda(); </script>

<br /><br /><br /><br />
{include file="_footer.tpl" aktUser=$username}