<?php 
$security_needed = 1; 
include './security_check.php';

//include topmenu, is stored in the $topmenu variable
include ('./topmenu.php');
$body .= $topmenu;

//include page specific secondary menu, is stored in the $topmenu2 variable
include ('./nt_menu.php');
$body .= $topmenu2;

//include rightside menu, is stored in the $toolmenu variable
include ('./toolmenu.php');

$body .= <<<eoq

<div id="actionbox">

eoq;
 
include './group_filter.php'; //add in group member filter
$body .= $group_filter_html;

$body .= <<<eoq

<div id=step>
<h1 id="s">Notes: <span style="float: right; margin-top: -25px;"><a href=#>Back to Top</a></span></h1>

eoq;

//display notes
	if ($rows = $db->get_results("SELECT * FROM notepad INNER JOIN usr ON nte_usr_id = usr_id WHERE nte_usr_id in ($id_list) AND nte_mod_id = $s_mod ORDER BY nte_dt DESC")) {
		foreach($rows as $row) {
			$note_html = "";
			$frm_dt = date('F j, Y, g:i a', strtotime($row->nte_dt));
			switch ($row->nte_res_type) {
				case 'img':
					$res = "<a href=$row->nte_res_url><img width=200 height=150 src=$s_path/$row->nte_res_url> </a><br />";
					break;
				case 'vid':
					$path_array = explode("/", $row->nte_res_url);
					$el = array_shift($path_array);
					$el = array_shift($path_array);
					$path = implode("/", $path_array);
					$qa = $db->get_row("SELECT * FROM question INNER JOIN answer ON que_ans_id = ans_id WHERE ans_media = '$path' AND que_mod_id = $s_mod");
					$res = "<a href=$row->nte_res_url><img src=img/video.gif></a><em>$qa->que_txt<br>$qa->ans_txt</em><br />";
					break;
				case 'txt':
					$res = "<img src=img/text.gif>";
					break;
				default:
					$res = "";
					break;
			}
			if (($row->usr_id == $s_usr) && ($row->nte_res_type <> 'pn')) {
				$nt_actions = " | <a href=nt_edit.php?nt=$row->nte_id>Edit</a> | <a href=nt_delete.php?nt=$row->nte_id>Delete</a> |";
			} else $nt_actions = "";
			$body .= <<<htmleoq
			
			<div class="commentbox">$res $row->nte_txt</div>
			<div class="commentfooter">$row->usr_fname $row->usr_lname <i>$frm_dt</i> $nt_actions</div>

			
htmleoq;
		}
	} else { $body .= "<p>No notes saved for this patient case.</p>"; }

	$body .= <<<eoq
</ul>	
</div>

<div id=step>
<h1 id="a">Diagnoses: <span style="float: right; margin-top: -25px;"><a href="#">Back to Top</a></span></h1>

eoq;

	if ($rows = $db->get_results("SELECT * FROM diagnoses INNER JOIN (diagnoses_chosen INNER JOIN usr ON dc_usr_id = usr_id) ON dc_dia_id = dia_id WHERE dc_mod_id = $s_mod AND dc_usr_id IN ($id_list) ORDER BY dc_status DESC, dia_txt ASC")) {
		$body .= "<ul style=\"list-style-type: none;\">";
		foreach ($rows as $row) {
			switch ($row->dc_status) {
				case 2:
					$icon = "<img src=img/dd_good.gif>";		
					break;
				case 1:
					$icon = "<img src=img/dd_bad.gif>";
					break;
				default:
					$icon = "<img src=img/dd_und.gif>";
			}
			$body .= "<li>$icon $row->dia_txt - $row->dc_note</li>";
		}
		$body .= "</ul>";
	} else { $body .= "<p>No diagnoses saved for this patient case.</p>"; }
	
	$body .= "</div>";

	if ($s_mode == 'dt') {

$body .= <<<eoq

<div id=step>
<h1 id="p">Treatment Objectives: <span style="float: right; margin-top: -25px;"><a href=#>Back to Top</a></span></h1>

eoq;

	$body .= "<h3>Problem List</h3>";
	if ($procs = $db->get_results("SELECT * FROM x_usr_grp x INNER JOIN (problems_chosen INNER JOIN problem ON pc_pro_id = pro_id) ON usr_id = pc_usr_id WHERE x.mod_id = $s_mod AND pc_mod_id = $s_mod AND grp_id = $s_grp ORDER BY pro_txt ASC")) {
		foreach ($procs as $pc) {
			$body .= "<li>$pc->pro_txt </li>";
		}
	} else { $body .= "<p>No problems chosen for this patient case.</p>"; }

	$body .= "<h3>Treatment Objectives</h3>";
	if ($rows = $db->get_results("SELECT * FROM x_usr_grp x INNER JOIN decisions_chosen dc ON dc.dc_usr_id = x.usr_id WHERE x.mod_id = $s_mod AND dc_mod_id = $s_mod AND x.grp_id = $s_grp ORDER BY dc.dc_pc_id ASC, dc.dc_dec ASC")) {
		$body .= "<br /><br /><table id=\"grid\">";
		$body .= "<tr>";
		$body .= "<th>Problem</th><th>Treatment Objective</th><th>Note</th>";
		$body .= "</tr>";
		foreach ($rows as $row) {
		 	$problem = $db->get_var("SELECT pro_txt FROM problem INNER JOIN problems_chosen ON pc_pro_id = pro_id WHERE pc_id = $row->dc_pc_id");
			$body .= "<tr>";
			$body .= "<td bgcolor=#EEEEEE class=shrink>$problem</td>";
			$body .= "<td bgcolor=#EEEEEE class=shrink>$row->dc_dec</td>";
			$body .= "<td bgcolor=#EEEEEE class=shrink>$row->dc_note</td>";
			$body .= "</tr>";
		}
		$body .= "</table>";
	} else { $body .= "<p>No treatment objectives saved for this patient case.</p>"; }

	$body .= "</div>";

	} //end if check on mode, if treatment mode then display

$body .= <<<eoq

</div>

eoq;
//end body

include './template.php'; //add in the standard page header 
?>