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

//get photonote and photo from db
	$pnid = $_GET['pnid'];
	$sql = "SELECT * FROM photonotes WHERE pn_id = $pnid";
	$r = $db->get_row($sql);
	$note_html = nl2br2($r->pn_data);
	$photo_id = $r->pn_photo_id;
	$pn_dt = substr($r->pn_dt, 0, 10);

$body .= <<<eoq

<div id="actionbox">

<script src="./classes/photonotes/photonotes.js" type="text/javascript"></script>
<link href="./classes/photonotes/photonotes.css" rel="stylesheet" type="text/css" />

<style>
#PhotoContainer {
	border: 1px solid #221111;
	margin: 0.5em;
	padding: 0.5em;
}
</style>

<div id=step>

<h1>Image with specified user note</h1>

eoq;

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

<script>
var insertid = 0;
var notec = new PhotoNoteContainer(document.getElementById('PhotoContainer'));
var phototext = '$note_html';
var note$r->pn_id = new PhotoNote(phototext,$r->pn_id,new PhotoNoteRect($r->pn_coords));
notec.AddNote(note$r->pn_id);
</script>

</div>
                 
</div>

eoq;
//end body

include './template.php'; //add in the standard page header 
?>