<?php

//header buttons
$header_buttons_html = <<<htmleoq
	
	<button class="btn dropdown-toggle" data-toggle="dropdown">Logged in as: $s_usr_name <span class="caret"></span></button>	
	<ul class="dropdown-menu">

htmleoq;

	if ($s_usr > 0) {
		//$instructor_level = $db->get_row("SELECT * FROM instructor WHERE ins_usr_id = $s_usr");
		$header_buttons_html .= '<li><a href="usr_profile.php"><i class="icon-pencil"></i> Edit Profile</a></li>';
		$header_buttons_html .= '<li><a href="choose_module.php"><i class="icon-ok"></i> Select a Case</a></li>';
		$header_buttons_html .= '<li><a href="logout.php"><i class="icon-unlock"></i> Logout</a></li>';
	} else { 
		$header_buttons_html .= '<li><a href="index.php"><i class="icon-lock"></i> Login</a></li>';
	}
$header_buttons_html .= '</ul>';

	if ($s_mod > 0) {
		$caseinfo_html =  "<span class=caseinfo>";
		if ($s_grp_type == 'G') $grp_hdr = "<br>$s_grp_txt"; else $grp_hdr = "";
		$caseinfo_html .= "<img src=$s_path/img/patient_sm.jpg class='img-polaroid'>";
		$caseinfo_html .= "$module_row->mod_name $grp_hdr";
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
	<script src="/genetics/main/shared/js/jquery-1.8.2.min.js"></script>
    <link href="/genetics/main/shared/css/bootstrap.min.css" rel="stylesheet">
    <link href="/genetics/main/shared/css/bootstrap-responsive.min.css" rel="stylesheet">
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
	<script src="/genetics/main/shared/js/bootstrap.js"></script>
<!-- Internal CSS -->
<!--	<link rel="stylesheet" media="screen" type="text/css" href="/genetics/main/css/main.css"> -->
<!--	<link rel="stylesheet" media="print" type="text/css" href="/genetics/main/css/print.css"> -->
	$headerscripts
</head>

<body>

<div class="row-fluid">
   	<!-- header -->
	<div id="header">
    		<span class="span3"><a href="mod_index.php"><img src="/genetics/main/img/header-short.jpg" alt="Health Education through Active Learning" class="logo"></a>
			</span>
			<span class="span3">
				$caseinfo_html
			</span>
			<span class="span3">
				$header_buttons_html
			</span>
	</div>
</div>
	<!-- end header -->

	<!-- CONTENT section -->
<div class="row-fluid">
	<span class="span9">
		<div id="content">
			$body
		</div>
	</span>

	<span class="span3" id="mainrightdiv">
		$toolmenu
	</span>
</div>
	<!-- END CONTENT SECTION -->

   	<!-- footer -->
<div class="row-fluid">
	<div id="footerContainer" class="span12" style="background-color: #DDD; padding: 3px;">
		<span class="pull-left">
		<a href="http://www.umich.edu" target="_blank"><img src="/genetics/main/img/WM_UMSOD_noline.png" alt="University of Michigan" id="umlogo" /></a>
		</span>
		
		<span class="cc pull-right">
			<a href="http://creativecommons.org/licenses/by/3.0" target="_blank"><img src="/genetics/main/img/CCby.gif" alt="Creative Commons" id="cclogo" class="image-rounded" /></a>
			Except where otherwise noted, content on this site is licensed under a <a href="http://creativecommons.org/licenses/by/3.0" target="_blank">Creative Commons Attribution 3.0 License</a>.
		</span>
	</div>
</div>
<!-- end footer -->

<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-12454123-1");
pageTracker._trackPageview();
} catch(err) {}
</script>

</body>
</html>
htmleoq;

echo $template_html;
?>