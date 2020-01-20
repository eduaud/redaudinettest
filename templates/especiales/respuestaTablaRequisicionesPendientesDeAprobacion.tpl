				{assign var="contador" value="1"}
					{section name="fila" loop=$filas}
						<tr>
							<td style="width:20px; text-align:center">{$contador}{*$sql*}</td>
							<td style="width:200px; text-align:center">{$filas[fila].0}</td>
							<td style="width:100px; text-align:center"><a href="#" onclick="verRequisicion({$filas[fila].1});">{$filas[fila].1}</a></td>
							<td style="width:150px; text-align:center">{$filas[fila].2}</td>
							<td style="width:100px; text-align:center">{$filas[fila].3}</td>
							<td style="width:350px; text-align:center">{$filas[fila].4}</td>
							<td style="width:255px; text-align:center">
								<div style="width : auto">
									<input type="button" id="aprobar" class="small button grey" value="Aprobar" onClick="apruebaRequisicion({$contador});"/>
									<input type="button" id="rechazar" class="small button grey" value="Rechazar" onClick="rechazaRequisicion({$contador});"/><br />
									<textarea style="width:150px; height:20px;" id="motivo-rechazo{$contador}" class="rechazo"></textarea>
								</div>		
							</td>
							<td style="display:none"><input id="idRequisicion{$contador}" type="hidden" value="{$filas[fila].1}"/></td>
							<td style="display:none">{$contador++}</td>
						</tr>
					{sectionelse}
					<tr>
						<td width="1205" align="center">{*$sql*}No existen &oacute;rdenes pendientes de aprobaci&oacute;n con estos criterios de b&uacute;squeda</td>
					</tr>
				{/section}