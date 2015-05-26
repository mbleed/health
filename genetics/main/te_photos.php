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

//group filter for notes
function setUrlVariables() {
  $arg = array();
  $string = "?";
  $vars = $_GET;
  for ($i = 0; $i < func_num_args(); $i++)
    $arg[func_get_arg($i)] = func_get_arg(++$i);
  foreach (array_keys($arg) as $key)
    $vars[$key] = $arg[$key];
  foreach (array_keys($vars) as $key)
    if ($vars[$key] != "") $string.= $key . "=" . $vars[$key] . "&";
  if (SID != "" && SID != "SID" && $_GET["PHPSESSID"] == "")
    $string.= htmlspecialchars(SID) . "&";

  return htmlspecialchars(substr($string, 0, -1));
}

$filter = $_GET['filter'];
$fullpg = $_SERVER['PHP_SELF'];
$pg = end(explode('/', $fullpg));
$get_vars = setUrlVariables("filter","");
$final_link = $pg.$get_vars;

//check to see if group or individual
	if ($s_grp_type == 'G') {
	//print filter options using all members of current user group
		$notes_filter .= "<p>Show notes from: ";
		$notes_filter .= "| <a href=$final_link&filter=$s_usr>My Data</a> | ";
		$notes_filter .= "<a href=$final_link&filter=all>$s_grp_txt</a> | ";
		if ($members = $db->get_results("SELECT * FROM x_usr_grp x INNER JOIN usr u ON x.usr_id = u.usr_id WHERE x.grp_id = $s_grp")) {
  			foreach ($members as $member) {
  			 	if ($member->usr_id <> $s_usr) {
  					$notes_filter .= "<a href=$final_link&filter=$member->usr_id>$member->usr_fname $member->usr_lname</a> | ";
  	 			}
  			}
  		}
		$notes_filter .= "</p>";

		//build filter based on user choice
			if ($filter == '') { $filter = 'all'; }
			if ($filter == 'all') {
				$ids = $db->get_results("SELECT * FROM x_usr_grp x INNER JOIN usr u ON x.usr_id = u.usr_id WHERE x.grp_id = $s_grp");
			} else {
				//check to see if id is actually in the user's group to prevent manual tampering
				$idingroup = false;
				$idscheck = $db->get_results("SELECT * FROM x_usr_grp x INNER JOIN usr u ON x.usr_id = u.usr_id WHERE x.grp_id = $s_grp");
				foreach ($idscheck as $idcheck) {
					if ($idcheck->usr_id == $filter) { $idingroup = true; }
				}
				if (!$idingroup) { $filter = $s_usr; }

				$ids[] = $db->get_row("SELECT * FROM x_usr_grp x INNER JOIN usr u ON x.usr_id = u.usr_id WHERE x.usr_id = $filter AND x.grp_id = $s_grp");
			}
	} else {
		//individual
		$notes_filter .= "<p>You are working as an individual, not currently in a group.  <a href=choose_group.php?u=$s_usr>Join a Group</a></p>";
		$ids[] = $db->get_row("SELECT * FROM x_usr_grp x INNER JOIN usr u ON x.usr_id = u.usr_id WHERE x.usr_id = $s_usr AND x.grp_id = $s_grp");
	}


$body .= <<<eoq

<div id="actionbox">

<script src="./classes/photonotes/photonotes.js" type="text/javascript"></script>
<link href="./classes/photonotes/photonotes.css" rel="stylesheet" type="text/css" />

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

<h1>Clinical photos for <em>$who</em> - Click on a photo to enlarge it and add notes to it.</h1>

<table><tr valign=top><td>

<ul>

eoq;

if ($imgs = $db->get_results("SELECT * FROM photos WHERE photo_mod_id = $s_mod AND photo_patient = '$who' AND photo_type = 'photo'")) {
	foreach ($imgs as $i) {
		$img = $i->photo_path.$i->photo_name; //add path to image
		list($width, $height, $type, $attr) = getimagesize($img);
		$thumb_h = $height/5;
		$thumb_w = $width/5;
		$body .= "<li><a href=te_photos.php?img=$i->photo_id&who=$who><img id=\"img_$i->photo_id\" height=\"$thumb_h\" width=\"$thumb_w\" border=\"0\" src=\"$img\"></a></li> \n";
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

$notes_filter

<div class="Photo fn-container" id="PhotoContainer">
	<img src="$current_img_src" name="$current_img_id" id="current_img" />
	<h3>$i->photo_display_notes</h3>
  	<br />
	<input type="button" value="Add a Note" onclick="AddNote();" />
	<input type="button" value="Hide Notes" onclick="notec.HideAllNotes();" />
	<input type="button" value="Show Notes" onclick="notec.ShowAllNotes();" />
	<ul>
		<li>This section allows you to add annotations directly onto the image above.  Click the 'Add Note' button to add a new note.  Then, you may drag and resize the box to graphically show what in the image your note refers to.</li>
		<li>Hovering over a box will display that note's detailed text.</li>
		<li>Hide Notes will remove them temporarily from the image so that you may get a clear view.</li>
		<li>Show Notes will restore hidden notes.</li>
		<li>Clicking on an existing note will allow you to move/resize/edit/delete any existing note.</li>
		<li>Due to browser limitations, only the last 10 notes will be shown.</li>
	</ul>
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

eoq;

foreach ($ids as $id) {
	$sql = "SELECT * FROM photonotes WHERE pn_mod_id = $s_mod AND pn_usr_id = $id->usr_id AND pn_photo_id = $current_img_id ORDER BY pn_dt DESC LIMIT 10";
	if ($rs = $db->get_results($sql)) {
		foreach ($rs as $r) {
			$note_html = nl2br2($r->pn_data);
			$body .= <<<eoq
		
	pn_dt = '$r->pn_dt'.substring(0,10);
	var phototext = '$note_html';
	var note$r->pn_id = new PhotoNote(phototext,$r->pn_id,new PhotoNoteRect($r->pn_coords));

eoq;

	if ($id->usr_id == $s_usr) {
	$body .= <<<eoq

  	note$r->pn_id.onsave = function(note$r->pn_id) {
		params = encodeURI('note='+note$r->pn_id.gui.TextBox.value+'&rect='+note$r->pn_id.rect+'&pnid=$r->pn_id');
		YAHOO.util.Connect.asyncRequest(
			'POST', 
			'ajax_save_pn.php', 
			{
				success: function(o) { alert ('Saved Note.'); },
				//success: function(o) { alert (o.responseText);},
				failure: function(o) { alert ('There was an error saving this note.'); }
			},
			params
		);
		return 1;
	}
	note$r->pn_id.ondelete = function(note$r->pn_id) {
		params = encodeURI('pnid='+note$r->pn_id.id);
		YAHOO.util.Connect.asyncRequest(
			'POST', 
			'ajax_delete_pn.php', 
			{
				success: function(o) { alert ('Deleted Note.'); },
				failure: function(o) { alert ('There was an error deleting this note.'); }
			},
			params
		);
		return 1;
	}

eoq;

	}

	$body .= <<<eoq

	notec.AddNote(note$r->pn_id);

eoq;

		} //end foreach rs
	} //end if photonotes rs
} //end foreach ids

	$body .= <<<eoq

function AddNote() {
	var AjaxAddNote = {
		handleSuccess:function(o){ 
			var insertid = o.responseText;
			var newNote = new PhotoNote('Add note text here...',o.responseText,new PhotoNoteRect(10,10,50,50));
			newNote.onsave = function(newNote) { 
				params = encodeURI('note='+newNote.gui.TextBox.value+'&rect='+newNote.rect+'&pnid='+insertid);
				YAHOO.util.Connect.asyncRequest(
					'POST', 
					'ajax_save_pn.php', 
					{
				success: function(o) { alert ('Saved Note.'); },						
					failure: function(o) { alert ('There was an error saving this note.'); }
					},
					params
				);
				return 1;
			}
			newNote.ondelete = function(newNote) { 
				params = encodeURI('pnid='+insertid);
				YAHOO.util.Connect.asyncRequest(
					'POST', 
					'ajax_delete_pn.php', 
					{
						success: function(o) { alert ('Deleted Note.'); },
						failure: function(o) { alert ('There was an error deleting this note.'); }
					},
					params
				);
				return 1;
			}
			notec.AddNote(newNote);
   			newNote.Select(); 
		},
		handleFailure:function(o){ alert ('There was an error adding this note.'); },
		startRequest:function() {
 			photo_id = document.getElementById('current_img').name;
			var params = 'id='+photo_id;
   			YAHOO.util.Connect.asyncRequest('POST', 'ajax_add_pn.php', AjaxAddNoteCallback, params);
		}
	};
	var AjaxAddNoteCallback =
	{
		success: AjaxAddNote.handleSuccess,
		failure:AjaxAddNote.handleFailure
	};
	AjaxAddNote.startRequest();
}
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