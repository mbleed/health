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

function linkify($txt) {
  if (strpos($txt, 'ttp://')) {
    $link = "<a href=$txt>$txt</a>";
    return $link;
  }
  if (strpos($txt, 'ww.')) {
    $link = "<a href=http://$txt>$txt</a>";
    return $link;
  }
  return $txt;
}

$body .= <<<eoq

<div id="actionbox">
<form name=qu method=post action="nt_qu_add.php">

<div id=step>
<h1>Here you can note any questions you come across that need further research.</h1>
		Enter Question:<BR>
		<textarea name=qu rows=4 cols=50></textarea>
		<br><br>
		<input type=submit name=Add value="Add Question">
</div>

<div id=step>
	<h1>Question List</h1>

eoq;

	if ($rows = $db->get_results("SELECT * FROM notepad_question_with_group WHERE qu_mod_id = $s_mod AND qu_grp_id = $s_grp AND qu_status = 0 ORDER BY qu_dt DESC")) {
		$body .= "<ol>";
		$body .= "<h5><img src=img/dd_und.gif class=middle>Unanswered Questions</h5>";
		foreach ($rows as $row) {
 			$body .= "<li>$row->qu_txt <a href=nt_qu_answer.php?qu=$row->qu_id>Answer</a> | <a href=nt_qu_delete.php?qu=$row->qu_id>Delete</a></li>"; 
		}
		$body .= "</ol>";
	}
	if ($rows = $db->get_results("SELECT * FROM notepad_question_with_group WHERE qu_mod_id = $s_mod AND qu_grp_id = $s_grp AND qu_status = 1 ORDER BY qu_dt DESC")) {
		$body .= "<ol>";
		$body .= "<h5><img src=img/dd_good.gif class=middle>Answered Questions</h5>";
		foreach ($rows as $row) {
			$answered_info = $db->get_row("SELECT * FROM usr WHERE usr_id = $row->qu_answer_usr");
			$answered_by = $answered_info->usr_fname." ".$answered_info->usr_lname;
			$answered_date = $frm_dt = date('F j, Y, g:i a', strtotime($row->qu_answer_dt));
			$citationlink = linkify($row->qu_citation);
 			$body .= "<li>$row->qu_txt<pre><em>Answered by: $answered_by, $answered_date.</em><br>$citationlink<br>$row->qu_answer</pre></li>"; 
		}
		$body .= "</ol>";
	}

$body .= <<<eoq

</div>
</form>

</div>

eoq;
//end body

include './template.php'; //add in the standard page header 
?>