<?php
include "vars.php";

setcookie( 'email', @$_COOKIE['email'], time() - 48 * 36000, '/', top_domain() );
setcookie( 'randid', @$_COOKIE['randid'], time() - 48 * 36000, '/', top_domain() );
setcookie( 'user_id', @$_COOKIE['user_id'], time() - 48 * 36000, '/', top_domain() );

setcookie( 'email', @$_COOKIE['email'], time() - 48 * 36000, '/' );
setcookie( 'randid', @$_COOKIE['randid'], time() - 48 * 36000, '/' );
setcookie( 'user_id', @$_COOKIE['user_id'], time() - 48 * 36000, '/' );

session_start();

// Unset all of the session variables.

session_unset(); 

// Finally, destroy the session.
session_destroy();
header("Location: $siteurl");
?>
