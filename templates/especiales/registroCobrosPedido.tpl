{literal}
<style>
table{ font-size:12px; font-family:Arial, Helvetica, sans-serif}
/*
	form[name="formax"] {padding: 10px; }
	select.articulos	{width	: 200px;}
	select.movimientos	{width 	: 250px;}
	.fechas				{width	: 249px;}
	input[type="radio"] {
			outline: none;
			width: 40px;
			display: inline-block;
			margin: 3px;
			background-color: #000;
			border: 0;
			border-bottom: 1px solid rgba(255,255,255,0.1);
		}
*/
		
		#pagos_registrados hr{
				border: 0; 
				border-bottom: 1px dashed #ccc; 
				background: #999;
			}
		.busca-pagos th{
				padding : 5px;
				font-weight : bold;
				font-size : 10px;
				text-align : center;
				background-color: #FAFBFF;
				background-image: -o-linear-gradient(bottom, #FAFBFF 0%, #B3B4B5 100%);
				background-image: -moz-linear-gradient(bottom, #FAFBFF 0%, #B3B4B5 100%);
				background-image: -webkit-linear-gradient(bottom, #FAFBFF 0%, #B3B4B5 100%);
				background-image: -ms-linear-gradient(bottom, #FAFBFF 0%, #B3B4B5 100%);
				background-image: linear-gradient(to bottom, #FAFBFF 0%, #B3B4B5 100%);
				}
		.busca-pagos caption div{
				font-size : 13px;
				font-weight : bold;
				text-align : left;
				padding : 10px 0;
				color : #404651;
				}
		.cuerpo-pagos td:first-child{
				border-left : 1px #C4C5C7 solid;
				}
		.cuerpo-pagos td{
				padding : 5px;
				font-size : 10px;
				border : 1px #C4C5C7 solid;
				border-top : none;
				border-left : none;
				}
		#scroll-tabla{
				height : 250px;
				overflow-y : auto;
				}
</style>
{/literal}
<link href="../../css/estilos.css" rel="stylesheet" type="text/css" />
<link href="../../css/gridSW.css" rel="stylesheet" type="text/css" />
<!-- Aquí va el tipo de documento -->
{include file="_header.tpl" pagetitle="$contentheader"}
<!--
<div style="z-index:5000; display:none; position:absolute; left:0; top:0;" id="waitingplease">
	<img src="../../imagenes/general/wait.gif" border="0" style="z-index:2000; position:absolute" id="imgW1"/>
	<img src="../../imagenes/general/back_wait.gif" border="0" style="z-index:1000; position:absolute" id="imgW2"/>
</div>
-->
<br/>
  <h1>{$titulo}</h1>
	<br/>	

	<table border="0" cellspacing="0"  >
    	<tr>
        	<td>Sucursal</td>
            <td>
            	<select name="slct_sucursal" class="campos_req movimientos" id="slct_sucursal" onChange="resetFormaCobro();" >
					{html_options values=$sucursal_id output=$sucursal_nombre}
				</select>
            </td> 
        </tr>
      <tr>
        <td height="30">
        	No. Pedido
        </td>
        <td>
        	<input type="text" name="txt_no_pedido" id="txt_no_pedido"  style="width:130px; margin-right:126px" />            
        	<input type="button"  class="boton" name="buscar_pedido" id="buscar_pedido" value="Buscar" onClick="obtenClienteNoPedido();">
        </td>
      </tr>
     
      <tr>
        <td>
        	Cliente
        </td>
        <td>
        	<input type="text" name="txt_cliente" id="txt_cliente" style="width:230px; margin-right:22px" />&nbsp;
        	<input type="button" class="boton" onClick="obtenClienteClic();" name="buscar_cliente" id="buscar_cliente" value="Buscar">
        </td>
      </tr>
      <tr>
        <td colspan="2">        	
       		<select id="slct_cliente_ajax" class="campos_req" name="slct_cliente_ajax" onChange='actualizaGridCliente();' style="width:410px;" size="4">
            	<option value="0" >Favor de capturar un cliente...</option>
			</select>
        </td>
      </tr>
    </table>

<div style="overflow-x:auto; width:1000px; padding:0; height:140px; ">
	<!-- ALto y ancho Tabla -->
    <div style="height:130px; width:980px; border:0; padding-top:5px; padding-left:3px; padding-bottom:5px; ">   
		<table 
        id="registroCobrosPedido"       
        cellpadding="0" 
        cellspacing="0" 
        border="0" 
        Alto="120" 
        width="960"  
        conScroll="S" 
        validaNuevo="false"
        despuesInsertar="" 
        AltoCelda="25" 
        auxiliar="0" 
        ruta="../../../imagenes/general/"
        validaElimina="false"  
        verFooter="N"  
        listado="N" 
        class="tabla_Grid_RC" 
        estilos_header="buttonHeader_1|buttonHeader_1|buttonHeader_1|buttonHeader_1|buttonHeader_1|
        				buttonHeader_1|buttonHeader_1|buttonHeader_1|buttonHeader_1|buttonHeader_1"
        scrollH="N" 
        Datos="">  
			<tr class="HeaderCell">                    
            
            	<td tipo="oculto" modificable="N" align="center" width="0" offsetwidth="0" >id_control_pedido</td>
                <td tipo="oculto" modificable="N" align="center" width="0" offsetwidth="0" >id_sucursal_alta</td>
                <td tipo="texto"  modificable="N" align="center" width="100" offsetwidth="100">Sucursal</td>                
                <td tipo="texto"  modificable="N" align="center" width="150" offsetwidth="200">Cliente</td>
                <td tipo="texto"  modificable="N" align="center" width="100" offsetwidth="90">Pedido</td>                
                <td tipo="oculto" modificable="N" align="center" width="0" offsetwidth="0" >id_estatus_pedido</td>
                <td tipo="oculto" modificable="N" align="center" width="0" offsetwidth="0" >id_estatus_pago_pedido</td>                
                <td tipo="texto"  modificable="N" align="center" width="100" offsetwidth="100" mascara="$###,###.##">Total</td>
                <td tipo="texto"  modificable="N" align="center" width="100" offsetwidth="100" mascara="$###,###.##">$ Pagos</td>
                <td tipo="texto"  modificable="N" align="center" width="105" offsetwidth="105" mascara="$###,###.##">$ Confirmados</td>
                <td tipo="texto"  modificable="N" align="center" width="105" offsetwidth="105" mascara="$###,###.##">$ No Confirmados</td>
				<td tipo="texto"  modificable="N" align="center" width="100" offsetwidth="100" mascara="$###,###.##">Saldo</td>
                <td tipo="libre"  valor='Registrar Pago' align="center" width="100"  offsetWidth="25">
                 	<img src="{$rooturl}imagenes/general/pagos.png" border="0" onclick="abreMod(0,'#')" alt="Registrar Pago" title="Registrar Pago" style="cursor:pointer; width:18px; height:18px"/>
				</td>
                
			</tr>       
		</table>
	</div>
</div>
<hr />
<div id="pagos" style="display:none">
<form name="frmPagos" id="frmPagos" action="#" method="post"  >
    <table cellpadding="0" cellspacing="0" border="0" >
      <tr>
        <td height="30">
        	Realizar pago a cliente:&nbsp;&nbsp;
        </td>
        <td height="30">
        	<P id="cliente_nombre"  style="width:230px"></P></td>
        <td height="30">
        	Forma de Cobro:
        </td>
        <td height="30">
            <select name="slct_forma_pago" onchange="consultaValidaciones(this.value)" class="campos_req" id="slct_forma_pago" style="width:230px" >
                <option value="0">Seleccione una opcion...</option>      
                {html_options values=$forma_pago_id output=$forma_pago_nombre}		
            </select>
        </td>
      </tr>
      <tr>
        <td height="30">Pedido:</td>
        <td height="30"><p id="txt_pedido" style="width:230px"></p></td>
        <td height="30">Terminal Bancaria</td>
        <td height="30">
            <select name="slct_terminal_bancaria" onchange="" class="campos_req" id="slct_terminal_bancaria" style="width:230px" disabled>
                <option value="0">Seleccione una opcion...</option>      
                {html_options values=$terminal_bancaria_id output=$terminal_bancaria_nombre }		
            </select>
        </td>
      </tr>
      <tr>
        <td height="30">Fecha</td>
        <td height="30">
            <p style="width:230px">{$fecha}
                <input type="hidden" value="{$fecha}" name="txt_fecha" id="txt_fecha"  />
            </p>
        </td>
        <td height="30">Numero de Documento&nbsp;&nbsp;</td>
        <td height="30">
        	<input type="text" name="txt_numero_documento" id="txt_numero_documento" class="campos_req" style="width:230px" disabled/>
        </td>
      </tr>
	  <tr>
			<td height="30">&nbsp;</td>
			<td height="30">&nbsp;</td>
			<td height="30">Banco</td>
			<td height="30">
            <select name="slct_banco" onchange="" class="campos_req" id="slct_banco" style="width:230px" disabled>
                <option value="0">Seleccione una opcion...</option>      
                {html_options values=$banco_id output=$banco_nombre }		
            </select>
        </td>
      <tr>
      <tr>
        <td height="30">&nbsp;</td>
        <td height="30">
        	<input type="hidden" name="txt_control_pedido" id="txt_control_pedido"  />
        	
        	<input type="hidden" name="txt_usuario_id" id="txt_usuario_id" value="{$usuario_id}"  /><input type="hidden" name="txt_autorizacion_credito_cobranza" id="txt_autorizacion_credito_cobranza" value=""  /></td>
        <td height="30">Monto</td>
        <td height="30">
        	<input type="text" name="txt_monto" id="txt_monto" class="campos_req" style="width:230px"/>
        </td>
      </tr>
	  
        <td height="30">
        	<input type="button" onclick="registraPago();" class="boton" value="Aplicar Pago"  />
        </td>
        <td height="30">&nbsp;</td>
        <td height="30">&nbsp;</td>
        <td height="30">&nbsp;</td>
      </tr>
    </table>
</form>
</div>
<br><div id="pagos_registrados" style="display:none">
		<hr>
		<div id="scroll-horizontal" style="overflow-x:auto; width:1000px; padding:0; height:250px; ">
				<table class="busca-pagos" style="width:1800px;">
				<caption><div style="width:20%; float:left">Pagos Registrados</div><div style="width:80%; float:right"><input type="button" onclick="generaRecibo();" class="boton" value="Generar Recibo" /></div></caption>
						<thead>
								<tr>
										<th style="width:20px;">No</th>
										<th style="width:18px;">&nbsp;</th>
										<th style="width:50px;">ID Recibo</th>
										<th style="width:50px;">Fecha</th>
										<th style="width:150px;">Forma de Pago</th>
										<th style="width:150px;">Sucursal de Pago</th>
										<th style="width:150px;">Terminal Bancaria</th>
										<th style="width:100px;">No. Documento</th>
										<th style="width:100px;">No. de Aprobaci&oacute;n</th>
										<th style="width:100px;">Monto</th>
										<th style="width:80px;">Confirmado</th>
										<th style="width:200px;">Observaciones</th>
										<th style="width:50px;">Cancelar</th>
										<th style="width:50px;">Imprimir</th>
								</tr>
						</thead>
				</table>
				<div id="scroll-tabla" style="width:1800px;">
						<table border="0" class="cuerpo-pagos" style="width:1800px;"> 
								<tbody>
								</tbody>
						</table>
				</div>
		</div>
</div>
<br />
<script>	  	
	CargaGrid('registroCobrosPedido');
</script>         	
{literal}
<script language="javascript">
/*
 * 1.- Llena select_cliente_ajax
 * 2.- Borra el contenido del grid
 * 3.- Esconde el formulario de pago
 */
function obtenClienteClic()
{	
	var selectHijo = "slct_cliente_ajax";
	var opcion = $("#txt_cliente").val();
//	alert(opcion);
	var urlAjax = "llenaDato.php";
	var envio_datos = 'id=' + opcion+'&dato=clientes_pedido';  
	ajaxCombos(urlAjax, envio_datos, selectHijo);	
	// Refresca grid "borra datos del grid"
	RecargaGrid('registroCobrosPedido', "../ajax/especiales/registroCobrosPedido/registroCobrosPedido.php?cliente=");
	$("#pagos_registrados").hide();
	// oculta formulario de pagos
	$("#pagos").slideUp('slow');
}
/*
 * 1.- Llena select_cliente_ajax con el cliente especifico de ese pedido, 
 * 2.- Selecciona el option (Cliente)
 * 3.- Almacena el ID del cliente
 * 4.- Envia el id al grid y lo refresca
 * 5.- Esconde el formulario de pagos
 */
function obtenClienteNoPedido()
{	
	var id_cliente=0;
	var hijo = "slct_cliente_ajax";
	var opcion = $("#txt_no_pedido").val();
//	alert(opcion);
	var url = "llenaDato.php";
	var datos = 'id=' + opcion+'&dato=no_pedido';  
	
	$.ajax({
			async:false, // solo para este caso
			type: "POST",
			dataType: "html",
			contentType: "application/x-www-form-urlencoded",
			data: datos,
			url:'../ajax/' + url,
			success: function(data) 
					{
						$("#" + hijo + " option").remove();
						$("#" + hijo).append(data);
					}
	});
	
	
	id_cliente=$("#slct_cliente_ajax").find("option:selected").val();
	// Refresca grid 
	RecargaGrid('registroCobrosPedido', 
				"../ajax/especiales/registroCobrosPedido/registroCobrosPedido.php?cliente="
				+ id_cliente + "&no_pedido=" + opcion);
	// oculta formulario de pagos
	$("#pagos_registrados").hide();
	$("#pagos").slideUp('slow');
//	alert(id_cliente);
}
/*
 * 1.- Realiza una consulta a la tabla na_formas_pago devuelve el resultado con formato JSON
 * 2.- Habilita o deshabilita select (Terminal Bancaria) e input text (Numero de documento)
 * 3.- Asigna el valor a txt_autorizacion_credito_cobranza (0 y 1)
 */
function consultaValidaciones(valor)
{
//	alert(valor);	
	$.getJSON('../ajax/especiales/registroCobrosPedido/validacionFormaCobro.php?id='+valor, {format: "json"}, function(data) {
		var valResp="";
	//        alert(data['id_forma_pago']+'\n'+
	//			  data['nombre']+'\n'+
	//			  data['autorizacion_credito_cobranza']+'\n'+
	//			  data['requiere_registro_terminal']+'\n'+
	//			  data['requiere_numero_documento']);
	//        $("#txt_no_pedido").val('titulo:'+data['nombre']);
			
		// habilita-desabilita Select (Terminal_bancaria)
				
		if (data['requiere_registro_terminal']==1)
		{	
			$("#slct_terminal_bancaria option").remove();
//			 $('#slct_terminal_bancaria').append('<option value="example' + 1 + '" selected="selected" Opcion #' + 1 + '</option>');
			// PARAMETROS
			var sucursal_id = $("#slct_sucursal").find("option:selected").val();
			$params = {'id':sucursal_id, 'dato': 'terminalPorSucursal'};			 

			// CONSULTA AJAXS	
			$.ajax({		  				
			  url : '../ajax/llenaDato.php' ,		  
			  type: 'POST',
			  data : $params
				}).done( function( data )
						 {
//							 alert(data);
							$("#slct_terminal_bancaria").append(data);
	//						console.log(data);
					 });	
		
		
//			$("#slct_terminal_bancaria option[value='0']").attr("selected",true).text('Seleccione una opcion...');
			$("#slct_terminal_bancaria").prop('disabled', false);
		}
		else
		{
			$("#slct_terminal_bancaria option[value='0']").attr("selected",true).text('No Aplica');
			$("#slct_terminal_bancaria").prop('disabled', true);
		}
		
		// habilita-deshabilita input  (numero de documento)
		
		if (data['requiere_numero_documento']==1)
		{	
			$("#txt_numero_documento").val('');
			$("#txt_numero_documento").prop('disabled', false);
		}
		else
		{
			$("#txt_numero_documento").val('No Aplica');
			$("#txt_numero_documento").prop('disabled', true);
		}	

			//Validacion para ver si se requiere banco
			
		if (data['requiere_banco']==1){
				$("#slct_banco option[value='0']").attr("selected",true).text('Seleccione una opcion...');	
				$("#slct_banco").prop('disabled', false);
				}
		else{
				$("#slct_banco option[value='0']").attr("selected",true).text('No Aplica');
				$("#slct_banco").prop('disabled', true);
				}
		$("#txt_autorizacion_credito_cobranza").val(data['autorizacion_credito_cobranza']);			
		
	});	
}

function formatear_pesos(num){
     num = num.toString().replace(/\$|\,/g,'');
     if (isNaN(num))
		  num = '0';
		  var signo = (num == (num = Math.abs(num)));
		  num = Math.floor(num * 100 + 0.50000000001);
		  centavos = num % 100;
		  num = Math.floor(num / 100).toString();

      if (centavos < 10)
           centavos = '0' + centavos;
      for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++)
           num = num.substring(0, num.length - (4 * i + 3)) + ',' + num.substring(num.length - (4 * i + 3));
      return (((signo) ? '' : '-') + num + '.' + centavos);
}
/*
 * 1.- Actualiza el grid "registroCobrosPedido" con el id del cliente
 * 2.- Esconde el formulario de pagos
 */
function actualizaGridCliente()
{
	var valor = $("#slct_cliente_ajax").val();	
	RecargaGrid('registroCobrosPedido', "../ajax/especiales/registroCobrosPedido/registroCobrosPedido.php?cliente="+valor);
	$("#pagos").slideUp('slow');			
}
/*
 * 1.- Almacena el ID del pedido y el ID control pedido
 * 2.- Muestra el formulario de pagos
 * 3.- Llena cliente_nombre, txt_pedido y txt_control_pedido
 *     del formulario de pagos
 */
function abreMod(val, campo){
		var id=celdaValorXY('registroCobrosPedido',0,campo);		
		var pedido=celdaValorXY('registroCobrosPedido',4,campo);
		$("#pagos").slideDown('slow');
		valor= $("#slct_cliente_ajax option:selected").text();
		$("#cliente_nombre").text(valor);
		$("#txt_pedido").text(pedido);
		$("#txt_control_pedido").val(id);
		$("#pagos_registrados").show();
		$(".cuerpo-pagos tbody").remove();
		
		var urlAjax = "llenaTablaPagos.php"; 
		var envio_datos = 'id=' + id; 
		var result = ajaxN(urlAjax, envio_datos);
		
		$(".cuerpo-pagos").append(result);
		}	

function cargaWait()
{
	var w=screen.width;
	var h=screen.height;
	var obj=document.getElementById('waitingplease');
	var im1=document.getElementById('imgW1');
	var im2=document.getElementById('imgW2');
	im2.width=screen.width;
	im2.height=screen.height;
	im1.style.left=(w-128)/2;
	im1.style.top=(h-128)/2-(h/8);
	obj.style.display='';	
}
/*
 * 1.- Realiza validaciones de llenado
 * 	   1.1.- Si las validaciones fueron satisfactorias envia un confirm
 *	   1.1.1 .- Si el confirm es verdadero almacena las variables,
 *				ejecuta ajaxs de insercion y pinta alert con la respuesta
 */
function registraPago()
{
	// Validaciones 
	var form =document.forms.frmPagos;	
								 	    
	var id_sucursal = $("#slct_sucursal").val();	
	if (id_sucursal==0)
	{
		alert ("Debe seleccionar una sucursal diferente");
		$("#slct_sucursal" ).focus();

		return false;
	}
	if (form.slct_forma_pago.value==0)
	{
		alert ("Favor de seleccionar una forma de cobro");
		form.slct_forma_pago.focus();		
		return false;
	}
	
	if (!document.getElementById("slct_terminal_bancaria").disabled)
	{
		// Si no esta deshabilitado
		if (form.slct_terminal_bancaria.value==0)
		{
			alert ("Favor de seleccionar la Terminal Bancaria");
			form.slct_terminal_bancaria.focus();		
			return false;
		}
		
	}
	if (!document.getElementById("slct_banco").disabled)
	{
		// Si no esta deshabilitado
		if (form.slct_banco.value==0)
		{
			alert ("Favor de seleccionar un banco");
			form.slct_banco.focus();		
			return false;
		}
		
	}
	if (!document.getElementById("txt_numero_documento").disabled)
	{
		// Si no esta deshabilitado
		if (form.txt_numero_documento.value=="")
		{
			alert ("Favor de capturar un numero de documento");
			form.txt_numero_documento.focus();		
			return false;
		}
		
	}
	
	if (form.txt_monto.value=="")
	{
		alert ("Favor de capturar el monto de pago");
		form.txt_monto.focus();		
		return false;
	}
	
	var monto	= $("#txt_monto").val();
	var pedido = $("#txt_pedido").text();
	
	// confirm
	if(confirm("\u00BFDesea registrar el pago por el monto de $" + formatear_pesos(monto) + " dentro del pedido " + pedido + "?")) 
	{
		// ALMACENAMOS LOS PARAMETROS EN VARIABLES
//		var id_sucursal = $("#slct_sucursal").val();
		var id_usuario= $("#txt_usuario_id").val();
		var autorizacion_credito_cobranza = $("#txt_autorizacion_credito_cobranza").val();
		var id_control_pedido = $("#txt_control_pedido").val();
		var fecha = $("#txt_fecha").val();
		var id_forma_pago = $("#slct_forma_pago").val();
		var id_terminal_bancaria = $("#slct_terminal_bancaria").val();
		var numero_documento = $("#txt_numero_documento").val();
		var banco = $("#slct_banco").find("option:selected").val();
//		var monto = $("#txt_monto").val();

		// PARAMETROS
		$params = {'id_usuario': id_usuario,
				   'id_sucursal': id_sucursal,
				   'autorizacion_credito_cobranza':autorizacion_credito_cobranza,
				   'id_control_pedido':id_control_pedido,
				   'fecha':fecha,
				   'id_forma_pago':id_forma_pago,
				   'id_terminal_bancaria':id_terminal_bancaria,
				   'numero_documento':numero_documento,
				   'monto' : monto,
					'banco' : banco				   
				   };			 
		// CONSULTA AJAXS

		$.ajax({		  				
		  url : '../ajax/especiales/registroCobrosPedido/insertCobrosPedido.php' ,		  
		  type: 'POST',
		  data : $params
			}).done( function( data )
					 {
						 var datos = JSON.parse(data);
						 alert( datos.mensaje );
						// imprimeReciboCobro(datos.pedido, datos.pago);
						 datosGrid=$("#registroCobrosPedido").attr("Datos");
						 RecargaGrid('registroCobrosPedido', datosGrid);
						// $("#pagos").slideUp('slow');
						 document.forms.frmPagos.reset();
						 $(".cuerpo-pagos tbody").remove();
		
						var urlAjax = "llenaTablaPagos.php"; 
						var envio_datos = 'id=' + datos.pedido; 
						var result = ajaxN(urlAjax, envio_datos);
						
						$(".cuerpo-pagos").append(result);

				 });		
		return false;
	}   		
}
function resetFormaCobro()
{ 
/*
// 	reset Select	
//	$('#slct_forma_pago').prop('selectedIndex',0);
	$("#slct_forma_pago option[value='0']").attr("selected",true)
	$("#slct_terminal_bancaria option[value='0']").attr("selected",true).text('Seleccione una opcion...');
	$("#slct_terminal_bancaria").prop('disabled', true);
	$("#txt_numero_documento").val(' ');
	$("#txt_numero_documento").prop('disabled', true);*/

// Resetea el formulario completo	
	document.forms.frmPagos.reset();

}

function generaRecibo(recibo = 100000000){
		var valorRecibo = new Array();
		
		$('table.cuerpo-pagos input[name="idPagosCheck[]"]:checked').each(function(index) {
				valorRecibo.push($(this).val());
				});
		var pedido = 0;
		var confirmado = 0;
		var contadorP = 0;		
		var valConf = 0;		
		$('table.cuerpo-pagos tr').each(function(index) {
						contadorP = parseInt(index) + 1;
						pedido = $("#pedidoTabla" + contadorP).val();
						confirmado = $("#confTabla" + contadorP).val();
						});
				
		if(valorRecibo.length == 0 && recibo == 100000000){
				alert("Selecciona los pagos que deseas incluir en el recibo");
				}
		else if(valConf > 0){
				alert("No puedes imprimir pagos no confirmados");
				}
		else{
				
				window.open('../../code/pdf/imprimeReciboPagos.php?pagos=' + valorRecibo + "&recibo=" + recibo + "&pedido=" + pedido, "Recibos de Pago", "width=1000, height=1000");
				
				$(".cuerpo-pagos tbody").remove();
				var urlAjax = "llenaTablaPagos.php"; 
				var envio_datos = 'id=' + pedido; 
				var result = ajaxN(urlAjax, envio_datos);
						
				$(".cuerpo-pagos").append(result);
				}
		}
		
function cancelaPago(pago){
		
		var confirma=confirm("Esta seguro de cancelar este pago?");
		if(confirma == true){
				var urlAjax = "cancelaPagos.php"; 
				var envio_datos = 'id=' + pago; 
				var result = ajaxN(urlAjax, envio_datos);
				var datos = result.split("|");
				
				if(datos[0] == 0){
						alert(datos[1]);
						return false;
						}
				else{
						alert(datos[1]);
						
						$(".cuerpo-pagos tbody").remove();
						var urlAjax = "llenaTablaPagos.php"; 
						var envio_datos = 'id=' + datos[0]; 
						var result = ajaxN(urlAjax, envio_datos);
						$(".cuerpo-pagos").append(result);
						}
				}
				
		}


</script>
{/literal}
{include file="_footer.tpl"}