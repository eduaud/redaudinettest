{include file="_header.tpl" pagetitle="$contentheader"}
<script type="text/javascript" src="{$rooturl}js/impresionEtiquetas.js"></script>

<style>
		{literal}
		
		
		.listas td{
				padding : 10px;
				font-size : 15px;
				}
		
		.select-lista, .select-sucursal{
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

<h1 class="encabezado">Impresi&oacuten de Etiquetas</h1>

<div id="lista_precio">
<table class="listas">
		
		<tr>
				<td>Sucursales:</td>
				<td>
						<select class="select-sucursal" id="select-sucursal" name="select-lista" onchange="">
								{section name="muestraTienda" loop=$tiendas}
										<option value="{$tiendas[muestraTienda].0}">{$tiendas[muestraTienda].1}</option>
								{/section}
						</select>
				</td>
		</tr>
		<tr>
				<td>Listas de Precios:</td>
				<td>
						<select class="select-lista" id="select-lista" name="select-lista" onchange="limpia();">
								{section name="muestraLista" loop=$lista}
										<option value="{$lista[muestraLista].0}">{$lista[muestraLista].1}</option>
								{/section}
						</select>
				</td>
		</tr>
</table>
</div>

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
								<select name="modelo" id="modelo" class="sel-mult" multiple="multiple" onChange="llenaCombos(this, 'marca', 3)">
						</td>
						<td>
								<span class="seleccion-todos">Seleccionar todo <input type="checkbox" id="check-marca" onclick="seleccionaTodo(this)"/></span>
								<p>Marca</p>
								<select name="marca" id="marca" class="sel-mult" multiple="multiple">
						</td>
						<td valign="bottom"><input type="button" class="boton" value="Buscar &raquo;" onclick="buscaProduto()"/></td>
				</tr>
		</table>
		<table class="busca-sku">
				<tr>
						<td><p style="font-weight:bold; font-size:12px; color:#121314;">Busqueda por Nombre o SKU</p></td>
						<td><input type="text" style="width:229px;" id="buscaListaSKU" name="buscaListaSKU"/></td>
						<td valign="bottom"><input type="button" class="boton" value="Buscar &raquo;" onclick="buscaSkuProd()"/></td>
				</tr>
		</table>
</div>
<div id="productos" style="margin-top:20px;">
		<div style="padding: 5px; width:15%;">
				<input type="button" class="boton" value="Imprimir &raquo;" onclick="imprimeEtiquetas();"/>
		</div>
		<div id="tabla-busqueda">
		<table class="tabla-productos" border="0">
				<thead>
						<tr>
								
								<th style="width:20px;">No</th>
								<th style="width:22px;"><input type="checkbox" id="sel-todos" onClick="seleccionarCheck(this, 'tabla-productos');"/></th>
								<th style="width:123px;">Familia</th>
								<th style="width:95px;">Tipo</th>
								<th style="width:95px;">Modelo</th>
								<th style="width:95px;">Marca</th>
								<th style="width:95px;">SKU</th>
								<th style="width:150px;">Producto</th>
								<th style="width:80px;">Precio</th>
						</tr>
				</thead>
				<tbody> 
						<tr>
								<td colspan="10" style="margin:0; padding:0;">
										<div class="scroll-tabla">
												<table border="0" class="tabla-productos" id="resultado-productos"> 
														<tbody>
														</tbody>
												</table>
										</div>
								</td>
						</tr>
				</tbody>
		</table>
</div>
</div>
{include file="_footer.tpl"}