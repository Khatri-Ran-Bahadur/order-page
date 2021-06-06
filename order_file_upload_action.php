<?php

include "vars.php";
$dbcon = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
 include($_SERVER['DOCUMENT_ROOT'].'/order/SMTPconfig.php');
include($_SERVER['DOCUMENT_ROOT'].'/order/SMTPClass.php');
 
 
$msg="";
if(!empty($_FILES['file1']))
  {
      // $path = "/home4/davidnjoroge/attachments/";
	  $sqldir = @mysqli_query($dbcon,"SELECT sitedir FROM orders_configuration WHERE site_id = '$site_id'  ");
//$xemail = @mysql_result($sql,0,'email');
$rowxemail = @mysqli_fetch_assoc($sqldir);
$sitedir=$rowxemail['sitedir'];
	  
   $dirName = $sitedir."/attachments/".$_COOKIE["order_idx"];  
	  @mkdir($dirName,0777);
     $path = $dirName .'/'. basename( $_FILES['file1']['name']); 
    if(move_uploaded_file($_FILES['file1']['tmp_name'], $path)) {
     $msg="The file ".  basename( $_FILES['file1']['name']). 
      " has been uploaded"; 
	  
	  $filename=basename( $_FILES['file1']['name']); 
	  	    $filetype = $_FILES['file1']['type'];
			$filesize = $_FILES['file1']['size'];
			$user_idx=$_COOKIE["user_id"];
			$time = time();
			$order_idx=$_COOKIE["order_idx"];
			$sql = @mysqli_query($dbcon,"SELECT * FROM  orders_configuration ");
	 // $emailx = stripslashes(nl2br(@mysql_result($sql,0,"admin_email"))); 
	 $sqlemail = @mysqli_query($dbcon,"SELECT * FROM  orders_configuration ");
	 $rowdoctype_x=@mysqli_fetch_assoc($sqlemail);
 	 $emailx=$rowdoctype_x['admin_email'];
//$emailx="rakesh.tuttu@gmail.com";
			
		$subject = "#$order_idx $topic : $upload_type";
		$message = "<a href=\"$siteurl\" target=\"_blank\"><img src=\"$email_logo\" alt=\"$companyname\" style=\"margin-bottom:10px\" border=\"0\"></a><br />
			<br />Dear Admin, <br />
			<br />
			A file has been uploaded by Customer to  order #$order_idx <a href='$siteurl/order/view.php?order_id=$order_idx'> $topic</a>.<br />
			<br />
			Details <br />
			--------------- <br />
			Name: <a href='$siteurl/order/view.php?order_id=$order_idx&action=download&n=$n&i=$i&e=$e' title='Click to download if logged in'> $filename </a><br />
			Type:  $upload_type <br />
			<p>Please click <a href='$siteurl/order/view.php?order_id=$order_idx&action=download&n=$n&i=$i&e=$e' title='Click to download if logged in'>here</a> or log into your account and review the file under the Files section on your order detail page.</p>
			<br />
			<span color='#ccc'>
			--<br />
			Thank you, 
			______________________________________________________<br />
			THIS IS AN AUTOMATED RESPONSE. <br />
			***DO NOT RESPOND TO THIS EMAIL****<br />
			</span>";
		
		
		$mail->addAddress($emailx); // Add a recipient

		$mail->Subject = $subject;

		$mail->MsgHTML($message);

		$mail->IsHTML(true);	

		$result = $mail->Send();			
	  
	  $sql_insert = "INSERT INTO orders_orderfiles 
					(order_id, site_id, filename, filepath, filetype, user_id, type, active, uploaded_by, size, time) VALUES 
			('$order_idx', '$site_id', '$filename',  '$path', 'Reference_Materials', '$user_idx', '$upload_type', '1', 'Customer', '$filesize', '$time')"; 
			
					mysqli_query($dbcon,$sql_insert);
    } else{
       $msg="";
    }
  }
?>