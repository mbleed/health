<?php

	$pg = $_SERVER['PHP_SELF'];
	$who = $_GET['who'];

	if (count($patients_array) > 1) { 			//add in multiple patient logic
		foreach ($patients_array as $key=>$pat) {
			$button_style .= "#toggle_button_$key a { padding-left:5em; background:url($s_path/img/$pat.jpg) 10% 50% no-repeat; } \n";
			$button_script .= "var toggle_button_$key = new YAHOO.widget.Button({ type:\"link\", id:\"toggle_button_$key\", label:\"<b>$pat</b>\", href:\"$pg?who=$pat\", container:\"toggle_button_group\" }); \n";
			//$button_script .= "var toggle_button_$key = new YAHOO.widget.Button(\"toggle_button_$key\") \n";
			//$button_html .= "<span id=\"toggle_button_$key\"><a href=\" $pg?who=$pat \"><img src=\"$s_path/img/$pat.jpg\"><br>$pat</a></span> \n";
		}
			
$body .= <<<eoq

<style>
#toggle_button_group {
	margin: 0.5em;
}
$button_style
</style>
<script>
$button_script
</script>
<div id="toggle_button_group">
<h1>Choose a patient.</h1>
$button_html
</div>

eoq;

	} else $who = $patients_array[0];

?>