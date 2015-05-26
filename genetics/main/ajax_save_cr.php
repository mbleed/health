<?php
$security_needed = 1; 
include './security_check.php';

//escape text fields and assign to vars
//print_r($_POST);
foreach($_POST as $var=>$val) $$var = htmlentities($val);
$sql = "UPDATE case_report SET cr_sub = '$cr_sub', cr_obj = '$cr_obj', cr_dia = '$cr_dia', cr_tre = '$cr_tre', cr_dt = now(), cr_status = 0 WHERE cr_mod_id = $s_mod AND cr_grp_id = $s_grp";
$db->query($sql);
echo $sql;
?>