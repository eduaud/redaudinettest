<?php
echo "<script>alert('llego a php');</script>";
if (!empty($_FILES)) {
	$tempFile = $_FILES['file_upload']['tmp_name'];
	$targetPath = $_REQUEST['folder'] . '/';
	$targetFile =  $targetPath . $_FILES['file_upload']['name'];
 
	move_uploaded_file($tempFile,$targetFile);
	echo str_replace($_SERVER['DOCUMENT_ROOT'],'',$targetFile);
}

?>