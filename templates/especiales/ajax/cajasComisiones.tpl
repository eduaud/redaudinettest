<input type="hidden" value="{$registros}" id="registros">
{section name="Caja" loop=$a_cajas}
	{if $caso eq '1'}
	<tr id="fila_{$a_cajas[Caja].0}">
		<td id="0">{$a_cajas[Caja].1}</td>
		<td id="1">{$a_cajas[Caja].2}</td>
		<td id="2">{$a_cajas[Caja].3}</td>
		<td id="3">{$a_cajas[Caja].4}</td>
		<td id="4">{$a_cajas[Caja].5}</td>
		<td id="5">{$a_cajas[Caja].6}</td>
		<td id="6">{$a_cajas[Caja].7}</td>
		<td id="7">{$a_cajas[Caja].8}</td>
	</tr>
	{elseif $caso eq '2'}
	<tr id="fila_x_{$a_cajas[Caja].0}">
		<td id="8">{$a_cajas[Caja].9}</td>
		<td id="9">{$a_cajas[Caja].10}</td>
		<td id="10">{$a_cajas[Caja].11}</td>
		<td id="11">{$a_cajas[Caja].12}</td>
		<td id="12">{$a_cajas[Caja].13}</td>
		<td id="13">{$a_cajas[Caja].14}</td>
		<td id="14">{$a_cajas[Caja].15}</td>
		<td id="15">{$a_cajas[Caja].16}</td>
		<td id="16">{$a_cajas[Caja].17}</td>
		<td id="17">{$a_cajas[Caja].18}</td>
		<td id="18">{$a_cajas[Caja].19}</td>
		<td id="19">{$a_cajas[Caja].20}</td>
		<td id="20">{$a_cajas[Caja].21}</td>
		<td id="21">{$a_cajas[Caja].22}</td>
		<td id="22">{$a_cajas[Caja].23}</td>
		<td id="23">{$a_cajas[Caja].24}</td>
		<td id="24">{$a_cajas[Caja].25}</td>
		<td id="25">{$a_cajas[Caja].26}</td>
		<td id="26">{$a_cajas[Caja].27}</td>
		<td id="27">{$a_cajas[Caja].28}</td>
		<td id="28">{$a_cajas[Caja].29}</td>
		<td style="width:15px !important" id="29">
			<img src="{$rooturl}imagenes/general/modificar.png" onclick="EditarCaja({$a_cajas[Caja].0},'1')" onmouseover="this.style.cursor='hand';this.style.cursor='pointer';" title="Modificar Registro"/>
		</td>
	</tr>
	{/if}
{/section}