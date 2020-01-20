{include file="_header.tpl" pagetitle="$contentheader"}
<script type="text/javascript" src="{$rooturl}js/prepedidos.js"></script>

<style>
		{literal}
		.busca-producto{
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
		select{
				border: 1px solid rgb(219, 225, 235);
				border-radius: 4px;
				color: rgb(90, 90, 90);
				padding: 4px;
				width: 300px;
				}
		.busca-productos th{
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
				padding : 10px 0;
				color : #404651;
				}

		.resultado-agregados td:first-child, .resultado-productos td:first-child{
				border-left : 1px #C4C5C7 solid;
				}
		.resultado-agregados td, .resultado-productos td{
				padding : 5px;
				font-size : 10px;
				border : 1px #C4C5C7 solid;
				border-top : none;
				border-left : none;
				}
		#scroll-tabla{
				width : auto;
				height : 200px;
				overflow : auto;
				}
		.solo-lectura{
				width : 80px;
				font-size : 11px;
				text-align : center; 
				border : none;
				}
		
		{/literal}
</style>
<h1 class="encabezado">Prepedido</h1>

<div id="busquedas">
		<div id="busqueda-producto" style="float:left">
				<p>Buscar por:</p><br>
				<label for="producto">Producto o SKU</label><input type="text" id="producto" name="producto" class="busca-producto"/>
				<br><br>
				<p style="display:block; float:right;"><input type="button" class="boton" value="Buscar Producto &raquo;" onclick="buscarProductos()"/></p>
		</div>
		<div id="listas" style="float:left; padding-left:25px;">
				<p>Aplicar:</p><br>
				<label for="lista-precio">Lista de Precios</label>
				<select name="lista-precio" id="lista-precio" onchange="colocaFormasPago(this, 1);">
						<option value="0">Seleccione Lista de Precio</option>
						{section name="listaPrecios" loop=$lista}
								<option value="{$lista[listaPrecios].0}">{$lista[listaPrecios].1}</option>
						{/section}
				</select>
				<br><br>
				<label for="formas-pago">Formas de Pago</label>
				<select name="formas-pago" id="formas-pago">
						<option value="0">Seleccione Forma de Pago</option>
				</select>
		</div>
</div>
<div style="clear:both"></div>
<div id="productos-encontrados" style="margin:20px 0; width:966px">
		<table class="busca-productos">
		<caption>Productos Encontrados</caption>
				<thead>
						<tr>
								<th style="width:20px;">No</th>
								<th style="width:200px;">Producto o SKU</th>
								<th style="width:75px;">Existencia</th>
								<!--<th style="width:75px;">Existencia Sucursal</th>-->
								<th style="width:100px;">Pendientes por Entregar</th>
								<th style="width:85px;">Disponible</th>
								<th style="width:70px;">Foto</th>
								<th style="width:110px;">Cantidad Solicitada</th>
								<th style="width:70px;">Precio</th>
								<th style="width:70px;">Importe</th>
								<th style="width:52px;">&nbsp;</th>
						</tr>
				</thead>
		</table>
		<div id="scroll-tabla">
				<table border="0" class="resultado-productos"> 
						<tbody>
						</tbody>
				</table>
		
		</div>
		
		
		
</div>

<div id="productos-agregados" style="margin:20px 0; width:966px">
		<table class="busca-productos">
		<caption>Productos Agregados</caption>
				<thead>
						<tr>
								<th style="width:20px;">No</th>
								<th style="width:200px;">Producto o SKU</th>
								<th style="width:75px;">Existencia</th>
								<!--<th style="width:75px;">Existencia Sucursal</th>-->
								<th style="width:100px;">Pendientes por Entregar</th>
								<th style="width:85px;">Disponible</th>
								<th style="width:70px;">Foto</th>
								<th style="width:110px;">Cantidad Solicitada</th>
								<th style="width:70px;">Precio</th>
								<th style="width:70px;">Importe</th>
								<th style="width:52px;">&nbsp;</th>
						</tr>
				</thead>
		</table>
		<div id="scroll-tabla">
				<table border="0" class="resultado-agregados"> 
						<tbody>
						</tbody>
				</table>
		
		</div>
		
		
		
</div><br>
<div id="guardar">
		<table style="width:100%;">
				<tr>
						<td><label for="total">Total:</label><input type="text" id="total" name="total" class="busca-producto" style="width:100px;" readonly/></td>
						<td><label for="cliente">Cliente:</label><input type="text" id="cliente" name="cliente" class="busca-producto"/></td>
						<td><label for="vendedor">Vendedor:</label>
								<select name="vendedor" id="vendedor">
										<option value="0">Seleccion vendedor</option>
										{html_options values=$vendedor_id output=$vendedor_nombre selected=$vendedor}		
								</select>
						<td id="respuestaG">&nbsp;</td>
						<td><input type="button" class="boton" value="Guardar &raquo;" onclick="guardarPrepedido()"/></td>
						
				</tr>
		</table>
</div>


{include file="_footer.tpl"}