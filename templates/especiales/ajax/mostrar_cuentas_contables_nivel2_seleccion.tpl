<table border="0" align="center" class="listaCuentasNivel2">
		<tbody>
			{section name="ccn2" loop=$cuentasNivel2}
				{if $smarty.section.ccn2.iteration == 1}
				<tr height="10px">
					<td></td>
				</tr>
				{/if}
				<tr height="10px">
					<td>
						{section name="mostrarRadio" loop=$agregarRadioButton}
							{if $agregarRadioButton[mostrarRadio].0 == $cuentasNivel2[ccn2].0 AND $agregarRadioButton[mostrarRadio].1 == "SI"}
								<input type="radio" name="id_cuenta_contable_radio" id="id_cuenta_contable_radio" value="{$cuentasNivel2[ccn2].0}" onClick="mostrarCuentasNivel3Seleccion('{$cuentasNivel2[ccn2].0}','{$cuentasNivel2[ccn2].0}','s');" />
								<a href="#" id="linkNivel2{$cuentasNivel2[ccn2].0}" style="text-decoration:none" onClick="mostrarCuentasNivel3Seleccion('{$cuentasNivel2[ccn2].0}','{$cuentasNivel2[ccn2].0}','s');"> {$cuentasNivel2[ccn2].2}</a>
							{/if}
							{if $agregarRadioButton[mostrarRadio].0 == $cuentasNivel2[ccn2].0 AND $agregarRadioButton[mostrarRadio].1 == "NO"}
								+ <a href="#" id="linkNivel2{$cuentasNivel2[ccn2].0}" style="text-decoration:none" onClick="mostrarCuentasNivel3Seleccion('{$cuentasNivel2[ccn2].0}','{$cuentasNivel2[ccn2].0}','n');"> {$cuentasNivel2[ccn2].1}</a>
							{/if}
						{/section}
						<div id="cuentasNivel3{$cuentasNivel2[ccn2].0}" style="display:none">
						</div>
					</td>
				</tr>
				{if $smarty.section.ccn2.iteration != $smarty.section.ccn2.max}
				<tr height="10">
					<td></td>
				</tr>
				{/if}
			{/section}
		</tbody>
</table>