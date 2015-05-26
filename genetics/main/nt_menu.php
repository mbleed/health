<?php

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
    		background-color: #E7E4D3;
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

eoq;

		$self = $_SERVER['PHP_SELF'];
		$pg = end(explode('/', $self));
		switch ($s_mode) {
		case 'd':

$topmenu2 .= <<<eoq

<!-- topmenu2 -->
	<div id="topmenu2" class="yuimenu">
    		<div class="bd">	
      		<ul>
				<li class="yuimenuitem"><a href="nt_qu_home.php">Questions</a></li>
				<li class="yuimenuitem"><a href="nt_private.php">Private Notes</a></li>
				<li class="yuimenuitem"><a href="nt_msgs.php">Group Messages</a></li>
				<li class="yuimenuitem"><a href="nt_home.php#s">Notes</a></li>
				<li class="yuimenuitem"><a href="nt_home.php#a">Diagnoses</a></li>
              	</ul>            
		</div>
	</div>
<!-- end topmenu2 -->

eoq;

			break;
		case 'dt':
			
$topmenu2 .= <<<eoq

<!-- topmenu2 -->
	<div id="topmenu2" class="yuimenu">
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
<!-- end topmenu2 -->

eoq;

			break;
		} // end switch
?>