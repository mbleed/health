<?php 
$security_needed = 1; 
include './security_check.php';

//include topmenu, is stored in the $topmenu variable
include ('./topmenu.php');
$body .= $topmenu;

//include rightside menu, is stored in the $toolmenu variable
include ('./toolmenu.php');

	$dc = $_GET['dc'];
	
	if ($_POST['submit']) {
	  	$dia = $_POST['dia'];
	  	$dc_note = $db->escape($_POST['dc_note']);
	  	$dc_citation = $db->escape($_POST['dc_citation']);
  		$db->query("UPDATE diagnoses_chosen SET dc_note = '$dc_note', dc_citation = '$dc_citation' WHERE dc_id = $dc");
	  	//$db->debug();
    		header ("Location: dd_home.php"); 
  } else $dc = $db->get_row("SELECT * FROM diagnoses_chosen WHERE dc_id = $dc");

$body .= <<<eoq

<div id="actionbox">

<div id=step>
<h1>Edit a Diagnosis Reasoning</h1>	
<form method=post>
  	<input type=hidden name=dc value="$dc->dc_id">
  	<h5>Edit evidence or reasoning</h5>
  	<input type=hidden name=dia value="$dia">
	<textarea name=dc_note cols=65 rows=10>$dc->dc_note</textarea><br>
	<h5>Citation of evidence (URL of website)</h5>
	<textarea name=dc_citation cols=65 rows=3>$dc->dc_citation</textarea><br>     
 	<input type=submit name=submit value="Save">
</form>
</div>

</div>

eoq;
//end body

include './template.php'; //add in the standard page header 
?>