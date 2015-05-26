<?php 
$security_needed = 1; 
include './security_check.php';

//include topmenu, is stored in the $topmenu variable
include ('./topmenu.php');
$body .= $topmenu;

//include page specific secondary menu, is stored in the $topmenu2 variable
include ('./te_menu.php');
$body .= $topmenu2;

//include rightside menu, is stored in the $toolmenu variable
include ('./toolmenu.php');

$body .= <<<eoq

<div id="actionbox">

eoq;

	if ($rows = $db->get_results("SELECT * FROM genetest WHERE gt_mod_id = $s_mod")) {
		$body .="<form method=POST>\n";
		$body .="<select name=gt_id>\n";
		$body .="<option value=0>Choose a Genetic Test to Run...</option>";
		foreach ($rows as $row) {
			if ($row->gt_id == $_POST['gt_id']) $default = 'selected'; else $default = '';
			$body .="<option value=$row->gt_id $default>$row->gt_txt</option>\n";
		}
		$body .="</select>\n";
		$body .="<input type=submit name=submit value=\"Run Selected Test\">";
		$body .="</form>\n";
		if (isset($_POST['submit']) && ($_POST['gt_id'] > 0)) {
			$body .="<div>\n";
			$gt_id = $_POST['gt_id'];
			$results = $db->get_row("SELECT * FROM genetest WHERE gt_id = $gt_id");
			$body .="<h2>$results->gt_txt</h2>";
			$body .="<p>$results->gt_results</p>";
			$body .="</div>\n";
		} 
	} else {
		$body .="<p>Not available for this patient case.</p>";
	}

$body .= <<<eoq

</div>

eoq;
//end body

include './template.php'; //add in the standard page header 
?>