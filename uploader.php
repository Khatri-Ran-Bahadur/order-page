<?php
include "classes/vars.php";
include "classes/sessions.php";

if ($logged == 0) {
header("Location: " . $siteurl."/login.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Upload</title>
<style type="text/css">
<!--
.style1 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
	font-weight: bold;
	color: #FFFFFF;
}
.input {
	background-color: #FBFBFB;
	height: 20px;
	width: 200px;
	border: 1px solid #E4E4E4;
	margin: 0px;
	padding: 0px;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #333333;
	line-height: 22px;
}
.upload-btn {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
	color: #FFFFFF;
	background-image: url(templates/gac/images/upload-btn-bg.jpg);
	background-repeat: repeat-x;
	height: 22px;
	padding-right: 15px;
	padding-left: 15px;
	border-top-width: 0px;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 0px;
	border-top-style: none;
	border-right-style: solid;
	border-bottom-style: solid;
	border-left-style: none;
	background-position: center center;
	border-right-color: #006600;
	border-bottom-color: #006600;
}
.style2 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
}
.style5 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; }
.attachment-btn {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 13px;
	font-weight: bold;
	color: #FFFFFF;
	background-image: url(templates/gac/images/attachment-btn-bg.jpg);
	background-repeat: repeat-x;
	padding-right: 12px;
	padding-left: 12px;
	border-top-width: 0px;
	border-right-width: 0px;
	border-bottom-width: 0px;
	border-left-width: 0px;
	border-top-style: none;
	border-right-style: none;
	border-bottom-style: none;
	border-left-style: none;
	background-position: center center;
	margin: 0px;
	padding-top: 5px;
	padding-bottom: 5px;
}
body {
	background-image: url(templates/gac/images/uploader-bg.jpg);
	background-repeat: repeat-x;
	margin-left: 5px;
	margin-top: 5px;
	margin-right: 5px;
	margin-bottom: 5px;
}
-->
</style>
<script type="text/javascript" src="uploader.js" ></script>
</head>
<body>		
<?php
	// This is the folder where file are uploaded
	//$uploadDirectory = "testdir";    for security reasons,  hardcode the name of the directrory in imageupload.php
	require_once("AjaxFileUploader.inc.php");
	$ajaxFileUploader = new AjaxFileuploader($uploadDirectory="");	
	echo $ajaxFileUploader->showFileUploader('id1');
?></body>
</html>
