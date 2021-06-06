<?php
ob_start();
include "classes/vars.php";
include "classes/sessions.php";
global $dbcon;
    $dbcon = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($logged == 0) {
header("Location: $siteurl/order/login.php");
exit();
}
if (!$action) {
header("Location: messages.php");
} else if ($action == "Mark as Read") {
	foreach ($message_id as $key => $id) {
	@mysqli_query($dbcon,"UPDATE orders_messages SET read_status = 1 WHERE id = $id AND customer = '$email' ");
	}
	header("Location: ".$url);

}else if ($action == "Mark as Unread") {
	foreach ($message_id as $key => $id) {
	@mysqli_query($dbcon,"UPDATE orders_messages SET read_status = 0 WHERE id = $id AND customer = '$email' ");
	}
	header("Location: ".$url);
}else if ($action == "Delete") {
	foreach ($message_id as $key => $id) {
	@mysqli_query($dbcon,"UPDATE orders_messages SET published = 0 WHERE id = $id AND customer = '$email' ");
	}
	header("Location: ".$url);
} else if ($action == "Restore") {
	foreach ($message_id as $key => $id) {
	@mysqli_query($dbcon,"UPDATE orders_messages SET published = 1 WHERE id = $id AND customer = '$email' ");
	}
	header("Location: ".$url);
}

ob_flush();
?>
