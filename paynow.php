<?php

include "vars.php";
 $dbcon = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($email == '') {
	header("Location: " . $siteurl."/order/login.php");
	exit();
}
$title="Pay Now";

function insert_custom_title() {
	global $title;
	return $title;
}

add_filter('wp_title','insert_custom_title');
include "header.php";

include "order/paynow.php";

if (isset($_REQUEST['submit']) && $_REQUEST['dconfirm'] == "2") {

?>
		<script language="JavaScript" type="text/javascript">

			document.getElementById('paymentProcessor').submit();
		
		</script>
<?php 

} 
include "footer.php"; 

?>
