<?php
include "vars.php";
$dbcon = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
include('SMTPconfig.php');
include('SMTPClass.php'); 
$sqlurl = @mysqli_query($dbcon,"SELECT admin_url FROM orders_configuration WHERE  site_id = '$site_id' ");
 
 
$admin_url = @mysqli_fetch_assoc($sqlurl);
$admin_url=$admin_url['admin_url'];
  
/*$test = print_r($_POST, true);
$msg = '<pre>'.$test.'</pre>';
mail("chriskyeu@gmail.com","IPN success",$msg,$headers);


exit();

*/
 //echo "SELECT * FROM orders_deposits WHERE order_code = '$order_code' AND status = 'Not Approved' AND site_id = '$site_id' ";die;

$ap_status = $_POST['payment_status']; 
$ap_merchant = $_POST['business'];
//$ap_securitycode = urldecode($_POST['ap_securitycode']);
$ap_amount = $_POST['payment_gross'];
$ap_totalamount = $_POST['mc_gross'];
$order_code = $_POST['invoice'];
$order_id = $_POST['item_number'];
$log = serialize($_POST);
$time  = time();


@mysqli_query($dbcon,"INSERT INTO orders_deposits_ipn_logs (log, time) VALUES ('$log', '$time') ");

@mysqli_query($dbcon,"INSERT INTO test (payment_status, business,	payment_gross,mc_gross,invoice,item_number) VALUES ('$ap_status', '$ap_merchant','$ap_amount','$ap_totalamount','$order_code','$order_id') ");
 

$sql = @mysqli_query($dbcon,"SELECT * FROM orders_deposits WHERE order_code = '$order_code' AND status = 'Not Approved' AND site_id = '$site_id' ");
//$xemail = @mysql_result($sql,0,'email');
//$xamount = @mysql_result($sql,0,'amount');
////$order_id = @mysql_result($sql,0,'order_id');
//$xcurr = @mysql_result($sql,0,'currency');
 
$rowxemail = @mysqli_fetch_assoc($sql);
  $xemail=$rowxemail['email'];


//$xamount = @mysql_result($sql,0,'amount');
//$rowxamount = @mysqli_fetch_assoc($sql);
  $xamount=$rowxemail['amount'];

//$order_id = @mysql_result($sql,0,'order_id');
//$roworder_id = @mysqli_fetch_assoc($sql);
  $order_id=$rowxemail['order_id'];

//$xcurr = @mysql_result($sql,0,'currency');
//$rowxcurr = @mysqli_fetch_assoc($sql);
  $xcurr=$rowxemail['currency'];


//$xdtype = strtoupper(@mysql_result($sql,0,'dtype'));
//$rowxdtype = @mysqli_fetch_assoc($sql);
  $xdtype=strtoupper($rowxemail['dtype']);

$sqlx = @mysqli_query($dbcon,"SELECT * FROM orders_orders WHERE order_id = '$order_id' AND site_id = '$site_id' ");

/*$xtopic = @mysql_result($sqlx,0,'topic');
$xuser_id = @mysql_result($sqlx,0,'user_id');
*/
$rowxtopic = @mysqli_fetch_assoc($sqlx);
  $xtopic=$rowxtopic['topic'];

//$xuser_id = @mysql_result($sqlx,0,'user_id');
//$rowxuser_id = @mysqli_fetch_assoc($sqlx);
$xuser_id=$rowxtopic['user_id'];

//$xstatus = @mysql_result($sqlx,0,'status');
//$rowxstatus = @mysqli_fetch_assoc($sqlx);
$xstatus=$rowxtopic['status'];
//$xuser_level = @mysql_result($sqlx,0,'user_level');
//$rowxuser_level = @mysqli_fetch_assoc($sqlx);
  $xuser_level=$rowxtopic['user_level'];



$sqlu = @mysqli_query($dbcon,"SELECT * FROM orders_customers WHERE user_id = $xuser_id ");
//$xfirstname = @mysql_result($sqlu,0,"firstname");
$rowxfirstname = @mysqli_fetch_assoc($sqlu);
$xfirstname=$rowxfirstname['firstname'];



if ($ap_status == "Completed" && $ap_merchant == $payment_email && $xamount == $ap_totalamount) {

log_admin_action("Approve Deposit","Approve $xdtype deposit on order => $xcurr $xamount: $order_code ",$order_id,$site_id,"$xdtype IPN => SYSTEM");

$subject = $companyname." Deposit Success";

$customer_message = '

<div style="background:#F6F6F6;font-family:Verdana, Arial, Helvetica, sans-serif;font-size:12px;margin:0;padding:0">
<div style="background:#F6F6F6;font-family:Verdana, Arial, Helvetica, sans-serif;font-size:12px;margin:0;padding:0">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tbody><tr>
    <td align="center" valign="top" style="padding:20px 0 20px 0">
        <table bgcolor="#FFFFFF" cellspacing="0" cellpadding="10" border="0" width="650" style="border:1px solid #E0E0E0">
            
            <tbody><tr>
                <td valign="top"><a href="'.$siteurl.'/order/home.php" target="_blank"><img src="'.$email_logo.'" alt="'.$companyname.'" style="margin-bottom:10px" border="0"></a></td>
            </tr>
            
            <tr>
                <td valign="top">
                    <h1 style="font-size:22px;font-weight:normal;line-height:22px;margin:0 0 11px 0">Hello, '.$xname.'</h1>
                    <p style="font-size:12px;line-height:16px;margin:0">
                       Your deposit of <b>'.$xcurr.' '.$ap_totalamount.'</b> for  <a href="'.$siteurl.'/order/view.php?order_id='.$order_id.'">'.$xtopic.'</a> has been credited to your account <br />
			<br />
            </td></tr>
            <tr>
                <td bgcolor="#EAEAEA" align="center" style="background:#EAEAEA;text-align:center"><center><p style="font-size:12px;margin:0">Thank you, <strong>'.$companyname.'</strong></p></center></td>
            </tr>
        </tbody></table>
    </td>
</tr>
</tbody></table>
</div>
</div>';

	//require_once "Mail.php";
	$mail->addAddress($xemail); // Add a recipient

    $mail->Subject = $subject;

    $mail->MsgHTML($customer_message);

    $mail->IsHTML(true);	

    $result = $mail->Send();	

	/*$site_headers = array ('From' => $smtp_from,
		  'To' => $xemail,
		  'Subject' => $subject, 'Content-type' => 'text/html; charset=utf-8; format=flowed');
	$smtp = Mail::factory('smtp',
		  array ('host' => $smtp_host,
		    'port' =>  $smtp_port,
		    'auth' => true,
		    'username' => $smtp_user,
		    'password' => $smtp_pass));

	$mail = $smtp->send($xemail, $site_headers, $customer_message);

	if (PEAR::isError($mail)) {

	 	mail($xemail,$subject,$customer_message,$headers);

	 } */




$subject2 = "#$order_id Payment Received";

$admin_message = '

<div style="background:#F6F6F6;font-family:Verdana, Arial, Helvetica, sans-serif;font-size:12px;margin:0;padding:0">
<div style="background:#F6F6F6;font-family:Verdana, Arial, Helvetica, sans-serif;font-size:12px;margin:0;padding:0">
<table cellspacing="0" cellpadding="0" border="0" width="100%">

<tbody><tr>

    <td align="center" valign="top" style="padding:20px 0 20px 0">
        <table bgcolor="#FFFFFF" cellspacing="0" cellpadding="10" border="0" width="650" style="border:1px solid #E0E0E0">
            
            <tbody><tr>
                <td valign="top"><a href="'.$siteurl.'/" target="_blank"><img src="'.$email_logo.'" alt="'.$companyname.'" style="margin-bottom:10px" border="0"></a></td>

            </tr>
            
            <tr>
                <td valign="top">
                    <h1 style="font-size:22px;font-weight:normal;line-height:22px;margin:0 0 11px 0">Dear Admin,</h1>

                    <p style="font-size:12px;line-height:16px;margin:0">
                       Payment received for order  #<a href="'.$admin_url.'/wp-admin/view_order.php&order_id='.$order_id.'">'.$order_id.'</a>. Please assign it to a writer. <br />
			<br />
            </td></tr>
            <tr>

                <td bgcolor="#EAEAEA" align="center" style="background:#EAEAEA;text-align:center"><center><p style="font-size:12px;margin:0">Thank you, <strong>'.$companyname.'</strong></p></center></td>
            </tr>
        </tbody></table>
    </td>
</tr>

</tbody></table>
</div>
</div>';



		$email_x = explode(",", $siteemail);
		foreach ($email_x as $ekey => $evalue) {
			$email = trim($evalue);
			$mail->addAddress($email); // Add a recipient

			$mail->Subject = $subject2;
		
			$mail->MsgHTML($admin_message);
		
			$mail->IsHTML(true);	
		
			$result = $mail->Send();	
					/*$site_headers2 = array ('From' => $smtp_from,
					  'To' => $email,
					  'Subject' => $subject2, 'Content-type' => 'text/html; charset=utf-8; format=flowed');
			$smtp2 = Mail::factory('smtp',
				  array ('host' => $smtp_host,
					    'port' =>  $smtp_port,
					    'auth' => true,
					    'username' => $smtp_user,
					    'password' => $smtp_pass));

			$mail2 = $smtp2->send($email, $site_headers2, $admin_message);


			if (PEAR::isError($mail2)) {

			 	mail($email,$subject2,$admin_message,$headers);

			} */
		}
				

 

	@mysqli_query($dbcon,"UPDATE orders_deposits SET status = 'Approved' WHERE order_code = '$order_code' ");
	if (strpos($order_code, '~') !== false) { 
		$order_code_arr = explode("~",$order_code);
		foreach ($order_code_arr as $key => $add_id) {
			@mysqli_query($dbcon,"UPDATE orders_additional_payments SET status = 1 WHERE id = $add_id ");

		}

	} 

	if ($xstatus == 7 && $xuser_level == 7) {

		unset_inquiry($order_id,$site_id,"1","$xdtype IPN => SYSTEM");

	}

	@mysqli_query($dbcon,"UPDATE orders_orders SET payment_status = 1, status = 1 WHERE order_id = $order_id ");

}
 

///mysqli_close($dbcon);

echo "Thanks!";


 
?>
