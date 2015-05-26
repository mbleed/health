<?php 
$security_needed = 1; 
include './security_check.php';

//include topmenu, is stored in the $topmenu variable
include ('./topmenu.php');
$body .= $topmenu;

//include rightside menu, is stored in the $toolmenu variable
include ('./toolmenu.php');

if ($row = $db->get_row("SELECT * FROM caries WHERE grp_id = $s_grp ORDER BY whenset DESC")) {
	$cariesrisk = $row->risk;
} else {
	$cariesrisk = '';
}

$lowcariesrisk = ($cariesrisk == 'Low') ? 'selected' : '';
$medcariesrisk = ($cariesrisk == 'Medium') ? 'selected' : '';
$highcariesrisk = ($cariesrisk == 'High') ? 'selected' : '';

$body .= <<<eoq

<div id="actionbox">

<div id=step>
<form action="dd_caries_save.php" method="post">
<h1>Assess the patient's caries risk</h1>
<select id="cariesrisk" name="cariesrisk">
	<option value="">Choose one...</option>
	<option value="Low" $lowcariesrisk>Low</option>
	<option value="Medium" $medcariesrisk>Medium</option>
	<option value="High" $highcariesrisk>High</option>
</select>
<br /><br />
<input type="submit" name="caries_submit" value="Save" />
</form>
</div>

<script>
document.getElementById("doc3").className = document.getElementById("doc3").className.replace(/\byui-t5\b/,'')
</script>

<div id=step >
	<img src="../shared/AxiumCariesPage.png" />
</div>

</div>

eoq;
//end body

include './template.php'; //add in the standard page header 
?>