<?php

//Gather data to be displayed in the PDF
	$module = $db->get_var("SELECT mod_name FROM module WHERE mod_id = $s_mod");
	$grp_members = $db->get_results("SELECT * FROM groups g INNER JOIN (x_usr_grp x INNER JOIN usr u ON u.usr_id = x.usr_id) ON g.grp_id = x.grp_id WHERE g.grp_id = $s_grp");
	$authors = "";
	foreach ($grp_members as $mem) { $authors .= "$mem->usr_fname $mem->usr_lname, "; $grp_name = $mem->grp_txt; }
	$authors = substr($authors,0,-2); //trim extra comma and space
	$dt = date("F j, Y g:i A", strtotime($cr->cr_dt));

//Appendix 1. Research Questions
	$appendix1 = "<h1>Appendix 1</h1>";
	if ($res_ques = $db->get_results("SELECT * FROM research_ques INNER JOIN (x_usr_grp x INNER JOIN usr u ON x.usr_id = u.usr_id) ON x.usr_id = rq_usr_id WHERE rq_mod_id = $s_mod AND grp_id = $s_grp ORDER BY rq_num ASC, x.usr_id ASC")) {
		$appendix1 .= "<h2>Research Questions:</h2>";
    		foreach ($res_ques as $res_que) {
      		$appendix1 .= "<p><em>$res_que->rq_ques</em><br>";
      		$appendix1 .= "$res_que->rq_ans";
      		$appendix1 .= " ($res_que->usr_fname $res_que->usr_lname)</p>";
    		}
  	}
	if ($rows = $db->get_results("SELECT * FROM notepad_question_with_group WHERE qu_mod_id = $s_mod AND qu_grp_id = $s_grp AND qu_status = 1 ORDER BY qu_dt DESC")) {
		$appendix1 .= "<br><h2>Answered Questions</h2>";
		foreach ($rows as $row) {
			$answered_info = $db->get_row("SELECT * FROM usr WHERE usr_id = $row->qu_answer_usr");
			$answered_by = $answered_info->usr_fname." ".$answered_info->usr_lname;
			$citationlink = $row->qu_citation;
 			$appendix1 .= "<p><em>$row->qu_txt</em><br>$row->qu_answer <br>$citationlink <br> $answered_by</p>"; 
		}
	}
//Appendix 2. Differential Diagnoses
	$appendix2 = "<h1>Appendix 2</h1>";
	if ($dds = $db->get_results("SELECT * FROM diagnoses INNER JOIN (diagnoses_chosen INNER JOIN x_usr_grp ON usr_id = dc_usr_id) ON dc_dia_id = dia_id WHERE grp_id = $s_grp AND dc_mod_id = $s_mod AND dc_status = 2")) {
		$appendix2 .= "<b>Accepted Diagnoses</b>";
		foreach ($dds as $dd) {
			$appendix2 .= "<p>$dd->dia_txt - $dd->dc_note";
			$appendix2 .= "<br /><em>$dd->dc_citation</em></p>";
			$appendix2 .= "<br /><br />";
		}
	}
	if ($dds = $db->get_results("SELECT * FROM diagnoses INNER JOIN (diagnoses_chosen INNER JOIN x_usr_grp ON usr_id = dc_usr_id) ON dc_dia_id = dia_id WHERE grp_id = $s_grp AND dc_mod_id = $s_mod AND dc_status = 1")) {
		$appendix2 .= "<b>Rejected Diagnoses</b>";
		foreach ($dds as $dd) {
			$appendix2 .= "<p>$dd->dia_txt - $dd->dc_note";
			$appendix2 .= "<br /><em>$dd->dc_citation</em></p>";
			$appendix2 .= "<br /><br />";
		}
	}
	if ($dds = $db->get_results("SELECT * FROM diagnoses INNER JOIN (diagnoses_chosen INNER JOIN x_usr_grp ON usr_id = dc_usr_id) ON dc_dia_id = dia_id WHERE grp_id = $s_grp AND dc_mod_id = $s_mod AND dc_status = 0")) {
		$appendix2 .= "<b>Undecided Diagnoses</b>";
		foreach ($dds as $dd) {
			$appendix2 .= "<p>$dd->dia_txt - $dd->dc_note";
			$appendix2 .= "<br /><em>$dd->dc_citation</em></p>";
			$appendix2 .= "<br />";
		}
	}
//Appendix3: Notes
	$appendix3 = "<h1>Appendix 3</h1>";
	include './group_filter.php'; //add in group member filter
	foreach ($ids as $id) { $in_id_list .= "$id->usr_id,"; }
	$in_id_list = substr($in_id_list,0,-1);

	//Notes
	$appendix3 .= "<h3>Notes:</h3>";
	if ($rows = $db->get_results("SELECT * FROM notepad INNER JOIN usr ON nte_usr_id = usr_id WHERE nte_usr_id IN ($in_id_list) AND nte_mod_id = $s_mod ORDER BY nte_dt ASC")) {
		foreach($rows as $row) {
			$frm_dt = date('F j, Y, g:i a', strtotime($row->nte_dt));
			$appendix3 .= "<p><i>$frm_dt</i> - <b>From $row->usr_fname $row->usr_lname:</b>";
 			if (strlen($row->nte_patient > 0)) $patient = "<b>($row->nte_patient)</b>"; else $patient = "";
			$appendix3 .= "<br>$patient $row->nte_txt</p>";
		}
	} 
	
	
//Subjective Report
	$subjective_report = "<h1>Subjective Report</h1>";
	$rs = $db->get_results("SELECT * FROM report_sections WHERE rs_cat = 'Subjective' ORDER BY rs_order ASC");
	foreach ($rs as $r) {
		$subjective_report .= "<h3>$r->rs_title</h3>";
		$subjective_report .= $db->get_var("SELECT rss_text FROM report_sections_saved WHERE rss_fieldid = '$r->rs_fieldid' AND rss_grp_id = $s_grp AND rss_mod_id = $s_mod");
	}
	
//Objective Report
	$objective_report = "<h1>Objective Report</h1>";
	$rs = $db->get_results("SELECT * FROM report_sections WHERE rs_cat = 'Objective' ORDER BY rs_order ASC");
	foreach ($rs as $r) {
		$objective_report .= "<h3>$r->rs_title</h3>";
		$objective_report .= $db->get_var("SELECT rss_text FROM report_sections_saved WHERE rss_fieldid = '$r->rs_fieldid' AND rss_grp_id = $s_grp AND rss_mod_id = $s_mod");
	}

//Diagnosis Report
	$diagnosis_report = "<h1>Diagnosis Report</h1>";
	$rs = $db->get_results("SELECT * FROM report_sections WHERE rs_cat = 'Diagnosis' ORDER BY rs_order ASC");
	foreach ($rs as $r) {
		$diagnosis_report .= "<h3>$r->rs_title</h3>";
		$diagnosis_report .= $db->get_var("SELECT rss_text FROM report_sections_saved WHERE rss_fieldid = '$r->rs_fieldid' AND rss_grp_id = $s_grp AND rss_mod_id = $s_mod");
	}
	
	if ($rows = $db->get_results("SELECT * FROM diagnoses INNER JOIN (diagnoses_chosen INNER JOIN usr ON dc_usr_id = usr_id) ON dc_dia_id = dia_id WHERE dc_mod_id = $s_mod AND dc_usr_id IN ($id_list) ORDER BY dc_status DESC, dia_txt ASC")) {
		$diagnosis_report .= "<ul style=\"list-style-type: none;\">";
		foreach ($rows as $row) {
			switch ($row->dc_status) {
				case 2:
					$icon = "Accepted";		
					break;
				case 1:
					$icon = "Rejected";
					break;
				default:
					$icon = "Undecided";
			}
			$diagnosis_report .= "<li><b>$icon</b> $row->dia_txt - $row->dc_note</li>";
		}
		$diagnosis_report .= "</ul>";
	} else { $diagnosis_report .= "<p>No diagnoses saved for this patient case.</p>"; }
	
	$diagnosis_report .= "</div>";
	
//Treatment Report
	$treatment_report = "<h1>Treatment Report</h1>";
	$rs = $db->get_results("SELECT * FROM report_sections WHERE rs_cat = 'Treatment' ORDER BY rs_order ASC");
	foreach ($rs as $r) {
		$treatment_report .= "<h3>$r->rs_title</h3>";
		$treatment_report .= $db->get_var("SELECT rss_text FROM report_sections_saved WHERE rss_fieldid = '$r->rs_fieldid' AND rss_grp_id = $s_grp AND rss_mod_id = $s_mod");
	}
		$treatment_report .= "<h3>Problem List</h3>";
	if ($procs = $db->get_results("SELECT * FROM x_usr_grp x INNER JOIN (problems_chosen INNER JOIN problem ON pc_pro_id = pro_id) ON usr_id = pc_usr_id WHERE x.mod_id = $s_mod AND pc_mod_id = $s_mod AND grp_id = $s_grp ORDER BY pro_txt ASC")) {
		foreach ($procs as $pc) {
			$treatment_report .= "<li>$pc->pro_txt </li>";
		}
	} else { $treatment_report .= "<p>No problems chosen for this patient case.</p>"; }

	$treatment_report .= "<h3>Treatment Objectives</h3>";
	if ($rows = $db->get_results("SELECT * FROM x_usr_grp x INNER JOIN decisions_chosen dc ON dc.dc_usr_id = x.usr_id WHERE x.mod_id = $s_mod AND dc_mod_id = $s_mod AND x.grp_id = $s_grp ORDER BY dc.dc_pc_id ASC, dc.dc_dec ASC")) {
		$treatment_report .= "<br /><br /><table id=\"grid\">";
		$treatment_report .= "<tr>";
		$treatment_report .= "<th>Problem</th><th>Treatment Objective</th><th>Note</th>";
		$treatment_report .= "</tr>";
		foreach ($rows as $row) {
		 	$problem = $db->get_var("SELECT pro_txt FROM problem INNER JOIN problems_chosen ON pc_pro_id = pro_id WHERE pc_id = $row->dc_pc_id");
			$treatment_report .= "<tr>";
			$treatment_report .= "<td bgcolor=#EEEEEE class=shrink>$problem</td>";
			$treatment_report .= "<td bgcolor=#EEEEEE class=shrink>$row->dc_dec</td>";
			$treatment_report .= "<td bgcolor=#EEEEEE class=shrink>$row->dc_note</td>";
			$treatment_report .= "</tr>";
		}
		$treatment_report .= "</table>";
	} else { $treatment_report .= "<p>No treatment objectives saved for this patient case.</p>"; }

	$treatment_report .= "</div>";
	
//check if ind or group and display proper info
if ($grp_type = $db->get_var("SELECT grp_type FROM groups WHERE grp_id = $s_grp")) {
	if ($grp_type == 'G') {
		$group_li = "<li>Group: $grp_name</li>";
	} else $group_li = "";
} 

?>