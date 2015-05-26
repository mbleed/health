<?php 
$security_needed = 0; 
include './security_check.php';

//include topmenu, is stored in the $topmenu variable
$topmenu = "<h1><a href=\"mod_index.php\">Return to Module</a></h1>";
$body .= $topmenu;

$grp = $_GET['grp'];
if (isset($_GET['mod'])) $s_mod = $_GET['mod'];

$g = $db->get_row("SELECT * FROM groups WHERE grp_id = $grp");

$ids = $db->get_results("SELECT * FROM x_usr_grp x INNER JOIN usr u ON x.usr_id = u.usr_id WHERE x.grp_id = $g->grp_id");


$body .= <<<eoq

<style>
h2 {
	background-color: #BBAA99;
	color: #880022
}

#groupbox {
	background-color: #EFE1C3; 
	color: #221111; 
	border: 1px solid #221111; 
	padding: 0.5em;
	margin: 0.5em;
}
</style>

<div id="groupbox">
<h1>Group: $g->grp_txt</h1>
<h2>Notes:</h2>

eoq;

//$s_path = substr($s_path,0,-2);

	foreach ($ids as $id) {
		if ($rows = $db->get_results("SELECT * FROM notepad WHERE nte_usr_id = $id->usr_id AND nte_mod_id = $s_mod ORDER BY nte_dt DESC")) {
			$html .= "<h3 id=notesection>From $id->usr_fname $id->usr_lname:</h3>";
			foreach($rows as $row) {
				$frm_dt = date('F j, Y, g:i a', strtotime($row->nte_dt));
				switch ($row->nte_res_type) {
					case 'img':
						$res = "<a href=$row->nte_res_url><img width=200 height=150 src=$row->nte_res_url> </a><br>";
						break;
					case 'vid':
						$res = "<a href=$row->nte_res_url><img src=img/video.gif></a>";
						break;
					case 'aud':
						$res = "<a href=$row->nte_res_url><img src=img/audio.gif></a>";
						break;
					case 'txt' :
						$res = "<img src=img/text.gif>";
						break;
					default:
						$res = "";
						break;
				}
				$html .= "<p><i>$frm_dt</i> - <b>$row->nte_patient</b>";
				$html .= "<br>$res $row->nte_txt</p>";
			}
		}
	}
	
	  	$toolmenu .= "<span id=\"pg_msgs\"><h1>Group Messages</h1>";
  	if ($rows = $db->get_results("SELECT * FROM messages WHERE msg_grp_id = $s_grp AND msg_mod_id = $s_mod ORDER BY msg_dt DESC LIMIT 3")) {
		$toolmenu .= "<h3>Last 3 group messages</h3>";
		foreach ($rows as $row) {
			$name_row = $db->get_row("SELECT * FROM usr WHERE usr_id = $row->msg_usr_id");
			$msg_name = "$name_row->usr_fname $name_row->usr_lname";
		  	$frm_dt = date('n-d-y, g:i a', strtotime($row->msg_dt));
		  	$toolmenu .= "<p><em style=\"font-size: 85%;\">$msg_name - $frm_dt</em>";
		  	$toolmenu .= "<br>$row->msg_txt</p>";
		}
		$view_msg_button = "<span class=\"buttons\"><a href=\"nt_msgs.php\"><img src=\"$icon_path/zoom.png\" alt=\"\" /> View All</a></span>";
	}
	$toolmenu .= "</span>";
	
	$html .= $toolmenu;

	$html .= <<<eoq
			
<h2>Assessment:</h2>

eoq;

	foreach ($ids as $id) {

	if ($rows = $db->get_results("SELECT * FROM diagnoses_chosen INNER JOIN diagnoses ON dc_dia_id = dia_id WHERE dc_mod_id = $s_mod AND dc_usr_id = $id->usr_id ORDER BY dc_status DESC, dia_txt ASC")) {
		$html .= "<ul style=\"list-style-type: none;\">";
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
			$html .= "<li>$icon $row->dia_txt - $row->dc_note</li>";
		}
		$html .= "</ul>";
	}
	}

	$html .= <<<eoq

<h2>Plan:</h2>

eoq;

	if ($rows = $db->get_results("SELECT * FROM x_usr_grp x INNER JOIN decisions_chosen dc ON dc.dc_usr_id = x.usr_id WHERE dc_mod_id = $s_mod AND x.grp_id = $g->grp_id ORDER BY dc.dc_pc_id ASC, dc.dc_dec ASC")) {
		$html .= "<table id=\"grid\">";
		$html .= "<tr>";
		$html .= "<th>Problem</th><th>Treatment Objective</th><th>Note</th>";
		$html .= "</tr>";
		foreach ($rows as $row) {
		 	$problem = $db->get_var("SELECT pro_txt FROM problem INNER JOIN problems_chosen ON pc_pro_id = pro_id WHERE pc_id = $row->dc_pc_id");
			$html .= "<tr>";
			$html .= "<td bgcolor=#EEEEEE class=shrink>$problem</td>";
			$html .= "<td bgcolor=#EEEEEE class=shrink>$row->dc_dec</td>";
			$html .= "<td bgcolor=#EEEEEE class=shrink>$row->dc_note</td>";
			$html .= "</tr>";
		}
		$html .= "</table>";
	} else { $html .= "<p>No treatment objectives saved for this patient case.</p>"; }


	$html .= <<<eoq

</div>

</div>

eoq;

$body .= $html;
//end body

include './template.php'; //add in the standard page header 
?>
