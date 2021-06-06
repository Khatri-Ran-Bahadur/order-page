<?php
ob_start();
include "classes/vars.php";
include "classes/sessions.php";
 $dbcon = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
 include('SMTPconfig.php');
include('SMTPClass.php'); 

if ($logged == 0) {
header("Location: $siteurl/order/login.php");
exit();
}
$title = "Compose Message";
$text .= '<form action="" method="POST"> ';
$subjectx ='';
if (!$subject) {
$text .= '
<h1>Compose Message</h1> 
<table width="90%" border="0">
<tr><td><div style="float: right;"><b>View: </b><a href="messages.php?view=all">All</a> | <a href="messages.php?view=unread">Unread</a> | <a href="messages.php?view=flagged">Flagged</a></div></td></tr></table><br />';

$text .= '  <table width="90%" border="0" cellspacing="0" cellpadding="0">

<tr>
<td class="instruction-bg"><table width="90%" border="0" cellspacing="1" cellpadding="5">
<tr>
<td align="left" valign="top"><strong>Please Select a Subject:</strong> </td>
<td align="left" valign="top">

';


$sql = @mysqli_query($dbcon,"SELECT * FROM orders_orders WHERE user_id = $user_id ORDER BY order_id DESC ");
	while ($row = @mysqli_fetch_array($sql)) {
	$text .= '<input type="radio" name="subject" value="'.$row['order_id'].'" /> <a href="view.php?order_id='.$row['order_id'].'">'.$row['topic'].' (#'.$row['order_id'].')</a>  <br />';

	}

$text .= '</td>
</tr>     
<tr>
<td colspan="2" align="center" valign="middle">

<input type="submit" name="action" class="button" value="Next" />

</td>
    	</tr>  
  </table></td>
</tr>
          </table>
';
}  else {
	if (!$submit) {
	$sql = @mysqli_query($dbcon,"SELECT * FROM orders_orders WHERE user_id = $user_id AND order_id = $subject ");
		while ($row = @mysqli_fetch_array($sql)) {
		$text .= '
		<input type="hidden" value="'.$subject.'" name="order_id" />
		<input type="hidden" value="'.$subject.'" name="subject" />
		<h1>Compose Message</h1> 
		<table width="90%" border="0">
		<tr><td><a href="compose-message.php">Compose Message</a><div style="float: right;"><b>View: </b><a href="messages.php?view=all">All</a> | <a href="messages.php?view=unread">Unread</a> | <a href="messages.php?view=flagged">Flagged</a></div></td></tr></table><br />';

		$text .= '  <table width="90%" border="0" cellspacing="0" cellpadding="0">

		<tr>
		<td class="instruction-bg"><table width="90%" border="0" cellspacing="1" cellpadding="5">
		<tr>
		<td align="left" colspan="2" valign="top"><strong>To:</strong>&nbsp;&nbsp;&nbsp;&nbsp;
		<select name="receiver"><option value="admin">Admin</option>';

		$text .= '</select></td>
		</tr>  
		<tr>
		<td align="left" colspan="2"  valign="top"><strong>Flag:</strong>&nbsp;&nbsp;<select name="flag">
		<option style="color: #0000FF;" selected value="0">Normal</option>
		<option style="color: #00FF00;" value="1">Urgent</option>
		<option style="color: #FFCD02;" value="2">Emergency</option>
		<option style="color: #FF0000;" value="3">Critical</option>
		</select></td>
		</tr>    
		<tr>
		<td colspan="2" align="center" valign="middle">
		<textarea id="composeMessage"  name="details" rows="15" cols="65" style=""></textarea><br />
		<input type="submit" name="submit" class="button" value="Send" />

		</td>
		    	</tr>  
		  </table></td>
		</tr>

			  </table>
		';
		}
	
	} else {
	//insert into DB
	$sql = @mysqli_query($dbcon,"SELECT * FROM orders_orders WHERE user_id = $user_id AND order_id = $order_id ");
//	$assigned_writer  = @mysql_result($sql,0,"assigned_writer");
	//$subject = @mysql_result($sql,0,"topic");
	$rowsubject = @mysqli_fetch_assoc($sql);
$subject=$rowsubject['topic'];
	
	$date = time();
	@mysqli_query($dbcon,"INSERT INTO orders_send_messages (sender, receiver, order_id, customer, admin, writer, date, subject, details, read_status, published, flag) VALUES ('customer', '$receiver', '$order_id', '$email', '1', '', '$date', '$subject', '$details', '0', '1', '$flag') ");
	@mysqli_query($dbcon,"INSERT INTO orders_messages (sender, receiver, order_id, customer, admin, writer, date, subject, details, read_status, published, flag) VALUES ('customer', '$receiver', '$order_id', '$email', '1', '', '$date', '$subject', '$details', '0', '1', '$flag') ");
	//$text .= "INSERT INTO messages (sender, receiver, order_id, customer, admin, writer, date, subject, details, read_status, published, flag) VALUES ('customer', '$receiver', '$order_id', '$email', '1', '$assigned_writer', '$date', '$subject', '$details21', '0', '1', '$flag') ";
		$detailsx = str_replace(array("\r\n","\r","\n"), "<br>",$details);
		if ($receiver == "admin") {
		//email admin
				$subjectx = "#$order_id New Message From Customer";
				$message = "Dear Admin, <br />
			<br />
			A message has been posted by the customer $email to the order <b><a href='$siteurl/wp-admin/view_order.php?order_id=$order_id'>#$order_id</a></b>.<br />
			<br />
			Details <br />
			--------------- <br />
			$detailsx <br />
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
			
				$result = $mail->Send();	  				
							
		} 
	header("Location: messages.php?view=sent-messages");

	}

}
$text .= '</form>';
include "header.php";
?>
<div>
<?php echo '<h2>'.$title.'</h2>'.$text; ?>
</div>
<?php include "footer.php"; ?>

<?
ob_flush();
?>
