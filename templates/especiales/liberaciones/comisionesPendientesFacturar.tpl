{include file="_header.tpl" pagetitle="$contentheader"}   
<!-- <script language="javascript" src="{$rooturl}js/franquicias.js"></script>
<link href="{$rooturl}css/tabswizard.css" rel="stylesheet" type="text/css">
<link href="{$rooturl}css/jquery-ui.css" rel="stylesheet" type="text/css"> -->

 <br>
<h1>Comisiones Pendientes por Prefacturar</h1> </div>

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
			var selected = '';
			if(idSelect != ''){
				var combo = document.getElementById(idSelect);
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
			}
			ajaxLLenaCombos("../../ajax/llenaDatosCombos.php", "id=" + selected + "&caso=" + caso, idSelectALLenar);
		}
		
		function listaResultadoDeBusqueda(){
			$("#waitingplease").show();
			
			var fechaIni = $("#fecha_inicio").val();
			var fechaFin = $("#fecha_fin").val();
			
			var datos="fechaIni=" + fechaIni + "&fechaFin=" + fechaFin + "&id_accion_contrato=3" + "&tipo=CPF";
			
			$.ajax({
				async:false,
				type: "POST",
				dataType: "html",
				contentType: "application/x-www-form-urlencoded",
				data: datos,
				url: "../../ajax/listaPrefacturasComisiones.php",
				success: function(data) {
					$("#comisionesPendientes").html(data);
				},
				timeout:50000
			});
			
			$("#waitingplease").hide();
		}
		
		function facturarComisiones(){
			var filas = $("#numeroComisiones").val();
			console.log(filas);
			if(filas > 0){
				var conf = confirm("Desea facturar las comisiones.");
				$("#waitingplease").show();
				if(conf){
					var fechaIni = $("#fecha_inicio").val();
					var fechaFin = $("#fecha_fin").val();
					
					var datos="fechaIni=" + fechaIni + "&fechaFin=" + fechaFin + "&id_accion_contrato=3";
					var url = "../../ajax/facturarComisiones.php";
					var result = ajaxN(url,datos);
					$("#waitingplease").hide();
					if(result.indexOf('exito') >= 0){
						alert("Prefacturas generadas correctamente");
						location.href="../../indices/listados.php?t=YWRfZmFjdHVyYXM=&stm=";
					}else{
						alert(result);							
					}
					/*
					$.ajax({
						async:false,
						type: "POST",
						dataType: "html",
						contentType: "application/x-www-form-urlencoded",
						data: datos,
						url: "../../ajax/facturarComisiones.php",
						success: function(data){
								$("#waitingplease").hide();alert(data);
								if(data == 'exito'){
									alert("Facturas generadas correctamente");
									location.href="../../indices/listados.php?t=YWRfZmFjdHVyYXM=&stm=";
								}else{
									alert("Error al timbrar las facturas");							
								}
								//listaResultadoDeBusqueda();
						},
						timeout:50000
					});*/
				}else{
					$("#waitingplease").hide();
				}
			}else{
				alert("No existen comisiones por facturar.");
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
		
		<tr class='nom_campo'>
			<td align="right"><p>Fecha Activaci&oacute;n</p></td>
			<td align="right">
				<input name="fecha_inicio" type="text" class="campos_req" id="fecha_inicio" size="10"  onfocus="calendario(this);" style="width: 150px;"/>
			</td>
			<td align="center" style="padding: 0 10px 0 10px;"><p>al</p></td>
			<td align="left">
				<input name="fecha_fin" type="text" class="campos_req" id="fecha_fin" size="10"  onfocus="calendario(this);" style="width: 150px;"/>
			</td>
			<td align="left"><input name="btnBuscar" type="button" class="button_search" value="Buscar&raquo;" onClick="listaResultadoDeBusqueda();" /></td>
		</tr>
	</form>
</table> 

<br>
<div id="comisionesPendientes"></div>

<p style="display:block; float:right;">
	<input class="boton" type="button" onclick="facturarComisiones();" value="Prefacturar Comisiones">
</p>

<script> llenaSelect('','id_cliente',5); listaResultadoDeBusqueda(); </script>

<br /><br /><br /><br />
{include file="_footer.tpl" aktUser=$username}