<?php 
	$s_mod = 1;
	include "./classes/ez_sql.php"; //include db class 
	$groups = $db->get_results("SELECT * FROM groups WHERE grp_id > 95");
	foreach ($groups as $g) {
		$ids = $db->get_results("SELECT * FROM x_usr_grp x INNER JOIN usr u ON x.usr_id = u.usr_id WHERE x.grp_id = $g->grp_id");

	$html .= <<<eoq

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
<h2>Subjective Notes:</h2>

eoq;

	foreach ($ids as $id) {
		if ($rows = $db->get_results("SELECT * FROM notepad WHERE nte_usr_id = $id->usr_id AND nte_mod_id = $s_mod AND nte_soap = 's' ORDER BY nte_dt DESC")) {
			$html .= "<h3 id=notesection>From $id->usr_fname $id->usr_lname:</h3>";
			foreach($rows as $row) {
				$frm_dt = date('F j, Y, g:i a', strtotime($row->nte_dt));
				switch ($row->nte_res_type) {
					case 'img':
						$res = "<a href=$row->nte_res_url><img  width=200 height=150 src=$s_path/$row->nte_res_url> </a><br>";
						break;
					case 'vid':
						$res = "<a href=$row->nte_res_url><img  src=img/video.gif></a>";
						break;
					case 'aud':
						$res = "<a href=$row->nte_res_url><img  src=img/audio.gif></a>";
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

	$html .= <<<eoq
			
<h2>Objective Notes:</h2>

eoq;

	foreach ($ids as $id) {
	if ($rows = $db->get_results("SELECT * FROM notepad WHERE nte_usr_id = $id->usr_id AND nte_mod_id = $s_mod AND nte_soap = 'o' ORDER BY nte_dt DESC")) {
		$html .= "<h3 id=notesection>From $id->usr_fname $id->usr_lname:</h3>";
		foreach($rows as $row) {
			$frm_dt = date('F j, Y, g:i a', strtotime($row->nte_dt));
			switch ($row->nte_res_type) {
				case 'img':
					$res = "<a href=$row->nte_res_url><img  width=200 height=150 src=$s_path/$row->nte_res_url> </a><br>";
					break;
				case 'vid':
					$res = "<a href=$row->nte_res_url><img  src=img/video.gif></a>";
					break;
				case 'aud':
					$res = "<a href=$row->nte_res_url><img  src=img/audio.gif></a>";
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
	} else { $body .= "<p>No treatment objectives saved for this patient case.</p>"; }


	$html .= <<<eoq

</div>

eoq;

	} //end foreach group


	$html .= "</div>";

	echo $html;
?>
