<?php
$security_needed = 1; 
include './security_check.php';

//check if case report exists for this group, if not create it
if (!$db->get_row("SELECT * FROM case_report WHERE cr_grp_id = $s_grp AND cr_mod_id = $s_mod")) {
	$db->query("INSERT INTO case_report (cr_mod_id, cr_grp_id, cr_dt) VALUES ($s_mod, $s_grp, now())");
}

//escape text fields and assign to vars
foreach($_POST as $var=>$val) $$var = $db->escape($val);

$sql = "UPDATE case_report SET cr_sub = '$cr_sub', cr_obj = '$cr_obj', cr_dia = '$cr_dia', cr_tre = '$cr_tre', cr_dt = now(), cr_status = 1 WHERE cr_mod_id = $s_mod AND cr_grp_id = $s_grp";
$db->query($sql);
echo $sql;
?>