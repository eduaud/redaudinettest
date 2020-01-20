{assign var="contador" value="1"}
{section name="Series" loop=$series}
	<div class="div_numero_serie" id="div_numero_serie'+consecutivo+'">
		<span class="texto_numeros_serie" name="TxtValorLista'+consecutivo+'" id="TxtValorLista'+consecutivo+'" align="center">{$series[Series].serie}</span>
	</div>
	<script>
	AgregaSeries('{$series[Series].serie}');
	{literal}
		function AgregaSeries(serie){
			var series=document.getElementById("numeros_serie").value;
			if(series=="")
				series=serie;
			else 
				series=series+','+serie;
				
			document.getElementById("numeros_serie").value=series;
			{/literal}
			document.getElementById("TxtContador").value='{$contador++}';
			{literal}
		}
	{/literal}
	</script>
{/section}
