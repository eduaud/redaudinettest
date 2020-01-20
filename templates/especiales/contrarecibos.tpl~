{include file="_header.tpl" pagetitle="$contentheader"}
<link rel="stylesheet" type="text/css" href="{$rooturl}css/estilos_especiales.css"/>
{literal}
<script type="text/javascript">
function eliminarFilaContrarecibo(fila){
	$("#fila_"+fila).remove();
}
function agregaContrarecibo(id){
	var url='../../code/ajax/especiales/agregaContrarecibos.php';
	var  datos='id_detalle='+id;
	var resultado=ajaxN(url,datos);
	$("#cuentas").append(resultado);
	VerificaContratos(id);
}
function BuscarContratos(contrato){
	
	if($("#folio").val()!=''){
		$("#waitingplease").show();
		var contratos=new Array();
		$("#cuentas >tr").each(function(index,value){
			contratos.push($(this).find(".contrato").html());
			$("#contrato_"+contrato).remove();
		});
		var url='../../code/ajax/especiales/buscarContratos.php';
		var  datos='contrato='+contrato;
		if(contratos.length>0)
			datos+='&control='+JSON.stringify(contratos);
		
		var resultado=ajaxN(url,datos);
		$("#cuentasContrarecibos").html(resultado);
		$("#folio").val('');
		
		$("#waitingplease").hide();
	}
	else
		alert('Ingrese un Contrato');
}
function GuardarContrarecibos(){
	var Contratos=new Array();
	var Sucursales=new Array();
	var Clientes=new Array();
	$('#controlCuentas tr').each(function(index,value) {
		var contrato = $(this).find(".contrato").html();   
		var sucursal = $(this).find(".sucursal").html();   
		var cliente = $(this).find(".cliente").html();   
		Contratos.push(index);
		Contratos[index]=new Array();
		Contratos[index].push(contrato);
		Contratos[index].push(cliente);
		Contratos[index].push(sucursal);
		
		Sucursales.push(sucursal);
		Clientes.push(cliente);
	});
	var contratosJSON = JSON.stringify(Contratos);
	var sucursalesJSON = JSON.stringify(Sucursales);
	var clientesJSON = JSON.stringify(Clientes);
	if(Contratos.length > 0){
				$("#waitingplease").show();
				var datos="contratos=" + contratosJSON+"&sucursales="+sucursalesJSON+"&clientes="+clientesJSON+"&usuario="+$("#usuario").val();
				$.ajax({
					async:false,
					type: "POST",
					dataType: "html",
					contentType: "application/x-www-form-urlencoded",
					data: datos,
					url: "../../code/ajax/especiales/guardarContrarecibo.php",
					success: function(data) {
						alert(data);
						$("#waitingplease").show();
						location.reload();
					},
					timeout:50000
				});
			} else {
				alert('Se debe agregar al menos un contrato para generar el contrarecibo');
			}

}
function RechazaContrarecibos(idContrato){
	var confirma=confirm("Esta seguro de querer rechazar este contrato?");
	if(confirma==true){
		if($("#motivo_rechazo_"+idContrato).val()!=''){
		var url='../../code/ajax/especiales/rechazaContrarecibos.php';
		var datos='contrato='+idContrato+'&motivo='+$("#motivo_rechazo_"+idContrato).val();
			var resultado=ajaxN(url,datos);
			alert('Contrato Rechazado');
			location.reload();
		}
		else{
			$("#motivo_rechazo_"+idContrato).css('border','1px solid red');
		}
	}
	else
		return false
}
function VerificaContratos(id_contrato){
	var cuentas=$("#cuentas >tr");
	cuentas.each(function(index,value){
		var contrato = $(this).find(".contrato").html();
		$("#contrato_"+contrato).remove();
	});
	var cuentas=$("#cuentasContrarecibos >tr");
	if(cuentas.length==0){
		$("#cuentasContrarecibos").append('<td>Ingrese un contrato para agregarlo a un contra recibo.</td>');
	}
		
}
{/literal}
</script>
<!--
<div style="z-index:5000; display:none; position:absolute; left:50px; top:0px; width:500px; height:400px;" id="waitingplease">
	<img src="../../imagenes/general/wait.gif" border="0" style="z-index:2000; position:absolute" id="imgW1"/>
	<img src="../../imagenes/general/back_wait.gif" border="0" style="z-index:1000; position:absolute" id="imgW2"/>
</div>
-->
<h1 class="encabezado">Contra Recibos</h1>
<div id="busquedas">
	<div id="provedores" style="float:left; padding-left:25px;">
		<h4>Buscar Contrato:</h4>
		<label for="select-proveedor">Contrato:</label>
		<input type="text" id="folio"/>
		<p style="display:block; float:right;"><input type="button" class="boton" value="Buscar &raquo;" onclick="BuscarContratos($('#folio').val())"/></p>
	</div>
</div>
<div style="padding-left:25px; width:700px; float:left;">
	<h4>Contratos Encontrados</h4>
	<table>
		<thead class="encabezadoarqueo">
			<tr>
				<th style="width:150px;">Contrato</th>
				<th style="width:80px;">Cuenta</th>
				<th style="width:80px;">Distribuidor</th>
				<th style="width:100px;">Fecha Activaci&oacute;n</th>
				<th style="width:100px;">Tipo Activaci&oacute;n</th>
				<th style="width:100px;">Estatus</th>
				<th style="width:220px;">Acciones</th>
			</tr>
		</thead>
	</table>
	<div id="scroll-tabla" style="height:100px;">
		<table> 
			<tbody id="cuentasContrarecibos" class="detalleArqueo">
				<td>Ingrese un contrato para agregarlo a un contra recibo.</td>
			</tbody>
		</table>
	</div>
</div>
<div style="float:left; padding-left:25px;">
	<h4>Contratos que se anexaran para generar contra recibos</h4>
	<table>
		<thead class="encabezadoarqueo">
			<tr id="cabeceras">
					<th style="width:150px;">Contrato</th>
					<th style="width:80px;">Cuenta</th>
					<th style="width:80px;">Distribuidor</th>
					<th style="width:100px;">Fecha Activaci&oacute;n</th>
					<th style="width:100px;">Tipo Activaci&oacute;n</th>
					<th style="width:100px;">Estatus</th>
					<th style="width:100px;">Acciones</th>
			</tr>
		</thead>
	</table>
	<table id="controlCuentas">
		<tbody id="cuentas" class="detalleArqueo"></tbody>
	</table>
	<label for="select-proveedor">Persona que entreg&oacute; contratos:</label>
	<input type="text" name="usuario" id="usuario" style="width:250px;"/><br>
	<input type="button" name="guardarContrarecibo" id="guardarContrarecibo" value="Guardar Contra Recibo" onclick="GuardarContrarecibos();"/><br><br>
</div>
{include file="_footer.tpl"}