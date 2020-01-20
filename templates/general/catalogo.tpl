{if $hf neq 10}
		{include file="_header.tpl" pagetitle="$contentheader"}
{else}    
	<html >
<title>:</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    
    <link href="{$rooturl}css/estilos.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{$rooturl}css/topmenu_style.css" type="text/css" media="screen" />
    <link rel="stylesheet" type="text/css" href="{$rooturl}css/gridSW.css"/>
    
    <div class="tabla" id="tabla">
    
    <!-- Librerias para el grid-->
    <script language="javascript" src="{$rooturl}js/grid/RedCatGrid.js"></script>
    <script language="javascript" src="{$rooturl}js/jquery/jquery.js"></script>
    <script language="JavaScript" type="text/javascript" src="{$rooturl}js/grid/yahoo.js"></script>
    <script language="JavaScript" type="text/javascript" src="{$rooturl}js/grid/event.js"></script>
    <script language="JavaScript" type="text/javascript" src="{$rooturl}js/grid/dom.js"></script>
    <script language="JavaScript" type="text/javascript" src="{$rooturl}js/grid/fix.js"></script>
	<script language="javascript" src="{$rooturl}js/datepicker/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="{$rooturl}js/calendar.js"></script>
    <script type="text/javascript" src="{$rooturl}js/calendar-es.js"></script>
    <script type="text/javascript" src="{$rooturl}js/calendar-setup.js"></script>
    <script language="javascript" src="{$rooturl}js/funciones.js"></script>
    <script language="javascript" src="{$rooturl}js/funcionesGenerales.js"></script>
    <script language="javascript" src="{$rooturl}js/funcionesProyecto.js"></script>
	<script language="javascript" src="{$rooturl}/js/agregarDirecciones.js"></script>
	
    
    {literal}
	
	<style>
		body{background-color:#FFF; background-image:none}
	</style>
    {/literal}
{/if}
{include file="general/funciones_catalogos_cabecera.tpl"}

{* crea la validacion del grid*}

<script>
{$funcNew}
{$funcFin}
{literal}
		function verContrasena(obj){
			if($("#pass").prop('type') == 'text'){
				$("#pass").prop('type','password');
				$("#pass").attr('style','height:25px;');
			}
			else{
				$("#pass").removeAttr('style');
				$("#pass").prop('type','text');
			}
		}
		/***********Funcion para cargar y vaciar datos en cuentas por pagar desde un XML*****************/
		function agregaXML(archivo){
				//Validamos que sea extension XML
				var patern = /.xml/i;
				if(patern.test($(archivo).val()) === false){
						alert("Solo se admiten archivos con formato XML");
						}
				else{
						//Variable archivo contiene el nombre del archivo seleccionado
						var files = $(archivo).get(0).files[0]; //Obtenecion del valor file con jquery
						var data = new FormData();  //Se crea form para procesar el archivo
						data.append("file_xml", files); //Agregamos el valor del archivo al form para enviarlo por ajax
						$.ajax({
							type: "POST",
							url:'../ajax/xmlTemp.php',
							contentType: false,
							processData: false,
							data: data,
							//Si se procesan correctamente los datos
							success: function(resultado) {
								var datos = JSON.parse(resultado); //Recibimo la cadena en formato JSON								
								//Validamos que no se repita el UUID
								if(datos.verifica_uuid == 1){
										alert("Este XML ya esta asociado a una cuenta por pagar");
										}
								else{
										//Formateamos a fecha de registro la fecha que viene del XML
										var fecha_xml = datos.fecha[0].split("T");
										fecha_sub = fecha_xml[0].split("-");
										fecha_real = fecha_sub[2] + "/" + fecha_sub[1] + "/" + fecha_sub[0];
										
										/****Procedemos a vaciar la informacion en el encabezado****/
										
										$('#id_tipo_cuenta_por_pagar > option[value="2"]').attr('selected', 'selected');
										ocultaGridCXP();
										
										$('#id_tipo_proveedor option').remove();
										$('#id_tipo_proveedor').append("<option value='0' >Todos</option>");
										$('#id_tipo_proveedor').append("<option value='4' >ACREEDORES DIVERSOS</option>");
										$('#id_tipo_proveedor').append("<option value='5' >ACREEDORES DE SERVICIOS</option>");
										
										//Si se inserto un nuevo proveedor lo agregamos al select y lo ponemos por default seleccionado
										if(datos.genera_proveedor == 1)
												$('#id_proveedor').append("<option value='" + datos.proveedor + "' selected='selected'>" + datos.proveedorNombre + "</option>");
												$('#id_proveedor > option[value="' + datos.proveedor +  '"]').attr('selected', true);
												$('#id_tipo_documento_recibido > option[value="' + datos.tipo_documento + '"]').attr('selected', 'selected');
										
										
										
										$("#fecha_documento").val(fecha_real);
										$("#fecha_vencimiento").val(fecha_real);
										$("#numero_documento").val(datos.folio[0]);
										$("#numero_documento_2").val(datos.factura_sap[0]);
										$("#numero_documento_3").val(datos.documento_sap[0]);
										$("#subtotal_2").val(datos.subtotal[0]);
										$("#iva").val(datos.iva[0]);
										$("#total").val(datos.total[0]);
										$("#folio_fiscal").val(datos.uuid[0]);
										
										ocultaCamposCXP(); 
										
										/************Procedemos a llenar el grid de gastos con los conceptos del XML****************/
										var jsonConceptos = datos.conceptos; //Obtenemos el array
										var contadorConceptos = jsonConceptos.length;
										$('table#Body_detalleConceptosGastosCuentasPorPagar tr').remove(); //Limpiamos el grid cada vez que se seleccione un nuevo archivo XML
										for(var i=0; i<contadorConceptos; i++){
												var gastos = datos.conceptos[i].split("|");
												var importe = gastos[2];
												importe = parseFloat(importe).toFixed(2)
												var costo = gastos[2] / gastos[1];
												costo = parseFloat(costo).toFixed(2);
												var cantidad = parseInt(gastos[1]);
												var unitario_s = gastos[3];
												unitario = parseFloat(unitario_s).toFixed(2)
												
												
												nuevoGridFila('detalleConceptosGastosCuentasPorPagar'); //Cargamos una nueva fila
												
												$("#detalleConceptosGastosCuentasPorPagar_2_" + i).attr("valor", 17); //GASTO
												$("#detalleConceptosGastosCuentasPorPagar_3_" + i).attr("valor", 17); //GASTO
												$("#detalleConceptosGastosCuentasPorPagar_3_" + i).html("GASTOS DE OPERACION");
												
												$("#detalleConceptosGastosCuentasPorPagar_4_" + i).attr("valor", 18); //SUBGASTO
												$("#detalleConceptosGastosCuentasPorPagar_5_" + i).attr("valor", 18); //SUBGASTO
												$("#detalleConceptosGastosCuentasPorPagar_5_" + i).html("OTROS");
												
												$("#detalleConceptosGastosCuentasPorPagar_6_" + i).attr("valor", gastos[0]); //Descripcion
												$("#detalleConceptosGastosCuentasPorPagar_6_" + i).html(gastos[0]);
												
												$("#detalleConceptosGastosCuentasPorPagar_8_" + i).attr("valor", unitario_s); //Costo
												$("#detalleConceptosGastosCuentasPorPagar_8_" + i).html("$" + formatear_pesos(unitario));
												
												$("#detalleConceptosGastosCuentasPorPagar_7_" + i).attr("valor", cantidad); //Cantidad
												$("#detalleConceptosGastosCuentasPorPagar_7_" + i).html(cantidad);
												
												$("#detalleConceptosGastosCuentasPorPagar_9_" + i).attr("valor", gastos[2]); //Importe
												$("#detalleConceptosGastosCuentasPorPagar_9_" + i).html("$" + formatear_pesos(gastos[2]));
												
												$("#detalleConceptosGastosCuentasPorPagar_10_" + i).attr("valor", 0); //IVA
												}
										
												
												
										
										
										calculaTotalesN('detalleConceptosGastosCuentasPorPagar', 9, 'subtotal_gastos');
										//calculaSubtotalesN();
										
										if(datos.tipo_documento == 5){  //Recibos de honorarios
												var retenciones = datos.retenciones[0].split("|");
												$("#retencion_iva_documentos").val(formatear_pesos(retenciones[0]));
												$("#retencion_isr_documentos").val(formatear_pesos(retenciones[1]));
												
												var retencion_isr = $("#retencion_isr_documentos").val();
												retencion_isr = retencion_isr.replace(",", "");
												retencion_isr = retencion_isr == "" ? retencion_isr = 0 : retencion_isr = retencion_isr; 
												
												var retencion_iva = $("#retencion_iva_documentos").val();
												retencion_iva = retencion_iva.replace(",", "");
												retencion_iva = retencion_iva == "" ? retencion_iva = 0 : retencion_iva = retencion_iva; 
												
												/*var total_r = $("#total").val();
												total_r = total_r.replace(",", "");
												total_r = total_r == "" ? total_r = 0 : total_r = total_r; 
												
												var subRet = parseFloat(retencion_isr) + parseFloat(retencion_iva);
												
												var nuevo_total = parseFloat(subRet) + parseFloat(total_r);*/
												
												/*$("#total").val(formatear_pesos(nuevo_total));
												$("#saldo").val(formatear_pesos(nuevo_total));*/
												
												}
									$("#valida_xml").val(1); //Indicamos que se guarda a partir de XML
									$("#nombre_xml").val(datos.archivo);
									$('#iva_xml').val(datos.iva[0])
									tipoCalculoCXP(2);
									calculaSaldoCXP();
									}
							}
						});
						
						}
				
				}
{/literal}
</script>

<h1 class="titulo-catalogo" style="margin-top:15px;">{$nombre_menu}</h1>

<!--div de espera -->
<!--
<div style="z-index:5000; display:none; position:absolute; left:0; top:0;" id="waitingplease">
	<img src="../../imagenes/general/wait.gif" border="0" style="z-index:2000; position:absolute" id="imgW1"/>
	<img src="../../imagenes/general/back_wait.gif" border="0" style="z-index:1000; position:absolute" id="imgW2"/>
</div>
 -->
<div class="tabla" id="tabla">
<form action="encabezados.php" name="forma_datos" id="forma_datos" method="post" enctype="multipart/form-data" autocomplete="off">
{* make indicara la accion que realizaremos dar click al boton de guardar *}

{if $popup eq 'SI'}
	<input type="hidden" name="popup" value="{$popup}" />
    <input type="hidden" name="funcpop" value="{$funcpop}" />
    <input type="hidden" name="valpopup" value="{$valpopup}" />
    <input type="hidden" name="popup2" value="SI" />
    <input type="hidden" name="closewindow" value="10" />
{/if}
<input type="hidden"  name="porcentaje_iva_general" id="porcentaje_iva_general"  value="{$porcentaje_iva}"  />
<input type="hidden"  name="make" id="make"  value="{$make}"  />
<input type="hidden"  name="t" id="t"  value="{$t}"  />
<input type="hidden"  name="v"  id = "v" value="{$v}"  />
<input type="hidden"  name="op" id="op"  value="{$op}"  />
<input type="hidden"  name="suc" id="suc"  value="{$id_sucursal}"  />

<input type="hidden" id="hf" name="closewindow" value="{$hf}" />

<input type="hidden" name="nreg" value="{$nreg}" />
<input name="strSelected" type="hidden" value="" />
<input name="tipo_mensaje" type="hidden" value="{$tipo_mensaje}" />
<input name="keycodif" id="keyCodif" type="hidden" value="{$keyCodif}" />

<input name="generaSubmit" type="hidden" value="0" />
<input name="iva_100" type="hidden"  id="iva_100" value="{$iva_100}" />


<!--Campos utilizados  -->
<!--<input type="hidden" id="porcentaje"  value="" />-->
<input type="hidden" id="cl"  value="{$cl}" />
<input type="hidden" id="stm" name="stm" value="{$stm}" />
<a href="" class="thickbox" id="thickbox_href" ></a>  
<!--------------------------- SECCION AQUI DEBEN IR LO BOTONES ---------------------------->
<br />
<table>
	<tr>
        
		{if $v neq '0'  and $v neq '3' and $v neq ''  and $npe neq 0 and $t neq 'YWRfY29zdGVvX3Byb2R1Y3Rvcw==' and $t neq 'bmFfbW92aW1pZW50b3NfYWxtYWNlbg==' and $t neq 'YWRfZmFjdHVyYXM='and $t neq 'Y2xfcmFuZ29zX2lyZHM=' }
			<td >
			 <input type="button" name="nuevo" value=" Nuevo &raquo;" onclick="irNuevo('{$rooturl}','{$t}','{$tcr}','{$stm}')" class="button_new"/>&nbsp;&nbsp;&nbsp;
			</td>
		{elseif ( $t eq 'bmFfbW92aW1pZW50b3NfYWxtYWNlbg=='  && ($stm eq '70003' || $stm eq '70007' || $stm eq '70008' || $stm eq '70004' || $stm eq '70011'  || $stm eq '70011'  || $stm eq '70012'  || $stm eq '70033' || $stm eq '70055' || $stm eq '70066' || $stm eq '70088') ) }
        	<td ><!----excepcion para los almacenes de nasser------>
			 <input type="button" name="nuevo" value=" Nuevo &raquo;" onclick="irNuevo('{$rooturl}','{$t}','{$tcr}','{$stm}')" class="button_new"/>&nbsp;&nbsp;&nbsp;
			</td>
        {/if}	
        
		        
        {if $v eq '3'  and $epe neq 0}
			<td align="right" width="750">
				<input type="button" name="nuevo" value=" Eliminar &raquo;" onclick="irEliminarRegistro(this.form,'{$rooturl}','{$t}','campo_0','{$stm}')" class="button_new"/> &nbsp;&nbsp;&nbsp;
			</td>
        {else}
	        {if $v eq '3' and $epe neq 0}
	        	<td align="right" width="750">
	        		<input type="button" name="cancelar" value=" Cancelar " onclick="CancelarFactura(this.form,'{$rooturl}','{$t}','campo_0')" class="button_cancelar"/>&nbsp;&nbsp;&nbsp;
	    		</td>
	        {/if}	
		{/if}
        
               {if $v neq '0' and $v neq '3' and  $v neq ''  and $mpe neq 0  and $t neq 'YWRfZmFjdHVyYXNfYXVkaWNlbA==' and $t neq 'YWRfZmFjdHVyYXM='  and $t neq 'YWRfbm90YXNfY3JlZGl0bw=='and $t neq 'YWRfbm90YXNfY3JlZGl0b19hdWRpY2Vs' }
  	    	{if $modifica_doc neq '0' and not ($t eq 'cmFjX3BlZGlkb3M=' and $atributos[68][15] eq 1) and $t neq 'YWRfZmFjdHVyYXM='}
  	    		<td >
  	    			<input type="button" name="mod" value=" Modificar &raquo;" onclick="irModificarRegistro(this.form,'{$rooturl}','{$t}','campo_0','{$tcr}','{$stm}')" class="button_modificar"/> &nbsp;&nbsp;&nbsp;
				</td>
				
				
  	    	{/if}  	    
		{/if}
        
        {if $v neq '1'  and $v neq '3'}
            {if $especialValorProyectoPedidos eq '1'}
				{if $t neq 'cmFjX3BlZGlkb3M=' and $atributos[68][15] neq 1}
					<td>
						<input type="button" name="actualizarEspecial"  id = "actualizarEspecialb" value=" Actualizar &raquo;" onclick="validayGuardaEspecial(this.form,'actualizar')" class="button_save"/> &nbsp;&nbsp;&nbsp;
					</td>
				{/if}
				
				{if $t eq 'cmFjX3BlZGlkb3M=' and $atributos[68][15] eq 1}
					<!--<td >
						<input type="button" name="mod" value=" Cotizar &raquo;" onclick="generarCot({$k})" class="botonSecundario"/> &nbsp;&nbsp;&nbsp;
					</td>-->
				{/if}
            {else} 
		        <td >
				<input type="button" name="modificar"  id = "guardarb" value=" Guardar &raquo;" onclick="valida(this.form,'actualizar')" class="button_save"/> &nbsp;&nbsp;&nbsp;
			</td>
            {/if}
		{/if}
        
        {if $hf neq 10}
			{if ($make eq 'insertar' or ($make eq 'actualizar' and $v eq 0)) and $mensaje_salida eq '1'}
            	<td >
            		<input type="button" name="listado" value=" Listado &raquo;" direccion="{$rooturl}code/indices/listados.php?t={$t}&stm={$stm}" onclick="Redirecciona(this)" class="button_list"/>&nbsp;&nbsp;&nbsp;
        		</td>                			
				 			
			{else}            	
				<td >
					<input type="button" name="listado" value=" Listado &raquo;" onclick="irListado('{$rooturl}','{$t}','{$stm}')" class="button_list"/>&nbsp;&nbsp;&nbsp;
				</td>                
			{/if}
            {if  ($t eq 'cmFjX3BlZGlkb3M=' or $t eq 'cmFjX2NvdGl6YWNpb25lcw==' or $t eq 'cmFjX29yZGVuZXNfc2VydmljaW8=') and $v eq 1}        
				<td >
				<input type="button" name="imprimir"  id = "guardarb" value=" PDF &raquo;" onclick="imprimeDoc();" class="botonSecundario"/> &nbsp;&nbsp;&nbsp;
				</td>
			</td>  
			{/if}
            {if  ($t eq 'YWRfY3VlbnRhc19wb3JfcGFnYXJfb3BlcmFkb3Jh'||$t eq 'YWRfY3VlbnRhc19wb3JfcGFnYXJfZGlzdHJpYnVpZG9yZXM=') and $v eq 0}
					<td >
							<div class="upload" style="width: 120px; height: 25px; overflow: hidden; background:#555; position:relative; border-radius:3px">
									<div id="textoFile" style="position:absolute; top:25%; left:20%; color:#FFF; font-weight:bold; font-size:12px;">
											Cargar XML &raquo;
									</div>
									<input type="file" name="leeXML"  id="leeXML" value="Agregar Datos XML &raquo;" class="botonSecundario" style="display: block !important; width: 120px !important; height: 25px !important; opacity: 0 !important; overflow: hidden !important;" onchange="if(!this.value.length) return false; agregaXML(this);"/> 
							</div>
					</td>
            {/if}
            {if  ($t eq 'cmFjX3BlZGlkb3M=') and $v eq 1}
				{if $atributos[68][15] eq 0}
					<td >
						<input type="button" name="enviarxmail"  id = "enviarEmailb" value=" Enviar al cliente &raquo;" onclick="enviarEmailSolCot({$k});" class="botonSecundario"/> &nbsp;&nbsp;&nbsp;
					</td>
				{/if}
				
					<td >
						<input type="button" name=""  id = "" value=" Cliente cancela &raquo;" onclick="cancelarSolCot(4);" class="botonSecundario"/> &nbsp;&nbsp;&nbsp;
					</td>
					
					<td >
						<input type="button" name=""  id = "" value=" Vendedor cancela &raquo;" onclick="cancelarSolCot(5);" class="botonSecundario"/> &nbsp;&nbsp;&nbsp;
					</td>
				
				{if $atributos[68][15] eq 1}
					<td >
						<input type="button" name=""  id = "" value=" Replicar solicitud &raquo;" onclick="replicarSolCot();" class="botonSecundario"/> &nbsp;&nbsp;&nbsp;
					</td>
				{/if}
					
				{if $atributos[68][15] eq 1 and $atributos[6][15] eq 7}	
					<td >
						<input type="button" name=""  id = "" value=" Generar Cotización &raquo;" onclick="generarCot({$k});" class="botonSecundario"/> &nbsp;&nbsp;&nbsp;
					</td>
				{/if}
			{/if}
			
			{if  ($t eq 'cmFjX2NvdGl6YWNpb25lcw==') and $v eq 1}        
				<td >
					<input type="button" name="enviarxmail"  id = "enviarEmailb" value=" Enviar al cliente &raquo;" onclick="enviaMailCot({$k});" class="botonSecundario"/> &nbsp;&nbsp;&nbsp;
				</td>            
			{/if}
            
        {/if}  
        {if $t eq 'bmFfdmFsZXNfcHJvZHVjdG9z' and $v eq 1}
				
					<td >
						&nbsp;&nbsp;&nbsp;<input type="button" name="imprimirVale"  id="imprimirVale" value="Imprimir &raquo;" onclick="imprimeVale({$k});" class="botonSecundario"/> 
					</td>
		{/if}
    
        {if ($t eq 'cmFjX2NvdGl6YWNpb25lcw==') and $v eq 1}
				<td >
					<input type="button" name=""  id = "" value=" Cliente cancela &raquo;" onclick="cancelarCot(4);" class="botonSecundario"/> &nbsp;&nbsp;&nbsp;
				</td>
				
				<td >
					<input type="button" name=""  id = "" value=" Vendedor cancela &raquo;" onclick="cancelarCot(5);" class="botonSecundario"/> &nbsp;&nbsp;&nbsp;
				</td>
				
				<td >
					<input type="button" name=""  id = "" value=" Dirección cancela &raquo;" onclick="cancelarCot(6);" class="botonSecundario"/> &nbsp;&nbsp;&nbsp;
				</td>
		{/if}
		
		
		{if $t eq 'cmFjX2NvdGl6YWNpb25lcw==' and $atributos[9][15] eq 1}	
				<td >
					<input type="button" name=""  id = "" value=" Aprobación Cliente &raquo;" onclick="actEstatusCot({$k}, 2);" class="botonSecundario"/> &nbsp;&nbsp;&nbsp;
				</td>
		{/if}
		
		{if $t eq 'cmFjX2NvdGl6YWNpb25lcw==' and $atributos[9][15] eq 2}	
				<td >
					<input type="button" name=""  id = "" value=" Aprobación Cobranza &raquo;" onclick="actEstatusCot({$k}, 14);" class="botonSecundario"/> &nbsp;&nbsp;&nbsp;
				</td>
		{/if}
		
		{if $t eq 'cmFjX2NvdGl6YWNpb25lcw==' and $atributos[9][15] eq 14}	
				<td >
					<input type="button" name=""  id = "" value=" Generar Orden de Sevicio &raquo;" onclick="generarOrdenServicio({$k});" class="botonSecundario"/> &nbsp;&nbsp;&nbsp;
				</td>
		{/if}
        {if $t eq 'YWRfcGVkaWRvcw==1' and ($op eq 1 or $op eq 2 && $v eq 0)}
				<td >
					<input type="button" name="descuento"  id = "descuento" value="%" onclick="solicitaDescuento();" class="botonSecundario descuentoFancy"/>
				</td>
		{/if}
		{if  ($t eq 'YWRfcGVkaWRvcw==') and $v eq 1}
				
					<td >
						&nbsp;&nbsp;&nbsp;<input type="button" name="imprimirPedido"  id="imprimirPedido" value=" Imprimir &raquo;" onclick="imprimePedido({$k});" class="botonSecundario"/> 
					</td>
					<!--<td >
						&nbsp;&nbsp;&nbsp;<input type="button" name="ImpClausula"  id="ImpClausula" value=" Clausulas &raquo;" onclick="imprimeClausula({$k});" class="botonSecundario"/> 
					</td>-->
		{/if}
		{if  ($t eq 'bmFfZWdyZXNvc19jYWphX2NoaWNh') and $v eq 1}
					<td >
						&nbsp;&nbsp;&nbsp;<input type="button" name="imprimirCajaChicaE"  id="imprimirCajaChicaE" value=" Imprimir &raquo;" onclick="imprimeEgresoCaja({$k});" class="botonSecundario"/> 
					</td>
		{/if}
		
		{if  ($t eq 'bmFfYml0YWNvcmFfcnV0YXM=') and $v eq 1}
				
					<td >
						&nbsp;&nbsp;&nbsp;<input type="button" name="imprimirBitacora"  id="imprimirBitacora" value="Imprimir Bit&aacute;cora &raquo;" onclick="imprimeBitacora({$k});" class="botonSecundario"/> 
					</td>
		{/if}
		
		{if  ($t eq 'bmFfbW92aW1pZW50b3NfYWxtYWNlbg==') and $v eq 1}
				
					<td >
						&nbsp;&nbsp;&nbsp;<input type="button" name="imprimirMovimiento"  id="imprimirMovID" value="Imprimir Movimiento &raquo;" onclick="imprimirMov({$k});" class="botonSecundario"/> 
					</td>
		{/if}
		{if  ($t eq 'YWRfb3JkZW5lc19jb21wcmFfcHJvZHVjdG9z') and $v eq 1}
					<td >
						&nbsp;&nbsp;&nbsp;<input type="button" name="imprimeOrdenCompra"  id="imprimeOrdenCompra" value=" Imprimir &raquo;" onclick="imprimeOrdenCompraE({$k});" class="botonSecundario"/> 
					</td>
		{/if}
    
        
        	<!--TABLA: {$t} -->         
		{if $t eq '2' and $ipe neq 0}
			<td>
				<input type="button" value="Imprimir &raquo;" class="botonSecundario" onClick="imprime('{$t}');">&nbsp;&nbsp;&nbsp;
			</td>
		{/if}   
		{if $t eq 'cmFjX2FydGljdWxvcw=='}
				<td style="padding-left : 15px;">
  	    			<input type="button" name="ant" value="&laquo; Anterior" onclick="navegaRegistros('{$t}', '{$k}', '{$v}', 'a')" class="botonSecundario"/> &nbsp;&nbsp;&nbsp;
				</td>
				<td style="padding-left : 10px;">
  	    			<input type="button" name="sig" value=" Siguiente &raquo;" onclick="navegaRegistros('{$t}', '{$k}', '{$v}', 's')" class="botonSecundario"/> &nbsp;&nbsp;&nbsp;
				</td>
		{/if}
        
        <!--{if $t eq 'YWRfZmFjdHVyYXNfYXVkaWNlbA==' or $t eq 'YWRfZmFjdHVyYXM=' or $t eq 'YWRfbm90YXNfY3JlZGl0bw==' or $t eq 'YWRfbm90YXNfY3JlZGl0b19hdWRpY2Vs'}
				<td style="padding-left : 15px;">
  	    			<input type="button" name="ant" value="Sella y Timbra" onclick="SellaYTimbra({if $t eq 'YWRfZmFjdHVyYXNfYXVkaWNlbA=='}'ad_facturas_audicel'{elseif $t eq 'YWRfZmFjdHVyYXM=' }'ad_facturas'{elseif $t eq 'YWRfbm90YXNfY3JlZGl0b19hdWRpY2Vs'}'ad_notas_credito_audicel'{elseif $t eq 'YWRfbm90YXNfY3JlZGl0bw=='} 'ad_notas_credito'{/if})" class="botonSecundario"/> &nbsp;&nbsp;&nbsp;
				</td>
			
		{/if}-->
        	{if  ($t eq 'YWRfZmFjdHVyYXNfYXVkaWNlbA==' or $t eq 'YWRfZmFjdHVyYXM=') and $v eq 1}        
				<td >
						<input type="button" name="listado" value=" Enviar por E-mail &raquo; " onclick="mandarMailFactura({$k})" class="button_list"/>
				</td>            
			{/if}
			
			{if $t eq 'YWRfZmFjdHVyYXNfYXVkaWNlbA==' AND $op eq 2}
					<td style="padding-left : 15px;">
							<input type="button" name="cobros"  id = "detalleCobros" value="Agregar Cobro &raquo;" onclick="abreCobros({$k});" class="botonSecundario"/>
					</td>
			{/if}

		
	</tr>
</table>

{if $mensaje_err neq ''}
	<table>
    	<tr>
        	<td>
            	<br />
            	<font face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000" size="2">
                	<b>{$mensaje_err}</b>
                </font>
            </td>
        </tr>
    </table>
{/if}




{* TABLA DE CAMPOS *}  

{assign var="contColumnas" value=0}	
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
<!---CUENTAS POR PAGAR----->
{if $t eq 'YWRfY3VlbnRhc19wb3JfcGFnYXJfb3BlcmFkb3Jh'}
		<script type="text/javascript">
		{literal}
				$(document).ready(function() {
				ocultaCamposCXP();
				ocultaGridCXP();
				agregaEgresosCXP();
				var href = $(location).attr('href');
				href = href.split("idCosteo=");
				
				if($("#op").val() == 1)
						$("#id_costeo_productos").val(href[1]);
						
				/*if($("#op").val() == 1 || $("#op").val() == 2)
						var egresos = calculaTotalesN('detallePagosEgresosCuentasPorPagar', 4, 'subtotal_egresos');
						calculaSubtotalesN();*/
				});
		{/literal}
		</script>
{/if}
{if $t eq 'YWRfY3VlbnRhc19wb3JfcGFnYXJfb3BlcmFkb3Jh' AND $op eq 1}
		<script type="text/javascript">
		{literal}
				$(document).ready(function() {
						colocaFechaActual('fecha_captura');
						colocaTipoProveedocxp();
						colocaProveedores();
				});
		{/literal}
		</script>
{/if}

{if $t eq 'bmFfY2xpZW50ZXNfZGlyZWNjaW9uZXNfZW50cmVnYQ==' AND $op eq 2}
		<script type="text/javascript">
		{literal}
				$(document).ready(function() {
				var href = $(location).attr('href');
						href = href.split("modhf=");
						if(href[1] == 1){
								$("#header_bg").css("display", "none");
								$("input[name=listado]").css("display", "none");
								}
				//$("#forma_datos").append("<input type='hidden' id='indicaMod' value='0' name='indicaMod'/>");
				});
		{/literal}
		</script>
{/if}

{if $t eq 'bmFfY2xpZW50ZXNfZGlyZWNjaW9uZXNfZW50cmVnYQ==' AND $hf eq '10' AND $op eq 1}
		<script type="text/javascript">
		{literal}
				$(document).ready(function() {
						var href = $(location).attr('href');
						href = href.split("id_cliente=");	
						$("#id_cliente").val(href[1]);
						$("#id_ciudad option").remove();
						$("#id_ciudad").append("<option value='0'>Selecciona una opci&oacute;n</option>");
						}); 
		
				function obtenCiudad(selectHijo,id_estado){
						var opcion = $("#"+id_estado+"").find("option:selected").val();
						var urlAjax = "llenaCiudadCombo.php";
						var envio_datos = 'id=' + opcion;  // Se arma la variable de datos que procesara el php
						ajaxCombos(urlAjax, envio_datos, selectHijo);
				}
				function ajaxCombos(url, datos, hijo){
										$.ajax({
											async:false,
											type: "POST",
											dataType: "html",
											contentType: "application/x-www-form-urlencoded",
											data: datos,
											url:'../ajax/' + url,
											/*beforeSend:function(){
													},*/
											success: function(data) {
													
													
													$("#" + hijo + " option").remove();
													$("#" + hijo).append(data);
													},
											timeout:50000
											});
									}
		{/literal}
		</script>
{/if}
{if $t eq 'bmFfY2xpZW50ZXNfZGlyZWNjaW9uZXNfZW50cmVnYQ==' AND $hf eq '10' AND $op eq 2}
		<script type="text/javascript">
		{literal}
				$(document).ready(function() {
						$("input[name=nuevo]").css("display", "none");
						var href = $(location).attr('href');
						href = href.split("id_registro=");	
						
						var envia_datos = "id_registro=" + href[1];
						
						var url = "llenaRegistrosPedidosDireccion.php";
						
						var registros = ajaxN(url, envia_datos);
						
						registros = registros.split("|");
					
						
						$("#calle").val(registros[0]);
						$("#numero_exterior").val(registros[1]);
						$("#numero_interior").val(registros[2]);
						$("#colonia").val(registros[3]);
						$("#delegacion_municipio").val(registros[4]);
						$("#campo1_id_estado").val(registros[5]);
						$("#campo1_id_ciudad").val(registros[6]);
						$("#codigo_postal").val(registros[7]);
						$("#telefono_1").val(registros[8]);
						$("#telefono_2").val(registros[9]);
						$("#celular").val(registros[10]);
						$("#referencias").val(registros[11]);
						$("#campo1_id_ruta").val(registros[12]);
						$("#forma_datos").append("<input type='hidden' id='indicaMod' value='1' name='indicaMod'/>");
						});
		
		function ajaxN(url, datos){					
			var entrega;
			$.ajax({
					async:false,
					type: "POST",
					dataType: "html",
					contentType: "application/x-www-form-urlencoded",
					data: datos,
					url:'../ajax/' + url,
					/*beforeSend:function(){
					},*/
					success: function(data) {
							entrega = data;								
						
					},
					timeout:50000
					});
			return entrega;
			}		
				
		{/literal}
		</script>
{/if}

{if $t eq 'bmFfY2xpZW50ZXM=' AND $op eq 2}
		<script type="text/javascript">
		{literal}
				$(document).ready(function() {
				
						var href = $(location).attr('href');
						href = href.split("modhf=");
						if(href[1] == 1){
								$("#header_bg").css("display", "none");
								$("input[name=listado]").css("display", "none");
								$("#divgrid_detallesclientesdirent").css("display", "none");
								
								}
						//$("#forma_datos").append("<input type='hidden' id='indicaMod' value='0' name='indicaMod'/>");
						});
		{/literal}
		</script>
{/if}
				
{if $t eq 'bmFfY2xpZW50ZXM=' AND $hf eq '10' AND $op eq 2}
		<script type="text/javascript">
		{literal}
				$(document).ready(function() {
						$("input[name=nuevo]").css("display", "none");
						requiereFactura();
						var href = $(location).attr('href');
						href = href.split("id_registro=");	
						
						var envia_datos = "id_registro=" + href[1];
						
						var url = "llenaRegistrosPedidos.php";
						
						var registros = ajaxN(url, envia_datos);
						
						registros = registros.split("|");
						
						$("#id_cliente").val(registros[13]);
						$("#nombre").val(registros[0]);
						$("#campo1_id_sucursal_alta").val(registros[1]);
						$("#apellido_paterno").val(registros[2]);
						$("#apellido_materno").val(registros[3]);
						$("#campo1_id_categoria_cliente").val(registros[4]);
						$("#telefono_1").val(registros[5]);
						$("#telefono_2").val(registros[6]);
						$("#celular").val(registros[7]);
						$("#email").val(registros[8]);
						$("#campo1_id_tipo_pago").val(registros[9]);
						$("#campo1_id_fuente_contacto").val(registros[10]);
						$("#campo1_requiere_factura").val(registros[11]);
						$("#campo1_activo").val(registros[12]);
						$("#forma_datos").append("<input type='hidden' id='indicaMod' value='1' name='indicaMod'/>");
						
						});
		
		function ajaxN(url, datos){					
			var entrega;
			$.ajax({
					async:false,
					type: "POST",
					dataType: "html",
					contentType: "application/x-www-form-urlencoded",
					data: datos,
					url:'../ajax/' + url,
					/*beforeSend:function(){
					},*/
					success: function(data) {
							entrega = data;								
						
					},
					timeout:50000
					});
			return entrega;
			}
		
		{/literal}
		</script>
{/if}

{if ($t eq 'bmFfY2xpZW50ZXM=' ) AND $hf eq '10'}
<script type="text/javascript">
{literal}
		$(document).ready(function() {
				muestraDiasCredito();
				requiereFactura();
				//diasCredito();
				
				$("#divgrid_detallesclientesdirent").hide();
				$('#id_estado > option[value="0"]').attr('selected', 'selected');
				
				var envio_datos = 'caso=1';
				var urlAjax = "obtenSesion.php";
				var sesion = ajaxN(urlAjax, envio_datos);
				$('#id_sucursal_alta > option[value="' + sesion + '"]').attr('selected', 'selected');
				$("#id_ciudad option").remove();
				$("#id_ciudad").append("<option value='0'>Selecciona una opci&oacute;n</option>");

				
				});
function ajaxN(url, datos){					
			var entrega;
			$.ajax({
					async:false,
					type: "POST",
					dataType: "html",
					contentType: "application/x-www-form-urlencoded",
					data: datos,
					url:'../ajax/' + url,
					/*beforeSend:function(){
					},*/
					success: function(data) {
							entrega = data;								
						
					},
					timeout:50000
					});
			return entrega;
			}		
function requiereFactura(){
		if($("#v").val() == 1){
				var condicion = $("#requiere_factura").val() == 1 ? condicion = 1 : condicion = 0;
				}
		else{
				var condicion = $("#requiere_factura").is(':checked') ? condicion = 1 : condicion = 0;
				}
				
		if(condicion == 1) {
				$("#tabla_fila_catalogo_17").css("display", "block");
				$("#div_fila_catalogo_17").css("display", "block");
				var telefono = $("#telefono_1").val();
				var correo = $("#email").val();
				$("#telefono_facturas_1").val(telefono);
				$("#email_envio_facturas").val(correo);
				} 
		else{
				$("#tabla_fila_catalogo_17").css("display", "none");
				$("#div_fila_catalogo_17").css("display", "none");
				limpiaCampos("nombre_razon_social" ,"texto");
				limpiaCampos("rfc","texto");
				limpiaCampos("calle","texto");
				limpiaCampos("numero_exterior","texto");
				limpiaCampos("numero_interior","texto");
				limpiaCampos("colonia","texto");
				limpiaCampos("delegacion_municipio","texto");
				limpiaCampos("cp","texto");
				limpiaCampos("telefono_facturas_1","texto");
				limpiaCampos("email_envio_facturas","texto");
				limpiaCampos("id_estado","combo");
				limpiaCampos("id_ciudad","combo");
				}
		}
	function limpiaCampos(campo, tipo){
				if(tipo == "texto")
						$("#" + campo).val("");
				else if(tipo == "combo")
						$('#' + campo + ' > option[value="0"]').attr('selected', 'selected');
				}
		function muestraDiasCredito(){
		var tipoPago = $("#id_tipo_pago").find("option:selected").val();
		if(tipoPago == 2){
				$("#fila_catalogo_14").show();
				}
		else{
				$("#fila_catalogo_14").hide();
				}
		
		
		}
		function obtenCiudad(selectHijo,id_estado){
						var opcion = $("#"+id_estado+"").find("option:selected").val();
						var urlAjax = "llenaCiudadCombo.php";
						var envio_datos = 'id=' + opcion;  // Se arma la variable de datos que procesara el php
						ajaxCombos(urlAjax, envio_datos, selectHijo);
				}
		function ajaxCombos(url, datos, hijo){
										$.ajax({
											async:false,
											type: "POST",
											dataType: "html",
											contentType: "application/x-www-form-urlencoded",
											data: datos,
											url:'../ajax/' + url,
											/*beforeSend:function(){
													},*/
											success: function(data) {
													
													
													$("#" + hijo + " option").remove();
													$("#" + hijo).append(data);
													},
											timeout:50000
											});
									}
	
{/literal}

</script>
{/if}

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
                            		
                                {if $atributos[indice][25] neq '-1' and $atributos[indice][25] neq ''}
                                    <br />	
                                    <div id="div_fila_catalogo_{$smarty.section.indice.index}" style="width:920px; height:15px; background-color:#E5E5E5; color:#333; font-size:10pt; padding-top:2px; padding-bottom:2px " >
                                      <b> <div style=""> &nbsp;&nbsp; {$atributos[indice][25]} </div></b>
</div>                              <br />
                               	{else}
                                	  <br />
                                {/if}                               
                                
                                <div>
                                <table style="width:920px;" id="tabla_fila_catalogo_{$smarty.section.indice.index}" border="0" class="form-space">
                                {assign var="generaDiv" value = 1}
                                {if $atributos[indice][25] eq 'Características Particulares'}
                                	<tr>
                                    	<td colspan="6" style="font-size:14px; color:#06C; height:70px;">
                                        	Designa con el número 1 a la característica que creas que mejor te describe, 
                                        	con el número 2 a la siguiente, y así sucesivamente hasta asignar el número 11 a la
                                            característica que creas que menos te define:<br />
                                    	</td>
                                 	</tr>
                                {/if}
                            {/if}
                        {/if}
                    {/if}
                    
                    {if $atributos[indice][40] eq "1"}
                    	</tr>
                        <tr><td class="{$atributos[indice][18]}" style="text-align:left" colspan="4"><p>{$atributos[indice][2]}</p></td></tr>
                        <tr id="fila_catalogo_{$smarty.section.indice.index}"> 
					{/if}
                    
					{if $atributos[indice][38] eq "1"}
						<tr id="fila_catalogo_{$smarty.section.indice.index}"> 
					{/if}
					             
                                                
					<!-------------- SE PONE EL NOMBRE DE LA ETIQUETA PARA EL CAMPO -------------------->
					<!-------------- AQUI VA EL WIDTH 110 EN LOS CUATRO ELEMENTOS -------------------->
                    {* 1/ Para los campos visibles (visible->6)*}
         			                   	
                    {if $atributos[indice][6] eq "1"}
                        {if $atributos[indice][3] neq 'ARCHIVO'}
                        	{if $atributos[indice][40] eq "1"}
                                <td class="{$atributos[indice][18]}" colspan="1" ></td>
                            {else if $atributos[indice][3] eq 'ARCHIVO'}
                            	<td class="{$atributos[indice][18]}" colspan="1" ><p>{$atributos[indice][2]}</p></td>
                         	{/if}
                    {else if $atributos[indice][3] eq 'ARCHIVO'}
                        {if $v neq 1 or $op eq 3}
                            <td class="{$atributos[indice][18]}" colspan="1" ><p>{$atributos[indice][2]}</p></td>
						{else if $v eq 1  and $atributos[indice][3] eq 'ARCHIVO'}
                         	<td class="{$atributos[indice][18]}" colspan="1" ><p>{$atributos[indice][2]}</p></td>
                        {/if}
                    {/if}                  	
                  	              

                	<!----------------------------------------------------------------------------------->
                    
                    <input type="hidden"  name="propiedades_[{$smarty.section.indice.iteration-1}]"  
                    value="{$atributos[indice][2]}|{$atributos[indice][3]}|{$atributos[indice][5]}"  />	
                    				
                    <td colspan="{$atributos[indice][39]}">
                    
                    {* 1.1 el control que Utilizaremos*}
                    {* 1.1.1 para char y enteros*}
                
                    {if $atributos[indice][3] eq "CHAR" or $atributos[indice][3] eq "MAIL" }
                        {*****{if $atributos[indice][4] > 100 }	***********}
                            <!--<textarea name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" cols="20" rows="3" 
                            class="{$atributos[indice][19]}" {$readonly} onblur="{$atributos[indice][28]}" 
                            onkeypress="{$atributos[indice][21]}" {if $atributos[indice][7]  eq '0'}readonly{/if}>{$atributos[indice][15]}
                            </textarea>-->
                        {*****{else}***********}
                            {*para llaves del tipo cadena*}
                            {if $atributos[indice][7]  eq '0'}
                                <input type="text" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                                maxlength="{$atributos[indice][4]}" size="{$atributos[indice][37]}" value="{$atributos[indice][15]}"  
                                class="{$atributos[indice][19]}" readonly onblur="{$atributos[indice][28]}" 
                                title="Tipo texto, {$atributos[indice][4]} caracteres">
                            {else}
								{if $t eq "YWRfY2xpZW50ZXM=" && $atributos[indice][0] eq "no_certificado"}
									<input type="text" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
									maxlength="{$atributos[indice][4]}" size="{$atributos[indice][37]}" value="{$atributos[indice][15]}"  
									class="{$atributos[indice][19]}" {$readonly} onblur="{$atributos[indice][28]}" 
									onkeypress="{$atributos[indice][21]}" title="Tipo texto, {$atributos[indice][4]} caracteres" onfocus="{$atributos[indice][22]}" value="{$atributos[indice][15]}">
								{else}
									<input type="text" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
									maxlength="{$atributos[indice][4]}" size="{$atributos[indice][37]}" value="{$atributos[indice][15]}"  
									class="{$atributos[indice][19]}" {$readonly} onblur="{$atributos[indice][28]}" 
									onkeypress="validarTexto(event,this.id);" title="Tipo texto, {$atributos[indice][4]} caracteres" onfocus="{$atributos[indice][22]}" value="{$atributos[indice][15]}">
								{/if}
                            {/if}
                        
                        {*****{/if}***********}		
                    {elseif $atributos[indice][3] eq "TEXTAREA"}	
						<textarea name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                        	cols="{$atributos[indice][37]}" rows="4" class="{$atributos[indice][19]}" {$readonly} 
                            onblur="{$atributos[indice][28]}" 
                            onkeypress="validarTexto(event,this.id);" {if $atributos[indice][7]  eq '0'}readonly{/if}>{$atributos[indice][15]}</textarea>        
                  	{elseif $atributos[indice][3] eq "LABEL"}	
						<label name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}">{$atributos[indice][15]}
                       	</label>                
                    {elseif $atributos[indice][3] eq "INT"}	                    				
                        {if $atributos[indice][11]  eq '1'}
						
                            <input type="text" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                            maxlength="{$atributos[indice][4]}" size="{$atributos[indice][37]}" value="{$atributos[indice][15]}"  
                            class="{$atributos[indice][19]}" onkeypress="prueba();"  onkeydown="prueba()" readonly 
                            title="Tipo n&uacute;mero, {$atributos[indice][4]} caracteres">
                        {else}
                            {if $atributos[indice][7]  eq '0'}
                                <input type="text" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                                maxlength="{$atributos[indice][4]}" size="{$atributos[indice][37]}" value="{$atributos[indice][15]}"  
                                class="{$atributos[indice][19]}" onkeypress="validarNumero(event,this.id);" 
                                onblur="{$atributos[indice][28]}" onchange="{$atributos[indice][29]}" readonly 
                                title="Tipo n&uacute;mero, {$atributos[indice][4]} caracteres">
                            {else}
							   
                                <input type="text" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                                maxlength="{$atributos[indice][4]}" size="{$atributos[indice][37]}" value="{$atributos[indice][15]}"  
                                class="{$atributos[indice][19]}" onkeypress="validarNumero(event,this.id);" {$readonly} 
                                onblur="{$atributos[indice][28]}" onchange="{$atributos[indice][29]}"
                                title="Tipo n&uacute;mero, {$atributos[indice][4]} caracteres" >
                            {/if}
                        
                        {/if}
                        
                        {if $make == 'insertar' AND $atributos[indice][0] eq 'monto_inicial'}
                            <font face="Verdana, Arial, Helvetica, sans-serif" size="1" color="#000000">
                                <i>Monto del cierre anterior:</i>
                            </font>
                            <font face="Verdana, Arial, Helvetica, sans-serif" size="1" color="#FF0000">
                                <b>{$monto_ultimo_cierre}</b>
                            </font>
                        {/if}
                    
                     {elseif $atributos[indice][3] eq "DECIMAL"}	                    				
                     
                        {if $atributos[indice][7]  eq '0'}
                            <input type="text" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                            maxlength="{$atributos[indice][4]}" size="{$atributos[indice][37]}" value="{$atributos[indice][15]}"  
                            class="{$atributos[indice][19]}" onkeypress="{$atributos[indice][21]}" 
                            onblur="{$atributos[indice][28]}" onchange="{$atributos[indice][29]}" readonly 
                            title="Tipo n&uacute;mero, {$atributos[indice][4]} caracteres">
                        {else}
                            <input type="text" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                            maxlength="{$atributos[indice][4]}" size="{$atributos[indice][37]}" value="{$atributos[indice][15]}"  
                            class="{$atributos[indice][19]}" onkeypress="validarNumero(event,this.id);" {$readonly} 
                            onblur="{$atributos[indice][28]}" onchange="{$atributos[indice][29]}"
                            title="Tipo n&uacute;mero, {$atributos[indice][4]} caracteres" >
                        {/if}
                        
                    
                    {elseif $atributos[indice][3] eq 'PASS'}
                        <div style="float:left">
						<input type="password" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                        maxlength="{$atributos[indice][4]}" size="{$atributos[indice][37]}" value="{$atributos[indice][15]}" 
                        class="{$atributos[indice][19]}" {$readonly} title="Tipo contrase&ntilde;a, {$atribs[indice][2]} caracteres" style="height: 25px;">				
						</div>
						<div><img src="{$rooturl}/imagenes/lock-open.png" onclick="verContrasena();" id="addCuenta" style="cursor: pointer;"/></div>
                    {elseif $atributos[indice][3] eq 'TYNYINT'}					
                        {if $atributos[indice][7] eq '0'}					
                            <input type="hidden"  name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                            value="{$atributos[indice][15]}" />
                    
                            <input type="text" name="campo1_{$smarty.section.indice.iteration-1}" id="campo1_{$atributos[indice][0]}" 
                            maxlength="{$atributos[indice][4]}" size="{$atributos[indice][37]}" value="{$atributos[indice][17]}"  
                            class="{$atributos[indice][19]}"  readonly="true" >					
                        {else}                    	
                            <p>&nbsp;<input type="checkbox" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                            value="1"  {if $atributos[indice][15] eq 'checked' or $atributos[indice][15] eq '1'} 
                            checked="checked"{/if} onclick="{$atributos[indice][30]}"></p>	 						 
                        {/if} 					
                    {elseif $atributos[indice][3] eq 'DATE'}        			
                        {if $atributos[indice][7] eq '0'}
                            <input type="text" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" maxlength="14"
                             size="20" class="{$atributos[indice][19]}" onblur="{$atributos[indice][28]}"
                             value="{$atributos[indice][15]}" onchange="{$atributos[indice][29]}" readonly title="Tipo fecha (aaaa-mm-dd)"/>
                        {else}
                            <input type="text" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" maxlength="14"
                             size="20" class="{$atributos[indice][19]}" onfocus="calendario(this);" onblur="{$atributos[indice][28]}"
                             value="{$atributos[indice][15]}" onchange="{$atributos[indice][29]}" {$readonly} 
                             title="Tipo fecha (aaaa-mm-dd)"/>
                        {/if}
                        <span style="font-size:9px">&nbsp;&nbsp;Formato dd/mm/aaaa</span>	
                    {elseif $atributos[indice][3] eq 'TIME'}
                        {if $atributos[indice][7] eq '0'}		
                            <input type="text" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" maxlength="10"
                            size="12" class="{$atributos[indice][19]}"    value="{$atributos[indice][15]}" {$readonly} 
                            title="Tipo hora (hh:mm:ss)" readonly/>
                        {else}    
                            <input type="text" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" maxlength="10"
                            size="12" class="{$atributos[indice][19]}"    value="{$atributos[indice][15]}" {$readonly} 
                            title="Tipo hora (hh:mm)"/>
                        {/if}            
                        <span style="font-size:9px">&nbsp;&nbsp;Formato HH:mm:ss</span>            
                    {elseif $atributos[indice][3] eq 'COMBO'}					
                        {* 1/ Para los campos no modificables (modificable->7)*}	
                        {if $atributos[indice][7] eq '0' }
                            <input type="hidden"  name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                            value="{$atributos[indice][15]}" />
                            
                            <input type="text" name="campo1_{$smarty.section.indice.iteration-1}" id="campo1_{$atributos[indice][0]}" 
                            maxlength="{$atributos[indice][4]}" size="{$atributos[indice][37]}" value="{$atributos[indice][17]}"  
                            class="{$atributos[indice][19]}"  readonly="true"  >	
                        {else}
                            <select name="campo_{$smarty.section.indice.iteration-1}" class="{$atributos[indice][19]}" onchange="{$atributos[indice][29]}" id="{$atributos[indice][0]}">
                            {if $op eq '1' and $atributos[indice][16][0]|@count > 1}<option value="0"> - Seleccione una opci&oacute;n - </option>
							{/if}
                            {html_options values=$atributos[indice][16][0] output=$atributos[indice][16][1]  selected=$atributos[indice][15]}
                            </select>		
							
							{if $atributos[indice][0] eq 'direccion_entrega'  }
							    <br />
							    <input class="botonSecundario" type="button" value=" + " id="agregarE"  onclick="direccion_agregar();"/>	
								<input class="botonSecundario" type="button" value=" ver " id="verE"  onclick="direccion_ver();"/>
								<input class="botonSecundario" type="button" value="+ Existente " id="verE"  onclick="direccionesExistentes();"/>		
							{/if}        
							
							{if $atributos[indice][0] eq 'direccion_evento'  }
								<br />
							    <input class="botonSecundario" type="button" value=" + " id="agregarEv"  onclick="direccion_agregar();"/>	
								<input class="botonSecundario" type="button" value=" ver " id="verEv"  onclick="direccion_ver();"/>
								<input class="botonSecundario" type="button" value="+ Existente " id="verE"  onclick="direccionesExistentes();"/>		
							{/if} 
							{if $atributos[indice][0] eq 'persona_recibe' or $atributos[indice][0] eq 'persona_entrega' }
							    <input class="botonSecundario" type="button" value=" + " id="agregarEv"  onclick="direccion_agregar_Contacto();"/>	
								<input class="botonSecundario" type="button" value="Ver" id="verE"  onclick="direccion_ver_Contacto();"/>		
							{/if} 
							
							{if  $atributos[indice][0] eq 'direccion_recoleccion' }
								<br />
							    <input class="botonSecundario" type="button" value=" + " id="agregarR"  onclick="direccion_agregar();"/>	
								<input class="botonSecundario" type="button" value=" ver " id="verR" onclick="direccion_ver();" />	
							{/if} 
                        {/if}
                    {elseif $atributos[indice][3] eq 'BUSCADOR'}           
                        {if $atributos[indice][7] eq '0'}
                            <input type="hidden"  name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                            value="{$atributos[indice][15]}" />
                            
                            <input type="text" name="campo1_{$smarty.section.indice.iteration-1}" id="campo1_{$atributos[indice][0]}" 
                            maxlength="{$atributos[indice][4]}" size="{$atributos[indice][37]}" value="{$atributos[indice][17]}"  
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
                            maxlength="{$atributos[indice][4]}" size="{$atributos[indice][37]}" value="{$atributos[indice][15][1]}"  
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
                                maxlength="{$atributos[indice][4]}" size="{$atributos[indice][37]}" value="{$atributos[indice][15][1]}"  
                                class="{$atributos[indice][19]}"  onblur="{$atributos[indice][28]}" onkeyup="activaBuscador(this,event);" 
                                datosdb="{$atributos[indice][16]}" onclick="ocultaCombobusc('campo_{$smarty.section.indice.iteration-1}')"  
                                on_change="{$atributos[indice][29]}" depende="{$atributos[indice][33]}"  
                                title="Digite una palabra, y se desplegara un menu emergente, seleccione una opci&oacute;n del menu." 
                                autocomplete="on"/>
                                <img src="{$rooturl}imagenes/general/flecha_abajo.gif" style="height:12px;" 
                                onmouseover="this.style.cursor='hand';this.style.cursor='pointer';" 
                                onclick="botonBuscador('campo_{$smarty.section.indice.iteration-1}')"/>										
                                <input type="hidden" name="campo_{$smarty.section.indice.iteration-1}" 
                                id="hcampo_{$smarty.section.indice.iteration-1}" value="{$atributos[indice][15][0]}" />               
								
                            </div>
                        {/if}
						{elseif $atributos[indice][3] eq 'ARBOL'}
                        {if $atributos[indice][7] eq '0'}
                            <input type="text" name="v_campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                            maxlength="{$atributos[indice][4]}" size="{$atributos[indice][37]}" value="{$atributos[indice][15][1]}"  
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
                                onclick="asignavalorbusc('campo_{$smarty.section.indice.iteration-1}')" >
                                </select>
                                </div>								
                                <input type="text" name="v_campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                                maxlength="{$atributos[indice][4]}" size="{$atributos[indice][37]}" value="{$atributos[indice][15][1]}"  
                                class="{$atributos[indice][19]}"  onblur="{$atributos[indice][28]}" onkeyup="activaBuscador(this,event);BuscaCuentaContable(this.value,'selcampo_{$smarty.section.indice.iteration-1}');" 
                                datosdb="{$atributos[indice][16]}" onclick="ocultaCombobusc('campo_{$smarty.section.indice.iteration-1}')"  
                                on_change="{$atributos[indice][29]}" depende="{$atributos[indice][33]}"  
                                title="Digite la Cuenta Contable, y se desplegara un menu emergente, seleccione una opci&oacute;n del menu." 
                                autocomplete="on"/>								
                                <input type="hidden" name="campo_{$smarty.section.indice.iteration-1}" 
                                id="hcampo_{$smarty.section.indice.iteration-1}" value="{$atributos[indice][15][0]}" />
                            </div>
							<img src="{$rooturl}/imagenes/general/Stack add.png" onclick="verArbolCuentasContables('{$atributos[indice][0]}','hcampo_{$smarty.section.indice.iteration-1}','addCuenta');" id="addCuenta" style="cursor: pointer;"/>
                        {/if}
						
                    {elseif $atributos[indice][3] eq 'BUSCADORCP'}
                        <input type="text" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                        maxlength="{$atributos[indice][4]}" size="{$atributos[indice][37]}" value="{$atributos[indice][15][1]}"  
                        class="{$atributos[indice][19]}"  onblur="{$atributos[indice][28]}" onkeyup="activaBuscador(this,event);" 
                        title="Tipo Buscador de CP, digite y de click en buscar para llenar los campos de Ciudad,Estado y Municipio.">
                        <input name="button" type="button"  
                        style="background-image:url(../../imagenes/general/e-boton-bg3.jpg); color:#FFFFFF; font-size:8pt; border-color:#999999 #999999 #999999 #FAFAFA; border-width:0 2px 2px 1px; height:18px;" onclick="buscadorcp('campo_{$smarty.section.indice.iteration-1}');"
                         value="Buscar"/>
                        <input type="hidden" id="hcampo_{$smarty.section.indice.iteration-1}" value="cp|id_estado|id_ciudad|id_delmpo|colonia" />
                    
					{elseif $atributos[indice][3] eq 'ARCHIVO' }
						
						{if $v neq 1}
					
									<input type="file" name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
									maxlength="{$atributos[indice][4]}" size="{$atributos[indice][37]}" class="{$atributos[indice][19]}" 
									title="Tipo Archivo, seleccione y asigne un archivo" onchange="{$atributos[indice][29]}">
									
									<input type="hidden" name="hcampo_{$smarty.section.indice.iteration-1}" value="{$atributos[indice][15]}" />
									
									<a href="{$atributos[indice][15]}" target="blank">
									<IMG SRC="{$atributos[indice][15]}" WIDTH=100 HEIGHT=100 ALT="" > </a>  
									{if $op eq '2' and  $atributos[indice][15] neq ""}
										<a href="{$atributos[indice][15]}" target="blank"><br />VER ARCHIVO CARGADO</a>										
									{/if}
									
							{elseif  ($v neq 0 and $atributos[indice][15] neq "")}
									<a href="{$atributos[indice][15]}" target="blank">VER
									<IMG SRC="{$atributos[indice][15]}" WIDTH=100 HEIGHT=100 ALT="" > </a>  
							{else}
						           <span class="nom_campo">Sin archivo</span><br>
							{/if}		
									
					{else}
						<span class="nom_campo">Sin archivo</span><br>
                    {/if}   
					
				<!-- Agregamos los botones que lanzaran el fancybox en los campos del catalogo---->
					       	
							<!--{if $atributos[indice][0] eq 'id_cliente' AND  $t eq 'YWRfcGVkaWRvcw=='}
								{if $v eq 0}
								&nbsp;<input class="botonSecundario fancy" type="button" value=" + " id="fancy_{$atributos[indice][0]}" onclick="agregaDatos('bmFfY2xpZW50ZXM=', this, 1)"/>
								{/if}
								<input class="botonSecundario fancy" type="button" value="Ver" id="fancy_{$atributos[indice][0]}" onclick="verDatos('bmFfY2xpZW50ZXM=', this, 1)"/>
							{/if}-->
							{if $atributos[indice][0] eq 'id_direccion_entrega' AND  $t eq 'YWRfcGVkaWRvcw=='}
								{if $v eq 0}
								<input class="botonSecundario fancy" type="button" value=" + " id="fancy_{$atributos[indice][0]}" onclick="agregaDatos('bmFfY2xpZW50ZXNfZGlyZWNjaW9uZXNfZW50cmVnYQ==', this, 2)"/>	
								{/if}
								<input class="botonSecundario fancy" type="button" value="Ver" id="fancy_{$atributos[indice][0]}" onclick="verDatos('bmFfY2xpZW50ZXNfZGlyZWNjaW9uZXNfZW50cmVnYQ==', this, 2)"/>
							{/if}
					<!-------------------------------------------------------------------------------->     	
					
					  	
					
        			</td>			
                    {else}
                        <input type="hidden"  name="campo_{$smarty.section.indice.iteration-1}" id="{$atributos[indice][0]}" 
                        value="{$atributos[indice][15]}"  />
                        <input type="hidden"  name="propiedades_[{$smarty.section.indice.iteration-1}]"  
                        value="{$atributos[indice][2]}|{$atributos[indice][3]}|{$atributos[indice][5]}"  />		
                        {* 1/*} 
                    {/if}      

                    
                    {assign var="contColumnas" value=$contColumnas+1}
                    
					{if $atributos[$contColumnas][38] eq "1"}
						</tr>
					{/if}
					
                {/if}	
				{assign var="contadorCiclos" value=$contadorCiclos+1}
			{/section}
			<input type="hidden" name='countReg' size="10" value = '{$smarty.section.indice.index}'/>
			<!-- Agregamos boton de replicar direccion en grid---->
					{if $t eq 'bmFfY2xpZW50ZXM=' AND $hf neq 10}
							<tr>
								<td colspan="5" style="text-align:right">
										<br><input class="botonSecundario" type="button" value="Agregar Direccion a Cliente" id="agregaDir_{$atributos[indice][0]}" onclick="agregaDireccion();"/>
								</td>
							</tr>
					{/if}
					<!-------------------------------------------------------------------------------->   
            </table>
			</div>
		</td>
    </tr>
</table>

{* TERMINA TABLA DE CAMPOS *}
<!-- Para mover los grid a las izquierda -->
{if $t eq ''  && $make neq 'insertar'}
	<script language="javascript">
	if(navigator.appName == 'Microsoft Internet Explorer')
		document.getElementById("tabla_campos").style.styleFloat="left";
	else
		document.getElementById("tabla_campos").style.cssFloat="left";
	</script>
{/if}


{* en esta parte va el grid y sus excepciones*}

<input type="hidden" name="ngrids" value="{$ngrids}" />

{section loop=$grids name=ng start=0}
	<br />
    <input type="hidden" name="file_{$smarty.section.ng.index}" value="" />
    <input type="hidden" name="grid_{$smarty.section.ng.index}" value="{$grids[ng][1]}" />       
    <input type="hidden" name="guardaen_{$smarty.section.ng.index}" value="{$grids[ng][13]}" />
{if $grids[ng][18] eq '1'}
<table>
	<tr>
		<td>
{else if $grids[ng][18] gt '1'}
<td>
{/if}

    {if $grids[ng][25] eq '1' && !($grids[ng][1] eq 'sucursalesusuarios' && $VER_SUCURSAL neq 1)}
		{if $grids[ng][1] eq 'datellaProductosServiciosCompuestos'}
			<div id="divgrid_{$grids[ng][1]}" style="display:none; float:left;">  
		{else}
			<div id="divgrid_{$grids[ng][1]}" style="display:block; float:left;">        
		{/if}
	{else}
		
    	<div id="divgrid_{$grids[ng][1]}" style="display:none ">
    {/if}
	
	
    <table class="table-duo" width="100%" cellspacing="0" cellpadding="0" border="0">
    	<tr>
        	<td>&nbsp;</td>
        	<td  width="0%">
            	<table>
                	<tr>
                    	<td class="encabezado_grid">
                    		<h1>{$grids[ng][2]}&nbsp;&nbsp;</h1>
                        </td>
                        <td>
                        	{if $grids[ng][10] neq 'false' && $grids[ng][14] neq 'S' && $grids[ng][14] neq 's' && ($grids[ng][1] neq 'detalleEgresos' && $grids[ng][1] neq 'detalleDepositosBancarios')}
				            	<img id="btnNuevaFila" src="{$rooturl}imagenes/general/btn_agregar_linea.png" alt=""  height="25" border="0" onmouseover="this.style.cursor='hand';this.style.cursor='pointer';" onclick="nuevoGridFila('{$grids[ng][1]}')">
            			    {/if}
							{* La siguiente línea valida si el grid es detalleEgresos y la tabla es egresos y la opción es guardar o editar, para mostrar el botón de cuentas por pagar *}
							{if $grids[ng][1] eq 'detalleEgresos' && $t eq 'YWRfZWdyZXNvcw==' && ($op eq 1 || $op eq 2)}
								<input class="botonSecundario" height="15px" type="button" style="vertical-align:text-top; cursor:pointer; background: #4E5457; height: 22.5px" onclick="cuentasPorPagarDetalleEgresos()" value=" Cuentas por Pagar &raquo;" />
							{/if}
							{* La siguiente línea valida si el grid es detalleDepositosBancarios y la tabla es depositos bancarios y la opción es guardar o editar, para mostrar el botón de Pedidos *}
							{if $grids[ng][1] eq 'detalleDepositosBancarios' AND $t=='YWRfZGVwb3NpdG9zX2JhbmNhcmlvcw==' AND ($op == 1 OR $op == 2)}
								<div style="padding:5px;">
								{*<input class="botonSecundario" height="15px" type="button" style="vertical-align:text-top; cursor:pointer; background: #4E5457;" onclick="pedidosDetalleDepositosBancarios()" value="Facturas &raquo;" /> *}
								{*<input class="botonSecundario" height="15px" type="button" style="vertical-align:text-top; cursor:pointer; background: #4E5457;" onclick="facturasDetalleDepositosBancarios()" value="Facturas &raquo;" />*}
								<input class="botonSecundario" height="15px" type="button" style="vertical-align:text-top; cursor:pointer; background: #4E5457;" onclick="agregaFacturaDB()" value="Agregar Facturas &raquo;" />
								
								</div>
							{/if}
							{* La siguiente línea valida si el grid es detalleCuentasPorPagarCosteo y la tabla es costeo de productos y la opción es guardar o editar, para mostrar el botón de Agregar CxP *}
							{if $grids[ng][1] == 'detalleCuentasPorPagarCosteo' AND $t=='YWRfY29zdGVvX3Byb2R1Y3Rvcw==' AND ($op == 1 OR $op == 2)}
								<div style="padding:5px;">
								<input class="botonSecundario" type="button" style="vertical-align:text-top; cursor:pointer; background: #4E5457; height: 22.5px" onclick="agregarCuentaPorPagarCosteoProductos()" value="Agregar CxP &raquo;" />
								</div>
							{/if}
                        </td>
                    </tr>
                </table>
            	
            </td>            
            	
        </tr>
        <tr>
        	<td>&nbsp;</td>
        	<td>
            	<div id="marco_{$grids[ng][1]}" style="overflow-x:auto; width:{$grids[ng][24]}px; padding:0; height: {if $grids[ng][12] eq 'S' or $grids[ng][12] eq 's'} {$grids[ng][5]+25}px">
                {else}
                	{$grids[ng][5]+23}px">    
                {/if}
                {if $t neq 'YW5kZXJwX3Byb2R1Y3Rvc190aXBvcw=='}
						<table id="{$grids[ng][1]}" cellpadding="0" cellspacing="0" border="1" Alto="{$grids[ng][5]}"
						   conScroll="{$grids[ng][7]}" validaNuevo="{$grids[ng][10]}" despuesInsertar="{$grids[ng][28]}" AltoCelda="{$grids[ng][6]}" auxiliar="0" ruta="{$grids[ng][8]}"
						   validaElimina="{$grids[ng][9]}" Datos="{$grids[ng][11]}{$llave}&campoid={$grids[ng][17]}&id_grid={$grids[ng][0]}&id_PF={$id_PF}" verFooter="{$grids[ng][12]}" guardaEn="{$grids[ng][13]}{$llave}&campoid={$grids[ng][17]}&id_grid={$grids[ng][0]}&make={$make}"
						   listado="{$grids[ng][14]}" class="tabla_Grid_RC" scrollH="{$grids[ng][23]}" despuesEliminar="{$grids[ng][29]}" estilos_header="{$grids[ng][30]}" alto_head="{$grids[ng][31]}" >
						   <!--{if $grids[ng][23] eq 'S'}
							   width="700px"
						   {/if}  -->  
							<tr class="HeaderCell">                      
								{section loop=$grid_detalle[ng] name=ngd}
								{if $grid_detalle[ng][ngd][4] neq 'libre'}
									<th tipo="{$grid_detalle[ng][ngd][4]}" modificable="{$grid_detalle[ng][ngd][5]}" mascara="{$grid_detalle[ng][ngd][6]}" align="{$grid_detalle[ng][ngd][7]}" formula="{$grid_detalle[ng][ngd][8]}" datosdb="../grid/getCombo.php?id={$grid_detalle[ng][ngd][0]}" depende="{$grid_detalle[ng][ngd][10]}" onChange="{$grid_detalle[ng][ngd][11]}" largo_combo="{$grid_detalle[ng][ngd][12]}" verSumatoria="{$grid_detalle[ng][ngd][13]}" valida="{$grid_detalle[ng][ngd][14]}" onkey="{$grid_detalle[ng][ngd][15]}" inicial="{$grid_detalle[ng][ngd][16]}" width="{$grid_detalle[ng][ngd][19]}" offsetwidth="{$grid_detalle[ng][ngd][19]}" on_Click="{$grid_detalle[ng][ngd][21]}" multidependencia="{$grid_detalle[ng][ngd][22]}" multiseleccion="{$grid_detalle[ng][ngd][23]}">{$grid_detalle[ng][ngd][2]}</th>
								{else}
									<th tipo="{$grid_detalle[ng][ngd][4]}" modificable="NO" align="{$grid_detalle[ng][ngd][7]}" width="{$grid_detalle[ng][ngd][19]}" offsetwidth="{$grid_detalle[ng][ngd][19]}" valor="{$grid_detalle[ng][ngd][2]}" on_Click="{$grid_detalle[ng][ngd][21]}">{$grid_detalle[ng][ngd][20]}</th>
								{/if}
								{/section}
							</tr>       
						</table>
				</div>
				<script>	  	
                CargaGrid('{$grids[ng][1]}');
					{if $grids[ng][27] neq '0'}
						for(ci=NumFilas('{$grids[ng][1]}');ci<{$grids[ng][27]};ci++)
							InsertaFila('{$grids[ng][1]}');
							
						{if $grids[ng][1] eq 'sucursalesusuarios' && $VER_SUCURSAL neq 1}					
							document.getElementById('csucursalesusuarios_4_0').checked = true;
							valorXY('sucursalesusuarios', 4, 0, '1');
						{/if}
					{/if}
				</script> 
        	</td>      
        </tr>
    </table>
				{/if}
        
    </div>
{if $grids[ng][18] ge '1'}
	  </td>
{/if}
{if $grids[ng][18] ge '3'}
	</tr>     
</table>
{/if}


{/section}

{if $id_PF}
	<input name="borrFaT" type="hidden" value="{$id_PF}" />
{/if}

<!--------------------------- TERMINA AQUI DEBEN IR LO BOTONES ---------------------------->
<!--------------------------- TABLA DE SUCURSALES PARA LA OPCION DE MULTISUCURSALES EN CUENTAS POR PAGAR ---------------------------->
{if $t == 'YWRfY3VlbnRhc19wb3JfcGFnYXJfb3BlcmFkb3Jh'}
<style>
		{literal}
		
		.sucursales_cxp th{
				padding : 5px;
				font-weight : bold;
				font-size : 10px;
				text-align : center;
				background-color: #e5e5e5;
				height : 15px;
				}
		caption.encabezado_grid{
				text-align : left;
				}

		.cuerpo-sucursales td:first-child{
				border-left : 1px #C4C5C7 solid;
				}
		.cuerpo-sucursales td{
				padding : 5px;
				font-size : 10px;
				border : 1px #4E5457 solid;
				border-top : none;
				border-left : none;
				}
		#scroll-tabla{
				width : 490px;
				height : 264px;
				overflow : auto;
				}
		.total_cxp label{
				font-size : 12px;
				padding-right : 10px;
				color : #808080;
				font-weight : bold;
				}
				
		#total_suc_cxp, #porc_suc_cxp{
				padding : 7px !important;
				font-size : 14px;
				width : 300px;
				height : 15px;
				border : 1px #DBE1EB solid;
				border-radius : 4px;
				background: #FFFFFF;
				background : -moz-linear-gradient(left, #FFFFFF, #F7F9FA);
				background : -webkit-linear-gradient(left, #FFFFFF, #F7F9FA);
				background : -o-linear-gradient(left, #FFFFFF, #F7F9FA);
				background : -ms-linear-gradient(left, #FFFFFF, #F7F9FA);
				color : #6E6E6E;
				}
		
		{/literal}
</style>

<table class="sucursales_cxp">
		<caption class="encabezado_grid">Prorrateo de Gasto(s) entre Areas de Negocio</caption>
		<thead>
				<tr>
						<th style="width:20px;" class="buttonHeader_12">No</th>
						<th style="width:200px;" class="buttonHeader_12">Areas de Negocio</th>
						<th style="width:100px;" class="buttonHeader_12">Porcentaje %</th>
						<th style="width:100px;" class="buttonHeader_12">Monto $</th>
				</tr>
		</thead>
</table>
<div id="scroll-tabla">
		<table border="0" class="cuerpo-sucursales"> 
				<tbody>
						{assign var="contador" value="1"}
								{section name="filasSucursales" loop=$filascxp}
								<tr>
										<td style="width:20px; text-align:center" class="Contador">{$contador}</td>
										<td style="width:203px; text-align:center">{$filascxp[filasSucursales].1}</td>
										
										<td style="width:102px; text-align:center" class="sel_porcentaje_cxp">
												<input type="text" name="porcentaje_cxp{$filascxp[filasSucursales].0}" id="porcentaje_cxp{$contador}" style="width:80px; font-size:11px; text-align:center" onkeydown="return noletras(event);" onChange="obtenPorcCXP({$contador})"/>
										</td>
										<td style="width:102px; text-align:center" class="sel_monto_cxp">
												<input type="text" name="monto_cxp{$filascxp[filasSucursales].0}" id="monto_cxp{$contador}" style="width:80px; font-size:11px; text-align:center" onkeydown="return noletras(event);" onChange="calculaPorcentajeActualCXP({$contador});" />
										</td>
										
										<td style="display:none"><input id="idSucCXP{$contador}" name="sucursalesCXP[]"type="hidden" value="{$filascxp[filasSucursales].0}"/></td>
										<td style="display:none">{$contador++}</td>
								</tr>
								{sectionelse}
										<tr>
												<td>No hay sucursales registradas</td>
										</tr>
								{/section}
				</tbody>
		</table>
</div>
<br>
<div class="total_cxp">
		<label for="total_suc_cxp">Total Sucursales:</label>
		<input type="text" name="total_suc_cxp" id="total_suc_cxp" onkeydown="return noletras(event);" disabled="disabled"/><br>
		<input type="hidden" name="porc_suc_cxp" id="porc_suc_cxp" onkeydown="return noletras(event);"/>
</div>

{/if}
<!-- PIE DE CATALOGO----------------------->
{if $pie_pagina eq 1}
<br><br>
<table>
	<tr>
        
		{if $v neq '0'  and $v neq '3' and $v neq ''  and $npe neq 0}
			<td >
				<input type="button" name="nuevo" value=" Nuevo &raquo;" onclick="irNuevo('{$rooturl}','{$t}','{$tcr}','{$stm}')" class="botonSecundario"/>&nbsp;&nbsp;&nbsp;
			</td>
		{/if}		
		        
        {if $v eq '3'  and $epe neq 0}
			<td align="right" width="750">
				<input type="button" name="nuevo" value=" Eliminar &raquo;" onclick="irEliminarRegistro(this.form,'{$rooturl}','{$t}','campo_0','{$stm}')" class="botonSecundario"/> &nbsp;&nbsp;&nbsp;
			</td>
        {else}
	        {if $v eq '3' and $epe neq 0}
	        	<td align="right" width="750">
	        		<input type="button" name="cancelar" value=" Cancelar " onclick="CancelarFactura(this.form,'{$rooturl}','{$t}','campo_0')" class="botonSecundario"/>&nbsp;&nbsp;&nbsp;
	    		</td>
	        {/if}	
		{/if}
        
        {if $v neq '0' and $v neq '3' and  $v neq ''  and $mpe neq 0 }
  	    	{if $modifica_doc neq '0' and not ($t eq 'cmFjX3BlZGlkb3M=' and $atributos[68][15] eq 1)}
  	    		<td >
  	    			<input type="button" name="mod" value=" Modificar &raquo;" onclick="irModificarRegistro(this.form,'{$rooturl}','{$t}','campo_0','{$tcr}','{$stm}')" class="botonSecundario"/> &nbsp;&nbsp;&nbsp;
				</td>
				
				
  	    	{/if}  	    
		{/if}
        
        {if $v neq '1'  and $v neq '3'}
            {if $especialValorProyectoPedidos eq '1'}
				{if $t neq 'cmFjX3BlZGlkb3M=' and $atributos[68][15] neq 1}
					<td>
						<input type="button" name="actualizarEspecial"  id = "actualizarEspecialb" value=" Actualizar &raquo;" onclick="validayGuardaEspecial(this.form,'actualizar')" class="botonSecundario"/> &nbsp;&nbsp;&nbsp;
					</td>
				{/if}
				
				{if $t eq 'cmFjX3BlZGlkb3M=' and $atributos[68][15] eq 1}
					<!--<td >
						<input type="button" name="mod" value=" Cotizar &raquo;" onclick="generarCot({$k})" class="botonSecundario"/> &nbsp;&nbsp;&nbsp;
					</td>-->
				{/if}
            {else} 
		        <td >
				<input type="button" name="modificar"  id = "guardarb" value=" Guardar &raquo;" onclick="valida(this.form,'actualizar')" class="botonSecundario"/> &nbsp;&nbsp;&nbsp;
			</td>
            {/if}
		{/if}
        
        {if $hf neq 10}
			{if ($make eq 'insertar' or ($make eq 'actualizar' and $v eq 0)) and $mensaje_salida eq '1'}
            	<td >
            		<input type="button" name="listado" value=" Listado &raquo;" direccion="{$rooturl}code/indices/listados.php?t={$t}&stm={$stm}" onclick="Redirecciona(this)" class="botonSecundario"/>&nbsp;&nbsp;&nbsp;
        		</td>                			
				 			
			{else}            	
				<td >
					<input type="button" name="listado" value=" Listado &raquo;" onclick="irListado('{$rooturl}','{$t}','{$stm}')" class="botonSecundario"/>&nbsp;&nbsp;&nbsp;
				</td>                
			{/if}
            {if  ($t eq 'cmFjX3BlZGlkb3M=' or $t eq 'cmFjX2NvdGl6YWNpb25lcw==' or $t eq 'cmFjX29yZGVuZXNfc2VydmljaW8=') and $v eq 1}        
				<td >
				<input type="button" name="imprimir"  id = "guardarb" value=" PDF &raquo;" onclick="imprimeDoc();" class="botonSecundario"/> &nbsp;&nbsp;&nbsp;
				</td>
			</td>  
			{/if}
            {if  ($t eq 'YWRfY3VlbnRhc19wb3JfcGFnYXJfb3BlcmFkb3Jh'||$t eq 'YWRfY3VlbnRhc19wb3JfcGFnYXJfZGlzdHJpYnVpZG9yZXM=') and $v eq 0}
					<td >
							<div class="upload" style="width: 120px; height: 25px; overflow: hidden; background:#555; position:relative; border-radius:6px">
									<div id="textoFile" style="position:absolute; top:25%; left:20%; color:#FFF; font-weight:normal !important; font-size:12px;">
											Cargar XML &raquo;
									</div>
									<input type="file" name="leeXML"  id="leeXML" value="Agregar Datos XML &raquo;" class="botonSecundario" style="display: block !important; width: 120px !important; height: 25px !important; opacity: 0 !important; overflow: hidden !important;" onchange="if(!this.value.length) return false; agregaXML(this);"/> 
							</div>
					</td>
            {/if}
            {if  ($t eq 'cmFjX3BlZGlkb3M=') and $v eq 1}
				{if $atributos[68][15] eq 0}
					<td >
						<input type="button" name="enviarxmail"  id = "enviarEmailb" value=" Enviar al cliente &raquo;" onclick="enviarEmailSolCot({$k});" class="botonSecundario"/> &nbsp;&nbsp;&nbsp;
					</td>
				{/if}
				
					<td >
						<input type="button" name=""  id = "" value=" Cliente cancela &raquo;" onclick="cancelarSolCot(4);" class="botonSecundario"/> &nbsp;&nbsp;&nbsp;
					</td>
					
					<td >
						<input type="button" name=""  id = "" value=" Vendedor cancela &raquo;" onclick="cancelarSolCot(5);" class="botonSecundario"/> &nbsp;&nbsp;&nbsp;
					</td>
				
				{if $atributos[68][15] eq 1}
					<td >
						<input type="button" name=""  id = "" value=" Replicar solicitud &raquo;" onclick="replicarSolCot();" class="botonSecundario"/> &nbsp;&nbsp;&nbsp;
					</td>
				{/if}
					
				{if $atributos[68][15] eq 1 and $atributos[6][15] eq 7}	
					<td >
						<input type="button" name=""  id = "" value=" Generar Cotización &raquo;" onclick="generarCot({$k});" class="botonSecundario"/> &nbsp;&nbsp;&nbsp;
					</td>
				{/if}
			{/if}
			   
		
            
        {/if}  
        
    
        {if ($t eq 'cmFjX2NvdGl6YWNpb25lcw==') and $v eq 1}
				<td >
					<input type="button" name=""  id = "" value=" Cliente cancela &raquo;" onclick="cancelarCot(4);" class="botonSecundario"/> &nbsp;&nbsp;&nbsp;
				</td>
				
				<td >
					<input type="button" name=""  id = "" value=" Vendedor cancela &raquo;" onclick="cancelarCot(5);" class="botonSecundario"/> &nbsp;&nbsp;&nbsp;
				</td>
				
				<td >
					<input type="button" name=""  id = "" value=" Dirección cancela &raquo;" onclick="cancelarCot(6);" class="botonSecundario"/> &nbsp;&nbsp;&nbsp;
				</td>
		{/if}
		
		
		{if $t eq 'cmFjX2NvdGl6YWNpb25lcw==' and $atributos[9][15] eq 1}	
				<td >
					<input type="button" name=""  id = "" value=" Aprobación Cliente &raquo;" onclick="actEstatusCot({$k}, 2);" class="botonSecundario"/> &nbsp;&nbsp;&nbsp;
				</td>
		{/if}
		
		{if $t eq 'cmFjX2NvdGl6YWNpb25lcw==' and $atributos[9][15] eq 2}	
				<td >
					<input type="button" name=""  id = "" value=" Aprobación Cobranza &raquo;" onclick="actEstatusCot({$k}, 14);" class="botonSecundario"/> &nbsp;&nbsp;&nbsp;
				</td>
		{/if}
		
		{if $t eq 'cmFjX2NvdGl6YWNpb25lcw==' and $atributos[9][15] eq 14}	
				<td >
					<input type="button" name=""  id = "" value=" Generar Orden de Sevicio &raquo;" onclick="generarOrdenServicio({$k});" class="botonSecundario"/> &nbsp;&nbsp;&nbsp;
				</td>
		{/if}
        {if $t eq 'YWRfcGVkaWRvcw==' and ($op eq 1 or $op eq 2 && $v eq 0)}
				<td >
					<input type="button" name="descuento"  id = "descuento" value="%" onclick="solicitaDescuento();" class="botonSecundario descuentoFancy"/>
				</td>
		{/if}
		{if $t eq 'YWRfcGVkaWRvcw==' and $v eq 1}
				
					<td >
						&nbsp;&nbsp;&nbsp;<input type="button" name="imprimirPedido"  id="imprimirPedido" value=" Imprimir &raquo;" onclick="imprimePedido({$k});" class="botonSecundario"/> 
					</td>
					
					<!--<td >
						&nbsp;&nbsp;&nbsp;<input type="button" name="ImpClausula"  id="ImpClausula" value=" Clausulas &raquo;" onclick="imprimeClausula({$k});" class="botonSecundario"/> 
					</td>-->
		{/if}
		{if  ($t eq 'bmFfYml0YWNvcmFfcnV0YXM=') and $v eq 1}
				
					<td >
						&nbsp;&nbsp;&nbsp;<input type="button" name="imprimirBitacora"  id="imprimirBitacora" value="Imprimir Bit&aacute;cora &raquo;" onclick="imprimeBitacora({$k});" class="botonSecundario"/> 
					</td>
		{/if}
		{if $t eq 'bmFfdmFsZXNfcHJvZHVjdG9z' and $v eq 1}
				
					<td >
						&nbsp;&nbsp;&nbsp;<input type="button" name="imprimirVale"  id="imprimirVale" value="Imprimir &raquo;" onclick="imprimeVale({$k});" class="botonSecundario"/> 
					</td>
		{/if}
    
        
        	<!--TABLA: {$t} -->         
		{if $t eq '2' and $ipe neq 0}
			<td>
				<input type="button" value="Imprimir &raquo;" class="botonSecundario" onClick="imprime('{$t}');">&nbsp;&nbsp;&nbsp;
			</td>
		{/if}   
		{if $t eq 'cmFjX2FydGljdWxvcw=='}
				<td style="padding-left : 15px;">
  	    			<input type="button" name="ant" value="&laquo; Anterior" onclick="navegaRegistros('{$t}', '{$k}', '{$v}', 'a')" class="botonSecundario"/> &nbsp;&nbsp;&nbsp;
				</td>
				<td style="padding-left : 10px;">
  	    			<input type="button" name="sig" value=" Siguiente &raquo;" onclick="navegaRegistros('{$t}', '{$k}', '{$v}', 's')" class="botonSecundario"/> &nbsp;&nbsp;&nbsp;
				</td>
		{/if}
		
	</tr>
</table>

{if $mensaje_err neq ''}
	<table>
    	<tr>
        	<td>
            	<br />
            	<font face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000" size="2">
                	<b>{$mensaje_err}</b>
                </font>
            </td>
        </tr>
    </table>
{/if}
{/if}
<!--------------------------- --------------------------------------------------------------------->
</form>


</div>
{* toda esta parte de scrips se integrara a un archivo js generar propio de los catalagos
y alli se manejaran las excepciones
*} 

{literal}


<script type="text/javascript" language="javascript">
$(document).ready(function() {
	
		if($('#t').val() == 'bmFfY2xpZW50ZXNfZGlyZWNjaW9uZXNfZW50cmVnYQ==' && $('#op').val() == 1){
				obtenPlano();
				}
		if($('#t').val() == 'bmFfY2xpZW50ZXNfZGlyZWNjaW9uZXNfZW50cmVnYQ==' && $('#op').val() == 2 && $('#v').val() == 0){
				var opcion2 = $("#id_plano").find("option:selected").val();
				obtenPlano();
				$("#id_plano option[value='" + opcion2 + "']").prop("selected", "selected");
			
				}
		
		if($('#t').val() == 'YWRfY3VlbnRhc19wb3JfcGFnYXJfb3BlcmFkb3Jh' && $('#op').val() == 1){
				var href = $(location).attr('href');
				var tipo = href.split("hf=");
				//alert(tipo[1]);
				if(tipo[1] == 10){
						$("#divgrid_detallePagosCuentasPorPagar").hide();
						$("#divgrid_detallePagosEgresosCuentasPorPagar").hide();
						}
				else{
						$("#divgrid_detallePagosCuentasPorPagar").show();
						$("#divgrid_detallePagosEgresosCuentasPorPagar").show();
						}
				ocultaMultisucursal();
				}
				
		/*****CUENTAS POR PAGAR******/
		if($('#t').val() == 'YWRfY3VlbnRhc19wb3JfcGFnYXJfb3BlcmFkb3Jh'){
				var href = $(location).attr('href');
				var patt = /idCosteo=/;
				var result = patt.test(href);
				if(result === true){
						$("#header_bg").css("display", "none");
						$("input[name='listado']").css("display", "none");
						}
						
				}
		/***Ordenes de compra ******/
		if($('#t').val() == 'YWRfb3JkZW5lc19jb21wcmFfcHJvZHVjdG9z'){
				agenteAduanalOC();
				}
		
		if($('#t').val() == 'YWRfY3VlbnRhc19wb3JfcGFnYXJfb3BlcmFkb3Jh' && $('#op').val() == 2){
				if($('#v').val() == 1){
						var contador = 1;
						$('table.cuerpo-sucursales tr').each(function(index){
								$("#monto_cxp" + contador).attr("disabled", "disabled");
								$("#porcentaje_cxp" + contador).attr("disabled", "disabled");
								contador++;
						});
						}
				var cxp = $('#id_cuenta_por_pagar').val();
				var envio_datos = 'cxp=' + cxp;
				var urlAjax = "obtenMultisucursales.php";
				var camposSuc = ajaxN(urlAjax, envio_datos);
				
				var registrosSuc = camposSuc.split(",");
				var numRegistros = registrosSuc.length;
				
				for(var i=0; i<numRegistros; i++){
						var datosSuc = registrosSuc[i].split("|");
						var contador = 1;
						$('table.cuerpo-sucursales tr').each(function(index){
								var sucursal = $("#idSucCXP" + contador).val();
								if(sucursal == datosSuc[0]){
										$("#monto_cxp" + contador).val(datosSuc[1]);
										$("#porcentaje_cxp" + contador).val(datosSuc[2]);
										}
								contador++;
						});
						}
				sumaPorcentajeCXP();
				sumaCantidadesCXP();
				ocultaMultisucursal()
				}
		
		});
		
		function ocultaMultisucursal(){
				if($("#v").val() == 1)
						var opcion = $("#id_tipo_sucursal_cxp").val();
				else
						var opcion = $("#id_tipo_sucursal_cxp").find("option:selected").val();
				
				if(opcion == 2){
						$(".total_cxp").show();
						$("#scroll-tabla").show();
						$(".sucursales_cxp").show();
						}
				else{
						$(".total_cxp").hide();
						$("#scroll-tabla").hide();
						$(".sucursales_cxp").hide();
						}
				}
		
		function obtenRuta(){
				var selectHijo = "id_ruta";
				var opcion = $("#id_ciudad").find("option:selected").val();
				var urlAjax = "obtenPlanoRuta.php";
				var envio_datos = 'id=' + opcion + '&caso=1';  // Se arma la variable de datos que procesara el php
				ajaxCombos(urlAjax, envio_datos, selectHijo);
				obtenPlano();
				}
				
		function obtenPlano(){
				var selectHijo = "id_plano";
				var opcion = $("#id_ruta").find("option:selected").val();
				var urlAjax = "obtenPlanoRuta.php";
				var envio_datos = 'id=' + opcion + '&caso=2';  // Se arma la variable de datos que procesara el php
				ajaxCombos(urlAjax, envio_datos, selectHijo);
				}
				
		function ajaxCombos(url, datos, hijo){
				$.ajax({
						async:false,
						type: "POST",
						dataType: "html",
						contentType: "application/x-www-form-urlencoded",
						data: datos,
						url:'../ajax/' + url,
						/*beforeSend:function(){
						},*/
						success: function(data) {
								$("#" + hijo + " option").remove();
								$("#" + hijo).append(data);
								},
						timeout:50000
						});
				}

//validar numeros

function validarNumero(e,id){
     tecla_codigo = (document.all) ? e.keyCode : e.which;
	 if((tecla_codigo>=47 && tecla_codigo <=58)|| (tecla_codigo==45)||(tecla_codigo==46)||(tecla_codigo==8)){
	    
	 }else{
	   e.preventDefault();
	 }
 }

//validar texto

function validarTexto(event,id){
    tecla_codigo = (document.all) ? event.keyCode : event.which;
	if((tecla_codigo>=65)&&(tecla_codigo<=90) ||(tecla_codigo >= 97) && (event.keyCode <= 122)||(tecla_codigo>47 && tecla_codigo <58)||(tecla_codigo==44)||(tecla_codigo==45)||(tecla_codigo==46)||(tecla_codigo==160)||(tecla_codigo==162)||(tecla_codigo==163)||(tecla_codigo==130)||(tecla_codigo==161)||(tecla_codigo==64)||(tecla_codigo==95)||(tecla_codigo==32)||(tecla_codigo==8) || (tecla_codigo==0) || (tecla_codigo==38) || (tecla_codigo==16)){
	 }else{
	    event.preventDefault();
	}
 }
 
 
 
 
 
 
 /*function validarTexto(e,id){
        //alert("zsdcfzxvkxv");
       key = e.keyCode || e.which;
       tecla = String.fromCharCode(key).toLowerCase();
       letras = " áéíóúabcdefghijklmnñopqrstuvwxyz";
       especiales = [8,37,39,46];

       tecla_especial = false
       for(var i in especiales){
            if(key == especiales[i]){
                tecla_especial = true;
                break;
            }
        }

        if(letras.indexOf(tecla)==-1 && !tecla_especial){
            //return false;
			 e.returnValue = false;
        }
    }
 */
 

//objform forma
//make accion a realizar
//filtro de la pantalla a considerar

/*if(document.forma_datos.t.value=="c3lzX3VzdWFyaW9z"){
  if(document.forma_datos.make.value == 'insertar'){

     document.forma_datos.email.value = '';
	 document.forma_datos.pass.value = '';
	  
  }
}*/



{/literal}

{if $EvitaRefresh eq 'SI'}
{if ($t eq 'YWRfZmFjdHVyYXNfYXVkaWNlbA==' or $t eq 'YWRfZmFjdHVyYXM=') and $erroresFac !=''}
	alert("{$erroresFac}");
	location.href="encabezados.php?t={$t}&k=&op=1&tcr=&stm=0";
{else}
	alert('Se ha insertado el dato correctamente');
	location.href="encabezados.php?t={$t}&k={$atributos[0][15]}&op=2&v=1&tabla=&cadP1=MDI0ZG5CbGZqRjhibkJsZmpGOGJYQmxmakY4MQ==&cadP2=MDI0WlhCbGZqRjhhWEJsZmpGOFozQmxmakU9MQ==&stm={$stm}";
{/if}
{/if}	

{if $EvitaRefreshEdicion eq 'SI'} 
	alert('Se ha guardado el dato correctamente');
		
	if("{$t}" != "c3BhX2NsaWVudGVz"){literal}{
		location.href="encabezados.php?t={/literal}{$t}&k={$atributos[0][15]}{literal}&op=2&v=1&tabla=&cadP1=MDI0ZG5CbGZqRjhibkJsZmpGOGJYQmxmakY4MQ==&cadP2=MDI0WlhCbGZqRjhhWEJsZmpGOFozQmxmakU9MQ==&stm={$stm}";
	}else{
		location.href = "../especiales/valoracionMedica.php?accion=0&soloVer=0&idCliente={/literal}{$atributos[0][15]}{literal}";
	}{/literal}
	
	location.href="encabezados.php?t={$t}&k={$atributos[0][15]}&op=2&v=1&tabla=&cadP1=MDI0ZG5CbGZqRjhibkJsZmpGOGJYQmxmakY4MQ==&cadP2=MDI0WlhCbGZqRjhhWEJsZmpGOFozQmxmakU9MQ==&stm={$stm}";
{/if}	


{literal}

function valida(objform,make)
{
	{/literal}	
		{if $t eq 'Y2xfY2FqYXNfY29taXNpb25lcw=='}
			var fechaI=$("#fecha_inicio").val();
			var fechaF=$("#fecha_fin").val();
			var idCaja=$("#id_caja_comision").val();
			{literal}
				if(fechaI!=''||fechaF!=''){
					var resultado=ValidaFechaCajasComision(fechaI,fechaF,idCaja);
					if(resultado==false)
						return false;
				}
			{/literal}
		{/if}
   	{literal}
	var obj=document.getElementById('waitingplease');
	if(document.forma_datos.modificar)
	{
		document.forma_datos.modificar.disabled=true;		
		obj.style.display="block";		
	}	
	
	if(validaR(objform,make) == false)	
	{
		if(document.forma_datos.modificar)
		{
			document.forma_datos.modificar.disabled=false;		
			obj.style.display="none";
		}	
	}
}

{/literal}

{if $t eq 'bmFfc3VidGlwb3NfbW92aW1pZW50b3M='}
	ocultaCamposSubtiposMovimiento();
{/if}
<!--Cambios de vendedor -->
{if $t eq 'c3lzX3VzdWFyaW9z'}

	muestraVendedores();
{/if}


//function imprimeDoc(val, tabla)
{literal}
  function imprimeDoc()
	{
		{/literal}
		{if $k neq ''}
			var doc={$k};
			{if $t eq 'cmFjX3BlZGlkb3M='}				
				window.open('../../code/pdf/imprimeDoc.php?tipo=PEDIDO&impDoc=1&doc='+doc, "Doc", "width=800, height=600");		
			{/if}
			{if $t eq 'cmFjX2NvdGl6YWNpb25lcw=='}
				window.open('../../code/pdf/imprimeDoc.php?tipo=COTIZACION&impDoc=1&doc='+doc, "Doc", "width=800, height=600");		
			{/if}
			{if $t eq 'cmFjX29yZGVuZXNfc2VydmljaW8='}
				window.open('../../code/pdf/imprimeDoc.php?tipo=ORDEN&impDoc=1&doc='+doc, "Doc", "width=800, height=600");		
			{/if}
		{/if}
		{literal}			
	}
	
  function ocultarGrid(grid)
   {
        var c=document.getElementById(grid).style.display;
		
		if(document.getElementById(grid).style.display=='block')
		{
			document.getElementById(grid).style.display='none';
			{/literal}
			$("#btnOcultarFila_" + grid).attr("src", "{$rooturl}imagenes/general/btn_ver-grid.png");
			console.log("#btnOcultarFila_" + grid);
			{literal}
		}
		else
		{
			{/literal}
			document.getElementById(grid).style.display='block';
			$("#btnOcultarFila_" + grid).attr("src", "{$rooturl}imagenes/general/btn_ocultar-grid.png");
			console.log("#btnOcultarFila_" + grid);
			{literal}
		}
   }
   function ajaxN(url, datos){					
			var entrega;
			$.ajax({
					async:false,
					type: "POST",
					dataType: "html",
					contentType: "application/x-www-form-urlencoded",
					data: datos,
					url:'../ajax/' + url,
					/*beforeSend:function(){
					},*/
					success: function(data) {
							entrega = data;								
						
					},
					timeout:50000
					});
			return entrega;
			}
   
   /**************CUENTAS POR PAGAR*********************/
function ocultaGridCXP(){
		if($("#v").val() == 1)
				var tipo = $("#id_tipo_cuenta_por_pagar").val();
		else
				var tipo = $("#id_tipo_cuenta_por_pagar").find("option:selected").val();
		
		if(tipo == 2){
				$("#divgrid_detalleProductosCuentasPorPagar").hide();
				vaciaGrid("detalleProductosCuentasPorPagar");
				}
		else{
				$("#divgrid_detalleProductosCuentasPorPagar").show();
				}
		
		}
function ocultaCamposCXP(){
		if($("#v").val() == 1)
				var id_documento = $("#id_tipo_documento_recibido").val();
		else
				var id_documento = $("#id_tipo_documento_recibido").find("option:selected").val();
		
		var urlAjax = "gastoSubgasto.php";
		var envio_datos = 'idDocumento=' + id_documento + '&caso=3';
		var campos = ajaxN(urlAjax, envio_datos);
		var oculta = campos.split("|");
		
		if(oculta[0] == 0){
				$("#fila_catalogo_18").hide();
				$("#iva").val("");
				}
		else{
				$("#fila_catalogo_18").show();
				
				}
				
		if(oculta[1] == 0){
				$("#fila_catalogo_19").hide();
				$("#retencion_iva_documentos").val("");
				}
		else{
				$("#fila_catalogo_19").show();
				
				}
				
		if(oculta[2] == 0){
				$("#fila_catalogo_20").hide();
				$("#retencion_isr_documentos").val("");
				}
		else{
				$("#fila_catalogo_20").show();
				
				}
				
		}


/************Validaciones Cuentas por Pagar*********************/

//Tabla de sucursales 
function sumaPorcentajeCXP(){
		var sumaPorc = 0;
		var contador = 1;

		$('table.cuerpo-sucursales tr').each(function() {
				var montos = $('#porcentaje_cxp' + contador).val();
				montos = montos.replace(",", "");
				montos = montos == "" ? montos = 0 : montos = montos;
				sumaPorc += parseFloat(montos);
				contador++;
				});
		$('#porc_suc_cxp').val(sumaPorc);
		}
		
function sumaCantidadesCXP(){  //En esta función obtendremos el total de la suma de los productos y el porcentaje que representa del total de la cxp
		var sumaSUc = 0;
		var contador = 1;

		$('table.cuerpo-sucursales tr').each(function() {
				var montos = $('#monto_cxp' + contador).val();
				
				montos = montos.replace("$", "");
				montos = montos.replace(",", "");
				montos = montos == "" ? montos = 0 : montos = montos;
				sumaSUc += parseFloat(montos);
				contador++;
				});
		$('#total_suc_cxp').val("$" + formatear_pesos(sumaSUc));
		
		}
function obtenPorcCXP(pos){
		var montos = $('#total').val();
		montos = montos.replace("$", "");
		montos = montos.replace(",", "");
		var porcentaje = $('#porcentaje_cxp' + pos).val();
		var porcCalculado = calculaPorcentajesMonto(montos, porcentaje, 1);
		$('#monto_cxp' + pos).val(formatear_pesos(porcCalculado));
		sumaCantidadesCXP();
		sumaPorcentajeCXP();
		}

function calculaPorcentajeActualCXP(pos){
		var totalCXP = $('#total').val();
		if(totalCXP == "" || totalCXP == "0.00"){
				$('#porcentaje_cxp' + pos).val("0.00");
				sumaCantidadesCXP();
				sumaPorcentajeCXP();
				}
		else{
				var subtotal = $('#monto_cxp' + pos).val();
				subtotal = subtotal == "" ? subtotal = 0 : subtotal = subtotal;
				subtotal = subtotal.replace("$", "");
				subtotal = subtotal.replace(",", "");
				totalCXP = totalCXP.replace("$", "");
				totalCXP = totalCXP.replace(",", "");
		
				var porcActual = (subtotal * 100) / totalCXP;
				$('#porcentaje_cxp' + pos).val(formatear_pesos(porcActual));
				sumaCantidadesCXP();
				sumaPorcentajeCXP();
				}
		}

function obtenPrecioProducto(pos, tabla, producto, precio){ //Funcion que obtiene el precio del campo precio_lista de un producto
		var idProd = $("#" + tabla + "_" + producto + "_" + pos).attr("valor");
		var envio_datos = 'id=' + idProd;
		var urlAjax = "obtenPrecioProducto.php";
		var resultado = ajaxN(urlAjax, envio_datos);
		var valores = resultado.split("|");
		$("#" + tabla + "_" + precio + "_" + pos).attr("valor", valores[0]);
		$("#" + tabla + "_" + precio + "_" + pos).html(valores[1]);
		}
function colocaImporteCXP(tabla, pos, col1, col2 , colImporte){ //Funcion que coloca producto * cantidad en el mismo grid
		var cantidad = $("#" + tabla + "_" + col1 + "_" + pos).attr("valor");
		var precio = $("#" + tabla + "_" + col2 + "_" + pos).attr("valor");
		
		precio = precio == "" ? precio = 0 : precio = precio; 
		cantidad = cantidad == "" ? cantidad = 0 : cantidad = cantidad; 
		
		var total = precio * cantidad;
		
		$("#" + tabla + "_" + colImporte + "_" + pos).attr("valor", total);
		$("#" + tabla + "_" + colImporte + "_" + pos).html("$" + formatear_pesos(total));
		
		if(tabla = "detalleConceptosGastosCuentasPorPagar"){
				colocaIVAOculto(pos);
				
				}
		
		}
		
function colocaIVAOculto(pos){
		var idSubgasto = $("#detalleConceptosGastosCuentasPorPagar_4_" + pos).attr('valor');
		var importe = $("#detalleConceptosGastosCuentasPorPagar_9_" + pos).attr('valor');
		var url = "calculosCXP.php";
		var envia_datos = "idSubgasto=" + idSubgasto;
		var resultado = ajaxN(url, envia_datos);
				
		if(resultado == 1){
				var ivaT = calculaPorcentajesMonto(importe, 16, 1); //Cantidad, porcentaje, valor que devolvera: 1--Monto del porcentaje 2--Porcentaje sumado a la cantidad
				}
		else{
				var ivaT = 0;
				}
		$("#detalleConceptosGastosCuentasPorPagar_10_" + pos).attr('valor', ivaT);
		}

function calculaTotalesN(tabla, columna, idDestino){ //Funcion que realiza la sumatoria de una columna de un grid a un campo del encabezado
		var total = 0; 
		$('table#Body_' + tabla + ' tr').each(function(index) { //Recorremos el grid que viene en el parametro tabla
				var sub = $(this).children().filter("[id^=" + tabla + "_" + columna + "_]").attr("valor"); //El parametro columna son los valores de suma del grid
				sub= sub.replace(",", "");
				sub= sub.replace(",", "");
				sub= sub.replace(",", "");
				
				total += parseFloat(sub); //Sumatoria
				if(tabla == "detalleCuentasPorPagarCosteo"){
						var genera = $(this).children().filter("[id^=detalleCuentasPorPagarCosteo_12_]").attr("valor");
						if(genera == 1)
								total -= parseFloat(sub)
						}
				});
		
		$("#" + idDestino).val(formatear_pesos(total)); //Colocamos el resultado en el id del campo del encabezado
		}

function agregaEgresosCXP(){
		var totalEgresos = 0;
		var totalPagos = 0;
		$('table#Body_detallePagosEgresosCuentasPorPagar tr').each(function(index) {
				var subEgresos = $(this).children().filter("[id^=detallePagosEgresosCuentasPorPagar_4_]").attr("valor"); //El parametro columna son los valores de suma del grid
				subEgresos = subEgresos == "" ? subEgresos = 0 : subEgresos = subEgresos;
				subEgresos = subEgresos.toString().replace(",", "");
				totalEgresos += parseFloat(subEgresos);
				});
		$('table#Body_detallePagosCuentasPorPagar tr').each(function(index) {
				var subPagos = $(this).children().filter("[id^=detallePagosCuentasPorPagar_6_]").attr("valor"); //El parametro columna son los valores de suma del grid
				subPagos = subPagos == "" ? subPagos = 0 : subPagos = subPagos; 
				subPagos = subPagos.toString().replace(",", "");
				totalPagos += parseFloat(subPagos);
				});
		var final_pagos = parseFloat(totalPagos) + parseFloat(totalEgresos);
		$("#subtotal").val(formatear_pesos(final_pagos));
		
		/****Calculo del saldo*****/
		var totalSub = $("#total").val();
		totalSub = totalSub.replace(",", "");
		
		//Formateo a cero si no hay valores
		totalSub = totalSub == "" ? totalSub = 0 : totalSub = totalSub; 
		
		var saldo = totalSub - final_pagos;
		$("#saldo").val(formatear_pesos(saldo));
		}

function calculaSubtotalesN(){ //Funcion que realiza los calculos de cuentas por pagar de acuerdo a tipos de documentos
		
		var productos = $("#subtotal_productos").val();
		productos = productos.replace(",", "");
		var gastos = $("#subtotal_gastos").val();
		gastos = gastos.replace(",", "");
		
		//Formateo a cero si no hay valores
		productos = productos == "" ? productos = 0 : productos = productos; 
		gastos = gastos == "" ? gastos = 0 : gastos = gastos; 
		
		//Calculo subtotal
		var subtotal1 = parseFloat(productos) + parseFloat(gastos);
		$("#subtotal_2").val(formatear_pesos(subtotal1));
		
		var subtotal_2 = $("#subtotal_2").val();
		subtotal_2 = subtotal_2.replace(",", "");
		subtotal_2 = subtotal_2 == "" ? subtotal_2 = 0 : subtotal_2 = subtotal_2; 
		
		if($('#iva').is (':visible')){
				if($('#valida_xml').val() == 1){
						var total = $('#iva_xml').val();
						}
				else{
						var total = 0;
						}
				$('table#Body_detalleConceptosGastosCuentasPorPagar tr').each(function(index) {
						var sub = $(this).children().filter("[id^=detalleConceptosGastosCuentasPorPagar_10_]").attr("valor");
						sub = sub == "" ? 0 : sub;
						total = parseFloat(total) + parseFloat(sub);
						});
				
				$("#iva").val(formatear_pesos(total));
				}
		var documentoRet = $("#id_tipo_documento_recibido").find("option:selected").val();
		
		
		if($('#retencion_iva_documentos').is (':visible')){
				var envia_datos = "id=" + documentoRet + "&caso=2";
				var url = "colocaRetenciones.php";
				var respuesta = ajaxN(url, envia_datos);
				var retenciones = respuesta.split("|");
				
				if(retenciones[0] == 1){
						var ret_iva = calculaPorcentajesMonto(subtotal_2, retenciones[1], 1); //Cantidad, porcentaje, valor que devolvera: 1--Monto del porcentaje 2--Porcentaje sumado a la cantidad
						}
				else{
						var ret_iva = calculaPorcentajesMonto(subtotal_2, 0, 1);
						}
				
				$("#retencion_iva_documentos").val(formatear_pesos(ret_iva));
				}
		if($('#retencion_isr_documentos').is (':visible')){
				var envia_datos = "id=" + documentoRet + "&caso=3";
				var url = "colocaRetenciones.php";
				var respuesta = ajaxN(url, envia_datos);
				var retenciones = respuesta.split("|");
				if(retenciones[0] == 1){
						var isrSub = calculaPorcentajesMonto(subtotal_2, retenciones[1], 1); //Cantidad, porcentaje, valor que devolvera: 1--Monto del porcentaje 2--Porcentaje sumado a la cantidad
						}
				else{
						var isrSub = calculaPorcentajesMonto(subtotal_2, 0, 1);
						}
				
				$("#retencion_isr_documentos").val(formatear_pesos(isrSub));
				}

		/****Obtenemos los valores de las variables para el calculo del total*****/
		//Variables que dependen del tipo de documento
		var iva = $("#iva").val();
		iva = iva.replace(",", "");
		var retencion_iva = $("#retencion_iva_documentos").val();
		retencion_iva = retencion_iva.replace(",", "");
		var retencion_isr = $("#retencion_isr_documentos").val();
		retencion_isr = retencion_isr.replace(",", "");

		//Formateo a cero si no hay valores
		iva = iva == "" ? iva = 0 : iva = iva; 
		retencion_iva = retencion_iva == "" ? retencion_iva = 0 : retencion_iva = retencion_iva; 
		retencion_isr = retencion_isr == "" ? retencion_isr = 0 : retencion_isr = retencion_isr; 		
				
		var subRet = parseFloat(subtotal_2) + parseFloat(iva);
		var sumaRetenciones = parseFloat(retencion_iva) + parseFloat(retencion_isr);
		var total = subRet - sumaRetenciones;
		$("#total").val(formatear_pesos(total));
		
		//Se agrega a los calculo el total de EGRESOS
		var totalEgresos = 0;
		$('table#Body_detallePagosEgresosCuentasPorPagar tr').each(function(index) {
				var subEgresos = $(this).children().filter("[id^=detallePagosEgresosCuentasPorPagar_4_]").attr("valor"); //El parametro columna son los valores de suma del grid
				totalEgresos += parseFloat(subEgresos);
				});
		var pendiente = $("#subtotal").val();
		pendiente = pendiente.replace(",", "");
		pendiente = pendiente == "" ? pendiente = 0 : pendiente = pendiente;
		var pagos_final = parseFloat(pendiente) + parseFloat(totalEgresos);
		$("#subtotal").val(formatear_pesos(pagos_final));
		
		/****Calculo del saldo*****/
		var totalSub = $("#total").val();
		totalSub = totalSub.replace(",", "");
		var pagos = $("#subtotal").val();
		pagos = pagos.replace(",", "");
		
		//Formateo a cero si no hay valores
		totalSub = totalSub == "" ? totalSub = 0 : totalSub = totalSub; 
		pagos = pagos == "" ? pagos = 0 : pagos = pagos; 
		var saldo = totalSub - pagos;
		$("#saldo").val(formatear_pesos(saldo));
		tipoCalculoCXP(2);
		
		}
function calculaIvaManual(){
		var subtotal_2 = $("#subtotal_2").val();
		subtotal_2 = subtotal_2.replace(",", "");
		subtotal_2 = subtotal_2 == "" ? subtotal_2 = 0 : subtotal_2 = subtotal_2; 
		
		var iva = $("#iva").val();
		iva = iva.replace(",", "");
		iva = iva == "" ? iva = 0 : iva = iva; 
		
		var subRet = parseFloat(subtotal_2) + parseFloat(iva);
	
		$("#total").val(formatear_pesos(subRet));
		
		/****Calculo del saldo*****/
		var totalSub = $("#total").val();
		totalSub = totalSub.replace(",", "");
		var pagos = $("#subtotal").val();
		pagos = pagos.replace(",", "");
		
		//Formateo a cero si no hay valores
		totalSub = totalSub == "" ? totalSub = 0 : totalSub = totalSub; 
		pagos = pagos == "" ? pagos = 0 : pagos = pagos; 
		
		var saldo = totalSub - pagos;
		$("#saldo").val(formatear_pesos(saldo));
		
		}
		
function calculaSaldoCXP(){
		var totalSub = $("#total").val();
		totalSub = totalSub.replace(",", "");
		var pagos = $("#subtotal").val();
		pagos = pagos.replace(",", "");
		//Formateo a cero si no hay valores
		totalSub = totalSub == "" ? totalSub = 0 : totalSub = totalSub; 
		pagos = pagos == "" ? pagos = 0 : pagos = pagos; 
		
		var saldo = totalSub - pagos;
		$("#saldo").val(formatear_pesos(saldo));
		}
function validacionProductoCXP(pos){
		var producto = $("#detalleProductosCuentasPorPagar_2_" + pos).attr("valor");
		var urlAjax = "obtenPrecioProducto.php"; 
		var envio_datos = 'id=' + producto;
		var precio = ajaxN(urlAjax, envio_datos);
		var muestra = precio.split("|");
		$("#detalleProductosCuentasPorPagar_5_" + pos).attr("valor", muestra[0]);
		$("#detalleProductosCuentasPorPagar_5_" + pos).html(muestra[1]);
		
		}
function calculaPorcentajesMonto(monto, porcentaje, accion){  //Cantidad, porcentaje, valor que devolvera: 1--Monto del porcentaje 2--Porcentaje sumado a la cantidad
		var montoPorcentaje = parseFloat(monto * porcentaje);
		montoPorcentaje = montoPorcentaje / 100;
		var totalSumado = parseFloat(monto) + parseFloat(montoPorcentaje);
		var totalRestado = parseFloat(monto) - parseFloat(montoPorcentaje);
		if(accion == 1)
				return montoPorcentaje;
		else if(accion == 2)
				return totalSumado;
		else if(accion == 3)
				return totalRestado;
		}
function precioProductoGrid(tabla, columna, pos, destino){
		var producto = $("#" + tabla + "_" + columna + "_" + pos).attr("valor");
		var urlAjax = "obtenPrecioProducto.php"; 
		var envio_datos = 'id=' + producto;
		var precio = ajaxN(urlAjax, envio_datos);
		var muestra = precio.split("|");
		$("#" + tabla + "_" + destino + "_" + pos).attr("valor", muestra[0]);
		$("#" + tabla + "_" + destino + "_" + pos).html(muestra[1]);
		
		}
		
function porcentajeGrid(tabla, monto, porc, pos, destino){ //Funcion que obtiene el porcentaje de un campo de un grid y lo arroja en otro campo del mismo grid
		var precio = $("#" + tabla + "_" + monto + "_" + pos).attr("valor");
		var porcentaje = $("#" + tabla + "_" + porc + "_" + pos).attr("valor");
		var montoPorc = calculaPorcentajesMonto(precio, porcentaje, 3);
		$("#" + tabla + "_" + destino + "_" + pos).attr("valor", montoPorc);
		$("#" + tabla + "_" + destino + "_" + pos).html("$" + formatear_pesos(montoPorc));
		}
		
function ivaTotalOrdenesCompra(){
		var monto = $("#subtotal").val();
		monto = monto.replace(",", "");
		monto = monto == "" ? monto = 0 : monto = monto; 
		var porcentaje = calculaPorcentajesMonto(monto, 16, 1);
		 $("#iva").val(formatear_pesos(porcentaje));
		
		var total = parseFloat(monto) + parseFloat(porcentaje);
		$("#total").val(formatear_pesos(total));
		}
		
function ivaTotalOrdenesCompraManual(){
		var monto = $("#subtotal").val();
		monto = monto.replace(",", "");
		monto = monto == "" ? monto = 0 : monto = monto; 
		
		var iva = $("#iva").val();
		iva = iva.replace(",", "");
		iva = iva == "" ? iva = 0 : iva = iva; 
		
		var total = parseFloat(monto) + parseFloat(iva);
		$("#total").val(formatear_pesos(total));
		}
//Esta funcion se coloca en el change del grid detalleConceptosGastosCuentasPorPagar en el campo subgasto
/*function colocaPorcentajeSubgasto(pos){
		var subgasto = $("#detalleConceptosGastosCuentasPorPagar_4_" + pos).attr("valor");
		var envia_datos = "id=" + subgasto + "&caso=1";
		var url = "colocaRetenciones.php";
		var respuesta = ajaxN(url, envia_datos);
		var retenciones = respuesta.split("|");
		 $("#detalleConceptosGastosCuentasPorPagar_10_" + pos).attr("valor", retenciones[0]);
		 $("#detalleConceptosGastosCuentasPorPagar_11_" + pos).attr("valor", retenciones[1]);
		}*/
function validaNivelRetenciones(){
		var retencion = 1;
		}
		
function colocaFechaActual(id_destino){
		var ahora = new Date();
		var mes = ahora.getMonth()+1;
		var dia = ahora.getDate();
		var anio = ahora.getFullYear();
		if(dia.toString().length == 1)
				dia = '0' + dia;
		if(mes.toString().length == 1)
				mes = '0' + mes;
		$("#" + id_destino).val(dia + '/' + mes + '/' + anio);
		}

function colocaProveedores(){
		var selectHijo = "id_proveedor";
		if($("#v").val() == 1)
				var opcion = $("#id_tipo_proveedor").val();
		else	
				var opcion = $("#id_tipo_proveedor").find("option:selected").val();
		var urlAjax = "llenaProveedor.php";
		var envio_datos = 'id=' + opcion + "&caso=1";  // Se arma la variable de datos que procesara el php
		ajaxCombos(urlAjax, envio_datos, selectHijo);
		
		
		
		}
function agenteAduanalOC(){
		if($("#v").val() == 1)
				var opcion = $("#id_tipo_proveedor").val();
		else	
				var opcion = $("#id_tipo_proveedor").find("option:selected").val();
		if(opcion == 2)
				$("#fila_catalogo_9").show();
		else
				$("#fila_catalogo_9").hide();
				
		}
function colocaTipoProveedocxp(){
		var selectHijo = "id_tipo_proveedor";
		if($("#v").val() == 1)
				var opcion = $("#id_tipo_cuenta_por_pagar").val();
		else	
				var opcion = $("#id_tipo_cuenta_por_pagar").find("option:selected").val();
		
		var urlAjax = "llenaProveedor.php";
		var envio_datos = 'id=' + opcion + "&caso=2";  // Se arma la variable de datos que procesara el php
		ajaxCombos(urlAjax, envio_datos, selectHijo);
		colocaProveedores();
		}
		
function tipoCalculoCXP(tipo){
		//$('#tipo_calculos > option[value="' + tipo + '"]').attr('selected', 'selected');
		$('#tipo_calculos').val(tipo);
		}

function imprimeOrdenCompraE(orden){
		window.open('../../code/pdf/imprimeOrdenCompra.php?orden=' + orden, "Orden de Compra", "width=800, height=600");		
		}		
		
/*************************************************************************************/
function SellaYTimbra(tabla)
{
		if(document.getElementById('t').value=='YWRfZmFjdHVyYXM='||document.getElementById('t').value=='YWRfZmFjdHVyYXNfYXVkaWNlbA=='){
			var id=document.getElementById('id_control_factura').value;
			url="../ajax/sellaTimbraFactura.php?tipo_documento=FAC&id_documento="+id+"&tabla="+tabla;
		}
		else if(document.getElementById('t').value=='YWRfbm90YXNfY3JlZGl0bw=='||document.getElementById('t').value=='YWRfbm90YXNfY3JlZGl0b19hdWRpY2Vs'){
			var id=document.getElementById('id_control_nota_credito').value;
			url="../ajax/sellaTimbraFactura.php?tipo_documento=NC&id_documento="+id+"&tabla="+tabla;
		}
		
		var aux=ajaxR(url);
		
		if(aux != '0'  && aux != ' 0'  && aux != '  0' )
		{
			alert(aux);
			return false;
		}
		else
		{
				alert("La factura se sello con exito");
				//http://sysnasser.net/sys_facturacion2/code/general/encabezados.php?t=YWRfZmFjdHVyYXNfYXVkaWNlbA==&k=16&op=2&v=1&tabla=&cadP1=MDI0ZG5CbGZqRjhibkJsZmpGOGJYQmxmakY4MQ==&cadP2=MDI0WlhCbGZqRjhhWEJsZmpGOFozQmxmakU9MQ==&stm=
				
				location.href="encabezados.php?t=YWRfZmFjdHVyYXNfYXVkaWNlbA==&k="+id+"&op=2&v=1&tabla=&cadP1=MDI0ZG5CbGZqRjhibkJsZmpGOGJYQmxmakY4MQ==&cadP2=MDI0WlhCbGZqRjhhWEJsZmpGOFozQmxmakU9MQ==";
				
		}
		
}


function mandarMailFactura(id){
	var resp = ajaxR("../especiales/enviarFacturas.php?fact=" + id);	
	alert(resp);
}

{/literal}
</script>	
<!--la variable $scripts contiene las funciones que se ejecutaran al cargar la pagina-->
{if $t eq 'Y2xfcmFuZ29zX2lyZHM='}
{literal}
	<script language="javascript">
		$("#id_color option").each(function(index,obj){
			$.ajax({
				async:false,
				type: "POST",
				dataType: "html",
				contentType: "application/x-www-form-urlencoded",
				data:'id_color='+obj.value,
				url: '../../code/ajax/obtenColores.php',
				success: function(data) {
					$(obj).css('background-color',data);
				},
				timeout:50000
			});
		});
		function colocaColor(id_color){
			$.ajax({
				async:false,
				type: "POST",
				dataType: "html",
				contentType: "application/x-www-form-urlencoded",
				data:'id_color='+id_color,
				url: '../../code/ajax/obtenColores.php',
				success: function(data) {
					$("#id_color").css('background-color',data);
				},
				timeout:50000
			});
		}
		var campoColor=$("#campo1_id_color").val();
		var selectColor=$("#id_color").val();
		
		if(campoColor!=undefined){
			var campo="#campo1_id_color";
			var datos='id_color='+$("#id_color").val();
			obtenColores(campo,datos);
		}
		if(selectColor!=undefined){
			var campo="#id_color";
			var datos='id_color='+$("#id_color").val();
			obtenColores(campo,datos);
		}
		function obtenColores(campo,datos){
		$.ajax({
				async:false,
				type: "POST",
				dataType: "html",
				contentType: "application/x-www-form-urlencoded",
				data:datos,
				url: '../../code/ajax/obtenColores.php',
				success: function(data) {
					$(campo).css('background-color',data);
				},
				timeout:50000
			});
		}
	</script>
{/literal}
{/if}
{if $t eq 'YWRfcGVkaWRvcw==' and $op eq 1}
<script>
	//obtenerDependencia(document.getElementById("id_sucursal_alta"),'id_almacen_solicita','ad_almacenes');
	obtenPrefijo($("#id_sucursal_alta").val(),'ad_pedidos','id_pedido');
</script>
{/if}
{if $t eq 'YWRfZW50aWRhZGVzX2ZpbmFuY2llcmFz' and ($op eq '2' or $op eq '3')}
<script>
	var id=$("#id_tipo_entidad_financiera").val();
	ExcepcionesEntFin(id,'1');
</script>
{/if}
{if $t eq 'Y2xfcHJvZHVjdG9zX3NlcnZpY2lvcw==' and ($op eq '1' or $op eq '2' or $op eq '3')}
<script>
	var mostrar="";
	{if $op eq '1'}
		if($("#producto_compuesto").is(':checked'))
			mostrar=true;
		else
			mostrar=false;
		mostrar_div('divgrid_datelleProductosServiciosCompuestos',mostrar);
	{else}
		ruta='entidadesFinancieras.php';
		var envio='llave='+'{$k}'+'&caso=3';
		var respuesta = ajaxN(ruta,envio);
		if(respuesta=='SI')
			mostrar=true;
		else
			mostrar=false;
			
		mostrar_div('divgrid_datelleProductosServiciosCompuestos',mostrar);
		
	{/if}
	var id=$("#id_tipo_producto_servicio").val();
	ExcepcionesEntFin(id,'2');
</script>
{/if}
{if $t eq 'Y2xfY29udHJhcmVjaWJvcw=='}
	<script>
		var url = "contrareciboInfo.php";
		var envio = "llave={$k}";
		{literal}
		var result = ajaxN(url,envio);
		var DatosContra = result.split('|');
		if(DatosContra[0] == "entidad"){
			$("#fila_catalogo_2").css('display','none');
		}
		else{
			$("#fila_catalogo_3").css('display','none');
		}
		$("#clave").val(DatosContra[1]);
		$("#di").val(DatosContra[2]);
		$("#tipo_cliente").val(DatosContra[3]);
		{/literal}
	</script>
{/if}
{if $op eq '2' and $v eq '0'}
	<script>
		{if $t eq "YWRfY2xpZW50ZXM="}
			var valClave = $('#clave').val();
		{/if}
	</script>

	{section name='indice' loop=$atributos}
		{if $atributos[indice][3] eq 'COMBO' and $atributos[indice][29] neq ''}
			<script>
				$('#{$atributos[indice][0]} > option[value="{$atributos[indice][15]}"]').attr('selected', 'selected');
				
				{if $t eq "YWRfY2xpZW50ZXM="}
					$("#{$atributos[indice][0]}").change();
				{/if}
				
				var now = new Date().getTime();
				{literal}
					while(new Date().getTime() < now + 2000){ }
				{/literal}
			</script>
		{/if}
		{if $atributos[indice][3] eq 'COMBO'}
			<script>
				//console.log($('#{$atributos[indice][0]}'));
				$('#{$atributos[indice][0]} > option[value="{$atributos[indice][15]}"]').attr('selected', 'selected');
			</script>
		{/if}
	{/section}
	<script>
		{if $t eq "YWRfY2xpZW50ZXM="}
			$('#clave').val(valClave);
		{/if}
	</script>
{/if}
{if $t eq 'YWRfZmFjdHVyYXM='}
<script>
	IDFactura('ad_facturas');
</script>
{/if}
	{include file="general/funciones_catalogos_pie.tpl"}
	{if $hf neq 10}
		{include file="_footer.tpl" aktUser=$username}
	{/if}