 $(document).ready(function() {
     
	 
	
    
 });
	

//FUNCION QUE  ABRE  LA PANTALLA  VER LA LOCALIZACIÓN DE LOS ARTICULOS
function verSustitutos(id){
	var articulo=$('#detalleArticulosSustitutos_2_'+id).attr('valor');
	if(articulo!='')
	{
		 var ruta="../especiales/verArticulosSustitutos.php?action=0&height=600&width=1100&modal=false&id_articulo="+articulo;
		 abrirThickBox(ruta);
	}
	else
	{
		alert("Debe seleccionar un articulo, para ver su imagen");
	}
}

//Abrir formato para un contacto nuevo 
function abrirThickBox(ruta){
	var obj=document.getElementById('thickbox_href');
	obj.setAttribute('href',ruta);
	obj.click();		
 }
	
