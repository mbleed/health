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
if ($_POST['msg_submit'] <> '') {
  	$msg_txt = $db->escape($_POST['msg_txt']);
	$sql = "INSERT INTO messages (msg_dt, msg_usr_id, msg_mod_id, msg_grp_id, msg_txt) VALUES (now(), $s_usr, $s_mod, $s_grp, '$msg_txt')";
	$db->query($sql);
}

$body .= <<<eoq

<div id="actionbox">

<div id=step>
	<h1>Group Messages</h1>

<style>
#msg_list p {
	border-bottom: 1px dashed #999988;
	padding: 5px;
	margin: 5px;
}
</style>


eoq;

	if ($rows = $db->get_results("SELECT * FROM messages WHERE msg_mod_id = $s_mod AND msg_grp_id = $s_grp ORDER BY msg_dt DESC")) {
		//$body .= "<ul id=\"msg_list\">";
		foreach ($rows as $row) {
			$name_row = $db->get_row("SELECT * FROM usr WHERE usr_id = $row->msg_usr_id");
			$msg_name = "$name_row->usr_fname $name_row->usr_lname";
		  	$frm_dt = date('F j, Y, g:i a', strtotime($row->msg_dt));
		  	
		  				$body .= <<<htmleoq
			
			<div class="commentbox">$res $row->msg_txt</div>
			<div class="commentfooter">$msg_name <i>$frm_dt</i> </div>

			
htmleoq;
		}
		$body .= "</ul>";
	}

$body .= <<<eoq

<br>
<h3 id=addnote>Add a new message:</h3>
<form name="addmsg" method="post">
	<textarea name="msg_txt" cols="100" rows="5"></textarea>
	<br />
	<input type="submit" name="msg_submit" value="Add Message" />
</form>

</div>
</div>

eoq;
//end body

include './template.php'; //add in the standard page header 
?>