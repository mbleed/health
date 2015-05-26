<?php $security_needed = 0; ?>
<?php include "./classes/ez_sql.php"; //include db class ?>
<?php include "gedSession.php"; //include start session and extract of session variables with prefix of s_ ?>
<?php $s_mod = 0; //set module to zero so no header info shows up ?>
<?php include './security_check.php'; ?>
<?php include './header.php'; //add in the standard page header ?>

<div id=actionbox>

<h2>Student Reports</h2>

<div id=step>

<?php 
	$mod = $_GET['mod'];
	if ($mod == '') {
		echo "<h1>Choose a module.</h1>";
		$mods = $db->get_results("SELECT * FROM module WHERE mod_status = 1 ORDER BY mod_order");
		foreach ($mods as $mod) {
    			echo "<h3><a href=reports.php?mod=$mod->mod_id><img src=\"../$mod->mod_path/img/patient_sm.jpg\"> $mod->mod_name</a></h3>";
		}
	} else {
		echo "<h1>Choose a report.</h1>";
		$delimiters = array("m","_","g",".");
		if ($handle = opendir("../reports")) {
			while (false !== ($file = readdir($handle))) {
      		 	if ($file != "." && $file != "..") {
					$a = split('[m_g.]',$file);
					if (($a[1] == $mod) AND ($a[4] == "pdf")) { //filter out this modules reports only
	           				$reports[] = "$file";
					}
       			}
   			}
   			closedir($handle);
		}
		echo "<table border=1 cellpadding=3 cellspacing=3>";
		$i = 1;
		foreach ($reports as $r) {
			if ($i == 1) echo "<tr>";
			$a = split('[m_g.]',$r);
			//echo "<pre>"; print_r($a); echo "</pre>";
			$grp_id = $a[3];
			$group = $db->get_var("SELECT grp_txt FROM groups WHERE grp_id = $grp_id");
			echo "<td>";
			echo "<h3>$group</h3>";
			echo "<a href=\"../reports/$r\"><img border=0 src=img/page_white_acrobat.png>PDF</a><br>";
			echo "<a href=\"../reports/$r\"><img border=0 src=img/page_white_text.png>Text</a><br>";
			echo "<a href=\"../reports/$r\"><img border=0 src=img/page_white_world.png>Html</a>";
			echo "</td>";
			if ($i == 8) { echo "</tr>"; $i = 0; }
			$i++;
		}
		echo "</table>";
	}
?>

</div>

<?php include 'footer.php'; //add in the standard page footer ?>