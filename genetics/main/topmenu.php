<?php
$topmenu = "";
$genetic_tests = (in_array($s_mod, array("4","7","8"))) ? "<li class=\"yuimenuitem\"><a href=\"te_gene.php\">Genetic Tests</a></li>" : ""; 
$pedchart = ($s_mod == 11) ? '' : '<li class="yuimenuitem"><a href="pi_pedchart.php?p=0">Pedigree Chart</a></li>';
$interview = ($s_mod == 11) ? '' : '<li class="yuimenuitem"><a href="pi_qa.php">Interview</a></li>';
//$dentchart = ($s_mod == 11) ? '' : '<li class="yuimenuitem"><a href="te_chart.php">Dental Chart</a></li>';
$dentchart = '<li class="yuimenuitem"><a href="te_chart.php">Dental Chart</a></li>';

$diagnoses = ($s_mod == 11) ? '<li class="yuimenubaritem"><a href="dd_caries.php">Diagnosis</a></li>' : '<li class="yuimenubaritem"><a href="dd_home.php">Diff. Diagnoses</a></li>';
//echo $s_mod;
$topmenu .= <<<eoq

<!-- Top Menu-specific script -->
<script type="text/javascript">
onMenuBarReady = function() {
	// Instantiate and render the menu bar
	var oMenuBar = new YAHOO.widget.MenuBar("topmenu", { autosubmenudisplay:true, hidedelay:750, lazyload:true });
	oMenuBar.render();
};

// Initialize and render the menu bar when it is available in the DOM
YAHOO.util.Event.onContentReady("topmenu", onMenuBarReady);
</script>

eoq;

		$self = $_SERVER['PHP_SELF'];
		$pg = end(explode('/', $self));
		switch ($s_mode) {
		case 'd':

			$tabs = <<<eoq

<!-- topmenu -->
                     <div id="topmenu" class="yuimenubar">
                            <div class="bd">
                                <ul class="first-of-type">
                                  	<li class="yuimenubaritem first-of-type"><a href="nt_home.php">Notepad</a>
                                        <div id="notepad" class="yuimenu">
                                            <div class="bd">
                                                <ul>
									<li class="yuimenuitem"><a href="nt_qu_home.php">Questions</a></li>
									<li class="yuimenuitem"><a href="nt_msgs.php">Group Messages</a></li>
									<li class="yuimenuitem"><a href="nt_private.php">Private Notes</a></li>
									<li class="yuimenuitem"><a href="nt_home.php#s">Notes</a></li>
									<li class="yuimenuitem"><a href="nt_home.php#a">Diagnoses</a></li>
              						</ul>  
                                            </div>
                                        </div>   
                                  	</li>
                                    <li class="yuimenubaritem"><a href="re_home.php">Resources</a>
                                    </li>
                                    <li class="yuimenubaritem"><a href="mod_index.php">Patient</a>
                                      	<div id="patient" class="yuimenu">
                                          	<div class="bd">
                                                <ul>
                                                    <li class="yuimenuitem"><a href="mod_index.php">Case Intro</a></li>
                                                    <li class="yuimenuitem"><a href="pi_medhist.php">Medical History</a></li>
                                                    <li class="yuimenuitem"><a href="pi_denthist.php">Dental History</a></li>
                                                    $interview
                                                    $pedchart
                                                </ul>
                                            </div>
                                        </div>   
						</li>
						<li class="yuimenubaritem"><a href="te_photos.php">Exams & Tests</a>
                                        <div id="tests" class="yuimenu">
                                            <div class="bd">
                                                <ul>
                                                    <li class="yuimenuitem"><a href="te_photos.php">Clinical Photos</a></li>
                                                    <li class="yuimenuitem"><a href="te_radios.php">Radiographs</a></li>
                                                    $dentchart
													$genetic_tests
                                                </ul>
                                            </div>
                                        </div>      
                                    </li>
                                    <li class="yuimenubaritem"><a href="pl_home.php">Problem List</a>
                                    </li>
                                    $diagnoses
                                    <li class="yuimenubaritem"><a href="cr_home.php">Case Report</a>
                                    </li>

                                </ul>            
                            </div>

                        </div>

<!-- end topmenu -->

eoq;

			break;
		case 'dt':
			$tabs = <<<eoq

<!-- topmenu -->
                     <div id="topmenu" class="yuimenubar">
                            <div class="bd">
                                <ul class="first-of-type">
                                  	<li class="yuimenubaritem first-of-type"><a href="nt_home.php">Notepad</a>
                                        <div id="notepad" class="yuimenu">
                                            <div class="bd">
                                                <ul>
									<li class="yuimenuitem"><a href="nt_qu_home.php">Questions</a></li>
									<li class="yuimenuitem"><a href="nt_private.php">Private Notes</a></li>
									<li class="yuimenuitem"><a href="nt_msgs.php">Group Messages</a></li>
									<li class="yuimenuitem"><a href="nt_home.php#s">Notes</a></li>
									<li class="yuimenuitem"><a href="nt_home.php#a">Diagnoses</a></li>
									<li class="yuimenuitem"><a href="nt_home.php#p">Treatment Objectives</a></li>
              						</ul>  
                                            </div>
                                        </div>   
                                  	</li>
                                    <li class="yuimenubaritem"><a href="re_home.php">Resources</a>
                                    </li>
                                    <li class="yuimenubaritem"><a href="pi_medhist.php">Patient</a>
                                        <div id="patient" class="yuimenu">
                                            <div class="bd">
                                                <ul>
                                                    <li class="yuimenuitem"><a href="mod_index.php">Case Intro</a></li>
                                                    <li class="yuimenuitem"><a href="pi_medhist.php">Medical History</a></li>
                                                    <li class="yuimenuitem"><a href="pi_denthist.php">Dental History</a></li>
                                                    $interview
                                                    $pedchart
                                                </ul>
                                            </div>
                                        </div>   
						</li>
						<li class="yuimenubaritem"><a href="te_photos.php">Exams & Tests</a>
                                        <div id="tests" class="yuimenu">
                                            <div class="bd">
                                                <ul>
                                                    <li class="yuimenuitem"><a href="te_photos.php">Clinical Photos</a></li>
                                                    <li class="yuimenuitem"><a href="te_radios.php">Radiographs</a></li>
                                                    $dentchart
								    				$genetic_tests
                                                </ul>
                                            </div>
                                        </div>      
                                    </li>
                                    <li class="yuimenubaritem"><a href="pl_home.php">Problem List</a>
                                    </li>
                                    $diagnoses
                                    <li class="yuimenubaritem"><a href="cd_home.php">Tx Objectives</a>
                                    </li>
                                    <li class="yuimenubaritem"><a href="cr_home.php">Case Report</a>
                                    </li>

                                </ul>            
                            </div>

                        </div>

<!-- end topmenu -->

eoq;
			break;
		} // end switch


$topmenu .= $tabs;

?>