<?php
ob_start();
include "classes/vars.php";
include "classes/sessions.php";
$dbcon = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($logged == 0) {
header("Location: " . $siteurl);
exit();
}

$text .= '
<form id="form" name="form" method="POST" action="action-message.php"> ';

if (!$view && !$order_id) {
$title = 'All Messages';

$sql = "SELECT * FROM orders_messages WHERE receiver = 'customer' AND customer = '$email'  AND published = '1' ORDER BY id DESC LIMIT $start, $limit ";
$result = mysqli_query($dbcon,$sql);

$text .= '
<input type="hidden" name="url" value="messages.php" />
 <h1>All Messages</h1> 
<table width="90%" border="0">
<tr><td><input type="submit" name="action" class="button" value="Mark as Read" />&nbsp;<input type="submit" name="action" class="button" value="Mark as Unread" />&nbsp;<input type="submit" name="action" class="button" value="Delete" /><div style="float: right;">'.$pagination.'</div></td></tr>
<tr><td><b>select: </b><a href="JavaScript: check_all();">All</a> | <a href="JavaScript: uncheck_all();">None</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="compose-message.php">Compose Message</a><div style="float: right;"><b>View: </b><a href="messages.php?view=all">All</a> | <a href="messages.php?view=unread">Unread</a> | <a href="messages.php?view=flagged">Flagged</a> | <a href="messages.php?view=trash">Trash</a> | <a href="messages.php?view=sent-messages">Sent Messages</a></div></td></tr></table>';

$text .= '  <div id="dt_example">
		<div id="container">

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
            <tr>
              <th>&nbsp;</th>
              <th>Flag</th>
              <th>From</th>
              <th>TO</td>
              <th>Subject</th>
              <th>Date</th>
            </tr>
	</thead>
	<tbody>
';

} else if ($view == "all") {

	header("Location: ".$siteurl."/order/messages.php");
	exit();
} else if ($view == "unread") {
$title = 'Unread Messages';

$sql = "SELECT * FROM orders_messages WHERE receiver = 'customer' AND customer = '$email'  AND published = '1' AND read_status = 0  ORDER BY id DESC LIMIT $start, $limit ";
$result = mysqli_query($dbcon,$sql);
include "classes/pagination.php";
$text .= '
<input type="hidden" name="url" value="messages.php?view=unread" />
 <h1>Unread Messages</h1> 
<table width="90%" border="0">
<tr><td><input type="submit" name="action" class="button" value="Mark as Read" />&nbsp;<input type="submit" name="action" class="button" value="Mark as Unread" />&nbsp;<input type="submit" name="action" class="button" value="Delete" /><div style="float: right;">'.$pagination.'</div></td></tr>
<tr><td><b>select: </b><a href="JavaScript: check_all();">All</a> | <a href="JavaScript: uncheck_all();">None</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="compose-message.php">Compose Message</a><div style="float: right;"><b>View: </b><a href="messages.php?view=all">All</a> | <a href="messages.php?view=unread">Unread</a> | <a href="messages.php?view=flagged">Flagged</a> | <a href="messages.php?view=trash">Trash</a> | <a href="messages.php?view=sent-messages">Sent Messages</a></div></td></tr></table>';

$text .= '  <div id="dt_example">
		<div id="container">

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
            <tr>
              <th>&nbsp;</th>
              <th>Flag</th>
              <th>From</th>
              <th>TO</td>
              <th>Subject</th>
              <th>Date</th>
            </tr>
	</thead>
	<tbody>
';

} else if ($view == "flagged") {
$title = 'Flagged Messages';

$sql = "SELECT * FROM orders_messages WHERE receiver = 'customer' AND customer = '$email'  AND published = '1' AND flag != 0  ORDER BY id DESC LIMIT $start, $limit ";
$result = mysqli_query($dbcon,$sql);
include "classes/pagination.php";
$text .= '
<input type="hidden" name="url" value="messages.php?view=flagged" />
 <h1>Flagged Messages</h1> 
<table width="90%" border="0">
<tr><td><input type="submit" name="action" class="button" value="Mark as Read" />&nbsp;<input type="submit" name="action" class="button" value="Mark as Unread" />&nbsp;<input type="submit" name="action" class="button" value="Delete" /><div style="float: right;">'.$pagination.'</div></td></tr>
<tr><td><b>select: </b><a href="JavaScript: check_all();">All</a> | <a href="JavaScript: uncheck_all();">None</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="compose-message.php">Compose Message</a><div style="float: right;"><b>View: </b><a href="messages.php?view=all">All</a> | <a href="messages.php?view=unread">Unread</a> | <a href="messages.php?view=flagged">Flagged</a> | <a href="messages.php?view=trash">Trash</a> | <a href="messages.php?view=sent-messages">Sent Messages</a></div></td></tr></table>';

$text .= '   <div id="dt_example">
		<div id="container">

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
            <tr>
              <th>&nbsp;</th>
              <th>Flag</th>
              <th>From</th>
              <th>TO</td>
              <th>Subject</th>
              <th>Date</th>
            </tr>
	</thead>
	<tbody>
';


} else if ($view == "trash") {
$title = 'Deleted Messages';

$sql = "SELECT * FROM orders_messages WHERE receiver = 'customer' AND customer = '$email'  AND published = '0'  ORDER BY id DESC LIMIT $start, $limit ";
$result =mysqli_query($dbcon,$sql);
include "classes/pagination.php";
$text .= '
<input type="hidden" name="url" value="messages.php?view=trash" />
 <h1>Deleted Messages</h1> 
<table width="90%" border="0">
<tr><td><input type="submit" name="action" class="button" value="Mark as Read" />&nbsp;<input type="submit" name="action" class="button" value="Mark as Unread" />&nbsp;<input type="submit" name="action" class="button" value="Restore" /><div style="float: right;">'.$pagination.'</div></td></tr>
<tr><td><b>select: </b><a href="JavaScript: check_all();">All</a> | <a href="JavaScript: uncheck_all();">None</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="compose-message.php">Compose Message</a><div style="float: right;"><b>View: </b><a href="messages.php?view=all">All</a> | <a href="messages.php?view=unread">Unread</a> | <a href="messages.php?view=flagged">Flagged</a> | <a href="messages.php?view=trash">Trash</a> | <a href="messages.php?view=sent-messages">Sent Messages</a></div></td></tr></table>';

$text .= '   <div id="dt_example">
		<div id="container">

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
            <tr>
              <th>&nbsp;</th>
              <th>Flag</th>
              <th>From</th>
              <th>TO</td>
              <th>Subject</th>
              <th>Date</th>
            </tr>
	</thead>
	<tbody>
';


}  else if ($view == "sent-messages") {
$title = 'Sent Messages';

$sql = "SELECT * FROM orders_send_messages WHERE sender = 'customer' AND customer = '$email'  AND published = '1'  ORDER BY id DESC LIMIT $start, $limit ";
$result = mysqli_query($dbcon,$sql);
include "classes/pagination.php";
$text .= '
<input type="hidden" name="url" value="messages.php?view=sent-messages" />
 <h1>Sent Messages</h1> 
<table width="90%" border="0">
<tr><td><input type="submit" name="action" class="button" value="Mark as Read" />&nbsp;<input type="submit" name="action" class="button" value="Mark as Unread" />&nbsp;<input type="submit" name="action" class="button" value="Delete" /><div style="float: right;">'.$pagination.'</div></td></tr>
<tr><td><b>select: </b><a href="JavaScript: check_all();">All</a> | <a href="JavaScript: uncheck_all();">None</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="compose-message.php">Compose Message</a><div style="float: right;"><b>View: </b><a href="messages.php?view=all">All</a> | <a href="messages.php?view=unread">Unread</a> | <a href="messages.php?view=flagged">Flagged</a> | <a href="messages.php?view=trash">Trash</a> | <a href="messages.php?view=sent-messages">Sent Messages</a></div></td></tr></table>';

$text .= '   <div id="dt_example">
		<div id="container">

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
            <tr>
              <th>&nbsp;</th>
              <th>Flag</th>
              <th>From</th>
              <th>TO</td>
              <th>Subject</th>
              <th>Date</th>
            </tr>
	</thead>
	<tbody>
';


} else if ($order_id != "") {
$title = 'Order Messages';

$sql = "SELECT * FROM orders_messages WHERE receiver = 'customer' AND customer = '$email' AND order_id = $order_id   AND published = '1' AND flag != 0  ORDER BY id DESC LIMIT $start, $limit ";
$result = mysqli_query($dbcon,$sql);
include "classes/pagination.php";
$text .= '
<input type="hidden" name="url" value="messages.php?order_id='.$order_id.'" />
 <h1>Order Messages</h1> 
<table width="90%" border="0">
<tr><td><input type="submit" name="action" class="button" value="Mark as Read" />&nbsp;<input type="submit" name="action" class="button" value="Mark as Unread" />&nbsp;<input type="submit" name="action" class="button" value="Delete" /><div style="float: right;">'.$pagination.'</div></td></tr>
<tr><td><b>select: </b><a href="JavaScript: check_all();">All</a> | <a href="JavaScript: uncheck_all();">None</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="compose-message.php">Compose Message</a><div style="float: right;"><b>View: </b><a href="messages.php?view=all">All</a> | <a href="messages.php?view=unread">Unread</a> | <a href="messages.php?view=flagged">Flagged</a> | <a href="messages.php?view=trash">Trash</a> | <a href="messages.php?view=sent-messages">Sent Messages</a></div></td></tr></table>';

$text .= '    <div id="dt_example">
		<div id="container">

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
            <tr>
              <th>&nbsp;</th>
              <th>Flag</th>
              <th>From</th>
              <th>TO</td>
              <th>Subject</th>
              <th>Date</th>
            </tr>
	</thead>
	<tbody>
';


}
if ($view == "sent-messages") {
$linkxx = "view-sent-message.php";
} else {
$linkxx = "view-message.php";
}

if (mysqli_num_rows($result) == 0) {
$text .= '
 <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>No Messages</td>
              <td>&nbsp;</td>
            </tr>';

} else {
	while ($row = @mysqli_fetch_array($result)) {

		if ($row['flag'] == "0" ) {
			$flag = '<span style="color: #0000FF;" >Normal</span>';
		} elseif ($row['flag'] == "1" ) {
			$flag = '<span style="color: #00FF00;">Urgent</span>';
		} elseif ($row['flag'] == "2" ) {
			$flag = '<span style="color: #FFCD02;">Emergency</span>';
		} elseif ($row['flag'] == "3" ) {
			$flag = '<span style="color: #FF0000;">Critical</span>';
		}
		if ($row['sender'] == "admin") {
		$senderx = "Admin";
		$receiverx = $row['customer'];
		} else {
		$senderx = $row['customer'];
		$receiverx = "Admin";
		}
		$tr = $i++;
		$date  = getdate($row['date']);
		$month = $date["mon"];
		$day   = $date["mday"];
		$year  = $date["year"];

		$senddate = $day . '/' . $month . '/' . $year.' @ '.$date["hours"].':'.$date["minutes"];
		if ($row['read_status']== 0) {
		$bs = '<b>';
		$be = '</b>';
		} else {
		$bs = '';
		$be = '';
		}
	$text .= '<tr id="tr'.$tr.'" '.$trbgcolor.'>
		<td><input onClick="JavaScript: highlight(\'tr'.$tr.'\', \'one\', this.id);" type="checkbox" id="cb'.$tr.'" name="id[]" value="'.$row['id'].'"></td>
		<td>'.$flag.'</td>
		<td >'.$senderx .'</td>
		<td >'.$receiverx.'</td>
              	<td ><a href="'.$linkxx.'?message_id='.$row['id'].'">'.$bs.$row['subject'].$be.'</a></td>
              	<td >'.$senddate.'</td>
</tr>
';
		}

	}
$text .= '
	</tbody>
</table>
	</div>
	</div>

<table width="90%" border="0">
<tr><td><b>select: </b><a href="JavaScript: check_all();">All</a> | <a href="JavaScript: uncheck_all();">None</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="compose-message.php">Compose Message</a><div style="float: right;"><b>View: </b><a href="messages.php?view=all">All</a> | <a href="messages.php?view=unread">Unread</a> | <a href="messages.php?view=flagged">Flagged</a> | <a href="messages.php?view=trash">Trash</a> | <a href="messages.php?view=sent-messages">Sent Messages</a></div></td></tr>
<tr><td><input type="submit" name="action" class="button" value="Mark as Read" />&nbsp;<input type="submit" name="action" class="button" value="Mark as Unread" />&nbsp;<input type="submit" name="action" class="button" value="';
if ($view == "trash") {
$text.= "Restore";
} else {
$text.= "Delete";
}
$text .='" /><div style="float: right;">'.$pagination.'</div></td></tr>
</table>
</form>';
include "header.php";	

echo $text;


include "footer.php"; 
ob_flush();
?>
