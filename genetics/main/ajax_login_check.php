<?php
$security_needed = 0; 
include './security_check.php';

// setup variables
$s_usr_nm = $db->escape($_POST['usr_nm']);
$s_usr_ps = $db->escape($_POST['usr_ps']);
$md5passwd = md5($s_usr_ps);
		
if($user = $db->get_row("SELECT * FROM usr WHERE usr_nm = '$s_usr_nm'")) {
	if ($user->usr_ps == $md5passwd) {
		$_SESSION["usr_name"] = $user->usr_fname." ".$user->usr_lname;
		$_SESSION["usr"] = $user->usr_id;
		$_SESSION["sec_level"] = $user->usr_sec_lvl;
		$db->query("UPDATE usr SET usr_log_dt = now() WHERE usr_id = $user->usr_id"); //update last logon date on db
		echo "2|Login Successful.";
	} else {
		echo "0|Incorrect password, check CAPS LOCK.  <a href=reminder.php>Click for a reminder</a>";
	}
} else {
	echo "0|No such user exists on the system.";
}		
?>