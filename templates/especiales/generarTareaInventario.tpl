{include file="_header.tpl" pagetitle="$contentheader"}

<br /><br />
<a href="" class="thickbox" id="thickbox_href"></a>  
<h1>{$nombre_menu}</h1>


{if $pantalla eq "listado"}
	<div class="buscador" id="buscador">
		<form name="forma_filtro" action="#b" method="" onsubmit="return false" >
			<input type="hidden" value="{$cadP1}" name="cadP1" id="cadP1"/>
            <input type="hidden" value="{$cadP2}" name="cadP2" id="cadP2"/>
            <input type="hidden" value="{$t}" name="t" id="t"/>
            <input type="hidden" value="{$rooturl}" name="rooturl" id="rooturl"/>
            
            <table>
                <tr>
                	<td>
						<p>Seleccionar campo:</p>
                    </td>
                    <td>
                        <input name="tcr" type="hidden" value="{$tcr}" />
                        <select class="campos" name="campo" title="Seleccionar Campo para Filtrar">
                            {html_options values=$valuesFiltro output=$outputsFiltro}    
                            <option value="a.unidad_medida">a.unidad_medida</option>                        
                        </select>
                    </td>
                    <td>
                    	<p> &nbsp; que &nbsp;</p>
                    </td>
                    <td>
                        <select class="campos" name="operador" title="Seleccione una Condicion para Filtrar.">
							<option value="contiene">contiene a...</option>
                            <option value="=">es igual a(&#61;)</option>
                            <option value="!=">es diferente de(&ne;)</option>
                            <option value=">">es mayor que (&gt;)</option>
                            <option value="<">es menor que (&lt;)</option>
                            <option value=">=">es mayor o igual que (&ge;)</option>
                            <option value="<=">es menor o igual que (&le;)</option>
                            <option value="empieza">empieza con </option>                            
                        </select>
                    </td>
                    <td>
                   		<p>&nbsp; </p>
                    </td>
                    <td>
                        <input type="text" name="valor" class="campos" title="Asigne un valor para Filtrar." onkeydown="teclaEnter(event, '{$t}&vpe={$vpe}&npe={$npe}&mpe={$mpe}&epe={$epe}&ipe={$ipe}&gpe={$gpe}', campo.value, operador.value, valor.value,'{$opExtra}','{$rooturl}','{$tcr}', fecdel.value, fecal.value)"/>
                    </td>            
                    <td>&nbsp;&nbsp;&nbsp;
                        <input type="button" name="ac_filtro" value="Filtrar" class="boton" onClick="filtro('{$t}&vpe={$vpe}&npe={$npe}&mpe={$mpe}&epe={$epe}&ipe={$ipe}&gpe={$gpe}', campo.value, operador.value, valor.value,'{$opExtra}','{$rooturl}','{$tcr}', fecdel.value, fecal.value)" title="Filtrar Registros"/>
                    </td>
                    <td>&nbsp;&nbsp;&nbsp;
                        <input type="button" name="no_filtro" value="Ver todos" class="boton" onClick="filtro('{$t}&vpe={$vpe}&npe={$npe}&mpe={$mpe}&epe={$epe}&ipe={$ipe}&gpe={$gpe}','viewall',null,null,'{$opExtra}','{$rooturl}','{$tcr}')" title="Ver todos los Registros"/>
                    </td>
                </tr>
            </table>
		</form>
	</div>
    
 	<br>
	
    <div class="bot-tablaup" id="bot-tablaup">
		<a href="../general/encabezados.php?t={$t}&k=&op=1&cadP1={$cadP1}&cadP2={$cadP2}" title="Agregar Nuevo Registro">
			<img src="{$rooturl}imagenes/general/nuevo-it.jpg" alt="" width="100" height="32" border="0">
		</a>
  	</div>
    
	<div class="tabla" id="tabla">
		<table>
    		<tr>
    			<td>
                    <div style="overflow-x:auto; width:1000px; padding:0; height:550px;">
                    <div style="height:455px; width:400px; border:0; padding-top:5px; padding-left:3px; padding-bottom:5px;" id="borde">
                    
    				<table id="listado" cellpadding="0" cellspacing="0" border="0" Alto="420" conScroll="S" scrollH="S" 
                    	width="860px" validaNuevo="false" AltoCelda="25" auxiliar="0" ruta="../../imagenes/general/" 
                        validaElimina="false" Datos="generarTareaInventario.php?accion=4"
                        verFooter="N" guardaEn="False" listado="S" class="tabla_Grid_RC" paginador="S" datosxPag="30" pagMetodo='php' ordenaPHP="S" 
                        title="Listado de Registros" estilos_header="buttonHeader_1|buttonHeader_1|buttonHeader_1" alto_head="{$altohead}">

            			<tr class="HeaderCell">
            			{section loop=$headers name=x}
                             <!--<input type="text" value="{$valuesEncGrid[x]}  abc  {$headers[x]}"><br>-->
                            {if $headers[x] eq "ID_CONTROL" or $headers[x] eq "ID"}
                                <td offsetwidth="0" width="0" tipo="oculto" modificable="N" campoBD='{$valuesEncGrid[x]}'>{$headers[x]} </td>
                            {elseif $anchos[x] eq 0}
                                <td width="0" offsetWidth="0" tipo="oculto">{$headers[x]} </td>
                            {elseif $headers[x] eq "No de solicitud"}        
                                <td width="{$anchos[x]}" offsetWidth="{$anchos[x]}" tipo="entero" modificable="N" campoBD="{$valuesEncGrid[x]}">{$headers[x]} </td>	
                            {else}
                                <td width="{$anchos[x]}" offsetWidth="{$anchos[x]}" tipo="texto" modificable="N" campoBD="{$valuesEncGrid[x]}">{$headers[x]} </td>	
                            {/if}	
                        {/section}				
                        
                        <td width="0" offsetWidth="0" tipo="oculto" valor="ID Tarea" align="center" campoBD='{$valuesEncGrid[x]}'>aaaa</td>	
                        <td width="260" offsetWidth="60" tipo="texto" valor="Almacen" align="center" campoBD='c1'>Almacen</td>	
						<td width="260" offsetWidth="60" tipo="texto" valor="Fecha creación" align="center" campoBD='c2'>Fecha creación</td>	
				
						{if $mpe neq '0'}
                			<td width="60" offsetWidth="60" tipo="libre" valor="Modificar" align="center" campoBD='{$valuesEncGrid[x]}'>
                                <img src="{$rooturl}imagenes/general/modificar.png" border="0" onclick="abreMod(1,'#')" 
                                onmouseover="this.style.cursor='hand';this.style.cursor='pointer';" alt="Mod" title="Modificar Registro"/>
                            </td>
						{/if}
				
                		{if $vpe neq '0'}
                			<td width="50" offsetWidth="60" tipo="libre" valor="Ver" align="center" campoBD='{$valuesEncGrid[x]}'>
                            	<img src="{$rooturl}imagenes/general/ver.png" border="0"  onclick="abreMod(2,'#')" 
                                onmouseover="this.style.cursor='hand';this.style.cursor='pointer';" alt="Ver" title="Ver Registro"/>
                            </td>
                		{/if}
				
                		{if $epe neq '0'}
               				<td width="50" offsetWidth="60" tipo="libre" valor="Eliminar" align="center" campoBD='{$valuesEncGrid[x]}'>
                            	<img src="{$rooturl}imagenes/general/eliminar.png" border="0" onclick="abreMod(3,'#')" 
                                onmouseover="this.style.cursor='hand';this.style.cursor='pointer';" alt="Eliminar" title="Eliminar Registro"/>
                            </td>
                		{/if}
            			</tr>                        
					</table>
       			
                	</div><!--fin del div borde_redondo_grid-->
		       		</div>

				<script>	  	
                	CargaGrid('listado');
                </script>
      			</td>
    		</tr>
		</table>
	<br />
	</div>
    
{/if}














{if $pantalla eq "generar"}

<div style="padding-top:20px; padding-bottom:20px">

	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="checkbox" id="c_categoriaA" onclick="seleccionaTodos('categoriaA')"> Seleccionar/Deseleccionar Categor&iacute;a A<br />
	<div style="overflow-x:auto; width:900px; padding:0; height:340px">    
        <table id="categoriaA" cellpadding="0" cellspacing="0" border="1" Alto="300" conScroll="S" validaNuevo="false" 
            despuesInsertar="" AltoCelda="25" auxiliar="0" validaElimina="false"  verFooter="N"  listado="N" 
            class="tabla_Grid_RC" estilos_header="buttonHeader_1|buttonHeader_1|buttonHeader_1|" scrollH="N" 
            Datos="generarTareaInventario.php?accion=2&idCategoria=1">
            <tr class="HeaderCell">                      
                <td tipo="oculto" modificable="N" mascara="" align="left" formula="" datosdb="" depende="" onChange="" 
                    largo_combo="0" verSumatoria="" valida="" onkey="" inicial="" width="0" offsetwidth="0" on_Click="">id_pedido</td>
                <td tipo="binario" width="19" offsetWidth="0"  modificable="S"  multiseleccion="N" valor="1" campoBD='' align="center">-</td>
                <td tipo="entero" modificable="N" mascara="" align="left" formula="" datosdb="" depende="" onChange="" largo_combo="0" verSumatoria="" 
                    valida="" onkey="" inicial="" width="300" offsetwidth="50" on_Click="">Pedido</td>
            </tr>       
        </table>
	</div>

	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="checkbox" id="c_categoriaB" onclick="seleccionaTodos('categoriaB')"> Seleccionar/Deseleccionar Categor&iacute;a B<br />
	<div style="overflow-x:auto; width:900px; padding:0; height:340px">    
        <table id="categoriaB" cellpadding="0" cellspacing="0" border="1" Alto="300" conScroll="S" validaNuevo="false" 
            despuesInsertar="" AltoCelda="25" auxiliar="0" validaElimina="false"  verFooter="N"  listado="N" 
            class="tabla_Grid_RC" estilos_header="buttonHeader_1|buttonHeader_1|buttonHeader_1|" scrollH="N" 
            Datos="generarTareaInventario.php?accion=2&idCategoria=2">
            <tr class="HeaderCell">                      
                <td tipo="oculto" modificable="N" mascara="" align="left" formula="" datosdb="" depende="" onChange="" 
                    largo_combo="0" verSumatoria="" valida="" onkey="" inicial="" width="0" offsetwidth="0" on_Click="">id_pedido</td>
                <td tipo="binario" width="19" offsetWidth="0"  modificable="S"  multiseleccion="N" valor="1" campoBD='' align="center">-</td>
                <td tipo="entero" modificable="N" mascara="" align="left" formula="" datosdb="" depende="" onChange="" largo_combo="0" verSumatoria="" 
                    valida="" onkey="" inicial="" width="300" offsetwidth="50" on_Click="">Pedido</td>
            </tr>       
        </table>
	</div>
    
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="checkbox" id="c_categoriaC" onclick="seleccionaTodos('categoriaC')"> Seleccionar/Deseleccionar Categor&iacute;a C<br />
	<div style="overflow-x:auto; width:900px; padding:0; height:340px">    
        <table id="categoriaC" cellpadding="0" cellspacing="0" border="1" Alto="300" conScroll="S" validaNuevo="false" 
            despuesInsertar="" AltoCelda="25" auxiliar="0" validaElimina="false"  verFooter="N"  listado="N" 
            class="tabla_Grid_RC" estilos_header="buttonHeader_1|buttonHeader_1|buttonHeader_1|" scrollH="N" 
            Datos="generarTareaInventario.php?accion=2&idCategoria=3">
            <tr class="HeaderCell">                      
                <td tipo="oculto" modificable="N" mascara="" align="left" formula="" datosdb="" depende="" onChange="" 
                    largo_combo="0" verSumatoria="" valida="" onkey="" inicial="" width="0" offsetwidth="0" on_Click="">id_pedido</td>
                <td tipo="binario" width="19" offsetWidth="0"  modificable="S"  multiseleccion="N" valor="1" campoBD='' align="center">-</td>
                <td tipo="entero" modificable="N" mascara="" align="left" formula="" datosdb="" depende="" onChange="" largo_combo="0" verSumatoria="" 
                    valida="" onkey="" inicial="" width="300" offsetwidth="50" on_Click="">Pedido</td>
            </tr>       
        </table>
	</div>



        
        

	<script>	  	
    CargaGrid('categoriaA');
    CargaGrid('categoriaB');
    CargaGrid('categoriaC');
    </script> 

</div>


            
<!--div de espera -->
<!--
<div style="z-index:5000; display:none; position:absolute; left:50px; top:0px; width:500px; height:400px;" id="waitingplease">
	<img src="../../imagenes/general/wait.gif" border="0" style="z-index:2000; position:absolute" id="imgW1"/>
	<img src="../../imagenes/general/back_wait.gif" border="0" style="z-index:1000; position:absolute" id="imgW2"/>
</div>
-->
{literal}
<script language="javascript">

function seleccionaTodos(idTabla){
	var f=document.forma_filtro;
	var num=NumFilas(idTabla);			
	for(var i=0;i<num;i++){
		var o=document.getElementById('c'+idTabla+'_1_'+(i))
					
		if(document.getElementById('c_'+idTabla).checked == true)
			o.checked=true;
		else
			o.checked=false;	
	}
}

</script>
{/literal}

{/if}

{include file="_footer.tpl" aktUser=$username}