<?php

$file = base64_decode(@$_GET['f']);
$type = base64_decode(@$_GET['t']);
	header("Pragma: public"); // required
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("$type");
	header("Content-disposition: attachment; filename=\"" . basename($file)."\";");
	header("Content-Transfer-Encoding: binary");
	header('Content-Length: ' . filesize($file));
	readfile($file);
	exit();	

?>
