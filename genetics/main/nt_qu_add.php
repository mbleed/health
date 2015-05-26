<?php 
$security_needed = 1; 
include './security_check.php';

if ($_POST['Add']) {
	$add_question = $db->escape($_POST['qu']);
	$db->query("INSERT INTO notepad_question (qu_grp_id, qu_mod_id, qu_dt, qu_txt) VALUES ($s_grp, $s_mod, now(), '$add_question')");
}

$lastpage = $_SERVER['HTTP_REFERER'];
header ("Location: $lastpage"); 
?>