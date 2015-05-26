<?php
$security_needed = 1; 
include './security_check.php';

$que = $_POST['que'];

$qa = $db->get_row("SELECT * FROM question INNER JOIN answer ON que_ans_id = ans_id WHERE que_txt = '$que' AND que_mod_id = $s_mod");

$media = "$s_path/$qa->ans_media";

if ($qa->ans_type == 'txt') {
	$dir = "$s_path/img/interview";
	$images = scandir($dir);
	$getridofperiods = array_shift($images);
	$getridofperiods = array_shift($images);
	$random_key = array_rand($images);
	$img = $images[$random_key];
	$img_html .= "<img src=$dir/$img>";
	$media = "blank.mov";
}

$body .= "$media|||";
$body .= $img_html;
$body .= "<i>$qa->que_txt</i>";
$body .= "<br>";
$body .= "$qa->ans_txt";

echo $body;
?>