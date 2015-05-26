<?php
$topmenu = "";
$genetic_tests = (in_array($s_mod, array("4","7","8"))) ? "<li><a href=\"te_gene.php\">Genetic Tests</a></li>" : ""; 
$pedchart = ($s_mod == 11) ? '' : '<li><a href="pi_pedchart.php?p=0">Pedigree Chart</a></li>';
$interview = ($s_mod == 11) ? '' : '<li><a href="pi_qa.php">Interview</a></li>';
$dentchart = ($s_mod == 11) ? '' : '<li><a href="te_chart.php">Dental Chart</a></li>';
$diagnoses = ($s_mod == 11) ? '<li><a href="dd_caries.php">Diagnosis</a></li>' : '<li><a href="dd_home.php">Diff. Diagnoses</a></li>';

$self = $_SERVER['PHP_SELF'];
$pg = end(explode('/', $self));

$tabs = <<<eoq

<div class="row hidefromprint">
<div class="span12">
<div class="navbar">
	<div class="navbar-inner">
		<div class="container">
			<ul class="nav">
<!-- NOTEPAD -->
<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
      Notepad
      <b class="caret"></b>
    </a>
    <ul class="dropdown-menu">
		<li><a href="nt_qu_home.php">Questions</a></li>
		<li><a href="nt_msgs.php">Group Messages</a></li>
		<li><a href="nt_private.php">Private Notes</a></li>
		<li><a href="nt_home.php#s">Notes</a></li>
		<li><a href="nt_home.php#a">Diagnoses</a></li>
		<li><a href="nt_home.php#p">Treatment Objectives</a></li>
    </ul>
</li>
<!-- RESOURCES -->
<li><a href="re_home.php">Resources</a></li>
<!-- PATIENT -->
<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
      Patient
      <b class="caret"></b>
    </a>
    <ul class="dropdown-menu">
		<li><a href="mod_index.php">Case Intro</a></li>
		<li><a href="pi_medhist.php">Medical History</a></li>
		<li><a href="pi_denthist.php">Dental History</a></li>
		$interview
		$pedchart
    </ul>
</li>
<!-- EXAMS AND TESTS
<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
      Exams & Tests
      <b class="caret"></b>
    </a>
    <ul class="dropdown-menu">
		<li><a href="te_photos.php">Clinical Photos</a></li>
		<li><a href="te_radios.php">Radiographs</a></li>
		$dentchart
		$genetic_tests
	</ul>
</li>
<!-- PROBLEM LIST -->
<li><a href="pl_home.php">Problem List</a></li>
<!-- TREATMENT OBJECTIVES -->
<li><a href="cd_home.php">Tx Objectives</a></li>
<!-- CASE REPORT -->
<li><a href="cr_home.php">Case Report</a></li>

</ul><!-- end nav -->
</div><!-- end container -->
</div><!-- end navbar-inner -->
</div><!-- end navbar -->
</div>
</div>

eoq;

$topmenu .= $tabs;

?>