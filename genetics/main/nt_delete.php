<?php 
$security_needed = 1; 
include './security_check.php';

	$nte_id = $_GET['nt'];
	$db->query("DELETE FROM notepad WHERE nte_id = $nte_id");

	$lastpage = $_SERVER['HTTP_REFERER'];
	header ("Location: $lastpage"); 
?>			
