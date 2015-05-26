<?php
$security_needed = 1; 
include './security_check.php';

  $q_cnt = $_POST['q_cnt'];
  for ($i=1; $i<=$q_cnt; $i++) {
    $ques = "q$i";
    $ans = "a$i";
    $rq_ques = $db->escape($_POST[$ques]);
    $rq_ans = $db->escape($_POST[$ans]);
    $rq_title = $_POST['rq_title'];
    $db->query("INSERT INTO research_ques (rq_dt, rq_usr_id, rq_mod_id, rq_num, rq_ques, rq_ans, rq_title) VALUES (now(), $s_usr, $s_mod, $i, '$rq_ques', '$rq_ans', '$rq_title')");
  }

$lastpage = $_SERVER['HTTP_REFERER'];
header ("Location: $lastpage"); 
?>			
