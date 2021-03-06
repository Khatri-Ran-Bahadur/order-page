<?php

//******************************************************************************************************
//	Name: ubr_default_config.php
//	Revision: 2.2
//	Date: 10:43 PM December 14, 2009
//	Link: http://uber-uploader.sourceforge.net
//	Developer: Peter Schmandra
//	Description: Configure upload options
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
//********************************************************************************************************

//********************************************************************************************************
//	ATTENTION
//
//	Any extra config settings added to this file will be passed through the upload process.
//	They can be accessed in the 'ubr_upload.pl' script using $config
//	They can be accessed in the 'ubr_finished.php' script using $_CONFIG_DATA
//********************************************************************************************************

$_CONFIG['allow_extensions']                      = '(ppt|pptx|pps|ppsx|mp3|doc|pdf|docx|txt|jpeg|jpg|png|gif|rtf|odt|ott|xls|xlsx|wma|wmv|mpg3|mpg|mpeg|avi|mov|jpg|jpeg|gif|bmp|png|tiff)';                                                  // Include file extentions that are allowed to be uploaded.
$_CONFIG['bucket_progress_bar']                   = 0;    // Enable the 'Bucket' style progress bar (Must disable Cedric style progress bar).
$_CONFIG['cedric_hold_to_sync']                   = 0;    // Hold 'Cedric' progress bar if it races ahead of actual upload.
$_CONFIG['cedric_progress_bar']                   = 0;    // Enable the 'Cedric' style progress bar.
$_CONFIG['check_allow_extensions_on_client']      = 1;    // Check allow file extensions BEFORE upload.
$_CONFIG['check_allow_extensions_on_server']      = 1;    // Checks for allow file extensions on the server.
$_CONFIG['check_disallow_extensions_on_client']   = 0;    // Check disallow file extensions BEFORE upload.
$_CONFIG['check_disallow_extensions_on_server']   = 0;    // Checks for dissalow file extensions on the server.
$_CONFIG['check_duplicate_file_count']            = 1;    // Make sure the user did not select duplicate files.
$_CONFIG['check_file_name_error_message']         = 'Error, legal file name characters are 1-9, a-z, A-Z, _, -';                                                  // Error message used for client side regex fail.
$_CONFIG['check_file_name_format']                = 0;    // Check the format of the file names BEFORE upload.
$_CONFIG['check_file_name_regex']                 = '^[a-zA-Z0-9\-\_]+[a-zA-Z0-9\-\_\.]+[a-zA-Z0-9\-\_]$';                                                        // Regex used on client side file name check.
$_CONFIG['check_null_file_count']                 = 1;    // Make sure the user selected at least one file to upload.
$_CONFIG['config_file_name']                      = 'ubr_default_config';  // Name of this config file.
$_CONFIG['disallow_extensions']                   = '(sh|php|php3|php4|php5|py|shtml|stm|shtm|phtml|html|htm|js|jsp|asp|aspx|exe|cgi|pl|plx|htaccess|htpasswd)';  // Include file extentions that are NOT allowed to be uploaded.
$_CONFIG['email_subject']                         = '#'.$_COOKIE["order_idx"].' File Upload';       // Subject of the email.
//$_CONFIG['from_email_address']                    = "\"$from_name\" <$from_email>";      // From email address.
$_CONFIG['from_email_address']                    = base64_decode($_COOKIE['jfhjfhutyu56886758jhfghjhfjhJJJhddh']);      // From email address.
$_CONFIG['html_email_support']                    = 1;       // Add html support to email.
$_CONFIG['link_to_upload']                        = 1;     // Create a web link to the uploaded file.
$_CONFIG['link_to_upload_in_email']               = 1;   // Provide web links to uploaded files in email.
$_CONFIG['log_dir']                               = base64_decode($_COOKIE['GTDRfsgdfsgdgwhwue7456475ggdyrgeHrf']).'/tmp/ubr_logs/';   // Path to log directory.
$_CONFIG['log_uploads']                           = 0;    // Log all uploads.
$_CONFIG['max_file_name_chars']                   = 48;                         // The maximum characters allowed in the file name.
$_CONFIG['max_upload_size']                       = 52428800;   // Maximum upload size (5 * 1024 * 1024 = 5242880 = 5MB).
$_CONFIG['max_upload_slots']                      = 10;    // Maximum number of files a user can upload at once.
$_CONFIG['min_file_name_chars']                   = 6;    // The maximum characters allowed in the file name.
$_CONFIG['normalize_file_name_char']              = '_';  // The character that is used as a replacement any disallowed characters in the file name.
$_CONFIG['normalize_file_name_regex']             = '[^a-zA-Z0-9\_\-\.]';                                                                                         // Search and replace regex used in file name normalization.
$_CONFIG['normalize_file_names']                  = 1;    // Only allows  a-z A-Z 0-9 _ . - and space characters in file names.
$_CONFIG['overwrite_existing_files']              = 0;    // Overwrite any existing files by the same name in the upload folder.
$_CONFIG['path_to_upload']                        = base64_decode($_COOKIE['GTDRfsgdfsgdgwhwue7456475ggdyrgeHrf']).'/attachments/'.$_COOKIE["order_idx"].'/';       // Used for a web link to the uploaded file.
$_CONFIG['redirect_url']                          = '/order/ubr_finished.php';       // What page to load after the upload completes. (use a relative path)
$_CONFIG['send_email_on_upload']                  = 1;    // Send an email when the upload is finished.
$_CONFIG['show_current_file']                     = 0;    // Show files uploaded info.
$_CONFIG['show_current_position']                 = 1;    // Show current bytes uploaded info.
$_CONFIG['show_elapsed_time']                     = 1;    // Show elapsed time info.
$_CONFIG['show_est_speed']                        = 1;    // Show estimated speed info.
$_CONFIG['show_est_time_left']                    = 1;    // Show estimated time left info.
$_CONFIG['show_files_uploaded']                   = 1;    // Show files uploaded info.
$_CONFIG['show_percent_complete']                 = 1;    // Show percent complete info.
$_CONFIG['strict_file_name_check']                = 0;    // Strict check of file name. If check fails, the file WILL NOT be transfered.
$_CONFIG['strict_file_name_regex']                = '^[a-zA-Z0-9\-\_]+[a-zA-Z0-9\-\_\.]+[a-zA-Z0-9\-\_]$';                                                        // REGEX applied to file name in strict mode.
$_CONFIG['to_email_address']                      = base64_decode($_COOKIE['GTSDWGRbtrth7556hfghfbgbfgfjghfgjfjgjhf']);   // To Email addresses.
$_CONFIG['unique_file_name']                      = 0;    // Rename the file to a unique file name.
$_CONFIG['unique_file_name_length']               = 16;   // Number of characters to use in the unique name.
$_CONFIG['unique_upload_dir']                     = 0;    // Upload the files to a folder based on upload id inside the upload folder.
$_CONFIG['upload_dir']                            = base64_decode($_COOKIE['GTDRfsgdfsgdgwhwue7456475ggdyrgeHrf']).'/attachments/'.$_COOKIE["order_idx"].'/';   // Path to upload directory.




?>
