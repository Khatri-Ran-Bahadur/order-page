<?php
	//Email admin
	$tod = mysqli_query($dbcon,"SELECT admin_email FROM orders_configuration ");

 $rowtod = mysqli_fetch_array($tod);
	$subject2x = "New Order: #$order_id";
$adminmessagex ='<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body><div style="background:#F6F6F6;font-family:Verdana, Arial, Helvetica, sans-serif;font-size:12px;margin:0;padding:0">
<div style="background:#F6F6F6;font-family:Verdana, Arial, Helvetica, sans-serif;font-size:12px;margin:0;padding:0">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tbody><tr>
    <td align="center" valign="top" style="padding:20px 0 20px 0">
        <table bgcolor="#FFFFFF" cellspacing="0" cellpadding="10" border="0" width="650" style="border:1px solid #E0E0E0">
            
            <tbody><tr>
                <td valign="top"><a href="'.$siteurl.'" target="_blank"><img src="'.$email_logo.'" alt="'.$companyname.'" style="margin-bottom:10px" border="0"></a></td>
            </tr>
            
            <tr>
                <td valign="top">
                    <h1 style="font-size:22px;font-weight:normal;line-height:22px;margin:0 0 11px 0">Hello, Admin</h1>
                    <p style="font-size:12px;line-height:16px;margin:0">
                        An order has been placed at <b>'.$companyname.'</b> <br />
			<br />
			Please <a href="'.$admin_url.'/view_order.php&order_id='.$order_id.'">Click here</a> to view the details. <br />
			<br />

                    <p style="font-size:12px;line-height:16px;margin:0">A copy of the order is below:</p>
            </td></tr>
            <tr>
                <td>
                    <table cellspacing="0" cellpadding="0" border="0" width="650">
                        <thead>
                        <tr>
                            <th align="left" width="325" bgcolor="#EAEAEA" style="font-size:13px;padding:5px 9px 6px 9px;line-height:1em">First Name</th>
                            <th width="10"></th>
                            <th align="left" width="325" bgcolor="#EAEAEA" style="font-size:13px;padding:5px 9px 6px 9px;line-height:1em">Last Name</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td valign="top" style="font-size:12px;padding:7px 9px 9px 9px;border-left:1px solid #EAEAEA;border-bottom:1px solid #EAEAEA;border-right:1px solid #EAEAEA"> '.$firstname.'  </td>
                            <td>&nbsp;</td>
                            <td valign="top" style="font-size:12px;padding:7px 9px 9px 9px;border-left:1px solid #EAEAEA;border-bottom:1px solid #EAEAEA;border-right:1px solid #EAEAEA"> '.$lastname.' </td>
                        </tr>
                        </tbody>
                    </table>
                    <br />



                    <table cellspacing="0" cellpadding="0" border="0" width="650">
                        <thead>
                        <tr>
                            <th align="left" width="325" bgcolor="#EAEAEA" style="font-size:13px;padding:5px 9px 6px 9px;line-height:1em">Contact Number:</th>
                            <th width="10"></th>
                            <th align="left" width="325" bgcolor="#EAEAEA" style="font-size:13px;padding:5px 9px 6px 9px;line-height:1em">#ID & Email</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td valign="top" style="font-size:12px;padding:7px 9px 9px 9px;border-left:1px solid #EAEAEA;border-bottom:1px solid #EAEAEA;border-right:1px solid #EAEAEA"> '.$phone_full.'  </td>
                            <td>&nbsp;</td>
                            <td valign="top" style="font-size:12px;padding:7px 9px 9px 9px;border-left:1px solid #EAEAEA;border-bottom:1px solid #EAEAEA;border-right:1px solid #EAEAEA"><a href="'.$admin_url.'/customers.php&customer_id='.$user_id.'">#'.$user_id.' ('.$emailx.')</a> </td>
                        </tr>
                        </tbody>
                    </table>
			<br />

                    <table cellspacing="0" cellpadding="0" border="0" width="650">
                        <thead>
                        <tr>
                            <th align="left" width="325" bgcolor="#EAEAEA" style="font-size:13px;padding:5px 9px 6px 9px;line-height:1em">Writing Style:</th>
                            <th width="10"></th>
                            <th align="left" width="325" bgcolor="#EAEAEA" style="font-size:13px;padding:5px 9px 6px 9px;line-height:1em">Type of Document</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td valign="top" style="font-size:12px;padding:7px 9px 9px 9px;border-left:1px solid #EAEAEA;border-bottom:1px solid #EAEAEA;border-right:1px solid #EAEAEA"> '.$stylex.' </td>
                            <td>&nbsp;</td>
                            <td valign="top" style="font-size:12px;padding:7px 9px 9px 9px;border-left:1px solid #EAEAEA;border-bottom:1px solid #EAEAEA;border-right:1px solid #EAEAEA"> '.$doctype_x.'</td>
                        </tr>
                        </tbody>
                    </table>
			<br />

                    <table cellspacing="0" cellpadding="0" border="0" width="650">
                        <thead>
                        <tr>
                            <th align="left" width="325" bgcolor="#EAEAEA" style="font-size:13px;padding:5px 9px 6px 9px;line-height:1em">Subject Area:</th>
                            <th width="10"></th>
                            <th align="left" width="325" bgcolor="#EAEAEA" style="font-size:13px;padding:5px 9px 6px 9px;line-height:1em">Academic Level</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td valign="top" style="font-size:12px;padding:7px 9px 9px 9px;border-left:1px solid #EAEAEA;border-bottom:1px solid #EAEAEA;border-right:1px solid #EAEAEA"> '.$order_categoryx.'  </td>
                            <td>&nbsp;</td>
                            <td valign="top" style="font-size:12px;padding:7px 9px 9px 9px;border-left:1px solid #EAEAEA;border-bottom:1px solid #EAEAEA;border-right:1px solid #EAEAEA"> '.$academic_level.'</td>
                        </tr>
                        </tbody>
                    </table>
			<br />

                    <table cellspacing="0" cellpadding="0" border="0" width="650">
                        <thead>
                        <tr>
                            <th align="left" width="325" bgcolor="#EAEAEA" style="font-size:13px;padding:5px 9px 6px 9px;line-height:1em">Number of Pages:</th>

                            <th width="10"></th>
                            <th align="left" width="325" bgcolor="#EAEAEA" style="font-size:13px;padding:5px 9px 6px 9px;line-height:1em">Deadline</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr>
                            <td valign="top" style="font-size:12px;padding:7px 9px 9px 9px;border-left:1px solid #EAEAEA;border-bottom:1px solid #EAEAEA;border-right:1px solid #EAEAEA"> '.$numpagesx.' ('.$interval.')</td>
                            <td>&nbsp;</td>
                            <td valign="top" style="font-size:12px;padding:7px 9px 9px 9px;border-left:1px solid #EAEAEA;border-bottom:1px solid #EAEAEA;border-right:1px solid #EAEAEA"> '.$urgencyx.'</td>

                        </tr>
                        </tbody>
                    </table>
			<br />

                    <table cellspacing="0" cellpadding="0" border="0" width="650">
                        <thead>
                        <tr>
                            <th align="left" width="325" bgcolor="#EAEAEA" style="font-size:13px;padding:5px 9px 6px 9px;line-height:1em">Top 10 Writers:</th>

                            <th width="10"></th>
                            <th align="left" width="325" bgcolor="#EAEAEA" style="font-size:13px;padding:5px 9px 6px 9px;line-height:1em">VIP Support</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr>
                            <td valign="top" style="font-size:12px;padding:7px 9px 9px 9px;border-left:1px solid #EAEAEA;border-bottom:1px solid #EAEAEA;border-right:1px solid #EAEAEA"> '.$top10writerx.'  </td>
                            <td>&nbsp;</td>
                            <td valign="top" style="font-size:12px;padding:7px 9px 9px 9px;border-left:1px solid #EAEAEA;border-bottom:1px solid #EAEAEA;border-right:1px solid #EAEAEA"> '.$vipsupportx.'</td>

                        </tr>
                        </tbody>
                    </table>
			<br />

                    <table cellspacing="0" cellpadding="0" border="0" width="650" style="border:1px solid #EAEAEA">
			    <thead>
				<tr>
				    <th align="left" bgcolor="#EAEAEA" style="font-size:13px;padding:3px 9px">Topic:</th>
				</tr>
			    </thead>


				    <tbody bgcolor="#F6F6F6">
				<tr>
			    <td align="left" valign="top" style="font-size:11px;padding:3px 9px;border-bottom:1px dotted #CCCCCC">
				'.stripslashes(nl2br($_SESSION['topic'])).'

						                                </td>

			</tr>
			    </tbody>
			</table>


<br />

                                      
                    <table cellspacing="0" cellpadding="0" border="0" width="650" style="border:1px solid #EAEAEA">
			    <thead>
				<tr>
				    <th align="left" bgcolor="#EAEAEA" style="font-size:13px;padding:3px 9px">Details:</th>
				</tr>
			    </thead>

				    <tbody bgcolor="#F6F6F6">
				<tr>
			    <td align="left" valign="top" style="font-size:11px;padding:3px 9px;border-bottom:1px dotted #CCCCCC">
				'.stripslashes(nl2br($_SESSION['details'])).'
						                                </td>

			</tr>
			    </tbody>
			</table>

<br />
                    <table cellspacing="0" cellpadding="0" border="0" width="650">
                        <thead>
                        <tr>
                            <th align="left" width="325" bgcolor="#EAEAEA" style="font-size:13px;padding:5px 9px 6px 9px;line-height:1em">Discount:</th>
                            <th width="10"></th>
                            <th align="left" width="325" bgcolor="#EAEAEA" style="font-size:13px;padding:5px 9px 6px 9px;line-height:1em">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td valign="top" style="font-size:12px;padding:7px 9px 9px 9px;border-left:1px solid #EAEAEA;border-bottom:1px solid #EAEAEA;border-right:1px solid #EAEAEA"> '.$discount_percent_h.'% ('.$discount_h.')  </td>
                            <td>&nbsp;</td>
                            <td valign="top" style="font-size:12px;padding:7px 9px 9px 9px;border-left:1px solid #EAEAEA;border-bottom:1px solid #EAEAEA;border-right:1px solid #EAEAEA"> '.$total_x.'</td>
                        </tr>
                        </tbody>
                    </table>
		


                    <p style="font-size:12px;margin:0 0 10px 0"></p>
                </td>
            </tr>
            <tr>
                <td bgcolor="#EAEAEA" align="center" style="background:#EAEAEA;text-align:center"><center><p style="font-size:12px;margin:0">Thank you, <strong>'.$companyname.'</strong></p></center></td>
            </tr>
        </tbody></table>
    </td>
</tr>
</tbody></table>
</div>
</div>
</body>
</html>';
	$email_x = explode(",", $siteemail);
	foreach ($email_x as $ekey => $evalue) {
		$email = trim($evalue);
        $headers = array( 'Content-Type: text/html; charset=UTF-8' );
        // wp_mail($email, $subject2x, $adminmessagex, $headers);
	}

	


?>
