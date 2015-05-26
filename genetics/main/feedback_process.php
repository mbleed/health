<?php
$security_needed = 0; 
include './security_check.php';

$url = $_SERVER['HTTP_REFERER'];
$screen_a = explode("/", $url);
$screen = 'test';

$fb_txt = $db->escape($_POST['feedback']);
$fb_type = $_POST['fb_type'];

$sql = "INSERT INTO feedback (";
$sql .= "fb_type, fb_usr_id, fb_txt, fb_dt, fb_mod_id, fb_scr";
$sql .= ") VALUES (";
$sql .= "'$fb_type', $s_usr, '$fb_txt', now(), $s_mod, '$screen'";
$sql .= ")";
$db->query($sql);
//echo $sql;
//$db->debug();

$lastpage = $_SERVER['HTTP_REFERER'];
header ("Location: $lastpage"); 
?>