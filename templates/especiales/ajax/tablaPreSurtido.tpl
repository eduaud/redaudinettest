 <table id="table_cat_cia_consultar_presurtido">
    <tr>
      <th class="claseTH"><input type="hidden" name="accion" id="accion" value="actualizar" />
          <input type="hidden" name="id_orden_servicio" id="id_orden_servicio" value="" />

          <input type="hidden" name="realiza" id="realiza" value="realiza" />
                          Orden de Servicio</th>
      <th class="claseTH">Cliente</th>
      <th class="claseTH">Cotizaci&oacute;n</th>
      <th class="claseTH">Fecha de Realizaci&oacute;n</th>
      <th class="claseTH">Fecha de Entrega</th>
      <th class="claseTH">Detalle de Articulos</th>
       <th class="claseTH">Acci&oacute;n</th>
    </tr>
    {section loop=$registros name=indice start=0}
    <tr>
      <td class="nom_campo">{$registros[indice][0]}</td>
      <td class="nom_campo">{$registros[indice][2]}</td>
      <td class="nom_campo">{$registros[indice][1]}</td>
      <td class="nom_campo">{$registros[indice][3]}</td>
      <td class="nom_campo">{$registros[indice][4]}</td>
      <td class="claseParrafo" valign="top">
          <table id="detalle_consultar_presurtido" width="100%">
            <tr>
              <th class="claseTH">Articulo</th>
              <th class="claseTH">Descripci&oacute;n</th>
              <th class="claseTH">Cantidad Solicitada</th>
              <th class="claseTH">Cantidad pendiente por entregar</th>
              <th class="claseTH">Existencia</th>
			  <th class="claseTH">Surtir</th>
            </tr>
            {section loop=$registros[indice][5] name=indice2 start=0}
            <tr valign="top">
              <td class="nom_campo">
				<input type="hidden" name="{$registros[indice][0]}id_detalle{$smarty.section.indice2.index}" id="{$registros[indice][0]}id_detalle{$smarty.section.indice2.index}" size="2" value={$registros[indice][5][indice2][0]} />
				<input type="hidden" name="{$registros[indice][0]}id_articulo{$smarty.section.indice2.index}" id="{$registros[indice][0]}id_articulo{$smarty.section.indice2.index}" size="2" value={$registros[indice][5][indice2][2]} />
				{$registros[indice][5][indice2][3]}
			</td>
			  <input type="hidden" name="{$registros[indice][0]}descripcion{$smarty.section.indice2.index}" id="{$registros[indice][0]}descripcion{$smarty.section.indice2.index}" size="2" value='{$registros[indice][5][indice2][4]}' />
              <td class="nom_campo">{$registros[indice][5][indice2][4]}</td>
              <td class="nom_campo">
				{$registros[indice][5][indice2][5]}
				<input type="hidden" name="{$registros[indice][0]}cantidad_solicitada{$smarty.section.indice2.index}" id="{$registros[indice][0]}cantidad_solicitada{$smarty.section.indice2.index}" size="2" value={$registros[indice][5][indice2][5]} />
			  </td>
              <td class="nom_campo">
					<input type="hidden" name="{$registros[indice][0]}pendiente_entregar{$smarty.section.indice2.index}" id="{$registros[indice][0]}pendiente_entregar{$smarty.section.indice2.index}" size="2" value={$registros[indice][5][indice2][8]} />
					{$registros[indice][5][indice2][8]}
			</td>
			  <td class="claseParrafo" valign="top">
				{$registros[indice][5][indice2][6]}
				<input type="hidden" name="{$registros[indice][0]}existencia{$smarty.section.indice2.index}" id="{$registros[indice][0]}existencia{$smarty.section.indice2.index}" size="2" value={$registros[indice][5][indice2][6]} />
			  </td>
			  </td>
			  <td class="claseParrafo" valign="top">
				<input type="text" name="{$registros[indice][0]}cantidad_surtir{$smarty.section.indice2.index}" id="{$registros[indice][0]}cantidad_surtir{$smarty.section.indice2.index}" size="4" />
				{assign var="no_productos_orden" value=$smarty.section.indice2.index}
			  </td> 
            </tr>
            {/section}
          </table>
      </td>
	  <td>
		<input name="btnReporte" type="button" class="boton" value="Generar Salida      &raquo;" onClick="generarSalida({$no_productos_orden},'{$registros[indice][0]}');" />
	  </td>
    </tr>
	<tr>
			<td colspan ="7"><hr></td>
	</tr>
	{/section}
  </table>