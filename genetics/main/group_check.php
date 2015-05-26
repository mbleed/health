<?php

//check if user is in a group for this module
if ($x = $db->get_row("SELECT * FROM x_usr_grp x INNER JOIN groups g ON g.grp_id = x.grp_id WHERE x.usr_id = $s_usr AND x.mod_id = $s_mod")) {
	$_SESSION['grp'] = $group->grp_id;
	$_SESSION['grp_txt'] = $group->grp_txt;	
	$_SESSION['grp_type'] = $group->grp_type;
	$_SESSION['path'] = "../".$db->get_var("SELECT mod_path FROM module WHERE mod_id = $s_mod");
}

?>