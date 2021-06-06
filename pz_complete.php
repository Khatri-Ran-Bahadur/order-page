<?php
include "vars.php";

/*
$test = print_r($_POST, true);
$msg = '<pre>'.$test.'</pre>';
mail("chriskyeu@gmail.com","IPN success",$msg,$headers);
*/
/*
exit();

*/


/*
	//The value is the Security Code generated from the IPN section of your Payza account. Please change it to yours.
	define("IPN_SECURITY_CODE", "KEJlbcHwFktpYZLq");
	define("MY_MERCHANT_EMAIL", "$alertpay_email");
*/
	//Setting information about the transaction
	$receivedSecurityCode = $_POST['ap_securitycode'];
	$receivedMerchantEmailAddress = $_POST['ap_merchant'];	
	$transactionStatus = $_POST['ap_status'];
	$testModeStatus = $_POST['ap_test'];	 
	$purchaseType = $_POST['ap_purchasetype'];
	$totalAmountReceived = $_POST['ap_totalamount'];
	$feeAmount = $_POST['ap_feeamount'];
    $netAmount = $_POST['ap_netamount'];
	$transactionReferenceNumber = $_POST['ap_referencenumber'];
	$currency = $_POST['ap_currency']; 	
	$transactionDate= $_POST['ap_transactiondate'];
	$transactionType= $_POST['ap_transactiontype'];
	
	//Setting the customer's information from the IPN post variables
	$customerFirstName = $_POST['ap_custfirstname'];
	$customerLastName = $_POST['ap_custlastname'];
	$customerAddress = $_POST['ap_custaddress'];
	$customerCity = $_POST['ap_custcity'];
	$customerState = $_POST['ap_custstate'];
	$customerCountry = $_POST['ap_custcountry'];
	$customerZipCode = $_POST['ap_custzip'];
	$customerEmailAddress = $_POST['ap_custemailaddress'];
	
	//Setting information about the purchased item from the IPN post variables
	$myItemName = $_POST['ap_itemname'];
	$myItemCode = $_POST['ap_itemcode'];
	$myItemDescription = $_POST['ap_description'];
	$myItemQuantity = $_POST['ap_quantity'];
	$myItemAmount = $_POST['ap_amount'];
	
	//Setting extra information about the purchased item from the IPN post variables
	$additionalCharges = $_POST['ap_additionalcharges'];
	$shippingCharges = $_POST['ap_shippingcharges'];
	$taxAmount = $_POST['ap_taxamount'];
	$discountAmount = $_POST['ap_discountamount'];
	 
	//Setting your customs fields received from the IPN post variables
	$myCustomField_1 = $_POST['apc_1'];
	$myCustomField_2 = $_POST['apc_2'];
	$myCustomField_3 = $_POST['apc_3'];
	$myCustomField_4 = $_POST['apc_4'];
	$myCustomField_5 = $_POST['apc_5'];
	$myCustomField_6 = $_POST['apc_6'];

/*
	if ($receivedMerchantEmailAddress != MY_MERCHANT_EMAIL) {
		// The data was not meant for the business profile under this email address.
		// Take appropriate action 
	}
	else {	
		//Check if the security code matches
		if ($receivedSecurityCode != IPN_SECURITY_CODE) {
			// The data is NOT sent by Payza.
			// Take appropriate action.
		}
		else {
			if ($transactionStatus == "Success") {
				if ($testModeStatus == "1") {
					// Since Test Mode is ON, no transaction reference number will be returned.
					// Your site is currently being integrated with Payza IPN for TESTING PURPOSES
					// ONLY. Don't store any information in your production database and 
					// DO NOT process this transaction as a real order.
				}
				else {
					// This REAL transaction is complete and the amount was paid successfully.
					// Process the order here by cross referencing the received data with your database. 														
					// Check that the total amount paid was the expected amount.
					// Check that the amount paid was for the correct service.
					// Check that the currency is correct.
					// ie: if ($totalAmountReceived == 50) ... etc ...
					// After verification, update your database accordingly.
				}			
			}
			else {
					// Transaction was cancelled or an incorrect status was returned.
					// Take appropriate action.
			}
		}
	}



*/


$order_code = "#$myItemCode";
$order_id = $myItemCode;
$log = serialize($_POST);
$time  = time();

@mysql_query("INSERT INTO orders_deposits_ipn_logs (log, time) VALUES ('$log', '$time') ");

$sql = @mysql_query("SELECT * FROM orders_deposits WHERE order_code = '$order_code' AND status = 'Not Approved' AND site_id = '$site_id' ");
$xemail = @mysql_result($sql,0,'email');
$xamount = @mysql_result($sql,0,'amount');
//$order_id = @mysql_result($sql,0,'order_id');
$xcurr = @mysql_result($sql,0,'currency');
$xdtype = strtoupper(@mysql_result($sql,0,'dtype'));

$sqlx = @mysql_query("SELECT * FROM orders_orders WHERE order_id = '$order_id' AND site_id = '$site_id' ");
$xtopic = @mysql_result($sqlx,0,'topic');
$xuser_id = @mysql_result($sqlx,0,'user_id');
$xstatus = @mysql_result($sqlx,0,'status');
$xuser_level = @mysql_result($sqlx,0,'user_level');

$sqlu = @mysql_query("SELECT * FROM orders_customers WHERE user_id = $xuser_id ");
$xfirstname = @mysql_result($sqlu,0,"firstname");



if ( $transactionStatus == "Success" && $receivedMerchantEmailAddress == $alertpay_email ) {

log_admin_action("Approve Deposit","Approve $xdtype deposit on order => $xcurr $xamount: $order_code ",$order_id,$site_id,"$xdtype IPN => SYSTEM");

$subject = "$companyname Deposit Success (#$order_id)";

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
                    <h1 style="font-size:22px;font-weight:normal;line-height:22px;margin:0 0 11px 0">'.$xfirstname.',</h1>
                    <p style="font-size:12px;line-height:16px;margin:0">
                       Your deposit of <b>'.$xcurr.' '.$totalAmountReceived.'</b> for  <a href="'.$siteurl.'/order/view.php?order_id='.$order_id.'">'.$xtopic.'</a> has been credited to your account <br />
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

	require_once "Mail.php";
	$site_headers = array ('From' => $smtp_from,
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

	 } 




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
                       Payment received for order  #<a href="'.$admin_url.'/view_order.php&order_id='.$order_id.'">'.$order_id.'</a>. Please assign it to a writer. <br />
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


					//email Admins
					if (is_array($multiple_admin_email)) {


						//$email_x = explode(",", $siteemail);
						foreach ($multiple_admin_email as $ekey => $evalue) {
							$admin_to_email = trim($evalue);


							$site_headers = array ('From' => $smtp_from,
								  'To' => $admin_to_email,
								  'Subject' => $subject2, 'Content-type' => 'text/html; charset=utf-8; format=flowed');
							$smtp = Mail::factory('smtp',
								  array ('host' => $smtp_host,
								    'port' =>  $smtp_port,
								    'auth' => true,
								    'username' => $smtp_user,
								    'password' => $smtp_pass));

							$mail = $smtp->send($admin_to_email, $site_headers, $admin_message);

							if (PEAR::isError($mail)) {
								mail($admin_to_email,$subject2,$admin_message,$headers);
							 } 
					
						}




					} else {


						$admin_to_email = trim($multiple_admin_email);

						if($admin_to_email != '') {
							$site_headers = array ('From' => $smtp_from,
									  'To' => $admin_to_email,
									  'Subject' => $subject2, 'Content-type' => 'text/html; charset=utf-8; format=flowed');
							$smtp = Mail::factory('smtp',
									  array ('host' => $smtp_host,
									    'port' =>  $smtp_port,
									    'auth' => true,
									    'username' => $smtp_user,
									    'password' => $smtp_pass));

							$mail = $smtp->send($admin_to_email, $site_headers, $admin_message);

							if (PEAR::isError($mail)) {
								mail($admin_to_email,$subject2,$admin_message,$headers);
							} 

						}


					}


					//email support

					if (is_array($multiple_support_email)) {


						//$email_x = explode(",", $siteemail);
						foreach ($multiple_support_email as $ekey => $evalue) {
							$support_to_email = trim($evalue);


							$site_headers = array ('From' => $smtp_from,
								  'To' => $support_to_email,
								  'Subject' => $subject2, 'Content-type' => 'text/html; charset=utf-8; format=flowed');
							$smtp = Mail::factory('smtp',
								  array ('host' => $smtp_host,
								    'port' =>  $smtp_port,
								    'auth' => true,
								    'username' => $smtp_user,
								    'password' => $smtp_pass));

							$mail = $smtp->send($support_to_email, $site_headers, $admin_message);

							if (PEAR::isError($mail)) {
								mail($support_to_email,$subject2,$admin_message,$headers);
							 } 
					
						}




					} else {


						$support_to_email = trim($multiple_support_email);

						if($support_to_email != '') {

							$site_headers = array ('From' => $smtp_from,
									  'To' => $support_to_email,
									  'Subject' => $subject2, 'Content-type' => 'text/html; charset=utf-8; format=flowed');
							$smtp = Mail::factory('smtp',
									  array ('host' => $smtp_host,
									    'port' =>  $smtp_port,
									    'auth' => true,
									    'username' => $smtp_user,
									    'password' => $smtp_pass));

							$mail = $smtp->send($support_to_email, $site_headers, $admin_message);

							if (PEAR::isError($mail)) {
								mail($support_to_email,$subject2,$admin_message,$headers);
							} 
						}

					}


		/*
		$email_x = explode(",", $siteemail);
		foreach ($email_x as $ekey => $evalue) {
			$email = trim($evalue);
			$site_headers2 = array ('From' => $smtp_from,
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

			} 
		}
		*/		

 

	@mysql_query("UPDATE orders_deposits SET status = 'Approved' WHERE order_code = '$order_code' ");
	if (strpos($order_code, '~') !== false) { 
		$order_code_arr = explode("~",$order_code);
		foreach ($order_code_arr as $key => $add_id) {
			mysql_query("UPDATE orders_additional_payments SET status = 1 WHERE id = $add_id ");

		}

	} 

	if ($xstatus == 7 && $xuser_level == 7) {

		unset_inquiry($order_id,$site_id,"1","$xdtype IPN => SYSTEM");

	}

	@mysql_query("UPDATE orders_orders SET payment_status = 1, status = 1 WHERE order_id = $order_id ");

}

mysql_close();

echo "Thanks!";


 
?>
