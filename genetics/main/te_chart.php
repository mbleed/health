<?php 
$security_needed = 1; 
include './security_check.php';

//include topmenu, is stored in the $topmenu variable
include ('./topmenu.php');
$body .= $topmenu;

//include page specific secondary menu, is stored in the $topmenu2 variable
include ('./te_menu.php');
$body .= $topmenu2;

//include rightside menu, is stored in the $toolmenu variable
include ('./toolmenu.php');

$body .= <<<eoq

<div id="actionbox">

eoq;

	include './toggle.php'; //add in the patient toggle module

	if ($who <> '') { 

$body .= <<<eoq

<div id=step>
	<h1>$who's Dental Chart</h1>

eoq;

	$dir = "$s_path/$who/charts/";
	$found_file = true;
	for ($i = 1; $found_file; $i++) {
		$file = $dir."dc".$i.".jpg";
		if (file_exists($file))	$body .= "<img src=$file>";
		else $found_file = false;
	}  

	}

$body .= <<<eoq

</div>

</div>

eoq;
//end body

include './template.php'; //add in the standard page header 
?>