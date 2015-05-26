<?php
$security_needed = 1; 
include './security_check.php';

$id = $_POST['id'];

$dias = $db->get_results("SELECT * FROM diagnoses WHERE dia_parent = $id ORDER BY dia_txt ASC");
foreach ($dias as $d) {
	//create searchterm lowercase and just main search term before commas
	if (strpos($d->dia_txt, ',')) $searchterm = strtolower(substr($d->dia_txt, 0, strpos($d->dia_txt, ',')));
	else $searchterm = strtolower($d->dia_txt);
	//check if term has a folder in the resource list, if so add link
	if ($resourcerow = $db->get_row("SELECT * FROM resource WHERE lower(res_txt) LIKE '%$searchterm%'")) {
		$resourcelink = "<a href=\"re_home.php?searchterm=$searchterm&submit=Search\">To Resources<img class=middle src=\"img/resource.gif\" /></a>";
	} else $resourcelink = "";

	$body .= "$d->dia_txt | <a href=\"dd_add.php?dia=$d->dia_id\">Add<img class=middle src=img/add.gif></a> | ";
	if (strlen($d->dia_url) > 0) $body .= " <a href=\"$d->dia_url\" target=\"_blank\">More Info<img class=middle src=\"img/link.gif\" /></a> | ";
	if (strlen($resourcelink) > 0) $body .= " $resourcelink | ";
	$mlquery = urlencode($d->dia_txt); 
	//$body .= "<a href=\"http://vsearch.nlm.nih.gov/vivisimo/cgi-bin/query-meta?v%3Aproject=medlineplus&query=$mlquery&x=0&y=0\" target=\"_blank\">Definition</a> | ";

	$body .= " |||";
}
$body = substr($body, 0, -3);  //remove last delimiter

echo $body;
?>