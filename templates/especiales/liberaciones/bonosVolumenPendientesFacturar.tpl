{include file="_header.tpl" pagetitle="$contentheader"}   
<!-- <script language="javascript" src="{$rooturl}js/franquicias.js"></script>
<link href="{$rooturl}css/tabswizard.css" rel="stylesheet" type="text/css">
<link href="{$rooturl}css/jquery-ui.css" rel="stylesheet" type="text/css"> -->

 <br>
<h1>Bonos por Volumen Pendientes por Facturar</h1> </div>

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
			
			var comboBrakets = document.getElementById("id_braket");
			var brakets = '';
			for(var i = 0; i < comboBrakets.length; i++){
				if(comboBrakets.options[i].selected == true){
					if(brakets == ''){ brakets += comboBrakets.options[i].value; } 
					else { brakets += ','+comboBrakets.options[i].value; }
				}
			}
			
			var datos = "brakets=" + brakets;
			
			$.ajax({
				async:false,
				type: "POST",
				dataType: "html",
				contentType: "application/x-www-form-urlencoded",
				data: datos,
				url: "../../ajax/mostrarResultadoBusquedaBonosVolumen.php",
				success: function(data) {
					$("#resultadoBusqueda").html(data);
				},
				timeout:50000
			});
			
			$("#waitingplease").hide();
		}
		
		function facturar(){
			$("#waitingplease").show();
			var seleccion = new Array();
			
			$('table.bonos_volumen input[name="idBonoVolumen[]"]:checked').each(function() {
				seleccion.push($(this).val());
			});
			
			if(seleccion.length > 0){
				var conf = confirm("Desea facturar los bonos?");
				if(conf){
					var seleccionJSON = JSON.stringify(seleccion);
				
					var datos="seleccion=" + seleccionJSON;
					
					$.ajax({
						async:false,
						type: "POST",
						dataType: "html",
						contentType: "application/x-www-form-urlencoded",
						data: datos,
						url: "../../ajax/facturarBonosVolumen.php",
						success: function(data){
							var letras = ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','ñ','o','p','q','r','s','t','u','v','w','x','y','z','0','1','2','3','4','5','6','7','8','9'];
							var tieneTexto = 'no';
							for(var i = 0; i < data.length; i++){
								if(letras.indexOf(data.charAt(i)) >= 0){
									tieneTexto = 'si';
									i = data.length + 2;
								}
							}
							
							if(tieneTexto == 'si'){
								alert(data);
								$("#waitingplease").hide();
							} else {
								alert("Las facturas se generaron con exito.");
								location.href="../../indices/listados.php?t=YWRfZmFjdHVyYXM=";
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
<table border="0" width="70%" style="margin: auto;">
	<form name="" method="post" action="">
		<tr>
			<td colspan="3" class="campo_small" ><br>Seleccione los criterios que desee especificar y de clic al bot&oacute;n 'Buscar '.<br><br></td>
		</tr>
		<!--
		<tr class='nom_campo'>
			<td align="right" style="padding-right: 10px;"><p>Fecha Activaci&oacute;n</p></td>
			<td align="left">
				<input name="fecha_inicio" type="text" class="campos_req" id="fecha_inicio" size="10"  onfocus="calendario(this);" style="width: 300px;"/></td>
			<td align="right" style="padding-right: 10px;"><p>al</p></td>
			<td align="left">
				<input name="fecha_fin" type="text" class="campos_req" id="fecha_fin" size="10"  onfocus="calendario(this);" style="width: 300px;"/></td>
			<td>&nbsp;</td>
		</tr>
		-->
		
		<tr class='nom_campo'>
			<td align="right" style="padding-right: 10px;" width="30%"><p>Periodo</p></td>
			<td align="left" width="30%">
				<select name="id_braket" class="campos_req" id="id_braket" multiple="" style="width: 300px; height: 260px;">
					<option value="0" selected="selected">Selecciona una opci&oacute;n</option>
					{html_options values=$arryIdsBraket output=$arryNombresBraket selected=$idBraket}
				</select>
			</td>
			<td align="left" width="30%"><input name="btnBuscar" type="button" class="button_search" value="Buscar&raquo;" onClick="listaResultadoDeBusqueda();" /></td>
		</tr>
	</form>
</table> 

<div id="resultadoBusqueda"></div>
<br>
<table align="right"><tr><td><input class="boton" type="button" onclick="facturar();" value="Facturar Bonos por Volumen"></td></tr></table>

<script> listaResultadoDeBusqueda(); </script>

<br /><br /><br /><br />
{include file="_footer.tpl" aktUser=$username}