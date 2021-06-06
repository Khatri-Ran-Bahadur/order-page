<?php

require_once($base_url_order.'/wp-config.php');

global $dbcon;
$dbcon = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$phone2_typex='';
  
if ($logged == 0) {
	$firstname = $_SESSION['firstname'];
	$lastname = '';
	$emailx = $_SESSION['emailx'];
	$passwordx = $_SESSION['passwordx'];
	$retype_email = $_SESSION['retype_email'];
	$country = $_SESSION['country'];
	$phone1 = @$_SESSION['phone1'];
	$phone1_type = @$_SESSION['phone1_type'];
	$phone2 = @$_SESSION['phone2'];
	$phone2_type = @$_SESSION['phone2_type'];
}

$sn_topic = $_SESSION['topic'];
$doctype_x = $_SESSION['doctype_x'];
$urgency = @$_SESSION['urgency'];
$o_interval = @$_SESSION['o_interval'];
$numpages = @$_SESSION['numpagess'];
$curr = @$_SESSION['curr'];
$additionalx =@$_SESSION['additionalx'];
$order_category = @$_SESSION['order_category'];
$costperpage = @$_SESSION['costperpage'];
$total_h = @$_SESSION['total_h'];
$total_x = @$_SESSION['total_x'];
$discount_percent_h = @$_SESSION['discount_percent_h'];
$discount_h = @$_SESSION['discount_h'];
$academic_level = @$_SESSION['academic_level'];
$writing_style = @$_SESSION['writing_style'];
$numberOfSources = @$_SESSION['numberOfSources'];
$langstyle = @$_SESSION['langstyle'];
$sn_details = @$_SESSION['details'];
$allow_night_calls  = @$_SESSION['allow_night_calls'];
$top10writerx = @$_SESSION['top10writerx'];
$vipsupportx = @$_SESSION['vipsupportx'];
$dtype = @$_SESSION['dtype'];

if($o_interval=="")
	$o_interval=0;

if($discount_percent_h=="")
	$discount_percent_h=0;

//doc type 
$sqldoctype = @mysqli_query($dbcon,"SELECT details FROM orders_types WHERE codex = $doctype_x");
$doctype_x='';
if($sqldoctype){
	$rowdoctype_x=mysqli_fetch_assoc($sqldoctype);
	$doctype_x=$rowdoctype_x['details'];
}

// academic level
$sqlacademic_level = @mysqli_query($dbcon,"SELECT details FROM orders_academic_level WHERE codex = $academic_level ");

$rowacademic_level=@mysqli_fetch_assoc($sqlacademic_level);
$academic_level=@$rowacademic_level['details'];

//writing styles
$sql7 = @mysqli_query($dbcon,"SELECT details FROM orders_writing_styles WHERE codex = $writing_style ");

$rowstylex=@mysqli_fetch_assoc($sql7);
$stylex=@$rowstylex['details'];

 
$sqlura = @mysqli_query($dbcon," SELECT * FROM orders_urgency WHERE codex = '$urgency' ");
$rowurgency_t=@mysqli_fetch_assoc($sqlura);
$urgency_t=@$rowurgency_t['time'];

//if urgency more or equal to 3 days we divide writer deaine by 3
$urgency_w=0;
if ($urgency_t >= 259200) {
	$urgency_w = $urgency_t / 3;
} else if ($urgency_t >=43200) {
	$urgency_w = $urgency_t / 2;
} else if ($urgency_t == 21600) {
	$urgency_w = 14400;
}
 
//currency stuff 
$sqlcurr = @mysqli_query($dbcon,"SELECT details FROM orders_currency WHERE codex = $curr ");
$rowcurr=@mysqli_fetch_assoc($sqlcurr);
$curr=@$rowcurr['details'];

//pages stuff
if ($o_interval == '') {
    $o_intervalx = 0;
    $interval = "Double Spaced";
    $sql4 = @mysqli_query($dbcon,"SELECT details FROM orders_double_spaced WHERE codex = $numpages ");
    $rownumpagesx=@mysqli_fetch_assoc($sql4);
    $numpagesx=@$rownumpagesx['details'];
} else if ($o_interval != '') {
    $o_intervalx = 1;
    $interval = "Single Spaced";
    $sql4 = @mysqli_query($dbcon,"SELECT details FROM orders_single_spaced WHERE codex = $numpages ");
    $rownumpagesx=@mysqli_fetch_assoc($sql4);
    $numpagesx=@$rownumpagesx['details'];
}

$urgencyxx = @mysqli_query($dbcon,"SELECT details FROM orders_urgency WHERE codex = $urgency ");
$rowurgencyx=@mysqli_fetch_assoc($urgencyxx);
$urgencyx=@$rowurgencyx['details'];


$secondsPerDay = ((24 * 60) * 60);
$timeStamp = time();
$daysUntilExpiry = $urgency;

$expiry = $timeStamp + $urgency_t;
$writer_expiry = $timeStamp + $urgency_w;

if ($logged == 0) { 
    //check whether they have ordered before and they have a profile
    $sql2 = @mysqli_query($dbcon,"SELECT * FROM orders_customers WHERE email = '$emailx' AND site_id = $site_id ");
	
	if (@mysqli_num_rows($sql2) == 0) {
		//generate password
		if ($_SESSION['passwordx'] == "") {
			$str =  strtoupper(md5(uniqid(rand(), true)));
			$len = strlen($str);
			$pass = substr("$str", 0, ($len-24));
			$passx = md5($pass);
		} else {
			$pass = $passwordx;
			$passx = md5($passwordx);
		}
		// after change last name to full name
		$lastname="";
		$sql3 = "INSERT INTO orders_customers (site_id, password, email, firstname, phone1, phone1_type, phone2, phone2_type, country) VALUES 
		('$site_id', '$pass', '$emailx', '$firstname', '$phone1', '$phone1_type', '$phone2', '$phone2_type', '$country') "; 
		
		@mysqli_query($dbcon,$sql3);
		$xuser_id = mysqli_insert_id($dbcon); 
		$user_id = $xuser_id;	
		
		

  		$sql = "INSERT INTO `orders_orders` (`user_id`, `site_id`, `topic`, `doctype_x`, `urgency`, `o_interval`, `numpages`, `curr`, `order_category`, `costperpage`, `total_h`, `total_x`, `discount_percent_h`, `discount_h`, `academic_level`, `style`, `numberOfSources`, `langstyle`, `details`, `allow_night_calls`, `time`, `expiry`, `writer_expiry`, top10writer, vipsupport) VALUES ('$xuser_id', '$site_id', '$sn_topic', '$doctype_x', '$urgency', '$o_interval', '$numpages', '$curr', '$order_category', '$costperpage', '$total_h', '$total_x', '$discount_percent_h', '$discount_h', '$academic_level', '$writing_style', '$numberOfSources', '$langstyle', '$sn_details', '$allow_night_calls', '$timeStamp', '$expiry', '$writer_expiry',  '$top10writerx', '$vipsupportx') ";  
		 
		mysqli_query($dbcon,$sql);
		$order_id = mysqli_insert_id($dbcon); 

		//make order directory on server..
		$dirName = $sitedir."/attachments/".$order_id;
		@mkdir($dirName,0777);

		include "customeremail.php";

		//phone types first:
		$sql0 = @mysqli_query($dbcon,"SELECT details FROM orders_phone_type WHERE codex = $phone1_type ");
		$rowphone1_typex=@mysqli_fetch_assoc($sql0);
		$phone1_typex=$rowphone1_typex['details'];		
        
		if ($phone2_type) {
    		$sql01 = @mysqli_query($dbcon,"SELECT details FROM orders_phone_type WHERE codex = $phone2_type ");
    		$rowphone2_typex=@mysqli_fetch_assoc($sql01);
    		$phone2_typex=$rowphone2_typex['details'];
		
		}

		//country code and phone number
		$sql1 = @mysqli_query($dbcon,"SELECT details FROM orders_country_codes WHERE codex = $country ");
		$rowphone_full=@mysqli_fetch_assoc($sql1);
		$phone_full = $rowphone_full['details'].'-'.$phone1.' ('.$phone1_typex.')';
		if (!$phone2) {
		    $alternative_phone = 'N/A';
		} else {
		    $alternative_phone = $rowphone_full['details'].'-'.$phone2.' ('.$phone2_typex.')';
		}
		//subject types
		$sql6 = @mysqli_query($dbcon,"SELECT details FROM orders_subject_areas WHERE codex = $order_category ");
		$roworder_categoryx=@mysqli_fetch_assoc($sql6);
		$order_categoryx=$roworder_categoryx['details'];

		require("emailadmin.php");
		
		$randid = md5(rand());
	
		@mysqli_query($dbcon," UPDATE orders_customers SET randid = '$randid', lastlogin = '". time() ."' WHERE email = '$emailx'  AND site_id = $site_id  ");	
		
		
		setcookie( "email", @$_SESSION['email'], time() - 3600, '/' );
		setcookie( "email", $emailx, 0, '/' );
		setcookie( "randid", @$randid, 0, '/' );
		setcookie( "user_id", $xuser_id, 0, '/' );
		
		require($base_url_order."/order/clear2.php");
		
		if ($order_form_version == 1) {
			header("Location:  $siteurl/order/paynow.php?order_id=$order_id");
			exit();

		} else if ($order_form_version == 2) {
			header("Location: $siteurl/order/paynow_auto.php?order_id=$order_id&dtype=$dtype");
			exit();
		}

	} else {
		//get customer details
		$rowcustomer=@mysqli_fetch_assoc($sql2);
		
		$emailx = $rowcustomer["email"];
		$xuser_id = $rowcustomer["user_id"]; 
		$firstname = $rowcustomer["firstname"]; 
		$lastname = $rowcustomer["lastname"]; 
		$pass = $rowcustomer["password"]; 
		$phone1 =$rowcustomer["phone1"];  
		$phone1_type = $rowcustomer["phone1_type"]; 
		$phone2 = $rowcustomer["phone2"]; 
		$phone2_type = $rowcustomer["phone2_type"]; 
		$country = $rowcustomer["country"]; 
		

		$sql = "INSERT INTO `orders_orders` (`user_id`, `site_id`, `topic`, `doctype_x`, `urgency`, `o_interval`, `numpages`, `curr`, `order_category`, `costperpage`, `total_h`, `total_x`, `discount_percent_h`, `discount_h`, `academic_level`, `style`, `numberOfSources`, `langstyle`, `details`, `allow_night_calls`, `time`, `expiry`, `writer_expiry`, top10writer, vipsupport) VALUES ('$xuser_id', '$site_id', '$sn_topic', '$doctype_x', '$urgency', '$o_interval', '$numpages', '$curr', '$order_category', '$costperpage', '$total_h', '$total_x', '$discount_percent_h', '$discount_h', '$academic_level', '$writing_style', '$numberOfSources', '$langstyle', '$sn_details', '$allow_night_calls', '$timeStamp', '$expiry', '$writer_expiry',  '$top10writerx', '$vipsupportx') ";  

		@mysqli_query($dbcon,$sql);
		$order_id = @mysqli_insert_id($dbcon); 
		
		//make order directory on server..
		$dirName = $sitedir."/attachments/".$order_id;
		@mkdir($dirName,0777);

		//phone types first:
		$sql0 = @mysqli_query($dbcon,"SELECT details FROM orders_phone_type WHERE codex = $phone1_type ");
		$rowphone1_typex=@mysqli_fetch_assoc($sql0);
		$phone1_typex=@$rowphone1_typex['details'];

		if ($phone2_type) {
			$sql01 = @mysqli_query($dbcon,"SELECT details FROM orders_phone_type WHERE codex = $phone2_type ");
			$rowphone2_typex=@mysqli_fetch_assoc($sql01);
				$phone2_typex=@$rowphone2_typex['details'];
		}

		//country code and phone number
		$sql1 = @mysqli_query($dbcon,"SELECT details FROM orders_country_codes WHERE codex = $country ");
		$rowphone_full=@mysqli_fetch_assoc($sql1);
		$phone_full =$rowphone_full["details"].'-'.$phone1.' ('.$phone1_typex.')';
		if (!$phone2) {
			$alternative_phone = 'N/A';
		} else {
			$alternative_phone = $rowphone_full["details"].'-'.$phone2.' ('.$phone2_typex.')';
		}

		//subject types
		$sql6 = @mysqli_query($dbcon,"SELECT details FROM orders_subject_areas WHERE codex = $order_category ");
		$roworder_categoryx=@mysqli_fetch_assoc($sql6);
		$order_categoryx=$roworder_categoryx['details'];

		require("customeremail.php");

		$user_id = $xuser_id;
		require("emailadmin.php");
			setcookie( "email", @$_COOKIE['email'], time() - 3600, '/' );
			setcookie( "email", $emailx, 0, '/' );
			include $_SERVER['DOCUMENT_ROOT']."/order/clear2.php";
		if ($order_form_version == 1) {
			header("Location: " . $siteurl . "/order/paynow.php?order_id=$order_id");
			exit();

		} else if ($order_form_version == 2) {
			header("Location: " . $siteurl . "/order/paynow_auto.php?order_id=$order_id&dtype=$dtype");
			exit();
		}
	

	}

} else if ($logged == 1) {
	$sqlx = @mysqli_query($dbcon," SELECT * FROM orders_customers WHERE email = '$email'  AND site_id = $site_id ");
	$emailx = $email; //from cookie
	$xuser_id = $user_id; //from cookie
	
	$rowcustomer=@mysqli_fetch_assoc($sqlx);	
	$firstname = $rowcustomer["firstname"]; 
	$lastname = $rowcustomer["lastname"]; 
	$pass = $rowcustomer["password"]; 
	$phone1 =$rowcustomer["phone1"];  
	$phone1_type = $rowcustomer["phone1_type"]; 
	$phone2 = $rowcustomer["phone2"]; 
	$phone2_type = $rowcustomer["phone2_type"]; 
	$country = $rowcustomer["country"]; 

	//phone types first:
	$sql0 = @mysqli_query($dbcon,"SELECT details FROM orders_phone_type WHERE codex = $phone1_type ");
	$rowphone1_typex=@mysqli_fetch_assoc($sql0);
	$phone1_typex=$rowphone1_typex['details'];

	if ($phone2_type) {
		$sql01 = @mysqli_query($dbcon,"SELECT details FROM orders_phone_type WHERE codex = $phone2_type ");
		$rowphone2_typex=@mysqli_fetch_assoc($sql01);
		$phone2_typex=$rowphone2_typex['details'];
	}

	//country code and phone number
	$sql1 = @mysqli_query($dbcon,"SELECT details FROM orders_country_codes WHERE codex = $country ");
	$rowphone_full=@mysqli_fetch_assoc($sql1);
		$phone_full =$rowphone_full["details"].'-'.$phone1.' ('.$phone1_typex.')';
	 
	if (!$phone2) {
		$alternative_phone = 'N/A';
	} else {
		$alternative_phone = $rowphone_full["details"].'-'.$phone2.' ('.$phone2_typex.')';
	}

	//subject types
	$sql6 = @mysqli_query($dbcon,"SELECT details FROM orders_subject_areas WHERE codex = $order_category ");
	$roworder_categoryx=@mysqli_fetch_assoc($sql6);
	$order_categoryx=$roworder_categoryx['details'];

	//insert stuff into DB
	$sql = "INSERT INTO `orders_orders` (`user_id`, `site_id`, `topic`, `doctype_x`, `urgency`, `o_interval`, `numpages`, `curr`, `order_category`, `costperpage`, `total_h`, `total_x`, `discount_percent_h`, `discount_h`, `academic_level`, `style`, `numberOfSources`, `langstyle`, `details`, `allow_night_calls`, `time`, `expiry`, `writer_expiry`, top10writer, vipsupport) VALUES ('$xuser_id', '$site_id', '$sn_topic', '$doctype_x', '$urgency', '$o_interval', '$numpages', '$curr', '$order_category', '$costperpage', '$total_h', '$total_x', '$discount_percent_h', '$discount_h', '$academic_level', '$writing_style', '$numberOfSources', '$langstyle', '$sn_details', '$allow_night_calls', '$timeStamp', '$expiry', '$writer_expiry',  '$top10writerx', '$vipsupportx') ";   

	mysqli_query($dbcon,$sql);
 	$order_id = @mysqli_insert_id($dbcon);  

	//make order directory on server..
	$dirName = $sitedir."/attachments/".$order_id;
	@mkdir($dirName,0777);

	
	require("customeremail.php");
	require("emailadmin.php");
	require($base_url_order."/order/clear2.php");

	if ($order_form_version == 1) {
		//$text .= $sql;
		$text .= "Thank you for placing an Order with us.<br />You will be redirected shortly to complete the order.
		<META HTTP-EQUIV=REFRESH CONTENT=\"1; URL= $siteurl/order/paynow.php?order_id=$order_id\">";

	} else if ($order_form_version == 2) {
		//$text .= $sql;
		$text .= "Thank you for placing an Order with us.<br />You will be redirected shortly to complete the order.
		<META HTTP-EQUIV=REFRESH CONTENT=\"1; URL= $siteurl/order/paynow_auto.php?order_id=$order_id&dtype=$dtype\">";
	}
	
}

?>
