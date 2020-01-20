<?

	extract($_GET);
	
	
	$aux=file_get_contents("http://184.168.64.195:123/bridge.aspx?id_tipo=$id_tipo&id_documento=$id_documento&cancelacion=SI");
	
	$ax=explode('<span id="ajaxRes">', $aux);
	
	if(sizeof($ax) != 2)
		die("No se pudo conectar al sistema de timbrado.");
		
	$aux=$ax[1];
	
	$ax=explode('</span>', $aux);
	
	if(ereg($ax[0], "SignatureValue"))
		die("El documento se ha cancelado exitosamente");
	else
		die($ax[0]);	

?>