<?php 
$security_needed = 1; 
include './security_check.php';

$qu_id = $_GET['qu'];
$db->query("DELETE FROM notepad_question WHERE qu_id = $qu_id");

$lastpage = $_SERVER['HTTP_REFERER'];
header ("Location: $lastpage"); 
?>			
