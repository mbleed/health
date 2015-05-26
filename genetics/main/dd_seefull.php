<?php 
$security_needed = 1; 
include './security_check.php';

//include topmenu, is stored in the $topmenu variable
include ('./topmenu.php');
$body .= $topmenu;

//include rightside menu, is stored in the $toolmenu variable
include ('./toolmenu.php');

	$dc_id = $_GET['dc'];
	$row = $db->get_row("SELECT * FROM diagnoses_chosen INNER JOIN diagnoses ON dc_dia_id = dia_id WHERE dc_id = $dc_id");
	
$body .= <<<eoq

<div id="actionbox">

<div id=step>
<h1>Diagnosis</h1>
<h3>$row->dia_txt</h3>		
  	<p>
    	$row->dc_note
    	</p>
    	<p>
    	$row->dc_citation
    	</p>
</div>

</div>

eoq;
//end body

include './template.php'; //add in the standard page header 
?>