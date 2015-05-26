<?php 
$security_needed = 0; 
include './security_check.php';
//$db->debug_all = true;
$class_id = $_GET['class'];
$s_mod = $_GET['m'];

$class = $db->get_row("SELECT * FROM classes WHERE class_id = $class_id");
$groups = $db->get_results("SELECT * FROM groups WHERE grp_class_id = $class_id ORDER BY grp_txt ASC");

$body .= <<<eoq

<style>
.box { 
	padding: 10px;
	margin: 10px;
	border: 1px solid #333333;
	float: left;
}
</style>

<div id="actionbox">  

<h2>Instructor's Toolkit</h2>

<div class="box">
	<h2>Student Groups in: $class->class_name</h2>
	<table border=1 cellspacing=1 cellpadding=10>

eoq;

	foreach ($groups as $g) {
		$case_report = "../reports/m".$s_mod."_g".$g->grp_id.".pdf";
		if (is_file($case_report)) $crlink = "<a href=\"$case_report\">View Case Report</a>"; else $crlink = "No Case Report Filed";
		//$body .= "<li><b>$g->grp_txt</b> [ $crlink ] [ <a href=\"group_notes.php?grp=$g->grp_id\">Show Group Notepad</a> ] </li>";
		$body .= "<tr><td><b>$g->grp_txt</b></td><td>  $crlink </td> </tr>";
	}

$body .= <<<eoq

	</table>
</div>

eoq;


$body .= <<<eoq

<div class="box">
	<h2>Class Trends:</h2>
		<table>
		<tr valign="top">
		<td>
		<ul>
			<li><h3>Chosen Problems (# of groups choosing)</h3></li>

eoq;

	if ($procs = $db->get_results("SELECT pro_txt, count(pro_txt) AS cnt FROM groups INNER JOIN (x_usr_grp INNER JOIN (problems_chosen INNER JOIN problem ON pro_id = pc_pro_id) ON usr_id = pc_usr_id) ON x_usr_grp.grp_id = groups.grp_id WHERE grp_class_id = $class_id AND pc_mod_id = $s_mod GROUP BY pro_txt ORDER BY cnt DESC")) {
		foreach ($procs as $pc) {
			$body .= "<li>$pc->pro_txt - ($pc->cnt)</li>";
		}
	}

$body .= <<<eoq

		</ul>
		</td>
		<td>
		<ul>

eoq;

	$body .= "<li><h3>Chosen Accepted Diagnoses (# of groups choosing)</h3></li>";
	if ($dds_a = $db->get_results("SELECT dia_txt, count(dia_txt) AS cnt FROM groups INNER JOIN (x_usr_grp INNER JOIN (diagnoses_chosen INNER JOIN diagnoses ON dia_id = dc_dia_id) ON usr_id = dc_usr_id) ON x_usr_grp.grp_id = groups.grp_id WHERE grp_class_id = $class_id AND dc_mod_id = $s_mod AND dc_status = 2 GROUP BY dia_txt ORDER BY cnt DESC")) {
		foreach ($dds_a as $d) {
			$body .= "<li>$d->dia_txt - ($d->cnt)</li>";
		}
	}

	$body .= "<li><h3>Chosen Rejected Diagnoses (# of groups choosing)</h3></li>";
	if ($dds_r = $db->get_results("SELECT dia_txt, count(dia_txt) AS cnt FROM groups INNER JOIN (x_usr_grp INNER JOIN (diagnoses_chosen INNER JOIN diagnoses ON dia_id = dc_dia_id) ON usr_id = dc_usr_id) ON x_usr_grp.grp_id = groups.grp_id WHERE grp_class_id = grp_class_id AND dc_mod_id = $s_mod AND dc_status = 1 GROUP BY dia_txt ORDER BY cnt DESC")) {		
		foreach ($dds_r as $d) {
			$body .= "<li>$d->dia_txt - ($d->cnt)</li>";
		}
	}
/*
	$body .= "<li><h3>Chosen Undecided Diagnoses (# of groups choosing)</h3></li>";
	if ($dds_u = $db->get_results("SELECT dia_txt, count(dia_txt) AS cnt FROM instructor INNER JOIN (groups INNER JOIN (x_usr_grp INNER JOIN (diagnoses_chosen INNER JOIN diagnoses ON dia_id = dc_dia_id) ON usr_id = dc_usr_id) ON x_usr_grp.grp_id = groups.grp_id) ON ins_class_id = grp_class_id WHERE ins_class_id = $class_id AND dc_mod_id = $s_mod AND dc_status = 0 GROUP BY dia_txt ORDER BY cnt DESC")) {	
		foreach ($dds_u as $d) {
			$body .= "<li>$d->dia_txt - ($d->cnt)</li>";
		}
		
	}
*/
$body .= <<<eoq

		</ul>
		</td>
		</tr>
		</table>
</div>
 
</div>
 
eoq;
//end body


echo $body;
?>