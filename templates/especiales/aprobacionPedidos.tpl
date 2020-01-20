{include file="_header.tpl" pagetitle="$contentheader"}
<link rel="stylesheet" type="text/css" href="{$rooturl}css/estilos_especiales.css"/>
<script type="text/javascript" src="{$rooturl}js/funciones_especiales.js"></script>
<h1 class="encabezado">Aprobaci&oacute;n de Pedidos</h1>
<div id="busquedas">
	<h4>Buscar por:</h4><br>
	<div id="provedores" style="float:left; padding-left:25px;">
		<label for="select-proveedor">Cliente</label>
		<select name="select-cliente" id="select-cliente" onchange="">
			<option value="0">Seleccione Cliente</option>
			{section name="cliente" loop=$a_cliente}
			<option value="{$a_cliente[cliente].id_cliente}">{$a_cliente[cliente].Cliente}</option>
			{/section}
		</select><br><br>
		<table>
			<tr>
				<td><label for="fechadel">Fecha del&nbsp;&nbsp;&nbsp;</label></td>
				<td><input type="text" id="fechadel" name="fechadel" class="fechas_ordenes" onFocus="calendario(this);"/></td>
				<td style="padding-left : 5px;"><label for="fechaal">Al</label></td>
				<td><input type="text" id="fechaal" name="fechaal" class="fechas_ordenes" onFocus="calendario(this);"/></td>
			</tr>
		</table><br>
		<p style="display:block; float:right;"><input type="button" class="boton" value="Buscar &raquo;" onclick="buscarPedidos(1)"/></p>
	</div>
<div id="orden-compra" style="float:left; padding-left:25px;">
		<table>
			<tr>
				<td><label for="orden">Pedido No.</label></td>
				<td><input type="text" id="pedido" name="orden" class="busca-producto" onkeydown="return noletrasCantidades(event);"/></td>
				<td><input type="button" class="boton" value="Buscar &raquo;" onclick="buscarPedidos(2);"/></td>
			</tr>
		</table>
</div>
</div>
<div style="clear:both;"></div>
<!---Tabla de ordenes de compra--->
<div style="margin:20px 0; width:966px">
	<table class="encabezados">
	<caption>Pedidos</caption>
		<thead>
				<tr>
						<th style="width:20px;">No</th>
						<th style="width:150px;">Sucursal</th>
						<th style="width:80px;">No. Pedido</th>
						<th style="width:100px;">Cliente</th>
						<th style="width:100px;">Fecha</th>
						<th style="width:100px;">Monto Pedido</th>
						<th style="width:100px;">Saldo Pendiente</th>
						<th style="width:300px;">Acciones</th>
				</tr>
		</thead>
	</table>
	<div id="scroll-tabla">
		<table border="0" class="detalle"> 
			<tbody>
			{assign var="contador" value="1"}
					{section name="pedido" loop=$a_pedidos}
						<tr>
							<td style="width:20px; text-align:center">{$contador}</td>
							<td style="width:150px; text-align:center">{$a_pedidos[pedido].Plaza}</td>
							<td style="width:80px; text-align:center">{$a_pedidos[pedido].Pedido}</td>
							<td style="width:100px; text-align:center">{$a_pedidos[pedido].Cliente}</td>
							<td style="width:100px; text-align:center">{$a_pedidos[pedido].Fecha}</td>
							<td style="width:100px; text-align:center">{$a_pedidos[pedido].Monto}</td>
							<td style="width:100px; text-align:center">&nbsp;&nbsp;</td>
							<td style="width:200px; text-align:center">
							<div style="width : auto">
									<input type="button" id="aprobar" class="botonesD" value="Aprobar" onClick="apruebaPedido({$a_pedidos[pedido].id_control_pedido})"/>
									<input type="button" id="rechazar" class="botonesD" value="Rechazar" onClick="rechazaPedido({$a_pedidos[pedido].id_control_pedido})"/>
									<br><br>
									<textarea style="width:150px" id="motivo-rechazo{$contador}" class="rechazo"></textarea>
							</div>		
							
							</td>
							<td style="display:none"><input id="idOrden{$contador}" type="hidden" value="{$filas[filasOrdenes].1}"/></td>
							<td style="display:none">{$contador++}</td>
						</tr>
					{sectionelse}
						<tr>
							<td>No existen pedidos con estos datos de busqueda</td>
						</tr>
					{/section}
			</tbody>
		</table>
	</div>
</div>

{include file="_footer.tpl"}