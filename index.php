<?php
require("vars.php");

/*
*Check current pages
*/
if (curPageURL() == "$siteurl/order/index.php") { 
	header("Location: $siteurl/order/");
	exit();
}

/*
* Add Custom title in pages
*/
function insert_custom_title() {
	return "Order Now";
}
add_filter('wp_title','insert_custom_title');

/*
*Check current pages
*/
if (isset($_REQUEST['preview']) && $_REQUEST['preview'] == "Preview") {
	if ($_REQUEST['accept'] != '') {
		require("order/preview.php");
	} else {
		$text = 'You have to agree to our terms.<br /><br /><span class="buttonx"><a href="javascript:history.go(-1)">Back</a></span>';
	}
} else if (isset($_REQUEST['preview']) && $_REQUEST['preview'] == "Complete") {
	include("order/order_complete.php");
}


//Create page 
require("header.php");
if (!isset($_REQUEST['preview'])) {	    
	require("order/form.php");
} else {
	echo $text;
}
require('footer.php'); 

?>
