<?php 
$security_needed = 1; 
include './security_check.php';

//include topmenu, is stored in the $topmenu variable
include ('./shared/topmenu.php');
$body .= $topmenu;

//include page specific secondary menu, is stored in the $topmenu2 variable
include ('./shared/pi_menu.php');
$body .= $topmenu2;

//include rightside menu, is stored in the $toolmenu variable
include ('./shared/toolmenu.php');


if ($imgs = $db->get_results("SELECT * FROM photos WHERE photo_mod_id = $s_mod AND photo_patient = 'Mitch' AND photo_type = 'photo'")) {
	foreach ($imgs as $ikey=>$i) {
		$img = $i->photo_path.$i->photo_name; //add path to image
		$image_list_html .= <<<htmleoq
		
	<li class="span4">
		<a href="#thumbnailModal$ikey" class="thumbnail" data-toggle="modal">
			<img src="$img" alt="">
		</a>
	</li>
	
htmleoq;

$modal_html .= <<<htmleoq

	<div class="modal hide" id="thumbnailModal$ikey" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-body">
			<img src="$img" alt="" title="" />
			<p>$i->photo_display_notes</p>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		</div>
	</div>
		
htmleoq;

	}
}

$body .= <<<eoq

<ul class="thumbnails">
  $image_list_html
</ul>

$modal_html

eoq;
//end body

include './shared/template.php'; //add in the standard page header 
?>