<?php

include "classes/vars.php";


if ($_POST['tfdfbfdhdgfhbyrtgrhtbqwywegkglhjdhsfuryr6585697jfhgshdhd'] == md5($writer_site_url)) {
	$order_id_decoded = base64_decode($_POST['UYtrdwIUQAZXCVBHgfdroooo34oqrqwrq424243253tewterwetkillyou']);
	$site_id_decoded = base64_decode($_POST['talktomeagaingh334vgh343434dftdftyweghsdghsgdhgdhsgdsdsd']);
	$order_id_arr = explode(";",$order_id_decoded);
	$site_id_arr = explode(";",$site_id_decoded);
	$order_id = base64_decode($order_id_arr[2]);
	$site_id = base64_decode($site_id_arr[2]);
	$upload_type_decoded = base64_decode($_POST['HGSFHhdgfhdgfhgereryerthshgsghdgIIUTQXCmgjgh1423']);
	$upload_type_arr = explode(";",$upload_type_decoded);
	$upload_type = base64_decode($upload_type_arr[2]);

	$dirName = $sitedir."/attachments/".$order_id;
	@mkdir($dirName,0777);
	$filename = $_FILES['uploadedfile']['name'];

	$uploadFile = $dirName."/".$filename;

	if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $uploadFile)) {

		$msg .= $_FILES['file']['tmp_name'][$fi].' => '.$uploadFile .'<br />';
	} else {
		$msg .= 'Error: '.$_FILES['file']['error'][$fi].'<br />';
	}
	
	/*
	$subject = "File Upload Success: #$order_id.$site_id";
	$message = "Dear Admin, <br />
	<br />
	This is just to inform you that file $filename for order #$order_id.$site_id has been successfully moved to the server via CURL from the writers website. It is still disabled so the customer can not see it yet. Please review and take the necessary action.
	<span color='#ccc'>
	--<br />
	Thank you,<br />
	Administrator<br />
	$siteurl<br />
	______________________________________________________<br />
	THIS IS AN AUTOMATED RESPONSE. <br />
	***DO NOT RESPOND TO THIS EMAIL****<br />
	</span>";
			       
	require_once "Mail.php";
	$email_x = explode(",", $siteemail);


	foreach ($email_x as $ekey => $evalue) {
		$email = trim($evalue);
		$site_headers = array ('From' => $smtp_from,
				  'To' => $email,
				  'Subject' => $subject, 'Content-type' => 'text/html; charset=utf-8; format=flowed');
		$smtp = Mail::factory('smtp',
			  array ('host' => $smtp_host,
				    'port' =>  $smtp_port,
				    'auth' => true,
				    'username' => $smtp_user,
				    'password' => $smtp_pass));
			$mail = $smtp->send($email, $site_headers, $message);

		if (PEAR::isError($mail)) {
			 $text .= "<p>" . $mail->getMessage() . "</p>";
		} 
	}

	*/
		
}



exit();

?>
