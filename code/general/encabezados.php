<?php

date_default_timezone_set('America/Mexico_City');

extract($_GET);
extract($_POST);
/*echo '<pre>';print_r($_GET);echo '</pre>';
echo '<pre>';print_r($_POST);echo '</pre>';*/
//CONECCION Y PERMISOS A LA BASE DE DATOS
include("../../conect.php");
include("funciones.php");
include("../../code/especiales/funcionesNasser.php");
include("../../consultaBase.php");
include("../../code/especiales/funcionesCosteoLote.php");
include("../../code/contabilidad/funcionesContabilidad.php");

//include("../../code/especiales/funcionesRent.php");
//echo base64_encode("anderp_vendedores");
//echo base64_decode("cHJlZl9mb2xpb3M=");

function truncateFloat($number, $digitos)
{
    $raiz = 10;
    $multiplicador = pow ($raiz,$digitos);
    $resultado = ((int)($number * $multiplicador)) / $multiplicador;
    return number_format($resultado, $digitos);
 
}

$tabla = base64_decode($t);

$timeArchivosCxP=time();

//excepcion especial para la asignacion de grupos al 

$strSQLUpda="update sys_usuarios_grupos as aux
	set id_grupo=(SELECT id_grupo FROM sys_usuarios where aux.id_usuario=sys_usuarios.id_usuario)";
mysql_query($strSQLUpda);

if($tabla =='ad_movimientos_almacen')
{
		if (isset($stm))
		{
			$numero_catalogo=$stm;
		}
		else
		{
			$llave = $k;
			if(isset($llave))
			{
				//obtenemos la llave
				$strSQL="SELECT id_subtipo_movimiento FROM ad_movimientos_almacen WHERE id_control_movimiento='".$llave."' ";
		
				$arrRes=valBuscador($strSQL);
				//Si ya no es modificable lanzamos todas la peticiones de ser llenadas
				$_SESSION["USR"]->subtipo_movimiento=$arrRes[0];
				$stm=$arrRes[0];
				$smarty->assign("stm",$stm);
				//die();
			}	
		}
		
		$_SESSION["USR"]->subtipo_movimiento=$stm;
}
elseif($tabla =='ad_clientes')
{
		
	
		if (isset($stm))
		{
			$numero_catalogo=$stm;
		}
		else
		{
			$llave = $k;
			if(isset($llave))
			{
				//obtenemos la llave
				$strSQL="SELECT id_tipo_cliente_proveedor FROM ad_clientes where id_cliente='".$llave."' ";
		
				$arrRes=valBuscador($strSQL);
				//Si ya no es modificable lanzamos todas la peticiones de ser llenadas
				
				//debemos buscar el numero de menu
				
				$strSQLB1="SELECT numero_catalogo  FROM cl_tipos_cliente_proveedor where id_tipo_cliente_proveedor =".$arrRes[0];;
				$resValB1=valBuscador($strSQLB1);
				$numero_catalogo=$resValB1[0];
				
				
				$_SESSION["USR"]->subtipo_movimiento=$numero_catalogo;
				$stm=$numero_catalogo;
				$smarty->assign("stm",$stm);
				//die();
			}	                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             
		}
		
		$_SESSION["USR"]->subtipo_movimiento=$stm;
}

/*
La siguiente linea. contiene el valor del grid que se verá en un módulo si es lanzado desde un FancyBox.
Para el módulo de Costeo de Productos, $verGridFancy tiene dos valores
1 - Muestra todos los GRIDS
2- Muestra solo el grid de Conceptos de Gastos de Cuentas Por Pagar
*/

$verGridFancy = isset($verGridFancy) ? $verGridFancy : "";

//echo base64_encode("of_franquicias");
//Extraccion del nombre del encabezado de la pgina colocar en sis menus la correspondencia
if(isset($numero_catalogo))
	$strConsulta="SELECT nombre FROM sys_menus WHERE tabla_asociada='".$tabla."' and numero_catalogo='".$numero_catalogo."'";
else
	$strConsulta="SELECT nombre FROM sys_menus WHERE tabla_asociada='".$tabla."'";
	
$resource = mysql_query($strConsulta) or die("Error at rowsel $strConsulta::".mysql_error());
$row=mysql_fetch_row($resource);

//excepcion para almacenes

$smarty->assign('nombre_menu',$row[0]);


$strConsulta="SELECT mostrar_mensaje_abandono_catalogo FROM sys_parametros_configuracion WHERE activo='1'";
$respuesta=mysql_query($strConsulta) or die("Error at rowsel $strConsulta::".mysql_error());
$row=mysql_fetch_row($respuesta);
mysql_free_result($respuesta);
$smarty->assign('mensaje_salida',$row[0]);


//echo "ES Admon: $esAdmin<br>";
if($tabla=='na_clientes_direcciones_entrega' )
{
	$esAdmin=1;
}
//echo "v_cli: ".$v_cli."</br>";
$accion_insertar=0;
if($esAdmin==0)
{
		$strWhereNumCat="";
		if($stm!=0)
		{
			$strWhereNumCat = " AND numero_catalogo= $stm";
		}
		
		$strSQL="SELECT id_menu FROM sys_menus where tabla_asociada='".$tabla ."'  ". ES_ADM_SUC ." ".$strWhereNumCat;

		//$strSQL="SELECT id_menu FROM sys_menus where tabla_asociada='".$tabla ."'  ". ES_ADM_SUC ." ";
		
	$resUS = mysql_query($strSQL) or die(mysql_error());
	$resvariableUS=mysql_fetch_assoc($resUS);

	$id_submenu=$resvariableUS['id_menu'];

	mysql_free_result($resUS);

	//obtenemos los grupos del usuario
	$id_grupo=strObtenGruposMenu();

	
	
	if(stristr( ",".$strSubmenusPermitidos.",", ','.$id_submenu.','))
	{
		
		//de que tabla lo obtenemos??
		if($nivelPermiso=='usuario')
		{
			$strSQLPer="SELECT id_menu, nombre,
			(SELECT count(id_secuencia)  FROM `sys_permisos_usuarios`  where id_permiso=2 and id_menu=aux.id_menu and  id_usuario ='".$_SESSION["USR"]->userid."' ) as ver,

			(SELECT count(id_secuencia)  FROM `sys_permisos_usuarios`  where id_permiso=3 and id_menu=aux.id_menu and id_usuario ='".$_SESSION["USR"]->userid."' )  AS nuevo,

			(SELECT count(id_secuencia)  FROM `sys_permisos_usuarios`  where id_permiso=4 and id_menu=aux.id_menu and id_usuario ='".$_SESSION["USR"]->userid."' ) AS modi,
			(SELECT count(id_secuencia)  FROM `sys_permisos_usuarios`  where id_permiso=5 and id_menu=aux.id_menu and id_usuario ='".$_SESSION["USR"]->userid."' ) AS eliminar,

			(SELECT count(id_secuencia)  FROM `sys_permisos_usuarios`  where id_permiso=6 and id_menu=aux.id_menu and  id_usuario ='".$_SESSION["USR"]->userid."' )  as imprimir,
			(SELECT count(id_secuencia)  FROM `sys_permisos_usuarios`  where id_permiso=7 and id_menu=aux.id_menu and  id_usuario ='".$_SESSION["USR"]->userid."' )  as generar
			FROM  sys_menus aux
			WHERE id_menu = '".$id_submenu."'  ". ES_ADM_SUC ." ";

		}
		else
		{
			//obtenemos otro query

			$strSQLPer="SELECT id_menu, nombre,
			(SELECT count(id_secuencia)  FROM `sys_permisos_grupos`  where id_permiso=2 and id_menu=aux.id_menu and  id_grupo in (".$id_grupo.") ) as ver,

			(SELECT count(id_secuencia)  FROM `sys_permisos_grupos`  where id_permiso=3 and id_menu=aux.id_menu and  id_grupo in (".$id_grupo.") )  AS nuevo,

			(SELECT count(id_secuencia)  FROM `sys_permisos_grupos`  where id_permiso=4 and id_menu=aux.id_menu and  id_grupo in (".$id_grupo.") ) AS modi,
			(SELECT count(id_secuencia)  FROM `sys_permisos_grupos`  where id_permiso=5 and id_menu=aux.id_menu and  id_grupo in (".$id_grupo.") ) AS eliminar,

			(SELECT count(id_secuencia)  FROM `sys_permisos_grupos`  where id_permiso=6 and id_menu=aux.id_menu and  id_grupo in (".$id_grupo.") )  as imprimir,
			(SELECT count(id_secuencia)  FROM `sys_permisos_grupos`  where id_permiso=7 and id_menu=aux.id_menu and  id_grupo in (".$id_grupo.") )  as generar
			FROM  sys_menus aux
			WHERE id_menu = '".$id_submenu."'  ". ES_ADM_SUC ." ";

		}
			
		//echo $strSQLPer.' <br>';
		//enviamos las variables
			
		$resPer = mysql_query($strSQLPer) or die(mysql_error());
		$resvariablePer=mysql_fetch_assoc($resPer);

		$ver_per=$resvariablePer['ver'];
		$nuevo_per=$resvariablePer['nuevo'];
		$modi_per=$resvariablePer['modi'];
		$eliminar_per=$resvariablePer['eliminar'];
		$imprimir_per=$resvariablePer['imprimir'];
		$generar_per=$resvariablePer['generar'];
			
		if($nuevo_per>0 || $modi_per>0 || $eliminar_per>0  || $imprimir_per>0 || $generar_per>0)
			$ver_per=1;
			
		mysql_free_result($resPer);


	}

	else
	{
		//si es de autorizacion no sacarlo
			
		if($t != 'cGV1Z19jbGllbnRlc19kaXN0X3RtcA==')
		{
			//si no tiene permisos lo saca

			header("Location: ".$rooturl."index.php?".SID);
			die();
		}
	}

}
else
{
	//permisos para admin
	$ver_per=1;
	$nuevo_per=1;
	$modi_per=1;
	$eliminar_per=1;
	$imprimir_per=1;
	$generar_per=1;

}

	switch($t){
		case '';
			$ver_per=1;
			$nuevo_per=0; 
			$modi_per=1; 
			$eliminar_per=0;   
			$imprimir_per=0;  
			$generar_per=0; 
			break;
	}

$smarty->assign("vpe",$ver_per);
$smarty->assign("npe",$nuevo_per);
$smarty->assign("mpe",$modi_per);
$smarty->assign("epe",$eliminar_per);
$smarty->assign("ipe",$imprimir_per);
$smarty->assign("gpe",$generar_per);

//PARAMETROS QUE LLEGAN DESDE LA FORMA DE LISTADO O DEL CATALOGO
//opcion ((1)) mostrar la forma en blanco y cambia el parametro make a insertar, ((2)) opcion de mostrar el registro para ver modificar o eliminar
$opcion = base64_decode($op);
//echo "op".$op."-".$opcion;
//tabla de la cual obtendra la configuracion y en caso de opcion 2

//indica (((1))) si el registro ser� solo de lectura (((2))) con opcion de cambiar
$ver=$v;
//llave de las tabla
$llave = $k;
$keyCodif=base64_encode($llave);

//variableEspecial
$especialValorProyectoPedidos="0";
//echo base64_encode('novalaser_cierre_dia');
//echo "Llave: [$llave]<br>";
$ejecucion=0;

$strWhereExcepcionesEncabezados="";

//Excepciones Especiales
require('encabezadosInicialExcepciones.php');
//echo 'op '. $op.'<br> ver '. $ver.'<br> make'.$make.'<br>especialValorProyectoPedidos '.$especialValorProyectoPedidos."<br> llave ".	$llave ;
//die();

//*********************************************
//*********************************************
if($make=='actualizar' || $make=='insertar')
{
	//cambiamos las opciones de 1 a 2
	$op=2;
	$ver=1;
}





//leemos los datos de configuracion de la tabla
//cambiar por el valor decodificado
$scripts="";
$strSQL="SELECT
campo/*0*/,
orden/*1*/,
IF(requerido = 1, CONCAT('* ', nombre), nombre)/*2*/,
tipo/*3*/,
tam/*4*/,
requerido/*5*/,
visible/*6*/,
modificable/*7*/,
en_base/*8*/,
sql_combo/*9*/,
combo_where_2/*10*/,
es_llave/*11*/,
compuesta_con/*12*/,
usuarios_bloqueo/*13*/,
grupos_bloqueados/*14*/,
valor/*15*/,
'' as arr/*16*/,
'' as valor2/*17*/,
clase_nombre/*18*/,
clase_control/*19*/,
if(modificable=0,'enabled','') as enabled/*20*/,
on_keypress_funcion/*21*/,
on_focus_funcion/*22*/,
num_html/*23*/,
num_div/*24*/,
prop1_div/*25*/,
prop2_div/*26*/,
prop3_div/*27*/,
on_blur/*28*/,
on_change/*29*/,
on_click_funcion/*30*/,
campo2buscador/*31*/,
id_encabezado/*32*/,
depende_de/*33*/,
on_doble_click_funcion/*34*/,
multidependencia/*35*/,
sql_combo_order/*36*/,
size_campo/*37*/,
num_columna/*38*/,
colspan_columna/*39*/,
titulo_largo/*40*/,
default_seleccion_combo/*41*/,
id_asociacion/*42*/
FROM sys_config_encabezados
where tabla ='".$tabla."'  ". $strWhereExcepcionesEncabezados. "
order by orden ";


//aqui colocamos la excepcion de los campos que se deben de ver



//echo "SQL ".$strSQL;

//'' as arr guarda el array con los valores de los combos $row[16]=array();
// si cambiar el indice de 16 en la consulta tambien cambiar en el template en el tipo combo
// {html_options values=$atributos[indice][16][0] output=$atributos[indice][16][1] }

// as valor2 es la descripcion si el combo no es editable
unset($atributos);
//atributos de cada campo
$atributos=array();
//valor de cada campo de un registro seleccionado
$camposValores=array();
//le da nombre a los campos ocultos
$intValor=0;

//echo "$strSQL<br>";
$contaAux=0;
if(!($resource = mysql_query($strSQL)))	die("Error at rowsel $rowsel::".mysql_error());
while($row=mysql_fetch_row($resource)){
	$intValor ++;
	if($ver!='1'){
		//guarda las funciones que se ejecutaran al cargar la pagina
		$scripts.='<script>'.$row[30].'</script>';
	}
	//si estamos en la opcion ver registro cambioamos la propiedad modificable -> 7 a 0 no modificable
	//$especialValorProyectoPedidos especial para las solicitudes de cotizacion si estan no modificables
	
	
			
	
	
	if($ver=='1' || $especialValorProyectoPedidos=="1")
		$row[7]="0";
	//podemos colocar otro estilo del texto en el campo clase_control 19

	//SI ES COMBO MANDAR EL ARRY CON EL COMBO EN EL ROW CORRESPONDIENTE CON EL QUERY QUE SACAREMOS DE sql_combo con el  where
	//si es combo pero solo es para ver y no es oculto  lo coloca como texto el combo con el valor que trae la bdd
	// 3 -> tipo 6 -> visible


	if($row[3]=="COMBO" && $row[6]=="1" )
	{
		//si es modificable pero estamos en la opcion de ver entonces se convierte en no modificable
			
		//*********************************
		//aqui agregamos el if si estamos en la accion de insertar o modificar porque seguira pasando por este archivo para despues mostrar
		//echo "Entro a Combo ".$row[33]."//";
		if ($row[7]=="1")
		{
			//llenamos un arreglo con los valores del combo
			$htmlA=array();
			if($row[33] != 0)
			{
				$axVal=$atributos[$row[33]];
				$axVal=$axVal[16];
				$axVal=$axVal[0];
				$axVal=$axVal[0];
			}

			//echo $row[9]."</br>".$k."</br>".$row[33]."</br>".$axVal."</br>".$row[36]."///</br>";
			$htmlA=retornaListaIdsNombres($row[9], $k, $row[33], $axVal, $row[36]);
			$row[16]=array();
			$row[16]=$htmlA;
			//echo $htmlA."-".$htmlA[0][0]."-".$htmlA[1]."///";
		}
		else
		{
			//para el valor predeterminado si no es modificable
			if($row[15]!='')
			{
				if($row[33] != 0)
				{
					//print_r($atributos[$row[33]]);
					$axVal=$atributos[$row[33]];
					$axVal=$axVal[16];
					$axVal=$axVal[0];
					$axVal=$axVal[0];
					//$row[17]=retornaListaIdsNombres($row[9], $row[15], $row[33], $atributos[$row[33]+0][15]);
					//print_r($axVal);
					//print_r($axVal);
					//echo "<br>".$row[33]."<br>";
				}
				$strSQL1=$row[9]." ".$row[10]."  '".$row[15]."' ";
				$row[17]= retornaValor($strSQL1, $k, $row[33], $axVal);
			}
		}
			
	}

	//no indica si es la llave  y el primer campo que es el indice 1 siempre sera la llave
	if($row[11]=="1" && $intValor==1 )
	{
			
		$campoKey=	$row[0];
			
		//echo $campoKey."<br>";
		// colocamos el valor como no modificable
			
		//aqui veriricamos si estamos en nuevo o en modificar
		if($op==1 && $row[3]=='CHAR')
		{
			$row[7]="1";
		}
		else
			$row[7]="0";
			
	}

	if($row[3]=="BUSCADOR")
	{
		$row[31]=explode('|',$row[31]);
		if ($row[7]=="1")
		{
			//llenamos un arreglo con los valores del combo
			$htmlA=array();
			//echo '<br>'.$row[9];
			$htmlA=retornaListaIdsNombresBuscador($row[9], $k, $row[31]);
			$row[16]=array();
			$row[16]=$htmlA;
		}
		else
		{
			//para el valor predeterminado si no es modificable
			//if($row[15] != '')
			//echo "Valor: [".$row[15]."]<br>";
			if($row[15] != '')
			{
				$strSQL1=$row[9];
					
				$row[17]= retornaValor($strSQL1, $k, $row[31]);
			}
		}
	}
	if($row[3]=="COMBOBUSCADOR")
	{
			
		$idbuscador=retornaidencabezado($row[0],$tabla);
		$row[16]=$rooturl."code/ajax/datosbuscador.php?id=".$idbuscador."&llave=";
		if($row[35]!="")
			$row[33]=$row[35];

	}

	//colocamos el nombre del campo en el array para traerlo de la base de datos
	if($row[8]=="1")
		array_push($camposValores,$row[0]);
	else
		array_push($camposValores," '' as valor".$intValor);

	//selecciones por default cuando es nuevo
	if($op==1)
	{
		//vemos las restricciones si tiene un valor por defaulr si si vemos si es usuario o fecha o algo especial
		//para valor del usuario
		if($row[15]=='usuario')
		{
			$row[15]= $_SESSION["USR"]->userid;
			$row[17]= $_SESSION["USR"]->fullusername;

		} //para valor de la fecha
		
		if($row[15]=='$SUCURSAL')
		{
			$row[15]= $_SESSION["USR"]->sucursalid;
			

		} //para valor de la fecha
		
		elseif($row[15]=='now()')
		{
			$row[15]= $hoy = date("d/m/Y H:i:s");
			$row[17]= $hoy = date("d/m/Y H:i:s");
		}
		elseif($row[15]=='now(d/m/Y)')
		{
			$row[15]= $hoy = date("d/m/Y");
			$row[17]= $hoy = date("d/m/Y");
		}
		elseif($row[15]=='time')
		{
			$row[15]= date("H:i:s");
			//$row[17]= $_SESSION["USR"]->fullusername;
		}
		elseif($row[15]=='sucursal')
		{
			$row[15]=$_SESSION["USR"]->id_sucursal;
		}
		elseif($row[15]=='id_compania')
		{
			$row[15]=$_SESSION["USR"]->id_compania;
		}
		elseif($row[15]=='id_sucursal')
		{
			$row[15]= $_SESSION["USR"]->sucursalid;
		}
	}

	//anexamos las propiedades del campo
	array_push($atributos,$row);

	$countAux++;

}
mysql_free_result($resource);


//una vez cargados los atributos cambiamos las propuiedade segun sea el caso
require('encabezadosPreExcepciones.php');


$strTrans="AUTOCOMMIT=0";
mysql_query($strTrans);
mysql_query("BEGIN");
$error=0;


//TERMINA VALIDACIONES DEL GRID
//******************************************************Validacion grid*******************

//echo $make."<br>";
//print_r($atributos);
//die;
//ACCIONES DEL REGISTRO 'MODIFICAR O ACTUALIZAR'
if($make=='actualizar')
{
	//vamos formado el update pero quitamos los campos que no se ingresaran a la base de datos
	$tabla = str_replace("#","", $tabla);
	$strUpdate = "UPDATE ".$tabla." SET ";
	$strBitacora ="";
	for($i=0; $i <  count($atributos); $i++)
	{
		//si el atributo va a la base de datos lo cosideramos para el update
		//si es del tipo passwod lo decodificamos
		if($atributos[$i][8]=='1')
		{

			//solo manejamos llaves unicas
			//vemos si el atributo es llave y no compuesta
			if($atributos[$i][11]=='1' && $atributos[$i][12]=='0')
			{
				$valorllave= "campo_".$i;
			}

			//valores que obtenemos de la forma
			$valor= "campo_".$i;
			//si el valor es vacios
			if($$valor=="" && $atributos[$i][3] != 'ARCHIVO')//if($$valor=="")
			{
				//analizamos el tipo
				if($t == "cGV1Z19jbGllbnRlc191c3Vhcmlvc193ZWI=" && $atributos[$i][3]=='PASS' || $atributos[$i][0] == 'vin')
					$strUpdate .= " ";
				elseif($atributos[$i][3]=='TYNYINT')
					$strUpdate .= " ". $atributos[$i][0]. " = '0' ,";
				else
					$strUpdate .= " ". $atributos[$i][0]. " = NULL ,";
			}
			else
			{
				if($atributos[$i][3]=='PASS')
				{
					if($t == "cGV1Z19jbGllbnRlc191c3Vhcmlvc193ZWI=")
					{
						if($$valor != '')
							$strUpdate .= " ". $atributos[$i][0]. " = '".md5($$valor)."' ,";
					}
					else
						$strUpdate .= " ". $atributos[$i][0]. " = '".base64_encode($$valor)."' ,";
				}
				elseif($atributos[$i][3]=='INT'){
					$$valor=str_replace(',','',$$valor);
					$strUpdate .= " ". $atributos[$i][0]. " = '".$$valor."' ,";
				}
				elseif($atributos[$i][3]=='DECIMAL'){
					$$valor=str_replace(',','',$$valor);
					$strUpdate .= " ". $atributos[$i][0]. " = '".$$valor."' ,";
				}
				elseif($atributos[$i][3]=='DATE')
					$strUpdate .= " ". $atributos[$i][0]. " = '".convertDate($$valor)."' ,";
				elseif($atributos[$i][3]=='ARCHIVO')
				{
					$ruta = "";
					$ext = pathinfo($_FILES[$valor]['name'], PATHINFO_EXTENSION);
					switch($atributos[$i][0])
					{
						case 'fotografia':
							$ruta = "../../fotos/";
							$nvoNombreArc = $ruta . create_guid($tabla) . base64_encode($_FILES[$valor]["name"]) . ".$ext";			
							break;
						case 'documento_referencia':
							//egreos bancarios
							$ruta = "../../comprobantes/";
							$nvoNombreArc = $ruta . create_guid($tabla) . base64_encode($_FILES[$valor]["name"]) . ".$ext";			
					
							break;
						case 'referencia_xml':
							//para cxp xml
							$ruta = ROOTPATHCXP;
							$nvoNombreArc = $ruta .$timeArchivosCxP. $_FILES[$valor]["name"] . strtolower(".$ext");
//							echo $nvoNombreArc = $ruta .$timeArchivosCxP. $_FILES[$valor]["name"];
							$destino = str_replace(ROOTPATHCXP,ROOTPATHCXPVER,$nvoNombreArc);		
					
						break;
						case 'referencia_pdf':
							//para cxp pdf
							$ruta = ROOTPATHCXP;
							$nvoNombreArc = $ruta .$timeArchivosCxP. $_FILES[$valor]["name"] . ".$ext";
//							echo $nvoNombreArc = $ruta .$timeArchivosCxP. $_FILES[$valor]["name"];
							$destino = str_replace(ROOTPATHCXP,ROOTPATHCXPVER,$nvoNombreArc);			
						break;
						case 'ruta_fiel':
							$ruta = "../../../audicel_archivos/archivos_fiel/";			
							$nvoNombreArc = $ruta.$_FILES[$valor]["name"];			
						break;
						case 'ruta_cer':
							$ruta = RCSD;			
							$nvoNombreArc = $ruta.$_FILES[$valor]["name"];			
						break;
						case 'ruta_key':
							$ruta = RCSD;			
							$nvoNombreArc = $ruta.$_FILES[$valor]["name"];			
						break;

						default:
							$ruta = "../../fotos/";
							$nvoNombreArc = $ruta .$timeArchivosCxP. $_FILES[$valor]["name"] . ".$ext";	
							break;
					}
					//$ext = pathinfo($_FILES[$valor]['name'], PATHINFO_EXTENSION);	
					//$nvoNombreArc = $ruta . create_guid($tabla) . base64_encode($_FILES[$valor]["name"]) . ".$ext";
					
					if (!file_exists($ruta . $_FILES[$valor]["name"]) && $_FILES[$valor]["name"] != "")
					{
						//echo $nvoNombreArc;
						$archivoCopiado = move_uploaded_file($_FILES[$valor]["tmp_name"], $nvoNombreArc);
						if ($archivoCopiado)
						{
							//echo 'Se copio';
							if(empty($destino))
								$strUpdate .= "". $atributos[$i][0]. " = '" . $nvoNombreArc . "' ,";
							else
								$strUpdate .= "". $atributos[$i][0]. " = '" . $destino . "' ,";
							//echo $strUpdate;
						}
						else
						{
							//echo 'No Se copio';
							$strUpdate .= "";//. $atributos[$i][0]. " = '' ,";
						}
					}
					else
					{
						$archivoCopiado = move_uploaded_file($_FILES[$valor]["tmp_name"], $nvoNombreArc);
						if ($archivoCopiado)
						{
							$strUpdate .= "". $atributos[$i][0]. " = '" . $nvoNombreArc . "' ,";
						}
						else
						{
							$strUpdate .= "";//. $atributos[$i][0]. " = '' ,";
						}
					}
				}
				else if($atributos[$i][3]=='MAIL'){
					$strUpdate .= " ".$atributos[$i][0]. " = '".$$valor."' ,";
				}else if($atributos[$i][3]!='ARCHIVO'){
					$strUpdate .= " ". $atributos[$i][0]. " = '". mb_strtoupper($$valor, 'ISO-8859-1')."' ,";
					$strUpdate .= " ". $atributos[$i][0]. " = '".$$valor."' ,";
				}
			}


			

			if($i>0)
			{
				//	$concat="CONCAT('1|',".$atributos[$i][0].","'")";
				//	if(nombre= 'KODAK9',"0|",CONCAT("1|",nombre,"'"))

				//select 1 as num , if(nombre= 'KODAK9',"0|",CONCAT("1|",nombre,"'")) as act from newa_marcas where id_marca = '1'
				$idGeneral=$$valorllave;
				$strSQL="select 1 as num , if(".$atributos[$i][0]." = '". $$valor."','0|',CONCAT('1|',".$atributos[$i][0].")) as act from ".$tabla." where ".$campoKey ." = '".$$valorllave."'";
					
				$cambioval= retornaValor($strSQL);
				$cambioarr= explode('|',$cambioval);
				if($cambioarr[0]==1)
				{
					//bitacora
					//formamos una cadena de inserts
					if($strBitacora !='')
					{
						$strBitacora .=" ,";
					}

					$strBitacora .=" (NULL, '5', '".$tabla."', '".$atributos[$i][0]."','".$$valorllave."', '".$_SESSION["USR"]->userid."',CURDATE(), CURTIME(), '0', '".$cambioarr[1]."', '0') ";

				}
					
				//echo $atributos[$i][0]." - ". $tipo.'<br>';
			}

		}

	}

	//quitamos la ultima " , "
	//echo " update -->",$strUpdate. "<br>";
	
	$strUpdate = rtrim($strUpdate,",");
	$strUpdate = rtrim($strUpdate," ,");

	//nombre de la llave
	$strUpdate .= " where " .$campoKey ." = '".$$valorllave."'";

	if($tabla == 'anderp_productos'){

		$strUpdate .= " AND id_sucursal=".$_SESSION["USR"]->sucursalid;
		$insertSucur=2; //bandera es edicion de un producto
		$accion_pant = 5; //Accion update(EDICION del producto)
		$v_producto = $$valorllave; //clave de producto
		$v_prodTipo = $v_prodTipo; //tipo de producto
			
			
	}



	$llave=$$valorllave;

	//echo "edicion: ".$strUpdate."</br>";

	//EJECUTAMOS LA CONSULTA
	//if(!( mysql_query($strUpdate)))	die("Error at strUpdate $strUpdate::".mysql_error());
	//echo $strUpdate;
	//die($strUpdate);
	mysql_query($strUpdate) or rollback('strUpdate',mysql_error(),mysql_errno(),$strUpdate);




	//VARIABLE QUE INDICA SI SE REALIZO LA OPERACION DE INSERTAR ACTUALIZAR O ELIMINAR
	$ejecucion=1;
	//PROCESAMOS LAS EXCEPCIONES DE LOS CATALOGOS

	//aqui asignamos nuevamente el valor de la llave porque sabemos que campo es
	$llave=$$valorllave;
	//le reasiganmso el valor llave al actualizar
	$valorllave	=$llave;

	grabaBitacora(5, $tabla, 0, $valorllave, $_SESSION["USR"]->userid, 0, '', '');



	//Excepcion para evitar los multiples inserts en los refresh
	$smarty->assign("EvitaRefreshEdicion","SI");
	
	/**********************************Validacion que actualizara el grid de multisucursales dentro de cuentas por pagar**********************************/
	if($tabla =='ad_cuentas_por_pagar_operadora' && isset($sucursalesCXP)){ //Si la tabla es cuentas por pagar y la variable de sucursales existe
			
			foreach ($sucursalesCXP as $sucursalesDatos){ //Recorremos el arreglo de sucursales que envian desde cuenta por pagar
			
					$sql2 = "SELECT id_sucursal FROM ad_cuentas_por_pagar_sucursal_detalle WHERE id_sucursal = " . $sucursalesDatos . " AND id_cuenta_por_pagar = " . $llave;
					$result = new consultarTabla($sql2);
					$contador = $result -> cuentaRegistros();
			
					$monto_suc_cxp = "monto_cxp" . $sucursalesDatos; //Creamos la variable de monto
					$monto_real = str_replace(',' , '', $$monto_suc_cxp);
					$porc_suc_cxp = "porcentaje_cxp" . $sucursalesDatos;  //Creamos la variable de porcentaje
					
					if(!empty($monto_real) && !empty($$porc_suc_cxp) && $make == 'actualizar'){ 
							if($contador == 0){
									$sucCXPA['id_cuenta_por_pagar'] = $llave;
									$sucCXPA['id_sucursal'] = $sucursalesDatos;
									$sucCXPA['porcentaje'] = $$porc_suc_cxp;
									$sucCXPA['monto'] = $monto_real;
								
									accionesMysql($sucCXPA, 'ad_cuentas_por_pagar_sucursal_detalle', 'Inserta'); //Insertamos
									}
							else{
									$sql = "UPDATE ad_cuentas_por_pagar_sucursal_detalle SET porcentaje = " . $$porc_suc_cxp . ", monto = " . $monto_real . " WHERE id_sucursal = " . 	$sucursalesDatos . " AND id_cuenta_por_pagar = " . $llave; 
									mysql_query($sql) or die("Error en consulta:<br> $sql <br>" . mysql_error());
									}
							}	
					else if($monto_real == 0 || $$porc_suc_cxp == 0){
							if($contador > 0){
									$sqlElimina = "DELETE FROM ad_cuentas_por_pagar_sucursal_detalle WHERE id_sucursal = " . $sucursalesDatos . " AND id_cuenta_por_pagar = " . $llave;
									mysql_query($sqlElimina) or die("Error en consulta:<br> $sql <br>" . mysql_error());
									}
							}
					}
			}

//echo $strUpdate;

}
elseif($make=='insertar')
{

	$strNombresCampos="";

	//realizamos el insert a la base de datos de los campos
	for($i=0; $i <  count($atributos); $i++)
	{
		//si el atributo va a la base de datos lo consideramos para el update
		//si es del tipo passwod lo decodificamos
		if($atributos[$i][8]=='1')
		{
			//echo $atributos[$i][0]."-".$atributos[$i][1]."-".$atributos[$i][2]."-".$atributos[$i][3]."-".$atributos[$i][4]."-".$atributos[$i][5]."</br>";

			//echo $$valor."</br>";
			//valores que obtenemos de la forma
			$valor= "campo_".$i;
			$strNombresCampos .= " ". $atributos[$i][0]." ,";
			//solo manejamos llaves unicas
			//vemos si el atributo el llave
			if($atributos[$i][11]=='1')
			{
				//checamos si es llave compuesta con otro campo para crear su ID
				//sugerimos en nuevo id de la tabla
				//la llave siempre ser� cero
				if ($i==0)
				{
					//si el tipo de datos es char para el id conservamos en que trae
					if($atributos[$i][3]=='CHAR')
					{
						//conserva el valor de la llave que es ademas editable
						$tipoLlave="CHAR";
						$llaveChar=$$valor;
							
					}
					else
					{
						$SQLid = "SELECT MAX(".$atributos[$i][0].") AS 'maxId' FROM ".$tabla;
						$ref = mysql_query($SQLid) or die(mysql_error());
						extract(mysql_fetch_assoc($ref));
						$maxId = is_null($maxId)?1:++$maxId;
						$$valor=$maxId;
					}

				}
				else
				{
					//entonces esta compuesta con otra variable
					//12

				}
					
					
			}


			//si el valor es vacios
			if($$valor=="" && $atributos[$i][3] != 'ARCHIVO')
			{

				//analizamos el tipo
				if($atributos[$i][3]=='TYNYINT')
					$strValues .= " '0' ,";
				elseif($atributos[$i][3]=='DATE' && $t == 'YW5kZXJwX25vdGFzX3ZlbnRh')
				$strValues .= "NULL ,";
				elseif($atributos[$i][3]=='DATE')
				$strValues .= " NOW() ,";
				else
					$strValues .= " NULL ,";

			}
			else
			{
				if($atributos[$i][3]=='PASS')
				{
					if($t == 'cGV1Z19jbGllbnRlc191c3Vhcmlvc193ZWI=')
						$strValues .= " '".md5($$valor)."' ,";
					else
						$strValues .= " '".base64_encode($$valor)."' ,";
				}
				elseif($atributos[$i][3]=='INT')
				{
					$$valor=str_replace(',','',$$valor);
					
					$strValues .=" '". $$valor."' ,";
					
				}
				elseif($atributos[$i][3]=='DECIMAL')
				{
					$$valor=str_replace(',','',$$valor);
					
					$strValues .=" '". $$valor."' ,";
					
				}
				elseif($atributos[$i][3]=='DATE' && $$valor=="now()")
					$strValues .= " NOW() ,";
				elseif($atributos[$i][3]=='DATE' && $$valor=="NULL")
					$strValues .= " NULL ,";
				elseif($atributos[$i][3]=='DATE')
					$strValues .= " '".convertDate($$valor)."' ,";
				elseif($atributos[$i][3]=='ARCHIVO')
				{	
					$ruta = "";
					$ext = pathinfo($_FILES[$valor]['name'], PATHINFO_EXTENSION);	
					
					switch($atributos[$i][0])
					{
						case 'fotografia':
							$ruta = "../../fotos/";
							$nvoNombreArc = $ruta . create_guid($tabla) . base64_encode($_FILES[$valor]["name"]) . ".$ext";			
						break;
						case 'documento_referencia':
							//egreos bancarios
							$ruta = "../../comprobantes/";
							$nvoNombreArc = $ruta . create_guid($tabla) . base64_encode($_FILES[$valor]["name"]) . ".$ext";			
					
							break;
						case 'referencia_xml':
							//para cxp xml
							$ruta = ROOTPATHCXP;
							$nvoNombreArc = $ruta .$timeArchivosCxP. $_FILES[$valor]["name"] . ".$ext";
							$destino = str_replace(ROOTPATHCXP,ROOTPATHCXPVER,$nvoNombreArc);
							$ruta_destino = ROOTPATHCXPVER;			
					
						break;
						case 'referencia_pdf':
							//para cxp pdf
							$ruta = ROOTPATHCXP;
							$nvoNombreArc = $ruta .$timeArchivosCxP. $_FILES[$valor]["name"] . ".$ext";
							$destino = str_replace(ROOTPATHCXP,ROOTPATHCXPVER,$nvoNombreArc);
							$ruta_destino = ROOTPATHCXPVER;			
					
						break;
						case 'ruta_cer':
							$ruta = RCSD;		
							$nvoNombreArc = $ruta.$_FILES[$valor]["name"];		
						break;
						case 'ruta_key':
							$ruta = RCSD;		
							$nvoNombreArc = $ruta.$_FILES[$valor]["name"];			
						break;
						case 'ruta_fiel':
							$ruta = RCSD;	
							$nvoNombreArc = $ruta.$_FILES[$valor]["name"];			
						break;
						default:
							$ruta = "../../fotos/";
							$nvoNombreArc = $ruta . create_guid($tabla) . base64_encode($_FILES[$valor]["name"]) . ".$ext";			
					
						break;
					}

						
					//$nvoNombreArc = $ruta . create_guid($tabla) . base64_encode($_FILES[$valor]["name"]) . ".$ext";			
					
					if (!file_exists($ruta . $_FILES[$valor]["name"]))
					{						
						$archivoCopiado = move_uploaded_file($_FILES[$valor]["tmp_name"], $nvoNombreArc);
						if ($archivoCopiado)
						{
							if(empty($destino))
								$strValues .= " '" . $nvoNombreArc . "' ,";
							else
								$strValues .= " '" . $destino . "' ,";
						}
						else
						{
							$strValues .= " '',";
						}
					}
					else
					{
						$strValues .= " '" . $ruta . $_FILES[$valor]["name"] . "' ,";
					}	
				}
				elseif($atributos[$i][3]=='MAIL')
					$strValues .=" '". $$valor."' ,";
				else
					$strValues .= " '". mb_strtoupper($$valor, 'ISO-8859-1') ."' ,";
			}
		}
		//Realizamos la consulta asociada
	}

	$strNombresCampos = rtrim($strNombresCampos,',');
	$strValues = rtrim($strValues,',');

	$tabla = str_replace('#', '', $tabla);

	$strInsert=" INSERT INTO ".$tabla."  (".$strNombresCampos.") VALUES " ."  (".$strValues.")";
	//if(!( mysql_query($strInsert)))	die("Error at strInsert $strInsert ::".mysql_error());


	//echo $strInsert."</br>";
	//inserta encabezados
	mysql_query($strInsert) or rollback('strInsert',mysql_error(),mysql_errno(),$strInsert );


   
	$idGeneral=mysql_insert_id();
	
	/*Excepcion para Notas de Venta
	 *****/
	$accion_insertar=1;

	
	//Excepcion para evitar los multiples inserts en los refresh
	$smarty->assign("EvitaRefresh","SI");


	if($tipoLlave=="CHAR")
	{
		$valorllave=$llaveChar;
	}
	else
	{
		$valorllave=mysql_insert_id();
	}


	
	//bitacora 3 ES NUEVO
	grabaBitacora('3',$tabla,'0',$valorllave,$_SESSION["USR"]->userid,'0','','');



	//VARIABLE QUE INDICA SI SE REALIZO LA OPERACION DE INSERTAR ACTUALIZAR O ELIMINAR
	$ejecucion=1;


	//reasignamos el valor de la llave
	//
	if($tipoLlave=="CHAR")
	{
		$llave=$llaveChar;
		$valorllave=$llave;
	}
	else
	{
		$llave=$valorllave;
		$llave=$maxId;
	}



	//asignamos el valor de la llave que genero el nuevo insert
if($tabla =='ad_cuentas_por_pagar_operadora' && isset($sucursalesCXP)){ //Si la tabla es cuentas por pagar y la variable de sucursales existe
			
			foreach ($sucursalesCXP as $sucursalesDatos){ //Recorremos el arreglo de sucursales que nos da
				$monto_suc_cxp = "monto_cxp" . $sucursalesDatos; //Creamos la variable de monto
				$porc_suc_cxp = "porcentaje_cxp" . $sucursalesDatos;  //Creamos la variable de porcentaje
				$monto_real = str_replace(',' , '', $$monto_suc_cxp);
				if(!empty($$monto_suc_cxp) && !empty($$porc_suc_cxp) && $make == 'insertar'){  //Las variables que vengan con valores las insertamos
						
								$sucCXPI['id_cuenta_por_pagar'] = $llave;
								$sucCXPI['id_sucursal'] = $sucursalesDatos;
								$sucCXPI['porcentaje'] = $$porc_suc_cxp;
								$sucCXPI['monto'] = $monto_real;
								
								accionesMysql($sucCXPI, 'ad_cuentas_por_pagar_sucursal_detalle', 'Inserta'); //Insertamos
						
						}
				}
		}
	//asignamos el valor de la llave que genero el nuevo insert


}
elseif($make=='eliminar')
{
	
	//colomos las excepciones de las tablas que pueden eliminar

	//para otras el eliminar sera el cancelar

	//traemos el id y actualuzamos los tablas de activar =0 y cancelar
	//despues de esto nos vamos al listado
	if($tabla=='2')
		$strUpdate = "UPDATE ".$tabla." SET activo=0 ";
	else
		$strUpdate = "DELETE FROM ".$tabla."  ";

	$valorllave= "campo_0";

	/**Excepcion para pantalla productos opcion Eliminar
	 ***********************************/
	require('excepcionPantallaProductosOpcionEliminar.php');
	

	//el campo campoKey lo obtenemos desde el primer bloque
	$strUpdate .= " where " .$campoKey ." = '".$$valorllave."'".$condicion2;


	//echo "Elimina 1: ".$strUpdate."Accion ".$make."</br>";
	//die();
	mysql_query($strUpdate) or rollback('$strUpdate',mysql_error(),mysql_errno(),$strUpdate);


	
	if($tabla =='ad_cuentas_por_pagar_operadora' && isset($sucursalesCXP)){ //Si la tabla es cuentas por pagar y la variable de sucursales existe
			foreach ($sucursalesCXP as $sucursalesDatos){ //Recorremos el arreglo de sucursales que envian desde cuenta por pagar
					echo $sqlElimina = "DELETE FROM ad_cuentas_por_pagar_sucursal_detalle WHERE id_sucursal = " . $sucursalesDatos . " AND id_cuenta_por_pagar = " . $campo_0;
					mysql_query($sqlElimina) or die("Error en consulta:<br> $sql <br>" . mysql_error());
					}
			}



	//BITACORA 6 ELIMNAR
	grabaBitacora('6',$tabla,'0',$$valorllave,$_SESSION["USR"]->userid,'0','','');

	//echo "Location: ../general/listados.php?t=".tabla;

	mysql_query("COMMIT");
	header("Location: ../indices/listados.php?t=".$t."&velimino=1");
	die();
}


/**
*** La siguiente Excepción consulta solamente el GRID de Detalle de Conceptos de Gastos para el módulo de 'Cuentas Por Pagar' Cuando es llamado
*** por el FancyBox de Costeo de Productos
**/
require('excepcionGridDetalleConceptosGastosCXP.php');

	
	
	$res=mysql_query($sql);
	if(!$res)
		die("Error en:<br><i>$sql</i><br><br>Descripcion:<b>".mysql_error());
	$num=mysql_num_rows($res);
	if($num > 0)
	{
		require('encabezadosExcepcionesAntesGrid.php');
		require('../grid/confGrid.php');
	}
	mysql_free_result($res);
	
	//Funcion para ejecutar comandos del grid
	if($make=='insertar' || $make=='actualizar')	
	{
		for($i=0;$i<$ngrids;$i++)
		{
		
			$aux="file_".$i;
			$aux=$$aux;			
			if($aux != "")
			{
				$ar=fopen("".$aux."","rt");
				if($ar)
				{
					$texto="";					
					while(!feof($ar))
					{
						$texto.=fread($ar,10000);
					}
					
					$cons=explode('|',$texto);
					array_pop($cons);
					for($j=0;$j<sizeof($cons);$j++)
					{
						//echo "<br>".$cons[$j]."<br>";
						//echo "LLAVE ".$llave."</br>";
						if($llave == '')	
							$cons[$j]=str_replace('$llave',$k,$cons[$j]);
						else	
							$cons[$j]=str_replace('$llave',$llave,$cons[$j]);
						$cons[$j]=str_replace('NOW()',date("Y-m-d H:i:s"),$cons[$j]);
						$cons[$j]=str_replace('NOW(Y-m-d)',date("Y-m-d"),$cons[$j]);
						$cons[$j]=str_replace('time()',date("H:i:s"),$cons[$j]);
						$cons[$j]=str_replace('$user',$_SESSION["USR"]->userid,$cons[$j]);
						/*if(!mysql_query($cons[$j]))
							die("Error en:<br><i>".$cons[$j]."</i><br><br>Descripci�n:<br><b>".mysql_error());*/
					  // echo "En ".$cons[$j]."-";
					  //die($cons[$j]);
						mysql_query($cons[$j]) or rollback('strDetalleGrid',mysql_error(),mysql_errno(),$cons[$j]);
			    
					}
					
					
				}
				if($ar)
					fclose($ar);
					
				if(file_exists($aux))
					unlink($aux);
			}					
		}
		
		
		$idRecibo = "";
		mysql_query("SET NAMES 'utf8'") or die("Error en:<br><i>$sqlCliente</i><br><br>Descripcion:<br><b>".mysql_error());
		
		
	}


//-------------------------------
//-------------------------------
//---ESPECIAL PARA NASSER
//---------------------	
if(($tabla=='ad_facturas'||$tabla=='ad_facturas_audicel')&& $make=="insertar")
{
	mysql_query("COMMIT"); 
}
//---TERMINA FUNCION ESPECIAL PARA NASSER
//---------------------	

	
//EXCEPCIONES PARA LOS CATALOGOS	
	require('encabezadosExcepciones.php');


	
//-------------------------------
//-------------------------------
//---ESPECIAL PARA NASSER
//---------------------	
/*
if($tabla=='ad_facturas' && $make=="insertar")
{
	if($errorFacturacion!="0")
	{
		
		//$sqlDelFact="DELETE FROM na_facturas_detalle where id_control_factura= '".$llave."'";
		//mysql_query($sqlDelFact) or die("Error of_clientes_franquicias: " . mysql_error());
		//$sqlDelFact="DELETE FROM ad_facturas where id_control_factura= '".$llave."'";
		//mysql_query($sqlDelFact) or die("Error of_clientes_franquicias: " . mysql_error());
		
		
		$smarty->assign("respuesta",$errorFacturacion);
		$smarty->display("especiales/errorFacturas.tpl");
		die();
	}
}*/
	
	

//echo $op.'<BR>';
//echo $llave.'<BR>';

if($ver==1)
	$readonly="readonly";
else
	$readonly="";

//realizamso todas las transacciones realizadas
mysql_query("COMMIT");


//guarda los nombres de los campos de la tabla
$camposField=implode(",",$camposValores);
//la opcion 2 es de modificar y ver
//para la opcion de ver cambiamos a modificables= 0 if($atributos[$i][7]=="0")
	//echo "$op<br>";
if($op==1)
{
	$make="insertar";
	$tituloOperacion="Nuevo";

	//echo $strSQL;
	//vamos llenando la tabla con los valores precargados

	//mismo tama&ntilde;o que el numero de campos

}
elseif($op==2 || $op==3)
{
	//obtiene datos para desplegar
	//llena los campos con la informacion del registro

	if($op==2)
		$make="actualizar";
	else{
		$make="eliminar";
		$ver=3;
	}

	/*Excepcion Para casa ibarra para pantalla de productos de cada Sucursal */
	$sucursalX="";
	if($tabla == 'anderp_productos'){
		$sucursalX = " AND id_sucursal=".$_SESSION["USR"]->sucursalid;
	}
	else
		$sucursalX="";

	//formamos el query
	/*en caso de que sea una tabla repetido que lleva # se lo quitamos para que no quede comentado el query*/
	$tabla = str_replace("#", "", $tabla);
	$strSQL=" SELECT  ".$camposField. " FROM ". $tabla ." where ".$campoKey." = '".$llave."'".$sucursalX;

	//$strSQL=" SELECT  ".$camposField. " FROM ". $tabla ." where ".$campoKey." = '".$llave."' AND id_sucursal=".$_SESSION["USR"]->sucursalid;


	// echo "uno ".$strSQL."</br>";

	//asignamos el numero de campos
	$smarty->assign("Nreg",count($atributos));
	//echo $strSQL;
	//vamos llenando la tabla con los valores precargados
	if(!($result = mysql_query($strSQL)))	die("Archivo: encabezados.php<br>Error 1 en:<br><i>$strSQL</i><br><br> Descripcion:<br><b>".mysql_error());
	//mismo tamao que el numero de campos
	$valorRegistro = mysql_fetch_row($result);

	for ($i = 0; $i <  count($atributos); $i++) {
		//a cada campo del attibuto anexamos el valor de valor Registro
		$atributos[$i][15]=$valorRegistro[$i];
		//si el campo es de tipo TINYINT ENTONCES TOMAMOS EL VALOR DE CHE
		// 3 es el tipo 7 es visibilidad
		//si es del tipo check box 3 y es visible 6
		if($atributos[$i][3]=="TYNYINT" && $atributos[$i][6]=="1")
		{
			//si es modificable
			if($atributos[$i][7]=="1")
			{
				if($valorRegistro[$i]=="1")
					$atributos[$i][15]='checked';
				else
					$atributos[$i][15]='';
			}
			else
			{
				//si el valor no es modificable
				if($valorRegistro[$i]=="1")
					$atributos[$i][17]='SI';
				else
					$atributos[$i][17]='NO';
			}
		}
		else if($atributos[$i][3]=="DECIMAL"){
				$cuenta = strlen($atributos[$i][15]);
				$pos = strpos($atributos[$i][15], ".");
				$formato = ($cuenta - $pos) - 1;
				
				$atributos[$i][15]=truncateFloat($atributos[$i][15], $formato);;
				}
		
		elseif($atributos[$i][3]=="COMBO")
		{

			//si no es modificable
			//echo $atributos[$i][7]." => ".$atributos[$i][15]."-".$atributos[$i][33]."<br>";
			if($atributos[$i][7]=="0" && $atributos[$i][15]!='')
			{
				//anexe el '' en el where

				if($atributos[$i][33] != 0)
				{

					$strSQL1=$atributos[$i][9]." ".$atributos[$i][10]." '".$atributos[$i][15]."'";

					$atributos[$i][17]= retornaValor($strSQL1, $k, $atributos[$i][33], $atributos[$atributos[$i][33]][15]);

					//echo $atributos[$i][2]." => ".$atributos[$i][17]."<br>";
				}
				else
				{

					$strSQL1=$atributos[$i][9]." ".$atributos[$i][10]." '".$atributos[$i][15]."'";
					//echo $strSQL1."</br>";
					$atributos[$i][17]= retornaValor($strSQL1, $k, $atributos[$i][33]);
					//colocamos el tama&ntilde;o del valor del 4
					$atributos[$i][4]= strlen($atributos[$i][17]);
					//echo $atributos[$i][2]." => ".$atributos[$i][17]."<br>";
				}
			}
			else{
				//si es modificable agregado 16/01/12

				$strSQL1=$atributos[$i][9]." ".$atributos[$i][10]." '".$atributos[$i][15]."'";
				$atributos[$i][17]= retornaValor($strSQL1, $k, $atributos[$i][33]);
				//colocamos el tama&ntilde;o del valor del 4
				//$atributos[$i][4]= strlen($atributos[$i][17]);
			}

		}
		elseif($atributos[$i][3]=="BUSCADOR")
		{

			//si no es modificable
			if($atributos[$i][7]=="0" && $atributos[$i][15]!='')
			{
				//anexe el '' en el where
					
				$strSQL1=$atributos[$i][9]." ".$atributos[$i][10]." '".$atributos[$i][15]."'";
				$atributos[$i][17]= retornaValorBuscador($strSQL1, $k, $atributos[$i][31]);
				//colocamos el tama�o del valor del 4
				$atributos[$i][4]= strlen($atributos[$i][17]);
			}
		}
		elseif($atributos[$i][3]=="PASS")
		{
			//el password nunca debe ser mostrado
			if($t == "cGV1Z19jbGllbnRlc191c3Vhcmlvc193ZWI=")
				$atributos[$i][15]="";
			else
				$atributos[$i][15]=base64_decode($atributos[$i][15]);
		}
		elseif($atributos[$i][3]=="COMBOBUSCADOR")
		{

			$strConsulta=$atributos[$i][9]." ".$atributos[$i][10]."'".$valorRegistro[$i]."'";

			//echo "1 ".$strConsulta."</br>";

			$strConsulta=str_replace('$SUCURSAL',$_SESSION["USR"]->sucursalid,$strConsulta);

			$arregloval=valBuscador($strConsulta);
			if($arregloval[1]=="")
				$arregloval[1]==$arregloval[0];
			$atributos[$i][15]=$arregloval;
		}
		elseif($atributos[$i][3]=="ARBOL")
		{

			$strConsulta=$atributos[$i][9]." ".$atributos[$i][10]."'".$valorRegistro[$i]."'";

			//echo "1 ".$strConsulta."</br>";

			$strConsulta=str_replace('$SUCURSAL',$_SESSION["USR"]->sucursalid,$strConsulta);

			$arregloval=valBuscador($strConsulta);
			if($arregloval[1]=="")
				$arregloval[1]==$arregloval[0];
			$atributos[$i][15]=$arregloval;
		}
		else if($atributos[$i][3]=="ARCHIVO"){
			$atributos[$i][15] = str_replace(RCSD,VERCSD,$atributos[$i][15]);
		}
		if($atributos[$i][3]=="DATE")
		{
			if($atributos[$i][15] == ''){
				$atributos[$i][15]=$atributos[$i][15];
			}
			else{
				$atributos[$i][15]=convertDate2($atributos[$i][15]);
			}
		}
	}
	
		

	if($op==2)
		grabaBitacora('4',$tabla,'0',$llave,$_SESSION["USR"]->userid,'0','','');
}

//una lleno el array de atributos con sus respectivos valores del registro
//obtenemos valores dependientes de otras consultas o cambiamos las propiedades para desplegar.
require('encabezadosPostExcepciones.php');
//echo "-------op--".$op."<br>";
	//echo "-------make--".$make."<br>";
	//echo "-------ver--".$ver;
/******************************************************************************************************
 * Llenamos las acciones que se desplegaran en los catalogos
*/
if($make=='insertar' && $llave=='')
{
	$smarty->assign("mensaje_accion","Agregando Nuevo Registro.");
	$smarty->assign("tipo_mensaje","nuevo");
}
elseif($make=='actualizar' && $ver==0)
{
	$smarty->assign("mensaje_accion","Modificando Registro.");
	$smarty->assign("tipo_mensaje","actualizado");
}
elseif($make=='eliminar'&&$ver==3&&$tipo_mensaje!='eliminado')
{
	$smarty->assign("mensaje_accion","Eliminando Registro.");
	$smarty->assign("tipo_mensaje","eliminado");
}
elseif($make=='actualizar'&&$ver==1&&$tipo_mensaje=='nuevo')
{
	$smarty->assign("mensaje_accion","Registro Agregado Exitosamente.");
}
elseif($make=='eliminar'&&$ver==3&&$tipo_mensaje=='eliminado')
{
	$smarty->assign("mensaje_accion","Registro Eliminado Exitosamente.");
}
elseif($make=='actualizar'&&$ver==1&&$tipo_mensaje=='actualizado')
{
	$smarty->assign("mensaje_accion","Registro Modificado Exitosamente.");
}



if($popup == 'SI' && $popup2 == 'SI')
{
	$funcpop=str_replace('valpop', "'".$valpopup."'", $funcpop);
	$funcpop=str_replace('llaveReturn', "'".$llave."'", $funcpop);
	echo "<script>window.opener.$funcpop;</script>";
}

if($closewindow == 10)
{
	echo "<script>window.close();</script>";
	die();
}




if($gridPresentaciones != '') {
	$smarty->assign("TipoProdPresentacion",$gridPresentaciones);
}
//la llave del registro
$smarty->assign("llave",$llave);

//Si se trata de un Popup
if($popup == 'SI')
{
	$hf=10;
	$smarty->assign("popup",$popup);
	$smarty->assign("funcpop",$funcpop);
	$smarty->assign("valpopup",$valpopup);
}


//Asignamos si se van a ver footer y header
if(isset($hf))
	$smarty->assign("hf",$hf);

//para checar si es solo lectura
$smarty->assign("readonly",$readonly);

//opcion de ver o modificar
$smarty->assign("v",$ver);
$smarty->assign("k",$k);
$smarty->assign("op",$op);
//tabla
$smarty->assign("t",$t);
$smarty->assign("keyCodif",$keyCodif);

$smarty->assign("make",$make);
$smarty->assign("scripts",$scripts);

$smarty->assign("atributos",$atributos);
$smarty->assign("x",count($atributos));
$smarty->assign("iva_100","15");
$smarty->assign("numColumnas",2);
$smarty->assign("usuario_sucursal", $_SESSION["USR"]->id_sucursal);

$smarty->assign("id_sucursal", $_SESSION["USR"]->sucursalid);

$smarty->assign("especialValorProyectoPedidos", $especialValorProyectoPedidos);
//$smarty->assign("prospecto",$prospecto);
//die;
$smarty->assign("cl",$_GET['cl']);
$smarty->assign("stm",$stm);


//Variable de usuario para obtener los perfiles

$grupo = $_SESSION["USR"] -> idGrupo;
$smarty->assign("excep_articulos",$grupo);
$smarty->assign("pie_pagina",$pie);
$smarty->display("general/catalogo.tpl");



?>
