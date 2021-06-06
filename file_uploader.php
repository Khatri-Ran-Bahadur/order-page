<?php
include "classes/vars.php";
include "classes/sessions.php";
$dbcon = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($logged == 0) {
header("Location: " . $siteurl."/login.php");
}

@session_start();
$order_idx = @$_SESSION["order_idx"];
if ($order_idx != "") {

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
	text-decoration: none;
}
body {
	background-image: url(templates/gac/images/uploader-bg.jpg);
	background-repeat: repeat-x;
	margin-left: 5px;
	margin-top: 5px;
	margin-right: 5px;
	margin-bottom: 5px;
}
</style>
<script type="text/javascript" src="uploader.js" ></script>
</head>

<body>
<table width="450" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td width="202" bgcolor="#333333"><span class="style1">Select your File</span></td>
    <td width="228" bgcolor="#333333" align="right"><a  class="attachment-btn" href="javascript:window.close();">Close</a>
</td>
  </tr>
 <tr>
    <td align="left" valign="top" colspan="2"><iframe name="iframeid2" src="uploader.php"   scrolling="no" frameborder="0" height="40" width="500"> </iframe></td>
  </tr>
    <tr>
    <td align="left" valign="top" colspan="2"><iframe name="iframeid2" src="uploader.php"   scrolling="no" frameborder="0" height="40" width="500"> </iframe></td>
  </tr>
    <tr>
    <td align="left" valign="top" colspan="2"><iframe name="iframeid2" src="uploader.php"   scrolling="no" frameborder="0" height="40" width="500"> </iframe></td>
  </tr>
    <tr>
    <td align="left" valign="top" colspan="2"><iframe name="iframeid2" src="uploader.php"   scrolling="no" frameborder="0" height="40" width="500"> </iframe></td>
  </tr>
    <tr>
    <td align="left" valign="top" colspan="2"><iframe name="iframeid2" src="uploader.php"   scrolling="no" frameborder="0" height="40" width="500"> </iframe></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><span class="style2">Current Files </span></td>
    <td>&nbsp;</td>
  </tr>
<?

$sql = @mysqli_query($dbcon,"SELECT * FROM orders_orderfiles WHERE order_id = '$order_idx' AND email = '$email' AND active = 1 ");
	if (@mysqli_num_rows($sql)==0) {
	echo '  
	<tr>
    	<td colspan="2">No files Uploaded yet!</td>
  	</tr>';
	} else {

		while ($files = @mysqli_fetch_array($sql)) {
		$filename = $files['filename'];	
					$i = base64_encode($order_idx);
					$n = base64_encode($files['filename']);
					$e = base64_encode($email);
		echo '  <tr>
    <td><span class="style5">'.$filename.' ('.$files['type'].')</span></td>
    <td><a href="download.php?n='.$n.'&i='.$i.'&e='.$e.'" class="upload-btn" id="button2" value="Open File">Open</a>
    </td>
  </tr>';
		//echo $filename."&nbsp;<a href='download.php?n=".$n."&i=".$i."&e=".$e."' target='_BLANK'>Open File</a> &nbsp;&nbsp;&nbsp; <a href='deletefile_b.php?filename=".$filename."&order_idx=".$order_idx."'>Delete File</a><br />";

		}

	} 
?>  <tr>
    <td><form><input type=button value="Refresh" class="upload-btn" id="button4" onClick="window.location.reload()"></form></td>
    <td>&nbsp;</td>
  </tr>
</table>

<?
} else {
echo "Invalid access!";
}

?>
