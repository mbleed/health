<?php
$security_needed = 0; 
include 'security_check.php';

$s_mod = 0; //set module to zero so no header info shows up 

if ($cur_security_level == 0) {
$loginbox = <<<htmleoq

<div class="loginbox">	
<div>                                                   
<form id="loginform" name="loginform" method="get" onsubmit="return false;">
	<b>Login: </b>
	<label for=login> Username: </label>
	<input name="login" id="login" type="text">
	<label for="password"> Password: </label>
	<input name="password" id="password" type="password">
	<input class=button name=submitbutton onclick="checkLogin()" value="Login" type="submit">
	<span id="login_error_div" class="error"></span>
	<br />
	<b>OR</b> <a href="register.php">Register for a new account</a>.</b>
</form>
</div>
</div>

htmleoq;
} else $loginbox = "<h4>You are logged in, please <a href='choose_module.php'>choose a case</a>.</h4>";

$body .= <<<eoq

<script type="text/javascript">
//init tabs
var tabView = new YAHOO.widget.TabView('tabbox');

//init ajax post for login check
var AjaxLogin = {

	handleSuccess:function(o){
		this.processResult(o);
	},

	handleFailure:function(o){
		//alert(o.responseText);
		alert('Error processing Login, please contact healtheducation@umich.edu for assistance');
	},

	processResult:function(o){
		var response = o.responseText.split('|');
  		var status = response[0];
		var message = response[1];
  		if (status == 2) {
			document.getElementById('login_error_div').innerHTML = message;
			window.location="choose_module.php";
  		} else {
			document.getElementById('login_error_div').innerHTML = message;
		}
	},

	startRequest:function() {
		var params = 'usr_nm=' + document.loginform.login.value + '&usr_ps=' + document.loginform.password.value;
	   	YAHOO.util.Connect.asyncRequest('POST', 'ajax_login_check.php', callback, params);
	}

};

var callback =
{
	success:AjaxLogin.handleSuccess,
	failure:AjaxLogin.handleFailure,
	scope: AjaxLogin
};

function checkLogin() {
	AjaxLogin.startRequest();
}
</script>

<div id=actionbox>

<style>
.loginbox {
	background-color: #e6e6e6;
	margin: 5px;
	padding: 3px;
	border: 1px solid #6d7c82;
}

#loginform { 
	margin: 0px;
	padding: 0px;
}

#loginform label {
		width: 100px;
		font-size: 92%;
	}
#loginform input {
		margin: 1px;
		width: 100px;
		font-size: 92%;
	}

.indexbox {
	font: small 'Lucida Grande', Arial, sans-serif; 
	color: #6d7c82;
	line-height: 1.6em;
	font-size: 108%;
	width: 800px;
	padding: 15px;
	margin: 5px;
}
.indexbox h2 {
	font-size: 122%;
	border-bottom: 1px dotted #999988;
}
.yui-nav li a, .yui-nav li a:visited { 
	font-size: 92%;
	font-weight: bolder;
	color: #6d7c82;
	text-decoration: none; 
	}
.yui-nav li a:hover { 
	text-decoration: none; 
	}

li.modlist {
	padding: 0.25em 0.25em;
	margin: 0.5em;
	border: 1px solid #999988;
	background-color: #EFEFEF;
	width: 600px;	
	/*float: left;*/
	display: block;
}
.modreview {
	padding-left: 50px;	
}
</style>

$loginbox

<div id="tabbox" class="yui-navset">

        <ul class="yui-nav">
            <li class="selected"><a href="#about"><em>About the Project</em></a></li>
            <li><a href="#manual"><em>Getting Started</em></a></li>
            <li><a href="#authors"><em>Case Authors</em></a></li>
            <li><a href="#designteam"><em>Design Team</em></a></li>
            <li><a href="#collaborators"><em>Collaborators</em></a></li>
            <li><a href="#board"><em>Advisory Board</em></a></li>
            <li><a href="#moreinfo"><em>For More Information</em></a></li>
        </ul>

<div class="yui-content">

<div class="indexbox" id="about"> 
<h2>Patient Case Simulator</h2>    
<img src="img/front.png" class="floatleft" style="margin-right: 10px;">  
<p>
Funding was provided by NIH/NIDCR Grant # 5R25 DE015350-02. For more information, please contact us at healtheducation@umich.edu.
</p>
<p>
<table>
<tr>
<td colspan=2 style="padding:8px 18px;" align="center">
	<a href="http://www.dent.umich.edu/media/academic-resources/video/genetics/GeneticsWebsiteTutorial.mov"><img src="img/screencap.png" /><br />How to guide for students</a>
</td>
</tr>
<td style="padding:8px 18px;" align="center">
	<a href="../shared/intro2.mov"><img src="../shared/introcap2.jpg" /><br />Why Case Based Learning?</a>
</td>
<td style="padding:8px 18px;" align="center">
	<a href="../shared/intro1.mov"><img src="../shared/introcap1.jpg" /><br />Why Genetics for Dentists?</a>
</td>
</tr>
</table>
</p>

<h2>Case Overviews</h2>
<p>The case overviews will present you with the patient in each case.  You will be able to view all the pertinent information for each case,
but will not be able to save any work.</p>
<ul style="list-style-type: disc; line-height: 1.5em; margin-left: 2em;">

eoq;

	$mods = $db->get_results("SELECT * FROM module WHERE mod_status = 1 ORDER BY mod_order");
	foreach ($mods as $mod) {
		$modreview = ($mod->mededportal_id > 0) ? "<a href=\"http://services.aamc.org/30/mededportal/servlet/s/segment/mededportal/find_resources/browse/?subid=$mod->mededportal_id\"><img src=\"img/mededportal.png\"></a>" : "";
      	$body .= <<<htmleoq
      		<li class="modlist" style="height: $li_height; overflow: auto;">
				<span><a href=info_index.php?m=$mod->mod_id><img src="../$mod->mod_path/img/patient_sm.jpg"> $mod->mod_name</a></span>
      			<span style="float: right;">$modreview</span>
      		</li>
      	
htmleoq;

	}

$body .= <<<eoq

</ul>
</div>

<div class="indexbox" id="manual"> 
<h2>User Manuals</h2>  
<ul>  
<li><a href="/docs/">Online User Manual</a>
<li>	<a href="http://www.dent.umich.edu/media/academic-resources/video/genetics/GeneticsWebsiteTutorial.mov">Video Tutorial <br/><img src="img/screencap.png" /></a>
</ul>
<h2 style="margin-top: 50px;">User Guides</h2>
<ul>
<li>User Guide <a href="UserGuide.pdf"><img src="./img/page_white_acrobat.png" />PDF</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="/docs/UserGuide.docx"><img src="./img/page_white_office.png" />Office</a>
<li>How to Register <a href="/docs/RegisterGuide.pdf"><img src="./img/page_white_acrobat.png" />PDF</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="/docs/RegisterGuide.docx"><img src="./img/page_white_office.png" />Office</a>
</ul>
</div>

<div class="indexbox" id="authors"> 
<h2>Case Authors:</h2>    
<li><strong>Jan C-C. Hu</strong> BDS PhD University of Michigan
<li><strong>James P. Simmer</strong> DDS, PhD University of Michigan
<li><strong>Rebecca Slayton</strong> DDS, PhD University of Iowa
<li><strong>Tae-Ju Oh</strong> DDS, MS University of Michigan
<li><strong>William Giannobile</strong> DDS, MS University of Michigan
<li><strong>Helen M. Sharp</strong> PhD Western Michigan University, University of Iowa
<li><strong>Christine P. Klausner</strong> RDH MS University of Michigan 
<li><strong>Lynn Johnson</strong> PhD University of Michigan
<li><strong>Amy E. Coplen</strong> RDH University of Michigan
</div>

<div class="indexbox" id="designteam"> 
<h2>Simulation Design Team:</h2>    
<li><strong>Christine P. Klausner</strong> RDH MS University of Michigan 
<li><strong>Carol Anne Murdoch-Kinch</strong> DDS PhD University of Michigan 
<li><strong>James P. Simmer</strong> DDS, PhD University of Michigan
<li><strong>Katherine Kelly</strong> DDS, PhD University of Michigan 
<li><strong>Ruxandra-Ana Iacob</strong> University of Michigan
<li><strong>Mike Bleed</strong> University of Michigan
<li><strong>Lynn Johnson</strong> PhD University of Michigan
<li><strong>Tom Green</strong> PhD University of Michigan 
<li><strong>Amy E. Coplen</strong> RDH University of Michigan
</div>

<div class="indexbox" id="collaborators"> 
<h2>Collaborators:</h2>    
<li><a href="http://www.nchpeg.org/">National Coalition for Health Professional Education in Genetics (NCHPEG)</a>
</div>

<div class="indexbox" id="board"> 
<h2>Advisory Board:</h2>    
<li><strong>Dr. Phyllis Beemsterboer</strong>  RDH, MS, EdD Associate Dean for Academic Affairs School of Dentistry, Oregon Health and Science University
<li><strong>Carl Berger</strong> MA, EdD Director of Advanced Academic Technology Collaboratory for Advanced Research and Academic Technologies (CARAT, Emeritus Professor and Dean)
<li><strong>Erin K. Harvey</strong> ScM Project Director, NCHPEG (National Coalition for Health Professional Education in Genetics)
<li><strong>Lynn Johnson</strong> PhD Assoc Professor, PPG, Director Dental Informatics, School of Dentistry, University of Michigan
<li><strong>Marilyn Lantz</strong> DMD, PhD Assoc Dean, Academic Affairs-Dentistry, Professor, School of Dentistry, University of Michigan
<li><strong>Carl Marrs</strong> MS, PhD Assoc Professor, Epidemiology Department School of Public Health, University of Michigan
<li><strong>Charles Shuler</strong> DMD, PhD Associate Dean, Academic Affairs; Director, Center for Craniofacial Molecular Biology University of Southern California School of Dentistry
</div>

<div class="indexbox" id="moreinfo"> 
<p>
Funding was provided by NIH/NIDCR Grant # 5R25 DE015350-02. For more information, please contact us at healtheducation@umich.edu.
</p>
<p>
<a href="../shared/brochure.pdf"><img src="img/brochure_thumb.jpg" align="middle" border="0"> Click here to view a printable brochure</a>
</p>
</div>


</div> <!-- end yui-content div -->
</div> <!-- end tabbox div -->

</div> <!-- end actionbox div -->

eoq;
//end body

include './template.php'; //add in the standard page header 
?>