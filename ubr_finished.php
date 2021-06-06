<?php
include "vars.php";
 $dbcon = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);


page_protect();


	$THIS_VERSION = "3.9";                                // Version of this file
	$UPLOAD_ID = '';                                      // Initialize upload id

/*	require_once 'ubr_ini.php';
	require_once 'ubr_lib.php';
	require_once 'ubr_finished_lib.php';*/

	//if($_INI['php_error_reporting']){ error_reporting(E_ALL); }

	header('Content-type: text/html; charset=UTF-8');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Last-Modified: '.date('r'));
	header('Cache-Control: no-store, no-cache, must-revalidate');
	header('Cache-Control: post-check=0, pre-check=0', FALSE);
	header('Pragma: no-cache');

	if(isset($_GET['upload_id']) && preg_match("/^[a-zA-Z0-9]{32}$/", $_GET['upload_id'])){ $UPLOAD_ID = $_GET['upload_id']; }
	elseif(isset($_GET['about'])){ kak("<u><b>UBER UPLOADER FINISHED PAGE</b></u><br>UBER UPLOADER VERSION =  <b>" . $_INI['uber_version'] . "</b><br>UBR_FINISHED = <b>" . $THIS_VERSION . "<b><br>\n", 1 , __LINE__, $_INI['path_to_css_file']); }
	else{ kak("<span class='ubrError'>ERROR</span>: Invalid parameters passed<br>", 1, __LINE__, $_INI['path_to_css_file']); }

	//Declare local values
	$_XML_DATA = array();                                          // Array of xml data read from the upload_id.redirect file
	$_CONFIG_DATA = array();                                       // Array of config data read from the $_XML_DATA array
	$_POST_DATA = array();                                         // Array of posted data read from the $_XML_DATA array
	$_FILE_DATA = array();                                         // Array of 'FileInfo' objects read from the $_XML_DATA array
	$_FILE_DATA_TABLE = '';                                        // String used to store file info results nested between <tr> tags
	$_FILE_DATA_EMAIL = '';                                        // String used to store file info results

	$xml_parser = new XML_Parser;                                  // XML parser
	$xml_parser->setXMLFile($TEMP_DIR, $_GET['upload_id']);        // Set upload_id.redirect file
	$xml_parser->setXMLFileDelete($_INI['delete_redirect_file']);  // Delete upload_id.redirect file when finished parsing
	$xml_parser->parseFeed();                                      // Parse upload_id.redirect file

	// Display message if the XML parser encountered an error
	if($xml_parser->getError()){ kak($xml_parser->getErrorMsg(), 1, __LINE__, $_INI['path_to_css_file']); }

	$_XML_DATA = $xml_parser->getXMLData();                        // Get xml data from the xml parser
	$_CONFIG_DATA = getConfigData($_XML_DATA);                     // Get config data from the xml data
	$_POST_DATA  = getPostData($_XML_DATA);                        // Get post data from the xml data
	$_FILE_DATA = getFileData($_XML_DATA);                         // Get file data from the xml data

	// Output XML DATA, CONFIG DATA, POST DATA, FILE DATA to screen and exit if DEBUG_ENABLED.
	if($_INI['debug_finished']){
		debug("<br><u>XML DATA</u>", $_XML_DATA);
		debug("<u>CONFIG DATA</u>", $_CONFIG_DATA);
		debug("<u>POST DATA</u>", $_POST_DATA);
		debug("<u>FILE DATA</u>", $_FILE_DATA);

		exit();
	}

	/////////////////////////////////////////////////////////////////////////////////////////////////////
	//
	//           *** ATTENTION: ENTER YOUR CODE HERE !!! ***
	//
	//	This is a good place to put your post upload code. Like saving the
	//	uploaded file information to your DB or doing some image
	//	manipulation. etc. Everything you need is in the
	//	$_XML_DATA, $_CONFIG_DATA, $_POST_DATA and $_FILE_DATA arrays.
	//
	/////////////////////////////////////////////////////////////////////////////////////////////////////
	//	NOTE: You can now access all XML values below this comment. eg.
	//
	//	$_XML_DATA['upload_dir']; or $_XML_DATA['link_to_upload'] etc
	/////////////////////////////////////////////////////////////////////////////////////////////////////
	//	NOTE: You can now access all config values below this comment. eg.
	//
	//	$_CONFIG_DATA['upload_dir']; or $_CONFIG_DATA['link_to_upload'] etc
	/////////////////////////////////////////////////////////////////////////////////////////////////////
	//	NOTE: You can now access all post values below this comment. eg.
	//
	//	if(isset($_POST_DATA['client_id'])){ do something; }
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	NOTE: You can now access all file (slot, name, size, type, status, status_desc) values below this comment. eg.
	//
	//	for($i = 0; $i < count($_FILE_DATA); $i++){
	//		$file_slot = $_FILE_DATA[$i]->getFileInfo('slot');
	//		$file_name = $_FILE_DATA[$i]->getFileInfo('name');
	//		$file_size = $_FILE_DATA[$i]->getFileInfo('size');
	//		$file_type = $_FILE_DATA[$i]->getFileInfo('type');
	//		$file_status = $_FILE_DATA[$i]->getFileInfo('status');
	//		$file_status_desc = $_FILE_DATA[$i]->getFileInfo('status_desc');
	//	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	Create Thumnail Example
	//
	//	createThumbFile(source_file_path, source_file_name, thumb_file_path, thumb_file_name, thumb_file_width, thumb_file_height)
	//
	//	EXAMPLE
	//	$file_extension = getFileExtension($_FILE_DATA[0]->name);
	//
	//	if($file_extension == 'jpg' || $file_extension == 'jpeg' || $file_extension == 'png'){ $success = createThumbFile($_CONFIG_DATA['upload_dir'], $_FILE_DATA[0]->name, $_CONFIG_DATA['upload_dir'], 'thumb_' . $_FILE_DATA[0]->name, 120, 100); }
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	// Format upload results
	$_FORMATTED_UPLOAD_RESULTS = getFormattedUploadResults($_FILE_DATA, $_CONFIG_DATA, $_POST_DATA);

	// Create and send email
	if($_CONFIG_DATA['send_email_on_upload']){ emailUploadResults($_FILE_DATA, $_CONFIG_DATA, $_POST_DATA,$_COOKIE['email'],$user_id,$admin_url,$_COOKIE["order_idx"],$headers,$smtp_from,$smtp_host,$smtp_port,$smtp_user,$smtp_pass,$customer_details); }


$order_idx = $_COOKIE["order_idx"];

$dirName = $sitedir."/attachments/".$order_idx;

$title = "Upload files finished";





	for($i = 0; $i < count($_FILE_DATA); $i++){
		$file_slot = $_FILE_DATA[$i]->getFileInfo('slot');
		$file_name = $_FILE_DATA[$i]->getFileInfo('name');
		$file_size = $_FILE_DATA[$i]->getFileInfo('size');
		$file_type = $_FILE_DATA[$i]->getFileInfo('type');
		$file_status = $_FILE_DATA[$i]->getFileInfo('status');
		$file_status_desc = $_FILE_DATA[$i]->getFileInfo('status_desc');

		$filepath = $dirName."/".$file_name;
		$filepath_curl[] = $dirName."/".$file_name;



		$file_sql = "SELECT * FROM orders_orderfiles WHERE order_id = '$order_idx' AND filename = '$filename' AND user_id = '$user_id' ";

		$sql2 = @mysqli_query($dbcon,$file_sql); 
		if (@mysqli_num_rows($sql2)==0) {
			$time = time();
		$insert_sql = "INSERT INTO orders_orderfiles 
		(order_id, site_id, filename, filepath, filetype, user_id, type, active, uploaded_by, size, time) VALUES 
('$order_idx', '$site_id', '$file_name', '$filepath', '$file_type', '$user_id', 'Order Files', '1', 'Customer', '$file_size', '$time')";
			@mysqli_query($dbcon,$insert_sql);
		}

		log_admin_action('uploadfile',"Customer upload file $file_name",$order_idx,$site_id,$customer_details);

	}

	   
	if ($writer_site_integration == 1) {

		list($user_level) = mysqli_num_rows(mysqli_query($dbcon,"SELECT user_level FROM orders_orders WHERE order_id = '$order_idx' AND site_id = '$site_id' AND user_id = '$user_id' "));

		if ($user_level > 0 ) {
			upload_file_to_writer_site($filepath_curl,$order_idx,$site_id,'Customer');
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
<p>The files listed below have been successfully uploaded to your order <a href="view.php?order_id=<?=$_SESSION["order_idx"];?>"><?=$_SESSION["order_idx"];?></a>.<p> <p>The Support Staff and your writer have been notified by the system.</p>
		<div id="main_container">
			<br clear="all"/>
			<div id="upload_results_container">
				<?php print $_FORMATTED_UPLOAD_RESULTS; ?>
			</div>
			<br clear="all"/>

			<?php if(!$_INI['embedded_upload_results']){ ?><br><input type="button" value="Next" ONCLICK="window.location.href='<?=$siteurl;?>/order/view.php?order_id=<?=$order_idx;?>'"><?php } ?>
		</div>
		<br clear="all"/>

</div>
<?php

include "footer.php"; 
?>
