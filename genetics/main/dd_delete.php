<?php 
$security_needed = 1; 
include './security_check.php';

	$dc = $_GET['dc'];
	$sql = "DELETE FROM diagnoses_chosen WHERE dc_id = $dc AND dc_mod_id = $s_mod AND dc_usr_id = $s_usr";
	$db->query($sql);

header ("Location: dd_home.php"); 
?>