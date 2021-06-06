<?php

include "classes/vars.php";
include "classes/sessions.php";

echo "<pre>";
print_r($_COOKIE);
echo "</pre>";

die();

$sql = mysql_query("SELECT * FROM orders_orders ORDER BY order_id DESC ");
while ($row = mysql_fetch_array($sql)) {
//make order directory on server..
$dirName = $sitedir."/attachments/".$row['order_id'];
@mkdir($dirName,0777);

}
?>
