<?php 
$security_needed = 1; 
include './security_check.php';

//include topmenu, is stored in the $topmenu variable
include ('./topmenu.php');
$body .= $topmenu;

//include rightside menu, is stored in the $toolmenu variable
include ('./toolmenu.php');

if ($_POST['submit']) {
	$pro = $_POST['itemName'];
	$pro = ucwords($pro); //capitalize first letter of each word
	//check for duplicate or blank entry
	if ($pro == "") {
		$msg = "<span class=\"alert alert-error\">Blank problem entered.</span>";
	} elseif ($db->get_row("SELECT * FROM problem WHERE pro_txt = '$pro' AND (pro_grp_id = 0 OR pro_grp_id = $s_grp)")) {
		$msg = "<span class=\"alert alert-error\">This problem is already in the list.</span>";
	} else {
		$db->query("INSERT INTO problem (pro_txt, pro_grp_id) VALUES ('$pro', $s_grp)");
		$msg = "<span class=\"alert alert-error\">Problem added successfully.</span>";
	}
}

//get info for html lists and javascripts
	$pros = $db->get_results("SELECT * FROM problem WHERE pro_visible = 'y' AND (pro_grp_id = 0 OR pro_grp_id = $s_grp) ORDER BY pro_txt ASC");
	foreach ($pros as $p) {
		$pro_html_list .= "<tr><td>$p->pro_txt</td><td><a href=\"ajax_add_proc.php?id=$p->pro_id\" class=\"btn\"><i class=\"icon-plus\"></i> Add</a></td></tr>";  
		//$pro_js .= "var oAddButton_$p->pro_id = new YAHOO.widget.Button({ type:\"link\", label:\"Add\", id:\"addbutton_$p->pro_id\", href:\"ajax_add_proc.php?id=$p->pro_id\", container:\"addbuttonspan_$p->pro_id\" }); \n";
		//$pro_js .= "var oAddButton_$p->pro_id = new YAHOO.widget.Button(\"addbuttonspan_$p->pro_id\"); \n";
	}
	if ($procs = $db->get_results("SELECT * FROM x_usr_grp x INNER JOIN (problems_chosen INNER JOIN problem ON pc_pro_id = pro_id) ON usr_id = pc_usr_id WHERE x.mod_id = $s_mod AND pc_mod_id = $s_mod AND grp_id = $s_grp ORDER BY pro_txt ASC")) {
		foreach ($procs as $pc) {
			$proc_html_list .= "<tr><td>$pc->pro_txt </td><td><a href=\"ajax_del_proc.php?id=$pc->pc_id\" class=\"btn\"><i class=\"icon-minus\"></i> Delete</a></td></tr>";
			//$proc_js .= "var oDeleteButton_$p->pro_id = new YAHOO.widget.Button( { type:\"link\", label:\"Delete\", id:\"deletebutton_$pc->pc_id\", href:\"ajax_del_proc.php?id=$pc->pc_id\", container:\"deletebuttonspan_$pc->pc_id\" }); \n";
			//$proc_js .= "var oDeleteButton_$p->pro_id = new YAHOO.widget.Button(\"deletebuttonspan_$pc->pc_id\"); \n";
		}
	}

$body .= <<<eoq

<style>       
.pl_grid {
	float: left;
	margin: 2em;
	border: 1px solid #221111;
}
#createNew {
	background-color: #EFE1C3;
	padding: 0.5em;
}
</style>  

<script type="text/javascript">
function init_buttons() {
	$pro_js

	$proc_js
}
YAHOO.util.Event.onDOMReady(init_buttons); 
</script>

<div id="actionbox">  
	<table id="grid" class="pl_grid">
		<tr><th colspan=2>Potential Problems</th></tr>
		<tr><td colspan=2>
			Create a New Problem
			<div>$msg</div>
			<form id="form_addnew" method="post">
				<input type="text" name="itemName" id="itemName"> <br />
				<input name="submit" type="submit" value="Add New Problem">
			</form>
		</td></tr>

	$pro_html_list

eoq;

	$who = implode(" and ",$patients_array);

$body .= <<<eoq

	</table>
	
	<table id="grid" class="pl_grid">
		<tr><th colspan=2>$who's Problems</th></tr>
 
	$proc_html_list

	</table>      
</div>
 
eoq;
//end body

include './template.php'; //add in the standard page header 
?>