{if $tipoPantalla eq "1"}
	{include file="_header.tpl" pagetitle="$contentheader"}
    <script language="JavaScript" type="text/javascript" src="{$rooturl}js/almacen_salidas.js"></script>
    {literal}
    	<style>
			td{
				font-size:12px;
				padding:10px;
			}
		</style>
    {/literal}
{/if}

<script language="javascript" src="{$rooturl}js/jquery-1.8.3.js"></script>

{if $tipoPantalla eq "1"}
<br /><br />

<a href="" class="thickbox" id="thickbox_href"></a>  

<h1>{$nombre_menu}</h1>

<div style="padding-top:20px; padding-bottom:20px">
	<div class="m_modificar_catalogo m_modificar_catalogo">
		
		<table width="935" border="0" cellpadding="0" cellspacing="0" id="form">
			<tr>
				<td class="form_labels">
					Tipo de salida:
				</td>
				<td>
					<span class="camporequerido">
					<select name="c_categorias" class="select_filtro" id="tipoEntrada">
                        <option value="0" selected="selected"> - Seleccione tipo de salida - </option>
                        {html_options values=$arrysIdAlm output=$arrysNombreAlm selected=$idAlmSel }
                    </select>
					*</span>
				</td>
				<td width="173" class="form_labels">&nbsp;</td>
				<td width="303">&nbsp;</td>
			</tr>
			<tr>
				<td class="form_labels">
					Documento de salida:
				</td>
				<td>
					<span class="camporequerido">
					<input name="textfield41" type="text" class="inputfield" id="idDocBuscado" size="25" value="36545554" />
					*</span>
				</td>
				<td width="173" class="form_labels">&nbsp;</td>
				<td width="303">&nbsp;</td>
			</tr>
			<tr>
				<td width="228" class="form_labels"><p>&nbsp;</p></td>
				<td width="500"><input type="button" name="modificar" id="buscarDatos" value=" Buscar &raquo;" class="botonSecundario">	</td>
				<td width="173" class="form_labels">&nbsp;</td>
				<td width="303">&nbsp;</td>
			</tr>
		</table>

	</div>
    
    
    
    <div class="m_modificar_catalogo m_modificar_catalogo" id="contenido" style="display:none">
    	<br />
        <b id="infoEntrada"></b>
		<h2>Salida: </h2>

		<table border="0" cellpadding="0" cellspacing="0" id="table_cat_cia">
			<tr>
				<th width="100" scope="col"><p>Producto</p></th>
				<th width="450" scope="col"><p>Descripci&oacute;n</p></th>
				<th width="150" scope="col"><p>Cantidad a recibir</p></th>
				<th width="150" scope="col"><p>Cantidad recibida</p></th>
				<th width="150" scope="col"><p>Agregar</p></th>
			</tr>
			<tbody id="bodySolicitado">
				<tr>
					<td><p>Producto 1</p></td>
					<td><p>Producto de medidas 3 x 2</p></td>
					<td><p>3</p></td>
					<td><p>1</p></td>
					<td class="table_align_center_icon"><p><a href="admin_modificar_proyecto_cia.html"><img src="{$rooturl}imagenes/iconos/btn_ic_new.png" height="14" alt="modificar" /></a></p></td>
				</tr>
			</tbody>
		</table>

		<h2>Cantidad entregada: </h2>
		<table border="0" cellpadding="0" cellspacing="0" id="table_cat_cia">
			<tr>
				<th width="150" scope="col"><p>SKU</p></th>
				<th width="350" scope="col"><p>Producto</p></th>
				<th width="100" scope="col"><p>Ubicaci&oacute;n</p></th>
				<th width="150" scope="col"><p>Cantidad entregada</p></th>
				<th width="150" scope="col"><p>Lote</p></th>
				<th width="100" scope="col"><p>Modificar</p></th>
				<th width="100" scope="col"><p>Borrar</p></th>
			</tr>
			<tbody id="bodyRecibido" indice="0">
				<tr>
					<td>Producto 1</td>
					<td>1-A</td>
					<td>1</td>
					<td>0002S</td>
					<td class="table_align_center_icon"><p><a href="admin_modificar_proyecto_cia.html"><img src="{$rooturl}imagenes/iconos/ic18x18_modificar2.png" width="18" height="18" alt="modificar" /></a></p></td>
					<td class="table_align_center_icon"><p><a href="admin_modificar_proyecto_cia.html"><img src="{$rooturl}imagenes/iconos/ic18x18_eliminar.png" width="18" height="18" alt="modificar" /></a></p></td>
				</tr>
			</tbody>
		</table>
		
        
        <table width="952" border="0" cellpadding="0" cellspacing="0" id="table_btns">
            <tr>
                <td width="28" height="59">
                    <input type="button" value=" Guardar &raquo;" onclick="guardarCambios()" class="botonSecundario">	
                </td>
                <td width="137"></td>
                <td width="120"></td>
                <td width="141">&nbsp;</td>
                <td width="391"><p>&nbsp;</p></td>
                <td width="142"></td>
            </tr>
        </table>
	</div>
</div>

{include file="_footer.tpl" aktUser=$username}

{else if $tipoPantalla eq "2"}
<script language="JavaScript" type="text/javascript" src="{$rooturl}js/almacen_salidasPop.js"></script>
<input type="hidden" id="id_producto" value="{$id_producto}" />
<input type="hidden" id="producto" value="{$arryDatos[0][1]}" />
<input type="hidden" id="id_entrada" value="{$id_entrada}" />
<input type="hidden" id="id_entrada_solicitado" value="{$id_entrada_solicitado}" />
<input type="hidden" id="id_origen_dest" value="{$id_origen_dest}" />
<input type="hidden" id="val1" value="{$val1}" />
<input type="hidden" id="val2" value="{$val2}" />
<input type="hidden" id="sku" value="{$arryDatos[0][0]}" />
<input type="hidden" id="fila" value="{$fila}" />
<input type="hidden" id="similar" value="{$arryDatos[0][4]}" />
<input type="hidden" id="valCantidadTemp" value="{$valCantidad}" />


<h1>Producto: <b>{$arryDatos[0][1]}</b> </h1>
SKU:  <b>{$arryDatos[0][0]}</b> <br />
Unidad de medida:  <b>{$arryDatos[0][3]}</b> <br />
Solicitado: <b>{$val1}</b><br />
Recibido: <b>{$val2}</b><br />
{if $arryDatos[0][4] eq "1"}
Es un producto miscelaneo
{else}
No es un producto miscelaneo
{/if}
<br />

<br />
{if $arryDatosMiscId[0] neq ""}
Producto similar: <br />
<select id="valSimilar">
    <option value="0"> - Seleccione un similar - </option>
    {html_options values=$arryDatosMiscId output=$arryDatosMiscVal selected=$valSimilar }
</select><br /><br />
{/if}

Cantidad: <br />
<input type="text" id="valCantidad" value="{$valCantidad}" /><br /><br />

{if $arryDatos[0][2] eq "1"}
    Lote: <br />
    <input type="text" id="valLote" value="{$valLote}" /><br /><br />
{else}
	<input type="hidden" id="valLote" value="" />
{/if}

Ubicación: <br />
<select id="valUbicacion">
    <option value="0"> - Seleccione una ubicación - </option>
    {html_options values=$arryDatos2A output=$arryDatos2B selected=$valUbicacion }
</select><br /><br />

<!--Documento de la entrega:<br />
<input type="text" id="valDoc" value="{$valDoc}" /><br /><br />-->

{if $esLlanta eq "1"}
    Clasificación Llanta:<br />
    <select id="clasifLlanta">
        <option value="0"> - Seleccione una ubicación - </option>
        {html_options values=$arryDatos3A output=$arryDatos3B selected=$clasifLlanta }    
    </select>
{/if}

<input type="button" id="agregarItem" value=" Agregar elementos " />

{/if}