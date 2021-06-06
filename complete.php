<?php
//mysql_connect('localhost', 'bestessa_user', 'cgqK]wn[m*M;') or die(mysql_error());
//mysql_select_db("bestessa_orders") or die(mysql_error());

// add config data to email
include('SMTPconfig.php');
include('SMTPClass.php'); 

$host  = $_SERVER['HTTP_HOST'];
$headers = 'Content-type: text/html; charset=utf-8; format=flowed' . "\r\n";
$end_char = "\n";
$headers .= "From: \"BestEssayWriters.com Support\" <auto-reply@$host>\r\n" ."X-Mailer: PHP/" . phpversion(). "\r\n";


//	exit();
$ap_status = urldecode($_POST['ap_status']); 
$ap_merchant = urldecode($_POST['ap_merchant']);
$ap_securitycode = urldecode($_POST['ap_securitycode']);
$ap_amount = urldecode($_POST['ap_amount']);
$ap_totalamount = urldecode($_POST['ap_totalamount']);




if ($ap_status == "Success" && $ap_merchant == "dmcwriter9@gmail.com" && $ap_securitycode == "DKjE4B6tgRbpYonL" && $ap_amount == $ap_totalamount) {

$itemcode = $_POST['ap_itemcode'];

$sql = @mysqli_query($dbcon,"SELECT * FROM orders_deposits WHERE order_code = '$itemcode' AND status = 'Not Approved' ");
//$xemail = @mysql_result($sql,0,'email');
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


$sqlx = @mysqli_query($dbcon,"SELECT * FROM orders_orders WHERE order_id = '$order_id' AND email = '$xemail' ");
//$xtopic = @mysql_result($sqlx,0,'topic');
$rowxtopic = @mysqli_fetch_assoc($sqlx);
$xtopic=$rowxtopic['topic'];
//$xname = @mysql_result($sqlx,0,'firstname');
$rowxfirstname = @mysqli_fetch_assoc($sqlx);
$xname=$rowxfirstname['firstname'];

	if ($xamount == $ap_totalamount) {
	

	$subject2x = "BestEssayWriters.com Deposit Success";

$adminmessagex = '

<div style="background:#F6F6F6;font-family:Verdana, Arial, Helvetica, sans-serif;font-size:12px;margin:0;padding:0">
<div style="background:#F6F6F6;font-family:Verdana, Arial, Helvetica, sans-serif;font-size:12px;margin:0;padding:0">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tbody><tr>
    <td align="center" valign="top" style="padding:20px 0 20px 0">
        <table bgcolor="#FFFFFF" cellspacing="0" cellpadding="10" border="0" width="650" style="border:1px solid #E0E0E0">
            
            <tbody><tr>
                <td valign="top"><a href="'.$siteurl.'/order/home.php" target="_blank"><img src="'.$siteurl.'/wp-content/uploads/2011/03/logo-1.png" alt="Best Essay Writers" style="margin-bottom:10px" border="0"></a></td>
            </tr>
            
            <tr>
                <td valign="top">
                    <h1 style="font-size:22px;font-weight:normal;line-height:22px;margin:0 0 11px 0">Hello, '.$xname.'</h1>
                    <p style="font-size:12px;line-height:16px;margin:0">
                       Your deposit of <b>'.$xcurr.' '.$ap_totalamount.'</b> for  <a href="'.$siteurl.'/order/view.php?order_id='.$order_id.'">'.$xtopic.'</a> has been credited to your account <br />
			<br />
            </td></tr>
            <tr>
                <td bgcolor="#EAEAEA" align="center" style="background:#EAEAEA;text-align:center"><center><p style="font-size:12px;margin:0">Thank you, <strong>Best Essay Writers</strong></p></center></td>
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


	@mysqli_query($dbcon,"UPDATE orders_deposits SET status = 'Approved' WHERE order_code = '$itemcode' ");
	@mysqli_query($dbcon,"UPDATE orders_orders SET payment_status = 1 WHERE order_id = $order_id ");
	}
				/////

}

mysqli_close($dbcon);

header("Location: '.$siteurl.'/order/home.php");
 
?>
