<?php 
$security_needed = 1; 
include './security_check.php';

$risk = $_POST['cariesrisk'];
//print_r($_POST);

$sql = "INSERT INTO caries (mod_id, grp_id, whenset, risk) VALUES ($s_mod, $s_grp, now(), '$risk')";

$db->query($sql);
//echo $sql;

$lastpage = $_SERVER['HTTP_REFERER'];
header ("Location: $lastpage");
?>