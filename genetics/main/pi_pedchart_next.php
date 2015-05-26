<?php
$security_needed = 1; 
include './security_check.php';

	$sign = $_GET['ped'];
	$sign = "ped".$sign;

	$db->query("UPDATE signpost SET sign = '$sign' WHERE sign_usr = $s_usr AND sign_mod = $s_mod AND sign LIKE 'ped%'");

	$lastpage = $_SERVER['HTTP_REFERER'];
	header ("Location: $lastpage"); 
?>			
