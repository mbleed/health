<?php 
$security_needed = 0; 
include './security_check.php';

//PERFORM SUBMIT CHECK AND ASSOC LOGIC
if ($_POST['c_submit'] == 'Add Class') {
	foreach ($_POST as $var=>$val) $$var = $db->escape($val);
	$sql = "INSERT INTO classes (class_name, class_dt) VALUES ('$class_name', now())";
	$db->query($sql);
	$class_id = $db->get_var("SELECT max(class_id) FROM classes");	
	$sql = "INSERT INTO instructor (ins_class_id, ins_usr_id, ins_dt) VALUES ($class_id, $s_usr, now())";
	$db->query($sql);
}
if ($_POST['g_submit'] == 'Add Group') {
	foreach ($_POST as $var=>$val) $$var = $db->escape($val);
	$sql = "INSERT INTO groups (grp_txt, grp_ps, grp_add_to, grp_class_id) VALUES ('$grp_txt', '$grp_ps', '$grp_add_to', $grp_class_id)";
	$db->query($sql);
	//echo $sql; exit();
}

//ADD CLASS FORM
$add_class_form = <<<htmleoq

<form name="add_c" method="post" style="margin:5px; padding: 5px; background-color: #EFE1C3;">
<h3>Add a New Class</h3>
<input type="text" name="class_name" />
<input type="submit" name="c_submit" value="Add Class" />
</form>

htmleoq;

//CLASS TABLE
$class_html = "";
$class_html .= $add_class_form;

$class_html .= "<table id=grid>";
$class_html .= "<h3>Current classes you are the instructor of: </h3>";
$class_html .= "<tr><th>Class</th><th>Date</th><th>Actions</th></tr>";
$cs = $db->get_results("SELECT * FROM classes INNER JOIN instructor ON class_id = ins_class_id WHERE ins_usr_id = $s_usr ORDER BY class_dt DESC");
foreach ($cs as $c) {
	$action_html = "<a href=sg_edit_c.php?id=$c->class_id>Edit</a>";
	$action_html .= "<br><a href=sg_delete_c.php?id=$c->class_id>Delete</a>";
	$action_html .= "<br><a href=sg_unreg_c.php?id=$c->class_id>Stop Group Registration</a>";
	$class_html .= "<tr><td>$c->class_name</td><td>$c->class_dt</td><td>$action_html</td></tr>";
} 
$class_html .= "</table>";

//CLASS SELECT
$class_select = "<select name=\"grp_class_id\">";
foreach ($cs as $c) {
		$class_select .= "<option value=\"$c->class_id\">$c->class_name</option>";
}
$class_select .= "</select>";

//ADD GROUP FORM
$add_group_form = <<<htmleoq

<form name="add_g" method="post" style="margin:5px; padding: 5px; background-color: #EFE1C3;">
<h3>Add a Group</h3>
<input type="text" name="grp_txt" />
<input type="text" name="grp_ps" />
<input type="text" name="grp_add_to" value="Y" />
$class_select
<input type="submit" name="g_submit" value="Add Group" />
</form>

htmleoq;

//GROUP TABLE
$group_html = "";
$group_html .= $add_group_form;

$group_html .= "<table id=grid>";
$group_html .= "<h3>Groups in your classes</h3>";
$group_html .= "<tr><th>Group</th><th>Password</th><th>Available for registration?</th><th>Class</th><th>Actions</th></tr>";
$gs = $db->get_results("SELECT * FROM groups INNER JOIN classes ON grp_class_id = class_id WHERE grp_type = 'G' ORDER BY class_dt DESC");
foreach ($gs as $g) {
	$action_html = "<a href=sg_edit_g.php?id=$g->grp_id>Edit</a>";
	$action_html .= "<br><a href=sg_delete_g.php?id=$g->grp_id>Delete</a>";
	$group_html .= "<tr><td>$g->grp_txt</td><td>$g->grp_ps</td><td>$g->grp_add_to</td><td>$g->class_name</td><td>$action_html</td></tr>";
}
$group_html .= "</table>";


$body .= <<<eoq

<div id="step">
<h1>Classes</h1>
$class_html
</div>

<div id="step">
<h1>Groups</h1>
$group_html
</div>

eoq;
//end body

include './template.php'; //add in the standard page header 
?>