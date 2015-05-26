<?php
$topmenu = "";
$genetic_tests = (in_array($s_mod, array("4","7","8"))) ? "<li class=\"yuimenuitem\"><a href=\"info_te_gene.php\">Genetic Tests</a></li>" : ""; 
$pedchart = ($s_mod == 11) ? '' : '<li class="yuimenuitem"><a href="info_pi_pedchart.php?p=0">Pedigree Chart</a></li>';
$interview = ($s_mod == 11) ? '' : '<li class="yuimenuitem"><a href="info_pi_qa.php">Interview</a></li>';
$dentchart = ($s_mod == 11) ? '' : '<li class="yuimenuitem"><a href="info_te_chart.php">Dental Chart</a></li>';

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
		$tabs = <<<eoq

<!-- topmenu -->
                     <div id="topmenu" class="yuimenubar">
                            <div class="bd">
                                <ul class="first-of-type">
                                    <li class="yuimenubaritem"><a href="info_re_home.php">Resources</a>
                                    </li>
                                    <li class="yuimenubaritem"><a href="info_index.php">Patient</a>
                                        <div id="patient" class="yuimenu">
                                            <div class="bd">
                                                <ul>
                                                    <li class="yuimenuitem"><a href="info_index.php">Case Intro</a></li>
                                                    <li class="yuimenuitem"><a href="info_pi_medhist.php">Medical History</a></li>
                                                    <li class="yuimenuitem"><a href="info_pi_denthist.php">Dental History</a></li>
                                                    $interview
                                                    $pedchart
                                                </ul>
                                            </div>
                                        </div>   
						</li>
						<li class="yuimenubaritem"><a href="info_te_photos.php">Exams & Tests</a>
                                        <div id="tests" class="yuimenu">
                                            <div class="bd">
                                                <ul>
                                                    <li class="yuimenuitem"><a href="info_te_photos.php">Clinical Photos</a></li>
                                                    <li class="yuimenuitem"><a href="info_te_radios.php">Radiographs</a></li>
                                                    $dentchart
								    				$genetic_tests
                                                </ul>
                                            </div>
                                        </div>      
                                    </li>
                                </ul>            
                            </div>

                        </div>

<!-- end topmenu -->

eoq;

$topmenu .= $tabs;
?>
