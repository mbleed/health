<?php
	include "./classes/ez_sql.php"; //include db class

	session_name('gedSession');
	session_start();
	
	//blank out vars needed later
	$body = "";
	$headerscripts = "";
	$save_res_url = "";
	
	//init some vars 
	$icon_path = "/genetics/css/icons";
	$yui_path = "/genetics/src/yui2.6";
	
	//hardcode type in right now:
	$s_mode = 'dt';
	
	//check if user is in session
	if ($_SESSION['usr'] > 0) {
		$s_usr = $_SESSION['usr'];
		$cur_security_level = 1;
		$s_usr_name = $_SESSION['usr_name'];
	} else {
		$cur_security_level = 0;
	}

	if ($cur_security_level < $security_needed) header("Location: ./accessdenied.php"); 	//stop and send user elsewhere if sec check fails
	
	//check if module has been set, in module
	if ($_SESSION['mod'] > 0) {
		$s_mod = $_SESSION['mod'];
		$_SESSION['path'] = "../".$db->get_var("SELECT mod_path FROM module WHERE mod_id = $s_mod");
		$s_path = $_SESSION['path'];
	} else $s_mod = 0;

	//now get all module information if needed
	if ($s_mod > 0) {
		$module_row = $db->get_row("SELECT * FROM module WHERE mod_id = $s_mod");
		$patients_csv = $module_row->mod_pat_name;
		$patients_array = explode(',', $patients_csv);
	
		//check if group has been chosen
		if ($x = $db->get_row("SELECT * FROM x_usr_grp x INNER JOIN groups g ON g.grp_id = x.grp_id WHERE x.usr_id = $s_usr AND x.mod_id = $s_mod")) {
			$_SESSION['grp'] = $x->grp_id;
			$s_grp = $_SESSION['grp'];
			$s_grp_txt = $x->grp_txt;	
			$s_grp_type = $x->grp_type;
		} elseif (basename($_SERVER['PHP_SELF']) <> "choose_group.php") { header("Location: ./choose_group.php?mod=$s_mod"); }
	}
?>