<?php ob_start(); ?>
<?php
die("Download");
error_reporting(0);
include "classes/vars.php";
$dbcon = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($logged == 0) {
header("Location: $siteurl/order/login.php");
exit();
}
$n = base64_decode(@$_GET['n']);
$i = base64_decode(@$_GET['i']);
$e = base64_decode(@$_GET['e']);

if ($e != $email) {
header("Location: " . $siteurl);
} else {
// Headers for an download:
$sql = @mysqli_query($dbcon,"SELECT * FROM orders_orderfiles WHERE order_id = '$i' AND filename = '$n' AND user_id = $user_id ");
	if (@mysqli_num_rows($sql)==0) {
	echo "File not found!";
	} else {
		while ($row=@mysqli_fetch_array($sql)){
		$file = base64_encode($sitedir.'/attachments/'.$i.'/'.$n);
		$type = base64_encode("Content-Type: ".$row['filetype']);
		}
		if (file_exists($sitedir.'/attachments/'.$i.'/'.$n)) {
			header("Location: force-download.php?f=$file&t=$type");
			exit();	

			
		} else {
			echo "File not found!";

		}
	}
}
?>
<? ob_flush(); ?> 
