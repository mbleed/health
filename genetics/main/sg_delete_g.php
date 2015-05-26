<?php
$security_needed = 1; 
include './security_check.php';

$id = $_GET['id'];

$db->query("DELETE FROM groups WHERE grp_id = $id");
	
header ("Location: ./setup_groups.php"); 
?>		