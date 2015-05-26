<?php 
$security_needed = 0; 
$s_mod = $_GET['m'];
include './security_check_info.php';

//1st time accessing module logic, set module session var
	if ($_GET['m'] <> '') {
    		$_SESSION['mod'] = $_GET['m'];
    		$s_mod = $_GET['m'];
  	} 

	$s_mode = $_SESSION['mode'];
	$s_mod = $_SESSION['mod'];
	$_SESSION['path'] = "../".$db->get_var("SELECT mod_path FROM module WHERE mod_id = $s_mod");
	$s_path = $_SESSION['path'];

//include topmenu, is stored in the $topmenu variable
include ('./info_topmenu.php');
$body .= $topmenu;

//include page specific secondary menu, is stored in the $topmenu2 variable
include ('./info_pi_menu.php');
$body .= $topmenu2;

$body .= <<<eoq

<div id="actionbox">
<div id="step">

eoq;

	$row = $db->get_row("SELECT * FROM module WHERE mod_id = $s_mod");
	$body .= "<h2>$row->mod_name</h2>";
	$body .= "<img src=\"$s_path/img/patient_lg.jpg\" class=\"floatleft\">";
	$body .= "<div style=\"clear:both\">&nbsp;</div>";
	$body .= "<p style='font-size: 122%;'>$row->mod_abstract</p>";
	$body .= "<p>Module Author(s): $row->mod_credits</p>";

	$body .= <<<eoq
</div>
</div>

eoq;

include './template_info.php'; //add in the standard page header 
?>
