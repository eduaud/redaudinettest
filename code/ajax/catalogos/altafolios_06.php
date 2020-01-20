<?php
	php_track_vars;

	extract($_GET);
	extract($_POST);
	
//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../../conect.php");
	include("../../../code/general/funciones.php");

	if($opcion==1)
	{
		/*$strConsulta="SELECT 
							id_fuente, 
							nombre 
					FROM fes_fuentes_datos 
					WHERE activo=1 AND id_fuente IN (
												SELECT DISTINCT(id_fuente) 
												FROM pref_series_fuentes_detalles 
												LEFT JOIN pref_series_fuentes ON pref_series_fuentes_detalles.id_serie_fuente=pref_series_fuentes.id_serie_fuente 
												WHERE pref_series_fuentes_detalles.activo=1 
												AND pref_series_fuentes.activo=1
												AND pref_series_fuentes.id_tipo_documento='".$tipodoc."')";
		$res=mysql_query($strConsulta) or die("Error en:\n$strConsulta\n\nDescripcion:".mysql_error());	
		$num=mysql_num_rows($res);
		echo "exito";
		echo "|".$num;
		for($i=0;$i<$num;$i++)
		{
			echo "|";
			$row=mysql_fetch_row($res);
			echo utf8_encode($row[0]."~".$row[1]);
		}
		mysql_free_result($res);*/
		die("exito|1|0|0");
	}
	else if($opcion==2)
	{
		$strConsulta="SELECT 
							id_serie, 
							nombre 
					FROM anderp_series 
					WHERE activo=1 
					AND id_serie IN (
									SELECT 
										DISTINCT(id_serie) 
									FROM anderp_series_documentos 
									WHERE id_tipo_documento='".$documento."' AND anderp_series_documentos.activo=1)";
		$res=mysql_query($strConsulta) or die("Error en:\n$strConsulta\n\nDescripcion:".mysql_error());	
		$num=mysql_num_rows($res);
		echo "exito";
		echo "|".$num;
		for($i=0;$i<$num;$i++)
		{
			echo "|";
			$row=mysql_fetch_row($res);
			echo utf8_encode($row[0]."~".$row[1]);
		}
		mysql_free_result($res);
	}
	else if($opcion==3)
	{
		/*$strConsulta="SELECT 
							pref_series_fuentes_detalles.id_fuente,
							IF(folio_maximo IS NULL,0,folio_maximo) AS maximo 
					FROM pref_series_fuentes 
					LEFT JOIN pref_series_fuentes_detalles ON pref_series_fuentes.id_serie_fuente=pref_series_fuentes_detalles.id_serie_fuente 
					LEFT JOIN (
								SELECT 
										MAX(folio) AS folio_maximo,
										id_tipo_documento,
										id_serie,
										id_fuente  
								FROM pref_folios_documentos 
								GROUP BY id_tipo_documento,id_serie,id_fuente
								) AS tabla_datos ON tabla_datos.id_tipo_documento=pref_series_fuentes.id_tipo_documento 
						AND tabla_datos.id_serie=pref_series_fuentes.id_serie AND pref_series_fuentes_detalles.id_fuente=tabla_datos.id_fuente 
					WHERE pref_series_fuentes.id_tipo_documento='".$documento."' AND pref_series_fuentes.id_serie='".$serie."'
					 AND pref_series_fuentes.activo=1 AND pref_series_fuentes_detalles.activo=1";*/
					  /*td.id_tipo_documento,*/
//en este caso ejecuto dos queris ya q no me acepta mysql ejecutar un MAX() con otro campoSolo para casa ibarra
	$row = Array();	
  if($documento != '' && $serie != ''){	
           //obtiene tipo de documento
		   $strConsulta1="SELECT DISTINCT ffd.id_tipo_documento 
		                 FROM anderp_folios_documentos ffd
						 JOIN anderp_series s ON ffd.id_serie= s.id_serie AND s.activo=1
					     JOIN anderp_tipos_documentos td ON ffd.id_tipo_documento = td.id_tipo_documento
					     WHERE ffd.id_tipo_documento='".$documento."' AND ffd.id_serie=".$serie;
                
                $res1=mysql_query($strConsulta1) or die("Error en:\n$strConsulta1\n\nDescripcion:".mysql_error());	
                $num1=mysql_num_rows($res1);
                
                for($i=0;$i<$num1;$i++)
		        {
			      $row1=mysql_fetch_row($res1);
			      $arrTipoDoc[0] = utf8_encode($row1[0]);
		        }
		        mysql_free_result($res1);
                
				//echo $strConsulta1."-".$arrTipoDoc[0];
				
                //obtengo max folio
		$strConsulta2="SELECT
					  IF(MAX(folio) IS NULL, 0, MAX(folio))
					  FROM anderp_folios_documentos ffd
					  JOIN anderp_series s ON ffd.id_serie= s.id_serie
					  JOIN anderp_tipos_documentos td ON ffd.id_tipo_documento = td.id_tipo_documento
					  WHERE td.id_tipo_documento='".$documento."'
					  AND s.id_serie='".$serie."'
					  AND s.activo=1";			 
		
		$res2=mysql_query($strConsulta2) or die("Error en:\n$strConsulta2\n\nDescripcion:".mysql_error());	
		$num2=mysql_num_rows($res2);
		for($i=0;$i<$num2;$i++)
		{
			$row2=mysql_fetch_row($res2);
			$row[1] = utf8_encode($row2[0]);
		}
		mysql_free_result($res);
       
   
             //echo "Dos ".$strConsulta2."-".$row[1];   
            
			
	}//fin if variables	
	  echo "exito";
	  echo "|";
	  echo utf8_encode($arrTipoDoc[0]."~".$row[1]);
			
			
		//echo "|".$num;
		
		/*for($i=0;$i<$num;$i++)
		{
			echo "|";
			//$row=mysql_fetch_row($res);
			echo utf8_encode($row[0]."~".$row[1]);
		}
		mysql_free_result($res);*/
                
                
	}
	elseif($opcion==4)
	{
		$strConsulta="SELECT id_control_folio FROM anderp_folios WHERE id_serie='".$idserie."' AND id_tipo_documento='".$tipodocumento."'";
		$res=mysql_query($strConsulta) or die("Error en:\n$strConsulta\n\nDescripcion:".mysql_error());	
		$num=mysql_num_rows($res);
		mysql_free_result($res);
		die("exito|".$num);		
	}
?>