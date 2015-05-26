<?php
$security_needed = 1; 
include './security_check.php';

$errmsg = ''; //initialize err message so nothing displays if it is not set later because of a real error

if ($_POST["submit"] <> "") {
	// setup variables
	$grp_ps = $_POST["grp_ps"];
	$grp_id = $_POST["grp_id"];
	$grp_ps = (get_magic_quotes_gpc()) ? stripslashes($grp_ps) : $grp_ps;
		
	if ($group = $db->get_row("SELECT * FROM groups WHERE grp_id = $grp_id")) {
		if (rtrim($group->grp_ps) == rtrim($grp_ps)) {
			$db->query("INSERT INTO x_usr_grp (usr_id, grp_id, mod_id, mod_level) VALUES ($s_usr, $grp_id, $s_mod, 'dt')");
			header ("Location: ./mod_index.php"); 
		} else {
			$errmsg = "Incorrect password, check CAPS LOCK";
		}
	} else {
		$errmsg = "No such group exists on the system.";
	}	
}	
if ($_POST["submitnogroup"] <> "") {
  	$usr = $db->get_row("SELECT * FROM usr WHERE usr_id = $s_usr");
  	$db->query("INSERT INTO groups (grp_txt, grp_add_to, grp_type) VALUES ('$usr->usr_fname $usr->usr_lname', 'N', 'I')");
  	$grp_id = $db->insert_id;
	$db->query("INSERT INTO x_usr_grp (usr_id, grp_id, mod_id, mod_level) VALUES ($s_usr, $grp_id, $s_mod, 'dt')");
	header ("Location: ./mod_index.php"); 
}

$body .= <<<eoq

<div id=actionbox>

<style>
ol li {
	background-color: #EFEFEF;	
	margin: 10px;
	padding: 10px;
}
ol li h1 { font-size: 150%; }
</style>

<h2>This is the first time you are entering this module, you will either choose to work on this module as (1) a member of a group, or (2) all by yourself.</h2>

<ol>
<li>
<h1>(1) As a member of a group.  Choose the group name below and enter the password for the group (if there was one)</h1>
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
		   $body .= "<select name=grp_id style=\"margin:10px;\">";
		   $body .= "<option value=\"0\">Select a Group...</a>";
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
		<td><input type="password" name="grp_ps" size="32" style="margin:10px;"></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><input type="submit" name="submit" value="Signup for Selected Group" style="margin:10px;"></td>
	</tr>
</table>
</li>

<h1>OR</h1>

<li>
<h1>(2) Working as an Individual</h1>
<table border="0" cellspacing="5" cellpadding="8">
	<tr>
		<td>Simply click the button below, to work on this case as an individual.</td>
	<tr>
		<td><input type="submit" name="submitnogroup" value="Signup as an Individual"></td>
	</tr>
</table>
</div>

</form>

</div>

eoq;
//end body

include './template.php'; //create (render) webpage 
?>