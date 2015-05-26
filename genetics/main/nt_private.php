<?php 
$security_needed = 1; 
include './security_check.php';

//include topmenu, is stored in the $topmenu variable
include ('./topmenu.php');
$body .= $topmenu;

//include page specific secondary menu, is stored in the $topmenu2 variable
include ('./nt_menu.php');
$body .= $topmenu2;

//include rightside menu, is stored in the $toolmenu variable
include ('./toolmenu.php');

//check to see if user has submitted the form to add a new note
	if ($_POST['submit'] <> '') {
		$nte_txt = $db->escape($_POST['nte_txt']);
		$db->query("INSERT INTO notepad (nte_dt, nte_usr_id, nte_mod_id, nte_txt, nte_soap) VALUES (now(), $s_usr, $s_mod, '$nte_txt', 'z')");
	}

$body .= <<<eoq

<div id="actionbox">

<div id="step">
<h1>Private Notes:</h1>
<p>Notes entered here are not viewable by other members of your group.</p>

eoq;

//display notes
if ($rows = $db->get_results("SELECT * FROM notepad WHERE nte_usr_id = $s_usr AND nte_mod_id = $s_mod AND nte_soap = 'z' ORDER BY nte_dt DESC")) {
	foreach($rows as $row) {
		$frm_dt = date('F j, Y, g:i a', strtotime($row->nte_dt));
		$delete = "<a href=nt_delete.php?nt=$row->nte_id>Delete</a>";
		
					$body .= <<<htmleoq
			
			<div class="commentbox">$res $row->nte_txt</div>
			<div class="commentfooter"><i>$frm_dt</i> $delete</div>

			
htmleoq;

}
} else { $body .= "<p>No notes saved for this module.</p>"; }

$body .= <<<eoq

<br>
<h3 id=addnote>Add a new note:</h3>
<form name=addnote method=post>
	<textarea name=nte_txt cols=70 rows=10></textarea><br>
	<input type=submit name=submit value="Add Note">
</form>

</div>
</div>

eoq;
//end body

include './template.php'; //add in the standard page header 
?>