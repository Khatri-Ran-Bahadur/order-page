<style>
#customer_order_nav a{text-decoration:none;}
</style>
<?php

include "vars.php";
global $wpdb;
page_protect();

$text .= customer_orders_menu();
//check all orders

$view = $get['view'];
if (!$view || $view == 'all_orders') {
	$title = 'My Orders';
	$text .= '<h2>All My Orders</h2>
	';
	$results = $wpdb->get_results("SELECT * FROM orders_orders  WHERE user_id = $user_id AND site_id = $site_id  ORDER BY order_id DESC");

	if ($results) {
		$text .='
		<div id="dt_example">
		<div id="container">
		<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
			<thead>
				<tr>
				<th>#</th>
				<th>Topic</th>
				<!--<th>Type</th>-->
				<!--<th>Category</th>-->
				<!--<th>Academic Level</th>-->
				<!--<th>Pages</th>-->
				<th>Total</th>
				<th>Payment</th>
				<th>Deadline</th>
				</tr>
			</thead>
			<tbody>
		';

		$mn = 1;
		foreach ($results as $row) {
			
			$order_categoryR = $wpdb->get_results("SELECT * FROM orders_subject_areas WHERE codex = '$row->order_category'");
			$roworder_category=$order_categoryR[0];
			$order_category=$roworder_category->details; 
			$secondsPerDay = ((24 * 60) * 60);

			$timeStamp = time();

			$daysUntilExpiry = $row->expiry;
			$expiry = $daysUntilExpiry * $secondsPerDay;

			$date  = getdate($expiry);
			$month = $date["mon"];
			$day   = $date["mday"];
			$year  = $date["year"];

			$expiryDate = $month . '/' . $day . '/' . $year;

			$expiryx = number_format(( $row->expiry - $timeStamp) / $secondsPerDay);

			$color = (is_int($mn / 2)) ? $color_one : $color_two;
			if ($row->payment_status == "0") {
				$payment_status = '<div><a href="paynow.php?order_id='.$row->order_id.'">'.payment_status($row->payment_status).' Pay Now</a></div>';
			} else {
				$payment_status  = '<div>'.payment_status($row->payment_status).' Paid</div>';
			}

					$text .= '
					<tr class="'.$color.'" onmouseout="this.className=\''.$color.'\';" onmouseover="this.className=\'selected\';">

						<td><a href="view.php?order_id='.$row->order_id.'">'.$row->order_id.'</a></td>
						<td><a href="view.php?order_id='.$row->order_id.'">'.stripslashes($row->topic).'</a></td>
						<!--<td>'.$row->doctype_x.'</td>-->
						<!--<td>'.$order_category.'</td>-->
						<!--<td>'.$row->academic_level.'</td>-->
						<!--<td>'.$row->numpages.'</td>-->
						<td>'.$row->total_x.'</td>
						<td>'.$payment_status.'</td>
						<td><span style="display: none">'.$row->expiry.'</span> '.gettimeremaining($row->expiry).' '.time_remaining_bar($expiryx).'</td>

					</tr>
				';
		}
		$text .= '	</tbody>
			</table>
				</div>
				</div>';
	} else {
		$text .= '<p>No orders in your profile. Click <a href="'.$siteurl.'/order/">here</a> to place an order now.';

	}
}else if ($view == "current") {
	$title = 'Current Orders';
	$text .= '<h2>Current Orders</h2>';
	$current_arr = "'4','5','6','7','8','9'";

	$results = $wpdb->get_results("SELECT * FROM orders_orders WHERE user_id = $user_id  AND site_id = $site_id AND status IN ($current_arr) ORDER BY order_id DESC");

	if ($results) {
		$text .='
		<div id="dt_example">
			<div id="container">

		<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
		<thead>
			<tr>
				<th>#</th>
				<th>Topic</th>
				<!--<th>Type</th>-->
				<!--<th>Category</th>-->
				<!--<th>Academic Level</th>-->
				<!--<th>Pages</th>-->
				<th>Total</th>
				<th>Payment</th>
				<th>Deadline</th>
			</tr>
		</thead>
		<tbody>
		';

		$mn = 1;
		foreach($results as $row) {
			$order_categoryx = $wpdb->get_results("SELECT * FROM orders_subject_areas WHERE codex = '$row->order_category'");
			$roworder_category=$order_categoryx[0];
			$order_category=$roworder_category->details;
			$secondsPerDay = ((24 * 60) * 60);

			$timeStamp = time();

			$daysUntilExpiry = $row->expires;
			$expiry =($daysUntilExpiry * $secondsPerDay);


			$date  = getdate($expiry);
			$month = $date["mon"];
			$day   = $date["mday"];
			$year  = $date["year"];

			$expiryDate = $month . '/' . $day . '/' . $year;

			$expiryx = number_format(( $row->expiry - $timeStamp) / $secondsPerDay);
			//echo $expiryDate . ' ' . $row->ctime;

			$color = (is_int($mn / 2)) ? $color_one : $color_two;


			if ($row->payment_status == "0") {
				$payment_status = '<div><a href="paynow.php?order_id='.$row->order_id.'">'.payment_status($row->payment_status).' Pay Now</a></div>';
			} else {
				$payment_status  = '<div>'.payment_status($row->payment_status).' Paid</div>';
			}



					$text .= '
					<tr class="'.$color.'" onmouseout="this.className=\''.$color.'\';" onmouseover="this.className=\'selected\';">

						<td><a href="view.php?order_id='.$row->order_id.'">'.$row->order_id.'</a></td>
						<td><a href="view.php?order_id='.$row->order_id.'">'.stripslashes($row->topic).'</a></td>
						<!--<td>'.$row->doctype_x.'</td>-->
						<!--<td>'.$order_category.'</td>-->
						<!--<td>'.$row->academic_level.'</td>-->
						<!--<td>'.$row->numpages.'</td>-->
						<td>'.$row->total_x.'</td>
						<td>'.$payment_status.'</td>
						<td><span style="display: none">'.$row->expiry.'</span> '.gettimeremaining($row->expiry).' '.time_remaining_bar($expiryx).'</td>

					</tr>
				';



		}
		$text .= '	</tbody>
			</table>
				</div>
				</div>';
	} else {
		$text .= '<p>No current orders (being worked on) at the moment.';

	}

} else if ($view == "approve") {
	$title = 'Approval Orders';
	$text .= '<h2>Review these uploaded Orders</h2>

	';
	$sql ="SELECT * FROM orders_orders WHERE user_id = $user_id AND site_id = $site_id AND status = 2 ORDER BY order_id DESC ";
	$results = $wpdb->get_results($sql);

	if ($results) {
		$text .='
	<div id="dt_example">
		<div id="container">

	<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th>#</th>
			<th>Topic</th>
			<!--<th>Type</th>-->
			<!--<th>Category</th>-->
			<!--<th>Academic Level</th>-->
			<!--<th>Pages</th>-->
			<th>Total</th>
			<th>Payment</th>
			<th>Deadline</th>
		</tr>
	</thead>
	<tbody>
		';

	$mn = 1;
		foreach($results as $row) {
			//$order_category = @mysql_result(@mysql_query("SELECT * FROM orders_subject_areas WHERE codex = '".$row->order_category."'"),0,"details");
			$order_categoryx = $wpdb->get_results("SELECT * FROM orders_subject_areas WHERE codex = '$row->order_category'");
			$roworder_category = $order_categoryx[0];
			$order_category=$roworder_category->details;

			$secondsPerDay = ((24 * 60) * 60);

			$timeStamp = time();

			$daysUntilExpiry = $row->expires;
			$date2 = $row->date2;
			$expiry = $date2 + ($daysUntilExpiry * $secondsPerDay);


			$date  = getdate($expiry);
			$month = $date["mon"];
			$day   = $date["mday"];
			$year  = $date["year"];

			$expiryDate = $month . '/' . $day . '/' . $year;

			$expiryx = number_format(( $row->expiry - $timeStamp) / $secondsPerDay);
			//echo $expiryDate . ' ' . $row->ctime;

			$color = (is_int($mn / 2)) ? $color_one : $color_two;


			if ($row->payment_status == "0") {
				$payment_status = '<div><a href="paynow.php?order_id='.$row->order_id.'">'.payment_status($row->payment_status).' Pay Now</a></div>';
			} else {
				$payment_status  = '<div>'.payment_status($row->payment_status).' Paid</div>';
			}



					$text .= '
					<tr class="'.$color.'" onmouseout="this.className=\''.$color.'\';" onmouseover="this.className=\'selected\';">

						<td><a href="view.php?order_id='.$row->order_id.'">'.$row->order_id.'</a></td>
						<td><a href="view.php?order_id='.$row->order_id.'">'.stripslashes($row->topic).'</a></td>
						<!--<td>'.$row->doctype_x.'</td>-->
						<!--<td>'.$order_category.'</td>-->
						<!--<td>'.$row->academic_level.'</td>-->
						<!--<td>'.$row->numpages.'</td>-->
						<td>'.$row->total_x.'</td>
						<td>'.$payment_status.'</td>
						<td><span style="display: none">'.$row->expiry.'</span> '.gettimeremaining($row->expiry).' '.time_remaining_bar($expiryx).'</td>

					</tr>
				';



		}
		$text .= '	</tbody>
			</table>
				</div>
				</div>';
	} else {
		$text .= '<p>No orders awaiting your approval at the moment..';

	}

} else if ($view == "complete") {
	$title = 'Complete Orders';
	$text .= '<h2>Completed Orders</h2>';
	$results = $wpdb->get_results("SELECT * FROM orders_orders WHERE user_id = $user_id AND site_id = $site_id AND status = 3 ORDER BY order_id DESC");

	if ($results) {
		$text .='
		<div id="dt_example">
			<div id="container">

		<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
		<thead>
			<tr>
				<th>#</th>
				<th>Topic</th>
				<!--<th>Type</th>-->
				<!--<th>Category</th>-->
				<!--<th>Academic Level</th>-->
				<!--<th>Pages</th>-->
				<th>Total</th>
				<th>Payment</th>
				<th>Deadline</th>
			</tr>
		</thead>
		<tbody>
		';

		$mn = 1;
		foreach($results as $row) {
			$order_categoryx = $wpdb->get_results("SELECT * FROM orders_subject_areas WHERE codex = '$row->order_category'");
			$roworder_category=$order_categoryx[0];
			$order_category=$roworder_category->details;

			$secondsPerDay = ((24 * 60) * 60);
			$timeStamp = time();
			$daysUntilExpiry = $row->expires;
			$expiry = ($daysUntilExpiry * $secondsPerDay);


			$date  = getdate($expiry);
			$month = $date["mon"];
			$day   = $date["mday"];
			$year  = $date["year"];

			$expiryDate = $month . '/' . $day . '/' . $year;

			$expiryx = number_format(( $row->expiry - $timeStamp) / $secondsPerDay);
			//echo $expiryDate . ' ' . $row->ctime;

			$color = (is_int($mn / 2)) ? $color_one : $color_two;


			if ($row->payment_status == "0") {
				$payment_status = '<div><a href="paynow.php?order_id='.$row->order_id.'">'.payment_status($row->payment_status).' Pay Now</a></div>';
			} else {
				$payment_status  = '<div>'.payment_status($row->payment_status).' Paid</div>';
			}
			$text .= '
			<tr class="'.$color.'" onmouseout="this.className=\''.$color.'\';" onmouseover="this.className=\'selected\';">
				<td><a href="view.php?order_id='.$row->order_id.'">'.$row->order_id.'</a></td>
				<td><a href="view.php?order_id='.$row->order_id.'">'.stripslashes($row->topic).'</a></td>
				<td>'.$row->total_x.'</td>
				<td>'.$payment_status.'</td>
				<td><span style="display: none">'.$row->expiry.'</span> '.gettimeremaining($row->expiry).' '.time_remaining_bar($expiryx).'</td>

			</tr>';
		}
		$text .= '</tbody>
					</table>
				</div>
			</div>';
	} else {
		$text .= '<p>No Complete orders at the moment.';
	}
}
function insert_custom_title() {
	global $title;
	return $title;
}

add_filter('wp_title','insert_custom_title');
include "header.php";

echo $text;

include "footer.php"; ?>
