<?php 
$security_needed = 1; 
include './security_check.php';

  	$in_save_txt = $db->escape($_POST['save_txt']);
  	$in_save_res_type = $db->escape($_POST['save_res_type']);
  	$in_save_res_url = $db->escape($_POST['save_res_url']);
 // 	$in_save_soap = $db->escape($_POST['save_soap']);
	$lastpage = basename($_SERVER['HTTP_REFERER']);
	$sql = "INSERT INTO notepad (nte_dt, nte_usr_id, nte_mod_id, nte_txt, nte_res_type, nte_res_url, nte_from_page) VALUES (now(), $s_usr, $s_mod, '$in_save_txt', '$in_save_res_type', '$in_save_res_url', '$lastpage')";
	$db->query($sql);
//refresh notepad data
  	if ($rows = $db->get_results("SELECT * FROM x_usr_grp x INNER JOIN (usr u INNER JOIN notepad ON nte_usr_id = u.usr_id) ON x.usr_id = u.usr_id WHERE x.grp_id = $s_grp AND nte_mod_id = $s_mod AND nte_from_page = '$lastpage' ORDER BY nte_dt DESC")) {
		$toolmenu .= "<h3>Notes from this page</h3>";
		foreach ($rows as $row) {
		  	$frm_dt = date('F j, Y, g:i a', strtotime($row->nte_dt));
		  	$toolmenu .= "<p><i>$frm_dt - $row->usr_fname $row->usr_lname</i>";
		  	$toolmenu .= "<br>$row->nte_txt</p>";
		}
	}

echo $toolmenu;
?>			
