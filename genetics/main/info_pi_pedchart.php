<?php 
$security_needed = 0;
include('./security_check_info.php');

//include topmenu, is stored in the $topmenu variable
include ('./info_topmenu.php');
$body .= $topmenu;

//include page specific secondary menu, is stored in the $topmenu2 variable
include ('./info_pi_menu.php');
$body .= $topmenu2;

	$cur_ped = $_GET['p']; 
  	$image = $db->get_var("SELECT ped_img FROM pedchart WHERE ped_mod_id = $s_mod AND ped_order = $cur_ped");

	$body .= <<<eoq

<div id="actionbox">
<div id=step>
	<h1>Click on the next question in the list or the pedigree chart to research the family history. 
	(The pedigree chart will update automatically)</h1>

eoq;

	$body .= "<a href=info_pi_pedchart.php?p=0>Reset Chart</a>";
	$body .= "<ol>";
	$rows = $db->get_results("SELECT * FROM pedchart WHERE ped_mod_id = $s_mod AND ped_order <= $cur_ped ORDER BY ped_order ASC");
	foreach ($rows as $row) {
		if ($row->ped_order > 0) {
			$body .= "<li>$row->ped_question - <br><em>$row->ped_answer</em></li>";
		}
	}
	if ($next_que = $db->get_row("SELECT ped_order, ped_question FROM pedchart WHERE ped_id = ".$row->ped_next_que)) {
		$body .= "<li><a href=info_pi_pedchart.php?p=".$next_que->ped_order.">".$next_que->ped_question."</a></li>";
	}
	$body .= "</ol>";
	$body .= "<img src=$s_path/pedchart/$row->ped_img>";

	$body .= <<<eoq

</div>

</div>

eoq;
//end body

include './template_info.php'; //add in the standard page header 
?>