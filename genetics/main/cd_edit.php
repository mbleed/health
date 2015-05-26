<?php 
$security_needed = 1; 
include './security_check.php';

//include topmenu, is stored in the $topmenu variable
include ('./topmenu.php');
$body .= $topmenu;

//include rightside menu, is stored in the $toolmenu variable
include ('./toolmenu.php');

$dc = $_GET['dc'];

	if ($_POST['edit']) {
		//extract($HTTP_POST_VARS);
		$dc_note = $db->escape($_POST['dc_note']);
		$dc_id = $_POST['dc_id'];
		$dc_dec = $db->escape($_POST['dc_dec']);
		$db->query("UPDATE decisions_chosen SET dc_dec = '$dc_dec', dc_note = '$dc_note' WHERE dc_id = $dc_id");
		header ("Location: cd_home.php"); 
	}

$body .= <<<eoq

<div id="actionbox" style="font-size: 122%; padding: 25px;">
<form name=dd method=post>

eoq;

	if ($row = $db->get_row("SELECT * FROM decisions_chosen WHERE dc_id = $dc")) {
		$body .= <<<htmleoq
			<label for="dc_dec">Treatment Objective:</label> <input type="text" name="dc_dec" id="dc_dec" value="$row->dc_dec" />
			<br />
			<br />
			<br />
			<label for="dc_note">Explanation and Citation:</label> <textarea name="dc_note" id="dc_note" cols="50" rows="8">$row->dc_note</textarea>
			<input type="hidden" name="dc_id" id="dc_id" value="$row->dc_id" />
			<br />
			<br />
			<br />
			<input type="submit" name="edit" id="edit" value="Save" />
htmleoq;
	}

$body .= <<<eoq

</form>

</div>

eoq;
//end body

include './template.php'; //add in the standard page header 
?>