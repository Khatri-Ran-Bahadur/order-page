<?php
//*******************************************************************************************************************
//	Name: ubr_link_upload.php
//	Revision: 3.3
//	Date: 10:27 PM November 23, 2009
//	Link: http://uber-uploader.sourceforge.net
//	Developer: Peter Schmandra
//	Description: Creates an upload_id.link file containing all the config settings
//
//	Copyright (C) 2009  Peter Schmandra
//
//	This file is part of Uber-Uploader.
//
//	Uber-Uploader is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, either version 3 of the License, or
//	(at your option) any later version.
//
//	Uber-Uploader is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
//	GNU General Public License for more details.
//
//	You should have received a copy of the GNU General Public License
//	along with Uber-Uploader. If not, see http://www.gnu.org/licenses/.
//
//********************************************************************************************************************

//********************************************************************************************************************
//	The following possible query string formats are assumed
//
//	1. No query string
//	2. ?about
//********************************************************************************************************************

$THIS_VERSION = '3.3';         // Version of this file
$UPLOAD_ID = '';               // Initialize upload id

require_once 'ubr_ini.php';
require_once 'ubr_lib.php';

if($_INI['php_error_reporting']){ error_reporting(E_ALL); }

if(isset($_GET['about'])){ kak("<u><b>UBER UPLOADER LINK UPLOAD</b></u><br>UBER UPLOADER VERSION =  <b>" . $_INI['uber_version'] . "</b><br>UBR_LINK_UPLOAD = <b>" . $THIS_VERSION . "<b><br>\n", 1, __LINE__, $_INI['path_to_css_file']); }
else{
	if(!isset($_POST['upload_file']) || empty($_POST['upload_file'])){ kak("<span class='ubrError'>ERROR</span>: Invalid parameters passed<br>", 1, __LINE__, $_INI['path_to_css_file']); }

	/////////////////////////////////////////////////////////////////////////////////////
	//	ATTENTION
	//
	//	Put your authentication code here. eg.
	//
	//	if(!authUser($_COOKIE['uber_user']){
	//		stopUpload();
	//		showAlertMessage("<span class='ubrError'>ERROR</span>: Access Denied", 1);
	//	}
	////////////////////////////////////////////////////////////////////////////////////
}

// Set config file
if($_INI['multi_configs_enabled']){
	///////////////////////////////////////////////////////////////////////////////
	//	ATTENTION
	//
	//	Put your multi config file code here. eg.
	//
	//	if($_SESSION['user_name'] == 'TOM'){ $config_file = 'tom_config.php'; }
	//	if($_COOKIE['user_name'] == 'TOM'){ $config_file = 'tom_config.php'; }
	///////////////////////////////////////////////////////////////////////////////
}
else{ $config_file = $_INI['default_config']; }

// Load config file
require_once $config_file;

// Generate upload id
$UPLOAD_ID = generateUploadID();

// Format link file path
$PATH_TO_LINK_FILE = $TEMP_DIR . $UPLOAD_ID . ".link";

//Pass ini settings via the link file
$_CONFIG['temp_dir'] = $TEMP_DIR;
$_CONFIG['upload_id'] = $UPLOAD_ID;
$_CONFIG['path_to_link_file'] = $PATH_TO_LINK_FILE;
$_CONFIG['redirect_after_upload'] = $_INI['redirect_after_upload'];
$_CONFIG['embedded_upload_results'] = $_INI['embedded_upload_results'];
$_CONFIG['cgi_upload_hook'] = $_INI['cgi_upload_hook'];
$_CONFIG['debug_upload'] = $_INI['debug_upload'];
$_CONFIG['delete_link_file'] = $_INI['delete_link_file'];
$_CONFIG['purge_temp_dirs'] = $_INI['purge_temp_dirs'];
$_CONFIG['purge_temp_dirs_limit'] = $_INI['purge_temp_dirs_limit'];

///////////////////////////////////////////////////////////////////////////////////////////////
//	ATTENTION
//
//	You can pass data via the link file by creating or over-riding config values. eg.
//
//	$_CONFIG['max_upload_size'] = $_SESSION['new_max_upload_size'];
//	$_CONFIG['max_upload_size'] = $_COOKIE['new_max_upload_size'];
//	$_CONFIG['max_upload_size'] = $_POST['new_max_upload_size'];
//
///////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////////////////
//	ATTENTION
//
//	All upload form values including file names are available to this script through $_POST.
//	Therefore it is possible to create or over-ride config values based on user input. eg.
//
//	if($_POST['upload_location'] == 1){ $_CONFIG['upload_dir'] = '/var/home/docs/foo/'; }
//	else{ $_CONFIG['upload_dir'] = '/var/home/docs/bar/'; }
//
//	To access file names simply use a for loop. eg
//
//	for($i = 0; $i < count($_POST['upload_file']); $i++){
//		$file_name = rawurldecode($_POST['upload_file'][$i]);
//	}
//
////////////////////////////////////////////////////////////////////////////////////////////////

// Create temp, upload and log directories
if(!createDir($TEMP_DIR)){
	if($_INI['debug_ajax']){ showDebugMessage('Failed to create temp_dir ' . $TEMP_DIR); }
	showAlertMessage("<span class='ubrError'>ERROR</span>: Failed to create temp_dir", 1);
}
if(!createDir($_CONFIG['upload_dir'])){
	if($_INI['debug_ajax']){ showDebugMessage('Failed to create upload_dir ' . $_CONFIG['upload_dir']); }
	showAlertMessage("<span class='ubrError'>ERROR</span>: Failed to create upload_dir", 1);
}
if($_CONFIG['log_uploads']){
	if(!createDir($_CONFIG['log_dir'])){
		if($_INI['debug_ajax']){ showDebugMessage('Failed to create log_dir ' . $_CONFIG['log_dir']); }
		showAlertMessage("<span class='ubrError'>ERROR</span>: Failed to create log_dir", 1);
	}
}

// Purge old .link files
if($_INI['purge_link_files']){ purgeFiles($TEMP_DIR, $_INI['purge_link_limit'], 'link', $_INI['debug_ajax']); }

// Purge old .redirect files
if($_INI['purge_redirect_files']){ purgeFiles($TEMP_DIR, $_INI['purge_redirect_limit'], 'redirect', $_INI['debug_ajax']); }

// Show debug message
if($_INI['debug_ajax']){ showDebugMessage("Upload ID = $UPLOAD_ID"); }

// Write link file
if(writeLinkFile($_CONFIG, $DATA_DELIMITER)){
	if($_INI['debug_ajax']){ showDebugMessage('Created link file ' . $PATH_TO_LINK_FILE); }
	startUpload($UPLOAD_ID, $_INI['debug_upload'], $_INI['debug_ajax']);
}
else{
	if($_INI['debug_ajax']){ showDebugMessage('Failed to create link file ' . $PATH_TO_LINK_FILE); }
	showAlertMessage("<span class='ubrError'>ERROR</span>: Failed to create link file: $UPLOAD_ID.link", 1);
}

?>