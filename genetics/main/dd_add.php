<?php 
$security_needed = 1; 
include './security_check.php';

	$dia = $_GET['dia'];
	if ($db->get_row("SELECT * FROM diagnoses_chosen WHERE dc_dia_id = $dia AND dc_usr_id = $s_usr AND dc_mod_id = $s_mod")) {
			$errmsg = "<h3>You already have entered this diagnosis, please try again.</h3>";
	} else {
			$db->query("INSERT INTO diagnoses_chosen (dc_dia_id, dc_usr_id, dc_mod_id) VALUES ($dia, $s_usr, $s_mod)");
	}

	header ("Location: dd_home.php"); 
?>
