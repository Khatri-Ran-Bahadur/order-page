<?php
/*IPN Sample code
=================
This code gets all the parameters of "IPN", checks if the request came from a plimus server (by IP) and sends the data to the system log.
Revision: 1.0.4 | 05/25/2009
*/
$plimusIps = array("62.219.121.253", "209.128.93.248", "72.20.107.242", "209.128.93.229", "209.128.93.98", "209.128.93.230", "209.128.93.245", "209.128.93.104", "209.128.93.105", "209.128.93.107", "209.128.93.108", "209.128.93.242", "209.128.93.243", "209.128.93.254", "62.216.234.216", "62.216.234.218", "62.216.234.219", "62.216.234.220", "127.0.0.1","localhost", "209.128.104.18", "209.128.104.19", "209.128.104.20", "209.128.104.21", "209.128.104.22", "209.128.104.23", "209.128.104.24", "209.128.104.25", "209.128.104.26", "209.128.104.27", "209.128.104.28", "209.128.104.29", "209.128.104.30", "209.128.104.31", "209.128.104.32", "209.128.104.33", "209.128.104.34", "209.128.104.35", "209.128.104.36", "209.128.104.37", "99.186.243.9", "99.186.243.10", "99.186.243.11", "99.186.243.12", "99.186.243.13", "99.180.227.233", "99.180.227.234", "99.180.227.235", "99.180.227.236", "99.180.227.237");

//Check if the request came from Plimus IP
if (array_search($_SERVER['REMOTE_ADDR'], $plimusIps) == false) {
exit($_SERVER['REMOTE_ADDR'] . " is not a plimus server!!!");
}


include "vars.php";
 $dbcon = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
include('SMTPconfig.php');
include('SMTPClass.php'); 
/*
$test = print_r($_REQUEST, true);
$msg = '<pre>'.$test.'</pre>';
mail("chriskyeu@gmail.com","IPN success",$msg,$headers);
exit();
*/



$log = serialize($_REQUEST);
$time  = time();

$productname_arr = explode("#",$_REQUEST['productName']);
$itemcode = $productname_arr[1];
$amount = $_REQUEST['invoiceChargeAmount']; 

@mysqli_query($dbcon,"INSERT INTO orders_deposits_ipn_logs (log, time) VALUES ('$log', '$time') "); 

$sql = @mysqli_query($dbcon,"SELECT * FROM orders_deposits WHERE order_id = '$itemcode' AND status = 'Not Approved' AND site_id = '$site_id' AND dtype = 'plimus' ");

$rowxemail = @mysqli_fetch_assoc($sql);
$xemail=$rowxemail['email'];


//$xamount = @mysql_result($sql,0,'amount');
$rowxamount = @mysqli_fetch_assoc($sql);
$xamount=$rowxamount['amount'];

//$order_id = @mysql_result($sql,0,'order_id');
$roworder_id = @mysqli_fetch_assoc($sql);
$order_id=$roworder_id['order_id'];

//$xcurr = @mysql_result($sql,0,'currency');
$rowxcurr = @mysqli_fetch_assoc($sql);
$xcurr=$rowxcurr['currency'];

$sqlx = @mysqli_query($dbcon,"SELECT * FROM orders_orders WHERE order_id = '$order_id' AND payment_status = 0 AND site_id = '$site_id' ");
$rowxtopic = @mysqli_fetch_assoc($sqlx);
$xtopic=$rowxtopic['topic'];

//$xuser_id = @mysql_result($sqlx,0,'user_id');
$rowxuser_id = @mysqli_fetch_assoc($sqlx);
$xuser_id=$rowxuser_id['user_id'];

$sqlu = @mysqli_query($dbcon,"SELECT * FROM orders_customers WHERE user_id = $xuser_id ");
$rowxfirstname = @mysqli_fetch_assoc($sqlu);
$xfirstname=$rowxfirstname['firstname'];


//$test = print_r($_REQUEST, true);
$msg .= '<pre>'.$test.'</pre>';

$msg .= "
SELECT * FROM orders_deposits WHERE order_id = '$itemcode' AND status = 'Not Approved' AND site_id = '$site_id' AND dtype = 'plimus'
<br />
$xamount == $amount


";
//mail("chriskyeu@gmail.com","IPN success",$msg,$headers);

	if ($xamount == $amount) {
	

	$subject2x = $companyname." Deposit Success";

$adminmessagex = '

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
	//mail($xemail,$subject2x,$adminmessagex,$headers);
    $mail->addAddress($xemail); // Add a recipient

    $mail->Subject = $subject2x;

    $mail->MsgHTML($adminmessagex);

    $mail->IsHTML(true);	

    $result = $mail->Send();	

	@mysqli_query($dbcon,"UPDATE orders_deposits SET status = 'Approved' WHERE order_id = '$itemcode' ");
	@mysqli_query($dbcon,"UPDATE orders_orders SET payment_status = 1, status = 1 WHERE order_id = $order_id ");
	}
				/////



mysql_close();
exit();


?>
