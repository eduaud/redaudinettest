{include file="_header.tpl" pagetitle="$contentheader"}

  <div class="titulo-icono" id="titulo-icono">
	     <div class="titulo" id="titulo">{$nombre_menu} </div>
 </div>
<div class="buscador" id="buscador">
<form action="#" name="forma_permisos" method="post">
	<table >
		<tr>
			<td >
				{section loop=$menus_p name=indice start=0}
				
				
					<input type="hidden" name='ids_menu_[{$smarty.section.indice.iteration-1}]'  value = '{$menus_p[indice][0]}' />
					<input type="checkbox" name="checked_[{$smarty.section.indice.iteration-1}]" value="1"  > 
					{$menus_p[indice][1]}
					<br>
				{/section}
				</td>				
			<td><span class="campos">que&nbsp;</span>
		
			</td>
			<td><span class="campos">a&nbsp;</span><input type="text" name="valor" class="campos"/></td>
			<td><input type="button" name="ac_filtro" value="Filtrar" class="boton" onClick="filtro('{$t}', campo.value, operador.value, valor.value,'{$opExtra}','{$rooturl}')"/></td>
			<td><input type="button" name="no_filtro" value="Ver todos" class="boton" onClick="filtro('{$t}',null,null,null,'{$opExtra}','{$rooturl}')" /></td>
		</tr>
  </table>
</form>
 </div>


<div class="bot-tablaup" id="bot-tablaup">
	  <a href="inc/general/encabezados.php?t={$t}&k=&op=1"><img src="{$rooturl}imagenes/nuevo-it.jpg" alt="newart lab" width="32" height="41" border="0"></a>	  
  </div>
<div class="tabla" id="tabla">
<table>
	    
    <!-- Codigo Grid de Iv&aacute;n -->
    <tr>
    	<table id="listado" cellpadding="0" cellspacing="0" border="1" Alto="250"
               conScroll="S" validaNuevo="false" AltoCelda="25" auxiliar="0" ruta="img/" validaElimina="false" Datos="datosListados.php?t={$t}&stm={$stm}"
               verFooter="N" guardaEn="False" listado="S" class="tabla_Grid_RC">
            <tr class="HeaderCell">
            	{section loop=$headers name=x}	
					<td width="100" tipo="texto" modificable="N">{$headers[x]} </td>	
				{/section}                
                
            </tr>            
       </table> 
	  <script>	  	
	  	CargaGrid('listado');
      </script>
    </tr>
		
</table>
<br />
<br />


</div>


{include file="_footer.tpl" aktUser=$username}
