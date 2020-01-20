{if $idLayout eq '5' or $idLayout eq '20'}
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <link href="{$rooturl}css/estilos.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{$rooturl}css/topmenu_style.css" type="text/css" media="screen" />
    <link rel="stylesheet" type="text/css" href="{$rooturl}css/gridSW.css"/>
	<script src='{$rooturl}js/funcionesGenerales.js' type="text/javascript" language="javascript"></script><script src='{$rooturl}js/jquery.min.js' type="text/javascript" language="javascript"></script>
{else}
		{include file="_header.tpl" pagetitle="$contentheader"}
		<script src='{$rooturl}js/multicarga/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
{/if}
	<script>
	{literal}
	
	function validarFile(f){
		$("#waitingplease").show();
		
		var layout = f.layout.value;
		
		if(layout === '4'){
			//var archivos= f.archivo;
			
			var archivos= $('.multi');
			/*console.log(archivos.length);
			console.log(archivos[0].value);
			console.log(archivos[1].value);
			console.log(archivos[0].id);
			console.log(archivos[1].id);*/
			
			var cantidad = archivos.length;
			if(cantidad > 2){
				var archivo1 = archivos[0].value;
				var pos1 = archivo1.lastIndexOf('.');
				var extension1 = archivo1.substring(pos1+1);
				
				var archivo2 = archivos[1].value;
				var pos2 = archivo2.lastIndexOf('.');
				var extension2 = archivo2.substring(pos2+1);
				
				if(archivo1 != '' && archivo2 != ''){
					var file = 'bien';
				} else {
					var file = '';
				}
				
				if((extension1 === 'csv' || extension1 === 'CSV') && (extension2 === 'csv' || extension2 === 'CSV')){
					extension = 'csv';
				} else {
					extension = '';
				}
			} else {
				var file = '';
			}
		} else {
			var file = f.archivo.value;
			var pos = file.lastIndexOf('.');
			var extension = file.substring(pos+1);
		}
		
					
		if (file!=='' && layout !== '0') {
			if (extension === 'csv' || extension === 'CSV') {
				if(layout === '6'){
					var idCajaComisiones = f.cajaComisiones.value;
					if(idCajaComisiones !== '0'){
					var datos='tabla=cl_importacion_caja_comisiones&campo=id_caja_comisiones&dato='+idCajaComisiones;
						var existeCaja = ajaxR('../ajax/validaValorEnCampoTabla.php?'+datos);
						if(existeCaja == 'si'){
							var conf = confirm("La caja de comision seleccionada ha sido registrada anteriormente desea remplazarla.");
							if(conf == true){
								document.getElementById('actualizaImporCajaComisiones').value='si';
								retVal = true;
							} else {
								retVal = false; $("#waitingplease").hide();
							}
						} else {
							retVal = true;
						}
					} else {
						alert('Seleccione una caja de comisiones.');	
						retVal = false; $("#waitingplease").hide();
					}
				} else if(layout == '17'){
					var files = $("#archivo").get(0).files[0];
					var data = new FormData();
					data.append("file",files);
					var url='../ajax/validaValorEnCampoTabla.php?tabla=cl_importacion_remesas&campo=t46&posicion=0';
					var resultadoR='';
					$.ajax({
							async:false,
							type: "POST",
							url:url,
							contentType: false,
							processData: false,
							data: data,
							success: function(resultado){
								resultadoR=resultado;
							},
							timeout:50000
					});
					if(resultadoR!=''){
						var conf = confirm("Las Remesas "+resultadoR+" ya existen desea reemplazarlas?");
						if(conf == true){
							$("#IDRemesasRepetidas").val(resultadoR);
							retVal = true;
						} else {
							retVal = false; $("#waitingplease").hide();
						}
					
					} else{
						retVal = true; $("#waitingplease").hide();
					}
				}else if (layout === '18') {
					if($("#AniosCuotas").val()=='0'){
						alert('Seleccione un Año');
						$("#waitingplease").hide();
						retVal = false;
					}
					else{
						$("#waitingplease").hide();
						retVal = true;
					}
					
				} else if(layout == '4'){
					var datos='posiciones1=4&posiciones2=0';
					
					var file1 = $("#"+archivos[0].id).get(0).files[0];
					var file2 = $("#"+archivos[1].id).get(0).files[0];
					
					var data = new FormData();
					data.append("file", file1);
					data.append("file2", file2);
					
					var url='../ajax/validaValorEnCampoTabla.php?'+datos;
					var resultadoR='';
					$.ajax({
							async:false,
							type: "POST",
							url:url,
							contentType: false,
							processData: false,
							data: data,
							success: function(resultado){
								resultadoR=resultado;
							},
							timeout:50000
					});
					if(resultadoR!=''){
						alert(resultadoR);
						retVal = false; $("#waitingplease").hide();
					} else{
						var datos2='tabla=cl_importacion_derechos_activaciones_detalle&campo=activo&dato=1&posicion=4&campoAcomparar=t46';
					
						var data2 = new FormData();
						data2.append("file", file1);
						
						var url='../ajax/validaValorEnCampoTabla.php?'+datos2;
						var resultadoR='';
						$.ajax({
								async:false,
								type: "POST",
								url:url,
								contentType: false,
								processData: false,
								data: data2,
								success: function(resultado){
									resultadoR=resultado;
								},
								timeout:50000
						});
						if(resultadoR!=''){
							var conf = confirm('Las facturas: '+resultadoR+' fueron registradas previamente,\nel sistema validara si tienen una cuenta por pagar relacionada.\n\nDesea reemplazar las facturas que no tengan una cuenta por pagar relacionada?');
							
							if(conf){
								retVal = true; $("#waitingplease").hide();
							} else {
								retVal = false; $("#waitingplease").hide();
							}
						} else{
							retVal = true; $("#waitingplease").hide();
						}
					}
				} else {

					if(layout === '5') {
						document.getElementById('actualizaNumerosDeSerie').value='si';
						retVal = true;
					} else {
						retVal = true;
					}
				}


			}else{
				alert('El formato debe ser CSV');	
				retVal = false; $("#waitingplease").hide();
			}
		}else{
			if(layout === '0'){
				alert('Seleccione un layout');
			} else {
				if(layout === '4'){
					alert('Seleccione 2 archivos');
				} else {
					alert('Seleccione un archivo');
				}
			}
			retVal = false; $("#waitingplease").hide();
		}
		
		return retVal;
	 }
	 {/literal}
	</script>
	<!--
	<div style="z-index:5000; display:none; position:absolute; left:50px; top:0px; width:500px; height:400px;" id="waitingplease">
		<img src="../../imagenes/general/wait.gif" border="0" style="z-index:2000; position:absolute" id="imgW1"/>
		<img src="../../imagenes/general/back_wait.gif" border="0" style="z-index:1000; position:absolute" id="imgW2"/>
	</div>
-->
	<div>
	<h1 class="encabezado">&nbsp;&nbsp;Importaci&oacute;n {$nombreLayout}</h1>
	<h3><p>&Uacute;nicamente archivos tipo CSV.</p></h3>
	</div>
	<form id="formulario1" name="formulario1" action="" method="post" enctype="multipart/form-data" onsubmit="return validarFile(this)" style="width: 80%; margin: auto;">
		<div align="center">
			<table>
				{if $idLayout eq '6'}
					<tr>
						<td align="center">
							<label class="nom_campo">Caja de Comisiones:</label>
							<select name="cajaComisiones" id="cajaComisiones">
								{section name="cajaComisiones" loop=$arrCajaComisiones}
									<option value="{$arrCajaComisiones[cajaComisiones].0}">{$arrCajaComisiones[cajaComisiones].1}</option>
								{/section}
							</select> <br><br>
						</td>
					</tr>
				{/if}
				{if $idLayout eq '18'}
					<tr>
						<td align="center">
							<label class="nom_campo">A&ntilde;o:</label>
							<select name="AniosCuotas" id="AniosCuotas">
								{section name="Anios" loop=$arrAnios}
									<option value="{$arrAnios[Anios].0}">{$arrAnios[Anios].1}</option>
								{/section}
							</select> <br><br>
						</td>
					</tr>
				{/if}
				<tr>
					<td align="center">
						{if $idLayout eq '4'}
							<input type="file" name="archivo[]" id="archivo" class="multi" maxlength="2" style="padding-bottom: 30px;"></input>
						{else}
							<input type="file" name="archivo" id="archivo" style="padding-bottom: 30px;"></input>
						{/if}
						<input type="hidden" name="action" value="upload">
						<input type="hidden" name="layout" value="{$idLayout}">
						<input type="hidden" name="actualizaImporCajaComisiones" id="actualizaImporCajaComisiones" value="no">
						
						{if $idLayout eq '17'}
							<input type="hidden" name="IDRemesasRepetidas" id="IDRemesasRepetidas" value="">
						{/if}
						
						<input type="hidden" name="actualizaNumerosDeSerie" id="actualizaNumerosDeSerie" value="no">
						
						
						<input type="hidden" name="cantidadAIngresar" id="cantidadAIngresar" value="{$cantidadAIngresar}">
						<input type="hidden" name="idAlmacen" value="{$idAlmacen}">
						<input type="hidden" name="idOrdenCompra" value="{$idOrdenCompra}">
						<input type="hidden" name="id_carga" id="id_carga" value="{$id_carga}">
						<input type="hidden" name="numeroRenglon" value="{$numeroRenglon}">
					</td>
					<td style="vertical-align: top;"><input type="submit" value="Subir archivo" class="boton"></input></td>
				</tr>
			</table>
		</div>
	</form>
	
	{if $informe eq 'Correcto'}
		{if $numErrorinsertPorLayout > 0}
			<table class="table_border" id="mensajes_log_error" style="margin: 0 auto; margin-top: 30px;  width: 80%;">
				{if $idLayout == 2 || $idLayout == 7}
					<caption><b>Ocurrieron los siguientes errores durante la importaci&oacute;n:</b><br><br></caption>
				{else}
					<caption><b>Se ha realizado la importaci&oacute;n pero se omitier&oacute;n las siguientes filas:</b><br><br></caption>
				{/if}
				<tr>
					<th>No.</th>
					<th>LINEA</th>
					<th>CAMPO</th>
					<th>ERROR</th>
				</tr>
				{section loop=$arrErrorinsertPorLayout name=x}
					<tr>
						<td align="center">{$smarty.section.x.iteration}</td>
						<td align="center">{$arrErrorinsertPorLayout[x][0]}</td>
						<td>{$arrErrorinsertPorLayout[x][1]}</td>
						<td>{$arrErrorinsertPorLayout[x][2]}</td>
					</tr>
				{/section}
				<tr>
					<th colspan="4">
						{if $idLayout == 2 || $idLayout == 7}
							<b>El archivo no se ha importado.</b>
						{else}
							<b>La importaci&oacute;n se realiz&oacute; correctamente.</b>
						{/if}
					</th>
				</tr>
			</table>
		{else}
			<table class="table_border" id="mensajes_log_exito" style="margin: 0 auto; margin-top: 30px; width: 80%;">
				<tr>
					<th style="font-size: 16px;"><b>La importaci&oacute;n se realiz&oacute; correctamente.</b></th>
				</tr>
			</table>
		{/if}
	{elseif $informe eq 'error'}
		<table class="table_border" id="mensajes_log_error" style="margin: 0 auto; margin-top: 30px;  width: 80%;">
			<caption><b>Ocurrieron los siguientes errores durante la importaci&oacute;n:</b><br><br></caption>
			{if $numarrErr2 > 0}
				<tr><th colspan="4" align="left">{$nombreA1}</th></tr>
			{/if}
			<tr>
				<th>No.</th>
				<th>LINEA</th>
				<th>CAMPO</th>
				<th>ERROR</th>
			</tr>
			{section loop=$arrErr1 name=x}
				<tr>
					<td align="center">{$smarty.section.x.iteration}</td>
					<td align="center">{$arrErr1[x][0]}</td>
					<td>{$arrErr1[x][1]}</td>
					<td>{$arrErr1[x][2]}</td>
				</tr>
			{/section}
			<tr>
				<th colspan="4"><b>El archivo no se ha importado.</b></th>
			</tr>
		</table>
		{if $numarrErr2 > 0}
			<table class="table_border" id="mensajes_log_error" style="margin: 0 auto; margin-top: 30px;  width: 80%;">
			<tr><th colspan="4" align="left">{$nombreA2}</th></tr>
			<tr>
				<th>No.</th>
				<th>LINEA</th>
				<th>CAMPO</th>
				<th>ERROR</th>
			</tr>
			{section loop=$arrErr2 name=x}
				<tr>
					<td align="center">{$smarty.section.x.iteration}</td>
					<td align="center">{$arrErr2[x][0]}</td>
					<td>{$arrErr2[x][1]}</td>
					<td>{$arrErr2[x][2]}</td>
				</tr>
			{/section}
			<tr>
				<th colspan="4"><b>El archivo no se ha importado.</b></th>
			</tr>
		</table>
		{/if}
	{/if}
	<br><br>
	
{if $idLayout neq 5 and $idLayout neq '20'}
	{include file="_footer.tpl"}
{elseif $idLayout eq 5}
	<script type="text/javascript">
	window.top.numeroCarga{$idOrdenCompra}{$numeroRenglon}.value = document.getElementById("id_carga").value;
	window.top.cantidadIAux{$idOrdenCompra}{$numeroRenglon}.value = document.getElementById("cantidadAIngresar").value;
	</script>
{/if}