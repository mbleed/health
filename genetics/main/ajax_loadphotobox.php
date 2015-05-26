<?php
$security_needed = 1; 
include './security_check.php';

function nl2br2($string) {
$string = str_replace(array("\r\n", "\r", "\n"), "<br />", $string);
return $string;
}

$id = $_POST['id'];
$i = $db->get_row("SELECT * FROM photos WHERE photo_id = $id");

$fullimg = "$i->photo_path/$i->photo_name";

$body .= "<img src=\"$fullimg\" name=\"$i->photo_id\" id=\"pb_img\" />";
$body .= "<h2>$i->photo_path</h2>";
$body .= "<br><br><br>";
$body .= "<input type=\"button\" value=\"Add a Note!\" style=\"margin-left:30px;\" onclick=\"AddNote();\" />"; 
$body .= "<input type=\"button\" value=\"Hide Notes\" style=\"margin-left:30px;\" onclick=\"nc.HideAllNotes();\" />";
$body .= "<input type=\"button\" value=\"Show Notes\" style=\"margin-left:30px;\" onclick=\"nc.ShowAllNotes();\" />";

$body .= "<script>";
   
$sql = "SELECT * FROM photonotes INNER JOIN (x_usr_grp x INNER JOIN usr u ON u.usr_id = x.usr_id) ON x.usr_id = pn_usr_id WHERE pn_mod_id = $s_mod AND x.grp_id = $s_grp AND pn_photo_id = $id";
if ($rs = $db->get_results($sql)) {
	$body .= "var photo_id = document.getElementById('pb_img').name; \n";
	foreach ($rs as $r) {
		$htmltext = nl2br2($r->pn_data);
		$body .= <<<eoq

	pn_dt = '$r->pn_dt'.substring(0,10);
	//var phototext = '$htmltext<br><span class="pnsmall">$r->usr_fname $r->usr_lname on '+pn_dt+'</span>';
	var phototext = '$htmltext';
	var note_$r->pn_id = new PhotoNote(phototext,$r->pn_id,new PhotoNoteRect($r->pn_coords));
	note_$r->pn_id.onsave = function(note_$r->pn_id) { 
		YAHOO.util.Connect.asyncRequest(
			'POST', 
			encodeURI('ajax_save_pn.php?note='+note_$r->pn_id.gui.TextBox.value+'&rect='+note_$r->pn_id.rect+'&pnid='+insertid), 
				{
				success: function(o) { alert ('Saved Note.'); },
				failure: function(o) { alert ('There was an error saving this note.'); }
				}
			);
		return 1;
	}
	note_$r->pn_id.ondelete = function(note_$r->pn_id) { 
		YAHOO.util.Connect.asyncRequest(
			'POST', 
			encodeURI('ajax_delete_pn.php?pnid='+note_$r->pn_id.id), 
				{
				success: function(o) { alert ('Deleted Note.'); },
				failure: function(o) { alert ('There was an error deleting this note.'); }
				}
			);
		return 1;
	}
	nc.AddNote(note_$r->pn_id);

eoq;
	}

} 
//$db->debug();
$body .= <<<eoq

</script>

eoq;

echo $body;

?>