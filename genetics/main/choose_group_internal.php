<?php
$security_needed = 0; 
include './security_check.php';

$usr_id = $_GET['u'];
$errmsg= ''; //initialize err message so nothing displays if it is not set later because of a real error

if ($_POST["submit"] <> "") {
	// setup variables
	$usr_id = $_POST["usr_id"];
	$grp_ps = $_POST["grp_ps"];
	$grp_id = $_POST["grp_id"];
	$grp_ps = (get_magic_quotes_gpc()) ? stripslashes($grp_ps) : $grp_ps;
		
	if($group = $db->get_row("SELECT * FROM groups WHERE grp_id = $grp_id")) {
		if (rtrim($group->grp_ps) == rtrim($grp_ps)) {
			if ($db->get_row("SELECT * FROM x_usr_grp WHERE usr_id = $usr_id")) {  //check for prior group and delete when switching from ind to grp
				$db->query("DELETE FROM x_usr_grp WHERE usr_id = $usr_id");
			}
			$db->query("INSERT INTO x_usr_grp (usr_id, grp_id) VALUES ($usr_id, $grp_id)");
			$_SESSION['grp'] = $grp_id;
			$_SESSION['grp_type'] = 'G'; 
			header ("Location: choose_module.php"); 
		} else {
			$errmsg = "Incorrect password, check CAPS LOCK";
		}
	} else {
		$errmsg = "No such group exists on the system.";
	}	
}	

$body .= <<<eoq

<div id=actionbox>

<div id=step>
<h1>Signup for a group.  Choose the group name below and enter the password for the group.
You may also finish registering without joining a group by clicking the 'Signup as an Individual' button.</h1>
<br>
	<p class="error">$errmsg</p>
<br>

<form method="post">
<input type=hidden name=usr_id value=$usr_id>

<table border="0" cellspacing="5" cellpadding="4">
	<tr>
		<td align="left">Group: </td>
		<td>

eoq;

	    if ($rows = $db->get_results("SELECT * FROM open_groups ORDER BY grp_txt ASC")) {
		   $body .= "<select name=grp_id>";
		   foreach ($rows as $row) {
			    $body .= "<option value=$row->grp_id>$row->grp_txt</option>";
		   }
		   $body .= "</select>";
	    }

$body .= <<<eoq

    </td>
	</tr>
	<tr>
		<td align="left">Password: </td>
		<td><input type="password" name="grp_ps" size="32"></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><input type="submit" name="submit" value="Signup for Selected Group"></td>
	</tr>
</table>
</div>

</form>

</div>

eoq;
//end body

include './template.php'; //create (render) webpage 
?>