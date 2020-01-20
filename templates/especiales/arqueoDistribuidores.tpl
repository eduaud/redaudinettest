{include file="_header.tpl" pagetitle="$contentheader"}
<link rel="stylesheet" type="text/css" href="{$rooturl}css/estilos_especiales.css"/>
<script type="text/javascript" src="{$rooturl}js/funciones_especiales.js"></script>
<script>
{literal}
function CalcularMontoPagar(opcion,cobrar,seleccionado,Mtotal,cheque){
	if(seleccionado==true){
		$("#"+cobrar+"_"+opcion).removeAttr("disabled");
	}
	else{
		$("#"+cobrar+"_"+opcion).attr('disabled','disabled');
		var monto_t=$("#"+Mtotal).val();
		var monto_t=$("#"+cheque).val();
		var montoTot=$("#"+cobrar+"_"+opcion).val();
		
		$("#"+cobrar+"_"+opcion).val("");
		if(monto_t=='')
			montoT=0;
		if(montoTot=='')
			montoTot=0;
		Total=monto_t-montoTot;
		$("#"+Mtotal).val(Total);
		$("#"+cheque).val(Total);
	}  
}
function MontoTotal(cobrar,total,clase,cheque){
	var monto=0;
	$("."+clase+":checkbox:checked").each(function(){
		id=$(this).val();
		if($("#"+cobrar+"_"+id).val()=='')
			value=0;
		else 
			value=parseFloat($("#"+cobrar+"_"+id).val())
			
		monto+=value;
		}
	);
	$("#"+total).val(monto);
	$("#"+cheque).val(monto);
}
function buscarArqueo(){
}
{/literal}
</script>
<h1 class="titulo-catalogo">Arqueo Distribuidores</h1>
<div>
	<table>
		<tr>
			<td><label for="select-proveedor">Plaza: </label></td>
			<td>
				<select id="distribuidor">
					<option value="0">Seleccione una Plaza</option>
					{section name=Plaza loop=$a_Plazas}
						<option value="{$a_Plazas[Plaza].id_sucursal}">{$a_Plazas[Plaza].nombre}</option>
					{/section}
				</select>
			</td>
			<td>&nbsp;<label for="select-proveedor">Distribuidor: </label></td>
			<td>
				<select id="distribuidor">
					<option value="0">Seleccione un Distribuidor</option>
					{section name="Distribuidor" loop=$a_Distribuidores}
						<option value="{$a_Distribuidores[Distribuidor].id_cliente}">{$a_Distribuidores[Distribuidor].nombre}</option>
					{/section}
				</select>
			</td>
				<td>&nbsp;<input type="button" class="" value="Buscar"/></td>
		</tr>
	</table>
</div>
<div style="clear:both;"></div>
<div>
	<div style="float:left;">
		
			<table>
			<caption>FACTURAS</caption>
			<thead class="encabezadoarqueo" >
			<tr id="factura">
				<th width="15px">&nbsp;</th>
				<th width="75px">FACTURA</th>
				<th width="75px">FECHA</th>
				<th width="70px">CONCEPTO</th>
				<th width="70px">TOTAL $</th>
				<th width="70px">PAGOS $</th>
				<th width="70px">SALDO $</th>
				<th width="91px">COBRAR $</th>
			</tr>
			</thead>
			</table>
			<div id="scroll-tablaArqueo">
			<table>
			<tbody class="detalleArqueo">
				{section name=Factura loop=$a_Facturas}
				<tr>
					<td width="15px"><input class="C_facturas" type="checkbox" value="{$a_Facturas[Factura].id_control_factura}" id="fac_{$a_Facturas[Factura].id_control_factura}" onclick="CalcularMontoPagar('{$a_Facturas[Factura].id_control_factura}','cobrarFac',this.checked,'montoTotalFact','pagoDeFacturas')"/></td>
					<td width="75px">{$a_Facturas[Factura].id_control_factura}</td>
					<td width="75px">{$a_Facturas[Factura].fecha_y_hora|date_format:"%D"}</td>
					<td width="70px">{$a_Facturas[Factura].nombre}</td>
					<td width="70px">{$a_Facturas[Factura].total|number_format:2:'.':','}</td>
					<td width="70px">{$a_Facturas[Factura].pagos}</td>
					<td width="70px">{$a_Facturas[Factura].saldo}</td>
					<td style="text-align:center;width:90px;"><input type="text" class="inputPago" id="cobrarFac_{$a_Facturas[Factura].id_control_factura}" disabled="disabled" onkeyup="MontoTotal('cobrarFac','montoTotalFact','C_facturas','pagoDeFacturas');"/></td>
				</tr>
				{/section}
			</tbody>
		</table>
		</div>
		<table>
			<tr>
				<td colspan="5" width="470px"><label for="select-proveedor">Monto Total a Cobrar $</label></td>
				<td style="text-align:center"><input type="text" id="montoTotalFact" style="width:70px;" disabled/></td>
			</tr>
		</table>
	</div>
	<div style="float:left;margin-left:10px;">
		<table>
			<caption>CUENTAS POR PAGAR</caption>
			<thead class="encabezadoarqueo" >
			<tr id="cuentasporpagar">
				<th width="10px">&nbsp;</th>
				<th width="75px">CxP</th>
				<th width="70px">FECHA</th>
				<th width="70px">CONCEPTO</th>
				<th width="70px">TOTAL $</th>
				<th width="70px">PAGOS $</th>
				<th width="70px">SALDO $</th>
				<th width="96px">PAGAR $</th>
			</tr>
			</thead>
		</table>
		<div id="scroll-tablaArqueo">
			<table>
				<tbody class="detalleArqueo">
					{section name=CxP loop=$a_cxp}
						<tr>
							<td width="15px"><input class="cxp" type="checkbox" value="{$a_cxp[CxP].id_cuenta_por_pagar}" id="cxp_{$a_cxp[CxP].id_cuenta_por_pagar}" onclick="CalcularMontoPagar('{$a_cxp[CxP].id_cuenta_por_pagar}','Cxp',this.checked,'montoTotalCxP','chequeCxp')"/></td>
							<td width="75px">{$a_cxp[CxP].id_cuenta_por_pagar}</td>
							<td width="70px">{$a_cxp[CxP].fecha_captura}</td>
							<td width="70px">{$a_cxp[CxP].fecha_vencimiento}</td>
							<td width="70px">{$a_cxp[CxP].total}</td>
							<td width="70px">{$a_cxp[CxP].total}</td>
							<td width="70px">{$a_cxp[CxP].total}</td>
							<td style="text-align:center;width:90px"><input type="text" class="inputPago" onkeyup="MontoTotal('Cxp','montoTotalCxP','cxp','chequeCxp');" disabled="disabled" id="Cxp_{$a_cxp[CxP].id_cuenta_por_pagar}"/></td>
						</tr>
					{/section}
				</tbody>
			</table>
		</div>
		<table>
			<tr>
				<td colspan="5" width="460px"><label for="select-proveedor">Monto Total a Pagar $</label></td>
				<td style="text-align:center"><input type="text" id="montoTotalCxP" style="width:70px;" disabled/></td>
			</tr>
		</table>
	</div>
	<div style="float:left;">
		<table>
		<caption>CUENTAS POR COBRAR</caption>
			<thead class="encabezadoarqueo">
			<tr id="cuentasporcobrar">
				<th width="10px">&nbsp;</th>
				<th width="65px">CxC</th>
				<th width="65px">CONTRATO</th>
				<th width="65px">CUENTA</th>
				<th width="65px">FECHA</th>
				<th width="65px">CONCEPTO</th>
				<th width="65px">TOTAL $</th>
				<th width="65px">PAGOS $</th>
				<th width="65px">SALDO $</th>
				<th width="70px">COBRAR $</th>
			</tr>
			</thead>
		</table>
		<div id="scroll-tablaArqueo">
			<table>
			<tbody class="detalleArqueo">
			{section name=CxC loop=$a_cxc}
				<tr>
					<td width="10px"><input class="CxC" type="checkbox" value="{$a_cxc[CxC].id_control_factura}" id="cxc_{$a_cxc[CxC].id_control_factura}" onclick="CalcularMontoPagar('{$a_cxc[CxC].id_control_factura}','cobrarFac',this.checked,'cobrarcxc')"/></td>
					<td width="65px"></td>
					<td width="65px"></td>
					<td width="65px"></td>
					<td width="65px"></td>
					<td width="65px"></td>
					<td width="65px"></td>
					<td width="65px"></td>
					<td width="65px"></td>
					<td width="65px"></td>
					<td style="text-align:center;width:70px"><input type="text" class="inputPago" onkeyup="MontoTotal('cobrarcxc','montoTotalCxP','cxp');" disabled="disabled" id="cobrarcxc_{$a_cxc[CxC].id_cuenta_por_pagar}"/></td>
				</tr>
			{sectionelse}
				<tr><td>No existen cuentas por cobrar</td></tr>
			{/section}
			</tbody>
			</table>
			</div>
			<table>
			<tr>
				<td colspan="5" width="540px"><label for="select-proveedor">Monto Total a Endosar $</label></td>
				<td style="text-align:center"><input type="text" id="montoTotal" style="width:70px;" disabled/></td>
			</tr>
		</table>
	</div>
	<div style="float:left;margin-left:60px;">
		<h2 align="center">Resumen</h2>
		<table>
			<tr><td width="150px"></td><td width="150px"></td><td align="center"><label>ENDOSOS POR PAGAR</label></td></tr>
			<tr>
				<td><label>ENDOSO POR PAGO DE FACTURAS</label></td>
				<td><input type="text" id="pagoDeFacturas" class="endosado" disabled="disabled"/>
				<td><label>TYM&nbsp;</label><input type="text" class="endosado" disabled="disabled"></td>
			</tr>
			<tr>
				<td><label>ENDOSO POR CUENTAS POR PAGAR</label></td>
				<td><input type="text" id="chequeCxp" class="endosado" disabled="disabled"/></td><td><label>VETV</label><input type="text" class="endosado" disabled="disabled"></td>	
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td><label>TMK&nbsp;</label><input type="text" class="endosado" disabled="disabled"></td>	
			</tr>	
		</table>
	</div>
</div>
<script>
	{if $totalFactura>=7}
		$("#factura").html('<th width="10px"></th>');
	{/if}
	{if $totalcxp>=7}
		$("#cuentasporpagar").append('<th width="10px"></th>');
	{/if}
	{if $totalcxp>=7}
		$("#cuentasporcobrar").append('<th width="10px"></th>');
	{/if}
</script>
{include file="_footer.tpl"}