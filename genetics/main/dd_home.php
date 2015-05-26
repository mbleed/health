<?php 
$security_needed = 1; 
include './security_check.php';

//include topmenu, is stored in the $topmenu variable
include ('./topmenu.php');
$body .= $topmenu;

$dc_view = $_GET['dc_view']; 

//include rightside menu, is stored in the $toolmenu variable
include ('./toolmenu.php');

if ($_POST['submitSearch']) {
	$searchtermsraw = $_POST['searchTerms'];
	$searchterms = explode(" ", $searchtermsraw);
	$sql = "SELECT * FROM diagnoses WHERE ";
	foreach ($searchterms as $t) {
		$lt = strtolower($t);
		$sql .= "lower(dia_txt) LIKE '%$lt%' OR ";
	}
	$sql = substr($sql, 0, -3);  //remove last OR
	$sql .= " ORDER BY dia_txt ASC";
	$searchhtml .= "<ul class=\"dia_ul\">";
	if ($dias = $db->get_results($sql)) {
		foreach ($dias as $d) {
			$searchhtml .= "<li class=dia_li>";
			//create searchterm lowercase and just main search term before commas
			if (strpos($d->dia_txt, ',')) $searchterm = strtolower(substr($d->dia_txt, 0, strpos($d->dia_txt, ',')));
			else $searchterm = strtolower($d->dia_txt);
			//check if term has a folder in the resource list, if so add link
			if ($resourcerow = $db->get_row("SELECT * FROM resource WHERE lower(res_txt) LIKE '%$searchterm%'")) {
				$resourcelink = "<a href=\"re_home.php?searchterm=$searchterm&submit=Search\">Look Up In Resources<img class=middle src=\"img/resource.gif\" /></a>";
			} else $resourcelink = "";
			$searchhtml .= "$d->dia_txt | <a href=\"dd_add.php?dia=$d->dia_id\">Add<img class=middle src=img/add.gif></a> | ";
			if (strlen($d->dia_url) > 0) $searchhtml .= " <a href=\"$d->dia_url\" target=\"_blank\">More Info<img class=middle src=\"img/link.gif\" /></a> | ";
			if (strlen($resourcelink) > 0) $searchhtml .= " $resourcelink | ";
			$searchhtml .= "</li>";
		}
	} else $searchhtml .= "<h3>No Diagnoses found with those keywords</h3>";
	$searchhtml .= "</ul>";
} else {

$dia_js .= <<<eoq

<script type="text/javascript">
(function() {
	(new YAHOO.widget.TreeView("DDtree",[

eoq;

  	$types = $db->get_results("SELECT * FROM diagnoses WHERE dia_parent = 0 ORDER BY dia_txt ASC");
      	foreach ($types as $t) {
		$dia_js .= <<<eoq
			{type:'Text', label:'$t->dia_txt', title:'$t->dia_txt', expanded:false, children:[

eoq;
		if ($dias = $db->get_results("SELECT * FROM diagnoses WHERE dia_parent = $t->dia_id ORDER BY dia_txt ASC")) {
			$dia_html_a = array();
			foreach ($dias as $d) {
				$dia_html = "";
				//create searchterm lowercase and just main search term before commas
				if (strpos($d->dia_txt, ',')) $searchterm = strtolower(substr($d->dia_txt, 0, strpos($d->dia_txt, ',')));
				else $searchterm = strtolower($d->dia_txt);
				//check if term has a folder in the resource list, if so add link
				if ($resourcerow = $db->get_row("SELECT * FROM resource WHERE lower(res_txt) LIKE '%$searchterm%'")) {
					$resourcelink = "<a href=\"re_home.php?searchterm=$searchterm&submit=Search\">To Resources<img class=middle src=\"img/resource.gif\" /></a>";
				} else $resourcelink = "";
			
				$dia_html .= $db->escape($d->dia_txt);
				$dia_html .= " | <a href=\"dd_add.php?dia=$d->dia_id\">Add<img class=middle src=img/add.gif></a> | ";
				if (strlen($d->dia_url) > 0) $dia_html .= " <a href=\"$d->dia_url\" target=\"_blank\">More Info<img class=middle src=\"img/link.gif\" /></a> | ";
				if (strlen($resourcelink) > 0) $dia_html .= " $resourcelink | ";
				$dia_html_a[] = "'$dia_html'";
			}
			$dia_html_full = implode(",", $dia_html_a);
			$dia_js .= $dia_html_full." \n";
			unset($dia_html_a);
		}
		$dia_js .= "]},";
	}
	$dia_js = substr($dia_js,0,-1); //remove trailing comma
	

$dia_js .= <<<eoq
			
	])).render();
}
)();
</script>

eoq;

}

$body .= <<<eoq

<style>
	.dia_ul {
		list-style-type: none;
		font-size: 115%;
		margin: 0.25em;
		padding: 0.5em;
		background-color: #DDDDDD;
		color: #221111;
		border: 1px solid #998888;
	}
	.dia_li {
		margin: 0.25em;
		padding: 0.5em;
		background-color: #EFE1C3;
		color: #221111;
		border: 1px solid #999988;
	}
	.acceptbutton .first-child a {
		padding-left:2em;
 		background:url(img/add.gif) 10% 50% no-repeat;      
	}
	.rejectbutton .first-child a {
		padding-left:2em;
 		background:url(img/remove.gif) 10% 50% no-repeat;      
	}
	.deletebutton .first-child a {
		padding-left:2em;
 		background:url(img/dd_bad.gif) 10% 50% no-repeat;      
	}
</style>

<div id="actionbox">

<div id=step>
<h1>Browse the categories for possible diagnoses and click the <img class=middle src=img/add.png> to add it to your list.</h1>
<div id=search>
	<form name="search_dias" id="search_dias" class="search_form" method="post">
	<h3>Search the diagnoses: <input type="text" size="20" name="searchTerms" id="searchTerms">
	<input type="submit" name="submitSearch" value="Search"></h3>
	</form>
</div>
<div id="diaResult">$searchhtml</div>
<h3>Browse the Differential Diagnoses</h3>
<div id="DDtree"></div>
$dia_js
</div>

<div id=step>
	<h1>After researching the validity of each diagnosis, choose to accept or reject it.</h1>

eoq;

		if ($rows = $db->get_results("SELECT * FROM x_usr_grp x INNER JOIN (diagnoses_chosen INNER JOIN diagnoses ON dc_dia_id = dia_id) ON usr_id = dc_usr_id WHERE x.mod_id = $s_mod AND dc_mod_id = $s_mod AND grp_id = $s_grp AND dc_status = 0 ORDER BY dia_txt ASC")) {
			$body .= "<ul class=\"dia_ul\">";
			$body .= "<h3><img src=img/dd_und.gif class=middle> Still Researching These Diagnoses</h3>";
			foreach ($rows as $row) {
				$accept_link = "<span id=\"bAccept_$row->dc_id\" class=\"acceptbutton\"><span class=\"first-child\"><a href=dd_accept.php?dc=$row->dc_id>Accept</a></span></span>";
				$reject_link = "<span id=\"bReject_$row->dc_id\" class=\"rejectbutton\"><span class=\"first-child\"><a href=dd_reject.php?dc=$row->dc_id>Reject</a></span></span>";
				$delete_link = "<span id=\"bDelete_$row->dc_id\" class=\"deletebutton\"><span class=\"first-child\"><a href=dd_delete.php?dc=$row->dc_id>Delete</a></span></span>";
 				$body .= "<li class=dia_li>$row->dia_txt $accept_link $reject_link $delete_link</li>";
				//save up js
				$btn_js .= "var bAccept_$row->dc_id = new YAHOO.widget.Button(\"bAccept_$row->dc_id\"); \n";
				$btn_js .= "var bReject_$row->dc_id = new YAHOO.widget.Button(\"bReject_$row->dc_id\"); \n";
				$btn_js .= "var bDelete_$row->dc_id = new YAHOO.widget.Button(\"bDelete_$row->dc_id\"); \n";
			}
			$body .= "</ul>";
			$headerscripts .= <<<eoq

<script>
function load_dia_buttons() {
$btn_js
}
YAHOO.util.Event.onDOMReady(load_dia_buttons);
</script>

eoq;
		}

		if ($rows = $db->get_results("SELECT * FROM x_usr_grp x INNER JOIN (diagnoses_chosen INNER JOIN diagnoses ON dc_dia_id = dia_id) ON usr_id = dc_usr_id WHERE x.mod_id = $s_mod AND dc_mod_id = $s_mod AND grp_id = $s_grp AND dc_status = 2 ORDER BY dia_txt ASC")) {
			$body .= "<ul class=\"dia_ul\">";
			$body .= "<h3><img src=img/dd_good.gif class=middle> Accepted Diagnoses</h3>";
			foreach ($rows as $row) {
			  	if ($dc_view == $row->dc_id) {
 				 	$body .= "<li class=dia_li>$row->dia_txt <br>$row->dc_note<br><em>$row->dc_citation</em><br> [ <a href=dd_home.php>Compact View</a> ] | [ <a href=dd_edit.php?dc=$row->dc_id>Edit</a> ] </li>";
 				} else {
 				 	$body .= "<li class=dia_li>$row->dia_txt [ <a href=dd_home.php?dc_view=$row->dc_id>Full Rationale</a> ]</li>"; 
        			}
			}
			$body .= "</ul>";
		}

		if ($rows = $db->get_results("SELECT * FROM x_usr_grp x INNER JOIN (diagnoses_chosen INNER JOIN diagnoses ON dc_dia_id = dia_id) ON usr_id = dc_usr_id WHERE x.mod_id = $s_mod AND dc_mod_id = $s_mod AND grp_id = $s_grp AND dc_status = 1 ORDER BY dia_txt ASC")) {
			$body .= "<ul class=\"dia_ul\">";
			$body .= "<h3><img src=img/dd_bad.gif class=middle> Rejected Diagnoses</h3>";
			foreach ($rows as $row) {
 				if ($dc_view == $row->dc_id) {
 				 	$body .= "<li class=dia_li>$row->dia_txt <br>$row->dc_note<br><em>$row->dc_citation</em><br> [ <a href=dd_home.php>Compact View</a> ] | [ <a href=dd_edit.php?dc=$row->dc_id>Edit</a> ] </li>";
 				} else {
 				 	$body .= "<li class=dia_li>$row->dia_txt [ <a href=dd_home.php?dc_view=$row->dc_id>Full Rationale</a> ]</li>"; 
        		}
			}
			$body .= "</ul>";
		}

$body .= <<<eoq

</div>

</div>

eoq;
//end body

include './template.php'; //add in the standard page header 
?>