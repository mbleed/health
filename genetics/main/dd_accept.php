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
	  	$dc_id = $_POST['dc'];
	  	$dc_note = $db->escape($_POST['dc_note']);
	  	$dc_citation = $db->escape($_POST['dc_citation']);
  		$db->query("UPDATE diagnoses_chosen SET dc_status = 2, dc_note = '$dc_note', dc_citation = '$dc_citation' WHERE dc_id = $dc_id");
	  	//$db->debug();
    		header ("Location: dd_home.php"); 
  	} else $dia_txt = $db->get_var("SELECT dia_txt FROM diagnoses INNER JOIN diagnoses_chosen ON dc_dia_id = dia_id WHERE dc_id = $dc");

$body .= <<<eoq

<div id="actionbox">

<div id=step>
<h1>Accept a Diagnosis</h1>
<h3>$dia_txt</h3>		
<form method=post>
  	<h3>Add some evidence or reasoning to why this is an accepted diagnosis.</h3>
  	<input type=hidden name=dc value="$dc">
	<textarea name=dc_note cols=65 rows=10></textarea><br>
	<h3>Citation of evidence</h3>
	<textarea name=dc_citation cols=65 rows=3></textarea><br>     
 	<input type=submit name=submit value="Save">
</form>
</div>

</div>

eoq;
//end body

include './template.php'; //add in the standard page header 
?>