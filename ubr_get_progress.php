<?php
//******************************************************************************************************
//	Name: ubr_get_progress.php
//	Revision: 3.2
//	Date: 11:05 PM October 8, 2009
//	Link: http://uber-uploader.sourceforge.net
//	Developer: Peter Schmandra
//	Description: Gather stats on an existing upload
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
//******************************************************************************************************************************

//******************************************************************************************************************************
//	The following possible query string formats are assumed
//
//	1. ?upload_id=32_character_alpha_numeric_string&start_time=unix_time&total_upload_size=total_upload_size_in_bytes
//	2. ?about
//******************************************************************************************************************************

$THIS_VERSION = "3.2";     // Version of this file
$UPLOAD_ID = '';           // Initialize upload id

require_once 'ubr_ini.php';
require_once 'ubr_lib.php';

if($_INI['php_error_reporting']){ error_reporting(E_ALL); }

if(isset($_GET['upload_id']) && preg_match("/^[a-zA-Z0-9]{32}$/", $_GET['upload_id']) && isset($_GET['start_time']) && isset($_GET['total_upload_size'])){ $UPLOAD_ID = $_GET['upload_id']; }
elseif(isset($_GET['about'])){ kak("<u><b>UBER UPLOADER GET PROGRESS</b></u><br>UBER UPLOADER VERSION =  <b>" . $_INI['uber_version'] . "</b><br>UBR_GET_PROGRESS = <b>" . $THIS_VERSION . "<b>", 1, __LINE__, $_INI['path_to_css_file']); }
else{ kak("<span class='ubrError'>ERROR</span>: Invalid parameters passed<br>", 1, __LINE__, $_INI['path_to_css_file']); }

$total_bytes_read = 0;
$files_uploaded = 0;
$current_filename = '';
$bytes_read = 0;
$upload_active = 0;
$flength_file = $UPLOAD_ID . '.flength';
$path_to_flength_file = $TEMP_DIR . $UPLOAD_ID . '.dir/' . $flength_file;
$temp_upload_dir = $TEMP_DIR . $UPLOAD_ID . '.dir';

// If the "/temp_dir/upload_id.dir/upload_id.flength" file exist, the upload is active
if(@is_readable($path_to_flength_file)){
	$upload_active = 1;

	if($_INI['cgi_upload_hook']){
		// Get upload status by reading the "/temp_dir/upload_id.dir/upload_id.hook" file
		$hook_file = $TEMP_DIR . $UPLOAD_ID . '.dir/' . $UPLOAD_ID . '.hook';

		if(($upload_status = readUbrFile($hook_file, $_INI['debug_ajax'])) !== false){ list($total_bytes_read, $files_uploaded, $current_filename, $bytes_read) = explode($DATA_DELIMITER, $upload_status); }
		else{ $upload_active = 0; }
	}
	else{
		// Get upload status by reading the "/temp_dir/upload_id.dir" directory
		if(($dp = @opendir($temp_upload_dir)) !== false){
			while(($file_name = @readdir($dp)) !== false){
				if(($file_name !== '.') && ($file_name !== '..') && ($file_name !== $flength_file)){
					$total_bytes_read += sprintf("%u", @filesize($temp_upload_dir . '/' . $file_name));
					$files_uploaded++;
				}
			}
			@closedir($dp);

			if($files_uploaded > 0){ $files_uploaded -= 1; }
		}
		else{ $upload_active = 0; }
	}
}

if($upload_active && $total_bytes_read < $_GET['total_upload_size']){
	$lapsed_time = time() - $_GET['start_time'];

	if($_INI['debug_ajax']){
		if($_INI['cgi_upload_hook']){ showDebugMessage("Set progress: bytes uploaded=" . $total_bytes_read . " files uploaded=" . $files_uploaded . " current file=" . $current_filename . " bytes read=" . $bytes_read . " lapsed time=" . $lapsed_time); }
		else{ showDebugMessage("Set progress: bytes uploaded=" . $total_bytes_read . " files uploaded=" . $files_uploaded . " lapsed time=" . $lapsed_time); }
	}

	setProgressStatus($total_bytes_read, $files_uploaded, $current_filename, $bytes_read, $lapsed_time);
	getProgressStatus($_INI['get_progress_speed']);
}
else{
	stopDataLoop();

	if($_INI['debug_ajax']){ showDebugMessage("<span class='ubrWarning'>WARNING</span>: No active upload detected $path_to_flength_file"); }
}

?>