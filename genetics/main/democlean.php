<?php 
	$demouser = 4;
	$demogroup = 121;
//		$demouser = 48;
//	$demogroup = 13;
	if ($s_usr == $demouser) { //security check must be logged in as user 48, the demo account
		$db->query("DELETE FROM case_report WHERE cr_grp_id = $demogroup");
		$db->query("DELETE FROM decisions_chosen WHERE dc_usr_id = $demouser");
		$db->query("DELETE FROM diagnoses_chosen WHERE dc_usr_id = $demouser");
		$db->query("DELETE FROM notepad WHERE nte_usr_id = $demouser");
		$db->query("DELETE FROM notepad_question WHERE qu_grp_id = $demogroup");
		$db->query("DELETE FROM problems_chosen WHERE pc_usr_id = $demouser");
		$db->query("DELETE FROM research_ques WHERE rq_usr_id = $demouser");
		$db->query("DELETE FROM signpost WHERE sign_usr = $demouser");
		$db->query("DELETE FROM photonotes WHERE pn_usr_id = $demouser");
		$db->query("DELETE FROM messages WHERE msg_usr_id = $demouser");
	}
?>