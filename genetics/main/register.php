<?php
$security_needed = 0; 
include './security_check.php';

	if ($_POST["submit"] <> "") {
		$s_usr_nm = $_POST['usr_nm'];
		$usr_ps = $_POST['usr_ps'];
		$s_usr_ps = md5($_POST['usr_ps']);
		$ps_verify = md5($_POST['ps_verify']);
		$s_usr_email = $_POST['usr_email'];
		$s_usr_fname = $db->escape($_POST['usr_fname']);
		$s_usr_lname = $db->escape($_POST['usr_lname']);;
		$s_usr_type = $_POST['usr_type'];
		$s_usr_ps_reminder = $_POST['usr_ps_reminder'];

		//username and password must be at least 3 characters
		if ((strlen($s_usr_nm) > 2) && (strlen($usr_ps))) {
		//check to see if two typed passwords match each other
		if ($s_usr_ps == $ps_verify) {
			//check if username selected already exists on system
			if (!$db->get_results("SELECT * FROM usr WHERE usr_nm = '$s_usr_nm'")) {
				//insert the record into the database
				$insertsql =  "INSERT INTO usr (usr_fname, usr_lname, usr_nm, usr_ps, usr_email, usr_type, usr_sec_lvl, usr_ps_reminder) ";
				$insertsql .= "VALUES('$s_usr_fname', '$s_usr_lname', '$s_usr_nm', '$s_usr_ps', '$s_usr_email', 'S', 1, '$s_usr_ps_reminder')";
				$db->query($insertsql);
				$ins_id = $db->insert_id;
				//send to main menu
        header("Location: index.php");
			} else {
				$errmsg = "The username you picked is already in use, please select again.";
			}
		} else {
			$errmsg = "The two passwords you typed did not match, please retype them.";
		}
		} else {
			$errmsg = "The username and password must have at least 3 letters.";
		}
	} 

$body .= <<<eoq

<div id="actionbox">
<p>
<h2>Register</h2>

<!-- <h3><a href="license.php">View Product License Agreement</a></h3> -->

Registering is FREE and easy.  Once registered, you will have full access to the case simulations and can save your progress through each case.  
We store emails only for demographic purposes, and will only contact you in extreme circumstances.
</p>

<p align="center"><font color="#FF0000"><font face="Verdana" size="1">$errmsg</font></font></p>

<p><h3>User Information</h3></p>

<p>
<form name="newaccount" method="post">
<table width="100%" cellpadding="3" cellspacing="0" border="0">
	<tr>
		<td width="30%" align="right"> First Name <font color="red"> * </font> </td>
		<td width="70%" align="left"><input type="text" name="usr_fname" maxlength="50" value="$s_usr_fname"></td>
	</tr>	
	<tr>
		<td width="30%" align="right"> Last Name <font color="red"> * </font> </td>
		<td width="70%" align="left"><input type="text" name="usr_lname" maxlength="50" value="$s_usr_lname"></td>
	</tr>	
	<tr>
        <td align="right"> Email <font color="red"> * </font> </td>
		<td align="left"><input type="text" name="usr_email" maxlength="50" value="$s_usr_email"></td>
	</tr>
</table>
<br>

<h3>Create Username and Password</h3>
<br>

<table width="100%" cellpadding="3" cellspacing="0" border="0">	
	<tr>		
        <td width="30%" align="right"> Username <font color="red"> * </font> </td>
		<td width="70%" align="left"><input type="text" name="usr_nm" maxlength="25" value="$s_usr_nm"></td>
	</tr>
	<tr>
		<td align="right"> Password <font color="red"> * </font> </td>
		<td align="left"><input type="password" name="usr_ps" maxlength="25"></td>
	</tr>		
	<tr>			
        <td align="right"> Re-enter Password <font color="red"> * </font> </td>
		<td align="left"><input type="password" name="ps_verify" maxlength="25"></td>
	</tr>
		<tr>			
        <td align="right"> Type a phrase or description of your password to help jog your memory (if you wish).</td>
		<td align="left"><input type="text" name="usr_ps_reminder" size=75></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Register" border="0"></td>
	</tr>
</table>
<br>

</form>
</p>
</div>

eoq;
//end body

include './template.php'; //create (render) webpage 
?>