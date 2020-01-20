<table border="0" align="center"  class="listaCuentasNivel3">
		<tbody>
			{section name="ccn3" loop=$cuentasNivel3}
				{if $smarty.section.ccn3.iteration == 1}
				<tr>
					<td>&nbsp;</td>
				</tr>
				{/if}
				<tr>
					<td>
					{if $caso eq 'fancy'}
						<input type="checkbox" onclick="guardaCuentaContable(this.value,'{$id}','{$cuenta}')" value="{$cuentasNivel3[ccn3].0}">
					{/if}
						<a href="#" style="text-decoration:none" onClick="displayDivDetalleFormulario('{$cuentasNivel3[ccn3].0}'{if $caso eq 'fancy'},'{$id}','{$cuenta}'{/if});"> - {$cuentasNivel3[ccn3].1} </a>
					</td>
					<td>&nbsp;</td>
				</tr>
				{if $smarty.section.ccn3.iteration != $smarty.section.ccn3.max}
				<tr>
					<td colspan="5">&nbsp;</td>
				</tr>
				{/if}
			{/section}
		</tbody>
</table>