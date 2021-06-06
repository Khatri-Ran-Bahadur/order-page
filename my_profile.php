<?php

include "vars.php";
$dbcon = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
global $wpdb;

page_protect();

$text .= customer_orders_menu();

$title = 'My profile';

$text .= '<h1>My profile</h1>';
	if (!$_REQUEST['submit']) {
		if ($_REQUEST['msg'] !="") {
			$msg = base64_decode($_REQUEST['msg']);
			$text .= '<div style="color:green;">'.$msg.' <a href="home.php">go home</a></div>';
		} else if ($_REQUEST['error'] !="") {
			$error = base64_decode($_REQUEST['error']);
			$text .= '<div style="color:red;">'.$error.'</div>';
		}

		$sql = @mysqli_query($dbcon,"SELECT * FROM orders_customers WHERE email = '$email'  AND site_id = '$site_id'  ");

		while ($row = @mysqli_fetch_array($sql)) {
			$crc = $wpdb->get_results("SELECT * FROM orders_country_codes ORDER BY id ASC");

			foreach ($crc as $data) {
				if($row['country']==$data->codex){
					$options.="<option value='$data->codex' selected>$data->details</option>";
				}else{
					$options.="<option value='$data->codex'>$data->details</option>";
				}
				
			}
	
			$text .= '<form action="" method="POST">
			<input type="hidden" value="profile" name="view" />
			<table width="100%">
				<tr><td width="180px"><strong>Name:</strong> </td><td><input type="text" value="'.$email.'" name="full_name"/></td></tr>
				<tr><td width="180px"><strong>Email:</strong> </td><td><input type="text" value="'.$email.'" name"readonlyemail" readonly /> <br />Please <a href="../contact-us/">contact us</a> to change your email.</td></tr>
			<tr><td width="180px"><strong>Password:</strong> </td><td><input name="password" value="'.$row['password'].'" type="password" /></td></tr>
			<tr><td width="180px"><strong>Confirm Password:</strong></td><td> <input name="cpassword" value="'.$row['password'].'" type="password" /></td></tr>

			<tr><td width="180px"><strong>Country: </strong></td><td> <select name="country">'.$options.' </select></td></tr>

			<tr id="tr_phonenumber_6" style="display:"><td class="unnamed4 insect" width="180px">Contact Phone #1:*</td>
		            <td class="unnamed4" align="left">
		            <input type="text" name="phone1" id="phonenumber6" value="'.$row['phone1'].'" size="30"  >
		            </td>
		            </tr> 
		            
		            <tr id="tr_phonenumber_two_7" style="display:" ><td class="unnamed4 insect" width="180px" valign="top" style="padding-top: 4px;">Contact Phone #2:</td>
		            <td class="unnamed4" align="left">
		            <input type="text" name="phone2" id="phonenumber_two7" value="'.$row['phone2'].'" size="30"  >
					<div class="brdata">Number should be of the following format:<br> <u>(country code)-(area code)-(number)</u><br/>

					<table><tr><td class="unnamed4">For example:</td><td class="unnamed4">1-401-4293920 for the US<br/>(44)-(078)-43283145 for the UK.</td></tr></table></div><div class="brdata">Attention! In our ongoing attempts <strong>to protect your privacy and billing information</strong> we request that you please, provide us with a <strong>phone number you can be reached at within 15-30 minutes</strong>. You will get an automatic phone call from our customer support service to confirm the order placement.</div>
		            </td>
		            </tr> 
			</table><br /><br />
	<input name="submit" type="submit" value="Update" class="button" style="float:right; margin-right:350px;  " />
			</form>';
		}
	} else {
		if ($get['password'] != $get['cpassword']) {
			$error = base64_encode("Password and Confirm password do not match! Please try again.");
			header("Location: ".$siteurl."/order/my_profile.php?error=".$error);
			exit();

		} else {
			$sql = @mysqli_query($dbcon,"UPDATE orders_customers SET password = '$get[password]', phone1 = '".$_REQUEST['phone1']."', phone2 = '".$_REQUEST['phone2']."' WHERE email = '$email' AND site_id = '$site_id' ");
			$msg = base64_encode("Profile updated successfully!");
			header("Location: ".$siteurl."/order/my_profile.php?msg=".$msg);
			exit();
		}

	}

function insert_custom_title() {
	global $title;
	return $title;
}

add_filter('wp_title','insert_custom_title');

include "header.php";

?>
<div class="view_order">
   <?php echo $text; ?>
</div>
<?php include "footer.php"; ?>
