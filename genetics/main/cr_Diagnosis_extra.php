<?php

$extra_html .= <<<eoq

<div id=step>
<h1 id="a">Diagnoses:</h1>

eoq;

	if ($rows = $db->get_results("SELECT * FROM diagnoses INNER JOIN (diagnoses_chosen INNER JOIN usr ON dc_usr_id = usr_id) ON dc_dia_id = dia_id WHERE dc_mod_id = $s_mod AND dc_usr_id IN ($id_list) ORDER BY dc_status DESC, dia_txt ASC")) {
		$extra_html .= "<ul style=\"list-style-type: none;\">";
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
			$extra_html .= "<li>$icon $row->dia_txt - $row->dc_note</li>";
		}
		$extra_html .= "</ul>";
	} else { $extra_html .= "<p>No diagnoses saved for this patient case.</p>"; }
	
	$extra_html .= "</div>";

?>