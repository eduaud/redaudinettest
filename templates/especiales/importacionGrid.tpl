<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="{$rooturl}css/estilos.css" rel="stylesheet" type="text/css" />
<link href="{$rooturl}css/estilos_especiales.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{$rooturl}css/topmenu_style.css" type="text/css" media="screen" />
<link rel="stylesheet" type="text/css" href="{$rooturl}css/gridSW.css"/>
<script type="text/javascript" src="{$rooturl}js/jquery/jquery.js"></script>
<SCRIPT Language=Javascript SRC="{$rooturl}js/funciones_especiales.js"></SCRIPT>


<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="{$rooturl}css/estilos.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{$rooturl}css/topmenu_style.css" type="text/css" media="screen" />
<link rel="stylesheet" href="{$rooturl}css/estilos.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="{$rooturl}css/gridSW.css"/>
<script src='{$rooturl}js/funcionesGenerales.js' type="text/javascript" language="javascript"></script><script src='{$rooturl}js/jquery.min.js' type="text/javascript" language="javascript"></script>
<script>
{literal}

function validarFile(){
	cargaWait();
	$("#numeros_serie").val('');
	$("#waitingplease").show();
	var files = $("#archivo").get(0).files[0];
	var data = new FormData();
	data.append("file",files);
	var url='../ajax/ImportacionGrid.php?caso=1';
	var resultadoR='';
	$.ajax({
			async:false,
			type: "POST",
			url:url,
			contentType: false,
			processData: false,
			data: data,
			success: function(resultado){
				$("#lista_datos").html(resultado);
				$("#waitingplease").hide();
			},
			timeout:50000
	});
 }
function GuardaNumerosDeSerie(){
	var texto=$("#numeros_serie").val();
	{/literal}
	parent.document.getElementById("detalleMovimientosAlmacen_26_{$numeroRenglon}").setAttribute("valor",texto);
	parent.$.fancybox.close();
	{literal}
}
 {/literal}
</script>

<div style="z-index:5000; display:none; position:absolute; left:50px; top:0px; width:500px; height:400px;" id="waitingplease">
	<img src="../../imagenes/general/wait.gif" border="0" style="z-index:2000; position:absolute" id="imgW1"/>
	<img src="../../imagenes/general/back_wait.gif" border="0" style="z-index:1000; position:absolute" id="imgW2"/>
</div>

<div>
<h1 class="encabezado">&nbsp;&nbsp;Importaci&oacute;n N&uacute;meros de Serie</h1>
<h3><p>&Uacute;nicamente archivos tipo CSV.</p></h3>
</div>
<input type="hidden" id="numeros_serie"/>
<div align="center">
	<table id="tabla_captura" width="500" border="0" name="tabla_captura">
		<tr>
			<td align="center">
				<input type="file" name="archivo" id="archivo" style="padding-bottom: 30px;"/>
			</td>
			<td style="vertical-align: top;">
				<input type="button" value="Subir archivo" class="boton" onclick="validarFile()"/>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div id="lista_datos" class="lista_datos" name="lista_datos">
		
				</div>
			</td>
		</tr>
		<tr>
			<td><br />
				<input name="BtnGuardaIRDS" type="button" id="BtnGuardaIRDS" value="Guardar IRDS" onclick="GuardaNumerosDeSerie()"/>
				<td align="right">
					  Importados:&nbsp;
				  <input type="text" name="TxtContador" id="TxtContador" value="0" class="texto_numeros_serie" size="2" readonly="true"/>
			</td>
		</tr>
	</table>
</div>
<script>
{if $irds neq ''}
	var irds='{$irds}';
	var seriesIRDS=irds.split(',');{literal}
	$("#numeros_serie").val(irds);
	var cadena="";
	for(i=0;i<seriesIRDS.length;i++){
		cadena = cadena + '<div class="div_numero_serie" id="div_numero_serie'+i+'">';
			cadena = cadena + '<span class="texto_numeros_serie" name="TxtValorLista'+i+'" id="TxtValorLista'+i+'" align="center">'+seriesIRDS[i].toUpperCase()+'</span>&nbsp;';
			cadena = cadena + '</div>';
			$('#lista_datos').append(cadena); 
			document.getElementById('TxtContador').value = i+1;
		cadena="";
	}{/literal}
{/if}
</script>