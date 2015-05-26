<?php
$security_needed = 1; 
include './security_check.php';

$id = $_GET['id'];

$sql = "UPDATE groups SET grp_add_to = 'N' WHERE grp_class_id = $id"; 
$db->query($sql);
	
header ("Location: ./setup_groups.php"); 
?>		