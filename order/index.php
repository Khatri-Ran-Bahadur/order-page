<?php ob_start(); ?>
<?php

include "classes/vars.php";
include "classes/sessions.php";

header("Location: $siteurl/order/");
exit();
?>
<? ob_flush(); ?> 
