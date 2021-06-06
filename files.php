<?php
//include "configuration.php";
require_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');
	global $dbcon;
$dbcon = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$config = new JConfig;
	
echo '<table border="2" cellspacing="0">
    <tbody>
        <tr valign="bottom">
            <th width="22" bgcolor="#b0b0b0">&nbsp;</th>
            <th width="353" align="center" bgcolor="#b0b0b0"><font face="Arial" size="2"><b>File Name</b></font></th>

            <th width="173" align="center" bgcolor="#b0b0b0"><font face="Arial" size="2"><b>Category</b></font></th>
            <th width="111" align="center" bgcolor="#b0b0b0"><font face="Arial" size="2"><b>Posted by Participant</b></font></th>
            <th width="83" align="center" bgcolor="#b0b0b0"><font face="Arial" size="2"><b>Posted by Name</b></font></th>
            <th width="57" align="center" bgcolor="#b0b0b0"><font face="Arial" size="2"><b>On date</b></font></th>
        </tr>
';
$no = 1;
$sql = @mysqli_query($dbcon,"SELECT * FROM jos_jdownloads_files WHERE published = 1 ORDER BY file_id ASC");
while ($row = @mysqli_fetch_array($sql)) {
$cat_id = $row['cat_id'];

echo '
        <tr valign="bottom">
            <th width="18" align="center" bgcolor="#b0b0b0" height="12"><b>'.$no++.'</b></th>

            <td width="283"><a href="index.php?option=com_jdownloads&Itemid=62&task=finish&cid='.$row['file_id'].'&catid='.$cat_id.'" target="_blank"><font face="Arial" size="2">'.$row['file_title'].'</font></a></td>
            <td width="139"><font face="Arial" size="2">'; 
$sql2 = @mysqli_query($dbcon,"SELECT * FROM jos_jdownloads_cats WHERE cat_id = $cat_id ");
$row2 = @mysqli_fetch_array($sql2);
echo $row2['cat_title'];

echo '</font></td>
            <td width="89"><font face="Arial" size="2">'.$row['author'].'</font></td>
            <td width="67"><font face="Arial" size="2">'.$row['size'].'</font></td>
            <td width="46" align="right"><font face="Arial" size="2">'.$row['date_added'].'</font></td>
        </tr>
';

}
echo '    </tbody>
</table>
';

?>
