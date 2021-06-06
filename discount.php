<?php
include './vars.php';
$total = base64_decode($_REQUEST['total']);
echo calculate_discount($_REQUEST['code'],$total);

/* 
* Get discount percentage
*/
function calculate_discount($code,$total) {
	
	if ($code && $total) {
		$now = time();
		if ( $total < 30 ) {
			return "Order total must be $30 or more!";
			exit();
		}
		global $wpdb;
		$site_id=SITE_ID;		
		$data = $wpdb->get_results("SELECT percentage FROM orders_discount_codes WHERE site_id=$site_id AND  codex = '$code' AND expiry > $now AND status = 1 ");
		
		if ($data > 0) {
			return $data[0]->percentage;
			exit();
		} else {
			return "Invalid coupon code. Please try again.";
			exit();
		}
	} else {
		return "invalid request!";
		exit();
	}
}
?>
