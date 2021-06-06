<?php
include "vars.php";
$dbcon = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

function echo_array($arr) {

	$text .= "<pre>";
	$text .= print_r($arr,true);
	$text .= "</pre>";

	return $text;

}

$jarr = array();
$jarr['base_price'] = $base_price;

$tod = mysqli_query($dbcon,"SELECT * FROM orders_types ORDER BY id ASC");
while ($row_tod = mysqli_fetch_array($tod)) {
	$jarr['tod'][] = $row_tod;
}

$sa =  mysqli_query($dbcon,"SELECT * FROM  orders_subject_areas ORDER BY id ASC");
while ($rowsa = mysqli_fetch_array($sa)) {
	$jarr['sa'][] = $rowsa;	
} 

$cu =  mysqli_query($dbcon,"SELECT * FROM  orders_currency ORDER BY id ASC");
while ($rowcu = mysqli_fetch_array($cu)) {
	$jarr['cu'][] = $rowcu;
} 

//echo json_encode($jarr);
//echo echo_array($jarr);

?>
