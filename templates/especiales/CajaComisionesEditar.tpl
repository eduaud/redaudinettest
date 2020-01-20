{section name="Caja" loop=$a_cajas}
	<!--<td id="0" width="100px">{$a_cajas[Caja].1}</td>
	<td id="1" width="100px">{$a_cajas[Caja].2}</td>
	<td id="2" width="100px">{$a_cajas[Caja].3}</td>
	<td id="3" width="100px">{$a_cajas[Caja].4}</td>
	<td id="4" width="100px">{$a_cajas[Caja].5}</td>
	<td id="5" width="100px">{$a_cajas[Caja].6}</td>
	<td id="6" width="100px">{$a_cajas[Caja].7}</td>
	<td id="7" width="100px">{$a_cajas[Caja].8}</td>-->
	<td id="8">{if $accion eq '1'}<input type="text" id="PrecioPublico_{$a_cajas[Caja].0}" value='{$a_cajas[Caja].9|string_format:"%.2f"}' class="select-input2"/>
	{else}{$a_cajas[Caja].9|string_format:"%.2f"}{/if}</td>
	<td id="9">{if $accion eq '1'}<input type="text" id="Instalacion_{$a_cajas[Caja].0}" value='{$a_cajas[Caja].10|string_format:"%.2f"}' class="select-input2"/>{else}{$a_cajas[Caja].10|string_format:"%.2f"}{/if}</td>
	<td id="10">{if $accion eq '1'}<input type="text" id="Promocion_{$a_cajas[Caja].0}" value='{$a_cajas[Caja].11|string_format:"%.2f"}' class="select-input2"/>{else}{$a_cajas[Caja].11|string_format:"%.2f"}{/if}</td>
	<td id="11">{if $accion eq '1'}<input type="text" id="ServInstalacion_{$a_cajas[Caja].0}" value='{$a_cajas[Caja].12|string_format:"%.2f"}' class="select-input2"/>{else}{$a_cajas[Caja].12|string_format:"%.2f"}{/if}</td>
	<td id="12">{if $accion eq '1'}<input type="text" id="Complemento_{$a_cajas[Caja].0}" value='{$a_cajas[Caja].13|string_format:"%.2f"}' class="select-input2"/>{else}{$a_cajas[Caja].13|string_format:"%.2f"}{/if}</td>
	<td id="13" width="100px">{$a_cajas[Caja].14|string_format:"%d"}</td>
	<td id="14">{if $accion eq '1'}<input type="text" id="DerechoActivacion_{$a_cajas[Caja].0}" value='{$a_cajas[Caja].15|string_format:"%.2f"}' class="select-input2"/>{else}{$a_cajas[Caja].15|string_format:"%.2f"}{/if}</td>
	<td id="15">{if $accion eq '1'}<input type="text" id="TotalGanar_{$a_cajas[Caja].0}" value='{$a_cajas[Caja].16|string_format:"%.2f"}' class="select-input2"/>{else}{$a_cajas[Caja].16|string_format:"%.2f"}{/if}</td>
	<td id="16">{if $accion eq '1'}<input type="text" id="Accesorio_{$a_cajas[Caja].0}" value='{$a_cajas[Caja].17|string_format:"%.2f"}' class="select-input2"/>{else}{$a_cajas[Caja].17|string_format:"%.2f"}{/if}</td>
	<td id="17">{if $accion eq '1'}<input type="text" id="DIST_{$a_cajas[Caja].0}" value='{$a_cajas[Caja].18|string_format:"%.2f"}' class="select-input2"/>{else}{$a_cajas[Caja].18|string_format:"%.2f"}{/if}</td>
	<td id="18">{if $accion eq '1'}<input type="text" id="Audicel_{$a_cajas[Caja].0}" value='{$a_cajas[Caja].19|string_format:"%.2f"}' class="select-input2"/>{else}{$a_cajas[Caja].19|string_format:"%.2f"}{/if}</td>
	<td id="19">{if $accion eq '1'}<input type="text" id="Total_{$a_cajas[Caja].0}" value='{$a_cajas[Caja].20|string_format:"%.2f"}' class="select-input2"/>{else}{$a_cajas[Caja].20|string_format:"%.2f"}{/if}</td>
	<td id="20">{if $accion eq '1'}<input type="text" id="TotalDistribuidor_{$a_cajas[Caja].0}" value='{$a_cajas[Caja].21|string_format:"%.2f"}' class="select-input2"/>{else}{$a_cajas[Caja].21|string_format:"%.2f"}{/if}</td>
	<td id="21">{if $accion eq '1'}<input type="text" id="TotalDISCOM_{$a_cajas[Caja].0}" value='{$a_cajas[Caja].22|string_format:"%.2f"}' class="select-input2"/>{else}{$a_cajas[Caja].22|string_format:"%.2f"}{/if}</td>
	<td id="22">{if $accion eq '1'}<input type="text" id="TotalTECEXT_{$a_cajas[Caja].0}" value='{$a_cajas[Caja].23|string_format:"%.2f"}' class="select-input2"/>{else}{$a_cajas[Caja].23|string_format:"%.2f"}{/if}</td>
	<td id="23">{if $accion eq '1'}<input type="text" id="TotalTECFP_{$a_cajas[Caja].0}" value='{$a_cajas[Caja].24|string_format:"%.2f"}' class="select-input2"/>{else}{$a_cajas[Caja].24|string_format:"%.2f"}{/if}</td>
	<td id="24">{if $accion eq '1'}<input type="text" id="TotalVendedorEXT_{$a_cajas[Caja].0}" value='{$a_cajas[Caja].25|string_format:"%.2f"}' class="select-input2"/>{else}{$a_cajas[Caja].25|string_format:"%.2f"}{/if}</td>
	<td id="25">{if $accion eq '1'}<input type="text" id="TotalVendedorFP_{$a_cajas[Caja].0}" value='{$a_cajas[Caja].26|string_format:"%.2f"}' class="select-input2"/>{else}{$a_cajas[Caja].26|string_format:"%.2f"}{/if}</td>
	<td id="26">{if $accion eq '1'}<input type="text" id="DescuentoCliente_{$a_cajas[Caja].0}" value='{$a_cajas[Caja].27|string_format:"%.2f"}' class="select-input2"/>{else}{$a_cajas[Caja].27|string_format:"%.2f"}{/if}</td>
	<td id="27">{if $accion eq '1'}<input type="text" id="DescuentoFP_{$a_cajas[Caja].0}" value='{$a_cajas[Caja].28|string_format:"%.2f"}' class="select-input2"/>{else}{$a_cajas[Caja].28|string_format:"%.2f"}{/if}</td>
	<td id="28" width="100px">{$a_cajas[Caja].29}</td>
	<td style="width:15px !important" id="29">
		{if $accion eq '1'}<img src="{$rooturl}imagenes/general/Save.png" onclick="GuardarCaja({$a_cajas[Caja].0})" onmouseover="this.style.cursor='hand';this.style.cursor='pointer';" title="Guardar Registro"/>
		{else}<img src="{$rooturl}imagenes/general/modificar.png" onclick="EditarCaja({$a_cajas[Caja].0},'1')" onmouseover="this.style.cursor='hand';this.style.cursor='pointer';" title="Modificar Registro"/>{/if}
	</td>
{/section}
