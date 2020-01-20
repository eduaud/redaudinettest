{include file="_header.tpl" pagetitle="$contentheader"}

<br><br>
<h1>
	Permisos
</h1>

<br>
<input name="b_actualiza" type="button" class="botonSecundario" value=" Guardar Permisos " onclick="guarda_permisos(this.form,'{$no_per}')" />
<br>
<br>

<div class="tabla" id="tabla">
<form action="encabezados.php" name="forma_datos" id="forma_datos" method="post" >
   
	<input name="id_grupo_sel" id="id_grupo_sel" type="hidden" value="" />
	<input name="usuario_cambiar" id="usuario_cambiar" type="hidden" value="0" />
	<input name="no_permiso" id="no_permiso" type="hidden" value="{$no_per}" />
	
	<table class="campos" width="100%">
		<tr valign="top">
			<td class="buttonHeader_1" width="25%" style="padding-top:0px !important; padding-bottom:0px !important">
				<h3>Grupos</h3>
			</td>
			<td class="buttonHeader_1" width="35%" style="padding-top:0px !important; padding-bottom:0px !important">
				<h3>Usuarios</h3>
			</td>
			<td class="buttonHeader_1" width="40%" style="padding-top:0px !important; padding-bottom:0px !important">
				<h3>Men&uacute;s</h3>
			</td>
		</tr>
			
		{section loop=$grupos name=y}
		<tr valign="top">	
			<td align="left">
				{if $no_per eq 1 or $no_per eq 3}
					<input type="radio" name="op" id="id_g_{$smarty.section.y.index}" value="{$grupos[y][0]}" onclick="cambiaIframe(this.form,'{$grupos[y][0]}','{$no_per}','{$smarty.section.y.index}')" />
				{else}
					<input name="id_g_{$smarty.section.y.index}" type="checkbox" value="{$grupos[y][0]}" />
				{/if}
				 &nbsp;{$grupos[y][1]}
			 </td>
		  
			{if $no_per neq 3}
				<td align="left">  
					<select name="campo_{$smarty.section.y.iteration-1}" class="campos" multiple="multiple" size="6" style="width:150">
						{html_options values=$grupos[y][2] output=$grupos[y][3]  }
					</select>
				</td>
			{/if}

			{if $no_per eq 2}
				<!--
				<td align="left">   
					&nbsp; &nbsp;                  
					<select name="campo2_{$smarty.section.y.iteration-1}" class="campos" multiple="multiple" size="6" style="width:150">
						{html_options values=$grupos[y][4] output=$grupos[y][5]  }
					</select>	
					<br>
					&nbsp; &nbsp;
					<input name="check_usr_{$smarty.section.y.index}" id="check_usr_{$smarty.section.y.index}" type="checkbox" value="{$grupos[y][0]}"  />	
					<span class="tiposdatos">   
					Aplicar cambio para usuarios con <br>&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; permisos especiales.</span> 
				</td>
				-->
			{/if}
			
			
			{if $smarty.section.y.index eq 0}
				<td class="subtitulo" rowspan="{$smarty.section.y|@count}">
					<iframe id="id_iframe_sub" name="id_iframe_sub" src="permisos.php?make=submenus&ic=0" frameborder="0" framespacing="0" scrolling="yes" border="0" height="500px" width="400px"></iframe>
					<br>
					{if $no_per eq '3-'}
						<input name="b_cambiar" type="button" class="boton" value="Asigna Permisos del Grupo asignado" onclick="asigna_permisos(this.form)" />
					{/if}						
				</td>
			{/if}
		</tr>
		{/section}
		
		<input type="hidden" name='countReg' id='countReg' size="10" value = '{$smarty.section.y.index}'/>     
	</table>
		
</form>
</div>

{literal}
<script type="text/javascript" language="javascript">
/* cambiar a un archivo js especial de este template */
function makeRequest(url,busca){
	http_request = false;
	if(window.XMLHttpRequest){ // mozilla, netscape, opera...
		http_request = new XMLHttpRequest();
		if(http_request.overrideMimeType){
			http_request.overrideMimeType('text/xml');
		}
	}else if(window.ActiveXObject){ // IE
		try{
			http_request = new ActiveXObject("Msxml2.XMLHTTP");
		}catch(e){
			try{
				http_request = new ActiveXObject("Microsoft.XMLHTTP");
			}catch(e){
			}
		}
	}
	if(!http_request){
		alert('Falla :( No es posible crear una instancia XMLHTTP');
		return false;
	}
	
	if(busca=='insertaGpo')
		http_request.onreadystatechange = insertaGrupo;
	
	http_request.open('GET', url, true);
	http_request.send(null);
}

function insertaGrupo()
{
	if(http_request.readyState == 4){
		if(http_request.status == 200){
			
			//alert(http_request.responseText);
			//var combos=http_request.responseText.split('$');
			var valor=http_request.responseText;
			
					
			if(valor=='Exito')
			{
				alert("Los permisos se guardaron con éxito");
				
				if(document.getElementById('no_permiso').value=='2-')
				{
					location.reload();
				}								
			}
			else
				alert("Hubo un problema al insertar los permisos");
			
		}else{
			alert('Hubo problemas con la petición.');
		}
	}
}


//funciones que recargan la informacion
function cambiaIframe(objform,valor,p,indice)
{
    
	//colocamos el nombre del submenu que estamos cambiando
	
	//llenamos el frame pagares no saldados ../permisos/permisos.php?make=submenus&ic=0
	var x=document.getElementById('id_iframe_sub');

	// 2 es para actualizar subgrupos
	
	if(p=='1-')
	{
		//obtenemos el y seleccionado para concer que indice tenemos
		document.getElementById('usuario_cambiar').value=indice;	
				
		
		document.getElementById('id_grupo_sel').value=valor;
		x.src="permisos.php?make=grupos&ic="+valor+"&p="+p;
	}
	else if(p=='3-')
	{
		document.getElementById('id_grupo_sel').value=valor;
		x.src="permisos.php?make=usuarios&ic="+valor+"&p="+p;
	}
	else
		x.src="permisos.php?make=submenus&ic="+valor+"&p="+p;
	
	//alert("../permisos/permisos.php?make=grupos&ic="+valor+"&p="+p);
	
	//retrasamos un momento la funcion para que termine de cargar la pagina con los datos
	
	/*//llenamos con los recibos generados
	var y=document.getElementById('id_iframe_usuarios');
	y.src="../permisos/permisos.php?make=usuarios&ic="+valor;
	
	//llenamos con los pagares saldados
	var z=document.getElementById('id_iframe_grupos');
	z.src="../permisos/permisos.php?make=grupos&ic="+valor;
	
	//makeRequest("../pagos/buscaCalPagares.php?make=unpagare&ic="+valor+"&por="+bucarpor+"&rec=",'valores_recibos');*/
	
}

//----------------------------------
//funcion final de gurda permisos
//----------------------------------
function guarda_permisos(objform,permiso)
{
	var strSelected="";
	var strSelectedPermiso="";
	var k=0;
	var camus='0';
	var pregunta="";
	var opcionMake="";
	var countReg = $("#countReg").val();
	
	//vemos que exista al menos un grupos seleccionado
	//---------------
	//----------------
	if(permiso=="2-")
	{
			if(document.getElementById('id_grupo_sel').value =='')
			{
				alert("Es necesario seleccionar un Grupo para asignar permisos.");
				return false;
			}
			else
				strSelected= document.getElementById('id_grupo_sel').value+",";
			
			//vemos cual y esta seleccionado
			
			//cambiar los permisos tambien del usuario
			if (document.getElementById('check_usr_'+document.getElementById('usuario_cambiar').value).checked==true)
				camus=1;
			
			
			
	}
	else if(permiso=="3-")
	{
		if(document.getElementById('id_grupo_sel').value =='')
			{
				alert("Es necesario seleccionar un Usuario para cambiar permisos.");
				return false;
			}
			else
				strSelected= document.getElementById('id_grupo_sel').value+",";
	}
	else
	{
		if (countReg == 1)
		{
			if (document.getElementById("id_g_0").checked)
			{
					strSelected= document.getElementById("id_g_0").value+",";
					k++;
			}
		}
		else
		{
			for(var i=0; i<countReg; i++)
			{
				if (document.getElementById("id_g_"+i).checked == true )
				{
					strSelected= strSelected + document.getElementById("id_g_"+i).value +",";
					k++;
				}
							
			}
		}	
			
		if (k==0)
		{
			alert("Es necesario seleccionar al menos un Grupo para asignar permisos.");
			return false;
		}
	}
	
	//ahora realizamos el recorrido 
	var formFrame=frames.id_iframe_sub.document.forma_datos_frame;
	var countMenus=formFrame.countRegTodos.value;
	var counSubmenu=0;
	//alert(countMenus);
	
	//para cada menu realizamos un recorrido y checamos si esta seleccionado y traemos su valor y realizamso el submit
	for(var y=0;y<countMenus;y++)
	{
		
		//obtenemos el numero de menu objform.elements["countReg"]
		counSubmenu=formFrame.elements["countReg_"+y].value;

		for(var x=0; x<counSubmenu;x++)
		{
			//para cada checkbox traemos los valores que estan separados por pipas
			// sub_0_0_3
			
			for(var w=2; w<8;w++)
			{
				
				if(formFrame.elements["sub_"+y+"_"+x+"_"+w].checked)
				{
				  //alert(formFrame.elements["sub_"+y+"_"+x+"_"+w].value);			
				  strSelectedPermiso=strSelectedPermiso+formFrame.elements["sub_"+y+"_"+x+"_"+w].value+"~";
				 }
			}
		}
		
	}
			
	//objform.strSelected.value=strSelected;	
	//-------------------
	//-------------------
	//--------------------
	//alert(strSelectedPermiso);
	if(strSelectedPermiso=="")
	{
		alert("Es necesario seleccionar al menos un permiso.");
		return false;
	}
	
	//para los tipos 2 y 
	opcionMake="inGpo";
	
	if(permiso=="2-")
	{
		pregunta=" ¿Desea crear los permisos para el grupo seleccionado.? \n\n   Este esta acción eliminar&aacute; los permisos previamente \n   asignados a el Grupo";
		if(camus=='1')	
		{
			pregunta = pregunta+ " y a sus Usuarios Relacionados .";
		}
		
	}	
	else if(permiso=="3-")
	{
		pregunta=" ¿Desea actualizar los permisos para el usuario seleccionado? ";
		opcionMake='inUsr';
	}
	else
		pregunta=" ¿Desea crear los permisos para los grupos seleccionados.? \n\n   Este esta acción eliminará los permisos previamente \n   asignados a Grupos y sus Usuarios.";
	
	
	
	//peguntamos si desea eliminar el descuento que se pidio
	if(confirm(pregunta))
	{
		//camus cambiar tambien ususarios relacionados
		makeRequest('guardaPermisos.php?make='+opcionMake+'&submenu='+strSelectedPermiso+'&grupo='+strSelected+"&camus="+camus,'insertaGpo');
	}
}

//para actualizar los permisos de los ususarios al grupo al que pertenece
function asigna_permisos(objform)
{
	var strSelected="";
	var strSelectedPermiso="";
	var k=0;
	var camus='0';
	var pregunta="";
	var opcionMake="";
	//vemos que exista al menos un grupos seleccionado
	//---------------
	//----------------
	if(document.getElementById('id_grupo_sel').value =='')
	{
		alert("Es necesario seleccionar un Usuario para cambiar permisos.");
		return false;
	}
	else
		strSelected= document.getElementById('id_grupo_sel').value+",";
	
	//para los tipos 2 y 
	opcionMake="delUsrs";
	
	pregunta=" ¿Desea cambiar los permisos del usuario por los de su Grupo relacionado?";
	
	//peguntamos si desea eliminar el descuento que se pidio
	if(confirm(pregunta))
	{
		//camus cambiar tambien ususarios relacionados
		makeRequest('../permisos/guardaPermisos.php?make='+opcionMake+'&submenu='+strSelectedPermiso+'&grupo='+strSelected+"&camus=0",'insertaGpo');
	}
}


function activaCambiarUsr(objform,valor)
{
	var k=0;
	
	if (valor.checked == true)
		k=1;
	
	if (objform.elements["countReg_"+menu].value==1)
	{
			objform.elements["sub_"+menu+"_0_"+columna].checked = k;
	}
	else
	{
		for(var i=0; i<objform.elements["countReg_"+menu].value;i++)
		{
			objform.elements["sub_"+menu+"_"+i+"_"+columna].checked = k;
		}		
	}
}

</script>
{/literal}
{include file="_footer.tpl" aktUser=$username}