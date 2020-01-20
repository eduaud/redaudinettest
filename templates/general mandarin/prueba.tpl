{* TABLA DE CAMPOS *}  

{assign var="contColumnas" value=1}	
{assign var="divAnterior" value=0}	
{assign var="divActual" value = 0}
{assign var="generaDiv" value = 0}
{assign var="contadorCampos" value = 0}
{assign var="contadorCiclos" value = 0}

{section loop=$atributos name=indice start=0}
	{if $atributos[indice][6]}
		{assign var="contadorCampos" value=$contadorCampos+1}
	{/if}
{/section}
<table id="tabla_campos">
	<tr>	
    	<td>
			{section loop=$atributos name=indice start=0}		
        		{if $atributos[indice][3] eq "LEYENDA" }
          			<tr class="subtitulocenter" >
                		<td colspan="4" >
	       					<br>
	       					$atributos[indice][2]}       
	       				</td>
	   				</tr>
				{else} 
					{assign var="divActual" value = $atributos[indice][24]}
		
                    {if $divAnterior neq $divActual }		
                        </table>
                        </div>				
                        {assign var="divAnterior" value = $atributos[indice][24]}
                        {assign var="generaDiv" value = 0}		
                    {/if}
                
                    {if $divAnterior eq $divActual }
                        {if $generaDiv eq 0}
                            {if $contadorCiclos < $contadorCampos}			
                                <!--<h3><a href="#">{$atributos[indice][25]}</a></h3>-->
                                <div style="width:920px; background-color:#E5F0F7; color:#006FAE;"><h3>{$atributos[indice][25]}</h3></div>
                                <div>
                                <table>
                                {assign var="generaDiv" value = 1}
                            {/if}
                        {/if}
                    {/if}
        
         
                    {*****************************************}
                    {if $atributos[indice][23] neq "22"}	 	
                        {if $contColumnas eq "1" }
                            <tr class="td" id="fila_catalogo_{$smarty.section.indice.index}"> 
                        {/if}		
                    {/if}
                    {*****************************************}
                    
                
                    {* 1/ Para los campos visibles (visible->6)*}
         
                    {if $atributos[indice][6] eq "1"}
                        {if $atributos[indice][3] neq 'ARCHIVO'}
                            <td class="{$atributos[indice][18]}">{$atributos[indice][2]}</td>
                    {else if $atributos[indice][3] eq 'ARCHIVO'}
                        {if $v neq 1 or $op eq 3}
                            <td class="{$atributos[indice][18]}">{$atributos[indice][2]}</td>
                        {/if}
                    {/if}
                
                    <input type="hidden"  name="propiedades_[{$smarty.section.indice.iteration-1}]"  
                    value="{$atributos[indice][2]}|{$atributos[indice][3]}|{$atributos[indice][5]}"  />					
                    <td>
                    {* 1.1 el control que Utilizaremos*}
                    {* 1.1.1 para char y enteros*}
                
                    {if $atributos[indice][3] eq "CHAR" or $atributos[indice][3] eq "EMAIL" }
                        {if $atributos[indice][4] > 100 }	
                            <textarea name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" cols="20" rows="5" 
                            class="{$atributos[indice][19]}" {$readonly} onblur="{$atributos[indice][28]}" 
                            onkeypress="{$atributos[indice][21]}" {if $atributos[indice][7]  eq '0'}readonly{/if}>{$atributos[indice][15]}
                            </textarea>
                        {else}
                            {*para llaves del tipo cadena*}
                            {if $atributos[indice][7]  eq '0'}
                                <input type="text" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                                maxlength="{$atributos[indice][4]}" size="{$atributos[indice][4]+3}" value="{$atributos[indice][15]}"  
                                class="{$atributos[indice][19]}" readonly onblur="{$atributos[indice][28]}" 
                                title="Tipo texto, {$atributos[indice][4]} caracteres">
                            {else}
                                <input type="text" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                                maxlength="{$atributos[indice][4]}" size="{$atributos[indice][4]+3}" value="{$atributos[indice][15]}"  
                                class="{$atributos[indice][19]}" {$readonly} onblur="{$atributos[indice][28]}" 
                                onkeypress="{$atributos[indice][21]}" title="Tipo texto, {$atributos[indice][4]} caracteres">
                            {/if}
                        
                        {/if}			
                    {elseif $atributos[indice][3] eq "INT"}					
                        {if $atributos[indice][11]  eq '1'}
                            <input type="text" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                            maxlength="{$atributos[indice][4]}" size="{$atributos[indice][4]+3}" value="{$atributos[indice][15]}"  
                            class="{$atributos[indice][19]}" onkeypress="{$atributos[indice][21]}" readonly 
                            title="Tipo n&uacute;mero, {$atributos[indice][4]} caracteres">
                        {else}
                            {if $atributos[indice][7]  eq '0'}
                                <input type="text" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                                maxlength="{$atributos[indice][4]}" size="{$atributos[indice][4]+3}" value="{$atributos[indice][15]}"  
                                class="{$atributos[indice][19]}" onkeypress="{$atributos[indice][21]}" 
                                onblur="{$atributos[indice][28]}" onchange="{$atributos[indice][29]}" readonly 
                                title="Tipo n&uacute;mero, {$atributos[indice][4]} caracteres">
                            {else}
                                <input type="text" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                                maxlength="{$atributos[indice][4]}" size="{$atributos[indice][4]+3}" value="{$atributos[indice][15]}"  
                                class="{$atributos[indice][19]}" onkeypress="{$atributos[indice][21]}" {$readonly} 
                                onblur="{$atributos[indice][28]}" onchange="{$atributos[indice][29]}"
                                title="Tipo n&uacute;mero, {$atributos[indice][4]} caracteres" >
                            {/if}
                        
                        {/if}
                        
                        {if $make == 'insertar' AND $atributos[indice][0] eq 'monto_inicial' && $t eq 'bm92YWxhc2VyX2FwZXJ0dXJhX2RpYQ=='}
                            <font face="Verdana, Arial, Helvetica, sans-serif" size="1" color="#000000">
                                <i>Monto del cierre anterior:</i>
                            </font>
                            <font face="Verdana, Arial, Helvetica, sans-serif" size="1" color="#FF0000">
                                <b>{$monto_ultimo_cierre}</b>
                            </font>
                        {/if}
                    {elseif $atributos[indice][3] eq 'PASS'}
                        <input type="password" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                        maxlength="{$atributos[indice][4]}" size="{$atributos[indice][4]+3}" value="{$atributos[indice][15]}" 
                        class="{$atributos[indice][19]}" {$readonly} title="Tipo contrase&ntilde;a, {$atribs[indice][2]} caracteres">				
                    {elseif $atributos[indice][3] eq 'TYNYINT'}					
                        {if $atributos[indice][7] eq '0'}					
                            <input type="hidden"  name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                            value="{$atributos[indice][15]}" />
                    
                            <input type="text" name="campo1_{$smarty.section.indice.iteration-1}" id="campo1_{$atributos[indice][0]}" 
                            maxlength="{$atributos[indice][4]}" size="{$atributos[indice][4]+3}" value="{$atributos[indice][17]}"  
                            class="{$atributos[indice][19]}"  readonly="true" >					
                        {else}                    	
                            <input type="checkbox" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                            value="1"  {if $atributos[indice][15] eq 'checked' or $atributos[indice][15] eq '1'} 
                            checked="checked"{/if} onclick="{$atributos[indice][30]}">						 						 
                        {/if} 					
                    {elseif $atributos[indice][3] eq 'DATE'}        			
                        {if $atributos[indice][7] eq '0'}
                            <input type="text" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" maxlength="14"
                             size="18" class="{$atributos[indice][19]}" onfocus="{$atributos[indice][22]}" onblur="{$atributos[indice][28]}"
                             value="{$atributos[indice][15]}" onchange="{$atributos[indice][29]}" readonly title="Tipo fecha (aaaa-mm-dd)"/>
                        {else}
                            <input type="text" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" maxlength="14"
                             size="18" class="{$atributos[indice][19]}" onfocus="{$atributos[indice][22]}" onblur="{$atributos[indice][28]}"
                             value="{$atributos[indice][15]}" onchange="{$atributos[indice][29]}" {$readonly} 
                             title="Tipo fecha (aaaa-mm-dd)"/>
                        {/if}
                        <span class="tiposDatos">&nbsp;&nbsp;Formato dd/mm/aaaa</span>	
                    {elseif $atributos[indice][3] eq 'TIME'}
                        {if $atributos[indice][7] eq '0'}		
                            <input type="text" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" maxlength="10"
                            size="12" class="{$atributos[indice][19]}"    value="{$atributos[indice][15]}" {$readonly} 
                            title="Tipo hora (hh:mm:ss)" readonly="readonly"/>
                        {else}    
                            <input type="text" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" maxlength="10"
                            size="12" class="{$atributos[indice][19]}"    value="{$atributos[indice][15]}" {$readonly} 
                            title="Tipo hora (hh:mm:ss)"/>
                        {/if}            
                        <span class="tiposDatos">&nbsp;&nbsp;Formato HH:mm:ss</span>            
                    {elseif $atributos[indice][3] eq 'COMBO'}					
                        {* 1/ Para los campos no modificables (modificable->7)*}	
                        {if $atributos[indice][7] eq '0' }
                            <input type="hidden"  name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                            value="{$atributos[indice][15]}" />
                            
                            <input type="text" name="campo1_{$smarty.section.indice.iteration-1}" id="campo1_{$atributos[indice][0]}" 
                            maxlength="{$atributos[indice][4]}" size="{$atributos[indice][4]+3}" value="{$atributos[indice][17]}"  
                            class="{$atributos[indice][19]}"  readonly="true"  >				        
                        {else}  
                            <!-- Excepcion para Pantalla Series y Vendedores con combo de sucursal-->
                            {if $t eq 'YW5kZXJwX3Nlcmllcw==' or $t eq 'YW5kZXJwX3ZlbmRlZG9yZXM='}
                                {if $atributos[indice][0] eq 'id_sucursal'}	
                                    <select name="campo_{$smarty.section.indice.iteration-1}_combo" class="{$atributos[indice][19]}" 
                                    onchange="{$atributos[indice][29]}" id="{$atributos[indice][0]}_combo" disabled="disabled">
                                        {html_options values=$atributos[indice][16][0] output=$atributos[indice][16][1]  selected=$atributos[indice][15] }
                                    </select>    
                                    <input type="hidden"  name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                                    value="{$atributos[indice][15]}" />
                                {else}
                                    <select name="campo_{$smarty.section.indice.iteration-1}" class="{$atributos[indice][19]}" 
                                    onchange="{$atributos[indice][29]}" id="{$atributos[indice][0]}">
                                    {html_options values=$atributos[indice][16][0] output=$atributos[indice][16][1]  selected=$atributos[indice][15]}
                                    </select>
                                {/if}	                  
                            {else}         
                                <select name="campo_{$smarty.section.indice.iteration-1}" class="{$atributos[indice][19]}" onchange="{$atributos[indice][29]}" id="{$atributos[indice][0]}">
                                {html_options values=$atributos[indice][16][0] output=$atributos[indice][16][1]  selected=$atributos[indice][15]}
                                </select>                                            
                            {/if} 
                        {/if}
                    {elseif $atributos[indice][3] eq 'BUSCADOR'}           
                        {if $atributos[indice][7] eq '0'}
                            <input type="hidden"  name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                            value="{$atributos[indice][15]}" />
                            
                            <input type="text" name="campo1_{$smarty.section.indice.iteration-1}" id="campo1_{$atributos[indice][0]}" 
                            maxlength="{$atributos[indice][4]}" size="{$atributos[indice][4]+3}" value="{$atributos[indice][17]}"  
                            class="{$atributos[indice][19]}"  readonly="true">
                        {else}    
                            <input type="text" name="texto_buscador_{$smarty.section.indice.iteration-1}" 
                            id="texto_buscador_{$smarty.section.indice.iteration-1}" size="23" class="{$atributos[indice][19]}" 
                            onkeyup="ejecutaBuscador(this.value, document.forma_datos.radio_buscador_{$smarty.section.indice.iteration-1}.value, {$atributos[indice][32]}, campo_{$smarty.section.indice.iteration-1});"/>
                            <input type="hidden" name="radio_buscador_{$smarty.section.indice.iteration-1}" value="0" />
                            {section loop=$atributos[indice][31] name=xbus start=0 step=2}                	
                                {if $smarty.section.xbus.loop eq 2}	
                                
                                {else}	                        
                                    {if $smarty.section.xbus.iteration eq 1}
                                        <input type="radio" name="rb_{$smarty.section.indice.iteration}" checked="checked" 
                                        onclick="radio_buscador_{$smarty.section.indice.iteration-1}.value={$smarty.section.xbus.index}"/>{$atributos[indice][31][xbus]}&nbsp;
                                    {else}    
                                        <input type="radio" name="rb_{$smarty.section.indice.iteration}" 
                                        onclick="radio_buscador_{$smarty.section.indice.iteration-1}.value={$smarty.section.xbus.index}"/>{$atributos[indice][31][xbus]}&nbsp;
                                    {/if}
                                {/if}
                            {/section}
                            <br />
                            <select name="campo_{$smarty.section.indice.iteration-1}" class="{$atributos[indice][19]}" 
                            onchange="{$atributos[indice][29]}" id="{$atributos[indice][0]}" size="5" style="width:100">
                            {html_options values=$atributos[indice][16][0] output=$atributos[indice][16][1]  selected=$atributos[indice][15] }
                            </select>
                        {/if}    	
                    {elseif $atributos[indice][3] eq 'COMBOBUSCADOR'}
                        {if $atributos[indice][7] eq '0'}
                            <input type="text" name="v_campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                            maxlength="{$atributos[indice][4]}" size="{$atributos[indice][4]+3}" value="{$atributos[indice][15][1]}"  
                            class="{$atributos[indice][19]}"  onchange="{$atributos[indice][28]}" onkeyup="activaBuscador(this,event);" 
                            datosdb="{$atributos[indice][16]}" onclick="ocultaCombobusc('campo_{$smarty.section.indice.iteration-1}')"  
                            on_change="{$atributos[indice][29]}" depende="{$atributos[indice][33]}" onchange="compruebaRef()" readonly>
                            
                            <input type="hidden" name="campo_{$smarty.section.indice.iteration-1}" 
                            id="hcampo_{$smarty.section.indice.iteration-1}" value="{$atributos[indice][15][0]}" />
                        {else}
                            <div style="float:left">
                                <div id="divcampo_{$smarty.section.indice.iteration-1}" style="visibility:hidden; display:none; 
                                position:absolute; z-index:3;">
                                <select id="selcampo_{$smarty.section.indice.iteration-1}" size="4" 
                                onclick="asignavalorbusc('campo_{$smarty.section.indice.iteration-1}')" 
                                onkeydown="teclaCombo('campo_{$smarty.section.indice.iteration-1}',event)">
                                    <option></option>
                                </select>
                                </div>				
                                <input type="text" name="v_campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                                maxlength="{$atributos[indice][4]}" size="{$atributos[indice][4]+3}" value="{$atributos[indice][15][1]}"  
                                class="{$atributos[indice][19]}"  onblur="{$atributos[indice][28]}" onkeyup="activaBuscador(this,event);" 
                                datosdb="{$atributos[indice][16]}" onclick="ocultaCombobusc('campo_{$smarty.section.indice.iteration-1}')"  
                                on_change="{$atributos[indice][29]}" depende="{$atributos[indice][33]}"  
                                title="Digite una palabra, y se desplegara un menu emergente, seleccione una opci&oacute;n del menu." 
                                autocomplete="off"/>
                                <img src="{$rooturl}imagenes/general/flecha_abajo.gif" style="height:12px;" 
                                onmouseover="this.style.cursor='hand';this.style.cursor='pointer';" 
                                onclick="botonBuscador('campo_{$smarty.section.indice.iteration-1}')"/>										
                                <input type="hidden" name="campo_{$smarty.section.indice.iteration-1}" 
                                id="hcampo_{$smarty.section.indice.iteration-1}" value="{$atributos[indice][15][0]}" />                
                            </div>
                        {/if}
                    {elseif $atributos[indice][3] eq 'BUSCADORCP'}
                        <input type="text" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                        maxlength="{$atributos[indice][4]}" size="{$atributos[indice][4]+3}" value="{$atributos[indice][15][1]}"  
                        class="{$atributos[indice][19]}"  onblur="{$atributos[indice][28]}" onkeyup="activaBuscador(this,event);" 
                        title="Tipo Buscador de CP, digite y de click en buscar para llenar los campos de Ciudad,Estado y Municipio.">
                        <input name="button" type="button"  
                        style="background-image:url(../../imagenes/general/e-boton-bg3.jpg); color:#FFFFFF; font-size:8pt; border-color:#999999 #999999 #999999 #FAFAFA; border-width:0 2px 2px 1px; height:18px;" onclick="buscadorcp('campo_{$smarty.section.indice.iteration-1}');"
                         value="Buscar"/>
                        <input type="hidden" id="hcampo_{$smarty.section.indice.iteration-1}" value="cp|id_estado|id_ciudad|id_delmpo|colonia" />
                    {elseif $atributos[indice][3] eq 'ARCHIVO' and $v neq 1}
                        <input type="file" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                        maxlength="{$atributos[indice][4]}" size="{$atributos[indice][4]+3}" class="{$atributos[indice][19]}" 
                        title="Tipo Archivo, seleccione y asigne un archivo" >
                    {/if}        	
        			</td>			
                    {else}
                        <input type="hidden"  name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                        value="{$atributos[indice][15]}"  />
                        <input type="hidden"  name="propiedades_[{$smarty.section.indice.iteration-1}]"  
                        value="{$atributos[indice][2]}|{$atributos[indice][3]}|{$atributos[indice][5]}"  />		
                        {* 1/*} 
                    {/if}                
        
                    {**********************************}
                    {if $atributos[indice][23] neq "21"}	 	
                        {if $contColumnas eq $numColumnas }
                            </tr>
                            {assign var="contColumnas" value=1}				
                        {else}
                            {assign var="contColumnas" value=$contColumnas+1}	 
                        {/if}		
                    {/if}
                    {**********************************}		
                {/if}	
				{assign var="contadorCiclos" value=$contadorCiclos+1}
			{/section}
			<input type="hidden" name='countReg' size="10" value = '{$smarty.section.indice.index}'/>
		AKI</td>
    </tr>
    </table>
</div>
</table>
{* TERMINA TABLA DE CAMPOS *}
