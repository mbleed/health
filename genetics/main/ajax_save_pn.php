<?php
$security_needed = 1; 
include './security_check.php';

	$note = $db->escape($_POST['note']);
	$rectraw = $_POST['rect'];
	$pnid = $_POST['pnid'];
	//parse coords
	$rect = explode(",",$rectraw);
	$leftraw = $rect[0];
	$topraw = $rect[1];
	$widthraw = $rect[2];
	$heightraw = $rect[3];
	$left = substr($leftraw, 6);
	$top = substr($topraw, 5);
	$width = substr($widthraw, 7);
	$height = substr($heightraw, 8);
	$coords = "$left,$top,$width,$height";

	$sql = "UPDATE photonotes SET pn_data = '$note', pn_coords = '$coords', pn_dt = now() WHERE pn_id = $pnid";		
	$db->query($sql);

	$sql = "UPDATE notepad SET nte_txt = '$note' WHERE nte_res_url = $pnid";
	$db->query($sql);
?>