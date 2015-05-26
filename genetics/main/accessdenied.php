<?php 
$security_needed = 0; 
include './security_check.php';

//include topmenu, is stored in the $topmenu variable
include ('./topmenu.php');
$body .= $topmenu;

$today = date("l F dS, Y  h:i:s A");

$body .= <<<eoq

<div id="actionbox">
<h2>Access Denied</h2>
<h3>$today</h3>
<p>
	You must <a href=index.php>login</a> before you can visit this section of the website.  Contact a system
	administrator if you feel this message is in error. 
</p>
</div>

eoq;
//end body

include './template.php'; //add in the standard page header 
?>