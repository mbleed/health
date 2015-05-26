<?php 
$security_needed = 0; 
include './security_check.php';

//include topmenu, is stored in the $topmenu variable
$topmenu = "<h1><a href=\"choose_module.php\">Return to Choose a Module</a></h1>";
$body .= $topmenu;


$mod_id = $_GET['mod'];
$class_links = "";
$classes = $db->get_results("SELECT * from classes INNER JOIN instructor ON ins_class_id = class_id WHERE ins_usr_id = $s_usr ORDER BY class_dt DESC");
foreach ($classes as $c) $class_links .=  "<li><a href=\"class_detail.php?mod=$mod_id&class=$c->class_id\">$c->class_name</a></li>";

$class_id = $_GET['class'];
if ($class_id > 0) {
	$class = $db->get_row("SELECT * FROM classes WHERE class_id = $class_id");
	$module = $db->get_row("SELECT * FROM module WHERE mod_id = $mod_id");
	$groups = $db->get_results("SELECT * FROM groups WHERE grp_class_id = $class_id ORDER BY grp_txt ASC");
	
	$mod_detail = <<<htmleoq
	
	<h2>Class: $class->class_name </h2>
	<h2>Module: $module->mod_name</h2>

<div class="box">
	<h2>Group Status</h2>
	<table class=spectbl border=1>

htmleoq;

	foreach ($groups as $g) {
		$userlist = "";
		$usernames = "";
		if ($user_rs = $db->get_results("SELECT * FROM x_usr_grp x INNER JOIN usr u ON u.usr_id = x.usr_id WHERE grp_id = $g->grp_id AND x.mod_id = $mod_id")) {
		foreach ($user_rs as $u) {
			$userlist .= "$u->usr_id,";
			$usernames .= "<h5 class=\"ulist\">$u->usr_fname $u->usr_lname</h5>";
		}
		$userlist = substr($userlist,0,-1);
		if ($num_notes = $db->get_var("SELECT count(*) FROM notepad WHERE nte_mod_id = $mod_id AND nte_usr_id IN ($userlist)")) {
			$dummy = 'found notes and counted';
		} else $num_notes = 0;
		}
		$case_report = "../reports/m".$mod_id."_g".$g->grp_id.".pdf";
		//echo $case_report;
		if (is_file($case_report)) $crlink = "<a href=\"$case_report\">View Case Report</a>"; else $crlink = "No Case Report Filed";
		$mod_detail .= "<tr>";
		$mod_detail .= "<td>$g->grp_txt</td>";
		$mod_detail .= "<td>$usernames</td>";
		$mod_detail .= "<td>$crlink</td>";
		$mod_detail .= "<td>$num_notes Notes</td>";
		$mod_detail .= "<td><a href=\"group_notes.php?grp=$g->grp_id&mod=$mod_id\">Show Group Notepad</a></td>";
		$mod_detail .= "</tr>";
	}

$mod_detail .= <<<eoq

	</table>
</div>


<div class="box">
	<h2>Class Trends:</h2>
		<table>
		<tr valign="top">
		<td>
		<ul>
			<li><h3>Chosen Problems (# of groups choosing)</h3></li>

eoq;

	if ($procs = $db->get_results("SELECT pro_txt, count(pro_txt) AS cnt FROM instructor INNER JOIN (groups INNER JOIN (x_usr_grp INNER JOIN (problems_chosen INNER JOIN problem ON pro_id = pc_pro_id) ON usr_id = pc_usr_id) ON x_usr_grp.grp_id = groups.grp_id) ON ins_class_id = grp_class_id WHERE ins_class_id = $class_id AND pc_mod_id = $mod_id GROUP BY pro_txt ORDER BY cnt DESC")) {
		foreach ($procs as $pc) {
			$mod_detail .= "<li>$pc->pro_txt - ($pc->cnt)</li>";
		}
	}

$mod_detail .= <<<eoq

		</ul>
		</td>
		<td>
		<ul>

eoq;

	$mod_detail .= "<li><h3>Chosen Accepted Diagnoses (# of groups choosing)</h3></li>";
	if ($dds_a = $db->get_results("SELECT dia_txt, count(dia_txt) AS cnt FROM instructor INNER JOIN (groups INNER JOIN (x_usr_grp INNER JOIN (diagnoses_chosen INNER JOIN diagnoses ON dia_id = dc_dia_id) ON usr_id = dc_usr_id) ON x_usr_grp.grp_id = groups.grp_id) ON ins_class_id = grp_class_id WHERE ins_class_id = $class_id AND dc_mod_id = $mod_id AND dc_status = 2 GROUP BY dia_txt ORDER BY cnt DESC")) {
		foreach ($dds_a as $d) {
			$mod_detail .= "<li>$d->dia_txt - ($d->cnt)</li>";
		}
	}

	$mod_detail .= "<li><h3>Chosen Rejected Diagnoses (# of groups choosing)</h3></li>";
	if ($dds_r = $db->get_results("SELECT dia_txt, count(dia_txt) AS cnt FROM instructor INNER JOIN (groups INNER JOIN (x_usr_grp INNER JOIN (diagnoses_chosen INNER JOIN diagnoses ON dia_id = dc_dia_id) ON usr_id = dc_usr_id) ON x_usr_grp.grp_id = groups.grp_id) ON ins_class_id = grp_class_id WHERE ins_class_id = $class_id AND dc_mod_id = $mod_id AND dc_status = 1 GROUP BY dia_txt ORDER BY cnt DESC")) {		
		foreach ($dds_r as $d) {
			$mod_detail .= "<li>$d->dia_txt - ($d->cnt)</li>";
		}
	}

	$mod_detail .= "<li><h3>Chosen Undecided Diagnoses (# of groups choosing)</h3></li>";
	if ($dds_u = $db->get_results("SELECT dia_txt, count(dia_txt) AS cnt FROM instructor INNER JOIN (groups INNER JOIN (x_usr_grp INNER JOIN (diagnoses_chosen INNER JOIN diagnoses ON dia_id = dc_dia_id) ON usr_id = dc_usr_id) ON x_usr_grp.grp_id = groups.grp_id) ON ins_class_id = grp_class_id WHERE ins_class_id = $class_id AND dc_mod_id = $mod_id AND dc_status = 0 GROUP BY dia_txt ORDER BY cnt DESC")) {	
		foreach ($dds_u as $d) {
			$mod_detail .= "<li>$d->dia_txt - ($d->cnt)</li>";
		}
	}

$mod_detail .= <<<eoq

		</ul>
		</td>
		</tr>
		</table>
</div>

eoq;
}

$body .= <<<eoq

<style>
.box { 
	padding: 10px;
	margin: 10px;
	border: 1px solid #333333;
	float: left;
}
.spectbl tr td {
	padding: 5px;
	color: #111;
}
.ulist {
	margin: 0;
	font-size: 77%;
}
</style>

<div id="actionbox">  

<div id="step">
<h1>Select a Class:</h1>
$class_links
</div>
 
</div>
 
 $mod_detail
 
eoq;
//end body

include './template.php'; //add in the standard page header 
?>