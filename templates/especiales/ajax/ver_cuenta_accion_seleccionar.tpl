{section name="detalleCuenta"}
<table border="0" class="tableFormCuentasContables">
				<tbody>
						{if $datosCuenta[detalleCuenta].3 == 2 || $datosCuenta[detalleCuenta].3 == 3}
						<tr>
							<td>Cuenta Mayor:</td>
							<td>
								<select name="id_cuenta_mayor" id="id_cuenta_mayor">
									<option value="0" disabled="disabled"> -- Seleccionar -- </option>
									{section name="cm" loop=$aCuentasMayores}
										<option disabled="disabled" value="{$aCuentasMayores[cm].0}" {if $aCuentasMayores[cm].0 == $datosCuenta[detalleCuenta].8 } selected {/if}> 
											{$aCuentasMayores[cm].2} 
										</option>
									{/section}
								</select>
							</td>
						</tr>
						{/if}
						<tr>
							<td>
								<input type="hidden" name="nivel_cuenta_contable" id="nivel_cuenta_contable" value="{$datosCuenta[detalleCuenta].3}" />
							</td>
						</tr>
						{if $datosCuenta[detalleCuenta].3 == 3}
						<tr>
							<td>Cuenta Superior:</td>
							<td>
								<select name="id_cuenta_superior" id="id_cuenta_superior">
									<option value="0" disabled="disabled"> -- Selecciona Cuenta Superior -- </option>
									{section name="cs" loop=$aCuentasSuperiores}
										<option disabled="disabled" value="{$aCuentasSuperiores[cs].0}" {if $aCuentasSuperiores[cs].0 == $datosCuenta[detalleCuenta].7 } selected {/if}> 
											{$aCuentasSuperiores[cs].2} 
										</option>
									{/section}
								</select>
							</td>
						</tr>
						{/if}
						<tr>
							<td>
								Cuenta Contable: 
							</td>
							<td colspan="4">
								<input type="text" name="id_cuenta_contable" id="id_cuenta_contable" size="15" value="{$datosCuenta[detalleCuenta].1}" readonly />
							</td>
						</tr>
						<tr>
							<td>
								Nombre de la Cuenta: 
							</td>
							<td colspan="3">
								<input type="text" name="nombre_cuenta_contable" id="nombre_cuenta_contable" size="70" value="{$datosCuenta[detalleCuenta].2}" readonly />
							</td>
						</tr>
						<tr>
							<td>
								Genero de Cuenta Contable
							</td>
							<td>
								<select name="id_genero_cuenta_contable" id="id_genero_cuenta_contable">
									<option disabled="disabled" value="0"> -- Selecciona un Genero -- </option>
									{section name="generosCC" loop=$aGenerosCC}
										<option disabled="disabled" value="{$aGenerosCC[generosCC].0}" {if $aGenerosCC[generosCC].0 == $datosCuenta[detalleCuenta].5} selected {/if}> 
											{$aGenerosCC[generosCC].1} 
										</option>
									{/section}
								</select>
							</td>
						</tr>
						<tr>
							<td>
								¿Es Cuenta Mayor?
							</td>
							<td>
								<input type="text" name="es_cuenta_mayor" id="es_cuenta_mayor" size="5" readonly {if $datosCuenta[detalleCuenta].4 == 1} value="SI" {else} value="NO" {/if} />
							</td>
							<td>
								¿Es Facturable? 
							</td>
							<td>
								<input type="text" name="es_facturable" id="es_facturable" size="5" readonly {if $datosCuenta[detalleCuenta].6 == 1} value="SI" {else} value="NO" {/if} />
							</td>
						</tr>
						<tr>
							<td>
								Visible en Arbol
							</td>
							<td>
								<input type="text" name="visible_arbol" id="visible_arbol" size="5" readonly {if $datosCuenta[detalleCuenta].9 == 1} value="SI" {else} value="NO" {/if} />
							</td>
							<td>
								Visible en Poliza
							</td>
							<td>
								<input type="text" name="visible_arbol" id="visible_arbol" size="5" readonly {if $datosCuenta[detalleCuenta].10 == 1} value="SI" {else} value="NO" {/if} />
							</td>
						</tr>
						<tr>
							<td>
								Activo
							</td>
							<td>
								<input type="text" name="activo" id="activo" size="5" readonly {if $datosCuenta[detalleCuenta].11 == 1} value="SI" {else} value="NO" {/if} />
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td align="right">
								&nbsp;
							</td>
							<td align="right">
								{if $mostrarBoton == "s"}
									<input class="botonSecundario" type="button" onclick="seleccionarCuentaContable('{$datosCuenta[detalleCuenta].0}','{$datosCuenta[detalleCuenta].1}','{$datosCuenta[detalleCuenta].2}')" value=" Seleccionar »" />
								{/if}
							</td>
						</tr>
				</tbody>
		</table>
{/section}