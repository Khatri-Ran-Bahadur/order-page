<?php
include "vars.php";
$dbcon = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
 include('SMTPconfig.php');
include('SMTPClass.php'); 

$log = serialize($_POST);
$time  = time();

@mysqli_query($dbcon,"INSERT INTO orders_deposits_ipn_logs (log, time) VALUES ('$log', '$time') ");

//exit();


$itemcode = $_POST['order_id'];
$pay_to_email =    $_POST['pay_to_email'];
$mb_amount   ['mb_amount'];
$currency    ['currency'];
$amount   ['amount'];

if ($pay_to_email == $moneybookers_email) {

$sql = @mysqli_query($dbcon,"SELECT * FROM orders_deposits WHERE order_id = '$itemcode' AND status = 'Not Approved' AND site_id = '$site_id' ");


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

$rowxuser_id = @mysqli_fetch_assoc($sqlx);
$xuser_id=$rowxuser_id['user_id'];

$sqlu = @mysqli_query($dbcon,"SELECT * FROM orders_customers WHERE user_id = $xuser_id ");
$rowxfirstname = @mysqli_fetch_assoc($sqlu);
$xfirstname=$rowxfirstname['firstname'];

	if ($xamount == $amount && $xcurr == $currency) {
	

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
                       Your deposit of <b>'.$xcurr.' '.$amount.'</b> for  <a href="'.$siteurl.'/order/view.php?order_id='.$order_id.'">'.stripslashes($xtopic).'</a> has been credited to your account <br />
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

}

mysql_close();
exit();

?>
