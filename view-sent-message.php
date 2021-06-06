<?php
ob_start();
include "classes/vars.php";
include "classes/sessions.php";
 $dbcon = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($logged == 0) {
header("Location: " . $siteurl);
}

$text .= '
<form id="form" name="form" method="GET" view=""> ';

if (!$message_id) {
$title = 'Invalid Request';
$text .= '
 <h1>Invalid Request</h1> 
<table width="90%" border="0">
<tr><td><div style="float: right;"><b>View: </b><a href="messages.php?view=all">All</a> | <a href="messages.php?view=unread">Unread</a> | <a href="messages.php?view=flagged">Flagged</a></div></td></tr>
<tr><td>Invalid Request</td></tr>
</table>';
} else if ($message_id != "") {
/* Get data. */
$sql = "SELECT * FROM orders_send_messages WHERE sender = 'customer' AND customer = '$email'  AND published = '1' AND id = $message_id ";
$result = mysqli_query($dbcon,$sql);
if (@mysqli_num_rows($result) == 0) {
$title = 'Invalid Request';
$text .= '
 <h1>Invalid Request</h1> 
<table width="90%" border="0">
<tr><td><div style="float: right;"><b>View: </b><a href="messages.php?view=all">All</a> | <a href="messages.php?view=unread">Unread</a> | <a href="messages.php?view=flagged">Flagged</a></div></td></tr>
<tr><td>Invalid Request</td></tr>
</table>';

} else {
	while ($row = @mysqli_fetch_array($result)) {
		$date  = getdate($row['date']);
		$month = $date["mon"];
		$day   = $date["mday"];
		$year  = $date["year"];

		$senddate = $month . '/' . $day . '/' . $year;
// update message set as read
@mysqli_query($dbcon,"UPDATE send_messages set read_status = 1 WHERE sender = 'customer' AND customer = '$email'  AND published = '1' AND id = $message_id");
$title = $row['subject'];
$text .= '
 <h1></h1> 
<table width="90%" border="0">
<tr><td><a href="compose-message.php?subject='.$row['order_id'].'">Reply</a> | <a href="compose-message.php">Compose Message</a><div style="float: right;"><b>View: </b><a href="messages.php?view=all">All</a> | <a href="messages.php?view=unread">Unread</a> | <a href="messages.php?view=flagged">Flagged</a></div></td></tr></table><br />';

$text .= '  <table width="90%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="instruction-bg"><table width="654" border="0" cellspacing="1" cellpadding="5">
		<tr>
                    <td width="15%" align="left" valign="top" ><strong>From: </strong></td>
                    <td width="84%" align="left" valign="top" >'.$row['sender'].'</td>
                  </tr>
                  <tr>
                    <td align="left" valign="top"><strong>To:</strong> </td>
                    <td align="left" valign="top">'.$row['receiver'].'  </td>
                  </tr>
		<tr>
                  		<td align="left" valign="top"><strong>Subject:</strong> </td>
                  		<td align="left" valign="top">'.$row['subject'].'</td>
                	</tr>   
		<tr>
                  		<td align="left" valign="top"><strong>Date:</strong> </td>
                  		<td align="left" valign="top">'.$senddate.'</td>
                	</tr>   
		<tr>
                  		<td colspan="2" align="center" valign="middle"><table class="message-details" width="100%"><tr bgcolor="#FFFFFF"><td align="left" valign="middle">
			'.$row['details'].' 
			</td></tr></table>

				</td>
                	</tr>              
              </table></td>
            </tr>
          </table>
<br />
<table width="90%" border="0">
<tr><td><a href="compose-message.php?subject='.$row['order_id'].'">Reply</a> | <a href="compose-message.php">Compose Message</a><div style="float: right;"><b>View: </b><a href="messages.php?view=all">All</a> | <a href="messages.php?view=unread">Unread</a> | <a href="messages.php?view=flagged">Flagged</a></div></td></tr></table>
';
		}

	}
$text .= '
	
</form>';

} 


include "header.php";


echo $text;

include "footer.php"; ?>

<?
ob_flush();
?>
