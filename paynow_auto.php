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

include "order/paynow_auto.php";



?>
		<script language="JavaScript" type="text/javascript">

			document.getElementById('paymentProcessor').submit();
		
		</script>

		<script language="javascript" type="text/javascript">

			function doOrderFormCalculation() {

				return false;

			}

		</script>
<?php include "footer.php"; ?>
