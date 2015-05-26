<?php
	include "./classes/ez_sql.php"; //include db class

	session_name('gedSessionInfo');
	session_start();
	
	//blank out vars needed later
	$body = "";
	$headerscripts = "";
	
		//init some vars 
	$icon_path = "/genetics/css/icons";
	$yui_path = "/genetics/src/yui2.6";
	
	$cur_security_level = 0; 	//get current level of security from session

	//check if module has been set, in module
	if ($_SESSION['mod'] > 0) {
		$s_mod = $_SESSION['mod'];
		$_SESSION['path'] = "../".$db->get_var("SELECT mod_path FROM module WHERE mod_id = $s_mod");
		$s_path = $_SESSION['path'];
	} else if ($_GET['m'] > 0) {
		$s_mod = $_GET['m'];
		$_SESSION['MOD'] = $s_mod;
	} else $s_mod = 0;

	//now get all module information if needed
	if ($s_mod > 0) {
		$module_row = $db->get_row("SELECT * FROM module WHERE mod_id = $s_mod");
		$patients_csv = $module_row->mod_pat_name;
		$patients_array = explode(',', $patients_csv);
	}

	//additional check to see if module has been chosen to stop people coming in directly to these pages from Google without selecting a mod_id
	if ($s_mod < 1) header("Location: ./index.php"); 

	//get module information
	if ($s_mod > 0) {
		$module_row = $db->get_row("SELECT * FROM module WHERE mod_id = $s_mod");
		$patients_csv = $module_row->mod_pat_name;
		$patients_array = explode(',', $patients_csv);
	}
	
?>