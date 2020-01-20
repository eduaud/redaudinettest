			</div>
		</div>
	</div>



	<div class="footer" id="footer">
  		<table width="1000" border="0" cellpadding="0" cellspacing="0">
    		<tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
    		</tr>
  		</table>
  
  		<div class="footer_thebooks" id="footer_thebooks">
    		<table width="996" border="0" cellpadding="0" cellspacing="0">
      			<tr>
                    <td width="17" align="right"><p>&nbsp;</p></td>
                    <td width="867"><p>Copyright &copy;2015  Audicel. Todos los derechos reservados.</p></td>
                    <td width="112" align="right"><p>Desarrollo: <a href="http://www.sysandweb.com/" target="_blank">Sys&amp;Web</a></p></td>
      			</tr>
    		</table>
  		</div>
	</div>
</div>
</body>
</html>




{*falta agregar el codigo de las notificaciones*}
{literal}
<script type="text/javascript" language="javascript">
function checaID(campo, tabla, campoTabla){
	if(document.forms.forma_datos.pri){
		if(campo.value!=document.forms.forma_datos.pri.value){
			if(campo.value!=null)
				campo.value=campo.value.toUpperCase();
			ajax_request('POST','text','buscaID.php','valor='+campo.value+'&tabla='+tabla+'&campoTabla='+campoTabla,'checaIDRespuesta(ans)');
		}
	}else{
		if(campo.value!=null)
			campo.value=campo.value.toUpperCase();
		ajax_request('POST','text','buscaID.php','valor='+campo.value+'&tabla='+tabla+'&campoTabla='+campoTabla,'checaIDRespuesta(ans)');
	}
	return true;
}

function checaIDRespuesta(respuesta){
	if(respuesta=='no id'){
		alert("Debe introducir un ID válido.");
		document.forms.forma_datos.folio.focus();
		document.forms.forma_datos.folio.select();
		return false;
	}
	if(respuesta=='ya existe'){
		alert("El ID que acaba de indicar ya existe.\nIntroduzca otro.");
		document.forms.forma_datos.folio.focus();
		document.forms.forma_datos.folio.select();
		return false;
	}
	if(respuesta=='no existe')
		return true;
	return false;
}

function Abrir_Ventana(cadena){
	var arreglo=cadena.split('|');
	var centroAncho = (screen.width/2) - (arreglo[1]/2);
	var centroAlto = (screen.height/2) - (arreglo[2]/2);
	var especificaciones="top="+centroAlto+",left="+centroAncho+",toolbar=no,location=no,status=no,menubar=no,scrollbars=no,width="+arreglo[1]+",height="+arreglo[2]+",resizable=no"
	var titulo="VentanaAdmon";
	window.open(arreglo[0],titulo,especificaciones);
}

function ventanaNombre(cadena)
{
	var arreglo=cadena.split('|');
	var centroAncho = (screen.width/2) - (arreglo[1]/2);
	var centroAlto = (screen.height/2) - (arreglo[2]/2);
	var especificaciones="top="+centroAlto+",left="+centroAncho+",toolbar=no,location=no,status=no,menubar=no,scrollbars=no,width="+arreglo[1]+",height="+arreglo[2]+",resizable=no"
	var titulo=arreglo[3];
	window.open(arreglo[0],titulo,especificaciones);
}

</script>
{/literal}
</body>
</html>