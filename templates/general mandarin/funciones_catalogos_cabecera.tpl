{*Funciones antes del body*}

{literal}
<script language="javascript">
/*Funcion para catalogo clientes combo  Forma de Pago SAT*/
function validaNoCuenta(obj){
   
  // alert(obj+''+obj.value);
   //variables
   var f=document.forma_datos;
   var v_formapagoSat = obj.value;
   
   if(v_formapagoSat == 1 || v_formapagoSat == 2){ //si la forma de pago sat es NO IDENTIFICADO id (1)
      f.no_cuenta.value ='NO IDENTIFICADO';
	  f.no_cuenta.readOnly = true;
   }
   else{
       f.no_cuenta.value ='';
	   f.no_cuenta.readOnly = false;
   }
	  

}//validaNoCuenta
/**************************************************************/

/*Funcion para inicializar las pantallas de edicion  dependientdo de cada caso*/
/*********************************************
Pantalla Productos en onload carga Combo 
**/
function inicia(){

//variables
	   var f=document.forma_datos;

	   //productos
       if(f.t.value=="YW5kZXJwX3Byb2R1Y3Rvcw=="){
	       //inicializa los combos relacionados con el Tipo de Producto Default

	       var id_prod = f.id_producto.value;
           var accion_pant = 0;
	
		   if(id_prod == '') 
		       accion_pant=2; //nuevo
		   else 
		       accion_pant=5; //edicion 
	  
   if(f.id_producto_tipo_default.options[f.id_producto_tipo_default.selectedIndex].text !='')
    {
		//tipo producto default
		var name = f.id_producto_tipo_default.options[f.id_producto_tipo_default.selectedIndex].text;
		var id = f.id_producto_tipo_default.options[f.id_producto_tipo_default.selectedIndex].value;  
			
	   //presentacion default producto
		var id_prest_default  = f.id_presentacion_default.options[f.id_presentacion_default.selectedIndex].value;        var id_prest_default_txt =  f.id_presentacion_default.options[f.id_presentacion_default.selectedIndex].text;
	
		//obtenemos las presentaciones para el tipo de producto seleccionado	
	    var aux = ajaxR("../ajax/getDatosProducto.php?accion=1&id_prod_tipo="+id);  
		var ax=aux.split("|");
		
	
		 if(ax[0] != 'exito')
			{
			  alert(aux);
			  f.id_presentacion_default.options.length = 0;								
			  return false;
			}
			
		//nuevo 
		if(accion_pant == 2){
		   f.id_presentacion_default.options.length = 0;
		   	for(var i=2; i < ax.length; i++ )
				{
				  var a=ax[i].split('~');
				  f.id_presentacion_default.options[i-2] = new Option(a[1], a[0]);
				}
		}
		
		//edicion
		if(accion_pant == 5){
		f.id_presentacion_default.options.length = 0;
		   
			  	for(var i=2; i < ax.length; i++ )
				{
				  var a=ax[i].split('~');
				
				  if(a[0] == id_prest_default){
				    f.id_presentacion_default.options[i-2] = new Option(a[1], a[0]);
					f.id_presentacion_default.options[i-2].selected = true;
				  }
				  else{
				     f.id_presentacion_default.options[i-2] = new Option(a[1], a[0]);
				  }
				}
		}	

			
			
			
			
			   if(accion_pant == 2){ //cuando en nuevo 		
				 //recorre grid de productos 
				 var grid="detalleproducto";
			     var nfil=NumFilas(grid);
			     
			  	 for(var i=0;i<nfil;i++)
	              {
						valorCeldaXY(grid,2,i,id); //coloca id  producto tipo
						valorCeldaXY(grid,3,i,name); //llena celda de tipo producto
						valorCeldaXY(grid,4,i,''); //limpia id presentacion 
						valorCeldaXY(grid,5,i,''); //limpia presentacion 

				  }//fin for i
		        }//fin de accion pant nuevo
	
			 
	     }//fin if
       }//fin de if productos
	   
	   //notas de venta
	  if(f.t.value=="YW5kZXJwX25vdGFzX3ZlbnRh"){
	     cambiaVendedor();
	  }
	   
}//fin de funcion inicia
/****************************************/
/***************************************/
/*Actualiza el campo id_presentacion del detalle de productos grid('detalleproducto')*/

function actualizaPresentacion(grid,posXBuscador,posXOculto, filaAct)
{
//alert("Entro a actualiza presentacion"+grid+','+posXBuscador+','+posXOculto+', '+filaAct);
	var cadBuscador=celdaValorXY(grid,posXBuscador,filaAct);
//alert(cadBuscador);

	var id_presentacion = 0;
	if(cadBuscador.indexOf(":")!=-1)
	{
		var arrBusq=cadBuscador.split(":");
		id_presentacion = arrBusq[0]; 	
	}
	else
		id_presentacion=cadBuscador;
	
	var f=document.forma_datos;
	
	//coloca el id_presentacion en la celda 4 del grid 	
    // aplicaValorXY(grid,4,filaAct,id_presentacion);

        valorCeldaXY(grid,4,filaAct,id_presentacion);
	
	/*var respAjax=ajaxR("../ajax/catalogos/general.php?opcion=1&id_sucursal="+id_sucursal);
	var arrResp=respAjax.split("|");
	if(arrResp[0]!="exito")
	{
		alert(respAjax);
		return false;
	}
	valorXYNoOnChange(grid,posXBuscador,filaAct,arrResp[3]);
	valorXY(grid,posXOculto,filaAct,arrResp[2]);
	return true;*/	
}
/**************
Pantalla Productos cuando selecciona Tipo de Producto aparece en grid el combo con las presentaciones del tipo de producto*/
function CambiaPresentacion(grid,pos){
         //variables
         var f=document.forma_datos;
		 var id_prod = f.id_producto.value;
         var accion_pant = 0;
		 
		if(id_prod == '') 
		   accion_pant=2; //nuevo
		else 
		   accion_pant=5; //edicion 
		 
		      
	   if(f.id_producto_tipo_default.options[f.id_producto_tipo_default.selectedIndex].text !='')
	   {
	   	      var name = f.id_producto_tipo_default.options[f.id_producto_tipo_default.selectedIndex].text;
			  var id = f.id_producto_tipo_default.options[f.id_producto_tipo_default.selectedIndex].value;   
			  var aux = ajaxR("../ajax/getDatosProducto.php?accion=1&id_prod_tipo="+id);
			  //obtenPresentaciones.php
			 // alert(aux);
			  
			  var ax=aux.split("|");
				
				if(ax[0] != 'exito')
				{
					alert(aux);
					f.id_presentacion_default.options.length = 0;								
					return false;
				}
			
				f.id_presentacion_default.options.length = 0;
			
		
				for(var i=2; i < ax.length; i++ )
				{
					 var a=ax[i].split('~');
					f.id_presentacion_default.options[i-2] = new Option(a[1], a[0]);
				}
					
					 
			  if(accion_pant == 2){ //cuando en nuevo
			  
			    
				//recorre grid de productos 
			
		       	var nfil=NumFilas(grid);
	
			  
			  	for(var i=0;i<nfil;i++)
	           {
	            //alert(celdaValorXY(grid,2,i));
				valorCeldaXY(grid,2,i,id); //coloca id tipo producto 
		        valorCeldaXY(grid,3,i,name); //llena celda de tipo producto
				valorCeldaXY(grid,4,i,''); //limpia id_presentacion 
				valorCeldaXY(grid,5,i,''); //limpia presentacion 
	          }
	
					
			}
		       
	   }

}//fin de funcion CambiaPresentacion
/*************************/





/**
Funciones para Pantalla Notas de Venta
**********************/

function activaTipoProd(grid,pos){
  /*Activa la celda de tipo de producto  en el grid
  *****/
   var f = document.forma_datos;
   
   if(f.t.value == 'YW5kZXJwX25vdGFzX3ZlbnRh'){//Pantalla Notas de Venra
	   var id_prod = celdaValorXY(grid,2,pos);
	   
	   //alert(id_prod);
	}

			
			
	
     var aux = ajaxR("../ajax/getDatosProducto.php?accion=8&id_prod="+id_prod);
 
   //alert(aux);
   var ax=aux.split("|");
				
	 if(ax[0] != 'exito')
	  {
		alert(aux);
		f.id_direccion_entrega.options.length = 0;								
		return false;
	  }
	 if(ax[0] == 'exito'){
		  var getId = ax[2].split("~");
		  var id_tipo = getId[0];
		  var nomb = getId[1];
		  
		  
		  
		  
		  valorCeldaXY(grid,3,pos,id_tipo); //coloca id tipo producto 
		  aplicaValorXY(grid,4,pos,nomb); //coloca id tipo producto  texto
		  
	 }
			

	 
	 

}

/************************************/
/*funcion para cargar las direcciones de entrega del cliente seleccionado en Notas de Venta*/
function cambiaDireccionCli(grid,pos){

   var f=document.forma_datos;
   
   if(f.t.value=='YW5kZXJwX25vdGFzX3ZlbnRh'){ //Pantalla Notas  de Venta
      var id_cli = f.selcampo_7.value;   
   }	  

   var aux = ajaxR("../ajax/getDatosProducto.php?accion=2&id="+id_cli);
   //obtenPresentaciones.php
   //alert(aux);
   var ax=aux.split("|");
				
	 if(ax[0] != 'exito')
	  {
		alert(aux);
		f.id_direccion_entrega.options.length = 0;								
		return false;
	  }
			
	f.id_direccion_entrega.options.length = 0;
			         	
	for(var i=2; i < ax.length; i++ )
	 {
	   var a=ax[i].split('~');
	   f.id_direccion_entrega.options[i-2] = new Option(a[1], a[0]);					
	 }
 
}



/*************************************/
/*Carga combo de vendedor dependiendo de la ruta seleccionada en Notas de Venta*/

function cambiaVendedor(){
 
   //variables
   var f=document.forma_datos; 
   var id = f.id_ruta.options[f.id_ruta.selectedIndex].value;   
   var idSucursal = f.id_sucursal.value;   
  
   var aux = ajaxR("../ajax/getDatosProducto.php?accion=3&id="+id);
   //obtenPresentaciones.php
   var ax=aux.split("|");
				
	 if(ax[0] != 'exito')
	  {
		alert(aux);
		f.id_vendedor.options.length = 0;								
		return false;
	  }
			
	f.id_vendedor.options.length = 0;
			         	
	for(var i=2; i < ax.length; i++ )
	 {
	   var a=ax[i].split('~');
	   f.id_vendedor.options[i-2] = new Option(a[1], a[0]);					
	 }
 
 	
	//-----------
	var aux2 = ajaxR("../ajax/getDatosProducto.php?accion=33&id="+id);
	
	//alert(aux2);
   	//obtenPresentaciones.php
  	var ax2=aux2.split("|");
				
	if(ax2[0] != 'exito')
	  {
		
		f.consecutivo.value = 0;								
		return false;
	  }
			
	f.consecutivo.value = ax2[1];
			         	
	//obtenemos la ultima fecha de captura
 	
	//-----------
	var aux3 = ajaxR("../ajax/getDatosProducto.php?accion=333&id="+idSucursal);
	
	//alert(aux2);
   	//obtenPresentaciones.php
  	var ax3=aux3.split("|");
				
	if(ax3[0] != 'exito')
	  {
		
		f.fecha_y_hora.value = 0;								
		return false;
	  }
			
	f.fecha_y_hora.value = ax3[1];
	
 	
 
}//fin cambiaVendedor

/***************************************/
function setDiasCreditoDefault(obj){
var v_name = obj.id;
var objTipoPago = document.getElementById(v_name);
var opcion = objTipoPago.options[objTipoPago.selectedIndex].value;

//alert(objTipoPago.options[objTipoPago.selectedIndex].value); 
if(opcion == 2){
//Colocamos 7 Dias de Credito para Todos los Clientes que utilizan Forma de Pago CREDITO
document.getElementById('dias_credito').value = 7;
}
else{
document.getElementById('dias_credito').value = 0;
}
   

}
/*****************************************/
function getFechaVencimiento(grid,pos){
 /*Obtiene La fecha de vencimiento.
 Apartir del dia de revision se obtiene una fecha y se suman los dias de credito para obtener la fecha de vencimiento
 *****/
   var f = document.forma_datos;
 //  var fech_act = f.fecha_revision.value;
  
	
	  if(f.t.value=='YW5kZXJwX25vdGFzX3ZlbnRh'){ //nota de venta
	     var id_cli = f.selcampo_7.value;   
	  }else{
	     if(f.t.value=='YW5kZXJwX2N1ZW50YXNfcG9yX2NvYnJhcg=='){//cuentas por cobrar
		    var id_cli = f.selcampo_8.value;   
		 }
		 else{
		     if(f.t.value=='YW5kZXJwX2ZhY3R1cmFz'){//facturas
			     var id_cli = f.selcampo_7.value; 
			 } 
		 }
	  }
	 
	  //agrega fecha de vencimiento  
	  //alert(id_cli);
	  var aux = ajaxR("../ajax/getDatosNotaVenta.php?accion=1&id_cli="+id_cli);
      //alert(aux); 
      var ax=aux.split("|"); 
	  
      if(ax[0] != 'exito'){
	     alert(ax[0]);
	     return false;
	  }
	  
	  
	  if(ax[0] == 'exito'){
	     var v_fecha_vencimiento = ax[1];
	  }
	  
	  
	  f.fecha_vencimiento.value = v_fecha_vencimiento;
   
   
 //Forma de Pago en notas de venta Cancelada  
   //avisa de que tipo de Forma de pago tiene el cliente seleccionado
      if(f.t.value=='YW5kZXJwX25vdGFzX3ZlbnRh'){ //nota de venta
              
	    var respuesta = ajaxR("../ajax/getDatosNotaVenta.php?accion=2&id_cli="+id_cli);
        // alert(respuesta); 
         var arrResp = respuesta.split("|"); 
		 if(arrResp[0] != 'exito'){
	        alert(arrResp[0]);
	        return false;
	     }
		// alert("La Forma de Pago del Cliente  es "+arrResp[1]);
	      	// alert(arrResp[2]); 
	
		 objTipoPago = document.getElementById('id_tipo_pago');
	
		objTipoPago.value=arrResp[2];
		// alert(objTipoPago.value);
		 
		 /*for(var i=0;i<objTipoPago.length;i++){
			 if(objTipoPago.options[i].value == arrResp[2]){
			   
			    objTipoPago.options[i].selected = true;
				alert("hey");
			 }
		 }*/
	 }
   
  
}
/********************************************/

function getFechaVencimientoPago(grid,pos){
 /*Obtiene La fecha de vencimiento.
 Apartir del dia de revision se obtiene una fecha y se suman los dias de credito para obtener la fecha de vencimiento
 *****/
	var f = document.forma_datos;
 //  var fech_act = f.fecha_revision.value;
	
	if(f.t.value=='YW5kZXJwX25vdGFzX3ZlbnRh'){ //nota de venta
		var id_cli = f.selcampo_7.value;   
	}else{
		if(f.t.value=='YW5kZXJwX2N1ZW50YXNfcG9yX2NvYnJhcg=='){//cuentas por cobrar
			var id_cli = f.selcampo_8.value;   
		}
		else{
		    if(f.t.value=='YW5kZXJwX2ZhY3R1cmFz'){//facturas
				var id_cli = f.selcampo_7.value; 
		 		} 
			}
	 }
	
	
	if(document.getElementById('id_tipo_pago').value==1 || id_cli=='')
	{
		
		var d = new Date();
		var curr_date = d.getDate();
		var curr_month = d.getMonth();
		curr_month++;
		var curr_year = d.getFullYear();
		f.fecha_vencimiento.value =curr_date + "/" + curr_month + "/" + curr_year
		return false;
		
	}
	
	
	
	  
	  var aux = ajaxR("../ajax/getDatosNotaVenta.php?accion=1&id_cli="+id_cli);
	  //alert(aux); 
	  var ax=aux.split("|"); 
	  
	  if(ax[0] != 'exito'){
		 alert(ax[0]);
		 return false;
	  }
	  
	  if(ax[0] == 'exito'){
		 var v_fecha_vencimiento = ax[1];
	  }
	  f.fecha_vencimiento.value = v_fecha_vencimiento;
	  
}

/*****************************************/
function getUnidadVentaProd(grid,pos){

    var f = document.forma_datos;
    var nfil=NumFilas(grid);
	
	//obtiene datos del producto seleccionado en GRID
	if(f.t.value == 'YW5kZXJwX25vdGFzX3ZlbnRh'){//Pantalla Notas de Venra
	   var id_prod = celdaValorXY(grid,2,pos);  //producto
	   var id_tipo_prod = celdaValorXY(grid,3,pos); //tipo 
	   var id_prod_present = celdaValorXY(grid,5,pos); //presentacion 
	}
	if(f.t.value == 'YW5kZXJwX2ZhY3R1cmFz'){ //Pantalla Facturas
	   var id_prod = celdaValorXY(grid,3,pos); //id_producto
	   var id_prod_present = celdaValorXY(grid,5,pos); //presentacion 
	}
		
//alert(id_prod+'-'+id_prod_present);
	
	var getId = id_prod.split(":");
	var id=getId[0];
	


	//obtiene la unidad de venta para ese producto con la presentacion seleccionada
	  var aux = ajaxR("../ajax/getDatosProducto.php?accion=6&id_prod_pres="+id_prod_present+"&id_prod="+id);
    //   alert(aux);
	  var ax = aux.split("|");
	  
	   if(ax[0] != 'exito'){
		alert(resp);
		return false;
	   }
	  
	  if(ax[0] == 'exito'){	
	    if(ax[1] == 0){
		   alert("Este Producto no tiene asignada la Presentación seleccionada.\nSeleccione otra Presentación.");
		   valorXY(grid,5,pos,2); //presentacion 
		   Foco(grid, 5, pos);
		   return false;
		}
		  
	  	if(f.t.value == 'YW5kZXJwX25vdGFzX3ZlbnRh'){//Pantalla Notas de Venra			
		   //coloca valores   
	       valorCeldaXY(grid,6,pos,ax[1]); //coloca id unidad de venta del producto seleccionadon en grid 
	   }
	   	if(f.t.value == 'YW5kZXJwX2ZhY3R1cmFz'){ //Pantalla Facturas
		    valorCeldaXY(grid,27,pos,ax[1]); //coloca id unidad de venta del producto seleccionado en grid   
		}
	   
	 }
	  

}
/***************************************/

  

/************************************/
/***
Actualiza importe, subtotal,total en nota de ventas
***********/
function actualizaImportes(grid,pos)
{
	var f = document.forma_datos;
	var objSubtotal=document.getElementById("subtotal");
	
	if(!objSubtotal)
		return false;

	var objIVA=document.getElementById("iva");
	
	if(!objIVA)
		return false;
	
	var objTotal=document.getElementById("total");
	
	if(!objTotal)
		return false;
	var nfil=NumFilas(grid);
	var subtotal=0;
	var montoiva=0;
	var total=0;
    for(var i=0;i<nfil;i++)
	{
	//columna de precio
	  if(f.t.value == 'YW5kZXJwX25vdGFzX3ZlbnRh'){//Pantalla Notas de Venra	
	     subtotal+=parseFloat((celdaValorXY(grid,15,i)=="")?0:celdaValorXY(grid,15,i));
	     montoiva+=parseFloat(celdaValorXY(grid,12,i));
	  }
	  if(f.t.value == 'YW5kZXJwX2ZhY3R1cmFz'){ //Pantalla Facturas
	     subtotal+=parseFloat((celdaValorXY(grid,19,i)=="")?0:celdaValorXY(grid,19,i));          //importe
	     montoiva+=parseFloat(celdaValorXY(grid,20,i));
	  }
	  //****************abc***************
	 if(f.t.value == 'b2ZfcGVkaWRvcw==' || f.t.value == 'ZnJfb3JkZW5lc19jb21wcmFfZnJhbnF1aWNpYQ=='){ //Pantalla Pedidos y OC Operadora Franquicia
	    //verificamos que la tasa iva y el monto no esten en vacios
		tasa_iva=parseFloat((celdaValorXY(grid,8,i)=="")?0:celdaValorXY(grid,8,i));
		monto=parseFloat((celdaValorXY(grid,6,i)=="")?0:celdaValorXY(grid,6,i));
		//calculamos el monto_iva
	    monto_iva=(tasa_iva/100)*monto;
		//asignamos el monto iva al campo correspondiente
		valorXY(grid,9,i,monto_iva);
		//alert("celda_"+i+" = "+celdaValorXY(grid,9,i));
		//Creamos las sumatorias para subtotal y monto iva
		subtotal+=parseFloat((celdaValorXY(grid,6,i)=="")?0:celdaValorXY(grid,6,i));
	    montoiva+=parseFloat(celdaValorXY(grid,9,i));
		
	  }
	  //**********************************
	  
	  	 
	}

     //alert("Subtotal="+subtotal+" -- montoiva="+montoiva+" -- total="+total);
	 objSubtotal.value=Mascara("###,###.##",redondear(subtotal,2));
	  //objIVA.value=Mascara("###,###.##",redondear(montoiva,2));
	  //var montoiva = 0;
     objIVA.value=Mascara("###,###.##",redondear(montoiva,2));
	 objTotal.value=Mascara("###,###.##",redondear(subtotal+montoiva,2));

	 
	
	return true;
}
/********************/
/*Calculo de Importe dependiendo de la unidad de venta del producto seleccionado
     El producto seleccionado tiene Unidad de Venta en KG Importe=Kilos Netos * Precio
	 El producto seleccionado tiene Unidad de Venta en PIEZAS Importe=Cantidad * Precio
*****/
function operacion(grid,pos){
  
   var f = document.forma_datos;
   
   //obtenemos la unidad de venta del producto seleccionado
	if(f.t.value == 'YW5kZXJwX25vdGFzX3ZlbnRh'){//Pantalla Notas de Venta
	  var getId_uv = celdaValorXY(grid,6,pos);
	}
	if(f.t.value == 'YW5kZXJwX2ZhY3R1cmFz'){//Pantalla Facturas
	   var getId_uv = celdaValorXY(grid,27,pos);
	}
//alert(getId_uv);
	if(getId_uv ==''){
	   alert("Seleccione un producto valido para realizar la operación.");
	   valorCeldaXY(grid,8,pos,''); 
	   return false;
	}


	if(parseInt(getId_uv) == 1){ //id_unidad_venta KG
	
	   //Importe = Kilos Netos * Precio
	   if(f.t.value=='YW5kZXJwX25vdGFzX3ZlbnRh'){//Pantalla Notas de Venta
	      var kn = celdaValorXY(grid,11,pos); //Kilos Netos
		  var precio = celdaValorXY(grid,13,pos); //Precio
	   } //FIN IF NOTAS VENTA 
	   
	   if(f.t.value=='YW5kZXJwX2ZhY3R1cmFz'){//Pantalla Facturas
		  var kn = celdaValorXY(grid,11,pos);  //kilos Netos
		  var precio = celdaValorXY(grid,13,pos); //Precio
	   }
	 
		if(parseInt(kn) == 0){
		  alert("Kilos Netos no puede ser Cero,Verifique.");
		  return false;
		}
		
	//	alert('operacion 1: '+kn+'-'+precio);
		
		if(precio=='') precio =0;
        if(kn=='') kn =0;
		
		var importe = ((parseFloat(kn) * parseFloat(precio)));
		//alert(importe);
	}
	
	
	if(parseInt(getId_uv) == 2){ //id_unidad_venta PIEZAS
	  //Importe = Cantidad * Precio
	   if(f.t.value=='YW5kZXJwX25vdGFzX3ZlbnRh'){//Pantalla Notas de Venta
	      var cantidad = celdaValorXY(grid,8,pos);
		  var precio = celdaValorXY(grid,13,pos);
	   } 	 
	   if(f.t.value=='YW5kZXJwX2ZhY3R1cmFz'){//Pantalla Facturas
	      var cantidad = celdaValorXY(grid,8,pos);
		  var precio = celdaValorXY(grid,13,pos);//precio
	   }	
	   
	   // alert(cantidad+' - '+precio);
		
		 if(precio =='') precio =0; 
		
		 var importe = (parseFloat(cantidad) * parseFloat(precio));
	}
	
	 // alert(cantidad +'-'+kn+'-'+precio+'-'+importe);
	  //alert(cantidad+'-'+precio);
	  
	  if(precio > 0){
	    if(f.t.value=='YW5kZXJwX25vdGFzX3ZlbnRh'){//Pantalla Notas de Venta
	       aplicaValorXY(grid,15,pos,importe);  //importe
		}
		if(f.t.value=='YW5kZXJwX2ZhY3R1cmFz'){//Pantalla Facturas
		    aplicaValorXY(grid,19,pos,importe);  //importe
		}
	  }
	  else{
	     if(f.t.value=='YW5kZXJwX25vdGFzX3ZlbnRh'){//Pantalla Notas de Venta
	        aplicaValorXY(grid,13,pos,precio); //precio
	        aplicaValorXY(grid,15,pos,importe);  //importe
		 }
		 if(f.t.value=='YW5kZXJwX2ZhY3R1cmFz'){//Pantalla Facturas
		    //alert(precio+' '+importe);
		    aplicaValorXY(grid,13,pos,parseFloat(precio)); //precio
	        aplicaValorXY(grid,19,pos,parseFloat(importe));  //importe
		 }
	  
	  }

}
/************************/
//Calcula Tara del producto seleccionado con x presentacion-----------------------------------------------------//
function getTara(grid,pos){
	//alert("Entro a calcular tara"+obj);
	
	//CALCULO DEL TARA
	      var getId_uv = celdaValorXY(grid,6,pos);
		  var id_prod_present = celdaValorXY(grid,5,pos); //presentacion
	
		  //si el producto seleccionado es de KG CALCULAMOS TARA con la Tara definida en Presentaciones por KG
	//	if(parseInt(getId_uv) == 1){ //id_unidad_venta KG	  
		   var aux = ajaxR("../ajax/getDatosProducto.php?accion=9&idPresentacion="+id_prod_present);
		  
           //alert(aux);
	       var ax = aux.split("|");
	  
		   if(ax[0] != 'exito'){
			  alert(resp);
			  return false;
		   }
		  // alert('calculo tara'+ax[1]);
		   if(ax[1] > 0){
			  //calculamos Tara
			  var xtara = parseFloat(ax[2]);
			  var xpesoBruto = parseFloat(celdaValorXY(grid,9,pos)); //Kilos Brutos
			  var xcantidad = parseFloat(celdaValorXY(grid,8,pos)); //Cantidad
			  
			  //operacion de tara 
			   // Kilos Netos = (cantidad * tara) - Kilos Brutos //Para todos los casos
			  var xcalculo = parseFloat(xcantidad * xtara);  
			  var taraxcaja = (parseFloat(xpesoBruto) - xcalculo);
			
			  if(taraxcaja > 0) var kilos_netos = taraxcaja; 
			  else var kilos_netos = ((taraxcaja) * (-1));
			  
			  var v_taracaja = kilos_netos.toFixed(2);
			 
			   //colocamos tara xtara
			  aplicaValorXY(grid,10,pos,xtara);	  
			   //coloca kilos netos
			  aplicaValorXY(grid,11,pos,v_taracaja); 
			  	 
			
			  	 
		   }
		   else{  //si no hay registro ponemos 0 en la tara
		      //colocamos tara xtara
			  aplicaValorXY(grid,10,pos,0);	
		   }
	  
		//}//fin if si unidad de venta del producto es en KG
}

/**************************************************************/
function AplicaImporte(grid,pos){
	
	alert(grid+"---"+pos);

    var f = document.forma_datos;
	
  //obtenemos la unidad de venta del producto seleccionado
  if(f.t.value=='YW5kZXJwX25vdGFzX3ZlbnRh'){//Pantalla Notas de Venta
	var getId_uv = celdaValorXY(grid,6,pos); //id_unidad de venta
  }	
  if(f.t.value == 'YW5kZXJwX2ZhY3R1cmFz'){//Pantalla Facturas
	var getId_uv = celdaValorXY(grid,27,pos);
  }	

     //validaciones
	if(getId_uv ==''){
	   alert("Seleccione un producto para realizar la operacion.");
	   valorCeldaXY(grid,7,pos,'-'); 
	   return false;
	}
	 //validacion INFORMATIVA  de precios de un producto que arrebasa el precio minimo por unidad de venta
	  var v_precio = celdaValorXY(grid,13,pos); //Precio
	  if (v_precio > 0){
		 var v_precio_minimo_uv = celdaValorXY(grid,14,pos); //Precio minimo por unidad de venta  
		 if(parseFloat(v_precio) < parseFloat(v_precio_minimo_uv)){
		    alert("El precio ingresado no está dentro de rango mínimo de venta establecida.");
		 }
	  }
	
	

	if(parseInt(getId_uv) == 1){ //id_unidad_venta KG
	   //Importe = Kilos Netos * Precio
	   if(f.t.value=='YW5kZXJwX25vdGFzX3ZlbnRh'){//Pantalla Notas de Venta
	    var kn = celdaValorXY(grid,11,pos);
		var precio = celdaValorXY(grid,13,pos);
	   }
	   if(f.t.value=='YW5kZXJwX2ZhY3R1cmFz'){//Pantalla Facturas
		  var kn = celdaValorXY(grid,11,pos);  //kilos Netos
		  var precio = celdaValorXY(grid,13,pos); //Precio
	   }
	   
	    if(kn == '') kn =0;
		if(precio == '') precio =0;
		var importe = (parseFloat(kn) * parseFloat(precio));
	}
	
	if(parseInt(getId_uv) == 2){ //id_unidad_venta PIEZAS
	  //Importe = Cantidad * Precio
	   if(f.t.value=='YW5kZXJwX25vdGFzX3ZlbnRh'){//Pantalla Notas de Venta
	     var cantidad = celdaValorXY(grid,8,pos);
		 var precio = celdaValorXY(grid,13,pos);
	   }	 
	   if(f.t.value=='YW5kZXJwX2ZhY3R1cmFz'){//Pantalla Facturas
	      var cantidad = celdaValorXY(grid,8,pos);
		  var precio = celdaValorXY(grid,13,pos);//precio
	   }		 
		  
		 if(cantidad =='') cantidad =0;  
		 if(precio =='') precio =0; 
		
		 var importe = (parseFloat(cantidad) * parseFloat(precio));
		 
	}
	
	
	 if(f.t.value=='YW5kZXJwX25vdGFzX3ZlbnRh'){//Pantalla Notas de Venta
        aplicaValorXY(grid,15,pos,importe);  //importe
     }
   	 if(f.t.value=='YW5kZXJwX2ZhY3R1cmFz'){//Pantalla Facturas
	    //alert(precio +'-'+importe);
	    aplicaValorXY(grid,19,pos,importe);  //importe
	 }
   
   
     
}
/***********************************/

function getPreciosProd(grid,pos){
/*Obtiene los precios del producto seleccionado para GRID Notas de Venta 
***/
	var f = document.forma_datos;
	   
	   	//obtiene datos del producto seleccionado en GRID
	   var id_prod = celdaValorXY(grid,2,pos);//id_producto
	   var id_prod_present = celdaValorXY(grid,5,pos); //presentacion 
	   var id_prod_tipo = celdaValorXY(grid,3,pos); //tipo producto 


		//obtiene los precios del producto para ese producto con la presentacion seleccionada
var aux = ajaxR("../ajax/getDatosProducto.php?accion=5&id_prod="+id_prod+"&id_prod_pres="+id_prod_present+"&id_prod_tipo="+id_prod_tipo);

     //alert(aux);

     var ax = aux.split("|");
	   if(ax[0] != 'exito'){
		alert(resp);
		return false;
	   }
	   
	   if(ax[0] == 'exito'){
	     var v_precios = ax[1].split("@@");
	    // alert(v_precios[0]); 
		 var v_precioxUV = v_precios[0];
		 var v_precioMinxUV = v_precios[1];
		 var v_precioPartidaxUV = v_precios[2];
		 var v_precioMinPartidaxUV = v_precios[3];
		 
		 if(ax[2] != '') var v_tara = parseFloat(ax[2]);

		    //colocamos tara xtara
			  valorCeldaXY(grid,10,pos,v_tara);	 
			  document.getElementById('detallenotasdeventa_10_'+pos).innerHTML =  v_tara;
	   //colocamos precio		  	 
		 valorCeldaXY(grid,16,pos,v_precioxUV);  //precio x UV
		 var AuxprecioUV = Mascara("$ ###,###.##",redondear(v_precioxUV,2));
		 document.getElementById('detallenotasdeventa_13_'+pos).innerHTML = AuxprecioUV;
 
		 valorCeldaXY(grid,14,pos,v_precioMinxUV);  //precio Minimo x UV  PRECIO DESEABLE
		 valorCeldaXY(grid,17,pos,v_precioPartidaxUV);  //precio Partida x UV
		 valorCeldaXY(grid,18,pos,v_precioMinPartidaxUV);  //precio Minimo Partida x UV
	  }
	  

  //colocar precio 	SUGERIDO EN CAMPO PRECIO DEL GRID NOTAS DE VENTA  
  var precioSugerido = celdaValorXY(grid,16,pos);

 //alert(precioSugerido);
   valorCeldaXY(grid,13,pos,precioSugerido);  //coloca en Precio el Precio Sugerido
}


/******************************/

/*Pantalla Facturas 
******/
function getPrecioDeseable(grid,pos){
 //alert("Entro a precion deseable");
    
	var f = document.forma_datos;
	   
	   	//obtiene datos del producto seleccionado en GRID
	if(f.t.value == 'YW5kZXJwX25vdGFzX3ZlbnRh'){//Pantalla Notas de Venra
	   var id_prod = celdaValorXY(grid,2,pos);//id_producto
	   var id_prod_present = celdaValorXY(grid,4,pos); //presentacion 
	   var id_prod_tipo = celdaValorXY(grid,3,pos); //tipo producto 
	}
	if(f.t.value == 'YW5kZXJwX2ZhY3R1cmFz'){ //Pantalla Facturas
	   var id_prod = celdaValorXY(grid,3,pos); //id_producto
	   var id_prod_present = celdaValorXY(grid,5,pos); //presentacion 
	   var id_prod_tipo = celdaValorXY(grid,4,pos); //tipo producto 
	}
	
	//alert(id_prod +'-'+ id_prod_present+'-'+id_prod_tipo);
	
	//obtiene el precio del producto para ese producto con la presentacion seleccionada
var aux = ajaxR("../ajax/getDatosProducto.php?accion=7&id_prod="+id_prod+"&id_prod_pres="+id_prod_present+"&id_prod_tipo="+id_prod_tipo);
     // alert(aux);
	  var ax = aux.split("|");
	  
	   if(ax[0] != 'exito'){
		alert(resp);
		return false;
	   }
	   
	   if(ax[0] == 'exito'){
	     var v_precio_deseable = ax[1];
	  }
	  //alert(v_precio_deseable);
	  
	if(f.t.value == 'YW5kZXJwX25vdGFzX3ZlbnRh'){//Pantalla Notas de Venra
        valorCeldaXY(grid,13,pos,v_precio_deseable);  //precio deseable
	}
	if(f.t.value == 'YW5kZXJwX2ZhY3R1cmFz'){ //Pantalla Facturas
         valorCeldaXY(grid,14,pos,v_precio_deseable);  //precio deseable
	}
	  
	  
	  
	  
	  
	

}
/***********************************/

/********************************/
function validaPorcentajeIVA()
{
	var objFuente=document.getElementById("id_fuente");
	if(!objFuente)
		return false;
	if(objFuente.value=="1")
	{
		alert("No se puede agregar un porcentaje de IVA a un documento de Intereses Wholesales");
		return false;
	}
	return true;
}

function vaciaIVA(grid, obj)
{
	if(obj.value!="1")
		return true;
	var numf=NumFilas(grid);
	var objH=document.getElementById("H_"+grid+"8")
	if(!objH)
		return false;
	var valida=(objH.valida)?objH.valida:objH.getAttribute("valida");
	objH.valida="";
	objH.setAttribute("valida","");
	for(var i=0;i<numf;i++)
	{
		aplicaValorXY(grid,8,i,'');
	}
	objH.valida=valida;
	objH.setAttribute("valida",valida);
	return true;
}
/*******************************/

/**Pantalla Cuentas Por Cobrar
************/
function actualizaTipoCobro(grid,pos){

  var id_tipo_cobro = celdaValorXY(grid,3,pos);  
  if(id_tipo_cobro>0){
     valorCeldaXY(grid,2,pos,id_tipo_cobro); //coloca id tipo de cobro 
  }
     
	   
}//fin function 

function validaCancelacionNV(grid,pos){
/*Valida si se puede cancelar la nota de venta y Coloca datos de quien cancelo
***/

//alert("Entro a Cancelar Cuenta x Cobrar");


}
/********************************/
//**************FUNCIONES ABC***********************
//carga datos especificos del gri de Pedidos
function cargarDatosProductosPedidos(grid,pos){
		idProducto=celdaValorXY(grid,3,pos);
		//alert(idProducto+" - "+celdaValorXY(grid,3,pos));
		if(idProducto.indexOf(":")!=-1)
	    {
		  idProd=idProducto.split(":");
		  producto=idProd[0];
		  id=idProd[1];
	      //alert("Producto="+producto+" - id="+id);
		  var DatosSource = ajaxR("../ajax/getDatosAjax.php?opc=1&id_prod="+id);
		  //alert(DatosSource);
		  datosJson = jQuery.parseJSON(DatosSource);
		  	  
		  if(datosJson.length>0){
			valorXY(grid,5,pos,datosJson[0].precio_unitario);//precio sugerido
	        valorXY(grid,8,pos,datosJson[0].porcentaje_iva);//iva sugerido
	        valorXY(grid,3,pos,producto);//nombre del producto
			valorXY(grid,2,pos,id);//id producto
		  }else{	
			alert("No se encontraron datos");
		  }
		}
	
	
}

function cargarDatosProductosCXP(grid,pos){
		idProducto=celdaValorXY(grid,3,pos);
		//alert(idProducto+" - "+celdaValorXY(grid,3,pos));
		if(idProducto.indexOf(":")!=-1)
	    {
		  idProd=idProducto.split(":");
		  producto=idProd[0];
		  id=idProd[1];
	      //alert("Producto="+producto+" - id="+id);
		  var DatosSource = ajaxR("../ajax/getDatosAjax.php?opc=1&id_prod="+id);
		  //alert("datos surse"+DatosSource);
		  datosJson = jQuery.parseJSON(DatosSource);
		  	  
		  if(datosJson.length>0){
			valorXY(grid,5,pos,datosJson[0].precio_unitario);//precio sugerido
	        valorXY(grid,3,pos,producto);//nombre del producto
			valorXY(grid,2,pos,id);//id producto
		  }else{	
			alert("No se encontraron datos");
		  }
		}
	
	
}


//esta funcion inserta el id de cualquier elemento en el campo oculto de id
//cuando se trata de un elemento buscador ("configuracion que depende de la bd")
//Para que funcione esta funcion el combo buscador deve retornar un valor en este 
//formato id:valor    ejemplo    1:mexico 
//Los parametros de la funcion son 
// grid = Nombre del grid
// posX = Posicion eje X-1 de la tabla
// posY = la cual te la retorna la misma configuracion poniendo '#' al momento de llamar la base
// pos_asigna_id = Posicion donde deceas que escriba el id (id_producto) es  igual a n-1 como posX 
function cargaIdPosicionBuscador(grid,posX,posY,pos_asigna_id){
		valorElemento=celdaValorXY(grid,posX,posY);
		//alert(grid+" - "+posX+" - "+posY+" - "+pos_asigna_id);
		//alert("id_elemnto = "+idElemento+" - "+celdaValorXY(grid,posX,posY));
		if(valorElemento.indexOf(":")!=-1)
	    {
		  //obtenemos datos de la busqueda en el "combo buscador"	
		  ArrayElemento=valorElemento.split(":");
		  id_elemento=ArrayElemento[0];
		  elemento=ArrayElemento[1];
	      //asignamos el valor id elemto al elemnto requerido
		  valorXY(grid,pos_asigna_id,posY,id_elemento);
		  //asignamos el valor texto al elemto del cual obtuvimos la cadena inicial
		  valorXY(grid,posX,posY,elemento);
	      
		
		}
	
	
}
//Funcion para obtener un valor o id de un elemnto del encabezado y pasarlo
//a un elemnto del grid
function cargaValorEncabezadoEnGrid(grid,posX,posY,id_elemento_cabecera){
	
	//alert(grid+" - "+posX+" - "+posY+" - "+id_elemento_cabecera);
	obj_encabezado=document.getElementById(id_elemento_cabecera);
	valor_return=obj_encabezado.value;
	valorXY(grid,posX,posY,valor_return);
	//alert(" valor del objeto = "+obj_encabezado.value);
	
	
}
// funcion que calcula el operaciones de encabezados

function calculaTotalEncabezado(id_subtotal,id_iva,id_total){
	objSubtotal=document.getElementById(id_subtotal);
	objIva=document.getElementById(id_iva);
	objTotal=document.getElementById(id_total);
	//asignamos valores a los campos
	valSub=parseFloat((objSubtotal.value=="")?0:objSubtotal.value.replace(/[,]/g, ''));
	valIva=parseFloat((objIva.value=="")?0:objIva.value.replace(/[,]/g, ''));
	//ponemos las mascaras a los campos
	objSubtotal.value=Mascara("###,###.##",redondear(valSub,2));
	objIva.value=Mascara("###,###.##",redondear(valIva,2));
	objTotal.value=Mascara("###,###.##",redondear(valSub+valIva,2));
	
	
}
function abrirThickBox(id){
	//alert(id);
	//ruta del archivo que se mostrara en el thick box
    complemento_ruta="&fila="+id;
	ruta="../../templates/popups/thickbox.php?height=180&width=200&modal=true"+complemento_ruta;
	//accedemos al objeto que dispara el thick box
	var obj=document.getElementById('thickbox_href');
	//asignamos la propiedad href al objeto
	//alert("Atributo = "+obj.getAttribute("href"));
	obj.setAttribute('href',ruta);
	//alert("Atributo nuevo = "+obj.getAttribute('href'));
	obj.click();
	
    
	
}
function agregarError(id){
	//alert("prueba id = "+id);
	gridFuente="";
	gridDestino="";
	//falta general la recoleccion e insercion de datos en los 2 grids
	tb_remove();
	
}

//**************************************************


</script>
{/literal}