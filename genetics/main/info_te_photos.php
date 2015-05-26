<?php 
$security_needed = 0;
include('./security_check_info.php');

//include topmenu, is stored in the $topmenu variable
include ('./info_topmenu.php');
$body .= $topmenu;

//include page specific secondary menu, is stored in the $topmenu2 variable
include ('./info_te_menu.php');
$body .= $topmenu2;

	$body .= <<<eoq

<div id="actionbox">

eoq;

	include './toggle.php'; //add in the patient toggle module

	if ($who <> '') { 

$body .= <<<eoq

<div id=step>
<h1>$who's photos, click a photo to enlarge it.</h1>
<table cellpadding=10>
<tr valign=top>
<td style="border-right: 1px dashed #CCC;">

eoq;

	$dirext = "$s_path/$who/photos/";
	$imgs = scandir($dirext);
	$body .= "<ul>\n";
	foreach ($imgs as $img) {
		if ($img != "." && $img != "..")  {	
			$img = $dirext.$img; //add path to image
			list($width, $height, $type, $attr) = getimagesize($img);
			$thumb_h = $height/5;
			$thumb_w = $width/5;
			$body .= "<li><img height=$thumb_h width=$thumb_w border=0 src=$img onClick=\"document.images['viewer'].src='$img'; document.forms['notes'].elements['save_res_url'].value='$img'; document.forms['notes'].elements['save_patient'].value='$who'; document.forms['notes'].elements['save_res_type'].value='img';\">\n";
		}
	}
	$body .= "</ul>";
	}

$body .= <<<eoq

</td>
<td><img name=viewer src="./img/blank.jpg"></td>
</tr>
</table>
</div>

</div>

eoq;
//end body

include './template_info.php'; //add in the standard page header 
?>