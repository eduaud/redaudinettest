{assign var="contador" value="1"}
		{section name="filasCXP" loop=$filas}
				<tr>
						<td style="width:20px; text-align:center">{$contador}</td>
						<td style="width:18px; text-align:center"><input type="checkbox" id="idCXPCheck{$contador}" value="{$filas[filasCXP].0}" name="idCXPCheck[]"/></td>
						<td style="width:150px; text-align:center">{$filas[filasCXP].0} / {$filas[filasCXP].8}</td>
						<td style="width:150px; text-align:center">{$filas[filasCXP].1}</td>
						<td style="width:250px; text-align:center">{$filas[filasCXP].2} / {$filas[filasCXP].7}</td>
						<td style="width:150px; text-align:center">{$filas[filasCXP].3}</td>
						<td style="width:150px; text-align:center">{$filas[filasCXP].4}</td>
						<td style="width:150px; text-align:center">{$filas[filasCXP].5}</td>
						<td style="width:100px; text-align:center">
								<input style="width:70px; font-size:10px; text-align:right" id="montoCXP{$filas[filasCXP].0}" type="text" onkeydown="return noletras(event);" value='{$filas[filasCXP].5|regex_replace:"/[$]/":""}'/>
						</td>
						<td style="display:none"><input id="idProvD{$filas[filasCXP].0}" type="hidden" value="{$filas[filasCXP].6}"/></td>
						<td style="display:none">{$contador++}</td>
					</tr>
		{sectionelse}
				<tr>
						<td width="1208">{*$sql*}<div align="center">No existen ordenes con estos datos de busqueda</div></td>
				</tr>
		{/section}