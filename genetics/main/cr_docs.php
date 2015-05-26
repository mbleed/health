<?php 
$security_needed = 1; 
include './security_check.php';

//include topmenu, is stored in the $topmenu variable
include ('./topmenu.php');
$body .= $topmenu;

//include rightside menu, is stored in the $toolmenu variable
include ('./toolmenu.php');

//use variabel to turn on and off expert case report viewing for security reasons
$show_expert_report = true;
$my_class = $db->get_var("SELECT grp_class_id FROM groups WHERE grp_id = $s_grp");
$classes_no_expert_report = array();
//add class ids here who you want to NOT see the expert report when filing
$classes_no_expert_report[] = 7;
if (in_array($my_class, $classes_no_expert_report)) $show_expert_report = false;

$body .= <<<eoq

<div id="actionbox">

<h1>Available Case Documents</h1>

<center>
<table cellpadding=5 cellspacing=5 bgcolor=#EEEEEE>
<tr>
<th></th>
<th>Adobe PDF</th>
<th>HTML Doc</th>
<th>Text Doc</th>
</tr>
<tr>
<td>
<h2>Your Case Report</h2>
</td>
<td align=center>

eoq;

	$path = "../reports/m".$s_mod."_g".$s_grp;
	$fn = $path.".pdf";
	$fs = round(filesize($fn)/1000);
	if (file_exists($fn)) $body .= "<a href=$fn><img border=0 src=img/pdf_doc.jpg><br>View</a><br>Size: $fs KB";

	$body .= "</td><td align=center>";

	$fn = $path.".html";
	$fs = round(filesize($fn)/1000);
	if (file_exists($fn)) $body .= "<a href=$fn><img border=0 src=img/html_doc.jpg><br>View</a><br>Size: $fs KB";

	$body .= "</td><td align=center>";

	$fn = $path.".txt";
	$fs = round(filesize($fn)/1000);
	if (file_exists($fn)) $body .= "<a href=$fn><img border=0 src=img/txt_doc.jpg><br>View</a><br>Size: $fs KB";

	$body .= <<<eoq

</td>
</tr>

eoq;

if ($show_expert_report) { 
$body .= <<<eoq

<tr>
<td>
<h2>The Expert's Case Report</h2>
</td>
<td align=center>

eoq;

	$path = "../m".$s_mod."/casereport/casereport"; 

	$fn = $path.".pdf";
	$fs = round(filesize($fn)/1000);
	if (file_exists($fn)) $body .= "<a href=$fn><img border=0 src=img/pdf_doc.jpg><br>View</a><br>Size: $fs KB";

	$body .= "</td><td align=center>";

	$fn = $path.".html";
	$fs = round(filesize($fn)/1000);
	if (file_exists($fn)) $body .= "<a href=$fn><img border=0 src=img/html_doc.jpg><br>View</a><br>Size: $fs KB";

	$body .= "</td><td align=center>";
 
	$fn = $path.".txt";
	$fs = round(filesize($fn)/1000);
	if (file_exists($fn)) $body .= "<a href=$fn><img border=0 src=img/txt_doc.jpg><br>View</a><br>Size: $fs KB";

	$body .= <<<eoq

</td>
</tr>

eoq;
}

$body .= <<<eoq

</table>
</center>

</div>

eoq;
//end body

include './template.php'; //add in the standard page header 
?>