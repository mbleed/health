<?php
$toolmenu = "";
$toolmenu .= <<<eoq

<div id="toolmenu">

eoq;

//************************* Notepad ************************
$toolmenu .= <<<eoq

	<div class="alert alert-success">
	<h4>Notepad</h4>
	<form name="note_form" id="note_form" method="post">
		<textarea name=save_txt rows=3 class="span12"></textarea>
		<br /> 
		<input type="hidden" id="save_res_url" name="save_res_url" value="$save_res_url">
		<input type="hidden" id="save_res_type" name="save_res_type" value="">
		<button type="button" name="note_save_button" onClick="clickSave()" class="btn"><i class="icon-pencil"></i> Save Note</button>
	</form>
eoq;

	$pg_params = $_SERVER['QUERY_STRING'];
	if (strlen($pg_params) > 0) $currentpage = basename($_SERVER['SCRIPT_NAME'])."?$pg_params";
	else $currentpage =  basename($_SERVER['SCRIPT_NAME']);
	$toolmenu .= "<span id=\"pg_notes\">";
  	if ($rows = $db->get_results("SELECT * FROM x_usr_grp x INNER JOIN (usr u INNER JOIN notepad ON nte_usr_id = u.usr_id) ON x.usr_id = u.usr_id WHERE x.grp_id = $s_grp AND nte_mod_id = $s_mod AND x.mod_id = $s_mod AND nte_from_page = '$currentpage' ORDER BY nte_dt DESC")) {
		$toolmenu .= "<h3>Notes from this page</h3>";
		foreach ($rows as $row) {
		  	$frm_dt = date('n-d-y, g:i a', strtotime($row->nte_dt));
		  	$toolmenu .= "<p><em style=\"font-size: 85%; border-bottom: 1px dotted #666666;\">$row->usr_fname $row->usr_lname - $frm_dt</em>";
		  	$toolmenu .= "<br>$row->nte_txt</p>";
		}
	}
	$toolmenu .= "</span>";

$toolmenu .= <<<eoq
	
	</div>

eoq;

//*********************             Group Messages    **************************
$toolmenu .= <<<eoq

<style>
#pg_msgs p {
	border-bottom: 1px dashed #999988;
}
</style>

	<div class="alert alert-success">
	<h4>Group Messages</h4>
	<form name="msg_form" id="msg_form" method="post">
		<textarea name=msg_txt rows=3 class="span12"></textarea>
		<br />
		<button type="button" name="msg_save_button" onClick="clickMsgSave()" class="btn"><i class="icon-plus"></i> Add Message</button>
	</form>
eoq;

	$view_msg_button = "";
  	$toolmenu .= "<span id=\"pg_msgs\">";
  	if ($rows = $db->get_results("SELECT * FROM messages WHERE msg_grp_id = $s_grp AND msg_mod_id = $s_mod ORDER BY msg_dt DESC LIMIT 3")) {
		$toolmenu .= "<h3>Last 3 group messages</h3>";
		foreach ($rows as $row) {
			$name_row = $db->get_row("SELECT * FROM usr WHERE usr_id = $row->msg_usr_id");
			$msg_name = "$name_row->usr_fname $name_row->usr_lname";
		  	$frm_dt = date('n-d-y, g:i a', strtotime($row->msg_dt));
		  	$toolmenu .= "<p><em style=\"font-size: 85%;\">$msg_name - $frm_dt</em>";
		  	$toolmenu .= "<br>$row->msg_txt</p>";
		}
		$view_msg_button = "<a href=\"nt_msgs.php\" class=\"btn\"><i class=\"icon-search\"></i> View All</a>";
	}
	$toolmenu .= "</span>";
	$toolmenu .= $view_msg_button;

$toolmenu .= <<<eoq
	
	</div>

eoq;

//*************************   Page Resources ******************************
	if ($rows = $db->get_results("SELECT * FROM page_resources WHERE pr_pg = '$pg'")) {
	  	$toolmenu .= "<div class='alert alert-success'>";
	  	$toolmenu .= "<h4>Resources</h4>";
	  	$toolmenu .= "<ul>";
    		foreach ($rows as $row) {
      		$toolmenu  .= "<li><a href=$row->pr_res_url target=\"_blank\">$row->pr_res_txt</a></li>";
    		}
    		$toolmenu .= "</ul>";
		$toolmenu .= "</div>";
	}

//*************************   Questions  ******************************

	$toolmenu .= "<div class='alert alert-success'>";
	$toolmenu .= "<h4>Questions</h4>";
	$toolmenu .= <<<eoq

	<form name=qu method=post action="nt_qu_add.php">
	Enter a question you need to answer:<br>
	<textarea name="qu" rows="3" class="span12"></textarea>
	<br>
	<input type="submit" name="Add" value="Add Question" class="btn">
	</form>

eoq;

	if ($rows = $db->get_results("SELECT * FROM notepad_question_with_group WHERE qu_mod_id = $s_mod AND qu_grp_id = $s_grp AND qu_status = 0 ORDER BY qu_dt DESC")) {
	  	$toolmenu .= "<ul>";
		$toolmenu .= "<h5><img src=img/dd_und.gif class=middle>Unanswered Questions</h5>";
		foreach ($rows as $row) {
 			$toolmenu  .= "<li>$row->qu_txt <a href=nt_qu_answer.php?qu=$row->qu_id>Answer</a> | <a href=nt_qu_delete.php?qu=$row->qu_id>Delete</a></li>"; 
		}
    		$toolmenu .= "</ul>";
	}

	$toolmenu .= "<a href=\"nt_qu_home.php\" class=\"btn\"><i class=\"icon-search\"></i> View Answers</a>";
    	$toolmenu .= "</div>";

$toolmenu .= "</div>";
?>