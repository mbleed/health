<?php
$security_needed = 1; 
include './security_check.php';

$s_mod = 0; //set module to zero so no header info shows up 

	$ed = $_GET['ed'];
	if ($_POST['edit']) {
		$ed_fld = $_POST['ed'];
		$ed_val = $_POST[$ed_fld];
		if ($ed_fld == 'usr_ps') $ed_val = md5($ed_val);
		$db->query("UPDATE usr SET $ed_fld = '$ed_val' WHERE usr_id = $s_usr");
	}

$body .= <<<eoq

<div id="actionbox">
<style>
	table tr td {
		padding: 0.5em;
	}
</style>
<form method=post name=usr_prof action=usr_profile.php>
<table border="1">
<tr>
<td colspan=3>
	<h3>User Profile for $s_usr_name</h3>
</td>
</tr>

eoq;

	if ($row = $db->get_row("SELECT * FROM usr WHERE usr_id = $s_usr")) {
 		$body .= "<tr>";
		$body .= "<td>First Name:</td>";
		$body .= "<td>$row->usr_fname</td>";
		$body .= "<td>&nbsp;</td>";
		$body .= "</tr>";
 		
		$body .= "<tr>";
		$body .= "<td>Last Name:</td>";
		$body .= "<td>$row->usr_lname</td>";
		$body .= "<td>&nbsp;</td>";
		$body .= "</tr>";

		if ($ed == 'usr_email') {
	 		$body .= "<tr>";
			$body .= "<td>Email:</td>";
			$body .= "<td><input type=text name=usr_email value=$row->usr_email><input type=hidden name=ed value=usr_email></td>";
			$body .= "<td><input type=submit name=edit value=Save></td>";
			$body .= "</tr>";
		} else {
			$body .= "<tr>";
			$body .= "<td>Email:</td>";
			$body .= "<td>$row->usr_email</td>";
			$body .= "<td><a href=usr_profile.php?ed=usr_email>Edit</a></td>";
			$body .= "</tr>";
		}

		if ($ed == 'usr_nm') {
	 		$body .= "<tr>";
			$body .= "<td>Username:</td>";
			$body .= "<td><input type=text name=usr_nm value=$row->usr_nm><input type=hidden name=ed value=usr_nm></td>";
			$body .= "<td><input type=submit name=edit value=Save></td>";
			$body .= "</tr>";
		} else {
 			$body .= "<tr>";
			$body .= "<td>Username:</td>";
			$body .= "<td>$row->usr_nm</td>";
			$body .= "<td><a href=usr_profile.php?ed=usr_nm>Edit</a></td>";
			$body .= "</tr>";
		}

		if ($ed == 'usr_ps') {
	 		$body .= "<tr>";
			$body .= "<td>Password:</td>";
			$body .= "<td><input type=password name=usr_ps value=$enc_ps><input type=hidden name=ed value=usr_ps></td>";
			$body .= "<td><input type=submit name=edit value=Save></td>";
			$body .= "</tr>";
		} else {
	 		$body .= "<tr>";
			$body .= "<td>Password:</td>";
			$body .= "<td> -- Encrypted -- </td>";
			$body .= "<td><a href=usr_profile.php?ed=usr_ps>Edit</a></td>";
			$body .= "</tr>";	
		}
	}

$body .= <<<eoq

</td>
</tr>
</table>

</div>

eoq;
//end body

include './template.php'; //create (render) webpage 
?>