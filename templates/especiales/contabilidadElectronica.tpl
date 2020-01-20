{include file="_header.tpl" pagetitle="$contentheader"}
<script>
	{literal}
	function mostrarDiv(opcion){
		$("#EnviarXML").css('display','inline');
		if(opcion=='1'){
			$("#TenvioB").css('display','none');
			$("#TenvioSol").css('display','none');
			$("#Generar").val('Generar XML Cuentas');
			$("#option").val('1');
		}else if(opcion=='2'){
			$("#TenvioB").css('display','inline');
			$("#TenvioSol").css('display','none');
			$("#Generar").val('Generar XML Balanza');
			$("#option").val('2');
		}else if(opcion=='3'){
			$("#TenvioB").css('display','none');
			$("#TenvioSol").css('display','inline');
			$("#Generar").val('Generar XML P\u00F3lizas');
			$("#option").val('3');
		}else if(opcion=='4'){
			$("#TenvioB").css('display','none');
			$("#TenvioSol").css('display','none');
			$("#Generar").val('Generar XML Folios');
			$("#option").val('4');
		}else if(opcion=='5'){
			$("#TenvioB").css('display','none');
			$("#TenvioSol").css('display','none');
			$("#Generar").val('Generar XML Cuentas y/o Subcuentas');
			$("#option").val('5');
		}else if(opcion=='6'){
			$("#TenvioB").css('display','none');
			$("#TenvioSol").css('display','none');
			$("#Generar").val('Generar XML Sello Digital');
			$("#option").val('6');
		}
			
	}
function generarXML(option){
	var anio=$("#anio").val();
	var mes=$("#mes").val();
	if(anio!=0&&mes!=0){
		var datos='opcion='+option+'&anio='+$("#anio").val()+'&mes='+$("#mes").val();
		if(option=='2')
			datos+='&tipoBalanza='+$("#tipoBalanza").val();
		if(option=='3'){
			if($("#tipoSolicitudP").val()!='0')
				datos+='&tipoSolicitud='+$("#tipoSolicitudP").val();
			else{
				alert('Seleccione una tipo de solicitud de p\u00F3liza');
				return false;
			}
		}
		window.open('../../code/especiales/generarXMLsFE.php?'+datos);
	}
	else{
		alert("El mes y/o a\u00f1o seleccionado es incorrecto");
		return false;
	}
}
	{/literal}
</script>
<div class="iconos-home">
	<div class="holder">
		<a href="#" onclick="mostrarDiv('1');">Generar XML de Catalogo de Cuentas</a>
	</div>
	<div class="holder">
		<a href="#" onclick="mostrarDiv('2');">Generar XML de Balanza de Comprobaci&oacute;n</a>
	</div>
	<div class="holder">
		<a href="#" onclick="mostrarDiv('3');">Generar XML de P&oacute;lizas del Periodo</a>
	</div>
	<div class="holder">
		<a href="#" onclick="mostrarDiv('4');">Generar XML del Formato auxiliar de folios</a>
	</div>
	<div class="holder">
		<a href="#" onclick="mostrarDiv('5');">Generar XML del Formato auxiliar de cuentas y/o subcuentas</a>
	</div>
	<div class="holder">
		<a href="#" onclick="mostrarDiv('6');">Generar XML del Formato SelloDigitalContElec</a>
	</div>
</div>
<div style="display:none" id="EnviarXML">
	<table>
		<tr>
			<td>A&ntilde;o al que corresponde la informaci&oacute;n a enviar</label>
			</td>
			<td>
				<select id="anio">
					<option value="0">--Seleccione un a&ntilde;o--</option>
					{section name="Anio" loop=$a_anios}
						<option value="{$a_anios[Anio].id_anio}">{$a_anios[Anio].nombre}</option>
					{/section}
				<select>
			</td>
		</tr>
		<tr>
			<td>Periodo al que corresponde la informaci&oacute;n a enviar</td>
			<td>
				<select id="mes">
					<option value="0">--Seleccione un mes--</option>
					{section name="Mes" loop=$a_meses}
						<option value="{$a_meses[Mes].id_mes}">{$a_meses[Mes].nombre}</option>
					{/section}
				<select>
			</td>
		</tr>
		<tr id="TenvioB">
			<td>Tipo de Envio de la Balanza</td>
			<td>
				<select id="tipoBalanza">
					<option value="0">--Seleccione un tipo--</option>
					<option value="N">Normal</option>
					<option value="C">Complementaria</option>
				<select>
			</td>
		</tr>
		<tr id="TenvioSol">
			<td>Tipo de Solicitud de P&oacute;liza</td>
			<td>
				<select id="tipoSolicitudP">
					<option value="0">--Seleccione un tipo--</option>
					<option value="AF">AF (Acto de Fiscalizaci&oacute;n)</option>
					<option value="FC">FC (Fiscalizaci&oacute;n Compulsa)</option>
					<option value="DE">DE (Devoluci&oacute;n)</option><option value="CO">CO (Compensaci&oacute;n)</option>
				<select>
			</td>
		</tr>
		<tr>
			<td></td>
			<td>
				<input type="button" value="" id="Generar" onclick="generarXML($('#option').val());"/><input type="hidden" id="option"/>
			</td>
		</tr>
	</table>
</div>
{include file="_footer.tpl"}