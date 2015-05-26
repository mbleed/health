<?php
$security_needed = 1; 
include './security_check.php';
//echo 'in';

$var = $db->escape($_POST['var']);
$val = $db->escape($_POST['val']);
//echo "<pre>"; print_r($_POST); echo "</pre>";

if ($rss_id = $db->get_var("SELECT rss_id FROM report_sections_saved WHERE rss_grp_id = $s_grp AND rss_mod_id = $s_mod AND rss_fieldid = '$var'")) {
	$db->query("UPDATE report_sections_saved SET rss_text = '$val', rss_update_dt = now() WHERE rss_id = $rss_id");
} else {
	$db->query("INSERT INTO report_sections_saved (rss_usr_id, rss_grp_id, rss_mod_id, rss_fieldid, rss_text) VALUES ($s_usr, $s_grp, $s_mod, '$var', '$val')");
} 

$db->debug();

?>