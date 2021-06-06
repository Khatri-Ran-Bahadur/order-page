<?php
include "classes/vars.php";
include "classes/sessions.php";
$dbcon = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($logged == 0) {
header("Location: " . $siteurl);
}

@session_start();
$order_idx = @$_SESSION["order_idx"];

$dirName = $sitedir."/attachments/".$order_idx;

$file = @$_FILES[$_POST['id']];

$xxx = @$_FILES[$_POST['id']]['name'];

if (isset($_POST['id'])) {
  	if(isAllowedExtension($xxx)) {
	//$uploadFile=$_GET['dirname']."/".$_FILES[$_POST['id']]['name']; for security reasons,  hardcode the name of the directrory.
	@mkdir($dirName,0777);
	$filename = $_FILES[$_POST['id']]['name'];
	$filename = str_replace(" ","_",$filename);
	$filetype = $_FILES[$_POST['id']]['type'];
	$uploadFile = $dirName."/".$filename;
	
		if(!is_dir($_GET['dirname'])) {
		echo "Failed to find the final upload directory: $dirName";
		}
		if (!copy($_FILES[$_POST['id']]['tmp_name'], $uploadFile)) {	
			echo "Failed to upload file";
		} else {
		$filepath = $uploadFile;
		$sql2 = @mysqli_query($dbcon,"SELECT * FROM orders_orderfiles WHERE order_id = '$order_idx' AND filename = '$filename' AND user_id = '$user_id' "); 
			if (@mysqli_num_rows($sql2)==0) {


			$sqlu = @mysqli_query($dbcon,"SELECT * FROM orders_orders WHERE order_id = '$order_idx' ");
			//$topic = @mysql_result($sqlu,0,"topic");
			$rowtopic = @mysqli_fetch_assoc($sqlu);
			$topic=$rowtopic['topic'];
			
			$subject = "$topic : File Uploaded by Customer";
				$message = "Dear Admin, <br />
			<br />
			A file has been uploaded by user $email to the order <b><a href='$siteurl/wp-admin/view_order.php?order_id=$order_idx'>$topic</a></b>.<br />
			<br />
			Details <br />
			--------------- <br />
			Name: <b> $filename </b><br />
			Type: <b> Order Files </b><br />
			<p>Please <a href='$siteurl/wp-admin/view_order.php?order_id=$order_idx'>log into admin</a> section and review the file under attachments</p>
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
				mail($siteemail,$subject,$message,$headers);



			@mysqli_query($dbcon,"INSERT INTO orders_orderfiles (order_id, filename, filepath, filetype, user_id, type, active) VALUES ('$order_idx', '$filename', '$filepath', '$filetype', '$user_id', 'Order Files', '1')");
			}
	
		}
	}
	
} else {
//$uploadFile=$_GET['dirname']."/".$_GET['filename']; // removed for security reasons (happend with my demo )
//$namex = $_GET['filename'];
$filename = str_replace(" ","_",$_GET['filename']);
$uploadFile = $dirName."/".$filename;
//$uploadFile = str_replace(" ","_",$uploadFile);
$i = base64_encode($order_idx);
$n = base64_encode($filename);
$e = base64_encode($email);

	if (file_exists($uploadFile)) {
		echo 'File uploaded. <a href="download.php?n='.$n.'&i='.$i.'&e='.$e.'" target="_BLANK">Open File</a> &nbsp;&nbsp;&nbsp; 
<a href="deletefile.php?n='.$n.'&i='.$i.'&e='.$e.'">Delete File</a>';
	}
	else {
		echo "<img src='loading.gif' alt='loading...' />";

	}
}
?>
