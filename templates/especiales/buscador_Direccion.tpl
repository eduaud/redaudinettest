{if $tipoCarga eq "1"}
{include file="_header.tpl" pagetitle="$contentheader"}
<link href="{$rooturl}/css/pop_up_form.css" rel="stylesheet" type="text/css" />

{/if}    
<!--<script src='{$rooturl}/js/fullcalendar-1.6.1/jquery/jquery-1.9.1.min.js'></script>
<script src='{$rooturl}/js/fullcalendar-1.6.1/jquery/jquery-ui-1.10.2.custom.min.js'></script>-->
<script language="javascript" src="{$rooturl}/js/jquery-1.8.3.js"></script>

<link rel="stylesheet" type="text/css" href="{$rooturl}/css/thickbox.css" media="screen"/>

 <link href="{$rooturl}css/style-t.css" rel="stylesheet" type="text/css" />



    <br />
	<br />
	


















     <br />

    <table width="300">
	<tr>
	  <td><label>Nombre</label></td>
	 <td><input type="text" id="nombrebus" /></td>
	<tr> 
	  <td><br /><label>Lugar</label><br /><br /></td>
	  <td><select id="lugares" >
	  <option value="0">--Seleccione lugar--</option>
	  <option value="1">TEMPLO</option>
	  <option value="2">SALON</option>
	  <option value="3">JARDIN</option>
	  <option value="4">HOTEL</option>
	  <option value="5">CASA</option>
	  <option value="6">BAR</option>
	  <option value="7">SHOWROOM</option>
	  <option value="8">CORPORATIVO</option>
	  <option value="9">GALERIA DE ARTE</option>
	  <option value="10">RESTAURANT</option>
	  <option value="11">MUSEO</option>
	  <option value="12">ALMACEN</option>
	  <option value="13">TERRAZA</option>
	  <option value="14">FLORERIA</option>
	  
	</select>
	</td>
	</tr>
	</table>
	<input type="button" value="Buscar" onclick="buscador();" class="botonSecundario" />   
	<input type="button" value="Ver todos" onclick="ver_todos();" class="botonSecundario" />  
	<br /><br />
	
	<div  class="tablaContenedor"  >
		 
    		<table   id="tablaDatos" width="1500" border="0" cellpadding="0" cellspacing="0" style="font-size:14px" style="font-family:Arial, Helvetica, sans-serif">
						
						  <th   class="titulo_a" width="60px"  > Seleccionar   </th>
						  <th   class="titulo_a" width="90px" >  Tipo de Lugar    </th>
						  <th   class="titulo_a" width="110">Nombre</th>
						  <th   class="titulo_a" >Calle</th>
						  <th   class="titulo_a" width="50">N° Exterior</th>
						  <th   class="titulo_a"  width="50">N° Interior</th>
						  <th 	class="titulo_a" >Colonia</th>
						  <th 	class="titulo_a" >Ciudad</th>
						  <th  	class="titulo_a">Del./Mpio</th>
						  <th 	class="titulo_a" >CP</th>
						  <th 	class="titulo_a" >Teléfono 1</th>
						  <th  	class="titulo_a">Teléfono 2</th>
						  <th 	class="titulo_a" >Celular</th>
						  <th 	class="titulo_a" >Referencias</th>
						  <th 	class="titulo_a" >Web</th>
						  <th  	class="titulo_a">Email</th>
						  <th 	class="titulo_a" >Tiempo Traslado</th>
						  <th 	class="titulo_a" >Google Maps</th>
						  <th   class="titulo_a" >Comentario</th>
						
					
						<tbody id="datosBusquedaBody" class="HeaderCell">
						</tbody>
						</table>
		<br /><br />				
           
	</div>
	<br />
    <input type="button" value="Guardar" onclick="guardarDireccion();" class="botonSecundario"/>

