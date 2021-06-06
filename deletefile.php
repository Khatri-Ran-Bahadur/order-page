<?php
include "classes/vars.php";
include "classes/sessions.php";
$dbcon = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
error_reporting(0);

$homepage="file_uploader.php";

if ($logged == 0) {
header("Location: " . $siteurl."/order/login.php");
exit();
}

@session_start();
$order_idx = @$_SESSION["order_idx"];

if ($_SESSION["order_idx"] != $_GET['order_idx']) {
header("Location: " . $homepage);
}


if ((isset($_GET['filename'])) && (isset($_GET['order_idx']))) {	
$order_idx = $_GET['order_idx'];
$dirName = $sitedir."/attachments/".$order_idx;
$fname = $_GET['filename'];
$filename = $dirName."/".$_GET['filename'];
	if (unlink($filename)) {
	@mysql_query($dbcon,"DELETE FROM orders_orderfiles WHERE order_id = '$order_idx' AND filename = '$fname'");
		header("Location: $homepage");
	} else {
	@mysql_query($dbcon,"DELETE FROM orders_orderfiles WHERE order_id = '$order_idx' AND filename = '$fname'");
		header("Location: $homepage");
		//echo "<script type='text/javascript'> alert('Failed to delete: ".$_GET['filename'].". Please try again.');";
	}
}
else {
	echo "File deleted";
}
?>
