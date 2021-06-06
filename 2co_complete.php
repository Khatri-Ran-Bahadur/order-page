<?php
include "vars.php";

$log = serialize($_POST);
$time  = time();

$ap_status = $_POST['message_type']; 
$ap_merchant = $_POST['vendor_id'];
$ap_amount = $_POST['invoice_list_amount'];

$order_id = $_POST['vendor_order_id'];
$order_code = "#$order_id";
$invoice_status = $_POST['invoice_status'];
$fraud_status = $_POST['fraud_status'];

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


//for fraud status
//if ($ap_status == "FRAUD_STATUS_CHANGED" && $ap_merchant == $xcheckout_sid && $xamount == $ap_amount && $invoice_status == "approved" && $fraud_status == "pass") {

//for order status 
//if ($ap_status == "ORDER_CREATED" && $ap_merchant == $xcheckout_sid && $xamount == $ap_amount && $invoice_status == "approved") {
if ($ap_status == "ORDER_CREATED" && $ap_merchant == $xcheckout_sid && $xamount == $ap_amount) {

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
                       Your deposit of <b>'.$xcurr.' '.$ap_amount.'</b> for  <a href="'.$siteurl.'/order/view.php?order_id='.$order_id.'">'.$xtopic.'</a> has been credited to your account <br />
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

/*


    if ($_POST['message_type'] == 'FRAUD_STATUS_CHANGED') {

        $insMessage = array();
        foreach ($_POST as $k => $v) {
        $insMessage[$k] = $v;
        }

        # Validate the Hash
        $hashSecretWord = $xcheckout_secretword; # Input your secret word
        $hashSid = $xcheckout_sid; #Input your seller ID (2Checkout account number)
        $hashOrder = $insMessage['sale_id'];
        $hashInvoice = $insMessage['invoice_id'];
        $StringToHash = strtoupper(md5($hashOrder . $hashSid . $hashInvoice . $hashSecretWord));

        if ($StringToHash != $insMessage['md5_hash']) {
            die('Hash Incorrect');
        }

        switch ($insMessage['fraud_status']) {
            case 'pass':
                # Do something when sale passes fraud review.
                break;
            case 'fail':
                # Do something when sale fails fraud review.
                break;
            case 'wait':
                # Do something when sale requires additional fraud review.
                break;
        }
    }

*/


/* sample INS 



Array
(
    [message_type] => ORDER_CREATED
    [message_description] => New order created
    [timestamp] => 2016-09-15 07:21:57
    [message_id] => 2
    [vendor_id] => 102920755
    [vendor_order_id] => 893520
    [invoice_id] => 106017335814
    [recurring] => 0
    [invoice_status] => approved
    [invoice_list_amount] => 1.24
    [invoice_usd_amount] => 1.24
    [invoice_cust_amount] => 1.24
    [auth_exp] => 2016-09-22
    [fraud_status] => wait
    [list_currency] => USD
    [cust_currency] => USD
    [payment_type] => credit card
    [sale_id] => 106017335805
    [sale_date_placed] => 2016-09-15 07:21:57
    [customer_ip] => 197.237.218.52
    [customer_ip_country] => Kenya
    [customer_first_name] => christopher
    [customer_last_name] => mutua
    [customer_name] => christopher mutua
    [customer_email] => chriskyeu@gmail.com
    [customer_phone] => 
    [ship_status] => 
    [ship_tracking_number] => 
    [ship_name] => 
    [ship_street_address] => 
    [ship_street_address2] => 
    [ship_city] => 
    [ship_state] => 
    [ship_postal_code] => 
    [ship_country] => 
    [bill_street_address] => 52565-00100 GPO Nairobi
    [bill_street_address2] => 
    [bill_city] => Nairobi
    [bill_state] => Nairobi
    [bill_postal_code] => 00100
    [bill_country] => KEN
    [item_count] => 1
    [item_name_1] => Order #893520
    [item_id_1] => 893520
    [item_list_amount_1] => 1.24
    [item_usd_amount_1] => 1.24
    [item_cust_amount_1] => 1.24
    [item_type_1] => bill
    [item_duration_1] => 
    [item_recurrence_1] => 
    [item_rec_list_amount_1] => 
    [item_rec_status_1] => 
    [item_rec_date_next_1] => 
    [item_rec_install_billed_1] => 
    [md5_hash] => 10DEE21131BEA6A2F2B6DF24820F2494
    [key_count] => 56
)


Array
(
    [message_type] => FRAUD_STATUS_CHANGED
    [message_description] => Order fraud status changed
    [timestamp] => 2016-09-15 09:29:02
    [message_id] => 5
    [vendor_id] => 102920755
    [vendor_order_id] => 893521
    [invoice_id] => 106017393141
    [recurring] => 0
    [invoice_status] => approved
    [invoice_list_amount] => 1.09
    [invoice_usd_amount] => 1.09
    [invoice_cust_amount] => 1.09
    [auth_exp] => 2016-09-22
    [fraud_status] => pass
    [list_currency] => USD
    [cust_currency] => USD
    [payment_type] => credit card
    [sale_id] => 106017393132
    [sale_date_placed] => 2016-09-15 09:06:38
    [customer_ip] => 197.237.218.52
    [customer_ip_country] => Kenya
    [customer_first_name] => christopher
    [customer_last_name] => mutua
    [customer_name] => christopher mutua
    [customer_email] => chriskyeu@gmail.com
    [customer_phone] => 
    [ship_status] => 
    [ship_tracking_number] => 
    [ship_name] => 
    [ship_street_address] => 
    [ship_street_address2] => 
    [ship_city] => 
    [ship_state] => 
    [ship_postal_code] => 
    [ship_country] => 
    [bill_street_address] => 52565-00100 GPO Nairobi
    [bill_street_address2] => 
    [bill_city] => Nairobi
    [bill_state] => Nairobi
    [bill_postal_code] => 00100
    [bill_country] => KEN
    [item_count] => 1
    [item_name_1] => Order #893521
    [item_id_1] => 893521
    [item_list_amount_1] => 1.09
    [item_usd_amount_1] => 1.09
    [item_cust_amount_1] => 1.09
    [item_type_1] => bill
    [item_duration_1] => 
    [item_recurrence_1] => 
    [item_rec_list_amount_1] => 
    [item_rec_status_1] => 
    [item_rec_date_next_1] => 
    [item_rec_install_billed_1] => 
    [md5_hash] => 41CDA5F8C7D104AB8B1BEF270C30E1DD
    [key_count] => 56
)

*/

?>
