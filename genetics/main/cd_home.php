<?php 
$security_needed = 1; 
include './security_check.php';

//include topmenu, is stored in the $topmenu variable
include ('./topmenu.php');
$body .= $topmenu;

//include rightside menu, is stored in the $toolmenu variable
include ('./toolmenu.php');

	if ($_POST['add']) {
		//extract($HTTP_POST_VARS);
		$dec = $db->escape($_POST['dec']);
		$pro = $_POST['pro'];
		$dc_note =  $db->escape($_POST['dc_note']);
		$db->query("INSERT INTO decisions_chosen (dc_dec, dc_pc_id, dc_usr_id, dc_mod_id, dc_note) VALUES ('$dec', $pro, $s_usr, $s_mod, '$dc_note')");
	}

$body .= <<<eoq

<div id="actionbox">
<form name=dd method=post>

<div id=step>
	<h1>1. Choose the problem you wish to act on.</h1>
	<br />
eoq;

	if ($rows = $db->get_results("SELECT * FROM x_usr_grp x INNER JOIN (problems_chosen INNER JOIN problem ON pc_pro_id = pro_id) ON usr_id = pc_usr_id WHERE x.mod_id = $s_mod AND pc_mod_id = $s_mod AND grp_id = $s_grp ORDER BY pro_txt ASC")) {
		$body .= "<select name=pro size=$cnt>";
		foreach ($rows as $row) {
			if ($row->pro_txt == 'Other') { $problem = "$row->pc_other"; }
			else { $problem = $row->pro_txt; }
			if ($_POST['pro'] == $row->pc_id) $body .= "<option value=$row->pc_id SELECTED>$problem</option>";
			else $body .= "<option value=$row->pc_id>$problem</option>";
		}
		$body .= "</select>";
	} else $body .= "<h3>No problems chosen yet for this patient.  <a href=\"pl_home.php\">Add Problems to the Problem List</a>.</h3>";

$body .= <<<eoq

</div>

<div id=step>
	<h1>2. Enter in your treatment objective for the selected problem.</h1>
	<br />
	<input name=dec type=text id=dec size=70>
</div>

<div id=step>
	<h1>3. Support with an explanation and citation.  Then add it to your list by clicking the button below.</h1>
	<br />
		<textarea name=dc_note rows=3 cols=50></textarea>
	<br />
		<input type=submit name=add value="Add to Plan">
</div>

<div id=step>
	<h1>4. Your treatment objectives.</h1>
	<br />
eoq;

	if ($rows = $db->get_results("SELECT * FROM x_usr_grp x INNER JOIN decisions_chosen dc ON dc.dc_usr_id = x.usr_id WHERE dc_mod_id = $s_mod AND x.grp_id = $s_grp ORDER BY dc.dc_pc_id ASC, dc.dc_dec ASC")) {
		$body .= "<table id=\"grid\">";
		$body .= "<tr>";
		$body .= "<th>Problem</th><th>Treatment Objective</th><th>Note</th><th>Actions</th>";
		$body .= "</tr>";
		foreach ($rows as $row) {
		 	$problem = $db->get_var("SELECT pro_txt FROM problem INNER JOIN problems_chosen ON pc_pro_id = pro_id WHERE pc_id = $row->dc_pc_id");
		 	$actions_html = "<a href=cd_edit.php?dc=$row->dc_id>Edit</a><br /><a href=cd_delete.php?dc=$row->dc_id>Delete</a>";
			$body .= "<tr>";
			$body .= "<td bgcolor=#EEEEEE class=shrink>$problem</td>";
			$body .= "<td bgcolor=#EEEEEE class=shrink>$row->dc_dec</td>";
			$body .= "<td bgcolor=#EEEEEE class=shrink>$row->dc_note</td>";
			$body .= "<td bgcolor=#EEEEEE class=shrink>$actions_html</td>";
			$body .= "</tr>";
		}
		$body .= "</table>";
	}

$body .= <<<eoq

</div>
</form>

</div>

eoq;
//end body

include './template.php'; //add in the standard page header 
?>