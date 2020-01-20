<html>
<head>
	<link href="{$rooturl}css/tabswizard.css" rel="stylesheet" type="text/css" />
	<link href="{$rooturl}css/estilos.css" rel="stylesheet" type="text/css" />
		
	<script language="javascript" src="{$rooturl}js/datepicker/jquery-1.9.1.js"></script>
	<script language="javascript" src="{$rooturl}js/funcionesNasser.js"></script>
    <link rel="stylesheet" href="{$rooturl}js/datepicker/jquery-ui-themes-1.10.2/themes/cupertino/jquery-ui.css" />
	<link rel="stylesheet" type="text/css" href="{$rooturl}css/gridSW.css"/>
	
	<script type="text/javascript" src="{$rooturl}/js/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
	<link rel="stylesheet" href="{$rooturl}/js/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
	<script type="text/javascript" src="{$rooturl}/js/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
	<script type="text/javascript" src="{$rooturl}js/calendar.js"></script>
    <script type="text/javascript" src="{$rooturl}js/calendar-es.js"></script>
    <script type="text/javascript" src="{$rooturl}js/calendar-setup.js"></script>
	
	<script language="javascript" src="{$rooturl}js/funciones.js"></script>
    <script language="javascript" src="{$rooturl}js/funcionesNasser.js"></script>
	<script language="javascript" src="{$rooturl}js/pedidos.js"></script>
	<script language="javascript" src="{$rooturl}js/grid/RedCatGrid.js"></script>
	<style>
		{literal}
		.busca-producto{
				padding : 7px !important;
				font-size : 14px;
				width : 250px;
				height : 30px;
				border : 1px #DBE1EB solid;
				border-radius : 4px;
				background: #FFFFFF;
				background : -moz-linear-gradient(left, #FFFFFF, #F7F9FA);
				background : -webkit-linear-gradient(left, #FFFFFF, #F7F9FA);
				background : -o-linear-gradient(left, #FFFFFF, #F7F9FA);
				background : -ms-linear-gradient(left, #FFFFFF, #F7F9FA);
				color : #6E6E6E;
				}
		label{
				font-size : 12px;
				padding-right : 10px;
				color : #808080;
				font-weight : bold;
				}
		#busquedas p{
				font-size:13px; 
				font-weight:bold;
				}
		#busquedas select{
				border: 1px solid rgb(219, 225, 235);
				border-radius: 4px;
				color: rgb(90, 90, 90);
				padding: 4px;
				width: 300px;
				font-size : 12px;
				color : #999999;
				}
		.busca-cxp th{
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
		caption{
				font-size : 13px;
				font-weight : bold;
				text-align : left;
				padding : 2px 0;
				color : #404651;
				}

		.cuerpo-cxp td:first-child{
				border-left : 1px #C4C5C7 solid;
				}
		.cuerpo-cxp td{
				padding : 5px;
				font-size : 10px;
				border : 1px #C4C5C7 solid;
				border-top : none;
				border-left : none;
				}
		#scroll-tabla{
				width : auto;
				height : 117px;
				overflow : auto;
				}
		
		.rechazo{
				font-size : 11px;
				color : #999999;
				}
		
		.fechas_ordenes{
				padding : 5px !important;
				font-size : 12px;
				width : 125px;
				height : 25px;
				border : 1px #DBE1EB solid;
				border-radius : 4px;
				background: #FFFFFF;
				background : -moz-linear-gradient(left, #FFFFFF, #F7F9FA);
				background : -webkit-linear-gradient(left, #FFFFFF, #F7F9FA);
				background : -o-linear-gradient(left, #FFFFFF, #F7F9FA);
				background : -ms-linear-gradient(left, #FFFFFF, #F7F9FA);
				color : #6E6E6E;
				}
		.tabla-busquedas td, .cxp_doc td{
				padding : 5px;
				}
		
		{/literal}
</style>
<script>
{literal}
	function buscaIdDoc(){
				var idCXP = $("#busca_cxp").val();
				var numDoc = $("#num_doc").val();
				var total = $("#total").val();
				if(idCXP == "" && numDoc == "" && total == ""){
						alert("Anota un numero de cuenta por pagar\no numero de documento\no un monto en el total");
						}
				else{
						if($("#sel-todos").is(':checked'))
								$("#sel-todos").prop("checked", "");
								
						$(".cuerpo-cxp tbody tr").remove();
						var ruta = "llenaTablaCXP.php";
						var envio = "idcxp=" + idCXP + "&numDoc=" + numDoc + "&total=" + total;
						var respuesta = ajaxN(ruta, envio);
						$(".cuerpo-cxp tbody").append(respuesta);
						}
				}
	function buscaCXP(){
					var idProv = $("#select-prov").find("option:selected").val();
					var idEmp = $("#select-empleado").find("option:selected").val();
					var fecha_inicio = $("#fechadel").val();
					var fecha_fin = $("#fechaal").val();
					var fechavec = $("#fechavec").val();
					var FechaInicioConv = convierteFechaJava(fecha_inicio);
					var FechaFinConv = convierteFechaJava(fecha_fin);
					if(FechaInicioConv > FechaFinConv){
							alert("La fecha final no puede ser mayor a la fecha inicial");
							}
					else{
							if(idProv != 0 && idEmp == 0){
									var a_nombre = $("#select-prov").find("option:selected").html();
									parent.$("#a_nombre_de").val(a_nombre);
									}
							else if(idProv == 0 && idEmp != 0){
									var a_nombre = $("#select-empleado").find("option:selected").html();
									parent.$("#a_nombre_de").val(a_nombre);
									}
							else if(idProv != 0 && idEmp != 0){
									var a_nombre = $("#select-empleado").find("option:selected").html();
									parent.$("#a_nombre_de").val(a_nombre);
									}
							else{
									parent.$("#a_nombre_de").val("");
									}
							if($("#sel-todos").is(':checked'))
									$("#sel-todos").prop("checked", "");
									
							$(".cuerpo-cxp tbody tr").remove();
					
							var ruta = "llenaTablaCXP.php";
							
							var envio = "idProv=" + idProv + "&fecini=" + fecha_inicio + "&fecfin=" + fecha_fin + "&idEmp=" + idEmp + "&fechavec=" + fechavec;
							var respuesta = ajaxN(ruta, envio);
							$(".cuerpo-cxp tbody").append(respuesta);
							}
					
					}
function anadirCXP(){
		var idProv = $("#idProv").val();
		var idCXPG = $("#idCXPGet").val();
		var duplicadoProv = 0;
		var duplicadoCXP = 0;
		var valorCXP = new Array();
		var cantidades = new Array();
		var cuentaSeleccionada = 0;
		$('table.cuerpo-cxp input[name="idCXPCheck[]"]:checked').each(function() {
				valorCXP.push($(this).val());
				cantidades.push($("#montoCXP" + $(this).val()).val().replace(",", ""));
				if($("#idProvD" + $(this).val()).val() != idProv && idProv != "undefined")
						duplicadoProv += 1;
				if($(this).val() == idCXPG && idCXPG != "undefined"){
						duplicadoCXP += 1;
						cuentaSeleccionada = $(this).val();
						}
				});
		if(valorCXP.length == 0){
				alert("Seleccione alguna cuenta por pagar");
				}
		/*else if(duplicadoProv > 0){
				alert("Debes seleccionar el mismo proveedor previamente seleccionado");
				}*/
		else if(duplicadoCXP > 0){
				alert("La cuenta por pagar " + cuentaSeleccionada + " ya esta seleccionada");
				}
		else{
				parent.$("#campo_cxp").val(valorCXP);
				parent.$("#campo_cantidades_cxp").val(cantidades);
				parent.$.fancybox.close();
				}
				
		}
{/literal}
</script>
		<title>Buscar Cuentas Por Pagar</title>
</head>

<body>
<input type="hidden" id="idProv" name="IdProv" value="{$idProveedor}"/>
<input type="hidden" id="idCXPGet" name="idCXPGet" value="{$idCXPG}"/>
		<h1> B&uacute;squeda de Cuentas por Pagar</h1>
<div id="busquedas">
<p>Buscar por:</p>
		<div id="provedores" style="float:left; padding-left:25px;">
			<table class="tabla-busquedas">
				<tr>
				<td>
						<label for="select-prov">Proveedor</label>
				</td>
				<td colspan="3">
						<select name="select-prov" id="select-prov" onchange="">
								<option value="0">Seleccione Proveedor</option>
								{section name="provDatos" loop=$proveedor}
								<option value="{$proveedor[provDatos].0}">{$proveedor[provDatos].1}</option>
								{/section}
						</select>
				</td>
				</tr>
				<tr>
				<td>
						<label for="select-empleado">Reembolsar a</label>
				</td>
				<td colspan="3">
						<select name="select-empleado" id="select-empleado" onchange="">
								<option value="0">Seleccione Empleado</option>
								{section name="provEmp" loop=$empleado}
								<option value="{$empleado[provEmp].0}">{$empleado[provEmp].1}</option>
								{/section}
						</select>
				</td>
				</tr>
				<tr>
						<td><label for="fechadel">Fecha del&nbsp;&nbsp;&nbsp;</label></td>
						<td><input type="text" id="fechadel" name="fechadel" class="fechas_ordenes" onFocus="calendario(this);"/></td>
						<td style="padding-left : 5px;"><label for="fechaal">Al</label></td>
						<td><input type="text" id="fechaal" name="fechaal" class="fechas_ordenes" onFocus="calendario(this);"/></td>
				</tr>
				<tr>
						<td><label for="fechavec">Fecha de vencimiento</label></td>
						<td><input type="text" id="fechavec" name="fechavec" class="fechas_ordenes" onFocus="calendario(this);"/></td>
				</tr>
				</table>
				<p style="display:block; float:right;"><input type="button" class="boton" value="Buscar &raquo;" onclick="buscaCXP()"/></p>
						
		</div>
		<div id="cuentas_pagar" style="float:left; padding-left:25px;">
				<table class="cxp_doc">
						<tr>
								<td><label for="busca_cxp">Cuenta por Pagar</label></td>
								<td><input type="text" id="busca_cxp" name="busca_cxp" class="busca-producto"/></td>
						</tr>
						<tr>
								<td><label for="num_doc">N&uacute;m. de documento</label></td>
								<td><input type="text" id="num_doc" name="num_doc" class="busca-producto"/></td>
						</tr>
						<tr>
								<td><label for="total">Total</label></td>
								<td><input type="text" id="total" name="total" class="busca-producto"/></td>
						</tr>
						<tr>
								<td colspan="2"><p style="display:block; float:right;"><input type="button" class="boton" value="Buscar &raquo;" onclick="buscaIdDoc();"/></p></td>
						</tr>
				</table>
		</div>
</div>
<div style="clear:both;"></div>
<div style="margin:0px 0; width:100%">
		<table class="busca-cxp">
		<caption>Cuentas por Pagar</caption>
				<thead>
						<tr>
								<th style="width:20px;">No</th>
								<th style="width:22px;"><input type="checkbox" id="sel-todos" onClick="seleccionarCheck(this, 'cuerpo-cxp');"/></th>
								<th style="width:150px;">ID CXP / Documento</th>
								<th style="width:150px;">Tipo de Documento</th>
								<th style="width:250px;">Proveedor/Reembolsar a</th>
								<th style="width:150px;">Monto</th>
								<th style="width:150px;">Pagos</th>
								<th style="width:150px;">Saldo</th>
								<th style="width:150px;">Monto</th>
						</tr>
				</thead>
		</table>
		<div id="scroll-tabla">
				<table border="0" class="cuerpo-cxp"> 
						<tbody>
						</tbody>
				</table>
		</div>
</div>
<p style="display:block; float:right;"><input type="button" class="boton" value="A&ntilde;adir Cuenta por Pagar &raquo;" onclick="anadirCXP();"/></p>
</body>
</html>