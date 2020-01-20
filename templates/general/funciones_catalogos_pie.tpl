{*Funciones Comunes a Todos los Catalogos*}


<script language="javascript">
{literal}

/******************************************************/
function cambiaActivoCxC(grid,fila){
/*Si eliminan un renglon del grid se cambiara el estatus a activo=0 cuando guarde el registro
Se guarda una variable en un campo oculto dentro de catalogo.tpl para cuando Guarde se realice el update de los registros que no estan en la cadena.
****/
document.getElementById('regActivoCxC').value ='';
//Recorro el grid Detalle de Cobros y guardo el id del registro que queda en Grid
  var nfil=NumFilas('detallecobros');
  var cade ='';
  

 	for(var k=0;k<nfil;k++){
	    var v_tipo_cobro = celdaValorXY('detallecobros',0,k); 
	if(v_tipo_cobro != ''){	
		if(cade == ''){
		    cade = v_tipo_cobro;
		}
		 else{
		     cade = cade + '@@' + v_tipo_cobro;
		}
	}	
			
   }//fin for
	  
	  document.getElementById('regActivoCxC').value=cade; 


}//fin function 
/*******************************************************/

//funcion que valida la eliminacion de una fila antigua
function validaFilaAnterior(grid,posY,posX)
{
	//var tabla=document.forma_datos.t.value;	
	posX=parseInt(posX);
	posY=parseInt(posY);
	valor=celdaValorXY(grid,posX,posY)
	if(valor != 'NO')
	{
		alert("No se pueden eliminar filas que ya se encontraban registradas");
		return false;
	}	
	//
	
	return true;
}


{/literal}

{if $t eq 'ZmVsZWNfZmFjdHVyYXM=' or $t eq 'ZmVsZWNfbm90YXNfY3JlZGl0bw=='}
{literal}

	/*Proyecto FACELEC, funcion de cambio en productos Factura*/
	
	
	function cambiosConcepto(grid, pos)
	{
		var aux, ax, id_prod, desc;
		var url="../ajax/validaProducto.php";
		
		aux=celdaValorXY(grid, 3, pos);
		ax=aux.split(':');
		id_prod=ax[0];
		desc=ax[1];
		
		//validamos producto
		url+="?id_producto="+ax[0]+"&id_compania=1";
		aux=ajaxR(url);		
		//alert(aux);
		ax=aux.split('|');
		if(ax[0] == 'exito')
		{
			//alert(ax[4]);
			valorXY(grid, 2, pos, id_prod)
			valorXYNoOnChange(grid, 3, pos, desc)
			valorXY(grid, 6, pos, ax[1])
			valorXY(grid, 7, pos, ax[2])
			valorXY(grid, 8, pos, ax[3])
			valorXY(grid, 10, pos, ax[4])			
			valorXY(grid, 12, pos, ax[5])
			valorXY(grid, 14, pos, ax[5])			
		}
		else	
			alert(aux);
	}
	
	function actualizaTotalT(grid)
	{		
		var num=NumFilas(grid);	
		var desc, can, pre;
		
		obj=document.getElementById('porcentaje_descuento');
		desc=isNaN(parseFloat(obj.value))?0:parseFloat(obj.value);
		
		for(var i=0;i<num;i++)
		{			
		
			can=celdaValorXY(grid, 5, i);
			can=isNaN(parseFloat(can))?0:parseFloat(can);
		
			pre=celdaValorXY(grid, 6, i);
			pre=isNaN(parseFloat(pre))?0:parseFloat(pre);
		
		
			valorXY(grid, 8, i, (desc/100)*can*pre);
			htmlXY(grid, 8, i, Mascara('$ #,###.##', (desc/100)*can*pre));
		}
		
		if(num >= 1 )
			actualizaTotal(grid, 0);
		
	}
	
	function actualizaTotal(grid, pos)
	{
		var num=NumFilas(grid);
		var total=0, stotal=0, descuento=0, iva=0, ieps=0, aux=0, desc, can, pre;
		
		
		//calculamos el descuento		
		obj=document.getElementById('porcentaje_descuento');
		desc=isNaN(parseFloat(obj.value))?0:parseFloat(obj.value);
		
		can=celdaValorXY(grid, 5, pos);
		can=isNaN(parseFloat(can))?0:parseFloat(can);
		
		pre=celdaValorXY(grid, 6, pos);
		pre=isNaN(parseFloat(pre))?0:parseFloat(pre);
		
		
		valorXY(grid, 8, pos, (desc/100)*can*pre);
		htmlXY(grid, 8, pos, Mascara('$ #,###.##', (desc/100)*can*pre));
		
		
		//sumamos totales
		for(var i=0;i<num;i++)
		{
			//total
			aux=celdaValorXY(grid, 16, i);
			total+=isNaN(parseFloat(aux))?0:parseFloat(aux);
			
			//subtotal
			aux=celdaValorXY(grid, 9, i);
			stotal+=isNaN(parseFloat(aux))?0:parseFloat(aux);
			
			//descuento
			aux=celdaValorXY(grid, 8, i);
			descuento+=isNaN(parseFloat(aux))?0:parseFloat(aux);
			
			//iva
			aux=celdaValorXY(grid, 11, i);
			iva+=isNaN(parseFloat(aux))?0:parseFloat(aux);
			
			//ieps
			aux=celdaValorXY(grid, 13, i);
			ieps+=isNaN(parseFloat(aux))?0:parseFloat(aux);
		}
		
		var obj=document.getElementById('total');
		obj.value=redond(total,2);
		
		var obj=document.getElementById('subtotal');
		obj.value=redond(stotal,2);
		
		var obj=document.getElementById('descuento');
		obj.value=redond(descuento,2);
		
		var obj=document.getElementById('iva');
		obj.value=redond(iva,2);
		
		var obj=document.getElementById('ieps');
		obj.value=redond(ieps,2);
	}
	
	function SellaYTimbra()
	{
		alert(1);
		
		/*var id=document.getElementById('id_control_factura').value;
		url="../ajax/sellaTimbraFactura.php?tipo_documento=FAC&id_documento="+id;
		var aux=ajaxR(url);			
		
		alert(aux);
		if(aux != 'exito')
		{
			alert(aux);
			
			return false;
		}*/
		
	
		
		
		
	}
	
	function SellaYTimbraNC(bot, f)
	{
		bot.disabled=true;
		
		var id=document.getElementById('id_control_nota_credito').value;
		var url="../especiales/facturaXML.php?tabla=notas_credito&llave="+id+"&id_compania=1";
		var aux=ajaxR(url);
		
		if(aux == 'exito' || aux == 'DocumentoSellado')
		{
			url="../ajax/timbrado.php?id_tipo=NC&id_documento="+id;
			//alert(url);
			var aux=ajaxR(url);			
			if(aux != 'exito')
			{
				alert(aux);
				bot.disabled=false;
				return false;
			}
			else
				alert("El documento fue sellado y timbrado exitosamente.");
			
		}
		else if(aux == 'DocumentoTimbrado')
			alert('El documento ya se encuentra timbrado.');
		else
			alert("Error al sellar la factura, por favor intente mas tarde.\n\n" + aux);
		
		
		
		bot.disabled=false;
	}


{/literal}
{/if}

{if $t eq 'cGV1Z19wdW50b3NfdmVudGE='}
{literal}


	function validaUsuariosPV(obj)
	{
		if(obj.checked == false)
		{
			aux=ajaxR("../ajax/getListaUsarios.php?id_pv="+document.getElementById('id_punto_venta').value);
			ax=aux.split("|");
			if(ax[0] == 'SI')
				return true;
			else if(ax[0] == 'NO')	
			{
				if(confirm(ax[1]))
				{
					return true;
				}
				else
				{
					obj.checked = true;
					return false;
				}
			}
			else
			{
				alert(aux);
				obj.checked = true;
				return false;
			}
		}
	
		
	}



{/literal}
{/if}
{if $t eq 'c3lzX3VzdWFyaW9z'}
{literal}



function verificaGrids()
{
	//funcion que guarda el valor $llave en los grid vacios
	
	//alert('WTF');
	
	var grid="sucursalesusuarios";
	var numf=NumFilas(grid);
	var id;
	for(var i=0;i<numf;i++)
	{
		id=celdaValorXY(grid,0,i);
		if(id.length==0)
		{
			valorXY(grid,1,i,'$llave');
		}
	}
	grid="gruposusuarios";
	numf=NumFilas(grid);	
	for(var i=0;i<numf;i++)
	{
		id=celdaValorXY(grid,0,i);
		if(id.length==0)
		{
			valorXY(grid,1,i,'$llave');
		}
	}
}

function validaGrupos()
{
	var grid="gruposusuarios";
	var numf=NumFilas(grid);
	var activo;
	var objGrid=document.getElementById(grid);
	if(!objGrid)
		return false;
	objGrid.validaElimina="true";
	objGrid.setAttribute("validaElimina","true");	
	for(var i=numf-1;i>=0;i--)
	{
		activo=celdaValorXY(grid,4,i);
		if(activo==0)
		{			
			EliminaFila(grid+"_4_"+i)			
		}
	}
	return true;
}
verificaGrids()

{/literal}
{/if}
{literal}



{/literal}
{if $t eq 'YW5kZXJwX2ZvbGlvcw=='} /* Folios*/
{literal}


function obtenComboSeries()
{
alert("obtenComboSeries 1");
	var objTipoDoc=document.getElementById("id_tipo_documento");
	if(!objTipoDoc)
		return false;
	if(objTipoDoc.type!="select-one")
		return false;	
	var objSerie=document.getElementById("id_serie");
	if(!objSerie)
		return false;
	if(objSerie.type!="select-one")
		return false;
	var opcionSel=objSerie.value;
	limpiaCombo(objSerie);
	var respuesta=ajaxR("../ajax/catalogos/altafolios.php?opcion=2&documento="+objTipoDoc.value);
	var arrResp=respuesta.split("|");
	if(arrResp[0]!="exito")
	{
		alert(respuesta);
		return false;
	}
	if(arrResp[1]>0)
	{
		var numDatos=parseInt(arrResp[1]);		
		for(var i=0;i<numDatos;i++)
		{
			var arrDatos=arrResp[i+2].split("~");			
			if(arrDatos[0]==opcionSel)
				objSerie.options[i]=new Option(arrDatos[1], arrDatos[0],"defaultSelected");
			else
				objSerie.options[i]=new Option(arrDatos[1], arrDatos[0]);
		}
	}
	obtenUltimoFolio();
	return true;
}

function obtenUltimoFolio()
{
alert("obtenUltimoFolio 2");

	var objDoc=document.getElementById("id_tipo_documento");
	if(!objDoc)
		return false;	
	var objSerie=document.getElementById("id_serie");
	if(!objSerie)
		return false;	
	var objGrid=document.getElementById("fuentesfolios");
	if(!objGrid)
		return false;
	var url=objGrid.getAttribute("Datos");
	url+="&documento="+objDoc.value+"&serie="+objSerie.value;
	RecargaGrid("fuentesfolios",url);
}
if(!obtenComboSeries())
	obtenUltimoFolio();

{/literal}
{/if}
{literal}


{/literal}
{if $t eq 'YW5kZXJwX2ZvbGlvcw=='} /*folios*/
{literal}


function obtenComboFuentes()
{
alert("ontencombofuentes 3");
	var objTipoDoc=document.getElementById("id_tipo_documento");
	if(!objTipoDoc)
		return false;
	if(objTipoDoc.type!="select-one")
		return false;
	var objFuente=document.getElementById("id_fuente");
	if(!objFuente)
		return false;
	if(objFuente.type!="select-one")
		return false;
	var opcionSel=objFuente.value;
	limpiaCombo(objFuente);
	var respuesta=ajaxR("../ajax/catalogos/altafolios.php?opcion=1&tipodoc="+objTipoDoc.value);
	var arrResp=respuesta.split("|");
	if(arrResp[0]!="exito")
	{
		alert(respuesta);
		return false;
	}
	if(arrResp[1]>0)
	{
		var numDatos=parseInt(arrResp[1]);		
		for(var i=0;i<numDatos;i++)
		{
			var arrDatos=arrResp[i+2].split("~");
			if(arrDatos[0]==opcionSel)
				objFuente.options[i]=new Option(arrDatos[1], arrDatos[0],"defaultSelected");
			else
				objFuente.options[i]=new Option(arrDatos[1], arrDatos[0]);
		}		
	}
	obtenComboSerie();
	return true;
}

function obtenComboSeries()
{
//alert("obtenComboseries 4");
	var objTipoDoc=document.getElementById("id_tipo_documento");	
	if(!objTipoDoc)
		return false;
	if(objTipoDoc.type!="select-one")
		return false;	
	var objSerie=document.getElementById("id_serie");
	if(!objSerie)
		return false;
	if(objSerie.type!="select-one")
		return false;
	var opcionSel=objSerie.value;
	limpiaCombo(objSerie);
  // alert("doc "+objTipoDoc.value);
	var respuesta=ajaxR("../ajax/catalogos/altafolios.php?opcion=2&documento="+objTipoDoc.value);
	var arrResp=respuesta.split("|");
	if(arrResp[0]!="exito")
	{
		alert(respuesta);
		return false;
	}
	if(arrResp[1]>0)
	{
		var numDatos=parseInt(arrResp[1]);		
		for(var i=0;i<numDatos;i++)
		{
			var arrDatos=arrResp[i+2].split("~");			
			if(arrDatos[0]==opcionSel)
				objSerie.options[i]=new Option(arrDatos[1], arrDatos[0],"defaultSelected");
			else
				objSerie.options[i]=new Option(arrDatos[1], arrDatos[0]);
		}
	}
	obtenUltimoFolio();
	return true;
}

function obtenUltimoFolio()
{

//alert("obtenUltimoFolio 5");
	var objDoc=document.getElementById("id_tipo_documento");
	if(!objDoc)
		return false;	
	var objSerie=document.getElementById("id_serie");
	if(!objSerie)
		return false;	
	var objGrid=document.getElementById("fuentesfolios");
	if(!objGrid)
		return false;
	var url=objGrid.getAttribute("Datos");
	url+="&documento="+objDoc.value+"&serie="+objSerie.value;
	//alert(url);
	RecargaGrid("fuentesfolios",url);
}

if(!obtenComboSeries()){
    //alert("obtencomboserie 7");
	obtenUltimoFolio();
}


function verificaSerieDocumento()
{
alert("verificaSeirDocumento 6");	
var objserie=document.getElementById("id_serie");	
	var objdocumento=document.getElementById("id_tipo_documento");	
	if(!objserie)
		return false;
	if(!objdocumento)
		return false;
	var respuesta=ajaxR("../ajax/catalogos/altafolios.php?opcion=4&idserie="+objserie.value+"&tipodocumento="+objdocumento.value);
	var arrResp=respuesta.split("|");
	if(arrResp[0]!="exito")
	{
		alert(respuesta);
		return false;
	}
	if(arrResp[1]!=0)
	{
		alert("Ya se encuentra dado de alta una serie de folios con la serie y el tipo de documento especificado.")
		return false;
	}
	return true;
}

function abrirArchivo(pos)
{
	var nom_archivo=celdaValorXY("archivosfolios",2,pos);
	if(nom_archivo.length>0)
	{
		abrirVentana("../../archivos_folios/"+nom_archivo+"|800|600");
	}
	return true;
}


{/literal}
{elseif $t eq 'c3lzX3VzdWFyaW9z'}
{literal}

	function validaGR(obj)
	{
		var id=document.getElementById('id_usuario');
		if(obj.checked == false && id.value != '')
		{
			aux=ajaxR("../ajax/validaPV.php?id_usuario="+id.value);
			if(aux != 'exito')
			{
				alert(aux);
				obj.checked=true;
			}	
		}
	}
	
	
	document.getElementById('password2').value = document.getElementById('pass').value;

{/literal}
{/if}


{if $t eq 'cmFjX3BlZGlkb3M='}
{literal}
	/*
	seleccionado = $("#requiere_presupuesto_flete:checked" ).length;
	if(seleccionado == 0)
		$("#fila_catalogo_46").hide();
	else
		$("#fila_catalogo_46").show();
	
	seleccionado = $("#requiere_presupuesto_viaticos:checked" ).length;
	if(seleccionado == 0)
		$("#fila_catalogo_48").hide();
	else
		$("#fila_catalogo_48").show();

	seleccionado = $("#requiere_presupuesto_motaje:checked" ).length;
	if(seleccionado == 0)
	{
		$("#fila_catalogo_50").hide();
		$("#fila_catalogo_51").hide();
	}
	else
	{
		$("#fila_catalogo_50").hide();
		$("#fila_catalogo_51").hide();
	}
		

	$("#requiere_presupuesto_flete").on("click", function(){
		seleccionado = $("#requiere_presupuesto_flete:checked" ).length;
		
		if(seleccionado == 0)
		{
			$("#fila_catalogo_46").hide();
			$("#monto_flete_registrado_logistica").val(0);
			//$("#monto_flete_gerente_ventas").val(0);
		}
		else
		{
			$("#fila_catalogo_46").show();
		}
	});
	
	$("#requiere_presupuesto_viaticos").on("click", function(){
		seleccionado = $("#requiere_presupuesto_viaticos:checked" ).length;
		
		if(seleccionado == 0)
		{
			$("#fila_catalogo_48").hide();
			$("#monto_viaticos_logistica").val(0);
			//$("#monto_viaticos_gerente_ventas").val(0);
		}
		else
		{
			$("#fila_catalogo_48").show();
		}
	});
	
	
	$("#requiere_presupuesto_motaje").on("click", function(){
		seleccionado = $("#requiere_presupuesto_motaje:checked" ).length;
		
		if(seleccionado == 0)
		{
			$("#fila_catalogo_50").hide();
			$("#fila_catalogo_51").hide();
			$("#monto_montaje_gerente_ventas").val(0);
			$("#monto_montaje_extraorinario_gerente_ventas").val(0);
			$("#monto_desmontaje_gerente_ventas").val(0);
		}
		else
		{
			$("#fila_catalogo_50").show();
			$("#fila_catalogo_51").show();
		}
	});
	*/
	
{/literal}
{/if}

</script>