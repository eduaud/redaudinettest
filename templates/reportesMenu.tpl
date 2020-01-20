<link rel="stylesheet" type="text/css" href="../../inc/css/gridSW.css">
{include file="_header.tpl" pagetitle="$contentheader"}
<div class="titulo-icono" id="titulo-icono">
	     <div class="titulo" id="titulo">Reporte Mensual SAT</div>
 </div>
<div class="tabla" id="tabla">


<form action="encabezados.php" name="forma_datos" id="forma_datos" method="post" >
<table class="campos">

    
    <tr>
        <td>
    
            <table>
                <tr>
                    <td class="nom_campo">Periodo</td>
                    <td>
                    	<select class="campos_req" name="periodo">
                        	{html_options values=$ids_per output=$nom_per }
                        </select>
                    </td>
                </tr>
                <tr>
                	<td colspan="2" align="right">
                    	<br />
                        <input type="button" value="Reporte Gr&aacute;fico" onclick="window.open('{$rooturl}/code/reportes/procesaReportes.php?idRep=11&periodo='+periodo.value, '', 'width=800, height=600')" class="boton"/>
                    	<input type="button" value="Reporte SAT" onclick="verReporte(periodo.value)" class="boton"/>
                    </td>
                </tr>	
			</table>            
        </td>
	</tr>

</table>	
</form>


</div>
{* toda esta parte de scrips se integrara a un archivo js generar propio de los catalagos
y alli se manejaran las excepciones
*} 

{literal}


<script type="text/javascript" language="javascript">
	
	
	function verReporte(val)
	{
	  // alert(val);
		var aux=val.split(",");		
		//alert("code/reportes/reporteSAT.php?anio="+aux[0]+"&mes="+aux[1]);
		window.open("reporteSAT.php?anio="+aux[0]+"&mes="+aux[1], "", "width=100,height=100")
	}
	
</script>
{/literal}

{include file="_footer.tpl" aktUser=$username}