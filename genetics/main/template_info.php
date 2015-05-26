<?php

			//header buttons
			$header_buttons_html = "<span class=headerbuttons><ul class=actions>";
			if ($s_usr > 0) {
					$instructor_level = $db->get_row("SELECT * FROM instructor WHERE ins_usr_id = $s_usr");
					$header_buttons_html .= "<li><a href=\"usr_profile.php\"><img src=\"css/icons/user.png\" alt=\"\" />Edit Profile</a>";
   					$header_buttons_html .= "<li><a href=\"choose_module.php\"><img src=\"css/icons/key.png\" alt=\"\" />Select a Case</a>";
					$header_buttons_html .= "<li><a href=\"logout.php\"><img src=\"css/icons/door_out.png\" alt=\"\" />Logout</a>";
			} else {
					$header_buttons_html .= "<li><a href=\"/genetics/main/index.php\"><img src=\"css/icons/door_in.png\" alt=\"\" />Home</a>";
			}
			$header_buttons_html .= "</ul></span>";

			if ($s_mod > 0) {
				$caseinfo_html =  "<span class=caseinfo>";
				$caseinfo_html .=  "<table><tr>";
				if ($s_grp_type == 'G') $grp_hdr = "<br>$s_grp_txt"; else $grp_hdr = "";
				$caseinfo_html .= "<td>$module_row->mod_name $grp_hdr <br>$s_usr_name</td>";
				$caseinfo_html .= "<td><img src=$s_path/img/patient_sm.jpg class=top></td>";
				$caseinfo_html .= "</tr></table>";
				$caseinfo_html .= "</span>";
			} else $caseinfo_html = "";

$template_html = '';
$template_html .= <<<htmleoq

<html lang="en">
  <head>
    <meta charset="utf-8">
	<title>Health Education through Active Learning</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="/src/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="/src/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

<!--CSS source files for the entire YUI Library-->
<!--CSS Foundation: (also partially aggegrated in reset-fonts-grids.css; does not include base.css)-->
<link rel="stylesheet" type="text/css" href="$yui_path/build/reset/reset-min.css">
<link rel="stylesheet" type="text/css" href="$yui_path/build/fonts/fonts-min.css">
<link rel="stylesheet" type="text/css" href="$yui_path/build/grids/grids-min.css">

<!--CSS for Controls:-->
<link rel="stylesheet" type="text/css" href="$yui_path/build/container/assets/skins/sam/container.css">
<!--<link rel="stylesheet" type="text/css" href="$yui_path/build/menu/assets/skins/sam/menu.css"> -->
<link rel="stylesheet" type="text/css" href="$yui_path/build/menu/assets/menu.css">
<link rel="stylesheet" type="text/css" href="$yui_path/build/autocomplete/assets/skins/sam/autocomplete.css">
<link rel="stylesheet" type="text/css" href="$yui_path/build/button/assets/skins/sam/button.css">
<link rel="stylesheet" type="text/css" href="$yui_path/build/calendar/assets/skins/sam/calendar.css">
<link rel="stylesheet" type="text/css" href="$yui_path/build/carousel/assets/skins/sam/carousel.css">
<link rel="stylesheet" type="text/css" href="$yui_path/build/colorpicker/assets/skins/sam/colorpicker.css">
<link rel="stylesheet" type="text/css" href="$yui_path/build/datatable/assets/skins/sam/datatable.css">
<link rel="stylesheet" type="text/css" href="$yui_path/build/editor/assets/skins/sam/editor.css">
<link rel="stylesheet" type="text/css" href="$yui_path/build/imagecropper/assets/skins/sam/imagecropper.css">
<link rel="stylesheet" type="text/css" href="$yui_path/build/layout/assets/skins/sam/layout.css">
<link rel="stylesheet" type="text/css" href="$yui_path/build/paginator/assets/skins/sam/paginator.css">
<link rel="stylesheet" type="text/css" href="$yui_path/build/resize/assets/skins/sam/resize.css">
<link rel="stylesheet" type="text/css" href="$yui_path/build/tabview/assets/skins/sam/tabview.css">
<link rel="stylesheet" type="text/css" href="$yui_path/build/treeview/assets/skins/sam/treeview.css">


<!--JavaScript source files for the entire YUI Library:-->

<!--YUI Core (also aggregated in yahoo-dom-event.js; see readmes in the
YUI download for details on each of the aggregate files and their contents):-->
<script type="text/javascript" src="$yui_path/build/yahoo/yahoo-min.js"></script>
<script type="text/javascript" src="$yui_path/build/dom/dom-min.js"></script>
<script type="text/javascript" src="$yui_path/build/event/event-min.js"></script>

<!--Utilities (also partialy aggregated utilities.js; see readmes in the
YUI download for details on each of the aggregate files and their contents):-->
<script type="text/javascript" src="$yui_path/build/element/element-beta-min.js"></script>
<script type="text/javascript" src="$yui_path/build/animation/animation-min.js"></script>
<script type="text/javascript" src="$yui_path/build/connection/connection-min.js"></script>
<script type="text/javascript" src="$yui_path/build/cookie/cookie-min.js"></script>
<script type="text/javascript" src="$yui_path/build/datasource/datasource-min.js"></script>
<script type="text/javascript" src="$yui_path/build/dragdrop/dragdrop-min.js"></script>
<script type="text/javascript" src="$yui_path/build/get/get-min.js"></script>
<script type="text/javascript" src="$yui_path/build/history/history-min.js"></script>
<script type="text/javascript" src="$yui_path/build/imageloader/imageloader-min.js"></script>
<script type="text/javascript" src="$yui_path/build/json/json-min.js"></script>
<script type="text/javascript" src="$yui_path/build/resize/resize-min.js"></script>
<script type="text/javascript" src="$yui_path/build/selector/selector-beta-min.js"></script>
<script type="text/javascript" src="$yui_path/build/yuiloader/yuiloader-min.js"></script>

<!--YUI's UI Controls:-->
<script type="text/javascript" src="$yui_path/build/container/container-min.js"></script>
<script type="text/javascript" src="$yui_path/build/menu/menu-min.js"></script>
<script type="text/javascript" src="$yui_path/build/autocomplete/autocomplete-min.js"></script>
<script type="text/javascript" src="$yui_path/build/button/button-min.js"></script>
<script type="text/javascript" src="$yui_path/build/calendar/calendar-min.js"></script>
<script type="text/javascript" src="$yui_path/build/charts/charts-experimental-min.js"></script>
<script type="text/javascript" src="$yui_path/build/colorpicker/colorpicker-min.js"></script>
<script type="text/javascript" src="$yui_path/build/datatable/datatable-min.js"></script>
<script type="text/javascript" src="$yui_path/build/editor/editor-min.js"></script>
<script type="text/javascript" src="$yui_path/build/imagecropper/imagecropper-beta-min.js"></script>
<script type="text/javascript" src="$yui_path/build/layout/layout-min.js"></script>
<script type="text/javascript" src="$yui_path/build/pagiator/paginator-min.js"></script>
<script type="text/javascript" src="$yui_path/build/slider/slider-min.js"></script>
<script type="text/javascript" src="$yui_path/build/tabview/tabview-min.js"></script>
<script type="text/javascript" src="$yui_path/build/treeview/treeview-min.js"></script>
<script type="text/javascript" src="$yui_path/build/uploader/uploader-experimental-min.js"></script>

<!-- Internal CSS -->
	<link rel="stylesheet" media="screen" type="text/css" href="css/main.css">
	<link rel="stylesheet" media="print" type="text/css" href="css/print.css">
	$headerscripts
</head>

<div class="row-fluid">

   	<!-- header -->
	<div id="header">
    		<span class="span8"
				<a href="info_index.php"><img src="img/header.jpg" alt="Health Education through Active Learning" class="logo"></a>
			</span>
			<span class="span2">
				$header_buttons_html
			</span>
			<span class="span2">
				$caseinfo_html
			</span>
	</div>
</div>
	<!-- end header -->

<div class="row-fluid">
	<span class="span8">
		<div id="content">
			$body
		</div>
	</span>

	<span class="span3" id="mainrightdiv">
		$toolmenu
	</span>
</div>


   	<!-- footer -->
<div class="row-fluid">
	<div id="footerContainer" class="span12">
	<div id="footerDiv">
		<div id="footerL">
		<a href="http://www.umich.edu" target="_blank"><img src="img/WM_UMSOD_noline.png" alt="University of Michigan" id="umlogo" /></a>

		</div>

		<a href="http://creativecommons.org/licenses/by/3.0" target="_blank"><img src="img/CCby.gif" alt="Creative Commons" id="cclogo" /></a>
		<p class="cc">
			Except where otherwise noted, content on this site is licensed under a <a href="http://creativecommons.org/licenses/by/3.0" target="_blank">Creative Commons Attribution 3.0 License</a>.
			<!--<br />
			Copyright &copy; 2008 <a href="http://regents.umich.edu" target="_blank">The Regents of the University of Michigan</a>-->
		</p>

	</div>
	</div>
<!-- end footer -->
</div>

<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-12454123-1");
pageTracker._trackPageview();
} catch(err) {}</script>

</body>
</html>
htmleoq;

echo $template_html;
?>
