<?php 
$security_needed = 0;
include('./security_check_info.php');

//include topmenu, is stored in the $topmenu variable
include ('./info_topmenu.php');
$body .= $topmenu;

//include page specific secondary menu, is stored in the $topmenu2 variable
include ('./info_te_menu.php');
$body .= $topmenu2;

	$body .= <<<eoq

<div id="actionbox">

eoq;

	if ($rows = $db->get_results("SELECT * FROM genetest WHERE gt_mod_id = $s_mod")) {
		$body .= "<form method=POST>\n";
		$body .= "<select name=gt_id>\n";
		foreach ($rows as $row) $body .= "<option value=$row->gt_id>$row->gt_txt</option>\n";
		$body .= "</select>\n";
		$body .= "<input type=submit name=submit value=\"Run Selected Test\">";
		$body .= "</form>\n";
		if (isset($_POST['submit'])) {
			$body .= "<fieldset>\n";
			$gt_id = $_POST['gt_id'];
			$results = $db->get_row("SELECT * FROM genetest WHERE gt_id = $gt_id");
			$body .= "<h2>$results->gt_txt</h2>";
			$body .= "<p>$results->gt_results</p>";
			$body .= "</fieldset>\n";
		} 
	} else {
		$body .= "<p>Not available for this patient case.</p>";
	}

	$body .= <<<eoq

</div>

eoq;
//end body

include './template_info.php'; //add in the standard page header 
?>