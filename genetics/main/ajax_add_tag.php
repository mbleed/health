<?php
$security_needed = 1; 
include './security_check.php';

  	$tag_txt = urldecode($db->escape($_GET['tag']));
  	$res_id = $_GET['res'];
	$sql = "INSERT INTO resource_tags (tag_res_id, tag_txt, tagged_on) ";
 	$sql .= "VALUES ($res_id, '$tag_txt', now())";
  	$db->query($sql);
	$body .= $res_id;

	echo $body;
?>
