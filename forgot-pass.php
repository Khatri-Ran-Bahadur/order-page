<?php
include "vars.php";
require "../wp-load.php";
$dbcon = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$errMsg = '';
$emailx = $get['emailx'];
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($get['submit']) == "Resend" && isset($get['emailx']) ){

	$result = @mysqli_query($dbcon,"SELECT * FROM orders_customers WHERE email ='$emailx' AND site_id = $site_id ");

	if (@mysqli_num_rows($result) == 0) { 

		$errMsg =  "That user does not exist in our system. Please check again.";

	} else {

		$str =  strtoupper(md5(uniqid(rand(), true)));
		$len = strlen($str);
		$pass = substr("$str", 0, ($len-24));
		$passx = md5($pass);

		@mysqli_query($dbcon," UPDATE orders_customers SET password = '$pass' WHERE email = '$emailx'  AND site_id = $site_id ");	
		//email them their new login details
		$subject = $companyname . ' Password Reminder!';
		$message = "You recently requested a new password.<br />
		<br />
		Account Details <br />
		--------------- <br />
		Your username: <b> $emailx </b><br />
		Your Password: <b> $pass </b><br />
		<br />
		Keep this e-mail or write down your login and password. You will need this info to login and track your order at $siteurl<br />
		<br />
		<span color='#ccc'>
		--<br />
		Thank you,<br />
		Administrator<br />
		$siteurl<br />
		______________________________________________________<br />
		THIS IS AN AUTOMATED RESPONSE. <br />
		***DO NOT RESPOND TO THIS EMAIL****<br />
		</span>";


		
        $headers = 'Content-Type: text/html; charset=UTF-8';
        wp_mail($emailx, $subject, $message, $headers);
        
		$errMsg =  "The system has successfully sent your new password to the email address <b>$emailx </b>. If you do not get the email on your inbox check, please check it in your Junk / Spam Folder and set it as 'Not spam'. ";

	}
} else {
	$errMsg =  "Please enter your Email address";
}
$title = "Forgot Password";

function insert_custom_title() {
	global $title;
	return $title;
}

add_filter('wp_title','insert_custom_title');
include "header.php";
?>

<div id="contentOrder">	
    <div class="title"><h2 style="display: inline;" class="important">Password Reminder</h2></div>
    <?php if($errMsg != ''){ echo '<div id="messageError">' . $errMsg . '</div>'; } ?>
    <form id="formAccountLogin" method="POST" action="">
    <div><label style="width:50px !important;">Email:</label> <input name="emailx" value="" size="15" type="text"></div>
    <div><input value="Resend" name="submit" type="submit" class="button" />&nbsp;&nbsp;&nbsp;<a href="<?=$siteurl;?>/order/login.php">Login</a></div>
    </form>
    <div class="infoCookie">
        <strong>NOTE:</strong><br />Make sure your browser is set to accept cookies.  If your browsers functionality of cookies involves privacy/security levels (such as Internet Explorer), set the privacy/security level to "Medium" (if this DOES NOT work, set the privacy/security level to "Low"). Otherwise you won't be able to access any login-based pages in the account management area.
    </div>
</div>
<?php include "footer.php"; ?>
