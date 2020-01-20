{include file="_header.tpl" pagetitle="$contentheader"}

<div>
	<h1 class="encabezado">&nbsp;&nbsp;Biblioteca de Archivos</h1>
</div>

<div align="center" style="">
	<form action="descargar_zip.php" method="post" target="_blank">
		<table>
			{section name=archivo loop=$arr_archivos step=2}
				{assign var="siguiente" value=$smarty.section.archivo.index+1}
				<tr>
					<td><p>{$arr_archivos[archivo].0}</p></td>
					<td>
						<a href="{$directorio}/{$arr_archivos[archivo].0}" target="_blank" download>
							<img src="{$arr_archivos[archivo].1}" alt="Descargar Archivo" style="width: 25px; height: 30px; padding-left: 20px;">
						</a>
					</td>
					
					<td style="padding-left: 55px;"><p>{$arr_archivos[$siguiente].0}</p></td>
					<td>
						{if $arr_archivos[$siguiente].0 != ""}
							<a href="{$directorio}/{$arr_archivos[$siguiente].0}" target="_blank" download>
								<img src="{$arr_archivos[$siguiente].1}" alt="Descargar Archivo" style="width: 25px; height: 30px; padding-left: 20px;">
							</a>
						{/if}
					</td>
				</tr>
			{/section}
			<!--
			<tr>
				<td colspan="4" style="text-align: right; padding-top: 30px;">
					<input type="submit" name="descargarTodo" value="Descargar Todo" class="boton">
				</td>
			</tr>
			-->
		</table>
	</form>
</div>
	
{include file="_footer.tpl"}