<table border="0" align="center" class="listaCuentasNivel1">
		<tbody>
			{section name="ccn1" loop=$cuentasNivel1}
				<tr>
					<td align="left">
						<a href="#" onClick="mostrarCuentasNivel2('{$cuentasNivel1[ccn1].2}');"><b>+ {$cuentasNivel1[ccn1].1}</b></a>
						<div id="cuentasNivel2{$cuentasNivel1[ccn1].0}" style="display:none">
						</div>
					</td>
				</tr>
				{if $smarty.section.ccn1.iteration != $smarty.section.ccn1.max}
				<tr>
					<td>&nbsp;</td>
				</tr>
				{/if}
			{/section}
		</tbody>
</table>