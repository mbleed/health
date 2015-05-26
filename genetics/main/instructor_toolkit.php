<?php 
$db->trace_all = true;
$security_needed = 1; 
include './security_check.php';

//include topmenu, is stored in the $topmenu variable
$topmenu = "<h1><a href=\"mod_index.php\">Return to Module</a></h1>";
$body .= $topmenu;

$classes = $db->get_results("SELECT * from classes INNER JOIN instructor ON ins_class_id = class_id WHERE ins_usr_id = $s_usr ORDER BY class_dt DESC");

$body .= <<<eoq

<style>
.box { 
	padding: 10px;
	margin: 10px;
	border: 1px solid #333333;
}
</style>

<div id="actionbox">  

<h2>Instructor's Toolkit</h2>

<div class="box">
	You are the instructor for the class(es):
	<ul>

eoq;

	foreach ($classes as $c) $body .=  "<li><a href=\"class_detail.php?class=$c->class_id\">$c->class_name</a></li>";

$body .= <<<eoq

	</ul>
</div>
 
</div>
 
eoq;
//end body

include './template.php'; //add in the standard page header 
?>