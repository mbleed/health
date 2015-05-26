<?php

$html = <<<htmleoq

<style>

h1 { 
	background-color: #AAA; 
	color: #FFF;
	padding-left: 20px;
	font-size: 14pt;
}
h2 {
	border-bottom: 2px solid #A1A1A1;
}
h3 {
	text-decoration: underline;
}
#all {
	font-family: Arial;	
}
.authorship {
	background-color: #0D4F5E;
	font-size: 85%;
	font-weight: bolder;	
	width: 79%;
	color: #DBDBDB;
	padding: 3px;
}
.authorship ul li {
	list-style-type: none;
}
</style>

<div id="all">

<img src="http://health.dent.umich.edu/genetics/main/img/header.jpg">

<div class="authorship">
Case Report
<ul>
<li>Module: $module</li>
<li>Authored By: $authors</li>
$group_li
<li>On: $dt</li>
</ul>
</div>

$subjective_report

$objective_report

$diagnosis_report

$treatment_report

$appendix1

$appendix2

$appendix3

</div>

htmleoq;

?>