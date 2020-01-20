 $(document).ready(function() {
     
    
    
 });

//importar todas las direcciones
function importar_direccion(direccion)
{
	var array = new Object(); 
			array['accion'] =4;
			array['id_direccion'] =direccion;
			array['id_cliente'] =$('#id_cliente').val();
			$.ajax({
				    url: '../../code/ajax/getPreciosArticulos.php',
					data:array,
					type: 'GET',
				success: function(resp){ 
				        //alert(resp);
						guardar($('#id_cliente').val(),resp);
				},
				error: function(data) {
					alert('Error de al conectar con el servidor'); //or whatever
				}
	   		 });	
	
}


//Extraer todas las direcciones del listador, del buscador
function guardarDireccion(){
 	var table = document.getElementById("tablaDatos");
        var rowCount = table.rows.length;
		for(var i=0; i<rowCount; i++) {
			var row = table.rows[i];
            var chkbox = row.cells[0].childNodes[0];
			if(null != chkbox && true == chkbox.checked) 
			{
				var tem=chkbox.id; 
				var idCombo=tem.split('|');
				importar_direccion(idCombo[0]);	
						
			}			  
		}
	alert("Las direcciones fueron agragadas correctamente");	
	tb_remove();
}

//Guardar direcciones
function guardar(cliente,direccion)
{
	 var array = new Object(); 
	 array['action'] =4;
	 array['id_direccion'] =direccion;
	 array['id_cliente'] =cliente;
	 $.ajax({
	        url: '../../code/especiales/direccionExistente.php',
		    data:array,
			type: 'GET',
			success: function(resp){ 
				     	
					 
			  },
			error: function(data) {
					alert('Error de al conectar con el servidor'); //or whatever
			  }
		});	
}
	
//Abrir formato para un contacto nuevo 
function abrirThickBox(ruta){
	var obj=document.getElementById('thickbox_href');
	obj.setAttribute('href',ruta);
	obj.click();		
 }
	


//FUNCION QUE  ABRE  LA PANTALLA  VER LOS PRODUCTOS SUSTITUTOS
function articulos_sustitutos_existencia(id){
	    //sacar la fila donde se encuentran los datos
	var idRenglonArticulo='#detalleArticulos_2_'+id;
	var idRenglonNombre='#detalleArticulos_3_'+id;
	    //Sacar id articulo y  el nombre
	var articulo=$(idRenglonArticulo).attr('valor');
	var  nombre=$(idRenglonNombre).attr('valor');
	
	    //Se valida que esxista una fecha
	if( $('#fecha_entrega_articulos').val()!='' ){
		var ruta="../especiales/detalle_articulos.php?action=1&height=600&width=1100&modal=false&valor="+articulo+"&nombre="+nombre;
		//abrirThickBox(ruta);
		window.open(ruta,"","width=800,height=500");
	}else{
		alert("Debe seleccionar una fecha de entrega"); 
	}
}

//FUNCION QUE  ABRE  LA PANTALLA  VER LA LOCALIZACI�N DE LOS ARTICULOS
function articulos_ver_localizacion(){
	var ruta="../especiales/detalle_articulos.php?action=0&height=600&width=1100&modal=false";
	abrirThickBox(ruta);
}


//FUNCION PARA SACR EL NUMEROS DE FILAS DEL GRID
function NumFilas(tabla)
{	
	var obj=document.getElementById('Body_'+tabla);
	if(!obj)
		return null;
	var Trs=obj.getElementsByTagName('tr');
	return Trs.length;
}




function buscadorDirecciones(){
	
  	direcciones();
}


function direccionesExistentes(){
	 var ruta="../especiales/direccionExistente.php?action=1&height=600&width=1100&modal=false";
	 abrirThickBox(ruta);	
}

//VERIFICA SI EXISTE LA DIRECCION EN EL GRID
function verificarLugar(id_direccion,grid)
{
	for(i=0;i<NumFilas(grid);i++)
	 {
		if ($("#clientesDireccionEvento_24_"+i).attr('valor')==id_direccion)
		{
		 return false;	
		}
	 }	
	return true;
}

//VERIFICA SI EXISTE LA DIRECCION EN EL GRID
function verificarContacto(id_direccion,grid,contacto)
{
	var bandera=true;
 	for(i=0;i<NumFilas(grid);i++)
	{
		//alert($("#clientesContactos_23_"+i).attr('valor')+'---->'+id_direccion+'         '+$("#clientesContactos_22_"+i).attr('valor')+' -->  '+contacto);
		//alert(contacto);
		if ($("#clientesContactos_23_"+i).attr('valor')==id_direccion &&$("#clientesContactos_22_"+i).attr('valor')==contacto )
		{
		   bandera=false;
		}
	}	
	if(bandera==false)
	{
		return false;
	}else
	{
		return true;
	}
}

function buscador()
{
	$("#tablaDatos").find("tr:gt(0)").remove();
	var array = new Object(); 
			array['action'] =2;
			array['nombre'] =$('#nombrebus').val();
			array['lugares'] =$('#lugares').val();
			$.ajax({
				    url: '../../code/especiales/buscador_Direccion.php',
					data:array,
					type: 'GET',
				success: function(resp){ 
				     
					 var datos=resp;
					 //alert(resp);
					 var renglones=datos.split('|');
					
					 for(i=1;i<renglones.length;i++){
						 var row=renglones[i].split('~');	 
						 var id=row[18]+'|'+row[19]+'|'+row[20]+'|'+row[21];
						 $("#tablaDatos > tbody").addClass('celda_Texto').append("<tr> <td><input type='checkbox' id="+id+" /> </td><td>"+row[0]+"</td><td>"+row[1]+"</td><td>"+row[2]+"</td><td>"+row[3]+"</td><td>"+row[4]+"</td><td>"+row[5]+"</td><td>"+row[6]+"</td><td>"+row[7]+"</td><td>"+row[8]+"</td><td>"+row[9]+"</td><td>"+row[10]+"</td><td>"+row[11]+"</td><td>"+row[12]+"</td><td>"+row[13]+"</td><td>"+row[14]+"</td><td>"+row[15]+"</td><td>"+row[16]+"</td><td>"+row[17]+"</td></tr>");
					 }
				$("#datosBusquedaBody").html("");	
						
				},
				error: function(data) {
					alert('Error de al conectar con el servidor'); //or whatever
				}
	   		 });	
}

function ver_todos()
{
	$("#tablaDatos").find("tr:gt(0)").remove();
	var array = new Object(); 
			array['action'] =3;
			$.ajax({
				    url: '../../code/especiales/buscador_Direccion.php',
					data:array,
					type: 'GET',
				success: function(resp){ 
				     
					 var datos=resp;
					 var renglones=datos.split('|');
					
					 for(i=1;i<renglones.length;i++){
						 var row=renglones[i].split('~');	 
						 var id=row[18]+'|'+row[19]+'|'+row[20]+'|'+row[21];
						 $("#tablaDatos > tbody").addClass('celda_Texto').append("<tr> <td><input type='checkbox' id="+id+" /> </td><td>"+row[0]+"</td><td>"+row[1]+"</td><td>"+row[2]+"</td><td>"+row[3]+"</td><td>"+row[4]+"</td><td>"+row[5]+"</td><td>"+row[6]+"</td><td>"+row[7]+"</td><td>"+row[8]+"</td><td>"+row[9]+"</td><td>"+row[10]+"</td><td>"+row[11]+"</td><td>"+row[12]+"</td><td>"+row[13]+"</td><td>"+row[14]+"</td><td>"+row[15]+"</td><td>"+row[16]+"</td><td>"+row[17]+"</td></tr>");
					 }
				$("#datosBusquedaBody").html("");	
						
				},
				error: function(data) {
					alert('Error de al conectar con el servidor'); //or whatever
				}
	   		 });	
}