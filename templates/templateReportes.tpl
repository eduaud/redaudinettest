{include file="_header.tpl" pagetitle="$contentheader"}
<style>
{literal}
	.campos_reportes p{
		font-weight : bold;
	}
	.campos_reportes td{
		padding : 5px;
	}
	.campos_reportes select{
		width : 250px;
	}
	.campos_reportes select.multiples{
		width : 500px;
		height : 300px
	}
{/literal}
</style>
<link href="../../css/estilos.css" rel="stylesheet" type="text/css" />
<link href="../../css/gridSW.css" rel="stylesheet" type="text/css" />
<!-- Aquí va el tipo de documento -->
<script language="JavaScript" type="text/javascript" src="{$rooturl}js/jquery/jquery.js"></script>
<script language="JavaScript" type="text/javascript" src="{$rooturl}js/funcionesNasser.js"></script>
<script language="JavaScript" type="text/javascript" src="{$rooturl}js/reportes.js"></script>

<div id="content-sistema" class="content-sistema">
	<br/><br/>
	<h1>{$titulo}</h1>
	<div id="buscador" class="buscador" style="background-color:#CCC">
		<form action="#" method="post" id="formax" name="formax">
			<table border="0" style="margin:0 auto" class="campos_reportes">
			{if $reporte eq 0}
				<tr>
					<td>
						<p>Familia de Productos</p>
						<select name="slct_familia" class="campos_req movimientos" id="slct_familia" onChange="llenaMultiple();">
							<option value="0">Todos</option>
							{html_options values=$familia_id output=$familia_nombre}
						</select>
					</td>
					<td>
						<p>Tipo de Productos</p>
							<select id="slct_tipos" class="campos_req" name="slct_tipos" onChange="llenaMultiple();">
								<option value="0">Todos</option>
								{html_options values=$tipo_id output=$tipo_nombre}
							</select>
					</td>
				</tr>
			{/if}
			{if $reporte eq 105}
				<!--<tr>
					<td>
						<p>Filtrar por Fecha de:</p>
					</td>
					<td>
						<p><input type="radio" name="campoFecha" id="radio_act" value="activacion" onclick="document.getElementById('fechadel').disabled=false; document.getElementById('fechaal').disabled=false"> Activacion</p>
						<p><input type="radio" name="campoFecha" id="radio_mov" value="movimiento" onclick="document.getElementById('fechadel').disabled=false; document.getElementById('fechaal').disabled=false"> Movimiento</p>
					</td>
				</tr>-->
				<tr>
					<td>
						<p>Fecha Activaci&oacute;n del:</p>
						<input style="width: 240px;" type="text" id="fechadel" name="fechadel" class="campos_req fechas" title="Tipo fecha (dd/mm/aaaa)" onfocus="calendario(this);" />
					</td>
					<td>
						<p>Al:</p>
						<input style="width: 240px;" type="text" id="fechaal" name="fechaal" class="campos_req fechas" title="Tipo fecha (dd/mm/aaaa)" onfocus="calendario(this);" />
					</td>
				</tr>
			{/if}
			{if $reporte eq 150}
				<tr>
					<td>
						<p>Estatus:</p>
						<select id="estatus-estado">
							<option value="0">TODO</option>
							<option value="1">PENDIENTES</option>
							<option value="2">SALDADAS</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<p>Fecha de Documentos del:</p>
						<input style="width: 240px;" type="text" id="fechadel" name="fechadel" class="campos_req fechas" title="Tipo fecha (dd/mm/aaaa)" onfocus="calendario(this);" />
					</td>
					<td>
						<p>al:</p>
						<input style="width: 240px;" type="text" id="fechaal" name="fechaal" class="campos_req fechas" title="Tipo fecha (dd/mm/aaaa)" onfocus="calendario(this);" />
					</td>
				</tr>
			{/if}
                        
                        {if $reporte eq 228}
			<tr>
				<td colspan="2">
					<p>Movimientos</p>
					<select class="campos_req multiples" name="slct_mov" id="slct_mov" multiple="multiple" style="height:100px;" >
						<option value="0">Todos</option>
						{html_options values=$arrIdSubMovimientos output=$arrSubMovimientos}
					</select>
				</td>
			</tr>
			<tr>
				<td>
					<p>Fecha Ingreso Inicial:</p>
					<input style="width:150px;" type="text" id="sFechaIngresoInicial" name="sFechaIngresoInicial" class="campos_req fechas" title="Tipo fecha (dd/mm/aaaa)" readonly onfocus="calendario(this);"/>
				</td>
				<td style="padding-left : 15px;">
					<p>Fecha Ingreso Final:</p>
					<input style="width:150px;" type="text" id="sFechaIngresoFinal" name="sFechaIngresoFinal" class="campos_req fechas" title="Tipo fecha (dd/mm/aaaa)" readonly onfocus="calendario(this);"/>
				</td>
			</tr>
			<tr>
				<td><p><h3>Filtro de Productos</h3></p></td>
			</tr>
			<tr>
				<td style="border-top: 1px solid #666; border-left: 1px solid #666;" >
					<p>Tipo de Productos</p>
					<select id="slct_tipos" class="campos_req" name="slct_tipos" onChange="llenaMultiple();">
						<option value="0">Todos</option>
						{html_options values=$tipo_id output=$tipo_nombre}
					</select>
				</td>
                                
                                 <td style=" border-top: 1px solid #666; border-right: 1px solid #666;" colspan="2">
					<p>Tipo de Reporte</p>
					<select id="tipo_reporte" class="campos_req" name="tipo_reporte">
						<option value="1">Detallado</option>
						<option value="2">General</option>
					</select>
                                </td>
                                
			</tr>

                        
			<tr>
				<td style="border-left: 1px solid #666;">
					<p>B&uacute;squeda por Clave</p>
					<input style="width:250px;" type="text" id="busqueda_sku" name="busqueda_sku"/>
				</td>
				<td valign="bottom" style="border-right: 1px solid #666;">
					<input class="boton" type="button" id="busqueda-sku" value="Buscar" onClick="buscaClave();"/>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="border-left: 1px solid #666; border-bottom: 1px solid #666; border-right: 1px solid #666;">
					<p>Productos</p>
					<select name="slct_productos" class="campos_req multiples" id="slct_productos"  multiple="multiple"></select>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<p>Proveedores</p>
					<select class="campos_req multiples" name="slct_prov" id="slct_prov" multiple="multiple" style="height:100px;" >
						<option value="0">Todos</option>
						{html_options values=$prov_id output=$prov_nombre}
					</select>
				</td>
			</tr>
		{/if}
                        
                        
			</table>
		</form>
	</div>
	<table width="100%">
		<tr valign="middle">
			<td height="26">
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr align="left">
						<!--Botonera-->
						<td align="left" class="user"><br>
							<input type="button" value="Generar reporte" class="boton" onclick="generaReporte(1,{$reporte})"/>
							<input type="button" value="Exportar a Excel" class="boton" onclick="generaReporte(2,{$reporte})"/>                            
						</td>
						<!--Fin de botonera-->                      
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>
{include file="_footer.tpl" aktUser=$username}