<table border="0" align="center"  class="listaCuentasNivel3">
		<tbody>
			{section name="ccn3" loop=$cuentasNivel3}
				{if $smarty.section.ccn3.iteration == 1}
				<tr height="10px">
					<td></td>
				</tr>
				{/if}
				<tr height="10px">
					<td>
						<input type="radio" name="id_cuenta_contable_radio" id="id_cuenta_contable_radio" value="{$cuentasNivel3[ccn3].0}" onClick="displayDivDetalleFormularioSeleccion('{$cuentasNivel3[ccn3].0}','{$cuentasNivel3[ccn3].0}');" />
						<a href="#" style="text-decoration:none" onClick="displayDivDetalleFormularioSeleccion('{$cuentasNivel3[ccn3].0}','{$cuentasNivel3[ccn3].0}');"> - {$cuentasNivel3[ccn3].2} </a>
					</td>
				</tr>
				{if $smarty.section.ccn3.iteration == $smarty.section.ccn3.max}
				<tr>
					<td></td>
				</tr>
				{/if}
			{/section}
		</tbody>
</table>