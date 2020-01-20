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
		height : 150px;
	}
	.campos_reportes select.multiples{
		width : 500px;
		height : 300px;
	}
{/literal}
</style>

<link href="../../css/estilos.css" rel="stylesheet" type="text/css" />
<link href="../../css/gridSW.css" rel="stylesheet" type="text/css" />
<!-- Aquí va el tipo de documento -->
<script language="JavaScript" type="text/javascript" src="{$rooturl}js/jquery/jquery.js"></script>
<script language="JavaScript" type="text/javascript" src="{$rooturl}js/pedidos.js"></script>
<script language="JavaScript" type="text/javascript" src="{$rooturl}js/funciones_especiales.js"></script>

<div id="content-sistema" class="content-sistema">
	<br/><br/>
	<h1>{$titulo}</h1>

	<div id="buscador" class="buscador" style="background-color:#CCC">
		<form action="#" method="post" id="formax" name="formax">
			<br>
			<table border="0" style="margin:0 auto" class="campos_reportes">
				{if $rep == 106}
					<tr>
						<td align="center">
							<p>Plazas</p>
							<select id="idPlazas" class="campos_req" name="idPlazas" onChange="llenaSelect('idPlazas','idClientes','25');" multiple="">
								<option value="0">Todos</option>
								{html_options values=$arrPlazasID output=$arrPlazasNombre}
							</select>
						</td>
						<td align="center">
							<p>Clientes</p>
							<select id="idClientes" class="campos_req" name="idClientes" multiple="">
								<option value="0">Todos</option>
							</select>
						</td>
					</tr>
					
					<tr>
						<td align="center">
							<p>Fecha Inicial</p>
							<input type="text" id="fechaInicial" onfocus="calendario(this);" class="campos_req"/>
						</td>
						<td align="center">
							<p>Fecha Final</p>
							<input type="text" id="fechaFinal" onfocus="calendario(this);" class="campos_req"/>
						</td>
					</tr>
				{/if}
				
				{if $rep == 107}
					<tr>
						<td align="center">
							<p>Plazas</p>
							<select id="idPlazas" class="campos_req" name="idPlazas" onChange="llenaSelect('idPlazas','idClientes','25');" multiple="">
								<option value="0">Todos</option>
								{html_options values=$arrPlazasID output=$arrPlazasNombre}
							</select>
						</td>
						<td align="center">
							<p>Clientes</p>
							<select id="idClientes" class="campos_req" name="idClientes" multiple="">
								<option value="0">Todos</option>
							</select>
						</td>
					</tr>
					
					<tr>
						<td align="center">
							<p>Fecha Inicial</p>
							<input type="text" id="fechaInicial" onfocus="calendario(this);" class="campos_req"/>
						</td>
						<td align="center">
							<p>Fecha Final</p>
							<input type="text" id="fechaFinal" onfocus="calendario(this);" class="campos_req"/>
						</td>
					</tr>
				{/if}
			</table>
			
			<table style="margin: auto; width: 99%; padding-top: 20px;">
				<tr align="left">
					<td align="left" class="user">
						<input type="button" value="Generar reporte" class="boton" onclick="mostrarReporte('{$nombreReporte}','no');"/>
						<input type="button" value="Exportar a Excel" class="boton" onclick="mostrarReporte('{$nombreReporte}','si');"/>
					</td>
				</tr>
			</table>
			<br>
		</form>
	</div>
</div>
{include file="_footer.tpl" aktUser=$username}