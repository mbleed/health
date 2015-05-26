<?php
$security_needed = 1; 
include './security_check.php';

	$photo_id = $_POST['id'];

	$sql = "INSERT INTO photonotes (pn_mod_id, pn_usr_id, pn_photo_id, pn_data, pn_coords, pn_dt) VALUES ($s_mod, $s_usr, $photo_id, 'Insert Note Here...', '10,10,50,50', now())";
	$db->query($sql);

	echo $db->insert_id;

	$sql = "INSERT INTO notepad (nte_mod_id, nte_usr_id, nte_res_type, nte_res_url, nte_txt, nte_soap, nte_dt) VALUES ($s_mod, $s_usr, 'pn', '$db->insert_id', '', 'o', now())";
	$db->query($sql);

	//echo $sql;
?>