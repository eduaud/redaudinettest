<table border="0" align="center" class="listaCuentasNivel2">
		<tbody>
			{section name="ccn2" loop=$cuentasNivel2}
				{if $smarty.section.ccn2.iteration == 1}
				<tr>
					<td>&nbsp;</td>
				</tr>
				{/if}
				<tr>
					<td>
					
					{if $caso eq 'fancy'}
						<input type="checkbox" onclick="guardaCuentaContable(this.value,'{$id}','{$cuenta}')" value="{$cuentasNivel2[ccn2].0}">
					{/if}
						<a href="#" id="linkNivel2{$cuentasNivel2[ccn2].0}" style="text-decoration:none" onClick="mostrarCuentasNivel3('{$cuentasNivel2[ccn2].0}'{if $caso eq 'fancy'},'{$id}','{$cuenta}'{/if});"> * {$cuentasNivel2[ccn2].2}</a>
						<div id="cuentasNivel3{$cuentasNivel2[ccn2].0}" style="display:none">
						</div>
					</td>
				</tr>
				{if $smarty.section.ccn2.iteration != $smarty.section.ccn2.max}
				<tr>
					<td colspan="5">&nbsp;</td>
				</tr>
				{/if}
			{/section}
		</tbody>
</table>