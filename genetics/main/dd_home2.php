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
			$mlquery = urlencode($d->dia_txt); 
			//$searchhtml .= "<a href=\"http://vsearch.nlm.nih.gov/vivisimo/cgi-bin/query-meta?v%3Aproject=medlineplus&query=$mlquery&x=0&y=0\">Definition</a> | ";
			$searchhtml .= "</li>";
		}
	} else $searchhtml .= "<h3>No Diagnoses found with those keywords</h3>";
	$searchhtml .= "</ul>";
} else {

$body .= <<<eoq

<script type="text/javascript">
var tree;
function loadNodeData(node, onCompleteCallback) {
	var AjaxGetDias = {
		handleSuccess:function(o){ 
			dias = o.responseText.split('|||'); 
			for (var i=0; i<dias.length; i++) {
				if (i%2) { bgcolor = 'DDD'; }
				else { bgcolor = 'FFFFFF'; }
				span_1 = '<span style="width: 400px; background-color: #'+bgcolor+';">';
				span_2 = '</span>';
				var newNode = new YAHOO.widget.HTMLNode(span_1+dias[i]+span_2, node, false, false);
			}
			onCompleteCallback();
		},
		handleFailure:function(o){ alert('Error retrieving diagnoses, try your request again.'); },
		startRequest:function(node_id) {
			params = 'id='+node_id;
		   	YAHOO.util.Connect.asyncRequest('POST', 'ajax_get_dias.php', AjaxGetDiasCallback, params);
		}
	};

	var AjaxGetDiasCallback = {
		success:AjaxGetDias.handleSuccess,
		failure:AjaxGetDias.handleFailure,
		scope: AjaxGetDias
	};
	var node_id = node.data.id;
	AjaxGetDias.startRequest(node_id);	
}

function buildTree() {
	tree = new YAHOO.widget.TreeView("diaResult");
	tree.setDynamicLoad(loadNodeData);
	var root = tree.getRoot();
	//add initial nodes for tree:

eoq;

  	$types = $db->get_results("SELECT * FROM diagnoses WHERE dia_parent = 0 ORDER BY dia_txt ASC");
      foreach ($types as $t) {
		$body .= "var treenode$t->dia_id = new YAHOO.widget.TextNode({label:\"$t->dia_txt\",id:\"$t->dia_id\"}, root, false); \n";
	}

$body .= <<<eoq

	tree.draw();
}

YAHOO.util.Event.onDOMReady(buildTree);
</script>

 	<script type="text/javascript" src="src/ext/adapter/ext/ext-base.js"></script>
 	<!-- ENDLIBS -->

    <script type="text/javascript" src="src/ext/ext-all.js"></script>

    <script type="text/javascript" src="src/ext/ColumnNodeUI.js"></script>
    <script type="text/javascript" src="src/ext/column-tree.js"></script>
    <link rel="stylesheet" type="text/css" href="src/ext/resources/css/ext-all.css" />        
    <link rel="stylesheet" type="text/css" href="src/ext/shared/examples.css" />
    <link rel="stylesheet" type="text/css" href="src/ext/column-tree.css" />


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
</div>

<div id=step>
	<h1>After researching the validity of each diagnosis, choose to accept or reject it.</h1>

eoq;

		if ($rows = $db->get_results("SELECT * FROM x_usr_grp x INNER JOIN (diagnoses_chosen INNER JOIN diagnoses ON dc_dia_id = dia_id) ON usr_id = dc_usr_id WHERE x.mod_id = $s_mod AND dc_mod_id = $s_mod AND grp_id = $s_grp AND dc_status = 0 ORDER BY dia_txt ASC")) {
			$body .= "<ul class=\"dia_ul\">";
			$body .= "<h2><img src=img/dd_und.gif class=middle> Still Researching These Diagnoses</h2>";
			foreach ($rows as $row) {
				$accept_link = "<span id=\"bAccept_$row->dc_id\" class=\"acceptbutton\"><span class=\"first-child\"><a href=dd_accept.php?dc=$row->dc_id>Accept</a></span></span>";
				$reject_link = "<span id=\"bReject_$row->dc_id\" class=\"rejectbutton\"><span class=\"first-child\"><a href=dd_reject.php?dc=$row->dc_id>Reject</a></span></span>";
				$delete_link = "<span id=\"bDelete_$row->dc_id\" class=\"deletebutton\"><span class=\"first-child\"><a href=dd_delete.php?dc=$row->dc_id>Delete</a></span></span>";
 				//$body .= "<li class=dia_li>$row->dia_txt <span id=\"bcDia\">$accept_link $reject_link $delete_link </span></li>";
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
			$body .= "<h2><img src=img/dd_good.gif class=middle> Accepted Diagnoses</h2>";
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
			$body .= "<h2><img src=img/dd_bad.gif class=middle> Rejected Diagnoses</h2>";
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