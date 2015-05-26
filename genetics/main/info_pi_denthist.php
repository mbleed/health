<?php 
$security_needed = 0;
include('./security_check_info.php');

//include topmenu, is stored in the $topmenu variable
include ('./info_topmenu.php');
$body .= $topmenu;

//include page specific secondary menu, is stored in the $topmenu2 variable
include ('./info_pi_menu.php');
$body .= $topmenu2;

	$body .= <<<eoq

<div id="actionbox">

eoq;

	include 'toggle.php'; //add in the patient toggle module

	if ($who <> '') { 

$body .= <<<eoq

<div id=step>
	<h1>$who's Dental History</h1>

eoq;

		$dir = "$s_path/$who/charts/";

		$found_file = true;
		for ($i = 1; $found_file; $i++) {
			$file = $dir."dh".$i.".jpg";
			if (file_exists($file))	$body .= "<img src=\"$file\" style=\"border: 1px solid #998; margin: 5px;\"><br><br>";
			else $found_file = false;
		}  
	}

$body .= <<<eoq

</div>

</div>

eoq;
//end body

include './template_info.php'; //add in the standard page header 
?>