<?php

include "vars.php";
 $dbcon = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

page_protect();

$text = customer_orders_menu();
$order_id=$_REQUEST['order_id'];
if ($order_id != "") {
	if (!$action) {
		$sql = @mysqli_query($dbcon,"SELECT * FROM orders_orders WHERE order_id = '$order_id' AND site_id = '$site_id' AND user_id = '$user_id' ");
		//echo "SELECT * FROM orders_orders WHERE order_id = '$order_id' AND email = '$email'" ;
		if (@mysqli_num_rows($sql)==0) {
		$title = "Order not found!";
		$text .="Order not found!";

		} else {
			setcookie( "order_idx", $_COOKIE['order_idx'], time() - 3600, '/' );
			setcookie( "order_idx", $order_id, 0, '/' );
			while ($row = @mysqli_fetch_array($sql)) {

			       	if ($row['payment_status'] == "0") {
				$payment_status = '<a href="paynow.php?order_id='.$row['order_id'].'">'.payment_status($row['payment_status']).'&nbsp;&nbsp;&nbsp;&nbsp; Pay Now</a>';
				} else {
				$payment_status  = payment_status($row['payment_status']).' Paid';
				}
			$order_categoryR = @mysqli_query($dbcon,"SELECT * FROM orders_subject_areas WHERE codex = '".$row['order_category']."'");
			$roworder_category = @mysqli_fetch_assoc($order_categoryR);
			$order_category=$roworder_category['details'];

					
			$sql7 = @mysqli_query($dbcon,"SELECT details FROM orders_writing_styles WHERE codex = $row[style] ");
			//$stylex = @mysql_result($sql7,0,"details");
			$rowstylex = @mysqli_fetch_assoc($sql7);
			$stylex=$rowstylex['details'];

			

			//email, country code and phone number
			$phone1R = @mysqli_query($dbcon,"SELECT * FROM orders_customers WHERE user_id = $user_id ");
			$rowphone1 = @mysqli_fetch_assoc($phone1R);
			$phone1=$rowphone1['phone1'];
			
			
			$phone1_typeR = @mysqli_query($dbcon,"SELECT * FROM orders_customers WHERE user_id = $user_id ");
			$rowphone1_type = @mysqli_fetch_assoc($phone1_typeR);
			$phone1_type=$rowphone1_type['phone1_type'];
            //phone types first:
			$phone1_typexR = @mysqli_query($dbcon,"SELECT details FROM orders_phone_type WHERE codex = ".$phone1_type." ");
			$rowphone1_typexR = @mysqli_fetch_assoc($phone1_typexR);
			$phone1_typex=$rowphone1_typexR['details'];
			
			
			$ccountryR =  @mysqli_query($dbcon,"SELECT * FROM orders_customers WHERE user_id = $user_id ");
			$rowccountry = @mysqli_fetch_assoc($ccountryR);
			$ccountry=$rowccountry['country'];
			
			$sql1 = @mysqli_query($dbcon,"SELECT details FROM orders_country_codes WHERE codex = $ccountry ");
			$rowphone_full = @mysqli_fetch_assoc($sql1);
			//$$ccountry=$rowphone_full['details'];
			
			$phone_full = $rowphone_full['details'].'-'.$phone1.' ('.$phone1_typex.')';

				if ($row['numpages'] == 1) {
				$pagesx = "Page";
				} else {
				$pagesx = "Pages";
				}
			
				

				//calculate time remaining
	
				$secondsPerDay = ((24 * 60) * 60);
				
				$timeStamp = time();


				$date  = getdate($row['expiry']);
				$month = $date["mon"];
				$day   = $date["mday"];
				$year  = $date["year"];
				$hours = $date['hours'];
				$minutes = $date['minutes'];

				$expiryDate = $day. '/' .  $month . '/' . $year.' at '.$hours.':'.$minutes;

				$expiryx = number_format(( $row['expiry'] - $timeStamp) / $secondsPerDay);
			
				$title = stripslashes($row['topic']);


				if ($row['o_interval'] == 0 )	{
					$spacing = "Double Spaced";
				} else {
					$spacing = "Single Spaced";
				}
			
				if ($row['langstyle'] == 1 )	{
					$langstyle = "English (U.S.)";
				} else if ($row['langstyle'] == 2 ) {
					$langstyle = "English (U.K.)";
				}

				
				$allow_night_calls = $row['allow_night_calls'];
				

				$num_messages = @mysqli_num_rows(@mysqli_query($dbcon,"SELECT * FROM orders_messages WHERE order_id = $order_id AND site_id = '$site_id' AND (sender = 'customer' OR receiver = 'customer') AND published = '1' "));

				$num_files = @mysqli_num_rows(@mysqli_query($dbcon,"SELECT * FROM orders_orderfiles WHERE order_id = '$order_id' AND site_id = '$site_id' AND active = 1  "));

		


		if ($row['status'] == 2 && $row['writer_id'] != 0 ) {

			$text .='
	<form method="POST" action="">
	<input type="hidden" name="order_category" value="'.$row['order_category'].'">
 		<a name="ratings"></a>
	<h3>Complete order </h3>';


		//check if we rated this writer before on the same order
		$sql_rating = mysqli_query($dbcon,"SELECT * FROM  `orders_writers_ratings_temp` WHERE `by` = 'Customer' AND order_id = '$order_id' AND site_id = '$site_id' AND writer_id = '$row[writer_id]' ");

			if (@mysqli_num_rows($sql_rating) > 0) {
				$text .= '
	<input type="hidden" name="quality" value="0">
	<input type="hidden" name="communication" value="0">
	<input type="hidden" name="expertise" value="0">
	<input type="hidden" name="professionalism" value="0">
	<input type="hidden" name="hire_again" value="0">
	<input type="hidden" name="rating_details" value="0">

 <p><b>Satisfied with order? Please click complete below. If you are not satisfied with the order, please request a revision by composing a message to the admin <a href="#messages">here</a></b></p>


				';
			} else {

				$text .= '
		  <p><b>Satisfied with order? Please take a moment to rate the writer below and click complete. If you are not satisfied with the order, please request a revision by composing a message to the admin <a href="#messages">here</a></b></p>
				<table border="0" width="100%" cellspacing="0" cellpading="0">
		      <tr><th>Quality</th><th>Communication</th><th>Expertise</th><th>Professionalism</th><th>Would Hire again</th></tr>
		      	<tr>
				<td>
					<select name="quality">
						<option value="5">5</option>
						<option value="4">4</option>
						<option value="3">3</option>
						<option value="2">2</option>
						<option value="1">1</option>
					</select>
				</td>
				<td>
					<select name="communication">
						<option value="5">5</option>
						<option value="4">4</option>
						<option value="3">3</option>
						<option value="2">2</option>
						<option value="1">1</option>
					</select>
				</td>
				<td>
					<select name="expertise">
						<option value="5">5</option>
						<option value="4">4</option>
						<option value="3">3</option>
						<option value="2">2</option>
						<option value="1">1</option>
					</select>
				</td>
				<td>
					<select name="professionalism">
						<option value="5">5</option>
						<option value="4">4</option>
						<option value="3">3</option>
						<option value="2">2</option>
						<option value="1">1</option>
					</select>
				</td>
				<td>
					<select name="hire_again">
						<option value="5">5</option>
						<option value="4">4</option>
						<option value="3">3</option>
						<option value="2">2</option>
						<option value="1">1</option>
					</select>

				</td>
			</tr>
			<tr>
				<td>Message</td>
				<td colspan="4"><textarea  name="rating_details" rows="4" cols="65" style=""></textarea></td>
			</tr>
			</table>';


			}


		$text .='
          <p>&nbsp;</p>
          <p><input type="submit" name="submit_rating" value="Complete" /></p>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
		</form>
			';

		}
				
			
			
			
			
			
			$text .=' 			
			<h3>'.stripslashes(nl2br($row['topic'])).'         </h3>
			<p>&nbsp;</p>

			<table border="0" width="100%" cellspacing="0" cellpading="0" id="view1">
			<tr id="view11">
				<td style="width: 35%;"><strong>Order ID:</strong> '.$order_id.'</td>
				<td><b><a href="#messages"><b>Messages :  '.$num_messages.'</b></a></td>				
				<td><b><a href="#files"><b>Files :  '.$num_files.'</b></a></td>			
			</tr>
			<tr id="view12">
				<td><b>Language Style:</b> '.$langstyle. '</td>
				<td><b>Number of sources: </b>'.$row['numberOfSources']. '</td>
				<td><b>Dead-line:</b> '.$expiryx.' days remaining ('.$expiryDate.')</td>			
			</tr>
			<tr id="view13">
				<td><b>Topic:</b> '.stripslashes(nl2br($row['topic'])).'</td>
				<td><b>Type of document:</b> '.$row['doctype_x']. '</td>
				<td><b>Academic Level:</b>'.$row['academic_level']. '</td>
			
			</tr>
			<tr id="view14">
				<td><b>Number of Pages: </b> '.$row['numpages'].' '. $pagesx.'&nbsp;('.$spacing.')</td>
				<td><b>Category</b>: '.$order_category.'</td>
				<td><b>Total: </b>'.$row['total_x']. '&nbsp;</td>	
			</tr>
			<tr id="view15">
				<td><b>VIP Support:</b> '.$row['vipsupport']. '</td>
				<td><b>Allow night calls:</b> '.$allow_night_calls. '</td>
				<td><b>Written by 10 writers:</b> '.$row['top10writer']. '</td>
			</tr>
			<tr id="view16">
				<td><b>Writing Style:</b> '.$stylex. '</td>
				<td><b>Email:</b> '.$email. '</td>
				<td><b>Phone:</b> '.$phone_full. '</td>
			</tr>
             		 <tr id="view16"><td colspan="3"><strong>Details:</strong> </td> </tr>
			<tr><td colspan="3">'.stripslashes(nl2br($row['details'])). '</td> </tr>
	     </table>

          <p>&nbsp;</p>
<a name="files"></a>
<h3>Payments / Invoices  </h3>
			<form name="" action="" method="POST" >
			<table width="100%" border="0" cellspacing="1" cellpadding="4" id="view1">
			<tr  id="view11">
				<td><b>Item</b></td>
				<td><b>Amount</b></td>
				<td><b>Status</b></td>
			</tr>

			<tr  id="view12">
				<td>Order Total</td>
				<td> <b>'.$row['total_x']. '</b>&nbsp; Discount:'.$row['discount_percent_h']. '% ('.$row['curr'].' '.$row['discount_h']. ')</td>
				<td>'.$payment_status.'</td>
			</tr>';

			$additional_sql = mysqli_query($dbcon,"SELECT * FROM orders_additional_payments WHERE order_id =". $_REQUEST['order_id']." ORDER BY id ASC");
			if (@mysqli_num_rows($additional_sql) > 0) {
			$text .='<tr  id="view11">
				<td colspan="3"><b>Additional Payments</b></td>
			</tr>';
				while($additional_row = mysqli_fetch_array($additional_sql)) {
					$additional_currency = $additional_row['currency'];
					$text .=  '
					<tr  id="view12">
						<td>'.$additional_row['reason'].'</td>
						<td>'.$additional_row['currency'].' '.$additional_row['amount'].'</td>
						<td>'.payment_status($additional_row['status']).'</td>
					</tr>

					';

				}

				$additional_sum = mysqli_query($dbcon,"SELECT SUM(amount) AS amount_due FROM orders_additional_payments WHERE order_id = ". $_REQUEST['order_id']." AND status = 0 ");
				list($amount_due) = mysqli_fetch_row($additional_sum);
				if ($amount_due > 0) {
					$total_due = '<span style="text-decoration: underline;">'.$amount_due.' </span>&nbsp;&nbsp;&nbsp;&nbsp;<a href="paynow-additional.php?order_id='.$row['order_id'].'">Pay Now</a>';
				} else {
					$total_due = 0;

				}
				$text .=  '
				<tr><td colspan="3"><div style="text-align: right; font-weight: bold;">Total Additional  Payment Due: '.$additional_currency.' '.$total_due.'</div></td></tr></table>

				';
			} else {
				$text .=  '
				<tr><td colspan="3">&nbsp;</td></tr></table>

				';

			}


				$text .='
<a name="files"></a>
<h3 style="float:left;width:100%;">File (s):  </h3>
			<table border="0" width="100%" cellspacing="0" cellpading="0" id="view2">
              <tr id="view21"><th>#</th><th>File Name</th><th>File Type</th><th>Uploaded by</th><th>Size</th><th>Date</th></tr>';
			$sql = @mysqli_query($dbcon,"SELECT * FROM orders_orderfiles WHERE order_id = '$order_id' AND site_id = '$site_id' AND active = 1 ");
				if (@mysqli_num_rows($sql)==0) {
				$text .='<tr id="view22"><td colspan="6">No files uploaded yet!</td></tr>';
				} else {

$n1 = 1;
	
					while ($files = @mysqli_fetch_array($sql)) {
					$filename = $files['filename'];	
					
					$i = base64_encode($order_id);
					$n = base64_encode($files['filename']);
					
					$e = base64_encode($email);
					$text .='
                  <tr id="view23">
                    <td>'.$n1++.'.</td><td> <a title="Click to Download '.$filename.'" href="view.php?order_id='.$order_id.'&action=download&n='.$n.'&i='.$i.'&e='.$e.'" target="_BLANK">'.$filename.'</a></td><td> ('.$files['type'].')</td><td>'.$files['uploaded_by'].'</td><td>'.format_bytes($files['size']).'</td><td>'.date("F j, Y, g:i a",$files['time']).'</td>
           
                  </tr>
					';
	
					}
	
				} 		
			$text .='                 
                	<tr id="view24">

                    <td colspan="6"><a href="order_file_upload.php">Upload Files</a></strong></td>
                  </tr>
                  
              </table>
		<a name="messages"></a>
			';



		$sql_messages = mysqli_query($dbcon,"SELECT * FROM orders_messages WHERE order_id = $order_id AND site_id = '$site_id' AND (sender = 'customer' OR receiver = 'customer') AND published = '1'  ORDER BY id ASC ");
		if (mysqli_num_rows($sql_messages) == 0) {
		$text .= '<h3> No messages for this order!</h3> ';
	
		} else {
		$text .= '<h3>#'.$order_id.' (Order Messages)</h3> <p>&nbsp;</p>

        ';
        $mn1 = 1;
			while ($row_messages = mysqli_fetch_array($sql_messages)) {
			$color = (is_int($mn1 / 2)) ? $color_one : $color_two;

			$senddate = date("F j, Y, g:i a",$row_messages['date']);



			$senderx = $row_messages['sender'];

			$receiverx = $row_messages['receiver'];
			

			if ($row_messages['flag'] == "0" ) {
				$flag = '<span style="color: #0000FF;" >Normal</span>';
			} elseif ($row_messages['flag'] == "1" ) {
				$flag = '<span style="color: #00FF00;">Urgent</span>';
			} elseif ($row_messages['flag'] == "2" ) {
				$flag = '<span style="color: #FFCD02;">Emergency</span>';
			} elseif ($row_messages['flag'] == "3" ) {
				$flag = '<span style="color: #FF0000;">Critical</span>';
			}

			$text .= '<a name="message_'.$row_messages['id'].'"></a>		
		<table width="100%" border="0" cellspacing="1" cellpadding="4" class='.$color.' id="view3">
			<tr class='.$color.' id="view31">
				<td width="20%" valign="top"><b>From:</b> '.$senderx.'<br /><b>To:</b> '.$receiverx.'<br /><b>Date:</b> '.$senddate.'<br /><b>flag:</b> '.$flag.'</td>
				<td width="79%" valign="top"><b>'.TimeAgo($row_messages['date']).'</b><br />
				<input type="hidden" name="finish" value="1" />
				<input type="hidden" name="message_id" value="'.$row_messages['id'].'" />
				<input type="hidden" name="order_id" value="'.$order_id.'" />
				'.stripslashes(nl2br($row_messages['details'])).'
				<br /><br />
				</td>
			</tr>
		</table>

			';
			$mn1++;
			}
		$text .=' <a name="messages"></a>';
		}

		if ($row['writer_id'] != '0' ) {

			if ($row['status'] != 2 && $row['status'] != 3)
				$writer = '<option value="writer">Writer</option>';
		} else {
			$writer = '';
		}
			
		    $receivers = '	<select name="receiver">
				<option value="admin">Admin </option>
				<option value="" disabled>---------</option>
				<option value="" disabled>---------</option>
				'.$writer.'
				</select>
				';
		
	        $text .= '<br />
        	<form method="POST" action="">
        	<input type="hidden" name="finish" value="1" />
        	<input type="hidden" name="order_id" value="'.$order_id.'" />
        
        	<h3>Compose Message Below</h3>
        
        	<table width="100%" border="0" cellspacing="1" cellpadding="4" style="margin-bottom: 25px;" id="view4">';
        	$text .= '<tr class='.$color_one.'>
        	<td width="20%" valign="top"><b>To:</b> '.$receivers.'<br /><br /><strong>Flag:</strong>&nbsp;&nbsp;<select name="flag">
        		<option style="color: #0000FF;" selected value="0">Normal</option>
        		<option style="color: #00FF00;" value="1">Urgent</option>
        		<option style="color: #FFCD02;" value="2">Emergency</option>
        		<option style="color: #FF0000;" value="3">Critical</option>
        		</select></td>
        	<td width="79%" valign="top"><textarea  name="message_details" class="message_details" rows="15" cols="65" style=""></textarea><br />
        	<input type="submit" name="submit" value="Send" /></td>
	</tr>		';
	$text .='</table></form>';







			
			}
		} 
	} 
} else {
		$title = "Invalid Request!";
		$text .="Invalid request!";
}

add_action('woo_title','insert_custom_title');

function insert_custom_title(){
	global $title;

	return $title;
}
include "header.php";
?>
<!--<style type="text/css"> table{ border-collapse:collapse; } table, th, td{ border: 1px solid #ccc; padding:7px; }.one { background-color: #E2E4FF;} .two { background-color: #FFFFFF;} </style>-->
<div class="view_order">
   <?php echo $text; ?>
</div>
<?php

/*echo "<pre>";
print_r($_SESSION);
echo "</pre>";*/
include "footer.php"; 
?>
