<?php 
include('SMTPconfig.php');

$subject = "Thank you for placing your order at $companyname (#$order_id)";
$timeStamp = time();
$date  = getdate($timeStamp);
$month = $date["mon"];
$day   = $date["mday"];
$year  = $date["year"];
$hours = $date['hours'];
$minutes = $date['minutes'];

$orderDate = $day. '/' .  $month . '/' . $year;


$message = "<a href=\"$siteurl\" target=\"_blank\"><img src=\"$email_logo\" alt=\"$companyname\" style=\"margin-bottom:10px\" border=\"0\"></a><br />

<p>Thank you for  submitting your order details at <b><a href=\"$siteurl\" target=\"_blank\">$companyname</a></b> on $orderDate. Kindly make a note that your order ID is <b>$order_id</b>. This mail has been generated to inform you that your order details have been  successfully received and we assure you that our writers will get your document  completed within the provided deadline.</p>

<p>In order to  serve our esteemed and prosperous customers, we are using the most secure and  safest mode of payments to ensure a protected transaction mode. As soon as you pay for your order, you will be getting a sales  receipt at <b>$emailx</b>, which assures that your payment has been processed  successfully. In case you do not receive a payment confirmation mail <b><a href='$siteurl/order/paynow.php?order_id=$order_id'>Click Here</a></b> to pay for your order again. If the link seems to be void then copy and  paste the below link in your browser.</p>

<b>$siteurl/order/paynow.php?order_id=$order_id</b><br>
<p>Once your  order placement is confirmed you can visit your order page to contact  support/writer without any hassles. To resolve any of your issue, just follow 3 simple steps.</p>
<ol>
<li><b><a href=\"$siteurl/order/login.php\" target=\"_blank\">Click Here</a></b> to log in. If the link appears to be  void then copy and paste the below link in you browser.<br>

<u><b><a href=\"$siteurl/order/login.php\" target=\"_blank\">$siteurl/order/login.php</a></b> </u><br>
</li>
<li>Fill  in the required fields and insert the details as follows. <br>
Email ID: <b>$emailx</b><br>
Password:<b> $pass</b><br>
</li>
<li>In the Admin home area under My orders, click on the order title <a href=\"$siteurl/order/view.php?order_id=$order_id\">".stripslashes(nl2br($_SESSION['topic'])).". </li>
</ol>
<p>Rest assured that all your concerns will be resolved as we are here to assist you in every aspect we can.<br>
For any further concerns, it is requested to please get back to us and we assure you that we will get all your concerns resolved as we are here to assist you at our optimums. We also offer revisions/corrections for free in the even you are not satisfied with the finished project. </p>
<span color='#ccc'>
--<br />
Thank you,<br />
Administrator<br />
$siteaddress
$siteurl<br />
______________________________________________________<br />
THIS IS AN AUTOMATED RESPONSE. <br />
***DO NOT RESPOND TO THIS EMAIL****<br />
</span>

";
// $headers = 'Content-Type: text/html; charset=UTF-8';
// wp_mail($emailx, $subject, $message, $headers);

?>
