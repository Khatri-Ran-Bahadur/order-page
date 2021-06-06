<?php
include "vars.php";
$dbcon = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$errMsg = '';

//print_r($_POST);die;
if ($_REQUEST['submit'] == "Login"){

//echo "SELECT * FROM orders_customers WHERE email = '".$_REQUEST['emailx']."' AND password = '".$_REQUEST['password']."' AND site_id = $site_id ";die;
$result = @mysqli_query($dbcon,"SELECT * FROM orders_customers WHERE email = '".$_REQUEST['emailx']."' AND password = '".$_REQUEST['password']."' AND site_id = $site_id ");
	if (@mysqli_num_rows($result) == 0) { 
	$errMsg =  "Wrong username or password"; 

	} else {
		//log them in
		//$user_id = @mysql_result($result,0,"user_id");
		$rowxemail = @mysqli_fetch_assoc($result); 
        $user_id=$rowxemail['user_id']; 

		
		$randid = md5(rand());
 
		@mysqli_query($dbcon," UPDATE orders_customers SET randid = '$randid', lastlogin = '". time() ."' WHERE email = '".$_REQUEST['emailx']."' AND site_id = $site_id ");			
		setcookie( "email", $_COOKIE['email'], time() - 3600, '/' );
    	setcookie( "email", $_REQUEST['emailx'], 0, '/' ); 
		setcookie( "randid", $_COOKIE['randid'], time() - 3600, '/' );
		setcookie( "randid", $randid, 0, '/' );
		setcookie( "user_id", $_COOKIE['user_id'], time() - 3600, '/' );
		setcookie( "user_id", $user_id, 0, '/' );
	//	echo $siteurl;die;
		header("Location: " . $siteurl . "/order/home.php");
		exit();

	}
}


if ($errMsg != '') {
	$title =  $errMsg;
} else {
$title = "Login Now";
}

function insert_custom_title() {
	global $title;
	return $title;
}

add_filter('wp_title','insert_custom_title');


include "header.php";
?>

<div id="contentOrder" >
    <h2 style="display: inline;" class="important">Login to Account Management</h2>
    <?php if($errMsg != ''){ echo '<div id="messageError">' . $errMsg . '</div>'; } ?>
    <form id="formAccountLogin" method="POST" action="">
	<script type="text/javascript">
	var visitortime = new Date();
	var offset = visitortime ? -visitortime.getTimezoneOffset()*60 : 0;
	document.write('<input type="hidden" name="browsertimezone" value="' + offset + '">');
	</script><div class="row">
    <div class="col-md-6"><label>Email:</label> <input name="emailx" value="" size="15" type="text"></div>
    <div class="col-md-6"><label>Password:</label> <input name="password" size="15" type="password"></div>
    <div class="col-md-12"><input type="submit" class="button" value="Login" name="submit" />&nbsp;&nbsp;&nbsp;&nbsp; <a href="<?php echo $siteurl; ?>/order/forgot-pass.php">forgot password</a></div></div>
    </form>
    
    <div class="infoCookie col-md-12" style="font-size:13px">
    	<strong>NOTE:</strong><br />Make sure your browser is set to accept cookies.  If your browsers functionality of cookies involves privacy/security levels (such as Internet Explorer), set the privacy/security level to "Medium" (if this DOES NOT work, set the privacy/security level to "Low"). Otherwise you won't be able to access any login-based pages in the account management area.
    </div>
</div>
<?php include "footer.php"; ?>
