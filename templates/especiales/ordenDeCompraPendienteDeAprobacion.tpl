{include file="_header.tpl" pagetitle="$contentheader"}
<link href="{$rooturl}css/estilos_especiales.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="{$rooturl}js/funciones_especiales.js"></script>
<h1 class="encabezado">Ordenes de Compra Pendientes de Aprobaci&oacute;n</h1>
<div id="busquedas">
	<p>Buscar por:</p><br>
	<div id="ordenescompra" style="float:left; padding-left:25px;">
		<table>
			<tr>
				<td><label for="select-plaza">Plaza</label></td>
				<td>
					<select name="select-sucursal" id="select-sucursal" onchange="">
						<option value="-1">Seleccione la Plaza</option>
						{section name="sucursales" loop=$sucursal}
							<option value="{$sucursal[sucursales].0}">{$sucursal[sucursales].1}</option>
						{/section}
					</select>				</td>
				<td>&nbsp;</td>
			  	<td><label>ODC:</label></td>
		      	<td><input type="text" id="idordencompra" name="idordencompra"/></td>
			</tr>
			<tr>
				<td><label for="select-solicitante">Usuario Solicitante</label></td>
				<td>
					<select name="select-usuario-solicitante" id="select-usuario-solicitante" onchange="">
						<option value="-1">Seleccione un Usuario</option>
						{section name="usuarios" loop=$usuario}
							<option value="{$usuario[usuarios].0}">{$usuario[usuarios].1}</option>
						{/section}
					</select>				</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			    <td><span style="display:block; float:right;">
			      <input name="button" type="button" class="boton" onclick="buscaOrdenesDeCompraPendientesDeAprobacion(2)" value="Buscar &raquo;"/>
			    </span></td>
			</tr>
			<tr>
				<td><label for="fechadel">Fecha del</label></td>
				<td>
					<input type="text" id="fechadel" name="fechadel" class="fechas" onFocus="calendario(this);"/>
					<label for="fechaal">&nbsp;Al</label>
					<input type="text" id="fechaal" name="fechaal" class="fechas" onFocus="calendario(this);"/>				</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td><p style="display:block; float:right;">&nbsp;</p></td>
			    <td>&nbsp;</td>
			</tr>
			<tr>
			  <td>&nbsp;</td>
		      <td><span style="display:block; float:right;">
		        <input name="button2" type="button" class="boton" onclick="buscaOrdenesDeCompraPendientesDeAprobacion(1)" value="Buscar &raquo;"/>
		      </span></td>
		      <td>&nbsp;</td>
		      <td>&nbsp;</td>
		      <td>&nbsp;</td>
		  </tr>
		</table>
	</div>
</div>
<div style="clear:both;"></div>

<!-- Tabla de Ordenes de Compra -->
<div style="margin:20px 0; width:auto">
	<table class="encabezados">
	<caption>Ordenes de Compra</caption>
		<thead>
			<tr>
				<th style="width:20px;">No</th>
				<th style="width:200px; text-align:center">Plaza Solicitante</th>
				<th style="width:100px; text-align:center">ID ODC</th>
				<th style="width:100px; text-align:center">Proveedor</th>
				<th style="width:100px; text-align:center">Fecha de Creaci&oacute;n</th>
				<th style="width:100px; text-align:center">Fecha Requerida</th>
				<th style="width:200px; text-align:center">Usuario Solicitante</th>
				<th style="width:200px; text-align:center">$Total</th>
				<th style="width:300px; text-align:center">Acciones</th>
			</tr>
		</thead>
	</table>
	<div id="scroll-tabla">
		<table border="0" class="detalle"> 
			<tbody>
				{assign var="contador" value="1"}
					{section name="fila" loop=$filas}
						<tr>
							<td style="width:20px; text-align:center">{$contador}</td>
							<!--
							<td style="width:200px; text-align:center">{$filas[fila].0}</td>
							<td style="width:100px; text-align:center">{$filas[fila].1}</td>
							<td style="width:100px; text-align:center">{$filas[fila].2}</td>
							<td style="width:100px; text-align:center">{$filas[fila].3}</td>
							<td style="width:100px; text-align:center">{$filas[fila].7}</td>
							<td style="width:200px; text-align:center">{$filas[fila].8}</td>
							<td style="width:200px; text-align:center">{$filas[fila].6}</td>
							<td style="width:300px; text-align:center">
							-->
							<td style="width:200px; text-align:center">{$filas[fila].1}</td>
							{*<td style="width:100px; text-align:center"><a href="#" onclick="verOrdenDeCompra({$filas[fila].0});">{$filas[fila].2}</a></td>*}
							<td style="width:100px; text-align:center">{$filas[fila].2}</td>
							<td style="width:100px; text-align:center">{$filas[fila].3}</td>
							<td style="width:100px; text-align:center">{$filas[fila].4}</td>
							<td style="width:100px; text-align:center">{$filas[fila].8}</td>
							<td style="width:200px; text-align:center">{$filas[fila].9}</td>
							<td style="width:200px; text-align:center">{$filas[fila].7}</td>
							<td style="width:300px; text-align:center">
								<div style="width : auto">
									<input type="button" id="aprobar" class="small button grey" value="Aprobar" onClick="apruebaOrdenDeCompra({$contador})"/>
									<input type="button" id="rechazar" class="small button grey" value="Rechazar" onClick="rechazaOrdenDeCompra({$contador})"/><br />
									<textarea style="width:auto; height:25px;" id="motivo-rechazo{$contador}" class="rechazo"></textarea>
								</div>		
							</td>
							<td style="display:none">
							<!--
								<input id="idOrdenCompra{$contador}" type="hidden" value="{$filas[fila].1}"/>
								<input id="idOrdenCompraControl{$contador}" type="hidden" value="{$filas[fila].9}"/>
							-->
								<input id="idOrdenCompra{$contador}" type="hidden" value="{$filas[fila].2}"/>
								<input id="idOrdenCompraControl{$contador}" type="hidden" value="{$filas[fila].10}"/>

							</td>
							<td style="display:none">{$contador++}</td>
						</tr>
					{sectionelse}
					<tr>
						<td width="1215" colspan="9" align="center">No existen &oacute;rdenes pendientes con estos datos de b&uacute;squeda</td>
					</tr>
				{/section}
			</tbody>
		</table>
	</div>
</div>
<!-- Tabla de Ordenes de Compra -->
{include file="_footer.tpl"}