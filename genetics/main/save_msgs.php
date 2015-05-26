<?php 
$security_needed = 1; 
include './security_check.php';

  	$msg_txt = $db->escape($_POST['msg_txt']);
	$sql = "INSERT INTO messages (msg_dt, msg_usr_id, msg_mod_id, msg_grp_id, msg_txt) VALUES (now(), $s_usr, $s_mod, $s_grp, '$msg_txt')";
	$db->query($sql);

//refresh notepad data
  	if ($rows = $db->get_results("SELECT * FROM messages WHERE msg_grp_id = $s_grp AND msg_mod_id = $s_mod ORDER BY msg_dt DESC LIMIT 3")) {
		$toolmenu .= "<h3>Group Messages</h3>";
		foreach ($rows as $row) {
			$name_row = $db->get_row("SELECT * FROM usr WHERE usr_id = $row->msg_usr_id");
			$msg_name = "$name_row->usr_fname $name_row->usr_lname";
		  	$frm_dt = date('m-j-y, g:i a', strtotime($row->msg_dt));
		  	$toolmenu .= "<p><i>$frm_dt - $msg_name</i>";
		  	$toolmenu .= "<br>$row->msg_txt</p>";
		}
	}

echo $toolmenu;
?>			
