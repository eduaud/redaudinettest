{include file="_header.tpl" pagetitle="$contentheader"}
<link rel="stylesheet" type="text/css" href="{$rooturl}css/estilos_especiales.css"/>
{literal}
<script>
function buscarCuotas(){
	if($("#anio").val()!='0'&&$("#mes").val()!='0'){
		var datos='anio='+$("#anio").val()+'&mes='+$("#mes").val();
		if($("#sucursal").val()!='0')
			datos+='&sucursal='+$("#sucursal").val();

		url='buscarCuotas.php';
		$("#waitingplease").show();
		var resultado=ajaxN(url,datos);
		$("#waitingplease").hide();
		$("#Cuotas").html(resultado);
		$("#parametros").val(datos);
		$("#mes_1").val($("#mes").val());
		$("#anio_1").val($("#anio").val());
		
	}else
		alert('Se debe seleccionar un mes y/o a\u00F1o');
}
function exportarcuotas(){
	window.open('../ajax/exportarCuotasExcel.php?'+$("#parametros").val());
}
function EditarCuota(fila){
	$("#fila_"+fila+" > td").each(function(index,val){
		var input='';
		if(index==0){
			var result=ajaxN('cargaCombosSucursal.php','caso=3');
			$("#fila_"+fila+" > #col_"+index).html('<select id="plaza_'+fila+'" width="100px" class="select-input" onchange="cargaDistribuidor(this.value,'+"'"+fila+"'"+')">'+result+'</select>');
		}else if(index==3){
			input='<input type="text" id="tradicionalSD_'+fila+'" value="" class="select-input">';
			$("#fila_"+fila+" > #col_"+index).html(input);
		}else if(index==4){
			input='<input type="text" id="tradicionalHD_'+fila+'" value="" class="select-input"/>';
			$("#fila_"+fila+" > #col_"+index).html(input);
		}else if(index==5){
			input='<input type="text" id="Vetv1_'+fila+'" value="" class="select-input"/>';
			$("#fila_"+fila+" > #col_"+index).html(input);
		}else if(index==6){
			input='<input type="text" id="Vetv2_'+fila+'" value="" class="select-input"/>';
			$("#fila_"+fila+" > #col_"+index).html(input);
		}else if(index==7){
			input='<input type="text" id="total_'+fila+'" value="" class="select-input"/>';
			$("#fila_"+fila+" > #col_"+index).html(input);
		}else if(index==8){
			img='<img onmouseover="this.style.cursor='+"'"+'hand'+"'"+';this.style.cursor='+"'"+'pointer'+"'"+';" onclick="GuardarCuota('+"'"+fila+"'"+')" src="http://anddev001/sistema_base/imagenes/general/Save.png" style="cursor: pointer;" title="Guardar Registro">';
			$("#fila_"+fila+" > #col_"+index).html(img);
		}
		
	});
}
function cargaDistribuidor(id,fila){
	var result=ajaxN('cargaCombosSucursal.php','caso=4&id='+id);
	$("#fila_"+fila+" > #col_2").html('<select  class="select-input" id="distribuidor_'+fila+'" onchange="cargaClaveDistribuidor(this.value,'+"'"+fila+"'"+')">'+result+'</select>');
}
function cargaClaveDistribuidor(id,fila){
	var result=ajaxN('cargaCombosSucursal.php','caso=5&id='+id);
	$("#fila_"+fila+" > #col_1").html('<input type="text" id="claveD_'+fila+'" value="'+result+'" class="select-input" disabled/>');
}
function GuardarCuota(id){
	var plaza=$('#plaza_'+id).val();var distribuidor=$('#distribuidor_'+id).val();var clavedis=$('#claveD_'+id).val();var tradicionalsd=$('#tradicionalSD_'+id).val();var tradicionalhd=$('#tradicionalHD_'+id).val();var vetv=$('#Vetv1_'+id).val();var vetv2=$('#Vetv2_'+id).val();var total=$('#total_'+id).val();var nombrePla=$('#plaza_'+id+' option:selected').html();var Nomdistribuidor=$('#distribuidor_'+id+' option:selected').html();
	if(plaza!=0&&distribuidor!=0){
		if(isNumber(tradicionalsd)&&isNumber(tradicionalhd)&&isNumber(vetv)&&isNumber(vetv2)){
			var datos='plaza='+plaza+'&distribuidor='+distribuidor+'&clavedis='+clavedis+'&tradicionalsd='+tradicionalsd+'&tradicionalhd='+tradicionalhd+'&vetv='+vetv+'&vetv2='+vetv2+'&total='+total+'&anio='+$("#anio_1").val()+'&mes='+$("#mes_1").val()+'&disNom='+Nomdistribuidor+'&plazaNom='+nombrePla+'&id='+id;
			url='guardaCuotas.php';
			var result=ajaxN(url,datos);
			alert(result);
			buscarCuotas();
		}else{
			alert('Solo se permite ingresar n\u00fameros');
			return false;
		}
	}else if(plaza=='0')
		alert('Seleccione una plaza');
	else 
		alert('Seleccione un distribuidor');
}
function isNumber(n){
  return !isNaN(parseFloat(n)) && isFinite(n);
}
function ExportarDatosAnuales(){
	if($("#anio").val()!='0'){
		window.open('../ajax/exportarCuotasAnualesExcel.php?anio='+$("#anio").val());
	}
}
</script>
{/literal}
<input type="hidden" id="parametros"/>
<input type="hidden" id="mes_1"/>
<input type="hidden" id="anio_1"/>
<!--
<div style="z-index:5000; display:none; position:absolute; left:50px; top:0px; width:500px; height:400px;" id="waitingplease">
		<img src="../../imagenes/general/wait.gif" border="0" style="z-index:2000; position:absolute" id="imgW1"/>
		<img src="../../imagenes/general/back_wait.gif" border="0" style="z-index:1000; position:absolute" id="imgW2"/>
	</div>
-->
<div style="margin-left:40px">
	<div>
	<table>
	<tr>
	<td><label class="select-plaza">Sucursal:</label></td>
	<td>
		<select id="sucursal">
			<option value="0">--Seleccione una sucursal--</option>
			{section name='Sucursal' loop=$a_sucursales}
				<option value="{$a_sucursales[Sucursal].0}">{$a_sucursales[Sucursal].1}</option>
			{/section}
		</select>
	</td>
	</tr>
	<tr>
	<td><label class="select-plaza">A&ntilde;o:</label></td>
	<td>
		<select id="anio">
			<option value="0">--Seleccione un a&ntilde;o--</option>
			{section name='Anios' loop=$a_anios}
				<option value="{$a_anios[Anios].0}">{$a_anios[Anios].1}</option>
			{/section}
		</select>
	</td>
	<td><input type="button" value="Exportar" class="button_export"onclick="ExportarDatosAnuales()"/></td>
	</tr>
	<tr>
		<td><label class="select-plaza">Mes:</label></td>
		<td>
			<select id="mes">
				<option value="0">--Seleccione un Mes--</option>
				{section name='Meses' loop=$a_meses}
					<option value="{$a_meses[Meses].0}">{$a_meses[Meses].1}</option>
				{/section}
			</select>
		</td>
	</tr>
	<tr>
	<td></td>
	<td>
		<input type="button" value="Buscar" class="button_search" onclick="buscarCuotas();"/>
	</td>
	</tr>
		</table>
	</div>
	<div id="Cuotas">
	</div>
</div>
{include file="_footer.tpl"}