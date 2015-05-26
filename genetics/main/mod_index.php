<?php 
$security_needed = 1; 
include './security_check.php';

//include topmenu, is stored in the $topmenu variable
include ('topmenu.php');
$body .= $topmenu;

//include page specific secondary menu, is stored in the $topmenu2 variable
include ('pi_menu.php');
$body .= $topmenu2;

//include rightside menu, is stored in the $toolmenu variable
include ('toolmenu.php');


//get module info from db
$row = $db->get_row("SELECT * FROM module WHERE mod_id = $s_mod");

$body .= <<<eoq

<div class="hero-unit">
	<h2>$row->mod_name</h2>
	<img src="$s_path/img/patient_lg.jpg">
	<p>$row->mod_abstract</p>
	<p>Case Author: $row->mod_credits</p>
</div>

eoq;
//end body

//include feedback box, is stored in the $feedback variable
//include ('./feedback.php');
//$body .= $feedback;

include './template.php'; //add in the standard page header 
?>
