<?php

require_once("../wp-config.php");
$base_url_order=$_SERVER['DOCUMENT_ROOT']."/bestessaywriter/";
$text=''; 
$wp->init();
$wp->register_globals();
if( !session_id() ) session_start();
?>
