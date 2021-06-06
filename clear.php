<?php
// Initialize the session.
// If you are using session_name("something"), don't forget it now!
session_start();
include "vars.php";

// or this would remove all the variable in the session 
session_unset(); 

session_destroy();
header("Location: $siteurl/order/")
?>
