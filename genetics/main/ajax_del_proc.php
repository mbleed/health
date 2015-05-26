<?php
$security_needed = 1; 
include './security_check.php';

$id = $_GET['id'];
$db->query("DELETE FROM problems_chosen WHERE pc_id = $id");

header ("Location: pl_home.php"); 
?>			
