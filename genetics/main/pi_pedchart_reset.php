<?php
$security_needed = 1; 
include './security_check.php';

	$db->query("UPDATE signpost SET sign = 'ped0' WHERE sign_usr = $s_usr AND sign_mod = $s_mod AND sign LIKE 'ped%'");

	$lastpage = $_SERVER['HTTP_REFERER'];
	header ("Location: $lastpage"); 
?>			
