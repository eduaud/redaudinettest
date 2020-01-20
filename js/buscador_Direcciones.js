 $(document).ready(function() {
     

    
 });
	
function importar_contactos(direccion)
{
	var array = new Object(); 
			array['accion'] =2;
			array['id_direccion'] =direccion;
			array['id_cliente'] =$('#id_cliente').val();
			$.ajax({
				    url: '../../code/ajax/getPreciosArticulos.php',
					data:array,
					type: 'GET',
				success: function(resp){ 
				         
						var numContacto=resp.split('|');
						var idContacto="";
						var idDireccion="";
						for(i=1;i<numContacto.length;i++)
						{ 
						        var datos=numContacto[i].split('~'); 
								//if(verificarContacto(direccion,'clientesContactos',datos[15])==false){
									//alert("El contacto ya existe en la lista");
									
								//}else{
									if(NumFilas('clientesContactos')==1 && $("#clientesContactos_2_"+0).attr('valor')==""){
										var fila=NumFilas('clientesContactos')-1;		
									}else{
										var fila=NumFilas('clientesContactos');
										nuevoGridFila('clientesContactos');
									}
									 
								
									
									aplicaValorXY('clientesContactos',2,fila,datos[0]);
									aplicaValorXY('clientesContactos',3,fila,datos[1]);
									aplicaValorXY('clientesContactos',4,fila,datos[2]);
									aplicaValorXY('clientesContactos',5,fila,datos[4]);
									aplicaValorXY('clientesContactos',6,fila,datos[4]);
									aplicaValorXY('clientesContactos',7,fila,datos[3]);
									aplicaValorXY('clientesContactos',8,fila,datos[3]);
									aplicaValorXY('clientesContactos',9,fila,datos[5]);
									aplicaValorXY('clientesContactos',10,fila,datos[6]);
									aplicaValorXY('clientesContactos',11,fila,datos[7]);
									aplicaValorXY('clientesContactos',12,fila,datos[8]);
									aplicaValorXY('clientesContactos',13,fila,datos[9]);
									aplicaValorXY('clientesContactos',14,fila,datos[10]);
									aplicaValorXY('clientesContactos',15,fila,datos[11]);
									aplicaValorXY('clientesContactos',16,fila,datos[12]);
									aplicaValorXY('clientesContactos',17,fila,datos[12]);
									aplicaValorXY('clientesContactos',18,fila,datos[13]);
									aplicaValorXY('clientesContactos',19,fila,datos[13]);
									aplicaValorXY('clientesContactos',20,fila,datos[14]);
									aplicaValorXY('clientesContactos',21,fila,datos[14]);
									//aplicaValorXY('clientesContactos',22,fila,datos[15]);
									//aplicaValorXY('clientesContactos',23,fila,direccion);
									 idContacto='#clientesContactos_22_'+fila;
									 $(idContacto).attr('valor',datos[15]);
									 idDireccion='#clientesContactos_23_'+fila;
									 $(idDireccion).attr('valor',direccion);
								
						}
				},
				error: function(data) {
					alert('Error de al conectar con el servidor'); //or whatever
				}
	   		 });	
	
}

function guardarDireccion(){
 
  var table = document.getElementById("tablaDatos");
        var rowCount = table.rows.length;
		
	
		for(var i=0; i<rowCount; i++) {
					
			var row = table.rows[i];
            var chkbox = row.cells[0].childNodes[0];
			
			if(null != chkbox && true == chkbox.checked) {
				
				var tem=chkbox.id; 
				var idCombo=tem.split('|');
				
				
				if(verificarLugar(idCombo[0],'clientesDireccionEvento')==false)
				{
					
					 alert("La dirección ya se encuentra en el listado");
				}
				else{
						
					
						var cl16=row.cells[16].childNodes[0].nodeValue;
						var cl17=row.cells[17].childNodes[0].nodeValue;
						var cl18=row.cells[18].childNodes[0].nodeValue;
						var cl15=row.cells[15].childNodes[0].nodeValue;
						var cl14=row.cells[14].childNodes[0].nodeValue;
						var cl13=row.cells[13].childNodes[0].nodeValue;
						
						var cl12=row.cells[12].childNodes[0].nodeValue;
						var cl11=row.cells[11].childNodes[0].nodeValue;
						var cl10=row.cells[10].childNodes[0].nodeValue;
						var cl9=row.cells[9].childNodes[0].nodeValue;
						var cl8=row.cells[8].childNodes[0].nodeValue;
						
						var cl7=row.cells[7].childNodes[0].nodeValue;
						var cl6=row.cells[6].childNodes[0].nodeValue;
						var cl5=row.cells[5].childNodes[0].nodeValue;
						var cl4=row.cells[4].childNodes[0].nodeValue;
						var cl3=row.cells[3].childNodes[0].nodeValue;
						
						var cl2=row.cells[2].childNodes[0].nodeValue;
					
					   
				    if(NumFilas('clientesDireccionEvento')==1 && $("#clientesDireccionEvento_6_"+0).attr('valor')==''){
					   //
					   //alert(NumFilas('clientesDireccionEvento')+'     '+$("#clientesDireccionEvento_6_"+0).text());
					   var filas=NumFilas('clientesDireccionEvento')-1;	
					 //  nuevoGridFila('clientesDireccionEvento');
					}else{
						 var filas=NumFilas('clientesDireccionEvento');		
						 nuevoGridFila('clientesDireccionEvento');
					      
					}
					valorXY('clientesDireccionEvento',23,filas,cl18);
					valorXY('clientesDireccionEvento',22,filas,cl17);
					valorXY('clientesDireccionEvento',21,filas,cl16);
					valorXY('clientesDireccionEvento',20,filas,cl15);
					valorXY('clientesDireccionEvento',19,filas,cl14);
					valorXY('clientesDireccionEvento',18,filas,cl13);
					valorXY('clientesDireccionEvento',17,filas,cl12);
					valorXY('clientesDireccionEvento',16,filas,cl11);
					valorXY('clientesDireccionEvento',15,filas,cl10);
					valorXY('clientesDireccionEvento',14,filas,cl9);
					valorXY('clientesDireccionEvento',13,filas,cl8);
					valorXY('clientesDireccionEvento',12,filas,idCombo[3]);
					valorXY('clientesDireccionEvento',11,filas,idCombo[3]);
					valorXY('clientesDireccionEvento',10,filas,cl6);
					valorXY('clientesDireccionEvento',9,filas,cl5);
					valorXY('clientesDireccionEvento',8,filas,cl4);
					valorXY('clientesDireccionEvento',7,filas,cl3);
					valorXY('clientesDireccionEvento',5,filas,idCombo[2]);
					valorXY('clientesDireccionEvento',4,filas,idCombo[2]);
					valorXY('clientesDireccionEvento',2,filas,idCombo[1]);
					valorXY('clientesDireccionEvento',3,filas,idCombo[1]);
					valorXY('clientesDireccionEvento',24,filas,idCombo[0]);
					valorXY('clientesDireccionEvento',6,filas,cl2);
					
					var importar=confirm("¿Desea importar los contactos de: "+row.cells[2].childNodes[0].nodeValue+"  lugar:  "+row.cells[1].childNodes[0].nodeValue +"?");
					    if(importar)
						{
						
						 importar_contactos(idCombo[0]);	
						}
					
				}
              }
			  
		}
		
		tb_remove();
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


function direcciones(){
	 var ruta="../especiales/buscador_Direccion.php?action=1&height=600&width=1100&modal=false";
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