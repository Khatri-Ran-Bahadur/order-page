<?php
if (isset($_REQUEST['preview']) && $_REQUEST['preview']== "Preview") {
	if (isset($_REQUEST['accept']) && $_REQUEST['accept'] != '') {
		include "preview.php";
	} else {
		echo 'You have to agree to our terms.<br /><br /><span class="buttonx"><a href="javascript:history.go(-1)">Back</a></span>';
	}
} else if (isset($_REQUEST['preview']) && $_REQUEST['accept']== "Complete") {
	die("Order Complete");
	include "order_complete.php";
} else if (!isset($_REQUEST['preview'])) {
	include 'form.php';
}
?>
