<?php
$security_needed = 1; 
include './security_check.php';

	$pnid = $_POST['pnid'];

	$sql = "DELETE FROM photonotes WHERE pn_id = $pnid";
	$db->query($sql);

	$sql = "DELETE FROM notepad WHERE nte_res_type = 'pn' AND nte_res_url ' '$pnid'";
	$db->query($sql);
?>