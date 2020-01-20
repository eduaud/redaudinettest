{include file="_header.tpl" pagetitle="$contentheader"}
<link href="{$rooturl}css/estilos_especiales.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="{$rooturl}js/funciones_especiales.js"></script>
<h1 class="encabezado">Requisiciones Pendientes de Aprobaci&oacute;n</h1>
<div id="busquedas">
	<p>Buscar por:</p><br>
	<div id="requisiciones" style="float:left; padding-left:25px;">
		<table>
			<tr>
				<td><label for="select-plaza">Plaza</label></td>
				<td>
					<select name="select-sucursal" id="select-sucursal" onchange="">
						<option value="-1">Seleccione la Plaza</option>
						{section name="sucursales" loop=$sucursal}
							<option value="{$sucursal[sucursales].0}">{$sucursal[sucursales].1}</option>
						{/section}
					</select>
				</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><label for="select-solicitante">Usuario Solicitante</label></td>
				<td>
					<select name="select-usuario-solicitante" id="select-usuario-solicitante" onchange="">
						<option value="-1">Seleccione un Usuario</option>
						{section name="usuarios" loop=$usuario}
							<option value="{$usuario[usuarios].0}">{$usuario[usuarios].1}</option>
						{/section}
					</select>
				</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><label for="fechadel">Fecha del</label></td>
				<td>
					<input type="text" id="fechadel" name="fechadel" class="fechas" onFocus="calendario(this);"/>
					<label for="fechaal">&nbsp;Al</label>
					<input type="text" id="fechaal" name="fechaal" class="fechas" onFocus="calendario(this);"/>
				</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td><p style="display:block; float:right;"><input type="button" class="boton" value="Buscar &raquo;" onclick="buscaRequisicionesPendientesDeAprobacion()"/></p></td>
			</tr>
		</table>
	</div>
</div>
<div style="clear:both;"></div>

<!-- Tabla de Requisiciones -->
<div style="margin:20px 0; width:auto">
	<table class="encabezados" border="0">
	<caption>Ordenes de Compra</caption>
		<thead>
			<tr>
				<th style="width:20px;">No</th>
				<th style="width:200px; text-align:center">Plaza Solicitante</th>
				<th style="width:100px; text-align:center">ID Requisici&oacute;n</th>
				<th style="width:150px; text-align:center">Fecha de Creaci&oacute;n</th>
				<th style="width:100px; text-align:center">Fecha Requerida</th>
				<th style="width:350px; text-align:center">Usuario Solicitante</th>
				<th style="width:300px; text-align:center">Acciones</th>
			</tr>
		</thead>
	</table>
	<div id="scroll-tabla-detalle-requisiciones">
		<table border="0" class="detalle"> 
			<tbody>
				{assign var="contador" value="1"}
					{section name="fila" loop=$filas}
						<tr>
							<td style="width:20px; text-align:center">{$contador}</td>
							<td style="width:200px; text-align:center">{$filas[fila].0}</td>
							<td style="width:100px; text-align:center"><a href="#" onclick="verRequisicion({$filas[fila].1});">{$filas[fila].1}</a></td>
							<td style="width:150px; text-align:center">{$filas[fila].2}</td>
							<td style="width:100px; text-align:center">{$filas[fila].3}</td>
							<td style="width:350px; text-align:center">{$filas[fila].4}</td>
							<td style="width:255px; text-align:center">
								<div style="width : auto">
									<input type="button" id="aprobar" class="small button grey" value="Aprobar" onClick="apruebaRequisicion({$contador})"/>
									<input type="button" id="rechazar" class="small button grey" value="Rechazar" onClick="rechazaRequisicion({$contador})"/><br />
									<textarea style="width:150px; height:20px;" id="motivo-rechazo{$contador}" class="rechazo"></textarea>
								</div>
							</td>
							<td style="display:none"><input id="idRequisicion{$contador}" type="hidden" value="{$filas[fila].1}"/></td>
							<td style="display:none">{$contador++}</td>
						</tr>
					{sectionelse}
					<tr>
						<td align="center" colspan="9" width="1775">No existen &oacute;rdenes con estos datos de b&uacute;squeda</td>
					</tr>
				{/section}
			</tbody>
		</table>
	</div>
</div>
<!-- Tabla de Requisiciones -->
{include file="_footer.tpl"}