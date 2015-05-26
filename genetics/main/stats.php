<?php 
$db->trace_all = true;
$security_needed = 0; 
include './security_check.php';

//include topmenu, is stored in the $topmenu variable
$topmenu = "<h1>Stats</h1>";
$body .= $topmenu;

$classes = $db->get_results("SELECT * from classes ORDER BY class_dt DESC");

$body .= <<<eoq

<style>
.box { 
	padding: 10px;
	margin: 10px;
	border: 1px solid #333333;
}
</style>

<div id="actionbox">  

eoq;

$usrcnt = $db->get_var("SELECT count(*) FROM usr");
$crcnt = $db->get_var("SELECT count(*) FROM case_report_old WHERE cr_status = 1") + $db->get_var("SELECT count(*) FROM case_report WHERE cr_status = 1");
$ntcnt = $db->get_var("SELECT count(*) FROM notepad") + $db->get_var("SELECT count(*) FROM photonotes");


$body .= <<<eoq

<div class=box>
<h1>Totals</h1>
<ul>
	<li>Users: $usrcnt</li>
	<li>Case Reports Completed: $crcnt</li>
	<li>Notes: $ntcnt</li>
</ul>
</div>

<div class="box">
	<h1>Classes</h1>
	<ul>

eoq;

	foreach ($classes as $c) {
		$class_cnt = 0;
		//unset(@$mod_names);
		$mod_names = array();
		$body .=  "<li><h2>$c->class_name - $c->class_dt</h2>";
		$gs = $db->get_results("SELECT * FROM groups WHERE grp_class_id = $c->class_id AND grp_type = 'G' ORDER BY grp_id ASC");
		$body .= "<ul>";
		foreach ($gs as $g) {
			$body .=  "<li>$g->grp_txt";
			$usrcnt = $db->get_var("SELECT count(*) FROM x_usr_grp WHERE grp_id = $g->grp_id");
			$ms = $db->get_results("SELECT mod_id FROM x_usr_grp WHERE grp_id = $g->grp_id");
			$body .=  " - <b>Students: $usrcnt</b>";
			$class_cnt += $usrcnt;
			//$db->debug();
			foreach($ms as $m) if ($m->mod_id != '') $mod_names[] = $db->get_var("SELECT mod_name FROM module WHERE mod_id = $m->mod_id");
			$body .= "</li>";
		}
		$body .= "</ul>";
		//echo "<pre>"; print_r($mod_names); echo "</pre>";
		$res = array_unique($mod_names);
		$body .= "<h3> Modules done: ".implode(", ",$res)."</h3>";
		$body .= "<h3>Total students: $class_cnt</h3>";
		$body .= "</li>";
	}

$body .= <<<eoq

	</ul>
</div>

<div class="box">
	<h1>Groups not in Classes</h1>
	<ul>

eoq;

	$gs = $db->get_results("SELECT * FROM groups WHERE grp_class_id IS NULL AND grp_type = 'G'");
	foreach ($gs as $g) {
			$body .=  "<li>$g->grp_txt";
			$usrcnt = $db->get_var("SELECT count(*) FROM x_usr_grp WHERE grp_id = $g->grp_id");
			$body .=  " - <b>Students: $usrcnt</b>";
			$body .= "</li>";
	}

$body .= <<<eoq

	</ul>
</div>
 
</div>
 
eoq;
//end body

include './template.php'; //add in the standard page header 
?>