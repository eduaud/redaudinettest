<?php

	include("../../inc/conect.php");
	include("../../inc/header.php");
	//header ya trae las funciones
	extract($_GET);
	extract($_POST);
	
	$pagina=$_GET["pagina"];
	
	if($accion=='')
	{
		$accion=$_GET["accion"];
	}
	if($idcat=='')
	{
		$idcat=$_GET["idcat"];
	}
	if($idsubcat=='')
	{
		$idsubcat=$_GET["idsubcat"];
	}
	if($op=='')
	{
		$idsubcat=$_GET["op"];
	}
	if($vweb=='')
	{
		$vweb=$_GET["vweb"];
	}
	//despues de las dos formas no tiene nada
	
	//echo " op->".$op." accion->". $accion." idcat->".$idcat." idsubcat->".$idsubcat." tbuscar->".$tbuscar;
	
	switch($accion){
		case "1": 
			$accion=1;
			//echo $accion."------";
		
			if($op!=4)
				cargaInicial($smarty,$pagina,$accion,$buscar,$idcat,$idsubcat);
			elseif($op==4)
			{
				//guardamos en la badde de datos el nodo de la busquedas
				cargaInicialNodo($smarty,$pagina,$accion,$idnodo,$vweb);
				//echo " ->>".$idnodo;
			}
				
			
		break;
		case "2": 
			$accion=2;
			//dentro del div de listados
			if(($idcat!=0 && $idcat!= '') && ($idsubcat==0 || $idsubcat==''))
				$op=2;
			if($tbuscar!='' || ($idsubcat!=0 && $idsubcat!= '') )
				$op=3;
			
			if($op!=4)
				cargaListado($smarty,$pagina,$accion,$op,$idcat,$idsubcat,$tbuscar);
			else
			{
				
				cargaNodo($smarty,$idnodo,$vweb);
			}
				
		break;
	}
	
	//obten dos ultimos articulos 
	
		
	//obtenemos las dos ultimos ARTICULOS
	$arrGeneral2=obtenIdsNodos($id_cd);
	$id1=0;
	$id2=0;
	
	

	if($arrGeneral2[0][0]!='')
	{
		$id1=$arrGeneral2[0][0];
	}
	if($arrGeneral2[1][0]!='')
	{
		$id2=$arrGeneral2[1][0];
	}
	
	
	$arrImgArticulosLista1=datosNodo($id1,2,$vweb);
		//echo " ->>".$idnodo;
	$smarty->assign("arrImgArticulosLista1", $arrImgArticulosLista1);
	
		//obtenemos las dos ultimos ARTICULOS
	$arrImgArticulosLista2=datosNodo($id2,2,$vweb);
		//echo " ->>".$idnodo;
	$smarty->assign("arrImgArticulosLista1", $arrImgArticulosLista1);
	$smarty->assign("arrImgArticulosLista2", $arrImgArticulosLista2);
	
	$smarty->assign("id1", $id1);
	$smarty->assign("id2", $id2);
	
		
	$smarty->assign("usuario", $_SESSION['nombre']);
	$smarty->assign("accion",$accion);
	$smarty->assign("op",$op);
	$smarty->assign("idnodo",$idnodo);
	
	$smarty->assign("idcd", $idcd);
	$smarty->assign("idcat", $idcat);
	$smarty->assign("idsubcat", $idsubcat);
	
	
	if($op!=4)
	{
		$smarty->assign("tbuscar", $tbuscar);
		$smarty->assign("pagina", $pagina);
		$smarty->display("articulos/articulos.tpl");
	
	}
	else
	{
		$smarty->display("articulos/articulos.tpl");
	}
	
	/*echo encode("root")."<br>";
	echo decode(encode("3|sc=50000"));
	
	die();*/
	//--------------------------------------------------------------
	//-----------F U N C I O N E S   -------------------------------
	//--------------------------------------------------------------
	//--------------------------------------------------------------
	function cargaNodo($smarty,$idnodo,$vweb)
	{
		unset($arrImgArticulos);
		$arrImgArticulos = array();
		$arrImgArticulos=datosNodo($idnodo,0,$vweb);
		//echo " ->>".$idnodo;
		$smarty->assign("arrImgArticulos", $arrImgArticulos);
	}
	
	
	//imagenes de las secciones del index , las obtene
	function cargaInicial($smarty,$pagina,$accion,$buscar,$idcat,$idsubcat)
	{
		//obtenemos todas la categorias y las enviamos al listado
		unset($arrCategoria);
		$arrCategoria = array();
		$arrCategoria= obtenDatosCategoriasArticulos(0,'combo');
		$arrysID = $arrCategoria[0];
		$arrysNombre = $arrCategoria[1];
		
		//print_r($arrCategoria);
		
		$smarty->assign("arrysID", $arrysID);
		$smarty->assign("arrysNombre", $arrysNombre);
		//van todas la variantes
		
		$smarty->assign("arrCategoria", $arrCategoria);
		//echo "   buscar-->>".$buscar;
		//print_r($arrCategoria);
		
		if($buscar==1)
		{
			
			unset($arrsubCategoria);
			$arrsubCategoria = array();
			$arrsubCategoria= obtenDatosSubCategoriasArticulos(0,'combo',$idcat);
			$arrysIDsub = $arrsubCategoria[0];
			$arrysNombresub = $arrsubCategoria[1];
			
			$smarty->assign("arrysIDsub", $arrysIDsub);
			$smarty->assign("arrysNombresub", $arrysNombresub);
			
		}
		
		
	}
	
	function cargaInicialNodo($smarty,$pagina,$accion,$idnodo)
	{
		
	}
	
  	         
	function cargaListado($smarty,$pagina,$accion,$op,$idcat,$idsubcat,$tbuscar)
	{
		$smarty->assign("accion", $accion);
		$smarty->assign("pagina", $pagina);
		
		unset($arrImgArticulos);
		$arrImgArticulos = array();
		//echo " -x";
		if($tbuscar=='')
		{
			
			//si vienen los valores vacios
			if(($idcat==0 || $idcat=='') &&  ($idsubcat==0 || $idsubcat=='') )
			{	
				//echo " -1";
				$arrImgArticulos= obtenDatosCategoriasArticulos($pagina,'img');
			}
			elseif(($idcat!=0 || $idcat!='' )&& ($idsubcat==0 || $idsubcat=='') )
			{
				//echo " -2";
				$arrImgArticulos= obtenDatosSubCategoriasArticulos($pagina,'img',$idcat);
			}
			else
			{
				//echo " -3";
				//vienes de la subcategoria seleccionada y debemos mostrar los articulos
				$arrImgArticulos= obtenDatosArticulos($pagina,'img',$idcat,$idsubcat,$tbuscar);
			}
		}
		else
		{
			//echo "-4";
			$arrImgArticulos= obtenDatosArticulos($pagina,'img',$idcat,$idsubcat,$tbuscar);
		}
		
		$smarty->assign("arrImgArticulos", $arrImgArticulos);
		
	}
	
	function obtenDatosCategoriasArticulos($pagina,$tipo)
	{
		unset($arrGeneral);
	    $arrGeneral = array();
		
		if($tipo=='combo')
		{
			unset($htmltable);
			$htmltable = array();
			unset($ids);
			unset($names);
			$ids = array();
			$names = array();
		}
		
		//solo con articulos activos
		//-----------------------------------------------
		$strWhereArt=" AND web_articulos.activo=1 "; 
	
		$strSQLIn="
			SELECT DISTINCT web_articulos_categorias.id_categoria_articulo 
			FROM web_articulos_subcategorias
			left join web_articulos on web_articulos.id_subcategoria_articulo=web_articulos_subcategorias.id_subcategoria_articulo
			left join web_articulos_categorias on web_articulos_subcategorias.id_categoria_articulo=web_articulos_categorias.id_categoria_articulo
			WHERE web_articulos.fecha_publicacion <= NOW() AND 
			web_articulos_categorias.activo=1 and web_articulos_subcategorias.activo=1
		";
						
	    $strSQLIn=$strSQLIn.$strWhereArt;
    	//-----------------------------------------------
		
		
		$factor=60;
		$inicio=($pagina*$factor)-$factor;
		//$fin=$fin-1;
		
		//es muy importante el orden de las secciones
		$strSQL="
			SELECT id_categoria_articulo,nombre,imagen FROM web_articulos_categorias 
			WHERE id_categoria_articulo in(".$strSQLIn.") AND activo=1 order by orden 
		";
		
		if($tipo=='img')
		{
			$strSQL .= " limit $inicio,$factor ";
		}
		
		//echo $strSQL;
		
		if(!($resource0 = mysql_query($strSQL)))	die("Error en:<br><i>$strSQL</i><br><br>Descripcion:<br><b>".mysql_error());
		while($row0 = mysql_fetch_row($resource0))
		{
			if($tipo=='img')
			{
				//validamos si existe la imagen, si no colocamos la me moms book
				/*--if( !existeImagen( $row0[2]))
				{
					$row0[0]='img_moms.jpg'
				}*/
				
				//echo "-->".$row0[2]."<--<br>";
				if($row0[2] == ''){
					$row0[2] = '../../sin_imagen_categoria.jpg';
				}
				
				$row0[1] = normalAhtml($row0[1]);
				
				//print_r($row0);
				array_push($arrGeneral,$row0);
			}
			else
			{
				$row0[1] = normalAhtml($row0[1]);
				array_push($ids,$row0[0]);
				array_push($names,($row0[1]));	
				
			}
			
		}
		mysql_free_result($resource0);
		
		if($tipo=='combo')
		{
			$arrGeneral[0] = $ids;
			$arrGeneral[1] = $names;
		}
		
		//print_r($arrGeneral);
		
		return  $arrGeneral;
		
	}
	
	
	
	function obtenDatosSubCategoriasArticulos($pagina,$tipo,$idcat)
	{
		unset($arrGeneral);
	    $arrGeneral = array();
		
		if($tipo=='combo')
		{
			unset($htmltable);
			$htmltable = array();
			unset($ids);
			unset($names);
			$ids = array();
			$names = array();
		}
		
		//solo con articulos activos
		//-----------------------------------------------
		$strWhereArt=" AND web_articulos.activo=1 and web_articulos.fecha_publicacion<=NOW()"; 
	
		$strSQLIn="
			SELECT DISTINCT web_articulos_subcategorias.id_subcategoria_articulo 
			FROM web_articulos_subcategorias
			left join web_articulos on web_articulos.id_subcategoria_articulo=web_articulos_subcategorias.id_subcategoria_articulo
			left join web_articulos_categorias on web_articulos_subcategorias.id_categoria_articulo=web_articulos_categorias.id_categoria_articulo
			WHERE web_articulos.fecha_publicacion <= NOW() AND 
			web_articulos_categorias.activo=1 and web_articulos_subcategorias.activo=1
		";
						
	    $strSQLIn=$strSQLIn.$strWhereArt;
    	//-----------------------------------------------
		
		
		$factor=1000;
		$inicio=($pagina*$factor)-$factor;
		//$fin=$fin-1;
		
		//es muy importante el orden de las secciones  //id_subcategoria_articulo, id_categoria_articulo, nombre, orden, imagen, nombre_imagen, activo
		$strSQL="
			SELECT id_subcategoria_articulo,nombre,imagen 
			FROM web_articulos_subcategorias WHERE id_subcategoria_articulo in(".$strSQLIn.") 
			AND id_categoria_articulo= '".$idcat."' AND  activo=1 order by orden 
		";
		
		if($tipo=='img')
		{
			$strSQL .= " limit $inicio,$factor ";
		}
		
		if(!($resource0 = mysql_query($strSQL)))	die("Error en:<br><i>$strSQL</i><br><br>Descripcion:<br><b>".mysql_error());
		while($row0 = mysql_fetch_row($resource0))
		{
			if($tipo=='img')
			{
				//validamos si existe la imagen, si no colocamos la me moms book
				if($row0[2] == ''){
					$row0[2] = '../../sin_imagen_categoria.jpg';
				}
				
				$row0[1] = normalAhtml($row0[1]);
				
				array_push($arrGeneral,$row0);
			}
			else
			{
				$row0[1] = normalAhtml($row0[1]);
				array_push($ids,$row0[0]);
				array_push($names,($row0[1]));					
			}
			
		}
		mysql_free_result($resource0);
		
		if($tipo=='combo')
		{
			$arrGeneral[0] = $ids;
			$arrGeneral[1] = $names;
		}
		
		return  $arrGeneral;
		
	}
	
	//para otros agregar la ciudad
	function obtenDatosArticulos($pagina,$tipo,$idcat,$idsubcat,$tbuscar)
	{
		unset($arrGeneral);
	    $arrGeneral = array();
		
		if($tbuscar!='' )
		{
			$strWhereArt .=" AND (contenido like '%".$tbuscar."%'  or web_articulos_categorias.nombre like '%".$tbuscar."%'  or web_articulos_subcategorias.nombre like '%".$tbuscar."%') ";
		}
		
		if($idcat!='0' && $idcat!='' )
		{
			$strWhereArt .=" AND  web_articulos.id_categoria_articulo = '".$idcat."' ";
		}
		if(($idsubcat!='0' && $idsubcat!='' ) )
		{
			$strWhereArt .=" AND web_articulos.id_subcategoria_articulo = '".$idsubcat."' ";
		}
		
		
		
		//solo con articulos activos
		//-----------------------------------------------
		$strWhereArt .=" AND web_articulos.activo=1 and web_articulos.fecha_publicacion<=NOW() "; 
	
		$strSQL="
			SELECT  id_articulo,titulo_1,imagen_listado,contenido
			FROM web_articulos_subcategorias
			left join web_articulos on web_articulos.id_subcategoria_articulo=web_articulos_subcategorias.id_subcategoria_articulo
			left join web_articulos_categorias on web_articulos_subcategorias.id_categoria_articulo=web_articulos_categorias.id_categoria_articulo
			WHERE web_articulos_categorias.activo=1 and web_articulos_subcategorias.activo=1 and not(id_articulo is null) 
		";
						
	    $strSQL=$strSQL.$strWhereArt;
    	//-----------------------------------------------
		
		$factor=1000;
		$inicio=($pagina*$factor)-$factor;
		//$fin=$fin-1;
		if($tipo=='img')
		{
			$strSQL .= " limit $inicio,$factor ";
		}
		
		if(!($resource0 = mysql_query($strSQL)))	die("Error en:<br><i>$strSQL</i><br><br>Descripcion:<br><b>".mysql_error());
		while($row0 = mysql_fetch_row($resource0))
		{
			if($tipo=='img')
			{
				//validamos si existe la imagen, si no colocamos la me moms book
				/*--if( !existeImagen( $row0[2]))
				{
					$row0[0]='img_moms.jpg'
				}*/
				
				
			   	$row0[1]= ($row0[1]);
			    $row0[3]= formatoCorto(($row0[3]));
				array_push($arrGeneral,$row0);
			}
			
			
		}
		mysql_free_result($resource0);
		
		//print_r($arrGeneral);
		
		return  $arrGeneral;
		
	}

function formatoCorto($strContenido)
{
	
	/*$arrCadenas=array('<h2>','</h2>','<h2>','</h2>','<h3>','</h3>','<p>','</p>','</b>','<span class="pink">','</span>','<b>','</b>','</ol>','<>');
	
	
	for($i=0;$i<count($arrCadenas)-1;$i++)
	{
		$strContenido=str_replace($arrCadenas[$i],'',$strContenido);
		
	}*/
	//$strContenido = strip_tags($strContenido, '<p><a>'
	$strContenido = strip_tags($strContenido);
		
	$strContenido=str_replace('  ',' ',$strContenido);
	
	$strContenido= substr($strContenido,0,90);
	
	return($strContenido);
	
}

/************************************************************************/
function datosNodo($nodo,$opcion,$vweb)
	{
		unset($arrGeneral);
	    $arrGeneral = array();
		
		//solo con articulos activos
		//-----------------------------------------------
		//solo si no esta activo
		if($vweb!=97)
			$strWhereArt =" AND web_articulos.activo=1 "; 
		
		
		
		$strSQL="
			SELECT id_articulo,
			titulo_1,
			web_articulos.id_categoria_articulo,
			web_articulos_categorias.nombre,
			web_articulos.id_subcategoria_articulo,
			web_articulos_subcategorias.nombre,
			IF(imagen_contenido_1 = '0', '', imagen_contenido_1) imagen_contenido_1,
			IF(imagen_contenido_2 = '0', '', imagen_contenido_2) imagen_contenido_2,
			IF(imagen_contenido_3 = '0', '', imagen_contenido_3) imagen_contenido_3,
			imagen_marca,
			contenido,
			subtitulo_1
			FROM web_articulos_subcategorias
			left join web_articulos on web_articulos.id_subcategoria_articulo=web_articulos_subcategorias.id_subcategoria_articulo
			left join web_articulos_categorias on web_articulos_subcategorias.id_categoria_articulo=web_articulos_categorias.id_categoria_articulo
			WHERE web_articulos.fecha_publicacion <= NOW() AND web_articulos_categorias.activo=1 and 
			web_articulos_subcategorias.activo=1 and id_articulo = ".$nodo." 
		";
						
	    
		//$strSQL=$strSQL.$strWhereArt;

		//echo '<br>'.$strSQL.'<br>';
    	//-----------------------------------------------
		
		if(!($resource0 = mysql_query($strSQL)))	die("Error en:<br><i>$strSQL</i><br><br>Descripcion:<br><b>".mysql_error());
		while($row0 = mysql_fetch_row($resource0))
		{
			//validamos si existe la imagen, si no colocamos la me moms book
			/*--if( !existeImagen( $row0[2]))
			{
				$row0[0]='img_moms.jpg'
			}*/
			$row0[1]= normalAhtml($row0[1]); 
			$row0[3]= normalAhtml($row0[3]);
			$row0[5]= normalAhtml($row0[5]);
			
			//para el contenido
			if($opcion==2)
			{
				$row0[10]=normalAhtml(quitaEstilos($row0[10]));	
				$row0[10]= substr($row0[10],0,150);
			}
			else
			{
				//ingrese un div
				$row0[10] = "<div class='p_nodo'>". normalAhtml((colocaEstilos($row0[10])))."</div>";	
				grabaEstadisticas('1','1',$nodo);
			}
			
			array_push($arrGeneral,$row0);
			
		}
		
		//print_r($arrGeneral);
		
		mysql_free_result($resource0);
				
		return  $arrGeneral;
		
	}
	
	
	function quitaEstilos($cadena)
	{
		//cambiamos los h1
		$cadena=strip_tags($cadena);
		
		return($cadena);	
				
	}
	
	function colocaEstilos($cadena)
	{
		//cambiamos los h1
		$cadena=str_replace("<h1>","<h1 class='h1_pink'>",$cadena);
		$cadena=str_replace("<h2>","<h2 class='h2_blue'>",$cadena);
		
		$cadena=str_replace("<li>","<li class='li_pink'>",$cadena);
		$cadena=str_replace("<ul>","<ul class='ul_pink'>",$cadena);

		$cadena=str_replace("<ol>","<ol class='ol_pink'>",$cadena);
		
		return($cadena);	
				
	}
	
	
	function obtenIdsNodos($id_cd)
	{
		unset($arrGeneral);
	    $arrGeneral = array();
		
		$strWhereArt .=" AND web_articulos.activo=1  and web_articulos.fecha_publicacion<=NOW() "; 
		
		$strSQL="
			SELECT id_articulo 
			FROM web_articulos_subcategorias
			left join web_articulos on web_articulos.id_subcategoria_articulo=web_articulos_subcategorias.id_subcategoria_articulo
			left join web_articulos_categorias on web_articulos_subcategorias.id_categoria_articulo=web_articulos_categorias.id_categoria_articulo
			WHERE  web_articulos_categorias.activo=1 and web_articulos_subcategorias.activo=1 
		";
					
		
		$strSQL=$strSQL.$strWhereArt;
		
		$strSQL .= " ORDER BY web_articulos.fecha_publicacion desc,id_articulo limit 2 ";
		if(!($resource0 = mysql_query($strSQL)))	die("Error en:<br><i>$strSQL</i><br><br>Descripcion:<br><b>".mysql_error());
		while($row0 = mysql_fetch_row($resource0))
		{
			array_push($arrGeneral,$row0);
			
		}
		
		mysql_free_result($resource0);
				
		return  $arrGeneral;
		
	}
	
	
?>