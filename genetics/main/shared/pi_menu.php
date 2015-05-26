<?php
$pedchart = ($s_mod == 11) ? '' : '<li><a href="pi_pedchart.php?p=0">Pedigree Chart</a></li>';
$interview = ($s_mod == 11) ? '' : '<li><a href="pi_qa.php">Interview</a></li>';

$topmenu2 .= <<<eoq

<div class="row hidefromprint">
<div class="span12">
<div class="navbar navbar-inverse">
	<div class="navbar-inner">
		<div class="container">
			<ul class="nav">
			
	<li><a href="mod_index.php">Case Intro</a></li>
	<li><a href="pi_medhist.php">Medical History</a></li>
	<li><a href="pi_denthist.php">Dental History</a></li>
	$interview
	$pedchart
	
			</ul><!-- end nav -->
		</div><!-- end container -->
	</div><!-- end navbar-inner -->
</div><!-- end navbar -->
</div>
</div>           

eoq;
?>