<?php 
$security_needed = 0; 
include './security_check_info.php';

//include topmenu, is stored in the $topmenu variable
include ('./info_topmenu.php');
$body .= $topmenu;

if ($_GET['submit']) {
	$searchterm = strtolower($_GET['searchterm']);
}

$body .= <<<eoq

<style>
#search_re {
	border: 1px solid #999988;
	background-color: #EFE1C3;
	color: #221111;
	padding: 0.25em;
	margin: 0.25em;
}
</style>

<div id="actionbox">

<h2>Online Resources</h2>  

<div id=step>
<h3>These resources are organized by concepts.  Search for a keyword or browse the list of concepts.</h3>
<form id="search_re" method=get action=info_re_home.php>
<h3>Search for: </h3>
<input type=text name=searchterm>
<input type=submit name=submit value="Search">
</form>
eoq;

	//get resources by selected tag
  	$tag = urldecode($_GET['tag']);
	if (strlen($tag) > 0) {
		$body .= "<h2>Tag: $tag</h2>";
		$links = $db->get_results("SELECT * FROM resource INNER JOIN resource_tags ON res_id = tag_res_id WHERE tag_txt = '$tag' ORDER BY res_txt ASC");
	} elseif (strlen($searchterm) > 0) {
		$body .= "<h2>Searched for: $searchterm</h2>";
		$links = $db->get_results("SELECT * FROM resource WHERE lower(res_txt) LIKE '%$searchterm%' OR lower(res_cite) LIKE '%$searchterm%' ORDER BY res_txt ASC");
	} else {
		$links = $db->get_results("SELECT * FROM resource WHERE res_txt = 'zzzzzzzzzzz' ORDER BY res_txt ASC");
	}
	$body .= "<ul>\n";
	foreach ($links as $link) {
	  	$link_url = "href=\"$link->res_path\" target=\"_blank\"";
	  	$icon = "img/icons/".rtrim($link->res_type).".gif";
		$res = $link->res_id;
		if ($s_usr == 7)	$addtaglink = "<span class=addtag id=addtag$res><input type=text id=res$res><span onClick='addTag($res);'><img class=middle src=img/add.gif>Add Tag</span></span>";
    		$body .= "<li> <a $link_url> <img src=$icon>$link->res_txt</a> $link->res_cite $addtaglink</li>\n";
  	}
  	$body .= "</ul>\n";
	$body .= "<br>";

	//list tag categories 
	$body .= "<ul id=taglist>";
	$body .= "<table><tr valign=top><td width=20%> <h3>Resources</h3> \n";
	$i = 1;
	$browsetags = $db->get_results("SELECT tag_txt, count(tag_txt) as cnt FROM resource_tags INNER JOIN resource ON tag_res_id = res_id WHERE res_category = 'resource' GROUP BY tag_txt ORDER by tag_txt ASC");
	foreach ($browsetags as $browsetag) {
		if ($i++ % 10 == 0) $body .= "</td><td width=20%>\n";
		$urltag = urlencode($browsetag->tag_txt);
		$body .= " <li><a class=tag href=info_re_home.php?tag=$urltag > $browsetag->tag_txt ($browsetag->cnt)</a> \n";
	}
	$body .= "</td><td width=20% style=\"border-left: 1px solid #ACC797; padding: 10px;\"> <h3>Information Tools</h3> ";
	$i = 1;
	$browsetags = $db->get_results("SELECT tag_txt, count(tag_txt) as cnt FROM resource_tags INNER JOIN resource ON tag_res_id = res_id WHERE res_category = 'tool' GROUP BY tag_txt ORDER by tag_txt ASC");
	foreach ($browsetags as $browsetag) {
		if ($i++ % 10 == 0) $body .= "</td><td width=20%> \n";
		$urltag = urlencode($browsetag->tag_txt);
		$body .= " <li><a class=tag href=info_re_home.php?tag=$urltag > $browsetag->tag_txt ($browsetag->cnt)</a> \n";
	}
	$body .= "</td></tr></table>";
	$body .= "</ul>";

$body .= <<<eoq

</div>

</div>
eoq;

include './template_info.php'; //add in the standard page header 
?>