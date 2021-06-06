<?php

include "vars.php";
 $dbcon = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

page_protect();

$text .= customer_inbox_menu();
//check all orders

$view = $get['view'];

	if (!$view || $view == 'all') {
	$title = 'All Messages';
	$text .= '<h2>All Messages</h2>
	';

		$sql = "SELECT * FROM orders_messages WHERE receiver = 'customer' AND customer = '$email' AND site_id = $site_id AND published = 1 ORDER BY id DESC";
		$result = mysqli_query($dbcon,$sql);

		if (@mysqli_num_rows($result) > 0) {
			$text .='
					<table class="member_inbox_main_details td-align-left"  style="width: 100%;">
						<tbody>
			';

			while($row = mysqli_fetch_array($result)) {
				$sender =  $row['sender'];

				if ($row['read_status'] == 0) {
					$bs = '<b>';
					$be = '</b>';
					$tr_bg = ' style="background: orange"';
				} else {
					$bs = '';
					$be = '';
					$tr_bg = '';

				}

				if ($row['order_id'] != "0") {
					$x_order_id = $row['order_id'];
					$subject_x = '#'.$x_order_id.': '.stripslashes(show_order_title($x_order_id));
					$type = "Order Message";
				} else {
					$subject_x = stripslashes($row['subject']);
					$type = "Private Message"; //f5f0ae
				}

				$subject = substr($subject_x,0,30);

				
		
				//$date = date("F j, Y, g:i a",$row['date']);
				$date = date("F j",$row['date']);
				$text .='
							<tr '.$tr_bg.'>
								<td width="220">'.$bs.ucwords($type).' from '.ucwords($sender).$be.' </td>
								<td width="200"><a href="inbox.php?view=message&message_id='.$row['id'].'">'.$bs.$subject.$be.'</a></td>
								<td style="text-align: right;">'.$bs.' ('.TimeAgo($row['date']).')'.$be.'</td>
							</tr>
				';
			}

			$text .= '
						</tbody>
					</table>

			';
		} else {
			$text .= '<p>No Messages in your inbox.';

		}
	} else if ($view == "unread") {
	$title = 'Unread Messages';
	$text .= '<h2>Unread Messages</h2>

		';

		$sql = "SELECT * FROM orders_messages WHERE receiver = 'customer' AND customer = '$email'  AND site_id = $site_id AND published = '1' AND read_status = 0  ";

		$result = mysqli_query($dbcon,$sql);

		if (@mysqli_num_rows($result) > 0) {
			$text .='
					<table class="member_inbox_main_details td-align-left"  style="width: 100%;">
						<tbody>
			';
			while($row = mysqli_fetch_array($result)) {
				$sender =  $row['sender'];

				if ($row['read_status'] == 0) {
					$bs = '<b>';
					$be = '</b>';
					$tr_bg = ' style="background: orange"';
				} else {
					$bs = '';
					$be = '';
					$tr_bg = '';

				}

				if ($row['order_id'] != "0") {
					$x_order_id = $row['order_id'];
					$subject_x = '#'.$x_order_id.': '.stripslashes(show_order_title($x_order_id));
					$type = "Order Message";
				} else {
					$subject_x = stripslashes($row['subject']);
					$type = "Private Message"; //f5f0ae
				}

				$subject = substr($subject_x,0,30);

				
		
				//$date = date("F j, Y, g:i a",$row['date']);
				$date = date("F j",$row['date']);
				$text .='
							<tr '.$tr_bg.'>
								<td width="220">'.$bs.ucwords($type).' from '.ucwords($sender).$be.' </td>
								<td width="200"><a href="inbox.php?view=message&message_id='.$row['id'].'">'.$bs.$subject.$be.'</a></td>
								<td style="text-align: right;">'.$bs.' ('.TimeAgo($row['date']).')'.$be.'</td>
							</tr>
				';
			}

			$text .= '
						</tbody>
					</table>

			';
		} else {
			$text .= '<p>No Unread Messages in your inbox.';

		}

	} else if ($view == "sent") {
	$title = 'Sent Messages';
	$text .= '<h2>Sent Messages</h2>

		';
		$sql = "SELECT * FROM orders_messages WHERE sender = 'Customer' AND customer = '$email' AND site_id = $site_id AND published = 1 ORDER BY id DESC";
		$result = mysqli_query($dbcon,$sql);

		if (@mysqli_num_rows($result) > 0) {
			$text .='
					<table class="member_inbox_main_details td-align-left" style="width: 100%;">
						<tbody>
			';
			while($row = mysqli_fetch_array($result)) {
				$sender =  $row['sender'];

				$bs = '';
				$be = '';
				$tr_bg = '';

				if ($row['order_id'] != "0") {
					$x_order_id = $row['order_id'];
					$subject_x = '#'.$x_order_id.': '.stripslashes(show_order_title($x_order_id));
					$type = "Order Message";
				} else {
					$subject_x = stripslashes($row['subject']);
					$type = "Private Message"; //f5f0ae
				}

				$subject = substr($subject_x,0,30);

				
		
				//$date = date("F j, Y, g:i a",$row['date']);
				$date = date("F j",$row['date']);
				$text .='
							<tr '.$tr_bg.'>
								<td width="220">'.$bs.ucwords($type).' from '.ucwords($sender).$be.' </td>
								<td width="200"><a href="inbox.php?view=message&message_id='.$row['id'].'">'.$bs.$subject.$be.'</a></td>
								<td style="text-align: right;">'.$bs.' ('.TimeAgo($row['date']).')'.$be.'</td>
							</tr>
				';
			}

			$text .= '
						</tbody>
					</table>

			';
		} else {
			$text .= '<p>No Sent Messages in your inbox.';

		}


	} else if ($view == "message") {

		$message_id = $get['message_id'];
		if ($message_id !='') {
			$sql = mysqli_query($dbcon,"SELECT * FROM orders_messages WHERE id = $message_id AND (receiver = 'customer' OR sender = 'Customer') AND customer = '$email' AND site_id = $site_id ");
			if (@mysqli_num_rows($sql) == 0) {
				$title = "Message not found!";
				$text .="Message not found!";
			} else {
				$row = mysqli_fetch_array($sql);


				if ($row['order_id'] != "0") {
					$x_order_id = $row['order_id'];
					$subject = '#'.$x_order_id.': '.stripslashes(show_order_title($x_order_id));
					$type = "Order Message";
					$view_order = ' <a href="view.php?order_id='.$x_order_id.'">View Order #'.$x_order_id.'</a> | ';
					$reply = '<a href="view.php?order_id='.$x_order_id.'#compose_message">Reply</a> |';
				} else {
					$subject = $row['subject'];
					$type = "Private Message";
					$view_order = '';
					$reply = '';
				}

				$date = date("F j, Y, g:i a",$row['date']);

				$title = $subject;

				$text .= '
					<h3>'.$subject.'</h3>

					<table class="member_inbox_main_details td-align-left" style="width: 100%;">
						<tbody>
							<tr>
								<th>'.ucwords($type).' from '.ucwords($row['sender']).'</th>
							</tr>
							<tr>
								<th>Date '.$date.' ('.TimeAgo($row['date']).')</th>
							</tr>
							<tr>
								<td>'.$view_order.$reply.'</td>
							</tr>
							<tr>
								<td><b>Message Details:</b> <br />'.stripslashes(nl2br($row['details'])).'</td>
							</tr>
							<tr>
								<td>'.$view_order.$reply.'</td>
							</tr>
						</tbody>
					</table>
		';

				//set status to read
				mysqli_query($dbcon,"UPDATE orders_messages SET read_status = 1 WHERE id = $message_id  AND site_id = $site_id ");

			}

		} else {
				$title = "Invalid Request!";
				$text .="Invalid request!";
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
