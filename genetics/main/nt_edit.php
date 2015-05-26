<?php 
$security_needed = 1; 
include './security_check.php';
 
	$nt = $_GET['nt'];
	
	if ($_POST['submit']) {
	  	$note = $db->escape($_POST['note']);
		$nte_id = $_POST['nte_id'];
  		$db->query("UPDATE notepad SET nte_txt = '$note', nte_dt = now() WHERE nte_id = $nte_id");
	  	//$db->debug();
    		header ("Location: nt_home.php"); 
  	} else $nt = $db->get_row("SELECT * FROM notepad WHERE nte_id = $nt");

//include topmenu, is stored in the $topmenu variable
include ('./topmenu.php');
$body .= $topmenu;

//include page specific secondary menu, is stored in the $topmenu2 variable
include ('./nt_menu.php');
$body .= $topmenu2;

//include rightside menu, is stored in the $toolmenu variable
include ('./toolmenu.php');

$body .= <<<eoq

<div id="actionbox">

<div id=step>
<h2>Edit a Notepad Entry</h2>	
<form method=post>
  	<p>
  	<input type=hidden name=nte_id value=$nt->nte_id>
	<textarea name="note" cols=100 rows=20>$nt->nte_txt</textarea><br>
	<input type="submit" name="submit" value="Save Changes">
	</p>
</form>
</div>

</div>

eoq;
//end body

include './template.php'; //add in the standard page header 
?>		