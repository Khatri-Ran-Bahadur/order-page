<?php
//******************************************************************************************************
//	Name: ubr_ini_progress.php
//	Revision: 3.2
//	Date: 12:02 AM December 5, 2009
//	Link: http://uber-uploader.sourceforge.net
//	Developer: Peter Schmandra
//	Description: Initializes Uber-Uploader
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
//***************************************************************************************************************

$TEMP_DIR                            = '/home/smaryxcu/tmp/ubr_temp/';             // *ATTENTION * : The $TEMP_DIR value MUST be duplicated in the "ubr_upload.pl" file. (use an absolute path)
$DATA_DELIMITER                      = '<=>';                        // *ATTENTION * : The $DATA_DELIMITER value MUST be duplicated in the "ubr_upload.pl" file.

$_INI['block_ui_enabled']            = 1;                            // Enable/Disable block UI.
$_INI['cgi_upload_hook']             = 0;                            // Use the CGI hook file to get upload status. Requires CGI.pm >= 3.15.
$_INI['debug_ajax']                  = 0;                            // Enable/Disable AJAX debug mode. Add your own debug messages by calling the "showDebugMessage() " function. UPLOADS POSSIBLE.
$_INI['debug_config']                = 0;                            // Enable/Disable config debug mode. Dumps the loaded config file to screen and exits. UPLOADS IMPOSSIBLE.
$_INI['debug_finished']              = 0;                            // Enable/Disable debug mode in the upload finished page. Dumps all values to screen and exits. UPLOADS POSSIBLE.
$_INI['debug_php']                   = 0;                            // Enable/Disable PHP debug mode. Dumps your PHP settings to screen and exits. UPLOADS IMPOSSIBLE.
$_INI['debug_upload']                = 0;                            // Enable/Disable debug mode in uploader. Dumps your CGI and loaded config settings to screen and exits. UPLOADS IMPOSSIBLE.
$_INI['default_config']              = 'ubr_default_config.php';     // Name of the default config file
$_INI['delete_link_file']            = 1;                            // Enable/Disable delete .link file.
$_INI['delete_redirect_file']        = 1;                            // Enable/Disable delete .redirect file.
$_INI['embedded_upload_results']     = 0;                            // Display the upload results on the file upload page.
$_INI['flength_timeout_limit']       = 6;                            // Max number of seconds to find the flength file.
$_INI['get_progress_speed']          = 1000;                         // CAUTION ! How frequently the web server is poled for upload status. 5000=5 seconds, 1000=1 second, 500=0.5 seconds, 250=0.25 seconds. etc.
$_INI['hook_timeout_limit']          = 6;                            // Max number of seconds to find the .hook file.
$_INI['multi_configs_enabled']       = 0;                            // Enable/Disable multi config files.
$_INI['path_to_block_ui']            = 'jquery.blockUI.js';          // Path Info.
$_INI['path_to_css_file']            = 'ubr.css';                    // Path info.
$_INI['path_to_get_progress_script'] = 'ubr_get_progress.php';       // Path info.
$_INI['path_to_jquery']              = 'jquery-1.3.2.min.js';        // Path Info.
$_INI['path_to_js_script']           = 'ubr_file_upload.js';         // Path info.
$_INI['path_to_link_script']         = 'ubr_link_upload.php';        // Path info.
$_INI['path_to_set_progress_script'] = 'ubr_set_progress.php';       // Path info.
$_INI['path_to_upload_script']       = '/cgi-bin/ubr_upload.pl';     // Path info.
$_INI['php_error_reporting']         = 1;                            // Enable/Disable PHP error_reporting(E_ALL). UPLOADS POSSIBLE.
$_INI['progress_bar_width']          = 400;                          // Width of the progress bar in pixels (This value is also used in calculations).
$_INI['purge_link_files']            = 1;                            // Enable/Disable delete old .link files.
$_INI['purge_link_limit']            = 300;                          // Delete old .link files older than X seconds.
$_INI['purge_redirect_files']        = 1;                            // Enable/Disable delete old .redirect files.
$_INI['purge_redirect_limit']        = 300;                          // Delete old .redirect files older than X seconds.
$_INI['purge_temp_dirs']             = 1;                            // Enable/Disable delete .dir directories.
$_INI['purge_temp_dirs_limit']       = 43200;                        // Delete old .dir directories older than X seconds (43200=12 hrs).
$_INI['redirect_after_upload']       = 1;                            // Enable/Disable redirect after upload.
$_INI['uber_version']                = '6.8.2';                      // This version of Uber-Uploader.

?>