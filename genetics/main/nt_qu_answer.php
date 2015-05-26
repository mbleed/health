<?php 
$security_needed = 1; 
include './security_check.php';

//include topmenu, is stored in the $topmenu variable
include ('./topmenu.php');
$body .= $topmenu;

//include rightside menu, is stored in the $toolmenu variable
include ('./toolmenu.php');

	$qu_id = $_GET['qu'];
	
	if ($_POST['submit']) {
	  	$qu_id = $_POST['qu'];
	  	$qu_answer = $db->escape($_POST['qu_answer']);
	  	$qu_citation = $db->escape($_POST['qu_citation']);
  		$db->query("UPDATE notepad_question SET qu_status = 1, qu_answer_usr = $s_usr, qu_answer = '$qu_answer', qu_citation = '$qu_citation', qu_answer_dt = now() WHERE qu_id = $qu_id");
    		header ("Location: nt_qu_home.php"); 
  	} else $qu_txt = $db->get_var("SELECT qu_txt FROM notepad_question WHERE qu_id = $qu_id");

$body .= <<<eoq

<div id="actionbox">

<div id=step>
<h2>Answer a Question</h2>
<h3>$qu_txt</h3>		
<form method=post>
  <p>Add as much supporting evidence or reasoning as possible.
  <input type=hidden name=qu value=$qu_id>
	<textarea name=qu_answer cols=100 rows=20></textarea><br>
	</p>
	<p>Citation of evidence (URL of website).
	<input type=text name=qu_citation size=100><br>
	<input type=submit name=submit value="Answer Question">
	</p>
</form>
</div>

</div>

eoq;
//end body

include './template.php'; //add in the standard page header 
?>