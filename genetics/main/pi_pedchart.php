<?php 
$security_needed = 1; 
include './security_check.php';

//include topmenu, is stored in the $topmenu variable
include ('./topmenu.php');
$body .= $topmenu;
//$db->debug_all = true;

//include page specific secondary menu, is stored in the $topmenu2 variable
include ('./pi_menu.php');
$body .= $topmenu2;

//include rightside menu, is stored in the $toolmenu variable
include ('./toolmenu.php');

  	//check signpost table to see if ped chart filled out previously so we can fill it out automatically
  	if ($cur_ped = $db->get_var("SELECT sign FROM signpost WHERE sign_usr = $s_usr AND sign_mod = $s_mod")) {
  	  	$cur_ped = substr($cur_ped, 3, 1);
  	} else { 
		$db->query("INSERT INTO signpost (sign_usr, sign_mod, sign, sign_dt) VALUES ($s_usr, $s_mod, 'ped0', now())");
		$cur_ped = 0; 
	}

  	$image = $db->get_var("SELECT ped_img FROM pedchart WHERE ped_mod_id = $s_mod AND ped_order = $cur_ped");
	$save_res_url = "$s_path/pedchart/$image"; 
	$save_res_type = "img"; 

$body .= <<<eoq

<div id="actionbox">

<div id=step>
	<h1>Click on the next question in the list or the pedigree chart to research the family history. 
	(The pedigree chart will update automatically)</h1>

eoq;

	$img = "ped0.jpg"; //hardcode starting image, will get overwritten
	$body .= "<a href=pi_pedchart_reset.php>Reset Chart</a>";
	$body .= "<ol>";
	$rows = $db->get_results("SELECT * FROM pedchart WHERE ped_mod_id = $s_mod AND ped_order <= $cur_ped ORDER BY ped_order ASC");
	foreach ($rows as $row) {
		if ($row->ped_order > 0) {
			$body .= "<li>$row->ped_question - <br><b>$row->ped_answer</b></li>";
		}
	}
	if ($next_que = $db->get_row("SELECT ped_order, ped_question FROM pedchart WHERE ped_id = ".$row->ped_next_que)) {
		$body .= "<li><a href=pi_pedchart_next.php?ped=".$next_que->ped_order.">".$next_que->ped_question."</a></li>";
	}
	$body .= <<<eoq

	</ol>
	<img src=$s_path/pedchart/$row->ped_img style="padding:10px;">
</div>

<div id=step>

eoq;

  if ($ques = $db->get_results("SELECT * FROM research_ques WHERE rq_title = 'Pedigree Chart Question' AND rq_usr_id = $s_usr AND rq_mod_id = $s_mod")) {
    	foreach ($ques as $que) {
      		$body .= "<h3>$que->rq_ques</h3>";
      		$body .= "<p>$que->rq_ans</p>";
    	}
  } else {

	$body .= <<<eoq

	<h1>Answer the questions when you have finished your research.</h1>
	<form name=pedques method=post action=save_researchques.php>
	 <input type=hidden name=q_cnt value=2>
		Which pattern of inheritance seems most likely in this family?
		<input type=hidden name=q1 value="Which pattern of inheritance seems most likely in this family?">
		<br>
		<textarea name=a1 cols=60 rows=4></textarea>
		<br>
		How does this information affect your diagnosis?
		<input type=hidden name=q2 value="How does this information affect your diagnosis?">
		<br>
		<textarea name=a2 cols=60 rows=4></textarea>
		<br>
		<input type=hidden name=rq_title value="Pedigree Chart Question">
		<input type=submit name=submit value="Save to Notepad">
	</form>

eoq;

 	} 

	$body .= <<<eoq

</div>

</div>

eoq;
//end body

include './template.php'; //add in the standard page header 
?>