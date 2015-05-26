<?php

$genetic_tests = (in_array($s_mod, array("4","7","8"))) ? "<li class=\"yuimenuitem\"><a href=\"info_te_gene.php\">Genetic Tests</a></li>" : ""; 
//$dentchart = ($s_mod == 11) ? '' : '<li class="yuimenuitem"><a href="info_te_chart.php">Dental Chart</a></li>';
$dentchart = '<li class="yuimenuitem"><a href="info_te_chart.php">Dental Chart</a></li>';


$topmenu2 .= <<<eoq

<!-- Top Menu-specific styles -->
<style type="text/css">
	#topmenu2 {
		margin: 1em 0;
	}
	#topmenu2.yuimenu ul {
		margin: 0;
		padding: 0;
	}
	/* menu items */
	#topmenu2.yuimenu {
		background-color: #CCDDEE;
	}
	/* selected menu items */
	#topmenu2.yuimenu li.selected {
    		background-color: #EFE1C3;
	}
	#topmenu2.yuimenu li.selected a {
    		color: #221111;
		text-decoration: none;
	}
</style>

<!-- Top Menu-specific script -->
<script type="text/javascript">
onMenuBarReady = function() {
	// Instantiate and render the menu bar
	var oMenuBar = new YAHOO.widget.MenuBar("topmenu2", { autosubmenudisplay:true, hidedelay:750, lazyload:true });
	oMenuBar.render();
	items = oMenuBar.getItems();
	url_and_params = document.location.href.split('?');
	just_url = url_and_params[0];
	for (var i = 0; i < items.length; i++) {
		if (items[i].element.firstChild.href == just_url) {
			items[i].element.className += " selected";
			break;
		}
	}
};

// Initialize and render the menu bar when it is available in the DOM
YAHOO.util.Event.onContentReady("topmenu2", onMenuBarReady);
</script>

<!-- topmenu2 -->
	<div id="topmenu2" class="yuimenu">
    		<div class="bd">	
      		<ul>
				<li class="yuimenuitem"><a href="info_te_photos.php">Clinical Photos</a></li>
     			<li class="yuimenuitem"><a href="info_te_radios.php">Radiographs</a></li>
    			$dentchart
				$genetics_tests
              	</ul>            
		</div>
	</div>
<!-- end topmenu2 -->

eoq;

?>