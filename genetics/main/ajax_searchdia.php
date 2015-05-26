<?php
$security_needed = 1; 
include './security_check.php';

$rawterms = $_GET['terms'];
$terms = explode(' ', $rawterms);
$where = "WHERE ";
foreach ($terms as $term) {
	$where .= "lower(dia_txt) LIKE lower('%$term%') OR ";
}
$where = substr($where, 0, -4); //remove last OR

$body .= "<ul>";
if ($diags = $db->get_results("SELECT * FROM diagnoses $where ORDER BY dia_subcode ASC")) {
	foreach ($diags as $d) {
		//if comma, the diagnoses could have multiple types, search glossary by main part.
		if (strpos($d->dia_txt, ',')) $searchterm = substr($d->dia_txt, 0, strpos($d->dia_txt, ','));
		else $searchterm = $d->dia_txt;

		//check if term is in internal glossary, if so add link
		if ($glossaryrow = $db->get_row("SELECT * FROM glossary WHERE lower(glo_term) = lower('$searchterm')")) {
			$glossarylink = "<a href=\"$glossaryrow->glo_url\" target=\"_blank\"><img class=middle src=\"img/link.gif\"></img></a>";
		} else $glossarylink = "";

		//check if term has a folder in the resource list, if so add link
		if ($resourcerow = $db->get_row("SELECT * FROM resource WHERE lower(res_txt) = lower('$searchterm') AND res_type='folder'")) {
			$resourcelink = "<a href=\"re_home.php?prt=$resourcerow->res_id\"><img class=middle src=\"img/resource.gif\"></img></a>";
		} else $resourcelink = "";

		$body .= "<li><a href=\"dd_add.php?dia=$d->dia_id\">$d->dia_txt<img class=middle src=img/add.gif></a> $glossarylink $resourcelink </li>\n";
	}
} else {
	$body .= "<li>No diagnoses found with that search term(s)</li>";
}
$body .= "</ul>";

echo $body;
?>