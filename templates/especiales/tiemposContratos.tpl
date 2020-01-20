{include file="_header.tpl" pagetitle="$contentheader"}
{literal}
<style>
	.circulo {
		width: 20px;
		height: 20px;
		-moz-border-radius: 50%;
		-webkit-border-radius: 50%;
		border-radius: 50%;
	}
</style>
<script type="text/javascript">
function BuscarContratosTiempos(){
	var datos='';
	
	if($("#folio").val()!=''){
		$("#folio_1").val($("#folio").val());
		if(datos!='')
			datos+='&folio='+$("#folio").val();
		else
		datos+='folio='+$("#folio").val();
	}
	if($("#cuenta").val()!=''){
		$("#cuenta_1").val($("#cuenta").val());
		if(datos!='')
			datos+='&cuenta='+$("#cuenta").val();
		else 
			datos+='cuenta='+$("#cuenta").val();
	}
	for(i=0;i<7;i++){
		datos = datos+'&body='+i;
		var url='../../code/ajax/especiales/buscarTiemposContratos.php';
		var resultado = ajaxN(url,datos);
		$("#tbody_"+i).html(resultado);
	}
	$("#filtro").val(datos);
	$("#folio").val('');
	$("#cuenta").val('');
}
function ExportarContratosExcel(){
	var folio = $("#folio_1").val();
	var cuenta = $("#cuenta_1").val();
	var datos = "";
	//if(folio != '' || cuenta != ''){
		if(folio != ''&& cuenta =='')
			datos += "folio="+folio;
		else if(folio != ''&& cuenta !='')
			datos += "folio="+folio+"&cuenta="+cuenta;
		else if(folio != ''&& cuenta !='')
			datos += "cuenta="+cuenta;
		window.open('../../code/ajax/contratos_excel.php?'+datos);	
	/*}
	else
		alert("Ingrese el folio y/o cuenta");*/
}
</script>
{/literal}
<link rel="stylesheet" type="text/css" href="{$rooturl}css/estilos_especiales.css"/>
<h1 class="encabezado">Tiempos Contratos</h1>
<div id="busquedas">
	<div id="provedores" style="float:block; padding-left:25px;">
	<input type="hidden" id="filtro"/>
	<input type="hidden" id="folio_1"/>
	<input type="hidden" id="cuenta_1"/>
	<h4>Buscar Contrato:</h4>
		<table>
			<tr>
				<!--<td class="nom_campos"><p>Distribuidor:</p></td>
				<td>
					<select id="distribuidor">
						{section name="Distribuidor" loop=$a_Distribuidores}
							<option value="{$a_Distribuidores[Distribuidor].id_cliente}">{$a_Distribuidores[Distribuidor].cliente}</option>
						{/section}
					</select>
				</td>-->
				<td class="nom_campos"><p>Folio/Contrato:</p></td>
				<td>
					<input type="text" id="folio" style="margin-left:5px;"/>
				</td>
				<td class="nom_campos"><p>&nbsp;Cuenta:</p></td>
				<td>
					<input type="text" id="cuenta"/>
				</td>
				<td>
					&nbsp;<input type="button" colspan="2" align="center" class="boton" value="Buscar &raquo;" onclick="BuscarContratosTiempos();"/>
				</td>				
			</tr>	
		</table>
	</div>
</div>
<div style="margin-left:20px;">
	<br/><input type="button" value="Exportar Datos a Excel" onclick="ExportarContratosExcel();"/>
</div>
<div width="100%">
	<h4>CONTRATOS PENDIENTES DE ENTREGA</h4>
	<table width="80%">
		<thead class="encabezados_contratos">
			<tr>
				<th style="width:3.3%;">No</th>
				<th style="width:8.8%;">Contrato</th>
				<th style="width:8.8%;">Cuenta</th>
				<th style="width:17.3%;">Distribuidor</th>
				<th style="width:8.8%;">Fecha Activaci&oacute;n</th>
				<th style="width:8.8%;">Comisi&oacute;n Posible(INCLUYE IVA)</th>
				<th style="width:8.8%;">Fecha de vencimiento</th>
				<th style="width:7.8%;">Dias transcurridos</th>
				<th style="width:6.3%;">Color</th>
				<th style="width:1.5%;"></th>
			</tr>
		</thead>
	</table>
	<div id="scroll-tabla" style="height:200px;width:80%">
			<table width="100%">
				<tbody id="tbody_0" class="detalle_contrato">
				</tbody>
			</table>
	</div>	
		
</div>
<div width="100%">
	<h4>CONTRATOS ENTREGADOS EN PLAZA</h4>
	<table class="encabezados" width="80%">
		<thead>
			<tr>
				<th style="width:3.3%;">No</th>
				<th style="width:8.8%;">Contrato</th>
				<th style="width:8.8%;">Cuenta</th>
				<th style="width:17.3%;">Distribuidor</th>
				<th style="width:8.8%;">Fecha Activaci&oacute;n</th>
				<th style="width:8.8%;">Comisi&oacute;n Posible(INCLUYE IVA)</th>
				<th style="width:8.8%;">Fecha de entrega a plaza</th>
				<th style="width:6.3%;">Contra Recibo</th>
				<th style="width:7.8%;">Color</th>
				<th style="width:1.5%;"></th>
				
			</tr>
		</thead>
	</table>
	<div id="scroll-tabla" style="height:200px;width:80%">
		<table width="100%">
			<tbody id="tbody_1" class="detalle_contrato">
			</tbody>
		</table>
	</div>
</div>
<div>
	<h4>CONTRATOS RECHAZADOS EN PLAZA</h4>
	<table class="encabezados" width="96%">
		<thead>
			<tr>
				<th style="width:3.3%;">No</th>
				<th style="width:8.8%;">Contrato</th>
				<th style="width:8.8%;">Cuenta</th>
				<th style="width:17.3%;">Distribuidor</th>
				<th style="width:8.8%;">Fecha Activaci&oacute;n</th>
				<th style="width:8.8%;">Comisi&oacute;n Posible(INCLUYE IVA)</th>
				<th style="width:8.8%;">Fecha de rechazo</th>
				<th style="width:6.3%;">Fecha de vencimiento</th>
				<th style="width:7.8%;">Dias transcurridos</th>
				<th style="width:7.8%;">Motivo de rechazo</th>
				<th style="width:7.8%;">Color</th>
				<th style="width:3%;"></th>
			</tr>
		</thead>
	</table>
	<div id="scroll-tabla" style="height:200px;width:95.8%">
		<table border="0" class="detalle_contrato" width="100%">
			<tbody id="tbody_2">
			</tbody>
		</table>
	</div>
</div>
<div>
	<h4>CONTRATOS REAGENDADOS POR SKY</h4>
	<table class="encabezados" width="100%">
		<thead>
			<tr>
				<th style="width:2.3%;">No</th>
				<th style="width:7.8%;">Contrato</th>
				<th style="width:7.8%;">Cuenta</th>
				<th style="width:15.3%;">Distribuidor</th>
				<th style="width:6.8%;">Fecha Activaci&oacute;n</th>
				<th style="width:6.8%;">Comisi&oacute;n Posible(INCLUYE IVA)</th>
				<th style="width:6.8%;">Fecha de rechazo SKY</th>
				<th style="width:6.3%;">Contra Recibo</th>
				<th style="width:6.8%;">Fecha de vencimiento</th>
				<th style="width:5.8%;">Dias transcurridos</th>
				<th style="width:6.8%;">Motivo de rechazo 1</th>
				<th style="width:6.8%;">Motivo de rechazo 2</th>
				<th style="width:6.8%;">Motivo de rechazo 3</th>
				<th style="width:6.8%;">Color</th>
				<th style="width:1.5%;"></th>
			</tr>
		</thead>
	</table>
	<div id="scroll-tabla" style="height:200px;width:100%">
		<table border="0" class="detalle_contrato" width="100%">
			<tbody id="tbody_3">
			</tbody>
		</table>
	</div>
</div>
<!--<div style="float:left;">-->
<div>
	<h4>CONTRATOS DE MALA CALIDAD</h4>
	<table class="encabezados" width="73%">
		<thead>
			<tr>
				<th style="width:3.3%;">No</th>
				<th style="width:8.8%;">Contrato</th>
				<th style="width:8.8%;">Cuenta</th>
				<th style="width:17.3%;">Distribuidor</th>
				<th style="width:8.8%;">Fecha Activaci&oacute;n</th>
				<th style="width:8.8%;">Comisi&oacute;n Posible(INCLUYE IVA)</th>
				<th style="width:6.3%;">Contra Recibo</th>
				<th style="width:6.8%;">Motivo de rechazo</th>
				<th style="width:1.5%;"></th>
			</tr>
		</thead>
	</table>
</div>
<div id="scroll-tabla" style="height:200px;width:73%">
	<table border="0" class="detalle_contrato" width="100%">
		<tbody id="tbody_4">
		</tbody>
	</table>
</div>
<div>
	<h4>CONTRATOS POR FACTURAR</h4>
	<table class="encabezados" width="58%">
		<thead>
			<tr>
				<th style="width:3.3%;">No</th>
				<th style="width:8.8%;">Contrato</th>
				<th style="width:8.8%;">Cuenta</th>
				<th style="width:17.3%;">Distribuidor</th>
				<th style="width:8.8%;">Fecha Activaci&oacute;n</th>
				<th style="width:8.8%;">Comisi&oacute;n Posible(INCLUYE IVA)</th>
				<th style="width:1.5%;"></th>
			</tr>
		</thead>
	</table>
</div>
<div id="scroll-tabla" style="height:200px;width:58%">
	<table border="0" class="detalle_contrato" width="100%">
		<tbody id="tbody_5">
		</tbody>
	</table>
</div>
<div class="TiemposContratos">
	<h4>CONTRATOS FACTURADOS</h4>
	<table class="encabezados" width="84%">
		<thead>
			<tr>
				<th style="width:3.3%;">No</th>
				<th style="width:8.8%;">Contrato</th>
				<th style="width:8.8%;">Cuenta</th>
				<th style="width:17.3%;">Distribuidor</th>
				<th style="width:8.8%;">Fecha Activaci&oacute;n</th>
				<th style="width:8.8%;">Comisi&oacute;n Real</th>
				<th style="width:8.8%;">IVA</th>
				<th style="width:8.8%;">TOTAL</th>
				<th style="width:8.8%;">FOLIO FACTURA</th>
				<th style="width:1.5%;"></th>
			</tr>
		</thead>
	</table>
	<div id="scroll-tabla" style="height:200px;width:84%">
		<table border="0" class="detalle_contrato" width="100%">
			<tbody id="tbody_6">
			</tbody>
		</table>
	</div>
</div>
<script>
BuscarContratosTiempos();
</script>
{include file="_footer.tpl"}