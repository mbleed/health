<?php

$extra_html .= <<<eoq

<div id=step>
<h1 id="s">Notes:</h1>

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
			$extra_html .= <<<htmleoq
			
			<div class="commentbox">$res $row->nte_txt</div>
			<div class="commentfooter">$row->usr_fname $row->usr_lname <i>$frm_dt</i> $nt_actions</div>

			
htmleoq;
		}
	} else { $extra_html .= "<p>No notes saved for this patient case.</p>"; }

	$extra_html .= <<<eoq
</ul>	
</div>

eoq;

?>