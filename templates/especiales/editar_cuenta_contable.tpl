<!DOCTYPE html>

<html lang="es">
<head>
	<meta charset="utf-8">
	<link href="{$rooturl}css/tabswizard.css" rel="stylesheet" type="text/css">
	<link href="{$rooturl}css/estilos.css" rel="stylesheet" type="text/css" />
	<script language="javascript" src="{$rooturl}js/jquery-1.8.3.js"></script>
	<script language="javascript" src="{$rooturl}js/funcionesNasser.js"></script>
	<script type="text/javascript" src="{$rooturl}/js/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
	<link rel="stylesheet" href="{$rooturl}/js/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
	<script type="text/javascript" src="{$rooturl}/js/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
	
	<title>Captura de Cuentas Contables</title>
</head>
<body onload="nivelDeLaCuenta();">
<br />
<h1> Editar Cuenta Contable </h1>
<p>
{assign var="text" value="readonly"}
{if $editar == 0}
	{assign var="bloquear" value="n"}
{/if}

{if $editar == 1}
	{assign var="bloquear" value="s"}
	<b>Nota: La cuenta seleccionada para editar contiene subcuentas asociadas, por lo que solamente podrá modificar el 'Nombre de la Cuenta'</b>
{/if}

{if $editar == 2}
	{assign var="bloquear" value="s"}
	<b>Nota: La cuenta seleccionada para editar esta Relacionada con Documentos, solo podrá modificar el 'Nombre de la Cuenta'</b>
{/if}
</p>
	<form name="frmEditarCuentasContables" id="frmEditarCuentasContables">
		<p>
		&nbsp;
		</p>
	{section name="detalleCuenta"}
	<input type="hidden" name="llave" id="llave" size="10" value="{$datosCuenta[detalleCuenta].0}" />
		<table border="0" class="tableFormCuentasContables">
				<tbody>
						{if $tipoFormularioMostrar == 2 || $tipoFormularioMostrar == 3}
						<tr>
							<td>Cuenta Mayor:</td>
							<td>
								<select name="id_cuenta_mayor" id="id_cuenta_mayor" {if $tipoFormularioMostrar == 3} onChange="llenarComboCuentaSuperior($(this).val());" {/if} {if $tipoFormularioMostrar == 2} onChange="verificaObligatorioCuentaMayorNivel2($(this).val());" {/if}>
									<option value="0"  {if $bloquear == 's'} disabled {/if}> -- Seleccionar -- </option>
									{section name="cm" loop=$aCuentasMayores}
										<option 
										{if $aCuentasMayores[cm].0 != $datosCuenta[detalleCuenta].8 }
											{if $bloquear == 's'} disabled {/if} 
										{/if}
										value="{$aCuentasMayores[cm].0}" 
										{if $aCuentasMayores[cm].0 == $datosCuenta[detalleCuenta].8 } selected enabled {/if}> 
											{$aCuentasMayores[cm].2} 
										</option>
									{/section}
								</select>
							<td>
							<div id="mensajeCuentaMayor"></div>
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
									<select name="id_cuenta_superior" id="id_cuenta_superior" onChange="verificaObligatorioCuentaMayorNivel2($(this).val())">
										<option {if $bloquear == 's'} disabled {/if} value="0"> -- Selecciona Cuenta Superior -- </option>
										{section name="cuentaSuperior" loop=$comboCuentaSuperior}
											<option 
											{if $comboCuentaSuperior[cuentaSuperior].0 != $datosCuenta[detalleCuenta].7 }
												{if $bloquear == 's'} disabled {/if} 
											{/if}
											value="{$comboCuentaSuperior[cuentaSuperior].0}" 
											{if $comboCuentaSuperior[cuentaSuperior].0 == $datosCuenta[detalleCuenta].7 } 
												selected enabled
											{/if}> 
											{$comboCuentaSuperior[cuentaSuperior].2} 
											</option>
										{/section}
									</select>
								</div>
							<td>
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
								<input type="hidden" name="preLlenarCuenta" id="preLlenarCuenta" size="10" value="{$datosCuenta[detalleCuenta].8}"/>
								<input type="text" name="preLlenarCuentaName" id="preLlenarCuentaName" size="10" value="{$cuenta_contable_superior}"  {$text}  /> - 
								<input type="text" name="id_cuenta_contable" id="id_cuenta_contable" size="10" onKeyPress="borraValidacion('id_cuenta_contable','mensajeCuentaContable');" value="{$cuenta_contable}" {if $bloquear == 's'} {$text} {/if} /><div id="mensajeCuentaContable"></div>
								{/if}
								{if $tipoFormularioMostrar == 1}
								<input type="text" name="id_cuenta_contable" id="id_cuenta_contable" size="10" onKeyPress="borraValidacion('id_cuenta_contable','mensajeCuentaContable');" value="{$datosCuenta[detalleCuenta].1}" {if $bloquear == 's'} {$text} {/if} /><div id="mensajeCuentaContable"></div>
								{/if}
							</td>
							{if $tipoFormularioMostrar == 1}
								<td colspan="2">
									Niveles de la Cuenta:
									<select id="nivelesDeLaCuenta" name="nivelesDeLaCuenta" onChange="nivelDeLaCuenta();" {if $bloquear == 's'}disabled="disabled"{/if}>
										<option value="1" {if $datosCuenta[detalleCuenta].12 == 1}selected="selected"{/if}>1</option>
										<option value="2" {if $datosCuenta[detalleCuenta].12 == 2}selected="selected"{/if}>2</option>
										<option value="3" {if $datosCuenta[detalleCuenta].12 == 3}selected="selected"{/if}>3</option>
									</select>
								</td>
							{/if}
						</tr>
						<tr>
							<td>
								Nombre de la Cuenta: 
							</td>
							<td colspan="3">
								<input type="text" name="nombre_cuenta_contable" id="nombre_cuenta_contable" value="{$datosCuenta[detalleCuenta].2}" onKeyPress="borraValidacion('nombre_cuenta_contable','mensajeNombreCuentaContable');" size="70" /><div id="mensajeNombreCuentaContable"></div>
							</td>
						</tr>
						<tr>
							<td>
								Genero de Cuenta Contable
							</td>
							<td>
								{section name="generosCC" loop=$aGenerosCC}
									{if $aGenerosCC[generosCC].0 == $datosCuenta[detalleCuenta].5}
										<input type="hidden" name="id_genero_cuenta_contable_aux" id="id_genero_cuenta_contable_aux"  value="{$datosCuenta[detalleCuenta].5}" />
									{/if}
								{/section}
								<select name="id_genero_cuenta_contable" id="id_genero_cuenta_contable" onChange="verificaObligatorioCuentaContable();">
									<option  disabled value="0"> -- Selecciona un Genero -- </option>
									{section name="generosCC" loop=$aGenerosCC}
										<option value="{$aGenerosCC[generosCC].0}" 
											{if $aGenerosCC[generosCC].0 == $datosCuenta[detalleCuenta].5}
												selected
											{/if}
											disabled
											> 
												{$aGenerosCC[generosCC].1} 
										</option>
									{/section}
								</select>
								<div id="mensajeGeneroCuentaContable"></div>
							</td>
							<td>
								{if $tipoFormularioMostrar != 3}
									Cuenta SAT:
									<select id="cuentaSAT" name="cuentaSAT" >
										{section name="CuentaSAT" loop=$detalleCuentaSAT}
											<option value="{$detalleCuentaSAT[CuentaSAT].0}" selected>{$detalleCuentaSAT[CuentaSAT].1}</option>
										{/section}
									</select>
								{/if}
							</td>
						</tr>
						<tr>
							<td>
								¿Es Cuenta Mayor?
							</td>
							<td>
								<input type="checkbox"  {if $bloquear == 's'} disabled {/if} name="es_cuenta_mayor" id="es_cuenta_mayor" value="1" {if $datosCuenta[detalleCuenta].4 == 1} checked {/if} />
							</td>
						</tr>
						<tr>
							<td>
								¿Es Facturable? 
							</td>
							<td>
								<input type="checkbox"   disabled  {if $bloquear == 's'} disabled {/if} name="es_facturable" id="es_facturable" value="1" {if $datosCuenta[detalleCuenta].6 == 1} {$select_check} checked {/if}  />
							</td>
							<td>
								Visible en Arbol
							</td>
							<td>
								<input type="checkbox"  {if $bloquear == 's'} disabled {/if} name="visible_arbol" id="visible_arbol" value="1"  {if $datosCuenta[detalleCuenta].9 == 1} checked {/if} />
							</td>
						</tr>
						<tr>
							<td>
								Visible en Poliza
							</td>
							<td>
								<input type="checkbox"  {if $bloquear == 's'} disabled {/if} name="visible_poliza" id="visible_poliza" value="1" {if $datosCuenta[detalleCuenta].11 == 1} checked {/if} />
							</td>
							<td>
								Activo
							</td>
							<td>
								<input type="checkbox"  {if $bloquear == 's'} disabled {/if} name="activo" id="activo" value="1"  {if $datosCuenta[detalleCuenta].11 == 1} checked {/if} />
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td colspan="4" align="right">
								<input class="botonSecundario" type="button" onclick="registrarCuentaContable('editar')" value=" Editar »">
								<input type="hidden" name="tipoFormularioMostrar" id="tipoFormularioMostrar" value="{$tipoFormularioMostrar}">
							</td>
						</tr>
				</tbody>
		</table>
	{/section}
	</form>
	</body>
</html>