<?php
$security_needed = 1; 
include './security_check.php';

$id = $_GET['id'];

$db->query("INSERT INTO problems_chosen (pc_pro_id, pc_mod_id, pc_usr_id) VALUES ($id, $s_mod, $s_usr)");
	
header ("Location: ./pl_home.php"); 
?>		