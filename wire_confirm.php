<?php
include "classes/vars.php";
include "classes/sessions.php";

if ($logged == 0) {
header("Location: " . $siteurl."/login.php");
}

include "header.php";

include "order/wire_confirm.php";
include "footer.php"; ?>
<? ob_flush(); ?> 
