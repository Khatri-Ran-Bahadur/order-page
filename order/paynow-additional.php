<?
$dbcon = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$order_id = $_REQUEST['order_id'];
$dtype = $_REQUEST['dtype'];
$dconfirm = $_REQUEST['dconfirm'];

$total_h = $_REQUEST['total_h'];
$curr = $_REQUEST['curr'];
$order_code = $_REQUEST['order_code'];

if (!$_REQUEST['submit']) {

$sql = @mysqli_query($dbcon," SELECT * FROM  orders_additional_payments WHERE order_id = '$order_id' AND status = 0 ORDER BY id ASC ");
	if (@mysqli_num_rows($sql) == 0) {
	echo 'Invalid Request';

	} else {

		$additional_sum = mysqli_query($dbcon,"SELECT SUM(amount) AS amount_due FROM orders_additional_payments WHERE order_id = $get[order_id] AND status = 0 ");
		list($amount_due) = mysqli_fetch_row($additional_sum);

		echo '
		<form method="POST" action="paynow-additional.php">
		<input type="hidden" name="dconfirm" value="2">
		<input type="hidden" name="order_id" value="'.$order_id.'">


		<h2>Additional Order Payment</h2>
		<p><b>Order :</b> <a target="_BLANK" href="view.php?order_id='.$get['order_id'].'">'.$get['order_id'].'</a> </p>
		<table width="70%" border="0" cellspacing="1" cellpadding="4">
		<tr>
		<td><h3>Item</h3></td><td><h3>Amount</h3></td>
		</tr>
		
		';
		while ($row = @mysqli_fetch_array($sql)) {
			$id_codes[] = $row['id'];
			echo '<tr><td>'.$row['reason'].'</td><td>'.$row['currency'].' '.$row['amount'].'</td></tr>';
			$currency = $row['currency'];

		} //end while
		$id_codes_im = implode("~",$id_codes);
		$order_codex = "#".$_REQUEST['order_id']."~".$id_codes_im;


		echo '
		<tr><td>&nbsp;</td><td style="font-weight: bold;">Total : '.$currency.' '.$amount_due.' &nbsp;</td></tr>
		</table>
		

		<input type="hidden" name="total_h" value="'.$amount_due.'">
		<input type="hidden" name="curr" value="'.$currency.'">
		<input type="hidden" name="order_code" value="'.$order_codex.'">
		';

		include ("templates/deposit_tpl.php");

		?>

		<br /><br />
		<p><input type="submit" class="button" value="Continue" name="submit">
		</form>
		<?php

	} //end if numrows..
} else {
	if ($dtype == "mail" && $dconfirm == "1") {
	echo '<h1>Payments by Mail</h1>
	<p>
	Please send a cash or check payment for ' . $curr . '' . $total_h . '  to the following address:
	<p>
	' . $companyname . '<br>
	' . $siteaddress . '<br>
	' . $sitecity . ', ' . $sitestate . ', ' . $sitecountry . '<br>
	' . $sitezip . '<br>
	<p>
	Include a note with your Email (' . $email . ') and Order Code (' . $order_code . ') on it so we can credit your order accordingly.
	<br>
	Thanks';
	} else if ($dtype == "paypal") {
		if ($dconfirm == "1") {
		?>

		<h2>Deposit Money</h2>
		<p>
		<b>2. Confirm payment details</b>
		<br />

		<p>
		Please, verify deposit details:
		<p>
		<?
		echo '
		<form method="POST" action="paynow.php">
		<input type="hidden" name="dconfirm" value="2">
		<input type="hidden" name="dtype" value="paypal">
		<input type="hidden" name="order_id" value="'.$order_id.'">
		<input type="hidden" name="total_h" value="'.$total_h.'">
		<input type="hidden" name="curr" value="'.$curr.'">
		<input type="hidden" name="order_code" value="'.$order_code.'">
		'; ?>

		<table cellspacing=10 cellpadding=0 border=0>
		<tr valign="top">
 		<td colspan="2"><b>Payment method:</b>
  		<p><img src="order/images/paypal.gif" alt="Paypal" style="float: left; margin-right: 10px; margin-bottom: 10px">
  		<b>Paypal</b>
  		<br><i>Deposits by PayPal are instant except of payments by echeck. If you don't have PayPal you will be able to pay by Credit Card using this option. Read more about <a href='http://www.paypal.com/' target=_blank>PayPal</a></i></p>
  		</td>
		</tr>
		<tr>
  		<td><b>Amount to send:</b>
  		</td>
  		<td> <? echo $curr.'&nbsp;'.$total_h;?>
  		</td>
		</tr>
		<tr><td colspan=2>
		<input type="button" class="button" onclick="history.go(-1);" value="Back">
		<input type="submit" class="button" value="Next" name="submit">
		<p><a href="home.php">Back to orders</a>
  		</td>
		</tr>
		</table>
		</form>
		<?
		} else if ($dconfirm == "2") {
		$today = getdate();
		$month = $today['mon'];
		$day = $today['mday'];
		$year = $today['year'];
		$hours = $today['hours'];
		$minutes = $today['minutes'];
		$ddate = "$month/$day/$year at $hours:$minutes";
		$sql2 = @mysqli_query($dbcon,"SELECT * FROM orders_deposits WHERE email = '$email' AND order_code = '$order_code' ");
			if (@mysqli_num_rows($sql2)== 0 ) {
			@mysqli_query($dbcon,"INSERT INTO orders_deposits 
(email, order_id, site_id, dtype, amount, currency, status, month, day, year, time, order_code) VALUES 
('$email', '$order_id', '$site_id', '$dtype', '$total_h', '$curr', 'Not Approved', '$month', '$day', '$year', '".time()."', '$order_code' ) ");
			} else {
			@mysqli_query($dbcon,"UPDATE orders_deposits SET dtype = '$dtype', amount = '$total_h', month = '$month', day = '$day', year = '$year', time = '".time()."' WHERE order_code = '$order_code' ");
			}

			$alsql = @mysqli_query($dbcon,"SELECT * FROM orders_customers WHERE email = '$email' ");
			while ($alrow = @mysqli_fetch_array($alsql)) {
			$ap_fname = $alrow['firstname'];
			$ap_lname = $alrow['lastname'];


			}

/*echo "INSERT INTO orders_deposits 
(email, order_id, site_id, dtype, amount, currency, status, month, day, year, time, order_code) VALUES 
('$email', '$order_id', '$site_id', '$dtype', '$total_h', '$curr', 'Not Approved', '$month', '$day', '$year', '".time()."', '$order_code' ) ";*/

		?>
		<form name="_xclick" action="https://www.paypal.com/cgi-bin/webscr" method="post"  id="paymentProcessor">
		<!--<form name="_xclick" action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">-->
		<input type="hidden" name="cmd" value="_xclick">
		<input type="hidden" name="currency_code" value="<?=$curr;?>">
		<input type="hidden" name="business" value="<?=$payment_email;?>">
		<!--<input type="hidden" name="business" value="chris_1311108022_biz@webfennecs.com">-->
		<input type="hidden" name="item_name" value="Service-related deposit on <?=$companyname;?> Email: <?=$email;?> #<?=$order_id;?>">
		<input type="hidden" name="item_number" value="<?=$order_id;?>">
		<input type="hidden" name="invoice" value="<?=$order_code;?>">
		<input type="hidden" name="first_name" value="<?=$ap_fname;?>">
		<input type="hidden" name="last_name" value="<?=$ap_lname;?>">
		<input type="hidden" name="no_shipping" value="1">
		<!--<input type="hidden" name="country" value="US">-->
		<input type="hidden" name="amount" value="<?=$total_h;?>">
		<input type="hidden" name="notify_url" value="<?=$siteurl;?>/order/pp_complete.php">
		<input type="hidden" name="return" value="<?=$siteurl;?>/order/home.php">
		<input type="hidden" name="cancel_return" value="<?=$siteurl;?>/order/home.php">

		</form>
		<img src="ajax-loader.gif"><br />
		<p>
		You are being redirected to paypal.<br>
		Deposited funds will be added to your balance as soon as we receive notification from PayPal.
		</p>
		<?
		}
	} else if ($dtype == "alertpay") {
		if ($dconfirm == "1") {
		?>

		<div  class="contentheading">Deposit Money</div>
		<h1>2. Confirm payment details</h1>
		<br />

		<p>
		Please, verify deposit details:
		<p>
		<?
		echo '
		<form method="POST" action="paynow.php">
		<input type="hidden" name="dconfirm" value="2">
		<input type="hidden" name="dtype" value="alertpay">
		<input type="hidden" name="order_id" value="'.$order_id.'">
		<input type="hidden" name="total_h" value="'.$total_h.'">
		<input type="hidden" name="curr" value="'.$curr.'">
		<input type="hidden" name="order_code" value="'.$order_code.'">
		'; ?>

		<table cellspacing=10 cellpadding=0 border=0>
		<tr valign="top">
  		<td colspan="2"><b>Payment method:</b>
  		<p><img src="https://www.alertpay.com/Images/BuyNow/pay_now_11.gif" alt="Alertpay"  style="float: left; margin-right: 10px; margin-bottom: 10px" />
  		<p>Instant and cost-effective deposits via Credit or Debit Card powered by AlertPay Gateway</p>

  		</td>
		</tr>
		<tr>
  		<td><b>Amount to send:</b>
  		</td>
  		<td><? echo $curr.'&nbsp;'.$total_h;?>
  		</td>
		</tr>
		<tr><td colspan=2>
		<input type="button" class="button" onclick="history.go(-1);" value="Back">
		<input type="submit" class="button" value="Next" name="submit">
		<p><a href="home.php">Back to orders</a>
  		</td>
		</tr>
		</table>
		</form>
		<?
		} else if ($dconfirm == "2") {

		$today = getdate();
		$month = $today['mon'];
		$day = $today['mday'];
		$year = $today['year'];
		$hours = $today['hours'];
		$minutes = $today['minutes'];
		$ddate = "$month/$day/$year at $hours:$minutes";
		$sql2 = @mysqli_query($dbcon,"SELECT * FROM orders_deposits WHERE email = '$email' AND order_code = '$order_code' ");
			if (@mysqli_num_rows($sql2)== 0 ) {
			@mysqli_query($dbcon,"INSERT INTO orders_deposits 
(email, order_id, site_id, dtype, amount, currency, status, month, day, year, time, order_code) VALUES 
('$email', '$order_id', '$site_id', '$dtype', '$total_h', '$curr', 'Not Approved', '$month', '$day', '$year', '".time()."', '$order_code' ) ");
			} else {
			@mysqli_query($dbcon,"UPDATE orders_deposits SET dtype = '$dtype', amount = '$total_h', month = '$month', day = '$day', year = '$year', time = '".time()."' WHERE order_code = '$order_code' ");
			}

			$alsql = @mysqli_query($dbcon,"SELECT * FROM orders_customers WHERE email = '$email' ");
			while ($alrow = @mysqli_fetch_array($alsql)) {
			$ap_fname = $alrow['firstname'];
			$ap_lname = $alrow['lastname'];


			}

/*echo "INSERT INTO orders_deposits 
(email, order_id, site_id, dtype, amount, currency, status, month, day, year, time, order_code) VALUES 
('$email', '$order_id', '$site_id', '$dtype', '$total_h', '$curr', 'Not Approved', '$month', '$day', '$year', '".time()."', '$order_code' ) ";*/

		?>

		<form method="post" action="https://www.alertpay.com/PayProcess.aspx"  id="paymentProcessor">
		    <input type="hidden" name="ap_merchant" value="<?=$alertpay_email;?>"/>
		    <input type="hidden" name="ap_purchasetype" value="item-goods"/>
		    <input type="hidden" name="ap_itemname" value="<?=$order_code;?>"/>
		    <input type="hidden" name="ap_amount" value="<?=$total_h;?>"/>
		    <input type="hidden" name="ap_currency" value="<?=$curr;?>"/>
    		    <input type="hidden" name="ap_quantity" value="1"/>
		    
		    <input type="hidden" name="ap_itemcode" value="<?=$order_id;?>"/>
		    <input type="hidden" name="ap_description" value="Service-related deposit on <?=$companyname;?> Email: <?=$email;?> #<?=$order_id;?>" />
		    <input type="hidden" name="ap_returnurl" value="<?=$siteurl;?>/order/ap_complete.php"/>
		    <input type="hidden" name="ap_cancelurl" value="<?=$siteurl;?>/order/home.php"/>

		    <input type="hidden" name="ap_custfirstname" value="<?=$ap_fname;?>" />
		    <input type="hidden" name="ap_custlastname" value="<?=$ap_lname;?>" />
		    <input type="hidden" name="ap_custemailaddress" value="<?=$email;?>" />
		    

		</form>
		<img src="ajax-loader.gif" /><br />		    
		        
		<p>
		You are being redirected to alertpay.<br>
		Deposited funds will be added to your balance as soon as we receive notification from AlertPay.
		</p>
		<?
		}

	} else if ($dtype == "moneybookers") {
		if ($dconfirm == "1") {
		?>

		<div  class="contentheading">Deposit Money</div>
		<h1>2. Confirm payment details</h1>
		<br />

		<p>
		Please, verify deposit details:
		<p>
		<?
		echo '
		<form method="POST" action="paynow.php">
		<input type="hidden" name="dconfirm" value="2">
		<input type="hidden" name="dtype" value="alertpay">
		<input type="hidden" name="order_id" value="'.$order_id.'">
		<input type="hidden" name="total_h" value="'.$total_h.'">
		<input type="hidden" name="curr" value="'.$curr.'">
		<input type="hidden" name="order_code" value="'.$order_code.'">
		'; ?>

		<table cellspacing=10 cellpadding=0 border=0>
		<tr valign="top">
  		<td colspan="2"><b>Payment method:</b>
  		<p><img src="https://www.alertpay.com/Images/BuyNow/pay_now_11.gif" alt="Alertpay"  style="float: left; margin-right: 10px; margin-bottom: 10px" />
  		<p>Instant and cost-effective deposits via Credit or Debit Card powered by AlertPay Gateway</p>

  		</td>
		</tr>
		<tr>
  		<td><b>Amount to send:</b>
  		</td>
  		<td><? echo $curr.'&nbsp;'.$total_h;?>
  		</td>
		</tr>
		<tr><td colspan=2>
		<input type="button" class="button" onclick="history.go(-1);" value="Back">
		<input type="submit" class="button" value="Next" name="submit">
		<p><a href="home.php">Back to orders</a>
  		</td>
		</tr>
		</table>
		</form>
		<?
		} else if ($dconfirm == "2") {

		$today = getdate();
		$month = $today['mon'];
		$day = $today['mday'];
		$year = $today['year'];
		$hours = $today['hours'];
		$minutes = $today['minutes'];
		$ddate = "$month/$day/$year at $hours:$minutes";
		$sql2 = @mysqli_query($dbcon,"SELECT * FROM orders_deposits WHERE email = '$email' AND order_code = '$order_code' ");
			if (@mysqli_num_rows($sql2)== 0 ) {
			@mysqli_query($dbcon,"INSERT INTO orders_deposits 
(email, order_id, site_id, dtype, amount, currency, status, month, day, year, time, order_code) VALUES 
('$email', '$order_id', '$site_id', '$dtype', '$total_h', '$curr', 'Not Approved', '$month', '$day', '$year', '".time()."', '$order_code' ) ");
			} else {
			@mysqli_query($dbcon,"UPDATE orders_deposits SET dtype = '$dtype', amount = '$total_h', month = '$month', day = '$day', year = '$year', time = '".time()."' WHERE order_code = '$order_code' ");
			}

			$alsql = @mysqli_query($dbcon,"SELECT * FROM orders_customers WHERE email = '$email' ");
			while ($alrow = @mysqli_fetch_array($alsql)) {
			$ap_fname = $alrow['firstname'];
			$ap_lname = $alrow['lastname'];


			}

		?>

<form action="https://www.moneybookers.com/app/payment.pl"  id="paymentProcessor" name="payment_form">

 <input type="hidden" name="pay_to_email" value="<?=$moneybookers_email;?>"> 
 <input type="hidden" name="pay_from_email" value="<?=$email;?>">
 <input type="hidden" name="return_url" value="<?=$siteurl;?>/order/view.php?order_id=<?=$order_id;?>"> <!-- URL to redirect after payment success -->
 <input type="hidden" name="cancel_url" value="<?=$siteurl;?>/order/view.php?order_id=<?=$order_id;?>">  <!-- URL to redirect after payment cancel -->
 <input type="hidden" name="status_url" value="<?=$siteurl;?>/order/mb_complete.php"> <!-- URL to get the payment response (not visible to user, called on backend) -->
 <input type="hidden" name="language" value="EN"> <!-- Language of payment -->

 <input type="hidden" name="hide_login" value="1">  <!-- Whether to show the tiny login form with the payment form, no in our case -->

 <!-- Specifies a target in which the return_url value will be called upon successful payment from customer.  -->
 <!-- 1 = '_top', 2 = '_parent', 3 = '_self', 4= '_blank' -->
 <input type="hidden" name="return_url_target" value="1">  
 <input type="hidden" name="cancel_url_target" value="1"> 

<!-- Custom fields for your own needs -->
 <input type="hidden" name="merchant_fields" value="order_id"> <!-- List all your custom fields here (comma separated, max 5)-->
 <input type="hidden" name="order_id" value="<?=$order_id;?>">  <!-- Value of Custom 'user_id' -->

 <input type="hidden" name="amount_description" value="Order #<?=$order_id;?>  payment">  <!-- Description of the amount -->
 <input type="hidden" name="amount" value="<?=$total_h;?>">  <!-- Amount to be charged -->
 <input type="hidden" name="currency" value="<?=$curr;?>">   <!-- Currency of payment -->
 <input type="hidden" name="firstname" value="<?=$ap_fname;?>">   <!-- Firstname of buyer, need for autofilling -->
 <input type="hidden" name="lastname" value="<?=$ap_lname;?>">    <!-- Lastname of buyer, need for autofilling -->
 <input type="hidden" name="email" value="<?=$email;?>">    <!-- Email of buyer, need for autofilling -->

 <input type="hidden" name="detail1_description" value="Service-related deposit on <?=$companyname;?> Email: <?=$email;?> #<?=$order_id;?>">      <!-- Description heading of the payyment, shown after payment has been made -->
 <input type="hidden" name="detail1_text" value="Service-related deposit on <?=$companyname;?> Email: <?=$email;?> #<?=$order_id;?>">      <!-- Detailed description of the payment, shown after payment has been made -->
 <input type="hidden" name="confirmation_note" value="Thank you for your payment!"> <!-- Confirmation message to be shown after payment has been made --> 

		</form>
		<img src="ajax-loader.gif" /><br />		    
		        
		<p>
		You are being redirected to Moneybookers.<br>
		Deposited funds will be added to your balance as soon as we receive notification from Moneybookers.
		</p>
		<?
		}

	} else if ($dtype == "swreg") {
		if ($dconfirm == "1") {
		?>

		<h2>Deposit Money</h2>
		<p>
		<b>2. Confirm payment details</b>
		<br />

		<p>
		Please, verify deposit details:
		<p>
		<?
		echo '
		<form method="POST" action="paynow.php">
		<input type="hidden" name="dconfirm" value="2">
		<input type="hidden" name="dtype" value="paypal">
		<input type="hidden" name="order_id" value="'.$order_id.'">
		<input type="hidden" name="total_h" value="'.$total_h.'">
		<input type="hidden" name="curr" value="'.$curr.'">
		<input type="hidden" name="order_code" value="'.$order_code.'">
		'; ?>

		<table cellspacing=10 cellpadding=0 border=0>
		<tr valign="top">
 		<td colspan="2"><b>Payment method:</b>
  		<p><img src="order/images/paypal.gif" alt="Paypal" style="float: left; margin-right: 10px; margin-bottom: 10px">
  		<b>Paypal</b>
  		<br><i>Deposits by PayPal are instant except of payments by echeck. If you don't have PayPal you will be able to pay by Credit Card using this option. Read more about <a href='http://www.paypal.com/' target=_blank>PayPal</a></i></p>
  		</td>
		</tr>
		<tr>
  		<td><b>Amount to send:</b>
  		</td>
  		<td> <? echo $curr.'&nbsp;'.$total_h;?>
  		</td>
		</tr>
		<tr><td colspan=2>
		<input type="button" class="button" onclick="history.go(-1);" value="Back">
		<input type="submit" class="button" value="Next" name="submit">
		<p><a href="home.php">Back to orders</a>
  		</td>
		</tr>
		</table>
		</form>
		<?
		} else if ($dconfirm == "2") {
		$today = getdate();
		$month = $today['mon'];
		$day = $today['mday'];
		$year = $today['year'];
		$hours = $today['hours'];
		$minutes = $today['minutes'];
		$ddate = "$month/$day/$year at $hours:$minutes";

		$sql2 = @mysqli_query($dbcon,"SELECT * FROM orders_deposits WHERE email = '$email' AND order_code = '$order_code' ");
			if (@mysqli_num_rows($sql2)== 0 ) {
			@mysqli_query($dbcon,"INSERT INTO orders_deposits 
(email, order_id, site_id, dtype, amount, currency, status, month, day, year, time, order_code) VALUES 
('$email', '$order_id', '$site_id', '$dtype', '$total_h', '$curr', 'Not Approved', '$month', '$day', '$year', '".time()."', '$order_code' ) ");
			} else {
			@mysqli_query($dbcon,"UPDATE orders_deposits SET dtype = '$dtype', amount = '$total_h', month = '$month', day = '$day', year = '$year', time = '".time()."' WHERE order_code = '$order_code' ");
			}

			$alsql = @mysqli_query($dbcon,"SELECT * FROM orders_customers WHERE email = '$email' ");
			while ($alrow = @mysqli_fetch_array($alsql)) {
			$ap_fname = $alrow['firstname'];
			$ap_lname = $alrow['lastname'];


			}

		?>
		<form action="https://usd.swreg.org/cgi-bin/s.cgi" method="POST"  id="paymentProcessor">
		<input type='hidden' name="s"  value="<?=$swreg_s;?>">
		<input type='hidden' name="p"  value="<?=$swreg_p;?>">
		<input type='hidden' name="v"  value="0">
		<input type='hidden' name="d"  value="0">
		<input type='hidden' name="q"  value="1">
		<input type='hidden' name="c"  value="<?=$curr;?>">
		<input type="hidden" name="t" value="Service-related deposit on <?=$companyname;?> Email: <?=$email;?> #<?=$order_code;?>">
		<input type='hidden' name="vp" value="<?=$total_h;?>" />
		</form> 

		<img src="ajax-loader.gif" /><br />		    
		        
		<p>
		You are being redirected to SWREG.<br>
		Deposited funds will be added to your balance as soon as we receive notification from SWREG.
		</p>
		<?
		}
	} else if ($dtype == "plimus") {
		if ($dconfirm == "1") {
		?>

		<h2>Deposit Money</h2>
		<p>
		<b>2. Confirm payment details</b>
		<br />

		<p>
		Please, verify deposit details:
		<p>
		<?
		echo '
		<form method="POST" action="paynow.php">
		<input type="hidden" name="dconfirm" value="2">
		<input type="hidden" name="dtype" value="paypal">
		<input type="hidden" name="order_id" value="'.$order_id.'">
		<input type="hidden" name="total_h" value="'.$total_h.'">
		<input type="hidden" name="curr" value="'.$curr.'">
		<input type="hidden" name="order_code" value="'.$order_code.'">
		'; ?>

		<table cellspacing=10 cellpadding=0 border=0>
		<tr valign="top">
 		<td colspan="2"><b>Payment method:</b>
  		<p><img src="order/images/paypal.gif" alt="Paypal" style="float: left; margin-right: 10px; margin-bottom: 10px">
  		<b>Paypal</b>
  		<br><i>Deposits by PayPal are instant except of payments by echeck. If you don't have PayPal you will be able to pay by Credit Card using this option. Read more about <a href='http://www.paypal.com/' target=_blank>PayPal</a></i></p>
  		</td>
		</tr>
		<tr>
  		<td><b>Amount to send:</b>
  		</td>
  		<td> <? echo $curr.'&nbsp;'.$total_h;?>
  		</td>
		</tr>
		<tr><td colspan=2>
		<input type="button" class="button" onclick="history.go(-1);" value="Back">
		<input type="submit" class="button" value="Next" name="submit">
		<p><a href="home.php">Back to orders</a>
  		</td>
		</tr>
		</table>
		</form>
		<?
		} else if ($dconfirm == "2") {
		$today = getdate();
		$month = $today['mon'];
		$day = $today['mday'];
		$year = $today['year'];
		$hours = $today['hours'];
		$minutes = $today['minutes'];
		$ddate = "$month/$day/$year at $hours:$minutes";
		$sql2 = @mysqli_query($dbcon,"SELECT * FROM orders_deposits WHERE email = '$email' AND order_code = '$order_code' ");
			if (@mysqli_num_rows($sql2)== 0 ) {
			@mysqli_query($dbcon,"INSERT INTO orders_deposits 
(email, order_id, site_id, dtype, amount, currency, status, month, day, year, time, order_code) VALUES 
('$email', '$order_id', '$site_id', '$dtype', '$total_h', '$curr', 'Not Approved', '$month', '$day', '$year', '".time()."', '$order_code' ) ");
			} else {
			@mysqli_query($dbcon,"UPDATE orders_deposits SET dtype = '$dtype', amount = '$total_h', month = '$month', day = '$day', year = '$year', time = '".time()."' WHERE order_code = '$order_code' ");
			}

			$alsql = @mysqli_query($dbcon,"SELECT * FROM orders_customers WHERE email = '$email' ");
			while ($alrow = @mysqli_fetch_array($alsql)) {
			$ap_fname = $alrow['firstname'];
			$ap_lname = $alrow['lastname'];


			}

		?>
		<form action="https://secure.plimus.com/jsp/buynow.jsp" method="POST"  id="paymentProcessor">
		<input type='hidden' name="contractId"  value="<?=$plimus_id;?>">
		<input type='hidden' name="currency"  value="<?=$curr;?>">
		<input type="hidden" name="overrideName" value="Service-related deposit on <?=$companyname;?> Email: <?=$email;?> #<?=$order_id;?>">
		<input type='hidden' name="overridePrice" value="<?=$total_h;?>" />
		<input type="hidden" name="firstName" value="<?=$ap_fname;?>">	<!--String	Customer first name-->
		<input type="hidden" name="lastName" value="<?=$ap_lname;?>">	<!--String	Customer last name-->
		<input type="hidden" name="email" value="<?=$email;?>">	
		<input type="hidden" name="referrer" value="<?=$siteurl;?>/order/view.php?order_id=<?=$order_id;?>">	
		<input type="hidden" name="invoiceInfoURL" value="<?=$siteurl;?>/order/view.php?order_id=<?=$order_id;?>">	

		</form>  


		<img src="ajax-loader.gif" /><br />		    
		        
		<p>
		You are being redirected to PLIMUS.<br>
		Deposited funds will be added to your balance as soon as we receive notification from PLIMUS.
		</p>
		<?
		}
	} else {

	echo 'Please choose a deposit method to continue with the deposit process.<br>
	<a href="javascript:history.go(-1);">Go Back...</a>';
	}
}

?>
