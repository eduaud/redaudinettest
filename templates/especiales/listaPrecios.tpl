{include file="_header.tpl" pagetitle="$contentheader"}
<script type="text/javascript" src="{$rooturl}js/listaPrecios.js"></script>

<style>
		{literal}
		
		
		.listas td{
				padding : 10px;
				font-size : 15px;
				}
		
		.select-lista{
				padding : 7px 10px;
				font-size : 14px;
				width : 300px;
				border : 1px #DBE1EB solid;
				border-radius : 4px;
				background: #FFFFFF;
				background : -moz-linear-gradient(left, #FFFFFF, #F7F9FA);
				background : -webkit-linear-gradient(left, #FFFFFF, #F7F9FA);
				background : -o-linear-gradient(left, #FFFFFF, #F7F9FA);
				background : -ms-linear-gradient(left, #FFFFFF, #F7F9FA);
				color : #6E6E6E;
				}
		.select-horas{
				width : 127px;
				border : 1px #DBE1EB solid;
				border-radius : 4px;
				color : #5A5A5A;
				padding : 4px;
				}
		.tabla-nueva-lista td{
				padding : 7px;
				font-size : 12px;
				font-weight: bold;
				color : #565656;
				}
		.registro-lista{
				color : #5A5A5A;
				background : #FDFEFE;
				height : 18px;
				border: 1px solid #DBE1EB;
				border-radius : 4px;
				}
		
		#detalle-izquierdo{
				float : left;
				width : 40%;
				}
		
		
		
		.tabla-detalles th:first-child{
				border-right : 1px #8C8A8F solid;
				
				}
		.tabla-detalles th{
				font-weight : bold;
				padding : 7px;
				font-size : 12px;
				
				background-color: #FAFBFF;
				background-image: -o-linear-gradient(bottom, #FAFBFF 0%, #B3B4B5 100%);
				background-image: -moz-linear-gradient(bottom, #FAFBFF 0%, #B3B4B5 100%);
				background-image: -webkit-linear-gradient(bottom, #FAFBFF 0%, #B3B4B5 100%);
				background-image: -ms-linear-gradient(bottom, #FAFBFF 0%, #B3B4B5 100%);
				background-image: linear-gradient(to bottom, #FAFBFF 0%, #B3B4B5 100%);
				}
		.tabla-detalles td:first-child{
				text-align : center;
				font-weight : bold;
				border-left : 1px #8C8A8F solid;
				}
		.tabla-detalles td{
				font-size : 11px;
				padding : 5px;
				
				}
		#filtros{
				margin-top : 20px;
				}
		.sel-mult{
				width : 200px;
				height : 180px;
				font-size : 12px;
				padding : 4px 2px;
				}
		.cuerpo-filtro p{
				padding: 4px 2px;
				font-weight : bold;
				font-size : 13px;
				background : #D6D6D6;
				border : 1px #8C8A8F solid;
				}
		.cuerpo-filtro td{
				padding-right : 15px;
				}
		.cuerpo-filtro select option{
				font-size : 10px;
				padding : 2px;
				}
		.tabla-productos{
				width : 100%;
				}
		.tabla-productos th{
				padding : 5px;
				background-color: #FAFBFF;
				background-image: -o-linear-gradient(bottom, #FAFBFF 0%, #B3B4B5 100%);
				background-image: -moz-linear-gradient(bottom, #FAFBFF 0%, #B3B4B5 100%);
				background-image: -webkit-linear-gradient(bottom, #FAFBFF 0%, #B3B4B5 100%);
				background-image: -ms-linear-gradient(bottom, #FAFBFF 0%, #B3B4B5 100%);
				background-image: linear-gradient(to bottom, #FAFBFF 0%, #B3B4B5 100%);
				font-size : 10px;
				font-weight : bold;
				}
		.tabla-productos td:first-child{
				text-align : center;
				}
		.tabla-productos td{
				font-size : 10px;
				padding : 5px;
				border-bottom : 1px solid #000;
				}
		.cantidades{
				text-align : right;
				}
		.scroll-detalles{
				overflow:auto;
				}
		.scroll-tabla{
				position:relative; 
				margin:0; 
				padding:0; 
				width:100%; 
				height:350px; 
				overflow:auto;
				
				}
		.scroll-tabla tr:first-child td{
				border-top : none;
				}
		.scroll-tabla td{
				border : 1px #D6D6D6 solid;
				}
		.scroll-tabla2 tr:first-child td{
				border-top : none;
				}
		.scroll-tabla2 td{
				text-align : left;
				border : none;
				border-bottom : 1px #7A838A solid;
				}
		#info-porc{
				text-align : right;
				}
		#info-porc table td{
				padding : 5px;
				font-size : 13px;
				font-weight : bold;
				}

		#info-porc input[type="text"]{
				padding : 4px;
				width : 50px;
				font-size : 14px;
				}
		.seleccion-todos{		
				display : block; 
				padding : 5px 0;
				font-weight : bold; 
				font-size : 11px;
				color : #4E5457;
				}
		.texto-calculos{
				width:60px;
				font-size:10px !important;
				color:#4E5457;
				text-align : center;
				}
		.tabla_resultante td{
				border : 1px #8C8A8F solid;
				border-left : none;
				border-top : none;
				}
		.guardaSuc{
				margin-top : 10px;
				margin-left : 30%;
				display : none;
				}
		#respuesta-suc, #respuesta-suc2{
				font-size : 8pt;
				margin-bottom : 5px;
				color : #345540;
				}
		.busca-sku tr{
				display : block;
				margin-top : 10px;
				}
		.busca-sku td{
				padding : 5px;
				}
		{/literal}
</style>
<input type="hidden" id="op" value="0"/>

<h1 class="encabezado">Listas de Precios</h1>
<table>
		<tr>
				<td><input type="button" class="boton" value="Nueva Lista &raquo;" onclick="nuevaLista()"/></td>
				<td style="padding-left:15px;"><input type="button" class="boton" value="Guardar &raquo;" id="boton-registra" onclick="registraLista()"/></td>
				<td style="padding-left:15px;" valign="bottom" id="respuesta"></td>
		</tr>
</table>

<br><br>
<div id="lista_precio">
<table class="listas">
		<tr>
				<td>Listas de Precios:</td>
				<td>
						<select class="select-lista" id="select-lista" name="select-lista" onchange="editarCampos(this)">
								<option value="0">Selecciona una opci&oacute;n</option>
								{section name="listaPrecios" loop=$lista}
										<option value="{$lista[listaPrecios].0}">{$lista[listaPrecios].1}</option>
								{/section}
						</select>
				</td>
		</tr>
</table>
</div>
<div id="form-nueva-lista">
<form id="crea-nueva-lista">
		<table border="0" class="tabla-nueva-lista">
				<tr>
						<td>Nombre:</td><td colspan="3"><input class="registro-lista campo1" style="width:400px;" type="text" id="nombre-lista" name="nombre-lista"/></td>
				</tr>
				<tr>
						<td>Vigencia del:</td><td><input class="registro-lista campo2" type="text" id="inicio_vigencia" name="inicio_vigencia" onfocus="calendario(this);" onkeydown="return noletras(event);"/></td>
						<td>al:</td><td style="text-align:right;"><input class="registro-lista campo3" type="text" id="fin_vigencia" name="fin_vigencia" onfocus="calendario(this);" onkeydown="return noletras(event);"/></td>
				</tr>
				<tr>
						<td>Horario de:</td>
						<td>
								<select name="hora_inicio" id="hora_inicio" class="select-horas campo4">
										
										{section name="arregloHoras" loop=$horas}
												<option value="{$horas[arregloHoras].0}">{$horas[arregloHoras].1}</option>
										{/section}
								</select>
						</td>
						<td>a:</td>
						<td style="text-align:right;">
								<select name="hora_final" id="hora_final" class="select-horas campo5">
										
										{section name="arregloHoras" loop=$horas}
												<option value="{$horas[arregloHoras].0}">{$horas[arregloHoras].1}</option>
										{/section}
								</select>
						</td>
				</tr>
				<tr>
						<td>&iquest;Requiere pago total?</td><td colspan="3" style="text-align:left"><input style="width:auto;" type="checkbox" id="requiere_pago" name="requiere_pago"/></td>
				</tr>
				<tr>
						<td style="text-align:left"><br></td>
						
				</tr>	
		</table>
</form>
</div>
<div id="detalles">
		<div id="detalle-izquierdo">
				<table border="0" class="tabla-detalles">
				<caption id="respuesta-suc2"></caption>
						<thead>
								<tr>
										<th style="width:30px;">No.</th>
										<th colspan="2" style="width:200px;" >Tipos de Pago</th>
										
								</tr>
						</thead>
						<tbody>
						
						<tr>
								<td style="padding:0; border:none" colspan="3">
						
						
									<div class="scroll-detalles" style="height:144px;">
												<table border="0" class="tabla_resultante"> 
												<tbody>
												{assign var="contador" value="1"}
												{section name="metodoPagos" loop=$pagos}
														<tr>
																
																<td style="width:38px;">{$contador}</td>
																<td><input type="checkbox" id="checkPago{$pagos[metodoPagos].0}" name="checkPago[]" value="{$pagos[metodoPagos].0}"/></td>
																<td style="width:180px; text-align:left">{$pagos[metodoPagos].1}</td>
																<div style="display:none">{$contador++}</div>
														</tr>
												{/section}
												</tbody>	
												</table>
										</div>
						
						</td>
						</tr>
						
						</tbody>
				</table>
		</div>
		<div id="detalle-derecho">
				<table border="0" class="tabla-detalles">
				<caption id="respuesta-suc"></caption>
						<thead>
								<tr>
										<th style="width:28px;">No.</th>
										<th colspan="2" style="width:300px;">Tiendas</th>
										
								</tr>
						</thead>
						
						<tbody>
						
						<tr>
								<td style="padding:0; border:none" colspan="3">
						
						
									<div class="scroll-detalles" style="height:144px;">
												<table border="0" class="tabla_resultante"> 
												<tbody>
												{assign var="contador2" value="1"}
												{section name="muestraTienda" loop=$tiendas}
								<tr>
										
										<td style="width:33px;">{$contador2}</td>
										<td><input type="checkbox" id="checkSuc{$tiendas[muestraTienda].0}" name="checkSucursal[]" value="{$tiendas[muestraTienda].0}"/></td>
										<td style="width:280px; text-align:left">{$tiendas[muestraTienda].1}</td>
										<div style="display:none">{$contador2++}</div>
								</tr>
						{/section}
												</tbody>	
												</table>
										</div>
						
						</td>
						</tr>
						
						</tbody>
						
				</table>
				<input type="button" class="boton guardaSuc" value="Guarda Sucursales &raquo;" id="guardaSucP" onclick="sucursalesPublica()"/>
		</div>
</div>
<div style="clear:both"></div>
<div id="filtros">
<p style="font-weight:bold; font-size:12px; margin-bottom:5px;">Seleccionar:</p>
<p style="font-weight:bold; font-size:12px; margin-bottom:15px; color:#79838A;">Todos los Productos&nbsp;&nbsp;&nbsp;<input type="checkbox" onclick="desabilitaSelect(this)"/></p>
<p style="font-weight:bold; font-size:12px; margin-bottom:5px;">O filtrar por:</p>
		<table>
				<tr class="cuerpo-filtro">
						<td>
								<span class="seleccion-todos">Seleccionar todo <input type="checkbox" id="check-familia" onclick="seleccionaTodo(this)"/></span>
								<p>Familia de Producto</p>
								<select name="familia" id="familia" class="sel-mult" multiple="multiple" onChange="llenaCombos(this, 'tipo' , 1)">
										{section name="arregloFamilia" loop=$familia}
												<option value="{$familia[arregloFamilia].0}">{$familia[arregloFamilia].1}</option>
										{/section}
								</select>
						</td>
						<td>
								<span class="seleccion-todos">Seleccionar todo <input type="checkbox" id="check-tipo" onclick="seleccionaTodo(this)"/></span>
								<p>Tipo de Producto</p>
								<select name="tipo" id="tipo" class="sel-mult" multiple="multiple" onChange="llenaCombos(this, 'modelo', 2)"></select>
						</td>
						<td>
								<span class="seleccion-todos">Seleccionar todo <input type="checkbox" id="check-modelo" onclick="seleccionaTodo(this)"/></span>
								<p>Modelo</p>
								<select name="modelo" id="modelo" class="sel-mult" multiple="multiple" onChange="llenaCombos(this, 'marca', 3)"></select>
						</td>
						<td>
								<span class="seleccion-todos">Seleccionar todo <input type="checkbox" id="check-marca" onclick="seleccionaTodo(this)"/></span>
								<p>Marca</p>
								<select name="marca" id="marca" class="sel-mult" multiple="multiple"></select>
						</td>
						<td valign="bottom"><input type="button" class="boton" value="Buscar &raquo;" onclick="buscaProduto()"/></td>
						
				</tr>
				<tr class="cuerpo-filtro">
						<td style="padding-top:10px;">
								<span class="seleccion-todos">Seleccionar todo <input type="checkbox" id="check-proveedor" onclick="seleccionaTodo(this)"/></span>
								<p>Proveedores</p>
								<select name="proveedor-select" id="proveedor-select" class="sel-mult" multiple="multiple" onChange="llenaCombos(this, 'marcaProv' , 5)">
								{section name="arregloProv" loop=$proveedores}
										<option value="{$proveedores[arregloProv].0}">{$proveedores[arregloProv].1}</option>
								{/section}
								</select>
						</td>
						<td style="padding-top:10px;">
								<span class="seleccion-todos">Seleccionar todo <input type="checkbox" id="check-marcaProv" onclick="seleccionaTodo(this)"/></span>
								<p>Marca</p>
								<select name="marcaProv" id="marcaProv" class="sel-mult" multiple="multiple">
										{section name="arregloMarca" loop=$marcas}
												<option value="{$marcas[arregloMarca].0}">{$marcas[arregloMarca].1}</option>
										{/section}
								</select>
						</td>
						<td valign="bottom"><input type="button" class="boton" value="Buscar &raquo;" onclick="buscaProveedor()"/></td>
						<td><input type="hidden" id="buscaProovedor" value="0"/></td>
				</tr>
		</table>
		<table class="busca-sku">
				<tr>
						<td><p style="font-weight:bold; font-size:12px; color:#121314;">Busqueda por Nombre o SKU</p></td>
						<td><input type="text" style="width:229px;" id="buscaListaSKU" name="buscaListaSKU"/></td>
						<td valign="bottom"><input type="button" class="boton" value="Buscar &raquo;" onclick="buscaSkuProd()"/></td>
						<td><div id="carga-productos"></div></td>
						<td><input type="hidden" value="0" id="bandera-sku"/></div></td>
						
				</tr>
		</table>
</div>
<div id="productos" style="margin-top:20px;">
		<div id="info-porc" style="float:left">
				<table>
						<tr>
								<td>Porcentaje a aplicar:</td>
								
								<td>
										<input type="text" name="porcentaje" id="porcentaje" onkeydown="return noletras(event);"/>
								</td>
								<td>
										%
								</td>
						</tr>
				</table>
				
		</div>
		<div style="padding: 5px; width:15%; text-align:right; float:left">
				<input type="button" class="boton" value="Aplicar Porcentaje &raquo;" id="aPorc" onclick="actualizaPorcentaje();"/>
		</div>
		<div style="padding: 5px; width:10%; text-align:right; float:left">
				<input type="button" class="boton" value="Limpiar Grid &raquo;" onclick="verificaOcultar();"/>
		</div>
		<div style="padding: 5px; width:14%; text-align:right; float:left; display:none" id="boton-actualizar" onclick="actualizaGrid();">
				<input type="button" class="boton"  value="Actualizar Lista &raquo;"/>
		</div>
		<div style="padding: 5px; width:14%; text-align:right; float:left;">
				<input type="hidden" value="0" id="bandera-porc"/>
		</div>
		<div style="padding: 5px; width:14%; text-align:right; float:left;">
				<input type="hidden" value="0" id="bandera-busqueda"/>
		</div>
		<div style="padding: 5px; width:5%; text-align:right; float:left" id="carga-actualiza"></div>
		<div style="clear:both"></div>
		<div id="tabla-busqueda">
		<table class="tabla-productos" border="0">
				<thead>
						<tr>
								<th style="width:20px;">No</th>
								<th style="width:123px;">Familia</th>
								<th style="width:103px;">Tipo</th>
								<th style="width:95px;">Modelo</th>
								<th style="width:95px;">Marca</th>
								<th style="width:95px;">SKU</th>
								<th style="width:150px;">Producto</th>
								<th style="width:80px;">Precio P&uacute;blico</th>
								<th style="width:80px;">Descuento o Incremento</th>
								<th style="width:80px;">Precio Final</th>
						</tr>
				</thead>
				<tbody> 
						<tr>
								<td colspan="10" style="margin:0; padding:0;">
										<div class="scroll-tabla">
												<table border="0" class="tabla-productos" id="resultado-productos"> 
														
												</table>
										</div>
								</td>
						</tr>
				</tbody>
		</table>
</div>
</div>
{include file="_footer.tpl"}