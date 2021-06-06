<?php

include "vars.php";
$dbcon = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
 include($_SERVER['DOCUMENT_ROOT'].'/order/SMTPconfig.php');
	include($_SERVER['DOCUMENT_ROOT'].'/order/SMTPClass.php');

page_protect();




	$THIS_VERSION = '3.4';        // Version of this file

	//require_once 'ubr_ini.php';
	//require_once 'ubr_lib.php';

	//if($_INI['php_error_reporting']){ error_reporting(E_ALL); }

	//Set config file
	if($_INI['multi_configs_enabled']){
		//////////////////////////////////////////////////////////////////////////////
		//	ATTENTION
		//
		//	Put your multi config file code here. eg
		//
		//	if($_SESSION['user_name'] == 'TOM'){ $config_file = 'tom_config.php'; }
		//	if($_COOKIE['user_name'] == 'TOM'){ $config_file = 'tom_config.php'; }
		//////////////////////////////////////////////////////////////////////////////
	}
	else{ $config_file = $_INI['default_config']; }

	// Load config file
	require_once $config_file;

	//debug($_CONFIG['config_file_name'], $_CONFIG); exit();

	if($_INI['debug_php']){ phpinfo(); exit(); }
	elseif($_INI['debug_config']){ debug($_CONFIG['config_file_name'], $_CONFIG); exit(); }
	elseif(isset($_GET['about'])){
		kak("<u><b>UBER UPLOADER FILE UPLOAD</b></u><br>UBER UPLOADER VERSION =  <b>" . $_INI['uber_version'] . "</b><br>UBR_FILE_UPLOAD = <b>" . $THIS_VERSION . "</b><br>\n", 1, __LINE__, $_INI['path_to_css_file']);
	}


function insert_custom_title() {

	return "Upload files to order";
}
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
add_filter('wp_title','insert_custom_title');

include "header.php";

?>
<div class="view_order">
<?php echo customer_orders_menu(); ?>
<p>Upload files to your order <a href="view.php?order_id=<?=$_COOKIE["order_idx"];?>"><?=$_COOKIE["order_idx"];?></a>. The system supports many files upload at a time. Click on “CHOOOSE FILE” and open the file, repeat the same to add subsequent files until all your files are listed. Click on UPLOAD button and you will view the Upload in progress as the files are being uploaded. Upon completion, click next to proceed to your order page.</p>
<p><i> NB: The system will not allow you to upload duplicated files if you have such files rename it to have a unique name, to remove a file from the list click on the X button at the right hand side of the file name.</i></p>
		<div id="main_container">
			<?php if($_INI['debug_ajax']){ ?><div id='ubr_debug'></div><?php } ?>
			<div id="ubr_alert"></div>

			<!-- Progress Bar -->
			<div id="progress_bar_container">
				<div id="upload_stats_toggle">&nbsp;</div>
				<div id="progress_bar_background">
					<div id="progress_bar"></div>
				</div>
				<div id="percent_complete">&nbsp;</div>
			</div>

			<br clear="all">

			<!-- Upload Stats -->
			<?php if($_CONFIG['show_files_uploaded'] || $_CONFIG['show_current_position'] || $_CONFIG['show_elapsed_time'] || $_CONFIG['show_est_time_left'] || $_CONFIG['show_est_speed']){ ?>
				<div id="upload_stats_container">
					<?php if($_CONFIG['show_files_uploaded']){ ?>
					<div class='upload_stats_label'>&nbsp;Files Uploaded:</div>
					<div class='upload_stats_data'><span id="files_uploaded">0</span> of <span id="total_uploads">0</span></div>
					<?php }if($_CONFIG['show_current_position']){ ?>
					<div class='upload_stats_label'>&nbsp;Current Position:</div>
					<div class='upload_stats_data'><span id="current_position">0</span> / <span id="total_kbytes">0</span> KBytes</div>
					<?php }if($_INI['cgi_upload_hook'] && $_CONFIG['show_current_file']){ ?>
					<div class='upload_stats_label'>&nbsp;Current File Uploading:</div>
					<div class='upload_stats_data'><span id="current_file"></span></div>
					<?php }if($_CONFIG['show_elapsed_time']){ ?>
					<div class='upload_stats_label'>&nbsp;Elapsed Time:</div>
					<div class='upload_stats_data'><span id="elapsed_time">0</span></div>
					<?php }if($_CONFIG['show_est_time_left']){ ?>
					<div class='upload_stats_label'>&nbsp;Est Time Left:</div>
					<div class='upload_stats_data'><span id="est_time_left">0</span></div>
					<?php }if($_CONFIG['show_est_speed']){ ?>
					<div class='upload_stats_label'>&nbsp;Est Speed:</div>
					<div class='upload_stats_data'><span id="est_speed">0</span> KB/s.</div>
					<?php } ?>
				</div>
				<br clear="all">
			<?php } ?>

			<!-- Container for upload iframe -->
			<div id="upload_container"></div>
<p id="loaded_n_total"></p>
			<!-- Start Upload Form -->
			<?php /*?><form id="ubr_upload_form" name="ubr_upload_form" method="post" enctype="multipart/form-data" action="#" onSubmit="return UberUpload.linkUpload();">
				<noscript><span class="ubrError">ERROR</span>: Javascript must be enabled to use Uber-Uploader.<br><br></noscript>
				<div id="file_picker_container"></div>
				<div id="upload_slots_container"></div>
				<div id="upload_form_values_container">
				<!-- Add Your Form Values Here -->
				</div>
				<div id="upload_buttons_container"><input type="button" id="reset_button" name="reset_button" value="Reset">&nbsp;&nbsp;&nbsp;<input type="submit" id="upload_button" name="upload_button" value="Upload"></div>
			</form><?php */?>
			
			<?php /*?><form enctype="multipart/form-data" action="" method="POST">
    <p>Upload your file</p>
	<p><?php echo $msg; ?></p>
    <input type="file" name="uploaded_file"></input><br />
    <input type="submit" value="Upload"></input>
  </form><?php */?>
  <form id="upload_form" enctype="multipart/form-data" method="post">
  <input type="file" name="file1" id="file1">
   <input type="hidden" name="submit" value="submit"><br>
    <input type="button" value="Upload File" onclick="uploadFile()"> 
  <progress id="progressBar" value="0" max="100" style="width:300px;"></progress>
  <h3 id="status"></h3>
  
</form>
		</div>
		<br clear="all">
		

</div>
<link href="http://hayageek.github.io/jQuery-Upload-File/4.0.11/uploadfile.css" rel="stylesheet">
 <script src="http://hayageek.github.io/jQuery-Upload-File/4.0.11/jquery.uploadfile.min.js"></script>
<script>
function _(el) {
  return document.getElementById(el);
}

function uploadFile() {
  var file = _("file1").files[0];
  
  if(!file.name==''){
  var formdata = new FormData();
  formdata.append("file1", file);
  var ajax = new XMLHttpRequest();
  ajax.upload.addEventListener("progress", progressHandler, false);
  ajax.addEventListener("load", completeHandler, false);
  ajax.addEventListener("error", errorHandler, false);
  ajax.addEventListener("abort", abortHandler, false);
  ajax.open("POST", "<?php echo site_url()?>/order/order_file_upload_action.php");
  ajax.send(formdata);
  }else{
  alert('select file first!');
  }
}

function progressHandler(event) {
  _("loaded_n_total").innerHTML = "File Uploaded Succesfully!";
  var percent = (event.loaded / event.total) * 100;
  _("progressBar").value = Math.round(percent);
  _("status").innerHTML = Math.round(percent) + "% uploaded... please wait";
}

function completeHandler(event) {
  _("status").innerHTML = event.target.responseText;
  _("progressBar").value = 0;
  
  document.getElementById("file1").value = null;
}

function errorHandler(event) {
  _("status").innerHTML = "Upload Failed";
}

function abortHandler(event) {
  _("status").innerHTML = "Upload Aborted";
}
</script>
<?php


include "footer.php"; 
?>
