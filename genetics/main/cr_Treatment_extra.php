<?php

$extra_html .= <<<eoq

<div id=step>
<h1 id="p">Treatment Objectives:</h1>

eoq;

	$extra_html .= "<h3>Problem List</h3>";
	if ($procs = $db->get_results("SELECT * FROM x_usr_grp x INNER JOIN (problems_chosen INNER JOIN problem ON pc_pro_id = pro_id) ON usr_id = pc_usr_id WHERE x.mod_id = $s_mod AND pc_mod_id = $s_mod AND grp_id = $s_grp ORDER BY pro_txt ASC")) {
		foreach ($procs as $pc) {
			$extra_html .= "<li>$pc->pro_txt </li>";
		}
	} else { $extra_html .= "<p>No problems chosen for this patient case.</p>"; }

	$extra_html .= "<h3>Treatment Objectives</h3>";
	if ($rows = $db->get_results("SELECT * FROM x_usr_grp x INNER JOIN decisions_chosen dc ON dc.dc_usr_id = x.usr_id WHERE x.mod_id = $s_mod AND dc_mod_id = $s_mod AND x.grp_id = $s_grp ORDER BY dc.dc_pc_id ASC, dc.dc_dec ASC")) {
		$extra_html .= "<br /><br /><table id=\"grid\">";
		$extra_html .= "<tr>";
		$extra_html .= "<th>Problem</th><th>Treatment Objective</th><th>Note</th>";
		$extra_html .= "</tr>";
		foreach ($rows as $row) {
		 	$problem = $db->get_var("SELECT pro_txt FROM problem INNER JOIN problems_chosen ON pc_pro_id = pro_id WHERE pc_id = $row->dc_pc_id");
			$extra_html .= "<tr>";
			$extra_html .= "<td bgcolor=#EEEEEE class=shrink>$problem</td>";
			$extra_html .= "<td bgcolor=#EEEEEE class=shrink>$row->dc_dec</td>";
			$extra_html .= "<td bgcolor=#EEEEEE class=shrink>$row->dc_note</td>";
			$extra_html .= "</tr>";
		}
		$extra_html .= "</table>";
	} else { $extra_html .= "<p>No treatment objectives saved for this patient case.</p>"; }

	$extra_html .= "</div>";

?>