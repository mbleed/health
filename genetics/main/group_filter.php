<?php 
$group_filter_html = "";

$filter = $_GET['filter'];
if ($filter == '') $filter = $_POST['filter'];

$fullpg = $_SERVER['PHP_SELF'];
$pg = end(explode('/', $fullpg));
//check to see if group or individual
	if ($s_grp_type == 'G') {
	//print filter options using all members of current user group
		$group_filter_html .= "<p>Show results for: ";
		$group_filter_html .= "| <a href=$pg?filter=$s_usr>My Data</a> | ";
		$group_filter_html .= "<a href=$pg?filter=all>$s_grp_txt</a> | ";
		if ($members = $db->get_results("SELECT * FROM x_usr_grp x INNER JOIN usr u ON x.usr_id = u.usr_id WHERE x.grp_id = $s_grp AND x.mod_id = $s_mod")) {
  			foreach ($members as $member) {
  			 	if ($member->usr_id <> $s_usr) {
  					$group_filter_html .= "<a href=$pg?filter=$member->usr_id>$member->usr_fname $member->usr_lname</a> | ";
  	 			}
  			}
  		}
		$group_filter_html .= "</p>";

		//build filter based on user choice
			if ($filter == '') { $filter = 'all'; }
			if ($filter == 'all') {
				$rs = $db->get_results("SELECT * FROM x_usr_grp x INNER JOIN usr u ON x.usr_id = u.usr_id WHERE x.grp_id = $s_grp AND x.mod_id = $s_mod");
				foreach ($rs as $r) {
					$ids[] = $r;
					$just_ids[] = $r->usr_id;	
				}
			} else {
				//check to see if id is actually in the user's group to prevent manual tampering
				$idingroup = false;
				$idscheck = $db->get_results("SELECT * FROM x_usr_grp x INNER JOIN usr u ON x.usr_id = u.usr_id WHERE x.grp_id = $s_grp AND x.mod_id = $s_mod");
				foreach ($idscheck as $idcheck) {
					if ($idcheck->usr_id == $filter) { $idingroup = true; }
				}
				if (!$idingroup) { $filter = $s_usr; }
				$r = $db->get_row("SELECT * FROM x_usr_grp x INNER JOIN usr u ON x.usr_id = u.usr_id WHERE x.usr_id = $filter AND x.grp_id = $s_grp AND x.mod_id = $s_mod");
				$ids[] = $r;
				$just_ids[] = $r->usr_id;
			}
	} else {
		//individual
		$group_filter_html .= "<p>You are working as an individual, not currently in a group.</p>";
		$r = $db->get_row("SELECT * FROM x_usr_grp x INNER JOIN usr u ON x.usr_id = u.usr_id WHERE x.usr_id = $s_usr AND x.grp_id = $s_grp AND x.mod_id = $s_mod");
		$ids[] = $r;
		$just_ids[] = $r->usr_id;
	}

	$id_list = implode(",", $just_ids);
?>