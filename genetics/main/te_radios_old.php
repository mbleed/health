<?php 
$security_needed = 1; 
include './security_check.php';

function nl2br2($string) {
	$string = str_replace(array("\r\n", "\r", "\n"), "<br />", $string);
	return addslashes($string);
}

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

$body .= <<<eoq

<style>
#PhotoContainer {
	border: 1px solid #221111;
	margin: 0.5em;
	padding: 0.5em;
}
</style>

<div id=step>

<h1>Radiographs for <em>$who</em> - Click a radiograph to enlarge it.</h1>

<table><tr valign=top><td>

<ul>

eoq;

if ($imgs = $db->get_results("SELECT * FROM photos WHERE photo_mod_id = $s_mod AND photo_patient = '$who' AND photo_type = 'radio'")) {
	foreach ($imgs as $i) {
		$img = $i->photo_path.$i->photo_name; //add path to image
		list($width, $height, $type, $attr) = getimagesize($img);
		$thumb_h = $height/5;
		$thumb_w = $width/5;
		$body .= "<li><a href=te_radios.php?img=$i->photo_id&who=$who><img id=\"img_$i->photo_id\" height=\"$thumb_h\" width=\"$thumb_w\" border=\"0\" src=\"$img\"></a></li> \n";
	}
}

$body .= <<<eoq

	</ul>

</td><td>

eoq;

if ($_GET['img']) {
	$photo_id = $_GET['img'];
	$i = $db->get_row("SELECT * FROM photos WHERE photo_id = $photo_id");
	$current_img_src = "$i->photo_path/$i->photo_name";
	$current_img_id = $i->photo_id;
	$body .= <<<eoq

<div class="Photo fn-container" id="PhotoContainer">
	<img src="$current_img_src" name="$current_img_id" id="current_img" />
	<h3>$i->photo_display_notes</h3>
</div>

<script>
function load_save_params() {
	document.getElementById('save_res_type').value = 'img';
	document.getElementById('save_res_url').value = '$current_img_src';
}
YAHOO.util.Event.onDOMReady(load_save_params);
</script>

eoq;

	} //end if img in GET param

$body .= <<<eoq

</td></tr></table>

</div>
                 
</div>

eoq;
//end body

include './template.php'; //add in the standard page header 
?>