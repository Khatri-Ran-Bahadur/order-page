<?php

/** this page is called from order form calculators.. it maintains the values posted from the order calculator into the main order form.**/

include "vars.php";
$_SESSION['doctype_x'] = @$_POST['typeofdocument'];
$_SESSION['urgency'] = @$_POST['urgency'];
$_SESSION['numpagess'] = @$_POST['numberofpages'];
$_SESSION['numpages'] = @$_POST['numberofpages'];
$_SESSION['curr'] = @$_POST['currency'];
$_SESSION['order_category'] =  @$_POST['subjectarea'];
$_SESSION['academic_level'] = @$_POST['academiclevel'];
$_SESSION['costperpage'] = @$_POST['costperpage'];
$_SESSION['total_h'] = @$_POST['ordercost'];


header("Location: " . $siteurl . "/order/");
exit();

?>
