<?php

$top10writer = @$_REQUEST['vas_id'][0];
$vipsupport = @$_REQUEST['vas_id'][1];


//phone types first:
$sql0 = @mysqli_query($dbcon, "SELECT details FROM orders_phone_type WHERE codex = '".$_REQUEST['phone1_type']."' ");
$rowphone1_typex=@mysqli_fetch_assoc($sql0);
$phone1_typex=@$rowphone1_typex['details'];



if (@$phone2_type) {
	$sql01 = @mysqli_query($dbcon,"SELECT details FROM orders_phone_type WHERE codex = '".$_REQUEST['phone2_type']."' ");
	$rowphone2_typex=@mysqli_fetch_assoc($sql01);
	$phone2_typex=@$rowphone2_typex['details'];
}

//country code and phone number
$sql1 = @mysqli_query($dbcon,"SELECT details FROM orders_country_codes WHERE codex = '".$_REQUEST['country']."' ");
$rowphone_full=@mysqli_fetch_assoc($sql1);
 
$phone_full = $rowphone_full['details'].'-'.$_REQUEST['phone1'].' ('.$phone1_typex.')';
if (!$_REQUEST['phone2']) {
	$alternative_phone = 'N/A';
} else {
	$alternative_phone =@$rowphone_full['details'].'-'.@$_REQUEST['phone2'].' ('.@$phone2_typex.')';
}

//doc type 
$sqldoctype = @mysqli_query($dbcon,"SELECT details FROM orders_types WHERE codex = '".$_REQUEST['doctype_x']."' ");

$rowdoctype=@mysqli_fetch_assoc($sqldoctype);
$doctype=$rowdoctype['details'];
//urgency
$sql2 = @mysqli_query($dbcon,"SELECT details FROM orders_urgency WHERE codex = '".$_REQUEST['urgency']."' ");
//$urgencyx = @mysql_result($sql2,0,"details");
$rowurgencyx=@mysqli_fetch_assoc($sql2);
$urgencyx=@$rowurgencyx['details'];


//pages stuff
if (isset($_REQUEST['o_interval']) && $_REQUEST['o_interval'] == '') {
	$o_intervalx = 0;
	$interval = "Double Spaced";
	$sql4 = @mysqli_query($dbcon,"SELECT details FROM orders_double_spaced WHERE codex = '".$_REQUEST['numpages']."' ");
	$rownumpagesx=@mysqli_fetch_assoc($sql4);
	$numpagesx=$rownumpagesx['details'];
} else if (isset($_REQUEST['o_interval']) && $_REQUEST['o_interval'] != '') {
	$o_intervalx = 1;
	$interval = "Single Spaced";
	$sql4 = @mysqli_query($dbcon,"SELECT details FROM orders_single_spaced WHERE codex = '".$_REQUEST['numpages']."' ");
	$rownumpagesx=@mysqli_fetch_assoc($sql4);
	$numpagesx=@$rownumpagesx['details'];
}

//currency stuff 
$sqlcurr = @mysqli_query($dbcon,"SELECT details FROM orders_currency WHERE codex = '".$_REQUEST['curr']."' ");
$rowcurrency=@mysqli_fetch_assoc($sqlcurr);
$currency=@$rowcurrency['details'];

//language styles
if ($_REQUEST['langstyle'] == 1) {
	$langstylex = 'English (U.S.)';
} else if ($_REQUEST['langstyle'] == 2) {
	$langstylex = 'English (U.K.)';
}

	//subject types
	$sql6 = @mysqli_query($dbcon,"SELECT details FROM orders_subject_areas WHERE codex = '".$_REQUEST['order_category']."' ");
	$roworder_categoryx=@mysqli_fetch_assoc($sql6);
	$order_categoryx=$roworder_categoryx['details'];

	//writing styles
	$sql7 = @mysqli_query($dbcon,"SELECT details FROM orders_writing_styles WHERE codex = '".$_REQUEST['writing_style']."' ");
	//$stylex = @mysql_result($sql7,0,"details");
	$rowstylex=@mysqli_fetch_assoc($sql7);
	$stylex=@$rowstylex['details'];

	// academic level
	$sqlacademic_level = @mysqli_query($dbcon,"SELECT details FROM orders_academic_level WHERE codex = '".$_REQUEST['academic_level']."' ");
	//$academic_levelx = @mysql_result($sqlacademic_level,0,"details");
	$rowacademic_levelx=@mysqli_fetch_assoc($sqlacademic_level);
	$academic_levelx=@$rowacademic_levelx['details'];

//top 10 writer
if ($top10writer == 3) {
	$top10writerx = 'Yes';
} else {
	$top10writerx = 'N/A';
}

//vip support
if (($vipsupport == 6) || ($top10writer == 6)) {
	$vipsupportx = 'Yes';
} else {
	$vipsupportx = 'N/A';
}

//allow_night_calls
if (isset($get['allow_night_calls']) && $get['allow_night_calls']) {
	$allow_night_callsx = "Yes";
} else {
	$allow_night_callsx = "No";
}


//decode order_cost
$ordercostx = base64_decode($_REQUEST['MMNBGFREWQASCXZSOPJHGVNMTIuOTU']);
$costperpagex = base64_decode($_REQUEST['MTIuOTUYGREXGHNMKJGT23467GGFDSSSbbbbbIOK']);

@$text .= '<div id="orderDetails">

<h2>ORDER PREVIEW</h2>
<form name="orderNow" id="orderpreview" action="" method="POST">
	<table border="0" width="100%" cellspacing="0" cellpading="0">';

if ($logged == 0) {
$text .= '
		<tr bgcolor="#FCFCFC">
			<td colspan="3"><b>First Name:</b> '.$_REQUEST['firstname'].' </td>	
		</tr>
		<tr bgcolor="#FCFCFC">
			<td><b>Email:</b> '.$_REQUEST['emailx'].'</td>
			<td><b>Phone1:</b> '.$phone_full.'</td>				
			<td></td>			
		</tr>
		<tr bgcolor="#FCFCFC">
			<td><b>Phone2:</b> '.$alternative_phone.'</td>
			<td></td>				
			<td></td>			
		</tr>
';
}

$text .= '
			<tr bgcolor="#FCFCFC">
				<td><b>Topic:</b> '.stripslashes(nl2br(@$_POST['topic'])).'</td>
				<td><b>Type of document:</b> '.$doctype. '</td>
				<td><b>Academic Level:</b>'.$academic_levelx. '</td>
			
			</tr>
			<tr bgcolor="#FCFCFC">
				<td><b>Writing Style:</b> '.$stylex. ' </td>
				<td><b>Subject Area</b>: '.$order_categoryx.'</td>
				<td><b>Urgency:</b> '.$urgencyx.'</td>			
			</tr>
			<tr bgcolor="#FCFCFC">
				<td><b>Number of Pages: </b> '.@$numpagesx.'</td>
				<td><b>Number of sources: </b>'.$_REQUEST['numberOfSources']. '</td>
				<td><b>Language Style:</b> '.$langstylex. '</td>	
			</tr>
			<tr bgcolor="#FCFCFC">

				<td><b>Written by 10 writers:</b> '.$top10writerx. '</td>
				<td><b>Editor Services:</b> '.$vipsupportx. '</td>
				<td><b>Allow night calls:</b> '.$allow_night_callsx. '</td>

			</tr>
			<tr bgcolor="#FCFCFC">
				<td><b>Cost per page: </b>'.$currency.' '.$costperpagex. '</td>
				<td><b>Discount: </b>'.$_REQUEST['discount_percent_h']. '% ('.$currency.' '.$_REQUEST['discount_h']. ')</td>
				<td><b>Total: </b>'.$currency.' '.$ordercostx. '</td>
			</tr>

		      	<tr><td colspan="3"><strong>Details:</strong> </td> </tr>
			<tr><td colspan="3">'.stripslashes(nl2br(@$_POST['details'])). '</td> </tr>
              	</table>
		<p><a href="'.$siteurl.'/order/" class="submit button">Back</a>&nbsp;&nbsp;&nbsp;&nbsp;<input name="preview" type="submit" value="Complete" class="submit button"  /></p>
</form>
</div><div class="rednotice">Press COMPLETE to proceed to payment page or back to begin again....</div>
';


if ($logged == 0) { 
$_SESSION['firstname'] = $_REQUEST['firstname'];
// $_SESSION['lastname'] = $_REQUEST['lastname'];
$_SESSION['emailx'] = $_REQUEST['emailx'];
$_SESSION['retype_email'] = $_REQUEST['retype_email'];
$_SESSION['passwordx'] = @$_REQUEST['passwordx'];
$_SESSION['country'] = $_REQUEST['country'];
$_SESSION['phone1'] = $_REQUEST['phone1'];
$_SESSION['phone1_type'] = $_REQUEST['phone1_type'];
$_SESSION['phone2'] = @$_REQUEST['phone2'];
$_SESSION['phone2_type'] = @$_REQUEST['phone2_type'];

}

$_SESSION['topic'] = $_REQUEST['topic'];
$_SESSION['doctype_x'] = $_REQUEST['doctype_x'];
$_SESSION['urgency'] = $_REQUEST['urgency'];
$_SESSION['o_interval'] = isset($_REQUEST['o_interval'])?$_REQUEST['o_interval']:'';
$_SESSION['numpages'] = isset($_REQUEST['numpages'])?$_REQUEST['numpages']:0;
$_SESSION['numpagess'] = @$_REQUEST['numpages'];
$_SESSION['curr'] = $_REQUEST['curr'];
//$_SESSION['additionalx'] = $_REQUEST['additionalx'];
$_SESSION['order_category'] = $_REQUEST['order_category'];
$_SESSION['costperpage'] = $costperpagex;
$_SESSION['total_h'] = $ordercostx;
$_SESSION['total_x'] = $currency.' '.$ordercostx;
$_SESSION['discount_percent_h'] = $_REQUEST['discount_percent_h'];
$_SESSION['discount_h'] = $_REQUEST['discount_h'];
$_SESSION['academic_level'] = $_REQUEST['academic_level'];
$_SESSION['writing_style'] = $_REQUEST['writing_style'];
$_SESSION['numberOfSources'] = $_REQUEST['numberOfSources'];
$_SESSION['langstyle'] = $_REQUEST['langstyle'];
$_SESSION['details'] = @$_REQUEST['details'];
$_SESSION['top10writerx'] = $top10writerx;
$_SESSION['vipsupportx'] = $vipsupportx;
$_SESSION['allow_night_calls'] = $allow_night_callsx;
$_SESSION['accept'] = $_REQUEST['accept'];
$_SESSION['dtype'] = @$_POST['dtype'];
$_SESSION['lblCustomerSavings'] = @mysqli_real_escape_string(@$_REQUEST['lblCustomerSavings']);

?>
