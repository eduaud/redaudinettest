<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<link href="{$rooturl}css/tabswizard.css" rel="stylesheet" type="text/css" />
	<link href="{$rooturl}css/estilos.css" rel="stylesheet" type="text/css" />
	<script language="javascript" src="{$rooturl}js/jquery-1.8.3.js"></script>
	<script language="javascript" src="{$rooturl}js/funcionesNasser.js"></script>
	<script type="text/javascript" src="{$rooturl}/js/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
	<link rel="stylesheet" href="{$rooturl}/js/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
	<script type="text/javascript" src="{$rooturl}/js/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
	
	<title>Captura de Cuentas Contables</title>
</head>

<body>
	<form name="frmCapturaDeCuentasContables" id="frmCapturaDeCuentasContables">
		{if $tipoFormularioMostrar == ""}
		<br />
		<h1> Nueva Cuenta Contable </h1>
		<p>
			<b>Tipo de cuenta a registrar</b>
		</p>

		<div id="frmCuentasContablesAgregar">
		
			<table border="0" align="left" class="tableFormCuentasContables">
				<tbody>
						<tr>
							<td>
								<input type="radio" name="tipo_cuenta_contable" id="tipo_cuenta_contable" onClick="mostrarFormularioAgregarCuentaContable($(this).val())" value="1" />Nivel 1
							</td>
							<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="radio"  name="tipo_cuenta_contable" id="tipo_cuenta_contable" onClick="mostrarFormularioAgregarCuentaContable($(this).val())" value="2" />Nivel 2
							</td>
							<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="radio" name="tipo_cuenta_contable" id="tipo_cuenta_contable" onClick="mostrarFormularioAgregarCuentaContable($(this).val())" value="3" />Nivel 3
							</td>
							<td><div id="mensajeNivelCuentaContable"></div></td>
						</tr>
				</tbody>
			</table>
			
		</div>

		<div id="frmAgregarCuentaContableTipoNivel">
		</div>
		{/if}
		{if $tipoFormularioMostrar != ""}
		<p>
		&nbsp;
		</p>
		<table border="0" class="tableFormCuentasContables">
				<tbody>
						{if $tipoFormularioMostrar == 2 || $tipoFormularioMostrar == 3}
						<tr>
							<td>Cuenta Mayor:</td>
							<td colspan="2">
								<select name="id_cuenta_mayor" id="id_cuenta_mayor" {if $tipoFormularioMostrar == 3} onChange="llenarComboCuentaSuperior($(this).val());" {/if} {if $tipoFormularioMostrar == 2} onChange="verificaObligatorioCuentaMayorNivel2($(this).val());" {/if}>
									<option value="0"> -- Seleccionar -- </option>
									{section name="cm" loop=$aCuentasMayores}
										<option value="{$aCuentasMayores[cm].0}"> {$aCuentasMayores[cm].2} </option>
									{/section}
								</select>
								<div id="mensajeCuentaMayor"></div>
							</td>
							
						</tr>
						{/if}
						<tr>
							<td>
								<input type="hidden" name="nivel_cuenta_contable" id="nivel_cuenta_contable" value="{$tipoFormularioMostrar}" />
							</td>
						</tr>
						{if $tipoFormularioMostrar == 3}
						<tr>
							<td>Cuenta Superior:</td>
							<td>
								<div id="selectCuentaSuperior">
									<select name="id_cuenta_superior" id="id_cuenta_superior">
										<option value="0"> -- Selecciona Cuenta Superior -- </option>
									</select>
								</div>
							</td>
							<div id="mensajeCuentaSuperior"></div>
						</tr>
						{/if}
						<tr>
							<td>
								Cuenta Contable: 
							</td>
							{if $tipoFormularioMostrar == 1}
								<td>
							{else}
								<td colspan="4">
							{/if}
								{if $tipoFormularioMostrar == 2 || $tipoFormularioMostrar == 3}
								<input type="hidden" name="preLlenarCuenta" id="preLlenarCuenta" size="10" readonly />
								<input type="text" name="preLlenarCuentaName" id="preLlenarCuentaName" size="10" readonly />								- 
								{/if}
								<input type="text" name="id_cuenta_contable" id="id_cuenta_contable" size="10" onKeyPress="borraValidacion('id_cuenta_contable','mensajeCuentaContable');" /><div id="mensajeCuentaContable"></div>
							</td>
							{if $tipoFormularioMostrar == 1}
								<td colspan="2">
									Niveles de la Cuenta:
									<select id="nivelesDeLaCuenta" name="nivelesDeLaCuenta" onChange="nivelDeLaCuenta();">
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
									</select>
								</td>
							{/if}
						</tr>
						<tr>
							<td>
								Nombre de la Cuenta: 
							</td>
							<td colspan="3">
								<input type="text" name="nombre_cuenta_contable" id="nombre_cuenta_contable" onKeyPress="borraValidacion('nombre_cuenta_contable','mensajeNombreCuentaContable');" size="70" /><div id="mensajeNombreCuentaContable"></div>
							</td>
						</tr>
						<tr>
							<td>
								Genero de Cuenta Contable
							</td>
							<td>
								<select name="id_genero_cuenta_contable" id="id_genero_cuenta_contable" onChange="verificaObligatorioCuentaContable();">
									<option value="0" {if $tipoFormularioMostrar == 2 || $tipoFormularioMostrar == 3} disabled="disabled" {/if}> -- Selecciona un Género -- </option>
									{section name="generosCC" loop=$aGenerosCC}
										<option value="{$aGenerosCC[generosCC].0}"> {$aGenerosCC[generosCC].1} </option>
									{/section}
								</select>
								<div id="mensajeGeneroCuentaContable"></div>
							</td>
						</tr>
					{if $tipoFormularioMostrar != 3}
					</tbody>
					</table>
					<table>
						<tr>
							<td>
							Cuenta SAT:
							</td>
							<td>
								<select id="cuentaSAT" name="cuentaSAT">
									<option value="">--Selecciona--</option>
								</select>
							</td>
						</tr>
					</table>
					{else}
						</tbody>
						</table>
					{/if}
					<br/><table>
						<tr>
							<td>
								¿Es Facturable? 
							</td>
							<td>
								<input type="checkbox" name="es_facturable" id="es_facturable" value="1" {if $tipoFormularioMostrar == 2 || $tipoFormularioMostrar == 3} disabled="disabled" {/if} />
							</td>
							<td width="20px">&nbsp;</td>
							<td>
								Visible en Arbol
							</td>
							<td>
								<input type="checkbox" name="visible_arbol" id="visible_arbol" value="1" />
							</td>
						</tr>
						<tr>
							<td>
								Visible en Poliza
							</td>
							<td>
								<input type="checkbox" name="visible_poliza" id="visible_poliza" value="1" />
							</td>
							<td width="20px">&nbsp;</td>
							<td>
								Activo
							</td>
							<td>
								<input type="checkbox" name="activo" id="activo" value="1" />
							</td>
						</tr>
						<tr>
							<td>
								¿Es Cuenta Mayor?
							</td>
							<td>
								<input type="checkbox" name="es_cuenta_mayor" id="es_cuenta_mayor" value="1" />
							</td>
							<td>&nbsp;</td>
						</tr>
						</table>
						<br/>
						<div align="middle">
								<input class="botonSecundario" type="button" onclick="registrarCuentaContable('registrar')" value=" Registrar »" id="botonRegistra"/>
								<input type="hidden" name="tipoFormularioMostrar" id="tipoFormularioMostrar" value="{$tipoFormularioMostrar}">
						</div>
		{/if}
	</form>
	</body>
</html>