<?php
include "classes/vars.php";
include "classes/sessions.php";
$dbcon = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
include('SMTPconfig.php');
include('SMTPClass.php'); 
if ($logged == 0) {
header("Location: " . $siteurl."/login.php");
}

if ($submit == "Send") { 
	if ($finish == 1) {
		//insert into DB
	$sql = @mysqli_query($dbcon,"SELECT * FROM orders_orders WHERE order_id = $order_id AND site_id = '$site_id' ");
	$rowxtopic = @mysqli_fetch_assoc($sql);
	$subject=stripslashes(nl2br($rowxtopic['topic']));
	//$subject = stripslashes(nl2br(@mysql_result($sql,0,"topic")));
	
	
	//$user_idx = @mysql_result($sql,0,"user_id");
	$rowxuser_id = @mysqli_fetch_assoc($sql);
	$user_idx=$rowxuser_id['user_id'];
	
	
	$sqlu = mysqli_query($dbcon,"SELECT * FROM orders_customers WHERE user_id = $user_idx AND site_id = '$site_id' ");
	//$firstname = @mysql_result($sqlu,0,"firstname");
	$rowxfirstname = @mysqli_fetch_assoc($sqlu);
   $firstname=$rowxfirstname['firstname'];

	//$email = @mysql_result($sqlu,0,"email");
	$rowemail = @mysqli_fetch_assoc($sqlu);
   	$email=$rowemail['email'];
	
	$date = time();
	$detailsx = @mysqli_real_escape_string($details);

	@mysqli_query($dbcon,"INSERT INTO orders_messages (sender, receiver, order_id, site_id, customer, admin, writer, date, subject, details, read_status, published, flag) VALUES ('admin', '$receiver', '$order_id', '$site_id', '$email', '1', '', '$date', '$subject', '$detailsx', '0', '1', '$flag') ");
	$message_id = mysqli_insert_id($dbcon); 

		if ($receiver == "writer") {
		//email customer
				$subjectx = "#$order_id.$site_id New Message From Customer";
				$message = "<a href=\"$siteurl\" target=\"_blank\"><img src=\"$email_logo\" alt=\"$companyname\" style=\"margin-bottom:10px\" border=\"0\"></a><br />
<br />Dear $firstname, <br />
			<br />
			A message has been posted by the customer to your order #$order_id.$site_id <a href='$siteurl/order/view.php?order_id=$order_id.$site_id#message_$message_id'>$subject</a>.<br />
			<br />
			Details <br />
			--------------- <br />
			".stripslashes(nl2br($details))." <br />
			---------------<br />
			<p>Please log into your account and review the message</p>
			<br />
			<span color='#ccc'>
			--<br />
			Thank you,<br />
			Administrator<br />
			$siteurl<br />
			______________________________________________________<br />
			THIS IS AN AUTOMATED RESPONSE. <br />
			***DO NOT RESPOND TO THIS EMAIL****<br />
			</span>";
				//mail($email,$subjectx,$message,$headers);
		
				$mail->addAddress($email); // Add a recipient

				$mail->Subject = $subjectx;
		
				$mail->MsgHTML($message);
		
				$mail->IsHTML(true);	
		
				$result = $mail->Send();				header("Location: view.php?order_id=$order_id#message_$message_id");
				exit();
		} else if ($receiver == "admin"){
		//email admin
				$subjectx = "#$order_id.$site_id New Message From Customer";
				$message = "<a href=\"$siteurl\" target=\"_blank\"><img src=\"$email_logo\" alt=\"$companyname\" style=\"margin-bottom:10px\" border=\"0\"></a><br />
<br />Admin, <br />
			<br />
			A message has been posted by admin to the order  <a href='".$siteurl."/wp-admin/view_order.php?order_id=".$order_id."'><b>#$order_id</b></a>.<br />
			Details <br />
			--------------- <br />
			".stripslashes(nl2br($details))." <br />
			---------------<br />
			<p>Please log into your account and review the message</p>
			<span color='#ccc'>
			--<br />
			Thank you,<br />
			$xfirsttname $xlasttname<br />
			Administrator<br />
			$siteaddress
			$siteurl<br />
			______________________________________________________<br />
			THIS IS AN AUTOMATED RESPONSE. <br />
			***DO NOT RESPOND TO THIS EMAIL****<br />
			</span>";
			//	mail($siteemail,$subjectx,$message,$headers);
				$mail->addAddress($siteemail); // Add a recipient

				$mail->Subject = $subjectx;

				$mail->MsgHTML($message);

				$mail->IsHTML(true);	

				$result = $mail->Send();	
				
				header("Location: view.php?order_id=$order_id#message_$message_id");
				exit();

		}

	}

} 





























if ($submit == "Send") { 
	if ($finish == 1) {
		//insert into DB
	$sql = @mysqli_query($dbcon,"SELECT * FROM orders_orders WHERE order_id = $order_id ");
	//$subject = @mysql_result($sql,0,"topic");
	$rowxtopic = @mysqli_fetch_assoc($sql);
	$subject=$rowxtopic['topic'];
	$sqlu = @mysqli_query($dbcon,"SELECT * FROM orders_customers WHERE user_id = $user_id ");
	//$firstname = @mysql_result($sqlu,0,"firstname");
	$rowfirstname = @mysqli_fetch_assoc($sqlu);
	$firstname=$firstname['topic'];
	$date = time();

	@mysqli_query($dbcon,"INSERT INTO orders_send_messages (sender, receiver, order_id, customer, admin, writer, date, subject, details, read_status, published, flag) VALUES ('customer', '$receiver', '$order_id', '$email', '1', '', '$date', '$subject', '$details', '0', '1', '$flag') ");
	@mysqli_query($dbcon,"INSERT INTO orders_messages (sender, receiver, order_id, customer, admin, writer, date, subject, details, read_status, published, flag) VALUES ('customer', '$receiver', '$order_id', '$email', '1', '', '$date', '$subject', '$details', '0', '1', '$flag') ");
	//$text .= "INSERT INTO messages (sender, receiver, order_id, customer, admin, writer, date, subject, details, read_status, published, flag) VALUES ('customer', '$receiver', '$order_id', '$email', '1', '$assigned_writer', '$date', '$subject', '$details21', '0', '1', '$flag') ";

		if ($receiver == "admin") {
		//email admin
				$subjectx = "#$order_id New Message From Customer";
				$message = "Dear Admin, <br />
			<br />
			A message has been posted by the customer $email to the order <b><a href='$siteurlview.php?order_id=$order_id'>#$order_id</a></b>.<br />
			<br />
			Details <br />
			--------------- <br />
			$details <br />
			---------------<br />
			<p>Please <a title='You have to be logged in as admin' href='$siteurl/wp-admin/order_messages.php?order_id=$order_id'>click here</a> to review the message</p>
			<br />
			<span color='#ccc'>
			--<br />
			Thank you,<br />
			Administrator<br />
			$siteurl<br />
			______________________________________________________<br />
			THIS IS AN AUTOMATED RESPONSE. <br />
			***DO NOT RESPOND TO THIS EMAIL****<br />
			</span>";
				//mail($siteemail,$subjectx,$message,$headers);
			
			$mail->addAddress($siteemail); // Add a recipient

			$mail->Subject = $subjectx;

			$mail->MsgHTML($message);

			$mail->IsHTML(true);	

			$result = $mail->Send();			} 
	$text .= 'Message Sent Successfully and Admin Notified!
	<META HTTP-EQUIV=REFRESH CONTENT="3; URL=order_messages.php?order_id='.$order_id.'">
	';

	}

} else {
	if (!$order_id || $order_id == "") {
	$text .= "Invalid Request";
	} else {
	$sql = mysqli_query($dbcon,"SELECT * FROM orders_messages WHERE order_id = $order_id AND customer = '$email' AND (sender = 'customer' OR receiver = 'customer') AND published = '1'  ORDER BY id ASC ");
		if (mysqli_num_rows($sql) == 0) {
		$text .= '<h2> No messages for this order!</h2> ';
	
		} else {
		$text .= '<h2><a href="view.php?order_id='.$order_id.'" title="Click to view order">#'.$order_id.' (Order Messages)</a></h2>
		<table width="100%" border="0" cellspacing="3" cellpadding="4">';
			while ($row = mysqli_fetch_array($sql)) {
			$color = (is_int($i / 2)) ? $color_one : $color_two;
			$date  = getdate($row['date']);
			$month = $date["mon"];
			$day   = $date["mday"];
			$year  = $date["year"];
			
			$senddate = $day . '/' . $month . '/' . $year.' @ '.$date["hours"].':'.$date["minutes"];
			$agox = floor((time() - $row['date']) / 1440);

			if ($agox <= 24) {
				$ago = $agox." Hours ago";
			} else {
				$ago = floor((time() - $row['date']) / 86400)." Days ago";
			}
			
			if ($row['sender'] == "admin") {
			$senderx = "Admin";
			$receiverx = $row['customer'];
			} else {
			$senderx = $row['customer'];
			$receiverx = "Admin";
			}
			if ($row['flag'] == "0" ) {
				$flag = '<span style="color: #0000FF;" ><strong>Normal</strong></span>';
			} elseif ($row['flag'] == "1" ) {
				$flag = '<span style="color: #009900;"><strong>Urgent</strong></span>';
			} elseif ($row['flag'] == "2" ) {
				$flag = '<span style="color: #FFCC66;"><strong>Emergency</strong></span>';
			} elseif ($row['flag'] == "3" ) {
				$flag = '<span style="color: #FF0000;"><strong>Critical</strong></span>';
			}

			$text .= '<tr class='.$color.'>
			<td width="20%" valign="top"><b>From:</b> '.$senderx.'<br /><b>To:</b> '.$receiverx.'<br /><b>Date:</b> '.$senddate.'<br /><b>flag:</b> '.$flag.'<br /></td>
			<td width="79%" valign="top"><b>'.$ago.'</b><br />
			<form method="POST" action="">
			<input type="hidden" name="finish" value="1" />
			<input type="hidden" name="message_id" value="'.$row['id'].'" />
			<input type="hidden" name="order_id" value="'.$order_id.'" />
			'.$row['details'].'
			<br /><br />
			</form></td>
			</tr>

			';
			$i++;
			}
		$text .='</table>';
		}

	$sql2 = mysqli_query($dbcon,"SELECT * FROM orders_orders WHERE order_id = $order_id ");
		$receivers = '	<select name="receiver">
				<option value="admin">Admin</option>
				</select>
				';
		
	$text .= '
	<form method="POST" action="">
	<input type="hidden" name="finish" value="1" />
	<h1>Compose Message Below</h1>
	<table width="100%" border="0" cellspacing="1" cellpadding="4">';
	$text .= '<tr class='.$color_one.'>
	<td width="20%" valign="top"><b>To:</b> '.$receivers.'<br /><br /><strong>Flag:</strong>&nbsp;&nbsp;<select name="flag">
		<option style="color: #0000FF;" selected value="0">Normal</option>
		<option style="color: #009900;" value="1"><strong>Urgent</strong></option>
		<option style="color: #FFCC66;" value="2"><strong>Emergency</strong></option>
		<option style="color: #FF0000;" value="3"><strong>Critical</strong></option>
		</select></td>
	<td width="59%" valign="top"><textarea  name="details" rows="15" cols="45" style=""></textarea><br />
	<input type="submit" name="submit" value="Send" /></td>
	</tr>		';
	$text .='</table></form>';

	}
}
include "header.php";	
?>
<div id="orderMessages">
	<?php echo $text; ?>
</div>
<?php
include "footer.php"; 
ob_flush();
?>
