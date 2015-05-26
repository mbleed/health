<?php 
$security_needed = 0; 
include './security_check.php';

$s_mod = 0; //set module to zero so no header info shows up 

if ($_SESSION['sec_level'] == 2) {	
$body .= <<<htmleoq

<div id="toolmenu">		
	<div id="toolsection">
	<div>
		<h1>Resources</h1>
		<ul>
			<li><a href="../../docs/manual-2-3.php">Curricular Suggestions</a>
			<li><a href="../../docs/manual-2-4.php">Case Overviews</a>
			<li><a href="../../docs/groupguide.pdf">Guide for Setting up Student Groups</a>
			<li><a href="../shared/Rubric.pdf">Grading Rubric</a>
		</ul>
	</div>
	</div>
</div>
	
htmleoq;
} else {
	$body .= <<<htmleoq

<div id="toolmenu">		
	<div id="toolsection">
	<div>
		<h1>Resources</h1>
		<ul>
			<li>	<a href="http://desica.dent.umich.edu/video/dent/GeneticsWebsiteTutorial.mov"><img src="img/screencap.png" /><br />How To guide for students</a>
		</ul>
	</div>
	</div>
</div>
	
htmleoq;
	
}

$body .= <<<eoq

<style>
.buttons a { padding: 2px 5px 3px 3px; }
</style>

<div id="actionbox">

eoq;

//check if demo account, supply link to wipe demo info off of database
if ($s_usr == 48 || $s_usr == 4) {
	include './democlean.php';	
}

$body .= <<<eoq

<style>
li.modlist {
	padding: 0.25em 0.25em;
	margin: 0.5em;
	border: 1px solid #999988;
	background-color: #EFEFEF;
	width: 400px;	
	/*float: left;*/
	display: block;
}
</style>

<div id="step" style="overflow: hidden;">
<h1>Choose a patient case.</h1>

<ul class="mod_ul">

eoq;

	$sql = ($s_usr == 48) ? "SELECT * FROM module ORDER BY mod_order" : "SELECT * FROM module WHERE mod_status = 1 ORDER BY mod_order";

	$mods = $db->get_results($sql);
	
	switch ($_SESSION['sec_level']) {
	case 1: //student
	foreach ($mods as $mod) {
		$li_height = 132;
		$docs_listing = "";
			//get group information for this user and module
			if ($x = $db->get_row("SELECT * FROM x_usr_grp x INNER JOIN groups g ON g.grp_id = x.grp_id WHERE x.usr_id = $s_usr AND x.mod_id = $mod->mod_id")) {
				//get case report if filed
				if ($cr = $db->get_row("SELECT * FROM case_report WHERE cr_grp_id = $x->grp_id AND cr_mod_id = $mod->mod_id AND cr_status = 1")) {
					$fn = $cr->cr_pdf;
					$docs_listing .= "<li><a href=\"$fn\"><img src=\"$icon_path/report_magnify.png\" alt=\"\" />View Case Report</a>";
					$li_height += 30;
				} else $docs_listing = "";

				$case_chooser = <<<htmleoq
				
				      	<p>
      						You have already started this case:
      						<br /> 
      						Group: $x->grp_txt 
      						<ul class="actions">
      							<li><a href="set_module.php?mod=$mod->mod_id"><img src="$icon_path/page_white_edit.png" alt="" />Resume Case</a>
      							$docs_listing
      						</ul>
      					</p>
      					
htmleoq;
			} else {
				$case_chooser = <<<htmleoq
	
					<ul class="actions">
						<li><a href="info_index.php?m=$mod->mod_id"><img src="$icon_path/page_white_magnify.png" alt="" />Preview Case</a>
      					<li><a href="set_module.php?mod=$mod->mod_id"><img src="$icon_path/page_white_key.png" alt="" />Start Case</a>	
					</ul>
htmleoq;
			}
			
			$mededportal = ($mod->mededportal_id > 0) ? "<a href=\"http://services.aamc.org/30/mededportal/servlet/s/segment/mededportal/find_resources/browse/?subid=$mod->mededportal_id\"><img src=\"img/mededportal.png\"></a>" : "";


			$body .= <<<htmleoq
		
		<li class="modlist" style="height: $li_height; overflow: auto;">
			<h3>$mod->mod_name</h3>
			<table border=0>
				<tr>
					<td><img src="../$mod->mod_path/img/patient_sm.jpg"></td>
					<td>
						$case_chooser
      				</td>
      				      				<td>
      					$mededportal
      				</td>
      			</tr>
      		</table>
		</li>

htmleoq;
	} //end foreach mod
	break; //end case sec_level = 1
	
	case 2: //instructor
	
		foreach ($mods as $mod) {
				$li_height = 160;
				//get group information for this user and module
				if ($x = $db->get_row("SELECT * FROM x_usr_grp x INNER JOIN groups g ON g.grp_id = x.grp_id WHERE x.usr_id = $s_usr AND x.mod_id = $mod->mod_id")) {
					$start_case = <<<htmleoq
					
					      	<li>
	      						You have already started this case:
	      						<br /> 
	      						Group: $x->grp_txt 
	      						<br />
	      						<a href=set_module.php?mod=$mod->mod_id><img src="$icon_path/page_white_edit.png" alt="" />Resume Case</a>
	      					</li>
	      					
htmleoq;
				} else {
					$start_case = <<<htmleoq
		
							<li><a href="info_index.php?m=$mod->mod_id"><img src="$icon_path/page_white_magnify.png" alt="" />Preview Case</a></li>
	      					<li><a href="set_module.php?mod=$mod->mod_id"><img src="$icon_path/page_white_key.png" alt="" />Start Case</a>	</li>
						
htmleoq;
				}
				$sa_path = "../".$mod->mod_path."/sampleassignment/sampleassignment";
				if (file_exists($sa_path.".pdf")) {
					$sa_link = <<<htmleoq
						<li><a href="$sa_path.pdf"><img src="$icon_path/page_white_acrobat.png" />Sample Assignment - PDF</a></li>
htmleoq;
					$li_height += 30;
				} else $sa_link = "";

				$case_chooser = <<<htmleoq
				
      						<ul class="actions">
      							$start_case
      							<li><a href="class_detail.php?mod=$mod->mod_id"><img src="$icon_path/wrench.png" alt="" />Instructor's Toolkit</a></li>
      							<li><a href="setup_groups.php"><img src="$icon_path/group.png" alt="" />Setup Classes/Groups</a></li>
      							<li><a href="../$mod->mod_path/casereport/casereport.pdf"><img src="$icon_path/report.png" alt="" />Expert Case Report</a></li>
      							$sa_link
      						</ul>
      					
htmleoq;

			$body .= <<<htmleoq
			
		<li class="modlist" style="height: $li_height; overflow: auto;">
			<h3>$mod->mod_name</h3>
			<table border=0>
				<tr>
					<td><img src="../$mod->mod_path/img/patient_sm.jpg"></td>
					<td>
						$case_chooser
      				</td>
      			</tr>
      		</table>
		</li>

htmleoq;
	} //end foreach mod
	break;
	
} //end switch

$body .= <<<eoq

	</ul>

</div>

</div>

eoq;
//end body

include './template.php'; //create (render) webpage 
?>